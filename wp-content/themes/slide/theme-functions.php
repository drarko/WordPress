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
	wp_enqueue_style( 'theme-style', get_stylesheet_uri());

	//Themify Media Queries CSS
	wp_enqueue_style( 'themify-media-queries', THEME_URI . '/media-queries.css');


	//Google Web Fonts embedding
	wp_enqueue_style( 'google-fonts', themify_https_esc('http://fonts.googleapis.com/css'). '?family=Abril+Fatface|Arapey:400italic,400&subset=latin,latin-ext');

	///////////////////
	//Enqueue scripts
	///////////////////

	//flex slider
	wp_enqueue_script( 'flexslider-js', THEME_URI . '/js/jquery.flexslider-min.js', array('jquery'), false, true );

	//fullscreen window
	wp_enqueue_script( 'fullscreen-js', THEME_URI . '/js/jquery.fullscreen-min.js', array('jquery'), false, true );

	//image loaded plugin
	wp_enqueue_script( 'image-loaded-js', THEME_URI . '/js/jquery.imagesloaded.min.js', array('jquery'), false, true );

	//swipe jquery plugin
	wp_enqueue_script( 'swipe-js', THEME_URI . '/js/jquery.touchwipe.js', array('jquery'), false, true );

	//carousel fred plugin
	wp_enqueue_script( 'carouselfred-js', THEME_URI . '/js/jquery.carouFredSel-5.6.4-packed.js', array('jquery'), false, true );

	//auto height iframe plugin
	wp_enqueue_script( 'auto-height-iframe', THEME_URI . '/js/jquery.iframe-auto-height.plugin.1.8.0.min.js', array('jquery'), false, true );

	//isotope, used to dinamically filter posts
	wp_enqueue_script( 'isotope', THEME_URI . '/js/jquery.isotope.min.js', array('jquery'), false, true );

	//Themify internal scripts
	wp_enqueue_script( 'theme-script',	THEME_URI . '/js/themify.script.js', array('jquery'), false, true );

	/** Get slider settings
	 * @var Array */
	$data = themify_get_data();

	wp_localize_script( 'theme-script', 'themifyScript', array(
		'lightbox' => themify_lightbox_vars_init(),
		'lightboxContext' => apply_filters('themify_lightbox_context', 'body'),
		'sliderAuto' => 'off' == isset($data['setting-feature_box_auto']) && $data['setting-feature_box_auto']? 'false': 'true',
		'sliderSpeed' => isset($data['setting-feature_box_auto']) && 'off' != $data['setting-feature_box_auto']? $data['setting-feature_box_auto']: '4000',
		'sliderAnimationSpeed' => $data['setting-feature_box_speed']? $data['setting-feature_box_speed']: '1000',
		'sliderEffect' => $data['setting-feature_box_effect']? $data['setting-feature_box_effect']: 'slide',
	));

	//WordPress internal script to move the comment box to the right place when replying to a user
	if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' );

}

/**
 * Add viewport tag for responsive layouts
 * @package themify
 */
function themify_viewport_tag(){
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">';
}
add_action( 'wp_head', 'themify_viewport_tag' );

/**
 * Add sidebar layout and post layout classes to body tag.
 * @param Array
 * @return Array
 * @package themify
 * @since 1.0.0
 */
function themify_add_body_classes($classes) {
	// If it's post in lightbox, do nothing
	if( isset($_GET['post_in_lightbox']) && $_GET['post_in_lightbox'] == 1 ){
	    $classes[] = 'post-lightbox-iframe';
		return $classes;
	}
	return $classes;
}
add_filter('body_class', 'themify_add_body_classes');

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
	// Allow Sorting
	array(
		  "name" 		=> 'allow_sorting',
		  "title"		=> __('Allow Sorting', 'themify'),
		  "description"	=> __('This will add a sorting navigation', 'themify'),
		  "type" 		=> 'dropdown',
		  "meta"		=> array(
				array("value" => 'default', 'name' => '', 'selected' => true),
				array("value" => 'yes', 'name' => __('Yes', 'themify')),
				array("value" => 'no',	'name' => __('No', 'themify'))
			)
		),
	// Post in lightbox
	array(
		  "name" 		=> 'post_in_lightbox',
		  "title"		=> __('Post Lightbox', 'themify'),
		  "description"	=> __('Open post in lightbox window', 'themify'),
		  "type" 		=> 'dropdown',
		  "meta"		=> array(
				array("value" => 'yes', 'name' => __('Yes', 'themify'), 'selected' => true),
				array("value" => 'no',	'name' => __('No', 'themify'))
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
				array('value' => 'grid2-thumb', 'img' => 'images/layout-icons/grid2-thumb.png', 'title' => __('Grid 2 Thumb', 'themify')),
				array("value" => "slideshow", "img" => "images/layout-icons/slideshow.png", 'title' => __('Slideshow', 'themify'))
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
	add_theme_support( 'post-thumbnails', array( 'post' ) );

	if(!function_exists('themify_theme_get_featured_image_link')) {
		/**
		 * Filters the link generation to include changes for this theme
		 * @param string $args Standard link generated in Themify framework
		 * @return string $link Filtered link
		 */
		function themify_theme_get_featured_image_link( $args ) {
			$defaults = array (
				'no_permalink' => false // if there is no lightbox link, don't return a link
			);
			$args = wp_parse_args( $args, $defaults );
			if ( themify_get('external_link') != '') {
				$link = esc_url(themify_get('external_link'));
			} elseif ( themify_get('lightbox_link') != '') {
				$link = esc_url(themify_get('lightbox_link'));
				if(themify_check('iframe_url')) {
					$do_iframe = '?iframe=true&width=100%&height=100%';
				} else {
					$do_iframe = '';
				}
				$link = $link . $do_iframe . '" class="themify_lightbox';
			} elseif ( $args['no_permalink'] ) {
				$link = '';
			} else {
				$link = get_permalink();
				if( !is_single() && '' != themify_get('setting-open_inline') ){
					$link = get_permalink().'?post_in_lightbox=1" class="themify_lightbox_post';
				}
				if( themify_is_query_page() ){
					global $themify;
					$post_in_lightbox = get_post_meta($themify->query_page_id, 'post_in_lightbox', true);
					if( 'no' == $post_in_lightbox ){
						$link = get_permalink();
					} elseif( 'yes' == $post_in_lightbox ){
						$link = get_permalink().'?post_in_lightbox=1" class="themify_lightbox_post';
					} elseif('' != themify_get('setting-open_inline')) {
						$link = get_permalink().'?post_in_lightbox=1" class="themify_lightbox_post';
					}
				}
			}
			return $link;
		};
		add_filter('themify_get_featured_image_link', 'themify_theme_get_featured_image_link');
	}

	///////////////////////////////////////
	// Register Custom Menu Function
	///////////////////////////////////////
	function themify_register_custom_nav() {
		if (function_exists('register_nav_menus')) {
			register_nav_menus( array(
				'main-nav' => __( 'Main Navigation', 'themify' )
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
			'id' => 'sidebar',
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
	// Footer Widgets
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
		<small class="comment-time"><strong>
		<?php comment_date( apply_filters( 'themify_comment_date', '' ) ); ?>
		</strong> @
		<?php comment_time( apply_filters( 'themify_comment_time', '' ) ); ?>
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

	/**
	 * Template redirect function
	 **/
	function themify_do_theme_redirect($url) {
		global $post, $wp_query;

		if (have_posts()) {
			include($url);
			die();
		} else {
			$wp_query->is_404 = true;
		}
	}

	/**
	 * Single post lightbox
	 **/
	function themify_single_post_lightbox() {
		global $wp;

		// locate template single page in lightbox
		if (is_single() && isset($_GET['post_in_lightbox']) && $_GET['post_in_lightbox'] == 1) {

			// remove admin bar inside iframe
			add_filter( 'show_admin_bar', '__return_false' );

			$templatefilename = 'single-lightbox.php';

			$return_template = locate_template( $templatefilename );

			themify_do_theme_redirect($return_template);

		}

	}
	add_action( 'template_redirect', 'themify_single_post_lightbox', 10 );
