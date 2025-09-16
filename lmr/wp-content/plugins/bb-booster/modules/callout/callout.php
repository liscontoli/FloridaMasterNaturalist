<?php

/**
 * @class FLCalloutModule
 */
class FLCalloutModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Callout', 'bb-booster' ),
			'description'     => __( 'A heading and snippet of text with an optional link, icon and image.', 'bb-booster' ),
			'category'        => __( 'Actions', 'bb-booster' ),
			'partial_refresh' => true,
			'icon'            => 'megaphone.svg',
		));
	}

	/**
	 * Ensure backwards compatibility with old settings.
	 *
	 * @since 2.2
	 * @param object $settings A module settings object.
	 * @param object $helper A settings compatibility helper.
	 * @return object
	 */
	public function filter_settings( $settings, $helper ) {

		// Make sure we have a typography array.
		if ( ! isset( $settings->typography ) || ! is_array( $settings->typography ) ) {
			$settings->typography = array();
		}

		// Handle old title size settings.
		if ( isset( $settings->title_size ) && 'custom' === $settings->title_size ) {
			$settings->typography['font_size'] = array(
				'length' => $settings->title_custom_size,
				'unit'   => 'px',
			);
			unset( $settings->title_size );
			unset( $settings->title_custom_size );
		}

		// Handle old button module settings.
		$helper->filter_child_module_settings( 'button', $settings, array(
			'btn_3d'                 => 'three_d',
			'btn_style'              => 'style',
			'btn_padding'            => 'padding',
			'btn_padding_top'        => 'padding_top',
			'btn_padding_bottom'     => 'padding_bottom',
			'btn_padding_left'       => 'padding_left',
			'btn_padding_right'      => 'padding_right',
			'btn_mobile_align'       => 'mobile_align',
			'btn_align_responsive'   => 'align_responsive',
			'btn_font_size'          => 'font_size',
			'btn_font_size_unit'     => 'font_size_unit',
			'btn_typography'         => 'typography',
			'btn_bg_color'           => 'bg_color',
			'btn_bg_hover_color'     => 'bg_hover_color',
			'btn_bg_opacity'         => 'bg_opacity',
			'btn_bg_hover_opacity'   => 'bg_hover_opacity',
			'btn_border'             => 'border',
			'btn_border_hover_color' => 'border_hover_color',
			'btn_border_radius'      => 'border_radius',
			'btn_border_size'        => 'border_size',
		) );

		// Return the filtered settings.
		return $settings;
	}

	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings ) {
		// Cache the photo data.
		$settings->photo_data = FLBuilderPhoto::get_attachment_data( $settings->photo );

		return $settings;
	}

	/**
	 * @method delete
	 */
	public function delete() {
		// Delete photo module cache.
		if ( 'photo' == $this->settings->image_type && ! empty( $this->settings->photo_src ) ) {
			$module_class                         = get_class( FLBuilderModel::$modules['photo'] );
			$photo_module                         = new $module_class();
			$photo_module->settings               = new stdClass();
			$photo_module->settings->photo_source = 'library';
			$photo_module->settings->photo_src    = $this->settings->photo_src;
			$photo_module->settings->crop         = $this->settings->photo_crop;
			$photo_module->delete();
		}
	}

	/**
	 * @method get_classname
	 */
	public function get_classname() {
		$classname = 'fl-callout fl-callout-' . $this->settings->align;

		if ( 'photo' == $this->settings->image_type ) {
			$classname .= ' fl-callout-has-photo fl-callout-photo-' . $this->settings->photo_position;
		} elseif ( 'icon' == $this->settings->image_type ) {
			$classname .= ' fl-callout-has-icon fl-callout-icon-' . $this->settings->icon_position;
		}

		return $classname;
	}

	/**
	 * @method render_title
	 */
	public function render_title() {
		echo '<' . $this->settings->title_tag . ' class="fl-callout-title">';

		$nofollow = ( ( 'yes' === $this->settings->link_nofollow ) ? 'rel="nofollow"' : '' );

		if ( ! empty( $this->settings->link ) && 'icon' === $this->settings->image_type ) {
			echo '<a href="' . $this->settings->link . '" target="' . $this->settings->link_target . '" ' . $nofollow . '  class="fl-callout-title-link fl-callout-title-text">';
		}

		if ( ! empty( $this->settings->link ) && 'icon' !== $this->settings->image_type ) {
			echo '<a href="' . $this->settings->link . '" target="' . $this->settings->link_target . '" ' . $nofollow . ' class="fl-callout-title-link fl-callout-title-text">';
		}

		if ( 'left-title' === $this->settings->icon_position ) {
			$this->render_image( 'left-title' );
		}

		echo '<span' . ( empty( $this->settings->link ) ? ' class="fl-callout-title-text"' : '' ) . '>';
		echo $this->settings->title;
		echo '</span>';

		if ( 'right-title' === $this->settings->icon_position ) {
			$this->render_image( 'right-title' );
		}

		if ( ! empty( $this->settings->link ) ) {
			echo '</a>';
		}

		echo '</' . $this->settings->title_tag . '>';
	}

	/**
	 * @method render_text
	 */
	public function render_text() {
		global $wp_embed;

		echo '<div class="fl-callout-text">' . wpautop( $wp_embed->autoembed( $this->settings->text ) ) . '</div>';
	}

	/**
	 * @method render_link
	 */
	public function render_link() {
		if ( 'link' == $this->settings->cta_type ) {
			$nofollow = '';

			if ( 'yes' == $this->settings->link_nofollow ) {
				$nofollow = 'rel="nofollow"';
			}

			echo '<a href="' . $this->settings->link . '" ' . $nofollow . ' target="' . $this->settings->link_target . '" class="fl-callout-cta-link">' . $this->settings->cta_text . '</a>';
		}
	}

	/**
	 * Returns an array of settings used to render a button module.
	 *
	 * @since 2.2
	 * @return array
	 */
	public function get_button_settings() {
		$settings = array(
			'link'          => $this->settings->link,
			'link_target'   => $this->settings->link_target,
			'link_nofollow' => $this->settings->link_nofollow,
			'text'          => $this->settings->cta_text,
		);

		foreach ( $this->settings as $key => $value ) {
			if ( strstr( $key, 'btn_' ) ) {
				$key              = str_replace( 'btn_', '', $key );
				$settings[ $key ] = $value;
			}
		}

		return $settings;
	}

	/**
	 * @method render_button
	 */
	public function render_button() {
		if ( 'button' == $this->settings->cta_type ) {
			echo '<div class="fl-callout-button">';
			FLBuilder::render_module_html( 'button', $this->get_button_settings() );
			echo '</div>';
		}
	}

	/**
	 * Returns an array of settings used to render an icon module.
	 *
	 * @since 2.2
	 * @return array
	 */
	public function get_icon_settings() {
		$settings = array(
			'id'              => $this->node,
			'align'           => '',
			'exclude_wrapper' => true,
			'icon'            => $this->settings->icon,
			'text'            => '',
			'three_d'         => $this->settings->icon_3d,
			'sr_text'         => $this->settings->sr_text,
			'link'            => $this->settings->link,
		);

		if ( isset( $this->settings->icon_position ) && ( 'left-title' === $this->settings->icon_position || 'right-title' === $this->settings->icon_position ) ) {
			unset( $settings['link'] );
		}

		foreach ( $this->settings as $key => $value ) {
			if ( strstr( $key, 'icon_' ) ) {
				$key              = str_replace( 'icon_', '', $key );
				$settings[ $key ] = $value;
			}
		}

		return $settings;
	}

	/**
	 * Returns an array of settings used to render a photo module.
	 *
	 * @since 2.2
	 * @return array
	 */
	public function get_photo_settings() {
		$photo_data = FLBuilderPhoto::get_attachment_data( $this->settings->photo );

		if ( ! $photo_data && isset( $this->settings->photo_data ) ) {
			$photo_data = $this->settings->photo_data;
		} elseif ( ! $photo_data ) {
			$photo_data = -1;
		}

		$settings = array(
			'link_url_target' => $this->settings->link_target,
			'link_nofollow'   => $this->settings->link_nofollow,
			'link_type'       => 'url',
			'link_url'        => $this->settings->link,
			'photo'           => $photo_data,
			'photo_src'       => $this->settings->photo_src,
			'photo_source'    => 'library',
		);

		foreach ( $this->settings as $key => $value ) {
			if ( strstr( $key, 'photo_' ) ) {
				$key              = str_replace( 'photo_', '', $key );
				$settings[ $key ] = $value;
			}
		}

		return $settings;
	}

	/**
	 * @method render_image
	 */
	public function render_image( $position ) {
		if ( 'photo' == $this->settings->image_type && $this->settings->photo_position == $position ) {
			if ( empty( $this->settings->photo ) ) {
				return;
			}
			echo '<div class="fl-callout-photo">';
			FLBuilder::render_module_html( 'photo', $this->get_photo_settings() );
			echo '</div>';
		} elseif ( 'icon' == $this->settings->image_type && $this->settings->icon_position == $position ) {
			FLBuilder::render_module_html( 'icon', $this->get_icon_settings() );
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('FLCalloutModule', array(
	'general' => array(
		'title'    => __( 'General', 'bb-booster' ),
		'sections' => array(
			'title' => array(
				'title'  => '',
				'fields' => array(
					'title'     => array(
						'type'        => 'text',
						'label'       => __( 'Heading', 'bb-booster' ),
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.fl-callout-title-text',
						),
						'connections' => array( 'string' ),
					),
					'title_tag' => array(
						'type'    => 'select',
						'label'   => __( 'Heading Tag', 'bb-booster' ),
						'default' => 'h3',
						'options' => array(
							'h1' => 'h1',
							'h2' => 'h2',
							'h3' => 'h3',
							'h4' => 'h4',
							'h5' => 'h5',
							'h6' => 'h6',
						),
					),
				),
			),
			'text'  => array(
				'title'  => __( 'Text', 'bb-booster' ),
				'fields' => array(
					'text' => array(
						'type'          => 'editor',
						'label'         => '',
						'media_buttons' => false,
						'wpautop'       => false,
						'preview'       => array(
							'type'     => 'text',
							'selector' => '.fl-callout-text',
						),
						'connections'   => array( 'string' ),
					),
				),
			),
		),
	),
	'style'   => array(
		'title'    => __( 'Style', 'bb-booster' ),
		'sections' => array(
			'overall_structure' => array(
				'title'  => '',
				'fields' => array(
					'bg_color' => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Background Color', 'bb-booster' ),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.fl-module-content',
							'property' => 'background-color',
						),
					),
					'border'   => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'bb-booster' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-module-content',
						),
					),
					'align'    => array(
						'type'    => 'align',
						'label'   => __( 'Alignment', 'bb-booster' ),
						'default' => 'left',
						'help'    => __( 'The alignment that will apply to all elements within the callout.', 'bb-booster' ),
						'preview' => array(
							'type' => 'none',
						),
					),
					'padding'  => array(
						'type'       => 'dimension',
						'label'      => __( 'Padding', 'bb-booster' ),
						'default'    => '',
						'responsive' => true,
						'slider'     => true,
						'units'      => array(
							'px',
							'em',
							'%',
						),
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-module-content',
							'property' => 'padding',
						),
					),
				),
			),
			'title_structure'   => array(
				'title'  => __( 'Heading', 'bb-booster' ),
				'fields' => array(
					'title_color'      => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Heading Color', 'bb-booster' ),
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.fl-callout-title, .fl-callout-title-text, .fl-callout-title-text:hover',
							'property' => 'color',
						),
					),
					'title_typography' => array(
						'type'       => 'typography',
						'label'      => __( 'Heading Typography', 'bb-booster' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-callout-title',
						),
					),
				),
			),
			'content'           => array(
				'title'  => __( 'Text', 'bb-booster' ),
				'fields' => array(
					'content_color'      => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Text Color', 'bb-booster' ),
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.fl-callout-text, .fl-callout-cta-link',
							'property' => 'color',
						),
					),
					'content_typography' => array(
						'type'       => 'typography',
						'label'      => __( 'Text Typography', 'bb-booster' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-callout-text, .fl-callout-cta-link',
						),
					),
				),
			),
		),
	),
	'image'   => array(
		'title'    => __( 'Image', 'bb-booster' ),
		'sections' => array(
			'general'        => array(
				'title'  => '',
				'fields' => array(
					'image_type' => array(
						'type'    => 'select',
						'label'   => __( 'Image Type', 'bb-booster' ),
						'default' => 'photo',
						'options' => array(
							'none'  => _x( 'None', 'Image type.', 'bb-booster' ),
							'photo' => __( 'Photo', 'bb-booster' ),
							'icon'  => __( 'Icon', 'bb-booster' ),
						),
						'toggle'  => array(
							'none'  => array(),
							'photo' => array(
								'sections' => array( 'photo', 'photo_style' ),
							),
							'icon'  => array(
								'sections' => array( 'icon', 'icon_colors', 'icon_structure' ),
							),
						),
					),
				),
			),
			'photo'          => array(
				'title'  => __( 'Photo', 'bb-booster' ),
				'fields' => array(
					'photo'          => array(
						'type'        => 'photo',
						'show_remove' => true,
						'label'       => __( 'Photo', 'bb-booster' ),
						'connections' => array( 'photo' ),
					),
					'photo_position' => array(
						'type'    => 'select',
						'label'   => __( 'Position', 'bb-booster' ),
						'default' => 'above-title',
						'options' => array(
							'above-title' => __( 'Above Heading', 'bb-booster' ),
							'below-title' => __( 'Below Heading', 'bb-booster' ),
							'left'        => __( 'Left of Text and Heading', 'bb-booster' ),
							'right'       => __( 'Right of Text and Heading', 'bb-booster' ),
						),
					),
				),
			),
			'photo_style'    => array(
				'title'  => __( 'Photo Style', 'bb-booster' ),
				'fields' => array(
					'photo_crop'   => array(
						'type'    => 'select',
						'label'   => __( 'Crop', 'bb-booster' ),
						'default' => '',
						'options' => array(
							''          => _x( 'None', 'Photo Crop.', 'bb-booster' ),
							'landscape' => __( 'Landscape', 'bb-booster' ),
							'panorama'  => __( 'Panorama', 'bb-booster' ),
							'portrait'  => __( 'Portrait', 'bb-booster' ),
							'square'    => __( 'Square', 'bb-booster' ),
							'circle'    => __( 'Circle', 'bb-booster' ),
						),
					),
					'photo_width'  => array(
						'type'       => 'unit',
						'label'      => __( 'Width', 'bb-booster' ),
						'responsive' => true,
						'units'      => array(
							'px',
							'vw',
							'%',
						),
						'slider'     => array(
							'px' => array(
								'min'  => 0,
								'max'  => 1000,
								'step' => 10,
							),
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.fl-photo-img',
							'property'  => 'width',
							'important' => true,
						),
					),
					'photo_align'  => array(
						'type'       => 'align',
						'label'      => __( 'Align', 'bb-booster' ),
						'default'    => '',
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.fl-photo',
							'property'  => 'text-align',
							'important' => true,
						),
					),
					'photo_border' => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'bb-booster' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-photo-img',
						),
					),
				),
			),
			'icon'           => array(
				'title'  => __( 'Icon', 'bb-booster' ),
				'fields' => array(
					'icon'          => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'bb-booster' ),
						'show_remove' => true,
					),
					'sr_text'       => array(
						'type'    => 'text',
						'label'   => __( 'Screen Reader Text', 'bb-booster' ),
						'default' => '',
					),
					'icon_position' => array(
						'type'    => 'select',
						'label'   => __( 'Position', 'bb-booster' ),
						'default' => 'left-title',
						'options' => array(
							'above-title' => __( 'Above Heading', 'bb-booster' ),
							'below-title' => __( 'Below Heading', 'bb-booster' ),
							'left-title'  => __( 'Left of Heading', 'bb-booster' ),
							'right-title' => __( 'Right of Heading', 'bb-booster' ),
							'left'        => __( 'Left of Text and Heading', 'bb-booster' ),
							'right'       => __( 'Right of Text and Heading', 'bb-booster' ),
						),
					),
				),
			),
			'icon_colors'    => array(
				'title'  => __( 'Icon Colors', 'bb-booster' ),
				'fields' => array(
					'icon_duo_color1'     => array(
						'label'      => __( 'DuoTone Primary Color', 'bb-booster' ),
						'type'       => 'color',
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.fl-icon i.fad:before',
							'property'  => 'color',
							'important' => true,
						),
					),
					'icon_duo_color2'     => array(
						'label'      => __( 'DuoTone Secondary Color', 'bb-booster' ),
						'type'       => 'color',
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.fl-icon i.fad:after',
							'property'  => 'color',
							'important' => true,
						),
					),
					'icon_color'          => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Color', 'bb-booster' ),
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '.fl-icon i, .fl-icon i:before',
							'property'  => 'color',
							'important' => true,
						),
					),
					'icon_hover_color'    => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Hover Color', 'bb-booster' ),
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
					'icon_bg_color'       => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Background Color', 'bb-booster' ),
						'show_reset'  => true,
						'show_alpha'  => true,
					),
					'icon_bg_hover_color' => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Background Hover Color', 'bb-booster' ),
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
					'icon_3d'             => array(
						'type'    => 'select',
						'label'   => __( 'Gradient', 'bb-booster' ),
						'default' => '0',
						'options' => array(
							'0' => __( 'No', 'bb-booster' ),
							'1' => __( 'Yes', 'bb-booster' ),
						),
					),
				),
			),
			'icon_structure' => array(
				'title'  => __( 'Icon Structure', 'bb-booster' ),
				'fields' => array(
					'icon_size' => array(
						'type'       => 'unit',
						'label'      => __( 'Size', 'bb-booster' ),
						'default'    => '30',
						'responsive' => true,
						'units'      => array( 'px', 'em', 'rem' ),
						'slider'     => true,
						'preview'    => array(
							'type' => 'none',
						),
					),
				),
			),
		),
	),
	'cta'     => array(
		'title'    => __( 'Link', 'bb-booster' ),
		'sections' => array(
			'link'       => array(
				'title'  => '',
				'fields' => array(
					'link' => array(
						'type'          => 'link',
						'label'         => __( 'Link', 'bb-booster' ),
						'help'          => __( 'The link applies to the entire module. If choosing a call to action type below, this link will also be used for the text or button.', 'bb-booster' ),
						'show_target'   => true,
						'show_nofollow' => true,
						'preview'       => array(
							'type' => 'none',
						),
						'connections'   => array( 'url' ),
					),
				),
			),
			'cta'        => array(
				'title'  => __( 'Call to Action', 'bb-booster' ),
				'fields' => array(
					'cta_type' => array(
						'type'    => 'select',
						'label'   => __( 'Type', 'bb-booster' ),
						'default' => 'none',
						'options' => array(
							'none'   => _x( 'None', 'Call to action.', 'bb-booster' ),
							'link'   => __( 'Link', 'bb-booster' ),
							'button' => __( 'Button', 'bb-booster' ),
						),
						'toggle'  => array(
							'none'   => array(),
							'link'   => array(
								'fields'   => array( 'cta_text' ),
								'sections' => array( 'link_text' ),
							),
							'button' => array(
								'fields'   => array( 'cta_text' ),
								'sections' => array( 'btn_icon', 'btn_style', 'btn_text', 'btn_colors', 'btn_border' ),
							),
						),
					),
					'cta_text' => array(
						'type'        => 'text',
						'label'       => __( 'Text', 'bb-booster' ),
						'default'     => __( 'Read More', 'bb-booster' ),
						'connections' => array( 'string' ),
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.fl-callout-cta-link, .fl-button-text',
						),
					),
				),
			),
			'link_text'  => array(
				'title'  => __( 'Link Text', 'bb-booster' ),
				'fields' => array(
					'link_color'       => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Link Color', 'bb-booster' ),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => 'a.fl-callout-cta-link',
							'property'  => 'color',
							'important' => true,
						),
					),
					'link_hover_color' => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Link Hover Color', 'bb-booster' ),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => 'a.fl-callout-cta-link:hover, a.fl-callout-cta-link:focus',
							'property'  => 'color',
							'important' => true,
						),
					),
					'link_typography'  => array(
						'type'       => 'typography',
						'label'      => __( 'Link Typography', 'bb-booster' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => 'a.fl-callout-cta-link',
						),
					),
				),
			),
			'btn_icon'   => array(
				'title'  => __( 'Button Icon', 'bb-booster' ),
				'fields' => array(
					'btn_icon'           => array(
						'type'        => 'icon',
						'label'       => __( 'Button Icon', 'bb-booster' ),
						'show_remove' => true,
						'show'        => array(
							'fields' => array( 'btn_icon_position', 'btn_icon_animation' ),
						),
					),
					'btn_duo_color1'     => array(
						'label'      => __( 'DuoTone Primary Color', 'bb-booster' ),
						'type'       => 'color',
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.fl-button i.fad:before',
							'property'  => 'color',
							'important' => true,
						),
					),
					'btn_duo_color2'     => array(
						'label'      => __( 'DuoTone Secondary Color', 'bb-booster' ),
						'type'       => 'color',
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.fl-button i.fad:after',
							'property'  => 'color',
							'important' => true,
						),
					),
					'btn_icon_position'  => array(
						'type'    => 'select',
						'label'   => __( 'Button Icon Position', 'bb-booster' ),
						'default' => 'before',
						'options' => array(
							'before' => __( 'Before Text', 'bb-booster' ),
							'after'  => __( 'After Text', 'bb-booster' ),
						),
					),
					'btn_icon_animation' => array(
						'type'    => 'select',
						'label'   => __( 'Button Icon Visibility', 'bb-booster' ),
						'default' => 'disable',
						'options' => array(
							'disable' => __( 'Always Visible', 'bb-booster' ),
							'enable'  => __( 'Fade In On Hover', 'bb-booster' ),
						),
					),
				),
			),
			'btn_style'  => array(
				'title'  => __( 'Button Style', 'bb-booster' ),
				'fields' => array(
					'btn_width'   => array(
						'type'    => 'select',
						'label'   => __( 'Button Width', 'bb-booster' ),
						'default' => 'auto',
						'options' => array(
							'auto' => _x( 'Auto', 'Width.', 'bb-booster' ),
							'full' => __( 'Full Width', 'bb-booster' ),
						),
						'toggle'  => array(
							'auto' => array(
								'fields' => array( 'btn_align' ),
							),
						),
					),
					'btn_align'   => array(
						'type'       => 'align',
						'label'      => __( 'Button Align', 'bb-booster' ),
						'default'    => '',
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-button-wrap',
							'property' => 'text-align',
						),
					),
					'btn_padding' => array(
						'type'       => 'dimension',
						'label'      => __( 'Button Padding', 'bb-booster' ),
						'responsive' => true,
						'slider'     => true,
						'units'      => array( 'px' ),
						'preview'    => array(
							'type'     => 'css',
							'selector' => 'a.fl-button',
							'property' => 'padding',
						),
					),
				),
			),
			'btn_text'   => array(
				'title'  => __( 'Button Text', 'bb-booster' ),
				'fields' => array(
					'btn_text_color'       => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Button Text Color', 'bb-booster' ),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => 'a.fl-button, a.fl-button *',
							'property'  => 'color',
							'important' => true,
						),
					),
					'btn_text_hover_color' => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Button Text Hover Color', 'bb-booster' ),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => 'a.fl-button:hover, a.fl-button:hover *, a.fl-button:focus, a.fl-button:focus *',
							'property'  => 'color',
							'important' => true,
						),
					),
					'btn_typography'       => array(
						'type'       => 'typography',
						'label'      => __( 'Button Typography', 'bb-booster' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => 'a.fl-button',
						),
					),
				),
			),
			'btn_colors' => array(
				'title'  => __( 'Button Background', 'bb-booster' ),
				'fields' => array(
					'btn_bg_color'          => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Button Background Color', 'bb-booster' ),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
					'btn_bg_hover_color'    => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Button Background Hover Color', 'bb-booster' ),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
					'btn_style'             => array(
						'type'    => 'select',
						'label'   => __( 'Button Background Style', 'bb-booster' ),
						'default' => 'flat',
						'options' => array(
							'flat'     => __( 'Flat', 'bb-booster' ),
							'gradient' => __( 'Gradient', 'bb-booster' ),
						),
					),
					'btn_button_transition' => array(
						'type'    => 'select',
						'label'   => __( 'Button Background Animation', 'bb-booster' ),
						'default' => 'disable',
						'options' => array(
							'disable' => __( 'Disabled', 'bb-booster' ),
							'enable'  => __( 'Enabled', 'bb-booster' ),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
				),
			),
			'btn_border' => array(
				'title'  => __( 'Button Border', 'bb-booster' ),
				'fields' => array(
					'btn_border'             => array(
						'type'       => 'border',
						'label'      => __( 'Button Border', 'bb-booster' ),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => 'a.fl-button',
							'important' => true,
						),
					),
					'btn_border_hover_color' => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Button Border Hover Color', 'bb-booster' ),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
				),
			),
		),
	),
));
