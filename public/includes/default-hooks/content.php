<?php

function enlightenment_wp_actions() {
	if ( ! is_singular() && ! is_front_page() ) {
		if ( is_author() ) {
			add_action( 'enlightenment_page_header', 'enlightenment_author_avatar' );
		}

		add_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
		add_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
		add_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );

		if ( is_author() ) {
			add_action( 'enlightenment_page_header', 'enlightenment_author_social_links' );
		}
	}

	add_action( 'enlightenment_page_content', 'enlightenment_the_loop' );

	if ( ! is_singular() ) {
		add_action( 'enlightenment_after_entries_list', 'enlightenment_posts_nav' );
	}

	add_action( 'enlightenment_no_entries', 'enlightenment_no_posts_found' );
}
add_action( 'wp', 'enlightenment_wp_actions', 8 );

function enlightenment_lead_entry_hooks() {
	if ( is_singular() ) {
		return;
	}

	enightenment_clear_entry_hooks();

	add_action( 'enlightenment_entry_header', 'enlightenment_post_thumbnail' );

	add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );

	if ( ! is_page() ) {
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
	}

	if ( is_search() ) {
		add_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );
	} else {
		add_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
	}
}
add_action( 'enlightenment_before_entry', 'enlightenment_lead_entry_hooks', 3 );

function enlightenment_teaser_entry_hooks() {
	if ( ! current_theme_supports( 'enlightenment-grid-loop' ) ) {
		return;
	}

	if ( enlightenment_is_lead_post() ) {
		return;
	}

	enightenment_clear_entry_hooks();

	add_action( 'enlightenment_entry_header', 'enlightenment_post_thumbnail' );
	add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
	add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
	add_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );
}
add_action( 'enlightenment_before_entry', 'enlightenment_teaser_entry_hooks', 5 );

function enlightenment_singular_entry_hooks() {
	if ( ! is_singular() ) {
		return;
	}

	add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );

	if ( ! is_page() ) {
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
	}

	add_action( 'enlightenment_entry_content', 'enlightenment_post_content' );

	add_action( 'enlightenment_entry_content', 'enlightenment_link_pages' );

	if ( is_singular( 'post' ) ) {
		add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );
	}

	add_action( 'enlightenment_after_entry_footer', 'enlightenment_post_nav' );
	add_action( 'enlightenment_after_entry_footer', 'enlightenment_comments_template' );
}
add_action( 'wp', 'enlightenment_singular_entry_hooks', 8 );
