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

	<div id="headerwrap">

		<?php themify_header_before(); //hook ?>
		<header id="header" class="pagewidth" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
        	<?php themify_header_start(); //hook ?>
			<hgroup>
				<?php echo themify_logo_image('site_logo'); ?>
			</hgroup>

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
			</nav>
			<!-- /#main-nav -->

			<div id="social-wrap">
				<div id="searchform-wrap">
					<div id="search-icon" class="mobile-button"></div>

					<?php if(!themify_check('setting-exclude_search_form')): ?>
						<?php get_search_form(); ?>
					<?php endif ?>
				</div>
				<!-- /searchform-wrap -->

				<div class="social-widget">
					<?php dynamic_sidebar('social-widget'); ?>

					<?php if(!themify_check('setting-exclude_rss')): ?>
						<div class="rss"><a href="<?php if(themify_get('setting-custom_feed_url') != ""){ echo themify_get('setting-custom_feed_url'); } else { bloginfo('rss2_url'); } ?>">RSS</a></div>
					<?php endif ?>
				</div>
				<!-- /.social-widget -->
			</div>
		<?php themify_header_end(); //hook ?>
		</header>
		<!-- /#header -->
        <?php themify_header_after(); //hook ?>

	</div>
	<!-- /#headerwrap -->

	<div id="body" class="clearfix">
	<?php themify_layout_before(); //hook ?>
