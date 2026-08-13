<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cow;
use App\Models\CowCategory;
use App\Services\OptimizedImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CowController extends Controller
{
    public function __construct(private readonly OptimizedImageStorage $images) {}

    public function index(Request $request): View
    {
        $cows = Cow::query()
            ->with('category')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.cows.index', compact('cows'));
    }

    public function create(): View
    {
        return view('admin.cows.form', [
            'cow' => new Cow,
            'categories' => CowCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(5));

        if ($request->hasFile('image')) {
            $data['image'] = $this->images->store($request->file('image'), 'cows');
        }

        Cow::create($data);

        return redirect()->route('admin.cows.index')->with('success', 'Cow added.');
    }

    public function edit(Cow $cow): View
    {
        return view('admin.cows.form', [
            'cow' => $cow,
            'categories' => CowCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Cow $cow, Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->images->replace($request->file('image'), 'cows', $cow->image);
        }

        $cow->update($data);

        return redirect()->route('admin.cows.index')->with('success', 'Cow updated.');
    }

    public function destroy(Cow $cow): RedirectResponse
    {
        $image = $cow->image;
        $cow->delete();
        $this->images->delete($image);

        return back()->with('success', 'Cow removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:cow_categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'breed' => ['nullable', 'string', 'max:120'],
            'age' => ['nullable', 'string', 'max:60'],
            'gender' => ['required', 'in:female,male'],
            'color' => ['nullable', 'string', 'max:60'],
            'rescued_at' => ['nullable', 'date'],
            'rescue_story' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
            'monthly_sponsorship_amount' => ['required', 'integer', 'min:100'],
            'is_available_for_sponsorship' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:active,under_treatment,passed_away'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
