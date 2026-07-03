import Link from "next/link";

type Props = {
  crumbs: Array<{ label: string; href?: string }>;
  title: string;
  subtitle: string;
};

export default function PageHeader({ crumbs, title, subtitle }: Props) {
  return (
    <header className="page-header reveal">
      <div className="breadcrumb">
        {crumbs.map((crumb, index) => (
          <span key={`${crumb.label}-${index}`}>
            {index > 0 && <span> / </span>}
            {crumb.href ? <Link href={crumb.href}>{crumb.label}</Link> : <span>{crumb.label}</span>}
          </span>
        ))}
      </div>
      <h1 className="page-title">{title}</h1>
      <p className="page-subtitle">{subtitle}</p>
    </header>
  );
}
