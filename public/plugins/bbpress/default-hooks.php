<?php

add_action( 'enlightenment_before_entry', 'enlightenment_bbp_the_forum', 1 );

function enlightenment_bbp_layout_hooks() {
	if ( ! is_bbpress() && ! bbp_is_forum_edit() ) {
		return;
	}

	if ( function_exists( 'is_buddypress' ) && is_buddypress() ) {
		return;
	}

	add_action( 'enlightenment_before_site_content', 'enlightenment_bbp_before_main_content', 8 );

	add_action( 'enlightenment_after_site_content', 'enlightenment_bbp_after_main_content', 12 );

	if ( enlightenment_bbp_is_forum_archive_forums() ) {
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
		remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );
		remove_action( 'enlightenment_page_content', 'enlightenment_the_loop' );

		add_action( 'enlightenment_page_header', 'enlightenment_bbp_template_notices' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
		add_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bbp_global_stats' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bbp_nav' );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_forum_front_open_container', 6 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_forums_wrapper_open_container', 8 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_form_search' );
		add_action( 'enlightenment_before_page_content', 'bbp_forum_subscription_link' );

		add_action( 'enlightenment_page_content', 'enlightenment_bbp_template_before_forums_index', 8 );
		add_action( 'enlightenment_page_content', 'enlightenment_bbp_forums_loop' );
		add_action( 'enlightenment_page_content', 'enlightenment_bbp_template_after_forums_index', 12 );

		add_action( 'enlightenment_after_page_content', 'enlightenment_close_div', 12 );
		add_action( 'enlightenment_after_page_content', 'enlightenment_close_div', 14 );
	} elseif ( bbp_is_single_forum() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
		remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
		remove_action( 'enlightenment_after_entry_footer', 'enlightenment_post_nav' );

		add_action( 'enlightenment_entry_header', 'enlightenment_bbp_template_notices' );
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
		add_action( 'enlightenment_entry_header', 'enlightenment_post_content' );
		// add_action( 'enlightenment_entry_header', 'enlightenment_bbp_forum_stats' );
		// add_action( 'enlightenment_entry_header', 'enlightenment_bbp_nav' );

		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_forum_content_open_container', 6 );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_forums_wrapper_open_container', 8 );

		add_action( 'enlightenment_before_entry_content', 'enlightenment_breadcrumbs' );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_forum_user_actions' );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_single_forum_description' );

		add_action( 'enlightenment_entry_content', 'enlightenment_bbp_template_before_single_forum', 8 );

		add_action( 'enlightenment_entry_content', 'enlightenment_bbp_forums_loop' );
		add_action( 'enlightenment_entry_content', 'enlightenment_bbp_topics_loop' );

		add_action( 'enlightenment_after_entry_content', 'enlightenment_bbp_topic_form' );

		add_action( 'enlightenment_after_entry_content', 'enlightenment_bbp_template_after_single_forum', 12 );
		add_action( 'enlightenment_after_entry_content', 'enlightenment_close_div', 14 );
		add_action( 'enlightenment_after_entry_content', 'enlightenment_close_div', 16 );
	} elseif ( bbp_is_forum_edit() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
		remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
		remove_action( 'enlightenment_after_entry_footer', 'enlightenment_post_nav' );

		add_action( 'enlightenment_entry_header', 'enlightenment_bbp_template_notices' );
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
		// add_action( 'enlightenment_entry_header', 'enlightenment_post_content' );
		add_action( 'enlightenment_entry_header', 'enlightenment_breadcrumbs' );

		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_forum_content_open_container', 6 );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_forums_wrapper_open_container', 8 );

		// add_action( 'enlightenment_before_entry_content', 'enlightenment_breadcrumbs' );
		// add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_forum_user_actions' );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_single_forum_description' );

		add_action( 'enlightenment_entry_content', 'enlightenment_bbp_forum_form' );

		add_action( 'enlightenment_after_entry_content', 'enlightenment_close_div', 14 );
		add_action( 'enlightenment_after_entry_content', 'enlightenment_close_div', 16 );
	} elseif ( enlightenment_bbp_is_topic_archive() || bbp_is_topic_tag() || bbp_is_single_view() ) {
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
		remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );
		remove_action( 'enlightenment_page_content', 'enlightenment_the_loop' );

		add_action( 'enlightenment_page_header', 'enlightenment_bbp_template_notices' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
		add_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bbp_global_stats' );
		// add_action( 'enlightenment_page_header', 'enlightenment_bbp_nav' );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_topic_front_open_container', 6 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_forums_wrapper_open_container', 8 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_form_search' );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_topic_tag_description' );

		add_action( 'enlightenment_page_content', 'enlightenment_bbp_topics_loop' );

		add_action( 'enlightenment_after_page_content', 'enlightenment_close_div', 12 );
		add_action( 'enlightenment_after_page_content', 'enlightenment_close_div', 14 );
	} elseif ( bbp_is_topic_tag_edit() ) {
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
		remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );
		remove_action( 'enlightenment_page_content', 'enlightenment_the_loop' );

		add_action( 'enlightenment_page_header', 'enlightenment_bbp_template_notices' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		// add_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
		add_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_topic_front_open_container', 6 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_forums_wrapper_open_container', 8 );

		// add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_form_search' );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_topic_tag_description' );

		add_action( 'enlightenment_page_content', 'enlightenment_bbp_topic_tag_edit' );

		add_action( 'enlightenment_after_page_content', 'enlightenment_close_div', 12 );
		add_action( 'enlightenment_after_page_content', 'enlightenment_close_div', 14 );
	} elseif ( bbp_is_single_topic() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
		remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
		remove_action( 'enlightenment_after_entry_footer', 'enlightenment_post_nav' );

		add_action( 'enlightenment_entry_header', 'enlightenment_bbp_template_notices' );
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );

		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_topic_wrapper_open_container', 4 );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_forums_wrapper_open_container', 6 );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_template_before_single_topic', 8 );

		add_action( 'enlightenment_before_entry_content', 'enlightenment_breadcrumbs' );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_topic_user_actions' );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_topic_tag_list' );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_single_topic_description' );

		add_action( 'enlightenment_entry_content', 'enlightenment_bbp_replies_loop' );

		add_action( 'enlightenment_before_entry_footer', 'enlightenment_bbp_reply_form' );

		add_action( 'enlightenment_entry_footer', 'enlightenment_bbp_alert_topic_lock' );

		add_action( 'enlightenment_entry_footer', 'enlightenment_bbp_template_after_single_topic', 14 );
		add_action( 'enlightenment_entry_footer', 'enlightenment_close_div', 14 );
		add_action( 'enlightenment_entry_footer', 'enlightenment_close_div', 16 );
	} elseif ( bbp_is_topic_merge() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
		remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
		remove_action( 'enlightenment_after_entry_footer', 'enlightenment_post_nav' );

		add_action( 'enlightenment_entry_header', 'enlightenment_breadcrumbs' );

		add_action( 'enlightenment_entry_content', 'enlightenment_bbp_topic_merge_form' );
	} elseif ( bbp_is_topic_split() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
		remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
		remove_action( 'enlightenment_after_entry_footer', 'enlightenment_post_nav' );

		add_action( 'enlightenment_entry_header', 'enlightenment_breadcrumbs' );

		add_action( 'enlightenment_entry_content', 'enlightenment_bbp_topic_split_form' );
	} elseif ( bbp_is_topic_edit() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
		remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
		remove_action( 'enlightenment_after_entry_footer', 'enlightenment_post_nav' );

		add_action( 'enlightenment_entry_header', 'enlightenment_breadcrumbs' );

		add_action( 'enlightenment_entry_content', 'enlightenment_bbp_topic_form' );
	} elseif ( bbp_is_single_reply() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
		remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );

		add_action( 'enlightenment_entry_header', 'enlightenment_bbp_template_notices' );
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );

		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_reply_wrapper_open_container', 4 );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_forums_wrapper_open_container', 6 );
		add_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_template_before_single_reply', 8 );

		add_action( 'enlightenment_before_entry_content', 'enlightenment_breadcrumbs' );

		add_action( 'enlightenment_entry_content', 'enlightenment_bbp_single_reply' );

		add_action( 'enlightenment_after_entry_content', 'enlightenment_bbp_template_after_single_reply', 14 );
		add_action( 'enlightenment_after_entry_content', 'enlightenment_close_div', 14 );
		add_action( 'enlightenment_after_entry_content', 'enlightenment_close_div', 16 );
	} elseif ( bbp_is_reply_move() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
		remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );

		add_action( 'enlightenment_entry_header', 'enlightenment_breadcrumbs' );

		add_action( 'enlightenment_entry_content', 'enlightenment_bbp_reply_move_form' );
	} elseif ( bbp_is_reply_edit() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
		remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );

		add_action( 'enlightenment_entry_content', 'enlightenment_bbp_reply_form' );
	} elseif ( bbp_is_search() ) {
		remove_action( 'enlightenment_page_content', 'enlightenment_the_loop' );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_forums_wrapper_open_container', 8 );

		add_action( 'enlightenment_page_content', 'enlightenment_bbp_search_loop' );

		add_action( 'enlightenment_after_page_content', 'enlightenment_close_div', 12 );
	} elseif( bbp_is_single_user() ) {
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		remove_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
		remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );
		remove_action( 'enlightenment_page_content', 'enlightenment_the_loop' );

		add_action( 'enlightenment_page_header', 'enlightenment_bbp_template_notices' );
		add_action( 'enlightenment_page_header', 'enlightenment_bbp_single_user_avatar' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_description' );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_forums_wrapper_open_container', 4 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_template_before_user_wrapper', 6 );
		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_user_wrapper_open_container', 8 );

		add_action( 'enlightenment_before_page_content', 'enlightenment_bbp_single_user_details' );

		add_action( 'enlightenment_page_content', 'enlightenment_bbp_single_user_body' );

		add_action( 'enlightenment_after_page_content', 'enlightenment_close_div', 12 );
		add_action( 'enlightenment_after_page_content', 'enlightenment_bbp_template_after_user_wrapper', 14 );
		add_action( 'enlightenment_after_page_content', 'enlightenment_close_div', 16 );
	}
}
add_action( 'wp', 'enlightenment_bbp_layout_hooks' );

function enlightenment_bbp_layout_hooks_overrides() {
	if ( ! is_bbpress() ) {
		return;
	}

	if ( function_exists( 'is_buddypress' ) && is_buddypress() ) {
		return;
	}

	if ( bbp_is_single_forum() ) {
		if ( ! bbp_user_can_view_forum() ) {
			remove_all_actions( 'enlightenment_before_entry_content', 10 );
			remove_all_actions( 'enlightenment_entry_content', 10 );
			remove_all_actions( 'enlightenment_after_entry_content', 10 );

			add_action( 'enlightenment_entry_content', 'enlightenment_bbp_feedback_no_access' );
		} elseif ( post_password_required() ) {
			remove_action( 'enlightenment_entry_header', 'enlightenment_post_content' );
			remove_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_single_forum_description' );
			remove_action( 'enlightenment_entry_content', 'enlightenment_bbp_forums_loop' );
			remove_action( 'enlightenment_entry_content', 'enlightenment_bbp_topics_loop' );
			remove_action( 'enlightenment_after_entry_content', 'enlightenment_bbp_topic_form' );

			add_action( 'enlightenment_entry_content', 'enlightenment_bbp_form_protected' );
		}
	} elseif ( bbp_is_single_topic() ) {
		if ( ! bbp_user_can_view_forum( array( 'forum_id' => bbp_get_topic_forum_id() ) ) ) {
			remove_all_actions( 'enlightenment_before_entry_content', 10 );
			remove_all_actions( 'enlightenment_entry_content', 10 );
			remove_all_actions( 'enlightenment_after_entry_content', 10 );
			remove_all_actions( 'enlightenment_before_entry_footer', 10 );

			add_action( 'enlightenment_entry_content', 'enlightenment_bbp_feedback_no_access' );
		} elseif ( post_password_required() ) {
			remove_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_topic_tag_list' );
			remove_action( 'enlightenment_before_entry_content', 'enlightenment_bbp_single_topic_description' );
			remove_action( 'enlightenment_entry_content', 'enlightenment_bbp_replies_loop' );
			remove_action( 'enlightenment_before_entry_footer', 'enlightenment_bbp_reply_form' );

			add_action( 'enlightenment_entry_content', 'enlightenment_bbp_form_protected' );
		}
	} elseif ( bbp_is_single_reply() ) {
		if ( ! bbp_user_can_view_forum( array( 'forum_id' => bbp_get_reply_forum_id() ) ) ) {
			remove_all_actions( 'enlightenment_before_entry_content', 10 );
			remove_all_actions( 'enlightenment_entry_content', 10 );

			add_action( 'enlightenment_entry_content', 'enlightenment_bbp_feedback_no_access' );
		} elseif ( post_password_required() ) {
			remove_action( 'enlightenment_entry_content', 'enlightenment_bbp_single_reply' );

			add_action( 'enlightenment_entry_content', 'enlightenment_bbp_form_protected' );
		}
	}
}
add_action( 'wp', 'enlightenment_bbp_layout_hooks_overrides', 40 );
