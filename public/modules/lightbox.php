<?php

function enlightenment_lightbox_directory() {
	return apply_filters( 'enlightenment_lightbox_directory', enlightenment_modules_directory() . '/lightbox' );
}

require_once( enlightenment_lightbox_directory() . '/actions.php' );
require_once( enlightenment_lightbox_directory() . '/filters.php' );
