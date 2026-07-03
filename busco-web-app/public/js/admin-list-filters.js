(function () {
    function bindPagination(container, loadPage) {
        container.querySelectorAll("nav a[href]").forEach((link) => {
            link.addEventListener("click", (event) => {
                event.preventDefault();
                const url = new URL(link.href, window.location.origin);
                const page = url.searchParams.get("page") || "1";
                loadPage(page);
            });
        });
    }

    function initAdminAjaxList(root) {
        const form = root.querySelector("[data-admin-filter-form]");
        const results = root.querySelector("[data-admin-results]");
        const listUrl = root.getAttribute("data-admin-list-url") || window.location.pathname;

        if (!form || !results) {
            return;
        }

        let debounceTimer = null;
        let activeController = null;

        const setLoading = (loading) => {
            results.style.opacity = loading ? "0.55" : "1";
            results.style.pointerEvents = loading ? "none" : "";
        };

        const load = async (page) => {
            const params = new URLSearchParams(new FormData(form));

            Object.keys(Object.fromEntries(params)).forEach((key) => {
                if (params.get(key) === "") {
                    params.delete(key);
                }
            });

            if (page && page !== "1") {
                params.set("page", String(page));
            } else {
                params.delete("page");
            }

            const query = params.toString();
            const url = query ? `${listUrl}?${query}` : listUrl;

            if (activeController) {
                activeController.abort();
            }

            activeController = new AbortController();
            setLoading(true);

            try {
                const response = await fetch(url, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        Accept: "application/json",
                    },
                    signal: activeController.signal,
                });

                if (!response.ok) {
                    throw new Error(`Request failed (${response.status})`);
                }

                const payload = await response.json();
                results.innerHTML = payload.html;
                window.history.replaceState(null, "", url);
                bindPagination(results, load);
            } catch (error) {
                if (error.name !== "AbortError") {
                    console.error(error);
                }
            } finally {
                setLoading(false);
            }
        };

        form.addEventListener("submit", (event) => {
            event.preventDefault();
            load();
        });

        form.querySelectorAll("select").forEach((select) => {
            select.addEventListener("change", () => load());
        });

        const searchInput = form.querySelector("[data-admin-search]");
        if (searchInput) {
            searchInput.addEventListener("input", () => {
                window.clearTimeout(debounceTimer);
                debounceTimer = window.setTimeout(() => load(), 300);
            });
        }

        bindPagination(results, load);
    }

    function boot() {
        document.querySelectorAll("[data-admin-ajax-list]").forEach(initAdminAjaxList);
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", boot);
    } else {
        boot();
    }
})();
