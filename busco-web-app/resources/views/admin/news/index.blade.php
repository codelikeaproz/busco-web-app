{{-- View: admin/news/index.blade.php | Purpose: Admin module page template. --}}

@extends('layouts.admin')

@section('title', 'News Management')
@section('page_header_title', 'News Management')
@section('page_header_subtitle', 'Manage news, achievements, events, and CSR/community posts in one module.')

@section('content')
{{-- Filter toolbar for category/status/trash state --}}
<section class="admin-section">
    <div class="form-card">
        <form method="GET" action="{{ route('admin.news.index') }}" class="form-grid" style="gap:12px;">
            <div data-news-filter-grid style="display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:12px; align-items:end;">
            <div class="form-group">
                <label for="filter_category">Category</label>
                <select id="filter_category" name="category" class="form-input">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ $filters['category'] === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="filter_status">Status</label>
                <select id="filter_status" name="status" class="form-input">
                    <option value="">All statuses</option>
                    <option value="draft" {{ $filters['status'] === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ $filters['status'] === 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            <div class="form-group">
                <label for="filter_trashed">Trash</label>
                <select id="filter_trashed" name="trashed" class="form-input">
                    <option value="">Include all</option>
                    <option value="without" {{ $filters['trashed'] === 'without' ? 'selected' : '' }}>Without trashed</option>
                    <option value="only" {{ $filters['trashed'] === 'only' ? 'selected' : '' }}>Only trashed</option>
                </select>
            </div>
            </div>
            <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; border-top:1px solid #edf2e9; padding-top:12px;">
                <div style="color:#637266; font-size:.9rem;">
                    Filter records by category, publish state, or trash status before managing articles.
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end;">
                    <button type="submit" class="btn-admin" style="min-width:84px;">Apply</button>
                    <a href="{{ route('admin.news.index') }}" class="btn-admin-secondary" style="min-width:84px; text-align:center;">Reset</a>
                    <a href="{{ route('admin.news.create') }}" class="btn-admin-secondary" style="min-width:98px; text-align:center; background:#1b5e20; color:#fff; border-color:#1b5e20;">Add News</a>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- News records table with inline admin actions (edit/publish/trash/restore) --}}
<section class="admin-section">
    <div class="form-card" style="overflow:auto;">
        <table style="width:100%; border-collapse:collapse; min-width:920px;">
            <thead>
                <tr style="text-align:left; border-bottom:1px solid #e7edde;">
                    <th style="padding:10px 8px;">No.</th>
                    <th style="padding:10px 8px;">Title</th>
                    <th style="padding:10px 8px;">Category</th>
                    <th style="padding:10px 8px;">Status</th>
                    <th style="padding:10px 8px;">Featured</th>
                    <th style="padding:10px 8px;">Publish Date</th>
                    <th style="padding:10px 8px;">State</th>
                    <th style="padding:10px 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $article)
                    <tr style="border-bottom:1px solid #eef2e8; {{ $article->trashed() ? 'opacity:.75;background:#fcfcfa;' : '' }}">
                        <td style="padding:12px 8px; white-space:nowrap; color:#4f5f55; font-weight:700;">
                            {{ ($news->firstItem() ?? 1) + $loop->index }}
                        </td>
                        <td style="padding:12px 8px; vertical-align:top;">
                            <div style="font-weight:700; color:#173f1b;">{{ $article->title }}</div>
                            <div style="font-size:.85rem; color:#637266; margin-top:4px;">
                                @if($article->trashed())
                                    Trashed {{ $article->deleted_at?->diffForHumans() }}
                                @else
                                    {{ $article->created_at?->format('M d, Y h:i A') }}
                                @endif
                            </div>
                        </td>
                        <td style="padding:12px 8px;">{{ $article->category }}</td>
                        <td style="padding:12px 8px;">{{ ucfirst($article->status) }}</td>
                        <td style="padding:12px 8px;">{{ $article->is_featured ? 'Yes' : 'No' }}</td>
                        <td style="padding:12px 8px;">{{ $article->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                        <td style="padding:12px 8px;">{{ $article->trashed() ? 'Trashed' : 'Active' }}</td>
                        <td style="padding:12px 8px;">
                            <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                                @if(!$article->trashed())
                                    <a class="btn-admin-secondary" href="{{ route('admin.news.edit', $article) }}" style="padding:6px 10px; min-width:46px; text-align:center;">Edit</a>
                                    <form method="POST" action="{{ route('admin.news.toggle', $article) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-admin-secondary" style="padding:6px 10px; min-width:84px; text-align:center;">{{ $article->status === 'published' ? 'Unpublish' : 'Publish' }}</button>
                                    </form>
                                    @if($article->status === 'published')
                                        <a class="btn-admin-secondary" href="{{ route('news.show', $article) }}" target="_blank" rel="noopener" style="padding:6px 10px; min-width:46px; text-align:center;">View</a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.news.destroy', $article) }}" style="display:inline;" data-confirm-title="Move Article to Trash" data-confirm-message="Move this article to trash?" data-confirm-submit-label="Move to Trash">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-admin-secondary" style="padding:6px 10px; min-width:56px; text-align:center; color:#8d241e; border-color:#f0c8c4; background:#fff4f3;">Trash</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.news.restore', $article->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-admin-secondary" style="padding:6px 10px; min-width:70px; text-align:center;">Restore</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding:20px 8px; color:#637266;">No news articles found for the current filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($news->hasPages())
            {{-- Compact shared pagination component --}}
            @include('partials.custom-pagination', [
                'paginator' => $news,
                'navLabel' => 'Admin news pagination',
                'compact' => true,
            ])
        @endif
    </div>
</section>

{{-- Responsive filter grid collapse for smaller screens --}}
<style>
    @media (max-width: 980px) {
        .admin-panel [data-news-filter-grid] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
