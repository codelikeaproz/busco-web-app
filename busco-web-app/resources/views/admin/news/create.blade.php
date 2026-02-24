@extends('layouts.admin')

@section('title', 'Create News')
@section('page_header_title', 'Create News Article')
@section('page_header_subtitle', 'Add a new article with category, content, and optional gallery images.')

@section('content')
<section class="admin-section">
    <div class="form-card">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:12px;">
            <div style="color:#637266; font-size:.9rem;">Use a subtitle for the intro summary to avoid repeating your first paragraph in the article content.</div>
            <a href="{{ route('admin.news.index') }}" class="btn-admin-secondary" style="text-decoration:none;">Back to List</a>
        </div>
        <div style="border-top:1px solid #edf2e9; margin:0 0 14px;"></div>
        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.news._form')
            <div style="margin-top:14px; padding-top:12px; border-top:1px solid #edf2e9; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div style="color:#637266; font-size:.9rem;">You can upload up to 5 JPG/JPEG images and preview/remove them before saving.</div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <button type="submit" class="btn-admin" style="min-width:110px;">Save Article</button>
                    <a href="{{ route('admin.news.index') }}" class="btn-admin-secondary" style="min-width:84px; text-align:center;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
