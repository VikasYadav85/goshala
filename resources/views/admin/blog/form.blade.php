@extends('admin.layout')
@section('title', $post->exists ? 'Edit post' : 'New post')

@section('content')
<form method="POST" action="{{ $post->exists ? route('admin.blog.update', $post) : route('admin.blog.store') }}" enctype="multipart/form-data" class="admin-card p-6 max-w-4xl">
    @csrf
    @if ($post->exists) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2"><label for="blog_title" class="form-label">Title *</label><input id="blog_title" name="title" required value="{{ old('title', $post->title) }}" class="form-input"></div>
        <div>
            <label for="blog_category_id" class="form-label">Category</label>
            <select id="blog_category_id" name="category_id" class="form-select">
                <option value="">— None —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $post->category_id) == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="blog_status" class="form-label">Status *</label>
            <select id="blog_status" name="status" class="form-select">
                @foreach (['draft','published','archived'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $post->status) === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div><label for="blog_reading_minutes" class="form-label">Reading minutes</label><input id="blog_reading_minutes" type="number" name="reading_minutes" value="{{ old('reading_minutes', $post->reading_minutes) }}" class="form-input"></div>
        <div><label for="blog_published_at" class="form-label">Publish at</label><input id="blog_published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label for="blog_excerpt" class="form-label">Excerpt</label><textarea id="blog_excerpt" name="excerpt" rows="2" class="form-textarea">{{ old('excerpt', $post->excerpt) }}</textarea></div>
        <div class="sm:col-span-2"><label for="blog_body" class="form-label">Body *</label><textarea id="blog_body" name="body" rows="12" required class="form-textarea font-mono text-sm">{{ old('body', $post->body) }}</textarea></div>
        <div class="sm:col-span-2">
            <label for="blog_cover_image" class="form-label">Cover image</label>
            <input id="blog_cover_image" type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="form-input">
            <p class="text-xs text-gray-500 mt-1">JPEG, PNG or WebP, max 8 MB. Stored automatically as optimized WebP.</p>
            @if ($post->cover_image)<img src="{{ asset('storage/' . $post->cover_image) }}" class="mt-2 h-32 rounded-lg" alt="">@endif
        </div>
        <div class="sm:col-span-2"><label for="blog_tags" class="form-label">Tags (comma separated)</label><input id="blog_tags" name="tags" value="{{ old('tags', is_array($post->tags) ? implode(', ', $post->tags) : '') }}" class="form-input"></div>
        <div class="sm:col-span-2"><label for="blog_seo_title" class="form-label">SEO title</label><input id="blog_seo_title" name="seo_title" value="{{ old('seo_title', $post->seo_title) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label for="blog_seo_description" class="form-label">SEO description</label><textarea id="blog_seo_description" name="seo_description" rows="2" class="form-textarea">{{ old('seo_description', $post->seo_description) }}</textarea></div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured)) class="rounded"> Feature on home</label>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $post->exists ? 'Update' : 'Create' }}</button>
    </div>
</form>
@endsection
