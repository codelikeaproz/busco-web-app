@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | Contact')
@section('meta_description', 'Contact information for BUSCO Sugar Milling Co., Inc. including office location, email, and phone.')

@section('content')
<section class="page-shell">
    <header class="page-header reveal">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Contact</span>
        </div>
        <h1 class="page-title">Contact BUSCO</h1>
        <p class="page-subtitle">Use this static contact page during the initial non-CRUD implementation phase.</p>
    </header>

    <div class="contact-grid">
        <section class="contact-panel reveal">
            <h3>Office Information</h3>
            <p>For inquiries regarding milling operations, partnerships, and careers, contact our team through the channels below.</p>
            <ul class="contact-list">
                <li><strong>Address:</strong> Brgy. Butong, Quezon, Bukidnon, Philippines</li>
                <li><strong>Email:</strong> hrd_buscosugarmill@yahoo.com</li>
                <li><strong>Telefax:</strong> (02) 817-8403 / Local 143</li>
                <li><strong>Mobile:</strong> 0997-688-5420</li>
            </ul>
        </section>

        <section class="contact-map reveal">
            <h3>BUSCO Location Map</h3>
            <div class="contact-map-frame">
                <img class="contact-map-image" src="{{ asset('img/busco_map.webp') }}" alt="Satellite map of BUSCO Sugar Milling Co., Inc. in Quezon, Bukidnon">
            </div>
            <p class="contact-map-note">Brgy. Butong, Quezon, Bukidnon, Philippines</p>
        </section>
    </div>
</section>
@endsection
