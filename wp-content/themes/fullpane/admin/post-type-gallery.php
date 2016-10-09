<?php

/**
 * Gallery Meta Box Options
 * @param array $args
 * @return array
 * @since 1.0.7
 */
function themify_theme_gallery_meta_box( $args = array() ) {
	extract( $args );
	return array(
		// Content Width
		array(
			'name'=> 'content_width',
			'title' => __('Content Width', 'themify'),
			'description' => '',
			'type' => 'layout',
			'show_title' => true,
			'meta' => array(
				array(
					'value' => 'default_width',
					'img' => 'themify/img/default.png',
					'selected' => true,
					'title' => __( 'Default', 'themify' )
				),
				array(
					'value' => 'full_width',
					'img' => 'themify/img/fullwidth.png',
					'title' => __( 'Fullwidth', 'themify' )
				)
			)
		),
	    // Menu Bar Position
        array(
            'name'        => 'menu_bar_position',
            'title'       => __( 'Menu Bar Position', 'themify' ),
            'description' => '',
            'type'        => 'dropdown',
            'meta'        => array(
                array( 'value' => '', 'name' => '', 'selected' => true ),
                array(
                    'value' => 'menubar-bottom',
                    'name'  => __( 'Menu bar at bottom (footer panel at top)', 'themify' )
                ),
                array(
                    'value' => 'menubar-top',
                    'name'  => __( 'Menu bar at the top (footer bar at bottom)', 'themify' )
                )
            )
        ),
		// Post Image
		array(
			'name' 		=> 'post_image',
			'title' 		=> __('Featured Image', 'themify'),
			'description' => '',
			'type' 		=> 'image',
			'meta'		=> array()
		),
		// Featured Image Size
		array(
			'name'	=>	'feature_size',
			'title'	=>	__('Image Size', 'themify'),
			'description' => sprintf(__('Image sizes can be set at <a href="%s">Media Settings</a> and <a href="%s" target="_blank">Regenerated</a>', 'themify'), 'options-media.php', 'https://wordpress.org/plugins/regenerate-thumbnails/'),
			'type'		 =>	'featimgdropdown',
			'display_callback' => 'themify_is_image_script_disabled'
		),
		// Multi field: Image Dimension
		themify_image_dimensions_field(),
		// Gallery Shortcode
		array(
			'name' 		=> 'gallery_shortcode',
			'title' 	=> __('Gallery', 'themify'),
			'description' => '',
			'type' 		=> 'gallery_shortcode',
		),
	);
}

/**************************************************************************************************
 * Highlight Class - Shortcode
 **************************************************************************************************/

if ( ! class_exists( 'Themify_Gallery' ) ) {

	class Themify_Gallery {

		var $instance = 0;
		var $atts = array();
		var $post_type = 'gallery';
		var $tax = 'gallery-category';
		var $taxonomies;

		function __construct( $args = array() ) {
			add_action( 'init', array( $this, 'register' ) );
			add_shortcode( 'themify_' . $this->post_type . '_posts', array( $this, 'init_shortcode' ) );
			add_action( 'admin_init', array( $this, 'manage_and_filter' ) );
			add_action( 'save_post', array( $this, 'set_default_term' ), 100, 2 );
			add_filter( 'themify_post_types', array( $this, 'extend_post_types' ) );
			add_filter( 'themify_gallery_plugins_args', array( $this, 'enable_gallery_area' ) );
			add_filter( 'themify_types_excluded_in_search', array( $this, 'exclude_in_search' ) );

			add_action( 'wp_ajax_themify_get_gallery_entry', array( $this, 'themify_get_gallery_entry' ) );
			add_action( 'wp_ajax_nopriv_themify_get_gallery_entry', array( $this, 'themify_get_gallery_entry' ) );

			add_filter( 'themify_default_layout_condition', array( $this, 'sidebar_condition' ), 12 );
			add_filter( 'themify_default_layout', array( $this, 'sidebar' ), 12 );
		}

		/**
		 * Initialize gallery content area for fullscreen gallery
		 * @param $args
		 * @return mixed
		 */
		function enable_gallery_area( $args ) {
			$args['contentImagesAreas'] .= ', .type-gallery';
			return $args;
		}

		/**
		 * AJAX hook to return gallery entry
		 */
		function themify_get_gallery_entry() {

			if ( ! isset( $_POST['entry_id'] ) ) {
				echo json_encode( array(
					'error' => __( 'Entry ID not set', 'themify' ),
				));
				die();
			}

			$entry = get_post( $_POST['entry_id'] );
			setup_postdata( $entry );

			$tax = 'gallery-category';

			$terms = array();

			$raw_terms = get_the_terms( $entry->ID, $tax );

			if ( $raw_terms && ! is_wp_error( $raw_terms ) ) {
				foreach ( $raw_terms as $term ) {
					$terms[] = array(
						'name' => $term->name,
						'link' => get_term_link( $term, $tax )
					);
				}
			}

			echo json_encode( array(
				'title' => apply_filters( 'the_title', $entry->post_title ),
				'date' => apply_filters( 'the_date', mysql2date( 'M j, Y', $entry->post_date ) ),
				'content' => apply_filters( 'the_content', $entry->post_content ),
				'excerpt' => apply_filters( 'the_excerpt', get_the_excerpt() ),
				'link' => get_permalink( $entry->ID ),
				'terms' => $terms,
			));

			die();
		}

		/**
		 * Register post type and taxonomy
		 */
		function register() {
			$cpt = array(
				'plural' => __('Galleries', 'themify'),
				'singular' => __('Gallery', 'themify'),
			);
			register_post_type( $this->post_type, array(
				'labels' => array(
					'name' => $cpt['plural'],
					'singular_name' => $cpt['singular'],
					'add_new' => __( 'Add New', 'themify' ),
					'add_new_item' => __( 'Add New Gallery', 'themify' ),
					'edit_item' => __( 'Edit Gallery', 'themify' ),
					'new_item' => __( 'New Gallery', 'themify' ),
					'view_item' => __( 'View Gallery', 'themify' ),
					'search_items' => __( 'Search Galleries', 'themify' ),
					'not_found' => __( 'No Galleries found', 'themify' ),
					'not_found_in_trash' => __( 'No Galleries found in Trash', 'themify' ),
					'menu_name' => __( 'Galleries', 'themify' ),
				),
				'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
				'hierarchical' => false,
				'public' => true,
				'exclude_from_search' => false,
				'query_var' => true,
				'can_export' => true,
				'capability_type' => 'post'
			));
			register_taxonomy( $this->tax, array( $this->post_type ), array(
				'labels' => array(
					'name' => sprintf(__( '%s Categories', 'themify' ), $cpt['singular']),
					'singular_name' => sprintf(__( '%s Category', 'themify' ), $cpt['singular'])
				),
				'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'show_tagcloud' => true,
				'hierarchical' => true,
				'rewrite' => true,
				'query_var' => true
			));
			if ( is_admin() ) {
				add_filter('manage_edit-'.$this->tax.'_columns', array(&$this, 'taxonomy_header'), 10, 2);
				add_filter('manage_'.$this->tax.'_custom_column', array(&$this, 'taxonomy_column_id'), 10, 3);
				add_filter( 'attachment_fields_to_edit', array($this, 'attachment_fields_to_edit'), 10, 2 );
				add_action( 'edit_attachment', array($this, 'attachment_fields_to_save'), 10, 2 );

			}
		}

	function attachment_fields_to_edit( $form_fields, $post ) {

		if ( ! preg_match( '!^image/!', get_post_mime_type( $post->ID ) ) ) {
			return $form_fields;
		}

		$include = get_post_meta( $post->ID, 'themify_gallery_featured', true );

		$name = 'attachments[' . $post->ID . '][themify_gallery_featured]';

		$form_fields['themify_gallery_featured'] = array(
			'label' => __( 'Larger', 'themify' ),
			'input' => 'html',
			'helps' => __('Show larger image in the gallery.', 'themify'),
			'html'  => '<span class="setting"><label for="' . $name . '" class="setting"><input type="checkbox" name="' . $name . '" id="' . $name . '" value="featured" ' . checked( $include, 'featured', false ) . ' />' . '</label></span>',
		);

		return $form_fields;
	}

	function attachment_fields_to_save( $attachment_id ) {
		if( isset( $_REQUEST['attachments'][$attachment_id]['themify_gallery_featured'] ) && preg_match( '!^image/!', get_post_mime_type( $attachment_id ) ) ) {
			update_post_meta($attachment_id, 'themify_gallery_featured', 'featured');
		} else {
			update_post_meta($attachment_id, 'themify_gallery_featured', '');
		}
	}
	
		/**
		 * Show in Themify Settings module to exclude this post type in search results.
		 * @param $types
		 * @return mixed
		 */
		function exclude_in_search( $types ) {
			$types[$this->post_type] = $this->post_type;
			return $types;
		}

		/**
		 * Set default term for custom taxonomy and assign to post
		 * @param number
		 * @param object
		 */
		function set_default_term( $post_id, $post ) {
			if ( 'publish' === $post->post_status ) {
				$terms = wp_get_post_terms( $post_id, $this->tax );
				if ( empty( $terms ) ) {
					wp_set_object_terms( $post_id, __( 'Uncategorized', 'themify' ), $this->tax );
				}
			}
		}

		/**
		 * Display an additional column in categories list
		 * @since 1.0.0
		 */
		function taxonomy_header($cat_columns) {
			$cat_columns['cat_id'] = 'ID';
			return $cat_columns;
		}
		/**
		 * Display ID in additional column in categories list
		 * @since 1.0.0
		 */
		function taxonomy_column_id($null, $column, $termid) {
			return $termid;
		}

		/**
		 * Includes new post types registered in theme to array of post types managed by Themify
		 * @param array
		 * @return array
		 */
		function extend_post_types( $types ) {
			return array_merge( $types, array( $this->post_type ) );
		}

		/**
		 * Trigger at the end of __construct
		 */
		function manage_and_filter() {
			add_filter( "manage_edit-{$this->post_type}_columns", array( $this, 'type_column_header' ), 10, 2 );
			add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'type_column' ), 10, 3 );
			add_action( 'load-edit.php', array( $this, 'filter_load' ) );
			add_filter( 'post_row_actions', array( $this, 'remove_quick_edit' ), 10, 1 );
		}

		/**
		 * Remove quick edit action from entries list in admin
		 * @param $actions
		 * @return mixed
		 */
		function remove_quick_edit( $actions ) {
			global $post;
			if( $post->post_type == $this->post_type )
				unset($actions['inline hide-if-no-js']);
			return $actions;
		}

		/**
		 * Display an additional column in list
		 * @param array
		 * @return array
		 */
		function type_column_header( $columns ) {
			unset( $columns['date'] );
			$columns['icon'] = __('Icon', 'themify');
			return $columns;
		}

		/**
		 * Display shortcode, type, size and color in columns in tiles list
		 * @param string $column key
		 * @param number $post_id
		 * @return string
		 */
		function type_column( $column, $post_id ) {
			switch( $column ) {

				case 'icon' :
					the_post_thumbnail( array( 50, 50 ) );
					break;
			}
		}

		/**
		 * Filter request to sort
		 */
		function filter_load() {
			global $typenow;
			if ( $typenow == $this->post_type ) {
				add_action( current_filter(), array( $this, 'setup_vars' ), 20 );
				add_action( 'restrict_manage_posts', array( $this, 'get_select' ) );
				add_filter( "manage_taxonomies_for_{$this->post_type}_columns", array( $this, 'add_columns' ) );
			}
		}

		/**
		 * Add columns when filtering posts in edit.php
		 */
		public function add_columns( $taxonomies ) {
			return array_merge( $taxonomies, $this->taxonomies );
		}

		/**
		 * Parses the arguments given as category to see if they are category IDs or slugs and returns a proper tax_query
		 * @param $category
		 * @param $post_type
		 * @return array
		 */
		function parse_category_args( $category, $post_type ) {
			if ( 'all' != $category ) {
				$tax_query_terms = explode( ',', $category );
				if ( preg_match( '#[a-z]#', $category ) ) {
					return array(
						array(
							'taxonomy' => $post_type . '-category',
							'field'    => 'slug',
							'terms'    => $tax_query_terms
						)
					);
				} else {
					return array(
						array(
							'taxonomy' => $post_type . '-category',
							'field'    => 'ID',
							'terms'    => $tax_query_terms
						)
					);
				}
			}
		}

		/**
		 * Select form element to filter the post list
		 * @return string HTML
		 */
		public function get_select() {
			$html = '';
			foreach ($this->taxonomies as $tax) {
				$options = sprintf('<option value="">%s %s</option>', __('View All', 'themify'),
				get_taxonomy($tax)->label);
				$class = is_taxonomy_hierarchical($tax) ? ' class="level-0"' : '';
				foreach (get_terms( $tax ) as $taxon) {
					$options .= sprintf('<option %s%s value="%s">%s%s</option>', isset($_GET[$tax]) ? selected($taxon->slug, $_GET[$tax], false) : '', '0' !== $taxon->parent ? ' class="level-1"' : $class, $taxon->slug, '0' !== $taxon->parent ? str_repeat('&nbsp;', 3) : '', "{$taxon->name} ({$taxon->count})");
				}
				$html .= sprintf('<select name="%s" id="%s" class="postform">%s</select>', $tax, $tax, $options);
			}
			return print $html;
		}

		/**
		 * Setup vars when filtering posts in edit.php
		 */
		function setup_vars() {
			$this->post_type =  get_current_screen()->post_type;
			$this->taxonomies = array_diff(get_object_taxonomies($this->post_type), get_taxonomies(array('show_admin_column' => 'false')));
		}

		/**
		 * Returns link wrapped in paragraph either to the post type archive page or a custom location
		 * @param bool|string False does nothing, true goes to archive page, custom string sets custom location
		 * @param string Text to link
		 * @return string
		 */
		function section_link( $more_link = false, $more_text, $post_type ) {
			if ( $more_link ) {
				if ( 'true' == $more_link ) {
					$more_link = get_post_type_archive_link( $post_type );
				}
				return '<p class="more-link-wrap"><a href="' . esc_url( $more_link ) . '" class="more-link">' . $more_text . '</a></p>';
			}
			return '';
		}

		/**
		 * Returns class to add in columns when querying multiple entries
		 * @param string $style Entries layout
		 * @return string $col_class CSS class for column
		 */
		function column_class( $style ) {
			$col_class = '';
			switch ( $style ) {
				case 'grid4':
					$col_class = 'col4-1';
					break;
				case 'grid3':
					$col_class = 'col3-1';
					break;
				case 'grid2':
					$col_class = 'col2-1';
					break;
				default:
					$col_class = '';
					break;
			}
			return $col_class;
		}

		/**
		 * Add shortcode to WP
		 * @param $atts Array shortcode attributes
		 * @return String
		 * @since 1.0.0
		 */
		function init_shortcode( $atts ) {
			$this->instance++;
			$this->atts = array(
				'id' => '',
				'title' => 'yes', // no
				'image' => 'yes', // no
				'image_w' => 144,
				'image_h' => 144,
				'display' => 'content', // excerpt, none
				'more_link' => false, // true goes to post type archive, and admits custom link
				'more_text' => __('More &rarr;', 'themify'),
				'limit' => 6,
				'category' => 'all', // integer category ID
				'order' => 'DESC', // ASC
				'orderby' => 'date', // title, rand
				'style' => 'slider', // And only slider
				'auto' => '4000', // off, 1000 - 100000
				'transition' => 'normal', // fast, slow
				'bgmode' => 'cover', // best-fit
				'section_link' => false, // true goes to post type archive, and admits custom link
				'use_original_dimensions' => 'no' // yes
			);
			if ( ! isset( $atts['image_w'] ) || '' == $atts['image_w'] ) {
				if ( ! isset( $atts['style'] ) ) {
					$atts['style'] = 'slider';
				}
				switch ( $atts['style'] ) {
					case 'slider':
						$this->atts['image_w'] = 1280;
						$this->atts['image_h'] = 500;
						break;
				}
			}
			$shortcode_atts = shortcode_atts( $this->atts, $atts );
			return do_shortcode( $this->shortcode( $shortcode_atts, $this->post_type ) );
		}

		/**
		 * Main shortcode rendering
		 * @param array $atts
		 * @param $post_type
		 * @return string|void
		 */
		function shortcode($atts = array(), $post_type) {
			extract($atts);
			// Parameters to get posts
			$args = array(
				'post_type' => $post_type,
				'posts_per_page' => $limit,
				'order' => $order,
				'orderby' => $orderby,
				'suppress_filters' => false
			);
			$args['tax_query'] = $this->parse_category_args($category, $post_type);

			// Defines layout type
			$cpt_layout_class = $this->post_type.'-multiple clearfix type-multiple';
			$multiple = true;

			// Single post type or many single post types
			if( '' != $id ) {
				if(strpos($id, ',')) {
					$ids = explode(',', str_replace(' ', '', $id));
					foreach ($ids as $string_id) {
						$int_ids[] = intval($string_id);
					}
					$args['post__in'] = $int_ids;
					$args['orderby'] = 'post__in';
				} else {
					$args['p'] = intval($id);
					$cpt_layout_class = $this->post_type.'-single';
					$multiple = false;
				}
			}

			// Get posts according to parameters
			if ( apply_filters( 'themify_theme_always_exclude_password_protected', false ) ) {
				add_filter( 'posts_where', array( $this, 'exclude_password_protected' ) );
			}
			$get_posts = new WP_Query;
			$posts = $get_posts->query( apply_filters( 'themify_theme_get_gallery_posts', $args ) );
			if ( apply_filters( 'themify_theme_always_exclude_password_protected', false ) ) {
				remove_filter( 'posts_where', array( $this, 'exclude_password_protected' ) );
			}

			// Collect markup to be returned
			$out = '';

			if ( $posts ) {
				global $themify;
				$themify_save = clone $themify; // save a copy

				// override $themify object
				$themify->hide_title = 'yes' == $title? 'no': 'yes';
				$themify->hide_image = 'yes' == $image? 'no': 'yes';
				$themify->hide_meta_category = 'no';
				$themify->hide_meta_tag = 'no';
				$themify->hide_meta_author = 'yes';
				if ( ! $multiple ) {
					if( '' == $image_w || get_post_meta($args['p'], 'image_width', true ) ) {
						$themify->width = get_post_meta($args['p'], 'image_width', true );
					}
					if( '' == $image_h || get_post_meta($args['p'], 'image_height', true ) ) {
						$themify->height = get_post_meta($args['p'], 'image_height', true );
					}
				} else {
					$themify->width = $image_w;
					$themify->height = $image_h;
				}
				$themify->use_original_dimensions = 'yes' == $use_original_dimensions? 'yes': 'no';
				$themify->display_content = $display;
				$themify->more_link = $more_link;
				$themify->more_text = $more_text;
				$themify->post_layout = $style;
				$themify->col_class = $this->column_class( $style );
				$themify->media_position = themify_check( 'setting-default_media_position' )? themify_get( 'setting-default_media_position' ) : 'above';

				$themify->show_post_media = true;
				$themify->is_shortcode = true;

				// SHORTCODE RENDERING
				if ( false !== stripos( $style, 'slider' ) ) {
					$style = 'twg-slider';
					// We're displaying the gallery using the shortcode
					$themify->gallery_by_shortcode = true;
					$themify->gallery_by_shortcode_auto = $auto;
					$themify->gallery_by_shortcode_transition = $transition;
					$themify->gallery_by_shortcode_bgmode = $bgmode;
				}
				$this->entries = $posts;
				ob_start();
				get_template_part( 'includes/gallery', 'post_type' );
				$loop = ob_get_contents();
				ob_end_clean();
				$this->entries = false;

				$out = "<div id='$post_type-slider-$this->instance' class='loops-wrapper shortcode $post_type $style $cpt_layout_class'>$loop</div>" . $this->section_link( $more_link, $more_text, $post_type );

				$themify->gallery_by_shortcode = false;

				// END SHORTCODE RENDERING

				$themify = clone $themify_save; // revert to original $themify state
			}
                        wp_reset_postdata();
			return $out;
		}

		var $entries = false;

		/**
		 * Extract image IDs from gallery shortcode and try to return them as entries list
		 * @param string $field
		 * @return array|bool
		 * @since 1.0.0
		 */
		function get_gallery_images( $field = 'gallery_shortcode' ) {
			$gallery_shortcode = themify_get( $field );
			$image_ids = preg_replace( '#\[gallery(.*)ids="([0-9|,]*)"(.*)\]#i', '$2', $gallery_shortcode );

			$query_args = array(
				'post__in' => explode( ',', str_replace( ' ', '', $image_ids ) ),
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'numberposts' => -1,
				'orderby' => stripos( $gallery_shortcode, 'rand' ) ? 'rand': 'post__in',
				'order' => 'ASC'
			);
			$entries = get_posts( apply_filters( 'themify_theme_get_gallery_images', $query_args ) );

			if ( $entries ) {
				return $entries;
			}

			return false;
		}
                
                /**
		 * Get columns specified in gallery shortcode. Defaults to 3.
		 *
		 * @since 1.0.0
		 *
		 * @param string $field Custom field where the gallery shortcode is saved.
		 *
		 * @return string Number of columns.
		 */
		function get_gallery_columns( $field = 'gallery_shortcode' ) {
			$gallery_shortcode = themify_get( $field );
			preg_match( '#\[gallery(.*?)columns="([0-9])"(.*?)\]#i', $gallery_shortcode, $matches );

			if ( isset( $matches[2] ) && ctype_digit( $matches[2] ) ) {
				return (int) $matches[2];
			}
			return 3;
		}

		/**
		 * Get size specified in gallery shortcode. Defaults to large.
		 *
		 * @since 1.1.9
		 *
		 * @param string $field Custom field where the gallery shortcode is saved.
		 *
		 * @return string Number of columns.
		 */
		function get_gallery_size( $field = 'gallery_shortcode' ) {
			$gallery_shortcode = themify_get( $field );
			preg_match( '#\[gallery(.*?)size="([a-z]+)"(.*?)\]#i', $gallery_shortcode, $matches );
                        return isset( $matches[2] )? $matches[2]:'thumbnail';
		}
                
                
		/**
		 * Return gallery post type entries
		 * @param string $field
		 * @return array|bool
		 * @since 1.0.0
		 */
		function get_gallery_posts( $field = 'gallery_posts' ) {
			if ( is_array( $this->entries ) && ! empty( $this->entries ) ) {
				return $this->entries;
			}
			$query_term = themify_check( $field ) ? themify_get( $field ) : '0';
			$query_args = array(
				'post_type' => 'gallery',
				'posts_per_page' => 15,
				'no_found_rows' => true,
			);
			if ( '0' != $query_term ) {
				$query_args['tax_query'] = array(
					array(
						'taxonomy' => 'gallery-category',
						'field' => 'slug',
						'terms' => $query_term
					)
				);
			}
			if ( apply_filters( 'themify_theme_always_exclude_password_protected', false ) ) {
				add_filter( 'posts_where', array( $this, 'exclude_password_protected' ) );
			}
			$get_posts = new WP_Query;
			$entries = $get_posts->query( apply_filters( 'themify_theme_get_gallery_posts', $query_args ) );
			if ( apply_filters( 'themify_theme_always_exclude_password_protected', false ) ) {
				remove_filter( 'posts_where', array( $this, 'exclude_password_protected' ) );
			}

			if ( $entries ) {
				return $entries;
			}

			return false;
		}

		/**
		 * Exclude entries protected by password.
		 * 
		 * @since 1.0.0
		 * 
		 * @param string $where The query so far.
		 * 
		 * @return string Updated query excluding entries protected by password.
		 */
		function exclude_password_protected( $where ) {
			global $wpdb;
			return $where .= " AND {$wpdb->posts}.post_password = '' ";
		}

		/**
		 * Checks if there's a description and returns it, otherwise returns caption
		 * @param $image
		 * @return mixed
		 */
		function get_description( $image ) {
			if ( '' != $image->post_content ) {
				return $image->post_content;
			}
			return $image->post_excerpt;
		}

		/**
		 * Checks if there's a caption and returns it, otherwise returns description
		 * @param $image
		 * @return mixed
		 */
		function get_caption( $image ) {
			if ( '' != $image->post_excerpt ) {
				return $image->post_excerpt;
			}
			return $image->post_content;
		}

		/**
		 * Return slider parameters
		 * @param $post_id
		 * @return mixed
		 */
		function get_slider_params( $post_id ) {
			global $themify;
			$params = array();
			if ( $themify->gallery_by_shortcode ) {
				$params['autoplay'] = 'no' == $themify->gallery_by_shortcode_auto ? 'off' : 'on';
				switch ( $themify->gallery_by_shortcode_transition ) {
					case 'fast':
						$params['transition'] = '500';
						break;
					case 'normal':
						$params['transition'] = '1000';
						break;
					case 'slow':
						$params['transition'] = '1500';
						break;
				}
				$params['bgmode'] = $themify->gallery_by_shortcode_bgmode;
			} else {
				if ( $temp = get_post_meta( $post_id, 'gallery_autoplay', true ) ) {
					$params['autoplay'] = $temp;
				} else {
					$params['autoplay'] = '4000';
				}
				if ( $temp = get_post_meta( $post_id, 'gallery_transition', true ) ) {
					$params['transition'] = $temp;
				} else {
					$params['transition'] = '500';
				}
				if ( 'best-fit' == get_post_meta( $post_id, 'gallery_stretch', true ) ) {
					$params['bgmode'] = 'best-fit';
				} else {
					$params['bgmode'] = 'cover';
				}
			}
			return $params;
		}

		/**************************************************************************************************
		 * Body Classes for Portfolio index and single
		 **************************************************************************************************/

		/**
		 * Changes condition to filter sidebar layout class
		 * @param bool $condition
		 * @return bool
		 */
		function sidebar_condition( $condition ) {
			return $condition || is_singular('gallery');
		}
		/**
		 * Returns modified sidebar layout class
		 * @param string $class Original body class
		 * @return string
		 */
		function sidebar( $class ) {
			global $themify;
			$class = $themify->layout;
			if ( is_singular( 'gallery' ) ) {
				$class = 'sidebar-none';
			}
			return $class;
		}
	}
}

/**************************************************************************************************
 * Initialize Type Class
 **************************************************************************************************/
$GLOBALS['themify_gallery'] = new Themify_Gallery();