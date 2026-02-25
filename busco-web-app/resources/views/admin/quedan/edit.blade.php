{{-- View: admin/quedan/edit.blade.php | Purpose: Admin module page template. --}}

@extends('layouts.admin')

@section('title', 'Edit Quedan Record')
@section('page_header_title', 'Edit Quedan Record')
@section('page_header_subtitle', 'Correct a posted Quedan entry and recalculate differences/trends across the history.')

@section('content')
<section class="admin-section">
    <div class="form-card" style="margin-bottom:14px;">
        <h2 style="margin:0 0 10px; color:#183f1d; font-family:'Playfair Display', serif; font-size:1.2rem;">Record Context</h2>
        <div data-quedan-edit-context-top style="display:grid; gap:10px; grid-template-columns: repeat(4, minmax(0, 1fr));">
            <div style="padding:10px 12px; border-radius:10px; background:#fbfdf9; border:1px solid #e8eee3;"><small style="color:#637266;">Status</small><div style="font-weight:700;">{{ ucfirst($quedan->status) }}</div></div>
            <div style="padding:10px 12px; border-radius:10px; background:#fbfdf9; border:1px solid #e8eee3;"><small style="color:#637266;">Trading Date</small><div style="font-weight:700;">{{ $quedan->trading_date?->format('M d, Y') }}</div></div>
            <div style="padding:10px 12px; border-radius:10px; background:#fbfdf9; border:1px solid #e8eee3;"><small style="color:#637266;">Weekending Date</small><div style="font-weight:700;">{{ $quedan->weekending_date?->format('M d, Y') }}</div></div>
            <div style="padding:10px 12px; border-radius:10px; background:#fbfdf9; border:1px solid #e8eee3;"><small style="color:#637266;">Current Price</small><div style="font-weight:700;">{{ $quedan->formatted_price }}</div></div>
        </div>
        @if($previousRecord)
            <div style="margin-top:10px; color:#607062; padding-top:10px; border-top:1px solid #edf2e9;">
                <strong>Previous Chronological Record:</strong>
                {{ $previousRecord->trading_date?->format('M d, Y') }} - {{ $previousRecord->formatted_price }}
            </div>
        @endif
    </div>

    <div class="form-card">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:12px;">
            <div style="color:#637266; font-size:.9rem;">Edits will recalculate Quedan differences and trends across the chronology for data consistency.</div>
            <a href="{{ route('admin.quedan.index') }}" class="btn-admin-secondary" style="text-decoration:none;">Back to List</a>
        </div>
        <div style="border-top:1px solid #edf2e9; margin:0 0 14px;"></div>

        <form method="POST" action="{{ route('admin.quedan.update', $quedan) }}" class="form-grid" data-quedan-edit-form-grid style="grid-template-columns: repeat(2, minmax(0, 1fr)); gap:14px;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="trading_date">Trading Date</label>
                <input id="trading_date" name="trading_date" type="date" class="form-input" value="{{ old('trading_date', optional($quedan->trading_date)->format('Y-m-d')) }}" required>
                @error('trading_date')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input id="price" name="price" type="number" step="0.01" min="0" class="form-input" value="{{ old('price', number_format((float) $quedan->price, 2, '.', '')) }}" required>
                @error('price')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="weekending_date">Weekending Date</label>
                <input id="weekending_date" name="weekending_date" type="date" class="form-input" value="{{ old('weekending_date', optional($quedan->weekending_date)->format('Y-m-d')) }}" required>
                @error('weekending_date')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="grid-column:1 / -1;">
                <label for="price_subtext">Price Subtext (Optional)</label>
                <input id="price_subtext" name="price_subtext" type="text" class="form-input" value="{{ old('price_subtext', $quedan->price_subtext) }}" maxlength="255" placeholder="Net of Taxes & Liens or As advance subject for final price">
                @error('price_subtext')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="grid-column:1 / -1;">
                <label for="notes">Notes (Optional)</label>
                <textarea id="notes" name="notes" class="form-input" rows="4" maxlength="500">{{ old('notes', $quedan->notes) }}</textarea>
                @error('notes')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="grid-column:1 / -1; margin-top:4px; padding-top:12px; border-top:1px solid #edf2e9; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div style="color:#637266; font-size:.9rem;">Use this for corrections only. The system updates dependent differences and trends automatically.</div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <button type="submit" class="btn-admin" style="min-width:110px;">Save Changes</button>
                    <a href="{{ route('admin.quedan.index') }}" class="btn-admin-secondary" style="min-width:84px; text-align:center;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
    @media (max-width: 980px) {
        .admin-panel [data-quedan-edit-context-top],
        .admin-panel [data-quedan-edit-form-grid] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
