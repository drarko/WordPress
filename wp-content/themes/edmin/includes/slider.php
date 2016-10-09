<?php
/** Check if slider is enabled */
if('' == themify_get('setting-header_slider_enabled') || 'on' == themify_get('setting-header_slider_enabled')) { ?>

	<?php themify_slider_before(); //hook ?>
	<div id="header-slider" class="slider">
    	<?php themify_slider_start(); //hook ?>
		<?php
                    $slider_args = array();
                    $slider_args['wrapvar'] = 'yes' == themify_get( 'setting-header_slider_wrap' );
                    $slider_args['visible'] = themify_get( 'setting-header_slider_visible' );
                    if (!$slider_args['visible']) {
                        $slider_args['visible'] = 4;
                    }
                    $slider_args['speed'] = themify_get( 'setting-header_slider_speed' );

                    if (!$slider_args['speed']) {
                         $slider_args['speed'] = '.5';
                    }
                    $slider_args['scroll'] = themify_get( 'setting-header_slider_scroll' );
                    if(!$slider_args['scroll']){
                        $slider_args['scroll'] = 1;
                    }
                    $slider_args['auto'] = themify_get( 'setting-slider_auto' );
                    if (!$slider_args['auto']) {
                        $slider_args['auto'] = 0;
                    }
                    $slider_args['play'] = '0' != $slider_args['auto'];
                    $slider_args['slider_nav'] = 1;
                    $slider_args['width'] = '100%';
                    $slider_args['item_width'] = 150;
                    $slider_args['numsldr'] = $slider_args['custom_numsldr'] = 'header-slider';
                ?>
		<ul class="slides clearfix" data-slider="<?php esc_attr_e( base64_encode( json_encode( $slider_args ) ) ); ?>">

    		<?php
    		// Get image width and height or set default dimensions
			$img_width = themify_check('setting-header_slider_width')?	themify_get('setting-header_slider_width'): '260';
			$img_height = themify_check('setting-header_slider_height')? themify_get('setting-header_slider_height'): '190';

			if(themify_check('setting-header_slider_posts_category')){
				$cat = "&cat=".themify_get('setting-header_slider_posts_category');
			} else {
				$cat = "";
			}
			if(themify_check('setting-header_slider_posts_slides')){
				$num_posts = "showposts=".themify_get('setting-header_slider_posts_slides')."&";
			} else {
				$num_posts = "showposts=7&";
			}
			if(themify_check('setting-header_slider_display') && themify_get('setting-header_slider_display') == "images"){

				$options = array('one','two','three','four','five','six','seven','eight','nine','ten');
				foreach($options as $option){
					$option = 'setting-header_slider_images_'.$option;
					if(themify_check($option.'_image')){
						echo '<li>';

							$title = function_exists( 'icl_t' )? icl_t('Themify', $option.'_title', themify_get($option.'_title')) : ( themify_check($option.'_title') ? themify_get($option.'_title') : '' );
							$image = themify_get($option.'_image');
							$alt = $title? $title : $image;

							if(themify_check($option.'_link')){
								$link = themify_get($option.'_link');
								$title_attr = $title? "title='$title'" : '';

								echo "<a href='$link' $title_attr>" . themify_get_image("src=$image&ignore=true&w=$img_width&h=$img_height&alt=$link&class=feature-img") . '</a>';
								echo $title? '<div class="slide-content-wrap"><h3 class="slide-post-title"><a href="'.$link.'" '.$title_attr.' >'.$title.'</a></h3></div>' : '';
							} else {
								themify_image("src=$image&ignore=true&w=$img_width&h=$img_height&alt=$alt&class=feature-img");
								echo $title? '<div class="slide-content-wrap"><h3 class="slide-post-title">'.$title.'</h3></div>' : '';
							}
						echo '</li>';
					}
				}
			} else {


				query_posts($num_posts.$cat);

				if( have_posts() ) {

					while ( have_posts() ) : the_post(); ?>

						<?php $link = themify_get_featured_image_link(); ?>

                    	<li>
                       		<div class="slide-feature-image">
								<a href="<?php echo $link; ?>" title="<?php the_title_attribute(); ?>">
									<?php themify_image("class=feature-img&ignore=true&w=$img_width&h=$img_height"); ?>
								</a>
							</div>
						<!-- /slide-feature-image -->

                       		<div class="slide-content-wrap">

							<?php if(themify_get('setting-header_slider_hide_title') != 'yes'): ?>
								<h3 class="slide-post-title"><a href="<?php echo $link; ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
							<?php endif; ?>

							<?php if(themify_get('setting-header_slider_default_display') == 'content'): ?>
								<div class="slide-excerpt">
								<?php the_content(); ?>
								</div>
							<?php elseif(themify_get('setting-header_slider_default_display') == 'none'): ?>
									<?php //none ?>
							<?php else: ?>
								<div class="slide-excerpt">
								<?php the_excerpt(); ?>
								</div>
							<?php endif; ?>

						</div>
						<!-- /slide-content-wrap -->

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
	<!--/slider -->
    <?php themify_slider_after(); //hook ?>

<?php } ?>
