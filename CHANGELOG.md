# Changelog
All notable changes to this project are documented here.
Format: [DATE] [AUTHOR] Description

## [Unreleased]

### Changed
- Trust identity corrected to match the registered trust deed. Legal name is now
  **Gopal Samarpan Sewa Charitable Trust** (was "Gopal Seva Samarpan Trust") across the
  public site, admin panel, meta/OG/JSON-LD, 80G PDF receipt, and emails (config-driven).
  Two addresses are now shown — **Goshala** (Jaunpur, UP – 222001) and **Registered Office**
  (A-002, Somnath Apartment, …, Bhayandar East, Thane, MH – 401105, from the deed) — on the
  contact page and footer; the 80G receipt uses the registered office as the official address
  (new `TRUST_REGISTERED_OFFICE` env + `registered_office` site setting). Stale "Vrindavan,
  Mathura" address removed. `publicSettings` now resolves lazily per view render (was shared once
  at boot) so it reflects settings saved during a request. Covered by `TrustIdentityTest`.

### Added
- Final client-delivery QA: form accessibility/validation and admin-login regression coverage. Full suite now passes 54 tests (176 assertions).
- Alpine.js and its collapse plugin are bundled locally through Vite so mobile navigation and accordions do not depend on a third-party CDN.
- Media optimization: every admin image upload now converts JPEG/PNG/WebP to WebP (quality 82), caps the longest edge at 1920px, uses UUID filenames, and removes replaced files safely. Gallery covers are center-cropped to 1280×720. Gallery videos remain external YouTube embeds with strict URL validation. Added 12 focused tests (19 assertions).
- RBAC: role-based access control via `spatie/laravel-permission`. New "Access control" admin menu
  (super_admin only) — Users, Roles, and Permissions management:
  - `Admin\{User,Role,Permission}Controller` (resource CRUD) + Blade views under
    `resources/views/admin/{users,roles,permissions}/`.
  - Users: create/edit accounts, assign a role, active toggle. Guards: can't delete self or the last
    super_admin; last super_admin can't be demoted.
  - Roles: create/edit with a grouped permission checkbox matrix. Built-in roles
    (super_admin/admin/editor/staff) can't be deleted; super_admin keeps every permission.
  - Permissions: fully editable catalog (key + display group); built-in permission keys locked;
    new permissions auto-granted to super_admin.
  - Enforcement: every admin section is gated by `permission:manage-<section>` middleware and the
    sidebar filters links via `@can(...)`. super_admin bypasses all gates (`Gate::before`).
  - Catalog + role presets live in `config/rbac.php`, seeded by `RolePermissionSeeder`.
  - `User::isAdmin()`/`canManageContent()` now delegate to Spatie (method names unchanged).
    `UserSeeder` backfills existing users' roles so no current login breaks.
- Tests: `SecretaryPermissionTest` (role-gated add/edit) + `AccessControlTest` (menu gating, user
  creation, permission→section access flip, guard rails, super_admin bypass). 11 tests, 27 assertions.
- Mail: notification emails on public form submissions, all using a shared branded email layout
  (logo header + footer): contact → admin notification + sender acknowledgement, new newsletter
  subscriber → welcome, volunteer → admin notification, and donation → admin notification (alongside
  the donor's 80G receipt, fired once from SendDonationReceipt). Admin recipient via
  `ADMIN_NOTIFY_EMAIL` (defaults to the From address). Failures are logged, never break the
  flow. Covered by tests.
- Mail: Brevo HTTP-API transport (`brevo` mailer) so email works on hosts that block outbound SMTP
  (DigitalOcean blocks 25/465/587 → Gmail SMTP timed out, donation receipts silently failed).
  Set `MAIL_MAILER=brevo` + `BREVO_API_KEY` + a Brevo-verified `MAIL_FROM_ADDRESS`.
- SEO: dynamic `/sitemap.xml` (static pages + published blog posts, campaigns, events, gallery albums).
- SEO: dynamic `/robots.txt` route (domain-agnostic, exposes sitemap, disallows /admin) replacing the static file.
- SEO: canonical link, og:url/og:site_name/twitter:card meta, and JSON-LD `NGO` structured data in the public layout.
- Tests: `SeoTest` covering sitemap XML, robots.txt, and homepage canonical/structured-data.
- Initial Claude Code documentation layer (CLAUDE.md, docs/, tasks/, .claude/)

### Changed
- Donation receipt email now uses the shared branded layout (logo header, "Dear {name}",
  receipt content, date + trust sign-off, footer) for a consistent look with the other emails.

### Fixed
- Admin login now always redirects authenticated users to `/admin`, ignoring stale frontend intended URLs.
- Permission create/edit pages no longer return 500 from an unescaped `@can` example.
- Mobile overflow on the public newsletter footer, admin dashboard, donation/volunteer/message detail cards, filters, and all wide admin tables.
- Every visible public/admin form control now has an accessible label; public/admin forms were verified at 320, 390, 768, and 1440 widths.

### Removed
(none)
