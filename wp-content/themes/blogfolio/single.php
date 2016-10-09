<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
	global $themify; ?>

	<?php if( have_posts() ) while ( have_posts() ) : the_post(); ?>
	
	<!-- layout-container -->
	<div id="layout" class="pagewidth clearfix">
	
    	<?php themify_content_before(); //hook ?>
		<!-- content -->
		<div id="content" class="list-post">
        	<?php themify_content_start(); //hook ?>

			<?php get_template_part( 'includes/loop' , 'single'); ?>
	
			<?php wp_link_pages(array('before' => '<p class="post-pagination"><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			<?php if ( themify_get( 'large_image_1' ) != '' ) : ?>

				<?php
				$themify_slider_thumbs = '';
				$themify_slider_larges = '';
				for ( $x = 0; $x <= 30; $x ++ ) {
					if ( themify_is_image_script_disabled() ) {
						if ( $img = get_post_meta(get_the_ID(), '_large_image_'.$x.'_attach_id', true) ) {
							// Save thumb
							$themify_slider_thumbs .= '<li><a href="javascript:void(0);" title="">';
							$themify_slider_thumbs .= wp_get_attachment_image( $img, 'thumbnail', false, array(
								'width' => 40,
								'height' => 40,
								'alt' => trim(strip_tags( get_post_meta($img, '_wp_attachment_image_alt', true) )),
							));
							$themify_slider_thumbs .= '</a></li>';

							// Save large
							$themify_slider_larges .= '<span>';
							$themify_slider_larges .= wp_get_attachment_image( $img, 'large', false, array(
								'width' => 665,
								'alt' => trim(strip_tags( get_post_meta($img, '_wp_attachment_image_alt', true) )),
								'class' => 'img-style',
							));
							$themify_slider_larges .= '</span>';
						}
					} else {
						// Save thumb
						$themify_slider_thumbs .= themify_get_image("field_name=Large Image ".$x.", large_image_".$x."&w=40&h=40&ignore=true&before=<li><a href='javascript:void(0);' title=''>&after=</a></li>");

						// Save large
						$themify_slider_larges .= themify_get_image("field_name=Large Image ".$x.", large_image_".$x."&setting=image_large&w=665&h=0&ignore=true&before=<span>&after=</span>&class=img-style");
					}
				} ?>

				<!-- showcase -->
				<div class="showcase">

					<ul class="showcase-nav clearfix" id="showcasenav">
						<?php echo $themify_slider_thumbs; ?>
					</ul>

					<div id="slideshow">
						<?php echo $themify_slider_larges; ?>
					</div>

				</div>
				<!-- /showcase -->

			<?php endif; // large_image_1 ?>

			<?php get_template_part( 'includes/author-box', 'single'); ?>
					
			<?php get_template_part( 'includes/post-nav'); ?>
	
			<?php if(!themify_check('setting-comments_posts')): ?>
				<?php comments_template(); ?>
			<?php endif; ?>
            
			<?php themify_content_end(); //hook ?>
		</div>
		<!--/content -->
        <?php themify_content_after() //hook; ?>
	
	<?php endwhile; ?>
	
	<?php 
	/////////////////////////////////////////////
	// Sidebar							
	/////////////////////////////////////////////
	if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>

</div>
<!-- layout-container -->
	
<?php get_footer(); ?>