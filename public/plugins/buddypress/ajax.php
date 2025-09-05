<?php

function enlightenment_bp_ajax_actions() {
	remove_action( 'wp_ajax_activity_filter',        'bp_nouveau_ajax_object_template_loader' );
	remove_action( 'wp_ajax_nopriv_activity_filter', 'bp_nouveau_ajax_object_template_loader' );
	add_action(    'wp_ajax_activity_filter',        'enlightenment_bp_ajax_object_template_loader' );
	add_action(    'wp_ajax_nopriv_activity_filter', 'enlightenment_bp_ajax_object_template_loader' );

    remove_action( 'wp_ajax_new_activity_comment', 'bp_nouveau_ajax_new_activity_comment' );
    add_action(    'wp_ajax_new_activity_comment', 'enlightenment_bp_ajax_new_activity_comment' );

	remove_action( 'wp_ajax_members_filter',        'bp_nouveau_ajax_object_template_loader' );
	remove_action( 'wp_ajax_nopriv_members_filter', 'bp_nouveau_ajax_object_template_loader' );
	add_action(    'wp_ajax_members_filter',        'enlightenment_bp_ajax_object_template_loader' );
	add_action(    'wp_ajax_nopriv_members_filter', 'enlightenment_bp_ajax_object_template_loader' );

    remove_action( 'wp_ajax_groups_filter',        'bp_nouveau_ajax_object_template_loader' );
	remove_action( 'wp_ajax_nopriv_groups_filter', 'bp_nouveau_ajax_object_template_loader' );
	add_action(    'wp_ajax_groups_filter',        'enlightenment_bp_ajax_object_template_loader' );
	add_action(    'wp_ajax_nopriv_groups_filter', 'enlightenment_bp_ajax_object_template_loader' );

    remove_action( 'wp_ajax_blogs_filter',        'bp_nouveau_ajax_object_template_loader' );
	remove_action( 'wp_ajax_nopriv_blogs_filter', 'bp_nouveau_ajax_object_template_loader' );
	add_action(    'wp_ajax_blogs_filter',        'enlightenment_bp_ajax_object_template_loader' );
	add_action(    'wp_ajax_nopriv_blogs_filter', 'enlightenment_bp_ajax_object_template_loader' );

	if ( has_action( 'wp_ajax_rtmedia_get_template' ) || has_action( 'wp_ajax_nopriv_rtmedia_get_template' ) ) {
		global $wp_filter;

		if ( has_action( 'wp_ajax_rtmedia_get_template' ) ) {
			foreach ( $wp_filter['wp_ajax_rtmedia_get_template']->callbacks as $priority => $callbacks ) {
				foreach ( $callbacks as $key => $callback ) {
					if (
						is_array( $callback['function'] ) &&
						$callback['function'][0] instanceof RTMediaGalleryShortcode &&
						'ajax_rtmedia_get_template' == $callback['function'][1]
					) {
						remove_action( 'wp_ajax_rtmedia_get_template', array( &$callback['function'][0], $callback['function'][1] ) );
						add_action(    'wp_ajax_rtmedia_get_template', 'enlightenment_bp_ajax_rtmedia_get_template' );
					}
				}
			}
		}

		if ( has_action( 'wp_ajax_nopriv_rtmedia_get_template' ) ) {
			foreach ( $wp_filter['wp_ajax_rtmedia_get_template']->callbacks as $priority => $callbacks ) {
				foreach ( $callbacks as $key => $callback ) {
					if (
						is_array( $callback['function'] ) &&
						$callback['function'][0] instanceof RTMediaGalleryShortcode &&
						'ajax_rtmedia_get_template' == $callback['function'][1]
					) {
						remove_action( 'wp_ajax_nopriv_rtmedia_get_template', array( &$callback['function'][0], $callback['function'][1] ) );
						add_action(    'wp_ajax_nopriv_rtmedia_get_template', 'enlightenment_bp_ajax_rtmedia_get_template' );
					}
				}
			}
		}
	}
}
add_action( 'admin_init', 'enlightenment_bp_ajax_actions', 14 );

/**
 * Load the template loop for the current object.
 *
 * @see bp_nouveau_ajax_object_template_loader()
 * @since 2.0.0
 *
 * @return string Template loop for the specified object
**/
function enlightenment_bp_ajax_object_template_loader() {
    if ( ! bp_is_post_request() ) {
		wp_send_json_error();
	}

	$post_vars = bp_parse_args(
		$_POST,
		array(
			'action'   => '',
			'object'   => '',
			'scope'    => '',
			'filter'   => '',
			'nonce'    => '',
			'template' => '',
		)
	);

	$object = sanitize_title( $post_vars['object'] );

	// Bail if object is not an active component to prevent arbitrary file inclusion.
	if ( ! bp_is_active( $object ) ) {
		wp_send_json_error();
	}

	// Nonce check!
	if ( empty( $post_vars['nonce'] ) || ! wp_verify_nonce( $post_vars['nonce'], 'bp_nouveau_' . $object ) ) {
		wp_send_json_error();
	}

	$result = array();

	if ( 'activity' === $object ) {
		$scope = '';
		if ( $post_vars['scope'] ) {
			$scope = sanitize_text_field( $post_vars['scope'] );
		}

		// We need to calculate and return the feed URL for each scope.
		switch ( $scope ) {
			case 'friends':
				$feed_url = bp_loggedin_user_url( bp_members_get_path_chunks( array( bp_nouveau_get_component_slug( 'activity' ), 'friends', array( 'feed' ) ) ) );
				break;
			case 'groups':
				$feed_url = bp_loggedin_user_url( bp_members_get_path_chunks( array( bp_nouveau_get_component_slug( 'activity' ), 'groups', array( 'feed' ) ) ) );
				break;
			case 'favorites':
				$feed_url = bp_loggedin_user_url( bp_members_get_path_chunks( array( bp_nouveau_get_component_slug( 'activity' ), 'favorites', array( 'feed' ) ) ) );
				break;
			case 'mentions':
				$feed_url = bp_loggedin_user_url( bp_members_get_path_chunks( array( bp_nouveau_get_component_slug( 'activity' ), 'mentions', array( 'feed' ) ) ) );

				// Get user new mentions
				$new_mentions = bp_get_user_meta( bp_loggedin_user_id(), 'bp_new_mentions', true );

				// If we have some, include them into the returned json before deleting them
				if ( is_array( $new_mentions ) ) {
					$result['new_mentions'] = $new_mentions;

					// Clear new mentions
					bp_activity_clear_new_mentions( bp_loggedin_user_id() );
				}

				break;
			default:
				$feed_url = bp_get_sitewide_activity_feed_link();
				break;
		}

		/**
		 * Filters the browser URL for the template loader.
		 *
		 * @since 3.0.0
		 *
		 * @param string $feed_url Template feed url.
		 * @param string $scope    Current component scope.
		 */
		$result['feed_url'] = apply_filters( 'bp_nouveau_ajax_object_template_loader', $feed_url, $scope );
	}

	/*
	 * AJAX requests happen too early to be seen by bp_update_is_directory()
	 * so we do it manually here to ensure templates load with the correct
	 * context. Without this check, templates will load the 'single' version
	 * of themselves rather than the directory version.
	 */
	if ( ! bp_current_action() ) {
		bp_update_is_directory( true, bp_current_component() );
	}

	// Get the template path based on the 'template' variable via the AJAX request.
	$template = '';
	if ( $post_vars['template'] ) {
		$template = wp_unslash( $post_vars['template'] );
	}

	switch ( $template ) {
		case 'group_members' :
		case 'groups/single/members' :
			$template_part = 'groups/single/members-loop.php';
		break;

		case 'group_requests' :
			$template_part = 'groups/single/requests-loop.php';
		break;

		case 'friend_requests' :
			$template_part = 'members/single/friends/requests-loop.php';
		break;

		case 'member_notifications' :
			$template_part = 'members/single/notifications/notifications-loop.php';
		break;

		default :
			$template_part = $object . '/' . $object . '-loop.php';
		break;
	}

	ob_start();

	$template_path = bp_locate_template( array( $template_part ), false );

	/**
	 * Filters the server path for the template loader.
	 *
	 * @since 3.0.0
	 *
	 * @param string Template file path.
	 */
	$template_path = apply_filters( 'bp_nouveau_object_template_path', $template_path );

	load_template( $template_path );
	$result['contents'] = ob_get_contents();
	ob_end_clean();

	/**
	 * Add additional info to the Ajax response.
	 *
	 * @since 10.0.0
	 *
	 * @param array $value     An associative array with additional information to include in the Ajax response.
	 * @param array $post_vars An associative array containing the Ajax request arguments.
	 */
	$additional_info = apply_filters( "bp_nouveau_{$object}_ajax_object_template_response", array(), $post_vars );
	if ( $additional_info ) {
		// Prevents content overrides.
		if ( isset( $additional_info['contents'] ) ) {
			unset( $additional_info['contents'] );
		}

		$result = array_merge( $result, $additional_info );
	}

    if ( '.php' == substr( $template_part, -4 ) ) {
        $template_part = substr( $template_part, 0, -4 );
    }

    $result['contents'] = apply_filters( sprintf( 'enlightenment_bp_filter_template_%s_output', $template_part ), $result['contents'] );

	// Locate the object template.
	wp_send_json_success( $result );
}

/**
 * Posts new Activity comments received via a POST request.
 *
 * @since 3.0.0
 *
 * @global BP_Activity_Template $activities_template
 *
 * @return string JSON reply
 */
function enlightenment_bp_ajax_new_activity_comment() {
	global $activities_template;
	$bp = buddypress();

    $error_class = apply_filters( 'enlightenment_bp_ajax_new_activity_comment_error_class', 'bp-feedback bp-messages error' );

	$response = array(
		'feedback' => sprintf(
			'<div class="%s">%s</div>',
            esc_attr( $error_class ),
			esc_html__( 'There was an error posting your reply. Please try again.', 'enlightenment' )
		),
	);

	// Bail if not a POST action.
	if ( ! bp_is_post_request() ) {
		wp_send_json_error( $response );
	}

	// Nonce check!
	if ( empty( $_POST['_wpnonce_new_activity_comment'] ) || ! wp_verify_nonce( $_POST['_wpnonce_new_activity_comment'], 'new_activity_comment' ) ) {
		wp_send_json_error( $response );
	}

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( $response );
	}

	if ( empty( $_POST['content'] ) ) {
		wp_send_json_error( array( 'feedback' => sprintf(
			'<div class="%s">%s</div>',
            esc_attr( $error_class ),
			esc_html__( 'Please do not leave the comment area blank.', 'enlightenment' )
		) ) );
	}

	if ( empty( $_POST['form_id'] ) || empty( $_POST['comment_id'] ) || ! is_numeric( $_POST['form_id'] ) || ! is_numeric( $_POST['comment_id'] ) ) {
		wp_send_json_error( $response );
	}

	$activity_id   = (int) $_POST['form_id'];
	$activity_item = new BP_Activity_Activity( $activity_id );
	if ( ! bp_activity_user_can_read( $activity_item ) ) {
		wp_send_json_error( $response );
	}

	$comment_id = bp_activity_new_comment( array(
		'activity_id' => $_POST['form_id'],
		'content'     => $_POST['content'],
		'parent_id'   => $_POST['comment_id'],
	) );

	if ( ! $comment_id ) {
		if ( ! empty( $bp->activity->errors['new_comment'] ) && is_wp_error( $bp->activity->errors['new_comment'] ) ) {
			$response = array( 'feedback' => sprintf(
    			'<div class="%s">%s</div>',
                esc_attr( $error_class ),
				esc_html( $bp->activity->errors['new_comment']->get_error_message() )
			) );
			unset( $bp->activity->errors['new_comment'] );
		}

		wp_send_json_error( $response );
	}

	// Load the new activity item into the $activities_template global.
	bp_has_activities(
		array(
			'display_comments' => 'stream',
			'hide_spam'        => false,
			'show_hidden'      => true,
			'include'          => $comment_id,
		)
	);

	// Swap the current comment with the activity item we just loaded.
	if ( isset( $activities_template->activities[0] ) ) {
		$activities_template->activity                  = new stdClass();
		$activities_template->activity->id              = $activities_template->activities[0]->item_id;
		$activities_template->activity->current_comment = $activities_template->activities[0];

		// Because the whole tree has not been loaded, we manually
		// determine depth.
		$depth     = 1;
		$parent_id = (int) $activities_template->activities[0]->secondary_item_id;
		while ( $parent_id !== (int) $activities_template->activities[0]->item_id ) {
			$depth++;
			$p_obj     = new BP_Activity_Activity( $parent_id );
			$parent_id = (int) $p_obj->secondary_item_id;
		}
		$activities_template->activity->current_comment->depth = $depth;
	}

	ob_start();
	// Get activity comment template part.
	bp_get_template_part( 'activity/comment' );
	$response = array( 'contents' => ob_get_contents() );
	ob_end_clean();

	unset( $activities_template );

    $response['contents'] = apply_filters( 'enlightenment_bp_filter_template_activity/comment_output', $response['contents'] );

	wp_send_json_success( $response );
}

function enlightenment_bp_ajax_rtmedia_get_template() {
	$template = sanitize_text_field( filter_input( INPUT_GET, 'template', FILTER_SANITIZE_STRING ) );

	if ( ! empty( $template ) ) {
		$template_url = RTMediaTemplate::locate_template( $template, 'media/', false );

		ob_start();
		require_once $template_url;
		$output = ob_get_clean();

		$template_slug = 'media/' . $template;

		$output = apply_filters( sprintf( 'enlightenment_bp_filter_template_rtmedia_%s', $template_slug ), $output );

		echo $output;
	}
	die();
}
