<?php

declare(strict_types=1);

namespace CarrankerAdmin;

class CarrankerAdmin
{
    public function __construct()
    {
        /** For the tables parameters, specs_dropdown, specs_minmax, makes, models and trims an admin menu entry is added. */
        add_action( 'admin_menu', array($this, 'my_admin_makes' ));
        add_action( 'admin_menu', array($this, 'my_admin_models' ));
        add_action( 'admin_menu', array($this, 'my_admin_trims' ));
        add_action( 'admin_menu', array($this, 'my_admin_profanities' ));
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

    public function admin_page()
    {
        require_once __DIR__ . '/library/routing.php';
    }
}