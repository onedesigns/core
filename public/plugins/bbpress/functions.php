<?php

function enlightenment_bbp_before_main_content() {
	do_action( 'bbp_before_main_content' );
}

function enlightenment_bbp_after_main_content() {
	do_action( 'bbp_after_main_content' );
}

function enlightenment_bbp_template_before_forums_index() {
    do_action( 'bbp_template_before_forums_index' );
}

function enlightenment_bbp_template_after_forums_index() {
    do_action( 'bbp_template_after_forums_index' );
}

function enlightenment_bbp_template_before_single_forum() {
    do_action( 'bbp_template_before_single_forum' );
}

function enlightenment_bbp_template_after_single_forum() {
    do_action( 'bbp_template_after_single_forum' );
}

function enlightenment_bbp_template_before_single_topic() {
    do_action( 'bbp_template_before_single_topic' );
}

function enlightenment_bbp_template_after_single_topic() {
    do_action( 'bbp_template_after_single_topic' );
}

function enlightenment_bbp_template_before_single_reply() {
    do_action( 'bbp_template_before_single_reply' );
}

function enlightenment_bbp_template_after_single_reply() {
    do_action( 'bbp_template_after_single_reply' );
}

function enlightenment_bbp_template_before_user_wrapper() {
    do_action( 'bbp_template_before_user_wrapper' );
}

function enlightenment_bbp_template_after_user_wrapper() {
    do_action( 'bbp_template_after_user_wrapper' );
}

function enlightenment_bbp_is_forum_archive_forums() {
	return bbp_is_forum_archive() && 'forums' === bbp_show_on_root();
}

function enlightenment_bbp_is_forum_archive_topics() {
	return bbp_is_forum_archive() && 'topics' === bbp_show_on_root();
}

function enlightenment_bbp_is_topic_archive () {
	return bbp_is_topic_archive() || enlightenment_bbp_is_forum_archive_topics();
}

function enlightenment_bbp_forum_front_open_container( $args = null ) {
	$defaults = array(
		'container_class' => 'bbp-forum-front',
		'container_id'    => 'forum-front',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_forum_front_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( 'div', $args['container_class'], $args['container_id'] );

	$output = apply_filters( 'enlightenment_bbp_forum_front_open_container', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_forum_content_open_container( $args = null ) {
	$defaults = array(
		'container_class' => bbp_is_forum_edit() ? 'bbp-edit-page' : 'bbp-forum-content',
		'container_id'    => bbp_is_forum_edit() ? 'bbp-edit-page' : sprintf( 'forum-%s', bbp_user_can_view_forum() ? bbp_get_forum_id() : 'private' ),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_forum_content_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( 'div', $args['container_class'], $args['container_id'] );

	$output = apply_filters( 'enlightenment_bbp_forum_content_open_container', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_topic_front_open_container( $args = null ) {
	$defaults = array(
		'container_class' => bbp_is_single_view() ? sprintf( 'bbp-view-%s', bbp_get_view_id() ) : ( bbp_is_topic_tag() ? 'bbp-topic-tag' : 'bbp-topics-front' ),
		'container_id'    => bbp_is_single_view() ? 'bbp-view' : ( bbp_is_topic_tag() ? 'topic-tag' : 'topic-front' ),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_topic_front_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( 'div', $args['container_class'], $args['container_id'] );

	$output = apply_filters( 'enlightenment_bbp_topic_front_open_container', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_forums_wrapper_open_container( $args = null ) {
	$defaults = array(
		'container_class' => 'bbpress-wrapper',
		'container_id'    => 'bbpress-forums',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_forums_wrapper_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( 'div', $args['container_class'], $args['container_id'] );

	$output = apply_filters( 'enlightenment_bbp_forums_wrapper_open_container', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_topic_wrapper_open_container( $args = null ) {
	$defaults = array(
		'container_class' => 'bbp-topic-wrapper',
		'container_id'    => bbp_user_can_view_forum( array( 'forum_id' => bbp_get_topic_forum_id() ) ) ? sprintf( 'bbp-topic-wrapper-%s', bbp_get_topic_id() ) : 'forum-private',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_topic_wrapper_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( 'div', $args['container_class'], $args['container_id'] );

	$output = apply_filters( 'enlightenment_bbp_topic_wrapper_open_container', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_reply_wrapper_open_container( $args = null ) {
	$defaults = array(
		'container_class' => 'bbp-reply-wrapper',
		'container_id'    => bbp_user_can_view_forum( array( 'forum_id' => bbp_get_reply_forum_id() ) ) ? sprintf( 'bbp-reply-wrapper-%s', bbp_get_reply_id() ) : 'forum-private',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_reply_wrapper_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( 'div', $args['container_class'], $args['container_id'] );

	$output = apply_filters( 'enlightenment_bbp_reply_wrapper_open_container', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_user_wrapper_open_container( $args = null ) {
	$defaults = array(
		'container_class' => '',
		'container_id'    => 'bbp-user-wrapper',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_user_wrapper_container_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = enlightenment_open_tag( 'div', $args['container_class'], $args['container_id'] );

	$output = apply_filters( 'enlightenment_bbp_user_wrapper_open_container', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
