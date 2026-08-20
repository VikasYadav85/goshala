@extends('admin.layout')
@section('title', 'Blog')
@section('page_title', 'Blog')

@section('content')

<x-admin.page-header title="Blog posts" subtitle="गौ सेवा ज्ञान — articles, guides and stories.">
    <x-slot:cta><a href="{{ route('admin.blog.create') }}" class="btn btn-primary text-sm">+ New post</a></x-slot:cta>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-5 py-3">Title</th>
                <th class="text-left px-5 py-3">Category</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-left px-5 py-3">Author</th>
                <th class="text-right px-5 py-3">Views</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($posts as $p)
                <tr>
                    <td class="px-5 py-3">
                        <div class="font-medium">{{ $p->title }}</div>
                        <div class="text-xs text-gray-500">{{ optional($p->published_at)->format('d M Y') ?: 'Draft' }}</div>
                    </td>
                    <td class="px-5 py-3 text-sm">{{ optional($p->category)->name ?? '—' }}</td>
                    <td class="px-5 py-3">
                        @php $cls = ['published' => 'badge-success','draft' => 'badge-warning','archived' => 'badge-neutral'][$p->status] ?? 'badge-neutral'; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($p->status) }}</span>
                        @if ($p->is_featured)<span class="badge badge-info ml-1">Featured</span>@endif
                    </td>
                    <td class="px-5 py-3 text-sm">{{ optional($p->author)->name }}</td>
                    <td class="px-5 py-3 text-right">{{ number_format($p->view_count) }}</td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.blog.edit', $p) }}" class="text-saffron-700 text-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.blog.destroy', $p) }}" onsubmit="return confirm('Delete?')" class="inline ml-2">
                            @csrf @method('DELETE')<button class="text-red-600 text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-500 text-sm">No posts yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $posts->links() }}</div>
</div>

@endsection
