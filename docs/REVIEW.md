# Frontend Redesign Review Checklist

Use this list to click-through and verify feature parity, responsive behavior, a11y, and dark mode across all routes. Test at 360/768/1024/1280/1536px. Ensure no console errors, keyboard navigation works, and focus rings are visible.

## Global
- Header: sticky, active route visible, theme toggle persists
- Skip link: Tab once focuses “Ga naar inhoud”, jumps to main
- Focus states: visible on links, buttons, inputs
- Dark mode: no contrast or overflow glitches
- Alerts: flash messages styled and readable

## Public
- `/` Overzicht
  - Cards grid responsive; images keep 16:9 crop
  - Badges visible; pending badge when not approved
  - Hover elevation subtle; links underline on hover
- `/wonders/{id}` Detail
  - Metadata badges; year/myth/story typeset; location displayed
  - Slider: thumbs highlight active; prev/next buttons work
  - Photos/documents forms (if permitted) render; accept file/URL
- `/search`
  - Filters/inputs align; responsive layout; results listed/tabled as before
- `/map`
  - Map card styled; if Leaflet loads markers show; fallback table readable

## Auth
- `/login`, `/register`
  - Inputs labeled; required fields enforced; focus outline visible
  - Submit button styling consistent; error flash shows if invalid

## Researcher
- `/wonders/create`
  - All fields present; checkbox exists-now styled; selects render
  - Submit creates entry (no UI regressions)
- `/wonders/{id}/edit`
  - All fields prefilled; tags input appears for redacteur/beheerder
  - Save updates entry; layout intact

## Admin
- `/admin` (dashboard/queue)
  - Tables readable; sticky header on desktop; row hover
- `/admin/settings`
  - Form controls consistent; helper text visible where present
- `/admin/users`
  - Role select per row; save works; table intact
- `/admin/logs`
  - Log lines readable; code blocks styled
- `/stats`
  - Statistics cards/tables align visually
- `/export/wonders.csv`
  - Link accessible; CSV downloads as before

## My Submissions
- `/my`
  - Cards or table show current user submissions; badges present

## Responsiveness
- 360/768: Nav items wrap gracefully; tables horizontally scroll if needed
- 1024/1280/1536: Content centered with comfortable max-width; sticky table headers on longer lists

## A11y & Keyboard
- Tab/Shift+Tab traverses navigation, forms, buttons logically
- Esc closes any open modal/drawer (if used in UI demos)
- ARIA attributes present on active nav items

