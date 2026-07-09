<?php

namespace Tests\Feature;

use App\Actions\SendDonationReceipt;
use App\Mail\DonationAdminNotification;
use App\Mail\DonationReceiptMail;
use App\Models\Donation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DonationAdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function successfulDonation(): Donation
    {
        return Donation::create([
            'reference_no' => 'GSST-TEST-000001',
            'donor_name' => 'Ram Kumar',
            'donor_email' => 'ram@example.com',
            'amount' => 2100,
            'currency' => 'INR',
            'payment_method' => 'upi',
            'payment_status' => Donation::STATUS_SUCCESS,
            'paid_at' => now(),
        ]);
    }

    public function test_successful_donation_emails_donor_and_admin(): void
    {
        Mail::fake();
        config(['services.admin.email' => 'admin@example.com']);

        app(SendDonationReceipt::class)($this->successfulDonation());

        Mail::assertSent(DonationReceiptMail::class, fn ($m) => $m->hasTo('ram@example.com'));
        Mail::assertSent(DonationAdminNotification::class, fn ($m) => $m->hasTo('admin@example.com'));
    }

    public function test_admin_notification_is_sent_only_once(): void
    {
        Mail::fake();
        config(['services.admin.email' => 'admin@example.com']);

        $donation = $this->successfulDonation();
        app(SendDonationReceipt::class)($donation);
        app(SendDonationReceipt::class)($donation->fresh()); // re-invoke: should early-return

        Mail::assertSent(DonationAdminNotification::class, 1);
    }
}
