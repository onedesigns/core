<?php

function enlightenment_web_fonts_directory() {
	return apply_filters( 'enlightenment_web_fonts_directory', enlightenment_modules_directory() . '/web-fonts' );
}

require_once( enlightenment_web_fonts_directory() . '/functions.php' );
require_once( enlightenment_web_fonts_directory() . '/actions.php' );
require_once( enlightenment_web_fonts_directory() . '/filters.php' );
require_once( enlightenment_web_fonts_directory() . '/customizer.php' );
