# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

# Project Overview
GSSTG (Gopal Seva Samarpan Trust) is a Laravel 12 platform for a goshala / cow-rescue NGO. It serves a public marketing + donation website and a custom server-rendered admin panel for managing all content, donations, volunteers, and contact messages. Donations flow through Razorpay (online) or UPI (manual-verify) and generate an auto-numbered 80G PDF receipt emailed to the donor. It is server-rendered only (Blade + CDN Alpine) — no separate API or SPA.

# Tech Stack
- Laravel 12, PHP 8.2+ · Eloquent ORM
- DB: SQLite (local) / MySQL (prod); sessions, cache, queue all use the `database` driver
- Tailwind CSS v4 + Vite 7; Alpine.js 3 (CDN); minimal bundled JS (`qrcode` for UPI)
- DomPDF (80G receipts) · Razorpay + UPI payments · Gmail SMTP
- PHPUnit 11 (no Pest) · custom session-based admin auth

# Architecture
Two surfaces: public site (`Public\*` controllers) and `/admin` panel (`Admin\*`, behind `auth`+`admin` middleware). Thin controllers → Eloquent models → `RazorpayService` (singleton) / `SendDonationReceipt` (invokable action) → Blade view or redirect. **See docs/ARCHITECTURE.md for details.**

# Directory Structure
- `app/` — controllers, models, services, actions, mail, middleware, providers
- `bootstrap/` — Laravel 12 slim config (`app.php`, middleware aliases)
- `config/` — 10 config files (services, mail, database, …)
- `database/` — migrations (21 domain tables), seeders, factories, local sqlite
- `resources/` — Blade views, Tailwind CSS, minimal JS
- `routes/` — `web.php` (all routes), `console.php`
- `public/` — web root + Vite build output
- `tests/` — PHPUnit (scaffold only so far)
- `docs/` — architecture, patterns, audit, access, deploy log
- `tasks/` — todo + lessons

# Key Commands
- `composer setup` — first-time bootstrap (install, env, key, migrate, build)
- `composer dev` — serve + queue:listen + pail + vite concurrently
- `npm run dev` / `npm run build` — Vite assets
- `composer test` — config:clear + full PHPUnit suite
- `php artisan test --filter=Name` — single test
- `php artisan migrate --seed` — schema + demo data
- Deploy: manual file-mirroring to `159.223.107.48:/var/www/html/goshala` (no CI). No lint step wired in (Pint available).

# Coding Conventions
- Controllers namespaced by surface (`Admin\`/`Public\`), thin, always typed returns; validate inline.
- External APIs → `app/Services/` (singleton); idempotent side-effects → invokable `app/Actions/`.
- snake_case DB columns; `*_id` FKs; PascalCase models with `STATUS_*` constants; kebab-case URIs, dot-named routes.
- `config('...')` in app code; `env()` only in `config/*`. Constructor-inject dependencies (readonly promoted props).

# Patterns
See docs/PATTERNS.md for examples with real code (routes, queries, error handling, middleware, config access, responses, naming).

# Testing
PHPUnit 11. Tests live in `tests/Feature/` and `tests/Unit/`, run on in-memory SQLite. Run `composer test`, or `php artisan test --filter=Name` for one. Coverage is currently scaffold-only — add Feature tests for the donation flow and admin gate first.

# Task Management
- Before starting work, write plan to tasks/todo.md
- Track progress by marking items complete
- After ANY correction or mistake, update tasks/lessons.md with a rule that prevents it
- After completing work, add entry to CHANGELOG.md

# Git Workflow
- Always create a feature branch: feature/[your-name]/[short-description]
- Never commit directly to main
- Every PR must have a clear description of what changed and why
- Run tests before pushing
- Request review from at least one team member

# Important Rules
- NEVER modify code without an approved plan
- NEVER skip tests
- ALWAYS check docs/PATTERNS.md before creating new patterns
- ALWAYS update CHANGELOG.md with your changes
- Do not touch: .claude/skills/ (third-party, not project code), node_modules/, vendor/, .git/, public/build/, .env files
- Project-specific protected paths: database/database.sqlite (local data), storage/ (runtime), database/gopal_seva_mysql.sql (prod dump)

# Bulk Operation Safety
- NEVER run bulk find-and-replace (sed, grep -rl | xargs) without excluding: .claude/skills/, node_modules/, vendor/, .git/, public/build/, package-lock.json, composer.lock
- Safe bulk rename pattern: grep -rl 'old-name' --exclude-dir=node_modules --exclude-dir=vendor --exclude-dir=.git --exclude-dir=public --exclude-dir=.claude/skills . | xargs sed -i 's/old-name/new-name/g'
- ALWAYS show the list of files that will be affected BEFORE running any bulk operation
- ALWAYS ask for confirmation before executing bulk changes

# Security Rules (Enforced on Every Task)
- NEVER store auth tokens in localStorage — use httpOnly cookies only (this app uses server sessions)
- NEVER return stack traces, file paths, or SQL errors in API responses
- NEVER build SQL queries with string concatenation — use Eloquent / parameterized bindings
- NEVER commit .env files or hardcode secrets in source code
- NEVER serve user-uploaded files without MIME type and size validation
- ALWAYS verify webhook/callback signatures (Razorpay HMAC via RazorpayService::verifySignature)
- ALWAYS rate-limit auth, payment, and public routes
- ALWAYS validate environment variables / config at app startup
- ALWAYS add server-side validation — client-side validation is not security
- ALWAYS use HTTPS — no mixed content
- ALWAYS hash passwords with bcrypt/argon2 (BCrypt rounds 12 here) — never plaintext
- (Multi-tenant tenant_id rules: N/A — this is a single-tenant app)

# Deployment Rules
- Server: Claude can deploy and test autonomously. For destructive DB ops (DROP, TRUNCATE, DELETE without WHERE), show command and wait for APPROVED.
- ALWAYS backup database before running migrations — no exceptions
- After any artisan/composer run on the server as root: chown -R www-data:www-data storage bootstrap/cache (else unlogged 500s)
- NEVER auto-fix without circuit breaker: max 3 auto-fix cycles, then STOP and report
- If circuit breaker fires, run /rollback — NEVER leave a broken deployment live
- /rollback checks database migration state before reverting code
- Log every deployment to docs/DEPLOY_LOG.md
- NEVER run bulk sed/find-replace without excluding: .claude/skills/, node_modules/, vendor/, .git/, public/build/, lock files
- Before ANY operation touching 5+ files, show the file list and wait for APPROVED

# Testing Rules
- Every new route/endpoint MUST have at least one automated test
- Tests must verify behavior (what SHOULD happen), not just implementation
- Human defines WHAT to test, AI writes HOW to test
- Run /test before every push — no exceptions
- After human QA finds a bug, add a regression test
- Test categories: API contracts, business logic (payment transitions), auth/role gates, integration, security
- For human QA test cases, run /generate-qa-sheet before release

# Subdirectory Docs
- app/CLAUDE.md · app/Http/Controllers/CLAUDE.md · app/Models/CLAUDE.md
- app/Services/CLAUDE.md · app/Actions/CLAUDE.md
- database/CLAUDE.md · resources/CLAUDE.md · routes/CLAUDE.md · tests/CLAUDE.md

# Context Window Budget
- Root CLAUDE.md: under 150 lines (this file)
- Each subdirectory CLAUDE.md: under 80 lines
- tasks/lessons.md: prune entries older than 30 days to an archive file
- docs/explorations/: delete explorations older than 2 weeks (re-explore if needed)
- .claudeignore keeps build artifacts, dependencies, and large data out of context
- When context feels heavy: run /compact. One task per session — start fresh.
