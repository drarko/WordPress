<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<!-- layout-container -->
<div id="layout" class="clearfix">

	<?php themify_content_before(); //hook ?>
	<!-- content -->
	<div id="content" class="clearfix">
    	<?php themify_content_start(); //hook ?>
		
		<?php 
		/////////////////////////////////////////////
		// Category Title	 							
		/////////////////////////////////////////////
		?>
		
                <h1 class="page-title"><?php single_cat_title(); ?></h1>
                <?php echo $themify->get_category_description(); ?>
		<?php 
		/////////////////////////////////////////////
		// Loop	 							
		/////////////////////////////////////////////
		?>	
		<?php if (have_posts()) : ?>
			<!-- masonry container -->
			<div id="loops-wrapper" class="loops-wrapper <?php if($themify->post_layout=="grid2" || $themify->post_layout=="grid3") { echo 'masonry-container'; } ?> <?php echo $themify->layout . ' ' . $themify->post_layout; ?>">

                            <?php while (have_posts()) : the_post(); ?>
                                    <?php get_template_part( 'includes/loop','index'); ?>
                            <?php endwhile; ?>			
			</div>
			<!--/masonry container -->	

			<?php get_template_part( 'includes/pagination'); ?>
		
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
	<!--/content -->
    <?php themify_content_after() //hook; ?>
	<?php 
	/////////////////////////////////////////////
	// Sidebar							
	/////////////////////////////////////////////
	if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>
	
</div>
<!-- layout-container -->

<?php get_footer(); ?>