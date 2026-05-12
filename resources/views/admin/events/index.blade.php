@extends('admin.layout')
@section('title', 'Events')
@section('page_title', 'Events')

@section('content')

<x-admin.page-header title="Events &amp; Festivals" subtitle="Janmashtami, Gopashtami, Annadan, Pujan and other gatherings.">
    <x-slot:cta><a href="{{ route('admin.events.create') }}" class="btn btn-primary text-sm">+ New event</a></x-slot:cta>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-5 py-3">Event</th>
                <th class="text-left px-5 py-3">Type</th>
                <th class="text-left px-5 py-3">When</th>
                <th class="text-left px-5 py-3">Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($events as $e)
                <tr>
                    <td class="px-5 py-3">
                        <div class="font-medium">{{ $e->title }}</div>
                        <div class="text-xs text-gray-500">{{ $e->venue ?: '—' }}</div>
                    </td>
                    <td class="px-5 py-3 text-sm">{{ \Illuminate\Support\Str::title($e->type) }}</td>
                    <td class="px-5 py-3 text-sm">{{ $e->starts_at->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        @php $cls = ['upcoming' => 'badge-info','ongoing' => 'badge-success','completed' => 'badge-neutral','cancelled' => 'badge-danger'][$e->status] ?? 'badge-neutral'; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($e->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.events.edit', $e) }}" class="text-saffron-700 text-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.events.destroy', $e) }}" onsubmit="return confirm('Delete?')" class="inline ml-2">
                            @csrf @method('DELETE')<button class="text-red-600 text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-500 text-sm">No events yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">{{ $events->links() }}</div>
</div>

@endsection
