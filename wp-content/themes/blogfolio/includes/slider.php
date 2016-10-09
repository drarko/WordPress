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
            
			<ul class="slides">
			<?php if(themify_get('setting-feature_box_display') == "images"){ ?>
				<?php
				$options = array('one','two','three','four','five','six','seven','eight','nine','ten');
				foreach($options as $option){
					$option = 'setting-feature_box_images_'.$option;
					if(themify_get($option."_image") != ''){
						echo '<li>';
						
						$title = function_exists( 'icl_t' )? icl_t('Themify', $option.'_title', themify_get($option.'_title')) : ( themify_check($option.'_title') ? themify_get($option.'_title') : '' );
						$image = themify_get($option."_image");
						$alt = $title? $title : $image;
						
						if(themify_get($option."_link") != '') {
							$link = themify_get($option."_link");
							$title_attr = $title? "title='$title'" : "title='$image'";
							
							echo "<a href='$link' $title_attr >" . themify_get_image("src=".$image."&w=954&h=420&ignore=true&setting=image_feature&class=feature-img&alt=".$alt) . '</a>';
							echo $title? '<div class="details"><h3><a href="'.$link.'" '.$title_attr.' >'.$title.'</a></h3></div>': '';
						} else {
							themify_image("src=".$image."&w=954&h=420&ignore=true&setting=image_feature&alt=$alt&class=feature-img");
							echo $title? '<div class="details"><h3>'.$title.'</h3></div>': '';
						}
						echo '</li>';
					}
				}
				?>
			<?php } else { ?>
				<?php while (have_posts()) : the_post(); ?>

						<?php $link = themify_get_featured_image_link(); ?>

						<li>
							<div class="details">
								<h3><?php the_title(); ?></h3>
								<?php the_excerpt(); ?>
							</div>
								
							<div class="slide-feature-image">
								<a href="<?php echo $link; ?>" title="<?php the_title_attribute(); ?>">
									<?php themify_image("w=954&h=420&ignore=true&setting=image_feature"); ?>
								</a>	
							</div>
							<!-- /.slide-feature-image -->
								
						</li>
					
				<?php endwhile; ?>
			<?php } ?>
			</ul>
            <?php themify_slider_end(); //hook ?>
		</div>
		<!--/slider -->
		<?php themify_slider_after(); //hook ?>

	<?php else : ?>
	
	<?php endif; ?>
	<?php wp_reset_query(); ?>
	
<?php } ?>
