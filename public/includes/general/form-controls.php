<?php

function enlightenment_form_control_allowed_tags() {
	global $allowedposttags;

	return array_merge( $allowedposttags, array(
		'svg'    => array(
			'version'     => true,
			'xmlns'       => true,
			'viewbox'     => true,
			'baseprofile' => true,
			'width'       => true,
			'height'      => true,
		),
		'rect'   => array(
			'x'          => true,
			'y'          => true,
			'width'      => true,
			'height'     => true,
			'rx'         => true,
			'ry'         => true,
			'pathlength' => true,
			'fill'       => true,
			'class'      => true,
			'style'      => true,
		),
		'circle' => array(
			'cx'   => true,
			'cy'   => true,
			'r'    => true,
			'fill' => true,
		),
		'path'   => array(
			'fill' => true,
			'd'    => true,
		),
		'text'   => array(
			'x'           => true,
			'y'           => true,
			'fill'        => true,
			'font-size'   => true,
			'text-anchor' => true,
		),
	) );
}

function enlightenment_settings_text( $args, $echo = true ) {
	$defaults = array(
		'text' => '',
	);
	$args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'enlightenment_settings_text_args', $args );

	$output = wp_kses( $args['text'], enlightenment_form_control_allowed_tags() );
	$output = apply_filters( 'enlightenment_settings_text', $output, $args );

	if ( $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_input( $args, $echo = true ) {
	$defaults = array(
		'label'       => '',
		'description' => '',
		'class'       => '',
		'id'          => '',
		'type'        => 'text',
		'value'       => '',
		'placeholder' => '',
		'size'        => '',
		'min'         => '',
		'max'         => '',
		'step'        => '',
		'readonly'    => false,
		'extra_atts'  => array(),
	);
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'enlightenment_input_args', $args );

	if ( ! isset( $args['name'] ) || empty( $args['name'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Please specify a name attribute for your text field.', 'enlightenment' ), '' );
		return;
	}

	$output  = '';

	if ( ! empty( $args['label'] ) ) {
		$output .= '<label ';
		$output .= empty( $args['id'] ) ? '' : sprintf( 'for="%s"', esc_attr( $args['id'] ) );
		$output .= enlightenment_class( $args['class'] . '-label' );
		$output .= enlightenment_id( $args['id'] . '-label' );
		$output .= '>';
		$output .= wp_kses( $args['label'], enlightenment_form_control_allowed_tags() );
		$output .= '<br />' . "\n";
	}

	$output .= '<input ';
	$output .= sprintf( 'name="%s"', esc_attr( $args['name'] ) );
	$output .= enlightenment_class( $args['class'] );
	$output .= enlightenment_id( $args['id'] ) . ' ';
	$output .= sprintf( 'value="%s" ', esc_attr( $args['value'] ) );
	$output .= sprintf( 'type="%s" ', esc_attr( $args['type'] ) );
	$output .= empty( $args['placeholder'] ) ? '' : sprintf( ' placeholder="%s" ', esc_attr( $args['placeholder'] ) );
	$output .= empty( $args['size'] ) ? '' : sprintf( ' size="%s" ', esc_attr( $args['size'] ) );
	$output .= '' === $args['min'] ? '' : sprintf( ' min="%s" ', esc_attr( $args['min'] ) );
	$output .= empty( $args['max'] ) ? '' : sprintf( ' max="%s" ', esc_attr( $args['max'] ) );
	$output .= empty( $args['step'] ) ? '' : sprintf( ' step="%s" ', esc_attr( $args['step'] ) );
	$output .= $args['readonly'] ? 'readonly' : '';
	$output .= enlightenment_extra_atts( $args['extra_atts'] );
	$output .= '/>';

	if ( ! empty( $args['label'] ) ) {
		$output .= '</label>';
    }

	$output .= empty( $args['description'] ) ? '' : sprintf( '<p class="description">%s</p>', wp_kses( $args['description'], enlightenment_form_control_allowed_tags() ) );
	$output  = apply_filters( 'enlightenment_input', $output, $args );

	if ( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_checkbox( $args, $echo = true ) {
	$defaults = array(
		'class'       => '',
		'id'          => '',
		'value'       => true,
		'checked'     => false,
		'description' => '',
	);
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'enlightenment_checkbox_args', $args );

	if ( ! isset( $args['name'] ) || empty( $args['name'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Please specify a name attribute for your checkbox.', 'enlightenment' ), '' );
		return;
	}

	if ( ! isset( $args['label'] ) || empty( $args['label'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Please specify a label for your checkbox.', 'enlightenment' ), '' );
		return;
	}

	$output  = '<label ';
	$output .= empty( $args['id'] ) ? '' : sprintf( 'for="%s"', esc_attr( $args['id'] ) );
	$output .= empty( $args['class'] ) ? '' : enlightenment_class( $args['class'] . '-label' );
	$output .= empty( $args['id'] ) ? '' : enlightenment_id( $args['id'] . '-label' );
	$output .= '>';
	$output .= '<input ';
	$output .= sprintf( 'name="%s"', esc_attr( $args['name'] ) );
	$output .= enlightenment_class( $args['class'] );
	$output .= enlightenment_id( $args['id'] ) . ' ';
	$output .= sprintf( 'value="%s" ', esc_attr( $args['value'] ) );
	$output .= 'type="checkbox" ';
	$output .= checked( $args['checked'], true, false );
	$output .= ' /> ';
	$output .= wp_kses( $args['label'], enlightenment_form_control_allowed_tags() );
	$output .= '</label>';
	$output .= empty( $args['description'] ) ? '' : sprintf( '<p class="description">%s</p>', wp_kses( $args['description'], enlightenment_form_control_allowed_tags() ) );
	$output  = apply_filters( 'enlightenment_checkbox', $output, $args );

    if ( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_checkboxes( $args, $echo = true ) {
	$defaults = array(
		'class'       => '',
		'boxes'       => array(),
		'description' => '',
	);
	$args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'enlightenment_checkboxes_args', $args );

	$output = '<fieldset>';

	foreach ( $args['boxes'] as $key => $checkbox ) {
		$checkbox = wp_parse_args( $checkbox, $args );

		unset( $checkbox['description'] );

		$output .= enlightenment_checkbox( $checkbox, false );

		if ( $key != count( $args['boxes'] ) - 1 ) {
			$output .= '<br />';
        }
	}

	$output .= '</fieldset>';
	$output .= empty( $args['description'] ) ? '' : sprintf( '<p class="description">%s</p>', wp_kses( $args['description'], enlightenment_form_control_allowed_tags() ) );
	$output  = apply_filters( 'enlightenment_checkboxes', $output, $args );

	if ( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_radio_buttons( $args, $echo = true ) {
	$defaults = array(
		'container_class' => 'radio-buttons',
		'legend'          => '',
		'legend_tag'      => 'legend',
		'legend_class'    => '',
		'control_class'   => 'radio-button-control',
		'input_class'     => '',
		'label_class'     => '',
		'value'           => '',
		'buttons'         => array(),
		'description'     => '',
	);
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'enlightenment_radio_buttons_args', $args );

	if ( ! isset( $args['name'] ) || empty( $args['name'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Please specify a name attribute for your radio buttons.', 'enlightenment' ), '' );
		return;
	}

	$output = enlightenment_open_tag( 'fieldset', $args['container_class'] );

	if ( ! empty( $args['legend'] ) ) {
		$output .= enlightenment_open_tag( $args['legend_tag'], $args['legend_class'] );
		$output .= wp_kses( $args['legend'], enlightenment_form_control_allowed_tags() );
		$output .= enlightenment_close_tag( $args['legend_tag'] );
	}

	foreach ( $args['buttons'] as $button ) {
		if ( ! isset( $button['label'] ) || empty( $button['label'] ) ) {
			_doing_it_wrong( __FUNCTION__, __( 'Please specify a label for your radio button.', 'enlightenment' ), '' );
			return;
		}

		if ( ! isset( $button['value'] ) || empty( $button['value'] ) ) {
			_doing_it_wrong( __FUNCTION__, __( 'Please specify a value attribute for your radio button.', 'enlightenment' ), '' );
			return;
		}

		$control_class = $args['control_class'];

		if ( isset( $button['control_class'] ) ) {
			$control_class .= sprintf( ' %s', $button['control_class'] );
		}

		$button['id'] = sprintf( '%s-radio-%s', $args['name'], $button['value'] );
		$button['id'] = str_replace( ' ', '_', $button['id'] );

		$output .= enlightenment_open_tag( 'span', $control_class );
		$output .= '<input ';
		$output .= sprintf( 'name="%s"', esc_attr( $args['name'] ) );
		$output .= enlightenment_class( $args['input_class'] );
		$output .= sprintf( 'id="%s"', esc_attr( $button['id'] ) );
		$output .= sprintf( 'value="%s" ', esc_attr( $button['value'] ) );
		$output .= 'type="radio" ';
		$output .= checked( $args['value'], $button['value'], false );
		$output .= ' />';
		$output .= ' ';
		$output .= sprintf( '<label for="%s" %s>', esc_attr( $button['id'] ), enlightenment_class( $args['label_class'] ) );
		$output .= wp_kses( $button['label'], enlightenment_form_control_allowed_tags() );
		$output .= '</label>';
		$output .= enlightenment_close_tag( 'span' );
	}

	$output .= enlightenment_close_tag( 'fieldset' );
	$output .= empty( $args['description'] ) ? '' : '<p class="description">' . wp_kses( $args['description'], enlightenment_form_control_allowed_tags() ) . '</p>';
	$output  = apply_filters( 'enlightenment_radio_buttons', $output, $args );

	if ( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_select_box( $args, $echo = true ) {
	$defaults = array(
		'class'       => '',
		'id'          => '',
		'value'       => '',
		'multiple'    => false,
		'options'     => array(),
		'description' => '',
	);
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'enlightenment_select_box_args', $args );

	if ( ! isset( $args['name'] ) || empty( $args['name'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Please specify a name attribute for your radio buttons.', 'enlightenment' ), '' );
		return;
	}

	$output = '';

	if ( ! empty( $args['options'] ) ) {
		if ( ! empty( $args['label'] ) ) {
			$output .= '<label ';
			$output .= empty( $args['id'] ) ? '' : 'for="' . esc_attr( $args['id'] ) . '"';
			$output .= enlightenment_class( $args['class'] . '-label' );
			$output .= enlightenment_id( $args['id'] . '-label' );
			$output .= '>';
			$output .= wp_kses( $args['label'], enlightenment_form_control_allowed_tags() );
			$output .= '<br />' . "\n";
		}

		$output .= '<select ';
		$output .= sprintf( 'name="%s%s"', esc_attr( $args['name'] ), ( $args['multiple'] ? '[]' : '' ) );
		$output .= enlightenment_class( $args['class'] );
		$output .= enlightenment_id( $args['id'] );

		if ( $args['multiple'] ) {
			$output .= ' multiple="multiple"';
        }

		$output .= '>';

		foreach ( $args['options'] as $value => $label ) {
			$output .= '<option ';
			$output .= sprintf( 'value="%s"', esc_attr( $value ) );
			$output .= selected( $args['value'], $value, false );
			$output .= '>';
			$output .= esc_html( wp_kses( $label, 'strip' ) );
			$output .= '</option>';
		}

		$output .= '</select>';

		if ( ! empty( $args['label'] ) ) {
			$output .= '</label>';
        }
	}

	$output .= empty( $args['description'] ) ? '' : '<p class="description">' . wp_kses( $args['description'], enlightenment_form_control_allowed_tags() ) . '</p>';
	$output  = apply_filters( 'enlightenment_select_box', $output, $args );

	if ( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_textarea( $args, $echo = true ) {
	$defaults = array(
		'class'       => '',
		'id'          => '',
		'cols'        => '',
		'rows'        => '',
		'value'       => '',
		'description' => '',
	);
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'enlightenment_textarea_args', $args );

	if ( ! isset( $args['name'] ) || empty( $args['name'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Please specify a name attribute for your textarea.', 'enlightenment' ), '' );
		return;
	}

	$output  = '';

	if ( ! empty( $args['label'] ) ) {
		$output .= '<label ';
		$output .= empty( $args['id'] ) ? '' : 'for="' . esc_attr( $args['id'] ) . '"';
		$output .= enlightenment_class( $args['class'] . '-label' );
		$output .= enlightenment_id( $args['id'] . '-label' );
		$output .= '>';
		$output .= wp_kses( $args['label'], enlightenment_form_control_allowed_tags() );
		$output .= '<br />' . "\n";
	}

	$output .= '<textarea ';
	$output .= 'name="' . apply_filters( 'enlightenment_textarea_name', esc_attr( $args['name'] ) ) . '"';
	$output .= enlightenment_class( $args['class'] );
	$output .= enlightenment_id( $args['id'] ) . ' ';
	$output .= empty( $args['cols'] ) ? '' : ' cols="' . esc_attr( $args['cols'] ) . '" ';
	$output .= empty( $args['rows'] ) ? '' : ' rows="' . esc_attr( $args['rows'] ) . '" ';
	$output .= '>';
	$output .= esc_textarea( $args['value'] );
	$output .= '</textarea>';
	$output .= empty( $args['description'] ) ? '' : '<p class="description">' . wp_kses( $args['description'], enlightenment_form_control_allowed_tags() ) . '</p>';
	$output  = apply_filters( 'enlightenment_textarea', $output, $args );

	if ( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_upload_media( $args, $echo = true ) {
	$defaults = array(
		'class'                => '',
		'id'                   => '',
		'description'          => '',
		'upload_button_text'   => __( 'Choose Media', 'enlightenment' ),
		'uploader_title'       => __( 'Insert Media', 'enlightenment' ),
		'uploader_button_text' => __( 'Select', 'enlightenment' ),
		'remove_button_text'   => __( 'Remove Media', 'enlightenment' ),
		'mime_type'            => 'image',
		'multiple'             => false,
		'thumbnail'            => 'thumbnail',
		'value'                => '',
	);
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'enlightenment_upload_media_args', $args );

	if ( ! isset( $args['name'] ) || empty( $args['name'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Please specify a name attribute for your upload field.', 'enlightenment' ), '' );
		return;
	}

	wp_enqueue_media();

	$output  = '<input ';
	$output .= enlightenment_class( $args['class'] . ' button upload-media-button' );
	$output .= enlightenment_id( $args['id'] ) . ' ';
	$output .= 'value="' . esc_attr( $args['upload_button_text'] ) . '" ';
	$output .= 'type="button" ';
	$output .= 'data-uploader-title="' . esc_attr( $args['uploader_title'] ) . '" ';
	$output .= 'data-uploader-button-text="' . esc_attr( $args['uploader_button_text'] ) . '" ';
	$output .= 'data-mime-type="' . esc_attr( $args['mime_type'] ) . '" ';
	$output .= 'data-multiple="' . esc_attr( $args['multiple'] ) . '" ';
	$output .= 'data-thumbnail="' . esc_attr( $args['thumbnail'] ) . '" ';
	$output .= '/>';

	$media = $args['value'];

	if ( '' != $args['remove_button_text'] ) {
		$output .= '<input class="button remove-media-button" value="' . esc_attr( $args['remove_button_text'] ) . '" type="button"' . ( empty( $media ) ? ' style="display:none"' : '' ) . ' />';
    }

	if ( null != $args['thumbnail'] ) {
		$output .= sprintf( '<div class="%s">', esc_attr( 'preview-media' ) );

		if ( ! empty( $media ) ) {
			if ( $args['multiple'] ) {
				$media = explode( ',', $media );

				foreach ( $media as $attachment_id ) {
					$attachment_id = intval( $attachment_id );

					if ( wp_attachment_is_image( $attachment_id ) ) {
						$attachment = wp_get_attachment_image( $attachment_id, $args['thumbnail'] );
					} else {
						$attachment = '<a href="' . wp_get_attachment_url( $attachment_id ) . '">' . get_the_title( $attachment_id ) . '</a>';
		            }

					$output .= $attachment;
				}
			} else {
				$attachment_id = intval( $media );

				if ( wp_attachment_is_image( $attachment_id ) ) {
					$attachment = wp_get_attachment_image( $attachment_id, $args['thumbnail'] );
				} else {
					$attachment = '<a href="' . wp_get_attachment_url( $attachment_id ) . '">' . get_the_title( $attachment_id ) . '</a>';
	            }

				$output .= $attachment;
			}
		}

		$output .= '</div>';
	}

	$input_args = $args;

	$input_args['class'] .= ' upload-media-input';
	$input_args['type']   = 'hidden';

	$output .= enlightenment_input( $input_args, false );

	$output .= empty( $args['description'] ) ? '' : '<p class="description">' . wp_kses( $args['description'], enlightenment_form_control_allowed_tags() ) . '</p>';

	$output  = apply_filters( 'enlightenment_upload_media', $output, $args );

    if ( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_attached_media_src() {
	$id        = intval( $_POST['id'] );
	$size      = esc_attr( $_POST['size'] );
	$mime_type = esc_attr( $_POST['mime_type'] );

	if ( 'image' == $mime_type ) {
		echo wp_get_attachment_image( $id, $size );
	} else {
		printf( '<a href="%s">%s"</a>', wp_get_attachment_url( $id ), get_the_title( $id ) );
    }

	die();
}
add_action( 'wp_ajax_enlightenment_media_preview', 'enlightenment_attached_media_src' );

function enlightenment_color_picker( $args, $echo = true ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => '',
		'container_id'    => '',
		'picker_class'    => '',
		'picker_id'       => '',
		'palette'         => true,
		'default_color'   => '',
		'alpha'           => false,
		'show_opacity'    => false,
		'description'     => '',
	);
	$args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'enlightenment_color_picker_args', $args );

	if ( ! isset( $args['name'] ) || empty( $args['name'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Please specify a name attribute for your color picker.', 'enlightenment' ), '' );
		return;
	}

	$description = $args['description'];
	unset( $args['description'] );

	$picker_args = $args;

	$picker_args['class']  = $picker_args['picker_class'];
	$picker_args['class'] .= ' wp-color-picker';

	$picker_args['id']  = $picker_args['picker_id'];

	$picker_args['extra_atts'] = array();

	$picker_args['extra_atts']['data-palette'] = ( false === $args['palette'] || 'false' === $args['palette'] ) ? 'false' : 'true';

	if ( ! empty( $args['default_color'] ) && is_string( $args['default_color'] ) ) {
		$picker_args['extra_atts']['data-default-color'] = $args['default_color'];
	}

	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );

	if ( $args['alpha'] ) {
		$picker_args['class'] .= ' alpha-color-picker';

		$picker_args['extra_atts']['data-show-opacity'] = ( true === $args['show_opacity'] || 'true' === $args['show_opacity'] ) ? 'true' : 'false';

		wp_enqueue_style( 'enlightenment-alpha-color-picker' );
		wp_enqueue_script( 'enlightenment-alpha-color-picker' );
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	$output .= enlightenment_input( $picker_args, false );

	$output .= empty( $description ) ? '' : sprintf( '<p class="description">%s</p>', wp_kses( $description, enlightenment_form_control_allowed_tags() ) );

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_color_picker', $output, $args );

	if( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_background_options( $args, $echo = true ) {
	$defaults = array(
		'class'               => '',
		'id'                  => '',
		'value'               => array(),
		'color_picker_args'   => array(),
		'image_uploader_args' => array(),
		'position_args'       => array(),
		'repeat_args'         => array(),
		'size_args'           => array(),
		'scroll_args'         => array(),
		'labels'              => array(),
		'description'         => '',
	);
	$args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'enlightenment_background_options_args', $args );

	if ( ! isset( $args['name'] ) || empty( $args['name'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Please specify a name attribute for your background options.', 'enlightenment' ), '1.1.0' );
		return;
	}

	$args['class'] .= ' background-options';

	$description = $args['description'];
	unset( $args['description'] );

	$value  = array_merge( array(
		'color'    => '',
		'image'    => '',
		'position' => '',
		'repeat'   => '',
		'size'     => '',
		'scroll'   => '',
	), (array) $args['value'] );

	$labels  = array_merge( array(
		'color'    => __( 'Background Color',      'enlightenment' ),
		'image'    => __( 'Background Image',      'enlightenment' ),
		'position' => __( 'Background Position',   'enlightenment' ),
		'repeat'   => __( 'Background Repeat',     'enlightenment' ),
		'size'     => __( 'Background Size',       'enlightenment' ),
		'scroll'   => __( 'Background Attachment', 'enlightenment' ),
	), (array) $args['labels'] );

	$color_picker_args = array(
		'container_class' => 'background-options__bg-color',
		'picker_id'       => ! empty( $args['id'] ) ? sprintf( '%s__bg-color', $args['id'] ) : '',
		'name'            => sprintf( '%s[color]', $args['name'] ),
		'value'           => $value['color'],
	);

	if ( ! is_array( $args['color_picker_args'] ) ) {
		$args['color_picker_args'] = array();
	}

	$color_picker_args = array_merge( $color_picker_args, $args['color_picker_args'] );

	$image_uploader_args = array(
		'container_class'      => 'background-options__bg-image',
		'id'                   => ! empty( $args['id'] ) ? sprintf( '%s__bg-image', $args['id'] ) : '',
		'name'                 => sprintf( '%s[image]', $args['name'] ),
		'value'                => $value['image'],
		'upload_button_text'   => __( 'Choose Image', 'enlightenment' ),
		'uploader_title'       => __( 'Select Image', 'enlightenment' ),
		'uploader_button_text' => __( 'Use Image',    'enlightenment' ),
		'remove_button_text'   => __( 'Remove Image', 'enlightenment' ),
		'mime_type'            => 'image',
		'thumbnail'            => 'thumbnail',
	);

	if ( ! is_array( $args['image_uploader_args'] ) ) {
		$args['image_uploader_args'] = array();
	}

	$image_uploader_args = array_merge( $image_uploader_args, $args['image_uploader_args'] );

	$position_args = array(
		'container_class' => 'background-options__bg-position',
		'container_id'    => ! empty( $args['id'] ) ? sprintf( '%s__bg-position', $args['id'] ) : '',
		'name'            => sprintf( '%s[position]', $args['name'] ),
		'value'           => $value['position'],
	);

	if ( ! is_array( $args['position_args'] ) ) {
		$args['position_args'] = array();
	}

	$position_args = array_merge( $position_args, $args['position_args'] );

	$repeat_args = array(
		'container_class' => 'background-options__bg-repeat',
		'id'              => ! empty( $args['id'] ) ? sprintf( '%s__bg-repeat', $args['id'] ) : '',
		'name'            => sprintf( '%s[repeat]', $args['name'] ),
		'value'           => $value['repeat'],
		'options'         => array(
			'no-repeat'  => __( 'No Repeat',          'enlightenment' ),
			'repeat'     => __( 'Tiled',              'enlightenment' ),
			'repeat-x'   => __( 'Tiled Horizontally', 'enlightenment' ),
			'repeat-y'   => __( 'Tiled Vertically',   'enlightenment' ),
		),
	);

	if ( ! is_array( $args['repeat_args'] ) ) {
		$args['repeat_args'] = array();
	}

	$repeat_args = array_merge( $repeat_args, $args['repeat_args'] );

	$size_args = array(
		'container_class' => 'background-options__bg-size',
		'id'              => ! empty( $args['id'] ) ? sprintf( '%s__bg-size', $args['id'] ) : '',
		'name'            => sprintf( '%s[size]', $args['name'] ),
		'value'           => $value['size'],
		'options'         => array(
			'auto'    => __( 'Scaled',    'enlightenment' ),
			'cover'   => __( 'Cover',     'enlightenment' ),
			'contain' => __( 'Contained', 'enlightenment' ),
		),
	);

	if ( ! is_array( $args['size_args'] ) ) {
		$args['size_args'] = array();
	}

	$size_args = array_merge( $size_args, $args['size_args'] );

	$scroll_args = array(
		'container_class' => 'background-options__bg-scroll',
		'id'              => ! empty( $args['id'] ) ? sprintf( '%s__bg-scroll', $args['id'] ) : '',
		'name'            => sprintf( '%s[scroll]', $args['name'] ),
		'value'           => $value['scroll'],
		'options'         => array(
			'scroll' => __( 'Scroll',   'enlightenment' ),
			'fixed'  => __( 'Fixed',    'enlightenment' ),
		),
	);

	if ( ! is_array( $args['scroll_args'] ) ) {
		$args['scroll_args'] = array();
	}

	$scroll_args = array_merge( $scroll_args, $args['scroll_args'] );

	$output  = enlightenment_open_tag( 'fieldset', $args['class'], $args['id'] );

	$output .= sprintf(
		'<label%s>%s</label>',
		! empty( $color_picker_args['picker_id'] ) ? sprintf( ' for="%s"', $color_picker_args['picker_id'] ) : '',
		$labels['color']
	);
	$output .= enlightenment_color_picker( $color_picker_args, false );

	$output .= enlightenment_open_tag( 'div', $image_uploader_args['container_class'] );
	$output .= sprintf(
		'<label%s>%s</label><br />',
		! empty( $image_uploader_args['id'] ) ? sprintf( ' for="%s"', $image_uploader_args['id'] ) : '',
		$labels['image']
	);
	$output .= enlightenment_upload_media( $image_uploader_args, false );
	$output .= enlightenment_close_tag( 'div' );

	$output .= sprintf( '<label>%s</label><br />', $labels['position'] );
	$output .= enlightenment_position_radio_buttons( $position_args, false );

	$output .= enlightenment_open_tag( 'p', $repeat_args['container_class'] );
	$output .= sprintf(
		'<label%s>%s</label><br />',
		! empty( $repeat_args['id'] ) ? sprintf( ' for="%s"', $repeat_args['id'] ) : '',
		$labels['repeat']
	);
	$output .= enlightenment_select_box( $repeat_args, false );
	$output .= enlightenment_close_tag( 'p' );

	$output .= enlightenment_open_tag( 'p', $size_args['container_class'] );
	$output .= sprintf(
		'<label%s>%s</label><br />',
		! empty( $size_args['id'] ) ? sprintf( ' for="%s"', $size_args['id'] ) : '',
		$labels['size']
	);
	$output .= enlightenment_select_box( $size_args, false );
	$output .= enlightenment_close_tag( 'p' );

	$output .= enlightenment_open_tag( 'p', $scroll_args['container_class'] );
	$output .= sprintf(
		'<label%s>%s</label><br />',
		! empty( $scroll_args['id'] ) ? sprintf( ' for="%s"', $scroll_args['id'] ) : '',
		$labels['scroll']
	);
	$output .= enlightenment_select_box( $scroll_args, false );
	$output .= enlightenment_close_tag( 'p' );

	$output .= enlightenment_close_tag( 'fieldset' );

	$output .= empty( $description ) ? '' : '<p class="description">' . wp_kses( $description, enlightenment_form_control_allowed_tags() ) . '</p>';

	$output  = apply_filters( 'enlightenment_background_options', $output, $args );

	if ( ! $echo ) {
		return $output;
	}

	echo $output;
}

function enlightenment_image_radio_buttons( $args, $echo = true ) {
	$defaults = array(
		'container_class' => '',
		'legend_tag'      => 'div',
		'legend_class'    => 'image-radio-buttons-legend',
		'input_class'     => '',
		'label_class'     => '',
		'buttons'         => array(),
		'description'     => '',
	);
	$args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'enlightenment_image_radio_buttons_args', $args );

	$args['container_class'] .= ' image-radio-buttons';

	if ( ! isset( $args['name'] ) || empty( $args['name'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Please specify a name attribute for your image radio buttons.', 'enlightenment' ), '1.0.0' );
		return;
	}

	foreach ( $args['buttons'] as $key => $button ) {
		if ( ! isset( $button['label'] ) || empty( $button['label'] ) ) {
			_doing_it_wrong( __FUNCTION__, __( 'Please specify a label for your image radio button.', 'enlightenment' ), '2.0.0' );
			return;
        }

		if ( ! isset( $button['image'] ) || empty( $button['image'] ) ) {
			$args['buttons'][ $key ]['control_class'] = 'text-radio-button-control';
			$args['buttons'][ $key ]['label']         = $button['label'];
		} elseif ( 0 === strpos( $button['image'], '<svg ' ) ) {
			$args['buttons'][ $key ]['control_class'] = 'image-radio-button-control';
			$args['buttons'][ $key ]['label']         = $button['image'];
			$args['buttons'][ $key ]['label']        .= sprintf( '<span class="screen-reader-text">%s</span>', esc_html( $button['label'] ) );
		} else {
			$args['buttons'][ $key ]['control_class'] = 'image-radio-button-control';
			$args['buttons'][ $key ]['label']         = sprintf( '<img src="%s" alt="%s" />', esc_url( $button['image'] ), esc_attr( $button['label'] ) );
		}
	}

	$output = enlightenment_radio_buttons( $args, false );
	$output = str_replace( '<br />', '', $output );
	$output = apply_filters( 'enlightenment_image_radio_buttons', $output, $args );

	if ( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_position_radio_buttons( $args, $echo = true ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => '',
		'container_id'    => '',
		'fieldset_class'  => '',
		'legend_tag'      => 'div',
		'legend_class'    => 'position-radio-buttons-legend',
		'radio_class'     => 'radio-button-control',
		'blank_class'     => 'text-radio-button-control',
		'position_class'  => 'position-radio-button-control',
		'input_class'     => '',
		'label_class'     => '',
		'value'           => '',
		'buttons_labels'  => array(),
		'can_be_blank'    => true,
		'blank_label'     => _x( 'Default', 'Position', 'enlightenment' ),
		'description'     => '',
	);
	$args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'enlightenment_position_radio_buttons_args', $args );

	$args['container_class'] .= ' position-radio-buttons';

	if ( ! isset( $args['name'] ) || empty( $args['name'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Please specify a name attribute for your position radio buttons.', 'enlightenment' ), '1.0.0' );
		return;
	}

	$buttons_labels = array(
		'left-top'      => _x( 'Left Top',      'Position', 'enlightenment' ),
		'center-top'    => _x( 'Center Top',    'Position', 'enlightenment' ),
		'right-top'     => _x( 'Right Top',     'Position', 'enlightenment' ),
		'left-center'   => _x( 'Left Center',   'Position', 'enlightenment' ),
		'center-center' => _x( 'Center Center', 'Position', 'enlightenment' ),
		'right-center'  => _x( 'Right Center',  'Position', 'enlightenment' ),
		'left-bottom'   => _x( 'Left Bottom',   'Position', 'enlightenment' ),
		'center-bottom' => _x( 'Center Bottom', 'Position', 'enlightenment' ),
		'right-bottom'  => _x( 'Right Bottom',  'Position', 'enlightenment' ),
	);

	if ( ! empty( $args['buttons_labels'] ) ) {
		foreach ( $buttons_labels as $value => $label ) {
			if ( array_key_exists( $value, $args['buttons_labels'] ) ) {
				$buttons_labels[ $value ] = $args['buttons_labels'][ $value ];
			}
		}
	}

	$buttons = array();

	foreach ( $buttons_labels as $value => $label ) {
		$buttons[] = array(
			'value' => $value,
			'label' => sprintf( '<span class="screen-reader-text">%s</span>', esc_html( $label ) ),
		);
	}

	$output = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	if ( $args['can_be_blank'] ) {
		$output .= enlightenment_open_tag( 'span', sprintf( '%s %s', $args['radio_class'], $args['blank_class'] ) );
		$output .= '<input ';
		$output .= sprintf( 'name="%s" ', esc_attr( $args['name'] ) );
		$output .= enlightenment_class( $args['input_class'] );
		$output .= sprintf( 'id="%s" ', esc_attr( sprintf( '%s-radio-default', $args['name'] ) ) );
		$output .= 'value="" ';
		$output .= 'type="radio" ';
		$output .= checked( $args['value'], '', false );
		$output .= ' />';
		$output .= ' ';
		$output .= sprintf( '<label for="%s" %s>', esc_attr( sprintf( '%s-radio-default', $args['name'] ) ), enlightenment_class( $args['label_class'] ) );
		$output .= wp_kses( $args['blank_label'], enlightenment_form_control_allowed_tags() );
		$output .= '</label>';
		$output .= enlightenment_close_tag( 'span' );
	}

	$output .= enlightenment_radio_buttons( array(
		'container_class' => $args['fieldset_class'],
		'name'            => $args['name'],
		'value'           => $args['value'],
		'legend'          => '',
		'control_class'   => sprintf( '%s %s', $args['radio_class'], $args['position_class'] ),
		'input_class'     => $args['input_class'],
		'label_class'     => $args['label_class'],
		'description'     => '',
		'buttons'         => $buttons,
	), false );

	$output .= enlightenment_close_tag( $args['container'] );
	$output .= empty( $args['description'] ) ? '' : '<p class="description">' . wp_kses( $args['description'], enlightenment_form_control_allowed_tags() ) . '</p>';

	$output  = str_replace( '<br />', '', $output );
	$output  = apply_filters( 'enlightenment_position_radio_buttons', $output, $args );

	if ( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_custom_css( $args, $echo = true ) {
	$defaults = array(
		'class'       => '',
		'id'          => '',
		'description' => '',
	);
	$args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'enlightenment_custom_css_args', $args );

	$args['class'] .= ' custom-css';

	$output = enlightenment_textarea( $args, false );
	$output = apply_filters( 'enlightenment_custom_css', $output, $args );

	if( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_submit_button( $args, $echo = true ) {
	$defaults = array(
		'name'  => '',
		'class' => 'button',
		'id'    => '',
		'value' => '',
	);
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'enlightenment_submit_button_args', $args );

	$output  = '';
	$output .= '<input ';
	$output .= 'name="' . apply_filters( 'enlightenment_submit_button_name', esc_attr( $args['name'] ) ) . '"';
	$output .= enlightenment_class( $args['class'] );
	$output .= enlightenment_id( $args['id'] ) . ' ';
	$output .= 'value="' . esc_attr( $args['value'] ) . '" ';
	$output .= 'type="submit" ';
	$output .= '/>';
	$output  = apply_filters( 'enlightenment_submit_button', $output, $args );

	if ( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_taxonomy_form_field( $args = null, $echo = true ) {
    $defaults = array(
        'term'     => null,
        'key'      => '',
        'label'    => '',
        'callback' => '',
        'cb_args'  => array(),
    );
    $args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'enlightenment_taxonomy_form_field_args', $args );

    if ( ! empty( $args['term'] ) && ! $args['term'] instanceof WP_Term ) {
        $args['term'] = get_term( $args['term'] );

        if ( ! $args['term'] instanceof WP_Term ) {
            _doing_it_wrong( __FUNCTION__, __( 'You have passed an invalid term.', 'enlightenment' ), '2.0.0' );
            return;
        }
    }

    if ( empty( $args['key'] ) ) {
        _doing_it_wrong( __FUNCTION__, __( 'Please specify a meta `key` argument for your form field.', 'enlightenment' ), '2.0.0' );
        return;
    }

    if ( empty( $args['label'] ) ) {
        _doing_it_wrong( __FUNCTION__, __( 'Please specify a `label` argument for your form field.', 'enlightenment' ), '2.0.0' );
        return;
    }

    if ( ! is_callable( $args['callback'] ) ) {
        _doing_it_wrong( __FUNCTION__, __( 'Please specify a valid function as `callback` argument for your form field.', 'enlightenment' ), '2.0.0' );
        return;
    }

    $cb_args = array(
        'name'  => $args['key'],
        'value' => $args['term'] instanceof WP_Term ? get_term_meta( $args['term']->term_id, $args['key'], true ) : '',
    );

    if ( ! is_array( $args['cb_args'] ) ) {
        $args['cb_args'] = array();
    }

    $cb_args = array_merge( $cb_args, $args['cb_args'] );

	ob_start();

    // We are in the add term screen
    if ( empty( $args['term'] ) ) {
        printf( '<div class="form-field term-%s-wrap">', esc_attr( $args['key'] ) );
    	printf( '<label for="term-%s">%s</label>', esc_attr( $args['key'] ), esc_html( $args['label'] ) );
        call_user_func( $args['callback'], $cb_args );
    	echo '</div>';
    // We are in the edit term screen
    } else {
        printf( '<tr class="form-field term-%s-wrap"><th scope="row">', esc_attr( $args['key'] ) );
    	printf( '<label for="term-%s">%s</label>', esc_attr( $args['key'] ), esc_html( $args['label'] ) );
    	echo '</th><td>';
    	call_user_func( $args['callback'], $cb_args );
    	echo '</td></tr>';
    }

	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_taxonomy_form_field', $output, $args );

	if ( ! $echo ) {
		return $output;
    }

	echo $output;
}
