<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Grade;
use App\Models\RawMaterial;
use App\Models\FinishedGood;
use App\Models\EpoxyComponent;
use App\Models\Department;
use App\Models\MarketingOrder;
use App\Models\MarketingOrderItem;
use App\Services\MarketingOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingOrderTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $marketingUser;
    protected $supervisorUser;
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

    public function test_order_item_without_token_does_not_match_token_finished_good_stock(): void
    {
        $grade = Grade::where('code', 'F101')->first();
        $coupon = RawMaterial::where('is_coupon', true)->first();
        $deptAdhesive = \App\Models\Department::where('code', 'TAD')->first();

        // 1. Create a FinishedGood record WITH token and 100 bags stock
        $fgWithToken = FinishedGood::create([
            'department_id' => $deptAdhesive->id,
            'grade_id' => $grade->id,
            'coupon_raw_material_id' => $coupon->id,
            'packing' => '20KG',
            'available_bags' => 100,
            'available_weight' => 2000,
            'minimum_stock' => 10,
            'status' => 'active',
        ]);

        // 2. Create an order item WITHOUT token (coupon_raw_material_id is null)
        $noTokenItem = MarketingOrderItem::create([
            'marketing_order_id' => $this->orderPending->id,
            'department_code' => 'TAD',
            'grade_id' => $grade->id,
            'quantity_bags' => 50,
            'packing' => '20KG',
            'coupon_raw_material_id' => null, // NO TOKEN
        ]);

        // 3. findFinishedGood() on noTokenItem should NOT match $fgWithToken!
        $foundFg = $noTokenItem->findFinishedGood();
        $this->assertNull($foundFg);
        $this->assertEquals(0, $noTokenItem->stock_info['available_bags']);

        // 4. Create a FinishedGood record WITHOUT token and 80 bags stock
        $fgWithoutToken = FinishedGood::create([
            'department_id' => $deptAdhesive->id,
            'grade_id' => $grade->id,
            'coupon_raw_material_id' => null, // NO TOKEN
            'packing' => '20KG',
            'available_bags' => 80,
            'available_weight' => 1600,
            'minimum_stock' => 10,
            'status' => 'active',
        ]);

        $foundFgNoToken = $noTokenItem->findFinishedGood();
        $this->assertNotNull($foundFgNoToken);
        $this->assertEquals($fgWithoutToken->id, $foundFgNoToken->id);
        $this->assertEquals(80, $noTokenItem->stock_info['available_bags']);
    }

    public function test_admix_component_stock_is_consistent_for_order_availability_and_stock_api(): void
    {
        $epoxyDepartment = Department::where('code', 'EPX')->firstOrFail();
        $component = EpoxyComponent::firstOrCreate(
            ['code' => 'EPX-GA-200GM'],
            [
                'name' => 'Grout Admix 200GM',
                'category' => 'Bottle',
                'purpose' => 'Direct Finished Product',
                'is_active' => true,
            ]
        );

        $finishedGood = FinishedGood::create([
            'department_id' => $epoxyDepartment->id,
            'epoxy_component_id' => $component->id,
            // Existing production records used this generic packing value.
            'packing' => 'Box',
            'available_bags' => 17,
            'available_weight' => 3.4,
            'minimum_stock' => 0,
            'status' => 'active',
        ]);

        $item = MarketingOrderItem::create([
            'marketing_order_id' => $this->orderPending->id,
            'department_code' => 'EPX',
            'epoxy_component_id' => $component->id,
            'quantity_bags' => 1,
            'packing' => '200GM',
        ]);

        $availability = app(MarketingOrderService::class)->checkItemAvailability($item);

        $this->assertSame($finishedGood->id, $item->findFinishedGood()?->id);
        $this->assertSame(17, $availability['fg_stock']);
        $this->assertTrue($availability['product_available']);

        $response = $this->actingAs($this->admin)->getJson(route('marketing.api.product_stock', [
            'department_code' => 'EPX',
            'component_id' => $component->id,
            'packing' => '200GM',
        ]));

        $response->assertOk()->assertJsonPath('stock.available_bags', 17);
    }

    public function test_tile_cleaner_packings_resolve_to_their_own_component_stock(): void
    {
        $epoxyDepartment = Department::where('code', 'EPX')->firstOrFail();

        foreach ([
            ['code' => 'EPX-TC-1LTR', 'packing' => '1-LTR', 'stock' => 9],
            ['code' => 'EPX-TC-5LTR', 'packing' => '5-LTR', 'stock' => 4],
        ] as $definition) {
            $component = EpoxyComponent::firstOrCreate(
                ['code' => $definition['code']],
                [
                    'name' => $definition['code'],
                    'category' => 'Box',
                    'purpose' => 'Direct Finished Product',
                    'is_active' => true,
                ]
            );

            FinishedGood::create([
                'department_id' => $epoxyDepartment->id,
                'epoxy_component_id' => $component->id,
                'packing' => 'Box',
                'available_bags' => $definition['stock'],
                'available_weight' => $definition['stock'],
                'minimum_stock' => 0,
                'status' => 'active',
            ]);

            $item = MarketingOrderItem::create([
                'marketing_order_id' => $this->orderPending->id,
                'department_code' => 'EPX',
                'epoxy_component_id' => $component->id,
                'quantity_bags' => 1,
                'packing' => $definition['packing'],
            ]);

            $this->assertSame($definition['stock'], $item->stock_info['available_bags']);
        }
    }
}
