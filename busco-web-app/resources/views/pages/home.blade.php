@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | Home')
@section('meta_description', 'Official BUSCO Sugar Milling Co., Inc. website with company profile, news, and Quedan updates.')

@section('content')
<section class="home-hero">
    <div class="hero-shell">
        <div class="reveal">
            <h1 class="hero-title">From Cane Fields to <span>Trusted Sugar Quality</span></h1>
            <p class="hero-copy">
                BUSCO Sugar Milling Co., Inc. serves Bukidnon and neighboring communities through reliable milling operations,
                quality assurance, and farmer-centered collaboration.
            </p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <strong>50+</strong>
                    <span>Years Serving</span>
                </div>
                <div class="hero-stat">
                    <strong>5K+</strong>
                    <span>Planter Partners</span>
                </div>
                <div class="hero-stat">
                    <strong>24/7</strong>
                    <span>Mill Operations</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-shell">
    <div class="reveal">
        <span class="eyebrow">Latest Updates</span>
        <h2 class="section-title">News & Achievements Preview</h2>
        <p class="section-copy">Static preview cards for now. CRUD integration will follow after this static phase.</p>
    </div>

    <div class="news-preview-grid">
        <a class="preview-card reveal" href="{{ route('news.show') }}">
            <div class="preview-thumb media-achievements"></div>
            <div class="preview-body">
                <div class="preview-meta">
                    <span class="pill">Achievements</span>
                    <span class="preview-date">Feb 20, 2026</span>
                </div>
                <h3 class="preview-title">BUSCO Recognized for Quality Milling Standards</h3>
                <p class="preview-copy">Recognition received for operational consistency and product quality benchmarks.</p>
            </div>
        </a>

        <a class="preview-card reveal" href="{{ route('news.show') }}">
            <div class="preview-thumb media-announcement"></div>
            <div class="preview-body">
                <div class="preview-meta">
                    <span class="pill">Announcements</span>
                    <span class="preview-date">Feb 14, 2026</span>
                </div>
                <h3 class="preview-title">Advisory on Weekly Milling Schedule</h3>
                <p class="preview-copy">Operational reminders and schedule guidance for partner farmers and stakeholders.</p>
            </div>
        </a>

        <a class="preview-card reveal" href="{{ route('news.show') }}">
            <div class="preview-thumb media-training"></div>
            <div class="preview-body">
                <div class="preview-meta">
                    <span class="pill">Events</span>
                    <span class="preview-date">Jan 30, 2026</span>
                </div>
                <h3 class="preview-title">Regional Agriculture Expo Participation</h3>
                <p class="preview-copy">Showcasing sustainable sugarcane farming and community partnership initiatives.</p>
            </div>
        </a>
    </div>
</section>

<section class="section-shell section-alt">
    <div class="reveal">
        <span class="eyebrow">Current Announcement</span>
        <h2 class="section-title">Active Quedan Price</h2>
    </div>

    <div class="quedan-spotlight reveal">
        <div class="quedan-top">
            <div class="buying-price-head">BUSCO BUYING PRICE</div>
            <div class="buying-price-dates">
                <span><strong>Trading Date:</strong> Jun. 5, 2025</span>
                <span><strong>Weekending:</strong> Jun. 1, 2025</span>
            </div>
            <div class="quedan-price">PHP 2,650.00</div>
            <div class="quedan-label">Net of Taxes & Liens</div>
        </div>
        <div class="quedan-bottom">
            <span><strong>Previous Week:</strong> May 30, 2025 - PHP 2,650.00</span>
            <span class="trend flat">SAME PRICE</span>
        </div>
        <p class="buying-note">Note: Negros buying price is Gross Price and Busco buying price is Net Price.</p>
    </div>
</section>

<section class="section-shell">
    <div class="home-community-block reveal">
        <div class="home-community-media">
            <span class="home-community-accent home-community-accent-top" aria-hidden="true"></span>
            <img src="{{ asset('img/training_events.webp') }}" alt="BUSCO farmer training and community learning session">
            <span class="home-community-accent home-community-accent-bottom" aria-hidden="true"></span>
        </div>

        <div class="home-community-content">
            <h4 class="home-community-kicker">Community & Social Responsibility</h4>
            <h2 class="home-community-title">Empowering Local Farming Communities</h2>
            <p class="home-community-copy">
                At BUSCO, our growth is tied to the prosperity of our surrounding communities. Through training,
                outreach, and farmer support programs, we help strengthen productivity, safety, and long-term sustainability
                in Butong, Quezon, and across Bukidnon.
            </p>

            <ul class="home-community-list" aria-label="Community impact highlights">
                <li>Farmer training sessions focused on better crop management and field productivity.</li>
                <li>Sustainable farming workshops supporting soil health and improved yields.</li>
                <li>Community support initiatives and local outreach programs in partner barangays.</li>
            </ul>

            <a class="btn btn-primary" href="{{ route('community') }}">View Our Impact</a>
        </div>
    </div>
</section>
@endsection
