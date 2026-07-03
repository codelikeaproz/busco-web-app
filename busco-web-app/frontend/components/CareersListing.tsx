"use client";

import Link from "next/link";
import { useCallback, useEffect, useRef, useState } from "react";
import { usePathname, useRouter } from "next/navigation";
import Pagination from "@/components/Pagination";
import { clientFetch } from "@/lib/api";
import type { CareersResponse } from "@/lib/types";

type Props = {
  initial: CareersResponse;
};

type FilterState = {
  search: string;
  department: string;
  employment_type: string;
  page: number;
};

export default function CareersListing({ initial }: Props) {
  const router = useRouter();
  const pathname = usePathname();
  const [data, setData] = useState(initial);
  const [search, setSearch] = useState(initial.filters.search);
  const [department, setDepartment] = useState(initial.filters.department);
  const [employmentType, setEmploymentType] = useState(initial.filters.employment_type);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const skipSearchDebounce = useRef(true);

  const buildQuery = useCallback((filters: FilterState) => {
    const params = new URLSearchParams();
    if (filters.search) params.set("search", filters.search);
    if (filters.department) params.set("department", filters.department);
    if (filters.employment_type) params.set("employment_type", filters.employment_type);
    if (filters.page > 1) params.set("page", String(filters.page));
    return params.toString();
  }, []);

  const fetchCareers = useCallback(async (qs: string) => {
    setLoading(true);
    setError("");
    try {
      const result = await clientFetch<CareersResponse>(`/api/careers${qs ? `?${qs}` : ""}`);
      setData(result);
    } catch (caught) {
      const message =
        caught instanceof Error
          ? caught.message
          : "Unable to load careers right now. Please try again.";
      setError(message);
    } finally {
      setLoading(false);
    }
  }, []);

  const runQuery = useCallback(
    (overrides: Partial<FilterState>) => {
      const next: FilterState = {
        search: overrides.search ?? search,
        department: overrides.department ?? department,
        employment_type: overrides.employment_type ?? employmentType,
        page: overrides.page ?? 1,
      };

      const qs = buildQuery(next);
      router.replace(`${pathname}${qs ? `?${qs}` : ""}`, { scroll: false });
      fetchCareers(qs);
    },
    [buildQuery, department, employmentType, fetchCareers, pathname, router, search],
  );

  useEffect(() => {
    if (skipSearchDebounce.current) {
      skipSearchDebounce.current = false;
      return;
    }

    const timer = window.setTimeout(() => {
      const qs = buildQuery({
        search,
        department,
        employment_type: employmentType,
        page: 1,
      });
      router.replace(`${pathname}${qs ? `?${qs}` : ""}`, { scroll: false });
      fetchCareers(qs);
    }, 300);

    return () => window.clearTimeout(timer);
  }, [search]); // eslint-disable-line react-hooks/exhaustive-deps

  const onDepartmentChange = (value: string) => {
    setDepartment(value);
    runQuery({ department: value, page: 1 });
  };

  const onEmploymentTypeChange = (value: string) => {
    setEmploymentType(value);
    runQuery({ employment_type: value, page: 1 });
  };

  const onPage = (page: number) => {
    runQuery({ page });
  };

  return (
    <section className="page-shell">
      <header className="page-header reveal">
        <div className="breadcrumb">
          <Link href="/">Home</Link><span> / </span><span>Careers</span>
        </div>
        <h1 className="page-title">Careers</h1>
        <p className="page-subtitle">
          Explore current openings at BUSCO. Applications are handled through our HR email and reviewed by the recruitment team.
        </p>
      </header>

      <section className="surface reveal" style={{ padding: 22 }}>
        <h2 className="section-title" style={{ fontSize: "clamp(26px, 3.3vw, 36px)" }}>Open Positions</h2>
        <p className="section-copy" style={{ marginBottom: 0 }}>
          To apply, send your resume and application letter to{" "}
          <a href="mailto:hrd_buscosugarmill@yahoo.com" style={{ color: "var(--green-mid)", fontWeight: 600 }}>hrd_buscosugarmill@yahoo.com</a>.
        </p>
      </section>

      <div className="careers-controls reveal">
        <div className="search-box">
          <span className="search-icon" aria-hidden="true" style={{ fontSize: 15, left: 13 }}>⌕</span>
          <input
            type="search"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search position, department, or location"
            aria-label="Search careers"
          />
        </div>
        <div className="control-group">
          <label htmlFor="careers_department">Department</label>
          <select
            id="careers_department"
            className="control-select"
            value={department}
            onChange={(e) => onDepartmentChange(e.target.value)}
          >
            <option value="">All</option>
            {data.departments.map((d) => <option key={d} value={d}>{d}</option>)}
          </select>
        </div>
        <div className="control-group">
          <label htmlFor="careers_type">Type</label>
          <select
            id="careers_type"
            className="control-select"
            value={employmentType}
            onChange={(e) => onEmploymentTypeChange(e.target.value)}
          >
            <option value="">All</option>
            {data.employment_types.map((t) => <option key={t} value={t}>{t}</option>)}
          </select>
        </div>
        <div className="result-count" style={{ marginLeft: "auto" }}>
          {loading ? "Loading..." : <><strong>{data.meta.total}</strong> open position{data.meta.total === 1 ? "" : "s"}</>}
        </div>
      </div>

      {error !== "" && (
        <div className="surface reveal" style={{ padding: 18, marginTop: 18, border: "1px solid #f2c6c6", background: "#fff7f7" }}>
          <h3 style={{ margin: "0 0 6px", color: "#9b2c2c" }}>Could not refresh careers</h3>
          <p style={{ margin: 0, color: "#6b3d3d", lineHeight: 1.7 }}>{error}</p>
        </div>
      )}

      {data.data.length ? (
        <>
          <div className="careers-grid">
            {data.data.map((job) => (
              <article key={job.slug} className="career-card reveal">
                <div className="career-card-top">
                  <span className="career-pill career-pill-dept">{job.department}</span>
                  <span className="career-pill career-pill-type">{job.employment_type}</span>
                </div>
                <h2 className="career-card-title">{job.title}</h2>
                <p className="career-card-location">{job.location}</p>
                <p className="career-card-summary">{job.short_description}</p>
                <div className="career-card-meta">
                  <span>Posted {job.posted_at ?? "Recently"}</span>
                  <span>Deadline: {job.deadline_at ?? "Open until filled"}</span>
                </div>
                <Link href={`/careers/${job.slug}`} className="career-card-action">
                  <span>View Details</span><span aria-hidden="true">→</span>
                </Link>
              </article>
            ))}
          </div>
          <Pagination meta={data.meta} links={data.links} onPage={onPage} navLabel="Careers pagination" />
        </>
      ) : (
        <div className="surface reveal" style={{ padding: 22, marginTop: 20 }}>
          <h3 style={{ margin: "0 0 8px", color: "var(--green)", fontFamily: "'Playfair Display', serif" }}>No Open Positions Found</h3>
          <p style={{ margin: 0, color: "var(--muted)", lineHeight: 1.7 }}>Try changing the filters or check back later for new opportunities.</p>
        </div>
      )}
    </section>
  );
}
