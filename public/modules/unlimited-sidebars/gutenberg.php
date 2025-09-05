<?php

function enlightenment_unlimited_sidebars_gutenberg_panel() {
    global $post, $wp_registered_sidebars;

	// We are not editing a post
	if ( ! $post instanceof WP_Post ) {
		return;
	}

	if ( ! post_type_supports( $post->post_type, 'custom-fields' ) ) {
		return;
	}

	// Elementor landing pages short circuit the theme's template system so
	// there's no need for an unlimited sidebats panel.
	if ( 'e-landing-page' == $post->post_type ) {
		return;
	}

	// Elementor floating elements are displayed off-canvas so
	// there's no need for an unlimited sidebats panel.
	if ( 'e-floating-buttons' == $post->post_type ) {
		return;
	}

	if ( ! get_post_type_object( $post->post_type )->public ) {
		return;
	}

    wp_enqueue_script(
        'enlightenment-unlimited-sidebars',
        enlightenment_scripts_directory_uri() . '/unlimited-sidebars.js',
        array( 'wp-plugins', 'wp-edit-post', 'wp-components', 'wp-compose', 'wp-data', 'wp-element' )
    );

    $post_name = strtolower( get_post_type_object( $post->post_type )->labels->singular_name );
	$settings  = enlightenment_sidebar_locations();
	$locations = $settings[ $post->post_type ];
    $args      = array(
        'panel_title'        => __( 'Sidebars', 'enlightenment' ),
		'default'           => sprintf( __( 'Use global sidebar locations for %s', 'enlightenment' ), $post_name ),
		'sidebars'           => array(),
        'locations'          => array(),
		'template_locations' => array(),
    );

	foreach ( $wp_registered_sidebars as $sidebar => $atts ) {
		$args['sidebars'][] = array(
			'value' => $sidebar,
			'label' => $atts['name'],
		);
	}

	foreach ( $locations as $location => $sidebar ) {
		$args['locations'][] = array(
			'name'  => $location,
			'label' => $sidebar['name'],
		);
	}

	foreach ( $locations as $location => $sidebar ) {
		$args['template_locations'][ $location ] = $sidebar['sidebar'];
	}

	wp_localize_script( 'enlightenment-unlimited-sidebars', 'enlightenment_unlimited_sidebars_args', $args );
}
add_action( 'enqueue_block_editor_assets', 'enlightenment_unlimited_sidebars_gutenberg_panel' );
