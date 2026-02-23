<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BUSCO Sugar Milling Co., Inc.')</title>
    <meta name="description" content="@yield('meta_description', 'BUSCO Sugar Milling Co., Inc. corporate website')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/busco-static.css') }}">
    @stack('head')
</head>
<body>
    @include('partials.navbar', ['activePage' => $activePage ?? ''])

    <main class="site-main">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('js/busco-static.js') }}"></script>
    @stack('scripts')
</body>
</html>
