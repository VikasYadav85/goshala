@extends('public.layout')
@section('title', 'Donate — Checkout')
@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Checkout',
    'title' => 'Just one step away from <span class="text-devanagari text-saffron-200">सेवा</span>.',
    'subtitle' => 'Your contribution will be utilised toward feeding, medical care, and shelter for our rescued cows.',
])

<section class="py-16">
    <div class="container mx-auto px-4 grid lg:grid-cols-3 gap-8">
        <form action="{{ route('donations.store') }}" method="POST" class="lg:col-span-2 card-soft p-8">
            @csrf

            @if ($category)<input type="hidden" name="donation_category_id" value="{{ $category->id }}">@endif
            @if ($campaign)<input type="hidden" name="campaign_id" value="{{ $campaign->id }}">@endif
            @if ($cow)<input type="hidden" name="cow_id" value="{{ $cow->id }}">@endif

            <h2 class="font-display text-2xl font-bold text-saffron-900 mb-6 heading-underline">Your details</h2>

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="form-label">Full name *</label>
                    <input name="donor_name" required maxlength="120" value="{{ old('donor_name') }}" class="form-input">
                    @error('donor_name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label">Email *</label>
                    <input type="email" name="donor_email" required value="{{ old('donor_email') }}" class="form-input">
                    @error('donor_email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label">Phone *</label>
                    <input name="donor_phone" required value="{{ old('donor_phone') }}" class="form-input">
                    @error('donor_phone')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label">PAN (for 80G receipt)</label>
                    <input name="donor_pan" maxlength="20" value="{{ old('donor_pan') }}" class="form-input" placeholder="ABCDE1234F">
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Address</label>
                    <input name="donor_address" value="{{ old('donor_address') }}" class="form-input">
                </div>
                <div><label class="form-label">City</label><input name="donor_city" value="{{ old('donor_city') }}" class="form-input"></div>
                <div><label class="form-label">State</label><input name="donor_state" value="{{ old('donor_state') }}" class="form-input"></div>
                <div><label class="form-label">Pincode</label><input name="donor_pincode" value="{{ old('donor_pincode') }}" class="form-input"></div>
                <div><label class="form-label">Country</label><input name="donor_country" value="{{ old('donor_country', 'India') }}" class="form-input"></div>
            </div>

            <h2 class="font-display text-2xl font-bold text-saffron-900 mt-8 mb-6 heading-underline">Your contribution</h2>

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="form-label">Amount (₹) *</label>
                    <input type="number" name="amount" required min="10" value="{{ old('amount', $amount) }}" class="form-input">
                    @error('amount')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label">Frequency *</label>
                    <select name="frequency" class="form-select">
                        <option value="one_time" {{ old('frequency') === 'one_time' ? 'selected' : '' }}>One-time donation</option>
                        <option value="monthly" {{ old('frequency') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ old('frequency') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Dedication (optional)</label>
                    <input name="dedication" maxlength="255" value="{{ old('dedication') }}" class="form-input" placeholder="In memory of, in honour of, on the occasion of...">
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Message (optional)</label>
                    <textarea name="message" rows="3" class="form-textarea" placeholder="Any prayer or message you'd like to share with the Goshala team">{{ old('message') }}</textarea>
                </div>
            </div>

            <div class="space-y-2 mt-3">
                <label class="flex items-center gap-2 text-sm text-saffron-900/80">
                    <input type="checkbox" name="wants_80g_receipt" value="1" checked class="rounded border-saffron-300 text-saffron-600">
                    Send me an 80G tax exemption receipt by email
                </label>
                <label class="flex items-center gap-2 text-sm text-saffron-900/80">
                    <input type="checkbox" name="is_anonymous" value="1" class="rounded border-saffron-300 text-saffron-600">
                    Make this donation anonymous on the public donor wall
                </label>
            </div>

            <div class="mt-8 flex flex-wrap gap-3 justify-end">
                <a href="{{ route('donations.index') }}" class="btn btn-ghost">← Back</a>
                <button class="btn btn-primary">Continue to Payment →</button>
            </div>
        </form>

        <aside class="space-y-4">
            <div class="card-soft p-6">
                <h3 class="font-display text-lg font-semibold text-saffron-900 mb-4 heading-underline">Your seva</h3>
                @if ($category)
                    <div class="text-xs uppercase tracking-widest text-saffron-700">Category</div>
                    <div class="font-semibold text-saffron-900">{{ $category->name }}</div>
                @endif
                @if ($campaign)
                    <div class="text-xs uppercase tracking-widest text-saffron-700 mt-3">Campaign</div>
                    <div class="font-semibold text-saffron-900">{{ $campaign->title }}</div>
                @endif
                @if ($cow)
                    <div class="text-xs uppercase tracking-widest text-saffron-700 mt-3">Sponsoring</div>
                    <div class="font-semibold text-saffron-900">{{ $cow->name }} — {{ $cow->breed ?? 'Indian Desi' }}</div>
                @endif
                <hr class="my-4 border-saffron-100">
                <div class="text-xs uppercase tracking-widest text-saffron-700">Suggested amount</div>
                <div class="font-display text-3xl text-saffron-700 font-bold">₹{{ number_format($amount) }}</div>
                <p class="text-xs text-saffron-700 mt-3">You can change the amount on the form. 100% of donations are utilised for Gau Seva.</p>
            </div>

            <div class="card-soft p-6 bg-saffron-50">
                <h3 class="font-display text-lg font-semibold text-saffron-900 mb-2">Why we are different</h3>
                <ul class="text-sm text-saffron-900/80 space-y-2">
                    <li>✅ 80G tax-exempt receipt</li>
                    <li>✅ 100% utilisation report</li>
                    <li>✅ Annual audit + impact report</li>
                    <li>✅ Photo updates on cow sponsorships</li>
                </ul>
            </div>
        </aside>
    </div>
</section>

@endsection
