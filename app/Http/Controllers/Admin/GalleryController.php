<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use App\Rules\YouTubeUrl;
use App\Services\OptimizedImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __construct(private readonly OptimizedImageStorage $images) {}

    public function index(): View
    {
        $albums = GalleryAlbum::withCount('items')->latest()->paginate(20);

        return view('admin.gallery.index', compact('albums'));
    }

    public function create(): View
    {
        return view('admin.gallery.form', ['album' => new GalleryAlbum]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(5));
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->images->store($request->file('cover_image'), 'gallery/covers', 1280, 720);
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
            $data['cover_image'] = $this->images->replace(
                $request->file('cover_image'),
                'gallery/covers',
                $album->cover_image,
                1280,
                720,
            );
        }
        $album->update($data);

        return back()->with('success', 'Album updated.');
    }

    public function destroy(GalleryAlbum $album): RedirectResponse
    {
        $album->load('items');
        $coverImage = $album->cover_image;
        $itemImages = $album->items->pluck('file_path')->filter()->all();
        $album->delete();
        $this->images->delete($coverImage);
        foreach ($itemImages as $itemImage) {
            $this->images->delete($itemImage);
        }

        return back()->with('success', 'Album removed.');
    }

    public function addItem(GalleryAlbum $album, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:image,youtube'],
            'file' => ['nullable', 'required_if:type,image', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
            'external_url' => ['nullable', 'required_if:type,youtube', 'url', new YouTubeUrl],
            'caption' => ['nullable', 'string', 'max:200'],
            'alt_text' => ['nullable', 'string', 'max:200'],
        ], [
            'file.required_if' => 'Upload an image for an image item.',
            'external_url.required_if' => 'Add a YouTube URL for a video item.',
        ]);

        $payload = [
            'album_id' => $album->id,
            'type' => $data['type'],
            'caption' => $data['caption'] ?? null,
            'alt_text' => $data['alt_text'] ?? null,
            'external_url' => $data['external_url'] ?? null,
        ];

        if ($request->hasFile('file')) {
            $payload['file_path'] = $this->images->store($request->file('file'), 'gallery/items');
        }

        GalleryItem::create($payload);

        return back()->with('success', 'Item added.');
    }

    public function destroyItem(GalleryItem $item): RedirectResponse
    {
        $filePath = $item->file_path;
        $item->delete();
        $this->images->delete($filePath);

        return back()->with('success', 'Item removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'in:cows,events,feeding,rescue,volunteers,media,general'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192', 'dimensions:min_width=640,min_height=360'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
