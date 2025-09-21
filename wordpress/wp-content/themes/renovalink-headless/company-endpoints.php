<?php
/**
 * Company Info Endpoint - Alternative implementation
 * This file provides company information for the About Us page
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register alternative company info endpoint
function register_alt_company_endpoint() {
    register_rest_route('renovalink/v1', '/company-alt', array(
        'methods' => 'GET',
        'callback' => 'get_alt_company_info',
        'permission_callback' => '__return_true'
    ));
}

function get_alt_company_info() {
    // Try to get company page
    $company_page = get_page_by_path('informacion-de-la-empresa');
    
    $company_info = array(
        'success' => true,
        'data' => array(
            'company_info' => array(
                'name' => $company_page && function_exists('get_field') ? 
                    (get_field('company_name', $company_page->ID) ?: 'RenovaLink') : 'RenovaLink',
                'description' => $company_page && function_exists('get_field') ? 
                    (get_field('company_description', $company_page->ID) ?: 'Transformamos espacios con excelencia. Especializados en renovación de piscinas, ingeniería estructural, trabajo de concreto y limpieza residencial.') : 'Transformamos espacios con excelencia. Especializados en renovación de piscinas, ingeniería estructural, trabajo de concreto y limpieza residencial.',
                'emergency_phone' => $company_page && function_exists('get_field') ? 
                    (get_field('emergency_phone', $company_page->ID) ?: '+1(786)643-1254') : '+1(786)643-1254',
                'regular_phone' => $company_page && function_exists('get_field') ? 
                    (get_field('regular_phone', $company_page->ID) ?: '+1(786)643-1254') : '+1(786)643-1254',
                'email' => $company_page && function_exists('get_field') ? 
                    (get_field('email', $company_page->ID) ?: get_option('admin_email')) : get_option('admin_email'),
                'logo' => null
            ),
            'credentials' => array(
                'licensed' => true,
                'insured' => true,
                'certified_engineer' => true,
                'years_experience' => 15,
                'certifications' => array(
                    'Ingeniero Profesional de Florida',
                    'Licencia de Contratista de Piscinas y Spas',
                    'Licencia de Contratista General',
                    'Certificado EPA'
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
        ),
        'page_found' => $company_page ? true : false,
        'page_id' => $company_page ? $company_page->ID : null
    );
    
    return rest_ensure_response($company_info);
}

// Register the endpoint
add_action('rest_api_init', 'register_alt_company_endpoint');