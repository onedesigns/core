<?php

function enlightenment_events_layout_hooks() {
	if (
		is_post_type_archive( 'tribe_events' )
		||
		is_tax( 'tribe_events_cat' )
		||
		is_singular( 'tribe_venue' )
		||
		is_tax( 'tec_venue_category' )
		||
		is_singular( 'tribe_organizer' )
		||
		is_tax( 'tec_organizer_category' )
		||
		is_singular( 'tribe_event_series' )
	) {
		remove_action( 'enlightenment_page_content', 'enlightenment_the_loop' );
		remove_action( 'enlightenment_no_entries', 'enlightenment_no_posts_found' );

		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_description' );

		add_action( 'enlightenment_before_page_content', 'enlightenment_tribe_events_open_container', 1 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_events_loader', 5 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_events_json_ld_data', 5 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_events_data', 5 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_before_events', 5 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_tribe_events_header_open_container', 8 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_tribe_events_header_close_container', 12 );

		add_action( 'enlightenment_page_content', 'enlightenment_events_filter_bar_calendar_wrapper_maybe_open_container', 8 );
		add_action( 'enlightenment_page_content', 'enlightenment_events_filter_bar' );
		add_action( 'enlightenment_page_content', 'enlightenment_events_filter_bar_calendar_wrapper_maybe_close_container', 12 );

		add_action( 'enlightenment_before_the_loop', 'enlightenment_events_calendar_wrapper_maybe_open_container', 8 );
		add_action( 'enlightenment_after_the_loop', 'enlightenment_events_calendar_wrapper_maybe_close_container', 12 );

		add_action( 'enlightenment_entry_header', 'enlightenment_event_wrapper_open_container', 8 );

		if ( is_post_type_archive( 'tribe_events' ) || is_tax( 'tribe_events_cat' ) || is_singular( 'tribe_event_series' ) ) {
			add_action( 'enlightenment_before_page_content', 'enlightenment_events_messages' );

			if ( is_singular( 'tribe_event_series' ) ) {
				add_action( 'enlightenment_before_page_content', 'enlightenment_tribe_event_series_description' );
			}

			add_action( 'enlightenment_before_page_content', 'enlightenment_events_bar' );
			add_action( 'enlightenment_before_page_content', 'enlightenment_events_top_bar' );

			add_action( 'enlightenment_before_entry_content', 'enlightenment_event_details_open_container', 8 );
			add_action( 'enlightenment_after_entry_footer', 'enlightenment_close_div', 12 );

			switch ( enlightenment_tribe_get_view() ) {
				case 'list':
					add_action( 'enlightenment_before_entry_header', 'enlightenment_event_row_open_container', 8 );

					add_action( 'enlightenment_after_entry_footer', 'enlightenment_close_div', 16 );

				case 'day':
					add_action( 'enlightenment_page_content', 'enlightenment_the_loop' );

					add_action( 'enlightenment_page_content', 'enlightenment_events_ical_link' );
					add_action( 'enlightenment_page_content', 'enlightenment_after_events', 15 );

					break;

				case 'summary':
					add_action( 'enlightenment_page_content', 'enlightenment_events_summary' );
					add_action( 'enlightenment_page_content', 'enlightenment_events_ical_link' );

					add_action( 'enlightenment_after_page_content', 'enlightenment_after_events', 15 );

					break;

				case 'month':
					add_action( 'enlightenment_page_content', 'enlightenment_events_month_calendar' );
					add_action( 'enlightenment_page_content', 'enlightenment_events_month_mobile_events' );
					add_action( 'enlightenment_page_content', 'enlightenment_events_ical_link' );

					add_action( 'enlightenment_after_page_content', 'enlightenment_after_events', 15 );

					break;

				case 'week':
					add_action( 'enlightenment_page_content', 'enlightenment_events_week_day_selector' );
					add_action( 'enlightenment_page_content', 'enlightenment_events_week_mobile_events' );
					add_action( 'enlightenment_page_content', 'enlightenment_events_week_grid' );
					add_action( 'enlightenment_page_content', 'enlightenment_events_ical_link' );

					add_action( 'enlightenment_after_page_content', 'enlightenment_after_events', 15 );

					break;

				case 'photo':
					add_action( 'enlightenment_page_content', 'enlightenment_the_loop' );

					add_action( 'enlightenment_before_entries', 'enlightenment_tribe_events_photo_view_open_row', 997 );

					add_action( 'enlightenment_after_entry_header', 'enlightenment_photo_event_details_wrapper_open_container', 8 );
					add_action( 'enlightenment_after_entry_footer', 'enlightenment_close_div', 14 );

					add_action( 'enlightenment_after_entries', 'enlightenment_close_div', 3 );

					add_action( 'enlightenment_page_content', 'enlightenment_events_ical_link' );
					add_action( 'enlightenment_page_content', 'enlightenment_after_events', 15 );

					break;

				case 'map':
					add_action( 'enlightenment_page_content', 'enlightenment_events_map' );
					add_action( 'enlightenment_page_content', 'enlightenment_events_ical_link' );

					add_action( 'enlightenment_after_page_content', 'enlightenment_after_events', 15 );

					break;
			}
		} elseif (
			is_singular( 'tribe_venue' )
			||
			is_tax( 'tec_venue_category' )
			||
			is_singular( 'tribe_organizer' )
			||
			is_tax( 'tec_organizer_category' )
		) {
			remove_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
			remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
			remove_action( 'enlightenment_after_entry_footer', 'enlightenment_comments_template' );

			add_action( 'enlightenment_before_page_content', 'enlightenment_events_messages' );

			if ( is_singular( 'tribe_venue' ) ) {
				add_action( 'enlightenment_before_page_content', 'enlightenment_tribe_venue_meta' );
			} elseif ( is_singular( 'tribe_organizer' ) ) {
				add_action( 'enlightenment_before_page_content', 'enlightenment_tribe_organizer_meta' );
			} elseif ( is_tax( 'tec_venue_category' ) || is_tax( 'tec_organizer_category' ) ) {
				add_action( 'enlightenment_before_page_content', 'enlightenment_events_content_title' );
			}

			add_action( 'enlightenment_before_page_content', 'enlightenment_events_bar' );
			add_action( 'enlightenment_before_page_content', 'enlightenment_events_top_bar' );

			add_action( 'enlightenment_page_content', 'enlightenment_the_loop' );

			add_action( 'enlightenment_before_entry', 'enlightenment_events_separator' );

			add_action( 'enlightenment_before_entry_header', 'enlightenment_event_row_open_container', 8 );

			add_action( 'enlightenment_before_entry_header', 'enlightenment_event_date_tag' );

			add_action( 'enlightenment_entry_header', 'enlightenment_post_thumbnail' );

			add_action( 'enlightenment_before_entry_content', 'enlightenment_event_details_open_container', 8 );

			add_action( 'enlightenment_entry_content', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_content', 'enlightenment_entry_meta' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );

			add_action( 'enlightenment_entry_footer', 'enlightenment_event_cost' );

			add_action( 'enlightenment_after_entry_footer', 'enlightenment_close_div', 12 );

			add_action( 'enlightenment_after_entry_footer', 'enlightenment_close_div', 16 );

			add_action( 'enlightenment_after_entries_list', 'enlightenment_posts_nav' );

			add_action( 'enlightenment_page_content', 'enlightenment_events_ical_link' );
			add_action( 'enlightenment_page_content', 'enlightenment_after_events', 15 );
		}

		add_action( 'enlightenment_after_entry_footer', 'enlightenment_event_wrapper_close_container', 14 );

		add_action( 'enlightenment_after_page_content', 'enlightenment_tribe_events_close_container', 20 );

		add_action( 'enlightenment_after_page_content', 'enlightenment_events_breakpoints', 30 );
	} elseif ( is_singular( 'tribe_events' ) ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
		remove_action( 'tribe_events_single_event_after_the_content', array( tribe( 'tec.iCal' ), 'single_event_links' ) );

		if ( class_exists( 'Tribe__Events__Pro__Main' ) ) {
			remove_action( 'tribe_events_single_event_after_the_meta', array( Tribe__Events__Pro__Main::instance(), 'register_related_events_view' ) );
		}

		if ( ! tribe_get_option( 'showComments', false ) ) {
			remove_action( 'enlightenment_after_entry_footer', 'enlightenment_comments_template' );
		}

		add_action( 'enlightenment_entry_header', 'enlightenment_single_event_back_link' );

		add_action( 'enlightenment_entry_header', 'enlightenment_tribe_the_notices' );
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );

		try {
	        $template = tribe( 'events.editor.template' );
	    } catch ( Exception $e ) {
	        $template = false;
	    }

		if ( ! $template || ! has_blocks( get_the_ID() ) ) {
			add_action( 'enlightenment_entry_header', 'enlightenment_post_thumbnail' );

			add_action( 'enlightenment_entry_content', 'enlightenment_single_event_before_the_content', 5 );
			add_action( 'enlightenment_entry_content', 'enlightenment_single_event_ical_links' );
			add_action( 'enlightenment_entry_content', 'enlightenment_single_event_after_the_content', 15 );

			add_action( 'enlightenment_entry_footer', 'enlightenment_single_event_meta' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_related_events' );
		}
	} elseif ( enlightenment_tribe_is_community_my_events_page() ) {
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
		remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );
	} elseif ( enlightenment_tribe_community_tickets_is_frontend_sales_report() ) {
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
		remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );
	}
}
add_action( 'wp', 'enlightenment_events_layout_hooks' );

function enlightenment_event_lead_entry_hooks() {
	if ( enlightenment_tribe_is_community_my_events_page() ) {
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
		remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );

		add_action( 'enlightenment_entry_header', 'enlightenment_tribe_community_events_list_add_new_button' );
		add_action( 'enlightenment_entry_header', 'enlightenment_tribe_event_list_search' );
	}

	if (
		! is_post_type_archive( 'tribe_events' )
		&&
		! is_tax( 'tribe_events_cat' )
	) {
		return;
	}

	switch ( enlightenment_tribe_get_view() ) {
		case 'list':
			add_action( 'enlightenment_before_entry_header', 'enlightenment_event_date_tag' );

		case 'day':
			remove_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
			remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
			// Fix for is_search() returning true in rest request
			remove_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );

			add_action( 'enlightenment_before_entry', 'enlightenment_events_separator' );

			add_action( 'enlightenment_entry_content', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_content', 'enlightenment_entry_meta' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );

			add_action( 'enlightenment_entry_footer', 'enlightenment_event_cost' );

			break;
	}
}
add_action( 'enlightenment_before_entry', 'enlightenment_event_lead_entry_hooks', 5 );

function enlightenment_event_tag_archive_entry_hooks() {
	if ( ! is_tag() || 'tribe_events' != get_post_type() ) {
		return;
	}

	if ( has_blocks( get_the_ID() ) ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
	} else {
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
	}
}
add_action( 'enlightenment_before_entry', 'enlightenment_event_tag_archive_entry_hooks', 5 );

function enlightenment_event_teaser_entry_hooks() {
	if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
		return;
	}

	if ( 'photo' != enlightenment_tribe_get_view() ) {
		return;
	}

	if( ! current_theme_supports( 'enlightenment-grid-loop' ) ) {
		return;
	}

	if( enlightenment_is_lead_post() ) {
		return;
	}

	remove_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
	remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
	remove_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );

	add_action( 'enlightenment_after_entry_header', 'enlightenment_event_date_tag' );
	add_action( 'enlightenment_entry_content', 'enlightenment_entry_meta' );
	add_action( 'enlightenment_entry_content', 'enlightenment_entry_title' );
	add_action( 'enlightenment_entry_content', 'enlightenment_event_cost' );
}
add_action( 'enlightenment_before_entry', 'enlightenment_event_teaser_entry_hooks', 5 );

function enlightenment_community_events_pages_hooks() {
	if ( enlightenment_tribe_is_community_my_events_page() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );

		add_action( 'enlightenment_entry_header', 'enlightenment_tribe_community_events_list_add_new_button' );
		add_action( 'enlightenment_entry_header', 'enlightenment_tribe_event_list_search' );
	} elseif ( enlightenment_tribe_is_community_edit_event_page() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );

		add_action( 'enlightenment_entry_header', 'enlightenment_tribe_community_events_list_events_link' );
	} elseif ( enlightenment_tribe_community_tickets_is_frontend_attendees_report() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
	} elseif ( enlightenment_tribe_community_tickets_is_frontend_sales_report() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
	}
}
add_action( 'enlightenment_before_entry', 'enlightenment_community_events_pages_hooks', 5 );

function enlightenment_tribe_ar_page_template_hooks() {
	if ( ! enlightenment_tribe_is_ar_page() ) {
        return;
    }

	remove_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
	remove_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
	remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );

	remove_action( 'enlightenment_after_entries_list', 'enlightenment_posts_nav' );
}
add_action( 'wp', 'enlightenment_tribe_ar_page_template_hooks', 50 );

function enlightenment_tribe_ar_entry_hooks() {
	if ( ! enlightenment_tribe_is_ar_page() ) {
        return;
    }

	remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
}
add_action( 'enlightenment_before_entry', 'enlightenment_tribe_ar_entry_hooks', 50 );
