<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class EnterpriseMaintenanceModeTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $superAdminUser;
    protected $supervisorUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        // Retrieve seeded users
        $this->adminUser = User::where('email', 'admin@solcon.com')->first();
        $this->supervisorUser = User::where('email', 'supervisor@solcon.com')->first();

        // Create a super-admin user
        $superAdminRole = Role::firstOrCreate([
            'slug' => 'super-admin',
            'name' => 'Super Administrator',
            'description' => 'Solcon Super Administrator with root settings control.',
        ]);

        $this->superAdminUser = User::factory()->create([
            'email' => 'superadmin@solcon.com',
            'name' => 'Super Admin User',
            'is_active' => true,
        ]);
        $adminRole = Role::where('slug', 'admin')->first();
        $this->superAdminUser->roles()->sync([$adminRole->id, $superAdminRole->id]);

        // Configure default maintenance settings
        SettingService::set('maintenance_mode', 'disable');
        SettingService::set('maintenance_password', Hash::make('admin123'));
        SettingService::set('maintenance_title', 'System Under Maintenance');
        SettingService::set('maintenance_message', 'Downtime Message');
        SettingService::set('maintenance_downtime', '2 hours');
        SettingService::set('maintenance_contact', 'support@solcon.com');
    }

    public function test_maintenance_is_off_by_default_and_access_is_allowed(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($this->supervisorUser)->get('/supervisor/dashboard');
        $response->assertStatus(200);
    }

    public function test_when_maintenance_is_on_normal_users_get_503(): void
    {
        SettingService::set('maintenance_mode', 'enable');

        // Admin (who is not super-admin) gets 503
        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');
        $response->assertStatus(503);
        $response->assertSee('System Under Maintenance');
        $response->assertSee('Downtime Message');

        // Supervisor gets 503
        $response = $this->actingAs($this->supervisorUser)->get('/supervisor/dashboard');
        $response->assertStatus(503);
    }

    public function test_secret_url_deactivates_and_bypasses_maintenance(): void
    {
        SettingService::set('maintenance_mode', 'enable');

        // Without entering secret URL, super admin gets 503
        $response = $this->actingAs($this->superAdminUser)->get('/admin/dashboard');
        $response->assertStatus(503);

        // Accessing secret URL /admin/admin123 deactivates maintenance and unlocks system
        $response = $this->actingAs($this->superAdminUser)->get('/admin/admin123');
        $response->assertRedirect();
        $this->assertEquals('disable', SettingService::get('maintenance_mode'));
    }

    public function test_unlocked_session_bypasses_maintenance(): void
    {
        SettingService::set('maintenance_mode', 'enable');

        // Without unlock session, supervisor gets 503
        $response = $this->actingAs($this->supervisorUser)->get('/supervisor/dashboard');
        $response->assertStatus(503);

        // With unlock session, supervisor gets access
        $response = $this->actingAs($this->supervisorUser)
            ->withSession(['maintenance_unlocked' => true])
            ->get('/supervisor/dashboard');
        $response->assertStatus(200);
    }

    public function test_unlock_page_is_accessible_during_maintenance(): void
    {
        SettingService::set('maintenance_mode', 'enable');

        $response = $this->get('/unlock');
        $response->assertStatus(200);
        $response->assertSee('Unlock Bypass');
    }

    public function test_unlock_with_correct_password_sets_session(): void
    {
        SettingService::set('maintenance_mode', 'enable');

        $response = $this->post('/unlock', [
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('maintenance_unlocked', true);
    }

    public function test_unlock_with_incorrect_password_fails(): void
    {
        SettingService::set('maintenance_mode', 'enable');

        $response = $this->from('/unlock')->post('/unlock', [
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/unlock');
        $response->assertSessionHasErrors('password');
        $response->assertSessionMissing('maintenance_unlocked');
    }

    public function test_unlock_brute_force_is_rate_limited(): void
    {
        SettingService::set('maintenance_mode', 'enable');
        $throttleKey = 'maintenance_unlock_attempts:127.0.0.1';
        RateLimiter::clear($throttleKey);

        // Fail 5 times
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/unlock', [
                'password' => 'wrong-password',
            ]);
            $response->assertSessionHasErrors('password');
        }

        // 6th attempt should be blocked by rate limiter
        $response = $this->post('/unlock', [
            'password' => 'wrong-password',
        ]);
        $response->assertSessionHasErrors('password');
        $this->assertTrue(RateLimiter::tooManyAttempts($throttleKey, 5));
    }
}
