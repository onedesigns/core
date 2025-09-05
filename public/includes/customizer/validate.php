<?php

function enlightenment_validate_editable_dropdown( $validity, $value, $setting ) {
	if ( empty( $value ) ) {
		return $validity;
	}

	$control = $setting->manager->get_control( $setting->id );

	if ( ! empty( $control->accepted_units ) ) {
		$accepted_units = array_values( $control->accepted_units );

		if ( in_array( '', $accepted_units ) && is_numeric( $value ) ) {
			return $validity;
		}

		/**
		 * This hack ensures the validation function does not accidentally
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
			if ( in_array( '', $accepted_units ) ) {
				$validity->add(
					'required',
					sprintf(
						__( 'Invalid unit. Accepted values are: %s and no unit.', 'enlightenment' ),
						join( ', ', array_diff( $control->accepted_units, array( '' ) ) )
					)
				);
			} else {
				$validity->add(
					'required',
					sprintf(
						__( 'Invalid unit. Accepted values are: %s', 'enlightenment' ),
						join( ', ', $control->accepted_units )
					)
				);
			}
		} else {
			if ( ! is_numeric( substr( $value, 0, -strlen( $unit ) ) ) ) {
				$validity->add(
					'required',
					__( 'Please enter a valid number.', 'enlightenment' ),
				);
			}
		}
	}

	return $validity;
}
