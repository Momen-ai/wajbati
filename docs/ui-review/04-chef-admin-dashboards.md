# UI Review: Chef Portal & Admin Dashboard

## 1. Audit
- **Surfaces**: `chef.dashboard`, `chef.orders.*`, `chef.meals.*`, `dashboard` (Admin), `dashboard.orders.*`, `dashboard.users.*`.
- **Authorization**: Strict role gating enforced. Chef cannot access admin panels or other chefs' meals.

## 2. Critique
- **Before**:
  - Admin users and orders tables lacked localized strings, causing awkward mixing of English labels with Arabic data.
  - Chef order status transition dropdown was static and could trigger 500 errors on invalid transitions.
  - Chef dashboard lacked CSV export functionality for financial tracking.
- **After**:
  - Full RTL layout for Admin dashboard with mirrored tables, headers, and action buttons.
  - State machine enforcement: status change triggers server validation with user-friendly error notifications.
  - Added "Export Orders CSV" feature for chefs to download comprehensive sales reports.

## 3. Harden & Clarify
- **Admin Safety**:
  - Action buttons include explicit confirmation dialogues (`Are you sure you want to delete...?`).
  - Administrator count guard alerts admin if attempting to delete or demote the last remaining admin.
- **Arabic Terminology**:
  - Standardized terminology across admin and chef views:
    - "Kitchen Orders" -> "طلبات المطبخ"
    - "Total Revenue" -> "إجمالي الأرباح"
    - "Active Meals" -> "الوجبات النشطة"

## 4. Adapt & Polish
- Stat counter cards feature responsive layout with subtle background accent badges.
- Chart.js analytics graphs resize fluidly across tablet and desktop breakpoints.
