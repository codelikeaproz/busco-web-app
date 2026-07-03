"use client";

import { useState } from "react";
import Pagination from "@/components/Pagination";
import PageHeader from "@/components/PageHeader";
import Reveal from "@/components/Reveal";
import { clientFetch } from "@/lib/api";
import { formatDate, formatDifference, trendUiClass } from "@/lib/format";
import type { QuedanItem, QuedanResponse } from "@/lib/types";

type Props = { initial: QuedanResponse };

export default function QuedanListing({ initial }: Props) {
  const [data, setData] = useState(initial);
  const [loading, setLoading] = useState(false);

  const active = data.active_price;
  const previous = data.previous_price;
  const trendClass = trendUiClass(active?.trend);

  const onPage = async (page: number) => {
    setLoading(true);
    try {
      const result = await clientFetch<QuedanResponse>(`/api/quedan?page=${page}`);
      setData(result);
      window.history.pushState({}, "", page > 1 ? `/quedan?page=${page}` : "/quedan");
    } finally {
      setLoading(false);
    }
  };

  return (
    <section className="page-shell">
      <PageHeader
        crumbs={[{ label: "Home", href: "/" }, { label: "Quedan Price" }]}
        title="Quedan Price Announcement"
        subtitle="Official weekly Quedan price updates and historical comparison records."
      />

      {active ? (
        <>
          <Reveal as="article" className="price-hero">
            <div className="price-hero-top">
              <span className="quedan-update-chip">Official Weekly Update</span>
              <div className="buying-price-head">BUSCO BUYING PRICE</div>
              <div className="buying-price-dates">
                <span><strong>Trading Date:</strong> {formatDate(active.trading_date)}</span>
                <span><strong>Weekending:</strong> {formatDate(active.weekending_date)}</span>
              </div>
              <h2>{active.formatted_price}</h2>
              <p>{active.price_subtext || "Net of Taxes & Liens"}</p>
            </div>
            <p className="buying-note buying-note-dark">{active.notes || "Note: Negros buying price is Gross Price and Busco buying price is Net Price."}</p>
          </Reveal>
          <div className="price-grid">
            <Reveal className="price-metric"><small>Previous Week</small><strong>{previous?.formatted_price ?? "N/A"}</strong></Reveal>
            <Reveal className="price-metric"><small>Difference</small><strong>{formatDifference(active.difference)}</strong></Reveal>
            <Reveal className="price-metric"><small>Trend</small><strong><span className={`trend ${trendClass}`}>{active.trend ?? "NO CHANGE"}</span></strong></Reveal>
          </div>
        </>
      ) : (
        <Reveal as="article" className="price-hero">
          <div className="price-hero-top">
            <span className="quedan-update-chip">No Active Price Yet</span>
            <div className="buying-price-head">BUSCO BUYING PRICE</div>
            <h2>PHP 0.00</h2>
            <p>Post the first Quedan record in Admin to activate public display.</p>
          </div>
        </Reveal>
      )}

      <Reveal as="section" className="history-table">
        {loading && <p>Loading history...</p>}
        <table>
          <thead>
            <tr>
              <th>Trading Date</th>
              <th>Weekending Date</th>
              <th>Price</th>
              <th>Difference</th>
              <th>Trend</th>
            </tr>
          </thead>
          <tbody>
            {data.history.data.length ? data.history.data.map((row: QuedanItem) => (
              <tr key={row.id}>
                <td>{formatDate(row.trading_date, "long")}</td>
                <td>{formatDate(row.weekending_date, "long")}</td>
                <td>{row.formatted_price}</td>
                <td>{formatDifference(row.difference)}</td>
                <td><span className={`trend ${trendUiClass(row.trend)}`}>{row.trend ?? "N/A"}</span></td>
              </tr>
            )) : (
              <tr><td colSpan={5}>No archived Quedan history yet.</td></tr>
            )}
          </tbody>
        </table>
        <Pagination meta={data.history.meta} links={data.history.links} onPage={onPage} navLabel="Quedan history pagination" />
      </Reveal>
    </section>
  );
}
