"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useState } from "react";

const links = [
  { href: "/", label: "Home", key: "home" },
  { href: "/about", label: "About", key: "about" },
  { href: "/services", label: "Services", key: "services" },
  { href: "/process", label: "Process", key: "process" },
  { href: "/news", label: "News & Updates", key: "news" },
  { href: "/quedan", label: "Quedan", key: "quedan" },
  { href: "/careers", label: "Careers", key: "careers" },
];

function activeKey(pathname: string): string {
  if (pathname === "/") return "home";
  if (pathname.startsWith("/news")) return "news";
  if (pathname.startsWith("/careers")) return "careers";
  if (pathname.startsWith("/quedan")) return "quedan";
  if (pathname.startsWith("/about")) return "about";
  if (pathname.startsWith("/services")) return "services";
  if (pathname.startsWith("/process")) return "process";
  if (pathname.startsWith("/contact")) return "contact";
  return "";
}

export default function Navbar() {
  const pathname = usePathname();
  const active = activeKey(pathname);
  // Track which route opened the menu; when pathname changes, menuOpen becomes false automatically.
  const [openMenuPath, setOpenMenuPath] = useState<string | null>(null);
  const menuOpen = openMenuPath === pathname;

  useEffect(() => {
    const nav = document.querySelector(".site-nav");
    const onScroll = () => {
      if (!nav) return;
      nav.classList.toggle("scrolled", window.scrollY > 12);
    };
    window.addEventListener("scroll", onScroll);
    onScroll();
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    const reveals = document.querySelectorAll(".reveal");
    if (!reveals.length) return;
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12 },
    );
    reveals.forEach((item) => observer.observe(item));
    return () => observer.disconnect();
  });

  return (
    <nav className="site-nav" aria-label="Primary navigation">
      <div className="nav-inner">
        <Link className="nav-brand" href="/">
          <img className="nav-logo" src="/img/busco_logo.webp" alt="BUSCO logo" />
          <span className="nav-name">
            BUSCO Sugar Milling
            <small>Co., Inc. - Bukidnon</small>
          </span>
        </Link>

        {menuOpen ? (
          <button
            className="nav-toggle"
            type="button"
            aria-label="Toggle menu"
            aria-expanded="true"
            onClick={() => setOpenMenuPath(null)}
          >
            <span aria-hidden="true">|||</span>
          </button>
        ) : (
          <button
            className="nav-toggle"
            type="button"
            aria-label="Toggle menu"
            aria-expanded="false"
            onClick={() => setOpenMenuPath(pathname)}
          >
            <span aria-hidden="true">|||</span>
          </button>
        )}

        <div className={`nav-menu${menuOpen ? " open" : ""}`}>
          {links.map((link) => (
            <Link
              key={link.href}
              className={`nav-link${active === link.key ? " is-active" : ""}`}
              href={link.href}
            >
              {link.label}
            </Link>
          ))}
          <Link className="btn btn-primary nav-cta" href="/contact">
            Contact
          </Link>
        </div>
      </div>
    </nav>
  );
}
