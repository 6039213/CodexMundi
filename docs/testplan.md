
# Testplan — Codex Mundi

Doel
----
Dit testplan beschrijft de aanpak, testvormen, omgeving en scenario’s voor Codex Mundi.

Methodiek & testvormen
----------------------
- Black-box scenario-tests (nadruk)
- Unit- en integratietests voor kernlogica (auth, RBAC, filters)
- Acceptatietests (UAT) op high-level criteria

Testomgeving
------------
- Docker Compose: Postgres, MinIO, API, Web
- Seed data: 21 wereldwonderen
- Testaccounts per rol (zie docs/README.md)
- Testbestanden: PNG (1MB), PDF (OK), EXE (FOUT), JPEG (50MB FOUT)

Scenario’s (overzicht)
----------------------
1. Zoek op naam (Bezoeker)
2. Filter klassiek/modern (Bezoeker/Onderzoeker)
3. CRUD eigen item (Onderzoeker)
4. Indienen & goedkeuren (Onderzoeker → Redacteur)
5. Foto-upload policy (Beheerder/Redacteur)
6. Kaart & locatie (Archivaris/Bezoeker)
7. Statistieken & export (Beheerder)
8. Rechtennegatief (Onderzoeker probeert userbeheer)
9. Beveiliging: 2FA
10. Publiek veilig zonder login

Voor elk scenario uitwerken: doel, precondities, testdata, stappen, expected, actual, resultaat, bevindingen.

