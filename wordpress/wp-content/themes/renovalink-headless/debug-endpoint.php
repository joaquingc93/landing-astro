<?php
// Simple debug endpoint to test if REST API registration works at all

function register_debug_endpoint() {
    register_rest_route('debug/v1', '/test', array(
        'methods' => 'GET',
        'callback' => 'get_debug_test',
        'permission_callback' => '__return_true'
    ));
}

function get_debug_test() {
    return rest_ensure_response(array(
        'success' => true,
        'message' => 'Debug endpoint works!',
        'timestamp' => current_time('mysql')
    ));
}

add_action('rest_api_init', 'register_debug_endpoint');