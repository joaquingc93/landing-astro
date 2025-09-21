<?php
/**
 * Simple test endpoint to verify REST API registration is working
 */

function register_simple_test_endpoint() {
    register_rest_route('renovalink/v1', '/simple-test', array(
        'methods' => 'GET',
        'callback' => 'get_simple_test',
        'permission_callback' => '__return_true'
    ));
}

function get_simple_test() {
    return rest_ensure_response(array(
        'success' => true,
        'message' => 'Simple test endpoint is working!',
        'timestamp' => current_time('mysql'),
        'wp_version' => get_bloginfo('version'),
        'theme' => get_template()
    ));
}

add_action('rest_api_init', 'register_simple_test_endpoint');