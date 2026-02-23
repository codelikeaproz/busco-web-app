@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | About')
@section('meta_description', 'Company overview, history, mission, and vision of BUSCO Sugar Milling Co., Inc.')

@section('content')
<section class="page-shell">
    <header class="page-header reveal">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>About</span>
        </div>
        <h1 class="page-title">About BUSCO</h1>
        <p class="page-subtitle">
            Corporate background, industry role, and long-term commitment to quality sugar milling in Bukidnon.
        </p>
    </header>

    <div class="about-intro-block reveal">
        <div class="about-intro-content">
            <h4 class="about-intro-kicker">Who We Are</h4>
            <h2 class="about-intro-title">Driving the Sweet Success of the Philippine Sugar Industry</h2>
            <p class="about-intro-copy">
                BUSCO Sugar Milling Co., Inc. stands as a pillar of industrial strength in Butong, Quezon, Bukidnon.
                Established to harness the rich agricultural potential of the region, BUSCO has grown into one of the
                country's recognized sugar milling facilities.
            </p>
            <p class="about-intro-copy">
                With a strong commitment to modernization and operational reliability, BUSCO connects local farmers to
                the wider market while maintaining quality-focused milling standards and supporting regional growth.
            </p>

            <div class="about-intro-stats">
                <div class="about-intro-stat">
                    <strong>15K+</strong>
                    <span>Daily TCD Capacity</span>
                </div>
                <div class="about-intro-stat">
                    <strong>40+</strong>
                    <span>Years of Excellence</span>
                </div>
            </div>
        </div>

        <div class="about-intro-media">
            <span class="about-intro-frame" aria-hidden="true"></span>
            <img src="{{ asset('img/announcement.webp') }}" alt="BUSCO Sugar Milling facility and operations">
        </div>
    </div>
</section>

<section class="section-shell section-alt">
    <div class="about-history-head reveal">
        <h2 class="section-title">Our History</h2>
        <p class="section-copy">From a bold vision in Bukidnon to a recognized sugar milling leader, this is the BUSCO journey.</p>
    </div>

    <div class="about-history-timeline reveal">
        <div class="about-history-line" aria-hidden="true"></div>

        <article class="about-history-item">
            <div class="about-history-dot about-history-dot-gold" aria-hidden="true"></div>
            <div class="about-history-card about-history-card-left">
                <h3>1980</h3>
                <h4>Foundation</h4>
                <p>BUSCO was established in Butong, Quezon, Bukidnon to support the region's strong sugarcane potential.</p>
            </div>
        </article>

        <article class="about-history-item">
            <div class="about-history-dot" aria-hidden="true"></div>
            <div class="about-history-card about-history-card-right">
                <h3>1995</h3>
                <h4>Expansion Phase</h4>
                <p>Major infrastructure upgrades expanded milling capacity and improved service coverage for more farmers.</p>
            </div>
        </article>

        <article class="about-history-item">
            <div class="about-history-dot" aria-hidden="true"></div>
            <div class="about-history-card about-history-card-left">
                <h3>2010</h3>
                <h4>Technological Modernization</h4>
                <p>Automation and process improvements enhanced sugar recovery, safety, and plant reliability.</p>
            </div>
        </article>

        <article class="about-history-item">
            <div class="about-history-dot about-history-dot-gold" aria-hidden="true"></div>
            <div class="about-history-card about-history-card-right">
                <h3>Present Day</h3>
                <h4>Sustainable Leadership</h4>
                <p>BUSCO continues to advance sustainable milling, community development, and dependable Quedan performance.</p>
            </div>
        </article>
    </div>
</section>

<section class="section-shell about-vm-section">
    <div class="about-vm-grid">
        <article class="about-vm-card reveal">
            <div class="about-vm-icon" aria-hidden="true">V</div>
            <div>
                <h3>Our Vision</h3>
                <p>
                    To be a premier sugar milling company recognized for operational excellence, sustainable practices,
                    and meaningful contribution to stakeholder and community prosperity.
                </p>
            </div>
        </article>

        <article class="about-vm-card reveal">
            <div class="about-vm-icon" aria-hidden="true">M</div>
            <div>
                <h3>Our Mission</h3>
                <p>
                    To produce high-quality sugar products through efficient and reliable milling operations while
                    supporting farmers, employees, and the Bukidnon community through responsible stewardship.
                </p>
            </div>
        </article>
    </div>
</section>

<section class="section-shell">
    <div class="about-values-head reveal">
        <h4 class="about-intro-kicker">What Drives Us</h4>
        <h2 class="section-title">Our Core Values</h2>
    </div>

    <div class="about-values-grid">
        <article class="about-value-card reveal">
            <div class="about-value-icon" aria-hidden="true">S</div>
            <h3>Safety</h3>
            <p>We prioritize the well-being of our workforce and surrounding communities in every operation.</p>
        </article>

        <article class="about-value-card reveal">
            <div class="about-value-icon" aria-hidden="true">Q</div>
            <h3>Quality</h3>
            <p>We maintain high production standards to deliver dependable sugar milling performance and output.</p>
        </article>

        <article class="about-value-card reveal">
            <div class="about-value-icon" aria-hidden="true">C</div>
            <h3>Community</h3>
            <p>We work as partners in progress for the growth and development of Bukidnon farming communities.</p>
        </article>

        <article class="about-value-card reveal">
            <div class="about-value-icon" aria-hidden="true">G</div>
            <h3>Growth</h3>
            <p>We embrace innovation and sustainable practices to support long-term resilience and shared success.</p>
        </article>
    </div>
</section>
@endsection
