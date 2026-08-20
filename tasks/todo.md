# Current Tasks

## In Progress
### Invitation feature (admin) — AWAITING APPROVAL
Admin-only invitation sender: staff fills invitee + event details, invitee gets a
branded invitation email with trust details; every invitation logged in DB.

- DB: `invitations` table + `Invitation` model (invitee_name, invitee_email,
  invitee_phone?, occasion, event_date?, event_time?, venue?, message?, status,
  sent_at, created_by). STATUS_PENDING/SENT/FAILED.
- RBAC: new `manage-invitations` permission in config/rbac.php (Engagement group),
  granted to admin (super_admin auto). Reseed + assign on live.
- Controller: `Admin\InvitationController` — index (list), create (form), store
  (validate → save → send email → set status), destroy, resend.
- Routes: under `auth`+`admin`, gated `permission:manage-invitations`.
- Nav: "Invitations" link in admin sidebar (Engagement group), @can-gated.
- Views: admin/invitations/{index,form}.blade.php (existing admin style).
- Email: `InvitationMail` mailable + emails/invitation.blade.php using the shared
  branded emails/layout (logo header/footer). Content: greeting, invitation text,
  event block (occasion/date/time/venue), personal message, trust details
  (address x2, phones, emails, whatsapp), CTA (website / directions). Sent via
  Brevo; failures caught + logged + status=failed.
- Tests: permission gate (403 without), create+send (Mail::fake asserts InvitationMail
  to invitee), validation, record persisted.
- Deploy: migrate + seed permission + assign to admin on live (backup first).
### Client delivery QA — 2026-08-14 (awaiting APPROVED)
- Fix admin login so authenticated/admin logins always land on `/admin`.
- Fix permission create/edit 500 caused by an unescaped Blade directive example.
- Fix 320/390px overflow in the public footer, admin dashboard, and donation detail.
- Associate every visible public/admin form control with an accessible label.
- Add regression, form-render, validation, and responsive-markup tests.
- Re-test every public/admin page at 320, 390, 768, and 1440px; build; deploy; live smoke-test.
- Generate `docs/QA-SHEET.md`, update project docs/changelog, and log deployment.

### RBAC — DONE locally (pending server rollout)
Built + tested locally (11 tests pass). Spatie installed, config/rbac.php + RolePermissionSeeder,
Users/Roles/Permissions CRUD + views, per-section permission middleware, nav filtered, super_admin
bypass. Access-control menu restricted to super_admin. NOT deployed to server yet — separate step
(backup → migrate → seed RolePermissionSeeder → UserSeeder backfill).
Pre-existing unrelated failure: tests/Feature/ExampleTest.php (RefreshDatabase commented out).

### RBAC — original plan (reference)
Decisions: Spatie laravel-permission · permissions fully editable · full enforcement (nav + routes) ·
seed existing users' roles so no current login breaks.

- Phase 0 — Setup: `composer require spatie/laravel-permission`, publish + run migrations,
  add `HasRoles` to User.
- Phase 1 — Reconcile: seed permissions (`manage-<section>` + `access-admin`) + roles
  (super_admin/admin/editor/staff), backfill existing users, refactor `isAdmin()`/`canManageContent()`
  to delegate to Spatie (method names unchanged so callers keep working).
- Phase 2 — Enforce: register `permission` middleware alias, gate each admin section route group,
  filter sidebar nav with `@can('manage-<section>')`.
- Phase 3 — UI: `Admin\{User,Role,Permission}Controller` resource CRUD + Blade views (existing
  x-admin.page-header / admin-card / form-* style) + new "Access control" nav group. Guards:
  can't delete self/last super_admin; super_admin role locked.
- Phase 4 — Tests: user CRUD gate, permission→section access flip (200↔403), section gating,
  back-compat for seeded super_admin.
- Phase 5 — Docs: ARCHITECTURE/PATTERNS auth section + CHANGELOG.
- Rollout: Spatie migrations additive (safe); deploy = backup → migrate → seed → backfill. Separate step.

## Planned
### Part A — Domain (user action, not code)
- Register a real domain (recommend `.org`).
- Add DNS A records `@` and `www` -> 159.223.107.48.
- On server: add ServerName/ServerAlias to Apache vhost, run certbot SSL, set APP_URL, view:clear.
- After domain live: submit sitemap in Google Search Console.

## Completed
### WebP media optimization — 2026-08-13
- All 9 admin image forms store optimized WebP via shared GD service (quality 82, max 1920px).
- Gallery covers crop to 1280×720; replaced images are deleted only after successful new storage.
- Gallery video uses validated external YouTube URLs; no server-side video files.
- Focused tests: 12 passed (19 assertions); Vite build passed.

### Part B — SEO code (make site discoverable on Google)
- Dynamic `/sitemap.xml` (static pages + published blog/campaigns/events/gallery albums).
- `robots.txt`: add Sitemap directive, disallow /admin.
- Canonical `<link>` in public layout.
- JSON-LD Organization (NGO) structured data in public layout.
- Feature test for sitemap route.
- CHANGELOG entry.

### Notes
- Canonical/OG/JSON-LD all derive from APP_URL — switch APP_URL when the real domain lands and
  everything follows automatically. No code change needed for the domain cutover.
