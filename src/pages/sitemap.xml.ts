import type { APIRoute } from "astro";
import { wordpressClient } from "@/lib/wordpress-client";
import { SITE_URL } from "@/lib/seo";

export const GET: APIRoute = async () => {
  const urls: string[] = ["", "/about", "/contacto", "/servicios"];
  try {
    const services = await wordpressClient.getAllServices();
    services.forEach((s) => urls.push(`/servicios/${s.slug}`));
  } catch (e) {
    console.warn("sitemap: failed to fetch services", e);
  }

  const lastmod = new Date().toISOString();
  const body =
    `<?xml version="1.0" encoding="UTF-8"?>\n` +
    `<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">` +
    urls
      .map((path) => {
        const loc = new URL(path.replace(/\/+/g, "/"), SITE_URL).toString();
        return `<url><loc>${loc}</loc><lastmod>${lastmod}</lastmod><changefreq>weekly</changefreq><priority>${path === "" ? "1.0" : "0.7"}</priority></url>`;
      })
      .join("") +
    `</urlset>`;

  return new Response(body, {
    headers: {
      "Content-Type": "application/xml",
      "Cache-Control": "public, max-age=3600",
    },
  });
};
