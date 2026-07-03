import type { Metadata } from "next";
import { Suspense } from "react";
import NewsListing from "@/components/NewsListing";
import { getNews } from "@/lib/api";

export const metadata: Metadata = {
  title: "News & Achievements",
  description: "Company announcements, milestones, and event updates from BUSCO Sugar Milling Co., Inc.",
};

type Props = { searchParams: Promise<{ category?: string; page?: string }> };

export default async function NewsPage({ searchParams }: Props) {
  const params = await searchParams;
  const category = params.category ?? "";
  const page = Number(params.page ?? "1");

  let initial;
  try {
    initial = await getNews({ category: category || undefined, page });
  } catch {
    initial = { data: [], meta: { current_page: 1, last_page: 1, per_page: 6, total: 0, from: null, to: null }, links: { first: null, last: null, prev: null, next: null } };
  }

  return (
    <Suspense fallback={<section className="page-shell"><p>Loading news...</p></section>}>
      <NewsListing initial={initial} initialCategory={category} />
    </Suspense>
  );
}
