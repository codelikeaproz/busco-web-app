# Railway Deployment Guide (BUSCO Web App)

Updated: February 25, 2026

This guide is the working setup for deploying `busco-web-app` (Laravel + PostgreSQL + file uploads) to Railway.

It includes the exact fixes for the issue where uploaded news images returned `404` on `/storage/news/...`.

## 1. What You Are Deploying

- Laravel app service (`buscosugarmill`)
- Railway PostgreSQL service
- Railway volume mounted to Laravel uploads folder
- Public file uploads served through Laravel `public/storage` symlink

## 2. Prerequisites

Before starting:

- Code is pushed to GitHub
- Railway account is ready
- Railway CLI installed locally (optional but strongly recommended)
- Laravel app works locally

Optional CLI setup:

```powershell
npm i -g @railway/cli
railway login
```

## 3. Create Railway Project and Services

### 3.1 Create Railway project

In Railway dashboard:

1. Create a new project
2. Add your GitHub repo / service for `busco-web-app`

### 3.2 Add PostgreSQL service

1. Click `New`
2. Add `Postgres`
3. Wait until status is `Online`

## 4. Attach Persistent Volume for Uploads (Important)

This is required for uploaded images to survive restarts/redeploys.

Attach a volume to the Laravel web service (`buscosugarmill`) with:

- Mount Path: `/app/storage/app/public`

Why this path:

- Laravel stores public uploads in `storage/app/public/...`
- Your news images are saved to `storage/app/public/news/...`
- Railway volume must be mounted exactly there

## 5. Connect Laravel Service to Postgres

In Railway, open your Laravel service (`buscosugarmill`) and connect/link the Postgres service.

Railway usually exposes database variables automatically after linking, but verify them in the Laravel service `Variables` tab.

## 6. Set Laravel Environment Variables (Laravel Service)

In Railway -> `buscosugarmill` -> `Variables`, configure these:

### Required app vars

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://buscosugarmill.up.railway.app
```

`APP_KEY` must be set.

Generate locally (one time):

```powershell
cd "D:\4th Year\OJT Project\busco-web-app"
php artisan key:generate --show
```

Copy the generated key into Railway `APP_KEY`.

### Required database vars (PostgreSQL)

```env
DB_CONNECTION=pgsql
DB_HOST=<from Railway Postgres service>
DB_PORT=<from Railway Postgres service>   # usually 5432
DB_DATABASE=<from Railway Postgres service>
DB_USERNAME=<from Railway Postgres service>
DB_PASSWORD=<from Railway Postgres service>
DB_SSLMODE=prefer
```

Notes:

- `DB_SSLMODE=prefer` matches this app's `config/database.php`
- If Railway/network policy requires SSL strictly, use `DB_SSLMODE=require`

### Recommended app vars for this project

```env
FILESYSTEM_DISK=public
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

Optional (first deploy admin bootstrap only):

```env
ADMIN_SEED_ENABLED=true
ADMIN_SEED_NAME=Your Name
ADMIN_SEED_EMAIL=your@email.com
ADMIN_SEED_PASSWORD=StrongPassword123
```

After first successful deploy/login, disable:

```env
ADMIN_SEED_ENABLED=false
```

## 7. Railway Service Commands (Correct Working Setup)

Open Railway -> `buscosugarmill` -> `Settings`.

### 7.1 Build Command

Use:

```bash
composer install --no-dev --optimize-autoloader --no-interaction && npm ci && npm run build
```

### 7.2 Pre-deploy Command

Use:

```bash
php artisan optimize:clear && php artisan migrate --force
```

Why no `storage:link` here:

- `public/storage` symlink exists in the runtime container filesystem
- Pre-deploy runs before runtime starts
- We need the symlink created every time the app container starts

### 7.3 Start Command (Important Fix for Image Uploads)

Use:

```bash
php artisan storage:link --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

Why this fixes uploads:

- Your uploaded files are in `/app/storage/app/public/news/...`
- Public URLs like `/storage/news/file.jpg` require `/app/public/storage` symlink
- Railway runtime containers can restart/change, so the symlink may be missing unless recreated at startup

## 8. Deploy

1. Save the commands and variables
2. Trigger deploy (or push a new commit)
3. Watch build/deploy logs for errors

## 9. First Deployment Verification Checklist

After deployment:

1. Open the site home page
2. Open `/admin/login`
3. Log in
4. Create or edit a news item
5. Upload a JPG/JPEG image
6. Open the news page and verify image renders

Expected image URL pattern:

```text
https://buscosugarmill.up.railway.app/storage/news/<filename>.jpg
```

## 10. Common Mistakes (That Caused Real Issues)

### 10.1 Wrong Artisan command syntax

Do not use:

```bash
php artisan migrate --force db:seed
```

This causes:

- `No arguments expected for "migrate" command, got "db:seed"`

Use either:

```bash
php artisan migrate --force --seed
```

or:

```bash
php artisan migrate --force && php artisan db:seed --force
```

### 10.2 Running `railway run` and thinking it changed the deployed container

Important:

- `railway run ...` runs on your local machine with Railway env vars
- `railway ssh -s buscosugarmill` opens the deployed container shell

If you want to inspect the live app filesystem, use `railway ssh`.

### 10.3 File exists but image still 404

This usually means:

- Real file exists in `/app/storage/app/public/news/...`
- But `/app/public/storage` symlink is missing

Fix:

```bash
php artisan storage:link --force
```

And keep it in `Start Command`.

## 11. Troubleshooting Uploaded Images (Step by Step)

If uploaded image URLs return `404` on `/storage/news/...`, check in this order.

### 11.1 Confirm the browser URL

In browser dev tools, inspect the `<img>` and copy `src`.

It should look like:

```text
/storage/news/<filename>.jpg
```

or full URL:

```text
https://buscosugarmill.up.railway.app/storage/news/<filename>.jpg
```

### 11.2 SSH into the deployed Railway container

```powershell
cd "D:\4th Year\OJT Project\busco-web-app"
railway login
railway link
railway ssh -s buscosugarmill
```

### 11.3 Check where the file actually is

Inside the container:

```bash
ls -lah /app/storage/app/public/news
```

If the uploaded file is there, upload storage is working.

### 11.4 Check the symlink used by `/storage/...`

Inside the container:

```bash
ls -lah /app/public/storage
ls -lah /app/public/storage/news
```

Expected:

- `/app/public/storage` is a symlink to `/app/storage/app/public`
- `/app/public/storage/news` shows the same uploaded files

If `/app/public/storage` does not exist:

```bash
php artisan storage:link --force
```

### 11.5 Clear cached config/routes/views (safe)

```bash
php artisan optimize:clear
```

## 12. Postgres Connection Troubleshooting

If the app fails to connect to PostgreSQL:

1. Confirm `DB_CONNECTION=pgsql`
2. Confirm `DB_*` values are from the Railway Postgres service (not local)
3. Confirm the Laravel service is linked/connected to Postgres in Railway
4. Re-run deploy
5. Check logs for authentication or SSL errors

If you see SSL-related errors, try:

```env
DB_SSLMODE=require
```

## 13. File Upload Architecture (For Future Reference)

This is the expected setup:

- Real uploaded file path: `/app/storage/app/public/news/<file>.jpg`
- Public web path: `/app/public/storage/news/<file>.jpg`
- Public web URL: `/storage/news/<file>.jpg`
- `public/storage` is a symlink to `storage/app/public`

This app's news upload code already stores files correctly to the public disk:

- `app/Http/Controllers/Admin/NewsController.php` uses `store('news', 'public')`

## 14. Final Recommended Railway Configuration (Copy/Paste)

### Build Command

```bash
composer install --no-dev --optimize-autoloader --no-interaction && npm ci && npm run build
```

### Pre-deploy Command

```bash
php artisan optimize:clear && php artisan migrate --force
```

### Start Command

```bash
php artisan storage:link --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

### Volume Mount Path

```text
/app/storage/app/public
```

## 15. Quick Recovery Checklist (When Stressed / Broken Again)

If image uploads break again:

1. Check Railway `Start Command` still contains `php artisan storage:link --force`
2. Check volume is still attached to `buscosugarmill`
3. Confirm volume mount path is `/app/storage/app/public`
4. `railway ssh -s buscosugarmill`
5. Run:

```bash
ls -lah /app/storage/app/public/news
ls -lah /app/public/storage
```

6. If symlink missing:

```bash
php artisan storage:link --force
```

7. Redeploy after fixing settings

## 16. Notes From This Debugging Session (What Actually Happened)

- Upload files were successfully stored in `/app/storage/app/public/news`
- Browser requested `/storage/news/...`
- Requests returned `404` because `/app/public/storage` symlink was missing in the running container
- Manual `php artisan storage:link --force` fixed it temporarily
- Container restarts changed runtime instances, so manual SSH fixes did not persist
- Permanent fix was to recreate the symlink in Railway `Start Command`

