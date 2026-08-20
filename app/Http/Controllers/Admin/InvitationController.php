<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvitationMail;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function index(): View
    {
        $invitations = Invitation::with('creator')->latest()->paginate(30);
        return view('admin.invitations.index', compact('invitations'));
    }

    public function create(): View
    {
        return view('admin.invitations.form', ['invitation' => new Invitation()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $invitation = Invitation::create($this->validated($request) + [
            'status' => Invitation::STATUS_PENDING,
            'created_by' => $request->user()?->id,
        ]);

        $this->dispatchInvite($invitation);

        return redirect()->route('admin.invitations.index')->with(
            $invitation->status === Invitation::STATUS_SENT ? 'success' : 'error',
            $invitation->status === Invitation::STATUS_SENT
                ? 'Invitation sent to '.$invitation->invitee_email.'.'
                : 'Invitation saved, but the email could not be sent. You can resend it.'
        );
    }

    public function resend(Invitation $invitation): RedirectResponse
    {
        $this->dispatchInvite($invitation);

        return back()->with(
            $invitation->status === Invitation::STATUS_SENT ? 'success' : 'error',
            $invitation->status === Invitation::STATUS_SENT
                ? 'Invitation resent to '.$invitation->invitee_email.'.'
                : 'Could not send the invitation email. Please try again later.'
        );
    }

    public function destroy(Invitation $invitation): RedirectResponse
    {
        $invitation->delete();
        return back()->with('success', 'Invitation removed.');
    }

    /** Send the invitation email and record the outcome. Never throws. */
    private function dispatchInvite(Invitation $invitation): void
    {
        try {
            Mail::to($invitation->invitee_email)->send(new InvitationMail($invitation));
            $invitation->forceFill(['status' => Invitation::STATUS_SENT, 'sent_at' => now()])->save();
        } catch (\Throwable $e) {
            Log::error('Invitation email failed', ['invitation_id' => $invitation->id, 'error' => $e->getMessage()]);
            $invitation->forceFill(['status' => Invitation::STATUS_FAILED])->save();
        }
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        return $request->validate([
            'invitee_name' => ['required', 'string', 'max:120'],
            'invitee_email' => ['required', 'email', 'max:160'],
            'invitee_phone' => ['nullable', 'string', 'max:30'],
            'occasion' => ['required', 'string', 'max:180'],
            'event_date' => ['nullable', 'date'],
            'event_time' => ['nullable', 'string', 'max:60'],
            'venue' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
