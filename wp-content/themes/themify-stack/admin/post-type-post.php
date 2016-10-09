<?php
/**
 * Post Meta Box Options
 * @param array $args
 * @return array
 * @since 1.0.7
 */
function themify_theme_post_meta_box( $args = array() ) {
	extract( $args );
	return array(
		// Layout
		array(
			'name' 		=> 'layout',
			'title' 		=> __('Sidebar Option', 'themify'),
			'description' => '',
			'type' 		=> 'layout',
			'show_title' => true,
			'meta'		=> array(
				array('value' => 'default', 'img' => 'images/layout-icons/default.png', 'selected' => true, 'title' => __('Default', 'themify')),
				array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify')),
				array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
				array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar ', 'themify'))
			)
		),
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
		// Post Format
		array(
			'name'             => 'post_format',
			'title'            => __( 'Post Format', 'themify' ),
			'description'      => '',
			'type'             => 'radio',
			'enable_toggle'    => true,
			'meta'             => array(
				array( 'value' => '0', 'name' => __( 'Standard', 'themify' ), 'selected' => true ),
				array( 'value' => 'image', 'name' => __( 'Image', 'themify' ) ),
				array( 'value' => 'video', 'name' => __( 'Video', 'themify' ) ),
				array( 'value' => 'gallery', 'name' => __( 'Gallery', 'themify' ) ),
				array( 'value' => 'quote', 'name' => __( 'Quote', 'themify' ) ),
				array( 'value' => 'audio', 'name' => __( 'Audio', 'themify' ) ),
				array( 'value' => 'link', 'name' => __( 'Link', 'themify' ) ),
				array( 'value' => 'aside', 'name' => __( 'Aside', 'themify' ) ),
			),
			'default_toggle' => '0'
		),
		// Gallery Shortcode
		array(
			'name'        => 'gallery_shortcode',
			'title'       => __( 'Gallery', 'themify' ),
			'description' => '',
			'type'        => 'gallery_shortcode',
			'toggle'      => 'gallery-toggle'
		),
		// Gallery Slider Controls
		array(
			'type' => 'multi',
			'name' => '_gallery_slider_controls',
			'title' => '',
			'meta' => array(
				'fields' => array(
					// Gallery background mode
					array(
						'name' 	=> 'gallery_stretch',
						'label' => '',
						'description' => '',
						'type' 	=> 'dropdown',
						'meta'	=> array(
							array( 'value' => 'cover', 'name' => __( 'Full Cover', 'themify' ), 'selected' => true ),
							array( 'value' => 'best-fit', 'name' => __( 'Best Fit', 'themify' ) )
						),
						'before' => __( 'Image Mode', 'themify' ) . '<br/> ',
						'after' => '',
					),
					// Slider Speed
					array(
						'name' => 'gallery_autoplay',
						'label' => '',
						'description' => '',
						'type' 		=> 'dropdown',
						'meta' => array(
							array('value' => 'off', 'name' => __('Off', 'themify')),
							array('value' => 1000,  'name' => __('1 Sec', 'themify')),
							array('value' => 2000,  'name' => __('2 Sec', 'themify')),
							array('value' => 3000,  'name' => __('3 Sec', 'themify')),
							array('value' => 4000,  'name' => __('4 Secs', 'themify'), 'selected' => true),
							array('value' => 5000,  'name' => __('5 Sec', 'themify')),
							array('value' => 6000,  'name' => __('6 Sec', 'themify')),
							array('value' => 7000,  'name' => __('7 Sec', 'themify')),
							array('value' => 8000,  'name' => __('8 Sec', 'themify')),
							array('value' => 9000,  'name' => __('9 Sec', 'themify')),
							array('value' => 10000, 'name' => __('10 Sec', 'themify')),
						),
						'before' => '<p>' . __('Auto Play', 'themify') . '<br/> ',
						'after' => '</p>'
					),
					// Slider Transition Speed
					array(
						'name' => 'gallery_transition',
						'label' => '',
						'description' => '',
						'type' 		=> 'dropdown',
						'meta' => array(
							array('value' => 500,  'name' => __('Fast', 'themify')),
							array('value' => 1000,  'name' => __('Normal', 'themify'), 'selected' => true),
							array('value' => 1500,  'name' => __('Slow', 'themify')),
						),
						'before' => '<p>' . __('Transition Speed', 'themify') . '<br/> ',
						'after' => '</p>'
					),
					// Slider Timer
					array(
						'name'        => 'timer',
						'title'       => '',
						'description' => __( 'Timer is not displayed if autoplay is off.', 'themify' ),
						'type'        => 'dropdown',
						'meta'        => array(
							array( 'value' => 'yes', 'name' => __( 'Yes', 'themify' ), 'selected' => true ),
							array( 'value' => 'no', 'name' => __( 'No', 'themify' ) )
						),
						'before' => '<p>' . __( 'Display Timer', 'themify' ) . '<br/> ',
						'after' => '</p>'
					),
				),
				'description' => '',
				'before' => '',
				'after' => '',
				'separator' => ''
			),
			'toggle'	=> array( 'gallery-toggle', 'gallery_posts-toggle' )
		),
		// Video URL
		array(
			'name'        => 'video_url',
			'title'       => __( 'Video URL', 'themify' ),
			'description' => sprintf( __( 'Video embed URL such as YouTube or Vimeo video url (<a href="%s">details</a>).', 'themify' ), 'http://themify.me/docs/video-embeds' ),
			'type'        => 'textbox',
			'meta'        => array(),
			'toggle'      => 'video-toggle',
		),
		// Audio URL
		array(
			'name'        => 'audio_url',
			'title'       => __( 'Audio URL', 'themify' ),
			'description' => __( 'For audio post (eg. mp3)', 'themify' ),
			'type'        => 'textbox',
			'toggle'      => 'audio-toggle',
			'meta'        => array()
		),
		// Quote Author
		array(
			'name'        => 'quote_author',
			'title'       => __( 'Quote Author', 'themify' ),
			'description' => __( 'For quote post', 'themify' ),
			'type'        => 'textbox',
			'toggle'      => 'quote-toggle',
			'meta'        => array()
		),
		// Quote Author Link
		array(
			'name'        => 'quote_author_link',
			'title'       => __( 'Quote Author Link', 'themify' ),
			'description' => __( 'For quote post', 'themify' ),
			'type'        => 'textbox',
			'toggle'      => 'quote-toggle',
			'meta'        => array()
		),
		// Post Image
		array(
			'name' 		=> 'post_image',
			'title' 		=> __('Featured Image', 'themify'),
			'description' => '',
			'type' 		=> 'image',
			'meta'		=> array(),
		),
		// Featured Image Size
		array(
			'name'	=>	'feature_size',
			'title'	=>	__('Image Size', 'themify'),
			'description' => sprintf( __( 'Image sizes can be set at <a href="%s">Media Settings</a>', 'themify' ), admin_url( 'options-media.php' ) ),
			'type'		 =>	'featimgdropdown',
			'display_callback' => 'themify_is_image_script_disabled'
		),
		// Multi field: Image Dimension
		themify_image_dimensions_field(),
		// Hide Post Title
		array(
			'name' 		=> 'hide_post_title',
			'title' 	=> __('Hide Post Title', 'themify'),
			'description' => '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			)
		),
		// Unlink Post Title
		array(
			'name' 		=> 'unlink_post_title',
			'title' 		=> __('Unlink Post Title', 'themify'),
			'description' => __('Unlink post title (it will display the post title without link)', 'themify'),
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			)
		),
		// Hide Post Meta
		themify_multi_meta_field(),
		// Hide Post Date
		array(
			'name' 		=> 'hide_post_date',
			'title' 		=> __('Hide Post Date', 'themify'),
			'description' => '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			)
		),
		// Hide Post Image
		array(
			'name' 		=> 'hide_post_image',
			'title' 		=> __('Hide Featured Image', 'themify'),
			'description' => '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'toggle'		=> array( '0-toggle', 'image-toggle', 'gallery-toggle', 'audio-toggle' )
		),
		// Unlink Post Image
		array(
			'name' 		=> 'unlink_post_image',
			'title' 		=> __('Unlink Featured Image', 'themify'),
			'description' => __('Display the Featured Image without link', 'themify'),
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'toggle'		=> array( '0-toggle', 'image-toggle', 'gallery-toggle', 'audio-toggle' )
		),
		// External Link
		array(
			'name' 		=> 'external_link',
			'title' 		=> __('External Link', 'themify'),
			'description' => __('Link Featured Image and Post Title to external URL', 'themify'),
			'type' 		=> 'textbox',
			'meta'		=> array()
		),
		// Lightbox Link + Zoom icon
		themify_lightbox_link_field(),
		// Separator
		array(
			'name'        => '_separator_background',
			'title'       => '',
			'description' => '',
			'type'        => 'separator',
			'meta'        => array(
				'html' => '<h4>' . __( 'Post Style in Archive Views', 'themify' ) . '</h4><hr class="meta_fields_separator"/>'
			),
		),
		// Background Color
		array(
			'name'        => 'background_color',
			'title'       => __( 'Background', 'themify' ),
			'description' => '',
			'type'        => 'color',
			'meta'        => array( 'default' => null ),
		),
		// Background image
		array(
			'name'        => 'background_image',
			'title'       => '',
			'type'        => 'image',
			'description' => '',
			'meta'        => array(),
			'before'      => '',
			'after'       => '',
		),
		// Background repeat
		array(
			'name'        => 'background_repeat',
			'title'       => __( 'Background Repeat', 'themify' ),
			'description' => '',
			'type'        => 'dropdown',
			'meta'        => array(
				array(
					'value' => '',
					'name'  => ''
				),
				array(
					'value' => 'fullcover',
					'name'  => __( 'Fullcover', 'themify' )
				),
				array(
					'value' => 'repeat',
					'name'  => __( 'Repeat', 'themify' )
				),
				array(
					'value' => 'repeat-x',
					'name'  => __( 'Repeat horizontally', 'themify' )
				),
				array(
					'value' => 'repeat-y',
					'name'  => __( 'Repeat vertically', 'themify' )
				),
			),
		),
		// Text color
		array(
			'name'        => 'text_color',
			'title'       => __( 'Text Color', 'themify' ),
			'description' => '',
			'type'        => 'color',
			'meta'        => array( 'default' => null ),
		),
		// Link color
		array(
			'name'        => 'link_color',
			'title'       => __( 'Link Color', 'themify' ),
			'description' => '',
			'type'        => 'color',
			'meta'        => array( 'default' => null ),
		),
	);
}

/**
 * Default Single Post Layout
 * @param array $data Theme settings data
 * @return string Markup for module.
 * @since 1.0.0
 */
function themify_default_post_layout( $data = array() ){
	$data = themify_get_data();

	/**
	 * Theme Settings Option Key Prefix
	 * @var string
	 */
	$prefix = 'setting-default_page_';

	/**
	 * Tertiary options <blank>|yes|no
	 * @var array
	 */
	$default_options = array(
		array('name' => '', 'value' => ''),
		array('name' => __('Yes', 'themify'), 'value' => 'yes'),
		array('name' => __('No', 'themify'), 'value' => 'no')
	);

	/**
	 * Sidebar placement options
	 * @var array
	 */
	$sidebar_location_options = array(
		array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify'), 'selected' => true),
		array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
		array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar', 'themify') )
	);
	/**
	 * Image alignment options
	 * @var array
	 */
	$alignment_options = array(
		array('name' => '', 'value' => ''),
		array('name' => __('Left', 'themify'), 'value' => 'left'),
		array('name' => __('Right', 'themify'), 'value' => 'right')
	);

	/**
	 * Module markup
	 * @var string
	 */
	$output = '';

	/**
	 * Post sidebar placement
	 */
	$output .= '<p>
					<span class="label">' . __('Post Sidebar Option', 'themify') . '</span>';
	$val = isset( $data[$prefix.'post_layout'] ) ? $data[$prefix.'post_layout'] : '';
	foreach ( $sidebar_location_options as $option ) {
		if ( ( '' == $val || ! $val || ! isset( $val ) ) && ( isset( $option['selected'] ) && $option['selected'] ) ) {
			$val = $option['value'];
		}
		if ( $val == $option['value'] ) {
			$class = 'selected';
		} else {
			$class = '';
		}
		$output .= '<a href="#" class="preview-icon ' . esc_attr( $class ) . '" title="' . esc_attr( $option['title'] ) . '"><img src="' . esc_url( THEME_URI.'/'.$option['img'] ) . '" alt="' . esc_attr( $option['value'] ) . '"  /></a>';
	}
	$output .= '	<input type="hidden" name="' . esc_attr( $prefix . 'post_layout' ) . '" class="val" value="' . esc_attr( $val ) . '" />
				</p>';

	/**
	 * Hide Post Title
	 */
	$output .= '<p>
					<span class="label">' . __('Hide Post Title', 'themify') . '</span>
					<select name="' . esc_attr( $prefix . 'post_title' ) . '">'.
	           themify_options_module($default_options, $prefix.'post_title') . '
					</select>
				</p>';

	/**
	 * Unlink Post Title
	 */
	$output .= '<p>
					<span class="label">' . __('Unlink Post Title', 'themify') . '</span>
					<select name="' . esc_attr( $prefix . 'unlink_post_title' ) . '">'.
	           themify_options_module($default_options, $prefix.'unlink_post_title') . '
					</select>
				</p>';

	/**
	 * Hide Post Meta
	 */
	$output .= themify_post_meta_options($prefix.'post_meta', $data);

	/**
	 * Hide Post Date
	 */
	$output .= '<p>
					<span class="label">' . __('Hide Post Date', 'themify') . '</span>
					<select name="' . esc_attr( $prefix ) . 'post_date">'.
	           themify_options_module($default_options, $prefix.'post_date') . '
					</select>
				</p>';

	/**
	 * Hide Featured Image
	 */
	$output .= '<p>
					<span class="label">' . __('Hide Featured Image', 'themify') . '</span>
					<select name="' . esc_attr( $prefix ) . 'post_image">'.
	           themify_options_module($default_options, $prefix.'post_image') . '
					</select>
				</p>';

	/**
	 * Unlink Featured Image
	 */
	$output .= '<p>
					<span class="label">' . __('Unlink Featured Image', 'themify') . '</span>
					<select name="' . esc_attr( $prefix ) . 'unlink_post_image">'.
	           themify_options_module($default_options, $prefix.'unlink_post_image') . '
					</select>
				</p>';
	/**
	 * Featured Image Sizes
	 */
	$output .= themify_feature_image_sizes_select('image_post_single_feature_size');

	/**
	 * Image dimensions
	 */
	$output .= '<p>
			<span class="label">' . __('Image Size', 'themify') . '</span>
					<input type="text" class="width2" name="setting-image_post_single_width" value="' . esc_attr( themify_get( 'setting-image_post_single_width' ) ) . '" /> ' . __('width', 'themify') . ' <small>(px)</small>
					<input type="text" class="width2" name="setting-image_post_single_height" value="' . esc_attr( themify_get( 'setting-image_post_single_height' ) ) . '" /> ' . __('height', 'themify') . ' <small>(px)</small>
					<br /><span class="pushlabel show_if_enabled_img_php"><small>' . __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify') . '</small></span>

				</p>';

	/**
	 * Disable comments
	 */
	$pre = 'setting-comments_posts';
	$output .= '<p><span class="label">' . __('Post Comments', 'themify') . '</span><label for="' . esc_attr( $pre ) . '"><input type="checkbox" id="' . esc_attr( $pre ) . '" name="' . esc_attr( $pre ) . '" ' . checked( themify_get( $pre ), 'on', false ) . ' /> ' . __('Disable comments in all Posts', 'themify') . '</label></p>';

	/**
	 * Show author box
	 */
	$pre = 'setting-post_author_box';
	$output .= '<p><span class="label">' . __('Show Author Box', 'themify') . '</span><label for="' . esc_attr( $pre ) . '"><input type="checkbox" id="' . esc_attr( $pre ) . '" name="' . esc_attr( $pre ) . '" ' . checked( themify_get( $pre ), 'on', false ) . ' /> ' . __('Show author box in all Posts', 'themify') . '</label></p>';

	/**
	 * Remove Post Navigation
	 */
	$pre = 'setting-post_nav_';
	$output .= '<p>
					<span class="label">' . __('Post Navigation', 'themify') . '</span>
					<label for="' . esc_attr( $pre . 'disable' ) . '">
						<input type="checkbox" id="' . esc_attr( $pre . 'disable' ) . '" name="' . esc_attr( $pre . 'disable' ) . '" '. checked( themify_get( $pre . 'disable' ), 'on', false ) .'/> ' . __('Remove Post Navigation', 'themify') . '
						</label>
					<span class="pushlabel vertical-grouped">
						<label for="' . esc_attr( $pre ) . 'same_cat">
							<input type="checkbox" id="' . esc_attr( $pre ) . 'same_cat" name="' . esc_attr( $pre . 'same_cat' ) . '" '. checked( themify_get( $pre . 'same_cat' ), 'on', false ) .'/> ' . __('Show only posts in the same category', 'themify') . '
						</label>
					</span>
				</p>';

	return $output;
}

if ( ! class_exists( 'Themify_Gallery' ) ) {

	class Themify_Gallery {

		var $instance = 0;

		function __construct( $args = array() ) {
			add_filter( 'themify_gallery_plugins_args', array($this, 'enable_gallery_area' ) );
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
			$params = array();
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
			if ( $timer = get_post_meta( $post_id, 'timer', true ) ) {
				$params['timer'] = $timer;
			} else {
				$params['timer'] = 'yes';
			}
			if ( 'best-fit' == get_post_meta( $post_id, 'gallery_stretch', true ) ) {
				$params['bgmode'] = 'best-fit';
			} else {
				$params['bgmode'] = 'cover';
			}
			return $params;
		}

		/**
		 * Returns a slider id based on the entry id and the instance id.
		 *
		 * @param $post_id
		 *
		 * @return string
		 */
		function get_slider_id( $post_id ) {
			$this->instance++;
			return $post_id . '-' . $this->instance;
		}
	}
}

$GLOBALS['themify_gallery'] = new Themify_Gallery();