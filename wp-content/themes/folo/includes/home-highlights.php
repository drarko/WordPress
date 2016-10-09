<?php
/**
 * Template to display highlights in home page
 */

if( themify_get( 'setting-exclude_home_highlights' ) == 'on' )
	return;

$highlight_query = new WP_Query( apply_filters( 'themify_theme_home_highlights',
		array(
			'post_type' => 'highlight',
			'posts_per_page' => 8
		)
	)
);

if ( $highlight_query->have_posts() ) : ?>

<div class="home-highlightswrap">
	<div class="home-highlights pagewidth clearfix">

		<?php
		$x = 1;
		while ( $highlight_query->have_posts() ) :
			$highlight_query->the_post();
			$highlight_id = $highlight_query->post->ID;
			if( 1 == $x ) {
				$class = 'first';
				$x++;
			} else {
				$class = '';
				if( 4 == $x ){
					$x = 1;
				}
				else {
					$x++;
				}
			}
			?>
			<div id="highlights-<?php echo $highlight_id; ?>" class="<?php echo join( ' ', get_post_class( 'col4-1 ' . $class, $highlight_id ) ); ?>">
				<div class="icon">

					<?php
					$before = '';
					$after = '';
					$link = themify_get_featured_image_link('no_permalink=true');
					if( $link != ''){
						$before = '<a href="' . $link .'" title="'. get_the_title( $highlight_id ) .'">';
						$zoom_icon = themify_check('lightbox_icon')? '<span class="zoom"></span>': '';
						$after = $zoom_icon . '</a>' . $after;
					} ?>

					<?php echo $before; ?>
					<?php themify_image('field_name=feature_image&w=70&h=70'); ?>
					<?php echo $after; ?>

				</div>
				<div class="home-highlights-content">
					<h4 class="home-highlights-title"><?php echo $before ?><?php the_title(); ?></a></h4>
					<?php the_content(); ?>
					<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>
				</div>
			</div>

		<?php endwhile; ?>

	</div>
</div>
<!-- /.home-highlightswrap -->

<?php
endif;

wp_reset_postdata();
wp_reset_query();
?>