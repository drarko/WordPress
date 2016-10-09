<?php
$widgets = get_option( "widget_themify-feature-posts" );
$widgets[1002] = array (
  'title' => 'Featured Posts',
  'category' => '0',
  'show_count' => '2',
  'show_date' => 'on',
  'show_thumb' => 'on',
  'display' => 'none',
  'hide_title' => NULL,
  'thumb_width' => '50',
  'thumb_height' => '50',
  'excerpt_length' => '55',
  'orderby' => 'date',
  'order' => 'DESC',
);
update_option( "widget_themify-feature-posts", $widgets );

$widgets = get_option( "widget_themify-flickr" );
$widgets[1003] = array (
  'title' => 'Flickr',
  'username' => '52839779@N02',
  'show_count' => '15',
  'show_link' => NULL,
);
update_option( "widget_themify-flickr", $widgets );

$widgets = get_option( "widget_text" );
$widgets[1004] = array (
  'title' => 'About',
  'text' => 'Welcome to <a href="#">Magazine</a> website, elit egestas rutrum vehicula, neque dolor fringilla lacus, ut rhoncus turpis augue vitae libero. Vestibulum adipiscing rutrum nulla, vitae interdum urna posuere in. ',
  'filter' => false,
);
update_option( "widget_text", $widgets );

$widgets = get_option( "widget_text" );
$widgets[1005] = array (
  'title' => '',
  'text' => '<a href="https://themify.me"><img src="https://themify.me/demo/themes/magazine/files/2014/10/ad-300x250.jpg" alt="sample image" /></a>',
  'filter' => false,
);
update_option( "widget_text", $widgets );

$widgets = get_option( "widget_text" );
$widgets[1006] = array (
  'title' => '',
  'text' => '<a href="https://themify.me"><img src="https://themify.me/demo/themes/magazine/files/2014/10/ad-skyscapper.jpg" alt="ad" /></a>',
  'filter' => false,
);
update_option( "widget_text", $widgets );

$widgets = get_option( "widget_categories" );
$widgets[1007] = array (
  'title' => 'Categories',
  'count' => 1,
  'hierarchical' => 1,
  'dropdown' => 0,
);
update_option( "widget_categories", $widgets );

$widgets = get_option( "widget_themify-feature-posts" );
$widgets[1008] = array (
  'title' => 'Featured',
  'category' => '5',
  'show_count' => '3',
  'show_date' => NULL,
  'show_thumb' => 'on',
  'display' => 'none',
  'hide_title' => NULL,
  'thumb_width' => '160',
  'thumb_height' => '100',
  'excerpt_length' => '55',
  'orderby' => 'date',
  'order' => 'DESC',
);
update_option( "widget_themify-feature-posts", $widgets );

$widgets = get_option( "widget_themify-twitter" );
$widgets[1009] = array (
  'title' => 'Latest Tweets',
  'username' => 'themify',
  'show_count' => '3',
  'hide_timestamp' => NULL,
  'show_follow' => NULL,
  'follow_text' => '→ Follow me',
  'include_retweets' => NULL,
  'exclude_replies' => NULL,
);
update_option( "widget_themify-twitter", $widgets );

$widgets = get_option( "widget_themify-social-links" );
$widgets[1010] = array (
  'title' => '',
  'show_link_name' => NULL,
  'open_new_window' => NULL,
  'icon_size' => 'icon-medium',
  'orientation' => 'horizontal',
);
update_option( "widget_themify-social-links", $widgets );

$widgets = get_option( "widget_text" );
$widgets[1011] = array (
  'title' => '',
  'text' => '<a href="https://themify.me"><img src="https://themify.me/demo/themes/magazine/files/2014/10/ad-leaderboard.jpg" alt="sample image" /></a>',
  'filter' => false,
);
update_option( "widget_text", $widgets );

$widgets = get_option( "widget_text" );
$widgets[1012] = array (
  'title' => '',
  'text' => '<a href="https://themify.me"><img src="https://themify.me/demo/themes/magazine/files/2014/10/ad-250x250.jpg" alt="ad" /></a>',
  'filter' => false,
);
update_option( "widget_text", $widgets );

$widgets = get_option( "widget_themify-social-links" );
$widgets[1013] = array (
  'title' => '',
  'show_link_name' => NULL,
  'open_new_window' => NULL,
  'icon_size' => 'icon-large',
  'orientation' => 'horizontal',
);
update_option( "widget_themify-social-links", $widgets );



$sidebars_widgets = array (
  'wp_inactive_widgets' => 
  array (
    0 => 'themify-feature-posts-1002',
    1 => 'themify-flickr-1003',
    2 => 'text-1004',
  ),
  'sidebar-main' => 
  array (
    0 => 'text-1005',
  ),
  'sidebar-alt' => 
  array (
    0 => 'text-1006',
  ),
  'sidebar-main-2a' => 
  array (
    0 => 'categories-1007',
  ),
  'sidebar-main-2b' => 
  array (
    0 => 'themify-feature-posts-1008',
  ),
  'sidebar-main-3' => 
  array (
    0 => 'themify-twitter-1009',
  ),
  'social-widget' => 
  array (
    0 => 'themify-social-links-1010',
  ),
  'header-widget' => 
  array (
    0 => 'text-1011',
  ),
  'after-content-widget' => 
  array (
    0 => 'text-1012',
  ),
  'footer-social-widget' => 
  array (
    0 => 'themify-social-links-1013',
  ),
); 
update_option( "sidebars_widgets", $sidebars_widgets );

$menu_locations = array();
$menu = get_terms( "nav_menu", array( "slug" => "top-menu" ) );
if( is_array( $menu ) && ! empty( $menu ) ) $menu_locations["top-nav"] = $menu[0]->term_id;
$menu = get_terms( "nav_menu", array( "slug" => "main" ) );
if( is_array( $menu ) && ! empty( $menu ) ) $menu_locations["main-nav"] = $menu[0]->term_id;
$menu = get_terms( "nav_menu", array( "slug" => "footer-nav" ) );
if( is_array( $menu ) && ! empty( $menu ) ) $menu_locations["footer-nav"] = $menu[0]->term_id;
set_theme_mod( "nav_menu_locations", $menu_locations );


$homepage = get_posts( array( 'name' => 'home', 'post_type' => 'page' ) );
			if( is_array( $homepage ) && ! empty( $homepage ) ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage[0]->ID );
			}
			
	ob_start(); ?>a:137:{s:15:"setting-favicon";s:0:"";s:23:"setting-custom_feed_url";s:0:"";s:19:"setting-header_html";s:0:"";s:19:"setting-footer_html";s:0:"";s:23:"setting-search_settings";s:0:"";s:16:"setting-page_404";s:1:"0";s:21:"setting-feed_settings";s:0:"";s:21:"setting-webfonts_list";s:11:"recommended";s:24:"setting-webfonts_subsets";s:0:"";s:22:"setting-default_layout";s:8:"sidebar2";s:27:"setting-default_post_layout";s:5:"grid2";s:30:"setting-default_layout_display";s:7:"content";s:25:"setting-default_more_text";s:4:"More";s:21:"setting-index_orderby";s:4:"date";s:19:"setting-index_order";s:4:"DESC";s:26:"setting-default_post_title";s:0:"";s:33:"setting-default_unlink_post_title";s:0:"";s:25:"setting-default_post_meta";s:0:"";s:32:"setting-default_post_meta_author";s:0:"";s:34:"setting-default_post_meta_category";s:0:"";s:33:"setting-default_post_meta_comment";s:0:"";s:29:"setting-default_post_meta_tag";s:0:"";s:25:"setting-default_post_date";s:0:"";s:26:"setting-default_post_image";s:0:"";s:33:"setting-default_unlink_post_image";s:0:"";s:31:"setting-image_post_feature_size";s:5:"blank";s:24:"setting-image_post_width";s:0:"";s:25:"setting-image_post_height";s:0:"";s:32:"setting-default_page_post_layout";s:8:"sidebar2";s:31:"setting-default_page_post_title";s:0:"";s:38:"setting-default_page_unlink_post_title";s:0:"";s:30:"setting-default_page_post_meta";s:0:"";s:37:"setting-default_page_post_meta_author";s:0:"";s:39:"setting-default_page_post_meta_category";s:0:"";s:38:"setting-default_page_post_meta_comment";s:0:"";s:34:"setting-default_page_post_meta_tag";s:0:"";s:30:"setting-default_page_post_date";s:0:"";s:31:"setting-default_page_post_image";s:0:"";s:38:"setting-default_page_unlink_post_image";s:0:"";s:38:"setting-image_post_single_feature_size";s:5:"blank";s:31:"setting-image_post_single_width";s:0:"";s:32:"setting-image_post_single_height";s:0:"";s:27:"setting-default_page_layout";s:8:"sidebar2";s:23:"setting-hide_page_title";s:0:"";s:23:"setting-hide_page_image";s:0:"";s:24:"setting-gallery_lightbox";s:8:"lightbox";s:19:"setting-entries_nav";s:8:"numbered";s:25:"setting-breaking_news_tax";s:8:"category";s:21:"setting-breaking_news";s:13:"breaking-news";s:25:"setting-breaking_news_tag";s:0:"";s:27:"setting-breaking_news_limit";s:0:"";s:37:"setting-breaking_news_slider_autoplay";s:4:"4000";s:35:"setting-breaking_news_slider_effect";s:5:"slide";s:34:"setting-breaking_news_slider_speed";s:3:"500";s:21:"setting-related_posts";s:3:"yes";s:27:"setting-related_posts_limit";s:0:"";s:27:"setting-related_posts_width";s:0:"";s:28:"setting-related_posts_height";s:0:"";s:16:"setting-facebook";s:3:"yes";s:15:"setting-twitter";s:3:"yes";s:18:"setting-googleplus";s:3:"yes";s:17:"setting-pinterest";s:2:"no";s:19:"setting-stumbleupon";s:2:"no";s:16:"setting-linkedin";s:3:"yes";s:22:"setting-footer_widgets";s:17:"footerwidget-3col";s:24:"setting-footer_text_left";s:0:"";s:25:"setting-footer_text_right";s:0:"";s:27:"setting-global_feature_size";s:5:"blank";s:22:"setting-link_icon_type";s:9:"font-icon";s:32:"setting-link_type_themify-link-0";s:10:"image-icon";s:33:"setting-link_title_themify-link-0";s:7:"Twitter";s:32:"setting-link_link_themify-link-0";s:0:"";s:31:"setting-link_img_themify-link-0";s:98:"https://themify.me/demo/themes/magazine2/wp-content/themes/magazine/themify/img/social/twitter.png";s:32:"setting-link_type_themify-link-1";s:10:"image-icon";s:33:"setting-link_title_themify-link-1";s:8:"Facebook";s:32:"setting-link_link_themify-link-1";s:0:"";s:31:"setting-link_img_themify-link-1";s:99:"https://themify.me/demo/themes/magazine2/wp-content/themes/magazine/themify/img/social/facebook.png";s:32:"setting-link_type_themify-link-2";s:10:"image-icon";s:33:"setting-link_title_themify-link-2";s:7:"Google+";s:32:"setting-link_link_themify-link-2";s:0:"";s:31:"setting-link_img_themify-link-2";s:102:"https://themify.me/demo/themes/magazine2/wp-content/themes/magazine/themify/img/social/google-plus.png";s:32:"setting-link_type_themify-link-3";s:10:"image-icon";s:33:"setting-link_title_themify-link-3";s:7:"YouTube";s:32:"setting-link_link_themify-link-3";s:0:"";s:31:"setting-link_img_themify-link-3";s:98:"https://themify.me/demo/themes/magazine2/wp-content/themes/magazine/themify/img/social/youtube.png";s:32:"setting-link_type_themify-link-4";s:10:"image-icon";s:33:"setting-link_title_themify-link-4";s:9:"Pinterest";s:32:"setting-link_link_themify-link-4";s:0:"";s:31:"setting-link_img_themify-link-4";s:100:"https://themify.me/demo/themes/magazine2/wp-content/themes/magazine/themify/img/social/pinterest.png";s:32:"setting-link_type_themify-link-5";s:9:"font-icon";s:33:"setting-link_title_themify-link-5";s:7:"Twitter";s:32:"setting-link_link_themify-link-5";s:26:"http://twitter.com/themify";s:33:"setting-link_ficon_themify-link-5";s:10:"fa-twitter";s:35:"setting-link_ficolor_themify-link-5";s:0:"";s:37:"setting-link_fibgcolor_themify-link-5";s:0:"";s:32:"setting-link_type_themify-link-6";s:9:"font-icon";s:33:"setting-link_title_themify-link-6";s:8:"Facebook";s:32:"setting-link_link_themify-link-6";s:27:"http://facebook.com/themify";s:33:"setting-link_ficon_themify-link-6";s:11:"fa-facebook";s:35:"setting-link_ficolor_themify-link-6";s:0:"";s:37:"setting-link_fibgcolor_themify-link-6";s:0:"";s:32:"setting-link_type_themify-link-7";s:9:"font-icon";s:33:"setting-link_title_themify-link-7";s:7:"Google+";s:32:"setting-link_link_themify-link-7";s:0:"";s:33:"setting-link_ficon_themify-link-7";s:14:"fa-google-plus";s:35:"setting-link_ficolor_themify-link-7";s:0:"";s:37:"setting-link_fibgcolor_themify-link-7";s:0:"";s:32:"setting-link_type_themify-link-8";s:9:"font-icon";s:33:"setting-link_title_themify-link-8";s:7:"YouTube";s:32:"setting-link_link_themify-link-8";s:37:"http://www.youtube.com/user/themifyme";s:33:"setting-link_ficon_themify-link-8";s:10:"fa-youtube";s:35:"setting-link_ficolor_themify-link-8";s:0:"";s:37:"setting-link_fibgcolor_themify-link-8";s:0:"";s:32:"setting-link_type_themify-link-9";s:9:"font-icon";s:33:"setting-link_title_themify-link-9";s:9:"Pinterest";s:32:"setting-link_link_themify-link-9";s:0:"";s:33:"setting-link_ficon_themify-link-9";s:12:"fa-pinterest";s:35:"setting-link_ficolor_themify-link-9";s:0:"";s:37:"setting-link_fibgcolor_themify-link-9";s:0:"";s:22:"setting-link_field_ids";s:341:"{"themify-link-0":"themify-link-0","themify-link-1":"themify-link-1","themify-link-2":"themify-link-2","themify-link-3":"themify-link-3","themify-link-4":"themify-link-4","themify-link-5":"themify-link-5","themify-link-6":"themify-link-6","themify-link-7":"themify-link-7","themify-link-8":"themify-link-8","themify-link-9":"themify-link-9"}";s:23:"setting-link_field_hash";s:2:"10";s:30:"setting-page_builder_is_active";s:6:"enable";s:26:"setting-page_builder_cache";s:2:"on";s:23:"setting-hooks_field_ids";s:2:"[]";s:27:"setting-custom_panel-editor";s:7:"default";s:27:"setting-custom_panel-author";s:7:"default";s:32:"setting-custom_panel-contributor";s:7:"default";s:25:"setting-customizer-editor";s:7:"default";s:25:"setting-customizer-author";s:7:"default";s:30:"setting-customizer-contributor";s:7:"default";s:22:"setting-backend-editor";s:7:"default";s:22:"setting-backend-author";s:7:"default";s:27:"setting-backend-contributor";s:7:"default";s:23:"setting-frontend-editor";s:7:"default";s:23:"setting-frontend-author";s:7:"default";s:28:"setting-frontend-contributor";s:7:"default";s:4:"skin";s:91:"https://themify.me/demo/themes/magazine/wp-content/themes/magazine/themify/img/non-skin.gif";}<?php $themify_data = ob_get_clean();
	themify_set_data( unserialize( $themify_data ) );
	?>