import type { Metadata } from "next";
import PageHeader from "@/components/PageHeader";

export const metadata: Metadata = {
  title: "Contact",
  description: "Contact information for BUSCO Sugar Milling Co., Inc. including office location, email, and phone.",
};

export default function ContactPage() {
  return (
    <section className="page-shell">
      <PageHeader
        crumbs={[{ label: "Home", href: "/" }, { label: "Contact" }]}
        title="Contact BUSCO"
        subtitle="Reach our team for inquiries regarding milling operations, partnerships, and careers."
      />
      <div className="contact-grid">
        <section className="contact-panel reveal">
          <h3>Office Information</h3>
          <p>For inquiries regarding milling operations, partnerships, and careers, contact our team through the channels below.</p>
          <ul className="contact-list">
            <li><strong>Address:</strong> Brgy. Butong, Quezon, Bukidnon, Philippines</li>
            <li><strong>Email:</strong> hrd_buscosugarmill@yahoo.com</li>
            <li><strong>Telefax:</strong> (02) 817-8403 / Local 143</li>
            <li><strong>Mobile:</strong> 0997-688-5420</li>
          </ul>
        </section>
        <section className="contact-map reveal">
          <h3>BUSCO Location Map</h3>
          <div className="contact-map-frame">
            <img className="contact-map-image" src="/img/busco_map.webp" alt="Satellite map of BUSCO Sugar Milling Co., Inc. in Quezon, Bukidnon" />
          </div>
          <p className="contact-map-note">Brgy. Butong, Quezon, Bukidnon, Philippines</p>
        </section>
      </div>
    </section>
  );
}
