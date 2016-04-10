<?php get_header(); ?>
<h2>【product】</h2>
<p>
	<?php
	if(isset($_GET['branch'])){
		$branch = $_GET['branch'];
		switch ($branch) {
			case 'download':
				echo "download";
				break;
			case 'faq':
				echo "faq";
				break;
			case 'params':
				echo "params";
				break;
			default:
				# code...
				break;
		}
	}
	 ?>
</p>
	<?php
	// Start the loop.
	while ( have_posts() ) : the_post();

		// Include the single post content template.
		the_title();
		the_date();
		the_content();

		//关联产品
		$connected = new WP_Query( array(
			'connected_type'		=>	'download_to_product',
			'connected_items'		=>	get_queried_object(),
			'nopaging'					=>	true,
		) );
		echo "<h4>相关产品</h4>";
		while( $connected->have_posts() ): $connected->the_post();
			the_title();
		endwhile;
		wp_reset_postdata();

		if ( is_singular( 'attachment' ) ) {
			// Parent post navigation.
			the_post_navigation( array(
				'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'twentysixteen' ),
			) );
		} elseif ( is_singular( 'post' ) ) {
			// Previous/next post navigation.
			the_post_navigation( array(
				'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentysixteen' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Next post:', 'twentysixteen' ) . '</span> ' .
					'<span class="post-title">%title</span>',
				'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentysixteen' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Previous post:', 'twentysixteen' ) . '</span> ' .
					'<span class="post-title">%title</span>',
			) );
		}

		// End of the loop.
	endwhile;
	?>

<?php get_footer(); ?>
