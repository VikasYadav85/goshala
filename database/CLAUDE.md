# database/ — Schema, seeders, factories

## Purpose
Defines and populates the database. 24 migrations (3 framework + 21 domain tables), 13 seeders with realistic demo content, and a single factory. Local dev uses SQLite (`database.sqlite`); production uses MySQL.

## Key files
- `migrations/2025_01_01_000001_alter_users_add_admin_fields.php` — adds `role`, `is_active`, etc. to `users`.
- `migrations/2025_01_01_000009_create_donations_table.php` — the donations table (payment + donor fields, nullable FKs `nullOnDelete`).
- `migrations/2025_01_01_0000XX_*` — one per domain table, ordered by the hand-set timestamp prefix.
- `seeders/DatabaseSeeder.php` — orchestrates all seeders.
- `seeders/DonationCategorySeeder.php`, `CampaignSeeder.php`, etc. — demo content.
- `factories/UserFactory.php` — the only factory (used for tests/seeding users).
- `database.sqlite` — local dev DB (gitignored / `.claudeignore`).
- `gopal_seva_mysql.sql`, `export-mysql.php` — operational MySQL dump + export script (artifacts, possibly stale — see `docs/CODEBASE_AUDIT.md` §11).

## Data flow
`php artisan migrate` builds the schema; Eloquent models in `app/Models/` map to these tables. FK delete behavior: `cow_sponsorships`/`campaign_updates`/`gallery_items` cascade; all `donations` FKs `nullOnDelete` (financial records preserved).

## Dependencies
- Depends on: nothing (schema is the base layer).
- Depended on by: `app/Models/`, all seeders, tests.

## Conventions
- Domain migrations use ordered prefix `2025_01_01_0000XX_` + verb-first name. Columns `snake_case`; FKs `foreignId()->constrained()->nullOnDelete()`. Add a seeder when adding a content table.

## Common commands
- `php artisan migrate` / `migrate:fresh --seed` / `db:seed --class=FooSeeder`.
- ⚠️ On the live server, always `chown -R www-data:www-data storage bootstrap/cache` after migrating as root.
