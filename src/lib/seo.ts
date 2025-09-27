// SEO helper utilities
// Provides functions to build structured data objects and safely format meta descriptions.
// Keep these functions framework-agnostic (no direct Astro references) so they can be reused in scripts.

export const SITE_URL = "https://renovalink.com";
export const BUSINESS_NAME = "RenovaLink";
export const COUNTIES = ["Miami-Dade County", "Broward County"];

/** Truncate and sanitize a description for meta tags (default max 155 chars). */
export function truncateDescription(raw: string, max = 155): string {
  if (!raw) return "";
  const text = raw
    .replace(/<[^>]*>/g, "")
    .replace(/\s+/g, " ")
    .trim();
  if (text.length <= max) return text;
  return text.slice(0, max - 1).trimEnd() + "…";
}

/** Build canonical URL from a pathname (ensures trailing slash handling). */
export function buildCanonical(pathname: string): string {
  if (!pathname.startsWith("/")) pathname = "/" + pathname;
  return new URL(pathname, SITE_URL).toString();
}

/** Build FAQPage JSON-LD from an array of Q/A pairs. */
export function buildFaqJsonLD(
  faq: Array<{ question: string; answer: string }>
) {
  return {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    mainEntity: faq.map((q) => ({
      "@type": "Question",
      name: q.question,
      acceptedAnswer: {
        "@type": "Answer",
        text: q.answer,
      },
    })),
  };
}

/** Build BreadcrumbList JSON-LD from an ordered array of {name, item}. */
export function buildBreadcrumbJsonLD(
  items: Array<{ name: string; item: string }>
) {
  return {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    itemListElement: items.map((it, idx) => ({
      "@type": "ListItem",
      position: idx + 1,
      name: it.name,
      item: it.item,
    })),
  };
}

/** Build Organization JSON-LD. */
export function buildOrganizationSchema(opts?: {
  logoUrl?: string;
  sameAs?: string[];
}) {
  return {
    "@context": "https://schema.org",
    "@type": "Organization",
    name: BUSINESS_NAME,
    url: SITE_URL,
    logo:
      opts?.logoUrl ||
      new URL("/images/renovalink-og-default.jpg", SITE_URL).toString(),
    sameAs: opts?.sameAs || [],
  };
}

/** Build WebSite JSON-LD with optional search action. */
export function buildWebsiteSchema() {
  return {
    "@context": "https://schema.org",
    "@type": "WebSite",
    url: SITE_URL,
    name: BUSINESS_NAME,
    potentialAction: {
      "@type": "SearchAction",
      target: `${SITE_URL}/?q={search_term_string}`,
      "query-input": "required name=search_term_string",
    },
  };
}

/** Build Service schema with areaServed counties. */
export function buildServiceSchema(
  name: string,
  description: string,
  slug?: string
) {
  return {
    "@context": "https://schema.org",
    "@type": "Service",
    name,
    serviceType: name,
    description,
    provider: {
      "@type": "LocalBusiness",
      name: BUSINESS_NAME,
      url: SITE_URL,
    },
    areaServed: COUNTIES.map((c) => ({
      "@type": "AdministrativeArea",
      name: c,
    })),
    ...(slug && { url: buildCanonical(`/servicios/${slug}`) }),
  };
}

/** Combine multiple schema objects into a single array (filtering falsy). */
export function combineSchemas(...schemas: any[]) {
  return schemas.filter(Boolean);
}
