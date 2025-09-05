<?php

function enlightenment_tribe_template() {
    global $enlightenment_tribe_template;

    if ( ! isset( $enlightenment_tribe_template ) ) {
        if ( isset( $GLOBALS['tribe_template'] ) ) {
            return $GLOBALS['tribe_template'];
        } else {
			_doing_it_wrong( __FUNCTION__, __( 'Requested template was not initialized.', 'enlightenment' ), '2.0.0' );
			return null;
        }
    }

    return $enlightenment_tribe_template;
}

function enlightenment_tribe_view() {
	global $enlightenment_tribe_view;

	if ( ! isset( $enlightenment_tribe_view ) ) {
		try {
			$template = enlightenment_tribe_template();
		} catch ( Exception $e ) {
			_doing_it_wrong( __FUNCTION__, __( 'Requested view was not initialized.', 'enlightenment' ), '2.0.0' );
			return null;
		}

		return $template->get_view();
	}

    return $enlightenment_tribe_view;
}

function enlightenment_tribe_get_view() {
    if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
		try {
			$template = enlightenment_tribe_template();
		} catch ( Exception $e ) {
			$template = null;
		}

		if ( $template instanceof Tribe\Events\Views\V2\Template ) {
	        $context   = $template->get_values();
	        $view_slug = $context['slug'];
		} else {
			$view_slug = 'default';
		}
    } else {
        $query     = tribe_get_global_query_object();
    	$context   = tribe_context();
    	$view_slug = $context->get( 'view' );
		$post_type = $context->get( 'post_type', $view_slug );

		if ( ! is_array( $post_type ) ) {
			if ( 'tribe_event_series' == $post_type ) {
				/**
				 * Allows filtering the default View that should be used to display the
				 * list of Events related to a Series.
				 *
				 * @since 6.0.0
				 *
				 * @param string $default The default view slug, e.g. `summary` or `list`.
				 */
				$default       = apply_filters( 'tec_events_pro_custom_tables_v1_series_default_view', 'summary' );
				$event_display = get_query_var( 'eventDisplay', $default );
				$view_slug     = apply_filters( 'tec_events_pro_custom_tables_v1_series_event_view_slug', $event_display );
			} else {
		    	$view_slug = apply_filters( 'tribe_events_views_v2_bootstrap_view_slug', $view_slug, $context, $query );
			}
		}
    }

    switch ( $view_slug ) {
        case 'default':
			$view_slug = tribe( Tribe\Events\Views\V2\Manager::class )->get_default_view_option();

			if ( 'default' == $view_slug ) {
				$view_slug = tribe_get_option( 'viewOption', 'list' );
			}

            break;

        case 'all':
        case 'venue':
        case 'organizer':
            $view_slug = 'list';

            break;

		default:
			if ( class_exists( $view_slug ) ) {
				$view = new $view_slug;

				try {
					$view_slug = $view->get_view_slug();
				} catch ( Exception $e ) {
					$view_slug = tribe_get_option( 'viewOption', 'list' );
				}
			}
    }

    return $view_slug;
}

function enlightenment_tribe_event() {
    global $enlightenment_tribe_event;

    if ( ! isset( $enlightenment_tribe_event ) ) {
        _doing_it_wrong( __FUNCTION__, __( 'Events can only be requestsed while in the loop.', 'enlightenment' ), '2.0.0' );
        return null;
    }

    return $enlightenment_tribe_event;
}

function enlightenment_tribe_is_ar_page() {
    if ( ! class_exists( 'Tribe__Tickets__Attendee_Registration__Main' ) ) {
        return false;
    }

    try {
        /** @var \Tribe__Tickets__Attendee_Registration__Main $ar_reg */
        $ar_reg = tribe( 'tickets.attendee_registration' );

        $is_on_ar_page = $ar_reg->is_on_page();
    } catch ( \Exception $exception ) {
        $is_on_ar_page = false;
    }

    return $is_on_ar_page;
}

function enlightenment_tribe_is_community_my_events_page() {
    if ( ! function_exists( 'tribe_is_community_my_events_page' ) ) {
        return false;
    }

    return tribe_is_community_my_events_page();
}

function enlightenment_tribe_is_community_edit_event_page() {
    if ( ! function_exists( 'tribe_is_community_edit_event_page' ) ) {
        return false;
    }

    return tribe_is_community_edit_event_page();
}

function enlightenment_tribe_community_events_is_organizer_edit_screen() {
    if ( ! function_exists( 'tribe_community_events_is_organizer_edit_screen' ) ) {
        return false;
    }

    return tribe_community_events_is_organizer_edit_screen();
}

function enlightenment_tribe_community_events_is_venue_edit_screen() {
    if ( ! function_exists( 'tribe_community_events_is_venue_edit_screen' ) ) {
        return false;
    }

    return tribe_community_events_is_venue_edit_screen();
}

function enlightenment_tribe_community_events_is_community_event() {
    if ( ! function_exists( 'tribe_community_events_is_community_event' ) ) {
        return false;
    }

    return tribe_community_events_is_community_event();
}

function enlightenment_tribe_community_tickets_is_frontend_attendees_report() {
    if ( ! function_exists( 'tribe_community_tickets_is_frontend_attendees_report' ) ) {
        return false;
    }

    return tribe_community_tickets_is_frontend_attendees_report();
}

function enlightenment_tribe_community_tickets_is_frontend_sales_report() {
    if ( ! function_exists( 'tribe_community_tickets_is_frontend_sales_report' ) ) {
        return false;
    }

    return tribe_community_tickets_is_frontend_sales_report();
}

function enlightenment_tribe_community_tickets_is_payment_options() {
    $wp_route = get_query_var( 'WP_Route' );

    return ! empty( $wp_route ) && 'edit-payment-options-route' === $wp_route;
}
