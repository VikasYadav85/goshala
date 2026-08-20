@extends('admin.layout')
@section('title', 'Users')

@section('content')
<x-admin.page-header title="Users" subtitle="Admin accounts and the role each one holds.">
    <x-slot:cta><a href="{{ route('admin.users.create') }}" class="btn btn-primary text-sm">+ New user</a></x-slot:cta>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-5 py-3">Name</th>
                <th class="text-left px-5 py-3">Email</th>
                <th class="text-left px-5 py-3">Role</th>
                <th class="text-left px-5 py-3">Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($users as $user)
                <tr>
                    <td class="px-5 py-3 text-sm font-medium">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-3">
                        @forelse ($user->roles as $role)
                            <span class="badge badge-info">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $role->name)) }}</span>
                        @empty
                            <span class="badge badge-neutral">No role</span>
                        @endforelse
                    </td>
                    <td class="px-5 py-3">
                        @if ($user->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-neutral">Disabled</span>@endif
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-saffron-700 text-sm">Edit</a>
                        @unless ($user->is(auth()->user()))
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')" class="inline ml-2">
                                @csrf @method('DELETE')
                                <button class="text-red-600 text-sm">Delete</button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-500 text-sm">No users yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection
