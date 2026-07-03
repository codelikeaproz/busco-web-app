import type { Metadata } from "next";
import Link from "next/link";
import { notFound } from "next/navigation";
import Reveal from "@/components/Reveal";
import { getNewsArticle } from "@/lib/api";
import { formatDate } from "@/lib/format";

type Props = { params: Promise<{ id: string }> };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { id } = await params;
  try {
    const { data } = await getNewsArticle(id);
    return { title: data.title, description: data.excerpt, openGraph: { images: [data.image_url] } };
  } catch {
    return { title: "News Article" };
  }
}

export default async function NewsShowPage({ params }: Props) {
  const { id } = await params;
  let article;
  let related;
  try {
    const response = await getNewsArticle(id);
    article = response.data;
    related = response.related;
  } catch {
    notFound();
  }

  return (
    <>
      <section className="article-hero">
        <div className="article-hero-shell">
          <div className="breadcrumb">
            <Link href="/">Home</Link><span> / </span>
            <Link href="/news">News</Link><span> / </span><span>Article</span>
          </div>
          <span className="eyebrow">{article.category}</span>
          <h1>{article.title}</h1>
          {article.sub_title && <p className="article-subtitle">{article.sub_title}</p>}
          <div className="article-meta">
            <span>{formatDate(article.created_at, "long")}</span>
            {article.is_featured && <span>Featured</span>}
          </div>
        </div>
      </section>

      <section className="article-layout">
        <Reveal as="article" className="article-content">
          {article.gallery_images.length > 0 && (
            <div className="article-gallery">
              {article.gallery_images.map((img) => (
                <figure key={img.path}>
                  <img src={img.url} alt={article.title} />
                </figure>
              ))}
            </div>
          )}
          <div style={{ lineHeight: 1.9, color: "#32433a", whiteSpace: "pre-wrap" }}>{article.content}</div>
          <div style={{ marginTop: 18 }}>
            <Link className="btn btn-secondary" href="/news">Back to News</Link>
          </div>
        </Reveal>

        <aside className="article-sidebar">
          <Reveal as="section" className="sidebar-card">
            <div className="sidebar-head">Article Info</div>
            <div className="sidebar-row"><small>Category</small><strong>{article.category}</strong></div>
            <div className="sidebar-row"><small>Published</small><strong>{formatDate(article.created_at, "long")}</strong></div>
          </Reveal>

          {related.length > 0 && (
            <Reveal as="section" className="sidebar-card">
              <div className="sidebar-head">Related Articles</div>
              {related.map((item) => (
                <Link key={item.id} className="sidebar-link" href={`/news/${item.id}`}>
                  <strong>{item.title}</strong>
                  <small>{formatDate(item.created_at)}</small>
                </Link>
              ))}
            </Reveal>
          )}
        </aside>
      </section>
    </>
  );
}
