
Codex Mundi — Digital Archive of the 21 World Wonders
=====================================================

Quick start
-----------

1) Copy environment file

```
cp .env.example .env
```

2) Start services

```
docker compose -f infra/docker-compose.yml up -d --build
```

3) Install dependencies and run

```
cd api && npm install && cd ../web && npm install
```

4) Prisma migrate & seed

```
cd api && npx prisma migrate dev && npm run seed
```

Services
--------
- API: http://localhost:3000
- Web: http://localhost:5173
- Postgres: localhost:5432 (db: codexmundi)
- MinIO API: http://localhost:9000, Console: http://localhost:9001

See docs/README.md for details.

