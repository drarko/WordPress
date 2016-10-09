<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php themify_body_start(); //hook ?>
<div id="pagewrap" class="hfeed site">

	<div id="headerwrap">

		<div id="top-bar">
			<div id="top-bar-inner" class="pagewidth">

				<?php if ( has_nav_menu( 'top-nav' ) ) { ?>
					<nav id="top-nav-wrap">
						<div id="top-menu-icon" class="mobile-button"></div>
						<?php if (function_exists('wp_nav_menu')) {
							wp_nav_menu(array('theme_location' => 'top-nav' , 'fallback_cb' => '' , 'container'  => '' , 'menu_id' => 'top-nav' , 'menu_class' => 'top-nav clearfix'));
						} ?>
						<!-- /#top-nav -->
					</nav>
				<?php } ?>

				<?php if(!themify_check('setting-exclude_search_form')): ?>
					<div id="searchform-wrap">
						<div id="search-icon" class="mobile-button"></div>
						<?php get_search_form(); ?>
					</div>
					<!-- /searchform-wrap -->
				<?php endif ?>

				<?php /* Include shopdock */ ?>
				<?php themify_get_ecommerce_template( 'includes/shopdock' ); ?>

			</div>
			<!-- /#top-bar-inner -->
		</div>
		<!-- /#top-bar -->

		<?php themify_header_before(); //hook ?>
		<header id="header" class="pagewidth" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
        	<?php themify_header_start(); //hook ?>

			<hgroup>
				<?php echo themify_logo_image('site_logo'); ?>

				<?php if ( $site_desc = get_bloginfo( 'description' ) ) : ?>
					<?php global $themify_customizer; ?>
					<div id="site-description" class="site-description"><?php echo class_exists( 'Themify_Customizer' ) ? $themify_customizer->site_description( $site_desc ) : $site_desc; ?></div>
				<?php endif; ?>
			</hgroup>

			<div class="header-widget clearfix">
				<?php dynamic_sidebar('header-widget'); ?>
			</div>
			<!--/header-widget -->

            <?php themify_header_end(); //hook ?>
		</header>
		<!-- /#header -->
        <?php themify_header_after(); //hook ?>

		<div id="nav-bar">
			<div class="pagewidth">

				<nav id="main-nav-wrap" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
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
					<!-- /#main-nav -->
				</nav>

				<div class="social-widget">
					<?php dynamic_sidebar('social-widget'); ?>

					<?php if(!themify_check('setting-exclude_rss')): ?>
						<div class="rss"><a href="<?php if(themify_get('setting-custom_feed_url') != ""){ echo themify_get('setting-custom_feed_url'); } else { bloginfo('rss2_url'); } ?>">RSS</a></div>
					<?php endif ?>
				</div>
				<!-- /.social-widget -->

			</div>
			<!-- /.pagewidth -->
		</div><!-- /#nav-bar -->

	</div>
	<!-- /#headerwrap -->

	<div id="body" class="clearfix">
	<?php themify_layout_before(); //hook ?>
