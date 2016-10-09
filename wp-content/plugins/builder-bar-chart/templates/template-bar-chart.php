<?php
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
/**
 * Template Bar Chart
 * 
 * Access original fields: $mod_settings
 * @author Themify
 */
$fields_default = array(
	'mod_title_bar_chart' => '',
	'mod_height_bar_chart' => '',
	'content_bar_chart' => array(),
	'label_direction_chart' => 'horizontal',
	'animation_effect' => '',
	'css_bar_chart' => ''
);

if( method_exists( $GLOBALS['ThemifyBuilder'], 'load_templates_js_css' ) ) {
	$GLOBALS['ThemifyBuilder']->load_templates_js_css();
}

$fields_args = wp_parse_args( $mod_settings, $fields_default );
extract( $fields_args, EXTR_SKIP );
$animation_effect = $this->parse_animation_effect( $animation_effect );

$container_class = implode( ' ', apply_filters( 'themify_builder_module_classes', array(
		'module', 'module-' . $mod_name, $module_ID, 'label-direction-'. $label_direction_chart, $css_bar_chart, $animation_effect
	), $mod_name, $module_ID, $fields_args )
);

$ui_class = implode( ' ', array( 'module-' . $mod_name ) );
?>
<!-- module bar-chart -->
<div id="<?php echo $module_ID; ?>" class="<?php echo esc_attr( $container_class ); ?>">

	<?php if ( $mod_title_bar_chart != '' ) : ?>
		<?php echo $mod_settings['before_title'] . wp_kses_post( apply_filters( 'themify_builder_module_title', $mod_title_bar_chart, $fields_args ) ) . $mod_settings['after_title']; ?>
	<?php endif; ?>

	<?php do_action( 'themify_builder_before_template_content_render' ); ?>

	<div class="<?php echo $ui_class; ?>">
		<ul class="bar-chart-content bc-chart" <?php echo ( ! empty( $mod_height_bar_chart ) ? 'style="height: ' . $mod_height_bar_chart . 'px "' : '' ); ?>>
			<?php
			foreach ( $content_bar_chart as $bar ) {
				extract( wp_parse_args( $bar, array(
					'bar_chart_label' => '',
					'bar_chart_percentage' => 0,
					'bar_chart_number' => '',
					'bar_chart_color' => ''
				) ) );
				?>
				<li>
					<span class="bc-bar" data-height="<?php echo $bar_chart_percentage; ?>" style="background-color: <?php echo $this->get_rgba_color( $bar_chart_color ); ?>" title="<?php echo $bar_chart_label; ?>">
						<span class="bc-value"><?php echo $bar_chart_number; ?></span>
					</span>
				</li>
			<?php } ?>
		</ul>  
	</div>

	<?php do_action( 'themify_builder_after_template_content_render' ); ?>

</div>
<!-- /module bar-chart -->
