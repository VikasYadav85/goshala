# app/Actions/ — Invokable side-effect actions

## Purpose
Single-purpose invokable classes that encapsulate side-effecting business logic kept out of controllers. Currently one: `SendDonationReceipt`, which issues and emails the 80G donation receipt.

## Key files
- `SendDonationReceipt.php` — `__invoke(Donation $donation): void`.
  - **Idempotent:** returns early unless `payment_status === success`, and skips if `receipt_issued_at` is already set.
  - Assigns `receipt_no` up front but only sets `receipt_issued_at` **after** the mail actually sends — so a failed send (SMTP/GD error) is retryable by re-invoking.
  - Wraps the send in try/catch + `Log::error` so a mail failure never breaks the payment flow.

## Data flow
Called from `Public\DonationController::callback` / `simulate` (after a verified payment) and `Admin\DonationController::updateStatus` (admin-verified UPI/bank/cash). Builds + sends `DonationReceiptMail`, which renders the DomPDF invoice.

## Dependencies
- Depends on: `app/Mail/DonationReceiptMail.php`, `app/Models/Donation.php`, Laravel `Mail` + `Log`.
- Depended on by: `Public\DonationController`, `Admin\DonationController`.

## Conventions
- Invokable (`__invoke`) single-method action. Must stay **idempotent** and **never throw** to the caller — log and swallow. New cross-cutting side-effects (not external APIs) belong here as new action classes.

## Common commands
- `php artisan make:class Actions/DoSomething` (or create manually) for a new action.
