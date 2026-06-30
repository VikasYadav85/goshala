# app/Http/Controllers/ — HTTP controllers

## Purpose
Handle all incoming web requests for the two surfaces of the app: the public NGO/donation site (`Public/`) and the staff admin panel (`Admin/`). Controllers are thin — validate, query/mutate via Eloquent, return a Blade view or a redirect.

## Key files
- `Public/DonationController.php` — the core flow: `store` (create pending donation + Razorpay order), `pay`, `callback` (verify signature → success), `upiConfirm`, `simulate` (local only), `thanks`.
- `Public/HomeController.php`, `CampaignController.php`, `EventController.php`, `GalleryController.php`, `BlogController.php` — public content pages (index/show by slug).
- `Public/VolunteerController.php`, `ContactController.php`, `SubscriberController.php` — public form intake.
- `Admin/AuthController.php` — custom session login/logout.
- `Admin/DashboardController.php` — invokable KPI dashboard.
- `Admin/DonationController.php` — list/show + `updateStatus` (marks success, increments campaign, re-sends receipt).
- `Admin/{Cow,Campaign,Event,BlogPost,Testimonial,TeamMember,DonationCategory,Gallery,Faq}Controller.php` — resource CRUD (`->except(['show'])`).

## Data flow
Route (`routes/web.php`) → controller method (route-model binding resolves `{donation}`, `{slug}`) → Eloquent + `RazorpayService`/`SendDonationReceipt` → `view()`/`redirect()`. Admin routes pass through `auth` + `admin` middleware first.

## Dependencies
- Depends on: `app/Models/`, `app/Services/RazorpayService.php`, `app/Actions/SendDonationReceipt.php`, `resources/views/`.
- Depended on by: `routes/web.php`.

## Conventions
- Namespaced by surface: `App\Http\Controllers\{Public,Admin}`. Same-named controllers aliased in routes (`AdminDonationController` vs `PublicDonationController`).
- Return types always declared. Validation inline. Responses: `view()`, `redirect()->route()`, `back()->with('success'|'error', ...)`.

## Common commands
- `php artisan make:controller Admin/FooController` — new admin controller.
- `php artisan route:list` — inspect routes.
