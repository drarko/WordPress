<?php
/** Themify Default Variables
 @var object */
	global $themify; ?>

	</div><!--/pagewidth -->
    <?php themify_layout_after(); //hook ?>
</div><!--/body -->

<div id="footerwrap">

	<?php themify_footer_before(); //hook ?>
	<div id="footer" class="pagewidth clearfix" itemscope="itemscope" itemtype="https://schema.org/WPFooter">
    	<?php themify_footer_start(); //hook ?>

			<?php get_template_part( 'includes/footer-widgets'); ?>
		
			<p class="back-top"><a href="#header">Back to top</a></p>
		
			<div class="footer-text-wrap clearfix">
				<?php if (function_exists('wp_nav_menu')) {
					wp_nav_menu(array('theme_location' => 'footer-nav' , 'fallback_cb' => '' , 'container'  => '' , 'menu_id' => 'footer-nav' , 'menu_class' => 'footer-nav')); 
				} ?>

				<div class="footer-text clearfix">
					<?php themify_the_footer_text(); ?>
					<?php themify_the_footer_text('right'); ?>
				</div>
				<!-- /footer-text --> 
			</div>
			<!--/footer-text wrap --> 
            
			<?php themify_footer_end(); //hook ?>
	</div>
	<!--/footer --> 
	<?php themify_footer_after(); //hook ?>
    
</div>
<!--/footerwrap -->

</div>
<!--/pagewrap -->

<?php
/**
 *  Stylesheets and Javascript files are enqueued in theme-functions.php
 */
?>

<?php themify_body_end(); // hook ?>
<!-- wp_footer -->
<?php wp_footer(); ?>

</body>
</html>
