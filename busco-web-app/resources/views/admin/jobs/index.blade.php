{{-- View: admin/jobs/index.blade.php | Purpose: Admin module page template. --}}

@extends('layouts.admin')

@section('title', 'Job Hiring Management')
@section('page_header_title', 'Job Hiring Management')
@section('page_header_subtitle', 'Create and maintain job openings for the public Careers page, including hired/closed updates.')

@section('content')
<div data-admin-ajax-list data-admin-list-url="{{ route('admin.jobs.index') }}">
    <section class="admin-section">
        <div class="form-card">
            <form method="GET" action="{{ route('admin.jobs.index') }}" data-admin-filter-form class="form-grid" style="gap:12px;">
                <div data-jobs-filter-grid style="display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap:12px; align-items:end;">
                    <div class="form-group">
                        <label for="job_search">Search</label>
                        <input id="job_search" name="search" type="text" class="form-input" data-admin-search value="{{ $filters['search'] }}" placeholder="Title, department, or location">
                    </div>

                    <div class="form-group">
                        <label for="job_department">Department</label>
                        <select id="job_department" name="department" class="form-input">
                            <option value="">All departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department }}" {{ $filters['department'] === $department ? 'selected' : '' }}>{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="job_type">Employment Type</label>
                        <select id="job_type" name="employment_type" class="form-input">
                            <option value="">All types</option>
                            @foreach($employmentTypes as $type)
                                <option value="{{ $type }}" {{ $filters['employment_type'] === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="job_status">Status</label>
                        <select id="job_status" name="status" class="form-input">
                            <option value="">All statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $filters['status'] === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; border-top:1px solid #edf2e9; padding-top:12px;">
                    <div style="color:#637266; font-size:.9rem;">
                        Search and filters update automatically as you type or select options.
                    </div>
                    <a href="{{ route('admin.jobs.create') }}" class="btn-admin-secondary" style="min-width:98px; text-align:center; background:#1b5e20; color:#fff; border-color:#1b5e20; text-decoration:none;">Add Job</a>
                </div>
            </form>
        </div>
    </section>

    <section class="admin-section">
        <div class="form-card" style="overflow:auto;" data-admin-results>
            @include('admin.jobs._results')
        </div>
    </section>
</div>

<style>
    @media (max-width: 1080px) {
        .admin-panel [data-jobs-filter-grid] {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }
    @media (max-width: 700px) {
        .admin-panel [data-jobs-filter-grid] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-list-filters.js') }}"></script>
@endpush
