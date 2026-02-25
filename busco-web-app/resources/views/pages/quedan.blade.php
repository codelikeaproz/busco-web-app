{{-- View: pages/quedan.blade.php | Purpose: Public Quedan price page. --}}

@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | Quedan Price')
@section('meta_description', 'Weekly Quedan price announcement page with official active price and historical records.')

@php
    // Normalize current trend styling for the active Quedan summary card.
    $trendClass = match($activePrice?->trend) {
        'UP' => 'up',
        'DOWN' => 'down',
        'NO CHANGE' => 'flat',
        default => 'flat',
    };

    $trendLabel = $activePrice?->trend ?? 'NO CHANGE';
@endphp

@section('content')
{{-- Quedan page header and breadcrumb --}}
<section class="page-shell">
    <header class="page-header reveal">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Quedan Price</span>
        </div>
        <h1 class="page-title">Quedan Price Announcement</h1>
        <p class="page-subtitle">
            Official weekly Quedan price updates and historical comparison records.
        </p>
    </header>

    {{-- Active Quedan spotlight (or empty state if no records yet) --}}
    @if($activePrice)
        <article class="price-hero reveal">
            <div class="price-hero-top">
                <span class="quedan-update-chip">Official Weekly Update</span>
                <div class="buying-price-head">BUSCO BUYING PRICE</div>
                <div class="buying-price-dates">
                    <span><strong>Trading Date:</strong> {{ $activePrice->trading_date?->format('M. j, Y') }}</span>
                    <span><strong>Weekending:</strong> {{ $activePrice->weekending_date?->format('M. j, Y') }}</span>
                </div>
                <h2>{{ $activePrice->formatted_price }}</h2>
                <p>{{ $activePrice->price_subtext ?: 'Net of Taxes & Liens' }}</p>
            </div>
            <p class="buying-note buying-note-dark">{{ $activePrice->notes ?: 'Note: Negros buying price is Gross Price and Busco buying price is Net Price.' }}</p>
        </article>

        <div class="price-grid">
            <div class="price-metric reveal">
                <small>Previous Week</small>
                <strong>{{ $previousPrice?->formatted_price ?? 'N/A' }}</strong>
            </div>
            <div class="price-metric reveal">
                <small>Difference</small>
                <strong>
                    @if($activePrice->difference === null)
                        N/A
                    @else
                        {{ (float) $activePrice->difference > 0 ? '+ ' : '' }}PHP {{ number_format((float) $activePrice->difference, 2) }}
                    @endif
                </strong>
            </div>
            <div class="price-metric reveal">
                <small>Trend</small>
                <strong><span class="trend {{ $trendClass }}">{{ $trendLabel }}</span></strong>
            </div>
        </div>
    @else
        <article class="price-hero reveal">
            <div class="price-hero-top">
                <span class="quedan-update-chip">No Active Price Yet</span>
                <div class="buying-price-head">BUSCO BUYING PRICE</div>
                <div class="buying-price-dates">
                    <span><strong>Status:</strong> Pending initial record</span>
                    <span><strong>Source:</strong> Admin panel posting required</span>
                </div>
                <h2>PHP 0.00</h2>
                <p>Post the first Quedan record in Admin to activate public display.</p>
            </div>
        </article>
    @endif

    {{-- Archived historical Quedan records table --}}
    <section class="history-table reveal">
        <table>
            <thead>
                <tr>
                    <th>Trading Date</th>
                    <th>Weekending Date</th>
                    <th>Price</th>
                    <th>Difference</th>
                    <th>Trend</th>
                </tr>
            </thead>
            <tbody>
                @forelse($history as $row)
                    @php
                        // Per-row trend badge styling for archived history.
                        $rowTrendClass = match($row->trend) {
                            'UP' => 'up',
                            'DOWN' => 'down',
                            'NO CHANGE' => 'flat',
                            default => 'flat',
                        };
                    @endphp
                    <tr>
                        <td>{{ $row->trading_date?->format('F j, Y') }}</td>
                        <td>{{ $row->weekending_date?->format('F j, Y') }}</td>
                        <td>{{ $row->formatted_price }}</td>

                        <td>
                            @if($row->difference === null)
                                N/A
                            @else
                                {{ (float) $row->difference > 0 ? '+ ' : '' }}PHP {{ number_format((float) $row->difference, 2) }}
                            @endif
                        </td>
                        <td><span class="trend {{ $rowTrendClass }}">{{ $row->trend ?? 'N/A' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No archived Quedan history yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($history->hasPages())
            {{-- Shared pagination partial for archived history --}}
            @include('partials.custom-pagination', [
                'paginator' => $history,
                'navLabel' => 'Quedan history pagination',
            ])
        @endif
    </section>
</section>
@endsection
