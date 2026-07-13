<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $supervisorUser;
    protected $deptTAD;
    protected $deptGRT;
    protected $deptEPX;

    protected function setUp(): void
    {
        parent::setUp();

        // Run seeders
        $this->seed();

        $this->adminUser = User::where('email', 'admin@solcon.com')->first();
        $this->supervisorUser = User::where('email', 'supervisor@solcon.com')->first();

        $this->deptTAD = Department::where('code', 'TAD')->first();
        $this->deptGRT = Department::where('code', 'GRT')->first();
        $this->deptEPX = Department::where('code', 'EPX')->first();
    }

    public function test_user_department_relationships_and_helpers(): void
    {
        // TAD is default assigned in seeder
        $this->assertTrue($this->supervisorUser->hasDepartment($this->deptTAD->id));
        $this->assertFalse($this->supervisorUser->hasDepartment($this->deptGRT->id));

        // Test departmentIds() helper
        $this->assertTrue($this->supervisorUser->departmentIds()->contains($this->deptTAD->id));
        $this->assertFalse($this->supervisorUser->departmentIds()->contains($this->deptGRT->id));

        // Test canAccessDepartment() helper
        $this->assertTrue($this->supervisorUser->canAccessDepartment($this->deptTAD->id));
        $this->assertFalse($this->supervisorUser->canAccessDepartment($this->deptGRT->id));

        // Admin can access everything
        $this->assertTrue($this->adminUser->canAccessDepartment($this->deptTAD->id));
        $this->assertTrue($this->adminUser->canAccessDepartment($this->deptGRT->id));
    }

    public function test_dynamic_department_attribute_accessor(): void
    {
        // Authenticate the supervisor to load session-based department
        $this->actingAs($this->supervisorUser);

        // Accessing department_id should return the current active department (TAD)
        $this->assertEquals($this->deptTAD->id, $this->supervisorUser->department_id);
        $this->assertEquals($this->deptTAD->name, $this->supervisorUser->department->name);
    }

    public function test_department_switching(): void
    {
        // Assign both TAD and GRT to supervisor
        $this->supervisorUser->departments()->sync([$this->deptTAD->id, $this->deptGRT->id]);
        
        // Clear supervisor cache
        app(\App\Services\DepartmentAccessService::class)->clearUserCache($this->supervisorUser);

        $this->actingAs($this->supervisorUser);

        // Switch to GRT
        $response = $this->post('/department/switch', [
            'department_id' => $this->deptGRT->id,
        ]);

        $response->assertRedirect();
        
        // After switching, the dynamic department_id should be GRT
        $this->assertEquals($this->deptGRT->id, $this->supervisorUser->department_id);
        $this->assertEquals($this->deptGRT->name, $this->supervisorUser->department->name);
    }

    public function test_unauthorized_department_switch_fails(): void
    {
        $this->actingAs($this->supervisorUser);

        // Attempt to switch to EPX (which is not assigned to supervisor)
        $response = $this->post('/department/switch', [
            'department_id' => $this->deptEPX->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_middleware_restricts_access(): void
    {
        $this->actingAs($this->supervisorUser);

        // Supervisor is assigned to TAD. Visiting TAD-scoped production page should be 200.
        // Wait, the production.index route doesn't take department parameter in route,
        // it filters by user->department_id dynamically.
        // If we switch to TAD, it works.
        $response = $this->get('/production');
        $response->assertStatus(200);

        // If we switch the session to an unauthorized department (e.g. EPX) by force (mocking),
        // it should hit the middleware and abort 403.
        session(['current_department_id_' . $this->supervisorUser->id => $this->deptEPX->id]);

        $response = $this->get('/production');
        $response->assertStatus(403);
    }

    public function test_user_creation_requires_at_least_one_department(): void
    {
        $this->actingAs($this->adminUser);

        $roleSupervisor = Role::where('slug', 'supervisor')->first();

        // Create user with empty departments should fail validation
        $response = $this->post('/admin/users', [
            'name' => 'Test User',
            'email' => 'newuser@solcon.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $roleSupervisor->id,
            'departments' => [], // Empty
        ]);

        $response->assertSessionHasErrors('departments');
    }
}
