<?php if(!is_single()) { global $more; $more = 0; } //enable more link ?>
<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php themify_post_before(); //hook ?>

<?php
$categories = wp_get_object_terms(get_the_id(), 'portfolio-category');
$class = '';
foreach($categories as $cat){
	$class .= ' cat-'.$cat->term_id;
}
?>

<article id="portfolio-<?php the_id(); ?>" class="post clearfix portfolio-post <?php $themify->extra_classes(); ?>">
	<?php if(!is_singular('portfolio')) echo '<div class="tile-flip-wrapper"><div class="tile-flip"><div class="front side">'; ?>
	<?php if($themify->hide_image != 'yes'): ?>
		<div class="post-image">

			<?php
			$out = '';
			// Check if portfolio has more than one image in gallery

			if(themify_check('_gallery_shortcode')){

				$sc_gallery = preg_replace('#\[gallery(.*)ids="([0-9|,]*)"(.*)\]#i', '$2', themify_get('_gallery_shortcode'));
				$image_ids = explode(',', str_replace(' ', '', $sc_gallery));

				// Check if portfolio has more than one image in gallery
				$gallery_images = get_posts(array(
					'post__in' => $image_ids,
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'numberposts' => -1,
					'orderby' => 'post__in',
					'order' => 'ASC'
				));

				$autoplay = themify_check('setting-portfolio_slider_autoplay')?
								themify_get('setting-portfolio_slider_autoplay'): '4000';

				$effect = themify_check('setting-portfolio_slider_effect')?
						themify_get('setting-portfolio_slider_effect'):	'scroll';

				$speed = themify_check('setting-portfolio_slider_transition_speed')?
						themify_get('setting-portfolio_slider_transition_speed'): '500';

				$navigation = !is_singular('portfolio') ? false : true;
				$out .= '<div id="portfolio-slider-'.get_the_id().'" class="slideshow-wrap"><ul class="slideshow" data-id="portfolio-slider-'.get_the_id().'" data-autoplay="'.$autoplay.'" data-effect="'.$effect.'" data-speed="'.$speed.'" data-navigation="'.$navigation.'">';
				foreach ( $gallery_images as $gallery_image ) {
					$out .= '<li>';
						$out .= '<a href="'.themify_get_featured_image_link().'">';
						$out .= $themify->portfolio_image($gallery_image->ID, $themify->width, $themify->height);
						$out .= '</a>';
						if(is_singular('portfolio')){
							if('' != $img_caption = $gallery_image->post_excerpt) {
								$out .= '<div class="slider-image-caption">'.$img_caption.'</div>';
							}
						}
					$out .= '</li>';
				}
				$out .= '</ul></div>';
			} else {
				$out .= themify_get_image('ignore=true&w='.$themify->width.'&h='.$themify->height );
			}
			echo $out;
			?>

		</div><!-- .post-image -->
	<?php endif; //post image ?>
	<?php if(!is_singular('portfolio')) echo '</div><!-- front side -->'; ?>

	<?php if(($themify->display_content != 'none' || $themify->hide_title != 'yes')): ?>
		<?php if(!is_singular('portfolio')): ?>
			<div class="tile-overlay back side">
		<?php endif; ?>
			<div class="post-inner">
				<?php if($themify->hide_meta != 'yes'): ?>
					<p class="post-meta entry-meta post-category-wrap">
						<?php echo ' '. get_the_term_list( get_the_id(), get_post_type().'-category', '<span class="post-category">', ', ', '</span>' ) ?>
					</p>
				<?php endif; //post meta ?>

				<?php if($themify->hide_title != 'yes'): ?>
					<?php if($themify->unlink_title == 'yes'): ?>
						<h1 class="post-title entry-title"><?php the_title(); ?></h1>
					<?php else: ?>
						<h1 class="post-title entry-title"><a href="<?php echo themify_get_featured_image_link(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
					<?php endif; //unlink post title ?>
				<?php endif; //post title ?>

				<?php if($themify->hide_date != 'yes'): ?>
					<p class="post-meta entry-meta">

						<?php if($themify->hide_date != 'yes'): ?>
							<time datetime="<?php the_time('o-m-d') ?>" class="post-date entry-date updated"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></time>
							
						<?php endif; //post date ?>
					</p>
				<?php endif; //post date or meta ?>

				<div class="entry-content">

				<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>

					<?php the_excerpt(); ?>

				<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>

				<?php else: ?>

					<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>

				<?php endif; //display content ?>

				</div><!-- /.entry-content -->

				<?php echo is_user_logged_in()? '<p>[<a href="' . get_edit_post_link(get_the_id()) . '">' . __('Edit', 'string') . '</a>]</p>' : ''; ?>
			</div>
			<!-- /.post-inner -->
		<?php if(!is_singular('portfolio')): ?>
			</div>
			<!-- /.tile-overlay -->
		<?php endif; ?>

	<?php endif; // end if content and title ?>

	<?php if(!is_singular('portfolio')) echo '</div><!-- /.tile-flip --></div><!-- /.tile-flip-wrapper -->'; ?>

</article>
<!-- /.post -->

<?php themify_post_after(); //hook ?>
