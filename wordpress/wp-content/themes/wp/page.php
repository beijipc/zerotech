<?php get_header(); ?>

<?php
// Start the loop.
while ( have_posts() ) : the_post();

the_title();
the_date();
the_content();
echo "<hr>";

	// End of the loop.
endwhile;
?>


<?php get_footer(); ?>
