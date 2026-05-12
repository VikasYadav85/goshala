@extends('public.layout')
@section('title', 'Thank you for your seva')

@section('content')

<section class="py-20">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="card-soft p-10 text-center bg-saffron-gradient">
            <div class="text-6xl mb-4 animate-float">🪔</div>
            <h1 class="font-display text-3xl font-bold text-saffron-900 mb-3">Thank you for your <span class="text-devanagari">सेवा</span>!</h1>
            <p class="text-saffron-900/80 max-w-md mx-auto mb-6">
                We have received your donation of <strong>₹{{ number_format($donation->amount) }}</strong>.
                @if ($donation->payment_status === \App\Models\Donation::STATUS_SUCCESS)
                    Your contribution will go straight toward fodder, medicine and shelter for our rescued cows.
                @else
                    Once payment confirmation is received, your 80G receipt will be emailed to you.
                @endif
            </p>

            <div class="bg-white rounded-2xl p-5 text-left mb-6 inline-block min-w-[240px]">
                <div class="text-xs text-saffron-700 uppercase">Reference</div>
                <div class="font-mono font-semibold text-saffron-900">{{ $donation->reference_no }}</div>
                <div class="text-xs text-saffron-700 uppercase mt-3">Status</div>
                <div class="font-semibold text-saffron-900">{{ \Illuminate\Support\Str::title(str_replace('_',' ',$donation->payment_status)) }}</div>
            </div>

            <p class="text-sm text-saffron-900/70 mb-6">
                A confirmation has been sent to <strong>{{ $donation->donor_email }}</strong>. If you don't receive it
                within a few minutes, please check spam or contact us at {{ $publicSettings['email'] }}.
            </p>

            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('home') }}" class="btn btn-secondary">Back to home</a>
                <a href="{{ route('goshala') }}" class="btn btn-primary">Sponsor a Cow</a>
            </div>
        </div>
    </div>
</section>

@endsection
