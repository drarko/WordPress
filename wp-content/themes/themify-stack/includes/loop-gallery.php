<?php
/**
 * Partial template to render the gallery shortcode section type
 * Created by themify
 * @since 1.0.0
 */

global $themify, $themify_gallery;

$images = $themify_gallery->get_gallery_images();

if ( $images ) :

	if ( ! wp_script_is( 'themify-carousel-js' ) ) {
		// Enqueue Carousel Script
		wp_enqueue_script( 'themify-carousel-js' );
	}
	if ( ! wp_script_is( 'themify-backstretch' ) ) {
		// Enqueue Backstretch
		wp_enqueue_script( 'themify-backstretch' );
	}
	if ( ! wp_script_is( 'themify-widegallery' ) ) {
		// Enqueue Wide Gallery
		wp_enqueue_script( 'themify-widegallery' );
	}

	/**
	 * @var array $gsops Slider parameters.
	 */
	$gsops = $themify_gallery->get_slider_params( get_the_id() );
	?>

	<div class="gallery-wrapper">
		<div class="gallery-shortcode-wrap twg-wrap twg-gallery-shortcode" data-bgmode="<?php echo esc_attr( $gsops['bgmode'] ); ?>">
			<div class="gallery-image-holder twg-holder">
				<div class="twg-loading themify-loading"></div>
				<div class="gallery-info twg-info">
					<div class="gallery-caption twg-caption">
					</div>
					<!-- /.gallery-caption -->
				</div>
				<!-- /.gallery-info -->
			</div>

			<div class="gallery-slider-wrap twg-controls">
				<?php if ( 'no' != $gsops['timer'] && 'off' != $gsops['autoplay'] ) : ?>
					<div class="gallery-slider-timer">
						<div class="timer-bar"></div>
					</div>
					<!-- /.gallery-slider-timer -->
				<?php endif; ?>
				<ul class="gallery-slider-thumbs slideshow twg-list" data-sliderid="gallery-shortcode-slider-<?php echo esc_attr( $themify_gallery->get_slider_id( get_the_id() ) ); ?>" data-autoplay="<?php echo esc_attr( $gsops['autoplay'] ); ?>" data-effect="scroll" data-speed="<?php echo esc_attr( $gsops['transition'] ); ?>" data-wrap="yes" data-slidernav="yes" data-pager="no">
					<?php foreach ( $images as $image ) :
						$full = wp_get_attachment_image_src( $image->ID, apply_filters( 'themify_gallery_shortcode_full_size', 'large' ) );
						$caption = $themify_gallery->get_caption( $image );
						$description = $themify_gallery->get_description( $image );
						?>
						<li class="twg-item">
							<a href="#" data-image="<?php echo esc_attr( $full[0] ); ?>" data-caption="<?php echo esc_attr( $caption ); ?>" data-description="<?php echo esc_attr( $description ); ?>" class="twg-link">
								<?php
								if ( themify_is_image_script_disabled() ) {
									$img = wp_get_attachment_image_src( $image->ID );
									if ( ! empty( $img[0] ) ) {
										echo '<img src="' . esc_url( $img[0] ) . '" alt="' . esc_attr( $caption ) . '" width="40" height="33">';
									}
								} else {
									echo themify_get_image('src='.wp_get_attachment_url( $image->ID ).'&w=40&h=33&ignore=true' );
								}
								?>
							</a>
							<!-- /.twg-link -->
						</li>
						<!-- /.twg-item -->
					<?php endforeach; // images as image ?>
				</ul>
				<!-- /.twg-list -->
			</div>
		</div>
		<!-- /.twg-wrap -->
	</div>
	<!-- /.gallery-wrapper -->

	<?php
	// Increment instance for next gallery
	$themify->gallery_shortcode_slider_id++;
endif; // images ?>