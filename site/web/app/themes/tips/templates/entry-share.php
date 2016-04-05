<?php
	$share_link = get_permalink($post->ID);
	$share_title = urlencode(get_the_title($post->ID));
	$share_img = get_the_post_thumbnail_url($post->ID, 'medium');
?>

<ul class="share">
	<li>
		<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_link; ?>" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a>
	</li>
	<li>
		<a href="https://twitter.com/home?status=<?php echo $share_title; ?>%20<?php echo $share_link; ?>" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a>
	</li>
	<li>
		<a href="http://pinterest.com/pin/create/button/?url=<?php echo $share_link; ?>&media=<?php echo $share_img; ?>&description=<?php echo $share_title; ?>" class="pin-it-button" target="_blank" rel="nofollow" count-layout="horizontal"><i class="fa fa-pinterest"></i></a>
	</li>

	<li>
		<a href="mailto:?&body=<?php echo $share_link; ?>" target="_blank" rel="nofollow"><i class="fa fa-envelope"></i></a>
	</li>
</ul>