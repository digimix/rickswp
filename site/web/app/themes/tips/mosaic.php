<?php
	$attached = mix_get_option('mosaic_attached_posts');
	$incident_status = mix_get_option('incident_status');
	if ( $incident_status == 'fw') { ?>
<div class="featured banner incident-message">
	<?php get_template_part('templates/content', 'incident'); ?>
</div>
<?php } else if ($incident_status == 'mosaic') {  ?>
<ul class="mosaic">
	<li class="incident-message">
		<?php get_template_part('templates/content', 'incident'); ?>
	</li>

	<?php
	$args = array( 'posts_per_page' => 3, 'include' => $attached );

	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
		<li>
			<?php get_template_part('templates/content', 'mosaic'); ?>
		</li>
	<?php endforeach;
	wp_reset_postdata();?>

</ul>
<?php } else { ?>
	<ul class="mosaic">
	<?php

	$args = array( 'posts_per_page' => 4, 'include' => $attached );

	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
		<li>
			<?php get_template_part('templates/content', 'mosaic'); ?>
		</li>
	<?php endforeach;
	wp_reset_postdata();?>

	</ul>
<?php } ?>