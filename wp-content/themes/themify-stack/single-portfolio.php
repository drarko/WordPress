<?php
/**
 * Template for single portfolio view
 * @package themify
 * @since 1.0.0
 */
?>

<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify, $themify_portfolio; ?>

<?php if( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div class="featured-area <?php echo esc_attr( themify_theme_featured_area_style() ); ?>">

	<?php if ( $themify->hide_image != 'yes' ) : ?>

		<?php themify_before_post_image(); // Hook ?>

		<?php
		///////////// GALLERY //////////////////////
		if ( $images = $themify_portfolio->get_gallery_images() ) : ?>
			<?php
                        $use =  themify_check( 'setting-img_settings_use' );
			// Find out the number of columns in shortcode
			$columns = $themify_portfolio->get_gallery_columns();
                        $columns = ( $columns == '' ) ? 3 : $columns;
			// Count how many images we really have
			$n_images = count( $images );
			if ( $columns > $n_images ) {
				$columns = $n_images;
			}
                        
                        // Find out the size specified in shortcode
			$thumb_size = $themify_portfolio->get_gallery_size();
                        if (!$thumb_size) {
                            $thumb_size = 'thumbnail';
			}
			if($thumb_size!=='full'){
				$size['width']  = get_option( "{$thumb_size}_size_w" );
				$size['height'] = get_option( "{$thumb_size}_size_h" );
			}
			?>
			<div class="gallery-wrapper packery-gallery clearfix gallery-columns-<?php echo $columns ?>">
				<?php
				$counter = 0; ?>

				<?php foreach ( $images as $image ) :
					$counter++;

					$caption = $themify_gallery->get_caption( $image );
					$description = $themify_gallery->get_description( $image );
					$alt = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
					if(!$alt){
                                            $alt = $caption?$caption:($description?$description:the_title_attribute('echo=0'));
					}
					$featured = get_post_meta( $image->ID, 'themify_gallery_featured', true );
					$img_size = $thumb_size!=='full'?$size:( $featured?  array('width' => 350,'height' => 400):array('width' => 350,'height' => 200));
					$img_size = apply_filters( 'themify_single_gallery_image_size', $img_size, $featured );
					$height = $thumb_size !== 'full' && $featured ? 2 * $img_size['height'] : $img_size['height'];
					$thumb = $featured ? 'large' : $thumb_size;
					$img = wp_get_attachment_image_src( $image->ID, apply_filters( 'themify_gallery_post_type_single', $thumb ) );
					$url = !$featured || $use ? $img[0]:themify_get_image("src={$img[0]}&w={$img_size['width']}&h={$height}&ignore=true&urlonly=true");
					$lightbox_url = $thumb_size!=='large'?wp_get_attachment_image_src($image->ID, 'large'):$img;
					?>
					<div class="item gallery-icon <?php echo $featured; ?>">
						<a href="<?php echo $lightbox_url[0]; ?>" title="<?php esc_attr_e($image->post_title)?>" data-image="<?php echo $lightbox_url[0]; ?>" data-caption="<?php echo esc_attr( $caption ); ?>" data-description="<?php echo esc_attr( $description ); ?>">
							<span class="gallery-item-wrapper">
							<img src="<?php echo $url ?>" alt="<?php echo $alt ?>" width="<?php echo  $img_size['width'] ?>" height="<?php echo $height ?>" />
							<?php if($caption):?>
								<span class="gallery-caption"><?php echo $caption?></span>
							<?php endif;?>
							</span>
						</a>
					</div>
				<?php endforeach; // images as image ?>
			</div>

		<?php
		///////////// SINGLE IMAGE //////////////////////
		elseif( $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height) ) : ?>

			<figure class="post-image <?php echo esc_attr( $themify->image_align ); ?>">
				<?php if( 'yes' == $themify->unlink_image): ?>
					<?php echo wp_kses_post( $post_image ); ?>
				<?php else: ?>
					<a href="<?php echo themify_get_featured_image_link(); ?>"><?php echo wp_kses_post( $post_image ); ?><?php themify_zoom_icon(); ?></a>
				<?php endif; // unlink image ?>
			</figure>

		<?php endif; // video else image ?>

		<?php themify_after_post_image(); // Hook ?>

	<?php endif; // hide image ?>

	</div>

<!-- layout-container -->
<div id="layout" class="pagewidth clearfix">

	<?php themify_content_before(); // hook ?>
	
	<!-- content -->
	<div id="content" class="list-post">
    	<?php themify_content_start(); // hook ?>

		<?php get_template_part( 'includes/loop-portfolio', 'single' ); ?>

		<?php wp_link_pages(array('before' => '<p class="post-pagination"><strong>' . __('Pages:', 'themify') . ' </strong>', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		
		<?php get_template_part( 'includes/author-box', 'single'); ?>
		
		<?php get_template_part( 'includes/post-nav', 'portfolio'); ?>
		
		<?php if(!themify_check('setting-comments_posts')): ?>
			<?php comments_template(); ?>
		<?php endif; ?>
		
        <?php themify_content_end(); // hook ?>
	</div>
	<!-- /content -->

    <?php themify_content_after(); // hook ?>

<?php endwhile; ?>

<?php 
/////////////////////////////////////////////
// Sidebar							
/////////////////////////////////////////////
if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>

</div>
<!-- /layout-container -->
	
<?php get_footer(); ?>