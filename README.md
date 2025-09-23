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

This project implements a modern headless CMS architecture using Astro.js as the frontend framework with WordPress as the content management system. The setup provides the performance benefits of static generation with the flexibility of dynamic content management.

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

## 🧪 Testing & Quality Assurance

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
