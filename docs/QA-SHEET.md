# QA Test Sheet
Generated: 2026-08-14
Scope: Final client-delivery responsive, form, authentication, and interaction QA.
Automated Coverage: 54 PHPUnit tests / 176 assertions cover required fields, control-label contracts, admin redirects, RBAC, mail, SEO, media optimization, and URL validation. Browser matrix covered 236 page-width combinations before interaction checks.

## Critical User Flows (Priority 1 — Test First)
| # | Flow | Steps | Expected Result | Status | Tester |
|---|------|-------|-----------------|--------|--------|
| 1 | Admin login | Open `/admin/login`, sign in | Redirects to `/admin`; no frontend redirect | ✅ Local | Codex |
| 2 | Donation checkout | Open donation page and checkout at four widths | Form fits viewport; labels and validation work | ✅ Local | Codex |
| 3 | Volunteer registration | Open form at four widths; submit empty in automated test | Responsive form; required fields rejected | ✅ Local | Codex |
| 4 | Contact form | Open form at four widths; submit empty in automated test | Responsive form; required fields rejected | ✅ Local | Codex |
| 5 | Admin CRUD forms | Open all create/edit pages at four widths | No 500, overflow, broken image, or unnamed control | ✅ Local | Codex |

## Feature-Specific Tests (Priority 2)
| # | Feature | Steps | Expected Result | Status | Tester |
|---|---------|-------|-----------------|--------|--------|
| 1 | Admin mobile sidebar | Toggle twice at 320px | Closed → open → closed | ✅ Local | Codex |
| 2 | Public mobile menu | Toggle twice at 320px | Closed → open → closed | ✅ Local | Codex |
| 3 | FAQ accordion | Open and close first FAQ | Answer toggles correctly | ✅ Local | Codex |
| 4 | Admin tables | Open all list pages at 320/390px | Table content horizontally scrollable | ✅ Local | Codex |
| 5 | Permission form | Open create and edit | HTTP 200; literal `@can` displays as text | ✅ Local | Codex |

## Cross-Device Tests (Priority 3)
| # | Page/Flow | Device | Check | Status | Tester |
|---|-----------|--------|-------|--------|--------|
| 1 | All public routes | 320, 390, 768, 1440px | Overflow, images, forms, accessible names | ✅ Local | Codex |
| 2 | All admin routes | 320, 390, 768, 1440px | Overflow, tables, images, forms, errors | ✅ Local | Codex |
| 3 | Real detail/edit routes | 320, 390, 768, 1440px | Long IDs, cards, form controls | ✅ Local | Codex |

## Edge Cases (Priority 4)
| # | Scenario | Steps | Expected Result | Status | Tester |
|---|----------|-------|-----------------|--------|--------|
| 1 | Stale intended URL | Set intended URL to homepage, then admin login | Redirect remains `/admin` | ✅ Automated | Codex |
| 2 | Missing required values | POST every public/admin create form empty | Validation errors; no record created | ✅ Automated | Codex |
| 3 | Alpine CDN unavailable | Load app with no CDN Alpine | Bundled controls remain functional | ✅ Local | Codex |

## Bug Report Section
| # | Summary | Steps to Reproduce | Expected | Actual | Severity | Screenshot |
|---|---------|-------------------|----------|--------|----------|------------|
| | | | | | | |
