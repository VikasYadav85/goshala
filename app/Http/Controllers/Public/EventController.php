<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $upcoming = Event::upcoming()->orderBy('starts_at')->get();
        $past = Event::past()->orderByDesc('starts_at')->take(8)->get();
        return view('public.events.index', compact('upcoming', 'past'));
    }

    public function show(string $slug): View
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('public.events.show', compact('event'));
    }
}
