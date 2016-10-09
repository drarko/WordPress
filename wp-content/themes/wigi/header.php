<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php
/** Themify Default Variables
 @var object */
global $themify; ?>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<!-- wp_header -->
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php themify_body_start(); //hook ?>
<div id="bg">

	<div id="topbar">
		<div class="pagewidth">
			<div class="topbar-nav" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
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
			</div><!--/topbar-nav -->

			<div class="topbar-widget">
				<?php dynamic_sidebar('social-widget'); ?>

				<?php if(!themify_check('setting-exclude_rss')): ?>
					<div class="rss"><a href="<?php if(themify_get('setting-custom_feed_url') != ""){ echo themify_get('setting-custom_feed_url'); } else { bloginfo('rss2_url'); } ?>">RSS</a></div>
				<?php endif ?>

				<?php if(!themify_check('setting-exclude_search_form')): ?>
					<?php get_search_form(); ?>
				<?php endif ?>
			</div><!--/topbar-widget -->
		</div>
		<!--/pagewidth -->
	</div>
	<!--/topbar -->
	<div id="pagewrap" class="pagewidth hfeed site">
		<div id="headerwrap">
		<?php themify_header_before(); //hook ?>
			<div id="header" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
			<?php themify_header_start(); //hook ?>

				<?php echo themify_logo_image('site_logo'); ?>

				<?php if ( $site_desc = get_bloginfo( 'description' ) ) : ?>
					<?php global $themify_customizer; ?>
					<div id="site-description" class="site-description"><?php echo class_exists( 'Themify_Customizer' ) ? $themify_customizer->site_description( $site_desc ) : $site_desc; ?></div>
				<?php endif; ?>

				<div class="header-ad">
					<?php dynamic_sidebar('header-widget'); ?>
				</div>
				<!--/header-ad -->
			  <?php themify_header_end(); //hook ?>
			</div>
			<!--/header -->
		   <?php themify_header_after(); //hook ?>
		   </div><!--/headerwrap -->

		<div id="body" class="clearfix">
	   <?php themify_layout_before(); //hook ?>
