</main>

<footer>
	<?php if ( has_nav_menu( 'secondary' ) ) : ?>
		<nav role="navigation">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'secondary',
					'menu_class'     => 'secondary-links-menu',
					'depth'          => 1,
					'link_before'    => '<span class="screen-reader-text">',
					'link_after'     => '</span>',
				) );
			?>
		</nav>
	<?php endif; ?>

</footer>


<?php //wp_footer(); ?>
</body>
</html>
