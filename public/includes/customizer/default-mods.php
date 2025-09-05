<?php

/**
 * Default customizer options.
 *
 * @return array
 */
function enlightenment_default_theme_mods() {
	return apply_filters( 'enlightenment_default_theme_mods', array() );
}

/**
 * Filter theme mods with default value when they are not set in database.
 */
function enlightenment_set_default_theme_mods( $mod ) {
	$mods     = get_theme_mods();
	$defaults = enlightenment_default_theme_mods();
	$name     = str_replace( 'theme_mod_', '', current_filter() );

	// No value in database, retrieve from default array
	if ( ! isset( $mods[ $name ] ) ) {
		$mod = $defaults[ $name ];
	}

	return $mod;
}

/**
 * Set default mods filter for each theme mod.
 */
function enlightenment_set_default_theme_mods_filter() {
	$mods = enlightenment_default_theme_mods();

	foreach ( $mods as $mod => $value ) {
		add_filter( "theme_mod_{$mod}", 'enlightenment_set_default_theme_mods', 1 );
	}
}
// Generally available mods
add_action( 'after_setup_theme', 'enlightenment_set_default_theme_mods_filter',  0 );
// Theme supported features
add_action( 'after_setup_theme', 'enlightenment_set_default_theme_mods_filter', 60 );
