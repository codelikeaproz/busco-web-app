import type { Metadata } from "next";
import QuedanListing from "@/components/QuedanListing";
import { getQuedan } from "@/lib/api";

export const metadata: Metadata = {
  title: "Quedan Price",
  description: "Weekly Quedan price announcement page with official active price and historical records.",
};

type Props = { searchParams: Promise<{ page?: string }> };

export default async function QuedanPage({ searchParams }: Props) {
  const params = await searchParams;
  let initial;
  try {
    initial = await getQuedan(Number(params.page ?? "1"));
  } catch {
    initial = {
      active_price: null,
      previous_price: null,
      history: {
        data: [],
        meta: { current_page: 1, last_page: 1, per_page: 5, total: 0, from: null, to: null },
        links: { first: null, last: null, prev: null, next: null },
      },
    };
  }

  return <QuedanListing initial={initial} />;
}
