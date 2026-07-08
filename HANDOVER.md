# Project Handover — Gopal Seva Samarpan Trust (GSSTG)

_Goshala / cow-rescue NGO platform. Laravel 12 + custom admin panel._

> Last updated: 2026-06-30. **Keep this file local — it contains server access notes and is intentionally NOT committed to git.**

---

## 0. Start here (new developer)

Read these first, in order — they replace most of the tribal knowledge:
1. **`CLAUDE.md`** (root) — project overview, conventions, team rules, command list.
2. **`docs/ARCHITECTURE.md`** — system design, DB schema, routes, server access.
3. **`docs/PATTERNS.md`** — how to write code here (real examples).
4. **`docs/ACCESS.md`** — credentials/access checklist (fill in the `[FILL IN]` bits).
5. This file (`HANDOVER.md`) — current state + open issues + the actual server password location.

There is also a full audit in `docs/CODEBASE_AUDIT.md` and slash commands under `.claude/commands/` (run `/pickup` to auto-orient).

---

## 1. Quick facts

| | |
|---|---|
| **Project** | Gopal Seva Samarpan Trust (`GSSTG`) — Laravel 12 NGO donation platform |
| **PHP** | 8.2+ (live: 8.2.31, php8.2-fpm) |
| **Frontend** | Tailwind v4, Vite 7, Alpine 3 (CDN), minimal JS (UPI QR) |
| **DB** | SQLite (local) / MySQL `gopal_seva` (live) |
| **Git remote** | `https://github.com/VikasYadav85/GSSTG.git` (branch `main`) |
| **Local path** | `/Applications/XAMPP/xamppfiles/htdocs/Goshala/GSSTG` |
| **Live URL** | https://goshala.159.223.107.48.sslip.io |
| **Admin panel** | `/admin/login` — `admin@gopalsevatrust.org` / `Gopal@2025` (super-admin) |

---

## 2. Live server access

| | |
|---|---|
| **Host** | `159.223.107.48` (DigitalOcean droplet) |
| **SSH** | user `root`, port 22 — **password auth** (key auth does NOT work) |
| **Password** | FileZilla site "159_test" → `~/.config/filezilla/sitemanager.xml` (base64-encoded) |
| **App path** | `/var/www/html/goshala` |
| **Web user** | `www-data` (nginx + php8.2-fpm, no Docker) |
| **DB (live)** | MySQL `gopal_seva` — creds in server `/var/www/html/goshala/.env` (`DB_*`) |

`sshpass` is installed at `/opt/homebrew/bin/sshpass`. Deployment = **manual file-mirroring** (edit the matching file on the server, `.bak` first, then `php artisan view:clear` / `config:clear`). No CI/CD.

### ⚠️ Critical server rule
After ANY `php artisan` / `composer` run on the server as root:
```bash
chown -R www-data:www-data /var/www/html/goshala/storage /var/www/html/goshala/bootstrap/cache
```
Otherwise www-data can't write → **unlogged 500 errors**.

---

## 3. 🔴 Open issues (current state)

### 3a. Receipt emails do NOT send on live — SMTP ports blocked
DigitalOcean blocks all outbound SMTP ports (25/465/587/2525) on the droplet; only **443 is open**. Gmail SMTP (`radheradhe7266@gmail.com`) therefore times out — every `success` donation's receipt fails (`Connection timed out` in `storage/logs/laravel.log`). GD extension is fine now (fixed).
- The Gmail **app-password was updated** to `fbxr cobd niyh hqlu` (config cleared) — but this does NOT fix it; the port block does.
- **Two fixes (decision pending):** (A) ask DigitalOcean to unblock SMTP (ticket — owner chose this; slow, may be refused), or (B) switch mailer to Resend/Brevo HTTP API (works over 443 — faster, needs a free API key).

### 3b. Donations awaiting receipt re-send (once mail works)
`success` but `receipt_issued_at` is NULL — re-trigger from admin panel (re-save status = "success") once SMTP works:
- **id 1, id 2** — Vivek (₹10 each)
- **id 4** — Arun Yadav (₹10) — UPI, money received, **manually marked success on 2026-06-30** (was stuck `pending`).

### 3c. Razorpay is NOT live
Live `.env` has placeholder Razorpay keys (`rzp_test_placeholder`), so `RazorpayService` returns fake `order_LOCAL_…` orders — real card/gateway payments don't work. **Only UPI works** (VPA `7266945885@ptaxis`), and UPI needs manual admin verification. Set real `RAZORPAY_KEY`/`RAZORPAY_SECRET` + `config:clear` to enable online payments.

---

## 4. Architecture in one screen

Two surfaces: public site (`app/Http/Controllers/Public/`) + `/admin` panel (`Admin/`, behind `auth`+`admin` middleware). Thin controllers → Eloquent models → `RazorpayService` (singleton) / `SendDonationReceipt` (invokable, idempotent action) → Blade view or redirect. Server-rendered only, no API. 21 domain tables; `Donation` is the hub (status `pending→processing→success/failed/refunded`). Full detail in `docs/ARCHITECTURE.md`.

---

## 5. Common commands

```bash
composer setup            # first-time bootstrap
composer dev              # serve + queue + vite + logs
composer test             # full PHPUnit suite (coverage is scaffold-only so far)
php artisan test --filter=Name   # single test
npm run build             # production assets
php artisan migrate --seed
```
Deploy: mirror the file to `159.223.107.48:/var/www/html/goshala`, then `view:clear`/`config:clear` + chown.

---

## 6. Git workflow (going forward)

- The **documentation layer** (CLAUDE.md, docs/, tasks/, .claude/, CHANGELOG, .claudeignore) is committed & pushed to `main` (commit `b6b0094`). That was a one-time direct-to-main push.
- **From now on:** feature branches `feature/<name>/<desc>` + PRs. Never push to `main` directly.
- `.claude/` is now tracked (only `.claude/settings.local.json` stays ignored) so the whole team shares the same commands + tool permissions (`.claude/settings.json`).
- ⚠️ The working tree has ~20 unrelated **modified app files** (controllers, views, composer, etc.) that were NOT committed — review and commit/discard those separately. `HANDOVER.md` is deliberately untracked.

---

## 7. Contacts / accounts

- Razorpay account, Gmail (`radheradhe7266@gmail.com`) app-password, UPI VPA `7266945885@ptaxis`, DigitalOcean droplet — all managed by the project owner. Fill owners into `docs/ACCESS.md`.
- Receipt invoice shows contact `vy32353@gmail.com` / `+91 8591300362`.

---

## 8. Immediate next steps

1. **Fix email** — decide DO-unblock vs Resend/Brevo API (3a), then re-send receipts for donations id 1, 2, 4 (3b).
2. **Enable real payments** — add live Razorpay keys (3c).
3. **Clean working tree** — review the ~20 uncommitted app changes (6).
4. **Optional cleanup** (from audit §11): remove dead `resources/views/welcome.blade.php`, the stray `image /` folder, and confirm `axios` usage.
