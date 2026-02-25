{{-- View: admin/profile/index.blade.php | Purpose: Admin module page template. --}}

@php($adminUser = auth()->user())

@extends('layouts.admin')

@section('title', 'Profile')
@section('page_header_title', 'Profile')
@section('page_header_subtitle', 'Update your admin name and password from one account settings page.')

@section('content')
<section class="admin-section">
    <div data-profile-grid style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:16px; align-items:start;">
        <div class="form-card" style="height:100%;">
            <h2 style="margin:0 0 10px; color:#183f1d; font-family:'Playfair Display', serif; font-size:1.15rem;">Account Details</h2>
            <p style="margin:0 0 14px; color:#607062;">Update your display name for the admin panel. Email remains your login identifier.</p>

            <form method="POST" action="{{ route('admin.profile.update') }}" class="form-grid">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-input"
                        value="{{ old('name', $adminUser?->name ?? '') }}"
                        maxlength="255"
                        required
                        autocomplete="name"
                    >
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email_readonly">Email (Login)</label>
                    <input
                        type="email"
                        id="email_readonly"
                        class="form-input"
                        value="{{ $adminUser?->email ?? '' }}"
                        readonly
                        disabled
                    >
                    <small style="color:#637266;">Email change is disabled in the admin profile for now.</small>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:2px;">
                    <button type="submit" class="btn-admin">Save Profile</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-admin-secondary">Back to Dashboard</a>
                </div>
            </form>
        </div>

        <div class="form-card" style="height:100%;">
            <h2 style="margin:0 0 10px; color:#183f1d; font-family:'Playfair Display', serif; font-size:1.15rem;">Change Password</h2>
            <p style="margin:0 0 14px; color:#607062;">Update your administrator account password. Use at least 8 characters.</p>

            <form method="POST" action="{{ route('admin.profile.password.update') }}" class="form-grid">
                @csrf

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="form-input"
                        required
                        autocomplete="current-password"
                    >
                    @error('current_password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

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

                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:2px;">
                    <button type="submit" class="btn-admin">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
    @media (max-width: 980px) {
        .admin-panel [data-profile-grid] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
