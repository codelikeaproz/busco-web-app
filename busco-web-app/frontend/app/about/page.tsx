import type { Metadata } from "next";
import PageHeader from "@/components/PageHeader";
import Reveal from "@/components/Reveal";

export const metadata: Metadata = {
  title: "About",
  description: "Company overview, history, mission, and vision of BUSCO Sugar Milling Co., Inc.",
};

export default function AboutPage() {
  return (
    <>
      <section className="page-shell">
        <PageHeader
          crumbs={[{ label: "Home", href: "/" }, { label: "About" }]}
          title="About BUSCO"
          subtitle="Corporate background, industry role, and long-term commitment to quality sugar milling in Bukidnon."
        />
        <Reveal className="about-intro-block">
          <div className="about-intro-content">
            <h4 className="about-intro-kicker">Who We Are</h4>
            <h2 className="about-intro-title">Driving the Sweet Success of the Philippine Sugar Industry</h2>
            <p className="about-intro-copy">
              BUSCO Sugar Milling Co., Inc. stands as a pillar of industrial strength in Butong, Quezon, Bukidnon.
              Established to harness the rich agricultural potential of the region, BUSCO has grown into one of the
              country&apos;s recognized sugar milling facilities.
            </p>
            <p className="about-intro-copy">
              With a strong commitment to modernization and operational reliability, BUSCO connects local farmers to
              the wider market while maintaining quality-focused milling standards and supporting regional growth.
            </p>
            <div className="about-intro-stats">
              <div className="about-intro-stat"><strong>15K+</strong><span>Daily TCD Capacity</span></div>
              <div className="about-intro-stat"><strong>40+</strong><span>Years of Excellence</span></div>
            </div>
          </div>
          <div className="about-intro-media">
            <span className="about-intro-frame" aria-hidden="true" />
            <img src="/img/announcement.webp" alt="BUSCO Sugar Milling facility and operations" />
          </div>
        </Reveal>
      </section>

      <section className="section-shell section-alt">
        <Reveal className="about-history-head">
          <h2 className="section-title">Our History</h2>
          <p className="section-copy">From a bold vision in Bukidnon to a recognized sugar milling leader, this is the BUSCO journey.</p>
        </Reveal>
        <Reveal className="about-history-timeline">
          <div className="about-history-line" aria-hidden="true" />
          {[
            { year: "1980", title: "Foundation", copy: "BUSCO was established in Butong, Quezon, Bukidnon to support the region's strong sugarcane potential.", side: "left", gold: true },
            { year: "1995", title: "Expansion Phase", copy: "Major infrastructure upgrades expanded milling capacity and improved service coverage for more farmers.", side: "right", gold: false },
            { year: "2010", title: "Technological Modernization", copy: "Automation and process improvements enhanced sugar recovery, safety, and plant reliability.", side: "left", gold: false },
            { year: "Present Day", title: "Sustainable Leadership", copy: "BUSCO continues to advance sustainable milling, community development, and dependable Quedan performance.", side: "right", gold: true },
          ].map((item) => (
            <article key={item.year} className="about-history-item">
              <div className={`about-history-dot${item.gold ? " about-history-dot-gold" : ""}`} aria-hidden="true" />
              <div className={`about-history-card about-history-card-${item.side}`}>
                <h3>{item.year}</h3>
                <h4>{item.title}</h4>
                <p>{item.copy}</p>
              </div>
            </article>
          ))}
        </Reveal>
      </section>

      <section className="section-shell about-vm-section">
        <div className="about-vm-grid">
          <Reveal as="article" className="about-vm-card">
            <div className="about-vm-icon" aria-hidden="true">V</div>
            <div>
              <h3>Our Vision</h3>
              <p>To be a premier sugar milling company recognized for operational excellence, sustainable practices, and meaningful contribution to stakeholder and community prosperity.</p>
            </div>
          </Reveal>
          <Reveal as="article" className="about-vm-card">
            <div className="about-vm-icon" aria-hidden="true">M</div>
            <div>
              <h3>Our Mission</h3>
              <p>To produce high-quality sugar products through efficient and reliable milling operations while supporting farmers, employees, and the Bukidnon community through responsible stewardship.</p>
            </div>
          </Reveal>
        </div>
      </section>

      <section className="section-shell">
        <Reveal className="about-values-head">
          <h4 className="about-intro-kicker">What Drives Us</h4>
          <h2 className="section-title">Our Core Values</h2>
        </Reveal>
        <div className="about-values-grid">
          {[
            { icon: "S", title: "Safety", copy: "We prioritize the well-being of our workforce and surrounding communities in every operation." },
            { icon: "Q", title: "Quality", copy: "We maintain high production standards to deliver dependable sugar milling performance and output." },
            { icon: "C", title: "Community", copy: "We work as partners in progress for the growth and development of Bukidnon farming communities." },
            { icon: "G", title: "Growth", copy: "We embrace innovation and sustainable practices to support long-term resilience and shared success." },
          ].map((v) => (
            <Reveal as="article" key={v.title} className="about-value-card">
              <div className="about-value-icon" aria-hidden="true">{v.icon}</div>
              <h3>{v.title}</h3>
              <p>{v.copy}</p>
            </Reveal>
          ))}
        </div>
      </section>
    </>
  );
}
