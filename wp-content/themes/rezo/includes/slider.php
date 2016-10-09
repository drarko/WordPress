<?php
$args = array(
	'post_type' => 'slider',
	'posts_per_page' => '-1'
);
$slides = new WP_Query( apply_filters( 'themify_slider_query_vars', $args ) );

if ( $slides->have_posts() ) : ?>

<?php themify_slider_before(); //hook ?>

<div id="slider">

	<?php themify_slider_start(); //hook ?>
    
	<ul class="slides">
		
		<?php while ( $slides->have_posts() ) : $slides->the_post(); ?>
			<?php $slide_id = $slides->post->ID; ?>

			<li class="<?php echo get_post_meta( $slide_id, 'layout', true); ?>"> 
			
			<?php
			$before = '';
			$after = '';
			if(get_post_meta( $slide_id, 'image_link', true) != ''){
				$before = '<a href="'.get_post_meta( $slide_id, 'image_link', true).'" class="" title="'.the_title_attribute('echo=0').'">';
				$zoom_icon = themify_check('lightbox_icon')? '<span class="zoom"></span>': '';
				$after = $zoom_icon . '</a>' . $after;
			} 
							  
			if(get_post_meta( $slide_id, 'layout', true) == 'slider-image-only'){
			
				 echo $before . themify_get_image('w=976&h=435') . $after;
			
			} else if(get_post_meta( $slide_id, 'layout', true) == 'slider-content-only'){
			
				the_content();
				edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>');
			
			} else if(get_post_meta( $slide_id, 'layout', true) == 'slider-image-caption'){
			
				echo $before . themify_get_image('w=976&h=435') . $after;
				echo '<div class="caption">';
				echo '<h3>' . $before . get_the_title() . $after . '</h3>';
				edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>');
				the_content();
				echo '</div>';
			
			} else {
			
				echo $before . themify_get_image('w=620&h=435&class=slide-feature-image') . $after;
				echo '<div class="slide-content">';
				echo '<h3>' . $before . get_the_title() . $after . '</h3>';
				edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>');
				the_content();
				echo '</div>';
			}
			?>
	
			</li>

		<?php endwhile; ?>

	</ul>
    
    <?php themify_slider_end(); //hook ?>
</div>
<!--/slider -->
<?php themify_slider_after(); //hook ?>
 
<?php endif; ?>
<?php wp_reset_postdata(); ?>