<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\ContactMessage;
use App\Models\Cow;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Volunteer;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalRaised = Donation::successful()->sum('amount');
        $monthRaised = Donation::successful()
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $stats = [
            'total_donations'  => Donation::successful()->count(),
            'total_raised'     => $totalRaised,
            'month_raised'     => $monthRaised,
            'pending_payments' => Donation::where('payment_status', Donation::STATUS_PENDING)->count(),
            'cows_total'       => Cow::active()->count(),
            'volunteers_total' => Volunteer::count(),
            'volunteers_pending' => Volunteer::where('status', Volunteer::STATUS_PENDING)->count(),
            'campaigns_active' => Campaign::active()->count(),
            'events_upcoming'  => Event::upcoming()->count(),
            'messages_new'     => ContactMessage::where('status', ContactMessage::STATUS_NEW)->count(),
        ];

        $recentDonations = Donation::with(['category', 'campaign', 'cow'])
            ->successful()
            ->latest('paid_at')
            ->take(8)
            ->get();

        $recentVolunteers = Volunteer::latest()->take(5)->get();
        $recentMessages = ContactMessage::latest()->take(5)->get();

        // 30-day donations trend
        $trend = Donation::successful()
            ->selectRaw('DATE(paid_at) as day, SUM(amount) as total')
            ->where('paid_at', '>=', now()->subDays(30))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentDonations',
            'recentVolunteers',
            'recentMessages',
            'trend',
        ));
    }
}
