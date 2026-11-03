# Mobile Redesign (<768px) Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Transform the mobile experience (<768px) of the entire Wajbati application across every route and layout, matching the 6 approved reference screens with bespoke mobile tokens, components, bottom tab bars, sticky bars, and bottom sheets, leaving Desktop (≥768px) strictly unchanged.

**Architecture:** Add mobile-first tokens in `resources/css/theme.css` wrapped in `@media (max-width: 767.98px)`. Build and refine mobile Blade components (`meal-card`, `category-circle`, `chip`, `segmented`, `switch`, `stepper`, `bottom-sheet`, `stat-card`, `status-badge`, `list-row`, `bottom-tab-bar`, and top-bar). Refactor templates to present high-density, native-feeling mobile views without touching backend controllers, migrations, or database queries.

**Tech Stack:** Laravel 11 Blade, Bootstrap 5.3 + Custom CSS, Chart.js, Vanilla JS with Spring-like motion, Phosphor/FontAwesome SVGs.

**Spec:** User prompt specifying mobile design language from 6 reference screens (Home, Meals, Filters bottom sheet, Meal detail, Cart, Chef dashboard), batch divisions (A, B, C, D), strict presentation-only scope, dark ink `#1b0e03` on orange fills, and zero desktop regressions.

---

## Global Constraints
- Viewports < 768px only (`@media (max-width: 767.98px)` / `d-md-none` / `d-none d-md-block`). **Desktop (≥ 768px) must not change.**
- Presentation layer only: no controller, route, migration, or business-logic changes.
- Every string localized via `__()` in `lang/ar.json` and `lang/en.json`.
- Prices formatted via `money()`.
- CSS logical properties only (`inset-inline`, `margin-inline`, `padding-inline`, `border-inline`, `start`, `end`) for flawless RTL and LTR.
- Text on orange fills = dark ink `#1b0e03` (WCAG AAA contrast against `#ff7a18`).
- Real input `font-size: 16px` minimum on mobile to prevent iOS Safari auto-zoom.
- Touch targets ≥ 44px with zero horizontal scroll at 360px, 390px, and 430px viewports.
- All 143 existing PHPUnit tests and Laravel Pint must remain 100% green before any completion.

## Review Focus
1. **Desktop regression:** Ensure no mobile styles bleed into viewports ≥ 768px.
2. **Form inputs iOS auto-zoom:** Verify all mobile inputs use `font-size: 16px` (or `1rem`).
3. **Sticky action bar padding conflict:** Verify `<main>` has sufficient bottom padding (`padding-bottom: calc(84px + env(safe-area-inset-bottom))` or action bar height) so bottom content is never hidden.
4. **Touch target accessibility:** Verify all interactive buttons, steppers, and chips have ≥ 44px hit areas.
5. **RTL and LTR symmetry:** Verify chevrons, margins, and icons mirror properly in Arabic and English.

---

## Task Decomposition by Batch

### Batch A: Tokens, Mobile Shell, Blade Components & The 6 Reference Screens

#### Task 1: Mobile Design Tokens & Shell Layout
**Files:**
- Modify: `resources/css/theme.css`
- Modify: `resources/views/layouts/frontend.blade.php`
- Modify: `resources/views/components/ui/bottom-tab-bar.blade.php`

- [ ] **Step 1: Add Mobile Design Tokens in `resources/css/theme.css`**
  - Define mobile-scoped CSS variables under `@media (max-width: 767.98px)`:
    - Surfaces: `--surface-0: #0a0a0a`, `--surface-1: #151515`, `--surface-2: #1c1c1c`, `--border-subtle: rgba(255,255,255,0.08)`
    - Primary ink: `--text-on-primary: #1b0e03`
    - Radii: `--radius-card: 20px`, `--radius-img: 15px`, `--radius-chip: 20px`, `--radius-sheet: 28px`
    - Typography & Inputs: inputs ≥ 16px font-size, line clamps, and touch targets.
- [ ] **Step 2: Update Mobile Shell Header & Bottom Tab Bar**
  - Refine top-bar on mobile in `frontend.blade.php`: logo mark (34px) + wordmark at start, notification and cart pill/circle buttons with dark-ink badges at end.
  - Refine `bottom-tab-bar.blade.php` to 84px height including `env(safe-area-inset-bottom)`, `rgba(14,14,14,0.82)` blur backdrop, 24px icon sizes, active tab filled icon with 2px stroke and bold orange label, `aria-current="page"`.
  - Add conditional check to hide tab bar on `meals.show`, `cart.index`, and replace with sticky action bar.
- [ ] **Step 3: Verification**
  - Run Pint and verify syntax. Check 390px viewport shell in headless browser.

---

#### Task 2: Core Mobile Blade Components
**Files:**
- Create/Modify: `resources/views/components/ui/meal-card.blade.php`
- Create/Modify: `resources/views/components/ui/category-circle.blade.php`
- Create/Modify: `resources/views/components/ui/bottom-sheet.blade.php`
- Create/Modify: `resources/views/components/ui/stat-card.blade.php`
- Create/Modify: `resources/views/components/ui/stepper.blade.php`
- Create/Modify: `resources/views/components/ui/status-badge.blade.php`

- [ ] **Step 1: Build `category-circle.blade.php`**
  - 62px circle avatar/icon container, 12px label, horizontal snap scroller with next item peeking. Selected state: 2px orange ring with gap. Hide categories with 0 meals.
- [ ] **Step 2: Update `meal-card.blade.php` for Mobile 2-Column Grid**
  - On mobile (<768px): 6px card padding, 104–112px image height (radius 15px), 32px translucent circular heart button (top inline-end), rating / prep-time chip (bottom inline-start), 1-line name with ellipsis, orange price, 34px round orange "+" button.
  - Retain existing desktop layout for ≥ 768px unchanged.
- [ ] **Step 3: Build/Refine `bottom-sheet.blade.php`**
  - 28px top radius, 40×5px drag handle, title, orange text "Clear all" button, circular close button, scrollable body, sticky primary action button, backdrop `rgba(0,0,0,0.62)`.
- [ ] **Step 4: Refine `stat-card.blade.php` & `stepper.blade.php`**
  - Mobile stat-card: 152px width, 32px soft-orange icon tile, 12px muted label, 24px/800 value, trend chip, horizontal scroll-snap.
  - Mobile stepper: `#1f1f1f` pill, − qty +, 30px touch buttons, minus turns into red trash icon at qty 1.
- [ ] **Step 5: Verification**
  - Verify component markup and ensure all existing component calls render seamlessly.

---

#### Task 3: Implement Reference Screen 1 — Home Page
**Files:**
- Modify: `resources/views/front/home.blade.php`

- [ ] **Step 1: Implement Mobile Layout for Home**
  - Top bar with greeting ("أهلاً بيك / شو بدك تاكل اليوم؟" / "Welcome back / What would you like to eat today?").
  - Rounded search bar + soft-orange filter icon button that opens the filter bottom sheet.
  - Horizontal snap row of `category-circle` components with "View all" link.
  - "Popular meals" section rendered as 2-column mobile meal card grid.
  - Keep canvas hero animation on mobile with enhanced mobile scrim so text is 100% legible.
- [ ] **Step 2: Verification**
  - Capture 390px screenshot of Home (AR & EN) and compare against reference screen 1.

---

#### Task 4: Implement Reference Screen 2 & 3 — Meals Page & Filters Bottom Sheet
**Files:**
- Modify: `resources/views/front/meals/index.blade.php`

- [ ] **Step 1: Implement Mobile Layout for Meals Index**
  - Large title ("الوجبات / Meals") with dish count badge ("18 وجبة").
  - Full-width search bar.
  - Horizontal chip row: "Filters" button with sliders icon first, followed by category filter pills.
  - 2-column grid of mobile compact meal cards.
- [ ] **Step 2: Implement Refined Filters Bottom Sheet (Ref Screen 3)**
  - Multi-select category chips with soft orange border + checkmark.
  - Max-price interactive range slider with live price indicator (`₪60` in orange).
  - Segmented control for sorting: [Newest / Top rated / Lowest price].
  - "Verified kitchens only" switch (`role="switch"`, 52×32px).
  - Sticky primary button: "Show X results" ("عرض 7 وجبة" / "Show results").
- [ ] **Step 3: Verification**
  - Capture 390px screenshot of Meals index and active Filters sheet (AR & EN). Compare with references 2 & 3.

---

#### Task 5: Implement Reference Screen 4 — Meal Detail Page
**Files:**
- Modify: `resources/views/front/meals/show.blade.php`

- [ ] **Step 1: Implement Mobile Layout for Meal Detail**
  - Hero image (~340px) with bottom gradient fade into page surface.
  - Floating circular buttons: back chevron (top start), favorite heart and share buttons (top end).
  - Overlapping card content: large title, price in orange, chef row card (avatar, kitchen name, rating, chevron).
  - Info chips (prep time, category, availability).
  - Description and options/size segmented control if present.
  - Sticky bottom action bar replacing tab bar: compact stepper + full-width primary button "Add to cart ₪XX" with dark ink text.
- [ ] **Step 2: Verification**
  - Capture 390px screenshot of Meal Detail (AR & EN). Compare with reference screen 4.

---

#### Task 6: Implement Reference Screen 5 — Cart Page
**Files:**
- Modify: `resources/views/front/cart/index.blade.php`

- [ ] **Step 1: Implement Mobile Layout for Cart**
  - Header: back chevron, large "Cart" title with item count badge ("4 وجبات").
  - Cart item list: rounded surface cards with 72px thumbnail, meal name, kitchen name, orange price, and compact stepper with trash icon at qty 1.
  - Swipe-to-delete visual indicator or tap-to-remove.
  - Order summary card: subtotal, delivery fee grouped by distinct kitchen, and bold total.
  - Payment method row: "Cash on delivery" with icon tile.
  - Sticky bottom bar: "Complete Order ₪158" ("إتمام الطلب") full-width button.
  - Polished empty state when cart is 0.
- [ ] **Step 2: Verification**
  - Capture 390px screenshot of Cart (AR & EN). Compare with reference screen 5.

---

#### Task 7: Implement Reference Screen 6 — Chef Dashboard
**Files:**
- Modify: `resources/views/front/chef/dashboard.blade.php`

- [ ] **Step 1: Implement Mobile Layout for Chef Dashboard**
  - Kitchen header with chef avatar, kitchen name, and kitchen status toggle (open/closed switch).
  - Large title ("لوحة المطبخ" / "Kitchen Dashboard").
  - Period segmented control: [Today / Week / Month] ("اليوم", "الأسبوع", "الشهر").
  - Horizontal scrolling row of stat-cards (Earnings, Orders, Pending).
  - Smooth revenue area chart (Chart.js with orange line, soft gradient fill, no legends, horizontal grid only).
  - "Recent orders" list-rows with status badges (pending, preparing, delivered).
  - Chef mobile bottom tab bar (Dashboard, Orders, Meals, Earnings, Account).
- [ ] **Step 2: Verification & Review Gate A**
  - Capture 390px screenshots of Chef Dashboard (AR & EN).
  - Run full test suite, Pint, and present Batch A screenshots to user for approval.

---

### Batch B: Remaining Storefront, Auth & Account Pages

#### Task 8: Remaining Storefront Pages (Chefs, Chef Detail, Orders, Favourites, Info Pages)
**Files:**
- Modify: `resources/views/front/chefs/index.blade.php`
- Modify: `resources/views/front/chefs/show.blade.php`
- Modify: `resources/views/front/orders/index.blade.php`
- Modify: `resources/views/front/orders/show.blade.php`
- Modify: `resources/views/front/favourites/index.blade.php`
- Modify: `resources/views/front/pages/contact.blade.php`
- Modify: `resources/views/front/pages/privacy.blade.php`
- Modify: `resources/views/front/pages/terms.blade.php`
- Modify: `resources/views/front/chef/apply.blade.php`

- [ ] **Step 1: Chefs Index & Chef Show**
  - Chefs index: compact list rows with kitchen avatar, verified badge, rating, dish count, and chevron; zero-dish chefs sorted last; sticky search; "Become a chef" card.
  - Chef show: cover + overlapping avatar, stats row, 2-column meal grid.
- [ ] **Step 2: Orders Index & Order Tracking Show**
  - Orders index: inset grouped list-rows with status badge, date, total, and chevron.
  - Order show: vertical timeline tracking, summary card, items list, and sticky bottom bar for reorder/cancel.
- [ ] **Step 3: Favourites & Informational Pages**
  - Favourites: segmented control [Meals / Chefs], grid of compact meal cards / chef rows.
  - Contact, Privacy, Terms, and Chef Apply: single-column mobile-optimized forms with 16px inputs, clean card groups, and full-width buttons.
- [ ] **Step 4: Verification & Review Gate B**
  - Run tests and capture 390px screenshots for all Batch B views.

---

### Batch C: Chef Portal

#### Task 9: Chef Orders, Meals & Earnings
**Files:**
- Modify: `resources/views/front/chef/earnings.blade.php`
- Modify: `resources/views/front/chef/meals/index.blade.php`
- Modify: `resources/views/front/chef/meals/create.blade.php`
- Modify: `resources/views/front/chef/meals/edit.blade.php`
- Modify: `resources/views/front/chef/orders/index.blade.php`
- Modify: `resources/views/front/chef/orders/show.blade.php`
- Modify: `resources/views/front/chef/profile/edit.blade.php`

- [ ] **Step 1: Chef Earnings & Capacity**
  - Revenue chart + period segmented control + payouts and daily capacity settings card.
- [ ] **Step 2: Chef Meals Management**
  - Card list with availability switch, 3-dots action menu (bottom sheet for edit/delete), grouped form with photo upload preview for create/edit.
- [ ] **Step 3: Chef Orders Management & Profile**
  - Orders list with status badges and quick filter chips.
  - Order details with customer details and sticky action bar to advance status.
  - Kitchen profile edit with mobile inset grouped form.
- [ ] **Step 4: Verification & Review Gate C**
  - Run tests and capture 390px screenshots for all Batch C views.

---

### Batch D: Admin, Auth, Profile & System Pages

#### Task 10: Auth Pages, Profile & Account
**Files:**
- Modify: `resources/views/auth/login.blade.php`
- Modify: `resources/views/auth/register.blade.php`
- Modify: `resources/views/auth/forgot-password.blade.php`
- Modify: `resources/views/auth/reset-password.blade.php`
- Modify: `resources/views/auth/confirm-password.blade.php`
- Modify: `resources/views/auth/verify-email.blade.php`
- Modify: `resources/views/profile/edit.blade.php`
- Modify: `resources/views/profile/partials/update-profile-information-form.blade.php`
- Modify: `resources/views/profile/partials/update-password-form.blade.php`
- Modify: `resources/views/profile/partials/manage-addresses.blade.php`
- Modify: `resources/views/profile/partials/delete-user-form.blade.php`

- [ ] **Step 1: Mobile Auth Screens**
  - Single column, collapse desktop hero image, large title, 16px inputs, 52px full-width primary button.
- [ ] **Step 2: Profile & Account Settings**
  - Inset grouped lists, address management rows, language switch row, confirmation sheet for destructive actions.

---

#### Task 11: Admin Portal & System Error Pages
**Files:**
- Modify: `resources/views/admin/dashboard.blade.php`
- Modify: `resources/views/admin/category/index.blade.php`, `create.blade.php`, `edit.blade.php`
- Modify: `resources/views/admin/meals/index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`
- Modify: `resources/views/admin/orders/index.blade.php`, `show.blade.php`
- Modify: `resources/views/admin/ratings/index.blade.php`
- Modify: `resources/views/admin/users/index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`
- Modify: `resources/views/admin/contacts/index.blade.php`
- Modify: `resources/views/admin/chef_applications/index.blade.php`
- Modify: `resources/views/admin/audit_log/index.blade.php`
- Modify: `resources/views/layouts/app.blade.php`
- Modify: `resources/views/layouts/navigation.blade.php`
- Modify: `resources/views/errors/403.blade.php`, `404.blade.php`, `419.blade.php`, `429.blade.php`, `500.blade.php`, `503.blade.php`

- [ ] **Step 1: Admin Shell & Mobile Navigation**
  - Top bar + slide-over drawer navigation on mobile (no bottom tab bar in admin).
  - Admin dashboard: horizontal stat-cards row + revenue chart + recent lists.
- [ ] **Step 2: Table to Mobile Card Transformation**
  - All admin index tables transformed on mobile to stacked card rows with 3–4 key fields, status badge, and an actions bottom sheet.
- [ ] **Step 3: Admin Forms & System Errors**
  - Grouped form sections with sticky save bar on mobile.
  - Centered error pages with large status code, friendly message, and primary CTA.
- [ ] **Step 4: Verification & Final QA Report**
  - Run full test suite (`php artisan test`) -> 143 passed.
  - Run Pint (`./vendor/bin/pint --test`) -> 0 style issues.
  - Run Vite build (`npm run build`).
  - Capture 390px screenshots in AR and EN for every route.
  - Compile final report table with screenshots, motion notes, and verification evidence.
