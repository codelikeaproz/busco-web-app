<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found | BUSCO Sugar Milling Co., Inc.</title>
    <style>
        :root {
            --green: #1b5e20;
            --green-mid: #2e7d32;
            --gold: #f9a825;
            --bg: #f7f9f5;
            --text: #16311b;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            background: radial-gradient(circle at top right, #fff7d7 0%, var(--bg) 45%, #eef5ea 100%);
            color: var(--text);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }
        .card {
            width: min(680px, 100%);
            background: rgba(255,255,255,.92);
            border: 1px solid rgba(27,94,32,.12);
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 24px 60px rgba(22,49,27,.08);
        }
        .code {
            display: inline-block;
            color: var(--green);
            background: rgba(46,125,50,.08);
            border: 1px solid rgba(46,125,50,.16);
            border-radius: 999px;
            padding: 6px 12px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        h1 {
            margin: 0 0 10px;
            color: var(--green);
            font-size: clamp(1.7rem, 3vw, 2.2rem);
        }
        p {
            margin: 0 0 16px;
            line-height: 1.55;
        }
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 8px;
        }
        a {
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 700;
            border: 1px solid transparent;
        }
        .primary {
            background: var(--green);
            color: #fff;
        }
        .secondary {
            color: var(--green);
            border-color: rgba(27,94,32,.25);
            background: #fff;
        }
        .accent {
            height: 4px;
            background: linear-gradient(90deg, var(--gold), var(--green-mid));
            border-radius: 999px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <main class="card" role="main" aria-labelledby="error-title">
        <div class="code">404</div>
        <h1 id="error-title">Page Not Found</h1>
        <p>The page you requested could not be found or may have been moved.</p>
        <p>Please return to the homepage or continue to the latest public updates.</p>
        <div class="actions">
            <a class="primary" href="{{ route('home') }}">Go to Home</a>
            <a class="secondary" href="{{ route('news.index') }}">Open News</a>
        </div>
        <div class="accent" aria-hidden="true"></div>
    </main>
</body>
</html>
