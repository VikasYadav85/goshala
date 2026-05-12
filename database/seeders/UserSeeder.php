<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
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

        User::updateOrCreate(
            ['email' => 'editor@gopalsevatrust.org'],
            [
                'name' => 'Content Editor',
                'password' => Hash::make('Editor@2025'),
                'role' => User::ROLE_EDITOR,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
    }
}
