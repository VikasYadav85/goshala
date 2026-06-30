<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            $this->fitCover($data['cover_image']);
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
            $this->fitCover($data['cover_image']);
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
            'file' => ['nullable', 'required_without:external_url', 'image', 'max:5120'],
            'external_url' => ['nullable', 'required_without:file', 'url'],
            'caption' => ['nullable', 'string', 'max:200'],
            'alt_text' => ['nullable', 'string', 'max:200'],
        ], [
            'file.required_without' => 'Add an image file or a video URL.',
            'external_url.required_without' => 'Add a video URL or upload an image.',
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
        } elseif (! empty($payload['external_url'])) {
            // A link without an uploaded file is always an embedded video.
            $payload['type'] = 'youtube';
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
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096', 'dimensions:min_width=640,min_height=360'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }

    /**
     * Center-crop the uploaded cover to 16:9 and resize to 1280x720 so it
     * always fits the gallery card (which renders covers as aspect-video).
     */
    private function fitCover(?string $path): void
    {
        if (! $path) {
            return;
        }

        $file = Storage::disk('public')->path($path);
        $info = @getimagesize($file);
        if (! $info) {
            return;
        }

        [$width, $height] = $info;
        $type = $info[2];

        $targetW = 1280;
        $targetH = 720;
        $targetRatio = $targetW / $targetH;
        $srcRatio = $width / $height;

        if ($srcRatio > $targetRatio) {
            // Too wide: crop the sides.
            $cropH = $height;
            $cropW = (int) round($height * $targetRatio);
            $srcX = (int) round(($width - $cropW) / 2);
            $srcY = 0;
        } else {
            // Too tall: crop the top/bottom.
            $cropW = $width;
            $cropH = (int) round($width / $targetRatio);
            $srcX = 0;
            $srcY = (int) round(($height - $cropH) / 2);
        }

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($file),
            IMAGETYPE_PNG => @imagecreatefrompng($file),
            IMAGETYPE_WEBP => @imagecreatefromwebp($file),
            default => null,
        };
        if (! $src) {
            return;
        }

        $dst = imagecreatetruecolor($targetW, $targetH);
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }
        imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $targetW, $targetH, $cropW, $cropH);

        match ($type) {
            IMAGETYPE_PNG => imagepng($dst, $file),
            IMAGETYPE_WEBP => imagewebp($dst, $file),
            default => imagejpeg($dst, $file, 85),
        };

        imagedestroy($src);
        imagedestroy($dst);
    }
}
