<?php

function enlightenment_tribe_venue_meta() {
    $template = enlightenment_tribe_template();
    $view     = enlightenment_tribe_view();
	$post_id  = $view->get_post_id();

	if ( is_array( $post_id ) ) {
		$post_id = array_shift( $post_id );
	}

    $venue    = tribe_get_venue_object( $post_id );

    $template->template( 'venue/meta', array_merge( $template->get_values(), [ 'venue' => $venue ] ) );
}

function enlightenment_tribe_organizer_meta() {
    $template  = enlightenment_tribe_template();
    $view      = enlightenment_tribe_view();
	$post_id   = $view->get_post_id();

	if ( is_array( $post_id ) ) {
		$post_id = array_shift( $post_id );
	}

    $organizer = tribe_get_organizer_object( $post_id );

    $template->template( 'organizer/meta', array_merge( $template->get_values(), [ 'organizer' => $organizer ] ) );
}

function enlightenment_tribe_event_series_description( $args = null ) {
	$series_id = tribe_context()->get( 'post_id', false );

	$defaults = array(
        'container'       => 'div',
		'container_class' => 'event-series-description',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_tribe_event_series_description_args', $defaults, $series_id );
	$args     = wp_parse_args( $args, $defaults );

	$content = get_the_content( null, false, $series_id );
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	$content = apply_filters( 'enlightenment_tribe_event_series_description_content', $content, $args, $series_id );

    $output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= $content;
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_tribe_event_series_description', $output, $args, $series_id );

    if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_tribe_events_open_container() {
	$context = enlightenment_tribe_template()->get_values();
    $classes = new Tribe\Utils\Element_Classes( $context['container_classes'] );
    $class   = $classes->get_classes_as_string();
    $atts    = ' data-js="tribe-events-view"';
	$atts   .= sprintf( ' data-view-rest-nonce="%s"', esc_attr( $context['rest_nonce'] ) );
	$atts   .= sprintf( ' data-view-rest-url="%s"', esc_url( $context['rest_url'] ) );

    if ( isset( $context['rest_method'] ) ) {
        $atts   .= sprintf( ' data-view-rest-method="%s"', esc_attr( $context['rest_method'] ) );
    }

	$atts   .= sprintf( ' data-view-manage-url="%s"', esc_attr( $context['should_manage_url'] ) );

	foreach ( $context['container_data'] as $key => $value ) {
		$atts .= sprintf( ' data-view-%s="%s"', esc_attr( $key ), esc_attr( $value ) );
	}

	if ( ! empty( $context['breakpoint_pointer'] ) ) {
		$atts .= sprintf( ' data-view-breakpoint-pointer="%s"', esc_attr( $context['breakpoint_pointer'] ) );
	}

    echo enlightenment_open_tag( 'div', $class, 'tribe-events-view', $atts );

    $class = 'tribe-common-l-container tribe-events-l-container';

    echo enlightenment_open_tag( 'div', $class );
}

function enlightenment_tribe_events_close_container() {
    echo enlightenment_close_tag( 'div' );
    echo enlightenment_close_tag( 'div' );
	echo '<!-- #tribe-events-view -->';
}

function enlightenment_events_loader() {
	enlightenment_tribe_template()->template( 'components/loader', [ 'text' => __( 'Loading&#8230;', 'enlightenment' ) ] );
}

function enlightenment_events_json_ld_data() {
	enlightenment_tribe_template()->template( 'components/json-ld-data' );
}

function enlightenment_events_data() {
	enlightenment_tribe_template()->template( 'components/data' );
}

function enlightenment_before_events() {
	enlightenment_tribe_template()->template( 'components/before' );
}

function enlightenment_event_row_open_container( $args = null ) {
    global $enlightenment_tribe_event;

    $defaults = array(
		'container_class' => 'tribe-common-g-row tribe-events-calendar-list__event-row',
		'echo'            => true,
	);

    if ( isset( $enlightenment_tribe_event ) && $enlightenment_tribe_event->featured ) {
        $defaults['container_class'] .= ' tribe-events-calendar-list__event-row--featured';
    }

	$defaults = apply_filters( 'enlightenment_event_row_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    $output = enlightenment_open_tag( 'div', $args['container_class'] );

    if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_photo_event_details_wrapper_open_container( $args = null ) {
    if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
        return;
    }

    if ( 'photo' != enlightenment_tribe_get_view() ) {
        return;
    }

    $defaults = array(
		'container_class' => 'tribe-events-pro-photo__event-details-wrapper',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_photo_event_details_wrapper_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    $output  = enlightenment_open_tag( 'div', $args['container_class'] );

    if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_event_details_open_container( $args = null ) {
    if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
        return;
    }

    $prefix = ( function_exists( 'tribe_is_photo' ) && tribe_is_photo() ) ? 'pro' : 'calendar';
    $view   = enlightenment_tribe_get_view();

    $defaults = array(
		'container_class' => sprintf( 'tribe-events-%s-%s__event-details', $prefix, $view ),
		'echo'            => true,
	);

    switch ( $view ) {
        case 'list':
        case 'day':
            $defaults['container_class'] .= ' tribe-common-g-col';
            break;
    }

	$defaults = apply_filters( 'enlightenment_event_details_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    $output  = enlightenment_open_tag( 'div', $args['container_class'] );

    if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_tribe_events_header_open_container() {
    $context = enlightenment_tribe_template()->get_values();

    $classes = array( 'tribe-events-header' );

    if ( empty( $context['disable_event_search'] ) ) {
    	$classes[] = 'tribe-events-header--has-event-search';
    }

    $classes = new Tribe\Utils\Element_Classes( $classes );
    $class   = apply_filters( 'enlightenment_tribe_events_header_container_class', $classes->get_classes_as_string() );

    echo enlightenment_open_tag( 'header', $class );
}

function enlightenment_tribe_events_header_close_container() {
    echo enlightenment_close_tag( 'header' );
}

function enlightenment_events_filter_bar_calendar_wrapper_maybe_open_container( $args = null ) {
    if ( ! function_exists( 'tribe_events_filterbar_init' ) ) {
        return;
    }

    if ( ! has_action( 'enlightenment_page_content', 'enlightenment_events_filter_bar' ) ) {
        return;
    }

    if ( 'vertical' !== tribe_get_option( 'events_filters_layout', 'vertical' ) ) {
        return;
    }

    $defaults = array(
        'container'       => 'div',
		'container_class' => 'tribe-events-filter-bar-calendar-wrapper',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_events_filter_bar_calendar_wrapper_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    $output = enlightenment_open_tag( $args['container'], $args['container_class'] );

	$output = apply_filters( 'enlightenment_events_filter_bar_calendar_wrapper_maybe_open_container', $output );

    if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_filter_bar_calendar_wrapper_maybe_close_container( $args = null ) {
    if ( ! function_exists( 'tribe_events_filterbar_init' ) ) {
        return;
    }

    if ( ! has_action( 'enlightenment_page_content', 'enlightenment_events_filter_bar' ) ) {
        return;
    }

    if ( 'vertical' !== tribe_get_option( 'events_filters_layout', 'vertical' ) ) {
        return;
    }

    $defaults = array(
        'container'       => 'div',
		'container_class' => 'tribe-events-filter-bar-calendar-wrapper',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_events_filter_bar_calendar_wrapper_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    $output = enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_events_filter_bar_calendar_wrapper_maybe_close_container', $output );

    if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_calendar_wrapper_maybe_open_container( $args = null ) {
    if ( ! function_exists( 'tribe_events_filterbar_init' ) ) {
        return;
    }

    if ( ! has_action( 'enlightenment_page_content', 'enlightenment_events_filter_bar' ) ) {
        return;
    }

    if ( 'vertical' !== tribe_get_option( 'events_filters_layout', 'vertical' ) ) {
        return;
    }

    $defaults = array(
        'container'       => 'div',
		'container_class' => 'tribe-events-calendar-wrapper',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_events_calendar_wrapper_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    $output = enlightenment_open_tag( $args['container'], $args['container_class'] );

	$output = apply_filters( 'enlightenment_events_calendar_wrapper_maybe_open_container', $output );

    if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_calendar_wrapper_maybe_close_container( $args = null ) {
    if ( ! function_exists( 'tribe_events_filterbar_init' ) ) {
        return;
    }

    if ( ! has_action( 'enlightenment_page_content', 'enlightenment_events_filter_bar' ) ) {
        return;
    }

    if ( 'vertical' !== tribe_get_option( 'events_filters_layout', 'vertical' ) ) {
        return;
    }

    $defaults = array(
        'container'       => 'div',
		'container_class' => 'tribe-events-calendar-wrapper',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_events_calendar_wrapper_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    $output = enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_events_calendar_wrapper_maybe_close_container', $output );

    if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_messages() {
	enlightenment_tribe_template()->template( 'components/messages' );
}

function enlightenment_events_content_title() {
	enlightenment_tribe_template()->template( 'components/content-title' );
}

function enlightenment_events_bar() {
	enlightenment_tribe_template()->template( 'components/events-bar' );
}

function enlightenment_events_top_bar() {
	enlightenment_tribe_template()->template( enlightenment_tribe_get_view() . '/top-bar' );
}

function enlightenment_events_toggle_filter_bar_button( $file = null, $name = null, $template = null, $args = null ) {
    if ( ! class_exists( 'Tribe\Events\Filterbar\Views\V2_1\Hooks' ) ) {
        return false;
    }

    $defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_events_toggle_filter_bar_button_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    if ( is_null( $template ) ) {
        $template = enlightenment_tribe_template();
    }

    ob_start();
    tribe( 'filterbar.views.v2_1.hooks' )->action_include_filter_button( $file, $name, $template );
    $output = ob_get_clean();

	$output = apply_filters( 'enlightenment_events_toggle_filter_bar_button', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_filter_bar( $args = null ) {
    $defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_events_filter_bar_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    ob_start();

    if ( class_exists( 'Tribe\Events\Filterbar\Views\V2_1\Hooks' ) ) {
        $template = enlightenment_tribe_template();
        $layout   = tribe( Tribe\Events\Filterbar\Views\V2_1\Filters::class )->get_layout_setting();
		$values   = $template->get_values();

		if ( 'vertical' == $layout || ! empty( Tribe__Utils__Array::get( $values, 'disable_event_search', false ) ) ) {
			tribe( 'filterbar.views.v2_1.hooks' )->action_include_filter_bar( null, null, $template );
		} else {
			tribe( 'filterbar.views.v2_1.hooks' )->action_include_horizontal_filter_bar( null, null, $template );
		}
    } else {
        enlightenment_tribe_template()->template( 'components/filter-bar' );
    }

    $output = ob_get_clean();

	$output = apply_filters( 'enlightenment_events_filter_bar', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_tribe_events_photo_view_open_row( $args = null ) {
    $defaults = array(
        'container_class' => 'tribe-common-g-row tribe-common-g-row--gutters',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_tribe_events_photo_view_row_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( 'div', $args['container_class'] );

	$output = apply_filters( 'enlightenment_tribe_events_photo_view_open_row', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_map_map( $args = null ) {
    $defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_events_map_map_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	ob_start();
	enlightenment_tribe_template()->template( 'map/map' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_events_map_map', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_map_event_cards( $args = null ) {
    $defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_events_map_event_cards_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	ob_start();
	enlightenment_tribe_template()->template( 'map/event-cards' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_events_map_event_cards', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_map( $args = null ) {
    $context = enlightenment_tribe_template()->get_values();

    if ( empty( $context['events'] ) ) {
        return;
    }

    $defaults = array(
        'container'       => 'div',
        'container_class' => 'tribe-events-pro-map tribe-common-g-row',
        'format'          => '%1$s%2$s',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_events_map_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    $map   = enlightenment_events_map_map( array( 'echo' => false ) );
    $cards = enlightenment_events_map_event_cards( array( 'echo' => false ) );

    $output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= sprintf( $args['format'], $map, $cards );
    $output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_events_map', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_week_day_selector( $args = null ) {
	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_events_week_day_selector_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	ob_start();
	enlightenment_tribe_template()->template( 'week/day-selector' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_events_week_day_selector', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_week_mobile_events() {
    $context = enlightenment_tribe_template()->get_values();
	enlightenment_tribe_template()->template( 'week/mobile-events', [ 'days' => $context['mobile_days'] ] );
}

function enlightenment_events_week_grid_header( $args = null ) {
    $defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_events_week_grid_header_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	ob_start();
	enlightenment_tribe_template()->template( 'week/grid-header' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_events_week_grid_header', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_week_grid_body( $args = null ) {
    $defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_events_week_grid_body_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	ob_start();
	enlightenment_tribe_template()->template( 'week/grid-body' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_events_week_grid_body', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_week_grid( $args = null ) {
    $context = enlightenment_tribe_template()->get_values();

    $defaults = array(
        'container'            => 'div',
        'container_class'      => 'tribe-events-pro-week-grid tribe-common-a11y-hidden',
        'container_extra_atts' => array(
            'role'            => 'grid',
            'aria-labelledby' => 'tribe-events-pro-week-header',
            'aria-readonly'   => 'true',
        ),
        'format'               => '%1$s%2$s',
		'echo'                 => true,
	);

    if ( $context['hide_weekends'] ) {
    	$defaults['container_class'] .= ' tribe-events-pro-week-grid--hide-weekends';
    }

	$defaults = apply_filters( 'enlightenment_events_week_grid_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    $header = enlightenment_events_week_grid_header( array( 'echo' => false ) );
    $body   = enlightenment_events_week_grid_body( array( 'echo' => false ) );

    $output  = enlightenment_open_tag( $args['container'], $args['container_class'], '', $args['container_extra_atts'] );
	$output .= sprintf( $args['format'], $header, $body );
    $output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_events_week_grid', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_month_calendar_header( $args = null ) {
    $defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_events_month_calendar_header_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	ob_start();
	enlightenment_tribe_template()->template( 'month/calendar-header' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_events_month_calendar_header', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_month_calendar_body( $args = null ) {
    $defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_events_month_calendar_body_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	ob_start();
	enlightenment_tribe_template()->template( 'month/calendar-body' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_events_month_calendar_body', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_month_calendar( $args = null ) {
    $defaults = array(
        'container'            => 'table',
        'container_class'      => 'tribe-events-calendar-month',
        'container_extra_atts' => array(
            'role'          => 'presentation',
            'aria-readonly' => 'true',
            'data-js'       => 'tribe-events-month-grid',
        ),
        'format'               => '%1$s%2$s',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_events_month_calendar_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    $header = enlightenment_events_month_calendar_header( array( 'echo' => false ) );
    $body   = enlightenment_events_month_calendar_body( array( 'echo' => false ) );

    $output  = enlightenment_open_tag( $args['container'], $args['container_class'], '', $args['container_extra_atts'] );
	$output .= sprintf( $args['format'], $header, $body );
    $output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_events_month_calendar', $output );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_month_mobile_events() {
	enlightenment_tribe_template()->template( 'month/mobile-events' );
}

function enlightenment_events_month_separator( $args = null ) {
	$template = enlightenment_tribe_template();
	$event    = enlightenment_tribe_event();

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_events_month_separator_args', $defaults, $template, $event );
	$args     = wp_parse_args( $args, $defaults );

	ob_start();
	$template->template( 'list/month-separator', [ 'event' => $event ] );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_events_month_separator', $output, $template, $event );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_day_type_separator() {
	enlightenment_tribe_template()->template( 'day/type-separator', [ 'event' => enlightenment_tribe_event() ] );
}

function enlightenment_events_day_time_separator() {
	enlightenment_tribe_template()->template( 'day/time-separator', [ 'event' => enlightenment_tribe_event() ] );
}

function enlightenment_events_summary() {
	$template = enlightenment_tribe_template();
	$vaules   = $template->get_values();

	echo enlightenment_open_tag( 'div', 'tribe-events-pro-summary' );

	foreach ( $vaules['events_by_date'] as $group_date => $events_data ) {
		if ( empty( $events_data ) ) {
			continue;
		}

		$event      = current( $events_data );
		$group_date = Tribe__Date_Utils::build_date_object( $group_date );

		$template->setup_postdata( $event );

		$template->template( 'summary/month-separator', array(
			'events'     => $vaules['events'],
			'event'      => $event,
			'group_date' => $group_date,
		) );

		$template->template( 'summary/date-separator', array(
			'events'     => $vaules['events'],
			'event'      => $event,
			'group_date' => $group_date,
		) );

		$template->template( 'summary/date-group', array(
			'events_for_date' => $events_data,
			'group_date'      => $group_date,
		) );
	}

	echo enlightenment_close_tag( 'div' );

	$template->template( 'summary/nav' );
}

function enlightenment_events_separator() {
	switch ( enlightenment_tribe_get_view() ) {
		case 'list':
			enlightenment_events_month_separator();
			break;

		case 'day':
			enlightenment_events_day_type_separator();
			enlightenment_events_day_time_separator();
			break;
	}
}

function enlightenment_tribe_the_notices( $args = null ) {
    $defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_tribe_the_notices_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    ob_start();
	tribe_the_notices();
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_tribe_the_notices', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_event_wrapper_open_container( $args = null ) {
    $defaults = array(
		'container'       => 'list' == enlightenment_tribe_get_view() ? 'div' : '',
		'container_class' => 'tribe-events-calendar-list__event-wrapper tribe-common-g-col',
		'row_wrapper'     => 'photo' == enlightenment_tribe_get_view() ? '' : 'div',
        'row_class'       => 'tribe-events-calendar-%s__event tribe-common-g-row tribe-common-g-row--gutters',
		'col_wrapper'     => 'day' == enlightenment_tribe_get_view() ? 'div' : '',
        'col_class'       => 'tribe-events-calendar-day__event-content tribe-common-g-col',
		'echo'            => true,
	);

	if ( 'day' == enlightenment_tribe_get_view() && enlightenment_tribe_event()->featured ) {
	    $defaults['row_class'] .= ' tribe-events-calendar-day__event--featured';
	}

	$defaults = apply_filters( 'enlightenment_event_wrapper_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$args['row_class'] = sprintf( $args['row_class'], enlightenment_tribe_get_view() );

    $output  = enlightenment_open_tag( $args['container'],   $args['container_class'] );
    $output .= enlightenment_open_tag( $args['row_wrapper'], $args['row_class'] );
    $output .= enlightenment_open_tag( $args['col_wrapper'], $args['col_class'] );

    if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_event_wrapper_close_container( $args = null ) {
	$defaults = array(
		'container'       => 'list' == enlightenment_tribe_get_view() ? 'div' : '',
		'container_class' => 'tribe-events-calendar-list__event-wrapper tribe-common-g-col',
		'row_wrapper'     => 'photo' == enlightenment_tribe_get_view() ? '' : 'div',
        'row_class'       => 'tribe-events-calendar-%s__event tribe-common-g-row tribe-common-g-row--gutters',
		'col_wrapper'     => 'day' == enlightenment_tribe_get_view() ? 'div' : '',
        'col_class'       => 'tribe-events-calendar-day__event-content tribe-common-g-col',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_event_wrapper_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    $output  = enlightenment_close_tag( $args['container'] );
    $output .= enlightenment_close_tag( $args['row_wrapper'] );
    $output .= enlightenment_close_tag( $args['col_wrapper'] );

    if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_event_date_tag( $args = null ) {
	$template = enlightenment_tribe_template();
	$event    = enlightenment_tribe_event();

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_event_date_tag_args', $defaults, $template, $event );
	$args     = wp_parse_args( $args, $defaults );

	ob_start();
	$template->template( sprintf( '%s/event/date-tag', enlightenment_tribe_get_view() ), [ 'event' => $event ] );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_event_date_tag', $output, $template, $event );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_single_event_recurring_description( $args = null ) {
	if ( ! function_exists( 'tribe_is_recurring_event' ) ) {
        return;
    }

	if ( ! is_singular( 'tribe_events' ) ) {
        return;
    }

	if ( ! tribe_is_recurring_event( get_the_ID() ) ) {
        return;
    }

    $defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_single_event_recurring_description_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    try {
        $template = tribe( 'events.editor.template' );
    } catch ( Exception $e ) {
        $template = false;
    }

    ob_start();
    if ( $template ) {
        $template->template( 'single-event/recurring-description', [ 'post_id' => get_the_ID() ] );
    } else {
        $recurrence_data = get_post_meta( get_the_ID(), '_EventRecurrence', true );
        $recurrence_description = $recurrence_data['description'] ? $recurrence_data['description'] : esc_html__( 'Recurring event', 'enlightenment' );

        ?>
        <div class="tribe-events-single-event-recurrence-description">
            <img src="<?php echo Tribe__Main::instance()->plugin_url . 'src/modules/icons/recurrence.svg'; ?>" />
            <span><?php echo $recurrence_description; ?></span>
            <a href="<?php echo esc_url( tribe_all_occurrences_link( get_the_ID(), false ) ); ?>">
                <?php echo __( 'see all', 'enlightenment' ); ?>
            </a>
        </div>
        <?php

        $event_id = get_the_ID();
        $event = get_post( $event_id );

        /**
         * If a yearless date format should be preferred.
         *
         * By default, this will be true if the event starts and ends in the current year.
         *
         * @since 0.2.5-alpha
         *
         * @param bool    $use_yearless_format
         * @param WP_Post $event
         */
        $use_yearless_format = apply_filters( 'tribe_events_event_block_datetime_use_yearless_format',
        	(
        		tribe_get_start_date( $event_id, false, 'Y' ) === date_i18n( 'Y' )
        		&& tribe_get_end_date( $event_id, false, 'Y' ) === date_i18n( 'Y' )
        	),
        	$event
        );

        $time_format    = tribe_get_time_format();
        $date_format    = tribe_get_date_format( $use_yearless_format );
        $timezone       = get_post_meta( $event_id, '_EventTimezone', true );
        $show_time_zone = tribe_get_option( 'tribe_events_timezones_show_zone', false );

        if ( is_null( $show_time_zone ) ) {
        	$show_time_zone = tribe_get_option( 'tribe_events_timezones_show_zone', false );
        }

        $time_zone_label = class_exists( 'Tribe__Timezones' ) ? Tribe__Timezones::wp_timezone_string() : get_option( 'timezone_string', 'UTC' );

        if ( is_null( $time_zone_label ) ) {
        	$time_zone_label = Tribe__Events__Timezones::get_event_timezone_abbr( $event_id );
        }


        $formatted_start_date = tribe_get_start_date( $event_id, false, $date_format );
        $formatted_start_time = tribe_get_start_time( $event_id, $time_format );
        $formatted_end_date   = tribe_get_end_date( $event_id, false, $date_format );
        $formatted_end_time   = tribe_get_end_time( $event_id, $time_format );
        $separator_date       = get_post_meta( $event_id, '_EventDateTimeSeparator', true );
        $separator_time       = get_post_meta( $event_id, '_EventTimeRangeSeparator', true );

        if ( empty( $separator_time ) ) {
        	$separator_time = tribe_get_option( 'timeRangeSeparator', ' - ' );
        }
        if ( empty( $separator_date ) ) {
        	$separator_date = tribe_get_option( 'dateTimeSeparator', ' - ' );
        }

        $is_all_day        = tribe_event_is_all_day( $event_id );
        $is_same_day       = $formatted_start_date == $formatted_end_date;
        $is_same_start_end = $formatted_start_date == $formatted_end_date && $formatted_start_time == $formatted_end_time;

        ?>
        <div class="tribe-events-schedule tribe-clearfix">
        	<h2 class="tribe-events-schedule__datetime">
        		<span class="tribe-events-schedule__date tribe-events-schedule__date--start">
        			<?php echo esc_html( $formatted_start_date ); ?>
        		</span>

        		<?php if ( ! $is_all_day ) : ?>
        			<span class="tribe-events-schedule__separator tribe-events-schedule__separator--date">
        				<?php echo esc_html( $separator_date ); ?>
        			</span>
        			<span class="tribe-events-schedule__time tribe-events-schedule__time--start">
        				<?php echo esc_html( $formatted_start_time ); ?>
        			</span>
        		<?php elseif ( $is_same_day ) : ?>
        			<span class="tribe-events-schedule__all-day"><?php echo esc_html__( 'All day', 'the-events-calendar' ); ?></span>
        		<?php endif; ?>

        		<?php if ( ! $is_same_start_end ) : ?>
        			<?php if ( ! $is_all_day || ! $is_same_day ) : ?>
        				<span class="tribe-events-schedule__separator tribe-events-schedule__separator--time">
        					<?php echo esc_html( $separator_time ); ?>
        				</span>
        			<?php endif; ?>

        			<?php if ( ! $is_same_day ) : ?>
        				<span class="tribe-events-schedule__date tribe-events-schedule__date--end">
        					<?php echo esc_html( $formatted_end_date ); ?>
        				</span>

        				<?php if ( ! $is_all_day ) : ?>
        					<span class="tribe-events-schedule__separator tribe-events-schedule__separator--date">
        						<?php echo esc_html( $separator_date ); ?>
        					</span>
        					<span class="tribe-events-schedule__time tribe-events-schedule__time--end">
        						<?php echo esc_html( $formatted_end_time ); ?>
        					</span>
        				<?php endif; ?>

        			<?php elseif ( ! $is_all_day ) : ?>
        				<span class="tribe-events-schedule__time tribe-events-schedule__time--end">
        					<?php echo esc_html( $formatted_end_time ); ?>
        				</span>
        			<?php endif; ?>

        			<?php if ( $show_time_zone ) : ?>
        				<span class="tribe-events-schedule__timezone"><?php echo esc_html( $time_zone_label ); ?></span>
        			<?php endif; ?>
        		<?php endif; ?>
        	</h2>
        </div>
        <?php
    }
    $output = ob_get_clean();

	$output = apply_filters( 'enlightenment_single_event_recurring_description', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_single_event_back_link( $args = null ) {
    $defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_single_event_back_link_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    try {
        $template = tribe( 'events.editor.template' );
    } catch ( Exception $e ) {
        $template = false;
    }

    if ( $template && has_blocks( get_the_ID() ) ) {
        ob_start();
        $template->template( 'single-event/back-link' );
        $output = ob_get_clean();
    } else {
        if ( 'tickets' == get_query_var( 'eventDisplay', false ) ) {
            $output  = enlightenment_open_tag( 'p', 'tribe-back' );
            $output .= sprintf(
                '<a href="%s"> &laquo; %s</a>',
                esc_url( get_permalink( get_queried_object_id() ) ),
                sprintf( esc_html__( 'View %s', 'enlightenment' ), tribe_get_event_label_singular() )
            );
            $output .= enlightenment_close_tag( 'p' );
        } else {
            $output  = enlightenment_open_tag( 'p', 'tribe-events-back' );
            $output .= sprintf(
                '<a href="%s"> &laquo; %s</a>',
                esc_url( tribe_get_events_link() ),
                sprintf( esc_html_x( 'All %s', '%s Events plural label', 'enlightenment' ), tribe_get_event_label_plural() )
            );
            $output .= enlightenment_close_tag( 'p' );
        }
    }

    $output = apply_filters( 'enlightenment_single_event_back_link', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_single_event_before_the_content() {
	do_action( 'tribe_events_single_event_before_the_content' );
}

function enlightenment_single_event_after_the_content() {
	do_action( 'tribe_events_single_event_after_the_content' );
}

function enlightenment_single_event_meta() {
    if ( 'tickets' == get_query_var( 'eventDisplay', false ) ) {
        return;
    }

	do_action( 'tribe_events_single_event_before_the_meta' );
	tribe_get_template_part( 'modules/meta' );
	do_action( 'tribe_events_single_event_after_the_meta' );
}

function enlightenment_related_events() {
    if ( ! class_exists( 'Tribe__Events__Pro__Main' ) ) {
        return;
    }

    if ( 'tickets' == get_query_var( 'eventDisplay', false ) ) {
        return;
    }

    Tribe__Events__Pro__Main::instance()->register_related_events_view();
}

function enlightenment_event_cost() {
    $template = enlightenment_tribe_template();
    $event    = enlightenment_tribe_event();
    $view     = enlightenment_tribe_get_view();

	$template->template( sprintf( '%s/event/cost', $view ), [ 'event' => $event ] );
}

function enlightenment_single_event_ical_links() {
    if ( 'tickets' == get_query_var( 'eventDisplay', false ) ) {
        return;
    }

	tribe( 'tec.iCal' )->single_event_links();
}

function enlightenment_events_nav() {
	enlightenment_tribe_template()->template( sprintf( '%s/nav', enlightenment_tribe_get_view() ) );
}

function enlightenment_events_ical_link() {
	enlightenment_tribe_template()->template( 'components/ical-link' );
}

function enlightenment_after_events() {
	enlightenment_tribe_template()->template( 'components/after' );
}

function enlightenment_events_breakpoints() {
	enlightenment_tribe_template()->template( 'components/breakpoints' );
}

function enlightenment_events_gmap_container() {
	tribe_get_template_part( 'pro/map/gmap-container' );
}

function enlightenment_events_venue_embed_google_map( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'tribe-events-map-wrap',
		'container_id'    => '',
		'venue_id'        => get_the_ID(),
		'width'           => '100%',
		'height'          => '200px',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_events_venue_embed_google_map_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = '';

	if ( tribe_embed_google_map() && tribe_address_exists() ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
		$output .= tribe_get_embedded_map( $args['venue_id'], $args['width'], $args['height'] );
		$output .= enlightenment_close_tag( $args['container'] );
	}

	$output = apply_filters( 'enlightenment_events_venue_embed_google_map', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_venue_upcoming_events( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'tribe-venue-upcoming-events',
		'container_id'    => '',
		'venue_id'        => get_the_ID(),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_events_venue_upcoming_events_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	ob_start();
	do_action( 'tribe_events_single_venue_before_upcoming_events' );
	$output .= ob_get_clean();

	$output .= tribe_venue_upcoming_events( $args['venue_id'] );

	ob_start();
	do_action( 'tribe_events_single_venue_after_upcoming_events' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_events_venue_upcoming_events', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_organizer_upcoming_events( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'tribe-organizer-upcoming-events',
		'container_id'    => '',
		'organizer_id'    => get_the_ID(),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_events_organizer_upcoming_events_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	ob_start();
	do_action( 'tribe_events_single_organizer_before_upcoming_events' );
	$output .= ob_get_clean();

	$output .= tribe_organizer_upcoming_events( $args['organizer_id'] );

	ob_start();
	do_action( 'tribe_events_single_organizer_after_upcoming_events' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_events_organizer_upcoming_events', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_events_month_loop() {
	$class = new Tribe__Events__Template__Month();
	$class->setup_view();

	do_action( 'enlightenment_before_entries_list' );
	tribe_get_template_part( 'month/loop', 'grid' );
	do_action( 'enlightenment_after_entries_list' );

	$class->shutdown_view();
}

function enlightenment_events_month_nav() {
	tribe_get_template_part( 'month/nav' );
}

function enlightenment_events_month_mobile_tooltip() {
	tribe_get_template_part( 'month/mobile' );
	tribe_get_template_part( 'month/tooltip' );
}

function enlightenment_events_week_loop() {
	$class = new Tribe__Events__Pro__Templates__Week();
	$class->setup_view();

	do_action( 'enlightenment_before_entries_list' );
	tribe_get_template_part( 'pro/week/loop', 'grid' );
	do_action( 'enlightenment_after_entries_list' );

	$class->shutdown_view();
}

function enlightenment_events_week_nav() {
	tribe_get_template_part( 'pro/week/nav', 'footer' );
}

function enlightenment_events_week_mobile_tooltip() {
	tribe_get_template_part( 'pro/week/mobile' );
	tribe_get_template_part( 'pro/week/tooltip' );
}

function enlightenment_events_day_loop() {
	$class = new Tribe__Events__Template__Day();
	$class->setup_view();
}

function enlightenment_tribe_events_day_time_slot() {
	global $enlightenment_events_current_timeslot, $post;

	if( ! isset( $enlightenment_events_current_timeslot ) ) {
		$enlightenment_events_current_timeslot = null;
	}

	if( $enlightenment_events_current_timeslot != $post->timeslot ) {
		$enlightenment_events_current_timeslot = $post->timeslot;

		echo enlightenment_open_tag( 'div', 'tribe-events-day-time-slot' );
		echo enlightenment_open_tag( 'h5' );
		echo esc_attr( $enlightenment_events_current_timeslot );
		echo enlightenment_close_tag( 'h5' );
		echo enlightenment_close_tag( 'div' );
	}
}

function enlightenment_events_meta() {
	do_action( 'tribe_events_single_event_before_the_meta' );
	tribe_get_template_part( 'modules/meta' );
	do_action( 'tribe_events_single_event_after_the_meta' );
}

function enlightenment_events_meta_details() {
	if( ! did_action( 'tribe_events_single_event_before_the_meta' ) ) {
		do_action( 'tribe_events_single_event_before_the_meta' );
	}

	if( ! did_action( 'tribe_events_single_meta_before' ) ) {
		do_action( 'tribe_events_single_meta_before' );
	}

	// Check for skeleton mode (no outer wrappers per section)
	$not_skeleton = ! apply_filters( 'tribe_events_single_event_the_meta_skeleton', false, get_the_ID() );

	if ( $not_skeleton ) {
		echo enlightenment_open_tag( 'div', 'tribe-events-single-section tribe-events-event-meta primary tribe-clearfix' );
	}

	if( ! did_action( 'tribe_events_single_event_meta_primary_section_start' ) ) {
		do_action( 'tribe_events_single_event_meta_primary_section_start' );
	}

	// Iclude the main event details
	tribe_get_template_part( 'modules/meta/details' );

	// Include organizer meta if appropriate
	if ( tribe_has_organizer() ) {
		tribe_get_template_part( 'modules/meta/organizer' );
	}

	if( ! did_action( 'tribe_events_single_event_meta_primary_section_end' ) ) {
		do_action( 'tribe_events_single_event_meta_primary_section_end' );
	}

	if ( $not_skeleton ) {
		echo enlightenment_close_tag( 'div' );
	}

	if( ! did_action( 'tribe_events_single_meta_after' ) ) {
		do_action( 'tribe_events_single_meta_after' );
	}

	if( ! did_action( 'tribe_events_single_event_after_the_meta' ) ) {
		do_action( 'tribe_events_single_event_after_the_meta' );
	}
}

function enlightenment_events_meta_venue() {
	// Check for skeleton mode (no outer wrappers per section)
	$not_skeleton = ! apply_filters( 'tribe_events_single_event_the_meta_skeleton', false, get_the_ID() );

	if ( $not_skeleton ) {
		echo enlightenment_open_tag( 'div', 'tribe-events-single-section tribe-events-event-meta secondary tribe-clearfix' );
	}

	if( ! did_action( 'tribe_events_single_event_meta_secondary_section_start' ) ) {
		do_action( 'tribe_events_single_event_meta_secondary_section_start' );
	}

	// Iclude the event venue
	tribe_get_template_part( 'modules/meta/venue' );

	// Embed Google Map
	tribe_get_template_part( 'modules/meta/map' );

	if( ! did_action( 'tribe_events_single_event_meta_secondary_section_end' ) ) {
		do_action( 'tribe_events_single_event_meta_secondary_section_end' );
	}

	if ( $not_skeleton ) {
		echo enlightenment_close_tag( 'div' );
	}
}

function enlightenment_tribe_community_events_list_add_new_button() {
    if ( ! function_exists( 'tribe_community_events_add_event_link' ) ) {
        return;
    }

    if ( ! is_user_logged_in() ) {
        return;
    }

	$output = sprintf(
        '<a class="tribe-button tribe-button-primary add-new" href="%1$s">%2$s</a>',
        esc_url( tribe_community_events_add_event_link() ),
        apply_filters( 'tribe_community_events_add_event_label', __( 'Add New', 'enlightenment' ) )
    );

    echo apply_filters( 'enlightenment_tribe_community_events_list_add_new_button', $output );
}

function enlightenment_tribe_event_list_search() {
    ob_start();
    do_action( 'tribe_community_events_before_list_navigation' );
    $output  = ob_get_clean();
    $output .= '<div class="tribe-event-list-search">';
    $output .= '<form role="search" method="get" class="tribe-search-form" action="">';
    $output .= '<div>';
    $output .= sprintf(
        '<label class="screen-reader-text" for="s">%s</label>',
        esc_html__( 'Search for:', 'enlightenment' )
    );
    $output .= sprintf(
        '<input type="search" value="%s" name="event-search" placeholder="%s" />',
        isset( $_GET['event-search'] ) ? esc_html( $_GET['event-search'] ) : '',
        esc_html__( 'Search Event Titles', 'enlightenment' )
    );
    $output .= sprintf(
        '<input type="submit" id="search-submit" value="%s" />',
        esc_attr__( 'Search', 'enlightenment' )
    );
    $output .= '</div>';
    $output .= '</form>';
    $output .= '</div>';

    echo apply_filters( 'enlightenment_tribe_event_list_search', $output );
}

function enlightenment_tribe_community_events_list_events_link() {
    if ( ! function_exists( 'tribe_community_events_list_events_link' ) ) {
        return;
    }

    if ( ! is_user_logged_in() ) {
        return;
    }

	$output = sprintf(
        '<a href="%1$s" class="tribe-button tribe-button-secondary" >%2$s</a>',
        esc_url( tribe_community_events_list_events_link() ),
        esc_html__( 'View Your Submitted Events', 'enlightenment' )
    );

    echo apply_filters( 'enlightenment_tribe_community_events_list_events_link', $output );
}
