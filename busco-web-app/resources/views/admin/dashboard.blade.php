@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_header_title', 'Welcome, ' . (auth()->user()?->name ?? 'Administrator'))
@section('page_header_subtitle', 'Dashboard overview and quick actions for managing News, Jobs, Quedan prices, and your account.')

@section('content')
<section class="admin-section">
    <div data-dashboard-stat-grid style="display:grid; gap:12px; grid-template-columns: repeat(4, minmax(0, 1fr));">
        <article class="stat-card">
            <div class="label">Total News</div>
            <div class="value">{{ $stats['total_news'] }}</div>
        </article>
        <article class="stat-card">
            <div class="label">Published News</div>
            <div class="value">{{ $stats['published_news'] }}</div>
        </article>
        <article class="stat-card">
            <div class="label">Draft News</div>
            <div class="value">{{ $stats['draft_news'] }}</div>
        </article>
        <article class="stat-card">
            <div class="label">Open Jobs</div>
            <div class="value">{{ $stats['open_jobs'] ?? 0 }}</div>
        </article>
    </div>
</section>

<section class="admin-section">
    <div class="form-card" style="padding:16px;">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:14px; flex-wrap:wrap;">
            <div>
                <div style="font-size:.78rem; color:#637266; text-transform:uppercase; letter-spacing:.08em; font-weight:700;">Active Quedan Price</div>
                <div style="margin-top:4px; font-family:'Playfair Display', serif; color:#183f1d; font-size:1.35rem;">
                    {{ $stats['active_quedan']?->formatted_price ?? 'N/A' }}
                </div>
            </div>
            <a href="{{ route('admin.quedan.index') }}" class="btn-admin-secondary" style="text-decoration:none;">Manage Quedan</a>
        </div>

        <div style="margin-top:12px; padding-top:12px; border-top:1px solid #edf2e9;">
            @if($stats['active_quedan'])
                <div data-dashboard-quedan-meta style="display:grid; gap:10px; grid-template-columns: repeat(3, minmax(0, 1fr));">
                    <div style="padding:10px 12px; border:1px solid #e8eee3; border-radius:10px; background:#fbfdf9;">
                        <small style="color:#637266;">Trading Date</small>
                        <div style="font-weight:700; color:#183f1d;">{{ $stats['active_quedan']->trading_date?->format('M d, Y') }}</div>
                    </div>
                    <div style="padding:10px 12px; border:1px solid #e8eee3; border-radius:10px; background:#fbfdf9;">
                        <small style="color:#637266;">Weekending Date</small>
                        <div style="font-weight:700; color:#183f1d;">{{ $stats['active_quedan']->weekending_date?->format('M d, Y') }}</div>
                    </div>
                    <div style="padding:10px 12px; border:1px solid #e8eee3; border-radius:10px; background:#fbfdf9;">
                        <small style="color:#637266;">Trend</small>
                        <div style="font-weight:700; color:#183f1d;">{{ $stats['active_quedan']->trend ?? 'Initial' }}</div>
                    </div>
                </div>
            @else
                <div style="color:#637266;">No active Quedan record yet.</div>
            @endif
        </div>
    </div>
</section>

<section class="admin-section">
    <div data-dashboard-lower-grid style="display:grid; gap:16px; grid-template-columns: 1fr 1fr;">
        <div class="form-card" style="height:100%;">
            <h2 style="margin:0 0 8px; color:#183f1d; font-family:'Playfair Display', serif;">Quick Actions</h2>
            <p style="margin:0 0 12px; color:#607062;">Jump directly to the most common admin tasks.</p>
            <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:10px;">
                <a class="btn-admin-secondary" href="{{ route('admin.profile.index') }}" style="text-align:center; text-decoration:none;">Profile</a>
                <a class="btn-admin-secondary" href="{{ route('admin.news.index') }}" style="text-align:center; text-decoration:none;">Manage News</a>
                <a class="btn-admin-secondary" href="{{ route('admin.jobs.index') }}" style="text-align:center; text-decoration:none;">Manage Jobs</a>
                <a class="btn-admin-secondary" href="{{ route('admin.quedan.index') }}" style="text-align:center; text-decoration:none;">Manage Quedan</a>
            </div>
        </div>

        <div class="form-card" style="height:100%;">
            <h2 style="margin:0 0 8px; color:#183f1d; font-family:'Playfair Display', serif;">Latest News Record</h2>
            @if($stats['last_news'])
                <div style="padding:12px; border:1px solid #e8eee3; border-radius:12px; background:#fbfdf9;">
                    <div style="font-weight:700; color:#1c3d20;">{{ $stats['last_news']->title }}</div>
                    @if($stats['last_news']->sub_title)
                        <div style="margin-top:6px; color:#4f5f55; line-height:1.5; font-size:.92rem;">
                            {{ $stats['last_news']->sub_title }}
                        </div>
                    @endif
                    <div style="margin-top:8px; color:#607062; font-size:.9rem;">
                        {{ $stats['last_news']->category }} | {{ ucfirst($stats['last_news']->status) }} | {{ $stats['last_news']->created_at?->format('M d, Y h:i A') }}
                    </div>
                </div>
                <div style="margin-top:10px; display:flex; gap:8px; flex-wrap:wrap;">
                    <a class="btn-admin-secondary" href="{{ route('admin.news.index') }}" style="text-decoration:none;">Open News Manager</a>
                    @if($stats['last_news']->status === \App\Models\News::STATUS_PUBLISHED)
                        <a class="btn-admin-secondary" href="{{ route('news.show', $stats['last_news']) }}" target="_blank" rel="noopener" style="text-decoration:none;">View Public</a>
                    @endif
                </div>
            @else
                <p style="margin:0; color:#607062;">No news record available yet.</p>
            @endif
        </div>
    </div>
</section>

<style>
    @media (max-width: 1180px) {
        .admin-panel [data-dashboard-stat-grid] {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
        .admin-panel [data-dashboard-quedan-meta] {
            grid-template-columns: 1fr !important;
        }
    }
    @media (max-width: 900px) {
        .admin-panel [data-dashboard-lower-grid] {
            grid-template-columns: 1fr !important;
        }
    }
    @media (max-width: 700px) {
        .admin-panel [data-dashboard-stat-grid] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
