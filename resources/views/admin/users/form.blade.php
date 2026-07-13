@extends('admin.layout')
@section('title', $user->exists ? 'Edit user' : 'New user')

@section('content')
<form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="admin-card p-6 max-w-2xl">
    @csrf
    @if ($user->exists) @method('PUT') @endif
    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="form-label">Name *</label>
            <input name="name" required value="{{ old('name', $user->name) }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Email *</label>
            <input type="email" name="email" required value="{{ old('email', $user->email) }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Phone</label>
            <input name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Role *</label>
            <select name="role" class="form-select">
                @foreach ($roles as $role)
                    <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $role)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">{{ $user->exists ? 'New password' : 'Password *' }}</label>
            <input type="password" name="password" @required(! $user->exists) class="form-input" autocomplete="new-password">
            @if ($user->exists)<p class="text-xs text-gray-500 mt-1">Leave blank to keep the current password.</p>@endif
        </div>
        <div>
            <label class="form-label">Confirm password</label>
            <input type="password" name="password_confirmation" @required(! $user->exists) class="form-input" autocomplete="new-password">
        </div>
        <label class="flex items-center gap-2 text-sm sm:col-span-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true)) class="rounded"> Active (can sign in)
        </label>
    </div>
    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $user->exists ? 'Update user' : 'Create user' }}</button>
    </div>
</form>
@endsection
