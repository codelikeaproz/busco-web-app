# BUSCO Next.js Frontend (Vercel)

Public site for BUSCO Sugar Milling. Talks to the Laravel API on Render.

## Local dev

```bash
cp .env.example .env.local
npm install
npm run dev
```

Open http://localhost:3000. Browser-side AJAX now goes through the Next.js proxy route at `/api/backend/*`, which forwards to Laravel.

Run Laravel in the repo root: `php artisan serve --host=127.0.0.1 --port=8000`

## Vercel deploy

1. Import the GitHub repo in [vercel.com](https://vercel.com)
2. **Root Directory:** `frontend`
3. Framework preset: Next.js (auto-detected)
4. Environment variables:

| Variable | Example |
|----------|---------|
| `API_URL` | `https://busco-api.onrender.com` |
| `NEXT_PUBLIC_API_URL` | `https://busco-api.onrender.com` |
| `NEXT_PUBLIC_ADMIN_URL` | `https://busco-api.onrender.com/admin/login` |

5. Deploy — preview URL is generated automatically

## Build check

```bash
npm run build
```

## Pages

| Route | Notes |
|-------|-------|
| `/` | Home — API `/api/home` |
| `/news` | AJAX category filter |
| `/careers` | AJAX search + filters |
| `/quedan` | AJAX history pagination |
| `/community` | Redirects to CSR news filter |
