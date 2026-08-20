# tests/ — Automated tests

## Purpose
PHPUnit test suite covering RBAC, admin redirects, form validation/accessibility, mail flows, SEO, image optimization, and YouTube URL validation.

## Key files
- `TestCase.php` — base test case (default empty scaffold; extend for app tests).
- `Feature/FormValidationTest.php` — required-field validation for all public and admin create forms.
- `Feature/FormAccessibilityTest.php` — visible-control label contract plus permission-form render regression.
- `Feature/AdminLoginRedirectTest.php` — admin login and stale intended-URL regressions.
- `Feature/AccessControlTest.php`, `SecretaryPermissionTest.php` — RBAC and permission behavior.
- `Unit/OptimizedImageStorageTest.php`, `YouTubeUrlTest.php` — media behavior.
- `../phpunit.xml` — suites `Unit` (`tests/Unit`) + `Feature` (`tests/Feature`); runs on in-memory SQLite (`DB_DATABASE=:memory:`, `MAIL_MAILER=array`, `QUEUE_CONNECTION=sync`).

## Data flow
`composer test` runs `php artisan config:clear` then `php artisan test`. Tests boot the framework, hit routes via `$this->get/post(...)`, and assert on response/DB. Feature tests should use `RefreshDatabase`.

## Dependencies
- Depends on: `app/` (code under test), `database/migrations/` + `factories/UserFactory.php`, `phpunit.xml`.
- Depended on by: CI (none yet) and the `/test`, `/test-api`, `/test-business`, `/test-security` slash commands.

## Conventions
- PHPUnit, **not Pest**. Class names `*Test`, methods `test_snake_case()` or PHPUnit attributes. Feature tests use `RefreshDatabase`; data sets use `#[DataProvider]`. Add regressions for every production or QA-found bug.

## Common commands
- `composer test` — full suite.
- `php artisan test --filter=DonationTest` — single test.
- `php artisan test tests/Feature/Foo.php` — single file.
