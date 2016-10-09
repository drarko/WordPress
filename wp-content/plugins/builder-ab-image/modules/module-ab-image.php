<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Module Name: A/B Image
 */
class TB_AB_Image_Module extends Themify_Builder_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __('A/B Image', 'builder-ab-image'),
			'slug' => 'ab-image'
		));
	}

	public function get_options() {
		$image_sizes = themify_get_image_sizes_list( false );

		return array(
			array(
				'id' => 'mod_title_image_compare',
				'type' => 'text',
				'label' => __('Module Title', 'builder-ab-image'),
				'class' => 'large'
			),
			array(
				'id' => 'url_image_a',
				'type' => 'image',
				'label' => __('Image URL', 'builder-ab-image'),
				'class' => 'xlarge'
			),
			array(
				'id' => 'url_image_b',
				'type' => 'image',
				'label' => __('Second Image URL', 'builder-ab-image'),
				'class' => 'xlarge'
			),
			array(
				'id' => 'title_image',
				'type' => 'text',
				'label' => __('Image Alt', 'builder-ab-image'),
				'class' => 'fullwidth',
				'after' => '<small>' . __( 'Optional: Image alt is the image "alt" attribute. Primarily used for SEO describing the image.', 'builder-ab-image' ) . '</small>'
			),
			array(
				'id' => 'image_size_image_compare',
				'type' => 'select',
				'label' => Themify_Builder_Model::is_img_php_disabled() ? __('Image Size', 'builder-ab-image') : false,
				'empty' => array(
					'val' => '',
					'label' => ''
				),
				'hide' => Themify_Builder_Model::is_img_php_disabled() ? false : true,
				'options' => $image_sizes
			),
			array(
				'id' => 'width_image_compare',
				'type' => 'text',
				'label' => __('Width', 'builder-ab-image'),
				'class' => 'xsmall',
				'help' => 'px',
				'value' => 300
			),
			array(
				'id' => 'height_image_compare',
				'type' => 'text',
				'label' => __('Height', 'builder-ab-image'),
				'class' => 'xsmall',
				'help' => 'px',
				'value' => 200
			),
			array(
				'id' => 'orientation_compare',
				'type' => 'select',
				'label' => __('Orientation', 'builder-ab-image'),
				'options' => array(
					'horizontal' => __('Horizontal', 'builder-ab-image'),
					'vertical' => __('Vertical', 'builder-ab-image'),
				)
			)
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
				'meta' => array('html'=>'<h4>'.__('Animation', 'builder-ab-image').'</h4>'),
			),
			array(
				'id' => 'animation_effect',
				'type' => 'animation_select',
				'label' => __( 'Effect', 'builder-ab-image' )
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
				'meta' => array('html'=>'<h4>'.__('Background', 'builder-ab-image').'</h4>'),
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'label' => __('Background Color', 'builder-ab-image'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => '.module-ab-image'
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Padding', 'builder-ab-image').'</h4>'),
			),
			array(
				'id' => 'multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding', 'builder-ab-image'),
				'fields' => array(
					array(
						'id' => 'padding_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-top',
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-ab-image'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-ab-image')),
							array('value' => '%', 'name' => __('%', 'builder-ab-image'))
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
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-ab-image'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-ab-image')),
							array('value' => '%', 'name' => __('%', 'builder-ab-image'))
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
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-ab-image'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-ab-image')),
							array('value' => '%', 'name' => __('%', 'builder-ab-image'))
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
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-ab-image'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-ab-image')),
							array('value' => '%', 'name' => __('%', 'builder-ab-image'))
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
				'meta' => array('html'=>'<h4>'.__('Margin', 'builder-ab-image').'</h4>'),
			),
			array(
				'id' => 'multi_margin_top',
				'type' => 'multi',
				'label' => __('Margin', 'builder-ab-image'),
				'fields' => array(
					array(
						'id' => 'margin_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-top',
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'margin_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-ab-image'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-ab-image')),
							array('value' => '%', 'name' => __('%', 'builder-ab-image'))
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
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'margin_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-ab-image'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-ab-image')),
							array('value' => '%', 'name' => __('%', 'builder-ab-image'))
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
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'margin_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-ab-image'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-ab-image')),
							array('value' => '%', 'name' => __('%', 'builder-ab-image'))
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
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'margin_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-ab-image'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-ab-image')),
							array('value' => '%', 'name' => __('%', 'builder-ab-image'))
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
				'meta' => array('html'=>'<h4>'.__('Border', 'builder-ab-image').'</h4>'),
			),
			array(
				'id' => 'multi_border_top',
				'type' => 'multi',
				'label' => __('Border', 'builder-ab-image'),
				'fields' => array(
					array(
						'id' => 'border_top_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-top-color',
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-top-width',
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'border_top_style',
						'type' => 'select',
						'description' => __('top', 'builder-ab-image'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-ab-image' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-ab-image' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-ab-image' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-ab-image' ) )
						),
						'prop' => 'border-top-style',
						'selector' => '.module-ab-image'
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
						'selector' => '.module-ab-image'
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
						'description' => __('right', 'builder-ab-image'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-ab-image' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-ab-image' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-ab-image' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-ab-image' ) )
						),
						'prop' => 'border-right-style',
						'selector' => '.module-ab-image'
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
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-bottom-width',
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'builder-ab-image'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-ab-image' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-ab-image' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-ab-image' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-ab-image' ) )
						),
						'prop' => 'border-bottom-style',
						'selector' => '.module-ab-image'
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
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-left-width',
						'selector' => '.module-ab-image'
					),
					array(
						'id' => 'border_left_style',
						'type' => 'select',
						'description' => __('left', 'builder-ab-image'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-ab-image' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-ab-image' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-ab-image' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-ab-image' ) )
						),
						'prop' => 'border-left-style',
						'selector' => '.module-ab-image'
					)
				)
			),
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'css_ab_image',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-ab-image'),
				'class' => 'large exclude-from-reset-field',
				'description' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-ab-image') )
			)
		);
	}
}

Themify_Builder_Model::register_module( 'TB_AB_Image_Module' );