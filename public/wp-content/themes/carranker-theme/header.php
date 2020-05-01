<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Carranker_Theme
 * @since 1.0.0
 */
declare(strict_types=1);

$user = wp_get_current_user();
if ( !in_array( 'administrator', (array) $user->roles ) && !in_array( 'editor', (array) $user->roles ) ) {
	wp_redirect('/wp-admin');
}

$post = get_post();
if ($post->post_name == 'phpinfo' && in_array( 'administrator', (array) $user->roles )) {
	phpinfo();
	exit;
} ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
    <title><?php echo $post->post_title; ?></title>
</head>

<body>
<?php
if ( has_nav_menu( 'primary' ) || ! has_nav_menu( 'expanded' ) ) {
?>

<nav class="primary-menu-wrapper" aria-label="<?php esc_attr_e( 'Horizontal', 'carranker-theme' ); ?>" role="navigation">

    <ul class="primary-menu reset-list-style">

		<?php

		wp_nav_menu(array('theme_location' => 'menu-1'));

        ?>

    </ul> <?php
	}
	?>

</nav><!-- .primary-menu-wrapper -->