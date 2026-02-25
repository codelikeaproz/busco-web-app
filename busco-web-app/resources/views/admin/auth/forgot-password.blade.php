{{-- View: admin/auth/forgot-password.blade.php | Purpose: Admin authentication page template. --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | BUSCO Admin</title>
    <link rel="icon" type="image/webp" href="{{ asset('img/busco_logo.webp') }}?v=2">
    <link rel="icon" type="image/jpeg" href="{{ asset('img/busco_logo.jpg') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('img/busco_logo.jpg') }}?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/busco-static.css') }}">
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: radial-gradient(circle at top, #e9f2e3 0%, #f6f8f2 45%, #eef3ea 100%); padding: 20px; }
        .login-wrap { width: min(100%, 460px); background: #fff; border-radius: 18px; border: 1px solid #e3eadb; box-shadow: 0 20px 45px rgba(24, 54, 29, .1); padding: 24px; }
        .login-kicker { color: #2e7d32; text-transform: uppercase; letter-spacing: .12em; font-size: .75rem; font-weight: 700; }
        .login-title { margin: 8px 0 6px; color: #133a18; font-family: "Playfair Display", serif; font-size: 1.65rem; }
        .login-copy { margin: 0 0 16px; color: #647266; line-height: 1.6; }
        .form-group { display: grid; gap: 6px; margin-bottom: 12px; }
        .form-group label { font-weight: 600; color: #213b25; }
        .form-input { width: 100%; border: 1px solid #cdd9c8; border-radius: 10px; padding: 10px 12px; font: inherit; }
        .form-input:focus { outline: 2px solid rgba(46,125,50,.22); border-color: #2e7d32; }
        .btn-login { width: 100%; border: none; border-radius: 12px; padding: 11px 14px; background: #1b5e20; color: #fff; font-weight: 700; cursor: pointer; }
        .login-links { margin-top: 12px; display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; font-size: .9rem; }
        .login-links a { color:#1b5e20; text-decoration:none; font-weight:600; }
    </style>
</head>
<body>
    <section class="login-wrap" aria-labelledby="admin-forgot-title">
        @include('partials.flash-messages')

        <div class="login-kicker">Administrator Access</div>
        <h1 class="login-title" id="admin-forgot-title">Forgot Admin Password</h1>
        <p class="login-copy">
            Enter your admin email address and we will send a password reset link using your configured SMTP mail settings.
        </p>

        <form method="POST" action="{{ route('admin.password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">Admin Email</label>
                <input class="form-input" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>

            <button type="submit" class="btn-login">Send Reset Link</button>
        </form>

        <div class="login-links">
            <a href="{{ route('admin.login') }}">Back to Admin Login</a>
            <a href="{{ route('home') }}">Back to Site</a>
        </div>
    </section>
</body>
</html>
