import { defineConfig } from "astro/config";
import tailwind from "@astrojs/tailwind";
import react from "@astrojs/react";
import sitemap from "@astrojs/sitemap";

export default defineConfig({
  site: "https://renovalink.com",
  output: "static", // Pure static export (no Netlify adapter / SSR function)
  integrations: [
    tailwind(),
    react(),
    sitemap({
      changefreq: "weekly",
      priority: 0.7,
      lastmod: new Date(),
    }),
  ],
  image: {
    domains: [
      "renovalinksite.local",
      "renovalink.local",
      "admin.renovalink.com",
      "dodgerblue-rail-758925.hostingersite.com",
    ],
    remotePatterns: [
      {
        protocol: "http",
        hostname: "renovalinksite.local",
      },
      {
        protocol: "http",
        hostname: "renovalink.local",
      },
      {
        protocol: "https",
        hostname: "renovalink.local",
      },
      {
        protocol: "https",
        hostname: "admin.renovalink.com",
      },
    ],
  },
  vite: {
    // Removed custom rollup output naming to restore default Astro/Vite CSS emission.
    // (Previous customization appeared to coincide with missing *.css assets in dist.)
    ssr: {
      external: ["node-cache"],
    },
  },
  compressHTML: true,
  // Dev server config (only used locally)
  server: {
    port: 4327,
    host: true,
  },
});
