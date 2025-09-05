<?php

function enlightenment_bootstrap_theme_support_args() {
	$defaults = array(
		'load_styles'       => true,
		'load_scripts'      => true,
		'color-mode'        => 'light',
		'navbar-expand'     => 'lg',
		'navbar-position'   => 'static-top',
		'navbar-color'      => 'body',
		'navbar-background' => 'body',
	);

	$args = get_theme_support( 'enlightenment-bootstrap' );
	$args = is_array( $args ) ? array_shift( $args ) : array();
	$args = wp_parse_args( $args, $defaults );

	global $_wp_theme_features;

	if( ! is_array( $_wp_theme_features['enlightenment-bootstrap'] ) ) {
		$_wp_theme_features['enlightenment-bootstrap'] = array();
	}

	$_wp_theme_features['enlightenment-bootstrap'][0] = $args;
}
add_action( 'after_setup_theme', 'enlightenment_bootstrap_theme_support_args', 50 );

function enlightenment_bootstrap_set_user_color_mode() {
	if ( ! is_user_logged_in() ) {
        return;
    }

    if ( ! isset( $_REQUEST['action'] ) ) {
        return;
    }

	if ( 'enlightenment_color_mode' != $_REQUEST['action'] ) {
        return;
    }

	if ( ! isset( $_REQUEST['color_mode'] ) ) {
        return;
    }

	if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'enlightenment_color_mode' ) ) {
		return;
	}

	$color_mode = $_REQUEST['color_mode'];

	if ( empty( $color_mode ) ) {
		delete_user_meta( get_current_user_id(), '_enlightenment_color_mode' );
	} else {
		$color_modes = array_keys( enlightenment_bootstrap_get_color_modes() );

		if ( ! in_array( $color_mode, $color_modes ) ) {
			return;
		}

		update_user_meta( get_current_user_id(), '_enlightenment_color_mode', $color_mode );
	}

	if ( ! wp_doing_ajax() ) {
		header( 'Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0' );
		header( 'Pragma: no-cache' );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s \G\M\T', 0 ) );

	    wp_safe_redirect( remove_query_arg(
			array( 'action', 'color_mode', '_wpnonce' ),
			enlightenment_get_current_uri()
		) );
	}

    exit();
}
add_action( 'template_redirect', 'enlightenment_bootstrap_set_user_color_mode' );
add_action( 'wp_ajax_enlightenment_color_mode', 'enlightenment_bootstrap_set_user_color_mode' );

function enlightenment_print_bootstrap_color_mode_script() {
	$ignore_local_storage = false;

	if ( is_user_logged_in() ) {
		$user_meta = get_user_meta( get_current_user_id(), '_enlightenment_color_mode', true );

		if ( ! empty( $user_meta ) && 'auto' != $user_meta ) {
			return;
		}

		if ( 'auto' == $user_meta ) {
			$ignore_local_storage = true;
		}
	}

	$script = trim( file_get_contents( sprintf(
		'%s/bootstrap-color-mode%s.js',
		enlightenment_scripts_directory_uri(),
		wp_scripts_get_suffix()
	) ) );

	if ( $ignore_local_storage ) {
		$script = str_replace(
			array(
				'e=!0',
				'useLocalStorage = true',
			),
			array(
				'e=!1',
				'useLocalStorage = false',
			),
			$script
		);
	}

	wp_print_inline_script_tag( $script );
}
add_action( 'enlightenment_head', 'enlightenment_print_bootstrap_color_mode_script' );

function enlightenment_enqueue_bootstrap_script() {
	if ( ! current_theme_supports( 'enlightenment-bootstrap', 'load_scripts' ) ) {
		return;
	}

	wp_enqueue_script( 'bootstrap' );
}
add_action( 'wp_enqueue_scripts', 'enlightenment_enqueue_bootstrap_script' );

function enlightenment_add_header_container() {
	$theme_support = get_theme_support( 'enlightenment-bootstrap' );
	$header_class  = enlightenment_site_header_class( array( 'echo' => false ) );

	if( false !== strpos( $header_class, 'sticky-' ) || false !== strpos( $header_class, 'fixed-' ) ) {
		add_action( 'enlightenment_site_header', 'enlightenment_open_container', 1 );
		add_action( 'enlightenment_site_header', 'enlightenment_close_container', 999 );
	}
}
add_action( 'init', 'enlightenment_add_header_container' );

function enlightenment_bootstrap_remove_wpmu_signup_stylesheet() {
	remove_action( 'wp_head', 'wpmu_signup_stylesheet' );
}
add_action( 'get_header', 'enlightenment_bootstrap_remove_wpmu_signup_stylesheet' );

add_action( 'enlightenment_before_site_content', 'enlightenment_open_container', 997 );
add_action( 'enlightenment_before_site_content', 'enlightenment_open_row', 998 );

add_action( 'enlightenment_after_site_content', 'enlightenment_close_row', 11 );
add_action( 'enlightenment_after_site_content', 'enlightenment_close_container', 12 );

remove_action( 'enlightenment_comment_header', 'enlightenment_comment_author_avatar', 10, 2 );

add_action( 'enlightenment_before_comment_header', 'enlightenment_comment_author_avatar', 8, 2 );

function enlightenment_open_comment_content_wrap() {
	echo enlightenment_open_tag( 'div', 'flex-grow-1' );
}
add_action( 'enlightenment_before_comment_header', 'enlightenment_open_comment_content_wrap' );

add_action( 'enlightenment_after_comment_end_callback', 'enlightenment_close_div' );

function enlightenment_add_footer_container() {
	add_action( 'enlightenment_site_footer', 'enlightenment_open_container', 1 );
	add_action( 'enlightenment_site_footer', 'enlightenment_close_container', 999 );
}
add_action( 'init', 'enlightenment_add_footer_container' );

function enlightenment_bootstrap_yoast_breadcrumbs_remove_breadcrumbs_separator_control( $wp_customize ) {
	$wp_customize->remove_control( 'wpseo-breadcrumbs-separator' );
}
add_action( 'customize_register', 'enlightenment_bootstrap_yoast_breadcrumbs_remove_breadcrumbs_separator_control', 12 );

function enlightenment_bootstrap_user_color_mode_option( $user ) {
	$current_color_mode = get_user_meta( $user->ID, '_enlightenment_color_mode', true );
	$color_modes        = array_merge( array(
		'' => __( 'Default',  'enlightenment' ),
	), array_map( function( $atts ) {
		return $atts['name'];
	}, enlightenment_bootstrap_get_color_modes() ) );

	?>
	<tr class="user-color-mode-wrap">
		<th scope="row">
			<?php /* translators: The user color scheme selection field label. */ ?>
			<label for="color-mode"><?php _e( 'Color Scheme', 'enlightenment' ); ?></label>
		</th>
		<td>
			<select name="enlightenment_color_mode" id="enlightenment_color_mode">
				<?php foreach ( $color_modes as $color_mode => $name ) : ?>
					<option value="<?php echo esc_attr( $color_mode ); ?>" <?php selected( $color_mode, $current_color_mode ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<?php
}
add_action( 'personal_options', 'enlightenment_bootstrap_user_color_mode_option' );

function enlightenment_bootstrap_update_user_color_mode_option( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	if ( ! isset( $_POST['enlightenment_color_mode'] ) ) {
		return;
	}

	$color_mode = $_POST['enlightenment_color_mode'];

	if ( empty( $color_mode ) ) {
		delete_user_meta( $user_id, '_enlightenment_color_mode' );

		return;
	}

 	$color_modes = array_keys( enlightenment_bootstrap_get_color_modes() );

	if ( ! in_array( $color_mode, $color_modes ) ) {
		return;
	}

	update_user_meta( $user_id, '_enlightenment_color_mode', $color_mode );
}
add_action( 'edit_user_profile_update', 'enlightenment_bootstrap_update_user_color_mode_option' );
add_action( 'personal_options_update', 'enlightenment_bootstrap_update_user_color_mode_option' );
