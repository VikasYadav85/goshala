<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\OptimizedImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __construct(private readonly OptimizedImageStorage $images) {}

    public function index(): View
    {
        $testimonials = Testimonial::orderBy('sort_order')->latest()->paginate(20);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('admin.testimonials.form', ['testimonial' => new Testimonial]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->images->store($request->file('avatar'), 'testimonials');
        }
        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial added.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', compact('testimonial'));
    }

    public function update(Testimonial $testimonial, Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->images->replace($request->file('avatar'), 'testimonials', $testimonial->avatar);
        }
        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $avatar = $testimonial->avatar;
        $testimonial->delete();
        $this->images->delete($avatar);

        return back()->with('success', 'Removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
            'quote' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
