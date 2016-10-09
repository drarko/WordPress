<?php
	$prev_post_ts    		= null;
	$prev_post_year  		= null;
	$prev_post_month		= null;
?>	
	<div class="timeline-nav-wrap">
		<ul class="timeline-nav">
	
		<?php while ( have_posts() ) { the_post();
	
			$post_ts    =  strtotime( $post->post_date );
			$post_year  =  date( 'Y', $post_ts );
			$post_month =  date( 'n', $post_ts );
	
			/* Handle the first year as a special case */
			if ( is_null( $prev_post_year ) ) {
				?>
				<li>
				<a class="timeline-nav-year" href="#"><?php echo $post_year; ?></a>
				<ul class="timeline-nav-months">
				<?php
			}
			else if ( $prev_post_year != $post_year ) {
				/* Close off the OL */
				?>
				</ul>
				<!-- /timeline-nav-months -->
				</li>
				<?php
	
				$working_year  =  $prev_post_year;
	
				/* Print year headings until we reach the post year */
				while ( $working_year > $post_year ) {
					$working_year--;
					if($working_year == $post_year){
					?>
					<li>
					<a class="timeline-nav-year" href="#"><?php echo $working_year; ?></a>
					<?php
					}
				}
	
				/* Open a new ordered list */
				?>
				<ul class="timeline-nav-months">
				<?php
			}

			if($post_month != $prev_post_month || $post_year != $prev_post_year){
		?>
			<li>
				<a class="jump-month" data-jump="<?php echo "jump-$post_year-$post_month"; ?>" data-year="<?php echo $post_year; ?>" data-month="<?php echo $post_month; ?>" href="<?php echo get_month_link($post_year, $post_month); ?>">
					<?php echo get_the_date(apply_filters('themify_timeline_month_nav', 'F')); ?>
				</a>
			</li>
		<?php
				$prev_post_month = $post_month;
			}

				/* For subsequent iterations */
				$prev_post_ts    =  $post_ts;
				$prev_post_year  =  $post_year;
			} // End WHILE Loop
		
			/* If we've processed at least *one* post, close the div */
			if ( ! is_null( $prev_post_ts ) ) {
		?>
		</ul>
		<!-- /timeline-nav-months -->
		</li>
		<?php } ?>
		<?php //wp_reset_postdata(); ?>
		</ul>
	</div>
	<!-- /timeline-nav -->