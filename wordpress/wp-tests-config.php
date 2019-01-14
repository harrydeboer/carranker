<?php

/* Path to the WordPress codebase you'd like to test. Add a forward slash in the end. */
define( 'ABSPATH', __DIR__ . '/wp/' );

$dotenvPath = dirname(__DIR__) . '/vendor/vlucas/phpdotenv/src/';
require_once($dotenvPath . 'Dotenv.php' );
require_once($dotenvPath . 'Loader.php' );
require_once($dotenvPath . 'Validator.php' );
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

/*
 * Path to the theme to test with.
 *
 * The 'default' theme is symlinked from test/phpunit/data/themedir1/default into
 * the themes directory of the WordPress installation defined above.
 */
define( 'WP_DEFAULT_THEME', 'default' );

// Test with multisite enabled.
// Alternatively, use the tests/phpunit/multisite.xml configuration file.
// define( 'WP_TESTS_MULTISITE', true );

// Force known bugs to be run.
// Tests with an associated Trac ticket that is still open are normally skipped.
// define( 'WP_TESTS_FORCE_KNOWN_BUGS', true );

// Test with WordPress debug mode (default).
define( 'WP_DEBUG', true );

// ** MySQL settings ** //

// This configuration file will be used by the copy of WordPress being tested.
// wordpress/wp-config.php will be ignored.

// WARNING WARNING WARNING!
// These tests will DROP ALL TABLES in the database with the prefix named below.
// DO NOT use a production database or one that is shared with something else.

define('DB_NAME', getenv('DB_TEST_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST'));
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );
define('DEV_ENV', (int) getenv('DEV_ENV'));
define('JWT_AUTH_SECRET_KEY', getenv('JWT_AUTH_SECRET_KEY'));
define('LARAVEL_URL', getenv('LARAVEL_URL'));
define( 'WP_CONTENT_URL', getenv('WP_CONTENT_URL' ));

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 */
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

$table_prefix  = getenv('WP_DB_PREFIX');   // Only numbers, letters, and underscores please!

define( 'WP_TESTS_DOMAIN', 'cms.carranker' );
define( 'WP_TESTS_EMAIL', getenv('TEST_EMAIL') );
define( 'WP_TESTS_TITLE', 'Test Blog' );

/** WP_PHP_BINARY is not set to php if the install of wordpress in the test database is already executed.
 * A dummy binary is used which does nothing so that wordpress does not install again. Use path to bin/dummy.exe
 * dirname(__DIR__) . '/bin/dummy.exe'
 */
define( 'WP_PHP_BINARY', dirname(__DIR__) . '/bin/dummy.exe');

define( 'WPLANG', '' );

if ( ! defined( 'DISALLOW_FILE_MODS' ) ) {
    define( 'DISALLOW_FILE_MODS', true );
}

define('JWT_AUTH_CORS_ENABLE', true);
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );
define( 'WP_CONTENT_DIR', dirname(__DIR__) . '/public/wp-content' );
