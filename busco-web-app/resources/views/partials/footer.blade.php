{{-- View: partials/footer.blade.php | Purpose: Shared public footer partial. --}}

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                <div class="footer-brand-head">
                    <img class="footer-brand-logo" src="{{ asset('img/busco_logo.webp') }}" alt="BUSCO logo">
                    <h3 class="footer-title">BUSCO Sugar Milling Co., Inc.</h3>
                </div>
                <p class="footer-copy">
                    Brgy. Butong, Quezon, Bukidnon, Philippines. Corporate information portal for operations,
                    public advisories, community engagement, and industry updates.
                </p>
            </div>

            <div>
                <h4 class="footer-col-title">Company</h4>
                <a class="footer-link" href="{{ route('about') }}">About</a>
                <a class="footer-link" href="{{ route('services') }}">Services</a>
                <a class="footer-link" href="{{ route('process') }}">Milling Process</a>
            </div>

            <div>
                <h4 class="footer-col-title">Updates</h4>
                <a class="footer-link" href="{{ route('news.index') }}">News & Achievements</a>
                <a class="footer-link" href="{{ route('quedan') }}">Quedan Price</a>
                <a class="footer-link" href="{{ route('news.index', ['category' => 'CSR / Community']) }}">Community</a>
                @if(auth()->check() && auth()->user()?->isAdmin())
                    <a class="footer-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                @else
                    <a class="footer-link" href="{{ route('admin.login') }}">Admin Login</a>
                @endif
            </div>

            <div>
                <h4 class="footer-col-title">Contact</h4>
                <a class="footer-link" href="mailto:hrd_buscosugarmill@yahoo.com">hrd_buscosugarmill@yahoo.com</a>
                <a class="footer-link" href="tel:+63028178403">(02) 817-8403</a>
                <a class="footer-link" href="tel:+639976885420">0997-688-5420</a>
                <a class="footer-link" href="{{ route('careers') }}">Open Positions</a>
            </div>
        </div>

        <div class="footer-bottom">
            <small>(c) {{ now()->year }} BUSCO Sugar Milling Co., Inc. All rights reserved.</small>
            <span class="footer-gold" aria-hidden="true"></span>
        </div>
    </div>
</footer>

