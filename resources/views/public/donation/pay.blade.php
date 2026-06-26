@extends('public.layout')
@section('title', 'Complete your donation')

@section('content')

<section class="py-20">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="card-soft p-8">
            <div class="text-center">
                <div class="text-5xl mb-4">🪔</div>
                <h1 class="font-display text-3xl font-bold text-saffron-900 mb-3">Complete your donation</h1>
                <p class="text-saffron-900/70 mb-6">Reference: <strong>{{ $donation->reference_no }}</strong></p>
            </div>

            <div class="grid grid-cols-2 gap-3 text-left bg-saffron-50 rounded-2xl p-5 mb-6">
                <div class="text-saffron-700 text-sm">Donor</div>
                <div class="font-semibold text-saffron-900 text-sm">{{ $donation->donor_name }}</div>
                <div class="text-saffron-700 text-sm">Email</div>
                <div class="font-semibold text-saffron-900 text-sm">{{ $donation->donor_email }}</div>
                <div class="text-saffron-700 text-sm">Amount</div>
                <div class="font-display text-xl font-bold text-saffron-700">₹{{ number_format($donation->amount) }}</div>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 text-sm mb-5">
                    {{ $errors->first() }}
                </div>
            @endif

            @if ($upiVpa)
                {{-- ===================== UPI (primary) ===================== --}}
                <div x-data="{ copied: false }" class="border border-saffron-200 rounded-2xl p-6 mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-2xl">📲</span>
                        <h2 class="font-display text-xl font-bold text-saffron-900">Pay ₹{{ number_format($donation->amount) }} via UPI</h2>
                    </div>

                    <div class="flex flex-col items-center text-center">
                        {{-- Dynamic QR (amount pre-filled). Rendered by resources/js/app.js --}}
                        <div data-upi-qr="{{ $upiUri }}" class="bg-white p-3 rounded-xl shadow-inner inline-block"></div>
                        <p class="text-xs text-saffron-900/60 mt-2">Scan with any UPI app — Paytm, PhonePe, Google&nbsp;Pay, BHIM</p>

                        <div class="mt-4 w-full max-w-xs">
                            <div class="text-xs text-saffron-700 uppercase tracking-wide mb-1">UPI ID</div>
                            <div class="flex items-center gap-2">
                                <code class="flex-1 bg-saffron-50 rounded-lg px-3 py-2 text-saffron-900 font-mono text-sm select-all">{{ $upiVpa }}</code>
                                <button type="button"
                                        class="btn btn-ghost text-sm px-3 py-2"
                                        @click="navigator.clipboard.writeText(@js($upiVpa)); copied = true; setTimeout(() => copied = false, 1500)">
                                    <span x-show="!copied">Copy</span>
                                    <span x-show="copied" x-cloak>Copied ✓</span>
                                </button>
                            </div>
                        </div>

                        {{-- On a phone this opens the UPI app directly --}}
                        <a href="{{ $upiUri }}" class="btn btn-primary w-full max-w-xs mt-4 md:hidden">Open UPI app to pay →</a>
                    </div>

                    {{-- After paying, donor reports the transaction reference --}}
                    <hr class="my-6 border-saffron-100">
                    <h3 class="font-semibold text-saffron-900 mb-1">After you’ve paid</h3>
                    <p class="text-sm text-saffron-900/70 mb-4">Enter the UPI transaction / UTR reference shown in your payment app so we can match and confirm your donation. You’ll get your 80G receipt once verified.</p>
                    <form method="POST" action="{{ route('donations.upi.confirm', $donation) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="form-label" for="upi_reference">UPI Transaction ID / UTR</label>
                            <input id="upi_reference" name="upi_reference" required minlength="6" maxlength="60"
                                   class="form-input w-full" placeholder="e.g. 412345678901"
                                   value="{{ old('upi_reference') }}">
                        </div>
                        <div>
                            <label class="form-label" for="upi_app">Paid using (optional)</label>
                            <select id="upi_app" name="upi_app" class="form-select w-full">
                                <option value="">Select app</option>
                                @foreach (['Paytm', 'PhonePe', 'Google Pay', 'BHIM', 'Other'] as $app)
                                    <option value="{{ $app }}" @selected(old('upi_app') === $app)>{{ $app }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary w-full">I’ve paid — submit reference →</button>
                    </form>
                </div>
            @endif

            @if (! str_contains((string) $rzpKey, 'placeholder') && $rzpKey)
                {{-- ===================== Razorpay (secondary, only if configured) ===================== --}}
                <div class="border border-saffron-200 rounded-2xl p-6 mb-2">
                    <h2 class="font-display text-lg font-bold text-saffron-900 mb-2">Or pay by card / netbanking</h2>
                    <p class="text-sm text-saffron-900/70 mb-4">You'll be redirected to Razorpay to complete your payment securely.</p>
                    <button id="rzp-pay" class="btn btn-secondary w-full">Pay ₹{{ number_format($donation->amount) }} via Razorpay →</button>
                </div>

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
                            name: @json($upiPayee),
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

            @if (app()->environment('local'))
                {{-- Local-only: simulate a successful payment for testing --}}
                <form method="POST" action="{{ route('donations.simulate', $donation) }}" class="mt-4">
                    @csrf
                    <button class="btn btn-ghost w-full text-sm">[local] Simulate successful payment</button>
                </form>
            @endif

            <div class="text-center">
                <a href="{{ route('home') }}" class="btn btn-ghost mt-4">Cancel and return home</a>
            </div>
        </div>
    </div>
</section>

@endsection
