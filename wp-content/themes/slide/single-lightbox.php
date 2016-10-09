<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">

<title><?php if (is_home() || is_front_page()) { bloginfo('name'); } else { echo wp_title(''); } ?></title>

<!-- wp_header -->
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div id="pagewrap">
<div id="body">

	<div class="lightbox-item">

		<?php if( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'includes/loop' , 'single'); ?>

			<?php wp_link_pages(array('before' => '<p class="post-pagination"><strong>' . __('Pages:', 'Themify') . ' </strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			<?php get_template_part( 'includes/post-nav'); ?>

			<?php if(!themify_check('setting-comments_posts')): ?>
				<?php comments_template(); ?>
			<?php endif; ?>

		<?php endwhile; ?>

		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('a').on('click', function(){
					history.pushState({}, $(this).parent().text(), window.location);
				});
			});
		</script>
	</div> <!-- /.lightbox-item -->
</div>
<!-- /#body -->

<!-- wp_footer -->
<?php wp_footer(); ?>
</div>
</body>
</html>