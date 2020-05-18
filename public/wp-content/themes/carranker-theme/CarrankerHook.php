<?php

declare(strict_types=1);

class CarrankerHook
{
    public function __construct()
    {
        global $wpdb;
        
        add_filter( 'rest_authentication_errors', array($this, 'rest_authentication_required_by_admin' ));
        add_action( 'rest_api_init', array($this, 'register_route' ));

        $wpdb->hide_errors();

        define('DISALLOW_FILE_EDIT', true);

        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('template_redirect', 'rest_output_link_header', 11);

        add_filter('xmlrpc_enabled', '__return_false');

        add_filter('the_generator', array($this, 'remove_version' ));

        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(
            array(
                'menu-1' => __( 'Primary', 'carranker-theme' ),
                'footer' => __( 'Footer Menu', 'carranker-theme' ),
                'social' => __( 'Social Links Menu', 'carranker-theme' ),
            )
        );

        register_sidebar(
            array(
                'name'          => __( 'Footer', 'carranker-theme' ),
                'id'            => 'sidebar-1',
                'description'   => __( 'Add widgets here to appear in your footer.', 'carranker-theme' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );
    }

    public function remove_version() {
        return '';
    }

    public function register_route()
    {
        register_rest_route( 'myroutes', '/allmenus', [
            'methods' => 'GET',
            'callback' => array($this, 'get_api_menus'),
        ]);
    }

    public function get_api_menus()
    {
        $locations = get_nav_menu_locations();
        $menus = [];
        foreach ($locations as $location) {
            if ($location !== 0) {
                $menuObject = get_term($location);
                $menuItem = wp_get_nav_menu_items($location);
                $menus[$menuObject->name] = $menuItem;
            }
        }

        return $menus;
    }

    public function rest_authentication_required_by_admin($result)
    {
        if ( ! empty( $result ) ) {
            return $result;
        }
        $rest_route = $GLOBALS['wp']->query_vars['rest_route'];
        if ($rest_route === '/jwt-auth/v1/token' || $rest_route === '/jwt-auth/v1/token/validate') {
            return $result;
        }
        $user = wp_get_current_user();
        if ( !is_user_logged_in() || (is_user_logged_in() && !in_array( 'administrator', (array) $user->roles )) ) {
            return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in as administrator.', array( 'status' => 401 ) );
        }
        return $result;
    }
}