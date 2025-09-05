<?php

function enlightenment_bp_setup_theme_compat() {
	if ( 'nouveau' == bp_get_theme_compat_id() ) {
		return;
	}

	bp_setup_theme_compat( 'nouveau' );
}
add_action( 'enlightenment_plugins_loaded', 'enlightenment_bp_setup_theme_compat' );

function enlightenment_bp_remove_theme_package_id_settings_field() {
	global $wp_settings_fields;

	if ( ! isset( $wp_settings_fields['buddypress'] ) ) {
		return;
	}

	if ( ! isset( $wp_settings_fields['buddypress']['bp_main'] ) ) {
		return;
	}

	if ( ! isset( $wp_settings_fields['buddypress']['bp_main']['_bp_theme_package_id'] ) ) {
		return;
	}

	unset( $wp_settings_fields['buddypress']['bp_main']['_bp_theme_package_id'] );
}
add_action( 'bp_register_admin_settings', 'enlightenment_bp_remove_theme_package_id_settings_field' );

function enlightenment_bp_scripts() {
	$deps = apply_filters( 'enlightenment_buddypress_script_deps', array( enlightenment_bp_get_js_handle() ) );

	wp_enqueue_script( 'bp-enlightenment-js', enlightenment_scripts_directory_uri() . '/buddypress.js', $deps, null, true );
}
add_action( 'bp_enqueue_scripts', 'enlightenment_bp_scripts' );

add_action( 'bp_before_member_activity_post_form', 'enlightenment_ob_start' );

function enlightenment_bp_filter_member_activity_post_form( $output ) {
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_filter_member_activity_post_form', $output );

	echo $output;
}
add_action( 'bp_after_member_activity_post_form', 'enlightenment_bp_filter_member_activity_post_form' );

add_action( 'bp_activity_entry_meta', 'enlightenment_ob_start', 0 );

function enlightenment_bp_filter_activity_entry_meta() {
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_filter_activity_entry_meta', $output );

	echo $output;
}
add_action( 'bp_activity_entry_meta', 'enlightenment_bp_filter_activity_entry_meta', 9999 );

add_action( 'bp_before_activity_entry_comments', 'enlightenment_ob_start' );

function enlightenment_bp_filter_activity_entry_comments( $output ) {
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_filter_activity_entry_comments', $output );

	echo $output;
}
add_action( 'bp_after_activity_entry_comments', 'enlightenment_bp_filter_activity_entry_comments' );

function enlightenment_bp_filter_activity_comments_thread_ob_start( $return ) {
	ob_start();

	return $return;
}
add_filter( 'bp_activity_recurse_comments_start_ul', 'enlightenment_bp_filter_activity_comments_thread_ob_start', 999 );

function enlightenment_bp_filter_activity_comments_thread( $return ) {
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_filter_activity_comments_thread', $output, $return );
	echo $output;

	return $return;
}
add_filter( 'bp_activity_recurse_comments_end_ul', 'enlightenment_bp_filter_activity_comments_thread', 1 );

add_action( 'bp_custom_profile_edit_fields_pre_visibility', 'enlightenment_ob_start', 999 );

function enlightenment_bp_filter_xprofile_edit_visibilty() {
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_filter_xprofile_edit_visibilty', $output );

	echo $output;
}
add_action( 'bp_custom_profile_edit_fields', 'enlightenment_bp_filter_xprofile_edit_visibilty', 1 );

add_action( 'bp_before_member_messages_content', 'enlightenment_ob_start' );

function enlightenment_bp_filter_member_messages_content() {
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_filter_member_messages_content', $output );

	echo $output;
}
add_action( 'bp_after_member_messages_content', 'enlightenment_bp_filter_member_messages_content' );

add_action( 'wp_footer', 'enlightenment_ob_start', 8 );

function enlightenment_bp_filter_wp_footer_content() {
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_filter_wp_footer_content', $output );

	echo $output;
}
add_action( 'wp_footer', 'enlightenment_bp_filter_wp_footer_content', 12 );

add_action( 'bp_before_login_widget_loggedin', 'enlightenment_ob_start' );

function enlightenment_bp_filter_login_widget_loggedin() {
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_filter_login_widget_loggedin', $output );

	echo $output;
}
add_action( 'bp_after_login_widget_loggedin', 'enlightenment_bp_filter_login_widget_loggedin' );

add_action( 'bp_before_login_widget_loggedout', 'enlightenment_ob_start' );

function enlightenment_bp_filter_login_widget_loggedout() {
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bp_filter_login_widget_loggedout', $output );

	echo $output;
}
add_action( 'bp_after_login_widget_loggedout', 'enlightenment_bp_filter_login_widget_loggedout' );

if ( has_action( 'wp_footer', 'rtm_privacy_message_on_website' ) ) {
	remove_action( 'wp_footer', 'rtm_privacy_message_on_website' );
	add_action( 'wp_footer', 'enlightenment_rtm_privacy_message_on_website' );
}

function enlightenment_bp_rtmedia_remove_replace_the_content_filter( $template ) {
	global $wp_filter;

	if ( ! isset( $wp_filter['the_content'] ) ) {
		return $template;
	}

	if ( ! $wp_filter['the_content']->callbacks[10] ) {
		return $template;
	}

	foreach ( $wp_filter['the_content']->callbacks[10] as $callback ) {
		if ( ! is_array( $callback['function'] ) ) {
			continue;
		}

		if (
			$callback['function'][0] instanceof RTMediaRouter
			&&
			'rt_replace_the_content' == $callback['function'][1]
		) {
			remove_filter( 'the_content', $callback['function'] );
			break;
		}
	}

	return $template;
}
add_filter( 'rtmedia_main_template_include', 'enlightenment_bp_rtmedia_remove_replace_the_content_filter' );
