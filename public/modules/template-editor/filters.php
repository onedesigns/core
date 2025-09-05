<?php

function enlightenment_yoast_template_functions( $functions ) {
    if ( class_exists( 'WPSEO_Option_Social' ) ) {
    	$functions['enlightenment_social_nav_menu'] = __( 'Social Links', 'enlightenment' );
    }

	return $functions;
}
add_filter( 'enlightenment_template_functions', 'enlightenment_yoast_template_functions' );

function enlightenment_yoast_site_header_footer_hooks( $hooks ) {
    if ( class_exists( 'WPSEO_Option_Social' ) ) {
        if ( isset( $hooks['enlightenment_site_header'] ) ) {
        	$hooks['enlightenment_site_header']['functions'][] = 'enlightenment_social_nav_menu';
        }

        if ( isset( $hooks['enlightenment_site_footer'] ) ) {
        	$hooks['enlightenment_site_footer']['functions'][] = 'enlightenment_social_nav_menu';
        }
    }

	return $hooks;
}
add_filter( 'enlightenment_site_header_hooks', 'enlightenment_yoast_site_header_footer_hooks' );
add_filter( 'enlightenment_site_footer_hooks', 'enlightenment_yoast_site_header_footer_hooks' );
