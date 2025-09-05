<?php

do_action( 'enlightenment_before_framework_init' );

function enlightenment_framework_directory() {
	return apply_filters( 'enlightenment_framework_directory', dirname( __FILE__ ) );
}

function enlightenment_framework_directory_uri() {
	return apply_filters( 'enlightenment_framework_directory_uri', get_template_directory_uri() . '/core' );
}

function enlightenment_includes_directory() {
	return apply_filters( 'enlightenment_includes_directory', enlightenment_framework_directory() . '/includes' );
}

function enlightenment_modules_directory() {
	return apply_filters( 'enlightenment_modules_directory', enlightenment_framework_directory() . '/modules' );
}

function enlightenment_plugins_directory() {
	return apply_filters( 'enlightenment_plugins_directory', enlightenment_framework_directory() . '/plugins' );
}

function enlightenment_assets_directory() {
	return apply_filters( 'enlightenment_assets_directory', enlightenment_framework_directory() . '/assets' );
}

function enlightenment_assets_directory_uri() {
	return apply_filters( 'enlightenment_assets_directory_uri', enlightenment_framework_directory_uri() . '/assets' );
}

function enlightenment_languages_directory() {
	return apply_filters( 'enlightenment_languages_directory', enlightenment_assets_directory() . '/languages' );
}

function enlightenment_styles_directory_uri() {
	return apply_filters( 'enlightenment_styles_directory_uri', enlightenment_assets_directory_uri() . '/css' );
}

function enlightenment_scripts_directory_uri() {
	return apply_filters( 'enlightenment_scripts_directory_uri', enlightenment_assets_directory_uri() . '/js' );
}

function enlightenment_images_directory_uri() {
	return apply_filters( 'enlightenment_images_directory_uri', enlightenment_assets_directory_uri() . '/images' );
}

function enlightenment_fonts_directory_uri() {
	return apply_filters( 'enlightenment_fonts_directory_uri', enlightenment_assets_directory_uri() . '/fonts' );
}

function enlightenment_head() {
	do_action( 'enlightenment_head' );
}

function enlightenment_before_site() {
	do_action( 'enlightenment_before_site' );
}

function enlightenment_before_site_header() {
	do_action( 'enlightenment_before_site_header' );
}

function enlightenment_site_header() {
	do_action( 'enlightenment_site_header' );
}

function enlightenment_after_site_header() {
	do_action( 'enlightenment_after_site_header' );
}

function enlightenment_before_site_content() {
	do_action( 'enlightenment_before_site_content' );
}

function enlightenment_before_page_content() {
	do_action( 'enlightenment_before_page_content' );
}

function enlightenment_before_page_header() {
	do_action( 'enlightenment_before_page_header' );
}

function enlightenment_page_header() {
	do_action( 'enlightenment_page_header' );
}

function enlightenment_after_page_header() {
	do_action( 'enlightenment_after_page_header' );
}

function enlightenment_page_content() {
	do_action( 'enlightenment_page_content' );
}

function enlightenment_before_entry() {
	do_action( 'enlightenment_before_entry' );
}

function enlightenment_before_entry_header() {
	do_action( 'enlightenment_before_entry_header' );
}

function enlightenment_entry_header() {
	do_action( 'enlightenment_entry_header' );
}

function enlightenment_after_entry_header() {
	do_action( 'enlightenment_after_entry_header' );
}

function enlightenment_before_entry_content() {
	do_action( 'enlightenment_before_entry_content' );
}

function enlightenment_entry_content() {
	do_action( 'enlightenment_entry_content' );
}

function enlightenment_after_entry_content() {
	do_action( 'enlightenment_after_entry_content' );
}

function enlightenment_before_entry_footer() {
	do_action( 'enlightenment_before_entry_footer' );
}

function enlightenment_entry_footer() {
	do_action( 'enlightenment_entry_footer' );
}

function enlightenment_after_entry_footer() {
	do_action( 'enlightenment_after_entry_footer' );
}

function enlightenment_after_entry() {
	do_action( 'enlightenment_after_entry' );
}

function enlightenment_after_page_content() {
	do_action( 'enlightenment_after_page_content' );
}

function enlightenment_after_site_content() {
	do_action( 'enlightenment_after_site_content' );
}

function enlightenment_comments_require_password() {
	do_action( 'enlightenment_comments_require_password' );
}

function enlightenment_before_comments() {
	do_action( 'enlightenment_before_comments' );
}

function enlightenment_comments() {
	do_action( 'enlightenment_comments' );
}

function enlightenment_after_comments() {
	do_action( 'enlightenment_after_comments' );
}

function enlightenment_no_comments() {
	do_action( 'enlightenment_no_comments' );
}

function enlightenment_before_sidebar() {
	do_action( 'enlightenment_before_sidebar' );
}

function enlightenment_after_sidebar() {
	do_action( 'enlightenment_after_sidebar' );
}

function enlightenment_before_widgets() {
	do_action( 'enlightenment_before_widgets' );
}

function enlightenment_after_widgets() {
	do_action( 'enlightenment_after_widgets' );
}

function enlightenment_before_site_footer() {
	do_action( 'enlightenment_before_site_footer' );
}

function enlightenment_site_footer() {
	do_action( 'enlightenment_site_footer' );
}

function enlightenment_after_site_footer() {
	do_action( 'enlightenment_after_site_footer' );
}

function enlightenment_after_site() {
	do_action( 'enlightenment_after_site' );
}

do_action( 'enlightenment_before_framework_functions' );

require_once( enlightenment_includes_directory() . '/customizer.php' );
require_once( enlightenment_includes_directory() . '/general.php' );
require_once( enlightenment_includes_directory() . '/navigation.php' );
require_once( enlightenment_includes_directory() . '/content.php' );
require_once( enlightenment_includes_directory() . '/comments.php' );
require_once( enlightenment_includes_directory() . '/default-hooks.php' );

do_action( 'enlightenment_functions_loaded' );

function enlightenment_theme_supported_features() {
	do_action( 'enlightenment_before_theme_features' );

	require_if_theme_supports( 'custom-header',   enlightenment_includes_directory() . '/custom-header.php' );
	require_if_theme_supports( 'custom-logo',     enlightenment_includes_directory() . '/custom-logo.php' );
	require_if_theme_supports( 'post-thumbnails', enlightenment_includes_directory() . '/post-thumbnails.php' );
	require_if_theme_supports( 'post-formats',    enlightenment_includes_directory() . '/post-formats.php' );

	do_action( 'enlightenment_theme_features_loaded' );
}
add_action( 'after_setup_theme', 'enlightenment_theme_supported_features', 30 );

function enlightenment_theme_supported_modules() {
	do_action( 'enlightenment_before_modules' );

	require_if_theme_supports( 'enlightenment-web-fonts',          enlightenment_modules_directory() . '/web-fonts.php' );
	require_if_theme_supports( 'enlightenment-accessibility',      enlightenment_modules_directory() . '/accessibility.php' );
	require_if_theme_supports( 'enlightenment-bootstrap',          enlightenment_modules_directory() . '/bootstrap.php' );
	require_if_theme_supports( 'enlightenment-schema-markup',      enlightenment_modules_directory() . '/schema-markup.php' );
	require_if_theme_supports( 'enlightenment-menu-icons',         enlightenment_modules_directory() . '/menu-icons.php' );
	require_if_theme_supports( 'enlightenment-menu-descriptions',  enlightenment_modules_directory() . '/menu-descriptions.php' );
	require_if_theme_supports( 'enlightenment-lightbox',           enlightenment_modules_directory() . '/lightbox.php' );
	require_if_theme_supports( 'enlightenment-ajax-navigation',    enlightenment_modules_directory() . '/ajax-navigation.php' );
	require_if_theme_supports( 'enlightenment-infinite-scroll',    enlightenment_modules_directory() . '/infinite-scroll.php' );
	require_if_theme_supports( 'enlightenment-custom-layouts',     enlightenment_modules_directory() . '/custom-layouts.php' );
	require_if_theme_supports( 'enlightenment-grid-loop',          enlightenment_modules_directory() . '/grid-loop.php' );
	require_if_theme_supports( 'enlightenment-template-editor',    enlightenment_modules_directory() . '/template-editor.php' );
	require_if_theme_supports( 'enlightenment-unlimited-sidebars', enlightenment_modules_directory() . '/unlimited-sidebars.php' );
	require_if_theme_supports( 'enlightenment-custom-queries',     enlightenment_modules_directory() . '/custom-queries.php' );

	do_action( 'enlightenment_modules_loaded' );
}
add_action( 'after_setup_theme', 'enlightenment_theme_supported_modules', 30 );

function enlightenment_theme_supported_plugins() {
	do_action( 'enlightenment_before_plugins' );

	require_if_theme_supports( 'enlightenment-bbpress',         enlightenment_plugins_directory() . '/bbpress.php' );
	require_if_theme_supports( 'enlightenment-buddypress',      enlightenment_plugins_directory() . '/buddypress.php' );
	require_if_theme_supports( 'enlightenment-events-calendar', enlightenment_plugins_directory() . '/events-calendar.php' );
	require_if_theme_supports( 'enlightenment-jetpack',         enlightenment_plugins_directory() . '/jetpack.php' );
	require_if_theme_supports( 'enlightenment-woocommerce',     enlightenment_plugins_directory() . '/woocommerce.php' );

	do_action( 'enlightenment_plugins_loaded' );
}
add_action( 'after_setup_theme', 'enlightenment_theme_supported_plugins', 30 );

do_action( 'enlightenment_framework_loaded' );
