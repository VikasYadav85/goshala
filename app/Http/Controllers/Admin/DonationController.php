<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function index(Request $request): View
    {
        $donations = Donation::query()
            ->with(['category', 'campaign', 'cow'])
            ->when($request->filled('status'), fn ($q) => $q->where('payment_status', $request->status))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(function ($q) use ($s) {
                    $q->where('donor_name', 'like', "%{$s}%")
                        ->orWhere('donor_email', 'like', "%{$s}%")
                        ->orWhere('reference_no', 'like', "%{$s}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.donations.index', compact('donations'));
    }

    public function show(Donation $donation): View
    {
        $donation->load(['category', 'campaign', 'cow']);
        return view('admin.donations.show', compact('donation'));
    }

    public function updateStatus(Donation $donation, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'payment_status' => ['required', 'in:pending,processing,success,failed,refunded'],
        ]);

        $donation->payment_status = $data['payment_status'];
        if ($data['payment_status'] === Donation::STATUS_SUCCESS && ! $donation->paid_at) {
            $donation->paid_at = now();
        }
        $donation->save();

        if ($donation->payment_status === Donation::STATUS_SUCCESS && $donation->campaign_id) {
            $donation->campaign->increment('raised_amount', $donation->amount);
        }

        return back()->with('success', 'Donation status updated.');
    }
}
