@extends('layouts.admin')

@section('title', 'Change Password')

@section('content')
<div class="admin-page-header">
    <h1>Change Password</h1>
    <p>Update your administrator account password.</p>
</div>

<div class="admin-section">
    <div class="form-card" style="max-width: 560px;">
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

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn-admin">Update Password</button>
                <a href="{{ route('admin.dashboard') }}" class="btn-admin-secondary">Back to Dashboard</a>
            </div>
        </form>
    </div>
</div>
@endsection
