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
} elseif ($post->post_name == 'opcachereset' && (APP_ENV !== 'local' && APP_ENV !== 'testing')
    && in_array( 'administrator', (array) $user->roles )) {
    opcache_reset();
    echo 'Opcache reset!';
}

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
    <title><?php echo $post->post_title; ?></title>
</head>

<body>