<?php

function enlightenment_tribe_venue_archive_layout( $layouts ) {
    global $wp_query;

    $layouts['tribe_venue']        = $layouts['tribe_events-archive'];
    $layouts['tribe_organizer']    = $layouts['tribe_events-archive'];
	$layouts['tribe_event_series'] = $layouts['tribe_events-archive'];

    if ( $wp_query->is_search() && 'tribe_events' == $wp_query->get( 'post_type' ) ) {
        $layouts['search'] = $layouts['tribe_events-archive'];
    }

    return $layouts;
}
add_filter( 'enlightenment_archive_layouts', 'enlightenment_tribe_venue_archive_layout', 16 );

function enlightenment_tribe_ar_page_layout( $layout ) {
    if ( ! enlightenment_tribe_is_ar_page() ) {
        return $layout;
    }

    if ( is_page() ) {
        return $layout;
    }

    if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
        return array(
            'smartphone-portrait'  => 'full-width',
            'smartphone-landscape' => 'inherit',
            'tablet-portrait'      => 'inherit',
            'tablet-landscape'     => 'inherit',
            'desktop-laptop'       => 'inherit',
        );
    }

    return 'full-width';
}
add_filter( 'enlightenment_current_layout', 'enlightenment_tribe_ar_page_layout' );

function enlightenment_tribe_my_events_page_layout( $layout ) {
    if ( ! enlightenment_tribe_is_community_my_events_page() ) {
        return $layout;
    }

    if ( is_page() ) {
        return $layout;
    }

    if( current_theme_supports( 'enlightenment-bootstrap' ) ) {
        return array(
            'smartphone-portrait'  => 'full-width',
            'smartphone-landscape' => 'inherit',
            'tablet-portrait'      => 'inherit',
            'tablet-landscape'     => 'inherit',
            'desktop-laptop'       => 'inherit',
        );
    }

    return 'full-width';
}
add_filter( 'enlightenment_current_layout', 'enlightenment_tribe_my_events_page_layout' );

function enlightenment_tribe_frontend_sales_report_page_layout( $layout ) {
    if ( ! enlightenment_tribe_community_tickets_is_frontend_sales_report() ) {
        return $layout;
    }

    if( current_theme_supports( 'enlightenment-bootstrap' ) ) {
        return array(
            'smartphone-portrait'  => 'full-width',
            'smartphone-landscape' => 'inherit',
            'tablet-portrait'      => 'inherit',
            'tablet-landscape'     => 'inherit',
            'desktop-laptop'       => 'inherit',
        );
    }

    return 'full-width';
}
add_filter( 'enlightenment_current_layout', 'enlightenment_tribe_frontend_sales_report_page_layout' );
