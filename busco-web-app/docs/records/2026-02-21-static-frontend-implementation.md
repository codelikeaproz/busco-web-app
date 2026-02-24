# Static Frontend Implementation Record

- Date: 2026-02-21
- Project: BUSCO Sugar Milling Co., Inc. Website
- Phase: Static Files First (No CRUD yet)

## Scope Completed

1. Implemented shared static frontend structure using Blade:
- `resources/views/layouts/app.blade.php`
- `resources/views/partials/navbar.blade.php`
- `resources/views/partials/footer.blade.php`

2. Implemented public static pages based on system architecture:
- `resources/views/pages/home.blade.php`
- `resources/views/pages/about.blade.php`
- `resources/views/pages/services.blade.php`
- `resources/views/pages/process.blade.php`
- `resources/views/pages/news/index.blade.php`
- `resources/views/pages/news/show.blade.php`
- `resources/views/pages/quedan.blade.php`
- `resources/views/pages/community.blade.php`
- `resources/views/pages/careers.blade.php`
- `resources/views/pages/contact.blade.php`

3. Applied a unified design system with News-style direction inspired by `resources/views/busco-news.html`:
- `public/css/busco-static.css`
- `public/js/busco-static.js`

4. Wired static routes only (no CRUD/controllers/models added):
- `routes/web.php`

## Functional Notes

- Implemented responsive navigation with mobile toggle.
- Implemented reveal animation hooks and shared visual components.
- Implemented static News & Achievements list page with client-side:
  - search
  - category filter
  - newest/oldest sort
  - empty-state handling
- Implemented static News article detail page template.
- Implemented static Quedan price page with historical table placeholders.

## Verification Done

- Ran: `php artisan route:list`
- Result: All intended public static routes were successfully registered.

## Explicitly Deferred (Next Phase)

- Admin authentication work
- News CRUD and DB integration
- Quedan CRUD and automatic trend computation
- File upload handling

## Reason for Deferment

This task intentionally prioritizes frontend static implementation first, as requested, before touching CRUD operations.

---

## Status Refresh (2026-02-23) - Pre-CRUD Handoff

This section updates the original 2026-02-21 record after reviewing the current codebase against:

- `docs/BUSCO_FULL_DOCUMENTATION.md`
- `docs/SYSTEM_ARCHITECTURE.md`

### Current Implementation Status (Verified)

1. Static public frontend implementation remains the current achieved phase.
- Public pages are implemented as Blade views under `resources/views/pages/*`.
- Shared layout and partials are in place:
  - `resources/views/layouts/app.blade.php`
  - `resources/views/partials/navbar.blade.php`
  - `resources/views/partials/footer.blade.php`
- Shared frontend assets are in use:
  - `public/css/busco-static.css`
  - `public/js/busco-static.js`

2. Routing is still intentionally static (no CRUD/controller flow yet).
- `routes/web.php` uses `Route::view(...)` for public pages.
- Active page state is passed to views through route parameters (e.g., `['activePage' => 'home']`).
- Named routes are already prepared for navigation (`home`, `about`, `services`, `process`, `news.index`, `news.show`, `quedan`, `community`, `careers`, `contact`).

3. Backend CRUD foundations are not yet implemented (still deferred).
- No custom controllers yet for News, Quedan, Admin Dashboard, or public dynamic pages.
- No custom models yet for `News` or `QuedanPrice`.
- No migrations yet for `news` / `quedan_prices` tables.
- No admin authentication module scaffolding (Breeze/admin routes/views) added yet.

### Verification Re-Run (2026-02-23)

- Ran: `php artisan route:list`
- Result:
  - Public static routes are registered as expected.
  - Framework/default routes also appear (e.g., `storage/{path}`, `up`).
  - Total routes shown during verification: `13`

### Maintenance Fix Applied During Review

- Fixed `routes/web.php` UTF-8 BOM issue (file now saved without BOM).
- Reason: BOM bytes can emit output before Laravel headers and cause `headers already sent` problems.

### Current Readiness for Next Phase (CRUD)

The project is in a good handoff state to start backend work using the documented workflow. The next phase should begin with the application/data-layer items from the documentation, then connect them to the already completed static frontend.

Recommended continuation order:

1. Database + migrations (`news`, `quedan_prices`, and any user/admin fields needed).
2. Models + seeders (including admin account seeding).
3. Admin authentication + protected admin routes/layout.
4. News CRUD (controller, validation, views, image handling).
5. Quedan CRUD + price-difference/trend business logic.
6. Replace static placeholders in public pages with database-driven queries.

---

## CRUD Phase Progress - Step 1 (Database + Migrations) (2026-02-24)

This entry continues the documented implementation order after the static frontend handoff.

### Completed in Step 1

1. Added database migration for admin/user role support.
- New migration: `database/migrations/2026_02_24_000001_add_role_to_users_table.php`
- Adds `role` column (`VARCHAR(50)`, default `admin`) to `users`

2. Added `news` table migration based on the documentation schema.
- New migration: `database/migrations/2026_02_24_000002_create_news_table.php`
- Includes:
  - `title`, `content`, `image`, `category`, `status`
  - `is_featured` (default `false`) — supports featured badge logic
  - `deleted_at` via SoftDeletes — supports recoverable deletes

3. Added `quedan_prices` table migration based on the documentation schema.
- New migration: `database/migrations/2026_02_24_000003_create_quedan_prices_table.php`
- Uses `decimal(10,2)` for monetary values (`price`, `difference`)
- Includes `week_label`, `effective_date`, `trend`, `notes`, `status`

### Notes / Alignment with Documentation

- `remember_token` was already present in the default Laravel users migration (`0001_01_01_000000_create_users_table.php`), so no additional migration was needed for that item.
- Quedan monetary columns were implemented with `decimal(10,2)` (not `float`) as documented.

### Verification Done (Step 1)

- Ran syntax checks:
  - `php -l database/migrations/2026_02_24_000001_add_role_to_users_table.php`
  - `php -l database/migrations/2026_02_24_000002_create_news_table.php`
  - `php -l database/migrations/2026_02_24_000003_create_quedan_prices_table.php`
- Ran non-destructive migration dry run:
  - `php artisan migrate --pretend`
- Result:
  - All 3 migrations loaded successfully
  - Generated SQL matches intended PostgreSQL schema (including `decimal(10,2)` and `deleted_at`)

### Next Documented Step

Step 2: Models + Seeders (including admin account seeding and demo data)

---

## CRUD Phase Progress - Step 2 (Models + Seeders) (2026-02-24)

This entry continues the documented implementation order after Step 1 (Database + Migrations).

### Completed in Step 2

1. Updated `User` model for admin-role support.
- Updated file: `app/Models/User.php`
- Added `role` to `$fillable`
- Added `isAdmin(): bool` helper method
- Kept Laravel password hashing cast and existing auth model behavior

2. Added `News` model with SoftDeletes, scopes, and accessors.
- New file: `app/Models/News.php`
- Includes:
  - `$fillable` for news CRUD fields
  - `$casts` for `is_featured` and `deleted_at`
  - scopes: `published()`, `featured()`, `category(...)`
  - accessors: `image_url`, `excerpt`
  - constants for categories and status values

3. Added `QuedanPrice` model with decimal/date casts and display helpers.
- New file: `app/Models/QuedanPrice.php`
- Includes:
  - `$fillable` for Quedan module fields
  - `$casts` for `price`, `difference`, `effective_date`
  - scopes: `active()`, `archived()`
  - accessors: `formatted_price`, `trend_class`, `trend_icon`
  - constants for status and trend values

4. Added seeders for admin account and demo data.
- New files:
  - `database/seeders/AdminSeeder.php`
  - `database/seeders/NewsSeeder.php`
  - `database/seeders/QuedanSeeder.php`
- `AdminSeeder` creates the default admin account only if it does not already exist
- `NewsSeeder` adds 6 demo news articles
- `QuedanSeeder` adds 4 demo Quedan records (3 archived, 1 active)

5. Updated the master seeder to call the new seeders in order.
- Updated file: `database/seeders/DatabaseSeeder.php`
- Seeder order:
  - `AdminSeeder`
  - `NewsSeeder`
  - `QuedanSeeder`

### Verification Done (Step 2)

- Ran syntax checks:
  - `php -l app/Models/User.php`
  - `php -l app/Models/News.php`
  - `php -l app/Models/QuedanPrice.php`
  - `php -l database/seeders/AdminSeeder.php`
  - `php -l database/seeders/NewsSeeder.php`
  - `php -l database/seeders/QuedanSeeder.php`
  - `php -l database/seeders/DatabaseSeeder.php`
- Re-ran `php artisan migrate --pretend` to confirm Step 1 migrations remain valid after model/seeder additions

### Execution Note

- Seeders were added and validated (syntax), but not executed yet to avoid modifying your local database without explicit confirmation.

### Next Documented Step

Step 3: Admin authentication + protected admin routes/layout

---

## CRUD Phase Progress - Step 3 (Admin Authentication + Protected Admin Routes/Layout) (2026-02-24)

This entry continues the documented implementation order after Step 2 (Models + Seeders).

### Completed in Step 3

1. Implemented custom admin authentication flow (Breeze-equivalent for current project setup).
- `laravel/breeze` is not installed in the current project, so a custom session-based admin auth flow was implemented using Laravel's built-in `Auth` facade and `web` guard.
- New controller: `app/Http/Controllers/Admin/AuthController.php`
- Routes added:
  - `GET /admin/login` (`admin.login`)
  - `POST /admin/login` (`admin.login.submit`)
  - `POST /admin/logout` (`admin.logout`)

2. Added admin-only middleware and registered middleware alias.
- New middleware: `app/Http/Middleware/EnsureUserIsAdmin.php`
- Registered alias in `bootstrap/app.php`:
  - `admin` => `EnsureUserIsAdmin::class`
- Admin route group now uses `['auth', 'admin']`

3. Added protected admin dashboard controller and route.
- New controller: `app/Http/Controllers/Admin/AdminController.php`
- Route added:
  - `GET /admin/dashboard` (`admin.dashboard`)
- Dashboard uses `News` and `QuedanPrice` models for stats
- Includes defensive `Schema::hasTable(...)` checks so the view can still load safely before migrations are executed

4. Implemented admin profile password change flow (Documented Missing Item #6).
- New controller: `app/Http/Controllers/Admin/ProfileController.php`
- Routes added:
  - `GET /admin/profile/password` (`admin.profile.password`)
  - `POST /admin/profile/password` (`admin.profile.password.update`)
- Validates current password + new password confirmation and updates hashed password

5. Added admin layout and views for Step 3.
- New layout: `resources/views/layouts/admin.blade.php`
- New partial: `resources/views/admin/partials/sidebar.blade.php`
- New views:
  - `resources/views/admin/auth/login.blade.php`
  - `resources/views/admin/dashboard.blade.php`
  - `resources/views/admin/profile/change-password.blade.php`

6. Added shared flash message partial and integrated it into layouts.
- New partial: `resources/views/partials/flash-messages.blade.php`
- Included in:
  - `resources/views/layouts/app.blade.php`
  - `resources/views/layouts/admin.blade.php`

7. Updated route/bootstrap configuration for custom auth redirects.
- `bootstrap/app.php` now redirects unauthenticated admin requests to `/admin/login`
- `routes/web.php` now includes admin route groups (`admin.index`, `admin.dashboard`, auth/profile routes)

### Verification Done (Step 3)

- Ran syntax checks:
  - `php -l app/Http/Middleware/EnsureUserIsAdmin.php`
  - `php -l app/Http/Controllers/Admin/AuthController.php`
  - `php -l app/Http/Controllers/Admin/AdminController.php`
  - `php -l app/Http/Controllers/Admin/ProfileController.php`
  - `php -l bootstrap/app.php`
  - `php -l routes/web.php`
- Ran route verification:
  - `php artisan route:list --path=admin`
- Result:
  - 7 admin routes registered successfully (`admin.index`, `admin.dashboard`, login/logout, profile password routes)

### Execution Note

- To actually use admin login locally, the database must be migrated and seeded first (Steps 1 and 2 are already implemented in code):
  - `php artisan migrate`
  - `php artisan db:seed`

### Next Documented Step

Step 4: News CRUD (controller, validation, admin views, public DB integration)

---

## CRUD Phase Progress - Step 4 (News CRUD + Public News DB Integration) (2026-02-24)

This entry continues the documented implementation order after Step 3 (Admin Authentication + Protected Admin Routes/Layout).

### Completed in Step 4

1. Implemented admin News CRUD controller (including soft-delete restore and publish toggle).
- New controller: `app/Http/Controllers/Admin/NewsController.php`
- Implemented:
  - `index()` with admin filters (`category`, `status`, `trashed`) and pagination
  - `create()` / `store()` with validation and image upload support
  - `edit()` / `update()` with validation and image replacement logic
  - `destroy()` (soft delete)
  - `restore()` (restore soft-deleted article)
  - `toggleStatus()` (draft/published toggle)

2. Added admin News CRUD routes under protected admin middleware.
- Updated `routes/web.php` to include:
  - `Route::resource('news', NewsController::class)->except(['show'])`
  - `admin.news.restore`
  - `admin.news.toggle`

3. Added admin News CRUD views.
- New files:
  - `resources/views/admin/news/index.blade.php`
  - `resources/views/admin/news/create.blade.php`
  - `resources/views/admin/news/edit.blade.php`
  - `resources/views/admin/news/_form.blade.php`
- Features included:
  - article list table
  - filters
  - create/edit form fields
  - featured checkbox
  - publish/unpublish action
  - soft-delete and restore actions

4. Implemented public database-backed News controllers/routes.
- New controller: `app/Http/Controllers/PublicSite/NewsPublicController.php`
- Replaced static public news routes in `routes/web.php` with:
  - `GET /news` → `news.index`
  - `GET /news/{news}` → `news.show`
- Public behavior:
  - list only `published` articles
  - optional category filter (`?category=...`)
  - article detail blocks access to non-published articles (`404`)
  - related articles by same category

5. Converted public News pages from static placeholders to DB-driven views while preserving the existing visual direction.
- Updated:
  - `resources/views/pages/news/index.blade.php`
  - `resources/views/pages/news/show.blade.php`
- `news.index` now renders paginated DB articles and server-side category filter UI
- `news.show` now renders dynamic article content, metadata, and related articles

6. Updated admin and public navigation touchpoints for the new News module.
- `resources/views/admin/partials/sidebar.blade.php` now includes `News Management`
- `resources/views/admin/dashboard.blade.php` quick actions now link to `Manage News`
- `resources/views/pages/home.blade.php` placeholder preview cards now link safely to `news.index` (homepage preview remains static until the later homepage integration step)

### Verification Done (Step 4)

- Ran syntax checks:
  - `php -l app/Http/Controllers/Admin/NewsController.php`
  - `php -l app/Http/Controllers/PublicSite/NewsPublicController.php`
  - `php -l routes/web.php`
- Ran route verification:
  - `php artisan route:list --path=news`
- Result:
  - Public news routes (`news.index`, `news.show`) and admin news routes (`admin.news.*`) registered successfully
  - Total news-related routes shown during verification: `10`
- Ran Blade compilation check:
  - `php artisan view:cache`
  - Result: Blade templates cached successfully

### Maintenance Fix Applied During Step 4

- Removed a reintroduced UTF-8 BOM from `routes/web.php` after route updates (verified clean again).

### Execution Notes

- To use admin/public News pages against real data, migrate and seed first if not yet done:
  - `php artisan migrate`
  - `php artisan db:seed`
- For uploaded article images to be publicly accessible:
  - `php artisan storage:link`

### Next Documented Step

Step 5: Quedan CRUD + automatic trend computation/business logic

---

## CRUD Phase Progress - Step 5 (Quedan CRUD + Automatic Trend Computation) (2026-02-24)

This entry continues the documented implementation order after Step 4 (News CRUD + Public News DB Integration).

### Completed in Step 5

1. Implemented admin Quedan CRUD controller with business logic.
- New controller: `app/Http/Controllers/Admin/QuedanController.php`
- Implemented:
  - `index()` (active + archived records)
  - `create()` (new price form with previous active reference)
  - `store()` with automatic business logic
  - `destroy()` with protection against deleting the active record

2. Implemented Quedan business logic in `store()`.
- On posting a new Quedan price:
  - validates input (`week_label`, `price`, `effective_date`, `notes`)
  - loads the current active record
  - computes `difference = new_price - previous_price`
  - computes trend (`UP`, `DOWN`, `NO CHANGE`)
  - archives the previous active record
  - creates the new record as `active`
- Logic is wrapped in a database transaction to keep status changes and record creation consistent

3. Added admin Quedan routes under protected admin middleware.
- Updated `routes/web.php` to include:
  - `admin.quedan.index`
  - `admin.quedan.create`
  - `admin.quedan.store`
  - `admin.quedan.destroy`

4. Added admin Quedan views.
- New files:
  - `resources/views/admin/quedan/index.blade.php`
  - `resources/views/admin/quedan/create.blade.php`
- Features included:
  - active record summary
  - archived history table
  - delete action for archived records only
  - create form with previous active record preview

5. Implemented public Quedan controller and connected the public `/quedan` page to the database.
- New controller: `app/Http/Controllers/PublicSite/QuedanPublicController.php`
- Updated `routes/web.php`:
  - `GET /quedan` now uses controller (`quedan`) instead of static `Route::view(...)`
- Controller passes:
  - active price
  - archived history
  - previous archived price reference

6. Converted `pages/quedan` from static placeholders to DB-driven rendering.
- Updated file: `resources/views/pages/quedan.blade.php`
- Preserved the existing Quedan visual layout/classes (`price-hero`, `price-metric`, `history-table`, `trend up/down/flat`)
- Added fallback display when no active Quedan record exists yet

7. Updated admin navigation/dashboard links for Quedan management.
- `resources/views/admin/partials/sidebar.blade.php` now includes `Quedan Prices`
- `resources/views/admin/dashboard.blade.php` quick actions now link to `Manage Quedan`

### Verification Done (Step 5)

- Ran syntax checks:
  - `php -l app/Http/Controllers/Admin/QuedanController.php`
  - `php -l app/Http/Controllers/PublicSite/QuedanPublicController.php`
  - `php -l routes/web.php`
- Ran route verification:
  - `php artisan route:list --path=quedan`
- Result:
  - Admin Quedan routes and public `/quedan` route registered successfully
  - Total Quedan-related routes shown during verification: `5`
- Ran Blade compilation check:
  - `php artisan view:cache`
  - Result: Blade templates cached successfully

### Execution Notes

- To use Quedan admin/public pages against real data (if not yet executed):
  - `php artisan migrate`
  - `php artisan db:seed`

### Next Documented Step

Step 6: Home Page Integration + Polish (dynamic homepage news preview and active Quedan highlight)

---

## Quedan Schema Revision - Latest Static Design Alignment (2026-02-24)

This revision updates the Quedan module to match the latest static design changes you made.

### Requested Field Changes Applied

1. Replaced old date fields in Quedan schema/CRUD/public UI.
- Replaced `week_label` with `weekending_date`
- Replaced `effective_date` with `trading_date`

2. Updated Quedan display text handling in CRUD and public view.
- `Tax & Liens` remains a UI-only fixed text label (`Net of Taxes & Liens`) and is NOT stored in the database
- Retained and used `notes` field for the Quedan note line (e.g. explanatory buying-price note)

### Code Updates Applied

1. Schema / migrations
- Updated `database/migrations/2026_02_24_000003_create_quedan_prices_table.php` (fresh installs use the new fields)
- Per request, no extra Quedan schema-alignment migration was kept.
- Quedan schema changes are now represented directly in `create_quedan_prices_table` and should be applied via `migrate:fresh --seed`.

2. Model
- Updated `app/Models/QuedanPrice.php`
- `fillable` / `casts` now use:
  - `trading_date`
  - `weekending_date`
  - `notes`

3. Controllers
- Updated `app/Http/Controllers/Admin/QuedanController.php`
  - validation and create/store logic now use `trading_date`, `weekending_date`, and `notes`
- Updated `app/Http/Controllers/PublicSite/QuedanPublicController.php`
  - ordering now uses `trading_date`
- Updated `app/Http/Controllers/Admin/AdminController.php`
  - dashboard active Quedan query now orders by `trading_date`

4. Views (Admin + Public)
- Updated admin Quedan create/index views to replace `week label` and `effective date` with:
  - Trading Date
  - Weekending Date
  - Tax & Liens (UI-only fixed text)
  - Notes
- Updated `resources/views/pages/quedan.blade.php`
  - now displays:
    - Trading Date
    - Weekending Date
    - Tax & Liens label (UI-only fixed text)
    - Notes line
  - history table columns updated accordingly
- Updated admin dashboard active Quedan summary display to show trading/weekending dates

5. Seeder
- Updated `database/seeders/QuedanSeeder.php` to seed new Quedan fields (`trading_date`, `weekending_date`, `notes`)

### Verification Done (Revision)

- Ran syntax checks for updated Quedan model/controllers/migrations
- Ran `php artisan route:list --path=quedan`
- Ran `php artisan view:cache`
- Ran `php artisan migrate --pretend`
- Verified `routes/web.php` remains saved without BOM

### Local DB Update Note

- Per request, use a fresh rebuild for the Quedan schema changes:
  - `php artisan migrate:fresh --seed`

---

## CRUD Phase Progress - Step 6 (Home Page Integration + Polish) (2026-02-24)

This entry continues the documented implementation order after Step 5 and applies the homepage integration portion of Week 6.

### Completed in Step 6

1. Implemented public `HomeController` for dynamic homepage data.
- New controller: `app/Http/Controllers/PublicSite/HomeController.php`
- Pulls:
  - latest 3 published news articles
  - active Quedan record
  - previous archived Quedan record (for previous-week display)
- Includes `Schema::hasTable(...)` checks so the homepage can still render safely before migrations/seeding

2. Switched the homepage route from static `Route::view(...)` to controller-based rendering.
- Updated `routes/web.php`
- `/` now resolves to `PublicSite\HomeController@index` (`home`)

3. Connected the homepage News preview section to database content.
- Updated `resources/views/pages/home.blade.php`
- Renders latest 3 published news articles from DB
- Preserves visual design (cards, category pills, date, preview copy)
- Links each card to the public article detail route (`news.show`)
- Adds a fallback state when no news records are available yet

4. Connected the homepage Quedan spotlight section to database content.
- Updated `resources/views/pages/home.blade.php`
- Renders active Quedan price using current schema (`trading_date`, `weekending_date`, `price`, `difference`, `trend`, `notes`)
- Shows previous archived Quedan record as the "Previous Week" reference
- Keeps `Net of Taxes & Liens` as UI-only fixed text (per latest design alignment)
- Adds a fallback state when no active Quedan record is available yet

5. Minor homepage polish / UX updates (within Step 6 scope).
- Added clearer dynamic-section copy in the homepage News preview
- Added CTA links to:
  - `View All News`
  - `View Full Quedan Page`
- Preserved the established frontend style/classes (no design reset)

### Week 6 Checklist Status Notes (Already Completed Earlier)

The following Week 6 items were already completed before this Step 6 entry and remain in place:

- Public news category filter (`?category=`) — completed in Step 4
- SEO title/meta yields in public layout — already present from earlier frontend work (`@yield('title')` and meta description support)
- Change Password route/view — completed in Step 3

### Verification Done (Step 6)

- Ran syntax checks:
  - `php -l app/Http/Controllers/PublicSite/HomeController.php`
  - `php -l routes/web.php`
- Ran route verification:
  - `php artisan route:list --path=/`
- Result:
  - Homepage route now points to `PublicSite\HomeController@index`
- Ran Blade compilation check:
  - `php artisan view:cache`
  - Result: Blade templates cached successfully

### Next Documented Step

Step 7: Testing + Security Review

---

## Community Navigation Consolidation (2026-02-24)

Applied the "single CRUD" approach for Community content by routing users to the News module with the `CSR / Community` category filter.

### Changes Applied

1. Community UI links now point to filtered News instead of a separate community page route.
- Navbar `Community` -> `news.index?category=CSR / Community`
- Footer `Community` -> `news.index?category=CSR / Community`
- Homepage `View Our Impact` -> `news.index?category=CSR / Community`

2. `/community` route is kept for backward compatibility but now redirects to filtered News.
- `GET /community` -> redirect to `news.index` with category `CSR / Community`

3. Navbar active-state behavior updated.
- On `news.index?category=CSR / Community`, the `Community` nav item is highlighted instead of `News`

### Verification Done

- Ran `php artisan route:list --path=community`
- Ran `php artisan view:cache`

### Notes

- `resources/views/pages/community.blade.php` is retained in the codebase for reference/history, but the main user flow now uses the News module filter.

---

## CRUD Phase Progress - Step 7 (Testing + Security Review) (2026-02-24)

This entry continues the documented implementation order after Step 6 and covers the automated testing pass, security review checks, and error-page hardening.

### Completed in Step 7

1. Added automated feature tests for admin access protection and core security checks.
- New test file: `tests/Feature/AdminAccessSecurityTest.php`
- Verified:
  - guest access to admin routes redirects to `admin.login`
  - non-admin authenticated user is blocked (`403`) from admin dashboard
  - `remember_token` column exists on `users` table
  - custom 404 page renders when `APP_DEBUG=false` (simulated in test)

2. Added automated end-to-end News CRUD tests (including validation and file upload checks).
- New test file: `tests/Feature/NewsCrudTest.php`
- Covered:
  - create (with valid image upload)
  - update (including image replacement and old image deletion)
  - publish/unpublish toggle
  - soft delete + restore
  - validation errors for invalid category/status
  - file upload validation:
    - invalid type rejected
    - oversized image rejected
    - missing image allowed (optional upload field)

3. Added automated Quedan logic and validation tests.
- New test file: `tests/Feature/QuedanCrudTest.php`
- Covered:
  - 4 successive price entries (first entry, UP, NO CHANGE, DOWN)
  - archive behavior (previous active becomes archived)
  - trend and difference computation checks
  - validation errors (required fields, negative price, notes max length)
  - active record delete protection and archived record delete success

4. Added data-integrity validation hardening for Quedan posting.
- Updated `app/Http/Controllers/Admin/QuedanController.php`
- `weekending_date` now requires `after_or_equal:trading_date`
- Prevents inconsistent date ranges from being saved

5. Added custom production-safe error pages for 404 and 500.
- New views:
  - `resources/views/errors/404.blade.php`
  - `resources/views/errors/500.blade.php`
- Supports the production requirement to avoid exposing framework exception details when `APP_DEBUG=false`

### Verification Done (Step 7)

1. Automated tests
- Ran `php artisan test`
- Result:
  - `13` tests passed
  - `85` assertions passed
- Includes new coverage for:
  - admin route protection
  - News CRUD workflow + upload validation
  - Quedan archive/trend business logic
  - `remember_token` presence
  - custom 404 page with debug disabled

2. Route verification
- Ran `php artisan route:list --path=admin`
- Result:
  - admin auth, dashboard, News CRUD routes, Quedan CRUD routes, and password change routes all registered

3. Blade compilation verification
- Ran `php artisan view:cache`
- Result:
  - Blade templates compiled successfully (including custom error pages)

### Security Review Notes (Current Status)

Confirmed in implementation/tests:
- Admin routes protected by `auth` + custom `admin` middleware
- Password hashing via Laravel hashed cast on `User`
- Input validation present in News and Quedan store/update flows
- News file upload restrictions enforced (`image`, mime whitelist, size limit)
- CSRF protection used in forms (`@csrf`)
- Soft deletes enabled for News
- `remember_token` exists in users schema
- Decimal storage used for Quedan prices and differences

### Manual QA / Supervisor Demo Checks (Pending Local Browser Run)

The following Week 7 items require manual browser/device testing and were not executable from the CLI-only environment:
- Cross-browser checks (Chrome / Firefox / Edge)
- Mobile viewport checks (375px and 768px)
- Manual 500-page visual verification with local `APP_DEBUG=false` and a forced server error route/action

### Known Limitations / Notes for Supervisor

- Automated tests now cover backend logic, validation, route protection, and CRUD behavior, but visual responsiveness and browser rendering remain manual QA tasks.
- Custom 404 and 500 pages are implemented; 404 rendering was automatedly verified, while 500 rendering should be manually confirmed in a local production-like config (`APP_DEBUG=false`).

### Next Documented Step

Step 8: Deployment + Documentation

---

## Quedan Price Subtext Update (2026-02-24)

Applied a Quedan UI/CRUD refinement so the text shown directly below the posted Quedan price is now editable per record (separate from the main Notes field).

### What Changed

1. Added an optional Quedan field for the price subtext/caption.
- Field: `price_subtext`
- Purpose:
  - supports changing the line below the price when needed (example: `As advance subject for final price`)
  - remains separate from the existing `notes` field

2. Integrated into Quedan CRUD (admin create form + store validation).
- Updated `resources/views/admin/quedan/create.blade.php`
- Updated `app/Http/Controllers/Admin/QuedanController.php`
- Validation:
  - `nullable|string|max:255`

3. Updated Quedan displays to use the saved subtext with fallback.
- Public Quedan page hero now shows:
  - `price_subtext` when provided
  - fallback to `Net of Taxes & Liens` when blank
- Homepage Quedan spotlight now shows:
  - `price_subtext` when provided
  - fallback to `Net of Taxes & Liens` when blank
- Admin Quedan active summary and previous-active preview now display the same value

4. Kept Quedan history tables unchanged (no new table column added for this field).
- Admin archived history table structure remains the same
- Public history table structure remains the same

5. Updated Quedan schema + seed data (fresh migration flow).
- Updated `create_quedan_prices_table` to include `price_subtext`
- Updated `QuedanSeeder` demo records with sample values
- Note:
  - This follows the current development workflow of using `migrate:fresh --seed` instead of adding a separate migration for this in-progress schema adjustment

### Validation Correction (Quedan Dates)

Corrected Quedan date validation direction to match actual BUSCO workflow examples:
- `weekending_date` must be `before_or_equal:trading_date`

This replaces the earlier Step 7 validation hardening direction (`after_or_equal`) which was too strict for the real seeded date pattern.

### Verification Done

- `php artisan test --filter=QuedanCrudTest`
- `php artisan view:cache`
- Syntax checks for updated Quedan model/controller/migration/seeder/test

---

## News Multi-Image Gallery Enhancement (2026-02-24)

Implemented multi-image support for News/Achievements/Events/CSR article detail pages with admin upload/remove controls.

### What Changed

1. News schema updated to support multiple image paths.
- Added `news.images` JSON column (gallery paths)
- Kept legacy `news.image` column for compatibility and thumbnail/primary-image use
- Column is now defined directly in `database/migrations/2026_02_24_000002_create_news_table.php` (follow-up add-column migration removed for fresh/refresh workflow)

2. News model updated for multi-image handling.
- Added `images` cast (`array`)
- Added accessors for:
  - combined/normalized image paths (legacy + JSON)
  - primary image path (for existing cards/list previews)
  - gallery image data (`path` + URL) for admin/public rendering

3. Admin News CRUD now supports gallery upload + removal (max 5 images/article).
- Updated `NewsController`:
  - validates `gallery_images[]`
  - stores multiple uploads
  - supports removal of selected existing images during edit
  - enforces max 5 total images per article after removals/additions
  - updates `news.image` to the first image for backward-compatible previews
- Accepted formats (validation):
  - `jpg`, `jpeg`

4. Admin News form updated for multi-image workflow.
- Replaced single image input with `gallery_images[]` (multiple)
- Added current image preview grid in edit mode
- Added remove checkboxes per current image
- Added helper text including format support and browser caveat

5. Public News article show page now renders a fixed-size image gallery below article content.
- Added article image grid with consistent tile sizes
- Supports up to 5 uploaded images
- Images open in a new tab when clicked

### Image Format Note

- Simplified to `JPG/JPEG` only to reduce upload/display complexity and ensure consistent browser rendering.

### Verification Done

- `php artisan test --filter=NewsCrudTest`
- `php artisan view:cache`
- `php artisan migrate --pretend` (confirmed `news.images` JSON column migration)
- Syntax checks for updated News controller/model/migration/test/view

---

## Admin Pagination + Quedan Edit Correction Workflow (2026-02-24)

Implemented admin pagination UI fixes and added Quedan record editing (including automatic recalculation of differences/trends after corrections).

### Changes Applied

1. Admin pagination UI fixes (custom styled pagination, no broken default links).
- Updated `resources/views/admin/quedan/index.blade.php`
  - archived Quedan history pagination now uses custom `Previous / pages / Next` controls
- Updated `resources/views/admin/news/index.blade.php`
  - News list pagination now uses the same custom admin pagination style

2. Added Quedan edit/update routes and admin edit screen.
- Updated `routes/web.php`
  - `admin.quedan.edit` (`GET /admin/quedan/{quedan}/edit`)
  - `admin.quedan.update` (`PUT /admin/quedan/{quedan}`)
- Added `resources/views/admin/quedan/edit.blade.php`
  - editable fields:
    - `trading_date`
    - `weekending_date`
    - `price`
    - `price_subtext`
    - `notes`

3. Added Quedan update logic in controller.
- Updated `app/Http/Controllers/Admin/QuedanController.php`
- Editing a Quedan record now:
  - saves corrected values
  - recalculates `difference` and `trend` across the full Quedan history in chronological order
- This supports correction scenarios where a previously posted Quedan price needs to be fixed after verification

4. Added Quedan Edit buttons in admin UI.
- Updated `resources/views/admin/quedan/index.blade.php`
  - `Edit Active Record` button in the active summary card
  - `Edit` button for each archived row in the Actions column

### Verification Done

- `php artisan route:list --path=admin/quedan`
- `php artisan test --filter=QuedanCrudTest`
  - includes new test covering Quedan edit + recalculation behavior
- `php artisan view:cache`
- Syntax checks:
  - `app/Http/Controllers/Admin/QuedanController.php`
  - `routes/web.php`
  - `tests/Feature/QuedanCrudTest.php`

---

## Admin Table Numbering + Stable News Ordering + Seeder Expansion (2026-02-24)

Applied admin list UX refinements requested during validation/testing: visible pagination for News (via more seed records), custom row numbering (instead of DB ids), and stable News ordering so edited records do not unexpectedly change position when timestamps are tied.

### Changes Applied

1. Expanded `NewsSeeder` demo records so pagination appears.
- Updated `database/seeders/NewsSeeder.php`
- Increased demo records from `6` to `14` (published + 1 draft)
- Added explicit `created_at` / `updated_at` timestamps per seeded article for deterministic ordering in demos
- This ensures:
  - admin News pagination (10 per page) is visible
  - public News pagination (6 per page) is visible

2. Replaced DB id display in admin News table with a separate custom row number column.
- Updated `resources/views/admin/news/index.blade.php`
- Added `No.` column using display numbers like `N-001`, `N-002`, ...
- Removed inline database id display (`ID: ...`) from the title cell
- Keeps admin UI cleaner and avoids exposing DB ids in the listing UI

3. Added custom row number column to admin Quedan archived table.
- Updated `resources/views/admin/quedan/index.blade.php`
- Added `No.` column using display numbers like `Q-001`, `Q-002`, ...
- Matches the News admin table approach (UI numbering separate from DB ids)

4. Made News ordering explicit and stable across admin/public/home queries.
- Updated:
  - `app/Http/Controllers/Admin/NewsController.php`
  - `app/Http/Controllers/PublicSite/NewsPublicController.php`
  - `app/Http/Controllers/PublicSite/HomeController.php`
- News queries now use:
  - `ORDER BY created_at DESC, id DESC`
- This avoids visually unstable ordering when many records share the same `created_at` timestamp (common in seeded/demo data), including after record updates

### Verification Done

- `php artisan view:cache`

---

## Admin Profile Section (Unified Name + Password) (2026-02-24)

Replaced the standalone admin "Change Password" navigation flow with a unified "Profile" section so administrators can manage account name and password in one page (no admin image upload).

### Changes Applied

1. Added unified admin Profile page.
- New view: `resources/views/admin/profile/index.blade.php`
- Includes two sections:
  - Account Details (change admin name)
  - Change Password

2. Refactored `ProfileController` for unified profile management.
- Updated `app/Http/Controllers/Admin/ProfileController.php`
- Added:
  - `showProfile()` (profile page)
  - `updateProfile()` (name only; no image upload)
- Existing password update remains, but now redirects back to the Profile page after success

3. Updated admin routes for profile flow.
- Updated `routes/web.php`
- Added:
  - `GET /admin/profile` -> `admin.profile.index`
  - `PATCH /admin/profile` -> `admin.profile.update`
- Kept backward compatibility:
  - `GET /admin/profile/password` now redirects to `admin.profile.index`
- Password update route remains:
  - `POST /admin/profile/password` -> `admin.profile.password.update`

4. Updated admin navigation labels/links.
- Sidebar item changed from `Change Password` to `Profile`
- Dashboard Quick Action changed from `Change Password` to `Profile`

### Notes

- Admin email is displayed as the login identifier and is currently read-only in the profile page.
- No admin profile image upload was added (per request).

### Verification Done

- `php artisan route:list --path=admin/profile`
- `php artisan view:cache`
- Syntax checks for `ProfileController` and `routes/web.php`

---

## Admin Layout Header/Footer + Page Title Sections (2026-02-24)

Added a simple shared admin header (top bar) and footer for the authenticated admin layout, plus per-page title/subtitle sections for core admin pages.

### Changes Applied

1. Added simple admin header/top bar in shared admin layout.
- Updated `resources/views/layouts/admin.blade.php`
- Shows:
  - `BUSCO Admin` label
  - page title + subtitle (from Blade sections)
  - current admin user name (right side)

2. Added simple admin footer in shared admin layout.
- Updated `resources/views/layouts/admin.blade.php`
- Footer text:
  - `Busco Sugar Milling Co., Inc. {{ now()->year }}`

3. Added page title/subtitle sections for core admin pages.
- Updated:
  - `resources/views/admin/dashboard.blade.php` (Welcome message in title)
  - `resources/views/admin/news/index.blade.php`
  - `resources/views/admin/news/create.blade.php`
  - `resources/views/admin/news/edit.blade.php`
  - `resources/views/admin/quedan/index.blade.php`
  - `resources/views/admin/quedan/create.blade.php`
  - `resources/views/admin/quedan/edit.blade.php`
  - `resources/views/admin/profile/index.blade.php`

### Notes

- The admin layout uses a shared title fallback based on `@section('title')`, but the pages above now provide explicit `page_header_title` and `page_header_subtitle` values for cleaner admin header labels.
- Existing per-page content headers were retained (no destructive UI removal), so the new header/footer can be evaluated first before further simplification.

### Verification Done

- `php artisan view:cache`
- `php artisan test --filter=NewsCrudTest`
- `php artisan test --filter=QuedanCrudTest`
- Syntax checks for updated News/Public controllers and `NewsSeeder`

---

## Admin Custom Confirm Modal for News Trash / Quedan Delete (2026-02-24)

Replaced browser `confirm()` popups with a reusable custom admin modal for destructive actions.

### Changes Applied

1. Added reusable admin confirmation modal in the admin layout.
- Updated `resources/views/layouts/admin.blade.php`
- Includes:
  - modal markup
  - admin-styled modal UI (overlay, card, buttons)
  - reusable JavaScript handler for forms with `data-confirm-*` attributes

2. Wired News Trash action to the custom modal.
- Updated `resources/views/admin/news/index.blade.php`
- Removed inline `onsubmit="return confirm(...)"` usage
- Added `data-confirm-title`, `data-confirm-message`, `data-confirm-submit-label`

3. Wired Quedan archived Delete action to the custom modal.
- Updated `resources/views/admin/quedan/index.blade.php`
- Removed inline `onsubmit="return confirm(...)"` usage
- Added `data-confirm-*` attributes for the delete action

### Result

- News Trash and Quedan Delete now use a consistent admin-designed confirmation modal instead of the browser default popup
- Reusable for future destructive actions by adding `data-confirm-*` attributes to forms

### Verification Done

- `php artisan view:cache`

---

## Careers / Job Hiring CRUD Module (2026-02-24)

Converted the static Careers page into a database-driven Job Hiring module with public list/detail pages and admin CRUD management. Applications are handled through the BUSCO HR email (no resume upload form).

### Changes Applied

1. Added Jobs data layer (using existing `job_openings` schema + model).
- Reused `database/migrations/2026_02_24_000004_create_job_openings_table.php`
- Reused `app/Models/JobOpening.php` (slug route binding, status constants, employment types)

2. Added admin Job Hiring CRUD.
- New controller: `app/Http/Controllers/Admin/JobController.php`
- Admin routes added:
  - `admin.jobs.index`
  - `admin.jobs.create`
  - `admin.jobs.store`
  - `admin.jobs.edit`
  - `admin.jobs.update`
  - `admin.jobs.destroy`
- New views:
  - `resources/views/admin/jobs/index.blade.php`
  - `resources/views/admin/jobs/create.blade.php`
  - `resources/views/admin/jobs/edit.blade.php`
  - `resources/views/admin/jobs/_form.blade.php`

3. Added public Careers list + show pages (DB-driven).
- New controller: `app/Http/Controllers/PublicSite/CareerPublicController.php`
- Updated routes:
  - `GET /careers` -> public careers list
  - `GET /careers/{jobOpening}` -> job detail page (slug-based)
- Updated/added views:
  - `resources/views/pages/careers.blade.php` (replaced static list)
  - `resources/views/pages/careers/show.blade.php`

4. Implemented email-only apply flow (no resume upload form).
- Public Careers detail page uses `mailto:` CTA to BUSCO HR email
- Admin job form shows application email as read-only reference
- Default/forced application email: `hrd_buscosugarmill@yahoo.com`

5. Added Jobs seed data and registered it in master seeder.
- New seeder: `database/seeders/JobSeeder.php`
- Registered in `database/seeders/DatabaseSeeder.php`
- Includes demo records across statuses:
  - `open`
  - `hired`
  - `closed`
  - `draft`

6. Integrated Jobs in admin navigation and dashboard.
- Added `Job Hiring` menu item in admin sidebar
- Added `Open Jobs` dashboard stat
- Added `Manage Jobs` dashboard quick action

7. Added public Careers card/list styling.
- Updated `public/css/busco-static.css`
- Includes:
  - careers filter bar
  - responsive careers grid cards
  - card meta/action styling

### Notes

- Public Careers pages only show jobs with status `open`.
- Admin can change a job status to `hired`, `closed`, or `draft` to remove it from the public list while retaining the record.
- Job detail pages are slug-based (e.g. `/careers/mill-operations-supervisor`).

### Verification Done

- `php -l app/Http/Controllers/Admin/JobController.php`
- `php -l app/Http/Controllers/PublicSite/CareerPublicController.php`
- `php -l database/seeders/JobSeeder.php`
- `php artisan route:list --path=careers`
- `php artisan route:list --path=admin/jobs`
- `php artisan view:cache`

---

## Post-Careers Hotfixes / Stability Updates (2026-02-24)

Documented the immediate fixes applied after the Careers/Job Hiring CRUD rollout to keep the implementation record aligned with the latest code changes.

### Changes Applied

1. Fixed homepage Blade parse error (`/` route).
- Updated `resources/views/pages/home.blade.php`
- Root cause: a multi-line `match (...)` expression used inside inline `@php(...)` caused Blade parsing to fail and surface an `unexpected token "else"` error.
- Fix: converted the inline `@php(...)` into a standard `@php ... @endphp` block and re-saved the file cleanly (no BOM).

2. Fixed `JobSeeder` insert failure caused by missing slug values.
- Updated `database/seeders/JobSeeder.php`
- Root cause: `job_openings.slug` is `NOT NULL`, but the seeder insert path did not supply a slug.
- Fix: added explicit slug generation using `Str::slug($job['title'])` before `updateOrCreate(...)`.

3. Added a troubleshooting record for the encountered runtime/seeding issues.
- New file: `docs/records/error_in_home.md`
- Includes captured errors and the applied resolutions for:
  - homepage parse error
  - `JobSeeder` slug/`NOT NULL` issue

### Verification Done

- `php artisan view:cache`
- `php -l database/seeders/JobSeeder.php`
- `php artisan db:seed --class=JobSeeder`

---

## Admin Forgot Password (SMTP Reset Link, Admin-Only Flow) (2026-02-24)

Added a custom admin forgot-password / reset-password flow using Laravel's built-in password broker (no Breeze integration), while preserving the existing custom admin login UI.

### Changes Applied

1. Added admin password reset controller (broker-based).
- New controller: `app/Http/Controllers/Admin/PasswordResetController.php`
- Supports:
  - request reset link form
  - send reset link email
  - reset form (token)
  - password update and redirect to admin login

2. Added admin password reset routes.
- Updated `routes/web.php`
- Added guest-only admin routes:
  - `admin.password.request` (`GET /admin/forgot-password`)
  - `admin.password.email` (`POST /admin/forgot-password`)
  - `admin.password.reset` (`GET /admin/reset-password/{token}`)
  - `admin.password.store` (`POST /admin/reset-password`)

3. Restricted the admin reset flow to admin accounts only.
- Only `users.role = admin` accounts are allowed to receive or use admin reset links
- Non-admin/unknown emails return a generic success response on the request form (to reduce account enumeration exposure)

4. Customized Laravel reset email links to use admin routes.
- Updated `app/Providers/AppServiceProvider.php`
- Configured `ResetPassword::createUrlUsing(...)` so the reset email points to:
  - `/admin/reset-password/{token}?email=...`

5. Added admin-styled forgot/reset views.
- New views:
  - `resources/views/admin/auth/forgot-password.blade.php`
  - `resources/views/admin/auth/reset-password.blade.php`
- Reuses the current admin login visual style and flash messaging

6. Updated admin login page for discoverability.
- Updated `resources/views/admin/auth/login.blade.php`
- Added `Forgot password?` link

7. Corrected seeded admin email hint/message mismatch.
- Updated `resources/views/admin/auth/login.blade.php` (default seeded email hint)
- Updated `database/seeders/AdminSeeder.php` (console output now matches actual seeded email)

### Notes

- This implementation uses your existing SMTP configuration in `.env`.
- No Breeze scaffolding was added to avoid conflicting with the current custom admin auth UI/routes.

### Verification Done

- `php -l app/Http/Controllers/Admin/PasswordResetController.php`
- `php -l app/Providers/AppServiceProvider.php`
- `php -l database/seeders/AdminSeeder.php`
- `php artisan route:list --path=admin/forgot-password`
- `php artisan route:list --path=admin/reset-password`
- `php artisan view:cache`

---

## Admin Auth Security Hardening (Rate Limiting / Throttling) (2026-02-24)

Added targeted rate limiting to admin authentication and password reset POST endpoints to reduce brute-force attempts, bot spam, and unnecessary database/mail load.

### Changes Applied

1. Added named admin auth rate limiters.
- Updated `app/Providers/AppServiceProvider.php`
- Added limiters:
  - `admin-login`
  - `admin-password-email`
  - `admin-password-reset`

2. Applied route-level throttling to admin POST endpoints.
- Updated `routes/web.php`
- Applied middleware:
  - `POST /admin/login` -> `throttle:admin-login`
  - `POST /admin/forgot-password` -> `throttle:admin-password-email`
  - `POST /admin/reset-password` -> `throttle:admin-password-reset`

3. Configured limits by `email + IP` with extra IP fallback caps.
- `admin-login`
  - 5 attempts / minute per `email + IP`
  - 20 attempts / minute per `IP`
- `admin-password-email`
  - 3 attempts / 5 minutes per `email + IP`
  - 10 attempts / 5 minutes per `IP`
- `admin-password-reset`
  - 5 attempts / 5 minutes per `email + IP`
  - 12 attempts / 5 minutes per `IP`

### Why This Was Added

- Reduces brute-force login attempts
- Helps prevent forgot-password email spam
- Limits DB writes to `sessions` / `password_reset_tokens`
- Protects SMTP usage from bot abuse

### Verification Done

- `php -l app/Providers/AppServiceProvider.php`
- `php -l routes/web.php`
- `php artisan route:list --path=admin/login`

---

## News Sub-title Field (Reduce Redundant Intro Content) (2026-02-24)

Added a `sub_title` field for News articles so the public article intro/highlight can be written separately from the main content body, reducing repeated text.

### Changes Applied

1. Updated News schema (create migration) to include subtitle.
- Updated `database/migrations/2026_02_24_000002_create_news_table.php`
- Added nullable column:
  - `sub_title` (`string`, max 500)

2. Updated News model and excerpt fallback behavior.
- Updated `app/Models/News.php`
- Added `sub_title` to `$fillable`
- Updated `excerpt` accessor to prefer `sub_title` and fall back to `content`

3. Updated admin News validation and form.
- Updated `app/Http/Controllers/Admin/NewsController.php`
  - added `sub_title` validation (`nullable|string|max:500`)
- Updated `resources/views/admin/news/_form.blade.php`
  - added `Sub-title / Intro Summary` textarea

4. Updated public News and Home display to use subtitle.
- Updated `resources/views/pages/news/show.blade.php`
  - article highlight now uses `sub_title` when present
  - meta description also prefers `sub_title`
- Updated `resources/views/pages/news/index.blade.php`
  - cards show `sub_title` first (fallback to excerpt)
- Updated `resources/views/pages/home.blade.php`
  - news preview cards show `sub_title` first (fallback to excerpt)

5. Updated News demo data to include subtitles.
- Updated `database/seeders/NewsSeeder.php`
- Added `sub_title` values for seeded articles

### Notes

- `sub_title` is optional for backward compatibility.
- If left blank, the UI still falls back to generated excerpts from `content`.
- Because the News schema was updated in the original create migration (dev workflow), run a refresh/reseed to apply the new column locally.

### Verification Done

- `php -l app/Models/News.php`
- `php -l app/Http/Controllers/Admin/NewsController.php`
- `php -l database/seeders/NewsSeeder.php`
- `php artisan view:cache`

---

## Admin Inactivity Timeout (5 Minutes, Auto Logout) (2026-02-24)

Added a 5-minute admin inactivity timeout that logs out the user and ends the session to improve admin-panel security.

### Changes Applied

1. Added server-side inactivity timeout middleware.
- New middleware: `app/Http/Middleware/AdminInactivityTimeout.php`
- Tracks last admin activity timestamp in session
- If no activity for 5 minutes (`300` seconds):
  - logs out the admin
  - invalidates the session
  - regenerates CSRF token
  - redirects to `admin.login` with a warning message

2. Registered middleware alias.
- Updated `bootstrap/app.php`
- Added alias:
  - `admin.inactivity`

3. Applied inactivity timeout to protected admin routes.
- Updated `routes/web.php`
- Protected admin route group now uses:
  - `auth`
  - `admin`
  - `admin.inactivity`

4. Added client-side idle auto-logout for admin pages.
- Updated `resources/views/layouts/admin.blade.php`
- Watches user activity events (`click`, `keydown`, `mousemove`, `scroll`, `touchstart`)
- Auto-submits the admin logout form after 5 minutes of inactivity
- Uses shared `localStorage` timestamp to reduce multi-tab false logouts when another admin tab is active

5. Tagged admin logout form for idle script.
- Updated `resources/views/admin/partials/sidebar.blade.php`
- Added `data-admin-logout-form` attribute

### Notes

- The server-side middleware is the authoritative security enforcement.
- The client-side script improves UX by logging out even when the admin leaves the page open without making another request.

### Verification Done

- `php -l app/Http/Middleware/AdminInactivityTimeout.php`
- `php -l bootstrap/app.php`
- `php -l routes/web.php`
- `php artisan view:cache`

---

## Current Accomplishment Audit (2026-02-24, Codebase Review)

This audit updates the record based on the current code in the workspace (not only prior notes).

### What You Accomplished (Verified in Code)

1. Completed the core dynamic BUSCO website modules beyond the original static-first phase:
- Dynamic homepage (`/`) with latest published news + active Quedan preview
- Public News list/detail (DB-driven)
- Public Quedan announcement page (DB-driven)
- Public Careers list/detail (DB-driven Job Hiring module)
- Admin Dashboard + Admin Auth + Admin Profile management
- Admin News CRUD (soft delete, restore, publish toggle)
- Admin Quedan CRUD (create, edit, delete archived, automatic trend/difference recalculation)
- Admin Job Hiring CRUD

2. Added security and admin usability features:
- Admin-only middleware and route protection
- Admin forgot-password/reset-password flow (SMTP/password broker)
- Admin auth/password rate limiting (throttling)
- Flash messages partial
- Custom admin confirmation modal (replaces browser `confirm()` in key destructive actions)
- Custom admin pagination partial/styling

3. Added QA/testing assets and error handling:
- Feature tests for admin access security, News CRUD, and Quedan CRUD
- Custom error pages (`404`, `500`, plus `429`)
- Error troubleshooting record (`docs/records/error_in_home.md`)

### New Folders Added (Major)

- `app/Http/Controllers/Admin/`
- `app/Http/Controllers/PublicSite/`
- `app/Http/Middleware/`
- `resources/views/admin/`
- `resources/views/admin/auth/`
- `resources/views/admin/jobs/`
- `resources/views/admin/news/`
- `resources/views/admin/profile/`
- `resources/views/admin/quedan/`
- `resources/views/admin/partials/`
- `resources/views/errors/`
- `resources/views/pages/careers/`

### New Files Added (Grouped)

#### Controllers / Middleware
- `app/Http/Controllers/Admin/AdminController.php`
- `app/Http/Controllers/Admin/AuthController.php`
- `app/Http/Controllers/Admin/NewsController.php`
- `app/Http/Controllers/Admin/QuedanController.php`
- `app/Http/Controllers/Admin/ProfileController.php`
- `app/Http/Controllers/Admin/PasswordResetController.php`
- `app/Http/Controllers/Admin/JobController.php`
- `app/Http/Controllers/PublicSite/HomeController.php`
- `app/Http/Controllers/PublicSite/NewsPublicController.php`
- `app/Http/Controllers/PublicSite/QuedanPublicController.php`
- `app/Http/Controllers/PublicSite/CareerPublicController.php`
- `app/Http/Middleware/EnsureUserIsAdmin.php`

#### Models
- `app/Models/News.php`
- `app/Models/QuedanPrice.php`
- `app/Models/JobOpening.php`

#### Migrations
- `database/migrations/2026_02_24_000001_add_role_to_users_table.php`
- `database/migrations/2026_02_24_000002_create_news_table.php`
- `database/migrations/2026_02_24_000003_create_quedan_prices_table.php`
- `database/migrations/2026_02_24_000004_create_job_openings_table.php`

#### Seeders
- `database/seeders/AdminSeeder.php`
- `database/seeders/NewsSeeder.php`
- `database/seeders/QuedanSeeder.php`
- `database/seeders/JobSeeder.php`

#### Admin / Public Views (New)
- `resources/views/layouts/admin.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/auth/login.blade.php`
- `resources/views/admin/auth/forgot-password.blade.php`
- `resources/views/admin/auth/reset-password.blade.php`
- `resources/views/admin/profile/index.blade.php`
- `resources/views/admin/news/index.blade.php`
- `resources/views/admin/news/create.blade.php`
- `resources/views/admin/news/edit.blade.php`
- `resources/views/admin/news/_form.blade.php`
- `resources/views/admin/quedan/index.blade.php`
- `resources/views/admin/quedan/create.blade.php`
- `resources/views/admin/quedan/edit.blade.php`
- `resources/views/admin/jobs/index.blade.php`
- `resources/views/admin/jobs/create.blade.php`
- `resources/views/admin/jobs/edit.blade.php`
- `resources/views/admin/jobs/_form.blade.php`
- `resources/views/admin/partials/sidebar.blade.php`
- `resources/views/pages/careers/show.blade.php`
- `resources/views/errors/404.blade.php`
- `resources/views/errors/429.blade.php`
- `resources/views/errors/500.blade.php`
- `resources/views/partials/flash-messages.blade.php`
- `resources/views/partials/custom-pagination.blade.php`

#### Tests / Docs / Assets (New)
- `tests/Feature/AdminAccessSecurityTest.php`
- `tests/Feature/NewsCrudTest.php`
- `tests/Feature/QuedanCrudTest.php`
- `docs/records/error_in_home.md`
- `public/css/error.css`

### New / Changed Database Fields (Current Schema)

1. `users` table
- Added `role` (`string(50)`, default `admin`)
- `remember_token` is already present in Laravel default users migration (not added here, but confirmed expected)

2. `news` table (implemented and expanded)
- `title`
- `sub_title` (`string(500)`, nullable) ← new enhancement
- `content`
- `image` (`string`, nullable) ← primary/legacy preview image path
- `images` (`json`, nullable) ← new multi-image gallery storage
- `category`
- `status` (draft/published)
- `is_featured` (`boolean`, default `false`)
- `deleted_at` (soft deletes)
- timestamps

3. `quedan_prices` table (revised from earlier spec naming)
- `price` (`decimal(10,2)`)
- `trading_date` (`date`) ← replaces `effective_date`
- `weekending_date` (`date`) ← replaces `week_label`
- `difference` (`decimal(10,2)`, nullable)
- `trend` (`string(20)`, nullable)
- `price_subtext` (`string(255)`, nullable) ← new UI text support
- `notes` (`text`, nullable)
- `status` (`active`/`archived`)
- timestamps

4. `job_openings` table (new module)
- `title`, `slug`, `department`, `location`, `employment_type`
- `status`, `application_email`
- `posted_at`, `deadline_at`
- `summary`, `description`, `qualifications`, `responsibilities`
- timestamps

### Legacy Static File Cleanup (Observed)

- Deleted older raw static HTML resources and placeholders:
  - `resources/about.html`
  - `resources/news_community.html`
  - `resources/process.html`
  - `resources/views/busco-news.html`
  - `resources/views/busco-news-article.html`
  - `resources/views/busco-sugar-milling.html`
  - `resources/views/welcome.blade.php`

### BUSCO Full Documentation 100% Check (Current Verdict)

Verdict: **No, not 100% spec-identical to `docs/BUSCO_FULL_DOCUMENTATION.md`**.

Reason (important): the project has progressed into a stronger implementation, but some parts intentionally diverged from the original documentation design.

#### Implemented (or exceeded) from the documented feature set

- Admin authentication and protected admin routes
- Admin dashboard
- News CRUD with soft delete/restore/publish toggle
- Quedan CRUD with automatic difference/trend logic
- Home/news/quedan public DB integration
- Flash messages
- Custom error pages (`404`, `500`)
- Decimal-based Quedan pricing (`decimal(10,2)`)
- Admin password change (inside unified Profile page)

#### Not 100% aligned with the original doc (but mostly improved)

- `community` page is now a redirect to filtered News category (`CSR / Community`) instead of a dedicated standalone page
- Careers is no longer static/hardcoded; it is now a full Job Hiring CRUD module (superset of original scope)
- Admin auth is custom-built (with forgot-password + throttling) instead of Laravel Breeze scaffolding
- News image handling evolved from single-image flow to gallery (`images` JSON, max 5 images, JPG/JPEG)
- Quedan schema/UI uses `trading_date` + `weekending_date` (and `price_subtext`) instead of the original `effective_date` + `week_label`
- Admin password management is unified under Profile instead of a standalone change-password-only page flow

#### Verification Snapshot (2026-02-24)

- `php artisan route:list` ran successfully and showed **47 routes**
- Feature tests exist, but local test execution is **not currently green** in this environment due PostgreSQL test DB/reset setup issues (and one observed `419` during Quedan feature test run), so full automated verification is still pending
