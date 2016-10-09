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
				<a class="timeline-nav-year nav-yearly jump-month" data-jump="<?php echo "jump-$post_year"; ?>" data-year="<?php echo $post_year; ?>" data-month="<?php echo $post_month; ?>" href="<?php echo get_year_link( $post_year )?>"><?php echo $post_year; ?></a>
				</li>
				<?php
			}
			else if ( $prev_post_year != $post_year ) {
				/* Close off the OL */
				?>
				<?php
	
				$working_year  =  $prev_post_year;
	
				/* Print year headings until we reach the post year */
				while ( $working_year > $post_year ) {
					$working_year--;
					if($working_year == $post_year){
					?>
					<li>
						<a class="timeline-nav-year nav-yearly jump-month" data-jump="<?php echo "jump-$post_year"; ?>" data-year="<?php echo $post_year; ?>" data-month="<?php echo $post_month; ?>" href="<?php echo get_year_link( $working_year ); ?>"><?php echo $working_year; ?></a>
					</li>
					<?php
					}
				}
	
				/* Open a new ordered list */
				?>
				<?php
			}

				/* For subsequent iterations */
				$prev_post_ts    =  $post_ts;
				$prev_post_year  =  $post_year;
			} // End WHILE Loop
		
			/* If we've processed at least *one* post, close the div */
			if ( ! is_null( $prev_post_ts ) ) {
		?>
		<?php } ?>
		</ul>
	</div>
	<!-- /timeline-nav -->