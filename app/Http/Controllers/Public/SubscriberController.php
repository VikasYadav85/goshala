<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\SubscriberWelcomeMail;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriberController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:160'],
            'name' => ['nullable', 'string', 'max:120'],
        ]);

        $subscriber = Subscriber::updateOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name'] ?? null, 'is_subscribed' => true, 'unsubscribed_at' => null, 'source' => 'footer'],
        );

        // Welcome only newly-added subscribers, so re-submits don't re-spam.
        if ($subscriber->wasRecentlyCreated) {
            try {
                Mail::to($subscriber->email)->send(new SubscriberWelcomeMail($subscriber));
            } catch (\Throwable $e) {
                Log::error('Subscriber welcome email failed', ['subscriber_id' => $subscriber->id, 'error' => $e->getMessage()]);
            }
        }

        return back()->with('success', 'You\'re subscribed. Thank you for joining our seva community.');
    }
}
