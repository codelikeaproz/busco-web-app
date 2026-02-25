{{-- View: layouts/app.blade.php | Purpose: Public site layout wrapper used by frontend pages. --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BUSCO Sugar Milling Co., Inc.')</title>
    <meta name="description" content="@yield('meta_description', 'BUSCO Sugar Milling Co., Inc. corporate website')">
    {{-- <link rel="icon" type="image/webp" href="{{ asset('img/busco_logo.webp') }}?v=2"> --}}
    <link rel="icon" type="image/jpeg" href="{{ asset('img/busco_logo.jpg') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('img/busco_logo.jpg') }}?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/busco-static.css') }}">
    @stack('head')
</head>
<body>
    {{-- Global public navigation --}}
    @include('partials.navbar', ['activePage' => $activePage ?? ''])

    {{-- Cross-page flash/validation messages --}}
    @include('partials.flash-messages')

    {{-- Page-specific content --}}
    <main class="site-main">
        @yield('content')
    </main>

    {{-- Global public footer --}}
    @include('partials.footer')

    {{-- Shared static interactions for the public site --}}
    <script src="{{ asset('js/busco-static.js') }}"></script>
    @stack('scripts')
</body>
</html>
