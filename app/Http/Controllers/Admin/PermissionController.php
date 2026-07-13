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

class PermissionController extends Controller
{
    public function index(): View
    {
        $grouped = Permission::withCount('roles')->orderBy('name')->get()
            ->groupBy(fn (Permission $p) => $p->group ?: 'Other');

        return view('admin.permissions.index', [
            'grouped' => $grouped,
            'core' => $this->coreKeys(),
        ]);
    }

    public function create(): View
    {
        return view('admin.permissions.form', ['permission' => new Permission(), 'core' => $this->coreKeys()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $permission = Permission::create([
            'name' => $data['name'],
            'group' => $data['group'] ?: null,
            'guard_name' => 'web',
        ]);

        // New permissions are always granted to super_admin (it holds everything).
        Role::where('name', User::ROLE_SUPER_ADMIN)->first()?->givePermissionTo($permission);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created.');
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.form', ['permission' => $permission, 'core' => $this->coreKeys()]);
    }

    public function update(Permission $permission, Request $request): RedirectResponse
    {
        // Core permissions gate real routes — their key (name) is immutable; only the group is editable.
        $isCore = in_array($permission->name, $this->coreKeys(), true);
        $data = $this->validated($request, $permission, lockName: $isCore);

        $permission->group = $data['group'] ?: null;
        if (! $isCore) {
            $permission->name = $data['name'];
        }
        $permission->save();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        if (in_array($permission->name, $this->coreKeys(), true)) {
            return back()->with('error', 'Built-in permissions cannot be deleted (they protect admin sections).');
        }
        if ($permission->roles()->exists()) {
            return back()->with('error', 'Remove this permission from all roles before deleting it.');
        }

        $permission->delete();
        return back()->with('success', 'Permission removed.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?Permission $permission = null, bool $lockName = false): array
    {
        $rules = [
            'name' => [
                'required', 'string', 'max:255', 'regex:/^[a-z0-9_\-]+$/',
                Rule::unique('permissions', 'name')->ignore($permission),
            ],
            'group' => ['nullable', 'string', 'max:255'],
        ];
        if ($lockName) {
            $rules['name'] = ['nullable']; // ignored on core permissions
        }

        return $request->validate($rules, [], ['name' => 'permission key']);
    }

    /** Permission keys defined in config — these gate live routes and are protected. */
    private function coreKeys(): array
    {
        return array_keys(config('rbac.permissions'));
    }
}
