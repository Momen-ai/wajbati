# CONTINUATION — Wajbati mission (resume from verified state)

-An independent audit of your current code was done (suite run: **90/90 tests pass**). Phases 0 and 1 are done. Phase 2 is ~40% done. **Do not redo finished work.** Finish what is listed below, in this order, using the same rules as the master prompt (`wajbati-antigravity-prompt.md`): TDD (RED first), Superpowers skills, Impeccable for UI, sub-agents with disjoint file ownership (max 4), independent reviewer per stream, `verification-before-completion` (quote real command output), migrations only as new files, SQLite + MySQL compatible, everything via `__()`.

## Verified state (do not re-verify, just trust and move on)
- DONE: F-01, F-02, F-03, F-04, F-06, F-07, F-09, F-10, F-12…F-19, F-24 (mostly), F-25 (mostly).
- PARTIAL: F-05 (UI), F-08 (MealSeeder), F-11 (two lists), F-20, F-21, F-22, F-23.
- NOT STARTED: Phase 3 (F-26), Phase 4 (F-27, F-28), Phase 5.

## STEP 0 — Housekeeping (first, ~30 min, no sub-agents)
1. Create `docs/REFACTOR_REPORT.md`: one row per F-01…F-28 → Status (Fixed / Partial / Not started), files changed, migration names, tests added, evidence (command + result). Update it after EVERY step below.
2. Tick the checkboxes in `docs/plans/phase-0-blockers.md`, `phase-1-functional.md`, `phase-2-platform.md` to match reality (currently 0 of 45 are ticked). Update them as you go.
3. `docs/phases_overview.txt` and `docs/phase2_findings.txt` are copies of the master prompt, not plans: move them to `docs/reference/` and write the remaining steps below into `docs/plans/phase-2-remaining.md` using `writing-plans`.
4. Commit: `docs: report + plans in sync with code`.

## STEP 1 — F-05 (UI still wrong; money-critical)
Problem: `OrderService` charges the delivery fee **per chef**, but `front/cart/index.blade.php` still shows a flat `$3.00` and `$total + 3`, and `front/orders/show.blade.php:84` computes `$order->total - 3`. The customer can see a lower total than the amount charged.
Do:
- Add `OrderService::quote(array $cart): Quote` (groups by chef; per-chef subtotal, fee, total; grand total). Cart page AND checkout both use it — single source of truth.
- Cart view: items grouped by chef, per-chef subtotal + fee line, grand total = sum of what will be created. Read name/price/image live from DB (no stale data).
- Order pages: use stored `subtotal`, `delivery_fee`, `total` columns; remove `total - 3` and every literal `3` / `$3.00`.
- Test (Pest): 2-chef cart → quote total == sum of created orders' totals == total shown in the cart HTML; single-chef; price changed after add-to-cart.
- Stream: S2-E (owns cart + order views, `OrderService::quote`).

## STEP 2 — Remaining Phase 0/1 leftovers (parallel, small)
- **F-08:** `database/seeders/MealSeeder.php` still creates `chef@gmail.com / 123456789`. Move demo chef creation into `DemoSeeder` only, password from `DEMO_PASSWORD` env or random printed once; `MealSeeder` must not create users. Test: `DatabaseSeeder` in production never creates demo users.
- **F-11:** `admin/cart/index` and `admin/favourites/index` still paginate without `links()`. Per F-25 these two admin CRUDs should be REMOVED (routes, controllers, views, sidebar links) because the cart is session-based; if favourites will be built in Phase 5, keep only the table/model. Verify `routes/admin.php` and delete what remains.
- **F-20:** `Chef\MealController` validates `number_persons`, `is_available`, `prep_time_minutes`, but the chef create/edit forms (and admin meal forms) have no inputs for them. Add the inputs (translated), show persons/prep time on meal cards, hide unavailable meals on the storefront, and make checkout reject unavailable meals. Tests for each.
- **F-17 follow-up:** confirm the migration marks EXISTING users as verified (so nobody is locked out) — add test.
- Red-team + reviewer evidence was never produced for Phases 0/1: spawn the red-team sub-agent now (scope in master prompt rule 9, incl. checkout double-submit/race → add one-time checkout token if reproducible) and save its findings to `docs/redteam-phase01.md`.

## STEP 3 — F-21 / F-22 i18n, currency, RTL (largest remaining item)
Current numbers (measured): 165 keys used in code, only 59 in `ar.json` → **138 missing** (146 missing in `en.json`); admin views: 27 of 28 files have no `__()`; front: 14 of 16 files none (cart, orders, meal show, chef dashboard = 0); 27 hardcoded English flash messages in controllers; 21 places still render `${{ … }}`; `money()` used only twice; `.env.example` still `APP_LOCALE=en`; no `lang/ar/validation.php`.
Do (3 parallel sub-agents by directory, keys to `lang/_pending/<stream>.json`, orchestrator merges + sorts):
1. `resources/views/front/**`
2. `resources/views/admin/**`
3. `auth/**`, `profile/**`, `layouts/**`, controller/service flash messages, notifications (mail + database text localized via `$notifiable->locale`), validation attribute names, `lang/ar/validation.php`.
- Replace EVERY `${{ … }}`, `$3.00`, `… $` with `money()`. Set `.env.example`: `APP_LOCALE=ar`, `APP_FALLBACK_LOCALE=en`, `APP_TIMEZONE=Asia/Gaza`, `SHOP_*`.
- RTL: logical utilities (`ms-/me-/ps-/pe-`), `bootstrap.rtl` build, mirrored icons, admin dashboard RTL.
- Write `tests/Feature/I18nCoverageTest.php` (planned but missing): scan `resources/views` + `app` for `__('…')` keys (and `trans`, `@lang`); fail if any key is missing in `ar.json` or `en.json`; fail if `lang/_pending` is non-empty; assert `money()` formatting; assert no `${{` / `$[0-9]` literal remains in views.
- Impeccable (before → after, saved to `docs/ui-review/`): `audit`, `critique`, `harden`, `clarify` (natural Arabic copy), `adapt` (mobile first), `polish`, on: home, meals index/show, cart/checkout, customer orders/show, chef dashboard/orders/meal form, admin dashboard/orders, login/register/profile — AR RTL and EN LTR.

## STEP 4 — F-23 assets (run before the Impeccable polish of Step 3 finishes; S2-A)
Measured: 87 references to CDNs / `placehold.co` in views; `package.json` has no Bootstrap, jQuery, Chart.js or Font Awesome.
Do: install and bundle via Vite — Bootstrap 5 (+RTL), Font Awesome (or inline SVG), Chart.js (pinned), jQuery only if still needed (prefer `fetch`); self-host the Cairo font; replace every remaining `placehold.co` with `assets/images/placeholder.svg`; ONE CSS system (decide Tailwind vs Bootstrap, record in `DECISIONS.md`); `npm run build` must succeed from a clean clone.
Test/gate: a test (or script) that fails if any Blade layout/view contains `http://` or `https://` to a third-party host (allow `w3.org`/schema URLs), and a headless check that the home page makes zero third-party requests.

## STEP 5 — F-24 verification (S2-B, small)
Confirm and test: monthly revenue series uses ONE grouped query (portable SQLite/MySQL), "popular meals" = most completed orders in last 30 days with rating fallback, dashboard cache is invalidated on order status change (test), indexes migration applies on MySQL. Fix anything missing.

## STEP 6 — Phase 2 gate
All must hold before Phase 3: suite green · `I18nCoverageTest` green · zero third-party requests · F-05 test green · `REFACTOR_REPORT.md` + plans up to date · every Phase-2 stream has a reviewer sub-agent verdict recorded. Open the Phase 2 PR (never push to main, never force-push).

## STEP 7 — Phase 3 (F-26): tests & CI
- S3-A: convert tests to Pest consistently (OrderStatusTest provider already fixed — keep), `withoutVite()` in `TestCase`.
- S3-C: **authorization matrix test** (guest/user/chef/admin × every protected route), route-integrity (exists), i18n test (Step 3).
- S3-D (up to 3 sub-agents: commerce / accounts / content): add tests until **≥ 80% line coverage on `app/`** (`php artisan test --coverage --min=80`, needs pcov/xdebug; if unavailable, report that and use a coverage-by-file checklist).
- S3-B: `.github/workflows/ci.yml` — PHP 8.3 (+8.2), MySQL 8 job AND SQLite job, `composer install`, `npm ci && npm run build`, `vendor/bin/pint --test`, `php artisan test --parallel`, `composer audit`, `npm audit --audit-level=high`; add Larastan level 6.
- Run the whole suite on MySQL 8 at least once (migrations with SoftDeletes/FK changes have only been proven on SQLite).

## STEP 8 — Phase 4 (F-27, F-28)
- S4-A docs: rewrite `README.md` (still says Laravel 11, MySQL vs sqlite drift, no `key:generate` / `storage:link` / `npm run build` / queue worker, claims "full AR/EN" and favourites): accurate stack, setup, env table, roles matrix, Mermaid order-status + payment lifecycle, deployment checklist, nginx/apache rule denying script execution under `/storage`. `.env.example`: `APP_NAME=Wajbati`, `APP_DEBUG=false`, MySQL block, `ADMIN_EMAIL/ADMIN_PASSWORD`, `REQUIRE_EMAIL_VERIFICATION`, `SHOP_*`, `SENTRY_LARAVEL_DSN`, `LOG_STACK=daily`. ER diagram in `docs/`.
- S4-B hardening: `SecurityHeaders` middleware (CSP report-only first, HSTS in prod), trust proxies, `URL::forceScheme('https')` in production, localized 403/419/429/503 pages (404/500 exist), Sentry only when DSN set, optional Dockerfile/compose.

## STEP 9 — Phase 5 (one stream per feature, max 4 at once, each with tests + translations + Impeccable review)
1. Customer favourites (meals + chefs) · 2. Chef application + admin approval · 3. Customer cancel (pending), reorder, timeline, rate-after-completion · 4. Chef tools (capacity, earnings report, CSV, WhatsApp link) · 5. Admin tools (search/filter/export, moderation, audit log) · 6. Payment gateway wiring behind `PaymentGateway` (only if owner provides provider) · 7. Legal pages, SEO, accessibility.

## Reporting rules (violated so far — fix now)
- `docs/REFACTOR_REPORT.md` updated after every step; plan checkboxes ticked; reviewer verdicts and red-team output saved under `docs/`.
- After each step: `vendor/bin/pint`, `php artisan test` — paste the final lines (`Tests: N passed`) into the report.
- Final message must list: what is done, what is partial, what needs owner decisions (payment provider, SMTP, hosting, legal texts).