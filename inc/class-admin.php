<?php
/**
 * Pootle Slider Admin class
 * @property string token Plugin token
 * @property string $url Plugin root dir url
 * @property string $path Plugin root dir path
 * @property string $version Plugin version
 */
class Pootle_Slider_Admin{

	/**
	 * @var 	Pootle_Slider_Admin Instance
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Main Pootle Slider Instance
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 * @return Pootle_Slider_Admin instance
	 * @since 	1.0.0
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Constructor function.
	 * @access  private
	 * @since 	1.0.0
	 */
	private function __construct() {
		$this->token   =   Pootle_Slider::$token;
		$this->url     =   Pootle_Slider::$url;
		$this->path    =   Pootle_Slider::$path;
		$this->version =   Pootle_Slider::$version;
	} // End __construct()

	/**
	 * Creates pootle slider post type
	 * @action init
	 * @since 	1.0.0
	 */
	public function init() {
		$labels = array(
			'name'                  => _x( 'Pootle Sliders', 'Pootle Slider General Name', 'pootle-slider' ),
			'singular_name'         => _x( 'Pootle Slider', 'Pootle Slider Singular Name', 'pootle-slider' ),
			'menu_name'             => __( 'Pootle Sliders', 'pootle-slider' ),
			'name_admin_bar'        => __( 'Pootle Slider', 'pootle-slider' ),
			'archives'              => __( 'Pootle Slider Archives', 'pootle-slider' ),
			'parent_item_colon'     => __( 'Parent Pootle Slider:', 'pootle-slider' ),
			'all_items'             => __( 'All Pootle Sliders', 'pootle-slider' ),
			'add_new_item'          => __( 'Add New Pootle Slider', 'pootle-slider' ),
			'add_new'               => __( 'Add New', 'pootle-slider' ),
			'new_item'              => __( 'New Pootle Slider', 'pootle-slider' ),
			'edit_item'             => __( 'Edit Pootle Slider', 'pootle-slider' ),
			'update_item'           => __( 'Update Pootle Slider', 'pootle-slider' ),
			'view_item'             => __( 'View Pootle Slider', 'pootle-slider' ),
			'search_items'          => __( 'Search Pootle Slider', 'pootle-slider' ),
			'not_found'             => __( 'Not found', 'pootle-slider' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'pootle-slider' ),
			'featured_image'        => __( 'Featured Image', 'pootle-slider' ),
			'set_featured_image'    => __( 'Set featured image', 'pootle-slider' ),
			'remove_featured_image' => __( 'Remove featured image', 'pootle-slider' ),
			'use_featured_image'    => __( 'Use as featured image', 'pootle-slider' ),
			'insert_into_item'      => __( 'Insert into Pootle Slider', 'pootle-slider' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Pootle Slider', 'pootle-slider' ),
			'items_list'            => __( 'Pootle Sliders list', 'pootle-slider' ),
			'items_list_navigation' => __( 'Pootle Sliders list navigation', 'pootle-slider' ),
			'filter_items_list'     => __( 'Filter Pootle Sliders list', 'pootle-slider' ),
		);
		$args = array(
			'label'               => __( 'Pootle Slider', 'pootle-slider' ),
			'description'         => __( 'Slider for using in Pootle Pagebuilder', 'pootle-slider' ),
			'labels'              => $labels,
			'supports'            => array(),
			'hierarchical'        => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 20.59,
			'menu_icon'           => 'dashicons-images-alt2',
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'pootle-slider', $args );
	}

	/**
	 * Adds new slider link to admon bar
	 * @param $admin_bar
	 */
	function admin_menu( $admin_bar ) {
		add_submenu_page(
			'page_builder',
			'Pootle Sliders',
			'Pootle Sliders',
			'manage_options',
			'edit.php?post_type=pootle-slider'
		);
		add_submenu_page(
			'page_builder',
			'New Pootle Slider',
			'New Pootle Slider',
			'manage_options',
			'new_page_builder_pootle_slider',
			function(){}
		);
	}

	function redirect_new_slider() {
		global $pagenow;
		if ( ! empty( $pagenow ) && 'admin.php' == $pagenow ) {
			if ( 'new_page_builder_pootle_slider' == filter_input( INPUT_GET, 'page' ) ) {
				$new_live_page_url = admin_url( 'admin-ajax.php' );
				$new_live_page_url = wp_nonce_url( $new_live_page_url, 'ppb-new-live-post', 'ppbLiveEditor' ) . '&action=pootlepb_live_page';
				wp_redirect( "$new_live_page_url&post_type=pootle-slider" );
			}
		}
	}

	/**
	 * Adds row settings panel fields
	 * @param array $fields Fields to output in row settings panel
	 * @return array Tabs
	 * @filter pootlepb_row_settings_fields
	 * @since 	1.0.0
	 */
	public function ppb_posts( $posts ) {
		$posts[] = 'pootle-slider';
		return $posts;
	}

	/**
	 * Adds editor panel tab
	 * @param array $tabs The array of tabs
	 * @return array Tabs
	 * @filter pootlepb_content_block_tabs
	 * @since 	1.0.0
	 */
	public function content_block_tabs( $tabs ) {
		$tabs[ $this->token ] = array(
			'label' => 'Pootle Slider',
			'priority' => 7,
		);
		return $tabs;
	}

	/**
	 * Adds content block panel fields
	 * @param array $fields Fields to output in content block panel
	 * @return array Tabs
	 * @filter pootlepb_content_block_fields
	 * @since 	1.0.0
	 */
	public function content_block_fields( $fields ) {

		$sliders = get_posts( array(
			'post_type' => 'pootle-slider',
			'numberposts' => 25,
			'post_status' => 'any',
			) );

		$options = array( '' => 'Please choose...' );

		foreach ( $sliders as $s ) {
			$options[ $s->ID ] = $s->post_title;
		}

		$fields[ "$this->token-id" ] = array(
			'name' => 'Sample Number with unit',
			'type' => 'select',
			'priority' => 5,
			'options'  => $options,
			'tab' => $this->token,
			'help-text' => 'This is a sample boilerplate field, Sets left and top offset in em.'
		);

		$fields[ "$this->token-animation"] = array(
			'name' => 'Animation',
			'type' => 'select',
			'priority' => 10,
			'options'  => array(
				'' => 'Slide'
			),
			'tab' => $this->token,
		);
		$fields[ "$this->token-duration"] =  array(
			'name' => 'Duration',
			'type' => 'number',
			'priority' => 15,
			'tab' => $this->token,
		);
		$fields[ "$this->token-ratio"] =  array(
			'name' => 'Height as a percentage of width',
			'type' => 'number',
			'min' => '10',
			'max' => '250',
			'priority' => 20,
			'tab' => $this->token,
		);
		return $fields;
	}

}