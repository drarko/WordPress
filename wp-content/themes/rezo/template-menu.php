<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
	<time datetime="<?php the_time( 'o-m-d' ); ?>"></time>
	
	<h1 class="menu-title"><?php the_title(); ?></h1>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="post-image"><?php themify_image( "{$themify->auto_featured_image}w={$themify->page_image_width}&h=0&ignore=true" ); ?></figure>
	<?php endif; ?>

	<?php the_content(); ?>

	<?php $themify->calculate_menu_layout(); ?>

<?php endwhile; endif; ?>
<?php edit_post_link(__('Edit','themify'), '[', ']'); ?>

<!-- layout-container -->
<div id="layout" class="clearfix">

    <?php themify_content_before(); //hook ?>
	<!-- loops-wrapper -->
	<div id="loops-wrapper" class="loops-wrapper clearfix <?php echo $themify->menu_layout ?>">
    	<?php themify_content_start(); //hook ?>

		<?php if(!themify_check("menu_category_list")): ?>

			<?php if(themify_get("menu_category") == "all" || themify_get("menu_category") == ""): ?>

				<?php if(themify_get('section_menu_categories') != 'yes'): ?>

					<?php $temp = get_categories(array('taxonomy'=>'menu_categories', 'orderby' => 'id')); ?>
					<?php $cats = ""; ?>
					<?php foreach($temp as $t): ?>

						<?php $cats .= $t->cat_ID.","; ?>

					<?php endforeach; ?>
					<?php $themify->menu_loop($cats, false); ?>

				<?php else: ?>

					<?php $cats = get_categories(array('taxonomy'=>'menu_categories', 'orderby' => 'id')); ?>
					<?php $themify->menu_loop($cats); ?>

				<?php endif; ?>

			<?php else: ?>

				<?php $cats = get_categories(array('taxonomy'=>'menu_categories', 'include'=>themify_get("menu_category"), 'orderby' => 'id')); ?>
				<?php $themify->menu_loop($cats); ?>

			<?php endif; ?>

		<?php else: ?>

			<?php if(themify_get('section_menu_categories') != 'yes'): ?>

				<?php $themify->menu_loop(themify_get('menu_category_list'), false); ?>

			<?php else: ?>

				<?php $categories = explode(",",str_replace(" ","",themify_get('menu_category_list'))); ?>

				<?php foreach ($categories as $category): ?>

					<?php $cats = get_categories(array('taxonomy'=>'menu_categories','include'=>$category, 'orderby' => 'id')); ?>
					<?php $themify->menu_loop($cats); ?>

				<?php endforeach; ?>

			<?php endif; ?>

		<?php endif; ?>

        <?php themify_content_end(); //hook ?>
	</div>
	<!-- /loops-wrapper -->
    <?php themify_content_after() //hook; ?>

</div>
<!--/layout-container -->
<?php get_footer(); ?>
