<?php
/**
 * Template for gallery post type display.
 * @package themify
 * @since 1.0.0
 */

/** Themify Default Variables
 *  @var object */
global $themify, $themify_gallery;

?>

<?php themify_post_before(); // hook ?>
<article id="post-<?php the_id(); ?>" <?php post_class( 'gallery-post' ); ?>>
	<?php themify_post_start(); // hook ?>

	<?php themify_before_post_title(); // Hook ?>
		<h1 class="post-title entry-title"><?php the_title(); ?></h1>
	<?php themify_after_post_title(); // Hook ?>

	<?php the_terms( get_the_id(), 'gallery-category', '<span class="post-category">', ', ', '</span>' ); ?>

	<?php if ( post_password_required() ) : ?>
		<div class="gallery-content pagewidth">
			<?php echo get_the_password_form(); ?>
		</div>
	<?php else: ?>
		<div class="gallery-wrapper clearfix gallery-type-gallery">
			<?php
			/**
			 * GALLERY TYPE: GALLERY
			 */
			if ( themify_get( 'gallery_shortcode' ) != '' ) : ?>
				<?php themify_before_post_image(); // hook ?>
				<?php
				$images = $themify_gallery->get_gallery_images();
				if ( $images ) : 
					// Find out the size specified in shortcode
					$thumb_size = $themify_gallery->get_gallery_size();
					$counter = 0; 
					$use =  themify_check( 'setting-img_settings_use' );
					if($thumb_size!=='full'){
						$size['width']  = get_option( "{$thumb_size}_size_w" );
						$size['height'] = get_option( "{$thumb_size}_size_h" );
					}
					?>

					<?php foreach ( $images as $image ) :
						$counter++;
						$caption = $themify_gallery->get_caption( $image );
						$description = $themify_gallery->get_description( $image );
						$alt = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
						if(!$alt){
							$alt = $caption?$caption:($description?$description:the_title_attribute('echo=0'));
						}
						$featured = get_post_meta( $image->ID, 'themify_gallery_featured', true );
						$img_size = $thumb_size!=='full'?$size:( $featured?  array('width' => 500,'height' => 536):array('width' => 250,'height' => 268));
						$img_size = apply_filters( 'themify_single_gallery_image_size', $img_size, $featured );
						$img = wp_get_attachment_image_src( $image->ID, apply_filters( 'themify_gallery_post_type_single', $thumb_size ) );
						$out_image = $use?
									'<img src="' . $img[0] . '" alt="' . $alt . '" width="' . $img_size['width'] . '" height="' . $img_size['height'] . '" />'
									:
									themify_get_image( "src={$img[0]}&w={$img_size['width']}&h={$img_size['height']}&ignore=true&alt=$alt" );

						?>
						<div class="item gallery-icon <?php echo $featured; ?>">
							<a href="<?php echo $img[0]; ?>" title="<?php esc_attr_e($image->post_title)?>"  data-image="<?php echo $img[0]; ?>" data-caption="<?php echo $caption; ?>" data-description="<?php echo $description; ?>">
								<?php echo $out_image; ?>
								<?php if($caption):?>
									<span><?php echo $caption; ?></span>
								<?php endif;?>
							</a>
						</div>
					<?php endforeach; // images as image ?>

				<?php endif; // images ?>
				<?php themify_after_post_image(); // hook ?>
			<?php endif; // video section type ?>

		</div>

		<div class="gallery-content pagewidth">
			<div class="post clearfix">
				<div class="post-meta entry-meta">
					<p class="post-author">

						<?php echo get_avatar( get_the_author_meta('user_email'), $themify->avatar_size, '' ); ?>
						<br/>
						<small><?php printf( __('<a href="%s">%s</a>', 'themify'),  get_author_posts_url( get_the_author_meta( 'ID' ) ), get_the_author_meta('display_name') ); ?></small>
					</p>

					<time class="post-date entry-date updated" datetime="<?php the_time('o-m-d') ?>"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></time><br>
					<span class="post-comment"><?php comments_popup_link( __( '0 comments', 'themify' ), __( '1 comment', 'themify' ), __( '% comments', 'themify' ) ); ?></span><br/>

				</div>
				<div class="post-content">

					<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>

				</div>
				<!-- /.post-content -->

			</div>
			<!-- / .post -->

			<?php wp_link_pages(array('before' => '<p class="post-pagination"><strong>' . __('Pages:', 'themify') . ' </strong>', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			<?php get_template_part( 'includes/author-box', 'single'); ?>

			<?php if ( is_singular() ) : ?>
				<?php get_template_part( 'includes/post-nav'); ?>
			<?php endif; ?>

			<?php if(!themify_check('setting-comments_posts')): ?>
				<?php comments_template(); ?>
			<?php endif; ?>

		</div>

	<?php endif; ?>
	<?php themify_post_end(); // hook ?>

</article>
<!-- /.post -->
<?php themify_post_after(); // hook ?>
