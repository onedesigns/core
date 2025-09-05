<?php

function enlightenment_default_hooks_directory() {
	return apply_filters( 'enlightenment_default_hooks_directory', enlightenment_includes_directory() . '/default-hooks' );
}

require_once( enlightenment_default_hooks_directory() . '/general.php' );
require_once( enlightenment_default_hooks_directory() . '/content.php' );
require_once( enlightenment_default_hooks_directory() . '/comments.php' );
