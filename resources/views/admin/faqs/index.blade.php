@extends('admin.layout')
@section('title', 'FAQs')

@section('content')
<x-admin.page-header title="FAQs" subtitle="Frequently asked questions, grouped by topic.">
    <x-slot:cta><a href="{{ route('admin.faqs.create') }}" class="btn btn-primary text-sm">+ New FAQ</a></x-slot:cta>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr><th class="text-left px-5 py-3">Group</th><th class="text-left px-5 py-3">Question</th><th class="text-left px-5 py-3">Status</th><th></th></tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($faqs as $f)
                <tr>
                    <td class="px-5 py-3"><span class="badge badge-info">{{ \Illuminate\Support\Str::title($f->group) }}</span></td>
                    <td class="px-5 py-3 text-sm font-medium">{{ $f->question }}</td>
                    <td class="px-5 py-3">@if ($f->is_published)<span class="badge badge-success">Published</span>@else<span class="badge badge-neutral">Draft</span>@endif</td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.faqs.edit', $f) }}" class="text-saffron-700 text-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.faqs.destroy', $f) }}" onsubmit="return confirm('Delete?')" class="inline ml-2">@csrf @method('DELETE')<button class="text-red-600 text-sm">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-500 text-sm">No FAQs yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $faqs->links() }}</div>
</div>
@endsection
