# Current Tasks

## In Progress
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
