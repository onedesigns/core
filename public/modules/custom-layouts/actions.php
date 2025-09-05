<?php

function enlightenment_theme_supported_layouts() {
	$layouts = get_theme_support( 'enlightenment-custom-layouts' );
	$layouts = is_array( $layouts ) ? array_shift( $layouts ) : array();

	if( empty( $layouts ) ) {
		_doing_it_wrong(
			"add_theme_support( 'enlightenment-custom-layouts' );",
			__( 'An array of supported layouts needs to be specified as second parameter.', 'enlightenment' ),
			'1.0.0'
		);
	} elseif( 1 == count( $layouts ) ) {
		_doing_it_wrong(
			"add_theme_support( 'enlightenment-custom-layouts' );",
			__( 'At least two layouts need to be supported to use this feature.', 'enlightenment' ),
			'1.0.0'
		);
	} else {
		$available_layouts = enlightenment_available_layouts();

		foreach ( $layouts as $layout ) {
			if( ! array_key_exists( $layout, $available_layouts ) ) {
				_doing_it_wrong(
					"add_theme_support( 'enlightenment-custom-layouts' );",
					sprintf(
						__( 'The layout \'%1$s\' does not exist. You can create it using the <a href="%2$s">enlightenment_register_layout()</a> function.', 'enlightenment' ),
						esc_attr( $layout ),
						esc_url( __( 'https://enlightenmentcore.org/docs/functions/enlightenment_register_layout', 'enlightenment' ) )
					),
					'1.0.0'
				);
			}
		}
	}
}
add_action( 'after_setup_theme', 'enlightenment_theme_supported_layouts', 50 );

function enlightenment_register_custom_layout_post_meta() {
	$post_types = array_merge(
        array(
            'page' => 'page',
        ),
        get_post_types( array(
            'publicly_queryable' => true,
        ) )
    );

	foreach ( $post_types as $key => $post_type ) {
		if ( ! post_type_supports( $post_type, 'custom-fields' ) ) {
			unset( $post_types[ $key ] );
		}
	}

	unset( $post_types['attachment'] );
	unset( $post_types['e-landing-page'] );
	unset( $post_types['e-floating-buttons'] );

	foreach ( $post_types as $post_type ) {
		$args = array(
			'single'        => true,
			'type'          => 'string',
			'show_in_rest'  => true,
			'auth_callback' => 'enlightenment_can_edit_post_type',
		);

		if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
			$breakpoints = enlightenment_bootstrap_breakpoints();
			$properties  = array();

			foreach ( $breakpoints as $breakpoint => $label ) {
				$properties[ $breakpoint ] = array(
					'type' => 'string',
				);
			}

			$args['type']         = 'object';
			$args['show_in_rest'] = array(
				'schema' => array(
					'type'       => 'object',
					'properties' => $properties,
				),
			);
		}

		register_post_meta( $post_type, '_enlightenment_custom_layout', $args );
	}
}
add_action( 'init', 'enlightenment_register_custom_layout_post_meta' );
