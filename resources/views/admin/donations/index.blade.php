@extends('admin.layout')
@section('title', 'Donations')
@section('page_title', 'Donations')

@section('content')

<form method="GET" class="admin-card p-4 mb-5 flex flex-wrap gap-3">
    <input name="search" value="{{ request('search') }}" placeholder="Search by donor name, email, reference…" class="form-input flex-1 min-w-[200px]">
    <select name="status" class="form-select max-w-[180px]">
        <option value="">All statuses</option>
        @foreach (['pending','processing','success','failed','refunded'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary text-sm">Filter</button>
</form>

<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full admin-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-5 py-3">Reference</th>
                    <th class="text-left px-5 py-3">Donor</th>
                    <th class="text-left px-5 py-3">Cause</th>
                    <th class="text-right px-5 py-3">Amount</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-left px-5 py-3">Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($donations as $d)
                    <tr>
                        <td class="px-5 py-3 font-mono text-xs">{{ $d->reference_no }}</td>
                        <td class="px-5 py-3">
                            <div class="font-medium text-gray-900">{{ $d->donor_name }}</div>
                            <div class="text-xs text-gray-500">{{ $d->donor_email }}</div>
                        </td>
                        <td class="px-5 py-3 text-xs text-gray-600">
                            @if ($d->category_id) {{ $d->category->name }} @endif
                            @if ($d->campaign_id) {{ $d->campaign->title }} @endif
                            @if ($d->cow_id) {{ $d->cow->name }} @endif
                            @if (is_null($d->category_id) && is_null($d->campaign_id) && is_null($d->cow_id)) General @endif
                        </td>
                        <td class="px-5 py-3 text-right font-semibold">₹{{ number_format($d->amount) }}</td>
                        <td class="px-5 py-3">
                            @php $cls = ['success' => 'badge-success','pending' => 'badge-warning','processing' => 'badge-info','failed' => 'badge-danger','refunded' => 'badge-neutral'][$d->payment_status] ?? 'badge-neutral'; @endphp
                            <span class="badge {{ $cls }}">{{ ucfirst($d->payment_status) }}</span>
                        </td>
                        <td class="px-5 py-3 text-xs text-gray-500">{{ $d->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3 text-right"><a href="{{ route('admin.donations.show', $d) }}" class="text-saffron-700 hover:text-saffron-900 text-sm">View →</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-500 text-sm">No donations match your filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $donations->links() }}</div>
</div>

@endsection
