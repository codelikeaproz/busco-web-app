# BUSCO Sugar Milling Co., Inc.
# Full System Implementation Documentation
> **Version:** 1.0 · **OJT Duration:** 8 Weeks · **Stack:** Laravel 11 · PostgreSQL · Blade · Railway

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Tech Stack & Requirements](#2-tech-stack--requirements)
3. [Folder Structure](#3-folder-structure)
4. [Database Structure](#4-database-structure)
5. [Migrations](#5-migrations)
6. [Models](#6-models)
7. [Seeders](#7-seeders)
8. [Controllers](#8-controllers)
9. [Routes](#9-routes)
10. [Blade Views Structure](#10-blade-views-structure)
11. [Missing Items — Implementation Guide](#11-missing-items--implementation-guide)
12. [Admin Authentication](#12-admin-authentication)
13. [News CRUD Module](#13-news-crud-module)
14. [Quedan Price Module](#14-quedan-price-module)
15. [Flash Messages](#15-flash-messages)
16. [Error Pages](#16-error-pages)
17. [Image Upload](#17-image-upload)
18. [8-Week Sprint Tasks](#18-8-week-sprint-tasks)
19. [Deployment Guide (Railway)](#19-deployment-guide-railway)
20. [Security Checklist](#20-security-checklist)
21. [Progress Record - 2026-02-21](#21-progress-record---2026-02-21)

---

## 1. Project Overview

**System Name:** BUSCO Sugar Milling Co., Inc. — Corporate Website System  
**Type:** Web-Based Corporate Information System with Admin CRUD  
**Location:** Brgy. Butong, Quezon, Bukidnon, Philippines  

### 1.1 What the System Does

| Module | Type | Description |
|--------|------|-------------|
| Public Website | Static + Dynamic | 10 public-facing pages with branded design |
| News & Achievements | CRUD | Admin creates/edits/deletes/publishes news articles |
| Quedan Price Module | CRUD | Admin posts weekly sugar price; auto-computes trend |
| Admin Dashboard | Read | Stats overview: news count, active price, last update |
| Admin Auth | Auth | Single admin login/logout with route protection |

### 1.2 Pages List

| # | Page | Type | Dynamic? |
|---|------|------|----------|
| 1 | Home | Public | ✅ News preview + Active Quedan price |
| 2 | About | Public | ❌ Static |
| 3 | Services / Operations | Public | ❌ Static |
| 4 | Sugar Milling Process | Public | ❌ Static |
| 5 | News & Achievements (List) | Public | ✅ From DB |
| 6 | News Article (Detail) | Public | ✅ From DB |
| 7 | Quedan Price Announcement | Public | ✅ From DB |
| 8 | Careers | Public | ❌ Static (hardcoded) |
| 9 | Community & Mission | Public | ❌ Static |
| 10 | Contact | Public | ❌ Static |
| 11 | Admin Login | Admin | ✅ Auth |
| 12 | Admin Dashboard | Admin | ✅ Stats from DB |
| 13 | Admin News Index | Admin | ✅ CRUD |
| 14 | Admin News Create/Edit | Admin | ✅ CRUD |
| 15 | Admin Quedan Index | Admin | ✅ CRUD |
| 16 | Admin Quedan Create | Admin | ✅ CRUD |
| 17 | Admin Change Password | Admin | ✅ Auth |

---

## 2. Tech Stack & Requirements

### 2.1 Server Requirements

```
PHP        >= 8.2
Laravel    >= 11.x
PostgreSQL >= 15.x
Composer   >= 2.x
Node.js    >= 18.x (for Vite/assets)
```

### 2.2 Laravel Packages Used

```bash
# Core (built-in)
laravel/framework
illuminate/database

# Authentication
laravel/breeze   # Simplest auth scaffolding - USE THIS

# Optional helpers
```

### 2.3 Frontend Dependencies

```
Google Fonts: Playfair Display, DM Sans
Custom CSS (no heavy framework needed)
Alpine.js (optional — for hamburger nav toggle)
```

### 2.4 Initial Laravel Setup

```bash
# 1. Create project
composer create-project laravel/laravel busco-website

# 2. Enter project
cd busco-website

# 3. Install Breeze (for auth)
composer require laravel/breeze --dev
php artisan breeze:install blade

# 4. Install Node dependencies
npm install && npm run build

# 5. Configure .env
cp .env.example .env
php artisan key:generate
```

### 2.5 Environment Variables (.env)

```env
APP_NAME="BUSCO Sugar Milling"
APP_ENV=local
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=busco_db
DB_USERNAME=postgres
DB_PASSWORD=your_password

FILESYSTEM_DISK=public
```

---

## 3. Folder Structure

```
busco-website/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AdminController.php         ← Dashboard
│   │   │   │   ├── NewsController.php          ← News CRUD
│   │   │   │   ├── QuedanController.php        ← Quedan CRUD
│   │   │   │   └── ProfileController.php       ← Change password
│   │   │   └── Public/
│   │   │       ├── HomeController.php          ← Home page (dynamic)
│   │   │       ├── NewsPublicController.php    ← Public news list + detail
│   │   │       └── QuedanPublicController.php  ← Public quedan page
│   │   └── Middleware/
│   │       └── AdminMiddleware.php             ← Route protection
│   │
│   └── Models/
│       ├── User.php
│       ├── News.php
│       └── QuedanPrice.php
│
├── database/
│   ├── migrations/
│   │   ├── 0001_create_users_table.php
│   │   ├── 0002_create_news_table.php
│   │   └── 0003_create_quedan_prices_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── AdminSeeder.php
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php               ← Public layout
│       │   └── admin.blade.php             ← Admin layout
│       ├── partials/
│       │   ├── navbar.blade.php
│       │   ├── footer.blade.php
│       │   └── flash-messages.blade.php    ← ← MISSING ITEM #2
│       ├── errors/
│       │   ├── 404.blade.php               ← ← MISSING ITEM #7
│       │   └── 500.blade.php               ← ← MISSING ITEM #7
│       ├── pages/
│       │   ├── home.blade.php
│       │   ├── about.blade.php
│       │   ├── services.blade.php
│       │   ├── process.blade.php
│       │   ├── news/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── quedan.blade.php
│       │   ├── careers.blade.php
│       │   ├── community.blade.php
│       │   └── contact.blade.php
│       └── admin/
│           ├── dashboard.blade.php
│           ├── news/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   └── edit.blade.php
│           ├── quedan/
│           │   ├── index.blade.php
│           │   └── create.blade.php
│           └── profile/
│               └── change-password.blade.php  ← ← MISSING ITEM #6
│
├── public/
│   └── images/
│       └── no-image.jpg                    ← Default image fallback
│
├── storage/
│   └── app/public/news/                    ← Uploaded news images
│
└── routes/
    └── web.php
```

---

## 4. Database Structure

### 4.1 Table: `users`

```
Column          Type                    Notes
─────────────────────────────────────────────────────
id              BIGINT PK AI
name            VARCHAR(255)            Admin display name
email           VARCHAR(255) UNIQUE     Login credential
password        VARCHAR(255)            bcrypt hashed — NEVER plain text
role            VARCHAR(50)             Default: 'admin'
remember_token  VARCHAR(100) NULL       ← MISSING ITEM #5 — Laravel expects this
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

### 4.2 Table: `news`

```
Column          Type                    Notes
─────────────────────────────────────────────────────
id              BIGINT PK AI
title           VARCHAR(255)            Required, max 255 chars
content         TEXT                    Full article HTML/text body
image           VARCHAR(255) NULL       File path in storage — nullable
category        VARCHAR(100)            ENUM: Announcements, Achievements,
                                              Events, CSR / Community
status          VARCHAR(20)             'draft' | 'published' — default: 'draft'
is_featured     BOOLEAN                 ← MISSING ITEM #3 — default: false
deleted_at      TIMESTAMP NULL          ← MISSING ITEM #4 — SoftDeletes
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

### 4.3 Table: `quedan_prices`

```
Column          Type                    Notes
─────────────────────────────────────────────────────
id              BIGINT PK AI
week_label      VARCHAR(100)            e.g. "March 22, 2026"
price           DECIMAL(10,2)           ← MISSING ITEM #8 — NOT float
effective_date  DATE                    Official effectivity
difference      DECIMAL(10,2) NULL      new_price - previous_price (null if 1st)
trend           VARCHAR(20) NULL        'UP' | 'DOWN' | 'NO CHANGE' | null
notes           TEXT NULL               Optional admin notes
status          VARCHAR(20)             'active' | 'archived' — only 1 active
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

> ⚠️ **Why `decimal(10,2)` and NOT `float`?**
> Float arithmetic: `2500.00 - 2450.00` can return `49.999999999997` due to IEEE 754
> representation. Always use `decimal` for monetary values. This is a common exam trap.

---

## 5. Migrations

### 5.1 Users Table Migration

```php
// database/migrations/2024_01_01_000001_create_users_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('admin');
            $table->rememberToken();      // ← MISSING ITEM #5 — adds remember_token column
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

### 5.2 News Table Migration

```php
// database/migrations/2024_01_01_000002_create_news_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('image')->nullable();         // nullable — image is optional
            $table->string('category', 100);
            $table->string('status', 20)->default('draft');
            $table->boolean('is_featured')->default(false); // ← MISSING ITEM #3
            $table->timestamps();
            $table->softDeletes();                       // ← MISSING ITEM #4 — adds deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
```

### 5.3 Quedan Prices Table Migration

```php
// database/migrations/2024_01_01_000003_create_quedan_prices_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quedan_prices', function (Blueprint $table) {
            $table->id();
            $table->string('week_label', 100);
            $table->decimal('price', 10, 2);             // ← MISSING ITEM #8 — decimal not float
            $table->date('effective_date');
            $table->decimal('difference', 10, 2)->nullable(); // null for first entry
            $table->string('trend', 20)->nullable();     // null for first entry
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quedan_prices');
    }
};
```

### 5.4 Run Migrations

```bash
# Run all migrations
php artisan migrate

# Rollback all and re-run (development only)
php artisan migrate:fresh

# Rollback all, re-run, and seed
php artisan migrate:fresh --seed
```

---

## 6. Models

### 6.1 User Model

```php
// app/Models/User.php

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Hidden from JSON output (API safety)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts — password auto-hashes when set
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
```

### 6.2 News Model

```php
// app/Models/News.php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;  // ← MISSING ITEM #4

class News extends Model
{
    use SoftDeletes;   // ← enables soft delete behavior

    protected $fillable = [
        'title',
        'content',
        'image',
        'category',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',   // ← MISSING ITEM #3 — cast to bool
        'deleted_at'  => 'datetime',
    ];

    // ── SCOPES ────────────────────────────────────

    /**
     * Only published articles (used on public pages)
     * Usage: News::published()->get()
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Only featured articles
     * Usage: News::featured()->get()
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Filter by category
     * Usage: News::category('Events')->get()
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // ── ACCESSORS ─────────────────────────────────

    /**
     * Return image URL or default placeholder if no image
     * Usage: $news->image_url
     * ← MISSING ITEM — fallback handling
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(storage_path('app/public/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return asset('images/no-image.jpg');
    }

    /**
     * Short excerpt of content (first 160 characters)
     * Usage: $news->excerpt
     */
    public function getExcerptAttribute(): string
    {
        return \Str::limit(strip_tags($this->content), 160);
    }

    // ── CONSTANTS ─────────────────────────────────

    const CATEGORIES = [
        'Announcements',
        'Achievements',
        'Events',
        'CSR / Community',
    ];

    const STATUS_DRAFT     = 'draft';
    const STATUS_PUBLISHED = 'published';
}
```

### 6.3 QuedanPrice Model

```php
// app/Models/QuedanPrice.php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuedanPrice extends Model
{
    protected $fillable = [
        'week_label',
        'price',
        'effective_date',
        'difference',
        'trend',
        'notes',
        'status',
    ];

    protected $casts = [
        'price'          => 'decimal:2',   // ← MISSING ITEM #8 — decimal cast
        'difference'     => 'decimal:2',
        'effective_date' => 'date',
    ];

    // ── SCOPES ────────────────────────────────────

    /**
     * Get the one active Quedan record
     * Usage: QuedanPrice::active()->first()
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get all archived records, newest first
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived')->orderByDesc('created_at');
    }

    // ── ACCESSORS ─────────────────────────────────

    /**
     * Formatted price display
     * Usage: $quedan->formatted_price → "₱ 2,500.00"
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₱ ' . number_format($this->price, 2);
    }

    /**
     * Trend badge CSS class for views
     * Usage: $quedan->trend_class → "trend-up" / "trend-down" / "trend-neutral"
     */
    public function getTrendClassAttribute(): string
    {
        return match($this->trend) {
            'UP'        => 'trend-up',
            'DOWN'      => 'trend-down',
            'NO CHANGE' => 'trend-neutral',
            default     => 'trend-neutral',
        };
    }

    /**
     * Trend icon
     * Usage: $quedan->trend_icon → "▲" / "▼" / "—"
     */
    public function getTrendIconAttribute(): string
    {
        return match($this->trend) {
            'UP'        => '▲',
            'DOWN'      => '▼',
            'NO CHANGE' => '—',
            default     => '—',
        };
    }

    // ── CONSTANTS ─────────────────────────────────

    const STATUS_ACTIVE   = 'active';
    const STATUS_ARCHIVED = 'archived';

    const TREND_UP        = 'UP';
    const TREND_DOWN      = 'DOWN';
    const TREND_NO_CHANGE = 'NO CHANGE';
}
```

---

## 7. Seeders

### 7.1 AdminSeeder

> ← **MISSING ITEM #1** — Critical for Railway deployment. Without this,
> there is no way to create the admin account in production without Tinker.

```php
// database/seeders/AdminSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Creates the default admin account.
     *
     * IMPORTANT: Change the password immediately after first login in production.
     * Never commit production credentials to GitHub.
     */
    public function run(): void
    {
        // Only create if no admin exists yet (safe to run multiple times)
        if (!User::where('email', 'admin@busco.com')->exists()) {
            User::create([
                'name'     => 'BUSCO Administrator',
                'email'    => 'admin@busco.com',
                'password' => Hash::make('busco@admin2025'),  // change after first login
                'role'     => 'admin',
            ]);

            $this->command->info('Admin account created: admin@busco.com');
        } else {
            $this->command->warn('Admin account already exists. Skipping.');
        }
    }
}
```

### 7.2 NewsSeeder (Demo Data)

```php
// database/seeders/NewsSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title'       => 'BUSCO Recognized for Quality Milling Standards',
                'content'     => 'BUSCO Sugar Milling Co., Inc. has been formally recognized by the Philippine Sugar Regulatory Administration (SRA) for outstanding performance in quality milling operations. The recognition was awarded during the Regional Sugar Industry Summit held in Cagayan de Oro City...',
                'category'    => 'Achievements',
                'status'      => 'published',
                'is_featured' => true,
            ],
            [
                'title'       => 'Advisory on Milling Schedule for the Week',
                'content'     => 'Updated milling schedule and operational reminders for partner farmers and stakeholders. Coordination with field teams is encouraged for seamless operations this week...',
                'category'    => 'Announcements',
                'status'      => 'published',
                'is_featured' => false,
            ],
            [
                'title'       => 'BUSCO Participates in Regional Agriculture Expo',
                'content'     => 'BUSCO joins regional stakeholders to promote sustainable sugarcane farming and community development at the annual agricultural exposition in Cagayan de Oro...',
                'category'    => 'Events',
                'status'      => 'published',
                'is_featured' => false,
            ],
            [
                'title'       => 'Farmer Training Program Conducted in Bukidnon',
                'content'     => 'BUSCO supports farmers through hands-on training sessions focused on productivity, crop management, and safety practices in the field...',
                'category'    => 'CSR / Community',
                'status'      => 'published',
                'is_featured' => false,
            ],
            [
                'title'       => 'Operational Milestone: Successful Season Completion',
                'content'     => 'BUSCO marks a major operational milestone with improved efficiency and stronger farmer collaboration this milling season...',
                'category'    => 'Achievements',
                'status'      => 'published',
                'is_featured' => false,
            ],
            [
                'title'       => 'Holiday Office Advisory & Community Support Activities',
                'content'     => 'BUSCO shares holiday advisories and highlights upcoming community outreach initiatives for the holiday season...',
                'category'    => 'Announcements',
                'status'      => 'published',
                'is_featured' => false,
            ],
        ];

        foreach ($articles as $article) {
            News::create($article);
        }

        $this->command->info('6 demo news articles seeded.');
    }
}
```

### 7.3 QuedanSeeder (Demo Data)

```php
// database/seeders/QuedanSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuedanPrice;

class QuedanSeeder extends Seeder
{
    public function run(): void
    {
        // Past records (archived)
        QuedanPrice::create([
            'week_label'     => 'March 1, 2026',
            'price'          => 2350.00,
            'effective_date' => '2026-03-06',
            'difference'     => null,
            'trend'          => null,
            'status'         => 'archived',
        ]);

        QuedanPrice::create([
            'week_label'     => 'March 8, 2026',
            'price'          => 2400.00,
            'effective_date' => '2026-03-13',
            'difference'     => 50.00,
            'trend'          => 'UP',
            'status'         => 'archived',
        ]);

        QuedanPrice::create([
            'week_label'     => 'March 15, 2026',
            'price'          => 2380.00,
            'effective_date' => '2026-03-20',
            'difference'     => -20.00,
            'trend'          => 'DOWN',
            'status'         => 'archived',
        ]);

        // Current active price
        QuedanPrice::create([
            'week_label'     => 'March 22, 2026',
            'price'          => 2500.00,
            'effective_date' => '2026-03-27',
            'difference'     => 120.00,
            'trend'          => 'UP',
            'status'         => 'active',
        ]);

        $this->command->info('Demo Quedan price records seeded (3 archived, 1 active).');
    }
}
```

### 7.4 DatabaseSeeder (Master)

```php
// database/seeders/DatabaseSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,   // ← ALWAYS first
            NewsSeeder::class,
            QuedanSeeder::class,
        ]);
    }
}
```

### 7.5 Run Seeders

```bash
# Run all seeders
php artisan db:seed

# Run a specific seeder only
php artisan db:seed --class=AdminSeeder

# Fresh migrate + seed (development)
php artisan migrate:fresh --seed
```

---

## 8. Controllers

### 8.1 Admin Auth — ProfileController (Change Password)

> ← **MISSING ITEM #6** — Without this, password changes require Tinker in production.

```php
// app/Http/Controllers/Admin/ProfileController.php

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the change password form
     * Route: GET /admin/profile/password
     */
    public function showChangePassword()
    {
        return view('admin.profile.change-password');
    }

    /**
     * Process the password change
     * Route: POST /admin/profile/password
     */
    public function updatePassword(Request $request)
    {
        // Validate input
        $request->validate([
            'current_password'          => ['required'],
            'password'                  => ['required', 'min:8', 'confirmed'],
            'password_confirmation'     => ['required'],
        ]);

        $user = Auth::user();

        // Verify current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        // Update to new password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Flash success message ← MISSING ITEM #2
        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Password changed successfully. Please use your new password next login.');
    }
}
```

### 8.2 AdminController (Dashboard)

```php
// app/Http/Controllers/Admin/AdminController.php

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\QuedanPrice;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with stats
     * Route: GET /admin/dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_news'      => News::count(),
            'published_news'  => News::published()->count(),
            'draft_news'      => News::where('status', 'draft')->count(),
            'active_quedan'   => QuedanPrice::active()->first(),
            'last_news'       => News::latest()->first(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
```

### 8.3 NewsController (Full CRUD)

```php
// app/Http/Controllers/Admin/NewsController.php

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * List all news articles (with pagination)
     * Route: GET /admin/news
     */
    public function index(Request $request)
    {
        $query = News::withTrashed();  // Include soft-deleted for admin view

        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $news = $query->latest()->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    /**
     * Show create form
     * Route: GET /admin/news/create
     */
    public function create()
    {
        $categories = News::CATEGORIES;
        return view('admin.news.create', compact('categories'));
    }

    /**
     * Save new news article
     * Route: POST /admin/news
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'category'    => ['required', 'in:' . implode(',', News::CATEGORIES)],
            'status'      => ['required', 'in:draft,published'],
            'is_featured' => ['boolean'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('news', 'public');  // stores in storage/app/public/news/
        }

        // Default is_featured to false if unchecked
        $validated['is_featured'] = $request->boolean('is_featured');

        News::create($validated);

        // Flash message ← MISSING ITEM #2
        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News article "' . $validated['title'] . '" created successfully.');
    }

    /**
     * Show edit form
     * Route: GET /admin/news/{news}/edit
     */
    public function edit(News $news)
    {
        $categories = News::CATEGORIES;
        return view('admin.news.edit', compact('news', 'categories'));
    }

    /**
     * Update existing news article
     * Route: PUT /admin/news/{news}
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'category'    => ['required', 'in:' . implode(',', News::CATEGORIES)],
            'status'      => ['required', 'in:draft,published'],
            'is_featured' => ['boolean'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Handle new image upload — delete old file first
        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image); // remove old file
            }
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        $news->update($validated);

        // Flash message ← MISSING ITEM #2
        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News article updated successfully.');
    }

    /**
     * Soft delete a news article
     * Route: DELETE /admin/news/{news}
     * ← MISSING ITEM #4 — soft delete instead of hard delete
     */
    public function destroy(News $news)
    {
        $title = $news->title;
        $news->delete();  // SoftDeletes — sets deleted_at, doesn't remove from DB

        return redirect()
            ->route('admin.news.index')
            ->with('success', '"' . $title . '" has been moved to trash.');
    }

    /**
     * Restore a soft-deleted news article
     * Route: POST /admin/news/{id}/restore
     */
    public function restore(int $id)
    {
        $news = News::withTrashed()->findOrFail($id);
        $news->restore();

        return redirect()
            ->route('admin.news.index')
            ->with('success', '"' . $news->title . '" has been restored.');
    }

    /**
     * Toggle publish / draft status
     * Route: POST /admin/news/{news}/toggle-status
     */
    public function toggleStatus(News $news)
    {
        $news->update([
            'status' => $news->status === 'published' ? 'draft' : 'published',
        ]);

        $label = $news->status === 'published' ? 'published' : 'unpublished';

        return redirect()
            ->route('admin.news.index')
            ->with('success', '"' . $news->title . '" has been ' . $label . '.');
    }
}
```

### 8.4 QuedanController (Full CRUD + Business Logic)

```php
// app/Http/Controllers/Admin/QuedanController.php

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuedanPrice;
use Illuminate\Http\Request;

class QuedanController extends Controller
{
    /**
     * List all Quedan records
     * Route: GET /admin/quedan
     */
    public function index()
    {
        $active   = QuedanPrice::active()->first();
        $archived = QuedanPrice::archived()->paginate(10);

        return view('admin.quedan.index', compact('active', 'archived'));
    }

    /**
     * Show new price form
     * Route: GET /admin/quedan/create
     */
    public function create()
    {
        $previousActive = QuedanPrice::active()->first();
        return view('admin.quedan.create', compact('previousActive'));
    }

    /**
     * Save new Quedan price
     *
     * Business Logic:
     * 1. Validate input
     * 2. Get current active record
     * 3. Compute difference = new_price - previous_price
     * 4. Determine trend: UP / DOWN / NO CHANGE
     * 5. Archive the old active record
     * 6. Create new record as 'active'
     *
     * Route: POST /admin/quedan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'week_label'     => ['required', 'string', 'max:100'],
            'price'          => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        // Get current active record before archiving
        $previousActive = QuedanPrice::active()->first();

        // ── BUSINESS LOGIC ──────────────────────────────────
        $newPrice = (float) $validated['price'];

        if ($previousActive) {
            $previousPrice = (float) $previousActive->price;
            $difference    = $newPrice - $previousPrice;

            // Determine trend
            $trend = match(true) {
                $difference > 0  => QuedanPrice::TREND_UP,
                $difference < 0  => QuedanPrice::TREND_DOWN,
                default          => QuedanPrice::TREND_NO_CHANGE,
            };
        } else {
            // First entry ever — no comparison possible
            $difference = null;
            $trend      = null;
        }
        // ────────────────────────────────────────────────────

        // Archive the current active record
        if ($previousActive) {
            $previousActive->update(['status' => QuedanPrice::STATUS_ARCHIVED]);
        }

        // Create new active record
        QuedanPrice::create([
            'week_label'     => $validated['week_label'],
            'price'          => $validated['price'],
            'effective_date' => $validated['effective_date'],
            'difference'     => $difference,
            'trend'          => $trend,
            'notes'          => $validated['notes'] ?? null,
            'status'         => QuedanPrice::STATUS_ACTIVE,
        ]);

        // Flash message ← MISSING ITEM #2
        return redirect()
            ->route('admin.quedan.index')
            ->with('success', 'New Quedan price of ₱' . number_format($newPrice, 2) . ' posted successfully.');
    }

    /**
     * Delete a Quedan record (only archived records can be deleted)
     * Route: DELETE /admin/quedan/{quedan}
     */
    public function destroy(QuedanPrice $quedan)
    {
        // Prevent deleting the active record
        if ($quedan->status === 'active') {
            return redirect()
                ->route('admin.quedan.index')
                ->with('error', 'Cannot delete the active Quedan price. Post a new price first to archive it.');
        }

        $quedan->delete();

        return redirect()
            ->route('admin.quedan.index')
            ->with('success', 'Quedan record deleted.');
    }
}
```

### 8.5 Public Controllers

```php
// app/Http/Controllers/Public/HomeController.php

<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\QuedanPrice;

class HomeController extends Controller
{
    public function index()
    {
        // Latest 3 published articles for news preview
        $latestNews = News::published()
            ->latest()
            ->take(3)
            ->get();

        // Active Quedan price for homepage highlight
        $activeQuedan = QuedanPrice::active()->first();

        return view('pages.home', compact('latestNews', 'activeQuedan'));
    }
}
```

```php
// app/Http/Controllers/Public/NewsPublicController.php

<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsPublicController extends Controller
{
    /**
     * Public news list with optional category filter
     */
    public function index(Request $request)
    {
        $query = News::published()->latest();

        // Simple category filter — GET ?category=Events
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $news       = $query->paginate(6);
        $categories = News::CATEGORIES;

        return view('pages.news.index', compact('news', 'categories'));
    }

    /**
     * Full article view
     */
    public function show(News $news)
    {
        // 404 if article is not published
        abort_if($news->status !== 'published', 404);

        // Related articles: same category, exclude current, latest 3
        $related = News::published()
            ->where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->latest()
            ->take(3)
            ->get();

        return view('pages.news.show', compact('news', 'related'));
    }
}
```

```php
// app/Http/Controllers/Public/QuedanPublicController.php

<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\QuedanPrice;

class QuedanPublicController extends Controller
{
    public function index()
    {
        $activePrice = QuedanPrice::active()->first();
        $history     = QuedanPrice::archived()->take(8)->get();

        return view('pages.quedan', compact('activePrice', 'history'));
    }
}
```

---

## 9. Routes

```php
// routes/web.php

<?php

use Illuminate\Support\Facades\Route;

// ── Public Controllers ──────────────────────────────────────
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\NewsPublicController;
use App\Http\Controllers\Public\QuedanPublicController;

// ── Admin Controllers ───────────────────────────────────────
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\QuedanController;
use App\Http\Controllers\Admin\ProfileController;


// ════════════════════════════════════════════════
//  PUBLIC ROUTES
// ════════════════════════════════════════════════

Route::get('/', [HomeController::class, 'index'])->name('home');

// Static pages (no controller needed — use view() directly)
Route::view('/about',       'pages.about')->name('about');
Route::view('/services',    'pages.services')->name('services');
Route::view('/process',     'pages.process')->name('process');
Route::view('/careers',     'pages.careers')->name('careers');
Route::view('/community',   'pages.community')->name('community');
Route::view('/contact',     'pages.contact')->name('contact');

// Dynamic: News
Route::get('/news',          [NewsPublicController::class, 'index'])->name('news.index');
Route::get('/news/{news}',   [NewsPublicController::class, 'show'])->name('news.show');

// Dynamic: Quedan Price
Route::get('/quedan-price',  [QuedanPublicController::class, 'index'])->name('quedan.public');


// ════════════════════════════════════════════════
//  ADMIN ROUTES — protected by auth middleware
// ════════════════════════════════════════════════

Route::prefix('admin')
    ->middleware(['auth'])   // Laravel Breeze auth guard
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // ── News CRUD ──────────────────────────────────
        Route::resource('news', NewsController::class);

        // Extra news actions (not in standard resource)
        Route::post('/news/{id}/restore',      [NewsController::class, 'restore'])->name('news.restore');
        Route::post('/news/{news}/toggle',     [NewsController::class, 'toggleStatus'])->name('news.toggle');

        // ── Quedan CRUD ────────────────────────────────
        Route::get('/quedan',              [QuedanController::class, 'index'])->name('quedan.index');
        Route::get('/quedan/create',       [QuedanController::class, 'create'])->name('quedan.create');
        Route::post('/quedan',             [QuedanController::class, 'store'])->name('quedan.store');
        Route::delete('/quedan/{quedan}',  [QuedanController::class, 'destroy'])->name('quedan.destroy');

        // ── Profile / Change Password ──────────────────
        // ← MISSING ITEM #6
        Route::get('/profile/password',    [ProfileController::class, 'showChangePassword'])->name('profile.password');
        Route::post('/profile/password',   [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });


// Auth routes provided by Laravel Breeze
require __DIR__ . '/auth.php';
```

---

## 10. Blade Views Structure

### 10.1 Public Layout — `layouts/app.blade.php`

```blade
{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- ← MISSING ITEM — SEO meta tags --}}
    <title>@yield('title', 'BUSCO Sugar Milling Co., Inc.')</title>
    <meta name="description" content="@yield('description', 'Busco Sugar Milling Co., Inc. — Brgy. Butong, Quezon, Bukidnon.')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    @include('partials.navbar')

    {{-- Flash Messages — displayed on every page ← MISSING ITEM #2 --}}
    @include('partials.flash-messages')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

</body>
</html>
```

### 10.2 Admin Layout — `layouts/admin.blade.php`

```blade
{{-- resources/views/layouts/admin.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — BUSCO Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">

    {{-- Admin Sidebar/Navbar --}}
    <div class="admin-layout">
        @include('admin.partials.sidebar')

        <main class="admin-main">
            {{-- Flash Messages ← MISSING ITEM #2 --}}
            @include('partials.flash-messages')

            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
```

### 10.3 Flash Messages Partial

> ← **MISSING ITEM #2** — This partial must be included in BOTH layouts.

```blade
{{-- resources/views/partials/flash-messages.blade.php --}}

@if (session('success'))
    <div class="flash flash-success" role="alert">
        <span class="flash-icon">✅</span>
        <span class="flash-text">{{ session('success') }}</span>
        <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif

@if (session('error'))
    <div class="flash flash-error" role="alert">
        <span class="flash-icon">❌</span>
        <span class="flash-text">{{ session('error') }}</span>
        <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif

@if (session('warning'))
    <div class="flash flash-warning" role="alert">
        <span class="flash-icon">⚠️</span>
        <span class="flash-text">{{ session('warning') }}</span>
        <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif

{{-- Validation errors (form errors) --}}
@if ($errors->any())
    <div class="flash flash-error" role="alert">
        <span class="flash-icon">❌</span>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
```

### 10.4 Error Pages

> ← **MISSING ITEM #7** — Laravel auto-serves these when errors occur.

```blade
{{-- resources/views/errors/404.blade.php --}}

@extends('layouts.app')

@section('title', '404 — Page Not Found')

@section('content')
<section style="text-align:center; padding: 100px 20px;">
    <div style="font-size: 80px; margin-bottom: 20px;">🌾</div>
    <h1 style="font-family: 'Playfair Display', serif; font-size: 48px; color: #1B5E20;">404</h1>
    <h2 style="color: #2E7D32; margin-bottom: 16px;">Page Not Found</h2>
    <p style="color: #7A8C7C; max-width: 400px; margin: 0 auto 32px;">
        The page you're looking for doesn't exist or may have been moved.
    </p>
    <a href="{{ route('home') }}" class="btn-primary">← Back to Home</a>
</section>
@endsection
```

```blade
{{-- resources/views/errors/500.blade.php --}}

@extends('layouts.app')

@section('title', '500 — Server Error')

@section('content')
<section style="text-align:center; padding: 100px 20px;">
    <div style="font-size: 80px; margin-bottom: 20px;">⚙️</div>
    <h1 style="font-family: 'Playfair Display', serif; font-size: 48px; color: #1B5E20;">500</h1>
    <h2 style="color: #2E7D32; margin-bottom: 16px;">Something Went Wrong</h2>
    <p style="color: #7A8C7C; max-width: 400px; margin: 0 auto 32px;">
        We're experiencing a technical issue. Please try again later or contact the administrator.
    </p>
    <a href="{{ route('home') }}" class="btn-primary">← Back to Home</a>
</section>
@endsection
```

### 10.5 Admin Change Password View

> ← **MISSING ITEM #6**

```blade
{{-- resources/views/admin/profile/change-password.blade.php --}}

@extends('layouts.admin')
@section('title', 'Change Password')

@section('content')
<div class="admin-page-header">
    <h1>Change Password</h1>
    <p>Update your administrator account password.</p>
</div>

<div class="form-card" style="max-width: 500px;">
    <form method="POST" action="{{ route('admin.profile.password.update') }}">
        @csrf

        {{-- Current Password --}}
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input
                type="password"
                id="current_password"
                name="current_password"
                class="form-input @error('current_password') is-invalid @enderror"
                required
                autocomplete="current-password"
            >
            @error('current_password')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- New Password --}}
        <div class="form-group">
            <label for="password">New Password</label>
            <input
                type="password"
                id="password"
                name="password"
                class="form-input @error('password') is-invalid @enderror"
                required
                minlength="8"
                autocomplete="new-password"
            >
            @error('password')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Confirm New Password --}}
        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-input"
                required
                minlength="8"
                autocomplete="new-password"
            >
        </div>

        <div style="display:flex; gap:12px; margin-top:24px;">
            <button type="submit" class="btn-primary">Update Password</button>
            <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
```

---

## 11. Missing Items — Implementation Guide

This section consolidates all 8 missing items with exact implementation steps.

---

### MISSING ITEM #1 — DatabaseSeeder for Admin Account

**Why it matters:** Without a seeder, you cannot create the admin account on Railway. You'd need SSH + Tinker, which is impractical for a handover to the client.

**Steps:**
1. Create `database/seeders/AdminSeeder.php` (see Section 7.1)
2. Register it in `DatabaseSeeder.php` (see Section 7.4)
3. Run `php artisan db:seed --class=AdminSeeder`
4. On Railway, run via the console tab: `php artisan db:seed`

**Verification:**
```bash
# Check admin was created
php artisan tinker
>>> App\Models\User::where('email', 'admin@busco.com')->first()
```

---

### MISSING ITEM #2 — Flash Messages After Every CRUD Action

**Why it matters:** Without feedback, admins don't know if their action succeeded or failed.

**Steps:**
1. Create `resources/views/partials/flash-messages.blade.php` (see Section 10.3)
2. Include it in BOTH `layouts/app.blade.php` AND `layouts/admin.blade.php`
3. In every controller action, chain `->with('success', '...')` or `->with('error', '...')` on the redirect

**CSS for flash messages:**
```css
.flash {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 20px; border-radius: 8px;
    margin: 16px 0; font-size: 14px;
    animation: slideDown .3s ease;
}
.flash-success { background: #E8F5E9; border-left: 4px solid #2E7D32; color: #1B5E20; }
.flash-error   { background: #FFEBEE; border-left: 4px solid #C62828; color: #B71C1C; }
.flash-warning { background: #FFF8E1; border-left: 4px solid #F9A825; color: #7B5700; }
.flash-close   { margin-left: auto; background: none; border: none; cursor: pointer; font-size: 18px; }

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}
```

---

### MISSING ITEM #3 — `is_featured` Boolean on News

**Why it matters:** Your public news UI shows a "FEATURED" gold badge — this badge needs a database field to control it.

**Steps:**
1. Add `$table->boolean('is_featured')->default(false);` to news migration (see Section 5.2)
2. Add `'is_featured' => 'boolean'` to News model `$casts` (see Section 6.2)
3. Add `is_featured` to News model `$fillable`
4. Add a checkbox to admin create/edit form:
```blade
<div class="form-group">
    <label>
        <input type="checkbox" name="is_featured" value="1"
               {{ old('is_featured', $news->is_featured ?? false) ? 'checked' : '' }}>
        Mark as Featured Article
    </label>
</div>
```
5. In Blade (public news list), show badge conditionally:
```blade
@if($article->is_featured)
    <span class="badge-featured">Featured</span>
@endif
```

---

### MISSING ITEM #4 — `deleted_at` for SoftDeletes on News

**Why it matters:** Hard-deleting news articles is irreversible. SoftDeletes allows recovery.

**Steps:**
1. Add `$table->softDeletes();` to news migration (see Section 5.2)
2. Add `use SoftDeletes;` trait to News model (see Section 6.2)
3. In NewsController `destroy()`, use `$news->delete()` — this now soft-deletes (see Section 8.3)
4. Admin news index can show trashed items with `News::withTrashed()->...`
5. Add restore button in admin view:
```blade
@if($article->trashed())
    <form method="POST" action="{{ route('admin.news.restore', $article->id) }}">
        @csrf
        <button type="submit" class="btn-warning">Restore</button>
    </form>
@else
    <form method="POST" action="{{ route('admin.news.destroy', $article) }}">
        @csrf @method('DELETE')
        <button type="submit" class="btn-danger" onclick="return confirm('Move to trash?')">Delete</button>
    </form>
@endif
```

---

### MISSING ITEM #5 — `remember_token` on Users Table

**Why it matters:** Laravel's built-in authentication system expects this column. Without it, login sessions may behave unexpectedly.

**Steps:**
1. Add `$table->rememberToken();` to users migration (see Section 5.1)
2. That's it — Laravel handles the rest automatically

**Note:** `rememberToken()` is a Blueprint helper that creates a nullable `VARCHAR(100)` column named `remember_token`.

---

### MISSING ITEM #6 — Admin Password Change Form

**Why it matters:** After deployment, the admin password in the seeder is public (it's in your GitHub code). The client needs a way to change it without you accessing the server.

**Steps:**
1. Create `ProfileController.php` (see Section 8.1)
2. Add routes in `web.php` (see Section 9)
3. Create `resources/views/admin/profile/change-password.blade.php` (see Section 10.5)
4. Add a "Change Password" link in the admin sidebar/nav:
```blade
<a href="{{ route('admin.profile.password') }}">🔑 Change Password</a>
```

---

### MISSING ITEM #7 — Custom 404 and 500 Error Pages

**Why it matters:** Default Laravel error pages show "Whoops" or raw errors. Custom pages look professional and keep branding consistent.

**Steps:**
1. Create `resources/views/errors/404.blade.php` (see Section 10.4)
2. Create `resources/views/errors/500.blade.php` (see Section 10.4)
3. Laravel automatically serves these — no configuration needed
4. Test 404 by visiting `/nonexistent-page` in production (`APP_DEBUG=false`)

**Note:** Error pages only show when `APP_DEBUG=false`. In local development with debug on, Laravel shows its detailed error screen instead.

---

### MISSING ITEM #8 — Use `decimal(10,2)` NOT `float` for Price Columns

**Why it matters:** `float` in PostgreSQL uses IEEE 754 floating-point which causes binary rounding errors for base-10 decimal values.

**Example of the bug:**
```php
// With float:
$price     = 2500.00;
$previous  = 2450.00;
$diff      = $price - $previous;
// $diff = 49.999999999997  ← WRONG

// With decimal:
$price     = 2500.00;  // stored as exact decimal in PostgreSQL
$previous  = 2450.00;
$diff      = $price - $previous;
// $diff = 50.00  ← CORRECT
```

**Steps:**
1. In migration, use `$table->decimal('price', 10, 2)` (see Section 5.3)
2. In model, cast: `'price' => 'decimal:2'` (see Section 6.3)
3. In PHP computation (QuedanController), cast to float for arithmetic, then store result:
```php
$difference = round((float)$newPrice - (float)$previousPrice, 2);
```

---

## 12. Admin Authentication

### 12.1 Setup Laravel Breeze

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
php artisan migrate
```

### 12.2 Protecting Admin Routes

Laravel Breeze adds `auth` middleware automatically. All routes inside the `auth` middleware group require login.

```php
// routes/web.php
Route::prefix('admin')
    ->middleware(['auth'])  // ← This is all you need
    ->name('admin.')
    ->group(function () {
        // all admin routes here
    });
```

### 12.3 Redirect After Login

In `app/Http/Controllers/Auth/AuthenticatedSessionController.php`, change the redirect:

```php
// Change from '/dashboard' to '/admin/dashboard'
return redirect()->intended('/admin/dashboard');
```

Or in `RouteServiceProvider.php`:
```php
public const HOME = '/admin/dashboard';
```

### 12.4 Logout

```blade
{{-- In admin nav --}}
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
```

---

## 13. News CRUD Module

### 13.1 Full Flow Summary

```
Admin clicks "Add News"
       ↓
GET /admin/news/create  →  NewsController@create  →  admin/news/create.blade.php
       ↓
Admin fills form (title, content, category, status, is_featured, image)
       ↓
POST /admin/news  →  NewsController@store
       ↓
Validate inputs (title required, category in list, image max 2MB)
       ↓
If image: store to storage/app/public/news/ via Storage::disk('public')
       ↓
News::create($validated)
       ↓
Flash "Article created successfully."
       ↓
Redirect to /admin/news (index)
```

### 13.2 Storage Setup for Image Upload

```bash
# Create symbolic link from public/storage to storage/app/public
php artisan storage:link

# Verify: public/storage/ should now point to storage/app/public/
```

### 13.3 Public News Query Logic

```php
// Only published, newest first, 6 per page
News::published()->latest()->paginate(6);

// With category filter
News::published()->category('Events')->latest()->paginate(6);

// Featured for homepage
News::published()->featured()->latest()->take(1)->first();
```

---

## 14. Quedan Price Module

### 14.1 Business Logic Flow

```
Admin submits new Quedan price form
       ↓
Validate: week_label, price (numeric), effective_date
       ↓
Retrieve current active record (QuedanPrice::active()->first())
       ↓
Calculate difference:
  IF previous exists:
    difference = new_price - previous_price
    trend = difference > 0 → "UP"
            difference < 0 → "DOWN"
            difference = 0 → "NO CHANGE"
  ELSE (first ever entry):
    difference = null
    trend = null
       ↓
Archive previous active record:
  $previousActive->update(['status' => 'archived'])
       ↓
Create new active record with computed values
       ↓
Flash "New Quedan price posted."
       ↓
Redirect to /admin/quedan
```

### 14.2 Public Quedan Page Display Logic

```blade
{{-- resources/views/pages/quedan.blade.php --}}

@if($activePrice)
    <div class="quedan-card">
        <div class="quedan-week">{{ $activePrice->week_label }}</div>
        <div class="quedan-price">{{ $activePrice->formatted_price }}</div>
        <div class="quedan-date">Effective: {{ $activePrice->effective_date->format('F d, Y') }}</div>

        @if($activePrice->trend)
            <div class="trend-badge trend-{{ strtolower($activePrice->trend) }}">
                {{ $activePrice->trend_icon }} {{ $activePrice->trend }}
                @if($activePrice->difference)
                    ({{ $activePrice->difference > 0 ? '+' : '' }}₱{{ number_format(abs($activePrice->difference), 2) }})
                @endif
            </div>
        @endif
    </div>
@else
    <p>No Quedan price posted yet.</p>
@endif
```

---

## 15. Flash Messages

### 15.1 How to Use in Controllers

```php
// Success
return redirect()->route('admin.news.index')->with('success', 'Article published.');

// Error
return redirect()->back()->with('error', 'Something went wrong. Please try again.');

// Warning
return redirect()->back()->with('warning', 'No changes were made.');
```

### 15.2 Auto-dismiss with JavaScript

```javascript
// resources/js/app.js — Auto-dismiss flash messages after 4 seconds
document.addEventListener('DOMContentLoaded', function () {
    const flashes = document.querySelectorAll('.flash');
    flashes.forEach(function (flash) {
        setTimeout(function () {
            flash.style.opacity = '0';
            flash.style.transition = 'opacity .4s';
            setTimeout(function () { flash.remove(); }, 400);
        }, 4000);
    });
});
```

---

## 16. Error Pages

### 16.1 How Laravel Serves Error Pages

Laravel automatically uses the files in `resources/views/errors/` when errors occur:

| File | When Served |
|------|-------------|
| `errors/404.blade.php` | Route not found |
| `errors/500.blade.php` | Internal server error |
| `errors/403.blade.php` | Unauthorized (optional) |

### 16.2 Testing Locally

```bash
# Set APP_DEBUG=false in .env to see custom error pages locally
APP_DEBUG=false

# Trigger 404 — visit any non-existent route
# http://localhost:8000/this-does-not-exist

# Trigger 403 — abort(403) in any controller or route
```

---

## 17. Image Upload

### 17.1 Setup

```bash
# Step 1: Create symlink
php artisan storage:link
# This creates: public/storage → storage/app/public

# Step 2: Verify .env
FILESYSTEM_DISK=public
```

### 17.2 Storing Files

```php
// In controller — store image and get its path
$path = $request->file('image')->store('news', 'public');
// Stores in: storage/app/public/news/filename.jpg
// Access via: asset('storage/news/filename.jpg')
// OR via model accessor: $news->image_url
```

### 17.3 Deleting Old Files on Update

```php
// When updating, delete old file before storing new one
if ($request->hasFile('image') && $news->image) {
    Storage::disk('public')->delete($news->image);
}
```

### 17.4 Default Image Fallback

```php
// app/Models/News.php — getImageUrlAttribute()
public function getImageUrlAttribute(): string
{
    if ($this->image && Storage::disk('public')->exists($this->image)) {
        return asset('storage/' . $this->image);
    }
    return asset('images/no-image.jpg');  // public/images/no-image.jpg
}
```

```blade
{{-- In Blade — always use the accessor, never raw $news->image --}}
<img src="{{ $news->image_url }}" alt="{{ $news->title }}">
```

---

## 18. 8-Week Sprint Tasks

### Week 1 — Environment Setup
- [ ] Install Laravel 12, configure `.env` with PostgreSQL
- [ ] Create and connect Railway PostgreSQL database
- [ ] Create Blade layout: `layouts/app.blade.php`
- [ ] Create partials: `navbar.blade.php`, `footer.blade.php`
- [ ] Build 4 static Blade pages: Home (placeholder), About, Services, Process
- [ ] Apply BUSCO color palette to global CSS
- [ ] Set up GitHub repository, push initial commit

### Week 2 — Remaining Static Pages + Responsive
- [ ] Build Careers page (hardcoded, email apply link)
- [ ] Build Contact page (address + phone + email — static)
- [ ] Build Quedan Price public page (placeholder data)
- [ ] Build Community/Mission page (static)
- [ ] Make all pages responsive (mobile-first, hamburger nav)
- [ ] Create custom `errors/404.blade.php` ← ITEM #7
- [ ] Create custom `errors/500.blade.php` ← ITEM #7
- [ ] Test all pages on mobile viewport (Chrome DevTools)

### Week 3 — Admin Auth + Dashboard
- [ ] Install Laravel Breeze (`composer require laravel/breeze --dev`)
- [ ] Customize auth redirect to `/admin/dashboard`
- [ ] Create `AdminController@dashboard`
- [ ] Create `layouts/admin.blade.php` (separate admin layout)
- [ ] Build admin dashboard view (stats: news count, active price)
- [ ] Create `flash-messages.blade.php` partial ← ITEM #2
- [ ] Include flash messages in both layouts ← ITEM #2
- [ ] Create `AdminSeeder.php` ← ITEM #1
- [ ] Run migrations and seed admin account
- [ ] Verify admin routes return 302 redirect when not logged in

### Week 4 — News CRUD
- [ ] Write `create_news_table` migration (include `is_featured`, `deleted_at`) ← ITEMS #3 #4
- [ ] Build `News` model with SoftDeletes, scopes, accessors ← ITEMS #3 #4
- [ ] Create `NewsController` with all 7 methods
- [ ] Build admin news views: `index`, `create`, `edit`
- [ ] Run `php artisan storage:link` for image uploads
- [ ] Add image fallback handling ← ITEM (fallback accessor)
- [ ] Connect public news list to DB (published only, paginated)
- [ ] Connect news detail page to DB
- [ ] Add `is_featured` checkbox to create/edit form ← ITEM #3
- [ ] Test: create, edit, delete, restore, publish toggle

### Week 5 — Quedan Price CRUD
- [ ] Write `create_quedan_prices_table` migration (use `decimal(10,2)`) ← ITEM #8
- [ ] Build `QuedanPrice` model with scopes and accessors
- [ ] Create `QuedanController` with business logic
- [ ] Build admin Quedan views: `index`, `create`
- [ ] Connect public Quedan page to DB
- [ ] Test: post new price → verify old is archived → verify trend computed correctly
- [ ] Test edge cases: first ever price entry (no difference), no change

### Week 6 — Home Page Integration + Polish
- [ ] Update `HomeController` to pull dynamic news + active Quedan
- [ ] Render latest 3 news on homepage from DB
- [ ] Render active Quedan price on homepage
- [ ] Add category filter to public news list (`?category=`)
- [ ] Add SEO `@yield('title')` and `@yield('description')` in layout
- [ ] Polish all pages: spacing, hover states, font consistency
- [ ] Add Change Password route and view ← ITEM #6

### Week 7 — Testing + Security Review
- [ ] Test all CRUD operations end-to-end
- [ ] Verify admin routes blocked without login
- [ ] Test Quedan logic: 3+ successive price entries, check archive + trend
- [ ] Check all form validations trigger correctly
- [ ] Set `APP_DEBUG=false`, verify custom error pages show
- [ ] Check `remember_token` column exists ← ITEM #5
- [ ] Cross-browser test (Chrome, Firefox, Edge)
- [ ] Mobile viewport test (375px, 768px)
- [ ] File upload: test invalid types, oversized files, missing files
- [ ] Document known bugs or limitations for supervisor

### Week 8 — Deployment + Documentation
- [ ] Create Railway project, add Laravel + PostgreSQL services
- [ ] Configure environment variables on Railway dashboard
- [ ] Connect GitHub repository to Railway for auto-deploy
- [ ] Run `php artisan migrate --force` on production
- [ ] Run `php artisan db:seed` on production ← ITEM #1
- [ ] Run `php artisan storage:link` on production
- [ ] Verify SSL is active (https://)
- [ ] Seed demo data: 6 news articles + 4 Quedan records
- [ ] Write `README.md` with setup and deployment instructions
- [ ] Prepare demo walkthrough for presentation

---

## 19. Deployment Guide (Railway)

### 19.1 Step-by-Step

```bash
# Step 1: Push project to GitHub
git add .
git commit -m "Initial BUSCO website deployment"
git push origin main

# Step 2: On Railway (railway.app)
# - Create new project
# - "Deploy from GitHub repo"
# - Select your repository

# Step 3: Add PostgreSQL
# - Click "+ Add Service"
# - Select "Database" → "PostgreSQL"
# - Railway provides DB credentials automatically

# Step 4: Configure environment variables
# In Railway → Your Laravel Service → Variables tab, add:
APP_NAME="BUSCO Sugar Milling"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
APP_KEY=  ← Generate with: php artisan key:generate --show

DB_CONNECTION=pgsql
DB_HOST=     ← From Railway PostgreSQL service
DB_PORT=5432
DB_DATABASE= ← From Railway PostgreSQL service
DB_USERNAME= ← From Railway PostgreSQL service
DB_PASSWORD= ← From Railway PostgreSQL service

FILESYSTEM_DISK=public
```

### 19.2 Procfile (Required for Railway)

```
# Create Procfile in project root
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

Or use a `railway.json`:
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php artisan migrate --force && php artisan db:seed --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=$PORT"
  }
}
```

### 19.3 Post-Deployment Checklist

```bash
# Run via Railway console tab:
php artisan migrate --force
php artisan db:seed --class=AdminSeeder
php artisan storage:link

# Verify:
# ✅ https://your-app.railway.app loads
# ✅ https://your-app.railway.app/admin/login works
# ✅ Login with admin@busco.com credentials
# ✅ Change password immediately ← ITEM #6
# ✅ Create 1 test news article
# ✅ Post 1 Quedan price
# ✅ Verify public pages show DB data
```

---

## 20. Security Checklist

```
✅  Password hashing         — bcrypt via Laravel's 'hashed' cast on User model
✅  CSRF protection          — @csrf in every POST/PUT/DELETE form
✅  Route middleware         — ['auth'] on all /admin/* routes
✅  Input validation         — $request->validate() in every store/update method
✅  SQL injection            — Eloquent ORM with parameter binding (no raw queries)
✅  File upload validation   — mimes:jpg,jpeg,png,webp + max:2048 rule
✅  APP_DEBUG=false          — In production .env (never expose stack traces)
✅  APP_ENV=production       — In production .env
✅  HTTPS enforced           — Via Railway SSL (automatic)
✅  Soft deletes             — No accidental permanent data loss on news
✅  remember_token           — Present in users table for session stability
✅  Decimal for money        — No float rounding errors on Quedan prices
✅  Error pages              — Custom 404/500 don't expose system info
```

---

## Appendix A — Color Reference

| Variable | Hex | Usage |
|----------|-----|-------|
| `--green` | `#1B5E20` | Headings, nav, primary buttons |
| `--green-mid` | `#2E7D32` | Hover states, icons, borders |
| `--gold` | `#F9A825` | CTAs, featured badges, highlights |
| `--white` | `#FFFFFF` | Main backgrounds |
| `--offwhite` | `#F7F9F5` | Alternating section backgrounds |

---

## Appendix B — Common Artisan Commands

```bash
# Development
php artisan serve                       # Start dev server
php artisan migrate                     # Run pending migrations
php artisan migrate:fresh --seed        # Wipe DB and reseed
php artisan db:seed --class=AdminSeeder # Seed only admin
php artisan storage:link                # Create storage symlink
php artisan route:list                  # List all registered routes
php artisan tinker                      # Interactive PHP REPL

# Production
php artisan migrate --force             # Run migrations (skip confirmation)
php artisan config:cache                # Cache config for performance
php artisan route:cache                 # Cache routes for performance
php artisan view:cache                  # Cache compiled Blade views
php artisan optimize                    # Run all cache commands
php artisan optimize:clear              # Clear all caches
```

---

## Appendix C — File Naming Conventions

```
Models:          PascalCase singular       User.php, News.php, QuedanPrice.php
Controllers:     PascalCase + Controller   NewsController.php
Migrations:      snake_case with date      2024_01_01_create_news_table.php
Seeders:         PascalCase + Seeder       AdminSeeder.php
Blade views:     kebab-case               change-password.blade.php
Routes (names):  dot notation             admin.news.index, news.show
CSS classes:     kebab-case               btn-primary, flash-success, card-thumb
```

---

*Document prepared for BUSCO Sugar Milling Co., Inc. OJT Implementation*
*Laravel 12 · PostgreSQL · Blade · Railway Pro · 2025*

---

## 21. Progress Record - 2026-02-21

### Completed in This Turn (Static-First Phase)

- Implemented shared static layout and partials (`resources/views/layouts/app.blade.php`, `resources/views/partials/navbar.blade.php`, `resources/views/partials/footer.blade.php`).
- Implemented architecture-aligned static public pages:
  - Home
  - About
  - Services / Operations
  - Sugar Milling Process
  - News & Achievements (list)
  - News Article (detail)
  - Quedan Price Announcement
  - Community & Training
  - Careers
  - Contact
- Implemented a shared static design system in `public/css/busco-static.css` and `public/js/busco-static.js`.
- Styled News & Achievements using the same design direction as `resources/views/busco-news.html` (card grid, filters, badges, typography, spacing).
- Added static route mappings in `routes/web.php` for all implemented pages (no CRUD routes yet).
- Verification completed using `php artisan route:list` and `php artisan view:cache`.

### Explicitly Not Yet Done (Deferred by Request)

- Admin authentication
- News CRUD
- Quedan CRUD and price-difference computation
- Database-driven page rendering

### Record File

- `docs/records/2026-02-21-static-frontend-implementation.md`
