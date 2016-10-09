<?php
/*
Plugin Name:  Builder Timeline
Plugin URI:   http://themify.me/addons/timeline
Version:      1.0.9 
Author:       Themify
Description:  Display content in a timeline-styled layouts. It requires to use with a Themify theme (framework 2.0.6+) or the Builder plugin (v 1.2.5).
Text Domain:  builder-timeline
Domain Path:  /languages
*/

defined( 'ABSPATH' ) or die( '-1' );

class Builder_Timeline {

	private static $instance = null;
	var $url;
	var $dir;
	var $version;
	var $timeline_sources;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance() {
		return null == self::$instance ? self::$instance = new self : self::$instance;
	}

	private function __construct() {
		$this->constants();
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 5 );
		add_action( 'plugins_loaded', array( $this, 'load_sources' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 15 );
		add_action( 'themify_builder_setup_modules', array( $this, 'register_module' ) );
		add_action( 'themify_builder_admin_enqueue', array( $this, 'admin_enqueue' ), 15 );
		add_action( 'init', array( $this, 'updater' ) );
	}

	public function constants() {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		$this->version = $data[0];
		$this->url = trailingslashit( plugin_dir_url( __FILE__ ) );
		$this->dir = trailingslashit( plugin_dir_path( __FILE__ ) );
	}

	public function i18n() {
		load_plugin_textdomain( 'builder-timeline', false, '/languages' );
	}

	public function enqueue() {
		wp_register_script( 'kl-timeline-embed', $this->url . 'assets/knight-lab-timelinejs/js/storyjs-embed.js', array( 'jquery' ), '2.33.1', true );
		wp_localize_script( 'jquery', 'builder_timeline', array( 'data' => array() ) ); // empty array that will hold timeline data
		wp_register_script( 'builder-timeline', $this->url . 'assets/scripts.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_style( 'builder-timeline', $this->url . 'assets/style.css', null, $this->version );

		wp_enqueue_script( 'kl-timeline-embed' );
		wp_enqueue_script( 'builder-timeline' );
	}

	public function admin_enqueue() {
		wp_enqueue_script( 'kl-timeline-embed' );
		wp_enqueue_script( 'builder-timeline' );
		wp_enqueue_style( 'builder-timeline-admin', $this->url . 'assets/admin.css' );
	}

	public function register_module( $ThemifyBuilder ) {
		$ThemifyBuilder->register_directory( 'templates', $this->dir . 'templates' );
		$ThemifyBuilder->register_directory( 'modules', $this->dir . 'modules' );
	}

	public function register_source( $name ) {
		$class_name = "Builder_Timeline_{$name}_Source";
		if( class_exists( $class_name ) ) {
			$this->timeline_sources[$name] = new $class_name;
		}
	}

	public function load_sources() {
		foreach( array( 'post', 'text' ) as $source ) {
			include( $this->dir . 'includes/timeline-source-' . $source . '.php' );
			$this->register_source( $source );
		}
	}

	public function get_sources() {
		return apply_filters( 'builder_timeline_sources', $this->timeline_sources );
	}

	public function get_source( $name ) {
		if( isset( $this->timeline_sources[$name] ) ) {
			return $this->timeline_sources[$name];
		}
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
Builder_Timeline::get_instance();