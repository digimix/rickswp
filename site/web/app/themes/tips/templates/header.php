<?php use Roots\Soil\Nav; ?>
<header class="banner navbar navbar-blue navbar-static-top topHeader">
  <div class="">
    <a class="brand" href="<?= esc_url(home_url('/')); ?>" style="float: left;margin-left: 3%;text-decoration: none;">
	    <img class="main-logo" src="<?= mix_get_option('header_logo'); ?>">
	    <img class="sticky-logo" src="<?= mix_get_option('header_logo_scroll'); ?>">
	    <?php //bloginfo('name'); ?>
	</a>
	<div class="header-search">
        <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">
			<span class="sr-only"><?= __('Toggle navigation', 'sage'); ?></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
        </button>
		<?php //get_search_form(); ?>

		<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
			<input id="search-box" type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
			<label for="search-box">
				<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
				<span class="glyphicon glyphicon-search search-icon"></span>
				<!-- <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" /> -->
			</label>
			<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
		</form>

	</div>
    <nav class="nav-primary">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav hidden-xs']);
      endif;
      ?>
    </nav>
  </div>
</header>