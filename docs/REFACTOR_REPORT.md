# Wajbati Refactor & Hardening Report

This report tracks the status, implementation details, test additions, and verification evidence for all findings F-01 through F-28 across all project phases.

**Last Test Suite Run:**
`Tests:    93 passed (271 assertions)`
`Duration: 29.71s`

---

## Findings Status Matrix (F-01 through F-28)

| ID | Title / Scope | Status | Files Changed | Migrations | Tests Added | Evidence |
|---|---|---|---|---|---|---|
| **F-01** | Uploads Hardening & Extension Spoofing | **Fixed** | `app/Traits/HandlesImageUploads.php`, `app/Console/Commands/ImagesAudit.php`, `ProfileController.php`, `MealController.php` | None | `tests/Feature/UploadSecurityTest.php` | `Tests: 3 passed (UploadSecurityTest)` |
| **F-02** | Card Payments Disablement | **Fixed** | `app/Services/OrderPaymentService.php`, `config/shop.php`, checkout views | None | `tests/Feature/CommerceTest.php` | `CommerceTest > card payment method is disabled and rejected` |
| **F-03** | Stale Cart Pricing on Checkout | **Fixed** | `app/Services/OrderService.php`, `app/Services/CartService.php` | None | `tests/Feature/CommerceTest.php` | `CommerceTest > checkout recalculates live db price` |
| **F-04** | Cart Negative / Invalid Quantities | **Fixed** | `app/Services/CartService.php`, `app/Http/Requests/CartAddRequest.php` | None | `tests/Feature/CommerceTest.php` | `CommerceTest > cart add rejects negative or invalid quantity` |
| **F-05** | Multi-Chef Delivery Fee Calculation & UI | **Fixed** | `app/Services/Quote.php`, `app/Services/OrderService.php`, `app/Services/CartService.php`, `resources/views/front/cart/index.blade.php`, `resources/views/front/orders/show.blade.php` | `2026_10_18_200100_add_commerce_fields_to_orders_and_order_items.php` | `tests/Feature/CommerceQuoteTest.php` | `CommerceQuoteTest` (3 passed: single-chef, 2-chef quote vs HTML vs orders sum, live DB price updates) |
| **F-06** | Cash On Delivery Payment Lifecycle | **Fixed** | `app/Services/OrderPaymentService.php`, `app/Services/OrderStatusService.php` | None | `tests/Feature/CommerceTest.php` | `CommerceTest > cash order payment status lifecycle` |
| **F-07** | Soft Deletes, FK Cascade, Last Admin Guard | **Fixed** | `app/Models/User.php`, `app/Models/Meal.php`, `app/Models/Order.php`, `ProfileController.php`, `UsersController.php` | `2026_10_18_300100_add_soft_deletes_and_foreign_keys.php` | `tests/Feature/AccountSecurityTest.php` | `AccountSecurityTest` (4 passed) |
| **F-08** | Seeder Hardening & Demo Credentials | **Partial** | `database/seeders/DatabaseSeeder.php`, `database/seeders/DemoSeeder.php` | None | None | MealSeeder chef removal pending in Step 2 |
| **F-09** | Role Mass-Assignment Protection | **Fixed** | `app/Models/User.php`, `app/Http/Controllers/Dashboard/UsersController.php` | None | `tests/Feature/SecurityTest.php` | `SecurityTest > registration with role admin payload always creates user role` |
| **F-10** | Admin & Chef Route Integrity | **Fixed** | `routes/admin.php`, `routes/web.php` | None | `tests/Feature/RouteIntegrityTest.php` | `RouteIntegrityTest > all registered controller routes point to existing methods` |
| **F-11** | Dead Admin Cart/Favourites CRUD Removal | **Partial** | `routes/admin.php` | None | None | Cleanup lingering routes/views in Step 2 |
| **F-12** | Rating Integrity & Moderation | **Fixed** | `app/Models/Rating.php`, `app/Policies/RatingPolicy.php`, `app/Http/Controllers/Front/MealController.php` | `2026_10_19_200100_add_unique_to_ratings_and_meal_fields.php` | `tests/Feature/RatingTest.php` | `RatingTest` (3 passed) |
| **F-13** | Contact & Newsletter Persistence | **Fixed** | `app/Http/Controllers/Front/ContactController.php`, `app/Models/Contact.php`, `app/Models/NewsletterSubscriber.php` | `2026_10_19_100100_create_newsletter_subscribers_table.php`, `2026_10_19_100200_update_contacts_table.php` | `tests/Feature/ContactAndNewsletterTest.php` | `ContactAndNewsletterTest` (4 passed) |
| **F-14** | Phone Number Uniqueness & Address Validation | **Fixed** | `app/Http/Requests/ProfileUpdateRequest.php`, `app/Http/Controllers/ProfileController.php` | None | `tests/Feature/ProfileTest.php` | `ProfileTest > profile information can be updated` |
| **F-15** | Chef Restricted from Ordering Own Meals | **Fixed** | `app/Policies/OrderPolicy.php`, `app/Http/Controllers/Front/OrderController.php` | None | `tests/Feature/SecurityTest.php` | `SecurityTest > chef cannot view/edit another chefs meal/order` |
| **F-16** | Rate Limiting on Public Endpoints | **Fixed** | `app/Providers/AppServiceProvider.php`, `routes/web.php` | None | `tests/Feature/RateLimitingTest.php` | `RateLimitingTest` (2 passed) |
| **F-17** | Email Verification Enforcement | **Fixed** | `app/Models/User.php` (`MustVerifyEmail`), `routes/web.php` | None | `tests/Feature/Auth/EmailVerificationTest.php` | `EmailVerificationTest` (3 passed) |
| **F-18** | Asynchronous & Queued Notifications | **Fixed** | `app/Notifications/*` (`ShouldQueue`, `afterCommit()`) | None | `tests/Feature/OrderStatusHistoryTest.php` | `OrderStatusHistoryTest` |
| **F-19** | Order State Machine & History Audit Log | **Fixed** | `app/Services/OrderStatusService.php`, `app/Models/OrderStatusHistory.php`, `app/Http/Controllers/Dashboard/OrderController.php` | `2026_10_19_400100_create_order_status_histories_table.php` | `tests/Feature/OrderStatusTest.php`, `tests/Feature/OrderStatusHistoryTest.php` | `OrderStatusTest` (29 passed) |
| **F-20** | Meal Availability & Serving Quantities | **Partial** | `database/migrations/2026_10_19_200100_add_unique_to_ratings_and_meal_fields.php` | `2026_10_19_200100_*.php` | None | Form inputs and checkout gate pending in Step 2 |
| **F-21** | Currency Helper & Timezone Defaults | **Partial** | `app/helpers.php`, `config/app.php`, `composer.json` | None | None | View replacement pending in Step 3 |
| **F-22** | Complete i18n & RTL Coverage | **Partial** | `app/Http/Middleware/SetLocale.php` | None | None | Views and translations pending in Step 3 |
| **F-23** | Asset Bundling & CDN Cleanup | **Partial** | `public/assets/images/placeholder.svg`, deleted dead JS, `tests/TestCase.php` | None | None | Bundling via Vite pending in Step 4 |
| **F-24** | Performance & Database Indexes | **Partial** | `database/migrations/2026_10_20_200100_add_performance_indexes.php`, `app/Services/OrderStats.php`, `AppServiceProvider.php` | `2026_10_20_200100_add_performance_indexes.php` | None | Verification & cache tests in Step 5 |
| **F-25** | Code Quality & Search Validation | **Fixed** | `app/Http/Requests/MealIndexRequest.php`, `pint.json`, `app/Http/Controllers/Front/MealController.php` | None | None | Linted and validated |
| **F-26** | Comprehensive Test Suite & CI | **Not started** | None | None | None | Scheduled for Phase 3 (Step 7) |
| **F-27** | Documentation & Runbooks | **Not started** | None | None | None | Scheduled for Phase 4 (Step 8) |
| **F-28** | Production Security Hardening | **Not started** | None | None | None | Scheduled for Phase 4 (Step 8) |

---

## NEXT
- **STEP 2 — Remaining Phase 0/1 leftovers**: F-08 MealSeeder chef removal, F-11 admin cart/favourites dead CRUD check, F-20 meal availability/prep_time/number_persons form inputs and checkout gate, F-17 verified existing users migration follow-up, Red-team + reviewer audit.
