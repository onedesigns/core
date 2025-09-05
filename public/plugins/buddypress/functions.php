<?php

function enlightenment_bp_get_asset_handle( $file = 'buddypress', $type = 'css' ) {
	if ( current_theme_supports( 'buddypress' ) ) {
		return false;
	}

	$locations = array();
	$min       = bp_core_get_minified_asset_suffix();

	// Ensure the assets can be located when running from /src/.
	if ( defined( 'BP_SOURCE_SUBDIRECTORY' ) && BP_SOURCE_SUBDIRECTORY === 'src' ) {
		$min = '';
	}

	$file = "{$file}{$min}.{$type}";

	// Subdirectories within the top-level $locations directories.
	$subdirs = array(
		'buddypress/' . $type,
		'community/' . $type,
		$type,
	);

	// No need to check child if template == stylesheet.
	if ( is_child_theme() ) {
		$locations['bp-child'] = array(
			'dir'  => get_stylesheet_directory(),
			'file' => str_replace( $min, '', $file ),
		);
	}

	$locations['bp-parent'] = array(
		'dir'  => get_template_directory(),
		'file' => str_replace( $min, '', $file ),
	);

	$locations['bp-legacy'] = array(
		'dir'  => bp_get_theme_compat_dir(),
		'file' => $file,
	);

	foreach ( $locations as $location_type => $location ) {
		foreach ( $subdirs as $subdir ) {
			if ( file_exists( trailingslashit( $location['dir'] ) . trailingslashit( $subdir ) . $location['file'] ) ) {
				return "{$location_type}-{$type}";
			}
		}
	}
}

function enlightenment_bp_get_css_handle() {
	return enlightenment_bp_get_asset_handle( 'buddypress', 'css' );
}

function enlightenment_bp_get_js_handle() {
	return enlightenment_bp_get_asset_handle( 'buddypress', 'js' );
}

function enlightenment_bp_is_access_restricted() {
	$queried_object = get_queried_object();

	if ( ! $queried_object instanceof WP_Post ) {
		return false;
	}

	if ( 'buddypress' !== get_post_type( $queried_object ) ) {
		return false;
	}

	if ( 0 === $queried_object->ID ) {
		return true;
	}

	return ! bp_current_user_can( 'bp_view', array(
		'bp_component' => bp_core_get_component_from_directory_page_id( $queried_object->ID ),
	) );
}

function enlightenment_bp_is_user_page() {
	return bp_is_user() && ! bp_is_user_activity();
}

function enlightenment_bp_is_group_activity() {
	return ( bp_is_group_home() && bp_is_active( 'activity' ) ) || bp_is_group_activity();
}

function enlightenment_bp_is_group_page() {
	return bp_is_group() && ! enlightenment_bp_is_group_activity();
}

function enlightenment_open_buddypress_container() {
	echo enlightenment_open_tag( 'div', bp_nouveau_get_container_classes(), 'buddypress' );
}

function enlightenment_bp_before_groups_page() {
	bp_nouveau_groups_create_hook( 'before', 'page' );
}

function enlightenment_bp_after_groups_page() {
	bp_nouveau_groups_create_hook( 'after', 'page' );
}

function enlightenment_bp_before_groups_content_template() {
	bp_nouveau_groups_create_hook( 'before', 'content_template' );
}

function enlightenment_bp_after_groups_content_template() {
	bp_nouveau_groups_create_hook( 'after', 'content_template' );
}

function enlightenment_bp_before_blogs_content_template() {
	bp_nouveau_blogs_create_hook( 'before', 'content_template' );
}

function enlightenment_bp_after_blogs_content_template() {
	bp_nouveau_blogs_create_hook( 'after', 'content_template' );
}

function enlightenment_bp_before_blogs_content() {
	bp_nouveau_blogs_create_hook( 'before', 'content' );
}

function enlightenment_bp_after_blogs_content() {
	bp_nouveau_blogs_create_hook( 'after', 'content' );
}

function enlightenment_bp_before_member_header() {
	do_action( 'bp_before_member_header' );
}

function enlightenment_bp_after_member_header() {
	do_action( 'bp_after_member_header' );
}

function enlightenment_bp_before_member_home_content() {
	bp_nouveau_member_hook( 'before', 'home_content' );
}

function enlightenment_bp_after_member_home_content() {
	bp_nouveau_member_hook( 'after', 'home_content' );
}

function enlightenment_bp_setup_single_group_object() {
	if ( bp_has_groups() ) {
		while ( bp_groups() ) {
			bp_the_group();
		}
	}
}

function enlightenment_bp_before_group_header() {
	do_action( 'bp_before_group_header' );
}

function enlightenment_bp_after_group_header() {
	do_action( 'bp_after_group_header' );
}

function enlightenment_bp_before_group_home_content() {
	bp_nouveau_group_hook( 'before', 'home_content' );
}

function enlightenment_bp_after_group_home_content() {
	bp_nouveau_group_hook( 'after', 'home_content' );
}

function enlightenment_bp_get_cover_image( $args = null ) {
	$defaults = array(
		'component' => null,
		'item_id'   => null,
	);
	$defaults = apply_filters( 'enlightenment_bp_get_cover_image_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	if ( empty( $args['component'] ) || empty( $args['item_id'] ) ) {
		if ( bp_is_user() ) {
			$args['component'] = 'members';
			$args['item_id']   = bp_displayed_user_id();
		} elseif ( bp_is_group() ) {
			$args['component'] = 'groups';
			$args['item_id']   = bp_get_current_group_id();
		} else {
			$cover_image_object = apply_filters( 'bp_current_cover_image_object_inline_css', array() );

			if ( ! empty( $cover_image_object['component'] ) ) {
				$args['component'] = $cover_image_object['component'];
			}

			if ( ! empty( $cover_image_object['object'] ) && ! empty( $cover_image_object['object']->id ) ) {
				$args['item_id'] = $cover_image_object['object']->id;
			}
		}
	}

	$output = '';

	if (
		! empty( $args['component'] )
		&&
		! empty( $args['item_id'] )
		&&
		bp_is_active( $args['component'], 'cover_image' )
	) {
		// Get the settings of the cover image feature for the current component.
		$params = bp_attachments_get_cover_image_settings( $args['component'] );

		if ( ! empty( $params ) ) {
			$object_dir = $args['component'];

			if ( 'members' === $object_dir ) {
				$object_dir = 'members';
			}

			$output = bp_attachments_get_attachment( 'url', array(
				'object_dir' => $object_dir,
				'item_id'    => $args['item_id'],
			) );

			if ( empty( $output ) ) {
				if ( ! empty( $params['default_cover'] ) ) {
					$output = $params['default_cover'];
				}
			}
		}
	}

	return apply_filters( 'enlightenment_bp_get_cover_image', $output );
}

function enlightenment_bp_page_header_open_container( $args = null ) {
	if( bp_is_user() ) {
		$component = 'members';
		$item_id   = bp_displayed_user_id();
	} elseif ( bp_is_group() ) {
		$component = 'groups';
		$item_id   = bp_get_group_id();
	} else {
		return false;
	}

	$defaults = array(
		'container_class'      => sprintf( '%s-header single-headers', $component ),
		'container_id'         => 'item-header',
		'container_extra_atts' => array(
			'role'                   => 'complementary',
			'data-bp-item-id'        => $item_id,
			'data-bp-item-component' => $component,
		),
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_page_header_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( 'div', $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	$output = apply_filters( 'enlightenment_bp_page_header_open_container', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_cover_image_maybe_open_container( $args = null ) {
	if ( bp_is_user() && ! bp_displayed_user_use_cover_image_header() ) {
		return;
	}

	if ( bp_is_group() && ! bp_group_use_cover_image_header() ) {
		return;
	}

	$cover_image = enlightenment_bp_get_cover_image();

	if ( empty( $cover_image ) ) {
		return;
	}

	$defaults = array(
		'container'       => 'div',
		'container_class' => 'bp-cover-image-container',
		'container_id'    => 'cover-image-container',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_cover_image_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	$output = apply_filters( 'enlightenment_bp_cover_image_maybe_open_container', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_cover_image_maybe_close_container( $args = null ) {
	if ( bp_is_user() && ! bp_displayed_user_use_cover_image_header() ) {
		return;
	}

	if ( bp_is_group() && ! bp_group_use_cover_image_header() ) {
		return;
	}

	$cover_image = enlightenment_bp_get_cover_image();

	if ( empty( $cover_image ) ) {
		return;
	}

	$defaults = array(
		'container'       => 'div',
		'container_class' => 'bp-cover-image-container',
		'container_id'    => 'cover-image-container',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_cover_image_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_cover_image_maybe_close_container', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_header_cover_image_maybe_open_container( $args = null ) {
	if ( bp_is_user() && ! bp_displayed_user_use_cover_image_header() ) {
		return;
	}

	if ( bp_is_group() && ! bp_group_use_cover_image_header() ) {
		return;
	}

	$cover_image = enlightenment_bp_get_cover_image();

	if ( empty( $cover_image ) ) {
		return;
	}

	$defaults = array(
		'container'       => 'div',
		'container_class' => 'bp-item-header-cover-image',
		'container_id'    => 'item-header-cover-image',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_header_cover_image_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	$output = apply_filters( 'enlightenment_bp_header_cover_image_maybe_open_container', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_header_cover_image_maybe_close_container( $args = null ) {
	if ( bp_is_user() && ! bp_displayed_user_use_cover_image_header() ) {
		return;
	}

	if ( bp_is_group() && ! bp_group_use_cover_image_header() ) {
		return;
	}

	$cover_image = enlightenment_bp_get_cover_image();

	if ( empty( $cover_image ) ) {
		return;
	}

	$defaults = array(
		'container'       => 'div',
		'container_class' => 'bp-item-header-cover-image',
		'container_id'    => 'item-header-cover-image',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_header_cover_image_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_header_cover_image_maybe_close_container', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_wrap_open_container( $args = null ) {
	$defaults = array(
		'container_class' => 'bp-wrap',
		'container_id'    => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_wrap_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( 'div', $args['container_class'], $args['container_id'] );

	$output = apply_filters( 'enlightenment_bp_wrap_open_container', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}
