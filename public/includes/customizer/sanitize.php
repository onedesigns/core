<?php

/**
 * Sanitize checkbox inputs.
 */
function enlightenment_sanitize_checkbox( $input ) {
	return (bool) $input;
}

/**
 * Sanitize multiple checkboxes inputs.
 */
function enlightenment_sanitize_multiple_checkboxes( $input ) {
	foreach( $input as $key => $value ) {
		$input[ $key ] = sanitize_text_field( $value );
	}

	return $input;
}

/**
 * Sanitize editable dropdown.
 */
function enlightenment_sanitize_editable_dropdown( $input, $setting ) {
	if ( empty( $input ) ) {
		return '';
	}

	$input = strval( $input );

	$control = $setting->manager->get_control( $setting->id );

	if ( ! empty( $control->accepted_units ) ) {
		$accepted_units = array_values( $control->accepted_units );

		if ( in_array( '', $accepted_units ) && is_numeric( $input ) ) {
			return $input;
		}

		/**
		 * This hack ensures the sanitize function does not accidentally
		 * recognize em as the valid unit when rem unit is used but they
		 * were entered as accepted units in the order em, rem.
		 */
		if ( in_array( 'rem', $accepted_units ) && in_array( 'em', $accepted_units ) ) {
			$accepted_units = array_unique( array_merge( array( 'rem', 'em' ), $accepted_units ) );
		}

		$has_unit = false;
		$unit     = '';

		foreach ( $accepted_units as $accepted_unit ) {
			if ( strpos( $value, $accepted_unit ) === strlen( $value ) - strlen( $accepted_unit ) ) {
				$has_unit = true;
				$unit     = $accepted_unit;

				break;
			}
		}

		if ( ! $has_unit ) {
			if ( is_numeric( $input ) ) {
				if ( empty( $control->default_unit ) ) {
					$default_unit = $accepted_units[0];
				} else {
					$default_unit = $control->default_unit;
				}

				$input = sprintf( '%s%s', $input, $default_unit );
			} elseif ( ! is_numeric( substr( $input, 0, -strlen( $unit ) ) ) ) {
				$input = '';
			}
		}
	}

	return $input;
}

/**
 * Sanitize template hook.
 */
function enlightenment_sanitize_template_hook( $input ) {
	$templates = enlightenment_templates();
	$tpl_hooks = enlightenment_template_hooks();

	foreach ( $input as $template => $hooks ) {
		if ( array_key_exists( $template, $templates ) ) {
			foreach ( $hooks as $hook => $functions ) {
				$functions = explode( ',', $functions );

				foreach ( $functions as $key => $function ) {
					if ( ! in_array( $function, $tpl_hooks[ $hook ]['functions'] ) ) {
						unset( $functions[ $key ] );
					}
				}

				$input[ $template ][ $hook ] = $functions;
			}
		} else {
			unset( $input[ $template ] );
		}
	}

	return $input;
}
