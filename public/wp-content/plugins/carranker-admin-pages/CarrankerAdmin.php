<?php

declare(strict_types=1);

namespace CarrankerAdmin;

class CarrankerAdmin
{
	public function __construct()
	{
		global $pagenow;

		/** For the tables profanities, mail users, makes, models and trims an admin menu entry is added. */
		add_action( 'admin_menu', array($this, 'my_admin_makes' ));
		add_action( 'admin_menu', array($this, 'my_admin_models' ));
		add_action( 'admin_menu', array($this, 'my_admin_trims' ));
		add_action( 'admin_menu', array($this, 'my_admin_profanities' ));
		add_action( 'admin_menu', array($this, 'my_admin_mail_users' ));

		if ($pagenow === 'admin.php' && (
		    $_GET['page'] === 'make-admin-page' ||
		    $_GET['page'] === 'model-admin-page' ||
		    $_GET['page'] === 'trim-admin-page' ||
		    $_GET['page'] === 'profanity-admin-page' ||
		    $_GET['page'] === 'mail-user-admin-page')
		) {
			add_action( 'admin_head', array( $this, 'add_bootstrap_css_to_head' ) );
		}
	}

	public function add_bootstrap_css_to_head()
	{ ?>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<?php
	}

	public function my_admin_profanities()
	{
		add_menu_page( 'Profanities', 'Profanities', 'delete_posts', 'profanity-admin-page', array($this, 'admin_page' ));
	}

	public function my_admin_trims()
	{
		add_menu_page( 'Trims', 'Trims', 'delete_posts', 'trim-admin-page', array($this, 'admin_page' ));
	}

	public function my_admin_models()
	{
		add_menu_page( 'Models', 'Models', 'delete_posts', 'model-admin-page', array($this, 'admin_page' ));
	}

	public function my_admin_makes()
	{
		add_menu_page( 'Makes', 'Makes', 'delete_posts', 'make-admin-page', array($this, 'admin_page' ));
	}

	public function my_admin_mail_users()
	{
		add_menu_page( 'Mail Users', 'Mail Users', 'ure_delete_roles', 'mail-user-admin-page', array($this, 'admin_page' ));
	}

	public function admin_page()
	{
		require_once __DIR__ . '/library/routing.php';
	}
}