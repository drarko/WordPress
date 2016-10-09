<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Audio
 */
class TB_Audio_Module extends Themify_Builder_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __('Audio', 'builder-audio'),
			'slug' => 'audio'
		));
	}

	public function get_options() {
		return array(
			array(
				'id' => 'mod_title_playlist',
				'type' => 'text',
				'label' => __('Module Title', 'builder-audio'),
				'class' => 'large'
			),
			array(
				'id' => 'music_playlist',
				'type' => 'builder',
				'options' => array(
					array(
						'id' => 'audio_name',
						'type' => 'text',
						'label' => __('Audio Name', 'builder-audio'),
						'class' => 'large'
					),
					array(
						'id' => 'audio_url',
						'type' => 'audio',
						'label' => __('Audio File URL', 'builder-audio'),
						'class' => 'xlarge'
					),
				)
			),
			array(
				'id' => 'hide_download_audio',
				'type' => 'select',
				'label' => __('Hide Download Link', 'builder-audio'),
				'options' => array(
					'yes' => __('Yes', 'builder-audio'),
					'no' => __('No', 'builder-audio'),
				)
			)
                        ,
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'add_css_audio',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-audio'),
				'class' => 'large exclude-from-reset-field',
				'help' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-audio') )
			)
		);
	}

	public function get_animation() {
		return array(
			array(
				'id' => 'animation_effect',
				'type' => 'animation_select',
				'label' => __( 'Effect', 'builder-audio' )
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
				'meta' => array('html'=>'<h4>'.__('Background', 'builder-audio').'</h4>'),
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'label' => __('Background Color', 'builder-audio'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => '.module-audio'
			),
			// Font
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_font',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Font', 'builder-audio').'</h4>'),
			),
			array(
				'id' => 'font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'builder-audio'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => array( '.module-audio' )
			),
			array(
				'id' => 'font_color',
				'type' => 'color',
				'label' => __('Font Color', 'builder-audio'),
				'class' => 'small',
				'prop' => 'color',
				'selector' => array( '.module-audio', '.module-audio a', '.module-audio .album-playlist .mejs-controls .mejs-playpause-button button' )
			),
			array(
				'id' => 'multi_font_size',
				'type' => 'multi',
				'label' => __('Font Size', 'builder-audio'),
				'fields' => array(
					array(
						'id' => 'font_size',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'font-size',
						'selector' => '.module-audio'
					),
					array(
						'id' => 'font_size_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-audio')),
							array('value' => 'em', 'name' => __('em', 'builder-audio'))
						)
					)
				)
			),
			array(
				'id' => 'multi_line_height',
				'type' => 'multi',
				'label' => __('Line Height', 'builder-audio'),
				'fields' => array(
					array(
						'id' => 'line_height',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'line-height',
						'selector' => '.module-audio'
					),
					array(
						'id' => 'line_height_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-audio')),
							array('value' => 'em', 'name' => __('em', 'builder-audio')),
							array('value' => '%', 'name' => __('%', 'builder-audio'))
						)
					)
				)
			),
			array(
				'id' => 'text_align',
				'label' => __( 'Text Align', 'builder-audio' ),
				'type' => 'radio',
				'meta' => array(
					array( 'value' => '', 'name' => __( 'Default', 'builder-audio' ), 'selected' => true ),
					array( 'value' => 'left', 'name' => __( 'Left', 'builder-audio' ) ),
					array( 'value' => 'center', 'name' => __( 'Center', 'builder-audio' ) ),
					array( 'value' => 'right', 'name' => __( 'Right', 'builder-audio' ) ),
					array( 'value' => 'justify', 'name' => __( 'Justify', 'builder-audio' ) )
				),
				'prop' => 'text-align',
				'selector' => '.module-audio'
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Padding', 'builder-audio').'</h4>'),
			),
			array(
				'id' => 'multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding', 'builder-audio'),
				'fields' => array(
					array(
						'id' => 'padding_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-top',
						'selector' => '.module-audio'
					),
					array(
						'id' => 'padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-audio'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-audio')),
							array('value' => '%', 'name' => __('%', 'builder-audio'))
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
						'selector' => '.module-audio'
					),
					array(
						'id' => 'padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-audio'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-audio')),
							array('value' => '%', 'name' => __('%', 'builder-audio'))
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
						'selector' => '.module-audio'
					),
					array(
						'id' => 'padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-audio'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-audio')),
							array('value' => '%', 'name' => __('%', 'builder-audio'))
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
						'selector' => '.module-audio'
					),
					array(
						'id' => 'padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-audio'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-audio')),
							array('value' => '%', 'name' => __('%', 'builder-audio'))
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
				'meta' => array('html'=>'<h4>'.__('Margin', 'builder-audio').'</h4>'),
			),
			array(
				'id' => 'multi_margin_top',
				'type' => 'multi',
				'label' => __('Margin', 'builder-audio'),
				'fields' => array(
					array(
						'id' => 'margin_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-top',
						'selector' => '.module-audio'
					),
					array(
						'id' => 'margin_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-audio'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-audio')),
							array('value' => '%', 'name' => __('%', 'builder-audio'))
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
						'selector' => '.module-audio'
					),
					array(
						'id' => 'margin_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-audio'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-audio')),
							array('value' => '%', 'name' => __('%', 'builder-audio'))
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
						'selector' => '.module-audio'
					),
					array(
						'id' => 'margin_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-audio'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-audio')),
							array('value' => '%', 'name' => __('%', 'builder-audio'))
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
						'selector' => '.module-audio'
					),
					array(
						'id' => 'margin_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-audio'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-audio')),
							array('value' => '%', 'name' => __('%', 'builder-audio'))
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
				'meta' => array('html'=>'<h4>'.__('Border', 'builder-audio').'</h4>'),
			),
			array(
				'id' => 'multi_border_top',
				'type' => 'multi',
				'label' => __('Border', 'builder-audio'),
				'fields' => array(
					array(
						'id' => 'border_top_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-top-color',
						'selector' => '.module-audio'
					),
					array(
						'id' => 'border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-top-width',
						'selector' => '.module-audio'
					),
					array(
						'id' => 'border_top_style',
						'type' => 'select',
						'description' => __('top', 'builder-audio'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-audio' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-audio' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-audio' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-audio' ) )
						),
						'prop' => 'border-top-style',
						'selector' => '.module-audio'
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
						'selector' => '.module-audio'
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
						'description' => __('right', 'builder-audio'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-audio' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-audio' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-audio' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-audio' ) )
						),
						'prop' => 'border-right-style',
						'selector' => '.module-audio'
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
						'selector' => '.module-audio'
					),
					array(
						'id' => 'border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-bottom-width',
						'selector' => '.module-audio'
					),
					array(
						'id' => 'border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'builder-audio'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-audio' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-audio' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-audio' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-audio' ) )
						),
						'prop' => 'border-bottom-style',
						'selector' => '.module-audio'
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
						'selector' => '.module-audio'
					),
					array(
						'id' => 'border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'xsmall',
						'prop' => 'border-left-width',
						'selector' => '.module-audio'
					),
					array(
						'id' => 'border_left_style',
						'type' => 'select',
						'description' => __('left', 'builder-audio'),
						'meta' => array(
							array( 'value' => '', 'name' => '' ),
							array( 'value' => 'solid', 'name' => __( 'Solid', 'builder-audio' ) ),
							array( 'value' => 'dashed', 'name' => __( 'Dashed', 'builder-audio' ) ),
							array( 'value' => 'dotted', 'name' => __( 'Dotted', 'builder-audio' ) ),
							array( 'value' => 'double', 'name' => __( 'Double', 'builder-audio' ) )
						),
						'prop' => 'border-left-style',
						'selector' => '.module-audio'
					)
				)
			)
		);
	}
}

Themify_Builder_Model::register_module( 'TB_Audio_Module' );