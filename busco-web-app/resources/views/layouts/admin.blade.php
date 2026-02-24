<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | BUSCO Admin Panel</title>
    {{-- <link rel="icon" type="image/webp" href="{{ asset('img/busco_logo.webp') }}?v=2"> --}}
    <link rel="icon" type="image/jpeg" href="{{ asset('img/busco_logo.jpg') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('img/busco_logo.jpg') }}?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/busco-static.css') }}">
    <style>
        .admin-shell { display: grid; grid-template-columns: 210px 1fr; min-height: 100vh; background: #f3f6ee; }
        .admin-sidebar {
            background: linear-gradient(180deg, #113d15 0%, #1b5e20 55%, #255f2d 100%);
            color: #fff;
            padding: 10px 12px 12px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            box-shadow: inset -1px 0 0 rgba(255,255,255,.08);
        }
        .admin-sidebar-top { display: grid; gap: 12px; }
        .admin-brand {
            font-family: "Playfair Display", serif;
            font-size: 1rem;
            line-height: 1.2;
            margin-bottom: 2px;
            padding: 10px 8px 8px;
        }
        .admin-brand small {
            display: block;
            font-family: "DM Sans", sans-serif;
            opacity: .82;
            font-size: .68rem;
            letter-spacing: .11em;
            text-transform: uppercase;
            margin-top: 5px;
            white-space: nowrap;
        }
        .admin-user {
            padding: 10px;
            border-radius: 10px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.06);
            display: grid;
            grid-template-columns: 34px 1fr;
            align-items: center;
            gap: 10px;
        }
        .admin-user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: .85rem;
            color: #5a3e00;
            background: #f9a825;
            box-shadow: inset 0 -1px 0 rgba(0,0,0,.14);
        }
        .admin-user-meta { min-width: 0; }
        .admin-user-name {
            font-size: .84rem;
            font-weight: 700;
            line-height: 1.2;
            color: #fff;
        }
        .admin-user-email {
            margin-top: 3px;
            font-size: .78rem;
            color: rgba(255,255,255,.82);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .admin-nav { display: grid; gap: 6px; margin-top: 4px; }
        .admin-nav a {
            color: rgba(255,255,255,.92);
            text-decoration: none;
            padding: 10px 11px;
            border-radius: 10px;
            border: 1px solid transparent;
            background: rgba(255,255,255,.02);
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            font-weight: 500;
        }
        .admin-nav a:hover {
            background: rgba(255,255,255,.06);
            border-color: rgba(255,255,255,.08);
        }
        .admin-nav a.active {
            background: rgba(249,168,37,.16);
            border-color: rgba(249,168,37,.28);
            color: #f9a825;
        }
        .admin-nav a.active::before {
            content: "";
            position: absolute;
            left: -1px;
            top: 7px;
            bottom: 7px;
            width: 3px;
            border-radius: 999px;
            background: #f9a825;
        }
        .admin-nav-icon {
            width: 16px;
            height: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 16px;
            color: currentColor;
            opacity: .95;
        }
        .admin-nav-icon svg { width: 16px; height: 16px; display: block; }
        .admin-sidebar-footer { margin-top: 10px; padding-top: 10px; }
        .admin-sidebar-footer button {
            width: 100%;
            background: rgba(255,255,255,.08);
            color: #fff;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 10px;
            padding: 10px 12px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .admin-sidebar-footer button:hover {
            background: rgba(255,255,255,.12);
            border-color: rgba(255,255,255,.2);
        }
        .admin-main { padding: 18px 22px 16px; display: flex; flex-direction: column; gap: 12px; min-height: 100vh; }
        .admin-topbar {
            background: #fff;
            border: 1px solid #e7edde;
            border-radius: 14px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
            box-shadow: 0 10px 24px rgba(20,50,20,.04);
        }
        .admin-topbar-label {
            color: #6a7a6d;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            font-weight: 700;
        }
        .admin-topbar-title {
            margin: 4px 0 0;
            color: #163f1a;
            font-family: "Playfair Display", serif;
            font-size: 1.12rem;
            line-height: 1.15;
        }
        .admin-topbar-subtitle {
            margin: 2px 0 0;
            color: #617066;
            font-size: .86rem;
        }
        .admin-topbar-meta {
            color: #4f5f55;
            font-size: .86rem;
            background: #f5f8f2;
            border: 1px solid #e4ebde;
            border-radius: 999px;
            padding: 8px 10px;
            white-space: nowrap;
        }
        .admin-panel { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 12px 30px rgba(20,50,20,.08); }
        .admin-footer {
            margin-top: auto;
            color: #66756a;
            font-size: .88rem;
            text-align: center;
            padding: 8px 4px 2px;
        }
        .admin-page-header h1 { margin: 0; color: #163f1a; font-family: "Playfair Display", serif; }
        .admin-page-header p { margin: 6px 0 0; color: #617066; }
        .admin-grid { display: grid; gap: 14px; grid-template-columns: repeat(2, minmax(0, 1fr)); margin-top: 16px; }
        .stat-card { background: #f7f9f5; border: 1px solid #e6ecdf; border-radius: 14px; padding: 14px; }
        .stat-card .label { font-size: .82rem; color: #637266; }
        .stat-card .value { margin-top: 8px; font-size: 1.35rem; font-weight: 700; color: #183f1d; }
        .admin-section { margin-top: 18px; }
        .form-card { background: #fff; border-radius: 14px; padding: 18px; border: 1px solid #e7edde; }
        .form-grid { display: grid; gap: 14px; }
        .form-group { display: grid; gap: 6px; }
        .form-input { width: 100%; border: 1px solid #ccd8c5; border-radius: 10px; padding: 10px 12px; font: inherit; }
        .form-input:focus { outline: 2px solid rgba(46,125,50,.22); border-color: #2e7d32; }
        .form-error { color: #b3261e; font-size: .85rem; }
        .btn-admin { background: #1b5e20; color: #fff; border: none; border-radius: 10px; padding: 10px 14px; cursor: pointer; font-weight: 600; }
        .btn-admin-secondary { display: inline-block; background: #eef3ea; color: #1b5e20; border: 1px solid #d7e2d0; border-radius: 10px; padding: 10px 14px; text-decoration: none; font-weight: 600; }
        .quick-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
        .flash { display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 12px; margin-bottom: 14px; border: 1px solid; background: #fff; }
        .flash-success { border-color: #cbe6c5; background: #f2fbef; color: #1e5f24; }
        .flash-error { border-color: #f0c8c4; background: #fff4f3; color: #8d241e; }
        .flash-warning { border-color: #f0ddb0; background: #fff9eb; color: #7e5b09; }
        .flash-text { flex: 1; }
        .flash-close { background: transparent; border: none; font-size: 1.1rem; line-height: 1; cursor: pointer; color: inherit; }
        .admin-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 26, 17, .55);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            z-index: 1200;
        }
        .admin-modal-overlay.is-open { display: flex; }
        .admin-modal-card {
            width: min(460px, 100%);
            background: #fff;
            border: 1px solid #dfe8da;
            border-radius: 14px;
            box-shadow: 0 24px 60px rgba(16, 34, 19, .22);
            overflow: hidden;
        }
        .admin-modal-head {
            padding: 14px 16px 10px;
            border-bottom: 1px solid #edf2e9;
        }
        .admin-modal-title {
            margin: 0;
            color: #173f1b;
            font-family: "Playfair Display", serif;
            font-size: 1.12rem;
        }
        .admin-modal-body {
            padding: 14px 16px;
            color: #516256;
            line-height: 1.5;
        }
        .admin-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 12px 16px 16px;
            border-top: 1px solid #edf2e9;
        }
        .admin-modal-btn {
            border-radius: 10px;
            padding: 9px 14px;
            border: 1px solid #d7e2d0;
            background: #eef3ea;
            color: #1b5e20;
            font-weight: 600;
            cursor: pointer;
        }
        .admin-modal-btn-danger {
            background: #fff4f3;
            border-color: #f0c8c4;
            color: #8d241e;
        }
        @media (max-width: 900px) {
            .admin-shell { grid-template-columns: 1fr; }
            .admin-main { padding: 14px; }
            .admin-sidebar { padding: 12px; min-height: auto; }
            .admin-grid { grid-template-columns: 1fr; }
            .admin-topbar { align-items: flex-start; flex-direction: column; }
            .admin-topbar-meta { width: 100%; text-align: left; white-space: normal; }
        }
    </style>
    @stack('head')
</head>
<body>
    @php
        $pageHeaderTitle = trim($__env->yieldContent('page_header_title'));
        $fallbackTitle = trim($__env->yieldContent('title', 'Admin'));
        $resolvedPageTitle = $pageHeaderTitle !== '' ? $pageHeaderTitle : preg_replace('/\s*\|\s*BUSCO.*$/i', '', $fallbackTitle);
        $resolvedPageSubtitle = trim($__env->yieldContent('page_header_subtitle'));
    @endphp
    <div class="admin-shell">
        @include('admin.partials.sidebar')
        <main class="admin-main">
            <header class="admin-topbar" aria-label="Admin page header">
                <div>
                    <div class="admin-topbar-label">BUSCO Admin</div>
                    <div class="admin-topbar-title">{{ $resolvedPageTitle }}</div>
                    @if($resolvedPageSubtitle !== '')
                        <p class="admin-topbar-subtitle">{{ $resolvedPageSubtitle }}</p>
                    @endif
                </div>
            </header>
            @include('partials.flash-messages')
            <div class="admin-panel">
                @yield('content')
            </div>
            <footer class="admin-footer">Busco Sugar Milling Co., Inc. {{ now()->year }}</footer>
        </main>
    </div>
    <div class="admin-modal-overlay" data-confirm-modal hidden>
        <div class="admin-modal-card" role="dialog" aria-modal="true" aria-labelledby="admin-confirm-title" aria-describedby="admin-confirm-message">
            <div class="admin-modal-head">
                <h2 class="admin-modal-title" id="admin-confirm-title" data-confirm-modal-title>Confirm Action</h2>
            </div>
            <div class="admin-modal-body">
                <p id="admin-confirm-message" style="margin:0;" data-confirm-modal-message>Are you sure you want to continue?</p>
            </div>
            <div class="admin-modal-actions">
                <button type="button" class="admin-modal-btn" data-confirm-modal-cancel>Cancel</button>
                <button type="button" class="admin-modal-btn admin-modal-btn-danger" data-confirm-modal-submit>Confirm</button>
            </div>
        </div>
    </div>
    @stack('scripts')
    <script>
        (function () {
            const modal = document.querySelector('[data-confirm-modal]');
            if (!modal) {
                return;
            }

            const titleEl = modal.querySelector('[data-confirm-modal-title]');
            const messageEl = modal.querySelector('[data-confirm-modal-message]');
            const cancelBtn = modal.querySelector('[data-confirm-modal-cancel]');
            const submitBtn = modal.querySelector('[data-confirm-modal-submit]');
            let pendingForm = null;
            let lastFocused = null;

            const openModal = (form) => {
                pendingForm = form;
                lastFocused = document.activeElement;

                const title = form.getAttribute('data-confirm-title') || 'Confirm Action';
                const message = form.getAttribute('data-confirm-message') || 'Are you sure you want to continue?';
                const confirmLabel = form.getAttribute('data-confirm-submit-label') || 'Confirm';

                titleEl.textContent = title;
                messageEl.textContent = message;
                submitBtn.textContent = confirmLabel;

                modal.hidden = false;
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
                cancelBtn.focus();
            };

            const closeModal = () => {
                modal.classList.remove('is-open');
                modal.hidden = true;
                document.body.style.overflow = '';
                pendingForm = null;

                if (lastFocused && typeof lastFocused.focus === 'function') {
                    lastFocused.focus();
                }
            };

            document.addEventListener('submit', (event) => {
                const form = event.target;
                if (!(form instanceof HTMLFormElement)) {
                    return;
                }

                if (form.dataset.confirmBypassed === '1') {
                    form.dataset.confirmBypassed = '0';
                    return;
                }

                if (!form.hasAttribute('data-confirm-message')) {
                    return;
                }

                event.preventDefault();
                openModal(form);
            });

            cancelBtn.addEventListener('click', closeModal);

            submitBtn.addEventListener('click', () => {
                if (!pendingForm) {
                    closeModal();
                    return;
                }

                pendingForm.dataset.confirmBypassed = '1';
                const formToSubmit = pendingForm;
                closeModal();
                formToSubmit.requestSubmit ? formToSubmit.requestSubmit() : formToSubmit.submit();
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                    closeModal();
                }
            });
        })();
    </script>
    <script>
        (function () {
            const logoutForm = document.querySelector('[data-admin-logout-form]');
            if (!logoutForm) {
                return;
            }

            const timeoutMs = 5 * 60 * 1000;
            const storageKey = 'busco-admin-last-activity';
            let idleCheckTimer = null;

            const getNow = () => Date.now();

            const markActivity = () => {
                const now = String(getNow());
                try {
                    localStorage.setItem(storageKey, now);
                } catch (_) {
                    // Ignore storage write failures and continue with current tab timing.
                }
            };

            const getLastActivity = () => {
                try {
                    const raw = localStorage.getItem(storageKey);
                    const parsed = raw ? Number(raw) : NaN;
                    return Number.isFinite(parsed) ? parsed : getNow();
                } catch (_) {
                    return getNow();
                }
            };

            const triggerLogout = () => {
                if (logoutForm.dataset.idleLogoutTriggered === '1') {
                    return;
                }

                logoutForm.dataset.idleLogoutTriggered = '1';
                if (logoutForm.requestSubmit) {
                    logoutForm.requestSubmit();
                    return;
                }

                logoutForm.submit();
            };

            const checkIdle = () => {
                const idleFor = getNow() - getLastActivity();
                if (idleFor >= timeoutMs) {
                    triggerLogout();
                }
            };

            const resetWatcher = () => {
                markActivity();

                if (idleCheckTimer) {
                    window.clearTimeout(idleCheckTimer);
                }

                // Recheck slightly after timeout in case there are no more events.
                idleCheckTimer = window.setTimeout(checkIdle, timeoutMs + 500);
            };

            ['click', 'keydown', 'mousemove', 'scroll', 'touchstart'].forEach((eventName) => {
                window.addEventListener(eventName, resetWatcher, { passive: true });
            });

            window.addEventListener('storage', (event) => {
                if (event.key === storageKey) {
                    checkIdle();
                }
            });

            resetWatcher();
            window.setInterval(checkIdle, 15000);
        })();
    </script>
</body>
</html>
