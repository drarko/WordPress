<?php if ( ! is_single() ) {
	global $more;
	$more = 0;
} //enable more link ?>
<?php
/** Themify Default Variables
 *
 * @var object
 */
global $themify; ?>

<?php
$categories = wp_get_object_terms( get_the_id(), 'portfolio-category' );
$class      = '';
foreach ( $categories as $cat ) {
	$class .= ' cat-' . $cat->term_id;
}
?>

<?php themify_post_before(); //hook ?>
<article id="portfolio-<?php the_id(); ?>" class="<?php echo esc_attr( implode( ' ', get_post_class( 'post clearfix portfolio-post' . $class ) ) ); ?>">
	<?php themify_post_start(); // hook ?>

	<?php if ( ( ! is_single() && $themify->hide_image != 'yes' ) || ( isset( $themify->is_builder_portfolio_loop ) && $themify->is_builder_portfolio_loop && $themify->hide_image != 'yes' ) || ( isset( $themify->is_shortcode ) && $themify->is_shortcode && $themify->hide_image != 'yes' ) ) : ?>

		<?php get_template_part( 'includes/post-media', get_post_type() ); ?>

	<?php endif //hide image ?>

	<div class="post-content">
		<div class="disp-table">
			<div class="disp-row">
				<div class="disp-cell valignmid">

					<?php if ( is_single() ) : ?>
						<div class="project-meta">
							<?php if ( $date = get_post_meta( get_the_id(), 'project_date', true ) ): ?>
								<div class="project-date">
									<strong><?php _e( 'Date', 'themify' ); ?></strong>
									<?php echo wp_kses_post( $date ); ?>
								</div>
							<?php endif; //post date ?>

							<?php if ( $client = get_post_meta( get_the_id(), 'project_client', true ) ) : ?>
								<div class="project-client">
									<strong><?php _e( 'Client', 'themify' ); ?></strong>
									<?php echo wp_kses_post( $client ); ?>
								</div>
							<?php endif; ?>

							<?php if ( $services = get_post_meta( get_the_id(), 'project_services', true ) ) : ?>
								<div class="project-services">
									<strong><?php _e( 'Services', 'themify' ); ?></strong>
									<?php echo wp_kses_post( $services ); ?>
								</div>
							<?php endif; ?>

							<?php if ( $launch = get_post_meta( get_the_id(), 'project_launch', true ) ) : ?>
								<div class="project-launch">
									<a href="<?php echo esc_attr( $launch ); ?>"><?php _e( 'Launch Project', 'themify' ); ?></a>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $themify->hide_meta != 'yes' ): ?>
						<div class="post-meta-top entry-meta">
							<?php the_terms( get_the_id(), get_post_type() . '-category', '<span class="post-category">', ' <span class="separator">/</span> ', ' </span>' ) ?>
						</div>
					<?php endif; //post meta ?>

					<?php if ( $themify->hide_title != 'yes' ): ?>
						<?php themify_before_post_title(); // Hook ?>
						<h2 class="post-title entry-title">
							<?php if ( $themify->unlink_title == 'yes' ): ?>
								<?php the_title(); ?>
							<?php else: ?>
								<a href="<?php echo themify_get_featured_image_link(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
							<?php endif; //unlink post title ?>
						</h2>
						<?php themify_after_post_title(); // Hook ?>
					<?php endif; //post title ?>

					<div class="entry-content">

						<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>

							<?php the_excerpt(); ?>

						<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>

						<?php else: ?>

							<?php the_content( themify_check( 'setting-default_more_text' ) ? themify_get( 'setting-default_more_text' ) : __( 'More &rarr;', 'themify' ) ); ?>

						<?php endif; //display content ?>

					</div>
					<!-- /.entry-content -->

					<?php edit_post_link( __( 'Edit', 'themify' ), '<span class="edit-button">[', ']</span>' ); ?>

				</div>
				<!-- /.disp-cell -->
			</div>
			<!-- /.disp-row -->
		</div>
		<!-- /.disp-table -->
	</div>
	<!-- /.post-content -->
	<?php themify_post_end(); // hook ?>

</article>
<!-- /.post -->
<?php themify_post_after(); //hook ?>
