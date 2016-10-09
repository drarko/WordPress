<?php if(!is_single()) { global $more; $more = 0; } //enable more link ?>

<?php
/** Themify Default Variables
 @var object */
	global $themify; ?>

	<?php if( $themify->is_shortcode_template ) : ?>

	<?php themify_post_before(); //hook ?>
	<article id="post-<?php the_id(); ?>" <?php post_class( 'post clearfix' ); ?>>
		<?php themify_post_start(); //hook ?>
		<div class="post-inner">

	<?php endif; ?>

		<!-- Title, post icon, post date -->
		<span class="post-icon"></span>
		<?php if($themify->hide_date != "yes"): ?>
			<time datetime="<?php the_time('Y-m-d') ?>" class="post-date entry-date updated">
				<span><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></span>
			</time>
		<?php endif; //post date ?>

		<?php if($themify->hide_title != "yes"): ?>
			<?php themify_before_post_title(); // Hook ?>
			<?php if($themify->unlink_title == "yes"): ?>
				<h1 class="post-title entry-title"><?php the_title(); ?></h1>
			<?php else: ?>
				<h1 class="post-title entry-title"><a href="<?php echo themify_get_featured_image_link(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			<?php endif; //unlink post title ?>
			<?php themify_after_post_title(); // Hook ?>
		<?php endif; //post title ?>
		<!-- / Title, post icon, post date -->

		<?php get_template_part('includes/loop-' . $themify->get_format_template()); ?>

		<div class="post-dot"></div><!-- /post-dot -->
		<div class="post-arrow"></div><!-- /post-arrow -->

	<?php if( $themify->is_shortcode_template ) : ?>

		</div>
		<!-- /.post-inner -->
		<?php themify_post_end(); //hook ?>

	</article>
	<!-- /.post -->
	<?php themify_post_after(); //hook ?>

	<?php endif; ?>
