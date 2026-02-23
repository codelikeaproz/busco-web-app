<nav class="site-nav" aria-label="Primary navigation">
    <div class="nav-inner">
        <a class="nav-brand" href="{{ route('home') }}">
            <img class="nav-logo" src="{{ asset('img/busco_logo.webp') }}" alt="BUSCO logo">
            <span class="nav-name">
                BUSCO Sugar Milling
                <small>Co., Inc. - Bukidnon</small>
            </span>
        </a>

        <button class="nav-toggle" type="button" aria-label="Toggle menu" aria-expanded="false" data-nav-toggle>
            <span aria-hidden="true">|||</span>
        </button>

        <div class="nav-menu" data-nav-menu>
            <a class="nav-link {{ $activePage === 'home' ? 'is-active' : '' }}" href="{{ route('home') }}">Home</a>
            <a class="nav-link {{ $activePage === 'about' ? 'is-active' : '' }}" href="{{ route('about') }}">About</a>
            <a class="nav-link {{ $activePage === 'services' ? 'is-active' : '' }}" href="{{ route('services') }}">Services</a>
            <a class="nav-link {{ $activePage === 'process' ? 'is-active' : '' }}" href="{{ route('process') }}">Process</a>
            <a class="nav-link {{ $activePage === 'news' ? 'is-active' : '' }}" href="{{ route('news.index') }}">News</a>
            <a class="nav-link {{ $activePage === 'quedan' ? 'is-active' : '' }}" href="{{ route('quedan') }}">Quedan</a>
            <a class="nav-link {{ $activePage === 'community' ? 'is-active' : '' }}" href="{{ route('community') }}">Community</a>
            <a class="nav-link {{ $activePage === 'careers' ? 'is-active' : '' }}" href="{{ route('careers') }}">Careers</a>
            <a class="btn btn-primary nav-cta" href="{{ route('contact') }}">Contact</a>
        </div>
    </div>
</nav>
