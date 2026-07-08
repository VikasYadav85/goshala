# Current Tasks

## In Progress
(none)

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
