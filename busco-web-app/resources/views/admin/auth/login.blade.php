<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | BUSCO</title>
    <link rel="icon" type="image/webp" href="{{ asset('img/busco_logo.webp') }}?v=2">
    <link rel="icon" type="image/jpeg" href="{{ asset('img/busco_logo.jpg') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('img/busco_logo.jpg') }}?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/busco-static.css') }}">
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: radial-gradient(circle at top, #e9f2e3 0%, #f6f8f2 45%, #eef3ea 100%); padding: 20px; }
        .login-wrap { width: min(100%, 420px); background: #fff; border-radius: 18px; border: 1px solid #e3eadb; box-shadow: 0 20px 45px rgba(24, 54, 29, .1); padding: 24px; }
        .login-kicker { color: #2e7d32; text-transform: uppercase; letter-spacing: .12em; font-size: .75rem; font-weight: 700; }
        .login-title { margin: 8px 0 6px; color: #133a18; font-family: "Playfair Display", serif; font-size: 1.8rem; }
        .login-copy { margin: 0 0 16px; color: #647266; }
        .form-group { display: grid; gap: 6px; margin-bottom: 12px; }
        .form-group label { font-weight: 600; color: #213b25; }
        .form-input { width: 100%; border: 1px solid #cdd9c8; border-radius: 10px; padding: 10px 12px; font: inherit; }
        .form-input:focus { outline: 2px solid rgba(46,125,50,.22); border-color: #2e7d32; }
        .login-row { display: flex; justify-content: space-between; gap: 10px; align-items: center; margin: 10px 0 16px; color: #4b5c4f; font-size: .92rem; }
        .btn-login { width: 100%; border: none; border-radius: 12px; padding: 11px 14px; background: #1b5e20; color: #fff; font-weight: 700; cursor: pointer; }
        .flash { display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 12px; margin-bottom: 14px; border: 1px solid; background: #fff; }
        .flash-success { border-color: #cbe6c5; background: #f2fbef; color: #1e5f24; }
        .flash-error { border-color: #f0c8c4; background: #fff4f3; color: #8d241e; }
        .flash-warning { border-color: #f0ddb0; background: #fff9eb; color: #7e5b09; }
        .flash-text { flex: 1; }
        .flash-close { background: transparent; border: none; font-size: 1.1rem; line-height: 1; cursor: pointer; color: inherit; }
    </style>
</head>
<body>
    <section class="login-wrap" aria-labelledby="admin-login-title">
        @include('partials.flash-messages')

        <div class="login-kicker">Administrator Access</div>
        <h1 class="login-title" id="admin-login-title">BUSCO Admin Login</h1>
        <p class="login-copy">Sign in to manage dashboard content, news, and weekly Quedan prices.</p>

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-input @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-input @error('password') is-invalid @enderror" id="password" name="password" type="password" required autocomplete="current-password">
            </div>

            <div class="login-row">
                <label style="display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>
                <a href="{{ route('admin.password.request') }}" style="color:#1b5e20; text-decoration:none; font-weight:600;">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login">Sign In</button>

            <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin-top:12px; font-size:.92rem;">
                <a href="{{ route('home') }}" style="color:#1b5e20; text-decoration:none; font-weight:600;">Back to site</a>
            </div>
        </form>
    </section>
</body>
</html>
