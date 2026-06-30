# app/Models/ — Eloquent models

## Purpose
The domain layer: 22 Eloquent models mapping to 21 domain tables. They define relationships, casts, query scopes, status constants, and a few accessors/booted hooks. This is the single source of truth for business entities.

## Key files
- `Donation.php` — relational hub. Status constants (`STATUS_PENDING…STATUS_REFUNDED`), payment fields, json `payment_meta` cast, auto `reference_no` in `booted()`. belongsTo DonationCategory/Campaign/Cow.
- `DonationCategory.php`, `Campaign.php`, `Cow.php` — donation targets; each hasMany Donation. `Campaign` has `progress_percentage` accessor; `Cow` hasMany CowSponsorship.
- `Campaign.php`/`Cow.php`/`*Category.php` — define `scopeActive()`, `scopeFeatured()`, `scopePublished()`.
- `User.php` — roles (`ROLE_*`), `isAdmin()`, `canManageContent()`; hasMany BlogPost.
- `BlogPost.php`, `Volunteer.php`, `ContactMessage.php` — own status constants.
- `GalleryAlbum.php`/`GalleryItem.php` — album→items (cascade); `embed_url` accessor for YouTube.
- `SiteSetting.php` — key/value store with static cached `get()`/`put()`/`flushCache()`.

## Data flow
Controllers and Actions read/write models; route-model binding resolves them from URL params. Relationships are eager-loaded with `->with([...])`. Cascade vs nullOnDelete is enforced at the migration FK level (donations use nullOnDelete to stay immutable).

## Dependencies
- Depends on: `database/migrations/` (schema).
- Depended on by: all controllers, `app/Services/`, `app/Actions/`, `app/Mail/`, seeders.

## Conventions
- Singular PascalCase names. Status as `const STATUS_*` on the model. `$fillable` + `$casts` declared. Scopes typed `scopeX(Builder $q): Builder`. See `docs/PATTERNS.md` §2.

## Common commands
- `php artisan make:model Foo -m` — model + migration.
- `php artisan tinker` — inspect models interactively.
