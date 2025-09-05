<?php

function enlightenment_register_sidebars_args( $args = null ) {
	$defaults = array(
		'before_widget' => '<aside id="%1$s" class="widget %2$s">' . "\n",
		'after_widget'  => '</aside>' . "\n",
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>' . "\n",
	);
	$defaults = apply_filters( 'enlightenment_register_sidebars_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	return $args;
}

function enlightenment_site_title_home_link( $title ) {
	$defaults = array(
		'container_class'      => '',
		'container_id'         => '',
		'container_extra_atts' => array( 'href' => home_url( '/' ), 'rel' => 'home' ),
	);
	$args = apply_filters( 'enlightenment_site_title_home_link_args', $defaults );

	$output  = enlightenment_open_tag( 'a',  $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= $title;
	$output .= enlightenment_close_tag( 'a' );

	return $output;
}
add_filter( 'enlightenment_site_title_format', 'enlightenment_site_title_home_link', 30 );

function enlightenment_sidebar_ob_start() {
	if ( is_admin() ) {
		return;
	}

	ob_start();
}
add_action( 'dynamic_sidebar_before', 'enlightenment_sidebar_ob_start', 9999 );

function enlightenment_filter_widget() {
	if ( is_admin() ) {
		return;
	}

	$output = ob_get_clean();

	if ( isset( $GLOBALS['enlightenment_widget'] ) ) {
		$widget    = $GLOBALS['enlightenment_widget'];
		$instances = $widget['callback'][0]->get_settings();
		if ( array_key_exists( $widget['callback'][0]->number, $instances ) ) {
			$instance = $instances[ $widget['callback'][0]->number ];
		}

		$output = apply_filters( sprintf( 'enlightenment_widget_%s', $widget['callback'][0]->id_base ), $output, $widget, $instance );
		$output = apply_filters( 'enlightenment_widget', $output, $widget, $instance );

		unset( $GLOBALS['enlightenment_widget'] );
	}

	echo $output;
}
add_action( 'dynamic_sidebar', 'enlightenment_filter_widget', 1 );

function enlightenment_widget_loop( $widget ) {
	if ( is_admin() ) {
		return;
	}

	$GLOBALS['enlightenment_widget'] = $widget;

	ob_start();
}
add_action( 'dynamic_sidebar', 'enlightenment_widget_loop', 9999 );

add_action( 'dynamic_sidebar_after', 'enlightenment_filter_widget', 1 );

add_action( 'before_signup_form', 'enlightenment_ob_start' );

function enlightenment_filter_signup_form() {
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_filter_signup_form', $output );

	echo $output;
}
add_action( 'after_signup_form', 'enlightenment_filter_signup_form' );
