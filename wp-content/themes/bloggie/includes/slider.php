<?php
/** Check if slider is enabled */
if('' == themify_get('setting-feature_box_enabled') || 'on' == themify_get('setting-feature_box_enabled')) { ?>

	<?php themify_slider_before(); //hook ?>
    <div id="slider">
    	<?php themify_slider_start(); //hook ?>
    
        <ul class="slider-nav">
    		
            <?php 
			if(themify_get('setting-feature_box_posts_category') != ""){
				$cat = "&cat=".themify_get('setting-feature_box_posts_category');	
			} else {
				$cat = "&cat=0";
			}
			query_posts('showposts=4'.$cat); ?>		
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <li>
				 	<a href="#" title="<?php the_title_attribute(); ?>">
						<?php themify_image("ignore=true&w=70&h=58"); ?>
						<strong><?php the_title(); ?></strong> <small><?php the_time('M d Y') ?></small>
					</a>
				</li>
            <?php endwhile; else: endif; wp_reset_query(); ?>
    
        </ul>
    
        <ul class="slides">
            <?php query_posts('showposts=4'.$cat); ?>		
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				
				<li>
	                <div class="details">
	                    <h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
	                    <?php the_excerpt(); ?>
	                </div>

					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php themify_image("ignore=true&w=630&h=350"); ?>
					</a>
				</li>
           
            <?php endwhile; else: endif; wp_reset_query(); ?>
        </ul>
        
    	<?php themify_slider_end(); //hook ?>
    </div>
    <!--/slider -->
    <?php themify_slider_after(); //hook ?>
<?php } ?>