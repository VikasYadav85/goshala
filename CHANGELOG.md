# Changelog
All notable changes to this project are documented here.
Format: [DATE] [AUTHOR] Description

## [Unreleased]

### Added
- SEO: dynamic `/sitemap.xml` (static pages + published blog posts, campaigns, events, gallery albums).
- SEO: dynamic `/robots.txt` route (domain-agnostic, exposes sitemap, disallows /admin) replacing the static file.
- SEO: canonical link, og:url/og:site_name/twitter:card meta, and JSON-LD `NGO` structured data in the public layout.
- Tests: `SeoTest` covering sitemap XML, robots.txt, and homepage canonical/structured-data.
- Initial Claude Code documentation layer (CLAUDE.md, docs/, tasks/, .claude/)

### Changed
(none)

### Fixed
(none)

### Removed
(none)
