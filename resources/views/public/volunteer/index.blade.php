@extends('public.layout')
@section('title', 'Volunteer — Become Part of Gau Seva')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Volunteer / Join us',
    'title' => 'Become a Part of <span class="text-devanagari text-saffron-200">गौ सेवा</span>.',
    'subtitle' => 'Whether you have an hour or a lifetime to give — there is a seva for you. Feed, rescue, organise events, share stories or fundraise.',
])

<section class="py-16">
    <div class="container mx-auto px-4 grid md:grid-cols-3 gap-6 mb-12">
        @foreach ([
            ['🌾', 'Feeding & Care', 'Help with daily feeding, grooming and shelter cleaning.'],
            ['🚑', 'Rescue Missions', 'Join our 24/7 rescue squad responding to abandoned/injured cattle.'],
            ['🪔', 'Festivals & Events', 'Coordinate aarti, pujan and devotee gatherings throughout the year.'],
            ['📱', 'Social Media', 'Share rescue stories, take photos, and grow our digital reach.'],
            ['💰', 'Fundraising', 'Run campaigns, reach corporates, and connect us with supporters.'],
            ['🩺', 'Medical Support', 'Veterinarians and medical students assist in checkups and treatments.'],
        ] as $opt)
            <div class="card-soft p-6">
                <div class="text-3xl mb-3">{{ $opt[0] }}</div>
                <h3 class="font-display text-lg font-semibold text-saffron-900 mb-1">{{ $opt[1] }}</h3>
                <p class="text-sm text-saffron-900/70">{{ $opt[2] }}</p>
            </div>
        @endforeach
    </div>

    <div class="container mx-auto px-4 max-w-3xl card-soft p-8">
        <h2 class="font-display text-2xl font-bold text-saffron-900 mb-6 heading-underline">Volunteer registration</h2>
        <form action="{{ route('volunteer.store') }}" method="POST">
            @csrf
            <div class="grid sm:grid-cols-2 gap-4">
                <div><label class="form-label">Full name *</label><input name="full_name" required value="{{ old('full_name') }}" class="form-input">@error('full_name')<div class="form-error">{{ $message }}</div>@enderror</div>
                <div><label class="form-label">Email *</label><input type="email" name="email" required value="{{ old('email') }}" class="form-input">@error('email')<div class="form-error">{{ $message }}</div>@enderror</div>
                <div><label class="form-label">Phone *</label><input name="phone" required value="{{ old('phone') }}" class="form-input">@error('phone')<div class="form-error">{{ $message }}</div>@enderror</div>
                <div><label class="form-label">Date of birth</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-input"></div>
                <div>
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select</option>
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div><label class="form-label">Occupation</label><input name="occupation" value="{{ old('occupation') }}" class="form-input"></div>
                <div><label class="form-label">City</label><input name="city" value="{{ old('city') }}" class="form-input"></div>
                <div><label class="form-label">State</label><input name="state" value="{{ old('state') }}" class="form-input"></div>
            </div>

            <div class="mt-5">
                <label class="form-label">Areas of interest (pick any)</label>
                <div class="grid sm:grid-cols-3 gap-2 text-sm">
                    @foreach (['feeding','events','rescue','social_media','fundraising','medical','construction','festivals'] as $area)
                        <label class="flex items-center gap-2 px-3 py-2 rounded-xl border border-saffron-200 cursor-pointer hover:bg-saffron-50">
                            <input type="checkbox" name="areas_of_interest[]" value="{{ $area }}" class="rounded border-saffron-300 text-saffron-600">
                            <span>{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $area)) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mt-5">
                <label class="form-label">Availability</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-sm">
                    @foreach (['weekdays','weekends','evenings','full_time'] as $av)
                        <label class="flex items-center gap-2 px-3 py-2 rounded-xl border border-saffron-200 cursor-pointer hover:bg-saffron-50">
                            <input type="checkbox" name="availability[]" value="{{ $av }}" class="rounded border-saffron-300 text-saffron-600">
                            <span>{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $av)) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4 mt-5">
                <div class="sm:col-span-2"><label class="form-label">Previous experience (optional)</label><textarea name="previous_experience" rows="3" class="form-textarea">{{ old('previous_experience') }}</textarea></div>
                <div class="sm:col-span-2"><label class="form-label">What inspires you to serve? (optional)</label><textarea name="motivation" rows="3" class="form-textarea">{{ old('motivation') }}</textarea></div>
                <div><label class="form-label">How did you hear about us?</label><input name="referral_source" class="form-input" value="{{ old('referral_source') }}"></div>
            </div>

            <div class="mt-8 flex justify-end">
                <button class="btn btn-primary">Register as Volunteer →</button>
            </div>
        </form>
    </div>
</section>

@endsection
