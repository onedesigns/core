<?php

function enlightenment_grid_loop_directory() {
	return apply_filters( 'enlightenment_grid_loop_directory', enlightenment_modules_directory() . '/grid-loop' );
}

require_once( enlightenment_grid_loop_directory() . '/functions.php' );
require_once( enlightenment_grid_loop_directory() . '/actions.php' );
require_once( enlightenment_grid_loop_directory() . '/filters.php' );
require_once( enlightenment_grid_loop_directory() . '/customizer.php' );
