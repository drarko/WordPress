<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">

<?php
/**
 * Document title is generated in theme-functions.php
 * Stylesheets and Javascript files are enqueued in theme-functions.php
 */
?>

<!-- wp_header -->
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<?php themify_body_start(); // hook ?>

	<div id="pagewrap" class="hfeed site">

		<?php if ( 'yes' != $themify->hide_header ) : ?>

			<div id="headerwrap">

				<?php themify_header_before(); // hook ?>

				<header id="header" class="pagewidth clearfix" itemscope="itemscope" itemtype="https://schema.org/WPHeader">

					<?php themify_header_start(); // hook ?>

					<div class="logo-wrap">
						<?php if ( themify_theme_show_area( 'site_logo' ) ) : ?>
							<?php echo themify_logo_image(); ?>
						<?php endif; ?>

						<?php if ( themify_theme_show_area( 'site_tagline' ) ) : ?>
							<?php echo themify_site_description(); ?>
						<?php endif; ?>
					</div>

					<?php if ( themify_theme_do_not_exclude_all( 'mobile-menu' ) ) : ?>
						<a id="menu-icon" href="#mobile-menu"></a>

						<div id="mobile-menu" class="sidemenu sidemenu-off">

							<?php if ( themify_theme_show_area( 'search_form' ) ) : ?>
								<div id="searchform-wrap">
									<?php get_search_form(); ?>
								</div>
							<?php endif; // exclude search form ?>

							<?php if ( themify_theme_show_area( 'social_widget' ) ) : ?>
								<div class="social-widget">
									<?php dynamic_sidebar('social-widget'); ?>

									<?php if ( themify_theme_show_area( 'rss' ) ) : ?>
										<div class="rss"><a href="<?php themify_theme_feed_link(); ?>"></a></div>
									<?php endif; // exclude rss ?>
								</div>
								<!-- /.social-widget -->
							<?php endif; // exclude social widget ?>

							<?php if ( themify_theme_show_area( 'menu_navigation' ) ) : ?>
								<nav id="main-nav-wrap" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
									<?php themify_theme_menu_nav(); ?>
									<!-- /#main-nav -->
								</nav>
							<?php endif; // exclude menu navigation ?>

							<a id="menu-icon-close" href="#mobile-menu"></a>

						</div>
						<!-- /#mobile-menu -->
					<?php endif; // do not exclude all this ?>

					<?php themify_header_end(); // hook ?>

				</header>
				<!-- /#header -->

				<?php themify_header_after(); // hook ?>

			</div>
			<!-- /#headerwrap -->

		<?php endif; // exclude header ?>

		<div id="body" class="clearfix">

			<?php themify_layout_before(); //hook ?>
