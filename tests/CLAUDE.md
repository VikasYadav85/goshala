# tests/ — Automated tests

## Purpose
PHPUnit test suite. **Currently scaffold-only** — no real coverage exists yet. This is the place to add Feature tests for the donation flow, admin auth, and admin CRUD as the project hardens.

## Key files
- `TestCase.php` — base test case (default empty scaffold; extend for app tests).
- `Feature/ExampleTest.php` — default stub (`GET /` returns 200). Replace with real feature tests.
- `Unit/ExampleTest.php` — default stub.
- `../phpunit.xml` — suites `Unit` (`tests/Unit`) + `Feature` (`tests/Feature`); runs on in-memory SQLite (`DB_DATABASE=:memory:`, `MAIL_MAILER=array`, `QUEUE_CONNECTION=sync`).

## Data flow
`composer test` runs `php artisan config:clear` then `php artisan test`. Tests boot the framework, hit routes via `$this->get/post(...)`, and assert on response/DB. Feature tests should use `RefreshDatabase`.

## Dependencies
- Depends on: `app/` (code under test), `database/migrations/` + `factories/UserFactory.php`, `phpunit.xml`.
- Depended on by: CI (none yet) and the `/test`, `/test-api`, `/test-business`, `/test-security` slash commands.

## Conventions
- PHPUnit, **not Pest**. Class names `*Test`, methods `test_snake_case()` or `#[Test]`. Feature tests in `Feature/`, pure-logic in `Unit/`. Priorities to cover first: payment status transitions, `EnsureUserIsAdmin` role gate, receipt idempotency, donation `reference_no` generation.

## Common commands
- `composer test` — full suite.
- `php artisan test --filter=DonationTest` — single test.
- `php artisan test tests/Feature/Foo.php` — single file.
