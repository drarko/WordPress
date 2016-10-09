<?php
/**
 * Template for single post view
 * @package themify
 * @since 1.0.0
 */
?>

<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify;
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<div class="featured-area">

	<?php get_template_part( 'includes/post-media', 'loop' ); ?>

	<div class="top-post-meta-wrap">

		<p class="top-post-meta">
			<?php if ( $themify->hide_meta != 'yes' && $themify->hide_meta_category != 'yes' ) : ?>
				<?php the_terms( get_the_ID(), 'category', ' <span class="post-category">', ', ', '</span>' ); ?>
			<?php endif; ?>
		</p>
		<!-- /post-meta -->

		<h2 class="post-title entry-title"><?php the_title(); ?></h2>

		<?php if ( $themify->hide_meta != 'yes' || $themify->hide_date != 'yes' ) : ?>
			<p class="post-meta entry-meta">

				<?php if ( $themify->hide_meta_author != 'yes' ) : ?>
					<span class="author-avatar"><?php echo get_avatar( get_the_author_meta('user_email'), $themify->avatar_size ); ?></span>
					<span class="post-author"><?php echo themify_get_author_link(); ?></span>
				<?php endif; // post author ?>

				<span class="post-meta-inline">
					<?php if ( $themify->hide_date != 'yes' ) : ?>
						<time datetime="<?php the_time('o-m-d') ?>" class="post-date entry-date updated"><?php the_time( apply_filters( 'themify_loop_date', get_option( 'date_format' ) ) ) ?></time>
						
					<?php endif; // post date ?>

					<?php if ( $themify->hide_meta_tag != 'yes' ) : ?>
						<?php the_terms( get_the_ID(), 'post_tag', ' <span class="post-tag">', ', ', '</span>' ); ?>
					<?php endif; ?>

					<?php if ( ! themify_get( 'setting-comments_posts' ) && comments_open() && $themify->hide_meta_comment != 'yes' ) : ?>
						<span class="post-comment"><?php comments_popup_link( '0', '1', '%', 'respond' ); ?></span>
					<?php endif; // meta comments ?>
				</span>

			</p>
			<!-- /post-meta -->
		<?php endif; // end meta or date  ?>

		<div class="top-excerpt-wrap">
			<?php the_excerpt(); ?>
		</div>

	</div>

</div>
<!-- /.featured-area -->

<!-- layout-container -->
<div id="layout" class="pagewidth clearfix">

	<?php themify_content_before(); // hook ?>
	<!-- content -->
	<div id="content" class="list-post">
    	<?php themify_content_start(); // hook ?>

		<?php get_template_part( 'includes/loop' , 'single' ); ?>

		<?php wp_link_pages( array( 'before' => '<p class="post-pagination"><strong>' . __( 'Pages:', 'themify' ) . ' </strong>', 'after' => '</p>', 'next_or_number' => 'number', ) ); ?>

		<?php get_template_part( 'includes/author-box', 'single' ); ?>

		<?php get_template_part( 'includes/post-nav' ); ?>

		<?php if ( ! themify_check( 'setting-comments_posts' ) ) : ?>
			<?php comments_template(); ?>
		<?php endif; ?>

		<?php themify_content_end(); // hook ?>
	</div>
	<!-- /content -->
    <?php themify_content_after(); // hook ?>

<?php endwhile; ?>

<?php
/////////////////////////////////////////////
// Sidebar
/////////////////////////////////////////////
if ( $themify->layout != 'sidebar-none' ) : get_sidebar(); endif; ?>

</div>
<!-- /layout-container -->

<?php get_footer(); ?>
