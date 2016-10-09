<?php
/** Check if slider is enabled */
if('' == themify_get('setting-feature_box_enabled') || 'on' == themify_get('setting-feature_box_enabled')) {
	$num_posts = '';
	$cat = '';
	?>

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
	if(!themify_check("setting-feature_image_width")){
		$width = "946";	
	} else {
		$width = themify_get("setting-feature_image_width");
	}
	if(!themify_check("setting-feature_image_height")){
		$height = "480";	
	} else {
		$height = themify_get("setting-feature_image_height");
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
						if(themify_get($option."_image") != ""){
							echo '<li>';
							
							$title = function_exists( 'icl_t' )? icl_t('Themify', $option.'_title', themify_get($option.'_title')) : ( themify_check($option.'_title') ? themify_get($option.'_title') : '' );
							$image = themify_get($option."_image");
							$alt = $title? $title : $image;
							
							if(themify_get($option."_link") != ""){ 
								$link = themify_get($option."_link");
								$title_attr = $title? "title='$title'" : "title='$image'";
								
								echo "<a href='$link' $title_attr >";
								if($title){
									echo '<h3><span>'.$title.'</span></h3>';
								}
								themify_image("src=".$image."&ignore=true&w=".$width."&h=".$height."&setting=feature_image&class=feature-img&alt=".$alt);
								echo '</a>';
							} else {
								
								if($title){
									echo '<h3><span>'.$title.'</span></h3>';
								}
								themify_image("src=".$image."&ignore=true&w=".$width."&h=".$height."&setting=feature_image&class=feature-img&alt=".$alt);
								
							}
							echo '</li>';
						}
					}
					?>
				<?php } else { ?>
					<?php while (have_posts()) : the_post(); ?>

						<?php $link = themify_get_featured_image_link(); ?>

						<li>
							<a href="<?php echo $link; ?>" title="<?php the_title_attribute(); ?>">	
								<h3><span><?php the_title_attribute(); ?></span></h3>		
								<?php themify_image("ignore=true&w=".$width."&h=".$height."&setting=feature_image"); ?>
							</a>							
						</li>
						
					<?php endwhile; ?>
				<?php } ?>
			</ul>

			<?php if(themify_get('setting-feature_box_display') != "images"){ ?>
				<ul class="slider-nav">
					<?php while (have_posts()) : the_post(); ?>
						<li>
							<a href="#" title="<?php the_title_attribute(); ?>"><?php themify_image("ignore=true&w=40&h=40"); ?></a>							
						</li>
					
					<?php endwhile; ?>
				</ul>
			<?php } ?>

			<?php themify_slider_end(); //hook ?>
		</div>
		<!--/slider -->
        <?php themify_slider_after(); //hook ?>
	
	<?php else : ?>
	
	<?php endif; ?>
	<?php wp_reset_query(); ?>
	
<?php } ?>