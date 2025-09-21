=== RenovaLink Custom Endpoints ===
Contributors: renovalinkteam
Tags: rest-api, endpoints, headless, astro
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Custom REST API endpoints for RenovaLink website integration with Astro.js frontend.

== Description ==

This plugin provides custom REST API endpoints for the RenovaLink website to integrate with an Astro.js frontend. It includes endpoints for company information, debug data, and testing connectivity.

= Features =

* Company information endpoint with ACF integration
* Test endpoint for connectivity verification
* Debug endpoint for troubleshooting
* Automatic permalink flushing on activation
* Compatible with Advanced Custom Fields (ACF)

= Endpoints =

* `/wp-json/renovalink/v1/test` - Test connectivity
* `/wp-json/renovalink/v1/company-info` - Get company information
* `/wp-json/renovalink/v1/debug` - Debug information

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/renovalink-endpoints` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. The endpoints will be immediately available

== Frequently Asked Questions ==

= Do I need Advanced Custom Fields? =

The plugin works without ACF, but it's recommended for full functionality. With ACF, you can create custom fields for company information.

= Which ACF fields are supported? =

* company_logo (Image field)
* company_description (Textarea field)
* emergency_phone (Text field)

== Changelog ==

= 1.0.0 =
* Initial release
* Company info endpoint
* Test and debug endpoints
* ACF integration