# Deployment: Vercel + Render + Supabase + Cloudinary

## Architecture

| Service | Role | Root / URL |
|---------|------|------------|
| Vercel | Next.js public site | Monorepo `frontend/` |
| Render | Laravel API + admin | Repo root (`Dockerfile`) |
| Supabase | PostgreSQL | Connection via `DATABASE_URL` |
| Cloudinary | News images | `CLOUDINARY_URL` |

## Render environment variables

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
APP_URL=https://your-service.onrender.com
FRONTEND_URL=https://your-app.vercel.app
CORS_ALLOWED_ORIGINS=https://your-app.vercel.app,https://your-app-*.vercel.app

DB_CONNECTION=pgsql
DATABASE_URL=postgresql://postgres:...@db.xxx.supabase.co:5432/postgres?sslmode=require

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

NEWS_IMAGE_DISK=cloudinary
CLOUDINARY_URL=cloudinary://key:secret@cloud_name

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...
```

## Supabase setup

1. Create project at supabase.com
2. Copy **Session pooler** URI (port 5432 or 6543)
3. Run migrations: `php artisan migrate --force`
4. Import Railway backup: `psql $DATABASE_URL < backup.sql`

## Vercel environment variables

Set root directory to `frontend` in project settings.

```env
NEXT_PUBLIC_API_URL=https://your-service.onrender.com
NEXT_PUBLIC_ADMIN_URL=https://your-service.onrender.com/admin/login
```

## Cloudinary setup

1. Create folder `busco/news` (optional)
2. Set `CLOUDINARY_URL` on Render
3. Migrate existing images: `php artisan news:migrate-images-to-cloudinary`

## GitHub Actions secrets (keep-alive)

| Secret | Example |
|--------|---------|
| `RENDER_HEALTH_URL` | `https://your-service.onrender.com/up` |
| `RENDER_API_URL` | `https://your-service.onrender.com` |

## Local development

**Laravel** (port 8000):

```env
NEWS_IMAGE_DISK=public
FRONTEND_URL=http://localhost:3000
CORS_ALLOWED_ORIGINS=http://localhost:3000
```

**Next.js** (`frontend/.env.local`):

```env
NEXT_PUBLIC_API_URL=http://localhost:8000
NEXT_PUBLIC_ADMIN_URL=http://localhost:8000/admin/login
```

Run: `php artisan serve` and `cd frontend && npm run dev`

## DNS cutover

- Public domain → Vercel
- Admin link in footer → `NEXT_PUBLIC_ADMIN_URL` (Render)
- Set `FRONTEND_URL` on Render so legacy `/` routes redirect to Vercel
