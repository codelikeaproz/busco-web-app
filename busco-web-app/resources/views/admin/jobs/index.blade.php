@extends('layouts.admin')

@section('title', 'Job Hiring Management')
@section('page_header_title', 'Job Hiring Management')
@section('page_header_subtitle', 'Create and maintain job openings for the public Careers page, including hired/closed updates.')

@section('content')
<section class="admin-section">
    <div class="form-card">
        <form method="GET" action="{{ route('admin.jobs.index') }}" class="form-grid" style="gap:12px;">
            <div data-jobs-filter-grid style="display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap:12px; align-items:end;">
            <div class="form-group">
                <label for="job_search">Search</label>
                <input id="job_search" name="search" type="text" class="form-input" value="{{ $filters['search'] }}" placeholder="Title, department, or location">
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
                    Use filters to narrow results, then create or update job postings safely.
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end;">
                    <button type="submit" class="btn-admin" style="min-width:84px;">Apply</button>
                    <a href="{{ route('admin.jobs.index') }}" class="btn-admin-secondary" style="min-width:84px; text-align:center;">Reset</a>
                    <a href="{{ route('admin.jobs.create') }}" class="btn-admin-secondary" style="min-width:98px; text-align:center; background:#1b5e20; color:#fff; border-color:#1b5e20;">Add Job</a>
                </div>
            </div>
        </form>
    </div>
</section>

<section class="admin-section">
    <div class="form-card" style="overflow:auto;">
        <table style="width:100%; border-collapse:collapse; min-width:1100px;">
            <thead>
                <tr style="text-align:left; border-bottom:1px solid #e7edde;">
                    <th style="padding:10px 8px;">No.</th>
                    <th style="padding:10px 8px;">Position</th>
                    <th style="padding:10px 8px;">Department</th>
                    <th style="padding:10px 8px;">Type</th>
                    <th style="padding:10px 8px;">Status</th>
                    <th style="padding:10px 8px;">Posted</th>
                    <th style="padding:10px 8px;">Deadline</th>
                    <th style="padding:10px 8px;">Location</th>
                    <th style="padding:10px 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                    <tr style="border-bottom:1px solid #eef2e8;">
                        <td style="padding:12px 8px; white-space:nowrap; color:#4f5f55; font-weight:700;">
                            {{ ($jobs->firstItem() ?? 1) + $loop->index }}
                        </td>
                        <td style="padding:12px 8px; vertical-align:top;">
                            <div style="font-weight:700; color:#173f1b;">{{ $job->title }}</div>
                            <div style="font-size:.85rem; color:#637266; margin-top:4px;">
                                Slug: {{ $job->slug }}
                            </div>
                        </td>
                        <td style="padding:12px 8px;">{{ $job->department }}</td>
                        <td style="padding:12px 8px;">{{ $job->employment_type }}</td>
                        <td style="padding:12px 8px;">
                            <span style="display:inline-flex; padding:4px 8px; border-radius:999px; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; border:1px solid #dbe5d7; background:#f7fbf5; color:#355537;">
                                {{ $job->status }}
                            </span>
                        </td>
                        <td style="padding:12px 8px;">{{ $job->posted_at?->format('M d, Y') ?? 'N/A' }}</td>
                        <td style="padding:12px 8px;">{{ $job->deadline_at?->format('M d, Y') ?? 'Open until filled' }}</td>
                        <td style="padding:12px 8px;">{{ $job->location }}</td>
                        <td style="padding:12px 8px;">
                            <div style="display:flex; gap:6px; flex-wrap:wrap; justify-content:flex-start; align-items:center;">
                                <a class="btn-admin-secondary" href="{{ route('admin.jobs.edit', $job) }}" style="padding:6px 10px; min-width:46px; text-align:center;">Edit</a>
                                @if($job->status === \App\Models\JobOpening::STATUS_OPEN)
                                    <a class="btn-admin-secondary" href="{{ route('careers.show', $job) }}" target="_blank" rel="noopener" style="padding:6px 10px; min-width:46px; text-align:center;">View</a>
                                @endif
                                <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" style="display:inline;" data-confirm-title="Delete Job Opening" data-confirm-message="Delete this job opening permanently?" data-confirm-submit-label="Delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-admin-secondary" style="padding:6px 10px; min-width:58px; text-align:center; color:#8d241e; border-color:#f0c8c4; background:#fff4f3;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="padding:20px 8px; color:#637266;">No job openings found for the current filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($jobs->hasPages())
            @include('partials.custom-pagination', [
                'paginator' => $jobs,
                'navLabel' => 'Admin job pagination',
                'compact' => true,
            ])
        @endif
    </div>
</section>

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
