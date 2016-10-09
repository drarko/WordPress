<?php


/*
To add custom PHP functions to the theme, create a new 'custom-functions.php' file in the theme folder. 
They will be added to the theme automatically.
*/

/* 	Enqueue (and dequeue) Stylesheets and Scripts
/***************************************************************************/

function themify_theme_enqueue_scripts(){

	///////////////////
	//Enqueue styles
	///////////////////

	//Themify base stylesheet
	wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), wp_get_theme()->display('Version'));

	if( themify_woocommerce_active() ) {
		//Themify shop stylesheet
		wp_enqueue_style( 'themify-shop', THEME_URI . '/shop.css');
	}

	//Themify Media Queries stylesheet
	wp_enqueue_style( 'themify-media-queries', THEME_URI . '/media-queries.css');


	//Google Web Fonts
	wp_enqueue_style( 'google-fonts', themify_https_esc('http://fonts.googleapis.com/css'). '?family=Signika:400,600&subset=latin,latin-ext');

	///////////////////
	//Enqueue scripts
	///////////////////

	//Slider script
	wp_enqueue_script( 'jquery-slider', THEME_URI . '/js/jquery.slider.js', array('jquery'), false, true );

	//Themify internal script
	wp_enqueue_script( 'theme-script',	THEME_URI . '/js/themify.script.js', array('jquery', 'jquery-effects-core'), false, true );

	//Inject variable values in gallery script
	wp_localize_script( 'theme-script', 'themifyScript', array(
		'lightbox' => themify_lightbox_vars_init(),
		'lightboxContext' => apply_filters('themify_lightbox_context', '#pagewrap'),
		'variableLightbox' => themify_check( 'setting-variable_lightbox' ) ? '' : 'variable-lightbox',
	));

	if( themify_woocommerce_active() ) {
		//Themify shop script
		wp_enqueue_script( 'theme-shop',	THEME_URI . '/js/themify.shop.js', array('jquery'), false, true );

		// Get carousel variables
		$carou_visible = themify_get('setting-product_slider_visible');
		$carou_autoplay = themify_get('setting-product_slider_auto');
		$carou_speed = themify_get('setting-product_slider_speed');
		$carou_scroll = themify_get('setting-product_slider_scroll');
		$carou_wrap = themify_get('setting-product_slider_wrap');

		//Inject variable values in themify.shop.js
		global $woocommerce;
		wp_localize_script( 'theme-shop', 'themifyShop', array(
				'visible'	=> $carou_visible? $carou_visible : '4',
				'autoplay'	=> $carou_autoplay? $carou_autoplay : 0,
				'speed'		=> $carou_speed? $carou_speed : 300,
				'scroll'	=> $carou_scroll? $carou_scroll : 1,
				'wrap'		=> ('' == $carou_wrap || 'yes' == $carou_wrap)? 'circular' : null,
				'hideCart'  => (sizeof($woocommerce->cart->get_cart()) == 0)? 'hide' : 'show'
			)
		);
	}

	//WordPress thread comment reply script
	if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'themify_theme_enqueue_scripts', 11 );

/**
 * Add viewport tag for responsive layouts
 * @package themify
 */
function themify_viewport_tag(){
	echo "\n".'<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">'."\n";
}
add_action( 'wp_head', 'themify_viewport_tag' );

/* 	Custom Post Types
/***************************************************************************/

///////////////////////////////////////
// Setup Write Panel Options
///////////////////////////////////////

// Slider Post Type
register_post_type('slider', array(
	'labels' =>  array(
		'name' => __( 'Slides', 'themify' ),
		'singular_name' => __( 'Slide', 'themify' ),
		'add_new' => __( 'Add New Slide', 'themify' ),
		'add_new_item' => __( 'Add New Slide', 'themify' ),
		'edit_item' => __( 'Edit Slide', 'themify' ),
		'new_item' => __( 'New Slide', 'themify' ),
		'view_item' => __( 'View Slide', 'themify' ),
		'search_items' => __( 'Search Slides', 'themify' ),
		'not_found' => __( 'No slides found', 'themify' ),
		'not_found_in_trash' => __( 'No slides found in Trash', 'themify' ),
		'parent_item_colon' => __( 'Parent Slider:', 'themify' ),
		'menu_name' => __( 'Slider', 'themify' ),
	),
	'description' => "",
	'menu_position' => 5,
	'public' => true,
	'show_ui' => true,
	'capability_type' => 'post',
	'hierarchical' => false,
	'rewrite' => false,
	'query_var' => false,
	'supports' => array('title', 'editor', 'author', 'custom-fields')
));

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
	// Post Image
	array(
		"name" 		=> "post_image",
		"title" 		=> __('Featured Image', 'themify'),
		"description" => '',
		"type" 		=> "image",
		"meta"		=> array()
	),
	// Featured Image Size
	array(
		'name'	=>	'feature_size',
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
	// Hide Post Title
	array(
		"name" 		=> "hide_post_title",
		"title" 		=> __('Hide Post Title', 'themify'),
		"description" => "",
		"type" 		=> "dropdown",
		"meta"		=> array(
			array("value" => "default", "name" => "", "selected" => true),
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
		)
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
		"meta"		=> array()
	),
	// Lightbox Link + Zoom icon
	themify_lightbox_link_field()
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array('value' => 'grid4', 'img' => 'images/layout-icons/grid4.png', 'title' => __('Grid 4', 'themify')),
			array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify')),
			array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify')),
			array('value' => 'list-large-image', 'img' => 'images/layout-icons/list-large-image.png', 'title' => __('List Large Image', 'themify')),
			array('value' => 'list-thumb-image', 'img' => 'images/layout-icons/list-thumb-image.png', 'title' => __('List Thumb Image', 'themify')),
			array('value' => 'grid2-thumb', 'img' => 'images/layout-icons/grid2-thumb.png', 'title' => __('Grid 2 Thumb', 'themify'))
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
			array("name"=>"Full Content","value"=>"content","selected"=>true),
			array("name"=>"Excerpt","value"=>"excerpt"),
			array("name"=>"None","value"=>"none")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
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
			array("value" => "yes", "name" => "Yes"),
			array("value" => "no",	"name" => "No")
		)
	)
);

// Slider Meta Box Options
$slider_meta_box_options = array(
	// Post Layout
	array(
		"name" 		=> "layout",
		"title"		=> __('Slide Layout', 'themify'),
		"description"	=> "",
		"type"		=> "layout",
		'show_title' => true,
		"meta"		=> array(
			array('value' => 'slider-default', 'img' => 'images/layout-icons/slider-default.png', 'selected' => true, 'title' => __('Default', 'themify')),
			array('value' => 'slider-image-only', 'img' => 'images/layout-icons/slider-image-only.png', 'title' => __('Image Only', 'themify')),
			array('value' => 'slider-content-only', 'img' => 'images/layout-icons/slider-content-only.png', 'title' => __('Content Only', 'themify')),
			array('value' => 'slider-image-caption', 'img' => 'images/layout-icons/slider-image-caption.png', 'title' => __('Image Caption', 'themify'))
		)
	),
	// Feature Image
	array(
		"name" 		=> "feature_image",
		"title" 		=> __('Featured Image', 'themify'), //slider image
		"description" => "",
		"type" 		=> "image",
		"meta"		=> array()
	),
	// Featured Image Size
	array(
		'name'	=>	'feature_size',
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
	// Image Link
	array(
		"name" 		=> "image_link",
		"title" 		=> __('Image Link', 'themify'),
		"description" => "",
		"type" 		=> "textbox",
		"meta"		=> array()
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
		),
		array(
			"name"		=> __('Homepage Slider Options', 'themify'),
			'id' => 'slider-options',
			"options"	=> $slider_meta_box_options,
			"pages"	=> "slider"
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
		<p class="comment-author"> <?php echo get_avatar($comment,$size='48'); ?> <?php printf( '<cite>%s</cite>', get_comment_author_link()) ?><br />
			<small class="comment-time"><strong>
					<?php comment_date( apply_filters( 'themify_comment_date', '' ) ); ?>
				</strong> @
				<?php comment_time( apply_filters( 'themify_comment_time', '' ) ); ?>
				<?php edit_comment_link(__('Edit', 'themify'),' [',']') ?>
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

/**
 * Displays a link to edit the entry
 */
function themify_edit_link() {
	edit_post_link(__('Edit', 'themify'), '[', ']');
}

/**
 * Alters condition to filter layout class
 * @param bool
 * @return bool
 */
function themify_theme_default_layout_condition($condition){
	return $condition || themify_is_function('is_shop') || themify_is_function('is_product') || themify_is_function('is_product_category') || themify_is_function('is_product_tag');
}
/**
 * Returns default shop layout
 * @param String $class
 * @return @String
 */
function themify_theme_default_layout($class) {
	if( themify_is_function('is_shop') || themify_is_function('is_product_category') || themify_is_function('is_product_tag') ) {
		$class = themify_get('setting-shop_layout')? themify_get('setting-shop_layout') : 'sidebar1';
	} elseif( themify_is_function('is_product') ){
		$class = themify_get('setting-single_product_layout')? themify_get('setting-single_product_layout') : 'sidebar1';
	}
	return $class;
}
/**
 * Alters condition to filter post layout class
 * @param bool
 * @return bool
 */
function themify_theme_default_post_layout_condition($condition) {
	return $condition || themify_is_function('is_shop') || themify_is_function('is_product_category') || themify_is_function('is_product_tag');
};
/**
 * Returns default shop layout
 * @param String $class
 * @return @String
 */
function themify_theme_default_post_layout($class) {
	if( themify_is_function('is_shop') || themify_is_function('is_product_category') || themify_is_function('is_product_tag') ) {
		$class = '' != themify_get('setting-products_layout')? themify_get('setting-products_layout') : 'grid4';
	}
	return $class;
};

/**
 * Checks if it's the function name passed exists and in that case, it calls the function
 * @param string $context
 * @return bool|mixed
 * @since 1.3.6
 */
function themify_is_function( $context = '' ) {
	if( function_exists( $context ) )
		return call_user_func( $context );
	else
		return false;
}

// Filters to change body class applied in shop
add_filter('themify_default_layout_condition', 'themify_theme_default_layout_condition');
add_filter('themify_default_layout', 'themify_theme_default_layout');
add_filter('themify_default_post_layout_condition', 'themify_theme_default_post_layout_condition');
add_filter('themify_default_post_layout', 'themify_theme_default_post_layout');

///////////////////////////////////////
// Start Woocommerce functions
///////////////////////////////////////

// Declare Woocommerce support
add_theme_support( 'woocommerce' );

add_action( 'themify_layout_before', 'themify_theme_layout_before' );
add_action( 'template_redirect', 'themify_redirect_product_ajax_content', 20 );
add_action( 'admin_notices', 'themify_check_ecommerce_environment_admin' );

add_filter('woocommerce_params', 'themify_woocommerce_params');
add_filter('themify_body_classes', 'themify_woocommerce_site_notice_class');

if ( ! function_exists( 'themify_woocommerce_site_notice_class' ) ) {
	/**
	 * Add additional class when Woocommerce site wide notice is enabled.
	 * @param array $classes
	 * @return array
	 * @since 1.3.6
	 */
	function themify_woocommerce_site_notice_class( $classes ) {
		$notice = get_option( 'woocommerce_demo_store' );
		if ( ! empty( $notice ) && 'no' != $notice ) {
			$classes[] = 'site-wide-notice';
		}
		return $classes;
	}
}

if ( ! function_exists( 'themify_woocommerce_active' ) ) {
	/**
	 * Checks if Woocommerce is installed and active
	 * @return bool
	 */
	function themify_woocommerce_active() {
		$plugin = 'woocommerce/woocommerce.php';
		$network_active = false;
		if ( is_multisite() ) {
			$plugins = get_site_option( 'active_sitewide_plugins' );
			if ( isset( $plugins[$plugin] ) )
				$network_active = true;
		}
		return in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || $network_active;
	}
}

if ( ! function_exists( 'themify_get_ecommerce_template' ) ) {
	/**
	 * Checks if Woocommerce is active and loads the requested template
	 * @param string $template
	 * @since 1.3.6
	 */
	function themify_get_ecommerce_template( $template = '' ) {
		if ( themify_woocommerce_active() )
			get_template_part( $template );
	}
}

/**
 * Include slider and, if Woocommerce is active, product slider
 * @since 1.3.6
 */
function themify_theme_layout_before() {
	if(is_front_page() && !is_paged()){ get_template_part( 'includes/slider'); }
	if(is_front_page() && !is_paged()){ themify_get_ecommerce_template( 'includes/product-slider'); }
}

/**
 * Add woocommerce_enable_ajax_add_to_cart option to JS
 * @param Array
 * @return Array
 */
function themify_woocommerce_params($params){
	return array_merge($params, array(
		'option_ajax_add_to_cart' => ( 'yes' == get_option('woocommerce_enable_ajax_add_to_cart') )? 'yes': 'no'
	) );
}

/**
 * Put product variations in page
 */
function themify_available_variations() {
	global $product;
	if(isset($product->product_type) && 'variable' == $product->product_type){
		$product_vars = $product->get_available_variations();
		themify_json_esc_array($product_vars);
	} else {
		$product_vars = array();
	}
	echo '<div style="display:none;" id="themify_product_vars">' . json_encode($product_vars) . '</div>';
};

/**
 * Escape array data for later json_encode
 * @param array $arr_r Array passed by reference with data to be escaped
 */
function themify_json_esc_array(&$arr_r) {
	if(is_array($arr_r)) {
		foreach ($arr_r as &$val) {
			if(is_array($val)) {
				themify_json_esc_array($val);
			} else {
				$val = esc_html( str_replace('"', '\'', $val) );
			}
			unset($val);
		}
	} else {
		$arr_r = esc_html( str_replace('"', '\'', $val) );
	}
}

/**
 * Single product lightbox
 **/
function themify_redirect_product_ajax_content() {
	global $post, $wp_query;
	// locate template single page in lightbox
	if (is_single() && isset($_GET['ajax']) && $_GET['ajax']) {
		// remove admin bar inside iframe
		add_filter( 'show_admin_bar', '__return_false' );
		if (have_posts()) {
			woocommerce_single_product_content_ajax();
			die();
		} else {
			$wp_query->is_404 = true;
		}
	}
}

if ( ! function_exists( 'themify_check_ecommerce_environment_admin' ) ) {
	/**
	 * Check in admin if Woocommerce is enabled and show a notice otherwise.
	 * @since 1.3.0
	 */
	function themify_check_ecommerce_environment_admin() {
		if ( ! themify_woocommerce_active() ) {
			$warning = 'installwoocommerce';
			if ( ! get_option( 'themify_warning_' . $warning ) ) {
				wp_enqueue_script( 'themify-admin-warning' );
				echo '<div class="update-nag">'.__('Remember to install and activate WooCommerce plugin to enable the shop.', 'themify'). ' <a href="#" class="themify-close-warning" data-warning="' . $warning . '" data-nonce="' . wp_create_nonce( 'themify-warning' ) . '">' . __("Got it, don't remind me again.", 'themify') . '</a></div>';
			}
		}
	}
}

if ( ! function_exists( 'themify_check_ecommerce_scripts' ) ) {
	function themify_check_ecommerce_scripts() {
		wp_register_script( 'themify-admin-warning', THEME_URI . '/js/themify.admin.warning.js', array('jquery'), false, true );
	}
	add_action( 'admin_enqueue_scripts', 'themify_check_ecommerce_scripts' );
}

if ( ! function_exists( 'themify_dismiss_warning' ) ) {
	function themify_dismiss_warning() {
		check_ajax_referer( 'themify-warning', 'nonce' );
		$result = false;
		if ( isset( $_POST['warning'] ) ) {
			$result = update_option( 'themify_warning_' . $_POST['warning'], true );
		}
		if ( $result ) {
			echo 'true';
		} else {
			echo 'false';
		}
		die;
	}
	add_action( 'wp_ajax_themify_dismiss_warning', 'themify_dismiss_warning' );
}

// Load required files
if ( themify_woocommerce_active() ) {
	require_once(TEMPLATEPATH . '/woocommerce/theme-woocommerce.php'); // WooCommerce overrides
	require_once(TEMPLATEPATH . '/woocommerce/woocommerce-hooks.php'); // WooCommerce hook overrides
	require_once(TEMPLATEPATH . '/woocommerce/woocommerce-template.php'); // WooCommerce template overrides
}
