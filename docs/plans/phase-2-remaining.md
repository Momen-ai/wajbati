# Phase 2 Remaining Implementation Plan

> **Standard:** TDD (RED first), Superpowers skills, verification-before-completion.

**Goal:** Complete all remaining items in Phase 2 according to `docs/plans/wajbati-continue-prompt.md`.

---

### Step 1: F-05 Multi-Chef Cart & Checkout UI (Money-Critical)
**Owns:** `app/Services/OrderService.php`, `resources/views/front/cart/index.blade.php`, `resources/views/front/orders/show.blade.php`, `resources/views/front/checkout/index.blade.php` (if exists), `tests/Feature/CommerceTest.php`.

- [x] **Step 1.1: RED - Add test for `OrderService::quote()` and Cart UI multi-chef breakdown**
  - Assert quote groups by chef with per-chef subtotal, delivery fee, and per-chef total.
  - Assert grand total matches sum of orders created.
  - Assert Cart view displays per-chef breakdown, fees, and no flat $3.00.
- [x] **Step 1.2: Implement `OrderService::quote(array $cart): Quote` (or structured array/DTO)**
  - Group cart items by chef.
  - Query DB live for current meal data (name, price, image, availability).
  - Calculate per-chef subtotal and delivery fee (`config('shop.delivery_fee')`).
  - Calculate grand total.
- [x] **Step 1.3: Update Cart & Order Blade views**
  - `front/cart/index.blade.php`: group items by chef, display per-chef subtotal and delivery fee, display grand total.
  - `front/orders/show.blade.php`: display stored `subtotal`, `delivery_fee`, `total`. Remove `$order->total - 3` and any hardcoded 3 / $3.00.
- [x] **Step 1.4: GREEN - Verify tests pass and run Pint**

---

### Step 2: Remaining Phase 0/1 Leftovers
- [x] **Step 2.1: F-08 Seeder Hardening**
  - Remove chef creation (`chef@gmail.com / 123456789`) from `database/seeders/MealSeeder.php`.
  - Move demo chef creation to `DemoSeeder.php` only, using `DEMO_PASSWORD` or secure random.
  - Add test: `DatabaseSeeder` does not create demo users in production.
- [x] **Step 2.2: F-11 Remove Dead Admin Cart & Favourites Resources**
  - Verify routes in `routes/admin.php` for `cart` and `favourites` are fully removed.
  - Remove dead controllers/views if lingering.
- [x] **Step 2.3: F-20 Missing Meal Fields UI & Checkout Validation**
  - Add `number_persons`, `is_available`, `prep_time_minutes` inputs to Chef meal create/edit forms (`front/chef/meals/*.blade.php`) and Admin meal forms (`admin/meals/*.blade.php`).
  - Update storefront to hide unavailable meals from index.
  - Reject checkout if any item in cart is `is_available = false`.
  - Add tests in `tests/Feature/CommerceTest.php` and `tests/Feature/MealAvailabilityTest.php`.
- [x] **Step 2.4: F-17 Follow-Up Migration Verification**
  - Verify existing users are marked as verified so nobody is locked out.
  - Add test ensuring existing users have `email_verified_at` set.
- [x] **Step 2.5: Red-team & Reviewer Audit**
  - Document Phase 0/1 red-team audit findings in `docs/redteam-phase01.md`.

---

### Step 3: F-21 / F-22 i18n, Currency, and RTL
- [ ] **Step 3.1: Views & Messages Localization Pass**
  - `resources/views/front/**`
  - `resources/views/admin/**`
  - `auth/**`, `profile/**`, `layouts/**`, flash messages, notifications, validation attributes.
- [ ] **Step 3.2: Currency Helper `money()` Adoption**
  - Replace all `${{ ... }}`, `$3.00`, and literal `$` with `{{ money(...) }}`.
- [ ] **Step 3.3: RTL & Logical Utilities**
  - Use `bootstrap.rtl` and logical utility classes (`ms-`, `me-`, `ps-`, `pe-`).
- [ ] **Step 3.4: Complete `lang/ar.json`, `lang/en.json`, `lang/ar/validation.php`**
- [ ] **Step 3.5: Write `tests/Feature/I18nCoverageTest.php`**
  - Fail if any `__('...')` key is missing in `ar.json` or `en.json`.
  - Fail if `${{` or `$[0-9]` literal remains in views.
- [ ] **Step 3.6: Impeccable UI Review Documentation (`docs/ui-review/`)**

---

### Step 4: F-23 Frontend Asset Bundling (Vite)
- [ ] **Step 4.1: Install & bundle assets locally via Vite**
  - Bootstrap 5 (+ RTL), Font Awesome, Chart.js.
  - Self-host Cairo font.
  - Replace any remaining CDN links with local bundled assets.
- [ ] **Step 4.2: Automated zero third-party asset test / check**

---

### Step 5: F-24 Performance Verification
- [ ] **Step 5.1: Portable monthly revenue series query verification**
- [ ] **Step 5.2: Popular meals logic verification**
- [ ] **Step 5.3: Cache invalidation test for dashboard stats**

---

### Step 6: Phase 2 Gate
- [ ] Full suite green on SQLite.
- [ ] `I18nCoverageTest` green.
- [ ] Zero third-party requests verified.
- [ ] All reports and plans updated.
