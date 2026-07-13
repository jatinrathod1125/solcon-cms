<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Machine;
use App\Models\Unit;
use App\Models\BagSize;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MastersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles, permissions, and default accounts
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/departments');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/machines');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/units');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/bag-sizes');
        $response->assertRedirect('/login');
    }

    public function test_supervisor_cannot_access_masters(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();

        $response = $this->actingAs($supervisor)->get('/admin/departments');
        $response->assertStatus(403);

        $response = $this->actingAs($supervisor)->post('/admin/departments', [
            'name' => 'Failed Dept',
            'code' => 'FLD',
        ]);
        $response->assertStatus(403);
    }

    public function test_admin_can_perform_department_crud(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        // 1. Create
        $response = $this->actingAs($admin)->post('/admin/departments', [
            'name' => 'New Adhesive Dept',
            'code' => 'NAD',
            'description' => 'Brand new adhesive line',
            'is_active' => true,
        ]);
        $response->assertRedirect('/admin/departments');
        $this->assertDatabaseHas('departments', ['code' => 'NAD', 'name' => 'New Adhesive Dept']);

        // 2. Read
        $response = $this->actingAs($admin)->get('/admin/departments');
        $response->assertStatus(200);
        $response->assertSee('NAD');

        // 3. Update
        $dept = Department::where('code', 'NAD')->first();
        $response = $this->actingAs($admin)->put("/admin/departments/{$dept->id}", [
            'name' => 'Updated Adhesive Dept',
            'code' => 'NAD-U',
            'description' => 'Updated description',
            'is_active' => false,
        ]);
        $response->assertRedirect('/admin/departments');
        $this->assertDatabaseHas('departments', ['id' => $dept->id, 'code' => 'NAD-U', 'is_active' => false]);

        // 4. Delete
        $response = $this->actingAs($admin)->delete("/admin/departments/{$dept->id}");
        $response->assertRedirect('/admin/departments');
        $this->assertDatabaseMissing('departments', ['id' => $dept->id]);
    }

    public function test_admin_cannot_delete_department_with_machines(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        
        $dept = Department::where('code', 'TAD')->first();
        $machine = Machine::where('department_id', $dept->id)->first();

        // Try deleting department TAD which has active machines
        $response = $this->actingAs($admin)->delete("/admin/departments/{$dept->id}");
        $response->assertRedirect('/admin/departments');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('departments', ['id' => $dept->id]);
    }

    public function test_admin_can_perform_machine_crud(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $dept = Department::where('code', 'TAD')->first();

        // 1. Create
        $response = $this->actingAs($admin)->post('/admin/machines', [
            'department_id' => $dept->id,
            'name' => 'New Mixer X',
            'code' => 'MX-99',
            'description' => 'New high tech mixer',
            'is_active' => true,
        ]);
        $response->assertRedirect('/admin/machines');
        $this->assertDatabaseHas('machines', ['code' => 'MX-99', 'name' => 'New Mixer X']);

        // 2. Read
        $response = $this->actingAs($admin)->get('/admin/machines');
        $response->assertStatus(200);
        $response->assertSee('MX-99');

        // 3. Update
        $machine = Machine::where('code', 'MX-99')->first();
        $response = $this->actingAs($admin)->put("/admin/machines/{$machine->id}", [
            'department_id' => $dept->id,
            'name' => 'Updated Mixer X',
            'code' => 'MX-99-U',
            'description' => 'New description',
            'is_active' => false,
        ]);
        $response->assertRedirect('/admin/machines');
        $this->assertDatabaseHas('machines', ['id' => $machine->id, 'code' => 'MX-99-U']);

        // 4. Delete
        $response = $this->actingAs($admin)->delete("/admin/machines/{$machine->id}");
        $response->assertRedirect('/admin/machines');
        $this->assertDatabaseMissing('machines', ['id' => $machine->id]);
    }

    public function test_admin_can_perform_unit_crud(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        // 1. Create
        $response = $this->actingAs($admin)->post('/admin/units', [
            'name' => 'Metric Ton',
            'code' => 'MT',
            'description' => '1000 Kilograms',
            'is_active' => true,
        ]);
        $response->assertRedirect('/admin/units');
        $this->assertDatabaseHas('units', ['code' => 'MT']);

        // 2. Read
        $response = $this->actingAs($admin)->get('/admin/units');
        $response->assertStatus(200);
        $response->assertSee('MT');

        // 3. Update
        $unit = Unit::where('code', 'MT')->first();
        $response = $this->actingAs($admin)->put("/admin/units/{$unit->id}", [
            'name' => 'Metric Tonne',
            'code' => 'MT-U',
            'description' => 'Updated unit desc',
            'is_active' => false,
        ]);
        $response->assertRedirect('/admin/units');
        $this->assertDatabaseHas('units', ['id' => $unit->id, 'code' => 'MT-U']);

        // 4. Delete
        $response = $this->actingAs($admin)->delete("/admin/units/{$unit->id}");
        $response->assertRedirect('/admin/units');
        $this->assertDatabaseMissing('units', ['id' => $unit->id]);
    }

    public function test_admin_can_perform_bag_size_crud(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();

        // 1. Create
        $response = $this->actingAs($admin)->post('/admin/bag-sizes', [
            'name' => '15 KG Bag',
            'value' => 15.00,
            'description' => 'Standard 15kg weight package',
            'is_active' => true,
        ]);
        $response->assertRedirect('/admin/bag-sizes');
        $this->assertDatabaseHas('bag_sizes', ['name' => '15 KG Bag', 'value' => 15.00]);

        // 2. Read
        $response = $this->actingAs($admin)->get('/admin/bag-sizes');
        $response->assertStatus(200);
        $response->assertSee('15 KG Bag');

        // 3. Update
        $bag = BagSize::where('name', '15 KG Bag')->first();
        $response = $this->actingAs($admin)->put("/admin/bag-sizes/{$bag->id}", [
            'name' => '15.5 KG Bag',
            'value' => 15.50,
            'description' => 'Updated packaging weight',
            'is_active' => false,
        ]);
        $response->assertRedirect('/admin/bag-sizes');
        $this->assertDatabaseHas('bag_sizes', ['id' => $bag->id, 'name' => '15.5 KG Bag', 'value' => 15.50]);

        // 4. Delete
        $response = $this->actingAs($admin)->delete("/admin/bag-sizes/{$bag->id}");
        $response->assertRedirect('/admin/bag-sizes');
        $this->assertDatabaseMissing('bag_sizes', ['id' => $bag->id]);
    }
}
