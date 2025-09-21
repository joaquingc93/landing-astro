<?php
// Test endpoints file - created fresh to avoid cache issues

// Test endpoint
function register_fresh_test_endpoint() {
    register_rest_route('renovalink/v1', '/fresh-test', array(
        'methods' => 'GET',
        'callback' => 'get_fresh_test_response',
        'permission_callback' => '__return_true'
    ));
}

function get_fresh_test_response() {
    return rest_ensure_response(array(
        'message' => 'Fresh file is working!',
        'timestamp' => current_time('mysql'),
        'file_name' => 'test-endpoints.php'
    ));
}

// Register the test endpoint
add_action('rest_api_init', 'register_fresh_test_endpoint');

// Company info endpoint with fresh implementation
function register_fresh_company_endpoint() {
    register_rest_route('renovalink/v1', '/fresh-company', array(
        'methods' => 'GET',
        'callback' => 'get_fresh_company_info',
        'permission_callback' => '__return_true'
    ));
}

function get_fresh_company_info() {
    // Get company information from the "informacion-de-la-empresa" page
    $company_page = get_page_by_path('informacion-de-la-empresa');
    
    if ($company_page) {
        // Get ACF fields if available
        $company_name = function_exists('get_field') ? get_field('company_name', $company_page->ID) : null;
        $company_description = function_exists('get_field') ? get_field('company_description', $company_page->ID) : null;
        $emergency_phone = function_exists('get_field') ? get_field('emergency_phone', $company_page->ID) : null;
        
        $company_info = array(
            'name' => $company_name ?: get_bloginfo('name'),
            'description' => $company_description ?: 'Premier remodeling services in Florida',
            'emergency_phone' => $emergency_phone ?: '+1(786)643-1254',
            'page_found' => true,
            'page_id' => $company_page->ID,
            'page_title' => $company_page->post_title,
            'acf_available' => function_exists('get_field')
        );
    } else {
        $company_info = array(
            'name' => 'RenovaLink',
            'description' => 'Premier remodeling services in Florida',
            'emergency_phone' => '+1(786)643-1254',
            'page_found' => false,
            'acf_available' => function_exists('get_field')
        );
    }
    
    return rest_ensure_response($company_info);
}

// Register the company endpoint
add_action('rest_api_init', 'register_fresh_company_endpoint');