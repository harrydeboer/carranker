<?php

declare( strict_types=1 );

$_GET['step'] = 1;
define( 'WP_INSTALLING', true );

/** Load WordPress Bootstrap */
require __DIR__ . "/wordpress/wp/wp-load.php";

require_once(__DIR__ . "/wordpress/wp/wp-admin/includes/upgrade.php");

wp_upgrade();