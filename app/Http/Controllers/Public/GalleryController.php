<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $albums = GalleryAlbum::published()
            ->withCount('items')
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12);

        $categories = ['cows', 'events', 'feeding', 'rescue', 'volunteers', 'media'];

        return view('public.gallery.index', compact('albums', 'categories'));
    }

    public function show(string $slug): View
    {
        $album = GalleryAlbum::where('slug', $slug)->firstOrFail();
        $album->load('items');

        return view('public.gallery.show', compact('album'));
    }
}
