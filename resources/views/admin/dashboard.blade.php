@extends('admin.layout')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

{{-- KPIs --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach ([
        ['Total raised', '₹' . number_format($stats['total_raised']), 'bg-emerald-100 text-emerald-700'],
        ['This month', '₹' . number_format($stats['month_raised']), 'bg-blue-100 text-blue-700'],
        ['Successful donations', number_format($stats['total_donations']), 'bg-amber-100 text-amber-700'],
        ['Pending payments', number_format($stats['pending_payments']), 'bg-rose-100 text-rose-700'],
    ] as $kpi)
        <div class="admin-card p-5">
            <div class="text-xs uppercase tracking-widest text-gray-500">{{ $kpi[0] }}</div>
            <div class="font-display text-2xl font-bold text-gray-900 mt-1">{{ $kpi[1] }}</div>
            <span class="badge {{ $kpi[2] }} mt-3">Live</span>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
    @foreach ([
        ['Cows in care', number_format($stats['cows_total']), '🐄'],
        ['Volunteers', number_format($stats['volunteers_total']), '🌱'],
        ['Pending volunteers', number_format($stats['volunteers_pending']), '⏳'],
        ['Active campaigns', number_format($stats['campaigns_active']), '🎯'],
        ['Upcoming events', number_format($stats['events_upcoming']), '🎉'],
        ['New messages', number_format($stats['messages_new']), '✉️'],
    ] as $s)
        <div class="admin-card p-4">
            <div class="text-2xl">{{ $s[2] }}</div>
            <div class="text-xs uppercase tracking-widest text-gray-500 mt-1">{{ $s[0] }}</div>
            <div class="font-display text-xl font-bold text-gray-900">{{ $s[1] }}</div>
        </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-6 min-w-0">
    {{-- Recent donations --}}
    <div class="lg:col-span-2 admin-card min-w-0">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-display text-lg font-semibold">Recent successful donations</h2>
            <a href="{{ route('admin.donations.index') }}" class="text-sm text-saffron-700 hover:text-saffron-900">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full admin-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-5 py-3">Donor</th>
                        <th class="text-left px-5 py-3">Cause</th>
                        <th class="text-right px-5 py-3">Amount</th>
                        <th class="text-left px-5 py-3">When</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($recentDonations as $d)
                        <tr>
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.donations.show', $d) }}" class="font-medium text-gray-900 hover:text-saffron-700">{{ $d->donor_name }}</a>
                                <div class="text-xs text-gray-500">{{ $d->donor_email }}</div>
                            </td>
                            <td class="px-5 py-3 text-gray-600 text-xs">
                                @if ($d->category_id) Category · {{ $d->category->name }} @endif
                                @if ($d->campaign_id) Campaign · {{ $d->campaign->title }} @endif
                                @if ($d->cow_id) Cow · {{ $d->cow->name }} @endif
                                @if (is_null($d->category_id) && is_null($d->campaign_id) && is_null($d->cow_id)) General donation @endif
                            </td>
                            <td class="px-5 py-3 text-right font-semibold">₹{{ number_format($d->amount) }}</td>
                            <td class="px-5 py-3 text-gray-500 text-xs">{{ optional($d->paid_at)->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-gray-500 text-sm">No successful donations yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Side panels --}}
    <div class="space-y-6 min-w-0">
        <div class="admin-card">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-display text-lg font-semibold">Recent volunteers</h2>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse ($recentVolunteers as $v)
                    <li class="px-5 py-3">
                        <div class="font-medium text-gray-900 text-sm">{{ $v->full_name }}</div>
                        <div class="text-xs text-gray-500">{{ $v->city ?: '—' }} · {{ \Illuminate\Support\Str::title($v->status) }}</div>
                    </li>
                @empty
                    <li class="px-5 py-6 text-center text-sm text-gray-500">No volunteers yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="admin-card">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-display text-lg font-semibold">New messages</h2>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse ($recentMessages as $m)
                    <li class="px-5 py-3">
                        <a href="{{ route('admin.messages.show', $m) }}" class="font-medium text-gray-900 text-sm hover:text-saffron-700">{{ $m->name }}</a>
                        <div class="text-xs text-gray-500 truncate">{{ \Illuminate\Support\Str::limit($m->message, 80) }}</div>
                    </li>
                @empty
                    <li class="px-5 py-6 text-center text-sm text-gray-500">Inbox is clear.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

@endsection
