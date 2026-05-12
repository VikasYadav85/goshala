@extends('admin.layout')
@section('title', 'Donation categories')

@section('content')
<x-admin.page-header title="Donation categories" subtitle="The seva programs shown on the donation page.">
    <x-slot:cta><a href="{{ route('admin.donation-categories.create') }}" class="btn btn-primary text-sm">+ New category</a></x-slot:cta>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr><th class="text-left px-5 py-3">Name</th><th class="text-left px-5 py-3">Default</th><th class="text-left px-5 py-3">Suggested</th><th class="text-left px-5 py-3">Status</th><th></th></tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($categories as $c)
                <tr>
                    <td class="px-5 py-3">
                        <div class="font-medium">{{ $c->icon ?: '🐄' }} {{ $c->name }}</div>
                        <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($c->short_description, 60) }}</div>
                    </td>
                    <td class="px-5 py-3 text-sm">₹{{ number_format($c->default_amount) }}</td>
                    <td class="px-5 py-3 text-xs">{{ collect($c->suggested_amounts ?? [])->map(fn ($v) => '₹'.number_format($v))->implode(', ') }}</td>
                    <td class="px-5 py-3">
                        @if ($c->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-neutral">Inactive</span>@endif
                        @if ($c->is_featured)<span class="badge badge-info ml-1">Featured</span>@endif
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.donation-categories.edit', $c) }}" class="text-saffron-700 text-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.donation-categories.destroy', $c) }}" onsubmit="return confirm('Delete?')" class="inline ml-2">
                            @csrf @method('DELETE')<button class="text-red-600 text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-500 text-sm">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">{{ $categories->links() }}</div>
</div>
@endsection
