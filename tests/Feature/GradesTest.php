<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\BagSize;
use App\Models\Unit;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GradesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles, departments, units, bag sizes, and default accounts
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/grades');
        $response->assertRedirect('/login');
    }

    public function test_supervisor_cannot_access_grades(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();

        $response = $this->actingAs($supervisor)->get('/admin/grades');
        $response->assertStatus(403);

        $response = $this->actingAs($supervisor)->post('/admin/grades', [
            'name' => 'F107 White marble',
            'code' => 'F107-W',
        ]);
        $response->assertStatus(403);
    }

    public function test_admin_can_perform_grade_crud(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $dept = Department::where('code', 'TAD')->first();
        $bag25 = BagSize::where('name', '25 KG Bag')->first();
        $unitKG = Unit::where('code', 'KG')->first();

        // 1. Create
        $response = $this->actingAs($admin)->post('/admin/grades', [
            'name' => 'New Premium Grade F120',
            'code' => 'F120',
            'department_id' => $dept->id,
            'bag_size_id' => $bag25->id,
            'output_unit_id' => $unitKG->id,
            'description' => 'Grey adhesive premium spec',
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/grades');
        $this->assertDatabaseHas('grades', [
            'code' => 'F120',
            'name' => 'New Premium Grade F120',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        // 2. Read with Filters
        $response = $this->actingAs($admin)->get('/admin/grades?search=F120&status=active');
        $response->assertStatus(200);
        $response->assertSee('New Premium Grade F120');

        // 3. Update
        $grade = Grade::where('code', 'F120')->first();
        $response = $this->actingAs($admin)->put("/admin/grades/{$grade->id}", [
            'name' => 'New Premium Grade F120 Updated',
            'code' => 'F120-U',
            'department_id' => $dept->id,
            'bag_size_id' => $bag25->id,
            'output_unit_id' => $unitKG->id,
            'description' => 'White adhesive premium spec',
            'is_active' => false,
        ]);

        $response->assertRedirect('/admin/grades');
        $this->assertDatabaseHas('grades', [
            'id' => $grade->id,
            'code' => 'F120-U',
            'name' => 'New Premium Grade F120 Updated',
            'is_active' => false,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        // 4. Delete
        $response = $this->actingAs($admin)->delete("/admin/grades/{$grade->id}");
        $response->assertRedirect('/admin/grades');
        $this->assertDatabaseMissing('grades', ['id' => $grade->id]);
    }

    public function test_grade_validation_rules(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $dept = Department::where('code', 'TAD')->first();
        $bag25 = BagSize::where('name', '25 KG Bag')->first();
        $unitKG = Unit::where('code', 'KG')->first();

        // Unique Name and Code Violations (F101 is seeded)
        $response = $this->actingAs($admin)->post('/admin/grades', [
            'name' => 'Standard Tile Adhesive F101',
            'code' => 'F999',
            'department_id' => $dept->id,
            'bag_size_id' => $bag25->id,
            'output_unit_id' => $unitKG->id,
            'is_active' => true,
        ]);
        $response->assertSessionHasErrors('name');

        $response = $this->actingAs($admin)->post('/admin/grades', [
            'name' => 'Unique Grade Name',
            'code' => 'F101',
            'department_id' => $dept->id,
            'bag_size_id' => $bag25->id,
            'output_unit_id' => $unitKG->id,
            'is_active' => true,
        ]);
        $response->assertSessionHasErrors('code');
    }
}
