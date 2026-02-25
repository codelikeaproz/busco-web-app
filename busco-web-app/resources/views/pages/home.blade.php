{{-- View: pages/home.blade.php | Purpose: Public homepage with dynamic previews. --}}

@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | Home')
@section('meta_description', 'Official BUSCO Sugar Milling Co., Inc. website with company profile, news, and Quedan updates.')

@section('content')
{{-- Hero / brand positioning section --}}
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

{{-- Latest published news preview cards --}}
<section class="section-shell">
    <div class="reveal">
        <span class="eyebrow">Latest Updates</span>
        <h2 class="section-title">Latest News & Achievements</h2>
        <p class="section-copy">Latest published BUSCO news articles are now pulled from the database and linked to the public article pages.</p>
    </div>

    <div class="news-preview-grid">
        @forelse($latestNews as $article)
            <a class="preview-card reveal" href="{{ route('news.show', $article) }}">
                @if($article->image)
                    <div class="preview-thumb">
                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                @else
                    <div class="preview-thumb" style="display:flex; align-items:center; justify-content:center; background:linear-gradient(180deg, #f5f8f2 0%, #eef3ea 100%); border-bottom:1px solid #e3eadc;">
                        <div style="text-align:center; color:#6d7c70; padding:12px;">
                            <div style="font-weight:700; font-size:.9rem; color:#516256;">No uploaded photo</div>
                            <div style="font-size:.78rem; margin-top:4px;">{{ $article->category }}</div>
                        </div>
                    </div>
                @endif
                <div class="preview-body">
                    <div class="preview-meta">
                        <span class="pill">{{ $article->category }}</span>
                        <span class="preview-date">{{ $article->created_at?->format('M d, Y') }}</span>
                    </div>
                    <h3 class="preview-title">{{ $article->title }}</h3>
                    <p class="preview-copy">{{ $article->sub_title ?: $article->excerpt }}</p>
                </div>
            </a>
        @empty
            <div class="preview-card reveal" style="grid-column:1 / -1; text-decoration:none; cursor:default;">
                <div class="preview-body">
                    <div class="preview-meta">
                        <span class="pill">No News Yet</span>
                    </div>
                    <h3 class="preview-title">News preview will appear here after seeding or publishing articles.</h3>
                    <p class="preview-copy">Run <code>php artisan migrate:fresh --seed</code> or publish articles from the admin panel to populate this section.</p>
                    <div style="margin-top:10px;">
                        <a class="btn btn-secondary" href="{{ route('news.index') }}">Open News Page</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="reveal" style="margin-top: 16px;">
        <a class="btn btn-secondary" href="{{ route('news.index') }}">View All News</a>
    </div>
</section>

{{-- Active Quedan price spotlight --}}
<section class="section-shell section-alt">
    <div class="reveal">
        <span class="eyebrow">Current Announcement</span>
        <h2 class="section-title">Active Quedan Price</h2>
    </div>

    @if($activeQuedan)
        @php
            // Precompute UI labels/classes so the markup stays readable.
            $homeTrendClass = match($activeQuedan->trend) {
                'UP' => 'up',
                'DOWN' => 'down',
                'NO CHANGE' => 'flat',
                default => 'flat',
            };
            $homeTrendLabel = $activeQuedan->trend ?? 'NO CHANGE';
            $homeDifferenceLabel = $activeQuedan->difference === null
                ? 'N/A'
                : ((float) $activeQuedan->difference > 0 ? '+ ' : '') . 'PHP ' . number_format((float) $activeQuedan->difference, 2);
        @endphp
        <div class="quedan-spotlight reveal">
            <div class="quedan-top">
                <div class="buying-price-head">BUSCO BUYING PRICE</div>
                <div class="buying-price-dates">
                    <span><strong>Trading Date:</strong> {{ $activeQuedan->trading_date?->format('M. j, Y') }}</span>
                    <span><strong>Weekending:</strong> {{ $activeQuedan->weekending_date?->format('M. j, Y') }}</span>
                </div>
                <div class="quedan-price">{{ $activeQuedan->formatted_price }}</div>
                <div class="quedan-label">{{ $activeQuedan->price_subtext ?: 'Net of Taxes & Liens' }}</div>
            </div>
            <div class="quedan-bottom">
                @if($previousQuedan)
                    <span><strong>Previous Week:</strong> {{ $previousQuedan->trading_date?->format('M. j, Y') }} - {{ $previousQuedan->formatted_price }}</span>
                @else
                    <span><strong>Previous Week:</strong> No previous record</span>
                @endif
                <span><strong>Difference:</strong> {{ $homeDifferenceLabel }}</span>
                <span class="trend {{ $homeTrendClass }}">{{ $homeTrendLabel }}</span>
            </div>
            <p class="buying-note">{{ $activeQuedan->notes ?: 'Note: Negros buying price is Gross Price and Busco buying price is Net Price.' }}</p>
        </div>
    @else
        <div class="quedan-spotlight reveal">
            <div class="quedan-top">
                <div class="buying-price-head">BUSCO BUYING PRICE</div>
                <div class="buying-price-dates">
                    <span><strong>Trading Date:</strong> Pending</span>
                    <span><strong>Weekending:</strong> Pending</span>
                </div>
                <div class="quedan-price">PHP 0.00</div>
                <div class="quedan-label">Net of Taxes & Liens</div>
            </div>
            <div class="quedan-bottom">
                <span><strong>Previous Week:</strong> No previous record</span>
                <span><strong>Difference:</strong> N/A</span>
                <span class="trend flat">NO CHANGE</span>
            </div>
            <p class="buying-note">Post the first Quedan record from the admin panel to activate this homepage section.</p>
        </div>
    @endif

    <div class="reveal" style="margin-top: 14px;">
        <a class="btn btn-secondary" href="{{ route('quedan') }}">View Full Quedan Page</a>
    </div>
</section>

{{-- Community impact highlight and CTA to CSR/Community news --}}
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

            <a class="btn btn-primary" href="{{ route('news.index', ['category' => 'CSR / Community']) }}">View Our Impact</a>
        </div>
    </div>
</section>
@endsection
