<?php
/**
 * RenovaLink Headless WordPress Theme
 * Functions and definitions for headless WordPress setup
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}


/**
 * Theme Setup
 */
function renovalink_setup() {
    // Add theme support for post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add support for custom logo
    add_theme_support('custom-logo');
    
    // Add support for title tag
    add_theme_support('title-tag');
    
    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'comment-list',
        'comment-form',
        'search-form',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'renovalink_setup');

/**
 * Enqueue scripts and styles (minimal for headless)
 */
function renovalink_scripts() {
    // Only enqueue admin styles if needed
    if (is_admin()) {
        wp_enqueue_style('renovalink-admin-style', get_template_directory_uri() . '/admin-style.css');
    }
}
add_action('wp_enqueue_scripts', 'renovalink_scripts');

/**
 * Custom Post Types
 */

// Services Post Type
function register_servicios_post_type() {
    $args = array(
        'label' => 'Servicios',
        'labels' => array(
            'name' => 'Servicios',
            'singular_name' => 'Servicio',
            'menu_name' => 'Servicios',
            'add_new' => 'Añadir Servicio',
            'add_new_item' => 'Añadir Nuevo Servicio',
            'edit_item' => 'Editar Servicio',
            'new_item' => 'Nuevo Servicio',
            'view_item' => 'Ver Servicio',
            'search_items' => 'Buscar Servicios',
            'not_found' => 'No se encontraron servicios',
        ),
        'public' => true,
        'show_in_rest' => true,
        'rest_base' => 'servicios',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-hammer',
        'rewrite' => array('slug' => 'servicios'),
        'has_archive' => true,
    );
    register_post_type('servicios', $args);
}
add_action('init', 'register_servicios_post_type');

// Projects Post Type
function register_proyectos_post_type() {
    $args = array(
        'label' => 'Proyectos',
        'labels' => array(
            'name' => 'Proyectos',
            'singular_name' => 'Proyecto',
            'menu_name' => 'Proyectos',
            'add_new' => 'Añadir Proyecto',
            'add_new_item' => 'Añadir Nuevo Proyecto',
            'edit_item' => 'Editar Proyecto',
            'new_item' => 'Nuevo Proyecto',
            'view_item' => 'Ver Proyecto',
            'search_items' => 'Buscar Proyectos',
            'not_found' => 'No se encontraron proyectos',
        ),
        'public' => true,
        'show_in_rest' => true,
        'rest_base' => 'proyectos',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-portfolio',
        'rewrite' => array('slug' => 'proyectos'),
        'has_archive' => true,
    );
    register_post_type('proyectos', $args);
}
add_action('init', 'register_proyectos_post_type');

// Testimonials Post Type
function register_testimonios_post_type() {
    $args = array(
        'label' => 'Testimonios',
        'labels' => array(
            'name' => 'Testimonios',
            'singular_name' => 'Testimonio',
            'menu_name' => 'Testimonios',
            'add_new' => 'Añadir Testimonio',
            'add_new_item' => 'Añadir Nuevo Testimonio',
            'edit_item' => 'Editar Testimonio',
            'new_item' => 'Nuevo Testimonio',
            'view_item' => 'Ver Testimonio',
            'search_items' => 'Buscar Testimonios',
            'not_found' => 'No se encontraron testimonios',
        ),
        'public' => true,
        'show_in_rest' => true,
        'rest_base' => 'testimonios',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-format-quote',
        'rewrite' => array('slug' => 'testimonios'),
        'has_archive' => true,
    );
    register_post_type('testimonios', $args);
}
add_action('init', 'register_testimonios_post_type');

/**
 * Custom Taxonomies
 */

// Service Types Taxonomy
function register_service_types_taxonomy() {
    $args = array(
        'labels' => array(
            'name' => 'Tipos de Servicio',
            'singular_name' => 'Tipo de Servicio',
            'menu_name' => 'Tipos de Servicio',
        ),
        'public' => true,
        'show_in_rest' => true,
        'rest_base' => 'tipos-servicio',
        'hierarchical' => true,
        'rewrite' => array('slug' => 'tipo-servicio'),
    );
    register_taxonomy('service_type', array('servicios', 'proyectos'), $args);
}
add_action('init', 'register_service_types_taxonomy');

// Project Locations Taxonomy
function register_project_locations_taxonomy() {
    $args = array(
        'labels' => array(
            'name' => 'Ubicaciones',
            'singular_name' => 'Ubicación',
            'menu_name' => 'Ubicaciones',
        ),
        'public' => true,
        'show_in_rest' => true,
        'rest_base' => 'ubicaciones',
        'hierarchical' => true,
        'rewrite' => array('slug' => 'ubicacion'),
    );
    register_taxonomy('project_location', array('proyectos'), $args);
}
add_action('init', 'register_project_locations_taxonomy');

/**
 * REST API Enhancements
 */

// Add ACF fields to REST API
function add_acf_to_rest_api() {
    if (!function_exists('get_fields')) return;
    
    // Add ACF fields to posts
    register_rest_field('post', 'acf_fields', array(
        'get_callback' => function($post) {
            return get_fields($post['id']);
        }
    ));
    
    // Add ACF fields to servicios
    register_rest_field('servicios', 'acf_fields', array(
        'get_callback' => function($post) {
            return get_fields($post['id']);
        }
    ));
    
    // Add ACF fields to proyectos
    register_rest_field('proyectos', 'acf_fields', array(
        'get_callback' => function($post) {
            return get_fields($post['id']);
        }
    ));
    
    // Add ACF fields to testimonios
    register_rest_field('testimonios', 'acf_fields', array(
        'get_callback' => function($post) {
            return get_fields($post['id']);
        }
    ));
}
add_action('rest_api_init', 'add_acf_to_rest_api');

// Add featured image URL to REST API
function add_featured_image_to_rest() {
    register_rest_field(
        array('post', 'servicios', 'proyectos', 'testimonios'),
        'featured_image_url',
        array(
            'get_callback' => function($post) {
                if ($post['featured_media']) {
                    $image = wp_get_attachment_image_src($post['featured_media'], 'full');
                    return $image ? $image[0] : null;
                }
                return null;
            }
        )
    );
}
add_action('rest_api_init', 'add_featured_image_to_rest');

/**
 * Custom REST API Endpoints
 */

// Hero Section Endpoint
function register_hero_endpoint() {
    register_rest_route('renovalink/v1', '/hero', array(
        'methods' => 'GET',
        'callback' => 'get_hero_content',
        'permission_callback' => '__return_true'
    ));
}

function get_hero_content() {
    $hero_data = array(
        'title' => get_option('hero_title', 'Transform Your Home with Professional Remodeling Services'),
        'subtitle' => get_option('hero_subtitle', 'Quality pool remodeling, concrete work, and cleaning services in Florida'),
        'cta_text' => get_option('hero_cta_text', 'Get Free Quote'),
        'cta_link' => get_option('hero_cta_link', '#contact'),
        'background_image' => get_option('hero_background_image', ''),
        'secondary_message' => get_option('hero_secondary_message', 'Certified Florida Engineer • 15+ Years Experience • Licensed & Insured')
    );
    
    return rest_ensure_response($hero_data);
}

// Complete Services Endpoint
function register_services_complete_endpoint() {
    register_rest_route('renovalink/v1', '/services-complete', array(
        'methods' => 'GET',
        'callback' => 'get_services_complete',
        'permission_callback' => '__return_true'
    ));
}

function get_services_complete() {
    $services = get_posts(array(
        'post_type' => 'servicios',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    $services_data = array();
    
    foreach ($services as $service) {
        $acf_fields = get_fields($service->ID);
        $featured_image = get_the_post_thumbnail_url($service->ID, 'full');
        
        // Get related projects
        $related_projects = get_posts(array(
            'post_type' => 'proyectos',
            'meta_query' => array(
                array(
                    'key' => 'related_service',
                    'value' => $service->ID,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 6
        ));
        
        $project_data = array();
        foreach ($related_projects as $project) {
            $project_acf = get_fields($project->ID);
            $project_data[] = array(
                'id' => $project->ID,
                'title' => $project->post_title,
                'description' => $project->post_excerpt,
                'featured_image' => get_the_post_thumbnail_url($project->ID, 'full'),
                'before_image' => isset($project_acf['before_image']) ? $project_acf['before_image']['url'] : null,
                'after_image' => isset($project_acf['after_image']) ? $project_acf['after_image']['url'] : null,
                'location' => isset($project_acf['project_location']) ? $project_acf['project_location'] : '',
                'project_type' => isset($project_acf['project_type']) ? $project_acf['project_type'] : ''
            );
        }
        
        $services_data[] = array(
            'id' => $service->ID,
            'title' => $service->post_title,
            'content' => $service->post_content,
            'excerpt' => $service->post_excerpt,
            'featured_image' => $featured_image,
            'acf_fields' => $acf_fields,
            'related_projects' => $project_data
        );
    }
    
    return rest_ensure_response($services_data);
}

// Projects by Service Endpoint
function register_projects_by_service_endpoint() {
    register_rest_route('renovalink/v1', '/projects/(?P<service>[a-zA-Z0-9-]+)', array(
        'methods' => 'GET',
        'callback' => 'get_projects_by_service',
        'permission_callback' => '__return_true'
    ));
}

function get_projects_by_service($request) {
    $service_slug = $request['service'];
    
    $projects = get_posts(array(
        'post_type' => 'proyectos',
        'meta_query' => array(
            array(
                'key' => 'project_type',
                'value' => $service_slug,
                'compare' => 'LIKE'
            )
        ),
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    $projects_data = array();
    
    foreach ($projects as $project) {
        $acf_fields = get_fields($project->ID);
        $featured_image = get_the_post_thumbnail_url($project->ID, 'full');
        
        $projects_data[] = array(
            'id' => $project->ID,
            'title' => $project->post_title,
            'content' => $project->post_content,
            'excerpt' => $project->post_excerpt,
            'featured_image' => $featured_image,
            'acf_fields' => $acf_fields
        );
    }
    
    return rest_ensure_response($projects_data);
}

// Random Testimonials Endpoint
function register_random_testimonials_endpoint() {
    register_rest_route('renovalink/v1', '/testimonials/random', array(
        'methods' => 'GET',
        'callback' => 'get_random_testimonials',
        'permission_callback' => '__return_true'
    ));
}

function get_random_testimonials($request) {
    $limit = $request->get_param('limit') ? intval($request->get_param('limit')) : 3;
    
    $testimonials = get_posts(array(
        'post_type' => 'testimonios',
        'posts_per_page' => $limit,
        'orderby' => 'rand',
        'post_status' => 'publish'
    ));
    
    $testimonials_data = array();
    
    foreach ($testimonials as $testimonial) {
        $acf_fields = get_fields($testimonial->ID);
        $featured_image = get_the_post_thumbnail_url($testimonial->ID, 'full');
        
        $testimonials_data[] = array(
            'id' => $testimonial->ID,
            'title' => $testimonial->post_title,
            'content' => $testimonial->post_content,
            'featured_image' => $featured_image,
            'acf_fields' => $acf_fields
        );
    }
    
    return rest_ensure_response($testimonials_data);
}

// Register all custom endpoints
add_action('rest_api_init', 'register_hero_endpoint');
add_action('rest_api_init', 'register_services_complete_endpoint');
add_action('rest_api_init', 'register_projects_by_service_endpoint');
add_action('rest_api_init', 'register_random_testimonials_endpoint');

/**
 * CORS Support for Headless
 */
function add_cors_http_header() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With");
    header("Access-Control-Allow-Credentials: true");
}
add_action('init', 'add_cors_http_header');

/**
 * Optimize WordPress for Headless Usage
 */

// Remove unnecessary WordPress features for headless
function remove_unnecessary_features() {
    // Remove theme support for comments
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);
    
    // Hide comments metabox from dashboard
    add_action('admin_init', function() {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    });
    
    // Remove comment support from posts and pages
    add_action('init', function() {
        remove_post_type_support('post', 'comments');
        remove_post_type_support('page', 'comments');
    });
}
add_action('after_setup_theme', 'remove_unnecessary_features');

/**
 * Add Company Information to Options
 */
function register_company_options() {
    // Hero Section Options
    add_option('hero_title', 'Transform Your Home with Professional Remodeling Services');
    add_option('hero_subtitle', 'Quality pool remodeling, concrete work, and cleaning services in Florida');
    add_option('hero_cta_text', 'Get Free Quote');
    add_option('hero_cta_link', '#contact');
    add_option('hero_background_image', '');
    add_option('hero_secondary_message', 'Certified Florida Engineer • 15+ Years Experience • Licensed & Insured');
    
    // Company Information
    add_option('company_name', 'RenovaLink');
    add_option('company_phone', '(555) 123-4567');
    add_option('company_email', 'info@renovalink.com');
    add_option('company_address', 'Florida, USA');
    add_option('company_hours', 'Mon-Fri: 8AM-6PM, Sat: 9AM-4PM');
    
    // Service Areas
    add_option('service_areas', 'Miami-Dade, Broward, Palm Beach, Orange, Hillsborough');
    
    // Social Media
    add_option('facebook_url', '');
    add_option('instagram_url', '');
    add_option('linkedin_url', '');
    add_option('twitter_url', '');
}
add_action('init', 'register_company_options');

/**
 * Custom Admin Menu for Content Management
 */
function renovalink_admin_menu() {
    add_menu_page(
        'RenovaLink Settings',
        'RenovaLink',
        'manage_options',
        'renovalink-settings',
        'renovalink_settings_page',
        'dashicons-admin-home',
        2
    );
}
add_action('admin_menu', 'renovalink_admin_menu');

function renovalink_settings_page() {
    if (isset($_POST['submit'])) {
        // Update hero section
        update_option('hero_title', sanitize_text_field($_POST['hero_title']));
        update_option('hero_subtitle', sanitize_text_field($_POST['hero_subtitle']));
        update_option('hero_cta_text', sanitize_text_field($_POST['hero_cta_text']));
        update_option('hero_cta_link', sanitize_text_field($_POST['hero_cta_link']));
        update_option('hero_secondary_message', sanitize_text_field($_POST['hero_secondary_message']));
        
        // Update company info
        update_option('company_name', sanitize_text_field($_POST['company_name']));
        update_option('company_phone', sanitize_text_field($_POST['company_phone']));
        update_option('company_email', sanitize_email($_POST['company_email']));
        update_option('company_address', sanitize_text_field($_POST['company_address']));
        update_option('company_hours', sanitize_text_field($_POST['company_hours']));
        update_option('service_areas', sanitize_text_field($_POST['service_areas']));
        
        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }
    
    // Get current values
    $hero_title = get_option('hero_title');
    $hero_subtitle = get_option('hero_subtitle');
    $hero_cta_text = get_option('hero_cta_text');
    $hero_cta_link = get_option('hero_cta_link');
    $hero_secondary_message = get_option('hero_secondary_message');
    $company_name = get_option('company_name');
    $company_phone = get_option('company_phone');
    $company_email = get_option('company_email');
    $company_address = get_option('company_address');
    $company_hours = get_option('company_hours');
    $service_areas = get_option('service_areas');
    
    ?>
    <div class="wrap">
        <h1>RenovaLink Settings</h1>
        <form method="post" action="">
            <table class="form-table">
                <h2>Hero Section</h2>
                <tr>
                    <th scope="row">Hero Title</th>
                    <td><input type="text" name="hero_title" value="<?php echo esc_attr($hero_title); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">Hero Subtitle</th>
                    <td><input type="text" name="hero_subtitle" value="<?php echo esc_attr($hero_subtitle); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">CTA Button Text</th>
                    <td><input type="text" name="hero_cta_text" value="<?php echo esc_attr($hero_cta_text); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">CTA Button Link</th>
                    <td><input type="text" name="hero_cta_link" value="<?php echo esc_attr($hero_cta_link); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">Secondary Message</th>
                    <td><input type="text" name="hero_secondary_message" value="<?php echo esc_attr($hero_secondary_message); ?>" class="regular-text" /></td>
                </tr>
                
                <h2>Company Information</h2>
                <tr>
                    <th scope="row">Company Name</th>
                    <td><input type="text" name="company_name" value="<?php echo esc_attr($company_name); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">Phone</th>
                    <td><input type="text" name="company_phone" value="<?php echo esc_attr($company_phone); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td><input type="email" name="company_email" value="<?php echo esc_attr($company_email); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">Address</th>
                    <td><input type="text" name="company_address" value="<?php echo esc_attr($company_address); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">Business Hours</th>
                    <td><input type="text" name="company_hours" value="<?php echo esc_attr($company_hours); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">Service Areas</th>
                    <td><input type="text" name="service_areas" value="<?php echo esc_attr($service_areas); ?>" class="regular-text" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Image Size for Projects
 */
function renovalink_image_sizes() {
    add_image_size('project-thumbnail', 400, 300, true);
    add_image_size('project-large', 800, 600, true);
    add_image_size('hero-background', 1920, 1080, true);
}
add_action('after_setup_theme', 'renovalink_image_sizes');

// Include ACF Configuration
// ACF configuration now handled manually in WordPress admin

// Include Additional API Endpoints
require_once get_template_directory() . '/api-endpoints.php';

// Include Sample Content
require_once get_template_directory() . '/sample-content/sample-data.php';

// Include test endpoints
require_once get_template_directory() . '/test-endpoint.php';

// Include Security and Performance Optimizations
require_once get_template_directory() . '/security-performance.php';