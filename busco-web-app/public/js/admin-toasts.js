(function () {
    const TOAST_STYLES = {
        success: { bg: '#f2fbef', border: '#cbe6c5', color: '#1e5f24' },
        error: { bg: '#fff4f3', border: '#f0c8c4', color: '#8d241e' },
        warning: { bg: '#fff9eb', border: '#f0ddb0', color: '#7e5b09' },
    };

    function ensureStyles() {
        if (document.getElementById('admin-toast-styles')) {
            return;
        }

        const style = document.createElement('style');
        style.id = 'admin-toast-styles';
        style.textContent = `
            .admin-toast-container {
                position: fixed;
                top: 16px;
                right: 16px;
                z-index: 1300;
                display: grid;
                gap: 10px;
                width: min(360px, calc(100vw - 32px));
                pointer-events: none;
            }
            .admin-toast {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 12px 14px;
                border-radius: 12px;
                border: 1px solid;
                background: #fff;
                box-shadow: 0 12px 30px rgba(20, 50, 20, .12);
                opacity: 0;
                transform: translateX(12px);
                transition: opacity .2s ease, transform .2s ease;
                pointer-events: auto;
            }
            .admin-toast.is-visible {
                opacity: 1;
                transform: translateX(0);
            }
            .admin-toast-text {
                flex: 1;
                font-size: .9rem;
                line-height: 1.45;
            }
            .admin-toast-close {
                background: transparent;
                border: none;
                font-size: 1.15rem;
                line-height: 1;
                cursor: pointer;
                padding: 0;
            }
        `;
        document.head.appendChild(style);
    }

    let container = null;

    function ensureContainer() {
        ensureStyles();

        if (container) {
            return container;
        }

        container = document.createElement('div');
        container.className = 'admin-toast-container';
        container.setAttribute('aria-live', 'polite');
        container.setAttribute('aria-atomic', 'true');
        document.body.appendChild(container);
        return container;
    }

    function showAdminToast(type, message, duration) {
        const styles = TOAST_STYLES[type] || TOAST_STYLES.success;
        const toast = document.createElement('div');
        toast.className = 'admin-toast admin-toast-' + type;
        toast.setAttribute('role', 'alert');
        toast.style.background = styles.bg;
        toast.style.borderColor = styles.border;
        toast.style.color = styles.color;

        const text = document.createElement('span');
        text.className = 'admin-toast-text';
        text.textContent = message;

        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'admin-toast-close';
        close.setAttribute('aria-label', 'Dismiss notification');
        close.textContent = '\u00d7';
        close.style.color = styles.color;

        const remove = () => {
            toast.classList.remove('is-visible');
            window.setTimeout(() => toast.remove(), 200);
        };

        close.addEventListener('click', remove);
        toast.appendChild(text);
        toast.appendChild(close);
        ensureContainer().appendChild(toast);

        requestAnimationFrame(() => toast.classList.add('is-visible'));

        const timeout = duration ?? (type === 'success' ? 4000 : 6000);
        window.setTimeout(remove, timeout);
    }

    window.showAdminToast = showAdminToast;

    const dataEl = document.getElementById('admin-flash-data');
    if (!dataEl) {
        return;
    }

    try {
        const payload = JSON.parse(dataEl.textContent || '{}');
        Object.entries(payload).forEach(([type, message]) => {
            if (message) {
                showAdminToast(type, String(message));
            }
        });
    } catch (_) {
        // Ignore malformed flash payload.
    }
})();
