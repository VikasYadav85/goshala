<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Seeds the RBAC catalog from config/rbac.php: permissions (with display group),
 * the four base roles, and their permission presets. Idempotent — safe to re-run.
 * super_admin receives every permission (current and future via UI) and also
 * bypasses all gates through AppServiceProvider's Gate::before.
 */
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = 'web';

        // 1. Permissions (with group).
        foreach (config('rbac.permissions') as $name => $meta) {
            Permission::updateOrCreate(
                ['name' => $name, 'guard_name' => $guard],
                ['group' => $meta['group']],
            );
        }

        // 2. super_admin — gets everything.
        $superAdmin = Role::firstOrCreate(['name' => User::ROLE_SUPER_ADMIN, 'guard_name' => $guard]);
        $superAdmin->syncPermissions(Permission::where('guard_name', $guard)->get());

        // 3. Preset roles.
        foreach (config('rbac.roles') as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => $guard]);
            $role->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
