# Patterns & Style Guide — GSSTG

Laravel 12 / PHP 8.2+. Every example below is **real code** from this repo. Follow these when adding code. See `docs/ARCHITECTURE.md` for the big picture.

---

## 1. Typical route / endpoint structure

Routes are grouped with `prefix()` + `name()` and reference controllers via `[Controller::class, 'method']`. From `routes/web.php`:

```php
Route::prefix('donate')->name('donations.')->group(function () {
    Route::get('/', [PublicDonationController::class, 'index'])->name('index');
    Route::post('/checkout', [PublicDonationController::class, 'store'])->name('store');
    Route::get('/{donation}/pay', [PublicDonationController::class, 'pay'])->name('pay');
});
```

Controller methods use route-model binding and **always declare a return type**. From `app/Http/Controllers/Public/DonationController.php`:

```php
public function index(): View
{
    $categories = DonationCategory::active()->orderBy('sort_order')->get();
    return view('public.donation.index', compact('categories', 'faqs'));
}
```

Admin CRUD uses `Route::resource(...)->except(['show'])->parameters([...])`.

## 2. Database query pattern

Eloquent with eager loading, conditional `when()` filters, `latest()`, `paginate()->withQueryString()`. From `app/Http/Controllers/Admin/DonationController.php`:

```php
$donations = Donation::query()
    ->with(['category', 'campaign', 'cow'])
    ->when($request->filled('status'), fn ($q) => $q->where('payment_status', $request->status))
    ->latest()
    ->paginate(20)
    ->withQueryString();
```

Reusable filters are **query scopes** on the model. From `app/Models/Campaign.php`:

```php
public function scopeActive(Builder $q): Builder
{
    return $q->where('status', 'active');
}
```

Multi-step writes are wrapped in `DB::transaction(fn () => ...)` (see `Public\DonationController::store`).

## 3. Error handling

Three idioms, each for a distinct purpose:

```php
// (a) input validation — throws + redirects back with errors
$data = $request->validate([
    'payment_status' => ['required', 'in:pending,processing,success,failed,refunded'],
]);

// (b) guard conditions — abort_if / abort_unless
abort_if($donation->payment_status === Donation::STATUS_SUCCESS, 404);

// (c) side-effect that must NOT break the main flow — try/catch + Log, no rethrow
try {
    Mail::to($donation->donor_email)->send(new DonationReceiptMail($donation));
    $donation->forceFill(['receipt_issued_at' => now()])->save();
} catch (\Throwable $e) {
    Log::error('Donation receipt email failed', ['donation_id' => $donation->id, 'error' => $e->getMessage()]);
}
```
(From `Admin\DonationController`, `Public\DonationController`, `app/Actions/SendDonationReceipt.php`.)

## 4. Auth / middleware application

Middleware is applied via `Route::middleware([...])->group(...)`. From `routes/web.php`:

```php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    });
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        // Each section additionally gated by its permission:
        Route::resource('faqs', AdminFaqController::class)->except(['show'])
            ->middleware('permission:manage-faqs');
    });
});
```

The middleware aliases are registered in `bootstrap/app.php` (Laravel 12 — no `Kernel.php`); the `permission`/`role` aliases come from Spatie:

```php
$middleware->alias([
    'admin' => EnsureUserIsAdmin::class,
    'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
    'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
]);
$middleware->redirectGuestsTo(fn () => route('admin.login'));
```

`EnsureUserIsAdmin` is the **panel-entry** gate: `is_active` + `canManageContent()` (= the `access-admin` permission) → `abort(403)`. **Per-section** authorization is the `permission:manage-<section>` middleware on each route group. In code/Blade, check with `$user->can('manage-faqs')` / `@can('manage-faqs')` — never re-read the legacy `User.role` string. New sections: add a permission to `config/rbac.php`, gate the route with `permission:<key>`, and add an `@can`-filtered nav link. super_admin bypasses everything via `Gate::before` (`AppServiceProvider`).

## 5. Config / env access

`env()` lives **only** in `config/*` files; app code reads `config('dot.path')`. From `config/services.php`:

```php
'razorpay' => [
    'key' => env('RAZORPAY_KEY', ''),
    'secret' => env('RAZORPAY_SECRET', ''),
    'currency' => env('RAZORPAY_CURRENCY', 'INR'),
],
```

Consumed in `app/Providers/AppServiceProvider.php` (singleton binding) and controllers:

```php
$this->app->singleton(RazorpayService::class, fn () => new RazorpayService(
    (string) config('services.razorpay.key', ''),
    (string) config('services.razorpay.secret', ''),
    (string) config('services.razorpay.currency', 'INR'),
));
// ...later: $rzpKey = config('services.razorpay.key');
```

**Never call `env()` outside config files** — it returns null once config is cached.

## 6. Module / feature folder organization

A feature spans the standard Laravel layout, with controllers split into `Public/` vs `Admin/`. The **Donation** feature:

| Layer | Path |
|---|---|
| Public / Admin controllers | `app/Http/Controllers/{Public,Admin}/DonationController.php` |
| Models | `app/Models/Donation.php`, `DonationCategory.php` |
| External integration → **Service** | `app/Services/RazorpayService.php` (singleton) |
| Side-effecting logic → **invokable Action** | `app/Actions/SendDonationReceipt.php` |
| Mailable | `app/Mail/DonationReceiptMail.php` |
| Migration / Seeder | `database/migrations/2025_01_01_000009_create_donations_table.php`, `database/seeders/DonationCategorySeeder.php` |
| Views | `resources/views/public/donation/*.blade.php`, `admin/donations/*.blade.php`, `pdf/donation-invoice.blade.php` |

**Rule:** external API → `Service` (bound in `AppServiceProvider`); idempotent side-effect → invokable `Action`. No repository layer.

## 7. How tests are written

**PHPUnit** (not Pest). Tests extend `tests/TestCase.php`; suites `Unit`/`Feature` run on in-memory SQLite (`phpunit.xml`). Run: `composer test`, single: `php artisan test --filter=Name`.

```php
// tests/Feature/ExampleTest.php
class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
```

> ⚠️ Current coverage = scaffold stubs only. New feature tests should use `RefreshDatabase` and live in `tests/Feature/`. Method names: `test_snake_case` or `#[Test]` attribute.

## 8. Background jobs / queue tasks

**None exist yet.** `QUEUE_CONNECTION=database` is configured but no `Job` classes are defined. Side effects (receipt email) run **synchronously** inside the request via an invokable Action (`SendDonationReceipt`). If adding async work: create `app/Jobs/*`, implement `ShouldQueue`, dispatch with `Job::dispatch(...)`, and ensure a queue worker runs on the server.

## 9. Frontend structure

Server-rendered Blade + Tailwind v4 + CDN Alpine. No SPA / JS component framework.

- **Layouts:** `resources/views/public/layout.blade.php`, `admin/layout.blade.php`; assets via `@vite(['resources/css/app.css', 'resources/js/app.js'])`.
- **CSS:** Tailwind v4 configured **in-CSS** (`resources/css/app.css` — `@import 'tailwindcss';`, `@source`, `@theme { --color-saffron-*, --font-display ... }`). No `tailwind.config.js`.
- **JS:** minimal. `resources/js/app.js` imports `qrcode` and renders UPI QR onto `[data-upi-qr]` at `DOMContentLoaded`.
- **Alpine 3** loaded via CDN `<script defer src="https://unpkg.com/alpinejs@3.x.x/...">` — not bundled.
- **Shared partials:** `resources/views/public/partials/*` (`header`, `footer`, `page-hero`, `campaign-card`, `donate-cta`).

## 10. Response formatting (success & error)

All responses are HTML (no JSON). Three idioms:

```php
return view('admin.donations.index', compact('donations'));                 // GET page
return redirect()->route('donations.pay', $donation);                        // success → redirect
return back()->with('success', 'Donation status updated.');                  // in-place update
return back()->withInput($request->only('email'))                            // validation/error
    ->withErrors(['email' => 'These credentials do not match our records.']);
```

Flash keys are `success` and `error`. Validation errors surface via `$errors` in Blade.

## 11. Naming conventions

- **Controllers:** `PascalCase` + `Controller`, namespaced by surface (`Admin\DonationController`, `Public\DonationController`); aliased in routes (`use ...Admin\DonationController as AdminDonationController`).
- **Models:** singular `PascalCase` (`Donation`, `CowSponsorship`). Status constants: `Donation::STATUS_SUCCESS`.
- **Migrations:** domain tables use ordered prefix `2025_01_01_0000XX_` + verb-first name (`..._create_donations_table.php`).
- **Routes:** kebab-case URIs (`/our-goshala`, `donation-categories`), dot-named (`donations.pay`, `admin.dashboard`).
- **DB columns:** `snake_case` (`donor_name`, `payment_status`); FKs `*_id` via `foreignId()->constrained()->nullOnDelete()`.
- **Blade views:** lowercase dot paths mirroring surface (`admin.donations.index`, `public.donation.checkout`).

## 12. Import / export patterns

- PHP namespacing per PSR-4 (`App\...`); one class per file. Controllers `App\Http\Controllers\{Public,Admin}`.
- Route file disambiguates same-named controllers with `use ... as Alias` (e.g. `PublicDonationController` / `AdminDonationController`).
- Dependencies are **constructor-injected** with promoted readonly props:
  ```php
  public function __construct(private readonly RazorpayService $razorpay) {}
  ```
- Services resolved via the container (singleton in `AppServiceProvider`), not `new`. Models referenced via FQN imports at the top of the file.
- JS uses ES module imports (`import QRCode from 'qrcode'`); `package.json` is `"type": "module"`.

## 13. Image uploads and video media

Validate uploads in the controller, then use the shared service rather than calling `UploadedFile::store()` directly:

```php
// app/Http/Controllers/Admin/CowController.php
public function __construct(private readonly OptimizedImageStorage $images) {}

$data['image'] = $this->images->replace(
    $request->file('image'),
    'cows',
    $cow->image,
);
```

`app/Services/OptimizedImageStorage.php` writes UUID-named WebP files at quality 82, limits the longest edge to 1920px, and can center-crop exact dimensions. New image forms must accept only JPEG, PNG, and WebP, validate MIME/size server-side, and use this service. Gallery videos are external YouTube URLs validated by `app/Rules/YouTubeUrl.php`; do not upload video files to this server.
