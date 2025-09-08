import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind';
import react from '@astrojs/react';
import sitemap from '@astrojs/sitemap';
import node from '@astrojs/node';

export default defineConfig({
  site: 'https://renovalink.com',
  output: 'server',
  adapter: node({
    mode: 'standalone'
  }),
  integrations: [
    tailwind({
      applyBaseStyles: false,
    }),
    react(),
    sitemap({
      changefreq: 'weekly',
      priority: 0.7,
      lastmod: new Date(),
    }),
  ],
  image: {
    domains: ['renovalinksite.local', 'renovalink.local', 'admin.renovalink.com'],
    remotePatterns: [
      {
        protocol: 'http',
        hostname: 'renovalinksite.local',
      },
      {
        protocol: 'http',
        hostname: 'renovalink.local',
      },
      {
        protocol: 'https', 
        hostname: 'renovalink.local',
      },
      {
        protocol: 'https',
        hostname: 'admin.renovalink.com',
      }
    ]
  },
  vite: {
    build: {
      rollupOptions: {
        output: {
          assetFileNames: 'assets/[name].[hash][extname]',
          chunkFileNames: 'assets/[name].[hash].js',
          entryFileNames: 'assets/[name].[hash].js',
        },
      },
    },
    ssr: {
      external: ['node-cache'],
    },
  },
  compressHTML: true,
  build: {
    inlineStylesheets: 'auto',
  },
  server: {
    port: 4321,
    host: true
  }
});