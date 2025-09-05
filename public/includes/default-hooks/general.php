<?php

add_action( 'enlightenment_head', 'enlightenment_meta_charset',  1 );
add_action( 'enlightenment_head', 'enlightenment_meta_viewport', 1 );
add_action( 'enlightenment_head', 'enlightenment_profile_link',  1 );
add_action( 'enlightenment_head', 'enlightenment_pingback_link', 1 );

add_action( 'enlightenment_site_header', 'enlightenment_site_branding' );
add_action( 'enlightenment_site_header', 'enlightenment_nav_menu' );

add_action( 'enlightenment_site_footer', 'enlightenment_copyright_notice' );
add_action( 'enlightenment_site_footer', 'enlightenment_credit_links' );
