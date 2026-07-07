# AutoDeals — Agent Guide

## One-liner

Car dealership inventory management system. Laravel 13 + Breeze (Blade/Alpine) + Tailwind v3 + PestPHP.

## Project status

Only Breeze auth scaffolding exists. **No car/inquiry domain code has been built yet.** `BRIEF.md` has the real requirements; `ERD.md` is a stale template (courts/cities — ignore it).

## Quickstart

```bash
# First-time setup (copies .env, generates key, migrates, builds assets):
composer setup

# Daily dev (server + queue + logs + Vite via concurrently):
composer dev

# Run all tests:
composer test

# Run a single test file:
php artisan test tests/Feature/SomeTest.php
```

## Testing

- **PestPHP** (not raw PHPUnit). Feature tests use `RefreshDatabase`.
- Tests run against SQLite `:memory:` — no external DB needed.
- Example test patterns in `tests/Feature/ExampleTest.php` and `tests/Unit/ExampleTest.php`.

## Key config quirks

| Concern | Setting |
|---|---|
| `.npmrc` | `ignore-scripts=true` — npm install skips postinstall scripts. Use `--ignore-scripts=false` if you need them. |
| Queue driver | `database` (not `sync`) in `.env` — run `php artisan queue:work` for async jobs |
| Cache driver | `database` in `.env` |
| Session driver | `database` in `.env` |
| Dev server | `composer dev` spawns 4 processes via `concurrently`: artisan serve, queue:listen, pail (logs), Vite |
| Formatter | Laravel Pint (`./vendor/bin/pint`) |
| Test env | Config in `phpunit.xml`: SQLite `:memory:`, array cache, sync queue |

## Domain model (to build)

Per `BRIEF.md`:
- **Car** (stock code, brand, model, year, price, km, color, transmission, fuel, engine cc, plate, condition, VIN, description, photos, status, inquiry)
- **Inquiry** (buyer name, phone, email, date, offer price, status, notes)
- **CarStatus**: Available / Reserved / Sold
- **CarCondition**: New / Excellent / Good / Fair / Poor
- **Transmission**: Manual / Automatic / CVT
- **FuelType**: Bensin / Diesel / Hybrid / Electric
- **InquiryStatus**: Pending / Test Drive / Approved / Rejected

## Conventions

- **Blade + Alpine.js** for views (Breeze stack). No Vue/React.
- **Tailwind v3** with `@tailwindcss/forms` plugin. Font: Figtree.
- No Laravel Boost installed — skip `composer require laravel/boost`.
- Editorconfig: 4 spaces, LF line endings.
- Namespaces: `App\Models`, `App\Http\Controllers`, `Database\Factories`, `Database\Seeders`.
