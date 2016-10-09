<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Fittext
 * Description: Display responsive heading
 */
class TB_Fittext_Module extends Themify_Builder_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __('FitText', 'builder-fittext'),
			'slug' => 'fittext'
		));
	}

	public function get_options() {
		return array(
			array(
				'id' => 'fittext_text',
				'type' => 'text',
				'label' => __('Text', 'builder-fittext'),
				'class' => 'fullwidth'
			),
			array(
				'id' => 'fittext_link',
				'type' => 'text',
				'label' => __('Link', 'builder-fittext'),
				'class' => 'fullwidth',
			),
			array(
				'id' => 'fittext_params',
				'type' => 'checkbox',
				'label' => false,
				'pushed' => 'pushed',
				'options' => array(
					array( 'name' => 'lightbox', 'value' => __('Open link in lightbox', 'builder-fittext') ),
					array( 'name' => 'newtab', 'value' => __('Open link in new tab', 'builder-fittext') )
				),
				'new_line' => false,
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
				'meta' => array('html'=>'<h4>'.__('Animation', 'builder-fittext').'</h4>'),
			),
			array(
				'id' => 'animation_effect',
				'type' => 'animation_select',
				'label' => __( 'Effect', 'builder-fittext' )
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
				'meta' => array('html'=>'<h4>'.__('Background', 'builder-fittext').'</h4>'),
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'label' => __('Background Color', 'builder-fittext'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => '.module-fittext'
			),
			// Font
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_font',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Font', 'builder-fittext').'</h4>'),
			),
			array(
				'id' => 'font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'builder-fittext'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => array( '.module-fittext', '.module-fittext span', '.module-fittext a' )
			),
			array(
				'id' => 'font_color',
				'type' => 'color',
				'label' => __('Font Color', 'builder-fittext'),
				'class' => 'small',
				'prop' => 'color',
				'selector' => array( '.module-fittext', '.module-fittext span', '.module-fittext a' )
			),
			array(
				'id' => 'text_align',
				'label' => __( 'Text Align', 'builder-fittext' ),
				'type' => 'radio',
				'meta' => array(
					array( 'value' => '', 'name' => __( 'Default', 'builder-fittext' ), 'selected' => true ),
					array( 'value' => 'left', 'name' => __( 'Left', 'builder-fittext' ) ),
					array( 'value' => 'center', 'name' => __( 'Center', 'builder-fittext' ) ),
					array( 'value' => 'right', 'name' => __( 'Right', 'builder-fittext' ) ),
					array( 'value' => 'justify', 'name' => __( 'Justify', 'builder-fittext' ) )
				),
				'prop' => 'text-align',
				'selector' => array( '.module-fittext', '.module-fittext span', '.module-fittext a' )
			),
			// Link
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_link',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Link', 'builder-fittext').'</h4>'),
			),
			array(
				'id' => 'text_decoration',
				'type' => 'select',
				'label' => __( 'Text Decoration', 'builder-fittext' ),
				'meta'	=> array(
					array('value' => '',   'name' => '', 'selected' => true),
					array('value' => 'underline',   'name' => __('Underline', 'builder-fittext')),
					array('value' => 'overline', 'name' => __('Overline', 'builder-fittext')),
					array('value' => 'line-through',  'name' => __('Line through', 'builder-fittext')),
					array('value' => 'none',  'name' => __('None', 'builder-fittext'))
				),
				'prop' => 'text-decoration',
				'selector' => '.module-fittext a'
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Padding', 'builder-fittext').'</h4>'),
			),
			array(
				'id' => 'multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding', 'builder-fittext'),
				'fields' => array(
					array(
						'id' => 'padding_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-top',
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-fittext'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-fittext')),
							array('value' => '%', 'name' => __('%', 'builder-fittext'))
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
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-fittext'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-fittext')),
							array('value' => '%', 'name' => __('%', 'builder-fittext'))
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
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-fittext'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-fittext')),
							array('value' => '%', 'name' => __('%', 'builder-fittext'))
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
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-fittext'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-fittext')),
							array('value' => '%', 'name' => __('%', 'builder-fittext'))
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
				'meta' => array('html'=>'<h4>'.__('Margin', 'builder-fittext').'</h4>'),
			),
			array(
				'id' => 'multi_margin_top',
				'type' => 'multi',
				'label' => __('Margin', 'builder-fittext'),
				'fields' => array(
					array(
						'id' => 'margin_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-top',
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'margin_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-fittext'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-fittext')),
							array('value' => '%', 'name' => __('%', 'builder-fittext'))
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
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'margin_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-fittext'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-fittext')),
							array('value' => '%', 'name' => __('%', 'builder-fittext'))
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
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'margin_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-fittext'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-fittext')),
							array('value' => '%', 'name' => __('%', 'builder-fittext'))
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
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'margin_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-fittext'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-fittext')),
							array('value' => '%', 'name' => __('%', 'builder-fittext'))
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
				'meta' => array('html'=>'<h4>'.__('Border', 'builder-fittext').'</h4>'),
			),
			array(
				'id' => 'multi_border_top',
				'type' => 'multi',
				'label' => __('Border', 'builder-fittext'),
				'fields' => array(
					array(
						'id' => 'border_top_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-top-color',
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-top-width',
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'border_top_style',
						'type' => 'select',
						'description' => __('top', 'builder-fittext'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-fittext' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-fittext' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-fittext' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-fittext' ) )
						),
						'prop' => 'border-top-style',
						'selector' => '.module-fittext'
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
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'border_right_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-right-width',
						'selector' => '.module-fittext',
					),
					array(
						'id' => 'border_right_style',
						'type' => 'select',
						'description' => __('right', 'builder-fittext'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-fittext' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-fittext' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-fittext' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-fittext' ) )
						),
						'prop' => 'border-right-style',
						'selector' => '.module-fittext'
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
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-bottom-width',
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'builder-fittext'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-fittext' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-fittext' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-fittext' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-fittext' ) )
						),
						'prop' => 'border-bottom-style',
						'selector' => '.module-fittext'
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
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-left-width',
						'selector' => '.module-fittext'
					),
					array(
						'id' => 'border_left_style',
						'type' => 'select',
						'description' => __('left', 'builder-fittext'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-fittext' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-fittext' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-fittext' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-fittext' ) )
						),
						'prop' => 'border-left-style',
						'selector' => '.module-fittext'
					)
				)
			),
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'add_css_fittext',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-fittext'),
				'class' => 'large exclude-from-reset-field',
				'description' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-fittext') )
			)
		);
	}
}

Themify_Builder_Model::register_module( 'TB_Fittext_Module' );