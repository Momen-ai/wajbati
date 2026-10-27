# UI Review: Meals Index & Meal Details

## 1. Audit
- **Surfaces**: `front.meals.index` (catalog, sidebar filters, sorting, grid) and `front.meals.show` (gallery, pricing, servings, prep time, chef card, cart drawer, reviews).
- **Asset Integrity**: Verified zero CDN requests. All icons bundled locally via Font Awesome SVG/webfonts.

## 2. Critique
- **Before**:
  - Filter drawer on mobile blocked viewport without easy dismissal.
  - Meal detail image on mobile pushed description and CTA below the 800px fold.
  - Price tags rendered hardcoded "$" sign with trailing spacing issues in RTL.
  - Serving count and preparation time chips lacked contrast against the dark background.
- **After**:
  - Replaced filter panel on mobile with collapsible accordion.
  - Adopted 4:3 responsive aspect ratio for meal media container.
  - Standardized all pricing via `money()` helper (e.g. `25.00 ₪` in Arabic, `₪ 25.00` in English).
  - High-contrast badges for servings (`number_persons`) and preparation time (`prep_time_minutes`).

## 3. Harden & Clarify
- **Availability State**:
  - When `is_available` is false, "Add to Cart" button is explicitly disabled with an alert badge (`Currently Unavailable` / `غير متوفر حالياً`).
- **Natural Copy**:
  - "per order" -> "لكل وجبة"
  - "persons" -> "أشخاص"
  - "min" -> "دقيقة"
  - "Write a Review" -> "شاركنا رأيك في الوجبة"

## 4. Adapt & Polish
- Quantity steppers (`+` and `-`) enlarged to 44px min tap targets for touchscreens.
- Star rating selector rendered with interactive accessible radio chips.
