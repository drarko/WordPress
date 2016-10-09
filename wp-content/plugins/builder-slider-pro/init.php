<?php
/*
Plugin Name:  Builder Slider Pro
Plugin URI:   http://themify.me/addons/slider-pro
Version:      1.0.8
Author:       Themify
Description:  A Builder addon to make animated sliders with many transition effects. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
Text Domain:  builder-slider-pro
Domain Path:  /languages
*/

defined('ABSPATH') or die('-1');

class Builder_Pro_Slider {

	private static $instance = null;
	var $url;
	var $dir;
	var $version;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance() {
		return null == self::$instance ? self::$instance = new self : self::$instance;
	}

	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 15 );
		add_action( 'themify_builder_setup_modules', array( $this, 'register_module' ) );
		add_action( 'themify_builder_admin_enqueue', array( $this, 'admin_enqueue' ), 15 );
	}

	public function constants() {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		$this->version = $data[0];
		$this->url = trailingslashit( plugin_dir_url( __FILE__ ) );
		$this->dir = trailingslashit( plugin_dir_path( __FILE__ ) );
	}

	public function i18n() {
		load_plugin_textdomain( 'builder-slider-pro', false, '/languages' );
	}

	public function enqueue() {
		wp_enqueue_style( 'builder-slider-pro', $this->url . 'assets/style.css', null, $this->version );
		wp_register_script( 'jquery-slider-pro', $this->url . 'assets/jquery.sliderPro.min.js', array( 'jquery' ), '1.2.1', true );
		wp_register_script( 'builder-slider-pro', $this->url . 'assets/scripts.js', array( 'jquery', 'jquery-slider-pro' ), $this->version, true );
		wp_localize_script( 'builder-slider-pro', 'builderSliderPro', apply_filters( 'builder_slider_pro_script_vars', array(
			'height_ratio' => '1.9'
		) ) );
	}

	public function admin_enqueue() {
		wp_enqueue_script( 'builder-slider-pro' );
		wp_enqueue_style( 'builder-pro-slider-admin-css', $this->url . 'assets/admin.css' );
	}

	public function register_module($ThemifyBuilder) {
		$ThemifyBuilder->register_directory( 'templates', $this->dir . 'templates' );
		$ThemifyBuilder->register_directory( 'modules', $this->dir . 'modules' );
	}
}
Builder_Pro_Slider::get_instance();