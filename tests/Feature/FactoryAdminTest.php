<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class FactoryAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('admin.settings.factory'))->assertRedirect('/login');
        $this->get(route('admin.profile.edit'))->assertRedirect('/login');
        $this->get(route('admin.activity-logs'))->assertRedirect('/login');
        $this->get(route('admin.backups.index'))->assertRedirect('/login');
        $this->get(route('admin.system.health'))->assertRedirect('/login');
    }

    public function test_supervisors_are_forbidden_from_admin_modules(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();

        $this->actingAs($supervisor)->get(route('admin.settings.factory'))->assertStatus(403);
        $this->actingAs($supervisor)->get(route('admin.activity-logs'))->assertStatus(403);
        $this->actingAs($supervisor)->get(route('admin.backups.index'))->assertStatus(403);
        $this->actingAs($supervisor)->get(route('admin.system.health'))->assertStatus(403);
    }

    public function test_admin_can_access_and_save_factory_settings(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        // Get view
        $response = $this->actingAs($admin)->get(route('admin.settings.factory'));
        $response->assertStatus(200);
        $response->assertSee('Solcon Factory Settings Console');

        // Post settings updates
        $responsePost = $this->actingAs($admin)->post(route('admin.settings.factory.update'), [
            // Factory Settings
            'company_name' => 'Solcon Enterprise Ltd',
            'company_address' => 'Phase 3, Industrial Area',
            'company_phone' => '+919999999999',
            'company_email' => 'contact@solconenterprise.com',
            'gst_number' => 'GSTIN9988776655',
            'default_timezone' => 'Asia/Kolkata',
            'default_currency' => 'INR',
            'default_language' => 'en',
            'report_header' => 'Solcon Daily Report',
            'report_footer' => 'Solcon Production System Footer',
            'default_bag_size' => 20,

            // System Settings
            'auto_batch_number' => 'enable',
            'auto_report_generation' => 'disable',
            'production_timer' => 'enable',
            'maintenance_mode' => 'disable',
            'maintenance_title' => 'System Maintenance',
            'maintenance_message' => 'The system is currently undergoing scheduled maintenance.',
            'maintenance_downtime' => '30 minutes',
            'maintenance_contact' => 'support@solcon.com',

            // SMTP Settings
            'smtp_host' => '127.0.0.1',
            'smtp_port' => 2525,
            'smtp_username' => 'testuser',
            'smtp_password' => 'secret',
            'smtp_encryption' => 'tls',

            // UI Settings
            'ui_theme' => 'light',
            'ui_primary_color' => 'cyan',
            'ui_sidebar_style' => 'light',
            'ui_compact_mode' => 'enable',
            'ui_table_density' => 'dense',
        ]);

        $responsePost->assertRedirect();
        $responsePost->assertSessionHas('success');

        // Validate values updated in DB settings
        $this->assertEquals('Solcon Enterprise Ltd', Setting::get('company_name'));
        $this->assertEquals('Phase 3, Industrial Area', Setting::get('company_address'));
        $this->assertEquals('Asia/Kolkata', Setting::get('default_timezone'));
        $this->assertEquals('cyan', Setting::get('ui_primary_color'));
        $this->assertEquals('dense', Setting::get('ui_table_density'));

        // Assert activity log written
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'SETTINGS_UPDATED',
            'module' => 'Factory Settings'
        ]);
    }

    public function test_admin_can_update_profile_and_password(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        // Get profile view
        $response = $this->actingAs($admin)->get(route('admin.profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Profile Management');
        $response->assertSee('Access History');

        // Post Profile Update
        $responseProfile = $this->actingAs($admin)->post(route('admin.profile.update'), [
            'name' => 'Admin Boss',
            'email' => 'admin_boss@solcon.com',
        ]);
        $responseProfile->assertRedirect();
        $responseProfile->assertSessionHas('success');
        $this->assertEquals('Admin Boss', $admin->fresh()->name);
        $this->assertEquals('admin_boss@solcon.com', $admin->fresh()->email);

        // Change Password
        $responsePass = $this->actingAs($admin)->post(route('admin.profile.password'), [
            'current_password' => 'password',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);
        $responsePass->assertRedirect();
        $responsePass->assertSessionHas('success');
        $this->assertTrue(Hash::check('newpassword123', $admin->fresh()->password));
    }

    public function test_admin_can_view_activity_logs_and_export_csv(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        // Generate some logs
        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'TEST_LOG_ACTION',
            'module' => 'System',
            'description' => 'Test log description details',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit Browser'
        ]);

        // Get view
        $response = $this->actingAs($admin)->get(route('admin.activity-logs'));
        $response->assertStatus(200);
        $response->assertSee('Activity Logs');
        $response->assertSee('TEST_LOG_ACTION');

        // Get filtered view
        $responseFiltered = $this->actingAs($admin)->get(route('admin.activity-logs', ['module' => 'System', 'ip_address' => '127.0.0.1']));
        $responseFiltered->assertStatus(200);
        $responseFiltered->assertSee('TEST_LOG_ACTION');

        // Export CSV
        $responseCsv = $this->actingAs($admin)->get(route('admin.activity-logs', ['export' => 'csv']));
        $responseCsv->assertStatus(200);
        $responseCsv->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_can_manage_database_backups(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        // Ensure backups folder exists
        $backupPath = storage_path('app/backups');
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        // Get backups list
        $response = $this->actingAs($admin)->get(route('admin.backups.index'));
        $response->assertStatus(200);
        $response->assertSee('Database Backups Manager');

        // Generate backup
        $responseGen = $this->actingAs($admin)->post(route('admin.backups.generate'));
        $responseGen->assertRedirect();
        
        $files = File::files($backupPath);
        $this->assertNotEmpty($files);

        $filename = $files[0]->getFilename();

        // Download backup
        $responseDl = $this->actingAs($admin)->get(route('admin.backups.download', $filename));
        $responseDl->assertStatus(200);

        // Delete backup
        $responseDel = $this->actingAs($admin)->delete(route('admin.backups.destroy', $filename));
        $responseDel->assertRedirect();
        
        $this->assertFalse(File::exists($backupPath . '/' . $filename));
    }

    public function test_admin_can_access_diagnostics_and_clear_cache(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        // Get diagnostics view
        $response = $this->actingAs($admin)->get(route('admin.system.health'));
        $response->assertStatus(200);
        $response->assertSee('Server Diagnostics');

        // Clear Cache
        $responseClear = $this->actingAs($admin)->post(route('admin.system.clear-cache'), [
            'type' => 'cache'
        ]);
        $responseClear->assertRedirect();
        $responseClear->assertSessionHas('success');
    }
}
