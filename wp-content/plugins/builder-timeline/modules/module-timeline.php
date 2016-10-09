<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Button
 */
class TB_Timeline_Module extends Themify_Builder_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __( 'Timeline', 'builder-timeline' ),
			'slug' => 'timeline'
		));
	}

	public function get_options() {
		return array(
			array(
				'id' => 'mod_title_timeline',
				'type' => 'text',
				'label' => __('Module Title', 'builder-timeline'),
				'class' => 'large'
			),
			array(
				'id' => 'template_timeline',
				'type' => 'radio',
				'label' => __('Timeline Layout', 'builder-timeline'),
				'options' => array(
					'graph' => __( 'Timeline Graph', 'builder-timeline' ),
					'list' => __( 'List View', 'builder-timeline' ),
				),
				'default' => 'graph',
				// 'option_js' => true
			),
			array(
				'id' => 'source_timeline',
				'type' => 'timeline_source',
			),
			array(
				'id' => 'config_source_timeline',
				'type' => 'timeline_source_config',
			),
		);
	}


	public function get_styling() {
		return array(
			// Animation
			array(
				'id' => 'separator_animation',
				'title' => '',
				'description' => '',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Animation', 'builder-timeline').'</h4>'),
			),
			array(
				'id' => 'animation_effect',
				'type' => 'animation_select',
				'label' => __( 'Effect', 'builder-timeline' )
			),
			// Background
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_image_background',
				'title' => '',
				'description' => '',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Background', 'builder-timeline').'</h4>'),
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'label' => __('Background Color', 'builder-timeline'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => '.module-timeline'
			),
			// Font
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_font',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Font', 'builder-timeline').'</h4>'),
			),
			array(
				'id' => 'font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'builder-timeline'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => array( '.module-timeline' )
			),
			array(
				'id' => 'font_color',
				'type' => 'color',
				'label' => __('Font Color', 'builder-timeline'),
				'class' => 'small',
				'prop' => 'color',
				'selector' => array( '.module-timeline' )
			),
			array(
				'id' => 'multi_font_size',
				'type' => 'multi',
				'label' => __('Font Size', 'builder-timeline'),
				'fields' => array(
					array(
						'id' => 'font_size',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'font-size',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'font_size_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-timeline')),
							array('value' => 'em', 'name' => __('em', 'builder-timeline'))
						)
					)
				)
			),
			array(
				'id' => 'multi_line_height',
				'type' => 'multi',
				'label' => __('Line Height', 'builder-timeline'),
				'fields' => array(
					array(
						'id' => 'line_height',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'line-height',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'line_height_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-timeline')),
							array('value' => 'em', 'name' => __('em', 'builder-timeline')),
							array('value' => '%', 'name' => __('%', 'builder-timeline'))
						)
					)
				)
			),
			array(
				'id' => 'text_align',
				'label' => __( 'Text Align', 'builder-timeline' ),
				'type' => 'radio',
				'meta' => array(
					array( 'value' => '', 'name' => __( 'Default', 'builder-timeline' ), 'selected' => true ),
					array( 'value' => 'left', 'name' => __( 'Left', 'builder-timeline' ) ),
					array( 'value' => 'center', 'name' => __( 'Center', 'builder-timeline' ) ),
					array( 'value' => 'right', 'name' => __( 'Right', 'builder-timeline' ) ),
					array( 'value' => 'justify', 'name' => __( 'Justify', 'builder-timeline' ) )
				),
				'prop' => 'text-align',
				'selector' => '.module-timeline'
			),
			// Link
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_link',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Link', 'builder-timeline').'</h4>'),
			),
			array(
				'id' => 'link_color',
				'type' => 'color',
				'label' => __('Color', 'builder-timeline'),
				'class' => 'small',
				'prop' => 'color',
				'selector' => '.module-timeline a'
			),
			array(
				'id' => 'text_decoration',
				'type' => 'select',
				'label' => __( 'Text Decoration', 'builder-timeline' ),
				'meta'	=> array(
					array('value' => '',   'name' => '', 'selected' => true),
					array('value' => 'underline',   'name' => __('Underline', 'builder-timeline')),
					array('value' => 'overline', 'name' => __('Overline', 'builder-timeline')),
					array('value' => 'line-through',  'name' => __('Line through', 'builder-timeline')),
					array('value' => 'none',  'name' => __('None', 'builder-timeline'))
				),
				'prop' => 'text-decoration',
				'selector' => '.module-timeline a'
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Padding', 'builder-timeline').'</h4>'),
			),
			array(
				'id' => 'multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding', 'builder-timeline'),
				'fields' => array(
					array(
						'id' => 'padding_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-top',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-timeline'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-timeline')),
							array('value' => '%', 'name' => __('%', 'builder-timeline'))
						)
					),
				)
			),
			array(
				'id' => 'multi_padding_right',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'padding_right',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-right',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-timeline'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-timeline')),
							array('value' => '%', 'name' => __('%', 'builder-timeline'))
						)
					),
				)
			),
			array(
				'id' => 'multi_padding_bottom',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'padding_bottom',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-bottom',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-timeline'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-timeline')),
							array('value' => '%', 'name' => __('%', 'builder-timeline'))
						)
					),
				)
			),
			array(
				'id' => 'multi_padding_left',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'padding_left',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-left',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-timeline'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-timeline')),
							array('value' => '%', 'name' => __('%', 'builder-timeline'))
						)
					),
				)
			),
			// Margin
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_margin',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Margin', 'builder-timeline').'</h4>'),
			),
			array(
				'id' => 'multi_margin_top',
				'type' => 'multi',
				'label' => __('Margin', 'builder-timeline'),
				'fields' => array(
					array(
						'id' => 'margin_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-top',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'margin_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-timeline'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-timeline')),
							array('value' => '%', 'name' => __('%', 'builder-timeline'))
						)
					),
				)
			),
			array(
				'id' => 'multi_margin_right',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'margin_right',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-right',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'margin_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-timeline'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-timeline')),
							array('value' => '%', 'name' => __('%', 'builder-timeline'))
						)
					),
				)
			),
			array(
				'id' => 'multi_margin_bottom',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'margin_bottom',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-bottom',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'margin_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-timeline'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-timeline')),
							array('value' => '%', 'name' => __('%', 'builder-timeline'))
						)
					),
				)
			),
			array(
				'id' => 'multi_margin_left',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'margin_left',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-left',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'margin_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-timeline'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-timeline')),
							array('value' => '%', 'name' => __('%', 'builder-timeline'))
						)
					),
				)
			),
			// Border
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_border',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Border', 'builder-timeline').'</h4>'),
			),
			array(
				'id' => 'multi_border_top',
				'type' => 'multi',
				'label' => __('Border', 'builder-timeline'),
				'fields' => array(
					array(
						'id' => 'border_top_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-top-color',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-top-width',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'border_top_style',
						'type' => 'select',
						'description' => __('top', 'builder-timeline'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-timeline' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-timeline' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-timeline' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-timeline' ) )
						),
						'prop' => 'border-top-style',
						'selector' => '.module-timeline'
					)
				)
			),
			array(
				'id' => 'multi_border_right',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'border_right_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-right-color',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'border_right_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall'
					),
					array(
						'id' => 'border_right_style',
						'type' => 'select',
						'description' => __('right', 'builder-timeline'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-timeline' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-timeline' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-timeline' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-timeline' ) )
						),
						'prop' => 'border-right-style',
						'selector' => '.module-timeline'
					)
				)
			),
			array(
				'id' => 'multi_border_bottom',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'border_bottom_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-bottom-color',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-bottom-width',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'builder-timeline'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-timeline' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-timeline' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-timeline' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-timeline' ) )
						),
						'prop' => 'border-bottom-style',
						'selector' => '.module-timeline'
					)
				)
			),
			array(
				'id' => 'multi_border_left',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'border_left_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-left-color',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-left-width',
						'selector' => '.module-timeline'
					),
					array(
						'id' => 'border_left_style',
						'type' => 'select',
						'description' => __('left', 'builder-timeline'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-timeline' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-timeline' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-timeline' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-timeline' ) )
						),
						'prop' => 'border-left-style',
						'selector' => '.module-timeline'
					)
				)
			),
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'add_css_timeline',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-timeline'),
				'class' => 'large exclude-from-reset-field',
				'description' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-timeline') )
			)
		);
	}
}

function themify_builder_field_timeline_source( $field, $mod_name ) {
	$options = array();
	foreach( Builder_Timeline::get_instance()->get_sources() as $key => $instance ) {
		$options[$key] = $instance->get_name();
	}
	themify_builder_module_settings_field( array(
		array(
			'id' => $field['id'],
			'type' => 'radio',
			'label' => __('Display', 'builder-timeline'),
			'options' => $options,
			'default' => 'post',
			'option_js' => true
		)
	), $mod_name );
}
function themify_builder_field_timeline_source_config( $field, $mod_name ) {
	foreach( Builder_Timeline::get_instance()->get_sources() as $key => $instance ) {
		themify_builder_module_settings_field( array(
			array(
				'id' => $key,
				'type' => 'group',
				'fields' => $instance->get_options(),
				'wrap_with_class' => 'tf-group-element tf-group-element-' . $key
			)
		), $mod_name );
	}
}

Themify_Builder_Model::register_module( 'TB_Timeline_Module' );