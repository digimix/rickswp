<div class="featured banner">
	<?php
	$current_cat = get_query_var('cat');
	$args = array( 'posts_per_page' => 1, 'category' => $current_cat );

	$myposts = get_posts( $args );

	if ( is_single() ) {

		foreach ( $myposts as $post ) : setup_postdata( $post ); ?>

			  <?php get_template_part('templates/content-featimg'); ?>

		<?php endforeach;
		wp_reset_postdata();

	} else {

		foreach ( $myposts as $post ) : setup_postdata( $post ); ?>

			  <?php get_template_part('templates/content-mosaic'); ?>

		<?php endforeach;
		wp_reset_postdata();

	}
	?>
</div>