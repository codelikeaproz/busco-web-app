@extends('layouts.app')

@section('title', $job->title . ' | Careers | BUSCO')
@section('meta_description', $job->short_description)

@php
    $applySubject = rawurlencode('Application - ' . $job->title);
    $applyHref = 'mailto:' . ($job->application_email ?: 'hrd_buscosugarmill@yahoo.com') . '?subject=' . $applySubject;
@endphp

@section('content')
<section class="article-hero" style="background: linear-gradient(135deg, #14411a 0%, #1f6028 55%, #17461d 100%);">
    <div class="article-hero-shell">
        <div class="breadcrumb" style="color: rgba(255,255,255,.72);">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,.82);">Home</a>
            <span>/</span>
            <a href="{{ route('careers') }}" style="color: rgba(255,255,255,.82);">Careers</a>
            <span>/</span>
            <span>Job Details</span>
        </div>

        <span class="eyebrow" style="background: rgba(249,168,37,.16); border-color: rgba(249,168,37,.4); color: #ffe39f;">
            {{ $job->department }} • {{ $job->employment_type }}
        </span>
        <h1>{{ $job->title }}</h1>
        <div class="article-meta">
            <span>Location: {{ $job->location }}</span>
            <span>Posted: {{ $job->posted_at?->format('F d, Y') ?? $job->created_at?->format('F d, Y') }}</span>
            <span>Deadline: {{ $job->deadline_at?->format('F d, Y') ?? 'Open until filled' }}</span>
        </div>
    </div>
</section>

<section class="article-layout">
    <article class="article-content reveal">
        @if($job->summary)
            <p class="article-highlight">{{ $job->summary }}</p>
        @endif

        <h2 style="font-size:1.8rem; margin-top:0;">Job Description</h2>
        <div style="line-height:1.9; color:#32433a;">
            {!! nl2br(e($job->description)) !!}
        </div>

        @if($job->responsibilities)
            <section class="article-box" style="margin-top:18px;">
                <h3>Key Responsibilities</h3>
                <div style="line-height:1.75; color:#394b3f;">
                    {!! nl2br(e($job->responsibilities)) !!}
                </div>
            </section>
        @endif

        @if($job->qualifications)
            <section class="article-box" style="margin-top:18px;">
                <h3>Qualifications</h3>
                <div style="line-height:1.75; color:#394b3f;">
                    {!! nl2br(e($job->qualifications)) !!}
                </div>
            </section>
        @endif

        <div style="margin-top: 18px; display:flex; gap:10px; flex-wrap:wrap;">
            <a class="btn btn-secondary" href="{{ route('careers') }}">Back to Open Positions</a>
            <a class="btn btn-primary" href="{{ $applyHref }}">Apply via Email</a>
        </div>
    </article>

    <aside class="article-sidebar">
        <section class="sidebar-card reveal">
            <div class="sidebar-head">Job Summary</div>
            <div class="sidebar-row">
                <small>Department</small>
                <strong>{{ $job->department }}</strong>
            </div>
            <div class="sidebar-row">
                <small>Employment Type</small>
                <strong>{{ $job->employment_type }}</strong>
            </div>
            <div class="sidebar-row">
                <small>Location</small>
                <strong>{{ $job->location }}</strong>
            </div>
            <div class="sidebar-row">
                <small>Application Deadline</small>
                <strong>{{ $job->deadline_at?->format('F d, Y') ?? 'Open until filled' }}</strong>
            </div>
        </section>

        <section class="sidebar-card reveal" style="background: var(--green); color: rgba(255,255,255,.9);">
            <div class="sidebar-head" style="color: rgba(255,255,255,.75); border-bottom-color: rgba(255,255,255,.14);">Apply Through Email</div>
            <div style="padding: 14px 16px;">
                <p style="margin: 0 0 10px; line-height: 1.7;">
                    Send your resume and application letter to our HR email. Please include the job title in your subject line.
                </p>
                <div style="font-size:13px; padding:10px 12px; border-radius:10px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.14); margin-bottom:12px; word-break:break-word;">
                    {{ $job->application_email ?: 'hrd_buscosugarmill@yahoo.com' }}
                </div>
                <a class="btn btn-primary" href="{{ $applyHref }}" style="background: var(--gold); border-color: var(--gold); color:#553500;">Send Application</a>
            </div>
        </section>

        <section class="sidebar-card reveal">
            <div class="sidebar-head">More Open Positions</div>
            @forelse($relatedJobs as $relatedJob)
                <a class="related-link" href="{{ route('careers.show', $relatedJob) }}">
                    <small>{{ $relatedJob->department }} • {{ $relatedJob->employment_type }}</small>
                    <strong>{{ $relatedJob->title }}</strong>
                </a>
            @empty
                <div style="padding:14px 16px; color:#6a796d;">No other open positions in this department yet.</div>
            @endforelse
        </section>
    </aside>
</section>
@endsection
