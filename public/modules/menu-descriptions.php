<?php

function enlightenment_menu_descriptions_directory() {
	return apply_filters( 'enlightenment_menu_descriptions_directory', enlightenment_modules_directory() . '/menu-descriptions' );
}

require_once( enlightenment_menu_descriptions_directory() . '/admin.php' );
require_once( enlightenment_menu_descriptions_directory() . '/filters.php' );
