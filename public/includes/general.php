<?php

function enlightenment_general_directory() {
	return apply_filters( 'enlightenment_general_directory', enlightenment_includes_directory() . '/general' );
}

require_once( enlightenment_general_directory() . '/functions.php' );
require_once( enlightenment_general_directory() . '/setup.php' );
require_once( enlightenment_general_directory() . '/template-tags.php' );
require_once( enlightenment_general_directory() . '/extras.php' );

if ( is_admin() ) {
	require_once( enlightenment_general_directory() . '/form-controls.php' );
}
