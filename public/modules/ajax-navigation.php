<?php

function enlightenment_ajax_navigation_directory() {
	return apply_filters( 'enlightenment_ajax_navigation_directory', enlightenment_modules_directory() . '/ajax-navigation' );
}

require_once( enlightenment_ajax_navigation_directory() . '/actions.php' );
require_once( enlightenment_ajax_navigation_directory() . '/filters.php' );
