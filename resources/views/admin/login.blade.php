<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — KAWARA</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logoKawara.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <a href="{{ route('news.index') }}" class="auth-brand-banner" aria-label="KAWARA">
                <img src="{{ asset('images/logoKawara.svg') }}" alt="KAWARA">
            </a>

            <div class="auth-body">
                <div class="auth-header">
                    <h1 class="auth-title">Login Admin</h1>
                    <p class="auth-subtitle">Masuk untuk mengelola dan mempublikasikan berita.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            class="form-control"
                            placeholder="Masukkan username"
                            required
                            autofocus
                            autocomplete="username"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                        >
                    </div>

                    <div class="form-group">
                        <label class="checkbox-row">
                            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <span>Ingat sesi login saya</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Masuk ke Dashboard</button>
                </form>

                <div style="text-align: center; margin-top: 20px;">
                    <a href="{{ route('news.index') }}" class="back-link" style="margin-bottom: 0;">
                        &larr; Kembali ke Portal Berita
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
