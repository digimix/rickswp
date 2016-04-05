<?php use Roots\Sage\Extras;
$incident_img_id = Extras\get_attachment_id_by_src(mix_get_option('incident_img'));
$incident_img = Extras\featured_url_by_id($incident_img_id, 'large');
?>
<figure <?php post_class(); ?> style="background:url(<?= $incident_img; ?>) 50% 50%;background-size: cover;">
	<figcaption>
		<a href="<?php echo mix_get_option('incident_url'); ?>">
			<?php if (!empty(mix_get_option('incident_caption') ) ) { ?>
			<div class="wrap">
				<h2 class="entry-title"><?php echo mix_get_option('incident_caption'); ?></h2>
				<div class="summary">
					<p class="read-more">Read More</p>
				</div>
			</div>
			 <?php } ?>
		</a>
	</figcaption>
</figure>