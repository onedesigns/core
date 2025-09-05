<?php

function enlightenment_content_directory() {
	return apply_filters( 'enlightenment_content_directory', enlightenment_includes_directory() . '/content' );
}

require_once( enlightenment_content_directory() . '/template-tags.php' );
require_once( enlightenment_content_directory() . '/functions.php' );
require_once( enlightenment_content_directory() . '/gutenberg.php' );
require_once( enlightenment_content_directory() . '/extras.php' );
