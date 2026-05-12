@extends('admin.layout')
@section('title', 'Campaigns')
@section('page_title', 'Campaigns')

@section('content')

<x-admin.page-header title="Campaigns" subtitle="Active fundraising campaigns and rescue appeals.">
    <x-slot:cta><a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary text-sm">+ New campaign</a></x-slot:cta>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-5 py-3">Campaign</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-right px-5 py-3">Progress</th>
                <th class="text-right px-5 py-3">Goal</th>
                <th class="text-left px-5 py-3">Ends</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($campaigns as $c)
                <tr>
                    <td class="px-5 py-3">
                        <div class="font-medium text-gray-900">{{ $c->title }}</div>
                        <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($c->short_description, 80) }}</div>
                    </td>
                    <td class="px-5 py-3">
                        @php $cls = ['active' => 'badge-success','upcoming' => 'badge-info','completed' => 'badge-neutral','emergency' => 'badge-danger'][$c->status] ?? 'badge-neutral'; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($c->status) }}</span>
                        @if ($c->is_emergency)<span class="badge badge-danger ml-1">Urgent</span>@endif
                    </td>
                    <td class="px-5 py-3 text-right">{{ $c->progress_percentage }}%</td>
                    <td class="px-5 py-3 text-right font-semibold">₹{{ number_format($c->goal_amount) }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ optional($c->end_date)->format('d M Y') ?: '—' }}</td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.campaigns.edit', $c) }}" class="text-saffron-700 text-sm hover:text-saffron-900">Edit</a>
                        <form method="POST" action="{{ route('admin.campaigns.destroy', $c) }}" onsubmit="return confirm('Delete?')" class="inline ml-2">
                            @csrf @method('DELETE')<button class="text-red-600 text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-500 text-sm">No campaigns yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">{{ $campaigns->links() }}</div>
</div>

@endsection
