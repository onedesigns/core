<?php

function enlightenment_custom_queries() {
	$queries = array(
		'sticky_posts' => array(
			'name' => __( 'Sticky Posts', 'enlightenment' ),
			'args' => array( 'post__in' => get_option( 'sticky_posts' ) ),
		),
		'post_type_archive' => array(
			'name' => __( 'Post Type Archive', 'enlightenment' ),
			'args' => array( 'post_type' => null ),
		),
		'post_type' => array(
			'name' => __( 'Single Post Type', 'enlightenment' ),
			'args' => array( 'p' => null, 'post_type' => null ),
		),
		'page' => array(
			'name' => __( 'Single Page', 'enlightenment' ),
			'args' => array( 'page_id' => null ),
		),
		'pages' => array(
			'name' => __( 'Multiple Pages', 'enlightenment' ),
			'args' => array( 'post__in' => null, 'post_type' => 'page' ),
		),
		'gallery' => array(
			'name' => __( 'Image Gallery', 'enlightenment' ),
			'args' => array( 'post_type' => 'attachment', 'post_status' => 'any', 'post__in' => null ),
		),
		'author' => array(
			'name' => __( 'Author Archive', 'enlightenment' ),
			'args' => array( 'author' => null ),
		),
		'taxonomy' => array(
			'name' => __( 'Taxonomy', 'enlightenment' ),
			'args' => array( '%taxonomy%' => null ),
		),
	);

	return apply_filters( 'enlightenment_custom_queries', $queries );
}

function enlightenment_custom_post_types() {
	$queries    = array();
	$post_types = get_post_types( array( 'publicly_queryable' => true ), 'objects' );

	unset( $post_types['attachment'] );

	foreach ( $post_types as $name => $post_type ) {
		$queries[$name] = array(
			'name' => $post_type->labels->singular_name,
			'args' => array( 'p' => null, 'post_type' => $name ),
		);
	}

	return apply_filters( 'enlightenment_custom_post_types', $queries );
}

function enlightenment_custom_taxonomies() {
	$queries    = array();
	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

	foreach ( $taxonomies as $name => $taxonomy ) {
		if ( 'post_format' == $name ) {
			$taxonomy->labels->singular_name = __( 'Post Format', 'enlightenment' );
		}

		$queries[$name] = array(
			'name' => $taxonomy->labels->singular_name,
			'args' => array( $name => null ),
		);
	}

	return apply_filters( 'enlightenment_custom_taxonomies', $queries );
}

function enlightenment_custom_query_post_thumbnail_size( $size ) {
	global $enlightenment_custom_widget_instance, $enlightenment_custom_lead_posts, $enlightenment_custom_post_counter;

	$instance   = $enlightenment_custom_widget_instance;
	$lead_posts = $enlightenment_custom_lead_posts;
	$counter    = $enlightenment_custom_post_counter;

	if (
		'post_type' == $instance['query']
		||
		'page' == $instance['query']
	) {
		$lead_posts = 1;
	}

	if ( 'list' == $instance['type'] || 'gallery' == $instance['type'] ) {
		if( $counter > $lead_posts ) {
			return 'enlightenment-custom-query-small-thumb';
		}
	}

	return $size;
}

function enlightenment_open_slides_container() {
	echo enlightenment_open_tag( 'ul', 'slides' );
}

function enlightenment_close_slides_container() {
	echo enlightenment_close_tag( 'ul' );
}

function enlightenment_custom_query_widget_open_slide_container() {
	echo enlightenment_open_tag( 'div', 'slide-container' );
}

function enlightenment_custom_query_widget_image_link() {
	$post = get_post( get_the_ID() );

	if ( 'attachment' != $post->post_type ) {
		return;
	}

	$size = apply_filters( 'enlightenment_custom_query_widget_image_size', 'full' );

	echo wp_get_attachment_link( get_the_ID(), $size );
}

function enlightenment_custom_query_widget_lead_posts() {
	global $enlightenment_custom_widget_instance, $enlightenment_custom_lead_posts;

	$instance = $enlightenment_custom_widget_instance;

	if (
		'post_type' == $instance['query']
		||
		'page' == $instance['query']
	) {
		return 1;
	}

	if ( isset( $enlightenment_custom_lead_posts ) ) {
		return $enlightenment_custom_lead_posts;
	}

	return false;
}
