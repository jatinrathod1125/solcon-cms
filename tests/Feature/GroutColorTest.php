<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use App\Models\Color;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroutColorTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $supervisorUser;
    protected $deptGRT;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('email', 'admin@solcon.com')->first();
        $this->supervisorUser = User::where('email', 'supervisor@solcon.com')->first();
        $this->deptGRT = Department::where('code', 'GRT')->first();
    }

    public function test_guest_and_supervisor_cannot_access_grout_colors(): void
    {
        $this->get('/admin/grout-colors')->assertRedirect('/login');

        $this->actingAs($this->supervisorUser)
            ->get('/admin/grout-colors')
            ->assertStatus(403);
    }

    public function test_admin_can_access_grout_colors_list(): void
    {
        $this->actingAs($this->adminUser)
            ->get('/admin/grout-colors')
            ->assertStatus(200)
            ->assertSee('Grout Color Master');
    }

    public function test_admin_can_create_grout_color(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/grout-colors', [
            'department_id' => $this->deptGRT->id,
            'name' => 'Ivory Grout',
            'code' => 'GR-IVO',
            'packing_size' => '1000 GM', // Will fail: not in enum/validation (must be 500 GM or 1 KG)
            'default_cement' => 'White Cement',
            'dual_color' => 0,
            'is_active' => 1,
            'description' => 'Standard Ivory grout',
        ]);
        $response->assertSessionHasErrors('packing_size');

        $responseSuccess = $this->actingAs($this->adminUser)->post('/admin/grout-colors', [
            'department_id' => $this->deptGRT->id,
            'name' => 'Ivory Grout',
            'code' => 'GR-IVO',
            'packing_size' => '1 KG',
            'default_cement' => 'White Cement',
            'is_active' => 1,
            'description' => 'Standard Ivory grout',
        ]);
        $responseSuccess->assertRedirect('/admin/grout-colors');

        $this->assertDatabaseHas('colors', [
            'code' => 'GR-IVO',
            'name' => 'Ivory Grout',
            'packing_size' => '1 KG',
        ]);
    }

    public function test_admin_can_edit_grout_color(): void
    {
        $color = Color::create([
            'department_id' => $this->deptGRT->id,
            'name' => 'Grey Grout',
            'code' => 'GR-GRY',
            'packing_size' => '500 GM',
            'default_cement' => 'Grey Cement',
            'is_active' => 1,
            'created_by' => $this->adminUser->id,
        ]);

        $response = $this->actingAs($this->adminUser)->put("/admin/grout-colors/{$color->id}", [
            'department_id' => $this->deptGRT->id,
            'name' => 'Grey Grout Updated',
            'code' => 'GR-GRY',
            'packing_size' => '1.5 KG', // fails validation
            'default_cement' => 'Grey Cement',
        ]);
        $response->assertSessionHasErrors('packing_size');

        $responseSuccess = $this->actingAs($this->adminUser)->put("/admin/grout-colors/{$color->id}", [
            'department_id' => $this->deptGRT->id,
            'name' => 'Grey Grout Updated',
            'code' => 'GR-GRY',
            'packing_size' => '500 GM',
            'default_cement' => 'Grey Cement',
            'is_active' => 0,
        ]);
        $responseSuccess->assertRedirect('/admin/grout-colors');

        $color->refresh();
        $this->assertEquals('Grey Grout Updated', $color->name);
        $this->assertFalse($color->is_active);
    }

    public function test_color_filtering(): void
    {
        Color::create([
            'department_id' => $this->deptGRT->id,
            'name' => 'White Grout',
            'code' => 'GR-WHT',
            'packing_size' => '1 KG',
            'default_cement' => 'White Cement',
            'is_active' => 1,
            'created_by' => $this->adminUser->id,
        ]);

        Color::create([
            'department_id' => $this->deptGRT->id,
            'name' => 'Chocolate Grout',
            'code' => 'GR-CHO',
            'packing_size' => '500 GM',
            'default_cement' => 'Grey Cement',
            'is_active' => 0,
            'created_by' => $this->adminUser->id,
        ]);

        // Filter by cement
        $response = $this->actingAs($this->adminUser)->get('/admin/grout-colors?default_cement=White+Cement');
        $response->assertSee('White Grout');
        $response->assertDontSee('Chocolate Grout');

        // Filter by status
        $responseInactive = $this->actingAs($this->adminUser)->get('/admin/grout-colors?status=inactive');
        $responseInactive->assertSee('Chocolate Grout');
        $responseInactive->assertDontSee('White Grout');
    }
}
