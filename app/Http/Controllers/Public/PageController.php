<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Cow;
use App\Models\Faq;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $trustees = TeamMember::published()->where('group', 'trustee')->orderBy('sort_order')->get();
        $team     = TeamMember::published()->whereIn('group', ['team', 'veterinarian'])->orderBy('sort_order')->get();

        return view('public.about', compact('trustees', 'team'));
    }

    public function goshala(): View
    {
        $cows = Cow::availableForSponsorship()->orderByDesc('is_featured')->orderBy('sort_order')->paginate(12);
        return view('public.goshala', compact('cows'));
    }

    public function faqs(): View
    {
        $faqs = Faq::published()->orderBy('group')->orderBy('sort_order')->get()->groupBy('group');
        return view('public.faqs', compact('faqs'));
    }

    public function testimonials(): View
    {
        $testimonials = Testimonial::published()->orderByDesc('is_featured')->paginate(20);
        return view('public.testimonials', compact('testimonials'));
    }

    public function transparency(): View
    {
        return view('public.transparency');
    }
}
