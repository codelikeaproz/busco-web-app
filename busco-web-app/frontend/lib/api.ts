import type {
  CareersResponse,
  HomeResponse,
  JobDetail,
  NewsDetail,
  Paginated,
  QuedanResponse,
} from "./types";

const API_BASE =
  process.env.API_URL ??
  process.env.NEXT_PUBLIC_API_URL ??
  "http://127.0.0.1:8000";

async function apiFetch<T>(path: string, init?: RequestInit & { cache?: RequestCache }): Promise<T> {
  const url = `${API_BASE.replace(/\/$/, "")}${path}`;
  const response = await fetch(url, {
    ...init,
    headers: {
      Accept: "application/json",
      ...init?.headers,
    },
    next: init?.cache === "no-store" ? undefined : { revalidate: 60 },
  });

  if (!response.ok) {
    throw new Error(`API ${response.status}: ${path}`);
  }

  return response.json() as Promise<T>;
}

export function getHome(): Promise<HomeResponse> {
  return apiFetch<HomeResponse>("/api/home");
}

export function getNews(params?: { category?: string; page?: number }): Promise<Paginated<import("./types").NewsListItem>> {
  const search = new URLSearchParams();
  if (params?.category) search.set("category", params.category);
  if (params?.page) search.set("page", String(params.page));
  const query = search.toString();

  return apiFetch(`/api/news${query ? `?${query}` : ""}`);
}

export function getNewsArticle(id: string): Promise<{ data: NewsDetail; related: import("./types").NewsListItem[] }> {
  return apiFetch(`/api/news/${id}`);
}

export function getQuedan(page = 1): Promise<QuedanResponse> {
  return apiFetch(`/api/quedan?page=${page}`);
}

export function getCareers(params?: {
  search?: string;
  department?: string;
  employment_type?: string;
  page?: number;
}): Promise<CareersResponse> {
  const search = new URLSearchParams();
  if (params?.search) search.set("search", params.search);
  if (params?.department) search.set("department", params.department);
  if (params?.employment_type) search.set("employment_type", params.employment_type);
  if (params?.page) search.set("page", String(params.page));
  const query = search.toString();

  return apiFetch(`/api/careers${query ? `?${query}` : ""}`);
}

export function getCareer(slug: string): Promise<{ data: JobDetail; related_jobs: import("./types").JobListItem[] }> {
  return apiFetch(`/api/careers/${slug}`);
}

export function getApiBase(): string {
  return API_BASE.replace(/\/$/, "");
}

export function getAdminUrl(): string {
  return process.env.NEXT_PUBLIC_ADMIN_URL ?? `${getApiBase()}/admin/login`;
}

/** Client-side fetch without Next.js cache (for AJAX filters). */
export async function clientFetch<T>(path: string): Promise<T> {
  const proxyPath = `/api/backend${path.startsWith("/") ? path : `/${path}`}`;

  let response: Response;

  try {
    response = await fetch(proxyPath, {
      headers: { Accept: "application/json" },
    });
  } catch {
    throw new Error(
      "Unable to reach the Laravel API. Make sure `php artisan serve --host=127.0.0.1 --port=8000` is running and your frontend env points to the correct API URL.",
    );
  }

  if (!response.ok) {
    const detail = await response.text().catch(() => "");
    throw new Error(detail || `API ${response.status}`);
  }

  return response.json() as Promise<T>;
}
