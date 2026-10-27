# Architectural & Engineering Decisions Log

This document records key technical decisions, trade-offs, and defaults selected during the Wajbati refactor and hardening mission.

---

## Decision 01: Cart Architecture & Session Storage
- **Context:** An unused `carts` table and admin Cart CRUD existed, while storefront cart is session-based.
- **Decision:** Keep cart purely session-based (`meal_id => quantity`) managed strictly through `App\Services\CartService`. Remove obsolete admin cart CRUD in Phase 1 / Phase 2 to prevent state desynchronization.

## Decision 02: Multi-Chef Delivery Fee Model
- **Context:** F-05 revealed delivery fees were hardcoded as 3 and customers were charged 3 per chef sub-order while UI showed a flat 3.
- **Decision:** Charge delivery fee per chef sub-order because each home cook/chef operates from a distinct kitchen and handles delivery independently. Cart UI will display breakdown grouped by chef with per-chef delivery fee and total.

## Decision 03: Card Payments Disablement
- **Context:** Card option was selectable at checkout and marked orders as `paid` with no real payment gateway integration (F-02).
- **Decision:** Introduce `config('shop.card_enabled', false)`. Disable card payment in UI and validation until a real payment gateway is implemented in Phase 5. Default to Cash on Delivery with `PaymentGateway` contract interface.

## Decision 04: Soft Deletes & Historical Order Snapshots
- **Context:** Cascading deletes destroyed completed order history when meals or user accounts were deleted (F-07).
- **Decision:** Add SoftDeletes to `Meal`, `User`, and `Order`. Make foreign keys restrict or set null on delete. Snapshot `meal_name` onto `order_items` alongside `price` so completed orders remain fully intact historically even if the meal is modified or deleted.

## Decision 05: Last-Admin Guard & Anonymization
- **Context:** An admin could delete themselves, leaving zero admins in the system (F-07).
- **Decision:** Enforce that the last remaining admin user cannot be deleted or demoted across both ProfileController and Admin UsersController. Implement user account anonymization for customers to preserve orders while respecting deletion requests.

## Decision 06: Frontend Styling System & Asset Bundling (F-23)
- **Context:** The codebase had mixed references to CDN Bootstrap 5, Font Awesome, Google Fonts, and partial Tailwind CSS.
- **Decision:** Standardize on **Bootstrap 5 (+ RTL build)** as the primary UI framework across storefront, auth, chef, and admin panels, bundled entirely through Vite along with pinned `@fortawesome/fontawesome-free`, `chart.js`, and self-hosted `@fontsource/cairo`. Remove all external CDN stylesheets and scripts, guaranteeing zero third-party network requests and offline asset availability.

