<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Countdown
 */
class TB_Countdown_Module extends Themify_Builder_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __('Countdown', 'builder-countdown'),
			'slug' => 'countdown'
		));
	}

	public function get_options() {
		return array(
			array(
				'id' => 'mod_title_countdown',
				'type' => 'text',
				'label' => __('Module Title', 'builder-countdown'),
				'class' => 'large'
			),
			array(
				'id' => 'mod_date_countdown',
				'type' => 'text',
				'label' => __('Date', 'builder-countdown'),
				'class' => 'large',
				'wrap_with_class' => 'builder-countdown-datepicker'
			),
			array(
				'id' => 'color_countdown',
				'type' => 'layout',
				'label' => __('Color', 'builder-countdown'),
				'options' => array(
					array('img' => 'color-default.png', 'value' => 'default', 'label' => __('default', 'builder-countdown')),
					array('img' => 'color-black.png', 'value' => 'black', 'label' => __('black', 'builder-countdown')),
					array('img' => 'color-grey.png', 'value' => 'gray', 'label' => __('gray', 'builder-countdown')),
					array('img' => 'color-blue.png', 'value' => 'blue', 'label' => __('blue', 'builder-countdown')),
					array('img' => 'color-light-blue.png', 'value' => 'light-blue', 'label' => __('light-blue', 'builder-countdown')),
					array('img' => 'color-green.png', 'value' => 'green', 'label' => __('green', 'builder-countdown')),
					array('img' => 'color-light-green.png', 'value' => 'light-green', 'label' => __('light-green', 'builder-countdown')),
					array('img' => 'color-purple.png', 'value' => 'purple', 'label' => __('purple', 'builder-countdown')),
					array('img' => 'color-light-purple.png', 'value' => 'light-purple', 'label' => __('light-purple', 'builder-countdown')),
					array('img' => 'color-brown.png', 'value' => 'brown', 'label' => __('brown', 'builder-countdown')),
					array('img' => 'color-orange.png', 'value' => 'orange', 'label' => __('orange', 'builder-countdown')),
					array('img' => 'color-yellow.png', 'value' => 'yellow', 'label' => __('yellow', 'builder-countdown')),
					array('img' => 'color-red.png', 'value' => 'red', 'label' => __('red', 'builder-countdown')),
					array('img' => 'color-pink.png', 'value' => 'pink', 'label' => __('pink', 'builder-countdown')),
					array('img' => 'color-transparent.png', 'value' => 'transparent', 'label' => __('Transparent', 'builder-countdown'))
				),
				'bottom' => true,
			),
			array(
				'id' => 'counter_background_color',
				'type' => 'text',
				'colorpicker' => true,
				'label' => __('Custom Color', 'builder-countdown'),
				'class' => 'small'
			),
			array(
				'id' => 'done_action_countdown',
				'type' => 'radio',
				'label' => __('Finish Action', 'builder-countdown'),
				'options' => array(
					'nothing' => __('Do nothing', 'builder-countdown'),
					'redirect' => __('Redirect to an external URL', 'builder-countdown'),
					'revealo' => __('Show content', 'builder-countdown'),
				),
				'default' => 'nothing',
				'option_js' => true
			),
			array(
				'id' => 'content_countdown',
				'type' => 'wp_editor',
				'class' => 'fullwidth',
				'wrap_with_class' => 'tf-group-element tf-group-element-revealo',
			),
			array(
				'id' => 'redirect_countdown',
				'type' => 'text',
				'label' => __('External Link', 'builder-countdown'),
				'class' => 'fullwidth',
				'after' => __( '<div class="description">Note: the redirect will not occur for website administrators.</div>', 'builder-countdown' ),
				'wrap_with_class' => 'tf-group-element tf-group-element-redirect',
			),
			array(
				'id' => 'custom_labels',
				'type' => 'multi',
				'label' => __('Labels', 'builder-countdown'),
				'fields' => array(
					array(
						'id' => 'label_seconds',
						'type' => 'text',
						'label' => __('Seconds', 'themify'),
					),
					array(
						'id' => 'label_minutes',
						'type' => 'text',
						'label' => __('Minutes', 'themify'),
					),
					array(
						'id' => 'label_hours',
						'type' => 'text',
						'label' => __('Hours', 'themify'),
					),
					array(
						'id' => 'label_days',
						'type' => 'text',
						'label' => __('Days', 'themify'),
					),
				)
			),
		);
	}

	public function get_styling() {
		$module_styling = array(
			// Animation
			array(
				'id' => 'separator_animation',
				'title' => '',
				'description' => '',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Animation', 'builder-countdown').'</h4>'),
			),
			array(
				'id' => 'animation_effect',
				'type' => 'animation_select',
				'label' => __( 'Effect', 'builder-countdown' ),
				'class' => ''
			),
			array(
				'id' => 'separator_image_background',
				'title' => '',
				'description' => '',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Background', 'builder-countdown').'</h4>'),
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'label' => __('Background Color', 'builder-countdown'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => '.module-countdown'
			),
			// Font
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_font',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Font', 'builder-countdown').'</h4>'),
			),
			array(
				'id' => 'font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'builder-countdown'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => array( '.module-countdown', '.module-countdown .builder-countdown-holder .ui' )
			),
			array(
				'id' => 'font_color',
				'type' => 'color',
				'label' => __('Font Color', 'builder-countdown'),
				'class' => 'small',
				'prop' => 'color',
				'selector' => array( '.module-countdown', '.module-countdown .ui' )
			),
			array(
				'id' => 'multi_font_size',
				'type' => 'multi',
				'label' => __('Font Size', 'builder-countdown'),
				'fields' => array(
					array(
						'id' => 'font_size',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'font-size',
						'selector' => array( '.module-countdown' )
					),
					array(
						'id' => 'font_size_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-countdown')),
							array('value' => 'em', 'name' => __('em', 'builder-countdown'))
						)
					)
				)
			),
			array(
				'id' => 'multi_line_height',
				'type' => 'multi',
				'label' => __('Line Height', 'builder-countdown'),
				'fields' => array(
					array(
						'id' => 'line_height',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'line-height',
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'line_height_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-countdown')),
							array('value' => 'em', 'name' => __('em', 'builder-countdown')),
							array('value' => '%', 'name' => __('%', 'builder-countdown'))
						)
					)
				)
			),
			array(
				'id' => 'text_align',
				'label' => __( 'Text Align', 'builder-countdown' ),
				'type' => 'radio',
				'meta' => array(
					array( 'value' => '', 'name' => __( 'Default', 'builder-countdown' ), 'selected' => true ),
					array( 'value' => 'left', 'name' => __( 'Left', 'builder-countdown' ) ),
					array( 'value' => 'center', 'name' => __( 'Center', 'builder-countdown' ) ),
					array( 'value' => 'right', 'name' => __( 'Right', 'builder-countdown' ) ),
					array( 'value' => 'justify', 'name' => __( 'Justify', 'builder-countdown' ) )
				),
				'prop' => 'text-align',
				'selector' => '.module-countdown'
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Padding', 'builder-countdown').'</h4>'),
			),
			array(
				'id' => 'multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding', 'builder-countdown'),
				'fields' => array(
					array(
						'id' => 'padding_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-top',
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-countdown'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-countdown')),
							array('value' => '%', 'name' => __('%', 'builder-countdown'))
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
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-countdown'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-countdown')),
							array('value' => '%', 'name' => __('%', 'builder-countdown'))
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
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-countdown'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-countdown')),
							array('value' => '%', 'name' => __('%', 'builder-countdown'))
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
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-countdown'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-countdown')),
							array('value' => '%', 'name' => __('%', 'builder-countdown'))
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
				'meta' => array('html'=>'<h4>'.__('Margin', 'builder-countdown').'</h4>'),
			),
			array(
				'id' => 'multi_margin_top',
				'type' => 'multi',
				'label' => __('Margin', 'builder-countdown'),
				'fields' => array(
					array(
						'id' => 'margin_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-top',
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'margin_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-countdown'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-countdown')),
							array('value' => '%', 'name' => __('%', 'builder-countdown'))
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
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'margin_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-countdown'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-countdown')),
							array('value' => '%', 'name' => __('%', 'builder-countdown'))
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
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'margin_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-countdown'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-countdown')),
							array('value' => '%', 'name' => __('%', 'builder-countdown'))
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
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'margin_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-countdown'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-countdown')),
							array('value' => '%', 'name' => __('%', 'builder-countdown'))
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
				'meta' => array('html'=>'<h4>'.__('Border', 'builder-countdown').'</h4>'),
			),
			array(
				'id' => 'multi_border_top',
				'type' => 'multi',
				'label' => __('Border', 'builder-countdown'),
				'fields' => array(
					array(
						'id' => 'border_top_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-top-color',
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-top-width',
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'border_top_style',
						'type' => 'select',
						'description' => __('top', 'builder-countdown'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-countdown' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-countdown' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-countdown' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-countdown' ) )
						),
						'prop' => 'border-top-style',
						'selector' => '.module-countdown'
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
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'border_right_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-right-width',
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'border_right_style',
						'type' => 'select',
						'description' => __('right', 'builder-countdown'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-countdown' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-countdown' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-countdown' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-countdown' ) )
						),
						'prop' => 'border-right-style',
						'selector' => '.module-countdown'
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
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-bottom-width',
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'builder-countdown'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-countdown' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-countdown' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-countdown' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-countdown' ) )
						),
						'prop' => 'border-bottom-style',
						'selector' => '.module-countdown'
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
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-left-width',
						'selector' => '.module-countdown'
					),
					array(
						'id' => 'border_left_style',
						'type' => 'select',
						'description' => __('left', 'builder-countdown'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-countdown' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-countdown' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-countdown' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-countdown' ) )
						),
						'prop' => 'border-left-style',
						'selector' => '.module-countdown'
					)
				)
			),
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'add_css_countdown',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-countdown'),
				'description' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-countdown') ),
				'class' => 'large exclude-from-reset-field'
			)
		);

		return $module_styling;
	}
}

Themify_Builder_Model::register_module( 'TB_Countdown_Module' );