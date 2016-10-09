<?php query_posts("post_type=slider&showposts=-1"); ?>	
<?php if (have_posts()) : ?>
 
<div id="sliderwrap" class="clearfix">

	<?php themify_slider_before(); //hook ?>
	<div id="slider" class="pagewidth">
    	<?php themify_slider_start(); //hook ?>

		<ul class="slides">

			<?php while (have_posts()) : the_post(); ?>

				 <li id="slider-<?php the_ID(); ?>" <?php post_class(themify_get('layout')); ?>>

				<?php $link = themify_get_featured_image_link('no_permalink=true'); ?>

				<?php
				$before = '';
				$after = '';
				if( $link != ''){
					$before = '<a href="'. $link .'" title="'. the_title_attribute('echo=0') . '">';
					$zoom_icon = themify_check('lightbox_icon')? '<span class="zoom"></span>': '';
					$after = $zoom_icon . '</a>' . $after;
				}

				if(themify_get('layout') == 'slider-image-only'){
					$img_width = themify_check('image_width')?	themify_get('image_width'): '978';
					$img_height = themify_check('image_height')? themify_get('image_height'): '400';
					echo $before . themify_get_image("ignore=true&w=$img_width&h=$img_height") . $after;

				} else if(themify_get('layout') == 'slider-content-only'){

					the_content();

				} else if(themify_get('layout') == 'slider-image-caption'){
					$img_width = themify_check('image_width')?	themify_get('image_width'): '978';
					$img_height = themify_check('image_height')? themify_get('image_height'): '420';
					echo '<div class="image-caption-wrap">';
						echo $before . themify_get_image("ignore=true&w=$img_width&h=$img_height") . $after;
						echo '<div class="caption">';
							echo '<h3>' . $before . get_the_title() . $after . '</h3>';
							the_content();
						echo '</div>';
					echo '</div>';

				} else {
					$img_width = themify_check('image_width')?	themify_get('image_width'): '550';
					$img_height = themify_check('image_height')? themify_get('image_height'): '420';
					echo $before . themify_get_image("ignore=true&w=$img_width&h=$img_height&class=slide-feature-image") . $after;
					echo '<div class="slide-content">';
						echo '<h3>' . $before . get_the_title() . $after . '</h3>';
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
    
</div>
<!--/sliderwrap -->
<?php endif; ?>
<?php wp_reset_query(); ?>

