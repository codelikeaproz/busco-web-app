@extends('layouts.app')

@section('title', 'BUSCO Recognized for Quality Milling Standards | BUSCO')
@section('meta_description', 'Sample static article page for BUSCO News & Achievements.')

@section('content')
<section class="article-hero">
    <div class="article-hero-shell">
        <div class="breadcrumb" style="color: rgba(255,255,255,.72);">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,.82);">Home</a>
            <span>/</span>
            <a href="{{ route('news.index') }}" style="color: rgba(255,255,255,.82);">News & Achievements</a>
            <span>/</span>
            <span>Article</span>
        </div>

        <span class="eyebrow" style="background: rgba(249,168,37,.2); border-color: rgba(249,168,37,.5); color: #ffd77a;">Featured Achievement</span>
        <h1>BUSCO Recognized for Quality Milling Standards</h1>
        <div class="article-meta">
            <span>Published: February 20, 2026</span>
            <span>Category: Achievements</span>
            <span>Reading Time: 5 minutes</span>
        </div>
    </div>
</section>

<section class="article-layout">
    <article class="article-content reveal">
        <p class="article-highlight">
            BUSCO Sugar Milling Co., Inc. received recognition for maintaining consistent technical performance and
            quality benchmarks throughout the recent milling season.
        </p>

        <p>
            The recognition was presented during a regional sugar industry gathering attended by planters, mill operators,
            and partner institutions. BUSCO was acknowledged for operational reliability, extraction performance,
            and cross-functional process discipline.
        </p>

        <p>
            Management emphasized that the recognition reflects the combined effort of operations, quality teams,
            engineering, and partner planters who contributed throughout the season.
        </p>

        <h2>Recognition Highlights</h2>
        <div class="article-box">
            <h3>Season Performance</h3>
            <ul>
                <li>High plant availability during peak operations</li>
                <li>Improved extraction efficiency compared to prior season</li>
                <li>Consistent product quality checks across milling stations</li>
                <li>Strong safety compliance during operations</li>
            </ul>
        </div>

        <p>
            Alongside technical metrics, BUSCO also highlighted continuing support for partner farmers through training,
            field coordination, and advisory activities that improve cane quality and harvest preparation.
        </p>

        <blockquote class="article-quote">
            <p>Quality is a shared commitment across the entire BUSCO operation and partner network.</p>
            <cite>BUSCO Communications Office</cite>
        </blockquote>

        <h2>Next Phase</h2>
        <p>
            Planned improvements for the next season include targeted equipment upgrades,
            stronger process monitoring, and expanded farmer engagement programs to sustain performance momentum.
        </p>

        <p>
            This static article page is intentionally prepared ahead of CRUD integration to lock visual direction and
            page structure first, as requested.
        </p>

        <a class="btn btn-secondary" href="{{ route('news.index') }}">Back to All News</a>
    </article>

    <aside class="article-sidebar">
        <section class="sidebar-card reveal">
            <div class="sidebar-head">Article Details</div>
            <div class="sidebar-row">
                <small>Published</small>
                <strong>February 20, 2026</strong>
            </div>
            <div class="sidebar-row">
                <small>Category</small>
                <strong>Achievements</strong>
            </div>
            <div class="sidebar-row">
                <small>Status</small>
                <strong>Featured</strong>
            </div>
        </section>

        <section class="sidebar-card reveal">
            <div class="sidebar-head">More Articles</div>
            <a class="related-link" href="{{ route('news.show') }}">
                <small>Announcements</small>
                <strong>Advisory on Milling Schedule for the Week</strong>
            </a>
            <a class="related-link" href="{{ route('news.show') }}">
                <small>Events</small>
                <strong>BUSCO Participates in Regional Agriculture Expo</strong>
            </a>
            <a class="related-link" href="{{ route('news.show') }}">
                <small>Community</small>
                <strong>Farmer Training Program Conducted in Bukidnon</strong>
            </a>
        </section>

        <section class="sidebar-card reveal" style="background: var(--green); color: rgba(255,255,255,.88);">
            <div class="sidebar-head" style="color: rgba(255,255,255,.75); border-bottom-color: rgba(255,255,255,.15);">Need Assistance?</div>
            <div style="padding: 14px 16px;">
                <p style="margin: 0 0 12px; line-height: 1.7;">For corporate inquiries and partnership concerns, contact our team.</p>
                <a class="btn btn-primary" href="{{ route('contact') }}" style="background: var(--gold); border-color: var(--gold); color: #553500;">Contact BUSCO</a>
            </div>
        </section>
    </aside>
</section>
@endsection
