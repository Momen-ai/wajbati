# Phase 2 Implementation Plan: Platform Quality (i18n, UI, Assets, Performance)

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development or superpowers:executing-plans.

**Goal:** Execute Phase 2 findings (F-21 through F-25) covering asset bundling & dead code removal, query performance & indexing, code quality & request validation, and complete RTL / i18n / currency helper coverage.

---

### Stream S2-A: Assets & Layouts (F-23)
**Owns:** `public/assets/*`, `resources/views/layouts/*`, `tests/TestCase.php`, deletion of dead files (`routes.txt`, dead JS).

- [x] **Step 1: Delete dead JS & tracked junk**
  - Delete `public/assets/js/app.js`, `public/assets/js/lang.js`, `public/assets/js/image-check.js`, and `routes.txt`.
- [x] **Step 2: Create local SVG placeholder**
  - Create `public/assets/images/placeholder.svg`.
  - Replace all `placehold.co` occurrences across all Blade views with `asset('assets/images/placeholder.svg')`.
- [x] **Step 3: Add `withoutVite()` to test setup**
  - Ensure tests do not crash if Vite build assets are not present.

---

### Stream S2-B: Performance & Database Indexes (F-24)
**Owns:** `database/migrations/2026_10_20_200100_add_performance_indexes.php`, `app/Http/Controllers/Front/HomeController.php`, `app/Http/Controllers/Front/ChefController.php`, `app/Http/Controllers/Dashboard/*`, `app/Services/OrderStats.php`, `app/Providers/AppServiceProvider.php`.

- [x] **Step 1: Migration for composite & performance indexes**
  - `orders`: `(status)`, `(chef_id, status)`, `(payment_status, created_at)`.
  - `ratings`: `(meal_id)`.
  - `meals`: `(chef_id, is_available)`.
- [x] **Step 2: Fix N+1 and lazy loading queries in controllers**
  - `Front\HomeController`: use `withAvg('ratings', 'star')` and `withCount('ratings')`, define popular meals query cleanly.
  - `Front\ChefController`: use `withCount('meals')`.
- [x] **Step 3: Enable `Model::preventLazyLoading(! app()->isProduction())`**
  - In `AppServiceProvider::boot()`.
- [x] **Step 4: Cache dashboard aggregates**
  - Cache summary statistics for 5 minutes (`Cache::remember`), invalidate on order status transitions.

---

### Stream S2-D: Code Quality & Search Validation (F-25)
**Owns:** `app/Http/Requests/MealIndexRequest.php`, `app/Http/Controllers/Front/MealController.php`, `app/Http/Controllers/Dashboard/UsersController.php`, `pint.json`.

- [x] **Step 1: Remove dead `Front\MealController::home()` method**
- [x] **Step 2: Create `MealIndexRequest` and sanitize LIKE search query**
  - Validate `min_price`, `max_price`, `sort`, `category`, `chef`.
  - Escape `%` and `_` wildcards in search queries.
- [x] **Step 3: Strengthen `Dashboard\UsersController::update` validation**
  - `name` required, password uses `Rules\Password::defaults()`.
- [x] **Step 4: Commit `pint.json` configuration**

---

### Stream S2-C: i18n, Currency Helper & RTL (F-21, F-22)
**Owns:** `app/helpers.php`, `composer.json`, `config/app.php`, `config/shop.php`, `lang/ar.json`, `lang/en.json`, `lang/ar/validation.php`, `app/Http/Middleware/SetLocale.php`, Blade views.

- [x] **Step 1: Create global `money($amount)` helper**
  - Format amount using currency symbol from config (`shop.currency_symbol` / `shop.currency`).
  - Autoload `app/helpers.php` via `composer.json`.
- [x] **Step 2: Configure default locale and timezone in `config/app.php`**
  - `APP_TIMEZONE` = `Asia/Gaza`, `APP_LOCALE` = `ar`, `APP_FALLBACK_LOCALE` = `en`.
  - `SetLocale` middleware sets `Carbon::setLocale`.
- [x] **Step 3: Replace hardcoded `$` in all views with `{{ money(...) }}`**
- [x] **Step 4: Audit and populate `lang/ar.json` and `lang/en.json`**
  - Add missing translation keys and `lang/ar/validation.php`.
- [x] **Step 5: Write `I18nCoverageTest.php`**
  - Verify key translations exist and money helper operates correctly.
