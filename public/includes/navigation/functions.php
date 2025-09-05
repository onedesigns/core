<?php

function enlightenment_get_social_links() {
    $links     = array();
    $services  = enlightenment_get_social_links_services();
    $providers = enlightenment_get_social_links_providers();

    foreach ( $services as $service => $label ) {
        if ( isset( $providers[ $service ] ) && is_callable( $providers[ $service ] ) ) {
            $link = call_user_func( $providers[ $service ], $service );

            if ( ! empty( $link ) ) {
                $links[ $service ] = $link;
            }
        }
    }

    return apply_filters( 'enlightenment_social_links', $links );
}

function enlightenment_get_social_links_icons() {
    return apply_filters( 'enlightenment_social_links_icons', array(
		'facebook'   => 'b fa-facebook-f',
		'instagram'  => 'b fa-instagram',
		'linkedin'   => 'b fa-linkedin-in',
		'pinterest'  => 'b fa-pinterest-p',
		'soundcloud' => 'b fa-soundcloud',
		'tumblr'     => 'b fa-tumblr',
		'twitter'    => 'b fa-twitter',
		'youtube'    => 'b fa-youtube',
		'wikipedia'  => 'b fa-wikipedia-w',
	) );
}

function enlightenment_get_social_links_services() {
	return apply_filters( 'enlightenment_social_links_services', array() );
}

function enlightenment_get_social_links_providers() {
	return apply_filters( 'enlightenment_get_social_links_providers', array() );
}

function enlightenment_yoast_social_link( $service ) {
    $links = get_option( 'wpseo_social' );

    if ( empty( $links ) ) {
        return '';
    }

    switch ( $service ) {
        case 'facebook':
        case 'twitter':
            $service = sprintf( '%s_site', $service );
            break;

        default:
            $service = sprintf( '%s_url', $service );
            break;
    }

    if ( empty( $links[ $service ] ) ) {
        return '';
    }

    if ( 'twitter_site' == $service ) {
        return sprintf( 'https://twitter.com/%s', $links[ $service ] );
    }

    return $links[ $service ];
}
