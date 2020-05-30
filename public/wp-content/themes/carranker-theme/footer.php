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

</nav>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
        crossorigin="anonymous"></script>
<?php

wp_footer(); ?>
</body>
</html>