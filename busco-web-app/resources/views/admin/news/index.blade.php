{{-- View: admin/news/index.blade.php | Purpose: Admin module page template. --}}

@extends('layouts.admin')

@section('title', 'News Management')
@section('page_header_title', 'News Management')
@section('page_header_subtitle', 'Manage news, achievements, events, and CSR/community posts in one module.')

@section('content')
<div data-admin-ajax-list data-admin-list-url="{{ route('admin.news.index') }}">
    <section class="admin-section">
        <div class="form-card">
            <form method="GET" action="{{ route('admin.news.index') }}" data-admin-filter-form class="form-grid" style="gap:12px;">
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
                        Filters update automatically. Manage articles below.
                    </div>
                    <a href="{{ route('admin.news.create') }}" class="btn-admin-secondary" style="min-width:98px; text-align:center; background:#1b5e20; color:#fff; border-color:#1b5e20; text-decoration:none;">Add News</a>
                </div>
            </form>
        </div>
    </section>

    <section class="admin-section">
        <div class="form-card" style="overflow:auto;" data-admin-results>
            @include('admin.news._results')
        </div>
    </section>
</div>

<style>
    @media (max-width: 980px) {
        .admin-panel [data-news-filter-grid] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-list-filters.js') }}"></script>
@endpush
