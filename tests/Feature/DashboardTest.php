<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Machine;
use App\Models\Grade;
use App\Models\ProductionBatch;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/admin/settings/factory')->assertRedirect('/login');
    }

    public function test_supervisor_cannot_access_settings(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();

        // Should receive forbidden/redirect depending on role middleware
        $response = $this->actingAs($supervisor)->get('/admin/settings/factory');
        $response->assertStatus(403);

        $responsePost = $this->actingAs($supervisor)->post('/admin/settings/factory', [
            'company_name' => 'Hack Attempt',
        ]);
        $responsePost->assertStatus(403);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Factory Dashboard');
        $response->assertSee('Interactive Production Calendar');
    }

    public function test_admin_can_access_settings_and_update(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        // Access edit form
        $response = $this->actingAs($admin)->get('/admin/settings/factory');
        $response->assertStatus(200);
        $response->assertSee('Solcon Factory Settings Console');

        // Post settings update
        $responseUpdate = $this->actingAs($admin)->post('/admin/settings/factory', [
            'company_name' => 'Solcon Modified Ltd',
            'report_header' => 'Shift Summary Report',
            'report_footer' => 'Confidential Solcon Document',
            'default_bag_size' => 20,
            'default_timezone' => 'Asia/Kolkata',
            'default_currency' => 'INR',
            'default_language' => 'en',
            'auto_batch_number' => 'enable',
            'auto_report_generation' => 'disable',
            'production_timer' => 'enable',
            'maintenance_mode' => 'disable',
            'maintenance_title' => 'System Maintenance',
            'maintenance_message' => 'System under maintenance',
            'maintenance_downtime' => '30 mins',
            'maintenance_contact' => 'admin@solcon.com',
            'ui_theme' => 'light',
            'ui_primary_color' => 'indigo',
            'ui_sidebar_style' => 'dark',
            'ui_compact_mode' => 'disable',
            'ui_table_density' => 'normal',
        ]);
        $responseUpdate->assertRedirect();
        $responseUpdate->assertSessionHas('success');

        // Assert DB has changed
        $this->assertEquals('Solcon Modified Ltd', Setting::get('company_name'));
        $this->assertEquals('Shift Summary Report', Setting::get('report_header'));
        $this->assertEquals('Asia/Kolkata', Setting::get('default_timezone'));
    }

    public function test_live_machines_ajax_endpoint(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard/machines');
        $response->assertStatus(200);
        $response->assertSee('Mixer ID:');
    }

    public function test_supervisor_dashboard_access_and_ajax_endpoints(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();

        // 1. Dashboard view
        $response = $this->actingAs($supervisor)->get('/supervisor/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Supervisor Dashboard');
        $response->assertSee('Live Mixer Floor');

        // 2. AJAX machines endpoint
        $responseMachines = $this->actingAs($supervisor)->get('/supervisor/dashboard/machines');
        $responseMachines->assertStatus(200);
        $responseMachines->assertSee('Mixer ID:');
    }

    public function test_calendar_details_ajax_endpoint(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $today = now()->toDateString();

        // Access without parameters should fail validation
        $this->actingAs($admin)->get('/admin/calendar/details')->assertStatus(302);

        // Access with parameters
        $response = $this->actingAs($admin)->get("/admin/calendar/details?date={$today}");
        $response->assertStatus(200);
        $response->assertSee('Production Breakdown:');
    }

    public function test_whatsapp_pdf_export(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $today = now()->toDateString();

        $response = $this->actingAs($admin)->get("/production/reports/daily/pdf/whatsapp?date={$today}");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_production_history_filters_and_csv_export(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        
        // Base View
        $response = $this->actingAs($admin)->get('/production/history');
        $response->assertStatus(200);
        $response->assertSee('Production Batches History Log');

        // CSV Export
        $responseCsv = $this->actingAs($admin)->get('/production/history?export=csv');
        $responseCsv->assertStatus(200);
        $responseCsv->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}
