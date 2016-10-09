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
	

	///////////////////
	//Enqueue scripts
	///////////////////
		
	//slider
	wp_enqueue_script( 'jquery-slider', THEME_URI . '/js/jquery.slider.js', array('jquery'), false, true );	
	
	//Themify internal scripts
	wp_enqueue_script( 'theme-script',	THEME_URI . '/js/themify.script.js', array('jquery'), false, true );
	
	//Inject variable values in gallery script
	wp_localize_script( 'theme-script', 'themifyScript', array(
		'lightbox' => themify_lightbox_vars_init(),
		'lightboxContext' => apply_filters('themify_lightbox_context', '#pagewrap')
	));
	
	//WordPress internal script to move the comment box to the right place when replying to a user
	if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' );
	
}
	
/* 	Custom Post Types
/***************************************************************************/

function themify_theme_register_custom_post_type_and_taxonomy() {
	// Slider Post Type
	register_post_type('slider', array(
			'label' => __('Slides', 'themify'),
			'singular_label' => __('Slide', 'themify'),
			'description' => '',
			'menu_position' => 5,
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => false,
			'query_var' => false,
			'supports' => array('title', 'editor', 'author', 'custom-fields')
		));

	// Menu Post Type
	register_post_type('menu', array(
			'label' => __('Menu', 'themify'),
			'singular_label' => __('Menu', 'themify'),
			'description' => '',
			'menu_position' => 6,
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array('slug' => 'menu'),
			'query_var' => false,
			'supports' => array('title', 'editor', 'author', 'custom-fields')
		));
	// Add menu categories
	register_taxonomy( 'menu_categories', 'menu',
		array(
			'hierarchical' => true,
			'label' => __('Categories', 'themify'),
			'rewrite' => true
		)
	);
};
add_action( 'init', 'themify_theme_register_custom_post_type_and_taxonomy' );

/* Custom Write Panels
/***************************************************************************/

if ( ! function_exists( 'themify_theme_init_types' ) ) {
	/**
	 * Initialize custom panel with its definitions
	 * Custom panel definitions are located in admin/post-type-TYPE.php
	 * @since 1.0.0
	 */
	function themify_theme_init_types() {
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
				)
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

		$themify_cats = array(
			array( 'name' => '', 'value' => '', 'selected' => true ),
			array( 'name' => __('All Categories', 'themify'), 'value' => 'all' ),
		);

		foreach ( get_terms( 'menu_categories' ) as $menu ) {
			$themify_cats[] = array( 'name' => $menu->name, 'value' => $menu->term_id );
		}

		// Menu Custom Options
		$menu_custom_options = array(
			// Menu Categories
			array(
				  "name"		=> "menu_category",
				  "title"		=> __('Category', 'themify'),
				  "description" => __('Select a menu category or enter multiple category IDs below', 'themify'),
				  "type"		=> "dropdown",
				  "meta"		=> $themify_cats
				  ),
			// Menu Categories
			array(
				  "name"		=> "menu_category_list",
				  "title"		=> __('Multiple Menu Categories', 'themify'),
				  "description" => __('eg. 3,4,8', 'themify'),
				  "type"		=> "textbox",
				  "meta"		=> array("size"=>"small")
				 ),
			// Section Menu Categories
			array(
				  "name" 		=> "section_menu_categories",
				  "title" 		=> __('Section Categories', 'themify'),
				  "description" => __('Display multiple menu categories separately', 'themify'),
				  "type" 		=> "dropdown",
				  "meta"		=> array(
						array("value" => "default", "name" => "", "selected" => true),
						array("value" => "yes", 'name' => __('Yes', 'themify')),
						array("value" => "no",	'name' => __('No', 'themify'))
					)
				),
			// Menu Layout
			array(
				  "name" 		=> "menu_layout",
				  "title"		=> __('Layout', 'themify'),
				  "description"	=> "",
				  "type"		=> "layout",
				'show_title' => true,
				  "meta"		=> array(
						array("value" => "grid2", "img" => "images/layout-icons/menu-2col.png", "selected" => true, 'title' => __('2 Columns', 'themify')),
						array("value" => "grid3", "img" => "images/layout-icons/menu-3col.png", 'title' => __('3 Columns', 'themify')),
						array("value" => "fullwidth", "img" => "images/layout-icons/menu-list.png", 'title' => __('List', 'themify'))
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
			// Image Width
			array(
				  "name" 		=> "menu_image_width",
				  "title" 		=> __('Image Width', 'themify'),
				  "description" => "",
				  "type" 		=> "textbox",
				  "meta"		=> array("size"=>"small")
				),
			// Image Height
			array(
				  "name" 		=> "menu_image_height",
				  "title" 		=> __('Image Height', 'themify'),
				  "description" => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'),
				  "type" 		=> "textbox",
				  "meta"		=> array("size"=>"small")
				),
			// Zoom Image
			array(
				  "name"		=> "zoom_image",
				  "title"		=> __('Zoom image', 'themify'),
				  "description"	=>	"",
				  "type"		=> "dropdown",
				  "meta"		=> array(
										array("name"=>"Yes","value"=>"yes"),
										array("name"=>"No","value"=>"no")
									)
				  ),
			// Link to Menu item
			array(
				  "name" 		=> "link_to_menu",
				  "title" 		=> __('Link to menu item', 'themify'),
				  "description" => __('Link menu item to post', 'themify'),
				  "type" 		=> "checkbox",
				  "meta"		=> array()
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
			// Image Link
			array(
				  "name" 		=> "image_link",
				  "title" 		=> __('Image Link', 'themify'),
				  "description" => "",
				  "type" 		=> "textbox",
				  "meta"		=> array()
				)
		);

		// Menu Meta Box Options
		$menu_meta_box_options = array(
			// Feature Image
			array(
				  "name" 		=> "feature_image",
				  "title" 		=> __('Menu Image', 'themify'),
				  "description" => "",
				  "type" 		=> "image",
				  "meta"		=> array()
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
			// Price
			array(
				  "name" 		=> "price",
				  "title" 		=> __('Price', 'themify'),
				  "description" => "",
				  "type" 		=> "textbox",
				  "meta"		=> array("size"=>"small")
				),
			// Menu Icon 1
			array(
				  "name" 		=> "icon",
				  "title"		=> __('Icon', 'themify') . " 1",
				  "description"	=> "",
				  "type"		=> "layout",
				'show_title' => true,
				  "meta"		=> array(
						array("value" => "", 	 "img" => "images/layout-icons/icon-none.png", "selected" => true),
						array("value" => "new", 	 "img" => "images/layout-icons/icon-new.png"),
						array("value" => "favorite", 	 "img" => "images/layout-icons/icon-favorite.png"),
						array("value" => "happy", 	 "img" => "images/layout-icons/icon-happy.png"),
						array("value" => "hot", 	 "img" => "images/layout-icons/icon-hot.png"),
						array("value" => "popular", 	 "img" => "images/layout-icons/icon-popular.png"),
						array("value" => "recommend", 	 "img" => "images/layout-icons/icon-recommend.png"),
						array("value" => "tag-blue", 	 "img" => "images/layout-icons/icon-tag-blue.png"),
						array("value" => "tag-green", 	 "img" => "images/layout-icons/icon-tag-green.png"),
						array("value" => "tag-orange", 	 "img" => "images/layout-icons/icon-tag-orange.png"),
						array("value" => "tag-pink", 	 "img" => "images/layout-icons/icon-tag-pink.png"),
						array("value" => "tag-yellow", 	 "img" => "images/layout-icons/icon-tag-yellow.png"),
					)
				),
			// Menu Icon 2
			array(
				  "name" 		=> "icon2",
				  "title"		=> __('Icon', 'themify') . " 2",
				  "description"	=> "",
				  "type"		=> "layout",
				'show_title' => true,
				  "meta"		=> array(
					 array("value" => "", 	 "img" => "images/layout-icons/icon-none.png", "selected" => true),
					 array("value" => "new", 	 "img" => "images/layout-icons/icon-new.png"),
					 array("value" => "favorite", 	 "img" => "images/layout-icons/icon-favorite.png"),
					 array("value" => "happy", 	 "img" => "images/layout-icons/icon-happy.png"),
					 array("value" => "hot", 	 "img" => "images/layout-icons/icon-hot.png"),
					 array("value" => "popular", 	 "img" => "images/layout-icons/icon-popular.png"),
					 array("value" => "recommend", 	 "img" => "images/layout-icons/icon-recommend.png"),
					 array("value" => "tag-blue", 	 "img" => "images/layout-icons/icon-tag-blue.png"),
					 array("value" => "tag-green", 	 "img" => "images/layout-icons/icon-tag-green.png"),
					 array("value" => "tag-orange", 	 "img" => "images/layout-icons/icon-tag-orange.png"),
					 array("value" => "tag-pink", 	 "img" => "images/layout-icons/icon-tag-pink.png"),
					 array("value" => "tag-yellow", 	 "img" => "images/layout-icons/icon-tag-yellow.png"),
					)
				),
			// Menu Icon 3
			array(
				  "name" 		=> "icon3",
				  "title"		=> __('Icon', 'themify') . " 3",
				  "description"	=> "",
				  "type"		=> "layout",
				'show_title' => true,
				  "meta"		=> array(
						array("value" => "", 	 "img" => "images/layout-icons/icon-none.png", "selected" => true),
						array("value" => "new", 	 "img" => "images/layout-icons/icon-new.png"),
						array("value" => "favorite", 	 "img" => "images/layout-icons/icon-favorite.png"),
						array("value" => "happy", 	 "img" => "images/layout-icons/icon-happy.png"),
						array("value" => "hot", 	 "img" => "images/layout-icons/icon-hot.png"),
						array("value" => "popular", 	 "img" => "images/layout-icons/icon-popular.png"),
						array("value" => "recommend", 	 "img" => "images/layout-icons/icon-recommend.png"),
						array("value" => "tag-blue", 	 "img" => "images/layout-icons/icon-tag-blue.png"),
						array("value" => "tag-green", 	 "img" => "images/layout-icons/icon-tag-green.png"),
						array("value" => "tag-orange", 	 "img" => "images/layout-icons/icon-tag-orange.png"),
						array("value" => "tag-pink", 	 "img" => "images/layout-icons/icon-tag-pink.png"),
						array("value" => "tag-yellow", 	 "img" => "images/layout-icons/icon-tag-yellow.png"),
					)
				)
		);


		///////////////////////////////////////
		// Build Write Panels
		///////////////////////////////////////
		themify_build_write_panels( apply_filters('themify_theme_meta_boxes',
			array(
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
					 "name"		=> __('Menu Query Options', 'themify'),
					 'id' => 'query-menu',
					 "options"  => $menu_custom_options,
					 "pages"    => "page"
					),
				array(
					 "name"		=> __('Homepage Slider Options', 'themify'),
					'id' => 'slider-options',
					 "options"	=> $slider_meta_box_options,
					 "pages"	=> "slider"
					 ),
				array(
					 "name"		=> __('Menu Options', 'themify'),
					 'id' => 'menu-options',
					 "options"	=> $menu_meta_box_options,
					 "pages"	=> "menu"
					 )
				)
			)
		);
	}
}
add_action( 'init', 'themify_theme_init_types', 12 );

/* 	Custom Functions
/***************************************************************************/

	///////////////////////////////////////
	// Enable WordPress feature image
	///////////////////////////////////////
	add_theme_support( 'post-thumbnails' );
		
	// Register Custom Menu Function
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
	
	// Default Main Nav Function
	function themify_default_main_nav() {
		echo '<ul id="main-nav" class="main-nav clearfix">';
		wp_list_pages('title_li=');
		echo '</ul>';
	}

	// Register Sidebars
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
			'name' => __('Header Widget', 'themify'),
			'id' => 'header-widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<strong class="widgettitle">',
			'after_title' => '</strong>',
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

	// Homepage Widgets
	themify_register_grouped_widgets(
		array(
			'homewidget-4col' => 4,
			'homewidget-3col' => 3,
			'homewidget-2col' => 2,
			'homewidget-1col' => 1
		),
		array(
			'sidebar_name' => __('Home Widget', 'themify'),
			'sidebar_id' => 'home-widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>'
		),
		'setting-homepage_widgets',
		'homewidget-3col'
	);
	
	// Footer Sidebars
	themify_register_grouped_widgets();
	
	// Check if paged > 1
	function show_posts_nav() {
		global $wp_query;
		return ($wp_query->max_num_pages > 1);
	}

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
		<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
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
			<p class="reply"><?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'depth' => $depth, 'reply_text' => __( 'Reply', 'themify' ), 'max_depth' => $args['max_depth']))) ?></p>
		<?php
	}
}

// Set class on body tag
add_filter( 'body_class', 'tf_rezo_body_classes', 99 );

if( !function_exists('tf_rezo_body_classes')){
	function tf_rezo_body_classes( $classes ){
		// Add section-categories-on class to body if user selected it in custom panel
		if ( ( is_single() || is_page() ) && 'yes' == themify_get( 'section_menu_categories' ) ) {
			$classes[] = 'section-categories-on';
		}

		return apply_filters('themify_body_classes', $classes);
	}
}