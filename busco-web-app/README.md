# BUSCO Sugar Milling Website

## Architecture (split stack)

| Layer | Service | Path |
|-------|---------|------|
| Public frontend | **Vercel** (Next.js) | [`frontend/`](frontend/) |
| API + Admin | **Render** (Laravel) | Repo root |
| Database | **Supabase** (PostgreSQL) | — |
| Images | **Cloudinary** | — |

Legacy monolith URL (Railway): https://buscosugarmill.up.railway.app

## Quick start (local)

**Laravel API + admin** (port 8000):

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

**Next.js frontend** (port 3000):

```bash
cd frontend
cp .env.example .env.local
npm install
npm run dev
```

Set `NEXT_PUBLIC_API_URL=http://localhost:8000` in `frontend/.env.local`.

## Deployment

See [docs/DEPLOYMENT_VERCEL_RENDER.md](docs/DEPLOYMENT_VERCEL_RENDER.md) for Render, Vercel, Supabase, and Cloudinary env vars.

- Vercel project root directory: `frontend`
- Render: uses [`Dockerfile`](Dockerfile) and [`render.yaml`](render.yaml)
- Set `FRONTEND_URL` on Render to your Vercel URL (public Laravel routes redirect to Next.js)

## API

Public read-only JSON at `/api/*`. Contract: [docs/API_CONTRACT.md](docs/API_CONTRACT.md).

## Tech Stack

- **Backend:** Laravel 12, Blade admin, PostgreSQL
- **Frontend:** Next.js (App Router), TypeScript
- **Images:** Cloudinary (production) or local disk (development)
