<?php

function enlightenment_bbp_bootstrap_template_notices( $output ) {
	$start = strpos( $output, '<div class="bbp-template-notice error"' );
	if ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-danger"', $offset + 3, 0 );
			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}
	}

	$start = strpos( $output, '<div class="bbp-template-notice important"' );
	if ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );
			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}
	}

	$start = strpos( $output, '<div class="bbp-template-notice"' );
	if ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );
			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_bbp_template_notices', 'enlightenment_bbp_bootstrap_template_notices' );

function enlightenment_bbp_bootstrap_form_search( $output ) {
	$output = str_replace( 'class="screen-reader-text hidden"', 'class="screen-reader-text hidden visually-hidden"', $output );

	$offset = strpos( $output, '<input type="text"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="input-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$output = str_replace( 'id="bbp_search"', 'id="bbp_search" class="form-control"', $output );
	$output = str_replace( 'class="button"', 'class="button btn btn-light"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_form_search', 'enlightenment_bbp_bootstrap_form_search' );

function enlightenment_bbp_open_content_container() {
	echo enlightenment_open_tag( 'div', 'col-lg-9' );
}

function enlightenment_bbp_open_sidebar_container() {
	echo enlightenment_open_tag( 'div', 'col-lg-3 stickit' );
}

function enlightenment_bbp_bootstrap_forums_loop( $output ) {
	$start = strpos( $output, '<div class="bbp-template-notice"' );
	if ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );
			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_bbp_forums_loop', 'enlightenment_bbp_bootstrap_forums_loop' );

function enlightenment_bbp_bootstrap_single_forum_description( $output ) {
	$output = str_replace( '<ul>', '<ul class="list-unstyled mb-0">', $output );
	$output = str_replace( 'class="bbp-forum-description"', 'class="bbp-forum-description alert alert-info"', $output );

	return $output;
}
add_filter( 'bbp_get_single_forum_description', 'enlightenment_bbp_bootstrap_single_forum_description' );

function enlightenment_bbp_bootstrap_single_topic_description( $output ) {
	$output = str_replace( '<ul>', '<ul class="list-unstyled mb-0">', $output );
	$output = str_replace( 'class="bbp-topic-description"', 'class="bbp-topic-description alert alert-info"', $output );

	return $output;
}
add_filter( 'bbp_get_single_topic_description', 'enlightenment_bbp_bootstrap_single_topic_description' );

function enlightenment_bbp_bootstrap_topic_user_actions( $output, $args ) {
	if( ! is_user_logged_in() ) {
		return $output;
	}

	if( ! bbp_is_single_forum() && ! bbp_is_single_topic() ) {
		return $output;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );

	$output .= bbp_get_topic_subscription_link();

	$output .= sprintf( '<button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="visually-hidden">%s</span></button>', __( 'Toggle Dropdown', 'enlightenment' ) );
	$output .= enlightenment_open_tag( 'div', 'dropdown-menu' );
	$output .= bbp_get_user_favorites_link();
	$output .= enlightenment_close_tag( 'div' );

	$output .= enlightenment_close_tag( $args['container'] );

	return $output;
}
// add_filter( 'enlightenment_bbp_topic_user_actions', 'enlightenment_bbp_bootstrap_topic_user_actions', 10, 2 );

function enlightenment_bbp_bootstrap_user_subscribe_link( $output ) {
	if( ! bbp_is_single_forum() && ! bbp_is_single_topic() ) {
		return $output;
	}

	$output = str_replace( 'class="subscription-toggle"', 'class="subscription-toggle btn btn-secondary"', $output );

	if( doing_action( 'bp_template_content' ) && ! bbp_is_single_forum() ) {
		$output = enlightenment_open_tag( 'nav' ,'group-topic-actions btn-group' ) . $output;
	}

	return $output;
}
add_filter( 'bbp_get_user_subscribe_link', 'enlightenment_bbp_bootstrap_user_subscribe_link' );

function enlightenment_bbp_bootstrap_user_favorites_link( $output ) {
	if( ! bbp_is_single_forum() && ! bbp_is_single_topic() ) {
		return $output;
	}

	// $output = str_replace( 'id="favorite-toggle"', 'id="favorite-toggle" class="dropdown-item"', $output );
	$output = str_replace( 'class="favorite-toggle"', 'class="favorite-toggle btn btn-secondary"', $output );

	if( doing_action( 'bp_template_content' ) ) {
		$output .= enlightenment_close_tag( 'nav' );
	}

	return $output;
}
add_filter( 'bbp_get_user_favorites_link', 'enlightenment_bbp_bootstrap_user_favorites_link' );

function enlightenment_bbp_filter_breadcrumb_args( $args ) {
	$args['before']         = '<ol class="bbp-breadcrumb breadcrumb">';
	$args['after']          = '</ol>';
	$args['sep']            = '';
	// $args['crumb_before']   = '<li class="breadcrumb-item">';
	// $args['crumb_after']    = '</li>';
	// $args['current_before'] = '<li class="breadcrumb-item active" aria-current="page">';
	// $args['current_after']  = '</li>';

	return $args;
}
add_filter( 'bbp_before_get_breadcrumb_parse_args', 'enlightenment_bbp_filter_breadcrumb_args' );

function enlightenment_bbp_filter_breadcrumbs( $output, $crumbs, $args ) {
	foreach ( $crumbs as $key => $crumb ) {
		if ( ! empty( $args['include_current'] ) && $key == ( count( $crumbs ) - 1 ) ) {
			$output = str_replace( $crumb, sprintf( '<li class="breadcrumb-item active" aria-current="page">%s</li>', $crumb ), $output );
		} else {
			$output = str_replace( $crumb, sprintf( '<li class="breadcrumb-item">%s</li>', $crumb ), $output );
		}
	}

	return $output;
}
add_filter( 'bbp_get_breadcrumb', 'enlightenment_bbp_filter_breadcrumbs', 10, 3 );

function enlightenment_bbp_bootstrap_topics_loop( $output ) {
	$start = strpos( $output, '<div class="bbp-template-notice"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice"', $start + 1 );
	}

	$output = str_replace( 'class="bbp-pagination"', 'class="bbp-pagination d-sm-flex align-items-sm-center"', $output );
	$output = str_replace( 'class="bbp-pagination-links"', 'class="bbp-pagination-links mt-3 mt-sm-0 ms-sm-auto"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_topics_loop', 'enlightenment_bbp_bootstrap_topics_loop' );
add_filter( 'enlightenment_bp_filter_template_group_plugins_output', 'enlightenment_bbp_bootstrap_topics_loop' );

function enlightenment_bbp_bootstrap_forum_form( $output ) {
	$start = strpos( $output, '<div class="bbp-template-notice"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice"', $start + 1 );
	}

	$start = strpos( $output, '<div class="bbp-template-notice info"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice info"', $start + 1 );
	}

	$start = strpos( $output, '<div class="bbp-template-notice error"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-danger"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice error"', $start + 1 );
	}

	$output = str_replace( 'class="form-allowed-tags"', 'class="form-allowed-tags alert alert-info"', $output );
	$output = str_replace( 'class="bbp-the-content-wrapper"', 'class="bbp-the-content-wrapper mb-3"', $output );
	$output = str_replace( '</label><br />', '</label>', $output );
	$output = str_replace( '<input type="text"', '<input type="text" class="form-control"', $output );
	$output = str_replace( 'class="form-control" id="bbp_forum_title"', 'class="form-control form-control-lg" id="bbp_forum_title"', $output );
	$output = str_replace( 'class="bbp-the-content"', 'class="bbp-the-content form-control"', $output );
	$output = str_replace( 'class="bbp_dropdown"', 'class="bbp_dropdown form-select"', $output );
	$output = str_replace( 'class="button submit"', 'class="button submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_forum_form', 'enlightenment_bbp_bootstrap_forum_form' );

add_action( 'bbp_theme_before_forum_form_type', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_forum_form_type() {
	$output = ob_get_clean();
	$output = sprintf( '<div class="row gx-2">%s%s', "\n", $output );
	$output = str_replace( '<p>', '<p class="mb-3 col-lg">', $output );

	echo $output;
}
add_action( 'bbp_theme_after_forum_form_type', 'enlightenment_bbp_bootstrap_forum_form_type' );

add_action( 'bbp_theme_before_forum_form_status', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_forum_form_status() {
	$output = ob_get_clean();
	$output = str_replace( '<p>', '<p class="col-lg">', $output );

	echo $output;
}
add_action( 'bbp_theme_after_forum_form_status', 'enlightenment_bbp_bootstrap_forum_form_status' );

add_action( 'bbp_theme_before_forum_visibility_status', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_forum_visibility_status() {
	$output = ob_get_clean();
	$output = str_replace( '<p>', '<p class="col-lg">', $output );

	echo $output;
}
add_action( 'bbp_theme_after_forum_visibility_status', 'enlightenment_bbp_bootstrap_forum_visibility_status' );

add_action( 'bbp_theme_before_forum_form_parent', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_forum_form_parent() {
	$output = ob_get_clean();
	$output = str_replace( '<p>', '<p class="col-lg">', $output );
	$output = sprintf( '%s%s</div>', $output, "\n" );

	echo $output;
}
add_action( 'bbp_theme_after_forum_form_parent', 'enlightenment_bbp_bootstrap_forum_form_parent' );

function enlightenment_bbp_bootstrap_topic_tag_edit( $output ) {
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );

	$start = strpos( $output, '<div class="bbp-template-notice"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice"', $start + 1 );
	}

	$start = strpos( $output, '<div class="bbp-template-notice info"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice info"', $start + 1 );
	}

	$start = strpos( $output, '<div class="bbp-template-notice error"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-danger"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice error"', $start + 1 );
	}

	$output = str_replace( '<div>', '<div class="mb-3">', $output );
	$output = str_replace( '<input type="text"', '<input type="text" class="form-control"', $output );
	$output = str_replace( '<textarea', '<textarea class="form-control"', $output );
	$output = str_replace( 'class="button submit"', 'class="button submit btn btn-primary btn-lg"', $output );

	$start = strpos( $output, '<form id="delete_tag"' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</form>', $start );
		$offset = strpos( $output, 'class="button submit btn btn-primary btn-lg"', $start );

		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, 'danger' , $offset + 29, 7 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_bbp_topic_tag_edit', 'enlightenment_bbp_bootstrap_topic_tag_edit' );

function enlightenment_bbp_forum_pagination_links( $output ) {
	if ( empty( $output ) ) {
		return $output;
	}

	$output = str_replace( '<a ', '<li class="page-item"><a ', $output );
	$output = str_replace( '</a>', '</a></li>', $output );
	$output = str_replace( '<span aria-current="page" ', '<li class="page-item active"><span aria-current="page" ', $output );
	$output = str_replace( '<span class="page-numbers dots">', '<li class="page-item"><span class="page-numbers dots">', $output );
	$output = str_replace( '</span>', '</span></li>', $output );
	$output = str_replace( 'class="page-numbers current"', 'class="page-numbers current page-link"', $output );
	$output = str_replace( 'class="page-numbers"', 'class="page-numbers page-link"', $output );
	$output = str_replace( 'class="page-numbers dots"', 'class="page-numbers dots page-link"', $output );
	$output = str_replace( 'class="prev page-numbers"', 'class="prev page-numbers page-link"', $output );
	$output = str_replace( 'class="next page-numbers"', 'class="next page-numbers page-link"', $output );
	$output = sprintf( '<ul class="pagination">%s</ul>', $output );

	return $output;
}
add_filter( 'bbp_get_forum_pagination_links', 'enlightenment_bbp_forum_pagination_links' );

function enlightenment_bbp_bootstrap_topic_pagination( $output ) {
	if ( ! is_string( $output ) ) {
		return $output;
	}

	$offset = strpos( $output, '<span class="page-numbers dots">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<li class="page-item">' , $offset, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, '</li>', $offset + 7, 0 );
	}

	$output = str_replace( '<span class="bbp-topic-pagination">', '<span class="bbp-topic-pagination"><ul class="pagination pagination-sm">', $output );
	$output = str_replace( '<a ', '<li class="page-item"><a ', $output );
	$output = str_replace( '</a>', '</a></li>', $output );
	$output = str_replace( 'class="page-numbers"', 'class="page-numbers page-link"', $output );
	$output = str_replace( 'class="page-numbers dots"', 'class="page-numbers dots page-link"', $output );
	$output = str_replace( '</span>', '</ul></span>', $output );

	return $output;
}
add_filter( 'bbp_get_topic_pagination', 'enlightenment_bbp_bootstrap_topic_pagination' );

function enlightenment_bbp_bootstrap_topic_form_template_notice( $output ) {
	$start = strpos( $output, '<div class="bbp-template-notice"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice"', $start + 1 );
	}

	return $output;
}
add_filter( 'enlightenment_bbp_topic_form', 'enlightenment_bbp_bootstrap_topic_form_template_notice' );

function enlightenment_bbp_bootstrap_topic_form( $output ) {
	if ( bbp_current_user_can_access_create_topic_form() ) {
		$output = str_replace( '</label><br />', '</label>', $output );
		$output = str_replace( 'for="bbp_anonymous_author"', 'for="bbp_anonymous_author" class="form-label"', $output );
		$output = str_replace( 'id="bbp_anonymous_author"', 'id="bbp_anonymous_author" class="form-control"', $output );
		$output = str_replace( 'for="bbp_anonymous_email"', 'for="bbp_anonymous_email" class="form-label"', $output );
		$output = str_replace( 'id="bbp_anonymous_email"', 'id="bbp_anonymous_email" class="form-control"', $output );
		$output = str_replace( 'for="bbp_anonymous_website"', 'for="bbp_anonymous_website" class="form-label"', $output );
		$output = str_replace( 'id="bbp_anonymous_website"', 'id="bbp_anonymous_website" class="form-control"', $output );
		$output = str_replace( 'for="bbp_topic_title"', 'for="bbp_topic_title" class="form-label"', $output );
		$output = str_replace( 'id="bbp_topic_title"', 'id="bbp_topic_title" class="form-control form-control-lg"', $output );
		$output = str_replace( 'class="bbp-the-content-wrapper"', 'class="bbp-the-content-wrapper mb-3"', $output );
		$output = str_replace( 'class="bbp-the-content"', 'class="bbp-the-content form-control"', $output );
		$output = str_replace( 'class="form-allowed-tags"', 'class="form-allowed-tags alert alert-info"', $output );
		$output = str_replace( 'for="bbp_topic_tags"', 'for="bbp_topic_tags" class="form-label"', $output );
		$output = str_replace( 'id="bbp_topic_tags"', 'id="bbp_topic_tags" class="form-control"', $output );
		$output = str_replace( 'for="bbp_forum_id"', 'for="bbp_forum_id" class="form-label"', $output );
		$output = str_replace( 'for="bbp_stick_topic"', 'for="bbp_stick_topic" class="form-label"', $output );
		$output = str_replace( 'class="bbp_dropdown"', 'class="bbp_dropdown form-select"', $output );
		$output = str_replace( 'id="bbp_stick_topic_select"', 'id="bbp_stick_topic_select" class="form-select"', $output );
		$output = str_replace( 'for="bbp_topic_status"', 'for="bbp_topic_status" class="form-label"', $output );
		$output = str_replace( 'id="bbp_topic_status_select"', 'id="bbp_topic_status_select" class="form-select"', $output );
		$output = str_replace( 'class="button submit"', 'class="button submit btn btn-primary btn-lg"', $output );
	} elseif( ! bbp_is_forum_closed() ) {
		$output = str_replace( 'class="bbp-username"', 'class="bbp-username mb-3"', $output );
		$output = str_replace( 'for="user_login"', 'for="user_login" class="form-label"', $output );
		$output = str_replace( 'id="user_login"', 'id="user_login" class="form-control"', $output );
		$output = str_replace( 'class="bbp-password"', 'class="bbp-password mb-3"', $output );
		$output = str_replace( 'for="user_pass"', 'for="user_pass" class="form-label"', $output );
		$output = str_replace( 'id="user_pass"', 'id="user_pass" class="form-control"', $output );
		$output = str_replace( 'class="bbp-remember-me"', 'class="bbp-remember-me mb-3 form-check"', $output );
		$output = str_replace( 'id="rememberme"', 'id="bbp-topic-form-rememberme" class="form-check-input"', $output );
		$output = str_replace( 'for="rememberme"', 'for="bbp-topic-form-rememberme" class="form-check-label"', $output );
		$output = str_replace( 'class="bbp-submit-wrapper"', 'class="bbp-submit-wrapper mb-3"', $output );
		$output = str_replace( 'class="button submit user-submit"', 'class="button submit user-submit btn btn-primary btn-lg"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_bbp_topic_form', 'enlightenment_bbp_bootstrap_topic_form' );
add_filter( 'enlightenment_bp_filter_template_group_plugins_output', 'enlightenment_bbp_bootstrap_topic_form' );

add_action( 'bbp_theme_before_topic_form_tags', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_topic_form_tags() {
	$output = ob_get_clean();
	$output = sprintf( '<div class="row gx-2">%s%s', "\n", $output );
	$output = str_replace( '<p>', '<p class="col-lg">', $output );

	if ( bbp_is_single_forum() && ! current_user_can( 'moderate', bbp_get_topic_id() ) ) {
		$output .= '</div>';
	}

	echo $output;
}
add_action( 'bbp_theme_after_topic_form_tags', 'enlightenment_bbp_bootstrap_topic_form_tags' );

add_action( 'bbp_theme_before_topic_form_forum', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_topic_form_forum() {
	$output = ob_get_clean();

	if ( ! did_action( 'bbp_theme_after_topic_form_tags' ) ) {
		$output = sprintf( '<div class="row gx-2">%s%s', "\n", $output );
	}

	$output = str_replace( '<p>', '<p class="col-lg">', $output );

	if ( ! current_user_can( 'moderate', bbp_get_topic_id() ) ) {
		$output .= '</div>';
	}

	echo $output;
}
add_action( 'bbp_theme_after_topic_form_forum', 'enlightenment_bbp_bootstrap_topic_form_forum' );

add_action( 'bbp_theme_before_topic_form_type', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_topic_form_type_status() {
	$output = ob_get_clean();

	if ( ! did_action( 'bbp_theme_after_topic_form_tags' ) && ! did_action( 'bbp_theme_after_topic_form_forum' ) ) {
		$output = sprintf( '<div class="row gx-2">%s%s', "\n", $output );
	}

	$output  = str_replace( '<p>', '<p class="col-lg">', $output );
	$output .= '</div>';

	echo $output;
}
add_action( 'bbp_theme_after_topic_form_status', 'enlightenment_bbp_bootstrap_topic_form_type_status' );

add_action( 'bbp_theme_before_topic_form_subscriptions', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_topic_form_subscriptions() {
	$output  = ob_get_clean();
	$output  = str_replace( '<p>', '<p class="form-check mb-3">', $output );
	$output  = str_replace( 'id="bbp_topic_subscription"', 'id="bbp_topic_subscription" class="form-check-input"', $output );
	$output  = str_replace( 'for="bbp_topic_subscription"', 'for="bbp_topic_subscription" class="form-check-label"', $output );

	echo $output;
}
add_action( 'bbp_theme_after_topic_form_subscriptions', 'enlightenment_bbp_bootstrap_topic_form_subscriptions' );

add_action( 'bbp_theme_before_topic_form_revisions', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_topic_form_revisions() {
	$output  = ob_get_clean();
	$output  = str_replace( '<legend>', '<div class="form-check">', $output );
	$output  = str_replace( '</legend>', '</div>', $output );
	$output  = str_replace( 'id="bbp_log_topic_edit"', 'id="bbp_log_topic_edit" class="form-check-input"', $output );
	$output  = str_replace( 'for="bbp_log_topic_edit"', 'for="bbp_log_topic_edit" class="form-check-label"', $output );
	$output  = str_replace( '<div>', '<div class="mb-3">', $output );
	$output  = str_replace( 'id="bbp_topic_edit_reason"', 'id="bbp_topic_edit_reason" class="form-control"', $output );

	echo $output;
}
add_action( 'bbp_theme_after_topic_form_revisions', 'enlightenment_bbp_bootstrap_topic_form_revisions' );

function enlightenment_bbp_bootstrap_topic_merge_form( $output ) {
	$start = strpos( $output, '<div class="bbp-template-notice"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice"', $start + 1 );
	}

	$start = strpos( $output, '<div class="bbp-template-notice info"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice info"', $start + 1 );
	}

	$start = strpos( $output, '<div class="bbp-template-notice error"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-danger"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice error"', $start + 1 );
	}

	$offset = strpos( $output, 'class="bbp-form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="bbp-form"', $offset + 1 );
		$offset = strpos( $output, '<div>', $offset );
		$output = substr_replace( $output, ' class="mb-3"', $offset + 4, 0 );
	}

	$output = str_replace( 'for="bbp_destination_topic"', 'for="bbp_destination_topic" class="form-label"', $output );
	$output = str_replace( 'class="bbp_dropdown"', 'class="bbp_dropdown form-select"', $output );
	$output = str_replace( '<input name="bbp_topic_subscribers"', '<div class="mb-3 form-check"><input name="bbp_topic_subscribers" class="form-check-input"', $output );
	$output = str_replace( '<label for="bbp_topic_subscribers">', '<label for="bbp_topic_subscribers" class="form-check-label">', $output );
	$output = str_replace( '<input name="bbp_topic_favoriters"', '<div class="mb-3 form-check"><input name="bbp_topic_favoriters" class="form-check-input"', $output );
	$output = str_replace( '<label for="bbp_topic_favoriters">', '<label for="bbp_topic_favoriters" class="form-check-label">', $output );
	$output = str_replace( '<input name="bbp_topic_tags"', '<div class="mb-3 form-check"><input name="bbp_topic_tags" class="form-check-input"', $output );
	$output = str_replace( '<label for="bbp_topic_tags">', '<label for="bbp_topic_tags" class="form-check-label">', $output );
	$output = str_replace( '</label><br />', '</label></div>', $output );
	$output = str_replace( 'class="button submit"', 'class="button submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_topic_merge_form', 'enlightenment_bbp_bootstrap_topic_merge_form' );

function enlightenment_bbp_bootstrap_topic_split_form( $output ) {
	$start = strpos( $output, '<div class="bbp-template-notice info"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice info"', $start + 1 );
	}

	$start = strpos( $output, '<div class="bbp-template-notice"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice"', $start + 1 );
	}

	$start = strpos( $output, '<div class="bbp-template-notice error"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-danger"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice error"', $start + 1 );
	}

	$offset = strpos( $output, 'class="bbp-form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="bbp-form"', $offset + 1 );
		$offset = strpos( $output, '<div>', $offset );
		$output = substr_replace( $output, ' class="mb-3"', $offset + 4, 0 );
		$offset = strpos( $output, '<input name="bbp_topic_split_option"', $offset );
		$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
		$output = substr_replace( $output, '<div class="form-check mb-2">' . "\n", $offset, 0 );
		$offset = strpos( $output, '<label for="bbp_topic_split_option_reply">', $offset );
		$output = substr_replace( $output, ' class="form-check-label"', $offset + 6, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );

		if ( false !== strpos( $output, 'id="bbp_topic_split_option_existing"' ) ) {
			$offset = strpos( $output, '<div>', $offset );
			$output = substr_replace( $output, ' class="mb-3"', $offset + 4, 0 );
			$offset = strpos( $output, '<input name="bbp_topic_split_option"', $offset );
			$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
			$output = substr_replace( $output, '<div class="form-check mb-2">' . "\n", $offset, 0 );
			$offset = strpos( $output, '<label for="bbp_topic_split_option_existing">', $offset );
			$output = substr_replace( $output, ' class="form-check-label"', $offset + 6, 0 );
			$offset = strpos( $output, '</label>', $offset );
			$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
		}
	}

	$output = str_replace( 'id="bbp_topic_split_destination_title"', 'id="bbp_topic_split_destination_title" class="form-control"', $output );
	$output = str_replace( 'class="bbp_dropdown"', 'class="bbp_dropdown form-select"', $output );
	$output = str_replace( '<input name="bbp_topic_subscribers"', '<div class="mb-3 form-check"><input name="bbp_topic_subscribers" class="form-check-input"', $output );
	$output = str_replace( '<label for="bbp_topic_subscribers">', '<label for="bbp_topic_subscribers" class="form-check-label">', $output );
	$output = str_replace( '<input name="bbp_topic_favoriters"', '<div class="mb-3 form-check"><input name="bbp_topic_favoriters" class="form-check-input"', $output );
	$output = str_replace( '<label for="bbp_topic_favoriters">', '<label for="bbp_topic_favoriters" class="form-check-label">', $output );
	$output = str_replace( '<input name="bbp_topic_tags"', '<div class="mb-3 form-check"><input name="bbp_topic_tags" class="form-check-input"', $output );
	$output = str_replace( '<label for="bbp_topic_tags">', '<label for="bbp_topic_tags" class="form-check-label">', $output );
	$output = str_replace( '</label><br />', '</label></div>', $output );
	$output = str_replace( 'class="button submit"', 'class="button submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_topic_split_form', 'enlightenment_bbp_bootstrap_topic_split_form' );

function enlightenment_bbp_bootstrap_reply_form( $output ) {
	$start = strpos( $output, '<div class="bbp-template-notice"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice"', $start + 1 );
	}

	if ( bbp_current_user_can_access_create_reply_form() ) {
		$output = str_replace( '</label><br />', '</label>', $output );
		$output = str_replace( 'id="bbp_anonymous_author"', 'id="bbp_anonymous_author" class="form-control"', $output );
		$output = str_replace( 'id="bbp_anonymous_email"', 'id="bbp_anonymous_email" class="form-control"', $output );
		$output = str_replace( 'id="bbp_anonymous_website"', 'id="bbp_anonymous_website" class="form-control"', $output );
		$output = str_replace( 'id="bbp_topic_title"', 'id="bbp_topic_title" class="form-control form-control-lg"', $output );
		$output = str_replace( 'class="bbp-the-content-wrapper"', 'class="bbp-the-content-wrapper mb-3"', $output );
		$output = str_replace( 'class="bbp-the-content"', 'class="bbp-the-content form-control"', $output );
		$output = str_replace( 'class="form-allowed-tags"', 'class="form-allowed-tags alert alert-info"', $output );
		$output = str_replace( 'for="bbp_topic_tags"', 'for="bbp_topic_tags" class="form-label"', $output );
		$output = str_replace( 'id="bbp_topic_tags"', 'id="bbp_topic_tags" class="form-control"', $output );
		$output = str_replace( 'id="bbp_stick_topic_select"', 'id="bbp_stick_topic_select" class="form-control"', $output );
		$output = str_replace( 'id="bbp_topic_status_select"', 'id="bbp_topic_status_select" class="form-control"', $output );
		$output = str_replace( 'class="bbp-submit-wrapper"', 'class="bbp-submit-wrapper d-flex"', $output );
		$output = str_replace( 'class="button submit"', 'class="button submit btn btn-primary btn-lg"', $output );
	} elseif( ! bbp_is_topic_closed() && ! bbp_is_forum_closed( bbp_get_topic_forum_id() ) ) {
		$output = str_replace( 'class="bbp-username"', 'class="bbp-username mb-3"', $output );
		$output = str_replace( 'for="user_login"', 'for="user_login" class="form-label"', $output );
		$output = str_replace( 'id="user_login"', 'id="user_login" class="form-control"', $output );
		$output = str_replace( 'class="bbp-password"', 'class="bbp-password mb-3"', $output );
		$output = str_replace( 'for="user_pass"', 'for="user_pass" class="form-label"', $output );
		$output = str_replace( 'id="user_pass"', 'id="user_pass" class="form-control"', $output );
		$output = str_replace( 'class="bbp-remember-me"', 'class="bbp-remember-me mb-3 form-check"', $output );
		$output = str_replace( 'id="rememberme"', 'id="bbp-reply-form-rememberme" class="form-check-input"', $output );
		$output = str_replace( 'for="rememberme"', 'for="bbp-reply-form-rememberme" class="form-check-label"', $output );
		$output = str_replace( 'class="bbp-submit-wrapper"', 'class="bbp-submit-wrapper mb-3"', $output );
		$output = str_replace( 'class="button submit user-submit"', 'class="button submit user-submit btn btn-primary btn-lg ms-2"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_bbp_reply_form', 'enlightenment_bbp_bootstrap_reply_form' );

function enlightenment_bbp_bootstrap_cancel_reply_to_link( $output ) {
	return str_replace( 'id="bbp-cancel-reply-to-link"', 'id="bbp-cancel-reply-to-link" class="btn btn-secondary btn-lg"', $output );
}
add_filter( 'bbp_get_cancel_reply_to_link', 'enlightenment_bbp_bootstrap_cancel_reply_to_link' );

add_action( 'bbp_theme_before_reply_form_subscription', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_reply_form_subscriptions() {
	$output  = ob_get_clean();
	$output  = str_replace( '<p>', '<p class="form-check mb-3">', $output );
	$output  = str_replace( 'id="bbp_topic_subscription"', 'id="bbp_topic_subscription" class="form-check-input"', $output );
	$output  = str_replace( 'for="bbp_topic_subscription"', 'for="bbp_topic_subscription" class="form-check-label"', $output );

	echo $output;
}
add_action( 'bbp_theme_after_reply_form_subscription', 'enlightenment_bbp_bootstrap_reply_form_subscriptions' );

add_action( 'bbp_theme_before_reply_form_reply_to', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_reply_form_moderate() {
	$output = ob_get_clean();
	$output = str_replace( 'class="form-reply-to"', 'class="form-reply-to mb-3 col-lg-8"', $output );
	$output = str_replace( 'for="bbp_reply_to"', 'for="bbp_reply_to" class="form-label"', $output );
	$output = str_replace( '<p>', '<p class="mb-3 col-lg-4">', $output );
	$output = str_replace( 'for="bbp_reply_status"', 'for="bbp_reply_status" class="form-label"', $output );
	$output = sprintf( '<div class="row gx-2">%s</div>', $output );

	echo $output;
}
add_action( 'bbp_theme_after_reply_form_status', 'enlightenment_bbp_bootstrap_reply_form_moderate' );

function enlightenment_bbp_bootstrap_reply_to_dropdown( $output ) {
	return str_replace( 'class="bbp_dropdown"', 'class="bbp_dropdown form-select"', $output );
}
add_filter( 'bbp_get_reply_to_dropdown', 'enlightenment_bbp_bootstrap_reply_to_dropdown' );

function enlightenment_bbp_bootstrap_form_reply_status_dropdown( $output ) {
	return str_replace( 'class="bbp_dropdown"', 'class="bbp_dropdown form-select"', $output );
}
add_filter( 'bbp_get_form_reply_status_dropdown', 'enlightenment_bbp_bootstrap_form_reply_status_dropdown' );

add_action( 'bbp_theme_before_reply_form_revisions', 'enlightenment_ob_start' );

function enlightenment_bbp_bootstrap_reply_form_revisions() {
	$output  = ob_get_clean();
	$output  = str_replace( '<legend>', '<div class="mb-3 form-check">', $output );
	$output  = str_replace( '</legend>', '</div>', $output );
	$output  = str_replace( 'id="bbp_log_reply_edit"', 'id="bbp_log_reply_edit" class="form-check-input"', $output );
	$output  = str_replace( 'for="bbp_log_reply_edit"', 'for="bbp_log_reply_edit" class="form-check-label"', $output );
	$output  = str_replace( '<div>', '<div class="mb-3">', $output );
	$output  = str_replace( 'for="bbp_reply_edit_reason"', 'for="bbp_reply_edit_reason" class="form-label"', $output );
	$output  = str_replace( 'id="bbp_reply_edit_reason"', 'id="bbp_reply_edit_reason" class="form-control"', $output );

	echo $output;
}
add_action( 'bbp_theme_after_reply_form_revisions', 'enlightenment_bbp_bootstrap_reply_form_revisions' );

function enlightenment_bbp_bootstrap_reply_move_form( $output ) {
	if ( is_user_logged_in() && current_user_can( 'edit_topic', bbp_get_topic_id() ) ) {
		$start = strpos( $output, '<div class="bbp-template-notice"' );
		while ( false !== $start ) {
			$offset = strpos( $output, '<ul>', $start );
			$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $start );

			while ( false !== $offset && $offset < $end ) {
				$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

				$end    = strpos( $output, '</div>', $start );
				$offset = strpos( $output, '<li>', $offset + 1 );
			}

			$start = strpos( $output, '<div class="bbp-template-notice"', $start + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice info"' );
		while ( false !== $start ) {
			$offset = strpos( $output, '<ul>', $start );
			$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $start );

			while ( false !== $offset && $offset < $end ) {
				$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

				$end    = strpos( $output, '</div>', $start );
				$offset = strpos( $output, '<li>', $offset + 1 );
			}

			$start = strpos( $output, '<div class="bbp-template-notice info"', $start + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice error"' );
		while ( false !== $start ) {
			$offset = strpos( $output, '<ul>', $start );
			$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $start );

			while ( false !== $offset && $offset < $end ) {
				$output = substr_replace( $output, ' class="alert alert-danger"', $offset + 3, 0 );

				$end    = strpos( $output, '</div>', $start );
				$offset = strpos( $output, '<li>', $offset + 1 );
			}

			$start = strpos( $output, '<div class="bbp-template-notice error"', $start + 1 );
		}

		$offset = strpos( $output, 'class="bbp-form"' );
		if ( false !== $offset ) {
			$offset = strpos( $output, 'class="bbp-form"', $offset + 1 );
			$offset = strpos( $output, '<div>', $offset );
			$output = substr_replace( $output, ' class="mb-3"', $offset + 4, 0 );
			$offset = strpos( $output, '<input name="bbp_reply_move_option"', $offset );
			$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
			$output = substr_replace( $output, '<div class="form-check mb-2">' . "\n", $offset, 0 );
			$offset = strpos( $output, '<label for="bbp_reply_move_option_reply">', $offset );
			$output = substr_replace( $output, ' class="form-check-label"', $offset + 6, 0 );
			$offset = strpos( $output, '</label>', $offset );
			$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );

			if ( false !== strpos( $output, 'id="bbp_reply_move_option_existing"' ) ) {
				$offset = strpos( $output, 'class="bbp-form"', $offset + 1 );
				$offset = strpos( $output, '<div>', $offset );
				$output = substr_replace( $output, ' class="mb-3"', $offset + 4, 0 );
				$offset = strpos( $output, '<input name="bbp_reply_move_option"', $offset );
				$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
				$output = substr_replace( $output, '<div class="form-check mb-2">' . "\n", $offset, 0 );
				$offset = strpos( $output, '<label for="bbp_reply_move_option_existing">', $offset );
				$output = substr_replace( $output, ' class="form-check-label"', $offset + 6, 0 );
				$offset = strpos( $output, '</label>', $offset );
				$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
			}
		}

		$output = str_replace( 'class="bbp_dropdown"', 'class="bbp_dropdown form-select"', $output );
		$output = str_replace( 'id="bbp_reply_move_destination_title"', 'id="bbp_reply_move_destination_title" class="form-control"', $output );
		$output = str_replace( 'class="button submit"', 'class="button submit btn btn-primary btn-lg"', $output );
	} else {
		$output  = str_replace( 'class="bbp-no-reply"', 'class="bbp-no-reply alert alert-danger"', $output );

		$offset = strpos( $output, '<div class="entry-content">' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, '', $offset, 27 );
			$offset = strpos( $output, '</div>', $offset );
			$output = substr_replace( $output, '', $offset, 6 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_bbp_reply_move_form', 'enlightenment_bbp_bootstrap_reply_move_form' );

function enlightenment_bbp_bootstrap_replies_loop( $output ) {
	$output = str_replace( 'class="bbp-pagination"', 'class="bbp-pagination d-sm-flex align-items-sm-center"', $output );
	$output = str_replace( 'class="bbp-pagination-links"', 'class="bbp-pagination-links mt-3 mt-sm-0 ms-sm-auto"', $output );
	$output = str_replace( 'class="bbp-reply-revision-log"', 'class="bbp-reply-revision-log list-unstyled mb-0"', $output );
	$output = str_replace( 'class="bbp-reply-revision-log-item"', 'class="bbp-reply-revision-log-item alert alert-info"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_replies_loop', 'enlightenment_bbp_bootstrap_replies_loop' );
add_filter( 'enlightenment_bbp_single_user_body', 'enlightenment_bbp_bootstrap_replies_loop' );
add_filter( 'enlightenment_bp_filter_template_member_plugins_output', 'enlightenment_bbp_bootstrap_replies_loop' );

function enlightenment_bbp_bootstrap_single_reply( $output ) {
	$output = str_replace( 'class="bbp-reply-revision-log"', 'class="bbp-reply-revision-log list-unstyled mb-0"', $output );
	$output = str_replace( 'class="bbp-reply-revision-log-item"', 'class="bbp-reply-revision-log-item alert alert-info"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_single_reply', 'enlightenment_bbp_bootstrap_single_reply' );

function enlightenment_bbp_bootstrap_topic_pagination_links( $output ) {
	if ( empty( $output ) ) {
		return $output;
	}

	$output = str_replace( '<a ', '<li class="page-item"><a ', $output );
	$output = str_replace( '</a>', '</a></li>', $output );
	$output = str_replace( '<span aria-current="page" ', '<li class="page-item active"><span aria-current="page" ', $output );
	$output = str_replace( '<span class="page-numbers dots">', '<li class="page-item"><span class="page-numbers dots">', $output );
	$output = str_replace( '</span>', '</span></li>', $output );
	$output = str_replace( 'class="page-numbers current"', 'class="page-numbers current page-link"', $output );
	$output = str_replace( 'class="page-numbers"', 'class="page-numbers page-link"', $output );
	$output = str_replace( 'class="page-numbers dots"', 'class="page-numbers dots page-link"', $output );
	$output = str_replace( 'class="prev page-numbers"', 'class="prev page-numbers page-link"', $output );
	$output = str_replace( 'class="next page-numbers"', 'class="next page-numbers page-link"', $output );
	$output = sprintf( '<ul class="pagination">%s</ul>', $output );

	return $output;
}
add_filter( 'bbp_get_topic_pagination_links', 'enlightenment_bbp_bootstrap_topic_pagination_links' );

function enlightenment_bbp_bootstrap_alert_topic_lock( $output ) {
	$offset = strpos( $output, '<div class="bbp-alert-inner">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, "\n" . sprintf( '<div class="modal-content"><div class="modal-header"><button type="button" class="btn-close bbp-alert-close" aria-label="%s"></button></div>', __( 'Close', 'enlightenment' ) ), $offset + 29, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$offset = strpos( $output, '</div>', $offset + 1 );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, '<p class="bbp-alert-description">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="modal-body">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
	}

	$output = str_replace( 'class="bbp-alert-outer"', 'class="bbp-alert-outer modal fade show"', $output );
	$output = str_replace( 'class="bbp-alert-inner"', 'class="bbp-alert-inner modal-dialog"', $output );
	$output = str_replace( 'class="bbp-alert-description"', 'class="bbp-alert-description mb-0"', $output );
	$output = str_replace( 'class="bbp-alert-actions"', 'class="bbp-alert-actions modal-footer mb-0"', $output );
	$output = str_replace( 'class="bbp-alert-back"', 'class="bbp-alert-back btn btn-primary"', $output );
	$output = str_replace( 'class="bbp-alert-close"', 'class="bbp-alert-close btn btn-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_alert_topic_lock', 'enlightenment_bbp_bootstrap_alert_topic_lock' );

function enlightenment_bbp_bootstrap_search_loop( $output ) {
	if ( bbp_has_search_results() ) {
		$output = str_replace( 'class="bbp-pagination"', 'class="bbp-pagination d-sm-flex align-items-sm-center"', $output );
		$output = str_replace( 'class="bbp-pagination-links"', 'class="bbp-pagination-links mt-3 mt-sm-0 ms-sm-auto"', $output );
	} elseif ( bbp_get_search_terms() ) {
		$start = strpos( $output, '<div class="bbp-template-notice"' );
		if ( false !== $start ) {
			$offset = strpos( $output, '<ul>', $start );
			$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $start );

			while ( false !== $offset && $offset < $end ) {
				$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );
				$end    = strpos( $output, '</div>', $start );
				$offset = strpos( $output, '<li>', $offset + 1 );
			}
		}
	} else {
		$output = str_replace( 'class="screen-reader-text hidden"', 'class="screen-reader-text hidden visually-hidden"', $output );

		$offset = strpos( $output, '<input type="text"' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, '<div class="input-group">' . "\n", $offset, 0 );
			$offset = strpos( $output, 'id="bbp_search"', $offset );
			$output = substr_replace( $output, 'class="form-control" ', $offset, 0 );
			$offset = strpos( $output, 'class="button"', $offset );
			$output = substr_replace( $output, ' btn btn-light', $offset + 13, 0 );
			$offset = strpos( $output, ' />', $offset );
			$output = substr_replace( $output, '</div>', $offset + 3, 0 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_bbp_search_loop', 'enlightenment_bbp_bootstrap_search_loop' );

function enlightenment_bbp_bootstrap_search_pagination_links( $output ) {
	if ( empty( $output ) ) {
		return $output;
	}

	$output = str_replace( '<a ', '<li class="page-item"><a ', $output );
	$output = str_replace( '</a>', '</a></li>', $output );
	$output = str_replace( '<span aria-current="page" ', '<li class="page-item active"><span aria-current="page" ', $output );
	$output = str_replace( '<span class="page-numbers dots">', '<li class="page-item"><span class="page-numbers dots">', $output );
	$output = str_replace( '</span>', '</span></li>', $output );
	$output = str_replace( 'class="page-numbers current"', 'class="page-numbers current page-link"', $output );
	$output = str_replace( 'class="page-numbers"', 'class="page-numbers page-link"', $output );
	$output = str_replace( 'class="page-numbers dots"', 'class="page-numbers dots page-link"', $output );
	$output = str_replace( 'class="prev page-numbers"', 'class="prev page-numbers page-link"', $output );
	$output = str_replace( 'class="next page-numbers"', 'class="next page-numbers page-link"', $output );
	$output = sprintf( '<ul class="pagination">%s</ul>', $output );

	return $output;
}
add_filter( 'bbp_get_search_pagination_links', 'enlightenment_bbp_bootstrap_search_pagination_links' );

function enlightenment_bbp_bootstrap_single_user_details( $output ) {
	$output = str_replace( '<ul>', '<ul class="nav">', $output );
	$output = str_replace( '<li class="">', '<li class="nav-item">', $output );
	$output = str_replace( '<li class="current">', '<li class="current nav-item">', $output );
	$output = str_replace( '<a href=', '<a class="nav-link" href=', $output );

	$offset = strpos( $output, '<span class="vcard bbp-user-profile-link">' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<a class="url fn n"', $offset );
		$output = substr_replace( $output, 'nav-link ', $offset + 10, 0 );
	}

	$offset = strpos( $output, '<li class="current nav-item">' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<a class="nav-link', $offset );
		$output = substr_replace( $output, ' active', $offset + 18, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bbp_single_user_details', 'enlightenment_bbp_bootstrap_single_user_details' );

function enlightenment_bbp_bootstrap_single_user_body( $output ) {
	$output = str_replace( 'class="screen-reader-text hidden"', 'class="screen-reader-text hidden visually-hidden"', $output );
	$output = str_replace( 'class="bbp-pagination"', 'class="bbp-pagination d-sm-flex align-items-sm-center"', $output );
	$output = str_replace( 'class="bbp-pagination-links"', 'class="bbp-pagination-links mt-3 mt-sm-0 ms-sm-auto"', $output );

	$start = strpos( $output, '<div class="bbp-template-notice"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice"', $start + 1 );
	}

	$offset = strpos( $output, 'id="bbp-topic-search-form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<input type="text"', $offset );
		$output = substr_replace( $output, '<div class="input-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'id="ts"', $offset );
		$output = substr_replace( $output, 'class="form-control" ', $offset, 0 );
		$offset = strpos( $output, 'class="button"', $offset );
		$output = substr_replace( $output, ' btn btn-light', $offset + 13, 0 );
		$offset = strpos( $output, ' />', $offset );
		$output = substr_replace( $output, '</div>', $offset + 3, 0 );
	}

	$offset = strpos( $output, 'id="bbp-reply-search-form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<input type="text"', $offset );
		$output = substr_replace( $output, '<div class="input-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'id="rs"', $offset );
		$output = substr_replace( $output, 'class="form-control" ', $offset, 0 );
		$offset = strpos( $output, 'class="button"', $offset );
		$output = substr_replace( $output, ' btn btn-light', $offset + 13, 0 );
		$offset = strpos( $output, ' />', $offset );
		$output = substr_replace( $output, '</div>', $offset + 3, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bbp_single_user_body', 'enlightenment_bbp_bootstrap_single_user_body' );
add_filter( 'enlightenment_bp_filter_template_member_plugins_output', 'enlightenment_bbp_bootstrap_single_user_body' );

function enlightenment_bbp_bootstrap_single_user_edit( $output ) {
	if ( ! bbp_is_single_user_edit() ) {
		return $output;
	}

	$start = strpos( $output, '<fieldset ' );
	while ( false !== $start ) {
		$end    = strpos( $output, '</fieldset>', $start );
		$offset = strpos( $output, '<legend>', $start );

		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="screen-reader-text visually-hidden"', $offset + 7, 0 );
		}

		$start = strpos( $output, '<fieldset ', $start + 1 );
	}

	$start = strpos( $output, '<fieldset class="bbp-form">' );
	while ( false !== $start ) {
		$end    = strpos( $output, '</fieldset>', $start );
		$offset = strpos( $output, '<div>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="mb-3 row"', $offset + 4, 0 );
			$offset = strpos( $output, '<label ', $offset );
			$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
			$output = substr_replace( $output, '<tmp class="col-sm-3">', $offset, 0 );
			$offset = strpos( $output, '</label>', $offset );
			$output = substr_replace( $output, "\n" . '</tmp>' . "\n" . '<tmp class="col-sm-9">', $offset + 8, 0 );
			$offset = strpos( $output, '</div>', $offset );
			$output = substr_replace( $output, '</tmp>' . "\n", $offset, 0 );

			$end    = strpos( $output, '</fieldset>', $start );
			$offset = strpos( $output, '<div>', $offset + 1 );
		}

		$start = strpos( $output, '<fieldset class="bbp-form">', $start + 1 );
	}

	$offset = strpos( $output, 'class="user-pass1-wrap"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' mb-3 row', $offset + 22, 0 );
		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
		$output = substr_replace( $output, '<div class="col-sm-3">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="col-sm-9">', $offset + 8, 0 );
		$offset = strpos( $output, 'class="button wp-generate-pw hide-if-no-js"', $offset );
		$output = substr_replace( $output, 'btn btn-secondary ', $offset + 7, 0 );
		$offset = strpos( $output, '<fieldset class="bbp-form password wp-pwd hide-if-js">', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row gx-2">', $offset + 54, 0 );
		$offset = strpos( $output, 'class="password-button-wrapper', $offset );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="d-flex h-100">', $offset + 1, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$offset = strpos( $output, '</button>', $offset + 1 );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
		$offset = strpos( $output, '<div style="display:none" id="pass-strength-result" aria-live="polite"></div>', $offset );
		$output = substr_replace( $output, '<span class="d-block col-12 form-text"><span style="display:none" id="pass-strength-result" aria-live="polite"></span></span>', $offset, 77 );
		$offset = strpos( $output, '</fieldset>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 11, 0 );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );

		$offset = strpos( $output, '<div class="user-pass2-wrap hide-if-js">', $offset );
		$output = substr_replace( $output, "\n" . '<div class="mb-3 row">', $offset + 40, 0 );
		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
		$output = substr_replace( $output, '<div class="col-sm-3">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="col-sm-9">', $offset + 8, 0 );
		$offset = strpos( $output, '<p class="description">', $offset );
		$output = substr_replace( $output, '<span class="description d-block form-text">', $offset, 23 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, '</span>', $offset, 4 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>' . "\n" . '</div>' . "\n", $offset, 0 );

		$offset = strpos( $output, '<div>', $offset );
		$output = substr_replace( $output, ' class="mb-3 row"', $offset + 4, 0 );
		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
		$output = substr_replace( $output, '<tmp class="col-sm-3">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</tmp>' . "\n" . '<div class="col-sm-9">', $offset + 8, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</tmp>' . "\n", $offset, 0 );
	}

	$output = str_replace( '<tmp ',  '<div ',  $output );
	$output = str_replace( '</tmp>', '</div>', $output );

	$offset = strpos( $output, ' for="super_admin"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '', $offset, 18 );
		$offset = strpos( $output, '<label>', $offset );
		$output = substr_replace( $output, '<div class="form-check">', $offset, 7 );
		$offset = strpos( $output, 'class="checkbox"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 15, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<label class="form-check-label" for="super_admin">', $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	$output = str_replace( 'class="regular-text"', 'class="regular-text form-control"', $output );
	$output = str_replace( 'class="regular-text code"', 'class="regular-text code form-control"', $output );
	$output = str_replace( 'id="display_name"', 'id="display_name" class="form-control"', $output );
	$output = str_replace( 'id="description"', 'id="description" class="form-control"', $output );
	$output = str_replace( 'class="password-input-wrapper"', 'class="password-input-wrapper col flex-grow-1 flex-shrink-0"', $output );
	$output = str_replace( 'class="password-button-wrapper"', 'class="password-button-wrapper col flex-grow-0 flex-shrink-1"', $output );
	$output = str_replace( 'class="password-button-wrapper hide-if-no-js"', 'class="password-button-wrapper hide-if-no-js col flex-grow-0 flex-shrink-1"', $output );
	$output = str_replace( 'class="button wp-hide-pw hide-if-no-js"', 'class="btn btn-secondary d-flex align-items-center button wp-hide-pw hide-if-no-js"', $output );
	$output = str_replace( 'class="button wp-cancel-pw hide-if-no-js"', 'class="btn btn-secondary d-flex align-items-center ms-1 button wp-cancel-pw hide-if-no-js"', $output );
	$output = str_replace( 'id="locale"', 'id="locale" class="form-control"', $output );
	$output = str_replace( 'id="role"', 'id="role" class="form-control"', $output );
	$output = str_replace( 'id="bbp-forums-role"', 'id="bbp-forums-role" class="form-control"', $output );
	$output = str_replace( 'class="button submit user-submit"', 'class="button submit user-submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_single_user_body', 'enlightenment_bbp_bootstrap_single_user_edit' );

function enlightenment_bbp_bootstrap_feedback_no_access_args( $args ) {
	$args['wrapper_class'] = 'list-unstyled mb-0';
	$args['element_class'] = 'alert alert-info';

	return $args;
}
add_filter( 'enlightenment_bbp_feedback_no_access_args', 'enlightenment_bbp_bootstrap_feedback_no_access_args' );

function enlightenment_bbp_bootstrap_login_widget( $output ) {
	if ( is_user_logged_in() ) {
		$output = str_replace( 'class="bbp-logged-in"', 'class="bbp-logged-in d-flex align-items-center"', $output );
		$output = str_replace( 'class="submit user-submit"', 'class="submit user-submit flex-grow-0 flex-shrink-1 me-3"', $output );

		$offset = strpos( $output, '<h4>' );
		if ( false !== $offset ) {
            $output = substr_replace( $output, '<div class="flex-grow-1 flex-shrink-0">' . "\n", $offset, 0 );
			$offset = strpos( $output, 'class="button logout-link"', $offset );
			$offset = strpos( $output, '</a>', $offset );
			$output  = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
        }

		$output = str_replace( 'class="button logout-link"', 'class="button logout-link btn btn-secondary btn-sm"', $output );
	} else {
		$output = str_replace( 'class="bbp-username"', 'class="bbp-username mb-3"', $output );
		$output = str_replace( 'for="user_login"', 'for="user_login" class="form-label"', $output );
		$output = str_replace( 'id="user_login"', 'id="user_login" class="form-control"', $output );
		$output = str_replace( 'class="bbp-password"', 'class="bbp-password mb-3"', $output );
		$output = str_replace( 'for="user_pass"', 'for="user_pass" class="form-label"', $output );
		$output = str_replace( 'id="user_pass"', 'id="user_pass" class="form-control"', $output );
		$output = str_replace( 'class="bbp-remember-me"', 'class="bbp-remember-me mb-3 form-check"', $output );
		$output = str_replace( 'id="rememberme"', 'id="login-form-rememberme" class="form-check-input"', $output );
		$output = str_replace( 'for="rememberme"', 'for="login-form-rememberme" class="form-check-label"', $output );
		$output = str_replace( 'class="bbp-submit-wrapper"', 'class="bbp-submit-wrapper mb-3"', $output );
		$output = str_replace( 'class="button submit user-submit"', 'class="button submit user-submit btn btn-secondary btn-lg"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_widget_bbp_login_widget', 'enlightenment_bbp_bootstrap_login_widget' );

function enlightenment_bbp_bootstrap_search_widget( $output ) {
	$output = str_replace( 'class="screen-reader-text hidden"', 'class="screen-reader-text hidden visually-hidden"', $output );

	$offset = strpos( $output, '<input type="text"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="input-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'id="bbp_search"', $offset );
		$output = substr_replace( $output, 'class="form-control" ', $offset, 0 );
		$offset = strpos( $output, '<input class="button"', $offset );
		$offset = strpos( $output, 'class="button"', $offset );
		$output = substr_replace( $output, ' btn btn-light', $offset + 13, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_widget_bbp_search_widget', 'enlightenment_bbp_bootstrap_search_widget' );

function enlightenment_bbp_bootstrap_display_shortcode( $output ) {
	$start = strpos( $output, '<div class="bbp-template-notice"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice"', $start + 1 );
	}

	$start = strpos( $output, '<div class="bbp-template-notice info"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice info"', $start + 1 );
	}

	$start = strpos( $output, '<div class="bbp-template-notice error"' );
	while ( false !== $start ) {
		$offset = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $offset + 3, 0 );

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-danger"', $offset + 3, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset + 1 );
		}

		$start = strpos( $output, '<div class="bbp-template-notice error"', $start + 1 );
	}

	return $output;
}
add_filter( 'bbp_display_shortcode', 'enlightenment_bbp_bootstrap_display_shortcode' );

function enlightenment_bbp_bootstrap_display_shortcode_bbp_forum_form( $output ) {
	$output = str_replace( 'class="form-allowed-tags"', 'class="form-allowed-tags alert alert-info"', $output );
	$output = str_replace( 'class="bbp-the-content-wrapper"', 'class="bbp-the-content-wrapper mb-3"', $output );
	$output = str_replace( '</label><br />', '</label>', $output );
	$output = str_replace( '<input type="text"', '<input type="text" class="form-control"', $output );
	$output = str_replace( 'class="form-control" id="bbp_forum_title"', 'class="form-control form-control-lg" id="bbp_forum_title"', $output );
	$output = str_replace( 'class="bbp-the-content"', 'class="bbp-the-content form-control"', $output );
	$output = str_replace( 'class="bbp_dropdown"', 'class="bbp_dropdown form-select"', $output );
	$output = str_replace( 'class="button submit"', 'class="button submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_display_shortcode_bbp_forum_form', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_forum_form' );

function enlightenment_bbp_bootstrap_display_shortcode_pagination( $output ) {
	$output = str_replace( 'class="bbp-pagination"', 'class="bbp-pagination d-sm-flex align-items-sm-center"', $output );
	$output = str_replace( 'class="bbp-pagination-links"', 'class="bbp-pagination-links mt-3 mt-sm-0 ms-sm-auto"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_display_shortcode_bbp_single_forum', 'enlightenment_bbp_bootstrap_display_shortcode_pagination' );
add_filter( 'enlightenment_bbp_display_shortcode_bbp_topic_archive', 'enlightenment_bbp_bootstrap_display_shortcode_pagination' );
add_filter( 'enlightenment_bbp_display_shortcode_bbp_single_topic', 'enlightenment_bbp_bootstrap_display_shortcode_pagination' );

function enlightenment_bbp_bootstrap_display_shortcode_bbp_topic_form( $output ) {
	if ( bbp_current_user_can_access_create_topic_form() ) {
		$output = str_replace( '</label><br />', '</label>', $output );
		$output = str_replace( 'id="bbp_anonymous_author"', 'id="bbp_anonymous_author" class="form-control"', $output );
		$output = str_replace( 'id="bbp_anonymous_email"', 'id="bbp_anonymous_email" class="form-control"', $output );
		$output = str_replace( 'id="bbp_anonymous_website"', 'id="bbp_anonymous_website" class="form-control"', $output );
		$output = str_replace( 'for="bbp_topic_title"', 'for="bbp_topic_title" class="form-label"', $output );
		$output = str_replace( 'id="bbp_topic_title"', 'id="bbp_topic_title" class="form-control form-control-lg"', $output );
		$output = str_replace( 'class="bbp-the-content-wrapper"', 'class="bbp-the-content-wrapper mb-3"', $output );
		$output = str_replace( 'class="bbp-the-content"', 'class="bbp-the-content form-control"', $output );
		$output = str_replace( 'for="bbp_topic_tags"', 'for="bbp_topic_tags" class="form-label"', $output );
		$output = str_replace( 'class="form-allowed-tags"', 'class="form-allowed-tags alert alert-info"', $output );
		$output = str_replace( 'id="bbp_topic_tags"', 'id="bbp_topic_tags" class="form-control"', $output );
		$output = str_replace( 'for="bbp_forum_id"', 'for="bbp_forum_id" class="form-label"', $output );
		$output = str_replace( 'for="bbp_stick_topic"', 'for="bbp_stick_topic" class="form-label"', $output );
		$output = str_replace( 'class="bbp_dropdown"', 'class="bbp_dropdown form-select"', $output );
		$output = str_replace( 'id="bbp_stick_topic_select"', 'id="bbp_stick_topic_select" class="form-control"', $output );
		$output = str_replace( 'for="bbp_topic_status"', 'for="bbp_topic_status" class="form-label"', $output );
		$output = str_replace( 'id="bbp_topic_status_select"', 'id="bbp_topic_status_select" class="form-control"', $output );
		$output = str_replace( 'class="button submit"', 'class="button submit btn btn-primary btn-lg"', $output );
	} elseif( ! bbp_is_forum_closed() ) {
		$output = str_replace( 'class="bbp-username"', 'class="bbp-username mb-3"', $output );
		$output = str_replace( 'for="user_login"', 'for="user_login" class="form-label"', $output );
		$output = str_replace( 'id="user_login"', 'id="user_login" class="form-control"', $output );
		$output = str_replace( 'class="bbp-password"', 'class="bbp-password mb-3"', $output );
		$output = str_replace( 'for="user_pass"', 'for="user_pass" class="form-label"', $output );
		$output = str_replace( 'id="user_pass"', 'id="user_pass" class="form-control"', $output );
		$output = str_replace( 'class="bbp-remember-me"', 'class="bbp-remember-me mb-3 form-check"', $output );
		$output = str_replace( 'id="rememberme"', 'id="topic-form-rememberme" class="form-check-input"', $output );
		$output = str_replace( 'for="rememberme"', 'for="topic-form-rememberme" class="form-check-label"', $output );
		$output = str_replace( 'class="bbp-submit-wrapper"', 'class="bbp-submit-wrapper mb-3"', $output );
		$output = str_replace( 'class="button submit user-submit"', 'class="button submit user-submit btn btn-primary btn-lg"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_bbp_display_shortcode_bbp_topic_form', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_topic_form' );
add_filter( 'enlightenment_bbp_display_shortcode_bbp_single_forum', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_topic_form' );

function enlightenment_bbp_bootstrap_display_shortcode_bbp_reply_form( $output ) {
	if ( bbp_current_user_can_access_create_reply_form() ) {
		$output = str_replace( '</label><br />', '</label>', $output );
		$output = str_replace( 'id="bbp_anonymous_author"', 'id="bbp_anonymous_author" class="form-control"', $output );
		$output = str_replace( 'id="bbp_anonymous_email"', 'id="bbp_anonymous_email" class="form-control"', $output );
		$output = str_replace( 'id="bbp_anonymous_website"', 'id="bbp_anonymous_website" class="form-control"', $output );
		$output = str_replace( 'id="bbp_topic_title"', 'id="bbp_topic_title" class="form-control form-control-lg"', $output );
		$output = str_replace( 'class="bbp-the-content-wrapper"', 'class="bbp-the-content-wrapper mb-3"', $output );
		$output = str_replace( 'class="bbp-the-content"', 'class="bbp-the-content form-control"', $output );
		$output = str_replace( 'for="bbp_topic_tags"', 'for="bbp_topic_tags" class="form-label"', $output );
		$output = str_replace( 'class="form-allowed-tags"', 'class="form-allowed-tags alert alert-info"', $output );
		$output = str_replace( 'id="bbp_topic_tags"', 'id="bbp_topic_tags" class="form-control"', $output );
		$output = str_replace( 'id="bbp_stick_topic_select"', 'id="bbp_stick_topic_select" class="form-control"', $output );
		$output = str_replace( 'id="bbp_topic_status_select"', 'id="bbp_topic_status_select" class="form-control"', $output );
		$output = str_replace( 'class="button submit"', 'class="button submit btn btn-primary btn-lg"', $output );
	} elseif( ! bbp_is_topic_closed() && ! bbp_is_forum_closed( bbp_get_topic_forum_id() ) ) {
		$output = str_replace( 'class="bbp-username"', 'class="bbp-username mb-3"', $output );
		$output = str_replace( 'for="user_login"', 'for="user_login" class="form-label"', $output );
		$output = str_replace( 'id="user_login"', 'id="user_login" class="form-control"', $output );
		$output = str_replace( 'class="bbp-password"', 'class="bbp-password mb-3"', $output );
		$output = str_replace( 'for="user_pass"', 'for="user_pass" class="form-label"', $output );
		$output = str_replace( 'id="user_pass"', 'id="user_pass" class="form-control"', $output );
		$output = str_replace( 'class="bbp-remember-me"', 'class="bbp-remember-me mb-3 form-check"', $output );
		$output = str_replace( 'id="rememberme"', 'id="reply-form-rememberme" class="form-check-input"', $output );
		$output = str_replace( 'for="rememberme"', 'for="reply-form-rememberme" class="form-check-label"', $output );
		$output = str_replace( 'class="bbp-submit-wrapper"', 'class="bbp-submit-wrapper mb-3"', $output );
		$output = str_replace( 'class="button submit user-submit"', 'class="button submit user-submit btn btn-primary btn-lg"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_bbp_display_shortcode_bbp_reply_form', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_reply_form' );
add_filter( 'enlightenment_bbp_display_shortcode_bbp_single_topic', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_reply_form' );

function enlightenment_bbp_bootstrap_display_shortcode_bbp_search( $output ) {
	$output = str_replace( 'class="screen-reader-text hidden"', 'class="screen-reader-text hidden visually-hidden"', $output );

	$offset = strpos( $output, '<input type="text"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="input-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$output = str_replace( 'id="bbp_search"', 'id="bbp_search" class="form-control"', $output );
	$output = str_replace( 'class="button"', 'class="button btn btn-light"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_display_shortcode_bbp_search', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_search' );
add_filter( 'enlightenment_bbp_display_shortcode_bbp_search_form', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_search' );
add_filter( 'enlightenment_bbp_display_shortcode_bbp_forum_archive', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_search' );
add_filter( 'enlightenment_bbp_display_shortcode_bbp_topic_archive', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_search' );
add_filter( 'enlightenment_bbp_display_shortcode_bbp_topic_tag', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_search' );

function enlightenment_bbp_bootstrap_display_shortcode_bbp_login( $output ) {
	$output = str_replace( 'class="bbp-username"', 'class="bbp-username mb-3"', $output );
	$output = str_replace( 'for="user_login"', 'for="user_login" class="form-label"', $output );
	$output = str_replace( 'id="user_login"', 'id="user_login" class="form-control"', $output );
	$output = str_replace( 'class="bbp-password"', 'class="bbp-password mb-3"', $output );
	$output = str_replace( 'for="user_pass"', 'for="user_pass" class="form-label"', $output );
	$output = str_replace( 'id="user_pass"', 'id="user_pass" class="form-control"', $output );
	$output = str_replace( 'class="bbp-remember-me"', 'class="bbp-remember-me mb-3 form-check"', $output );
	$output = str_replace( 'id="rememberme"', 'id="bbp-login-form-rememberme" class="form-check-input"', $output );
	$output = str_replace( 'for="rememberme"', 'for="bbp-login-form-rememberme" class="form-check-label"', $output );
	$output = str_replace( 'class="bbp-submit-wrapper"', 'class="bbp-submit-wrapper mb-3"', $output );
	$output = str_replace( 'class="button submit user-submit"', 'class="button submit user-submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_display_shortcode_bbp_login', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_login' );

function enlightenment_bbp_bootstrap_display_shortcode_bbp_register( $output ) {
	$output = str_replace( 'class="bbp-username"', 'class="bbp-username mb-3"', $output );
	$output = str_replace( 'for="user_login"', 'for="user_login" class="form-label"', $output );
	$output = str_replace( 'id="user_login"', 'id="user_login" class="form-control"', $output );
	$output = str_replace( 'class="bbp-email"', 'class="bbp-email mb-3"', $output );
	$output = str_replace( 'for="user_email"', 'for="user_email" class="form-label"', $output );
	$output = str_replace( 'id="user_email"', 'id="user_email" class="form-control"', $output );
	$output = str_replace( 'class="bbp-submit-wrapper"', 'class="bbp-submit-wrapper mb-3"', $output );
	$output = str_replace( 'class="button submit user-submit"', 'class="button submit user-submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_display_shortcode_bbp_register', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_register' );

function enlightenment_bbp_bootstrap_display_shortcode_bbp_lost_pass( $output ) {
	$output = str_replace( 'id="user_login"', 'id="user_login" class="form-control"', $output );
	$output = str_replace( 'class="bbp-submit-wrapper"', 'class="bbp-submit-wrapper mb-3"', $output );
	$output = str_replace( 'class="button submit user-submit"', 'class="button submit user-submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_display_shortcode_bbp_lost_pass', 'enlightenment_bbp_bootstrap_display_shortcode_bbp_lost_pass' );
