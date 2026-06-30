# app/ — Application code

## Purpose
All server-side PHP for the GSSTG platform: HTTP controllers, Eloquent models, the Razorpay service, the donation-receipt action, mailables, middleware, and providers. Server-rendered Laravel 12 — no API layer.

## Key files
- `Http/Controllers/Public/` — 10 controllers serving the public site (Donation, Campaign, Event, Gallery, Blog, Volunteer, Contact, Home, Page, Subscriber).
- `Http/Controllers/Admin/` — 15 controllers for the `/admin` panel (Auth, Dashboard + CRUD).
- `Http/Middleware/EnsureUserIsAdmin.php` — the `admin` route-middleware (role + active check).
- `Models/` — 22 Eloquent models; `Donation.php` is the relational hub.
- `Services/RazorpayService.php` — payment gateway client (dual-mode: real vs placeholder).
- `Actions/SendDonationReceipt.php` — invokable, idempotent 80G receipt issuer.
- `Mail/DonationReceiptMail.php` — receipt mailable, attaches a DomPDF invoice.
- `Providers/AppServiceProvider.php` — binds `RazorpayService` singleton, shares site settings/Trust config to views.

## Data flow
Request → `routes/web.php` → controller → model(s) → `RazorpayService` / `SendDonationReceipt` as needed → `view()` or `redirect()`. See `docs/ARCHITECTURE.md` §2.

## Dependencies
- Depends on: `config/` (services, mail), `resources/views/` (Blade), `database/` (schema via Eloquent).
- Depended on by: `routes/web.php` (controllers), `tests/` (future).

## Conventions
- Controllers are thin, always declare return types (`View`/`RedirectResponse`), validate inline via `$request->validate()`.
- External APIs → `Services/` (singleton); idempotent side-effects → invokable `Actions/`. No repository layer. See `docs/PATTERNS.md`.

## Common commands
- `php artisan make:controller`, `make:model`, `make:middleware` — scaffold.
- `composer dev` — run the app (serve + queue + vite + logs).
