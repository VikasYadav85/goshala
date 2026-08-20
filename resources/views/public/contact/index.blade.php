@extends('public.layout')
@section('title', 'Contact — Visit our Goshala')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Contact',
    'title' => 'Come, Feel the <span class="text-saffron-200 text-devanagari">दिव्य</span> Presence.',
    'subtitle' => 'Visit our Goshala, schedule a Gau Pujan, or simply say hello. We respond within 24 hours.',
])

<section class="py-16">
    <div class="container mx-auto px-4 grid lg:grid-cols-3 gap-8">
        <aside class="space-y-4">
            <div class="card-soft p-6">
                <h3 class="font-display text-lg font-semibold text-saffron-900 mb-3">Reach us</h3>
                @if (!empty($publicSettings['address']))
                    <p class="text-xs uppercase tracking-widest text-saffron-600 mb-0.5">Goshala</p>
                    <p class="text-sm text-saffron-900/80 mb-3">{{ $publicSettings['address'] }}</p>
                @endif
                @if (!empty($publicSettings['registered_office']))
                    <p class="text-xs uppercase tracking-widest text-saffron-600 mb-0.5">Registered Office</p>
                    <p class="text-sm text-saffron-900/80 mb-3">{{ $publicSettings['registered_office'] }}</p>
                @endif
                <p class="text-sm"><a href="tel:{{ $publicSettings['phone'] }}" class="text-saffron-700 hover:text-saffron-900">📞 {{ $publicSettings['phone'] }}</a></p>
                <p class="text-sm"><a href="mailto:{{ $publicSettings['email'] }}" class="text-saffron-700 hover:text-saffron-900">✉️ {{ $publicSettings['email'] }}</a></p>
                <p class="text-sm"><a href="https://wa.me/{{ ltrim($publicSettings['whatsapp'] ?? '', '+') }}" class="text-saffron-700 hover:text-saffron-900">💬 WhatsApp: {{ $publicSettings['whatsapp'] }}</a></p>
            </div>

            <div class="card-soft p-6 bg-saffron-50">
                <h3 class="font-display text-lg font-semibold text-saffron-900 mb-2">Visit Goshala</h3>
                <p class="text-sm text-saffron-900/80 mb-3">Visiting hours: 6 AM – 7 PM, every day</p>
                <a href="https://www.google.com/maps?q=25.81661210056945,82.66854442980431"
                target="_blank"
                rel="noopener"
                class="btn btn-secondary text-sm">
                📍 View on Google Maps
             </a>
            </div>

            <div class="aspect-video rounded-2xl overflow-hidden relative">
                <iframe class="w-full h-full"
                    title="Gopal Samarpan Sewa Charitable Trust location map"
                    src="https://maps.google.com/maps?q=25.81661210056945,82.66854442980431&z=16&output=embed&iwloc=near"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>


        </aside>

        <form action="{{ route('contact.store') }}" method="POST" class="card-soft p-5 sm:p-8 lg:col-span-2 min-w-0">
            @csrf
            <h2 class="font-display text-2xl font-bold text-saffron-900 mb-6 heading-underline">Send us a message</h2>

            @if (session('success'))
                <div class="mb-5 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid sm:grid-cols-2 gap-4">
                <div><label for="contact_name" class="form-label">Full name *</label><input id="contact_name" name="name" required value="{{ old('name') }}" class="form-input">@error('name')<div class="form-error">{{ $message }}</div>@enderror</div>
                <div><label for="contact_email" class="form-label">Email *</label><input id="contact_email" type="email" name="email" required value="{{ old('email') }}" class="form-input">@error('email')<div class="form-error">{{ $message }}</div>@enderror</div>
                <div><label for="contact_phone" class="form-label">Phone</label><input id="contact_phone" name="phone" value="{{ old('phone') }}" class="form-input"></div>
                <div>
                    <label for="contact_message_type" class="form-label">Message type *</label>
                    <select id="contact_message_type" name="message_type" class="form-select">
                        <option value="general">General inquiry</option>
                        <option value="donation">Donation</option>
                        <option value="volunteer">Volunteering</option>
                        <option value="visit">Visit / Pujan</option>
                        <option value="partnership">Partnership / CSR</option>
                    </select>
                </div>
                <div class="sm:col-span-2"><label for="contact_subject" class="form-label">Subject</label><input id="contact_subject" name="subject" value="{{ old('subject') }}" class="form-input"></div>
                <div class="sm:col-span-2"><label for="contact_message" class="form-label">Message *</label><textarea id="contact_message" name="message" rows="6" required class="form-textarea">{{ old('message') }}</textarea>@error('message')<div class="form-error">{{ $message }}</div>@enderror</div>
            </div>
            <div class="mt-6 flex justify-end">
                <button class="btn btn-primary">Send Message →</button>
            </div>
        </form>
    </div>
</section>

@endsection
