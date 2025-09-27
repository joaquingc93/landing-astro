# RenovaLink Landing Page

A high-performance, professional landing page for RenovaLink - a Florida-based remodeling company specializing in pool renovations, concrete work, residential cleaning, and structural engineering services.

## 🚀 Tech Stack

- **Framework**: Astro.js 4.16.19 (Latest stable with hybrid rendering)
- **Styling**: Tailwind CSS 3.4.17 + @tailwindcss/typography + @tailwindcss/forms
- **CMS**: Headless WordPress integration
- **Validation**: Zod 3.24.1 schemas for type-safe API consumption
- **Performance**: Optimized for Core Web Vitals
- **SEO**: Comprehensive structured data and meta optimization
- **Development**: TypeScript 5.7.2 with ESLint 9.17.0

## 🏗️ Architecture Overview

This project implements a modern headless CMS architecture using Astro.js as the frontend framework with WordPress as the content management system. The current deployment target is a 100% static export (sin SSR/Edge en producción) para máxima simplicidad y performance; el contenido se obtiene en build-time mediante la REST API de WordPress.

### Key Features

- **Islands Architecture**: Interactive components load only when needed
- **Hybrid Rendering**: SSG for performance + SSR for dynamic content
- **Type-Safe API**: Zod validation for WordPress REST API responses
- **Professional Design**: Custom Tailwind design system optimized for conversion
- **SEO Optimized**: Structured data, meta tags, and performance optimization
- **Accessible**: WCAG 2.1 AA compliance with keyboard navigation and screen reader support

## 📁 Project Structure

```
src/
├── components/          # Reusable Astro components
│   ├── Hero.astro      # Dynamic hero section
│   ├── ServicesGrid.astro  # Services showcase
│   ├── ProjectGallery.astro  # Project portfolio with filtering

│   ├── ContactCTA.astro  # Lead capture form
│   ├── Footer.astro    # Site footer
│   └── SEOHead.astro   # SEO meta tags
├── layouts/
│   └── Layout.astro    # Base layout with performance optimizations
├── pages/              # File-based routing
│   ├── index.astro     # Homepage
│   ├── contacto.astro  # Contact page
│   └── servicios/      # Services pages
│       ├── index.astro # Services overview
│       └── [slug].astro  # Dynamic service detail pages
├── lib/                # Utility functions and API clients
│   ├── wordpress-client.ts  # WordPress API client with caching
│   └── utils.ts        # Helper functions
├── schemas/            # Zod validation schemas
│   └── wordpress.ts    # WordPress API type definitions
├── styles/             # Global styles and Tailwind config
│   └── global.css      # Base styles and custom components
└── types/              # TypeScript type definitions
```

## 🎨 Design System

### Color Palette

```css
Primary (Trust & Professionalism):
- primary-50: #eff6ff   # Light backgrounds
- primary-500: #3b82f6  # Main brand color
- primary-600: #2563eb  # Primary buttons
- primary-900: #1e3a8a  # Dark text

Secondary (Premium Quality):
- secondary-400: #facc15  # Gold accents
- secondary-500: #eab308  # Secondary buttons

Neutral (Content):
- neutral-50: #fafafa    # Page backgrounds
- neutral-600: #525252   # Body text
- neutral-900: #171717   # Headers
```

### Typography

- **Headers**: Playfair Display (serif) - Professional, elegant
- **Body**: Inter (sans-serif) - High readability, modern
- **Responsive scaling**: Mobile-first approach with fluid typography

### Component Library

- **Buttons**: Primary, secondary, outline, and ghost variants
- **Cards**: Hover effects and consistent spacing
- **Forms**: Validation states and accessibility features
- **Sections**: Consistent padding and responsive spacing

## 🔌 WordPress Integration

### Content Structure

The WordPress backend uses custom post types for flexible content management:

#### Custom Post Types

1. **Servicios** (Services)
   - Pool Remodeling
   - Concrete & Flooring
   - Residential Cleaning
   - Technical Support & Plans

2. **Proyectos** (Projects)
   - Project galleries
   - Before/after images
   - Location and duration info
   - Category classification

3. **Testimonios** (Testimonials)
   - Client information
   - Star ratings
   - Service categorization
   - Client photos

### API Endpoints

- **Standard REST API**: `/wp-json/wp/v2/`
- **Custom Endpoints**: `/wp-json/renovalink/v1/`
  - Hero content
  - Company information
  - Optimized service data

### Data Validation

All WordPress API responses are validated using Zod schemas to ensure type safety and handle API changes gracefully.

## 🚀 Performance Optimizations

### Core Web Vitals Targets

- **LCP (Largest Contentful Paint)**: < 2.5s
- **FID (First Input Delay)**: < 100ms
- **CLS (Cumulative Layout Shift)**: < 0.1

### Optimization Strategies

1. **Image Optimization**
   - WebP/AVIF formats with fallbacks
   - Responsive images with proper sizing
   - Lazy loading for below-the-fold content
   - WordPress Media Library integration

2. **JavaScript Optimization**
   - Islands architecture for selective hydration
   - Code splitting and dynamic imports
   - Critical path prioritization

3. **CSS Optimization**
   - Critical CSS inlining
   - Tailwind purging for minimal bundle size
   - Component-scoped styles

4. **Caching Strategy**
   - Node-cache for API responses
   - Static generation for unchanging content
   - Edge caching compatibility

## 🛠️ Development Setup

### Prerequisites

- Node.js 18.17.1+ or 20.3.0+
- npm 9.6.5+
- WordPress Local development environment

### Installation

1. **Clone the repository**

   ```bash
   git clone [repository-url]
   cd landing-astro
   ```

2. **Install dependencies**

   ```bash
   npm install
   ```

3. **Set up environment variables**

   ```bash
   cp .env.example .env.local
   ```

   Configure your WordPress API endpoints:

   ```env
   WORDPRESS_API_URL=http://renovalink.local/wp-json
   WORDPRESS_CUSTOM_API_URL=http://renovalink.local/wp-json/renovalink/v1
   WP_MEDIA_URL=http://renovalink.local/wp-content/uploads
   API_CACHE_TTL=300
   ```

4. **Start development server**
   ```bash
   npm run dev
   ```

### WordPress Local Setup (Versión Gratuita)

**IMPORTANTE**: Este proyecto está configurado para usar únicamente plugins gratuitos de WordPress.

Ver `LOCAL-WORDPRESS-SETUP.md` para instrucciones detalladas que incluyen:

- ✅ Configuración de Local by Flywheel
- ✅ Advanced Custom Fields (versión gratuita únicamente)
- ✅ Custom Post Types con Custom Post Type UI (gratuito)
- ✅ Configuración de campos ACF compatibles con versión gratuita
- ✅ Workarounds para limitaciones de la versión gratuita
- ✅ Ejemplos de contenido y estructura de datos
- ✅ Variables de entorno para desarrollo local

**Limitaciones importantes**:

- ❌ Sin campos repetidores (Repeater Fields)
- ❌ Sin galerías nativas ACF
- ❌ Sin campos flexibles (Flexible Content)
- ✅ Soluciones implementadas para todas las limitaciones

## 📊 SEO & Marketing Features

### Search Engine Optimization

- **Structured Data**: JSON-LD for business, services, and reviews
- **Meta Tags**: Dynamic generation based on content
- **Sitemaps**: Automatic generation with proper prioritization
- **Canonical URLs**: Prevent duplicate content issues
- **Open Graph**: Social media sharing optimization

### Conversion Optimization

- **Lead Capture**: Multiple contact forms strategically placed
- **Trust Signals**: Certifications, testimonials, and guarantees
- **Call-to-Actions**: Clear, compelling CTAs throughout the site
- **Local SEO**: Florida-specific optimization
- **Mobile-First**: Optimized for mobile user experience

### Analytics Ready

- **Performance Monitoring**: Core Web Vitals tracking
- **Conversion Tracking**: Form submissions and phone calls
- **Error Tracking**: Client-side error monitoring
- **A/B Testing**: Component-level testing capability

## � Detailed SEO Implementation (Meta, Schemas, Sitemap, Robots)

This section documents the full SEO architecture implemented in the project—beyond the high‑level bullet list above—so future contributors can extend it safely without regressions.

### Objectives

- Clean, deterministic meta generation (title, description, canonical, OG/Twitter)
- Rich, multi‑schema JSON‑LD coverage for entity disambiguation
- Zero runtime server dependency (works in pure static export)
- Easy opt‑in for new page types (services, FAQs, future blog)
- Guardrails against duplicate content (canonical + selective `noindex`)

### Components & Key Files

| Concern                                                                                | Location                                          | Responsibility                                                   |
| -------------------------------------------------------------------------------------- | ------------------------------------------------- | ---------------------------------------------------------------- |
| Meta + OG + Twitter + base JSON‑LD injection                                           | `src/components/SEOHead.astro`                    | Renders all meta tags + iterates provided schema objects         |
| Schema assembly helpers (title/description truncation, canonical builder, FAQ builder) | `src/lib/utils.ts` (and related helpers)          | Normalizes values passed into `SEOHead`                          |
| Dynamic sitemap                                                                        | `src/pages/sitemap.xml.ts`                        | Enumerates static + dynamic routes (services)                    |
| robots.txt                                                                             | `public/robots.txt` (or Astro route if converted) | Crawl directives + sitemap reference                             |
| Media normalization                                                                    | `src/lib/media-url.ts`                            | Ensures social images resolve in all environments                |
| Lightbox / Gallery                                                                     | `src/components/Lightbox.astro`                   | Provides UX for images (not SEO directly but feeds OG if reused) |

### Meta Tag Strategy

1. Title Pattern: `Specific Page Title | RenovaLink` (auto‑appended if site name missing)
2. Description: Truncated (helper) to ~155–160 chars (Google snippet safe zone) without cutting words mid‑token.
3. Canonical: Built from `Astro.site + Astro.url.pathname` unless explicitly overridden (e.g., aliases, UTM stripping future enhancement).
4. Open Graph & Twitter: Always present; default OG image fallback lives at `/images/renovalink-og-default.jpg`.
5. `noindex` Support: Pass `noindex={true}` to `SEOHead` for temporary / experimental pages (keeps them out of index while still testable).

### JSON‑LD Schema Catalog

We deliberately output ONE `<script type="application/ld+json">` PER schema object for clarity & easier debugging. (`SEOHead` accepts either `schema` or `schemas`.)

Implemented Types:

- Organization: Brand identity (name, logo, URL, contact) – usually injected on homepage and high‑authority pages.
- WebSite + SearchAction: Enables potential sitelinks search box.
- Service: For each service detail page (`/servicios/[slug]`) describing `@type: Service` with `areaServed`, `provider`, and `offers` fields (where applicable).
- FAQPage: Injected only when real FAQs exist (service detail pages with Q&A content).
- BreadcrumbList: Future‑friendly (can be added when multi‑level navigation grows; placeholder support available if needed).

Best Practices Followed:

- Avoid duplicate `@type` objects with conflicting `@id`.
- Use stable URLs (canonical) for `url` fields.
- Keep descriptions under ~250 chars to avoid unnecessary truncation by Google.
- Validate in Search Console → URL Inspection → Test Live URL (see QA Checklist).

### Adding a New Schema

1. Build a plain JS object: `{ '@context': 'https://schema.org', '@type': 'Thing', ... }`.
2. Supply via the `schemas` prop on `Layout` / page: `<SEOHead schemas={[existingSchemas..., newObject]} />`.
3. Keep arrays flattened; let `SEOHead` render sequential scripts.
4. Test locally: View Source → search for `data-schema-index` attributes.
5. Validate with Rich Results Test.

### Sitemap Generation (`/sitemap.xml`)

The route file (Astro endpoint) collects:

1. Core static pages (`/`, `/about`, `/contacto`, `/servicios`).
2. Dynamic service detail pages (fetched at build from WordPress via client helper).
3. (Future) Optionally map projects or blog posts.

Each entry includes: `<loc>`, `<lastmod>` (if available), and may include static `<changefreq>` / `<priority>` if later desired (currently minimalistic).

Extending:

- Add a function in the endpoint to fetch new collection types (e.g., blog posts) and push their URLs into the list.
- Rebuild; confirm the new paths appear.

### Robots Policy (`/robots.txt`)

Baseline goals:

- Allow full crawl of public marketing pages.
- Disallow obvious non‑content endpoints (e.g., internal API preview routes if any emerge).
- Reference the sitemap: `Sitemap: https://renovalink.com/sitemap.xml`.

If a temporary staging environment is deployed publicly, consider adding a `Disallow: /` with `X-Robots-Tag: noindex` at the platform level (Netlify header) instead of editing production robots.txt.

### Image & Social Preview Handling

- OG/Twitter image selected in priority: explicit prop → media object URL → default fallback.
- All URLs passed through media normalization so dev vs prod domain differences do not break preview tests.
- Recommended OG image dimensions: 1200×630 (place variants in `/public/images/`).

### Edge Cases & Safeguards

| Scenario                      | Handling                                        |
| ----------------------------- | ----------------------------------------------- |
| Missing description           | Fallback default in `SEOHead` (project tagline) |
| Title already contains brand  | No duplicate brand suffix added                 |
| Non‑HTTP media (data:, blob:) | Left untouched by normalization                 |
| Missing schema prop(s)        | Auto‑inserts default WebPage schema             |
| Experimental page             | Use `noindex` prop to suppress indexing         |

### QA / Validation Checklist

Run this before major releases:

1. View Source: Confirm one `<title>` and a single canonical `<link>`.
2. Schema Count: Count `<script type="application/ld+json" data-schema-index>` elements → matches expected (e.g., Home: Organization + WebSite + default WebPage).
3. Rich Results Test: Paste live URL → no critical errors (warnings acceptable if optional fields omitted).
4. Mobile Friendly Test (optional) → pass.
5. `curl -I https://site/page` → verify `200` and no unexpected redirects.
6. Check `robots.txt` includes correct sitemap URL and no accidental `Disallow: /`.
7. Validate sitemap in browser → ensure dynamic service URLs present.
8. Lighthouse (SEO category) → aim for 100 (or high 90s) consistently.

### Extending to Blog (Future Example)

When adding a blog:

- Add Post schema: `Article` with `headline`, `datePublished`, `dateModified`, `author`, `image`.
- Include each post in sitemap with accurate `lastmod`.
- Generate category listing pages (optional) and include if they add unique value.

### Troubleshooting

| Symptom                            | Likely Cause                                  | Fix                                                                          |
| ---------------------------------- | --------------------------------------------- | ---------------------------------------------------------------------------- |
| Duplicate canonical URLs in source | Manual `<link rel="canonical">` added in page | Remove manual tag; rely on `SEOHead`                                         |
| Missing structured data scripts    | `schemas` prop not passed / array empty       | Pass schemas or rely on default; verify array non‑empty                      |
| Rich Results warning about logo    | Logo URL inaccessible                         | Ensure `/public/images/logo.*` exists & is referenced in Organization schema |
| Search Console reports soft 404    | Thin content / placeholder page               | Add real content or temporarily `noindex`                                    |

### Quick Reference Snippet

```astro
<SEOHead
   title="Pool Renovation Services"
   description={truncateDescription(service.description)}
   canonical={`https://renovalink.com/servicios/${service.slug}/`}
   type="service"
   schemas={[
      organizationSchema,
      buildServiceSchema(service),
      buildFaqSchema(service.faqs)
   ]}
   image={service.heroImage}
/>
```

> Keep snippets minimal—heavy logic belongs in helpers, not in page components.

---

## �🧪 Testing & Quality Assurance

### Performance Testing

```bash
npm run build
npm run preview
```

Use tools like:

- Lighthouse for Core Web Vitals
- WebPageTest for detailed analysis
- GTmetrix for performance insights

### Accessibility Testing

- WCAG 2.1 AA compliance
- Keyboard navigation testing
- Screen reader compatibility
- Color contrast validation

### Cross-Browser Testing

Tested on:

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Android Chrome)

## 🚀 Deployment

### Build Process

```bash
# Production build
npm run build

# Preview build locally
npm run preview
```

### Deployment Options

1. **Static Hosting** (Netlify, Vercel)
   - Automatic builds from Git
   - CDN distribution
   - SSL certificates

2. **Server Deployment** (Node.js)
   - Hybrid rendering support
   - Dynamic API integration
   - Custom server configuration

3. **CDN Integration**
   - CloudFlare for global performance
   - Image optimization
   - Edge caching

### Environment Configuration

Production environment variables:

```env
WORDPRESS_API_URL=https://admin.renovalink.com/wp-json
WORDPRESS_CUSTOM_API_URL=https://admin.renovalink.com/wp-json/renovalink/v1
WP_MEDIA_URL=https://admin.renovalink.com/wp-content/uploads
NODE_ENV=production
```

### Static Deployment (Arquitectura Final)

La aplicación se exporta como HTML estático (sin SSR / sin Edge Functions) para máxima velocidad y evitar dependencias de runtime. Únicamente la función serverless de contacto (si se mantiene) vive aparte en Netlify Functions.

Pasos Netlify recomendados:

1. Configurar variables de entorno (WordPress + correo) en Site Settings.
2. Build command: `npm run build`
3. Publish directory: `dist` (tras Astro build).
4. (Opcional) Function contact: carpeta `netlify/functions` si existe.
5. Activar asset compression automática (Netlify lo hace por defecto) y HTTP/2.

Scripts útiles en `package.json` (añadir si faltan):

```jsonc
{
  "scripts": {
    "build": "astro build",
    "preview": "astro preview",
    "verify:images": "node scripts/verify-images.mjs",
  },
}
```

Checklist previo a deploy:

- [ ] `npm ci && npm run build` sin errores
- [ ] `npm run verify:images` retorna 0 imágenes rotas
- [ ] No quedan referencias a dominio local en `dist` (ver comando en sección medios)
- [ ] `.env.production` no contiene credenciales reales (solo placeholders)
- [ ] Variables reales están en panel Netlify

Rollback rápido:

- Usar Deploys > Published deploys > seleccionar deploy anterior > Publish

### Contact Form Function (Resumen)

El formulario envía JSON a un endpoint serverless (Netlify Function) usando Nodemailer. Requiere:

- `GMAIL_USER`
- `GMAIL_APP_PASSWORD` (App Password de Gmail, no password normal)
- `COMPANY_EMAIL` (destino principal)

Logs de la función se revisan en Netlify > Functions > Logs. Para test local, puede usarse `netlify dev` si se reintroduce el adapter funcional para functions.

---

## 🛠️ Fix de Imágenes (Causa Raíz + Solución)

### Problema Original

- En Home (ProjectGallery) las imágenes de proyectos aparecían rotas en desarrollo.
- El HTML mostraba `src="/wp-content/uploads/..."` (ruta relativa) o bien URLs con dominio de producción inaccesible localmente.
- `naturalWidth` = 0 en inspecciones automatizadas.
- IDs de imágenes venían como strings ("123") y el enriquecimiento esperaba number, resultando en falta de datos (sin `sizes` / `source_url`).

### Causas Identificadas

1. Normalización manual previa que eliminaba el dominio (dejando rutas relativas) sin un proxy configurado.
2. Falta de soporte para IDs numéricos en formato string en el enriquecimiento de medios.
3. Dependencia en un único dominio (producción) durante dev.

### Solución Implementada

1. Creación de `normalizeMediaUrl` centralizando la lógica (estrategia `env-swap`).
2. Ajuste en `wordpress-client.ts` para tratar IDs string como numéricos válidos al enriquecer.
3. Uso de la base local (`PUBLIC_WP_LOCAL_MEDIA_BASE`) en dev y dominio público en build.
4. Integración de la normalización en `ProjectGallery.astro` y `Lightbox.astro`.
5. Verificación con script post-build para detectar referencias al dominio local residual.

### Resultado

- Imágenes cargan correctamente tanto en dev como en producción.
- Se evita duplicar lógica de reemplazo en componentes.
- Fácil cambio de estrategia a `passthrough` o `proxy` sin tocar el resto del código.

### Guía para Nuevos Campos de Imagen (ACF Free)

Si se añaden nuevos campos `project_image_7`, `service_banner_image`, etc.:

1. Asegurar que WordPress retorna el ID (numérico o string numérica).
2. Extender el loop de enriquecimiento en `wordpress-client.ts` si la estructura cambia.
3. Aplicar `normalizeMediaUrl` al renderizar.
4. Verificar en build que no quedan rutas relativas sin dominio.

### Troubleshooting Rápido

| Síntoma                                     | Posible Causa                      | Acción                                        |
| ------------------------------------------- | ---------------------------------- | --------------------------------------------- |
| Imagen rota solo en dev                     | Falta `PUBLIC_WP_LOCAL_MEDIA_BASE` | Añadir variable y reiniciar dev               |
| Imagen rota solo en prod                    | Cache vieja / deploy incompleto    | Redeploy + limpiar caché CDN                  |
| Todas las imágenes usan dominio prod en dev | Estrategia mal seteada             | Confirmar `PUBLIC_WP_MEDIA_STRATEGY=env-swap` |
| Algunas imágenes sin tamaños                | Enriquecimiento no encontró media  | Revisar ID en WP y endpoint REST              |

---

## 🔧 Customization Guide

### Adding New Services

1. Create service content in WordPress
2. Update service icons in `ServicesGrid.astro`
3. Add service-specific styling if needed
4. Update navigation menus

### Modifying Design

1. Update color scheme in `tailwind.config.mjs`
2. Modify typography in `global.css`
3. Adjust component spacing and sizing
4. Update brand assets

### Content Management

All content is managed through WordPress:

- **Pages**: Hero content, company information
- **Services**: Service descriptions and features
- **Projects**: Portfolio images and details
- **Testimonials**: Client feedback and ratings

## 📞 Support & Contact

For technical support or questions about this implementation:

- **Documentation**: Check `wordpress/SETUP-GUIDE.md` for WordPress setup
- **Issues**: Create GitHub issues for bugs or feature requests
- **Performance**: Use browser dev tools and Lighthouse for optimization

## 📈 Future Enhancements

Potential improvements and features:

- **Multi-language support** (Spanish for Florida market)
- **Advanced animations** with Framer Motion
- **Blog integration** for content marketing
- **Online booking system** for consultations
- **Customer portal** for project tracking
- **Integration with CRM** systems
- **Advanced analytics** and reporting

---

## 🏆 Project Goals Achieved

✅ **Professional Design**: Modern, trustworthy visual identity
✅ **High Performance**: Optimized Core Web Vitals scores
✅ **SEO Optimized**: Comprehensive search engine optimization
✅ **Mobile-First**: Responsive design for all devices
✅ **Type Safety**: Zod validation for reliable data handling
✅ **Scalable Architecture**: Easy to maintain and extend
✅ **Content Management**: User-friendly WordPress backend
✅ **Conversion Focused**: Strategic lead capture and CTAs

This implementation provides RenovaLink with a solid foundation for their digital presence in Florida's competitive remodeling market.

## 🖼️ Estrategia de Medios Multi‑Entorno (WordPress Images)

Para evitar problemas de rutas de imágenes (especialmente en desarrollo local donde el dominio público de WordPress no resuelve) se implementó una normalización centralizada de URLs de medios.

### Objetivo

Usar automáticamente el dominio local de WordPress en `npm run dev` y el dominio público en el build de producción, sin tener que reescribir manualmente rutas en cada componente ni cometer URLs absolutas incorrectas.

### Archivo Clave

`src/lib/media-url.ts` expone:

- `normalizeMediaUrl(raw: string | null)`
- `normalizeMediaObject(obj)`

Estos helpers se aplican en componentes que renderizan imágenes de proyectos / servicios como:

- `ProjectGallery.astro`
- `Lightbox.astro`

### Variables de Entorno

```env
PUBLIC_WP_LOCAL_MEDIA_BASE=http://renovalinksite.local
PUBLIC_WP_PROD_MEDIA_BASE=https://admin.renovalink.com
PUBLIC_WP_MEDIA_STRATEGY=env-swap
```

Colócalas en:

- `.env.local` (para desarrollo – gitignored)
- Panel de tu hosting (Netlify / Vercel) para producción

### Estrategias Disponibles (`PUBLIC_WP_MEDIA_STRATEGY`)

1. `env-swap` (por defecto)
   - Dev: fuerza dominio LOCAL cuando detecta URLs con dominio PROD o rutas relativas `/wp-content/...`
   - Prod: fuerza dominio PROD cuando detecta URLs con dominio LOCAL o rutas relativas
   - Conserva intactos `data:` / `blob:` y otros dominios externos.

2. `passthrough`
   - No altera la URL. Útil si tu entorno local puede acceder sin problema al mismo dominio público (por ejemplo túnel o staging en línea).

3. `proxy` (no usado actualmente)
   - Reescribe cualquier media a la forma `/media/wp-content/...` asumiendo que configuras reglas de reescritura (ej. en `netlify.toml`). Se dejó soportado para un futuro escenario de CDN / caching intermedio.

### Reglas de Normalización

- Si la URL es relativa (`/wp-content/uploads/...`), se le antepone la base adecuada según entorno y estrategia.
- Si es absoluta y la estrategia es `env-swap`, se sustituye el dominio (local ⇄ prod) según `import.meta.env.DEV`.
- `data:` y `blob:` se devuelven intactas.
- En `passthrough` nunca se modifica.
- En `proxy` se captura el sufijo `/wp-content/...` y se genera `/media/wp-content/...`.

### Ejemplos

| Entorno | raw                                                              | Estrategia  | Resultado                                                         |
| ------- | ---------------------------------------------------------------- | ----------- | ----------------------------------------------------------------- |
| Dev     | `/wp-content/uploads/2025/02/img.jpg`                            | env-swap    | `http://renovalinksite.local/wp-content/uploads/2025/02/img.jpg`  |
| Prod    | `http://renovalinksite.local/wp-content/uploads/2025/02/img.jpg` | env-swap    | `https://admin.renovalink.com/wp-content/uploads/2025/02/img.jpg` |
| Dev     | `https://admin.renovalink.com/wp-content/uploads/..`             | env-swap    | `http://renovalinksite.local/wp-content/uploads/..`               |
| Prod    | `https://admin.renovalink.com/wp-content/uploads/...`            | passthrough | (sin cambios)                                                     |
| Prod    | `https://admin.renovalink.com/wp-content/uploads/...`            | proxy       | `/media/wp-content/uploads/...`                                   |

### Detección de Problemas Típicos

Problema: Imágenes de proyectos no cargan en `npm run dev` (naturalWidth 0) y el `src` muestra dominio de producción.

Solución: Asegúrate de tener `PUBLIC_WP_MEDIA_STRATEGY=env-swap` y `PUBLIC_WP_LOCAL_MEDIA_BASE` apuntando a tu instalación local (ej. `http://renovalinksite.local`). Reinicia el dev server tras ajustar `.env.local`.

### Verificación Post-Build

Tras `npm run build` puedes verificar que no queden rutas al dominio local dentro de `dist/` (excepto si intencionalmente usas `passthrough`). En PowerShell:

```powershell
Select-String -Path dist\**\*.html -Pattern "renovalinksite.local" | Measure-Object
```

Si el conteo es > 0 revisa esas referencias.

### Extensión Futura

Se podría añadir:

- Firma / cache-busting de imágenes procesadas
- CDN directo (Cloudflare R2 / Image Resizing)
- Modo `proxy` activo con reglas Netlify para uniformar dominios y agregar headers de caché

---

## 🔐 Environment Variables & Deployment Security

Sensitive values (API keys, email credentials) must NOT vivir en el repositorio.

Mover a variables de entorno en Netlify UI:

Requeridas en producción:

- WORDPRESS_API_URL
- WORDPRESS_CUSTOM_API_URL
- WP_MEDIA_URL
- API_CACHE_TTL (opcional, default 300)
- GMAIL_USER (solo para función contacto)
- GMAIL_APP_PASSWORD (App Password Gmail - generar y rotar si se filtró antes)
- COMPANY_EMAIL

Pasos para configurarlas en Netlify:

1. Netlify Dashboard > Site > Site configuration > Environment variables.
2. Añadir cada nombre exactamente como arriba y su valor.
3. Redeploy del sitio (trigger manual o commit vacío).

Buenas prácticas:

- Si cambias la contraseña de la cuenta Gmail, genera nuevo App Password y actualiza variable.
- Nunca echo / console.log valores de estas variables en código cliente.
- Revisa commits antiguos para asegurarte de que las credenciales expuestas se rotaron.
