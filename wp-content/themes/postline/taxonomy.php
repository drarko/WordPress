<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<!-- layout -->
<div id="layout" class="pagewidth clearfix">

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
		if( 'timeline' == $themify->post_layout || isset($_POST['ajax_load']) ){
			$default_timeline_layout = themify_get('setting-default_timeline_query');
			get_template_part('includes/layout-timeline', $default_timeline_layout);
		} else {
			get_template_part('includes/layout', 'general');
		}
		?>
        
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