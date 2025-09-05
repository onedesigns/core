<?php

function enlightenment_comments_directory() {
	return apply_filters( 'enlightenment_comments_directory', enlightenment_includes_directory() . '/comments' );
}

require_once( enlightenment_comments_directory() . '/template-tags.php' );
require_once( enlightenment_comments_directory() . '/functions.php' );
require_once( enlightenment_comments_directory() . '/walker.php' );
require_once( enlightenment_comments_directory() . '/extras.php' );
