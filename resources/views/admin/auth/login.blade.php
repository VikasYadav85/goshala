<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login · Gopal Seva Trust</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fraunces:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-saffron-50 via-saffron-100 to-saffron-200 p-4">
    <div class="w-full max-w-md">
        <div class="card-soft p-8">
            <div class="text-center mb-6">
                <img src="{{ asset('img/logo.png') }}" alt="Gopal Seva Samarpan Trust" class="h-24 w-auto mx-auto mb-3">
                <h1 class="font-display text-2xl font-bold text-saffron-900">Admin Sign-in</h1>
                <p class="text-sm text-saffron-700 mt-1">Gopal Seva Samarpan Trust</p>
            </div>

            @if (session('error'))
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}" class="form-input" autofocus>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" required class="form-input">
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
