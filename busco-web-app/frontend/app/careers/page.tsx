import type { Metadata } from "next";
import { Suspense } from "react";
import CareersListing from "@/components/CareersListing";
import { getCareers } from "@/lib/api";

export const metadata: Metadata = {
  title: "Careers",
  description: "Current job openings and career opportunities at BUSCO Sugar Milling Co., Inc.",
};

type Props = { searchParams: Promise<{ search?: string; department?: string; employment_type?: string; page?: string }> };

export default async function CareersPage({ searchParams }: Props) {
  const params = await searchParams;
  let initial;
  try {
    initial = await getCareers({
      search: params.search,
      department: params.department,
      employment_type: params.employment_type,
      page: Number(params.page ?? "1"),
    });
  } catch {
    initial = {
      data: [],
      meta: { current_page: 1, last_page: 1, per_page: 6, total: 0, from: null, to: null },
      links: { first: null, last: null, prev: null, next: null },
      departments: [],
      employment_types: ["Full-time", "Part-time", "Contractual", "Seasonal"],
      filters: { department: "", employment_type: "", search: "" },
    };
  }

  return (
    <Suspense fallback={<section className="page-shell"><p>Loading careers...</p></section>}>
      <CareersListing initial={initial} />
    </Suspense>
  );
}
