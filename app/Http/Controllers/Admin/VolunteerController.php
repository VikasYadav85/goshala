<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VolunteerController extends Controller
{
    public function index(Request $request): View
    {
        $volunteers = Volunteer::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(function ($q) use ($s) {
                    $q->where('full_name', 'like', "%{$s}%")
                        ->orWhere('email', 'like', "%{$s}%")
                        ->orWhere('city', 'like', "%{$s}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.volunteers.index', compact('volunteers'));
    }

    public function show(Volunteer $volunteer): View
    {
        return view('admin.volunteers.show', compact('volunteer'));
    }

    public function update(Volunteer $volunteer, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,active,inactive,rejected'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $volunteer->status = $data['status'];
        $volunteer->admin_notes = $data['admin_notes'] ?? null;

        if (in_array($data['status'], [Volunteer::STATUS_APPROVED, Volunteer::STATUS_ACTIVE], true) && ! $volunteer->approved_at) {
            $volunteer->approved_at = now();
            $volunteer->approved_by = $request->user()->id;
        }

        $volunteer->save();

        return back()->with('success', 'Volunteer updated.');
    }
}
