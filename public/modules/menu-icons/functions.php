<?php

function enlightenment_font_awesome() {
	$icons = array(
		's fa-home'               => __( 'Home',           'enlightenment' ),
		's fa-building'           => __( 'Building',       'enlightenment' ),
		'r fa-building'           => __( 'Building',       'enlightenment' ),
		's fa-briefcase'          => __( 'Briefcase',      'enlightenment' ),
		's fa-pencil-alt'         => __( 'Pencil',         'enlightenment' ),
		's fa-paperclip'          => __( 'Paper Clip',     'enlightenment' ),
		's fa-edit'               => __( 'Edit',           'enlightenment' ),
		'r fa-edit'               => __( 'Edit',           'enlightenment' ),
		's fa-image'              => __( 'Image',          'enlightenment' ),
		'r fa-image'              => __( 'Image',          'enlightenment' ),
		's fa-camera'             => __( 'Camera',         'enlightenment' ),
		's fa-music'              => __( 'Audio',          'enlightenment' ),
		's fa-headphones'         => __( 'Headphones',     'enlightenment' ),
		's fa-film'               => __( 'Video',          'enlightenment' ),
		's fa-cloud'              => __( 'Cloud',          'enlightenment' ),
		's fa-comment'            => __( 'Comment',        'enlightenment' ),
		'r fa-comment'            => __( 'Comment',        'enlightenment' ),
		's fa-clock'              => __( 'Clock',          'enlightenment' ),
		'r fa-clock'              => __( 'Clock',          'enlightenment' ),
		's fa-calendar-alt'       => __( 'Calendar',       'enlightenment' ),
		'r fa-calendar-alt'       => __( 'Calendar',       'enlightenment' ),
		's fa-map-marker-alt'     => __( 'Map Marker',     'enlightenment' ),
		's fa-graduation-cap'     => __( 'Graduation Cap', 'enlightenment' ),
		's fa-phone'              => __( 'Phone',          'enlightenment' ),
		's fa-fax'                => __( 'Fax',            'enlightenment' ),
		's fa-envelope'           => __( 'Envelope',       'enlightenment' ),
		'r fa-envelope'           => __( 'Envelope',       'enlightenment' ),
		'b fa-whatsapp'           => __( 'WhatsApp',       'enlightenment' ),
		'b fa-facebook-messenger' => __( 'Messnger',       'enlightenment' ),
	);

	return apply_filters( 'enlightenment_font_awesome', $icons );
}

function enlightenment_menu_icons() {
	$icons = array(
		'' => __( 'None', 'enlightenment' ),
	);

	switch ( current_theme_supports('enlightenment-menu-icons', 'iconset' ) ) {
		case 'font-awesome':
		default:
			$icons = array_merge( $icons, enlightenment_font_awesome() );
	}

	return apply_filters( 'enlightenment_menu_icons', $icons );
}

function enlightenment_menu_icon_prefix() {
	switch ( current_theme_supports('enlightenment-menu-icons', 'iconset' ) ) {
		case 'font-awesome':
		default:
			$prefix = 'fa';
	}

	return $prefix;
}
