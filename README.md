# Gopal Seva Samarpan Trust — Platform

Production-ready Laravel 12 platform for a goshala / cow-rescue NGO.

## Stack
- **Laravel 12.58** (PHP 8.2+)
- **Tailwind CSS v4** + **Vite 7** + **Alpine.js 3** (CDN)
- **SQLite** (default — switch to MySQL/Postgres in `.env` for production)
- **Razorpay** payment integration (test scaffold + live API)
- **Custom admin panel** (no Filament — works on any PHP without `intl`)

## Quick start

```bash
# in platform/ directory
composer install
npm install
cp .env.example .env   # already provided
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build         # or: npm run dev
php artisan serve
```

Visit:
- Public site: http://127.0.0.1:8000/
- Admin panel: http://127.0.0.1:8000/admin/login
  - **Super-admin:** `admin@gopalsevatrust.org` / `Gopal@2025`
  - **Editor:** `editor@gopalsevatrust.org` / `Editor@2025`

## Architecture

### Public site (14 pages)
Home, About, Our Goshala, Donation (+ checkout, pay, thanks), Campaigns
(index + show), Events (index + show), Gallery (index + show), Blog (index +
show), Volunteer (form + thanks), Contact, FAQs, Testimonials, Transparency.

### Admin panel
Dashboard with 10 KPIs, full CRUD for Cows, Campaigns, Events, Blog Posts,
Donation Categories, Testimonials, Team, FAQs, Gallery (albums + items),
plus list/show/update for Donations, Volunteers, Contact Messages, and
Site Settings.

### Database (21 tables)
`users`, `site_settings`, `cow_categories`, `cows`, `cow_sponsorships`,
`donation_categories`, `campaigns`, `campaign_updates`, `donations`,
`events`, `gallery_albums`, `gallery_items`, `blog_categories`,
`blog_posts`, `volunteers`, `contact_messages`, `testimonials`,
`team_members`, `pages`, `faqs`, `subscribers`.

### Donation flow
1. User picks category/cow/campaign → `/donate/checkout`
2. Submits donor info + amount → `Donation` row created (`pending`)
3. Razorpay order id assigned (or local stub if creds are placeholder)
4. `/donate/{id}/pay` shows Razorpay checkout (or local "simulate" button)
5. Callback route verifies HMAC signature, marks `success`, increments
   campaign tally, redirects to thanks page.

### Razorpay
Edit `.env`:
```
RAZORPAY_KEY=rzp_live_xxx
RAZORPAY_SECRET=xxx
```
The placeholder values let you exercise the full flow locally without a
Razorpay account — `RazorpayService` falls back to simulated orders and
trusts simulated callbacks.

### Roles
`super_admin`, `admin`, `editor`, `staff` — see `App\Models\User`.
Admin middleware: `EnsureUserIsAdmin` (alias `admin`).

## File map

```
app/Http/Controllers/Admin/      # 13 admin controllers
app/Http/Controllers/Public/     # 8 public controllers
app/Models/                      # 19 Eloquent models
app/Services/RazorpayService.php # Razorpay client
database/migrations/             # 21 domain migrations + 3 framework
database/seeders/                # 12 seeders with realistic content
resources/views/public/          # 14 page views + partials
resources/views/admin/           # admin layout, dashboard, all CRUD pages
resources/views/components/admin/ # shared admin Blade components
routes/web.php                   # 99 named routes
```

## Production checklist

- [ ] Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
- [ ] Configure MySQL/Postgres `DB_*` vars
- [ ] Set real `RAZORPAY_KEY` / `RAZORPAY_SECRET`
- [ ] `MAIL_*` for receipt emails (Postmark / Resend / SES)
- [ ] `php artisan config:cache route:cache view:cache`
- [ ] `npm run build`
- [ ] Configure file uploads disk (S3 / disk path)
- [ ] HTTPS + HSTS at the load balancer
- [ ] Daily DB backup cron
- [ ] Razorpay webhook (recommended) for async payment confirmation
# GSSTG
