# Red-Team Security & Integrity Audit Report (Phases 0 & 1)

This report documents adversarial testing and verification conducted across the Phase 0 (Blockers) and Phase 1 (Functional & Integrity) scopes of the Wajbati application.

---

## 1. Executive Summary

Adversarial testing targeted high-risk financial, authentication, data loss, and privilege escalation vulnerabilities. All identified vectors have been mitigated in code and verified with permanent regression tests.

| Attack Vector | Target Area | Exploit Scenario | Status | Mitigating Mechanism | Automated Test |
|---|---|---|---|---|---|
| **R01: Malicious Uploads** | Profile, Meals | Upload `.php` script disguised as avatar/meal | **Defeated** | Binary `getimagesize()` validation, `mimes:jpeg,png,jpg,webp`, UUID filename rewrite | `UploadSecurityTest` |
| **R02: Privilege Escalation** | Registration, Users Admin | `role=admin` in POST payload | **Defeated** | Removed `role` from `$fillable`; explicit authorization in controller | `AccountSecurityTest`, `SecurityTest` |
| **R03: Last Admin Destruction** | Dashboard Users, Profile | Self-delete or demote sole remaining admin | **Defeated** | Guard check: prevents delete/demotion if admin count <= 1 | `AccountSecurityTest` |
| **R04: Negative / Fraudulent Cart** | Cart Service | Negative quantity (`-5`), string, or `999999` | **Defeated** | Strict validation (`integer|min:1|max:20`) in `CartAddRequest` & `UpdateCartRequest` | `CommerceTest` |
| **R05: Stale Price Tampering** | Checkout | Add meal at $5, chef raises to $50, checkout | **Defeated** | `OrderService::quote()` recalculates live DB price at checkout | `CommerceTest`, `CommerceQuoteTest` |
| **R06: Checkout Race Condition** | Checkout | Rapid double-click / concurrent POST requests creating duplicate orders | **Defeated** | User-scoped atomic lock: `Cache::lock('checkout_user_' . $id, 10)` | `CommerceQuoteTest` |
| **R07: Address Injection** | Checkout | Submit another user's `address_id` | **Defeated** | Enforces `user_id == Auth::id()` query scoping before attaching | `CommerceTest` |
| **R08: Card Payment Bypass** | Checkout | Select `payment_method=card` without gateway | **Defeated** | `shop.card_enabled` disabled by default; rejects unverified payment methods | `CommerceTest` |
| **R09: Self-Order & Rating Fraud** | Storefront | Chef orders own meal; rating without purchase; multiple ratings | **Defeated** | `OrderPolicy`, `RatingPolicy` (`delivered`/`completed` purchase check), unique DB composite index | `RatingTest`, `SecurityTest` |
| **R10: Unavailable Item Checkout** | Storefront & Checkout | Order unavailable or out-of-stock meal | **Defeated** | Storefront filtering (`is_available = true`), `OrderService::createOrdersFromCart` validation gate | `MealAvailabilityTest` |
| **R11: Seeder Credential Leakage** | Database Seeders | Production DB seeded with default passwords | **Defeated** | `DatabaseSeeder` requires env vars in prod; `MealSeeder` decoupled from user creation | `SeederSecurityTest` |
| **R12: Existing User Lockout** | Auth Verification | Pre-existing users locked out when `MustVerifyEmail` enabled | **Defeated** | Migration `2026_10_20_300100` marks existing users verified | `EmailVerificationMigrationTest` |
| **R13: Public Endpoint Flooding** | Newsletter, Contacts | Automated script spamming submissions | **Defeated** | Route rate-limiting middleware configured in `AppServiceProvider` | `RateLimitingTest` |

---

## 2. Detailed Findings & Verification

### R06 — Checkout Double Submit / Race Condition
- **Finding:** A customer double-clicking the checkout button rapidly could create duplicate orders and debit multiple charges or place redundant kitchen requests before the session cart was cleared.
- **Fix:** Implemented an atomic cache lock on the customer ID in `OrderService::createOrdersFromCart`:
  ```php
  $lock = Cache::lock('checkout_user_'.$customer->id, 10);
  if (! $lock->get()) {
      throw ValidationException::withMessages([
          'cart' => __('An order is already being processed. Please wait.'),
      ]);
  }
  ```
- **Verification:** Verified via test `checkout double submit is blocked by atomic user lock` in `tests/Feature/CommerceQuoteTest.php`.

### R10 — Unavailable Meal Ordering
- **Finding:** Chefs and Admins lacked form controls for `is_available`, and customers could place orders for items that had become unavailable.
- **Fix:** Added inputs to all Chef and Admin forms, ensured models cast `is_available` to boolean, hid unavailable meals from the storefront catalog, and added an availability gate in `OrderService`.
- **Verification:** Verified via `tests/Feature/MealAvailabilityTest.php` (4 passed).

---

## 3. Conclusion
All red-team scenarios for Phases 0 and 1 have been validated with automated tests. The platform is hardened against common web exploits, concurrency issues, and financial inconsistencies.
