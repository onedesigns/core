<?php

function enlightenment_open_container( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'container',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output = apply_filters( 'enlightenment_open_container', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_open_container_fluid( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'container-fluid',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_container_fluid_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output = apply_filters( 'enlightenment_open_container', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_close_container( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'container',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_close_tag( $args['container'] );
	$output = apply_filters( 'enlightenment_close_container', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_close_container_fluid( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'container',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_close_tag( $args['container'] );
	$output = apply_filters( 'enlightenment_close_container_fluid', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_open_row( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'row',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_row_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output = apply_filters( 'enlightenment_open_row', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_close_row( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'row',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_row_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_close_tag( $args['container'] );
	$output = apply_filters( 'enlightenment_close_row', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bootstrap_breakpoints() {
	$breakpoints = array(
		'smartphone-portrait'  => __( 'Smartphone Portrait',    'enlightenment' ),
		'smartphone-landscape' => __( 'Smartphone Landscape',   'enlightenment' ),
		'tablet-portrait'      => __( 'Tablet Portrait',        'enlightenment' ),
		'tablet-landscape'     => __( 'Tablet Landscape',       'enlightenment' ),
		'desktop-laptop'       => __( 'Desktops &amp; Laptops', 'enlightenment' ),
	);

	return apply_filters( 'enlightenment_bootstrap_breakpoints', $breakpoints );
}

function enlightenment_bootstrap_breakpoint_prefixes() {
	$prefixes = array(
		'smartphone-portrait'  => '',
		'smartphone-landscape' => '-sm',
		'tablet-portrait'      => '-md',
		'tablet-landscape'     => '-lg',
		'desktop-laptop'       => '-xl',
	);

	return apply_filters( 'enlightenment_bootstrap_breakpoint_prefixes', $prefixes );
}

function enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint ) {
	$prefixes = enlightenment_bootstrap_breakpoint_prefixes();

	if( array_key_exists( $breakpoint, $prefixes ) ) {
		return $prefixes[ $breakpoint ];
	}

	return false;
}

function enlightenment_bootstrap_get_color_modes() {
	return apply_filters( 'enlightenment_bootstrap_color_modes', array(
		'auto'  => array(
			'name' => __( 'Auto',  'enlightenment' ),
			'icon' => 'adjust',
		),
		'light' => array(
			'name' => __( 'Light', 'enlightenment' ),
			'icon' => 'sun',
		),
		'dark'  => array(
			'name' => __( 'Dark',  'enlightenment' ),
			'icon' => 'moon',
		),
	) );
}

function enlightenment_bootstrap_get_current_color_mode() {
	$color_mode  = current_theme_supports( 'enlightenment-bootstrap', 'color-mode' );
	$color_modes = array_keys( enlightenment_bootstrap_get_color_modes() );

	if ( is_user_logged_in() ) {
		$user_meta = get_user_meta( get_current_user_id(), '_enlightenment_color_mode', true );

		if ( in_array( $user_meta, $color_modes ) ) {
			$color_mode = $user_meta;
		}
	}

	return apply_filters( 'enlightenment_bootstrap_current_color_mode', $color_mode );
}

function enlightenment_get_color_mode_atts( $color_mode = '' ) {
	if ( empty( $color_mode ) ) {
		$color_mode = enlightenment_bootstrap_get_current_color_mode();
	}

	$color_modes = enlightenment_bootstrap_get_color_modes();

	if ( isset( $color_modes[ $color_mode ] ) ) {
		return $color_modes[ $color_mode ];
	}

	return false;
}
