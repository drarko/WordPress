<?php
/** Themify Default Variables
 @var object */
	global $themify; ?>
	
<?php if (have_posts()) : ?>

	<div id="sliderwrap">
	
		<div id="slider-inner">
			
			<?php themify_slider_before(); //hook ?>
			<div id="slider" class="slider">
				<?php themify_slider_start(); //hook ?>
					
				<div class="slides-wrapper">
	
					<ul class="slides clearfix">
		
						<?php
						
						while (have_posts()) {
							the_post();
						?>
							<?php if(!is_single()) : global $more; $more = 0; endif; //enable more link ?>
			
							<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<?php if($themify->hide_image != "yes"): ?>
									<figure class='slide-feature-image'>
										<?php if($themify->unlink_image == "yes"):  ?>		
											<?php themify_image($themify->auto_featured_image."setting=image_post&w=".$themify->width."&h=".$themify->height); ?>
										<?php else: ?>
											<a href="<?php echo themify_get_featured_image_link(); ?>" title="<?php the_title_attribute(); ?>">
												<?php themify_image($themify->auto_featured_image."setting=image_post&w=".$themify->width."&h=".$themify->height); ?>
										<?php endif; ?>
											</a>
									</figure>
								<?php endif; ?>
								<!-- /.slide-feature-image -->

								<div class="slide-content-wrap">
									<div class="slide-content">
			
										<?php if($themify->hide_title != "yes"): ?>
											<?php if($themify->unlink_title == "yes"): ?>
												<h3 class="slide-post-title"><?php the_title(); ?></h3>
											<?php else: ?>
												<h3 class="slide-post-title"><a href="<?php echo themify_get_featured_image_link(); ?>"><?php the_title(); ?></a></h3>
											<?php endif; //unlink post title ?> 
										<?php endif; //post title ?>    
			
										<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>
									
											<div class="slide-excerpt">
												<?php the_excerpt(); ?>
											</div>
									
										<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>
									
										<?php else: ?>
										
											<?php the_content( themify_check('setting-default_more_text') ? themify_get('setting-default_more_text') : __('More &rarr;','themify')); ?>
										
										<?php endif; //display content ?>
										
									</div>
								</div>
								<!-- /.slide-content-wrap -->
							</li>

						<?php } ?>				
		
					</ul>
					
					<div id="fullscreen-button"></div><!-- /fullscreen-button -->
	
				</div>
				<!-- /slides-wrapper -->
		
				<?php themify_slider_end(); //hook ?>
			</div>
			<!--/#slider --> 
	        <?php themify_slider_after(); //hook ?>

			<?php get_template_part( 'includes/pagination'); ?>

		</div>
		<!-- /#slider-inner -->
		
	</div>
	<!-- /#sliderwrap -->
	
<?php else : ?>

	<p><?php _e( 'Sorry, nothing found.', 'themify' ); ?></p>

<?php endif; ?>