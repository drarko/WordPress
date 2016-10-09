<?php
/*
Plugin Name:  Builder Typewriter
Plugin URI:   http://themify.me/addons/builder-typewriter
Version:      1.0.1
Description:  This Builder addon allows you to create a module with typewriter effect. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
Author:       Themify
Author URI:   http://themify.me/
Text Domain:  builder-typewriter
Domain Path:  /languages
*/

/* Exit if accessed directly */
defined( 'ABSPATH' ) or die( '-1' );

class Builder_Typewriter {
	
	private static $instance = null;
	var $version;
	var $url;
	var $dir;

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

		add_action( 'plugins_loaded', array( $this, 'i18n' ), 5 );

		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );
		add_action( 'themify_builder_admin_enqueue', array( $this, 'admin_enqueue' ), 15 );

		add_action( 'themify_builder_setup_modules', array( $this, 'register_module' ) );
		
		add_action( 'init', array( $this, 'updater' ) );
	}

	private function constants() {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		$this->version = $data[0];
		$this->url = trailingslashit( plugin_dir_url( __FILE__ ) );
		$this->dir = trailingslashit( plugin_dir_path( __FILE__ ) );
	}

	public function i18n() {
		load_plugin_textdomain( 'builder-typewriter', false, '/languages' );
	}

	public function frontend_enqueue() {
		wp_register_script( 'tb_typewriter_frontend-scripts', $this->url . 'assets/frontend-scripts.js', array( 'jquery' ), $this->version );
		wp_localize_script( 'tb_typewriter_frontend-scripts', 'tb_typewriter_vars', array( 'url' => $this->url ) );
		wp_register_style( 'builder-typewriter', $this->url . 'assets/style.css', null, $this->version );
	}

	public function admin_enqueue() {
		wp_enqueue_script( 'tb_typewriter_admin-scripts', $this->url . 'assets/admin-scripts.js', array( 'jquery' ), $this->version );
		wp_enqueue_style( 'tb_typewriter_admin-styles', $this->url . 'assets/admin-styles.css', null, $this->version );
	}

	public function register_module( $ThemifyBuilder ) {
		$ThemifyBuilder->register_directory( 'templates', $this->dir . 'templates' );
		$ThemifyBuilder->register_directory( 'modules', $this->dir . 'modules' );
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

Builder_Typewriter::get_instance();
