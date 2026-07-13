<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create(['role' => $role, 'is_active' => true]);
        $user->syncRoles([$role]);

        return $user;
    }

    // --- Access-control menu is super_admin only ---

    public function test_super_admin_can_open_user_management(): void
    {
        $this->actingAs($this->userWithRole(User::ROLE_SUPER_ADMIN))
            ->get(route('admin.users.index'))->assertOk();
    }

    public function test_admin_and_editor_cannot_open_user_management(): void
    {
        $this->actingAs($this->userWithRole(User::ROLE_ADMIN))
            ->get(route('admin.users.index'))->assertForbidden();

        $this->actingAs($this->userWithRole(User::ROLE_EDITOR))
            ->get(route('admin.roles.index'))->assertForbidden();
    }

    // --- Creating users ---

    public function test_super_admin_can_create_a_user_with_a_role(): void
    {
        $this->actingAs($this->userWithRole(User::ROLE_SUPER_ADMIN))
            ->post(route('admin.users.store'), [
                'name' => 'New Secretary',
                'email' => 'sec@gsstg.test',
                'role' => User::ROLE_EDITOR,
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'is_active' => '1',
            ])->assertRedirect(route('admin.users.index'));

        $user = User::firstWhere('email', 'sec@gsstg.test');
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole(User::ROLE_EDITOR));
    }

    // --- Permission changes flip section access ---

    public function test_granting_a_permission_to_a_role_opens_that_section(): void
    {
        // A brand-new role with only panel access — no FAQ access yet.
        $role = Role::create(['name' => 'secretary', 'guard_name' => 'web']);
        $role->givePermissionTo(User::PERMISSION_ACCESS_ADMIN);

        $user = User::factory()->create(['role' => 'secretary', 'is_active' => true]);
        $user->syncRoles(['secretary']);

        $this->actingAs($user)->get(route('admin.faqs.create'))->assertForbidden();

        // Grant the FAQ permission → same user can now reach it.
        $role->givePermissionTo('manage-faqs');
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->actingAs($user)->get(route('admin.faqs.create'))->assertOk();
    }

    // --- Guards ---

    public function test_cannot_delete_the_last_super_admin(): void
    {
        $super = $this->userWithRole(User::ROLE_SUPER_ADMIN);

        $this->actingAs($super)
            ->delete(route('admin.users.destroy', $super))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $super->id]);
    }

    public function test_new_permission_is_granted_to_super_admin(): void
    {
        $this->actingAs($this->userWithRole(User::ROLE_SUPER_ADMIN))
            ->post(route('admin.permissions.store'), ['name' => 'manage-reports', 'group' => 'Content'])
            ->assertRedirect(route('admin.permissions.index'));

        $this->assertTrue(
            Role::findByName(User::ROLE_SUPER_ADMIN, 'web')->hasPermissionTo('manage-reports')
        );
    }

    public function test_super_admin_bypasses_all_gates(): void
    {
        // super_admin has no explicit 'manage-reports' preset yet reaches everything via Gate::before.
        $super = $this->userWithRole(User::ROLE_SUPER_ADMIN);
        Permission::create(['name' => 'random-future-perm', 'guard_name' => 'web']);

        $this->assertTrue($super->can('random-future-perm'));
    }
}
