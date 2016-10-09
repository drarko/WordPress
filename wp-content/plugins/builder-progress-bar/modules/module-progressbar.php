<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Progress Bar
 */
class TB_ProgressBar_Module extends Themify_Builder_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __('Progress Bar', 'builder-progressbar'),
			'slug' => 'progressbar'
		));
	}

	public function get_options() {
		return array(
			array(
				'id' => 'mod_title_progressbar',
				'type' => 'text',
				'label' => __('Module Title', 'builder-progressbar'),
				'class' => 'large'
			),
			array(
				'id' => 'progress_bars',
				'type' => 'builder',
				'options' => array(
					array(
						'id' => 'bar_label',
						'type' => 'text',
						'label' => __('Label', 'builder-progressbar'),
						'class' => 'large'
					),
					array(
						'id' => 'bar_percentage',
						'type' => 'text',
						'label' => __('Percentage', 'builder-progressbar'),
						'after' => '%',
						'class' => 'small'
					),
					array(
						'id' => 'bar_color',
						'type' => 'text',
						'colorpicker' => true,
						'label' => __('Color', 'builder-progressbar'),
						'class' => 'small'
					),
				)
			),
			array(
				'id' => 'hide_percentage_text',
				'type' => 'select',
				'label' => __('Hide Percentage Text', 'builder-progressbar'),
				'options' => array(
					'no' => __('No', 'builder-progressbar'),
					'yes' => __('Yes', 'builder-progressbar'),
				)
			),
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'add_css_progressbar',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-progressbar'),
				'class' => 'large exclude-from-reset-field',
				'help' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-progressbar') )
			)
		);
	}

	public function get_animation() {
		return array(
			array(
				'id' => 'animation_effect',
				'type' => 'animation_select',
				'label' => __( 'Effect', 'builder-countdown' ),
				'class' => ''
			)
		);
	}

	public function get_styling() {
		return array(
			array(
				'id' => 'separator_image_background',
				'title' => '',
				'description' => '',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Background', 'builder-progressbar').'</h4>'),
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'label' => __('Background Color', 'builder-progressbar'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => '.module-progressbar'
			),
			// Font
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_font',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Font', 'builder-progressbar').'</h4>'),
			),
			array(
				'id' => 'font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'builder-progressbar'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => array( '.module-progressbar' )
			),
			array(
				'id' => 'font_color',
				'type' => 'color',
				'label' => __('Font Color', 'builder-progressbar'),
				'class' => 'small',
				'prop' => 'color',
				'selector' => array( '.module-progressbar' )
			),
			array(
				'id' => 'multi_font_size',
				'type' => 'multi',
				'label' => __('Font Size', 'builder-progressbar'),
				'fields' => array(
					array(
						'id' => 'font_size',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'font-size',
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'font_size_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-progressbar')),
							array('value' => 'em', 'name' => __('em', 'builder-progressbar'))
						)
					)
				)
			),
			array(
				'id' => 'multi_line_height',
				'type' => 'multi',
				'label' => __('Line Height', 'builder-progressbar'),
				'fields' => array(
					array(
						'id' => 'line_height',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'line-height',
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'line_height_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-progressbar')),
							array('value' => 'em', 'name' => __('em', 'builder-progressbar')),
							array('value' => '%', 'name' => __('%', 'builder-progressbar'))
						)
					)
				)
			),
			array(
				'id' => 'text_align',
				'label' => __( 'Text Align', 'builder-progressbar' ),
				'type' => 'radio',
				'meta' => array(
					array( 'value' => '', 'name' => __( 'Default', 'builder-progressbar' ), 'selected' => true ),
					array( 'value' => 'left', 'name' => __( 'Left', 'builder-progressbar' ) ),
					array( 'value' => 'center', 'name' => __( 'Center', 'builder-progressbar' ) ),
					array( 'value' => 'right', 'name' => __( 'Right', 'builder-progressbar' ) ),
					array( 'value' => 'justify', 'name' => __( 'Justify', 'builder-progressbar' ) )
				),
				'prop' => 'text-align',
				'selector' => '.module-progressbar'
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Padding', 'builder-progressbar').'</h4>'),
			),
			array(
				'id' => 'multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding', 'builder-progressbar'),
				'fields' => array(
					array(
						'id' => 'padding_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-top',
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-progressbar'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-progressbar')),
							array('value' => '%', 'name' => __('%', 'builder-progressbar'))
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
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-progressbar'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-progressbar')),
							array('value' => '%', 'name' => __('%', 'builder-progressbar'))
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
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-progressbar'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-progressbar')),
							array('value' => '%', 'name' => __('%', 'builder-progressbar'))
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
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-progressbar'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-progressbar')),
							array('value' => '%', 'name' => __('%', 'builder-progressbar'))
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
				'meta' => array('html'=>'<h4>'.__('Margin', 'builder-progressbar').'</h4>'),
			),
			array(
				'id' => 'multi_margin_top',
				'type' => 'multi',
				'label' => __('Margin', 'builder-progressbar'),
				'fields' => array(
					array(
						'id' => 'margin_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-top',
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'margin_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-progressbar'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-progressbar')),
							array('value' => '%', 'name' => __('%', 'builder-progressbar'))
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
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'margin_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-progressbar'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-progressbar')),
							array('value' => '%', 'name' => __('%', 'builder-progressbar'))
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
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'margin_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-progressbar'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-progressbar')),
							array('value' => '%', 'name' => __('%', 'builder-progressbar'))
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
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'margin_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-progressbar'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-progressbar')),
							array('value' => '%', 'name' => __('%', 'builder-progressbar'))
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
				'meta' => array('html'=>'<h4>'.__('Border', 'builder-progressbar').'</h4>'),
			),
			array(
				'id' => 'multi_border_top',
				'type' => 'multi',
				'label' => __('Border', 'builder-progressbar'),
				'fields' => array(
					array(
						'id' => 'border_top_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-top-color',
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-top-width',
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'border_top_style',
						'type' => 'select',
						'description' => __('top', 'builder-progressbar'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-progressbar' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-progressbar' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-progressbar' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-progressbar' ) )
						),
						'prop' => 'border-top-style',
						'selector' => '.module-progressbar'
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
						'selector' => '.module-progressbar'
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
						'description' => __('right', 'builder-progressbar'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-progressbar' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-progressbar' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-progressbar' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-progressbar' ) )
						),
						'prop' => 'border-right-style',
						'selector' => '.module-progressbar'
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
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-bottom-width',
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'builder-progressbar'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-progressbar' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-progressbar' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-progressbar' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-progressbar' ) )
						),
						'prop' => 'border-bottom-style',
						'selector' => '.module-progressbar'
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
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-left-width',
						'selector' => '.module-progressbar'
					),
					array(
						'id' => 'border_left_style',
						'type' => 'select',
						'description' => __('left', 'builder-progressbar'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-progressbar' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-progressbar' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-progressbar' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-progressbar' ) )
						),
						'prop' => 'border-left-style',
						'selector' => '.module-progressbar'
					)
				)
			)
		);
	}
}

Themify_Builder_Model::register_module( 'TB_ProgressBar_Module' );