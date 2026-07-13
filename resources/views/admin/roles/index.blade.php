@extends('admin.layout')
@section('title', 'Roles')

@section('content')
<x-admin.page-header title="Roles" subtitle="Each role bundles a set of permissions. Assign a role to a user to grant it.">
    <x-slot:cta><a href="{{ route('admin.roles.create') }}" class="btn btn-primary text-sm">+ New role</a></x-slot:cta>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-5 py-3">Role</th>
                <th class="text-left px-5 py-3">Permissions</th>
                <th class="text-left px-5 py-3">Users</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($roles as $role)
                <tr>
                    <td class="px-5 py-3 text-sm font-medium">
                        {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $role->name)) }}
                        @if (in_array($role->name, $protected, true))<span class="badge badge-neutral ml-1">Built-in</span>@endif
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">
                        @if ($role->name === 'super_admin')<span class="badge badge-warning">All permissions</span>@else{{ $role->permissions_count }}@endif
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $role->users_count }}</td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="text-saffron-700 text-sm">Edit</a>
                        @unless (in_array($role->name, $protected, true))
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Delete this role?')" class="inline ml-2">
                                @csrf @method('DELETE')
                                <button class="text-red-600 text-sm">Delete</button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
