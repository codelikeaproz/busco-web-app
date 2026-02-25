<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

// Admin profile management controller (name + password updates)
class ProfileController extends Controller
{
    /**
     * Show the admin profile page.
     */
    public function showProfile(): View
    {
        return view('admin.profile.index');
    }

    /**
     * Update basic admin profile details (no image upload).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $user->update([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('admin.profile.index')
            ->with('success', 'Profile details updated successfully.');
    }

    /**
     * Process admin password update.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        $user = Auth::user();

        if (! $user || ! Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.profile.index')
            ->with('success', 'Password changed successfully. Please use your new password next login.');
    }
}
