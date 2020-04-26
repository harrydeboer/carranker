<?php

declare( strict_types=1 );

/** When wordpress upgrades it calls wp-admin/update-core.php
 * When only the database has to upgrade it calls wp-admin/upgrade.php
 * In that file only wp_upgrade() is needed. Wordpress and the necessary functions have to be loaded first.
 * When the database needs no upgrade then this functions will do nothing.
 */
define( 'WP_INSTALLING', true );

/** Load WordPress Bootstrap */
require __DIR__ . "/wordpress/wp/wp-load.php";

require_once(__DIR__ . "/wordpress/wp/wp-admin/includes/upgrade.php");

wp_upgrade();