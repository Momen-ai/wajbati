# Wajbati (وجبتي) — Design System & Guidelines

## Core Principles
Wajbati’s visual identity communicates culinary warmth, appetizing intimacy, and modern craftsmanship through a refined, dark dining aesthetic.

---

## 1. Color Palette & Surfaces

### Accent (Warm Culinary Orange)
- **Primary:** `#ff7a18` (rich flame orange)
- **Primary Hover:** `#ff9138`
- **Primary Active:** `#e66a10`
- **Primary Glow / Shadow:** `rgba(255, 122, 24, 0.25)`
- **Secondary / Warm Tint:** `rgba(255, 122, 24, 0.12)`

### Neutral Dark Surfaces
- **Surface 0 (Page Canvas):** `#0a0a0a`
- **Surface 1 (Cards & Modules):** `#161616`
- **Surface 2 (Elevated Panels, Drawers, Dropdowns):** `#222222`
- **Surface 3 (Input fields, Active States):** `#2d2d2d`
- **Border Default:** `rgba(255, 255, 255, 0.08)`
- **Border Highlight / Hover:** `rgba(255, 122, 24, 0.35)`

### Typography & Text Contrast
- **Text Main / Headings:** `#f0f0f0` (High contrast, meets WCAG AA on all dark surfaces)
- **Text Muted / Secondary:** `#a0a0a0`
- **Text Subtle / Captions:** `#737373`
- **Text Inverted (on Primary button):** `#0a0a0a` (bold weight for maximum readability)

---

## 2. Typography
- **Arabic (Primary / Default):** `Cairo`, sans-serif (via local `@fontsource/cairo`)
- **Latin / English:** `Inter`, sans-serif (via local `@fontsource/inter`)
- **Zero CDN Rule:** No external font stylesheets (`fonts.googleapis.com` or similar); all fonts bundled locally through Vite.
- **Hierarchy:**
  - Display / Hero: 2.5rem – 3.5rem, weight 800
  - H1 / Section Title: 1.75rem – 2.25rem, weight 700
  - H2 / Card Title: 1.25rem – 1.5rem, weight 600
  - Body / Paragraph: 0.95rem – 1.05rem, line-height 1.6, weight 400
  - Meta / Badges: 0.75rem – 0.85rem, weight 600

---

## 3. Directionality & RTL-First Standard
- **Layout:** RTL is primary (`dir="rtl"` when locale is `ar`). LTR supported for English (`dir="ltr"`).
- **Logical Properties:** All margins, paddings, and alignments use logical CSS utility classes:
  - Margins: `ms-*` (margin-start), `me-*` (margin-end)
  - Paddings: `ps-*` (padding-start), `pe-*` (padding-end)
  - Text alignment: `text-start`, `text-end`
  - Position: `inset-inline-start`, `inset-inline-end`
- **Iconography:** Directional arrows, chevrons, and pagination controls automatically mirror between RTL and LTR.

---

## 4. Cards & Component Language
- **Card Background:** Surface 1 (`#161616`) with `1px solid rgba(255, 255, 255, 0.08)`.
- **Top Edge Highlight:** Subtle inner highlight `box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06)`.
- **Border Radius:**
  - Small elements / badges / buttons: `8px`
  - Standard cards / inputs / modals: `16px` (`--radius: 16px`)
  - Featured containers / hero cards: `24px`
- **Hover Micro-interactions:**
  - Spotlight glow following the cursor (`data-spotlight`)
  - Soft orange rim light: `border-color: rgba(255, 122, 24, 0.35)`
  - Gentle lift: `transform: translateY(-3px)` (strictly no jarring `-8px` or heavy scaling).

---

## 5. Motion & Motion Safety
- **Framework:** GSAP 3 + ScrollTrigger, driven declaratively via data attributes.
- **Performance Budget:** Animations limited strictly to `transform` and `opacity`.
- **Reduced Motion:** If `prefers-reduced-motion: reduce` is active, animations immediately disable and render static endpoints.
- **Touch Targets:** Minimum 44px × 44px clickable area on all interactive elements.
