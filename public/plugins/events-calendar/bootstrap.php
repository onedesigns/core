<?php

function enlightenment_tribe_bootstrap_display_settings_tab_fields( $fields ) {
	if ( isset( $fields['stylesheet_mode'] ) ) {
		unset( $fields['stylesheet_mode'] );
	}

	if ( isset( $fields['stylesheetOption'] ) ) {
		unset( $fields['stylesheetOption'] );
	}

	return $fields;
}
add_filter( 'tribe_display_settings_tab_fields', 'enlightenment_tribe_bootstrap_display_settings_tab_fields' );

function enlightenment_tribe_bootstrap_maybe_remove_template_include_filter() {
    if ( ! is_singular( 'tribe_events' ) ) {
		return;
	}

    remove_filter( 'template_include', array( tribe( Tribe\Events\Views\V2\Hooks::class ), 'filter_template_include' ), 50 );

	if ( ! tribe_is_showing_all() && tribe_is_past_event() ) {
		Tribe__Notices::set_notice( 'event-past', sprintf( esc_html__( 'This %s has passed.', 'enlightenment' ), tribe_get_event_label_singular_lowercase() ) );
	}
}
add_action( 'template_redirect', 'enlightenment_tribe_bootstrap_maybe_remove_template_include_filter' );

function enlightenment_tribe_bootstrap_prevent_print_styles( $enqueue, $asset ) {
	if ( in_array( $asset->get_slug(), array(
		'tec-variables-skeleton',
		'tribe-common-skeleton-style',
		'tribe-tooltipster-css',
		'tribe-events-views-v2-skeleton',
		'tec-variables-full',
		'tribe-common-full-style',
		'tribe-events-views-v2-full',
		'tribe-events-pro-views-v2-skeleton',
		'tribe-events-pro-views-v2-full',
		'tribe-events-views-v2-print',
		'tribe-events-pro-views-v2-print',
		'tribe-events-filterbar-views-v2-print'
	) ) ) {
		return false;
	}

	return $enqueue;
}
add_filter( 'tribe_asset_enqueue', 'enlightenment_tribe_bootstrap_prevent_print_styles', 10, 2 );

/**
 * Dequeue styles.
 */
function enlightenment_tribe_bootstrap_dequeue_assets() {
	wp_deregister_style( 'tribe-common-skeleton-style' );
	wp_deregister_style( 'tribe-common-full-style' );
	wp_deregister_style( 'tribe-events-views-v2-skeleton' );
	wp_deregister_style( 'tribe-events-views-v2-full' );
	wp_deregister_style( 'tribe-events-v2-single-skeleton' );
	wp_deregister_style( 'tribe-events-v2-single-skeleton-full' );
    wp_deregister_style( 'tribe-tooltipster-css' );
	wp_dequeue_style( 'tribe-tooltip' );
	wp_deregister_style( 'tribe-events-full-calendar-style' );
	wp_deregister_style( 'tribe-events-calendar-style' );
	wp_deregister_style( 'tribe-events-widgets-v2-events-list-skeleton' );
	wp_dequeue_script( 'tribe-events-views-v2-ical-links' );

	// Events Calendar Pro
	wp_deregister_style( 'tribe-events-pro-views-v2-full' );
	wp_deregister_style( 'tribe-events-pro-views-v2-skeleton' );
	wp_dequeue_style( 'tribe-events-calendar-pro-style' );
	wp_dequeue_style( 'tribe-events-calendar-full-pro-mobile-style' );
	wp_dequeue_style( 'tribe-events-calendar-pro-mobile-style' );
	wp_deregister_style( 'tribe-events-full-pro-calendar-style' );
	wp_deregister_style( 'tec-events-pro-single' );
	wp_deregister_style( 'tec-events-pro-single-style' );
	wp_dequeue_style( 'tribe-events-pro-widgets-v2-events-list-skeleton' );

	// Filter Bar
	wp_deregister_style( 'tribe-filterbar-styles' );
	wp_deregister_style( 'tribe-filterbar-mobile-styles' );
	wp_dequeue_style( 'tribe-events-filterbar-views-v2-print' );

	// Event Tickets
	wp_deregister_style( 'event-tickets-tickets-rsvp-css' );
	wp_dequeue_style( 'event-tickets-rsvp' );
	wp_dequeue_style( 'event-tickets-tpp-css' );
	wp_deregister_style( 'tribe-tickets-gutenberg-block-rsvp-style' );
	wp_deregister_style( 'event-tickets-plus-tickets-css' );
	wp_dequeue_style( 'TribeEventsWooTickets' );
	wp_deregister_style( 'tribe-tickets-forms-style' );
	wp_dequeue_style( 'tribe-tickets-plus-iac-styles' );
	wp_dequeue_style( 'tribe-tickets-plus-attendee-tickets-styles' );
	wp_dequeue_style( 'tribe-tickets-plus-registration-page-styles' );
	wp_deregister_style( 'tec-tickets-plus-waitlist-frontend-style' );

	// Community Events
	wp_dequeue_style( 'tribe_events-community-styles' );
	wp_dequeue_style( 'tribe-events-admin-ui' );
	wp_dequeue_style( 'tribe_events-admin' );
	wp_dequeue_style( 'tribe_events-recurrence' );
	wp_dequeue_style( 'tec-events-pro-editor-events-css' );
	wp_dequeue_style( 'tec-events-pro-editor-events-classic-css' );

	// Community Tickets
	wp_dequeue_style( 'common' );
	wp_dequeue_style( 'events-community-tickets-css' );
	wp_dequeue_style( 'events-community-tickets-shortcodes-css' );
	wp_dequeue_style( 'tickets-report-css' );
	wp_dequeue_style( 'list-tables' );
	wp_dequeue_style( 'event-tickets-plus-meta-admin-css' );

	// Virtual Events
	wp_deregister_style( 'tribe-events-virtual-single-skeleton' );
	wp_deregister_style( 'tribe-events-virtual-single-full' );
	wp_deregister_style( 'tribe-events-virtual-single-v2-skeleton' );
	wp_deregister_style( 'tribe-events-virtual-single-v2-full' );
	wp_dequeue_style( 'tribe-events-virtual-admin-css' );
	wp_dequeue_style( 'tribe-events-virtual-zoom-admin-style' );
	wp_dequeue_style( 'tribe-events-virtual-widgets-v2-events-list-skeleton' );
}
add_action( 'wp_enqueue_scripts', 'enlightenment_tribe_bootstrap_dequeue_assets', 5 );
add_action( 'wp_enqueue_scripts', 'enlightenment_tribe_bootstrap_dequeue_assets', 25 );

function enlightenment_tribe_bootstrap_dequeue_footer_assets() {
	wp_dequeue_style( 'tribe-tickets-gutenberg-block-rsvp-style' );
	wp_dequeue_style( 'tribe-tickets-gutenberg-block-attendees-style' );
	wp_dequeue_style( 'tribe-dialog' );
	wp_dequeue_style( 'tribe-tickets-plus-modal-styles' );
	wp_dequeue_style( 'tribe-tickets-plus-iac-styles' );
	wp_dequeue_style( 'tribe-tickets-plus-attendee-tickets-styles' );
	wp_dequeue_style( 'tribe-tickets-plus-registration-page-styles' );
	wp_dequeue_style( 'tribe-events-virtual-widgets-v2-common-skeleton' );
	wp_dequeue_style( 'tec-events-pro-archives-style' );
	wp_dequeue_script( 'tribe-events-views-v2-ical-links' );

	wp_dequeue_style( 'tribe-tickets-orders-style' );

	// Community Events
	wp_dequeue_style( 'tribe-events-admin-ui' );
	wp_dequeue_style( 'tribe_events-admin' );
	wp_dequeue_style( 'tribe_events-recurrence' );
	wp_dequeue_style( 'tribe_events-community-styles' );
	wp_dequeue_style( 'tribe-events-community-shortcodes' );
	wp_dequeue_style( 'tribe-events-full-calendar-style' );
	wp_dequeue_style( 'tec-tickets-wallet-plus-passes-css' );
}
add_filter( 'wp_print_footer_scripts', 'enlightenment_tribe_bootstrap_dequeue_footer_assets', 8 );

function enlightenment_tribe_bootstrap_theme_stylesheet_deps( $deps ) {
	if ( class_exists( 'Tribe__Events__Filterbar__View' ) ) {
		if ( tribe_is_event_query() || tribe_is_event_organizer() || tribe_is_event_venue() ) {
			$deps[] = 'tribe-select2-css';
		}
	}

	if ( enlightenment_tribe_is_community_edit_event_page() ) {
		$deps[] = 'tribe-select2-css';
	}

	return $deps;
}
add_filter( 'enlightenment_theme_stylesheet_deps', 'enlightenment_tribe_bootstrap_theme_stylesheet_deps' );

function enlightenment_tribe_events_community_before_shortcode() {
	wp_enqueue_style( 'tribe-select2-css' );
}
add_action( 'tribe_events_community_before_shortcode', 'enlightenment_tribe_events_community_before_shortcode' );

function enlightenment_tribe_bootstrap_remove_grid_loop_content_row() {
	if( is_singular() || is_admin() || ! enlightenment_bootstrap_is_grid_active() ) {
		return;
	}

	if ( ! is_post_type_archive( 'tribe_events' ) && ! is_tax( 'tribe_events_cat' ) ) {
		return;
	}

	if ( 'photo' != enlightenment_tribe_get_view() ) {
		return;
	}

	remove_action( 'enlightenment_before_entries', 'enlightenment_open_row', 999 );
	remove_action( 'enlightenment_after_entries', 'enlightenment_close_row', 1 );
}
add_action( 'wp', 'enlightenment_tribe_bootstrap_remove_grid_loop_content_row', 12 );

function enlightenment_tribe_bootstrap_events_photo_view_row_args( $args ) {
	$args['container_class'] .= ' row';

	return $args;
}
add_filter( 'enlightenment_tribe_events_photo_view_row_args', 'enlightenment_tribe_bootstrap_events_photo_view_row_args' );

function enlightenment_tribe_bootstrap_photo_event_details_wrapper_args( $args ) {
	$args['container_class'] .= ' row';

	return $args;
}
add_filter( 'enlightenment_photo_event_details_wrapper_args', 'enlightenment_tribe_bootstrap_photo_event_details_wrapper_args' );

function enlightenment_tribe_bootstrap_template_output( $output ) {
	return str_replace( 'class="tribe-common-a11y-visual-hide screen-reader-text"', 'class="tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_output', 'enlightenment_tribe_bootstrap_template_output' );

function enlightenment_tribe_bootstrap_events_header_container_class( $class ) {
	$class .= ' row';

	return $class;
}
add_filter( 'enlightenment_tribe_events_header_container_class', 'enlightenment_tribe_bootstrap_events_header_container_class' );

// [tribe_events] shortcode only
function enlightenment_tribe_bootstrap_template_view_output( $output ) {
	$output = str_replace( 'class="tribe-events-header ', 'class="tribe-events-header row ', $output );
	$output = str_replace( 'class="tribe-events-header"', 'class="tribe-events-header row"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_list_output',    'enlightenment_tribe_bootstrap_template_view_output' );
add_filter( 'enlightenment_tribe_filter_template_month_output',   'enlightenment_tribe_bootstrap_template_view_output' );
add_filter( 'enlightenment_tribe_filter_template_day_output',     'enlightenment_tribe_bootstrap_template_view_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_output', 'enlightenment_tribe_bootstrap_template_view_output' );
add_filter( 'enlightenment_tribe_filter_template_week_output',    'enlightenment_tribe_bootstrap_template_view_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_output',   'enlightenment_tribe_bootstrap_template_view_output' );
add_filter( 'enlightenment_tribe_filter_template_map_output',     'enlightenment_tribe_bootstrap_template_view_output' );

function enlightenment_tribe_bootstrap_events_before_html( $output ) {
	$start = strpos( $output, '<span class="tribe-events-ajax-loading">' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</span>', $start ) + 7;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	return $output;
}
add_filter( 'tribe_events_before_html',     'enlightenment_tribe_bootstrap_events_before_html' );

function enlightenment_tribe_bootstrap_template_components_loader_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-view-loader__text tribe-common-a11y-visual-hide screen-reader-text"', 'class="tribe-events-view-loader__text tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );

	$offset = strpos( $output, '<div class="tribe-events-view-loader__dots tribe-common-c-loader">' );
	if ( false !== $offset ) {
		$start  = $offset + 66;
		$end    = strpos( $output, '</div>', $start );
		$length = $end - $start;
		$output = substr_replace( $output, '<i class="fas fa-spinner fa-pulse" role="presentation" aria-hidden="true"></i>', $start, $length );
	}

	$offset = strpos( 'class="tribe-events-loader__dots tribe-common-c-loader ', $output );
	if ( false !== $offset ) {
		$start  = strpos( '>', $output, $offset ) + 1;
		$end    = strpos( '</div>', $output, $offset );
		$length = $end - $start;
		$output = substr_replace( $output, '<i class="fas fa-spinner fa-pulse" role="presentation" aria-hidden="true"></i>', $start, $length );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_loader_output', 'enlightenment_tribe_bootstrap_template_components_loader_output' );

function enlightenment_tribe_bootstrap_template_components_messages_output( $output ) {
	$output = str_replace( 'class="tribe-events-header__messages tribe-events-c-messages tribe-common-b2 tribe-events-header__messages--mobile"', 'class="tribe-events-header__messages tribe-events-c-messages tribe-common-b2 tribe-events-header__messages--mobile d-none" aria-hidden="true"', $output );
	$output = str_replace( 'class="tribe-events-header__messages ', 'class="tribe-events-header__messages col-12 ', $output );
	$output = str_replace( 'class="tribe-events-c-messages__message-list"', 'class="tribe-events-c-messages__message-list list-unstyled mb-0"', $output );
	$output = str_replace( 'class="tribe-events-c-messages__message-list-item"', 'class="tribe-events-c-messages__message-list-item alert alert-info" role="alert"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_messages_output', 'enlightenment_tribe_bootstrap_template_components_messages_output' );

add_filter( 'enlightenment_tribe_filter_template_components_icons_messages_not_found_output', '__return_false' );

function enlightenment_events_bootstrap_filter_bar_calendar_wrapper_args( $args ) {
	$args['container_class'] .= ' row';

	return $args;
}
add_filter( 'enlightenment_events_filter_bar_calendar_wrapper_args', 'enlightenment_events_bootstrap_filter_bar_calendar_wrapper_args' );

function enlightenment_events_bootstrap_calendar_wrapper_args( $args ) {
	$args['container_class'] .= ' col-lg-9';

	return $args;
}
add_filter( 'enlightenment_events_calendar_wrapper_args', 'enlightenment_events_bootstrap_calendar_wrapper_args' );

function enlightenment_tribe_bootstrap_toggle_filter_bar_button( $output ) {
	$class = 'navbar-toggler';

	if ( class_exists( 'Tribe\Events\Filterbar\Views\V2_1\Filters' ) ) {
		$layout = tribe( Tribe\Events\Filterbar\Views\V2_1\Filters::class )->get_layout_setting();
	} else {
		$layout = tribe( Tribe\Events\Filterbar\Views\V2\Filters::class )->get_layout_setting();
	}

	switch ( $layout ) {
		case 'horizontal':
			$state = tribe_get_option( 'events_filters_default_state', 'closed' );

			if ( 'open' == $state ) {
				$class .= ' d-lg-none';
			}

			break;

		case 'vertical':
			$class .= ' d-lg-none';

			break;
	}

	$output = str_replace( 'class="tribe-events-c-events-bar__filter-button ', sprintf( 'class="tribe-events-c-events-bar__filter-button %s ', $class ), $output );
	$output = str_replace( 'class="tribe-events-c-events-bar__filter-button"', sprintf( 'class="tribe-events-c-events-bar__filter-button %s"', $class ), $output );
	$output = str_replace( 'data-js="tribe-events-filter-button"', 'data-js="tribe-events-filter-button" data-bs-toggle="collapse" data-bs-target="#tribe-filter-bar-filters-container"', $output );
	$output = str_replace( 'class="tribe-events-c-events-bar__filter-button-text tribe-common-b2 tribe-common-a11y-visual-hide screen-reader-text"', 'class="tribe-events-c-events-bar__filter-button-text tribe-common-b2 tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );

	return $output;
}
add_filter( 'enlightenment_events_toggle_filter_bar_button', 'enlightenment_tribe_bootstrap_toggle_filter_bar_button' );

function enlightenment_tribe_bootstrap_template_components_icons_filter_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--filter' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-sliders-h" role="presentation" aria-hidden="true"></i>', esc_attr( $classes ) );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_filter_output', 'enlightenment_tribe_bootstrap_template_components_icons_filter_output', 10, 4 );

function enlightenment_tribe_bootstrap_filter_bar( $output ) {
	$output = trim( $output );

	if ( empty( $output ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-filter-bar-wrapper"', 'class="tribe-events-filter-bar-wrapper col-lg-3"', $output );

	if ( class_exists( 'Tribe\Events\Filterbar\Views\V2_1\Filters' ) ) {
		$output = enlightenment_tribe_bootstrap_filter_bar_v2_1( $output );
	} else {
		$output = enlightenment_tribe_bootstrap_filter_bar_v2( $output );
	}

    return $output;
}
add_filter( 'enlightenment_events_filter_bar', 'enlightenment_tribe_bootstrap_filter_bar' );

function enlightenment_tribe_bootstrap_filter_bar_v2_1( $output ) {
	$output = str_replace( 'class="tribe-filter-bar__form-description tribe-common-a11y-visual-hide screen-reader-text"', 'class="tribe-filter-bar__form-description tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );

	$output = str_replace( 'class="tribe-filter-bar__selected-filters-label tribe-common-h7 screen-reader-text"', 'class="tribe-filter-bar__selected-filters-label tribe-common-h7 screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-clear-button ', 'class="tribe-filter-bar-c-clear-button btn btn-secondary ', $output );
	$output = str_replace( 'class="tribe-filter-bar__selected-filters-header"', 'class="tribe-filter-bar__selected-filters-header order-1"', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-pill__remove-button-text tribe-common-a11y-visual-hide"', 'class="tribe-filter-bar-c-pill__remove-button-text tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-pill tribe-filter-bar-c-pill--has-selections tribe-filter-bar__selected-filter"', 'class="tribe-filter-bar-c-pill tribe-filter-bar-c-pill--has-selections tribe-filter-bar__selected-filter d-flex"', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-pill__pill tribe-common-b2 tribe-common-b3--min-medium"', 'class="tribe-filter-bar-c-pill__pill tribe-common-b2 tribe-common-b3--min-medium me-1"', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-pill__remove-button"', 'class="tribe-filter-bar-c-pill__remove-button p-0 border-0 bg-transparent ms-auto"', $output );

	$output = str_replace( 'class="tribe-filter-bar__actions"', 'class="tribe-filter-bar__actions order-1 d-none"', $output );
	$output = str_replace( 'class="tribe-filter-bar__action-done tribe-common-c-btn-border tribe-common-c-btn-border--secondary"', 'class="tribe-filter-bar__action-done tribe-common-c-btn-border tribe-common-c-btn-border--secondary btn btn-secondary"', $output );

	$output = str_replace( 'class="tribe-filter-bar-c-filter__toggle tribe-common-b1 tribe-common-b2--min-medium"', 'class="tribe-filter-bar-c-filter__toggle tribe-common-b1 tribe-common-b2--min-medium nav-link dropdown-toggle border-0 bg-transparent" data-bs-toggle="dropdown"', $output );
	$output = str_replace( 'data-js="tribe-events-accordion-trigger tribe-filter-bar-c-filter-toggle"', '', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-filter__toggle-text"', 'class="tribe-filter-bar-c-filter__toggle-text d-inline"', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-filter__toggle-icon ', 'class="tribe-filter-bar-c-filter__toggle-icon d-none ', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-filter__toggle-icon-text tribe-common-a11y-visual-hide screen-reader-text"', 'class="tribe-filter-bar-c-filter__toggle-icon-text tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-filter__container"', 'class="tribe-filter-bar-c-filter__container dropdown-menu dropdown-menu-end"', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-filter__filters-legend tribe-common-h6 tribe-common-h--alt tribe-common-a11y-visual-hide"', 'class="tribe-filter-bar-c-filter__filters-legend tribe-common-h6 tribe-common-h--alt tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );

	$output = str_replace( 'class="tribe-filter-bar-c-checkbox tribe-common-form-control-checkbox"', 'class="tribe-filter-bar-c-checkbox tribe-common-form-control-checkbox form-check dropdown-item bg-transparent text-reset mb-0"', $output );
	$output = str_replace( 'class="tribe-common-form-control-checkbox__input"', 'class="tribe-common-form-control-checkbox__input form-check-input"', $output );
	$output = str_replace( 'class="tribe-common-form-control-checkbox__label"', 'class="tribe-common-form-control-checkbox__label form-check-label"', $output );

	$output = str_replace( 'class="tribe-filter-bar-c-radio tribe-common-form-control-radio"', 'class="tribe-filter-bar-c-radio tribe-common-form-control-radio form-check dropdown-item bg-transparent text-reset mb-0"', $output );
	$output = str_replace( 'class="tribe-common-form-control-radio__input"', 'class="tribe-common-form-control-radio__input form-check-input"', $output );
	$output = str_replace( 'class="tribe-common-form-control-radio__label"', 'class="tribe-common-form-control-radio__label form-check-label"', $output );

	$output = str_replace( 'class="tribe-filter-bar-c-dropdown ', 'class="tribe-filter-bar-c-dropdown dropdown-item bg-transparent text-reset ', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-dropdown"', 'class="tribe-filter-bar-c-dropdown dropdown-item bg-transparent text-reset"', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-multiselect ', 'class="tribe-filter-bar-c-multiselect dropdown-item bg-transparent text-reset ', $output );
	$output = str_replace( 'class="tribe-filter-bar-c-multiselect"', 'class="tribe-filter-bar-c-multiselect dropdown-item bg-transparent text-reset"', $output );

	$layout = tribe( Tribe\Events\Filterbar\Views\V2_1\Filters::class )->get_layout_setting();

	switch ( $layout ) {
		case 'vertical':
			$output = str_replace( 'class="tribe-filter-bar tribe-filter-bar--vertical ', 'class="tribe-filter-bar tribe-filter-bar--vertical navbar navbar-expand-lg p-0 ', $output );
			$output = str_replace( 'class="tribe-filter-bar tribe-filter-bar--vertical"', 'class="tribe-filter-bar tribe-filter-bar--vertical navbar navbar-expand-lg p-0"', $output );
			$output = str_replace( 'class="tribe-filter-bar__form"', 'class="tribe-filter-bar__form collapse navbar-collapse flex-column align-items-start" id="tribe-filter-bar-filters-container"', $output );

			$output = str_replace( 'class="tribe-filter-bar__selected-filters"', 'class="tribe-filter-bar__selected-filters d-flex flex-column w-100"', $output );

			$output = str_replace( 'class="tribe-filter-bar__filters-container"', 'class="tribe-filter-bar__filters-container w-100"', $output );
			$output = str_replace( 'class="tribe-filter-bar__filters"', 'class="tribe-filter-bar__filters navbar-nav flex-column w-100"', $output );
			$output = str_replace( 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--accordion ', 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--accordion nav-item dropdown ', $output );
			$output = str_replace( 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--accordion"', 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--accordion nav-item dropdown"', $output );

			break;

		case 'horizontal':
			$class = 'navbar';
			$state = tribe_get_option( 'events_filters_default_state', 'closed' );

			if ( 'open' == $state ) {
				$class .= ' navbar-expand-lg';
			}

			$output = str_replace( 'class="tribe-filter-bar tribe-filter-bar--horizontal ', sprintf( 'class="tribe-filter-bar tribe-filter-bar--horizontal %s ', $class ), $output );
			$output = str_replace( 'class="tribe-filter-bar tribe-filter-bar--horizontal"', sprintf( 'class="tribe-filter-bar tribe-filter-bar--horizontal %s"', $class ), $output );
			$output = str_replace( 'class="tribe-filter-bar__form"', 'class="tribe-filter-bar__form collapse navbar-collapse flex-nowrap align-items-center justify-content-between w-100" id="tribe-filter-bar-filters-container"', $output );

			$output = str_replace( 'class="tribe-filter-bar__form-heading tribe-common-h5 tribe-common-h--alt tribe-common-a11y-visual-hide screen-reader-text"', 'class="tribe-filter-bar__form-heading tribe-common-h5 tribe-common-h--alt tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );


			$output = str_replace( 'class="tribe-filter-bar__selected-filters"', 'class="tribe-filter-bar__selected-filters order-1"', $output );
			$output = str_replace( 'class="tribe-filter-bar__selected-filters-list-container"', 'class="tribe-filter-bar__selected-filters-list-container d-lg-none"', $output );

			$output = str_replace( 'class="tribe-filter-bar__filters"', 'class="tribe-filter-bar__filters navbar-nav flex-lg-row"', $output );
			$output = str_replace( 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--pill"', 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--pill nav-item dropdown"', $output );
			$output = str_replace( 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--pill ', 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--pill nav-item dropdown ', $output );
			$output = str_replace( 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--has-selections tribe-filter-bar-c-filter--pill"', 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--has-selections tribe-filter-bar-c-filter--pill nav-item dropdown"', $output );
			$output = str_replace( 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--has-selections tribe-filter-bar-c-filter--pill ', 'class="tribe-filter-bar-c-filter tribe-filter-bar-c-filter--has-selections tribe-filter-bar-c-filter--pill nav-item dropdown ', $output );
			$output = str_replace( 'class="tribe-filter-bar-c-filter__remove-button"', 'class="tribe-filter-bar-c-filter__remove-button p-0 border-0 bg-transparent d-none"', $output );
			$output = str_replace( 'class="tribe-filter-bar-c-filter__remove-button-text tribe-common-a11y-visual-hide screen-reader-tex"', 'class="tribe-filter-bar-c-filter__remove-button-text tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );
			$output = str_replace( 'class="tribe-filter-bar-c-filter__filters-close"', 'class="tribe-filter-bar-c-filter__filters-close d-none"', $output );

			$output = str_replace( 'class="tribe-filter-bar__filters-slider-container swiper-container"', 'class="tribe-filter-bar__filters-slider-container swiper-container d-none"', $output );
			$output = str_replace( 'class="tribe-filter-bar__filters-slider-container tribe-swiper-container"', 'class="tribe-filter-bar__filters-slider-container tribe-swiper-container d-none"', $output );
			$output = str_replace( 'class="tribe-filter-bar__filters-slider-wrapper swiper-wrapper"', 'class="tribe-filter-bar__filters-slider-wrapper swiper-wrapper navbar-nav"', $output );
			$output = str_replace( 'class="tribe-filter-bar__filters-slide swiper-slide"', 'class="tribe-filter-bar__filters-slide swiper-slide nav-item dropdown"', $output );
			$output = str_replace( 'class="tribe-filter-bar-c-pill__pill tribe-common-b2 tribe-common-b3--min-medium"', 'class="tribe-filter-bar-c-pill__pill tribe-common-b2 tribe-common-b3--min-medium nav-link dropdown-toggle border-0 bg-transparent" data-bs-toggle="dropdown"', $output );
			$output = str_replace( 'class="tribe-filter-bar-c-pill__remove-button"', 'class="tribe-filter-bar-c-pill__remove-button d-none"', $output );
			$output = str_replace( 'class="tribe-filter-bar__filters-slider-nav"', 'class="tribe-filter-bar__filters-slider-nav d-none"', $output );

			break;
	}

	$offset = strpos( $output, '<div class="tribe-filter-bar-c-filter__toggle-wrapper">' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, '', $offset, 55 );
		$offset = strpos( $output, '<button', $offset );
		$output = substr_replace( $output, 'a', $offset + 1, 6 );
		$offset = strpos( $output, 'type="button"', $offset );
		$output = substr_replace( $output, 'href="#"', $offset, 13 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, 'a', $offset + 2, 6 );
		// $offset = strpos( $output, '</button>', $offset );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '', $offset, 6 );

		$offset = strpos( $output, '<div class="tribe-filter-bar-c-filter__toggle-wrapper">', $offset );
	}

	$offset = strpos( $output, '<h3 class="tribe-filter-bar-c-filter__toggle-heading">' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, '', $offset, 54 );
		$offset = strpos( $output, '</h3>', $offset );
		$output = substr_replace( $output, '', $offset, 5 );

		$offset = strpos( $output, '<h3 class="tribe-filter-bar-c-filter__toggle-heading">', $offset );
	}

	$offset = strpos( $output, '<h3 class="tribe-filter-bar-c-filter__remove-heading">' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, '', $offset, 54 );
		$offset = strpos( $output, '</h3>', $offset );
		$output = substr_replace( $output, '', $offset, 5 );

		$offset = strpos( $output, '<h3 class="tribe-filter-bar-c-filter__remove-heading">', $offset );
	}

	$offset = strpos( $output, '<h3 class="tribe-filter-bar-c-filter__close-heading">' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, '', $offset, 53 );
		$offset = strpos( $output, '</h3>', $offset );
		$output = substr_replace( $output, '', $offset, 5 );

		$offset = strpos( $output, '<h3 class="tribe-filter-bar-c-filter__close-heading">', $offset );
	}

	return $output;
}

function enlightenment_tribe_bootstrap_filter_bar_v2( $output ) {
	$layout = tribe( Tribe\Events\Filterbar\Views\V2\Filters::class )->get_layout_setting();

	switch ( $layout ) {
		case 'vertical':
			$output = str_replace( 'class="tribe-events-filters-vertical tribe-clearfix"', 'class="tribe-events-filters-vertical navbar navbar-expand-l bg-light p-0"', $output );
			$output = str_replace( 'class="tribe-events-filters-content tribe-clearfix"', 'class="tribe-events-filters-content d-flex flex-column w-100"', $output );
			$output = str_replace( 'class="tribe-events-filters-label"', 'class="tribe-events-filters-label px-3 py-2 px-lg-2 h5"', $output );
			$output = str_replace( 'class="tribe_events_filter_control tribe-clearfix"', 'class="tribe_events_filter_control d-lg-none"', $output );
			$output = str_replace( 'class="tribe_events_filters_show_filters tribe-js-filters-toggle"', 'class="tribe_events_filters_show_filters tribe-js-filters-toggle navbar-toggler w-100 text-align-center"', $output );
			$output = str_replace( 'class="tribe_events_filter_control tribe-events-filters-mobile-controls tribe-clearfix"', 'class="tribe_events_filter_control tribe-events-filters-mobile-controls d-lg-none"', $output );
			$output = str_replace( '<form ', '<div class="collapse navbar-collapse"><form ', $output );
			$output = str_replace( 'id="tribe_events_filters_form"', 'id="tribe_events_filters_form" class="navbar-nav flex-column w-100"', $output );

			$start = strpos( $output, 'class="tribe_events_filter_control tribe-events-filters-mobile-controls ' );
			if ( false !== $start ) {
				$offset = strpos( $output, '</div>', $start );
				$output = substr_replace( $output, '</div>', $offset + 6, 0 );
			}

			$output = str_replace( '<input type="submit"', '<div class="tribe-events-filters-buttons-wrap px-3 py-2 px-lg-2"><input type="submit"', $output );
			$output = str_replace( 'class="tribe_events_filters_reset tribe_events_filters_reset--desktop tribe-js-filters-reset"', 'class="tribe_events_filters_reset tribe_events_filters_reset--desktop tribe-js-filters-reset d-none d-lg-inline-block btn btn-link"', $output );

			$start = strpos( $output, 'class="tribe_events_filters_reset tribe_events_filters_reset--desktop ' );
			if ( false !== $start ) {
				$offset = strpos( $output, '</button>', $start );
				$output = substr_replace( $output, '</div>', $offset + 9, 0 );
			}

			$output = str_replace( 'class="tribe_events_filters_close_filters tribe_events_filters_toggle tribe-js-filters-toggle"', 'class="tribe_events_filters_close_filters tribe_events_filters_toggle tribe-js-filters-toggle btn btn-link"', $output );
			$output = str_replace( 'class="tribe_events_filters_reset tribe-js-filters-reset"', 'class="tribe_events_filters_reset tribe-js-filters-reset btn btn-link"', $output );

			break;

		case 'horizontal':
			$output = str_replace( 'class="tribe-events-filters-horizontal tribe-clearfix"', 'class="tribe-events-filters-horizontal navbar navbar-expand-lg bg-light"', $output );
			$output = str_replace( 'class="tribe-events-filters-content tribe-clearfix"', 'class="tribe-events-filters-content d-flex flex-wrap flex-lg-nowrap align-items-center justify-content-between w-100"', $output );
			$output = str_replace( 'class="tribe_events_filter_control tribe-clearfix"', 'class="tribe_events_filter_control order-1"', $output );
			$output = str_replace( 'class="tribe_events_filters_show_filters tribe-js-filters-toggle"', 'class="tribe_events_filters_show_filters tribe-js-filters-toggle navbar-toggler w-100 text-align-center"', $output );
			$output = str_replace( 'class="tribe_events_filters_close_filters tribe-js-filters-toggle"', 'class="tribe_events_filters_close_filters tribe-js-filters-toggle btn btn-link"', $output );
			$output = str_replace( 'class="tribe_events_filters_close_filters tribe_events_filters_toggle tribe-js-filters-toggle"', 'class="tribe_events_filters_close_filters tribe_events_filters_toggle tribe-js-filters-toggle btn btn-link"', $output );
			$output = str_replace( 'class="tribe_events_filters_reset tribe-js-filters-reset"', 'class="tribe_events_filters_reset tribe-js-filters-reset btn btn-link"', $output );
			$output = str_replace( 'class="tribe_events_filter_control tribe-events-filters-mobile-controls tribe-clearfix"', 'class="tribe_events_filter_control tribe-events-filters-mobile-controls d-lg-none"', $output );
			$output = str_replace( '<form ', '<div class="collapse navbar-collapse"><form ', $output );
			$output = str_replace( 'id="tribe_events_filters_form"', 'id="tribe_events_filters_form" class="navbar-nav"', $output );

			$start = strpos( $output, 'class="tribe_events_filter_control tribe-events-filters-mobile-controls ' );
			if ( false !== $start ) {
				$offset = strpos( $output, '</div>', $start );
				$output = substr_replace( $output, '</div>', $offset + 6, 0 );
			}

			$output = str_replace( '<input type="submit"', '<div class="tribe-events-filters-buttons-wrap d-lg-flex align-items-lg-center"><input type="submit"', $output );
			$output = str_replace( 'class="tribe_events_filters_reset tribe_events_filters_reset--desktop tribe-js-filters-reset"', 'class="tribe_events_filters_reset tribe_events_filters_reset--desktop tribe-js-filters-reset d-none d-lg-inline-block btn btn-link"', $output );
			$output = str_replace( 'class="tribe_events_filters_form_submit" tabindex="-1" />', 'class="tribe_events_filters_form_submit" tabindex="-1" /></div>', $output );

			break;
	}

	$output = str_replace( 'class="a11y-hidden screen-reader-text"', 'class="a11y-hidden screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe_events_filters_form_submit"', 'class="tribe_events_filters_form_submit btn btn-secondary"', $output );

	return $output;
}

function enlightenment_tribe_bootstrap_template_components_icons_reset_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--reset' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-times" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_reset_output', 'enlightenment_tribe_bootstrap_template_components_icons_reset_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_close_alt_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--reset' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-times" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_close_alt_output', 'enlightenment_tribe_bootstrap_template_components_icons_close_alt_output', 10, 4 );

add_action( 'tribe_events_filter_view_do_display_filters', 'enlightenment_ob_start', 1 );

function enlightenment_tribe_bootstrap_display_filters( $context ) {
	$output = ob_get_clean();

	$output = str_replace( 'class="tribe_events_filter_item ', 'class="tribe_events_filter_item nav-item dropdown ', $output );
	$output = str_replace( 'class="tribe_events_filter_item"', 'class="tribe_events_filter_item nav-item dropdown"', $output );
	$output = str_replace( 'role="group"', 'class="dropdown" role="group"', $output );

	$offset = strpos( $output, '<legend class="tribe-events-filters-legend">' );
	while ( false !== $offset ) {
		$controls = '';
		$offset_a = strpos( $output, 'aria-controls="tribe-filter-', $offset );
		if ( false !== $offset_a ) {
			$offset_a = strpos( $output, '"', $offset_a ) + 1;
			$end_a    = strpos( $output, '"', $offset_a );
			$length_a = $end_a - $offset_a;
			$controls = substr( $output, $offset_a, $length_a );
		}

		$output = substr_replace(
			$output,
			sprintf( '<a class="tribe-events-filters-legend nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"%s>', empty( $controls ) ? '' : sprintf( ' aria-controls="%s"', $controls ) ),
			$offset,
			44
		);
		$output = str_replace(
			sprintf( '<button class="tribe-events-filters-group-heading" type="button" aria-expanded="false" aria-controls="%s">', $controls ),
			'<span class="tribe-events-filters-group-heading">',
			$output
		);
		$output = str_replace(
			sprintf( '<button class="tribe-events-filters-group-heading" type="button" aria-expanded="true" aria-controls="%s">', $controls ),
			'<span class="tribe-events-filters-group-heading">',
			$output
		);

		$offset = strpos( $output, '<legend class="tribe-events-filters-legend">', $offset + 1 );
	}
	$output = str_replace( '</legend>', '</a>', $output );
	$output = str_replace( '</button>', '</span>', $output );

	$offset = strpos( $output, 'class="tribe-events-filter-group tribe-events-filter-select2 tribe-events-filter-select"' );
	while ( false !== $offset ) {
		$offset = strpos( $output, 'class="tribe-section-content-field"', $offset );
		$output = substr_replace( $output, ' dropdown-item bg-transparent text-reset', $offset + 34, 0 );

		$offset = strpos( $output, 'class="tribe-events-filter-group tribe-events-filter-select2 tribe-events-filter-select"', $offset );
	}

	$offset = strpos( $output, 'class="tribe-events-filter-group tribe-events-filter-select2 tribe-events-filter-multiselect"' );
	while ( false !== $offset ) {
		$offset = strpos( $output, 'class="tribe-section-content-field"', $offset );
		$output = substr_replace( $output, ' dropdown-item bg-transparent text-reset', $offset + 34, 0 );

		$offset = strpos( $output, 'class="tribe-events-filter-group tribe-events-filter-select2 tribe-events-filter-multiselect"', $offset );
	}

	$output = str_replace( 'class="tribe-events-filter-group ', 'class="tribe-events-filter-group dropdown-menu ', $output );
	$output = str_replace( '<ul>', '<ul class="list-unstyled mb-0">', $output );
	$output = str_replace( '<li class="', '<li class="form-check ', $output );
	$output = str_replace( '<li>', '<li class="form-check">', $output );
	$output = str_replace( '<li >', '<li class="form-check">', $output );
	$output = str_replace( '<input type="checkbox"', '<input class="form-check-input" type="checkbox"', $output );
	$output = str_replace( '<input type="radio"', '<input class="form-check-input" type="radio"', $output );
	$output = str_replace( '<label for="', '<label class="dropdown-item form-check-label" for="', $output );

	echo $output;
}
add_action( 'tribe_events_filter_view_do_display_filters', 'enlightenment_tribe_bootstrap_display_filters', 999 );

function enlightenment_bootstrap_single_event_back_link( $output ) {
	return str_replace( 'class="tribe-events-back"', 'class="tribe-events-back float-start me-3"', $output );
}
add_filter( 'enlightenment_single_event_back_link', 'enlightenment_bootstrap_single_event_back_link' );

function enlightenment_tribe_bootstrap_template_single_virtual_marker_output( $output ) {
	$output = str_replace( 'class="tribe-events-virtual-single-marker tribe-events-virtual-single-marker--mobile"', 'class="tribe-events-virtual-single-marker tribe-events-virtual-single-marker--mobile badge text-bg-light float-end d-md-none"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-marker"', 'class="tribe-events-virtual-single-marker badge text-bg-light"', $output );

	if ( ! doing_filter( 'tribe_events_event_schedule_details' ) ) {
		$output = str_replace( 'class="tribe-events-virtual-single-marker badge text-bg-light"', 'class="tribe-events-virtual-single-marker badge text-bg-light float-end d-none d-md-inline-block"', $output );
	}

	$output = str_replace( '<em', '<i', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-marker__icon"', 'class="tribe-events-virtual-single-marker__icon fas fa-broadcast-tower" role="presentation" aria-hidden="true"', $output );
	$output = str_replace( '</em>', '</i>', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_single_virtual_marker_mobile_output', 'enlightenment_tribe_bootstrap_template_single_virtual_marker_output' );
add_filter( 'enlightenment_tribe_filter_template_single_virtual_marker_output', 'enlightenment_tribe_bootstrap_template_single_virtual_marker_output' );

function enlightenment_tribe_bootstrap_template_single_hybrid_marker_output( $output ) {
	$output = str_replace( 'class="tribe-events-hybrid-single-marker tribe-events-hybrid-single-marker--mobile"', 'class="tribe-events-hybrid-single-marker tribe-events-hybrid-single-marker--mobile badge text-bg-light float-end d-md-none"', $output );
	$output = str_replace( 'class="tribe-events-hybrid-single-marker"', 'class="tribe-events-hybrid-single-marker badge text-bg-light"', $output );

	if ( ! doing_filter( 'tribe_events_event_schedule_details' ) ) {
		$output = str_replace( 'class="tribe-events-hybrid-single-marker badge text-bg-light"', 'class="tribe-events-hybrid-single-marker badge text-bg-light float-end d-none d-md-inline-block"', $output );
	}

	$output = str_replace( '<em' . "\n", '<i' . "\n", $output );
	$output = str_replace( 'class="tribe-events-hybrid-single-marker__icon"', 'class="tribe-events-hybrid-single-marker__icon fas fa-project-diagram" role="presentation" aria-hidden="true"', $output );
	$output = str_replace( '</em>', '</i>', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_single_hybrid_marker_mobile_output', 'enlightenment_tribe_bootstrap_template_single_hybrid_marker_output' );
add_filter( 'enlightenment_tribe_filter_template_single_hybrid_marker_output', 'enlightenment_tribe_bootstrap_template_single_hybrid_marker_output' );

function enlightenment_tribe_bootstrap_template_part_pro_widgets_this_week_single_event_output( $output ) {
	return str_replace( 'class="tribe-events-virtual-single-marker badge text-bg-light float-end d-none d-md-inline-block"', 'class="tribe-events-virtual-single-marker badge text-bg-light"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_part_pro_widgets_this_week_single_event_output', 'enlightenment_tribe_bootstrap_template_part_pro_widgets_this_week_single_event_output' );

add_filter( 'enlightenment_tribe_filter_template_components_icons_virtual_output', '__return_false' );
add_filter( 'enlightenment_tribe_filter_template_v2_components_icons_virtual_output', '__return_false' );

add_filter( 'enlightenment_tribe_filter_template_components_icons_hybrid_output', '__return_false' );
add_filter( 'enlightenment_tribe_filter_template_v2_components_icons_hybrid_output', '__return_false' );

function enlightenment_tribe_bootstrap_template_components_icons_featured_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--featured' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-bookmark" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_featured_output', 'enlightenment_tribe_bootstrap_template_components_icons_featured_output', 10, 4 );

function enlightenment_bootstrap_tribe_the_notices( $output ) {
	if ( false === strpos( $output, '<div class="tribe-events-notices">' ) ) {
		$output .= '<div class="clearfix"></div>';
	} else {
		$output = str_replace( '<div class="tribe-events-notices">', '<div class="clearfix"></div>' . "\n" . '<div class="tribe-events-notices">', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_the_notices', 'enlightenment_bootstrap_tribe_the_notices' );

function enlightenment_bootstrap_events_entry_meta_args( $args ) {
	if ( function_exists( 'tribe_is_photo' ) && tribe_is_photo() ) {
		$args['container_class'] .= ' clearfix';
	}

	return $args;
}
add_filter( 'enlightenment_entry_meta_args', 'enlightenment_bootstrap_events_entry_meta_args', 12 );

function enlightenment_tribe_bootstrap_template_components_virtual_event_output( $output ) {
	$output = str_replace( 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-virtual-event"', 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-virtual-event badge text-bg-light"', $output );

	if ( doing_filter( 'tribe_template_before_include:events-pro/v2/photo/event/date-time' ) ) {
		$output = str_replace( 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-virtual-event badge text-bg-light"', 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-virtual-event badge text-bg-light float-end"', $output );
	} elseif ( doing_filter( 'tribe_template_after_include:events/v2/widgets/widget-events-list/event/date/featured' ) ) {
		$output = str_replace( 'class="tribe-events-virtual-virtual-event__text"', 'class="tribe-events-virtual-virtual-event__text screen-reader-text visually-hidden"', $output );
	} elseif ( doing_filter( 'tribe_template_before_include:events-pro/v2/summary/date-group/event/title/featured' ) ) {
		$output = str_replace( 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-virtual-event badge text-bg-light"', 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-virtual-event"', $output );
		$output = str_replace( 'class="tribe-events-virtual-virtual-event__text screen-reader-text"', 'class="tribe-events-virtual-virtual-event__text screen-reader-text visually-hidden"', $output );
	}

	$output = str_replace( '<em', '<i', $output );
	$output = str_replace( 'class="tribe-events-virtual-virtual-event__icon"', 'class="tribe-events-virtual-virtual-event__icon fas fa-broadcast-tower" role="presentation" aria-hidden="true"', $output );
	$output = str_replace( '</em>', '</i>', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_virtual_event_output', 'enlightenment_tribe_bootstrap_template_components_virtual_event_output' );

function enlightenment_tribe_bootstrap_template_components_hybrid_event_output( $output ) {
	$output = str_replace( 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-hybrid-event"', 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-hybrid-event badge text-bg-light"', $output );

	if ( doing_filter( 'tribe_template_before_include:events-pro/v2/photo/event/date-time' ) ) {
		$output = str_replace( 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-hybrid-event badge text-bg-light"', 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-hybrid-event badge text-bg-light float-end"', $output );
	} elseif ( doing_filter( 'tribe_template_after_include:events/v2/widgets/widget-events-list/event/date/featured' ) ) {
		$output = str_replace( 'class="tribe-events-virtual-virtual-event__text"', 'class="tribe-events-virtual-virtual-event__text screen-reader-text visually-hidden"', $output );
	} elseif ( doing_filter( 'tribe_template_before_include:events-pro/v2/summary/date-group/event/title/featured' ) ) {
		$output = str_replace( 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-hybrid-event badge text-bg-light"', 'class="tribe-common-b2 tribe-common-b2--bold tribe-events-virtual-hybrid-event"', $output );
		$output = str_replace( 'class="tribe-events-virtual-virtual-event__text screen-reader-text"', 'class="tribe-events-virtual-virtual-event__text screen-reader-text visually-hidden"', $output );
	}

	$output = str_replace( '<em' . "\n", '<i' . "\n", $output );
	$output = str_replace( 'class="tribe-events-virtual-hybrid-event__icon"', 'class="tribe-events-virtual-hybrid-event__icon fas fa-project-diagram" role="presentation" aria-hidden="true"', $output );
	$output = str_replace( '</em>', '</i>', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_hybrid_event_output', 'enlightenment_tribe_bootstrap_template_components_hybrid_event_output' );

function enlightenment_tribe_bootstrap_template_components_link_button_output( $output ) {
	return str_replace( 'class="tribe-events-virtual-link-button"', 'class="tribe-events-virtual-link-button btn btn-primary btn-lg"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_components_link_button_output', 'enlightenment_tribe_bootstrap_template_components_link_button_output' );

function enlightenment_tribe_bootstrap_template_components_icons_play_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--play' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-play" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_play_output', 'enlightenment_tribe_bootstrap_template_components_icons_play_output', 10, 4 );
add_filter( 'enlightenment_tribe_filter_template_v2_components_icons_play_output', 'enlightenment_tribe_bootstrap_template_components_icons_play_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_facebook_single_facebook_embed_output( $output ) {
	$output = str_replace( 'class="tribe-events-virtual-single-facebook__embed mb-0 ', 'class="tribe-events-virtual-single-facebook__embed mb-0 ', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-facebook__embed mb-0"', 'class="tribe-events-virtual-single-facebook__embed mb-0"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-facebook__wrapper"', 'class="tribe-events-virtual-single-facebook__wrapper d-flex"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_facebook_single_facebook_embed_output', 'enlightenment_tribe_bootstrap_template_facebook_single_facebook_embed_output' );

function enlightenment_tribe_bootstrap_template_facebook_single_facebook_embed_offline_output( $output ) {
	$output = str_replace( 'class="ribe-events-virtual-single-facebook__embed-offline-title"', 'class="ribe-events-virtual-single-facebook__embed-offline-title alert alert-info"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-facebook__embed-offline-title"', 'class="tribe-events-virtual-single-facebook__embed-offline-title alert alert-info"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_facebook_single_facebook_embed_offline_output', 'enlightenment_tribe_bootstrap_template_facebook_single_facebook_embed_offline_output' );

function enlightenment_tribe_bootstrap_template_youtube_single_youtube_embed_output( $output ) {
	$output = str_replace( 'class="tribe-events-virtual-single-youtube__embed ', 'class="tribe-events-virtual-single-youtube__embed mb-0 ', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-youtube__embed"', 'class="tribe-events-virtual-single-youtube__embed mb-0"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-youtube__wrapper"', 'class="tribe-events-virtual-single-youtube__wrapper d-flex"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-youtube__chat-wrap"', 'class="tribe-events-virtual-single-youtube__chat-wrap mt-3 mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_youtube_single_youtube_embed_output', 'enlightenment_tribe_bootstrap_template_youtube_single_youtube_embed_output' );

function enlightenment_tribe_bootstrap_template_youtube_single_youtube_embed_offline_output( $output ) {
	$output = str_replace( 'class="ribe-events-virtual-single-youtube__embed-offline-title"', 'class="ribe-events-virtual-single-youtube__embed-offline-title alert alert-info"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-youtube__embed-offline-title"', 'class="tribe-events-virtual-single-youtube__embed-offline-title alert alert-info"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_youtube_single_youtube_embed_offline_output', 'enlightenment_tribe_bootstrap_template_youtube_single_youtube_embed_offline_output' );

function enlightenment_tribe_bootstrap_template_zoom_single_zoom_details_output( $output ) {
	$output = str_replace( 'class="tribe-events-virtual-single-zoom-details tribe-events-single-section tribe-events-event-meta tribe-clearfix"', 'class="tribe-events-virtual-single-zoom-details tribe-events-single-section tribe-events-event-meta row"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-zoom-details__meta-group tribe-events-virtual-single-zoom-details__meta-group--link-button tribe-events-meta-group"', 'class="tribe-events-virtual-single-zoom-details__meta-group tribe-events-virtual-single-zoom-details__meta-group--link-button tribe-events-meta-group col-12 col-md"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tribe-events-virtual-single-zoom-details__meta-group tribe-events-virtual-single-zoom-details__meta-group--link-button tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tribe-events-virtual-single-zoom-details__meta-group tribe-events-virtual-single-zoom-details__meta-group--link-button tribe-events-meta-group col-12 col-md"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-zoom-details__meta-group tribe-events-virtual-single-zoom-details__meta-group--zoom-link tribe-events-meta-group"', 'class="tribe-events-virtual-single-zoom-details__meta-group tribe-events-virtual-single-zoom-details__meta-group--zoom-link tribe-events-meta-group col-12 col-md d-flex align-items-start"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tribe-events-virtual-single-zoom-details__meta-group tribe-events-virtual-single-zoom-details__meta-group--zoom-link tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tribe-events-virtual-single-zoom-details__meta-group tribe-events-virtual-single-zoom-details__meta-group--zoom-link tribe-events-meta-group col-12 col-md d-flex align-items-start"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-zoom-details__meta-group tribe-events-virtual-single-zoom-details__meta-group--zoom-phone tribe-events-meta-group"', 'class="tribe-events-virtual-single-zoom-details__meta-group tribe-events-virtual-single-zoom-details__meta-group--zoom-phone tribe-events-meta-group col-12 col-md d-flex align-items-start"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-zoom-details__meta-group-content"', 'class="tribe-events-virtual-single-zoom-details__meta-group-content ms-3"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group-content tribe-events-virtual-single-zoom-details__meta-group-content"', 'class="tec-events-virtual-single-api-details__meta-group-content tribe-events-virtual-single-zoom-details__meta-group-content ms-3"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-zoom-details__zoom-link"', 'class="tribe-events-virtual-single-zoom-details__zoom-link d-block"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-api-details__video-link"', 'class="tribe-events-virtual-single-api-details__video-link d-block"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-zoom-details__zoom-id"', 'class="tribe-events-virtual-single-zoom-details__zoom-id d-block text-body-secondary"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__api-id tribe-events-virtual-single-zoom-details__zoom-id"', 'class="tec-events-virtual-single-api-details__api-id tribe-events-virtual-single-zoom-details__zoom-id d-block text-body-secondary"', $output );
	$output = str_replace( 'class="tribe-events-virtual-single-zoom-details__phone-number-list"', 'class="tribe-events-virtual-single-zoom-details__phone-number-list list-unstyled mb-0"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__phone-number-list tribe-events-virtual-single-zoom-details__phone-number-list"', 'class="tec-events-virtual-single-api-details__phone-number-list tribe-events-virtual-single-zoom-details__phone-number-list list-unstyled mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_zoom_single_zoom_details_output', 'enlightenment_tribe_bootstrap_template_zoom_single_zoom_details_output' );

function enlightenment_tribe_bootstrap_template_webex_single_webex_details_output( $output ) {
	$output = str_replace( 'class="tec-events-virtual-single-api-details tribe-events-single-section tribe-events-event-meta tribe-clearfix"', 'class="tec-events-virtual-single-api-details tribe-events-single-section tribe-events-event-meta row"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group tec-events-virtual-single-webex-details__meta-group--link-button tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group tec-events-virtual-single-webex-details__meta-group--link-button tribe-events-meta-group col-12 col-md"', $output );

	if (
		false !== strpos( $output, '<div class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group--webex-link tribe-events-meta-group">' )
		&&
		false !== strpos( $output, '<div class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group--webex-lock tribe-events-meta-group">' )
	) {
		$offset  = strpos( $output, '<div class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group--webex-link tribe-events-meta-group">' );
		$output  = substr_replace( $output, '<div class="col-12 col-md mt-3 mt-md-0">' . "\n", $offset, 0 );
		$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group--webex-link tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group--webex-link tribe-events-meta-group d-flex align-items-start"', $output );
		$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group--webex-lock tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group--webex-lock tribe-events-meta-group d-flex align-items-start mt-3"', $output );
		$output = str_replace( ' fa-2x fas ', ' fa-2x fa-fw fas ', $output );
		$output .= "\n" . '</div>';
	} else {
		$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group--webex-link tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group--webex-link tribe-events-meta-group col-12 col-md d-flex align-items-start"', $output );
		$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group--webex-lock tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-webex-details__meta-group--webex-lock tribe-events-meta-group col-12 col-md d-flex align-items-start"', $output );
	}

	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group-content tec-events-virtual-single-webex-details__meta-group-content"', 'class="tec-events-virtual-single-api-details__meta-group-content tec-events-virtual-single-webex-details__meta-group-content ms-3"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__link tec-events-virtual-single-api-details__webex-link"', 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__link tec-events-virtual-single-api-details__webex-link d-block"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__link tec-events-virtual-single-api-details__video-link tribe-events-virtual-single-webex-details__webex-link"', 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__link tec-events-virtual-single-api-details__video-link tribe-events-virtual-single-webex-details__webex-link d-block"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__webex-id"', 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__webex-id d-block text-body-secondary"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__api-id"', 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__api-id d-block text-body-secondary"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__webex-password"', 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__webex-password d-block text-body-secondary"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__api-password"', 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__api-password d-block text-body-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_webex_single_webex_details_output', 'enlightenment_tribe_bootstrap_template_webex_single_webex_details_output' );

function enlightenment_tribe_bootstrap_template_google_single_google_details_output( $output ) {
	$output = str_replace( 'class="tec-events-virtual-single-api-details tribe-events-single-section tribe-events-event-meta tribe-clearfix"', 'class="tec-events-virtual-single-api-details tribe-events-single-section tribe-events-event-meta row"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-google-details__meta-group tec-events-virtual-single-google-details__meta-group--link-button tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-google-details__meta-group tec-events-virtual-single-google-details__meta-group--link-button tribe-events-meta-group col-12 col-md"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-google-details__meta-group--google-link tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-google-details__meta-group--google-link tribe-events-meta-group col-12 col-md d-flex align-items-start"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group-content tec-events-virtual-single-google-details__meta-group-content"', 'class="tec-events-virtual-single-api-details__meta-group-content tec-events-virtual-single-google-details__meta-group-content ms-3"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__link tec-events-virtual-single-api-details__api-link"', 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__link tec-events-virtual-single-api-details__api-link d-block"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__api-id"', 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__api-id d-block text-body-secondary"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tribe-events-virtual-single-google-details__meta-group tribe-events-virtual-single-google-details__meta-group--google-phone tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tribe-events-virtual-single-google-details__meta-group tribe-events-virtual-single-google-details__meta-group--google-phone tribe-events-meta-group col-12 col-md d-flex align-items-start"', $output );
	$output = str_replace( 'class="tribe-common-c-svgicon tribe-common-c-svgicon--phone tec-events-virtual-single-api-details__icon tec-events-virtual-single-api-details__icon--phone fas fa-phone"', 'class="tribe-common-c-svgicon tribe-common-c-svgicon--phone tec-events-virtual-single-api-details__icon tec-events-virtual-single-api-details__icon--phone fa-2x fas fa-phone"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group-content tribe-events-virtual-single-google-details__meta-group-content"', 'class="tec-events-virtual-single-api-details__meta-group-content tribe-events-virtual-single-google-details__meta-group-content ms-3"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__phone-number-list tribe-events-virtual-single-google-details__phone-number-list"', 'class="tec-events-virtual-single-api-details__phone-number-list tribe-events-virtual-single-google-details__phone-number-list list-unstyled mb-0"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__phone-number tribe-events-virtual-single-google-details__phone-number"', 'class="tec-events-virtual-single-api-details__phone-number tribe-events-virtual-single-google-details__phone-number d-block"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__text tec-events-virtual-meetings-api__phone-list-item-pin"', 'class="tec-events-virtual-single-api-details__text tec-events-virtual-meetings-api__phone-list-item-pin d-block text-body-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_google_single_google_details_output', 'enlightenment_tribe_bootstrap_template_google_single_google_details_output' );

function enlightenment_tribe_bootstrap_template_microsoft_single_microsoft_details_output( $output ) {
	$output = str_replace( 'class="tec-events-virtual-single-api-details tribe-events-single-section tribe-events-event-meta tribe-clearfix"', 'class="tec-events-virtual-single-api-details tribe-events-single-section tribe-events-event-meta row"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-microsoft-details__meta-group tec-events-virtual-single-microsoft-details__meta-group--link-button tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-microsoft-details__meta-group tec-events-virtual-single-microsoft-details__meta-group--link-button tribe-events-meta-group col-12 col-md"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-microsoft-details__meta-group--microsoft-link tribe-events-meta-group"', 'class="tec-events-virtual-single-api-details__meta-group tec-events-virtual-single-microsoft-details__meta-group--microsoft-link tribe-events-meta-group col-12 col-md d-flex align-items-start"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__meta-group-content tec-events-virtual-single-microsoft-details__meta-group-content"', 'class="tec-events-virtual-single-api-details__meta-group-content tec-events-virtual-single-microsoft-details__meta-group-content ms-3"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__link tec-events-virtual-single-api-details__api-link"', 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__link tec-events-virtual-single-api-details__api-link d-block"', $output );
	$output = str_replace( 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__api-id"', 'class="tec-events-virtual-single-api-details__text tec-events-virtual-single-api-details__api-id d-block text-body-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_microsoft_single_microsoft_details_output', 'enlightenment_tribe_bootstrap_template_microsoft_single_microsoft_details_output' );

function enlightenment_tribe_bootstrap_template_components_icons_video_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--video' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );

		if (
			in_array( 'tribe-events-virtual-single-zoom-details__icon', $context['classes'] )
			||
			in_array( 'tec-events-virtual-single-api-details__icon', $context['classes'] )
		) {
			$classes = array_merge( $classes, array( 'fa-2x' ) );
		}
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-video" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_video_output', 'enlightenment_tribe_bootstrap_template_components_icons_video_output', 10, 4 );
add_filter( 'enlightenment_tribe_filter_template_v2_components_icons_video_output', 'enlightenment_tribe_bootstrap_template_components_icons_video_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_phone_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--phone' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );

		if ( in_array( 'tribe-events-virtual-single-zoom-details__icon', $context['classes'] ) ) {
			$classes = array_merge( $classes, array( 'fa-2x' ) );
		}
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-phone" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_phone_output', 'enlightenment_tribe_bootstrap_template_components_icons_phone_output', 10, 4 );
add_filter( 'enlightenment_tribe_filter_template_v2_components_icons_phone_output', 'enlightenment_tribe_bootstrap_template_components_icons_phone_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_lock_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--lock' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );

		if ( in_array( 'tec-events-virtual-single-api-details__icon', $context['classes'] ) ) {
			$classes = array_merge( $classes, array( 'fa-2x' ) );
		}
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-lock" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_lock_output', 'enlightenment_tribe_bootstrap_template_components_icons_lock_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_venue_meta_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();

	if ( $context['enable_maps'] && $context['show_map'] ) {
		$colspan = 'col-12 col-lg-6';
	} else {
		$colspan = 'col-12';
	}

	$output = str_replace( 'class="tribe-events-pro-venue__meta-row tribe-common-g-row"', 'class="tribe-events-pro-venue__meta-row tribe-common-g-row row"', $output );
	$output = str_replace( 'class="tribe-events-pro-venue__meta-data tribe-common-g-col"', sprintf( 'class="tribe-events-pro-venue__meta-data tribe-common-g-col %s"', $colspan ), $output );
	$output = str_replace( 'class="tribe-events-pro-venue__meta-map tribe-common-g-col"', sprintf( 'class="tribe-events-pro-venue__meta-map tribe-common-g-col %s"', $colspan ), $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_venue_meta_output', 'enlightenment_tribe_bootstrap_template_venue_meta_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_map_pin_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--map-pin' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-map-marker-alt" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_map_pin_output', 'enlightenment_tribe_bootstrap_template_components_icons_map_pin_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_mail_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--mail' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-envelope" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_mail_output', 'enlightenment_tribe_bootstrap_template_components_icons_mail_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_website_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--website' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-link" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_website_output', 'enlightenment_tribe_bootstrap_template_components_icons_website_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_venue_meta_map_output( $output ) {
	return str_replace( 'class="tribe-events-pro-venue__meta-data-google-maps-default"', 'class="tribe-events-pro-venue__meta-data-google-maps-default w-100 h-100 border-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_venue_meta_map_output', 'enlightenment_tribe_bootstrap_template_venue_meta_map_output' );

function enlightenment_tribe_bootstrap_template_organizer_meta_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$colspan = $context['organizer']->thumbnail->exists ? 9 : 12;

	$output = str_replace( 'class="tec-events-c-view-box-border"', 'class="tec-events-c-view-box-border row"', $output );
	$output = str_replace( 'class="tribe-events-pro-organizer__meta-row tribe-common-g-row tribe-common-g-row--gutters"', 'class="tribe-events-pro-organizer__meta-row tribe-common-g-row tribe-common-g-row--gutters row"', $output );
	$output = str_replace( 'class="tribe-events-pro-organizer__meta-data tribe-common-g-col"', sprintf( 'class="tribe-events-pro-organizer__meta-data tribe-common-g-col col-%d"', $colspan ), $output );
	$output = str_replace( 'class="tribe-events-pro-organizer__meta-row tribe-common-g-row"', 'class="tribe-events-pro-organizer__meta-row tribe-common-g-row row"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_organizer_meta_output', 'enlightenment_tribe_bootstrap_template_organizer_meta_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_organizer_meta_featured_image_output( $output ) {
	$output = str_replace( 'class="tribe-events-pro-organizer__meta-featured-image-wrapper tribe-common-g-col ', 'class="tribe-events-pro-organizer__meta-featured-image-wrapper tribe-common-g-col col-3 ', $output );
	$output = str_replace( 'class="tribe-events-pro-organizer__meta-featured-image-wrapper tribe-common-g-col"', 'class="tribe-events-pro-organizer__meta-featured-image-wrapper tribe-common-g-col col-3"', $output );
	$output = str_replace( 'class="tribe-events-pro-organizer__meta-featured-image"', 'class="tribe-events-pro-organizer__meta-featured-image img-fluid"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_organizer_meta_featured_image_output', 'enlightenment_tribe_bootstrap_template_organizer_meta_featured_image_output' );

function enlightenment_tribe_bootstrap_template_organizer_meta_details_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();

	if ( ! $context['has_details'] ) {
		return $output;
	}

	$content = tribe_get_the_content( null, false, $context['organizer']->ID );
	$colspan = empty( $content ) ? 'col-12' : 'col-12 col-lg-4';

	$output = str_replace( 'class="tribe-events-pro-organizer__meta-details ', sprintf( 'class="tribe-events-pro-organizer__meta-details %s ', $colspan ), $output );
	$output = str_replace( 'class="tribe-events-pro-organizer__meta-details"', sprintf( 'class="tribe-events-pro-organizer__meta-details %s"', $colspan ), $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_organizer_meta_details_output', 'enlightenment_tribe_bootstrap_template_organizer_meta_details_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_organizer_meta_content_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$content = tribe_get_the_content( null, false, $context['organizer']->ID );

	if ( empty( $content ) ) {
		return $output;
	}

	$colspan = $context['has_details'] ? 'col-12 col-lg-8 order-first' : 'col-12';

	return str_replace( 'class="tribe-events-pro-organizer__meta-content ', sprintf( 'class="tribe-events-pro-organizer__meta-content %s ', $colspan ), $output );
}
add_filter( 'enlightenment_tribe_filter_template_organizer_meta_content_output', 'enlightenment_tribe_bootstrap_template_organizer_meta_content_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_organizer_meta_categories_output( $output ) {
	return str_replace( 'class="tribe-events-pro-organizer__meta-categories ', 'class="tribe-events-pro-organizer__meta-categories col-12 ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_organizer_meta_categories_output', 'enlightenment_tribe_bootstrap_template_organizer_meta_categories_output' );

function enlightenment_tribe_bootstrap_template_components_events_bar_output( $output ) {
	$output = str_replace( 'class="tribe-events-header__events-bar ', 'class="tribe-events-header__events-bar col flex-shrink-1 col-lg-12 flex-lg-shrink-0 navbar navbar-expand-lg py-0 shadow-none ', $output );
	$output = str_replace( 'class="tribe-events-c-events-bar__search-button"', 'class="tribe-events-c-events-bar__search-button navbar-toggler"', $output );
	$output = str_replace( 'data-js="tribe-events-search-button"', 'data-js="tribe-events-search-button" data-bs-toggle="collapse" data-bs-target="#tribe-events-search-container"', $output );
	$output = str_replace( 'class="tribe-events-c-events-bar__search-button-icon tribe-common-svgicon"', 'class="tribe-events-c-events-bar__search-button-icon tribe-common-svgicon fas fa-search"', $output );
	$output = str_replace( 'class="tribe-events-c-events-bar__search-button-text tribe-common-a11y-visual-hide screen-reader-text"', 'class="tribe-events-c-events-bar__search-button-text tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-events-c-events-bar__search-filters-container"', 'class="tribe-events-c-events-bar__search-filters-container collapse navbar-collapse"', $output );
	$output = str_replace( 'class="tribe-events-c-events-bar__search-container"', 'class="tribe-events-c-events-bar__search-container collapse navbar-collapse"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_events_bar_output', 'enlightenment_tribe_bootstrap_template_components_events_bar_output' );

function enlightenment_tribe_bootstrap_template_components_icons_search_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--search' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-search" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_search_output', 'enlightenment_tribe_bootstrap_template_components_icons_search_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_events_bar_search_output( $output ) {
	$output = str_replace( 'class="tribe-events-c-events-bar__search"', 'class="tribe-events-c-events-bar__search w-100"', $output );
	$output = str_replace( 'class="tribe-events-c-search tribe-events-c-events-bar__search-form"', 'class="tribe-events-c-search tribe-events-c-events-bar__search-form input-group w-100"', $output );
	$output = str_replace( 'class="tribe-events-c-search__input-group"', 'class="tribe-events-c-search__input-group d-flex flex-grow-1"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_events_bar_search_output', 'enlightenment_tribe_bootstrap_template_components_events_bar_search_output' );

function enlightenment_tribe_bootstrap_template_components_events_bar_search_keyword_output( $output ) {
	$output = str_replace( 'class="tribe-common-form-control-text tribe-events-c-search__input-control tribe-events-c-search__input-control--keyword"', 'class="tribe-common-form-control-text tribe-events-c-search__input-control tribe-events-c-search__input-control--keyword input-group"', $output );
    $output = str_replace( 'class="tribe-common-form-control-text__label screen-reader-text"', 'class="tribe-common-form-control-text__label screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-common-form-control-text__input tribe-events-c-search__input ', 'class="tribe-common-form-control-text__input tribe-events-c-search__input form-control ', $output );
	$output = str_replace( 'class="tribe-common-form-control-text__input tribe-events-c-search__input"', 'class="tribe-common-form-control-text__input tribe-events-c-search__input form-control"', $output );

	$start  = strpos( $output, '<label class="tribe-common-form-control-text__label screen-reader-text visually-hidden" for="tribe-events-events-bar-keyword">' );
    $end    = strpos( $output, '</label>', $start ) + 8;
    $length = $end - $start;
    $label  = substr( $output, $start, $length );
    $output = substr_replace( $output, '', $start, $length );
	$offset = strpos( $output, '/>', $start );
	$output = substr_replace( $output, "\n" . "\t" . $label, $offset + 2, 0 );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_events_bar_search_keyword_output', 'enlightenment_tribe_bootstrap_template_components_events_bar_search_keyword_output' );

function enlightenment_tribe_bootstrap_template_location_form_field_output( $output ) {
	$output = str_replace( 'class="tribe-common-form-control-text tribe-events-c-search__input-control tribe-events-c-search__input-control--location"', 'class="tribe-common-form-control-text tribe-events-c-search__input-control tribe-events-c-search__input-control--location input-group"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_location_form_field_output', 'enlightenment_tribe_bootstrap_template_location_form_field_output' );

function enlightenment_tribe_bootstrap_template_components_icons_location_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--location' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-map-marker-alt" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_location_output', 'enlightenment_tribe_bootstrap_template_components_icons_location_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_events_bar_search_submit_output( $output ) {
	return str_replace( 'class="tribe-common-c-btn tribe-events-c-search__button"', 'class="tribe-common-c-btn tribe-events-c-search__button btn btn-light"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_components_events_bar_search_submit_output', 'enlightenment_tribe_bootstrap_template_components_events_bar_search_submit_output' );

function enlightenment_tribe_bootstrap_template_components_events_bar_views_output( $output ) {
	$output = str_replace( 'class="tribe-events-c-view-selector ', 'class="tribe-events-c-view-selector dropdown ', $output );
	$output = str_replace( 'class="tribe-events-c-view-selector"', 'class="tribe-events-c-view-selector dropdown"', $output );
	$output = str_replace( 'class="tribe-events-c-view-selector__button ', 'class="tribe-events-c-view-selector__button btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown ', $output );
	$output = str_replace( 'class="tribe-events-c-view-selector__button"', 'class="tribe-events-c-view-selector__button btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"', $output );
	$output = str_replace( 'class="tribe-events-c-view-selector__content"', 'class="tribe-events-c-view-selector__content dropdown-menu dropdown-menu-end"', $output );
	$output = str_replace( 'class="tribe-events-c-view-selector__list"', 'class="tribe-events-c-view-selector__list list-unstyled mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_events_bar_views_output', 'enlightenment_tribe_bootstrap_template_components_events_bar_views_output' );

function enlightenment_tribe_bootstrap_template_components_events_bar_views_list_item_output( $output ) {
	$output = str_replace( 'class="tribe-events-c-view-selector__list-item-link"', 'class="tribe-events-c-view-selector__list-item-link dropdown-item"', $output );

	$offset = strpos( $output, ' tribe-events-c-view-selector__list-item--active"' );
	if ( false !== $offset ) {
		$end_a    = strpos( $output, '</li>', $offset );
		$offset_a = strpos( $output, 'class="tribe-events-c-view-selector__list-item-link dropdown-item"', $offset );

		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, ' active', $offset_a + 65, 0 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_events_bar_views_list_item_output', 'enlightenment_tribe_bootstrap_template_components_events_bar_views_list_item_output' );

function enlightenment_tribe_bootstrap_template_components_icons_list_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--list' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = array_merge( $classes, array( 'fas', 'fa-list' ) );

	if ( in_array( 'tribe-events-c-view-selector__list-item-icon-svg', $classes ) ) {
		$classes[] = 'fa-fw';
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_list_output', 'enlightenment_tribe_bootstrap_template_components_icons_list_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_month_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--month' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = array_merge( $classes, array( 'far', 'fa-calendar-alt' ) );

	if ( in_array( 'tribe-events-c-view-selector__list-item-icon-svg', $classes ) ) {
		$classes[] = 'fa-fw';
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_month_output', 'enlightenment_tribe_bootstrap_template_components_icons_month_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_day_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--day' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = array_merge( $classes, array( 'fas', 'fa-calendar-day' ) );

	if ( in_array( 'tribe-events-c-view-selector__list-item-icon-svg', $classes ) ) {
		$classes[] = 'fa-fw';
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_day_output', 'enlightenment_tribe_bootstrap_template_components_icons_day_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_summary_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--summary', 'tribe-common-c-svgicon__svg-stroke' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = array_merge( $classes, array( 'fas', 'fa-stream' ) );

	if ( in_array( 'tribe-events-c-view-selector__list-item-icon-svg', $classes ) ) {
		$classes[] = 'fa-fw';
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_summary_output', 'enlightenment_tribe_bootstrap_template_components_icons_summary_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_photo_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--photo' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = array_merge( $classes, array( 'fas', 'fa-th' ) );

	if ( in_array( 'tribe-events-c-view-selector__list-item-icon-svg', $classes ) ) {
		$classes[] = 'fa-fw';
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_photo_output', 'enlightenment_tribe_bootstrap_template_components_icons_photo_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_week_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--week' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = array_merge( $classes, array( 'fas', 'fa-calendar-week' ) );

	if ( in_array( 'tribe-events-c-view-selector__list-item-icon-svg', $classes ) ) {
		$classes[] = 'fa-fw';
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_week_output', 'enlightenment_tribe_bootstrap_template_components_icons_week_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_map_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--map' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = array_merge( $classes, array( 'fas', 'fa-map-marked-alt' ) );

	if ( in_array( 'tribe-events-c-view-selector__list-item-icon-svg', $classes ) ) {
		$classes[] = 'fa-fw';
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_map_output', 'enlightenment_tribe_bootstrap_template_components_icons_map_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_top_bar_output( $output ) {
	return str_replace( 'class="tribe-events-c-top-bar tribe-events-header__top-bar"', 'class="tribe-events-c-top-bar tribe-events-header__top-bar col flex-grow-1 col-lg-12 flex-lg-grow-0 order-first order-lg-0 d-flex align-items-center"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_list_top_bar_output',    'enlightenment_tribe_bootstrap_template_top_bar_output' );
add_filter( 'enlightenment_tribe_filter_template_month_top_bar_output',   'enlightenment_tribe_bootstrap_template_top_bar_output' );
add_filter( 'enlightenment_tribe_filter_template_day_top_bar_output',     'enlightenment_tribe_bootstrap_template_top_bar_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_top_bar_output', 'enlightenment_tribe_bootstrap_template_top_bar_output' );
add_filter( 'enlightenment_tribe_filter_template_week_top_bar_output',    'enlightenment_tribe_bootstrap_template_top_bar_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_top_bar_output',   'enlightenment_tribe_bootstrap_template_top_bar_output' );
add_filter( 'enlightenment_tribe_filter_template_map_top_bar_output',     'enlightenment_tribe_bootstrap_template_top_bar_output' );

function enlightenment_tribe_bootstrap_template_top_bar_nav_output( $output ) {
	$output = str_replace( 'class="tribe-events-c-top-bar__nav tribe-common-a11y-hidden"', 'class="tribe-events-c-top-bar__nav tribe-common-a11y-hidden d-none d-lg-block me-3"', $output );
	$output = str_replace( 'class="tribe-events-c-top-bar__nav-list"', 'class="tribe-events-c-top-bar__nav-list list-unstyled d-flex mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_list_top_bar_nav_output',    'enlightenment_tribe_bootstrap_template_top_bar_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_month_top_bar_nav_output',   'enlightenment_tribe_bootstrap_template_top_bar_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_day_top_bar_nav_output',     'enlightenment_tribe_bootstrap_template_top_bar_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_top_bar_nav_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_week_top_bar_nav_output',    'enlightenment_tribe_bootstrap_template_top_bar_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_top_bar_nav_output',   'enlightenment_tribe_bootstrap_template_top_bar_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_map_top_bar_nav_output',     'enlightenment_tribe_bootstrap_template_top_bar_nav_output' );

function enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output( $output ) {
	return str_replace( 'class="tribe-common-c-btn-icon tribe-common-c-btn-icon--caret-left tribe-events-c-top-bar__nav-link tribe-events-c-top-bar__nav-link--prev"', 'class="tribe-common-c-btn-icon tribe-common-c-btn-icon--caret-left tribe-events-c-top-bar__nav-link tribe-events-c-top-bar__nav-link--prev btn btn-link p-0 border-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_list_top_bar_nav_prev_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_list_top_bar_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_month_top_bar_nav_prev_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_month_top_bar_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_day_top_bar_nav_prev_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_day_top_bar_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_top_bar_nav_prev_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_top_bar_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_week_top_bar_nav_prev_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_week_top_bar_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_top_bar_nav_prev_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_top_bar_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_map_top_bar_nav_prev_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_map_top_bar_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_prev_output' );

function enlightenment_tribe_bootstrap_template_top_bar_nav_next_output( $output ) {
	return str_replace( 'class="tribe-common-c-btn-icon tribe-common-c-btn-icon--caret-right tribe-events-c-top-bar__nav-link tribe-events-c-top-bar__nav-link--next"', 'class="tribe-common-c-btn-icon tribe-common-c-btn-icon--caret-right tribe-events-c-top-bar__nav-link tribe-events-c-top-bar__nav-link--next btn btn-link p-0 border-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_list_top_bar_nav_next_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_list_top_bar_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_month_top_bar_nav_next_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_month_top_bar_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_day_top_bar_nav_next_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_day_top_bar_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_top_bar_nav_next_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_top_bar_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_week_top_bar_nav_next_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_week_top_bar_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_top_bar_nav_next_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_top_bar_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_map_top_bar_nav_next_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_map_top_bar_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_top_bar_nav_next_output' );

function enlightenment_tribe_bootstrap_template_components_icons_caret_left_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--caret-left' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-chevron-left" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_caret_left_output', 'enlightenment_tribe_bootstrap_template_components_icons_caret_left_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_caret_right_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--caret-right' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-chevron-right" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_caret_right_output', 'enlightenment_tribe_bootstrap_template_components_icons_caret_right_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_top_bar_today_output( $output ) {
	$output = str_replace( 'class="tribe-common-c-btn-border tribe-events-c-top-bar__today-button tribe-common-a11y-hidden"', 'class="tribe-common-c-btn-border tribe-events-c-top-bar__today-button btn btn-secondary d-none d-lg-block me-3"', $output );
	$output = str_replace( 'class="tribe-common-c-btn-border-small tribe-events-c-top-bar__today-button tribe-common-a11y-hidden"', 'class="tribe-common-c-btn-border-small tribe-events-c-top-bar__today-button btn btn-secondary d-none d-lg-block me-3"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_top_bar_today_output', 'enlightenment_tribe_bootstrap_template_components_top_bar_today_output' );

function enlightenment_tribe_bootstrap_template_top_bar_datepicker_output( $output ) {
	$output = str_replace( 'class="tribe-common-c-btn__clear tribe-common-h3 tribe-common-h--alt tribe-events-c-top-bar__datepicker-button"', 'class="tribe-common-c-btn__clear tribe-common-h3 tribe-common-h--alt tribe-events-c-top-bar__datepicker-button p-0 border-0"', $output );
	$output = str_replace( 'class="tribe-common-h3 tribe-common-h--alt tribe-events-c-top-bar__datepicker-button"', 'class="tribe-common-h3 tribe-common-h--alt tribe-events-c-top-bar__datepicker-button p-0 border-0"', $output );
	$output = str_replace( 'class="tribe-events-c-top-bar__datepicker-label tribe-common-a11y-visual-hide screen-reader-text"', 'class="tribe-events-c-top-bar__datepicker-label tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-events-c-top-bar__datepicker-input tribe-common-a11y-visual-hide"', 'class="tribe-events-c-top-bar__datepicker-input tribe-common-a11y-visual-hide invisible"', $output );
    $output = str_replace( 'class="tribe-events-c-top-bar__datepicker-desktop tribe-common-a11y-hidden screen-reader-text"', 'class="tribe-events-c-top-bar__datepicker-desktop tribe-common-a11y-hidden screen-reader-text visually-hidden"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_list_top_bar_datepicker_output', 'enlightenment_tribe_bootstrap_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_month_top_bar_datepicker_output', 'enlightenment_tribe_bootstrap_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_day_top_bar_datepicker_output', 'enlightenment_tribe_bootstrap_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_top_bar_datepicker_output', 'enlightenment_tribe_bootstrap_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_week_top_bar_datepicker_output', 'enlightenment_tribe_bootstrap_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_top_bar_datepicker_output', 'enlightenment_tribe_bootstrap_template_top_bar_datepicker_output' );
add_filter( 'enlightenment_tribe_filter_template_map_top_bar_datepicker_output', 'enlightenment_tribe_bootstrap_template_top_bar_datepicker_output' );

add_filter( 'enlightenment_tribe_filter_template_components_icons_caret_down_output', '__return_false' );
add_filter( 'enlightenment_tribe_filter_template_v2_components_icons_caret_down_output', '__return_false' );

// [tribe_events] shortcode only
function enlightenment_tribe_bootstrap_template_month_output( $output, $template_name, $file, $template ) {
	$context   = $template->get_view()->get_context();
	$widget    = $context->get( 'is-widget', false );
	$shortcode = $context->get( 'shortcode', false );

	if ( ! $widget && $shortcode ) {
		$output = str_replace( 'class="tribe-events-header__messages tribe-events-c-messages tribe-common-b2 tribe-events-header__messages--mobile ', 'class="tribe-events-header__messages tribe-events-c-messages tribe-common-b2 tribe-events-header__messages--mobile d-lg-none ', $output );
		$output = str_replace( 'class="tribe-events-header__messages tribe-events-c-messages tribe-common-b2 tribe-events-header__messages--mobile"', 'class="tribe-events-header__messages tribe-events-c-messages tribe-common-b2 tribe-events-header__messages--mobile d-lg-none"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_month_output', 'enlightenment_tribe_bootstrap_template_month_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_month_calendar_header_output( $output, $template_name, $file, $template ) {
	$context   = $template->get_view()->get_context();
	$widget    = $context->get( 'is-widget', false );
	$shortcode = $context->get( 'shortcode', false );

	$output = str_replace( 'class="tribe-events-calendar-month__header-row"', 'class="tribe-events-calendar-month__header-row row g-0 flex-nowrap"', $output );
	$output = str_replace( '<tr>', '<tr class="row g-0 flex-nowrap">', $output );
	$output = str_replace( 'class="tribe-events-calendar-month__header-column"', 'class="tribe-events-calendar-month__header-column col px-1"', $output );

	if ( $widget && $shortcode  ) {
		$shortcode_atts = get_transient( sprintf( 'tribe_events_shortcode_tribe_events_params_%s', $shortcode ) );

		if ( isset( $shortcode_atts['layout'] ) && 'vertical' == $shortcode_atts['layout'] ) {
			$output = str_replace( 'class="tribe-events-calendar-month__header-column-title-desktop ', 'class="tribe-events-calendar-month__header-column-title-desktop d-none ', $output );
		} else {
			$output = str_replace( 'class="tribe-events-calendar-month__header-column-title-mobile"', 'class="tribe-events-calendar-month__header-column-title-mobile d-lg-none"', $output );
			$output = str_replace( 'class="tribe-events-calendar-month__header-column-title-desktop ', 'class="tribe-events-calendar-month__header-column-title-desktop d-none d-lg-inline ', $output );
		}
	} else {
		$output = str_replace( 'class="tribe-events-calendar-month__header-column-title-mobile"', 'class="tribe-events-calendar-month__header-column-title-mobile d-lg-none"', $output );
		$output = str_replace( 'class="tribe-events-calendar-month__header-column-title-desktop ', 'class="tribe-events-calendar-month__header-column-title-desktop d-none d-lg-inline ', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_month_calendar_header_output', 'enlightenment_tribe_bootstrap_template_month_calendar_header_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_month_calendar_body_output( $output ) {
	return str_replace( 'class="tribe-events-calendar-month__week"', 'class="tribe-events-calendar-month__week row g-0 flex-nowrap"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_month_calendar_body_output', 'enlightenment_tribe_bootstrap_template_month_calendar_body_output' );

function enlightenment_tribe_bootstrap_template_month_calendar_body_day_output( $output, $template_name, $file, $template ) {
	$context   = $template->get_view()->get_context();
	$widget    = $context->get( 'is-widget', false );
	$shortcode = $context->get( 'shortcode', false );

	if ( $widget && $shortcode  ) {
		$shortcode_atts = get_transient( sprintf( 'tribe_events_shortcode_tribe_events_params_%s', $shortcode ) );

		if ( isset( $shortcode_atts['layout'] ) && 'vertical' == $shortcode_atts['layout'] ) {
			$output = str_replace( 'class="tribe-events-calendar-month__day ', 'class="tribe-events-calendar-month__day col ', $output );
			$output = str_replace( 'class="tribe-events-calendar-month__day"', 'class="tribe-events-calendar-month__day col"', $output );

			$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile ', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile w-100 p-0 border-0 bg-transparent ', $output );
			$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile"', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile w-100 p-0 border-0 bg-transparent"', $output );

			$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop ', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop d-none ', $output );
			$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop"', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop d-none"', $output );
		} else {
			$output = str_replace( 'class="tribe-events-calendar-month__day ', 'class="tribe-events-calendar-month__day col p-lg-3 ', $output );
			$output = str_replace( 'class="tribe-events-calendar-month__day"', 'class="tribe-events-calendar-month__day col p-lg-3"', $output );

			$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile ', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile d-lg-none w-100 border-0 bg-transparent ', $output );
			$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile"', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile d-lg-none w-100 border-0 bg-transparent"', $output );

			$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop ', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop d-none d-lg-block ', $output );
			$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop"', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop d-none d-lg-block"', $output );
		}
	} else {
		$output = str_replace( 'class="tribe-events-calendar-month__day ', 'class="tribe-events-calendar-month__day col p-lg-3 ', $output );
		$output = str_replace( 'class="tribe-events-calendar-month__day"', 'class="tribe-events-calendar-month__day col p-lg-3"', $output );

		$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile ', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile d-lg-none w-100 border-0 bg-transparent ', $output );
		$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile"', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--mobile d-lg-none w-100 border-0 bg-transparent"', $output );

		$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop ', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop d-none d-lg-block ', $output );
		$output = str_replace( 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop"', 'class="tribe-events-calendar-month__day-cell tribe-events-calendar-month__day-cell--desktop d-none d-lg-block"', $output );
	}

	$output = str_replace( 'class="tribe-events-calendar-month__calendar-event-tooltip-template tribe-common-a11y-hidden"', 'class="tribe-events-calendar-month__calendar-event-tooltip-template tribe-common-a11y-hidden d-none"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_month_calendar_body_day_output', 'enlightenment_tribe_bootstrap_template_month_calendar_body_day_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_month_calendar_event_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-calendar-month__calendar-event-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-calendar-month__calendar-event-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_month_calendar_event_recurring_output', 'enlightenment_tribe_bootstrap_template_month_calendar_event_recurring_output' );

function enlightenment_tribe_bootstrap_template_month_calendar_event_multiday_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-calendar-month__calendar-event-multiday-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-calendar-month__calendar-event-multiday-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_month_calendar_event_multiday_recurring_output', 'enlightenment_tribe_bootstrap_template_month_calendar_event_multiday_recurring_output' );

function enlightenment_tribe_bootstrap_template_month_mobile_event_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-calendar-month-mobile-events__mobile-event-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-calendar-month-mobile-events__mobile-event-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_month_mobile_event_recurring_output', 'enlightenment_tribe_bootstrap_template_month_mobile_event_recurring_output' );

function enlightenment_tribe_bootstrap_template_month_calendar_event_tooltip_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-calendar-month__calendar-event-tooltip-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-calendar-month__calendar-event-tooltip-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_month_calendar_event_tooltip_recurring_output', 'enlightenment_tribe_bootstrap_template_month_calendar_event_tooltip_recurring_output' );

function enlightenment_tribe_bootstrap_template_month_mobile_events_output( $output, $template_name, $file, $template ) {
	$context   = $template->get_view()->get_context();
	$widget    = $context->get( 'is-widget', false );
	$shortcode = $context->get( 'shortcode', false );

	if ( ! $widget ) {
		$output = str_replace( 'class="tribe-events-calendar-month-mobile-events"', 'class="tribe-events-calendar-month-mobile-events d-lg-none"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_month_mobile_events_output', 'enlightenment_tribe_bootstrap_template_month_mobile_events_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_view_more_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-events-widget-events-month__view-more"', 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-events-widget-events-month__view-more mt-3"', $output );
	$output = str_replace( 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-events-widget-events-week__view-more"', 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-events-widget-events-week__view-more mt-3"', $output );

	$output = str_replace( 'class="tribe-common-anchor-thin tribe-events-widget-events-month__view-more-link"', 'class="tribe-common-anchor-thin tribe-events-widget-events-month__view-more-link btn btn-secondary"', $output );
	$output = str_replace( 'class="tribe-common-anchor-thin tribe-events-widget-events-week__view-more-link"', 'class="tribe-common-anchor-thin tribe-events-widget-events-week__view-more-link btn btn-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_view_more_output', 'enlightenment_tribe_bootstrap_template_components_view_more_output' );

function enlightenment_tribe_bootstrap_template_summary_date_group_output( $output ) {
	$output = str_replace( 'class="tribe-common-g-row tribe-events-pro-summary__event-row ', 'class="tribe-common-g-row tribe-events-pro-summary__event-row row row ', $output );
	$output = str_replace( 'class="tribe-common-g-row tribe-events-pro-summary__event-row"', 'class="tribe-common-g-row tribe-events-pro-summary__event-row row"', $output );
	$output = str_replace( 'class="tribe-common-g-col tribe-events-pro-summary__event-wrapper"', 'class="tribe-common-g-col tribe-events-pro-summary__event-wrapper col"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_summary_date_group_output', 'enlightenment_tribe_bootstrap_template_summary_date_group_output' );

function enlightenment_tribe_bootstrap_template_summary_date_group_date_tag_output( $output ) {
	return str_replace( 'class="tribe-common-g-col tribe-events-pro-summary__event-date-tag"', 'class="tribe-common-g-col tribe-events-pro-summary__event-date-tag col flex-grow-0 flex-shrink-1"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_summary_date_group_date_tag_output', 'enlightenment_tribe_bootstrap_template_summary_date_group_date_tag_output' );

function enlightenment_tribe_bootstrap_template_summary_date_group_event_output( $output ) {
	return str_replace( 'class="tribe-events-pro-summary__event-header"', 'class="tribe-events-pro-summary__event-header row"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_summary_date_group_event_output', 'enlightenment_tribe_bootstrap_template_summary_date_group_event_output' );

function enlightenment_tribe_bootstrap_template_summary_date_group_event_date_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-pro-summary__event-datetime-recurring-link"', 'class="tribe-events-pro-summary__event-datetime-recurring-link ms-1"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_summary_date_group_event_date_recurring_output', 'enlightenment_tribe_bootstrap_template_summary_date_group_event_date_recurring_output' );

function enlightenment_tribe_bootstrap_template_summary_date_group_event_date_output( $output ) {
	return str_replace( 'class="tribe-common-b3 tribe-events-pro-summary__event-datetime-wrapper"', 'class="tribe-common-b3 tribe-events-pro-summary__event-datetime-wrapper col-lg-3 mb-2 mb-lg-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_summary_date_group_event_date_output', 'enlightenment_tribe_bootstrap_template_summary_date_group_event_date_output' );

function enlightenment_tribe_bootstrap_template_summary_date_group_event_title_output( $output ) {
	return str_replace( 'class="tribe-common-h8 tribe-common-h7--min-medium tribe-events-pro-summary__event-title"', 'class="tribe-common-h8 tribe-common-h7--min-medium tribe-events-pro-summary__event-title col-lg-9 d-lg-flex align-items-lg-start h5 mb-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_summary_date_group_event_title_output', 'enlightenment_tribe_bootstrap_template_summary_date_group_event_title_output' );

function enlightenment_tribe_bootstrap_template_summary_date_group_event_title_featured_output( $output ) {
	if ( empty ( $output ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-pro-summary__event-title-featured-text tribe-common-a11y-visual-hide screen-reader-text"', 'class="tribe-events-pro-summary__event-title-featured-text tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );
	$output = sprintf( '<div class="tribe-events-pro-summary__event-title-before d-inline-block me-1 me-lg-2">%s</div>', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_summary_date_group_event_title_featured_output', 'enlightenment_tribe_bootstrap_template_summary_date_group_event_title_featured_output' );

function enlightenment_tribe_bootstrap_template_summary_date_group_event_cost_output( $output, $template_name, $file, $template ) {
	$output = str_replace( 'class="tribe-events-c-small-cta tribe-common-b3 tribe-common-b3--bold tribe-events-pro-summary__event-cost"', 'class="tribe-events-c-small-cta tribe-common-b3 tribe-common-b3--bold tribe-events-pro-summary__event-cost mt-2 mt-lg-0 ms-auto"', $output );
	$output = str_replace( 'class="tribe-events-c-small-cta__text"', 'class="tribe-events-c-small-cta__text mt-2 mt-lg-0 ms-3"', $output );

	$values      = $template->get_values();
	$et_loaded   = function_exists( 'tribe_get_ticket_label_plural' );
	$is_sold_out = $et_loaded && $values['event']->tickets->sold_out();

	if (
		$et_loaded
		&&
		$values['event']->summary_view->has_tickets
		&&
		! $is_sold_out
	) {
		$output = str_replace( 'class="tribe-events-c-small-cta tribe-common-b3 tribe-common-b3--bold tribe-events-pro-summary__event-cost mt-2 mt-lg-0 ms-auto"', 'class="tribe-events-c-small-cta tribe-common-b3 tribe-common-b3--bold tribe-events-pro-summary__event-cost d-flex align-items-center mt-2 mt-lg-0 ms-auto"', $output );
		$output = str_replace( 'class="tribe-events-c-small-cta__text mt-2 mt-lg-0 ms-3"', 'class="tribe-events-c-small-cta__text btn btn-secondary btn-sm text-nowrap order-lg-1 me-2 me-lg-0 ms-lg-2"', $output );
		$output = str_replace( 'class="tribe-events-c-small-cta__price"', 'class="tribe-events-c-small-cta__price ms-lg-3"', $output );
	} elseif (
		$et_loaded
		&&
		$values['event']->summary_view->has_rsvp
		&&
		! $is_sold_out
	) {
		$output = str_replace( 'class="tribe-events-c-small-cta__text mt-2 mt-lg-0 ms-3"', 'class="tribe-events-c-small-cta__text btn btn-secondary btn-sm text-nowrap ms-lg-3"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_summary_date_group_event_cost_output', 'enlightenment_tribe_bootstrap_template_summary_date_group_event_cost_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_week_day_selector_output( $output, $template_name, $file, $template ) {
	$context   = $template->get_view()->get_context();
	$widget    = $context->get( 'is-widget', false );
	$shortcode = $context->get( 'shortcode', false );

	if ( $widget || $shortcode ) {
		// [tribe_events] shortcode
		if ( ! $widget && $shortcode ) {
			$output = str_replace( 'class="tribe-events-pro-week-day-selector ', 'class="tribe-events-pro-week-day-selector d-lg-none ', $output );
			$output = str_replace( 'class="tribe-events-pro-week-day-selector"', 'class="tribe-events-pro-week-day-selector d-lg-none"', $output );
		// [tribe_this_week] shortcode
		} else {
			$shortcode_atts = get_transient( sprintf( 'tribe_events_shortcode_tribe_events_params_%s', $shortcode ) );

			if ( isset( $shortcode_atts['layout'] ) && 'horizontal' == $shortcode_atts['layout'] ) {
				$output = str_replace( 'class="tribe-events-pro-week-day-selector ', 'class="tribe-events-pro-week-day-selector d-lg-none ', $output );
				$output = str_replace( 'class="tribe-events-pro-week-day-selector"', 'class="tribe-events-pro-week-day-selector d-lg-none"', $output );
			}
		}
	} else {
		$output = str_replace( 'class="tribe-events-pro-week-day-selector ', 'class="tribe-events-pro-week-day-selector d-lg-none ', $output );
		$output = str_replace( 'class="tribe-events-pro-week-day-selector"', 'class="tribe-events-pro-week-day-selector d-lg-none"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_week_day_selector_output', 'enlightenment_tribe_bootstrap_template_week_day_selector_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_week_mobile_events_output( $output, $template_name, $file, $template ) {
	$context   = $template->get_view()->get_context();
	$widget    = $context->get( 'is-widget', false );
	$shortcode = $context->get( 'shortcode', false );

	if ( $widget || $shortcode  ) {
		// [tribe_events] shortcode
		if ( ! $widget && $shortcode ) {
			$output = str_replace( 'class="tribe-events-pro-week-mobile-events"', 'class="tribe-events-pro-week-mobile-events d-lg-none"', $output );
		// [tribe_this_week] shortcode
		} else {
			$shortcode_atts = get_transient( sprintf( 'tribe_events_shortcode_tribe_events_params_%s', $shortcode ) );

			if ( isset( $shortcode_atts['layout'] ) && 'horizontal' == $shortcode_atts['layout'] ) {
				$output = str_replace( 'class="tribe-events-pro-week-mobile-events"', 'class="tribe-events-pro-week-mobile-events d-lg-none"', $output );
			}
		}
	} else {
		$output = str_replace( 'class="tribe-events-pro-week-mobile-events"', 'class="tribe-events-pro-week-mobile-events d-lg-none"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_week_mobile_events_output', 'enlightenment_tribe_bootstrap_template_week_mobile_events_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_week_day_selector_days_output( $output ) {
	return str_replace( 'class="tribe-events-pro-week-day-selector__days-list"', 'class="tribe-events-pro-week-day-selector__days-list list-unstyled row g-0 flex-nowrap mb-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_week_day_selector_days_output', 'enlightenment_tribe_bootstrap_template_week_day_selector_days_output' );

function enlightenment_tribe_bootstrap_template_week_day_selector_days_day_output( $output ) {
	$output = str_replace( 'class="tribe-events-pro-week-day-selector__days-list-item"', 'class="tribe-events-pro-week-day-selector__days-list-item col"', $output );
	$output = str_replace( 'class="tribe-events-pro-week-day-selector__day ', 'class="tribe-events-pro-week-day-selector__day w-100 border-0 bg-transparent ', $output );
	$output = str_replace( 'class="tribe-events-pro-week-day-selector__day"', 'class="tribe-events-pro-week-day-selector__day w-100 border-0 bg-transparent"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_week_day_selector_days_day_output', 'enlightenment_tribe_bootstrap_template_week_day_selector_days_day_output' );

function enlightenment_tribe_bootstrap_template_week_day_selector_nav_output( $output ) {
	return str_replace( 'class="tribe-events-pro-week-day-selector__nav-list"', 'class="tribe-events-pro-week-day-selector__nav-list list-unstyled mb-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_week_day_selector_nav_output', 'enlightenment_tribe_bootstrap_template_week_day_selector_nav_output' );

function enlightenment_tribe_bootstrap_events_week_grid_args( $args ) {
	$args['container_class'] .= ' d-none d-lg-block';

	return $args;
}
add_filter( 'enlightenment_events_week_grid_args', 'enlightenment_tribe_bootstrap_events_week_grid_args' );

function enlightenment_tribe_bootstrap_template_week_output( $output, $template_name, $file, $template ) {
	$context   = $template->get_view()->get_context();
	$widget    = $context->get( 'is-widget', false );
	$shortcode = $context->get( 'shortcode', false );

	if ( $widget || $shortcode ) {
		// [tribe_events] shortcode
		if ( ! $widget && $shortcode ) {
			$output = str_replace( 'class="tribe-events-pro-week-grid ', 'class="tribe-events-pro-week-grid d-none d-lg-block ', $output );
		// [tribe_this_week] shortcode
		} else {
			$shortcode_atts = get_transient( sprintf( 'tribe_events_shortcode_tribe_events_params_%s', $shortcode ) );

			if ( isset( $shortcode_atts['layout'] ) && 'vertical' == $shortcode_atts['layout'] ) {
				$output = str_replace( 'class="tribe-events-pro-week-grid ', 'class="tribe-events-pro-week-grid d-lg-none ', $output );
			}
		}
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_week_output', 'enlightenment_tribe_bootstrap_template_week_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_week_grid_header_output( $output ) {
	$output = str_replace( 'class="tribe-events-pro-week-grid__header-row"', 'class="tribe-events-pro-week-grid__header-row row g-0 flex-nowrap"', $output );
	$output = str_replace( ' tribe-events-pro-week-grid__header-column--empty ', ' tribe-events-pro-week-grid__header-column--empty col flex-grow-0 flex-shrink-1 ', $output );
	$output = str_replace( ' tribe-events-pro-week-grid__header-column--empty"', ' tribe-events-pro-week-grid__header-column--empty col flex-grow-0 flex-shrink-1"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_header_output', 'enlightenment_tribe_bootstrap_template_week_grid_header_output' );

function enlightenment_tribe_bootstrap_template_week_grid_header_header_column_output( $output ) {
	$output = str_replace( 'class="tribe-events-pro-week-grid__header-column ', 'class="tribe-events-pro-week-grid__header-column col ', $output );
	$output = str_replace( 'class="tribe-events-pro-week-grid__header-column"', 'class="tribe-events-pro-week-grid__header-column col"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_header_header_column_output', 'enlightenment_tribe_bootstrap_template_week_grid_header_header_column_output' );

function enlightenment_tribe_bootstrap_template_week_grid_body_output( $output ) {
	$output = str_replace( 'class="tribe-events-pro-week-grid__multiday-events-row"', 'class="tribe-events-pro-week-grid__multiday-events-row row g-0 flex-nowrap"', $output );
	$output = str_replace( 'class="tribe-events-pro-week-grid__events-row"', 'class="tribe-events-pro-week-grid__events-row row g-0 flex-nowrap"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_body_output', 'enlightenment_tribe_bootstrap_template_week_grid_body_output' );

function enlightenment_tribe_bootstrap_template_week_grid_body_multiday_events_row_header_output( $output ) {
	return str_replace( 'class="tribe-events-pro-week-grid__multiday-events-row-header"', 'class="tribe-events-pro-week-grid__multiday-events-row-header col flex-grow-0 flex-shrink-1"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_body_multiday_events_row_header_output', 'enlightenment_tribe_bootstrap_template_week_grid_body_multiday_events_row_header_output' );

function enlightenment_tribe_bootstrap_template_week_grid_body_multiday_events_day_output( $output ) {
	return str_replace( 'class="tribe-events-pro-week-grid__multiday-events-day"', 'class="tribe-events-pro-week-grid__multiday-events-day col"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_body_multiday_events_day_output', 'enlightenment_tribe_bootstrap_template_week_grid_body_multiday_events_day_output' );

function enlightenment_tribe_bootstrap_template_week_grid_body_multiday_events_day_multiday_event_bar_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-pro-week-grid__multiday-event-bar-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-pro-week-grid__multiday-event-bar-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_body_multiday_events_day_multiday_event_bar_recurring_output', 'enlightenment_tribe_bootstrap_template_week_grid_body_multiday_events_day_multiday_event_bar_recurring_output' );

function enlightenment_tribe_bootstrap_template_week_grid_body_multiday_events_day_multiday_event_hidden_link_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-pro-week-grid__multiday-event-hidden-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-pro-week-grid__multiday-event-hidden-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_body_multiday_events_day_multiday_event_hidden_link_recurring_output', 'enlightenment_tribe_bootstrap_template_week_grid_body_multiday_events_day_multiday_event_hidden_link_recurring_output' );

function enlightenment_tribe_bootstrap_template_week_grid_body_events_day_event_tooltip_date_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-pro-week-grid__event-tooltip-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-pro-week-grid__event-tooltip-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_body_events_day_event_tooltip_date_recurring_output', 'enlightenment_tribe_bootstrap_template_week_grid_body_events_day_event_tooltip_date_recurring_output' );

function enlightenment_tribe_bootstrap_template_week_grid_body_events_day_event_date_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-pro-week-grid__event-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-pro-week-grid__event-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_body_events_day_event_date_recurring_output', 'enlightenment_tribe_bootstrap_template_week_grid_body_events_day_event_date_recurring_output' );

function enlightenment_tribe_bootstrap_template_week_mobile_events_day_event_date_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-pro-week-mobile-events__event-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-pro-week-mobile-events__event-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_week_mobile_events_day_event_date_recurring_output', 'enlightenment_tribe_bootstrap_template_week_mobile_events_day_event_date_recurring_output' );

function enlightenment_tribe_bootstrap_template_day_event_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-calendar-day__event-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-calendar-day__event-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_day_event_recurring_output', 'enlightenment_tribe_bootstrap_template_day_event_recurring_output' );

function enlightenment_tribe_bootstrap_template_week_grid_body_events_row_header_output( $output ) {
	$output = str_replace( 'class="tribe-events-pro-week-grid__events-row-header"', 'class="tribe-events-pro-week-grid__events-row-header col flex-grow-0 flex-shrink-1 d-flex flex-column"', $output );
	$output = str_replace( 'class="tribe-events-pro-week-grid__events-time-tag ', 'class="tribe-events-pro-week-grid__events-time-tag text-end text-nowrap ', $output );
	$output = str_replace( 'class="tribe-events-pro-week-grid__events-time-tag"', 'class="tribe-events-pro-week-grid__events-time-tag text-end text-nowrap"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_body_events_row_header_output', 'enlightenment_tribe_bootstrap_template_week_grid_body_events_row_header_output' );

function enlightenment_tribe_bootstrap_template_week_grid_body_events_day_output( $output ) {
	return str_replace( 'class="tribe-events-pro-week-grid__events-day"', 'class="tribe-events-pro-week-grid__events-day col"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_body_events_day_output', 'enlightenment_tribe_bootstrap_template_week_grid_body_events_day_output' );

function enlightenment_tribe_bootstrap_template_week_grid_body_events_day_event_tooltip_output( $output ) {
	return str_replace( 'class="tribe-events-pro-week-grid__event-tooltip-template tribe-common-a11y-hidden"', 'class="tribe-events-pro-week-grid__event-tooltip-template tribe-common-a11y-hidden d-none"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_week_grid_body_events_day_event_tooltip_output', 'enlightenment_tribe_bootstrap_template_week_grid_body_events_day_event_tooltip_output' );

// [tribe_events] shortcode only
function enlightenment_tribe_bootstrap_template_photo_output( $output ) {
	return str_replace( 'class="tribe-common-g-row tribe-common-g-row--gutters"', 'class="tribe-common-g-row tribe-common-g-row--gutters row"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_photo_output', 'enlightenment_tribe_bootstrap_template_photo_output' );

// [tribe_events] shortcode only
function enlightenment_tribe_bootstrap_template_photo_event_output( $output ) {
	$output = str_replace( 'class="tribe-events-pro-photo__event-details-wrapper"', 'class="tribe-events-pro-photo__event-details-wrapper row"', $output );
	$output = str_replace( 'class="tribe-events-pro-photo__event-details"', 'class="tribe-events-pro-photo__event-details col"', $output );
	$output = sprintf( '<div class="col-12 col-md-6 col-lg-4">%s</div>', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_photo_event_output', 'enlightenment_tribe_bootstrap_template_photo_event_output' );

function enlightenment_tribe_bootstrap_template_photo_event_date_time_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-pro-photo__event-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-pro-photo__event-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_photo_event_date_time_recurring_output', 'enlightenment_tribe_bootstrap_template_photo_event_date_time_recurring_output' );

function enlightenment_tribe_bootstrap_events_map_args( $args ) {
	$args['container_class'] .= ' row g-0';

	return $args;
}
add_filter( 'enlightenment_events_map_args', 'enlightenment_tribe_bootstrap_events_map_args' );

// [tribe_events] shortcode only
function enlightenment_tribe_bootstrap_template_map_output( $output ) {
	$output = str_replace( 'class="tribe-events-pro-map tribe-common-g-row"', 'class="tribe-events-pro-map tribe-common-g-row row g-0"', $output );
	$output = str_replace( 'class="tribe-common-g-row tribe-events-pro-map"', 'class="tribe-common-g-row tribe-events-pro-map row g-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_map_output', 'enlightenment_tribe_bootstrap_template_map_output' );

function enlightenment_tribe_bootstrap_template_map_map_output( $output ) {
	return str_replace( 'class="tribe-events-pro-map__map tribe-common-g-col"', 'class="tribe-events-pro-map__map tribe-common-g-col col-12 col-lg-8"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_map_map_output', 'enlightenment_tribe_bootstrap_template_map_map_output' );

function enlightenment_tribe_bootstrap_template_map_event_cards_output( $output ) {
	return str_replace( 'class="tribe-events-pro-map__event-column tribe-common-g-col"', 'class="tribe-events-pro-map__event-column tribe-common-g-col col-12 col-lg-4"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_output', 'enlightenment_tribe_bootstrap_template_map_event_cards_output' );

function enlightenment_tribe_bootstrap_template_map_map_google_maps_premium_output( $output ) {
	return str_replace( 'class="tribe-events-pro-map__google-maps-premium"', 'class="tribe-events-pro-map__google-maps-premium w-100 h-100"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_map_map_google_maps_premium_output', 'enlightenment_tribe_bootstrap_template_map_map_google_maps_premium_output' );

function enlightenment_tribe_bootstrap_template_map_map_google_maps_default_output( $output ) {
	return str_replace( 'class="tribe-events-pro-map__google-maps-default"', 'class="tribe-events-pro-map__google-maps-default w-100 h-100 border-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_map_map_google_maps_default_output', 'enlightenment_tribe_bootstrap_template_map_map_google_maps_default_output' );

function enlightenment_tribe_bootstrap_template_map_map_no_venue_modal_output( $output ) {
	return str_replace( 'class="tribe-events-pro-map__no-venue-modal-link ', 'class="tribe-events-pro-map__no-venue-modal-link btn btn-secondary ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_map_map_no_venue_modal_output', 'enlightenment_tribe_bootstrap_template_map_map_no_venue_modal_output' );

function enlightenment_tribe_bootstrap_template_map_event_cards_event_card_output( $output ) {
	$output = str_replace( 'class="tribe-events-pro-map__event-card-button"', 'class="tribe-events-pro-map__event-card-button w-100 border-0 bg-transparent"', $output );
	$output = str_replace( 'class="tribe-common-g-row tribe-events-pro-map__event-row"', 'class="tribe-common-g-row tribe-events-pro-map__event-row row flex-nowrap"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_event_card_output', 'enlightenment_tribe_bootstrap_template_map_event_cards_event_card_output' );

function enlightenment_tribe_bootstrap_template_map_event_cards_event_card_date_tag_output( $output ) {
	return str_replace( 'class="tribe-events-pro-map__event-date-tag tribe-common-g-col"', 'class="tribe-events-pro-map__event-date-tag tribe-common-g-col col flex-grow-0 flex-shrink-1"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_event_card_date_tag_output', 'enlightenment_tribe_bootstrap_template_map_event_cards_event_card_date_tag_output' );

function enlightenment_tribe_bootstrap_template_map_event_cards_event_card_event_date_time_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-pro-map__event-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-pro-map__event-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_event_card_event_date_time_recurring_output', 'enlightenment_tribe_bootstrap_template_map_event_cards_event_card_event_date_time_recurring_output' );

function enlightenment_tribe_bootstrap_template_map_event_cards_event_card_tooltip_date_time_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-pro-map__event-tooltip-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-pro-map__event-tooltip-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_event_card_tooltip_date_time_recurring_output', 'enlightenment_tribe_bootstrap_template_map_event_cards_event_card_tooltip_date_time_recurring_output' );

function enlightenment_tribe_bootstrap_template_map_event_cards_event_card_event_output( $output ) {
	return str_replace( 'class="tribe-events-pro-map__event-wrapper tribe-common-g-col"', 'class="tribe-events-pro-map__event-wrapper tribe-common-g-col col"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_event_card_event_output', 'enlightenment_tribe_bootstrap_template_map_event_cards_event_card_event_output' );

function enlightenment_tribe_bootstrap_template_map_event_cards_event_card_event_date_time_featured_output( $output ) {
	return str_replace( 'class="tribe-events-pro-map__event-datetime-featured-text tribe-common-a11y-visual-hide screen-reader-text"', 'class="tribe-events-pro-map__event-datetime-featured-text tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_event_card_event_date_time_featured_output', 'enlightenment_tribe_bootstrap_template_map_event_cards_event_card_event_date_time_featured_output' );

function enlightenment_tribe_bootstrap_template_map_event_cards_event_card_actions_output( $output ) {
	$output = str_replace( 'class="tribe-events-c-small-cta__link tribe-common-cta tribe-common-cta--thin-alt ', 'class="tribe-events-c-small-cta__link tribe-common-cta tribe-common-cta--thin-alt btn btn-secondary btn-sm ', $output );
	$output = str_replace( 'class="tribe-events-c-small-cta__link tribe-common-cta tribe-common-cta--thin-alt"', 'class="tribe-events-c-small-cta__link tribe-common-cta tribe-common-cta--thin-alt btn btn-secondary btn-sm"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_event_card_actions_output', 'enlightenment_tribe_bootstrap_template_map_event_cards_event_card_actions_output' );

function enlightenment_tribe_bootstrap_template_components_icons_arrow_left_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--arrow-left' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-arrow-left" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_arrow_left_output', 'enlightenment_tribe_bootstrap_template_components_icons_arrow_left_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_arrow_right_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--arrow-right' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-arrow-right" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_arrow_right_output', 'enlightenment_tribe_bootstrap_template_components_icons_arrow_right_output', 10, 4 );

function enlightenmentt_tribe_bootstrap_event_row_args( $args ) {
	$args['container_class'] .= ' row';

	return $args;
}
add_filter( 'enlightenment_event_row_args', 'enlightenmentt_tribe_bootstrap_event_row_args' );

// [tribe_events] shortcode only
function enlightenment_tribe_bootstrap_template_list_event_output( $output ) {
	$output = str_replace( 'class="tribe-common-g-row tribe-events-calendar-list__event-row ', 'class="tribe-common-g-row tribe-events-calendar-list__event-row row ', $output );
	$output = str_replace( 'class="tribe-common-g-row tribe-events-calendar-list__event-row"', 'class="tribe-common-g-row tribe-events-calendar-list__event-row row"', $output );
	$output = str_replace( 'class="tribe-events-calendar-list__event-wrapper tribe-common-g-col"', 'class="tribe-events-calendar-list__event-wrapper tribe-common-g-col col"', $output );
	$output = str_replace( 'class="tribe-events-calendar-list__event tribe-common-g-row tribe-common-g-row--gutters ', 'class="tribe-events-calendar-list__event tribe-common-g-row tribe-common-g-row--gutters row ', $output );
	$output = str_replace( 'class="tribe-events-calendar-list__event-details tribe-common-g-col"', 'class="tribe-events-calendar-list__event-details tribe-common-g-col col-12 col-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_list_event_output', 'enlightenment_tribe_bootstrap_template_list_event_output' );

// [tribe_events] shortcode only
function enlightenment_tribe_bootstrap_template_day_event_output( $output ) {
	$output = str_replace( 'class="tribe-events-calendar-day__event-content tribe-common-g-col"', 'class="tribe-events-calendar-day__event-content tribe-common-g-col row"', $output );
	$output = str_replace( 'class="tribe-events-calendar-day__event-details"', 'class="tribe-events-calendar-day__event-details col-12 col-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_day_event_output', 'enlightenment_tribe_bootstrap_template_day_event_output' );

function enlightenment_tribe_bootstrap_template_latest_past_event_output( $output ) {
	$output = str_replace( 'class="tribe-common-g-row tribe-events-calendar-latest-past__event-row ', 'class="tribe-common-g-row tribe-events-calendar-latest-past__event-row row ', $output );
	$output = str_replace( 'class="tribe-common-g-row tribe-events-calendar-latest-past__event-row"', 'class="tribe-common-g-row tribe-events-calendar-latest-past__event-row row"', $output );
	$output = str_replace( 'class="tribe-events-calendar-latest-past__event-wrapper tribe-common-g-col"', 'class="tribe-events-calendar-latest-past__event-wrapper tribe-common-g-col col"', $output );
	$output = str_replace( 'class="tribe-events-calendar-latest-past__event tribe-common-g-row tribe-common-g-row--gutters ', 'class="tribe-events-calendar-latest-past__event tribe-common-g-row tribe-common-g-row--gutters row ', $output );
	$output = str_replace( 'class="tribe-events-calendar-latest-past__event-details tribe-common-g-col"', 'class="tribe-events-calendar-latest-past__event-details tribe-common-g-col col-12 col-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_latest_past_event_output', 'enlightenment_tribe_bootstrap_template_latest_past_event_output' );

function enlightenment_tribe_bootstrap_template_event_date_tag_output( $output ) {
	$output = str_replace( 'class="tribe-events-calendar-list__event-date-tag ', 'class="tribe-events-calendar-list__event-date-tag col flex-grow-0 flex-shrink-1 ', $output );
	$output = str_replace( 'class="tribe-events-pro-photo__event-date-tag ', 'class="tribe-events-pro-photo__event-date-tag col flex-grow-0 flex-shrink-1 ', $output );
	$output = str_replace( 'class="tribe-events-calendar-latest-past__event-date-tag ', 'class="tribe-events-calendar-latest-past__event-date-tag col flex-grow-0 flex-shrink-1 ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_list_event_date_tag_output', 'enlightenment_tribe_bootstrap_template_event_date_tag_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_event_date_tag_output', 'enlightenment_tribe_bootstrap_template_event_date_tag_output' );
add_filter( 'enlightenment_tribe_filter_template_latest_past_event_date_tag_output', 'enlightenment_tribe_bootstrap_template_event_date_tag_output' );

function enlightenmentt_tribe_bootstrap_event_date_tag( $output ) {
	$prefix = ( function_exists( 'tribe_is_photo' ) && tribe_is_photo() ) ? 'pro' : 'calendar';
	$view   = enlightenment_tribe_get_view();

	return str_replace(
		sprintf( 'class="tribe-events-%s-%s__event-date-tag tribe-common-g-col"', $prefix, $view ),
		sprintf( 'class="tribe-events-%s-%s__event-date-tag tribe-common-g-col col flex-grow-0 flex-shrink-1"', $prefix, $view ),
		$output
	);
}
// add_filter( 'enlightenment_event_date_tag', 'enlightenmentt_tribe_bootstrap_event_date_tag' );

function enlightenment_tribe_bootstrap_event_post_thumbnail( $output ) {
	$output = str_replace( 'class="tribe-events-calendar-list__event-featured-image-wrapper tribe-common-g-col"', 'class="tribe-events-calendar-list__event-featured-image-wrapper tribe-common-g-col col-12 col-lg flex-lg-grow-0 flex-lg-shrink-1 order-lg-1"', $output );
	$output = str_replace( 'class="tribe-events-calendar-day__event-featured-image-wrapper ', 'class="tribe-events-calendar-day__event-featured-image-wrapper col-12 col-lg flex-lg-grow-0 flex-lg-shrink-1 order-lg-1 ', $output );
	$output = str_replace( 'class="tribe-events-calendar-day__event-featured-image-wrapper"', 'class="tribe-events-calendar-day__event-featured-image-wrapper col-12 col-lg flex-lg-grow-0 flex-lg-shrink-1 order-lg-1"', $output );
	$output = str_replace( 'class="tribe-events-calendar-latest-past__event-featured-image-wrapper tribe-common-g-col"', 'class="tribe-events-calendar-latest-past__event-featured-image-wrapper tribe-common-g-col col-12 col-lg flex-lg-grow-0 flex-lg-shrink-1 order-lg-1"', $output );

	return $output;
}
add_filter( 'enlightenment_post_thumbnail', 'enlightenment_tribe_bootstrap_event_post_thumbnail' );
// [tribe_events] shortcode only
add_filter( 'enlightenment_tribe_filter_template_list_event_featured_image_output', 'enlightenment_tribe_bootstrap_event_post_thumbnail' );
// [tribe_events] shortcode only
add_filter( 'enlightenment_tribe_filter_template_day_event_featured_image_output', 'enlightenment_tribe_bootstrap_event_post_thumbnail' );
add_filter( 'enlightenment_tribe_filter_template_latest_past_event_featured_image_output', 'enlightenment_tribe_bootstrap_event_post_thumbnail' );

function enlightenmentt_tribe_bootstrap_event_wrapper_args( $args ) {
	switch ( enlightenment_tribe_get_view() ) {
		case 'list':
			$args['container_class'] .= ' col';
			$args['row_class']       .= ' row';
			break;

		case 'day':
			$args['col_class']       .= ' row';
			break;
	}

	return $args;
}
add_filter( 'enlightenment_event_wrapper_args', 'enlightenmentt_tribe_bootstrap_event_wrapper_args' );

function enlightenmentt_tribe_bootstrap_event_details_args( $args ) {
	switch ( enlightenment_tribe_get_view() ) {
		case 'list':
		case 'day':
			$args['container_class'] .= ' col-12 col-lg';
			break;

		case 'photo':
			$args['container_class'] .= ' col';
			break;
	}

	return $args;
}
add_filter( 'enlightenment_event_details_args', 'enlightenmentt_tribe_bootstrap_event_details_args' );

function enlightenment_tribe_bootstrap_template_components_icons_recurring_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--recurring' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-sync" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_recurring_output', 'enlightenment_tribe_bootstrap_template_components_icons_recurring_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_list_event_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-calendar-list__event-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-calendar-list__event-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_list_event_recurring_output', 'enlightenment_tribe_bootstrap_template_list_event_recurring_output' );

function enlightenment_tribe_bootstrap_template_components_icons_series_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--series' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-layer-group" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_series_output', 'enlightenment_tribe_bootstrap_template_components_icons_series_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_event_cost_output( $output ) {
	if ( false === strpos( $output, 'class="tribe-events-c-small-cta__link ' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-c-small-cta__link tribe-common-cta tribe-common-cta--thin-alt"', 'class="tribe-events-c-small-cta__link tribe-common-cta tribe-common-cta--thin-alt btn btn-secondary"', $output );
	$output = str_replace( 'class="tribe-events-c-small-cta__sold-out tribe-common-b3--bold"', 'class="tribe-events-c-small-cta__sold-out tribe-common-b3--bold text-danger"', $output );
	$output = str_replace( 'class="tribe-events-c-small-cta__stock"', 'class="tribe-events-c-small-cta__stock text-success"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_list_event_cost_output', 'enlightenment_tribe_bootstrap_template_event_cost_output' );
add_filter( 'enlightenment_tribe_filter_template_day_event_cost_output', 'enlightenment_tribe_bootstrap_template_event_cost_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_event_cost_output', 'enlightenment_tribe_bootstrap_template_event_cost_output' );
add_filter( 'enlightenment_tribe_filter_template_month_calendar_body_day_calendar_events_calendar_event_tooltip_cost_output', 'enlightenment_tribe_bootstrap_template_event_cost_output' );
add_filter( 'enlightenment_tribe_filter_template_month_mobile_events_mobile_day_mobile_event_cost_output', 'enlightenment_tribe_bootstrap_template_event_cost_output' );
add_filter( 'enlightenment_tribe_filter_template_week_grid_body_events_day_event_tooltip_cost_output', 'enlightenment_tribe_bootstrap_template_event_cost_output' );
add_filter( 'enlightenment_tribe_filter_template_week_mobile_events_day_event_cost_output', 'enlightenment_tribe_bootstrap_template_event_cost_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_event_cost_output', 'enlightenment_tribe_bootstrap_template_event_cost_output' );

function enlightenment_tribe_bootstrap_the_notices( $output, $notices ) {
	$output = str_replace( '<ul>', '<ul class="list-unstyled mb-0">', $output );
	$output = str_replace( '<li>', '<li class="alert alert-info" role="alert">', $output );

	return $output;
}
add_filter( 'tribe_the_notices', 'enlightenment_tribe_bootstrap_the_notices', 10, 2 );

function enlightenment_tribe_bootstrap_events_recurrence_tooltip( $output, $post_id ) {
	if ( ! tribe_is_recurring_event( $post_id ) ) {
		return $output;
	}

	$text = esc_attr( wp_kses( tribe_get_recurrence_text( $post_id ), 'strip' ) );

	$output = str_replace( '<span class="tribe-events-divider">|</span>', sprintf( '<span class="tribe-events-divider">|</span> <span class="recurring-event" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="%s">', $text ), $output );
	$output = str_replace( '<a ', '</span> <a ', $output );
	$output = str_replace( 'class="tribe-events-tooltip recurring-info-tooltip"', 'class="tribe-events-tooltip recurring-info-tooltip screen-reader-text visually-hidden"', $output );

	return $output;
}
add_filter( 'tribe_events_recurrence_tooltip', 'enlightenment_tribe_bootstrap_events_recurrence_tooltip', 10, 2 );

function enlightenment_tribe_bootstrap_single_event_links( $output ) {
	$output = str_replace( 'class="tribe-events-gcal tribe-events-button"', 'class="tribe-events-gcal tribe-events-button btn btn-secondary"', $output );
	$output = str_replace( 'class="tribe-events-ical tribe-events-button"', 'class="tribe-events-ical tribe-events-button btn btn-secondary ms-2"', $output );

	$output = str_replace( 'class="tribe-events-cal-links"', 'class="tribe-events-cal-links d-flex flex-column flex-md-row"', $output );

	$offset  = strpos( $output, 'class="tribe-events-button ' );
	$did_one = false;
	while ( false !== $offset ) {
		$class = ' btn btn-secondary';

		if ( $did_one ) {
			$class .= ' mt-2 mt-md-0 ms-md-2';
		}

		$output = substr_replace( $output, $class, $offset + 26, 0 );

		$offset  = strpos( $output, 'class="tribe-events-button ', $offset + 1 );
		$did_one = true;
	}

	// $output = str_replace( 'class="tribe-events-button ', 'class="tribe-events-button btn btn-secondary ', $output );

	return $output;
}
add_filter( 'tribe_events_ical_single_event_links', 'enlightenment_tribe_bootstrap_single_event_links', 22 );

function enlightenment_tribe_bootstrap_template_part_modules_meta_output( $output ) {
	$output = str_replace( 'class="tribe-events-single-section tribe-events-event-meta primary tribe-clearfix"', 'class="tribe-events-single-section tribe-events-event-meta primary tribe-clearfix row"', $output );
	$output = str_replace( 'class="tribe-events-meta-group tribe-events-meta-group-gmap"', 'class="tribe-events-meta-group tribe-events-meta-group-gmap col"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_modules_meta_output', 'enlightenment_tribe_bootstrap_template_part_modules_meta_output' );

function enlightenment_tribe_bootstrap_template_part_modules_meta_details_output( $output ) {
	return str_replace( 'class="tribe-events-meta-group tribe-events-meta-group-details"', 'class="tribe-events-meta-group tribe-events-meta-group-details col-12 col-md"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_part_modules_meta_details_output', 'enlightenment_tribe_bootstrap_template_part_modules_meta_details_output' );

function enlightenment_tribe_bootstrap_template_part_modules_meta_venue_output( $output ) {
	if ( ! did_action( 'tribe_events_single_event_meta_secondary_section_start' ) ) {
		$output = str_replace( 'class="tribe-events-meta-group tribe-events-meta-group-venue"', 'class="tribe-events-meta-group tribe-events-meta-group-venue col"', $output );
	}

	$output = str_replace( 'class="tribe-common-a11y-visual-hide"', 'class="tribe-common-a11y-visual-hide visually-hidden"', $output );

	$offset = strpos( $output, '<dd class="tribe-venue-url">' );
	if ( false !== $offset ) {
		$end    = strpos( $output, '</dd>', $offset );

		$offset = strpos( $output, '<a ', $offset );
		if ( false !== $offset && $offset < $end ) {
			$offset_a = strpos( $output, 'class="', $offset );
			$end_a    = strpos( $output, '>', $offset );

			if ( false !== $offset_a && $offset_a < $end_a ) {
				$offset_a = strpos( $output, '"', $offset_a + 7 );
				$output   = substr_replace( $output, ' btn btn-secondary', $offset_a, 0 );
			} else {
				$output = substr_replace( $output, ' class="btn btn-secondary"', $offset + 2, 0 );
			}
		}
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_modules_meta_venue_output', 'enlightenment_tribe_bootstrap_template_part_modules_meta_venue_output' );

function enlightenment_tribe_bootstrap_template_part_modules_meta_organizer_output( $output ) {
	$output = str_replace( 'class="tribe-events-meta-group tribe-events-meta-group-organizer"', 'class="tribe-events-meta-group tribe-events-meta-group-organizer col-12 col-md"', $output );
	$output = str_replace( 'class="tribe-common-a11y-visual-hide"', 'class="tribe-common-a11y-visual-hide visually-hidden"', $output );

	if ( empty( tribe_events_get_organizer_website_title() ) ) {
		$start = strpos( $output, '<dd class="tribe-organizer-url">' );

		if ( false !== $start ) {
			$end    = strpos( $output, '</dd>', $start );
			$offset = strpos( $output, '<a ', $start );

			if ( false !== $offset && $offset < $end ) {
				$output = substr_replace( $output, ' class="btn btn-secondary"', $offset + 2, 0 );
			}
		}
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_modules_meta_organizer_output', 'enlightenment_tribe_bootstrap_template_part_modules_meta_organizer_output' );

function enlightenment_tribe_bootstrap_events_promo_banner( $output ) {
	return str_replace( 'class="tribe-events-promo tribe-common-b1 tribe-events-c-promo"', 'class="tribe-events-promo tribe-common-b1 tribe-events-c-promo mb-0"', $output );
}
add_filter( 'tribe_events_promo_banner', 'enlightenment_tribe_bootstrap_events_promo_banner' );

function enlightenment_tribe_bootstrap_rsvp_template( $output ) {
	if ( false === strpos( $output, 'id="rsvp-now"' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-rsvp-message tribe-rsvp-message-success"', 'class="tribe-rsvp-message tribe-rsvp-message-success alert alert-success"', $output );
	$output = str_replace( 'class="tribe-rsvp-message tribe-rsvp-message-error tribe-rsvp-message-confirmation-error"', 'class="tribe-rsvp-message tribe-rsvp-message-error tribe-rsvp-message-confirmation-error alert alert-danger"', $output );
	$output = str_replace( 'class="tribe-events-tickets tribe-events-tickets-rsvp"', 'class="tribe-events-tickets tribe-events-tickets-rsvp w-100"', $output );

	$output = str_replace( 'class="tribe-tickets-remaining"', 'class="tribe-tickets-remaining d-block form-text text-nowrap"', $output );
	$output = str_replace( 'class="available-stock"', 'class="available-stock d-block form-text text-nowrap"', $output );
	$output = str_replace( 'class="tickets_nostock"', 'class="tickets_nostock d-block form-text text-nowrap"', $output );

	$offset = strpos( $output, '<td class="tickets_name">' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="h3 mb-0">', $offset + 25, 0 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );

		$offset = strpos( $output, '<td class="tickets_name">', $offset );
	}

	$output = str_replace( 'class="tribe-tickets-quantity"', 'class="tribe-tickets-quantity form-control"', $output );
	$output = str_replace( 'class="tickets_description"', 'class="tickets_description text-body-secondary"', $output );
	$output = str_replace( 'class="tribe-event-tickets-meta-required-message"', 'class="tribe-event-tickets-meta-required-message alert alert-danger d-none"', $output );

	$offset = strpos( $output, 'class="tribe-tickets-attendees"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<header>', $offset );
		$output = substr_replace( $output, '<h3>', $offset , 8 );
		$offset = strpos( $output, '</header>', $offset );
		$output = substr_replace( $output, '</h3>', $offset, 9 );
	}

	$output = str_replace( 'class="tribe-tickets-table"', 'class="tribe-tickets-table w-100"', $output );
	$output = str_replace( 'id="tribe-tickets-full-name"', 'id="tribe-tickets-full-name" class="form-control"', $output );
	$output = str_replace( 'id="tribe-tickets-email"', 'id="tribe-tickets-email" class="form-control"', $output );

	$offset = strpos( $output, 'class="tribe-tickets-attendees-list-optout"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="form-check">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, '</div>', $offset + 8, 0 );
	}

	$output = str_replace( 'id="tribe-tickets-attendees-list-optout"', 'id="tribe-tickets-attendees-list-optout" class="form-check-input"', $output );
	$output = str_replace( 'for="tribe-tickets-attendees-list-optout"', 'for="tribe-tickets-attendees-list-optout" class="form-check-label"', $output );

	$offset = strpos( $output, 'name="tribe_tickets_rsvp_submission"' );
	if ( false === $offset ) {
		$offset = strpos( $output, 'class="add-to-cart"' );
		if ( false !== $offset ) {
			$offset = strpos( $output, '<a ', $offset );
			$output = substr_replace( $output, 'class="btn btn-primary"', $offset + 3, 0 );
		}
	}

	$output = str_replace( 'class="tribe-button tribe-button--rsvp"', 'class="tribe-button tribe-button--rsvp btn btn-primary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_rsvp_template', 'enlightenment_tribe_bootstrap_rsvp_template' );
add_filter( 'the_content', 'enlightenment_tribe_bootstrap_rsvp_template', 13 );

function enlightenment_tribe_bootstrap_template_tickets_view_link_output( $output ) {
	$output = str_replace( 'class="wp-block-tribe-link-view-attendee tribe-link-view-attendee"', 'class="wp-block-tribe-link-view-attendee tribe-link-view-attendee alert alert-info d-flex flex-wrap align-items-center"', $output );
	$output = str_replace( 'class="tribe-link-view-attendee"', 'class="tribe-link-view-attendee alert alert-info d-flex flex-wrap align-items-center"', $output );

	$offset = strpos( $output, 'class="wp-block-tribe-link-view-attendee tribe-link-view-attendee ' );

	if ( false === $offset ) {
		$offset = strpos( $output, 'class="tribe-link-view-attendee ' );
	}

	if ( false !== $offset ) {
		$offset = strpos( $output, '<a ', $offset );

		if ( false !== $offset ) {
			$output = substr_replace( $output, 'class="btn btn-outline-info mt-1 mt-md-0 ms-md-auto" ', $offset + 3, 0 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_tickets_view_link_output', 'enlightenment_tribe_bootstrap_template_tickets_view_link_output' );
add_filter( 'enlightenment_tribe_filter_link_template', 'enlightenment_tribe_bootstrap_template_tickets_view_link_output' );
add_filter( 'the_content', 'enlightenment_tribe_bootstrap_template_tickets_view_link_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_output( $output ) {
	return str_replace( 'class="tribe-tickets__rsvp-wrapper"', 'class="tribe-tickets__rsvp-wrapper position-relative mb-3"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_output' );

function enlightenment_tribe_bootstrap_template_v2_components_loader_loader_output( $output ) {
	$output = str_replace( 'class="tribe-tickets-loader__dots tribe-common-c-loader tribe-common-a11y-hidden ', 'class="tribe-tickets-loader__dots tribe-common-c-loader tribe-common-a11y-hidden d-flex align-items-center justify-content-center position-absolute top-0 bottom-0 start-0 end-0 ', $output );
	$output = str_replace( 'class="tribe-tickets-loader__dots tribe-common-c-loader tribe-common-a11y-hidden"', 'class="tribe-tickets-loader__dots tribe-common-c-loader tribe-common-a11y-hidden d-flex align-items-center justify-content-center position-absolute top-0 bottom-0 start-0 end-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_components_loader_loader_output', 'enlightenment_tribe_bootstrap_template_v2_components_loader_loader_output' );

function enlightenment_tribe_bootstrap_template_components_icons_dot_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--dot' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );

		if ( in_array( 'tribe-common-c-loader__dot--first', $context['classes'] ) ) {
			$classes[] = 'fa-pulse fa-2x';
		}

		if ( in_array( 'tribe-common-c-loader__dot--second', $context['classes'] ) ) {
			return '';
		}

		if ( in_array( 'tribe-common-c-loader__dot--third', $context['classes'] ) ) {
			return '';
		}
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-spinner" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_dot_output', 'enlightenment_tribe_bootstrap_template_components_icons_dot_output', 10, 4 );
add_filter( 'enlightenment_tribe_filter_template_v2_components_icons_dot_output', 'enlightenment_tribe_bootstrap_template_components_icons_dot_output', 10, 4 );

function enlightenment_tribe_bootstrap_waitlist_for_rsvp_on_presale( $output ) {
	return str_replace( 'class="tribe-tickets__tickets-item-content tribe-tickets__tickets-item-content--inactive"', 'class="tribe-tickets__tickets-item-content tribe-tickets__tickets-item-content--inactive alert alert-info"', $output );
}
add_filter( 'tribe_template_pre_html:tickets/v2/rsvp', 'enlightenment_tribe_bootstrap_waitlist_for_rsvp_on_presale', 20 );

function enlightenment_tribe_bootstrap_template_waitlist_form_output( $output ) {
	$output = str_replace( '<h3>', '<h3 class="h5">', $output );
	$output = str_replace( '<p>', '<p class="mb-0">', $output );
	$output = str_replace( 'class="tec-tickets-plus-waitlist-container--input"', 'class="tec-tickets-plus-waitlist-container--input mb-3"', $output );
	$output = str_replace( '<label', '<label class="form-label"', $output );
	$output = str_replace( 'type="text"', 'type="text" class="form-control"', $output );
	$output = str_replace( 'type="email"', 'type="email" class="form-control"', $output );
	$output = str_replace( 'class="tec-tickets-plus-waitlist-container--input--error"', 'class="tec-tickets-plus-waitlist-container--input--error invalid-feedback"', $output );
	$output = str_replace( 'class="tec-tickets-plus-waitlist-submit"', 'class="tec-tickets-plus-waitlist-submit btn btn-primary btn-lg"', $output );
	$output = str_replace( 'class="tec-tickets-plus-waitlist-container__success ', 'class="tec-tickets-plus-waitlist-container__success alert alert-success mb-0 ', $output );
	$output = str_replace( 'class="tec-tickets-plus-waitlist-container__success-row"', 'class="tec-tickets-plus-waitlist-container__success-row d-flex flex-wrap align-items-flex-start gap-2"', $output );
	$output = str_replace( '<h4>', '<h4 class="alert-heading">', $output );

	$offset = strpos( $output, 'class="tec-tickets-plus-waitlist-container__success ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '"', $offset + 7 );
		$output = substr_replace( $output, ' style="display: none;"', $offset + 1, 0 );
	}

	$start = strpos( $output, '<svg' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</svg>', $start ) + 6;
		$length = $end - $start;
		$output = substr_replace( $output, '<i class="fas fa-check" aria-hidden="true" role="presentation"></i>', $start, $length );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_form_output', 'enlightenment_tribe_bootstrap_template_waitlist_form_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_content_output( $output ) {
	return str_replace( 'class="tribe-tickets__rsvp tribe-common-g-row tribe-common-g-row--gutters"', 'class="tribe-tickets__rsvp tribe-common-g-row tribe-common-g-row--gutters card"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_content_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_content_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_messages_must_login_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-message tribe-tickets__rsvp-message--must-login tribe-common-b3"', 'class="tribe-tickets__rsvp-message tribe-tickets__rsvp-message--must-login tribe-common-b3 alert alert-danger d-flex align-items-center"', $output );
	$output = str_replace( 'class="tribe-tickets__rsvp-message-text"', 'class="tribe-tickets__rsvp-message-text flex-grow-1"', $output );
	$output = str_replace( '<strong>', '<strong class="d-flex align-items-center ms-2">', $output );
	$output = str_replace( 'class="tribe-tickets__rsvp-message-link"', 'class="tribe-tickets__rsvp-message-link btn btn-outline-danger ms-auto"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_messages_must_login_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_messages_must_login_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_messages_error_output( $output ) {
	$output  = str_replace( 'class="tribe-tickets__rsvp-message tribe-tickets__rsvp-message--error tribe-common-b3"', 'class="tribe-tickets__rsvp-message tribe-tickets__rsvp-message--error tribe-common-b3 alert alert-danger d-flex"', $output );
	$output  = str_replace( '</i>', '</i>' . "\n" . '<div class="ms-2">', $output );
	$output .= "\n" . '</div>';

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_messages_error_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_messages_error_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_messages_success_output( $output ) {
	return str_replace( 'class="tribe-tickets__rsvp-message tribe-tickets__rsvp-message--success tribe-common-b3"', 'class="tribe-tickets__rsvp-message tribe-tickets__rsvp-message--success tribe-common-b3 alert alert-success d-flex align-items-center"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_messages_success_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_messages_success_output' );

function enlightenment_tribe_bootstrap_template_v2_components_icons_paper_plane_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-tickets-svgicon' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-paper-plane" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_v2_components_icons_paper_plane_output', 'enlightenment_tribe_bootstrap_template_v2_components_icons_paper_plane_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_rsvp_messages_success_going_output( $output ) {
	return str_replace( 'class="tribe-tickets__rsvp-message-text"', 'class="tribe-tickets__rsvp-message-text ms-2"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_messages_success_going_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_messages_success_going_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_messages_success_not_going_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_messages_success_going_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_details_title_output( $output ) {
	$output = '<div class="card-header d-flex align-items-baseline">' . "\n" . $output;
	$output = str_replace( 'class="tribe-tickets__rsvp-title tribe-common-h2 tribe-common-h4--min-medium"', 'class="tribe-tickets__rsvp-title tribe-common-h2 tribe-common-h4--min-medium h4 mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_details_title_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_details_title_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_details_description_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-description tribe-common-h6 tribe-common-h--alt tribe-common-b3--min-medium"', 'class="tribe-tickets__rsvp-description tribe-common-h6 tribe-common-h--alt tribe-common-b3--min-medium text-body-secondary ms-2"', $output );
	$output  = str_replace( '<p>', '<p class="mb-0">', $output );
	$output .= "\n" . '</div>';

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_details_description_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_details_description_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_details_attendance_output( $output ) {
	$output = '<div class="card-body d-flex flex-wrap align-items-end">' . "\n" . $output;
	$output = str_replace( 'class="tribe-tickets__rsvp-attendance"', 'class="tribe-tickets__rsvp-attendance d-flex flex-column text-center"', $output );
	$output = str_replace( 'class="tribe-tickets__rsvp-attendance-number ', 'class="tribe-tickets__rsvp-attendance-number h4 ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_details_attendance_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_details_attendance_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_details_availability_output( $output ) {
	$output  = str_replace( 'class="tribe-tickets__rsvp-availability tribe-common-h6 tribe-common-h--alt tribe-common-b3--min-medium"', 'class="tribe-tickets__rsvp-availability tribe-common-h6 tribe-common-h--alt tribe-common-b3--min-medium ms-5"', $output );
	$output = $output . "\n" . '</div>';

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_details_availability_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_details_availability_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_details_availability_remaining_output( $output ) {
	$output  = sprintf( '<div class="d-flex flex-column text-center">%s</div>', $output );
	$output  = str_replace( 'class="tribe-tickets__rsvp-availability-quantity tribe-common-b2--bold"', 'class="tribe-tickets__rsvp-availability-quantity tribe-common-b2--bold h4"', $output );
	$output .= ' </span>';
	$output  = str_replace( ',', '', $output );
	$output  = str_replace( '</span>', '</span> <span class="tribe-tickets__rsvp-availability-remaining"> ', $output );
	$output .= ' </span>';

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_details_availability_remaining_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_details_availability_remaining_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_details_availability_days_to_rsvp_output( $output ) {
	$output = '</div>' . "\n" . '<div class="w-100 mt-2">' . "\n" . $output;
	$output = str_replace( 'class="tribe-tickets__rsvp-availability-days-to-rsvp"', 'class="tribe-tickets__rsvp-availability-days-to-rsvp small text-body-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_details_availability_days_to_rsvp_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_details_availability_days_to_rsvp_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_actions_output( $output ) {
	return str_replace( 'class="tribe-tickets__rsvp-actions"', 'class="tribe-tickets__rsvp-actions card-footer"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_actions_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_actions_success_title_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-actions-success-going"', 'class="tribe-tickets__rsvp-actions-success-going d-flex align-items-center text-success"', $output );
	$output = str_replace( '<em class="tribe-tickets__rsvp-actions-success-going-check-icon"></em>', '<i class="fas fa-check" role="presentation" aria-hidden="true"></i>', $output );
	$output = str_replace( 'class="tribe-tickets__rsvp-actions-success-going-text tribe-common-h4 tribe-common-h6--min-medium"', 'class="tribe-tickets__rsvp-actions-success-going-text tribe-common-h4 tribe-common-h6--min-medium ms-2"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_actions_success_title_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_success_title_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_actions_success_toggle_output( $output, $template_name, $file, $template ) {
	$context = $template->get_local_values();

	$offset = strpos( $output, sprintf( 'id="tribe-tickets-tooltip-content-%s"', esc_attr( $context['rsvp']->ID ) ) );
	if ( false !== $offset ) {
		$start   = strpos( $output, '>', $offset ) + 1;
		$end     = strpos( $output, '</div>', $start );
		$length  = $end - $start;
		$tooltip = trim( substr( $output, $start, $length ) );
	}

	$offset = strpos( $output, '<div class="tribe-common-a11y-hidden">' );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '</div>', $start );
		$end    = strpos( $output, '</div>', $end + 1 );
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	$output = str_replace( 'class="tribe-tickets__rsvp-actions-success-going-toggle tribe-common-form-control-toggle"', 'class="tribe-tickets__rsvp-actions-success-going-toggle tribe-common-form-control-toggle form-check mt-2"', $output );
	$output = str_replace( 'class="tribe-common-form-control-toggle__input tribe-tickets__rsvp-actions-success-going-toggle-input"', 'class="tribe-common-form-control-toggle__input tribe-tickets__rsvp-actions-success-going-toggle-input form-check-input"', $output );
	$output = str_replace( 'class="tribe-common-form-control-toggle__label tribe-tickets__rsvp-actions-success-going-toggle-label"', 'class="tribe-common-form-control-toggle__label tribe-tickets__rsvp-actions-success-going-toggle-label form-check-label"', $output );
	$output = str_replace( 'data-js="tribe-tickets-tooltip"', sprintf( 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="%s"', $tooltip ), $output );
	$output = str_replace( sprintf( 'data-tooltip-content="#tribe-tickets-tooltip-content-%s"', esc_attr( $context['rsvp']->ID ) ), '', $output );
	$output = str_replace( sprintf( 'aria-describedby="tribe-tickets-tooltip-content-%s"', esc_attr( $context['rsvp']->ID ) ), '', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_actions_success_toggle_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_success_toggle_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-actions-rsvp"', 'class="tribe-tickets__rsvp-actions-rsvp d-flex align-items-center"', $output );
	$output = str_replace( 'class="tribe-common-h2 tribe-common-h6--min-medium"', 'class="tribe-common-h2 tribe-common-h6--min-medium me-auto"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_actions_rsvp_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_going_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-actions-rsvp-going"', 'class="tribe-tickets__rsvp-actions-rsvp-going ms-3"', $output );
	$output = str_replace( 'class="tribe-common-c-btn tribe-tickets__rsvp-actions-button-going tribe-common-b1 tribe-common-b2--min-medium"', 'class="tribe-common-c-btn tribe-tickets__rsvp-actions-button-going tribe-common-b1 tribe-common-b2--min-medium btn btn-primary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_actions_rsvp_going_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_going_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_not_going_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-actions-rsvp-not-going"', 'class="tribe-tickets__rsvp-actions-rsvp-not-going ms-2"', $output );
	$output = str_replace( 'class="tribe-common-cta tribe-common-cta--alt tribe-tickets__rsvp-actions-button-not-going"', 'class="tribe-common-cta tribe-common-cta--alt tribe-tickets__rsvp-actions-button-not-going btn btn-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_actions_rsvp_not_going_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_not_going_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_form_fields_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 ', 'class="tribe-common-b1 mb-3 ', $output );
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-tickets__form-field-label"', 'class="tribe-tickets__form-field-label form-label"', $output );
	$output = str_replace( 'class="tribe-common-b2--min-medium tribe-tickets__form-field-label"', 'class="tribe-common-b2--min-medium tribe-tickets__form-field-label form-label"', $output );
	$output = str_replace( 'class="tribe-required"', 'class="tribe-required text-danger"', $output );
	$output = str_replace( 'class="tribe-common-form-control-text__input tribe-tickets__form-field-input ', 'class="tribe-common-form-control-text__input tribe-tickets__form-field-input form-control ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_form_fields_name_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_form_fields_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_form_fields_email_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_form_fields_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_form_fields_quantity_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_form_fields_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_form_fields_cancel_output( $output ) {
	return str_replace( 'class="tribe-common-h7 tribe-tickets__rsvp-form-button tribe-tickets__rsvp-form-button--cancel"', 'class="tribe-common-h7 tribe-tickets__rsvp-form-button tribe-tickets__rsvp-form-button--cancel btn btn-secondary order-1 ms-2"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_form_fields_cancel_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_form_fields_cancel_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_form_fields_submit_output( $output ) {
	return str_replace( 'class="tribe-common-c-btn tribe-tickets__rsvp-form-button"', 'class="tribe-common-c-btn tribe-tickets__rsvp-form-button btn btn-primary"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_form_fields_submit_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_form_fields_submit_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_form_buttons_output( $output ) {
	return str_replace( 'class="tribe-tickets__rsvp-form-buttons"', 'class="tribe-tickets__rsvp-form-buttons d-flex"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_form_buttons_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_form_buttons_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-ar tribe-common-g-row tribe-common-g-row--gutters"', 'class="tribe-tickets__rsvp-ar tribe-common-g-row tribe-common-g-row--gutters row"', $output );
	$output = str_replace( 'class="tribe-tickets__rsvp-ar-sidebar-wrapper tribe-common-g-col"', 'class="tribe-tickets__rsvp-ar-sidebar-wrapper tribe-common-g-col col-12 col-md-4"', $output );
	$output = str_replace( 'class="tribe-tickets__rsvp-ar-form-wrapper tribe-common-g-col"', 'class="tribe-tickets__rsvp-ar-form-wrapper tribe-common-g-col col-12 col-md-8"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_title_output( $output ) {
	return str_replace( 'class="tribe-common-h5"', 'class="tribe-common-h5 h5"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_sidebar_title_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_title_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_quantity_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-ar-quantity"', 'class="tribe-tickets__rsvp-ar-quantity mb-3"', $output );

	$offset = strpos( $output, '<span class="tribe-common-h7 tribe-common-h--alt">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, 'label', $offset + 1, 4 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, 'label', $offset + 2, 4 );
	}

	$output = str_replace( 'class="tribe-tickets__rsvp-ar-quantity-input"', 'class="tribe-tickets__rsvp-ar-quantity-input input-group"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_sidebar_quantity_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_quantity_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_quantity_minus_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-ar-quantity-input-number tribe-tickets__rsvp-ar-quantity-input-number--minus"', 'class="tribe-tickets__rsvp-ar-quantity-input-number tribe-tickets__rsvp-ar-quantity-input-number--minus btn btn-light"', $output );
	$output = str_replace( '<span class="tribe-common-a11y-hidden">', '<i class="fas fa-minus" role="presentation" aria-hidden="true"></i><span class="tribe-common-a11y-hidden screen-reader-text visually-hidden">', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_sidebar_quantity_minus_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_quantity_minus_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_quantity_plus_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-ar-quantity-input-number tribe-tickets__rsvp-ar-quantity-input-number--plus"', 'class="tribe-tickets__rsvp-ar-quantity-input-number tribe-tickets__rsvp-ar-quantity-input-number--plus btn btn-light"', $output );
	$output = str_replace( '<span class="tribe-common-a11y-hidden">', '<i class="fas fa-plus" role="presentation" aria-hidden="true"></i><span class="tribe-common-a11y-hidden screen-reader-text visually-hidden">', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_sidebar_quantity_plus_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_quantity_plus_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_quantity_input_output( $output ) {
	return str_replace( 'class="tribe-common-h4"', 'class="tribe-common-h4 form-control"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_sidebar_quantity_input_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_quantity_input_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_guest_list_output( $output ) {
	return str_replace( 'class="tribe-tickets__rsvp-ar-guest-list tribe-common-h6"', 'class="tribe-tickets__rsvp-ar-guest-list tribe-common-h6 nav nav-pills flex-column"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_sidebar_guest_list_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_guest_list_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_guest_list_guest_output( $output, $template_name, $file, $template ) {
	$context = $template->get_local_values();

	$output = str_replace( 'class="tribe-tickets__rsvp-ar-guest-list-item"', 'class="tribe-tickets__rsvp-ar-guest-list-item nav-item"', $output );
	$output = str_replace( 'class="tribe-tickets__rsvp-ar-guest-list-item-button"', 'class="tribe-tickets__rsvp-ar-guest-list-item-button nav-link w-100 text-start active"', $output );
	$output = str_replace( 'type="button"', sprintf( 'type="button" data-bs-toggle="tab" data-bs-target="#tribe-tickets-rsvp-%s-guest-1-tab"', $context['rsvp']->ID ), $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_sidebar_guest_list_guest_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_guest_list_guest_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_guest_list_guest_template_output( $output, $template_name, $file, $template ) {
	$context = $template->get_local_values();
	$output = str_replace( 'class="tribe-tickets__rsvp-ar-guest-list-item"', 'class="tribe-tickets__rsvp-ar-guest-list-item nav-item"', $output );
	$output = str_replace( 'class="tribe-tickets__rsvp-ar-guest-list-item-button tribe-tickets__rsvp-ar-guest-list-item-button--inactive"', 'class="tribe-tickets__rsvp-ar-guest-list-item-button tribe-tickets__rsvp-ar-guest-list-item-button--inactive nav-link w-100 text-start"', $output );
	$output = str_replace( 'type="button"', sprintf( 'type="button" data-bs-toggle="tab" data-bs-target="#tribe-tickets-rsvp-%s-guest-{{data.attendee_id + 1}}-tab"', $context['rsvp']->ID ), $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_sidebar_guest_list_guest_template_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_sidebar_guest_list_guest_template_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_components_icons_guest_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-tickets-svgicon' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-user"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_v2_components_icons_guest_output', 'enlightenment_tribe_bootstrap_template_v2_components_icons_guest_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_output( $output ) {
	return str_replace( 'class="tribe-tickets__rsvp-ar-form"', 'class="tribe-tickets__rsvp-ar-form tab-content"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_form_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_guest_output( $output ) {
	return str_replace( 'class="tribe-tickets__rsvp-ar-form-guest"', 'class="tribe-tickets__rsvp-ar-form-guest tab-pane fade show active"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_form_guest_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_guest_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_guest_template_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-ar-form-guest tribe-common-a11y-hidden"', 'class="tribe-tickets__rsvp-ar-form-guest tribe-common-a11y-hidden tab-pane fade"', $output );

	$offset = strpos( $output, 'aria-labelledby="tribe-tickets-rsvp-' );
	if ( false !== $offset ) {
		$end    = strpos( $output, '>', $offset );
		$offset = strpos( $output, 'hidden', $offset );

		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, '', $offset, 6 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_form_guest_template_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_guest_template_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_title_output( $output ) {
	return str_replace( 'class="tribe-tickets__rsvp-ar-form-title tribe-common-h5"', 'class="tribe-tickets__rsvp-ar-form-title tribe-common-h5 h5"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_form_title_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_title_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_form_template_title_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_title_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_error_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__form-message tribe-tickets__form-message--error tribe-common-b3 tribe-common-a11y-hidden"', 'class="tribe-tickets__form-message tribe-tickets__form-message--error tribe-common-b3 tribe-common-a11y-hidden alert alert-danger d-flex align-items-center"', $output );
	$output = str_replace( 'class="tribe-tickets__form-message-text"', 'class="tribe-tickets__form-message-text ms-2"', $output );
	$output = str_replace( '<p>', '<p class="mb-0">', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_form_error_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_error_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_fields_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-tickets__form-field ', 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-tickets__form-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-tickets__form-field-label"', 'class="tribe-tickets__form-field-label form-label d-flex"', $output );
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-required"', 'class="tribe-required text-danger ms-1"', $output );
	$output = str_replace( 'class="tribe-common-form-control-text__input tribe-tickets__form-field-input ', 'class="tribe-common-form-control-text__input tribe-tickets__form-field-input form-control ', $output );
	$output = str_replace( 'class="tribe-common-form-control-text__input tribe-tickets__form-field-input"', 'class="tribe-common-form-control-text__input tribe-tickets__form-field-input form-control"', $output );
	$output = str_replace( 'class="tribe-common-b3 tribe-tickets__form-field-description"', 'class="tribe-common-b3 tribe-tickets__form-field-description form-text"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_form_fields_name_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_fields_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_form_fields_email_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_fields_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_components_meta_text_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_fields_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_components_meta_email_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_fields_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_components_meta_url_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_fields_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_components_meta_number_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_fields_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_components_meta_telephone_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_fields_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_components_meta_select_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_fields_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_components_meta_datetime_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_ari_form_fields_output' );

function enlightenment_tribe_bootstrap_template_v2_components_icons_error_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-tickets-svgicon' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-exclamation-circle"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_v2_components_icons_error_output', 'enlightenment_tribe_bootstrap_template_v2_components_icons_error_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_components_meta_select_output( $output ) {
	$output = str_replace( 'class="tribe-common-form-control-text__input tribe-tickets__form-field-input form-control ', 'class="tribe-common-form-control-text__input tribe-tickets__form-field-input form-select ', $output );
	$output = str_replace( 'class="tribe-common-form-control-text__input tribe-tickets__form-field-input form-control"', 'class="tribe-common-form-control-text__input tribe-tickets__form-field-input form-select"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_components_meta_select_output', 'enlightenment_tribe_bootstrap_template_v2_components_meta_select_output' );

function enlightenment_tribe_bootstrap_template_v2_components_meta_checkbox_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-tickets__form-field ', 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-tickets__form-field mb-3 ', $output );
	$output = str_replace( '<header class="tribe-tickets__form-field-label">', '<label class="tribe-tickets__form-field-label form-label d-flex">', $output );
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-required"', 'class="tribe-required text-danger ms-1"', $output );
	$output = str_replace( '</header>', '</label>', $output );

	$offset = strpos( $output, '<div class="tribe-common-form-control-checkbox">' );
	while ( false !== $offset ) {
		$start  = strpos( $output, '<label', $offset );
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$label  = substr( $output, $start, $length );
		$output = substr_replace( $output, '', $start, $length );
		$offset = strpos( $output, '<input', $offset );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, $label, $offset + 1, 0 );

		$offset = strpos( $output, '<div class="tribe-common-form-control-checkbox">', $offset );
	}

	$output = str_replace( 'class="tribe-common-form-control-checkbox"', 'class="tribe-common-form-control-checkbox form-check"', $output );
	$output = str_replace( 'class="tribe-common-form-control-checkbox__input tribe-tickets__form-field-input tribe-tickets__form-field-input--checkbox"', 'class="tribe-common-form-control-checkbox__input tribe-tickets__form-field-input tribe-tickets__form-field-input--checkbox form-check-input"', $output );
	$output = str_replace( 'class="tribe-common-form-control-checkbox__label"', 'class="tribe-common-form-control-checkbox__label form-check-label"', $output );
	$output = str_replace( 'class="tribe-common-b3 tribe-tickets__form-field-description"', 'class="tribe-common-b3 tribe-tickets__form-field-description form-text"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_components_meta_checkbox_output', 'enlightenment_tribe_bootstrap_template_v2_components_meta_checkbox_output' );

function enlightenment_tribe_bootstrap_template_v2_components_meta_radio_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-tickets__form-field ', 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-tickets__form-field mb-3 ', $output );
	$output = str_replace( '<header class="tribe-tickets__form-field-label">', '<label class="tribe-tickets__form-field-label form-label d-flex">', $output );
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-required"', 'class="tribe-required text-danger ms-1"', $output );
	$output = str_replace( '</header>', '</label>', $output );

	$offset = strpos( $output, '<div class="tribe-common-form-control-radio">' );
	while ( false !== $offset ) {
		$start  = strpos( $output, '<label', $offset );
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$label  = substr( $output, $start, $length );
		$output = substr_replace( $output, '', $start, $length );
		$offset = strpos( $output, '<input', $offset );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, $label, $offset + 1, 0 );

		$offset = strpos( $output, '<div class="tribe-common-form-control-radio">', $offset );
	}

	$output = str_replace( 'class="tribe-common-form-control-radio"', 'class="tribe-common-form-control-radio form-check"', $output );
	$output = str_replace( 'class="tribe-common-form-control-radio__input tribe-tickets__form-field-input"', 'class="tribe-common-form-control-radio__input tribe-tickets__form-field-input form-check-input"', $output );
	$output = str_replace( 'class="tribe-common-form-control-radio__label"', 'class="tribe-common-form-control-radio__label form-check-label"', $output );
	$output = str_replace( 'class="tribe-common-b3 tribe-tickets__form-field-description"', 'class="tribe-common-b3 tribe-tickets__form-field-description form-text"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_components_meta_radio_output', 'enlightenment_tribe_bootstrap_template_v2_components_meta_radio_output' );

function enlightenment_tribe_bootstrap_template_v2_components_meta_birth_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-tickets__form-field ', 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-tickets__form-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-tickets__form-field-label"', 'class="tribe-tickets__form-field-label form-label d-flex"', $output );
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-required"', 'class="tribe-required text-danger ms-1"', $output );

	$offset = strpos( $output, '<div class="tribe-tickets__form-field-input-wrapper">' );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '</div>', $start );

		$offset = strpos( $output, '<label', $offset );
		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, '<dummy-div class="col">' . "\n", $offset, 0 );
			$offset = strpos( $output, '</select>', $offset );
			$output = substr_replace( $output, "\n" . '</dummy-div>', $offset + 9, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<label', $offset );
		}
	}

	$output = str_replace( 'class="tribe-tickets__form-field-input-wrapper"', 'class="tribe-tickets__form-field-input-wrapper row gx-3"', $output );
	$output = str_replace( 'dummy-div', 'div', $output );
	$output = str_replace( 'class="tribe-common-form-control-text__input ', 'class="tribe-common-form-control-text__input form-select ', $output );
	$output = str_replace( 'class="tribe-common-b3 tribe-tickets__form-field-description"', 'class="tribe-common-b3 tribe-tickets__form-field-description form-text"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_components_meta_birth_output', 'enlightenment_tribe_bootstrap_template_v2_components_meta_birth_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_ari_form_buttons_output( $output, $template_name, $file, $template ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-form-buttons"', 'class="tribe-tickets__rsvp-form-buttons d-flex"', $output );
	$output = str_replace( 'class="tribe-common-h7 tribe-tickets__rsvp-form-button tribe-tickets__rsvp-form-button--cancel"', 'class="tribe-common-h7 tribe-tickets__rsvp-form-button tribe-tickets__rsvp-form-button--cancel btn btn-secondary order-1 ms-2"', $output );
	$output = str_replace( 'class="tribe-common-c-btn tribe-tickets__rsvp-form-button tribe-tickets__rsvp-form-button--next tribe-common-a11y-hidden"', 'class="tribe-common-c-btn tribe-tickets__rsvp-form-button tribe-tickets__rsvp-form-button--next tribe-common-a11y-hidden btn btn-primary"', $output );
	$output = str_replace( 'class="tribe-common-c-btn tribe-tickets__rsvp-form-button tribe-tickets__rsvp-form-button--submit"', 'class="tribe-common-c-btn tribe-tickets__rsvp-form-button tribe-tickets__rsvp-form-button--submit btn btn-primary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_ari_form_buttons_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_actions_rsvp_ari_form_buttons_output', 10, 4 );

function enlightenment_tribe_bootstrap_rsvp_front_end_meta_fields( $output ) {
	$offset = strpos( $output, '<header>' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<h4>', $offset + 8, 0 );
		$offset = strpos( $output, '</header>', $offset );
		$output = substr_replace( $output, '</h4>', $offset, 0 );
	}

	$output = str_replace( 'class="tribe-tickets-meta tribe-tickets-meta-', 'class="mb-3 tribe-tickets-meta tribe-tickets-meta-', $output );
	$output = str_replace( 'class="mb-3 tribe-tickets-meta tribe-tickets-meta-birth', 'class="mb-3 row gx-2 tribe-tickets-meta tribe-tickets-meta-birth', $output );

	$offset = strpos( $output, 'class="mb-3 row gx-2 tribe-tickets-meta tribe-tickets-meta-birth' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, 'class="col-12" ', $offset + 7, 0 );
	}

	$output = str_replace( 'class="tribe_horizontal_datepicker"', 'class="tribe_horizontal_datepicker col"', $output );
	$output = str_replace( 'class="tribe-common-a11y-hidden"', 'class="tribe-common-a11y-hidden screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe_horizontal_datepicker__month', 'class="tribe_horizontal_datepicker__month form-control"', $output );
	$output = str_replace( 'class="tribe_horizontal_datepicker__day', 'class="tribe_horizontal_datepicker__day form-control"', $output );
	$output = str_replace( 'class="tribe_horizontal_datepicker__year', 'class="tribe_horizontal_datepicker__year form-control"', $output );

    $start = strpos( $output, 'class="mb-3 tribe-tickets-meta tribe-tickets-meta-checkbox' );
	while ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = $start;

	    while ( $offset < $end ) {
			$offset = strpos( $output, '<label ', $offset );
			$output = substr_replace( $output, '<div-placeholder class="form-check">' . "\n", $offset, 0 );

			$offset = strpos( $output, '<label ', $offset );
			$close  = strpos( $output, '>', $offset ) + 1;
			$length = $close - $offset;
			$tag    = substr( $output, $offset, $length );

			$output = str_replace( $tag, '', $output );

			$tag    = str_replace( 'class="tribe-tickets-meta-field-header"', 'class="tribe-tickets-meta-field-header form-check-label"', $tag );

			$offset = strpos( $output, 'class="ticket-meta"', $offset );
			$output = substr_replace( $output, ' form-check-input', $offset + 18, 0 );

			$offset = strpos( $output, '<span class="tribe-tickets-meta-option-label">', $offset );
			$output = substr_replace( $output, $tag . "\n", $offset, 0 );

			$offset = strpos( $output, '</label>', $offset );
			$output = substr_replace( $output, "\n" . '</div-placeholder>', $offset + 8, 0 );

			$offset = strpos( $output, '<label ', $offset );
			$end    = strpos( $output, '</div>', $start );
	    }

		$start++;
		$start = strpos( $output, 'class="mb-3 tribe-tickets-meta tribe-tickets-meta-checkbox', $start );
	}

	$start = strpos( $output, 'class="mb-3 tribe-tickets-meta tribe-tickets-meta-radio' );
	while ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = $start;

	    while ( $offset < $end ) {
			$offset = strpos( $output, '<label ', $offset );
			$output = substr_replace( $output, '<div-placeholder class="form-check">' . "\n", $offset, 0 );

			$offset = strpos( $output, '<label ', $offset );
			$close  = strpos( $output, '>', $offset ) + 1;
			$length = $close - $offset;
			$tag    = substr( $output, $offset, $length );

			$output = str_replace( $tag, '', $output );

			$tag    = str_replace( 'class="tribe-tickets-meta-field-header"', 'class="tribe-tickets-meta-field-header form-check-label"', $tag );

			$offset = strpos( $output, 'class="ticket-meta"', $offset );
			$output = substr_replace( $output, ' form-check-input', $offset + 18, 0 );

			$offset = strpos( $output, '<span class="tribe-tickets-meta-option-label">', $offset );
			$output = substr_replace( $output, $tag . "\n", $offset, 0 );

			$offset = strpos( $output, '</label>', $offset );
			$output = substr_replace( $output, "\n" . '</div-placeholder>', $offset + 8, 0 );

			$offset = strpos( $output, '<label ', $offset );
			$end    = strpos( $output, '</div>', $start );
	    }

		$start++;
		$start = strpos( $output, 'class="mb-3 tribe-tickets-meta tribe-tickets-meta-radio', $start );
	}

	$output = str_replace( '<div-placeholder ', '<div ', $output );
	$output = str_replace( '</div-placeholder>', '</div>', $output );

	$output = str_replace( 'class="ticket-meta"', 'class="ticket-meta form-control"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_rsvp_front_end_meta_fields', 'enlightenment_tribe_bootstrap_rsvp_front_end_meta_fields' );

function enlightenment_tribe_bootstrap_template_blocks_attendees_view_link_output( $output ) {
	$output = str_replace( 'class="wp-block-tribe-link-view-attendee tribe-link-view-attendee"', 'class="wp-block-tribe-link-view-attendee tribe-link-view-attendee alert alert-info d-flex flex-wrap align-items-center"', $output );
	$output = str_replace( '<a ', '<a class="btn btn-outline-info mt-1 mt-md-0 ms-md-auto" ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_attendees_view_link_output', 'enlightenment_tribe_bootstrap_template_blocks_attendees_view_link_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_output( $output ) {
	$output = str_replace( 'class="tribe-block__rsvp__ticket ', 'class="tribe-block__rsvp__ticket card ', $output );
	$output = str_replace( 'class="tribe-block__rsvp__ticket"', 'class="tribe-block__rsvp__ticket card"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_icon_output( $output ) {
	$output = str_replace( '<div class="tribe-block__rsvp__icon">', '<div class="tribe-block__rsvp__icon card-header"><h2 class="mb-0">', $output );
	$output = str_replace( '</div>', '</h2></div>', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_icon_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_icon_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_content_output( $output ) {
	return str_replace( 'class="tribe-block__rsvp__details__status"', 'class="tribe-block__rsvp__details__status card-body"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_content_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_content_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_content_inactive_output( $output ) {
	return str_replace( 'class="tribe-block__rsvp__content tribe-block__rsvp__content--inactive"', 'class="tribe-block__rsvp__content tribe-block__rsvp__content--inactive card-body"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_content_inactive_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_content_inactive_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_details_title_output( $output ) {
	$output = str_replace( '<header class="tribe-block__rsvp__title">', '<h3 class="tribe-block__rsvp__title card-title">', $output );
	$output = str_replace( '</header>', '</h3>', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_details_title_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_details_title_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_details_description_output( $output ) {
	return str_replace( 'class="tribe-block__rsvp__description"', 'class="tribe-block__rsvp__description text-body-secondary"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_details_description_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_details_description_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_details_availability_output( $output ) {
	$output = str_replace( 'class="tribe-block__rsvp__availability"', 'class="tribe-block__rsvp__availability mb-3"',      $output );
	$output = str_replace( 'class="tribe-block__rsvp__no-stock"',     'class="tribe-block__rsvp__no-stock text-danger"',   $output );
	$output = str_replace( 'class="tribe-block__rsvp__unlimited"',    'class="tribe-block__rsvp__unlimited text-success"', $output );

	$offset = strpos( $output, '<span class="tribe-block__rsvp__quantity">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<span class="tribe-block__rsvp__remaining  text-success">', $offset, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</span>', $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_details_availability_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_details_availability_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_status_going_output( $output ) {
	return str_replace( 'class="tribe-block__rsvp__status-button ', 'class="tribe-block__rsvp__status-button btn btn-primary ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_status_going_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_status_going_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_status_not_going_output( $output ) {
	return str_replace( 'class="tribe-block__rsvp__status-button ', 'class="tribe-block__rsvp__status-button btn btn-secondary ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_status_not_going_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_status_not_going_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_form_form_output( $output ) {
	$output = str_replace( 'name="tribe-rsvp-form"', 'class="card-footer" name="tribe-rsvp-form"', $output );

	if ( is_user_logged_in() || ! tribe( 'tickets.rsvp' )->login_required() ) {
		$offset = strpos( $output, 'name="tribe-rsvp-form"' );

		if ( false !== $offset ) {
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, '<div class="row">', $offset + 1, 0 );
			$output = str_replace( '</form>', '</div></form>', $output );
			$output = str_replace( 'class="tribe-left"', 'class="tribe-left col-12 col-md flex-md-grow-0 flex-md-shrink-1"', $output );
			$output = str_replace( 'class="tribe-right"', 'class="tribe-right col-12 col-md"', $output );
		}
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_form_form_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_form_form_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_form_quantity_output( $output ) {
	$output = str_replace( 'class="tribe-block__rsvp__number-input-inner"', 'class="tribe-block__rsvp__number-input-inner input-group flex-nowrap mb-2"', $output );
	$output = str_replace( 'class="tribe-block__rsvp__number-input-label"', 'class="tribe-block__rsvp__number-input-label d-block fw-bold text-md-center"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_form_quantity_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_form_quantity_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_form_quantity_minus_output( $output ) {
	return str_replace( 'class="tribe-block__rsvp__number-input-button tribe-block__rsvp__number-input-button--minus"', 'class="tribe-block__rsvp__number-input-button tribe-block__rsvp__number-input-button--minus btn btn-light"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_form_quantity_minus_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_form_quantity_minus_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_form_quantity_input_output( $output ) {
	return str_replace( 'class="tribe-tickets-quantity"', 'class="tribe-tickets-quantity form-control"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_form_quantity_input_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_form_quantity_input_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_form_quantity_plus_output( $output ) {
	return str_replace( 'class="tribe-block__rsvp__number-input-button tribe-block__rsvp__number-input-button--plus"', 'class="tribe-block__rsvp__number-input-button tribe-block__rsvp__number-input-button--plus btn btn-light"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_form_quantity_plus_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_form_quantity_plus_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_form_error_output( $output ) {
	return str_replace( 'class="tribe-block__rsvp__message__error"', 'class="tribe-block__rsvp__message__error alert alert-danger"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_form_error_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_form_error_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_form_submit_login_output( $output ) {
	return str_replace( '<a ', '<a class="btn btn-primary" ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_form_submit_login_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_form_submit_login_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_form_name_output( $output ) {
	return str_replace( 'class="tribe-tickets-full-name"', 'class="tribe-tickets-full-name form-control mb-2"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_form_name_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_form_name_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_form_email_output( $output ) {
	return str_replace( 'class="tribe-tickets-email"', 'class="tribe-tickets-email form-control mb-2"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_form_email_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_form_email_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_form_submit_button_output( $output ) {
	return str_replace( 'class="tribe-block__rsvp__submit-button"', 'class="tribe-block__rsvp__submit-button btn btn-primary"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_form_submit_button_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_form_submit_button_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_messages_success_output( $output ) {
	return str_replace( 'class="tribe-block__rsvp__message__success"', 'class="tribe-block__rsvp__message__success alert alert-success mt-3"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_messages_success_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_messages_success_output' );

function enlightenment_tribe_bootstrap_dialog_args( $args ) {
	if ( 'modal' != $args['template'] ) {
		return $args;
	}

	$args['content_wrapper_classes'] .= ' modal-dialog';
	$args['title_classes'][]          = 'modal-title h5';
	$args['close_button_classes']    .= ' btn-close';

	return $args;
}
add_filter( 'tribe_dialog_args', 'enlightenment_tribe_bootstrap_dialog_args' );

function enlightenment_tribe_bootstrap_dialog_html( $output, $args ) {
	if ( 'modal' != $args['template' ]) {
		return $output;
	}

	return str_replace( 'class="tribe-modal-cart tribe-modal__cart tribe-common"', 'class="tribe-modal-cart tribe-modal__cart tribe-common modal-body"', $output );
}
add_filter( 'tribe_dialog_html', 'enlightenment_tribe_bootstrap_dialog_html', 10, 2 );

function enlightenment_tribe_bootstrap_template_v2_tickets_output( $output ) {
	return str_replace( 'class="tribe-tickets__tickets-form tribe-tickets__form"', 'class="tribe-tickets__tickets-form tribe-tickets__form card position-relative"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_title_output( $output ) {
	$offset = strpos( $output, '<h2 class="tribe-common-h4 tribe-common-h--alt tribe-tickets__tickets-title">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="card-header">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</h2>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 5, 0 );
	}

	$output  = str_replace( 'class="tribe-common-h4 tribe-common-h--alt tribe-tickets__tickets-title"', 'class="tribe-common-h4 tribe-common-h--alt tribe-tickets__tickets-title h5 mb-0"', $output );

	$output .= '<div class="card-body">';

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_title_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_title_output' );

function enlightenment_tribe_bootstrap_tickets_card_close_card_body( $output ) {
	$output .= '</div>';

	return $output;
}
add_action( 'tribe_template_entry_point:tickets/v2/tickets:etp-waitlist', 'enlightenment_tribe_bootstrap_tickets_card_close_card_body', 999 );

function enlightenment_tribe_bootstrap_template_v2_modal_cart_output( $output ) {
	$output = str_replace( 'class="tribe-modal-cart tribe-modal__cart tribe-common event-tickets"', 'class="tribe-modal-cart tribe-modal__cart tribe-common event-tickets modal-body"', $output );
	$output = str_replace( 'class="tribe-tickets__tickets-footer card-footer"', 'class="tribe-tickets__tickets-footer__modal"', $output );
	$output = str_replace( 'class="tribe-tickets__tickets-footer"', 'class="tribe-tickets__tickets-footer__modal"', $output );

	return$output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_modal_cart_output', 'enlightenment_tribe_bootstrap_template_v2_modal_cart_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_output( $output, $template_name, $file, $template ) {
	if ( ! $template->get( 'is_modal' ) ) {
		$output = str_replace( 'class="tribe-tickets__tickets-item ', 'class="tribe-tickets__tickets-item card-body ', $output );
	}

	if ( $template->get( 'is_mini' ) ) {
		$offset_a = strpos( $output, '<div class="tribe-tickets__tickets-item-content">' );
		$offset_b = strpos( $output, '<div class="tribe-ticket-quantity">' );
		if ( false !== $offset_a && false !== $offset_b ) {
			$output   = substr_replace( $output, ' ps-3 ms-auto', $offset_b + 33, 0 );
			$offset_b = strpos( $output, '</div>', $offset_b );
			$output   = substr_replace( $output, "\n" . '</div>', $offset_b + 6, 0 );
			$output   = substr_replace( $output, '<div class="col-8 d-flex align-items-center">' . "\n", $offset_a, 0 );
		}
	}

	$offset = strpos( $output, 'class="tribe-tickets__tickets-item ' );
	if ( false !== $offset ) {
		$class   = $template->get( 'is_mini' ) ? 'row gx-3 align-items-center' : 'row align-items-center';

		$offset  = strpos( $output, '>', $offset );
		$output  = substr_replace( $output, "\n" . sprintf( '<div class="%s">', $class ), $offset + 1, 0 );
		$output .= '</div>';
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_content_output( $output, $template_name, $file, $template ) {
	if ( $template->get( 'is_mini' ) ) {
		$output = sprintf( '<div class="tribe-tickets__tickets-item-content">%s</div>', $output );
	} else {
		$output = sprintf( '<div class="col"><div class="row align-items-center">%s</div></div>', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_content_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_content_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_content_title_output( $output, $template_name, $file, $template ) {
	$output = str_replace( 'class="tribe-common-b3 tribe-tickets__tickets-item-content-subtitle"', 'class="tribe-common-b3 tribe-tickets__tickets-item-content-subtitle small"', $output );

	if ( ! $template->get( 'is_mini' ) ) {
		$output = '<div class="col-12 col-md d-flex flex-wrap align-items-baseline">' . "\n" . $output;
		$output = str_replace( 'class="tribe-common-h7 tribe-common-h6--min-medium tribe-tickets__tickets-item-content-title ', 'class="tribe-common-h7 tribe-common-h6--min-medium tribe-tickets__tickets-item-content-title h3 card-title ', $output );
		$output = str_replace( 'class="tribe-common-h7 tribe-common-h6--min-medium tribe-tickets__tickets-item-content-title"', 'class="tribe-common-h7 tribe-common-h6--min-medium tribe-tickets__tickets-item-content-title h3 card-title"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_content_title_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_content_title_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_content_description_output( $output, $template_name, $file, $template ) {
	$output  = str_replace( 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__tickets-item-details-content ', 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__tickets-item-details-content card-text text-body-secondary w-100 ', $output );
	$output  = str_replace( 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__tickets-item-details-content"', 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__tickets-item-details-content card-text text-body-secondary w-100"', $output );

	if ( ! doing_filter( 'tribe_template_before_include:tickets/v2/tickets/submit/button' ) ) {
		$output  = str_replace( 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__tickets-item-details-content card-text text-body-secondary w-100 ', 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__tickets-item-details-content card-text text-body-secondary w-100 d-none d-md-block ', $output );
		$output  = str_replace( 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__tickets-item-details-content card-text text-body-secondary w-100"', 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__tickets-item-details-content card-text text-body-secondary w-100 d-none d-md-block"', $output );
	}

	if ( ! $template->get( 'is_mini' ) ) {
		$output .= "\n" . '</div>';
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_content_description_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_content_description_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_description_toggle_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__tickets-item-details-summary"', 'class="tribe-tickets__tickets-item-details-summary ms-2 d-none d-md-block"', $output );
	$output = str_replace( 'class="tribe-common-b3 tribe-tickets__tickets-item-details-summary-button--more"', 'class="tribe-common-b3 tribe-tickets__tickets-item-details-summary-button--more btn btn-link btn-sm"', $output );
	$output = str_replace( 'class="tribe-common-b3 tribe-tickets__tickets-item-details-summary-button--less"', 'class="tribe-common-b3 tribe-tickets__tickets-item-details-summary-button--less btn btn-link btn-sm"', $output );
	$output = str_replace( 'class="screen-reader-text tribe-common-a11y-visual-hide"', 'class="screen-reader-text tribe-common-a11y-visual-hide visually-hidden"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_content_description_toggle_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_description_toggle_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_extra_description_toggle_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_description_toggle_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_extra_output( $output, $template_name, $file, $template ) {
	$class = $template->get( 'is_mini' ) ? 'mt-1' : 'col-12 col-md flex-md-grow-0 flex-md-shrink-1 mt-1 mt-md-0';

	$output = str_replace( 'class="tribe-tickets__tickets-item-extra ', sprintf( 'class="tribe-tickets__tickets-item-extra %s ', $class ), $output );
	$output = str_replace( 'class="tribe-tickets__tickets-item-extra"', sprintf( 'class="tribe-tickets__tickets-item-extra %s"', $class ), $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_extra_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_extra_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_extra_price_output( $output, $template_name, $file, $template ) {
	$class = $template->get( 'is_mini' ) ? 'fw-bold' : 'fw-bold text-md-end';

	$output = str_replace( 'class="tribe-common-b2 tribe-common-b1--min-medium tribe-tickets__tickets-item-extra-price"', sprintf( 'class="tribe-common-b2 tribe-common-b1--min-medium tribe-tickets__tickets-item-extra-price %s"', $class ), $output );
	$output = str_replace( 'class="tribe-formatted-currency-wrap tribe-currency-prefix"', 'class="tribe-formatted-currency-wrap tribe-currency-prefix d-inline-flex"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_extra_price_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_extra_price_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_extra_available_output( $output ) {
	return str_replace( 'class="tribe-common-b3 tribe-tickets__tickets-item-extra-available">', 'class="tribe-common-b3 tribe-tickets__tickets-item-extra-available small text-body-secondary text-nowrap">', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_extra_available_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_extra_available_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_output( $output ) {
	return str_replace( 'class="tribe-common-h4 tribe-tickets__tickets-item-quantity"', 'class="tribe-common-h4 tribe-tickets__tickets-item-quantity col flex-grow-0 flex-shrink-1"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_quantity_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_remove_output( $output ) {
	$output = sprintf( '<div class="tribe-tickets__tickets-item-quantity-remove input-group">%s</div>', $output );
	$output = sprintf( '<div class="input-group flex-nowrap">%s', $output );

	if ( doing_filter( 'tribe_template_before_include:tickets/v2/tickets/submit/button' ) ) {
		$output = str_replace( 'class="tribe-tickets__tickets-item-quantity-remove input-group"', 'class="tribe-tickets__tickets-item-quantity-remove input-group d-none d-md-flex"', $output );
	}

	$output = str_replace( 'class="tribe-tickets__tickets-item-quantity-remove"', 'class="btn btn-light"', $output );
	$output = str_replace( 'class="screen-reader-text tribe-common-a11y-visual-hide"', 'class="screen-reader-text tribe-common-a11y-visual-hide visually-hidden"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_quantity_remove_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_remove_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_number_output( $output ) {
	return str_replace( 'class="tribe-common-h3 tribe-common-h4--min-medium tribe-tickets__tickets-item-quantity-number-input"', 'class="tribe-common-h3 tribe-common-h4--min-medium tribe-tickets__tickets-item-quantity-number-input form-control rounded-0 w-auto"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_quantity_number_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_number_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_add_output( $output ) {
	$output  = sprintf( '<div class="tribe-tickets__tickets-item-quantity-add input-group">%s</div>', $output );

	if ( doing_filter( 'tribe_template_before_include:tickets/v2/tickets/submit/button' ) ) {
		$output = str_replace( 'class="tribe-tickets__tickets-item-quantity-add input-group"', 'class="tribe-tickets__tickets-item-quantity-add input-group d-none d-md-flex"', $output );
	}

	$output  = str_replace( 'class="tribe-tickets__tickets-item-quantity-add"', 'class="btn btn-light"', $output );
	$output  = str_replace( 'class="screen-reader-text tribe-common-a11y-visual-hide"', 'class="screen-reader-text tribe-common-a11y-visual-hide visually-hidden"', $output );
	$output .= '</div>';

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_quantity_add_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_add_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_unavailable_output( $output ) {
	return str_replace( 'class="tribe-common-b2 tribe-common-b2--bold tribe-tickets__tickets-item-quantity-unavailable"', 'class="tribe-common-b2 tribe-common-b2--bold tribe-tickets__tickets-item-quantity-unavailable text-danger text-nowrap"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_quantity_unavailable_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_unavailable_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_mini_output( $output ) {
	return str_replace( 'class="tribe-ticket-quantity"', 'class="tribe-ticket-quantity col flex-grow-0 flex-shrink-1"', $output );
}
// add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_quantity_mini_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_quantity_mini_output' );

function enlightenment_tribe_bootstrap_template_v2_modal_item_total_output( $output, $template_name, $file, $template ) {
	if ( $template->get( 'is_modal' ) ) {
		$output = str_replace( 'class="tribe-common-b2 tribe-tickets__tickets-item-total-wrap"', 'class="tribe-common-b2 tribe-tickets__tickets-item-total-wrap col flex-grow-0 flex-shrink-1"', $output );
	} else {
		$output = str_replace( 'class="tribe-common-b2 tribe-tickets__tickets-item-total-wrap"', 'class="tribe-common-b2 tribe-tickets__tickets-item-total-wrap col-4"', $output );
	}

	$output = str_replace( 'class="tribe-tickets__tickets-item-total"', 'class="tribe-tickets__tickets-item-total d-block text-end"', $output );
	$output = str_replace( 'class="tribe-formatted-currency-wrap tribe-currency-prefix"', 'class="tribe-formatted-currency-wrap tribe-currency-prefix d-inline-flex"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_modal_item_total_output', 'enlightenment_tribe_bootstrap_template_v2_modal_item_total_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_opt_out_output( $output ) {
	$output = str_replace( 'class="tribe-common-form-control-checkbox tribe-tickets-attendees-list-optout--wrapper"', 'class="tribe-common-form-control-checkbox tribe-tickets-attendees-list-optout--wrapper col-12"', $output );

	$offset = strpos( $output, '<label' );
	if ( false !== $offset ) {
		$end    = strpos( $output, '>', $offset ) + 1;
		$length = $end - $offset;
		$label  = substr( $output, $offset, $length );
		$label  = str_replace( 'class="tribe-common-form-control-checkbox__label"', 'class="tribe-common-form-control-checkbox__label form-check-label"', $label );
		$output = substr_replace( $output, '<div class="form-check">', $offset, $length );

		$offset = strpos( $output, 'class="tribe-common-form-control-checkbox__input tribe-tickets__tickets-item-optout"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 83, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . $label, $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_opt_out_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_opt_out_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_footer_output( $output, $template_name, $file, $template ) {
	$output = str_replace( 'class="tribe-tickets__tickets-footer"', 'class="tribe-tickets__tickets-footer card-footer"', $output );
	$output = str_replace( 'class="tribe-tickets__tickets-footer__modal"', 'class="tribe-tickets__tickets-footer mt-3"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_footer_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_footer_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_tickets_footer_return_to_cart_output( $output, $template_name, $file, $template ) {
	if ( ! empty( $output ) ) {
		if ( $template->get( 'is_mini' ) ) {
			$output = sprintf( '<div class="col-8 d-flex align-items-center">%s%s', "\n", $output );
			$output = str_replace( 'class="tribe-common-b2 tribe-tickets__tickets-footer-back-link"', 'class="tribe-common-b2 tribe-tickets__tickets-footer-back-link btn btn-secondary text-nowrap"', $output );
		}
	}

	$class  = $template->get( 'is_modal' ) ? 'row align-items-center justify-content-end' : 'row gx-3 align-items-center';
	$output = sprintf( '<div class="%s">%s%s', $class, "\n", $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_footer_return_to_cart_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_footer_return_to_cart_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_tickets_footer_quantity_output( $output, $template_name, $file, $template ) {
	if ( $template->get( 'is_mini' ) ) {
		$output  = str_replace( 'class="tribe-common-b2 tribe-tickets__tickets-footer-quantity"', 'class="tribe-common-b2 tribe-tickets__tickets-footer-quantity ps-3 ms-auto"', $output );
		$output  = str_replace( 'class="tribe-tickets__tickets-footer-quantity-label"', 'class="tribe-tickets__tickets-footer-quantity-label screen-reader-text visually-hidden"', $output );

		$output .= "\n" . '</div>'; // .col-8
	} else {
		$output = str_replace( 'class="tribe-common-b2 tribe-tickets__tickets-footer-quantity"', 'class="tribe-common-b2 tribe-tickets__tickets-footer-quantity col flex-grow-0 flex-shrink-1 text-nowrap"', $output );
		$output = str_replace( 'class="tribe-tickets__tickets-footer-quantity-label"', 'class="tribe-tickets__tickets-footer-quantity-label text-body-secondary"', $output );
	}

	$output = str_replace( 'class="tribe-tickets__tickets-footer-quantity-number"', 'class="tribe-tickets__tickets-footer-quantity-number fw-bold"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_footer_quantity_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_footer_quantity_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_tickets_footer_total_output( $output, $template_name, $file, $template ) {
	if ( $template->get( 'is_mini' ) ) {
		$output = str_replace( 'class="tribe-common-b2 tribe-tickets__tickets-footer-total"', 'class="tribe-common-b2 tribe-tickets__tickets-footer-total col-4"', $output );
		$output = str_replace( 'class="tribe-tickets__tickets-footer-total-label"', 'class="tribe-tickets__tickets-footer-total-label screen-reader-text visually-hidden"', $output );
		$output = str_replace( 'class="tribe-tickets__tickets-footer-total-wrap"', 'class="tribe-tickets__tickets-footer-total-wrap fw-bold d-block text-end"', $output );
	} else {
		$output = str_replace( 'class="tribe-common-b2 tribe-tickets__tickets-footer-total"', 'class="tribe-common-b2 tribe-tickets__tickets-footer-total col flex-grow-0 flex-shrink-1 text-nowrap"', $output );
		$output = str_replace( 'class="tribe-tickets__tickets-footer-total-label"', 'class="tribe-tickets__tickets-footer-total-label text-body-secondary"', $output );
		$output = str_replace( 'class="tribe-tickets__tickets-footer-total-wrap"', 'class="tribe-tickets__tickets-footer-total-wrap fw-bold"', $output );
	}

	$output = str_replace( 'class="tribe-formatted-currency-wrap tribe-currency-prefix"', 'class="tribe-formatted-currency-wrap tribe-currency-prefix d-inline-flex"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_footer_total_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_footer_total_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_tickets_submit_output( $output ) {
	if ( ! empty( $output ) ) {
		$output = sprintf( '<div class="col flex-grow-0 flex-shrink-1 ms-auto">%s</div>', $output );
	}

	$output .= "\n" . '</div>'; // .row

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_submit_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_submit_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_submit_must_login_output( $output ) {
	return str_replace( 'class="tribe-common-c-btn tribe-common-c-btn--small"', 'class="tribe-common-c-btn tribe-common-c-btn--small btn btn-primary text-nowrap"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_submit_must_login_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_submit_must_login_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_submit_button_output( $output ) {
	return str_replace( 'class="tribe-common-c-btn tribe-common-c-btn--small tribe-tickets__tickets-buy"', 'class="tribe-common-c-btn tribe-common-c-btn--small tribe-tickets__tickets-buy btn btn-primary text-nowrap"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_submit_button_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_submit_button_output' );

function enlightenment_tribe_bootstrap_template_v2_tickets_item_inactive_output( $output ) {
	return str_replace( 'class="tribe-tickets__tickets-item-content tribe-tickets__tickets-item-content--inactive"', 'class="tribe-tickets__tickets-item-content tribe-tickets__tickets-item-content--inactive alert alert-info"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_inactive_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_inactive_output' );
add_filter( 'enlightenment_tribe_filter_template_v2_tickets_item_content_inactive_output', 'enlightenment_tribe_bootstrap_template_v2_tickets_item_inactive_output' );

function enlightenment_tribe_bootstrap_template_v2_modal_attendee_registration_output( $output ) {
	return str_replace( 'class="tribe-tickets__attendee-tickets-container"', 'class="tribe-tickets__attendee-tickets-container modal-body pt-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_modal_attendee_registration_output', 'enlightenment_tribe_bootstrap_template_v2_modal_attendee_registration_output' );

function enlightenment_tribe_bootstrap_template_v2_attendee_registration_content_output( $output, $template_name, $file, $template ) {
	ob_start();
	$template->template( 'v2/attendee-registration/content/notice' );
	$notice = ob_get_clean();
	$output = str_replace( $notice, '', $output );

	$offset = strpos( $output, '<div class="tribe-tickets__registration-content">' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, "\n" . $notice, $offset + 49, 0 );
	}

	ob_start();
	$template->template( 'v2/attendee-registration/footer' );
	$footer = ob_get_clean();
	$output = str_replace( $footer, '', $output );

	$offset = strrpos( $output, '</form>' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, '</div>', -( strlen( $output ) - $offset ) );
		$offset = strrpos( $output, '</div>', -( strlen( $output ) - $offset + 1 ) );
		$output = substr_replace( $output, $footer . "\n", $offset, 0 );
	}

	$output = str_replace( 'class="tribe-common-h8 tribe-common-h--alt tribe-tickets__registration-actions"', 'class="tribe-common-h8 tribe-common-h--alt tribe-tickets__registration-actions mb-3"', $output );
	$output = str_replace( 'class="tribe-tickets__registration-grid"', 'class="tribe-tickets__registration-grid row"', $output );
	$output = str_replace( 'class="tribe-tickets__registration-content"', 'class="tribe-tickets__registration-content col-lg-8 mt-3 mt-lg-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_attendee_registration_content_output', 'enlightenment_tribe_bootstrap_template_v2_attendee_registration_content_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_v2_attendee_registration_button_back_to_cart_output( $output ) {
	return str_replace( 'class="tribe-tickets__registration-back-to-cart"', 'class="tribe-tickets__registration-back-to-cart btn btn-secondary"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_attendee_registration_button_back_to_cart_output', 'enlightenment_tribe_bootstrap_template_v2_attendee_registration_button_back_to_cart_output' );

add_filter( 'enlightenment_tribe_filter_template_v2_attendee_registration_content_title_output', '__return_false' );

function enlightenment_tribe_bootstrap_template_v2_attendee_registration_mini_cart_output( $output ) {
	$output = str_replace( 'class="tribe-common event-tickets tribe-tickets__mini-cart"', 'class="tribe-common event-tickets tribe-tickets__mini-cart card"', $output );
	$output = sprintf( '<div class="col-lg-4 order-lg-1">%s</div>', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_attendee_registration_mini_cart_output', 'enlightenment_tribe_bootstrap_template_v2_attendee_registration_mini_cart_output' );

function enlightenment_tribe_bootstrap_template_v2_attendee_registration_mini_cart_title_output( $output ) {
	return str_replace( 'class="tribe-common-h6 tribe-common-h5--min-medium tribe-common-h--alt tribe-tickets__mini-cart-title"', 'class="tribe-common-h6 tribe-common-h5--min-medium tribe-common-h--alt tribe-tickets__mini-cart-title card-header h5"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_attendee_registration_mini_cart_title_output', 'enlightenment_tribe_bootstrap_template_v2_attendee_registration_mini_cart_title_output' );

function enlightenment_tribe_bootstrap_template_v2_attendee_registration_content_event_output( $output ) {
	return str_replace( 'class="tribe-tickets__registration-event"', 'class="tribe-tickets__registration-event mb-3"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_attendee_registration_content_event_output', 'enlightenment_tribe_bootstrap_template_v2_attendee_registration_content_event_output' );

function enlightenment_tribe_bootstrap_template_v2_attendee_registration_button_submit_output( $output ) {
	return str_replace( 'class="tribe-common-c-btn tribe-common-c-btn--small tribe-tickets__registration-submit"', 'class="tribe-common-c-btn tribe-common-c-btn--small tribe-tickets__registration-submit btn btn-primary btn-lg"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_attendee_registration_button_submit_output', 'enlightenment_tribe_bootstrap_template_v2_attendee_registration_button_submit_output' );

function enlightenment_tribe_bootstrap_template_v2_attendee_registration_content_attendees_error_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__attendee-tickets-error tribe-tickets__attendee-tickets-error--required"', 'class="tribe-tickets__attendee-tickets-error tribe-tickets__attendee-tickets-error--required alert alert-danger"', $output );
	$output = str_replace( 'class="tribe-tickets__attendee-tickets-error tribe-tickets__attendee-tickets-error--ajax"', 'class="tribe-tickets__attendee-tickets-error tribe-tickets__attendee-tickets-error--ajax alert alert-danger"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_attendee_registration_content_attendees_error_output', 'enlightenment_tribe_bootstrap_template_v2_attendee_registration_content_attendees_error_output' );

function enlightenment_tribe_bootstrap_template_v2_modal_attendee_registration_title_output( $output ) {
	return str_replace( 'class="tribe-common-h3 tribe-common-h4--min-medium tribe-common-h--alt tribe-tickets__attendee-tickets-title"', 'class="tribe-common-h3 tribe-common-h4--min-medium tribe-common-h--alt tribe-tickets__attendee-tickets-title modal-body pt-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_modal_attendee_registration_title_output', 'enlightenment_tribe_bootstrap_template_v2_modal_attendee_registration_title_output' );

function enlightenment_tribe_bootstrap_template_v2_modal_attendee_registration_notice_non_ar_output( $output ) {
	return str_replace( 'class="tribe-tickets__notice tribe-tickets__notice--non-ar ', 'class="tribe-tickets__notice tribe-tickets__notice--non-ar modal-body pt-0 ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_modal_attendee_registration_notice_non_ar_output', 'enlightenment_tribe_bootstrap_template_v2_modal_attendee_registration_notice_non_ar_output' );

function enlightenment_tribe_bootstrap_template_v2_attendee_registration_content_attendees_fields_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__form tribe-tickets__attendee-tickets-item ', 'class="tribe-tickets__form tribe-tickets__attendee-tickets-item card ', $output );
	$output = str_replace( 'class="tribe-tickets__form tribe-tickets__attendee-tickets-item"', 'class="tribe-tickets__form tribe-tickets__attendee-tickets-item card"', $output );

	$offset = strpos( $output, '<div class="tribe-tickets__attendee-tickets-item-header">' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, ' card-header d-flex justify-content-between', $offset + 55, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= "\n" . '</div>';
	} else {
		$offset = strpos( $output, '<h4 class="tribe-common-b1 tribe-common-b1--bold tribe-tickets__attendee-tickets-item-title">' );
		if ( false !== $offset ) {
			$output  = substr_replace( $output, '<div class="card-header d-flex justify-content-between">' . "\n", $offset, 0 );
			$offset  = strpos( $output, '</h4>', $offset ) + 5;

			$offset_a = strpos( $output, '<button class="tribe-common-b2 tribe-tickets__attendee-tickets-item-remove', $offset );
			if ( false !== $offset_a ) {
				$offset = strpos( $output, '</button>', $offset_a ) + 9;
			}

			$output  = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="card-body">', $offset, 0 );
			$output .= "\n" . '</div>';
		}
	}

	$output = str_replace( 'class="tribe-common-b1 tribe-common-b1--bold tribe-tickets__attendee-tickets-item-title"', 'class="tribe-common-b1 tribe-common-b1--bold tribe-tickets__attendee-tickets-item-title  mb-0"', $output );

	$offset = strrpos( $output, 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-tickets__form-field mb-3 ' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, '', $offset + 76, 5 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_attendee_registration_content_attendees_fields_output', 'enlightenment_tribe_bootstrap_template_v2_attendee_registration_content_attendees_fields_output' );

function enlightenment_tribe_bootstrap_template_v2_attendee_registration_content_attendees_remove_button_output( $output ) {
	$offset = strpos( $output, '<button class="tribe-common-b2 tribe-tickets__attendee-tickets-item-remove" type="button">' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, "\n" . '<span class="screen-reader-text visually-hidden">', $offset + 90, 0 );
		$output  = substr_replace( $output, ' btn-close', $offset + 74, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output  = substr_replace( $output, '</span>' . "\n", $offset, 0 );
	}

	$start = strpos( $output, '<svg ' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</svg>', $start ) + 6;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_attendee_registration_content_attendees_remove_button_output', 'enlightenment_tribe_bootstrap_template_v2_attendee_registration_content_attendees_remove_button_output' );

function enlightenment_tribe_bootstrap_template_v2_iac_attendee_registration_email_disclaimer_output( $output ) {
	$class  = enlightenment_tribe_is_ar_page() ? 'alert alert-info' : 'alert alert-info mb-0';

	$offset = strpos( $output, '<div class="tribe-tickets__iac-email-disclaimer tribe-common-b2">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, "\n" . sprintf( '<div class="%s">', $class ), $offset + 65, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	if ( ! enlightenment_tribe_is_ar_page() ) {
		$output = str_replace( 'class="tribe-tickets__iac-email-disclaimer tribe-common-b2"', 'class="tribe-tickets__iac-email-disclaimer tribe-common-b2 modal-body pt-0"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_iac_attendee_registration_email_disclaimer_output', 'enlightenment_tribe_bootstrap_template_v2_iac_attendee_registration_email_disclaimer_output' );

function enlightenment_tribe_bootstrap_template_v2_modal_attendee_registration_footer_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__attendee-tickets-footer"', 'class="tribe-tickets__attendee-tickets-footer modal-footer"', $output );
	$output = str_replace( 'class="tribe-common-c-btn-link ', 'class="tribe-common-c-btn-link btn btn-secondary ', $output );
	$output = str_replace( 'class="tribe-tickets__attendee-tickets-footer-divider"', 'class="tribe-tickets__attendee-tickets-footer-divider text-body-secondary"', $output );
	$output = str_replace( 'class="tribe-common-c-btn tribe-common-c-btn--small tribe-tickets__attendee-tickets-submit ', 'class="tribe-common-c-btn tribe-common-c-btn--small tribe-tickets__attendee-tickets-submit btn btn-primary ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_modal_attendee_registration_footer_output', 'enlightenment_tribe_bootstrap_template_v2_modal_attendee_registration_footer_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_output( $output ) {
	$output = str_replace( 'class="wp-block-tribe-tickets tribe-block tribe-tickets tribe-common"', 'class="wp-block-tribe-tickets tribe-block tribe-tickets tribe-common card"', $output );
	$output = str_replace( 'class="tribe-common-h4 tribe-common-h--alt tribe-tickets__title"', 'class="tribe-common-h4 tribe-common-h--alt tribe-tickets__title card-header"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_output' );

function enlightenment_tribe_bootstrap_tickets_unavailable_message( $output ) {
	return str_replace( 'class="tickets-unavailable"', 'class="tickets-unavailable alert alert-info mt-3 mb-0"', $output );
}
add_filter( 'tribe_tickets_unavailable_message', 'enlightenment_tribe_bootstrap_tickets_unavailable_message' );

function enlightenment_tribe_bootstrap_template_components_notice_output( $output ) {
	$offset = strpos( $output, 'class="tribe-tickets__notice tribe-tickets__notice--error tribe-tickets__validation-notice"' );
	if ( false !== $offset ) {
		$offset_a = strpos( $output, 'class="tribe-common-b2 tribe-tickets-notice__content tribe-common-b3"', $offset );
		if ( false !== $offset_a ) {
			$output = substr_replace( $output, ' alert alert-danger mb-0', $offset_a + 68, 0 );
		}

		$offset_a = strpos( $output, 'class="tribe-common-b2 tribe-tickets-notice__content"', $offset );
		if ( false !== $offset_a ) {
			$output = substr_replace( $output, ' alert alert-danger', $offset_a + 52, 0 );
		}
	}

	$output = str_replace( 'class="tribe-tickets__notice tribe-tickets__notice--barred tribe-tickets__notice--barred-left"', 'class="tribe-tickets__notice tribe-tickets__notice--barred tribe-tickets__notice--barred-left d-none"', $output );

	if ( ! enlightenment_tribe_is_ar_page() ) {
		$output = str_replace( 'class="tribe-tickets__notice tribe-tickets__notice--error tribe-tickets__validation-notice"', 'class="tribe-tickets__notice tribe-tickets__notice--error tribe-tickets__validation-notice modal-body pt-0"', $output );
		$output = str_replace( 'class="tribe-tickets__notice tribe-tickets__notice--non-ar tribe-common-a11y-hidden"', 'class="tribe-tickets__notice tribe-tickets__notice--non-ar tribe-common-a11y-hidden modal-body pt-0"', $output );
	}

	$output = str_replace( 'class="tribe-tickets__notice tribe-tickets__notice--non-ar"', 'class="tribe-tickets__notice tribe-tickets__notice--non-ar modal-body pt-0"', $output );
	$output = str_replace( 'class="tribe-common-b2 tribe-tickets-notice__content tribe-common-b3"', 'class="tribe-common-b2 tribe-tickets-notice__content tribe-common-b3 alert alert-info mb-0"', $output );
	$output = str_replace( 'class="tribe-common-b2 tribe-tickets-notice__content"', 'class="tribe-common-b2 tribe-tickets-notice__content alert alert-info"', $output );
	$output = str_replace( 'class="tribe-events-calendar-day__event-details"', 'class="tribe-events-calendar-day__event-details col-12 col-lg"', $output );

	$output = str_replace( 'class="tribe-tickets__notice tribe-tickets__notice--error tribe-tickets__commerce-checkout-notice"', 'class="tribe-tickets__notice tribe-tickets__notice--error tribe-tickets__commerce-checkout-notice alert alert-danger"', $output );
	$output = str_replace( 'class="tribe-tickets__notice tribe-tickets__notice--error tribe-tickets__commerce-checkout-footer-notice-error--no-gateway"', 'class="tribe-tickets__notice tribe-tickets__notice--error tribe-tickets__commerce-checkout-footer-notice-error--no-gateway alert alert-danger"', $output );
	$output = str_replace( 'class="tribe-common-h7 tribe-tickets-notice__title"', 'class="tribe-common-h7 tribe-tickets-notice__title alert-heading"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_notice_output', 'enlightenment_tribe_bootstrap_template_components_notice_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_item_output( $output, $template_name, $file, $template ) {
	if ( ! $template->get( 'is_modal' ) ) {
		$output = str_replace( 'class="tribe-tickets__item ', 'class="tribe-tickets__item card-body ', $output );
	}

	$offset = strpos( $output, 'class="tribe-tickets__item ' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '>', $offset );
		$output  = substr_replace( $output, '<div class="row align-items-center">', $offset + 1, 0 );
		$output .= '</div>';
	}

	$output = str_replace( 'class="tribe-ticket-quantity"', 'class="tribe-ticket-quantity col flex-grow-0 flex-shrink-1"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_item_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_item_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_blocks_tickets_content_output( $output, $template_name, $file, $template ) {
	return sprintf( '<div class="col"><div class="row align-items-center">%s</div></div>', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_content_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_content_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_modal_item_remove_output( $output ) {
	return str_replace( 'class="tribe-tickets__item__remove__wrap"', 'class="tribe-tickets__item__remove__wrap col flex-grow-0 flex-shrink-1 d-none d-md-block"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_modal_item_remove_output', 'enlightenment_tribe_bootstrap_template_modal_item_remove_output' );

function enlightenment_tribe_bootstrap_template_v2_modal_item_remove_output( $output ) {
	return str_replace( 'class="tribe-tickets__tickets-item-remove-wrap"', 'class="tribe-tickets__tickets-item-remove-wrap col flex-grow-0 flex-shrink-1 d-none d-md-block"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_modal_item_remove_output', 'enlightenment_tribe_bootstrap_template_v2_modal_item_remove_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_content_title_output( $output ) {
	$offset = strpos( $output, '<div ' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<h3 ', $offset, 5 );

		$offset = strpos( $output, 'class="tribe-common-b3 ' );
		if ( false !== $offset ) {
			$offset = strpos( $output, '</div>', $offset );
			$offset++;
		}

		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</h3>', $offset, 6 );
	}

	$output = str_replace( 'class="tribe-common-h7 tribe-common-h6--min-medium tribe-tickets__item__content__title"', 'class="tribe-common-h7 tribe-common-h6--min-medium tribe-tickets__item__content__title card-title"', $output );

	$output = sprintf( '<div class="col-12 col-md d-flex flex-wrap align-items-baseline">%s', $output );

	if ( tribe( 'tickets.editor.template' )->get( 'is_mini' ) ) {
		$output .= '</div>';
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_content_title_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_content_title_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_content_description_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__item__details__summary"', 'class="tribe-tickets__item__details__summary d-none"', $output );
	$output = str_replace( 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__item__details__content"', 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__item__details__content card-text text-body-secondary w-100 d-none d-md-block"', $output );

	if ( ! tribe( 'tickets.editor.template' )->get( 'is_mini' ) ) {
		$output .= '</div>';
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_content_description_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_content_description_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_extra_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__item__extra"', 'class="tribe-tickets__item__extra col-12 col-md flex-md-grow-0 flex-md-shrink-1 mt-1 mt-md-0"', $output );
	$output = str_replace( 'class="tribe-tickets__item__details__summary"', 'class="tribe-tickets__item__details__summary d-none"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_extra_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_extra_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_extra_price_output( $output ) {
	$output = str_replace( 'class="tribe-common-b2 tribe-common-b1--min-medium tribe-tickets__item__extra__price"', 'class="tribe-common-b2 tribe-common-b1--min-medium tribe-tickets__item__extra__price fw-bold text-md-end"', $output );
	$output = str_replace( 'class="tribe-formatted-currency-wrap tribe-currency-prefix"', 'class="tribe-formatted-currency-wrap tribe-currency-prefix d-inline-flex"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_extra_price_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_extra_price_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_extra_available_output( $output ) {
	$output = str_replace( 'class="tribe-common-b2 tribe-common-b1--min-medium tribe-tickets__item__extra__price"', 'class="tribe-common-b2 tribe-common-b1--min-medium tribe-tickets__item__extra__price fw-bold"', $output );
	$output = str_replace( 'class="tribe-common-b3 tribe-tickets__item__extra__available"', 'class="tribe-common-b3 tribe-tickets__item__extra__available small text-body-secondary text-nowrap"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_extra_available_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_extra_available_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_quantity_output( $output ) {
	return str_replace( 'class="tribe-common-h4 tribe-tickets__item__quantity"', 'class="tribe-common-h4 tribe-tickets__item__quantity col flex-grow-0 flex-shrink-1 d-flex align-items-center"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_quantity_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_quantity_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_quantity_remove_output( $output ) {
	$output = sprintf( '<div class="input-group flex-nowrap">%s', $output );

	$output = str_replace( 'class="tribe-tickets__item__quantity__remove"', 'class="tribe-tickets__item__quantity__remove btn btn-light"', $output );
	$output = str_replace( 'class="screen-reader-text tribe-common-a11y-visual-hide"', 'class="screen-reader-text tribe-common-a11y-visual-hide visually-hidden"', $output );

	if ( doing_filter( 'tribe_events_tickets_attendee_registration_modal_content' ) ) {
		$output = str_replace( 'class="tribe-tickets__item__quantity__remove ', 'class="tribe-tickets__item__quantity__remove d-none d-md-flex"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_quantity_remove_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_quantity_remove_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_quantity_number_output( $output ) {
	return str_replace( 'class="tribe-common-h3 tribe-common-h4--min-medium tribe-tickets-quantity"', 'class="tribe-common-h3 tribe-common-h4--min-medium tribe-tickets-quantity form-control rounded-0 w-auto"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_quantity_number_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_quantity_number_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_quantity_add_output( $output ) {
	$output = sprintf( '%s</div>', $output );

	$output = str_replace( 'class="tribe-tickets__item__quantity__add"', 'class="tribe-tickets__item__quantity__add btn btn-light"', $output );
	$output = str_replace( 'class="screen-reader-text tribe-common-a11y-visual-hide"', 'class="screen-reader-text tribe-common-a11y-visual-hide visually-hidden"', $output );

	if ( doing_filter( 'tribe_events_tickets_attendee_registration_modal_content' ) ) {
		$output = str_replace( 'class="tribe-tickets__item__quantity__add ', 'class="tribe-tickets__item__quantity__add d-none d-md-flex"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_quantity_add_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_quantity_add_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_quantity_unavailable_output( $output ) {
	return str_replace( 'class="tribe-common-b2 tribe-common-b2--bold tribe-tickets__item__quantity__unavailable"', 'class="tribe-common-b2 tribe-common-b2--bold tribe-tickets__item__quantity__unavailable text-body-secondary text-nowrap"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_quantity_unavailable_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_quantity_unavailable_output' );

function enlightenment_tribe_bootstrap_template_modal_item_total_output( $output ) {
	$output = str_replace( 'class="tribe-common-b2 tribe-tickets__item__total__wrap"', 'class="tribe-common-b2 tribe-tickets__item__total__wrap col flex-grow-0 flex-shrink-1"', $output );
	$output = str_replace( 'class="tribe-tickets__item__total"', 'class="tribe-tickets__item__total text-end"', $output );
	$output = str_replace( 'class="tribe-formatted-currency-wrap tribe-currency-prefix"', 'class="tribe-formatted-currency-wrap tribe-currency-prefix d-inline-flex"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_modal_item_total_output', 'enlightenment_tribe_bootstrap_template_modal_item_total_output' );

function enlightenment_tribe_bootstrap_template_modal_output( $output ) {
	return str_replace( 'class="tribe-tickets__footer card-footer"', 'class="tribe-tickets__footer"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_modal_output', 'enlightenment_tribe_bootstrap_template_modal_output', 999 );

function enlightenment_tribe_bootstrap_events_tickets_attendee_registration_modal_content( $output ) {
	$output = str_replace( 'class="tribe-tickets__item__attendee__fields__footer"', 'class="tribe-tickets__item__attendee__fields__footer modal-footer"', $output );
	$output = str_replace( 'class="tribe-common-h3 tribe-common-h4--min-medium tribe-common-h--alt tribe-tickets__item__attendee__fields__title"', 'class="tribe-common-h3 tribe-common-h4--min-medium tribe-common-h--alt tribe-tickets__item__attendee__fields__title modal-body pb-0"', $output );
	$output = str_replace( 'class="tribe-tickets__item__attendee__fields__container"', 'class="tribe-tickets__item__attendee__fields__container modal-body pt-0"', $output );
	$output = str_replace( 'class="tribe-common-c-btn-link ', 'class="tribe-common-c-btn-link btn btn-secondary ', $output );
	$output = str_replace( 'class="tribe-block__tickets__item__attendee__fields__footer__divider"', 'class="tribe-block__tickets__item__attendee__fields__footer__divider text-body-secondary"', $output );
	$output = str_replace( 'class="tribe-common-c-btn ', 'class="btn btn-primary tribe-common-c-btn ', $output );

	return $output;
}
add_filter( 'tribe_events_tickets_attendee_registration_modal_content', 'enlightenment_tribe_bootstrap_events_tickets_attendee_registration_modal_content', 12 );

function enlightenment_tribe_bootstrap_checkout_links( $output ) {
	$output = str_replace( 'class="tribe-checkout-backlinks"', 'class="tribe-checkout-backlinks d-flex gap-2 mb-2"', $output );
	$output = str_replace( 'class="tribe-checkout-backlink"', 'class="tribe-checkout-backlink btn btn-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_checkout_links', 'enlightenment_tribe_bootstrap_checkout_links' );

function enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_output( $output ) {
	$output = str_replace( 'class="tribe-ticket ', 'class="tribe-ticket card ', $output );

	$offset = strpos( $output, '<h4 class="tribe-common-b1 tribe-common-b1--bold tribe-tickets__attendee__title">' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '</h4>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 5, 0 );
		$output .= "\n" . '</div>';
	}

	$output = str_replace( 'class="tribe-common-b1 tribe-common-b1--bold tribe-tickets__attendee__title"', 'class="tribe-common-b1 tribe-common-b1--bold tribe-tickets__attendee__title card-header"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_registration_js_attendees_fields_output', 'enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_output' );

function enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_text_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-field ', 'class="tribe-common-b1 tribe-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-common-form-control-text__input ', 'class="tribe-common-form-control-text__input form-control ', $output );
	$output = str_replace( 'class="tribe-common-form-control-text__input"', 'class="tribe-common-form-control-text__input form-control"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_registration_js_attendees_fields_text_output', 'enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_text_output' );

function enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_email_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-field ', 'class="tribe-common-b1 tribe-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-common-form-control-email__input ', 'class="tribe-common-form-control-email__input form-control ', $output );
	$output = str_replace( 'class="tribe-common-form-control-email__input"', 'class="tribe-common-form-control-email__input form-control"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_registration_js_attendees_fields_email_output', 'enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_email_output' );

function enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_telephone_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-field ', 'class="tribe-common-b1 tribe-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-common-form-control-telephone__input ', 'class="tribe-common-form-control-telephone__input form-control ', $output );
	$output = str_replace( 'class="tribe-common-form-control-telephone__input"', 'class="tribe-common-form-control-telephone__input form-control"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_registration_js_attendees_fields_telephone_output', 'enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_telephone_output' );

function enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_url_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-field ', 'class="tribe-common-b1 tribe-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-common-form-control-url__input ', 'class="tribe-common-form-control-url__input form-control ', $output );
	$output = str_replace( 'class="tribe-common-form-control-url__input"', 'class="tribe-common-form-control-url__input form-control"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_registration_js_attendees_fields_url_output', 'enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_url_output' );

function enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_number_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-field ', 'class="tribe-common-b1 tribe-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-common-form-control-number__input ', 'class="tribe-common-form-control-number__input form-control ', $output );
	$output = str_replace( 'class="tribe-common-form-control-number__input"', 'class="tribe-common-form-control-number__input form-control"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_registration_js_attendees_fields_number_output', 'enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_number_output' );

function enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_datetime_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-field ', 'class="tribe-common-b1 tribe-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-common-form-control-datetime__input ', 'class="tribe-common-form-control-datetime__input form-control ', $output );
	$output = str_replace( 'class="tribe-common-form-control-datetime__input"', 'class="tribe-common-form-control-datetime__input form-control"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_registration_js_attendees_fields_datetime_output', 'enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_datetime_output' );

function enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_select_output( $output ) {
	$output = str_replace( 'class="tribe-field ', 'class="tribe-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-common-form-control-select__input ', 'class="tribe-common-form-control-select__input form-control ', $output );
	$output = str_replace( 'class="tribe-common-form-control-select__input"', 'class="tribe-common-form-control-select__input form-control"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_registration_js_attendees_fields_select_output', 'enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_select_output' );

function enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_birth_output( $output ) {
	if ( false !== strpos( $output, 'class="tribe_horizontal_datepicker__field_group"' ) ) {
		$output = str_replace( 'class="tribe-common-b1 tribe-field ', 'class="tribe-common-b1 tribe-field mb-3 ', $output );
		$output = str_replace( 'class="tribe-common-b2--min-medium tribe-tickets-meta-label"', 'class="tribe-common-b2--min-medium tribe-tickets-meta-label d-flex"', $output );
	} else {
		$output = str_replace( 'class="tribe-common-b1 tribe-field ', 'class="tribe-common-b1 tribe-field mb-3 row gx-2 ', $output );
		$output = str_replace( 'class="tribe-common-b2--min-medium tribe-tickets-meta-label"', 'class="tribe-common-b2--min-medium tribe-tickets-meta-label col-12"', $output );
	}

	$output = str_replace( 'class="tribe-required"', 'class="tribe-required order-1 ms-1"', $output );
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe_horizontal_datepicker__field_group"', 'class="tribe_horizontal_datepicker__field_group row gx-2"', $output );
	$output = str_replace( 'class="tribe_horizontal_datepicker"', 'class="tribe_horizontal_datepicker col"', $output );
	$output = str_replace( 'class="tribe-common-a11y-hidden"', 'class="tribe-common-a11y-hidden screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe_horizontal_datepicker__month"', 'class="tribe_horizontal_datepicker__month form-control"', $output );
	$output = str_replace( 'class="tribe_horizontal_datepicker__day"', 'class="tribe_horizontal_datepicker__day form-control"', $output );
	$output = str_replace( 'class="tribe_horizontal_datepicker__year"', 'class="tribe_horizontal_datepicker__year form-control"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_registration_js_attendees_fields_birth_output', 'enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_birth_output' );

function enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_checkbox_output( $output ) {
	$output = str_replace( 'class="tribe-field ', 'class="tribe-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-common-form-control-checkbox"', 'class="tribe-common-form-control-checkbox form-check"', $output );
	$output = str_replace( 'class="tribe-common-form-control-checkbox__input ticket-meta"', 'class="tribe-common-form-control-checkbox__input ticket-meta form-check-input"', $output );
	$output = str_replace( 'class="tribe-common-form-control-checkbox__label"', 'class="tribe-common-form-control-checkbox__label form-check-label"', $output );

	$offset = strpos( $output, '<label' );
	while ( false !== $offset ) {
		$end    = strpos( $output, '>', $offset ) + 1;
		$length = $end - $offset;
		$tag    = substr( $output, $offset, $length );

		$output = str_replace( $tag, '', $output );

		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, "\n" . $tag, $offset + 2, 0 );

		$offset = strpos( $output, '</label>', $offset );
		$offset = strpos( $output, '<label', $offset );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_registration_js_attendees_fields_checkbox_output', 'enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_checkbox_output' );

function enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_radio_output( $output ) {
	$output = str_replace( 'class="tribe-field ', 'class="tribe-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-common-form-control-radio"', 'class="tribe-common-form-control-radio form-check"', $output );
	$output = str_replace( 'class="tribe-common-form-control-radio__input ticket-meta"', 'class="tribe-common-form-control-radio__input ticket-meta form-check-input"', $output );
	$output = str_replace( 'class="tribe-common-form-control-radio__label"', 'class="tribe-common-form-control-radio__label form-check-label"', $output );

	$offset = strpos( $output, '<label' );
	while ( false !== $offset ) {
		$end    = strpos( $output, '>', $offset ) + 1;
		$length = $end - $offset;
		$tag    = substr( $output, $offset, $length );

		$output = str_replace( $tag, '', $output );

		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, "\n" . $tag, $offset + 2, 0 );

		$offset = strpos( $output, '</label>', $offset );
		$offset = strpos( $output, '<label', $offset );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_registration_js_attendees_fields_radio_output', 'enlightenment_tribe_bootstrap_template_registration_js_attendees_fields_radio_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_content_inactive_output( $output ) {
	return str_replace( 'class="tribe-tickets__item__content tribe-tickets__item__content--inactive"', 'class="tribe-tickets__item__content tribe-tickets__item__content--inactive card-body"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_content_inactive_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_content_inactive_output' );

function enlightenment_tribe_bootstrap_template_blocks_rsvp_form_opt_out_output( $output, $template_name, $file, $template ) {
	if ( 'Tribe__Tickets__RSVP' == $template->get( 'ticket' )->provider_class ) {
		$output = str_replace( 'class="tribe-common-form-control-checkbox tribe-tickets-attendees-list-optout--wrapper"', 'class="tribe-common-form-control-checkbox tribe-tickets-attendees-list-optout--wrapper mb-2"', $output );
	} else {
		$output = str_replace( 'class="tribe-common-form-control-checkbox tribe-tickets-attendees-list-optout--wrapper"', 'class="tribe-common-form-control-checkbox tribe-tickets-attendees-list-optout--wrapper col-12"', $output );
	}

	$offset = strpos( $output, '<label' );
	if ( false !== $offset ) {
		$end    = strpos( $output, '>', $offset ) + 1;
		$length = $end - $offset;
		$tag    = substr( $output, $offset, $length );
		$label  = tribe( 'tickets.privacy' )->get_opt_out_text();

		$output = str_replace( $tag, '', $output );
		$output = str_replace( '<input', '<div class="form-check"><input', $output );
		$output = str_replace( 'class="tribe-common-form-control-checkbox__input tribe-tickets__item__optout"', 'class="tribe-common-form-control-checkbox__input tribe-tickets__item__optout form-check-input"', $output );
		$output = str_replace( '/>', '/>' . "\n" . $tag, $output );
		$output = str_replace( 'class="tribe-common-form-control-checkbox__label"', 'class="tribe-common-form-control-checkbox__label form-check-label"', $output );
		$output = str_replace( '</label>', '</label></div>', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_rsvp_form_opt_out_output', 'enlightenment_tribe_bootstrap_template_blocks_rsvp_form_opt_out_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_blocks_tickets_footer_output( $output, $template_name, $file, $template ) {
	$class  = $template->get( 'is_modal' ) ? 'row align-items-center justify-content-end' : 'row gx-2 align-items-center';

	$offset = strpos( $output, 'class="tribe-tickets__footer"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '>', $offset );
		$output  = substr_replace( $output, sprintf( '<div class="%s">', $class ), $offset + 1, 0 );
		$output .= '</div>';
	}

	$offset = strpos( $output, 'class="tribe-tickets__footer"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' card-footer', $offset + 28, 0 );

		$offset = strpos( $output, 'class="tribe-tickets__footer"', $offset );
		if ( false !== $offset ) {
			$output = substr_replace( $output, ' mt-3', $offset + 28, 0 );
		}
	}

	$offset = strpos( $output, '<a class="tribe-common-b2 ' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="col flex-grow-0 flex-shrink-1">', $offset, 0 );
		$offset = strpos( $output, '</a>', $offset );
		$output = substr_replace( $output, '</div>', $offset + 4, 0 );
	}

	$output = str_replace( 'class="tribe-common-b2 tribe-tickets__footer__back-link"', 'class="tribe-common-b2 tribe-tickets__footer__back-link text-nowrap"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_footer_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_footer_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_blocks_tickets_footer_quantity_output( $output ) {
	$output = str_replace( 'class="tribe-common-b2 tribe-tickets__footer__quantity"', 'class="tribe-common-b2 tribe-tickets__footer__quantity col flex-grow-0 flex-shrink-1 text-nowrap"', $output );
	$output = str_replace( 'class="tribe-tickets__footer__quantity__label"', 'class="tribe-tickets__footer__quantity__label text-body-secondary"', $output );
	$output = str_replace( 'class="tribe-tickets__footer__quantity__number"', 'class="tribe-tickets__footer__quantity__number fw-bold"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_footer_quantity_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_footer_quantity_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_footer_total_output( $output ) {
	$output = str_replace( 'class="tribe-common-b2 tribe-tickets__footer__total"', 'class="tribe-common-b2 tribe-tickets__footer__total col flex-grow-0 flex-shrink-1 text-nowrap"', $output );
	$output = str_replace( 'class="tribe-tickets__footer__total__label"', 'class="tribe-tickets__footer__total__label text-body-secondary"', $output );
	$output = str_replace( 'class="tribe-tickets__footer__total__wrap"', 'class="tribe-tickets__footer__total__wrap fw-bold"', $output );
	$output = str_replace( 'class="tribe-formatted-currency-wrap tribe-currency-prefix"', 'class="tribe-formatted-currency-wrap tribe-currency-prefix d-inline-flex"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_footer_total_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_footer_total_output' );

function enlightenment_tribe_bootstrap_template_blocks_tickets_submit_output( $output ) {
	$output = sprintf( '<div class="col d-flex">%s</div>', $output );
	$output = str_replace( 'class="tribe-common-c-btn ', 'class="tribe-common-c-btn btn btn-primary ms-auto ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_blocks_tickets_submit_output', 'enlightenment_tribe_bootstrap_template_blocks_tickets_submit_output' );

function enlightenment_tribe_bootstrap_tpp_success_shortcode( $output ) {
	$offset = strpos( $output, 'class="order-recap invalid"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="alert alert-danger" ', $offset + 2, 0 );
	}

	$offset = strpos( $output, 'class="order-recap not-completed"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="alert alert-info" ', $offset + 2, 0 );
	}

	$offset = strpos( $output, 'class="order-recap valid"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="alert alert-success" ', $offset + 2, 0 );
	}

	$offset = strpos( $output, '<table class="tickets">' );
	if ( false !== $offset ) {
		$output   = substr_replace( $output, ' table', $offset + 21, 0 );
		$output   = substr_replace( $output, '<div class="table-responsive">' . "\n", $offset, 0 );

		$offset_a = strpos( $output, '<thead>', $offset );
		if ( false !== $offset_a ) {
			$end_a    = strpos( $output, '</thead>', $offset_a );
			$offset_a = strpos( $output, '<th>', $offset_a );
			$did_one  = false;

			while ( false !== $offset_a && $offset_a < $end_a ) {
				if ( $did_one ) {
					$output = substr_replace( $output, ' class="text-end"', $offset_a + 3, 0 );
				}

				$did_one  = true;

				$end_a    = strpos( $output, '</thead>', $offset_a );
				$offset_a = strpos( $output, '<th>', $offset_a + 1 );
			}
		}

		$offset_a = strpos( $output, '<td class="post-details">', $offset );
		while ( false !== $offset_a ) {
			$offset_b = strpos( $output, '<div class="thumbnail">', $offset_a );
			$end_b    = strpos( $output, '</td>', $offset_a );
			if ( false !== $offset_b && $offset_b < $end_b ) {
				$output   = substr_replace( $output, ' col-12 col-md flex-md-grow-0 flex-md-shrink-1', $offset_b + 21, 0 );
				$output   = substr_replace( $output, '<div class="row">' . "\n", $offset_b, 0 );
				$offset_b = strpos( $output, '</div>', $offset_b );
				$output   = substr_replace( $output, "\n" . '<div class="col-12 col-md">', $offset_b + 6, 0 );
				$offset_b = strpos( $output, '</td>', $offset_b );
				$output   = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset_b, 0 );
			}

			$offset_a = strpos( $output, '<td class="post-details">', $offset_a + 1 );
		}

		$offset   = strpos( $output, '</table>', $offset );
		$output   = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	$output = str_replace( 'class="ticket-price"', 'class="ticket-price text-end"', $output );
	$output = str_replace( 'class="ticket-quantity"', 'class="ticket-quantity text-end"', $output );
	$output = str_replace( 'class="ticket-subtotal"', 'class="ticket-subtotal text-end"', $output );

	$output = str_replace( 'class="title"', 'class="title text-end"', $output );
	$output = str_replace( 'class="quantity"', 'class="quantity text-end"', $output );
	$output = str_replace( 'class="total"', 'class="total text-end"', $output );

	return $output;
}
add_filter( 'enlightenment_filter_shortcode_tag_tribe-tpp-success', 'enlightenment_tribe_bootstrap_tpp_success_shortcode' );

function enlightenment_tribe_bootstrap_tickets_orders_template( $output ) {
    if ( false === strpos( $output, 'id="tribe-events-content"' ) ) {
        return $output;
    }

	$output = str_replace( 'class="tribe-tickets__form"', 'class="tribe-tickets__form row"', $output );
	$output = str_replace( 'class="tribe-submit-tickets-form"', 'class="tribe-submit-tickets-form col-12"', $output );
	$output = str_replace( 'class="button alt ', 'class="button alt btn btn-primary btn-lg ', $output );
    $output = str_replace( 'class="button alt"', 'class="button alt btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'the_content', 'enlightenment_tribe_bootstrap_tickets_orders_template', 12 );

function enlightenment_tribe_bootstrap_template_part_tickets_orders_rsvp_pp_tickets_output( $output ) {
	$output = str_replace( 'class="tribe-item', 'class="tribe-item list-group-item ', $output );

	$offset = strpos( $output, '<p class="list-attendee">' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, 'h3', $offset + 1, 1 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, 'h3', $offset + 2, 1 );

		$offset = strpos( $output, '<p class="list-attendee">', $offset );
	}

	$offset = strpos( $output, 'class="tribe-answer"' );
	while ( false !== $offset ) {
		$offset_a = strpos( $output, '-->', $offset );
		if ( false !== $offset_a ) {
			$offset = $offset_a;
		}

		$offset = strpos( $output, '<label>', $offset );
		$output = substr_replace( $output, '<label class="row align-items-center mb-0"><span class="col flex-grow-0 flex-shrink-1 fw-bold">', $offset, 7 );

		$offset_a = strpos( $output, '<select ', $offset );
		if ( false === $offset_a ) {
			$offset_a = strpos( $output, '<span>', $offset );
		}
		$offset = $offset_a;

		$output = substr_replace( $output, '</span>', $offset, 0 );

		$offset_a = strpos( $output, '<select ', $offset );
		if ( false !== $offset_a ) {
			$output   = substr_replace( $output, '<span class="col">', $offset_a, 0 );
			$offset_a = strpos( $output, '</select>', $offset_a );
			$output   = substr_replace( $output, '</span>', $offset_a + 9, 0 );
		} else {
			$offset_a = strpos( $output, '<span>', $offset );
			if ( false !== $offset_a ) {
				$output   = substr_replace( $output, '<span class="col">', $offset_a, 0 );
				$offset_a = strpos( $output, '</span>', $offset_a );
				$output   = substr_replace( $output, '</span>', $offset_a + 7, 0 );
			}
		}

		$offset = strpos( $output, '<div class="ticket-type">', $offset );
		$output = substr_replace( $output, ' row', $offset + 23, 0 );
		$offset = strpos( $output, '<span class="type-label">', $offset );
		$output = substr_replace( $output, ' col flex-grow-0 flex-shrink-1 fw-bold', $offset + 23, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, "\n" . '<span class="col">', $offset + 7, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</span>' . "\n", $offset, 0 );

		$offset = strpos( $output, 'class="tribe-answer"', $offset );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_tickets_orders_rsvp_output', 'enlightenment_tribe_bootstrap_template_part_tickets_orders_rsvp_pp_tickets_output' );
add_filter( 'enlightenment_tribe_filter_template_tickets_orders_rsvp_output', 'enlightenment_tribe_bootstrap_template_part_tickets_orders_rsvp_pp_tickets_output' );
add_filter( 'enlightenment_tribe_filter_template_part_tickets_orders_pp_tickets_output', 'enlightenment_tribe_bootstrap_template_part_tickets_orders_rsvp_pp_tickets_output' );
add_filter( 'enlightenment_tribe_filter_template_tickets_orders_pp_tickets_output', 'enlightenment_tribe_bootstrap_template_part_tickets_orders_rsvp_pp_tickets_output' );

function enlightenment_tribe_bootstrap_template_part_tickets_orders_rsvp_output( $output ) {
	if ( class_exists( 'Tribe__Tickets_Plus__Commerce__PayPal__Meta' ) ) {
		$colspan = 12;
	} else {
		$view    = tribe( 'tickets.commerce.paypal.view' );
		$post_id = get_the_ID();
		$user_id = get_current_user_id();
		$colspan = $view->has_ticket_attendees( $post_id, $user_id ) ? 6 : 12;
	}

	$output = str_replace( 'class="tribe-rsvp"', sprintf( 'class="tribe-rsvp col-%s"', $colspan ), $output );
	$output = str_replace( 'class="tribe-rsvp-list tribe-list"', 'class="tribe-rsvp-list tribe-list list-group"', $output );
	$output = str_replace( '<select ', '<select class="form-control" ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_tickets_orders_rsvp_output', 'enlightenment_tribe_bootstrap_template_part_tickets_orders_rsvp_output' );
add_filter( 'enlightenment_tribe_filter_template_tickets_orders_rsvp_output', 'enlightenment_tribe_bootstrap_template_part_tickets_orders_rsvp_output' );

function enlightenment_tribe_bootstrap_template_part_tickets_orders_pp_tickets_output( $output ) {
	$view    = Tribe__Tickets__Tickets_View::instance();
	$post_id = get_the_ID();
	$user_id = get_current_user_id();
	$colspan = $view->has_rsvp_attendees( $post_id, $user_id ) ? 6 : 12;

	$output = str_replace( 'class="tribe-pp"', sprintf( 'class="tribe-pp col-%s"', $colspan ), $output );
	$output = str_replace( 'class="tribe-tpp-list tribe-list"', 'class="tribe-tpp-list tribe-list list-group"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_tickets_orders_pp_tickets_output', 'enlightenment_tribe_bootstrap_template_part_tickets_orders_pp_tickets_output' );
add_filter( 'enlightenment_tribe_filter_template_tickets_orders_pp_tickets_output', 'enlightenment_tribe_bootstrap_template_part_tickets_orders_pp_tickets_output' );

function enlightenment_tribe_bootstrap_template_part_tickets_plus_orders_tickets_output( $output ) {
	$output = str_replace( 'class="tribe-tickets"', 'class="tribe-tickets col-12"', $output );
	$output = str_replace( 'class="tribe-orders-list"', 'class="tribe-orders-list list-unstyled mb-0"', $output );
	$output = str_replace( 'class="tribe-tickets-list tribe-list"', 'class="tribe-tickets-list tribe-list list-group"', $output );
	$output = str_replace( 'class="tribe-item" id="ticket-', 'class="tribe-item list-group-item" id="ticket-', $output );
	$output = str_replace( 'class="list-attendee"', 'class="list-attendee h3"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_tickets_plus_orders_tickets_output', 'enlightenment_tribe_bootstrap_template_part_tickets_plus_orders_tickets_output' );
add_filter( 'enlightenment_tribe_filter_template_tickets_my_tickets_orders_list_output', 'enlightenment_tribe_bootstrap_template_part_tickets_plus_orders_tickets_output' );

function enlightenment_tribe_bootstrap_template_part_tickets_plus_attendee_list_checkbox_output( $output ) {
	$output = str_replace( 'class="tribe-tickets-attendees-list-optout"', 'class="tribe-tickets-attendees-list-optout form-check mb-3"', $output );
	$output = str_replace( 'class="tribe-common-form-control-checkbox__input"', 'class="tribe-common-form-control-checkbox__input form-check-input"', $output );
	$output = str_replace( '<label ', '<label class="form-check-label"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_tickets_plus_attendee_list_checkbox_rsvp_output', 'enlightenment_tribe_bootstrap_template_part_tickets_plus_attendee_list_checkbox_output' );
add_filter( 'enlightenment_tribe_filter_template_part_tickets_plus_attendee_list_checkbox_tickets_output', 'enlightenment_tribe_bootstrap_template_part_tickets_plus_attendee_list_checkbox_output' );

function enlightenment_tribe_bootstrap_template_part_tickets_plus_orders_edit_meta_output( $output ) {
	$output = str_replace( '<p>', '<p class="alert alert-danger">', $output );
	$output = str_replace( 'class="tribe-event-tickets-plus-meta"', 'class="tribe-event-tickets-plus-meta mt-3"', $output );
	$output = str_replace( 'class="attendee-meta toggle show"', 'class="attendee-meta toggle btn btn-secondary" href="javascript:void(0)"', $output );
	$output = str_replace( 'class="attendee-meta-row"', 'class="attendee-meta-row" style="display: none;"', $output );
	$output = str_replace( 'class="tribe-common-a11y-hidden"', 'class="tribe-common-a11y-hidden screen-reader-text visually-hidden"', $output );

	$offset = strpos( $output, '<label ' );
	while ( false !== $offset ) {
		$offset += 7;
		$start   = strpos( $output, 'class="', $offset );
		$end     = strpos( $output, '>', $offset );

		if ( false !== $start && $start < $end ) {
			$output = substr_replace( $output, 'form-label ', $start + 7, 0 );
		} else {
			$output = substr_replace( $output, 'class="form-label" ', $offset, 0 );
		}

		$offset = strpos( $output, '<label ', $offset );
	}

	$class  = 'tribe-tickets__form-field tribe-tickets-meta tribe-tickets-meta-checkbox';
	$strlen = 56;
	$offset = strpos( $output, sprintf( '<div class="%s ', $class ) );
	if ( false === $offset ) {
		$class = 'tribe-tickets-meta tribe-tickets-meta-checkbox';
		$strlen = 30;
		$offset = strpos( $output, sprintf( '<div class="%s ', $class ) );
	}
	while ( false !== $offset ) {
		$output   = substr_replace( $output, ' done', $offset + $strlen, 0 );

		$offset_a = strpos( $output, '<label ', $offset );
		$end_a    = strpos( $output, '</div>', $offset );
		while ( false !== $offset_a && $offset_a < $end_a ) {
			$start    = $offset_a;
			$end      = strpos( $output, '>', $start ) + 1;
			$length   = $end - $start;
			$label    = substr( $output, $start, $length );
			$label    = str_replace( 'class="form-label tribe-tickets-meta-field-header"', 'class="tribe-tickets-meta-field-header form-check-label"', $label );
			$output   = substr_replace( $output, '<dummy-div class="form-check">', $start, $length );

			$offset_a = strpos( $output, 'class="ticket-meta"', $offset_a );
			$output   = substr_replace( $output, ' form-check-input', $offset_a + 18, 0 );
			$offset_a = strpos( $output, '<span class="tribe-tickets-meta-option-label">', $offset_a );
			$output   = substr_replace( $output, $label . "\n", $offset_a, 0 );
			$offset_a = strpos( $output, '</label>', $offset_a );
			$output   = substr_replace( $output, "\n" . '</dummy-div>', $offset_a + 8, 0 );

			$end_a    = strpos( $output, '</div>', $offset );
			$offset_a = strpos( $output, '<label ', $offset_a );
		}

		$offset = strpos( $output, 'class="tribe-tickets-meta tribe-tickets-meta-checkbox ', $offset );
	}

	// $offset = strpos( $output, '<div class="tribe-tickets-meta tribe-tickets-meta-radio ' );
	$class  = 'tribe-tickets__form-field tribe-tickets-meta tribe-tickets-meta-radio';
	$strlen = 56;
	$offset = strpos( $output, sprintf( '<div class="%s ', $class ) );
	if ( false === $offset ) {
		$class = 'tribe-tickets-meta tribe-tickets-meta-radio';
		$strlen = 30;
		$offset = strpos( $output, sprintf( '<div class="%s ', $class ) );
	}
	while ( false !== $offset ) {
		$output   = substr_replace( $output, ' done', $offset + $strlen, 0 );

		$offset_a = strpos( $output, '<label ', $offset );
		$end_a    = strpos( $output, '</div>', $offset );
		while ( false !== $offset_a && $offset_a < $end_a ) {
			$start    = $offset_a;
			$end      = strpos( $output, '>', $start ) + 1;
			$length   = $end - $start;
			$label    = substr( $output, $start, $length );
			$label    = str_replace( 'class="form-label tribe-tickets-meta-field-header"', 'class="tribe-tickets-meta-field-header form-check-label"', $label );
			$output   = substr_replace( $output, '<dummy-div class="form-check">', $start, $length );

			$offset_a = strpos( $output, 'class="ticket-meta"', $offset_a );
			$output   = substr_replace( $output, ' form-check-input', $offset_a + 18, 0 );
			$offset_a = strpos( $output, '<span class="tribe-tickets-meta-option-label">', $offset_a );
			$output   = substr_replace( $output, $label . "\n", $offset_a, 0 );
			$offset_a = strpos( $output, '</label>', $offset_a );
			$output   = substr_replace( $output, "\n" . '</dummy-div>', $offset_a + 8, 0 );

			$end_a    = strpos( $output, '</div>', $offset );
			$offset_a = strpos( $output, '<label ', $offset_a );
		}

		$offset = strpos( $output, 'class="tribe-tickets-meta tribe-tickets-meta-radio ', $offset );
	}

	$class  = 'tribe-tickets__form-field tribe_horizontal_datepicker__container';
	$strlen = 71;
	$offset = strpos( $output, sprintf( 'class="%s"', $class ) );
	if ( false === $offset ) {
		$class = 'tribe_horizontal_datepicker__container';
		$strlen = 45;
		$offset = strpos( $output, sprintf( 'class="%s"', $class ) );
	}
	while ( false !== $offset ) {
		$output   = substr_replace( $output, ' mt-3', $offset + $strlen, 0 );
		$offset   = strpos( $output, '<label>', $offset );
		$output   = substr_replace( $output, ' class="form-label"', $offset + 6, 0 );
		$offset   = strpos( $output, '<!-- Month -->', $offset );
		$output   = substr_replace( $output, '<div class="row gx-2">' . "\n", $offset, 0 );

		$offset_a = strpos( $output, 'class="tribe_horizontal_datepicker"', $offset );
		while ( false !== $offset_a ) {
			$output   = substr_replace( $output, ' col', $offset_a + 34, 0 );

			$offset_a = strpos( $output, 'class="tribe_horizontal_datepicker"', $offset_a + 1 );
		}

		$offset   = strpos( $output, '<!-- Year -->', $offset );
		$offset   = strpos( $output, '</div>', $offset );
		$output   = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );

		$offset = strpos( $output, 'class="tribe_horizontal_datepicker__container"', $offset );
	}

	$output = str_replace( '<dummy-div ', '<div ', $output );
	$output = str_replace( '</dummy-div>', '</div>', $output );
	$output = str_replace( 'class="tribe-tickets-meta done ', 'class="tribe-tickets-meta ', $output );
	$output = str_replace( 'class="tribe-tickets__form-field tribe-tickets-meta ', 'class="tribe-tickets__form-field tribe-tickets-meta mt-3 ', $output );
	$output = str_replace( 'class="tribe-tickets-meta ', 'class="tribe-tickets-meta mt-3 ', $output );
	$output = str_replace( 'class="ticket-meta"', 'class="ticket-meta form-control"', $output );
	$output = str_replace( 'class="tribe_horizontal_datepicker__month"', 'class="tribe_horizontal_datepicker__month form-control"', $output );
	$output = str_replace( 'class="tribe_horizontal_datepicker__day"', 'class="tribe_horizontal_datepicker__day form-control"', $output );
	$output = str_replace( 'class="tribe_horizontal_datepicker__year"', 'class="tribe_horizontal_datepicker__year form-control"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_tickets_plus_orders_edit_meta_output', 'enlightenment_tribe_bootstrap_template_part_tickets_plus_orders_edit_meta_output' );

function enlightenment_tribe_bootstrap_template_tickets_wallet_plus_passes_output( $output ) {
	return str_replace( 'class="tec-tickets__wallet-plus-passes-container ', 'class="tec-tickets__wallet-plus-passes-container d-flex align-items-center gap-3 mt-3 ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_attendees_list_passes_output', 'enlightenment_tribe_bootstrap_template_tickets_wallet_plus_passes_output' );
add_filter( 'enlightenment_tribe_filter_template_my_tickets_passes_output', 'enlightenment_tribe_bootstrap_template_tickets_wallet_plus_passes_output' );

function enlightenment_tribe_bootstrap_template_components_pdf_button_output( $output ) {
	return str_replace( 'class="tribe-common-c-btn-border tec-tickets__wallet-plus-component-pdf-button-link"', 'class="tribe-common-c-btn-border tec-tickets__wallet-plus-component-pdf-button-link btn btn-secondary"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_pdf_button_output', 'enlightenment_tribe_bootstrap_template_components_pdf_button_output' );

function enlightenment_tribe_bootstrap_template_apple_wallet_button_output( $output ) {
	$output = str_replace( 'class="tribe-common-c-btn-border tec-tickets__wallet-plus-component-apple-wallet-button-link"', 'class="tribe-common-c-btn-border tec-tickets__wallet-plus-component-apple-wallet-button-link btn btn-secondary"', $output );
	$output = str_replace( 'class="tribe-common-a11y-hidden"', 'class="tribe-common-a11y-hidden visually-hidden"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_apple_wallet_button_output', 'enlightenment_tribe_bootstrap_template_apple_wallet_button_output' );

function enlightenment_tribe_bootstrap_template_checkout_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__commerce-checkout"', 'class="tribe-tickets__commerce-checkout position-relative"', $output );

	$offset = strpos( $output, '<section' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="row">' . "\n" . '<div class="col-lg-8">' . "\n" . '<div class="tribe-tickets__commerce-checkout-form">', $offset + 1, 0 );
		$offset  = strrpos( $output, '</section>', -1 );
		$output  = substr_replace( $output, '</div>' . "\n" . '</div>' . "\n" . '</div>' . "\n", $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_output', 'enlightenment_tribe_bootstrap_template_checkout_output' );

function enlightenment_tribe_bootstrap_template_checkout_header_output( $output ) {
	return str_replace( 'class="tribe-tickets__commerce-checkout-header"', 'class="tribe-tickets__commerce-checkout-header d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_header_output', 'enlightenment_tribe_bootstrap_template_checkout_header_output' );

function enlightenment_tribe_bootstrap_template_checkout_header_title_output( $output ) {
	return str_replace( 'class="tribe-common-h2 tribe-tickets__commerce-checkout-header-title"', 'class="tribe-common-h2 tribe-tickets__commerce-checkout-header-title mb-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_header_title_output', 'enlightenment_tribe_bootstrap_template_checkout_header_title_output' );

function enlightenment_tribe_bootstrap_template_checkout_header_links_output( $output ) {
	$output = str_replace( 'class="tribe-common-b2 tribe-tickets__commerce-checkout-header-links"', 'class="tribe-common-b2 tribe-tickets__commerce-checkout-header-links btn-group"', $output );
	$output = str_replace( 'class="tribe-common-anchor-alt ', 'class="tribe-common-anchor-alt btn btn-secondary ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_header_links_output', 'enlightenment_tribe_bootstrap_template_checkout_header_links_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_output( $output ) {
	return str_replace( 'class="tribe-tickets__commerce-checkout-cart"', 'class="tribe-tickets__commerce-checkout-cart card mb-3"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_header_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-cart-header"', 'class="tribe-tickets__commerce-checkout-cart-header card-header"', $output );
	$output = str_replace( 'class="tribe-common-h4 tribe-common-h--alt tribe-tickets__commerce-checkout-cart-header-title"', 'class="tribe-common-h4 tribe-common-h--alt tribe-tickets__commerce-checkout-cart-header-title h4 mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_header_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_header_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_items_output( $output ) {
	return str_replace( 'class="tribe-tickets__commerce-checkout-cart-items"', 'class="tribe-tickets__commerce-checkout-cart-items card-body"', $output );
}
// add_filter( 'enlightenment_tribe_filter_template_checkout_cart_items_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_items_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_item_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-cart-item ', 'class="tribe-tickets__commerce-checkout-cart-item card-body ', $output );

	$offset = strpos( $output, '<article' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row align-items-baseline g-2">', $offset + 1, 0 );
		$offset = strpos( $output, '</article>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_item_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_item_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_item_details_output( $output ) {
	return str_replace( 'class="tribe-tickets__commerce-checkout-cart-item-details"', 'class="tribe-tickets__commerce-checkout-cart-item-details col-12 col-md d-flex flex-wrap align-items-baseline justify-content-between gap-2"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_item_details_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_item_details_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_item_details_title_output( $output ) {
	return str_replace( 'class="tribe-common-h6 tribe-tickets__commerce-checkout-cart-item-details-title"', 'class="tribe-common-h6 tribe-tickets__commerce-checkout-cart-item-details-title h5 mb-0"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_item_details_title_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_item_details_title_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_item_details_toggle_output( $output ) {
	return str_replace( 'class="tribe-common-b2 tribe-common-b3--min-medium ', 'class="tribe-common-b2 tribe-common-b3--min-medium btn btn-link btn-sm ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_item_details_toggle_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_item_details_toggle_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_item_details_description_output( $output ) {
	$output = str_replace( 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__commerce-checkout-cart-item-details-description ', 'class="tribe-common-b2 tribe-common-b3--min-medium tribe-tickets__commerce-checkout-cart-item-details-description card-text text-body-secondary w-100 ', $output );

	$offset = strpos( $output, '<div ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="d-flex flex-column gap-2">', $offset + 1, 0 );
		$offset = strrpos( $output, '</div>', -1 );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_item_details_description_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_item_details_description_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_item_details_extra_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-cart-item-details-description-attendee"', 'class="tribe-tickets__commerce-checkout-cart-item-details-description-attendee d-flex flex-column gap-1"', $output );
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-cart-item-details-description-attendee-name"', 'class="tribe-tickets__commerce-checkout-cart-item-details-description-attendee-name h6 mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_item_details_extra_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_item_details_extra_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_item_price_output( $output ) {
	return str_replace( 'class="tribe-tickets__commerce-checkout-cart-item-price"', 'class="tribe-tickets__commerce-checkout-cart-item-price col-4 col-md flex-md-grow-0 text-md-end"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_item_price_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_item_price_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_item_quantity_output( $output ) {
	return str_replace( 'class="tribe-tickets__commerce-checkout-cart-item-quantity"', 'class="tribe-tickets__commerce-checkout-cart-item-quantity col-4 col-md flex-md-grow-0 fw-bold text-center text-md-end"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_item_quantity_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_item_quantity_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_item_sub_total_output( $output ) {
	return str_replace( 'class="tribe-tickets__commerce-checkout-cart-item-subtotal"', 'class="tribe-tickets__commerce-checkout-cart-item-subtotal col-4 col-md flex-md-grow-0 text-end"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_item_sub_total_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_item_sub_total_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_footer_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-cart-footer ', 'class="tribe-tickets__commerce-checkout-cart-footer card-footer ', $output );

	$offset = strpos( $output, '<footer ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row gx-3 align-items-center justify-content-end">', $offset + 1, 0 );
		$offset = strpos( $output, '</footer>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_footer_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_footer_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_footer_quantity_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-cart-footer-quantity"', 'class="tribe-tickets__commerce-checkout-cart-footer-quantity col flex-grow-0 flex-shrink-1 text-nowrap"', $output );
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-cart-footer-quantity-label"', 'class="tribe-tickets__commerce-checkout-cart-footer-quantity-label text-body-secondary"', $output );
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-cart-footer-quantity-number"', 'class="tribe-tickets__commerce-checkout-cart-footer-quantity-number fw-bold"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_footer_quantity_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_footer_quantity_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_footer_total_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-cart-footer-total"', 'class="tribe-tickets__commerce-checkout-cart-footer-total col flex-grow-0 flex-shrink-1 text-nowrap"', $output );
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-cart-footer-total-label"', 'class="tribe-tickets__commerce-checkout-cart-footer-total-label text-body-secondary"', $output );
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-cart-footer-total-wrap"', 'class="tribe-tickets__commerce-checkout-cart-footer-total-wrap fw-bold"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_footer_total_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_footer_total_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_empty_output( $output ) {
	$output  = str_replace( 'class="tribe-tickets__commerce-checkout-cart-empty"', 'class="tribe-tickets__commerce-checkout-cart-empty alert alert-info"', $output );
	$output .= '</div>' . "\n" . '</div>' . "\n" . '<div class="col-lg-4">' . "\n" . '<div class="tribe-tickets__commerce-checkout-purchase">';

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_empty_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_empty_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_empty_title_output( $output ) {
	return str_replace( 'class="tribe-common-h3 tribe-tickets__commerce-checkout-cart-empty-title"', 'class="tribe-common-h3 tribe-tickets__commerce-checkout-cart-empty-title alert-heading"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_empty_title_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_empty_title_output' );

function enlightenment_tribe_bootstrap_template_checkout_cart_empty_description_output( $output ) {
	return str_replace( 'class="tribe-common-anchor-alt tribe-tickets__commerce-checkout-cart-empty-description-link"', 'class="tribe-common-anchor-alt tribe-tickets__commerce-checkout-cart-empty-description-link alert-link"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_cart_empty_description_output', 'enlightenment_tribe_bootstrap_template_checkout_cart_empty_description_output' );

function enlightenment_tribe_bootstrap_template_checkout_purchaser_info_output( $output ) {
	return str_replace( 'class="tribe-tickets__form ', 'class="tribe-tickets__form mb-3 ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_purchaser_info_output', 'enlightenment_tribe_bootstrap_template_checkout_purchaser_info_output' );

function enlightenment_tribe_bootstrap_template_checkout_purchaser_info_name_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-purchaser-info-field ', 'class="tribe-tickets__commerce-checkout-purchaser-info-field mb-3 ', $output );
	$output = str_replace( 'class="tribe-tickets__form-field-label ', 'class="tribe-tickets__form-field-label form-label ', $output );
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-purchaser-info-form-field ', 'class="tribe-tickets__commerce-checkout-purchaser-info-form-field form-control ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_purchaser_info_name_output', 'enlightenment_tribe_bootstrap_template_checkout_purchaser_info_name_output' );

function enlightenment_tribe_bootstrap_template_checkout_purchaser_info_email_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__form-field-label ', 'class="tribe-tickets__form-field-label form-label ', $output );
	$output = str_replace( 'class="tribe-common-b2 tribe-tickets__commerce-checkout-purchaser-info-form-field ', 'class="tribe-common-b2 tribe-tickets__commerce-checkout-purchaser-info-form-field form-control ', $output );
	$output = str_replace( 'class="tribe-common-b3 tribe-tickets__form-field-description"', 'class="tribe-common-b3 tribe-tickets__form-field-description form-text"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_checkout_purchaser_info_email_output', 'enlightenment_tribe_bootstrap_template_checkout_purchaser_info_email_output' );

function enlightenment_tribe_bootstrap_template_gateway_stripe_payment_element_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__commerce-checkout-stripe-payment-element"', 'class="tribe-tickets__commerce-checkout-stripe-payment-element mb-3"', $output );
	$output = str_replace( 'class="tribe-common-c-btn tribe-tickets__commerce-checkout-form-submit-button"', 'class="tribe-common-c-btn tribe-tickets__commerce-checkout-form-submit-button btn btn-primary btn-lg d-block w-100"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_gateway_stripe_payment_element_output', 'enlightenment_tribe_bootstrap_template_gateway_stripe_payment_element_output' );

function enlightenment_tribe_bootstrap_template_checkout_footer_gateway_error_output( $output ) {
	return str_replace( 'class="tribe-tickets__notice tribe-tickets__notice--error tribe-tickets__commerce-checkout-footer-notice-error--no-gateway alert alert-danger"', 'class="tribe-tickets__notice tribe-tickets__notice--error tribe-tickets__commerce-checkout-footer-notice-error--no-gateway alert alert-danger mb-0 mt-3"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_checkout_footer_gateway_error_output', 'enlightenment_tribe_bootstrap_template_checkout_footer_gateway_error_output' );

function enlightenment_tribe_bootstrap_template_order_description_order_output( $output ) {
	if ( empty( $output ) ) {
		return $output;
	}

	return sprintf( '<div class="alert alert-success">%s</div>', $output );
}
add_filter( 'enlightenment_tribe_filter_template_order_description_order_output', 'enlightenment_tribe_bootstrap_template_order_description_order_output' );

function enlightenment_tribe_bootstrap_template_order_description_order_empty_output( $output ) {
	if ( empty( $output ) ) {
		return $output;
	}

	return sprintf( '<div class="alert alert-info">%s</div>', $output );
}
add_filter( 'enlightenment_tribe_filter_template_order_description_order_empty_output', 'enlightenment_tribe_bootstrap_template_order_description_order_empty_output' );

function enlightenment_tribe_bootstrap_template_order_details_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-tickets__commerce-order-details"', 'class="tribe-common-b1 tribe-tickets__commerce-order-details row gy-3 mb-3"', $output );

	$offset = strpos( $output, 'class="tribe-tickets__commerce-order-details-row"' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, ' col-md', $offset + 48, 0 );
		$offset = strpos( $output, 'class="tribe-tickets__commerce-order-details-col1"', $offset );
		$output = substr_replace( $output, ' text-truncate', $offset + 49, 0 );
		$offset = strpos( $output, 'class="tribe-tickets__commerce-order-details-col2"', $offset );
		$output = substr_replace( $output, ' fw-bold text-truncate', $offset + 49, 0 );

		$offset = strpos( $output, 'class="tribe-tickets__commerce-order-details-row"', $offset );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_order_details_output', 'enlightenment_tribe_bootstrap_template_order_details_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_attendees_output( $output ) {
	$output = str_replace( 'class="tribe-tickets__rsvp-attendees-wrapper tribe-common-g-row"', 'class="tribe-tickets__rsvp-attendees-wrapper tribe-common-g-row mt-3"', $output );
	$output = str_replace( 'class="tec-tickets__attendees-list-item"', 'class="tec-tickets__attendees-list-item border-bottom py-2"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_attendees_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_attendees_output' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_attendees_attendee_output( $output ) {
	return str_replace( 'class="tec-tickets__attendees-list-item-attendee-details ', 'class="tec-tickets__attendees-list-item-attendee-details row ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_attendees_attendee_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_attendees_attendee_output' );

function enlightenment_tribe_bootstrap_attendee_name( $output ) {
	return str_replace( 'class="tec-tickets__attendees-list-item-attendee-details-name ', 'class="tec-tickets__attendees-list-item-attendee-details-name col-md-3 fw-bold text-truncate ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_attendees_attendee_name_output', 'enlightenment_tribe_bootstrap_attendee_name' );
add_filter( 'enlightenment_tribe_filter_template_components_attendees_list_attendees_attendee_name_output', 'enlightenment_tribe_bootstrap_attendee_name' );

function enlightenment_tribe_bootstrap_template_v2_rsvp_attendees_attendee_rsvp_output( $output ) {
	return str_replace( 'class="tec-tickets__attendees-list-item-attendee-details-rsvp"', 'class="tec-tickets__attendees-list-item-attendee-details-rsvp col-md-9 text-truncate"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_v2_rsvp_attendees_attendee_rsvp_output', 'enlightenment_tribe_bootstrap_template_v2_rsvp_attendees_attendee_rsvp_output' );

function enlightenment_tribe_bootstrap_template_components_attendees_list_attendees_output( $output ) {
	$output = str_replace( 'class="tec-tickets__attendees-list"', 'class="tec-tickets__attendees-list mb-3"', $output );
	$output = str_replace( 'class="tec-tickets__attendees-list-item"', 'class="tec-tickets__attendees-list-item border-bottom py-2"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_attendees_list_attendees_output', 'enlightenment_tribe_bootstrap_template_components_attendees_list_attendees_output' );

function enlightenment_tribe_bootstrap_template_components_attendees_list_attendees_attendee_output( $output ) {
	return str_replace( 'class="tec-tickets__attendees-list-item-attendee-details ', 'class="tec-tickets__attendees-list-item-attendee-details row ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_components_attendees_list_attendees_attendee_output', 'enlightenment_tribe_bootstrap_template_components_attendees_list_attendees_attendee_output' );

function enlightenment_tribe_bootstrap_template_components_attendees_list_attendees_attendee_ticket_output( $output ) {
	return str_replace( 'class="tec-tickets__attendees-list-item-attendee-details-ticket"', 'class="tec-tickets__attendees-list-item-attendee-details-ticket col-md-9 text-truncate"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_components_attendees_list_attendees_attendee_ticket_output', 'enlightenment_tribe_bootstrap_template_components_attendees_list_attendees_attendee_ticket_output' );

function enlightenment_tribe_bootstrap_template_order_footer_links_output( $output ) {
	return str_replace( 'class="tribe-common-b2 tribe-tickets__commerce-order-footer-links"', 'class="tribe-common-b2 tribe-tickets__commerce-order-footer-links d-flex flex-wrap justify-content-center gap-2"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_order_footer_links_output', 'enlightenment_tribe_bootstrap_template_order_footer_links_output' );

function enlightenment_tribe_bootstrap_template_order_footer_links_browse_events_output( $output ) {
	return str_replace( 'class="tribe-common-anchor-alt tribe-tickets__commerce-order-footer-link ', 'class="tribe-common-anchor-alt tribe-tickets__commerce-order-footer-link btn btn-primary btn-lg text-capitalize text-nowrap ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_order_footer_links_browse_events_output', 'enlightenment_tribe_bootstrap_template_order_footer_links_browse_events_output' );

function enlightenment_tribe_bootstrap_template_order_footer_links_back_home_output( $output ) {
	return str_replace( 'class="tribe-common-anchor-alt tribe-tickets__commerce-order-footer-link ', 'class="tribe-common-anchor-alt tribe-tickets__commerce-order-footer-link btn btn-secondary btn-lg text-capitalize text-nowrap ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_order_footer_links_back_home_output', 'enlightenment_tribe_bootstrap_template_order_footer_links_back_home_output' );

function enlightenment_tribe_bootstrap_community_events_list_add_new_button( $output ) {
	return str_replace( 'class="tribe-button tribe-button-primary add-new"', 'class="tribe-button tribe-button-primary add-new btn btn-primary"', $output );
}
add_filter( 'enlightenment_tribe_community_events_list_add_new_button', 'enlightenment_tribe_bootstrap_community_events_list_add_new_button' );

function enlightenment_tribe_bootstrap_event_list_search( $output ) {
	$output  = str_replace( '<div>', '', $output );
	$output  = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output  = str_replace( '</label>', '</label><div class="input-group">', $output );
	$output  = str_replace( 'name="event-search"', 'class="form-control" name="event-search"', $output );
	$output  = str_replace( 'id="search-submit"', 'class="btn btn-light" id="search-submit"', $output );
	$output .= '</div>';

	return $output;
}
add_filter( 'enlightenment_tribe_event_list_search', 'enlightenment_tribe_bootstrap_event_list_search' );

function enlightenment_tribe_bootstrap_community_event_list_template( $output ) {
    $output = str_replace( 'class="tribe-nav tribe-nav-top"', 'class="tribe-nav tribe-nav-top d-flex flex-column flex-md-row flex-wrap align-items-center"', $output );
	$output = str_replace( 'class="tribe-nav tribe-nav-bottom"', 'class="tribe-nav tribe-nav-bottom d-flex"', $output );
	$output = str_replace( 'class="my-events-display-options ce-top"', 'class="my-events-display-options ce-top btn-group me-md-auto"', $output );
	$output = str_replace( 'class="tribe-button tribe-button-small tribe-upcoming tribe-button-secondary"', 'class="tribe-button tribe-button-small tribe-upcoming tribe-button-secondary btn btn-secondary"', $output );
	$output = str_replace( 'class="tribe-button tribe-button-small tribe-upcoming tribe-button-tertiary"', 'class="tribe-button tribe-button-small tribe-upcoming tribe-button-tertiary btn btn-secondary"', $output );
	$output = str_replace( 'class="tribe-button tribe-button-small tribe-past tribe-button-secondary"', 'class="tribe-button tribe-button-small tribe-past tribe-button-secondary btn btn-secondary"', $output );
	$output = str_replace( 'class="tribe-button tribe-button-small tribe-past tribe-button-tertiary"', 'class="tribe-button tribe-button-small tribe-past tribe-button-tertiary btn btn-secondary"', $output );
	$output = str_replace( 'class="table-menu-wrapper ce-top"', 'class="table-menu-wrapper ce-top dropdown mt-3 mt-md-0 ms-md-auto order-md-last"', $output );
	$output = str_replace( 'class="table-menu-btn button tribe-button tribe-button-tertiary tribe-button-activate"', 'class="table-menu-btn button tribe-button tribe-button-tertiary tribe-button-activate btn btn-secondary dropdown-toggle ms-md-3" data-bs-toggle="dropdown" aria-expanded="false"', $output );
	$output = str_replace( 'class="table-menu table-menu-hidden"', 'class="table-menu table-menu-hidden dropdown-menu dropdown-menu-end"', $output );

	$start = strpos( $output, 'class="table-menu table-menu-hidden dropdown-menu dropdown-menu-end"' );
	if ( false !== $start ) {
		$start  = strpos( $output, '<ul>', $start );
		$output = substr_replace( $output, ' class="list-unstyled mb-0"', $start + 3, 0 );
		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="dropdown-item"', $offset + 3, 0 );
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, "\n" . '<div class="form-check">', $offset + 1, 0 );
			$offset = strpos( $output, '<label', $offset );
			$close  = strpos( $output, '>', $offset ) + 1;
			$length = $close - $offset;
			$tag    = substr( $output, $offset, $length );
			$output = substr_replace( $output, '', $offset, $length );
			$tag    = str_replace( 'class="', 'class="form-check-label ', $tag );
			$offset = strpos( $output, 'class="tribe-toggle-column"', $offset );
			$output = substr_replace( $output, ' form-check-input', $offset + 26, 0 );
			$offset = strpos( $output, '/>', $offset );
			$output = substr_replace( $output, "\n" . $tag, $offset + 2, 0 );
			$offset = strpos( $output, '</li>', $offset );
			$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
			$end    = strpos( $output, '</ul>', $start );
			$offset = strpos( $output, '<li>', $offset );
		}
	}

	$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-updated"' );
	while ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<p>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-success"', $offset + 2, 0 );
			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<p>', $offset );
		}

		$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-updated"', $end );
	}

	$output = str_replace( 'class="tribe-community-notice tribe-community-notice-updated"', 'class="tribe-community-notice order-first w-100 tribe-community-notice-updated"', $output );

	$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-error"' );
	while ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<p>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-danger"', $offset + 2, 0 );
			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<p>', $offset );
		}

		$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-error"', $end );
	}

	$output = str_replace( 'class="tribe-community-notice tribe-community-notice-error"', 'class="tribe-community-notice order-first w-100 tribe-community-notice-error"', $output );

	$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-' );
	while ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<p>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 2, 0 );
			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<p>', $offset );
		}

		$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-', $end );
	}

	$output = str_replace( 'class="tribe-community-notice tribe-community-notice-', 'class="tribe-community-notice order-first w-100 tribe-community-notice-', $output );

	$offset = strpos( $output, "<div class='tribe-pagination'>" );
	while ( false !== $offset ) {
		$output = substr_replace( $output, 'ul', $offset + 1, 3 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, 'ul', $offset + 2, 3 );
		$offset = strpos( $output, "<div class='tribe-pagination'>", $offset );
	}

	$start = strpos( $output, "class='tribe-pagination'" );
	while ( false !== $start ) {
		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<span ', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, '<li class="page-item active">', $offset, 0 );
			$offset = strpos( $output, '</span>', $offset );
			$output = substr_replace( $output, '</li>', $offset + 7, 0 );

			$end    = strpos( $output, '</ul>', $start );
			$offset = strpos( $output, '<span ', $offset );
		}

		$start = strpos( $output, "class='tribe-pagination'", $end );
	}

	$start = strpos( $output, "class='tribe-pagination'" );
	while ( false !== $start ) {
		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<a ', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, '<li class="page-item">', $offset, 0 );
			$offset = strpos( $output, '<a ', $offset );

			$offset_a = strpos( $output, 'class="', $offset );
			$end_a    = strpos( $output, '>', $offset );
			if ( false === $offset_a || $offset_a > $end_a ) {
				$output = substr_replace( $output, ' class="page-link"', $offset + 2, 0 );
			}

			$offset = strpos( $output, '</a>', $offset );
			$output = substr_replace( $output, '</li>', $offset + 4, 0 );

			$end    = strpos( $output, '</ul>', $start );
			$offset = strpos( $output, '<a ', $offset );
		}

		$start = strpos( $output, "class='tribe-pagination'", $end );
	}

	$output = str_replace( "class='tribe-pagination'", 'class="tribe-pagination pagination my-3 my-md-0 mx-auto"', $output );
	$output = str_replace( 'class="current"', 'class="current page-link"', $output );
	$output = str_replace( 'class="inactive"', 'class="inactive page-link"', $output );

	$output = str_replace( 'class="tribe-responsive-table-container"', 'class="tribe-responsive-table-container table-responsive"', $output );
	$output = str_replace( 'class="tribe-community-events-list my-events display responsive stripe"', 'class="tribe-community-events-list my-events display responsive stripe table table-striped"', $output );
	$output = str_replace( 'class="tribe-dependent column-header column-header-status"', 'class="tribe-dependent column-header column-header-status text-center"', $output );
	$output = str_replace( 'class="tribe-dependent tribe-list-column tribe-list-column-status"', 'class="tribe-dependent tribe-list-column tribe-list-column-status text-center"', $output );
	$output = str_replace( 'class="tribe-dependent column-header ', 'class="tribe-dependent column-header text-nowrap ', $output );
	$output = str_replace( 'class="tribe-dependent tribe-list-column ', 'class="tribe-dependent tribe-list-column text-nowrap ', $output );

	$output = str_replace( 'class="tribe-community-events-list tribe-community-no-items"', 'class="tribe-community-events-list tribe-community-no-items alert alert-info"', $output );

    return $output;
}
add_filter( 'enlightenment_tribe_filter_community_event_list_template', 'enlightenment_tribe_bootstrap_community_event_list_template' );

add_action( 'tribe_pre_get_template_part_community/columns/status', 'enlightenment_ob_start' );

function enlightenment_tribe_bootstrap_community_columns_status_template( $slug, $name, $data ) {
	ob_end_clean();

	$post_status  = $data['event']->post_status;
	$status_label = get_post_status_object( $post_status );

	if ( ! empty( $status_label ) ) {
		$status_label = $status_label->label;

		printf(
			'<div class="event-status d-inline-block" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="%s">%s</div>',
			esc_html( $status_label ),
			tribe( 'community.main' )->getEventStatusIcon( $post_status )
		);
	}
}
add_action( 'tribe_post_get_template_part_community/columns/status', 'enlightenment_tribe_bootstrap_community_columns_status_template', 10, 3 );

function enlightenment_tribe_bootstrap_community_events_list_events_link( $output ) {
	return str_replace( 'class="tribe-button tribe-button-secondary"', 'class="tribe-button tribe-button-secondary btn btn-secondary"', $output );
}
add_filter( 'enlightenment_tribe_community_events_list_events_link', 'enlightenment_tribe_bootstrap_community_events_list_events_link' );

function enlightenment_tribe_bootstrap_template_part_community_modules_header_links_output( $output ) {
	try {
		$submit_url = esc_url( tribe( 'community.main' )->getUrl( 'add' ) );
	} catch ( Exception $e ) {
		$submit_url = '';
	}

	if ( ! empty( $submit_url ) ) {
		$submit_url = apply_filters( 'tribe_events_community_submission_url', $submit_url );

		$offset = strpos( $output, '<p><a href="' . esc_url( $submit_url ) . '">' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, '<div><a class="btn btn-primary btn-lg"', $offset, 5 );
			$offset = strpos( $output, '</p>', $offset );
			$output = substr_replace( $output, '</div>', $offset, 4 );
		}
	}

	$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-updated"' );
	while ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<p>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-success"', $offset + 2, 0 );
			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<p>', $offset );
		}

		$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-updated"', $end );
	}

	$output = str_replace( 'class="tribe-community-notice tribe-community-notice-updated"', 'class="tribe-community-notice order-first w-100 tribe-community-notice-updated"', $output );

	$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-error"' );
	while ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<p>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-danger"', $offset + 2, 0 );
			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<p>', $offset );
		}

		$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-error"', $end );
	}

	$output = str_replace( 'class="tribe-community-notice tribe-community-notice-error"', 'class="tribe-community-notice order-first w-100 tribe-community-notice-error"', $output );

	$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-' );
	while ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<p>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="alert alert-info"', $offset + 2, 0 );
			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<p>', $offset );
		}

		$start = strpos( $output, 'class="tribe-community-notice tribe-community-notice-', $end );
	}

	$output = str_replace( 'class="tribe-community-notice tribe-community-notice-', 'class="tribe-community-notice order-first w-100 tribe-community-notice-', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_header_links_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_header_links_output' );
add_filter( 'the_content', 'enlightenment_tribe_bootstrap_template_part_community_modules_header_links_output' );

function enlightenment_tribe_bootstrap_community_tickets_before_html( $output, $slug ) {
	if ( 'event_form' != $slug && 'listing' != $slug ) {
		return $output;
	}

	$start = strpos( $output, '<span class="tribe-events-ajax-loading">' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</span>', $start ) + 7;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	return $output;
}
add_filter( 'tec_community_tickets_before_html', 'enlightenment_tribe_bootstrap_community_tickets_before_html', 10, 2 );

function enlightenment_tribe_bootstrap_template_part_community_modules_title_output( $output ) {
	$output = str_replace( 'class="events-community-post-title"', 'class="events-community-post-title mb-3"', $output );

	$start = strpos( $output, 'for="post_title"' );
	if ( false !== $start ) {
		$end    = strpos( $output, '>', $start );
		$offset = strpos( $output, 'class="', $start );

		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, 'form-label h5 ', $offset + 7, 0 );
		}
	}

	$start = strpos( $output, 'id="post_title"' );
	if ( false !== $start ) {
		$end    = strpos( $output, '/>', $start );
		$offset = strpos( $output, 'class="', $start );

		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, 'form-control form-control-lg ', $offset + 7, 0 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_title_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_title_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_description_output( $output ) {
	$output = str_replace( 'class="events-community-post-content"', 'class="events-community-post-content mb-3"', $output );

	$start = strpos( $output, 'for="post_content"' );
	if ( false !== $start ) {
		$end    = strpos( $output, '>', $start );
		$offset = strpos( $output, 'class="', $start );

		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, 'form-label h5 ', $offset + 7, 0 );
		}
	}

	$start = strpos( $output, 'id="post_content"' );
	if ( false !== $start ) {
		$end    = strpos( $output, '>', $start );
		$offset = strpos( $output, 'class="', $start );

		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, 'form-control ', $offset + 7, 0 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_description_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_description_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_datepickers_output( $output ) {
	$output = str_replace( 'class="tribe-section tribe-section-datetime ', 'class="tribe-section tribe-section-datetime card mb-3 ', $output );

	$offset = strpos( $output, 'class="tribe-section-header"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<h3>', $offset );
		$output  = substr_replace( $output, ' class="card-header h5"', $offset + 3, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	$output = str_replace( 'class="tribe-section-content tribe-datetime-block"', 'class="tribe-section-content tribe-datetime-block d-block w-100"', $output );
	$output = str_replace( '</colgroup>', '</colgroup>' . "\n" . '<tbody class="d-block">', $output );
	$output = str_replace( '</table>', '</tbody>' . "\n" . '</table>', $output );
	$output = str_replace( 'id="recurrence-changed-row"', 'id="recurrence-changed-row" class="d-none"', $output );

	$offset = strpos( $output, '<td class="tribe-section-content-field">' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, "\n" . '<div class="row gx-0 mb-3 align-items-center">', $offset + 40, 0 );
		$offset  = strpos( $output, '</td>', $offset );
		$output  = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, '<!-- Start Date -->' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, '<div class="col-12 col-sm-auto">' . "\n", $offset, 0 );
		$offset  = strpos( $output, '</span>', $offset );
		$output  = substr_replace( $output, "\n" . '</div>', $offset + 7, 0 );
	}

	$offset = strpos( $output, '<!-- Start Time -->' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, '<div class="col-12 col-sm-auto">' . "\n", $offset, 0 );
		$offset  = strpos( $output, '</span>', $offset );
		$output  = substr_replace( $output, "\n" . '</div>', $offset + 7, 0 );
	}

	$offset = strpos( $output, '<span class="tribe-datetime-separator"' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, '<div class="col-12 col-sm-auto align-self-start text-center">' . "\n", $offset, 0 );
		$offset  = strpos( $output, '</span>', $offset );
		$output  = substr_replace( $output, "\n" . '</div>', $offset + 7, 0 );
	}

	$offset = strpos( $output, '<!-- End Time -->' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, '<div class="col-12 col-sm-auto">' . "\n", $offset, 0 );
		$offset  = strpos( $output, '</span>', $offset );
		$output  = substr_replace( $output, "\n" . '</div>', $offset + 7, 0 );
	}

	$offset = strpos( $output, '<!-- End Date -->' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, '<div class="col-12 col-sm-auto">' . "\n", $offset, 0 );
		$offset  = strpos( $output, '</span>', $offset );
		$output  = substr_replace( $output, "\n" . '</div>', $offset + 7, 0 );
	}

	$offset = strpos( $output, '<span class="helper-text hide-if-js">' );
	while ( false !== $offset ) {
		$output  = substr_replace( $output, 'div', $offset + 1, 4 );
		$offset  = strpos( $output, '</span>', $offset );
		$output  = substr_replace( $output, 'div', $offset + 2, 4 );

		$offset = strpos( $output, '<span class="helper-text hide-if-js">', $offset );
	}

	$output = str_replace( 'class="tribe-datepicker tribe-field-start_date"', 'class="tribe-datepicker tribe-field-start_date form-control"', $output );
	$output = str_replace( 'class="tribe-timepicker tribe-field-start_time"', 'class="tribe-timepicker tribe-field-start_time form-control mt-1 mt-sm-0 ms-sm-1"', $output );
	$output = str_replace( 'class="tribe-datetime-separator"', 'class="tribe-datetime-separator d-block w-auto mx-auto mx-sm-2 my-sm-0 form-control px-0" style="border-color: transparent; border-width: var(--bs-border-width) 0;"', $output );
	$output = str_replace( 'class="tribe-timepicker tribe-field-end_time"', 'class="tribe-timepicker tribe-field-end_time form-control me-sm-1"', $output );
	$output = str_replace( 'class="tribe-datepicker tribe-field-end_date"', 'class="tribe-datepicker tribe-field-end_date form-control mt-1 mt-sm-0"', $output );
	$output = str_replace( 'class="helper-text ', 'class="helper-text form-text ', $output );
	$output = str_replace( 'class="tribe-field-timezone tribe-dropdown ', 'class="tribe-field-timezone tribe-dropdown form-select align-self-start ', $output );

	$offset = strpos( $output, '<!-- Timezone -->' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, "\n" . '<div class="col-12 col-xl-auto align-self-stretch d-flex align-items-center mt-2 mt-xl-0 ms-xl-3">', $offset + 17, 0 );
		$offset  = strpos( $output, '</select>', $offset );
		$output  = substr_replace( $output, "\n" . '</span>', $offset + 9, 0 );
	}

	$offset = strpos( $output, 'id="allDayCheckbox"' );
	if ( false !== $offset ) {
		$offset  = strrpos( $output, '<td class="tribe-section-content-field">', $offset - strlen( $output ) );
		$output = substr_replace( $output, "\n" . '<div class="form-check">', $offset + 40, 0 );
		$offset = strpos( $output, '<input', $offset );
		$offset = strpos( $output, 'class="', $offset );
		$output = substr_replace( $output, 'form-check-input ', $offset + 7, 0 );
		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, 'class="form-check-label" ', $offset + 7, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	$output = str_replace( 'class="tribe-section-content-row"', 'class="tribe-section-content-row row"', $output );
	$output = str_replace( 'class="tribe-section-content-row event-dynamic-helper"', 'class="tribe-section-content-row event-dynamic-helper row mb-3"', $output );
	$output = str_replace( 'class="tribe-section-content-label"', 'class="tribe-section-content-label d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( 'class="tribe-section-content-field"', 'class="tribe-section-content-field d-block col-12 col-md-9 py-0"', $output );

	$output = str_replace( '<label for="EventStartDate" class="', '<label for="EventStartDate" class="col-form-label ', $output );
	$output = str_replace( '<label for="EventSeries" class="', '<label for="EventSeries" class="col-form-label ', $output );

	$output = str_replace( 'class="event-dynamic-helper-text"', 'class="event-dynamic-helper-text form-text"', $output );
	$output = str_replace( 'class="tribe-add-recurrence button tribe-button tribe-button-secondary"', 'class="tribe-add-recurrence button tribe-button tribe-button-secondary btn btn-secondary"', $output );

	$offset = strpos( $output, 'id="tribe-row-delete-dialog"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, '<td>', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' class="p-0"', $offset + 3, 0 );
	}

	$offset = strpos( $output, 'class="recurrence-row tribe-datetime-block"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="d-block col-12 col-md-9 py-0"', $offset + 3, 0 );
	}

	$output = str_replace( 'class="recurrence-row tribe-datetime-block"', 'class="recurrence-row tribe-datetime-block row"', $output );
	$output = str_replace( 'class="recurrence-rules-header"', 'class="recurrence-rules-header d-block col-12 col-md-3 py-0"', $output );

	$offset = strpos( $output, 'class="recurrence-row tribe-recurrence-exclusion-row tribe-datetime-block"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="d-block col-12 col-md-9 py-0"', $offset + 3, 0 );
	}

	$offset = strpos( $output, 'class="recurrence-exclusions-header"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<div class="col-form-label">', $offset + 1, 0 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );
	}

	$output = str_replace( 'class="recurrence-row tribe-recurrence-exclusion-row tribe-datetime-block"', 'class="recurrence-row tribe-recurrence-exclusion-row tribe-datetime-block row mt-3"', $output );
	$output = str_replace( 'class="recurrence-exclusions-header"', 'class="recurrence-exclusions-header d-block col-12 col-md-3 py-0"', $output );

	$offset = strpos( $output, 'class="recurrence-row tribe-recurrence-description"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="d-block col-12 col-md-9 py-0"', $offset + 3, 0 );
	}

	$offset = strpos( $output, 'class="recurrence-description-header"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<div class="col-form-label">', $offset + 1, 0 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );
	}

	$output = str_replace( 'class="recurrence-row tribe-recurrence-description"', 'class="recurrence-row tribe-recurrence-description row mt-3"', $output );
	$output = str_replace( 'class="recurrence-description-header"', 'class="recurrence-description-header d-block col-12 col-md-3 py-0"', $output );

	$output = str_replace( 'class="tribe-confirm-delete-this tribe-delete-this button-primary button-red"', 'class="tribe-confirm-delete-this tribe-delete-this button-primary button-red d-none"', $output );

	$offset = strpos( $output, 'class="dashicons dashicons-trash tribe-delete-this"' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, 'btn btn-outline-danger float-end', $offset + 7, 25 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-trash" aria-hidden="true" role="presentation"></i>', $offset + 1, 0 );

		$offset = strpos( $output, 'class="dashicons dashicons-trash tribe-delete-this"', $offset );
	}

	$offset = strpos( $output, 'class="tribe-buttonset"' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<a', $offset );
		$output = substr_replace( $output, '<div class="btn-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$offset = strrpos( $output, '</a>', $offset - strlen( $output ) );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );

		$offset = strpos( $output, 'class="tribe-buttonset"', $offset );
	}

	$offset = strpos( $output, 'class="tec-events-pro-rule-type"' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, ' d-sm-none', $offset + 31, 0 );
		$offset = strrpos( $output, 'class="btn-group"', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' d-none d-sm-inline-flex', $offset + 16, 0 );

		$offset = strpos( $output, 'class="tec-events-pro-rule-type"', $offset );
	}

	$offset = strpos( $output, 'class="tec-events-pro-rule-type__tooltip dashicons dashicons-info tribe-sticky-tooltip"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, 'fas fa-info-circle', $offset + 41, 24 );
		$offset = strpos( $output, 'title="', $offset );
		$output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-', $offset, 0 );
	}

	$output = str_replace( 'class="tribe-buttonset"', 'class="tribe-buttonset mb-3"', $output );
	$output = str_replace( 'class="tribe-button-field ', 'class="tribe-button-field btn btn-secondary btn-sm ', $output );
	$output = str_replace( 'class="tribe-button-field"', 'class="tribe-button-field btn btn-secondary"', $output );
	$output = str_replace( 'class="tec-events-pro-week-days-lock dashicons dashicons-lock"', 'class="tec-events-pro-week-days-lock fa fa-lock"', $output );
	$output = str_replace( 'class="tribe-dependent recurrence-custom-container"', 'class="tribe-dependent recurrence-custom-container row gx-2 align-items-center mb-3"', $output );
	$output = str_replace( 'class="tribe-datepicker"', 'class="tribe-datepicker form-control form-control-sm"', $output );
	$output = str_replace( 'class="recurrence-row tribe-dependent"', 'class="recurrence-row tribe-dependent mb-3"', $output );

	$offset = strpos( $output, 'class="tribe-dependent recurrence-custom-container ' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<span class="tribe-field-inline-text">', $offset );
		$output = substr_replace( $output, ' d-block col-form-label', $offset + 36, 0 );
		$output = substr_replace( $output, '<span class="col-auto">', $offset, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, '</span>', $offset + 7, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<span class="col-auto">', $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, '</span>', $offset + 2, 0 );

		$offset = strpos( $output, 'class="tribe-dependent recurrence-custom-container ', $offset );
	}

	$offset = strpos( $output, 'class="recurrence-time"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="tribe-timepicker tribe-field-start_time form-control ', $offset );
		$output = substr_replace( $output, 'form-control-sm ', $offset + 60, 0 );
		$offset = strpos( $output, 'class="tribe-timepicker tribe-field-end_time form-control ', $offset );
		$output = substr_replace( $output, 'form-control-sm ', $offset + 58, 0 );
		$offset = strpos( $output, 'class="tribe-field-inline-text"', $offset );
		$output = substr_replace( $output, ' me-1 text-nowrap', $offset + 30, 0 );
	}

	$output = str_replace( 'class="recurrence-time"', 'class="recurrence-time d-inline-flex mt-2 mt-xl-0"', $output );
	$output = str_replace( 'class="tribe-field-inline-text eventduration-preamble"', 'class="tribe-field-inline-text eventduration-preamble d-block d-sm-inline w-sm-auto mx-1 text-center"', $output );
	$output = str_replace( 'class="tribe-event-recurrence-description"', 'class="tribe-event-recurrence-description form-text"', $output );
	$output = str_replace( 'class="tribe-dependent tribe-recurrence-details-option"', 'class="tribe-dependent tribe-recurrence-details-option mb-3"', $output );
	$output = str_replace( 'class="tribe-dependent tribe-recurrence-type"', 'class="tribe-dependent tribe-recurrence-type mb-3"', $output );
	$output = str_replace( 'class="tribe-dependent recurrence-row recurrence-end"', 'class="tribe-dependent recurrence-row recurrence-end mb-3"', $output );
	$output = str_replace( 'class="tribe-dropdown ', 'class="tribe-dropdown form-select form-select-sm w-auto d-inline-block ', $output );
	$output = str_replace( 'class="tribe-dropdown"', 'class="tribe-dropdown form-select form-select-sm w-auto d-inline-block"', $output );
	$output = str_replace( 'class="custom-recurrence-years tribe-dropdown ', 'class="custom-recurrence-years tribe-dropdown form-select form-select-sm w-auto d-inline-block ', $output );
	$output = str_replace( 'class="tribe-datepicker recurrence_end tribe-no-end-date-update tribe-field-end_date"', 'class="tribe-datepicker recurrence_end tribe-no-end-date-update tribe-field-end_date form-control form-control-sm"', $output );
	$output = str_replace( 'class="rec-count tribe-dependent"', 'class="rec-count tribe-dependent"', $output );
	$output = str_replace( 'class="recurrence_end_count"', 'class="recurrence_end_count form-control form-control-sm"', $output );

	$output = str_replace( 'class="recurrence-row custom-recurrence-weeks tribe-buttonset"', 'class="recurrence-row custom-recurrence-weeks tribe-buttonset mb-3"', $output );
	$output = str_replace( 'class="recurrence-row custom-recurrence-months"', 'class="recurrence-row custom-recurrence-months mb-3"', $output );
	$output = str_replace( 'class="tribe-month-select"', 'class="tribe-month-select mb-3"', $output );
	$output = str_replace( 'class="tribe-dame-day-select"', 'class="tribe-dame-day-select mb-3"', $output );
	$output = str_replace( 'class="tribe-same-day-select"', 'class="tribe-same-day-select mb-3"', $output );

	$offset = strpos( $output, '<div class="recurrence-row custom-recurrence-months ' );
	if ( false !== $offset ) {
		$end    = strpos( $output, '</div>', $offset );

		$offset = strpos( $output, 'class="tribe-field-inline-text first-label-in-line"', $offset );
		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' d-sm-none', $offset + 50, 0 );

			$offset = strpos( $output, 'class="tribe-field-inline-text first-label-in-line"', $offset );
			if ( false !== $offset && $offset < $end ) {
				$output = substr_replace( $output, ' d-none d-sm-inline', $offset + 50, 0 );
			}
		}
	}

	$offset = strpos( $output, '<div class="tribe-dame-day-select ' );
	if ( false === $offset ) {
		$offset = strpos( $output, '<div class="tribe-same-day-select ' );
	}
	if ( false !== $offset ) {
		$end    = strpos( $output, '</div>', $offset );

		$offset = strpos( $output, 'class="tribe-field-inline-text first-label-in-line"', $offset );
		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' d-sm-none', $offset + 50, 0 );

			$offset = strpos( $output, 'class="tribe-field-inline-text first-label-in-line"', $offset );
			if ( false !== $offset && $offset < $end ) {
				$output = substr_replace( $output, ' d-none d-sm-inline', $offset + 50, 0 );
			}
		}
	}

	$output = str_replace( 'class="tribe-dropdown tribe-same-day-select"', 'class="tribe-dropdown tribe-same-day-select d-none d-sm-inline"', $output );
	$output = str_replace( 'class="tribe-dropdown tec-events-pro-year-not-same-day-dropdown"', 'class="tribe-dropdown tec-events-pro-year-not-same-day-dropdown d-sm-none"', $output );
	$output = str_replace( 'class="tribe-dropdown tec-events-pro-month-on-the-dropdown"', 'class="tribe-dropdown tec-events-pro-month-on-the-dropdown d-sm-none"', $output );

	$offset = strpos( $output, 'data-field="custom-month-number"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'class="tribe-dropdown ', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' d-none d-sm-inline', $offset + 21, 0 );
	}

	$offset = strpos( $output, 'data-field="custom-month-day"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'class="tribe-dropdown ', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' d-none d-sm-inline', $offset + 21, 0 );
	}

	$offset = strpos( $output, 'data-field="custom-year-month-number"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'class="tribe-dropdown ', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' d-none d-sm-inline', $offset + 21, 0 );
	}

	$offset = strpos( $output, 'data-field="custom-year-month-day"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'class="tribe-dropdown ', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' d-none d-sm-inline', $offset + 21, 0 );
	}

	$output = str_replace( 'class="tribe-button-input tribe-hidden"', 'class="tribe-button-input tribe-hidden d-none"', $output );
	$output = str_replace( 'class="show"', 'class="show dropdown-toggle"', $output );
	$output = str_replace( 'class="hide"', 'class="hide dropdown-toggle"', $output );
	$output = str_replace( 'class="tribe-delete-this tribe-confirm-delete-this button-primary button-red"', 'class="tribe-delete-this tribe-confirm-delete-this button-primary button-red d-none"', $output );
	$output = str_replace( 'class="button"', 'class="button btn btn-secondary"', $output );

	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'id="recurrence-description"', 'id="recurrence-description" class="form-control"', $output );

	$offset = strpos( $output, 'class="recurrence-row tribe-recurrence-not-supported tec-events-pro-recurrence-not-supported"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' row mt-3', $offset + 92, 0 );
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="d-block col-12 col-md-3 py-0"', $offset + 3, 0 );
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="d-block col-12 col-md-9 py-0"', $offset + 3, 0 );
		$offset = strpos( $output, '<p class="tec-events-pro-recurrence-not-supported__text"', $offset );
		$output = substr_replace( $output, ' alert alert-info d-flex mb-0', $offset + 55, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<i class="fas fa-info-circle mt-1 me-2" role="presentation" aria-hidden="true"></i>' . "\n" . '<span>', $offset + 1, 0 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, '</span>' . "\n", $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_integrations_the_events_calendar_modules_datepickers_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_datepickers_output' );
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_datepickers_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_datepickers_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_image_output( $output ) {
	$output = str_replace( 'class="tribe-section tribe-section-image-uploader"', 'class="tribe-section tribe-section-image-uploader card mb-3"', $output );

	$offset = strpos( $output, '<div class="tribe-section-header">' );
	if ( false !== $offset ) {
		$end_a = strpos( $output, '</div>', $offset );

		$offset_a = strpos( $output, '<label ', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$end_b    = strpos( $output, '>', $offset_a );
			$offset_b = strpos( $output, 'class="', $offset_a );

			if ( false !== $offset_b && $offset_b < $end_b ) {
				$output = substr_replace( $output, 'card-header d-block h5 ', $offset_b + 7, 0 );
			} else {
				$output = substr_replace( $output, ' class="card-header d-block h5"', $offset_a + 6, 0 );
			}
		} else {
			$offset_a = strpos( $output, '<h3>', $offset );

			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output = substr_replace( $output, ' class="card-header h5"', $offset_a + 3, 0 );
			}
		}

		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body text-center">', $offset + 6, 0 );
		$output .= '</div>';
	}

	$output = str_replace( 'class="tribe-community-events-preview-image"', 'class="tribe-community-events-preview-image mb-3"', $output );
	$output = str_replace( 'class="submitdelete"', 'class="submitdelete text-danger"', $output );
	$output = str_replace( 'class="form-controls"', 'class="form-controls position-relative mx-auto"', $output );
	$output = str_replace( 'class="uploadFile"', 'class="uploadFile form-control"', $output );
	$output = str_replace( 'class="screen-reader-text ', 'class="screen-reader-text visually-hidden" ', $output );
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="choose-file tribe-button tribe-button-secondary"', 'class="choose-file tribe-button tribe-button-secondary btn btn-secondary btn-lg d-block w-100 mt-3"', $output );
	$output = str_replace( 'class="event_image ', 'class="event_image fixed-top position-absolute w-100 h-100 opacity-0 ', $output );
	$output = str_replace( 'class="EventImage"', 'class="EventImage fixed-top position-absolute w-100 h-100 opacity-0"', $output );
	$output = str_replace( 'class="tribe-remove-upload"', 'class="tribe-remove-upload mt-3"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_image_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_image_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_taxonomy_output( $output ) {
	$output = str_replace( 'class="tribe-section tribe-section-taxonomy ', 'class="tribe-section tribe-section-taxonomy card mb-3 ', $output );
	$output = str_replace( 'class="tribe-section tribe-section-taxonomy"', 'class="tribe-section tribe-section-taxonomy card mb-3"', $output );

	$offset = strpos( $output, 'class="tribe-section-header"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<h3>', $offset );
		$output  = substr_replace( $output, ' class="card-header h5"', $offset + 3, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	$offset = strpos( $output, '<select' );
	if ( false === $offset ) {
		$output = str_replace( 'class="tribe-dropdown"', 'class="tribe-dropdown form-control"', $output );
	} else {
		$output = str_replace( 'class="tribe-dropdown"', 'class="tribe-dropdown form-select"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_integrations_the_events_calendar_modules_taxonomy_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_taxonomy_output' );
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_taxonomy_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_taxonomy_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_event_status_output( $output ) {
	$output = str_replace( 'class="tribe-section tribe-section-event-status"', 'class="tribe-section tribe-section-event-status card mb-3"', $output );
	$output = str_replace( 'class="tribe-dropdown ', 'class="tribe-dropdown form-select ', $output );

	$offset = strpos( $output, 'class="tribe-section-header"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<h3 class="', $offset );
		$output  = substr_replace( $output, 'card-header h5 ', $offset + 11, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	$offset = strpos( $output, '<div class="tribe-events-status_metabox__container">' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<label for="', $offset );
		$output = substr_replace( $output, ' class="col-12 col-md-3 col-form-label"', $offset + 6, 0 );
		$output = substr_replace( $output, '<div class="row align-items-center">', $offset, 0 );
		$offset = strpos( $output, 'class="tribe-events-status tribe-events-status-select"', $offset );
		$output = substr_replace( $output, ' col-12 col-md-9', $offset + 53, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>', $offset + 6, 0 );
	}

	$offset = strpos( $output, 'class="tribe-events-status-components-textarea-control__container"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' row align-items-start mt-3', $offset + 65, 0 );
		$offset = strpos( $output, 'class="tribe-events-status-components-textarea-control__label"', $offset );
		$output = substr_replace( $output, ' col-12 col-md-3 col-form-label', $offset + 61, 0 );
		$offset = strpos( $output, '<textarea', $offset );
		$output = substr_replace( $output, '<div class="col-12 col-md-9">', $offset, 0 );
		$offset = strpos( $output, 'class="tribe-events-status-components-textarea-control__input"', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 61, 0 );
		$offset = strpos( $output, '</textarea>', $offset );
		$output = substr_replace( $output, '</div>', $offset + 11, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_event_status_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_event_status_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_virtual_output( $output ) {
	$output = str_replace( 'class="tribe-section tribe-section-virtual"', 'class="tribe-section tribe-section-virtual card mb-3"', $output );

	$offset = strpos( $output, 'class="tribe-section-header"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<h3 class="', $offset );
		$output  = substr_replace( $output, 'card-header h5 ', $offset + 11, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_virtual_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_virtual_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	return str_replace( 'class="virtual-event-wrapper eventtable"', 'class="virtual-event-wrapper eventtable d-block w-100"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_head_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( '<thead>', '<thead class="d-block">', $output );
	$output = str_replace( '<tr>', '<tr class="row">', $output );
	$output = str_replace( '<td>', '<td class="d-block col py-0">', $output );
	$output = str_replace( 'class="tribe-configure-virtual-button button ', 'class="tribe-configure-virtual-button button btn btn-secondary ', $output );
	$output = str_replace( 'class="tribe-configure-virtual-button button"', 'class="tribe-configure-virtual-button button btn btn-secondary"', $output );
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_head_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_head_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_event_type_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-virtual-type-of-event"', 'class="tribe-events-virtual-type-of-event row"', $output );
	$output = str_replace( "class='tribe-table-field-label'", 'class="tribe-table-field-label d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( '<td>', '<td class="d-flex col-12 col-md-9 align-items-start py-0">', $output );
	$output = str_replace( 'class="dashicons dashicons-trash tribe-remove-virtual-event tribe-dependent"', 'class="tribe-remove-virtual-event tribe-dependent btn btn-outline-danger order-1 ms-auto"', $output );

	$offset = strpos( $output, 'class="tribe-remove-virtual-event ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<i class="fas fa-trash" aria-hidden="true" role="presentation"></i>', $offset + 1, 0 );
	}

	$output = str_replace( '<ul>', '<ul class="list-unstyled">', $output );

	$offset = strpos( $output, '<label for="' );
	while ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$label  = substr( $output, $start, $length );
		$label  = str_replace( '<label ', '<label class="form-check-label" ', $label );

		$output = substr_replace( $output, '<div class="form-check">', $offset, $length );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . $label, $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );

		$offset = strpos( $output, '<label for="', $offset );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_event_type_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_event_type_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_video_source_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$offset = strpos( $output, 'class="dashicons dashicons-trash tribe-remove-virtual-event' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<i class="fas fa-trash-alt"></i>', $offset + 1, 0 );
	}

	$offset = strpos( $output, '<li class="tribe-events-virtual-video-source__virtual-url">' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="col-12 col-md flex-md-grow-1 flex-md-shrink-0">', $offset + 1, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 2, 0 );
	}

	$output = str_replace( 'class="tribe-events-virtual-video-source"', 'class="tribe-events-virtual-video-source row"', $output );
	$output = str_replace( 'class="tribe-table-field-label tribe-events-virtual-video-source__label"', 'class="tribe-table-field-label tribe-events-virtual-video-source__label d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( 'class="tribe-events-virtual-video-source__content"', 'class="tribe-events-virtual-video-source__content col-12 col-md-9 align-items-start py-0"', $output );
	$output = str_replace( 'class="dashicons dashicons-trash tribe-remove-virtual-event', 'class="tribe-remove-virtual-event btn btn-link order-1 ms-3', $output );

	// backpat
	$output = enlightenment_tribe_bootstrap_template_virtual_metabox_video_input_output( $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_video_source_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_video_source_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_facebook_controls_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-facebook__inner-controls"', 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-facebook__inner-controls modal-content"', $output );
	$output = str_replace( 'class="tec-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-facebook__inner-controls"', 'class="tec-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-facebook__inner-controls modal-content"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-video-source__title"', 'class="tribe-events-virtual-meetings-video-source__title modal-title h5"', $output );

	$offset = strpos( $output, '<div class="tribe-events-virtual-meetings-video-source__title ' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, '<div class="modal-header">' . "\n", $offset, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="modal-body">', $offset + 6, 0 );
		$output .= "\n" . '</div>';
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_facebook_controls_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_facebook_controls_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_facebook_incomplete_setup_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-dependent tribe-events-virtual-meetings-facebook-details"', 'class="tribe-dependent tribe-events-virtual-meetings-facebook-details card"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-facebook__inner-controls"', 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-facebook__inner-controls card-body"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-facebook-error__details-header"', 'class="tribe-events-virtual-meetings-facebook-error__details-header h5"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-facebook-error__link-connect"', 'class="tribe-events-virtual-meetings-facebook-error__link-connect btn btn-primary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_facebook_incomplete_setup_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_facebook_incomplete_setup_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_youtube_controls_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-youtube__inner-controls"', 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-youtube__inner-controls modal-content"', $output );
	$output = str_replace( 'class="tec-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-youtube__inner-controls"', 'class="tec-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-youtube__inner-controls modal-content"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-video-source__title"', 'class="tribe-events-virtual-meetings-video-source__title modal-title h5"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-source-youtube__channel-id-text-label"', 'class="tribe-events-virtual-meetings-source-youtube__channel-id-text-label form-label"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-source-youtube__channel-id-text-input components-text-control__input"', 'class="tribe-events-virtual-meetings-source-youtube__channel-id-text-input components-text-control__input form-control"', $output );

	$offset = strpos( $output, '<div class="tribe-events-virtual-meetings-video-source__title ' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, '<div class="modal-header">' . "\n", $offset, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="modal-body">', $offset + 6, 0 );
		$output .= "\n" . '</div>';
	}

	$offset = strpos( $output, 'class="tribe-events-virtual-meetings-source-youtube__channel-id-text-label ' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, '<label', $offset - strlen( $output ) );
		$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 2, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_youtube_controls_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_youtube_controls_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_youtube_panel_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	return str_replace( 'class="tribe-events-virtual-meetings-source-youtube__content"', 'class="tribe-events-virtual-meetings-source-youtube__content accordion-body"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_youtube_panel_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_youtube_panel_output' );

function enlightenment_tribe_bootstrap_template_youtube_components_switch_field_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-field tribe-field-text tribe-size-medium tribe-field-switch"', 'class="tribe-field tribe-field-text tribe-size-medium tribe-field-switch mb-3"', $output );
	$output = str_replace( 'class="tribe-field-switch-inner-wrap"', 'class="tribe-field-switch-inner-wrap d-flex"', $output );
	$output = str_replace( 'class="tribe-common-control tribe-common-control--switch ', 'class="tribe-common-control tribe-common-control--switch form-check mb-0 ', $output );

	$target = '';

	$offset = strpos( $output, '<label ' );
	if ( false !== $offset ) {
		$start  = strpos( $output, ' for="', $offset ) + 6;
		$end    = strpos( $output, '"', $start );
		$length = $end - $start;
		$target = substr( $output, $start, $length );

		$start  = $offset;
		$end    = strpos( $output, '</label>', $start ) + 8;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );

		$output = str_replace( 'class="tribe-common-control tribe-common-control--switch ', 'class="tribe-common-control tribe-common-control--switch form-check ps-0 ', $output );
		$output = str_replace( 'class="tribe-common-switch__input ', 'class="tribe-common-switch__input ms-0 ', $output );
	}

	$output = str_replace( '<legend class="tribe-field-label">', sprintf( '<label class="tribe-field-label d-flex align-items-baseline flex-grow-1 me-3 mb-0"%s>', empty( $target ) ? '' : sprintf( ' for="%s"', $target ) ), $output );
	$output = str_replace( '</legend>', '</label>', $output );

	$tooltip = '';

	$offset = strpos( $output, '<div class="down">' );
	if ( false !== $offset ) {
		$start   = strpos( $output, '<p>', $offset ) + 3;
		$end     = strpos( $output, '</p>', $start );
		$length  = $end - $start;
		$tooltip = substr( $output, $start, $length );
		$tooltip = trim( $tooltip );

		$start   = $offset;
		$end     = strpos( $output, '</div>', $start ) + 6;
		$length  = $end - $start;
		$output  = substr_replace( $output, '', $start, $length );
	}

	$output = str_replace( 'class="tribe-tooltip event-helper-text tribe-events-virtual-show-to-ticket-attendees-helper-text"', sprintf( 'class="tribe-tooltip event-helper-text tribe-events-virtual-show-to-ticket-attendees-helper-text d-flex ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-title="%s"', esc_html( $tooltip ) ), $output );
	$output = str_replace( 'class="dashicons dashicons-info"', 'class="fas fa-info-circle" aria-hidden="true" role="presentation"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_youtube_components_switch_field_output', 'enlightenment_tribe_bootstrap_template_youtube_components_switch_field_output' );

function enlightenment_tribe_bootstrap_template_components_switch_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-common-control tribe-common-control--switch ', 'class="tribe-common-control tribe-common-control--switch form-check form-switch ', $output );
	$output = str_replace( 'class="tribe-common-control tribe-common-control--switch"', 'class="tribe-common-control tribe-common-control--switch form-check form-switch"', $output );
	$output = str_replace( 'class="tribe-common-switch__input ', 'class="tribe-common-switch__input form-check-input ', $output );
	$output = str_replace( 'class="tribe-common-switch__input"', 'class="tribe-common-switch__input form-check-input"', $output );
	$output = str_replace( 'class="tribe-common-switch__label ', 'class="tribe-common-switch__label form-check-label ', $output );
	$output = str_replace( 'class="tribe-common-switch__label"', 'class="tribe-common-switch__label form-check-label"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_switch_output', 'enlightenment_tribe_bootstrap_template_components_switch_output' );

function enlightenment_tribe_bootstrap_template_components_accordion_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-virtual-meetings__accordion-wrapper ', 'class="tribe-events-virtual-meetings__accordion-wrapper accordion ', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings__accordion-wrapper"', 'class="tribe-events-virtual-meetings__accordion-wrapper accordion"', $output );
	$output = str_replace( 'class="button tribe-events-virtual-meetings__accordion-element ', 'class="button tribe-events-virtual-meetings__accordion-element accordion-button ', $output );

	$offset = strpos( $output, 'data-js="tribe-events-accordion-trigger"' );
	if ( false !== $offset ) {
		$start    = strpos( $output, 'aria-expanded="', $offset ) + 15;
		$end      = strpos( $output, '"', $start );
		$length   = $end - $start;
		$expanded = substr( $output, $start, $length );

		$output = str_replace( 'class="tribe-events-virtual-meetings__accordion-element ', sprintf( 'class="tribe-events-virtual-meetings__accordion-element accordion-collapse collapse%s ', 'true' == $expanded ? ' show' : '' ), $output );

		$offset  = strrpos( $output, '<button', $offset - strlen( $output ) );
		$output  = substr_replace( $output, '<div class="accordion-item">' . "\n" . '<h2 class="accordion-header">' . "\n", $offset, 0 );
		$offset  = strpos( $output, '</button>', $offset );
		$output  = substr_replace( $output, "\n" . '</h2>', $offset + 9, 0 );
		$output .= "\n" . '</div>';
	}

	$offset = strpos( $output, 'class="tribe-events-virtual-meetings__accordion-element ' );
	if ( false !== $offset ) {
		$start  = strpos( $output, 'aria-hidden="', $offset ) + 13;
		$end    = strpos( $output, '"', $start );
		$length = $end - $start;
		$hidden = substr( $output, $start, $length );

		if ( 'false' == $hidden ) {
			$offset_a = strpos( $output, 'style="display:block"', $offset );

			if ( false !== $offset_a ) {
				$output  = substr_replace( $output, '', $offset_a, 21 );
			}
		}

		$start  = strpos( $output, 'id="', $offset ) + 4;
		$end    = strpos( $output, '"', $start );
		$length = $end - $start;
		$target = substr( $output, $start, $length );

		$output = str_replace( 'data-js="tribe-events-accordion-trigger"', sprintf( 'data-bs-toggle="collapse" data-bs-target="#%s"', esc_attr( $target ) ), $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_accordion_output', 'enlightenment_tribe_bootstrap_template_components_accordion_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_components_dropdown_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	return str_replace( 'class="tribe-events-virtual-meetings-zoom-control__label"', 'class="tribe-events-virtual-meetings-zoom-control__label form-label"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_components_dropdown_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_components_dropdown_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_components_radio_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom-control tribe-events-virtual-meetings-zoom-control--radio"' );
	if ( false !== $offset ) {
		$start  = strpos( $output, '<label', $offset );
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$label  = substr( $output, $start, $length );
		$label  = str_replace( 'class="tribe-events-virtual-meetings-zoom__host-dropdown"', 'class="tribe-events-virtual-meetings-zoom__host-dropdown form-check-label"', $label );
		$output = substr_replace( $output, '', $start, $length );

		$output = substr_replace( $output, ' form-check', $offset + 99, 0 );
		$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom__host-dropdown"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 56, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . $label, $offset + 1, 0 );
		$offset = strpos( $output, '</div>', $offset );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_components_radio_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_components_radio_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_components_multiselect_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-control__label"', 'class="tribe-events-virtual-meetings-zoom-control__label form-label"', $output );
	$output = str_replace( 'class="tribe-dropdown ', 'class="tribe-dropdown form-select ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_components_multiselect_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_components_multiselect_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_controls_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom tribe-events-virtual-meetings-zoom-controls"', 'class="tribe-events-virtual-meetings-zoom tribe-events-virtual-meetings-zoom-controls col-12 col-md flex-md-grow-0 flex-md-shrink-1 d-flex flex-column align-items-center mt-2"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__or-label"', 'class="tribe-events-virtual-meetings-zoom__or-label mb-2"', $output );
	$output = str_replace( 'class="button tribe-events-virtual-meetings-zoom__create-link"', 'class="button tribe-events-virtual-meetings-zoom__create-link btn btn-secondary text-nowrap"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__connect-link"', 'class="tribe-events-virtual-meetings-zoom__connect-link btn btn-secondary text-nowrap"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_controls_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_controls_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_accounts_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details"', 'class="tribe-events-virtual-meetings-zoom-details col-12 col-md flex-md-grow-0 flex-md-shrink-1 d-flex flex-column align-items-center mt-2"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__or-label"', 'class="tribe-events-virtual-meetings-zoom-details__or-label mb-2"', $output );
	$output = str_replace( 'class="tribe-dependent tribe-events-virtual-meetings-zoom-details__generate-zoom-button button"', 'class="tribe-dependent tribe-events-virtual-meetings-zoom-details__generate-zoom-button button btn btn-secondary text-nowrap"', $output );
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tribe-dependent tribe-events-virtual-meetings-zoom-details__inner tribe-events-virtual-meetings-zoom-details__inner-accounts"', 'class="tribe-dependent tribe-events-virtual-meetings-zoom-details__inner tribe-events-virtual-meetings-zoom-details__inner-accounts modal-content"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-zoom-details__inner tribe-events-virtual-meetings-zoom-details__inner-accounts"', 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-zoom-details__inner tribe-events-virtual-meetings-zoom-details__inner-accounts modal-content"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__remove-link"', 'class="tribe-events-virtual-meetings-zoom-details__remove-link btn-close order-1"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__title"', 'class="tribe-events-virtual-meetings-zoom-details__title modal-title h5"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-control tribe-events-virtual-meetings-zoom-control--select"', 'class="tribe-events-virtual-meetings-zoom-control tribe-events-virtual-meetings-zoom-control--select modal-body"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-control__label"', 'class="tribe-events-virtual-meetings-zoom-control__label form-label"', $output );
	$output = str_replace( 'class="tribe-dropdown tribe-events-virtual-meetings-zoom__account-dropdown"', 'class="tribe-dropdown tribe-events-virtual-meetings-zoom__account-dropdown form-select"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__create-link-wrapper"', 'class="tribe-events-virtual-meetings-zoom-details__create-link-wrapper modal-footer"', $output );
	$output = str_replace( 'class="button tribe-events-virtual-meetings-zoom-details__account-select-link"', 'class="button tribe-events-virtual-meetings-zoom-details__account-select-link btn btn-secondary"', $output );

	$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom-details__remove-link ' );
	if ( false !== $offset ) {
		$start  = strpos( $output, '>', $offset ) + 1;
		$end    = strpos( $output, '</a>', $start );
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );

		$offset = strrpos( $output, '<a', -$offset );
		$output = substr_replace( $output, '<div class="modal-header">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom-details__title ', $offset );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_accounts_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_accounts_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_setup_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details"', 'class="tribe-events-virtual-meetings-zoom-details col-12 col-md flex-md-grow-0 flex-md-shrink-1 d-flex flex-column align-items-center mt-2"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__or-label"', 'class="tribe-events-virtual-meetings-zoom-details__or-label mb-2"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__inner"', 'class="tribe-events-virtual-meetings-zoom-details__inner modal-content"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-zoom-details__inner"', 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-zoom-details__inner modal-content"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__remove-link"', 'class="tribe-events-virtual-meetings-zoom-details__remove-link btn-close order-1"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__title"', 'class="tribe-events-virtual-meetings-zoom-details__title modal-title h5"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__account"', 'class="tribe-events-virtual-meetings-zoom__account mb-3"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-control tribe-events-virtual-meetings-zoom-control--select"', 'class="tribe-events-virtual-meetings-zoom-control tribe-events-virtual-meetings-zoom-control--select mb-3"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-control__label"', 'class="tribe-events-virtual-meetings-zoom-control__label form-label"', $output );
	$output = str_replace( 'class="tribe-dropdown ', 'class="tribe-dropdown form-select ', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__create-link-wrapper"', 'class="tribe-events-virtual-meetings-zoom-details__create-link-wrapper modal-footer"', $output );
	$output = str_replace( 'class="button tribe-events-virtual-meetings-zoom-details__create-link"', 'class="button tribe-events-virtual-meetings-zoom-details__create-link btn btn-secondary"', $output );

	$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom-details__remove-link ' );
	if ( false !== $offset ) {
		$start  = strpos( $output, '>', $offset ) + 1;
		$end    = strpos( $output, '</a>', $start );
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );

		$offset = strrpos( $output, '<a', -$offset );
		$output = substr_replace( $output, '<div class="modal-header">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom-details__title ', $offset );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="modal-body">', $offset + 6, 0 );
		$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom-details__create-link-wrapper ', $offset );
		$offset = strrpos( $output, '</div>', $offset - strlen( $output ) );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
	}

	/**
	 * Backwards compatibility
	 */
 	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-control tribe-events-virtual-meetings-zoom-control--radio"', 'class="tribe-events-virtual-meetings-zoom-control tribe-events-virtual-meetings-zoom-control--radio form-check"', $output );

	$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom-control tribe-events-virtual-meetings-zoom-control--radio"' );
	while ( false !== $offset ) {
		$output = enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_components_radio_output( $output );
		$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom-control tribe-events-virtual-meetings-zoom-control--radio"' );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_setup_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_setup_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_multiple_controls_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom tribe-events-virtual-meetings-zoom-controls tribe-events-virtual-meetings-zoom-controls--multi"', 'class="tribe-events-virtual-meetings-zoom tribe-events-virtual-meetings-zoom-controls tribe-events-virtual-meetings-zoom-controls--multi col-12 col-md flex-md-grow-0 flex-md-shrink-1 d-flex align-items-center mt-2 mt-md-0"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__or-label"', 'class="tribe-events-virtual-meetings-zoom__or-label me-2"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-controls__accordion-wrapper"', 'class="tribe-events-virtual-meetings-zoom-controls__accordion-wrapper dropdown"', $output );
	$output = str_replace( 'class="button tribe-events-virtual-meetings-zoom-controls__accordion-element tribe-events-virtual-meetings-zoom-controls__accordion-toggle"', 'class="button tribe-events-virtual-meetings-zoom-controls__accordion-element tribe-events-virtual-meetings-zoom-controls__accordion-toggle btn btn-secondary dropdown-toggle text-nowrap"', $output );
	$output = str_replace( 'data-js="tribe-events-accordion-trigger"', 'data-bs-toggle="dropdown" aria-haspopup="true"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-controls__accordion-element tribe-events-virtual-meetings-zoom-controls__accordion-contents"', 'class="tribe-events-virtual-meetings-zoom-controls__accordion-element tribe-events-virtual-meetings-zoom-controls__accordion-contents dropdown-menu dropdown-menu-end"', $output );
	$output = str_replace( '<ul>', '<ul class="list-unstyled mb-0">', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__create-link tribe-events-virtual-meetings-zoom__create-link--multi"', 'class="tribe-events-virtual-meetings-zoom__create-link tribe-events-virtual-meetings-zoom__create-link--multi dropdown-item"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_multiple_controls_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_multiple_controls_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_account_disabled_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	return str_replace( 'class="tribe-events-virtual-meetings-zoom-details"', 'class="tribe-events-virtual-meetings-zoom-details mt-3"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_account_disabled_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_account_disabled_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_account_disabled_details_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-message"', 'class="tribe-events-virtual-meetings-zoom-message alert alert-danger"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-error__details-header"', 'class="tribe-events-virtual-meetings-zoom-error__details-header alert-heading h4"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-error__link-wrapper"', 'class="tribe-events-virtual-meetings-zoom-error__link-wrapper mt-3"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-error__link-connect"', 'class="tribe-events-virtual-meetings-zoom-error__link-connect btn btn-danger"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_account_disabled_details_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_account_disabled_details_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_details_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$offset = strpos( $output, 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-zoom-details__inner"' );
	if ( false === $offset ) {
		$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom-details__inner"' );
	}
	if ( false !== $offset ) {
		$offset  = strpos( $output, '>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="modal-header">', $offset + 1, 0 );
		$offset  = strpos( $output, '<div class="tribe-events-virtual-meetings-zoom__title">', $offset );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="modal-body">', $offset + 6, 0 );
		$output .= "\n" . '</div>';
	}

	$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom-details__remove-link"' );
	if ( false !== $offset ) {
		$start  = strpos( $output, '>', $offset ) + 1;
		$end    = strpos( $output, '</a>', $start );
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	$offset = strpos( $output, '<svg  class="tribe-events-virtual-icon tribe-events-virtual-icon--video tribe-events-virtual-meeting-zoom__icon tribe-events-virtual-meeting-zoom__icon--video"' );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '</svg>', $start ) + 6;
		$length = $end - $start;
		$output = substr_replace( $output, '<i class="tribe-events-virtual-icon tribe-events-virtual-icon--video tribe-events-virtual-meeting-zoom__icon tribe-events-virtual-meeting-zoom__icon--video fas fa-video fa-2x" role="presentation" aria-hidden="true"></i>', $start, $length );
	}

	$offset = strpos( $output, '<svg  class="tribe-events-virtual-icon tribe-events-virtual-icon--phone tribe-events-virtual-meeting-zoom__icon tribe-events-virtual-meeting-zoom__icon--phone"' );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '</svg>', $start ) + 6;
		$length = $end - $start;
		$output = substr_replace( $output, '<i class="tribe-events-virtual-icon tribe-events-virtual-icon--phone tribe-events-virtual-meeting-zoom__icon tribe-events-virtual-meeting-zoom__icon--phone fas fa-phone fa-2x" role="presentation" aria-hidden="true"></i>', $start, $length );
	}

	$output = str_replace( 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-zoom-details__inner"', 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-zoom-details__inner modal-content"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__inner"', 'class="tribe-events-virtual-meetings-zoom-details__inner modal-content"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details"', 'class="tribe-events-virtual-meetings-zoom-details col-12 mt-2"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__remove-link"', 'class="tribe-events-virtual-meetings-zoom-details__remove-link btn-close order-1"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__title"', 'class="tribe-events-virtual-meetings-zoom__title modal-title h5"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__host"', 'class="tribe-events-virtual-meetings-zoom__host mb-3"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__alternative-host"', 'class="tribe-events-virtual-meetings-zoom__alternative-host mb-3"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__url-wrapper"', 'class="tribe-events-virtual-meetings-zoom__url-wrapper d-flex align-items-start"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__url"', 'class="tribe-events-virtual-meetings-zoom__url ms-3"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__phone-wrapper"', 'class="tribe-events-virtual-meetings-zoom__phone-wrapper d-flex align-items-start mt-3"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__phone-list"', 'class="tribe-events-virtual-meetings-zoom__phone-list list-unstyled ms-3 my-0"', $output );

	/**
	 * Backwards compatibility
	 */
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-control__label"', 'class="tribe-events-virtual-meetings-zoom-control__label form-label"', $output );
	$output = str_replace( 'class="tribe-dropdown ', 'class="tribe-dropdown form-select ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_details_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_details_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_meeting_link_error_details_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$offset = strpos( $output, 'id="tribe-events-virtual-meetings-zoom"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="modal-content">' . "\n" . '<div class="modal-header">', $offset + 1, 0 );
		$offset  = strpos( $output, '<div class="tribe-events-virtual-meetings-zoom-error__title">', $offset );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="modal-body">', $offset + 6, 0 );
		$output .= '</div>' . "\n" . '</div>';
	}

	$offset = strpos( $output, 'class="tribe-events-virtual-meetings-zoom-details__remove-link"' );
	if ( false !== $offset ) {
		$start  = strpos( $output, '>', $offset ) + 1;
		$end    = strpos( $output, '</a>', $start );
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-error"', 'class="tribe-events-virtual-meetings-zoom-error col-12 mt-2"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom__remove-link"', 'class="tribe-events-virtual-meetings-zoom__remove-link btn-close order-1"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-details__remove-link"', 'class="tribe-events-virtual-meetings-zoom-details__remove-link btn-close order-1"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-error__title"', 'class="tribe-events-virtual-meetings-zoom-error__title modal-title h5"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-error__message"', 'class="tribe-events-virtual-meetings-zoom-error__message alert alert-danger"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-error__details-header"', 'class="tribe-events-virtual-meetings-zoom-error__details-header fw-bold"', $output );
	$output = str_replace( 'class="button button-secondary tribe-events-virtual-meetings-zoom__create-link"', 'class="button button-secondary tribe-events-virtual-meetings-zoom__create-link btn btn-secondary"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-zoom-error__link-connect"', 'class="tribe-events-virtual-meetings-zoom-error__link-connect btn btn-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_meeting_link_error_details_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_meeting_link_error_details_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_display_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$output = str_replace( 'class="tribe-events-virtual-display"', 'class="tribe-events-virtual-display row"', $output );
	$output = str_replace( 'class="tribe-table-field-label tribe-events-virtual-display__label"', 'class="tribe-table-field-label tribe-events-virtual-display__label d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( 'class="tribe-table-field--top tribe-events-virtual-display__content"', 'class="tribe-table-field--top tribe-events-virtual-display__content d-flex col-12 col-md-9 align-items-start py-0"', $output );
	$output = str_replace( 'class="tribe-events-virtual-display__list"', 'class="tribe-events-virtual-display__list list-unstyled"', $output );

	$offset = strpos( $output, sprintf( 'for="%s"', esc_attr( sprintf( '%s-embed-video', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = strrpos( $output, '<label', $offset - strlen( $output ) );
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-embed-video', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	$offset = strpos( $output, sprintf( 'for="%s"', esc_attr( sprintf( '%s-linked-button', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = strrpos( $output, '<label', $offset - strlen( $output ) );
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$label  = substr( $output, $start, $length );
		$label  = str_replace( 'class="tribe-events-virtual-display__linked-button-label"', 'class="tribe-events-virtual-display__linked-button-label form-check-label"', $label );
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, $label, $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	$offset = strpos( $output, 'class="tribe-dependent tribe-events-virtual-display__linked-button-text-wrapper"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="input-group mt-2">' . "\n" . '', $offset + 1, 0 );
		$offset = strpos( $output, 'class="tribe-events-virtual-display__linked-button-text-label"', $offset );
		$output = substr_replace( $output, ' input-group-text', $offset + 61, 0 );
		$offset = strpos( $output, 'class="tribe-events-virtual-display__linked-button-text-input components-text-control__input"', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 92, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 1, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_display_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_display_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_api_display_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$offset = strpos( $output, sprintf( 'for="%s"', esc_attr( sprintf( '%s-meetings-api-display-details', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = strrpos( $output, '<label', $offset - strlen( $output ) );
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-meetings-api-display-details', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_api_display_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_api_display_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_api_accounts_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tec-events-virtual-meetings-video-source__inner tec-events-virtual-meetings-api-details__inner tec-events-virtual-meetings-api-details__inner-accounts"', 'class="tec-events-virtual-meetings-video-source__inner tec-events-virtual-meetings-api-details__inner tec-events-virtual-meetings-api-details__inner-accounts modal-content"', $output );
	$output = str_replace( 'class="tec-events-virtual-meetings-api-details__title"', 'class="tec-events-virtual-meetings-api-details__title modal-title h5"', $output );
	$output = str_replace( 'class="tec-events-virtual-meetings-api-details__create-link-wrapper"', 'class="tec-events-virtual-meetings-api-details__create-link-wrapper modal-footer"', $output );
	$output = str_replace( 'class="button tec-events-virtual-meetings-api-action__account-select-link"', 'class="button tec-events-virtual-meetings-api-action__account-select-link btn btn-secondary"', $output );

	$offset = strpos( $output, 'class="tec-events-virtual-meetings-api-details__remove-link"' );
	if ( false !== $offset ) {
		$start  = strrpos( $output, '<a', $offset - strlen( $output ) );
		$end    = strpos( $output, '</a>', $offset ) + 4;
		$length = $end - $start;
		$close  = substr( $output, $start, $length );
		$output = substr_replace( $output, '', $start, $length );

		$close  = str_replace( 'class="tec-events-virtual-meetings-api-details__remove-link"', 'class="tec-events-virtual-meetings-api-details__remove-link btn-close"', $close );
		$start  = strpos( $close, '>' ) + 1;
		$end    = strpos( $close, '<', $start );
		$length = $end - $start;
		$close  = substr_replace( $close, '', $start, $length );
	}

	$offset = strpos( $output, '<div class="tec-events-virtual-meetings-api-details__title ' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="modal-header">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . $close . "\n" . '</div>' . "\n" . '<div class="modal-body">', $offset + 6, 0 );
		$offset = strpos( $output, '<span class="tec-events-virtual-meetings-api-details__create-link-wrapper ', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_api_accounts_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_api_accounts_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_api_setup_link_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	return str_replace(
		sprintf( 'class="tribe-events-virtual-meetings-%s__connect-link"', esc_attr( $context['api_id'] ) ),
		sprintf( 'class="tribe-events-virtual-meetings-%s__connect-link btn btn-secondary"', esc_attr( $context['api_id'] ) ),
		$output
	);
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_api_setup_link_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_api_setup_link_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_autodetect_controls_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-dependent tribe-events-virtual-video-autodetect-details"', 'class="tribe-dependent tribe-events-virtual-video-autodetect-details modal-content"', $output );
	$output = str_replace( 'class="tec-events-virtual-meetings-video-source__inner tribe-events-virtual-video-source-autodetect__inner-controls"', 'class="tec-events-virtual-meetings-video-source__inner tribe-events-virtual-video-source-autodetect__inner-controls modal-body"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-video-source__title tribe-events-virtual-video-source-autodetect__title"', 'class="tribe-events-virtual-meetings-video-source__title tribe-events-virtual-video-source-autodetect__title form-label"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_autodetect_controls_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_autodetect_controls_output' );

function enlightenment_tribe_bootstrap_template_components_message_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	if ( empty( $context['type'] ) ) {
		return $output;
	}

	$message_classes = [ 'tec-events-virtual-settings-message__wrap' ];
	if ( ! empty( $context['add_classes'] ) ) {
		array_push( $message_classes, $context['add_classes'] );
	}

	array_push( $message_classes, $context['type'] );

	switch ( $context['type'] ) {
		case 'updated':
			$type = 'success';

			break;

		case 'error':
			$type = 'danger';

			break;

		case 'standard':
		default:
			$type = 'info';
	}

	$output = str_replace(
		sprintf( ' %s"', sanitize_html_class( $context['type'] ) ),
		sprintf( ' %s alert alert-%s"', sanitize_html_class( $context['type'] ), $type ),
		$output
	);

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_message_output', 'enlightenment_tribe_bootstrap_template_components_message_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_text_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$classes_obj = new \Tribe\Utils\Element_Classes( $context['classes_wrap'] );
	$classes_str = $classes_obj->get_attribute();

	$output = str_replace(
		$classes_str,
		str_replace( 'class="', 'class="mb-3 ', $classes_str ),
		$output
	);

	$output = str_replace( 'class="screen-reader-text tec-events-virtual-meetings-control__label"', 'class="screen-reader-text tec-events-virtual-meetings-control__label visually-hidden"', $output );

	$classes_obj = new \Tribe\Utils\Element_Classes( $context['classes_input'] );
	$classes_str = $classes_obj->get_attribute();

	$output = str_replace(
		$classes_str,
		str_replace( 'class="', 'class="form-control ', $classes_str ),
		$output
	);

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_text_output', 'enlightenment_tribe_bootstrap_template_components_text_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_dropdown_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tec-events-virtual-meetings-control tec-events-virtual-meetings-control--select ', 'class="tec-events-virtual-meetings-control tec-events-virtual-meetings-control--select mb-3 ', $output );
	$output = str_replace( 'class="tec-events-virtual-meetings-control tec-events-virtual-meetings-control--select"', 'class="tec-events-virtual-meetings-control tec-events-virtual-meetings-control--select mb-3"', $output );
	$output = str_replace( 'class="tribe-dropdown ', 'class="tribe-dropdown form-select ', $output );

	$offset = strpos( $output, '</label><span class="tribe-tooltip event-helper-text ' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, '<label', $offset - strlen( $output ) );
		$output = substr_replace( $output, '<span class="form-label d-inline-block">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, ' ', $offset + 8, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, '</span>', $offset + 7, 0 );
	} else {
		$output = str_replace( 'class="tec-events-virtual-meetings-control__label ', 'class="tec-events-virtual-meetings-control__label form-label ', $output );
		$output = str_replace( 'class="tec-events-virtual-meetings-control__label"', 'class="tec-events-virtual-meetings-control__label form-label"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_dropdown_output', 'enlightenment_tribe_bootstrap_template_components_dropdown_output' );

function enlightenment_tribe_bootstrap_template_components_tooltip_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$wrap_classes = array( 'tribe-tooltip', 'event-helper-text' );
	if ( ! empty( $context['classes_wrap'] ) ) {
		$wrap_classes = array_merge( $wrap_classes, $context['classes_wrap'] );
	}

	$output = sprintf(
		'<span class="%s"><i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="%s"></i></span>',
		esc_attr( join( ' ', $wrap_classes ) ),
		wp_kses(
			$context['message'],
			array( 'a' => array( 'href' => array() ) )
		)
	);

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_tooltip_output', 'enlightenment_tribe_bootstrap_template_components_tooltip_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_autodetect_components_button_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	return str_replace( 'class="button tribe-events-virtual-video-source-autodetect__button"', 'class="button tribe-events-virtual-video-source-autodetect__button btn btn-secondary"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_autodetect_components_button_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_autodetect_components_button_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_autodetect_video_preview_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	return str_replace( 'class="tec-autodetect-video-preview__container hide-preview"', 'class="tec-autodetect-video-preview__container hide-preview mt-3"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_autodetect_video_preview_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_autodetect_video_preview_output' );

function enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_display_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$offset = strpos( $output, sprintf( '<label for="%s">', esc_attr( sprintf( '%s-meetings-zoom-display-details', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-meetings-zoom-display-details', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_zoom_display_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_zoom_display_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_show_when_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$output = str_replace( 'class="tribe-events-virtual-show"', 'class="tribe-events-virtual-show row"', $output );
	$output = str_replace( "class='tribe-table-field-label'", 'class="tribe-table-field-label d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( '<td>', '<td class="d-block col-12 col-md-9 d-flex align-items-start py-0">', $output );
	$output = str_replace( '<ul>', '<ul class="list-unstyled">', $output );

	$offset = strpos( $output, sprintf( '<label for="%s">', esc_attr( sprintf( '%s-show-immediately', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-show-immediately', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	$tooltip = '';
	$offset  = strpos( $output, 'class="tribe-tooltip event-helper-text tribe-events-virtual-show-helper-text"' );
	if ( false !== $offset ) {
		$start   = strpos( $output, '<p>', $offset ) + 3;
		$end     = strpos( $output, '</p>', $start );
		$length  = $end - $start;
		$tooltip = substr( $output, $start, $length );
		$tooltip = trim( $tooltip );

		$offset  = strpos( $output, 'class="tribe-tooltip event-helper-text tribe-events-virtual-show-helper-text"' );
		$start   = strrpos( $output, '<div', $offset - strlen( $output ) );
		$end     = strpos( $output, '</div>', $start ) + 6;
		$end     = strpos( $output, '</div>', $end ) + 6;
		$length  = $end - $start;
		$output  = substr_replace( $output, '', $start, $length );
	}

	$offset = strpos( $output, sprintf( '<label for="%s">', esc_attr( sprintf( '%s-show-at-start', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-show-at-start', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );

		if ( ! empty( $tooltip ) ) {
			$output = substr_replace( $output, sprintf( '<span class="tribe-tooltip event-helper-text tribe-events-virtual-show-helper-text" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="%s"><i class="fas fa-info-circle" aria-hidden="true" role="presentation"></i><span class="screen-reader-text visually-hidden">%s</span></span>', esc_attr( $tooltip ), _x( 'Info', 'Event starts tooltip screen reader text', 'enlightenment' ) ) . "\n", $offset, 0 );
			$offset = strpos( $output, '</label>', $offset );
		}

		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_show_when_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_show_when_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_show_to_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$output = str_replace( 'class="tribe-events-virtual-show"', 'class="tribe-events-virtual-show row"', $output );
	$output = str_replace( "class='tribe-table-field-label'", 'class="tribe-table-field-label d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( '<td>', '<td class="d-block col-12 col-md-9 d-flex align-items-start py-0">', $output );
	$output = str_replace( '<ul>', '<ul class="list-unstyled">', $output );

	$offset = strpos( $output, sprintf( '<label for="%s">', esc_attr( sprintf( '%s-show-to-all', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-show-to-all', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	$offset = strpos( $output, sprintf( '<label for="%s">', esc_attr( sprintf( '%s-show-to-logged-in', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-show-to-logged-in', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_show_to_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_show_to_output', 10, 4 );
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_compatibility_event_tickets_show_to_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_show_to_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_compatibility_event_tickets_show_to_rsvp_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$tooltip = '';
	$offset  = strpos( $output, 'class="tribe-tooltip event-helper-text tribe-events-virtual-show-to-ticket-attendees-helper-text"' );
	if ( false !== $offset ) {
		$start   = strpos( $output, '<p>', $offset ) + 3;
		$end     = strpos( $output, '</p>', $start );
		$length  = $end - $start;
		$tooltip = substr( $output, $start, $length );
		$tooltip = trim( $tooltip );

		$start   = strpos( $output, '<div class="tribe-tooltip event-helper-text tribe-events-virtual-show-to-ticket-attendees-helper-text"' );
		$end     = strpos( $output, '</div>', $start ) + 6;
		$end     = strpos( $output, '</div>', $end ) + 6;
		$length  = $end - $start;
		$output  = substr_replace( $output, '', $start, $length );
	}

	$offset = strpos( $output, sprintf( 'for="%s"', esc_attr( sprintf( '%s-show-to-rsvp-attendees', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = strrpos( $output, '<label', $offset - strlen( $output ) );;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-show-to-rsvp-attendees', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );

		if ( ! empty( $tooltip ) ) {
			$output = substr_replace( $output, sprintf( '<span class="tribe-tooltip event-helper-text tribe-events-virtual-show-to-ticket-attendees-helper-text" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" data-bs-trigger="hover focus click" data-bs-title="%s"><i class="fas fa-info-circle" aria-hidden="true" role="presentation"></i><span class="screen-reader-text visually-hidden">%s</span></span>', esc_html( $tooltip ), _x( 'Info', 'RSVP Attendees only login requirements', 'enlightenment' ) ) . "\n", $offset, 0 );
			$offset = strpos( $output, '</label>', $offset );
		}

		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_compatibility_event_tickets_show_to_rsvp_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_compatibility_event_tickets_show_to_rsvp_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_compatibility_event_tickets_show_to_tickets_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$tooltip = '';
	$offset  = strpos( $output, 'class="tribe-tooltip event-helper-text tribe-events-virtual-show-to-ticket-attendees-helper-text"' );
	if ( false !== $offset ) {
		$start   = strpos( $output, '<p>', $offset ) + 3;
		$end     = strpos( $output, '</p>', $start );
		$length  = $end - $start;
		$tooltip = substr( $output, $start, $length );
		$tooltip = trim( $tooltip );

		$start   = strpos( $output, '<div class="tribe-tooltip event-helper-text tribe-events-virtual-show-to-ticket-attendees-helper-text"' );
		$end     = strpos( $output, '</div>', $start ) + 6;
		$end     = strpos( $output, '</div>', $end ) + 6;
		$length  = $end - $start;
		$output  = substr_replace( $output, '', $start, $length );
	}

	$offset = strpos( $output, sprintf( 'for="%s"', esc_attr( sprintf( '%s-show-to-ticket-attendees', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = strrpos( $output, '<label', $offset - strlen( $output ) );;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-show-to-ticket-attendees', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );

		if ( ! empty( $tooltip ) ) {
			$output = substr_replace( $output, sprintf( '<span class="tribe-tooltip event-helper-text tribe-events-virtual-show-to-ticket-attendees-helper-text" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" data-bs-trigger="hover focus click" data-bs-title="%s"><i class="fas fa-info-circle" aria-hidden="true" role="presentation"></i><span class="screen-reader-text visually-hidden">%s</span></span>', esc_html( $tooltip ), _x( 'Info', 'Ticket Attendees only login requirements', 'enlightenment' ) ) . "\n", $offset, 0 );
			$offset = strpos( $output, '</label>', $offset );
		}

		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_compatibility_event_tickets_show_to_tickets_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_compatibility_event_tickets_show_to_tickets_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_label_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$output = str_replace( '<tr>', '<tr class="tribe-events-virtual-show row">', $output );
	$output = str_replace( "class='tribe-table-field-label'", 'class="tribe-table-field-label d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( '<td>', '<td class="d-flex col-12 col-md-9 align-items-start py-0">', $output );
	$output = str_replace( '<ul>', '<ul class="list-unstyled mb-0">', $output );
	$output = str_replace( 'class="event-helper-text"', 'class="event-helper-text mb-0"', $output );

	$offset = strpos( $output, sprintf( '<label for="%s">', esc_attr( sprintf( '%s-show-on-event', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-show-on-event', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	$offset = strpos( $output, sprintf( '<label for="%s">', esc_attr( sprintf( '%s-show-on-views', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-show-on-views', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_label_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_label_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_compatibility_event_tickets_share_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$output = str_replace( '<tr>', '<tr class="tribe-events-virtual-show row">', $output );
	$output = str_replace( "class='tribe-table-field-label'", 'class="tribe-table-field-label d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( '<td>', '<td class="d-flex col-12 col-md-9 align-items-start py-0">', $output );
	$output = str_replace( '<ul>', '<ul class="list-unstyled">', $output );
	$output = str_replace( 'class="event-helper-text"', 'class="event-helper-text mb-0"', $output );

	$offset = strpos( $output, sprintf( '<label for="%s">', esc_attr( sprintf( '%s-rsvp-email-link', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-rsvp-email-link', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_compatibility_event_tickets_share_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_compatibility_event_tickets_share_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_container_compatibility_event_tickets_share_tickets_output( $output, $template_name, $file, $template ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$context = $template->get_values();

	$offset = strpos( $output, sprintf( '<label for="%s">', esc_attr( sprintf( '%s-ticket-email-link', $context['metabox_id'] ) ) ) );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '<div class="form-check">', $start, $length );

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, "\n" . 'class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', esc_attr( sprintf( '%s-ticket-email-link', $context['metabox_id'] ) ) ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_container_compatibility_event_tickets_share_tickets_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_container_compatibility_event_tickets_share_tickets_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_virtual_metabox_video_input_output( $output ) {
	if ( isset( $_SERVER['HTTP_REFERER'] ) && false !== strpos( $_SERVER['HTTP_REFERER'], '/wp-admin/' ) ) {
		return $output;
	}

	$output = str_replace( 'class="tribe-dependent tribe-events-virtual-meetings-source-video"', 'class="tribe-dependent tribe-events-virtual-meetings-source-video card"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-video__inner"', 'class="tribe-events-virtual-meetings-video-source__inner tribe-events-virtual-meetings-source-video__inner card-body"', $output );
	$output = str_replace( 'class="tribe-events-virtual-meetings-video-source__title"', 'class="tribe-events-virtual-meetings-video-source__title card-title h5"', $output );
	$output = str_replace( '<ul>', '<ul class="list-unstyled w-100 mb-0">', $output );
	$output = str_replace( 'class="screen-reader-text tribe-events-virtual-video-source__virtual-url-input-label"', 'class="screen-reader-text tribe-events-virtual-video-source__virtual-url-input-label form-label visually-hidden"', $output );
	$output = str_replace( 'class="components-text-control__input tribe-events-virtual-video-source__virtual-url-input"', 'class="components-text-control__input tribe-events-virtual-video-source__virtual-url-input form-control"', $output );
	$output = str_replace( 'class="tribe-events-virtual-video-source__not-embeddable-notice ', 'class="tribe-events-virtual-video-source__not-embeddable-notice mt-3 ', $output );
	$output = str_replace( 'class="tribe-events-virtual-video-source__not-embeddable-notice"', 'class="tribe-events-virtual-video-source__not-embeddable-notice mt-3"', $output );
	$output = str_replace( 'class="tribe-events-virtual-video-source__not-embeddable-text event-helper-text"', 'class="tribe-events-virtual-video-source__not-embeddable-text event-helper-text alert alert-danger mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_virtual_metabox_video_input_output', 'enlightenment_tribe_bootstrap_template_virtual_metabox_video_input_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_venue_output( $output ) {
	$output = str_replace( 'class="tribe-section tribe-section-venue ', 'class="tribe-section tribe-section-venue card mb-3 ', $output );

	$offset = strpos( $output, 'class="tribe-section-header"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<h3 class="', $offset );
		$output  = substr_replace( $output, 'card-header h5 ', $offset + 11, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	$output = str_replace( 'class="tribe-section-content"', 'class="tribe-section-content d-block w-100"', $output );
	$output = str_replace( '<tbody>', '<tbody class="d-block">', $output );

	$output = str_replace( 'class="tribe-dropdown ', 'class="tribe-dropdown form-select ', $output );

	$offset = strpos( $output, 'class="saved-venue-table-cell"' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="d-flex align-items-center">', $offset + 4, 0 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );

		$offset = strpos( $output, 'class="saved-venue-table-cell"', $offset );
	}

	$offset = strpos( $output, 'class="saved-venue-table-cell"' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<label', $offset );
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );

		$offset = strpos( $output, 'class="saved-venue-table-cell"', $offset );
	}

	$offset = strpos( $output, "class='tribe-table-field-label'" );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );

		$offset = strpos( $output, "class='tribe-table-field-label'", $offset );
	}

	$offset = strpos( $output, 'class="saved-venue-table-cell"' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="d-block col-12 col-md-9 py-0"', $offset + 3, 0 );

		$offset = strpos( $output, 'class="saved-venue-table-cell"', $offset );
	}

	$offset = strpos( $output, 'class="linked-post venue"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="d-block col-12 col-md-3 py-0"', $offset + 3, 0 );
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="d-block col-12 col-md-9 py-0"', $offset + 3, 0 );
	}

	$offset = strpos( $output, "class='tribe-table-field-label'" );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="d-block col-12 col-md-9 py-0"', $offset + 3, 0 );

		$offset = strpos( $output, "class='tribe-table-field-label'", $offset );
	}

	$output = str_replace( 'class="saved-linked-post"', 'class="saved-linked-post row align-items-center"', $output );
	$output = str_replace( 'class="saved-venue-table-cell"', 'class="saved-venue-table-cell d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( 'class="new-tribe_venue"', 'class="new-tribe_venue d-block"', $output );
	$output = str_replace( 'class="linked-post venue ', 'class="linked-post venue row align-items-center mt-3 ', $output );
	$output = str_replace( 'class="linked-post venue"', 'class="linked-post venue row align-items-center mt-3"', $output );
	$output = str_replace( "class='tribe-table-field-label'", 'class="tribe-table-field-label d-block col-12 col-md-3 py-0"', $output );

	$output = str_replace( 'class="edit-linked-post-link"', 'class="edit-linked-post-link ms-3"', $output );
	$output = str_replace( 'class="dashicons dashicons-trash tribe-delete-this"', 'class="tribe-delete-this fas fa-trash-alt ms-auto"', $output );

	$output = str_replace( 'class="tribe-add-post tribe-button tribe-button-secondary"', 'class="tribe-add-post tribe-button tribe-button-secondary btn btn-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_integrations_the_events_calendar_modules_venue_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_venue_output' );
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_venue_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_venue_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_organizer_output( $output ) {
	$output = str_replace( 'class="tribe-section tribe-section-organizer ', 'class="tribe-section tribe-section-organizer card mb-3 ', $output );
	$output = str_replace( 'class="tribe-section tribe-section-organizer"', 'class="tribe-section tribe-section-organizer card mb-3"', $output );

	$offset = strpos( $output, 'class="tribe-section-header"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<h3 class="', $offset );
		$output  = substr_replace( $output, 'card-header h5 ', $offset + 11, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	$output = str_replace( 'class="tribe-section-content"', 'class="tribe-section-content d-block w-100"', $output );
	$output = str_replace( '<tbody>', '<tbody class="d-block mb-3">', $output );

	$output = str_replace( 'class="tribe-dropdown ', 'class="tribe-dropdown form-select ', $output );

	$offset = strpos( $output, 'class="saved-organizer-table-cell"' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="d-flex align-items-center">', $offset + 4, 0 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );

		$offset = strpos( $output, 'class="saved-organizer-table-cell"', $offset );
	}

	$offset = strpos( $output, 'class="saved-organizer-table-cell"' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<label', $offset );
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );

		$offset = strpos( $output, 'class="saved-organizer-table-cell"', $offset );
	}

	$offset = strpos( $output, 'class="saved-organizer-table-cell"' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="d-block col-12 col-md-9 py-0"', $offset + 3, 0 );

		$offset = strpos( $output, 'class="saved-organizer-table-cell"', $offset );
	}

	$offset = strpos( $output, 'class="linked-post organizer' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<input', $offset );
		$offset = strpos( $output, "class='", $offset );
		$output = substr_replace( $output, 'form-control ', $offset + 7, 0 );

		$offset = strpos( $output, 'class="linked-post organizer', $offset );
	}

	$offset = strpos( $output, 'class="linked-post organizer' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<td', $offset );

		$offset_a = strpos( $output, 'class="', $offset );
		$end_a    = strpos( $output, '>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, 'd-block col-12 col-md-3 py-0 ', $offset_a + 7, 0 );
		} else {
			$output = substr_replace( $output, ' class="d-block col-12 col-md-3 py-0"', $offset + 3, 0 );
		}

		$offset = strpos( $output, '>', $offset );
		$offset = strpos( $output, '<td', $offset );

		$offset_a = strpos( $output, 'class="', $offset );
		$end_a    = strpos( $output, '>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, 'd-block col-12 col-md-9 py-0 ', $offset_a + 7, 0 );
		} else {
			$output = substr_replace( $output, ' class="d-block col-12 col-md-9 py-0"', $offset + 3, 0 );
		}

		$offset = strpos( $output, 'class="linked-post organizer', $offset );
	}

	$output = str_replace( 'class="saved-linked-post"', 'class="saved-linked-post row align-items-center"', $output );
	$output = str_replace( 'class="saved-organizer-table-cell"', 'class="saved-organizer-table-cell d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( 'class="new-tribe_organizer"', 'class="new-tribe_organizer d-block mb-3"', $output );
	$output = str_replace( 'class="linked-post organizer ', 'class="linked-post organizer row align-items-center mt-3 ', $output );
	$output = str_replace( 'class="linked-post organizer"', 'class="linked-post organizer row align-items-center mt-3"', $output );

	$output = str_replace( '<p>', '<p class="form-text mt-2 mb-0">', $output );
	$output = str_replace( 'class="edit-linked-post-link"', 'class="edit-linked-post-link ms-3"', $output );
	$output = str_replace( 'class="dashicons dashicons-trash tribe-delete-this"', 'class="tribe-delete-this fas fa-trash-alt ms-auto"', $output );

	$offset = strpos( $output, '<tfoot>' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="d-block"', $offset + 6, 0 );
		$offset = strpos( $output, '<tr>', $offset );
		$output = substr_replace( $output, ' class="row"', $offset + 3, 0 );
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="d-none"', $offset + 3, 0 );
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, ' class="col-12 py-0"', $offset + 3, 0 );
	}

	$output = str_replace( 'class="tribe-add-post tribe-button tribe-button-secondary"', 'class="tribe-add-post tribe-button tribe-button-secondary btn btn-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_integrations_the_events_calendar_modules_organizer_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_organizer_output' );
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_organizer_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_organizer_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_website_output( $output ) {
	$output = str_replace( 'class="tribe-section tribe-section-website"', 'class="tribe-section tribe-section-website card mb-3"', $output );

	$offset = strpos( $output, 'class="tribe-section-header"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<h3>', $offset );
		$output  = substr_replace( $output, ' class="card-header h5"', $offset + 3, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	$output = str_replace( 'class="tribe-section-content"', 'class="tribe-section-content d-block w-100"', $output );
	$output = str_replace( '</colgroup>', '</colgroup>' . "\n" . '<tbody class="d-block">', $output );
	$output = str_replace( '</table>', '</tbody>' . "\n" . '</table>', $output );

	$output = str_replace( 'class="tribe-section-content-row"', 'class="tribe-section-content-row row align-items-start"', $output );
	$output = str_replace( 'class="tribe-section-content-label"', 'class="tribe-section-content-label d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( 'class="tribe-section-content-field"', 'class="tribe-section-content-field d-block col-12 col-md-9 py-0"', $output );

	$offset = strpos( $output, '<label' );
	if ( false !== $offset ) {
		$offset_a = strpos( $output, 'class="', $offset );
		$end_a    = strpos( $output, '>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, 'col-form-label ', $offset_a + 7, 0 );
		} else {
			$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
		}
	}

	$offset = strpos( $output, '<input' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="', $offset );
		$output = substr_replace( $output, 'form-control ', $offset + 7, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_integrations_the_events_calendar_modules_website_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_website_output' );
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_website_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_website_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_custom_output( $output ) {
	$output = str_replace( 'class="tribe-section tribe-section-custom-fields"', 'class="tribe-section tribe-section-custom-fields card mb-3"', $output );

	$offset = strpos( $output, 'class="tribe-section-header"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<h3>', $offset );
		$output  = substr_replace( $output, ' class="card-header h5"', $offset + 3, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_custom_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_custom_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_custom_table_output( $output ) {
	$output = str_replace( 'class="tribe-section-content"', 'class="tribe-section-content d-block w-100"', $output );
	$output = str_replace( '</colgroup>', '</colgroup>' . "\n" . '<tbody class="d-block">', $output );
	$output = str_replace( '</table>', '</tbody>' . "\n" . '</table>', $output );

	$offset  = strpos( $output, 'class="tribe-section-content-row ' );
	$did_one = false;
	while ( false !== $offset ) {
		if ( $did_one ) {
			$output  = substr_replace( $output, ' mt-3', $offset + 32, 0 );
		}

		$did_one = true;

		$offset  = strpos( $output, 'class="tribe-section-content-row ', $offset + 1 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_custom_table_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_custom_table_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_custom_table_row_output( $output ) {
	$output = str_replace( 'class="tribe-section-content-row ', 'class="tribe-section-content-row row align-items-start ', $output );
	$output = str_replace( 'class="tribe-section-content-label"', 'class="tribe-section-content-label d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( 'class="tribe-section-content-field"', 'class="tribe-section-content-field d-block col-12 col-md-9 py-0"', $output );

	$offset = strpos( $output, '<label' );
	if ( false !== $offset ) {
		$offset_a = strpos( $output, 'class="', $offset );
		$end_a    = strpos( $output, '>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, 'col-form-label ', $offset_a + 7, 0 );
		} else {
			$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_custom_table_row_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_custom_table_row_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_custom_fields_text_output( $output ) {
	return str_replace( 'class="', 'class="form-control ', $output );
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_custom_fields_text_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_custom_fields_text_output' );
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_custom_fields_textarea_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_custom_fields_text_output' );
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_custom_fields_url_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_custom_fields_text_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_custom_fields_input_option_output( $output ) {
	$offset = strpos( $output, '<label' );
	$end    = strpos( $output, '>', $offset ) + 1;
	$length = $end - $offset;
	$tag    = substr( $output, $offset, $length );

	$output = str_replace( $tag, '', $output );

	$tag    = str_replace( '<label', '<label class="form-check-label"', $tag );

	$offset = strpos( $output, '<input', $offset );
	$offset = strpos( $output, 'class="', $offset );
	$output = substr_replace( $output, 'form-check-input ', $offset + 7, 0 );

	$offset = strpos( $output, '> ', $offset );
	$output = substr_replace( $output, $tag, $offset + 2, 0 );

	$output = sprintf( '<div class="form-check">%s</div>', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_custom_fields_input_option_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_custom_fields_input_option_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_cost_output( $output ) {
	$output = str_replace( 'class="tribe-section tribe-section-cost"', 'class="tribe-section tribe-section-cost card mb-3"', $output );

	$offset = strpos( $output, 'class="tribe-section-header"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<h3>', $offset );
		$output  = substr_replace( $output, ' class="card-header h5"', $offset + 3, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	$output = str_replace( 'class="tribe-section-content"', 'class="tribe-section-content d-block w-100"', $output );
	$output = str_replace( '</colgroup>', '</colgroup>' . "\n" . '<tbody class="d-block">', $output );
	$output = str_replace( '</table>', '</tbody>' . "\n" . '</table>', $output );

	$offset = strpos( $output, 'class="tribe-section-content-row" style="display: none;"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<td class="tribe-section-content-field">', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="d-flex">', $offset + 40, 0 );
		$offset  = strpos( $output, '</td>', $offset );
		$output  = substr_replace( $output, '</div>' . "\n", $offset, 0 );
		$output .= '</div>';
	}

	$output = str_replace( 'class="tribe-section-content-row" style="display: none;"', 'class="tribe-section-content-row row align-items-start mb-3" style="display: none;"', $output );
	$output = str_replace( 'class="tribe-section-content-row"', 'class="tribe-section-content-row row align-items-start"', $output );
	$output = str_replace( 'class="tribe-section-content-label"', 'class="tribe-section-content-label d-block col-12 col-md-3 py-0"', $output );
	$output = str_replace( 'class="tribe-section-content-field"', 'class="tribe-section-content-field d-block col-12 col-md-9 py-0"', $output );

	$offset = strpos( $output, '<label' );
	while ( false !== $offset ) {
		$offset_a = strpos( $output, 'class="', $offset );
		$end_a    = strpos( $output, '>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, 'col-form-label ', $offset_a + 7, 0 );
		} else {
			$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
		}

		$offset = strpos( $output, '<label', $offset + 1 );
	}

	$output = str_replace( 'class="event-currency-symbol ', 'class="event-currency-symbol form-control ', $output );
	$output = str_replace( 'class="event-currency-symbol"', 'class="event-currency-symbol form-control"', $output );
	$output = str_replace( 'class="event-currency-position tribe-dropdown ', 'class="event-currency-position tribe-dropdown ms-2 ', $output );
	$output = str_replace( 'class="event-currency-position tribe-dropdown"', 'class="event-currency-position tribe-dropdown ms-2"', $output );
	$output = str_replace( 'class="cost-input-field"', 'class="cost-input-field form-control"', $output );
	$output = str_replace( '<p>', '<p class="form-text mb-0">', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_integrations_the_events_calendar_modules_cost_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_cost_output' );
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_cost_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_cost_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_spam_control_output( $output ) {
	return str_replace( 'class="aes"', 'class="aes d-none"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_spam_control_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_spam_control_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_captcha_output( $output ) {
	$output = str_replace( 'class="tribe-events-community-details eventForm bubble"', 'class="tribe-events-community-details eventForm bubble card mb-3"', $output );

	$offset = strpos( $output, 'class="tribe_sectionheader"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<h4>', $offset );
		$output = substr_replace( $output, ' class="card-header h5"', $offset + 3, 0 );
	}

	$offset = strpos( $output, '<span class="captcha">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output,  '<div class="card-body">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output,  "\n" . '</div>', $offset + 7, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_captcha_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_captcha_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_terms_output( $output ) {
	$output = str_replace( 'class="tribe-section tribe-section-terms"', 'class="tribe-section tribe-section-terms card mb-3"', $output );

	$offset = strpos( $output, '<div class="tribe-section-header">' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<h3', $offset );
		$output  = substr_replace( $output, ' class="card-header h5"', $offset + 3, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	$output = str_replace( 'class="event-terms-description"', 'class="event-terms-description form-control"', $output );

	$output = str_replace( '<br />', '', $output );
	$output = str_replace( '<br/>',  '', $output );

	$offset = strpos( $output, 'class="tribe-section-content"' );
	if ( false !== $offset ) {
		$offset_a = strpos( $output, '<textarea', $offset );
		if ( false !== $offset_a ) {
			$output   = substr_replace( $output, '<div class="mb-3">', $offset_a, 0 );
			$offset_a = strpos( $output, '</textarea>', $offset_a );
			$output   = substr_replace( $output, '</div>', $offset_a + 11, 0 );
		}

		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="form-check">', $offset, 0 );
		$offset = strpos( $output, '<input', $offset );
		$offset = strpos( $output, 'class="', $offset );
		$output = substr_replace( $output, 'form-check-input ', $offset + 7, 0 );
		$offset = strpos( $output, '<label', $offset );
		$offset = strpos( $output, 'class="', $offset );
		$output = substr_replace( $output, 'form-check-label ', $offset + 7, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_terms_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_terms_output' );

function enlightenment_tribe_bootstrap_template_part_community_modules_submit_output( $output ) {
	return str_replace( 'class="tribe-button submit events-community-submit"', 'class="tribe-button submit events-community-submit btn btn-primary btn-lg"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_submit_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_submit_output' );

function enlightenment_tribe_bootstrap_community_edit_venue_template( $output ) {
	$output = str_replace( 'class="events-community-post-title"', 'class="events-community-post-title mb-3"', $output );
	$output = str_replace( '<label for="post_title">', '<label for="post_title" class="form-label h5">', $output );
	$output = str_replace( 'name="post_title"', 'name="post_title" class="form-control form-control-lg"', $output );
	$output = str_replace( 'class="events-community-post-content"', 'class="events-community-post-content mb-3"', $output );
	$output = str_replace( '<label for="post_content">', '<label for="post_content" class="form-label h5">', $output );
	$output = str_replace( '<textarea name="tcepostcontent">', '<textarea name="tcepostcontent" class="form-control">', $output );
	$output = str_replace( 'class="button submit events-community-submit"', 'class="button submit events-community-submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_community_edit_venue_template', 'enlightenment_tribe_bootstrap_community_edit_venue_template' );

function enlightenment_tribe_bootstrap_template_part_community_modules_venue_fields_output( $output ) {
	$output = str_replace( 'class="tribe-events-community-details eventForm bubble"', 'class="tribe-events-community-details eventForm bubble card mb-3"', $output );
	$output = str_replace( '<h4>', '<h4 class="card-header h5">', $output );
	$output = str_replace( '<label class="', '<label class="mb-0 ', $output );

	$offset = strpos( $output, '<div class="tribe_sectionheader">' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	/*$offset = strpos( $output, '<div class="venue">' );
	while ( false !== $offset ) {
		$end = strpos( $output, '</div>', $offset );

		$offset_a = strpos( $output, '<input', $offset );
		if ( false !== $offset_a && $offset_a < $end ) {
			$offset_a = strpos( $output, 'type="', $offset_a );
			$output   = substr_replace( $output, 'class="form-control" ', $offset_a, 0 );
		}

		$offset = strpos( $output, '<div class="venue">', $end );
	}*/

	$output = str_replace( 'type="text"', 'type="text" class="form-control"', $output );
	$output = str_replace( 'type="tel"',  'type="tel" class="form-control"',  $output );
	$output = str_replace( 'type="url"',  'type="url" class="form-control"',  $output );
	$output = str_replace( 'class="tribe-dropdown"', 'class="tribe-dropdown form-select"', $output );

	$offset = strpos( $output, '<div class="venue">' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, '<div class="col-lg-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="col-lg-9">', $offset + 8, 0 );
		$offset = strpos( $output, '<div class="col-lg-9">', $offset );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );

		$offset = strpos( $output, '<div class="venue">', $offset );
	}

	$offset = strpos( $output, 'type="checkbox"' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strrpos( $output, '<div class="venue">', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' form-check', $offset + 17, 0 );
		$offset = strpos( $output, '<label', $offset );
		$output = substr_replace( $output, ' class="form-check-label"', $offset + 6, 0 );
		$offset = strpos( $output, '/>', $offset );

		$offset = strpos( $output, 'type="checkbox"', $offset );
	}

	$output = str_replace( 'class="venue ', 'class="venue mb-3 row gx-2 align-items-center ', $output );
	$output = str_replace( 'class="venue"', 'class="venue mb-3 row gx-2 align-items-center"', $output );
	$output = str_replace( '<label for="', '<label class="mb-0" for="', $output );

	$output .= "
<script>
( function() {
	const countrySelect = document.getElementById( 'EventCountry' );
	const provinceInput = document.getElementById( 'StateProvinceText' );
	const stateSelect   = document.getElementById( 'StateProvinceSelect' );

	if ( countrySelect && provinceInput && stateSelect ) {
		const toggleStateProvince = function() {
			console.log('toggleStateProvince')
			switch ( countrySelect.value ) {
				case 'United States':
				case 'US':
					provinceInput.hidden = true;
					stateSelect.hidden = false;
					break;

				default:
					provinceInput.hidden = false;
					stateSelect.hidden = true;
			}
		}

		toggleStateProvince();

		if ( typeof jQuery != 'undefined' ) {
			jQuery(countrySelect).on( 'change', toggleStateProvince );
		} else {
			countrySelect.addEventListener( 'change', toggleStateProvince );
		}
	}
} )();
</script>
	";

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_venue_fields_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_venue_fields_output' );

function enlightenment_tribe_bootstrap_community_edit_organizer_template( $output ) {
	$output = str_replace( 'class="events-community-post-title"', 'class="events-community-post-title mb-3"', $output );
	$output = str_replace( 'for="post_title" class="', 'for="post_title" class="form-label h5 ', $output );
	$output = str_replace( 'name="post_title"', 'name="post_title" class="form-control form-control-lg"', $output );
	$output = str_replace( 'class="events-community-post-content"', 'class="events-community-post-content mb-3"', $output );
	$output = str_replace( '<label for="post_content">', '<label for="post_content" class="form-label h5">', $output );
	$output = str_replace( '<textarea name="tcepostcontent">', '<textarea name="tcepostcontent" class="form-control">', $output );
	$output = str_replace( 'class="button submit events-community-submit"', 'class="button submit events-community-submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_community_edit_organizer_template', 'enlightenment_tribe_bootstrap_community_edit_organizer_template' );

function enlightenment_tribe_bootstrap_template_part_community_modules_organizer_fields_output( $output ) {
	$output = str_replace( 'class="tribe-events-community-details eventForm bubble"', 'class="tribe-events-community-details eventForm bubble card mb-3"', $output );
	$output = str_replace( '<h4>', '<h4 class="card-header h5">', $output );
	$output = str_replace( '<label class="', '<label class="mb-0 ', $output );

	$offset = strpos( $output, '<div class="tribe_sectionheader">' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	$offset = strpos( $output, '<div class="organizer">' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, '<div class="col-lg-2">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, '<div class="col-lg-10">' . "\n", $offset, 0 );
		$offset = strpos( $output, ' />', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 3, 0 );

		$offset = strpos( $output, '<div class="organizer">', $offset );
	}

	$output = str_replace( 'class="organizer"', 'class="organizer mb-3 row gx-2 align-items-center"', $output );
	$output = str_replace( '<label for="', '<label class="mb-0" for="', $output );
	$output = str_replace( 'class="linked-post-name"', 'class="linked-post-name form-control"', $output );
	$output = str_replace( 'name="organizer[', 'class="form-control" name="organizer[', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_modules_organizer_fields_output', 'enlightenment_tribe_bootstrap_template_part_community_modules_organizer_fields_output' );

function enlightenment_tribe_bootstrap_template_part_community_tickets_modules_tickets_output( $output ) {
	$community_tickets = tribe( 'community-tickets.main' );

	if ( ! $community_tickets->is_enabled() ) {
		return $output;
	}

	$community_events = Tribe__Events__Community__Main::instance();
	$event_id         = $community_events->event_form()->get_event_id();

	$output = str_replace( 'class="tribe-section tribe-section-tickets ', 'class="tribe-section tribe-section-tickets card mb-3 ', $output );

	$offset = strpos( $output, 'class="tribe-section-header"' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '<h3>', $offset );
		$output  = substr_replace( $output, ' class="card-header h5"', $offset + 3, 0 );
		$offset  = strpos( $output, '</div>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 6, 0 );
		$output .= '</div>';
	}

	// Bail early if tickets are not enabled
	if (
		! $community_tickets->is_enabled_for_event( $event_id ) ||
		! current_user_can( 'sell_event_tickets' )
	) {
		$offset = strpos( $output, '<p>' );
		$output = substr_replace( $output, ' class="alert alert-info mb-0"', $offset + 2, 0 );

		return $output;
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_tickets_modules_tickets_output', 'enlightenment_tribe_bootstrap_template_part_community_tickets_modules_tickets_output' );

function enlightenment_tribe_bootstrap_events_tickets_new_ticket_warnings_end_tag() {
	echo '<!-- /.ticket_table_intro__warnings -->';
}
add_action( 'tribe_events_tickets_new_ticket_warnings', 'enlightenment_tribe_bootstrap_events_tickets_new_ticket_warnings_end_tag' );

function enlightenment_tribe_bootstrap_template_editor_panel_list_output( $output ) {
	$output = str_replace( 'class="tribe_sectionheader ticket_list_container tribe_no_capacity"', 'class="tribe_sectionheader ticket_list_container tribe_no_capacity d-flex flex-wrap gap-3 justify-content-between"', $output );
	$output = str_replace( 'class="tribe_sectionheader ticket_list_container"', 'class="tribe_sectionheader ticket_list_container d-flex flex-wrap gap-3 justify-content-between mb-3"', $output );
	$output = str_replace( 'class="ticket_table_intro"', 'class="ticket_table_intro d-flex flex-wrap gap-3"', $output );
	$output = str_replace( 'class="ticket-editor-notice info ', 'class="ticket-editor-notice info order-first w-100 ', $output );
	$output = str_replace( 'id="ticket_form_total_capacity"', 'id="ticket_form_total_capacity" class="text-body-secondary"', $output );
	$output = str_replace( 'class="ticket_list_wrapper"', 'class="ticket_list_wrapper table-responsive w-100 mb-0"', $output );
	$output = str_replace( 'id="ticket_list_wrapper"', 'id="ticket_list_wrapper" class="table-responsive w-100 mb-0"', $output );
	$output = str_replace( 'class="tribe_ticket_list_table ', 'class="tribe_ticket_list_table table ', $output );
	$output = str_replace( 'class="tribe-tickets-editor-table ', 'class="tribe-tickets-editor-table table ', $output );

	$offset = strpos( $output, '<!-- /.ticket_table_intro__warnings -->' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '', $offset, 39 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '', $offset, 6 );
		$offset = strrpos( $output, '<div class="ticket_table_intro__warnings">', $offset - strlen( $output ) );
		$output = substr_replace( $output, '', $offset, 42 );
	}

	$offset = strpos( $output, 'id="ticket_list_wrapper"' );
	if ( false === $offset ) {
		$offset = strpos( $output, 'class="ticket_list_wrapper"' );

		if ( false === $offset ) {
			$offset = strpos( $output, '<table id="tribe_ticket_list_table"' );

			if ( false !== $offset ) {
				$output = substr_replace( $output, '<div class="table-responsive w-100 mb-0">' . "\n", $offset, 0 );
				$offset = strpos( $output, '</table>', $offset );
				$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
			}
		}
	}

	$offset = strpos( $output, 'class="button-secondary"' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, 'btn btn-secondary ', $offset + 7, 0 );

		$offset = strpos( $output, 'class="button-secondary"', $offset );
	}

	$output = str_replace( 'class="tribe-ticket-control-wrap"', 'class="tribe-ticket-control-wrap d-flex flex-wrap align-items-center gap-3"', $output );
	$output = str_replace( 'class="tribe-ticket-control-wrap__ctas"', 'class="tribe-ticket-control-wrap__ctas d-flex flex-wrap gap-3"', $output );
	$output = str_replace( 'class="tribe-ticket-control-wrap__settings"', 'class="tribe-ticket-control-wrap__settings d-flex flex-wrap align-items-center justify-content-between gap-3 flex-grow-1"', $output );

	$offset = strpos( $output, 'class="ticket-editor-notice info ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="alert alert-info d-flex mb-0">', $offset + 1, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$start = strpos( $output, '<div class="tribe-ticket-control-wrap__ctas ' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );

		$offset = strpos( $output, '<a', $start );
		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="btn btn-secondary"', $offset + 2, 0 );

			$offset = strpos( $output, '<a', $offset + 1 );
		}
	}

	$offset = strpos( $output, 'id="ticket_form_toggle"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="', $offset );
		$output = substr_replace( $output, 'btn btn-secondary ', $offset + 7, 0 );
	}

	$offset = strpos( $output, 'id="rsvp_form_toggle"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="', $offset );
		$output = substr_replace( $output, 'btn btn-secondary ', $offset + 7, 0 );
	}

	$offset = strpos( $output, 'id="settings_form_toggle"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="', $offset );
		$output = substr_replace( $output, 'btn btn-secondary ', $offset + 7, 0 );
	}

	$output = str_replace( 'for="tribe-tickets-warning"', 'for="tribe-tickets-warning" class="mb-0"', $output );
	$output = str_replace( 'id="tribe-tickets-warning"', 'id="tribe-tickets-warning" class="d-none"', $output );
	$output = str_replace( 'class="recurring-warning ', 'class="recurring-warning alert alert-warning mt-3 ', $output );
	$output = str_replace( 'class="button-secondary tribe-button-icon tribe-button-icon-edit"', 'class="btn btn-secondary button-secondary tribe-button-icon tribe-button-icon-edit"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_editor_panel_list_output', 'enlightenment_tribe_bootstrap_template_editor_panel_list_output' );

function enlightenment_tribe_bootstrap_template_editor_recurring_warning_output( $output ) {
	$start = strpos( $output, '<span class="dashicons ' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</span>' ) + 7;
		$length = $end - $start;
		$output = substr_replace( $output, '<i class="fas fa-info-circle mt-1 me-2" role="presentation" aria-hidden="true"></i>', $start, $length );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_editor_recurring_warning_output', 'enlightenment_tribe_bootstrap_template_editor_recurring_warning_output' );

function enlightenment_tribe_bootstrap_template_editor_list_row_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$tickets = $context['tickets'];
	$rsvp    = array();
	$current = $context['ticket'];

	foreach ( $tickets as $key => $ticket ) {
		if ( false !== strpos( $ticket->provider_class, 'RSVP' ) ) {
			$rsvp[] = $ticket;
			unset( $tickets[ $key ] );
		}
	}

	if ( 1 === count( $tickets ) || false !== strpos( $current->provider_class, 'RSVP' ) ) {
		$output = str_replace( 'class="tribe-tickets__tickets-editor-ticket-name-sortable"', 'class="tribe-tickets__tickets-editor-ticket-name-sortable d-none"', $output );
	} else {
		$output = str_replace( 'class="tribe-tickets__tickets-editor-ticket-name"', 'class="tribe-tickets__tickets-editor-ticket-name d-flex"', $output );
		$output = str_replace( 'class="tribe-tickets__tickets-editor-ticket-name-sortable"', 'class="tribe-tickets__tickets-editor-ticket-name-sortable me-1"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_editor_list_row_output', 'enlightenment_tribe_bootstrap_template_editor_list_row_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_editor_panel_ticket_output( $output ) {
	$output = str_replace( 'class="ticket_form_label ', 'class="ticket_form_label col-md-3 col-form-label ', $output );
	$output = str_replace( 'class="ticket_form_label"', 'class="ticket_form_label col-md-3 col-form-label"', $output );
	$output = str_replace( 'class="tribe_soft_note ticket_form_right"', 'class="tribe_soft_note ticket_form_right form-text w-100"', $output );
	$output = str_replace( 'class="description ticket_form_right"', 'class="description ticket_form_right form-text w-100 mb-0"', $output );
	$output = str_replace( 'class="ticket_sale_price ', 'class="ticket_sale_price w-100 ', $output );
	$output = str_replace( 'class="dashicons dashicons-editor-help"', 'class="dashicons dashicons-editor-help" data-bs-toggle="tooltip" data-bs-placement="top"', $output );
	$output = str_replace( 'class="ticket_bottom"', 'class="ticket_bottom d-flex"', $output );
	$output = str_replace( 'class="ticket_bottom_buttons"', 'class="ticket_bottom_buttons d-flex"', $output );
	$output = str_replace( 'class="button-primary tribe-dependent tribe-validation-submit"', 'class="button-primary tribe-dependent tribe-validation-submit btn btn-primary"', $output );
	$output = str_replace( 'class="button-secondary"', 'class="button-secondary btn btn-secondary ms-2"', $output );
	$output = str_replace( 'id="ticket_bottom_right"', 'id="ticket_bottom_right" class="ms-auto"', $output );
	$output = str_replace( 'class="thickbox tribe-ticket-move-link"', 'class="thickbox tribe-ticket-move-link btn btn-secondary me-2"', $output );
	$output = str_replace( 'class="ticket_delete"', 'class="ticket_delete btn btn-danger"', $output );

	$offset = strpos( $output, 'class="dashicons dashicons-editor-help"' );
	while ( false !== $offset ) {
		$end   = strpos( $output, '>', $offset );
		$start = strpos( $output, 'title="', $offset );
		if ( false !== $start && $start < $end ) {
			$output = substr_replace( $output, 'data-bs-', $start, 0 );
		}

		$offset = strpos( $output, 'class="dashicons dashicons-editor-help"', $offset + 1 );
	}

	$offset = strpos( $output, "id='ticket_name'" );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'class="input_block"', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' mb-3 row align-items-start', $offset + 18, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="col-md-9">', $offset, 0 );
		$offset = strpos( $output, 'class="ticket_field ', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 19, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
	}

	$offset = strpos( $output, 'for="ticket_description"' );
	$end    = strpos( $output, 'id="ticket_form_advanced"' );
	if ( false !== $offset && ( false === $end || $offset < $end ) ) {
		$offset = strrpos( $output, 'class="input_block"', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' row align-items-start', $offset + 18, 0 );
		$offset = strpos( $output, '<textarea', $offset );
		$output = substr_replace( $output, '<dummy-div class="col-md-9">' . "\n" . '<dummy-div class="mb-3">', $offset, 0 );
		$offset = strpos( $output, 'class="ticket_field ', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 19, 0 );
		$offset = strpos( $output, '</textarea>', $offset );
		$output = substr_replace( $output, "\n" . '</dummy-div>', $offset + 11, 0 );

		$offset_a = strpos( $output, 'class="input_block"', $offset );
		$end_a    = strpos( $output, '</div>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$offset = $offset_a;
			$output = substr_replace( $output, ' mb-3 form-check', $offset + 18, 0 );
			$offset = strpos( $output, '<label class="tribe_soft_note">', $offset );
			$output = substr_replace( $output, '', $offset, 31 );
			$offset = strpos( $output, 'class="ticket_field ', $offset );
			$output = substr_replace( $output, ' form-check-input', $offset + 19, 0 );
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, "\n" . '<label for="tribe_tickets_show_description" class="tribe_soft_note form-check-label">', $offset + 1, 0 );
		}

		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '</dummy-div>', $offset + 6, 0 );
	}

	$output = str_replace( '<dummy-div ', '<div ', $output );
	$output = str_replace( '</dummy-div>', '</div>', $output );

	$offset = strpos( $output, 'id="ticket_type_options"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="input_block"', $offset );
		$output = substr_replace( $output, ' mb-3 row align-items-start', $offset + 18, 0 );
		$offset = strpos( $output, '<div class="ticket_form_right ticket_form_right--flex">', $offset );
		$output = substr_replace( $output, ' d-flex align-items-center gap-1', $offset + 53, 0 );
		$output = substr_replace( $output, '<div class="col-md-9">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="tribe_soft_note ticket_form_right tribe-active"', $offset );
		$output = substr_replace( $output, ' d-block form-text', $offset + 53, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
	}

	$offset = strpos( $output, 'for="ticket_start_date"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'class="input_block"', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' mb-3 row align-items-start', $offset + 18, 0 );
		$offset = strpos( $output, 'class="ticket_form_right"', $offset );
		$output = substr_replace( $output, ' col-md-9 d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center', $offset + 24, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="d-flex flex-column">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="tribe-datepicker tribe-field-start_date ticket_field"', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 59, 0 );
		$offset = strpos( $output, 'class="helper-text ', $offset );
		$output = substr_replace( $output, ' form-text', $offset + 18, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 7, 0 );
		$offset = strpos( $output, 'class="datetime_seperator"', $offset );
		$output = substr_replace( $output, ' my-1 my-sm-0 mx-sm-2', $offset + 25, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="d-flex flex-column">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="tribe-timepicker tribe-field-start_time ticket_field"', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 59, 0 );
		$offset = strpos( $output, 'class="helper-text ', $offset );
		$output = substr_replace( $output, ' form-text', $offset + 18, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 7, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-editor-help"', $offset );
		$output = substr_replace( $output, ' mt-1 mt-sm-0 ms-sm-2', $offset + 38, 0 );
	}

	$offset = strpos( $output, 'for="ticket_end_date"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'class="input_block"', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' mb-3 row align-items-start', $offset + 18, 0 );
		$offset = strpos( $output, 'class="ticket_form_right"', $offset );
		$output = substr_replace( $output, ' col-md-9 d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center', $offset + 24, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="d-flex flex-column">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="tribe-datepicker tribe-field-end_date ticket_field"', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 57, 0 );
		$offset = strpos( $output, 'class="helper-text ', $offset );
		$output = substr_replace( $output, ' form-text', $offset + 18, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 7, 0 );
		$offset = strpos( $output, 'class="datetime_seperator"', $offset );
		$output = substr_replace( $output, ' my-1 my-sm-0 mx-sm-2', $offset + 25, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="d-flex flex-column">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="tribe-timepicker tribe-field-end_time ticket_field"', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 57, 0 );
		$offset = strpos( $output, 'class="helper-text ', $offset );
		$output = substr_replace( $output, ' form-text', $offset + 18, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 7, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-editor-help"', $offset );
		$output = substr_replace( $output, ' mt-1 mt-sm-0 ms-sm-2', $offset + 38, 0 );
	}

	$offset = strpos( $output, 'for="ticket_price"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'class="input_block"', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' row align-items-start mb-3', $offset + 18, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="col-md-9 d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center">', $offset, 0 );
		$offset = strpos( $output, 'class="ticket_field ', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 19, 0 );

		$offset_a = strpos( $output, '<div class="ticket_sale_price_wrapper ' );
		if ( false !== $offset_a ) {
			$offset_b = strpos( $output, '<div>', $offset_a );
			if ( false !== $offset_b ) {
				$offset_a = strpos( $output, '</div>', $offset_b ) + 6;
			}

			$offset_b = strpos( $output, '<div class="ticket_sale_price ', $offset_a );
			if ( false !== $offset_b ) {
				$offset_c = strpos( $output, '<div class="ticket_sale_price-field">', $offset_b );
				while ( false !== $offset_c ) {
					$offset_b = strpos( $output, '</div>', $offset_c ) + 6;

					$offset_c = strpos( $output, '<div class="ticket_sale_price-field">', $offset_c + 1 );
				}

				$offset_a = strpos( $output, '</div>', $offset_b ) + 6;
			}

			$offset = strpos( $output, '</div>', $offset_a ) + 6;
		}

		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );
	}

	$offset = strpos( $output, '<!--Checkbox to toggle sale price fields-->' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<div>', $offset );
		$output = substr_replace( $output, ' class="form-check mt-3 mb-0"', $offset + 4, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, 'class="ticket_form_label col-md-3 col-form-label"', $offset );
		$output = substr_replace( $output, 'form-check-label', $offset + 25, 23 );
	}

	$offset = strpos( $output, '<div class="ticket_sale_price ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<div class="d-flex flex-column gap-3 mt-3">', $offset + 1, 0 );

		$offset_a = strpos( $output, '<div class="ticket_sale_price-field">', $offset );
		while ( false !== $offset_a ) {
			$offset   = strpos( $output, '</div>', $offset_a ) + 6;

			$offset_a = strpos( $output, '<div class="ticket_sale_price-field">', $offset_a + 1 );
		}

		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );
	}

	$offset = strpos( $output, '<div class="ticket_sale_price-field">' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, ' d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center', $offset + 35, 0 );
		$offset = strpos( $output, 'class="ticket_form_label col-md-3 col-form-label', $offset );
		$output = substr_replace( $output, 'form-label mb-sm-0 me-sm-2', $offset + 25, 23 );

		$offset_a = strpos( $output, 'class="ticket_field"', $offset );
		$end_a    = strpos( $output, '</div>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset_a + 19, 0 );
		}

		$offset_a = strpos( $output, 'class="tribe-datepicker ', $offset );
		$end_a    = strpos( $output, '</div>', $offset );
		while ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset_a + 23, 0 );

			$offset_a = strpos( $output, 'class="tribe-datepicker ', $offset_a + 1 );
			$end_a    = strpos( $output, '</div>', $offset );
		}

		$offset_a = strpos( $output, '<span>', $offset );
		$end_a    = strpos( $output, '</div>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, ' class="my-1 my-sm-0 mx-sm-2"', $offset_a + 5, 0 );
		}

		$offset = strpos( $output, '<div class="ticket_sale_price-field">', $offset );
	}

	$offset = strpos( $output, 'for="Tribe__Tickets__RSVP_capacity"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'class="input_block ', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' mb-3', $offset + 18, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row align-items-start">', $offset + 1, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="col-md-9 d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center">', $offset, 0 );
		$offset = strpos( $output, 'class="ticket_field ', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 19, 0 );
		$offset = strpos( $output, 'class="tribe_soft_note ticket_form_right ', $offset );
		$output = substr_replace( $output, ' w-100', $offset + 40, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, 'for="tribe-tickets-rsvp-not-going"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '', $offset, 34 );
		$offset = strpos( $output, ' col-form-label', $offset );
		$output = substr_replace( $output, ' mb-0', $offset, 15 );
		$offset = strrpos( $output, 'class="input_block ', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' mb-3', $offset + 18, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row align-items-start">', $offset + 1, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="col-md-9">' . "\n" . '<div class="form-check">', $offset, 0 );
		$offset = strpos( $output, 'class="ticket_field ', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 19, 0 );
		$offset = strpos( $output, '<span class="tribe_soft_note ticket_form_right form-text w-100">', $offset );
		$output = substr_replace( $output, '<label class="form-check-label" for="tribe-tickets-rsvp-not-going">', $offset, 64 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, '</label>', $offset, 7 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, 'id="tribe_ticket_provider_wrapper" class="input_block"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="input_block"', $offset );
		$output = substr_replace( $output, ' d-none mb-3', $offset + 18, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row align-items-start">', $offset + 1, 0 );
		$offset = strpos( $output, ' col-form-label', $offset );
		$output = substr_replace( $output, ' mb-0', $offset, 15 );
		$offset = strpos( $output, '</legend>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="col-md-9">', $offset + 9, 0 );
		$offset = strpos( $output, '</fieldset>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$start  = strpos( $output, 'id="tribe_ticket_provider_wrapper"' );
	if ( false !== $start ) {
		$offset = strpos( $output, '<input', $start );
		$end    = strpos( $output, '</fieldset>', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, '<div class="form-check">' . "\n", $offset, 0 );
			$offset = strpos( $output, 'id="', $offset ) + 4;
			$length = strpos( $output, '"', $offset ) - $offset;
			$id     = substr( $output, $offset, $length );
			$offset = strpos( $output, 'class="ticket_field ticket_provider"', $offset );
			$output = substr_replace( $output, ' form-check-input', $offset + 35, 0 );
			$offset = strpos( $output, '<span>', $offset );
			$output = substr_replace( $output, sprintf( '<label class="form-check-label" for="%s">', $id ), $offset, 6 );
			$offset = strpos( $output, '</span>', $offset );
			$output = substr_replace( $output, '</label>', $offset, 7 );
			$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );

			$offset = strpos( $output, '<input', $offset );
			$end    = strpos( $output, '</fieldset>', $start );
		}
	}

	$offset = strpos( $output, 'id="TECTicketsCommerceModule_ticket_global_stock"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="input_block ', $offset );
		$output = substr_replace( $output, ' mb-3', $offset + 18, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row align-items-start">', $offset + 1, 0 );
		$offset = strpos( $output, ' col-form-label', $offset );
		$output = substr_replace( $output, ' mb-0', $offset, 15 );
		$offset = strpos( $output, '</legend>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="col-md-9">', $offset + 9, 0 );
		$offset = strpos( $output, '<label for="TECTicketsCommerceModule_global" class="ticket_field">', $offset );
		$output = substr_replace( $output, '<div class="mb-3 form-check">', $offset, 66 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-mode"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 43, 0 );
		$offset = strpos( $output, '>', $offset ) + 1;
		$output = substr_replace( $output, "\n" . '<label for="TECTicketsCommerceModule_global" class="ticket_field form-check-label">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );

		$offset_a = strpos( $output, 'class="global_capacity-wrapper"', $offset );
		if ( false !== $offset_a ) {
			$output   = substr_replace( $output, ' d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center mb-3', $offset_a + 30, 0 );
			$offset_a = strpos( $output, 'class="ticket_form_label col-md-3 col-form-label"', $offset_a );
			$output   = substr_replace( $output, 'class="ticket_form_label form-label mb-sm-0 me-sm-2"', $offset_a, 49 );
			$offset_a = strpos( $output, 'class="ticket_field tribe-ticket-field-event-capacity small-text"', $offset_a );
			$output   = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset_a + 64, 0 );
			$offset_a = strpos( $output, 'class="tribe-tickets-global-sales"', $offset_a );
			$output   = substr_replace( $output, ' ms-2', $offset_a + 33, 0 );
		}

		$offset = strpos( $output, '<div>', $offset );
		$output = substr_replace( $output, ' class="d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center mb-3"', $offset + 4, 0 );
		$offset = strpos( $output, '<label for="TECTicketsCommerceModule_global_stock_cap">', $offset );
		$output = substr_replace( $output, ' class="form-label mb-sm-0 me-sm-2"', $offset + 54, 0 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-capacity small-text"', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 58, 0 );
		$offset = strpos( $output, '<p class="tribe-description-small">', $offset );
		$output = substr_replace( $output, '<p class="tribe-description-small form-text w-100 mb-0">', $offset, 35 );
		$offset = strpos( $output, '<label for="TECTicketsCommerceModule_own" class="ticket_field">', $offset );
		$output = substr_replace( $output, '<div class="mb-3 form-check">', $offset, 63 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-mode"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 43, 0 );
		$offset = strpos( $output, '>', $offset ) + 1;
		$output = substr_replace( $output, "\n" . '<label for="TECTicketsCommerceModule_own" class="ticket_field form-check-label">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
		$offset = strpos( $output, '<div>', $offset );
		$output = substr_replace( $output, ' class="d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center mb-3"', $offset + 4, 0 );
		$offset = strpos( $output, '<label for="TECTicketsCommerceModule_capacity">', $offset );
		$output = substr_replace( $output, ' class="form-label mb-sm-0 me-sm-2"', $offset + 46, 0 );
		$offset = strpos( $output, 'class="ticket_field ticket_stock"', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 32, 0 );
		$offset = strpos( $output, '<div class="input_block">', $offset );
		$output = substr_replace( $output, ' form-check', $offset + 23, 0 );
		$offset = strpos( $output, '<label for="TECTicketsCommerceModule_unlimited" class="ticket_field">', $offset );
		$output = substr_replace( $output, '', $offset, 69 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-mode"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 43, 0 );
		$offset = strpos( $output, '>', $offset ) + 1;
		$output = substr_replace( $output, "\n" . '<label for="TECTicketsCommerceModule_unlimited" class="ticket_field form-check-label">', $offset, 0 );
		$offset = strpos( $output, '</fieldset>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, 'id="Tribe__Tickets_Plus__Commerce__WooCommerce__Main_ticket_global_stock"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="input_block ', $offset );
		$output = substr_replace( $output, ' mb-3', $offset + 18, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row align-items-start">', $offset + 1, 0 );
		$offset = strpos( $output, ' col-form-label', $offset );
		$output = substr_replace( $output, ' mb-0', $offset, 15 );
		$offset = strpos( $output, '</legend>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="col-md-9">', $offset + 9, 0 );
		$offset = strpos( $output, '<label for="Tribe__Tickets_Plus__Commerce__WooCommerce__Main_global" class="ticket_field">', $offset );
		$output = substr_replace( $output, '<div class="mb-3 form-check">', $offset, 90 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-mode"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 43, 0 );
		$offset = strpos( $output, '>', $offset ) + 1;
		$output = substr_replace( $output, "\n" . '<label for="Tribe__Tickets_Plus__Commerce__WooCommerce__Main_global" class="ticket_field form-check-label">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );

		$offset_a = strpos( $output, 'class="global_capacity-wrapper"', $offset );
		if ( false !== $offset_a ) {
			$output   = substr_replace( $output, ' d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center mb-3', $offset_a + 30, 0 );
			$offset_a = strpos( $output, 'class="ticket_form_label col-md-3 col-form-label"', $offset_a );
			$output   = substr_replace( $output, 'class="ticket_form_label form-label mb-sm-0 me-sm-2"', $offset_a, 49 );
			$offset_a = strpos( $output, 'class="ticket_field tribe-ticket-field-event-capacity small-text"', $offset_a );
			$output   = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset_a + 64, 0 );
			$offset_a = strpos( $output, 'class="tribe-tickets-global-sales"', $offset_a );
			$output   = substr_replace( $output, ' ms-2', $offset_a + 33, 0 );
		}

		$offset = strpos( $output, '<div>', $offset );
		$output = substr_replace( $output, ' class="d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center mb-3"', $offset + 4, 0 );
		$offset = strpos( $output, '<label for="Tribe__Tickets_Plus__Commerce__WooCommerce__Main_global_stock_cap">', $offset );
		$output = substr_replace( $output, ' class="form-label mb-sm-0 me-sm-2"', $offset + 78, 0 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-capacity small-text"', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 58, 0 );
		$offset = strpos( $output, '<p class="tribe-description-small">', $offset );
		$output = substr_replace( $output, '<p class="tribe-description-small form-text w-100 mb-0">', $offset, 35 );
		$offset = strpos( $output, '<label for="Tribe__Tickets_Plus__Commerce__WooCommerce__Main_own" class="ticket_field">', $offset );
		$output = substr_replace( $output, '<div class="mb-3 form-check">', $offset, 87 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-mode"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 43, 0 );
		$offset = strpos( $output, '>', $offset ) + 1;
		$output = substr_replace( $output, "\n" . '<label for="Tribe__Tickets_Plus__Commerce__WooCommerce__Main_own" class="ticket_field form-check-label">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
		$offset = strpos( $output, '<div>', $offset );
		$output = substr_replace( $output, ' class="d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center mb-3"', $offset + 4, 0 );
		$offset = strpos( $output, '<label for="Tribe__Tickets_Plus__Commerce__WooCommerce__Main_capacity">', $offset );
		$output = substr_replace( $output, ' class="form-label mb-sm-0 me-sm-2"', $offset + 70, 0 );
		$offset = strpos( $output, 'class="ticket_field ticket_stock"', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 32, 0 );
		$offset = strpos( $output, '<div class="input_block">', $offset );
		$output = substr_replace( $output, ' form-check', $offset + 23, 0 );
		$offset = strpos( $output, '<label for="Tribe__Tickets_Plus__Commerce__WooCommerce__Main_unlimited" class="ticket_field">', $offset );
		$output = substr_replace( $output, '', $offset, 93 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-mode"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 43, 0 );
		$offset = strpos( $output, '>', $offset ) + 1;
		$output = substr_replace( $output, "\n" . '<label for="Tribe__Tickets_Plus__Commerce__WooCommerce__Main_unlimited" class="ticket_field form-check-label">', $offset, 0 );
		$offset = strpos( $output, '</fieldset>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, 'class="ticket_advanced_Tribe__Tickets_Plus__Commerce__WooCommerce__Main ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset ) + 1;
		$output = substr_replace( $output, "\n" . '<div class="row align-items-start">', $offset, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="col-md-9">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="ticket_field sku_input ticket_form_right"', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 47, 0 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
	}

	$offset = strpos( $output, 'id="Tribe__Tickets__Commerce__PayPal__Main_ticket_global_stock"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="input_block ', $offset );
		$output = substr_replace( $output, ' mb-3', $offset + 18, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row align-items-start">', $offset + 1, 0 );
		$offset = strpos( $output, ' col-form-label', $offset );
		$output = substr_replace( $output, ' mb-0', $offset, 15 );
		$offset = strpos( $output, '</legend>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="col-md-9">', $offset + 9, 0 );
		$offset = strpos( $output, '<label for="Tribe__Tickets__Commerce__PayPal__Main_global" class="ticket_field">', $offset );
		$output = substr_replace( $output, '<div class="mb-3 form-check">', $offset, 80 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-mode"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 43, 0 );
		$offset = strpos( $output, '>', $offset ) + 1;
		$output = substr_replace( $output, "\n" . '<label for="Tribe__Tickets__Commerce__PayPal__Main_global" class="ticket_field form-check-label">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );

		$offset_a = strpos( $output, 'class="global_capacity-wrapper"', $offset );
		if ( false !== $offset_a ) {
			$output   = substr_replace( $output, ' d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center mb-3', $offset_a + 30, 0 );
			$offset_a = strpos( $output, 'class="ticket_form_label col-md-3 col-form-label"', $offset_a );
			$output   = substr_replace( $output, 'class="ticket_form_label form-label mb-sm-0 me-sm-2"', $offset_a, 49 );
			$offset_a = strpos( $output, 'class="ticket_field tribe-ticket-field-event-capacity small-text"', $offset_a );
			$output   = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset_a + 64, 0 );
			$offset_a = strpos( $output, 'class="tribe-tickets-global-sales"', $offset_a );
			$output   = substr_replace( $output, ' ms-2', $offset_a + 33, 0 );
		}

		$offset = strpos( $output, '<div>', $offset );
		$output = substr_replace( $output, ' class="d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center mb-3"', $offset + 4, 0 );
		$offset = strpos( $output, '<label for="Tribe__Tickets__Commerce__PayPal__Main_global_stock_cap">', $offset );
		$output = substr_replace( $output, ' class="form-label mb-sm-0 me-sm-2"', $offset + 68, 0 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-capacity small-text"', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 58, 0 );
		$offset = strpos( $output, '<p class="tribe-description-small">', $offset );
		$output = substr_replace( $output, '<p class="tribe-description-small form-text w-100 mb-0">', $offset, 35 );
		$offset = strpos( $output, '<label for="Tribe__Tickets__Commerce__PayPal__Main_own" class="ticket_field">', $offset );
		$output = substr_replace( $output, '<div class="mb-3 form-check">', $offset, 77 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-mode"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 43, 0 );
		$offset = strpos( $output, '>', $offset ) + 1;
		$output = substr_replace( $output, "\n" . '<label for="Tribe__Tickets__Commerce__PayPal__Main_own" class="ticket_field form-check-label">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
		$offset = strpos( $output, '<div>', $offset );
		$output = substr_replace( $output, ' class="d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center mb-3"', $offset + 4, 0 );
		$offset = strpos( $output, '<label for="Tribe__Tickets__Commerce__PayPal__Main_capacity">', $offset );
		$output = substr_replace( $output, ' class="form-label mb-sm-0 me-sm-2"', $offset + 60, 0 );
		$offset = strpos( $output, 'class="ticket_field ticket_stock"', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 32, 0 );
		$offset = strpos( $output, '<div class="input_block">', $offset );
		$output = substr_replace( $output, ' form-check', $offset + 23, 0 );
		$offset = strpos( $output, '<label for="Tribe__Tickets__Commerce__PayPal__Main_unlimited" class="ticket_field">', $offset );
		$output = substr_replace( $output, '', $offset, 83 );
		$offset = strpos( $output, 'class="ticket_field tribe-ticket-field-mode"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 43, 0 );
		$offset = strpos( $output, '>', $offset ) + 1;
		$output = substr_replace( $output, "\n" . '<label for="Tribe__Tickets__Commerce__PayPal__Main_unlimited" class="ticket_field form-check-label">', $offset, 0 );
		$offset = strpos( $output, '</fieldset>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, 'class="ticket_advanced_TECTicketsCommerceModule ' );
	if ( false === $offset ) {
		$offset = strpos( $output, 'class="ticket_advanced_Tribe__Tickets__Commerce__PayPal__Main ' );
	}
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset ) + 1;
		$output = substr_replace( $output, "\n" . '<div class="row align-items-start">', $offset, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="col-md-9">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="ticket_field sku_input ticket_form_right"', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 47, 0 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
	}

	$offset = strpos( $output, 'for="TEC_Tickets_Commerce_Module_capacity"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'class="input_block ', $offset - strlen( $output ) );
		$output = substr_replace( $output, ' mb-3', $offset + 18, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row align-items-start">', $offset + 1, 0 );
		$offset = strpos( $output, '<input', $offset );
		$output = substr_replace( $output, '<div class="col-md-9 d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center">', $offset, 0 );
		$offset = strpos( $output, 'class="ticket_field ', $offset );
		$output = substr_replace( $output, ' form-control d-sm-inline-block w-auto', $offset + 19, 0 );
		$offset = strpos( $output, 'class="tribe_soft_note ticket_form_right ', $offset );
		$output = substr_replace( $output, ' w-100', $offset + 40, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	// Shush Event Tickets' function window.MTAccordion()
	$output = str_replace( '<div class="accordion">', '<div class="accordion d-none"></div>' . "\n" . '<div class="accordion">', $output );

	$output = str_replace( 'class="accordion"', 'class="accordion mb-3" id="tribe-events-tickets-metabox-edit-accordion"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_editor_panel_ticket_output', 'enlightenment_tribe_bootstrap_template_editor_panel_ticket_output' );

function enlightenment_tribe_bootstrap_template_notices_upsell_main_output( $output ) {
	$output = str_replace( 'class="tec-admin__upsell-content"', 'class="tec-admin__upsell-content alert alert-info d-flex align-items-baseline gap-2"', $output );
	$output = str_replace( 'class="tec-admin__upsell-link ', 'class="tec-admin__upsell-link alert-link ', $output );
	$output = str_replace( 'class="tec-admin__upsell-link"', 'class="tec-admin__upsell-link alert-link"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_main_output', 'enlightenment_tribe_bootstrap_template_notices_upsell_main_output' );

function enlightenment_tribe_bootstrap_template_notices_upsell_icon_output( $output ) {
	$offset = strpos( $output, 'class="tec-admin__upsell-icon-image"' );
	if ( false !== $offset ) {
		$start  = strrpos( $output, '<img', $offset - strlen( $output ) );
		$end    = strpos( $output, '/>', $start ) + 2;
		$length = $end - $start;

		$output = substr_replace( $output, '<i class="fas fa-bolt" aria-hidden="true"></i>', $start, $length );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_icon_output', 'enlightenment_tribe_bootstrap_template_notices_upsell_icon_output' );

function enlightenment_tribe_bootstrap_template_editor_fieldset_advanced_output( $output ) {
	$offset = strpos( $output, 'class="tribe-dependent"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' accordion-item', $offset + 22, 0 );
	}

	$offset = strpos( $output, '<button class="accordion-header tribe_advanced_meta">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' type="button" data-bs-toggle="collapse" data-bs-target="#ticket_form_advanced" aria-expanded="false" aria-controls="ticket_form_advanced"', $offset + 52, 0 );
		$output = substr_replace( $output, 'button collapsed', $offset + 25, 6 );
		$output = substr_replace( $output, '<div class="accordion-header">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
	}

	$output = str_replace( 'class="advanced accordion-content"', 'class="advanced accordion-content accordion-collapse collapse" data-bs-parent="#tribe-events-tickets-metabox-edit-accordion"', $output );
	$output = str_replace( 'class="accordion-label screen_reader_text"', 'class="accordion-label screen_reader_text visually-hidden"', $output );
	$output = str_replace( 'id="advanced_fields"', 'id="advanced_fields" class="accordion-body"', $output );
	$output = str_replace( 'class="input_block"', 'class="input_block row align-items-start"', $output );
	$output = str_replace( 'class="ticket_field ticket_form_right"', 'class="ticket_field ticket_form_right form-control"', $output );
	$output = str_replace( 'class="ticket_form_right"', 'class="ticket_form_right col-md-9 d-flex flex-column flex-sm-row flex-sm-wrap align-items-sm-center mb-3"', $output );
	$output = str_replace( 'class="tribe-datepicker tribe-field-start_date ticket_field"', 'class="tribe-datepicker tribe-field-start_date ticket_field form-control d-sm-inline-block w-auto"', $output );
	$output = str_replace( 'class="tribe-timepicker tribe-field-start_time ticket_field"', 'class="tribe-timepicker tribe-field-start_time ticket_field form-control d-sm-inline-block w-auto"', $output );
	$output = str_replace( 'class="tribe-datepicker tribe-field-end_date ticket_field"', 'class="tribe-datepicker tribe-field-end_date ticket_field form-control d-sm-inline-block w-auto"', $output );
	$output = str_replace( 'class="tribe-timepicker tribe-field-end_time ticket_field"', 'class="tribe-timepicker tribe-field-end_time ticket_field form-control d-sm-inline-block w-auto"', $output );
	$output = str_replace( 'class="datetime_seperator"', 'class="datetime_seperator my-1 my-sm-0 mx-sm-2"', $output );
	$output = str_replace( 'class="datetime_separator"', 'class="datetime_separator my-1 my-sm-0 mx-sm-2"', $output );
	$output = str_replace( 'class="dashicons dashicons-editor-help"', 'class="dashicons dashicons-editor-help ms-2" data-bs-toggle="tooltip" data-bs-placement="top"', $output );

	$offset = strpos( $output, 'class="dashicons dashicons-editor-help ' );
	while ( false !== $offset ) {
		$end   = strpos( $output, '>', $offset );
		$start = strpos( $output, 'title="', $offset );
		if ( false !== $start && $start < $end ) {
			$output = substr_replace( $output, 'data-bs-', $start, 0 );
		}

		$offset = strpos( $output, 'class="dashicons dashicons-editor-help"', $offset + 1 );
	}

	$offset = strpos( $output, 'for="ticket_description"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<textarea', $offset );
		$output = substr_replace( $output, '<div class="col-md-9">' . "\n" . '<div class="mb-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</textarea>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 11, 0 );
		$offset = strpos( $output, '<div class="input_block row align-items-start">', $offset );
		$output = substr_replace( $output, 'form-check mb-3', $offset + 24, 21 );
		$offset = strpos( $output, '<label class="tribe_soft_note">', $offset );
		$output = substr_replace( $output, '', $offset, 31 );
		$offset = strpos( $output, 'class="ticket_field ticket_form_left"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 36, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<label for="tribe_tickets_show_description" class="tribe_soft_note form-check-label">', $offset + 1, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_editor_fieldset_advanced_output', 'enlightenment_tribe_bootstrap_template_editor_fieldset_advanced_output' );

function enlightenment_tribe_bootstrap_template_editor_fieldset_attendee_collection_output( $output ) {
	$output = str_replace( 'data-depends="#Tribe__Tickets__RSVP_radio"', 'data-depends="#Tribe__Tickets__RSVP_radio" class="accordion-item"', $output );

	$offset = strpos( $output, '<button class="accordion-header tribe_attendee-collection_meta">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' type="button" data-bs-toggle="collapse" data-bs-target="#ticket_form_attendee_collection" aria-expanded="false" aria-controls="ticket_form_attendee_collection"', $offset + 63, 0 );
		$output = substr_replace( $output, 'button collapsed', $offset + 25, 6 );
		$output = substr_replace( $output, '<div class="accordion-header">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
	}

	$output = str_replace( 'class="attendee-collection accordion-content"', 'class="attendee-collection accordion-content accordion-collapse collapse" data-bs-parent="#tribe-events-tickets-metabox-edit-accordion"', $output );
	$output = str_replace( 'class="accordion-label screen_reader_text"', 'class="accordion-label screen_reader_text visually-hidden"', $output );

	$offset = strpos( $output, '<section id="ticket_form_attendee_collection"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="accordion-body">', $offset + 1, 0 );
		$offset = strpos( $output, '</section><!-- #ticket_form_attendee-collection -->', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, '<section id="ticket_form_attendee_collection"' );
	if ( false !== $offset ) {
		$start = $offset;
		$end   = strpos( $output, '</section><!-- #ticket_form_attendee-collection -->', $start );

		$offset = strpos( $output, 'class="input_block"', $start );
		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' form-check', $offset + 18, 0 );
			$offset = strpos( $output, '<input', $offset );
			$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
			$offset = strpos( $output, '<label', $offset );
			$output = substr_replace( $output, ' class="form-check-label"', $offset + 6, 0 );

			$end    = strpos( $output, '</section><!-- #ticket_form_attendee-collection -->', $start );
			$offset = strpos( $output, 'class="input_block"', $offset );
		}
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_editor_fieldset_attendee_collection_output', 'enlightenment_tribe_bootstrap_template_editor_fieldset_attendee_collection_output' );

function enlightenment_tribe_bootstrap_template_editor_fieldset_history_output( $output ) {
	$output = str_replace( 'class="tribe-tickets-editor-history-container"', 'class="tribe-tickets-editor-history-container accordion-item"', $output );

	$offset = strpos( $output, '<button class="accordion-header tribe-tickets-editor-history">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' type="button" data-bs-toggle="collapse" data-bs-target="#tribe-tickets-editor-history" aria-expanded="false" aria-controls="tribe-tickets-editor-history"', $offset + 61, 0 );
		$output = substr_replace( $output, 'button collapsed', $offset + 25, 6 );
		$output = substr_replace( $output, '<div class="accordion-header">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
	}

	$output = str_replace( 'class="accordion-content"', 'class="accordion-content accordion-collapse collapse" data-bs-parent="#tribe-events-tickets-metabox-edit-accordion"', $output );
	$output = str_replace( 'class="accordion-label screen_reader_text"', 'class="accordion-label screen_reader_text visually-hidden"', $output );

	$offset = strpos( $output, '<ul class="tribe-tickets-editor-history-list">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' list-unstyled mb-0', $offset + 44, 0 );
		$output = substr_replace( $output, '<div class="accordion-body">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</ul>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 5, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_editor_fieldset_history_output', 'enlightenment_tribe_bootstrap_template_editor_fieldset_history_output' );

function enlightenment_tribe_bootstrap_template_editor_panel_settings_output( $output ) {
	$output = str_replace( 'class="button"', 'class="button btn btn-secondary btn-lg"', $output );
	$output = str_replace( 'class="button-primary"', 'class="button-primary btn btn-primary"', $output );
	$output = str_replace( 'class="button-secondary"', 'class="button-secondary btn btn-secondary"', $output );
	$output = str_replace( 'id="tribe_ticket_header_remove"', 'id="tribe_ticket_header_remove" class="text-danger"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_editor_panel_settings_output', 'enlightenment_tribe_bootstrap_template_editor_panel_settings_output' );

function enlightenment_tribe_bootstrap_template_editor_fieldset_settings_capacity_output( $output ) {
	$output = sprintf( '<div class="table-responsive">%s</div>', $output );
	$output = str_replace( 'class="eventtable ticket_list tribe-tickets-editor-capacity-table eventForm tribe-tickets-editor-table striped fixed"', 'class="eventtable ticket_list tribe-tickets-editor-capacity-table eventForm tribe-tickets-editor-table striped fixed table"', $output );
	$output = str_replace( 'class="settings_field"', 'class="settings_field form-control"', $output );

	$offset = strpos( $output, 'class="tribe-tickets-editor-table-row tribe-tickets-editor-table-row-capacity-shared"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<button', $offset );
		$output = substr_replace( $output, '</td>' . "\n" . '<td>' . "\n", $offset, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$offset = strpos( $output, '<td>', $offset );
		$output = substr_replace( $output, '', $offset, 4 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, '', $offset, 5 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_editor_fieldset_settings_capacity_output', 'enlightenment_tribe_bootstrap_template_editor_fieldset_settings_capacity_output' );

function enlightenment_tribe_bootstrap_template_editor_settings_attendees_output( $output ) {
	$output = str_replace( '<label>', '', $output );
	$output = str_replace( 'class="tribe_show_attendees settings_field"', 'class="tribe_show_attendees settings_field form-check-input"', $output );

	$offset = strpos( $output, '<p>' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="mb-3 form-check">', $offset, 3 );
		$offset = strpos( $output, '<span class="tribe-strong-label">', $offset );
		$close  = strpos( $output, '</span>', $offset ) + 7;
		$length = $close - $offset;
		$tag    = substr( $output, $offset, $length );
		$output = str_replace( $tag, '', $output );
		$tag    = str_replace( '<span class="tribe-strong-label">', '<label for="tribe_show_attendees" class="tribe-strong-label form-check-label">', $tag );
		$tag    = str_replace( '</span>', '', $tag );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, $tag, $offset, 8 );
		$offset = strpos( $output, '<p class="description">', $offset );
		$output = substr_replace( $output, '<span class="description d-block form-text">', $offset, 23 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, '</span></label>', $offset, 4 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 4 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_editor_settings_attendees_output', 'enlightenment_tribe_bootstrap_template_editor_settings_attendees_output' );

function enlightenment_tribe_bootstrap_table_menu( $output ) {
	return str_replace( 'class="button"', 'class="button btn btn-primary"', $output );
}
add_filter( 'enlightenment_tribe_filter_table_menu', 'enlightenment_tribe_bootstrap_table_menu' );

function enlightenment_tribe_bootstrap_tickets_attendees_page_inside( $output ) {
	$offset = strpos( $output, '<h2 class="nav-tab-wrapper">' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, '<ul class="nav-tab-wrapper nav nav-tabs">', $offset, 28 );
        $offset = strpos( $output, '</h2>', $offset );
        $output = substr_replace( $output, '</ul>', $offset, 5 );
    }

	$output = str_replace( 'class="nav-tab"', 'class="nav-tab nav-link"', $output );
	$output = str_replace( 'class="nav-tab nav-tab-active"', 'class="nav-tab nav-tab-active nav-link active"', $output );

	$offset = strpos( $output, '<a id="tribe-tickets-plus-woocommerce-orders-report"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<li class="nav-item">', $offset, 0 );
		$offset = strpos( $output, '</a>', $offset );
		$output = substr_replace( $output, '</li>', $offset + 4, 0 );
	}

	$offset = strpos( $output, '<a id="tribe-tickets-attendance-report"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<li class="nav-item">', $offset, 0 );
		$offset = strpos( $output, '</a>', $offset );
		$output = substr_replace( $output, '</li>', $offset + 4, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_tickets_attendees_page_inside', 'enlightenment_tribe_bootstrap_tickets_attendees_page_inside' );

function enlightenment_tribe_bootstrap_template_attendees_output( $output ) {
	$output = str_replace( 'class="welcome-panel-column-container"', 'class="welcome-panel-column-container list-group d-flex flex-column flex-lg-row gap-3"', $output );
	$output = str_replace( 'class="welcome-panel-column ', 'class="welcome-panel-column card ', $output );
	$output = str_replace( 'class="welcome-panel-column card welcome-panel-last alternate', 'class="welcome-panel-column card welcome-panel-last alternate text-bg-light', $output );
	$output = str_replace( '<h3>', '<h3 class="card-header h5">', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-overview-title"', 'class="tec-tickets__admin-attendees-overview-title card-header h5"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-overview-ticket-type"', 'class="tec-tickets__admin-attendees-overview-ticket-type d-flex align-items-center gap-2 h6"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-overview-ticket-type-border"', 'class="tec-tickets__admin-attendees-overview-ticket-type-border flex-grow-1 border-top"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-overview-ticket-type-list"', 'class="tec-tickets__admin-attendees-overview-ticket-type-list list-unstyled"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-overview-ticket-type-list-item"', 'class="tec-tickets__admin-attendees-overview-ticket-type-list-item d-flex align-items-baseline justify-content-between gap-2"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-overview-ticket-type-list-item-ticket-name"', 'class="tec-tickets__admin-attendees-overview-ticket-type-list-item-ticket-name fw-bold"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-overview-ticket-type-list-item-ticket-id"', 'class="tec-tickets__admin-attendees-overview-ticket-type-list-item-ticket-id fw-bold text-muted"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-overview-ticket-totals"', 'class="tec-tickets__admin-attendees-overview-ticket-totals d-flex align-items-baseline justify-content-between gap-2 border-top pt-3"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-overview-ticket-totals-title"', 'class="tec-tickets__admin-attendees-overview-ticket-totals-title fw-bold"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-attendance-type"', 'class="tec-tickets__admin-attendees-attendance-type mb-3"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-attendance-type-heading"', 'class="tec-tickets__admin-attendees-attendance-type-heading d-flex align-items-center gap-2 mb-2"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-attendance-type-heading-label"', 'class="tec-tickets__admin-attendees-attendance-type-heading-label h6 mb-0"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-attendance-type-heading-border"', 'class="tec-tickets__admin-attendees-attendance-type-heading-border flex-grow-1 border-top"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-attendance-type-complete"', 'class="tec-tickets__admin-attendees-attendance-type-complete d-flex align-items-baseline justify-content-between gap-2"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-attendance-type-cancelled"', 'class="tec-tickets__admin-attendees-attendance-type-cancelled d-flex align-items-baseline justify-content-between gap-2"', $output );
	$output = str_replace( '<ul>', '<ul class="list-unstyled">', $output );
	$output = str_replace( '<li>', '<li class="d-flex justify-content-between gap-2">', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-attendance-totals-row"', 'class="tec-tickets__admin-attendees-attendance-totals-row d-flex align-items-baseline justify-content-between gap-2 border-top pt-3"', $output );
	$output = str_replace( 'class="tec-tickets__admin-attendees-attendance-totals-title"', 'class="tec-tickets__admin-attendees-attendance-totals-title fw-bold"', $output );

	$offset = strpos( $output, '<div class="tribe-tooltip ' );
	while ( false !== $offset ) {
		$offset_a = strpos( $output, '<div class="down">', $offset );
		$offset_b = strpos( $output, '<span>', $offset_a ) + 6;
		$end_a    = strpos( $output, '<i>', $offset_b );
		$length   = $end_a - $offset_b;
		$title    = substr( $output, $offset_b, $length );
		$close_a  = strpos( $output, '</div>', $offset_a ) + 6;
		$length   = $close_a - $offset_a;
		$output   = substr_replace( $output, '', $offset_a, $length );
		$output   = substr_replace( $output, sprintf( '<span class="tribe-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="%s">', esc_attr( $title ) ), $offset, 28 );
		$offset   = strpos( $output, '</div>', $offset );
		$output   = substr_replace( $output, '</span>', $offset, 6 );

		$offset = strpos( $output, '<div class="tribe-tooltip ', $offset );
	}

	$offset = strpos( $output, 'class="welcome-panel-column ' );
	while ( false !== $offset ) {
		$offset_a = strrpos( $output, 'class="welcome-panel-column ', $offset - strlen( $output ) - 1 );
		if ( false !== $offset_a ) {
			$offset_a = strrpos( $output, '</div>', $offset - strlen( $output ) );
			$output   = substr_replace( $output, '</div>', $offset_a, 0 );
		}

		$offset = strpos( $output, '<h3 ', $offset );
		$offset = strpos( $output, '</h3>', $offset );
		$output = substr_replace( $output, '<div class="card-body">', $offset + 5, 0 );

		$offset = strpos( $output, 'class="welcome-panel-column ', $offset );
	}

	$offset = strpos( $output, 'class="welcome-panel-column ' );
	if ( false !== $offset ) {
		$offset_a = strpos( $output, '<div class="totals-header">' );
		if ( false !== $offset_a ) {
			$output   = substr_replace( $output, 'dummy-div', $offset_a + 1, 3 );
			$offset_a = strpos( $output, '</div>', $offset_a );
			$output   = substr_replace( $output, 'dummy-div', $offset_a + 2, 3 );
		}

		while ( false !== $offset ) {
			$offset_b = strpos( $output, '<ul class="tec-tickets__admin-attendees-attendance-type-list">', $offset );
			$end      = strpos( $output, '</div>', $offset );

			while ( false !== $offset_b && $offset_b < $end ) {
				$output   = substr_replace( $output, ' list-unstyled mb-0', $offset_b + 60, 0 );

				$offset_b = strpos( $output, '<ul class="tec-tickets__admin-attendees-attendance-type-list">', $offset_b );
				$end      = strpos( $output, '</div>', $offset );
			}

			$offset_b = strpos( $output, '<ul>', $offset );
			$end      = strpos( $output, '</div>', $offset );

			while ( false !== $offset_b && $offset_b < $end ) {
				$output   = substr_replace( $output, ' class="list-unstyled"', $offset_b + 3, 0 );

				$offset_b = strpos( $output, '<ul>', $offset_b );
				$end      = strpos( $output, '</div>', $offset );
			}

			$offset = strpos( $output, 'class="welcome-panel-column ', $offset + 1 );
		}

		$offset_a = strpos( $output, '<dummy-div class="totals-header">' );
		if ( false !== $offset_a ) {
			$output   = substr_replace( $output, 'div', $offset_a + 1, 9 );
			$offset_a = strpos( $output, '</dummy-div>', $offset_a );
			$output   = substr_replace( $output, 'div', $offset_a + 2, 9 );
		}
	}

	$offset = strpos( $output, '<li class="event-actions">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="btn-group btn-group-sm">', $offset + 26, 0 );

		$offset_a = strpos( $output, '<a ', $offset );
		$end      = strpos( $output, '</li>', $offset );
		while ( false !== $offset_a && $offset_a < $end ) {
			$output   = substr_replace( $output, 'class="btn btn-secondary" ', $offset_a + 3, 0 );

			$offset_b = strpos( $output, ' | ', $offset_a );
			if ( false !== $offset_b ) {
				$output = substr_replace( $output, '', $offset_b, 3 );
			}

			$offset_a = strpos( $output, '<a ', $offset_a + 1 );
		}

		$offset = strpos( $output, '</li>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );
	}

	$offset = strpos( $output, 'class="tec-tickets__admin-attendees-overview-ticket-type-icon tec-tickets__admin-attendees-overview-ticket-type-icon--rsvp"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="far fa-envelope"></i>', $offset + 1, 0 );
	}

	$offset = strpos( $output, 'class="tec-tickets__admin-attendees-overview-ticket-type-icon tec-tickets__admin-attendees-overview-ticket-type-icon--ticket"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-ticket-alt"></i>', $offset + 1, 0 );
	}

	$offset = strpos( $output, '<p class="search-box">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="search-box d-flex flex-column flex-md-row flex-wrap align-items-md-center gap-2 mb-3">', $offset, 24 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 4 );
	}

	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( "class='screen-reader-text'", "class='screen-reader-text visually-hidden'", $output );
	$output = str_replace( 'class="tribe-admin-search-type"', 'class="tribe-admin-search-type form-select w-auto"', $output );
	$output = str_replace( 'id="attendees-search-search-input"', 'id="attendees-search-search-input" class="form-control d-sm-inline-block w-auto flex-grow-1"', $output );
	$output = str_replace( 'class="button"', 'class="button btn btn-secondary"', $output );
	$output = str_replace( 'class="tablenav top"', 'class="tablenav top d-flex flex-column flex-sm-row flex-wrap align-items-sm-center gap-3 mb-3"', $output );
	$output = str_replace( 'class="tablenav bottom"', 'class="tablenav bottom d-flex flex-column flex-sm-row flex-wrap align-items-sm-center gap-3 mt-3"', $output );
	$output = str_replace( 'class="alignleft actions bulkactions"', 'class="actions bulkactions d-flex gap-2"', $output );
	$output = str_replace( 'id="bulk-action-selector-top"', 'id="bulk-action-selector-top" class="form-select form-select-sm"', $output );
	$output = str_replace( 'id="bulk-action-selector-bottom"', 'id="bulk-action-selector-bottom" class="form-select form-select-sm"', $output );
	$output = str_replace( 'class="button action"', 'class="button action btn btn-secondary"', $output );
	$output = str_replace( 'class="alignleft actions attendees-actions"', 'class="actions attendees-actions btn-group"', $output );
	$output = str_replace( 'class="print button action"', 'class="print button action btn btn-secondary"', $output );
	$output = str_replace( 'class="export button action"', 'class="export button action btn btn-secondary"', $output );
	$output = str_replace( 'class="email button action thickbox"', 'class="email button action thickbox btn btn-secondary"', $output );
	$output = str_replace( 'class="alignright attendees-filter"', 'class="attendees-filter ms-sm-auto"', $output );

	$offset = strpos( $output, "class='tablenav-pages one-page'" );
	while ( false !== $offset ) {
		$offset = strpos( $output, "class='pagination-links'", $offset );
		$output = substr_replace( $output, ' d-none', $offset + 23, 0 );

		$offset = strpos( $output, "class='tablenav-pages one-page'", $offset );
	}

	$output = str_replace( "class='tablenav-pages'", "class='tablenav-pages d-flex align-items-center gap-3 ms-sm-auto'", $output );
	$output = str_replace( "class='tablenav-pages one-page'", "class='tablenav-pages one-page ms-sm-auto'", $output );
	$output = str_replace( "class='tablenav-pages no-pages'", "class='tablenav-pages no-pages d-none'", $output );
	$output = str_replace( 'class="tablenav-pages-navspan button disabled"', 'class="tablenav-pages-navspan button disabled btn btn-secondary"', $output );
	$output = str_replace( "class='first-page button'", "class='first-page button btn btn-secondary'", $output );
	$output = str_replace( "class='prev-page button'", "class='prev-page button btn btn-secondary'", $output );
	$output = str_replace( 'class="paging-input"', 'class="paging-input d-inline-flex align-items-center"', $output );
	$output = str_replace( "class='current-page'", "class='current-page form-control form-control-sm text-center'", $output );
	$output = str_replace( "class='tablenav-paging-text'", "class='tablenav-paging-text text-nowrap ms-1'", $output );
	$output = str_replace( "class='next-page button'", "class='next-page button btn btn-secondary'", $output );
	$output = str_replace( "class='last-page button'", "class='last-page button btn btn-secondary'", $output );

	$offset = strpos( $output, '<table class="wp-list-table ' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="table-responsive">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</table>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	$output = str_replace( 'class="wp-list-table ', 'class="wp-list-table table ', $output );
	$output = str_replace( '<button type="button" class="toggle-row">', '<button type="button" class="toggle-row visually-hidden-focusable"><i class="fas fa-thumbtack"></i>', $output );
	$output = str_replace( 'class="button-primary  tickets_checkin"', 'class="button-primary  tickets_checkin btn btn-secondary"', $output );
	$output = str_replace( 'class="button-primary tickets_checkin"', 'class="button-primary tickets_checkin btn btn-secondary"', $output );
	$output = str_replace( 'class="button-secondary tickets_uncheckin"', 'class="button-secondary tickets_uncheckin btn btn-secondary"', $output );
	$output = str_replace( 'class="button-secondary tickets-checkin"', 'class="button-secondary tickets-checkin btn btn-secondary"', $output );

	$offset = strpos( $output, "<td  id='cb'" );
	while ( false !== $offset ) {
		$output = substr_replace( $output, 'h', $offset + 2, 1 );
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
		$output = substr_replace( $output, '<div class="form-check mb-0" style="min-height: auto;">', $offset, 0 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, 'h', $offset + 3, 1 );
		$output = substr_replace( $output, '</div>', $offset, 0 );

		$offset = strpos( $output, "<td  id='cb'", $offset );
	}

	$offset = strpos( $output, "<td   class='manage-column column-cb check-column'" );
	while ( false !== $offset ) {
		$output = substr_replace( $output, 'h', $offset + 2, 1 );
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
		$output = substr_replace( $output, '<div class="form-check mb-0" style="min-height: auto;">', $offset, 0 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, 'h', $offset + 3, 1 );
		$output = substr_replace( $output, '</div>', $offset, 0 );

		$offset = strpos( $output, "<td   class='manage-column column-cb check-column'", $offset );
	}

	$offset = strpos( $output, '<th scope="row" class="check-column">' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="form-check mt-1 mb-0" style="min-height: auto;">', $offset + 37, 0 );
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '</th>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );

		$offset = strpos( $output, '<th scope="row" class="check-column">', $offset );
	}

	$output = str_replace( 'class="components-button ', 'class="components-button btn btn-secondary ', $output );
	$output = str_replace( '<br class="clear" />', '', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_attendees_output', 'enlightenment_tribe_bootstrap_template_attendees_output' );

/* Close last .card-body */
add_action( 'tec_tickets_attendees_event_summary_table_extra', 'enlightenment_close_container', 1 );

function enlightenment_tribe_bootstrap_attendees_list( $output ) {
	return str_replace( "class='tribe-attendees-list'", "class='tribe-attendees-list list-unstyled d-flex mb-0'", $output );
}
add_filter( 'enlightenment_tribe_filter_attendees_list', 'enlightenment_tribe_bootstrap_attendees_list' );

function enlightenment_tribe_bootstrap_order_report( $output ) {
	$output = str_replace( 'class="welcome-panel-column-container"', 'class="welcome-panel-column-container list-group d-flex flex-column flex-lg-row gap-3"', $output );
	$output = str_replace( 'class="welcome-panel-column ', 'class="welcome-panel-column card ', $output );
	$output = str_replace( 'class="welcome-panel-column card welcome-panel-last alternate', 'class="welcome-panel-column card welcome-panel-last alternate text-bg-light', $output );
	$output = str_replace( '<h3>', '<h3 class="card-header h5">', $output );
	$output = str_replace( 'class="tec-tickets__admin-orders-report-overview-title"', 'class="tec-tickets__admin-orders-report-overview-title card-header h5"', $output );
	$output = str_replace( 'class="tec-tickets__admin-orders-report__sales-overview__data"', 'class="tec-tickets__admin-orders-report__sales-overview__data card-body"', $output );
	$output = str_replace( 'class="tec-tickets__admin-orders-report-overview-ticket-type"', 'class="tec-tickets__admin-orders-report-overview-ticket-type d-flex align-items-center gap-2 h6"', $output );
	$output = str_replace( 'class="tec-tickets__admin-orders-report-overview--border"', 'class="tec-tickets__admin-orders-report-overview--border flex-grow-1 border-top"', $output );
	$output = str_replace( 'class="tec-tickets__admin-orders-report-overview-ticket-type-list"', 'class="tec-tickets__admin-orders-report-overview-ticket-type-list list-unstyled"', $output );
	$output = str_replace( 'class="tec-tickets__admin-orders-report-overview-ticket-type-list-item"', 'class="tec-tickets__admin-orders-report-overview-ticket-type-list-item d-flex align-items-baseline justify-content-between gap-2"', $output );
	$output = str_replace( 'class="tec-tickets__admin-orders-report-overview-ticket-type-list-item-ticket-name"', 'class="tec-tickets__admin-orders-report-overview-ticket-type-list-item-ticket-name fw-bold"', $output );
	$output = str_replace( 'class="tec-tickets__admin-orders-report__sales-overview__by-status"', 'class="tec-tickets__admin-orders-report__sales-overview__by-status mb-3"', $output );
	$output = str_replace( 'class="tec-tickets__admin-orders-report__sales-overview__list__item"', 'class="tec-tickets__admin-orders-report__sales-overview__list__item d-flex align-items-baseline justify-content-between gap-2"', $output );
	$output = str_replace( 'class="tec-tickets__admin-orders-report__sales-overview__list__item-label"', 'class="tec-tickets__admin-orders-report__sales-overview__list__item-label fw-bold"', $output );
	$output = str_replace( 'class="tec-tickets__admin-orders-report__sales-overview__total"', 'class="tec-tickets__admin-orders-report__sales-overview__total mt-3"', $output );

	$offset = strpos( $output, '<div class="tribe-tooltip "' );
	while ( false !== $offset ) {
		$offset_a = strpos( $output, '<div class="down">', $offset );
		$offset_b = strpos( $output, '<span>', $offset_a ) + 6;
		$end_a    = strpos( $output, '<i>', $offset_b );
		$length   = $end_a - $offset_b;
		$title    = substr( $output, $offset_b, $length );
		$title    = str_replace( 'class="tooltip-list"', 'class="tooltip-list text-start"', $title );
		$title    = str_replace( '"', "'", $title );
		$close_a  = strpos( $output, '</div>', $offset_a ) + 6;
		$length   = $close_a - $offset_a;
		$output   = substr_replace( $output, '', $offset_a, $length );
		$output   = substr_replace( $output, sprintf( '<span class="tribe-tooltip" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-title="%s"', $title ), $offset, 27 );
		$offset   = strpos( $output, '</div>', $offset );
		$output   = substr_replace( $output, '</span>', $offset, 6 );

		$offset = strpos( $output, '<div class="tribe-tooltip "', $offset );
	}

	$offset = strpos( $output, '<div class="tribe-tooltip large"' );
	while ( false !== $offset ) {
		$offset_a = strpos( $output, '<div class="down">', $offset );
		$offset_b = strpos( $output, '<span>', $offset_a ) + 6;
		$end_a    = strpos( $output, '<i>', $offset_b );
		$length   = $end_a - $offset_b;
		$title    = substr( $output, $offset_b, $length );
		$title    = str_replace( '"', "'", $title );
		$close_a  = strpos( $output, '</div>', $offset_a ) + 6;
		$length   = $close_a - $offset_a;
		$output   = substr_replace( $output, '', $offset_a, $length );
		$output   = substr_replace( $output, sprintf( '<span class="tribe-tooltip" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-trigger="hover focus click" data-bs-title="%s"', $title ), $offset, 32 );
		$offset   = strpos( $output, '</div>', $offset );
		$output   = substr_replace( $output, '</span>', $offset, 6 );

		$offset = strpos( $output, '<div class="tribe-tooltip "', $offset );
	}

	$offset = strpos( $output, 'class="welcome-panel-column ' );
	while ( false !== $offset ) {
		$offset_a = strrpos( $output, 'class="welcome-panel-column ', $offset - strlen( $output ) - 1 );
		if ( false !== $offset_a ) {
			$offset_a = strrpos( $output, '</div>', $offset - strlen( $output ) );
			$output   = substr_replace( $output, '</div>', $offset_a, 0 );
		}

		$offset_a = strpos( $output, 'class="tec-tickets__admin-orders-report__sales-overview__title"', $offset );
		$end_a    = strpos( $output, '</div>', $offset );
		if ( false === $offset_a || $offset_a > $end_a ) {
			$offset = strpos( $output, '<h3 ', $offset );
			$offset = strpos( $output, '</h3>', $offset );
			$output = substr_replace( $output, '<div class="card-body">', $offset + 5, 0 );
		}

		$offset = strpos( $output, 'class="welcome-panel-column ', $offset + 28 );
	}

	$offset = strpos( $output, 'class="welcome-panel-column ' );
	if ( false !== $offset ) {
		$offset_a = strpos( $output, '<div class="totals-header">' );
		if ( false !== $offset_a ) {
			$output   = substr_replace( $output, 'dummy-div', $offset_a + 1, 3 );
			$offset_a = strpos( $output, '</div>', $offset_a );
			$output   = substr_replace( $output, 'dummy-div', $offset_a + 2, 3 );
		}

		while ( false !== $offset ) {
			$offset_b = strpos( $output, '<ul>', $offset );
			$end      = strpos( $output, '</div>', $offset );

			while ( false !== $offset_b && $offset_b < $end ) {
				$output   = substr_replace( $output, '<ul class="list-unstyled mb-0">', $offset_b, 4 );

				$offset_b = strpos( $output, '<ul>', $offset_b );
				$end      = strpos( $output, '</div>', $offset );
			}

			$offset = strpos( $output, 'class="welcome-panel-column ', $offset + 1 );
		}

		$offset_a = strpos( $output, '<dummy-div class="totals-header">' );
		if ( false !== $offset_a ) {
			$output   = substr_replace( $output, 'div', $offset_a + 1, 9 );
			$offset_a = strpos( $output, '</dummy-div>', $offset_a );
			$output   = substr_replace( $output, 'div', $offset_a + 2, 9 );
		}
	}

	$offset = strpos( $output, 'class="tec-tickets__admin-orders-report-overview-ticket-type-icon tec-tickets__admin-orders-report-overview-ticket-type-icon--default"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-ticket-alt"></i>', $offset + 1, 0 );
	}

	$output = str_replace( 'class="tribe-event-meta-note"', 'class="tribe-event-meta-note list-unstyled"', $output );

	$offset = strpos( $output, '<li class="event-actions">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="btn-group btn-group-sm">', $offset + 26, 0 );

		$offset_a = strpos( $output, '<a ', $offset );
		$end      = strpos( $output, '</li>', $offset );
		while ( false !== $offset_a && $offset_a < $end ) {
			$output   = substr_replace( $output, 'class="btn btn-secondary" ', $offset_a + 3, 0 );

			$offset_b = strpos( $output, ' | ', $offset_a );
			if ( false !== $offset_b ) {
				$output = substr_replace( $output, '', $offset_b, 3 );
			}

			$offset_a = strpos( $output, '<a ', $offset_a + 1 );
		}

		$offset = strpos( $output, '</li>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );
	}

	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="tablenav top"', 'class="tablenav top d-flex flex-wrap align-items-center mb-3"', $output );
	$output = str_replace( 'class="tablenav bottom"', 'class="tablenav bottom d-flex flex-wrap align-items-center mt-3"', $output );
	$output = str_replace( 'class="alignleft actions bulkactions"', 'class="actions bulkactions d-flex me-3"', $output );

	$offset = strpos( $output, "class='tablenav-pages one-page'" );
	while ( false !== $offset ) {
		$offset = strpos( $output, "class='pagination-links'", $offset );
		$output = substr_replace( $output, ' d-none', $offset + 23, 0 );

		$offset = strpos( $output, "class='tablenav-pages one-page'", $offset );
	}

	$output = str_replace( "class='tablenav-pages'", "class='tablenav-pages d-flex align-items-center gap-3 ms-sm-auto'", $output );
	$output = str_replace( "class='tablenav-pages one-page'", "class='tablenav-pages one-page ms-auto'", $output );
	$output = str_replace( "class='tablenav-pages no-pages'", "class='tablenav-pages no-pages d-none'", $output );
	$output = str_replace( 'class="tablenav-pages-navspan button disabled"', 'class="tablenav-pages-navspan button disabled btn btn-secondary"', $output );
	$output = str_replace( "class='first-page button'", "class='first-page button btn btn-secondary'", $output );
	$output = str_replace( "class='prev-page button'", "class='prev-page button btn btn-secondary'", $output );
	$output = str_replace( 'class="paging-input"', 'class="paging-input d-inline-flex align-items-center"', $output );
	$output = str_replace( "class='current-page'", "class='current-page form-control form-control-sm text-center'", $output );
	$output = str_replace( "class='tablenav-paging-text'", "class='tablenav-paging-text text-nowrap ms-1'", $output );
	$output = str_replace( "class='next-page button'", "class='next-page button btn btn-secondary'", $output );
	$output = str_replace( "class='last-page button'", "class='last-page button btn btn-secondary'", $output );

	$offset = strpos( $output, '<table class="wp-list-table ' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="table-responsive">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</table>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	$output = str_replace( 'class="wp-list-table ', 'class="wp-list-table table ', $output );
	$output = str_replace( 'class="toggle-row"', 'class="toggle-row d-none"', $output );
	$output = str_replace( 'class="order-status ', 'class="order-status badge ms-1 ', $output );
	$output = str_replace( ' order-status-pending"', ' order-status-on-hold text-bg-info"', $output );
	$output = str_replace( ' order-status-processing"', ' order-status-on-hold text-bg-info"', $output );
	$output = str_replace( ' order-status-on-hold"', ' order-status-on-hold text-bg-warning"', $output );
	$output = str_replace( ' order-status-completed"', ' order-status-on-hold text-bg-success"', $output );
	$output = str_replace( ' order-status-cancelled"', ' order-status-on-hold text-bg-danger"', $output );
	$output = str_replace( ' order-status-refunded"', ' order-status-on-hold text-bg-success"', $output );
	$output = str_replace( ' order-status-failed"', ' order-status-on-hold text-bg-danger"', $output );

	$output = str_replace( '<br class="clear" />', '', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_order_report', 'enlightenment_tribe_bootstrap_order_report' );

function enlightenment_tribe_bootstrap_template_part_community_tickets_modules_payment_options_output( $output ) {
	$offset = strpos( $output, '<div class="tribe-menu-wrapper">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, 'ul', $offset + 1, 3 );
		$offset = strpos( $output, 'class="tribe-menu-wrapper"', $offset );
		$output = substr_replace( $output, ' nav nav-tabs', $offset + 25, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, 'ul', $offset + 2, 3 );
	}

	$start = strpos( $output, '<ul class="tribe-menu-wrapper ' );
	if ( false !== $offset ) {
		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<a ', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, '<li class="nav-item">', $offset, 0 );
			$offset = strpos( $output, '<a ', $offset );
			$offset = strpos( $output, 'class="', $offset );
			$offset = strpos( $output, '"', $offset + 7 );
			$output = substr_replace( $output, ' nav-link', $offset, 0 );
			$offset = strpos( $output, '</a>', $offset );
			$output = substr_replace( $output, '</li>', $offset + 4, 0 );

			$end    = strpos( $output, '</ul>', $start );
			$offset = strpos( $output, '<a ', $offset );
		}
	}

	$output = str_replace( 'class="tribe-community-tickets-payment-options-link button nav-link"', 'class="tribe-community-tickets-payment-options-link button nav-link active"', $output );

	$offset = strpos( $output, '<tr>' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<td', $offset );

		$offset_a = strpos( $output, 'class="', $offset );
		$end_a    = strpos( $output, '>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$offset_a = strpos( $output, '"', $offset_a + 7 );
			$output   = substr_replace( $output, ' d-block col-12 col-md-3 py-0', $offset_a, 0 );
		} else {
			$output = substr_replace( $output, ' class="d-block col-12 col-md-3 py-0"', $offset + 3, 0 );
		}

		$offset_a = strpos( $output, '<label ', $offset );
		$end_a    = strpos( $output, '</td>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$offset_b = strpos( $output, 'class="', $offset_a );
			$end_b    = strpos( $output, '>', $offset_a );

			if ( false !== $offset_b && $offset_b < $end_b ) {
				$output = substr_replace( $output, 'mb-0 ', $offset_b + 7, 0 );
			} else {
				$output = substr_replace( $output, ' class="mb-0"', $offset_b + 6, 0 );
			}
		}

		$offset = strpos( $output, '</td>', $offset );
		$offset = strpos( $output, '<td', $offset );

		$offset_a = strpos( $output, 'class="', $offset );
		$end_a    = strpos( $output, '>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$offset_a = strpos( $output, '"', $offset_a + 7 );
			$output   = substr_replace( $output, ' d-block col-12 col-md-9 py-0', $offset_a, 0 );
		} else {
			$output = substr_replace( $output, ' class="d-block col-12 col-md-9 py-0"', $offset + 3, 0 );
		}

		$offset = strpos( $output, '<tr>', $offset );
	}

	$output = str_replace( 'class="tribe-section-container"', 'class="tribe-section-container mb-3"', $output );
	$output = str_replace( 'class="tribe-community-tickets-payment-options"', 'class="tribe-community-tickets-payment-options d-block w-100"', $output );
	$output = str_replace( 'class="tribe-community-tickets-payment-options"', 'class="tribe-community-tickets-payment-options d-block w-100"', $output );
	$output = str_replace( '<tbody>', '<tbody class="d-block">', $output );
	$output = str_replace( '<tr>', '<tr class="row align-items-center">', $output );
	$output = str_replace( 'id="paypal_account_email"', 'id="paypal_account_email" class="form-control"', $output );
	$output = str_replace( 'class="button submit events-community-submit"', 'class="button submit events-community-submit btn btn-primary btn-lg"', $output );

	$offset = strpos( $output, '<td class="note ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="form-text">', $offset + 1, 0 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_community_tickets_modules_payment_options_output', 'enlightenment_tribe_bootstrap_template_part_community_tickets_modules_payment_options_output' );

function enlightenment_tribe_bootstrap_login_form( $output ) {
	$output = str_replace( '<p>', '<p class="alert alert-info">', $output );
	$output = str_replace( 'class="login-username"', 'class="login-username mb-3"', $output );
	$output = str_replace( 'class="login-password"', 'class="login-password mb-3"', $output );
	$output = str_replace( 'class="login-remember"', 'class="login-remember mb-3 form-check"', $output );
	$output = str_replace( 'class="login-submit"', 'class="login-submit mb-3"', $output );
	$output = str_replace( 'class="input"', 'class="input form-control"', $output );
	$output = str_replace( 'id="rememberme"', 'id="rememberme" class="form-check-input"', $output );
	$output = str_replace( 'class="button button-primary"', 'class="button button-primary btn btn-primary btn-lg"', $output );
	$output = str_replace( 'class="tribe-ce-register"', 'class="tribe-ce-register d-inline"', $output );

	$offset = strpos( $output, 'class="login-remember ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<label', $offset );
		$close  = strpos( $output, '>', $offset ) + 1;
		$length = $close - $offset;
		$tag    = substr( $output, $offset, $length );
		$output = substr_replace( $output, '', $offset, $length );
		$tag    = str_replace( '<label>', '<label for="rememberme" class="form-check-label">', $tag );
		$offset = strpos( $output, 'id="rememberme"', $offset );
		$offset = strpos( $output, '/> ', $offset );
		$output = substr_replace( $output, "\n" . $tag, $offset + 3, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_login_form', 'enlightenment_tribe_bootstrap_login_form' );

function enlightenment_tribe_bootstrap_template_event_venue_output( $output, $template_name, $file, $template ) {
	$view = $template->get_view()->get_view_slug();

	return str_replace(
		sprintf( 'class="tribe-events-calendar-%s__event-venue tribe-common-b2"', $view ),
		sprintf( 'class="tribe-events-calendar-%s__event-venue tribe-common-b2 mb-0"', $view ),
		$output
	);
}
add_filter( 'enlightenment_tribe_filter_template_list_event_venue_output', 'enlightenment_tribe_bootstrap_template_event_venue_output', 10, 4 );
add_filter( 'enlightenment_tribe_filter_template_day_event_venue_output', 'enlightenment_tribe_bootstrap_template_event_venue_output', 10, 4 );
add_filter( 'enlightenment_tribe_filter_template_latest_past_event_venue_output', 'enlightenment_tribe_bootstrap_template_event_venue_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_part_pro_related_events_output( $output ) {
	if ( ! function_exists( 'tribe_get_related_posts' ) ) {
		return $output;
	}

    $posts = tribe_get_related_posts();

	if ( empty( $posts ) ) {
		return $output;
	}

	$count   = count( $posts );
	$colspan = ( 0 === $count % 4 ) ? 3 : ( ( 0 === $count % 6 ) ? 4 : ( ( 0 === $count % 2 ) ? 6 : 4 ) );

	$output = str_replace( 'class="tribe-related-events tribe-clearfix"', 'class="tribe-related-events list-unstyled row mb-0"', $output );
	$output = str_replace( '<li>', sprintf( '<li class="col-sm-%s">', $colspan ), $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_part_pro_related_events_output', 'enlightenment_tribe_bootstrap_template_part_pro_related_events_output' );

function enlightenment_tribe_bootstrap_template_nav_output( $output ) {
	$context = enlightenment_tribe_template()->get_values();

	if ( empty( $context['prev_url'] ) && empty( $context['next_url'] ) ) {
		$output = str_replace( 'class="tribe-events-pro-map__nav tribe-events-c-nav"', 'class="tribe-events-pro-map__nav tribe-events-c-nav d-lg-none"', $output );
		$output = str_replace( 'class="tribe-events-calendar-list-nav tribe-events-c-nav"', 'class="tribe-events-calendar-list-nav tribe-events-c-nav d-lg-none"', $output );
	}

	$output = str_replace( 'class="tribe-events-c-nav__list"', 'class="tribe-events-c-nav__list list-unstyled row mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_list_nav_output', 'enlightenment_tribe_bootstrap_template_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_month_mobile_events_nav_output', 'enlightenment_tribe_bootstrap_template_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_day_nav_output', 'enlightenment_tribe_bootstrap_template_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_nav_output', 'enlightenment_tribe_bootstrap_template_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_week_mobile_events_nav_output', 'enlightenment_tribe_bootstrap_template_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_nav_output', 'enlightenment_tribe_bootstrap_template_nav_output' );
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_nav_output', 'enlightenment_tribe_bootstrap_template_nav_output' );

function enlightenment_tribe_bootstrap_template_nav_prev_output( $output ) {
	$colspan = 'day' == enlightenment_tribe_get_view() ? 'col-6' : 'col-4 col-lg-6';

	$output = str_replace( 'class="tribe-events-c-nav__list-item tribe-events-c-nav__list-item--prev"', sprintf( 'class="tribe-events-c-nav__list-item tribe-events-c-nav__list-item--prev %s"', $colspan ), $output );
	$output = str_replace( 'class="tribe-events-c-nav__prev-label-plural tribe-common-a11y-visual-hide screen-reader-text"', 'class="tribe-events-c-nav__prev-label-plural tribe-common-a11y-visual-hide screen-reader-text visually-hidden"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_list_nav_prev_output', 'enlightenment_tribe_bootstrap_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_month_mobile_events_nav_prev_output', 'enlightenment_tribe_bootstrap_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_day_nav_prev_output', 'enlightenment_tribe_bootstrap_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_nav_prev_output', 'enlightenment_tribe_bootstrap_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_week_mobile_events_nav_prev_output', 'enlightenment_tribe_bootstrap_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_nav_prev_output', 'enlightenment_tribe_bootstrap_template_nav_prev_output' );
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_nav_prev_output', 'enlightenment_tribe_bootstrap_template_nav_prev_output' );

function enlightenment_tribe_bootstrap_template_nav_today_output( $output ) {
	return str_replace( 'class="tribe-events-c-nav__list-item tribe-events-c-nav__list-item--today"', 'class="tribe-events-c-nav__list-item tribe-events-c-nav__list-item--today col-4 d-lg-none text-center"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_list_nav_today_output', 'enlightenment_tribe_bootstrap_template_nav_today_output' );
add_filter( 'enlightenment_tribe_filter_template_month_mobile_events_nav_today_output', 'enlightenment_tribe_bootstrap_template_nav_today_output' );
add_filter( 'enlightenment_tribe_filter_template_day_nav_today_output', 'enlightenment_tribe_bootstrap_template_nav_today_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_nav_today_output', 'enlightenment_tribe_bootstrap_template_nav_today_output' );
add_filter( 'enlightenment_tribe_filter_template_week_mobile_events_nav_today_output', 'enlightenment_tribe_bootstrap_template_nav_today_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_nav_today_output', 'enlightenment_tribe_bootstrap_template_nav_today_output' );
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_nav_today_output', 'enlightenment_tribe_bootstrap_template_nav_today_output' );

function enlightenment_tribe_bootstrap_template_nav_next_output( $output ) {
	$colspan = 'day' == enlightenment_tribe_get_view() ? 'col-6' : 'col-4 col-lg-6';

	$output = str_replace( 'class="tribe-events-c-nav__list-item tribe-events-c-nav__list-item--next"', sprintf( 'class="tribe-events-c-nav__list-item tribe-events-c-nav__list-item--next %s text-end"', $colspan ), $output );
	$output = str_replace( 'class="tribe-events-c-nav__next-label-plural tribe-common-a11y-visual-hide"', 'class="tribe-events-c-nav__next-label-plural tribe-common-a11y-visual-hide screen-reader-text"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_list_nav_next_output', 'enlightenment_tribe_bootstrap_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_month_mobile_events_nav_next_output', 'enlightenment_tribe_bootstrap_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_day_nav_next_output', 'enlightenment_tribe_bootstrap_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_nav_next_output', 'enlightenment_tribe_bootstrap_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_week_mobile_events_nav_next_output', 'enlightenment_tribe_bootstrap_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_nav_next_output', 'enlightenment_tribe_bootstrap_template_nav_next_output' );
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_nav_next_output', 'enlightenment_tribe_bootstrap_template_nav_next_output' );

function enlightenment_tribe_bootstrap_template_nav_disabled_output( $output ) {
	$colspan = 'day' == enlightenment_tribe_get_view() ? 'col-6' : 'col-4 col-lg-6';

	return sprintf( '<li class="%s"></li>', esc_attr( $colspan ) );
}
add_filter( 'enlightenment_tribe_filter_template_list_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_month_mobile_events_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_day_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_week_mobile_events_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_nav_prev_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_list_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_month_mobile_events_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_day_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_summary_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_week_mobile_events_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_photo_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );
add_filter( 'enlightenment_tribe_filter_template_map_event_cards_nav_next_disabled_output', 'enlightenment_tribe_bootstrap_template_nav_disabled_output' );

function enlightenment_tribe_bootstrap_template_components_ical_link_output( $output ) {
	$output = str_replace( 'class="tribe-events-c-ical tribe-common-b2 tribe-common-b3--min-medium"', 'class="tribe-events-c-ical tribe-common-b2 tribe-common-b3--min-medium d-flex"', $output );
	$output = str_replace( 'class="tribe-events-c-ical__link"', 'class="tribe-events-c-ical__link btn btn-primary mx-auto me-lg-0"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_ical_link_output', 'enlightenment_tribe_bootstrap_template_components_ical_link_output' );

function enlightenment_tribe_bootstrap_template_components_subscribe_links_list_output( $output ) {
	$output = str_replace( 'class="tribe-events-c-subscribe-dropdown"', 'class="tribe-events-c-subscribe-dropdown dropdown d-flex"', $output );
	$output = str_replace( 'class="tribe-events-c-subscribe-dropdown__button-text tribe-common-c-btn--clear"', 'class="tribe-events-c-subscribe-dropdown__button-text tribe-common-c-btn--clear btn btn-primary dropdown-toggle mx-auto me-lg-0" data-bs-toggle="dropdown" aria-expanded="false"', $output );
	$output = str_replace( 'class="tribe-events-c-subscribe-dropdown__button-text"', 'class="tribe-events-c-subscribe-dropdown__button-text btn btn-primary dropdown-toggle mx-auto me-lg-0" data-bs-toggle="dropdown" aria-expanded="false"', $output );
	$output = str_replace( 'class="tribe-events-c-subscribe-dropdown__content"', 'class="tribe-events-c-subscribe-dropdown__content dropdown-menu"', $output );
	$output = str_replace( 'class="tribe-events-c-subscribe-dropdown__list"', 'class="tribe-events-c-subscribe-dropdown__list list-unstyled mb-0"', $output );

	$offset = strpos( $output, 'class="tribe-common-c-btn-border tribe-events-c-subscribe-dropdown__button"' );
	if ( false !== $offset ) {
		$start  = strrpos( $output, '<div', $offset - strlen( $output ) );
		$end    = strpos(  $output, '>', $offset ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
		$offset = strpos( $output, '</div>', $start );
		$output = substr_replace( $output, '', $offset, 6 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_subscribe_links_list_output', 'enlightenment_tribe_bootstrap_template_components_subscribe_links_list_output' );

function enlightenment_tribe_bootstrap_template_components_subscribe_links_item_output( $output ) {
	return str_replace( 'class="tribe-events-c-subscribe-dropdown__list-item-link"', 'class="tribe-events-c-subscribe-dropdown__list-item-link dropdown-item"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_components_subscribe_links_item_output', 'enlightenment_tribe_bootstrap_template_components_subscribe_links_item_output' );
add_filter( 'enlightenment_tribe_filter_template_blocks_parts_subscribe_list_output', 'enlightenment_tribe_bootstrap_template_components_subscribe_links_item_output' );

function enlightenment_tribe_bootstrap_template_components_subscribe_links_single_event_list_output( $output ) {
	$output = str_replace( 'class="tribe-events-c-subscribe-dropdown"', 'class="tribe-events-c-subscribe-dropdown dropdown d-flex"', $output );
	$output = str_replace( 'class="tribe-events-c-subscribe-dropdown__content"', 'class="tribe-events-c-subscribe-dropdown__content dropdown-menu"', $output );
	$output = str_replace( 'class="tribe-events-c-subscribe-dropdown__list"', 'class="tribe-events-c-subscribe-dropdown__list list-unstyled mb-0"', $output );

	$offset = strpos( $output, '<div class="tribe-common-c-btn-border tribe-events-c-subscribe-dropdown__button" tabindex="0">' );
	$length = 94;
	if ( false === $offset ) {
		$offset = strpos( $output, '<div class="tribe-common-c-btn-border tribe-events-c-subscribe-dropdown__button">' );
		$length = 81;
	}
	if ( false !== $offset ) {
		$atts = ' aria-expanded="false"';

		$offset_a = strpos( $output, '<button', $offset );
		if ( false !== $offset ) {
			$offset_a += 7;
			$end_a     = strpos( $output, '>', $offset_a );
			$length_a  = $end_a - $offset_a;
			$atts      = substr( $output, $offset_a, $length_a );
			$atts      = str_replace( 'class="tribe-events-c-subscribe-dropdown__button-text tribe-common-c-btn--clear"', '', $atts );
			$atts      = str_replace( 'class="tribe-events-c-subscribe-dropdown__button-text"', '', $atts );
			$atts      = trim( $atts );
			$atts      = sprintf( ' %s', $atts );

			$output    = substr_replace( $output, ' class="tribe-events-c-subscribe-dropdown__button-text"', $offset_a, $length_a );
		}

		$output = substr_replace( $output, sprintf( '<button class="tribe-common-c-btn-border tribe-events-c-subscribe-dropdown__button btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"%s>', $atts ), $offset, $length );
		$offset = strpos( $output, '<button', $offset + 1 );
		$output = substr_replace( $output, 'span', $offset + 1, 6 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, 'span', $offset + 2, 6 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</button>', $offset, 6 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_components_subscribe_links_single_event_list_output', 'enlightenment_tribe_bootstrap_template_components_subscribe_links_single_event_list_output' );
add_filter( 'enlightenment_tribe_filter_template_blocks_parts_subscribe_list_output', 'enlightenment_tribe_bootstrap_template_components_subscribe_links_single_event_list_output' );

function enlightenment_tribe_bootstrap_template_components_icons_cal_export_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--cal-export ' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s far fa-calendar-plus" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_cal_export_output', 'enlightenment_tribe_bootstrap_template_components_icons_cal_export_output', 10, 4 );
add_filter( 'enlightenment_tribe_filter_template_v2_components_icons_cal_export_output', 'enlightenment_tribe_bootstrap_template_components_icons_cal_export_output', 10, 4 );

function enlightenment_tribe_bootstrap_template_components_icons_plus_output( $output, $template_name, $file, $template ) {
	$context = $template->get_values();
	$classes = array( 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--plus' );

	if ( ! empty( $context['classes'] ) ) {
		$classes = array_merge( $classes, $context['classes'] );
	}

	$classes = join( ' ', $classes );

	return sprintf( '<i class="%s fas fa-plus" role="presentation" aria-hidden="true"></i>', $classes );
}
add_filter( 'enlightenment_tribe_filter_template_components_icons_plus_output', 'enlightenment_tribe_bootstrap_template_components_icons_plus_output', 10, 4 );

function enlightenment_tribe_bootstrap_woocommerce_event_info( $output ) {
	return sprintf( '<div class="tribe-woocommerce-event-info-dropdown dropdown position-static"><a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a><ul class="dropdown-menu"><li class="dropdown-item">%s</li></ul></div>', $output );
}
add_filter( 'enlightenment_tribe_filter_woocommerce_event_info', 'enlightenment_tribe_bootstrap_woocommerce_event_info' );

function enlightenment_tribe_bootstrap_list_widget( $output ) {
	$output = str_replace( 'class="tribe-list-widget"', 'class="tribe-list-widget list-unstyled"', $output );
	$output = str_replace( 'class="tribe-events-widget-link"', 'class="tribe-events-widget-link d-flex justify-content-center mb-0"', $output );

	$offset = strpos( $output, 'class="tribe-events-widget-link ' );

	if ( false !== $offset ) {
		$offset = strpos( $output, '<a ', $offset );
		$output = substr_replace( $output, 'class="btn btn-secondary" ', $offset + 3, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_list_widget', 'enlightenment_tribe_bootstrap_list_widget' );

function enlightenment_tribe_bootstrap_template_widgets_widget_events_list_event_output( $output ) {
	$output = str_replace( 'class="tribe-common-g-row tribe-events-widget-events-list__event-row ', 'class="tribe-common-g-row tribe-events-widget-events-list__event-row row g-0 align-items-start ', $output );
	$output = str_replace( 'class="tribe-common-g-row tribe-events-widget-events-list__event-row"', 'class="tribe-common-g-row tribe-events-widget-events-list__event-row row g-0 align-items-start"', $output );
	$output = str_replace( 'class="tribe-events-widget-events-list__event-wrapper tribe-common-g-col"', 'class="tribe-events-widget-events-list__event-wrapper tribe-common-g-col col"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_events_list_event_output', 'enlightenment_tribe_bootstrap_template_widgets_widget_events_list_event_output' );

function enlightenment_tribe_bootstrap_template_widgets_widget_events_list_event_date_tag_output( $output ) {
	return str_replace( 'class="tribe-events-widget-events-list__event-date-tag tribe-common-g-col"', 'class="tribe-events-widget-events-list__event-date-tag tribe-common-g-col col flex-grow-0 flex-shrink-1 text-center me-3"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_events_list_event_date_tag_output', 'enlightenment_tribe_bootstrap_template_widgets_widget_events_list_event_date_tag_output' );

function enlightenment_tribe_bootstrap_template_widgets_widget_events_list_event_date_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-widget-events-list__event-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-widget-events-list__event-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_events_list_event_date_recurring_output', 'enlightenment_tribe_bootstrap_template_widgets_widget_events_list_event_date_recurring_output' );

function enlightenment_tribe_bootstrap_template_widgets_widget_events_list_view_more_output( $output ) {
	$output = str_replace( 'class="tribe-events-widget-events-list__view-more tribe-common-b1 tribe-common-b2--min-medium"', 'class="tribe-events-widget-events-list__view-more tribe-common-b1 tribe-common-b2--min-medium mt-3"', $output );
	$output = str_replace( 'class="tribe-events-widget-events-list__view-more-link tribe-common-anchor-thin"', 'class="tribe-events-widget-events-list__view-more-link tribe-common-anchor-thin btn btn-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_events_list_view_more_output', 'enlightenment_tribe_bootstrap_template_widgets_widget_events_list_view_more_output' );

function enlightenment_tribe_bootstrap_venue_widget( $output ) {
	$output = str_replace( 'class="tribe-venue-widget-list"', 'class="tribe-venue-widget-list list-unstyled"', $output );
	$output = str_replace( 'class="tribe-events-widget-link"', 'class="tribe-events-widget-link d-flex justify-content-center mb-0"', $output );

	$offset = strpos( $output, 'class="tribe-venue-widget-list ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '</ul>', $offset );
		$offset = strpos( $output, '<a ', $offset );
		$output = substr_replace( $output, '<div class="d-flex justify-content-center"><a class="btn btn-secondary" ', $offset, 3 );
		$offset = strpos( $output, '</a>', $offset );
		$output = substr_replace( $output, '</div>', $offset + 4, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_tribe_filter_venue_widget', 'enlightenment_tribe_bootstrap_venue_widget' );

function enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_venue_details_output( $output ) {
	$output = str_replace( 'class="tribe-common-h7 tribe-common-h--alt tribe-events-widget-featured-venue__venue-info-group ', 'class="tribe-common-h7 tribe-common-h--alt tribe-events-widget-featured-venue__venue-info-group d-flex ', $output );
	$output = str_replace( 'class="tribe-events-widget-featured-venue__venue-icon"', 'class="tribe-events-widget-featured-venue__venue-icon flex-grow-0 flex-shrink-1 me-2"', $output );
	$output = str_replace( 'class="tribe-events-widget-featured-venue__venue-content ', 'class="tribe-events-widget-featured-venue__venue-content flex-grow-1 flex-shrink-0 ', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_featured_venue_venue_address_output', 'enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_venue_details_output' );
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_featured_venue_venue_phone_output', 'enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_venue_details_output' );
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_featured_venue_venue_website_output', 'enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_venue_details_output' );

function enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_events_list_event_output( $output ) {
	$output = str_replace( 'class="tribe-common-g-row tribe-events-widget-featured-venue__event-row ', 'class="tribe-common-g-row tribe-events-widget-featured-venue__event-row row g-0 align-items-start ', $output );
	$output = str_replace( 'class="tribe-common-g-row tribe-events-widget-featured-venue__event-row"', 'class="tribe-common-g-row tribe-events-widget-featured-venue__event-row row g-0 align-items-start"', $output );
	$output = str_replace( 'class="tribe-common-g-col tribe-events-widget-featured-venue__event-wrapper"', 'class="tribe-common-g-col tribe-events-widget-featured-venue__event-wrapper col"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_featured_venue_events_list_event_output', 'enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_events_list_event_output' );

function enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_events_list_event_date_tag_output( $output ) {
	return str_replace( 'class="tribe-common-g-col tribe-events-widget-featured-venue__event-date-tag"', 'class="tribe-common-g-col tribe-events-widget-featured-venue__event-date-tag col flex-grow-0 flex-shrink-1 text-center me-3"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_featured_venue_events_list_event_date_tag_output', 'enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_events_list_event_date_tag_output' );

function enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_events_list_event_date_recurring_output( $output ) {
	return str_replace( 'class="tribe-events-widget-featured-venue__event-datetime-recurring-text tribe-common-a11y-visual-hide"', 'class="tribe-events-widget-featured-venue__event-datetime-recurring-text tribe-common-a11y-visual-hide visually-hidden"', $output );
}
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_featured_venue_events_list_event_date_recurring_output', 'enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_events_list_event_date_recurring_output' );

function enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_view_more_output( $output ) {
	$output = str_replace( 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-events-widget-featured-venue__view-more"', 'class="tribe-common-b1 tribe-common-b2--min-medium tribe-events-widget-featured-venue__view-more mt-3"', $output );
	$output = str_replace( 'class="tribe-common-anchor-thin tribe-events-widget-featured-venue__view-more-link"', 'class="tribe-common-anchor-thin tribe-events-widget-featured-venue__view-more-link btn btn-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_tribe_filter_template_widgets_widget_featured_venue_view_more_output', 'enlightenment_tribe_bootstrap_template_widgets_widget_featured_venue_view_more_output' );

function enlightenment_tribe_bootstrap_classic_event_details_block( $output ) {
	if ( ! tribe_has_organizer() ) {
		return $output;
	}

    $output = str_replace( 'class="wp-block-tribe-classic-event-details ', 'class="wp-block-tribe-classic-event-details row ', $output );
	$output = str_replace( 'class="tribe-events-meta-group ', 'class="tribe-events-meta-group col ', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_tribe_classic_event_details', 'enlightenment_tribe_bootstrap_classic_event_details_block' );

function enlightenment_tribe_bootstrap_event_venue_block( $output ) {
	if ( false === strpos( $output, ' tribe-block__venue--has-map"' ) ) {
		return $output;
	}

    $output = str_replace( 'class="wp-block-tribe-venue ', 'class="wp-block-tribe-venue row justify-content-start w-auto ', $output );
	$output = str_replace( ' tribe-block__venue--has-map"', '"', $output );
	$output = str_replace( 'class="tribe-block__venue__meta"', 'class="tribe-block__venue__meta col-12 col-md"', $output );
	$output = str_replace( 'class="tribe-block__venue__map"', 'class="tribe-block__venue__map col-12 col-md"', $output );

    return $output;
}
add_filter( 'enlightenment_render_block_tribe_event_venue', 'enlightenment_tribe_bootstrap_event_venue_block' );

function enlightenment_tribe_bootstrap_event_website_block( $output ) {
    $output = str_replace( 'class="wp-block-tribe-event-website tribe-block tribe-block__event-website"', 'class="wp-block-tribe-event-website tribe-block"', $output );
	$output = str_replace( '<a' . "\n", '<a class="btn btn-primary"' . "\n", $output );

    return $output;
}
add_filter( 'enlightenment_render_block_tribe_event_website', 'enlightenment_tribe_bootstrap_event_website_block' );

function enlightenment_tribe_bootstrap_event_links_block( $output ) {
	$output = str_replace( 'class="tribe-block__btn--link ', 'class="', $output );

	$start = strpos( $output, '<a' . "\n" );
	if ( false !== $start ) {
		$end    = strpos( $output, '>', $start );
		$offset = strpos( $output, 'class="', $start );

		if ( false === $offset || $offset > $end ) {
			$output = substr_replace( $output, ' class="btn btn-secondary"', $start + 2, 0 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_render_block_tribe_event_links', 'enlightenment_tribe_bootstrap_event_links_block' );

function enlightenment_tribe_bootstrap_related_events_block( $output ) {
	if ( ! function_exists( 'tribe_get_related_posts' ) ) {
		return $output;
	}

    $posts = tribe_get_related_posts();

	if ( empty( $posts ) ) {
		return $output;
	}

	$count   = count( $posts );
	$colspan = ( 0 === $count % 4 ) ? 3 : ( ( 0 === $count % 6 ) ? 4 : ( ( 0 === $count % 2 ) ? 6 : 4 ) );

	$output = str_replace( 'class="tribe-related-events tribe-clearfix"', 'class="tribe-related-events list-unstyled row mb-0"', $output );
	$output = str_replace( '<li>', sprintf( '<li class="col-sm-%s">', $colspan ), $output );

	return $output;
}
add_filter( 'enlightenment_render_block_tribe_related_events', 'enlightenment_tribe_bootstrap_related_events_block' );
