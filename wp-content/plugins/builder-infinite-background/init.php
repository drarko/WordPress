<?php
/*
Plugin Name:  Builder Infinite Background
Plugin URI:   http://themify.me/addons/infinite-background
Version:      1.0.5
Description:  This Builder addon allows you to set infinite scrolling row background image either horizontally or vertically in Row > Options.
Author:       Themify
Author URI:   http://themify.me/
Text Domain:  builder-infinite-background
Domain Path:  /languages
*/

class Builder_Infinite_Background {

	private static $instance = null;
	var $version;
	var $url;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance() {
		return null == self::$instance ? self::$instance = new self : self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @access	private
	 * @return	void
	 */
	private function __construct() {
		$this->constants();

		// TODO: enqueue this asynchronously only when front-end Builder is active.
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );

		add_filter( 'themify_builder_row_fields_options', array( $this, 'add_row_scrolling_background_option' ) );

		// Front end control
		add_filter( 'themify_builder_row_start', array( $this, 'themify_builder_row_start' ), 10, 3 );

		// Admin control
		add_action( 'init', array( $this, 'updater' ) );

		add_action( 'themify_builder_live_styling_ajax', array( $this, 'register_live_styling_ajax' ) );
	}

	function constants() {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		$this->version = $data[0];
		$this->url = trailingslashit( plugin_dir_url( __FILE__ ) );
	}

	public function frontend_enqueue() {
		// TODO: enqueue this asynchronously only when front-end Builder is active.
		wp_enqueue_script( 'builder-infinite-background-front', $this->url . 'assets/frontend-scripts.js', array( 'jquery' ), $this->version );
	}

	/**
	 * Append user role option to row module.
	 */
	function add_row_scrolling_background_option( $options ) {

		$new_options = array(
			array(
				'id' => 'row_scrolling_background',
				'type' => 'radio',
				'label' => __('Infinite Background Image', 'builder-infinite-background'),
				'meta' => array(
					array('value' => 'bg-scroll-horizontally', 'name' => __('Scroll horizontally', 'builder-infinite-background')),
					array('value' => 'bg-scroll-vertically', 'name' => __('Scroll vertically', 'builder-infinite-background')),
					array('value' => 'disable', 'name' => __('Disable', 'builder-infinite-background'), 'selected' => true)
				),
				'description' => '<small>' . __('This will make the row background image to scroll infinitely','builder-infinite-background') . '</small>',
				'wrap_with_class' => 'tf-group-element tf-group-element-image',
			),
			array(
				'id' => 'row_scrolling_background_speed',
				'type' => 'text',
				'label' => __('Scrolling speed', 'builder-infinite-background'),
				'class' => 'xsmall',
				'description' => '<br/><small>' . __('Insert speed in seconds', 'builder-infinite-background') . '</small>',
				'wrap_with_class' => 'tf-group-element tf-group-element-image',
			),
			array(
				'id' => 'row_scrolling_background_width',
				'type' => 'text',
				'label' => __('Background Width', 'builder-infinite-background'),
				'class' => 'xsmall',
				'description' => __('px', 'builder-infinite-background'),
				'wrap_with_class' => 'tf-group-element tf-group-element-image',
			),
			array(
				'id' => 'row_scrolling_background_height',
				'type' => 'text',
				'label' => __('Background Height', 'builder-infinite-background'),
				'class' => 'xsmall',
				'description' => __('px', 'builder-infinite-background'),
				'wrap_with_class' => 'tf-group-element tf-group-element-image',
			)
		);

		/* determine the position to insert the new options to */
		$position = 15;

		return array_merge(
			array_slice( $options, 0, $position, true ),
			$new_options,
			array_slice( $options, $position, count( $options ) - $position, true )
		);
	}

	public function register_live_styling_ajax() {
		add_action( 'wp_ajax_tfb_infinite_bg_live_styling', array( $this, 'live_styling' ) );
	}

	public function live_styling() {
		check_ajax_referer( 'tfb_load_nonce', 'nonce' );

		$infinite_bg_data = $_POST['tfb_infinite_background_data'];

		$builder_id = $infinite_bg_data['builder_id'];
		$row = array(
			'styling' => array(
				'row_scrolling_background' => $infinite_bg_data['type'],
				'row_scrolling_background_width' => $infinite_bg_data['width'],
				'row_scrolling_background_height' => $infinite_bg_data['height'],
				'row_scrolling_background_speed' => $infinite_bg_data['speed']
			),
			'row_order' => $infinite_bg_data['row_order']
		);

		$this->do_infinite_bg( $builder_id, $row );

		die();
	}

	private function do_infinite_bg( $builder_id, $row ) {
		if (isset($row['styling']['row_scrolling_background']) && !empty($row['styling']['row_scrolling_background'])
		    && $row['styling']['row_scrolling_background'] != "disable") {

			$width = $row['styling']['row_scrolling_background_width'];
			$height = $row['styling']['row_scrolling_background_height'];
			$type = $row['styling']['row_scrolling_background']; // Scrolling type [vertically|horizontally]
			$speed = empty( $row['styling']['row_scrolling_background_speed'] ) ? '5s' : $row['styling']['row_scrolling_background_speed'] . 's';
			$this_row = ".themify_builder_{$builder_id}_row.module_row_{$row['row_order']}";
			$unique_id = "{$builder_id}-{$row['row_order']}";

			$style_tag_class = 'themify_builder_infinite_bg';

			if ( $type === "bg-scroll-horizontally" ) {
				echo "<style class='{$style_tag_class}'>" . $this_row . "{"
				     . "-webkit-animation: bg-animation-horizontally-$unique_id $speed linear infinite !important;
				-moz-animation: bg-animation-horizontally-$unique_id $speed linear infinite !important;
				animation: bg-animation-horizontally-$unique_id $speed linear infinite !important;
				}

				@-webkit-keyframes bg-animation-horizontally-$unique_id {
					from { background-position: 0 0; }
					to { background-position: -". $width ."px 0px; }
				}

				@-moz-keyframes bg-animation-horizontally-$unique_id {
					0% { background-position: left; }
					100% { background-position: -". $width ."px 0%; }
				}

				@keyframes bg-animation-horizontally-$unique_id {
					from { background-position: 0 0; }
					to { background-position: -". $width ."px 0px }
				}</style>";
			} else {
				echo "<style class='{$style_tag_class}'>" . $this_row . "{"
				     . "-webkit-animation: bg-animation-vertically-$unique_id $speed linear infinite !important;
				-moz-animation: bg-animation-vertically-$unique_id $speed linear infinite !important;
					animation: bg-animation-vertically-$unique_id $speed linear infinite !important;
				}

				@-webkit-keyframes bg-animation-vertically-$unique_id {
				   from { background-position: 0 0;}
					to { background-position: 0% -".$height."px; }
				}

				@-moz-keyframes bg-animation-vertically-$unique_id {
					from { background-position: 0 0; }
					to { background-position: 0% -".$height."px }
				}

				@keyframes bg-animation-vertically-$unique_id {
					from { background-position: 0 0; }
					to { background-position: 0% -".$height."px; }
				}</style>";
			}
		}

	}

	/**
	 * Control front end display of row module.
	 * @access	public
	 * @return	array
	 */
	public function themify_builder_row_start( $builder_id, $row ) {
		$this->do_infinite_bg( $builder_id, $row );
	}

	public function updater() {
		if( class_exists( 'Themify_Builder_Updater' ) ) {
			if ( ! function_exists( 'get_plugin_data') )
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			$plugin_basename = plugin_basename( __FILE__ );
			$plugin_data = get_plugin_data( trailingslashit( plugin_dir_path( __FILE__ ) ) . basename( $plugin_basename ) );
			new Themify_Builder_Updater( array(
				'name' => trim( dirname( $plugin_basename ), '/' ),
				'nicename' => $plugin_data['Name'],
				'update_type' => 'addon',
			), $this->version, trim( $plugin_basename, '/' ) );
		}
	}
}

Builder_Infinite_Background::get_instance();
