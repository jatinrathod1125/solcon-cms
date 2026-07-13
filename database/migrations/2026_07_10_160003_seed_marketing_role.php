<?php

use Illuminate\Database\Migrations\Migration;
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
        // Create the role
        $marketingRole = Role::updateOrCreate(
            ['slug' => 'marketing'],
            [
                'name' => 'Marketing',
                'description' => 'Solcon Marketing user with access to order generation',
            ]
        );

        // Create a default marketing user if not exists
        $marketingUser = User::updateOrCreate(
            ['email' => 'marketing@solcon.com'],
            [
                'name' => 'Marketing User',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Link the user to the role
        $marketingUser->roles()->syncWithoutDetaching([$marketingRole->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $role = Role::where('slug', 'marketing')->first();
        if ($role) {
            $role->users()->detach();
            $role->delete();
        }

        User::where('email', 'marketing@solcon.com')->delete();
    }
};
