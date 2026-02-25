{{-- View: admin/jobs/create.blade.php | Purpose: Admin module page template. --}}

@extends('layouts.admin')

@section('title', 'Create Job Opening')
@section('page_header_title', 'Create Job Opening')
@section('page_header_subtitle', 'Publish a new hiring post for the public Careers page or save it as draft.')

@section('content')
<section class="admin-section">
    <div class="form-card">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:12px;">
            <div style="color:#637266; font-size:.9rem;">Enter job details and publish when ready. Draft status keeps the posting hidden from the public Careers page.</div>
            <a href="{{ route('admin.jobs.index') }}" class="btn-admin-secondary" style="text-decoration:none;">Back to List</a>
        </div>
        <div style="border-top:1px solid #edf2e9; margin:0 0 14px;"></div>
        <form method="POST" action="{{ route('admin.jobs.store') }}">
            @csrf
            @include('admin.jobs._form')
            <div style="margin-top:14px; padding-top:12px; border-top:1px solid #edf2e9; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div style="color:#637266; font-size:.9rem;">Applicants will apply via BUSCO HR email only (no resume upload form).</div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <button type="submit" class="btn-admin" style="min-width:130px;">Save Job Opening</button>
                    <a href="{{ route('admin.jobs.index') }}" class="btn-admin-secondary" style="min-width:84px; text-align:center;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
