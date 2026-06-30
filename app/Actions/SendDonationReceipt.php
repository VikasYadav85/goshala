<?php

namespace App\Actions;

use App\Mail\DonationReceiptMail;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDonationReceipt
{
    /**
     * Issue a receipt number and email the donor their invoice.
     *
     * Idempotent: only fires once per donation (guarded by receipt_issued_at)
     * and only for successful payments. A mail failure is logged but never
     * bubbles up, so it can't break the payment / callback flow.
     */
    public function __invoke(Donation $donation): void
    {
        if ($donation->payment_status !== Donation::STATUS_SUCCESS) {
            return;
        }

        if ($donation->receipt_issued_at) {
            return; // already sent successfully
        }

        // Assign a receipt number up front (stable across retries) but DON'T
        // mark it issued until the mail actually goes out — so a failed send
        // (e.g. SMTP/GD error) can be retried by re-saving the donation.
        if (empty($donation->receipt_no)) {
            $donation->forceFill(['receipt_no' => $this->generateReceiptNo($donation)])->save();
        }

        try {
            Mail::to($donation->donor_email)->send(new DonationReceiptMail($donation));

            $donation->forceFill(['receipt_issued_at' => now()])->save();
        } catch (\Throwable $e) {
            Log::error('Donation receipt email failed', [
                'donation_id' => $donation->id,
                'reference_no' => $donation->reference_no,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function generateReceiptNo(Donation $donation): string
    {
        $year = ($donation->paid_at ?? now())->format('Y');

        return 'RCPT-'.$year.'-'.str_pad((string) $donation->id, 5, '0', STR_PAD_LEFT);
    }
}
