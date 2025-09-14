<?php
/**
 * Plugin Name: RenovaLink Custom Endpoints
 * Description: Custom REST API endpoints for RenovaLink
 * Version: 1.0
 * Author: RenovaLink Team
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register endpoints on plugin activation
register_activation_hook(__FILE__, 'renovalink_flush_rewrite_rules');

function renovalink_flush_rewrite_rules() {
    flush_rewrite_rules();
}

// Register REST API endpoints
add_action('rest_api_init', 'renovalink_register_endpoints');

function renovalink_register_endpoints() {
    // Test endpoint
    register_rest_route('renovalink/v1', '/test', array(
        'methods' => 'GET',
        'callback' => 'renovalink_test_endpoint',
        'permission_callback' => '__return_true'
    ));

    // Company info endpoint
    register_rest_route('renovalink/v1', '/company-info', array(
        'methods' => 'GET',
        'callback' => 'renovalink_company_info_endpoint',
        'permission_callback' => '__return_true'
    ));
}

function renovalink_test_endpoint() {
    return rest_ensure_response(array(
        'success' => true,
        'message' => 'RenovaLink endpoints are working!',
        'timestamp' => current_time('mysql'),
        'wordpress_version' => get_bloginfo('version')
    ));
}

function renovalink_company_info_endpoint() {
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
            'current_theme' => get_stylesheet(),
            'plugin_active' => true
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
                'url' => $company_logo['url'] ?? '',
                'alt' => $company_logo['alt'] ?? '',
                'width' => $company_logo['width'] ?? null,
                'height' => $company_logo['height'] ?? null
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
    } else {
        $response['message'] = 'Company information page not found. Please create a page titled "Información de la Empresa"';
    }

    return rest_ensure_response($response);
}