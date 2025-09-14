<?php
/**
 * Standalone test endpoint
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register a simple test endpoint
add_action('rest_api_init', 'register_test_endpoints');

function register_test_endpoints() {
    // Simple test endpoint
    register_rest_route('renovalink/v1', '/test', array(
        'methods' => 'GET',
        'callback' => 'handle_test_endpoint',
        'permission_callback' => '__return_true'
    ));

    // Company info endpoint
    register_rest_route('renovalink/v1', '/company-info', array(
        'methods' => 'GET',
        'callback' => 'handle_company_info_endpoint',
        'permission_callback' => '__return_true'
    ));
}

function handle_test_endpoint() {
    return rest_ensure_response(array(
        'success' => true,
        'message' => 'Test endpoint is working!',
        'timestamp' => current_time('mysql'),
        'wordpress_version' => get_bloginfo('version'),
        'theme' => get_stylesheet()
    ));
}

function handle_company_info_endpoint() {
    // Find the company information page
    $company_page = get_page_by_title('Información de la Empresa');

    if (!$company_page) {
        $company_page = get_page_by_title('Informacion de la Empresa');
    }

    $response = array(
        'success' => true,
        'debug' => array(
            'page_found' => !!$company_page,
            'acf_active' => function_exists('get_field'),
            'theme_active' => get_stylesheet()
        )
    );

    if ($company_page) {
        $page_id = $company_page->ID;

        $response['data'] = array(
            'company_info' => array(
                'name' => get_bloginfo('name'),
                'description' => function_exists('get_field') ? get_field('company_description', $page_id) : 'ACF not active',
                'emergency_phone' => function_exists('get_field') ? get_field('emergency_phone', $page_id) : '+1(786)643-1254',
                'logo' => function_exists('get_field') ? get_field('company_logo', $page_id) : null
            ),
            'page_info' => array(
                'id' => $page_id,
                'title' => $company_page->post_title,
                'content' => wp_trim_words($company_page->post_content, 20)
            )
        );
    }

    return rest_ensure_response($response);
}