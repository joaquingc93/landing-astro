# RenovaLink Deployment Guide

This guide covers deployment strategies, optimization techniques, and production considerations for the RenovaLink landing page.

## 🚀 Deployment Options

### 1. Static Hosting (Recommended for High Performance)

#### Netlify Deployment
```bash
# Build and deploy to Netlify
npm run build
npx netlify-cli deploy --prod --dir dist
```

**Netlify Configuration (netlify.toml):**
```toml
[build]
  publish = "dist"
  command = "npm run build"

[build.environment]
  NODE_VERSION = "18.17.1"

[[headers]]
  for = "/*"
  [headers.values]
    X-Frame-Options = "DENY"
    X-Content-Type-Options = "nosniff"
    X-XSS-Protection = "1; mode=block"
    Referrer-Policy = "strict-origin-when-cross-origin"

[[headers]]
  for = "/assets/*"
  [headers.values]
    Cache-Control = "public, max-age=31536000, immutable"

[[redirects]]
  from = "/contact"
  to = "/contacto"
  status = 301
```

#### Vercel Deployment
```bash
# Install Vercel CLI
npm i -g vercel

# Deploy
vercel --prod
```

**Vercel Configuration (vercel.json):**
```json
{
  "buildCommand": "npm run build",
  "outputDirectory": "dist",
  "framework": "astro",
  "regions": ["iad1", "sfo1"],
  "headers": [
    {
      "source": "/assets/(.*)",
      "headers": [
        {
          "key": "Cache-Control",
          "value": "public, max-age=31536000, immutable"
        }
      ]
    }
  ]
}
```

### 2. Node.js Server Deployment (For Hybrid Rendering)

#### DigitalOcean/AWS/GCP
```bash
# Build for production
npm run build

# Install PM2 for process management
npm install -g pm2

# Create ecosystem file
```

**PM2 Configuration (ecosystem.config.js):**
```javascript
module.exports = {
  apps: [{
    name: 'renovalink-landing',
    script: './dist/server/entry.mjs',
    instances: 'max',
    exec_mode: 'cluster',
    env: {
      NODE_ENV: 'production',
      PORT: 3000,
      WORDPRESS_API_URL: 'https://admin.renovalink.com/wp-json',
      WORDPRESS_CUSTOM_API_URL: 'https://admin.renovalink.com/wp-json/renovalink/v1'
    }
  }]
}
```

## 📊 Performance Optimization

### Core Web Vitals Targets
- **LCP**: < 1.5s (Excellent)
- **FID**: < 100ms 
- **CLS**: < 0.1

### Optimization Checklist

#### ✅ Images
- [x] WebP/AVIF formats with fallbacks
- [x] Responsive images with srcset
- [x] Lazy loading for below-the-fold content
- [x] Proper aspect ratios to prevent CLS
- [x] WordPress media integration

#### ✅ JavaScript
- [x] Islands architecture for selective hydration
- [x] Code splitting and dynamic imports
- [x] Critical path prioritization
- [x] Minimal JavaScript bundle size

#### ✅ CSS
- [x] Critical CSS inlining
- [x] Tailwind purging
- [x] Component-scoped styles
- [x] Minimal external dependencies

#### ✅ Fonts
- [x] Font preloading
- [x] Font-display: swap
- [x] Self-hosted fonts
- [x] Variable font usage

### CDN Configuration

#### CloudFlare Setup
1. **DNS Configuration**
   - Point domain to hosting provider
   - Enable CloudFlare proxy
   - Configure SSL/TLS (Full Strict)

2. **Page Rules**
   ```
   *.renovalink.com/assets/* 
   Cache Level: Cache Everything, Edge Cache TTL: 1 month

   *.renovalink.com/api/*
   Cache Level: Bypass Cache

   *.renovalink.com/*
   Cache Level: Standard, Browser Cache TTL: 4 hours
   ```

3. **Performance Settings**
   - Enable Brotli compression
   - Enable HTTP/2 and HTTP/3
   - Enable Auto Minify (CSS, JS, HTML)
   - Enable Rocket Loader (test first)

#### AWS CloudFront
```yaml
# CloudFormation template excerpt
CloudFrontDistribution:
  Type: AWS::CloudFront::Distribution
  Properties:
    DistributionConfig:
      Origins:
        - DomainName: !GetAtt S3Bucket.DomainName
          Id: S3Origin
          S3OriginConfig:
            OriginAccessIdentity: !Sub "origin-access-identity/cloudfront/${OriginAccessIdentity}"
      DefaultCacheBehavior:
        TargetOriginId: S3Origin
        ViewerProtocolPolicy: redirect-to-https
        Compress: true
        CachePolicyId: "4135ea2d-6df8-44a3-9df3-4b5a84be39ad" # Managed-CachingOptimized
      PriceClass: PriceClass_100
      ViewerCertificate:
        AcmCertificateArn: !Ref SSLCertificate
        SslSupportMethod: sni-only
```

## 🔐 Security Configuration

### Security Headers
```javascript
// In Astro config or server setup
const securityHeaders = {
  'X-DNS-Prefetch-Control': 'on',
  'Strict-Transport-Security': 'max-age=63072000; includeSubDomains; preload',
  'X-Frame-Options': 'DENY',
  'X-Content-Type-Options': 'nosniff',
  'X-XSS-Protection': '1; mode=block',
  'Referrer-Policy': 'strict-origin-when-cross-origin',
  'Content-Security-Policy': `
    default-src 'self';
    script-src 'self' 'unsafe-inline' https://www.googletagmanager.com;
    style-src 'self' 'unsafe-inline' https://fonts.googleapis.com;
    img-src 'self' data: https: blob:;
    font-src 'self' https://fonts.gstatic.com;
    connect-src 'self' https://admin.renovalink.com;
    frame-ancestors 'none';
  `.replace(/\s+/g, ' ').trim()
};
```

### Environment Variables Security
```bash
# Production environment variables
NODE_ENV=production
WORDPRESS_API_URL=https://admin.renovalink.com/wp-json
WORDPRESS_CUSTOM_API_URL=https://admin.renovalink.com/wp-json/renovalink/v1
API_CACHE_TTL=300

# Optional: API authentication
WP_APPLICATION_PASSWORD=your_secure_app_password
```

## 📈 Monitoring & Analytics

### Performance Monitoring
```javascript
// In Layout.astro or main component
if ('PerformanceObserver' in window) {
  // Core Web Vitals monitoring
  new PerformanceObserver((list) => {
    for (const entry of list.getEntries()) {
      if (entry.entryType === 'largest-contentful-paint') {
        // Send LCP to analytics
        gtag('event', 'web_vitals', {
          name: 'LCP',
          value: entry.startTime,
          event_category: 'performance'
        });
      }
    }
  }).observe({entryTypes: ['largest-contentful-paint']});
}
```

### Error Tracking
```javascript
// Client-side error monitoring
window.addEventListener('error', (event) => {
  gtag('event', 'exception', {
    description: event.error.message,
    fatal: false
  });
});

window.addEventListener('unhandledrejection', (event) => {
  gtag('event', 'exception', {
    description: event.reason,
    fatal: false
  });
});
```

### Google Analytics 4 Setup
```html
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'GA_MEASUREMENT_ID', {
    page_title: document.title,
    page_location: window.location.href
  });
  
  // Enhanced ecommerce for lead tracking
  gtag('config', 'AW-CONVERSION_ID');
</script>
```

## 🧪 Testing & Validation

### Pre-Deployment Checklist

#### Performance Testing
```bash
# Build and test locally
npm run build
npm run preview

# Lighthouse CI
npm install -g @lhci/cli
lhci autorun --upload.target=temporary-public-storage

# PageSpeed Insights
npx psi https://renovalink.com --strategy=mobile
npx psi https://renovalink.com --strategy=desktop
```

#### Functionality Testing
- [ ] All forms submit successfully
- [ ] Contact information displays correctly
- [ ] Service pages load dynamically
- [ ] WordPress API endpoints respond
- [ ] Images load and display properly
- [ ] Mobile navigation works
- [ ] Search functionality (if implemented)

#### SEO Validation
```bash
# Check structured data
curl -s "https://renovalink.com" | grep -o '<script type="application/ld+json">[^<]*</script>'

# Validate sitemap
curl -s "https://renovalink.com/sitemap-index.xml" | xmllint --format -

# Check meta tags
curl -s "https://renovalink.com" | grep -E '<meta|<title|<link.*canonical'
```

#### Accessibility Testing
- [ ] WCAG 2.1 AA compliance
- [ ] Keyboard navigation
- [ ] Screen reader compatibility
- [ ] Color contrast ratios
- [ ] Alt text for images

### Cross-Browser Testing
- [ ] Chrome 90+ (Desktop/Mobile)
- [ ] Firefox 88+ (Desktop/Mobile)
- [ ] Safari 14+ (Desktop/Mobile)
- [ ] Edge 90+
- [ ] Samsung Internet (Android)

## 🔄 Continuous Integration

### GitHub Actions Workflow
```yaml
# .github/workflows/deploy.yml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  build-and-deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18.17.1'
          cache: 'npm'
      
      - name: Install dependencies
        run: npm ci
      
      - name: Build project
        run: npm run build
        env:
          WORDPRESS_API_URL: ${{ secrets.WORDPRESS_API_URL }}
          WORDPRESS_CUSTOM_API_URL: ${{ secrets.WORDPRESS_CUSTOM_API_URL }}
      
      - name: Deploy to Netlify
        uses: nwtgck/actions-netlify@v2.0
        with:
          publish-dir: './dist'
          production-branch: main
          github-token: ${{ secrets.GITHUB_TOKEN }}
          deploy-message: "Deploy from GitHub Actions"
        env:
          NETLIFY_AUTH_TOKEN: ${{ secrets.NETLIFY_AUTH_TOKEN }}
          NETLIFY_SITE_ID: ${{ secrets.NETLIFY_SITE_ID }}
```

## 📱 Mobile Optimization

### Progressive Web App Features
```javascript
// In public/manifest.json
{
  "name": "RenovaLink - Florida Remodeling Services",
  "short_name": "RenovaLink",
  "theme_color": "#2563eb",
  "background_color": "#ffffff",
  "display": "minimal-ui",
  "scope": "/",
  "start_url": "/",
  "icons": [
    {
      "src": "/icons/icon-192.png",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "/icons/icon-512.png",
      "sizes": "512x512",
      "type": "image/png"
    }
  ]
}
```

### Service Worker (Optional)
```javascript
// In public/sw.js for offline functionality
const CACHE_NAME = 'renovalink-v1';
const urlsToCache = [
  '/',
  '/servicios',
  '/contacto',
  '/assets/main.css',
  '/assets/main.js'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(urlsToCache))
  );
});
```

## 🎯 Conversion Optimization

### A/B Testing Setup
```javascript
// Google Optimize or custom A/B testing
if (typeof gtag !== 'undefined') {
  gtag('config', 'AW-CONVERSION_ID', {
    optimize_id: 'OPT-XXXXXXX'
  });
}

// Track form submissions
document.addEventListener('submit', (e) => {
  if (e.target.matches('#contact-form')) {
    gtag('event', 'generate_lead', {
      currency: 'USD',
      value: 1000 // Estimated lead value
    });
  }
});
```

### Heat Mapping Integration
```html
<!-- Hotjar or similar heat mapping tool -->
<script>
  (function(h,o,t,j,a,r){
    h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
    h._hjSettings={hjid:YOUR_HOTJAR_ID,hjsv:6};
    a=o.getElementsByTagName('head')[0];
    r=o.createElement('script');r.async=1;
    r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
    a.appendChild(r);
  })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
```

## 🔧 Maintenance & Updates

### Automated Dependency Updates
```yaml
# dependabot.yml
version: 2
updates:
  - package-ecosystem: "npm"
    directory: "/"
    schedule:
      interval: "weekly"
    open-pull-requests-limit: 10
```

### Content Updates
- WordPress content can be updated independently
- Changes reflect immediately on the frontend
- No rebuild required for content changes
- Automatic cache invalidation

### Performance Monitoring
- Set up alerts for Core Web Vitals degradation
- Monitor API response times
- Track conversion rates and form submissions
- Regular Lighthouse audits

---

## 🎉 Deployment Success

After successful deployment, your RenovaLink landing page will feature:

✅ **Lightning-fast performance** with optimized Core Web Vitals
✅ **Professional design** that builds trust and credibility
✅ **Mobile-first experience** for Florida's mobile-heavy market
✅ **SEO optimization** for local search dominance
✅ **Conversion-focused** design with strategic CTAs
✅ **Easy content management** via WordPress admin
✅ **Scalable architecture** ready for business growth

The site is now ready to help RenovaLink dominate Florida's competitive remodeling market! 🏆