<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@gopalsevatrust.org'],
            [
                'name' => 'Trust Administrator',
                'password' => Hash::make('Gopal@2025'),
                'role' => User::ROLE_SUPER_ADMIN,
                'is_active' => true,
                'phone' => '+91-98765-43210',
                'email_verified_at' => now(),
            ],
        );

        $editor = User::updateOrCreate(
            ['email' => 'editor@gopalsevatrust.org'],
            [
                'name' => 'Content Editor',
                'password' => Hash::make('Editor@2025'),
                'role' => User::ROLE_EDITOR,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        $admin->syncRoles([User::ROLE_SUPER_ADMIN]);
        $editor->syncRoles([User::ROLE_EDITOR]);

        // Backfill: any user created before RBAC keeps access by mapping their
        // legacy `role` string to the matching Spatie role.
        User::query()->whereNotNull('role')->each(function (User $user) {
            if ($user->roles()->exists()) {
                return;
            }
            $user->syncRoles([$user->role]);
        });
    }
}
