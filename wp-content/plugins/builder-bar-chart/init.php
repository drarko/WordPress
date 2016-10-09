<?php
/*
Plugin Name:  Builder Bar Chart
Plugin URI:   http://themify.me/addons/#
Version:      1.0.5
Author:       Themify
Description:  Build beautiful and colorful bar charts. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
Text Domain:  builder-bar-chart
Domain Path:  /languages
*/

defined( 'ABSPATH' ) or die( '-1' );

class Builder_Bar_Chart {

	private static $instance = null;
	var $url;
	var $dir;
	var $version;

	/*
	 * Creates or returns an instance of this class
	 * 
	 * @return  A single instance of this class
	 */

	public static function get_instance() {
		return null == self::$instance ? self::$instance = new self : self::$instance;
	}

	private function __construct() {
		$this->constants();
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 5 );
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
		load_plugin_textdomain( 'builder-bar-chart', false, '/languages' );
	}

	public function enqueue() {
		wp_enqueue_style( 'builder-bar-chart', $this->url . 'assets/style.css', null, $this->version );
		wp_register_script( 'builder-bar-chart', $this->url . 'assets/script.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'builder-bar-chart' );
	}

	public function admin_enqueue() {
		wp_enqueue_script( 'builder-bar-chart' );
		wp_enqueue_style( 'builder-bar-chart-admin', $this->url . 'assets/admin.css' );
	}

	public function register_module( $ThemifyBuilder ) {
		$ThemifyBuilder->register_directory( 'templates', $this->dir . 'templates' );
		$ThemifyBuilder->register_directory( 'modules', $this->dir . 'modules' );
	}

	public function updater() {
		if ( class_exists( 'Themify_Builder_Updater' ) ) {
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
Builder_Bar_Chart::get_instance();