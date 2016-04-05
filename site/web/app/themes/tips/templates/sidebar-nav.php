<?php use Roots\Soil\Nav; ?>
<div class="col-xs-6 col-sm-3 offcanvas-sidebar" id="sidebar">
	<button type="button" class="btn btn-dark btn-xs" data-toggle="offcanvas"><i class="icon ion-close-round"></i></button>
	<nav class="nav-primary">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav nav-stacked list-group visible-xs']);
      endif;
      ?>
	  <?php
	  if (has_nav_menu('popout_navigation')) :
	    wp_nav_menu(['theme_location' => 'popout_navigation', 'menu_class' => 'nav nav-stacked list-group']);
	  endif;
	  ?>
<!--
		<div class="nav list-group">
			<a href="#" class="list-group-item active">Link</a>
			<a href="#" class="list-group-item">Link</a>
			<a href="#" class="list-group-item">Link</a>
			<a href="#" class="list-group-item">Link</a>
			<a href="#" class="list-group-item">Link</a>
			<a href="#" class="list-group-item">Link</a>
			<a href="#" class="list-group-item">Link</a>
			<a href="#" class="list-group-item">Link</a>
			<a href="#" class="list-group-item">Link</a>
			<a href="#" class="list-group-item">Link</a>
		</div>
-->

	</nav>
	<H4 style="font-weight: bold;">Follow Us</H4>
      <?php
        if (has_nav_menu('social_navigation')) :
          wp_nav_menu(array('theme_location' => 'social_navigation', 'menu_class' => 'nav nav-pills m-auto', 'link_before' => '<span class="link-text">','link_after' => '</span>'));
        endif;
      ?>

</div>