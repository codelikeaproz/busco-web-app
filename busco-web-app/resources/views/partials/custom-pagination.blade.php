{{-- View: partials/custom-pagination.blade.php | Purpose: Reusable pagination UI partial for paginated lists. --}}

@php
    // Shared pagination UI configuration with optional compact sizing.
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Pagination\Paginator|null $paginator */
    $paginator = $paginator ?? null;
    $navLabel = $navLabel ?? 'Pagination';
    $compact = (bool) ($compact ?? false);
    $buttonMinHeight = $compact ? '36px' : '40px';
    $buttonPadding = $compact ? '8px 12px' : '9px 14px';
    $pagePadding = $compact ? '8px 10px' : '9px 12px';
    $radius = $compact ? '10px' : '12px';
    $gap = $compact ? '8px' : '10px';
    $marginTop = $compact ? '14px' : '18px';
    $paddingTop = $compact ? '12px' : '14px';
    $fontSize = $compact ? '.9rem' : '.95rem';
@endphp

@if($paginator && $paginator->hasPages())
    {{-- Pagination shell showing current page and controls --}}
    <nav aria-label="{{ $navLabel }}" style="margin-top:{{ $marginTop }}; padding-top:{{ $paddingTop }}; border-top:1px solid #e7edde; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div style="color:#637266; font-size:{{ $fontSize }}; line-height:1.2;">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </div>

        <div style="display:flex; align-items:center; gap:{{ $gap }}; flex-wrap:wrap;">
            @if($paginator->onFirstPage())
                <span style="min-height:{{ $buttonMinHeight }}; padding:{{ $buttonPadding }}; border-radius:{{ $radius }}; border:1px solid #dbe5d7; color:#9aa99d; background:#f8fbf6; display:inline-flex; align-items:center; justify-content:center; font-weight:600; line-height:1;">Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" style="min-height:{{ $buttonMinHeight }}; padding:{{ $buttonPadding }}; border-radius:{{ $radius }}; border:1px solid #dbe5d7; color:#1b5e20; background:#fff; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; justify-content:center; line-height:1;">Previous</a>
            @endif

            {{-- Full page number list; acceptable here because page counts are small in current modules --}}
            @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if($page === $paginator->currentPage())
                    <span aria-current="page" style="min-width:{{ $buttonMinHeight }}; min-height:{{ $buttonMinHeight }}; padding:{{ $pagePadding }}; border-radius:{{ $radius }}; border:1px solid #1b5e20; background:#1b5e20; color:#fff; font-weight:700; display:inline-flex; align-items:center; justify-content:center; line-height:1;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="min-width:{{ $buttonMinHeight }}; min-height:{{ $buttonMinHeight }}; padding:{{ $pagePadding }}; border-radius:{{ $radius }}; border:1px solid #dbe5d7; background:#fff; color:#1b5e20; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; justify-content:center; line-height:1;">{{ $page }}</a>
                @endif
            @endforeach

            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" style="min-height:{{ $buttonMinHeight }}; padding:{{ $buttonPadding }}; border-radius:{{ $radius }}; border:1px solid #dbe5d7; color:#1b5e20; background:#fff; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; justify-content:center; line-height:1;">Next</a>
            @else
                <span style="min-height:{{ $buttonMinHeight }}; padding:{{ $buttonPadding }}; border-radius:{{ $radius }}; border:1px solid #dbe5d7; color:#9aa99d; background:#f8fbf6; display:inline-flex; align-items:center; justify-content:center; font-weight:600; line-height:1;">Next</span>
            @endif
        </div>
    </nav>
@endif
