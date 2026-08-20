# Codebase Audit — GSSTG (Gopal Seva Samarpan Trust)

Laravel 12 platform for a goshala / cow-rescue NGO. Public marketing + donation site and a custom admin panel. Project root: `/Applications/XAMPP/xamppfiles/htdocs/Goshala/GSSTG`.

> Generated: 2026-06-30. Source of truth for `docs/ARCHITECTURE.md` and `docs/PATTERNS.md`.

---

## 1. Directory structure

**Top-level:**
- `app/` — application PHP (controllers, models, services, actions, mail, middleware, providers)
- `bootstrap/` — framework bootstrap; `bootstrap/app.php` (Laravel 12 slim config), `providers.php` (only `AppServiceProvider`)
- `config/` — 10 config files (app, auth, cache, database, filesystems, logging, mail, queue, services, session)
- `database/` — `migrations/` (24), `seeders/` (13 + DatabaseSeeder), `factories/` (UserFactory only), `database.sqlite` (local DB), `gopal_seva_mysql.sql` (109 KB prod dump), `export-mysql.php` (one-off export script)
- `public/` — web root: `index.php`, `build/` (Vite output + manifest), `img/`, `storage` symlink, `robots.txt`, `favicon.ico`
- `resources/` — `views/` (Blade), `js/`, `css/`
- `routes/` — `web.php` (~59 named routes), `console.php` (only stock `inspire`)
- `storage/` — runtime (logs, cache, sessions, compiled views)
- `tests/` — `Feature/` + `Unit/` (default `ExampleTest` stubs only), `TestCase.php`
- `vendor/`, `node_modules/` — dependencies
- `image ` — **stray root folder (trailing space)** with 4 WhatsApp `.jpeg` files; unreferenced (see §11)

**`app/` second level:** `Http/Controllers/Admin/` (15 controllers), `Http/Controllers/Public/` (10), `Http/Middleware/` (`EnsureUserIsAdmin`), `Models/` (22 models), `Services/` (`RazorpayService`), `Actions/` (`SendDonationReceipt`), `Mail/` (`DonationReceiptMail`), `Providers/` (`AppServiceProvider`).

**`resources/views/`:** `admin/`, `public/`, `emails/donations/`, `pdf/donation-invoice.blade.php`, `components/` (includes default `welcome.blade.php` — unreferenced).

---

## 2. Tech stack

- **Framework:** Laravel `^12.0`, PHP `^8.2`
- **ORM/DB:** Eloquent. Default `sqlite` (`database/database.sqlite`); production = MySQL (`gopal_seva`). 21 domain tables + 7 framework tables.
- **Queue:** `database` driver (jobs table). **Cache:** `database`. **Session:** `database`.
- **Auth:** custom session-based admin auth (`Admin\AuthController` + `EnsureUserIsAdmin` middleware), Laravel `auth`/`guest` guards. Roles: `super_admin`, `admin`, `editor`, `staff`. BCrypt rounds 12.
- **Frontend:** Vite `^7` + `laravel-vite-plugin ^2`, Tailwind CSS `^4` (`@tailwindcss/vite`, in-CSS config, no `tailwind.config.js`), locally bundled Alpine.js 3 + collapse. Bundled JS deps: `alpinejs`, `@alpinejs/collapse`, `axios`, `qrcode`.
- **Notable PHP libs:** `barryvdh/laravel-dompdf ^3.1` (80G receipt PDFs), `laravel/tinker`.
- **Testing:** PHPUnit `^11.5` (no Pest). Pint/Pail/Sail/Collision/Mockery/Faker dev tooling.

---

## 3. Request data flow

`public/index.php` → `bootstrap/app.php` (`Application::configure()`, registers `routes/web.php`, health `/up`, middleware aliases, exceptions) → middleware (alias `admin` → `EnsureUserIsAdmin`; `redirectGuestsTo(admin.login)`) → routing (`routes/web.php`, public vs `admin` group) → controller (e.g. `Public\DonationController`, constructor-injected `RazorpayService`) → Eloquent model (route-model binding) → `view()` or `redirect()->route()` → response back out through middleware.

There is **no `routes/api.php`** — server-rendered HTML only, no JSON API layer.

---

## 4. Database models & relationships

**21 domain tables:** `users`, `site_settings`, `cow_categories`, `cows`, `cow_sponsorships`, `donation_categories`, `campaigns`, `campaign_updates`, `donations`, `events`, `gallery_albums`, `gallery_items`, `blog_categories`, `blog_posts`, `volunteers`, `contact_messages`, `testimonials`, `team_members`, `pages`, `faqs`, `subscribers`. (+ framework: `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`.)

**Relationship graph:**
```
User ──hasMany──> BlogPost (author_id)
Volunteer ──belongsTo(approver)──> User (approved_by)

CowCategory ──hasMany──> Cow (category_id)
Cow ──hasMany──> CowSponsorship (cascade) | Donation (nullOnDelete)

DonationCategory ──hasMany──> Donation (nullOnDelete)
Campaign ──hasMany──> CampaignUpdate (cascade) | Donation (nullOnDelete)
Donation ──belongsTo──> DonationCategory, Campaign, Cow   (all nullable)

GalleryAlbum ──hasMany──> GalleryItem (cascade)
BlogCategory ──hasMany──> BlogPost (nullOnDelete)
BlogPost ──belongsTo──> BlogCategory, User(author)

Standalone: Event, ContactMessage, Testimonial, TeamMember, Page, Faq, Subscriber, SiteSetting
```

**`Donation` (the relational hub):** statuses `pending|processing|success|failed|refunded` (`payment_status`, default pending); `payment_method` `razorpay|upi|bank_transfer|cash|cheque`; fields `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`, `paid_at`, `payment_meta` (json), `receipt_no`, `receipt_issued_at`, auto `reference_no` (`GSST-Ymd-XXXXXX` in `booted()`), denormalised donor fields, `amount`, `currency` (INR), `frequency`, `is_anonymous`, `wants_80g_receipt`. All three FKs `nullOnDelete` — donations are preserved as immutable financial records.

**`SiteSetting`:** key/value store with static cached `get()`/`put()`/`flushCache()` (`rememberForever`, cache key `site_settings`).

---

## 5. API routes / endpoints (by module)

All in `routes/web.php`. No `api.php`.

- **Public pages:** `GET /`, `/about`, `/our-goshala`, `/transparency`, `/testimonials`, `/faqs`
- **Donations** (`donate` prefix, `donations.` name): `GET /donate`, `GET|POST /donate/checkout`, `GET /donate/{donation}/pay`, `POST /donate/{donation}/callback`, `POST /donate/{donation}/upi-confirm`, `POST /donate/{donation}/simulate` (local/testing only via `abort_unless`), `GET /donate/{donation}/thanks`
- **Content:** `GET /campaigns`, `/campaigns/{slug}`, `/events`, `/events/{slug}`, `/gallery`, `/gallery/{slug}`, `/blog`, `/blog/{slug}`
- **Forms:** `GET|POST /volunteer`, `GET /volunteer/thank-you`, `GET|POST /contact`, `POST /subscribe`
- **Admin auth** (`admin` prefix): `GET|POST /admin/login` (`guest`), `POST /admin/logout`
- **Admin dashboard/non-resource** (`auth`+`admin`): `GET /admin`, donations index/show/`PATCH` updateStatus, volunteers index/show/update, messages index/show/update/destroy, settings edit/update, gallery item add/destroy
- **Admin resources** (`->except(['show'])`, `auth`+`admin`): `cows`, `campaigns`, `events`, `blog` (`{post}`), `testimonials`, `team` (`{member}`), `donation-categories` (`{category}`), `gallery` (`{album}`), `faqs`

---

## 6. Shared utilities, common patterns, helpers

- **`RazorpayService`** (`app/Services/`) — singleton bound in `AppServiceProvider`; dual-mode (`isConfigured()` → fake `order_LOCAL_…` when keys are placeholders).
- **`SendDonationReceipt`** (`app/Actions/`) — invokable, idempotent action (guarded by `receipt_issued_at`); fires only for `success`, self-logs failures.
- **`AppServiceProvider::safeSettings()`** — defensive view-sharing of `SiteSetting` + `config('services.trust')` with try/catch fallback.
- **Eloquent scopes** — `scopeActive()`, `scopeFeatured()`, `scopePublished()` across content models.
- **Status constants** on models (`Donation::STATUS_*`, `BlogPost::STATUS_*`, `Volunteer::STATUS_*`, `ContactMessage::STATUS_*`).
- **`config()` everywhere in app code**; `env()` only in `config/*` and provider fallbacks.

---

## 7. Test setup

- **PHPUnit** (not Pest). `phpunit.xml` defines `Unit` (`tests/Unit`) + `Feature` (`tests/Feature`) suites; runs on in-memory SQLite (`DB_DATABASE=:memory:`, `MAIL_MAILER=array`, `QUEUE_CONNECTION=sync`).
- **Run:** `composer test` (= `config:clear` + `php artisan test`); single: `php artisan test --filter=Name` or `php artisan test tests/Feature/Foo.php`.
- **Coverage reality:** only the default `tests/Feature/ExampleTest.php` and `tests/Unit/ExampleTest.php` stubs exist. `tests/TestCase.php` is the empty scaffold. **No real test coverage yet.**

---

## 8. Build / deploy / CI/CD

- **Composer scripts:** `setup` (install+env+key+migrate+npm build), `dev` (concurrently: serve + queue:listen + pail + vite), `test`.
- **NPM scripts:** `build` (`vite build`), `dev` (`vite`).
- **CI/CD:** **None.** No `.github/workflows`, no `.gitlab-ci.yml`. Deployment is **manual file-mirroring** to `159.223.107.48:/var/www/html/goshala` (see root `CLAUDE.md` / `HANDOVER.md`). No lint step wired into CI (Pint available locally).

---

## 9. Environment variables & config files

**Env groups** (`.env.example` + `config/services.php`):
- **App:** `APP_NAME/ENV/KEY/DEBUG/URL/LOCALE/...`, `BCRYPT_ROUNDS`
- **DB:** `DB_CONNECTION` (sqlite), commented MySQL `DB_HOST/PORT/DATABASE/USERNAME/PASSWORD`
- **Session/Cache/Queue:** `SESSION_*`, `CACHE_STORE`, `QUEUE_CONNECTION` (all database)
- **Mail:** `MAIL_MAILER` (default `log`), `MAIL_HOST/PORT/USERNAME/PASSWORD/FROM_*`, `MAIL_SCHEME`
- **Payments:** `RAZORPAY_KEY/SECRET/CURRENCY`, `UPI_VPA`, `UPI_PAYEE_NAME`
- **Trust/80G** (in `services.php`, NOT in `.env.example`): `TRUST_EMAIL/PHONE/ADDRESS/PAN/80G_NUMBER/REG_NUMBER`
- **Scaffold/unused:** `AWS_*`, `REDIS_*`, `MEMCACHED_HOST`, `POSTMARK_API_KEY`, `RESEND_API_KEY`, `SLACK_*`

**Config files:** `app, auth, cache, database, filesystems, logging, mail, queue, services, session` (10).

---

## 10. Third-party integrations

| Service | Used by | Credentials |
|---|---|---|
| **Razorpay** (payment gateway) | `app/Services/RazorpayService.php` (`api.razorpay.com/v1/orders`, HMAC verify) | `RAZORPAY_KEY`, `RAZORPAY_SECRET`, `RAZORPAY_CURRENCY` — currently **placeholders on live** |
| **UPI** (direct collection) | QR in `resources/js/app.js` (`qrcode`), `upi://` intent | `UPI_VPA`, `UPI_PAYEE_NAME` (live: `7266945885@ptaxis`) |
| **DomPDF** (80G PDF) | `app/Mail/DonationReceiptMail.php` → `pdf.donation-invoice` | local lib (needs PHP GD extension) |
| **SMTP / Gmail** (receipts) | `MAIL_*`, `DonationReceiptMail` | `MAIL_*`; **blocked on live** (DigitalOcean blocks ports 25/465/587/2525) |
| **Trust/80G metadata** | `DonationReceiptMail`, `AppServiceProvider` | `config('services.trust')` / `TRUST_*` |

**Declared but unused:** Postmark, Resend, AWS SES, Slack (vestigial `config/services.php` scaffold stanzas — no code references).

---

## 11. Dead code / unused dependencies / cleanup candidates

Confirmed dead/stray:
- `resources/views/welcome.blade.php` — default Laravel landing, unreferenced by any route.
- `tests/Feature/ExampleTest.php`, `tests/Unit/ExampleTest.php` — scaffold stubs, no real coverage.
- Root `image ` folder (trailing space) — 4 WhatsApp `.jpeg`s (~1 MB), unreferenced.

Likely unused (needs manual confirmation):
- `axios` (npm) + `resources/js/bootstrap.js` — `window.axios` set up but no view/JS references it.
- Postmark / Resend / SES / Slack stanzas in `config/services.php` — no code references, no env vars.
- `REDIS_*` / `MEMCACHED_*` env vars — app uses database drivers; not active.
- `database/export-mysql.php` + `database/gopal_seva_mysql.sql` — operational artifacts, possibly stale.
- `pestphp/pest-plugin` allow-listed in composer config but Pest not installed — minor config cruft.

No deprecated/abandoned package versions detected (Laravel 12, Tailwind 4, Vite 7, dompdf 3, PHPUnit 11 all current).
