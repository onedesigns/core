<?php

function enlightenment_custom_queries_directory() {
	return apply_filters( 'enlightenment_custom_queries_directory', enlightenment_modules_directory() . '/custom-queries' );
}

require_once( enlightenment_custom_queries_directory() . '/functions.php' );
require_once( enlightenment_custom_queries_directory() . '/actions.php' );
require_once( enlightenment_custom_queries_directory() . '/filters.php' );
require_once( enlightenment_custom_queries_directory() . '/widgets.php' );
