@extends('admin.layout')
@section('title', $member->exists ? 'Edit member' : 'Add member')

@section('content')
<form method="POST" action="{{ $member->exists ? route('admin.team.update', $member) : route('admin.team.store') }}" enctype="multipart/form-data" class="admin-card p-6 max-w-2xl">
    @csrf
    @if ($member->exists) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div><label for="team_name" class="form-label">Name *</label><input id="team_name" name="name" required value="{{ old('name', $member->name) }}" class="form-input"></div>
        <div><label for="team_role" class="form-label">Role *</label><input id="team_role" name="role" required value="{{ old('role', $member->role) }}" class="form-input" placeholder="Founder / Trustee / Veterinarian"></div>
        <div>
            <label for="team_group" class="form-label">Group *</label>
            <select id="team_group" name="group" class="form-select">
                @foreach (['trustee','team','veterinarian','volunteer'] as $g)
                    <option value="{{ $g }}" @selected(old('group', $member->group) === $g)>{{ ucfirst($g) }}</option>
                @endforeach
            </select>
        </div>
        <div><label for="team_email" class="form-label">Email</label><input id="team_email" type="email" name="email" value="{{ old('email', $member->email) }}" class="form-input"></div>
        <div><label for="team_phone" class="form-label">Phone</label><input id="team_phone" name="phone" value="{{ old('phone', $member->phone) }}" class="form-input"></div>
        <div><label for="team_sort_order" class="form-label">Sort order</label><input id="team_sort_order" type="number" name="sort_order" value="{{ old('sort_order', $member->sort_order) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label for="team_bio" class="form-label">Bio</label><textarea id="team_bio" name="bio" rows="3" class="form-textarea">{{ old('bio', $member->bio) }}</textarea></div>
        <div class="sm:col-span-2">
            <label for="team_photo" class="form-label">Photo</label>
            <input id="team_photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="form-input">
            <p class="text-xs text-gray-500 mt-1">JPEG, PNG or WebP, max 8 MB. Stored automatically as optimized WebP.</p>
            @if ($member->photo)<img src="{{ asset('storage/' . $member->photo) }}" class="mt-2 w-24 h-24 rounded-full object-cover" alt="">@endif
        </div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $member->is_published ?? true)) class="rounded"> Published</label>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.team.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $member->exists ? 'Update' : 'Create' }}</button>
    </div>
</form>
@endsection
