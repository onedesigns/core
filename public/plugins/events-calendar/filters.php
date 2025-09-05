<?php

function enlightenment_tribe_events_views_v2_rest_params( $params ) {
    if ( ! isset( $params['eventDisplay'] ) ) {
        if ( isset( $params['pagename'] ) ) {
            $atts = explode( '/', $params['pagename'] );

            if ( strtotime( $atts[0] ) ) {
                $params['eventDisplay'] = 'day';
                $params['eventDate'] = $atts[0];
            } else {
                $params['eventDisplay'] = $atts[0];

                if ( isset( $atts[1] ) ) {
                    $params['eventDate'] = $atts[1];
                }
            }
        } elseif ( isset( $params['category_name'] ) ) {
            $atts = explode( '/', $params['category_name'] );

            $params['tribe_events_cat'] = $atts[0];

            if ( isset( $atts[1] ) ) {
                $params['eventDisplay'] = $atts[1];
            } else {
                $params['eventDisplay'] = 'default';
            }

            if ( isset( $atts[2] ) ) {
                $params['eventDate'] = $atts[2];
            }
        }
    }

    return $params;
}
add_filter( 'tribe_events_views_v2_rest_params', 'enlightenment_tribe_events_views_v2_rest_params' );

function enlightenment_tribe_maybe_filter_template_include( $template ) {
    if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
        return $template;
    }

    echo tribe( Tribe\Events\Views\V2\Template_Bootstrap::class )->get_view_html();
    exit();
}
// add_filter( 'template_include', 'enlightenment_tribe_maybe_filter_template_include' );

function enlightenment_tribe_maybe_remove_override_template( $template ) {
    global $wp_filter;

    if ( isset( $wp_filter['template_include'] ) && isset( $wp_filter['template_include'][10] ) ) {
        foreach ( $wp_filter['template_include'][10] as $handle => $callback ) {
            if ( ! is_array( $callback ) ) {
                continue;
            }

            if ( is_a( $callback['function'][0], 'WP_Router_Page' ) && 'override_template' == $callback['function'][1] ) {
                remove_filter( 'template_include', $callback['function'] );
                break;
            }
        }
    }

    return $template;
}
// add_filter( 'template_include', 'enlightenment_tribe_maybe_remove_override_template', 8 );

function enlightenment_tribe_override_template( $template ) {
    if ( 'default-template.php' == basename( $template ) ) {
        $template = locate_template( array(
            'page.php',
            'singular.php',
            'index.php'
        ) );
    }

    return $template;
}
// add_filter( 'template_include', 'enlightenment_tribe_override_template', 12 );

function enlightenment_tribe_pre_get_document_title( $title ) {
    if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
        return $title;
    }

    global $wp_query;

    if ( empty( $wp_query->query['s'] ) ) {
        $wp_query->is_search = false;
    }

    return $title;
}
add_filter( 'pre_get_document_title', 'enlightenment_tribe_pre_get_document_title', 1 );

function enlightenment_tribe_filter_template_html( $output, $file, $name, $template ) {
	/**
	 * Don't filter admin views
	 *
	 * is_admin() returns true in Community Tickets views, so we are checking if
	 * we are in an admin view by looking for the admin url in the current url.
	 */
	if (
		// current url is an admin request
		0 === strpos( $_SERVER['REQUEST_URI'], admin_url( '', 'relative' ) )
		&&
		// but not an ajax request
		0 !== strpos( $_SERVER['REQUEST_URI'], admin_url( 'admin-ajax.php', 'relative' ) )
	) {
		// so it must be an admin view
		return $output;
	}

	if (
		// current request is an ajax request
		0 === strpos( $_SERVER['REQUEST_URI'], admin_url( 'admin-ajax.php', 'relative' ) )
		&&
		// and referer is an admin view
		0 === strpos( $_SERVER['HTTP_REFERER'], admin_url() )
	) {
		// so it must be an admin view
		return $output;
	}

    $template_name = join( '/', $name );
    $template_slug = $template_name;
	$template_slug = str_replace( '-', '_', $template_slug );
	$template_slug = str_replace( '/', '_', $template_slug );
	$template_slug = ltrim( $template_slug, '_' );

    $output = apply_filters(
		'enlightenment_tribe_filter_template_output',
		$output, $template_name, $file, $template
	);

    $output = apply_filters(
		sprintf( 'enlightenment_tribe_filter_template_%s_output', $template_slug ),
		$output, $template_name, $file, $template
	);

    return $output;
}
add_filter( 'tribe_template_html', 'enlightenment_tribe_filter_template_html', 10, 4 );

function enlightenment_tribe_filter_template_part_content( $output, $template, $file, $slug ) {
    $template_name = $slug;
    $template_slug = $template_name;
	$template_slug = str_replace( '-', '_', $template_slug );
	$template_slug = str_replace( '/', '_', $template_slug );

    $output = apply_filters(
		'enlightenment_tribe_filter_template_part_output',
		$output, $template_name, $file, $template
	);

    $output = apply_filters(
		sprintf( 'enlightenment_tribe_filter_template_part_%s_output', $template_slug ),
		$output, $template_name, $file, $template
	);

    return $output;
}
add_filter( 'tribe_get_template_part_content', 'enlightenment_tribe_filter_template_part_content', 10, 4 );
add_filter( 'tribe_tickets_get_template_part_content', 'enlightenment_tribe_filter_template_part_content', 10, 4 );

function enlightenment_tribe_filter_template_file( $found_file, $name ) {
	if (
		doing_filter( 'the_content' )
		||
		isset( $_REQUEST['shortcode'] )
		||
		// Assume REST request in [tribe_events] shortcode

		(
			isset( $_REQUEST['url'] )
			&&
			( false !== strpos( $_REQUEST['url'], '?shortcode=' ) || false !== strpos( $_REQUEST['url'], '&shortcode=' ) )
		)
		||
		(
			isset( $_REQUEST['view_data'] ) && ! empty( $_REQUEST['view_data']['tribe-bar-date'] )
			&&
			isset( $_REQUEST['should_manage_url'] ) && 'false' === $_REQUEST['should_manage_url']
			&&
			(
				! isset( $_REQUEST['url'] )
				||
				( false === strpos( $_REQUEST['url'], '?related_series=' ) && false === strpos( $_REQUEST['url'], '&related_series=' ) )
			)
		)
		||
		(
			class_exists( 'Tribe\Events\Pro\Views\V2\Widgets\Widget_Month' )
			&&
			Tribe\Events\Pro\Views\V2\Widgets\Widget_Month::is_widget_in_use()
		)
		||
		(
			class_exists( 'Tribe\Events\Pro\Views\V2\Widgets\Widget_Week' )
			&&
			Tribe\Events\Pro\Views\V2\Widgets\Widget_Week::is_widget_in_use()
		)
	) {
        return $found_file;
    }

    if ( ! empty( $_REQUEST['tribe_events_cat'] ) ) {
        global $wp_query;

        $wp_query->is_post_type_archive = false;
        $wp_query->is_tax = true;
        $wp_query->tax_query = new WP_Tax_Query( array(
            array(
                'taxonomy' => 'tribe_events_cat',
                'field'    => 'slug',
                'terms'    => $_REQUEST['tribe_events_cat'],
            ),
        ) );
    }

    $name = join( '/', $name );

    switch ( $name ) {
        case 'list':
        case 'month':
        case 'day':
		case 'summary':
        case 'week':
        case 'photo':
        case 'map':
            $templates = array();
            $object    = get_queried_object();

            if ( is_a( $object, 'WP_Post_Type' ) ) {
                $templates[] = sprintf( 'archive-%s.php', $object->name );
            } elseif ( is_a( $object, 'WP_Term' ) ) {
                $templates[] = sprintf( 'taxonomy-%s-%s.php', $object->taxonomy, $object->slug );
                $templates[] = sprintf( 'taxonomy-%s.php', $object->taxonomy );
                $templates[] = 'taxonomy.php';
            } else {
                $templates[] = 'archive-tribe_events.php';
            }

            $templates[] = 'archive.php';
            $templates[] = 'index.php';

            $found_file = locate_template( $templates );
            break;

        /*case 'default-template':
            $templates = array();
            $object    = get_queried_object();

            if ( is_a( $object, 'WP_Post' ) ) {
                $found_file = locate_template( array(
                    'page.php',
                    'singular.php',
                    'index.php'
                ) );
            }
            break;*/
    }

    return $found_file;
}
add_filter( 'tribe_template_file', 'enlightenment_tribe_filter_template_file', 10, 2 );

function enlightenment_tribe_community_tickets_before_html( $html ) {
	ob_start();

	return $html;
}
add_filter( 'tec_community_tickets_before_html', 'enlightenment_tribe_community_tickets_before_html' );

function enlightenment_tribe_community_tickets_after_html( $html, $slug ) {
	$output = ob_get_clean();

	switch ( $slug ) {
		case 'venue':
			$output = apply_filters( 'enlightenment_tribe_filter_community_edit_venue_template', $output );
			break;
	}

	echo $output;

	return $html;
}
add_filter( 'tec_community_tickets_after_html', 'enlightenment_tribe_community_tickets_after_html', 10, 2 );

function enlightenment_tribe_maybe_strip_header_footer( $output ) {
	if ( ! ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) ) {
        return $output;
    }

    if ( isset( $_REQUEST['shortcode'] ) ) {
        return $output;
    }

	$offset = strpos( $output, ' id="tribe-events-view"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, '<div ', -( strlen( $output ) - $offset ) );

		if ( false !== $offset ) {
			$output = substr_replace( $output, '', 0, $offset );
		}
	}

	$offset = strpos( $output, '<!-- #tribe-events-view -->' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '', $offset, ( strlen( $output ) - $offset ) );
	}

	return $output;
}
add_filter( 'tribe_template_include_html:events/v2/list',      'enlightenment_tribe_maybe_strip_header_footer' );
add_filter( 'tribe_template_include_html:events/v2/month',     'enlightenment_tribe_maybe_strip_header_footer' );
add_filter( 'tribe_template_include_html:events/v2/day',       'enlightenment_tribe_maybe_strip_header_footer' );
add_filter( 'tribe_template_include_html:events/v2/summary',   'enlightenment_tribe_maybe_strip_header_footer' );
add_filter( 'tribe_template_include_html:events/v2/week',      'enlightenment_tribe_maybe_strip_header_footer' );
add_filter( 'tribe_template_include_html:events/v2/photo',     'enlightenment_tribe_maybe_strip_header_footer' );
add_filter( 'tribe_template_include_html:events-pro/v2/photo', 'enlightenment_tribe_maybe_strip_header_footer' );
add_filter( 'tribe_template_include_html:events/v2/map',       'enlightenment_tribe_maybe_strip_header_footer' );
add_filter( 'tribe_template_include_html:events-pro/v2/map',   'enlightenment_tribe_maybe_strip_header_footer' );

function enlightenment_tribe_filter_is_photo( $is_photo ) {
    global $enlightenment_tribe_template;

    if ( ! isset( $enlightenment_tribe_template ) ) {
        return $is_photo;
    }

    if ( 'photo' == enlightenment_tribe_get_view() ) {
        return true;
    }

    return $is_photo;
}
add_filter( 'tribe_is_photo', 'enlightenment_tribe_filter_is_photo', 10, 2 );

add_filter( 'tribe_events_get_link', 'trailingslashit', 999 );

function enlightenment_tribe_filter_events_title( $title, $depth ) {
    if ( ! $depth ) {
        return $title;
    }

	global $wp_query;

	$post_id = tribe_context()->get( 'post_id', false );

	if ( 'tribe_event_series' == get_post_type( $post_id ) ) {
		$link  = trailingslashit( tribe_get_events_link() );

        $title = sprintf( '<a href="%s">%s</a> &#8250; %s', esc_url( $link ), $title, get_the_title( $post_id ) );
	} elseif ( is_tag() ) {
		$link  = trailingslashit( tribe_get_events_link() );

		$title = sprintf( '<a href="%s">%s</a> &#8250; %s', esc_url( $link ), $title, single_term_title( '', false ) );
	} elseif ( tribe_is_month() ) {
        $link = Tribe__Events__Main::instance()->getLink( 'month', date_i18n( Tribe__Date_Utils::DBYEARMONTHTIMEFORMAT, strtotime( $wp_query->get('eventDate') ) ) );

        $title = str_replace( sprintf( '<a href="%s">', esc_url( tribe_get_events_link() ) ), sprintf( '<a href="%s">', esc_url( $link ) ), $title );
    } elseif ( tribe_is_day() ) {
        $link = Tribe__Events__Main::instance()->getLink( 'day', date_i18n( Tribe__Date_Utils::DBDATEFORMAT, strtotime( $wp_query->get('start_date') ) ) );

        $title = str_replace( sprintf( '<a href="%s">', esc_url( trailingslashit( tribe_get_events_link() ) ) ), sprintf( '<a href="%s">', esc_url( $link ) ), $title );
    } elseif ( function_exists( 'tribe_is_venue' ) && tribe_is_venue() ) {
        $link  = trailingslashit( tribe_get_events_link() );
        $venue = tribe_get_venue_object();
        $title = sprintf( '<a href="%s">%s</a> &#8250; %s', esc_url( $link ), $title, wp_kses_post( $venue->post_title ) );
    } elseif ( function_exists( 'tribe_is_organizer' ) && tribe_is_organizer() ) {
        $meta_query = $wp_query->get( 'meta_query' );

		if ( isset( $meta_query['_eventorganizerid_in'] ) ) {
	        $organizer_id = $meta_query['_eventorganizerid_in']['value'][0];

	        $link      = trailingslashit( tribe_get_events_link() );
	        $organizer = get_post( $organizer_id );
	        $title     = sprintf( '<a href="%s">%s</a> &#8250; %s', esc_url( $link ), $title, wp_kses_post( $organizer->post_title ) );
		}
    }

    return $title;
}
add_filter( 'tribe_get_events_title', 'enlightenment_tribe_filter_events_title', 10, 2 );

add_filter( 'tribe_events_title_tag', 'strip_tags' );

function enlightenment_tribe_filter_events_views_v2_view_title( $title, $view ) {
    global $wp_query;

    $query_args = $view->get_url_object()->get_query_args();

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

	if ( ! isset( $query_args['eventDate'] ) ) {
		$query_args['eventDate'] = date_i18n( Tribe__Date_Utils::DBDATETIMEFORMAT );
	}

    if ( empty( $wp_query->get( 'eventDate' ) ) ) {
        $wp_query->set( 'eventDate', $query_args['eventDate'] );
    }

    if ( ( 'day' == $view->get_view_slug() || 'week' == $view->get_view_slug() ) && empty( $wp_query->get( 'start_date' ) ) ) {
        $wp_query->set( 'start_date', tribe_beginning_of_day( $query_args['eventDate'] ) );
    }

    if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
        switch ( $view->get_view_slug() ) {
            case 'month':
                add_filter( 'tribe_is_month', '__return_true' );
                break;

            case 'day':
                add_filter( 'tribe_is_day', '__return_true' );
                break;

            case 'photo':
                add_filter( 'tribe_is_photo', '__return_true' );
                break;

            case 'week':
                add_filter( 'tribe_is_week', '__return_true' );
                break;

			case 'map':
				add_filter( 'tribe_is_map', '__return_true' );
				break;
        }

        $title = wp_kses( html_entity_decode( tribe_get_events_title() ), 'strip' );

        switch ( $view->get_view_slug() ) {
            case 'month':
                remove_filter( 'tribe_is_month', '__return_true' );
                break;

            case 'day':
                remove_filter( 'tribe_is_day', '__return_true' );
                break;

            case 'photo':
                remove_filter( 'tribe_is_photo', '__return_true' );
                break;

            case 'week':
                remove_filter( 'tribe_is_week', '__return_true' );
                break;

			case 'map':
				remove_filter( 'tribe_is_map', '__return_true' );
				break;
        }
    }

    return $title;
}
add_filter( 'tribe_events_views_v2_view_title', 'enlightenment_tribe_filter_events_views_v2_view_title', 10, 2 );

function enlightenment_tribe_events_views_v2_view_template_vars( $template_vars, $view ) {
    global $wp_query;

    $query_args = $view->get_url_object()->get_query_args();

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

	if ( ! isset( $query_args['eventDate'] ) ) {
		$query_args['eventDate'] = date_i18n( Tribe__Date_Utils::DBDATETIMEFORMAT );
	}

    if ( empty( $wp_query->get( 'eventDate' ) ) ) {
        $wp_query->set( 'eventDate', $query_args['eventDate'] );
    }

    if ( ( 'day' == $view->get_view_slug() || 'week' == $view->get_view_slug() ) && empty( $wp_query->get( 'start_date' ) ) ) {
        $wp_query->set( 'start_date', tribe_beginning_of_day( $query_args['eventDate'] ) );
    }

    if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
        if ( ! empty( $query_args['tribe_events_cat'] ) ) {
            $wp_query->is_tax = true;
            $wp_query->tax_query = new WP_Tax_Query( array(
                array(
                    'taxonomy' => 'tribe_events_cat',
                    'field'    => 'slug',
                    'terms'    => $query_args['tribe_events_cat'],
                ),
            ) );
        }

        switch ( $view->get_view_slug() ) {
            case 'month':
                add_filter( 'tribe_is_month', '__return_true' );
                break;

            case 'day':
                add_filter( 'tribe_is_day', '__return_true' );
                break;

            case 'photo':
                add_filter( 'tribe_is_photo', '__return_true' );
                break;

            case 'week':
                add_filter( 'tribe_is_week', '__return_true' );
                break;

			case 'map':
				add_filter( 'tribe_is_map', '__return_true' );
				break;
        }

        $template_vars['page_title'] = tribe_get_events_title();

        switch ( $view->get_view_slug() ) {
            case 'month':
                remove_filter( 'tribe_is_month', '__return_true' );
                break;

            case 'day':
                remove_filter( 'tribe_is_day', '__return_true' );
                break;

            case 'photo':
                remove_filter( 'tribe_is_photo', '__return_true' );
                break;

            case 'week':
                remove_filter( 'tribe_is_week', '__return_true' );
                break;

			case 'map':
				remove_filter( 'tribe_is_map', '__return_true' );
				break;
        }
    } else {
        $template_vars['page_title'] = tribe_get_events_title();
    }

    return $template_vars;
}
add_filter( 'tribe_events_views_v2_view_template_vars', 'enlightenment_tribe_events_views_v2_view_template_vars', 10, 2 );

/**
 * Strip the tribe-bar-date argument when a page argument is present in the url
 *
 * When both the tribe-bar-date and page argument are present in the url, the
 * page arguments gets stripped out during the requrest unless a view argument
 * is also present, which makes the first rest request return the existing set
 * of events the first time it is called. Stripping the tribe-bar-date argument
 * when a page argument is present fixes this behavior.
 */
function enlightenment_tribe_maybe_strip_tribe_bar_date_from_url( $url, $canonical ) {
	if (
		$canonical
		&&
		(
			false !== strpos( $url, '/page/' )
			||
			false !== strpos( $url, '&paged=' )
			||
			false !== strpos( $url, '?paged=' )
		)
	) {
		$url = remove_query_arg( 'tribe-bar-date', $url );
	}

	return $url;
}
add_filter( 'tribe_events_views_v2_view_next_url', 'enlightenment_tribe_maybe_strip_tribe_bar_date_from_url', 10, 2 );
add_filter( 'tribe_events_views_v2_view_prev_url', 'enlightenment_tribe_maybe_strip_tribe_bar_date_from_url', 10, 2 );

function enlightenment_tribe_filter_body_class( $classes ) {
    if ( enlightenment_tribe_is_ar_page() ) {
        $classes[] = 'page';
    }

    if ( enlightenment_tribe_community_tickets_is_frontend_sales_report() ) {
        $classes[] = 'page';
        $classes[] = 'page-tribe-sales-report';
    }

    return $classes;
}
add_filter( 'body_class', 'enlightenment_tribe_filter_body_class' );

function enlightenment_tribe_the_loop_args( $args ) {
    if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
        return $args;
    }

    $prefix = ( function_exists( 'tribe_is_photo' ) && tribe_is_photo() ) ? 'pro' : 'calendar';
    $view   = enlightenment_tribe_get_view();

    $args['container_class'] .= sprintf( ' tribe-events-%s-%s', $prefix, $view );

    return $args;
}
add_filter( 'enlightenment_the_loop_args', 'enlightenment_tribe_the_loop_args' );

function enlightenment_tribe_filter_ar_page_title( $title, $id = false ) {
    if ( -1 !== $id ) {
		return $title;
	}

    if ( ! enlightenment_tribe_is_ar_page() ) {
        return $title;
    }

	return __( 'Attendee Registration', 'enlightenment' );
}
add_filter( 'the_title', 'enlightenment_tribe_filter_ar_page_title', 12, 2 );

function enlightenment_tribe_a11y_template_output( $output ) {
    return str_replace( 'class="tribe-common-a11y-visual-hide"', 'class="tribe-common-a11y-visual-hide screen-reader-text"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_output', 'enlightenment_tribe_a11y_template_output' );

function enlightenment_tribe_filter_template_components_loader_output( $output ) {
	return str_replace( 'class="tribe-events-view-loader__text tribe-common-a11y-visual-hide"', 'class="tribe-events-view-loader__text tribe-common-a11y-visual-hide screen-reader-text"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_components_loader_output', 'enlightenment_tribe_filter_template_components_loader_output' );

function enlightenment_tribe_filter_template_components_events_bar_output( $output ) {
	return str_replace( 'class="tribe-events-c-events-bar__search-button-text tribe-common-a11y-visual-hide"', 'class="tribe-events-c-events-bar__search-button-text tribe-common-a11y-visual-hide screen-reader-text"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_components_events_bar_output', 'enlightenment_tribe_filter_template_components_events_bar_output' );

function enlightenment_tribe_filter_template_components_events_bar_search_keyword_output( $output ) {
    return str_replace( 'class="tribe-common-form-control-text__label"', 'class="tribe-common-form-control-text__label screen-reader-text"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_components_events_bar_search_keyword_output', 'enlightenment_tribe_filter_template_components_events_bar_search_keyword_output' );

function enlightenment_tribe_filter_template_components_virtual_event_output( $output ) {
	if ( doing_filter( 'tribe_template_before_include:events-pro/v2/summary/date-group/event/title/featured' ) ) {
		$output = str_replace( 'class="tribe-events-virtual-virtual-event__text"', 'class="tribe-events-virtual-virtual-event__text screen-reader-text"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_virtual_event_output', 'enlightenment_tribe_filter_template_components_virtual_event_output' );
add_filter( 'enlightenment_tribe_filter_template_components_hybrid_event_output', 'enlightenment_tribe_filter_template_components_virtual_event_output' );

function enlightenment_tribe_filter_template_top_bar_datepicker_output( $output ) {
	$output = str_replace( 'class="tribe-events-c-top-bar__datepicker-label tribe-common-a11y-visual-hide"', 'class="tribe-events-c-top-bar__datepicker-label tribe-common-a11y-visual-hide screen-reader-text"', $output );
    $output = str_replace( 'class="tribe-events-c-top-bar__datepicker-desktop tribe-common-a11y-hidden"', 'class="tribe-events-c-top-bar__datepicker-desktop tribe-common-a11y-hidden screen-reader-text"', $output );

    return $output;
}
add_filter( 'enlightenment_tribe_filter_template_list_top_bar_datepicker_output', 'enlightenment_tribe_filter_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_month_top_bar_datepicker_output', 'enlightenment_tribe_filter_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_day_top_bar_datepicker_output', 'enlightenment_tribe_filter_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_top_bar_datepicker_output', 'enlightenment_tribe_filter_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_week_top_bar_datepicker_output', 'enlightenment_tribe_filter_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_top_bar_datepicker_output', 'enlightenment_tribe_filter_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_map_top_bar_datepicker_output', 'enlightenment_tribe_filter_template_top_bar_datepicker_output' );

function enlightenment_tribe_filter_toggle_filter_bar_button( $output ) {
	return str_replace( 'class="tribe-events-c-events-bar__filter-button-text tribe-common-b2 tribe-common-a11y-visual-hide"', 'class="tribe-events-c-events-bar__filter-button-text tribe-common-b2 tribe-common-a11y-visual-hide screen-reader-text"', $output );
}
add_filter( 'enlightenment_events_toggle_filter_bar_button', 'enlightenment_tribe_filter_toggle_filter_bar_button' );

function enlightenment_tribe_filter_events_filter_bar( $output ) {
    $output = trim( $output );

    if ( empty( $output ) ) {
        return $output;
    }

    if ( class_exists( 'Tribe\Events\Filterbar\Views\V2_1\Filters' ) ) {
		$layout = tribe( Tribe\Events\Filterbar\Views\V2_1\Filters::class )->get_layout_setting();
	} else {
		$layout = tribe( Tribe\Events\Filterbar\Views\V2\Filters::class )->get_layout_setting();
	}

    if ( doing_action( 'enlightenment_page_content' ) && 'vertical' === $layout ) {
        $output = sprintf( '<div class="tribe-events-filter-bar-wrapper">%s</div>', $output );
    }

    $output = str_replace( 'class="a11y-hidden"', 'class="a11y-hidden screen-reader-text"', $output );

    if ( 'horizontal' === $layout ) {
        $output = str_replace( 'class="tribe-filter-bar__form-heading tribe-common-h5 tribe-common-h--alt tribe-common-a11y-visual-hide"', 'class="tribe-filter-bar__form-heading tribe-common-h5 tribe-common-h--alt tribe-common-a11y-visual-hide screen-reader-text"', $output );
    }

    $output = str_replace( 'class="tribe-filter-bar__selected-filters-label tribe-common-h7"', 'class="tribe-filter-bar__selected-filters-label tribe-common-h7 screen-reader-text"', $output );
    $output = str_replace( 'class="tribe-filter-bar-c-filter__remove-button-text tribe-common-a11y-visual-hide"', 'class="tribe-filter-bar-c-filter__remove-button-text tribe-common-a11y-visual-hide screen-reader-text"', $output );
    $output = str_replace( 'class="tribe-filter-bar-c-pill__remove-button-text tribe-common-a11y-visual-hide"', 'class="tribe-filter-bar-c-pill__remove-button-text tribe-common-a11y-visual-hide screen-reader-text"', $output );
    $output = str_replace( 'class="tribe-filter-bar__form-description tribe-common-a11y-visual-hide"', 'class="tribe-filter-bar__form-description tribe-common-a11y-visual-hide screen-reader-text"', $output );
    $output = str_replace( 'class="tribe-filter-bar-c-filter__toggle-icon-text tribe-common-a11y-visual-hide"', 'class="tribe-filter-bar-c-filter__toggle-icon-text tribe-common-a11y-visual-hide screen-reader-text"', $output );
    $output = str_replace( 'class="tribe-filter-bar-c-filter__filters-legend tribe-common-h6 tribe-common-h--alt tribe-common-a11y-visual-hide"', 'class="tribe-filter-bar-c-filter__filters-legend tribe-common-h6 tribe-common-h--alt tribe-common-a11y-visual-hide screen-reader-text"', $output );

    return $output;
}
add_filter( 'enlightenment_events_filter_bar', 'enlightenment_tribe_filter_events_filter_bar' );

function enlightenment_events_month_calendar_wrapper_maybe_open_container( $output ) {
    if ( ! doing_action( 'enlightenment_page_content' ) ) {
        return $output;
    }

    $container = enlightenment_events_calendar_wrapper_maybe_open_container( array( 'echo' => false ) );
    $output    = sprintf( '%s%s', $container, $output );

    return $output;
}
add_filter( 'enlightenment_events_month_calendar', 'enlightenment_events_month_calendar_wrapper_maybe_open_container' );
add_filter( 'enlightenment_events_week_day_selector', 'enlightenment_events_month_calendar_wrapper_maybe_open_container' );

function enlightenment_events_month_calendar_wrapper_maybe_close_container( $output ) {
    if ( ! doing_action( 'enlightenment_page_content' ) ) {
        return $output;
    }

    $view = enlightenment_tribe_get_view();

    if ( 'month' != $view && 'week' != $view ) {
        return $output;
    }

    $container = enlightenment_events_calendar_wrapper_maybe_close_container( array( 'echo' => false ) );
    $output    = sprintf( '%s%s', $output, $container );

    return $output;
}
add_filter( 'enlightenment_events_ical_link', 'enlightenment_events_month_calendar_wrapper_maybe_close_container' );

function enlightenment_tribe_filter_template_summary_date_separator_output( $output, $template_name, $file, $template ) {
	$values = $template->get_values();

	if ( $values['event']->summary_view->should_show_month_separator ) {
		return '';
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_summary_date_separator_output', 'enlightenment_tribe_filter_template_summary_date_separator_output', 12, 4 );

function enlightenment_tribe_filter_template_summary_date_group_event_title_featured_output( $output ) {
	return str_replace( 'class="tribe-events-pro-summary__event-title-featured-text tribe-common-a11y-visual-hide"', 'class="tribe-events-pro-summary__event-title-featured-text tribe-common-a11y-visual-hide screen-reader-text"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_summary_date_group_event_title_featured_output', 'enlightenment_tribe_filter_template_summary_date_group_event_title_featured_output' );

function enlightenment_tribe_filter_the_loop_args( $args ) {
    if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
        return $args;
    }

    $args['header_tag'] = '';

    return $args;
}
add_filter( 'enlightenment_the_loop_args', 'enlightenment_tribe_filter_the_loop_args' );

function enlightenment_tribe_filter_template_map_event_cards_event_card_event_date_time_featured_output( $output ) {
	return str_replace( 'class="tribe-events-pro-map__event-datetime-featured-text tribe-common-a11y-visual-hide"', 'class="tribe-events-pro-map__event-datetime-featured-text tribe-common-a11y-visual-hide screen-reader-text"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_event_card_event_date_time_featured_output', 'enlightenment_tribe_filter_template_map_event_cards_event_card_event_date_time_featured_output' );

function enlightenment_event_thumbnail_args( $args ) {
    if ( is_tag() ) {
        return $args;
    }

	if ( 'tribe_events' != get_post_type() ) {
		return $args;
	}

    if ( 'photo' != enlightenment_tribe_get_view() ) {
        return $args;
    }

    $args['default'] = sprintf( '<img class="tribe-events-pro-photo__event-featured-image wp-post-image" src="%s" />', trailingslashit( \Tribe__Events__Pro__Main::instance()->pluginUrl )
                                        . 'src/resources/images/tribe-event-placeholder-image.svg' );

    return $args;
}
add_filter( 'enlightenment_post_thumbnail_args', 'enlightenment_event_thumbnail_args' );

function enlightenment_event_post_thumbnail( $output ) {
	if ( ! in_the_loop() ) {
		return $output;
	}

    if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
        return $output;
    }

    if ( is_singular( 'tribe_events' ) && 'tickets' == get_query_var( 'eventDisplay', false ) ) {
        return '';
    }

    $prefix = ( function_exists( 'tribe_is_photo' ) && tribe_is_photo() ) ? 'pro' : 'calendar';
    $view   = enlightenment_tribe_get_view();
    $class  = sprintf( 'tribe-events-%s-%s__event-featured-image-wrapper', $prefix, $view );

    switch ( $view ) {
        case 'list':
        case 'day':
            $class .= ' tribe-common-g-col';
            break;
    }

    $output = sprintf( '<div class="%s">%s</div>', esc_attr( $class ), $output );

    return $output;
}
add_filter( 'enlightenment_post_thumbnail', 'enlightenment_event_post_thumbnail' );

function enlightenment_tribe_ar_page_entry_title_args( $args ) {
    if ( ! enlightenment_tribe_is_ar_page() ) {
        return $args;
    }

    $args['container']    = 'h1';
    $args['link_to_post'] = false;

    return $args;
}
add_filter( 'enlightenment_entry_title_args', 'enlightenment_tribe_ar_page_entry_title_args' );

function enlightenment_tribe_my_events_page_entry_title_args( $args ) {
    if ( ! enlightenment_tribe_is_community_my_events_page() ) {
        return $args;
    }

    $args['container']    = 'h1';
    $args['link_to_post'] = false;

    return $args;
}
add_filter( 'enlightenment_entry_title_args', 'enlightenment_tribe_my_events_page_entry_title_args' );

function enlightenment_tribe_frontend_sales_report_entry_title_args( $args ) {
    if ( ! enlightenment_tribe_community_tickets_is_frontend_sales_report() ) {
        return $args;
    }

    $args['container']    = 'h1';
    $args['link_to_post'] = false;

    return $args;
}
add_filter( 'enlightenment_entry_title_args', 'enlightenment_tribe_frontend_sales_report_entry_title_args' );

function enlightenment_tribe_payment_options_entry_title( $title, $post_id ) {
    if ( ! enlightenment_tribe_community_tickets_is_payment_options() ) {
        return $title;
    }

	$post = get_queried_object();

	if ( ! $post instanceof WP_Post ) {
		return $title;
	}

	if ( $post->ID != $post_id ) {
		return $title;
	}

    return __( 'Payment Options', 'enlightenment' );
}
add_filter( 'the_title', 'enlightenment_tribe_payment_options_entry_title', 14, 2 );

function enlightenment_tribe_get_venue_link( $link, $venue_id ) {
    if ( empty( $venue_id ) ) {
        return '';
    }

    return $link;
}
add_filter( 'tribe_get_venue_link', 'enlightenment_tribe_get_venue_link', 10, 2 );

function enlightenment_tribe_filter_template_blocks_featured_image_output( $output ) {
	return str_replace( 'class="tribe-events-event-image"', 'class="wp-block-tribe-events-event-image tribe-events-event-image"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_featured_image_output', 'enlightenment_tribe_filter_template_blocks_featured_image_output' );

function enlightenment_tribe_filter_template_blocks_virtual_event_output( $output ) {
    return str_replace( 'class="tribe-block tribe-block--virtual-event"', 'class="tribe-block tribe-block--virtual-event wp-block-virtual-event"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_virtual_event_output', 'enlightenment_tribe_filter_template_blocks_virtual_event_output' );

function enlightenment_tribe_filter_template_components_link_button_output( $output ) {
	return sprintf( '<div class="wp-block-tribe-events-virtual-link-button">%s</div>', $output );
}
add_filter( 'enlightenment_tribe_filter_template_components_link_button_output', 'enlightenment_tribe_filter_template_components_link_button_output' );

function enlightenment_tribe_filter_template_single_video_embed_output( $output ) {
    return str_replace( 'class="tribe-events-virtual-single-video-embed"', 'class="tribe-events-virtual-single-video-embed wp-block-tribe-events-virtual-single-video-embed"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_single_video_embed_output', 'enlightenment_tribe_filter_template_single_video_embed_output' );

function enlightenment_single_event_nav_args( $args ) {
    if ( 'tribe_events' != get_post_type() ) {
        return $args;
    }

	$args['prev_format'] = str_replace( '%4$s', '[event_date]', $args['prev_format'] );
	$args['next_format'] = str_replace( '%4$s', '[event_date]', $args['next_format'] );

	return $args;
}
add_filter( 'enlightenment_post_nav_args', 'enlightenment_single_event_nav_args' );

function enlightenment_filter_event_nav_link( $output, $format, $link, $post ) {
	if ( 'tribe_events' != get_post_type() ) {
		return $output;
	}

    if ( false === strpos( $output, '[event_date]' ) ) {
		return $output;
	}

    $args = array(
        'event_date_tag'   => '',
        'event_date_class' => '',
    );
    $args = apply_filters( 'enlightenment_event_nav_link_args', $args );

    $event_date  = enlightenment_open_tag( $args['event_date_tag'], $args['event_date_class'] );
	$event_date .= tribe_get_start_date( $post, false, tribe_get_date_format( true ) );
	$event_date .= enlightenment_close_tag( $args['event_date_tag'] );

    $output = str_replace( '[event_date]', $event_date, $output );

	return $output;
}
add_filter( 'previous_post_link', 'enlightenment_filter_event_nav_link', 10, 4 );
add_filter( 'next_post_link', 'enlightenment_filter_event_nav_link', 10, 4 );

function enlightenment_filter_single_event_nav( $output ) {
    if ( 'tickets' == get_query_var( 'eventDisplay', false ) ) {
        $output = '';
    }

    return $output;
}
add_filter( 'enlightenment_post_nav', 'enlightenment_filter_single_event_nav' );

function enlightenment_tribe_filter_template_venue_meta_output( $output, $template_name, $file, $template ) {
	$view = $template->get_view();

	if ( ! $view instanceof Tribe\Events\Pro\Views\V2\Views\Venue_View ) {
		return $output;
	}

	$post_id = $view->get_post_id();

	if ( is_array( $post_id ) ) {
		if ( 1 != count( $post_id ) ) {
			return $output;
		}

		$post_id = array_shift( $post_id );
	}

	if ( empty( $post_id ) ) {
		return $output;
	}

	$output = str_replace(
		'class="tribe-events-pro-venue__meta ',
		sprintf( 'id="tribe-events-pro-venue__meta-%1$s" class="tribe-events-pro-venue__meta tribe-events-pro-venue__meta-%1$s ', $post_id ),
		$output
	);

	$output = str_replace(
		'class="tribe-events-pro-venue__meta"',
		sprintf( 'id="tribe-events-pro-venue__meta-%1$s" class="tribe-events-pro-venue__meta tribe-events-pro-venue__meta-%1$s"', $post_id ),
		$output
	);

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_venue_meta_output', 'enlightenment_tribe_filter_template_venue_meta_output', 10, 4 );

function enlightenment_tribe_filter_template_organizer_meta_output( $output, $template_name, $file, $template ) {
	$view = $template->get_view();

	if ( ! $view instanceof Tribe\Events\Pro\Views\V2\Views\Organizer_View ) {
		return $output;
	}

	$post_id = $view->get_post_id();

	if ( is_array( $post_id ) ) {
		if ( 1 != count( $post_id ) ) {
			return $output;
		}

		$post_id = array_shift( $post_id );
	}

	if ( empty( $post_id ) ) {
		return $output;
	}

	$output = str_replace(
		'class="tribe-events-pro-organizer__meta ',
		sprintf( 'id="tribe-events-pro-organizer__meta-%1$s" class="tribe-events-pro-organizer__meta tribe-events-pro-organizer__meta-%1$s ', $post_id ),
		$output
	);

	$output = str_replace(
		'class="tribe-events-pro-organizer__meta"',
		sprintf( 'id="tribe-events-pro-organizer__meta-%1$s" class="tribe-events-pro-organizer__meta tribe-events-pro-organizer__meta-%1$s"', $post_id ),
		$output
	);

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_organizer_meta_output', 'enlightenment_tribe_filter_template_organizer_meta_output', 10, 4 );

function enlightenment_tribe_filter_template_tickets_view_link_output( $output ) {
	return str_replace( 'class="tribe-link-view-attendee"', 'class="wp-block-tribe-link-view-attendee tribe-link-view-attendee"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_tickets_view_link_output', 'enlightenment_tribe_filter_template_tickets_view_link_output' );

function enlightenment_tribe_events_remove_virtual_markers() {
	if ( ! class_exists( 'Tribe\Events\Virtual\Hooks' ) ) {
		return;
	}

	remove_filter( 'tribe_events_event_schedule_details', array( tribe( 'events-virtual.hooks' ), 'include_single_control_desktop_markers' ) );
	remove_filter( 'tribe_events_event_schedule_details', array( tribe( 'events-virtual.hooks' ), 'include_single_hybrid_control_desktop_markers' ) );
}
add_action( 'enlightenment_plugins_loaded', 'enlightenment_tribe_events_remove_virtual_markers' );

function enlightenment_tribe_filter_tickets_orders_template( $output ) {
    if ( false === strpos( $output, 'id="tribe-events-content"' ) ) {
        return $output;
    }

    $offset = strpos( $output, '<p class="tribe-back">' );
    if ( false !== $offset ) {
        $end    = strpos( $output, '</p>', $offset ) + 4;
        $length = $end - $offset;
        $output = substr_replace( $output, '', $offset, $length );
    }

    $offset = strpos( $output, '<h1 class="tribe-events-single-event-title">' );
    if ( false !== $offset ) {
        $end    = strpos( $output, '</h1>', $offset ) + 5;
        $length = $end - $offset;
        $output = substr_replace( $output, '', $offset, $length );
    }

    if ( function_exists( 'tribe_events_recurrence_tooltip' ) ) {
        $output = str_replace( tribe_events_recurrence_tooltip( get_the_ID() ), '', $output );
    }

    $offset = strpos( $output, '<div class="tribe-events-schedule tribe-clearfix">' );
    if ( false !== $offset ) {
        $end    = strpos( $output, '</div>', $offset ) + 6;
        $length = $end - $offset;
        $output = substr_replace( $output, '', $offset, $length );
    }

    $offset = strpos( $output, '<div class="tribe-events-notices">' );
    if ( false !== $offset ) {
        $end    = strpos( $output, '</div>', $offset ) + 6;
        $length = $end - $offset;
        $output = substr_replace( $output, '', $offset, $length );
    }

    $output = str_replace( '<!-- Notices -->', '', $output );

    return $output;
}
add_filter( 'the_content', 'enlightenment_tribe_filter_tickets_orders_template', 12 );

function enlightenment_tribe_events_community_template( $template ) {
    if ( 'default-template.php' == basename( $template ) ) {
        $template = locate_template( array(
            'page.php',
            'singular.php',
            'index.php'
        ) );
    }

    return $template;
}
add_filter( 'tribe_events_community_template', 'enlightenment_tribe_events_community_template' );
add_filter( 'tribe_community_tickets_template', 'enlightenment_tribe_events_community_template' );

function enlightenment_tribe_filter_community_event_list_template( $output ) {
    $start = strpos( $output, '<h2 class="tribe-community-events-list-title">' );
    if ( false !== $start ) {
        $offset  = strpos( $output, '<div class="tribe-nav tribe-nav-bottom">', $start );
        $offset  = strpos( $output, '</div>', $offset ) + 1;
        $end     = strpos( $output, '</div>', $offset ) + 6;
        $length  = $end - $start;
        $substr  = substr( $output, $start, $length );
        $filter  = apply_filters( 'enlightenment_tribe_filter_community_event_list_template', $substr );
        $output  = str_replace( $substr, $filter, $output );
    }

    return $output;
}
add_filter( 'the_content', 'enlightenment_tribe_filter_community_event_list_template', 12 );

function enlightenment_tribe_events_community_event_list_remove_elements( $output ) {
    $offset = strpos( $output, '<div class="tribe-nav tribe-nav-top">' );
    if ( false !== $offset ) {
        $output  = substr_replace( $output, '', 0, $offset );
    }

    return $output;
}
add_filter( 'enlightenment_tribe_filter_community_event_list_template', 'enlightenment_tribe_events_community_event_list_remove_elements' );

function enlightenment_tribe_filter_community_edit_venue_template( $output ) {
    $start = strpos( $output, '<div id="tribe-community-events" class="form venue">' );
    if ( false !== $start ) {
        $offset  = strpos( $output, '</form>', $start );
        $end     = strpos( $output, '</div>', $offset ) + 6;
        $length  = $end - $start;
        $substr  = substr( $output, $start, $length );
        $filter  = apply_filters( 'enlightenment_tribe_filter_community_edit_venue_template', $substr );
        $output  = str_replace( $substr, $filter, $output );
    }

    return $output;
}
add_filter( 'the_content', 'enlightenment_tribe_filter_community_edit_venue_template', 12 );

function enlightenment_tribe_filter_community_edit_organizer_template( $output ) {
    $start = strpos( $output, '<div id="tribe-community-events" class="form organizer">' );
    if ( false !== $start ) {
        $offset  = strpos( $output, '</form>', $start );
        $end     = strpos( $output, '</div>', $offset ) + 6;
        $length  = $end - $start;
        $substr  = substr( $output, $start, $length );
        $filter  = apply_filters( 'enlightenment_tribe_filter_community_edit_organizer_template', $substr );
        $output  = str_replace( $substr, $filter, $output );
    }

    return $output;
}
add_filter( 'the_content', 'enlightenment_tribe_filter_community_edit_organizer_template', 12 );

function enlightenment_tribe_filter_event_featured_image( $output ) {
    if ( doing_filter( 'pre_render_block' ) ) {
        $output = str_replace( 'class="tribe-events-event-image"', 'class="wp-block-tribe-events-event-image tribe-events-event-image"', $output );
    }

    return $output;
}
add_filter( 'tribe_event_featured_image', 'enlightenment_tribe_filter_event_featured_image' );

function enlightenment_tribe_filter_template_part_pro_related_events_output( $output ) {
	if ( ! function_exists( 'tribe_get_related_posts' ) ) {
		return $output;
	}

    $posts = tribe_get_related_posts();

    if ( empty( $posts ) ) {
		return $output;
	}

    foreach ( $posts as $post ) {
        if ( $post->post_type == Tribe__Events__Main::POSTTYPE ) {
            $meta = tribe_events_event_schedule_details( $post );
            $output = str_replace( $meta, sprintf( '<div class="tribe-related-event-meta">%s</div>', $meta ), $output );
        }
    }

    return sprintf( '<div class="tribe-related-events-wrapper">%s</div>', $output );
}
add_filter( 'enlightenment_tribe_filter_template_part_pro_related_events_output', 'enlightenment_tribe_filter_template_part_pro_related_events_output' );

function enlightenment_tribe_filter_community_events_form_title( $title, $id = false ) {
	if ( empty( $id ) || 0 > $id ) {
		return $title;
	}

    $post = get_post( $id );

    if ( 'wp_router_page' == $post->post_type && enlightenment_tribe_is_community_edit_event_page() ) {
        global $wp_query;

        $event_id = $wp_query->get( 'tribe_community_event_id' );

        if ( empty( $event_id ) ) {
            $event_id = $wp_query->get( 'tribe_event_id' );
        }

        if ( $event_id != $post->ID ) {
            $GLOBALS['post'] = get_post( $event_id );
        }

        ob_start();
        tribe_get_template_part( 'community/modules/header-links' );
        $output = ob_get_clean();

        if ( $event_id != $post->ID ) {
            $GLOBALS['post'] = $post;
        }

        $offset = strpos( $output, '<h2 class="my-events">' );
        if ( false !== $offset ) {
            $offset += 22;
            $end     = strpos( $output, '</h2>', $offset );
            $length  = $end - $offset;
            $title   = substr( $output, $offset, $length );
            $title   = trim( $title );
        }
    }

    return $title;
}
add_filter( 'the_title', 'enlightenment_tribe_filter_community_events_form_title', 12, 2 );

function enlightenment_tribe_events_community_form_remove_header( $output ) {
    $offset = strpos( $output, '<header class="my-events-header">' );
    if ( false !== $offset ) {
        $end     = strpos( $output, '</header>', $offset ) + 9;
        $length  = $end - $offset;
        $output  = substr_replace( $output, '', $offset, $length );
    }

	return $output;
}
add_filter( 'enlightenment_tribe_filter_events_community_form', 'enlightenment_tribe_events_community_form_remove_header' );
add_filter( 'enlightenment_tribe_filter_community_edit_venue_template', 'enlightenment_tribe_events_community_form_remove_header' );
add_filter( 'enlightenment_tribe_filter_community_edit_organizer_template', 'enlightenment_tribe_events_community_form_remove_header' );
add_filter( 'the_content', 'enlightenment_tribe_events_community_form_remove_header' );

function enlightenment_tribe_filter_community_events_report_title( $title, $id = false ) {
	if ( empty( $id ) || 0 > $id ) {
		return $title;
	}

    $post = get_post( $id );

    if ( 'wp_router_page' == $post->post_type && enlightenment_tribe_community_tickets_is_frontend_attendees_report() ) {
        $priority = has_filter( 'tribe_tickets_attendees_show_title', '__return_false' );

        if ( false !== $priority ) {
            remove_filter( 'tribe_tickets_attendees_show_title', '__return_false', $priority );
        }

        ob_start();
        tribe( 'tickets.admin.views' )->template( 'attendees' );
        $output = ob_get_clean();

        if ( false !== $priority ) {
            add_filter( 'tribe_tickets_attendees_show_title', '__return_false', $priority );
        }

        $offset = strpos( $output, '<h1>' );
        if ( false !== $offset ) {
            $offset += 4;
            $end     = strpos( $output, '</h1>', $offset );
            $length  = $end - $offset;
            $title   = substr( $output, $offset, $length );
            $title   = trim( $title );
        }
    }

    return $title;
}
add_filter( 'the_title', 'enlightenment_tribe_filter_community_events_report_title', 12, 2 );

add_filter( 'tribe_tickets_attendees_show_title', '__return_false', 999 );

function enlightenment_tribe_tickets_attendees_page_inside_remove_title( $output ) {
    $offset = strpos( $output, '<h1>' );
    if ( false !== $offset ) {
        $end     = strpos( $output, '</h1>', $offset ) + 5;
        $length  = $end - $offset;
        $output  = substr_replace( $output, '', $offset, $length );
    }

    return $output;
}
add_filter( 'enlightenment_tribe_filter_tickets_attendees_page_inside', 'enlightenment_tribe_tickets_attendees_page_inside_remove_title' );
add_filter( 'enlightenment_tribe_filter_community_tickets_sales_report_nav', 'enlightenment_tribe_tickets_attendees_page_inside_remove_title' );

function enlightenment_tribe_filter_table_menu( $output ) {
    $offset = strpos( $output, '<div class="table-menu-wrapper">' );
    if ( false !== $offset ) {
        $end     = strpos( $output, '</div>', $offset ) + 6;
        $length  = $end - $offset;
        $menu    = substr( $output, $offset, $length );
        $menu    = apply_filters( 'enlightenment_tribe_filter_table_menu', $menu );
        $output  = substr_replace( $output, $menu, $offset, $length );
    }

	return $output;
}
add_filter( 'the_content', 'enlightenment_tribe_filter_table_menu' );

add_filter( 'tribe_tickets_woocommerce_order_report_show_title', '__return_false', 999 );

function enlightenment_tribe_filter_order_report( $output ) {
    $offset = strpos( $output, '<div id="tribe-order-summary"' );
    if ( false !== $offset ) {
        $end     = strpos( $output, '</form>', $offset ) + 7;
        $length  = $end - $offset;
        $report  = substr( $output, $offset, $length );
        $report  = apply_filters( 'enlightenment_tribe_filter_order_report', $report );
        $output  = substr_replace( $output, $report, $offset, $length );
    }

	return $output;
}
add_filter( 'the_content', 'enlightenment_tribe_filter_order_report' );

function enlightenment_tribe_wrap_total_tickets_ordered( $output ) {
    $offset = strpos( $output, '<div class="order-total">' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, "\n" . '<strong>', $offset + 25, 0 );
        $offset = strpos( $output, ':', $offset );
        $output = substr_replace( $output, '</strong>', $offset + 1, 0 );
    }

	return $output;
}
add_filter( 'enlightenment_tribe_filter_order_report', 'enlightenment_tribe_wrap_total_tickets_ordered' );

function enlightenment_tribe_login_form_wrap( $output ) {
	return sprintf( '<div class="tribe-events-community-login">%s</div>', $output );
}
add_filter( 'enlightenment_tribe_filter_login_form', 'enlightenment_tribe_login_form_wrap' );

function enlightenment_events_nav_args( $args ) {
    if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
        return $args;
    }

    $args['custom_cb'] = 'enlightenment_events_nav';

    return $args;
}
add_filter( 'enlightenment_posts_nav_args', 'enlightenment_events_nav_args' );

function enlightenment_tribe_filter_template_nav_prev_output( $output ) {
	return str_replace( 'class="tribe-events-c-nav__prev-label-plural tribe-common-a11y-visual-hide"', 'class="tribe-events-c-nav__prev-label-plural tribe-common-a11y-visual-hide screen-reader-text"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_list_nav_prev_output', 'enlightenment_tribe_filter_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_month_nav_prev_output', 'enlightenment_tribe_filter_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_day_nav_prev_output', 'enlightenment_tribe_filter_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_nav_prev_output', 'enlightenment_tribe_filter_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_week_mobile_events_nav_prev_output', 'enlightenment_tribe_filter_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_nav_prev_output', 'enlightenment_tribe_filter_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_nav_prev_output', 'enlightenment_tribe_filter_template_nav_prev_output' );

function enlightenment_tribe_filter_template_nav_next_output( $output ) {
	return str_replace( 'class="tribe-events-c-nav__next-label-plural tribe-common-a11y-visual-hide"', 'class="tribe-events-c-nav__next-label-plural tribe-common-a11y-visual-hide screen-reader-text"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_list_nav_next_output', 'enlightenment_tribe_filter_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_month_nav_next_output', 'enlightenment_tribe_filter_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_day_nav_next_output', 'enlightenment_tribe_filter_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_nav_next_output', 'enlightenment_tribe_filter_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_week_mobile_events_nav_next_output', 'enlightenment_tribe_filter_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_nav_next_output', 'enlightenment_tribe_filter_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_nav_next_output', 'enlightenment_tribe_filter_template_nav_next_output' );

function enlightenment_tribe_filter_template_components_title_output( $output, $template_name, $file, $template ) {
    $context = $template->get_values();

	if (
		isset( $context['widget'] )
		&&
		$context['widget'] instanceof Tribe\Events\Pro\Views\V2\Widgets\Widget_Week
		&&
		empty( $context['widget']->get_argument( 'title', '' ) )
	) {
		$output = '';
	}

    return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_title_output', 'enlightenment_tribe_filter_template_components_title_output', 10, 4 );

function enlightenment_tribe_filter_template_widgets_widget_countdown_widget_title_output( $output, $template_name, $file, $template ) {
    $context = $template->get_values();

    if ( empty( $context['widget_title'] ) ) {
        $output = '';
    }

    return $output;
}
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_countdown_widget_title_output', 'enlightenment_tribe_filter_template_widgets_widget_countdown_widget_title_output', 10, 4 );

function enlightenment_tribe_filter_template_widgets_widget_events_list_output( $output ) {
	$output = str_replace( 'class="tribe-events-widget-events-list__header-title tribe-common-h6 tribe-common-h--alt"', 'class="tribe-events-widget-events-list__header-title tribe-common-h6 tribe-common-h--alt widget-title"', $output );
	$output = sprintf( '<aside class="widget">%s</aside>', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_events_list_output', 'enlightenment_tribe_filter_template_widgets_widget_events_list_output' );

function enlightenment_tribe_filter_events_pro_tribe_inline_shortcode_output( $output, $shortcode ) {
    return sprintf( '<div class="tribe-event-inline-wrapper">%s</div>', $output );
}
add_filter( 'tribe_events_pro_tribe_inline_shortcode_output', 'enlightenment_tribe_filter_events_pro_tribe_inline_shortcode_output', 10, 2 );

function enlightenment_tribe_filter_event_datetime_block( $output ) {
    return str_replace( 'class="tribe-events-schedule tribe-clearfix"', 'class="wp-block-tribe-event-datetime tribe-events-schedule tribe-clearfix"', $output );
}
add_filter( 'enlightenment_render_block_tribe_event_datetime', 'enlightenment_tribe_filter_event_datetime_block' );

function enlightenment_tribe_filter_event_price_block( $output ) {
	$content = $output;
	$offset  = strpos( $content, 'class="tribe-block tribe-block__event-price ' );

	if ( false === $offset ) {
		$offset = strpos( $content, 'class="tribe-block tribe-block__event-price"' );
	}

	if ( false !== $offset ) {
		$start   = strrpos( $content, '<div ', $offset - strlen( $content ) );
		$end     = strpos( $content, '>', $start ) + 1;
		$length  = $end - $start;
		$content = substr_replace( $content, '', $start, $length );

		$start   = strrpos( $content, '</div>' );
		if ( false !== $start ) {
			$content = substr_replace( $content, '', $start, 6 );
		}
	}

	if ( '' === trim( $content ) ) {
		return '';
	}

    return str_replace( 'class="tribe-block tribe-block__event-price"', 'class="wp-block-tribe-event-price tribe-block tribe-block__event-price"', $output );
}
add_filter( 'enlightenment_render_block_tribe_event_price', 'enlightenment_tribe_filter_event_price_block' );

function enlightenment_tribe_filter_event_organizer_block( $output ) {
    return str_replace( 'class="tribe-block tribe-block__organizer__details ', 'class="wp-block-tribe-event-organizer tribe-block tribe-block__organizer__details ', $output );
}
add_filter( 'enlightenment_render_block_tribe_event_organizer', 'enlightenment_tribe_filter_event_organizer_block' );

function enlightenment_tribe_filter_classic_event_details_block( $output ) {
    return str_replace( 'class="tribe-events-single-section tribe-events-event-meta ', 'class="wp-block-tribe-classic-event-details tribe-events-single-section tribe-events-event-meta ', $output );
}
add_filter( 'enlightenment_render_block_tribe_classic_event_details', 'enlightenment_tribe_filter_classic_event_details_block' );

function enlightenment_tribe_filter_event_venue_block( $output ) {
	$content = $output;
	$offset  = strpos( $content, 'class="tribe-block tribe-block__venue ' );

	if ( false === $offset ) {
		$offset = strpos( $content, 'class="tribe-block tribe-block__venue"' );
	}

	if ( false !== $offset ) {
		$start   = strrpos( $content, '<div ', $offset - strlen( $content ) );
		$end     = strpos( $content, '>', $start ) + 1;
		$length  = $end - $start;
		$content = substr_replace( $content, '', $start, $length );

		$start   = strrpos( $content, '</div>' );
		if ( false !== $start ) {
			$content = substr_replace( $content, '', $start, 6 );
		}
	}

	if ( '' === trim( $content ) ) {
		return '';
	}

    $output = str_replace( 'class="tribe-block tribe-block__venue ', 'class="wp-block-tribe-venue tribe-block tribe-block__venue ', $output );
    $output = str_replace( 'class="tribe-block tribe-block__venue"', 'class="wp-block-tribe-venue tribe-block tribe-block__venue"', $output );

    return $output;
}
add_filter( 'enlightenment_render_block_tribe_event_venue', 'enlightenment_tribe_filter_event_venue_block' );

function enlightenment_tribe_filter_event_website_block( $output ) {
    return str_replace( 'class="tribe-block tribe-block__event-website"', 'class="wp-block-tribe-event-website tribe-block tribe-block__event-website"', $output );
}
add_filter( 'enlightenment_render_block_tribe_event_website', 'enlightenment_tribe_filter_event_website_block' );

function enlightenment_tribe_filter_event_links_block( $output ) {
    return str_replace( 'class="tribe-block tribe-block__events-link"', 'class="wp-block-tribe-event-links tribe-block tribe-block__events-link"', $output );
}
add_filter( 'enlightenment_render_block_tribe_event_links', 'enlightenment_tribe_filter_event_links_block' );

function enlightenment_tribe_filter_event_category_block( $output ) {
    return str_replace( 'class="tribe-events-single-section tribe-events-section-category tribe-clearfix"', 'class="wp-block-tribe-event-category tribe-events-single-section tribe-events-section-category tribe-clearfix"', $output );
}
add_filter( 'enlightenment_render_block_tribe_event_category', 'enlightenment_tribe_filter_event_category_block' );

function enlightenment_tribe_filter_related_events_block( $output ) {
	if ( ! function_exists( 'tribe_get_related_posts' ) ) {
		return $output;
	}

    $posts = tribe_get_related_posts();

    if ( empty( $posts ) ) {
		return $output;
	}

    foreach ( $posts as $post ) {
        if ( $post->post_type == Tribe__Events__Main::POSTTYPE ) {
            $meta = tribe_events_event_schedule_details( $post );
            $output = str_replace( $meta, sprintf( '<div class="tribe-related-event-meta">%s</div>', $meta ), $output );
        }
    }

	return sprintf( '<div class="wp-block-tribe-related-events tribe-related-events-wrapper">%s</div>', $output );
}
add_filter( 'enlightenment_render_block_tribe_related_events', 'enlightenment_tribe_filter_related_events_block' );

function enlightenment_tribe_events_excerpt_blocks_removal( $bool ) {
    if ( ! isset( $GLOBALS['post'] ) ) {
        return false;
    }

    return $bool;
}
add_filter( 'tribe_events_excerpt_blocks_removal', 'enlightenment_tribe_events_excerpt_blocks_removal' );

function enlightenment_tribe_filter_template_blocks_attendees_view_link_output( $output ) {
	return str_replace( 'class="tribe-link-view-attendee"', 'class="wp-block-tribe-link-view-attendee tribe-link-view-attendee"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_attendees_view_link_output', 'enlightenment_tribe_filter_template_blocks_attendees_view_link_output' );

function enlightenment_tribe_block_rsvp_template( $output ) {
    return str_replace( 'class="tribe-common event-tickets"', 'class="wp-block-tribe-rsvp tribe-common event-tickets"', $output );
}
add_filter( 'enlightenment_tribe_filter_rsvp_template', 'enlightenment_tribe_block_rsvp_template' );
add_filter( 'the_content', 'enlightenment_tribe_block_rsvp_template', 13 );

function enlightenment_tribe_filter_template_blocks_rsvp_output( $output ) {
	return str_replace( 'class="tribe-block tribe-block__rsvp"', 'class="wp-block-tribe-rsvp tribe-block tribe-block__rsvp"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_output', 'enlightenment_tribe_filter_template_blocks_rsvp_output' );

function enlightenment_tribe_filter_template_v2_tickets_output( $output ) {
	return str_replace( 'class="tribe-common event-tickets tribe-tickets__tickets-wrapper"', 'class="wp-block-tribe-tickets tribe-common event-tickets tribe-tickets__tickets-wrapper"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_output', 'enlightenment_tribe_filter_template_v2_tickets_output' );

function enlightenment_tribe_filter_template_blocks_tickets_output( $output ) {
	return str_replace( 'class="tribe-block tribe-tickets tribe-common"', 'class="wp-block-tribe-tickets tribe-block tribe-tickets tribe-common"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_output', 'enlightenment_tribe_filter_template_blocks_tickets_output' );

function enlightenment_tribe_filter_template_blocks_attendees_output( $output ) {
	return str_replace( 'class="tribe-block tribe-block__attendees"', 'class="wp-block-tribe-attendees tribe-block tribe-block__attendees"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_attendees_output', 'enlightenment_tribe_filter_template_blocks_attendees_output' );
