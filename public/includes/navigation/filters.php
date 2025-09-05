<?php

function enlightenment_yoast_social_links_services( $services ) {
    if ( class_exists( 'WPSEO_Option_Social' ) ) {
		$services = array_merge( $services, array(
    		'facebook'   => __( 'Facebook',   'enlightenment' ),
    		'instagram'  => __( 'Instagram',  'enlightenment' ),
    		'linkedin'   => __( 'LinkedIn',   'enlightenment' ),
    		'myspace'    => __( 'MySpace',    'enlightenment' ),
    		'pinterest'  => __( 'Pinterest',  'enlightenment' ),
    		'soundcloud' => __( 'SoundCloud', 'enlightenment' ),
    		'tumblr'     => __( 'Tumblr',     'enlightenment' ),
    		'twitter'    => __( 'Twitter',    'enlightenment' ),
    		'youtube'    => __( 'YouTube',    'enlightenment' ),
    		'wikipedia'  => __( 'Wikipedia',  'enlightenment' ),
    	) );
	}

    return $services;
}
add_filter( 'enlightenment_social_links_services', 'enlightenment_yoast_social_links_services' );

function enlightenment_yoast_social_links_provider( $providers ) {
    if ( class_exists( 'WPSEO_Option_Social' ) ) {
		$providers = array_merge( $providers, array(
    		'facebook'   => 'enlightenment_yoast_social_link',
    		'instagram'  => 'enlightenment_yoast_social_link',
    		'linkedin'   => 'enlightenment_yoast_social_link',
    		'myspace'    => 'enlightenment_yoast_social_link',
    		'pinterest'  => 'enlightenment_yoast_social_link',
    		'soundcloud' => 'enlightenment_yoast_social_link',
    		'tumblr'     => 'enlightenment_yoast_social_link',
    		'twitter'    => 'enlightenment_yoast_social_link',
    		'youtube'    => 'enlightenment_yoast_social_link',
    		'wikipedia'  => 'enlightenment_yoast_social_link',
    	) );
	}

    return $providers;
}
add_filter( 'enlightenment_get_social_links_providers', 'enlightenment_yoast_social_links_provider' );
