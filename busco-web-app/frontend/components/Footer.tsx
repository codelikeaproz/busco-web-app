import Link from "next/link";
import { getAdminUrl } from "@/lib/api";

export default function Footer() {
  const year = new Date().getFullYear();
  const adminUrl = getAdminUrl();

  return (
    <footer className="site-footer">
      <div className="footer-inner">
        <div className="footer-grid">
          <div>
            <div className="footer-brand-head">
              <img className="footer-brand-logo" src="/img/busco_logo.webp" alt="BUSCO logo" />
              <h3 className="footer-title">BUSCO Sugar Milling Co., Inc.</h3>
            </div>
            <p className="footer-copy">
              Brgy. Butong, Quezon, Bukidnon, Philippines. Corporate information portal for operations,
              public advisories, community engagement, and industry updates.
            </p>
          </div>

          <div>
            <h4 className="footer-col-title">Company</h4>
            <Link className="footer-link" href="/about">About</Link>
            <Link className="footer-link" href="/services">Services</Link>
            <Link className="footer-link" href="/process">Milling Process</Link>
          </div>

          <div>
            <h4 className="footer-col-title">Updates</h4>
            <Link className="footer-link" href="/news">News & Achievements</Link>
            <Link className="footer-link" href="/quedan">Quedan Price</Link>
            <Link className="footer-link" href="/news?category=CSR%20/%20Community">Community</Link>
            <a className="footer-link" href={adminUrl}>Admin Login</a>
          </div>

          <div>
            <h4 className="footer-col-title">Contact</h4>
            <a className="footer-link" href="mailto:hrd_buscosugarmill@yahoo.com">hrd_buscosugarmill@yahoo.com</a>
            <a className="footer-link" href="tel:+63028178403">(02) 817-8403</a>
            <a className="footer-link" href="tel:+639976885420">0997-688-5420</a>
            <Link className="footer-link" href="/careers">Open Positions</Link>
          </div>
        </div>

        <div className="footer-bottom">
          <div>
            <small>(c) {year} BUSCO Sugar Milling Co., Inc. All rights reserved.</small>
            <small className="footer-credit">Personal project by Ralph Jumaoas</small>
          </div>
          <span className="footer-gold" aria-hidden="true" />
        </div>
      </div>
    </footer>
  );
}
