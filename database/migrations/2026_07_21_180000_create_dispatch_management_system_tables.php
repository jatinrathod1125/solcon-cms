<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->string('dispatch_number')->unique();
            $table->enum('dispatch_type', ['factory_pickup', 'crossing_delivery'])->default('factory_pickup');
            $table->string('party_name');
            $table->string('city')->nullable();
            $table->string('place')->nullable();
            $table->text('full_address')->nullable();
            $table->text('google_map_url')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_mobile')->nullable();
            $table->dateTime('expected_arrival_at')->nullable();
            
            // Payment tracking
            $table->boolean('payment_required')->default(false);
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending')->nullable();
            $table->decimal('payment_amount', 12, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('payment_remarks')->nullable();

            // Release status controlled by Marketing
            $table->boolean('is_released')->default(false);
            $table->foreignId('released_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('released_at')->nullable();

            // Status: planned, waiting_for_truck, truck_arrived, loading, completed, cancelled
            $table->string('status')->default('planned');
            $table->text('remarks')->nullable();

            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('loaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('loaded_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'dispatch_type']);
            $table->index('party_name');
            $table->index('is_released');
        });

        Schema::create('dispatch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispatch_id')->constrained('dispatches')->cascadeOnDelete();
            $table->foreignId('marketing_order_id')->nullable()->constrained('marketing_orders')->nullOnDelete();
            $table->foreignId('marketing_order_item_id')->nullable()->constrained('marketing_order_items')->nullOnDelete();
            $table->string('department_code'); // TAD, GRT, EPX
            $table->foreignId('grade_id')->nullable()->constrained('grades')->nullOnDelete();
            $table->foreignId('color_id')->nullable()->constrained('colors')->nullOnDelete();
            $table->foreignId('epoxy_product_id')->nullable()->constrained('epoxy_products')->nullOnDelete();
            $table->foreignId('epoxy_filler_color_id')->nullable()->constrained('epoxy_filler_colors')->nullOnDelete();
            $table->foreignId('epoxy_component_id')->nullable()->constrained('epoxy_components')->nullOnDelete();
            $table->integer('quantity_bags');
            $table->decimal('quantity_kg', 10, 2)->nullable();
            $table->string('packing')->nullable();
            $table->foreignId('coupon_raw_material_id')->nullable()->constrained('raw_materials')->nullOnDelete();
            $table->integer('coupon_quantity')->nullable();
            $table->timestamps();

            $table->index('dispatch_id');
            $table->index('marketing_order_id');
        });

        Schema::create('dispatch_loading_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispatch_id')->constrained('dispatches')->cascadeOnDelete();
            $table->string('status');
            $table->foreignId('user_id')->constrained('users');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('dispatch_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispatch_id')->constrained('dispatches')->cascadeOnDelete();
            $table->string('status');
            $table->foreignId('changed_by')->constrained('users');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Seed Dispatch Role & Default Dispatch User
        $dispatchRole = Role::updateOrCreate(
            ['slug' => 'dispatch'],
            [
                'name' => 'Dispatch',
                'description' => 'Solcon Dispatch department user responsible for loading and dispatching orders',
            ]
        );

        $dispatchUser = User::updateOrCreate(
            ['email' => 'dispatch@solcon.com'],
            [
                'name' => 'Dispatch Staff',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $dispatchUser->roles()->syncWithoutDetaching([$dispatchRole->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_status_history');
        Schema::dropIfExists('dispatch_loading_logs');
        Schema::dropIfExists('dispatch_items');
        Schema::dropIfExists('dispatches');

        $role = Role::where('slug', 'dispatch')->first();
        if ($role) {
            $role->users()->detach();
            $role->delete();
        }

        User::where('email', 'dispatch@solcon.com')->delete();
    }
};
