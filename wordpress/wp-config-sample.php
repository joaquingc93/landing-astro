<?php
/**
 * WordPress Configuration for RenovaLink Headless Setup
 * Copy this to wp-config.php and update the database settings
 */

// Database Settings - UPDATE THESE
define('DB_NAME', 'renovalink_wp');
define('DB_USER', 'your_db_user');
define('DB_PASSWORD', 'your_db_password');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// Authentication Unique Keys and Salts
// Generate these at: https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'your-unique-auth-key-here');
define('SECURE_AUTH_KEY',  'your-unique-secure-auth-key-here');
define('LOGGED_IN_KEY',    'your-unique-logged-in-key-here');
define('NONCE_KEY',        'your-unique-nonce-key-here');
define('AUTH_SALT',        'your-unique-auth-salt-here');
define('SECURE_AUTH_SALT', 'your-unique-secure-auth-salt-here');
define('LOGGED_IN_SALT',   'your-unique-logged-in-salt-here');
define('NONCE_SALT',       'your-unique-nonce-salt-here');

// WordPress Database Table prefix
$table_prefix = 'rl_';

// WordPress Debugging (set to false in production)
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

// Headless WordPress Optimizations
define('DISALLOW_FILE_EDIT', true);
define('WP_POST_REVISIONS', 5);
define('AUTOSAVE_INTERVAL', 300);
define('WP_AUTO_UPDATE_CORE', 'minor');

// Memory and Performance
ini_set('memory_limit', '256M');
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// Security Enhancements
define('FORCE_SSL_ADMIN', true);
define('WP_HTTP_BLOCK_EXTERNAL', false);

// API Optimizations
define('COMPRESS_CSS', true);
define('COMPRESS_SCRIPTS', true);
define('CONCATENATE_SCRIPTS', false);

// Cache Settings (if using object caching)
define('WP_CACHE', true);
define('ENABLE_CACHE', true);

// Multisite (if needed)
// define('WP_ALLOW_MULTISITE', true);

// Custom Content Directory (if needed)
// define('WP_CONTENT_DIR', dirname(__FILE__) . '/wp-content');
// define('WP_CONTENT_URL', 'http://example.com/wp-content');

// Absolute path to the WordPress directory
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

// Sets up WordPress vars and included files
require_once ABSPATH . 'wp-settings.php';

// RenovaLink Specific Settings
define('RENOVALINK_VERSION', '1.0.0');
define('RENOVALINK_API_VERSION', 'v1');

// Frontend URL (for CORS and redirects)
define('FRONTEND_URL', 'http://localhost:4321'); // Update for production

// Email Settings
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
define('SMTP_FROM', 'info@renovalink.com');
define('SMTP_NAME', 'RenovaLink');

// Rate Limiting Settings
define('API_RATE_LIMIT', 100); // requests per hour per IP
define('CONTACT_RATE_LIMIT', 5); // contact form submissions per hour per IP