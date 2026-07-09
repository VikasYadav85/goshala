# Changelog
All notable changes to this project are documented here.
Format: [DATE] [AUTHOR] Description

## [Unreleased]

### Added
- Mail: notification emails on public form submissions, all using a shared branded email layout
  (logo header + footer): contact → admin notification + sender acknowledgement, new newsletter
  subscriber → welcome, volunteer → admin notification. Admin recipient via `ADMIN_NOTIFY_EMAIL`
  (defaults to the From address). Failures are logged, never break the form. Covered by tests.
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
(none)

### Removed
(none)
