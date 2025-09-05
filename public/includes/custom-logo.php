<?php

function enlightenment_custom_logo_directory() {
	return apply_filters( 'enlightenment_custom_logo_directory', enlightenment_includes_directory() . '/custom-logo' );
}

require_once( enlightenment_custom_logo_directory() . '/template-tags.php' );
require_once( enlightenment_custom_logo_directory() . '/extras.php' );
