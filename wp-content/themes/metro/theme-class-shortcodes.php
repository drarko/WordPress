<?php
/**
 * Themify_Shortcode
 * Base class for shortcode creation.
 *
 * @class 		Themify_Shortcode
 * @author 		Themify
 */

if(!class_exists('Themify_Shortcode')){
	class Themify_Shortcode {

		var $instance = 0;
		var $prefix = '';
		var $atts = array();

		static $run_once = true;

		function __construct($args = array()) {
			$defaults = array(
				'prefix' => ''
			);
			$args = wp_parse_args($args, $defaults);
			$this->prefix = $args['prefix'];
			$this->atts = $args['atts'];
			add_action( 'init', array( $this, 'init' ) );

			$this->shortcode__construct();
		}

		/**
		 * Overridable function that runs at the beginning of the shortcode output
		 */
		function shortcode_start(){}

		/**
		 * Overridable function that runs at the end of __construct
		 */
		function shortcode__construct(){}

		/**
		 * Initialization function
		 */
		function init() {
			add_shortcode( $this->prefix, array( $this, 'init_shortcode' ) );
			add_shortcode( 'themify_'.$this->prefix.'_posts', array( $this, 'init_shortcode' ) );
		}

		/**
		 * Add shortcode to WP
		 * @param $atts Array shortcode attributes
		 * @return String
		 * @since 1.0.0
		 */
		function init_shortcode($atts) {
			$this->shortcode_start();
			$this->instance++;

			return do_shortcode($this->shortcode( wp_parse_args( $atts, $this->atts ), $this->prefix ));
		}

		/**
		 * Parses the arguments given as category to see if they are category IDs or slugs and returns a proper tax_query
		 * @param $category
		 * @param $post_type
		 * @return array
		 */
		function parse_category_args($category, $post_type){
			if( 'all' != $category){
				$tax_query_terms = explode(',', $category);
				if(preg_match('#[a-z]#', $category)){
					return array( array(
						'taxonomy' => $post_type . '-category',
						'field' => 'slug',
						'terms' => $tax_query_terms
					));
				} else {
					return array( array(
						'taxonomy' => $post_type . '-category',
						'field' => 'id',
						'terms' => $tax_query_terms
					));
				}
			}
		}


		/**
		 * Function to override in inherited classes
		 * @param array Shortcode attributes that have already been parsed with shortcode_atts
		 */
		function shortcode( $atts = array(), $post_type ) { }

		/**
		 * Returns the excerpt or the content based on user input
		 * @param string Whether to display content, excerpt or none. Defaults to content.
		 * @return string
		 */
		function display( $display = 'content' ) {
			if ( 'excerpt' == $display ) {
				return apply_filters( 'the_excerpt', get_the_excerpt() );
			} elseif ( 'content' == $display ) {
				global $more;
				$more = 0;
				return apply_filters( 'the_content', get_the_content() );
			}
			return '';
		}

		/**
		 * Returns link wrapped in paragraph either to the post type archive page or a custom location
		 * @param bool|string False does nothing, true goes to archive page, custom string sets custom location
		 * @param string Text to link
		 * @return string
		 */
		function section_link($more_link = false, $more_text, $post_type){
			if( $more_link ){
				if( 'true' == $more_link ){
					$more_link = get_post_type_archive_link($post_type);
				}
				return '<p class="more-link-wrap"><a href="' . esc_url($more_link) . '" class="more-link">' . $more_text . '</a></p>';
			}
			return '';
		}

		/**
		 * Returns the post type title wrapped in a specified tag
		 * @param string $title Whether to show the title or not
		 * @param string $tag HTML element used to wrap the title text
		 * @param bool $add_link Pass false to avoid linking the title
		 * @return string
		 */
		function post_title($title, $tag = 'h1', $add_link = true, $class = 'post-title'){
			if( 'yes' == $title || '' == $title ){
				if($add_link){
					$link_before = '<a href="'.$this->get_entry_link().'" title="'.the_title_attribute('echo=0').'">';
					$link_after = '</a>';
				} else {
					$link_before = '';
					$link_after = '';
				}
				return '<'.$tag.' class="'.$class.'">' . $link_before . get_the_title() . $link_after . '</'.$tag.'>';
			}
			return '';
		}

		/**
		 * Returns the post image according to specified dimensions
		 * @param string $image Whether to show the image or not
		 * @param number $width
		 * @param number $width
		 * @return string
		 */
		function post_image($image, $width, $height, $add_link = true, $class = 'post-image'){
			if( 'yes' == $image ){
				if($add_link){
					$before = '<a href="'.$this->get_entry_link().'" title="'.the_title_attribute('echo=0').'">';
					$after = '</a>';
				} else {
					$before = '';
					$after = '';
				}
				$out = '<figure class="'.$class.'">';
					$out .= $before . themify_get_image("w=$width&h=$height&ignore=true") . $after;
				$out .= '</figure>';
				return $out;
			}
			return '';
		}

		function get_entry_link($link_images = true, $img_id = ''){
			if($link_images){
				$post_link = 'post'.get_the_ID();
			} else {
				$post_link = 'post'.get_the_ID().$img_id;
			}
			if ( themify_get('external_link') != '') {

				$link = esc_url(themify_get('external_link'));
				if( themify_check( 'new_tab' ) ) {
					$link .= '" target="_blank';
				}
			}
			elseif ( themify_get('lightbox_link') != '') {

				if(themify_check('iframe_url')) {
					$do_iframe = '?iframe=true&width=100%&height=100%';
				} else {
					$do_iframe = '';
				}
				$link = esc_url(themify_get('lightbox_link')) . $do_iframe . '" class="themify_lightbox';
			} else {
				$link = get_permalink();
			}
			return $link;
		}

		/**
		 * Returns a link to edit the post
		 * @param number $post_id
		 * @return string
		 */
		function edit_link($post_id){
			return current_user_can('edit_others_posts')? '<p>[<a href="' . get_edit_post_link($post_id) . '" class="edit-button">' . __('Edit', 'string') . '</a>]</p>' : '';
		}

		/**
		* Select form element to filter the post list
		* @return string HTML
		*/
		public function get_select(){
			if(!self::$run_once){
				return;
			}
			self::$run_once = false;
			$html = '';
			foreach ( $this->taxonomies as $tax ) {
				$options = sprintf('<option value="">%s %s</option>',
	            	__( 'View All', 'themify' ),
					get_taxonomy($tax)->label
				);
				$class = is_taxonomy_hierarchical( $tax ) ? ' class="level-0"' : '';
				foreach ( get_terms( $tax ) as $taxon ) {
					$options .= sprintf( '<option %s%s value="%s">%s%s</option>',
						isset( $_GET[$tax] ) ? selected( $taxon->slug, $_GET[$tax], false ) : '',
						'0' !== $taxon->parent ? ' class="level-1"' : $class,
						$taxon->slug,
						'0' !== $taxon->parent ? str_repeat( '&nbsp;', 3 ) : '',
						"{$taxon->name} ({$taxon->count})"
					);
				}
				$html .= sprintf('<select name="%s" id="%s" class="postform">%s</select>', $tax, $tax, $options);
			}
	        return print $html;
	    }
		/**
		 * Setup vars when filtering posts in edit.php
		 */
		public function setup_vars() {
			$this->post_type  = get_current_screen()->post_type;
			$this->taxonomies = array_diff(
				get_object_taxonomies( $this->post_type ),
				get_taxonomies( array( 'show_admin_column' => 'false' ) )
			);
		}
		/**
		 * Add columns when filtering posts in edit.php
		 */
		public function add_columns( $taxonomies ) {
			return array_merge( $taxonomies, $this->taxonomies );
		}

		/**
 		 * Return images added in gallery shortcode
		 * @return array
		 */
		public function get_images_from_gallery_shortcode(){
			$sc_gallery = themify_get('_gallery_shortcode');
			$orderby = stripos($sc_gallery, 'rand')? 'rand': 'post__in';
			$image_ids = preg_replace('#\[gallery(.*)ids="([0-9|,]*)"(.*)\]#i', '$2', $sc_gallery );

			return get_posts(array(
				'post__in' => explode(',', str_replace(' ', '', $image_ids)),
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'numberposts' => -1,
				'orderby' => $orderby,
				'order' => 'ASC'
			));
		}

	}
}

if( ! class_exists( 'Themify_Tile' ) ) {

	class Themify_Tile extends Themify_Shortcode {

		public $post_type = 'tile';
		public $taxonomies;

		function shortcode_start(){}

		/**
		 * Trigger at the end of __construct of this shortcode
		 */
		function shortcode__construct(){
			if(is_admin()){
				add_filter( "manage_edit-{$this->post_type}_columns", array(&$this, "{$this->post_type}_column_header"), 10, 2);
				add_filter( "manage_{$this->post_type}_posts_custom_column", array(&$this, "{$this->post_type}_column"), 10, 3);
				add_action( 'load-edit.php', array(&$this, "{$this->post_type}_load") );
				add_filter( "manage_edit-{$this->post_type}_sortable_columns", array(&$this, "{$this->post_type}_column_sortable") );
			}
		}

		/**
		 * Filter request to sort tiles
		 */
		function tile_load() {
			add_filter( 'request', array(&$this, "{$this->post_type}_sort") );
			add_action( current_filter(), array( &$this, 'setup_vars' ), 20 );
			add_action( 'restrict_manage_posts', array( &$this, 'get_select' ) );
			add_filter( "manage_taxonomies_for_{$this->post_type}_columns", array( &$this, 'add_columns' ) );
		}
		/**
		 * Sorts the tiles by type, size and color
		 * @param array
		 * @return array
		 */
		function tile_sort( $vars ) {
			if ( isset( $vars['post_type'] ) && 'tile' == $vars['post_type'] ) {
				if ( isset( $vars['orderby'] ) && 'type' == $vars['orderby'] ) {
					$vars = array_merge( $vars, array(
						'meta_key' => '_tile_type',
						'orderby' => 'meta_value'
					));
				}
				if ( isset( $vars['orderby'] ) && 'size' == $vars['orderby'] ) {
					$vars = array_merge( $vars, array(
						'meta_key' => '_tile_size',
						'orderby' => 'meta_value'
					));
				}
				if ( isset( $vars['orderby'] ) && 'color' == $vars['orderby'] ) {
					$vars = array_merge( $vars, array(
						'meta_key' => '_tile_color',
						'orderby' => 'meta_value'
					));
				}
			}
			return $vars;
		}
		/**
		 * Display an additional column in tiles list
		 * @param array
		 * @return array
		 */
		function tile_column_header($columns){
			$columns['shortcode'] = __( 'Shortcode', 'themify' );
			$columns['type'] = __( 'Type', 'themify' );
			$columns['size'] = __( 'Size', 'themify' );
			$columns['color'] = __( 'Color', 'themify' );
			return $columns;
		}
		/**
		 * Display shortcode, type, size and color in columns in tiles list
		 * @param string column key
		 * @param number post id
		 */
		function tile_column( $column, $post_id ){
			switch( $column ) {
				case 'shortcode':
					echo '<code>[tile id="'.$post_id.'"]</code>';
					break;
				case 'type':
					echo ucwords(get_post_meta($post_id, '_tile_type', true));
					break;
				case 'size':
					echo ucwords(get_post_meta($post_id, '_tile_size', true));
					break;
				case 'color':
					$color_url = themify_check('_tile_color')? themify_get('_tile_color') : 'default';
					echo '<img src="'.THEME_URI.'/images/layout-icons/color-'.$color_url.'.png" />';
					break;
			}
		}
		/**
		 * Define sortable columns
		 * @param array
		 * @return array
		 */
		function tile_column_sortable( $columns ) {
			$columns['type'] = 'type';
			$columns['size'] = 'size';
			$columns['color'] = 'color';
			return $columns;
		}


		/**
		 * Function to output TILE shortcode
		 * @param array shortcode attributes
		 * @param string post type
		 */
		function shortcode($atts = array(), $post_type) {
			extract($atts);
			// Pagination
			global $paged;
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			// Parameters to get posts
			$args = array(
				'post_type' => $post_type,
				'posts_per_page' => isset( $atts['limit'] ) ? $atts['limit'] : 5,
				'order' => $order,
				'orderby' => $orderby,
				'suppress_filters' => false,
				'paged' => $paged
			);
			// Category parameters
			$args['tax_query'] = $this->parse_category_args($category, $post_type);

			/** @var string defines tile layout type */
			$cpt_layout_class = $this->post_type.'-wrapper';

			// Single post type or many single post types
			if( '' != $id ){
				if(strpos($id, ',')){
					$ids = explode(',', str_replace(' ', '', $id));
					foreach ($ids as $string_id) {
						$int_ids[] = intval($string_id);
					}
					$args['post__in'] = $int_ids;
					$args['orderby'] = 'post__in';

					// when using multiple IDs, set the default posts_per_page args to the number of tiles we want to display
					if( ! isset( $atts['limit'] ) ) {
						$args['posts_per_page'] = count( $ids );
					}
				} else {
					$args['p'] = intval($id);
					$cpt_layout_class = $this->post_type.'-single';
				}
			}

			// Get posts according to parameters
			$cpt_query = new WP_Query();
			$posts = $cpt_query->query(apply_filters('themify_'.$post_type.'_shortcode_args', $args));

			/** Collect markup generated to be returned later
			 *  @var string */
			$out = '';

			if( $posts ) {

				global $post;

				$out .= '<div class="loops-wrapper '.$cpt_layout_class.'">';

				foreach ($posts as $post) {
					setup_postdata($post);
					$tile_id = $post->ID;

					global $Metro;
					$tile_css = $Metro->get_custom_post_css( $tile_id );

					$tile_type = themify_get('_tile_type');
					$tile_size = themify_get('_tile_size');
					$tile_color = themify_get('_tile_color');
					$class = 'tile-post ';
					if('image' == $tile_type || 'map' == $tile_type) {
						$class .= $tile_size . ' ' . $tile_type;
					} else {
						$class .= $tile_size . ' ' . $tile_color . ' ' . $tile_type;
					}

					$out .= '<div id="tile-' . $post->ID . '" class="' . implode(' ', get_post_class($class)) . '">';
					if(('image' == $tile_type || 'map' == $tile_type) && '' != $post->post_content) {
						$out .= '<div class="tile-flip-wrapper"><div class="tile-flip">';
					}
					switch ($tile_size) {
						case 'small':
							$image_w = themify_check('image_width')? themify_get('image_width'): '158';
							$image_h = themify_check('image_height') || '0' == themify_get('image_height') ? themify_get('image_height'): '158';
							break;

						case 'medium':
							$image_w = themify_check('image_width')? themify_get('image_width'): '326';
							$image_h = themify_check('image_height') || '0' == themify_get('image_height') ? themify_get('image_height'): '158';
							break;

						case 'large':
							$image_w = themify_check('image_width')? themify_get('image_width'): '326';
							$image_h = themify_check('image_height') || '0' == themify_get('image_height') ? themify_get('image_height'): '326';
							break;

						default:
							/**
							 * Filter the default width and height for images in tile.
							 * Useful in case users create a new tile and want to set a different image size.
							 *
							 * @since 1.5.3
							 *
							 * @param array $size Index 0 stores width, Index 1 stores height.
							 */
							$def_wh = apply_filters( 'themify_tile_default_image_size', array( '326', '326' ), $tile_size );
							$image_w = themify_check('image_width')? themify_get('image_width'): $def_wh[0];
							$image_h = themify_check('image_height')? themify_get('image_height'): $def_wh[1];
							break;
					}
					if(('image' == $tile_type || 'map' == $tile_type) && '' != $post->post_content) {
						$out .= '<div class="front side">';
					}
					// Begins TILE CONTENT
					switch ($tile_type) {
						case 'map':
							$map_address = preg_replace( "[\n|\r]", ' ', themify_get('_map'));
							$out .= '<div class="tile-map">'.do_shortcode('[map address="'.$map_address.'"  zoom="'.themify_get('_map_zoom').'" width="'.$image_w.'px" height="'.$image_h.'px" ]').'</div>';
							break;
						case 'image':
							$out .= $this->post_image( 'yes', $image_w, $image_h, 'yes' != $unlink_image );
							break;
						case 'gallery':
							// Create Images Slider
							if( themify_check('_gallery_shortcode') ) {

								// open links in new tab?
								$new_tab = themify_check( 'new_tab' ) ? 'target="_blank"' : '';

								// Parse gallery shortcode
								$gallery_images = $this->get_images_from_gallery_shortcode();

								if ( themify_check('tile_slider_autoplay') ) {
									$autoplay = themify_get('tile_slider_autoplay');
								} elseif ( themify_check('setting-portfolio_slider_autoplay') ) {
									$autoplay = themify_get('setting-portfolio_slider_autoplay');
								} else {
									$autoplay = '4000';
								}

								if ( themify_check('tile_slider_effect') ) {
									$effect = themify_get('tile_slider_effect');
								} elseif ( themify_check('setting-portfolio_slider_effect') ) {
									$effect = themify_get('setting-portfolio_slider_effect');
								} else {
									$effect = 'scroll';
								}

								if ( themify_check('tile_slider_transition') ) {
									$speed = themify_get('tile_slider_transition');
								} elseif ( themify_check('setting-portfolio_slider_transition_speed') ) {
									$speed = themify_get('setting-portfolio_slider_transition_speed');
								} else {
									$speed = '500';
								}

								$out .= '<div id="'.$post_type.'-slider-'.$post->ID.'-'.$this->instance.'" class="post-image slideshow-wrap">
											<ul class="slideshow" data-id="'.$post_type.'-slider-'.$post->ID.'-'.$this->instance.'" data-autoplay="'.$autoplay.'" data-effect="'.$effect.'" data-speed="'.$speed.'" data-navigation="1">';
								foreach ( $gallery_images as $gallery_image ) {
									$img = wp_get_attachment_image_src($gallery_image->ID, 'large');
									$out .= '<li>';
									$bimg = $aimg = '';
									if( themify_check('external_link') && 'yes' != $unlink_image ) {
										$bimg = '<a href="'.esc_url(themify_get('external_link')).'" '. $new_tab .'>';
										$aimg = '</a>';
									}
									$caption = $gallery_image->post_excerpt;
									if( '' != $caption && ('content' == $display || 'excerpt' == $display) ) {
										$out .= '<div class="slider-image-caption">'.$caption.'</div>';
									}
									$out .= $bimg . themify_get_image('ignore=true&src=' . $img[0] . '&w=' . $image_w . '&h=' . $image_h) . $aimg;
									$out .= '</li>';
								}
								$out .= '</ul></div>';
							}
							break;
						case 'button':
							$image_w = themify_check('image_width')? themify_get('image_width'): '70';
							$image_h = themify_check('image_height') || '0' == themify_get('image_height')? themify_get('image_height'): '70';

							if ( '' != $button_link = themify_get('external_link') ) {
								$new_tab = themify_check( 'new_tab' ) ? 'target="_blank"' : '';
								$out .= '<a title="'.the_title_attribute('echo=0').'" href="'.esc_url($button_link).'" '. $new_tab .'>';
							} elseif ( '' != $button_link = themify_get('lightbox_link') ) {

								$link = esc_url($button_link);
								if(themify_check('iframe_url')) {
									$link = themify_get_lightbox_iframe_link( $link );
								}
								$link = $link . '" class="themify_lightbox';

								$out .= '<a title="'.the_title_attribute('echo=0').'" href="'.$link.'" >';
								$out .= themify_zoom_icon( false );
							}
							$out .= $this->post_image('yes', $image_w, $image_h, false, 'button-icon');
							$out .= $this->post_title('yes', 'span', false, 'button-title');
							if ( '' != $button_link )
								$out .= '</a>';
							break;
						case 'text':
							$out .= '<div class="tile-inner">';
							if ( 'no' != $title ) {
								if ( themify_check( 'external_link' ) ) {
									$out .= $this->post_title( 'yes', 'h3', true, 'tile-title' );
								} else {
									$out .= $this->post_title( 'yes', 'h3', false, 'tile-title' );
								}
							}
							$out .= apply_filters('the_content', $post->post_content);
							$out .= '</div>';
							break;

						default:

							break;
					}
					if(('image' == $tile_type || 'map' == $tile_type) && '' != $post->post_content) {
						$out .= '<a href="#" class="tile-flip-button"></a>';
						$out .= '</div>'; // front side
					}

					if( 'gallery' != $tile_type && 'text' != $tile_type && 'button' != $tile_type && '' != $post->post_content ){
						if(('image' == $tile_type || 'map' == $tile_type) && '' != $post->post_content) {
							$flip_class = 'back side ' . $tile_color;
						} else {
							$flip_class = '';
						}
						$out .= '<div class="tile-overlay '.$tile_color.' '. $flip_class .'">
								<div class="tile-inner">';
						if( 'image' == $tile_type && ( themify_check('external_link') || themify_check('lightbox_link') ) && 'yes' != $unlink_image ) {
							$before_image = '<a href="'.$this->get_entry_link(false, isset( $gallery_image->ID )? $gallery_image->ID : '').'">';
							$after_image = '</a>';
						} else {
							$before_image = '';
							$after_image = '';
						}
						$out .= $before_image;

						if ( 'no' != $title ) {
							switch ($tile_type) {
								case 'image':
									if ( themify_check( 'external_link' ) ) {
										$out .= $this->post_title( 'yes', 'span', false, 'tile-title' );
									} else {
										$out .= $this->post_title( 'yes', 'span', false, 'tile-title' );
									}
									break;
								default:
									$out .= $this->post_title('yes', 'h3', false, 'tile-title');
									break;
							}
						}

						// Post Content or Excerpt
						$out .= apply_filters('the_content', $post->post_content);

						$out .= $after_image;

						$out .= $this->edit_link($post->ID);
						$out .= '</div><!-- /.tile-inner -->';
						$out .= '<a href="#" class="tile-flip-button"></a>';
						$out .= '</div><!-- /.tile-overlay -->';

					}
					// Ends TILE CONTENT
					if(('image' == $tile_type || 'map' == $tile_type) && '' != $post->post_content) {
						$out .= '</div><!-- /.tile-flip --></div><!-- /.tile-flip-wrapper -->';
					}

					$out .= $tile_css;

					$out .= '</div><!-- /.tile '.$tile_id.' -->';

				} wp_reset_postdata();

				$out .= '</div><!-- .'.$cpt_layout_class.' -->'; // .tile-wrapper or .tile-single

				// Pagination
				if('yes' == $page_nav){
					$out .= themify_get_pagenav('', '', $cpt_query);
				}

				$out .= $this->section_link($more_link, $more_text, $post_type);
			}
			return $out;
		}
	}
}

if(!class_exists('Themify_Portfolio')){
	class Themify_Portfolio extends Themify_Shortcode {

		public $post_type = 'portfolio';
		public $taxonomies;

		/**
		 * Trigger at the end of __construct of this shortcode
		 */
		function shortcode__construct(){
			if(is_admin()){
				add_filter( "manage_edit-{$this->post_type}_columns", array(&$this, "{$this->post_type}_column_header"), 10, 2);
				add_filter( "manage_{$this->post_type}_posts_custom_column", array(&$this, "{$this->post_type}_column"), 10, 3);
				add_action( 'load-edit.php', array(&$this, "{$this->post_type}_load") );
			}
		}

		/**
		 * Filter request to sort tiles
		 */
		function portfolio_load() {
			add_action( current_filter(), array( $this, 'setup_vars' ), 20 );
			add_action( 'restrict_manage_posts', array( $this, 'get_select' ) );
			add_filter( "manage_taxonomies_for_{$this->post_type}_columns", array( $this, 'add_columns' ) );
		}

		/**
		 * Display an additional column in tiles list
		 * @param array
		 * @return array
		 */
		function portfolio_column_header($columns){
		    $columns['shortcode'] = __( 'Shortcode', 'themify' );
			$columns['color'] = __( 'Color', 'themify' );
		    return $columns;
		}
		/**
		 * Display shortcode, type, size and color in columns in tiles list
		 * @param string column key
		 * @param number post id
		 */
		function portfolio_column( $column, $post_id ){
			switch( $column ) {
				case 'shortcode':
					echo '<code>[portfolio id="'.$post_id.'"]</code>';
					break;
				case 'color':
					$color_url = themify_check('_portfolio_color')? themify_get('_portfolio_color') : 'default';
					echo '<img src="'.THEME_URI.'/images/layout-icons/color-'.$color_url.'.png" />';
					break;
			}
		}

		function shortcode($atts = array(), $post_type){
			extract($atts);
			// Pagination
			global $paged;
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			// Parameters to get posts
			$args = array(
				'post_type' => $post_type,
				'posts_per_page' => $limit,
				'order' => $order,
				'orderby' => $orderby,
				'suppress_filters' => false,
				'paged' => $paged
			);
			// Category parameters
			$args['tax_query'] = $this->parse_category_args($category, $post_type);

			/** @var string defines tile layout type */
			$cpt_layout_class = $this->post_type.'-wrapper';

			// Single post type or many single post types
			if( '' != $id ){
				if(strpos($id, ',')){
					$ids = explode(',', str_replace(' ', '', $id));
					foreach ($ids as $string_id) {
						$int_ids[] = intval($string_id);
					}
					$args['post__in'] = $int_ids;
					$args['orderby'] = 'post__in';
				} else {
					$args['p'] = intval($id);
					$cpt_layout_class = $this->post_type.'-single';
				}
			}

			// Get posts according to parameters
			$portfolio_query = new WP_Query();
			$posts = $portfolio_query->query(apply_filters('themify_'.$post_type.'_shortcode_args', $args));

			// Grid Style
			if( '' == $style ){
				$style = themify_check('setting-default_'.$post_type.'_index_post_layout')?
							themify_get('setting-default_'.$post_type.'_index_post_layout'):
							'grid3';
			}

			if('grid3' == $style){
				$gridx_img_width = '326';
			} elseif('grid2' == $style){
				$gridx_img_width = '494';
			} else {
				$gridx_img_width = '603';
			}

			if( is_singular($post_type) ){
				if( '' == $image_w ){
					$image_w = themify_check('setting-default_'.$post_type.'_single_image_post_width')?
							themify_get('setting-default_'.$post_type.'_single_image_post_width'):
							$gridx_img_width;
				}
				if( '' == $image_h ){
					$image_h = themify_check('setting-default_'.$post_type.'_single_image_post_height')?
							themify_get('setting-default_'.$post_type.'_single_image_post_height'):
							'175';
				}
				if( '' == $post_date ){
					$post_date = themify_check('setting-default_'.$post_type.'_single_post_date')?
							themify_get('setting-default_'.$post_type.'_index_post_date'):
							'no';
				}
				if( '' == $title ){
					$title = themify_check('setting-default_'.$post_type.'_single_title')?
							themify_get('setting-default_'.$post_type.'_single_title'):
							'yes';
				}
				if( '' == $unlink_title ){
					$unlink_title = themify_check('setting-default_'.$post_type.'_single_unlink_post_title')?
							themify_get('setting-default_'.$post_type.'_single_unlink_post_title'):
							'no';
				}
				if( '' == $post_meta ){
					$post_meta = themify_check('setting-default_'.$post_type.'_single_meta')?
							themify_get('setting-default_'.$post_type.'_single_meta'):
							'yes';
				}
			} else {
				if( '' == $image_w ){
					$image_w = themify_check('setting-default_'.$post_type.'_index_image_post_width')?
							themify_get('setting-default_'.$post_type.'_index_image_post_width'):
							$gridx_img_width;
				}
				if( '' == $image_h ){
					$image_h = themify_check('setting-default_'.$post_type.'_index_image_post_height')?
							themify_get('setting-default_'.$post_type.'_index_image_post_height'):
							'175';
				}
				if( '' == $post_date ){
					$post_date = themify_check('setting-default_'.$post_type.'_index_post_date')?
							themify_get('setting-default_'.$post_type.'_index_post_date'):
							'no';
				}
				if( '' == $title ){
					$title = themify_check('setting-default_'.$post_type.'_index_title')?
							themify_get('setting-default_'.$post_type.'_index_title'):
							'yes';
				}
				if( '' == $unlink_title ){
					$unlink_title = themify_check('setting-default_'.$post_type.'_index_unlink_post_title')?
							themify_get('setting-default_'.$post_type.'_index_unlink_post_title'):
							'no';
				}
				if( '' == $post_meta ){
					$post_meta = themify_check('setting-default_'.$post_type.'_index_meta')?
							themify_get('setting-default_'.$post_type.'_index_meta'):
							'no';
				}
			}

			$default_image_w = $image_w;
			$default_image_h = $image_h;

			/** Collect markup generated to be returned later
			 *  @var string */
			$out = '';
			if( $posts ) {
				global $post;

				$out .= '<div class="loops-wrapper shortcode ' . $post_type  . ' ' . $cpt_layout_class . ' ' . $style . '">';
				foreach ($posts as $post) {
					setup_postdata($post);

					$categories = wp_get_object_terms($post->ID, $post_type . '-category');

					/** @var string Portfolio color selected */
					$portfolio_color = themify_check('_portfolio_color')?
						themify_get('_portfolio_color') :
						'default';

					if('default' == $portfolio_color){
						$portfolio_color = themify_get('setting-default_portfolio_color')? themify_get('setting-default_portfolio_color') : 'default';
					}

					/** @var string Portfolio categories */
					$class = '';
					foreach($categories as $cat){
						$class .= ' cat-'.$cat->term_id;
					}

					$out .= '<article id="portfolio-' . $post->ID . '" class="post clearfix portfolio-post ' . $class . ' '. $portfolio_color .'">';

						// Set image dimensions giving priority to dimensions set in custom panel
						if(themify_check('image_width')){
							$image_w = themify_get('image_width');
						} else {
							$image_w = $default_image_w;
						}
						if(themify_check('image_height')){
							$image_h = themify_get('image_height');
						} else {
							$image_h = $default_image_h;
						}

						$out .= '<div class="tile-flip-wrapper"><div class="tile-flip"><div class="front side">';

						// Create Images Slider
						if(themify_check('_gallery_shortcode')){

							// Parse gallery shortcode
							$gallery_images = $this->get_images_from_gallery_shortcode();

							if( '' == $autoplay ){
								$autoplay = themify_check('setting-'.$post_type.'_slider_autoplay')?
										themify_get('setting-'.$post_type.'_slider_autoplay'):
										'4000';
							}
							if( '' == $effect ){
								$effect = themify_check('setting-'.$post_type.'_slider_effect')?
										themify_get('setting-'.$post_type.'_slider_effect'):
										'scroll';
							}
							if( '' == $speed ){
								$speed = themify_check('setting-'.$post_type.'_slider_transition_speed')?
										themify_get('setting-'.$post_type.'_slider_transition_speed'):
										'500';
							}
							$navigation = !is_singular('portfolio') ? false : true;
							$out .= '<div id="'.$post_type.'-slider-'.$post->ID.'-'.$this->instance.'" class="post-image slideshow-wrap">
									<ul class="slideshow" data-id="'.$post_type.'-slider-'.$post->ID.'-'.$this->instance.'" data-autoplay="'.$autoplay.'" data-effect="'.$effect.'" data-speed="'.$speed.'" data-navigation="'.$navigation.'">';
							foreach ( $gallery_images as $gallery_image ) {
								$out .= '<li>';
									$out .= '<a href="'.$this->get_entry_link(false, $gallery_image->ID).'">';
									global $themify;
									$out .= $themify->portfolio_image($gallery_image->ID, $image_w, $image_h);
									$out .= '</a>';
								$out .= '</li>';
							}
							$out .= '</ul></div>';
						} else {
							// Single Image
							$out .= $this->post_image($image, $image_w, $image_h);
						}
						$images = null;

						$out .= '<a href="#" class="tile-flip-button"></a>';
						$out .= '</div>'; // front side

						$flip_class = 'back side';


						if('no' != $title || 'none' != $display){
							$out .= '<div class="tile-overlay '.$flip_class.'">';
								$out .= '<div class="post-inner">';

									// Post Meta
									if ($post_meta == "yes") {
										$out .= '<p class="post-meta">'. get_the_term_list($post->ID, $post_type.'-category', '<span class="post-category">', ', ', ' </span>') . '</p>';
									}

									// Post Title
									$out .= $this->post_title($title, 'h1', 'yes'!=$unlink_title);

									// Post Date
									if ( 'yes' == $post_date) {
										$out .= '<p class="post-date">' . get_the_date() . '</p>';
									}

									// Post Content or Excerpt
									$out .= $this->display($display);
									$out .= $this->edit_link($post->ID);
								$out .= '</div>';//.post-inner
								$out .= '<a href="#" class="tile-flip-button"></a>';
							$out .= "</div>";//.tile-overlay
						}

						$out .= '</div><!-- /.tile-flip --></div><!-- /.tile-flip-wrapper -->';
					global $Metro;
					$out .= $Metro->get_custom_post_css($post->ID);
					$out .= "</article>";//.post

				} wp_reset_postdata();

				$out .= '</div>';//.portfolio-wrapper or .portfolio-single

				// Pagination
				if('yes' == $page_nav){
					// Infinite Scroll for Portfolio
					if( 'infinite' == themify_get('setting-more_posts') || '' == themify_get('setting-more_posts') ){
						$total_pages = intval(ceil($portfolio_query->max_num_pages));

						$current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
						if( $total_pages > $current_page ){

							$out .= '<script type="text/javascript">var qp_max_pages = ' . $total_pages . '</script>';

							// This will conflict
							$out .= '<p id="load-more"><a href="' . next_posts( $total_pages, false ) . '">' . __('Load More', 'themify') . '</a></p>';
						}
					} else {
						$out .= themify_get_pagenav('', '', $portfolio_query);
					}

				} else {

				}

				$out .= $this->section_link($more_link, $more_text, $post_type);

			}

			return $out;
		}

		function shortcode_start(){}
	}
}

?>
