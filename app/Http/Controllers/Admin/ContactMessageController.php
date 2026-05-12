<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = ContactMessage::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $message): View
    {
        if ($message->status === ContactMessage::STATUS_NEW) {
            $message->status = ContactMessage::STATUS_READ;
            $message->save();
        }

        return view('admin.messages.show', compact('message'));
    }

    public function update(ContactMessage $message, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,read,replied,spam,closed'],
            'admin_reply' => ['nullable', 'string'],
        ]);

        $message->status = $data['status'];
        $message->admin_reply = $data['admin_reply'] ?? $message->admin_reply;
        if ($data['status'] === ContactMessage::STATUS_REPLIED && ! $message->replied_at) {
            $message->replied_at = now();
        }
        $message->save();

        return back()->with('success', 'Message updated.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message removed.');
    }
}
