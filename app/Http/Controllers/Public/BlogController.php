<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $posts = BlogPost::published()
            ->with(['category', 'author'])
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('category', fn ($q) => $q->where('slug', $request->category));
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $s = $request->q;
                $q->where(function ($q) use ($s) {
                    $q->where('title', 'like', "%{$s}%")->orWhere('excerpt', 'like', "%{$s}%");
                });
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = BlogCategory::orderBy('sort_order')->get();
        $featured = BlogPost::published()->featured()->latest('published_at')->take(3)->get();

        return view('public.blog.index', compact('posts', 'categories', 'featured'));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::where('slug', $slug)->published()->firstOrFail();
        $post->increment('view_count');

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.blog.show', compact('post', 'related'));
    }
}
