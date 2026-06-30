# app/Services/ — External integration services

## Purpose
Houses classes that talk to external systems, kept out of controllers. Currently one service: `RazorpayService`, the payment gateway client. Bound as a singleton in `AppServiceProvider` and constructor-injected.

## Key files
- `RazorpayService.php` — creates Razorpay orders and verifies HMAC callback signatures.
  - `isConfigured()` — true only when real (non-placeholder) keys are set.
  - `createOrder(Donation)` — hits `https://api.razorpay.com/v1/orders` when configured; otherwise returns a **fake `order_LOCAL_…` order** so the flow works offline/on placeholder-key environments.
  - `verifySignature(orderId, paymentId, signature)` — `hash_hmac` check; returns `true` for simulated callbacks when unconfigured.

## Data flow
`Public\DonationController::store` → `createOrder()` → stores `razorpay_order_id`. `Public\DonationController::callback` → `verifySignature()` → marks the donation success/failed. Constructed with `config('services.razorpay.*')` values.

## Dependencies
- Depends on: `config/services.php` (`razorpay` keys), `app/Models/Donation.php`, Laravel `Http` client.
- Depended on by: `Public\DonationController`, `AppServiceProvider` (binding).

## Conventions
- One responsibility per service; injected, never `new`-ed. Config read via constructor args (no `env()`/`config()` calls deep inside methods). Dual-mode (real vs simulated) keyed on `isConfigured()` — preserve this so local dev needs no live keys.

## Common commands
- None specific. Test the live path by setting real `RAZORPAY_KEY`/`RAZORPAY_SECRET` in `.env` then `php artisan config:clear`.
