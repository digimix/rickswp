<?php
$cat_overide = get_post_meta($post->ID, 'mix_supercat_override', 1);

$categories = get_the_category();
if ( ! empty( $categories ) ) {
	if ($cat_overide !=='') {
		$cat_name = esc_html(get_cat_name($cat_overide));
		$cat_link = esc_url( get_category_link($cat_overide));
	} else {
		$cat_name = esc_html( $categories[0]->name );
		$cat_link = esc_url( get_category_link( $categories[0]->term_id ) );
	}
    echo '<p class="meta"><a rel="category" href="' . $cat_link . '">' . $cat_name . '</a></p>';
} ?>
<time class="updated hidden" datetime="<?= get_post_time('c', true); ?>"><?= get_the_date(); ?></time>
<p class="byline author vcard hidden"><?= __('By', 'sage'); ?> <a href="<?= get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn"><?= get_the_author(); ?></a></p>