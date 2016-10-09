<?php
/** Themify Default Variables
 @var object */
	global $themify; ?>

<?php if (have_posts()) : ?>
		
	<!-- loops-wrapper -->
	<div id="loops-wrapper" class="loops-wrapper <?php echo $themify->post_layout; ?>">
			
		<?php
		
		while (have_posts()) {
			the_post();
		?>
		
			<?php if(is_search()): ?>
				<?php get_template_part( 'includes/loop' , 'search'); ?>
			<?php else: ?>
				<?php get_template_part( 'includes/loop' , 'index'); ?>
			<?php endif; ?>
		
		<?php } ?>				
				
	</div>
	<!-- /loops-wrapper -->
	
	<?php get_template_part( 'includes/pagination'); ?>

<?php else : ?>

	<p><?php _e( 'Sorry, nothing found.', 'themify' ); ?></p>

<?php endif; ?>