@extends('admin.layout')
@section('title', $album->exists ? 'Edit album' : 'New album')

@section('content')
<form method="POST" action="{{ $album->exists ? route('admin.gallery.update', $album) : route('admin.gallery.store') }}" enctype="multipart/form-data" class="admin-card p-6 max-w-2xl">
    @csrf
    @if ($album->exists) @method('PUT') @endif
    <div class="grid sm:grid-cols-2 gap-4">
        <div><label for="gallery_name" class="form-label">Name *</label><input id="gallery_name" name="name" required value="{{ old('name', $album->name) }}" class="form-input"></div>
        <div>
            <label for="gallery_category" class="form-label">Category *</label>
            <select id="gallery_category" name="category" class="form-select">
                @foreach (['cows','events','feeding','rescue','volunteers','media','general'] as $c)
                    <option value="{{ $c }}" @selected(old('category', $album->category) === $c)>{{ ucfirst($c) }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-2"><label for="gallery_description" class="form-label">Description</label><textarea id="gallery_description" name="description" rows="3" class="form-textarea">{{ old('description', $album->description) }}</textarea></div>
        <div class="sm:col-span-2">
            <label for="gallery_cover_image" class="form-label">Cover image</label>
            <input id="gallery_cover_image" type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="form-input">
            <p class="text-xs text-gray-500 mt-1">JPEG, PNG or WebP, max 8 MB. Auto-cropped to 1280×720 and stored as optimized WebP.</p>
            @error('cover_image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            @if ($album->cover_image)<img src="{{ asset('storage/' . $album->cover_image) }}" class="mt-2 h-32 rounded-lg" alt="">@endif
        </div>
        <div><label for="gallery_sort_order" class="form-label">Sort order</label><input id="gallery_sort_order" type="number" name="sort_order" value="{{ old('sort_order', $album->sort_order) }}" class="form-input"></div>
        <label class="flex items-center gap-2 text-sm self-end"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $album->is_published ?? true)) class="rounded"> Published</label>
    </div>
    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $album->exists ? 'Update' : 'Create' }}</button>
    </div>
</form>
@endsection
