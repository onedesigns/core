<?php

function enightenment_clear_entry_hooks() {
	if ( ! is_singular() ) {
		remove_all_actions( 'enlightenment_before_entry_header',  10 );
		remove_all_actions( 'enlightenment_entry_header',         10 );
		remove_all_actions( 'enlightenment_after_entry_header',   10 );
		remove_all_actions( 'enlightenment_before_entry_content', 10 );
		remove_all_actions( 'enlightenment_entry_content',        10 );
		remove_all_actions( 'enlightenment_after_entry_content',  10 );
		remove_all_actions( 'enlightenment_before_entry_footer',  10 );
		remove_all_actions( 'enlightenment_entry_footer',         10 );
		remove_all_actions( 'enlightenment_after_entry_footer',   10 );
	}
}

function enlightenment_custom_loop( $args = null ) {
	$defaults = array(
		'container'            => '',
		'container_class'      => '',
		'container_id'         => '',
		'container_extra_atts' => '',
		'query_name'           => null,
		'query_args'           => null,
		'entry_tag'            => 'article',
		'entry_class'          => '',
		'entry_id'             => '',
		'entry_extra_atts'     => '',
		'default_post_class'   => false,
		'header_tag'           => 'header',
		'header_class'         => 'entry-header',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_custom_loop_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['query_name'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( "The 'query_name' argument is required.", 'enlightenment' ), '1.0' );
		return;
	}

	if ( empty( $args['query_args'] ) ) {
		return;
	}

	global $enlightenment_custom_query, $enlightenment_custom_query_name;

	$enlightenment_custom_query_name = $args['query_name'];

	$query = new WP_Query( $args['query_args'] );

	$enlightenment_custom_query = $query;

	if ( ! $args['echo'] ) {
		ob_start();
	}

	do_action( 'enlightenment_before_custom_loop', $args['query_name'] );

	if ( $query->have_posts() ) {
		global $enlightenment_custom_post_counter;
		$enlightenment_custom_post_counter = 0;

		do_action( 'enlightenment_custom_before_entries_list' );

		echo enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

		while ( $query->have_posts() ) {
			$query->the_post();

			$enlightenment_custom_post_counter++;

			$count = $enlightenment_custom_post_counter;

			$post_class = ( $args['default_post_class'] ? implode( ' ', get_post_class() ) . ' ' : '' ) . $args['entry_class'];
			$post_class = apply_filters( 'enlightenment_custom_post_class', $post_class );
			$post_class = apply_filters( 'enlightenment_custom_post_class-count-' . $count, $post_class );

			$post_id = apply_filters( 'enlightenment_custom_post_id', sprintf( $args['entry_id'], get_the_ID() ) );
			$post_id = apply_filters( 'enlightenment_custom_post_id-count-' . $count, $post_id );

			$post_extra_atts = apply_filters( 'enlightenment_custom_post_extra_atts', $args['entry_extra_atts'] );
			$post_extra_atts = apply_filters( 'enlightenment_custom_post_extra_atts-count-' . $count, $post_extra_atts );

			do_action( 'enlightenment_custom_before_entry' );

			echo enlightenment_open_tag( $args['entry_tag'], $post_class, $post_id, $post_extra_atts );

			do_action( 'enlightenment_custom_before_entry_header' );
			if ( has_action( 'enlightenment_custom_entry_header' ) ) {
				echo enlightenment_open_tag( $args['header_tag'], $args['header_class'] );
				do_action( 'enlightenment_custom_entry_header' );
				echo enlightenment_close_tag( $args['header_tag'] );
			}
			do_action( 'enlightenment_custom_after_entry_header' );

			do_action( 'enlightenment_custom_before_entry_content' );
			do_action( 'enlightenment_custom_entry_content', '(more&hellip;)' );
			do_action( 'enlightenment_custom_after_entry_content' );

			do_action( 'enlightenment_custom_before_entry_footer' );
			do_action( 'enlightenment_custom_entry_footer' );
			do_action( 'enlightenment_custom_after_entry_footer' );

			echo enlightenment_close_tag( $args['entry_tag'] );

			do_action( 'enlightenment_custom_after_entry' );
		}

		echo enlightenment_close_tag( $args['container'] );

		do_action( 'enlightenment_custom_after_entries_list' );

		wp_reset_postdata();

		unset( $GLOBALS['enlightenment_custom_post_counter'] );
	} else {
		do_action( 'enlightenment_custom_no_entries' );
	}

	do_action( 'enlightenment_after_custom_loop', $args['query_name'] );

	unset( $GLOBALS['enlightenment_custom_query_name'] );

	if ( ! $args['echo'] ) {
		$output = ob_get_clean();
		return $output;
	}
}

function enlightenment_is_custom_loop() {
	return isset( $GLOBALS['enlightenment_custom_query_name'] );
}

function enlightenment_remove_custom_loop_hooks() {
	remove_all_actions( 'enlightenment_custom_before_entries_list' );
	remove_all_actions( 'enlightenment_custom_before_entry' );
	remove_all_actions( 'enlightenment_custom_before_entry_header' );
	remove_all_actions( 'enlightenment_custom_entry_header' );
	remove_all_actions( 'enlightenment_custom_after_entry_header' );
	remove_all_actions( 'enlightenment_custom_before_entry_content' );
	remove_all_actions( 'enlightenment_custom_entry_content' );
	remove_all_actions( 'enlightenment_custom_after_entry_content' );
	remove_all_actions( 'enlightenment_custom_after_entry' );
	remove_all_actions( 'enlightenment_custom_after_entries_list' );
	remove_all_actions( 'enlightenment_custom_no_entries' );
	remove_all_actions( 'enlightenment_custom_before_entry_footer' );
	remove_all_actions( 'enlightenment_custom_entry_footer' );
	remove_all_actions( 'enlightenment_custom_after_entry_footer' );
}
add_action( 'enlightenment_after_custom_loop', 'enlightenment_remove_custom_loop_hooks', 999 );

function enlightenment_content_clearfix( $content ) {
	return $content . "\n" . enlightenment_clearfix( array( 'echo' => false ) );
}

function enlightenment_strip_hashtag_link( $more_link ) {
	$more_link = preg_replace( '|#more-[0-9]+|', '', $more_link );
	return $more_link;
}

function enlightenment_is_gutenberg_active() {
	$gutenberg    = false;
	$block_editor = false;

	if ( has_filter( 'replace_editor', 'gutenberg_init' ) ) {
		// Gutenberg is installed and activated.
		$gutenberg = true;
	}

	if ( version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' ) ) {
		// Block editor.
		$block_editor = true;
	}

	if ( ! $gutenberg && ! $block_editor ) {
		return false;
	}

	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	if ( ! is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
		return true;
	}

	return ( 'no-replace' === get_option( 'classic-editor-replace' ) );
}

function enlightenment_get_author_social_links_services() {
	return apply_filters( 'enlightenment_author_social_links_services', array_merge(
		array(
			'url' => __( 'Website', 'enlightenment' ),
		),
		enlightenment_get_social_links_services()
	) );
}

function enlightenment_get_author_social_links_icons() {
    return apply_filters( 'enlightenment_author_social_links_icons', array_merge(
		array(
			'url' => 's fa-link',
		),
		enlightenment_get_social_links_icons()
	) );
}

function enlightenment_get_author_social_links() {
	$links    = array();
	$services = enlightenment_get_author_social_links_services();

	foreach ( $services as $service => $label ) {
		$url = get_the_author_meta( $service );

		if ( ! empty( $url ) ) {
			if ( 'twitter' == $service ) {
				$url = sprintf( 'https://twitter.com/%s', $url );
			}

			$links[ $service ] = array(
				'url'   => $url,
				'label' => $label,
			);
		}
	}

	return $links;
}
