import type { Metadata } from "next";
import PageHeader from "@/components/PageHeader";
import Reveal from "@/components/Reveal";

export const metadata: Metadata = {
  title: "Services & Operations",
  description: "Overview of BUSCO services including sugar milling, procurement, quality assurance, and distribution support.",
};

const services = [
  { title: "Sugar Milling", copy: "End-to-end milling operations from cane delivery to sugar output, with continuous monitoring and technical coordination across stations." },
  { title: "Cane Procurement", copy: "Structured procurement coordination with partner planters and field teams to improve milling flow, scheduling, and receiving efficiency." },
  { title: "Quality Assurance", copy: "Laboratory checks and process controls are maintained to meet expected production specifications and support consistent product standards." },
  { title: "Distribution Support", copy: "Coordination of storage and dispatch schedules to align with demand and maintain stable supply movement." },
];

export default function ServicesPage() {
  return (
    <section className="page-shell">
      <PageHeader
        crumbs={[{ label: "Home", href: "/" }, { label: "Services" }]}
        title="Services & Operations"
        subtitle="Key operational services supporting farmers, logistics, and sugar production quality."
      />
      <div className="info-grid">
        {services.map((s) => (
          <Reveal as="article" key={s.title} className="info-card">
            <h3>{s.title}</h3>
            <p>{s.copy}</p>
          </Reveal>
        ))}
      </div>
    </section>
  );
}
