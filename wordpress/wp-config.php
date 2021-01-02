<?php

declare(strict_types=1);

/** Read the database credentials and other parameters from an .env file. The .env file is not in the opcache because
 *  it is not a php file. Modifying the .env file does not need opcache_reset().
 */

use Illuminate\Support\Env;

require_once __DIR__ . '/readenv.php';

define('DB_NAME', Env::get('DB_DATABASE'));
define('DB_USER', Env::get('DB_USERNAME'));
define('DB_PASSWORD', Env::get('DB_PASSWORD'));
define('DB_HOST', Env::get('DB_HOST'));
define('APP_ENV', Env::get('APP_ENV'));
define('JWT_AUTH_SECRET_KEY', Env::get('JWT_AUTH_SECRET_KEY'));
define( 'WP_CONTENT_URL', Env::get('WP_CONTENT_URL' ));
if (APP_ENV === 'production' || APP_ENV === 'acceptance') {
	define( 'MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT );
	define( 'DB_SSL', true );
}

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
define('AUTH_KEY',         Env::get('AUTH_KEY'));
define('SECURE_AUTH_KEY',  Env::get('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY',    Env::get('LOGGED_IN_KEY'));
define('NONCE_KEY',        Env::get('NONCE_KEY'));
define('AUTH_SALT',        Env::get('AUTH_SALT'));
define('SECURE_AUTH_SALT', Env::get('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT',   Env::get('LOGGED_IN_SALT'));
define('NONCE_SALT',       Env::get('NONCE_SALT'));
// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix = Env::get('WP_DB_PREFIX');
// ================================
// Language
// Leave blank for American English
// ================================
define( 'WPLANG', '' );
// ===========
// Hide errors
// ===========
ini_set( 'display_errors', Env::get('APP_DEBUG') === 'true' ? '1' : '0' );
define( 'WP_DEBUG_DISPLAY', Env::get('APP_DEBUG') === 'true' ? true : false );

// =================================================================
// Debug mode
// Debugging? Enable these. Can also enable them in local-config.php
// =================================================================
define( 'WP_DEBUG', Env::get('APP_DEBUG') === 'true' ? true : false );

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
    define( 'ABSPATH', __DIR__ . '/wp/' );

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
