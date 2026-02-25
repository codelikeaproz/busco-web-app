{{-- View: pages/news/show.blade.php | Purpose: Public news article detail page. --}}

@extends('layouts.app')

@section('title', $news->title . ' | BUSCO')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($news->sub_title ?: $news->content), 155))

{{-- Resolve gallery images once so the template can reuse normalized URLs. --}}
@php($articleImages = $news->gallery_images)

@section('content')
{{-- Article hero header with breadcrumb and metadata --}}
<section class="article-hero">
    <div class="article-hero-shell">
        <div class="breadcrumb" style="color: rgba(255,255,255,.72);">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,.82);">Home</a>
            <span>/</span>
            <a href="{{ route('news.index') }}" style="color: rgba(255,255,255,.82);">News & Achievements</a>
            <span>/</span>
            <span>Article</span>
        </div>

        @if($news->is_featured)
            <span class="eyebrow" style="background: rgba(249,168,37,.2); border-color: rgba(249,168,37,.5); color: #ffd77a;">Featured {{ $news->category }}</span>
        @endif
        <h1>{{ $news->title }}</h1>
        <div class="article-meta">
            <span>Published: {{ $news->created_at?->format('F d, Y') }}</span>
            <span>Category: {{ $news->category }}</span>
            <span>Status: {{ ucfirst($news->status) }}</span>
        </div>
    </div>
</section>

{{-- Two-column article layout: main content + sidebar --}}
<section class="article-layout">
    <article class="article-content reveal">
        @if($news->sub_title)
            <p class="article-highlight">{{ $news->sub_title }}</p>
        @endif

        <div style="line-height:1.9; color:#32433a;">
            {!! nl2br(e($news->content)) !!}
        </div>

        @if(count($articleImages))
            {{-- Render uploaded article gallery images (if any) --}}
            <section style="margin-top:18px;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:12px; flex-wrap:wrap;">
                    <h2 style="margin:0; font-size:1.05rem; color:#1c3d20;">Article Images</h2>
                    <small style="color:#607062;">{{ count($articleImages) }} image{{ count($articleImages) > 1 ? 's' : '' }}</small>
                </div>

                <div style="display:grid; grid-template-columns:1fr; gap:14px;">
                    @foreach($articleImages as $image)
                        <div style="width:100%; aspect-ratio:16 / 9; border-radius:14px; overflow:hidden; border:1px solid #e1e9de; background:#f8fbf7;">
                            <img
                                src="{{ $image['url'] }}"
                                alt="{{ $news->title }} image {{ $loop->iteration }}"
                                style="display:block; width:100%; height:100%; object-fit:cover;"
                            >
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <div style="margin-top: 18px;">
            <a class="btn btn-secondary" href="{{ route('news.index') }}">Back to All News</a>
        </div>
    </article>

    {{-- Sidebar metadata, related articles, and contact CTA --}}
    <aside class="article-sidebar">
        <section class="sidebar-card reveal">
            <div class="sidebar-head">Article Details</div>
            <div class="sidebar-row">
                <small>Published</small>
                <strong>{{ $news->created_at?->format('F d, Y') }}</strong>
            </div>
            <div class="sidebar-row">
                <small>Category</small>
                <strong>{{ $news->category }}</strong>
            </div>
            <div class="sidebar-row">
                <small>Featured</small>
                <strong>{{ $news->is_featured ? 'Yes' : 'No' }}</strong>
            </div>
        </section>

        <section class="sidebar-card reveal">
            <div class="sidebar-head">More Articles</div>
            @forelse($related as $article)
                <a class="related-link" href="{{ route('news.show', $article) }}">
                    <small>{{ $article->category }}</small>
                    <strong>{{ $article->title }}</strong>
                </a>
            @empty
                <div style="padding:14px 16px; color:#6a796d;">No related published articles yet.</div>
            @endforelse
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
