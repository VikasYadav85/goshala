<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $albums = GalleryAlbum::withCount('items')->latest()->paginate(20);


        return view('admin.gallery.index', compact('albums'));
    }

    public function create(): View
    {
        return view('admin.gallery.form', ['album' => new GalleryAlbum()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(5));
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('gallery/covers', 'public');
        }
        GalleryAlbum::create($data);
        return redirect()->route('admin.gallery.index')->with('success', 'Album added.');
    }

    public function edit(GalleryAlbum $album): View
    {
        $album->load('items');
        return view('admin.gallery.edit', compact('album'));
    }

    public function update(GalleryAlbum $album, Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('gallery/covers', 'public');
        }
        $album->update($data);
        return back()->with('success', 'Album updated.');
    }

    public function destroy(GalleryAlbum $album): RedirectResponse
    {
        $album->delete();
        return back()->with('success', 'Album removed.');
    }

    public function addItem(GalleryAlbum $album, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:image,video,youtube'],
            'file' => ['nullable', 'image', 'max:5120'],
            'external_url' => ['nullable', 'url'],
            'caption' => ['nullable', 'string', 'max:200'],
            'alt_text' => ['nullable', 'string', 'max:200'],
        ]);

        $payload = [
            'album_id' => $album->id,
            'type' => $data['type'],
            'caption' => $data['caption'] ?? null,
            'alt_text' => $data['alt_text'] ?? null,
            'external_url' => $data['external_url'] ?? null,
        ];

        if ($request->hasFile('file')) {
            $payload['file_path'] = $request->file('file')->store('gallery/items', 'public');
        }

        GalleryItem::create($payload);

        return back()->with('success', 'Item added.');
    }

    public function destroyItem(GalleryItem $item): RedirectResponse
    {
        $item->delete();
        return back()->with('success', 'Item removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'in:cows,events,feeding,rescue,volunteers,media,general'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
