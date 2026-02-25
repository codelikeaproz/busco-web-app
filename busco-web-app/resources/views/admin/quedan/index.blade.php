{{-- View: admin/quedan/index.blade.php | Purpose: Admin module page template. --}}

@extends('layouts.admin')

@section('title', 'Quedan Management')
@section('page_header_title', 'Quedan Price Management')
@section('page_header_subtitle', 'Post weekly Quedan prices, review archived history, and correct records when needed.')

@section('content')
{{-- Primary action card for posting the next weekly Quedan price --}}
<section class="admin-section">
    <div class="form-card" style="padding:14px 16px;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div style="color:#637266; font-size:.9rem;">Create a new weekly Quedan price to archive the current active record automatically.</div>
            <a class="btn-admin" href="{{ route('admin.quedan.create') }}" style="text-decoration:none;">Post New Quedan Price</a>
        </div>
    </div>
</section>

{{-- Current active Quedan record summary and quick edit action --}}
<section class="admin-section">
    <h2 style="margin:0 0 10px; color:#183f1d; font-family:'Playfair Display', serif;">Active Price</h2>
    <div class="form-card">
        @if($active)
            <div data-quedan-active-top style="display:grid; gap:10px; grid-template-columns: repeat(5, minmax(0, 1fr));">
                <div><small style="color:#637266;">Trading Date</small><div style="font-weight:700;">{{ $active->trading_date?->format('M d, Y') }}</div></div>
                <div><small style="color:#637266;">Weekending Date</small><div style="font-weight:700;">{{ $active->weekending_date?->format('M d, Y') }}</div></div>
                <div><small style="color:#637266;">Price</small><div style="font-weight:700;">{{ $active->formatted_price }}</div></div>
                <div><small style="color:#637266;">Trend</small><div style="font-weight:700;">{{ $active->trend ?? 'Initial Entry' }}</div></div>
                <div><small style="color:#637266;">Tax & Liens</small><div style="font-weight:700;">Net of Taxes & Liens</div></div>
            </div>
            <div style="margin-top:14px; padding-top:12px; border-top:1px solid #e7edde; display:grid; gap:10px;">
                @if($active->difference !== null)
                    <div style="padding:10px 12px; border-radius:10px; background:#f7fbf5; border:1px solid #e6eee2; color:#32433a;">
                        <strong style="color:#183f1d;">Price Difference:</strong>
                        {{ (float) $active->difference > 0 ? '+' : '' }}{{ number_format((float) $active->difference, 2) }}
                    </div>
                @endif
                <div data-quedan-active-details style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:10px;">
                    <div style="padding:10px 12px; border-radius:10px; background:#fbfdf9; border:1px solid #eaf0e5; color:#32433a;">
                        <strong>Price Subtext:</strong> {{ $active->price_subtext ?: 'Net of Taxes & Liens' }}
                    </div>
                    <div style="padding:10px 12px; border-radius:10px; background:#fbfdf9; border:1px solid #eaf0e5; color:#32433a;">
                        <strong>Notes:</strong> {{ $active->notes ?: '-' }}
                    </div>
                </div>
            </div>
            <div style="margin-top:14px; padding-top:12px; border-top:1px solid #edf2e9; display:flex; justify-content:flex-end;">
                <a href="{{ route('admin.quedan.edit', $active) }}" class="btn-admin-secondary" style="text-decoration:none;">Edit Active Record</a>
            </div>
        @else
            <p style="margin:0; color:#637266;">No active Quedan price yet. Post the first record to begin tracking.</p>
        @endif
    </div>
</section>

{{-- Archived Quedan history table with edit/delete actions --}}
<section class="admin-section">
    <h2 style="margin:0 0 10px; color:#183f1d; font-family:'Playfair Display', serif;">Archived History</h2>
    <div class="form-card" style="overflow:auto;">
        <table style="width:100%; border-collapse:collapse; min-width:820px;">
            <thead>
                <tr style="text-align:left; border-bottom:1px solid #e7edde;">
                    <th style="padding:10px 8px;">No.</th>
                    <th style="padding:10px 8px;">Trading Date</th>
                    <th style="padding:10px 8px;">Weekending Date</th>
                    <th style="padding:10px 8px;">Price</th>
                    <th style="padding:10px 8px;">Difference</th>
                    <th style="padding:10px 8px;">Trend</th>
                    <th style="padding:10px 8px;">Notes</th>
                    <th style="padding:10px 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($archived as $row)
                    <tr style="border-bottom:1px solid #eef2e8;">
                        <td style="padding:12px 8px; white-space:nowrap; color:#4f5f55; font-weight:700;">
                            {{ ($archived->firstItem() ?? 1) + $loop->index }}
                        </td>
                        <td style="padding:12px 8px;">{{ $row->trading_date?->format('M d, Y') }}</td>
                        <td style="padding:12px 8px;">{{ $row->weekending_date?->format('M d, Y') }}</td>
                        <td style="padding:12px 8px;">{{ $row->formatted_price }}</td>
                        <td style="padding:12px 8px;">
                            @if($row->difference === null)
                                N/A
                            @else
                                {{ (float) $row->difference > 0 ? '+' : '' }}{{ number_format((float) $row->difference, 2) }}
                            @endif
                        </td>
                        <td style="padding:12px 8px;">{{ $row->trend ?? 'N/A' }}</td>
                        <td style="padding:12px 8px;">{{ $row->notes ?: '-' }}</td>
                        <td style="padding:12px 8px;">
                            <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                                <a href="{{ route('admin.quedan.edit', $row) }}" class="btn-admin-secondary" style="padding:6px 10px; min-width:46px; text-align:center; text-decoration:none;">Edit</a>
                                <form method="POST" action="{{ route('admin.quedan.destroy', $row) }}" data-confirm-title="Delete Archived Quedan Record" data-confirm-message="Delete this archived Quedan record?" data-confirm-submit-label="Delete Record">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-admin-secondary" style="padding:6px 10px; min-width:58px; text-align:center; color:#8d241e; border-color:#f0c8c4; background:#fff4f3;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="padding:20px 8px; color:#637266;">No archived Quedan records yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($archived->hasPages())
            {{-- Compact shared pagination component --}}
            @include('partials.custom-pagination', [
                'paginator' => $archived,
                'navLabel' => 'Admin Quedan archived pagination',
                'compact' => true,
            ])
        @endif
    </div>
</section>

{{-- Responsive layout tweaks for active Quedan summary cards --}}
<style>
    @media (max-width: 1100px) {
        .admin-panel [data-quedan-active-top] {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        }
        .admin-panel [data-quedan-active-details] {
            grid-template-columns: 1fr !important;
        }
    }
    @media (max-width: 760px) {
        .admin-panel [data-quedan-active-top] {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }
</style>
@endsection
