<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Enable theme features
 */
add_theme_support('soil-clean-up');         // Enable clean up from Soil
add_theme_support('soil-nav-walker');       // Enable cleaner nav walker from Soil
add_theme_support('soil-relative-urls');    // Enable relative URLs from Soil
add_theme_support('soil-nice-search');      // Enable nice search from Soil
//add_theme_support('soil-jquery-cdn');       // Enable to load jQuery from the Google CDN
//add_theme_support('soil-google-analytics', 'UA-XXXXX-Y');

require_once locate_template('/lib/metabox.php');
require_once locate_template('/lib/options.php');


/*
function mix_adjust_offset_pagination($found_posts, $query) {
    if ( $query->is_home() && $query->is_main_query() ) {
        return $found_posts - 5;
    }
    return $found_posts;
}
add_filter('found_posts', __NAMESPACE__ . '\\mix_adjust_offset_pagination');
*/

/*
function wpsites_exclude_latest_post($query) {
	if ($query->is_home() && $query->is_main_query()) {
		$query->set( 'offset', '1' );
	}
}

add_action('pre_get_posts', __NAMESPACE__ . '\\wpsites_exclude_latest_post');
*/

/*
add_action('pre_get_posts', __NAMESPACE__ . '\\myprefix_query_offset', 1 );
function myprefix_query_offset(&$query) {

    //Before anything else, make sure this is the right query...
    if ( $query->is_category() && $query->is_main_query() ) {
        return;
    }

    //First, define your desired offset...
    $offset = 1;

    //Next, determine how many posts per page you want (we'll use WordPress's settings)
    $ppp = get_option('posts_per_page');

    //Next, detect and handle pagination...
    if ( $query->is_paged ) {

        //Manually determine page query offset (offset + current page (minus one) x posts per page)
        $page_offset = $offset + ( ($query->query_vars['paged']-1) * $ppp );

        //Apply adjust page offset
        $query->set('offset', $page_offset );

    }
    else {

        //This is the first page. Just use the offset...
        $query->set('offset',$offset);

    }
}
*/

add_filter('found_posts', __NAMESPACE__ . '\\mix_adjust_offset_pagination', 1, 2 );
function mix_adjust_offset_pagination($found_posts, $query) {

    //Define our offset again...
    $offset = 1;

    //Ensure we're modifying the right query object...
    if ( $query->is_category() && $query->is_main_query() ) {
        //Reduce WordPress's found_posts count by the offset...
        return $found_posts - $offset;
    }
    return $found_posts;
}



/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');


/**
 * Update Login Logo
 * --------------------------
 */

// change url for login logo
function put_my_url(){
    return (get_bloginfo('url')); // website url
}
add_filter('login_headerurl',  __NAMESPACE__ . '\\put_my_url');

/*
 * Builds custom HTML
 *
 * With this function, I can alter WPP's HTML output from my theme's functions.php.
 * This way, the modification is permanent even if the plugin gets updated.
 *
 * @param   array   $mostpopular
 * @param   array   $instance
 * @return  string
 */
function my_custom_popular_posts_html_list( $mostpopular, $instance ){
    $output = '<ul class="wpp-list">';

    // loop the array of popular posts objects
    foreach( $mostpopular as $popular ) {

        $stats = array(); // placeholder for the stats tag

        // Comment count option active, display comments
        if ( $instance['stats_tag']['comment_count'] ) {
            // display text in singular or plural, according to comments count
            $stats[] = '<span class="wpp-comments">' . sprintf(
                _n('1 comment', '%s comments', $popular->comment_count, 'wordpress-popular-posts'),
                number_format_i18n($popular->comment_count)
            ) . '</span>';
        }

        // Pageviews option checked, display views
        if ( $instance['stats_tag']['views'] ) {

            // If sorting posts by average views
            if ($instance['order_by'] == 'avg') {
                // display text in singular or plural, according to views count
                $stats[] = '<span class="wpp-views num">' . sprintf(
                    _n('1 view per day', '%s views per day', intval($popular->pageviews), 'wordpress-popular-posts'),
                    number_format_i18n($popular->pageviews, 2)
                ) . '</span>';
            } else { // Sorting posts by views
                // display text in singular or plural, according to views count
                $stats[] = '<span class="wpp-views num">' . sprintf(
                    _n('1', '%s', intval($popular->pageviews), 'wordpress-popular-posts'),
                    number_format_i18n($popular->pageviews)
                ) . '</span>';
            }
        }

        // Author option checked
        if ($instance['stats_tag']['author']) {
            $author = get_the_author_meta('display_name', $popular->uid);
            $display_name = '<a href="' . get_author_posts_url($popular->uid) . '">' . $author . '</a>';
            $stats[] = '<span class="wpp-author">' . sprintf(__('by %s', 'wordpress-popular-posts'), $display_name). '</span>';
        }

        // Date option checked
        if ($instance['stats_tag']['date']['active']) {
            $date = date_i18n($instance['stats_tag']['date']['format'], strtotime($popular->date));
            $stats[] = '<span class="wpp-date">' . sprintf(__('posted on %s', 'wordpress-popular-posts'), $date) . '</span>';
        }

        // Category option checked
        if ($instance['stats_tag']['category']) {
            $post_cat = get_the_category($popular->id);
            $post_cat = (isset($post_cat[0]))
              ? '<a href="' . get_category_link($post_cat[0]->term_id) . '">' . $post_cat[0]->cat_name . '</a>'
              : '';

            if ($post_cat != '') {
                $stats[] = '<span class="wpp-category">' . sprintf(__('under %s', 'wordpress-popular-posts'), $post_cat) . '</span>';
            }
        }

        // Build stats tag
        if ( !empty($stats) ) {
            //$stats = '<div class="wpp-stats">' . join( ' | ', $stats ) . '</div>';
            $stats = join( ' | ', $stats );
        }

        $excerpt = ''; // Excerpt placeholder

        // Excerpt option checked, build excerpt tag
        if ($instance['post-excerpt']['active']) {

            $excerpt = get_excerpt_by_id( $popular->id );
            if ( !empty($excerpt) ) {
                $excerpt = '<div class="wpp-excerpt">' . $excerpt . '</div>';
            }

        }

        // Excerpt option checked, build excerpt tag
        if ($instance['thumbnail']['active']) {

            $thumb = get_the_post_thumbnail($popular->id, ['36','36']);
            if ( !empty($thumb) ) {
                $thumb = $thumb;
            }

        }


        $output .= "<li>";
        $output .= "<a href=\"" . get_the_permalink( $popular->id ) . "\" title=\"" . esc_attr( $popular->title ) . "\">" . $stats . $thumb . "<span class=\"post-title\">" . $popular->title . "</span></a>";
        //$output .= $stats;
        //$output .= $excerpt;
        $output .= "</li>" . "\n";

    }

    $output .= '</ul>';

    return $output;
}

add_filter( 'wpp_custom_html', __NAMESPACE__ . '\\my_custom_popular_posts_html_list', 10, 2 );