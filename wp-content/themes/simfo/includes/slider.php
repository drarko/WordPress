<?php
/** Check if slider is enabled */
if('' == themify_get('setting-slider_enabled') || 'on' == themify_get('setting-slider_enabled')) { ?>

<div id="sliderwrap">

	<div id="slider-inner" class="pagewidth">

		<?php themify_slider_before(); //hook ?>
		<div id="slider" class="slider">
        	<?php themify_slider_start(); //hook ?>
				
			<ul class="slides clearfix">
	
			<?php
				// Get image width and height or set default dimensions
				$img_width = themify_check('setting-slider_width')?	themify_get('setting-slider_width'): '990';
				$img_height = themify_check('setting-slider_height')? themify_get('setting-slider_height'): '420';

				if(themify_check('setting-slider_posts_category')){
					$cat = "&cat=".themify_get('setting-slider_posts_category');	
				} else {
					$cat = "";
				}
				if(themify_check('setting-slider_posts_slides')){
					$num_posts = "showposts=".themify_get('setting-slider_posts_slides')."&";
				} else {
					$num_posts = "showposts=5&";	
				}
				if(themify_check('setting-slider_display') && themify_get('setting-slider_display') == "images"){
				
					$options = array('one','two','three','four','five','six','seven','eight','nine','ten');
					foreach($options as $option){
						$option = 'setting-slider_images_'.$option;
						if(themify_check($option.'_image')){
							echo '<li>';
								$title = function_exists( 'icl_t' )? icl_t('Themify', $option.'_title', themify_get($option.'_title')) : ( themify_check($option.'_title') ? themify_get($option.'_title') : '' );
								$image = themify_get($option.'_image');
								$alt = $title? $title : $image;
								
								if(themify_check($option.'_link')){ 
									$link = themify_get($option.'_link');
									$title_attr = $title? "title='$title'" : "title='$image'";
									echo "<div class='slide-feature-image'><a href='$link' $title_attr>" . themify_get_image("src=".$image."&ignore=true&w=$img_width&h=$img_height&alt=$alt&class=feature-img") . '</a></div>';
									echo $title ? '<div class="slide-content-wrap"><div class="slide-content-wrap-inner"><h3 class="slide-post-title"><a href="'.$link.'" '.$title_attr.'>'.$title.'</a></h3></div></div>' : '';
								} else {
									echo '<div class="slide-feature-image">' . themify_get_image("src=".$image."&ignore=true&w=$img_width&h=$img_height&alt=".$image."&class=feature-img&alt=$alt") . '</div>';
									echo $title ? '<div class="slide-content-wrap"><div class="slide-content-wrap-inner"><h3 class="slide-post-title">'.$title.'</h3></div></div>' : '';
								}
							echo '</li>';
						}
					}
				} else {
					
					query_posts($num_posts.$cat); 

					if( have_posts() ) {
						
						while ( have_posts() ) : the_post();
							?>                
	
						<?php $link = themify_get_featured_image_link(); ?>
	
						<li>
							<!-- post-video or post-image -->
							<?php
							//if there is a video url, show it
							if( themify_get("video_url") != '' ){
								global $wp_embed;
								echo $wp_embed->run_shortcode('[embed]' . themify_get('video_url') . '[/embed]');
							}
							else{
								//otherwise try to show the image
								?>
								<div class='slide-feature-image'>
									<a href="<?php echo $link; ?>" title="<?php the_title_attribute(); ?>">
										<?php themify_image("ignore=true&w=$img_width&h=$img_height"); ?>
									</a>
								</div>
							<?php } ?>
							<!-- /post-video or post-image -->

							<div class="slide-content-wrap">
								<div class="slide-content-wrap-inner">
									<?php if(themify_get('setting-slider_hide_title') != 'yes'): ?>
										<h3 class="slide-post-title"><a href="<?php echo $link; ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
									<?php endif; ?>
									
										<?php if(themify_get('setting-slider_default_display') == 'content'): ?>
											<div class="slide-excerpt">
											<?php the_content(); ?>
											</div>
										<?php elseif(themify_get('setting-slider_default_display') == 'none'): ?>
												<?php //none ?>
										<?php else: ?>
											<div class="slide-excerpt">
											<?php the_excerpt(); ?>
											</div>
										<?php endif; ?>
									
								</div>
							</div>
							<!-- /.slide-content-wrap -->
							
						</li>
							<?php 
						endwhile; 
					}
					
					wp_reset_query();
					
				} 
				?>
			</ul>

			<?php themify_slider_end(); //hook ?>
		</div>
		<!-- /#slider -->
        <?php themify_slider_after(); //hook ?>
        
	</div>
	<!-- /#slider-inner -->
	
</div>
<!-- /#sliderwrap -->

<?php } ?>