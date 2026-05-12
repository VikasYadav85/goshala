@extends('admin.layout')
@section('title', 'Donation · ' . $donation->reference_no)
@section('page_title', 'Donation ' . $donation->reference_no)

@section('content')

<a href="{{ route('admin.donations.index') }}" class="text-sm text-saffron-700 hover:text-saffron-900">← Back to all donations</a>

<div class="grid lg:grid-cols-3 gap-6 mt-4">
    <div class="lg:col-span-2 admin-card p-6">
        <h2 class="font-display text-xl font-bold mb-4">Donor &amp; payment details</h2>
        <dl class="grid grid-cols-2 gap-y-2 gap-x-4 text-sm">
            <dt class="text-gray-500">Reference</dt><dd class="font-mono">{{ $donation->reference_no }}</dd>
            <dt class="text-gray-500">Donor</dt><dd>{{ $donation->donor_name }}</dd>
            <dt class="text-gray-500">Email</dt><dd>{{ $donation->donor_email }}</dd>
            <dt class="text-gray-500">Phone</dt><dd>{{ $donation->donor_phone ?: '—' }}</dd>
            <dt class="text-gray-500">PAN</dt><dd>{{ $donation->donor_pan ?: '—' }}</dd>
            <dt class="text-gray-500">Address</dt><dd>{{ $donation->donor_address }}, {{ $donation->donor_city }}, {{ $donation->donor_state }} {{ $donation->donor_pincode }}</dd>

            <dt class="text-gray-500 mt-3">Amount</dt><dd class="font-display text-2xl text-saffron-700 font-bold">₹{{ number_format($donation->amount) }}</dd>
            <dt class="text-gray-500">Frequency</dt><dd>{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $donation->frequency)) }}</dd>
            <dt class="text-gray-500">Method</dt><dd>{{ \Illuminate\Support\Str::title($donation->payment_method) }}</dd>
            <dt class="text-gray-500">Razorpay order</dt><dd class="font-mono text-xs">{{ $donation->razorpay_order_id ?: '—' }}</dd>
            <dt class="text-gray-500">Razorpay payment</dt><dd class="font-mono text-xs">{{ $donation->razorpay_payment_id ?: '—' }}</dd>
            <dt class="text-gray-500">Paid at</dt><dd>{{ optional($donation->paid_at)->format('d M Y, h:i A') ?: '—' }}</dd>

            @if ($donation->category)<dt class="text-gray-500 mt-3">Category</dt><dd>{{ $donation->category->name }}</dd>@endif
            @if ($donation->campaign)<dt class="text-gray-500">Campaign</dt><dd>{{ $donation->campaign->title }}</dd>@endif
            @if ($donation->cow)<dt class="text-gray-500">Cow</dt><dd>{{ $donation->cow->name }}</dd>@endif
            @if ($donation->dedication)<dt class="text-gray-500">Dedication</dt><dd>{{ $donation->dedication }}</dd>@endif
            @if ($donation->message)<dt class="text-gray-500">Message</dt><dd>{{ $donation->message }}</dd>@endif
            <dt class="text-gray-500">Wants 80G receipt</dt><dd>{{ $donation->wants_80g_receipt ? 'Yes' : 'No' }}</dd>
            <dt class="text-gray-500">Anonymous</dt><dd>{{ $donation->is_anonymous ? 'Yes' : 'No' }}</dd>
        </dl>
    </div>

    <form method="POST" action="{{ route('admin.donations.update', $donation) }}" class="admin-card p-6 h-fit">
        @csrf
        @method('PATCH')
        <h3 class="font-display text-lg font-bold mb-4">Update status</h3>
        <label class="form-label">Payment status</label>
        <select name="payment_status" class="form-select mb-4">
            @foreach (['pending','processing','success','failed','refunded'] as $s)
                <option value="{{ $s }}" @selected($donation->payment_status === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary w-full text-sm">Save</button>

        <p class="text-xs text-gray-500 mt-4">Marking as <strong>success</strong> auto-updates the campaign tally and sets the paid_at timestamp.</p>
    </form>
</div>

@endsection
