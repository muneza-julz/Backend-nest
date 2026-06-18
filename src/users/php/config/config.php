<?php
/**
 * Application Configuration
 * General application settings and constants
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Application settings
define('APP_NAME', 'XWZ School SRS');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/xwz-school-srs/');

// Security settings
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('PASSWORD_MIN_LENGTH', 8);

// User roles
define('ROLE_ADMIN', 'admin');
define('ROLE_REGISTRAR', 'registrar');
define('ROLE_STUDENT', 'student');

// Timezone
date_default_timezone_set('UTC');

// Include database configuration
require_once __DIR__ . '/database.php';
?>
