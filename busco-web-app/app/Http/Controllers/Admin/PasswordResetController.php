<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

// Admin password reset request and reset submission controller
class PasswordResetController extends Controller
{
    // Show the "forgot password" form for admin users
    public function showLinkRequestForm(): View
    {
        return view('admin.auth.forgot-password');
    }

    // Send reset link while avoiding user/account enumeration
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        // Avoid exposing whether the email exists or is non-admin.
        if (! $user || ! $user->isAdmin()) {
            return back()->with('success', 'If the email belongs to an admin account, a reset link has been sent.');
        }

        $status = Password::broker()->sendResetLink([
            'email' => $validated['email'],
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', __($status));
        }

        return back()
            ->withErrors(['email' => __($status)])
            ->onlyInput('email');
    }

    // Show the reset password form with token and email query string
    public function showResetForm(Request $request, string $token): View
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    // Reset the admin password using the default password broker
    public function reset(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();
        if (! $user || ! $user->isAdmin()) {
            return back()
                ->withErrors(['email' => 'This account is not authorized for the admin password reset.'])
                ->onlyInput('email');
        }

        $status = Password::broker()->reset(
            $validated,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('admin.login')
                ->with('success', 'Password reset successful. You may now sign in.');
        }

        return back()
            ->withErrors(['email' => __($status)])
            ->onlyInput('email');
    }
}
