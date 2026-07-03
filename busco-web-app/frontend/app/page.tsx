import type { Metadata } from "next";
import Link from "next/link";
import { getHome } from "@/lib/api";
import { formatDate, formatDifference, trendUiClass } from "@/lib/format";

export const metadata: Metadata = {
  title: "Home",
  description: "Official BUSCO Sugar Milling Co., Inc. website with company profile, news, and Quedan updates.",
};

export default async function HomePage() {
  let data;
  try {
    data = await getHome();
  } catch {
    data = { latest_news: [], active_quedan: null, previous_quedan: null };
  }

  const active = data.active_quedan;
  const previous = data.previous_quedan;

  return (
    <>
      <section className="home-hero">
        <div className="hero-shell">
          <div className="reveal">
            <h1 className="hero-title">
              From Cane Fields to <span>Trusted Sugar Quality</span>
            </h1>
            <p className="hero-copy">
              BUSCO Sugar Milling Co., Inc. serves Bukidnon and neighboring communities through reliable milling operations,
              quality assurance, and farmer-centered collaboration.
            </p>
            <div className="hero-stats">
              <div className="hero-stat"><strong>50+</strong><span>Years Serving</span></div>
              <div className="hero-stat"><strong>5K+</strong><span>Planter Partners</span></div>
              <div className="hero-stat"><strong>24/7</strong><span>Mill Operations</span></div>
            </div>
          </div>
        </div>
      </section>

      <section className="section-shell">
        <div className="reveal">
          <span className="eyebrow">Latest Updates</span>
          <h2 className="section-title">Latest News & Achievements</h2>
          <p className="section-copy">Latest published BUSCO news articles from the database.</p>
        </div>
        <div className="news-preview-grid">
          {data.latest_news.length ? (
            data.latest_news.map((article) => (
              <Link key={article.id} className="preview-card reveal" href={`/news/${article.id}`}>
                {article.image_url && !article.image_url.endsWith("no-image.svg") ? (
                  <div className="preview-thumb">
                    <img src={article.image_url} alt={article.title} style={{ width: "100%", height: "100%", objectFit: "cover" }} />
                  </div>
                ) : (
                  <div className="preview-thumb" style={{ display: "flex", alignItems: "center", justifyContent: "center", background: "linear-gradient(180deg, #f5f8f2 0%, #eef3ea 100%)", borderBottom: "1px solid #e3eadc" }}>
                    <div style={{ textAlign: "center", color: "#6d7c70", padding: 12 }}>
                      <div style={{ fontWeight: 700, fontSize: ".9rem", color: "#516256" }}>No uploaded photo</div>
                      <div style={{ fontSize: ".78rem", marginTop: 4 }}>{article.category}</div>
                    </div>
                  </div>
                )}
                <div className="preview-body">
                  <div className="preview-meta">
                    <span className="pill">{article.category}</span>
                    <span className="preview-date">{formatDate(article.created_at)}</span>
                  </div>
                  <h3 className="preview-title">{article.title}</h3>
                  <p className="preview-copy">{article.sub_title || article.excerpt}</p>
                </div>
              </Link>
            ))
          ) : (
            <div className="preview-card reveal" style={{ gridColumn: "1 / -1", textDecoration: "none", cursor: "default" }}>
              <div className="preview-body">
                <div className="preview-meta"><span className="pill">No News Yet</span></div>
                <h3 className="preview-title">News preview will appear here after publishing articles.</h3>
                <div style={{ marginTop: 10 }}><Link className="btn btn-secondary" href="/news">Open News Page</Link></div>
              </div>
            </div>
          )}
        </div>
        <div className="reveal" style={{ marginTop: 16 }}><Link className="btn btn-secondary" href="/news">View All News</Link></div>
      </section>

      <section className="section-shell section-alt">
        <div className="reveal">
          <span className="eyebrow">Current Announcement</span>
          <h2 className="section-title">Active Quedan Price</h2>
        </div>
        <div className="quedan-spotlight reveal">
          <div className="quedan-top">
            <div className="buying-price-head">BUSCO BUYING PRICE</div>
            <div className="buying-price-dates">
              <span><strong>Trading Date:</strong> {active?.trading_date ? formatDate(active.trading_date) : "Pending"}</span>
              <span><strong>Weekending:</strong> {active?.weekending_date ? formatDate(active.weekending_date) : "Pending"}</span>
            </div>
            <div className="quedan-price">{active?.formatted_price ?? "PHP 0.00"}</div>
            <div className="quedan-label">{active?.price_subtext || "Net of Taxes & Liens"}</div>
          </div>
          <div className="quedan-bottom">
            <span><strong>Previous Week:</strong> {previous ? `${formatDate(previous.trading_date)} - ${previous.formatted_price}` : "No previous record"}</span>
            <span><strong>Difference:</strong> {formatDifference(active?.difference)}</span>
            <span className={`trend ${trendUiClass(active?.trend)}`}>{active?.trend ?? "NO CHANGE"}</span>
          </div>
          <p className="buying-note">{active?.notes || "Note: Negros buying price is Gross Price and Busco buying price is Net Price."}</p>
        </div>
        <div className="reveal" style={{ marginTop: 14 }}><Link className="btn btn-secondary" href="/quedan">View Full Quedan Page</Link></div>
      </section>

      <section className="section-shell">
        <div className="home-community-block reveal">
          <div className="home-community-media">
            <span className="home-community-accent home-community-accent-top" aria-hidden="true" />
            <img src="/img/training_events.webp" alt="BUSCO farmer training and community learning session" />
            <span className="home-community-accent home-community-accent-bottom" aria-hidden="true" />
          </div>
          <div className="home-community-content">
            <h4 className="home-community-kicker">Community & Social Responsibility</h4>
            <h2 className="home-community-title">Empowering Local Farming Communities</h2>
            <p className="home-community-copy">
              At BUSCO, our growth is tied to the prosperity of our surrounding communities. Through training,
              outreach, and farmer support programs, we help strengthen productivity, safety, and long-term sustainability
              in Butong, Quezon, and across Bukidnon.
            </p>
            <ul className="home-community-list" aria-label="Community impact highlights">
              <li>Farmer training sessions focused on better crop management and field productivity.</li>
              <li>Sustainable farming workshops supporting soil health and improved yields.</li>
              <li>Community support initiatives and local outreach programs in partner barangays.</li>
            </ul>
            <Link className="btn btn-primary" href="/news?category=CSR%20/%20Community">View Our Impact</Link>
          </div>
        </div>
      </section>
    </>
  );
}
