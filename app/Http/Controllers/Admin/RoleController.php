<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /** Base roles that must never be deleted. */
    private const PROTECTED_ROLES = [
        User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN, User::ROLE_EDITOR, User::ROLE_STAFF,
    ];

    public function index(): View
    {
        $roles = Role::withCount('permissions', 'users')->orderBy('name')->get();
        return view('admin.roles.index', ['roles' => $roles, 'protected' => self::PROTECTED_ROLES]);
    }

    public function create(): View
    {
        return view('admin.roles.form', [
            'role' => new Role(),
            'grouped' => $this->groupedPermissions(),
            'assigned' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9_\-]+$/', Rule::unique('roles', 'name')],
            'permissions' => ['array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')],
        ], [], ['name' => 'role name']);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role created.');
    }

    public function edit(Role $role): View
    {
        return view('admin.roles.form', [
            'role' => $role,
            'grouped' => $this->groupedPermissions(),
            'assigned' => $role->permissions->pluck('name')->all(),
        ]);
    }

    public function update(Role $role, Request $request): RedirectResponse
    {
        // super_admin always holds every permission and its name is immutable.
        if ($role->name === User::ROLE_SUPER_ADMIN) {
            $role->syncPermissions(Permission::all());
            return redirect()->route('admin.roles.index')->with('success', 'Super admin always has all permissions.');
        }

        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:255', 'regex:/^[a-z0-9_\-]+$/',
                Rule::unique('roles', 'name')->ignore($role),
            ],
            'permissions' => ['array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')],
        ], [], ['name' => 'role name']);

        // Base roles keep their slug; only permissions change.
        if (! in_array($role->name, self::PROTECTED_ROLES, true)) {
            $role->name = $data['name'];
            $role->save();
        }
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if (in_array($role->name, self::PROTECTED_ROLES, true)) {
            return back()->with('error', 'Built-in roles cannot be deleted.');
        }
        if ($role->users()->exists()) {
            return back()->with('error', 'Reassign its users before deleting this role.');
        }

        $role->delete();
        return back()->with('success', 'Role removed.');
    }

    /** @return \Illuminate\Support\Collection<string, \Illuminate\Support\Collection<int, Permission>> */
    private function groupedPermissions()
    {
        return Permission::orderBy('name')->get()->groupBy(fn (Permission $p) => $p->group ?: 'Other');
    }
}
