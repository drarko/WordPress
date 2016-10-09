<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Pointers
 */
class TB_Pointers_Module extends Themify_Builder_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __('Pointers', 'builder-pointers'),
			'slug' => 'pointers'
		));
	}

	public function get_options() {
		return array(
			array(
				'id' => 'mod_title',
				'type' => 'text',
				'label' => __('Module Title', 'builder-pointers'),
				'class' => 'large'
			),
			array(
				'id' => 'image_url',
				'type' => 'image',
				'label' => __('Image URL', 'builder-pointers'),
				'class' => 'xlarge'
			),
			array(
				'id' => 'title_image',
				'type' => 'text',
				'label' => __('Image Alt', 'builder-pointers'),
				'class' => 'fullwidth',
				'after' => '<small>' . __( 'Optional: Image alt is the image "alt" attribute. Primarily used for SEO describing the image.', 'builder-pointers' ) . '</small>'
			),
			array(
				'id' => 'image_dimension',
				'type' => 'multi',
				'label' => __( 'Image Dimension', 'builder-pointers' ),
				'fields' => array(
					array(
						'id' => 'image_width',
						'type' => 'text',
						'label' => __('Width', 'builder-pointers'),
						'class' => 'medium',
						'help' => 'px',
						'value' => 300
					),
					array(
						'id' => 'image_height',
						'type' => 'text',
						'label' => __('Height', 'builder-pointers'),
						'class' => 'medium',
						'help' => 'px',
						'value' => 200
					),
				)
			),
			array(
				'type' => 'pointers'
			),
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'css_class',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-pointers'),
				'class' => 'large exclude-from-reset-field',
				'help' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-pointers') )
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
				'meta' => array('html'=>'<h4>'.__('Background', 'builder-pointers').'</h4>'),
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'label' => __('Background Color', 'builder-pointers'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => '.module-pointers'
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Padding', 'builder-pointers').'</h4>'),
			),
			array(
				'id' => 'multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding', 'builder-pointers'),
				'fields' => array(
					array(
						'id' => 'padding_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-top',
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-pointers'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-pointers')),
							array('value' => '%', 'name' => __('%', 'builder-pointers'))
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
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-pointers'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-pointers')),
							array('value' => '%', 'name' => __('%', 'builder-pointers'))
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
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-pointers'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-pointers')),
							array('value' => '%', 'name' => __('%', 'builder-pointers'))
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
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-pointers'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-pointers')),
							array('value' => '%', 'name' => __('%', 'builder-pointers'))
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
				'meta' => array('html'=>'<h4>'.__('Margin', 'builder-pointers').'</h4>'),
			),
			array(
				'id' => 'multi_margin_top',
				'type' => 'multi',
				'label' => __('Margin', 'builder-pointers'),
				'fields' => array(
					array(
						'id' => 'margin_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-top',
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'margin_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-pointers'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-pointers')),
							array('value' => '%', 'name' => __('%', 'builder-pointers'))
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
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'margin_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-pointers'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-pointers')),
							array('value' => '%', 'name' => __('%', 'builder-pointers'))
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
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'margin_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-pointers'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-pointers')),
							array('value' => '%', 'name' => __('%', 'builder-pointers'))
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
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'margin_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-pointers'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-pointers')),
							array('value' => '%', 'name' => __('%', 'builder-pointers'))
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
				'meta' => array('html'=>'<h4>'.__('Border', 'builder-pointers').'</h4>'),
			),
			array(
				'id' => 'multi_border_top',
				'type' => 'multi',
				'label' => __('Border', 'builder-pointers'),
				'fields' => array(
					array(
						'id' => 'border_top_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-top-color',
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
						'prop' => 'border-top-width',
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'border_top_style',
						'type' => 'select',
						'description' => __('top', 'builder-pointers'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-pointers' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-pointers' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-pointers' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-pointers' ) )
						),
						'prop' => 'border-top-style',
						'selector' => '.module-pointers'
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
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'border_right_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
					),
					array(
						'id' => 'border_right_style',
						'type' => 'select',
						'description' => __('right', 'builder-pointers'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-pointers' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-pointers' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-pointers' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-pointers' ) )
						),
						'prop' => 'border-right-style',
						'selector' => '.module-pointers'
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
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
						'prop' => 'border-bottom-width',
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'builder-pointers'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-pointers' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-pointers' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-pointers' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-pointers' ) )
						),
						'prop' => 'border-bottom-style',
						'selector' => '.module-pointers'
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
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
						'prop' => 'border-left-width',
						'selector' => '.module-pointers'
					),
					array(
						'id' => 'border_left_style',
						'type' => 'select',
						'description' => __('left', 'builder-pointers'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-pointers' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-pointers' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-pointers' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-pointers' ) )
						),
						'prop' => 'border-left-style',
						'selector' => '.module-pointers'
					)
				)
			)
		);
	}
}

function themify_builder_field_pointers( $field, $module_name ) {
	echo '<div id="pointers">';
	echo '<p class="description">'. __( 'Click on the image to add tooltips (click tooltip points to edit again).', 'builder-pointers' ) .'</p>';

	echo '<div id="pointers-preview"><div class="loading"><i class="fa fa-gear fa-spin"></i></div></div>';

	themify_builder_module_settings_field( array(
		array(
			'id' => 'blobs_showcase',
			'type' => 'builder',
			'options' => array(
				array(
					'id' => 'title',
					'type' => 'textarea',
					'label' => __('Text', 'builder-pointers'),
					'class' => 'large'
				),
				array(
					'id' => 'direction',
					'type' => 'select',
					'label' => __('Tooltip Direction', 'builder-pointers'),
					'options' => array(
						'bottom' => __('Bottom', 'builder-pointers'),
						'top' => __('Top', 'builder-pointers'),
						'left' => __('Left', 'builder-pointers'),
						'right' => __('Right', 'builder-pointers'),
					),
					'default' => 'bottom'
				),
                                array(
					'id' => 'pointer_hide',
					'type' => 'select',
					'label' => __('Hide Pointer', 'builder-pointers'),
					'options' => array(
						'no' => __('No', 'builder-pointers'),
						'yes' => __('Yes', 'builder-pointers'),
					),
					'default' => 'no'
				),
				array(
					'id' => 'auto_visible',
					'type' => 'select',
					'label' => __('Always Visible', 'builder-pointers'),
					'options' => array(
						'no' => __('No', 'builder-pointers'),
						'yes' => __('Yes', 'builder-pointers'),
					),
					'default' => 'no'
				),
				array(
					'id' => 'pointer_color',
					'type' => 'text',
					'colorpicker' => true,
					'class' => 'large',
					'label' => __('Pointer Color', 'builder-pointers'),
				),
				array(
					'id' => 'tooltip_background',
					'type' => 'text',
					'colorpicker' => true,
					'class' => 'large',
					'label' => __('Tooltip Background', 'builder-pointers'),
				),
				array(
					'id' => 'tooltip_color',
					'type' => 'text',
					'colorpicker' => true,
					'class' => 'large',
					'label' => __('Tooltip Text Color', 'builder-pointers'),
				),
                                array(
					'id' => 'open',
					'type' => 'select',
					'label' => __('Open link in', 'builder-pointers'),
					'options' => array(
						'blank' => __('New Window', 'builder-pointers'),
						'' => __('Same Window', 'builder-pointers'),
						'lightbox' => __('Lightbox', 'builder-pointers'),
					),
					'default' => 'blank'
				),
				array(
					'id' => 'link',
					'type' => 'text',
					'label' => __('Link To', 'builder-pointers'),
					'class' => 'large',
				),
				array(
					'id' => 'left',
					'type' => 'text',
					'label' => __('Left', 'builder-pointers'),
					'class' => 'large',
					'wrap_with_class' => 'hide-if-js',
				),
				array(
					'id' => 'top',
					'type' => 'text',
					'label' => __('Top', 'builder-pointers'),
					'class' => 'large',
					'wrap_with_class' => 'hide-if-js',
				),
			)
		),
	), $module_name );
	echo '</div>';
}

Themify_Builder_Model::register_module( 'TB_Pointers_Module' );