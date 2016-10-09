<?php


/*
To add custom PHP functions to the theme, create a new 'custom-functions.php' file in the theme folder. 
They will be added to the theme automatically.
*/

/* 	Enqueue Stylesheets and Scripts
/***************************************************************************/
add_action( 'wp_enqueue_scripts', 'themify_theme_enqueue_scripts', 11 );
function themify_theme_enqueue_scripts(){
	
	///////////////////
	//Enqueue styles
	///////////////////
	
	//Themify base styling
	wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), wp_get_theme()->display('Version'));
	
	//Themify Media Queries CSS
	wp_enqueue_style( 'themify-media-queries', THEME_URI . '/media-queries.css');	


	///////////////////
	//Enqueue scripts
	///////////////////
	
	//audio-player
	wp_enqueue_script( 'audio-player', THEME_URI . '/js/audio-player.js', array('jquery'), false, false );
		
	//isotope, used to dinamically filter posts
	wp_enqueue_script( 'isotope', THEME_URI . '/js/jquery.isotope.min.js', array('jquery'), false, true );
	
	//Themify internal scripts
	wp_enqueue_script( 'theme-script',	THEME_URI . '/js/themify.script.js', array('jquery'), false, true );
	
	//Inject variable values in gallery script
	wp_localize_script( 'theme-script', 'themifyScript', apply_filters('themify_script_vars', array(
		'lightbox' => themify_lightbox_vars_init(),
		'lightboxContext' => apply_filters('themify_lightbox_context', '#pagewrap'),
		'audioPlayer'	=> THEME_URI . '/js/player.swf'
	)));
	
	//WordPress internal script to move the comment box to the right place when replying to a user
	if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' );
	
}

/**
 * Add spacing on mobile
 * @package themify
 */
function themify_viewport_tag(){
	echo "\n".'<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">'."\n";
}
add_action( 'wp_head', 'themify_viewport_tag' );


/* Custom Write Panels
/***************************************************************************/
	
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

									 array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify')),
									 array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
									 array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar ', 'themify'))
									 )			
			),
		// Post Layout Color
		array(
			  "name"		=> "post_color",
			  "title"		=> __('Post Color', 'themify'),
			  "description" => "",
			  "type"		=> "layout",
			'show_title' => true,
			  "meta"		=> array(
									array('value' => 'default', 'img' => 'images/layout-icons/color-default.png', 'selected' => true, 'title' => __('Default', 'themify')),
									array('value' => 'yellow', 'img' => 'images/layout-icons/color-yellow.png', 'title' => __('Yellow', 'themify')),
									array('value' => 'orange', 'img' => 'images/layout-icons/color-orange.png', 'title' => __('Orange', 'themify')),
									array('value' => 'pink', 'img' => 'images/layout-icons/color-pink.png', 'title' => __('Pink', 'themify')),
									array('value' => 'lavender', 'img' => 'images/layout-icons/color-lavender.png', 'title' => __('Lavender', 'themify')),
									array("value" => 'lime', "img" => 'images/layout-icons/color-lime.png', 'title' => __('Lime', 'themify')),
									array("value" => 'teal', "img" => 'images/layout-icons/color-teal.png', 'title' => __('Teal', 'themify')),
									array("value" => 'light-blue', "img" => 'images/layout-icons/color-light-blue.png', 'title' => __('Light Blue', 'themify')),
									array('value' => 'blue', 'img' => 'images/layout-icons/color-blue.png', 'title' => __('Blue', 'themify')),
									array('value' => 'green', 'img' => 'images/layout-icons/color-green.png', 'title' => __('Green', 'themify')),
									array("value" => 'rose', "img" => 'images/layout-icons/color-rose.png', 'title' => __('Rose', 'themify')),
									array("value" => 'brown', "img" => 'images/layout-icons/color-brown.png', 'title' => __('Brown', 'themify')),
									array("value" => 'violet', "img" => 'images/layout-icons/color-violet.png', 'title' => __('Violet', 'themify')),
			  						array('value' => 'black', 'img' => 'images/layout-icons/color-black.png', 'title' => __('Black', 'themify')),
									array('value' => 'gray', 'img' => 'images/layout-icons/color-gray.png', 'title' => __('Gray', 'themify')),
									array('value' => 'white', 'img' => 'images/layout-icons/color-white.png', 'title' => __('White', 'themify')),
			  					)
			  ),
	   	// Post Image
		array(
			  "name" 		=> "post_image",
			  "title" 		=> __('Featured Image', 'themify'),
			  "description" => "",
			  "type" 		=> "image",
			  "meta"		=> array()
			),
		// Featured Image Size
		array(
			'name'	=>	'feature_size',
			'title'	=>	__('Image Size', 'themify'),
			'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="admin.php?page=regenerate-thumbnails">Regenerated</a>', 'themify'),
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
	   	// Video URL
		array(
			  "name" 		=> "video_url",
			  "title" 		=> __('Video URL', 'themify'),
			  "description" => __('For video post (eg. youtube, vimeo embed url, etc.)', 'themify'),
			  "type" 		=> "textbox",
			  "meta"		=> array()
			),
	   	// Audio URL
		array(
			  "name" 		=> "audio_url",
			  "title" 		=> __('Audio URL', 'themify'),
			  "description" => __('For audio post (eg. mp3)', 'themify'),
			  "type" 		=> "textbox",
			  "meta"		=> array()
			),
	   	// Quote Author
		array(
			  "name" 		=> "quote_author",
			  "title" 		=> __('Quote Author', 'themify'),
			  "description" => __('For quote post', 'themify'),
			  "type" 		=> "textbox",
			  "meta"		=> array()
			),
	   	// Quote Author Link
		array(
			  "name" 		=> "quote_author_link",
			  "title" 		=> __('Quote Author Link', 'themify'),
			  "description" => __('For quote post', 'themify'),
			  "type" 		=> "textbox",
			  "meta"		=> array()
			),
		// Link URL
		array(
			  "name" 		=> "link_url",	
			  "title" 		=> __('Link URL', 'themify'), 	
			  "description" => __('For link post', 'themify'), 				
			  "type" 		=> "textbox",			
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
		array(
			  "name" 		=> "external_link",	
			  "title" 		=> __('External Link', 'themify'), 	
			  "description" => __('Link Featured Image and Post Title to external URL', 'themify'), 				
			  "type" 		=> "textbox",			
			  "meta"		=> array()			
			),
	// Lightbox Link + Zoom icon
	themify_lightbox_link_field()

	);

	// Layout
	// Check WordPress version for Custom Fields fallback
	if(get_bloginfo('version') < 3.1){
		array_unshift($post_meta_box_options, array(
			  "name" 		=> "post_format",	
			  "title" 		=> __('Post Format', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "layout",			
		'show_title' => true,
			  "meta"		=> array(
					 array("value" => "", "img" => "images/layout-icons/format-default.png", "selected" => true),
					 array("value" => "text", "img" => "images/layout-icons/format-text.png"),
					 array("value" => "video", "img" => "images/layout-icons/format-video.png"),
					 array("value" => "image", "img" => "images/layout-icons/format-image.png"),
					 array("value" => "gallery", "img" => "images/layout-icons/format-gallery.png"),
					 array("value" => "quote", "img" => "images/layout-icons/format-quote.png"),
					 array("value" => "audio", "img" => "images/layout-icons/format-audio.png"),
					 array("value" => "link", "img" => "images/layout-icons/format-link.png"),
					 array("value" => "status", "img" => "images/layout-icons/format-status.png")
				 )
			)
		);
	}
									
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
			  'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="admin.php?page=regenerate-thumbnails">Regenerated</a>', 'themify'),
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
	
	
	///////////////////////////////////////
	// Build Write Panels
	///////////////////////////////////////
	themify_build_write_panels(array(
		array(
			 "name"		=> __('Post Options', 'themify'), // Name displayed in box
			'id' => 'post-options',
			 "options"	=> $post_meta_box_options, 	// Field options
			 "pages"	=> "post"					// Pages to show write panel
			 ),
		array(
			 "name"		=> __('Page Options', 'themify'),	
			'id' => 'page-options',
			 "options"	=> $page_meta_box_options, 		
			 "pages"	=> "page"
			 ),
		array(
			"name"		=> __('Query Posts', 'themify'),	
			'id' => 'query-posts',
			"options"	=> $query_post_meta_box_options, 		
			"pages"	=> "page"
			)
		)
	);

	
/* 	Custom Functions
/***************************************************************************/

	///////////////////////////////////////
	// Enable WordPress feature image
	///////////////////////////////////////
	add_theme_support( 'post-thumbnails' );

	///////////////////////////////////////
	// Add offset & limit number of images to show in gallery shortcode [gallery numberposts=3 offset=1]
	///////////////////////////////////////
	add_filter( 'post_gallery', 'themify_post_gallery', 10, 3 );
	function themify_post_gallery( $output, $attr) {
		global $post, $wp_locale;
	
		static $instance = 0;
		$instance++;
	
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( !$attr['orderby'] )
				unset( $attr['orderby'] );
		}
	
	extract(shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'numberposts' => -1,
			'offset'     => 0,
		), $attr));
	
		$id = intval($id);	
		if ( 'RAND' == $order )
			$orderby = 'none';
	
		if ( !empty($include) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby, 'numberposts' => $numberposts, 'offset' => $offset) );
	
			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( !empty($exclude) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby, 'numberposts' => $numberposts, 'offset' => $offset) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby, 'numberposts' => $numberposts, 'offset' => $offset) );
		}
	
		if ( empty($attachments) )
			return '';
	
		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			return $output;
		}
	
		$itemtag = tag_escape($itemtag);
		$captiontag = tag_escape($captiontag);
		$columns = intval($columns);
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		$float = is_rtl() ? 'right' : 'left';
	
		$selector = "gallery-{$instance}";
	
		$output = apply_filters('gallery_style', "
			<style type='text/css'>
				#{$selector} {
					margin: auto;
				}
				#{$selector} .gallery-item {
					float: {$float};
					margin-top: 10px;
					text-align: center;
					width: {$itemwidth}%;           }
				#{$selector} img {
					border: 2px solid #cfcfcf;
				}
				#{$selector} .gallery-caption {
					margin-left: 0;
				}
			</style>
			<!-- see gallery_shortcode() in wp-includes/media.php -->
			<div id='$selector' class='gallery galleryid-{$id}'>");
	
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
	
			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon'>
					$link
				</{$icontag}>";
			if ( $captiontag && trim($attachment->post_excerpt) ) {
				$output .= "
					<{$captiontag} class='gallery-caption'>
					" . wptexturize($attachment->post_excerpt) . "
					</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			if ( $columns > 0 && ++$i % $columns == 0 )
				$output .= '<br style="clear: both" />';
		}
	
		$output .= "
				<br style='clear: both;' />
			</div>\n";
	
		return $output;
	}

	///////////////////////////////////////
	//	Try to add Post Format Support
	///////////////////////////////////////
	add_theme_support( 'post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat') );
	
	if(!function_exists('has_post_format')){
		function has_post_format($post_type){
			return false;
		}
	}

	///////////////////////////////////////
	// Filter RSS Feed to include Custom Fields
	///////////////////////////////////////
	add_filter('the_content', 'themify_post_format_custom_fields');

	function themify_post_format_custom_fields( $content ) {
	
		global $post, $id, $themify_check;
		if(!is_feed() || $themify_check == true){
			return $content;
		}
		$post_format = themify_get('post_format');
		
		if($post_format == 'image' && themify_check('post_image')) { 
			$content .= "<img src='".themify_get('post_image')."'>";
		} elseif($post_format == 'quote' && themify_check('quote_author')) {
			$content = '"'.$content.'" '.themify_get('quote_author')." - <a href='".themify_get('quote_author_link')."'>".themify_get('quote_author_link')."</a>";
		} elseif($post_format == 'link' && themify_check('link_url')) {
			$content .= "<a href='".themify_get('link_url')."'>".themify_get('link_url')."</a>";
		} elseif($post_format == 'audio' && themify_check('audio_url')) {
			$content .= "<p><img src='".themify_get('post_image')."'></p>";
			$content .= themify_get('audio_url');
		} elseif($post_format == 'video' && themify_check('video_url')) {
			$themify_check = true;
			$content = apply_filters('the_content', themify_get('video_url')) . $content;
		}
		$themify_check = false;
		return $content;
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
		echo '<ul id="main-nav" class="clearfix">';
		wp_list_pages('title_li=');
		echo '</ul>';
	}

	///////////////////////////////////////
	// Register Sidebars
	///////////////////////////////////////
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'name' => __('Sidebar', 'themify'),
			'id' => 'sidebar-main',
			'before_widget' => '<div class="widgetwrap"><div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div></div>',
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
		register_sidebar(array(
			'name' => __('Header Widget', 'themify'),
			'id' => 'header-widget',
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
				<?php echo get_avatar($comment,$size='48'); ?>
				<?php printf('<cite>%s</cite>', get_comment_author_link()) ?><br />
				<small class="comment-time"><strong><?php comment_date( apply_filters( 'themify_comment_date', '' ) ); ?></strong> @ <?php comment_time( apply_filters( 'themify_comment_time', '' ) ); ?><?php edit_comment_link( __('Edit', 'themify'),' [',']') ?></small>
			</p>
			<div class="commententry">
				<?php if ($comment->comment_approved == '0') : ?>
				<p><em><?php _e('Your comment is awaiting moderation.', 'themify') ?></em></p>
				<?php endif; ?>
			
				<?php comment_text() ?>
			</div>
			<p class="reply">&larr; <?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'depth' => $depth, 'reply_text' => __( 'Reply', 'themify' ), 'max_depth' => $args['max_depth']))) ?></p>
		<?php
	}
}