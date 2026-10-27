# UI Review: Home Page (`front.home`)

## 1. Audit
- **Visual Structure**: Hero banner with dynamic call-to-actions, category carousel, featured meals grid, chef showcase spotlight, and community testimonials.
- **Typography & Font**: Cairo webfont loaded locally. Headings properly scaled (`h1` 2.5rem - 3.25rem on desktop, 1.85rem on mobile).
- **RTL vs LTR**: Nav links, search inputs, and badge placements mirror smoothly when toggling between Arabic and English.

## 2. Critique
- **Before**:
  - Hero search button had directional icon `<i class="fas fa-search me-2">` which caused spacing collisions in RTL.
  - Category badges had fixed horizontal margins that misaligned on small screens.
  - Testimonial quotation icons were facing left regardless of reading direction.
- **After**:
  - Replaced manual directional margins with logical classes (`me-2` -> `pe-2`/`ps-2` logical context).
  - Flex gap wrapping (`gap-3`) used for category tags and CTA buttons.
  - Mirrored quote icons in RTL mode (`transform: scaleX(-1)`).

## 3. Harden & Clarify
- **Arabic Copy**:
  - Replaced generic translations with natural Palestinian Arabic phrasing:
    - "Homemade food with love" -> "وجبات بيتية محضرة بكل حب وعناية"
    - "Order fresh from local kitchens" -> "اطلب أشهى المأكولات الطازجة مباشرة من مطابخ الأسر المنتجة"
- **Resilience**:
  - Fallback placeholder SVG rendered if meal or chef image fails or is missing.
  - Star ratings gracefully default to 5.0 unrated state instead of rendering empty containers.

## 4. Adapt & Polish
- Mobile search bar collapses into an ergonomic sticky container.
- Card hover elevation (`hover-lift`) adds subtle translation without layout shift.
