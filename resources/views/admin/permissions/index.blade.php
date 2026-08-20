@extends('admin.layout')
@section('title', 'Permissions')

@section('content')
<x-admin.page-header title="Permissions" subtitle="The individual abilities that roles are built from.">
    <x-slot:cta><a href="{{ route('admin.permissions.create') }}" class="btn btn-primary text-sm">+ New permission</a></x-slot:cta>
</x-admin.page-header>

<div class="space-y-6">
    @foreach ($grouped as $group => $permissions)
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 text-xs uppercase tracking-widest text-gray-500 font-semibold">{{ $group }}</div>
            <div class="overflow-x-auto">
            <table class="w-full admin-table">
                <tbody class="divide-y divide-gray-100">
                    @foreach ($permissions as $permission)
                        <tr>
                            <td class="px-5 py-3 text-sm font-medium"><code>{{ $permission->name }}</code></td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $permission->roles_count }} role(s)</td>
                            <td class="px-5 py-3">
                                @if (in_array($permission->name, $core, true))<span class="badge badge-neutral">Built-in</span>@endif
                            </td>
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-saffron-700 text-sm">Edit</a>
                                @unless (in_array($permission->name, $core, true))
                                    <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" onsubmit="return confirm('Delete this permission?')" class="inline ml-2">
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
        </div>
    @endforeach
</div>
@endsection
