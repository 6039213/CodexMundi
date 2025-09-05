Codex Mundi — Digitaal archief van 21 wereldwonderen

Stack: HTML5, CSS3, vanilla JS, PHP 8 (PDO, sessions), MySQL, Leaflet (CDN)

Setup
1) Maak database aan via phpMyAdmin
   - Import eerst `database/schema.sql`
   - Import daarna `database/seed.sql`

2) Configureer database in `app/config.php` of maak `app/.env` met:
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=codex_mundi
DB_USER=root
DB_PASS=
```

3) Start server
   - Laragon/XAMPP: map `public/` als document root
   - Of: `php -S localhost:8000 -t public`

4) Demo-accounts
   - admin@demo.test / Admin123!
   - researcher@demo.test / Research123!
   - editor@demo.test / Editor123!

Pagina's
- Home: `/public/index.php`
- Wonders: `/public/wonders.php`
- Detail: `/public/wonder.php?slug=...`
- Map: `/public/map.php`
- Login/Registratie: `/public/login.php`, `/public/register.php`
- Dashboard: `/public/dashboard/index.php`

Beveiliging
- PDO prepared statements, utf8mb4
- CSRF token op POST (`helpers.php`)
- Password hashing bij registratie en login via `password_verify`
- Role checks met `require_role([...])`


