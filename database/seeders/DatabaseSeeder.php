<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database for live demo.
     */
    public function run(): void
    {
        $this->call(BrandSeeder::class);
        $solconBrandId = Brand::where('code', Brand::CODE_SOLCON)->valueOrFail('id');

        // Create basic departments if they don't exist
        $deptTAD = \App\Models\Department::updateOrCreate(['code' => 'TAD'], ['name' => 'Tile Adhesive Dept', 'description' => 'Tile Adhesive Department', 'is_active' => true]);
        $deptGRT = \App\Models\Department::updateOrCreate(['code' => 'GRT'], ['name' => 'Grout Dept', 'description' => 'Production department for Grout products', 'is_active' => true]);
        $deptEPX = \App\Models\Department::updateOrCreate(['code' => 'EPX'], ['name' => 'Epoxy Dept', 'description' => 'Production department for Epoxy products', 'is_active' => true]);

        // Create basic units if they don't exist
        $unitPCS = \App\Models\Unit::updateOrCreate(['code' => 'PCS'], ['name' => 'Pieces', 'description' => 'Pieces unit', 'is_active' => true]);
        $unitKG = \App\Models\Unit::updateOrCreate(['code' => 'KG'], ['name' => 'Kilogram', 'description' => 'Kilogram unit', 'is_active' => true]);
        \App\Models\Unit::updateOrCreate(['code' => 'GM'], ['name' => 'Gram', 'description' => 'Gram unit', 'is_active' => true]);

        // Seed coupons if they are not already seeded
        $denominations = [5, 10, 15, 20, 25, 30, 40, 50, 100, 200];
        foreach ($denominations as $val) {
            \App\Models\RawMaterial::updateOrCreate(
                ['code' => "COUPON-{$val}"],
                [
                    'brand_id' => $solconBrandId,
                    'name' => "₹{$val} Solcon Coupon",
                    'department_id' => $deptTAD->id,
                    'stock_unit_id' => $unitPCS->id,
                    'purchase_unit_id' => $unitPCS->id,
                    'purchase_conversion' => 1.0000,
                    'opening_stock' => 5000.0000,
                    'current_stock' => 5000.0000,
                    'minimum_stock' => 100.0000,
                    'maximum_stock' => 50000.0000,
                    'description' => "Standard ₹{$val} promotional coupon placed inside bag",
                    'is_coupon' => true,
                    'is_active' => true,
                ]
            );
            \App\Models\RawMaterial::updateOrCreate(
                ['code' => "CUSTOM-COUPON-{$val}"],
                [
                    'brand_id' => $solconBrandId,
                    'name' => "₹{$val} Custom Party Coupon",
                    'department_id' => $deptTAD->id,
                    'stock_unit_id' => $unitPCS->id,
                    'purchase_unit_id' => $unitPCS->id,
                    'purchase_conversion' => 1.0000,
                    'opening_stock' => 5000.0000,
                    'current_stock' => 5000.0000,
                    'minimum_stock' => 100.0000,
                    'maximum_stock' => 50000.0000,
                    'description' => "Custom party ₹{$val} promotional coupon placed inside bag",
                    'is_coupon' => true,
                    'is_active' => true,
                ]
            );
        }

        // 1. App Settings
        Setting::set('factory_name', 'Solcon Industries');
        Setting::set('timezone', 'Asia/Kolkata');
        Setting::set('ui_theme', 'dark');
        Setting::set('ui_primary_color', 'indigo');

        // 2. Create permissions
        $permissions = [
            'manage-masters' => 'Create and edit departments, machines, units, raw materials and grades',
            'manage-formulas' => 'Define formulas for grades',
            'log-production' => 'Start, track and complete production batches',
            'view-reports' => 'Generate and view production reports',
            'manage-users' => 'Manage user accounts and permissions',
            'manage-settings' => 'Manage global factory settings',
        ];

        $permissionModels = [];
        foreach ($permissions as $slug => $description) {
            $permissionModels[$slug] = Permission::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => ucwords(str_replace('-', ' ', $slug)),
                    'description' => $description,
                ]
            );
        }

        // 3. Create roles
        $adminRole = Role::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Solcon Administrator with full access to masters, formulas and settings',
            ]
        );

        $supervisorRole = Role::updateOrCreate(
            ['slug' => 'supervisor'],
            [
                'name' => 'Supervisor',
                'description' => 'Solcon Production Supervisor with access to department-level batch operations',
            ]
        );

        $adminRole->permissions()->sync(array_values(array_map(fn($m) => $m->id, $permissionModels)));
        $supervisorRole->permissions()->sync([
            $permissionModels['log-production']->id,
            $permissionModels['view-reports']->id,
        ]);

        // 5. Create Users
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@solcon.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $adminUser->roles()->sync([$adminRole->id]);

        $supervisorUser = User::updateOrCreate(
            ['email' => 'supervisor@solcon.com'],
            [
                'name' => 'Supervisor User',
                'password' => Hash::make('password'),
                'department_id' => $deptTAD->id,
                'is_active' => true,
            ]
        );
        $supervisorUser->roles()->sync([$supervisorRole->id]);
        $supervisorUser->departments()->sync([$deptTAD->id]);
    }
}
