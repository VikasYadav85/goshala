# routes/ — Routing

## Purpose
Defines every HTTP endpoint. All app routes live in `web.php` (server-rendered, session-based). There is no `api.php` — the app has no JSON API. `console.php` holds only the stock `inspire` command.

## Key files
- `web.php` — ~59 named routes in two sections:
  - **Public:** home/about/goshala/transparency/testimonials/faqs, the `donate` group (`donations.*`), campaigns/events/gallery/blog (index + `{slug}`), volunteer/contact/subscribe forms.
  - **Admin** (`admin` prefix, `admin.` name): login/logout under `guest`; everything else under `['auth','admin']` — dashboard, donations (index/show/updateStatus), volunteers, messages, settings, gallery items, and `Route::resource(...)->except(['show'])` for cows/campaigns/events/blog/testimonials/team/donation-categories/gallery/faqs.
- `console.php` — stock `inspire` Artisan command only.

## Data flow
`bootstrap/app.php` registers `web.php`. A request matches a route → middleware (`guest` or `auth`+`admin`) → controller. Route-model binding resolves `{donation}`, `{slug}`, etc. before the controller runs.

## Dependencies
- Depends on: `app/Http/Controllers/*`, `app/Http/Middleware/EnsureUserIsAdmin.php` (the `admin` alias, registered in `bootstrap/app.php`).
- Depended on by: every view/redirect that calls `route('name')`.

## Conventions
- Kebab-case URIs, dot-named routes. Controllers referenced as `[Controller::class, 'method']`; same-named controllers aliased with `use ... as`. Group with `prefix()->name()->group()`. See `docs/PATTERNS.md` §1.

## Common commands
- `php artisan route:list` — full route table. `php artisan route:cache` — cache (prod only; clear with `route:clear`).
