<?php

declare(strict_types=1);

if ( has_nav_menu( 'footer' ) || ! has_nav_menu( 'expanded' ) ) {
?>
<nav class="footer-menu-wrapper" aria-label="<?php esc_attr_e( 'Horizontal', 'carranker-theme' ); ?>" role="navigation">

    <ul class="footer-menu reset-list-style">

		<?php

		wp_nav_menu(array('theme_location' => 'footer'));

        ?>

    </ul> <?php
	}
	?>

</nav><!-- .footer-menu-wrapper -->
<?php

wp_footer(); ?>
</body>
</html>