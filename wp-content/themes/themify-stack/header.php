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

		<div id="headerwrap">

			<a id="menu-icon" href="#"></a>

			<div id="mobile-menu" class="sidemenu sidemenu-off">

				<?php themify_header_before(); // hook ?>

				<header id="header" class="pagewidth clearfix" itemscope="itemscope" itemtype="https://schema.org/WPHeader">

					<div class="header-padding">

						<?php themify_header_start(); // hook ?>

						<?php echo themify_logo_image(); ?>
						<?php if ( $site_desc = get_bloginfo( 'description' ) ) : ?>
							<?php global $themify_customizer; ?>
							<div id="site-description" class="site-description"><?php echo class_exists( 'Themify_Customizer' ) ? $themify_customizer->site_description( $site_desc ) : $site_desc; ?></div>
						<?php endif; ?>

						<div class="social-widget">
							<?php dynamic_sidebar('social-widget'); ?>

							<?php if ( ! themify_check( 'setting-exclude_rss' ) ) : ?>
								<div class="rss"><a href="<?php echo esc_url( themify_get( 'setting-custom_feed_url' ) != '' ? themify_get( 'setting-custom_feed_url' ) : get_bloginfo( 'rss2_url' ) ); ?>" class="hs-rss-link"><i class="fa fa-rss"></i></a></div>
							<?php endif ?>
						</div>
						<!-- /.social-widget -->

						<div id="searchform-wrap">
							<?php if(!themify_check('setting-exclude_search_form')): ?>
								<?php get_search_form(); ?>
							<?php endif ?>
						</div>
						<!-- /searchform-wrap -->

					</div>
					<!-- /header-padding -->

					<nav itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
						<?php themify_theme_menu_nav(); ?>
						<!-- /#main-nav -->
					</nav>

					<?php if ( is_active_sidebar( 'header-widget' ) ) : ?>
						<div class="header-widget header-padding">
							<?php dynamic_sidebar('header-widget'); ?>
						</div>
						<!-- /.header-widget -->
					<?php endif; ?>

					<?php themify_header_end(); // hook ?>

				</header>
				<!-- /#header -->

				<?php themify_header_after(); // hook ?>

			</div>
			<!-- /mobile-menu-panel -->

		</div>
		<!-- /#headerwrap -->

		<div id="body" class="clearfix">

			<?php themify_layout_before(); //hook ?>
