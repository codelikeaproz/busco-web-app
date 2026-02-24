<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PasswordResetController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QuedanController;
use App\Http\Controllers\PublicSite\CareerPublicController;
use App\Http\Controllers\PublicSite\HomeController;
use App\Http\Controllers\PublicSite\NewsPublicController;
use App\Http\Controllers\PublicSite\QuedanPublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'pages.about', ['activePage' => 'about'])->name('about');
Route::view('/services', 'pages.services', ['activePage' => 'services'])->name('services');
Route::view('/process', 'pages.process', ['activePage' => 'process'])->name('process');
Route::get('/news', [NewsPublicController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsPublicController::class, 'show'])->name('news.show');
Route::get('/quedan', [QuedanPublicController::class, 'index'])->name('quedan');
Route::get('/community', fn () => redirect()->route('news.index', ['category' => 'CSR / Community']))->name('community');
Route::view('/contact', 'pages.contact', ['activePage' => 'contact'])->name('contact');
Route::get('/careers', [CareerPublicController::class, 'index'])->name('careers');
Route::get('/careers/{jobOpening}', [CareerPublicController::class, 'show'])->name('careers.show');

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
