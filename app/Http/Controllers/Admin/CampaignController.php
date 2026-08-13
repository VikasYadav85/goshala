<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Services\OptimizedImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function __construct(private readonly OptimizedImageStorage $images) {}

    public function index(): View
    {
        $campaigns = Campaign::latest()->paginate(20);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        return view('admin.campaigns.form', ['campaign' => new Campaign]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(5));
        if ($request->hasFile('image')) {
            $data['image'] = $this->images->store($request->file('image'), 'campaigns');
        }
        Campaign::create($data);

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign created.');
    }

    public function edit(Campaign $campaign): View
    {
        return view('admin.campaigns.form', compact('campaign'));
    }

    public function update(Campaign $campaign, Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('image')) {
            $data['image'] = $this->images->replace($request->file('image'), 'campaigns', $campaign->image);
        }
        $campaign->update($data);

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign updated.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $image = $campaign->image;
        $campaign->delete();
        $this->images->delete($image);

        return back()->with('success', 'Campaign removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
            'goal_amount' => ['required', 'integer', 'min:1'],
            'raised_amount' => ['nullable', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:upcoming,active,completed,emergency'],
            'is_emergency' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
