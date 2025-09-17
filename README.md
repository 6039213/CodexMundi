Codex Mundi (PHP + SQLite)

Overview
- Minimal, dependency-free PHP app for managing 21 world wonders.
- Implements roles, CRUD, uploads with approval, search/filter/sort, stats, CSV export, and basic map (offline list).

Quick Start
1) Ensure your web root points to `public/` (Laragon: set Document Root to `public`).
2) Open the site in your browser. On first load the SQLite DB and seed data are created.
3) First-time setup: register a new account. The first registered user becomes 'beheerder' automatically; afterwards admins can assign roles under /admin/users.

Folders
- `public/`: web root, router at `index.php`, uploads subfolders.
- `src/`: PHP code (core, controllers, services, views).
- `storage/`: SQLite database file (auto-created).

Roles
- bezoeker: alleen kijken
- onderzoeker: wonderen aanmaken/bewerken (eigen), foto uploaden
- redacteur: keuren (foto’s/inhoud), tags beheren
- archivaris: historie/feiten (jaar, bestaan, mythe, verhaal, locatie) bewerken, documenten uploaden
- beheerder: alles + gebruikers/rollen + instellingen + export

Key Pages
- `/` overzicht
- `/login`, `/register`
- `/wonders/create`, `/wonders/{id}`, `/wonders/{id}/edit`
- `/search` zoeken/filteren/sorteren
- `/map` kaart (offline-lijst)
- `/admin` beoordelingsdashboard (redacteur/beheerder)
- `/stats` statistieken (redacteur/beheerder)
- `/export/wonders.csv` export (beheerder)
- `/admin/settings` upload-instellingen (beheerder)
- `/admin/users` gebruikers en rollen (beheerder)

Uploads
- Foto’s: jpg/png/webp (instelbaar), standaard max 5MB (instelbaar)
- Documenten: pdf/txt (instelbaar), standaard max 10MB (instelbaar)
- Foto’s worden pas zichtbaar na goedkeuring door redacteur/beheerder.

Notes
- Map is an offline stub (table with lat/lng). With internet you can add Leaflet.
- Export is CSV (simple and portable). PDF export can be added later if libraries permitted.
- Basic audit log is recorded for create/update/delete/approve/upload actions.

Frontend Redesign
- Tokens & theming: `public/assets/app.premium.css` augments base styles with design tokens, dark mode, and a premium UI polish. It is loaded after `assets/app.css` via `src/Views/layout.php`.
- Dark mode: Toggle persists in `localStorage` and applies `[data-theme="dark"]` on `<html>`. System `prefers-color-scheme` respected when no explicit choice.
- Components: server-rendered partials under `src/Views/components/ui/*` (Button, Input, Select, Textarea, Checkbox, Radio, Switch, Badge, Tooltip, Modal, Drawer, Tabs, Accordion, Alert, Card, Table, Pagination, Skeleton, EmptyState, Navbar, Sidebar, Footer).
  Example usage:
  ```php
  <?php $label = 'Opslaan'; $variant='primary'; include __DIR__.'/../components/ui/button.php'; ?>
  ```
- Accessibility: global skip link to `#main`, visible focus rings, ARIA `aria-current="page"` on active nav.
- Responsive: container and grids adapt at 640/768/1024/1280/1536px; tables gain horizontal scroll on small screens.


