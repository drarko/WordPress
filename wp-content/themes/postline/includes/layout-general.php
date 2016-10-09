<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php if (have_posts()) : ?>
		
			<!-- loops-wrapper -->
			<div id="loops-wrapper" class="loops-wrapper infinite-scrolling <?php echo $themify->layout . ' ' . $themify->post_layout; ?>">
					
				<?php
				
				while (have_posts()) {
					the_post();
				?>
				
                <?php themify_post_before(); //hook ?>
				<article id="post-<?php the_id(); ?>" <?php post_class( 'post clearfix' ); ?>>
                	<?php themify_post_start(); //hook ?>
                
					<div class="post-inner clearfix">
					<?php if(is_search()): ?>
						<?php get_template_part( 'includes/loop' , 'search'); ?>
					<?php else: ?>
						<?php get_template_part( 'includes/loop' , 'index'); ?>
					<?php endif; ?>
					</div>
					<!-- /.post-inner -->
                    
                    <?php themify_post_end(); //hook ?>
				</article>
				<!-- /.post -->
                <?php themify_post_after(); //hook ?>
				
				<?php } ?>				
						
			</div>
			<!-- /loops-wrapper -->
			
			<?php get_template_part( 'includes/pagination'); ?>
	
		<?php else : ?>
	
			<p><?php _e( 'Sorry, nothing found.', 'themify' ); ?></p>
	
		<?php endif; ?>