{{-- View: admin/partials/sidebar.blade.php | Purpose: Admin module page template. --}}

@php
    $user = auth()->user();
    $name = trim((string) ($user?->name ?? 'Administrator'));
    $parts = array_values(array_filter(preg_split('/\s+/', $name) ?: []));
    $initials = '';

    foreach (array_slice($parts, 0, 2) as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
    }

    if ($initials === '') {
        $initials = 'AD';
    }
@endphp
<aside class="admin-sidebar" aria-label="Admin navigation">
    <div class="admin-sidebar-top">
        <div class="admin-brand">
            BUSCO Admin Panel
            <small>Sugar Milling Co., Inc.</small>
        </div>

        <div class="admin-user">
            <div class="admin-user-avatar" aria-hidden="true">{{ $initials }}</div>
            <div class="admin-user-meta">
                <div class="admin-user-label">Signed in as</div>
                <div class="admin-user-name">{{ $user?->name ?? 'Administrator' }}</div>
            </div>
        </div>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="admin-nav-icon" aria-hidden="true">
                    <x-admin-icon name="layout-dashboard" />
                </span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <span class="admin-nav-icon" aria-hidden="true">
                    <x-admin-icon name="newspaper" />
                </span>
                <span>News & Updates</span>
            </a>
            <a href="{{ route('admin.jobs.index') }}" class="{{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                <span class="admin-nav-icon" aria-hidden="true">
                    <x-admin-icon name="briefcase" />
                </span>
                <span>Job Hiring</span>
            </a>
            <a href="{{ route('admin.quedan.index') }}" class="{{ request()->routeIs('admin.quedan.*') ? 'active' : '' }}">
                <span class="admin-nav-icon" aria-hidden="true">
                    <x-admin-icon name="circle-dollar-sign" />
                </span>
                <span>Quedan Prices</span>
            </a>
            <a href="{{ route('admin.profile.index') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <span class="admin-nav-icon" aria-hidden="true">
                    <x-admin-icon name="user-round" />
                </span>
                <span>Profile</span>
            </a>
        </nav>
    </div>

    <div class="admin-sidebar-footer">
        <form method="POST" action="{{ route('admin.logout') }}" data-admin-logout-form>
            @csrf
            <button type="submit">
                <span class="admin-nav-icon" aria-hidden="true">
                    <x-admin-icon name="log-out" :size="16" />
                </span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
