<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KAWARA — Portal Berita')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logoKawara.svg') }}">
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
                <a href="{{ route('news.index') }}" class="brand" aria-label="KAWARA — Informasi Lokal, Perspektif Aktual">
                    <img src="{{ asset('images/logoKawara.svg') }}" alt="KAWARA" class="brand-logo">
                    <span class="brand-tagline">Informasi Lokal, Perspektif Aktual</span>
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
        <div class="container">
            <div class="footer-top">
                <div class="footer-brand">
                    <a href="{{ route('news.index') }}" aria-label="KAWARA">
                        <img src="{{ asset('images/logoKawara.svg') }}" alt="KAWARA" class="footer-logo">
                    </a>
                    <p class="footer-tagline">Informasi Lokal, Perspektif Aktual</p>
                    <p class="footer-subtagline">MEDIA INFORMASI LOKAL KARAWANG</p>
                </div>

                <div class="footer-right">
                    <div class="footer-explore">
                        <span class="footer-section-label">Jelajahi Kawara</span>
                        <nav class="footer-topic-links" aria-label="Jelajahi Kawara">
                            <a href="{{ route('news.index', ['category' => 'EKONOMI']) }}">Ekonomi</a>
                            <span class="footer-dot" aria-hidden="true">&middot;</span>
                            <a href="{{ route('news.index', ['category' => 'POLITIK & PEMERINTAHAN']) }}">Pemerintahan</a>
                            <span class="footer-dot" aria-hidden="true">&middot;</span>
                            <a href="{{ route('news.index', ['category' => 'WISATA & LINGKUNGAN']) }}">Wisata &amp; Lingkungan</a>
                            <span class="footer-dot" aria-hidden="true">&middot;</span>
                            <a href="{{ route('news.index', ['category' => 'SOSIAL']) }}">Sosial</a>
                            <span class="footer-dot" aria-hidden="true">&middot;</span>
                            <a href="{{ route('news.index', ['category' => 'PENDIDIKAN']) }}">Pendidikan</a>
                        </nav>
                    </div>

                    <div class="footer-social">
                        <span class="footer-social-label">Ikuti Kami</span>
                        <div class="footer-social-links">
                            <a
                                href="https://www.instagram.com/kawara_updates?lqvp=MzFneDBzY2hlYms2"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="social-icon-link"
                                aria-label="Instagram KAWARA"
                                title="Instagram KAWARA"
                            >
                                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="2.5" y="2.5" width="19" height="19" rx="5"></rect>
                                    <circle cx="12" cy="12" r="4.2"></circle>
                                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"></circle>
                                </svg>
                            </a>

                            <a
                                href="https://whatsapp.com/channel/0029Vb9QkUuBadmdn7ndp220"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="social-icon-link"
                                aria-label="WhatsApp Channel KAWARA"
                                title="WhatsApp Channel KAWARA"
                            >
                                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">
                                    <path d="M20.52 3.48A11.82 11.82 0 0 0 12.06 0C5.5 0 .16 5.34.16 11.9c0 2.1.55 4.15 1.59 5.96L0 24l6.31-1.66a11.86 11.86 0 0 0 5.75 1.47h.01c6.56 0 11.9-5.34 11.9-11.9 0-3.18-1.24-6.17-3.45-8.43Zm-8.46 18.32h-.01a9.87 9.87 0 0 1-5.04-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.87 9.87 0 0 1-1.52-5.27c0-5.45 4.44-9.89 9.91-9.89 2.65 0 5.13 1.03 7 2.9a9.83 9.83 0 0 1 2.9 7c0 5.45-4.44 9.89-9.9 9.89Zm5.43-7.42c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.61-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.07 2.88 1.22 3.08.15.2 2.1 3.21 5.09 4.5.71.31 1.27.49 1.7.63.71.23 1.36.2 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35Z"/>
                                </svg>
                            </a>

                            <a
                                href="https://www.facebook.com/share/1A6DVqyL3j/"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="social-icon-link"
                                aria-label="Facebook KAWARA"
                                title="Facebook KAWARA"
                            >
                                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">
                                    <path d="M13.5 22v-8.2h2.8l.42-3.2H13.5V8.55c0-.93.26-1.56 1.6-1.56h1.71V4.12c-.3-.04-1.31-.12-2.49-.12-2.46 0-4.15 1.5-4.15 4.26v2.34H7.38v3.2h2.79V22h3.33Z"/>
                                </svg>
                            </a>

                            <a
                                href="https://x.com/KawaraUpdates"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="social-icon-link"
                                aria-label="X KAWARA"
                                title="X KAWARA"
                            >
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231 5.45-6.231Zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77Z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} KAWARA. Seluruh hak cipta dilindungi.</span>
            </div>
        </div>
    </footer>
</body>
</html>
