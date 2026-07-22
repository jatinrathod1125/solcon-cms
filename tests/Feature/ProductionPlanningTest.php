<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Grade;
use App\Models\MarketingOrder;
use App\Models\MarketingOrderItem;
use App\Models\FinishedGood;
use App\Models\RawMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductionPlanningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_access_production_planning_page(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $this->actingAs($admin);

        $response = $this->get(route('production.planning'));

        $response->assertStatus(200);
        $response->assertSee('Production Planning');
        $response->assertSee('Without Coupon');
        $response->assertSee('20 Rs Coupon');
    }

    public function test_supervisor_can_access_production_planning_page(): void
    {
        $supervisor = User::where('email', 'supervisor@solcon.com')->first();
        $this->actingAs($supervisor);

        $response = $this->get(route('production.planning'));

        $response->assertStatus(200);
        $response->assertSee('Production Planning');
    }

    public function test_unauthorized_roles_cannot_access_production_planning(): void
    {
        // Marketing User
        $marketingRole = Role::where('slug', 'marketing')->first();
        $marketingUser = User::whereHas('roles', function ($q) {
            $q->where('slug', 'marketing');
        })->first();

        if (!$marketingUser) {
            $marketingUser = User::factory()->create(['department_id' => 1]);
            $marketingUser->roles()->attach($marketingRole);
        }

        $this->actingAs($marketingUser);
        $response = $this->get(route('production.planning'));
        $response->assertStatus(403);

        // Dispatch User
        $dispatchRole = Role::where('slug', 'dispatch')->first();
        $dispatchUser = User::whereHas('roles', function ($q) {
            $q->where('slug', 'dispatch');
        })->first();

        if (!$dispatchUser) {
            $dispatchUser = User::factory()->create(['department_id' => 1]);
            $dispatchUser->roles()->attach($dispatchRole);
        }

        $this->actingAs($dispatchUser);
        $response = $this->get(route('production.planning'));
        $response->assertStatus(403);
    }

    public function test_production_planning_calculates_need_production_correctly(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $this->actingAs($admin);

        $grade = Grade::first();

        // Create approved order
        $order = MarketingOrder::create([
            'order_number' => 'ORD-TEST-001',
            'party_name' => 'Acme Builders',
            'order_date' => now(),
            'status' => 'pending',
            'created_by' => $admin->id,
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        MarketingOrderItem::create([
            'marketing_order_id' => $order->id,
            'department_code' => 'TAD',
            'grade_id' => $grade->id,
            'quantity_bags' => 50,
            'item_status' => 'pending',
        ]);

        // Create Finished Goods stock of 20 bags
        FinishedGood::create([
            'department_id' => 1,
            'grade_id' => $grade->id,
            'available_bags' => 20,
            'packing' => '20 KG',
        ]);

        $response = $this->get(route('production.planning'));
        $response->assertStatus(200);
        // Need Production = Max(50 - 20, 0) = 30
        $response->assertSee('Production Required');
    }

    public function test_get_orders_drawer_api_returns_formatted_orders(): void
    {
        $admin = User::where('email', 'admin@solcon.com')->first();
        $this->actingAs($admin);

        $grade = Grade::first();

        $order = MarketingOrder::create([
            'order_number' => 'ORD-DRAWER-999',
            'party_name' => 'Drawer Test Customer',
            'order_date' => now(),
            'status' => 'pending',
            'created_by' => $admin->id,
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        MarketingOrderItem::create([
            'marketing_order_id' => $order->id,
            'department_code' => 'TAD',
            'grade_id' => $grade->id,
            'quantity_bags' => 75,
            'item_status' => 'pending',
        ]);

        $response = $this->getJson(route('production.planning.orders', [
            'grade_id' => $grade->id,
            'category' => 'without_coupon',
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertSee('ORD-DRAWER-999');
        $response->assertSee('Drawer Test Customer');
    }
}
