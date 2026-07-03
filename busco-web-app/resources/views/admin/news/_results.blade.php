{{-- Partial: admin/news/_results.blade.php | AJAX-updatable news table + pagination. --}}

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
    @include('partials.custom-pagination', [
        'paginator' => $news,
        'navLabel' => 'Admin news pagination',
        'compact' => true,
    ])
@endif
