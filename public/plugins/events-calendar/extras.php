<?php

function enlightenment_tribe_filter_option( $option, $optionName ) {
	switch ( $optionName ) {
		case 'tribeEventsTemplate':
			$option = '';
			break;

		case 'views_v2_enabled':
			$option = true;
			break;
	}

	return $option;
}
add_filter( 'tribe_get_option', 'enlightenment_tribe_filter_option', 10, 2 );

function enlightenment_tribe_display_settings_tab_fields( $fields ) {
	if ( isset( $fields['tribeEventsTemplate'] ) ) {
		unset( $fields['tribeEventsTemplate'] );
	}

	if ( isset( $fields['views_v2_enabled'] ) ) {
		unset( $fields['views_v2_enabled'] );
	}

	return $fields;
}
add_filter( 'tribe_display_settings_tab_fields', 'enlightenment_tribe_display_settings_tab_fields' );

/**
 * Enqueue scripts and styles.
 */
function enlightenment_events_scripts() {
	wp_dequeue_style( 'tribe-events-full-calendar-style' );
	wp_dequeue_style( 'tribe-events-full-pro-calendar-style' );
	wp_dequeue_style( 'tribe-events-calendar-style' );
}
add_action( 'wp_enqueue_scripts', 'enlightenment_events_scripts', 12 );

function enlightenment_events_photo_grid( $grid ) {
	if( function_exists( 'tribe_is_photo' ) && tribe_is_photo() ) {
		if( current_theme_supports( 'enlightenment-bootstrap' ) ) {
			return array(
				'smartphone-portrait'  => 'onecol',
				'smartphone-landscape' => 'inherit',
				'tablet-portrait'      => 'twocol',
				'tablet-landscape'     => 'threecol',
				'desktop-laptop'       => 'inherit',
			);
		}

		return 'threecol';
	}

	return $grid;
}
add_filter( 'enlightenment_current_grid', 'enlightenment_events_photo_grid' );

function enlightenment_events_content_container() {
	echo enlightenment_open_tag( 'div', 'col-lg-8 push-lg-4' );
}

function enlightenment_events_meta_container() {
	if( has_action( 'enlightenment_entry_footer' ) ) {
		echo enlightenment_close_tag( 'div' );
		echo enlightenment_open_tag( 'div', 'col-lg-3 pull-lg-6 stickit' );
	}
}

function enlightenment_events_extra_container() {
	echo enlightenment_open_tag( 'div', 'col-lg-3' );
}

function enlightenment_tribe_events_page_title( $output ) {
	global $wp_query;

	if (
		is_post_type_archive( 'tribe_events' )
		||
		is_tax( 'tribe_events_cat' )
		||
		tribe_is_venue()
		||
		tribe_is_organizer()
		||
		tribe_context()->is( 'tec_venue_category' )
		||
		tribe_context()->is( 'tec_organizer_category' )
	) {
		$template      = enlightenment_tribe_template();
		$view          = $template->get_view();
		$template_vars = $view->get_template_vars();

		if ( ! empty( $template_vars['header_title'] ) ) {
			return $template_vars['header_title'];
		}

		if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
			if (
				! isset( $_REQUEST['tribe-bar-date'] )
				&&
				isset( $_REQUEST['view_data'] )
				&&
				isset( $_REQUEST['view_data']['tribe-bar-date'] )
			) {
				$_REQUEST['tribe-bar-date'] = $_REQUEST['view_data']['tribe-bar-date'];
			}

			if ( isset( $_REQUEST['tribe-bar-date'] ) ) {
				$wp_query->set( 'start_date', date( 'Y-m-d H:i:s', strtotime( $_REQUEST['tribe-bar-date'] ) ) );
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
		}

		$output = tribe_get_events_title();

		if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
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

			$url_object = $view->get_url_object();
			$query_args = $url_object->get_query_args();

			if (
				( in_array( $view->get_view_slug(), array( 'list', 'summary', 'map', 'venue', 'organizer' ) ) )
				&&
				isset( $query_args['tribe-bar-date'] )
				&&
				$wp_query->have_posts()
			) {
				$events_label_plural = tribe_get_event_label_plural();

				if ( $wp_query->get( 'featured' ) ) {
					$events_label_plural = sprintf( _x( 'Featured %s', 'featured events title', 'enlightenment'), $events_label_plural );
				}

				$first_returned_date = tribe_get_start_date( $wp_query->posts[0], false, Tribe__Date_Utils::DBDATEFORMAT );
				$first_event_date    = tribe_get_start_date( $wp_query->posts[0], false );
				$last_event_date     = tribe_get_end_date( $wp_query->posts[ count( $wp_query->posts ) - 1 ], false );

				// If we are on page 1 then we may wish to use the *selected*
				// start date in place of the first returned event date
				if ( 1 == $wp_query->get( 'paged' ) && $query_args['tribe-bar-date'] < $first_returned_date ) {
					$first_event_date = tribe_format_date( $query_args['tribe-bar-date'], false );
				}

				$output = sprintf( __( '%1$s for %2$s - %3$s', 'enlightenment' ), $events_label_plural, $first_event_date, $last_event_date );
			}

			if (
				( isset( $meta_query['_eventvenueid_in'] ) && 1 === count( $meta_query['_eventvenueid_in']['value'] ) )
				||
				isset( $meta_query['_eventorganizerid_in'] ) && 1 === count( $meta_query['_eventorganizerid_in']['value'] )
			) {
				$label = '';

				if ( ( isset( $meta_query['_eventvenueid_in'] ) && 1 === count( $meta_query['_eventvenueid_in']['value'] ) ) ) {
					$label = get_post( $meta_query['_eventvenueid_in']['value'][0] )->post_title;
				} elseif ( ( isset( $meta_query['_eventorganizerid_in'] ) && 1 === count( $meta_query['_eventorganizerid_in']['value'] ) ) ) {
					$label = get_post( $meta_query['_eventorganizerid_in']['value'][0] )->post_title;
				}

				if ( ! empty( $label ) ) {
					$output = sprintf(
						'<a href="%s">%s</a> &#8250; %s',
						esc_url( tribe_get_events_link() ),
						$output,
						esc_html( $label )
					);
				}
			}
		}
	}

	return $output;
}
add_filter( 'get_the_archive_title', 'enlightenment_tribe_events_page_title' );

function enlightenment_tribe_events_page_description( $output ) {
	if ( tribe_is_venue() || tribe_is_organizer() ) {
		return '';
	}

	$taxonomy = '';

	if ( is_tag() ) {
		$taxonomy = 'post_tag';
	} elseif ( tribe_context()->is( 'tec_venue_category' ) ) {
		$taxonomy = 'tec_venue_category';
	} elseif ( tribe_context()->is( 'tec_organizer_category' ) ) {
		$taxonomy = 'tec_organizer_category';
	}

	if ( ! empty( $taxonomy ) ) {
		$tax_slug = tribe_context()->get( $taxonomy );
		$tax_obj  = get_term_by( 'slug', $tax_slug, $taxonomy );

		if ( $tax_obj instanceof WP_Term ) {
			return $tax_obj->description;
		}
	}

	return $output;
}
add_filter( 'get_the_archive_description', 'enlightenment_tribe_events_page_description' );

add_filter( 'tribe_events_single_event_the_meta_group_venue', '__return_true' );

function enlightenment_filter_tribe_get_event_categories( $output ) {
	$label  = tribe_get_event_label_singular();
	$output = str_replace( $label, '', $output );

	return $output;
}
add_filter( 'tribe_get_event_categories', 'enlightenment_filter_tribe_get_event_categories' );

function enlightenment_events_entry_meta_args( $args ) {
	if ( 'tribe_events' != get_post_type() ) {
		return $args;
	}

	if ( is_post_type_archive( 'tribe_events' ) || is_tax( 'tribe_events_cat' ) ) {
	    $template = enlightenment_tribe_template();
		$event    = enlightenment_tribe_event();
		$view     = enlightenment_tribe_get_view();

		switch ( $view ) {
			case 'photo':
				ob_start();
				$template->template( sprintf( '%s/event/date-time', $view ), [ 'event' => $event ] );
				$args['format'] = ob_get_clean();
				break;

			default:
				ob_start();
				$template->template( sprintf( '%s/event/date', $view ), [ 'event' => $event ] );
				$date = ob_get_clean();

				ob_start();
				$template->template( sprintf( '%s/event/venue', $view ), [ 'event' => $event ] );
				$venue = ob_get_clean();

				$args['format'] = $date . $venue;
				break;
		}
	} elseif ( ( is_tag() || is_search() ) && 'tribe_events' == get_post_type() ) {
		$event_id = get_the_ID();
		$event    = get_post( $event_id );

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

		ob_start();
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

		$args['format'] = ob_get_clean();
	} elseif ( is_singular( 'tribe_events' ) ) {
		if ( has_blocks( get_the_ID() ) ) {
			$args['format']  = enlightenment_single_event_recurring_description( array( 'echo' => false ) );
		} else {
			$args['format']  = '<div class="tribe-events-schedule tribe-clearfix">';
			$args['format'] .= tribe_events_event_schedule_details( get_the_ID(), '<span class="tribe-events-schedule-details">', '</span>' );

			if ( tribe_get_cost() ) {
				$args['format'] .= '<span class="tribe-events-cost">';
				$args['format'] .= tribe_get_cost( null, true );
				$args['format'] .= '</span>';
			}

			$args['format'] .= '</div>';
		}
	}

	return $args;
}
add_filter( 'enlightenment_entry_meta_args', 'enlightenment_events_entry_meta_args', 12 );

function enlightenment_filter_tribe_get_venue_details( $details ) {
	if( doing_action( 'enlightenment_entry_header' ) ) {
		$details = implode( ' ', $details );
		$details = sprintf( $details, 'venue-name', 'venue-address', '' );
		$details = sprintf( '<span class="event-venue">%s</span>', $details );
	}

	return $details;
}
add_filter( 'tribe_get_venue_details', 'enlightenment_filter_tribe_get_venue_details' );

function enlightenment_events_remove_comments_template_filters() {
	if( is_singular( 'tribe_events' ) && tribe_get_option( 'showComments', false ) ) {
		remove_all_filters( 'comments_template', 10 );
	}
}
add_action( 'template_redirect', 'enlightenment_events_remove_comments_template_filters', 20 );
