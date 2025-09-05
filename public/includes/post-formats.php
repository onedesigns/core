<?php

function enlightenment_post_formats_directory() {
	return apply_filters( 'enlightenment_post_formats_directory', enlightenment_includes_directory() . '/post-formats' );
}

require_once( enlightenment_post_formats_directory() . '/template-tags.php' );
require_once( enlightenment_post_formats_directory() . '/extras.php' );
require_once( enlightenment_post_formats_directory() . '/default-hooks.php' );
