<?php
/** Themify Default Variables
 * @var object */
global $themify; ?>

<div id="sidebar-wrap">
	<?php themify_sidebar_before(); //hook ?>
	<div id="sidebar" class="clearfix">
    	<?php themify_sidebar_start(); //hook ?>

		<?php themify_header_before(); //hook ?>
		<div id="headerwrap" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
			<?php themify_header_start(); //hook ?>

			<?php echo themify_logo_image('site_logo'); ?>
			<!-- /#site-logo -->

			<?php if ( $site_desc = get_bloginfo( 'description' ) ) : ?>
				<?php global $themify_customizer; ?>
				<div id="site-description" class="site-description"><?php echo class_exists( 'Themify_Customizer' ) ? $themify_customizer->site_description( $site_desc ) : $site_desc; ?></div>
			<?php endif; ?>
			<!-- /#site-description -->

			<div id="main-nav-wrap" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
				<?php
				if (function_exists('wp_nav_menu')) {
					wp_nav_menu(array('theme_location' => 'main-nav' , 'fallback_cb' => 'themify_default_main_nav' , 'container'  => '' , 'menu_id' => 'main-nav' , 'menu_class' => 'clearfix'));
				}
				else {
					themify_default_main_nav();
				}
				?>

				<?php if(!themify_check('setting-exclude_rss')): ?>
					<div class="rss"><a href="<?php if(themify_get('setting-custom_feed_url') != ""){ echo themify_get('setting-custom_feed_url'); } else { echo bloginfo('rss2_url'); } ?>">RSS</a></div>
				<?php endif ?>

			</div>

			<div class="social-widget">
				<?php dynamic_sidebar('social-widget'); ?>
			</div>
			<!-- /.social-widget -->

		    <?php themify_header_end(); //hook ?>
		</div><!-- /headerwrap -->
		<?php themify_header_after(); //hook ?>

		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-main') ); ?>

		<div class="clearfix">
			<div class="secondary">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-main-2a') ); ?>
			</div>
			<div class="secondary last">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-main-2b') ); ?>
			</div>
		</div>

		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-main-3') ); ?>

		<?php themify_footer_before(); //hook ?>
		<div id="footer-text" class="footer-text" itemscope="itemscope" itemtype="https://schema.org/WPFooter">
        	<?php themify_footer_start(); //hook ?>

			<?php themify_the_footer_text(); ?>

			<?php themify_footer_end(); //hook ?>
		</div>
		<!-- /#footer-text -->
		<?php themify_footer_after(); //hook ?>

        <?php themify_sidebar_end(); //hook ?>
	</div>
	<!--/sidebar -->
    <?php themify_sidebar_after(); //hook ?>

</div>
<!--/sidebar-wrap -->
