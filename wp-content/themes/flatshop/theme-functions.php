<?php
/***************************************************************************
 *  					Theme Functions
 * 	----------------------------------------------------------------------
 * 						DO NOT EDIT THIS FILE
 *	----------------------------------------------------------------------
 * 
 *  					Copyright (C) Themify
 * 						http://themify.me
 *
 *  To add custom PHP functions to the theme, create a new 'custom-functions.php' file in the theme folder.
 *  They will be added to the theme automatically.
 * 
 ***************************************************************************/

/////// Actions ////////
// Setup post, page and additional post types if they exist
add_action( 'after_setup_theme', 'themify_theme_init_types' );
// Build custom panels for post, page and additional post types
add_action( 'init', 'themify_theme_create_custom_panels', 11 );

// Enqueue scripts and styles required by theme
add_action( 'wp_enqueue_scripts', 'themify_theme_enqueue_scripts', 11 );

// Browser compatibility
add_action( 'wp_head', 'themify_viewport_tag' );


// Register custom menu
add_action( 'init', 'themify_register_custom_nav');

// Register sidebars
add_action( 'widgets_init', 'themify_theme_register_sidebars' );


/**
 * Enqueue Stylesheets and Scripts
 * @since 1.0.0
 */
function themify_theme_enqueue_scripts() {
	global $wp_query, $themify;

	// Load WooCommerce PrettyPhoto resources to fix image gallery in product lightbox
	if ( themify_is_woocommerce_active() ) {
		global $woocommerce;
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$lightbox_en = get_option( 'woocommerce_enable_lightbox' ) == 'yes' ? true : false;
		if ( $lightbox_en ) {
			wp_enqueue_script( 'prettyPhoto', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), $woocommerce->version, true );
			wp_enqueue_script( 'prettyPhoto-init', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery' ), $woocommerce->version, true );
			wp_enqueue_style( 'woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/assets/css/prettyPhoto.css', array(), $woocommerce->version );
		}
	}

	// Get theme version for Themify theme scripts and styles
	$theme_version = wp_get_theme()->display('Version');

	///////////////////
	//Enqueue styles
	///////////////////

	// Themify base styling
	wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), $theme_version);

	if ( themify_is_woocommerce_active() ) {
		//Themify shop stylesheet
		wp_enqueue_style( 'themify-shop', THEME_URI . '/shop.css');
	}

	// Themify Media Queries CSS
	wp_enqueue_style( 'themify-media-queries', THEME_URI . '/media-queries.css', array(), $theme_version);
	

	// Google Web Fonts embedding
	wp_enqueue_style( 'google-fonts', themify_https_esc('http://fonts.googleapis.com/css'). '?family=Raleway:300,400&subset=latin,latin-ext');

	///////////////////
	//Enqueue scripts
	///////////////////

	//isotope, used to re-arrange blocks
	wp_enqueue_script( 'themify-isotope', THEME_URI . '/js/jquery.isotope.min.js', array('jquery'), false, true );

	//creates infinite scroll
	wp_enqueue_script( 'infinitescroll', THEME_URI . '/js/jquery.infinitescroll.min.js', array('jquery'), false, true );

	//Slider script
	wp_enqueue_script( 'jquery-slider', THEME_URI . '/js/jquery.slider.js', array('jquery'), false, true );

	
        $bufferPx = apply_filters( 'infinite_scroll_bufferPx', 40 );
	
	// Slide mobile navigation menu
	wp_enqueue_script( 'slide-nav', THEMIFY_URI . '/js/themify.sidemenu.js', array( 'jquery' ), $theme_version, true );

	//auto height iframe plugin
	wp_enqueue_script( 'auto-height-iframe', THEME_URI . '/js/jquery.iframe-auto-height.js', array('jquery'), false, true );

	// Themify custom lightbox
	wp_enqueue_script( 'themibox', THEME_URI . '/js/themibox.js', array('jquery'), $theme_version, true );
		
	// Themify internal scripts
	wp_enqueue_script( 'theme-script', THEME_URI . '/js/themify.script.js', array('jquery', 'jquery-effects-core'), $theme_version, true );

	// Get auto infinite scroll setting
	$autoinfinite = '';
	if ( ! themify_get( 'setting-autoinfinite' ) ) {
		$autoinfinite = 'auto';
	}

	// Slider Variables
	$data = themify_get_data();

	if ( ! isset( $data['setting-feature_box_speed'] ) ) {
		$speed = '4000';
	} else {
		$speed = $data['setting-feature_box_speed'];
	}
	if ( ! isset( $data['setting-feature_box_effect'] ) ) {
		$effect = 'slide';
	} else {
		$effect = $data['setting-feature_box_effect'];
	}
	if ( ! isset( $data['setting-feature_box_auto'] ) || '0' == $data['setting-feature_box_auto'] ) {
		$slideshowSpeed = 'false';
	} else {
		$slideshowSpeed = $data['setting-feature_box_auto'];
	}

	$headerParallax = themify_check('setting-parallax_scrolling_disabled')? false : true;
	if( themify_is_touch() ) { /* disable the parallax effect by default on mobile devices */
		$headerParallax = false;
	}

	// Inject variable values in gallery script
	wp_localize_script( 'theme-script', 'themifyScript', apply_filters('themify_script_vars', array(
		// Themify Lightbox/Fullscreen Gallery Parameters
		'lightbox' => themify_lightbox_vars_init(),
		'lightboxContext' => apply_filters('themify_lightbox_context', '#pagewrap'),
		// Fixed Header Parameter
		'fixedHeader' 	=> themify_check('setting-fixed_header_disabled')? '': 'fixed-header',
		// Infinite Scroll Parameters
		'loadingImg'   => THEME_URI . '/images/loading.gif',
		'maxPages'	   => $wp_query->max_num_pages,
		'autoInfinite' => $autoinfinite,
		'bufferPx' => $bufferPx,
		// Slider Parameters
		'animation' => $effect,
		'animationDuration' => $speed,
		'slideshow' => 0,
		'slideshowSpeed' => $slideshowSpeed,
		// Header Parallax
		'headerParallax' => $headerParallax,
		// Screen size at which horizontal menu is moved into side panel
		'smallScreen' => '900',
		// Resize refresh rate
		'resizeRefresh' => '250',
		// Masonry Layout Option
		'masonryLayout' => themify_check( 'setting-shop_masonry_disabled' )? '': 'masonry-layout',
	)));

	if ( themify_is_woocommerce_active() ) {
		// Themify shop script
		wp_enqueue_script( 'theme-shop', THEME_URI . '/js/themify.shop.js', array('jquery'), false, true );

		// Inject variable values in themify.shop.js
		// Can be filtered hooking to themify_shop_js_vars
		wp_localize_script( 'theme-shop', 'themifyShop', apply_filters(
				'themify_shop_js_vars',
				array(
					'themibox' => array(
						'close' => '<a href="#" class="close-lightbox"><i class="icon-flatshop-close"></i></a>',
					),
					'cartUrl' => WC()->cart->get_cart_url(),
                                        'redirect'=> get_option( 'woocommerce_cart_redirect_after_add' ) === 'yes'?wc_get_cart_url():false
				)
			)
		);
	}
	
	// WordPress internal script to move the comment box to the right place when replying to a user
	if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' );
}

/**
 * Add viewport tag for responsive layouts
 * @since 1.0.0
 */
function themify_viewport_tag() {
	echo "\n".'<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">'."\n";
}

/***************************************************************************
 * Custom Write Panels
 ***************************************************************************/

///////////////////////////////////////////
// Load Post, Page and Custom Post Types
///////////////////////////////////////////

if ( ! function_exists( 'themify_theme_init_types' ) ) {
	/**
	 * Load custom routines for post, page and custom post types.
	 *
	 * @since 1.0.7
	 */
	function themify_theme_init_types() {
		// Load required files for post, page and custom post types where it applies
		foreach( array(	'post',	'page', 'slider', 'product' ) as $cpt ) {
			require_once "admin/post-type-$cpt.php";
		}
	}
}

///////////////////////////////////////
// Build Write Panels
///////////////////////////////////////

if ( ! function_exists( 'themify_theme_create_custom_panels' ) ) {
	/**
	 * Initialize custom panel with its definitions
	 * Custom panel definitions are located in admin/post-type-TYPE.php
	 *
	 * @since 1.2.9
	 */
	function themify_theme_create_custom_panels() {
		themify_build_write_panels( apply_filters('themify_theme_meta_boxes',
			array(
				array(
					'name'		=> __('Post Options', 'themify'), 	// Name displayed in box
					'id' 		=> 'post-options',					// Panel ID used for tabs
					'options'	=> themify_theme_post_meta_box(),	// Field options
					'pages'		=> 'post'							// Pages to show write panel
				),
				array(
					'name'		=> __('Page Options', 'themify'),
					'id' 		=> 'page-options',
					'options'	=> themify_theme_page_meta_box(),
					'pages'		=> 'page'
				),
				array(
					'name'		=> __('Query Posts', 'themify'),
					'id' 		=> 'query-posts',
					'options'	=> themify_theme_query_post_meta_box(),
					'pages'		=> 'page'
				),
				array(
					'name'		=> __('Query Products', 'themify'),
					'id' 		=> 'query-products',
					'options'	=> themify_theme_query_product_meta_box(),
					'pages'		=> 'page'
				),
				array(
					'name'		=> __('Slider Options', 'themify'),
					'id' 		=> 'slider-options',
					'options'	=> themify_theme_slider_meta_box(),
					'pages'		=> 'slider'
				),
				array(
					'name'		=> __('Slider Style', 'themify'),
					'id' 		=> 'slider-style',
					'options'	=> themify_theme_slider_style_meta_box(),
					'pages'		=> 'slider'
				),
				array(
					'name'		=> __('Product Options', 'themify'),
					'id' 		=> 'product-options',
					'options'	=> themify_theme_product_meta_box(),
					'pages'		=> 'product'
				)
			)
		));
	}
}

/***************************************************************************
 * Custom Functions
 ***************************************************************************/

///////////////////////////////////////
// Enable WordPress feature image
///////////////////////////////////////
add_theme_support( 'post-thumbnails' );

if ( ! function_exists( 'themify_register_custom_nav' ) ) {
	/**
	 * Register Custom Menu Function
	 * @since 1.0.0
	 */
	function themify_register_custom_nav() {
		register_nav_menus( array(
			'main-nav' => __( 'Main Navigation', 'themify' ),
			'horizontal-menu' => __( 'Horizontal Menu', 'themify' ),
		));
	}
}

if ( ! function_exists( 'themify_default_main_nav' ) ) {
	/**
	 * Default Main Nav Function
	 * @since 1.0.0
	 */
	function themify_default_main_nav() {
		echo '<ul id="main-nav" class="main-nav clearfix">';
			wp_list_pages('title_li=');
		echo '</ul>';
	}
}

if ( ! function_exists( 'themify_theme_register_sidebars' ) ) {
	/**
	 * Register sidebars
	 * @since 1.0.0
	 */
	function themify_theme_register_sidebars() {
		$sidebars = array(
			array(
				'name' => __('Sidebar', 'themify'),
				'id' => 'sidebar-main',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			),
			array(
				'name' => __('Social Widget - Header', 'themify'),
				'id' => 'social-widget',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<strong class="widgettitle">',
				'after_title' => '</strong>',
			),
			array(
				'name' => __('Social Widget - Footer', 'themify'),
				'id' => 'social-widget-footer',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<strong class="widgettitle">',
				'after_title' => '</strong>',
			)
		);
		foreach( $sidebars as $sidebar ) {
			register_sidebar( $sidebar );
		}

		// Footer Sidebars
		themify_register_grouped_widgets();
	}
}

if ( ! function_exists( 'themify_theme_custom_post_css' ) ) {
	/**
	 * Outputs custom post CSS at the end of a post
	 * @since 1.0.0
	 */
	function themify_theme_custom_post_css() {
		global $themify;
		$css = array();
		$rules = array();
		$style = '';
		if ( ! isset( $themify->google_fonts ) ) {
			$themify->google_fonts = '';
		}
		$post_id = get_the_ID();
		$post_type = get_post_type( $post_id );
		switch( $post_type ) {
			case 'product':
				$id = '.post-' . $post_id;
				$product_id = '#product-' . $post_id;
				if ( is_singular( 'product' ) ) {
					$id .= ' .product-single-top';
					$pid = '.single ' . $id;
					$product_id = '.single ' . $product_id;
				}
				else {
					$pid = $id;
					if ( 'list-post' != $themify->post_layout )
						$id .= ' .product-bg';
					else
						$id = '.list-post ' . $id;
				}

				$rules = array(
					$id => array(
						array(	'prop' => 'background-color',
								'key' => 'background_color'
						),
						array(	'prop' => 'background-image',
								'key' => 'background_image'
						),
						array(	'prop' => 'background-repeat',
								'key' => 'background_repeat'
						),
						array(	'prop' => 'background-position',
								'key' => array('background_position_x', 'background_position_y')
						)
					),
					"$pid h3, $pid h3 a, $pid .product_title, $pid .product_title a, $pid .woocommerce-breadcrumb a, $pid .star-rating" => array(
						array(	'prop' => 'color',
								'key' => 'title_font_color'
						)
					),
					"$id .summary .price" => array(
						array(	'prop' => 'color',
								'key' => 'price_font_color'
						)
					),
					"$pid" => array(
						array(	'prop' => 'color',
								'key' => 'description_font_color'
						)
					),
					"$id .product-description a, $id .product_meta a, $id .quantity .plus, $id .quantity .minus" => array(
						array(	'prop' => 'color',
								'key' => 'link_font_color'
						)
					),
					"$pid .button.outline" => array(
						array(	'prop' => 'color',
								'key' => 'link_font_color',
								'important' => true
						),
						array(	'prop' => 'border-color',
								'key' => 'link_font_color'
						)
					),
					"$pid .single_add_to_cart_button, $pid .theme_add_to_cart_button, $pid .added_to_cart.button, $pid .quantity input.qty, $product_id .variations select" => array(
						array(	'prop' => 'color',
								'key' => 'button_font_color'
						),
						array(	'prop' => 'background-color',
								'key' => 'button_background_color'
						),
						array(	'prop' => 'border-color',
								'key' => 'button_background_color'
						),
					),
				);
				break;
			case 'slider':
				$id = '.slider-post.post-' . $post_id;
				$rules = array(
					$id => array(
						array(	'prop' => 'background-color',
								'key' => 'background_color'
						)
					),
					"$id .slide-post-title, $id .slide-post-title a" => array(
						array(	'prop' => 'color',
								'key' => 'title_font_color'
						),
						array(	'prop' => 'font-size',
								'key' => array('title_font_size', 'title_font_size_unit')
						),
						array(	'prop' => 'font-family',
								'key' => 'title_font_family'
						),
					),
					"$id .slide-excerpt" => array(
						array(	'prop' => 'color',
								'key' => 'text_font_color'
						)
					),
					"$id a" => array(
						array(	'prop' => 'color',
								'key' => 'link_font_color'
						)
					),
				);
				break;
		}
		foreach ( $rules as $selector => $property ) {
			foreach ( $property as $val ) {
				$prop = $val['prop'];
				$key = $val['key'];
				if ( is_array( $key ) ) {
					if ( 'fullcover' == themify_get( $key[0] ) ) {
						continue;
					}
					if ( $prop == 'font-size' && themify_check( $key[0] ) ){
						$css[$selector][$prop] = $prop .': '. themify_get( $key[0] ) . themify_get($key[1]);
					}
					if ( $prop == 'background-position' && themify_check( $key[0] ) ) {
						$css[$selector][$prop] = $prop .': '. themify_get($key[0]) . ' ' . themify_get($key[1]);
					}
				} elseif ( themify_check( $key ) && 'default' != themify_get( $key ) ) {
					if ( 'fullcover' == themify_get( $key ) ) {
						continue;
					}
					if ( $prop == 'color' || stripos($prop, 'color')) {
						$css[$selector][$prop] = $prop .': #'.themify_get( $key );
					}
					elseif ( $prop == 'background-image' ) {
						$css[$selector][$prop] = $prop .': url('.themify_get($key).')';
					}
					elseif ( $prop == 'font-family' ) {
						$font = themify_get( $key );
						$css[$selector][$prop] = $prop .': '. $font;
						if ( ! in_array( $font, themify_get_web_safe_font_list( true ) ) ) {
							$themify->google_fonts .= str_replace(' ', '+', $font.'|');
						}
					}
					else {
						$css[$selector][$prop] = $prop .': '. themify_get( $key );
					}
				}
				if ( isset( $val['important'] ) && $val['important'] && isset( $css[$selector] ) && isset( $css[$selector][$prop] ) ) {
					$css[$selector][$prop] .= ' !important';
				}
			}
			if ( ! empty( $css[$selector] ) ) {
				$style .= "$selector {\n\t" . implode( ";\n\t", $css[$selector] ) . "\n}\n";
			}
		}

		if ( '' != $style ) {
			echo "\n<!-- $post_type-$post_id Style -->\n<style>\n$style</style>\n<!-- End $post_type-$post_id Style -->\n";
		}
	}
}

if ( ! function_exists( 'themify_theme_enqueue_google_fonts' ) ) {
	/**
	 * Enqueue Google Fonts
	 * @since 1.0.0
	 */
	function themify_theme_enqueue_google_fonts() {
		global $themify;
		if ( ! isset( $themify->google_fonts ) || '' == $themify->google_fonts ) return;
		$themify->google_fonts = substr( $themify->google_fonts, 0, -1 );
		wp_enqueue_style( 'entry-styling-google-fonts', themify_https_esc( 'http://fonts.googleapis.com/css' ). '?family='.$themify->google_fonts );
	}
	add_action( 'wp_footer', 'themify_theme_enqueue_google_fonts' );
}

if ( ! function_exists( 'themify_theme_comment' ) ) {
	/**
	 * Custom Theme Comment
	 * @param object $comment Current comment.
	 * @param array $args Parameters for comment reply link.
	 * @param int $depth Maximum comment nesting depth.
	 */
	function themify_theme_comment($comment, $args, $depth) {
	   $GLOBALS['comment'] = $comment; ?>

		<li id="comment-<?php comment_ID() ?>">
			<p class="comment-author">
				<?php printf('%s <cite>%s</cite>', get_avatar($comment,$size='48'), get_comment_author_link()); ?>
				<small class="comment-time">
					<?php comment_date( apply_filters('themify_comment_date', '') ); ?> @
					<?php comment_time( apply_filters('themify_comment_time', '') ); ?>
					<?php edit_comment_link( __('Edit', 'themify'),' [',']'); ?>
				</small>
			</p>
			<div class="commententry">
				<?php if ($comment->comment_approved == '0') : ?>
					<p><em><?php _e('Your comment is awaiting moderation.', 'themify') ?></em></p>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<p class="reply">
				<?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'depth' => $depth, 'reply_text' => __( '<i class="fa fa-mail-reply icon-mail-reply"></i>', 'themify' ), 'max_depth' => $args['max_depth']))) ?>
			</p>
		<?php
	}
}

/*************************************************************
 * Product Lightbox
 *************************************************************/

/**
 * Template redirect function
 **/
function themify_do_theme_redirect( $url ) {
	global $post, $wp_query;

	if ( have_posts() ) {
		include( $url );
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
	if ( is_single() && isset( $_GET['post_in_lightbox'] ) && 1 == $_GET['post_in_lightbox'] ) {

		// remove admin bar inside iframe
		add_filter( 'show_admin_bar', '__return_false' );

		$templatefilename = 'single-lightbox.php';

		$return_template = locate_template( $templatefilename );

		themify_do_theme_redirect($return_template);
	}
}
add_action( 'template_redirect', 'themify_single_post_lightbox', 10 );

/**
 * Register any extra string for translation with external plugin
 * @param $strings
 * @return array
 */
function themify_theme_register_strings( $strings ) {
	$strings[] = 'setting-store_info_address';
	return $strings;
}
add_filter( 'themify_wpml_registered_strings', 'themify_theme_register_strings' );

if( ! function_exists( 'themify_theme_is_mobile' ) ) :
	function themify_theme_is_mobile() {
        if ( function_exists( 'themify_is_touch' ) ) {
            $isPhone = themify_is_touch( 'phone' );
        } else {
            if ( ! class_exists( 'Themify_Mobile_Detect' ) ) {
                require_once THEMIFY_DIR . '/class-themify-mobile-detect.php';
            }
            $detect = new Themify_Mobile_Detect;
            $isPhone = $detect->isMobile() && !$detect->isTablet();
        }
        return $isPhone;
	}
endif;

/**
 * List of layouts supported by the theme to display WooCommerce products
 *
 * @return array
 * @since 1.5.2
 */
function themify_theme_woocommerce_post_layouts( $arr ) {
	return array( 'list-post', 'grid2', 'grid3', 'grid4' );
}
add_filter( 'builder_woocommerce_theme_layouts', 'themify_theme_woocommerce_post_layouts' );

/**
 * Announcement Bar compatibility
 *
 * @since 1.5.9
 */
function themify_theme_announcement_bar_script_vars( $vars ) {
	$vars['margin_top_to_bar_height'] = '#headerwrap';
	
	return $vars;
}
add_filter( 'announcement_bar_script_vars', 'themify_theme_announcement_bar_script_vars' );
