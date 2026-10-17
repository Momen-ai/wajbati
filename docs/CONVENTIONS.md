# Wajbati Engineering Conventions

This document establishes the architecture, code conventions, standards, and rules for all developers and sub-agents working on the Wajbati codebase.

---

## 1. Money & Currency

- **Rule:** Never hardcode currency symbols (`$`, `€`, `EGP`) or fee numbers in views, emails, notifications, or services.
- **Helper:** All prices, subtotals, fees, and totals must be rendered via the global `money($amount)` helper (e.g. `{{ money($order->total) }}`).
- **Configuration:** Currencies and default delivery fees are read strictly from `config('shop.currency')` (`'code' => 'ILS', 'symbol' => '₪'`) and `config('shop.delivery_fee')`.
- **Database Storage:** Stored as decimal `(10, 2)` or integer cents. Order items must snapshot `price` and `meal_name`.

---

## 2. Localization & i18n Rules

- **Primary Locale:** Arabic (`ar`, RTL).
- **Secondary Locale:** English (`en`, LTR).
- **Strings:** Every user-visible string must be wrapped in `__('Translation Key or English string')`.
- **Translations Storage:** Main files are `lang/ar.json` and `lang/en.json`.
- **Sub-agent Convention:** Parallel streams write translation additions to `lang/_pending/<stream-id>.json`. The orchestrator merges, deduplicates, and sorts them into `lang/ar.json` and `lang/en.json` at stream merge time. `lang/_pending/` must remain empty at the end of each phase.
- **RTL Support:** Use Bootstrap 5 RTL (`bootstrap.rtl.min.css`) when the active locale is Arabic. Use logical CSS utilities (`ms-*`, `me-*`, `text-start`, `text-end`) instead of directional ones (`ml-*`, `mr-*`, `text-left`, `text-right`).

---

## 3. Image Uploads & File Security

- **Rule:** Never accept arbitrary uploads or trust user-supplied filenames or extensions.
- **Central Trait:** All uploads must go through `App\Traits\HandlesImageUploads`.
- **Validation:** Every upload must enforce `image|mimes:jpeg,png,jpg,webp|max:2048`.
- **Sanitization:** Generate filenames deterministically using `Str::uuid() . '.' . $safeExtension`.
- **Verification:** Run `getimagesize()` on the temporary file to ensure binary image validity before persisting to disk.
- **Storage:** Public disk under dedicated directories (`meals/`, `chefs/`, `users/`). Web-server configs must deny execution of `.php`, `.phtml`, `.php5` under `/storage`.

---

## 4. Order Status & Payment Lifecycle

- **Allowed Statuses:** `pending`, `accepted`, `preparing`, `ready_for_delivery`, `out_for_delivery`, `delivered`, `completed`, `cancelled`, `rejected`.
- **State Machine:** Transitions must be validated through `App\Services\OrderStatusService::canTransition($from, $to)`.
- **Payment Methods:** Cash on Delivery (COD) is default. Card payments are disabled (`config('shop.card_enabled') = false`) until a verified gateway integration exists.
- **Payment Statuses:** `pending`, `paid`, `failed`, `refund_pending`, `refunded`.
  - Cash on Delivery orders transition `payment_status` from `pending` to `paid` upon reaching `delivered` or `completed`.
  - Cancelled or rejected orders with pending cash become `failed`.
- **Revenue Calculation:** Total revenue is defined strictly in `App\Services\OrderStats` as the sum of `orders.total` where `payment_status = 'paid'` AND `status IN ('delivered', 'completed')`.

---

## 5. Database Migrations

- **Immutability:** Existing committed migration files must NEVER be edited.
- **Reversibility:** Every new migration must implement both `up()` and `down()` methods cleanly.
- **Cross-Platform Compatibility:** Migrations must execute without error on both SQLite (for tests) and MySQL 8 (for production). Avoid MySQL-only or SQLite-only syntax quirks.
- **Timestamp Reservation for Parallel Streams:**
  - `2026_10_{18+phase}_{HH}{mmss}_name.php`
  - Stream A: `HH = 10`
  - Stream B: `HH = 20`
  - Stream C: `HH = 30`
  - Stream D: `HH = 40`
  - Stream E: `HH = 50`

---

## 6. Testing & Quality Standards

- **TDD:** Write a failing test demonstrating the bug or requirement first (RED), implement the minimal code (GREEN), and refactor.
- **Test Style:** Standardized on Pest 4 / PHPUnit 12.
- **DataProvider syntax:** Always use `#[\PHPUnit\Framework\Attributes\DataProvider('methodName')]` with public static provider methods.
- **Zero Regressions:** Run `vendor/bin/pint` and `php artisan test` after every phase.
