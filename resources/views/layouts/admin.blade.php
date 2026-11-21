<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel — KAWARA')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logoKawara.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
</head>
<body>
    <nav class="admin-nav">
        <div class="container admin-nav-inner">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <img src="{{ asset('images/logoKawara.svg') }}" alt="KAWARA" class="admin-logo">
                <span class="admin-brand-tag">Admin</span>
            </a>
            <div class="admin-nav-links">
                <a href="{{ route('admin.dashboard') }}" class="admin-ribbon-link">Dashboard</a>
                <a href="{{ route('admin.news.create') }}" class="admin-ribbon-link primary">+ Buat Berita</a>
                <a href="{{ route('news.index') }}" class="admin-ribbon-link">Lihat Portal</a>
                <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="admin-ribbon-logout">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="site-main">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Periksa kembali input Anda:</strong>
                    <ul style="margin: 6px 0 0 18px; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
