<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationCategory;
use App\Services\OptimizedImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DonationCategoryController extends Controller
{
    public function __construct(private readonly OptimizedImageStorage $images) {}

    public function index(): View
    {
        $categories = DonationCategory::orderBy('sort_order')->paginate(20);

        return view('admin.donation_categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.donation_categories.form', ['category' => new DonationCategory]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']);
        $data['suggested_amounts'] = $this->parseAmounts($request->input('suggested_amounts'));
        if ($request->hasFile('image')) {
            $data['image'] = $this->images->store($request->file('image'), 'donation-categories');
        }
        DonationCategory::create($data);

        return redirect()->route('admin.donation-categories.index')->with('success', 'Category added.');
    }

    public function edit(DonationCategory $category): View
    {
        return view('admin.donation_categories.form', compact('category'));
    }

    public function update(DonationCategory $category, Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['suggested_amounts'] = $this->parseAmounts($request->input('suggested_amounts'));
        if ($request->hasFile('image')) {
            $data['image'] = $this->images->replace($request->file('image'), 'donation-categories', $category->image);
        }
        $category->update($data);

        return redirect()->route('admin.donation-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(DonationCategory $category): RedirectResponse
    {
        $image = $category->image;
        $category->delete();
        $this->images->delete($image);

        return back()->with('success', 'Removed.');
    }

    private function parseAmounts(?string $input): array
    {
        return collect(explode(',', (string) $input))
            ->map(fn ($v) => (int) trim($v))
            ->filter()
            ->values()
            ->all();
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'icon' => ['nullable', 'string', 'max:60'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'default_amount' => ['required', 'integer', 'min:100'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
