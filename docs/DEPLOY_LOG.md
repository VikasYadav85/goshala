# Deployment Log
All deployments tracked automatically.

| Date | Branch | Server | Result | Issues | Deployed By |
|------|--------|--------|--------|--------|-------------|
| 2026-06-30 | main (9e20839) | 159.223.107.48 | SUCCESS | Initial git-deploy cutover (was manual file-mirroring). Backup at /root/goshala-backup-pre-gitcutover.tgz. Deploy key added; reset --hard to goshala/main (proven content no-op). Health: / /donate /admin all 200. | Claude/Sandeep |
| 2026-07-13 | main (cb6c15a) | 159.223.107.48 | SUCCESS (after 1 rollback) | RBAC deploy (Spatie + Users/Roles/Permissions menu). DB backup: /root/goshala-db-backup-2026-07-13_074330.sql (29 tables). First attempt (585ae19) FAILED: composer.lock had spatie/laravel-permission 8.3.0 (needs PHP 8.3) but prod is PHP 8.2.31 → composer install aborted, User.php referenced missing HasRoles trait → site down. Rolled back code to 172e4c3 (site restored, DB untouched). Fix: pinned spatie ^6 (6.25.0) + config.platform.php=8.2.31, re-pushed (cb6c15a). Redeploy: git pull + composer install + migrate (2 additive tables) + RolePermissionSeeder + UserSeeder (backfill). 17 perms, 4 roles seeded; existing admin/editor backfilled. Health: / and /admin/login both 200. | Claude |
| 2026-08-13 | main (c161645) | 159.223.107.48 | SUCCESS | WebP media optimization via PR #1. No migration/dependency changes. Production runtime smoke: PNG→WebP 1920×640; `/`, `/admin/login`, `/gallery` all HTTP 200. Initial `artisan test` check was unavailable because production dependencies exclude test tooling; direct runtime smoke passed. | Codex |
