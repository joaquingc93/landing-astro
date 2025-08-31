<?php
/**
 * RenovaLink Headless Theme - Index Template
 * This theme serves content via REST API for Astro.js frontend
 */

get_header(); ?>

<div class="headless-notice" style="max-width: 800px; margin: 50px auto; padding: 40px; background: #f9f9f9; border-radius: 8px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <h1 style="color: #2271b1; margin-bottom: 20px;">🚀 RenovaLink Headless WordPress</h1>
    
    <p style="font-size: 18px; color: #50575e; margin-bottom: 30px;">
        This WordPress installation serves as a headless CMS for the RenovaLink website. 
        Content is delivered via REST API to the Astro.js frontend.
    </p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 30px 0;">
        <div style="background: white; padding: 20px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="color: #2271b1; margin: 0 0 10px;">📍 API Endpoints</h3>
            <p style="font-size: 14px; color: #50575e;">Access content via:<br><code>/wp-json/renovalink/v1/</code></p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="color: #2271b1; margin: 0 0 10px;">⚙️ Admin Dashboard</h3>
            <p style="font-size: 14px; color: #50575e;">Manage content at:<br><a href="<?php echo admin_url(); ?>">WordPress Admin</a></p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="color: #2271b1; margin: 0 0 10px;">🎨 Frontend</h3>
            <p style="font-size: 14px; color: #50575e;">Website powered by:<br>Astro.js + WordPress</p>
        </div>
    </div>
    
    <div style="margin-top: 40px; padding: 20px; background: #e7f3ff; border-radius: 6px;">
        <h3 style="color: #2271b1; margin: 0 0 15px;">Available API Endpoints:</h3>
        <ul style="text-align: left; display: inline-block; color: #50575e;">
            <li><code>/wp-json/renovalink/v1/hero</code> - Hero section content</li>
            <li><code>/wp-json/renovalink/v1/services-complete</code> - Services with projects</li>
            <li><code>/wp-json/renovalink/v1/projects/[service]</code> - Projects by service</li>
            <li><code>/wp-json/renovalink/v1/testimonials/random</code> - Random testimonials</li>
            <li><code>/wp-json/renovalink/v1/options</code> - Site options & settings</li>
            <li><code>/wp-json/renovalink/v1/stats</code> - Site statistics</li>
            <li><code>/wp-json/renovalink/v1/featured</code> - Featured content</li>
            <li><code>/wp-json/renovalink/v1/search</code> - Content search</li>
            <li><code>/wp-json/renovalink/v1/service-areas</code> - Service areas</li>
        </ul>
    </div>
</div>

<?php get_footer(); ?>