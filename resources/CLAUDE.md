# resources/ — Frontend (Blade, CSS, JS)

## Purpose
All server-rendered frontend: Blade templates for the public site + admin panel, the Tailwind v4 CSS entry, and bundled JavaScript. No SPA — Alpine.js 3, its collapse plugin, and UPI QR rendering are compiled locally by Vite.

## Key files
- `views/public/layout.blade.php`, `views/admin/layout.blade.php` — the two layouts; all CSS/JS loads through `@vite([...])`.
- `views/public/donation/{index,checkout,pay,thanks}.blade.php` — donation flow screens.
- `views/public/partials/*` — shared `header`, `footer`, `page-hero`, `campaign-card`, `donate-cta`.
- `views/admin/donations/{index,show}.blade.php` + per-model CRUD views.
- `views/emails/donations/receipt.blade.php` — receipt email body.
- `views/pdf/donation-invoice.blade.php` — 80G PDF invoice (rendered by DomPDF).
- `css/app.css` — Tailwind v4 in-CSS config (`@import 'tailwindcss'`, `@source`, `@theme` tokens). No `tailwind.config.js`.
- `js/app.js` — starts Alpine + collapse and renders UPI QR on `[data-upi-qr]`. `js/bootstrap.js` — axios setup.
- `views/components/welcome.blade.php` — default Laravel page, **unreferenced** (dead).

## Data flow
Controllers return `view('public.donation.index', compact(...))`. Blade reads passed vars + shared site settings (from `AppServiceProvider`). Vite compiles `css/app.css` + `js/app.js` → `public/build/`.

## Dependencies
- Depends on: controller-passed data, `vite.config.js`, `AppServiceProvider` (shared settings).
- Depended on by: every controller that returns a view; `DonationReceiptMail` (email + pdf views).

## Conventions
- View paths mirror surface: `admin.donations.index`, `public.donation.checkout`. Tailwind utility classes inline. Every visible form control needs an `id` and matching `label[for]`; wrapped checkboxes are allowed. Alpine uses `x-data` and is bundled locally. See `docs/PATTERNS.md` §9.

## Common commands
- `npm run dev` (HMR) / `npm run build` (production). After editing Blade on the server: `php artisan view:clear`.
