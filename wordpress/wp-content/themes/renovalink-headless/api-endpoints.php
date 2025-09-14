<?php
/**
 * Additional REST API Endpoints for RenovaLink
 * Advanced endpoints for specific frontend needs
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Advanced REST API Endpoints
 */

// Site Stats Endpoint
function register_site_stats_endpoint() {
    register_rest_route('renovalink/v1', '/stats', array(
        'methods' => 'GET',
        'callback' => 'get_site_stats',
        'permission_callback' => '__return_true'
    ));
}

function get_site_stats() {
    $stats = array(
        'total_services' => wp_count_posts('servicios')->publish,
        'total_projects' => wp_count_posts('proyectos')->publish,
        'total_testimonials' => wp_count_posts('testimonios')->publish,
        'years_experience' => get_field('years_experience', 'option') ?: 15,
        'projects_completed' => get_field('projects_completed', 'option') ?: 500,
        'average_rating' => get_average_testimonial_rating(),
        'last_updated' => get_the_modified_time('c', get_latest_updated_post()),
    );
    
    return rest_ensure_response($stats);
}

// Helper function to get average rating
function get_average_testimonial_rating() {
    global $wpdb;
    
    $average = $wpdb->get_var("
        SELECT AVG(CAST(meta_value AS DECIMAL(3,2))) 
        FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = 'rating' 
        AND p.post_type = 'testimonios' 
        AND p.post_status = 'publish'
    ");
    
    return $average ? round($average, 1) : 5.0;
}

// Helper function to get latest updated post
function get_latest_updated_post() {
    $posts = get_posts(array(
        'post_type' => array('servicios', 'proyectos', 'testimonios'),
        'posts_per_page' => 1,
        'orderby' => 'modified',
        'order' => 'DESC'
    ));
    
    return $posts ? $posts[0]->ID : null;
}

// Featured Content Endpoint
function register_featured_content_endpoint() {
    register_rest_route('renovalink/v1', '/featured', array(
        'methods' => 'GET',
        'callback' => 'get_featured_content',
        'permission_callback' => '__return_true'
    ));
}

function get_featured_content() {
    // Get featured testimonials
    $featured_testimonials = get_posts(array(
        'post_type' => 'testimonios',
        'meta_query' => array(
            array(
                'key' => 'featured_testimonial',
                'value' => '1',
                'compare' => '='
            )
        ),
        'posts_per_page' => 3,
        'post_status' => 'publish'
    ));
    
    $testimonials_data = array();
    foreach ($featured_testimonials as $testimonial) {
        $acf_fields = get_fields($testimonial->ID);
        $testimonials_data[] = array(
            'id' => $testimonial->ID,
            'title' => $testimonial->post_title,
            'content' => $testimonial->post_content,
            'acf_fields' => $acf_fields,
            'featured_image' => get_the_post_thumbnail_url($testimonial->ID, 'full')
        );
    }
    
    // Get latest projects (one per service type)
    $latest_projects = array();
    $service_types = array('pool', 'concrete', 'cleaning', 'engineering');
    
    foreach ($service_types as $type) {
        $project = get_posts(array(
            'post_type' => 'proyectos',
            'meta_query' => array(
                array(
                    'key' => 'project_type',
                    'value' => $type,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish'
        ));
        
        if ($project) {
            $acf_fields = get_fields($project[0]->ID);
            $latest_projects[] = array(
                'id' => $project[0]->ID,
                'title' => $project[0]->post_title,
                'excerpt' => $project[0]->post_excerpt,
                'featured_image' => get_the_post_thumbnail_url($project[0]->ID, 'full'),
                'acf_fields' => $acf_fields
            );
        }
    }
    
    $featured_content = array(
        'testimonials' => $testimonials_data,
        'latest_projects' => $latest_projects,
    );
    
    return rest_ensure_response($featured_content);
}

// Search Endpoint
function register_search_endpoint() {
    register_rest_route('renovalink/v1', '/search', array(
        'methods' => 'GET',
        'callback' => 'search_content',
        'permission_callback' => '__return_true'
    ));
}

function search_content($request) {
    $search_term = sanitize_text_field($request->get_param('q'));
    $post_type = $request->get_param('type') ?: 'any';
    $per_page = intval($request->get_param('per_page')) ?: 10;
    
    if (empty($search_term)) {
        return new WP_Error('missing_search_term', 'Search term is required', array('status' => 400));
    }
    
    $post_types = $post_type === 'any' ? array('servicios', 'proyectos', 'testimonios') : array($post_type);
    
    $args = array(
        'post_type' => $post_types,
        's' => $search_term,
        'posts_per_page' => $per_page,
        'post_status' => 'publish'
    );
    
    $search_results = get_posts($args);
    $results_data = array();
    
    foreach ($search_results as $result) {
        $acf_fields = get_fields($result->ID);
        $results_data[] = array(
            'id' => $result->ID,
            'type' => $result->post_type,
            'title' => $result->post_title,
            'content' => wp_trim_words($result->post_content, 30),
            'excerpt' => $result->post_excerpt,
            'featured_image' => get_the_post_thumbnail_url($result->ID, 'medium'),
            'link' => get_permalink($result->ID),
            'acf_fields' => $acf_fields
        );
    }
    
    return rest_ensure_response(array(
        'results' => $results_data,
        'total' => count($results_data),
        'search_term' => $search_term
    ));
}

// Service Areas Endpoint
function register_service_areas_endpoint() {
    register_rest_route('renovalink/v1', '/service-areas', array(
        'methods' => 'GET',
        'callback' => 'get_service_areas',
        'permission_callback' => '__return_true'
    ));
}

function get_service_areas() {
    $service_areas_raw = get_option('service_areas', 'Miami-Dade, Broward, Palm Beach, Orange, Hillsborough');
    $service_areas = array_map('trim', explode(',', $service_areas_raw));
    
    // Get project counts by location
    $areas_with_counts = array();
    foreach ($service_areas as $area) {
        $project_count = get_posts(array(
            'post_type' => 'proyectos',
            'meta_query' => array(
                array(
                    'key' => 'project_location',
                    'value' => $area,
                    'compare' => 'LIKE'
                )
            ),
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'fields' => 'ids'
        ));
        
        $areas_with_counts[] = array(
            'name' => $area,
            'project_count' => count($project_count),
            'slug' => sanitize_title($area)
        );
    }
    
    return rest_ensure_response(array(
        'service_areas' => $areas_with_counts,
        'total_areas' => count($areas_with_counts)
    ));
}

// Contact Form Submission Endpoint
function register_contact_form_endpoint() {
    register_rest_route('renovalink/v1', '/contact', array(
        'methods' => 'POST',
        'callback' => 'handle_contact_form',
        'permission_callback' => '__return_true',
        'args' => array(
            'name' => array(
                'required' => true,
                'validate_callback' => function($param) {
                    return !empty(sanitize_text_field($param));
                }
            ),
            'email' => array(
                'required' => true,
                'validate_callback' => function($param) {
                    return is_email($param);
                }
            ),
            'phone' => array(
                'required' => false,
                'validate_callback' => function($param) {
                    return true; // Phone is optional
                }
            ),
            'service_type' => array(
                'required' => true,
                'validate_callback' => function($param) {
                    $valid_services = array('pool', 'concrete', 'cleaning', 'engineering', 'general');
                    return in_array($param, $valid_services);
                }
            ),
            'message' => array(
                'required' => true,
                'validate_callback' => function($param) {
                    return !empty(sanitize_textarea_field($param));
                }
            )
        )
    ));
}

function handle_contact_form($request) {
    $name = sanitize_text_field($request->get_param('name'));
    $email = sanitize_email($request->get_param('email'));
    $phone = sanitize_text_field($request->get_param('phone'));
    $service_type = sanitize_text_field($request->get_param('service_type'));
    $message = sanitize_textarea_field($request->get_param('message'));
    
    // Get admin email
    $admin_email = get_option('admin_email');
    $company_email = get_option('company_email', $admin_email);
    
    // Prepare email
    $subject = 'New Contact Form Submission - ' . get_option('company_name', 'RenovaLink');
    $body = "New contact form submission from the website:\n\n";
    $body .= "Name: {$name}\n";
    $body .= "Email: {$email}\n";
    $body .= "Phone: {$phone}\n";
    $body .= "Service Interest: {$service_type}\n";
    $body .= "Message:\n{$message}\n\n";
    $body .= "Submitted: " . current_time('mysql') . "\n";
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $email
    );
    
    // Send email
    $sent = wp_mail($company_email, $subject, $body, $headers);
    
    if ($sent) {
        // Log the submission (optional)
        error_log("Contact form submission from {$email} sent successfully");
        
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Your message has been sent successfully. We will get back to you soon!'
        ));
    } else {
        return new WP_Error('email_failed', 'Failed to send your message. Please try again later.', array('status' => 500));
    }
}

// Quote Request Endpoint
function register_quote_endpoint() {
    register_rest_route('renovalink/v1', '/quote', array(
        'methods' => 'POST',
        'callback' => 'handle_quote_request',
        'permission_callback' => '__return_true',
        'args' => array(
            'name' => array('required' => true),
            'email' => array('required' => true),
            'phone' => array('required' => true),
            'service_type' => array('required' => true),
            'project_description' => array('required' => true),
            'project_size' => array('required' => false),
            'timeline' => array('required' => false),
            'budget_range' => array('required' => false)
        )
    ));
}

function handle_quote_request($request) {
    $data = array(
        'name' => sanitize_text_field($request->get_param('name')),
        'email' => sanitize_email($request->get_param('email')),
        'phone' => sanitize_text_field($request->get_param('phone')),
        'service_type' => sanitize_text_field($request->get_param('service_type')),
        'project_description' => sanitize_textarea_field($request->get_param('project_description')),
        'project_size' => sanitize_text_field($request->get_param('project_size')),
        'timeline' => sanitize_text_field($request->get_param('timeline')),
        'budget_range' => sanitize_text_field($request->get_param('budget_range'))
    );
    
    // Validate required fields
    if (empty($data['name']) || empty($data['email']) || !is_email($data['email'])) {
        return new WP_Error('invalid_data', 'Please provide valid name and email', array('status' => 400));
    }
    
    // Create the email
    $admin_email = get_option('admin_email');
    $company_email = get_option('company_email', $admin_email);
    
    $subject = 'New Quote Request - ' . get_option('company_name', 'RenovaLink');
    $body = "New quote request received:\n\n";
    $body .= "Contact Information:\n";
    $body .= "Name: {$data['name']}\n";
    $body .= "Email: {$data['email']}\n";
    $body .= "Phone: {$data['phone']}\n\n";
    $body .= "Project Details:\n";
    $body .= "Service Type: {$data['service_type']}\n";
    $body .= "Description: {$data['project_description']}\n";
    
    if (!empty($data['project_size'])) {
        $body .= "Project Size: {$data['project_size']}\n";
    }
    if (!empty($data['timeline'])) {
        $body .= "Timeline: {$data['timeline']}\n";
    }
    if (!empty($data['budget_range'])) {
        $body .= "Budget Range: {$data['budget_range']}\n";
    }
    
    $body .= "\nSubmitted: " . current_time('mysql') . "\n";
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $data['email']
    );
    
    $sent = wp_mail($company_email, $subject, $body, $headers);
    
    if ($sent) {
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Your quote request has been submitted! We will contact you within 24 hours.'
        ));
    } else {
        return new WP_Error('email_failed', 'Failed to submit quote request. Please try again.', array('status' => 500));
    }
}

// Site Configuration Endpoint
function register_site_config_endpoint() {
    register_rest_route('renovalink/v1', '/site-config', array(
        'methods' => 'GET',
        'callback' => 'get_site_config',
        'permission_callback' => '__return_true'
    ));
}

function get_site_config() {
    // Find the "Configuración del Sitio" page
    $config_page = get_page_by_title('Configuración del Sitio');
    $page_id = $config_page ? $config_page->ID : null;
    
    // Get hero fields from the configuration page
    $hero_background = $page_id ? get_field('hero_background_image', $page_id) : null;
    $hero_overlay = $page_id ? get_field('hero_overlay_opacity', $page_id) : 0.7;
    $hero_video = $page_id ? get_field('hero_video_url', $page_id) : null;
    $hero_title = $page_id ? get_field('hero_title', $page_id) : 'Transform Your Space with RenovaLink';
    $hero_subtitle = $page_id ? get_field('hero_subtitle', $page_id) : 'Premier Remodeling Services in Florida';
    $hero_description = $page_id ? get_field('hero_description', $page_id) : 'From pool renovations to structural engineering, we bring your vision to life with quality craftsmanship and certified expertise.';
    
    // Prepare hero background data
    $hero_bg_data = null;
    if ($hero_background) {
        $hero_bg_data = array(
            'id' => $hero_background['ID'] ?? $hero_background['id'],
            'url' => $hero_background['url'] ?? '',
            'alt' => $hero_background['alt'] ?? '',
            'title' => $hero_background['title'] ?? '',
            'width' => $hero_background['width'] ?? null,
            'height' => $hero_background['height'] ?? null,
            'sizes' => $hero_background['sizes'] ?? null
        );
    }
    
    $config = array(
        'site_name' => get_option('blogname'),
        'site_description' => get_option('blogdescription'),
        'hero' => array(
            'title' => $hero_title,
            'subtitle' => $hero_subtitle, 
            'description' => $hero_description,
            'background_image' => $hero_bg_data,
            'overlay_opacity' => $hero_overlay,
            'video_url' => $hero_video
        ),
        'contact' => array(
            'phone' => '(305) XXX-XXXX',
            'email' => get_option('admin_email'),
            'address' => 'Florida, USA'
        ),
        'config_page_id' => $page_id
    );
    
    return rest_ensure_response($config);
}

// Company Info Endpoint
function register_company_info_endpoint() {
    register_rest_route('renovalink/v1', '/company-info', array(
        'methods' => 'GET',
        'callback' => 'get_company_info',
        'permission_callback' => '__return_true'
    ));
}

function get_company_info() {
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
            'plugin_version' => '1.0.0',
            'available_pages' => get_available_pages_list()
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

// Helper function to get available pages for debugging
function get_available_pages_list() {
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

// Register all additional endpoints
add_action('rest_api_init', 'register_site_stats_endpoint');
add_action('rest_api_init', 'register_featured_content_endpoint');
add_action('rest_api_init', 'register_search_endpoint');
add_action('rest_api_init', 'register_service_areas_endpoint');
add_action('rest_api_init', 'register_contact_form_endpoint');
add_action('rest_api_init', 'register_quote_endpoint');
add_action('rest_api_init', 'register_site_config_endpoint');
add_action('rest_api_init', 'register_company_info_endpoint');

/**
 * Add custom headers for caching
 */
function add_custom_api_headers() {
    // Add cache headers for API endpoints
    if (strpos($_SERVER['REQUEST_URI'], '/wp-json/renovalink/') !== false) {
        // Cache static content for 1 hour
        if (strpos($_SERVER['REQUEST_URI'], '/hero') !== false || 
            strpos($_SERVER['REQUEST_URI'], '/options') !== false) {
            header('Cache-Control: public, max-age=3600');
        }
        // Cache dynamic content for 5 minutes
        else {
            header('Cache-Control: public, max-age=300');
        }
    }
}
add_action('template_redirect', 'add_custom_api_headers');