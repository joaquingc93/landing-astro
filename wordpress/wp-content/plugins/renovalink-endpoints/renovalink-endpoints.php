<?php
/**
 * Plugin Name: RenovaLink Custom Endpoints
 * Plugin URI: https://renovalink.com
 * Description: Custom REST API endpoints for RenovaLink website integration with Astro.js frontend
 * Version: 1.0.0
 * Author: RenovaLink Team
 * License: GPL v2 or later
 * Text Domain: renovalink-endpoints
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}

// Define plugin constants
define('RENOVALINK_ENDPOINTS_VERSION', '1.0.0');
define('RENOVALINK_ENDPOINTS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RENOVALINK_ENDPOINTS_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Plugin Class
 */
class RenovaLink_Endpoints {

    /**
     * Initialize the plugin
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('rest_api_init', array($this, 'register_endpoints'));

        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    /**
     * Initialize plugin
     */
    public function init() {
        // Load text domain for translations
        load_plugin_textdomain('renovalink-endpoints', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Flush rewrite rules to ensure endpoints work
        flush_rewrite_rules();

        // Add activation timestamp
        update_option('renovalink_endpoints_activated', current_time('mysql'));
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules on deactivation
        flush_rewrite_rules();
    }

    /**
     * Register REST API endpoints
     */
    public function register_endpoints() {
        // Test endpoint
        register_rest_route('renovalink/v1', '/test', array(
            'methods' => 'GET',
            'callback' => array($this, 'test_endpoint'),
            'permission_callback' => '__return_true',
            'args' => array()
        ));

        // Company info endpoint
        register_rest_route('renovalink/v1', '/company-info', array(
            'methods' => 'GET',
            'callback' => array($this, 'company_info_endpoint'),
            'permission_callback' => '__return_true',
            'args' => array()
        ));

        // Debug endpoint
        register_rest_route('renovalink/v1', '/debug', array(
            'methods' => 'GET',
            'callback' => array($this, 'debug_endpoint'),
            'permission_callback' => '__return_true',
            'args' => array()
        ));
    }

    /**
     * Test endpoint callback
     */
    public function test_endpoint($request) {
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'RenovaLink endpoints are working perfectly!',
            'plugin_version' => RENOVALINK_ENDPOINTS_VERSION,
            'timestamp' => current_time('mysql'),
            'wordpress_version' => get_bloginfo('version'),
            'php_version' => PHP_VERSION,
            'request_method' => $request->get_method(),
            'user_agent' => $request->get_header('user-agent')
        ));
    }

    /**
     * Debug endpoint callback
     */
    public function debug_endpoint($request) {
        return rest_ensure_response(array(
            'success' => true,
            'debug_info' => array(
                'plugin_active' => true,
                'plugin_version' => RENOVALINK_ENDPOINTS_VERSION,
                'wp_version' => get_bloginfo('version'),
                'php_version' => PHP_VERSION,
                'acf_active' => function_exists('get_field'),
                'current_theme' => get_stylesheet(),
                'available_pages' => $this->get_available_pages(),
                'server_time' => current_time('mysql'),
                'timezone' => get_option('timezone_string')
            )
        ));
    }

    /**
     * Company info endpoint callback
     */
    public function company_info_endpoint($request) {
        // Find the company information page
        $company_page = get_page_by_title('Información de la Empresa');

        if (!$company_page) {
            $company_page = get_page_by_title('Informacion de la Empresa');
        }

        if (!$company_page) {
            // Try by slug
            $company_page = get_page_by_path('informacion-de-la-empresa');
        }

        $response = array(
            'success' => true,
            'debug' => array(
                'page_found' => !!$company_page,
                'acf_active' => function_exists('get_field'),
                'current_theme' => get_stylesheet(),
                'plugin_version' => RENOVALINK_ENDPOINTS_VERSION,
                'available_pages' => $this->get_available_pages()
            )
        );

        if ($company_page) {
            $page_id = $company_page->ID;

            // Get ACF fields if available
            $company_logo = null;
            $company_description = null;
            $emergency_phone = null;

            if (function_exists('get_field')) {
                $company_logo = get_field('company_logo', $page_id);
                $company_description = get_field('company_description', $page_id);
                $emergency_phone = get_field('emergency_phone', $page_id);
            }

            // Prepare logo data
            $logo_data = null;
            if ($company_logo && is_array($company_logo)) {
                $logo_data = array(
                    'url' => isset($company_logo['url']) ? $company_logo['url'] : '',
                    'alt' => isset($company_logo['alt']) ? $company_logo['alt'] : '',
                    'width' => isset($company_logo['width']) ? $company_logo['width'] : null,
                    'height' => isset($company_logo['height']) ? $company_logo['height'] : null,
                    'sizes' => isset($company_logo['sizes']) ? $company_logo['sizes'] : null
                );
            }

            $response['data'] = array(
                'company_info' => array(
                    'name' => get_bloginfo('name'),
                    'description' => $company_description ?: get_bloginfo('description'),
                    'emergency_phone' => $emergency_phone ?: '+1(786)643-1254',
                    'regular_phone' => '+1(786)643-1254',
                    'email' => 'info@renovalink.com',
                    'logo' => $logo_data
                ),
                'credentials' => array(
                    'licensed' => true,
                    'insured' => true,
                    'certified_engineer' => true,
                    'years_experience' => 15,
                    'certifications' => array(
                        'Florida Professional Engineer',
                        'Pool & Spa Contractor License',
                        'General Contractor License',
                        'EPA Certified'
                    )
                ),
                'stats' => array(
                    'projects_completed' => 500,
                    'clients_satisfied' => 450,
                    'years_in_business' => 15,
                    'team_members' => 25
                ),
                'service_areas' => array(
                    'Miami-Dade',
                    'Broward',
                    'Palm Beach',
                    'Orange',
                    'Hillsborough'
                )
            );

            $response['debug']['page_details'] = array(
                'id' => $page_id,
                'title' => $company_page->post_title,
                'slug' => $company_page->post_name,
                'status' => $company_page->post_status
            );
        } else {
            $response['message'] = 'Company information page not found. Please create a page titled "Información de la Empresa"';
            $response['suggestions'] = array(
                'Create a page with title: "Información de la Empresa"',
                'Or create a page with title: "Informacion de la Empresa"',
                'Make sure the page is published',
                'Check available pages in the debug response'
            );
        }

        return rest_ensure_response($response);
    }

    /**
     * Get available pages for debugging
     */
    private function get_available_pages() {
        $pages = get_pages(array(
            'post_status' => array('publish', 'draft'),
            'number' => 20
        ));

        $page_list = array();
        foreach ($pages as $page) {
            $page_list[] = array(
                'id' => $page->ID,
                'title' => $page->post_title,
                'slug' => $page->post_name,
                'status' => $page->post_status
            );
        }

        return $page_list;
    }
}

// Initialize the plugin
new RenovaLink_Endpoints();

// Add admin notice for successful activation
add_action('admin_notices', function() {
    if (get_transient('renovalink_endpoints_activation_notice')) {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>RenovaLink Custom Endpoints</strong> plugin activated successfully! ';
        echo 'Test endpoint: <code>/wp-json/renovalink/v1/test</code></p>';
        echo '</div>';
        delete_transient('renovalink_endpoints_activation_notice');
    }
});

// Set activation notice
register_activation_hook(__FILE__, function() {
    set_transient('renovalink_endpoints_activation_notice', true, 30);
});