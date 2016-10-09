<?php

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
/**
 * Module Name: Bar Chart
 * Description: Display Bar Chart content
 */

class TB_Bar_Chart_Module extends Themify_Builder_Module {

	function __construct() {
		parent::__construct( array(
			'name' => __( 'Bar Chart', 'builder-bar-chart' ),
			'slug' => 'bar-chart'
		) );
	}

	public function get_options() {
		$options = array(
			array(
				'id' => 'mod_title_bar_chart',
				'type' => 'text',
				'label' => __('Module Title', 'builder-bar-chart'),
				'class' => 'large'
			),
			array(
				'id' => 'mod_height_bar_chart',
				'type' => 'text',
				'label' => __('Chart Height', 'builder-bar-chart'),
				'class' => 'xsmall',
				'unit' => array(
					'id' => 'unit_w',
					'selected' => '%',
					'options' => array(
						array('id' => 'pixel_unit_w', 'value' => 'px'),
					)
				),
				'value' => 300
			),
			array(
				'id' => 'content_bar_chart',
				'type' => 'builder',
				'options' => array(
					array(
						'id' => 'bar_chart_label',
						'type' => 'text',
						'label' => __('Bar Label', 'builder-bar-chart'),
						'class' => 'large'
					),
					array(
						'id' => 'bar_chart_percentage',
						'type' => 'text',
						'label' => __('Bar Height (%)', 'builder-bar-chart'),
						'class' => 'large'
					),
					array(
						'id' => 'bar_chart_number',
						'type' => 'text',
						'label' => __('Bar Number/Text', 'builder-bar-chart'),
						'class' => 'large'
					),
					array(
						'id' => 'bar_chart_color',
						'type' => 'text',
						'colorpicker' => true,
						'label' => __('Bar color', 'builder-bar-chart'),
						'class' => 'small'
					)
				),
				'new_row_text' => __( 'Add New Bar', 'builder-bar-chart' )
			),
			array(
				'id' => 'label_direction_chart',
				'type' => 'select',
				'label' => __('Label Direction', 'builder-bar-chart'),
				'options' => array(
					'horizontal' => __('Horizontal', 'builder-bar-chart'),
					'vertical' => __('Vertical', 'builder-bar-chart'),
				),
			)
                        ,
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array('html' => '<hr/>')
			),
			array(
				'id' => 'css_bar_chart',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-bar-chart'),
				'class' => 'large exclude-from-reset-field',
				'help' => sprintf('<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-bar-chart'))
			)
		);

		return $options;
	}

	public function get_animation() {
		return array(
			array(
				'id' => 'animation_effect',
				'type' => 'animation_select',
				'label' => __('Effect', 'builder-bar-chart'),
				'class' => ''
			)
		);
	}

	public function get_styling() {
		$styling = array(
			array(
				'id' => 'separator_image_background',
				'title' => '',
				'description' => '',
				'type' => 'separator',
				'meta' => array('html' => '<h4>' . __('Background', 'builder-bar-chart') . '</h4>'),
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'label' => __('Background Color', 'builder-bar-chart'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => array('.module-bar-chart')
			),
			// Font
			array(
				'type' => 'separator',
				'meta' => array('html' => '<hr />')
			),
			array(
				'id' => 'separator_font',
				'type' => 'separator',
				'meta' => array('html' => '<h4>' . __('Font', 'builder-bar-chart') . '</h4>'),
			),
			array(
				'id' => 'font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'builder-bar-chart'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => '.module-bar-chart',
			),
			array(
				'id' => 'font_color',
				'type' => 'color',
				'label' => __('Font Color', 'builder-bar-chart'),
				'class' => 'small',
				'prop' => 'color',
				'selector' => array('.module-bar-chart', '.module-bar-chart h1', '.module-bar-chart h2', '.module-bar-chart h3', '.module-bar-chart h4', '.module-bar-chart h5', '.module-bar-chart h6'),
			),
			array(
				'id' => 'multi_font_size',
				'type' => 'multi',
				'label' => __('Font Size', 'builder-bar-chart'),
				'fields' => array(
					array(
						'id' => 'font_size',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'font-size',
						'selector' => '.module-bar-chart',
					),
					array(
						'id' => 'font_size_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-bar-chart')),
							array('value' => 'em', 'name' => __('em', 'builder-bar-chart'))
						)
					)
				)
			),
			array(
				'id' => 'multi_line_height',
				'type' => 'multi',
				'label' => __('Line Height', 'builder-bar-chart'),
				'fields' => array(
					array(
						'id' => 'line_height',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'line-height',
						'selector' => '.module-bar-chart',
					),
					array(
						'id' => 'line_height_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-bar-chart')),
							array('value' => 'em', 'name' => __('em', 'builder-bar-chart')),
							array('value' => '%', 'name' => __('%', 'builder-bar-chart'))
						)
					)
				)
			),
			array(
				'id' => 'text_align',
				'label' => __('Text Align', 'builder-bar-chart'),
				'type' => 'radio',
				'meta' => array(
					array('value' => '', 'name' => __('Default', 'builder-bar-chart'), 'selected' => true),
					array('value' => 'left', 'name' => __('Left', 'builder-bar-chart')),
					array('value' => 'center', 'name' => __('Center', 'builder-bar-chart')),
					array('value' => 'right', 'name' => __('Right', 'builder-bar-chart')),
					array('value' => 'justify', 'name' => __('Justify', 'builder-bar-chart'))
				),
				'prop' => 'text-align',
				'selector' => '.module-bar-chart',
			),
			array(
				'id' => 'text_decoration',
				'type' => 'select',
				'label' => __('Text Decoration', 'builder-bar-chart'),
				'meta' => array(
					array('value' => '', 'name' => '', 'selected' => true),
					array('value' => 'underline', 'name' => __('Underline', 'builder-bar-chart')),
					array('value' => 'overline', 'name' => __('Overline', 'builder-bar-chart')),
					array('value' => 'line-through', 'name' => __('Line through', 'builder-bar-chart')),
					array('value' => 'none', 'name' => __('None', 'builder-bar-chart'))
				),
				'prop' => 'text-decoration',
				'selector' => '.module-bar-chart a'
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html' => '<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html' => '<h4>' . __('Padding', 'builder-bar-chart') . '</h4>'),
			),
			array(
				'id' => 'multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding', 'builder-bar-chart'),
				'fields' => array(
					array(
						'id' => 'padding_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-top',
						'selector' => array( '.module-bar-chart' )
					),
					array(
						'id' => 'padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-bar-chart'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-bar-chart')),
							array('value' => '%', 'name' => __('%', 'builder-bar-chart'))
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
						'selector' => array( '.module-bar-chart' )
					),
					array(
						'id' => 'padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-bar-chart'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-bar-chart')),
							array('value' => '%', 'name' => __('%', 'builder-bar-chart'))
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
						'selector' => array('.module-bar-chart')
					),
					array(
						'id' => 'padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-bar-chart'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-bar-chart')),
							array('value' => '%', 'name' => __('%', 'builder-bar-chart'))
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
						'selector' => array('.module-bar-chart')
					),
					array(
						'id' => 'padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-bar-chart'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-bar-chart')),
							array('value' => '%', 'name' => __('%', 'builder-bar-chart'))
						)
					),
				)
			),
			// Margin
			array(
				'type' => 'separator',
				'meta' => array('html' => '<hr />')
			),
			array(
				'id' => 'separator_margin',
				'type' => 'separator',
				'meta' => array('html' => '<h4>' . __('Margin', 'builder-bar-chart') . '</h4>'),
			),
			array(
				'id' => 'multi_margin_top',
				'type' => 'multi',
				'label' => __('Margin', 'builder-bar-chart'),
				'fields' => array(
					array(
						'id' => 'margin_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-top',
						'selector' => '.module-bar-chart',
					),
					array(
						'id' => 'margin_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-bar-chart'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-bar-chart')),
							array('value' => '%', 'name' => __('%', 'builder-bar-chart'))
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
						'selector' => '.module-bar-chart',
					),
					array(
						'id' => 'margin_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-bar-chart'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-bar-chart')),
							array('value' => '%', 'name' => __('%', 'builder-bar-chart'))
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
						'selector' => '.module-bar-chart',
					),
					array(
						'id' => 'margin_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-bar-chart'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-bar-chart')),
							array('value' => '%', 'name' => __('%', 'builder-bar-chart'))
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
						'selector' => '.module-bar-chart',
					),
					array(
						'id' => 'margin_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-bar-chart'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-bar-chart')),
							array('value' => '%', 'name' => __('%', 'builder-bar-chart'))
						)
					),
				)
			),
			// Border
			array(
				'type' => 'separator',
				'meta' => array('html' => '<hr />')
			),
			array(
				'id' => 'separator_border',
				'type' => 'separator',
				'meta' => array('html' => '<h4>' . __('Border', 'builder-bar-chart') . '</h4>'),
			),
			array(
				'id' => 'multi_border_top',
				'type' => 'multi',
				'label' => __('Border', 'builder-bar-chart'),
				'fields' => array(
					array(
						'id' => 'border_top_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-top-color',
						'selector' => '.module-bar-chart'
					),
					array(
						'id' => 'border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-top-width',
						'selector' => '.module-bar-chart'
					),
					array(
						'id' => 'border_top_style',
						'type' => 'select',
						'description' => __('top', 'builder-bar-chart'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-top-style',
						'selector' => '.module-bar-chart'
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
						'selector' => '.module-bar-chart'
					),
					array(
						'id' => 'border_right_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-right-width',
						'selector' => '.module-bar-chart'
					),
					array(
						'id' => 'border_right_style',
						'type' => 'select',
						'description' => __('right', 'builder-bar-chart'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-right-style',
						'selector' => '.module-bar-chart'
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
						'selector' => '.module-bar-chart'
					),
					array(
						'id' => 'border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-bottom-width',
						'selector' => '.module-bar-chart'
					),
					array(
						'id' => 'border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'builder-bar-chart'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-bottom-style',
						'selector' => '.module-bar-chart'
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
						'selector' => '.module-bar-chart'
					),
					array(
						'id' => 'border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-left-width',
						'selector' => '.module-bar-chart'
					),
					array(
						'id' => 'border_left_style',
						'type' => 'select',
						'description' => __('left', 'builder-bar-chart'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-left-style',
						'selector' => '.module-bar-chart'
					)
				)
			)
		);
		return $styling;
	}
}

///////////////////////////////////////
// Module Options
///////////////////////////////////////
Themify_Builder_Model::register_module( 'TB_Bar_Chart_Module' );