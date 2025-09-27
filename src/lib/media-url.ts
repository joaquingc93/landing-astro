// Utility to normalize WordPress media URLs depending on environment.
// Goal: In development use local WP media; in production use deployed WP domain.
// Controlled by env vars:
//   PUBLIC_WP_LOCAL_MEDIA_BASE (e.g. http://renovalinksite.local)
//   PUBLIC_WP_PROD_MEDIA_BASE  (e.g. https://admin.renovalink.com)
//   PUBLIC_WP_MEDIA_STRATEGY   passthrough | env-swap (default) | proxy
// If proxy: we return /media/wp-content/... and expect a redirect/proxy rule in netlify.toml

const LOCAL_BASE =
  import.meta.env.PUBLIC_WP_LOCAL_MEDIA_BASE || "http://renovalinksite.local";
const PROD_BASE =
  import.meta.env.PUBLIC_WP_PROD_MEDIA_BASE || "https://admin.renovalink.com";
const STRATEGY = (
  import.meta.env.PUBLIC_WP_MEDIA_STRATEGY || "env-swap"
).toLowerCase();

function stripTrailingSlash(u: string) {
  return u.replace(/\/$/, "");
}
function isAbsolute(url: string) {
  return /^(https?:)?\/\//i.test(url);
}

const localBaseClean = stripTrailingSlash(LOCAL_BASE);
const prodBaseClean = stripTrailingSlash(PROD_BASE);

export function normalizeMediaUrl(raw?: string | null): string | null {
  if (!raw || !raw.trim()) return null;
  let url = raw.trim();

  // Ignore data/blob
  if (/^(data:|blob:)/i.test(url)) return url;

  // If relative and strategy needs host, attach appropriate base
  if (!isAbsolute(url)) {
    // Assume it's already a /wp-content/... path
    if (STRATEGY === "proxy") {
      return "/media" + (url.startsWith("/") ? url : "/" + url);
    }
    // Attach local in dev, prod in build
    if (import.meta.env.DEV)
      return localBaseClean + (url.startsWith("/") ? url : "/" + url);
    return prodBaseClean + (url.startsWith("/") ? url : "/" + url);
  }

  // Absolute URL cases
  if (STRATEGY === "passthrough") return url; // Leave as is

  if (STRATEGY === "proxy") {
    // Extract /wp-content path if present
    const match = url.match(/(\/wp-content\/.*)$/i);
    if (match) return "/media" + match[1];
    return url; // Can't proxy if pattern not found
  }

  // Default env-swap: choose base depending on environment
  if (import.meta.env.DEV) {
    // Ensure we point to local when possible
    // If current URL already points to local, keep it, else if it contains prod base swap to local
    if (url.includes(prodBaseClean)) {
      return url.replace(prodBaseClean, localBaseClean);
    }
    return url; // Already local or other domain
  } else {
    // Production build: ensure prod domain
    if (url.includes(localBaseClean)) {
      return url.replace(localBaseClean, prodBaseClean);
    }
    return url;
  }
}

// Helper to bulk-normalize arrays of objects with url property
export function normalizeMediaObject<T extends { url?: string }>(
  obj: T | null | undefined
): T | null {
  if (!obj) return null;
  if (obj.url) {
    const norm = normalizeMediaUrl(obj.url);
    if (norm) obj.url = norm;
  }
  return obj;
}
