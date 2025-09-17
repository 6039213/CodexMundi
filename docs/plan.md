# Frontend Redesign Plan

Goal: Rebuild the visual layer into a premium, coherent, responsive UI with dark/light modes and zero functional changes. Back-end, routes, requests, and data shapes remain untouched.

## Stack Overview
- App: Minimal PHP MVC (custom) + SQLite
- Router: `public/index.php`
- Views: `src/Views` (server-rendered PHP templates)
- Styles: `public/assets/app.css` (single global stylesheet)
- JS: Lightweight inline scripts in views; Leaflet via CDN on the map page
- No Node/Tailwind build; no package.json — use vanilla CSS with tokens

## Routes & Core Flows
Defined in `public/index.php`:
- `/` list of wonders (public)
- Auth: `/login`, `/register`, `POST /login`, `POST /logout`, `POST /register`
- Wonders CRUD: `/wonders/create`, `POST /wonders`, `/wonders/{id}`, `/wonders/{id}/edit`, `POST /wonders/{id}`, `POST /wonders/{id}/delete`
- Media: `POST /wonders/{id}/photos`, `POST /wonders/{id}/documents`
- Admin/Approvals: `/admin`, `/admin/settings` (+POST), `/admin/users` (+POST role), `/admin/logs`, `POST /admin/photos/{id}/{approve|reject}`, `POST /admin/wonders/{id}/approve`
- Search/Stats/Export/Map/My: `/search`, `/stats`, `/export/wonders.csv`, `/map`, `/my`

Key flows
- Public browse/detail, search, map
- Auth (login/register/logout)
- Create/edit wonder, upload photo/document (role-gated)
- Admin review/approve content and photos
- Stats/CSV export

## Findings: Inconsistencies & Opportunities
- Typography: system-ui stack only; limited scale and hierarchy
- Spacing: fairly consistent but not tokenized; 8pt grid not explicit
- Colors: basic tokens exist; palette can be aligned to premium brand
- Radius/shadows: subtle; card radius 12; no unified token
- A11y: focus styles present; skip-link missing; labels mostly present but some lack `for` linkage; tables lack captions
- Performance: very light CSS/JS; images are mostly SVG; good base CWV
- Console: no warnings by default

## Risks & Mitigations
- Risk: Over-styling could break layout
  - Mitigation: keep classes stable; add tokens + extend styles; avoid markup changes where possible
- Risk: Dark-mode glitches
  - Mitigation: token-driven surfaces; test on key pages in both modes
- Risk: No build chain (no Tailwind)
  - Mitigation: vanilla CSS tokens in `:root` and `[data-theme="dark"]`
- Risk: Admin tables usability on mobile
  - Mitigation: add responsive tables (horizontal scroll) without altering markup

## Design Tokens (CSS variables)
Applied in `public/assets/app.css` under `:root` and `[data-theme="dark"]`.

Colors
- --color-primary: #6B5BFF; --color-primary-600: #5B4BE6; --color-primary-700: #4C3FCC
- --color-accent:  #22C55E
- Grays: 900 #0B0F14, 800 #111827, 700 #1F2937, 600 #374151, 500 #6B7280, 400 #9CA3AF, 200 #E5E7EB, 100 #F3F4F6
- Surfaces: --surface-0 #FFFFFF (light), --surface-0-dark #0B0F14 (dark)
- Text/muted/border tokens derive from surfaces and grays

Spacing (8pt grid)
- --space-1: 4px, --space-2: 8px, --space-3: 12px, --space-4: 16px, --space-5: 20px, --space-6: 24px, --space-8: 32px, --space-10: 40px, --space-12: 48px

Radius
- --radius-sm: 10px, --radius-md: 14px, --radius-lg: 16px, --radius-pill: 999px

Shadows
- --shadow-1: 0 1px 2px rgba(0,0,0,.06)
- --shadow-2: 0 8px 24px rgba(0,0,0,.08)
- --shadow-focus: 0 0 0 3px color-mix(in hsl, var(--color-primary) 35%, transparent)

Typography
- Base stack: Inter, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Arial, sans-serif
- --font-size-xs: 12px, --sm: 14px, --md: 16px, --lg: 18px, --xl: 20px, --2xl: 24px, --3xl: 30px, --4xl: 36px
- Headings map to a clear scale with comfortable line-height

Z-index
- --z-header: 100, --z-dropdown: 110, --z-modal: 120, --z-toast: 130

Breakpoints
- sm 640, md 768, lg 1024, xl 1280, 2xl 1536 (used in media queries)

## UI Components (basisbibliotheek)
Location: `src/Views/components/ui/`
- Button, Input, Select, Textarea, Checkbox, Radio, Switch
- Badge, Tooltip
- Modal, Drawer
- Tabs, Accordion
- Alert/Toast
- Card
- Table, Pagination
- Skeleton, EmptyState
- Navbar, Sidebar, Footer

Mapping (old → new)
- Buttons: `.btn`, `.btn-primary`, `.btn-secondary`, `.btn-link` → Button
- Forms: `input`, `select`, `textarea` → Input/Select/Textarea with consistent focus/invalid states
- Cards: `.card` → Card component wrapper
- Badges: `.badge` → Badge
- Tables: `table` markup preserved → Table styles + sticky header on desktop + row hover
- Alerts: `.alert.{success|error}` preserved → Alert styles; Toast available globally

## Page-by-Page Improvement Plan
- `src/Views/layout.php`
  - Add skip-to-content link; set `<main id="main">`; dynamic page title when provided
  - Sticky, blurred header; visible active nav; theme toggle persists
- Public pages (`wonders/*.php`)
  - Consistent headings hierarchy; grid/cards with unified spacing, hover micro-animations
  - Responsive images (`.cover`), badges for metadata; empty states styled
  - Tables: zebra/hover, sticky header on desktop, horizontal scroll on small screens
- Auth (`auth/*.php`)
  - Centered card; input helper text; clear validation/focus; password manager-friendly
- Admin (`admin/*.php`)
  - Dense table headers, clear actions; role selects aligned; sticky header
- Map (`wonders/map.php`)
  - Card styling around map; tokens for borders/radii; table as fallback remains

## Performance & A11y
- CSS only; no heavy libs; animations 80–150ms and GPU-friendly
- Keep images as-is; CSS contain painting where helpful
- Focus-visible rings; semantic markup preserved; labels linked where present
- Skip link for keyboard users; maintain `meta color-scheme` and theme toggle

## Rollout Strategy
1) Introduce tokens and base UI styles (no markup changes)
2) Update layout for skip-link and theming (non-breaking)
3) Incrementally refine pages: adjust class names minimally, keep attributes/ids intact
4) Add review checklist and run manual a11y/keyboard checks

