<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php
/** Themify Default Variables
 @var object */
global $themify; ?>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<!-- media-queries.js -->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->

<!-- wp_header -->
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php themify_body_start(); //hook ?>
<div id="pagewrap" class="hfeed site">

	<div id="headerwrap">

    	<?php themify_header_before(); //hook ?>
        <div id="header" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
        	<?php themify_header_start(); //hook ?>

                <?php echo themify_logo_image('site_logo'); ?>

				<?php if ( $site_desc = get_bloginfo( 'description' ) ) : ?>
					<?php global $themify_customizer; ?>
					<div id="site-description" class="site-description"><?php echo class_exists( 'Themify_Customizer' ) ? $themify_customizer->site_description( $site_desc ) : $site_desc; ?></div>
				<?php endif; ?>


            <div class="header-widget">

                <?php dynamic_sidebar('social-widget'); ?>

                <?php if(!themify_check('setting-exclude_rss')): ?>
                    <div class="rss"><a href="<?php if(themify_get('setting-custom_feed_url') != ""){ echo themify_get('setting-custom_feed_url'); } else { echo bloginfo('rss2_url'); } ?>">RSS</a></div>
                <?php endif ?>

            </div>
            <!--/header widget -->

            <div class="header-banner">
                <?php dynamic_sidebar('header-widget'); ?>
            </div>
            <!--/header widget -->

            <div id="main-nav-wrap" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
                <div id="menu-icon" class="mobile-button"></div>
				<?php
				if ( function_exists( 'themify_custom_menu_nav' ) ) {
					themify_custom_menu_nav();
				} else {
					wp_nav_menu( array(
						'theme_location' => 'main-nav',
						'fallback_cb'    => 'themify_default_main_nav',
						'container'      => '',
						'menu_id'        => 'main-nav',
						'menu_class'     => 'main-nav'
					));
				}
				?>
                <!--/main-nav -->
			</div>
            <!-- /#main-nav-wrap -->

		<?php if(!themify_check('setting-exclude_search_form')): ?>
			<div id="searchform-wrap">
			<div id="search-icon" class="mobile-button"></div>
				<?php get_search_form(); ?>
			</div>
			<!-- /#searchform-wrap -->
		<?php endif ?>

		<?php themify_header_end(); //hook ?>
        </div>
        <!--/header -->
        <?php themify_header_after(); //hook ?>

	</div>
    <!-- /#headerwrap -->

	<div id="body" class="clearfix">
    <?php themify_layout_before(); //hook ?>
