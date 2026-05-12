<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class SubscriberController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:160'],
            'name' => ['nullable', 'string', 'max:120'],
        ]);

        Subscriber::updateOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name'] ?? null, 'is_subscribed' => true, 'unsubscribed_at' => null, 'source' => 'footer'],
        );

        return back()->with('success', 'You\'re subscribed. Thank you for joining our seva community.');
    }
}
