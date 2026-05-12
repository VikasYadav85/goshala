@extends('public.layout')
@section('title', 'Complete your donation')

@section('content')

<section class="py-20">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="card-soft p-8 text-center">
            <div class="text-5xl mb-4">🪔</div>
            <h1 class="font-display text-3xl font-bold text-saffron-900 mb-3">Complete your donation</h1>
            <p class="text-saffron-900/70 mb-6">Reference: <strong>{{ $donation->reference_no }}</strong></p>

            <div class="grid grid-cols-2 gap-3 text-left bg-saffron-50 rounded-2xl p-5 mb-6">
                <div class="text-saffron-700 text-sm">Donor</div>
                <div class="font-semibold text-saffron-900 text-sm">{{ $donation->donor_name }}</div>
                <div class="text-saffron-700 text-sm">Email</div>
                <div class="font-semibold text-saffron-900 text-sm">{{ $donation->donor_email }}</div>
                <div class="text-saffron-700 text-sm">Amount</div>
                <div class="font-display text-xl font-bold text-saffron-700">₹{{ number_format($donation->amount) }}</div>
            </div>

            @if (str_contains((string) $rzpKey, 'placeholder'))
                {{-- Local/dev: simulate the Razorpay round-trip --}}
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-left text-sm text-amber-900 mb-5">
                    <p class="font-semibold mb-1">Local mode</p>
                    <p>Razorpay credentials are not configured. Use the button below to simulate a successful payment locally and continue testing.</p>
                </div>
                <form method="POST" action="{{ route('donations.simulate', $donation) }}">
                    @csrf
                    <button class="btn btn-primary w-full">Simulate Successful Payment →</button>
                </form>
            @else
                <p class="text-sm text-saffron-900/70 mb-4">You'll be redirected to Razorpay to complete your payment securely.</p>
                <button id="rzp-pay" class="btn btn-primary w-full">Pay ₹{{ number_format($donation->amount) }} via Razorpay →</button>

                @push('head')
                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                @endpush

                @push('scripts')
                <script>
                    document.getElementById('rzp-pay').addEventListener('click', function () {
                        const options = {
                            key: @json($rzpKey),
                            amount: {{ $donation->amount * 100 }},
                            currency: 'INR',
                            name: 'Gopal Seva Samarpan Trust',
                            description: 'Donation • {{ $donation->reference_no }}',
                            order_id: @json($donation->razorpay_order_id),
                            prefill: {
                                name: @json($donation->donor_name),
                                email: @json($donation->donor_email),
                                contact: @json($donation->donor_phone),
                            },
                            theme: { color: '#ea580c' },
                            handler: function (response) {
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = @json(route('donations.callback', $donation));
                                form.innerHTML = `
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
                                    <input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">
                                    <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
                                `;
                                document.body.appendChild(form);
                                form.submit();
                            },
                        };
                        const rzp = new Razorpay(options);
                        rzp.open();
                    });
                </script>
                @endpush
            @endif

            <a href="{{ route('home') }}" class="btn btn-ghost mt-4">Cancel and return home</a>
        </div>
    </div>
</section>

@endsection
