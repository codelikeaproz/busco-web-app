@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | Services & Operations')
@section('meta_description', 'Overview of BUSCO services including sugar milling, procurement, quality assurance, and distribution support.')

@section('content')
<section class="page-shell">
    <header class="page-header reveal">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Services</span>
        </div>
        <h1 class="page-title">Services & Operations</h1>
        <p class="page-subtitle">
            Key operational services supporting farmers, logistics, and sugar production quality.
        </p>
    </header>

    <div class="info-grid">
        <article class="info-card reveal">
            <h3>Sugar Milling</h3>
            <p>
                End-to-end milling operations from cane delivery to sugar output, with continuous monitoring and
                technical coordination across stations.
            </p>
        </article>

        <article class="info-card reveal">
            <h3>Cane Procurement</h3>
            <p>
                Structured procurement coordination with partner planters and field teams to improve milling flow,
                scheduling, and receiving efficiency.
            </p>
        </article>

        <article class="info-card reveal">
            <h3>Quality Assurance</h3>
            <p>
                Laboratory checks and process controls are maintained to meet expected production specifications and
                support consistent product standards.
            </p>
        </article>

        <article class="info-card reveal">
            <h3>Distribution Support</h3>
            <p>
                Coordination of storage and dispatch schedules to align with demand and maintain stable supply movement.
            </p>
        </article>

        <article class="info-card reveal">
            <h3>Planter Technical Support</h3>
            <p>
                Field support and guidance on practices that improve cane quality, harvest timing, and delivery readiness.
            </p>
        </article>

        <article class="info-card reveal">
            <h3>Operational Safety</h3>
            <p>
                Safety procedures and maintenance protocols are enforced throughout operations to protect personnel and
                reduce downtime risk.
            </p>
        </article>
    </div>
</section>
@endsection
