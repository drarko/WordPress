<?php
/**
 * Template for home page. Loads index template if a page is set to show latest posts.
 * @package themify
 * @since 1.0.0
 */
?>
<?php if ( is_home() && ! is_front_page() ) : ?>
	<?php get_template_part( 'index', 'blog' ); ?>
<?php endif; ?>

<?php get_header(); ?>
	
	<div id="upperwrap">
			
		<?php if(is_front_page() && !is_paged()){ get_template_part( 'includes/welcome-message'); } ?>
				
		<?php if(is_front_page() && !is_paged()){ get_template_part( 'includes/slider'); } ?>
	
	</div>
	<!-- /#upperwrap -->
	
	<?php if(is_front_page() && !is_paged()){ get_template_part( 'includes/home-highlights'); } ?>

	<?php if(is_front_page() && !is_paged()){ get_template_part( 'includes/action-text'); } ?>
	    
	<div class="pagewidth">

		<?php if( is_front_page() ) get_template_part( 'includes/home-widgets'); ?>

	</div>
	<!--/pagewidth --> 

<?php get_footer(); ?>