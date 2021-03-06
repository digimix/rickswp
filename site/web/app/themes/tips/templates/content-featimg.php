<?php use Roots\Sage\Extras;
$override_img = get_post_meta($post->ID, 'mix_banner_override', 1);
$override_img_id = get_post_meta($post->ID, 'mix_banner_override_id', 1);
if (! empty($override_img)) {
	//$override_id = Extras\get_attachment_id_by_src($override_img);
	//$featimg = Extras\featured_url_by_id($override_id, 'large');
	$featimg = wp_get_attachment_image_url( $override_img_id, 'square', null, ['class' => 'thumb img-responsive'] );
} else {
	//$featimg = Extras\featured_url('large');
	$featimg = wp_get_attachment_image_url( get_post_thumbnail_id( $post->ID ) , 'large', null, ['class' => 'thumb img-responsive']);

}
?>
<figure <?php post_class(); ?> style="background:url(<?= $featimg; ?>) 50% 50%;background-size: cover;">
	<?php //the_post_thumbnail('full', ['class'=>'img-responsive']); ?>
	<!-- <img class="img-responsive wp-post-image" src="/app/themes/sofly/assets/images/Eklutna.jpg"> -->

	<figcaption>
		<div class="wrap">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<!--
			<div class="summary">
				<?php the_excerpt(); ?>
			</div>
			-->
		</div>
	</figcaption>

</figure>