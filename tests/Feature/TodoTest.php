<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Todo;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $supervisor;
    protected User $otherSupervisor;
    protected Department $department;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('email', 'admin@solcon.com')->first();
        $this->supervisor = User::where('email', 'supervisor@solcon.com')->first();
        
        // Create an extra supervisor for testing boundary limits
        $this->otherSupervisor = User::factory()->create([
            'email' => 'other_supervisor@solcon.com',
            'is_active' => true,
        ]);
        // Seed some standard roles to other supervisor
        $supervisorRole = \App\Models\Role::where('slug', 'supervisor')->first();
        if ($supervisorRole) {
            $this->otherSupervisor->roles()->attach($supervisorRole);
        }

        $this->department = Department::first() ?? Department::factory()->create();
    }

    /**
     * Test guest redirection.
     */
    public function test_guest_is_unauthorized_for_todos(): void
    {
        $this->postJson('/todos', ['title' => 'Test'])->assertStatus(302);
        $this->putJson('/todos/1', ['title' => 'Test'])->assertStatus(302);
        $this->postJson('/todos/1/toggle')->assertStatus(302);
        $this->deleteJson('/todos/1')->assertStatus(302);
    }

    /**
     * Test Admin CRUD and assignments.
     */
    public function test_admin_can_crud_and_assign_todos(): void
    {
        // 1. Admin creates personal task
        $response = $this->actingAs($this->admin)->postJson('/todos', [
            'title' => 'Admin Task',
            'description' => 'Admin Desc',
            'priority' => 'high',
            'due_date' => now()->toDateString(),
            'assigned_to' => $this->admin->id
        ]);
        $response->assertStatus(200)->assertJsonPath('success', true);
        $this->assertDatabaseHas('todos', [
            'title' => 'Admin Task',
            'assigned_to' => $this->admin->id,
            'created_by' => $this->admin->id
        ]);

        $todo = Todo::where('title', 'Admin Task')->first();

        // 2. Admin assigns task to Supervisor
        $responseAssign = $this->actingAs($this->admin)->postJson('/todos', [
            'title' => 'Supervisor Assigned Task',
            'priority' => 'medium',
            'assigned_to' => $this->supervisor->id,
            'department_id' => $this->department->id
        ]);
        $responseAssign->assertStatus(200);
        $this->assertDatabaseHas('todos', [
            'title' => 'Supervisor Assigned Task',
            'assigned_to' => $this->supervisor->id,
            'created_by' => $this->admin->id
        ]);

        // 3. Admin can edit any task
        $responseEdit = $this->actingAs($this->admin)->putJson("/todos/{$todo->id}", [
            'title' => 'Admin Task Updated',
            'priority' => 'low',
            'assigned_to' => $this->supervisor->id
        ]);
        $responseEdit->assertStatus(200);
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Admin Task Updated',
            'assigned_to' => $this->supervisor->id
        ]);

        // 4. Admin can delete any task
        $responseDelete = $this->actingAs($this->admin)->deleteJson("/todos/{$todo->id}");
        $responseDelete->assertStatus(200);
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    /**
     * Test Supervisor permission limits.
     */
    public function test_supervisor_permissions_and_limits(): void
    {
        // 1. Supervisor creates personal task
        $response = $this->actingAs($this->supervisor)->postJson('/todos', [
            'title' => 'Supervisor Task',
            'priority' => 'low'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('todos', [
            'title' => 'Supervisor Task',
            'assigned_to' => $this->supervisor->id,
            'created_by' => $this->supervisor->id
        ]);

        $todo = Todo::where('title', 'Supervisor Task')->first();

        // 2. Supervisor edits own task
        $responseEdit = $this->actingAs($this->supervisor)->putJson("/todos/{$todo->id}", [
            'title' => 'Supervisor Task Updated',
            'priority' => 'medium'
        ]);
        $responseEdit->assertStatus(200);
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Supervisor Task Updated'
        ]);

        // 3. Create a task assigned to other supervisor
        $otherTodo = Todo::create([
            'title' => 'Other Supervisor Task',
            'priority' => 'medium',
            'status' => 'pending',
            'created_by' => $this->otherSupervisor->id,
            'assigned_to' => $this->otherSupervisor->id,
            'sort_order' => 1
        ]);

        // 4. Supervisor CANNOT edit other user's task
        $responseEditOther = $this->actingAs($this->supervisor)->putJson("/todos/{$otherTodo->id}", [
            'title' => 'Hacked Task',
            'priority' => 'high'
        ]);
        $responseEditOther->assertStatus(403);

        // 5. Supervisor CANNOT delete other user's task
        $responseDeleteOther = $this->actingAs($this->supervisor)->deleteJson("/todos/{$otherTodo->id}");
        $responseDeleteOther->assertStatus(403);

        // 6. Supervisor CAN delete own task
        $responseDelete = $this->actingAs($this->supervisor)->deleteJson("/todos/{$todo->id}");
        $responseDelete->assertStatus(200);
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    /**
     * Test visibility scope rules.
     */
    public function test_todo_visibility_rules(): void
    {
        // Setup:
        // 1. Task assigned to Admin
        $todoAdmin = Todo::create([
            'title' => 'Admin Task Only',
            'priority' => 'medium',
            'status' => 'pending',
            'created_by' => $this->admin->id,
            'assigned_to' => $this->admin->id,
            'sort_order' => 1
        ]);

        // 2. Task assigned to Supervisor
        $todoSupervisor = Todo::create([
            'title' => 'Supervisor Task Only',
            'priority' => 'medium',
            'status' => 'pending',
            'created_by' => $this->admin->id,
            'assigned_to' => $this->supervisor->id,
            'sort_order' => 2
        ]);

        // Dashboard check for Admin (Sees all)
        $responseAdmin = $this->actingAs($this->admin)->get('/admin/dashboard');
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Admin Task Only');
        $responseAdmin->assertSee('Supervisor Task Only');

        // Dashboard check for Supervisor (Sees only own)
        $responseSupervisor = $this->actingAs($this->supervisor)->get('/supervisor/dashboard');
        $responseSupervisor->assertStatus(200);
        $responseSupervisor->assertDontSee('Admin Task Only');
        $responseSupervisor->assertSee('Supervisor Task Only');
    }
}
