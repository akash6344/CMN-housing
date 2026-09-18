<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'CMNHousing' }} · Builder Portal</title>
    @vite(['resources/css/styles.css', 'resources/js/app.js'])
</head>
<body>
    <div class="overlay" id="overlay"></div>
    <div class="app">
        @include('builder.partials.sidebar')

        <div class="main">
            <header class="topbar">
                <button class="menu-toggle" id="menu-toggle" aria-label="Open menu" type="button">
                    {!! \App\Support\Icon::svg('menu') !!}
                </button>
                <div class="topbar-title">
                    <h1>{{ $pageTitle }}</h1>
                    <p>{{ $pageSub }}</p>
                </div>
                <div class="topbar-actions">
                    <label class="search">
                        {!! \App\Support\Icon::svg('search') !!}
                        <input id="global-search" type="search" placeholder="Search projects, leads...">
                    </label>
                    <button class="btn btn-primary" id="add-project" type="button">
                        {!! \App\Support\Icon::svg('plus') !!} Add Project
                    </button>
                    <a class="icon-btn" href="{{ route('builder.notifications') }}" aria-label="Notifications">
                        {!! \App\Support\Icon::svg('bell') !!}
                        <span class="badge-dot"></span>
                    </a>
                </div>
            </header>
            <main class="page">
                @yield('content')
            </main>
        </div>
    </div>

    <div class="modal-backdrop" id="modal"></div>
    <div class="toast" id="toast" role="status"></div>
</body>
</html>
