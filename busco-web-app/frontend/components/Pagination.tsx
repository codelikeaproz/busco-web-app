import type { PaginationLinks, PaginationMeta } from "@/lib/types";

type Props = {
  meta: PaginationMeta;
  links: PaginationLinks;
  onPage?: (page: number) => void;
  navLabel?: string;
  className?: string;
};

export default function Pagination({
  meta,
  links,
  onPage,
  navLabel = "Pagination",
  className = "news-pagination",
}: Props) {
  if (meta.last_page <= 1) return null;

  const pages = Array.from({ length: meta.last_page }, (_, i) => i + 1);

  const prev = () => onPage?.(meta.current_page - 1);
  const next = () => onPage?.(meta.current_page + 1);

  return (
    <nav className={className} aria-label={navLabel}>
      {meta.current_page <= 1 ? (
        <span className="page-chip" aria-disabled="true">Prev</span>
      ) : onPage ? (
        <button type="button" className="page-chip" onClick={prev}>Prev</button>
      ) : (
        <a className="page-chip" href={links.prev ?? "#"}>Prev</a>
      )}

      {pages.map((page) =>
        onPage ? (
          <button
            key={page}
            type="button"
            className={`page-chip${meta.current_page === page ? " active" : ""}`}
            onClick={() => onPage(page)}
            aria-current={meta.current_page === page ? "page" : undefined}
          >
            {page}
          </button>
        ) : (
          <a
            key={page}
            className={`page-chip${meta.current_page === page ? " active" : ""}`}
            href={`?page=${page}`}
            aria-current={meta.current_page === page ? "page" : undefined}
          >
            {page}
          </a>
        ),
      )}

      {meta.current_page >= meta.last_page ? (
        <span className="page-chip" aria-disabled="true">Next</span>
      ) : onPage ? (
        <button type="button" className="page-chip" onClick={next}>Next</button>
      ) : (
        <a className="page-chip" href={links.next ?? "#"}>Next</a>
      )}
    </nav>
  );
}
