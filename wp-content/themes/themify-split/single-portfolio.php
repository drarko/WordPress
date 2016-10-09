<?php
/**
 * Template for single portfolio view
 * @package themify
 * @since 1.0.0
 */
?>

<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php if( have_posts() ) while ( have_posts() ) : the_post(); ?>

<div class="featured-area">

	<?php get_template_part( 'includes/post-media', 'loop' ); ?>

	<div class="top-post-meta-wrap">

		<p class="top-post-meta">
			<?php if ( $themify->hide_meta != 'yes' ) : ?>
				<?php the_terms( get_the_ID(), 'portfolio-category', ' <span class="post-category portfolio-category">', ', ', '</span>' ); ?>
			<?php endif; ?>
		</p>
		<!-- /post-meta -->

		<h2 class="post-title entry-title"><?php the_title(); ?></h2>

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

		<?php get_template_part( 'includes/loop-portfolio', 'single' ); ?>

		<?php wp_link_pages(array('before' => '<p class="post-pagination"><strong>' . __('Pages:', 'themify') . ' </strong>', 'after' => '</p>', 'next_or_number' => 'number')); ?>

		<?php get_template_part( 'includes/author-box', 'single'); ?>

	    <?php get_template_part( 'includes/post-nav', 'portfolio' ); ?>

		<?php if(!themify_check('setting-comments_posts')): ?>
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
if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>

</div>
<!-- /layout-container -->

<?php get_footer(); ?>
