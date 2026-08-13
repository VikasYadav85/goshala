@extends('admin.layout')
@section('title', $post->exists ? 'Edit post' : 'New post')

@section('content')
<form method="POST" action="{{ $post->exists ? route('admin.blog.update', $post) : route('admin.blog.store') }}" enctype="multipart/form-data" class="admin-card p-6 max-w-4xl">
    @csrf
    @if ($post->exists) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2"><label class="form-label">Title *</label><input name="title" required value="{{ old('title', $post->title) }}" class="form-input"></div>
        <div>
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select">
                <option value="">— None —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $post->category_id) == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Status *</label>
            <select name="status" class="form-select">
                @foreach (['draft','published','archived'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $post->status) === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="form-label">Reading minutes</label><input type="number" name="reading_minutes" value="{{ old('reading_minutes', $post->reading_minutes) }}" class="form-input"></div>
        <div><label class="form-label">Publish at</label><input type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label class="form-label">Excerpt</label><textarea name="excerpt" rows="2" class="form-textarea">{{ old('excerpt', $post->excerpt) }}</textarea></div>
        <div class="sm:col-span-2"><label class="form-label">Body *</label><textarea name="body" rows="12" required class="form-textarea font-mono text-sm">{{ old('body', $post->body) }}</textarea></div>
        <div class="sm:col-span-2">
            <label class="form-label">Cover image</label>
            <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="form-input">
            <p class="text-xs text-gray-500 mt-1">JPEG, PNG or WebP, max 8 MB. Stored automatically as optimized WebP.</p>
            @if ($post->cover_image)<img src="{{ asset('storage/' . $post->cover_image) }}" class="mt-2 h-32 rounded-lg" alt="">@endif
        </div>
        <div class="sm:col-span-2"><label class="form-label">Tags (comma separated)</label><input name="tags" value="{{ old('tags', is_array($post->tags) ? implode(', ', $post->tags) : '') }}" class="form-input"></div>
        <div class="sm:col-span-2"><label class="form-label">SEO title</label><input name="seo_title" value="{{ old('seo_title', $post->seo_title) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label class="form-label">SEO description</label><textarea name="seo_description" rows="2" class="form-textarea">{{ old('seo_description', $post->seo_description) }}</textarea></div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured)) class="rounded"> Feature on home</label>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $post->exists ? 'Update' : 'Create' }}</button>
    </div>
</form>
@endsection
