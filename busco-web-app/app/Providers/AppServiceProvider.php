<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('admin-login', function (Request $request): array {
            $email = strtolower(trim((string) $request->input('email', 'unknown')));

            return [
                Limit::perMinute(5)->by('admin-login:' . $email . '|' . $request->ip()),
                Limit::perMinute(20)->by('admin-login-ip:' . $request->ip()),
            ];
        });

        RateLimiter::for('admin-password-email', function (Request $request): array {
            $email = strtolower(trim((string) $request->input('email', 'unknown')));

            return [
                Limit::perMinutes(5, 3)->by('admin-password-email:' . $email . '|' . $request->ip()),
                Limit::perMinutes(5, 10)->by('admin-password-email-ip:' . $request->ip()),
            ];
        });

        RateLimiter::for('admin-password-reset', function (Request $request): array {
            $email = strtolower(trim((string) $request->input('email', 'unknown')));

            return [
                Limit::perMinutes(5, 5)->by('admin-password-reset:' . $email . '|' . $request->ip()),
                Limit::perMinutes(5, 12)->by('admin-password-reset-ip:' . $request->ip()),
            ];
        });

        ResetPassword::createUrlUsing(function (User $user, string $token): string {
            return route('admin.password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);
        });
    }
}
