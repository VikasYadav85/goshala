# Architecture — GSSTG (Gopal Seva Samarpan Trust)

> Factual snapshot of what exists in the code. See `docs/CODEBASE_AUDIT.md` for the raw audit and `docs/PATTERNS.md` for code-level conventions.

## 1. System Overview

GSSTG is a Laravel 12 web platform for a goshala (cow-rescue NGO). It serves a public marketing/donation website (home, about, campaigns, events, gallery, blog, volunteer, contact, FAQs, transparency) and a custom server-rendered admin panel for staff to manage all content plus donations, volunteers, and contact messages. Donations flow through Razorpay (online) or UPI (manual-verify), generating an auto-numbered record and an 80G PDF receipt emailed to the donor. It is server-rendered only (Blade + CDN Alpine) with no separate API or SPA frontend.

## 2. High-Level Architecture

```
                     Browser (donors + admin staff)
                               │  HTTPS
                               ▼
                      public/index.php  (entry)
                               │
                      bootstrap/app.php  (Laravel 12 config:
                       routes, middleware aliases, exceptions)
                               │
            ┌──────────────────┴───────────────────┐
            ▼                                       ▼
   PUBLIC routes (web)                   ADMIN routes  [auth + admin]
   Public\*Controller                    Admin\*Controller
            │                                       │
            └──────────────┬────────────────────────┘
                           ▼
                 Eloquent Models (app/Models)
                           │
        ┌──────────────────┼─────────────────────┐
        ▼                  ▼                       ▼
  RazorpayService    SendDonationReceipt     SiteSetting cache
  (app/Services)     (app/Actions, invokable) (rememberForever)
        │                  │
        ▼                  ▼
  api.razorpay.com   DonationReceiptMail ─► DomPDF (80G PDF)
                          │
                          ▼
                     SMTP (Gmail)
                           │
                           ▼
              Blade views (resources/views)  ─►  HTML response
              (Tailwind v4 build via Vite; Alpine 3 via CDN)

  Storage: MySQL (prod) / SQLite (dev) · Sessions/Cache/Queue → database driver
```

## 3. Directory Map

| Path | Purpose |
|---|---|
| `app/Http/Controllers/Public/` | 10 public-site controllers (Home, Donation, Campaign, Event, Gallery, Blog, Volunteer, Contact, Page, Subscriber) |
| `app/Http/Controllers/Admin/` | 15 admin controllers (Auth, Dashboard + CRUD for all content + Donation/Volunteer/Message/Settings) |
| `app/Http/Middleware/` | `EnsureUserIsAdmin` (alias `admin`) |
| `app/Models/` | 22 Eloquent models |
| `app/Services/` | `RazorpayService` (payment gateway abstraction) |
| `app/Actions/` | `SendDonationReceipt` (invokable, idempotent receipt issuer) |
| `app/Mail/` | `DonationReceiptMail` (80G receipt mailable + PDF) |
| `app/Providers/` | `AppServiceProvider` (binds RazorpayService, shares site settings) |
| `bootstrap/` | `app.php` (slim config), `providers.php` |
| `config/` | 10 config files |
| `database/migrations/` | 24 migrations (3 framework + 21 domain) |
| `database/seeders/` | 13 seeders + DatabaseSeeder |
| `database/factories/` | `UserFactory` only |
| `resources/views/public/` | public Blade pages + `partials/` |
| `resources/views/admin/` | admin layout, dashboard, per-model CRUD views |
| `resources/views/emails/`, `pdf/` | receipt email + 80G invoice templates |
| `resources/css/`, `resources/js/` | Tailwind entry + minimal JS (UPI QR) |
| `routes/` | `web.php` (all routes), `console.php` |
| `tests/` | `Feature/`, `Unit/` (scaffold stubs only) |

## 4. Database Schema

21 domain tables. Key models, fields, relationships:

- **User** (`users`): `role` (super_admin/admin/editor/staff), `is_active`. → hasMany `BlogPost` (author).
- **Donation** (`donations`) — hub: `payment_status` (pending/processing/success/failed/refunded), `payment_method` (razorpay/upi/bank_transfer/cash/cheque), `razorpay_order_id`/`payment_id`/`signature`, `paid_at`, `payment_meta` (json), `receipt_no`, `receipt_issued_at`, `reference_no` (auto `GSST-Ymd-XXXXXX`), `amount`, `frequency`, denormalised donor fields. → belongsTo `DonationCategory`, `Campaign`, `Cow` (all nullable `nullOnDelete`).
- **DonationCategory** (`donation_categories`): `suggested_amounts` (json), `default_amount`. → hasMany `Donation`.
- **Campaign** (`campaigns`): `goal_amount`, `raised_amount`, `status` (upcoming/active/completed/emergency), `progress_percentage` accessor. → hasMany `CampaignUpdate` (cascade), `Donation`.
- **Cow** (`cows`): `status` (active/under_treatment/passed_away), `monthly_sponsorship_amount`. → belongsTo `CowCategory`; hasMany `CowSponsorship` (cascade), `Donation`.
- **CowSponsorship** (`cow_sponsorships`): `plan`, `amount`, `status`. → belongsTo `Cow`.
- **CampaignUpdate** (`campaign_updates`): → belongsTo `Campaign`.
- **CowCategory** / **BlogCategory** (`*_categories`): `slug`, `sort_order`. → hasMany Cow / BlogPost.
- **BlogPost** (`blog_posts`): `status` (draft/published/archived), `tags` (json), `view_count`. → belongsTo `BlogCategory`, `User` (author).
- **GalleryAlbum** → hasMany **GalleryItem** (cascade; `type` image/video/youtube, `embed_url` accessor).
- **Event** (`events`): `type`, `starts_at`/`ends_at`, `status`. Standalone.
- **Volunteer** (`volunteers`): `status` (pending/approved/active/inactive/rejected), `areas_of_interest`/`availability` (json). → belongsTo `User` (approver).
- **ContactMessage** (`contact_messages`): `status` (new/read/replied/spam/closed), `message_type`. Standalone.
- **Testimonial**, **TeamMember**, **Page**, **Faq**, **Subscriber** — standalone content tables.
- **SiteSetting** (`site_settings`): key/value with type; static cached `get()`/`put()`.

(Full field lists in `docs/CODEBASE_AUDIT.md` §4.)

## 5. API Surface

Server-rendered web routes only (no `api.php`, no JSON API). Grouped:

- **Public pages:** `GET /`, `/about`, `/our-goshala`, `/transparency`, `/testimonials`, `/faqs`
- **Donations** (`donations.`): `GET /donate`, `GET|POST /donate/checkout`, `GET /donate/{donation}/pay`, `POST /donate/{donation}/callback`, `POST /donate/{donation}/upi-confirm`, `POST /donate/{donation}/simulate` (local/testing only), `GET /donate/{donation}/thanks`
- **Content:** `GET /campaigns[/{slug}]`, `/events[/{slug}]`, `/gallery[/{slug}]`, `/blog[/{slug}]`
- **Forms:** `GET|POST /volunteer`, `GET /volunteer/thank-you`, `GET|POST /contact`, `POST /subscribe`
- **Admin auth:** `GET|POST /admin/login`, `POST /admin/logout`
- **Admin manage:** `/admin` (dashboard); donations (index/show/`PATCH` updateStatus); volunteers (index/show/update); messages (index/show/update/destroy); settings (edit/update); gallery items (store/destroy)
- **Admin resources** (`->except(['show'])`): `cows`, `campaigns`, `events`, `blog`, `testimonials`, `team`, `donation-categories`, `gallery`, `faqs`

## 6. Authentication & Authorization

- **Admin login** is a custom session flow in `Admin\AuthController`: `Auth::attempt($credentials, remember)`, then re-checks `is_active` + `canManageContent()`, `session()->regenerate()`, `redirect()->intended(admin.dashboard)`.
- **Guards:** Laravel default `web` guard (session). `guest` middleware on login routes; `auth` + `admin` on all admin management routes.
- **`admin` middleware** (`EnsureUserIsAdmin`): rejects unauthenticated (redirect to `admin.login`), inactive users, or roles without `canManageContent()` (= super_admin/admin/editor; `staff` excluded) → `abort(403)`.
- **Roles** live on `User.role`; helpers `isAdmin()`, `canManageContent()`. Passwords hashed (BCrypt rounds 12).
- **Sessions** stored in the `sessions` DB table (`SESSION_DRIVER=database`).
- There is **no token/API auth** — no `api.php`, no Sanctum/Passport.

## 7. Background Jobs / Queues

- `QUEUE_CONNECTION=database` (jobs/job_batches/failed_jobs tables exist).
- **No application jobs are currently dispatched** — receipt email is sent **synchronously** inside the request via `SendDonationReceipt` (invokable action), not queued. `composer dev` runs `queue:listen` for local convenience but no `Job` classes exist in `app/`.

## 8. Third-Party Integrations

| Service | Module / file | Credentials |
|---|---|---|
| Razorpay | `app/Services/RazorpayService.php` (bound singleton) | `RAZORPAY_KEY`, `RAZORPAY_SECRET`, `RAZORPAY_CURRENCY` (placeholders on live → fake orders) |
| UPI | `resources/js/app.js` (QR), `Public\DonationController::pay/upiConfirm` | `UPI_VPA`, `UPI_PAYEE_NAME` |
| DomPDF | `app/Mail/DonationReceiptMail.php` → `pdf.donation-invoice` | local (needs PHP GD) |
| Gmail SMTP | `MAIL_*`, `DonationReceiptMail` | `MAIL_HOST/PORT/USERNAME/PASSWORD/FROM_*` (blocked on live — DO firewall) |
| Trust/80G metadata | `DonationReceiptMail`, `AppServiceProvider` | `config('services.trust')` / `TRUST_*` |

Declared but unused: Postmark, Resend, AWS SES, Slack (scaffold stanzas).

## 9. Deployment Architecture

- **No CI/CD.** Deployment is **manual file-mirroring**: edit the matching file directly on the server (make a `.bak`), then `php artisan view:clear` (Blade) or `config:clear` (`.env`/config).
- **Infra:** single DigitalOcean droplet `159.223.107.48`, app at `/var/www/html/goshala`, nginx + php8.2-fpm, MySQL (`gopal_seva`). Served at `https://goshala.159.223.107.48.sslip.io`.
- **Critical rule:** after any `php artisan`/`composer` run as root, `chown -R www-data:www-data storage bootstrap/cache` — otherwise www-data can't write → unlogged 500s.
- Local build: `npm run build`; first-time setup: `composer setup`.

## 10. Key Environment Variables

- **App:** `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL`, `BCRYPT_ROUNDS`
- **Database:** `DB_CONNECTION`; (prod) `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- **Session/Cache/Queue:** `SESSION_DRIVER`, `SESSION_LIFETIME`, `CACHE_STORE`, `QUEUE_CONNECTION` (all `database`)
- **Mail:** `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`, `MAIL_SCHEME`
- **Payments:** `RAZORPAY_KEY`, `RAZORPAY_SECRET`, `RAZORPAY_CURRENCY`, `UPI_VPA`, `UPI_PAYEE_NAME`
- **Trust/80G:** `TRUST_EMAIL`, `TRUST_PHONE`, `TRUST_ADDRESS`, `TRUST_PAN`, `TRUST_80G_NUMBER`, `TRUST_REG_NUMBER`
- **Unused scaffold:** `AWS_*`, `REDIS_*`, `MEMCACHED_HOST`, `POSTMARK_API_KEY`, `RESEND_API_KEY`, `SLACK_*`

## 11. Real-Time / Event Flows

None. `BROADCAST_CONNECTION` is at its scaffold default; there are **no WebSockets, SSE, broadcasting, pub/sub, or event listeners** wired up. The only "event-like" hook is the `Donation` model's `booted()` callback that generates `reference_no` on create. All interactivity is client-side Alpine (CDN) + a sliver of bundled JS for the UPI QR.

## 12. Server Access

- **Host/IP:** `159.223.107.48` (DigitalOcean droplet). App path: `/var/www/html/goshala`. Web user: `www-data`.
- **SSH (current reality):** user `root`, port 22, **password auth** (key auth not configured). Password stored in FileZilla site manager config; not in this repo.
- **SSH (recommended target):** dedicated `claude-server` user + ed25519 key — see `docs/SSH_CONFIG.md`. The `/deploy`, `/monitor`, `/logs`, `/db`, `/rollback` slash commands assume host alias `gsstg-server`.
- **Firewall:** DigitalOcean blocks outbound SMTP (ports 25/465/587/2525); only 443 open outbound — this is why Gmail-SMTP receipts currently fail.
- **DB access:** MySQL `gopal_seva` on the droplet (creds in server `.env`). No remote DB port exposed; access via SSH.
