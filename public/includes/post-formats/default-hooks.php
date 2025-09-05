<?php

function enlightenment_post_format_hooks() {
	if( is_singular() ) {
		return;
	}

	if ( ! post_type_supports( get_post_type(), 'post-formats' ) ) {
		return;
	}

	enightenment_clear_entry_hooks();

	switch( get_post_format() ) {
		case 'gallery' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_gallery' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );

			break;

		case 'image' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_image' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );

			break;

		case 'video' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_video' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );

			break;

		case 'audio' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_audio' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );
			break;
		case 'aside' :
			add_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );

			break;

		case 'link' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );

			break;

		case 'quote' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_blockquote' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );

			break;

		case 'status' :
			add_action( 'enlightenment_entry_content', 'enlightenment_author_avatar' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );

			break;

		case 'chat' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_content' );

			break;

		default :
			enlightenment_lead_entry_hooks();
	}
}
add_action( 'enlightenment_before_entry', 'enlightenment_post_format_hooks', 4 );

function enlightenment_teaser_post_format_hooks() {
	if( ! current_theme_supports( 'enlightenment-grid-loop' ) ) {
		return;
	}

	if( enlightenment_is_lead_post() ) {
		return;
	}

	if( is_singular() ) {
		return;
	}

	if( '' == get_post_format() ) {
		return;
	}

	enightenment_clear_entry_hooks();

	switch( get_post_format() ) {
		case 'gallery' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_gallery' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );
			break;
		case 'image' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_image' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );
			break;
		case 'video' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_video' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );
			break;
		case 'audio' :
			add_action( 'enlightenment_entry_header', 'enlightenment_post_thumbnail' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_audio' );
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );
			break;
		case 'aside' :
			add_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );
			break;
		case 'link' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );
			break;
		case 'quote' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_blockquote' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );
			break;
		case 'status' :
			add_action( 'enlightenment_entry_content', 'enlightenment_author_avatar' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );
			break;
		case 'chat' :
			add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
			add_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
			add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );
			break;
	}
}
add_action( 'enlightenment_before_entry', 'enlightenment_teaser_post_format_hooks', 6 );
