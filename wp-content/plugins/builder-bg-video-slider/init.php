<?php
/*
Plugin Name:  Builder BG Video Slider
Plugin URI:   http://themify.me/addons/bg-video-slider
Version:      1.0.0 
Description:  This Builder addon allows you to set a video slider in Row > Options. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
Author:       Themify
Author URI:   http://themify.me/
Text Domain:  builder-bg-video-slider
Domain Path:  /languages
*/

class Builder_Slider_Videos {
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

		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );
		add_action( 'themify_builder_admin_enqueue', array( $this, 'admin_enqueue' ), 15 );

		add_filter( 'themify_builder_row_fields_styling', array( $this, 'add_row_slider_videos_styling' ) );
		add_filter( 'themify_builder_row_start', array( $this, 'themify_builder_row_start' ), 10, 3 );
		
		add_action( 'init', array( $this, 'updater' ) );
		add_action( 'themify_builder_live_styling_ajax', array( $this, 'register_live_styling_ajax' ) );
	}

	private function constants() {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		$this->version = $data[0];
		$this->url = trailingslashit( plugin_dir_url( __FILE__ ) );
	}

	public function frontend_enqueue() {
		wp_enqueue_script( 'tb_slider_videos_frontend-scripts', $this->url . 'assets/frontend-scripts.js', array( 'jquery' ), $this->version );
		wp_localize_script( 'tb_slider_videos_frontend-scripts', 'tb_slider_videos_vars', array( 'url' => $this->url ) );
		wp_enqueue_style( 'tb_slider_videos_frontend-styles', $this->url . 'assets/frontend-styles.css', null, $this->version );
	}

	public function admin_enqueue() {
		wp_enqueue_script( 'tb_slider_videos_admin-scripts', $this->url . 'assets/admin-scripts.js', array( 'jquery' ), $this->version );
		wp_enqueue_style( 'tb_slider_videos_admin-styles', $this->url . 'assets/admin-styles.css', null, $this->version );
	}

	/**
	 * Append user role option to row module.
	 */
	function add_row_slider_videos_styling( $options ) {
		$slider_videos_options = array(
			array(
				'id' => 'background_slider_videos_autoplay',
				'label' => __( 'Auto play', 'builder-slider-videos' ),
				'type' => 'select',
				'meta' => array(
					array('value' => 'yes', 'name' => __( 'Yes', 'builder-slider-videos' ) ),
					array('value' => 'no', 'name' => __( 'No', 'builder-slider-videos' ) ),
				),
				'wrap_with_class' => 'tf-group-element tf-group-element-slidervideos'
			),
			array(
				'id' => 'background_slider_videos_progressbar',
				'label' => __( 'Video progress bar', 'builder-slider-videos' ),
				'type' => 'select',
				'meta' => array(
					array('value' => 'show', 'name' => __( 'Show', 'builder-slider-videos' ) ),
					array('value' => 'hide', 'name' => __( 'Hide', 'builder-slider-videos' ) ),
				),
				'wrap_with_class' => 'tf-group-element tf-group-element-slidervideos'
			),
			array(
				'id' => 'background_slider_videos_controls',
				'label' => __( 'Video controls', 'builder-slider-videos' ),
				'type' => 'select',
				'meta' => array(
					array('value' => 'show', 'name' => __( 'Show', 'builder-slider-videos' ) ),
					array('value' => 'hide', 'name' => __( 'Hide', 'builder-slider-videos' ) ),
				),
				'wrap_with_class' => 'tf-group-element tf-group-element-slidervideos'
			),
			array(
				'id' => 'background_slider_videos_mute',
				'label' => __( 'Video mute', 'builder-slider-videos' ),
				'type' => 'select',
				'meta' => array(
					array('value' => 'no', 'name' => __( 'No', 'builder-slider-videos' ) ),
					array('value' => 'yes', 'name' => __( 'Yes', 'builder-slider-videos' ) ),
				),
				'wrap_with_class' => 'tf-group-element tf-group-element-slidervideos'
			),
			array(
				'id' => 'background_slider_videos',
				'type' => 'builder',
				'new_row_text' => __( 'Add new video', 'builder-slider-videos' ),
				'options' => array(
					array(
						'id' => 'background_slider_videos_video',
						'type' => 'video',
						'label' => __( 'Video', 'builder-slider-videos' ),
						'description' => __( 'Video format: mp4. Note: video background does not play on mobile, image below will be used as fallback.', 'builder-slider-videos' ),
						'class' => 'xlarge'
					),
					array(
						'id' => 'background_slider_videos_image',
						'type' => 'image',
						'label' => __( 'Fallback image', 'builder-slider-videos' ),
						'class' => 'xlarge'
					)
				),
				'wrap_with_class' => 'tf-group-element tf-group-element-slidervideos tf-group-element-slidervideos-videos'
			)
		);

		foreach ( $options as $key => $option ) {
			if ( isset( $option['id'] ) && $option['id'] == 'background_type' ) {
				$options[$key]['meta'][] = array( 'value' => 'slidervideos', 'name' => __( 'Slider Videos', 'builder-slider-videos' ) );
				$position = $key+1;

				$options = array_merge(
					array_slice( $options, 0, $position, true ),
					$slider_videos_options,
					array_slice( $options, $position, count( $options ) - $position, true )
				);
			}
		}

		return $options;
	}

	public function register_live_styling_ajax() {
		add_action( 'wp_ajax_tfb_slider_videos_live_styling', array( $this, 'live_styling' ) );
	}

	public function live_styling() {
		check_ajax_referer( 'tfb_load_nonce', 'nonce' );

		$slider_videos_data = $_POST['tfb_slider_videos_data'];
		$slider_videos_data['videos'] = str_replace( '\"', '"', $slider_videos_data['videos'] );

		$builder_id = $slider_videos_data['builder_id'];
		$row = array(
			'styling' => array(
				'background_type' => $slider_videos_data['type'],
				'background_slider_videos_autoplay' => $slider_videos_data['autoplay'],
				'background_slider_videos_progressbar' => $slider_videos_data['progressbar'],
				'background_slider_videos_controls' => $slider_videos_data['controls'],
				'background_slider_videos_mute' => $slider_videos_data['mute'],
				'background_slider_videos' => json_decode( $slider_videos_data['videos'], TRUE )
			),
			'row_order' => $slider_videos_data['row_order']
		);

		$this->do_slider_videos( $builder_id, $row );

		die();
	}

	private function do_slider_videos( $builder_id, $row ) {
		if ( is_array( $row )
			&& isset( $row['styling'] )
			&& isset( $row['styling']['background_type'] )
			&& $row['styling']['background_type'] == 'slidervideos'
			
			&& isset( $row['styling']['background_slider_videos'] )
			&& ! empty( $row['styling']['background_slider_videos'] )
			&& is_array( $row['styling']['background_slider_videos'] )
			&& sizeof( $row['styling']['background_slider_videos'] ) > 0
			) {

			$i = 0;
			$randomId = "{$builder_id}_{$row['row_order']}";

			echo "<div id=\"tb_slider_videos_{$randomId}\" class=\"tb_slider_videos\" data-index=\"{$randomId}\" data-autoplay=\"{$row['styling']['background_slider_videos_autoplay']}\" data-progressbar=\"{$row['styling']['background_slider_videos_progressbar']}\" data-controls=\"{$row['styling']['background_slider_videos_controls']}\" data-mute=\"{$row['styling']['background_slider_videos_mute']}\">";
			echo "<div class=\"tb_slider_videos_wrapper swiper-wrapper\">";

			foreach ( $row['styling']['background_slider_videos'] as $video_obj ) {
				$video = isset( $video_obj[0] ) ? $video_obj[0] : ( isset( $video_obj['background_slider_videos_video'] ) ? $video_obj['background_slider_videos_video'] : '' );
				$image = isset( $video_obj[1] ) ? $video_obj[1] : ( isset( $video_obj['background_slider_videos_image'] ) ? $video_obj['background_slider_videos_image'] : $this->url . 'assets/1px-placeholder.jpg' );

				if ( ! empty( $video ) ) {
					$video = esc_attr( $video );
					$image = esc_attr( $image );
					echo "<div class=\"tb_slider_videos_slide swiper-slide\" data-index=\"{$i}\" data-video=\"{$video}\" style=\"background-image:url({$image})\"></div>";
				}

				$i++;
			}

			echo "</div>";
			echo '</div>';

			echo "<div class=\"tb_slider_videos_helper tb_slider_videos_pagination\"></div>";

			echo "<div class=\"tb_slider_videos_helper tb_slider_videos_nav\">";
			echo "<a class=\"tb_slider_videos_nav_arrow tb_slider_videos_nav_arrow_prev\">&lsaquo;</a>";
			echo "<a class=\"tb_slider_videos_nav_arrow tb_slider_videos_nav_arrow_next\">&rsaquo;</a>";
			echo "<a class=\"tb_slider_videos_nav_control tb_slider_videos_nav_control_play\"><span></span></a>";
			echo "<a class=\"tb_slider_videos_nav_control tb_slider_videos_nav_control_pause\"><span></span><span></span></a>";
			echo "</div>";

			echo "<div class=\"tb_slider_videos_helper tb_slider_videos_progressbar\"></div>";
		}
	}

	/**
	 * Control front end display of row module.
	 * @access	public
	 * @return	array
	 */
	public function themify_builder_row_start( $builder_id, $row ) {
		$this->do_slider_videos( $builder_id, $row );
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

Builder_Slider_Videos::get_instance();
