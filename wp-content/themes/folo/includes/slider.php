<?php
/**
 * Template for home slider.
 */
$slides = new WP_Query( apply_filters( 'themify_slider_query_vars', array(
	'post_type' => 'slider',
	'posts_per_page' => '-1',
	'no_found_rows' => true,
) ) );

if ( $slides->have_posts() ) : ?>

	<?php themify_slider_before(); //hook ?>
	<div id="slider">
		<?php themify_slider_start(); //hook ?>

		<ul class="slides">
			<?php while ( $slides->have_posts() ) : $slides->the_post(); ?>
				<li id="slider-<?php echo esc_attr( $slides->post->ID ); ?>" <?php post_class(); ?>>
					<?php
					if ( themify_get('image_link') != '')
						$link = themify_get('image_link');
					else
						$link = themify_get_featured_image_link('no_permalink=true');
					?>

					<?php
					$before = '';
					$after = '';
					if ( $link != '' ) {
						$before = '<a href="' . $link .'" title="'. get_the_title() .'">';
						$zoom_icon = themify_check('lightbox_icon')? '<span class="zoom"></span>': '';
						$after = $zoom_icon . '</a>' . $after;
					} 
					?>

					<div class="slide-content">
						<h3 class="slide-post-title"><?php the_title() ?></h3>
						<?php the_content(); ?>	
					</div>
					<!-- /.slide-content -->	
					<?php echo $before . themify_get_image( 'w=510&h=340&class=slide-feature-image' ) . $after; ?>				
				</li>
			<?php endwhile; ?>
		</ul>

		<?php themify_slider_end(); //hook ?>
	</div>
	<!-- /#slider --> 
	<?php themify_slider_after(); //hook ?>

<?php endif; ?>
<?php wp_reset_postdata(); ?>