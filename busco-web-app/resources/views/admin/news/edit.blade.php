{{-- View: admin/news/edit.blade.php | Purpose: Admin module page template. --}}

@extends('layouts.admin')

@section('title', 'Edit News')
@section('page_header_title', 'Edit News Article')
@section('page_header_subtitle', 'Update article content, status, featured flag, and gallery images.')

@section('content')
<section class="admin-section">
    <div class="form-card">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:12px;">
            <div style="color:#637266; font-size:.9rem;">Update content, gallery images, and status without losing existing article history.</div>
            <a href="{{ route('admin.news.index') }}" class="btn-admin-secondary" style="text-decoration:none;">Back to List</a>
        </div>
        <div style="border-top:1px solid #edf2e9; margin:0 0 14px;"></div>
        <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.news._form', ['news' => $news])
            <div style="margin-top:14px; padding-top:12px; border-top:1px solid #edf2e9; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div style="color:#637266; font-size:.9rem;">Use the image remove checkboxes for existing uploads and the preview chips for new selected uploads.</div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <button type="submit" class="btn-admin" style="min-width:122px;">Update Article</button>
                    @if($news->status === \App\Models\News::STATUS_PUBLISHED)
                        <a href="{{ route('news.show', $news) }}" target="_blank" rel="noopener" class="btn-admin-secondary" style="text-decoration:none;">Open Public View</a>
                    @endif
                    <a href="{{ route('admin.news.index') }}" class="btn-admin-secondary" style="min-width:84px; text-align:center;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
