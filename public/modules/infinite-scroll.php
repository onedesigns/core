<?php

function enlightenment_infinite_scroll_directory() {
	return apply_filters( 'enlightenment_infinite_scroll_directory', enlightenment_modules_directory() . '/infinite-scroll' );
}

require_once( enlightenment_infinite_scroll_directory() . '/actions.php' );
require_once( enlightenment_infinite_scroll_directory() . '/filters.php' );
