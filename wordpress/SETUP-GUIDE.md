# RenovaLink Headless WordPress Setup Guide

## Overview

This is a complete headless WordPress configuration for RenovaLink, a Florida remodeling company. The setup includes custom post types, Advanced Custom Fields, optimized REST API endpoints, and sample content ready for consumption by an Astro.js frontend.

## Prerequisites

### Server Requirements

- **PHP**: 8.1 or higher
- **MySQL**: 8.0 or higher (or MariaDB 10.3+)
- **WordPress**: Latest version (6.4+)
- **Memory**: 256MB minimum (512MB recommended)
- **Disk Space**: 1GB minimum

### Required Plugins

1. **Advanced Custom Fields Pro** (Essential for content management)
2. **Classic Editor** (Optional - for better content editing experience)

### Recommended Plugins

- **Yoast SEO** (for meta descriptions and SEO optimization)
- **W3 Total Cache** or **WP Rocket** (for performance caching)
- **Wordfence Security** (additional security layer)
- **UpdraftPlus** (automated backups)

## Installation Steps

### 1. WordPress Installation

```bash
# Download WordPress
wget https://wordpress.org/latest.tar.gz
tar -xzf latest.tar.gz

# Set up database
mysql -u root -p
CREATE DATABASE renovalink_wp;
CREATE USER 'renovalink_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON renovalink_wp.* TO 'renovalink_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2. WordPress Configuration

1. Copy `wp-config-sample.php` to `wp-config.php`
2. Update database credentials:
   ```php
   define('DB_NAME', 'renovalink_wp');
   define('DB_USER', 'renovalink_user');
   define('DB_PASSWORD', 'your_secure_password');
   define('DB_HOST', 'localhost');
   ```
3. Generate security keys at: https://api.wordpress.org/secret-key/1.1/salt/
4. Update the frontend URL:
   ```php
   define('FRONTEND_URL', 'https://your-astro-site.com');
   ```

### 3. Theme Installation

1. Extract the `renovalink-headless` theme to `/wp-content/themes/`
2. Activate the theme in WordPress Admin → Appearance → Themes

### 4. Plugin Installation

1. Install and activate **en el caso Pro**
2. The theme will automatically register all ACF field groups
3. Install other recommended plugins

### 5. Sample Content Creation

1. Go to WordPress Admin → RenovaLink → Sample Content
2. Click "Create Sample Content" to populate with demo data
3. This creates:
   - 4 Services (Pool, Concrete, Cleaning, Engineering)
   - 4 Sample Projects
   - 5 Customer Testimonials
   - Taxonomy terms and site settings

## Content Management

### Custom Post Types

#### 1. Servicios (Services)

- **Fields**: Service icon, short description, features list, gallery, CTA, pricing
- **API Endpoint**: `/wp-json/renovalink/v1/services-complete`

#### 2. Proyectos (Projects)

- **Fields**: Project type, location, before/after images, gallery, duration, testimonial
- **API Endpoint**: `/wp-json/renovalink/v1/projects/[service]`

#### 3. Testimonios (Testimonials)

- **Fields**: Client info, service type, rating, testimonial text, photos, featured flag
- **API Endpoint**: `/wp-json/renovalink/v1/testimonials/random`

### Site Settings

Access via **RenovaLink → RenovaLink Settings**:

- Hero section content
- Company information
- Contact details
- Service areas
- Social media links

### Advanced Fields

Access via **RenovaLink → Site Options** (ACF):

- Hero background images and overlays
- Certifications and badges
- Company logo and description
- Google Maps integration
- Insurance and license information

## API Endpoints

### Core Endpoints

```
GET /wp-json/renovalink/v1/hero
GET /wp-json/renovalink/v1/services-complete
GET /wp-json/renovalink/v1/projects/[service-type]
GET /wp-json/renovalink/v1/testimonials/random?limit=3
GET /wp-json/renovalink/v1/options
```

### Additional Endpoints

```
GET /wp-json/renovalink/v1/stats
GET /wp-json/renovalink/v1/featured
GET /wp-json/renovalink/v1/search?q=pool&type=proyectos
GET /wp-json/renovalink/v1/service-areas
POST /wp-json/renovalink/v1/contact
POST /wp-json/renovalink/v1/quote
```

### WordPress REST API

All standard WordPress endpoints are also available:

```
GET /wp-json/wp/v2/servicios
GET /wp-json/wp/v2/proyectos
GET /wp-json/wp/v2/testimonios
```

## Frontend Integration (Astro.js)

### Environment Variables

```bash
# .env
WORDPRESS_API_URL=https://your-wordpress-site.com/wp-json/renovalink/v1
```

### Sample API Usage

```javascript
// services.js
export async function getAllServices() {
  const response = await fetch(
    `${import.meta.env.WORDPRESS_API_URL}/services-complete`
  );
  return response.json();
}

export async function getHeroContent() {
  const response = await fetch(`${import.meta.env.WORDPRESS_API_URL}/hero`);
  return response.json();
}

export async function getRandomTestimonials(limit = 3) {
  const response = await fetch(
    `${import.meta.env.WORDPRESS_API_URL}/testimonials/random?limit=${limit}`
  );
  return response.json();
}
```

## Security Configuration

### Implemented Security Features

- ✅ Removed WordPress version exposure
- ✅ Disabled XML-RPC and pingbacks
- ✅ Hidden login error messages
- ✅ File editing disabled in admin
- ✅ Security headers (XSS, CSRF, etc.)
- ✅ Rate limiting for contact forms
- ✅ Blocked sensitive file access

### Additional Security Steps

1. **SSL Certificate**: Ensure HTTPS is enabled
2. **Strong Passwords**: Use complex passwords for all accounts
3. **Regular Updates**: Keep WordPress and plugins updated
4. **Firewall**: Consider Cloudflare or server-level firewall
5. **Backups**: Set up automated daily backups

## Performance Optimization

### Implemented Optimizations

- ✅ Removed unnecessary WordPress features (emojis, oEmbed, etc.)
- ✅ Optimized database queries with indexes
- ✅ API response caching (5 minutes for dynamic, 1 hour for static)
- ✅ Image optimization and lazy loading
- ✅ GZIP compression via .htaccess
- ✅ Browser caching headers

### Additional Performance Steps

1. **CDN**: Set up CloudFlare or AWS CloudFront
2. **Caching Plugin**: Install W3 Total Cache or WP Rocket
3. **Image Optimization**: Use Smush or similar plugin
4. **Database Optimization**: Regular cleanup of revisions and spam

## Development Workflow

### Local Development

1. Use Local by Flywheel, XAMPP, or Docker
2. Set `WP_DEBUG` to `true` in wp-config.php
3. Enable query logging for API optimization

### Staging Environment

1. Clone production data to staging
2. Test all API endpoints before deployment
3. Verify ACF field changes don't break frontend

### Production Deployment

1. Set `WP_DEBUG` to `false`
2. Enable caching and security plugins
3. Configure SSL and security headers
4. Set up monitoring and backups

## Troubleshooting

### Common Issues

#### ACF Fields Not Showing in API

- Ensure ACF Pro is installed and activated
- Check that field groups are assigned to correct post types
- Verify ACF REST API integration is enabled

#### CORS Errors

- Check .htaccess CORS headers
- Verify `FRONTEND_URL` in wp-config.php
- Ensure server supports mod_headers

#### Slow API Responses

- Enable WordPress object caching
- Check for slow database queries
- Verify image optimization is working
- Consider CDN implementation

#### Contact Form Not Working

- Check email configuration in wp-config.php
- Verify SMTP settings
- Test with a simple contact form plugin first

### Debug Mode

Enable debugging in wp-config.php:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Logs are saved to `/wp-content/debug.log`

## Maintenance

### Regular Tasks

- **Weekly**: Update plugins and themes
- **Monthly**: Optimize database and clean spam
- **Quarterly**: Review and update security settings
- **Yearly**: Review and update content structure

### Backup Strategy

1. **Files**: Daily backup of wp-content
2. **Database**: Daily backup with retention
3. **Full Site**: Weekly complete backup
4. **Test Restores**: Monthly restore testing

## Support Resources

### Documentation

- [WordPress REST API Handbook](https://developer.wordpress.org/rest-api/)
- [Advanced Custom Fields Documentation](https://www.advancedcustomfields.com/resources/)
- [Astro.js Documentation](https://docs.astro.build/)

### Community

- WordPress Support Forums
- ACF Support Forums
- Astro Discord Community

## License

This theme is released under the GPL v2 or later license.
