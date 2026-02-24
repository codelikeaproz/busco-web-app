@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | Sugar Milling Process')
@section('meta_description', 'Step-by-step overview of BUSCO sugar milling and production workflow.')

@section('content')
<section class="page-shell">
    <header class="page-header reveal">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Sugar Milling Process</span>
        </div>
        <h1 class="page-title">Sugar Milling Process</h1>
        <p class="page-subtitle">
            A step-by-step guide to how sugarcane is transformed into raw sugar through BUSCO's milling operations.
        </p>
    </header>
</section>

<div class="process-sections">
    <section class="process-stage reveal">
        <div class="section-shell process-stage-inner">
            <div class="process-stage-media">
                <div class="process-stage-badge process-stage-badge-gold">01</div>
                <img class="process-stage-image" src="{{ asset('img/announcement.webp') }}" alt="Sugarcane trucks arriving at BUSCO for cane delivery and weighing">
            </div>
            <div class="process-stage-content">
                <div class="process-stage-heading">
                    <span class="process-stage-icon">CD</span>
                    <h2>Cane Delivery &amp; Weighing</h2>
                </div>
                <p>
                    The process begins with the arrival of harvested sugarcane from local farms. Trucks are weighed to record
                    gross load and establish accurate receiving records before unloading.
                </p>
                <ul class="process-stage-list">
                    <li>Automated weighbridge and receiving control</li>
                    <li>Initial sampling and intake documentation</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="process-stage process-stage-alt reveal">
        <div class="section-shell process-stage-inner process-stage-inner-reverse">
            <div class="process-stage-media">
                <div class="process-stage-badge process-stage-badge-green">02</div>
                <img class="process-stage-image process-stage-image-tight" src="{{ asset('img/cane_cutting.webp') }}" alt="BUSCO milling facility operations used as visual for cane cutting and shredding">
            </div>
            <div class="process-stage-content">
                <div class="process-stage-heading">
                    <span class="process-stage-icon">CS</span>
                    <h2>Cane Cutting &amp; Shredding</h2>
                </div>
                <p>
                    Cane stalks are prepared by knives and shredders to rupture cane cells and produce fibrous material
                    ready for efficient milling and extraction.
                </p>
                <ul class="process-stage-list">
                    <li>Heavy-duty cane preparation equipment</li>
                    <li>Fiber condition optimized for extraction efficiency</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="process-stage reveal">
        <div class="section-shell process-stage-inner">
            <div class="process-stage-media">
                <div class="process-stage-badge process-stage-badge-gold">03</div>
                <img class="process-stage-image process-stage-image-wide" src="{{ asset('img/juice_extraction.webp') }}" alt="BUSCO mill facility used as visual for juice extraction">
            </div>
            <div class="process-stage-content">
                <div class="process-stage-heading">
                    <span class="process-stage-icon">JE</span>
                    <h2>Juice Extraction</h2>
                </div>
                <p>
                    The prepared cane passes through milling tandems where pressure extracts sugar-rich juice. The remaining
                    bagasse fiber is separated and can be reused as boiler fuel.
                </p>
                <ul class="process-stage-list">
                    <li>Multi-stage milling tandem operation</li>
                    <li>Bagasse recovery for energy use</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="process-stage process-stage-alt reveal">
        <div class="section-shell process-stage-inner process-stage-inner-reverse">
            <div class="process-stage-media">
                <div class="process-stage-badge process-stage-badge-green">04</div>
                <img class="process-stage-image" src="{{ asset('img/clarification.webp') }}" alt="BUSCO facility used as visual for clarification stage">
            </div>
            <div class="process-stage-content">
                <div class="process-stage-heading">
                    <span class="process-stage-icon">CL</span>
                    <h2>Clarification</h2>
                </div>
                <p>
                    Raw juice is heated and treated to remove impurities and stabilize quality, resulting in clarified juice
                    for downstream concentration.
                </p>
                <ul class="process-stage-list">
                    <li>Liming and heating treatment process</li>
                    <li>Impurity settling and separation stages</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="process-stage reveal">
        <div class="section-shell process-stage-inner">
            <div class="process-stage-media">
                <div class="process-stage-badge process-stage-badge-gold">05</div>
                <img class="process-stage-image process-stage-image-tight" src="{{ asset('img/evaporation.webp') }}" alt="BUSCO facility used as visual for evaporation stage">
            </div>
            <div class="process-stage-content">
                <div class="process-stage-heading">
                    <span class="process-stage-icon">EV</span>
                    <h2>Evaporation</h2>
                </div>
                <p>
                    Clarified juice enters evaporator vessels where water is removed under controlled conditions,
                    concentrating the liquid into thick syrup.
                </p>
                <ul class="process-stage-list">
                    <li>Multiple-effect evaporator vessels</li>
                    <li>Energy-efficient steam usage</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="process-stage process-stage-alt reveal">
        <div class="section-shell process-stage-inner process-stage-inner-reverse">
            <div class="process-stage-media">
                <div class="process-stage-badge process-stage-badge-green">06</div>
                <img class="process-stage-image" src="{{ asset('img/crystallization.webp') }}" alt="BUSCO facility used as visual for crystallization stage">
            </div>
            <div class="process-stage-content">
                <div class="process-stage-heading">
                    <span class="process-stage-icon">CR</span>
                    <h2>Crystallization</h2>
                </div>
                <p>
                    Syrup is concentrated further in vacuum pans until crystallization begins. Seed crystals are introduced
                    to control crystal growth and form massecuite.
                </p>
                <ul class="process-stage-list">
                    <li>Vacuum pan boiling</li>
                    <li>Controlled crystal growth process</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="process-stage reveal">
        <div class="section-shell process-stage-inner">
            <div class="process-stage-media">
                <div class="process-stage-badge process-stage-badge-gold">07</div>
                <img class="process-stage-image process-stage-image-wide" src="{{ asset('img/centrifugation.webp') }}" alt="BUSCO facility used as visual for centrifugation stage">
            </div>
            <div class="process-stage-content">
                <div class="process-stage-heading">
                    <span class="process-stage-icon">CF</span>
                    <h2>Centrifugation</h2>
                </div>
                <p>
                    Massecuite is spun in centrifugal machines to separate sugar crystals from molasses, producing raw sugar
                    for final drying and handling.
                </p>
                <ul class="process-stage-list">
                    <li>High-speed basket centrifugals</li>
                    <li>Crystal and molasses separation</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="process-stage process-stage-alt reveal">
        <div class="section-shell process-stage-inner process-stage-inner-reverse">
            <div class="process-stage-media">
                <div class="process-stage-badge process-stage-badge-green">08</div>
                <img class="process-stage-image" src="{{ asset('img/bagging.webp') }}" alt="BUSCO operations visual for drying and packaging stage">
            </div>
            <div class="process-stage-content">
                <div class="process-stage-heading">
                    <span class="process-stage-icon">DP</span>
                    <h2>Drying &amp; Packaging</h2>
                </div>
                <p>
                    Raw sugar is dried to reduce residual moisture, then weighed and packed for storage, dispatch,
                    and delivery to refineries and markets.
                </p>
                <ul class="process-stage-list">
                    <li>Drying for moisture control and storage stability</li>
                    <li>Weighing, bagging, and dispatch preparation</li>
                </ul>
            </div>
        </div>
    </section>
</div>
@endsection
