<?php

function enlightenment_tribe_maybe_switch_v2_filterbar_hooks() {
    if ( ! class_exists( 'Tribe\Events\Filterbar\Views\V2_1\Hooks' ) ) {
        return;
    }

    try {
        $class = tribe( 'filterbar.views.v2_1.hooks' );
    } catch ( Exception $e ) {
        return;
    }

    remove_action(
        'tribe_template_before_include:events/v2/components/events-bar/views',
        array( $class, 'action_include_filter_button' ),
        10, 3
    );
    remove_action(
        'tribe_template_after_include:events/v2/components/filter-bar',
        array( $class, 'action_include_filter_bar' ),
        10, 3
    );
    remove_action(
        'tribe_template_after_include:events/v2/components/events-bar',
        array( $class, 'action_include_horizontal_filter_bar' ),
        10, 3
    );

    add_action(
        'tribe_template_before_include:events/v2/components/events-bar/views',
        'enlightenment_events_toggle_filter_bar_button',
        10, 3
    );
}
add_action( 'init', 'enlightenment_tribe_maybe_switch_v2_filterbar_hooks' );

function enlightenment_tec_events_views_v2_after_get_events( $events, $view ) {
	global $wp_query;

	if (
		! empty( $wp_query->posts )
		&&
		is_numeric( reset( $wp_query->posts ) )
		&&
		'ids' != $wp_query->get( 'fields' )
	) {
		if ( ! is_array( $events ) ) {
			$events = array( $events );
		}

		if (
			- 1 !== $view->get_context()->get( 'events_per_page', 12 )
			&&
			$view->has_next_event( $events )
		) {
			array_pop( $events );
			$wp_query->post_count = $wp_query->post_count - 1;
		}

		$wp_query->posts = $events;
	}
}
add_action( 'tec_events_views_v2_after_get_events', 'enlightenment_tec_events_views_v2_after_get_events', 10, 2 );

function enlightenment_tribe_maybe_remove_template_include_filter() {
    if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
        return;
    }

    remove_filter( 'template_include', [ tribe( Tribe\Events\Views\V2\Hooks::class ), 'filter_template_include' ], 50 );
}
// add_action( 'template_redirect', 'enlightenment_tribe_maybe_remove_template_include_filter' );

function enlightenment_tribe_load_template() {
	if ( is_404() ) {
		return;
	}

	if ( is_feed() ) {
		return;
	}

	if ( is_singular( 'tribe_events' ) ) {
		return;
	}

	if ( is_single() && post_password_required() ) {
		return;
	}

	$query = tribe_get_global_query_object();

	if ( ! $query instanceof WP_Query ) {
		return;
	}

	if ( ! $query->is_main_query() ) {
		return;
	}

	if (
		empty( $query->tribe_is_event_query )
		&&
		(
			empty( $query->tribe_controller )
			||
			empty( $query->query['post_type'] )
			||
			'tribe_event_series' != $query->query['post_type']
		)
	) {
		return;
	}

	if (
		tribe_get_request_var( 'tec-tickets-wallet-plus-pdf' )
		||
		tribe_get_request_var( 'apple-wallet-pass' )
	) {
		return;
	}

	$manager = tribe( Tribe\Events\Views\V2\Manager::class );

	if ( 'tribe_event_series' == $query->query['post_type'] ) {
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

		$context = tribe_context()->alter(
			array(
				'event_display'      => $event_display,
				'event_display_mode' => $event_display,
			)
		);

		Tribe\Events\Views\V2\View::set_container( tribe() );

		$view = Tribe\Events\Views\V2\View::make( $manager->get_view_class_by_slug( $view_slug ), $context );
		$view->disable_url_management();
		$view->set_context( $view->get_context()->alter( array(
			'related_series' => get_queried_object_id(),
		) ) );

		add_filter( 'tribe_events_views_v2_assets_should_enqueue_frontend', '__return_true' );
		add_filter( 'tribe_events_pro_views_v2_assets_should_enqueue_frontend', '__return_true' );
	} else {
		$context   = tribe_context();

		$view_slug = $context->get( 'view' );
		$view_slug = apply_filters( 'tribe_events_views_v2_bootstrap_view_slug', $view_slug, $context, $query );

		$view      = Tribe\Events\Views\V2\View::make( $manager->get_view_class_by_slug( $view_slug ), $context );

		$GLOBALS['enlightenment_tribe_view'] = $view;

		add_filter( 'tribe_events_views_v2_bootstrap_pre_should_load', '__return_true' );
	}

	echo $view->get_html();
	die();
}
add_action( 'template_redirect', 'enlightenment_tribe_load_template' );

function enlightenment_tribe_maybe_redirect_ar_page() {
    if ( ! enlightenment_tribe_is_ar_page() ) {
        return;
    }

    if (
		( ! isset( $_GET['tribe_tickets_post_id'] ) || empty( $_GET['tribe_tickets_post_id'] ) )
		&&
		( ! isset( $_GET['tickets_provider'] ) || empty( $_GET['tickets_provider'] ) )
	) {
        wp_safe_redirect( get_post_type_archive_link( 'tribe_events' ) );
        exit();
    }
}
add_action( 'template_redirect', 'enlightenment_tribe_maybe_redirect_ar_page' );

function enlightenment_tribe_filter_all_event_recurrences_request( $query ) {
    if ( ! $query->is_main_query() ) {
        return;
    }

    if ( 'all' != $query->get( 'eventDisplay' ) ) {
        return;
    }

    if ( 'tribe_events' != $query->get( 'post_type' ) ) {
        return;
    }

    $query->is_single   = false;
	$query->is_singular = false;

	$query->is_post_type_archive = true;
}
add_action( 'pre_get_posts', 'enlightenment_tribe_filter_all_event_recurrences_request' );

function enlightenment_tribe_front_page_query( $query ) {
	global $wp, $wp_query;

	if ( ! tribe_is_events_front_page() ) {
		return;
	}

	if ( ! empty( $wp->request ) || ! empty( $wp->query_vars ) ) {
		return;
	}

	$wp_query->is_404  = false;
	$wp_query->is_home = false;

	$wp_query->page_id = 0;
	$wp_query->post_type = Tribe__Events__Main::POSTTYPE;
	$wp_query->is_post_type_archive = true;
	$wp_query->eventDisplay = 'default';
	$wp_query->tribe_events_front_page = true;

	$wp_query->is_page     = false;
	$wp_query->is_singular = false;
}
add_action( 'wp', 'enlightenment_tribe_front_page_query', 8 );

function enlightenment_tribe_rest_query( $query ) {
    if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
        $meta_query = $query->get('meta_query');

        if ( isset( $meta_query['_eventvenueid_in'] ) ) {
            $template = enlightenment_tribe_template();
            $view     = $template->get_view();

            if ( $view instanceof Tribe\Events\Pro\Views\V2\Views\Venue_View ) {
				$post_id = $view->get_post_id();

				if ( is_array( $post_id ) ) {
					$post_id = array_shift( $post_id );
				}

                $venue   = tribe_get_venue_object( $post_id );

                $query->is_archive           = false;
                $query->is_post_type_archive = false;
                $query->is_single            = true;
                $query->is_singular          = true;

                $query->queried_object->post_type = 'tribe_venue';
                $query->queried_object->ID        = $venue->ID;
            }
        } /*elseif ( isset( $meta_query['_eventorganizerid_in'] ) ) {
            $template = enlightenment_tribe_template();
            $view     = $template->get_view();

            if ( $view instanceof Tribe\Events\PRO\Views\V2\Views\Organizer_View ) {
				$post_id = $view->get_post_id();

				if ( is_array( $post_id ) ) {
					$post_id = array_shift( $post_id );
				}

                $organizer = tribe_get_organizer_object( $post_id );

                $query->is_archive           = false;
                $query->is_post_type_archive = false;
                $query->is_single            = true;
                $query->is_singular          = true;

                $query->queried_object->post_type = 'tribe_organizer';
                $query->queried_object->ID        = $organizer->ID;
            }
        }*/
    }
}
add_action( 'wp', 'enlightenment_tribe_rest_query', 8 );

function enlightenment_tribe_reset_rest_query( $query ) {
    if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
        $meta_query = $query->get('meta_query');

        if ( isset( $meta_query['_eventvenueid_in'] ) ) {
            $template = enlightenment_tribe_template();
            $view     = $template->get_view();

            if ( $view instanceof Tribe\Events\Pro\Views\V2\Views\Venue_View ) {
				$post_id = $view->get_post_id();

				if ( is_array( $post_id ) ) {
					$post_id = array_shift( $post_id );
				}

                $venue   = tribe_get_venue_object( $post_id );

                $query->is_archive           = true;
                $query->is_post_type_archive = true;
                $query->is_single            = false;
                $query->is_singular          = false;

                unset( $query->queried_object->post_type );
                unset( $query->queried_object->ID );
            }
        }
    }
}
add_action( 'wp', 'enlightenment_tribe_reset_rest_query', 12 );

function enlightenment_tribe_template_before_include( $file, $name, $template ) {
    global $wp_query;

    $GLOBALS['enlightenment_tribe_template'] = $template;

    $name = join( '/', $name );

    switch ( $name ) {
        case 'list':
        case 'day':
		case 'summary':
        case 'photo':
            if ( empty( $wp_query->query['s'] ) ) {
                $wp_query->is_search = false;
            }

            $posts_per_page = tribe_get_option( 'postsPerPage' );

            if ( isset( $wp_query->posts ) ) {
                if ( count( $wp_query->posts ) > $posts_per_page ) {
                    $wp_query->posts_per_page = $posts_per_page;
                    $wp_query->posts          = array_slice( $wp_query->posts, 0, $posts_per_page );
                    $wp_query->found_posts    = count( $wp_query->posts );
                    $wp_query->post_count     = count( $wp_query->posts );
                }

                foreach ( $wp_query->posts as $key => $post ) {
                    if ( is_numeric( $post ) ) {
                        $wp_query->posts[ $key ] = get_post( $post );
                    }
                }
            }

            if ( 'day' == $name && empty( $wp_query->get( 'start_date' ) ) ) {
				$query_args = enlightenment_tribe_view()->get_url_object()->get_query_args();

				if ( ! isset( $query_args['eventDate'] ) && isset( $query_args['pagename'] ) ) {
					if ( false !== strpos( $query_args['pagename'], '/' ) ) {
						$endpoint = explode( '/', $query_args['pagename'] );

						if (
							$endpoint[0] == $view->get_view_slug()
							&&
							isset( $endpoint[1] )
							&&
							false !== strtotime( $endpoint[1] )
						) {
							$query_args['eventDate'] = date_i18n( Tribe__Date_Utils::DBDATETIMEFORMAT, strtotime( $endpoint[1] ) );
						}
					} elseif ( false !== strtotime( $query_args['pagename'] ) ) {
						$query_args['eventDate'] = date_i18n( Tribe__Date_Utils::DBDATETIMEFORMAT, strtotime( $query_args['pagename'] ) );
					}
			    }

                $event_date  = ! empty( $_REQUEST['eventDate'] ) ? $_REQUEST['eventDate'] : date( 'Y-m-d', current_time( 'timestamp' ) );
                $beginning_of_day = strtotime( tribe_beginning_of_day( $event_date ) );
                $wp_query->set( 'start_date', date_i18n( Tribe__Date_Utils::DBDATETIMEFORMAT, $beginning_of_day ) );
            }

            break;

        case 'month':
            $wp_query->is_archive           = true;
            $wp_query->is_post_type_archive = true;
            $wp_query->is_month             = true;
            break;
    }

    if ( is_tax( 'tribe_events_cat' ) ) {
        $wp_query->is_post_type_archive = false;
    }

    // Fix compatibility with Categories Images plugin.
    if ( ( is_tax( 'tribe_events_cat' ) || 'tribe_events_cat' == get_query_var( 'taxonomy' ) ) && empty( get_query_var( 'term' ) ) ) {

        $queried_object = get_queried_object();

        if ( $queried_object instanceof WP_Term ) {
            $wp_query->set( 'term', $queried_object->slug );
        } else {
            $tax_query = get_query_var('tax_query');

            if ( isset( $tax_query['Tribe__Events__Filterbar__Filters__Category'] ) ) {
                $term_ids = array_unique( $tax_query['Tribe__Events__Filterbar__Filters__Category'][0]['terms'] );

                if ( 1 == count( $term_ids ) ) {
                    $term = get_term( $term_ids[0] );

                    $wp_query->set( 'term', $term->slug );
                }
            }
        }

        if ( empty( get_query_var( 'taxonomy' ) ) ) {
            $wp_query->set( 'taxonomy', 'tribe_events_cat' );
        }
    }

    if ( null === $wp_query->query ) {
        $wp_query->is_archive           = true;
        $wp_query->is_post_type_archive = true;
        $wp_query->is_search            = true;
    }

	$context   = $template->get_view()->get_context();
	$widget    = $context->get( 'is-widget', false );
	$shortcode = $context->get( 'shortcode', false );

	if (
		defined( 'REST_REQUEST' ) && true === REST_REQUEST
		&&
		! $widget
		&&
		! $shortcode
	) {
        do_action_ref_array( 'wp', array( &$wp_query ) );
    }
}
add_action( 'tribe_template_before_include:events/v2/list',      'enlightenment_tribe_template_before_include', 999, 3 );
add_action( 'tribe_template_before_include:events/v2/month',     'enlightenment_tribe_template_before_include', 999, 3 );
add_action( 'tribe_template_before_include:events/v2/day',       'enlightenment_tribe_template_before_include', 999, 3 );
add_action( 'tribe_template_before_include:events/v2/summary',   'enlightenment_tribe_template_before_include', 999, 3 );
add_action( 'tribe_template_before_include:events/v2/week',      'enlightenment_tribe_template_before_include', 999, 3 );
add_action( 'tribe_template_before_include:events/v2/photo',     'enlightenment_tribe_template_before_include', 999, 3 );
add_action( 'tribe_template_before_include:events-pro/v2/photo', 'enlightenment_tribe_template_before_include', 999, 3 );
add_action( 'tribe_template_before_include:events/v2/map',       'enlightenment_tribe_template_before_include', 999, 3 );
add_action( 'tribe_template_before_include:events-pro/v2/map',   'enlightenment_tribe_template_before_include', 999, 3 );

function enlightenment_tribe_template_after_include( $file, $name ) {
    unset( $GLOBALS['enlightenment_tribe_template'] );
}
add_action( 'tribe_template_after_include:events/v2/list',      'enlightenment_tribe_template_after_include', 1, 2 );
add_action( 'tribe_template_after_include:events/v2/month',     'enlightenment_tribe_template_after_include', 1, 2 );
add_action( 'tribe_template_after_include:events/v2/summary',   'enlightenment_tribe_template_after_include', 1, 2 );
add_action( 'tribe_template_after_include:events/v2/day',       'enlightenment_tribe_template_after_include', 1, 2 );
add_action( 'tribe_template_after_include:events/v2/week',      'enlightenment_tribe_template_after_include', 1, 2 );
add_action( 'tribe_template_after_include:events/v2/photo',     'enlightenment_tribe_template_after_include', 1, 2 );
add_action( 'tribe_template_after_include:events-pro/v2/photo', 'enlightenment_tribe_template_after_include', 1, 2 );
add_action( 'tribe_template_after_include:events/v2/map',       'enlightenment_tribe_template_after_include', 1, 2 );
add_action( 'tribe_template_after_include:events-pro/v2/map',   'enlightenment_tribe_template_after_include', 1, 2 );

function enlightenment_tribe_setup_event() {
    if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
        return;
    }

    global $enlightenment_post_counter;

	$context = enlightenment_tribe_template()->get_values();
    $current = $enlightenment_post_counter - 1;

    $GLOBALS['enlightenment_tribe_event'] = $context['events'][ $current ];

    if ( isset( $_GET['eventDisplay'] ) && 'past' == $_GET['eventDisplay'] ) {
        $GLOBALS['post'] = $context['events'][ $current ];
    }
}
add_action( 'enlightenment_before_entry', 'enlightenment_tribe_setup_event', 1 );

function enlightenment_tribe_reset_event() {
    unset( $GLOBALS['enlightenment_tribe_event'] );
}
add_action( 'enlightenment_after_entry', 'enlightenment_tribe_reset_event', 999 );

add_action( 'tribe_events_single_event_after_the_meta', 'enlightenment_ob_start', 2 );

function enlightenment_tribe_filter_link_template() {
    $output = ob_get_clean();

    $start  = strpos( $output, '<div class="tribe-link-view-attendee">' );

    if ( false === $start ) {
        echo $output;

		return;
    }

    $end     = strpos( $output, '</div>', $start ) + 6;
    $length  = $end - $start;
    $content = substr( $output, $start, $length );

    $content = apply_filters( 'enlightenment_tribe_filter_link_template', $content );

    $output  = substr_replace( $output, $content, $start, $length );

    echo $output;
}
add_action( 'tribe_events_single_event_after_the_meta', 'enlightenment_tribe_filter_link_template', 6 );

function enlightenment_tribe_filter_rsvp_template_hooks( $instance ) {
    $ticket_form_hook = tribe( 'tickets.rsvp' )->get_ticket_form_hook();

    if ( ! empty( $ticket_form_hook ) ) {
        add_action( $ticket_form_hook, 'enlightenment_ob_start', 3 );
        add_action( $ticket_form_hook, 'enlightenment_tribe_filter_rsvp_template', 7 );
    }

}
add_action( 'tribe_tickets_tickets_hook', 'enlightenment_tribe_filter_rsvp_template_hooks' );

add_action( 'event_tickets_rsvp_after_ticket_row', 'enlightenment_ob_start', 8 );

function enlightenment_tribe_filter_rsvp_front_end_meta_fields( $post, $ticket ) {
    $output = ob_get_clean();

    if ( false === strpos( $output, 'class="tribe-event-tickets-plus-meta"' ) ) {
        echo $output;

		return;
    }

    $output = apply_filters( 'enlightenment_tribe_filter_rsvp_front_end_meta_fields', $output, $post, $ticket );

    echo $output;
}
add_action( 'event_tickets_rsvp_after_ticket_row', 'enlightenment_tribe_filter_rsvp_front_end_meta_fields', 12, 2 );

function enlightenment_tribe_filter_rsvp_template() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_tribe_filter_rsvp_template', $output );
    echo $output;
}

add_action( 'tribe_events_community_form', 'enlightenment_ob_start', 0 );

function enlightenment_tribe_filter_events_community_form() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_tribe_filter_events_community_form', $output );
    echo $output;
}
add_action( 'tribe_events_community_form', 'enlightenment_tribe_filter_events_community_form', 999 );

add_action( 'tribe_tickets_attendees_page_inside', 'enlightenment_ob_start', 0 );

function enlightenment_tribe_filter_tickets_attendees_page_inside() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_tribe_filter_tickets_attendees_page_inside', $output );
    echo $output;
}
add_action( 'tribe_tickets_attendees_page_inside', 'enlightenment_tribe_filter_tickets_attendees_page_inside', 999 );

add_action( 'tribe_tickets_before_front_end_ticket_form', 'enlightenment_ob_start', 2 );

function enlightenment_tribe_filter_attendees_list() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_tribe_filter_attendees_list', $output );
    echo $output;
}
add_action( 'tribe_tickets_before_front_end_ticket_form', 'enlightenment_tribe_filter_attendees_list', 6 );

add_action( 'woocommerce_checkout_before_order_review', 'enlightenment_ob_start', 8 );

function enlightenment_tribe_filter_checkout_links() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_tribe_filter_checkout_links', $output );
    echo $output;
}
add_action( 'woocommerce_checkout_before_order_review', 'enlightenment_tribe_filter_checkout_links', 12 );

add_action( 'tribe_community_before_login_form', 'enlightenment_ob_start', 0 );

add_action( 'woocommerce_order_item_meta_start', 'enlightenment_ob_start', 98 );

function enlightenment_tribe_filter_woocommerce_event_info() {
	$output = ob_get_clean();

	$has_event_details = false;
	$has_attendee_meta = false;

	$start  = strpos( $output, '<div class="tribe-event-details">' );
    if ( false !== $start ) {
		$has_event_details = true;

		$end     = strpos( $output, '</div>', $start ) + 6;
	    $length  = $end - $start;
	    $content = substr( $output, $start, $length );
	    $content = apply_filters( 'enlightenment_tribe_filter_woocommerce_event_details', $content );
	    $output  = substr_replace( $output, $content, $start, $length );
    }

	$start  = strpos( $output, '<table class="tribe-attendee-meta">' );
    if ( false !== $start ) {
		$has_event_details = true;

		$end     = strpos( $output, '</table>', $start ) + 8;
	    $length  = $end - $start;
	    $content = substr( $output, $start, $length );
	    $content = apply_filters( 'enlightenment_tribe_filter_woocommerce_attendee_meta', $content );
	    $output  = substr_replace( $output, $content, $start, $length );
    }

	if ( $has_event_details || $has_attendee_meta ) {
		$output = apply_filters( 'enlightenment_tribe_filter_woocommerce_event_info', $output );
	}

    echo $output;
}
add_action( 'woocommerce_order_item_meta_start', 'enlightenment_tribe_filter_woocommerce_event_info', 102 );

function enlightenment_tribe_filter_login_form() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_tribe_filter_login_form', $output );
    echo $output;
}
add_action( 'tribe_community_after_login_form', 'enlightenment_tribe_filter_login_form', 999 );

function enlightenment_tribe_maybe_fix_the_query() {
    if (
        ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) &&
        ( null === $GLOBALS['wp_the_query']->query ) &&
        (
            is_active_widget( false, false, 'tribe-events-list-widget', true ) ||
            is_active_widget( false, false, 'tribe-events-adv-list-widget', true ) ||
            is_active_widget( false, false, 'tribe-mini-calendar', true )
        )
    ) {
        $GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];
    }
}
add_action( 'enlightenment_before_widgets', 'enlightenment_tribe_maybe_fix_the_query', 999 );

add_action( 'tribe_events_before_list_widget', 'enlightenment_ob_start' );

function enlightenment_tribe_filter_list_widget() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_tribe_filter_list_widget', $output );
    echo $output;
}
add_action( 'tribe_events_after_list_widget', 'enlightenment_tribe_filter_list_widget' );

add_action( 'tribe_events_venue_widget_after_the_title', 'enlightenment_ob_start' );

function enlightenment_tribe_filter_venue_widget( $jsonld_enable ) {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_tribe_filter_venue_widget', $output );
    echo $output;

    return $jsonld_enable;
}
add_filter( 'tribe_events_tribe-events-venue-widget_jsonld_enabled', 'enlightenment_tribe_filter_venue_widget' );

add_action( 'tribe_events_this_week_widget_after_the_title', 'enlightenment_ob_start' );

function enlightenment_tribe_filter_this_week_widget( $jsonld_enable ) {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_tribe_filter_this_week_widget', $output );
    echo $output;

    return $jsonld_enable;
}
add_filter( 'tribe_events_tribe-this-week-events-widget_jsonld_enabled', 'enlightenment_tribe_filter_this_week_widget' );

function enlightenment_tribe_ajax_filter_this_week_widget( $output ) {
    if ( doing_action( 'wp_ajax_tribe_this_week' ) || doing_action( 'wp_ajax_nopriv_tribe_this_week' ) ) {
        $output = apply_filters( 'enlightenment_tribe_filter_this_week_widget', $output );
    }
    $output = '';

    return $output;
}
add_filter( 'tribe_events_ajax_response', 'enlightenment_tribe_ajax_filter_this_week_widget' );
