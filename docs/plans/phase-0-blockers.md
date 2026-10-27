# Phase 0 Implementation Plan: Blockers (Security, Money, Data Loss)

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development or superpowers:executing-plans.

**Goal:** Resolve all Phase 0 security, financial, and data integrity blockers (F-01 through F-09).  
**Architecture:** 
1. Stream S0-A: Centralize and harden image validation and upload handling through `HandlesImageUploads`, add `images:audit` artisan command.
2. Stream S0-C: Secure user accounts, soft deletes, foreign key cascade protections, seeders hardening, and protect against mass-assignment privilege escalation.
3. Stream S0-B: Overhaul checkout, shopping cart validation, live pricing recalculations, multi-chef delivery fees, phone preservation, and order payment lifecycles.

---

### Stream S0-A: Uploads Hardening (F-01)
**Owns:** `app/Traits/HandlesImageUploads.php`, `app/Console/Commands/ImagesAudit.php`, `app/Http/Controllers/ProfileController.php` (image update), `app/Http/Controllers/Chef/MealController.php`, `app/Http/Controllers/Dashboard/MealController.php`, `tests/Feature/UploadSecurityTest.php`.

- [x] **Step 1: Write failing upload security test (RED)**
  - Test scenario: attempt uploading a `.php` script or disguised executable as an avatar or meal image. Expect rejection (HTTP 422).
  - Verify test fails.
- [x] **Step 2: Implement `HandlesImageUploads` validation and filename sanitization**
  - Enforce `image|mimes:jpeg,png,jpg,webp|max:2048` and verify binary image headers with `getimagesize()`.
  - Enforce UUID generation for saved filenames.
- [x] **Step 3: Implement `images:audit` Artisan Command**
  - Create command `app/Console/Commands/ImagesAudit.php` with `--fix` option to detect and scrub non-whitelisted storage files.
- [x] **Step 4: Verify test passes (GREEN) and run pint**

---

### Stream S0-C: Accounts & Data Integrity (F-07, F-08, F-09, F-01 Admin Part)
**Owns:** `app/Models/User.php`, `app/Models/Meal.php`, `app/Models/Order.php`, `app/Http/Controllers/Dashboard/UsersController.php`, `app/Http/Controllers/ProfileController.php` (destroy), database migrations for soft deletes and FKs (`2026_10_18_300100_*`), `database/seeders/*`, `tests/Feature/AccountSecurityTest.php`.

- [x] **Step 1: Write failing account security test (RED)**
  - Test scenarios: Mass assignment with `User::create(['role' => 'admin', ...])` must not set admin; last admin cannot be deleted or demoted; meal deletion does not cascade-wipe order items.
  - Verify tests fail.
- [x] **Step 2: Migration for SoftDeletes & Foreign Key restrictions**
  - Add `softDeletes()` to `users`, `meals`, `orders`. Modify FK constraints on `order_items.meal_id` to `nullOnDelete()`.
- [x] **Step 3: Remove `role` from `$fillable` in `User.php`**
  - Explicitly set `$user->role` in `Dashboard\UsersController` with last-admin guard.
- [x] **Step 4: Update DatabaseSeeder and DemoSeeder**
  - Remove hardcoded credentials from seeders; use `ADMIN_EMAIL` and `ADMIN_PASSWORD` from environment. *(Note: MealSeeder cleanup tracked in Step 2 / F-08)*
- [x] **Step 5: Verify test passes (GREEN) and run pint**

---

### Stream S0-B: Commerce Core (F-02, F-03, F-04, F-05, F-06)
**Owns:** `app/Http/Controllers/Front/CartController.php`, `app/Http/Controllers/Front/OrderController.php`, `app/Services/CartService.php`, `app/Services/OrderService.php`, `app/Services/OrderPaymentService.php`, `app/Services/OrderStats.php`, `app/Contracts/PaymentGateway.php`, `config/shop.php`, `app/Models/OrderItem.php`, `tests/Feature/CommerceTest.php`, migrations `2026_10_18_200100_*`.

- [x] **Step 1: Write failing commerce tests (RED)**
  - Negative quantity cart rejection, live price re-fetch on checkout, phone column saved on order, multi-chef delivery fees calculation, COD payment status lifecycle.
  - Verify tests fail.
- [x] **Step 2: Migrations for Orders & Order Items**
  - Add `phone`, `subtotal`, `delivery_fee` to `orders`.
  - Add `meal_name` to `order_items`.
- [x] **Step 3: Implement `CartService` & Form Requests**
  - Validate quantities strictly (1..`shop.max_qty_per_item`).
- [x] **Step 4: Implement `OrderService`, `OrderPaymentService`, and `OrderStats`**
  - Centralize order creation, payment status transitions, and revenue metrics. *(Note: F-05 UI breakdown tracked in Step 1)*
- [x] **Step 5: Verify tests pass (GREEN) and run pint**
