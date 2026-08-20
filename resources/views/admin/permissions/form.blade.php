@extends('admin.layout')
@section('title', $permission->exists ? 'Edit permission' : 'New permission')

@php $isCore = $permission->exists && in_array($permission->name, $core, true); @endphp

@section('content')
<form method="POST" action="{{ $permission->exists ? route('admin.permissions.update', $permission) : route('admin.permissions.store') }}" class="admin-card p-6 max-w-2xl">
    @csrf
    @if ($permission->exists) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2">
            <label for="permission_name" class="form-label">Permission key (slug) *</label>
            <input id="permission_name" name="name" required value="{{ old('name', $permission->name) }}" class="form-input"
                   @readonly($isCore) placeholder="e.g. manage-reports">
            <p class="text-xs text-gray-500 mt-1">
                Lowercase letters, numbers, hyphen/underscore. This is the key checked in code
                (<code>@@can('...')</code> / <code>permission:...</code>).
                @if ($isCore)<span class="text-amber-700">Built-in — key is locked, only the group is editable.</span>@endif
            </p>
        </div>
        <div class="sm:col-span-2">
            <label for="permission_group" class="form-label">Group</label>
            <input id="permission_group" name="group" value="{{ old('group', $permission->group) }}" class="form-input" placeholder="e.g. Content">
            <p class="text-xs text-gray-500 mt-1">Display grouping in the role permission matrix.</p>
        </div>
    </div>

    @if (! $permission->exists)
        <p class="text-xs text-gray-500 mt-4 bg-gray-50 border border-gray-200 rounded-lg p-3">
            Note: a new permission only gates something once you also add a <code>permission:key</code> check to a route.
            It is granted to <strong>super admin</strong> automatically.
        </p>
    @endif

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $permission->exists ? 'Update permission' : 'Create permission' }}</button>
    </div>
</form>
@endsection
