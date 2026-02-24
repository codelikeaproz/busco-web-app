<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error | BUSCO Sugar Milling Co., Inc.</title>
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
            background: radial-gradient(circle at top left, #fff6cf 0%, var(--bg) 42%, #ecf3e8 100%);
            color: var(--text);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }
        .card {
            width: min(700px, 100%);
            background: rgba(255,255,255,.94);
            border: 1px solid rgba(27,94,32,.12);
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 24px 60px rgba(22,49,27,.08);
        }
        .code {
            display: inline-block;
            color: #7a4f00;
            background: rgba(249,168,37,.15);
            border: 1px solid rgba(249,168,37,.3);
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
        a {
            display: inline-block;
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 700;
            background: var(--green);
            color: #fff;
            margin-top: 6px;
        }
        .accent {
            height: 4px;
            background: linear-gradient(90deg, var(--green-mid), var(--gold));
            border-radius: 999px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <main class="card" role="main" aria-labelledby="error-title">
        <div class="code">500</div>
        <h1 id="error-title">Something Went Wrong</h1>
        <p>We encountered a server error while processing your request.</p>
        <p>Please try again in a few moments. If the issue continues, contact BUSCO support.</p>
        <a href="{{ route('home') }}">Return to Home</a>
        <div class="accent" aria-hidden="true"></div>
    </main>
</body>
</html>
