# BUSCO Web App System Documentation (Production Record)

Updated: February 25, 2026
Status: Production reference (Laravel + PostgreSQL + Railway)

## 1. Purpose of This Document

This file is the project-level technical documentation for the deployed `busco-web-app` system.

It documents:

- Project structure and file purposes
- Application architecture and request flow
- Models, controllers, middleware, providers, and methods
- Routes and feature modules
- Database schema, migrations, factories, and seeders
- Config and environment usage
- Frontend views/assets
- Packages and toolchain
- Testing coverage
- Production deployment/operations notes (Railway + Postgres + uploads)

## 2. Scope and Exclusions

This documentation covers project-owned application files used to build and run the system.

Included:

- `app/`
- `bootstrap/app.php`
- `routes/`
- `database/`
- `config/`
- `resources/`
- `public/` (custom app assets and entrypoint)
- `tests/`
- Root project config files (`composer.json`, `package.json`, `vite.config.js`, `.env.example`, etc.)
- Deployment record file `docs/records/railway.md`

Excluded (not documented file-by-file):

- `vendor/` (Composer dependencies)
- `node_modules/` (npm dependencies)
- generated caches/build artifacts (`public/build`, `storage/framework/*`, `.phpunit.result.cache`)
- historical docs not directly required for runtime behavior

## 3. System Overview

### 3.1 Application summary

The BUSCO Web App is a Laravel-based website with:

- Public pages (home, about, services, process, contact)
- Public news module
- Public careers/job openings module
- Public Quedan price page
- Admin panel for managing News, Jobs, Quedan prices, and admin profile
- Admin authentication and password reset

### 3.2 Tech stack

- Backend: PHP 8.2+, Laravel 12
- Database: PostgreSQL (production on Railway)
- Frontend rendering: Blade templates
- Frontend tooling: Vite + Tailwind CSS v4
- Hosting: Railway (web service + Postgres + persistent volume)

### 3.3 Key production behavior

- Uploaded news images are stored on Laravel `public` disk (`storage/app/public/news/...`)
- Public access is via `/storage/...` URL using `public/storage` symlink
- Railway runtime requires `php artisan storage:link --force` at startup to ensure the symlink exists

## 4. High-Level Request/Feature Flow

### 4.1 Public request flow

1. Request enters `public/index.php`
2. Laravel boots from `bootstrap/app.php`
3. Route resolves in `routes/web.php`
4. Controller queries Eloquent models (`News`, `JobOpening`, `QuedanPrice`)
5. Blade view renders HTML
6. Static assets load from `public/css`, `public/js`, `public/img`

### 4.2 Admin request flow

1. Route enters `/admin/*`
2. Guest routes handle login and password reset
3. Protected routes require middleware chain:
   - `auth`
   - `admin` (`EnsureUserIsAdmin`)
   - `admin.inactivity` (`AdminInactivityTimeout`)
4. Admin controllers validate input, update models, redirect with flash messages

### 4.3 File upload flow (News)

1. Admin uploads JPG/JPEG files in news form (`gallery_images[]`)
2. `Admin\NewsController::storeUploadedGalleryImages()` stores files on `public` disk under `news/`
3. DB stores relative paths (`news/filename.jpg`) in `image` and `images`
4. `App\Models\News` accessors build public URLs
5. Browser requests `/storage/news/<filename>.jpg`
6. Symlink `public/storage -> storage/app/public` serves file

## 5. Root Project File Reference

### 5.1 Root files (purpose)

- `.editorconfig`: editor formatting rules (UTF-8, LF, 4 spaces; markdown trailing whitespace exception)
- `.env`: local environment values (not committed; deployment values live in Railway variables)
- `.env.example`: environment variable template and project defaults
- `.gitattributes`: Git diff/export settings (language-aware diffs for Blade/CSS/PHP/MD)
- `.gitignore`: ignore rules for local env, build outputs, storage symlink, vendor/node_modules, IDE files
- `.vscode/settings.json`: local workspace IntelliSense config (excludes `vendor` from Intelephense indexing)
- `.phpunit.result.cache`: local PHPUnit cache file (generated)
- `artisan`: Laravel CLI entrypoint
- `composer.json`: PHP dependencies, autoload config, Composer scripts
- `composer.lock`: exact locked PHP dependency versions (generated, committed)
- `package.json`: frontend build dependencies and npm scripts
- `package-lock.json`: exact locked npm dependency versions (generated, committed)
- `phpunit.xml`: test suite configuration and test environment overrides
- `README.md`: default Laravel README (not project-specific)
- `vite.config.js`: Vite + Laravel plugin + Tailwind integration

### 5.2 Top-level directories (purpose)

- `api/`: currently empty placeholder directory
- `.vscode/`: local editor workspace settings
- `app/`: application logic (models, controllers, middleware, providers)
- `bootstrap/`: app bootstrap configuration
- `config/`: runtime framework/application configuration
- `database/`: migrations, seeders, factories
- `docs/`: project documentation and deployment records
- `node_modules/`: npm dependencies (generated)
- `public/`: web root (entrypoint, static assets, upload symlink)
- `resources/`: source Blade views and frontend source assets
- `routes/`: web and console route definitions
- `storage/`: framework temp data/logs and local file storage
- `tests/`: unit/feature test suite
- `vendor/`: Composer dependencies (generated)

## 6. Package and Tooling Reference

### 6.1 Composer packages (`composer.json`)

Runtime (`require`):

- `php:^8.2`: PHP runtime version requirement
- `laravel/framework:^12.0`: core framework (routing, ORM, Blade, auth, etc.)
- `laravel/tinker:^2.10.1`: REPL/debugging command (`php artisan tinker`)

Development (`require-dev`):

- `fakerphp/faker`: fake test/dev data generation
- `laravel/pail`: live log tailing utility
- `laravel/pint`: code style formatter
- `laravel/sail`: Docker-based local dev support (optional)
- `mockery/mockery`: mocking library for tests
- `nunomaduro/collision`: improved CLI exception output
- `phpunit/phpunit`: testing framework

Composer scripts (key project usage):

- `setup`: installs dependencies, creates `.env`, generates key, migrates, installs/builds frontend
- `dev`: starts Laravel server, queue listener, log tail, and Vite using `concurrently`
- `test`: clears config cache then runs test suite

### 6.2 Frontend packages (`package.json`)

Scripts:

- `npm run build`: production build via Vite
- `npm run dev`: Vite development server

Dev dependencies:

- `vite`: build tool
- `laravel-vite-plugin`: integrates Vite with Laravel Blade
- `@tailwindcss/vite`: Tailwind v4 Vite integration
- `tailwindcss`: CSS utility engine
- `axios`: JS HTTP client (initialized in `resources/js/bootstrap.js`)
- `concurrently`: runs multiple dev processes in Composer `dev` script

## 7. Bootstrap and Routing Files

### 7.1 `bootstrap/app.php`

Purpose:

- Bootstraps Laravel application and central middleware aliases/configuration

Key behavior:

- Registers web and console route files
- Enables health endpoint `/up`
- Trusts proxies (`trustProxies('*')`) for Railway/reverse proxy support
- Redirects guest users to admin login for `/admin*` routes, otherwise to home
- Aliases custom middleware:
  - `admin` => `EnsureUserIsAdmin`
  - `admin.inactivity` => `AdminInactivityTimeout`

### 7.2 `routes/web.php`

Purpose:

- Declares all public and admin HTTP routes

Public route groups:

- Static pages via `Route::view`: `about`, `services`, `process`, `contact`
- Homepage: `HomeController@index`
- News: `NewsPublicController@index/show`
- Quedan page: `QuedanPublicController@index`
- Careers: `CareerPublicController@index/show`
- Community shortcut redirects to news category `CSR / Community`

Admin route groups:

- `/admin/login` and login submission
- Password reset request/reset routes (guest-only)
- Authenticated admin-only routes for dashboard, news, jobs, quedan, profile, logout

### 7.3 `routes/console.php`

Purpose:

- Defines Artisan console closure commands

Defined command:

- `inspire`: prints a random quote using Laravel's `Inspiring` helper

### 7.4 Route inventory snapshot (local `php artisan route:list`)

The project currently exposes 44 routes (including admin CRUD and auth routes). Generate fresh list anytime with:

```bash
php artisan route:list --except-vendor
```

## 8. Application Class Reference (`app/`)

### 8.1 `app/Http/Controllers/Controller.php`

Purpose:

- Base abstract controller class for app controllers

Methods:

- None (placeholder base class)

### 8.2 `app/Providers/AppServiceProvider.php`

Purpose:

- Registers application-level bootstrapping logic (rate limiting + password reset URL customization)

Methods:

- `register(): void`
  - Placeholder; no custom service bindings currently
- `boot(): void`
  - Registers rate limiters:
    - `admin-login`
    - `admin-password-email`
    - `admin-password-reset`
  - Builds limiter keys using email + IP to reduce abuse
  - Overrides password reset URL generation to use admin route `admin.password.reset`

### 8.3 Middleware

#### `app/Http/Middleware/EnsureUserIsAdmin.php`

Purpose:

- Blocks non-admin authenticated users from admin panel routes

Methods:

- `handle(Request $request, Closure $next): Response`
  - Reads authenticated user
  - Verifies `isAdmin()` method exists and returns true
  - Aborts with HTTP 403 if unauthorized
  - Continues request if valid admin

#### `app/Http/Middleware/AdminInactivityTimeout.php`

Purpose:

- Logs out idle admins after 5 minutes of inactivity

Constants:

- `SESSION_KEY = admin_last_activity_at`
- `TIMEOUT_SECONDS = 300`

Methods:

- `handle(Request $request, Closure $next): Response|RedirectResponse`
  - Applies only to authenticated admins
  - Reads last activity timestamp from session
  - Logs out, invalidates session, regenerates CSRF token on timeout
  - Redirects to admin login with warning
  - Updates session activity timestamp when still active

### 8.4 Models

#### `app/Models/User.php`

Purpose:

- Authentication user model with admin-role check

Key properties:

- `$fillable`: `name`, `email`, `password`, `role`
- `$hidden`: `password`, `remember_token`

Methods:

- `casts(): array`
  - Casts `email_verified_at` to datetime
  - Casts `password` as hashed
- `isAdmin(): bool`
  - Returns true when `role === 'admin'`

#### `app/Models/QuedanPrice.php`

Purpose:

- Stores weekly Quedan price records (active + archived history)

Key properties:

- `$fillable`: price metadata, status, notes
- `$casts`: numeric/date casting for price and dates

Methods:

- `scopeActive($query)`
  - Filters records with `status = active`
- `scopeArchived($query)`
  - Filters archived records and orders newest first
- `getFormattedPriceAttribute(): string`
  - Returns formatted `PHP x,xxx.xx` string
- `getTrendClassAttribute(): string`
  - Maps trend code to CSS class (`trend-up`, `trend-down`, `trend-neutral`)
- `getTrendIconAttribute(): string`
  - Maps trend code to simple symbol (`^`, `v`, `-`)

Constants:

- Status: `STATUS_ACTIVE`, `STATUS_ARCHIVED`
- Trend: `TREND_UP`, `TREND_DOWN`, `TREND_NO_CHANGE`

#### `app/Models/News.php`

Purpose:

- News/article model supporting soft deletes, category/status filtering, featured flag, and image gallery paths

Key properties:

- Uses `SoftDeletes`
- `$fillable`: article fields + image fields
- `$casts`: `is_featured` boolean, `images` JSON array, `deleted_at` datetime

Methods:

- `scopePublished($query)`
  - Filters to published articles
- `scopeFeatured($query)`
  - Filters featured articles
- `scopeCategory($query, string $category)`
  - Filters by category
- `getImageUrlAttribute(): string`
  - Returns public URL for primary image, with fallback
- `getAllImagePathsAttribute(): array`
  - Merges legacy `image` and JSON `images[]` into a unique list
- `getPrimaryImagePathAttribute(): ?string`
  - Returns first image path from merged list
- `getGalleryImagesAttribute(): array`
  - Returns array of `{path, url}` for all gallery images
- `getExcerptAttribute(): string`
  - Builds trimmed plain-text excerpt from subtitle or content
- `resolveImageUrl(?string $path): string` (protected)
  - Accepts absolute URLs as-is
  - Checks existence on `public` disk
  - Returns `asset('storage/...')` for stored file
  - Falls back to `asset('images/no-image.svg')`

Constants:

- `CATEGORIES`: announcements/achievements/events/CSR
- `STATUS_DRAFT`, `STATUS_PUBLISHED`

#### `app/Models/JobOpening.php`

Purpose:

- Job posting model for public careers page and admin management

Key properties:

- `$fillable`: posting metadata, content fields, status, slug
- `$casts`: `posted_at`, `deadline_at` as dates

Methods:

- `booted(): void` (protected static)
  - Auto-generates unique slug on create
  - Regenerates slug when title changes on update
- `scopePubliclyOpen($query)`
  - Filters status `open` (publicly visible)
- `scopeStatus($query, string $status)`
  - Filters by specific status
- `getRouteKeyName(): string`
  - Uses `slug` for route model binding (`/careers/{jobOpening}`)
- `getStatusLabelAttribute(): string`
  - Human-friendly label for status
- `getShortDescriptionAttribute(): string`
  - 140-char text preview from summary/description
- `generateUniqueSlug(string $title, ?int $ignoreId = null): string` (protected static)
  - Produces unique slug with numeric suffix if needed

Constants:

- Statuses: `open`, `hired`, `closed`, `draft`
- Arrays: `STATUSES`, `EMPLOYMENT_TYPES`

## 9. Controller Reference (Admin)

### 9.1 `app/Http/Controllers/Admin/AdminController.php`

Purpose:

- Admin dashboard statistics and summary widgets

Methods:

- `dashboard(): View`
  - Checks table existence defensively using `Schema::hasTable`
  - Builds stats for news, jobs, active Quedan, and latest news
  - Returns `admin.dashboard` with `$stats`

### 9.2 `app/Http/Controllers/Admin/AuthController.php`

Purpose:

- Admin login/logout using Laravel default web guard

Methods:

- `showLoginForm(Request $request): View|RedirectResponse`
  - Redirects already-authenticated admins to dashboard
  - Otherwise renders admin login page
- `login(Request $request): RedirectResponse`
  - Validates email/password/remember
  - Attempts login via `Auth::attempt`
  - Regenerates session on success
  - Rejects authenticated non-admin users and logs them out
  - Redirects to intended admin page/dashboard
- `logout(Request $request): RedirectResponse`
  - Logs out user
  - Invalidates session and regenerates token
  - Redirects to admin login with success flash

### 9.3 `app/Http/Controllers/Admin/PasswordResetController.php`

Purpose:

- Admin password reset request and reset flow using Laravel password broker

Methods:

- `showLinkRequestForm(): View`
  - Renders forgot-password page
- `sendResetLinkEmail(Request $request): RedirectResponse`
  - Validates email
  - Looks up user and silently ignores non-admin/non-existent accounts (anti-enumeration)
  - Sends reset link through Laravel broker for admin users
- `showResetForm(Request $request, string $token): View`
  - Renders reset page with token and email query param
- `reset(Request $request): RedirectResponse`
  - Validates token/email/password+confirmation
  - Restricts reset to admin users
  - Updates hashed password and remember token
  - Fires `PasswordReset` event
  - Redirects to admin login on success

### 9.4 `app/Http/Controllers/Admin/ProfileController.php`

Purpose:

- Admin profile and password self-service actions

Methods:

- `showProfile(): View`
  - Renders profile page
- `updateProfile(Request $request): RedirectResponse`
  - Validates `name`
  - Updates current user name
  - Redirects with success flash
- `updatePassword(Request $request): RedirectResponse`
  - Validates current/new password + confirmation
  - Verifies current password hash
  - Updates password hash
  - Redirects with success flash

### 9.5 `app/Http/Controllers/Admin/NewsController.php`

Purpose:

- Full admin news management (CRUD + soft-delete restore + publish toggle + gallery uploads)

Methods:

- `index(Request $request): View`
  - Lists news (including trashed)
  - Supports filters: `category`, `status`, `trashed`
  - Returns paginated admin list with filter state
- `create(): View`
  - Renders create form with category list
- `store(Request $request): RedirectResponse`
  - Validates payload
  - Stores uploaded gallery images
  - Maps images to `image` + `images`
  - Creates article and redirects with success flash
- `edit(News $news): View`
  - Renders edit form for selected article
- `update(Request $request, News $news): RedirectResponse`
  - Validates payload
  - Merges remaining images + uploaded images
  - Supports `remove_images[]`
  - Enforces max 5 gallery images
  - Deletes removed files from storage
  - Updates article and redirects
- `destroy(News $news): RedirectResponse`
  - Soft deletes article and redirects
- `restore(int $id): RedirectResponse`
  - Restores soft-deleted article by ID
- `toggleStatus(News $news): RedirectResponse`
  - Toggles between `published` and `draft`
- `validatedNewsData(Request $request): array` (protected)
  - Central validation rules for admin create/update form
- `storeUploadedGalleryImages(Request $request): array` (protected)
  - Stores uploaded gallery images to `public` disk under `news/`
- `applyGalleryImagesToPayload(array $payload, array $images): array` (protected)
  - Assigns normalized gallery array and primary image field

### 9.6 `app/Http/Controllers/Admin/JobController.php`

Purpose:

- Admin job opening management (CRUD and filtering)

Methods:

- `index(Request $request): View`
  - Lists jobs with filters (`status`, `department`, `employment_type`, `search`)
  - Returns paginated results and filter options
- `create(): View`
  - Renders job create form with status/type options and default HR email
- `store(Request $request): RedirectResponse`
  - Validates input
  - Forces `application_email`
  - Defaults `posted_at` if missing
  - Creates job and redirects
- `edit(JobOpening $job): View`
  - Renders edit form for a job
- `update(Request $request, JobOpening $job): RedirectResponse`
  - Validates input
  - Forces `application_email`
  - Updates job and redirects
- `destroy(JobOpening $job): RedirectResponse`
  - Deletes job record and redirects
- `validatedJobData(Request $request): array` (protected)
  - Central validation rules for create/update

### 9.7 `app/Http/Controllers/Admin/QuedanController.php`

Purpose:

- Admin Quedan posting, editing, archival management, and trend recalculation

Methods:

- `index(): View`
  - Shows active Quedan record + paginated archived history
- `create(): View`
  - Shows create form and latest active record for comparison
- `edit(QuedanPrice $quedan): View`
  - Shows edit form and previous chronological record for context
- `store(Request $request): RedirectResponse`
  - Validates input
  - Creates new active record
  - Archives previous active record (if any)
  - Computes price difference and trend inside DB transaction
- `update(Request $request, QuedanPrice $quedan): RedirectResponse`
  - Validates input
  - Updates selected record
  - Recalculates full price series chain in transaction
- `destroy(QuedanPrice $quedan): RedirectResponse`
  - Prevents deletion of active record
  - Deletes archived record only
  - Recalculates series after deletion
- `validateQuedanPayload(Request $request): array` (protected)
  - Validation rules for dates, price, notes
- `recalculateSeries(): void` (protected)
  - Recomputes `difference` and `trend` for all records chronologically

## 10. Controller Reference (Public Site)

### 10.1 `app/Http/Controllers/PublicSite/HomeController.php`

Purpose:

- Public homepage dynamic sections (latest news + current Quedan)

Methods:

- `index(): View`
  - Safely checks table existence (`news`, `quedan_prices`)
  - Loads latest published news (max 3)
  - Loads active and previous Quedan record
  - Returns `pages.home`

### 10.2 `app/Http/Controllers/PublicSite/NewsPublicController.php`

Purpose:

- Public news listing and article detail pages

Methods:

- `index(Request $request): View`
  - Lists published news only
  - Optional category filter
  - Returns paginated news page
- `show(News $news): View`
  - 404s when requested article is not published
  - Loads related published articles from same category
  - Returns news detail page

### 10.3 `app/Http/Controllers/PublicSite/QuedanPublicController.php`

Purpose:

- Public Quedan announcement page with current record and historical list

Methods:

- `index(): View`
  - Loads active record, previous archived record, paginated archived history
  - Returns `pages.quedan`

### 10.4 `app/Http/Controllers/PublicSite/CareerPublicController.php`

Purpose:

- Public careers listing and job detail pages

Methods:

- `index(Request $request): View`
  - Lists open jobs only
  - Filters by department, employment type, keyword search
  - Returns paginated careers page with filter data
- `show(JobOpening $jobOpening): View`
  - 404s when job status is not `open`
  - Loads related open jobs from same department
  - Returns job detail page

## 11. Database Layer Reference (`database/`)

### 11.1 Migrations

#### `database/migrations/0001_01_01_000000_create_users_table.php`

Purpose:

- Creates core auth tables: `users`, `password_reset_tokens`, `sessions`

Methods:

- `up(): void`
  - Creates all three tables
- `down(): void`
  - Drops all three tables

#### `database/migrations/0001_01_01_000001_create_cache_table.php`

Purpose:

- Creates database-backed cache tables for `CACHE_STORE=database`

Methods:

- `up(): void`
  - Creates `cache` and `cache_locks`
- `down(): void`
  - Drops `cache` and `cache_locks`

#### `database/migrations/0001_01_01_000002_create_jobs_table.php`

Purpose:

- Creates queue infrastructure tables for `QUEUE_CONNECTION=database`

Methods:

- `up(): void`
  - Creates `jobs`, `job_batches`, `failed_jobs`
- `down(): void`
  - Drops queue tables

#### `database/migrations/2026_02_24_000001_add_role_to_users_table.php`

Purpose:

- Adds `role` column to users for admin authorization

Methods:

- `up(): void`
  - Adds `role` (default `admin`)
- `down(): void`
  - Drops `role` column

#### `database/migrations/2026_02_24_000002_create_news_table.php`

Purpose:

- Creates `news` table with gallery JSON support and soft deletes

Methods:

- `up(): void`
  - Creates article fields + `image`, `images`, `status`, `is_featured`, timestamps, soft deletes
- `down(): void`
  - Drops `news`

#### `database/migrations/2026_02_24_000003_create_quedan_prices_table.php`

Purpose:

- Creates `quedan_prices` table for active/archived weekly price records

Methods:

- `up(): void`
  - Creates pricing, date, diff/trend, note, status fields
- `down(): void`
  - Drops `quedan_prices`

#### `database/migrations/2026_02_24_000004_create_job_openings_table.php`

Purpose:

- Creates `job_openings` table for careers module

Methods:

- `up(): void`
  - Creates slugged job posting schema with status/type/date/content fields
- `down(): void`
  - Drops `job_openings`

### 11.2 Factory

#### `database/factories/UserFactory.php`

Purpose:

- Generates test/dev user records

Methods:

- `definition(): array`
  - Returns fake name/email/password/remember token
- `unverified(): static`
  - Returns factory state with `email_verified_at = null`

### 11.3 Seeders

#### `database/seeders/DatabaseSeeder.php`

Purpose:

- Central seeder orchestrator for admin/news/jobs/quedan demo data

Methods:

- `run(): void`
  - Calls `AdminSeeder`, `NewsSeeder`, `JobSeeder`, `QuedanSeeder`

#### `database/seeders/AdminSeeder.php`

Purpose:

- Optional bootstrap admin creation controlled by env vars (safe for production handover)

Methods:

- `run(): void`
  - Requires `ADMIN_SEED_ENABLED=true`
  - Validates required env vars (`ADMIN_SEED_NAME`, `ADMIN_SEED_EMAIL`, `ADMIN_SEED_PASSWORD`)
  - Rejects invalid email
  - Skips if admin already exists
  - Creates admin user with role `admin`

#### `database/seeders/NewsSeeder.php`

Purpose:

- Seeds demo/sample news records (currently partial template content; idempotent pattern)

Methods:

- `run(): void`
  - Iterates seeded articles
  - Uses `News::updateOrCreate(...)`
  - Backdates `created_at/updated_at`
  - Prints info message

Note:

- File includes comments/placeholders indicating it may be intentionally abbreviated and should be reviewed before relying on it for full demo content.

#### `database/seeders/JobSeeder.php`

Purpose:

- Seeds realistic sample job openings across departments and statuses

Methods:

- `run(): void`
  - Builds dated sample jobs with mixed statuses (`open`, `hired`, `closed`, `draft`)
  - Uses `updateOrCreate` by title
  - Assigns slug and default application email

#### `database/seeders/QuedanSeeder.php`

Purpose:

- Seeds sequential Quedan history and computes differences/trends

Methods:

- `run(): void`
  - Creates multiple historical Quedan rows
  - Calculates `difference` and `trend` relative to previous price
  - Marks last row as active, others archived

## 12. Config Reference (`config/`)

All `config/*.php` files are present and used. Most are Laravel defaults with project-specific runtime choices in env values.

### 12.1 `config/app.php`

Purpose:

- Core app settings (name, env, debug, URL, locale, maintenance)

Project-relevant notes:

- `app.url` depends on `APP_URL` (critical for storage URL generation)
- `debug` should be `false` in production

### 12.2 `config/auth.php`

Purpose:

- Auth guard/provider/password broker configuration

Project-relevant notes:

- Uses default `web` session guard
- `users` provider points to `App\Models\User`
- Password resets use `password_reset_tokens`

### 12.3 `config/cache.php`

Purpose:

- Cache store definitions and default selection

Project-relevant notes:

- Default store reads `CACHE_STORE` (often `database` in production)

### 12.4 `config/database.php`

Purpose:

- DB connections, migrations table, and Redis settings

Project-relevant notes:

- Supports `pgsql` and uses `DB_SSLMODE` (`prefer` by default)
- Production Railway setup uses `DB_CONNECTION=pgsql`

### 12.5 `config/filesystems.php`

Purpose:

- Filesystem disks and symlink configuration

Project-relevant notes:

- `public` disk root is `storage/app/public`
- Public URL uses `APP_URL + /storage`
- Symlink config: `public/storage -> storage/app/public`
- This file is central to uploaded news image rendering

### 12.6 `config/logging.php`

Purpose:

- Log channels and default log behavior

Project-relevant notes:

- Default channel reads `LOG_CHANNEL`
- `stack` channel includes channels from `LOG_STACK`

### 12.7 `config/mail.php`

Purpose:

- Mail transport and sender configuration

Project-relevant notes:

- Default mailer is env-driven (`MAIL_MAILER`)
- Password reset flow depends on working mail transport outside local dev

### 12.8 `config/queue.php`

Purpose:

- Queue connection and failed job behavior

Project-relevant notes:

- Default reads `QUEUE_CONNECTION` (project often uses `database`)
- Queue tables are created by base migration

### 12.9 `config/services.php`

Purpose:

- Credentials for optional third-party services (Postmark/Resend/SES/Slack)

Project-relevant notes:

- No custom service integration logic currently visible in app code

### 12.10 `config/session.php`

Purpose:

- Session driver/cookie/session table behavior

Project-relevant notes:

- Default session driver is `database`
- Requires `sessions` table from base migration
- Cookie security settings are env-driven

## 13. Frontend Source and Public Assets

### 13.1 Vite and source assets

#### `vite.config.js`

Purpose:

- Configures Vite with Laravel plugin and Tailwind plugin

Behavior:

- Build inputs: `resources/css/app.css`, `resources/js/app.js`
- Enables automatic refresh
- Ignores `storage/framework/views` in dev watcher

#### `resources/js/app.js`

Purpose:

- Frontend entrypoint; imports bootstrap JS setup

#### `resources/js/bootstrap.js`

Purpose:

- Initializes Axios globally on `window`
- Sets `X-Requested-With: XMLHttpRequest` header

#### `resources/css/app.css`

Purpose:

- Tailwind CSS v4 source file for Vite builds

Behavior:

- Imports Tailwind
- Defines content sources (`resources/**/*.blade.php`, JS, compiled view cache)
- Sets theme font token for sans font

### 13.2 Public web root files

#### `public/index.php`

Purpose:

- Laravel HTTP entrypoint (bootstraps app and handles requests)

#### `public/robots.txt`

Purpose:

- Search crawler instructions file (static)

#### `public/favicon.ico`

Purpose:

- Browser tab/site icon

### 13.3 Public custom CSS/JS

#### `public/css/busco-static.css`

Purpose:

- Main handcrafted site/admin visual styles for public pages and shared UI components

Observed behavior (high level):

- Defines design tokens (`--green`, `--gold`, spacing, shadows, radius)
- Styles navigation, layout shells, buttons, typography, responsive behavior, and page sections

#### `public/css/error.css`

Purpose:

- Placeholder/empty stylesheet for error pages (currently zero bytes)

#### `public/js/busco-static.js`

Purpose:

- Public UI behavior enhancements (non-Vite static JS)

Behavior:

- Mobile nav toggle open/close
- Auto-close nav on link click / outside click
- Nav scroll shadow effect (`.site-nav.scrolled`)
- Reveal-on-scroll animation using `IntersectionObserver` for `.reveal`

### 13.4 Public image assets (`public/img/` and `public/images/`)

Purpose:

- Static image assets used by marketing/public pages and fallbacks

Files in `public/img/`:

- `achievements.webp`
- `announcement.webp`
- `bagging.webp`
- `busco_logo.jpg`
- `busco_logo.webp`
- `busco_map.webp`
- `cane_cutting.webp`
- `centrifugation.webp`
- `clarification.webp`
- `crystallization.webp`
- `evaporation.webp`
- `hero_img.webp`
- `juice_extraction.webp`
- `training_events.webp`

Files in `public/images/`:

- `no-image.svg` (fallback for missing News images)

## 14. Blade View Reference (`resources/views/`)

This section documents every Blade view file by purpose and expected data.

### 14.1 Layouts

- `resources/views/layouts/app.blade.php`
  - Public site layout wrapper
  - Loads shared navbar/footer and static public assets
  - Uses `$activePage` for nav highlighting
- `resources/views/layouts/admin.blade.php`
  - Admin panel layout wrapper
  - Renders page header, sidebar, flash messages, and admin content area
  - Consumes section values like title/header/subtitle

### 14.2 Shared partials

- `resources/views/partials/navbar.blade.php`
  - Public site top navigation
  - Uses `$activePage`
- `resources/views/partials/footer.blade.php`
  - Public site footer
- `resources/views/partials/flash-messages.blade.php`
  - Renders success/warning/error/session validation messages
- `resources/views/partials/custom-pagination.blade.php`
  - Reusable pagination UI with configurable display options
  - Consumes `$paginator` and optional UI sizing vars

### 14.3 Error pages

- `resources/views/errors/404.blade.php`
  - Custom "Page Not Found" page
- `resources/views/errors/429.blade.php`
  - Custom "Too Many Requests" page (rate limiting feedback)
- `resources/views/errors/500.blade.php`
  - Custom server error page

### 14.4 Public pages (static/semi-static)

- `resources/views/pages/about.blade.php`
  - Company overview/about content page
- `resources/views/pages/services.blade.php`
  - Services and operations overview page
- `resources/views/pages/process.blade.php`
  - Sugar milling process walkthrough page
- `resources/views/pages/contact.blade.php`
  - Contact information page
- `resources/views/pages/community.blade.php`
  - Community/training/CSR-themed content page

### 14.5 Public pages (dynamic)

- `resources/views/pages/home.blade.php`
  - Homepage with dynamic news previews and Quedan summary
  - Expects `$latestNews`, `$activeQuedan`, `$previousQuedan`
- `resources/views/pages/quedan.blade.php`
  - Public Quedan price page
  - Expects `$activePrice`, `$previousPrice`, `$history`
- `resources/views/pages/careers.blade.php`
  - Public job listings with filters
  - Expects `$jobs`, `$departments`, `$employmentTypes`, `$filters`
- `resources/views/pages/careers/show.blade.php`
  - Job detail page and related jobs
  - Expects `$job`, `$relatedJobs`
- `resources/views/pages/news/index.blade.php`
  - Public news listing with category filter and pagination
  - Expects `$news`, `$categories`, `$selectedCategory`
- `resources/views/pages/news/show.blade.php`
  - News article detail with gallery/related items
  - Expects `$news`, `$related`

### 14.6 Admin auth views

- `resources/views/admin/auth/login.blade.php`
  - Admin login form UI
- `resources/views/admin/auth/forgot-password.blade.php`
  - Password reset request form
- `resources/views/admin/auth/reset-password.blade.php`
  - Password reset submission form
  - Expects `$token`, `$email`

### 14.7 Admin shell partials

- `resources/views/admin/partials/sidebar.blade.php`
  - Admin sidebar navigation and user identity display
  - Derives initials/name from authenticated user

### 14.8 Admin dashboard/profile

- `resources/views/admin/dashboard.blade.php`
  - Admin dashboard cards/stats/quick actions
  - Expects `$stats`
- `resources/views/admin/profile/index.blade.php`
  - Admin profile update + password change page
- `resources/views/admin/profile/change-password.blade.php`
  - Legacy/alternate password change view (route redirects to profile index)

### 14.9 Admin news module views

- `resources/views/admin/news/index.blade.php`
  - News management listing with filters, actions, soft-delete state
  - Expects paginated `$news`, `$categories`, `$filters`
- `resources/views/admin/news/create.blade.php`
  - Create news page wrapper
- `resources/views/admin/news/edit.blade.php`
  - Edit news page wrapper for `$news`
- `resources/views/admin/news/_form.blade.php`
  - Shared create/edit form
  - Handles title/subtitle/content/category/status/featured/gallery uploads/remove images
  - Supports up to 5 JPG/JPEG images

### 14.10 Admin jobs module views

- `resources/views/admin/jobs/index.blade.php`
  - Job management list with filters and CRUD actions
  - Expects `$jobs`, `$departments`, `$employmentTypes`, `$statuses`, `$filters`
- `resources/views/admin/jobs/create.blade.php`
  - Create job page wrapper
- `resources/views/admin/jobs/edit.blade.php`
  - Edit job page wrapper for `$job`
- `resources/views/admin/jobs/_form.blade.php`
  - Shared create/edit form for job fields and status/type metadata

### 14.11 Admin Quedan module views

- `resources/views/admin/quedan/index.blade.php`
  - Active Quedan display + archived records table + actions
  - Expects `$active`, `$archived`
- `resources/views/admin/quedan/create.blade.php`
  - Post new Quedan price form
  - Expects `$previousActive`
- `resources/views/admin/quedan/edit.blade.php`
  - Edit Quedan record form with previous reference row
  - Expects `$quedan`, `$previousRecord`

## 15. Tests Reference (`tests/`)

### 15.1 `tests/TestCase.php`

Purpose:

- Base test class extending Laravel's testing base class

### 15.2 Feature tests

#### `tests/Feature/AdminAccessSecurityTest.php`

Purpose:

- Verifies admin route protection and error-page behavior

Test methods:

- `test_admin_routes_redirect_to_admin_login_when_guest()`
- `test_non_admin_user_is_forbidden_from_admin_dashboard()`
- `test_users_table_has_remember_token_column()`
- `test_custom_404_page_renders_when_debug_is_disabled()`

#### `tests/Feature/NewsCrudTest.php`

Purpose:

- End-to-end admin News CRUD and validation tests, including file upload behavior

Test/helper methods:

- `test_admin_can_complete_news_crud_cycle_with_publish_toggle_and_restore()`
- `test_news_store_accepts_missing_optional_image()`
- `test_news_validation_rejects_invalid_file_type_and_oversized_image()`
- `test_news_validation_rejects_invalid_category_and_status()`
- `adminUser()` (helper factory for admin auth)

Notes:

- Uses `Storage::fake('public')` to test upload logic safely
- Confirms soft delete + restore behavior

#### `tests/Feature/QuedanCrudTest.php`

Purpose:

- Validates Quedan posting, archival transitions, recalculation logic, and validation rules

Test/helper methods:

- `test_quedan_logic_handles_successive_entries_archiving_and_trends()`
- `test_quedan_validation_rules_trigger_for_invalid_input()`
- `test_active_quedan_cannot_be_deleted_but_archived_record_can()`
- `test_deleting_archived_quedan_recalculates_following_records()`
- `test_admin_can_edit_quedan_and_recalculate_following_records()`
- `postQuedan(...)` helper
- `adminUser()` helper

#### `tests/Feature/ExampleTest.php`

Purpose:

- Basic smoke test for homepage response 200

Test method:

- `test_the_application_returns_a_successful_response()`

### 15.3 Unit tests

#### `tests/Unit/ExampleTest.php`

Purpose:

- Default placeholder unit test

Test method:

- `test_that_true_is_true()`

## 16. Runtime and Environment Variables (Project-Relevant)

### 16.1 Core application vars

- `APP_NAME`
- `APP_ENV`
- `APP_KEY`
- `APP_DEBUG`
- `APP_URL`

### 16.2 Database vars (Postgres on Railway)

- `DB_CONNECTION=pgsql`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `DB_SSLMODE` (project supports `prefer` / `require`)

### 16.3 Session/cache/queue vars

- `SESSION_DRIVER` (typically `database`)
- `CACHE_STORE` (typically `database`)
- `QUEUE_CONNECTION` (typically `database`)

### 16.4 Filesystem/upload vars

- `FILESYSTEM_DISK=public` (recommended for production and image uploads)

### 16.5 Admin seeding vars (optional one-time bootstrap)

- `ADMIN_SEED_ENABLED`
- `ADMIN_SEED_NAME`
- `ADMIN_SEED_EMAIL`
- `ADMIN_SEED_PASSWORD`

## 17. Production Deployment and Ops Notes (Railway)

Detailed deployment troubleshooting record:

- `docs/records/railway.md`

### 17.1 Proven working Railway commands

Build Command:

```bash
composer install --no-dev --optimize-autoloader --no-interaction && npm ci && npm run build
```

Pre-deploy Command:

```bash
php artisan optimize:clear && php artisan migrate --force
```

Start Command (critical for uploads):

```bash
php artisan storage:link --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

### 17.2 Volume mount (critical for uploads)

- Railway volume mount path: `/app/storage/app/public`

### 17.3 Upload issue root cause (resolved)

Observed production issue:

- Uploaded files existed in `/app/storage/app/public/news/...`
- Browser requests `/storage/news/...` returned `404`

Root cause:

- Missing runtime symlink `/app/public/storage`

Fix:

- Recreate symlink on every app start using `php artisan storage:link --force` in Start Command

## 18. Security and Operational Notes

- Admin authorization is role-based (`User::isAdmin()`)
- Non-admin authenticated users are blocked from admin routes (`403`)
- Admin login and password reset routes are rate-limited
- Admin inactivity auto-logout is set to 5 minutes
- Password reset flow avoids leaking whether an account exists/is admin
- Production should keep `APP_DEBUG=false`

## 19. Known Maintenance Considerations

- `database/seeders/NewsSeeder.php` appears intentionally abbreviated/commented; review before using as a full production seed source
- `README.md` is still the default Laravel README and can be replaced with a project-specific README
- `public/css/error.css` is empty (safe, but can be removed or populated if dedicated error styles are desired)
- `resources/views/admin/profile/change-password.blade.php` exists while route redirects password page to profile index; keep or remove based on UI cleanup decision

## 20. How to Keep This Documentation Updated

When making changes:

1. Update model/controller method notes when signatures/behavior change
2. Update route section after editing `routes/web.php`
3. Update migration/schema section for new tables/columns
4. Update view reference when adding/removing Blade files
5. Update package/tooling section when dependencies or scripts change
6. Update Railway/deployment notes when deployment commands or infra changes

Recommended quick checks after changes:

```bash
php artisan route:list --except-vendor
php artisan test
git status
```

## 21. Quick Index by File Group

### Core backend files

- `bootstrap/app.php`
- `routes/web.php`
- `routes/console.php`
- `app/Providers/AppServiceProvider.php`
- `app/Http/Middleware/EnsureUserIsAdmin.php`
- `app/Http/Middleware/AdminInactivityTimeout.php`
- `app/Models/User.php`
- `app/Models/News.php`
- `app/Models/JobOpening.php`
- `app/Models/QuedanPrice.php`
- `app/Http/Controllers/Admin/*.php`
- `app/Http/Controllers/PublicSite/*.php`

### Database files

- `database/migrations/*.php`
- `database/seeders/*.php`
- `database/factories/UserFactory.php`

### Frontend and UI files

- `resources/views/**/*.blade.php`
- `resources/js/*.js`
- `resources/css/app.css`
- `public/css/busco-static.css`
- `public/js/busco-static.js`
- `public/img/*`
- `public/images/no-image.svg`

### Config and tooling

- `config/*.php`
- `composer.json`
- `package.json`
- `vite.config.js`
- `phpunit.xml`
- `.env.example`
