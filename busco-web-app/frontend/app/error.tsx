"use client";

import Link from "next/link";
import { useEffect } from "react";

export default function Error({
  error,
  reset,
}: {
  error: Error & { digest?: string };
  reset: () => void;
}) {
  useEffect(() => {
    console.error(error);
  }, [error]);

  return (
    <section className="section" style={{ paddingTop: "4rem", paddingBottom: "4rem" }}>
      <div className="section-inner" style={{ maxWidth: 680, margin: "0 auto", textAlign: "center" }}>
        <span className="eyebrow">Error</span>
        <h1 className="section-title">Something went wrong</h1>
        <p className="section-copy">
          We could not load this page. Please try again or return to the homepage.
        </p>
        <div style={{ display: "flex", gap: 12, justifyContent: "center", flexWrap: "wrap", marginTop: 24 }}>
          <button type="button" className="btn btn-primary" onClick={reset}>
            Try again
          </button>
          <Link href="/" className="btn btn-secondary">
            Go to Home
          </Link>
        </div>
      </div>
    </section>
  );
}
