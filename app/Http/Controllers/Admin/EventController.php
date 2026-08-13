<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\OptimizedImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct(private readonly OptimizedImageStorage $images) {}

    public function index(): View
    {
        $events = Event::orderByDesc('starts_at')->paginate(20);

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.form', ['event' => new Event]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(5));
        if ($request->hasFile('image')) {
            $data['image'] = $this->images->store($request->file('image'), 'events');
        }
        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event created.');
    }

    public function edit(Event $event): View
    {
        return view('admin.events.form', compact('event'));
    }

    public function update(Event $event, Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('image')) {
            $data['image'] = $this->images->replace($request->file('image'), 'events', $event->image);
        }
        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $image = $event->image;
        $event->delete();
        $this->images->delete($image);

        return back()->with('success', 'Event removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'type' => ['required', 'in:event,festival,seva,annadan,pujan'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'venue' => ['nullable', 'string', 'max:200'],
            'address' => ['nullable', 'string'],
            'location_url' => ['nullable', 'url'],
            'rsvp_enabled' => ['nullable', 'boolean'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:upcoming,ongoing,completed,cancelled'],
            'is_featured' => ['nullable', 'boolean'],
        ]);
    }
}
