<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(): View
    {
        $active = Campaign::active()->latest()->get();
        $emergency = Campaign::where('is_emergency', true)->where('status', '!=', 'completed')->get();
        $upcoming = Campaign::where('status', 'upcoming')->latest()->get();
        $completed = Campaign::where('status', 'completed')->latest()->take(6)->get();

        return view('public.campaigns.index', compact('active', 'emergency', 'upcoming', 'completed'));
    }

    public function show(string $slug): View
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();
        $campaign->load(['updates' => fn ($q) => $q->orderByDesc('published_at')]);

        return view('public.campaigns.show', compact('campaign'));
    }
}
