<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php
/** Themify Default Variables
 @var object */
global $themify; ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">

<!-- wp_header -->
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php themify_body_start(); //hook ?>
<div id="pagewrap" class="hfeed site">

	<div id="topbar">
		<div class="pagewidth">
			<div class="topbar-nav">

			<?php
			if (function_exists('wp_nav_menu')) {
				wp_nav_menu(array('theme_location' => 'secondary-nav' , 'fallback_cb' => 'default_secondary_nav' , 'container'  => '' , 'menu_id' => 'secondary-nav'));
			}
			else {
				themify_default_main_nav();
			}
			?>

			</div><!--/topbar-nav -->

			<div class="social-widget">
				<?php dynamic_sidebar('social-widget'); ?>

				<?php if(!themify_check('setting-exclude_rss')): ?>
					<div class="rss"><a href="<?php if(themify_get('setting-custom_feed_url') != ""){ echo themify_get('setting-custom_feed_url'); } else { echo bloginfo('rss2_url'); } ?>">RSS</a></div>
				<?php endif ?>

			</div><!--/topbar-widget -->

		</div>
	</div>
	<!--/topbar -->
	<div class="pagewidth">
    	<div id="headerwrap">
        <?php themify_header_before(); //hook ?>
		<div id="header" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
        	<?php themify_header_start(); //hook ?>

			<?php echo themify_logo_image('site_logo'); ?>


			<?php if ( $site_desc = get_bloginfo( 'description' ) ) : ?>
				<?php global $themify_customizer; ?>
				<div id="site-description" class="site-description"><?php echo class_exists( 'Themify_Customizer' ) ? $themify_customizer->site_description( $site_desc ) : $site_desc; ?></div>
			<?php endif; ?><!-- /#site-description -->

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

			<?php if(!themify_check('setting-exclude_search_form')): ?>
				<?php get_search_form(); ?>
			<?php endif ?>

			<div class="header-ad">
				<?php dynamic_sidebar('header-widget'); ?>
			</div>
			<!-- /header-ad -->

			<div id="nav-bar"></div><!-- /nav bar -->

			<?php themify_header_end(); //hook ?>
		</div><!-- /header -->
        <?php themify_header_after(); //hook ?>
        </div><!-- /headerwrap -->

		<div id="body">
        <?php themify_layout_before(); //hook ?>
