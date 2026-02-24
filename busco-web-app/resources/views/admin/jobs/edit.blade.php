@extends('layouts.admin')

@section('title', 'Edit Job Opening')
@section('page_header_title', 'Edit Job Opening')
@section('page_header_subtitle', 'Update hiring details, deadlines, and status (open, hired, closed, or draft).')

@section('content')
<section class="admin-section">
    <div class="form-card">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:12px;">
            <div style="color:#637266; font-size:.9rem;">Update hiring details, deadlines, or status without changing the public careers layout.</div>
            <a href="{{ route('admin.jobs.index') }}" class="btn-admin-secondary" style="text-decoration:none;">Back to List</a>
        </div>
        <div style="border-top:1px solid #edf2e9; margin:0 0 14px;"></div>
        <form method="POST" action="{{ route('admin.jobs.update', $job) }}">
            @csrf
            @method('PUT')
            @include('admin.jobs._form', ['job' => $job])
            <div style="margin-top:14px; padding-top:12px; border-top:1px solid #edf2e9; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div style="color:#637266; font-size:.9rem;">Changing status to hired/closed/draft removes this record from the public Careers listing.</div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <button type="submit" class="btn-admin" style="min-width:138px;">Update Job Opening</button>
                    @if($job->status === \App\Models\JobOpening::STATUS_OPEN)
                        <a href="{{ route('careers.show', $job) }}" target="_blank" rel="noopener" class="btn-admin-secondary" style="text-decoration:none;">Open Public View</a>
                    @endif
                    <a href="{{ route('admin.jobs.index') }}" class="btn-admin-secondary" style="min-width:84px; text-align:center;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
