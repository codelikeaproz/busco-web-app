@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | Careers')
@section('meta_description', 'Current job openings and career opportunities at BUSCO Sugar Milling Co., Inc.')

@section('content')
<section class="page-shell">
    <header class="page-header reveal">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Careers</span>
        </div>
        <h1 class="page-title">Careers</h1>
        <p class="page-subtitle">
            Explore current openings at BUSCO. Applications are handled through our HR email and reviewed by the recruitment team.
        </p>
    </header>

    <section class="surface reveal" style="padding: 22px;">
        <h2 class="section-title" style="font-size: clamp(26px, 3.3vw, 36px);">Open Positions</h2>
        <p class="section-copy" style="margin-bottom:0;">
            Find the role that fits your skills and passion. To apply, send your resume and application letter to
            <a href="mailto:hrd_buscosugarmill@yahoo.com" style="color: var(--green-mid); font-weight:600;">hrd_buscosugarmill@yahoo.com</a>.
            No online resume upload form is used at this time.
        </p>
    </section>

    <form class="careers-controls reveal" method="GET" action="{{ route('careers') }}">
        <div class="search-box">
            <span class="search-icon" aria-hidden="true" style="font-size:15px; left:13px;">⌕</span>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search position, department, or location">
        </div>

        <div class="control-group">
            <label for="careers_department">Department</label>
            <select id="careers_department" name="department" class="control-select">
                <option value="">All</option>
                @foreach($departments as $department)
                    <option value="{{ $department }}" {{ ($filters['department'] ?? '') === $department ? 'selected' : '' }}>{{ $department }}</option>
                @endforeach
            </select>
        </div>

        <div class="control-group">
            <label for="careers_type">Type</label>
            <select id="careers_type" name="employment_type" class="control-select">
                <option value="">All</option>
                @foreach($employmentTypes as $type)
                    <option value="{{ $type }}" {{ ($filters['employment_type'] ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="padding:10px 16px;">Apply</button>
        <a href="{{ route('careers') }}" class="btn btn-secondary" style="padding:10px 16px;">Reset</a>

        <div class="result-count">
            <strong>{{ $jobs->total() }}</strong> open position{{ $jobs->total() === 1 ? '' : 's' }}
        </div>
    </form>

    @if($jobs->count())
        <div class="careers-grid">
            @foreach($jobs as $job)
                <article class="career-card reveal">
                    <div class="career-card-top">
                        <span class="career-pill career-pill-dept">{{ $job->department }}</span>
                        <span class="career-pill career-pill-type">{{ $job->employment_type }}</span>
                    </div>

                    <h2 class="career-card-title">{{ $job->title }}</h2>
                    <p class="career-card-location">{{ $job->location }}</p>
                    <p class="career-card-summary">{{ $job->short_description }}</p>

                    <div class="career-card-meta">
                        <span>Posted {{ $job->posted_at?->diffForHumans() ?? $job->created_at?->diffForHumans() }}</span>
                        <span>Deadline: {{ $job->deadline_at?->format('F d, Y') ?? 'Open until filled' }}</span>
                    </div>

                    <a href="{{ route('careers.show', $job) }}" class="career-card-action">
                        <span>View Details</span>
                        <span aria-hidden="true">→</span>
                    </a>
                </article>
            @endforeach
        </div>

        @if($jobs->hasPages())
            @include('partials.custom-pagination', [
                'paginator' => $jobs,
                'navLabel' => 'Careers pagination',
            ])
        @endif
    @else
        <div class="surface reveal" style="padding:22px; margin-top:20px;">
            <h3 style="margin:0 0 8px; color:var(--green); font-family:'Playfair Display', serif;">No Open Positions Found</h3>
            <p style="margin:0; color:var(--muted); line-height:1.7;">
                Try changing the filters or check back later for new opportunities. You may also send your resume to
                <a href="mailto:hrd_buscosugarmill@yahoo.com" style="color:var(--green-mid);">hrd_buscosugarmill@yahoo.com</a>.
            </p>
        </div>
    @endif
</section>
@endsection
