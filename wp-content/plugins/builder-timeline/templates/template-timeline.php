<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Template Timeline
 * 
 * Access original fields: $mod_settings
 */

if( method_exists( $GLOBALS['ThemifyBuilder'], 'load_templates_js_css' ) ) {
    $GLOBALS['ThemifyBuilder']->load_templates_js_css();
}

$fields_default = array(
	'mod_title_timeline' => '',
	'source_timeline' => 'post',
	'template_timeline' => 'graph',
	'add_css_timeline' => '',
);
$fields_args = wp_parse_args( $mod_settings, $fields_default );
extract( $fields_args, EXTR_SKIP );

$container_class = implode(' ', 
	apply_filters( 'themify_builder_module_classes', array(
		'module', 'module-' . $mod_name, $module_ID, 'layout-' . $template_timeline, $add_css_timeline
	), $mod_name, $module_ID, $fields_args )
);

// get items
$items = Builder_Timeline::get_instance()->get_source( $source_timeline )->get_items( $fields_args );

/* #3154 hack, if using Descending order for posts, make the Graph layout start at the end */
if( isset( $fields_args['order_post_timeline'] ) && $fields_args['order_post_timeline'] == 'desc' ) {
	$fields_args['start_at_end'] = true;
}
?>
<!-- module timeline -->
<div id="<?php echo $module_ID; ?>" class="<?php echo esc_attr( $container_class ); ?>">

	<?php if ( $mod_title_timeline != '' ): ?>
		<?php echo $mod_settings['before_title'] . wp_kses_post( apply_filters( 'themify_builder_module_title', $mod_title_timeline, $fields_args ) ) . $mod_settings['after_title']; ?>
	<?php endif; ?>

	<?php do_action( 'themify_builder_before_template_content_render' ); ?>

	<?php // render the template
	$this->retrieve_template( 'template-'.$mod_name.'-'.$template_timeline.'.php', array(
		'module_ID' => $module_ID,
		'mod_name' => $mod_name,
		'items' => $items,
		'settings' => ( isset( $fields_args ) ? $fields_args : array() )
	), '', '', true );
	?>

	<?php do_action( 'themify_builder_after_template_content_render' ); ?>
</div>
<!-- /module timeline -->
