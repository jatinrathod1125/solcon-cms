<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\Machine;
use App\Models\Color;
use App\Models\GroutProductionBatch;
use App\Models\Notification as DbNotification;
use App\Models\UserDevice;
use App\Services\FirebaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FirebaseNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $groutSupervisor;
    protected $adhesiveSupervisor;
    protected $groutDept;
    protected $adhesiveDept;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $adminRole = Role::where('slug', 'admin')->first();
        $supervisorRole = Role::where('slug', 'supervisor')->first();

        $this->groutDept = Department::where('code', 'GRT')->first();
        $this->adhesiveDept = Department::where('code', 'TAD')->first();

        // Create Users
        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin_test@solcon.com',
            'is_active' => true,
        ]);
        $this->adminUser->roles()->attach($adminRole);

        $this->groutSupervisor = User::factory()->create([
            'name' => 'Grout Supervisor',
            'email' => 'grout_test@solcon.com',
            'department_id' => $this->groutDept->id,
            'is_active' => true,
        ]);
        $this->groutSupervisor->roles()->attach($supervisorRole);
        $this->groutSupervisor->departments()->attach($this->groutDept);

        $this->adhesiveSupervisor = User::factory()->create([
            'name' => 'Adhesive Supervisor',
            'email' => 'adhesive_test@solcon.com',
            'department_id' => $this->adhesiveDept->id,
            'is_active' => true,
        ]);
        $this->adhesiveSupervisor->roles()->attach($supervisorRole);
        $this->adhesiveSupervisor->departments()->attach($this->adhesiveDept);
    }

    /**
     * Test registering and removing device tokens via API endpoints.
     */
    public function test_device_registration_and_deregistration()
    {
        $this->actingAs($this->groutSupervisor);

        // 1. Register device token
        $response = $this->postJson(route('notifications.devices.register'), [
            'device_token' => 'fcm-test-token-123',
            'browser_name' => 'Chrome',
            'platform' => 'Windows',
            'device_name' => 'Desktop'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $this->assertDatabaseHas('user_devices', [
            'user_id' => $this->groutSupervisor->id,
            'device_token' => 'fcm-test-token-123',
            'browser_name' => 'Chrome',
        ]);

        // 2. Remove device token
        $response = $this->deleteJson(route('notifications.devices.remove'), [
            'device_token' => 'fcm-test-token-123'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $this->assertDatabaseMissing('user_devices', [
            'device_token' => 'fcm-test-token-123'
        ]);
    }

    /**
     * Test granular department notifications and permissions.
     */
    public function test_granular_department_notifications()
    {
        // Mock Firebase Service
        $this->mock(FirebaseService::class, function ($mock) {
            $mock->shouldReceive('sendNotification')->andReturn(true);
        });

        // Register devices for supervisors
        UserDevice::create([
            'user_id' => $this->groutSupervisor->id,
            'device_token' => 'grout-token',
        ]);

        UserDevice::create([
            'user_id' => $this->adhesiveSupervisor->id,
            'device_token' => 'adhesive-token',
        ]);

        $notificationService = app(\App\Services\NotificationService::class);

        // Send a Grout department notification
        $notificationService->sendToDepartment(
            'GRT',
            'Grout Notice',
            'Grout message body',
            'grout_mixing_complete'
        );

        // Grout Supervisor should have a notification entry logged for them
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->groutSupervisor->id,
            'title' => 'Grout Notice',
            'status' => 'sent'
        ]);

        // Adhesive Supervisor should NOT have a notification logged for them
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->adhesiveSupervisor->id,
            'title' => 'Grout Notice'
        ]);
    }

    /**
     * Test mixing timer completion command triggers notifications correctly.
     */
    public function test_check_mixing_timers_console_command()
    {
        // Mock Firebase Service
        $this->mock(FirebaseService::class, function ($mock) {
            $mock->shouldReceive('sendNotification')->andReturn(true);
        });

        // Register device for Grout Supervisor
        UserDevice::create([
            'user_id' => $this->groutSupervisor->id,
            'device_token' => 'grout-token',
        ]);

        // Create a Grout Batch in Timer Running state that has reached 0
        $machine = Machine::firstOrCreate([
            'code' => 'M-01',
        ], [
            'name' => 'Mixer M-01',
            'department_id' => $this->groutDept->id,
            'is_active' => true,
        ]);

        $color = Color::first() ?? Color::create([
            'code' => 'WHT',
            'name' => 'Super White',
            'department_id' => $this->groutDept->id,
            'is_active' => true,
            'packing_size' => '1 KG',
            'default_cement' => 10,
        ]);

        $batch = GroutProductionBatch::create([
            'batch_no' => 'GRT-2026-0001',
            'machine_id' => $machine->id,
            'color_id' => $color->id,
            'grout_formula_id' => 1,
            'formula_snapshot' => [],
            'operator_id' => $this->groutSupervisor->id,
            'status' => 'Timer Running',
            'timer_start_time' => now()->subHour(),
            'timer_end_time' => now()->subMinute(), // 1 minute in the past
        ]);

        // Run console command
        $this->artisan('grout:check-timers')
            ->assertExitCode(0);

        // Notification should be logged
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->groutSupervisor->id,
            'type' => 'grout_mixing_complete',
            'title' => 'Mixing Complete'
        ]);

        // Running it again should NOT create duplicate notifications
        $this->artisan('grout:check-timers')
            ->assertExitCode(0);

        $notifCount = DbNotification::where('type', 'grout_mixing_complete')
            ->where('user_id', $this->groutSupervisor->id)
            ->count();

        $this->assertEquals(1, $notifCount);
    }
}
