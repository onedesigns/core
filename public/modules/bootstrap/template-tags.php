<?php

function enlightenment_bootstrap_color_mode_switcher( $args = null ) {
	$defaults = array(
		'container'                    => 'nav',
		'container_class'              => 'color-mode-switcher',
		'container_id'                 => '',
		'toggle_tag'                   => 'button',
		'toggle_class'                 => 'color-mode-switcher-toggle dropdown-toggle btn btn-link p-0 border-0 rounded-0',
		'toggle_id'                    => 'color-mode-switcher-toggle',
		'toggle_label_format'          => '%1$s %2$s',
		'toggle_extra_atts'            => array(
			'aria-label'     => __( 'Change color scheme', 'enlightenment' ),
			'aria-haspopup'  => 'true',
			'aria-expanded'  => 'false',
		),
		'dropdown_menu_tag'            => 'div',
		'dropdown_menu_class'          => 'color-mode-switcher-menu',
		'dropdown_menu_id'             => 'color-mode-switcher-menu',
		'dropdown_menu_extra_atts'     => array(
			'aria-labelledby' => 'color-mode-switcher-toggle',
		),
		'dropdown_item_tag'            => 'li',
		'dropdown_item_class'          => 'color-mode-switcher-option',
		'dropdown_button_tag'          => is_user_logged_in() ? 'a' : 'button',
		'dropdown_button_class'        => '',
		'dropdown_button_extra_atts'   => is_user_logged_in() ? array(
			'href' => '%s',
		) : array(),
		'dropdown_button_label_format' => '%1$s %2$s',
		'echo'                         => true,
	);

	$defaults = apply_filters( 'enlightenment_bootstrap_color_mode_switcher_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$args['container_class']              .= ' dropdown';
	$args['toggle_extra_atts']             = array_merge( $args['toggle_extra_atts'], array(
		'data-bs-toggle'   => 'dropdown',
		'data-js-selector' => 'color-mode-switcher-toggle',
	) );
	$args['dropdown_menu_class']   .= ' dropdown-menu dropdown-menu-end';
	$args['dropdown_button_class'] .= ' dropdown-item';

	$output = '';

	$color_modes             = enlightenment_bootstrap_get_color_modes();
	$current_color_mode      = enlightenment_bootstrap_get_current_color_mode();
	$current_color_mode_atts = enlightenment_get_color_mode_atts();

	if ( $current_color_mode_atts ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

		$output .= enlightenment_open_tag(
			$args['toggle_tag'],
			$args['toggle_class'],
			$args['toggle_id'],
			$args['toggle_extra_atts']
		);
		$output .= sprintf(
			$args['toggle_label_format'],
			! empty( $current_color_mode_atts['icon'] ) ? sprintf(
				'<i class="current-color-mode-icon fas fa-%s" aria-hidden="true" role="presentation"></i>',
				esc_attr( $current_color_mode_atts['icon'] )
			) : '',
			! empty( $current_color_mode_atts['name'] ) ? sprintf(
				'<span class="current-color-mode-name screen-reader-text visually-hidden">%s</span>',
				esc_html( $current_color_mode_atts['name'] )
			) : '',
		);
		$output .= enlightenment_close_tag( $args['toggle_tag'] );

		$output .= enlightenment_open_tag(
			$args['dropdown_menu_tag'],
			$args['dropdown_menu_class'],
			$args['dropdown_menu_id'],
			$args['dropdown_menu_extra_atts']
		);

		foreach ( $color_modes as $color_mode => $color_mode_atts ) {
			$output .= enlightenment_open_tag( $args['dropdown_item_tag'], $args['dropdown_item_class'] );

			$button_class = $args['dropdown_button_class'];

			if ( $current_color_mode === $color_mode ) {
				$button_class .= ' active';
			}

			$color_mode_url = esc_url( enlightenment_get_current_uri( array(
				'params' => array(
					'action'     => 'enlightenment_color_mode',
					'color_mode' => $color_mode,
					'_wpnonce'   => wp_create_nonce( 'enlightenment_color_mode' ),
				),
			) ) );

			$extra_atts = $args['dropdown_button_extra_atts'];

			foreach ( $extra_atts as $key => $extra_attr ) {
				$extra_atts[ $key ] = sprintf( $extra_attr, $color_mode_url );
			}

			$extra_atts['data-color-mode-value'] = $color_mode;

			if ( $current_color_mode === $color_mode ) {
				$extra_atts['aria-selected'] = 'true';
			}

			$output .= enlightenment_open_tag(
				$args['dropdown_button_tag'],
				$button_class,
				'',
				$extra_atts
			);

			$dropdown_button_label_format = $args['dropdown_button_label_format'];

			$output .= sprintf(
				$dropdown_button_label_format,
				! empty( $color_mode_atts['icon'] ) ? sprintf(
					'<i class="color-mode-icon fas fa-%s" aria-hidden="true" role="presentation"></i>',
					esc_attr( $color_mode_atts['icon'] )
				) : '',
				! empty( $color_mode_atts['name'] ) ? sprintf(
					'<span class="color-mode-name">%s</span>',
					esc_html( $color_mode_atts['name'] )
				) : '',
			);

			$output .= enlightenment_close_tag( $args['dropdown_button_tag'] );
			$output .= enlightenment_close_tag( $args['dropdown_item_tag'] );
		}

		$output .= enlightenment_close_tag( $args['dropdown_menu_tag'] );

		$user_color_mode = '';

		if ( is_user_logged_in() ) {
			$user_color_mode = get_user_meta( get_current_user_id(), '_enlightenment_color_mode', true );
		}

		if ( empty( $user_color_mode ) ) {
			$script = trim( file_get_contents( sprintf(
				'%s/bootstrap-color-mode-switcher%s.js',
				enlightenment_scripts_directory_uri(),
				wp_scripts_get_suffix()
			) ) );

			$script = str_replace( '{}', json_encode( $color_modes ), $script );

			$output .= wp_get_inline_script_tag( $script );
		}

		$output .= enlightenment_close_tag( $args['container'] );
	}

	$output = apply_filters( 'enlightenment_bootstrap_color_mode_switcher', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
