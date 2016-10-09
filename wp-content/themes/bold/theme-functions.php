<?php
/*
 * To add custom PHP functions to the theme, create a new 'custom-functions.php' file in the theme folder. 
 * They will be added to the theme automatically.
 */


	/////// Actions ////////
	add_action( 'wp_head', 'themify_viewport_tag' );
	add_action( 'wp_enqueue_scripts', 'themify_theme_enqueue_scripts', 11 );
	// Register Custom Menu Function - Action
	add_action( 'init', 'themify_register_custom_nav');
	
	/////// Filters ////////
	add_filter('themify_default_post_layout_condition', 'themify_theme_default_post_layout_condition', 12);
	add_filter('themify_default_post_layout', 'themify_theme_default_post_layout', 12);
        
	
	function themify_theme_default_post_layout_condition($condition) {
		return $condition || is_tax('portfolio-category');
	}
	
	function themify_theme_default_post_layout() {
		global $themify;
		// get default layout
		$class = $themify->post_layout;
		if('portfolio' == $themify->query_post_type) {
			$class = themify_check('portfolio_layout') ? themify_get('portfolio_layout') : themify_get('setting-default_post_layout');
		} elseif (is_tax('portfolio-category')) {
			$class = themify_check('setting-default_portfolio_index_post_layout')? themify_get('setting-default_portfolio_index_post_layout') : 'list-post';
		}
		return $class;
	}
	
	add_filter('themify_default_layout_condition', 'themify_theme_default_layout_condition', 12);
	add_filter('themify_default_layout', 'themify_theme_default_layout', 12);
	
	/**
	 * Changes condition to filter layout class
	 * @param bool $condition
	 * @return bool
	 */
	function themify_theme_default_layout_condition($condition) {
		global $themify;
		// if layout is not set or is the home page and front page displays is set to latest posts 
		return $condition || (is_home() && 'posts' == get_option('show_on_front')) || '' != $themify->query_category || is_tax('portfolio-category');
	}
	/**
	 * Returns modified layout class
	 * @param string $class Original body class
	 * @return string
	 */
	function themify_theme_default_layout($class) {
		global $themify;
		// get default layout
		$class = $themify->layout;
		// if it's the single view of the post
		if( is_singular('post') ) {
			// if there's a value in custom panel layout and it's not default, get it,
			// otherwise get theme settings layout 
			$class = (themify_get('layout') != 'default' && themify_check('layout')) ? themify_get('layout') : themify_get('setting-default_page_post_layout');
			// if for some reason we still don't have a layout value, grab the original one from theme options 
			if( '' == $class ){
				$class = $themify->layout;
			}
		} elseif (is_tax('portfolio-category')) {
			$class = themify_check('setting-default_portfolio_index_layout')? themify_get('setting-default_portfolio_index_layout') : 'sidebar-none';
		}
		return $class;
	}
	

/**
 * Enqueue Stylesheets and Scripts
 */
function themify_theme_enqueue_scripts(){
	global $wp_query;

	// Enqueue styles ////////////////////////////////////////////
	
	// Themify base styling
	wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), wp_get_theme()->display('Version') );

	// Themify Media Queries CSS
	wp_enqueue_style( 'themify-media-queries', THEME_URI . '/media-queries.css' );
	

	//Google Web Fonts embedding
	wp_enqueue_style( 'google-fonts', themify_https_esc('http://fonts.googleapis.com/css'). '?family=Old+Standard+TT:400,400italic|Oswald|Raleway:400,700' );

	// Enqueue scripts ////////////////////////////////////////////
	
	// Isotope, used to dinamically filter posts
	wp_enqueue_script( 'isotope', THEME_URI . '/js/jquery.isotope.min.js', array('jquery'), false, true );
	
	// Creates infinite scroll
	wp_enqueue_script( 'infinitescroll', THEME_URI . '/js/jquery.infinitescroll.min.js', array('jquery'), false, true );
	
	// Themify internal scripts
	wp_enqueue_script( 'theme-script',	THEME_URI . '/js/themify.script.js', array('jquery'), false, true );
	
	// Get auto infinite scroll setting
	$autoinfinite = '';
	if ( ! themify_get( 'setting-autoinfinite' ) ) {
		$autoinfinite = 'auto';
	}
	
	//Inject variable values in gallery script
	wp_localize_script( 'theme-script', 'themifyScript', array(
		'lightbox' => themify_lightbox_vars_init(),
		'lightboxContext' => apply_filters('themify_lightbox_context', '#pagewrap'),
		'loadingImg'   	=> THEME_URI . '/images/loading.gif',
		'maxPages'	   	=> $wp_query->max_num_pages,
		'autoInfinite' 	=> $autoinfinite,
		'fixedHeader'	=> apply_filters('themify_fixed_header', true)
	));
	
	//WordPress internal script to move the comment box to the right place when replying to a user
	if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' );
	
}

/**
 * Add viewport tag for responsive layouts
 */
function themify_viewport_tag(){
	echo "\n".'<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">'."\n";
}

/* Custom Write Panels
/***************************************************************************/

if ( ! function_exists('themify_get_google_web_fonts_list') ) {
	/**
	 * Returns a list of Google Web Fonts
	 * @return array
	 * @since 1.0.0
	 */
	function themify_get_google_web_fonts_list() {
		$google_fonts_list = array(
			array('value' => '', 'name' => ''),
			array(
				'value' => '',
				'name' => '--- '.__('Google Fonts', 'themify').' ---'
			)
		);
		foreach( themify_get_google_font_lists() as $font ) {
			$google_fonts_list[] = array(
				'value' => $font,
				'name' => $font
			);
		}
		return apply_filters('themify_get_google_web_fonts_list', $google_fonts_list);
	}
}

if ( ! function_exists('themify_get_web_safe_font_list') ) {
	/**
	 * Returns a list of web safe fonts
	 * @param bool $only_names
	 * @return mixed|void
	 * @since 1.0.0
	 */
	function themify_get_web_safe_font_list($only_names = false) {
		$web_safe_font_names = array(
			'Arial, Helvetica, sans-serif',
			'Verdana, Geneva, sans-serif',
			'Georgia, \'Times New Roman\', Times, serif',
			'\'Times New Roman\', Times, serif',
			'Tahoma, Geneva, sans-serif',
			'\'Trebuchet MS\', Arial, Helvetica, sans-serif',
			'Palatino, \'Palatino Linotype\', \'Book Antiqua\', serif',
			'\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif'
		);

		if( ! $only_names ) {
			$web_safe_fonts = array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => '', 'name' => '--- '.__('Web Safe Fonts', 'themify').' ---')
			);
			foreach( $web_safe_font_names as $font ) {
				$web_safe_fonts[] = array(
					'value' => $font,
					'name' => str_replace( '\'', '"', $font )
				);
			}
		} else {
			$web_safe_fonts = $web_safe_font_names;
		}

		return apply_filters( 'themify_get_web_safe_font_list', $web_safe_fonts );
	}
}

// Return Google Web Fonts list
$google_fonts_list = themify_get_google_web_fonts_list();
// Return Web Safe Fonts list
$fonts_list = themify_get_web_safe_font_list();

///////////////////////////////////////
// Setup Write Panel Options
///////////////////////////////////////

/** Definition for tri-state hide meta buttons
 *  @var array */
$states = array(
	array(
		'name' => __('Hide', 'themify'),
		'value' => 'yes',
		'icon' => THEMIFY_URI . '/img/ddbtn-check.png',
		'title' => __('Hide this meta', 'themify')
	),
	array(
		'name' => __('Do not hide', 'themify'),
		'value' => 'no',
		'icon' => THEMIFY_URI . '/img/ddbtn-cross.png',
		'title' => __('Show this meta', 'themify')
	),
	array(
		'name' => __('Theme default', 'themify'),
		'value' => '',
		'icon' => THEMIFY_URI . '/img/ddbtn-blank.png',
		'title' => __('Use theme settings', 'themify'),
		'default' => true
	)
);
	
/** 
 * Post Meta Box Options
 * @var array */
$post_meta_box_options = array(
	// Layout
	array(
		'name' 	=> 'layout',	
		'title' => __('Sidebar Option', 'themify'), 	
		'description' => '', 				
		'type' 	=> 'layout',
		'show_title' => true,			
		'meta'	=> array(
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
	// Post Style
	array(
		'name' => 'post_layout',
		'title' => __('Media Alignment'),
		'description' => '',
		'type' => 'layout',
		'show_title' => true,			
		'meta' => array(
			array('value' => 'media-default', 'img' => 'images/layout-icons/post-media-default.png', 'selected' => true, 'title' => __('Media Default', 'themify')),
			array('value' => 'media-left', 'img' => 'images/layout-icons/post-media-left.png', 'title' => __('Media Left', 'themify')),
			array('value' => 'media-center', 'img' => 'images/layout-icons/post-media-center.png', 'title' => __('Media Center', 'themify')),
			array('value' => 'media-right', 'img' => 'images/layout-icons/post-media-right.png', 'title' => __('Media Right', 'themify')),
		),
		'before' => '',
		'after' => ''
	),
	// Background Preset Color
	array(
		'name' => 'background_color_preset',
		'title' => __('Post Color'),
		'description' => '',
		'type' => 'layout',
		'show_title' => true,			
		'meta' => array(
			array('value' => 'default', 'img' => 'images/layout-icons/color-default.png', 'selected' => true, 'title' => __('Default', 'themify')),
			array('value' => 'blue', 'img' => 'images/layout-icons/color-blue.png', 'title' => __('Blue', 'themify')),
			array('value' => 'pink', 'img' => 'images/layout-icons/color-pink.png', 'title' => __('Pink', 'themify')),
			array('value' => 'yellow', 'img' => 'images/layout-icons/color-yellow.png', 'title' => __('Yellow', 'themify')),
			array('value' => 'green', 'img' => 'images/layout-icons/color-green.png', 'title' => __('Green', 'themify')),
			array('value' => 'red', 'img' => 'images/layout-icons/color-red.png', 'title' => __('Red', 'themify')),
			array('value' => 'purple', 'img' => 'images/layout-icons/color-purple.png', 'title' => __('Purple', 'themify')),
			array('value' => 'indigo', 'img' => 'images/layout-icons/color-indigo.png', 'title' => __('Indigo', 'themify')),
			array('value' => 'orange', 'img' => 'images/layout-icons/color-orange.png', 'title' => __('Orange', 'themify')),
			array('value' => 'gray', 'img' => 'images/layout-icons/color-gray.png', 'title' => __('Gray', 'themify')),
			array('value' => 'black', 'img' => 'images/layout-icons/color-black.png', 'title' => __('Black', 'themify'))
		),
		'before' => '',
		'after' => ''
	),
	// Media Type
	array(
		'name'		=> 'media_type',
		'title'		=> __('Media Type', 'themify'),
		'description' => '',
		'type'		=> 'layout',
		'show_title' => true,
		'meta'		=> array(
			array('value' => 'media-image', 'img' => 'images/layout-icons/type-image.png', 'selected' => true, 'title' => __('Image', 'themify')),
			array('value' => 'media-video', 'img' => 'images/layout-icons/type-video.png', 'title' => __('Video', 'themify')),
			array('value' => 'media-slider', 'img' => 'images/layout-icons/type-slider.png', 'title' => __('Slider', 'themify')),
			array('value' => 'media-gallery', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Gallery', 'themify')),
			array('value' => 'media-map', 'img' => 'images/layout-icons/type-map.png', 'title' => __('Map', 'themify')),
		),
		'enable_toggle' => true
	),
	// Post Image
	array(
		'name' 		=> "post_image",
		'title' 		=> __('Featured Image', 'themify'),
		'description' => '',
		"type" 		=> "image",
		'meta'		=> array(),
		'toggle'	=> array('media-image-toggle')
	),
   	// Featured Image Size
	array(
		'name'	=>	'feature_size',
		'title'	=>	__('Image Size', 'themify'),
		'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
		'type'		 =>	'featimgdropdown',
		'display_callback' => 'themify_is_image_script_disabled',
		'toggle'	=> array('media-image-toggle')
		),
	// Multi field: Image Dimension
	array(
		'type' => 'multi',
		'name' => 'image_dimensions',
		'title' => __('Image Dimension', 'themify'),
		'meta' => array(
			'fields' => array(
				// Image Width
				array(
					'name' => 'image_width',
					'label' => __('width', 'themify'),
					'description' => '',
					'type' => 'textbox',
					'meta' => array('size'=>'small'),
					'before' => '',
					'after' => '',
				),
				// Image Height
				array(
					'name' => 'image_height',
					'label' => __('height', 'themify'),
					'type' => 'textbox',
					'meta' => array('size'=>'small'),
					'before' => '',
					'after' => '',
				),
			),
			'description' => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'),
			'before' => '',
			'after' => '',
			'separator' => ''
		),
		'toggle'	=> array('media-image-toggle')
	),
	// Video URL
	array(
		'name' 		=> 'video_url',
		'title' 		=> __('Video URL', 'themify'),
		'description' => __('Video embed URL such as YouTube or Vimeo video url (<a href="https://themify.me/docs/video-embeds">details</a>)', 'themify'),
		'type' 		=> 'textbox',
		'meta'		=> array(),
		'toggle'	=> array('media-video-toggle')
	),
	// Map
	array(
		'name' 	=> '_multi_map',	
		'title' => __('Map Address', 'themify'),
		'type' 	=> 'multi',
		'meta' => array(
			'fields' => array(
				// Map Address
				array(
					'name' 		=> 'map',
					'label' 	=> '',
					'description' => '',			
					'type' 		=> 'textarea',
				),
				// Map Zoom Level
				array(
					'name' 		=> 'map_zoom',
					'label' 	=> __('Zoom Level', 'themify'),
					'description'	=> '',
					'type' 		=> 'dropdown',
					'meta'		=> array(
							array('value' => '1', 'name' => '1'),
							array('value' => '2', 'name' => '2'),
							array('value' => '3', 'name' => '3'),
							array('value' => '4', 'name' => '4'),
							array('value' => '5', 'name' => '5'),
							array('value' => '6', 'name' => '6'),
							array('value' => '7', 'name' => '7'),
							array('value' => '8', 'name' => '8 - default', 'selected' => true),
							array('value' => '9', 'name' => '9'),
							array('value' => '10', 'name' => '10'),
							array('value' => '11', 'name' => '11'),
							array('value' => '12', 'name' => '12'),
							array('value' => '13', 'name' => '13'),
							array('value' => '14', 'name' => '14'),
							array('value' => '15', 'name' => '15'),
							array('value' => '16', 'name' => '16')
						)
					)
				),
			'description' => '',
			'before' => '',
			'after' => '',
			'separator'	=> '<br/>',
		),
		'toggle'	=> 'media-map-toggle'
	),
	// Slider
	array(
		'name' 		=> 'slider',
		'title' 	=> __('Slider', 'themify'),
		'description' => '',			
		'type' 		=> 'gallery_shortcode',			
		'toggle'	=> 'media-slider-toggle'
	),
	// Gallery Shortcode
	array(
		'name' 		=> 'gallery_shortcode',
		'title' 	=> __('Gallery', 'themify'),
		'description' => '',			
		'type' 		=> 'gallery_shortcode',			
		'toggle'	=> 'media-gallery-toggle'
	),
	// Hide Post Title
	array(
		  'name' 		=> "hide_post_title",	
		  'title' 		=> __('Hide Post Title', 'themify'), 	
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
		'name' 		=> "unlink_post_title",	
		'title' 	=> __('Unlink Post Title', 'themify'), 	
		'description' => __('Display the post title without link', 'themify'), 				
		'type' 		=> 'dropdown',			
		'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		)
	),
	// Hide Post Meta
	array(
		'name' 		=> '_hide_meta_multi',	
		'title' 	=> __('Hide Post Meta', 'themify'), 	
		'description' => '', 				
		'type' 		=> 'multi',			
		'meta'		=>  array (
			'fields' => array(
				array(
					'name' => 'hide_meta_all',
					'title' => __('Hide All', 'themify'),
					'description' => '',
					'type' => 'dropdownbutton',
					'states' => $states,
					'main' => true,
					'disable_value' => 'yes'
				),
				array(
					'name' => 'hide_meta_author',
					'title' => __('Author', 'themify'),
					'description' => '',
					'type' => 'dropdownbutton',
					'states' => $states,
					'sub' => true
				),
				array(
					'name' => 'hide_meta_category',
					'title' => __('Category', 'themify'),
					'description' => '',
					'type' => 'dropdownbutton',
					'states' => $states,
					'sub' => true
				),
				array(
					'name' => 'hide_meta_comment',
					'title' => __('Comment', 'themify'),
					'description' => '',
					'type' => 'dropdownbutton',
					'states' => $states,
					'sub' => true
				),
				array(
					'name' => 'hide_meta_tag',
					'title' => __('Tag', 'themify'),
					'description' => '',
					'type' => 'dropdownbutton',
					'states' => $states,
					'sub' => true
				),
			),
			'description' => '',
			'before' => '',
			'after' => '',
			'separator' => ''
		),
	),
	// Hide Post Date
	array(
		'name' 		=> "hide_post_date",	
		'title' 	=> __('Hide Post Date', 'themify'), 	
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
		'name' 		=> "hide_post_image",	
		'title' 	=> __('Hide Featured Image', 'themify'), 	
		'description' => '', 				
		'type' 		=> 'dropdown',			
		'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		),
		'toggle'	=> array('media-image-toggle')
	),
	// Unlink Post Image
	array(
		'name' 		=> "unlink_post_image",	
		'title' 		=> __('Unlink Featured Image', 'themify'), 	
		'description' => __('Display the Featured Image without link', 'themify'), 				
		'type' 		=> 'dropdown',			
		'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
		),
		'toggle'	=> array('media-image-toggle')
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
	array(
		'name' 	=> '_multi_lightbox_link',	
		'title' => __('Lightbox Link', 'themify'), 	
		'description' => '', 				
		'type' 	=> 'multi',			
		'meta'	=> array(
			'fields' => array(
		  		// Lightbox link field
		  		array(
					'name' 	=> 'lightbox_link',
					'label' => '',
					'description' => __('Link Featured Image and Post Title to lightbox image, video or iframe URL <br/>(<a href="https://themify.me/docs/lightbox">learn more</a>)', 'themify'),
					'type' 	=> 'textbox',
					'meta'	=> array(),
					'before' => '',
					'after' => '',
				),
				array(
					'name' 		=> 'iframe_url',
					'label' 		=> __('iFrame URL', 'themify'),
					'description' => '',
					'type' 		=> 'checkbox',
					'before' => '',
					'after' => '',
				),
				array(
					'name' 		=> 'lightbox_icon',
					'label' 		=> __('Add zoom icon on lightbox link', 'themify'),
					'description' => '',
					'type' 		=> 'checkbox',
					'before' => '',
					'after' => '',
				)
			),
			'description' => '',
			'before' => '',
			'after' => '',
			'separator' => ''
		)
	),
);

/** 
 * Page Meta Box Options
 * @var array */
$page_meta_box_options = array(
  	// Page Layout
	array(
		'name' 		=> 'page_layout',
		'title'		=> __('Sidebar Option', 'themify'),
		'description'	=> '',
		'type'		=> 'layout',
		'show_title' => true,
		'meta'	=> array(
			array('value' => 'default',	'img' => 'images/layout-icons/default.png', 'selected' => true, 'title' => __('Default', 'themify')),
			array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify')),
			array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
			array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar ', 'themify'))
		),
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
		// Hide page title
	array(
		  'name' 		=> 'hide_page_title',
		  'title'		=> __('Hide Page Title', 'themify'),
		  'description'	=> '',
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		)
	),
		// Custom menu for page
        array(
            'name' 		=> 'custom_menu',
            'title'		=> __( 'Custom Menu', 'themify' ),
            'description'	=> '',
            'type'		=> 'dropdown',
            'meta'		=> themify_get_available_menus(),
        ),
);
$query_post_meta_box = array(
	// Post Category
	array(
		'name' 		=> 'query_category',
		'title'		=> __('Post Category', 'themify'),
		'description'	=> __('Select a category or enter multiple category IDs (eg. 2,5,6). Enter 0 to display all categories.', 'themify'),
		'type'		=> 'query_category',
		'meta'		=> array()
	),
	// Descending or Ascending Order for Posts
	array(
		'name' 		=> 'order',
		'title'		=> __('Order', 'themify'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array('name' => __('Descending', 'themify'), 'value' => 'desc', 'selected' => true),
			array('name' => __('Ascending', 'themify'), 'value' => 'asc')
		)
	),
	// Criteria to Order By
	array(
		'name' 		=> 'orderby',
		'title'		=> __('Order By', 'themify'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array('name' => __('Date', 'themify'), 'value' => 'date', 'selected' => true),
			array('name' => __('Random', 'themify'), 'value' => 'rand'),
			array('name' => __('Author', 'themify'), 'value' => 'author'),
			array('name' => __('Post Title', 'themify'), 'value' => 'title'),
			array('name' => __('Comments Number', 'themify'), 'value' => 'comment_count'),
			array('name' => __('Modified Date', 'themify'), 'value' => 'modified'),
			array('name' => __('Post Slug', 'themify'), 'value' => 'name'),
			array('name' => __('Post ID', 'themify'), 'value' => 'ID')
		)
	),
	// Post Layout
	array(
		  'name' 		=> 'layout',
		  'title'		=> __('Post Layout', 'themify'),
		  'description'	=> '',
		  'type'		=> 'layout',
		  'show_title' => true,
		  'meta'		=> array(
				array('value' => 'list-post', 'img' => 'images/layout-icons/list-post.png', 'selected' => true, 'title' => __('List Post', 'themify')),
				array('value' => 'grid4', 'img' => 'images/layout-icons/grid4.png', 'title' => __('Grid 4', 'themify')),
				array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify')),
				array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify')),
			)
		),
	// Posts Per Page
	array(
		  'name' 		=> 'posts_per_page',
		  'title'		=> __('Posts per page', 'themify'),
		  'description'	=> '',
		  'type'		=> 'textbox',
		  'meta'		=> array('size' => 'small')
		),
	
	// Display Content
	array(
		'name' 		=> 'display_content',
		'title'		=> __('Display Content', 'themify'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array('name' => __('Full Content', 'themify'),'value'=>'content','selected'=>true),
			array('name' => __('Excerpt', 'themify'),'value'=>'excerpt'),
			array('name' => __('None', 'themify'),'value'=>'none')
		)
	),
	// Featured Image Size
	array(
		'name'	=>	'feature_size_page',
		'title'	=>	__('Image Size', 'themify'),
		'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
		'type'		 =>	'featimgdropdown',
		'display_callback' => 'themify_is_image_script_disabled'
		),
	// Image Width
	array(
		  'name' 		=> 'image_width',	
		  'title' 		=> __('Image Width', 'themify'), 
		  'description' => '', 				
		  'type' 		=> 'textbox',			
		  'meta'		=> array('size'=>'small')			
		),
	// Image Height
	array(
		  'name' 		=> 'image_height',	
		  'title' 		=> __('Image Height', 'themify'), 
		  'description' => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'), 				
		  'type' 		=> 'textbox',			
		  'meta'		=> array('size'=>'small')			
		),
	// Hide Title
	array(
		  'name' 		=> 'hide_title',
		  'title'		=> __('Hide Post Title', 'themify'),
		  'description'	=> '',
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
		  						array('value' => 'default', 'name' => '', 'selected' => true),
								array('value' => 'yes', 'name' => __('Yes', 'themify')),
								array('value' => 'no',	'name' => __('No', 'themify'))
							)
		),
	// Unlink Post Title
	array(
		  'name' 		=> 'unlink_title',	
		  'title' 		=> __('Unlink Post Title', 'themify'), 	
		  'description' => __('Unlink post title (it will display the post title without link)', 'themify'), 				
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
		  						array('value' => 'default', 'name' => '', 'selected' => true),
								array('value' => 'yes', 'name' => __('Yes', 'themify')),
								array('value' => 'no',	'name' => __('No', 'themify'))
							)			
		),
	// Hide Post Date
	array(
		  'name' 		=> 'hide_date',
		  'title'		=> __('Hide Post Date', 'themify'),
		  'description'	=> '',
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
		  						array('value' => 'default', 'name' => '', 'selected' => true),
								array('value' => 'yes', 'name' => __('Yes', 'themify')),
								array('value' => 'no',	'name' => __('No', 'themify'))
							)
		),
	// Hide Post Meta
	array(
		'name' 		=> '_hide_meta_multi',	
		'title' 	=> __('Hide Post Meta', 'themify'), 	
		'description' => '', 				
		'type' 		=> 'multi',			
		'meta'		=>  array (
			'fields' => array(
				array(
					'name' => 'hide_meta_all',
					'title' => __('Hide All', 'themify'),
					'description' => '',
					'type' => 'dropdownbutton',
					'states' => $states,
					'main' => true,
					'disable_value' => 'yes',
					'toggle'	=> array('post-toggle', 'portfolio-toggle')
				),
				array(
					'name' => 'hide_meta_author',
					'title' => __('Author', 'themify'),
					'description' => '',
					'type' => 'dropdownbutton',
					'states' => $states,
					'sub' => true,
					'toggle'	=> 'post-toggle'
				),
				array(
					'name' => 'hide_meta_category',
					'title' => __('Category', 'themify'),
					'description' => '',
					'type' => 'dropdownbutton',
					'states' => $states,
					'sub' => true,
					'toggle'	=> 'post-toggle'
				),
				array(
					'name' => 'hide_meta_comment',
					'title' => __('Comment', 'themify'),
					'description' => '',
					'type' => 'dropdownbutton',
					'states' => $states,
					'sub' => true,
					'toggle'	=> 'post-toggle'
				),
				array(
					'name' => 'hide_meta_tag',
					'title' => __('Tag', 'themify'),
					'description' => '',
					'type' => 'dropdownbutton',
					'states' => $states,
					'sub' => true,
					'toggle'	=> 'post-toggle'
				),
			),
			'description' => '',
			'before' => '',
			'after' => '',
			'separator' => ''
		),
	),
	// Media Above/Below Title
	array(
		'name' 		=> 'media_position',
		'title'		=> __('Featured Image/Media Position', 'themify'),
		'description'	=> '',
		'type' 		=> 'dropdown',			
		'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'below', 'name' => __('Below Post Title', 'themify')),
			array('value' => 'above', 'name' => __('Above Post Title', 'themify')),
		)
	),
	// Hide Post Image
	array(
		  'name' 		=> 'hide_image',	
		  'title' 		=> __('Hide Featured Image', 'themify'), 	
		  'description' => '', 				
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		)
	),
	// Unlink Post Image
	array(
		  'name' 		=> 'unlink_image',	
		  'title' 		=> __('Unlink Featured Image', 'themify'), 	
		  'description' => __('Display the Featured Image without link', 'themify'), 				
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		)			
	),
	// Page Navigation Visibility
	array(
		  'name' 		=> 'hide_navigation',
		  'title'		=> __('Hide Page Navigation', 'themify'),
		  'description'	=> '',
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		)
	)
);

/** 
 * Page Meta Box Options
 * @var array */
$query_portfolio_meta_box = array(
	// Notice
	array(
		'name' => '_query_posts_notice',
		'title' => '',
		'description' => '',
		'type' => 'separator',
		'meta' => array(
			'html' => '<div class="themify-info-link">' . sprintf( __( '<a href="%s">Query Posts</a> allows you to query WordPress posts from any category on the page. To use it, select a Query Category.', 'themify' ), 'http://themify.me/docs/query-posts' ) . '</div>'
		),
	),
	// Query Category
	array(
		'name' 		=> 'portfolio_query_category',
		'title'		=> __('Portfolio Category', 'themify'),
		'description'	=> __('Select a portfolio category or enter multiple portfolio category IDs (eg. 2,5,6). Enter 0 to display all portfolio categories.', 'themify'),
		'type'		=> 'query_category',
		'meta'		=> array('taxonomy' => 'portfolio-category')
	),
	// Descending or Ascending Order for Portfolios
	array(
		'name' 		=> 'portfolio_order',
		'title'		=> __('Order', 'themify'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array('name' => __('Descending', 'themify'), 'value' => 'desc', 'selected' => true),
			array('name' => __('Ascending', 'themify'), 'value' => 'asc')
		)
	),
	// Criteria to Order By
	array(
		'name' 		=> 'portfolio_orderby',
		'title'		=> __('Order By', 'themify'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array('name' => __('Date', 'themify'), 'value' => 'date', 'selected' => true),
			array('name' => __('Random', 'themify'), 'value' => 'rand'),
			array('name' => __('Author', 'themify'), 'value' => 'author'),
			array('name' => __('Post Title', 'themify'), 'value' => 'title'),
			array('name' => __('Comments Number', 'themify'), 'value' => 'comment_count'),
			array('name' => __('Modified Date', 'themify'), 'value' => 'modified'),
			array('name' => __('Post Slug', 'themify'), 'value' => 'name'),
			array('name' => __('Post ID', 'themify'), 'value' => 'ID')
		)
	),
	// Post Layout
	array(
		  'name' 		=> 'portfolio_layout',
		  'title'		=> __('Portfolio Layout', 'themify'),
		  'description'	=> '',
		  'type'		=> 'layout',
		  'show_title' => true,
		  'meta'		=> array(
				array('value' => 'list-post', 'img' => 'images/layout-icons/list-post.png', 'selected' => true),
				array('value' => 'grid4', 'img' => 'images/layout-icons/grid4.png', 'title' => __('Grid 4', 'themify')),
				array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify')),
				array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify')),
			)
		),
	// Posts Per Page
	array(
		  'name' 		=> 'portfolio_posts_per_page',
		  'title'		=> __('Portfolios per page', 'themify'),
		  'description'	=> '',
		  'type'		=> 'textbox',
		  'meta'		=> array('size' => 'small')
		),
	
	// Display Content
	array(
		  'name' 		=> 'portfolio_display_content',
		  'title'		=> __('Display Content', 'themify'),
		  'description'	=> '',
		  'type'		=> 'dropdown',
		  'meta'		=> array(
								array('name' => __('Full Content', 'themify'),'value'=>'content','selected'=>true),
		  						array('name' => __('Excerpt', 'themify'),'value'=>'excerpt'),
								array('name' => __('None', 'themify'),'value'=>'none')
							)
		),
	// Featured Image Size
	array(
		'name'	=>	'portfolio_feature_size_page',
		'title'	=>	__('Image Size', 'themify'),
		'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
		'type'		 =>	'featimgdropdown',
		'display_callback' => 'themify_is_image_script_disabled'
		),
	// Image Width
	array(
		  'name' 		=> 'portfolio_image_width',	
		  'title' 		=> __('Image Width', 'themify'), 
		  'description' => '', 				
		  'type' 		=> 'textbox',			
		  'meta'		=> array('size'=>'small')			
		),
	// Image Height
	array(
		  'name' 		=> 'portfolio_image_height',	
		  'title' 		=> __('Image Height', 'themify'), 
		  'description' => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'), 				
		  'type' 		=> 'textbox',			
		  'meta'		=> array('size'=>'small')			
		),
	// Hide Title
	array(
		  'name' 		=> 'portfolio_hide_title',
		  'title'		=> __('Hide Portfolio Title', 'themify'),
		  'description'	=> '',
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
		  						array('value' => 'default', 'name' => '', 'selected' => true),
								array('value' => 'yes', 'name' => __('Yes', 'themify')),
								array('value' => 'no',	'name' => __('No', 'themify'))
							)
		),
	// Unlink Post Title
	array(
		  'name' 		=> 'portfolio_unlink_title',	
		  'title' 		=> __('Unlink Portfolio Title', 'themify'), 	
		  'description' => __('Unlink portfolio title (it will display the post title without link)', 'themify'), 				
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
		  						array('value' => 'default', 'name' => '', 'selected' => true),
								array('value' => 'yes', 'name' => __('Yes', 'themify')),
								array('value' => 'no',	'name' => __('No', 'themify'))
							)			
		),
	// Hide Post Date
	array(
		  'name' 		=> 'portfolio_hide_date',
		  'title'		=> __('Hide Portfolio Date', 'themify'),
		  'description'	=> '',
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
		  						array('value' => 'default', 'name' => '', 'selected' => true),
								array('value' => 'yes', 'name' => __('Yes', 'themify')),
								array('value' => 'no',	'name' => __('No', 'themify'))
							)
		),
	// Hide Post Meta
	array(
		'name' 		=> 'portfolio_hide_meta_all',
		'title' 	=> __('Hide Portfolio Meta', 'themify'), 	
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
		  'name' 		=> 'portfolio_hide_image',	
		  'title' 		=> __('Hide Featured Image', 'themify'), 	
		  'description' => '', 				
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		)
	),
	// Unlink Post Image
	array(
		  'name' 		=> 'portfolio_unlink_image',	
		  'title' 		=> __('Unlink Featured Image', 'themify'), 	
		  'description' => __('Display the Featured Image without link', 'themify'), 				
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		)			
	),
	// Page Navigation Visibility
	array(
		  'name' 		=> 'portfolio_hide_navigation',
		  'title'		=> __('Hide Page Navigation', 'themify'),
		  'description'	=> '',
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		)
	)
);

/**
 * Post Styles Meta Box Options
 * @var array
 */
$post_styles = array(
	// Separator
	array(
		'name' => '_separator_font',
		'title' => '', 
		'description' => '',
		'type' => 'separator',
		'meta' => array('html'=>'<h4>'.__('Post Font').'</h4><hr class="meta_fields_separator"/>'),
	),
	// Predesigned Font
	array(
		'name' => 'preset_font',
		'title' => __('Preset Font'),
		'description' => '',
		'type' => 'layout',
		'show_title' => true,			
		'meta' => array(
			array('value' => 'default', 'img' => 'images/layout-icons/default.png', 'selected' => true),
			array('value' => 'sans-serif', 'img' => 'images/layout-icons/font-sans-serif.png'),
			array('value' => 'serif', 'img' => 'images/layout-icons/font-serif.png'),
		),
		'before' => '',
		'after' => ''
	),
	// Multi field: Font
	array(
		'type' => 'multi',
		'name' => '_font',
		'title' => __('Font', 'themify'),
		'meta' => array(
			'fields' => array(
				// Font size
				array(
					'name' => 'font_size',
					'label' => '',
					'description' => '',
					'type' => 'textbox',			
					'meta' => array('size'=>'small'),
					'before' => '',
					'after' => ''
				),
				// Font size unit
				array(
					'name' 	=> 'font_size_unit',
					'label' => '',
					'type' 	=> 'dropdown',	
					'meta'	=> array(
						array('value' => 'px', 'name' => __('px', 'themify'), 'selected' => true),
						array('value' => 'em', 'name' => __('em', 'themify'))
					),
					'before' => '',
					'after' => ''
				),
				// Font family
				array(
					'name' 	=> 'font_family',
					'label' => '',
					'type' 	=> 'dropdown',	
					'meta'	=> array_merge( $fonts_list, $google_fonts_list ),
					'before' => '',
					'after' => '',
				),
			),
			'description' => '',	
			'before' => '',
			'after' => '',
			'separator' => ''
		)
	),
	// Font Color
	array(
		'name' => 'font_color',
		'title' => __('Font Color', 'themify'), 
		'description' => '',
		'type' => 'color',
		'meta' => array('default'=>null),
	),
	// Link Color
	array(
		'name' => 'link_color',
		'title' => __('Link Color', 'themify'), 
		'description' => '',
		'type' => 'color',
		'meta' => array('default'=>null),
	),
	// Separator
	array(
		'name' => '_separator',
		'title' => '', 
		'description' => '',
		'type' => 'separator',
		'meta' => array('html'=>'<h4>'.__('Post Background').'</h4><hr class="meta_fields_separator"/>'),
	),
	// Background Color
	array(
		'name' => 'background_color',
		'title' => __('Background Color', 'themify'), 
		'description' => '',
		'type' => 'color',
		'meta' => array('default'=>null),
	),
	array(
		'name' => 'background_image_preset',
		'title' => __('Preset Images'),
		'description' => __('Select a preset background or upload your own', 'themify'),
		'type' => 'layout',
		'show_title' => true,	
		'meta' => array(
			array('value' => 'default', 'img' => 'images/layout-icons/color-default.png', 'selected' => true, 'title' => __('Default', 'themify')),
			array('value' => 'bg1', 'img' => 'uploads/bg/bg1.png'),
			array('value' => 'bg2', 'img' => 'uploads/bg/bg2.png'),
			array('value' => 'bg3', 'img' => 'uploads/bg/bg3.png'),
			array('value' => 'bg4', 'img' => 'uploads/bg/bg4.png'),
			array('value' => 'bg5', 'img' => 'uploads/bg/bg5.png'),
			array('value' => 'bg7', 'img' => 'uploads/bg/bg7.png'),
			array('value' => 'bg8', 'img' => 'uploads/bg/bg8.png'),
			array('value' => 'bg9', 'img' => 'uploads/bg/bg9.png'),
			array('value' => 'bg11','img' => 'uploads/bg/bg11.png'),
			array('value' => 'bg12','img' => 'uploads/bg/bg12.png'),
			array('value' => 'bg13','img' => 'uploads/bg/bg13.jpg'),
			array('value' => 'bg14','img' => 'uploads/bg/bg14.gif'),
			array('value' => 'bg15','img' => 'uploads/bg/bg15.jpg'),
			array('value' => 'bg16','img' => 'uploads/bg/bg16.png'),
			array('value' => 'bg17','img' => 'uploads/bg/bg17.jpg'),
			array('value' => 'bg18','img' => 'uploads/bg/bg18.jpg'),
			array('value' => 'bg19','img' => 'uploads/bg/bg19.png'),
			array('value' => 'bg20','img' => 'uploads/bg/bg20.png'),
			array('value' => 'bg21','img' => 'uploads/bg/bg21.png'),
			array('value' => 'bg22','img' => 'uploads/bg/bg22.jpg')
		),
		'size' => array(20, 20),
		'before' => '',
		'after' => ''
	),
	// Backgroud image
	array(
		'name' 	=> 'background_image',
		'title' => '',
		'type' 	=> 'image',
		'description' => '',	
		'meta'	=> array(),
		'before' => '',
		'after' => ''
	),
	// Background repeat
	array(
		'name' 		=> 'background_repeat',
		'title'		=> __('Background Repeat', 'themify'),
		'description'	=> '',
		'type' 		=> 'dropdown',			
		'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'repeat', 'name' => __('Repeat', 'themify')),
			array('value' => 'repeat-x', 'name' => __('Repeat horizontally', 'themify')),
			array('value' => 'repeat-y', 'name' => __('Repeat vertically', 'themify')),
			array('value' => 'no-repeat', 'name' => __('Do not repeat', 'themify'))
		)
	),
	// Multi field: Background Position
	array(
		'type' => 'multi',
		'name' => '_multi_bg_position',
		'title' => __('Background Position', 'themify'),
		'meta' => array(
			'fields' => array(
				// Background horizontal position
				array(
					'name'  => 'background_position_x',
					'label' => '',
					'description' => '',
					'type' 	=> 'dropdown',			
					'meta'	=> array(
						array('value' => '',   'name' => '', 'selected' => true),
						array('value' => 'left',   'name' => __('Left', 'themify')),
						array('value' => 'center', 'name' => __('Center', 'themify')),
						array('value' => 'right',  'name' => __('Right', 'themify'))
					),
					'before' => '',
					'after'  => ''
				),
				// Background vertical position
				array(
					'name'  => 'background_position_y',
					'label' => '',
					'description' => '',
					'type' 	=> 'dropdown',			
					'meta'	=> array(
						array('value' => '',   'name' => '', 'selected' => true),
						array('value' => 'top',   'name' => __('Top', 'themify')),
						array('value' => 'center', 'name' => __('Center', 'themify')),
						array('value' => 'bottom',  'name' => __('Bottom', 'themify'))
					),
					'before' => '',
					'after'  => ''
				),
			),
			'description' => '',	
			'before' => '',
			'after' => '',
			'separator' => ''
		)
	),
);

/** Portfolio Meta Box Options */
$portfolio_meta_box = array(
	// Post Style
	array(
		'name' => 'post_layout',
		'title' => __('Media Alignment'),
		'description' => '',
		'type' => 'layout',
		'show_title' => true,			
		'meta' => array(
			array('value' => 'media-default', 'img' => 'images/layout-icons/post-media-default.png', 'selected' => true),
			array('value' => 'media-left', 'img' => 'images/layout-icons/post-media-left.png'),
			array('value' => 'media-center', 'img' => 'images/layout-icons/post-media-center.png'),
			array('value' => 'media-right', 'img' => 'images/layout-icons/post-media-right.png'),
		),
		'before' => '',
		'after' => ''
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
	// Background Preset Color
	array(
		'name' => 'background_color_preset',
		'title' => __('Portfolio Color'),
		'description' => '',
		'type' => 'layout',
		'show_title' => true,
		'meta' => array(
			array('value' => 'default', 'img' => 'images/layout-icons/color-default.png', 'selected' => true, 'title' => __('Default', 'themify')),
			array('value' => 'blue', 'img' => 'images/layout-icons/color-blue.png', 'title' => __('Blue', 'themify')),
			array('value' => 'pink', 'img' => 'images/layout-icons/color-pink.png', 'title' => __('Pink', 'themify')),
			array('value' => 'yellow', 'img' => 'images/layout-icons/color-yellow.png'),
			array('value' => 'green', 'img' => 'images/layout-icons/color-green.png', 'title' => __('Green', 'themify')),
			array('value' => 'red', 'img' => 'images/layout-icons/color-red.png', 'title' => __('Red', 'themify')),
			array('value' => 'purple', 'img' => 'images/layout-icons/color-purple.png', 'title' => __('Purple', 'themify')),
			array('value' => 'indigo', 'img' => 'images/layout-icons/color-indigo.png'),
			array('value' => 'orange', 'img' => 'images/layout-icons/color-orange.png', 'title' => __('Orange', 'themify')),
			array('value' => 'gray', 'img' => 'images/layout-icons/color-gray.png'),
			array('value' => 'black', 'img' => 'images/layout-icons/color-black.png', 'title' => __('Black', 'themify'))
		),
		'before' => '',
		'after' => ''
	),
	// Media Type
	array(
		'name'		=> 'media_type',
		'title'		=> __('Media Type', 'themify'),
		'description' => '',
		'type'		=> 'layout',
		'show_title' => true,
		'meta'		=> array(
			array('value' => 'media-image', 'img' => 'images/layout-icons/type-image.png', 'selected' => true),
			array('value' => 'media-video', 'img' => 'images/layout-icons/type-video.png', ),
			array('value' => 'media-slider', 'img' => 'images/layout-icons/type-slider.png'),
			array('value' => 'media-gallery', 'img' => 'images/layout-icons/grid3.png'),
		),
		'enable_toggle' => true
	),
	// Post Image
	array(
		'name' 		=> "post_image",
		'title' 		=> __('Featured Image', 'themify'),
		'description' => '',
		"type" 		=> "image",
		'meta'		=> array(),
		'toggle'	=> array('media-image-toggle')
	),
   	// Featured Image Size
	array(
		'name'	=>	'feature_size',
		'title'	=>	__('Image Size', 'themify'),
		'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
		'type'		 =>	'featimgdropdown',
		'toggle'	=> array('media-image-toggle'),
		'display_callback' => 'themify_is_image_script_disabled'
		),
	// Multi field: Image Dimension
	array(
		'type' => 'multi',
		'name' => 'image_dimensions',
		'title' => __('Image Dimension', 'themify'),
		'meta' => array(
			'fields' => array(
				// Image Width
				array(
					'name' => 'image_width',	
					'label' => __('width', 'themify'), 
					'description' => '',
					'type' => 'textbox',			
					'meta' => array('size'=>'small'),
					'before' => '',
					'after' => '',
				),
				// Image Height
				array(
					'name' => 'image_height',
					'label' => __('height', 'themify'),
					'type' => 'textbox',						
					'meta' => array('size'=>'small'),
					'before' => '',
					'after' => '',
				),
			),
			'description' => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'), 	
			'before' => '',
			'after' => '',
			'separator' => ''
		),
		'toggle'	=> array('media-image-toggle')
	),
	// Video URL
	array(
		'name' 		=> 'video_url',
		'title' 		=> __('Video URL', 'themify'),
		'description' => __('Video embed URL such as YouTube or Vimeo video url (<a href="https://themify.me/docs/video-embeds">details</a>)', 'themify'),
		'type' 		=> 'textbox',
		'meta'		=> array(),
		'toggle'	=> array('media-video-toggle')
	),
	// Slider
	array(
		'name' 		=> 'slider',
		'title' 	=> __('Slider', 'themify'),
		'description' => '',			
		'type' 		=> 'gallery_shortcode',			
		'toggle'	=> 'media-slider-toggle'
	),
	// Gallery Shortcode
	array(
		'name' 		=> 'gallery_shortcode',
		'title' 	=> __('Gallery', 'themify'),
		'description' => '',			
		'type' 		=> 'gallery_shortcode',			
		'toggle'	=> 'media-gallery-toggle'
	),
	// Hide Post Title
	array(
		  'name' 		=> "hide_post_title",	
		  'title' 		=> __('Hide Portfolio Title', 'themify'), 	
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
		  'name' 		=> "unlink_post_title",	
		  'title' 		=> __('Unlink Portfolio Title', 'themify'), 	
		  'description' => __('Display the portfolio title without link', 'themify'), 				
		  'type' 		=> 'dropdown',			
		  'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		)
	),
	// Hide Post Meta
	array(
		'name' 		=> 'hide_meta_all',
		'title' 	=> __('Hide Portfolio Meta', 'themify'), 	
		'description' => '', 				
		'type' 		=> 'dropdown',			
		'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		)
	),
	// Hide Post Date
	array(
		'name' 		=> "hide_post_date",	
		'title' 	=> __('Hide Portfolio Date', 'themify'), 	
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
		'name' 		=> "hide_post_image",	
		'title' 	=> __('Hide Featured Image', 'themify'), 	
		'description' => '', 				
		'type' 		=> 'dropdown',			
		'meta'		=> array(
			array('value' => 'default', 'name' => '', 'selected' => true),
			array('value' => 'yes', 'name' => __('Yes', 'themify')),
			array('value' => 'no',	'name' => __('No', 'themify'))
		),
		'toggle'	=> array('media-image-toggle')
	),
	// Unlink Post Image
	array(
		'name' 		=> "unlink_post_image",	
		'title' 		=> __('Unlink Featured Image', 'themify'), 	
		'description' => __('Display the Featured Image without link', 'themify'), 				
		'type' 		=> 'dropdown',			
		'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
		),
		'toggle'	=> array('media-image-toggle')
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
	array(
		'name' 	=> '_multi_lightbox_link',	
		'title' => __('Lightbox Link', 'themify'), 	
		'description' => '', 				
		'type' 	=> 'multi',			
		'meta'	=> array(
			'fields' => array(
		  		// Lightbox link field
		  		array(
					'name' 	=> 'lightbox_link',
					'label' => '',
					'description' => __('Link Featured Image and Post Title to lightbox image, video or iframe URL <br/>(<a href="https://themify.me/docs/lightbox">learn more</a>)', 'themify'),
					'type' 	=> 'textbox',
					'meta'	=> array(),
					'before' => '',
					'after' => '',
				),
				array(
					'name' 		=> 'iframe_url',
					'label' 		=> __('iFrame URL', 'themify'),
					'description' => '',
					'type' 		=> 'checkbox',
					'before' => '',
					'after' => '',
				),
				array(
					'name' 		=> 'lightbox_icon',
					'label' 		=> __('Add zoom icon on lightbox link', 'themify'),
					'description' => '',
					'type' 		=> 'checkbox',
					'before' => '',
					'after' => '',
				)
			),
			'description' => '',
			'before' => '',
			'after' => '',
			'separator' => ''
		)
	),
);
	
	///////////////////////////////////////
	// Build Write Panels
	///////////////////////////////////////
	themify_build_write_panels(array(
		array(
			'name'		=> __('Post Options', 'themify'), // Name displayed in box
			'id' => 'post-options',
			'options'	=> $post_meta_box_options, 	// Field options
			'pages'	=> 'post'					// Pages to show write panel
		),
		array(
			'name'		=> __('Post Styles', 'themify'),
			'id' => 'post-styles',
			'options'	=> $post_styles,
			'pages'	=> 'post'
		),
		array(
			'name'		=> __('Page Options', 'themify'),	
			'id' => 'page-options',
			'options'	=> $page_meta_box_options, 		
			'pages'	=> 'page'
		),
		array(
			'name'		=> __('Query Posts', 'themify'),	
			'id' => 'query-posts',
			'options'	=> $query_post_meta_box, 		
			'pages'	=> 'page'
		),
		array(
			'name'		=> __('Query Portfolios', 'themify'),	
			'id' => 'query-portfolio',
			'options'	=> $query_portfolio_meta_box, 		
			'pages'	=> 'page'
		),
		array(
			'name'		=> __('Portfolio Options', 'themify'),			// Name displayed in box
			'id' => 'portfolio-options',
			'options'	=> $portfolio_meta_box, 	// Field options
			'pages'	=> 'portfolio'					// Pages to show write panel
		),
		array(
			'name'		=> __('Post Styles', 'themify'),			// Name displayed in box
			'options'	=> $post_styles, 	// Field options
			'pages'	=> 'portfolio'					// Pages to show write panel
		),
  	));
	
/* 	Custom Functions
/***************************************************************************/

	///////////////////////////////////////
	// Enable WordPress feature image
	///////////////////////////////////////
	add_theme_support( 'post-thumbnails' );
		
	/**
	 * Register Custom Menu Function
	 */
	function themify_register_custom_nav() {
		if (function_exists('register_nav_menus')) {
			register_nav_menus( array(
				'main-nav' => __( 'Main Navigation', 'themify' ),
				'footer-nav' => __( 'Footer Navigation', 'themify' ),
			) );
		}
	}
	
	/**
	 * Default Main Nav Function
	 */
	function themify_default_main_nav() {
		echo '<ul id="main-nav" class="main-nav clearfix">';
		wp_list_pages('title_li=');
		echo '</ul>';
	}

	/**
	 * Encompassed $themify->media_position to builder post loop
	 */
	add_action( 'themify_builder_override_loop_themify_vars', 'themify_add_media_position_builder_loop', 10, 2 );
	function themify_add_media_position_builder_loop($themify, $mod_name){
		if ($mod_name == 'post'){
			$themify->media_position = themify_check('setting-default_media_position')? themify_get('setting-default_media_position') : 'below';
		}
	}

	///////////////////////////////////////
	// Register Sidebars
	///////////////////////////////////////
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'name' => __('Sidebar', 'themify'),
			'id' => 'sidebar-main',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'name' => __('Social Widget', 'themify'),
			'id' => 'social-widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<strong class="widgettitle">',
			'after_title' => '</strong>',
		));
	}

	///////////////////////////////////////
	// Footer Sidebars
	///////////////////////////////////////
	themify_register_grouped_widgets();

	if( ! function_exists('themify_theme_comment') ) {
		/**
		 * Custom Theme Comment
		 * @param object $comment Current comment.
		 * @param array $args Parameters for comment reply link.
		 * @param int $depth Maximum comment nesting depth.
		 * @since 1.0.0
		 */
		function themify_theme_comment($comment, $args, $depth) {
		   $GLOBALS['comment'] = $comment;
		   ?>

		<li id="comment-<?php comment_ID() ?>">
			<p class="comment-author"> <?php echo get_avatar($comment,$size='48'); ?> <?php printf('<cite>%s</cite>', get_comment_author_link()) ?><br />
				<small class="comment-time"><?php comment_date( apply_filters( 'themify_comment_date', '' ) ); ?> @ <?php comment_time( apply_filters( 'themify_comment_time', '' ) ); ?>
				<?php edit_comment_link( __('Edit', 'themify'),' [',']') ?>
				</small> </p>
			<div class="commententry">
				<?php if ($comment->comment_approved == '0') : ?>
				<p><em>
					<?php _e('Your comment is awaiting moderation.', 'themify') ?>
					</em></p>
				<?php endif; ?>
				<?php comment_text() ?>
			</div>
			<p class="reply">
				<?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'depth' => $depth, 'reply_text' => __( 'Reply', 'themify' ), 'max_depth' => $args['max_depth']))) ?>
			</p>
		<?php
		}
	}

	////////// Migrate fields prefixed with underscore //////////
	function themify_theme_migrate_underscore_fields(){
		$migrated = get_option('themify_migrate_underscore_fields');
		if( ! isset( $migrated ) || ! $migrated ) {
			global $wpdb;
			foreach( array(
				'iframe_url',
				'lightbox_icon',
				'background_color_preset',
				'background_image_preset',
				'media_type',
				'media_position',
				'post_layout',
				'preset_font',
				'gallery_shortcode',
				'slider',
				'map',
				'map_zoom',
				'font_size',
				'font_size_unit',
				'font_family',
				'font_color',
				'background_color',
				'background_image',
				'background_repeat',
				'background_position_x',
				'background_position_y',
				'link_color',
				'portfolio_hide_meta_all',
				'hide_meta_all',
				'hide_meta_author',
				'hide_meta_comment',
				'hide_meta_tag',
				'hide_meta_category',
				'portfolio_order',
				'portfolio_orderby',
				'portfolio_query_category',
				'order',
				'orderby'
				) as $field ) {
				$wpdb->query( $wpdb->prepare(
					"UPDATE $wpdb->postmeta SET meta_key = %s WHERE meta_key = %s",
					$field,
					'_'.$field
				) );
			}
			update_option('themify_migrate_underscore_fields', true);
		}
	}
	add_action('after_setup_theme', 'themify_theme_migrate_underscore_fields');
