import type { Metadata } from "next";
import PageHeader from "@/components/PageHeader";
import Reveal from "@/components/Reveal";

export const metadata: Metadata = {
  title: "Sugar Milling Process",
  description: "Step-by-step overview of BUSCO sugar milling and production workflow.",
};

const stages = [
  { n: "01", badge: "gold", img: "/img/announcement.webp", alt: "Sugarcane trucks arriving at BUSCO", icon: "CD", title: "Cane Delivery & Weighing", copy: "The process begins with the arrival of harvested sugarcane from local farms. Trucks are weighed to record gross load and establish accurate receiving records before unloading.", items: ["Automated weighbridge and receiving control", "Initial sampling and intake documentation"], altClass: "" },
  { n: "02", badge: "green", img: "/img/cane_cutting.webp", alt: "Cane cutting and shredding", icon: "CS", title: "Cane Cutting & Shredding", copy: "Cane stalks are prepared by knives and shredders to rupture cane cells and produce fibrous material ready for efficient milling and extraction.", items: ["Heavy-duty cane preparation equipment", "Fiber condition optimized for extraction efficiency"], altClass: "process-stage-alt", imgClass: "process-stage-image-tight", reverse: true },
  { n: "03", badge: "gold", img: "/img/juice_extraction.webp", alt: "Juice extraction", icon: "JE", title: "Juice Extraction", copy: "The prepared cane passes through milling tandems where pressure extracts sugar-rich juice. The remaining bagasse fiber is separated and can be reused as boiler fuel.", items: ["Multi-stage milling tandem operation", "Bagasse recovery for energy use"], imgClass: "process-stage-image-wide" },
  { n: "04", badge: "green", img: "/img/clarification.webp", alt: "Clarification stage", icon: "CL", title: "Clarification", copy: "Raw juice is heated and treated to remove impurities and stabilize quality, resulting in clarified juice for downstream concentration.", items: ["Liming and heating treatment process", "Impurity settling and separation stages"], altClass: "process-stage-alt", reverse: true },
  { n: "05", badge: "gold", img: "/img/evaporation.webp", alt: "Evaporation stage", icon: "EV", title: "Evaporation", copy: "Clarified juice enters evaporator vessels where water is removed under controlled conditions, concentrating the liquid into thick syrup.", items: ["Multiple-effect evaporator vessels", "Energy-efficient steam usage"], imgClass: "process-stage-image-tight" },
  { n: "06", badge: "green", img: "/img/crystallization.webp", alt: "Crystallization stage", icon: "CR", title: "Crystallization", copy: "Syrup is concentrated further in vacuum pans until crystallization begins. Seed crystals are introduced to control crystal growth and form massecuite.", items: ["Vacuum pan boiling", "Controlled crystal growth process"], altClass: "process-stage-alt", reverse: true },
  { n: "07", badge: "gold", img: "/img/centrifugation.webp", alt: "Centrifugation stage", icon: "CF", title: "Centrifugation", copy: "Massecuite is spun in centrifugal machines to separate sugar crystals from molasses, producing raw sugar for final drying and handling.", items: ["High-speed basket centrifugals", "Crystal and molasses separation"], imgClass: "process-stage-image-wide" },
  { n: "08", badge: "green", img: "/img/bagging.webp", alt: "Drying and packaging", icon: "DP", title: "Drying & Packaging", copy: "Raw sugar is dried to reduce residual moisture, then weighed and packed for storage, dispatch, and delivery to refineries and markets.", items: ["Drying for moisture control and storage stability", "Weighing, bagging, and dispatch preparation"], altClass: "process-stage-alt", reverse: true },
];

export default function ProcessPage() {
  return (
    <>
      <section className="page-shell">
        <PageHeader
          crumbs={[{ label: "Home", href: "/" }, { label: "Sugar Milling Process" }]}
          title="Sugar Milling Process"
          subtitle="A step-by-step guide to how sugarcane is transformed into raw sugar through BUSCO's milling operations."
        />
      </section>
      <div className="process-sections">
        {stages.map((stage) => (
          <Reveal
            as="section"
            key={stage.n}
            className={`process-stage${stage.altClass ? ` ${stage.altClass}` : ""}`}
          >
            <div className={`section-shell process-stage-inner${stage.reverse ? " process-stage-inner-reverse" : ""}`}>
              <div className="process-stage-media">
                <div className={`process-stage-badge process-stage-badge-${stage.badge}`}>{stage.n}</div>
                <img className={`process-stage-image${stage.imgClass ? ` ${stage.imgClass}` : ""}`} src={stage.img} alt={stage.alt} />
              </div>
              <div className="process-stage-content">
                <div className="process-stage-heading">
                  <span className="process-stage-icon">{stage.icon}</span>
                  <h2>{stage.title}</h2>
                </div>
                <p>{stage.copy}</p>
                <ul className="process-stage-list">
                  {stage.items.map((item) => <li key={item}>{item}</li>)}
                </ul>
              </div>
            </div>
          </Reveal>
        ))}
      </div>
    </>
  );
}
