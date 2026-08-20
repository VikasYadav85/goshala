<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login · Gopal Samarpan Sewa Charitable Trust</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fraunces:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-saffron-50 via-saffron-100 to-saffron-200 p-4">
    <div class="w-full max-w-md">
        <div class="card-soft p-8">
            <div class="text-center mb-6">
                <img src="{{ asset('img/logo.png') }}?v={{ @filemtime(public_path('img/logo.png')) }}" alt="Gopal Samarpan Sewa Charitable Trust" class="h-24 w-auto mx-auto mb-3">
                <h1 class="font-display text-2xl font-bold text-saffron-900">Admin Sign-in</h1>
                <p class="text-sm text-saffron-700 mt-1">Gopal Samarpan Sewa Charitable Trust</p>
            </div>

            @if (session('error'))
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="admin_email" class="form-label">Email</label>
                    <input id="admin_email" type="email" name="email" required value="{{ old('email') }}" class="form-input" autocomplete="email" autofocus>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div x-data="{ show: false }">
                    <label for="admin_password" class="form-label">Password</label>
                    <div class="relative">
                        <input id="admin_password" :type="show ? 'text' : 'password'" name="password" required class="form-input pr-11" autocomplete="current-password">
                        <button type="button" @click="show = !show" :aria-label="show ? 'Hide password' : 'Show password'"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-saffron-500 hover:text-saffron-800">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm text-saffron-900/80">
                    <input type="checkbox" name="remember" class="rounded border-saffron-300 text-saffron-600">
                    Keep me signed in
                </label>
                <button class="btn btn-primary w-full">Sign in →</button>
            </form>

            <div class="text-center mt-6 text-sm">
                <a href="{{ route('home') }}" class="text-saffron-700 hover:text-saffron-900">← Back to website</a>
            </div>
        </div>
    </div>
</body>
</html>
