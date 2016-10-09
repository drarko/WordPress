<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Module Name: Button
 */
class TB_Button_Module extends Themify_Builder_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __('Button Pro', 'builder-button'),
			'slug' => 'button'
		));
	}

	public function get_options() {
		return array(
			array(
				'id' => 'link_label',
				'type' => 'text',
				'label' => __('Button Text', 'builder-button'),
				'class' => 'fullwidth',
			),
			array(
				'id' => 'link_button',
				'type' => 'text',
				'label' => __('Link', 'builder-button'),
				'class' => 'fullwidth',
				'wrap_with_class' => 'tf-group-element tf-group-element-external',
			),
			array(
				'id' => 'param_button',
				'type' => 'checkbox',
				'label' => false,
				'pushed' => 'pushed',
				'options' => array(
					array( 'name' => 'lightbox', 'value' => __('Open link in lightbox', 'builder-button') ),
					array( 'name' => 'newtab', 'value' => __('Open link in new tab', 'builder-button') )
				),
				'new_line' => false,
				'wrap_with_class' => 'tf-group-element tf-group-element-external',
			),
			array(
				'id' => 'icon_button',
				'type' => 'icon',
				'label' => __('Button Icon', 'builder-button'),
			),
			array(
				'id' => 'color_button',
				'type' => 'layout',
				'label' => __('Button Color', 'builder-button'),
				'options' => array(
					array('img' => 'color-black.png', 'value' => 'black', 'label' => __('black', 'builder-button')),
					array('img' => 'color-grey.png', 'value' => 'gray', 'label' => __('gray', 'builder-button')),
					array('img' => 'color-blue.png', 'value' => 'blue', 'label' => __('blue', 'builder-button')),
					array('img' => 'color-light-blue.png', 'value' => 'light-blue', 'label' => __('light-blue', 'builder-button')),
					array('img' => 'color-green.png', 'value' => 'green', 'label' => __('green', 'builder-button')),
					array('img' => 'color-light-green.png', 'value' => 'light-green', 'label' => __('light-green', 'builder-button')),
					array('img' => 'color-purple.png', 'value' => 'purple', 'label' => __('purple', 'builder-button')),
					array('img' => 'color-light-purple.png', 'value' => 'light-purple', 'label' => __('light-purple', 'builder-button')),
					array('img' => 'color-brown.png', 'value' => 'brown', 'label' => __('brown', 'builder-button')),
					array('img' => 'color-orange.png', 'value' => 'orange', 'label' => __('orange', 'builder-button')),
					array('img' => 'color-yellow.png', 'value' => 'yellow', 'label' => __('yellow', 'builder-button')),
					array('img' => 'color-red.png', 'value' => 'red', 'label' => __('red', 'builder-button')),
					array('img' => 'color-pink.png', 'value' => 'pink', 'label' => __('pink', 'builder-button')),
					array('img' => 'color-transparent.png', 'value' => 'default', 'label' => __('Default', 'builder-button'))
				)
			),
                        array(
				'id' => 'color_button_hover',
				'type' => 'layout',
				'label' => __('Button Hover Color', 'builder-button'),
				'options' => array(
					array('img' => 'color-black.png', 'value' => 'black', 'label' => __('black', 'builder-button')),
					array('img' => 'color-grey.png', 'value' => 'gray', 'label' => __('gray', 'builder-button')),
					array('img' => 'color-blue.png', 'value' => 'blue', 'label' => __('blue', 'builder-button')),
					array('img' => 'color-light-blue.png', 'value' => 'light-blue', 'label' => __('light-blue', 'builder-button')),
					array('img' => 'color-green.png', 'value' => 'green', 'label' => __('green', 'builder-button')),
					array('img' => 'color-light-green.png', 'value' => 'light-green', 'label' => __('light-green', 'builder-button')),
					array('img' => 'color-purple.png', 'value' => 'purple', 'label' => __('purple', 'builder-button')),
					array('img' => 'color-light-purple.png', 'value' => 'light-purple', 'label' => __('light-purple', 'builder-button')),
					array('img' => 'color-brown.png', 'value' => 'brown', 'label' => __('brown', 'builder-button')),
					array('img' => 'color-orange.png', 'value' => 'orange', 'label' => __('orange', 'builder-button')),
					array('img' => 'color-yellow.png', 'value' => 'yellow', 'label' => __('yellow', 'builder-button')),
					array('img' => 'color-red.png', 'value' => 'red', 'label' => __('red', 'builder-button')),
					array('img' => 'color-pink.png', 'value' => 'pink', 'label' => __('pink', 'builder-button')),
					array('img' => 'color-transparent.png', 'value' => 'default', 'label' => __('Default', 'builder-button'))
				)
			),
			array(
				'id' => 'appearance_button',
				'type' => 'checkbox',
				'label' => __('Appearance', 'builder-button'),
				'default' => array(
					'rounded', 
					'gradient'
				),
				'options' => array(
					array( 'name' => 'rounded', 'value' => __('Rounded', 'builder-button')),
					array( 'name' => 'gradient', 'value' => __('Gradient', 'builder-button')),
					array( 'name' => 'glossy', 'value' => __('Glossy', 'builder-button')),
					array( 'name' => 'embossed', 'value' => __('Embossed', 'builder-button')),
					array( 'name' => 'shadow', 'value' => __('Shadow', 'builder-button'))
				)
			),
			array(
				'id' => 'type_button',
				'type' => 'radio',
				'label' => __('Type', 'builder-button'),
				'options' => array(
					'external' => __('External Link', 'builder-button'),
					'row_scroll' => __('Scroll to next row (it will scroll to the next Builder row)', 'builder-button'),
					'modules_reveal' => __('Show more/less (it will hide/show all modules after)', 'builder-button'),
					'modal' => __('Text modal (it will open text content in a lightbox)', 'builder-button'),
				),
				'default' => 'external',
				'option_js' => true
			),
			array(
				'id' => 'content_modal_button',
				'type' => 'wp_editor',
				'class' => 'fullwidth',
				'wrap_with_class' => 'tf-group-element tf-group-element-modal',
			),
			array(
				'id' 		=> 'modules_reveal_behavior_button',
				'label'		=> __('After click', 'builder-button'),
				'type' 		=> 'select',
				'default'	=> '',
				'options' => array(
					'toggle' => __('Toggle the less button', 'builder-button'),
					'hide' => __('Hide the button', 'builder-button'),
				),
				'binding' => array(
					'toggle' => array(
						'show' => array( 'show_less_label_button' )
					),
					'hide' => array(
						'hide' => array( 'show_less_label_button' )
					)
				),
				'wrap_with_class' => 'tf-group-element tf-group-element-modules_reveal',
			),
			array(
				'id' => 'show_less_label_button',
				'type' => 'text',
				'label' => __('Less button text', 'builder-button'),
				'class' => 'fullwidth',
				'wrap_with_class' => 'tf-group-element tf-group-element-modules_reveal',
			),
		);
	}

	public function get_animation() {
		return array(
			array(
				'id' => 'animation_effect',
				'type' => 'animation_select',
				'label' => __( 'Effect', 'builder-button' )
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
				'meta' => array('html'=>'<h4>'.__('Background', 'builder-button').'</h4>'),
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'label' => __('Background Color', 'builder-button'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => '.module-button a'
			),
			// Font
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_font',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Font', 'builder-button').'</h4>'),
			),
			array(
				'id' => 'font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'builder-button'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => array( '.module-button a' )
			),
			array(
				'id' => 'font_color',
				'type' => 'color',
				'label' => __('Font Color', 'builder-button'),
				'class' => 'small',
				'prop' => 'color',
				'selector' => array( '.module-button a' )
			),
			array(
				'id' => 'multi_font_size',
				'type' => 'multi',
				'label' => __('Font Size', 'builder-button'),
				'fields' => array(
					array(
						'id' => 'font_size',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'font-size',
						'selector' => '.module-button a'
					),
					array(
						'id' => 'font_size_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-button')),
							array('value' => 'em', 'name' => __('em', 'builder-button'))
						)
					)
				)
			),
			array(
				'id' => 'multi_line_height',
				'type' => 'multi',
				'label' => __('Line Height', 'builder-button'),
				'fields' => array(
					array(
						'id' => 'line_height',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'line-height',
						'selector' => '.module-button a'
					),
					array(
						'id' => 'line_height_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-button')),
							array('value' => 'em', 'name' => __('em', 'builder-button')),
							array('value' => '%', 'name' => __('%', 'builder-button'))
						)
					)
				)
			),
			array(
				'id' => 'text_align',
				'label' => __( 'Text Align', 'builder-button' ),
				'type' => 'radio',
				'meta' => array(
					array( 'value' => '', 'name' => __( 'Default', 'builder-button' ), 'selected' => true ),
					array( 'value' => 'left', 'name' => __( 'Left', 'builder-button' ) ),
					array( 'value' => 'center', 'name' => __( 'Center', 'builder-button' ) ),
					array( 'value' => 'right', 'name' => __( 'Right', 'builder-button' ) ),
					array( 'value' => 'justify', 'name' => __( 'Justify', 'builder-button' ) )
				),
				'prop' => 'text-align',
				'selector' => '.module-button'
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Padding', 'builder-button').'</h4>'),
			),
			array(
				'id' => 'multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding', 'builder-button'),
				'fields' => array(
					array(
						'id' => 'padding_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-top',
						'selector' => '.module-button a'
					),
					array(
						'id' => 'padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-button'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-button')),
							array('value' => '%', 'name' => __('%', 'builder-button'))
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
						'selector' => '.module-button a'
					),
					array(
						'id' => 'padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-button'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-button')),
							array('value' => '%', 'name' => __('%', 'builder-button'))
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
						'selector' => '.module-button a'
					),
					array(
						'id' => 'padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-button'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-button')),
							array('value' => '%', 'name' => __('%', 'builder-button'))
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
						'selector' => '.module-button a'
					),
					array(
						'id' => 'padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-button'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-button')),
							array('value' => '%', 'name' => __('%', 'builder-button'))
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
				'meta' => array('html'=>'<h4>'.__('Margin', 'builder-button').'</h4>'),
			),
			array(
				'id' => 'multi_margin_top',
				'type' => 'multi',
				'label' => __('Margin', 'builder-button'),
				'fields' => array(
					array(
						'id' => 'margin_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-top',
						'selector' => '.module-button a'
					),
					array(
						'id' => 'margin_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-button'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-button')),
							array('value' => '%', 'name' => __('%', 'builder-button'))
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
						'selector' => '.module-button a'
					),
					array(
						'id' => 'margin_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-button'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-button')),
							array('value' => '%', 'name' => __('%', 'builder-button'))
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
						'selector' => '.module-button a'
					),
					array(
						'id' => 'margin_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-button'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-button')),
							array('value' => '%', 'name' => __('%', 'builder-button'))
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
						'selector' => '.module-button a'
					),
					array(
						'id' => 'margin_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-button'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-button')),
							array('value' => '%', 'name' => __('%', 'builder-button'))
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
				'meta' => array('html'=>'<h4>'.__('Border', 'builder-button').'</h4>'),
			),
			array(
				'id' => 'multi_border_top',
				'type' => 'multi',
				'label' => __('Border', 'builder-button'),
				'fields' => array(
					array(
						'id' => 'border_top_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-top-color',
						'selector' => '.module-button a'
					),
					array(
						'id' => 'border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-top-width',
						'selector' => '.module-button a'
					),
					array(
						'id' => 'border_top_style',
						'type' => 'select',
						'description' => __('top', 'builder-button'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-button' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-button' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-button' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-button' ) )
						),
						'prop' => 'border-top-style',
						'selector' => '.module-button a'
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
						'selector' => '.module-button a'
					),
					array(
						'id' => 'border_right_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-right-width',
						'selector' => '.module-button a'
					),
					array(
						'id' => 'border_right_style',
						'type' => 'select',
						'description' => __('right', 'builder-button'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-button' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-button' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-button' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-button' ) )
						),
						'prop' => 'border-right-style',
						'selector' => '.module-button a'
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
						'selector' => '.module-button a'
					),
					array(
						'id' => 'border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-bottom-width',
						'selector' => '.module-button a'
					),
					array(
						'id' => 'border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'builder-button'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-button' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-button' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-button' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-button' ) )
						),
						'prop' => 'border-bottom-style',
						'selector' => '.module-button a'
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
						'selector' => '.module-button a'
					),
					array(
						'id' => 'border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-left-width',
						'selector' => '.module-button a'
					),
					array(
						'id' => 'border_left_style',
						'type' => 'select',
						'description' => __('left', 'builder-button'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-button' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-button' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-button' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-button' ) )
						),
						'prop' => 'border-left-style',
						'selector' => '.module-button a'
					)
				)
			),
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'add_css_button',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-button'),
				'class' => 'large exclude-from-reset-field',
				'description' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-button') )
			)
		);
	}
}

Themify_Builder_Model::register_module( 'TB_Button_Module' );

/**
 * Safely add "fa-" prefix to the icon name
 *
 * @since 1.0.6
 */
function builder_button_get_fa_icon_classname( $icon ) {
	if( ! ( substr( $icon, 0, 3 ) == 'fa-' ) ) {
		$icon = 'fa-' . $icon;
	}

	return $icon;
}