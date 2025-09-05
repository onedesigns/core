<?php

function enlightenment_custom_layouts_gutenberg_panel() {
    global $post;

	// We are not editing a post
	if ( ! $post instanceof WP_Post ) {
		return;
	}

	if ( ! post_type_supports( $post->post_type, 'custom-fields' ) ) {
		return;
	}

	// Elementor landing pages short circuit the theme's template system so
	// there's no need for a custom layout panel.
	if ( 'e-landing-page' == $post->post_type ) {
		return;
	}

	// Elementor floating elements are displayed off-canvas so
	// there's no need for a custom layout panel.
	if ( 'e-floating-buttons' == $post->post_type ) {
		return;
	}

	if ( ! get_post_type_object( $post->post_type )->public ) {
		return;
	}

    wp_enqueue_style(
        'enlightenment-custom-layouts',
        enlightenment_styles_directory_uri() . '/custom-layouts.css',
        array( 'enlightenment-editor-panels' )
    );

    wp_enqueue_script(
        'enlightenment-custom-layouts',
        enlightenment_scripts_directory_uri() . '/custom-layouts.js',
        array( 'wp-plugins', 'wp-edit-post', 'wp-components', 'wp-compose', 'wp-data', 'wp-element' )
    );

    $post_name = strtolower( get_post_type_object( $post->post_type )->labels->singular_name );
    $layouts   = enlightenment_custom_layouts();
    $templates = enlightenment_archive_layouts();
    $args      = array(
        'panel_title'     => __( 'Layout', 'enlightenment' ),
        'options'         => array(),
		'template_layout' => $templates[ $post->post_type ],
    );

    if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
		$prefixes  = enlightenment_bootstrap_breakpoint_prefixes();

        $args['bootstrap'] = true;
        $args['default']   = sprintf( __( 'Use global layout for %s', 'enlightenment' ), $post_name );
		$args['prefixes']  = $prefixes;

        $breakpoints = enlightenment_bootstrap_breakpoints();
        $prev        = '';

        foreach ( $breakpoints as $breakpoint => $label ) {
             $option = array(
                 'breakpoint' => $breakpoint,
				 'prefix'     => $prefixes[ $breakpoint ],
                 'label'      => $label,
                 'layouts'    => array(),
             );

            if( '' != $prev ) {
                $option['layouts'][] = array(
                    'label' => sprintf( __( 'Inherit from %s', 'enlightenment' ), $prev ),
                    'image' => '',
                    'value' => 'inherit',
                );
            }

            foreach ( $layouts as $layout => $atts ) {
                $option['layouts'][] = array(
                    'label' => $atts['name'],
                    'image' => $atts['image'],
                    'value' => $layout,
                );
            }

            $args['options'][] = $option;

            $prev = $label;
        }
    } else {
        $args['bootstrap'] = false;

        $args['options'][] = array(
            'label' => sprintf( __( 'Use global layout for %s', 'enlightenment' ), $post_name ),
            'image' => '',
            'value' => '',
        );

        foreach ( $layouts as $layout => $atts ) {
            $args['options'][] = array(
                'label' => $atts['name'],
                'image' => $atts['image'],
                'value' => $layout,
            );
        }
    }

    wp_localize_script( 'enlightenment-custom-layouts', 'enlightenment_custom_layouts_args', $args );
}
add_action( 'enqueue_block_editor_assets', 'enlightenment_custom_layouts_gutenberg_panel' );

function enlightenment_set_block_editor_layout_body_class( $class ) {
    global $current_screen, $post;

	if (
		! isset( $current_screen )
		||
		! $current_screen->is_block_editor()
		||
		! $post instanceof WP_Post
	) {
		return $class;
	}

    $layout = enlightenment_get_layout( enlightenment_current_layout() );

    if( ! empty( $layout['body_class'] ) ) {
		$class .= sprintf( ' enlightenment-%s ', $layout['body_class'] );
	}

    return $class;
}
add_filter( 'admin_body_class', 'enlightenment_set_block_editor_layout_body_class' );
