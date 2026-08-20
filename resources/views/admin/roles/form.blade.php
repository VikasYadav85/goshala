@extends('admin.layout')
@section('title', $role->exists ? 'Edit role' : 'New role')

@php $isSuperAdmin = $role->name === 'super_admin'; @endphp

@section('content')
<form method="POST" action="{{ $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store') }}" class="admin-card p-6 max-w-3xl">
    @csrf
    @if ($role->exists) @method('PUT') @endif

    <div class="max-w-md">
        <label for="role_name" class="form-label">Role key *</label>
        <input id="role_name" name="name" required value="{{ old('name', $role->name) }}" class="form-input"
               @readonly($role->exists && in_array($role->name, ['super_admin','admin','editor','staff'], true))
               placeholder="e.g. accountant">
        <p class="text-xs text-gray-500 mt-1">Lowercase letters, numbers, hyphen/underscore. Built-in role keys can't be renamed.</p>
    </div>

    <div class="mt-6">
        <div class="form-label">Permissions</div>
        @if ($isSuperAdmin)
            <p class="text-sm text-gray-600 bg-amber-50 border border-amber-200 rounded-lg p-3">
                The <strong>super admin</strong> role always holds every permission (current and future) and cannot be limited.
            </p>
        @else
            <div class="space-y-5">
                @foreach ($grouped as $group => $permissions)
                    <div>
                        <div class="text-xs uppercase tracking-widest text-gray-400 mb-2">{{ $group }}</div>
                        <div class="grid sm:grid-cols-2 gap-2">
                            @foreach ($permissions as $permission)
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                           @checked(in_array($permission->name, old('permissions', $assigned), true)) class="rounded">
                                    <span>{{ $permission->group === 'Access' ? 'Access admin panel' : \Illuminate\Support\Str::title(str_replace('-', ' ', $permission->name)) }}</span>
                                    <code class="text-xs text-gray-400">{{ $permission->name }}</code>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $role->exists ? 'Update role' : 'Create role' }}</button>
    </div>
</form>
@endsection
