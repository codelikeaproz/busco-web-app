export function formatDate(iso: string | null | undefined, style: "short" | "long" = "short"): string {
  if (!iso) return "";
  const date = new Date(iso);
  if (Number.isNaN(date.getTime())) return "";
  return date.toLocaleDateString("en-US", style === "long"
    ? { year: "numeric", month: "long", day: "numeric" }
    : { month: "short", day: "numeric", year: "numeric" });
}

export function trendUiClass(trend: string | null | undefined): string {
  switch (trend) {
    case "UP":
      return "up";
    case "DOWN":
      return "down";
    default:
      return "flat";
  }
}

export function formatDifference(value: number | null | undefined): string {
  if (value === null || value === undefined) return "N/A";
  const prefix = value > 0 ? "+ " : "";
  return `${prefix}PHP ${value.toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}
