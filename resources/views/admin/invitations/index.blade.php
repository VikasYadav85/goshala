@extends('admin.layout')
@section('title', 'Invitations')

@section('content')
<x-admin.page-header title="Invitations" subtitle="Send branded invitations to guests — each one is emailed and logged here.">
    <x-slot:cta><a href="{{ route('admin.invitations.create') }}" class="btn btn-primary text-sm">+ New invitation</a></x-slot:cta>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-5 py-3">Invitee</th>
                <th class="text-left px-5 py-3">Occasion</th>
                <th class="text-left px-5 py-3">Event date</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-left px-5 py-3">Sent</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($invitations as $inv)
                <tr>
                    <td class="px-5 py-3 text-sm">
                        <div class="font-medium">{{ $inv->invitee_name }}</div>
                        <div class="text-xs text-gray-500">{{ $inv->invitee_email }}</div>
                    </td>
                    <td class="px-5 py-3 text-sm">{{ $inv->occasion }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $inv->event_date?->format('d M Y') ?? '—' }}</td>
                    <td class="px-5 py-3">
                        @if ($inv->status === \App\Models\Invitation::STATUS_SENT)
                            <span class="badge badge-success">Sent</span>
                        @elseif ($inv->status === \App\Models\Invitation::STATUS_FAILED)
                            <span class="badge badge-danger">Failed</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $inv->sent_at?->format('d M Y, H:i') ?? '—' }}</td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <form method="POST" action="{{ route('admin.invitations.resend', $inv) }}" class="inline">@csrf<button class="text-saffron-700 text-sm">Resend</button></form>
                        <form method="POST" action="{{ route('admin.invitations.destroy', $inv) }}" onsubmit="return confirm('Delete this invitation record?')" class="inline ml-2">@csrf @method('DELETE')<button class="text-red-600 text-sm">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-500 text-sm">No invitations sent yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $invitations->links() }}</div>
</div>
@endsection
