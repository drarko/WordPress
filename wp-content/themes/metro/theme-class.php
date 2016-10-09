<?php
/**
 * Theme Class
 *
 * Class that provides special functions for the theme.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load theme shortcodes class
require_once('theme-class-shortcodes.php');

if(!class_exists('Themify_Metro')){
	class Themify_Metro {
	
		// Custom Post Types
		static $portfolio = 'portfolio';
		static $tile = 'tile';

		public $google_fonts = '';
		
		function set_portfolio_taxonomy($query){
			if( is_tax(self::$portfolio.'-category')){
				$query->set('post_type', self::$portfolio);
			}
			if( is_tax(self::$tile.'-category')){
				$query->set('post_type', self::$tile);
			}
		}
	
		function __construct(){
			add_action( 'init', array( $this, 'register' ) );
			add_filter( 'themify_post_types', array( $this, 'extend_post_types' ) );

			add_action( 'themify_post_end', array( $this, 'custom_post_css' ) );
			add_action( 'wp_footer', array( $this, 'enqueue_google_fonts' ) );
			
			//Create shortcode
			$cpts = array(
				// Portfolio shortcode parameters
				self::$portfolio => array(
					'id' => '',
					'title' => '',
					'unlink_title' => '',
					'image' => 'yes', // no
					'image_w' => '',
					'image_h' => '0',
					'display' => 'none', // excerpt, content
					'post_meta' => '', // yes
					'post_date' => '', // yes
					'more_link' => false, // true goes to post type archive, and admits custom link
					'more_text' => __('More &rarr;', 'themify'),
					'limit' => 4,
					'category' => 'all', // integer category ID
					'order' => 'DESC', // ASC
					'orderby' => 'date', // title, rand
					'style' => '', // grid3, grid2
					'sorting' => 'no', // yes
					'page_nav' => 'no', // yes
					'paged' => '0', // internal use for pagination, dev: previously was 1
					// slider parameters
					'autoplay' => '',
					'effect' => '',
					'timeout' => '',
					'speed' => ''
				),
				self::$tile => array(
					'id' => '',
					'title' => 'yes',
					'unlink_image' => '',
					'display' => 'content', // excerpt, content
					'category' => 'all', // tile category ID
					'order' => 'DESC', // ASC
					'orderby' => 'date', // title, rand
					'sorting' => 'no', // yes
					'page_nav' => 'no', // yes
					'paged' => '0', // internal use for pagination
					'more_link' => false,
					'more_text' => '',
				)
			);
			foreach ($cpts as $shortcode => $options) {
				$class_name = apply_filters('themify_theme_class_shortcodes', 'Themify_' . ucwords($shortcode));
				$new_class = new $class_name
				(array(
					'prefix' => $shortcode,
					'atts' => $options
				));
			}
		}
	
		function register() {
			/**
			 * @var array Custom Post Types to create with its plural and singular forms
			 */
			$cpts = array(
				self::$portfolio => array(
					'plural' => __('Portfolios', 'themify'),
					'singular' => __('Portfolio', 'themify'),
					'rewrite' => apply_filters('themify_portfolio_rewrite', 'project'),
					'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt', 'author' )
				),
				self::$tile => array(
					'plural' => __('Tiles', 'themify'),
					'singular' => __('Tile', 'themify'),
					'exclude_from_search' => true,
					'rewrite' => apply_filters( 'themify_tile_rewrite', 'tile' )
				)
			);
			$position = 52;
			foreach( $cpts as $key => $cpt ){
				register_post_type( $key, array(
					'labels' => array(
						'name' => $cpt['plural'],
						'singular_name' => $cpt['singular'],
						'add_new' => __( 'Add New', 'themify' ),
						'add_new_item' => sprintf(__( 'Add New %s', 'themify' ), $cpt['singular']),
						'edit_item' => sprintf(__( 'Edit %s', 'themify' ), $cpt['singular']),
						'new_item' => sprintf(__( 'New %s', 'themify' ), $cpt['singular']),
						'view_item' => sprintf(__( 'View %s', 'themify' ), $cpt['singular']),
						'search_items' => sprintf(__( 'Search %s', 'themify' ), $cpt['plural']),
						'not_found' => sprintf(__( 'No %s found', 'themify' ), $cpt['plural']),
						'not_found_in_trash' => sprintf(__( 'No %s found in Trash', 'themify' ), $cpt['plural']),
						'menu_name' => $cpt['plural']
					),
					'supports' => isset($cpt['supports'])? $cpt['supports'] : array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
					'menu_position' => $position++,
					'hierarchical' => false,
					'public' => true,
					'exclude_from_search' => isset( $cpt['exclude_from_search'] ) ? $cpt['exclude_from_search'] : false,
					'show_ui' => true,
					'show_in_menu' => true,
					'show_in_nav_menus' => true,
					'publicly_queryable' => true,
					'rewrite' => array( 'slug' => isset($cpt['rewrite'])? $cpt['rewrite']: strtolower($cpt['singular']) ),
					'query_var' => true,
					'can_export' => true,
					'capability_type' => 'post'
				));
				register_taxonomy( $key.'-category', array($key), array(
					'labels' => array(
						'name' => sprintf(__( '%s Categories', 'themify' ), $cpt['singular']),
						'singular_name' => sprintf(__( '%s Category', 'themify' ), $cpt['singular']),
						'search_items' => sprintf(__( 'Search %s Categories', 'themify' ), $cpt['singular']),
						'popular_items' => sprintf(__( 'Popular %s Categories', 'themify' ), $cpt['singular']),
						'all_items' => sprintf(__( 'All Categories', 'themify' ), $cpt['singular']),
						'parent_item' => sprintf(__( 'Parent %s Category', 'themify' ), $cpt['singular']),
						'parent_item_colon' => sprintf(__( 'Parent %s Category:', 'themify' ), $cpt['singular']),
						'edit_item' => sprintf(__( 'Edit %s Category', 'themify' ), $cpt['singular']),
						'update_item' => sprintf(__( 'Update %s Category', 'themify' ), $cpt['singular']),
						'add_new_item' => sprintf(__( 'Add New %s Category', 'themify' ), $cpt['singular']),
						'new_item_name' => sprintf(__( 'New %s Category', 'themify' ), $cpt['singular']),
						'separate_items_with_commas' => sprintf(__( 'Separate %s Category with commas', 'themify' ), $cpt['singular']),
						'add_or_remove_items' => sprintf(__( 'Add or remove %s Category', 'themify' ), $cpt['singular']),
						'choose_from_most_used' => sprintf(__( 'Choose from the most used %s Category', 'themify' ), $cpt['singular']),
						'menu_name' => sprintf(__( '%s Category', 'themify' ), $cpt['singular']),
					),
					'public' => true,
					'show_in_nav_menus' => true,
					'show_ui' => true,
					'show_tagcloud' => true,
					'hierarchical' => true,
					'rewrite' => true,
					'query_var' => true
				));
				add_filter('manage_edit-'.$key.'-category_columns', array(&$this, 'taxonomy_header'), 10, 2);
				add_filter('manage_'.$key.'-category_custom_column', array(&$this, 'taxonomy_column_id'), 10, 3);
			}
		}
	
		/**
		 * Includes this custom post to array of cpts managed by Themify
		 * @param Array
		 * @return Array
		 */
		function extend_post_types($types){
			return array_merge($types, array(self::$portfolio, self::$tile));
		}
		
		/**
		 * Display an additional column in categories list
		 * @since 1.0.0
		 */
		function taxonomy_header($cat_columns){
		    $cat_columns['cat_id'] = 'ID';
		    return $cat_columns;
		}
		/**
		 * Display ID in additional column in categories list
		 * @since 1.0.0
		 */
		function taxonomy_column_id($null, $column, $termid){
			return $termid;
		}

		function get_custom_post_css( $post_id = 0 ) {
			if( 0 == $post_id ) {
				$post_id = get_the_ID();
			}
			$css = array();
			$rules = array();
			$style = '';
			$post_type = get_post_type( $post_id );
			switch( $post_type ) {
				case 'post':
					$rules = array(
						'#post-'.$post_id => array(
							array(	'prop' => 'font-size',
								'key' => array('font_size', 'font_size_unit')
							),
							array(	'prop' => 'font-family',
								'key' => 'font_family'
							),
							array(	'prop' => 'color',
								'key' => 'font_color'
							),
							array(	'prop' => 'background-color',
								'key' => 'background_color'
							)
						),
						'#post-'.$post_id.' a' => array(
							array(	'prop' => 'color',
								'key' => 'link_color'
							)
						)
					);
					break;
				case 'portfolio':
					$rules = array(
						'#portfolio-'.$post_id => array(
							array(	'prop' => 'font-size',
									'key' => array('font_size', 'font_size_unit')
							),
							array(	'prop' => 'font-family',
									'key' => 'font_family'
							),
							array(	'prop' => 'color',
									'key' => 'font_color'
							)
						),
						'#portfolio-'.$post_id.' .tile-overlay, .single #portfolio-'.$post_id => array(
							array(	'prop' => 'background-color',
									'key' => 'background_color'
							)
						),
						'#portfolio-'.$post_id.' a' => array(
							array(	'prop' => 'color',
									'key' => 'link_color'
							)
						)
					);
					break;
				case 'tile':
					$rules = array(
						'#tile-'.$post_id => array(
							array(	'prop' => 'font-size',
									'key' => array('font_size', 'font_size_unit')
							),
							array(	'prop' => 'font-family',
									'key' => 'font_family'
							),
							array(	'prop' => 'color',
									'key' => 'font_color'
							),
						),
						'#tile-'.$post_id.' .tile-overlay' => array(
							array(	'prop' => 'background-color',
									'key' => 'background_color'
							)
						),
						'#tile-'.$post_id.' a' => array(
							array(	'prop' => 'color',
									'key' => 'link_color'
							)
						)
					);
					break;
			}

			if ( 'button' == get_post_meta( $post_id, '_tile_type', true ) ) {
				unset( $rules['#tile-'.$post_id.' .tile-overlay'] );
				$rules['#tile-'.$post_id.'.button'] = array(
					array(	'prop' => 'background-color',
							'key' => 'background_color'
					)
				);
			}
			if ( 'text' == get_post_meta( $post_id, '_tile_type', true ) ) {
				unset( $rules['#tile-'.$post_id.' .tile-overlay'] );
				$rules['#tile-'.$post_id] = array(
					array(	'prop' => 'background-color',
							'key' => 'background_color'
					)
				);
			}

			foreach ($rules as $selector => $property) {
				foreach ($property as $val) {
					$prop = $val['prop'];
					$key = $val['key'];
					if(is_array($key)) {
						if('font-size' == $prop && themify_check($key[0])){
							$css[$selector][$prop] = $prop .': '. themify_get($key[0]) . themify_get($key[1]);
						}
					} elseif(themify_check($key) && 'default' != themify_get($key)) {
						if('color' == $prop || stripos($prop, 'color')) {
							$css[$selector][$prop] = $prop .': #'.themify_get($key);
						}
						elseif( $prop == 'font-family' ) {
							$font = themify_get($key);
							$css[$selector][$prop] = $prop .': '. $font;
							if( ! in_array( $font, themify_get_web_safe_font_list(true) ) ) {
								$this->google_fonts .= str_replace(' ', '+', $font.'|');
							}
						}
						else {
							$css[$selector][$prop] = $prop .': '. themify_get($key);
						}
					}
				}
				if(!empty($css[$selector])){
					$style .= "$selector {\n\t" . implode(";\n\t", $css[$selector]) . "\n}\n";
				}
			}

			if( '' != $style ) {
				return "\n<!-- Post-$post_id Style -->\n<style>\n$style</style>\n<!-- End Post-$post_id Style -->\n";
			} else {
				return '';
			}
		}

		function custom_post_css(){
			echo $this->get_custom_post_css();
		}

		function enqueue_google_fonts() {
			if( '' == $this->google_fonts ) return;
			$this->google_fonts = substr($this->google_fonts, 0, -1);
			wp_enqueue_style('section-styling-google-fonts', themify_https_esc('http://fonts.googleapis.com/css'). '?family='.$this->google_fonts);
		}
	
	}
}

// Start Background Gallery
global $Metro;
$Metro = new Themify_Metro();

?>