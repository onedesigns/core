<?php

function enlightenment_bp_filter_theme_package_id( $value ) {
	return 'nouveau';
}
add_filter( 'pre_option__bp_theme_package_id', 'enlightenment_bp_filter_theme_package_id' );
add_filter( 'option__bp_theme_package_id', 'enlightenment_bp_filter_theme_package_id' );
add_filter( 'bp_get_theme_package_id', 'enlightenment_bp_filter_theme_package_id' );

function enlightenment_bp_cover_image_css( $css ) {
	$cover_image = enlightenment_bp_get_cover_image();

	if ( empty( $cover_image ) ) {
		return $css;
	}

	$cover_image = esc_url_raw( $cover_image );

	$css .= "
	#header-cover-image {
		background-image: url({$cover_image});
	}";

	return $css;
}
add_filter( 'enlightenment_theme_custom_css', 'enlightenment_bp_cover_image_css' );

function enlightenment_bp_filter_body_class( $classes ) {
	if ( enlightenment_bp_is_access_restricted() ) {
		$classes[] = 'buddypress-access-restricted';
	}

	return $classes;
}
add_filter( 'bp_get_the_body_class', 'enlightenment_bp_filter_body_class' );

function enlightenment_bp_filter_archive_title_args( $args ) {
	if(
		bp_is_activity_directory() ||
		bp_is_members_directory()  ||
		bp_is_groups_directory()   ||
		bp_is_group_create()       ||
		bp_is_blogs_directory()    ||
		bp_is_create_blog()        ||
		bp_is_register_page()      ||
		bp_is_activation_page()
	) {
		$args['container'] = 'h1';
	}

	return $args;
}
add_filter( 'enlightenment_archive_title_args', 'enlightenment_bp_filter_archive_title_args' );

function enlightenment_bp_filter_the_archive_title( $title ) {
	if ( enlightenment_bp_is_access_restricted() ) {
		$title = __( 'Members-only area', 'enlightenment' );
	} elseif ( bp_is_activity_directory() ) {
		$title = __( 'Activity', 'enlightenment' );
	} elseif ( bp_is_members_directory() ) {
		$title = __( 'Members', 'enlightenment' );
	} elseif ( bp_is_groups_directory() ) {
		$title = __( 'Groups', 'enlightenment' );
	} elseif ( bp_is_group_create() ) {
		$title = __( 'Create a Group', 'enlightenment' );
	} elseif ( bp_is_blogs_directory() ) {
		$title = __( 'Sites', 'enlightenment' );
	} elseif ( bp_is_create_blog() ) {
		$title = __( 'Create a Site', 'enlightenment' );
	} elseif ( bp_is_register_page() ) {
		if ( bp_get_membership_requests_required() ) {
			$title = __( 'Request Membership', 'enlightenment' );
		} else {
			$title = __( 'Create an Account', 'enlightenment' );
		}
	} elseif ( bp_is_activation_page() ) {
		$title = __( 'Activate Your Account', 'enlightenment' );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'enlightenment_bp_filter_the_archive_title' );

function enlightenment_bp_filter_get_group_create_nav_item( $output ) {
	if( bp_is_group_create() ) {
		$output = str_replace( '<li id="group-create-nav">', '<li class="selected" id="group-create-nav">', $output );
	}

	return $output;
}
add_filter( 'bp_get_group_create_nav_item', 'enlightenment_bp_filter_get_group_create_nav_item' );

function enlightenment_bp_filter_create_excerpt_args( $args ) {
	$args['ending'] = ' &hellip;';

	return $args;
}
// add_filter( 'bp_before_create_excerpt_parse_args', 'enlightenment_bp_filter_create_excerpt_args' );

function enlightenment_bp_filter_member_latest_update( $output ) {
	return str_replace( '<span class="activity-read-more">', ' <span class="activity-read-more">', $output );
}
add_filter( 'bp_get_member_latest_update', 'enlightenment_bp_filter_member_latest_update' );

function enlightenment_bp_filter_activity_recurse_comments_start_ul( $output ) {
	return '<ul class="activity-comments-list">';
}
add_filter( 'bp_activity_recurse_comments_start_ul', 'enlightenment_bp_filter_activity_recurse_comments_start_ul' );

function enlightenment_bp_activity_comment_form_input_placeholder( $output ) {
	$placeholder = apply_filters(
		'enlightenment_bp_activity_comment_form_input_placeholder',
		__( 'Write a comment&#8230;', 'enlightenment' )
	);

	$offset = strpos( $output, 'class="ac-input ' );
    if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, sprintf( ' placeholder="%s"', $placeholder ), $offset, 0 );
    }

	return $output;
}
add_filter( 'enlightenment_bp_filter_activity_entry_comments', 'enlightenment_bp_activity_comment_form_input_placeholder' );

function enlightenment_bp_filter_template_member_profile_action_output( $output ) {
	$output = apply_filters( sprintf( 'enlightenment_bp_filter_template_member_profile_%s_output', bp_current_action() ), $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_profile_output', 'enlightenment_bp_filter_template_member_profile_action_output' );

function enlightenment_bp_filter_template_member_settings_action_output( $output ) {
	$output = apply_filters( sprintf( 'enlightenment_bp_filter_template_member_settings_%s_output', bp_current_action() ), $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_settings_output', 'enlightenment_bp_filter_template_member_settings_action_output' );

function enlightenment_bp_filter_group_creation_screen( $output ) {
	$action    = bp_action_variable( 1 );
	$screen_id = sanitize_file_name( $action );

	if ( ! $screen_id ) {
		return $output;
	}

	if ( ! bp_is_group_creation_step( $screen_id ) ) {
		return $output;
	}

	$output = apply_filters( sprintf( 'enlightenment_bp_filter_group_creation_%s_screen', $screen_id ), $output );

	return $output;
}
add_filter( 'enlightenment_bp_group_creation_screen', 'enlightenment_bp_filter_group_creation_screen' );

function enlightenment_bp_filter_group_admin_screen( $output ) {
	$action    = bp_action_variable( 0 );
	$screen_id = sanitize_file_name( $action );

	if ( ! $screen_id ) {
		return $output;
	}

	if ( ! bp_is_group_admin_screen( $screen_id ) ) {
		return $output;
	}

	$output = apply_filters( sprintf( 'enlightenment_bp_filter_group_admin_%s_screen', $screen_id ), $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_group_admin_output', 'enlightenment_bp_filter_group_admin_screen' );

function enlightenment_bp_filter_activity_action( $action, &$activity, $r ) {
	$secondary_avatar = bp_get_activity_secondary_avatar();

	$offset = strpos( $action, $secondary_avatar );
	if ( false !== $offset ) {
		$offset = strpos( $secondary_avatar, '<a ' );
		if ( false !== $offset ) {
			$offset = strpos( $secondary_avatar, ' class="', $offset );
			$end    = strpos( $secondary_avatar, '>',        $offset );

			if ( false !== $offset && $offset < $end ) {
				switch ( $activity->component ) {
					case 'groups':
						$object = 'group';

						break;

					case 'blogs':
						$object = 'blog';

						break;

					case 'friends':
					default:
						$object = 'user';
				}

				$class = sprintf( 'secondary-avatar %s-avatar', $object );

				$offset_a = strpos( $secondary_avatar, ' class=""', $offset );
				if ( $offset_a !== $offset ) {
					$class .= ' ';
				}

				$updated_avatar = substr_replace( $secondary_avatar, esc_attr( $class ), $offset + 8, 0 );
				$action         = str_replace( $secondary_avatar, $updated_avatar, $action );
			}
		}
	}

	return $action;
}
add_filter( 'bp_get_activity_action', 'enlightenment_bp_filter_activity_action', 10, 3 );

function enlightenment_bp_filter_rtmedia_activity_content_body( $output, &$activity ) {
	if ( 'rtmedia_update' != $activity->type ) {
		return $output;
	}

	$length = 0;
	$offset = strpos( $output, 'class="rtmedia-list rtm-activity-media-list ' );
	if ( false !== $offset ) {
		$start  = $offset + 7;
		$end    = strpos( $output, '"', $start );
		$offset = strpos( $output, ' rtmedia-activity-media-length-', $start );

		if ( false !== $offset && $offset < $end ) {
			$start_a  = $offset + 31;
			$end_a    = strpos( $output, ' ', $start_a );

			if ( false === $offset || $end_a > $end ) {
				$end_a = $end;
			}

			$length = substr( $output, $start_a, $end_a - $start_a  );

			if ( absint( $length ) == $length ) {
				$length = absint( $length );
			}
		}
	}

	$count  = 0;
	$offset = strpos( $output, '<ul class="rtmedia-list rtm-activity-media-list ' );
	if ( false !== $offset ) {

		$end    = strpos( $output, '</ul>', $offset );
		$offset = strpos( $output, '<li class="rtmedia-list-item ', $offset );
		while ( false !== $offset && $offset < $end ) {
			$count++;

			$offset = strpos( $output, '<li class="rtmedia-list-item ', $offset + 1 );
		}

		if ( $count < $length ) {
			$output = str_replace( 'class="rtmedia-list rtm-activity-media-list ', sprintf( 'class="rtmedia-list rtm-activity-media-list rtmedia-activity-media-truncated rtmedia-activity-media-truncated-length-%s ', $count ), $output );
		}
	}

	$output = apply_filters( 'enlightenment_bp_filter_rtmedia_activity_content_body', $output, $length, $count );

	return $output;
}
add_filter( 'bp_get_activity_content_body', 'enlightenment_bp_filter_rtmedia_activity_content_body', 10, 2 );

function enlightenment_bp_filter_rtmedia_comment_activity_content_body( $output, &$activity ) {
	// Comment was added from the media view
	if ( 'rtmedia_comment_activity' == $activity->type ) {
		return apply_filters( 'enlightenment_bp_filter_rtmedia_comment_activity_content_body', $output );
	} elseif ( 'activity_comment' == $activity->type ) {
		// Comment was added from the activity view
		if ( false !== strpos( $output, '<ul class="rtmedia-list rtm-activity-media-list ' ) ) {
			return apply_filters( 'enlightenment_bp_filter_rtmedia_comment_activity_content_body', $output );
		}
	}

	// Comment does not contain media
	return $output;
}
add_filter( 'bp_get_activity_content_body', 'enlightenment_bp_filter_rtmedia_comment_activity_content_body', 10, 2 );

function enlightenment_bp_rtmedia_filter_the_content( $output ) {
	$offset = strpos( $output, 'id="rtmedia_gallery_container_' );
	if ( false !== $offset ) {
		return apply_filters( 'enlightenment_bp_filter_template_rtmedia_media/media-gallery', $output );
	}

	$offset = strpos( $output, 'id="rtmedia_gallery_container_' );
	if ( false !== $offset ) {
		return apply_filters( 'enlightenment_bp_filter_template_rtmedia_media/album-gallery', $output );
	}

	return $output;
}
add_filter( 'bp_replace_the_content', 'enlightenment_bp_rtmedia_filter_the_content' );

function enlightenment_bp_filter_rtmedia_credit_links_args( $args ) {
	if ( ! function_exists( 'rtmedia_link_in_footer' ) ) {
		return $args;
	}

	if ( ! has_action( 'wp_footer', 'rtmedia_link_in_footer' ) ) {
		return $args;
	}

	remove_action( 'wp_footer', 'rtmedia_link_in_footer' );

	ob_start();
	rtmedia_link_in_footer();
	$output = ob_get_clean();

	if ( empty( $output ) ) {
		return $args;
	}

	$output = str_replace( "<div class='rtmedia-footer-link'>", '<span class="rtmedia-footer-link">', $output );
	$output = str_replace( '</div>', '</span>', $output );

	if ( empty( $args['format'] ) ) {
		$args['format'] = $output;
	} else {
		$args['format'] = sprintf( '%s %s', $args['format'], $output );
	}

	return $args;
}
add_filter( 'enlightenment_credit_links_args', 'enlightenment_bp_filter_rtmedia_credit_links_args', 20 );
