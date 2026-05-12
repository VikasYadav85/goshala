@extends('admin.layout')
@section('title', 'Messages')
@section('page_title', 'Messages')

@section('content')

<form method="GET" class="admin-card p-4 mb-5 flex flex-wrap gap-3">
    <select name="status" class="form-select max-w-[180px]">
        <option value="">All statuses</option>
        @foreach (['new','read','replied','spam','closed'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary text-sm">Filter</button>
</form>

<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-5 py-3">From</th>
                <th class="text-left px-5 py-3">Subject</th>
                <th class="text-left px-5 py-3">Type</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-left px-5 py-3">When</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($messages as $m)
                <tr class="{{ $m->status === 'new' ? 'bg-amber-50/30 font-medium' : '' }}">
                    <td class="px-5 py-3">
                        <div>{{ $m->name }}</div>
                        <div class="text-xs text-gray-500">{{ $m->email }}</div>
                    </td>
                    <td class="px-5 py-3 text-sm">{{ $m->subject ?: \Illuminate\Support\Str::limit($m->message, 50) }}</td>
                    <td class="px-5 py-3 text-sm">{{ \Illuminate\Support\Str::title($m->message_type) }}</td>
                    <td class="px-5 py-3">
                        @php $cls = ['new' => 'badge-warning','read' => 'badge-info','replied' => 'badge-success','spam' => 'badge-danger','closed' => 'badge-neutral'][$m->status] ?? 'badge-neutral'; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($m->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $m->created_at->diffForHumans() }}</td>
                    <td class="px-5 py-3 text-right"><a href="{{ route('admin.messages.show', $m) }}" class="text-saffron-700 text-sm">Open →</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-500 text-sm">Inbox is clear.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">{{ $messages->links() }}</div>
</div>

@endsection
