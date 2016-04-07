<?php get_header(); ?>

<article class="">
	<?php
	while ( have_posts() ) : the_post();
		// Include the page content template.
		// get_template_part( 'template-parts/content', 'page' );
		the_title();
		the_date();
		the_content();
		echo "<hr>";
		// End of the loop.
	endwhile;
	?>
</article>
<?php get_footer(); ?>
