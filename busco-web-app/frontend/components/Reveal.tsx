"use client";

import Link from "next/link";
import { useEffect, useRef, useState, type CSSProperties, type ReactNode } from "react";

type Tag = "div" | "header" | "section" | "article";

type ElementProps = {
  as?: Tag;
  className?: string;
  children?: ReactNode;
  style?: CSSProperties;
};

type LinkRevealProps = {
  as: "link";
  href: string;
  className?: string;
  children?: ReactNode;
  style?: CSSProperties;
};

type Props = ElementProps | LinkRevealProps;

function useRevealClasses(className?: string) {
  const ref = useRef<HTMLElement | null>(null);
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry?.isIntersecting) {
          setVisible(true);
          observer.disconnect();
        }
      },
      { threshold: 0.12 },
    );

    observer.observe(el);
    return () => observer.disconnect();
  }, []);

  const classes = ["reveal", visible && "is-visible", className].filter(Boolean).join(" ");

  return { ref, classes };
}

export default function Reveal(props: Props) {
  const { className, children, style } = props;
  const { ref, classes } = useRevealClasses(className);

  if ("as" in props && props.as === "link") {
    return (
      <Link ref={ref as React.Ref<HTMLAnchorElement>} href={props.href} className={classes} style={style}>
        {children}
      </Link>
    );
  }

  const Tag = props.as ?? "div";

  return (
    <Tag ref={ref as never} className={classes} style={style}>
      {children}
    </Tag>
  );
}
