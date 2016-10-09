<?php
global $query_string, $themify;
// check pagination setting
$is_infinite = themify_get('setting-more_posts') != 'pagination' ? true : false;
$real_posts_page = $wp_query->query_vars['posts_per_page'];

$query_args = array(
	'cat' => $themify->query_category,
	'posts_per_page' => -1,
	'paged' => $themify->paged,
	'order' => $themify->order,
	'orderby' => $themify->orderby
);

if( $is_infinite ) {
	// if is page check the query page
	if( isset($themify->query_category) && $themify->query_category != '' ) {
		$build_query = build_query( $query_args );
		query_posts( apply_filters( 'themify_query_posts_page_args', $build_query ) );
	}
	else {
		unset($query_args['cat']);
		$build_query = build_query( $query_args );
		query_posts( $query_string . '&' . $build_query );
	}
}
?>
<?php if (have_posts()) : ?>

	<?php
	$post_page_meta = themify_get('posts_per_page');
	$posts_per_page = empty($post_page_meta) ? $real_posts_page : $post_page_meta;
	?>
	<script type="text/javascript">
		var timeline_query_posts = {
			posts_per_page: '<?php echo $posts_per_page; ?>'
		};
	</script>

		<!-- loops-wrapper -->
		<div class="loops-timeline infinite-scrolling <?php echo $themify->layout . ' ' . $themify->post_layout; ?>">

			<?php get_template_part( 'includes/timeline-nav'); ?>

			<div class="timeline-wrap">
				<div class="timeline-bar"></div><!-- /timeline-bar -->
				<div class="timeline-start-dot"></div><!-- /timeline-start-dot -->
					<div class="timeline-content">
						<?php
						$prev_post_ts    		= null;
						$prev_post_month  	= null;
						$prev_post_year = null;
						$first_timeline_loop = false;
						$yearly = apply_filters( 'themify_timeline_yearly', false );
						$iterate = 0;
						?>
						<?php while ( have_posts() ) { the_post();

							$post_ts    =  strtotime( $post->post_date );
							$post_month  =  date( apply_filters( 'themify_timeline_query', 'n' ), $post_ts );
							$post_year = get_the_date('Y');
							$dateon = ($yearly) ? $post_year : get_the_date('Y-n');

							/* Handle the first year as a special case */
							if ( is_null( $prev_post_month ) ) {
								?>
								<div id="timeline-set-<?php echo $dateon; ?>" class="timeline-set-month">
									<h3 id="set-<?php echo $dateon; ?>" class="timeline-month">
										<a name="jump-<?php echo $dateon; ?>" id="jump-<?php echo $dateon; ?>" class="timeline-jump"></a><span><?php echo get_the_date(apply_filters('themify_timeline_month', 'F Y')); ?></span>
									</h3>
									<div id="group-set-<?php echo $dateon; ?>" class="set-month">
								<?php
							}
							else if ( $prev_post_month != $post_month || $prev_post_year != $post_year ) {
								/* Close off the set-month */
								?>
									</div>
								<!-- /set-month -->

								<div class="inner-scroll-trigger <?php echo (!$first_timeline_loop) ? 'inner-scroll-visible': ''; ?>">
							 		<a href="#" class="trigger-more">
							 			<?php printf(__('See more %s stories','themify'), '<span class="date-placeholder-story"></span>'); ?>
							 		</a>
								</div>
								<!-- /#scroll-set -->

								</div>
								<!-- /timeline-set-month -->

								<?php
								$tl_set_month_style = $is_infinite ? ' style="display:none;"' : '';
								?>
								<div id="timeline-set-<?php echo $dateon; ?>" class="timeline-set-month unexpand"<?php echo $tl_set_month_style; ?>>
								<?php

								$working_year  =  $prev_post_month;

								if( $yearly ) {
									/* Print year headings until we reach the post year */
									while ( $working_year > $post_month ) {
										$working_year--;
										?>
										<h3 id="set-<?php echo $dateon; ?>" class="timeline-month">
											<a name="jump-<?php echo $dateon; ?>" id="jump-<?php echo $dateon; ?>" class="timeline-jump"></a><span><?php echo get_the_date(apply_filters('themify_timeline_month', 'F Y')); ?></span>
										</h3>
										<?php
									}
								}
								else{
									$timeline_disable_class = $is_infinite ? 'timeline-disable' : '';
									?>
									<h3 id="set-<?php echo $dateon; ?>" class="timeline-month <?php echo $timeline_disable_class; ?>">
										<a name="jump-<?php echo $dateon; ?>" id="jump-<?php echo $dateon; ?>" class="timeline-jump"></a><span><?php echo get_the_date(apply_filters('themify_timeline_month', 'F Y')); ?></span>
									</h3>
									<?php
								}
								?>

								<div id="group-set-<?php echo $dateon; ?>" class="set-month">
								<?php

								$first_timeline_loop = $is_infinite ? true : false;
							}
						?>

							<?php if(!$first_timeline_loop && ($iterate < $posts_per_page) ): ?>
								<?php themify_post_before(); //hook ?>
								<article id="post-<?php the_ID(); ?>" <?php post_class("post set-$dateon clearfix"); ?>>
									<?php themify_post_start(); //hook ?>

									<div class="post-inner clearfix">
									<?php if(is_search()): ?>
										<?php get_template_part( 'includes/loop' , 'search'); ?>
									<?php else: ?>
										<?php get_template_part( 'includes/loop' , 'index'); ?>
									<?php endif; ?>
									</div>
									<!-- /.post-inner -->

									<?php themify_post_end(); //hook ?>
								</article>
								<!-- /.post -->
								<?php themify_post_after(); //hook ?>

							<?php else: ?>

							<div class="post-load-queue post-load-queue-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>" style="display:none;"></div>

							<?php endif; // first timeline loop ?>

						<?php
								/* For subsequent iterations */
								$prev_post_ts    =  $post_ts;
								$prev_post_month  =  $post_month;
								$prev_post_year = $post_year;
								$iterate++;
							} // End WHILE Loop

							/* If we've processed at least *one* post, close the set-month */
							if ( ! is_null( $prev_post_ts ) ) {
						?>
							</div>
							<!-- /set-month -->

							<div class="inner-scroll-trigger <?php echo (!$first_timeline_loop) ? 'inner-scroll-visible': ''; ?>">
						 		<a href="#" class="trigger-more">
						 			<?php printf(__('See more %s stories','themify'), '<span class="date-placeholder-story"></span>'); ?>
						 		</a>
							</div>
							<!-- /#scroll-set -->
						</div>
						<!-- /timeline-set-month -->
						<?php } ?>
					</div>
					<!-- /timeline-content -->
			</div>
			<!-- /timeline-wrap -->

		</div>
		<!-- /loops-wrapper -->

		<?php get_template_part( 'includes/pagination'); ?>
		<?php wp_reset_postdata(); ?>

	<?php
	/////////////////////////////////////////////
	// Error - No Page Found
	/////////////////////////////////////////////
	?>

	<?php else : ?>

		<p><?php _e( 'Sorry, nothing found.', 'themify' ); ?></p>

	<?php endif; ?>
