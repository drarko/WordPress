<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<div id="upperwrap">
	<div id="upperwrap-inner">
		<div class="pagewidth">
		
		<?php 
                    /////////////////////////////////////////////
                    // Category Title	 							
                    /////////////////////////////////////////////
                ?>
                    <h1 class="page-title"><?php single_cat_title(); ?></h1>
                    <?php echo $themify->get_category_description(); ?>
		

		</div>
		<!-- /.pagewidth -->
	</div>
	<!-- /#upperwrap-inner -->
</div>
<!-- /#upperwrap -->

<div id="layout" class="pagewidth clearfix">
	
	<?php themify_content_before(); //hook ?>
	<div id="content" class="clearfix">
    	<?php themify_content_start(); //hook ?>
		
		

		<?php 
		/////////////////////////////////////////////
		// Loop	 							
		/////////////////////////////////////////////
		?>
		<?php if (have_posts()) : ?>
		
			<!-- loops-wrapper -->
			<div class="<?php echo $themify->allow_sorting == 'yes' ? 'sortable' : '' ; ?> loops-wrapper <?php echo $themify->layout . ' ' . $themify->post_layout; ?>">
				<?php while (have_posts()) : the_post(); ?>
                                    <?php get_template_part( 'includes/loop','index'); ?>
				<?php endwhile; ?>
								
				<?php get_template_part( 'includes/pagination'); ?>
				
			</div>
			<!-- /loops-wrapper -->
		
		<?php 
		/////////////////////////////////////////////
		// Error - No Page Found	 							
		/////////////////////////////////////////////
		?>

		<?php else : ?>
	
			<p><?php _e( 'Sorry, nothing found.', 'themify' ); ?></p>
	
		<?php endif; ?>			

		<?php themify_content_end(); //hook ?>
	</div>
	<!-- /#content -->
    <?php themify_content_after() //hook; ?>
	
	<?php 
	/////////////////////////////////////////////
	// Sidebar							
	/////////////////////////////////////////////
	if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>
	
</div>
<!-- /#layout -->

<?php get_footer(); ?>