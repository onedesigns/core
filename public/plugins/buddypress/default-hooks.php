<?php

function enlightenment_bp_layout() {
	if ( ! is_buddypress() ) {
		return;
	}

	add_action( 'enlightenment_after_site_header', 'enlightenment_open_buddypress_container', 999 );
	add_action( 'enlightenment_before_site_footer', 'enlightenment_close_div', 1 ); //buddypress_container
}
add_action( 'wp', 'enlightenment_bp_layout', 8 );

function enlightenment_bp_setup_objects() {
	if ( ! is_buddypress() ) {
		return;
	}

	if ( bp_is_group() ) {
		add_action( 'enlightenment_before_site_content', 'enlightenment_bp_setup_group_object', 999 );
	}
}
add_action( 'wp', 'enlightenment_bp_setup_objects' );

function enlightenment_bp_template_hooks() {
	if ( ! is_buddypress() ) {
		return;
	}

	remove_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
	remove_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
	remove_action( 'enlightenment_page_header', 'enlightenment_nav_menu' );
	remove_action( 'enlightenment_page_content', 'enlightenment_the_loop' );

	if ( enlightenment_bp_is_access_restricted() ) {
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );

		add_action( 'enlightenment_page_content', 'enlightenment_bp_restricted_access_message' );

		return;
	}

	if ( bp_is_activity_directory() ) {
		add_action( 'enlightenment_page_header', 'enlightenment_bp_template_notices' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_members_stats' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_activity_nav' );

		add_action( 'enlightenment_before_page_content', 'bp_nouveau_before_activity_directory_content', 8 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_activity_post_form' );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_directory_nav' );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_search_and_filters_bar' );

		add_action( 'enlightenment_page_content', 'enlightenment_bp_activity_loop' );

		add_action( 'enlightenment_after_page_content', 'bp_nouveau_after_activity_directory_content', 12 );
		add_action( 'enlightenment_after_page_content', 'bp_nouveau_after_directory_page', 14 );
	} elseif ( bp_is_user() ) {
		remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );

		add_action( 'enlightenment_before_page_header', 'enlightenment_bp_before_member_header', 999 );

		add_action( 'enlightenment_page_header', 'enlightenment_bp_page_header_open_container', 1 );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_cover_image_maybe_open_container', 3 );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_cover_image', 5 );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_header_cover_image_maybe_open_container', 6 );

		add_action( 'enlightenment_page_header', 'enlightenment_bp_template_notices' );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_user_avatar' );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_user_header_content' );

		add_action( 'enlightenment_page_header', 'enlightenment_bp_header_cover_image_maybe_close_container', 995 );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_cover_image_maybe_close_container', 997 );
		add_action( 'enlightenment_page_header', 'enlightenment_close_div', 999 );

		add_action( 'enlightenment_after_page_header', 'enlightenment_bp_after_member_header', 1 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_before_member_home_content', 6 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_wrap_open_container', 8 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_displayed_user_nav' );

		add_action( 'enlightenment_page_content', 'enlightenment_bp_displayed_user_body' );

		add_action( 'enlightenment_after_page_content', 'enlightenment_close_div', 12 );
		add_action( 'enlightenment_after_page_content', 'enlightenment_bp_after_member_home_content', 14 );
	} elseif ( bp_is_members_directory() ) {
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );

		add_action( 'enlightenment_before_page_content', 'bp_nouveau_before_members_directory_content', 8 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_directory_nav' );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_search_and_filters_bar' );

		add_action( 'enlightenment_page_content', 'enlightenment_bp_members_loop' );

		add_action( 'enlightenment_after_page_content', 'bp_nouveau_after_members_directory_content', 12 );
		add_action( 'enlightenment_after_page_content', 'bp_nouveau_after_directory_page', 14 );
	} elseif ( bp_is_group() ) {
		remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );

		add_action( 'enlightenment_before_page_header', 'enlightenment_bp_setup_single_group_object', 1 );

		add_action( 'enlightenment_before_page_header', 'enlightenment_bp_before_group_header', 999 );

		add_action( 'enlightenment_page_header', 'enlightenment_bp_page_header_open_container', 1 );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_cover_image_maybe_open_container', 3 );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_cover_image', 5 );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_header_cover_image_maybe_open_container', 6 );

		add_action( 'enlightenment_page_header', 'enlightenment_bp_template_notices' );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_group_avatar' );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_group_header_content' );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_group_header_actions' );

		add_action( 'enlightenment_page_header', 'enlightenment_bp_header_cover_image_maybe_close_container', 995 );
		add_action( 'enlightenment_page_header', 'enlightenment_bp_cover_image_maybe_close_container', 997 );
		add_action( 'enlightenment_page_header', 'enlightenment_close_div', 999 );

		add_action( 'enlightenment_after_page_header', 'enlightenment_bp_after_group_header', 1 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_before_group_home_content', 6 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_wrap_open_container', 8 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_group_description' );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_group_nav' );

		add_action( 'enlightenment_page_content', 'enlightenment_bp_group_body' );

		add_action( 'enlightenment_after_page_content', 'enlightenment_close_div', 12 );
		add_action( 'enlightenment_after_page_content', 'enlightenment_bp_after_group_home_content', 14 );
	} elseif ( bp_is_groups_directory() ) {
		add_action( 'enlightenment_page_header', 'enlightenment_bp_template_notices' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_groups_stats' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_groups_nav' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_groups_directory_group_types' );

		add_action( 'enlightenment_before_page_content', 'bp_nouveau_before_groups_directory_content', 8 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_directory_nav' );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_search_and_filters_bar' );

		add_action( 'enlightenment_page_content', 'enlightenment_bp_groups_loop' );

		add_action( 'enlightenment_after_page_content', 'bp_nouveau_after_groups_directory_content', 12 );
		add_action( 'enlightenment_after_page_content', 'bp_nouveau_after_directory_page', 14 );
	} elseif ( bp_is_group_create() ) {
		remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );

		add_action( 'enlightenment_before_page_header', 'enlightenment_bp_before_groups_page', 8 );

		add_action( 'enlightenment_page_header', 'enlightenment_bp_template_notices' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_groups_stats' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_groups_nav' );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_before_groups_content_template', 8 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_group_creation_tabs' );

		add_action( 'enlightenment_page_content', 'enlightenment_bp_group_creation_screen' );

		add_action( 'enlightenment_after_page_content', 'enlightenment_bp_after_groups_content_template', 12 );
		add_action( 'enlightenment_after_page_content', 'enlightenment_bp_after_groups_page', 14 );
	} elseif ( bp_is_blogs_directory() ) {
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_template_notices' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );

		add_action( 'enlightenment_before_page_content', 'bp_nouveau_before_blogs_directory_content', 8 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_directory_nav' );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_search_and_filters_bar' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_blogs_stats' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_blog_types' );

		add_action( 'enlightenment_page_content', 'enlightenment_bp_blogs_loop' );

		add_action( 'enlightenment_after_page_content', 'bp_nouveau_after_blogs_directory_content', 12 );
		add_action( 'enlightenment_after_page_content', 'bp_nouveau_after_directory_page', 14 );
	} elseif ( bp_is_create_blog() ) {
		add_action( 'enlightenment_page_header', 'enlightenment_bp_template_notices' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_create_blog_name' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bp_blogs_stats' );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_before_blogs_content_template', 6 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bp_before_blogs_content', 8 );

		add_action( 'enlightenment_page_content', 'enlightenment_bp_blog_create_form' );

		add_action( 'enlightenment_after_page_content', 'enlightenment_bp_after_blogs_content', 12 );
		add_action( 'enlightenment_after_page_content', 'enlightenment_bp_after_blogs_content_template', 14 );
	} elseif ( bp_is_register_page() ) {
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );

		add_action( 'enlightenment_page_content', 'enlightenment_bp_register_form' );
	} elseif ( bp_is_activation_page() ) {
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );

		add_action( 'enlightenment_page_content', 'enlightenment_bp_activate_page' );
	}
}
add_action( 'wp', 'enlightenment_bp_template_hooks' );

function enlightenment_bp_template_hooks_overrides() {
	if ( ! is_buddypress() ) {
		return;
	}

	if ( enlightenment_bp_is_access_restricted() ) {
		return;
	}

	if ( bp_is_group() ) {
		$group = groups_get_current_group();

		if ( 'hidden' === bp_get_group_status( $group ) && ! groups_is_user_member( get_current_user_id(), $group->id ) ) {
			remove_all_actions( 'enlightenment_before_page_header' );
			remove_all_actions( 'enlightenment_page_header' );
			remove_all_actions( 'enlightenment_after_page_header' );
			remove_all_actions( 'enlightenment_before_page_content' );
			remove_all_actions( 'enlightenment_page_content' );
			remove_all_actions( 'enlightenment_after_page_content' );

			add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		}
	}
}
add_action( 'wp', 'enlightenment_bp_template_hooks_overrides', 40 );

add_action( 'enlightenment_bp_user_header_content', 'enlightenment_bp_user_name' );
add_action( 'enlightenment_bp_user_header_content', 'enlightenment_bp_user_mentionname' );
add_action( 'enlightenment_bp_user_header_content', 'enlightenment_bp_member_header_actions' );
add_action( 'enlightenment_bp_user_header_content', 'enlightenment_bp_member_meta' );
add_action( 'enlightenment_bp_user_header_content', 'enlightenment_bp_follow_stats' );

add_action( 'enlightenment_bp_group_header_content', 'enlightenment_bp_group_name' );
add_action( 'enlightenment_bp_group_header_content', 'enlightenment_bp_group_status' );
add_action( 'enlightenment_bp_group_header_content', 'enlightenment_bp_group_last_active' );
add_action( 'enlightenment_bp_group_header_content', 'enlightenment_bp_group_type_list' );
add_action( 'enlightenment_bp_group_header_content', 'enlightenment_bp_group_meta' );
add_action( 'enlightenment_bp_group_header_content', 'enlightenment_bp_group_header_buttons' );
