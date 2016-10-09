<?php
/**
 * Template for testimonial post type display.
 * @package themify
 * @since 1.0.0
 */
?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php

$link = themify_get_featured_image_link('no_permalink=true');
$before = '';
$after = '';
if ( $link != '' ) {
	$before = '<a href="' . $link . '" title="' . get_the_title() . '">';
	$zoom_icon = themify_zoom_icon( false );
	$after = $zoom_icon . '</a>' . $after;
	$zoom_icon = '';
}

// Check if user wants to use a common dimension or those defined in each highlight
if ('yes' == $themify->use_original_dimensions) {
	// Save post id
	$post_id = get_the_id();

	// Set image width
	$themify->width = get_post_meta($post_id, 'image_width', true);

	// Set image height
	$themify->height = get_post_meta($post_id, 'image_height', true);
}

$testimonial_image = themify_get_image('ignore=true&w='.$themify->width.'&h='.$themify->height );
$testimonial_thumb = '';

if ( ! empty( $testimonial_image ) ) {
	// Get image url from returned image
	$img_doc = new DOMDocument();
	@$img_doc->loadHTML($testimonial_image);
	$img_tag = $img_doc->getElementsByTagName('img');
	foreach( $img_tag as $tag ) {
		$testimonial_thumb = $tag->getAttribute('src');
	}
}

?>

<?php if ( 'slider' == $themify->post_layout ) :?>
	<li data-image="">
<?php endif; ?>

<?php themify_post_before(); // hook ?>
<article id="testimonial-<?php the_id(); ?>" <?php post_class('post clearfix testimonial-post'); ?> data-thumb="<?php echo $testimonial_thumb; ?>" data-thumbw="<?php echo $themify->width; ?>" data-thumbh="<?php echo $themify->height; ?>">
	<?php themify_post_start(); // hook ?>

	<div class="testimonial-content">
		<div class="entry-content">

		<?php if ( 'yes' != $themify->hide_title ): ?>
			<?php themify_before_post_title(); // Hook ?>
				<h1 class="post-title"><?php the_title(); ?></h1>
			<?php themify_after_post_title(); // Hook   ?>
		<?php endif; //post title ?>

		<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>
			<?php the_excerpt(); ?>
		<?php elseif($themify->display_content == 'content'): ?>
			<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>
		<?php endif; //display content ?>

		</div><!-- /.entry-content -->
	</div>

	<?php if ( ( 'slider' != $themify->post_layout ) && ( 'no' != $themify->hide_image ) ): ?>
		<?php themify_before_post_image(); // hook ?>
		<figure class="post-image">
			<?php echo $before; ?>
			<?php echo $testimonial_image; ?>
			<?php echo $after; ?>
		</figure>
		<?php themify_after_post_image(); // hook ?>
	<?php endif; // hide image ?>

	<?php
	$testimonial_author = themify_get('testimonial_name');
	if( ! $testimonial_author ) {
		$testimonial_author = themify_get('_testimonial_name');
	}
	$testimonial_title = themify_get('testimonial_title');
	if( ! $testimonial_title ) {
		$testimonial_title = themify_get('_testimonial_position');
	}
	if( $testimonial_author || $testimonial_title ) : ?>
		<?php themify_before_post_title(); // hook ?>
		<p class="testimonial-author">
			<?php echo $before.$testimonial_author.$after; ?><?php if( $testimonial_title ) : ?><span class="testimonial-title"><?php echo $testimonial_title; ?></span><?php endif; ?>
		</p>
		<?php themify_after_post_title(); // hook ?>
	<?php endif; ?>

	<?php edit_post_link(__('Edit Testimonial', 'themify'), '<span class="edit-button">[', ']</span>'); ?>

	<?php themify_post_end(); // hook ?>
</article>
<!-- / .post -->
<?php themify_post_after(); // hook ?>

<?php if ( 'slider' == $themify->post_layout ) : ?>
	</li>
<?php endif; ?>
