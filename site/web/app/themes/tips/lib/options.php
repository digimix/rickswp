<?php
/**
 * CMB Tabbed Theme Options
 *
 * @author    Arushad Ahmed <@dash8x, contact@arushad.org>
 * @link      http://arushad.org/how-to-create-a-tabbed-options-page-for-your-wordpress-theme-using-cmb
 * @version   0.1.0
 */
class mix_Admin {

    /**
     * Default Option key
     * @var string
     */
    private $key = 'mix_options';

    /**
     * Array of metaboxes/fields
     * @var array
     */
    protected $option_metabox = array();

    /**
     * Options Page title
     * @var string
     */
    protected $title = '';

    /**
     * Options Tab Pages
     * @var array
     */
    protected $options_pages = array();

    /**
     * Constructor
     * @since 0.1.0
     */
    public function __construct() {
        // Set our title
        $this->title = __( 'Site Options', 'mix' );
    }

    /**
     * Initiate our hooks
     * @since 0.1.0
     */
    public function hooks() {
        add_action( 'admin_init', array( $this, 'init' ) );
        add_action( 'admin_menu', array( $this, 'add_options_page' ) ); //create tab pages
        add_action( 'admin_bar_menu', array( $this, 'custom_toolbar_link' ), 100 ); //create tab pages
    }

    /**
     * Register our setting tabs to WP
     * @since  0.1.0
     */
    public function init() {
    	$option_tabs = self::option_fields();
        foreach ($option_tabs as $index => $option_tab) {
        	register_setting( $option_tab['id'], $option_tab['id'] );
        }
    }

    /**
     * Add menu options page
     * @since 0.1.0
     */
    public function add_options_page() {
        $option_tabs = self::option_fields();
        foreach ($option_tabs as $index => $option_tab) {
        	if ( $index == 0) {
        		$this->options_pages[] = add_menu_page( $this->title, $this->title, 'manage_options', $option_tab['id'], array( $this, 'admin_page_display' ) ); //Link admin menu to first tab
        		add_submenu_page( $option_tabs[0]['id'], $this->title, $option_tab['title'], 'manage_options', $option_tab['id'], array( $this, 'admin_page_display' ) ); //Duplicate menu link for first submenu page
        	} else {
        		$this->options_pages[] = add_submenu_page( $option_tabs[0]['id'], $this->title, $option_tab['title'], 'manage_options', $option_tab['id'], array( $this, 'admin_page_display' ) );
        	}
        }
    }

    /**
     * Add menu options page to admin toolbar
     * @since  0.1.0
     */
	public function custom_toolbar_link($wp_admin_bar) {
		$option_tabs = self::option_fields();
		foreach ($option_tabs as $index => $option_tab) {
			if ( $index == 0) {
				$args = array(
					'id' => $option_tab['id'],
					'title' => '<span class="ab-icon dashicons-before dashicons-admin-generic"></span>' . $this->title,
					'href' => admin_url() . 'admin.php?page=' . $option_tab['id'],
					'meta' => array(
						'class' => $this->title,
						'title' =>  $this->title
						)
				);
				$wp_admin_bar->add_node($args);

				$args = array(
					'id' => $option_tab['id'] . '2',
					'title' => $option_tab['title'],
					'href' => admin_url() . 'admin.php?page=' . $option_tab['id'],
					'parent' => $option_tabs[0]['id'],
					'meta' => array(
						'class' => $option_tab['title'],
						'title' => $option_tab['title']
						)
				);
				$wp_admin_bar->add_node($args);

			} else {
				$args = array(
					'id' => $option_tab['id'],
					'title' => $option_tab['title'],
					'href' => admin_url() . 'admin.php?page=' . $option_tab['id'],
					'parent' => $option_tabs[0]['id'],
					'meta' => array(
						'class' => $option_tab['title'],
						'title' => $option_tab['title']
						)
				);
				$wp_admin_bar->add_node($args);
        	}
		}
	}


    /**
     * Admin page markup. Mostly handled by CMB
     * @since  0.1.0
     */
    public function admin_page_display() {
    	$option_tabs = self::option_fields(); //get all option tabs
    	$tab_forms = array();
        ?>
        <div class="wrap cmb2-options-page <?php echo $this->key; ?>">
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

            <!-- Options Page Nav Tabs -->
            <h2 class="nav-tab-wrapper">
            	<?php foreach ($option_tabs as $option_tab) :
            		$tab_slug = $option_tab['id'];
            		$nav_class = 'nav-tab';
            		if ( $tab_slug == $_GET['page'] ) {
            			$nav_class .= ' nav-tab-active'; //add active class to current tab
            			$tab_forms[] = $option_tab; //add current tab to forms to be rendered
            		}
            	?>
            	<a class="<?php echo $nav_class; ?>" href="<?php menu_page_url( $tab_slug ); ?>"><?php esc_attr_e($option_tab['title']); ?></a>
            	<?php endforeach; ?>
            </h2>
            <!-- End of Nav Tabs -->

            <?php foreach ($tab_forms as $tab_form) : //render all tab forms (normaly just 1 form) ?>
            <div id="<?php esc_attr_e($tab_form['id']); ?>" class="group">
            	<?php cmb2_metabox_form( $tab_form, $tab_form['id'] ); ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Defines the theme option metabox and field configuration
     * @since  0.1.0
     * @return array
     */
    public function option_fields() {

        // Only need to initiate the array once per page-load
        if ( ! empty( $this->option_metabox ) ) {
            return $this->option_metabox;
        }

        $this->option_metabox[] = array(
            'id'         => 'general_options', //id used as tab page slug, must be unique
            'title'      => 'General Options',
            'show_on'    => array( 'key' => 'options-page', 'value' => array( 'general_options' ), ), //value must be same as id
            'show_names' => true,
            'fields'     => array(
				array(
					'name' => __('Logo', 'mix'),
					'desc' => __('Logo to be displayed in the header menu.', 'mix'),
					'id' => 'header_logo', //each field id must be unique
					'default' => '',
					'type' => 'file',
				),
				array(
					'name' => __('Sticky Logo', 'mix'),
					'desc' => __('Logo to be displayed in the header menu.', 'mix'),
					'id' => 'header_logo_scroll', //each field id must be unique
					'default' => '',
					'type' => 'file',
				),

			)
        );

        $this->option_metabox[] = array(
            'id'         => 'home_options',
            'title'      => 'Home Settings',
            'show_on'    => array( 'key' => 'options-page', 'value' => array( 'home_options' ), ),
            'show_names' => true,
            'fields'     => array(
				array(
					'name'    => __( 'Attached Posts', 'cmb2' ),
					'desc'    => __( 'Drag 4 posts from the left column to the right column to attach them to the homepage mosaic grid.<br />You may rearrange the order of the posts in the right column by dragging and dropping.', 'cmb2' ),
					'id'      => 'mosaic_attached_posts',
					'type'    => 'custom_attached_posts',
					'options' => array(
						'show_thumbnails' => false, // Show thumbnails on the left
						'filter_boxes'    => true, // Show a text box for filtering the results
						'query_args'      => array( 'posts_per_page' => -1 ), // override the get_posts args
					),
					'before_row'  => '<h3>Mosaic Grid</h3>',
					'after_row'  => '<hr>',
				),
				array(
					'name' => __('Image', 'mix'),
					'id' => 'incident_img',
					'default' => '',
					'type' => 'file',
					'before_row'  => '<h3>Incident Message</h3>',
				),
				array(
					'name' => __('Caption', 'mix'),
					'id' => 'incident_caption',
					'default' => '',
					'type' => 'text'
				),
				array(
					'name' => __('URL', 'mix'),
					'id' => 'incident_url',
					'default' => '',
					'type' => 'text_url'
				),
				array(
					'name' 				=> __('Status', 'mix'),
					'id' 				=> 'incident_status',
					'desc' => __('Select how you would like this alert laid out.', 'mix'),
					'type' 				=> 'radio_inline',
					'show_option_none' 	=> false,
					'default' 			=> 'inactive',
					'options' => array(
						'inactive' 		=> '<img style="width:100px;" src="/app/themes/blue/dist/images/incident-false.jpg">&nbsp;<div style="margin-left:25px;">' .__( 'Inactive', 'cmb2' ) .'</div>',
						'fw'   			=> '<img style="width:100px;" src="/app/themes/blue/dist/images/incident-full.jpg">&nbsp;<div style="margin-left:25px;">' .__( 'Full Width', 'cmb2' ).'</div>',
						'mosaic'    	=> '<img style="width:100px;" src="/app/themes/blue/dist/images/incident-box.jpg">&nbsp;<div style="margin-left:25px;">' . __( 'Mosaic', 'cmb2' ).'</div>',
					),
				),

			)
        );

        $this->option_metabox[] = array(
            'id'         => 'advanced_options',
            'title'      => 'Advanced Settings',
            'show_on'    => array( 'key' => 'options-page', 'value' => array( 'advanced_options' ), ),
            'show_names' => true,
            'fields'     => array(
				array(
					'name' => __('Relation Parameter', 'mix'),
					'id' => 'rpost_relation',
					'default' => 'post_tag',
					'type' => 'radio_inline',
					'before_row'   => '<h3>Related Post Settings</h3>',
					//'before'       => '<p>Testing <b>"before"</b> parameter</p>',
					'options'          => array(
						'category' => __( 'SuperCat', 'cmb2' ),
						'post_tag'   => __( 'Hashtags', 'cmb2' ),
					),
				),
				array(
					'name' => __('Orderby', 'mix'),
					'id' => 'rpost_orderby',
					'type' => 'radio_inline',
					'options'          => array(
						'rand'	 => __( 'Random Order', 'cmb2' ),
						'date'   => __( 'Date', 'cmb2' ),
						'modified'     => __( 'Last Modified Date', 'cmb2' ),
					),
					'after_row'   => '<hr>',
				),
				array(
					'name' => __('Google Tag Manager ID', 'mix'),
					//'desc' => __('ID for Google Maps API.', 'mix'),
					'id' => 'gtm_id',
					'default' => '',
					'type' => 'text'
				),
			)
        );

        //insert extra tabs here

        return $this->option_metabox;
    }

    /**
     * Returns the option key for a given field id
     * @since  0.1.0
     * @return array
     */
    public function get_option_key($field_id) {
    	$option_tabs = $this->option_fields();
    	foreach ($option_tabs as $option_tab) { //search all tabs
    		foreach ($option_tab['fields'] as $field) { //search all fields
    			if ($field['id'] == $field_id) {
    				return $option_tab['id'];
    			}
    		}
    	}
    	return $this->key; //return default key if field id not found
    }

    /**
     * Public getter method for retrieving protected/private variables
     * @since  0.1.0
     * @param  string  $field Field to retrieve
     * @return mixed          Field value or exception is thrown
     */
    public function __get( $field ) {

        // Allowed fields to retrieve
        if ( in_array( $field, array( 'key', 'fields', 'title', 'options_pages' ), true ) ) {
            return $this->{$field};
        }
        if ( 'option_metabox' === $field ) {
            return $this->option_fields();
        }

        throw new Exception( 'Invalid property: ' . $field );
    }

}


// Get it started
$mix_Admin = new mix_Admin();
$mix_Admin->hooks();

/**
 * Wrapper function around cmb_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function mix_get_option( $key = '' ) {
    global $mix_Admin;
    return cmb2_get_option( $mix_Admin->get_option_key($key), $key );
}

?>