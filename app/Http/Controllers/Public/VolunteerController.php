<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VolunteerController extends Controller
{
    public function create(): View
    {
        return view('public.volunteer.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'occupation' => ['nullable', 'string', 'max:120'],
            'areas_of_interest' => ['nullable', 'array'],
            'areas_of_interest.*' => ['string', 'in:feeding,events,rescue,social_media,fundraising,medical,construction,festivals'],
            'availability' => ['nullable', 'array'],
            'availability.*' => ['string', 'in:weekdays,weekends,evenings,full_time'],
            'previous_experience' => ['nullable', 'string', 'max:2000'],
            'motivation' => ['nullable', 'string', 'max:2000'],
            'referral_source' => ['nullable', 'string', 'max:120'],
        ]);

        Volunteer::create($data + ['status' => Volunteer::STATUS_PENDING, 'country' => 'India']);

        return redirect()->route('volunteer.thanks');
    }

    public function thanks(): View
    {
        return view('public.volunteer.thanks');
    }
}
