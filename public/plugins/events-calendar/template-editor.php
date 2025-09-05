<?php

function enlightenment_tribe_template_functions( $functions ) {
	$functions['enlightenment_events_messages']            = __( 'Events Messages',        'enlightenment' );
	$functions['enlightenment_events_bar']                 = __( 'Events Bar',             'enlightenment' );
	$functions['enlightenment_events_top_bar']             = __( 'Events Top Bar',         'enlightenment' );
	$functions['enlightenment_tribe_venue_meta']           = __( 'Venue Meta',             'enlightenment' );
	$functions['enlightenment_tribe_organizer_meta']       = __( 'Organizer Meta',         'enlightenment' );
	$functions['enlightenment_events_filter_bar']          = __( 'Events Filter Bar',      'enlightenment' );
	$functions['enlightenment_events_separator']           = __( 'Events Separator',       'enlightenment' );
	$functions['enlightenment_events_summary']             = __( 'Events Summary',         'enlightenment' );
	$functions['enlightenment_events_month_calendar']      = __( 'Month Calendar',         'enlightenment' );
	$functions['enlightenment_events_month_mobile_events'] = __( 'Month Mobile Events',    'enlightenment' );
	$functions['enlightenment_events_week_day_selector']   = __( 'Week Day Selector',      'enlightenment' );
	$functions['enlightenment_events_week_mobile_events']  = __( 'Week Mobile Events',     'enlightenment' );
	$functions['enlightenment_events_week_grid']           = __( 'Week Grid',              'enlightenment' );
	$functions['enlightenment_events_map']                 = __( 'Events Map',             'enlightenment' );
	$functions['enlightenment_event_date_tag']             = __( 'Event Date Tag',         'enlightenment' );
	$functions['enlightenment_single_event_back_link']     = __( 'Single Event Back Link', 'enlightenment' );
	$functions['enlightenment_tribe_the_notices']          = __( 'Event Notices',          'enlightenment' );
	$functions['enlightenment_events_ical_link']           = __( 'Subscribe to Calendar',  'enlightenment' );
	$functions['enlightenment_single_event_ical_links']    = __( 'Add Event to Calendar',  'enlightenment' );
	$functions['enlightenment_event_cost']                 = __( 'Event Price',            'enlightenment' );
	$functions['enlightenment_single_event_meta']          = __( 'Event Meta',             'enlightenment' );
	$functions['enlightenment_related_events']             = __( 'Related Events',         'enlightenment' );

	return $functions;
}
add_filter( 'enlightenment_template_functions', 'enlightenment_tribe_template_functions' );

function enlightenment_tribe_page_content_hooks( $hooks ) {
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_events_messages';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_events_bar';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_events_top_bar';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_tribe_venue_meta';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_tribe_organizer_meta';

	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_events_filter_bar';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_events_summary';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_events_month_calendar';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_events_month_mobile_events';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_events_week_day_selector';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_events_week_mobile_events';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_events_week_grid';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_events_map';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_events_ical_link';

	return $hooks;
}
add_filter( 'enlightenment_page_content_hooks', 'enlightenment_tribe_page_content_hooks' );

function enlightenment_tribe_entry_hooks( $hooks ) {
	$hooks['enlightenment_before_entry']['functions'][] = 'enlightenment_events_separator';

	$hooks['enlightenment_before_entry_header']['functions'][] = 'enlightenment_event_date_tag';

	$hooks['enlightenment_entry_header']['functions'][] = 'enlightenment_single_event_back_link';
	$hooks['enlightenment_entry_header']['functions'][] = 'enlightenment_tribe_the_notices';

	$hooks['enlightenment_after_entry_header']['functions'][] = 'enlightenment_event_date_tag';

	$hooks['enlightenment_entry_content']['functions'][] = 'enlightenment_single_event_ical_links';
	$hooks['enlightenment_entry_content']['functions'][] = 'enlightenment_event_cost';

	$hooks['enlightenment_entry_footer']['functions'][] = 'enlightenment_event_cost';
	$hooks['enlightenment_entry_footer']['functions'][] = 'enlightenment_single_event_meta';
	$hooks['enlightenment_entry_footer']['functions'][] = 'enlightenment_related_events';

	return $hooks;
}
add_filter( 'enlightenment_entry_hooks', 'enlightenment_tribe_entry_hooks' );
