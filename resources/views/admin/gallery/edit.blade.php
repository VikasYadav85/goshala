@extends('admin.layout')
@section('title', 'Manage album · ' . $album->name)

@section('content')

<a href="{{ route('admin.gallery.index') }}" class="text-sm text-saffron-700 hover:text-saffron-900">← Back to albums</a>

<div class="grid lg:grid-cols-3 gap-6 mt-4 min-w-0">
    <form method="POST" action="{{ route('admin.gallery.update', $album) }}" enctype="multipart/form-data" class="admin-card p-6 lg:col-span-1 h-fit min-w-0">
        @csrf @method('PUT')
        <h2 class="font-display text-xl font-bold mb-4">Album details</h2>
        <div class="space-y-4">
            <div><label for="album_name" class="form-label">Name</label><input id="album_name" name="name" required value="{{ $album->name }}" class="form-input"></div>
            <div>
                <label for="album_category" class="form-label">Category</label>
                <select id="album_category" name="category" class="form-select">
                    @foreach (['cows','events','feeding','rescue','volunteers','media','general'] as $c)
                        <option value="{{ $c }}" @selected($album->category === $c)>{{ ucfirst($c) }}</option>
                    @endforeach
                </select>
            </div>
            <div><label for="album_description" class="form-label">Description</label><textarea id="album_description" name="description" rows="3" class="form-textarea">{{ $album->description }}</textarea></div>
            <div><label for="album_cover_image" class="form-label">Cover image</label><input id="album_cover_image" type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="form-input"><p class="text-xs text-gray-500 mt-1">JPEG, PNG or WebP, max 8 MB. Auto-cropped to 1280×720 and stored as optimized WebP.</p>@error('cover_image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror</div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" @checked($album->is_published) class="rounded"> Published</label>
            <button class="btn btn-primary w-full text-sm">Save details</button>
        </div>
    </form>

    <div class="lg:col-span-2 space-y-6 min-w-0">
        <form method="POST" action="{{ route('admin.gallery.items.store', $album) }}" enctype="multipart/form-data" class="admin-card p-6 min-w-0">
            @csrf
            <h2 class="font-display text-xl font-bold mb-4">Add new item</h2>
            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label for="gallery_item_type" class="form-label">Type</label>
                    <select id="gallery_item_type" name="type" class="form-select">
                        <option value="image">Image upload</option>
                        <option value="youtube">YouTube embed URL</option>
                    </select>
                </div>
                <div><label for="gallery_item_caption" class="form-label">Caption</label><input id="gallery_item_caption" name="caption" class="form-input"></div>
                <div><label for="gallery_item_file" class="form-label">Image (if type = image)</label><input id="gallery_item_file" type="file" name="file" accept="image/jpeg,image/png,image/webp" class="form-input"><p class="text-xs text-gray-500 mt-1">Max 8 MB; stored as optimized WebP.</p></div>
                <div><label for="gallery_item_external_url" class="form-label">YouTube URL</label><input id="gallery_item_external_url" name="external_url" class="form-input" placeholder="Watch, Short, Live, youtu.be or embed URL"><p class="text-xs text-gray-500 mt-1">Videos stay on YouTube for minimum server storage and adaptive quality.</p></div>
            </div>
            <button class="btn btn-primary text-sm mt-4">Add to album</button>
        </form>

        <div class="grid sm:grid-cols-3 gap-3">
            @forelse ($album->items as $item)
                <div class="admin-card overflow-hidden">
                    @if ($item->type === 'image' && $item->file_path)
                        <img src="{{ asset('storage/' . $item->file_path) }}" class="w-full h-32 object-cover" alt="">
                    @else
                        <div class="h-32 bg-gray-100 flex items-center justify-center text-3xl">🎬</div>
                    @endif
                    <div class="p-3">
                        <div class="text-xs text-gray-500 truncate">{{ $item->caption ?: $item->external_url }}</div>
                        <form method="POST" action="{{ route('admin.gallery.items.destroy', $item) }}" onsubmit="return confirm('Delete?')" class="mt-2">
                            @csrf @method('DELETE')<button class="text-red-600 text-xs">Remove</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-gray-500 text-center py-8 text-sm">This album is empty. Add items above.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
