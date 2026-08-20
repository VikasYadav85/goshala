@extends('admin.layout')
@section('title', $testimonial->exists ? 'Edit testimonial' : 'New testimonial')

@section('content')
<form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" enctype="multipart/form-data" class="admin-card p-6 max-w-2xl">
    @csrf
    @if ($testimonial->exists) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div><label for="testimonial_name" class="form-label">Name *</label><input id="testimonial_name" name="name" required value="{{ old('name', $testimonial->name) }}" class="form-input"></div>
        <div><label for="testimonial_role" class="form-label">Role</label><input id="testimonial_role" name="role" value="{{ old('role', $testimonial->role) }}" placeholder="Donor / Volunteer / Devotee" class="form-input"></div>
        <div><label for="testimonial_location" class="form-label">Location</label><input id="testimonial_location" name="location" value="{{ old('location', $testimonial->location) }}" class="form-input"></div>
        <div><label for="testimonial_rating" class="form-label">Rating *</label><select id="testimonial_rating" name="rating" class="form-select">@for ($i=1;$i<=5;$i++)<option value="{{ $i }}" @selected(old('rating', $testimonial->rating) == $i)>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>@endfor</select></div>
        <div class="sm:col-span-2"><label for="testimonial_quote" class="form-label">Quote *</label><textarea id="testimonial_quote" name="quote" rows="4" required class="form-textarea">{{ old('quote', $testimonial->quote) }}</textarea></div>
        <div>
            <label for="testimonial_avatar" class="form-label">Avatar</label>
            <input id="testimonial_avatar" type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="form-input">
            <p class="text-xs text-gray-500 mt-1">JPEG, PNG or WebP, max 8 MB. Stored automatically as optimized WebP.</p>
            @if ($testimonial->avatar)<img src="{{ asset('storage/' . $testimonial->avatar) }}" class="mt-2 w-16 h-16 rounded-full" alt="">@endif
        </div>
        <div><label for="testimonial_sort_order" class="form-label">Sort order</label><input id="testimonial_sort_order" type="number" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}" class="form-input"></div>
        <div class="space-y-2 sm:col-span-2">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $testimonial->is_published ?? true)) class="rounded"> Published</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $testimonial->is_featured)) class="rounded"> Featured</label>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $testimonial->exists ? 'Update' : 'Create' }}</button>
    </div>
</form>
@endsection
