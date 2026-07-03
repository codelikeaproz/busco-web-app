import type { Metadata } from "next";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import "./globals.css";

export const metadata: Metadata = {
  title: {
    default: "BUSCO Sugar Milling Co., Inc.",
    template: "%s | BUSCO Sugar Milling Co., Inc.",
  },
  description: "Official BUSCO Sugar Milling Co., Inc. website with company profile, news, and Quedan updates.",
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <head>
        <link rel="icon" type="image/jpeg" href="/img/busco_logo.jpg?v=2" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
        <link
          href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap"
          rel="stylesheet"
        />
        <link rel="stylesheet" href="/css/busco-static.css" />
      </head>
      <body>
        <Navbar />
        <main className="site-main">{children}</main>
        <Footer />
      </body>
    </html>
  );
}
