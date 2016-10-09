<?php


/*
To add custom PHP functions to the theme, create a new 'custom-functions.php' file in the theme folder. 
They will be added to the theme automatically.
*/
function themify_theme_default_layout_condition($condition) {
	return $condition || is_singular('portfolio') || is_tax('portfolio-category');
}
function themify_theme_default_layout($class) {
	
	if( is_singular('post') ){
		$class = (themify_get('layout') != 'default' && themify_check('layout')) ? themify_get('layout') : themify_get('setting-default_page_post_layout');
	}
	if( is_singular('portfolio') ){
		$class = themify_check('setting-default_portfolio_single_layout') ? 
					themify_get('setting-default_portfolio_single_layout'):
					'sidebar-none';
	}
	if( is_tax('portfolio-category') ){
		$class = themify_check('setting-default_portfolio_index_layout')? 
					themify_get('setting-default_portfolio_index_layout'):
					'sidebar-none';
	}
	
	return $class;
}
add_filter('themify_default_layout_condition', 'themify_theme_default_layout_condition');
add_filter('themify_default_layout', 'themify_theme_default_layout');

function themify_theme_default_post_layout_condition($condition) {
	return $condition || is_tax('portfolio-category');
};
function themify_theme_default_post_layout($class) {
	if( is_tax('portfolio-category') ){
		$class = themify_check('setting-default_portfolio_index_post_layout')? 
					themify_get('setting-default_portfolio_index_post_layout'):
					'grid3';
	}
	return $class;
};
add_filter('themify_default_post_layout_condition', 'themify_theme_default_post_layout_condition');
add_filter('themify_default_post_layout', 'themify_theme_default_post_layout');


/* 	Enqueue Stylesheets and Scripts
/***************************************************************************/
add_action( 'wp_enqueue_scripts', 'themify_theme_enqueue_scripts', 11 );
function themify_theme_enqueue_scripts(){
	global $wp_query;

	///////////////////
	//Enqueue styles
	///////////////////
	
	//Themify base styling
	wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), wp_get_theme()->display('Version'));
	
	//Themify Media Queries CSS
	wp_enqueue_style( 'themify-media-queries', THEME_URI . '/media-queries.css');
	

	//Google Web Fonts embedding
	wp_enqueue_style( 'google-fonts', themify_https_esc('http://fonts.googleapis.com/css'). '?family=Oxygen:300,400,700&subset=latin,latin-ext');
	
	///////////////////
	//Enqueue scripts
	///////////////////
	
	//audio-player
	wp_enqueue_script( 'audio-player', THEME_URI . '/js/audio-player.js', array('jquery'), false, false );
		
	//isotope, used to dinamically filter posts
	wp_enqueue_script( 'isotope', THEME_URI . '/js/jquery.isotope.min.js', array('jquery'), false, true );
	
	//creates infinite scroll
	wp_enqueue_script( 'infinitescroll', THEME_URI . '/js/jquery.infinitescroll.min.js', array('jquery'), false, true );
	
	//Themify internal scripts
	wp_enqueue_script( 'theme-script',	THEME_URI . '/js/themify.script.js', array('jquery'), false, true );

	// Get auto infinite scroll setting
	$autoinfinite = '';
	if ( ! themify_get( 'setting-autoinfinite' ) ) {
		$autoinfinite = 'auto';
	}

	//Inject variable values in javascript
	wp_localize_script( 'theme-script', 'themifyScript', apply_filters('themify_script_vars', array(
		'lightbox' => themify_lightbox_vars_init(),
		'lightboxContext' => apply_filters('themify_lightbox_context', '#pagewrap'),
		'loadingImg'   	=> THEME_URI . '/images/loading.gif',
		'maxPages'	   	=> $wp_query->max_num_pages,
		'autoInfinite' 	=> $autoinfinite,
		'audioPlayer'	=> THEME_URI . '/js/player.swf'
	)));
	
	//WordPress internal script to move the comment box to the right place when replying to a user
	if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' );
}


/**
 * Add viewport tag for responsive layouts
 * @package themify
 */
function themify_viewport_tag(){
	echo "\n".'<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">'."\n";
}
add_action( 'wp_head', 'themify_viewport_tag' );

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
	 * @return array
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
	
	// Post Meta Box Options
	$post_meta_box_options = array(
		// Layout
		array(
			  "name" 		=> "layout",	
			  "title" 		=> __('Sidebar Option', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "layout",
		'show_title' => true,
			  "meta"		=> array(
					array("value" => "default", "img" => "images/layout-icons/default.png", "selected" => true, 'title' => __('Default', 'themify')),
					array("value" => "sidebar1", "img" => "images/layout-icons/sidebar1.png", 'title' => __('Sidebar Right', 'themify')),
					array("value" => "sidebar1 sidebar-left", "img" => "images/layout-icons/sidebar1-left.png", 'title' => __('Sidebar Left', 'themify')),
					array("value" => "sidebar-none", "img" => "images/layout-icons/sidebar-none.png", 'title' => __('No Sidebar', 'themify'))
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
		// Post Layout Color
		array(
			'name'		=> 'post_color',
			'title'		=> __('Post Color', 'themify'),
			'description' => '',
			'type'		=> 'layout',
			'show_title' => true,
			'meta'		=> array(
				array('value' => 'default', 'img' => 'images/layout-icons/color-default.png', 'selected' => true, 'title' => __('Default', 'themify')),
				array('value' => 'purple', 'img' => 'images/layout-icons/color-purple.png', 'title' => __('Purple', 'themify')),
				array('value' => 'orange', 'img' => 'images/layout-icons/color-orange.png', 'title' => __('Orange', 'themify')),
				array('value' => 'pink', 'img' => 'images/layout-icons/color-pink.png', 'title' => __('Pink', 'themify')),
				array('value' => 'teal', 'img' => 'images/layout-icons/color-teal.png', 'title' => __('Teal', 'themify')),
				array('value' => 'blue', 'img' => 'images/layout-icons/color-blue.png', 'title' => __('Blue', 'themify')),
				array('value' => 'green', 'img' => 'images/layout-icons/color-green.png', 'title' => __('Green', 'themify')),
				array('value' => 'red', 'img' => 'images/layout-icons/color-red.png', 'title' => __('Red', 'themify')),
				array('value' => 'magenta', 'img' => 'images/layout-icons/color-magenta.png', 'title' => __('Magenta', 'themify')),
				array('value' => 'lime', 'img' => 'images/layout-icons/color-lime.png', 'title' => __('Lime', 'themify')),
				array('value' => 'brown', 'img' => 'images/layout-icons/color-brown.png', 'title' => __('Brown', 'themify')),
				array('value' => 'white', 'img' => 'images/layout-icons/color-white.png', 'title' => __('White', 'themify')),
				array('value' => 'black', 'img' => 'images/layout-icons/color-black.png', 'title' => __('Black', 'themify'))
			),
		),
		// Post Format
		array(
			  "name" 		=> "post_format",	
			  "title" 		=> __('Post Format', 'themify'), 	
			  "description" => "",
			  "type" 		=> "radio",
			  "enable_toggle" => true,
			  "meta"		=> array(
					array('value' => '0','name' => __('Standard','themify'), 'selected' => true),
					array('value' => 'image', 	'name' => __('Image','themify')),
					array('value' => 'video', 	'name' => __('Video','themify')),
					array('value' => 'gallery', 'name' => __('Gallery','themify')),
					array('value' => 'quote', 	'name' => __('Quote','themify')),
					array('value' => 'audio', 	'name' => __('Audio','themify')),
					array('value' => 'link', 	'name' => __('Link','themify')),
					array('value' => 'status', 	'name' => __('Status','themify')),
				),
				'default_selected' => ''
			),
		// Post Image
		array(
			'name' 		=> 'post_image',
			'title' 	=> __('Featured Image', 'themify'),
			'description' => '',
			'type' 		=> 'image',
			'meta'		=> array(),
			'toggle'		=> array( 'image-toggle', '0-toggle' )
		),
		// Featured Image Size
		array(
			'name'	=>	'feature_size',
			'title'	=>	__('Image Size', 'themify'),
			'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
			'type'		 =>	'featimgdropdown',
			"toggle"		=> array( 'image-toggle', '0-toggle' ),
			'display_callback' => 'themify_is_image_script_disabled'
			),
		// Image Width
		array(
			  "name" 		=> "image_width",	
			  "title" 		=> __('Image Width', 'themify'), 
			  "description" => "",
			  "type" 		=> "textbox",			
			  "meta"		=> array("size"=>"small"),
			  "toggle"		=> array( 'image-toggle', '0-toggle' )
			),
		// Image Height
		array(
			  "name" 		=> "image_height",	
			  "title" 		=> __('Image Height', 'themify'), 
			  "description" => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'), 				
			  "type" 		=> "textbox",			
			  "meta"		=> array("size"=>"small"),
			  "toggle"		=> array( 'image-toggle', '0-toggle' )
			),
		// Video URL
		array(
			  "name" 		=> "video_url",
			  "title" 		=> __('Video URL', 'themify'),
			  "description" => __('Video embed URL such as YouTube or Vimeo video url (<a href="https://themify.me/docs/video-embeds">details</a>).', 'themify'),
			  "type" 		=> "textbox",
			  "meta"		=> array(),
			  "toggle" => "video-toggle",
			),
		// Audio URL
		array(
			  "name" 		=> "audio_url",
			  "title" 		=> __('Audio URL', 'themify'),
			  "description" => __('For audio post (eg. mp3)', 'themify'),
			  "type" 		=> "textbox",
			  "toggle"	=> "audio-toggle",
			  "meta"		=> array()
			),
	   	// Quote Author
		array(
			  "name" 		=> "quote_author",
			  "title" 		=> __('Quote Author', 'themify'),
			  "description" => __('For quote post', 'themify'),
			  "type" 		=> "textbox",
			  "toggle"	=> "quote-toggle",
			  "meta"		=> array()
			),
	   	// Quote Author Link
		array(
			  "name" 		=> "quote_author_link",
			  "title" 		=> __('Quote Author Link', 'themify'),
			  "description" => __('For quote post', 'themify'),
			  "type" 		=> "textbox",
			  "toggle"	=> "quote-toggle",
			  "meta"		=> array()
			),
		// Link URL
		array(
			  "name" 		=> "link_url",	
			  "title" 		=> __('Link URL', 'themify'), 	
			  "description" => __('URL to link to post title', 'themify'), 				
			  "type" 		=> "textbox",			
			  "toggle"	=> "link-toggle",
			  "meta"		=> array()			
			),
		// Hide Post Title
		array(
			  "name" 		=> "hide_post_title",	
			  "title" 		=> __('Hide Post Title', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
		// Unlink Post Title
		array(
			  "name" 		=> "unlink_post_title",	
			  "title" 		=> __('Unlink Post Title', 'themify'), 	
			  "description" => __('Unlink post title (it will display the post title without link)', 'themify'), 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
		// Hide Post Meta
		array(
			  "name" 		=> "hide_post_meta",	
			  "title" 		=> __('Hide Post Meta', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)			
			),
		// Hide Post Date
		array(
			  "name" 		=> "hide_post_date",	
			  "title" 		=> __('Hide Post Date', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)			
			),
		// Hide Post Image
		array(
			"name" 		=> "hide_post_image",	
			"title" 	=> __('Hide Featured Image', 'themify'), 	
			"description" => "", 				
			"type" 		=> "dropdown",			
			"meta"		=> array(
				array("value" => "default", "name" => "", "selected" => true),
				array("value" => "yes", 'name' => __('Yes', 'themify')),
				array("value" => "no",	'name' => __('No', 'themify'))
			),
			"toggle" => array("image-toggle", "default-toggle")
		),
		// Unlink Post Image
		array(
			"name" 		=> "unlink_post_image",	
			"title" 		=> __('Unlink Featured Image', 'themify'), 	
			"description" => __('Display the Featured Image without link', 'themify'), 				
			"type" 		=> "dropdown",			
			"meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
			),
			"toggle" => array("image-toggle", "default-toggle")
		),
		// Video URL
		array(
			'name' 		=> 'video_url',
			'title' 		=> __('Video URL', 'themify'),
			'description' => __('Video embed URL such as YouTube or Vimeo video url (<a href="https://themify.me/docs/video-embeds">details</a>).', 'themify'),
			'type' 		=> 'textbox',
			'meta'		=> array()
		),
		// External Link
		array(
			  "name" 		=> "external_link",	
			  "title" 		=> __('External Link', 'themify'), 	
			  "description" => __('Link Featured Image and Post Title to external URL', 'themify'), 				
			  "type" 		=> "textbox",			
			  "meta"		=> array(),			
			  "toggle"		=> array("image-toggle", "default-toggle")
			),
		// Lightbox Link + Zoom icon
		themify_lightbox_link_field(array('toggle' => array('image-toggle', 'default-toggle')))
	);


	// Page Meta Box Options
	$page_meta_box_options = array(		
	  	// Page Layout
		array(
			"name" 		=> "page_layout",
			"title"		=> __('Sidebar Option', 'themify'),
			"description"	=> "",
			"type"		=> "layout",
			'show_title' => true,
			"meta"		=> array(
				array("value" => "default", "img" => "images/layout-icons/default.png", "selected" => true, 'title' => __('Default', 'themify')),
				array("value" => "sidebar1", "img" => "images/layout-icons/sidebar1.png", 'title' => __('Sidebar Right', 'themify')),
				array("value" => "sidebar1 sidebar-left", "img" => "images/layout-icons/sidebar1-left.png", 'title' => __('Sidebar Left', 'themify')),
				array("value" => "sidebar-none", "img" => "images/layout-icons/sidebar-none.png", 'title' => __('No Sidebar', 'themify'))
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
		// Hide page title
		array(
			"name" 		=> "hide_page_title",
			"title"		=> __('Hide Page Title', 'themify'),
			"description"	=> "",
			"type" 		=> "dropdown",			
			"meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
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

		// Query Post Meta Box Options
		$query_post_meta_box_options = array(
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
			"name" 		=> "query_category",
			"title"		=> __('Query Category', 'themify'),
			"description"	=> __('Select a category or enter multiple category IDs (eg. 2,5,6). Enter 0 to display all category.', 'themify'),
			"type"		=> "query_category",
			"meta"		=> array()
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
		// Section Categories
		array(
			  "name" 		=> "section_categories",	
			  "title" 		=> __('Section Categories', 'themify'), 	
			  "description" => __('Display multiple query categories separately', 'themify'), 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)			
			),
		// Allow Sorting
		array(
			  "name" 		=> "allow_sorting",
			  "title"		=> __('Allow Sorting', 'themify'),
			  "description"	=> __('This will add a sorting navigation', 'themify'),
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
		// Post Layout
		array(
			  "name" 		=> "layout",
			  "title"		=> __('Query Post Layout', 'themify'),
			  "description"	=> "",
			  "type"		=> "layout",
			'show_title' => true,
			  "meta"		=> array(
					array('value' => 'list-post', 'img' => 'images/layout-icons/list-post.png', 'selected' => true, 'title' => __('List Post', 'themify')),
					array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify')),
					array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify'))
				)
			),
		// Posts Per Page
		array(
			  "name" 		=> "posts_per_page",
			  "title"		=> __('Posts per page', 'themify'),
			  "description"	=> "",
			  "type"		=> "textbox",
			  "meta"		=> array("size" => "small")
			),
		
		// Display Content
		array(
			  "name" 		=> "display_content",
			  "title"		=> __('Display Content', 'themify'),
			  "description"	=> "",
			  "type"		=> "dropdown",
			  "meta"		=> array(
					array('name' => __('Full Content', 'themify'),"value"=>"content","selected"=>true),
					array('name' => __('Excerpt', 'themify'),"value"=>"excerpt"),
					array('name' => __('None', 'themify'),"value"=>"none")
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
			  "name" 		=> "image_width",	
			  "title" 		=> __('Image Width', 'themify'), 
			  "description" => "", 				
			  "type" 		=> "textbox",			
			  "meta"		=> array("size"=>"small")			
			),
		// Image Height
		array(
			  "name" 		=> "image_height",	
			  "title" 		=> __('Image Height', 'themify'), 
			  "description" => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'), 				
			  "type" 		=> "textbox",			
			  "meta"		=> array("size"=>"small")			
			),
		// Hide Title
		array(
			  "name" 		=> "hide_title",
			  "title"		=> __('Hide Post Title', 'themify'),
			  "description"	=> "",
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
		// Unlink Post Title
		array(
			  "name" 		=> "unlink_title",	
			  "title" 		=> __('Unlink Post Title', 'themify'), 	
			  "description" => __('Unlink post title (it will display the post title without link)', 'themify'), 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
		// Hide Post Date
		array(
			  "name" 		=> "hide_date",
			  "title"		=> __('Hide Post Date', 'themify'),
			  "description"	=> "",
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
		// Hide Post Meta
		array(
			  "name" 		=> "hide_meta",
			  "title"		=> __('Hide Post Meta', 'themify'),
			  "description"	=> "",
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
		// Hide Post Image
		array(
			  "name" 		=> "hide_image",	
			  "title" 		=> __('Hide Featured Image', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)			
			),
		// Unlink Post Image
		array(
			  "name" 		=> "unlink_image",	
			  "title" 		=> __('Unlink Featured Image', 'themify'), 	
			  "description" => __('Display the Featured Image without link', 'themify'), 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
		// Page Navigation Visibility
		array(
			  "name" 		=> "hide_navigation",
			  "title"		=> __('Hide Page Navigation', 'themify'),
			  "description"	=> "",
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			)
		
		);
		
		// Feature Image
		$post_image = array(
			'name' 		=> 'post_image',	
			'title' 	=> __('Featured Image', 'themify'),
			'description' => '', 				
			'type' 		=> 'image',			
			'meta'		=> array()
		);
		// Featured Image Size
		$featured_image_size = array(
			'name'	=>	'feature_size',
			'title'	=>	__('Image Size', 'themify'),
			'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
			'type'		 =>	'featimgdropdown',
			'display_callback' => 'themify_is_image_script_disabled'
		);
		// Image Width
		$image_width = array(
			'name' 		=> 'image_width',
			'title' 	=> __('Image Width', 'themify'),
			'description' => '',			
			'type' 		=> 'textbox',
			'meta'		=> array('size'=>'small')
		);
		// Image Height
		$image_height = array(
			'name' 		=> 'image_height',
			'title' 	=> __('Image Height', 'themify'),
			'description' => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'),
			'type' 		=> 'textbox',
			'meta'		=> array('size'=>'small')
		);
		// External Link
		$external_link = array(
			'name' 		=> 'external_link',
			'title' 	=> __('External Link', 'themify'),
			'description' => __('Link Featured Image and Post Title to external URL', 'themify'),
			'type' 		=> 'textbox',
			'meta'		=> array()
		);

		/** Portfolio Meta Box Options */
		$portfolio_meta_box = array(
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
			// Portfolio Color
			array(
				'name'		=> '_portfolio_color',
				'title'		=> __('Portfolio Color', 'themify'),
				'description' => '',
				'type'		=> 'layout',
				'show_title' => true,
				'meta'		=> array(
					array('value' => 'default', 'img' => 'images/layout-icons/color-default.png', 'selected' => true, 'title' => __('Default', 'themify')),
					array('value' => 'purple', 'img' => 'images/layout-icons/color-purple.png', 'title' => __('Purple', 'themify')),
					array('value' => 'orange', 'img' => 'images/layout-icons/color-orange.png', 'title' => __('Orange', 'themify')),
					array('value' => 'pink', 'img' => 'images/layout-icons/color-pink.png', 'title' => __('Pink', 'themify')),
					array('value' => 'teal', 'img' => 'images/layout-icons/color-teal.png', 'title' => __('Teal', 'themify')),
					array('value' => 'blue', 'img' => 'images/layout-icons/color-blue.png', 'title' => __('Blue', 'themify')),
					array('value' => 'green', 'img' => 'images/layout-icons/color-green.png', 'title' => __('Green', 'themify')),
					array('value' => 'red', 'img' => 'images/layout-icons/color-red.png', 'title' => __('Red', 'themify')),
					array('value' => 'magenta', 'img' => 'images/layout-icons/color-magenta.png', 'title' => __('Magenta', 'themify')),
					array('value' => 'lime', 'img' => 'images/layout-icons/color-lime.png', 'title' => __('Lime', 'themify')),
					array('value' => 'brown', 'img' => 'images/layout-icons/color-brown.png', 'title' => __('Brown', 'themify')),
					array('value' => 'white', 'img' => 'images/layout-icons/color-white.png', 'title' => __('White', 'themify')),
					array('value' => 'black', 'img' => 'images/layout-icons/color-black.png', 'title' => __('Black', 'themify'))
				),
			),
			// Feature Image
			$post_image,
			// Gallery Shortcode
			array(
				'name' 		=> '_gallery_shortcode',	
				'title' 	=> __('Gallery', 'themify'),
				'description' => '',			
				'type' 		=> 'gallery_shortcode'
			),
			// Featured Image Size
			$featured_image_size,
			// Image Width
			$image_width,
			// Image Height
			$image_height,
			// Hide Title
			array(
				"name" 		=> "hide_post_title",
				"title"		=> __('Hide Post Title', 'themify'),
				"description"	=> "",
				"type" 		=> "dropdown",			
				"meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
			// Unlink Post Title
			array(
				"name" 		=> "unlink_post_title",
				"title" 		=> __('Unlink Post Title', 'themify'), 	
				"description" => __('Unlink post title (it will display the post title without link)', 'themify'), 				
				"type" 		=> "dropdown",			
				"meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
			// Hide Post Date
			array(
				"name" 		=> "hide_post_date",
				"title"		=> __('Hide Post Date', 'themify'),
				"description"	=> "",
				"type" 		=> "dropdown",			
				"meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
			// Hide Post Meta
			array(
				"name" 		=> "hide_post_meta",
				"title"		=> __('Hide Post Meta', 'themify'),
				"description"	=> "",
				"type" 		=> "dropdown",			
				"meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
			// Hide Post Image
			array(
				"name" 		=> "hide_post_image",
				"title" 		=> __('Hide Featured Image', 'themify'), 	
				"description" => "", 				
				"type" 		=> "dropdown",			
				"meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)			
			),
			// Unlink Post Image
			array(
				"name" 		=> "unlink_post_image",
				"title" 		=> __('Unlink Featured Image', 'themify'), 	
				"description" => __('Display the Featured Image without link', 'themify'), 				
				"type" 		=> "dropdown",			
				"meta"		=> array(
					array("value" => "default", "name" => "", "selected" => true),
					array("value" => "yes", 'name' => __('Yes', 'themify')),
					array("value" => "no",	'name' => __('No', 'themify'))
				)
			),
			// External Link
			$external_link,
			// Lightbox Link + Zoom icon
			themify_lightbox_link_field(),
			// Shortcode ID
			array(
				'name' 		=> '_post_id_info',	
				'title' 	=> __('Shortcode ID', 'themify'),
				'description' => __('To show this use [portfolio id="%s"]', 'themify'),			
				'type' 		=> 'post_id_info'
			)
		);
		
		/** Tile Meta Box Options */
		$tile_meta_box = array(
			// Tile Size
			array(
				'name'		=> '_tile_size',
				'title'		=> __('Tile Size', 'themify'),
				'description' => '',
				'type'		=> 'layout',
				'show_title' => true,
				'meta'		=> array(
					array('value' => 'large', 'img' => 'images/layout-icons/tile-large.png', 'selected' => true, 'title' => __('Large', 'themify')),
					array('value' => 'small', 'img' => 'images/layout-icons/tile-small.png', 'title' => __('Small', 'themify')),
					array('value' => 'medium', 'img' => 'images/layout-icons/tile-medium.png', 'title' => __('Medium', 'themify'))
				)
			),
			// Tile Color
			array(
				'name'		=> '_tile_color',
				'title'		=> __('Tile Color', 'themify'),
				'description' => '',
				'type'		=> 'layout',
				'show_title' => true,
				'meta'		=> array(
					array('value' => 'default', 'img' => 'images/layout-icons/color-default.png', 'selected' => true, 'title' => __('Default', 'themify')),
					array('value' => 'purple', 'img' => 'images/layout-icons/color-purple.png', 'title' => __('Purple', 'themify')),
					array('value' => 'orange', 'img' => 'images/layout-icons/color-orange.png', 'title' => __('Orange', 'themify')),
					array('value' => 'pink', 'img' => 'images/layout-icons/color-pink.png', 'title' => __('Pink', 'themify')),
					array('value' => 'teal', 'img' => 'images/layout-icons/color-teal.png', 'title' => __('Teal', 'themify')),
					array('value' => 'blue', 'img' => 'images/layout-icons/color-blue.png', 'title' => __('Blue', 'themify')),
					array('value' => 'green', 'img' => 'images/layout-icons/color-green.png', 'title' => __('Green', 'themify')),
					array('value' => 'red', 'img' => 'images/layout-icons/color-red.png', 'title' => __('Red', 'themify')),
					array('value' => 'magenta', 'img' => 'images/layout-icons/color-magenta.png', 'title' => __('Magenta', 'themify')),
					array('value' => 'lime', 'img' => 'images/layout-icons/color-lime.png', 'title' => __('Lime', 'themify')),
					array('value' => 'brown', 'img' => 'images/layout-icons/color-brown.png', 'title' => __('Brown', 'themify')),
					array('value' => 'white', 'img' => 'images/layout-icons/color-white.png', 'title' => __('White', 'themify')),
					array('value' => 'black', 'img' => 'images/layout-icons/color-black.png', 'title' => __('Black', 'themify'))
				),
			),
			// Tile Type
			array(
				'name'		=> '_tile_type',
				'title'		=> __('Tile Type', 'themify'),
				'description' => '',
				'type'		=> 'layout',
				'show_title' => true,
				'meta'		=> array(
					array('value' => 'text', 'img' => 'images/layout-icons/tile-type-text.png', 'selected' => true, 'title' => __('Text', 'themify')),
					array('value' => 'button', 'img' => 'images/layout-icons/tile-type-button.png', 'title' => __('Button', 'themify')),
					array('value' => 'gallery', 'img' => 'images/layout-icons/tile-type-gallery.png', 'title' => __('Gallery', 'themify')),
					array('value' => 'image', 'img' => 'images/layout-icons/tile-type-image.png', 'title' => __('Image', 'themify')),
					array('value' => 'map', 'img' => 'images/layout-icons/tile-type-map.png', 'title' => __('Map', 'themify')),
				),
				'enable_toggle' => true,
			),
			// Gallery Shortcode
			array(
				'name' 		=> '_gallery_shortcode',	
				'title' 	=> __('Gallery', 'themify'),
				'description' => '',			
				'type' 		=> 'gallery_shortcode',			
				'toggle'	=> 'gallery-toggle'
			),
			// Feature Image
			array(
				'name' 		=> 'post_image',	
				'title' 	=> __('Featured Image', 'themify'),
				'description' => '', 				
				'type' 		=> 'image',			
				'toggle'	=> array('image-toggle', 'button-toggle')
			),
			// Featured Image Size
			array(
				'name'	=>	'feature_size',
				'title'	=>	__('Image Size', 'themify'),
				'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
				'type'		 =>	'featimgdropdown',
				'toggle'	=> array('image-toggle', 'button-toggle'),
				'display_callback' => 'themify_is_image_script_disabled'
			),
			// Image Width
			array(
				'name' 		=> 'image_width',
				'title' 	=> __('Image Width', 'themify'),
				'description' => '',			
				'type' 		=> 'textbox',
				'meta'		=> array('size'=>'small'),
				'toggle'	=> array('image-toggle', 'button-toggle', 'gallery-toggle')
			),
			// Image Height
			 array(
				'name' 		=> 'image_height',
				'title' 	=> __('Image Height', 'themify'),
				'description' => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'),
				'type' 		=> 'textbox',
				'meta'		=> array('size'=>'small'),
				'toggle'	=> array('image-toggle', 'button-toggle', 'gallery-toggle')
			),
			// External Link
			array(
				'name' 		=> 'external_link',
				'title' 	=> __('External Link', 'themify'),
				'description' => __('Link Featured Image and Post Title to external URL', 'themify'),
				'type' 		=> 'textbox',
				'meta'		=> array(),
				'toggle'	=> array('image-toggle', 'button-toggle', 'gallery-toggle')
			),
			array(
				'name'      => 'new_tab',
				'label'     => __('Open link in new tab', 'themify'),
				'description' => __('', 'themify'),
				'type'      => 'checkbox',
				'toggle'    => array('image-toggle', 'button-toggle', 'gallery-toggle')
			),
			// Lightbox Link + Zoom icon
			themify_lightbox_link_field(array('toggle' => array('image-toggle', 'button-toggle'))),
			// Map Textbox
			array(
				'name' 		=> '_map',	
				'title' 	=> __('Map Address', 'themify'),
				'description' => '',			
				'type' 		=> 'textarea',
				'toggle'	=> 'map-toggle'
			),
			// Map Zoom Level
			array(
				'name' 		=> '_map_zoom',
				'title' 	=> __('Map Zoom Level', 'themify'),
				'description'	=> '',
				'type' 		=> 'dropdown',			
				'meta'		=> array(
						array('value' => '8', 'name' => __('8 - default', 'themify'), 'selected' => true),
						array('value' => '1', 'name' => '1'),
						array('value' => '2', 'name' => '2'),
						array('value' => '3', 'name' => '3'),
						array('value' => '4', 'name' => '4'),
						array('value' => '5', 'name' => '5'),
						array('value' => '6', 'name' => '6'),
						array('value' => '7', 'name' => '7'),
						array('value' => '8', 'name' => '8'),
						array('value' => '9', 'name' => '9'),
						array('value' => '10', 'name' => '10'),
						array('value' => '11', 'name' => '11'),
						array('value' => '12', 'name' => '12'),
						array('value' => '13', 'name' => '13'),
						array('value' => '14', 'name' => '14'),
						array('value' => '15', 'name' => '15'),
						array('value' => '16', 'name' => '16')
				),
				'toggle'	=> 'map-toggle'
			),
			// Gallery Tile Slider Controls
			array(
				'type' => 'multi',
				'name' => '_tile_gallery_controls',
				'title' => __('Slider Controls', 'themify'),
				'meta' => array(
					'fields' => array(
						// Slider Speed
						array(
							'name' => 'tile_slider_autoplay',
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
							'before' => __('Auto Play', 'themify') . '<br/> ',
							'after' => ''
						),
						// Slider Effect
						array(
							'name' => 'tile_slider_effect',
							'label' => '',
							'description' => '',
							'type' 		=> 'dropdown',
							'meta' => array(
								array('value' => 'slide',  'name' => __('Slide', 'themify'), 'selected' => true),
								array('value' => 'fade',  'name' => __('Fade', 'themify')),
							),
							'before' => '<p>' . __('Effect', 'themify') . '<br/> ',
							'after' => '</p>'
						),
						// Slider Transition Speed
						array(
							'name' => 'tile_slider_transition',
							'label' => '',
							'description' => '',
							'type' 		=> 'dropdown',
							'meta' => array(
								array('value' => 500,  'name' => __('Fast', 'themify')),
								array('value' => 1000,  'name' => __('Normal', 'themify'), 'selected' => true),
								array('value' => 1500,  'name' => __('Slow', 'themify')),
							),
							'before' => '<p>' . __('Transition', 'themify') . '<br/> ',
							'after' => '</p>'
						),
					),
					'description' => '',
					'before' => '',
					'after' => '',
					'separator' => ''
				),
				'toggle'	=> 'gallery-toggle'
			),
			// Shortcode ID
			array(
				'name' 		=> '_post_id_info',	
				'title' 	=> __('Shortcode ID', 'themify'),
				'description' => __('To show this use [tile id="%s"]', 'themify'),			
				'type' 		=> 'post_id_info',
				'toggle'		=> array('default-toggle', 'button-toggle', 'gallery-toggle', 'image-toggle', 'map-toggle', 'text-toggle')
			)
		);

/**
 * Post Styles Meta Box Options
 * @var array
 */
$post_styles = array(
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
);
	
	///////////////////////////////////////
	// Build Write Panels
	///////////////////////////////////////
	themify_build_write_panels( apply_filters('themify_theme_meta_boxes',
		array(
			array(
				'name'		=> __('Post Options', 'themify'), // Name displayed in box
				'id' => 'post-options',
				'options'	=> $post_meta_box_options, 	// Field options
				'pages'	=> 'post'					// Pages to show write panel
			),
			array(
				'name'		=> __('Page Options', 'themify'),	
				'id' => 'page-options',
				'options'	=> $page_meta_box_options, 		
				'pages'	=> 'page'
			),
			array(
				"name"		=> __('Query Posts', 'themify'),	
				'id' => 'query-posts',
				"options"	=> $query_post_meta_box_options, 		
				"pages"	=> "page"
			),
			array(
				'name'		=> __('Portfolio Options', 'themify'),
				'id' => 'portfolio-options',
				'options'	=> $portfolio_meta_box,
				'pages'	=> 'portfolio'
			),
			array(
				'name'		=> __('Tile Options', 'themify'),
				'id' => 'tile-options',	
				'options'	=> $tile_meta_box, 		
				'pages'	=> 'tile'
			),
			array(
				'name'		=> __('Post Styles', 'themify'),
				'id' => 'post-styles',
				'options'	=> $post_styles,
				'pages'	=> 'post'
			),
			array(
				'name'		=> __('Portfolio Styles', 'themify'),
				'id' => 'portfolio-styles',
				'options'	=> $post_styles,
				'pages'	=> 'portfolio'
			),
			array(
				'name'		=> __('Tile Styles', 'themify'),
				'id' => 'tile-styles',
				'options'	=> $post_styles,
				'pages'	=> 'tile'
			)
		)
  	));
	
/* 	Custom Functions
/***************************************************************************/

	///////////////////////////////////////
	// Post Formats
	///////////////////////////////////////
	add_theme_support( 'post-formats', array( 'video', 'image', 'gallery', 'quote', 'audio', 'link', 'status' ) );
	
	function themify_set_post_format($post_id) {
		if ( !wp_is_post_revision( $post_id ) ) {
			set_post_format( $post_id , get_post_meta($post_id, 'post_format', true));
		}
	}
	add_action( 'save_post', 'themify_set_post_format', 20);
	
	if (is_admin()){
		function themify_metro_remove_formatdiv() {
			remove_meta_box('formatdiv', 'post', 'normal');
		}
		add_action( 'admin_menu', 'themify_metro_remove_formatdiv' );
	}

	///////////////////////////////////////
	// Enable WordPress feature image
	///////////////////////////////////////
	add_theme_support( 'post-thumbnails' );
	
	if(!function_exists('themify_post_format_custom_fields')){
		/**
		 * Filter RSS Feed to include Custom Fields
		 * @param string $content Post content to filter.
		 * @return string $content Filtered content with custom fields included.
		 */
		function themify_post_format_custom_fields( $content ) {
			global $post, $id, $themify_check;
			if(!is_feed() || $themify_check == true){
				return $content;
			}
			$post_format = themify_get('post_format');
			
			if(has_post_format( 'image' ) && themify_check('post_image')) { 
				$content = "<img src='".themify_get('post_image')."'><br>".$content;
			} elseif(has_post_format( 'quote' ) && themify_check('quote_author')) {
				$content = '"'.$content.'" '.themify_get('quote_author')." - <a href='".themify_get('quote_author_link')."'>".themify_get('quote_author_link')."</a>";
			} elseif(has_post_format( 'link' ) && themify_check('external_link')) {
				$content .= "<a href='".themify_get('external_link')."'>".themify_get('external_link')."</a>";
			} elseif(has_post_format( 'audio' ) && themify_check('audio_url')) {
				$content = "<p><img src='".themify_get('post_image')."'></p><br>".$content;
				$content .= themify_get('audio_url');
			} elseif(has_post_format( 'video' ) && themify_check('video_url')) {
				$themify_check = true;
				$content = apply_filters('the_content', themify_get('video_url')) . $content;
			}
			$themify_check = false;
			return $content;
		}
		add_filter('the_content', 'themify_post_format_custom_fields');
	}
	
	///////////////////////////////////////
	// Register Custom Menu Function
	///////////////////////////////////////
	function themify_register_custom_nav() {
		if (function_exists('register_nav_menus')) {
			register_nav_menus( array(
				'main-nav' => __( 'Main Navigation', 'themify' ),
				'footer-nav' => __( 'Footer Navigation', 'themify' ),
			) );
		}
	}
	
	// Register Custom Menu Function - Action
	add_action('init', 'themify_register_custom_nav');
	
	///////////////////////////////////////
	// Default Main Nav Function
	///////////////////////////////////////
	function themify_default_main_nav() {
		echo '<ul id="main-nav" class="main-nav clearfix">';
		wp_list_pages('title_li=');
		echo '</ul>';
	}

	///////////////////////////////////////
	// Register Sidebars
	///////////////////////////////////////
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'name' => __('Sidebar Wide', 'themify'),
			'id' => 'sidebar-main',
			'before_widget' => '<div class="widgetwrap"><div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div></div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'name' => __('Sidebar Wide 2A', 'themify'),
			'id' => 'sidebar-main-2a',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'name' => __('Sidebar Wide 2B', 'themify'),
			'id' => 'sidebar-main-2b',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'name' => __('Sidebar Wide 3', 'themify'),
			'id' => 'sidebar-main-3',
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
			<p class="comment-author">
				<?php echo get_avatar($comment,$size='68'); ?>
				<?php printf('<cite>%s</cite>', get_comment_author_link()) ?><br />
				<small class="comment-time"><strong><?php comment_date( apply_filters( 'themify_comment_date', '' ) ); ?></strong> @ <?php comment_time( apply_filters( 'themify_comment_time', '' ) ); ?><?php edit_comment_link( __('Edit', 'themify'),' [',']') ?></small>
			</p>
			<div class="commententry">
				<?php if ($comment->comment_approved == '0') : ?>
				<p><em><?php _e('Your comment is awaiting moderation.', 'themify') ?></em></p>
				<?php endif; ?>
			
				<?php comment_text() ?>
			</div>
			<p class="reply">
			<?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'depth' => $depth, 'reply_text' => __( 'Reply', 'themify' ), 'max_depth' => $args['max_depth']))) ?>
			</p>
		<?php
	}
}
	
	///////////////////////////////////////
	// WooCommerce Theme Support
	///////////////////////////////////////
	add_theme_support( 'woocommerce' );	
