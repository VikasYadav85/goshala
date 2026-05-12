<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Campaign;
use App\Models\Cow;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\Event;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredCampaign = Campaign::active()->where('is_emergency', true)
            ->orderByDesc('updated_at')->first()
            ?? Campaign::active()->featured()->first()
            ?? Campaign::active()->latest()->first();

        $donationPrograms = DonationCategory::active()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $featuredCows = Cow::availableForSponsorship()->featured()
            ->orderBy('sort_order')->take(4)->get();

        $upcomingEvents = Event::upcoming()->orderBy('starts_at')->take(3)->get();
        $latestPosts    = BlogPost::published()->latest('published_at')->take(3)->get();
        $testimonials   = Testimonial::published()->orderByDesc('is_featured')->orderBy('sort_order')->take(6)->get();

        $impact = [
            'cows_sheltered' => Cow::active()->count() ?: 500,
            'rescued'        => 1200,
            'fodder_kg'      => 2000,
            'trees_planted'  => 10000,
            'villages'       => 50,
            'total_raised'   => Donation::successful()->sum('amount'),
        ];

        return view('public.home', compact(
            'featuredCampaign',
            'donationPrograms',
            'featuredCows',
            'upcomingEvents',
            'latestPosts',
            'testimonials',
            'impact',
        ));
    }
}
