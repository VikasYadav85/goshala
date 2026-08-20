<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_authenticated_admin_opening_login_is_redirected_to_dashboard(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('admin.login'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_successful_login_ignores_stale_frontend_intended_url(): void
    {
        $admin = $this->superAdmin();

        $this->withSession(['url.intended' => route('home')])
            ->post(route('admin.login.post'), [
                'email' => $admin->email,
                'password' => 'admin-password',
            ])
            ->assertRedirect(route('admin.dashboard'));
    }

    private function superAdmin(): User
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'is_active' => true,
            'password' => Hash::make('admin-password'),
        ]);
        $user->syncRoles([User::ROLE_SUPER_ADMIN]);

        return $user;
    }
}
