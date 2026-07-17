<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create super-admin role if it doesn't exist
        $role = DB::table('roles')->where('slug', 'super-admin')->first();
        if (!$role) {
            $roleId = DB::table('roles')->insertGetId([
                'slug' => 'super-admin',
                'name' => 'Super Administrator',
                'description' => 'Solcon Super Administrator with root settings control.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $roleId = $role->id;
        }

        // 2. Assign super-admin role to default admin
        $admin = DB::table('users')->where('email', 'admin@solcon.com')->first();
        if ($admin) {
            $assigned = DB::table('role_user')
                ->where('user_id', $admin->id)
                ->where('role_id', $roleId)
                ->exists();
            if (!$assigned) {
                DB::table('role_user')->insert([
                    'user_id' => $admin->id,
                    'role_id' => $roleId,
                ]);
            }
        }

        // 3. Seed maintenance settings if they do not exist
        $settings = [
            'maintenance_mode' => 'disable',
            'maintenance_password' => Hash::make('admin123'),
            'maintenance_title' => 'System Under Maintenance',
            'maintenance_message' => 'Solcon ERP is currently undergoing scheduled updates and maintenance. We will be back online shortly.',
            'maintenance_downtime' => '2 hours',
            'maintenance_contact' => 'support@solcon.com',
            'maintenance_logo' => null,
        ];

        foreach ($settings as $key => $value) {
            $exists = DB::table('settings')->where('key', $key)->exists();
            if (!$exists) {
                DB::table('settings')->insert([
                    'key' => $key,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove seeded settings
        DB::table('settings')->whereIn('key', [
            'maintenance_mode',
            'maintenance_password',
            'maintenance_title',
            'maintenance_message',
            'maintenance_downtime',
            'maintenance_contact',
            'maintenance_logo',
        ])->delete();

        // Delete role association and role
        $role = DB::table('roles')->where('slug', 'super-admin')->first();
        if ($role) {
            DB::table('role_user')->where('role_id', $role->id)->delete();
            DB::table('roles')->where('id', $role->id)->delete();
        }
    }
};
