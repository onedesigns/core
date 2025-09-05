<?php

function enlightenment_template_editor_directory() {
	return apply_filters( 'enlightenment_template_editor_directory', enlightenment_modules_directory() . '/template-editor' );
}

require_once( enlightenment_template_editor_directory() . '/functions.php' );
require_once( enlightenment_template_editor_directory() . '/actions.php' );
require_once( enlightenment_template_editor_directory() . '/filters.php' );
require_once( enlightenment_template_editor_directory() . '/customizer.php' );

function enlightenment_template_editor_theme_supported_functions() {
	require_if_theme_supports( 'enlightenment-grid-loop', enlightenment_template_editor_directory() . '/grid-loop.php' );
	require_if_theme_supports( 'post-formats', enlightenment_template_editor_directory() . '/post-formats.php' );
}
add_action( 'after_setup_theme', 'enlightenment_template_editor_theme_supported_functions', 40 );
