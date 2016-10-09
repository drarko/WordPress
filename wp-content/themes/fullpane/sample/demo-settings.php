<?php
$widgets = get_option( "widget_themify-feature-posts" );
$widgets[1002] = array (
  'title' => 'Latest Posts',
  'category' => '3',
  'show_count' => '5',
  'show_date' => NULL,
  'show_thumb' => 'on',
  'display' => 'none',
  'hide_title' => NULL,
  'thumb_width' => '75',
  'thumb_height' => '60',
  'excerpt_length' => '55',
);
update_option( "widget_themify-feature-posts", $widgets );

$widgets = get_option( "widget_themify-flickr" );
$widgets[1003] = array (
  'title' => 'Photo Stream',
  'username' => '52839779@N02',
  'show_count' => '8',
  'show_link' => NULL,
);
update_option( "widget_themify-flickr", $widgets );

$widgets = get_option( "widget_themify-twitter" );
$widgets[1004] = array (
  'title' => 'Twitter Widget',
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
$widgets[1005] = array (
  'title' => '',
  'show_link_name' => NULL,
  'open_new_window' => NULL,
  'thumb_width' => '',
  'thumb_height' => '',
);
update_option( "widget_themify-social-links", $widgets );

$widgets = get_option( "widget_themify-twitter" );
$widgets[1006] = array (
  'title' => 'Latest Tweets',
  'username' => 'themify',
  'show_count' => '3',
  'hide_timestamp' => NULL,
  'show_follow' => NULL,
  'follow_text' => '→ Follow me',
  'include_retweets' => 'on',
  'exclude_replies' => NULL,
);
update_option( "widget_themify-twitter", $widgets );

$widgets = get_option( "widget_themify-feature-posts" );
$widgets[1007] = array (
  'title' => 'Recent Posts',
  'category' => '0',
  'show_count' => '3',
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

$widgets = get_option( "widget_text" );
$widgets[1008] = array (
  'title' => 'About',
  'text' => '<h4>Purchase this <a href="https://themify.me">theme</a> now.</h4>

Follow us → [team-social label="Twitter" link="http://twitter.com/themify" icon="twitter"] [team-social label="Facebook" link="http://facebook.com/themify" icon="facebook"]

Responsive and retina-ready right out of the box, Fullpane also includes the easy-to-use Themify Builder, allowing you to build the page of your dreams without having to touch the code.',
  'filter' => true,
);
update_option( "widget_text", $widgets );



$sidebars_widgets = array (
  'sidebar-main' => 
  array (
    0 => 'themify-feature-posts-1002',
    1 => 'themify-flickr-1003',
    2 => 'themify-twitter-1004',
  ),
  'social-widget' => 
  array (
    0 => 'themify-social-links-1005',
  ),
  'footer-widget-1' => 
  array (
    0 => 'themify-twitter-1006',
  ),
  'footer-widget-2' => 
  array (
    0 => 'themify-feature-posts-1007',
  ),
  'footer-widget-3' => 
  array (
    0 => 'text-1008',
  ),
); 
update_option( "sidebars_widgets", $sidebars_widgets );

$menu_locations = array();
$menu = get_terms( "nav_menu", array( "slug" => "demo-2-menu" ) );
if( is_array( $menu ) && ! empty( $menu ) ) $menu_locations["main-nav"] = $menu[0]->term_id;
set_theme_mod( "nav_menu_locations", $menu_locations );


$homepage = get_posts( array( 'name' => 'home', 'post_type' => 'page' ) );
			if( is_array( $homepage ) && ! empty( $homepage ) ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage[0]->ID );
			}
			
	ob_start(); ?>a:135:{s:15:"setting-favicon";s:0:"";s:23:"setting-custom_feed_url";s:0:"";s:19:"setting-header_html";s:0:"";s:19:"setting-footer_html";s:0:"";s:23:"setting-search_settings";s:0:"";s:16:"setting-page_404";s:1:"0";s:21:"setting-feed_settings";s:0:"";s:21:"setting-webfonts_list";s:11:"recommended";s:24:"setting-webfonts_subsets";s:0:"";s:22:"setting-default_layout";s:8:"sidebar1";s:27:"setting-default_post_layout";s:9:"list-post";s:30:"setting-default_layout_display";s:7:"content";s:25:"setting-default_more_text";s:4:"More";s:21:"setting-index_orderby";s:4:"date";s:19:"setting-index_order";s:4:"DESC";s:26:"setting-default_post_title";s:0:"";s:33:"setting-default_unlink_post_title";s:0:"";s:25:"setting-default_post_meta";s:0:"";s:32:"setting-default_post_meta_author";s:0:"";s:34:"setting-default_post_meta_category";s:0:"";s:33:"setting-default_post_meta_comment";s:0:"";s:29:"setting-default_post_meta_tag";s:0:"";s:25:"setting-default_post_date";s:0:"";s:26:"setting-default_post_image";s:0:"";s:33:"setting-default_unlink_post_image";s:0:"";s:31:"setting-image_post_feature_size";s:5:"blank";s:24:"setting-image_post_width";s:0:"";s:25:"setting-image_post_height";s:0:"";s:32:"setting-default_page_post_layout";s:8:"sidebar1";s:31:"setting-default_page_post_title";s:0:"";s:38:"setting-default_page_unlink_post_title";s:0:"";s:30:"setting-default_page_post_meta";s:0:"";s:37:"setting-default_page_post_meta_author";s:0:"";s:39:"setting-default_page_post_meta_category";s:0:"";s:38:"setting-default_page_post_meta_comment";s:0:"";s:34:"setting-default_page_post_meta_tag";s:0:"";s:30:"setting-default_page_post_date";s:0:"";s:31:"setting-default_page_post_image";s:0:"";s:38:"setting-default_page_unlink_post_image";s:0:"";s:38:"setting-image_post_single_feature_size";s:5:"blank";s:31:"setting-image_post_single_width";s:0:"";s:32:"setting-image_post_single_height";s:0:"";s:27:"setting-default_page_layout";s:8:"sidebar1";s:23:"setting-hide_page_title";s:0:"";s:23:"setting-hide_page_image";s:0:"";s:38:"setting-default_portfolio_index_layout";s:12:"sidebar-none";s:43:"setting-default_portfolio_index_post_layout";s:5:"grid4";s:39:"setting-default_portfolio_index_display";s:4:"none";s:37:"setting-default_portfolio_index_title";s:0:"";s:49:"setting-default_portfolio_index_unlink_post_title";s:0:"";s:50:"setting-default_portfolio_index_post_meta_category";s:3:"yes";s:41:"setting-default_portfolio_index_post_date";s:3:"yes";s:48:"setting-default_portfolio_index_image_post_width";s:0:"";s:49:"setting-default_portfolio_index_image_post_height";s:0:"";s:39:"setting-default_portfolio_single_layout";s:12:"sidebar-none";s:38:"setting-default_portfolio_single_title";s:0:"";s:50:"setting-default_portfolio_single_unlink_post_title";s:0:"";s:51:"setting-default_portfolio_single_post_meta_category";s:3:"yes";s:42:"setting-default_portfolio_single_post_date";s:0:"";s:49:"setting-default_portfolio_single_image_post_width";s:0:"";s:50:"setting-default_portfolio_single_image_post_height";s:0:"";s:22:"themify_portfolio_slug";s:7:"project";s:34:"setting-default_team_single_layout";s:12:"sidebar-none";s:38:"setting-default_team_single_hide_title";s:0:"";s:40:"setting-default_team_single_unlink_title";s:0:"";s:38:"setting-default_team_single_hide_image";s:0:"";s:40:"setting-default_team_single_unlink_image";s:0:"";s:44:"setting-default_team_single_image_post_width";s:0:"";s:45:"setting-default_team_single_image_post_height";s:0:"";s:17:"themify_team_slug";s:4:"team";s:24:"setting-gallery_lightbox";s:8:"lightbox";s:33:"setting-portfolio_slider_autoplay";s:3:"off";s:31:"setting-portfolio_slider_effect";s:6:"scroll";s:41:"setting-portfolio_slider_transition_speed";s:3:"500";s:32:"setting-portfolio_slider_visible";s:1:"1";s:31:"setting-portfolio_slider_scroll";s:1:"1";s:25:"setting-page_loader_color";s:0:"";s:24:"setting-page_loader_icon";s:0:"";s:25:"setting-menu_bar_position";s:14:"menubar-bottom";s:22:"setting-footer_widgets";s:17:"footerwidget-3col";s:24:"setting-footer_text_left";s:0:"";s:25:"setting-footer_text_right";s:0:"";s:27:"setting-global_feature_size";s:5:"large";s:22:"setting-link_icon_type";s:9:"font-icon";s:32:"setting-link_type_themify-link-0";s:10:"image-icon";s:33:"setting-link_title_themify-link-0";s:7:"Twitter";s:32:"setting-link_link_themify-link-0";s:26:"http://twitter.com/themify";s:31:"setting-link_img_themify-link-0";s:85:"https://themify.me/demo/themes/fullpane/wp-content/themes/fullpane/images/twitter.png";s:32:"setting-link_type_themify-link-1";s:10:"image-icon";s:33:"setting-link_title_themify-link-1";s:8:"Facebook";s:32:"setting-link_link_themify-link-1";s:27:"http://facebook.com/themify";s:31:"setting-link_img_themify-link-1";s:86:"https://themify.me/demo/themes/fullpane/wp-content/themes/fullpane/images/facebook.png";s:32:"setting-link_type_themify-link-2";s:10:"image-icon";s:33:"setting-link_title_themify-link-2";s:7:"Google+";s:32:"setting-link_link_themify-link-2";s:0:"";s:31:"setting-link_img_themify-link-2";s:89:"https://themify.me/demo/themes/fullpane/wp-content/themes/fullpane/images/google-plus.png";s:32:"setting-link_type_themify-link-3";s:10:"image-icon";s:33:"setting-link_title_themify-link-3";s:7:"YouTube";s:32:"setting-link_link_themify-link-3";s:0:"";s:31:"setting-link_img_themify-link-3";s:85:"https://themify.me/demo/themes/fullpane/wp-content/themes/fullpane/images/youtube.png";s:32:"setting-link_type_themify-link-4";s:10:"image-icon";s:33:"setting-link_title_themify-link-4";s:9:"Pinterest";s:32:"setting-link_link_themify-link-4";s:0:"";s:31:"setting-link_img_themify-link-4";s:87:"https://themify.me/demo/themes/fullpane/wp-content/themes/fullpane/images/pinterest.png";s:32:"setting-link_type_themify-link-6";s:9:"font-icon";s:33:"setting-link_title_themify-link-6";s:7:"Twitter";s:32:"setting-link_link_themify-link-6";s:26:"http://twitter.com/themify";s:33:"setting-link_ficon_themify-link-6";s:10:"fa-twitter";s:35:"setting-link_ficolor_themify-link-6";s:0:"";s:37:"setting-link_fibgcolor_themify-link-6";s:0:"";s:32:"setting-link_type_themify-link-7";s:9:"font-icon";s:33:"setting-link_title_themify-link-7";s:8:"Facebook";s:32:"setting-link_link_themify-link-7";s:27:"http://facebook.com/themify";s:33:"setting-link_ficon_themify-link-7";s:11:"fa-facebook";s:35:"setting-link_ficolor_themify-link-7";s:0:"";s:37:"setting-link_fibgcolor_themify-link-7";s:0:"";s:22:"setting-link_field_ids";s:239:"{"themify-link-0":"themify-link-0","themify-link-1":"themify-link-1","themify-link-2":"themify-link-2","themify-link-3":"themify-link-3","themify-link-4":"themify-link-4","themify-link-6":"themify-link-6","themify-link-7":"themify-link-7"}";s:23:"setting-link_field_hash";s:1:"8";s:30:"setting-page_builder_is_active";s:6:"enable";s:26:"setting-page_builder_cache";s:2:"on";s:45:"setting-page_builder_animation_mobile_exclude";s:2:"on";s:23:"setting-hooks_field_ids";s:2:"[]";s:27:"setting-custom_panel-editor";s:7:"default";s:27:"setting-custom_panel-author";s:7:"default";s:32:"setting-custom_panel-contributor";s:7:"default";s:25:"setting-customizer-editor";s:7:"default";s:25:"setting-customizer-author";s:7:"default";s:30:"setting-customizer-contributor";s:7:"default";s:22:"setting-backend-editor";s:7:"default";s:22:"setting-backend-author";s:7:"default";s:27:"setting-backend-contributor";s:7:"default";s:23:"setting-frontend-editor";s:7:"default";s:23:"setting-frontend-author";s:7:"default";s:28:"setting-frontend-contributor";s:7:"default";s:4:"skin";s:91:"https://themify.me/demo/themes/fullpane/wp-content/themes/fullpane/themify/img/non-skin.gif";}<?php $themify_data = ob_get_clean();
	themify_set_data( unserialize( $themify_data ) );
	?>