<?php

function enlightenment_navigation_directory() {
	return apply_filters( 'enlightenment_navigation_directory', enlightenment_includes_directory() . '/navigation' );
}

require_once( enlightenment_navigation_directory() . '/template-tags.php' );
require_once( enlightenment_navigation_directory() . '/functions.php' );
require_once( enlightenment_navigation_directory() . '/filters.php' );
require_once( enlightenment_navigation_directory() . '/walker.php' );
require_once( enlightenment_navigation_directory() . '/extras.php' );
