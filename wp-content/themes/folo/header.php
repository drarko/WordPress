<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php
/** Themify Default Variables
 @var object */
global $themify; ?>

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

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

			<div class="logo-wrap-outer">
				<div class="logo-wrap">
					<div class="logo-wrap-inner">
						<?php echo themify_logo_image('site_logo'); ?>

						<?php if ( $site_desc = get_bloginfo( 'description' ) ) : ?>
							<?php global $themify_customizer; ?>
							<div id="site-description" class="site-description"><?php echo class_exists( 'Themify_Customizer' ) ? $themify_customizer->site_description( $site_desc ) : $site_desc; ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="social-widget">
				<?php dynamic_sidebar('social-widget'); ?>
				<?php if(!themify_check('setting-exclude_rss')): ?>
					<div class="rss"><a href="<?php if(themify_get('setting-custom_feed_url') != ""){ echo themify_get('setting-custom_feed_url'); } else { bloginfo('rss2_url'); } ?>">RSS</a></div>
				<?php endif ?>

			</div>
			<!-- /.social widget -->

			<nav itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
				<?php
				if ( function_exists( 'themify_custom_menu_nav' ) ) {
					themify_custom_menu_nav( array(
						'theme_location' => 'main-nav-left',
						'menu_id'        => 'main-nav-left',
						'menu_class'     => 'main-nav'
					));
				} else {
					wp_nav_menu( array(
						'theme_location' => 'main-nav-left',
						'fallback_cb'    => 'themify_default_main_nav',
						'container'      => '',
						'menu_id'        => 'main-nav-left',
						'menu_class'     => 'main-nav'
					));
				}
				?>
				<!-- /.main-nav -->

				<?php
				if ( function_exists( 'themify_custom_menu_nav' ) ) {
					themify_custom_menu_nav( array(
						'theme_location' => 'main-nav-right',
						'menu_id'        => 'main-nav-right',
						'menu_class'     => 'main-nav'
					));
				} else {
					wp_nav_menu( array(
						'theme_location' => 'main-nav-right',
						'fallback_cb'    => 'themify_default_main_nav',
						'container'      => '',
						'menu_id'        => 'main-nav-right',
						'menu_class'     => 'main-nav'
					));
				}
				?>
			</nav>

			<?php if(!themify_check('setting-exclude_search_form')): ?>
				<?php get_search_form(); ?>
			<?php endif ?>

			<?php themify_header_end(); //hook ?>
		</header>
		<!-- /#header -->
        <?php themify_header_after(); //hook ?>

	</div>
	<!-- /#headerwrap -->

	<div id="body" class="clearfix">
    <?php themify_layout_before(); //hook ?>
