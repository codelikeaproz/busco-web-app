<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PasswordResetController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QuedanController;
use Illuminate\Support\Facades\Route;

// Public website routes — named for admin "View public" links; always redirect to Next.js.
Route::middleware('frontend.redirect')->group(function (): void {
    Route::get('/', fn () => abort(404))->name('home');
    Route::get('/about', fn () => abort(404))->name('about');
    Route::get('/services', fn () => abort(404))->name('services');
    Route::get('/process', fn () => abort(404))->name('process');
    Route::get('/news', fn () => abort(404))->name('news.index');
    Route::get('/news/{news}', fn () => abort(404))->name('news.show');
    Route::get('/quedan', fn () => abort(404))->name('quedan');
    Route::get('/community', fn () => abort(404))->name('community');
    Route::get('/contact', fn () => abort(404))->name('contact');
    Route::get('/careers', fn () => abort(404))->name('careers');
    Route::get('/careers/{jobOpening}', fn () => abort(404))->name('careers.show');
});

// Admin authentication and admin panel routes
Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:admin-login')
            ->name('login.submit');

        Route::middleware('guest')->group(function () {
            Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
            Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])
                ->middleware('throttle:admin-password-email')
                ->name('password.email');
            Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
            Route::post('/reset-password', [PasswordResetController::class, 'reset'])
                ->middleware('throttle:admin-password-reset')
                ->name('password.store');
        });

        Route::middleware(['auth', 'admin', 'admin.inactivity'])->group(function () {
            Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('index');
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
            Route::resource('news', NewsController::class)->except(['show']);
            Route::post('/news/{id}/restore', [NewsController::class, 'restore'])->name('news.restore');
            Route::post('/news/{news}/toggle', [NewsController::class, 'toggleStatus'])->name('news.toggle');
            Route::resource('jobs', JobController::class)->except(['show']);
            Route::get('/quedan', [QuedanController::class, 'index'])->name('quedan.index');
            Route::get('/quedan/create', [QuedanController::class, 'create'])->name('quedan.create');
            Route::post('/quedan', [QuedanController::class, 'store'])->name('quedan.store');
            Route::get('/quedan/{quedan}/edit', [QuedanController::class, 'edit'])->name('quedan.edit');
            Route::put('/quedan/{quedan}', [QuedanController::class, 'update'])->name('quedan.update');
            Route::delete('/quedan/{quedan}', [QuedanController::class, 'destroy'])->name('quedan.destroy');

            Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.index');
            Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
            Route::get('/profile/password', fn () => redirect()->route('admin.profile.index'))->name('profile.password');
            Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });
