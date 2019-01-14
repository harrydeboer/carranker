<?php

/** Read the database credentials and other parameters from an .env file. The .env file is not in the opcache because
 *  it is not a php file. Modifying the .env file does not need opcache_reset().
 */
$dotenvPath = dirname(__DIR__) . '/vendor/vlucas/phpdotenv/src/';
require_once($dotenvPath . 'Dotenv.php' );
require_once($dotenvPath . 'Loader.php' );
require_once($dotenvPath . 'Validator.php' );
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST'));
define('DEV_ENV', (int) getenv('DEV_ENV'));
define('JWT_AUTH_SECRET_KEY', getenv('JWT_AUTH_SECRET_KEY'));
define('LARAVEL_URL', getenv('LARAVEL_URL'));
define( 'WP_CONTENT_URL', getenv('WP_CONTENT_URL' ));

// ===========================================================
// No file edits unless explicitly allowed in local-config.php
// ===========================================================
if ( ! defined( 'DISALLOW_FILE_MODS' ) ) {
    define( 'DISALLOW_FILE_MODS', true );
}

define('JWT_AUTH_CORS_ENABLE', true);
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );

// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', dirname(__DIR__) . '/public/wp-content' );

// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );
// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ==============================================================
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );
// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix = getenv('WP_DB_PREFIX');
// ================================
// Language
// Leave blank for American English
// ================================
define( 'WPLANG', '' );
// ===========
// Hide errors
// ===========
ini_set( 'display_errors', 0 );
define( 'WP_DEBUG_DISPLAY', false );

// =================================================================
// Debug mode
// Debugging? Enable these. Can also enable them in local-config.php
// =================================================================
define( 'WP_DEBUG', true );

// ======================================
// Load a Memcached config if we have one
// ======================================
if ( file_exists( __DIR__ . '/memcached.php' ) ) {
    $memcached_servers = include( __DIR__ . '/memcached.php' );
}

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
    define( 'ABSPATH', __DIR__ . '/wp/' );

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );