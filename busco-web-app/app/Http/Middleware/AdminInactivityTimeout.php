<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminInactivityTimeout
{
    private const SESSION_KEY = 'admin_last_activity_at';
    private const TIMEOUT_SECONDS = 300; // 5 minutes

    /**
     * Log out authenticated admins after a period of inactivity.
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (! Auth::check() || ! $request->user()?->isAdmin()) {
            return $next($request);
        }

        $lastActivityAt = (int) $request->session()->get(self::SESSION_KEY, 0);
        $now = now()->timestamp;

        if ($lastActivityAt > 0 && ($now - $lastActivityAt) >= self::TIMEOUT_SECONDS) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->with('warning', 'You were logged out due to 5 minutes of inactivity.');
        }

        $request->session()->put(self::SESSION_KEY, $now);

        return $next($request);
    }
}
