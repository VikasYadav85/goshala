@extends('admin.layout')
@section('title', $category->exists ? 'Edit donation category' : 'New donation category')

@section('content')
<form method="POST" action="{{ $category->exists ? route('admin.donation-categories.update', $category) : route('admin.donation-categories.store') }}" enctype="multipart/form-data" class="admin-card p-6 max-w-3xl">
    @csrf
    @if ($category->exists) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div><label class="form-label">Name *</label><input name="name" required value="{{ old('name', $category->name) }}" class="form-input"></div>
        <div><label class="form-label">Icon (emoji)</label><input name="icon" value="{{ old('icon', $category->icon) }}" placeholder="🐄" class="form-input"></div>
        <div><label class="form-label">Default amount (₹) *</label><input type="number" min="100" name="default_amount" required value="{{ old('default_amount', $category->default_amount ?: 1100) }}" class="form-input"></div>
        <div><label class="form-label">Suggested amounts (comma sep, ₹)</label><input name="suggested_amounts" value="{{ old('suggested_amounts', collect($category->suggested_amounts ?? [])->implode(',')) }}" class="form-input" placeholder="501, 1100, 2100, 5100"></div>
        <div class="sm:col-span-2"><label class="form-label">Short description</label><textarea name="short_description" rows="2" class="form-textarea">{{ old('short_description', $category->short_description) }}</textarea></div>
        <div class="sm:col-span-2"><label class="form-label">Description</label><textarea name="description" rows="4" class="form-textarea">{{ old('description', $category->description) }}</textarea></div>
        <div class="sm:col-span-2">
            <label class="form-label">Image</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-input">
            <p class="text-xs text-gray-500 mt-1">JPEG, PNG or WebP, max 8 MB. Stored automatically as optimized WebP.</p>
            @if ($category->image)<img src="{{ asset('storage/' . $category->image) }}" class="mt-2 h-32 rounded-lg" alt="">@endif
        </div>
        <div><label class="form-label">Sort order</label><input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="form-input"></div>
        <div class="space-y-2 mt-4">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true)) class="rounded"> Active</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $category->is_featured)) class="rounded"> Featured</label>
        </div>
    </div>
    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.donation-categories.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $category->exists ? 'Update' : 'Create' }}</button>
    </div>
</form>
@endsection
