"use client";

import Link from "next/link";
import { useCallback, useState } from "react";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import Pagination from "@/components/Pagination";
import Reveal from "@/components/Reveal";
import { clientFetch } from "@/lib/api";
import { formatDate } from "@/lib/format";
import type { NewsListItem, Paginated } from "@/lib/types";

const CATEGORIES = ["Announcements", "Achievements", "Events", "CSR / Community"];

type Props = {
  initial: Paginated<NewsListItem>;
  initialCategory: string;
};

export default function NewsListing({ initial, initialCategory }: Props) {
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();
  const [data, setData] = useState(initial);
  const [loading, setLoading] = useState(false);
  const category = searchParams.get("category") ?? initialCategory;

  const fetchNews = useCallback(async (params: { category?: string; page?: number }) => {
    setLoading(true);
    try {
      const search = new URLSearchParams();
      if (params.category) search.set("category", params.category);
      if (params.page) search.set("page", String(params.page));
      const query = search.toString();
      const result = await clientFetch<Paginated<NewsListItem>>(`/api/news${query ? `?${query}` : ""}`);
      setData(result);
    } finally {
      setLoading(false);
    }
  }, []);

  const updateUrl = (next: { category?: string; page?: number }) => {
    const params = new URLSearchParams();
    const cat = next.category ?? category;
    const page = next.page ?? 1;
    if (cat) params.set("category", cat);
    if (page > 1) params.set("page", String(page));
    const qs = params.toString();
    router.replace(`${pathname}${qs ? `?${qs}` : ""}`, { scroll: false });
  };

  const onCategoryChange = (value: string) => {
    updateUrl({ category: value, page: 1 });
    fetchNews({ category: value || undefined, page: 1 });
  };

  const onPage = (page: number) => {
    updateUrl({ page });
    fetchNews({ category: category || undefined, page });
  };

  return (
    <section className="page-shell">
      <Reveal as="header" className="page-header">
        <div className="breadcrumb">
          <Link href="/">Home</Link><span> / </span><span>News & Achievements</span>
        </div>
        <h1 className="page-title">News & Achievements</h1>
        <p className="page-subtitle">Company announcements, milestones, awards, and events.</p>
      </Reveal>

      <Reveal className="news-controls" style={{ display: "flex", gap: 12, alignItems: "end", flexWrap: "wrap" }}>
        <div style={{ display: "flex", gap: 12, alignItems: "end", flexWrap: "wrap", width: "100%" }}>
          <div className="control-group">
            <label htmlFor="newsCategory">Category</label>
            <select
              id="newsCategory"
              className="control-select"
              value={category}
              onChange={(e) => onCategoryChange(e.target.value)}
            >
              <option value="">All</option>
              {CATEGORIES.map((c) => <option key={c} value={c}>{c}</option>)}
            </select>
          </div>
          {category !== "" && (
            <button type="button" className="btn btn-secondary" onClick={() => onCategoryChange("")}>Clear</button>
          )}
          <div className="result-count" style={{ marginLeft: "auto" }}>
            {loading ? "Loading..." : (
              <>Showing <strong>{data.meta.total}</strong> article{data.meta.total === 1 ? "" : "s"}
              {category !== "" && <> in <strong>{category}</strong></>}</>
            )}
          </div>
        </div>
      </Reveal>

      <div className="news-grid" id="newsGrid">
        {data.data.length ? data.data.map((article) => (
          <Reveal as="link" key={article.id} className="news-card" href={`/news/${article.id}`}>
            {article.image_url && !article.image_url.endsWith("no-image.svg") ? (
              <div className="news-thumb">
                <img src={article.image_url} alt={article.title} style={{ width: "100%", height: "100%", objectFit: "cover" }} />
                {article.is_featured && <span className="news-featured">Featured</span>}
              </div>
            ) : (
              <div className="news-thumb" style={{ display: "flex", alignItems: "center", justifyContent: "center", background: "linear-gradient(180deg, #f5f8f2 0%, #eef3ea 100%)", borderBottom: "1px solid #e3eadc" }}>
                <div style={{ textAlign: "center", color: "#6d7c70", padding: 14 }}>
                  <div style={{ fontWeight: 700, fontSize: ".95rem", color: "#516256" }}>No uploaded photo</div>
                  <div style={{ fontSize: ".8rem", marginTop: 4 }}>{article.category}</div>
                </div>
                {article.is_featured && <span className="news-featured">Featured</span>}
              </div>
            )}
            <div className="news-body">
              <div className="news-meta">
                <span className="pill">{article.category}</span>
                <span className="preview-date">{formatDate(article.created_at)}</span>
              </div>
              <h2 className="news-title">{article.title}</h2>
              <p className="news-excerpt">{article.sub_title || article.excerpt}</p>
              <div className="news-read">Read More -&gt;</div>
            </div>
          </Reveal>
        )) : (
          <div className="empty-state" style={{ display: "block" }}>
            <h3>No published articles yet</h3>
            <p>Check back soon for company announcements and updates.</p>
          </div>
        )}
      </div>

      <Pagination meta={data.meta} links={data.links} onPage={onPage} navLabel="News pagination" />
    </section>
  );
}
