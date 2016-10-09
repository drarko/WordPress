<?php
/** Check if slider is enabled */
if('' == themify_get('setting-footer_slider_enabled') || 'on' == themify_get('setting-footer_slider_enabled')) { ?>

    <?php themify_footer_slider_before(); //hook ?>
	<div id="footer-slider" class="slider">
    	<?php themify_footer_slider_start(); //hook ?>

		<?php if(get_cat_name(themify_get('setting-footer_slider_posts_category')) != ""){ ?>
			<h3 class="slider-title"><?php echo get_cat_name(themify_get('setting-footer_slider_posts_category')); ?></h3>
		<?php } else { ?>
			<h3 class="slider-title"><?php _e( 'Featured', 'themify' ); ?></h3>
		<?php } ?>
		 <?php
                    $slider_args = array();
                    $slider_args['wrapvar'] = 'yes' == themify_get( 'setting-footer_slider_wrap' );
                    $slider_args['visible'] = themify_get( 'setting-footer_slider_visible' );
                    if (!$slider_args['visible']) {
                        $slider_args['visible'] = 4;
                    }
                    $slider_args['speed'] = themify_get( 'setting-footer_slider_speed' );
                    if (!$slider_args['speed']) {
                         $slider_args['speed'] = '.5';
                    }
                    $slider_args['scroll'] = themify_get( 'setting-footer_slider_scroll' );
                    if(!$slider_args['scroll']){
                        $slider_args['scroll'] = 1;
                    }
                    $slider_args['auto'] = themify_get( 'setting-footer_slider_auto' );
                    if (!$slider_args['auto']) {
                        $slider_args['auto'] = 0;
                    }
                    $slider_args['play'] = '0' != $slider_args['auto'];
                    $slider_args['slider_nav'] = 1;
                    $slider_args['width'] = '100%';
                    $slider_args['item_width'] = 150;
                    $slider_args['numsldr'] = $slider_args['custom_numsldr'] = 'footer-slider';
					$slider_args = apply_filters( 'themify_theme_footer_slider_args', $slider_args );
                ?>
		<ul class="slides clearfix" data-slider="<?php esc_attr_e( base64_encode( json_encode( $slider_args ) ) ); ?>">
    		<?php
    		// Get image width and height or set default dimensions
			$img_width = themify_check('setting-footer_slider_width')?	themify_get('setting-footer_slider_width'): '170';
			$img_height = themify_check('setting-footer_slider_height')? themify_get('setting-footer_slider_height'): '120';

			if(themify_get('setting-footer_slider_posts_category') != "" ){
				$cat = "&cat=".themify_get('setting-footer_slider_posts_category');
			} else {
				$cat = "";
			}
			if(themify_get('setting-footer_slider_posts_slides') != "" ){
				$num_posts = "showposts=".themify_get('setting-footer_slider_posts_slides')."&";
			} else {
				$num_posts = "showposts=7&";
			}
			if(themify_get('setting-footer_slider_display') == "images"){

				$options = array('one','two','three','four','five','six','seven','eight','nine','ten');
				foreach($options as $option){
					$option = 'setting-footer_slider_images_'.$option;
					if(themify_get($option.'_image') != ""){
						echo '<li>';

							$title = function_exists( 'icl_t' )? icl_t('Themify', $option.'_title', themify_get($option.'_title')) : ( themify_check($option.'_title') ? themify_get($option.'_title') : '' );
							$image = themify_get($option.'_image');
							$alt = $title? $title : $image;

							if(themify_get($option.'_link') != ""){
								$link = themify_get($option.'_link');
								$title_attr = $title? "title='$title'" : "title='$image'";
								echo "<a href='$link' $title_attr>" . themify_get_image("src=".$image."&ignore=true&w=$img_width&h=$img_height&alt=$alt&class=feature-img") . '</a>';
								echo $title? '<h3 class="feature-post-title"><a href="'.$link.'" '.$title_attr.' >'.$title.'</a></h3>' : '';
							} else {
								themify_image("src=".$image."&ignore=true&w=$img_width&h=$img_height&alt=".$alt."&class=feature-img");
								echo $title? '<h3 class="feature-post-title">'.$title.'</h3>' : '';
							}
						echo '</li>';
					}
				}
			} else {

				query_posts($num_posts.$cat);

				if( have_posts() ) {

					while ( have_posts() ) : the_post();
						?>
                    	<li>
                       		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                       			<?php $auto_featured_image = themify_check('setting-auto_featured_image')? '': 'field_name=feature_image, post_image, image, wp_thumb&';
							themify_image($auto_featured_image."ignore=true&w=$img_width&h=$img_height&class=feature-img"); ?>
                       		</a>
                        	<h3 class="feature-post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                 		</li>
               			<?php
					endwhile;
				}

				wp_reset_query();

			}
			?>
		</ul>

		<?php themify_footer_slider_end(); //hook ?>
	</div>
	<!--/slider -->
    <?php themify_footer_slider_after(); //hook ?>

<?php } ?>
