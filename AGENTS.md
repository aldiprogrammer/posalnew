# AGENTS.md

## Stack

- **Laravel 13** (PHP 8.3+), Tailwind CSS 4, Vite 8, SQLite (default DB)
- Dev tooling: Pint (linting/formatting), PHPUnit 12, Pail (log tailing)

## Commands

```bash
composer setup          # install + .env + key:generate + migrate + npm install + build
composer dev            # full dev server (php artisan dev — runs queue, cache listener, vite)
composer test           # clears config cache then runs php artisan test

php artisan test --filter=TestName   # run a single test class
php artisan test --filter=test_method # run a single test method
./vendor/bin/pint       # auto-fix code style (Laravel Pint)
./vendor/bin/phpunit    # raw PHPUnit (tests use sqlite :memory: by default)
```

## Conventions

- **DB**: SQLite in-memory for tests (`phpunit.xml` overrides), `database/database.sqlite` for local dev
- **Tests**: `tests/Feature/` and `tests/Unit/` — base `TestCase` at `tests/TestCase.php`
- **Frontend**: Vite entry points are `resources/css/app.css` and `resources/js/app.js`
- **Fonts**: Loaded via `laravel-vite-plugin/fonts` (Bunny CDN, Instrument Sans)
- **Code style**: 4-space indent, LF line endings (`.editorconfig`)
