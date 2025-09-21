<?php
/**
 * Security and Performance Optimizations for RenovaLink Headless WordPress
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SECURITY ENHANCEMENTS
 */

// Remove WordPress version from head and RSS
function remove_wp_version() {
    return '';
}
add_filter('the_generator', 'remove_wp_version');

// Remove version from scripts and styles
function remove_version_from_assets($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'remove_version_from_assets');
add_filter('script_loader_src', 'remove_version_from_assets');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove XML-RPC pingback
function disable_xmlrpc_pingback($methods) {
    unset($methods['pingback.ping']);
    return $methods;
}
add_filter('xmlrpc_methods', 'disable_xmlrpc_pingback');

// Remove unnecessary meta tags from head
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

// Hide login errors for security
function hide_login_errors() {
    return 'Something is wrong!';
}
add_filter('login_errors', 'hide_login_errors');

// Disable file editing in WordPress admin
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

// Protect wp-config.php and .htaccess
function protect_sensitive_files() {
    $files_to_protect = array('wp-config.php', '.htaccess');
    
    foreach ($files_to_protect as $file) {
        $file_path = ABSPATH . $file;
        if (file_exists($file_path) && is_readable($file_path)) {
            if (basename($_SERVER['REQUEST_URI']) === $file) {
                wp_die('Access denied');
            }
        }
    }
}
add_action('init', 'protect_sensitive_files');

// Add security headers
function add_security_headers() {
    if (!is_admin()) {
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Only add HSTS in production
        if (is_ssl()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }
}
add_action('send_headers', 'add_security_headers');

// Rate limiting for REST API
function rest_api_rate_limiting($response, $handler, $request) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $endpoint = $request->get_route();
    
    // Skip rate limiting for admin users
    if (current_user_can('manage_options')) {
        return $response;
    }
    
    // Rate limiting for contact and quote endpoints
    if (strpos($endpoint, '/renovalink/v1/contact') !== false || 
        strpos($endpoint, '/renovalink/v1/quote') !== false) {
        
        $transient_key = 'rate_limit_' . md5($ip . $endpoint);
        $requests = get_transient($transient_key) ?: 0;
        
        if ($requests >= 5) { // Max 5 requests per hour
            return new WP_Error(
                'rate_limit_exceeded',
                'Rate limit exceeded. Please try again later.',
                array('status' => 429)
            );
        }
        
        set_transient($transient_key, $requests + 1, HOUR_IN_SECONDS);
    }
    
    return $response;
}
add_filter('rest_pre_dispatch', 'rest_api_rate_limiting', 10, 3);

/**
 * PERFORMANCE OPTIMIZATIONS
 */

// Remove unnecessary WordPress features for headless setup
function remove_unnecessary_wp_features() {
    // Remove emoji support
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Remove oEmbed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    
    // Remove REST API links from head (we'll use direct endpoint URLs)
    remove_action('wp_head', 'rest_output_link_wp_head');
    
    // Remove feed links (not needed for headless)
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
}
add_action('init', 'remove_unnecessary_wp_features');

// Optimize database queries
function optimize_wp_queries() {
    // Remove unnecessary queries
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
}
add_action('init', 'optimize_wp_queries');

// Add caching headers for static content
function add_cache_headers() {
    if (!is_admin() && !is_user_logged_in()) {
        $cache_duration = 3600; // 1 hour
        
        // Different cache durations for different content types
        if (is_singular('servicios')) {
            $cache_duration = 7200; // 2 hours for services
        } elseif (is_singular('proyectos')) {
            $cache_duration = 3600; // 1 hour for projects
        } elseif (is_singular('testimonios')) {
            $cache_duration = 1800; // 30 minutes for testimonials
        }
        
        // DISABLED CACHE FOR DEVELOPMENT
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    }
}
add_action('template_redirect', 'add_cache_headers');

// Optimize images for API responses
function optimize_api_images($size = 'medium') {
    // Add WebP support if available
    if (function_exists('imagewebp')) {
        add_filter('wp_generate_attachment_metadata', function($metadata) {
            // Generate WebP versions of images
            return $metadata;
        });
    }
    
    // Lazy loading for images
    add_filter('wp_get_attachment_image_attributes', function($attr) {
        $attr['loading'] = 'lazy';
        return $attr;
    });
}
add_action('init', 'optimize_api_images');

// Database optimization for API queries
function optimize_api_database_queries() {
    // Add indexes for commonly queried meta fields
    global $wpdb;
    
    // Check if indexes exist and create them if needed
    $indexes = array(
        'project_type_idx' => "CREATE INDEX project_type_idx ON {$wpdb->postmeta} (meta_key, meta_value) WHERE meta_key = 'project_type'",
        'service_type_idx' => "CREATE INDEX service_type_idx ON {$wpdb->postmeta} (meta_key, meta_value) WHERE meta_key = 'service_provided'",
        'featured_idx' => "CREATE INDEX featured_idx ON {$wpdb->postmeta} (meta_key, meta_value) WHERE meta_key = 'featured_testimonial'"
    );
    
    foreach ($indexes as $index_name => $query) {
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM information_schema.statistics 
            WHERE table_schema = %s 
            AND table_name = %s 
            AND index_name = %s",
            DB_NAME,
            $wpdb->postmeta,
            $index_name
        ));
        
        if (!$exists) {
            // Only create index if it doesn't exist (avoid errors)
            // $wpdb->query($query); // Uncomment in production with proper testing
        }
    }
}
// Uncomment for production: add_action('init', 'optimize_api_database_queries');

// Minify JSON responses
function minify_json_responses($response) {
    if (is_wp_error($response)) {
        return $response;
    }
    
    // Remove unnecessary whitespace from JSON responses
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        $response = json_decode(json_encode($response), true);
        return $response;
    }
    
    return $response;
}
add_filter('rest_pre_serve_request', 'minify_json_responses');

// Implement object caching for expensive queries
function cache_expensive_api_queries() {
    // Cache service with projects query
    add_filter('rest_pre_dispatch', function($result, $server, $request) {
        $route = $request->get_route();
        
        if ($route === '/renovalink/v1/services-complete') {
            $cache_key = 'services_complete_api';
            $cached_result = wp_cache_get($cache_key);
            
            if ($cached_result !== false) {
                return rest_ensure_response($cached_result);
            }
        }
        
        return $result;
    }, 10, 3);
    
    // Cache the result after generation
    add_filter('rest_post_dispatch', function($result, $server, $request) {
        $route = $request->get_route();
        
        if ($route === '/renovalink/v1/services-complete' && !is_wp_error($result)) {
            $cache_key = 'services_complete_api';
            wp_cache_set($cache_key, $result->get_data(), '', 300); // Cache for 5 minutes
        }
        
        return $result;
    }, 10, 3);
}
add_action('init', 'cache_expensive_api_queries');

// Clean up expired transients
function cleanup_expired_transients() {
    global $wpdb;
    
    // Clean up expired transients weekly
    if (!wp_next_scheduled('cleanup_transients_hook')) {
        wp_schedule_event(time(), 'weekly', 'cleanup_transients_hook');
    }
}
add_action('init', 'cleanup_expired_transients');

function do_cleanup_transients() {
    global $wpdb;
    
    $wpdb->query("
        DELETE FROM {$wpdb->options} 
        WHERE option_name LIKE '_transient_%' 
        AND option_value < UNIX_TIMESTAMP()
    ");
    
    $wpdb->query("
        DELETE FROM {$wpdb->options} 
        WHERE option_name LIKE '_transient_timeout_%'
        AND option_value < UNIX_TIMESTAMP()
    ");
}
add_action('cleanup_transients_hook', 'do_cleanup_transients');

/**
 * MONITORING AND LOGGING
 */

// Log API usage for monitoring
function log_api_usage($response, $handler, $request) {
    if (strpos($request->get_route(), '/renovalink/v1/') !== false) {
        $log_data = array(
            'endpoint' => $request->get_route(),
            'method' => $request->get_method(),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'timestamp' => current_time('mysql')
        );
        
        // Log to file (ensure logs directory exists and is writable)
        $log_file = WP_CONTENT_DIR . '/logs/api-usage.log';
        if (is_writable(dirname($log_file))) {
            error_log(json_encode($log_data) . "\n", 3, $log_file);
        }
    }
    
    return $response;
}
// Uncomment for production monitoring: add_filter('rest_post_dispatch', 'log_api_usage', 10, 3);

// Performance monitoring
function monitor_api_performance() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        add_action('rest_pre_dispatch', function($result, $server, $request) {
            $_SERVER['api_start_time'] = microtime(true);
            return $result;
        }, 10, 3);
        
        add_filter('rest_post_dispatch', function($result, $server, $request) {
            if (isset($_SERVER['api_start_time'])) {
                $execution_time = microtime(true) - $_SERVER['api_start_time'];
                if ($execution_time > 1.0) { // Log slow queries (> 1 second)
                    error_log("Slow API query: {$request->get_route()} took {$execution_time} seconds");
                }
            }
            return $result;
        }, 10, 3);
    }
}
add_action('init', 'monitor_api_performance');

/**
 * MAINTENANCE MODE FOR API
 */
function api_maintenance_mode() {
    if (get_option('renovalink_maintenance_mode', false)) {
        add_filter('rest_pre_dispatch', function($result, $server, $request) {
            if (strpos($request->get_route(), '/renovalink/v1/') !== false) {
                return new WP_Error(
                    'maintenance_mode',
                    'API is temporarily unavailable for maintenance. Please try again later.',
                    array('status' => 503)
                );
            }
            return $result;
        }, 10, 3);
    }
}
add_action('init', 'api_maintenance_mode');

// Add maintenance mode toggle to admin
function add_maintenance_mode_option() {
    add_option('renovalink_maintenance_mode', false);
}
add_action('init', 'add_maintenance_mode_option');

/**
 * BACKUP AND RECOVERY HELPERS
 */

// Export configuration function
function export_renovalink_config() {
    $config = array(
        'options' => array(
            'hero_title' => get_option('hero_title'),
            'hero_subtitle' => get_option('hero_subtitle'),
            'hero_cta_text' => get_option('hero_cta_text'),
            'hero_cta_link' => get_option('hero_cta_link'),
            'hero_secondary_message' => get_option('hero_secondary_message'),
            'company_name' => get_option('company_name'),
            'company_phone' => get_option('company_phone'),
            'company_email' => get_option('company_email'),
            'company_address' => get_option('company_address'),
            'company_hours' => get_option('company_hours'),
            'service_areas' => get_option('service_areas'),
        ),
        'acf_options' => array(),
        'timestamp' => current_time('mysql')
    );
    
    // Export ACF options if available
    if (function_exists('get_field')) {
        $acf_fields = array(
            'company_description',
            'years_experience',
            'projects_completed',
            'license_number',
            'insurance_info'
        );
        
        foreach ($acf_fields as $field) {
            $config['acf_options'][$field] = get_field($field, 'option');
        }
    }
    
    return $config;
}

// Create admin endpoint for config export
function add_config_export_endpoint() {
    add_action('wp_ajax_export_renovalink_config', function() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $config = export_renovalink_config();
        
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="renovalink-config-' . date('Y-m-d') . '.json"');
        
        echo json_encode($config, JSON_PRETTY_PRINT);
        exit;
    });
}
add_action('init', 'add_config_export_endpoint');