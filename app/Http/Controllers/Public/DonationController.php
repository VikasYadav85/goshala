<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Cow;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\Faq;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function __construct(private readonly RazorpayService $razorpay) {}

    public function index(): View
    {
        $categories = DonationCategory::active()->orderBy('sort_order')->get();
        $faqs = Faq::published()->where('group', 'donation')->orderBy('sort_order')->get();
        return view('public.donation.index', compact('categories', 'faqs'));
    }

    public function create(Request $request): View
    {
        $categoryId = $request->integer('category');
        $campaignId = $request->integer('campaign');
        $cowId      = $request->integer('cow');
        $amount     = $request->integer('amount') ?: 1100;

        $category = $categoryId ? DonationCategory::find($categoryId) : null;
        $campaign = $campaignId ? Campaign::find($campaignId) : null;
        $cow      = $cowId      ? Cow::find($cowId)          : null;

        if (! $category && ! $campaign && ! $cow) {
            $category = DonationCategory::active()->orderBy('sort_order')->first();
        }

        return view('public.donation.checkout', compact('category', 'campaign', 'cow', 'amount'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'donation_category_id' => ['nullable', 'exists:donation_categories,id'],
            'campaign_id' => ['nullable', 'exists:campaigns,id'],
            'cow_id' => ['nullable', 'exists:cows,id'],

            'donor_name' => ['required', 'string', 'max:120'],
            'donor_email' => ['required', 'email', 'max:160'],
            'donor_phone' => ['required', 'string', 'max:30'],
            'donor_pan' => ['nullable', 'string', 'max:20'],
            'donor_address' => ['nullable', 'string'],
            'donor_city' => ['nullable', 'string', 'max:100'],
            'donor_state' => ['nullable', 'string', 'max:100'],
            'donor_pincode' => ['nullable', 'string', 'max:12'],
            'donor_country' => ['nullable', 'string', 'max:100'],

            'amount' => ['required', 'integer', 'min:100'],
            'frequency' => ['required', 'in:one_time,monthly,yearly'],
            'is_anonymous' => ['nullable', 'boolean'],
            'wants_80g_receipt' => ['nullable', 'boolean'],
            'dedication' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $donation = DB::transaction(function () use ($data) {
            $donation = Donation::create(array_merge($data, [
                'currency' => 'INR',
                'payment_method' => 'razorpay',
                'payment_status' => Donation::STATUS_PENDING,
                'donor_country' => $data['donor_country'] ?? 'India',
            ]));

            $order = $this->razorpay->createOrder($donation);
            $donation->razorpay_order_id = $order['id'];
            $donation->save();

            return $donation;
        });

        return redirect()->route('donations.pay', $donation);
    }

    public function pay(Donation $donation): View
    {
        abort_if($donation->payment_status === Donation::STATUS_SUCCESS, 404);

        $rzpKey = config('services.razorpay.key');

        $upiVpa = config('services.upi.vpa');
        $upiPayee = config('services.upi.payee');
        $upiUri = $upiVpa
            ? 'upi://pay?' . http_build_query([
                'pa' => $upiVpa,
                'pn' => $upiPayee,
                'am' => $donation->amount,
                'cu' => 'INR',
                'tn' => 'Donation ' . $donation->reference_no,
                'tr' => $donation->reference_no,
            ])
            : null;

        return view('public.donation.pay', compact('donation', 'rzpKey', 'upiVpa', 'upiPayee', 'upiUri'));
    }

    public function upiConfirm(Donation $donation, Request $request): RedirectResponse
    {
        abort_if($donation->payment_status === Donation::STATUS_SUCCESS, 404);

        $data = $request->validate([
            'upi_reference' => ['required', 'string', 'min:6', 'max:60'],
            'upi_app' => ['nullable', 'string', 'max:40'],
        ]);

        // Donor self-reports the UPI transaction/UTR. We do NOT mark this
        // successful — it stays "processing" until an admin verifies the
        // amount against the bank/UPI statement and marks it success.
        $donation->fill([
            'payment_method' => 'upi',
            'payment_status' => Donation::STATUS_PROCESSING,
            'payment_meta' => array_merge((array) $donation->payment_meta, [
                'upi_reference' => $data['upi_reference'],
                'upi_app' => $data['upi_app'] ?? null,
                'upi_vpa' => config('services.upi.vpa'),
                'reported_at' => now()->toDateTimeString(),
            ]),
        ])->save();

        return redirect()->route('donations.thanks', $donation);
    }

    public function callback(Donation $donation, Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $verified = $this->razorpay->verifySignature(
            $payload['razorpay_order_id'],
            $payload['razorpay_payment_id'],
            $payload['razorpay_signature'],
        );

        $donation->fill([
            'razorpay_payment_id' => $payload['razorpay_payment_id'],
            'razorpay_signature' => $payload['razorpay_signature'],
            'payment_status' => $verified ? Donation::STATUS_SUCCESS : Donation::STATUS_FAILED,
            'paid_at' => $verified ? now() : null,
        ])->save();

        if ($verified && $donation->campaign_id) {
            $donation->campaign->increment('raised_amount', $donation->amount);
        }

        return redirect()->route('donations.thanks', $donation);
    }

    public function simulate(Donation $donation): RedirectResponse
    {
        // Local-only: simulate a successful payment without hitting Razorpay
        abort_unless(app()->environment(['local', 'testing']), 403);

        $donation->update([
            'razorpay_payment_id' => 'pay_'.strtoupper(\Illuminate\Support\Str::random(14)),
            'razorpay_signature' => 'sig_'.strtoupper(\Illuminate\Support\Str::random(14)),
            'payment_status' => Donation::STATUS_SUCCESS,
            'paid_at' => now(),
        ]);

        if ($donation->campaign_id) {
            $donation->campaign->increment('raised_amount', $donation->amount);
        }

        return redirect()->route('donations.thanks', $donation);
    }

    public function thanks(Donation $donation): View
    {
        return view('public.donation.thanks', compact('donation'));
    }
}
