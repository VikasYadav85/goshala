# app/Services/ — External integration services

## Purpose
Houses reusable application services kept out of controllers. Services are constructor-injected through Laravel's container.

## Key files
- `RazorpayService.php` — creates Razorpay orders and verifies HMAC callback signatures.
  - `isConfigured()` — true only when real (non-placeholder) keys are set.
  - `createOrder(Donation)` — hits `https://api.razorpay.com/v1/orders` when configured; otherwise returns a **fake `order_LOCAL_…` order** so the flow works offline/on placeholder-key environments.
  - `verifySignature(orderId, paymentId, signature)` — `hash_hmac` check; returns `true` for simulated callbacks when unconfigured.
- `OptimizedImageStorage.php` — validates decoded images, fixes JPEG orientation, resizes the longest edge to 1920px, and stores WebP at quality 82. Gallery covers use its exact 1280×720 center-crop mode. `replace()` stores first, then deletes the previous image.

## Data flow
`Public\DonationController::store` → `createOrder()` → stores `razorpay_order_id`. `Public\DonationController::callback` → `verifySignature()` → marks the donation success/failed. Constructed with `config('services.razorpay.*')` values.

Admin upload controller → validated `UploadedFile` → `OptimizedImageStorage::store()`/`replace()` → `storage/app/public/<module>/<uuid>.webp` → path persisted on the model.

## Dependencies
- Depends on: `config/services.php` (`razorpay` keys), `app/Models/Donation.php`, Laravel `Http` client.
- Depended on by: `Public\DonationController`, `AppServiceProvider` (binding).
- `OptimizedImageStorage` depends on PHP GD and Laravel's `public` filesystem; all admin controllers with image forms depend on it.

## Conventions
- One responsibility per service; injected, never `new`-ed. Config read via constructor args (no `env()`/`config()` calls deep inside methods). Dual-mode (real vs simulated) keyed on `isConfigured()` — preserve this so local dev needs no live keys.

## Common commands
- None specific. Test the live path by setting real `RAZORPAY_KEY`/`RAZORPAY_SECRET` in `.env` then `php artisan config:clear`.
