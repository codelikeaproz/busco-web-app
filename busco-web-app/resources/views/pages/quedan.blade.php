@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | Quedan Price')
@section('meta_description', 'Weekly Quedan price announcement page with static placeholder data for initial implementation.')

@section('content')
<section class="page-shell">
    <header class="page-header reveal">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Quedan Price</span>
        </div>
        <h1 class="page-title">Quedan Price Announcement</h1>
        <p class="page-subtitle">
            Static version of the Quedan page. Price records are placeholders while CRUD integration is pending.
        </p>
    </header>

    <article class="price-hero reveal">
        <div class="price-hero-top">
            <span class="quedan-update-chip">Official Weekly Update</span>
            <div class="buying-price-head">BUSCO BUYING PRICE</div>
            <div class="buying-price-dates">
                <span><strong>Trading Date:</strong> Jun. 5, 2025</span>
                <span><strong>Weekending:</strong> Jun. 1, 2025</span>
            </div>
            <h2>PHP 2,650.00</h2>
            <p>Net of Taxes & Liens</p>
        </div>
        <p class="buying-note buying-note-dark">Note: Negros buying price is Gross Price and Busco buying price is Net Price.</p>
    </article>

    <div class="price-grid">
        <div class="price-metric reveal">
            <small>Previous Week</small>
            <strong>PHP 2,650.00</strong>
        </div>
        <div class="price-metric reveal">
            <small>Difference</small>
            <strong>PHP 0.00</strong>
        </div>
        <div class="price-metric reveal">
            <small>Trend</small>
            <strong><span class="trend flat">SAME PRICE</span></strong>
        </div>
    </div>

    <section class="history-table reveal">
        <table>
            <thead>
                <tr>
                    <th>Week Label</th>
                    <th>Price</th>
                    <th>Effective Date</th>
                    <th>Difference</th>
                    <th>Trend</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>June 1, 2025</td>
                    <td>PHP 2,650.00</td>
                    <td>June 5, 2025</td>
                    <td>PHP 0.00</td>
                    <td><span class="trend flat">SAME PRICE</span></td>
                </tr>
                <tr>
                    <td>May 25, 2025</td>
                    <td>PHP 2,650.00</td>
                    <td>May 30, 2025</td>
                    <td>PHP 0.00</td>
                    <td><span class="trend flat">NO CHANGE</span></td>
                </tr>
                <tr>
                    <td>May 18, 2025</td>
                    <td>PHP 2,640.00</td>
                    <td>May 23, 2025</td>
                    <td>- PHP 10.00</td>
                    <td><span class="trend down">DOWN</span></td>
                </tr>
                <tr>
                    <td>May 11, 2025</td>
                    <td>PHP 2,650.00</td>
                    <td>May 16, 2025</td>
                    <td>+ PHP 20.00</td>
                    <td><span class="trend up">UP</span></td>
                </tr>
            </tbody>
        </table>
    </section>
</section>
@endsection

