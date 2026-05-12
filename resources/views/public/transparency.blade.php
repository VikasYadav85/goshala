@extends('public.layout')
@section('title', 'Transparency & Reports')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Donor accountability',
    'title' => 'Where Every Rupee Goes.',
    'subtitle' => 'Annual audited financials, donation utilisation, and full transparency on every campaign — that is our promise.',
])

<section class="py-16">
    <div class="container mx-auto px-4 grid md:grid-cols-3 gap-6">
        @foreach ([
            ['Fodder & Feed', 42, '#ea580c'],
            ['Medical Care', 24, '#d97706'],
            ['Shelter & Construction', 18, '#fbbf24'],
            ['Rescue Operations', 9, '#7c2d12'],
            ['Festivals & Pujan', 4, '#b45309'],
            ['Administration', 3, '#9a3412'],
        ] as $row)
            <div class="card-soft p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-semibold text-saffron-900">{{ $row[0] }}</span>
                    <span class="text-saffron-700 font-display text-xl">{{ $row[1] }}%</span>
                </div>
                <div class="w-full h-3 bg-saffron-50 rounded-full overflow-hidden border border-saffron-100">
                    <div class="h-full" style="width: {{ $row[1] }}%; background: {{ $row[2] }}"></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="container mx-auto px-4 mt-16 grid md:grid-cols-3 gap-6">
        @foreach ([
            ['📜', 'Annual report 2024-25', 'Detailed audited financials, programmes and impact.'],
            ['🧾', '80G certificate', 'Tax exemption certificate for individual donors.'],
            ['🏛️', 'NGO Darpan registration', 'Government of India NITI Aayog registration.'],
        ] as $r)
            <div class="card-soft p-6">
                <div class="text-3xl mb-2">{{ $r[0] }}</div>
                <h3 class="font-display text-lg font-semibold text-saffron-900">{{ $r[1] }}</h3>
                <p class="text-sm text-saffron-900/70 mt-1 mb-3">{{ $r[2] }}</p>
                <a href="{{ route('contact.index') }}" class="btn btn-secondary text-sm">Request copy</a>
            </div>
        @endforeach
    </div>
</section>

@endsection
