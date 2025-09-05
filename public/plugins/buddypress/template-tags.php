<?php

/**
 * Header Account Login Dropdown.
 *
 * @since 1.0.0
 */
function enlightenment_bp_header_account_login( $args = null ) {
	$bp = buddypress();

	$defaults = array(
		'container'                     => 'nav',
		'container_class'               => 'header-account-login',
		'container_id'                  => 'header-account-login',
		'toggle_tag'                    => 'a',
		'toggle_class'                  => 'header-account-login-toggle',
		'toggle_id'                     => 'header-account-login-toggle',
		'toggle_extra_atts'             => array(
			'href'          => $bp->loggedin_user->domain,
			'aria-haspopup' => 'true',
			'aria-expanded' => 'false',
		),
		'avatar_tag'                    => 'span',
		'avatar_class'                  => 'user-avatar',
		'avatar_size'                   => 36,
		'label_tag'                     => 'span',
		'label_class'                   => 'user-greeting',
		'label'                         => is_user_logged_in() ? $bp->loggedin_user->fullname : __( 'Welcome, Guest', 'enlightenment' ),
		'log_out_label'                 => __( 'Log out', 'enlightenment' ),
		'register_label'                => __( 'Register', 'enlightenment' ),
		'lostpass_label'                => __( 'Lost Password', 'enlightenment' ),
		'dropdown_menu_tag'             => 'ul',
		'dropdown_menu_class'           => 'header-account-login-menu',
		'dropdown_menu_id'              => 'header-account-login-menu',
		'dropdown_menu_extra_atts'      => array(),
		'dropdown_item_tag'             => 'li',
		'dropdown_item_class'           => '',
		'dropdown_item_parent_class'    => 'has-children',
		'dropdown_link_class'           => '',
		'dropdown_link_active_class'    => '',
		'dropdown_submenu_tag'          => 'ul',
		'dropdown_submenu_class'        => '',
		'dropdown_submenu_extra_atts'   => array(),
		'dropdown_subitem_tag'          => 'li',
		'dropdown_subitem_class'        => '',
		'echo'                          => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_header_account_login_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( ! empty( $args['toggle_id'] ) ) {
		$args['dropdown_menu_extra_atts']['aria-labelledby'] = $args['toggle_id'];
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	$output .= enlightenment_open_tag( $args['toggle_tag'], $args['toggle_class'], $args['toggle_id'], $args['toggle_extra_atts'] );

	if ( is_user_logged_in() ) {
		$output .= sprintf( '<span class="user-avatar">%s</span>', bp_core_fetch_avatar( array(
			'item_id'    => $bp->loggedin_user->id,
			'width'      => $args['avatar_size'],
			'height'     => $args['avatar_size'],

		) ) );

		$output .= sprintf( '<span class="user-greeting">%s</span>', $bp->loggedin_user->fullname );
	} else {
		$avatar = get_avatar( 'email@example.com', $args['avatar_size'], get_option( 'avatar_default', 'mystery' ) );
		$avatar = preg_replace( "/src='(.+?)'/", "src='\$1&amp;forcedefault=1'", $avatar );
		$avatar = preg_replace( "/srcset='(.+?) 2x'/", "srcset='\$1&amp;forcedefault=1 2x'", $avatar );

		$output .= sprintf( '<span class="user-avatar">%s</span>', $avatar );

		$output .= sprintf( '<span class="user-greeting">%s</span>', __( 'Welcome, Guest', 'enlightenment' ) );
	}

	$output .= enlightenment_close_tag( 'a' );

	$output .= enlightenment_open_tag( $args['dropdown_menu_tag'], $args['dropdown_menu_class'], $args['dropdown_menu_id'], $args['dropdown_menu_extra_atts'] );

	if ( is_user_logged_in() ) {
		$nav = buddypress()->members->nav->get_primary();

		foreach ( $nav as $nav_item ) {
			if ( 'media' == $nav_item->slug && class_exists( 'RTMedia' ) ) {
				global $rtmedia;

				$has_children = ! empty( $rtmedia->allowed_types ) || is_rtmedia_album_enable();
				$item_class   = $args['dropdown_item_class'];
				$link_class   = $args['dropdown_link_class'];
				$link_label   = preg_replace( ';<span(.*)>(.*)</span>;', '', $nav_item->name );

				if ( bp_displayed_user_domain() ) {
					$link_href = str_replace( bp_displayed_user_domain(), bp_loggedin_user_domain(), $nav_item->link );
				}

				$link_extra_atts = array(
					'href'  => $link_href,
					'title' => wp_kses( $link_label, 'strip' ),
				);

				if ( $has_children ) {
					$item_class .= ' ' . trim( $args['dropdown_item_parent_class'] );
				}

				if (
					isset( buddypress()->canonical_stack['canonical_url'] )
					&&
					$link_href == buddypress()->canonical_stack['canonical_url']
				) {
					$link_class .= ' ' . trim( $args['dropdown_link_active_class'] );

					$link_extra_atts['aria-current'] = 'page';
				}

				$output .= enlightenment_open_tag( $args['dropdown_item_tag'], $item_class );
				$output .= enlightenment_open_tag( 'a', $link_class, '',$link_extra_atts  );
				$output .= esc_html( $link_label );
				$output .= enlightenment_close_tag( 'a' );

				$output .= enlightenment_open_tag( $args['dropdown_submenu_tag'], $args['dropdown_submenu_class'] );

				if ( is_rtmedia_album_enable () ) {
					$link_href       = trailingslashit ( get_rtmedia_user_link ( get_current_user_id () ) ) . RTMEDIA_MEDIA_SLUG . '/album/';
					$link_class      = $args['dropdown_link_class'];
					$link_label      = RTMEDIA_ALBUM_PLURAL_LABEL;
					$link_extra_atts = array(
						'href'  => $link_href,
						'title' => wp_kses( $link_label, 'strip' ),
					);

					if (
						isset( buddypress()->canonical_stack['canonical_url'] )
						&&
						$link_href == buddypress()->canonical_stack['canonical_url']
					) {
						$link_class .= ' ' . trim( $args['dropdown_link_active_class'] );

						$link_extra_atts['aria-current'] = 'page';
					}

					$output .= enlightenment_open_tag( $args['dropdown_subitem_tag'], $args['dropdown_subitem_class'] );
					$output .= enlightenment_open_tag( 'a', $link_class, '', $link_extra_atts );
					$output .= esc_html( $link_label );
					$output .= enlightenment_close_tag( 'a' );
					$output .= enlightenment_close_tag( $args['dropdown_subitem_tag'] );
				}

				foreach ( $rtmedia->allowed_types as $type ) {
					if ( isset( $rtmedia->options[ 'allowedTypes_' . $type[ 'name' ] . '_enabled' ] ) ) {
						if ( ! $rtmedia->options[ 'allowedTypes_' . $type[ 'name' ] . '_enabled' ] ) {
							continue;
						}

						$link_href       = trailingslashit ( get_rtmedia_user_link ( get_current_user_id () ) ) . RTMEDIA_MEDIA_SLUG . '/' . constant ( 'RTMEDIA_' . strtoupper( $type[ 'name' ] ) . '_SLUG' ) . '/';
						$link_class      = $args['dropdown_link_class'];
						$link_label      = $type[ 'plural_label' ];
						$link_extra_atts = array(
							'href'  => $link_href,
							'title' => wp_kses( $link_label, 'strip' ),
						);

						if (
							isset( buddypress()->canonical_stack['canonical_url'] )
							&&
							$link_href == buddypress()->canonical_stack['canonical_url']
						) {
							$link_class .= ' ' . trim( $args['dropdown_link_active_class'] );

							$link_extra_atts['aria-current'] = 'page';
						}

						$output .= enlightenment_open_tag( $args['dropdown_subitem_tag'], $args['dropdown_subitem_class'] );
						$output .= enlightenment_open_tag( 'a', $link_class, '', $link_extra_atts );
						$output .= esc_html( $link_label );
						$output .= enlightenment_close_tag( 'a' );
						$output .= enlightenment_close_tag( $args['dropdown_subitem_tag'] );
					}
				}

				$output .= enlightenment_close_tag( $args['dropdown_submenu_tag'] );
			} else {
				$children = buddypress()->members->nav->get_secondary( array(
					'parent_slug' => $nav_item->slug,
				) );

				$has_children    = ! empty( $children );
				$link_href       = $nav_item->link;
				$item_class      = $args['dropdown_item_class'];
				$link_class      = $args['dropdown_link_class'];
				$link_label      = preg_replace( ';<span(.*)>(.*)</span>;', '', $nav_item->name );
				$link_extra_atts = array(
					'href'  => $link_href,
					'title' => wp_kses( $link_label, 'strip' ),
				);

				if ( $has_children ) {
					$item_class .= ' ' . trim( $args['dropdown_item_parent_class'] );
				}

				if (
					isset( buddypress()->canonical_stack['canonical_url'] )
					&&
					$link_href == buddypress()->canonical_stack['canonical_url']
				) {
					$link_class .= ' ' . trim( $args['dropdown_link_active_class'] );

					$link_extra_atts['aria-current'] = 'page';
				}

				if ( bp_displayed_user_domain() ) {
					$link_href = str_replace( bp_displayed_user_domain(), bp_loggedin_user_domain(), $nav_item->link );

					$link_extra_atts['href'] = esc_url( $link_href );
				}

				$output .= enlightenment_open_tag( $args['dropdown_item_tag'], $item_class );

				$output .= enlightenment_open_tag( 'a', $link_class, '', $link_extra_atts );
				$output .= esc_html( $link_label );
				$output .= enlightenment_close_tag( 'a' );

				if ( $has_children ) {
					$output .= enlightenment_open_tag( $args['dropdown_submenu_tag'], $args['dropdown_submenu_class'] );

					foreach ( $children as $subnav_item ) {
						$link_href       = $subnav_item->link;
						$link_class      = $args['dropdown_link_class'];
						$link_label      = $subnav_item->name;
						$link_extra_atts = array(
							'href'  => esc_url( $link_href ),
							'title' => wp_kses( $link_label, 'strip' ),
						);

						if (
							isset( buddypress()->canonical_stack['canonical_url'] )
							&&
							$subnav_item->link == buddypress()->canonical_stack['canonical_url']
						) {
							$link_class .= ' ' . trim( $args['dropdown_link_active_class'] );

							$link_extra_atts['aria-current'] = 'page';
						}

						if ( bp_displayed_user_domain() ) {
							$link_href = str_replace( bp_displayed_user_domain(), bp_loggedin_user_domain(), $subnav_item->link );

							$link_extra_atts['href'] = esc_url( $link_href );
						}

						if ( isset( $bp->displayed_user->userdata->user_login ) ) {
							$link_label = str_replace( $bp->displayed_user->userdata->user_login, $bp->loggedin_user->userdata->user_login, $subnav_item->name );
						}

						$output .= enlightenment_open_tag( $args['dropdown_subitem_tag'], $args['dropdown_subitem_class'] );
						$output .= enlightenment_open_tag( 'a', $link_class, '', $link_extra_atts );
						$output .= esc_html( $link_label );
						$output .= enlightenment_close_tag( 'a' );
						$output .= enlightenment_close_tag( $args['dropdown_subitem_tag'] );
					}

					$output .= enlightenment_close_tag( $args['dropdown_submenu_tag'] );
				}
			}

			$output .= enlightenment_close_tag( $args['dropdown_item_tag'] );
		}

		$output .= enlightenment_open_tag( $args['dropdown_item_tag'], $args['dropdown_item_class'] );
		$output .= enlightenment_open_tag( 'a', $args['dropdown_link_class'], '', array(
			'href'  => esc_url( wp_logout_url( bp_get_requested_url() ) ),
			'title' => $args['log_out_label'],
		) );
		$output .= esc_html( $args['log_out_label'] );
		$output .= enlightenment_close_tag( 'a' );
		$output .= enlightenment_close_tag( $args['dropdown_item_tag'] );
	} else {
		$output .= enlightenment_open_tag( $args['dropdown_item_tag'] );

		if ( function_exists( 'bbp_wp_login_action' ) ) {
			ob_start();
			bbp_get_template_part( 'form', 'user-login' );
			$form = ob_get_clean();
		} else {
			$form  = enlightenment_open_tag( 'form', 'bbp-login-form', '', array(
				'action' => wp_login_url( bp_get_requested_url() ),
				'method' => 'post',
			) );
			$form .= enlightenment_open_tag( 'fieldset', 'bbp-form' );

			$form .= sprintf( '<legend>%s</legend>', __( 'Log In', 'enlightenment' ) );

			$form .= enlightenment_open_tag( 'div', 'bbp-username' );
			$form .= sprintf( '<label for="user_login">%s: </label>', __( 'Username', 'enlightenment' ) );
			$form .= sprintf( '<input type="text" name="log" value="%s" size="20" id="user_login" />', ( empty( $_REQUEST['user_login'] ) ? '' : esc_attr( stripslashes( $_REQUEST['user_login'] ) ) ) );
			$form .= enlightenment_close_tag( 'div' );

			$form .= enlightenment_open_tag( 'div', 'bbp-password' );
			$form .= sprintf( '<label for="user_pass">%s: </label>', __( 'Password', 'enlightenment' ) );
			$form .= sprintf( '<input type="password" name="pwd" value="%s" size="20" id="user_pass" autocomplete="off" />', ( empty( $_REQUEST['user_pass'] ) ? '' : esc_attr( $_REQUEST['user_pass'] ) ) );
			$form .= enlightenment_close_tag( 'div' );

			$form .= enlightenment_open_tag( 'div', 'bbp-remember-me' );
			$form .= sprintf( '<input type="checkbox" name="rememberme" value="forever" %s id="rememberme" /> ', checked( ( empty( $_REQUEST['rememberme'] ) ? false : esc_attr( $_REQUEST['rememberme'] ) ), true, false ) );
			$form .= sprintf( '<label for="rememberme">%s</label>', __( 'Keep me signed in', 'enlightenment' ) );
			$form .= enlightenment_close_tag( 'div' );

			ob_start();
			do_action( 'login_form' );
			$form .= ob_get_clean();

			$form .= enlightenment_open_tag( 'div', 'bbp-submit-wrapper' );
			$form .= sprintf( '<button type="submit" name="user-submit" class="button submit user-submit">%s</button>', __( 'Log In', 'enlightenment' ) );
			$form .= '<input type="hidden" name="user-cookie" value="1" />';
			$form .= sprintf( '<input type="hidden" id="bbp_redirect_to" name="redirect_to" value="%s" />', esc_url( remove_query_arg( 'loggedout', ( isset( $_SERVER['REQUEST_URI'] ) ? enlightenment_get_current_uri() : wp_get_referer() ) ) ) );
			$form .= wp_nonce_field( 'bbp-user-login', '_wpnonce', true, false );
			$form .= enlightenment_close_tag( 'div' );

			$form .= enlightenment_close_tag( 'fieldset' );
			$form .= enlightenment_close_tag( 'form' );
		}

		$output .= apply_filters( 'enlightenment_bbp_login_form', $form );

		$output .= enlightenment_close_tag( $args['dropdown_item_tag'] );

		if ( get_option( 'users_can_register' ) ) {
			$output .= enlightenment_open_tag( $args['dropdown_item_tag'] );
			$output .= enlightenment_open_tag( 'a', $args['dropdown_link_class'], '', array(
				'href'  => esc_url( bp_get_signup_page() ),
				'title' => $args['register_label'],
			) );
			$output .= esc_html( $args['register_label'] );
			$output .= enlightenment_close_tag( 'a' );
			$output .= enlightenment_close_tag( $args['dropdown_item_tag'] );
		}

		$output .= enlightenment_open_tag( $args['dropdown_item_tag'] );
		$output .= enlightenment_open_tag( 'a', $args['dropdown_link_class'], '', array(
			'href'  => esc_url( wp_lostpassword_url() ),
			'title' => $args['lostpass_label'],
		) );
		$output .= esc_html( $args['lostpass_label'] );
		$output .= enlightenment_close_tag( 'a' );
		$output .= enlightenment_close_tag( $args['dropdown_item_tag'] );
	}

	$output .= enlightenment_close_tag( $args['dropdown_menu_tag'] );

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_header_account_login', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

/**
 * Build the "Notifications" dropdown.
 *
 * @since 1.0.0
 */
function enlightenment_bp_notifications( $args = null ) {
	if ( ! is_user_logged_in() ) {
		return false;
	}

	if ( ! bp_is_active( 'notifications' ) ) {
		return false;
	}

	$defaults = array(
		'container'                     => 'nav',
		'container_class'               => 'bp-notifications',
		'container_id'                  => 'bp-notifications',
		'dropdown_tag'                  => 'nav',
		'dropdown_class'                => 'bp-%s-notifications',
		'dropdown_id'                   => 'bp-%s-notifications',
		'toggle_tag'                    => 'a',
		'toggle_class'                  => '',
		'toggle_id'                     => 'bp-%s-notifications-toggle',
		'toggle_extra_atts'             => array(
			'aria-haspopup' => 'true',
			'aria-expanded' => 'false',
		),
		'alert_tag'                     => 'span',
		'alert_count_class'             => 'pending-count alert',
		'alert_no_count_class'          => 'count no-alert',
		'alert_id'                      => '%s-pending-notifications',
		'dropdown_menu_tag'             => 'div',
		'dropdown_menu_class'           => '',
		'dropdown_menu_id'              => 'bp-%s-notifications-list',
		'dropdown_menu_extra_atts'      => array(
			'aria-labelledby' => 'bp-%s-notifications-toggle',
		),
		'notifications_list_tag'        => 'ul',
		'notifications_list_class'      => 'notifications-list',
		'notification_tag'              => 'li',
		'notification_class'            => 'notification notification-component-%s',
		'notification_id'               => 'notification-%d',
		'notification_content_tag'      => 'div',
		'notification_content_class'    => 'notification-content',
		'notification_time_since_class' => 'time-since',
		'no_notifications_tag'          => 'li',
		'no_notifications_class'        => 'notification no-notifications',
		'no_notifications_id'           => 'no-%s-notifications',
		'no_notifications_link_class'   => '',
		'echo'                          => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_notifications_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	// Setup local variables.
	$bp = buddypress();

	// Get notifications (out of the cache, or query if necessary).
	$notifications = array_reverse( bp_notifications_get_all_notifications_for_user( bp_loggedin_user_id() ) );

	$callbacks = enlightenment_bp_header_notification_callbacks();

	$renderable = array(
		'friends'  => array(
			'friendship_request'  => array(),
			'friendship_accepted' => array(),
		),
		'other'    => array(),
	); // Renderable notifications.

	// Calculate a renderable output for each notification type.
	foreach ( $notifications as $notification ) {
		$component_name = $notification->component_name;

		// We prefer that extended profile component-related notifications use
		// the component_name of 'xprofile'. However, the extended profile child
		// object in the $bp object is keyed as 'profile', which is where we need
		// to look for the registered notification callback.
		if ( 'xprofile' == $component_name ) {
			$component_name = 'profile';
		}

		// Skip if group is empty.
		if ( empty( $notification->component_action ) || 'messages' == $component_name ) {
			continue;
		}

		// Callback function exists.
		if ( isset( $callbacks[$component_name] ) && is_callable( $callbacks[$component_name] ) ) {
			// Retrieve the content of the notification using the callback.
			$content = call_user_func(
				$callbacks[$component_name],
				$notification->component_action,
				$notification->item_id,
				$notification->secondary_item_id,
				1,
				'array'
			);
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
		// Allow non BuddyPress components to hook in.
		} else {

			// The array to reference with apply_filters_ref_array().
			$ref_array = array(
				$notification->component_action,
				$notification->item_id,
				$notification->secondary_item_id,
				1,
				'object',
				$notification->component_action, // Duplicated so plugins can check the canonical action name.
				$component_name,
				$notification->id,
			);

			/**
			 * Filters the notifications for a user.
			 *
			 * @since 1.9.0
			 *
			 * @param array $ref_array Array of properties for the current notification being rendered.
			 */
			$content = apply_filters_ref_array( 'bp_notifications_get_notifications_for_user', $ref_array );
		}

		// Minimal backpat with non-compatible notification callback functions.
		if ( is_string( $content ) ) {
			$content = array(
				'text' => $content,
				'link' => bp_loggedin_user_domain(),
			);
		}

		$notification->content = $content['text'];
		$notification->href    = $content['link'];

		if ( 'friends' == $component_name ) {
			$renderable['friends'][$notification->component_action][] = $notification;
		} else {
			$renderable['other'][] = $notification;
		}
	}

	$renderable['friends'] = array_merge( $renderable['friends']['friendship_request'], $renderable['friends']['friendship_accepted'] );

	// If renderable is empty array, set to false.
	if ( empty( $renderable['friends'] ) ) {
		$renderable['friends'] = false;
	}

	if ( empty( $renderable['messages'] ) ) {
		$renderable['messages'] = false;
	}

	if ( empty( $renderable['other'] ) ) {
		$renderable['other'] = false;
	}

	$output = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	/**
	 * Friends Notifications
	 */
	$notifications = $renderable['friends'];
	$menu_link     = trailingslashit( bp_loggedin_user_domain() . bp_get_friends_slug() ) . 'requests/';
	$count         = ! empty( $notifications ) ? count( $notifications ) : 0;
	$toggle_atts   = array_merge( array(
		'href' => $menu_link,
	), $args['toggle_extra_atts'] );
	$dropdown_atts = $args['dropdown_menu_extra_atts'];

	foreach ( $toggle_atts as $attr => $value ) {
		$toggle_atts[ $attr ] = sprintf( $value, 'friends' );
	}

	foreach ( $dropdown_atts as $attr => $value ) {
		$dropdown_atts[ $attr ] = sprintf( $value, 'friends' );
	}

	$output .= enlightenment_open_tag(
		$args['dropdown_tag'],
		sprintf( $args['dropdown_class'], 'friends' ),
		sprintf( $args['dropdown_id'], 'friends' )
	);

	$output .= enlightenment_open_tag(
		$args['toggle_tag'],
		$args['toggle_class'],
		sprintf( $args['toggle_id'], 'friends' ),
		$toggle_atts
	);
		$output .= apply_filters( 'enlightenment_bp_friends_notifications_toggle_label', __( 'Friend Requests', 'enlightement' ) );

		$output .= enlightenment_open_tag(
			$args['alert_tag'],
			( (int) $count > 0 ? $args['alert_count_class'] : $args['alert_no_count_class'] ),
			sprintf( $args['alert_id'], 'friends' )
		);
		$output .= number_format_i18n( $count );
		$output .= enlightenment_close_tag( $args['alert_tag'] );
	$output .= enlightenment_close_tag( $args['toggle_tag'] );

	$output .= enlightenment_open_tag(
		$args['dropdown_menu_tag'],
		$args['dropdown_menu_class'],
		sprintf( $args['dropdown_menu_id'], 'friends' ),
		$dropdown_atts
	);
		$output .= enlightenment_open_tag( $args['notifications_list_tag'], $args['notifications_list_class'] );

		if ( ! empty( $notifications ) ) {
			foreach ( (array) $notifications as $notification ) {
				if( isset( $callbacks[ $notification->component_name ] ) && is_callable( $callbacks[ $notification->component_name ] ) ) {
					$output .= enlightenment_open_tag(
						$args['notification_tag'],
						sprintf( $args['notification_class'], $notification->component_name ),
						sprintf( $args['notification_id'], $notification->id ),
					);
					$output .= enlightenment_open_tag( $args['notification_content_tag'], $args['notification_content_class'] );
					$output .= $notification->content;

					$output .= enlightenment_open_tag( 'a', $args['notification_time_since_class'], '', array(
						'href'               => $notification->href,
						'data-date-notified' => strtotime( $notification->date_notified ),
					) );
					$output .= bp_core_time_since( $notification->date_notified );
					$output .= enlightenment_close_tag( 'a' );
					$output .= enlightenment_close_tag( $args['notification_content_tag'] );
					$output .= enlightenment_close_tag( $args['notification_tag'] );
				} else {
					$output .= enlightenment_open_tag(
						$args['notification_tag'],
						sprintf( $args['notification_class'], $notification->component_name ),
						sprintf( $args['notification_id'], $notification->id ),
					);
					$output .= enlightenment_open_tag( $args['notification_content_tag'], $args['notification_content_class'] );
					$output .= enlightenment_open_tag( 'a', '', '', array(
						'title'  => $notification->content,
						'href'   => $notification->href,
					) );
					$output .= $notification->content;
					$output .= enlightenment_close_tag( 'a' );
					$output .= enlightenment_close_tag( $args['notification_content_tag'] );
					$output .= enlightenment_close_tag( $args['notification_tag'] );
				}
			}
		} else {
			$output .= enlightenment_open_tag(
				$args['no_notifications_tag'],
				$args['no_notifications_class'],
				sprintf( $args['no_notifications_id'], 'friends' )
			);
				$output .= enlightenment_open_tag( 'a', $args['no_notifications_link_class'], '', array(
					'title'  => __( 'Friends', 'enlightenment' ),
					'href'   => $menu_link,
				) );
				$output .= __( 'No new requests', 'enlightenment' );
				$output .= enlightenment_close_tag( 'a' );
			$output .= enlightenment_close_tag( $args['no_notifications_tag'] );
		}

		$output .= enlightenment_close_tag( $args['notifications_list_tag'] );
	$output .= enlightenment_close_tag( $args['dropdown_menu_tag'] );

	$output .= enlightenment_close_tag( $args['dropdown_tag'] );

	/**
	 * Message Notifications
	 */
	$query = BP_Messages_Thread::get_current_threads_for_user( array(
		'user_id' => bp_loggedin_user_id(),
		'box'     => 'inbox',
		'type'    => 'all',
	) );

	$count = 0;

	if ( $query ) {
		$threads = $query['threads'];

		foreach( $threads as $thread ) {
			$count += (int) $thread->unread_count;
		}
	}

	$menu_link     = trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() );
	$toggle_atts   = array_merge( array(
		'href' => $menu_link,
	), $args['toggle_extra_atts'] );
	$dropdown_atts = $args['dropdown_menu_extra_atts'];

	foreach ( $toggle_atts as $attr => $value ) {
		$toggle_atts[ $attr ] = sprintf( $value, 'messages' );
	}

	foreach ( $dropdown_atts as $attr => $value ) {
		$dropdown_atts[ $attr ] = sprintf( $value, 'messages' );
	}

	$output .= enlightenment_open_tag(
		$args['dropdown_tag'],
		sprintf( $args['dropdown_class'], 'messages' ),
		sprintf( $args['dropdown_id'], 'messages' )
	);

	$output .= enlightenment_open_tag(
		$args['toggle_tag'],
		$args['toggle_class'],
		sprintf( $args['toggle_id'], 'messages' ),
		$toggle_atts
	);
		$output .= apply_filters( 'enlightenment_bp_messages_notifications_toggle_label', __( 'Messages', 'enlightement' ) );

		$output .= enlightenment_open_tag(
			$args['alert_tag'],
			( (int) $count > 0 ? $args['alert_count_class'] : $args['alert_no_count_class'] ),
			sprintf( $args['alert_id'], 'messages' )
		);
		$output .= number_format_i18n( $count );
		$output .= enlightenment_close_tag( $args['alert_tag'] );
	$output .= enlightenment_close_tag( $args['toggle_tag'] );

	$output .= enlightenment_open_tag(
		$args['dropdown_menu_tag'],
		$args['dropdown_menu_class'],
		sprintf( $args['dropdown_menu_id'], 'messages' ),
		$dropdown_atts
	);
		$output .= enlightenment_open_tag( $args['notifications_list_tag'], $args['notifications_list_class'] );

		if ( $query ) {
			foreach ( $threads as $key => $thread ) {
				$last_sender_id = $thread->last_sender_id;

				if ( $thread->last_sender_id == bp_loggedin_user_id() ) {
					$thread->messages = array_reverse( $thread->messages );

					foreach ( $thread->messages as $message ) {
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

				$user = get_userdata( $thread->last_sender_id );

				$avatar = bp_core_fetch_avatar( array(
					'item_id' => $user->ID,
					'type'    => 'thumb',
					'width'   => 96,
					'height'  => 96,
					'email'   => $user->user_email,
				) );

				$permalink = bp_core_get_userlink( $user->ID );

				$chat_icon = count( $thread->recipients ) > 2 ? '<i class="fas fa-user-friends"></i>' : '';

				$unread_count = $thread->unread_count ? sprintf( '<span class="unread-count">(%s)</span>', $thread->unread_count ) : '';

				$status_icon = $last_sender_id == bp_loggedin_user_id() ? ( $thread->recipients[(int) $thread->last_sender_id]->unread_count ? '<i class="fas fa-reply"></i>' : '<i class="fas fa-check"></i>' ) : '';
				$status_icon = sprintf( '<span class="status-icon">%s</span>', $status_icon );

				$subject = apply_filters( 'bp_get_message_thread_subject', $thread->last_message_subject );
				$subject = sprintf( '<span class="subject">%s</span>', $subject );

				$content = apply_filters( 'bp_get_the_thread_message_content', $thread->last_message_content );
				$content = wp_kses( $content, 'strip' );
				$content = sprintf( '<span class="last-message-content">&ldquo;%s&rdquo;</span>', $content );

				$output .= enlightenment_open_tag(
					$args['notification_tag'],
					sprintf( $args['notification_class'], 'messages' ) . ' message-thread-notification' . ( $thread->unread_count ? ' unread' : '' ),
					sprintf( 'message-thread-notification-%d', $thread->thread_id ),
				);
				$output .= enlightenment_open_tag( $args['notification_content_tag'], $args['notification_content_class'] );

				$output .= sprintf(
					'%1$s %2$s %3$s %4$s %5$s %6$s %7$s',
					$avatar,
					$permalink,
					$chat_icon,
					$unread_count,
					$status_icon,
					$subject,
					$content
				);

				$output .= enlightenment_open_tag( 'a', $args['notification_time_since_class'], '', array(
					'href'               => bp_get_message_thread_view_link( $thread->thread_id ),
					'data-date-notified' => strtotime( $thread->last_message_date ),
				) );
				$output .= bp_core_time_since( $thread->last_message_date );
				$output .= enlightenment_close_tag( 'a' );
				$output .= enlightenment_close_tag( $args['notification_content_tag'] );
				$output .= enlightenment_close_tag( $args['notification_tag'] );
			}
		} else {
			$output .= enlightenment_open_tag(
				$args['no_notifications_tag'],
				$args['no_notifications_class'],
				sprintf( $args['no_notifications_id'], 'messages' )
			);
				$output .= enlightenment_open_tag( 'a', $args['no_notifications_link_class'], '', array(
					'title'  => __( 'Messages', 'enlightenment' ),
					'href'   => $menu_link,
				) );
				$output .= __( 'No messages', 'enlightenment' );
				$output .= enlightenment_close_tag( 'a' );
			$output .= enlightenment_close_tag( $args['no_notifications_tag'] );
		}

		$output .= enlightenment_close_tag( $args['notifications_list_tag'] );
	$output .= enlightenment_close_tag( $args['dropdown_menu_tag'] );

	$output .= enlightenment_close_tag( $args['dropdown_tag'] );

	/**
	 * Other Notifications
	 */
	$notifications = $renderable['other'];
	$menu_link     = trailingslashit( bp_loggedin_user_domain() . bp_get_notifications_slug() );
	$count         = ! empty( $notifications ) ? count( $notifications ) : 0;
	$toggle_atts   = array_merge( array(
		'href' => $menu_link,
	), $args['toggle_extra_atts'] );
	$dropdown_atts = $args['dropdown_menu_extra_atts'];

	foreach ( $toggle_atts as $attr => $value ) {
		$toggle_atts[ $attr ] = sprintf( $value, 'other' );
	}

	foreach ( $dropdown_atts as $attr => $value ) {
		$dropdown_atts[ $attr ] = sprintf( $value, 'other' );
	}

	$output .= enlightenment_open_tag(
		$args['dropdown_tag'],
		sprintf( $args['dropdown_class'], 'other' ),
		sprintf( $args['dropdown_id'], 'other' )
	);

	$output .= enlightenment_open_tag(
		$args['toggle_tag'],
		$args['toggle_class'],
		sprintf( $args['toggle_id'], 'other' ),
		$toggle_atts
	);
		$output .= apply_filters( 'enlightenment_bp_other_notifications_toggle_label', __( 'Other Notifications', 'enlightement' ) );

		$output .= enlightenment_open_tag(
			$args['alert_tag'],
			( (int) $count > 0 ? $args['alert_count_class'] : $args['alert_no_count_class'] ),
			sprintf( $args['alert_id'], 'other' )
		);
		$output .= number_format_i18n( $count );
		$output .= enlightenment_close_tag( $args['alert_tag'] );
	$output .= enlightenment_close_tag( $args['toggle_tag'] );

	$output .= enlightenment_open_tag(
		$args['dropdown_menu_tag'],
		$args['dropdown_menu_class'],
		sprintf( $args['dropdown_menu_id'], 'other' ),
		$dropdown_atts
	);
		$output .= enlightenment_open_tag( $args['notifications_list_tag'], $args['notifications_list_class'] );

		if ( ! empty( $notifications ) ) {
			foreach ( (array) $notifications as $notification ) {
				if( isset( $callbacks[ $notification->component_name ] ) && is_callable( $callbacks[ $notification->component_name ] ) ) {
					$output .= enlightenment_open_tag(
						$args['notification_tag'],
						sprintf( $args['notification_class'], $notification->component_name ),
						sprintf( $args['notification_id'], $notification->id ),
					);
					$output .= enlightenment_open_tag( $args['notification_content_tag'], $args['notification_content_class'] );
					$output .= $notification->content;

					$output .= enlightenment_open_tag( 'a', $args['notification_time_since_class'], '', array(
						'href'               => $notification->href,
						'data-date-notified' => strtotime( $notification->date_notified ),
					) );
					$output .= bp_core_time_since( $notification->date_notified );
					$output .= enlightenment_close_tag( 'a' );
					$output .= enlightenment_close_tag( $args['notification_content_tag'] );
					$output .= enlightenment_close_tag( $args['notification_tag'] );
				} else {
					$output .= enlightenment_open_tag(
						$args['notification_tag'],
						sprintf( $args['notification_class'], $notification->component_name ),
						sprintf( $args['notification_id'], $notification->id ),
					);
					$output .= enlightenment_open_tag( $args['notification_content_tag'], $args['notification_content_class'] );
					$output .= enlightenment_open_tag( 'a', '', '', array(
						'title' => $notification->content,
						'href'  => $notification->href,
					) );
					$output .= $username . $notification->content;
					$output .= enlightenment_close_tag( 'a' );
					$output .= enlightenment_close_tag( $args['notification_content_tag'] );
					$output .= enlightenment_close_tag( $args['notification_tag'] );
				}
			}
		} else {
			$output .= enlightenment_open_tag(
				$args['no_notifications_tag'],
				$args['no_notifications_class'],
				sprintf( $args['no_notifications_id'], 'friends' )
			);
				$output .= enlightenment_open_tag( 'a', $args['no_notifications_link_class'], '', array(
					'title'  => __( 'Notifications', 'enlightenment' ),
					'href'   => $menu_link,
				) );
				$output .= __( 'No new notifications', 'enlightenment' );
				$output .= enlightenment_close_tag( 'a' );
			$output .= enlightenment_close_tag( $args['no_notifications_tag'] );
		}

		$output .= enlightenment_close_tag( $args['notifications_list_tag'] );
	$output .= enlightenment_close_tag( $args['dropdown_menu_tag'] );

	$output .= enlightenment_close_tag( $args['dropdown_tag'] );
	// End Notifications

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_notifications', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_setup_group_object() {
	if ( bp_has_groups() ) {
		while ( bp_groups() ) {
			bp_the_group();
		}
	}
}

function enlightenment_bp_group_admin( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'group-admin-body item-body',
		'container_id'    => 'item-body',
		'echo'            => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_admin_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	ob_start();
	bp_get_template_part( 'groups/single/admin' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_admin', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_members( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'group-members-body item-body',
		'container_id'    => 'item-body',
		'echo'            => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_members_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	ob_start();
	bp_groups_members_template_part();
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_members', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_send_invites( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'group-send-invites-body item-body',
		'container_id'    => 'item-body',
		'echo'            => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_send_invites_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	ob_start();
	bp_get_template_part( 'groups/single/send-invites' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_send_invites', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_membership_request( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'group-membership-request-body item-body',
		'container_id'    => 'item-body',
		'echo'            => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_membership_request_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	ob_start();
	bp_get_template_part( 'groups/single/request-membership' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_membership_request', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_plugins( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'group-plugins-body item-body',
		'container_id'    => 'item-body',
		'echo'            => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_plugins_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	ob_start();
	bp_get_template_part( 'groups/single/plugins' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_plugins', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_avatar( $args = null ) {
	if ( bp_disable_group_avatar_uploads() || ! buddypress()->avatar->show_avatars ) {
		return;
	}

	$defaults = array(
		'container'            => 'figure',
		'container_class'      => 'group-avatar',
		'container_id'         => 'item-header-avatar',
		'container_extra_atts' => '',
		'item_id'              => bp_get_group_id(),
		'wrap_link'            => bp_is_group() ? false : true,
		'type'                 => 'full',
		'width'                => 300,
		'height'               => 300,
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_avatar_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$avatar = bp_get_group_avatar( $args );

	if( ! $avatar ) {
		return;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	if ( $args['wrap_link'] ) {
		$output .= sprintf( '<a href="%1$s" title="%2$s">', esc_url( bp_get_group_url() ), esc_attr( bp_get_group_name() ) );
	}

	$output .= $avatar;

	if ( $args['wrap_link'] ) {
		$output .= '</a>';
	}

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_group_avatar', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_header_content( $args = null ) {
	if ( bp_nouveau_groups_front_page_description() ) {
		return;
	}

	$defaults = array(
		'container'       => 'div',
		'container_class' => 'bp-item-header-content',
		'container_id'    => 'item-header-content',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_group_header_content_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	ob_start();
	do_action( 'enlightenment_bp_group_header_content' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_header_content_output', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_header_actions( $args = null ) {
	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_header_actions_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bp_get_template_part( 'groups/single/parts/header-item-actions' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_bp_group_header_actions', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_header_buttons( $args = null ) {
	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_header_buttons_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bp_nouveau_group_header_buttons();
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_bp_group_header_buttons', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_nav( $args = null ) {
	if ( bp_nouveau_is_object_nav_in_sidebar() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_nav_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bp_get_template_part( 'groups/single/parts/item-nav' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_bp_group_nav', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_group_description( $args = null ) {
	if ( bp_nouveau_groups_front_page_description() ) {
		return;
	}

	if ( ! bp_nouveau_group_has_meta( 'description' ) ) {
		return;
	}

	$defaults = array(
		'container'       => 'div',
		'container_class' => 'desc-wrap',
		'container_id'    => '',
		'wrapper'         => 'div',
		'wrapper_class'   => 'group-description',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_group_description_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= enlightenment_open_tag( $args['wrapper'], $args['wrapper_class'] );

	$output .= bp_get_group_description();

	$output .= enlightenment_close_tag( $args['wrapper'] );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_description', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_body( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'item-body',
		'container_id'    => 'item-body',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_group_body_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	ob_start();
	/**
	 * Fires before the display of the group home body.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_group_body' );
	$output .= ob_get_clean();

	if ( bp_is_group_home() && ! bp_current_user_can( 'groups_access_group' ) ) {
		$content = '';

		ob_start();
		/**
		 * Fires before the display of the group status message.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_group_status_message' );
		$content .= ob_get_clean();

		$content .= enlightenment_open_tag( 'div', 'info', 'message' );
		$content .= enlightenment_open_tag( 'p' );

		ob_start();
		bp_group_status_message();
		$content .= ob_get_clean();

		$content .= enlightenment_close_tag( 'p' );
		$content .= enlightenment_close_tag( 'div' );

		ob_start();
		/**
		 * Fires after the display of the group status message.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_group_status_message' );
		$content .= ob_get_clean();

		$content = apply_filters( 'enlightenment_bp_filter_group_status_message_output', $content );

		$output .= $content;
	} elseif ( bp_is_group_home() && false !== bp_groups_get_front_template() ) {
		ob_start();
		bp_groups_front_template_part();
		$content = ob_get_clean();
		$content = apply_filters( 'enlightenment_bp_filter_template_groups_front_output', $content );

		$output .= $content;
	} else {
		$template = 'plugins';

		// the home page
		if ( bp_is_group_home() ) {
			if ( bp_is_active( 'activity' ) ) {
				$template = 'activity';
			} else {
				$template = 'members';
			}

		// Not the home page
		} elseif ( bp_is_group_admin_page() ) {
			$template = 'admin';
		} elseif ( bp_is_group_activity() ) {
			$template = 'activity';
		} elseif ( bp_is_group_members() ) {
			$template = 'members';
		} elseif ( bp_is_group_invites() ) {
			$template = 'send-invites';
		} elseif ( bp_is_group_membership_request() ) {
			$template = 'request-membership';
		}

		ob_start();
		bp_nouveau_group_get_template_part( $template );
		$content = ob_get_clean();

		$content = apply_filters( sprintf( 'enlightenment_bp_filter_template_group_%s_output', $template ), $content );

		$output .= $content;
	}

	ob_start();
	/**
	 * Fires after the display of the group home body.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_group_body' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_group_body', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_group_admins( $args = null ) {
	$defaults = array(
		'container'            => 'aside',
		'container_class'      => 'widget widget_group_admins',
		'container_id'         => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_admins_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = '';

	if( bp_group_is_visible() ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

		$output .= sprintf( '<h3 class="widget-title">%s</h3>', __( 'Group Admins', 'enlightenment' ) );

		ob_start();
		bp_group_list_admins();

		/**
		 * Fires after the display of the group's administrators.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_group_menu_admins' );
		$output .= ob_get_clean();

		$output .= enlightenment_close_tag( $args['container'] );
	}

	$output = apply_filters( 'enlightenment_bp_group_admins', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_mods( $args = null ) {
	$defaults = array(
		'container'            => 'aside',
		'container_class'      => 'widget widget_group_mods',
		'container_id'         => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_mods_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = '';

	if( bp_group_is_visible() && bp_group_has_moderators() ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

		$output .= sprintf( '<h3 class="widget-title">%s</h3>', __( 'Group Mods', 'enlightenment' ) );

		ob_start();
		/**
		 * Fires before the display of the group's moderators, if there are any.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_group_menu_mods' );

		bp_group_list_mods();

		/**
		 * Fires after the display of the group's moderators, if there are any.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_group_menu_mods' );
		$output .= ob_get_clean();

		$output .= enlightenment_close_tag( $args['container'] );
	}

	$output = apply_filters( 'enlightenment_bp_group_mods', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_members_directory_title( $args = null ) {
	$defaults = array(
		'container'       => 'h1',
		'container_class' => 'page-title',
		'container_id'    => '',
		'directory_title' => __( 'Members', 'enlightenment' ),
		'before'          => '',
		'after'           => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_members_directory_title_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= $args['before'];

	$output .= esc_html( $args['directory_title'] );

	$output .= $args['after'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_members_directory_title', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_creation_tabs( $args = null ) {
	$defaults = array(
		'container'            => 'nav',
		'container_class'      => bp_nouveau_get_group_create_steps_classes(),
		'container_id'         => 'group-create-tabs',
		'container_extra_atts' => array(
			'role'       => 'navigation',
			'aria-label' => __( 'Group creation menu', 'enlightenment' ),
		),
		'wrapper'              => 'ol',
		'wrapper_class'        => 'group-create-buttons button-tabs',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_group_creation_tabs_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bp_group_creation_tabs();
	$tabs = ob_get_clean();

	if ( empty( $tabs ) ) {
		return;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= enlightenment_open_tag( $args['wrapper'], $args['wrapper_class'] );
	$output .= $tabs;
	$output .= enlightenment_close_tag( $args['wrapper'] );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_creation_tabs', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_creation_screen( $args = null ) {
	$defaults = array(
		'container'            => 'group-invites' == bp_get_groups_current_create_step() ? 'div' : 'form',
		'container_class'      => 'standard-form',
		'container_id'         => 'create-group-form',
		'container_extra_atts' => 'group-invites' == bp_get_groups_current_create_step() ? '' : array(
			'method'  => 'post',
			'enctype' => 'multipart/form-data',
		),
		'wrapper'              => 'div',
		'wrapper_class'        => 'item-body',
		'wrapper_id'           => 'group-create-body',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_group_creation_screen_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	ob_start();
	bp_nouveau_groups_create_hook( 'before' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( $args['wrapper'], $args['wrapper_class'], $args['wrapper_id'] );

	ob_start();
	bp_nouveau_group_creation_screen();
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['wrapper'] );

	ob_start();
	bp_nouveau_groups_create_hook( 'after' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_group_creation_screen', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_name( $args = null ) {
	$defaults = array(
		'container'       => 'h1',
		'container_class' => 'page-title',
		'container_id'    => '',
		'before'          => '',
		'after'           => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_group_name_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= $args['before'];

	$output .= esc_html( bp_get_group_name() );

	$output .= $args['after'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_name', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_status( $args = null ) {
	$defaults = array(
		'container'       => 'p',
		'container_class' => 'highlight group-status',
		'container_id'    => '',
		'before'          => '<strong>',
		'after'           => '</strong>',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_group_status_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= $args['before'];

	if ( function_exists( 'bp_nouveau_the_group_meta' ) ) {
		$output .= esc_html( bp_nouveau_the_group_meta( array(
			'keys' => 'status',
			'echo' => false,
		) ) );
	} else {
		$output .= esc_html( bp_nouveau_group_meta()->status );
	}

	$output .= $args['after'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_status', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_last_active( $args = null ) {
	$defaults = array(
		'container'            => 'p',
		'container_class'      => 'activity',
		'container_id'         => '',
		'container_extra_atts' => array(
			'data-livestamp' => bp_core_get_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ),
		),
		'before'               => '',
		'after'                => '',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_group_last_active_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= $args['before'];

	$output .= sprintf( __( 'active %s', 'enlightenment' ), bp_get_group_last_active() );

	$output .= $args['after'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_last_active', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_type_list( $args = null ) {
	if ( function_exists( 'bp_nouveau_the_group_meta' ) ) {
		$group_type_list = esc_html( bp_nouveau_the_group_meta( array(
			'keys' => 'group_type_list',
			'echo' => false,
		) ) );
	} else {
		$group_type_list = bp_nouveau_group_meta()->group_type_list;
	}

	if ( empty( $group_type_list ) ) {
		return;
	}

	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'group-type-list',
		'container_id'         => '',
		'before'               => '',
		'after'                => '',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_group_type_list_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= $args['before'];

	$output .= $group_type_list;

	$output .= $args['after'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_group_type_list', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_meta( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'item-meta',
		'container_id'    => '',
		'echo'            => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_meta_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = '';

	ob_start();
	bp_nouveau_group_hook( 'before', 'header_meta' );
	$output .= ob_get_clean();

	if ( bp_nouveau_group_has_meta_extra() ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

		if ( function_exists( 'bp_nouveau_the_group_meta' ) ) {
			$output .= esc_html( bp_nouveau_the_group_meta( array(
				'keys' => 'extra',
				'echo' => false,
			) ) );
		} else {
			$output .= bp_nouveau_group_meta()->extra;
		}

		$output .= enlightenment_close_tag( $args['container'] );
	}

	$output  = apply_filters( 'enlightenment_bp_group_meta', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_cover_image( $args = null ) {
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
		'container_class' => 'bp-header-cover-image',
		'container_id'    => 'header-cover-image',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_cover_image_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = sprintf(
		'<%1$s%2$s%3$s></%1$s>',
		$args['container'],
		empty( $args['container_class'] ) ? '' : sprintf( ' class="%s"', esc_attr( $args['container_class'] ) ),
		empty( $args['container_id'] )    ? '' : sprintf( ' id="%s"',    esc_attr( $args['container_id']    ) )
	);
	$output .= "\n";

	$output = apply_filters( 'enlightenment_bp_cover_image', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_template_notices( $args = null ) {
	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_template_notices_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bp_nouveau_template_notices();
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_template_notices', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_user_avatar( $args = null ) {
	$defaults = array(
		'container'            => 'figure',
		'container_class'      => 'user-avatar',
		'container_id'         => 'item-header-avatar',
		'container_extra_atts' => '',
		'item_id'              => bp_displayed_user_id(),
		'wrap_link'            => bp_is_user() ? false : true,
		'type'                 => 'full',
		'width'                => BP_AVATAR_FULL_WIDTH,
		'height'               => BP_AVATAR_FULL_HEIGHT,
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_user_avatar_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$avatar = bp_core_fetch_avatar( $args );

	if( ! $avatar ) {
		return;
	}

	if ( $args['wrap_link'] ) {
		$link = bp_members_get_user_url( $args['item_id'] );

		if ( $args['item_id'] == bp_displayed_user_id() ) {
			$link = apply_filters( 'bp_get_displayed_user_link', $link );
		} else {
			$link = apply_filters( 'bp_get_member_permalink', $link );
		}
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	if ( $args['wrap_link'] ) {
		$output .= sprintf( '<a href="%s">', esc_url( $link ) );
	}

	$output .= $avatar;

	if ( $args['wrap_link'] ) {
		$output .= '</a>';
	}

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_user_avatar', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_user_header_content( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'bp-item-header-content',
		'container_id'    => 'item-header-content',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_user_header_content_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	ob_start();
	do_action( 'enlightenment_bp_user_header_content' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_user_header_content_output', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_user_name( $args = null ) {
	$defaults = array(
		'container'       => 'h1',
		'container_class' => 'page-title',
		'container_id'    => '',
		'user_id'         => bp_displayed_user_id(),
		'before'          => '',
		'after'           => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_user_name_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= $args['before'];

	$output .= bp_core_get_user_displayname( $args['user_id'] );

	$output .= $args['after'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_user_name', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_user_mentionname( $args = null ) {
	if ( ! bp_is_active( 'activity' ) ) {
		return;
	}

	if ( ! bp_activity_do_mentions() ) {
		return;
	}

	$defaults = array(
		'container'       => 'h2',
		'container_class' => 'user-nicename',
		'container_id'    => '',
		'user_id'         => bp_displayed_user_id(),
		'format'          => '@%s',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_user_mentionname_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$mentionname = apply_filters( 'bp_get_displayed_user_mentionname', bp_activity_get_user_mentionname( $args['user_id'] ) );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= sprintf( $args['format'], $mentionname );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_bp_user_mentionname', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_member_header_actions( $args = null ) {
	$defaults = array(
		'container'       => 'ul',
		'container_class' => 'member-header-actions',
		'container_id'    => 'item-buttons',
		'parent_element'  => '',
		'parent_class'    => '',
		'button_element'  => 'button',
		'echo'            => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_member_header_actions_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$cb_args = array(
		'container'         => $args['container'],
		'container_classes' => explode( ' ', $args['container_class'] ),
		'parent_element'    => $args['parent_element'],
		'parent_attr'       => array( 'class' => $args['parent_class'] ),
		'button_element'    => $args['button_element'],
	);

	ob_start();
	bp_nouveau_member_header_buttons( $cb_args );
	$output = ob_get_clean();

	ob_start();
	bp_nouveau_wrapper( array_merge( $cb_args, array( 'output' => ' ' ) ) );
	$empty = ob_get_clean();

	if ( $output === $empty ) {
		return;
	}

	$output = apply_filters( 'enlightenment_bp_member_header_actions', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_member_meta( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'item-meta',
		'container_id'    => '',
		'echo'            => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_member_meta_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = '';

	ob_start();
	bp_nouveau_member_hook( 'before', 'header_meta' );
	$output .= ob_get_clean();

	if ( bp_nouveau_member_has_meta() ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

		ob_start();
		bp_nouveau_member_meta();
		$output .= ob_get_clean();

		$output .= enlightenment_close_tag( $args['container'] );
	}

	$output  = apply_filters( 'enlightenment_bp_member_meta', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_members_stats( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'members-stats stats',
		'container_id'         => 'members-stats',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_members_stats_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	$count   = bp_get_total_member_count();

	$output .= sprintf( '<span class="members-count"><strong class="number">%s</strong> %s</span>', $count, _n( 'Member', 'Members', $count, 'enlightenment' ) );

	if( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) {
		$count   = bp_get_total_friend_count( bp_loggedin_user_id() );

		$output .= sprintf( '<span class="friends-count"><strong class="number">%s</strong> %s</span>', $count, _n( 'Friend', 'Friends', $count, 'enlightenment' ) );
	}

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_members_stats', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_groups_stats( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'groups-stats stats',
		'container_id'         => 'groups-stats',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_groups_stats_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	$output .= sprintf( '<span class="groups-count"><strong class="number">%s</strong> %s</span>', bp_get_total_group_count(), __( 'Groups', 'enlightenment' ) );

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_groups_stats', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_stats( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'group-stats stats',
		'container_id'         => 'group-stats',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_stats_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	global $groups_template;

	if ( 'public' == $groups_template->group->status ) {
		$type = __( 'Public', 'enlightenment' );
	} elseif ( 'hidden' == $groups_template->group->status ) {
		$type = __( 'Hidden', 'enlightenment' );
	} elseif ( 'private' == $groups_template->group->status ) {
		$type = __( 'Private', 'enlightenment' );
	} else {
		$type = esc_html( ucwords( $groups_template->group->status ) );
	}

	if ( isset( $groups_template->group->total_member_count ) ) {
		$count = (int) $groups_template->group->total_member_count;
	} else {
		$count = 0;
	}

	$output .= sprintf( '<span class="group-visibility"><strong>%s</strong> %s</span>', $type, __( 'Group', 'enlightenment' ) );
	$output .= sprintf( '<span class="group-members-count"><strong class="number">%s</strong> %s</span>', $count, __( 'Members', 'enlightenment' ) );

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_group_stats', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_blogs_stats( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'blogs-stats stats',
		'container_id'         => 'blogs-stats',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_blogs_stats_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	$output .= sprintf( '<span class="blogs-count"><strong class="number">%s</strong> %s</span>', bp_get_total_blog_count(), __( 'Sites', 'enlightenment' ) );

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_blogs_stats', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_follow_stats( $args = null ) {
	if( ! function_exists( 'bp_follow_total_follow_counts' ) ) {
		return;
	}

	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'follow-stats stats',
		'container_id'         => 'follow-stats',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_follow_stats_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$user_id = bp_displayed_user_id();
	if( ! $user_id ) {
		global $members_template;
		$user_id = (int) $members_template->member->id;
	}

	$counts  = bp_follow_total_follow_counts( array( 'user_id' => $user_id ) );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	$output .= enlightenment_open_tag( 'span', 'followers' );
	$output .= sprintf( __( '%s Followers', 'enlightenment' ), sprintf( '<span class="number">%s</span>', $counts['followers'] ) );
	$output .= enlightenment_close_tag( 'span' );

	$output .= enlightenment_open_tag( 'span', 'following' );
	$output .= sprintf( __( '%s Following', 'enlightenment' ), sprintf( '<span class="number">%s</span>', $counts['following'] ) );
	$output .= enlightenment_close_tag( 'span' );

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_follow_stats', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_directory_nav( $args = null ) {
	if ( bp_nouveau_is_object_nav_in_sidebar() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_directory_nav_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bp_get_template_part( 'common/nav/directory-nav' );
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_directory_nav', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

// Deprecated, remove
function enlightenment_bp_activity_nav( $args = null ) {
	$defaults = array(
		'container'            => 'nav',
		'container_class'      => 'activity-navigation item-list-tabs',
		'container_id'         => '',
		'container_extra_atts' => ' role="navigation"',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_activity_nav_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= enlightenment_open_tag( 'ul', 'menu nav' );

	ob_start();
	/**
	 * Fires before the listing of activity type tabs.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_activity_type_tab_all' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( 'li', 'selected', 'activity-all' );
	$output .= sprintf( '<a href="%1$s">%2$s <span>%3$s</span></a>', esc_url( bp_get_activity_directory_permalink() ), __( 'All Members', 'buddypress' ), bp_get_total_member_count() );
	$output .= enlightenment_close_tag( 'li' );

	if( is_user_logged_in() ) {
		if( bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) {
			$output .= enlightenment_open_tag( 'li', '', 'activity-friends' );
			$output .= sprintf( '<a href="%1$s">%2$s <span>%3$s</span></a>', esc_url( bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/' ), __( 'My Friends ', 'buddypress' ), bp_get_total_friend_count( bp_loggedin_user_id() ) );
			$output .= enlightenment_close_tag( 'li' );
		}

		ob_start();
		/**
		 * Fires before the listing of groups activity type tab.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_activity_type_tab_groups' );
		$output .= ob_get_clean();

		if( bp_is_active( 'groups' ) && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) {
			$output .= enlightenment_open_tag( 'li', '', 'activity-groups' );
			$output .= sprintf( '<a href="%1$s">%2$s <span>%3$s</span></a>', esc_url( bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/' ), __( 'My Groups ', 'buddypress' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) );
			$output .= enlightenment_close_tag( 'li' );
		}

		ob_start();
		/**
		 * Fires before the listing of favorites activity type tab.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_activity_type_tab_favorites' );
		$output .= ob_get_clean();

		if( bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) {
			$output .= enlightenment_open_tag( 'li', '', 'activity-favorites' );
			$output .= sprintf( '<a href="%1$s">%2$s <span>%3$s</span></a>', esc_url( bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/' ), __( 'My Favorites ', 'buddypress' ), bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) );
			$output .= enlightenment_close_tag( 'li' );
		}

		if( bp_activity_do_mentions() ) {
			ob_start();
			/**
			 * Fires before the listing of mentions activity type tab.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_before_activity_type_tab_mentions' );
			$output .= ob_get_clean();

			$output .= enlightenment_open_tag( 'li', '', 'activity-mentions' );
			$output .= sprintf( '<a href="%1$s">%2$s%3$s</a>', esc_url( bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/' ), __( 'Mentions', 'buddypress' ), ( bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ? sprintf( ' <strong><span>%s</span></strong>', sprintf( _nx( '%s new', '%s new', bp_get_total_mention_count_for_user( bp_loggedin_user_id() ), 'Number of new activity mentions', 'buddypress' ), bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) ) : '' ) );
			$output .= enlightenment_close_tag( 'li' );
		}
	}

	ob_start();
	/**
	 * Fires after the listing of activity type tabs.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_activity_type_tabs' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( 'ul' );
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_activity_nav', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_members_nav( $args = null ) {
	$defaults = array(
		'container'            => 'nav',
		'container_class'      => 'members-navigation item-list-tabs',
		'container_id'         => '',
		'container_extra_atts' => ' role="navigation"',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_members_nav_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= enlightenment_open_tag( 'ul', 'menu nav' );

	$output .= enlightenment_open_tag( 'li', 'selected', 'members-all' );
	$output .= sprintf( '<a href="%1$s">%2$s <span>%3$s</span></a>', esc_url( bp_get_members_directory_permalink() ), __( 'All Members', 'buddypress' ), bp_get_total_member_count() );
	$output .= enlightenment_close_tag( 'li' );

	if( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) {
		$output .= enlightenment_open_tag( 'li', '', 'members-personal' );
		$output .= sprintf( '<a href="%1$s">%2$s <span>%3$s</span></a>', esc_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ), __( 'My Friends ', 'buddypress' ), bp_get_total_friend_count( bp_loggedin_user_id() ) );
		$output .= enlightenment_close_tag( 'li' );
	}

	ob_start();
	/**
	 * Fires inside the members directory member types.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_members_directory_member_types' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( 'ul' );
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_members_nav', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

// Deprecated, remove
function enlightenment_bp_groups_nav( $args = null ) {
	$defaults = array(
		'container'            => 'nav',
		'container_class'      => 'groups-navigation item-list-tabs',
		'container_id'         => '',
		'container_extra_atts' => ' role="navigation"',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_groups_nav_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= enlightenment_open_tag( 'ul', 'menu nav' );

	$output .= enlightenment_open_tag( 'li', bp_is_groups_directory() ? 'selected' : '', 'groups-all' );
	$output .= sprintf( '<a href="%1$s">%2$s <span>%3$s</span></a>', esc_url( bp_get_groups_directory_permalink() ), __( 'All Groups', 'buddypress' ), bp_get_total_group_count() );
	$output .= enlightenment_close_tag( 'li' );

	if( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) {
		$output .= enlightenment_open_tag( 'li', '', 'groups-personal' );
		$output .= sprintf( '<a href="%1$s">%2$s <span>%3$s</span></a>', esc_url( bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups/' ), __( 'My Groups', 'buddypress' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) );
		$output .= enlightenment_close_tag( 'li' );
	}

	ob_start();
	/**
	 * Fires inside the groups directory group filter input.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_groups_directory_group_filter' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( 'ul' );
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_groups_nav', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_member_types( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'member-types item-list-tabs',
		'container_id'         => 'subnav',
		'container_extra_atts' => ' role="navigation"',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_member_types_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= enlightenment_open_tag( 'ul' );

	ob_start();
	/**
	 * Fires inside the members directory member sub-types.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_members_directory_member_sub_types' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( 'li', 'last filter', 'members-order-select' );
	$output .= sprintf( '<label for="members-order-by">%s</label>', __( 'Order By:', 'enlightenment' ) );

	$output .= enlightenment_open_tag( 'select', '', 'members-order-by' );

	$output .= sprintf( '<option value="active">%s</option>', __( 'Last Active', 'enlightenment' ) );
	$output .= sprintf( '<option value="newest">%s</option>', __( 'Newest Registered', 'enlightenment' ) );

	if( bp_is_active( 'xprofile' ) ) {
		$output .= sprintf( '<option value="alphabetical">%s</option>', __( 'Alphabetical', 'enlightenment' ) );
	}

	ob_start();
	/**
	 * Fires inside the members directory member order options.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_members_directory_order_options' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( 'select' );

	$output .= enlightenment_close_tag( 'li' );

	$output .= enlightenment_close_tag( 'ul' );
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_member_types', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_groups_directory_group_types( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'groups-directory-group-types item-list-tabs',
		'container_id'         => 'subnav',
		'container_extra_atts' => ' role="navigation"',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_groups_directory_group_types_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= enlightenment_open_tag( 'ul' );

	ob_start();
	/**
	 * Fires inside the groups directory group types.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_groups_directory_group_types' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( 'li', 'last filter', 'groups-order-select' );
	$output .= sprintf( '<label for="groups-order-by">%s</label>', __( 'Order By:', 'enlightenment' ) );

	$output .= enlightenment_open_tag( 'select', '', 'groups-order-by' );

	$output .= sprintf( '<option value="active">%s</option>', __( 'Last Active', 'enlightenment' ) );
	$output .= sprintf( '<option value="popular">%s</option>', __( 'Most Members', 'enlightenment' ) );
	$output .= sprintf( '<option value="newest">%s</option>', __( 'Newly Created', 'enlightenment' ) );
	$output .= sprintf( '<option value="alphabetical">%s</option>', __( 'Alphabetical', 'enlightenment' ) );

	ob_start();
	/**
	 * Fires inside the groups directory group order options.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_groups_directory_order_options' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( 'select' );

	$output .= enlightenment_close_tag( 'li' );

	$output .= enlightenment_close_tag( 'ul' );
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_groups_directory_group_types', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_blog_types( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'blog-types item-list-tabs',
		'container_id'         => 'subnav',
		'container_extra_atts' => ' role="navigation"',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_blog_types_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= enlightenment_open_tag( 'ul' );

	ob_start();
	/**
	 * Fires inside the unordered list displaying blog sub-types.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_blogs_directory_blog_sub_types' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( 'li', 'last filter', 'blogs-order-select' );
	$output .= sprintf( '<label for="blogs-order-by">%s</label>', __( 'Order By:', 'enlightenment' ) );

	$output .= enlightenment_open_tag( 'select', '', 'blogs-order-by' );

	$output .= sprintf( '<option value="active">%s</option>', __( 'Last Active', 'enlightenment' ) );
	$output .= sprintf( '<option value="newest">%s</option>', __( 'Newly Created', 'enlightenment' ) );
	$output .= sprintf( '<option value="alphabetical">%s</option>', __( 'Alphabetical', 'enlightenment' ) );

	ob_start();
	/**
	 * Fires inside the select input listing blogs orderby options.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_blogs_directory_order_options' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( 'select' );

	$output .= enlightenment_close_tag( 'li' );

	$output .= enlightenment_close_tag( 'ul' );
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_blog_types', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_displayed_user_body( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'item-body',
		'container_id'    => 'item-body',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bp_displayed_user_body_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	ob_start();
	/**
	 * Fires before the display of member body content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_body' );
	$output .= ob_get_clean();

	if ( bp_is_user_front() ) {
		ob_start();
		bp_displayed_user_front_template_part();
		$content = ob_get_clean();
		$content = apply_filters( 'enlightenment_bp_filter_template_member_front_output', $content );

		$output .= $content;
	} else {
		$template = 'plugins';

		if ( bp_is_user_activity() ) {
			$template = 'activity';
		} elseif ( bp_is_user_blogs() ) {
			$template = 'blogs';
		} elseif ( bp_is_user_friends() ) {
			$template = 'friends';
		} elseif ( bp_is_user_groups() ) {
			$template = 'groups';
		} elseif ( bp_is_user_messages() ) {
			$template = 'messages';
		} elseif ( bp_is_user_profile() ) {
			$template = 'profile';
		} elseif ( bp_is_user_notifications() ) {
			$template = 'notifications';
		} elseif ( bp_is_user_settings() ) {
			$template = 'settings';
		}

		ob_start();
		bp_nouveau_member_get_template_part( $template );
		$content = ob_get_clean();

		$content = apply_filters( sprintf( 'enlightenment_bp_filter_template_member_%s_output', $template ), $content );

		$output .= $content;
	}

	ob_start();
	/**
	 * Fires after the display of member body content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_body' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_displayed_user_body', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_displayed_user_nav( $args = null ) {
	if ( bp_nouveau_is_object_nav_in_sidebar() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_displayed_user_nav_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bp_buffer_template_part( 'members/single/parts/item-nav' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_bp_displayed_user_nav', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_activity_post_form( $args = null ) {
	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_activity_post_form_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	if( bp_is_user() ) {
		do_action( 'bp_before_member_activity_post_form' );
		if( is_user_logged_in() && bp_is_my_profile() && ( ! bp_current_action() || bp_is_current_action( 'just-me' ) ) ) {
			bp_locate_template( array( 'activity/post-form.php'), true );
		}
		do_action( 'bp_after_member_activity_post_form' );
	} elseif( bp_is_group() ) {
		/**
		 * Fires before the display of the group activity post form.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_group_activity_post_form' );

		if ( is_user_logged_in() && bp_group_is_member() ) {
			bp_get_template_part( 'activity/post-form' );
		}

		/**
		 * Fires after the display of the group activity post form.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_group_activity_post_form' );
	} elseif( bp_is_activity_component() ) {
		if ( is_user_logged_in() ) {
			bp_get_template_part( 'activity/post-form' );
		}
	}
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_bp_activity_post_form', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_bp_user_activity_nav( $args = null ) {
	$defaults = array(
		'container'            => 'nav',
		'container_class'      => 'user-activity-nav item-list-tabs no-ajax',
		'container_id'         => 'subnav',
		'container_extra_atts' => ' role="navigation"',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_user_activity_nav_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$options  = enlightenment_open_tag( 'ul', 'options-nav menu nav' );

	ob_start();
	bp_get_options_nav();
	$options .= ob_get_clean();

	$options .= enlightenment_close_tag( 'ul' );

	$options  = apply_filters( 'enlightenment_bp_get_options_nav', $options );

	$filter  = enlightenment_open_tag( 'div', 'activity-filter', 'activity-filter-select' );
	$filter .= sprintf( '<label for="activity-filter-by" class="bp-screen-reader-text screen-reader-text">%s</label>', __( 'Show:', 'buddypress' ) );
	$filter .= enlightenment_open_tag( 'select', '', 'activity-filter-by' );
	$filter .= sprintf( '<option value="-1">%s</option>', __( 'Everything', 'enlightenment' ) );

	ob_start();
	bp_activity_show_filters();
	/**
	 * Fires inside the select input for member activity filter options.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_member_activity_filter_options' );
	$filter .= ob_get_clean();
	$filter .= enlightenment_close_tag( 'select' );
	$filter .= enlightenment_close_tag( 'div' );

	$filter  = apply_filters( 'enlightenment_bp_activity_show_filters', $filter );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= $options;
	$output .= $filter;
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_user_activity_nav', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_search_and_filters_bar( $args = null ) {
	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_search_and_filters_bar_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bp_get_template_part( 'common/search-and-filters-bar' );
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_search_and_filters_bar', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

// Deprecated, remove
function enlightenment_bp_activity_filter( $args = null ) {
	$defaults = array(
		'container'            => 'nav',
		'container_class'      => 'item-list-tabs no-ajax',
		'container_id'         => 'subnav',
		'container_extra_atts' => ' role="navigation"',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_activity_filter_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= enlightenment_open_tag( 'ul' );

	$output .= enlightenment_open_tag( 'li', 'feed' );
	$output .= sprintf( '<a href="%1$s" title="%2$s">%3$s</a>', bp_get_sitewide_activity_feed_link(), __( 'RSS Feed', 'enlightenment' ), __( 'RSS', 'enlightenment' ) );
	$output .= enlightenment_close_tag( 'li' );

	ob_start();
	/**
	 * Fires before the display of the activity syndication options.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_activity_syndication_options' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( 'li', 'activity-filter last', 'activity-filter-select' );
	$output .= sprintf( '<label for="activity-filter-by" class="bp-screen-reader-text screen-reader-text">%s</label>', __( 'Show:', 'buddypress' ) );
	$output .= enlightenment_open_tag( 'select', '', 'activity-filter-by' );
	$output .= sprintf( '<option value="-1">%s</option>', __( 'Everything', 'enlightenment' ) );

	ob_start();
	bp_activity_show_filters();
	/**
	 * Fires inside the select input for activity filter by options.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_activity_filter_options' );
	$output .= ob_get_clean();
	$output .= enlightenment_close_tag( 'select' );
	$output .= enlightenment_close_tag( 'li' );

	$output .= enlightenment_close_tag( 'ul' );
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_activity_filter', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_group_activity_nav( $args = null ) {
	$defaults = array(
		'container'            => 'nav',
		'container_class'      => 'group-activity-nav item-list-tabs no-ajax',
		'container_id'         => 'subnav',
		'container_extra_atts' => ' role="navigation"',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_group_activity_nav_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= enlightenment_open_tag( 'ul' );

	$output .= enlightenment_open_tag( 'li', 'feed' );
	$output .= sprintf( '<a href="%1$s" title="%2$s">%3$s</a>', bp_get_group_activity_feed_link(), __( 'RSS Feed', 'enlightenment' ), __( 'RSS', 'enlightenment' ) );
	$output .= enlightenment_close_tag( 'li' );

	ob_start();
	/**
	 * Fires inside the syndication options list, after the RSS option.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_group_activity_syndication_options' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( 'li', 'activity-filter last', 'activity-filter-select' );
	$output .= sprintf( '<label for="activity-filter-by" class="bp-screen-reader-text screen-reader-text">%s</label>', __( 'Show:', 'buddypress' ) );
	$output .= enlightenment_open_tag( 'select', '', 'activity-filter-by' );
	$output .= sprintf( '<option value="-1">%s</option>', __( 'Everything', 'enlightenment' ) );

	ob_start();
	bp_activity_show_filters( 'group' );
	/**
	 * Fires inside the select input for group activity filter options.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_group_activity_filter_options' );
	$output .= ob_get_clean();
	$output .= enlightenment_close_tag( 'select' );
	$output .= enlightenment_close_tag( 'li' );

	$output .= enlightenment_close_tag( 'ul' );
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_group_activity_nav', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_get_options_nav_dropdown( $output ) {
	$bp = buddypress();

	// If we are looking at a member profile, then the we can use the current
	// component as an index. Otherwise we need to use the component's root_slug
	$component_index = ! empty( $bp->displayed_user ) ? bp_current_component() : bp_get_root_slug( bp_current_component() );
	$selected_item   = bp_current_action();

	// Default to the Members nav.
	if ( ! bp_is_single_item() ) {
		$secondary_nav_items = $bp->members->nav->get_secondary( array( 'parent_slug' => $component_index ) );

		if ( ! $secondary_nav_items ) {
			return $output;
		}

	// For a single item, try to use the component's nav.
	} else {
		$current_item = bp_current_item();
		$single_item_component = bp_current_component();

		// If the nav is not defined by the parent component, look in the Members nav.
		if ( ! isset( $bp->{$single_item_component}->nav ) ) {
			$secondary_nav_items = $bp->members->nav->get_secondary( array( 'parent_slug' => $current_item ) );
		} else {
			$secondary_nav_items = $bp->{$single_item_component}->nav->get_secondary( array( 'parent_slug' => $current_item ) );
		}

		if ( ! $secondary_nav_items ) {
			return $output;
		}
	}

	$output  = enlightenment_open_tag( 'div', 'options-nav dropdown', 'options-nav-dropdown' );

	// Loop through each navigation item.
	foreach ( $secondary_nav_items as $subnav_item ) {
		if ( empty( $subnav_item->user_has_access ) ) {
			continue;
		}

		// If the current action or an action variable matches the nav item id, then show it first.
		if ( $subnav_item->slug === $selected_item ) {
			$output .= enlightenment_open_tag( 'a', 'dropdown-toggle', '', array(
				'href'           => esc_url( $subnav_item['link'] ),
				'data-bs-toggle' => 'dropdown',
				'data-bs-target' => 'options-nav-dropdown',
				'aria-haspopup'  => 'true',
				'aria-expanded'  => 'false',
			) );
			$output .= $subnav_item['name'];
			$output .= enlightenment_close_tag( 'a' );
		}
	}

	$output .= enlightenment_open_tag( 'ul', 'menu nav dropdown-menu' );

	// Loop through each navigation item
	foreach ( $secondary_nav_items as $subnav_item ) {
		if ( empty( $subnav_item->user_has_access ) ) {
			continue;
		}

		// If the current action or an action variable matches the nav item id, then add a highlight CSS class.
		if ( $subnav_item->slug === $selected_item ) {
			$selected = ' class="current selected"';
		} else {
			$selected = '';
		}

		// List type depends on our current component
		$list_type = bp_is_group() ? 'groups' : 'personal';

		/**
		 * Filters the "options nav", the secondary-level single item navigation menu.
		 *
		 * This is a dynamic filter that is dependent on the provided css_id value.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value         HTML list item for the submenu item.
		 * @param array  $subnav_item   Submenu array item being displayed.
		 * @param string $selected_item Current action.
		 */
		$output .= apply_filters( 'bp_get_options_nav_' . $subnav_item->css_id, '<li id="' . esc_attr( $subnav_item->css_id . '-' . $list_type . '-li' ) . '" ' . $selected . '><a id="' . esc_attr( $subnav_item->css_id ) . '" href="' . esc_url( $subnav_item->link ) . '">' . $subnav_item->name . '</a></li>', $subnav_item, $selected_item );
	}

	$output .= enlightenment_close_tag( 'ul' );
	$output .= enlightenment_close_tag( 'div' );

	return $output;
}
add_filter( 'enlightenment_bp_get_options_nav', 'enlightenment_bp_get_options_nav_dropdown' );

function enlightenment_bp_activity_loop( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'activity',
		'container_id'         => 'activity-stream',
		'container_extra_atts' => array( 'data-bp-list' => 'activity' ),
		'placeholder'          => '<div id="bp-ajax-loader">%s</div>',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_activity_loop_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	if ( false !== strpos( $args['placeholder'], '%s' ) ) {
		ob_start();
		bp_nouveau_user_feedback( 'directory-activity-loading' );
		$feedback = ob_get_clean();

		$args['placeholder'] = sprintf( $args['placeholder'], $feedback );
	}

	$output = '';

	ob_start();
	bp_nouveau_activity_hook( 'before_directory', 'list' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= $args['placeholder'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_activity_loop', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
	return;

	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'activity',
		'container_id'         => '',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_activity_loop_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = '';
	ob_start();
	/**
	 * Fires before the display of the member activities list.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_activity_content' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	ob_start();
	/**
	 * Fires before the start of the activity loop.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_activity_loop' );
	$output .= ob_get_clean();

	if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) {
		if( empty( $_POST['page'] ) ) {
			$output .= enlightenment_open_tag( 'div', 'activity-list item-list', 'activity-stream' );
		}

		while( bp_activities() ) {
			bp_the_activity();

			$output .= enlightenment_bp_activity_entry( array( 'echo' => false ) );
		}

		if( empty( $_POST['page'] ) ) {
			$output .= enlightenment_close_tag( 'div' );
		}
	} else {
		$output .= enlightenment_open_tag( 'div', 'info alert alert-warning', 'message' );
		$output .= __( 'Sorry, there was no activity found. Please try a different filter.', 'buddypress' );
		$output .= enlightenment_close_tag( 'div' );
	}

	ob_start();
	/**
	 * Fires after the finish of the activity loop.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_activity_loop' );
	$output .= ob_get_clean();

	if( empty( $_POST['page'] ) ) {
		$output .= enlightenment_open_tag( 'form', '', 'activity-loop-form', array(
			'name'   => 'activity-loop-form',
			'action' => '',
			'method' => 'post',
		) );
		$output .= wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter', true, false );
		$output .= enlightenment_close_tag( 'form' );
	}

	$output .= enlightenment_close_tag( $args['container'] );

	ob_start();
	/**
	 * Fires after the display of the member activities list.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_activity_content' );
	$output .= ob_get_clean();

	$output  = apply_filters( 'enlightenment_bp_activity_loop', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_single_activity() {
	echo enlightenment_open_tag( 'div', 'activity no-ajax' );

	if ( bp_has_activities( 'display_comments=threaded&show_hidden=true&include=' . bp_current_action() ) ) {
		echo enlightenment_open_tag( 'ul', 'activity-list item-list', 'activity-stream' );

		while ( bp_activities() ) {
			bp_the_activity();
			bp_get_template_part( 'activity/entry' );
		}

		echo enlightenment_close_tag( 'ul' );
	}

	echo enlightenment_close_tag( 'div' );
}

function enlightenment_bp_activity_entry( $args = null ) {
	$defaults = array(
		'container'            => 'article',
		'container_class'      => bp_get_activity_css_class(),
		'container_id'         => sprintf( 'activity-%s', bp_get_activity_id() ),
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_activity_entry_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = '';
	ob_start();
	/**
	 * Fires before the display of an activity entry.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_activity_entry' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	$output .= enlightenment_open_tag( 'div', 'activity-avatar' );
	$output .= enlightenment_open_tag( 'a', '', '', array( 'href' => bp_get_activity_user_link() ) );
	$output .= bp_get_activity_avatar( array(
		'width'  => 64,
		'height' => 64,
	) );
	$output .= enlightenment_close_tag( 'a' );
	$output .= enlightenment_close_tag( 'div' );

	$output .= enlightenment_open_tag( 'div', 'activity-content' );

	$output .= enlightenment_open_tag( 'div', 'activity-header' );
	$output .= bp_get_activity_action();
	$output .= enlightenment_close_tag( 'div' );

	if( bp_activity_has_content() ) {
		$output .= enlightenment_open_tag( 'div', 'activity-inner' );
		$output .= bp_get_activity_content_body();
		$output .= enlightenment_close_tag( 'div' );
	}

	ob_start();
	/**
	 * Fires after the display of an activity entry content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_activity_entry_content' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( 'div', 'activity-meta' );
	if( bp_get_activity_type() == 'activity_comment' ) {
		$output .= enlightenment_open_tag( 'a', 'button view bp-secondary-action', '', array(
			'href'  => bp_get_activity_thread_permalink(),
			'title' => esc_attr( 'View Conversation', 'buddypress' ),
		) );
		$output .= __( 'View Conversation', 'buddypress' );
		$output .= enlightenment_close_tag( 'a' );
	}

	if( is_user_logged_in() ) {
		if( bp_activity_can_favorite() ) {
			if( bp_get_activity_is_favorite() ) {
				$output .= enlightenment_open_tag( 'a', 'button unfav bp-secondary-action', '', array(
					'href'  => bp_get_activity_unfavorite_link(),
					'title' => esc_attr( 'Unlike', 'enlightenment' ),
				) );
				$output .= '<i class="fa fa-heart"></i> ';
				$output .= __( 'Unlike', 'enlightenment' );
				$output .= enlightenment_close_tag( 'a' );
			} else {
				$output .= enlightenment_open_tag( 'a', 'button fav bp-secondary-action', '', array(
					'href'  => bp_get_activity_favorite_link(),
					'title' => esc_attr( 'Like', 'enlightenment' ),
				) );
				$output .= '<i class="fa fa-heart"></i> ';
				$output .= __( 'Like', 'enlightenment' );
				$output .= enlightenment_close_tag( 'a' );
			}
		}

		if( bp_activity_can_comment() ) {
			$output .= enlightenment_open_tag( 'a', 'button acomment-reply bp-primary-action', sprintf( 'acomment-comment-%s', bp_get_activity_id() ), array(
				'href'  => bp_get_activity_comment_link(),
				'title' => esc_attr( 'View Conversation', 'buddypress' ),
			) );
			$output .= '<i class="fa fa-comment"></i> ';
			$output .= sprintf( __( 'Comment %s', 'buddypress' ), '<span>' . bp_activity_get_comment_count() . '</span>' );
			$output .= enlightenment_close_tag( 'a' );
		}

		if( bp_activity_user_can_delete() ) {
			$output .= bp_get_activity_delete_link();
		}

		ob_start();
		/**
		 * Fires at the end of the activity entry meta data area.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_activity_entry_meta' );
		$output .= ob_get_clean();
	}
	$output .= enlightenment_close_tag( 'div' );

	$output .= enlightenment_close_tag( 'div' );



	$output .= enlightenment_close_tag( $args['container'] );

	ob_start();
	/**
	 * Fires after the display of an activity entry.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_activity_entry' );
	$output .= ob_get_clean();

	$output  = apply_filters( 'enlightenment_bp_activity_entry', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_members_loop( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'members dir-list',
		'container_id'         => 'members-dir-list',
		'container_extra_atts' => array( 'data-bp-list' => 'members' ),
		'placeholder'          => '<div id="bp-ajax-loader">%s</div>',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_members_loop_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	if ( false !== strpos( $args['placeholder'], '%s' ) ) {
		ob_start();
		bp_nouveau_user_feedback( 'directory-members-loading' );
		$feedback = ob_get_clean();

		$args['placeholder'] = sprintf( $args['placeholder'], $feedback );
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= $args['placeholder'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_members_loop', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_groups_loop( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'groups dir-list',
		'container_id'         => 'groups-dir-list',
		'container_extra_atts' => array( 'data-bp-list' => 'groups' ),
		'placeholder'          => '<div id="bp-ajax-loader">%s</div>',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_groups_loop_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	if ( false !== strpos( $args['placeholder'], '%s' ) ) {
		ob_start();
		bp_nouveau_user_feedback( 'directory-groups-loading' );
		$feedback = ob_get_clean();

		$args['placeholder'] = sprintf( $args['placeholder'], $feedback );
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= $args['placeholder'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_groups_loop', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_blogs_loop( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'blogs dir-list',
		'container_id'         => 'blogs-dir-list',
		'container_extra_atts' => array( 'data-bp-list' => 'blogs' ),
		'placeholder'          => '<div id="bp-ajax-loader">%s</div>',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_blogs_loop_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	if ( false !== strpos( $args['placeholder'], '%s' ) ) {
		ob_start();
		bp_nouveau_user_feedback( 'directory-blogs-loading' );
		$feedback = ob_get_clean();

		$args['placeholder'] = sprintf( $args['placeholder'], $feedback );
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= $args['placeholder'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_blogs_loop', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_blog_create_form( $args = null ) {
	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_blog_create_form_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	if ( bp_blog_signup_enabled() ) {
		bp_show_blog_signup_form();
	} else {
		bp_nouveau_user_feedback( 'blogs-no-signup' );
	}
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_bp_blog_create_form', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_profile_loop( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'profile',
		'container_id'         => '',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_profile_loop_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = '';
	ob_start();
	/**
	 * Fires before the display of member profile content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_profile_content' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	ob_start();
	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_before_profile_loop_content' );
	$output .= ob_get_clean();

	if( bp_has_profile() ) {
		while( bp_profile_groups() ) {
			bp_the_profile_group();

			if( bp_profile_group_has_fields() ) {
				$output .= enlightenment_bp_profile_group( array( 'echo' => false ) );
			}
		}

		ob_start();
		/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
		do_action( 'bp_profile_field_buttons' );
		$output .= ob_get_clean();
	}

	ob_start();
	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_after_profile_loop_content' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	ob_start();
	/**
	 * Fires after the display of member profile content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_profile_content' );
	$output .= ob_get_clean();

	$output  = apply_filters( 'enlightenment_bp_profile_loop', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_user_profile( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'item-body',
		'container_id'         => 'item-body',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_user_profile_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	ob_start();
	bp_get_template_part( 'members/single/profile' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_user_profile', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_user_friends( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => sprintf( 'user-friends-body%s item-body', bp_is_user_friend_requests() ? ' user-friend-requests-body' : '' ),
		'container_id'         => 'item-body',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_user_friends_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	ob_start();
	bp_get_template_part( 'members/single/friends' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_user_friends', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_user_groups( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'user-groups-body item-body',
		'container_id'         => 'item-body',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_user_groups_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	ob_start();
	bp_get_template_part( 'members/single/groups' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_user_groups', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_user_blogs( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'user-blogs-body item-body',
		'container_id'         => 'item-body',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_user_blogs_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	ob_start();
	bp_get_template_part( 'members/single/blogs' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_user_blogs', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_user_messages( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'user-messages-body item-body',
		'container_id'         => 'item-body',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_user_messages_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	ob_start();
	bp_get_template_part( 'members/single/messages' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_user_messages', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_user_notifications( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'user-notifications-body item-body',
		'container_id'         => 'item-body',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_user_notifications_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	ob_start();
	bp_get_template_part( 'members/single/notifications' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_user_notifications', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_user_settings( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'user-settings-body item-body',
		'container_id'         => 'item-body',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_user_settings_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	ob_start();
	bp_get_template_part( 'members/single/settings' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_user_settings', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_user_plugins( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => 'user-plugins-body item-body',
		'container_id'         => 'item-body',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_user_plugins_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	ob_start();
	bp_get_template_part( 'members/single/plugins' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_user_plugins', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_register_form( $args = null ) {
	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_register_form_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bp_get_template_part( 'members/register' );
	$output = ob_get_clean();

	$output  = apply_filters( 'enlightenment_bp_register_form', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_activate_page( $args = null ) {
	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_activate_page_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bp_get_template_part( 'members/activate' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_bp_activate_page', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_profile_group( $args = null ) {
	$defaults = array(
		'container'            => 'div',
		'container_class'      => sprintf( 'bp-widget %s', bp_get_the_profile_group_slug() ),
		'container_id'         => '',
		'container_extra_atts' => '',
		'echo'                 => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_profile_group_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = '';
	ob_start();
	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_before_profile_field_content' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	$output .= enlightenment_open_tag( 'h4', 'profile-group-name' );
	$output .= bp_get_the_profile_group_name();
	$output .= enlightenment_close_tag( 'h4' );

	$output .= enlightenment_open_tag( 'div', 'profile-fields' );

	while( bp_profile_fields() ) {
		bp_the_profile_field();

		if( bp_field_has_data() ) {
			$output .= enlightenment_open_tag( 'div', bp_get_field_css_class() );

			$output .= enlightenment_open_tag( 'div', 'label' );
			$output .= bp_get_the_profile_field_name();
			$output .= enlightenment_close_tag( 'div' );

			$output .= enlightenment_open_tag( 'div', 'data' );
			$output .= bp_get_the_profile_field_value();
			$output .= enlightenment_close_tag( 'div' );

			$output .= enlightenment_close_tag( 'div' );
		}

		ob_start();
		/**
		 * Fires after the display of a field table row for profile data.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_profile_field_item' );
		$output .= ob_get_clean();
	}

	$output .= enlightenment_close_tag( 'div' );

	$output .= enlightenment_close_tag( $args['container'] );

	ob_start();
	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_after_profile_field_content' );
	$output .= ob_get_clean();

	$output  = apply_filters( 'enlightenment_bp_profile_group', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_sitewide_notices( $args = null ) {
	// Do not show notices if user is not logged in.
	if ( ! is_user_logged_in() ) {
		return;
	}

	$defaults = array(
		'container'       => 'div',
		'container_class' => 'sitewide-notice',
		'container_id'    => 'sitewide-notice',
		'echo'            => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_sitewide_notices_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	// Add a class to determine if the admin bar is on or not.
	$args['container_class'] .= did_action( 'admin_bar_menu' ) ? ' admin-bar-on' : ' admin-bar-off';

	ob_start();
	bp_message_get_notices();
	$notices = ob_get_clean();

	if ( empty( $notices ) ) {
		return;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= $notices;
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_sitewide_notices', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bp_restricted_access_message( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'bp-restricted-access-message',
		'container_id'    => 'bp-restricted-access-message',
		'message'         => __( 'This community area is accessible to logged-in members only.', 'enlightenment' ),
		'show_login_form' => true,
		'echo'            => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_restricted_access_message_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	$output .= wpautop( esc_html( $args['message'] ) );

	if ( $args['show_login_form'] ) {
		$output .= do_blocks( '<!-- wp:bp/login-form {"forgotPwdLink":true} /-->' );
	}

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bp_restricted_access_message', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
