# BUSCO Split-Stack Refactor Checklist

Legend: **Code** = done in repo · **Deploy** = you do in cloud dashboards

---

## Accounts (Deploy — manual)

- [ ] Render — Web Service for Laravel
- [ ] Supabase — PostgreSQL project
- [ ] Cloudinary — image CDN
- [ ] Vercel — Next.js frontend (`frontend/` root directory)

---

## Phase 0 — Prep

- [x] **Code** API contract (`docs/API_CONTRACT.md`)
- [x] **Code** DB export/import scripts (`scripts/export-railway-db.*`, `scripts/import-supabase-db.ps1`)
- [ ] **Deploy** Export Railway Postgres: `.\scripts\export-railway-db.ps1`
- [ ] **Deploy** Import into Supabase: `.\scripts\import-supabase-db.ps1 -InputFile backup.sql`

---

## Phase 1 — Infrastructure

- [x] **Code** `render.yaml` + `Dockerfile`
- [x] **Code** GitHub Actions keep-alive + free-tier monitor
- [ ] **Deploy** Set Render env vars ([`docs/DEPLOYMENT_VERCEL_RENDER.md`](DEPLOYMENT_VERCEL_RENDER.md))
- [ ] **Deploy** Point `DATABASE_URL` to Supabase on Render
- [ ] **Deploy** Retire Railway after Render smoke test

---

## Phase 2 — API + Cloudinary

- [x] **Code** `routes/api.php` + Resources + CORS
- [x] **Code** Cloudinary upload in admin news CRUD
- [x] **Code** `php artisan news:migrate-images-to-cloudinary`
- [ ] **Deploy** Run image migration on production if moving from Railway storage

---

## Phase 3–5 — Next.js frontend

- [x] **Code** `frontend/` monorepo — all public routes + AJAX filters
- [x] **Code** `frontend/README.md` + `npm run build` passes
- [ ] **Deploy** Vercel project (root directory: `frontend`)
- [ ] **Deploy** `NEXT_PUBLIC_API_URL` + `NEXT_PUBLIC_ADMIN_URL` on Vercel

---

## Phase 6 — Cutover

- [x] **Code** `RedirectPublicToFrontend` middleware + tests
- [x] **Code** Set `FRONTEND_URL` in `.env.example` / `config/busco.php`
- [ ] **Deploy** Set `FRONTEND_URL` + `CORS_ALLOWED_ORIGINS` on Render
- [ ] **Deploy** DNS: public domain → Vercel

---

## Phase 7 — Cleanup

- [x] **Code** `docs/BUSCO_FULL_DOCUMENTATION.md` sections 19–20 updated
- [x] **Code** Removed orphan `community.blade.php`
- [x] **Code** Removed legacy public Blade views, `PublicSite` controllers, and unused root Vite/Tailwind
- [x] **Code** Public Laravel routes redirect to Next.js (`FRONTEND_URL`, default `http://localhost:3000`)

---

## Next.js routes (all code-complete)

| Route | Status |
|-------|--------|
| `/` | Done |
| `/about`, `/services`, `/process`, `/contact` | Done |
| `/news` + AJAX filter | Done |
| `/news/[id]` | Done |
| `/quedan` + AJAX pagination | Done |
| `/careers` + AJAX filter | Done |
| `/careers/[slug]` | Done |
| `/community` | Done (redirect) |

---

## Quick deploy order

1. Supabase → migrate + import backup  
2. Render → deploy Laravel, set env vars  
3. Cloudinary → set `CLOUDINARY_URL`, run image migration  
4. Vercel → deploy `frontend/`, set API URL  
5. Render → set `FRONTEND_URL` to Vercel URL  
6. GitHub → add `RENDER_HEALTH_URL` / `RENDER_API_URL` secrets  
7. Retire Railway
