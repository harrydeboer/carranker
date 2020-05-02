<?php

declare( strict_types=1 );

/** When wordpress upgrades it calls wp-admin/update-core.php
 * When only the database has to upgrade it calls wp-admin/upgrade.php
 * In that file only wp_upgrade() is needed. Wordpress and the necessary functions have to be loaded first.
 * When the database needs no upgrade then this functions will do nothing.
 */
define('WP_INSTALLING', true);

/** Load WordPress Bootstrap */
require __DIR__ . "/wordpress/wp/wp-load.php";

require_once(__DIR__ . "/wordpress/wp/wp-admin/includes/upgrade.php");

wp_upgrade();

if (get_locale() !== 'en_US') {
	require_once __DIR__ . '/wordpress/wp/wp-admin/includes/class-wp-upgrader.php';

	$url     = 'update-core.php?action=do-translation-upgrade';
	$nonce   = 'upgrade-translations';
	$title   = __( 'Update Translations' );
	$context = WP_LANG_DIR;

	$upgrader = new Language_Pack_Upgrader( new Language_Pack_Upgrader_Skin( [
		'url'                => $url,
		'nonce'              => $nonce,
		'title'              => $title,
		'context'            => $context,
		'skip_header_footer' => true,
	] ) );

	$upgrader->bulk_upgrade();
}

echo "Wordpress database upgraded and updated translations!\r\n";