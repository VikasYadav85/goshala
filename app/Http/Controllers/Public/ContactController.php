<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactAcknowledgementMail;
use App\Mail\ContactAdminNotification;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('public.contact.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message_type' => ['required', 'in:general,donation,volunteer,visit,partnership'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $contact = ContactMessage::create($data + [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => ContactMessage::STATUS_NEW,
        ]);

        // Notify the trust and acknowledge the sender. A mail failure must never
        // break the form submission, so it is caught and logged.
        try {
            Mail::to(config('services.admin.email'))->send(new ContactAdminNotification($contact));
            Mail::to($contact->email)->send(new ContactAcknowledgementMail($contact));
        } catch (\Throwable $e) {
            Log::error('Contact email failed', ['contact_id' => $contact->id, 'error' => $e->getMessage()]);
        }

        return back()->with('success', 'Thank you! We have received your message and will respond shortly.');
    }
}
