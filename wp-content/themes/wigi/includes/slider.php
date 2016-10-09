<?php
/** Check if slider is enabled */
if('' == themify_get('setting-feature_box_enabled') || 'on' == themify_get('setting-feature_box_enabled')) { ?>
	<?php 
	if(themify_get('setting-feature_box_posts_category') != ""){
		$cat = "cat=".themify_get('setting-feature_box_posts_category');	
	} else {
		$cat = "";
	}
	if(themify_get('setting-feature_box_posts_slides') != ""){
		$num_posts = "showposts=".themify_get('setting-feature_box_posts_slides')."&";
	} else {
		$num_posts = "showposts=5&";	
	}
	query_posts($num_posts.$cat); ?>
	<?php if ( have_posts() || 'images' == themify_get('setting-feature_box_display') ) : ?>

		<?php themify_slider_before(); //hook ?>
		<div id="slider">
        	<?php themify_slider_start(); //hook ?>
			<div id="slider-inner">

				<ul class="slides clearfix">
				<?php if(themify_get('setting-feature_box_display') == "images"){ ?>
					<?php
					$options = array('one','two','three','four','five','six','seven','eight','nine','ten'); 
					foreach($options as $option){
						$option = 'setting-feature_box_images_'.$option;
						if(themify_get($option."_image") != ""){
							echo '<li>';
						
							$title = function_exists( 'icl_t' )? icl_t('Themify', $option.'_title', themify_get($option.'_title')) : ( themify_check($option.'_title') ? themify_get($option.'_title') : '' );
							$image = themify_get($option."_image");
							$alt = $title? $title : $image;
							
							if(themify_get($option."_link") != ""){ 
								$link = themify_get($option."_link");
								$title_attr = $title? "title='$title'" : "title='$image'";

								echo "<a href='$link' $title_attr>" . themify_get_image("src=$image&w=940&h=420&setting=image_feature&class=feature-img&alt=$alt") . '</a>';
								
								echo $title? '<div class="details"><h3 class="feature-post-title"><a href="'.$link.'" title="'.$title_attr.'">'.$title.'</a></h3></div>' : '';
								
							} else {
								themify_image("src=$image&w=940&h=420&setting=image_feature&class=feature-img");
								
								echo $title? '<div class="details"><h3 class="feature-post-title">'.$title.'</h3></div>' : '';
							}
							echo '</li>';
						}
					}
					?>
				<?php } else { ?>
					<?php while (have_posts()) : the_post(); ?>
						<li>
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php themify_image("setting=image_feature&w=630&h=350&class=feature-img"); ?>
							</a>
							<div class="details">
								<h3 class="feature-post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
								<p class="feature-post-date"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></p>
								<?php the_excerpt(); ?>
							</div>
						</li>
					<?php endwhile; ?>
				<?php } ?>
				</ul>

			</div>
			<!--/slider-inner -->
            <?php themify_slider_end(); //hook ?>
		</div>
		<!--/slider -->
        <?php themify_slider_after(); //hook ?>
	
	<?php else : ?>
	
	<?php endif; ?>
	<?php wp_reset_query(); ?>
	
<?php } ?>