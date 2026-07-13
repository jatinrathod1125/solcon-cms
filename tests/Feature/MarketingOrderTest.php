<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\MarketingOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingOrderTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $marketingUser;
    protected $orderPending;
    protected $orderInProgress;
    protected $orderCompleted;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('email', 'admin@solcon.com')->first();

        // Create marketing role and user
        $marketingRole = Role::firstOrCreate(['slug' => 'marketing'], [
            'name' => 'Marketing Staff',
            'description' => 'Marketing staff'
        ]);

        $this->marketingUser = User::create([
            'name' => 'Marketing User',
            'email' => 'marketing_test@solcon.com',
            'password' => bcrypt('password'),
            'is_active' => true
        ]);
        $this->marketingUser->roles()->sync([$marketingRole->id]);

        // Create supervisor user
        $supervisorRole = Role::where('slug', 'supervisor')->first();
        $this->supervisorUser = User::create([
            'name' => 'Supervisor User',
            'email' => 'supervisor_test@solcon.com',
            'password' => bcrypt('password'),
            'is_active' => true
        ]);
        $this->supervisorUser->roles()->sync([$supervisorRole->id]);

        // Create some sample marketing orders
        $this->orderPending = MarketingOrder::create([
            'order_number' => 'ORD-PEND-01',
            'party_name' => 'Pending Party',
            'order_date' => now(),
            'status' => 'pending',
            'priority' => 'medium',
            'created_by' => $this->marketingUser->id,
        ]);

        $this->orderInProgress = MarketingOrder::create([
            'order_number' => 'ORD-PROD-01',
            'party_name' => 'In Progress Party',
            'order_date' => now(),
            'status' => 'in_progress',
            'priority' => 'medium',
            'created_by' => $this->marketingUser->id,
        ]);

        $this->orderCompleted = MarketingOrder::create([
            'order_number' => 'ORD-COMP-01',
            'party_name' => 'Completed Party',
            'order_date' => now(),
            'status' => 'completed',
            'priority' => 'medium',
            'created_by' => $this->marketingUser->id,
        ]);
    }

    public function test_marketing_staff_can_delete_pending_order(): void
    {
        $response = $this->actingAs($this->marketingUser)->delete(route('marketing.orders.destroy', $this->orderPending->id));

        $response->assertStatus(200);
        $this->assertEquals('cancelled', $this->orderPending->fresh()->status);
    }

    public function test_marketing_staff_can_delete_in_progress_order(): void
    {
        $response = $this->actingAs($this->marketingUser)->delete(route('marketing.orders.destroy', $this->orderInProgress->id));

        $response->assertStatus(200);
        $this->assertEquals('cancelled', $this->orderInProgress->fresh()->status);
    }

    public function test_marketing_staff_cannot_delete_completed_order(): void
    {
        $response = $this->actingAs($this->marketingUser)->delete(route('marketing.orders.destroy', $this->orderCompleted->id));

        $response->assertStatus(403);
        $this->assertEquals('completed', $this->orderCompleted->fresh()->status);
    }

    public function test_admin_can_delete_completed_order(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('marketing.orders.destroy', $this->orderCompleted->id));

        // Admin can delete/cancel any order
        $response->assertStatus(200);
        $this->assertEquals('cancelled', $this->orderCompleted->fresh()->status);
    }

    public function test_supervisor_cannot_create_order(): void
    {
        $response = $this->actingAs($this->supervisorUser)->post(route('marketing.orders.store'), [
            'party_name' => 'Supervisor Party',
            'priority' => 'medium',
            'order_date' => now()->toDateString(),
            'items' => [
                [
                    'department_code' => 'TAD',
                    'grade_id' => 1,
                    'quantity_bags' => 10,
                    'packing' => '20 KG Bag'
                ]
            ]
        ]);

        $response->assertStatus(403);
    }

    public function test_supervisor_cannot_update_order(): void
    {
        $response = $this->actingAs($this->supervisorUser)->put(route('marketing.orders.update', $this->orderPending->id), [
            'party_name' => 'Updated Party Name',
            'priority' => 'high',
            'order_date' => now()->toDateString(),
            'items' => [
                [
                    'department_code' => 'TAD',
                    'grade_id' => 1,
                    'quantity_bags' => 15,
                    'packing' => '20 KG Bag'
                ]
            ]
        ]);

        $response->assertStatus(403);
    }

    public function test_supervisor_cannot_delete_order(): void
    {
        $response = $this->actingAs($this->supervisorUser)->delete(route('marketing.orders.destroy', $this->orderPending->id));

        $response->assertStatus(403);
        $this->assertEquals('pending', $this->orderPending->fresh()->status);
    }
}
