<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Template A/B Image
 * 
 * Access original fields: $mod_settings
 * @author Themify
 */

if( method_exists( $GLOBALS['ThemifyBuilder'], 'load_templates_js_css' ) ) {
	$GLOBALS['ThemifyBuilder']->load_templates_js_css();
}

$fields_default = array(
	'mod_title_image_compare' => '',
	'style_image' => '',
	'url_image_a' => '',
	'url_image_b' => '',
	'title_image' => '',
	'image_size_image_compare' => '',
	'width_image_compare' => '',
	'height_image_compare' => '',
	'orientation_compare' => 'horizontal',
	'css_ab_image' => '',
	'animation_effect' => ''
);

$fields_args = wp_parse_args( $mod_settings, $fields_default );
extract( $fields_args, EXTR_SKIP );
$animation_effect = $this->parse_animation_effect( $animation_effect );
$image_alt = esc_attr( $title_image );

$container_class = implode(' ', 
	apply_filters( 'themify_builder_module_classes', array(
		'module', 'module-' . $mod_name, $module_ID, $css_ab_image, $animation_effect
	), $mod_name, $module_ID, $fields_args )
);

if ( $this->is_img_php_disabled() ) {
	// get image preset
	$preset = themify_get('setting-global_feature_size');
	if ( isset( $_wp_additional_image_sizes[ $preset ]) && $image_size_image != '') {
		$width_image_compare = intval( $_wp_additional_image_sizes[ $preset ]['width'] );
		$height_image_compare = intval( $_wp_additional_image_sizes[ $preset ]['height'] );
	} else {
		$width_image_compare = $width_image_compare != '' ? $width_image_compare : get_option($preset.'_size_w');
		$height_image_compare = $height_image_compare != '' ? $height_image_compare : get_option($preset.'_size_h');
	}
	$image = '<img src="'.esc_url($url_image_a).'" alt="'.$image_alt.'" width="'.$width_image_compare.'" height="'.$height_image_compare.'">';
	$image2 = '<img src="'.esc_url($url_image_b).'" alt="'.$image_alt.'" width="'.$width_image_compare.'" height="'.$height_image_compare.'">';
} else {
	$image = themify_get_image( 'src='.esc_url($url_image_a).'&w='.$width_image_compare .'&h='.$height_image_compare.'&alt='.$image_alt.'&ignore=true' );
	$image2 = themify_get_image( 'src='.esc_url($url_image_b).'&w='.$width_image_compare .'&h='.$height_image_compare.'&alt='.$image_alt.'&ignore=true' );
}

$max_width_style = ( $width_image_compare != '' ) ? "style='max-width: {$width_image_compare}px;'" : '';
?>
<!-- module a/b image -->
<div id="<?php echo $module_ID; ?>" class="<?php echo esc_attr( $container_class ); ?>">
	
	<?php if ( $mod_title_image_compare != '' ): ?>
		<?php echo $mod_settings['before_title'] . wp_kses_post( apply_filters( 'themify_builder_module_title', $mod_title_image_compare, $fields_args ) ) . $mod_settings['after_title']; ?>
	<?php endif; ?>

	<?php do_action( 'themify_builder_before_template_content_render' ); ?>

	<div id="ab-image-<?php echo $module_ID; ?>" class="twentytwenty-container ab-image" data-orientation="<?php echo $orientation_compare; ?>" <?php echo $max_width_style; ?>>
		<?php echo $image; ?>
		<?php echo $image2; ?>
	</div>

	<?php do_action( 'themify_builder_after_template_content_render' ); ?>
</div>
<!-- /module a/b image -->