@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | Community & Training')
@section('meta_description', 'Community outreach, CSR initiatives, and farmer training programs of BUSCO.')

@section('content')
<section class="page-shell">
    <header class="page-header reveal">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Community & Training</span>
        </div>
        <h1 class="page-title">Community & Training</h1>
        <p class="page-subtitle">
            BUSCO community programs supporting farmer capability-building, outreach, and local partnerships.
        </p>
    </header>

    <div class="community-media-grid reveal">
        <article class="community-media-card">
            <img src="{{ asset('img/announcement.webp') }}" alt="BUSCO mill opening and operations">
            <h3>Operations & Community Presence</h3>
            <p>Continuing operations that support planters and nearby communities in Bukidnon.</p>
        </article>

        <article class="community-media-card">
            <img src="{{ asset('img/achievements.webp') }}" alt="BUSCO receiving achievement recognition">
            <h3>Corporate Achievements</h3>
            <p>Milestones and recognitions that reflect quality management and organizational standards.</p>
        </article>

        <article class="community-media-card">
            <img src="{{ asset('img/training_events.webp') }}" alt="BUSCO conducting farmer training program">
            <h3>Training & Extension</h3>
            <p>Hands-on trainings that improve farming practices, safety, and productivity outcomes.</p>
        </article>
    </div>

    <div class="info-grid">
        <article class="info-card reveal">
            <h3>CSR Programs</h3>
            <p>
                Local support initiatives focused on social welfare, environmental stewardship,
                and collaborative projects with community groups.
            </p>
        </article>

        <article class="info-card reveal">
            <h3>Farmer Trainings</h3>
            <p>
                Technical sessions covering crop management, harvest readiness, and coordinated delivery standards.
            </p>
        </article>

        <article class="info-card reveal">
            <h3>Workshops</h3>
            <p>
                Skills enhancement workshops for farm and mill stakeholders with practical, operations-aligned topics.
            </p>
        </article>

        <article class="info-card reveal">
            <h3>Outreach Activities</h3>
            <p>
                Scheduled outreach visits and engagement events in partner communities across Bukidnon.
            </p>
        </article>

        <article class="info-card reveal">
            <h3>Safety Awareness</h3>
            <p>
                Information campaigns supporting safe practices in farm transport, handling, and process coordination.
            </p>
        </article>

        <article class="info-card reveal">
            <h3>Youth Engagement</h3>
            <p>
                Internship and learning opportunities for students interested in agricultural and industrial operations.
            </p>
        </article>
    </div>
</section>
@endsection
