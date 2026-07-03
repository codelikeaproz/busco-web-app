import Link from "next/link";

export default function NotFound() {
  return (
    <section className="section" style={{ paddingTop: "4rem", paddingBottom: "4rem" }}>
      <div className="section-inner" style={{ maxWidth: 680, margin: "0 auto", textAlign: "center" }}>
        <span className="eyebrow">404</span>
        <h1 className="section-title">Page not found</h1>
        <p className="section-copy">
          The page you requested does not exist or may have been moved.
        </p>
        <div style={{ display: "flex", gap: 12, justifyContent: "center", flexWrap: "wrap", marginTop: 24 }}>
          <Link href="/" className="btn btn-primary">
            Go to Home
          </Link>
          <Link href="/news" className="btn btn-secondary">
            Open News
          </Link>
        </div>
      </div>
    </section>
  );
}
