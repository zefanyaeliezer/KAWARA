<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KAWARA — Portal Berita')</title>
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
</head>
<body>
    @auth
        @if(auth()->user()->isAdmin())
            <div class="admin-ribbon">
                <div class="container admin-ribbon-inner">
                    <div class="admin-ribbon-badge">
                        <span class="admin-ribbon-dot"></span>
                        <span>Mode Admin ({{ auth()->user()->username }})</span>
                    </div>
                    <div class="admin-ribbon-actions">
                        <a href="{{ route('admin.dashboard') }}" class="admin-ribbon-link">Dashboard Admin</a>
                        <a href="{{ route('admin.news.create') }}" class="admin-ribbon-link primary">+ Buat Berita</a>
                        <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="admin-ribbon-logout">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <header class="site-header">
        <div class="container">
            <div class="masthead">
                <a href="{{ route('news.index') }}" class="brand">
                    <span class="brand-name">KAWARA<span>.</span></span>
                    <span class="brand-tagline">Informasi Lokal, Perspektif Aktual &amp; Terpercaya</span>
                </a>

                <form action="{{ route('news.index') }}" method="GET" class="search-form">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari berita..."
                        class="search-input"
                        aria-label="Cari berita"
                    >
                    <button type="submit" class="search-btn">Cari</button>
                </form>
            </div>
        </div>

        @hasSection('categories')
            <div class="category-bar">
                <div class="container">
                    @yield('categories')
                </div>
            </div>
        @endif
    </header>

    <main class="site-main">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <span>&copy; {{ date('Y') }} KAWARA News Portal. Seluruh hak cipta dilindungi.</span>
            <span>Menyajikan berita langsung dari redaksi.</span>
        </div>
    </footer>
</body>
</html>
