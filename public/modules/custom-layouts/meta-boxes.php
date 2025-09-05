<?php

function enlightenment_custom_layout_meta_boxes_scripts( $hook ) {
    if ( 'post.php' != $hook ) {
        return;
    }

    if ( WP_Screen::get()->is_block_editor() ) {
        return;
    }

    wp_enqueue_style( 'enlightenment-admin-form-controls' );
    wp_enqueue_script( 'enlightenment-admin-form-controls' );
}
add_action( 'admin_enqueue_scripts', 'enlightenment_custom_layout_meta_boxes_scripts' );

function enlightenment_custom_layout_meta_boxes() {
    if ( WP_Screen::get()->is_block_editor() ) {
        return;
    }

	$post_types = array_merge(
        array(
            'page' => 'page',
        ),
        get_post_types( array(
            'publicly_queryable' => true,
        ) )
    );

	unset( $post_types['attachment'] );
	unset( $post_types['e-landing-page'] );
	unset( $post_types['e-floating-buttons'] );

	foreach ( $post_types as $post_type ) {
		add_meta_box(
            'enlightenment_custom_layout',
            __( 'Layout', 'enlightenment' ),
            'enlightenment_custom_layout_form',
            $post_type,
            'side',
            'low'
        );
    }
}
add_action( 'add_meta_boxes', 'enlightenment_custom_layout_meta_boxes' );

function enlightenment_custom_layout_form( $post ) {
    $post_meta = get_post_meta( $post->ID, '_enlightenment_custom_layout', true );

	wp_nonce_field( 'enlightenment_custom_layout_form', 'enlightenment_custom_layout_form_nonce' );

    if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
        echo '<div class="default-layout"><label>';
    	printf( '<input name="enlightenment_default_custom_layout" value="1" type="checkbox" %s /> ', checked( empty( $post_meta ), true, false ) );
    	printf( __( 'Use global layout for %s', 'enlightenment' ), strtolower( get_post_type_object( $post->post_type )->labels->singular_name ) );
    	echo '</label></div>';

        printf( '<div class="custom-layouts%s">', empty( $post_meta ) ? ' hidden' : '' );

        $breakpoints = enlightenment_bootstrap_breakpoints();
        $layouts     = enlightenment_archive_layouts();
        $layout      = $layouts[ $post->post_type ];
        $prev        = '';

        foreach ( $breakpoints as $breakpoint => $label ) {
            $value = isset( $post_meta[ $breakpoint ] ) ? $post_meta[ $breakpoint ] : $layout[ $breakpoint ];

            echo '<div class="custom-layout">';

            printf( '<h3 class="custom-layout-title">%s</h3>', $label );

            if( '' != $prev ) {
                echo '<p><label>';
            	printf( '<input name="enlightenment_custom_layout[%s]" value="inherit" type="radio" %s /> ', $breakpoint, checked( $value, 'inherit', false ) );
            	printf( __( 'Inherit from %s', 'enlightenment' ), $prev );
            	echo '</label></p>';
			}

            enlightenment_layout_options( array(
        		'name'  => sprintf( 'enlightenment_custom_layout[%s]', $breakpoint ),
        		'value' => $value,
        	) );

            echo '</div>';

            $prev = $label;
        }

        echo '</div>';
    } else {
    	echo '<p><label>';
    	printf( '<input name="enlightenment_custom_layout" value="" type="radio" %s /> ', checked( $post_meta, '', false ) );
    	printf( __( 'Use global layout for %s', 'enlightenment' ), strtolower( get_post_type_object( $post->post_type )->labels->singular_name ) );
    	echo '</label></p>';

    	enlightenment_layout_options( array(
    		'name'  => 'enlightenment_custom_layout',
    		'value' => $post_meta,
    	) );
    }
}

function enlightenment_layout_options( $args, $echo = true ) {
	$layouts = enlightenment_custom_layouts();
	$buttons = array();

	foreach ( $layouts as $layout => $atts ) {
		$buttons[] = array(
			'label' => $atts['name'],
			'image' => $atts['image'],
			'value' => $layout,
		);
	}

	$layouts = enlightenment_archive_layouts();
	$layouts['default'] = '';

	$defaults = array(
		'buttons'     => $buttons,
		'value'       => $layouts[ get_post_type() ],
	);
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_image_radio_buttons( $args, false );
	$output = apply_filters( 'enlightenment_layout_options', $output, $args );

	if ( ! $echo ) {
		return $output;
    }

	echo $output;
}

function enlightenment_custom_layout_form_save_postdata( $post_id ) {
	if ( ! isset( $_POST['enlightenment_custom_layout'] ) ) {
		return;
    }

	$nonce = $_POST['enlightenment_custom_layout_form_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'enlightenment_custom_layout_form' ) ) {
		return;
    }

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
    }

	$post = get_post( $post_id );

	if ( ! current_user_can( get_post_type_object( $post->post_type )->cap->edit_post, $post_id ) ) {
		return;
    }

	$layouts = enlightenment_custom_layouts();

    if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
        if ( ! is_array( $_POST['enlightenment_custom_layout'] ) ) {
            return;
        }

        if ( isset( $_POST['enlightenment_default_custom_layout'] ) && '1' == $_POST['enlightenment_default_custom_layout'] ) {
            delete_post_meta( $post_id, '_enlightenment_custom_layout' );
            return;
        }

        $breakpoints = enlightenment_bootstrap_breakpoints();
        $post_meta   = get_post_meta( $post->ID, '_enlightenment_custom_layout', true );
        $layouts_opt = enlightenment_archive_layouts();
        $tmpl_layout = $layouts_opt[ $post->post_type ];
        $layout      = array();

        foreach ( $breakpoints as $breakpoint => $label ) {
            if ( ! isset( $_POST['enlightenment_custom_layout'][ $breakpoint ] ) ) {
                if ( isset( $post_meta[ $breakpoint ] ) ) {
                    $layout[ $breakpoint ] = $post_meta[ $breakpoint ];
                } elseif ( isset( $tmpl_layout[ $breakpoint ] ) ) {
                    $layout[ $breakpoint ] = $tmpl_layout[ $breakpoint ];
                }

                continue;
            }

            $value = sanitize_text_field( $_POST['enlightenment_custom_layout'][ $breakpoint ] );

            if ( ! array_key_exists( $value, $layouts ) && 'inherit' !== $value ) {
                if ( isset( $post_meta[ $breakpoint ] ) ) {
                    $layout[ $breakpoint ] = $post_meta[ $breakpoint ];
                } elseif ( isset( $tmpl_layout[ $breakpoint ] ) ) {
                    $layout[ $breakpoint ] = $tmpl_layout[ $breakpoint ];
                }

                continue;
            }

            $layout[ $breakpoint ] = $value;
        }
    } else {
        $layout = sanitize_text_field( $_POST['enlightenment_custom_layout'] );

        if ( empty( $layout ) ) {
            delete_post_meta( $post_id, '_enlightenment_custom_layout' );
            return;
        }

        if ( ! array_key_exists( $layout, $layouts ) ) {
            return;
        }
    }

    update_post_meta( $post_id, '_enlightenment_custom_layout', $layout );
}
add_action( 'save_post', 'enlightenment_custom_layout_form_save_postdata' );
