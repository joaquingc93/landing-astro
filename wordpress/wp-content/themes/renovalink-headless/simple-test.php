<?php
/**
 * Simple test endpoint to verify WordPress API functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Simple test endpoint
function register_simple_test_endpoint() {
    register_rest_route('renovalink/v1', '/simple-test', array(
        'methods' => 'GET',
        'callback' => 'get_simple_test',
        'permission_callback' => '__return_true'
    ));
}

function get_simple_test() {
    return rest_ensure_response(array(
        'status' => 'success',
        'message' => 'Simple test endpoint working!',
        'timestamp' => current_time('mysql')
    ));
}

// Register the endpoint
add_action('rest_api_init', 'register_simple_test_endpoint');