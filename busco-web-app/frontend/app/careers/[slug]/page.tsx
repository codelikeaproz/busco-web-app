import type { Metadata } from "next";
import Link from "next/link";
import { notFound } from "next/navigation";
import Reveal from "@/components/Reveal";
import { getCareer } from "@/lib/api";
import { formatDate } from "@/lib/format";

type Props = { params: Promise<{ slug: string }> };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { slug } = await params;
  try {
    const { data } = await getCareer(slug);
    return { title: `${data.title} | Careers`, description: data.short_description };
  } catch {
    return { title: "Careers" };
  }
}

export default async function CareerShowPage({ params }: Props) {
  const { slug } = await params;
  let job;
  let relatedJobs;
  try {
    const response = await getCareer(slug);
    job = response.data;
    relatedJobs = response.related_jobs;
  } catch {
    notFound();
  }

  const applyHref = `mailto:${job.application_email}?subject=${encodeURIComponent(`Application - ${job.title}`)}`;

  return (
    <>
      <section className="article-hero" style={{ background: "linear-gradient(135deg, #14411a 0%, #1f6028 55%, #17461d 100%)" }}>
        <div className="article-hero-shell">
          <div className="breadcrumb" style={{ color: "rgba(255,255,255,.72)" }}>
            <Link href="/" style={{ color: "rgba(255,255,255,.82)" }}>Home</Link><span> / </span>
            <Link href="/careers" style={{ color: "rgba(255,255,255,.82)" }}>Careers</Link><span> / </span>
            <span>Job Details</span>
          </div>
          <span className="eyebrow" style={{ background: "rgba(249,168,37,.16)", borderColor: "rgba(249,168,37,.4)", color: "#ffe39f" }}>
            {job.department} • {job.employment_type}
          </span>
          <h1>{job.title}</h1>
          <div className="article-meta">
            <span>Location: {job.location}</span>
            <span>Posted: {formatDate(job.posted_at, "long")}</span>
            <span>Deadline: {job.deadline_at ? formatDate(job.deadline_at, "long") : "Open until filled"}</span>
          </div>
        </div>
      </section>

      <section className="article-layout">
        <Reveal as="article" className="article-content">
          {job.summary && <p className="article-highlight">{job.summary}</p>}
          <h2 style={{ fontSize: "1.8rem", marginTop: 0 }}>Job Description</h2>
          <div style={{ lineHeight: 1.9, color: "#32433a", whiteSpace: "pre-wrap" }}>{job.description}</div>
          {job.responsibilities && (
            <section className="article-box" style={{ marginTop: 18 }}>
              <h3>Key Responsibilities</h3>
              <div style={{ lineHeight: 1.75, color: "#394b3f", whiteSpace: "pre-wrap" }}>{job.responsibilities}</div>
            </section>
          )}
          {job.qualifications && (
            <section className="article-box" style={{ marginTop: 18 }}>
              <h3>Qualifications</h3>
              <div style={{ lineHeight: 1.75, color: "#394b3f", whiteSpace: "pre-wrap" }}>{job.qualifications}</div>
            </section>
          )}
          <div style={{ marginTop: 18, display: "flex", gap: 10, flexWrap: "wrap" }}>
            <Link className="btn btn-secondary" href="/careers">Back to Open Positions</Link>
            <a className="btn btn-primary" href={applyHref}>Apply via Email</a>
          </div>
        </Reveal>

        <aside className="article-sidebar">
          {relatedJobs.length > 0 && (
            <Reveal as="section" className="sidebar-card">
              <div className="sidebar-head">Related Openings</div>
              {relatedJobs.map((related) => (
                <Link key={related.slug} className="sidebar-link" href={`/careers/${related.slug}`}>
                  <strong>{related.title}</strong>
                  <small>{related.department}</small>
                </Link>
              ))}
            </Reveal>
          )}
        </aside>
      </section>
    </>
  );
}
