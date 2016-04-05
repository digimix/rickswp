<?php $upload_dir = wp_upload_dir(); ?>
<article <?php post_class(); ?>>
	<header>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php get_template_part('templates/entry-meta'); ?>
	</header>
	<figure>
		<a href="<?php the_permalink(); ?>">
		<?php if (get_post_thumbnail_id() == '') {?>
			<img class="img-responsive wp-post-image" src="<?= $upload_dir['baseurl'] . '/no-image-available.jpg';?>">
		<?php } else {
			the_post_thumbnail(['750','9999'], ['class'=>'img-responsive']);
		} ?>
		</a>
	</figure>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
	<div class="action-items">
		<div>
			<a class="read-more" href="<?php the_permalink(); ?>">Read More</a>
			<?php get_template_part('templates/entry-share'); ?>
		</div>
		<?php
		if(get_the_tag_list()) {
		echo get_the_tag_list('<p class="hashtags"># ',', ','</p>');
		}
		?>
	</div>
</article>