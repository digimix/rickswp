<article <?php post_class(); ?>>
	<header>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php get_template_part('templates/entry-meta'); ?>
	</header>
	<div class="entry-summary">
		<?php
		//If your post format is set to 'video'
		if ( has_post_format( 'video' )) {

			// create a variable named $video that contains the text inside of your field named 'video' that was created by pods
			//$video = get_post_meta($post->ID, 'video', true);

			//run the $video variable through the magical 'wp_oembed_get' function to automagically generate an embed frame and set the width
			//$embed_code = wp_oembed_get($video, array('width'=>1010));

			// output the contents of this magic!
			//echo ($embed_code);

			$media = get_media_embedded_in_content(apply_filters( 'the_content', get_the_content() ));
			echo '<div class="oembed-video"><div class="content">' . $media[0] . '</div></div>';
			the_excerpt();

		//If your post format is NOT set to video and has a featured image set
		} elseif ( has_post_format( 'video' ) && has_post_thumbnail() ) {

			//display the featured image with the size 'thumbnail'
			the_post_thumbnail( 'thumbnail', $default_attr);

			the_excerpt();
		}

		?>
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