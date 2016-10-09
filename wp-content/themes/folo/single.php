<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php if( have_posts() ) while ( have_posts() ) : the_post(); ?>
	
	<div id="upperwrap">
		<div id="upperwrap-inner">
			<div class="pagewidth">
			
				<h1 class="page-title"><?php $category = get_the_category(); echo isset( $category[0] )? $category[0]->cat_name : ''; ?></h1>
	
			</div>
			<!-- /.pagewidth -->
		</div>
		<!-- /#upperwrap-inner -->
	</div>
	<!-- /#upperwrap -->
	
	<div id="layout" class="clearfix pagewidth">

		<?php themify_content_before(); //hook ?>
		<div id="content" class="list-post">
        	<?php themify_content_start(); //hook ?>
	
			<?php get_template_part('includes/loop' , 'single'); ?>
	
			<?php wp_link_pages(array('before' => '<p class="post-pagination"><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			
			<?php get_template_part( 'includes/author-box', 'single'); ?>				
			
			<?php get_template_part('includes/post-nav'); ?>
	
			<!-- comments -->
			<?php if(!themify_check('setting-comments_posts')): ?>
				<?php comments_template(); ?>
			<?php endif; ?>
			<!-- /comments -->
			
            <?php themify_content_end(); //hook ?>
		</div>
		<!-- /#content -->
        <?php themify_content_after() //hook; ?>

<?php endwhile; ?>

<?php 
/////////////////////////////////////////////
// Sidebar							
/////////////////////////////////////////////
if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>

</div>
<!-- /#layout -->
	
<?php get_footer(); ?>