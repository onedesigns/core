<?php

if ( ! bp_is_active( 'notifications' ) ) {
	return;
}

function enlightenment_bp_header_notification_callbacks() {
	return apply_filters( 'enlightenment_bp_header_notification_callbacks', array(
		// 'profile'             => 'enlightenment_bp_xprofile_format_notifications',
		'activity'            => 'enlightenment_bp_activity_format_notifications',
		// 'blogs'               => 'enlightenment_bp_blogs_format_notifications',
		'friends'             => 'enlightenment_bp_friends_format_notifications',
		// 'groups'              => 'enlightenment_bp_groups_format_notifications',
		'messages'            => 'enlightenment_bp_messages_format_notifications',
		'forums'              => 'enlightenment_bp_forums_format_notifications',
		'follow'              => 'enlightenment_bp_follow_format_notifications',
		// 'profile'             => 'enlightenment_bp_rt_comment_notifications_callback',
		// 'profile'             => 'enlightenment_bp_like_notifications_callback',
		'friend_update'       => 'enlightenment_bp_friend_update_format_notifications',
		'rt_like_notifier'    => 'enlightenment_bp_rt_like_notifier_format_notifications',
		'rt_comment_notifier' => 'enlightenment_bp_rt_comment_notifier_format_notifications',
		'bp_fav2like'         => 'enlightenment_bp_fav2like_format_notifications',
		'bp_share_activity'   => 'enlightenment_bp_share_activity_format_notifications',
	) );
}

function enlightenment_bp_activity_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string', $id = 0 ) {
	$user = get_userdata( $secondary_item_id );

	switch ( $action ) {
		case 'new_at_mention':
			$link = bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/';
			$text = sprintf( __( '%1$s %2$s mentioned you', 'enlightenment' ), bp_core_fetch_avatar( array(
				'item_id' => $user->ID,
				'type'    => 'thumb',
				'width'   => 96,
				'height'  => 96,
				'email'   => $user->user_email,
			) ), bp_core_get_userlink( $user->ID ) );
		break;

		case 'update_reply':
			$link = add_query_arg( 'nid', (int) $id, bp_activity_get_permalink( $item_id ) );
			$text = sprintf( __( '%1$s %2$s commented on your post', 'enlightenment' ), bp_core_fetch_avatar( array(
				'item_id' => $user->ID,
				'type'    => 'thumb',
				'width'   => 96,
				'height'  => 96,
				'email'   => $user->user_email,
			) ), bp_core_get_userlink( $user->ID ) );
		break;

		case 'comment_reply':
			$link = add_query_arg( 'nid', (int) $id, bp_activity_get_permalink( $item_id ) );
			$text = sprintf( __( '%1$s %2$s replied to your comment', 'enlightenment' ), bp_core_fetch_avatar( array(
				'item_id' => $user->ID,
				'type'    => 'thumb',
				'width'   => 96,
				'height'  => 96,
				'email'   => $user->user_email,
			) ), bp_core_get_userlink( $user->ID ) );
		break;
	}

	return array(
		'text' => $text,
		'link' => $link
	);
}

function enlightenment_bp_friends_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	$user = get_userdata( $item_id );

	switch ( $action ) {
		case 'friendship_accepted':
			$link = bp_members_get_user_url( $item_id );
			$text = sprintf( __( '%1$s %2$s accepted your friendship request', 'enlightenment' ),  bp_core_fetch_avatar( array(
				'item_id' => $user->ID,
				'type'    => 'thumb',
				'width'   => 96,
				'height'  => 96,
				'email'   => $user->user_email,
			) ), bp_core_get_userlink( $user->ID ) );
		break;

		case 'friendship_request':
			$link = bp_loggedin_user_domain() . bp_get_friends_slug() . '/requests/?new';
			$text = sprintf( __( '%1$s %2$s sent you a friendship request', 'enlightenment' ),  bp_core_fetch_avatar( array(
				'item_id' => $user->ID,
				'type'    => 'thumb',
				'width'   => 96,
				'height'  => 96,
				'email'   => $user->user_email,
			) ), bp_core_get_userlink( $user->ID ) );
		break;
	}

	return array(
		'link' => $link,
		'text' => $text
	);
}

function enlightenment_bp_forums_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	if ( 'bbp_new_reply' !== $action ) {
		return $action;
	}

	$post = get_post( $item_id );
	$user = get_userdata( $secondary_item_id );

	$topic_id = bbp_get_reply_topic_id( $item_id );
	$link     = wp_nonce_url(
		add_query_arg(
			array(
				'action'   => 'bbp_mark_read',
				'topic_id' => $topic_id
			),
			bbp_get_reply_url( $item_id )
		),
		'bbp_mark_topic_' . $topic_id
	);

	$text = sprintf(
		__( '%1$s %2$s replied to &ldquo;%3$s&rdquo;', 'enlightenment' ),
		bp_core_fetch_avatar( array(
			'item_id' => $user->ID,
			'type'    => 'thumb',
			'width'   => 96,
			'height'  => 96,
			'email'   => $user->user_email,
		) ),
		bp_core_get_userlink( $user->ID ),
		bbp_get_topic_title( $topic_id )
	);

	return array(
		'link' => $link,
		'text' => $text,
	);
}

function enlightenment_bp_messages_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	$user = get_userdata( $secondary_item_id );

	if ( 'new_message' === $action ) {
		// Get message thread ID.
		$message   = new BP_Messages_Message( $item_id );
		$thread_id = $message->thread_id;
		$link      = ( ! empty( $thread_id ) ) ? bp_get_message_thread_view_link( $thread_id ) : false;
		$text = sprintf( __( '%1$s %2$s sent you a private message', 'enlightenment' ), bp_core_fetch_avatar( array(
			'item_id' => $user->ID,
			'type'    => 'thumb',
			'width'   => 96,
			'height'  => 96,
			'email'   => $user->user_email,
		) ), bp_core_get_userlink( $user->ID ) );

		return array(
			'link' => $link,
			'text' => $text,
		);

	// Custom notification action for the Messages component
	} else {
		return apply_filters( "bp_messages_{$action}_notification", array(
			'link' => trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() . '/inbox' ),
			'text' => '',
		), $item_id, $secondary_item_id, $total_items, $format );
	}
}

function enlightenment_bp_follow_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	$bp = buddypress();

	do_action( 'bp_follow_format_notifications', $action, $item_id, $secondary_item_id, $total_items, $format );

	switch ( $action ) {
		case 'new_follow':
			$user = get_userdata( $item_id );

			$text = sprintf( __( '%1$s %2$s is now following you', 'enlightenment' ), bp_core_fetch_avatar( array(
				'item_id' => $user->ID,
				'type'    => 'thumb',
				'width'   => 96,
				'height'  => 96,
				'email'   => $user->user_email,
			) ), bp_core_get_userlink( $user->ID ) );
			$link = bp_members_get_user_url( $user->ID  ) . '?bpf_read';
		break;

		default :
			$link = apply_filters( 'bp_follow_extend_notification_link', false, $action, $item_id, $secondary_item_id, $total_items );
			$text = apply_filters( 'bp_follow_extend_notification_text', false, $action, $item_id, $secondary_item_id, $total_items );
		break;
	}

	return apply_filters( 'bp_follow_new_followers_return_notification', array(
		'text' => $text,
		'link' => $link
	), $item_id, $secondary_item_id, $total_items );
}

function enlightenment_bp_friend_update_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	// Bail if not the notification action we are looking for
	if ( 'friend_update' !== $action ) {
		return $action;
	}

	$data = bp_activity_get_meta( $item_id, 'bp_url_scraper', true );
	$user = get_userdata( $secondary_item_id );

	if( ! empty( $data ) && isset( $data['url'] ) && ! empty( $data['url'] ) && ! $data['embed'] ) {
		$text = sprintf( __( '%1$s %2$s shared a link on your profile', 'enlightenment'), bp_core_fetch_avatar( array(
			'item_id' => $user->ID,
			'type'    => 'thumb',
			'width'   => 96,
			'height'  => 96,
			'email'   => $user->user_email,
		) ), bp_core_get_userlink( $user->ID ) );
	} else {
		$text = sprintf( __( '%1$s %2$s posted on your profile', 'enlightenment'), bp_core_fetch_avatar( array(
			'item_id' => $user->ID,
			'type'    => 'thumb',
			'width'   => 96,
			'height'  => 96,
			'email'   => $user->user_email,
		) ), bp_core_get_userlink( $user->ID ) );
	}

	$link = bp_activity_get_permalink( $item_id );

	return array(
		'text' => $text,
		'link' => $link,
	);
}

function enlightenment_bp_rt_like_notifier_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string', $id = 0 ) {
	$user = get_userdata( $secondary_item_id );

	$action = str_replace( $item_id, '', $action );

	switch ( $action ) {
		case 'new_like_to_media':
			$rtmedia_id = rtmedia_id( $item_id );

			if ( 1 === intval( $total_items ) ) {
				$text = __( '%1$s %2$s liked your %3$s', 'enlightenment' );
			} elseif ( 2 === intval( $total_items ) ) {
				$text = __( '%1$s %2$s and one more friend liked your %3$s', 'enlightenment' );
			} else {
				$text = __( '%1$s %2$s and %4$s other friends liked your %3$s', 'enlightenment' );
			}

			$link = esc_url( get_rtmedia_permalink( $rtmedia_id ) );
			$text = sprintf(
				$text,
				bp_core_fetch_avatar( array(
					'item_id' => $user->ID,
					'type'    => 'thumb',
					'width'   => 96,
					'height'  => 96,
					'email'   => $user->user_email,
				) ),
				bp_core_get_userlink( $user->ID ),
				rtmedia_type( $rtmedia_id ),
				( $total_items - 1 )
			);
		break;
	}

	return array(
		'text' => $text,
		'link' => $link
	);
}

function enlightenment_bp_rt_comment_notifier_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string', $id = 0 ) {
	$user = get_userdata( $secondary_item_id );

	$action = str_replace( $item_id, '', $action );

	switch ( $action ) {
		case 'new_comment_to_media':
			$rtmedia_id = rtmedia_id( $item_id );

			if ( 1 === intval( $total_items ) ) {
				$text = __( '%1$s %2$s commented on your %3$s', 'enlightenment' );
			} elseif ( 2 === intval( $total_items ) ) {
				$text = __( '%1$s %2$s and one more commented on your %3$s', 'enlightenment' );
			} else {
				$text = __( '%1$s %2$s and %4$s other friends commented on your %3$s', 'enlightenment' );
			}

			$link = esc_url( get_rtmedia_permalink( $rtmedia_id ) );
			$text = sprintf(
				$text,
				bp_core_fetch_avatar( array(
					'item_id' => $user->ID,
					'type'    => 'thumb',
					'width'   => 96,
					'height'  => 96,
					'email'   => $user->user_email,
				) ),
				bp_core_get_userlink( $user->ID ),
				rtmedia_type( $rtmedia_id ),
				( $total_items - 1 )
			);
		break;
	}

	return array(
		'text' => $text,
		'link' => $link
	);
}

function enlightenment_bp_fav2like_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	// Bail if not the notification action we are looking for
	if( 'like' !== $action ) {
		return $action;
	}

	$user = get_userdata( $item_id );

	$avatar = bp_core_fetch_avatar( array(
		'item_id' => $user->ID,
		'type'    => 'thumb',
		'width'   => 96,
		'height'  => 96,
		'email'   => $user->user_email,
	) );

	$userlink = bp_core_get_userlink( $user->ID );

	$activity = new BP_Activity_Activity( $secondary_item_id );

	switch( $activity->type ) {
		case 'activity_update':
			$text = sprintf( __( '%1$s %2$s likes your status', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'activity_comment':
		case 'new_blog_comment':
			$text = sprintf( __( '%1$s %2$s likes your comment', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'friend_update':
			if( $activity->item_id == $item_id ) {
				$text = sprintf( __( '%1$s %2$s likes your post on their profile', 'bpfav2like'), $avatar, $userlink );
			} else {
				$display_name = bp_core_get_user_displayname( $activity->item_id );

				if( 's' == substr( $display_name, -1 ) ) {
					$text = sprintf( __( '%1$s %2$s likes your post on %3$s&#8217; profile', 'bpfav2like'), $avatar, $userlink, $display_name );
				} else {
					$text = sprintf( __( '%1$s %2$s likes your post on %3$s&#8217;s profile', 'bpfav2like'), $avatar, $userlink, $display_name );
				}
			}
			$text = sprintf( __( '%1$s %2$s likes your status', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'new_blog':
			$text = sprintf( __( '%1$s %2$s likes your site', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'new_blog_post':
			$text = sprintf( __( '%1$s %2$s likes your blog post', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'bbp_topic_create':
			$text = sprintf( __( '%1$s %2$s likes your topic', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'bbp_reply_create':
			$text = sprintf( __( '%1$s %2$s likes your reply', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'rtmedia_update':
			$text = sprintf( __( '%1$s %2$s likes your photo', 'bpfav2like'), $avatar, $userlink );
		break;

		default:
			$text = sprintf( __( '%s likes your activity', 'bpfav2like'), $avatar, $userlink );
		break;
	}

	$link = bp_activity_get_permalink( $secondary_item_id );

	return array(
		'text' => $text,
		'link' => $link,
	);
}

function enlightenment_bp_share_activity_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	// Bail if not the notification action we are looking for
	if( 'share_activity' !== $action ) {
		return $action;
	}

	$user = get_userdata( $item_id );

	$avatar = bp_core_fetch_avatar( array(
		'item_id' => $user->ID,
		'type'    => 'thumb',
		'width'   => 96,
		'height'  => 96,
		'email'   => $user->user_email,
	) );

	$userlink = bp_core_get_userlink( $user->ID );

	$activity = new BP_Activity_Activity( $secondary_item_id );

	switch( $activity->type ) {
		case 'activity_update':
			$text = sprintf( __( '%1$s %2$s shared your status', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'activity_comment':
		case 'new_blog_comment':
			$text = sprintf( __( '%1$s %2$s shared your comment', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'friend_update':
			if( $activity->item_id == $item_id ) {
				$text = sprintf( __( '%1$s %2$s shared your post on their profile', 'bpfav2like'), $avatar, $userlink );
			} else {
				$display_name = bp_core_get_user_displayname( $activity->item_id );

				if( 's' == substr( $display_name, -1 ) ) {
					$text = sprintf( __( '%1$s %2$s shared your post on %3$s&#8217; profile', 'bpfav2like'), $avatar, $userlink, $display_name );
				} else {
					$text = sprintf( __( '%1$s %2$s shared your post on %3$s&#8217;s profile', 'bpfav2like'), $avatar, $userlink, $display_name );
				}
			}
			$text = sprintf( __( '%1$s %2$s shared your status', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'new_blog':
			$text = sprintf( __( '%1$s %2$s shared your site', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'new_blog_post':
			$text = sprintf( __( '%1$s %2$s shared your blog post', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'bbp_topic_create':
			$text = sprintf( __( '%1$s %2$s shared your topic', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'bbp_reply_create':
			$text = sprintf( __( '%1$s %2$s shared your reply', 'bpfav2like'), $avatar, $userlink );
		break;

		case 'rtmedia_update':
			$text = sprintf( __( '%1$s %2$s shared your photo', 'bpfav2like'), $avatar, $userlink );
		break;

		default:
			$text = sprintf( __( '%s shared your activity', 'bpfav2like'), $avatar, $userlink );
		break;
	}

	$link = bp_activity_get_permalink( $secondary_item_id );

	return array(
		'text' => $text,
		'link' => $link,
	);
}

function enlightenment_bp_notifications_heartbeat_script( $deps ) {
	$deps[] = 'heartbeat';

	return $deps;
}
add_filter( 'enlightenment_buddypress_script_deps', 'enlightenment_bp_notifications_heartbeat_script' );

function enlightenment_bp_notifications_heartbeat_where_conditions( $where_conditions ) {
	if( ! doing_action( 'heartbeat_received' ) ) {
		return $where_conditions;
	}

	$where_conditions['date_query'] = str_replace( 'date_recorded', 'date_notified', $where_conditions['date_query'] );

	return $where_conditions;
}
add_filter( 'bp_notifications_get_where_conditions', 'enlightenment_bp_notifications_heartbeat_where_conditions' );

function enlightenment_bp_notifications_heartbeat_last_recorded( $response = array(), $data = array() ) {
	if( empty( $data['bp_notifications_last_recorded'] ) ) {
		return $response;
	}

	$components = bp_notifications_get_registered_components();
	$callbacks  = enlightenment_bp_header_notification_callbacks();

	$response['bp_notifications_newest_notifications'] = array(
		'all'      => array(),
		'friends'  => array(),
		'messages' => array(),
		'other'    => array(),
	);

	if( ! empty( $data['bp_notifications_last_recorded']['friends'] ) ) {
		$newest        = array();
		$last_recorded = 0;
		$items         = '';

		$notifications = BP_Notifications_Notification::get( array(
			'order_by'   => 'date_notified',
			'sort_order' => 'DESC',
			'user_id'    => bp_loggedin_user_id(),
			'component_name' => 'friends',
			'date_query' => array(
				array(
					'after'  => date( 'Y-m-d H:i:s', $data['bp_notifications_last_recorded']['friends'] ),
				),
			),
		) );

		foreach( $notifications as $notification ) {
			// Skip if group is empty.
			if ( empty( $notification->component_action ) ) {
				continue;
			}

			$component_name = $notification->component_name;

			$time = strtotime( $notification->date_notified );
			if( $last_recorded < $time ) {
				$last_recorded = $time;
			}

			$output = '';

			if( isset( $callbacks[$component_name] ) && is_callable( $callbacks[$component_name] ) ) {
				// Retrieve the content of the notification using the callback.
				$content = call_user_func(
					$callbacks[$component_name],
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'array'
				);

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'div', 'notification-content' );
				$output .= $content['text'];

				$output .= enlightenment_open_tag( 'a', 'time-since', '', array(
					'href'               => $content['link'],
					'data-date-notified' => strtotime( $notification->date_notified ),
				) );
				$output .= bp_core_time_since( $notification->date_notified );
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'div' );
				$output .= enlightenment_close_tag( 'li' );
			} elseif ( isset( $bp->{$component_name}->notification_callback ) && is_callable( $bp->{$component_name}->notification_callback ) ) {
				// Retrieve the content of the notification using the callback.
				$content = call_user_func(
					$bp->{$component_name}->notification_callback,
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'array'
				);

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'a', '', '', array(
					'title'  => $content['text'],
					'href'   => $content['link'],
				) );
				$output .= $notification->content;
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'li' );
			// Allow non BuddyPress components to hook in.
			} else {

				// The array to reference with apply_filters_ref_array().
				$ref_array = array(
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'object'
				);

				$content = apply_filters_ref_array( 'bp_notifications_get_notifications_for_user', $ref_array );

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'a', '', '', array(
					'title'  => $content['text'],
					'href'   => $content['link'],
				) );
				$output .= $notification->content;
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'li' );
			}

			$items .= $output;

			$response['bp_notifications_newest_notifications']['all'][$time] = array(
				'component' => 'friends',
				'content'   => $output,
			);
		}

		$newest['contents']      = $items;
		$newest['last_recorded'] = $last_recorded;

		if( ! empty( $newest['last_recorded'] ) ) {
			$response['bp_notifications_newest_notifications']['friends'] = $newest;
		}
	}

	// Message Notifications
	$newest        = array();
	$last_recorded = 0;
	$items         = '';

	$query = BP_Messages_Thread::get_current_threads_for_user( array(
		'user_id' => bp_loggedin_user_id(),
		'box'     => 'inbox',
		'type'    => 'unread',
	) );

	if( $query ) {
		$threads = $query['threads'];

		foreach( $threads as $thread ) {
			$last_sender_id = $thread->last_sender_id;

			if( $thread->last_sender_id == bp_loggedin_user_id() ) {
				$thread->messages = array_reverse( $thread->messages );

				foreach( $thread->messages as $message ) {
					if( $message->sender_id != bp_loggedin_user_id() ) {
						$thread->last_message_id      = $message->id;
						$thread->last_message_date    = $message->date_sent;
						$thread->last_sender_id       = $message->sender_id;
						$thread->last_message_subject = $message->subject;
						//$thread->last_message_content = $message->message;

						break;
					}
				}
			}

			$time = strtotime( $thread->last_message_date );
			if( $last_recorded < $time ) {
				$last_recorded = $time;
			}

			$user = get_userdata( $thread->last_sender_id );

			$avatar = bp_core_fetch_avatar( array(
				'item_id' => $user->ID,
				'type'    => 'thumb',
				'width'   => 96,
				'height'  => 96,
				'email'   => $user->user_email,
			) );

			$permalink    = bp_core_get_userlink( $user->ID );
			$chat_icon    = count( $thread->recipients ) > 2 ? '<i class="fa fa-users"></i>' : '';
			$unread_count = $thread->unread_count ? sprintf( '<span class="unread-count">(%s)</span>', $thread->unread_count ) : '';
			$status_icon  = $last_sender_id == bp_loggedin_user_id() ? ( $thread->recipients[(int) $thread->last_sender_id]->unread_count ? '<i class="fa fa-reply"></i>' : '<i class="fa fa-check"></i>' ) : '';
			$status_icon  = sprintf( '<span class="status-icon">%s</span>', $status_icon );

			$subject = apply_filters( 'bp_get_message_thread_subject', $thread->last_message_subject );
			$subject = sprintf( '<span class="subject">%s</span>', $subject );

			$content = apply_filters( 'bp_get_the_thread_message_content', $thread->last_message_content );
			$content = wp_filter_nohtml_kses( $content );
			$content = sprintf( '<span class="last-message-content">&ldquo;%s&rdquo;</span>', $content );

			$output  = enlightenment_open_tag( 'li', sprintf( 'notification message-thread-notification dropdown-item%s', $thread->unread_count ? ' unread' : '' ), 'message-thread-notification-' . $thread->thread_id );
			$output .= enlightenment_open_tag( 'div', 'notification-content' );

			$output .= sprintf( '%1$s %2$s %3$s %4$s %5$s %6$s %7$s', $avatar, $permalink, $chat_icon, $unread_count, $status_icon, $subject, $content );

			$output .= enlightenment_open_tag( 'a', 'time-since', '', array(
				'href'               => bp_get_message_thread_view_link( $thread->thread_id ),
				'data-date-notified' => strtotime( $thread->last_message_date ),
			) );
			$output .= bp_core_time_since( $thread->last_message_date );
			$output .= enlightenment_close_tag( 'a' );
			$output .= enlightenment_close_tag( 'div' );
			$output .= enlightenment_close_tag( 'li' );

			$items .= $output;

			$response['bp_notifications_newest_notifications']['all'][$time] = array(
				'component' => 'messages',
				'content'   => $output,
			);
		}

		$newest['contents']      = $items;
		$newest['last_recorded'] = $last_recorded;

		if( ! empty( $newest['last_recorded'] ) ) {
			$response['bp_notifications_newest_notifications']['messages'] = $newest;
		}
	}

	/*if( ! empty( $data['bp_notifications_last_recorded']['messages'] ) ) {
		$newest        = array();
		$last_recorded = 0;
		$items         = '';

		$notifications = BP_Notifications_Notification::get( array(
			'order_by'   => 'date_notified',
			'sort_order' => 'DESC',
			'user_id'    => bp_loggedin_user_id(),
			'component_name' => 'messages',
			'date_query' => array(
				array(
					'after'  => date( 'Y-m-d H:i:s', $data['bp_notifications_last_recorded']['messages'] ),
				),
			),
		) );

		foreach( $notifications as $notification ) {
			// Skip if group is empty.
			if ( empty( $notification->component_action ) ) {
				continue;
			}

			$component_name = $notification->component_name;

			// We prefer that extended profile component-related notifications use
			// the component_name of 'xprofile'. However, the extended profile child
			// object in the $bp object is keyed as 'profile', which is where we need
			// to look for the registered notification callback.
			if ( 'xprofile' == $component_name ) {
				$component_name = 'profile';
			}

			$time = strtotime( $notification->date_notified );
			if( $last_recorded < $time ) {
				$last_recorded = $time;
			}

			$output = '';

			if( isset( $callbacks[$component_name] ) && is_callable( $callbacks[$component_name] ) ) {
				// Retrieve the content of the notification using the callback.
				$content = call_user_func(
					$callbacks[$component_name],
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'array'
				);

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'div', 'notification-content' );
				$output .= $content['text'];

				$output .= enlightenment_open_tag( 'a', 'time-since', '', array(
					'href'               => $content['link'],
					'data-date-notified' => strtotime( $notification->date_notified ),
				) );
				$output .= bp_core_time_since( $notification->date_notified );
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'div' );
				$output .= enlightenment_close_tag( 'li' );
			} elseif ( isset( $bp->{$component_name}->notification_callback ) && is_callable( $bp->{$component_name}->notification_callback ) ) {
				// Retrieve the content of the notification using the callback.
				$content = call_user_func(
					$bp->{$component_name}->notification_callback,
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'array'
				);

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'a', '', '', array(
					'title'  => $content['text'],
					'href'   => $content['link'],
				) );
				$output .= $notification->content;
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'li' );
			// Allow non BuddyPress components to hook in.
			} else {

				// The array to reference with apply_filters_ref_array().
				$ref_array = array(
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'object'
				);

				$content = apply_filters_ref_array( 'bp_notifications_get_notifications_for_user', $ref_array );

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'a', '', '', array(
					'title'  => $content['text'],
					'href'   => $content['link'],
				) );
				$output .= $notification->content;
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'li' );
			}

			$items .= $output;

			$response['bp_notifications_newest_notifications']['all'][$time] = array(
				'component' => 'messages',
				'content'   => $output,
			);
		}

		$newest['contents']      = $items;
		$newest['last_recorded'] = $last_recorded;

		if( ! empty( $newest['last_recorded'] ) ) {
			$response['bp_notifications_newest_notifications']['messages'] = $newest;
		}
	}*/

	if( ! empty( $data['bp_notifications_last_recorded']['other'] ) ) {
		$newest        = array();
		$last_recorded = 0;
		$items         = '';

		$notifications = BP_Notifications_Notification::get( array(
			'order_by'   => 'date_notified',
			'sort_order' => 'DESC',
			'user_id'    => bp_loggedin_user_id(),
			'component_name' => array_diff( $components, array( 'friends', 'messages' ) ),
			'date_query' => array(
				array(
					'after'  => date( 'Y-m-d H:i:s', $data['bp_notifications_last_recorded']['other'] ),
				),
			),
		) );

		foreach( $notifications as $notification ) {
			// Skip if group is empty.
			if ( empty( $notification->component_action ) ) {
				continue;
			}

			$component_name = $notification->component_name;

			// We prefer that extended profile component-related notifications use
			// the component_name of 'xprofile'. However, the extended profile child
			// object in the $bp object is keyed as 'profile', which is where we need
			// to look for the registered notification callback.
			if ( 'xprofile' == $component_name ) {
				$component_name = 'profile';
			}

			$time = strtotime( $notification->date_notified );
			if( $last_recorded < $time ) {
				$last_recorded = $time;
			}

			$output = '';

			if( isset( $callbacks[$component_name] ) && is_callable( $callbacks[$component_name] ) ) {
				// Retrieve the content of the notification using the callback.
				$content = call_user_func(
					$callbacks[$component_name],
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'array'
				);

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'div', 'notification-content' );
				$output .= $content['text'];

				$output .= enlightenment_open_tag( 'a', 'time-since', '', array(
					'href'               => $content['link'],
					'data-date-notified' => strtotime( $notification->date_notified ),
				) );
				$output .= bp_core_time_since( $notification->date_notified );
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'div' );
				$output .= enlightenment_close_tag( 'li' );
			} elseif ( isset( $bp->{$component_name}->notification_callback ) && is_callable( $bp->{$component_name}->notification_callback ) ) {
				// Retrieve the content of the notification using the callback.
				$content = call_user_func(
					$bp->{$component_name}->notification_callback,
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'array'
				);

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'a', '', '', array(
					'title'  => $content['text'],
					'href'   => $content['link'],
				) );
				$output .= $notification->content;
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'li' );
			// Allow non BuddyPress components to hook in.
			} else {

				// The array to reference with apply_filters_ref_array().
				$ref_array = array(
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'object'
				);

				$content = apply_filters_ref_array( 'bp_notifications_get_notifications_for_user', $ref_array );

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'a', '', '', array(
					'title'  => $content['text'],
					'href'   => $content['link'],
				) );
				$output .= $notification->content;
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'li' );
			}

			$items .= $output;

			$response['bp_notifications_newest_notifications']['all'][$time] = array(
				'component' => 'other',
				'content'   => $output,
			);
		}

		$newest['contents']      = $items;
		$newest['last_recorded'] = $last_recorded;

		if( ! empty( $newest['last_recorded'] ) ) {
			$response['bp_notifications_newest_notifications']['other'] = $newest;
		}
	}

	/*foreach( $data['bp_notifications_last_recorded'] as $type => $last_recorded ) {
		if( empty( $last_recorded ) ) {
			continue;
		}

		// Use the querystring argument stored in the cookie (to preserve
		// filters), but force the offset to get only new items.
		$notifications_latest_args = array( 'date_query' => array(
			array(
				'after' => date( 'Y-m-d H:i:s', $last_recorded ),
			),
		) );

		$newest_notifications = array();
		$last_notification_recorded = 0;
		$items = '';

		if( 'friends' == $type ) {
			$component = 'friends';
		} elseif( 'messages' == $type ) {
			$component = 'messages';
		} else {
			$component = array_diff( $components, array( 'friends', 'messages' ) );
		}

		$notifications = BP_Notifications_Notification::get( array(
			'order_by'   => 'date_notified',
			'sort_order' => 'DESC',
			'user_id'    => bp_loggedin_user_id(),
			'component_name' => $component,
			'date_query' => array(
				array(
					'after'  => date( 'Y-m-d H:i:s', $last_recorded ),
					//'column' => 'date_notified',
				),
			),
		) );

		foreach( $notifications as $notification ) {
			//$notification = $bp->notifications->query_loop->notification;

			$component_name = $notification->component_name;

			// We prefer that extended profile component-related notifications use
			// the component_name of 'xprofile'. However, the extended profile child
			// object in the $bp object is keyed as 'profile', which is where we need
			// to look for the registered notification callback.
			if ( 'xprofile' == $component_name ) {
				$component_name = 'profile';
			}

			// Skip if group is empty.
			if ( empty( $notification->component_action ) ) {
				continue;
			}

			$time = strtotime( $notification->date_notified );
			if( $last_notification_recorded < $time ) {
				$last_notification_recorded = $time;
			}

			$output = '';

			if ( 'messages' == $component_name] ) {

			// Callback function exists.
			} elseif ( isset( $callbacks[$component_name] ) && is_callable( $callbacks[$component_name] ) ) {
				// Retrieve the content of the notification using the callback.
				$content = call_user_func(
					$callbacks[$component_name],
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'array'
				);

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'div', 'notification-content' );
				$output .= $content['text'];

				$output .= enlightenment_open_tag( 'a', 'time-since', '', array(
					'href'               => $content['link'],
					'data-date-notified' => strtotime( $notification->date_notified ),
				) );
				$output .= bp_core_time_since( $notification->date_notified );
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'div' );
				$output .= enlightenment_close_tag( 'li' );
			} elseif ( isset( $bp->{$component_name}->notification_callback ) && is_callable( $bp->{$component_name}->notification_callback ) ) {
				// Retrieve the content of the notification using the callback.
				$content = call_user_func(
					$bp->{$component_name}->notification_callback,
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'array'
				);

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'a', '', '', array(
					'title'  => $content['text'],
					'href'   => $content['link'],
				) );
				$output .= $notification->content;
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'li' );
			// Allow non BuddyPress components to hook in.
			} else {

				// The array to reference with apply_filters_ref_array().
				$ref_array = array(
					$notification->component_action,
					$notification->item_id,
					$notification->secondary_item_id,
					1,
					'object'
				);

				$content = apply_filters_ref_array( 'bp_notifications_get_notifications_for_user', $ref_array );

				$output .= enlightenment_open_tag( 'li', 'notification dropdown-item', 'notification-' . $notification->id );
				$output .= enlightenment_open_tag( 'a', '', '', array(
					'title'  => $content['text'],
					'href'   => $content['link'],
				) );
				$output .= $notification->content;
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( 'li' );
			}

			$items .= $output;

			$response['bp_notifications_newest_notifications']['all'][$time] = $output;
		}

		$newest_notifications['contents']      = $items;
		$newest_notifications['last_recorded'] = $last_notification_recorded;

		if( ! empty( $newest_notifications['last_recorded'] ) ) {
			$response['bp_notifications_newest_notifications'][$type] = $newest_notifications;
		}
	}*/

	krsort( $response['bp_notifications_newest_notifications']['all'] );

	$response['bp_notifications_newest_notifications']['all'] = array_values( $response['bp_notifications_newest_notifications']['all'] );

	return $response;
}
add_filter( 'heartbeat_received', 'enlightenment_bp_notifications_heartbeat_last_recorded', 10, 2 );

remove_action( 'messages_message_sent', 'bp_messages_message_sent_add_notification' );

/**
 * Send notifications to message recipients.
 *
 * @since 1.9.0
 *
 * @param BP_Messages_Message $message Message object.
 */
function enlightenment_bp_messages_message_sent_add_notification( $message ) {
	if ( bp_is_active( 'notifications' ) && ! empty( $message->recipients ) ) {
		foreach ( (array) $message->recipients as $recipient ) {
			bp_notifications_add_notification( array(
				'user_id'           => $recipient->user_id,
				'item_id'           => $message->id,
				'secondary_item_id' => $message->sender_id,
				'component_name'    => buddypress()->messages->id,
				'component_action'  => 'new_message',
				'date_notified'     => $message->date_sent,
				'is_new'            => 1,
			) );
		}
	}
}
//add_action( 'messages_message_sent', 'enlightenment_bp_messages_message_sent_add_notification' );
