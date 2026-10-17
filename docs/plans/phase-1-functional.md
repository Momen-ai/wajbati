# Phase 1 Implementation Plan: Functional Bugs & Workflow

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development or superpowers:executing-plans.

**Goal:** Resolve all Phase 1 functional bugs, route integrity, ratings logic, admin bypass, and notifications (F-10 through F-20).

---

### Stream S1-A: Admin & Routing Integrity (F-10, F-11, F-13, F-25 Admin Parts)
**Owns:** `routes/admin.php`, `routes/web.php` (chef resource restrictions), `app/Http/Controllers/Dashboard/*`, `app/Http/Controllers/Front/ContactController.php`, `app/Providers/AppServiceProvider.php` (Paginator bootstrap five), admin views, newsletter subscriber table.

- [ ] **Step 1: Write RouteIntegrityTest and PaginationTest (RED)**
  - Assert that all registered routes point to existing controller methods.
  - Assert contacts store name/email and guest form persistence.
- [ ] **Step 2: Restrict resource routes and add route methods**
  - Restrict `Route::resource('meals')` in chef routes to `except(['show'])`.
  - In `routes/admin.php`, restrict category, ratings, contacts methods and remove dead cart/favourites resources.
- [ ] **Step 3: Fix pagination links in admin views**
  - Add `Paginator::useBootstrapFive()` in `AppServiceProvider::boot()`.
  - Add `{{ $items->withQueryString()->links() }}` to all paginated admin index views.
- [ ] **Step 4: Contact form & newsletter table**
  - Migration for `contacts` name/email/phone.
  - Migration for `newsletter_subscribers`.

---

### Stream S1-B: Storefront Rules (F-12, F-15, F-20)
**Owns:** `app/Http/Controllers/Front/MealController.php`, `app/Policies/RatingPolicy.php`, `app/Policies/OrderPolicy.php`, `app/Models/Rating.php`, `database/migrations/2026_10_19_200100_*.php`.

- [ ] **Step 1: Write failing tests for ratings, chef ordering own meal, and meal availability (RED)**
- [ ] **Step 2: Migration for Unique (user_id, meal_id) on ratings, and availability/number_persons on meals**
- [ ] **Step 3: Implement RatingPolicy and updateOrCreate rating logic**
- [ ] **Step 4: Restrict ordering own meals or by chefs/admins**

---

### Stream S1-C: Auth & Profile (F-14, F-16, F-17)
**Owns:** `app/Http/Requests/ProfileUpdateRequest.php`, `app/Http/Controllers/ProfileController.php`, `app/Models/User.php` (`MustVerifyEmail`), rate limiters in `AppServiceProvider`.

- [ ] **Step 1: Write failing tests for duplicate phone 422, rate limiting (429), and email verification check**
- [ ] **Step 2: Implement MustVerifyEmail on User model and verified middleware**
- [ ] **Step 3: Implement rate limiters in AppServiceProvider**
- [ ] **Step 4: Unify ProfileUpdateRequest and address validation**

---

### Stream S1-D: Order Workflow & Notifications (F-18, F-19)
**Owns:** `app/Notifications/*`, `app/Http/Controllers/Chef/OrderController.php`, `app/Http/Controllers/Dashboard/OrderController.php`, `app/Models/OrderStatusHistory.php`, migrations for `order_status_histories`.

- [ ] **Step 1: Write tests for queued notifications and order status history logging**
- [ ] **Step 2: Make notifications implement `ShouldQueue` with `afterCommit()`**
- [ ] **Step 3: Enforce state machine on Dashboard OrderController with forced flag & history table**
