@extends('layouts.app')

@section('title', 'BUSCO Sugar Milling Co., Inc. | News & Achievements')
@section('meta_description', 'Company announcements, milestones, and event updates from BUSCO Sugar Milling Co., Inc.')

@section('content')
<section class="page-shell">
    <header class="page-header reveal">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>News & Achievements</span>
        </div>
        <h1 class="page-title">News & Achievements</h1>
        <p class="page-subtitle">Company announcements, milestones, awards, and events.</p>
    </header>

    <div class="news-controls reveal">
        <div class="search-box">
            <span class="search-icon" aria-hidden="true">?</span>
            <input id="newsSearch" type="text" placeholder="Search news" aria-label="Search news">
        </div>

        <div class="control-group">
            <label for="newsCategory">Category</label>
            <select id="newsCategory" class="control-select">
                <option value="">All</option>
                <option value="Announcements">Announcements</option>
                <option value="Achievements">Achievements</option>
                <option value="Events">Events</option>
                <option value="CSR / Community">CSR / Community</option>
            </select>
        </div>

        <div class="control-group">
            <label for="newsSort">Sort</label>
            <select id="newsSort" class="control-select">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
            </select>
        </div>

        <div class="result-count" id="newsCount">Showing <strong>6</strong> articles</div>
    </div>

    <div class="news-grid" id="newsGrid">
        <a class="news-card reveal" href="{{ route('news.show') }}" data-category="Achievements" data-date="2026-02-20">
            <div class="news-thumb media-achievements">
                <span class="news-featured">Featured</span>
            </div>
            <div class="news-body">
                <div class="news-meta">
                    <span class="pill">Achievements</span>
                    <span class="preview-date">Feb 20, 2026</span>
                </div>
                <h2 class="news-title">BUSCO Recognized for Quality Milling Standards</h2>
                <p class="news-excerpt">Recognition for maintaining consistent milling quality and process reliability.</p>
                <div class="news-read">Read More -></div>
            </div>
        </a>

        <a class="news-card reveal" href="{{ route('news.show') }}" data-category="Announcements" data-date="2026-02-14">
            <div class="news-thumb media-announcement"></div>
            <div class="news-body">
                <div class="news-meta">
                    <span class="pill">Announcements</span>
                    <span class="preview-date">Feb 14, 2026</span>
                </div>
                <h2 class="news-title">Advisory on Milling Schedule for the Week</h2>
                <p class="news-excerpt">Updated milling schedule and coordination reminders for stakeholders.</p>
                <div class="news-read">Read More -></div>
            </div>
        </a>

        <a class="news-card reveal" href="{{ route('news.show') }}" data-category="Events" data-date="2026-01-30">
            <div class="news-thumb media-training"></div>
            <div class="news-body">
                <div class="news-meta">
                    <span class="pill">Events</span>
                    <span class="preview-date">Jan 30, 2026</span>
                </div>
                <h2 class="news-title">BUSCO Participates in Regional Agriculture Expo</h2>
                <p class="news-excerpt">Stakeholder engagement focused on sustainable sugarcane farming practices.</p>
                <div class="news-read">Read More -></div>
            </div>
        </a>

        <a class="news-card reveal" href="{{ route('news.show') }}" data-category="CSR / Community" data-date="2026-01-18">
            <div class="news-thumb media-training"></div>
            <div class="news-body">
                <div class="news-meta">
                    <span class="pill">CSR / Community</span>
                    <span class="preview-date">Jan 18, 2026</span>
                </div>
                <h2 class="news-title">Farmer Training Program Conducted in Bukidnon</h2>
                <p class="news-excerpt">Hands-on field training focused on productivity, safety, and crop management.</p>
                <div class="news-read">Read More -></div>
            </div>
        </a>

        <a class="news-card reveal" href="{{ route('news.show') }}" data-category="Achievements" data-date="2025-12-28">
            <div class="news-thumb media-achievements"></div>
            <div class="news-body">
                <div class="news-meta">
                    <span class="pill">Achievements</span>
                    <span class="preview-date">Dec 28, 2025</span>
                </div>
                <h2 class="news-title">Operational Milestone: Successful Season Completion</h2>
                <p class="news-excerpt">Season-end milestone highlighting improved process efficiency and teamwork.</p>
                <div class="news-read">Read More -></div>
            </div>
        </a>

        <a class="news-card reveal" href="{{ route('news.show') }}" data-category="Announcements" data-date="2025-12-10">
            <div class="news-thumb media-announcement"></div>
            <div class="news-body">
                <div class="news-meta">
                    <span class="pill">Announcements</span>
                    <span class="preview-date">Dec 10, 2025</span>
                </div>
                <h2 class="news-title">Holiday Office Advisory and Community Activities</h2>
                <p class="news-excerpt">Office schedule update and community support activities for the holiday period.</p>
                <div class="news-read">Read More -></div>
            </div>
        </a>
    </div>

    <div class="empty-state" id="newsEmpty">
        <h3>No results found</h3>
        <p>Try changing your search keyword or category.</p>
    </div>

    <nav class="news-pagination" aria-label="News pagination" id="newsPagination">
        <a class="page-chip active" href="#" aria-current="page">1</a>
        <a class="page-chip" href="#">2</a>
        <a class="page-chip" href="#">3</a>
        <a class="page-chip" href="#">4</a>
    </nav>
</section>
@endsection

@push('scripts')
<script>
    (function () {
        var search = document.getElementById('newsSearch');
        var category = document.getElementById('newsCategory');
        var sort = document.getElementById('newsSort');
        var grid = document.getElementById('newsGrid');
        var cards = Array.from(grid.querySelectorAll('.news-card'));
        var count = document.getElementById('newsCount');
        var empty = document.getElementById('newsEmpty');
        var pagination = document.getElementById('newsPagination');

        function applyFilter() {
            var q = search.value.toLowerCase().trim();
            var selectedCategory = category.value;
            var selectedSort = sort.value;

            var visible = cards.filter(function (card) {
                var cardCategory = card.getAttribute('data-category');
                var text = card.textContent.toLowerCase();

                var categoryMatch = !selectedCategory || selectedCategory === cardCategory;
                var queryMatch = !q || text.indexOf(q) >= 0;

                return categoryMatch && queryMatch;
            });

            visible.sort(function (a, b) {
                var aDate = new Date(a.getAttribute('data-date'));
                var bDate = new Date(b.getAttribute('data-date'));
                return selectedSort === 'oldest' ? aDate - bDate : bDate - aDate;
            });

            cards.forEach(function (card) {
                card.style.display = 'none';
            });

            visible.forEach(function (card) {
                card.style.display = 'flex';
                grid.appendChild(card);
            });

            var total = visible.length;
            count.innerHTML = 'Showing <strong>' + total + '</strong> article' + (total === 1 ? '' : 's');
            empty.style.display = total ? 'none' : 'block';
            pagination.style.display = total ? 'flex' : 'none';
        }

        search.addEventListener('input', applyFilter);
        category.addEventListener('change', applyFilter);
        sort.addEventListener('change', applyFilter);

        document.querySelectorAll('.page-chip').forEach(function (chip) {
            chip.addEventListener('click', function (event) {
                event.preventDefault();
                document.querySelectorAll('.page-chip').forEach(function (item) {
                    item.classList.remove('active');
                });
                chip.classList.add('active');
            });
        });
    })();
</script>
@endpush


