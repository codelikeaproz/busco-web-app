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
            Static careers listing for the initial frontend phase. This can later connect to a job module if needed.
        </p>
    </header>

    <section class="surface reveal" style="padding: 22px;">
        <h2 class="section-title" style="font-size: clamp(26px, 3.3vw, 36px);">Work With BUSCO</h2>
        <p class="section-copy">
            BUSCO seeks professionals who value operational discipline, technical excellence, and collaboration.
            Submit resumes to <a href="mailto:hrd_buscosugarmill@yahoo.com" style="color: var(--green-mid);">hrd_buscosugarmill@yahoo.com</a>.
        </p>
    </section>

    <div class="jobs-grid">
        <article class="job-card reveal">
            <div>
                <h3>Associate Engineer</h3>
                <p>Special Project Office - Quezon, Bukidnon</p>
            </div>
            <div class="job-tags">
                <span class="tag gold">Now Hiring</span>
                <span class="tag">Full-time</span>
            </div>
        </article>

        <article class="job-card reveal">
            <div>
                <h3>Agricultural Technician</h3>
                <p>Farm Operations - Quezon, Bukidnon</p>
            </div>
            <div class="job-tags">
                <span class="tag">Full-time</span>
            </div>
        </article>

        <article class="job-card reveal">
            <div>
                <h3>Chemical Laboratory Analyst</h3>
                <p>Quality Control - Millsite</p>
            </div>
            <div class="job-tags">
                <span class="tag">Full-time</span>
            </div>
        </article>

        <article class="job-card reveal">
            <div>
                <h3>HR & Administrative Staff</h3>
                <p>Human Resources Department - Head Office</p>
            </div>
            <div class="job-tags">
                <span class="tag">Full-time</span>
            </div>
        </article>
    </div>
</section>
@endsection

