<?php if ( has_nav_menu( 'primary' ) ) : ?>
	<nav>
		<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php get_search_form(); ?>
	</nav>
<?php endif; ?>
