<?php

function enlightenment_menu_icons_directory() {
	return apply_filters( 'enlightenment_menu_icons_directory', enlightenment_modules_directory() . '/menu-icons' );
}

require_once( enlightenment_menu_icons_directory() . '/admin.php' );
require_once( enlightenment_menu_icons_directory() . '/actions.php' );
require_once( enlightenment_menu_icons_directory() . '/functions.php' );
require_once( enlightenment_menu_icons_directory() . '/filters.php' );
require_once( enlightenment_menu_icons_directory() . '/customizer.php' );
