@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | News & Achievements')
@section('meta_description', 'Company announcements, milestones, and event updates from BUSCO Sugar Milling Co., Inc.')

@section('content')
<section class="page-shell">
    <header class="page-header reveal">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>News & Achievements</span>
        </div>
        <h1 class="page-title">News & Achievements</h1>
        <p class="page-subtitle">Company announcements, milestones, awards, and events.</p>
    </header>

    <div class="news-controls reveal" style="display:flex; gap:12px; align-items:end; flex-wrap:wrap;">
        <form method="GET" action="{{ route('news.index') }}" style="display:flex; gap:12px; align-items:end; flex-wrap:wrap; width:100%;">
            <div class="control-group">
                <label for="newsCategory">Category</label>
                <select id="newsCategory" name="category" class="control-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ $selectedCategory === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn btn-secondary">Apply Filter</button>
                @if($selectedCategory !== '')
                    <a href="{{ route('news.index') }}" class="btn btn-secondary">Clear</a>
                @endif
            </div>

            <div class="result-count" style="margin-left:auto;">
                Showing <strong>{{ $news->total() }}</strong> article{{ $news->total() === 1 ? '' : 's' }}
                @if($selectedCategory !== '')
                    in <strong>{{ $selectedCategory }}</strong>
                @endif
            </div>
        </form>
    </div>

    <div class="news-grid" id="newsGrid">
        @forelse($news as $article)
            <a class="news-card reveal" href="{{ route('news.show', $article) }}">
                @if($article->image)
                    <div class="news-thumb">
                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" style="width:100%; height:100%; object-fit:cover;">
                        @if($article->is_featured)
                            <span class="news-featured">Featured</span>
                        @endif
                    </div>
                @else
                    <div class="news-thumb" style="display:flex; align-items:center; justify-content:center; background:linear-gradient(180deg, #f5f8f2 0%, #eef3ea 100%); border-bottom:1px solid #e3eadc;">
                        <div style="text-align:center; color:#6d7c70; padding:14px;">
                            <div style="font-weight:700; font-size:.95rem; color:#516256;">No uploaded photo</div>
                            <div style="font-size:.8rem; margin-top:4px;">{{ $article->category }}</div>
                        </div>
                        @if($article->is_featured)
                            <span class="news-featured">Featured</span>
                        @endif
                    </div>
                @endif

                <div class="news-body">
                    <div class="news-meta">
                        <span class="pill">{{ $article->category }}</span>
                        <span class="preview-date">{{ $article->created_at?->format('M d, Y') }}</span>
                    </div>
                    <h2 class="news-title">{{ $article->title }}</h2>
                    <p class="news-excerpt">{{ $article->sub_title ?: $article->excerpt }}</p>
                    <div class="news-read">Read More -></div>
                </div>
            </a>
        @empty
            <div class="empty-state" style="display:block;">
                <h3>No published articles yet</h3>
                <p>Check back soon for company announcements and updates.</p>
            </div>
        @endforelse
    </div>

    @if($news->hasPages())
        <nav class="news-pagination" aria-label="News pagination">
            @if($news->onFirstPage())
                <span class="page-chip" aria-disabled="true">Prev</span>
            @else
                <a class="page-chip" href="{{ $news->previousPageUrl() }}">Prev</a>
            @endif

            @foreach($news->getUrlRange(1, $news->lastPage()) as $page => $url)
                <a class="page-chip {{ $news->currentPage() === $page ? 'active' : '' }}" href="{{ $url }}" @if($news->currentPage() === $page) aria-current="page" @endif>{{ $page }}</a>
            @endforeach

            @if($news->hasMorePages())
                <a class="page-chip" href="{{ $news->nextPageUrl() }}">Next</a>
            @else
                <span class="page-chip" aria-disabled="true">Next</span>
            @endif
        </nav>
    @endif
</section>
@endsection
