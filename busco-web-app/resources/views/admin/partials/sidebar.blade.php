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
                <div class="admin-user-name">{{ $user?->name ?? 'Administrator' }}</div>
            </div>
        </div>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="admin-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3.5" y="3.5" width="7" height="7" rx="1.5"></rect>
                        <rect x="13.5" y="3.5" width="7" height="4.5" rx="1.2"></rect>
                        <rect x="13.5" y="10.5" width="7" height="10" rx="1.5"></rect>
                        <rect x="3.5" y="13.5" width="7" height="7" rx="1.5"></rect>
                    </svg>
                </span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <span class="admin-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 4.5h14v15H5z"></path>
                        <path d="M8 8h8"></path>
                        <path d="M8 12h8"></path>
                        <path d="M8 16h5"></path>
                    </svg>
                </span>
                <span>News & Updates</span>
            </a>
            <a href="{{ route('admin.jobs.index') }}" class="{{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                <span class="admin-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4.5 7.5h15a1.5 1.5 0 0 1 1.5 1.5v8a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 17V9a1.5 1.5 0 0 1 1.5-1.5Z"></path>
                        <path d="M9 7.5V6a1.5 1.5 0 0 1 1.5-1.5h3A1.5 1.5 0 0 1 15 6v1.5"></path>
                        <path d="M3 11.5h18"></path>
                    </svg>
                </span>
                <span>Job Hiring</span>
            </a>
            <a href="{{ route('admin.quedan.index') }}" class="{{ request()->routeIs('admin.quedan.*') ? 'active' : '' }}">
                <span class="admin-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="8.5"></circle>
                        <path d="M14.7 9.2a2.8 2.8 0 0 0-2.7-1.2c-1.3.2-2.1 1-2.1 2 0 2.8 5.4 1.2 5.4 4 0 1.1-1 2-2.4 2.2a3.6 3.6 0 0 1-3-.8"></path>
                        <path d="M12 6.8v10.4"></path>
                    </svg>
                </span>
                <span>Quedan Prices</span>
            </a>
            <a href="{{ route('admin.profile.index') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <span class="admin-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6.5 10.5V8.8a5.5 5.5 0 0 1 11 0v1.7"></path>
                        <rect x="4.5" y="10.5" width="15" height="10" rx="2"></rect>
                        <path d="M12 14.3v2.4"></path>
                    </svg>
                </span>
                <span>Profile</span>
            </a>
        </nav>
    </div>

    <div class="admin-sidebar-footer">
        <form method="POST" action="{{ route('admin.logout') }}" data-admin-logout-form>
            @csrf
            <button type="submit">
                <span class="admin-nav-icon" aria-hidden="true" style="width:16px; height:16px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 7l5 5-5 5"></path>
                        <path d="M20 12H9"></path>
                        <path d="M9 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4"></path>
                    </svg>
                </span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
