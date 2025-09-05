<?php

function enlightenment_custom_header_directory() {
	return apply_filters( 'enlightenment_custom_header_directory', enlightenment_includes_directory() . '/custom-header' );
}

require_once( enlightenment_custom_header_directory() . '/template-tags.php' );
require_once( enlightenment_custom_header_directory() . '/extras.php' );
