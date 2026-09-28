<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'CMNHousing' }} · CMNHousing</title>
    @vite(['resources/css/styles.css', 'resources/js/app.js'])
</head>
<body class="site-body">
    <header class="site-header">
        <div class="site-header-inner">
            <a class="site-logo" href="{{ route('dashboard') }}">CMN<span>Housing</span></a>
            <nav class="site-nav" aria-label="Primary">
                <a href="#">Buy</a>
                <a href="#">Rent</a>
                <a class="is-active" href="#">New Projects</a>
                <a href="#">Smart Bargain</a>
                <a href="#">EMI Calculator</a>
                <a href="#">More</a>
            </nav>
            <div class="site-header-actions">
                <button type="button" class="icon-btn" aria-label="Search" data-toast="Search coming soon">
                    {!! \App\Support\Icon::svg('search') !!}
                </button>
                <button type="button" class="btn btn-outline btn-sm" data-toast="Post property coming soon">Post Property</button>
                <button type="button" class="btn btn-ghost btn-sm" data-toast="Login coming soon">
                    {!! \App\Support\Icon::svg('user') !!} Login / Sign Up
                </button>
            </div>
        </div>
    </header>

    <main class="site-main">
        @yield('content')
    </main>

    <div class="toast" id="toast" role="status"></div>
</body>
</html>
