@extends('public.layout')
@section('title', 'Welcome to the Seva family')

@section('content')

<section class="py-20">
    <div class="container mx-auto px-4 max-w-xl text-center">
        <div class="card-soft p-10 bg-saffron-gradient">
            <div class="text-6xl mb-4 animate-float">🌿</div>
            <h1 class="font-display text-3xl font-bold text-saffron-900 mb-3">Welcome to our seva family!</h1>
            <p class="text-saffron-900/80 mb-6">We've received your registration. Our volunteer coordinator will reach out within 2-3 working days with the next steps.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Return Home</a>
        </div>
    </div>
</section>

@endsection
