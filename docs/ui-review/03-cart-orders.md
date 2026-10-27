# UI Review: Cart, Checkout & Orders

## 1. Audit
- **Surfaces**: `front.cart.index`, `front.orders.index`, `front.orders.show`.
- **Commerce Integrity**: Quote calculation verified with multi-chef breakdown. Delivery fee calculated per chef.

## 2. Critique
- **Before**:
  - Cart displayed a static delivery fee line ($3.00) even when multiple chefs were present in the cart, causing customer total discrepancy with actual order charges.
  - Cancel order button was missing on customer order details.
  - Reorder capability was missing, requiring customer to navigate and search meals manually.
- **After**:
  - Implemented multi-chef quote breakdown: items grouped by kitchen, showing per-chef subtotal and delivery fee.
  - Prominent "Cancel Order" button for pending orders with instant confirmation modal.
  - "Reorder All Items" button allowing one-click cart replenishment from previous orders.

## 3. Harden & Clarify
- **Status Badges & Progress**:
  - Statuses color-coded with semantic contextual colors:
    - Pending: Warning yellow (`قيد الانتظار`)
    - Accepted / Preparing: Info / Primary blue (`مقبول` / `قيد التحضير`)
    - Prepared / Delivered / Completed: Success green (`جاهز` / `تم التوصيل` / `مكتمل`)
    - Cancelled / Rejected: Danger red (`ملغي` / `مرفوض`)
- **Address Selection**:
  - Dropdown populated with verified user addresses plus a quick inline "Add new delivery address" modal.

## 4. Adapt & Polish
- Mobile order summary adheres to sticky bottom bar on checkout.
- Clear breakdown of items, subtotal, delivery fees, and grand total in localized currency format.
