<?php

function enlightenment_accessibility_directory() {
	return apply_filters( 'enlightenment_accessibility_directory', enlightenment_modules_directory() . '/accessibility' );
}

require_once( enlightenment_accessibility_directory() . '/functions.php' );
require_once( enlightenment_accessibility_directory() . '/actions.php' );
require_once( enlightenment_accessibility_directory() . '/filters.php' );
