	<?php themify_layout_after(); //hook ?>
	</div>
	<!--/body --> 

	<div id="footerwrap">
    
    	<?php themify_footer_before(); //hook ?>
		<div id="footer" class="pagewidth clearfix" itemscope="itemscope" itemtype="https://schema.org/WPFooter">
        	<?php themify_footer_start(); //hook ?>
	
			<?php get_template_part( 'includes/footer-widgets'); ?>
			
			<?php if (function_exists('wp_nav_menu')) {
				wp_nav_menu(array('theme_location' => 'footer-nav' , 'fallback_cb' => '' , 'container'  => '' , 'menu_id' => 'footer-nav' , 'menu_class' => 'footer-nav')); 
			} ?>
	
    		<?php themify_footer_end(); //hook ?>
		</div>
		<!--/footer -->
        <?php themify_footer_after(); //hook ?>
         
	</div>
	<!--/footerwrap -->

	<div class="footer-text clearfix">
		<?php themify_the_footer_text(); ?>
	</div>
	<!--/footer-text --> 

</div>
<!--/inner pagewrap -->
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