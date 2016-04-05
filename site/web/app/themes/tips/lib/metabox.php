<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'mix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

/**
 * Gets a number of terms and displays them as options
 * @param  string       $taxonomy Taxonomy terms to retrieve. Default is category.
 * @param  string|array $args     Optional. get_terms optional arguments
 * @return array                  An array of options that matches the CMB2 options array
 */
function cmb2_get_term_options( $taxonomy = 'category', $args = array() ) {

    $args['taxonomy'] = $taxonomy;
    // $defaults = array( 'taxonomy' => 'category' );
    $args = wp_parse_args( $args, array( 'taxonomy' => 'category' ) );

    $taxonomy = $args['taxonomy'];

    $terms = (array) get_terms( $taxonomy, $args );

    // Initate an empty array
    $term_options = array();
    if ( ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $term_options[ $term->term_id ] = $term->name;
        }
    }

    return $term_options;
}

/**
 * Gets a number of posts and displays them as options
 * @param  array $query_args Optional. Overrides defaults.
 * @return array             An array of options that matches the CMB2 options array
 */
function mix_get_post_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'post',
        'numberposts' => 10,
    ) );

    $posts = get_posts( $args );

    $post_options = array();
    if ( $posts ) {
        foreach ( $posts as $post ) {
          $post_options[ $post->ID ] = $post->post_title;
        }
    }

    return $post_options;
}

/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 object $cmb CMB2 object
 *
 * @return bool             True if metabox should show
 */
function mix_show_if_front_page( $cmb ) {
	// Don't show this metabox if it's not the front page template
	if ( $cmb->object_id !== get_option( 'page_on_front' ) ) {
		return false;
	}
	return true;
}

/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 object $cmb CMB2 object
 *
 * @return bool             True if metabox should show
 */
function mix_show_if_home_page( $cmb ) {
	// Don't show this metabox if it's not the front page template
	if ( $cmb->object_id !== get_option( 'show_on_front' ) ) {
		return false;
	}
	return true;
}


/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field object $field Field object
 *
 * @return bool                     True if metabox should show
 */
function mix_hide_if_no_cats( $field ) {
	// Don't show this field if not in the cats category
	if ( ! has_tag( 'cats', $field->object_id ) ) {
		return false;
	}
	return true;
}

/**
 * Conditionally displays a message if the $post_id is 2
 *
 * @param  array             $field_args Array of field parameters
 * @param  CMB2_Field object $field      Field object
 */
function mix_before_row_if_2( $field_args, $field ) {
	if ( 2 == $field->object_id ) {
		echo '<p>Testing <b>"before_row"</b> parameter (on $post_id 2)</p>';
	} else {
		echo '<p>Testing <b>"before_row"</b> parameter (<b>NOT</b> on $post_id 2)</p>';
	}
}



add_action( 'cmb2_admin_init', 'mix_register_about_page_metabox' );
/**
 * Hook in and add a metabox that only appears on the 'About' page
 */
function mix_register_about_page_metabox() {
	$prefix = 'mix_about_';

	/**
	 * Metabox to be displayed on a single page ID
	 */
	$cmb_about_page = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'About Page Metabox', 'cmb2' ),
		'object_types' => array( 'page', ), // Post type
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, // Show field names on the left
		'show_on'      => array( 'id' => array( 2, ) ), // Specific post IDs to display this metabox
	) );

	$cmb_about_page->add_field( array(
		'name' => __( 'Test Text', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'text',
		'type' => 'text',
	) );
}


add_action( 'cmb2_admin_init', 'mix_register_banner_override_metabox' );
/**
 * Hook in and add a metabox that only appears on the 'About' page
 */
function mix_register_banner_override_metabox() {
	$prefix = 'mix_banner_';

	/**
	 * Metabox to be displayed on a single page ID
	 */
	$cmb_about_page = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Banner Override Image', 'cmb2' ),
		'object_types' => array( 'post', 'listicle'), // Post type
		'context'      => 'side', //  'normal', 'advanced', or 'side'
		'priority'     => 'low', //  'high', 'core', 'default' or 'low'
		'show_names'   => false, // Show field names on the left
		//'show_on'      => array( 'id' => array( 2, ) ), // Specific post IDs to display this metabox
	) );

	$cmb_about_page->add_field( array(
		'name' => __( 'Image', 'cmb2' ),
		'desc' => __( 'Override Featured Image for Full Page Hero where applicable (optional)', 'cmb2' ),
		'id'   => $prefix . 'override',
		'type' => 'file',
	) );
}


add_action( 'cmb2_admin_init', 'mix_register_supercat_override_metabox' );
/**
 * Hook in and add a metabox that only appears on the 'About' page
 */
function mix_register_supercat_override_metabox() {
	$prefix = 'mix_supercat_';

	/**
	 * Metabox to be displayed on a single page ID
	 */
	$cmb_supercat = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'SuperCat Override', 'cmb2' ),
		'object_types' => array( 'post', 'listicle' ), // Post type
		'context'      => 'side', //  'normal', 'advanced', or 'side'
		'priority'     => 'low', //  'high', 'core', 'default' or 'low'
		'show_names'   => false, // Show field names on the left
		//'show_on'      => array( 'id' => array( 2, ) ), // Specific post IDs to display this metabox
	) );

	$cmb_supercat->add_field( array(
	    'name'    => 'SuperCat Term',
	    'desc'    => 'Set a supercat override for this post.',
	    'id'      => $prefix . 'override',
	    'type'    => 'select',
	    'show_option_none' => true,
	    'options' => cmb2_get_term_options(),
	) );
}



add_action( 'cmb2_admin_init', 'mix_register_incident_metabox' );
/**
 * Hook in and add a metabox that only appears on the 'About' page
 */
function mix_register_incident_metabox() {
	$prefix = 'mix_incident_';

	/**
	 * Metabox to be displayed on a single page ID
	 */
	$cmb_incident = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Incident Message [Hero Override]', 'cmb2' ),
		'object_types' => array( 'page', ), // Post type
		'context'      => 'normal', //  'normal', 'advanced', or 'side'
		'priority'     => 'low', //  'high', 'core', 'default' or 'low'
		'show_names'   => true, // Show field names on the left
		'show_on'      => array( 'id' => 21 ), // Specific post IDs to display this metabox
	) );

	$cmb_incident->add_field( array(
		'name' => __( 'Title', 'cmb2' ),
		//'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'title',
		'type' => 'text',
	) );
	$cmb_incident->add_field( array(
		'name' => __( 'URL', 'cmb2' ),
		//'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'url',
		'type' => 'text_url',
	) );
	$cmb_incident->add_field( array(
		'name' => __( 'Image', 'cmb2' ),
		//'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'img',
		'type' => 'file',
	) );
	$cmb_incident->add_field( array(
		'name'             => __( 'Status', 'cmb2' ),
		//'desc'             => __( 'field description (optional)', 'cmb2' ),
		'id'               => $prefix . 'status',
		'type'             => 'radio_inline',
		'show_option_none' => false,
		'default' => 'inactive',
		'options'          => array(
			'inactive' => __( 'Inactive', 'cmb2' ),
			'fw'   => __( 'Full Width', 'cmb2' ),
			'box'     => __( 'Box 1', 'cmb2' ),
		),
	) );
}


add_action( 'cmb2_admin_init', 'mix_register_user_profile_metabox' );
/**
 * Hook in and add a metabox to add fields to the user profile pages
 */
function mix_register_user_profile_metabox() {
	$prefix = 'mix_user_';

	/**
	 * Metabox for the user profile screen
	 */
	$cmb_user = new_cmb2_box( array(
		'id'               => $prefix . 'edit',
		'title'            => __( 'User Profile Metabox', 'cmb2' ),
		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta vs post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
	) );

	$cmb_user->add_field( array(
		'name'     => __( 'Extra Info', 'cmb2' ),
		'desc'     => __( 'field description (optional)', 'cmb2' ),
		'id'       => $prefix . 'extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );

	$cmb_user->add_field( array(
		'name'    => __( 'Avatar', 'cmb2' ),
		'desc'    => __( 'field description (optional)', 'cmb2' ),
		'id'      => $prefix . 'avatar',
		'type'    => 'file',
	) );

	$cmb_user->add_field( array(
		'name' => __( 'Facebook URL', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'facebookurl',
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => __( 'Twitter URL', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'twitterurl',
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => __( 'Google+ URL', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'googleplusurl',
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => __( 'Linkedin URL', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'linkedinurl',
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => __( 'User Field', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'user_text_field',
		'type' => 'text',
	) );

}



add_action( 'cmb2_admin_init', 'mix_register_repeatable_group_field_metabox' );
/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields
 */
function mix_register_repeatable_group_field_metabox() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = 'mix_listicle_';
	/**
	 * Repeatable Field Groups
	 */
	$cmb_listicle = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Listicles', 'cmb2' ),
		'object_types' => array( 'listicle', ),
	) );
	// $listicle_field_id is the field id string, so in this case: $prefix . 'demo'
	$listicle_field_id = $cmb_listicle->add_field( array(
		'id'          => $prefix . 'group',
		'type'        => 'group',
		'description' => __( 'Generates reusable form entries', 'cmb2' ),
		'options'     => array(
			'group_title'   => __( 'Listicle Article #{#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'    => __( 'Add Another Entry', 'cmb2' ),
			'remove_button' => __( 'Remove Entry', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );
	/**
	 * Group fields works the same, except ids only need
	 * to be unique to the group. Prefix is not needed.
	 *
	 * The parent field's id needs to be passed as the first argument.
	 */
	$cmb_listicle->add_group_field( $listicle_field_id, array(
		'name'       => __( 'Entry Title', 'cmb2' ),
		'id'         => 'title',
		'type'       => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );
	$cmb_listicle->add_group_field( $listicle_field_id, array(
		'name'        => __( 'Description', 'cmb2' ),
		'description' => __( 'Write a short description for this entry', 'cmb2' ),
		'id'          => 'description',
		'type'        => 'textarea_small',
	) );
	$cmb_listicle->add_group_field( $listicle_field_id, array(
		'name' => __( 'Entry Image', 'cmb2' ),
		'id'   => 'image',
		'type' => 'file',
	) );
}



/**
 * Hook in and register a metabox to handle a theme options page
 */
function mix_register_theme_options_metabox() {

	$option_key = 'mix_theme_options';

	/**
	 * Metabox for an options page. Will not be added automatically, but needs to be called with
	 * the `cmb2_metabox_form` helper function. See wiki for more info.
	 */
	$cmb_options = new_cmb2_box( array(
		'id'      => $option_key . 'page',
		'title'   => __( 'Theme Options Metabox', 'cmb2' ),
		'hookup'  => false, // Do not need the normal user/post hookup
		'show_on' => array(
			// These are important, don't remove
			'key'   => 'options-page',
			'value' => array( $option_key )
		),
	) );

	/**
	 * Options fields ids only need
	 * to be unique within this option group.
	 * Prefix is not needed.
	 */
	$cmb_options->add_field( array(
		'name'    => __( 'Site Background Color', 'cmb2' ),
		'desc'    => __( 'field description (optional)', 'cmb2' ),
		'id'      => 'bg_color',
		'type'    => 'colorpicker',
		'default' => '#ffffff',
	) );

}