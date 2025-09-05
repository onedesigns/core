<?php

function enlightenment_custom_query_add_slider_background_image( $atts ) {
	global $enlightenment_custom_widget_instance;

	if ( ! isset( $enlightenment_custom_widget_instance ) ) {
		return $atts;
	}

	if ( 'slider' == $enlightenment_custom_widget_instance['type'] && has_post_thumbnail() ) {
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		$atts     .= sprintf( ' style="background-image: url(%s);"', $thumbnail[0] );
	}

	return $atts;
}
add_filter( 'enlightenment_custom_post_extra_atts', 'enlightenment_custom_query_add_slider_background_image' );

function enlightenment_call_flexslider_script( $deps ) {
	if ( is_active_widget( false, false, 'enlightenment-custom-query' ) ) {
		$deps[] = 'flexslider';
	}

	return $deps;
}
add_filter( 'enlightenment_call_js', 'enlightenment_call_flexslider_script' );

function enlightenment_custom_query_widget_post_class( $class ) {
	global $enlightenment_custom_widget_instance, $enlightenment_custom_post_counter;

	$instance = $enlightenment_custom_widget_instance;
	$counter  = $enlightenment_custom_post_counter;

	if (
		'slider' == $instance['type']
		||
		'carousel' == $instance['type']
		||
		'post_type' == $instance['query']
		||
		'page' == $instance['query']
	) {
		$class .= ' custom-entry-lead';
	} elseif (
		current_theme_supports( 'enlightenment-grid-loop' )
		&&
		! current_theme_supports( 'enlightenment-bootstrap' )
	) {
		$grid = enlightenment_get_grid( $instance['grid'] );

		if ( $counter <= enlightenment_custom_query_widget_lead_posts() ) {
			if ( ! empty( $grid['full_width_class'] ) ) {
				$class .= ' ' . $grid['full_width_class'];
			}
		} else {
			if ( ! empty( $grid['entry_class'] ) ) {
				$class .= ' ' . $grid['entry_class'];
			}
		}
	}

	if ( current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail() ) {
		$class .= ' custom-entry-has-thumbnail';
	}

	$class .= ' custom-post-type-' . get_post_type();

	return $class;
}

function enlightenment_custom_query_widget_lead_post_class( $class ) {
	$class .= ' custom-entry-lead';

	return $class;
}

function enlightenment_custom_query_widget_entry_title_args( $args ) {
	$args['container'] = 'h4';

	return apply_filters( 'enlightenment_custom_query_widget_entry_title_args', $args );
}

function enlightenment_custom_query_widget_entry_meta_args( $args ) {
	if ( 'post' == get_post_type() ) {
		$args['format'] = '%1$s';
	}

	return apply_filters( 'enlightenment_custom_query_widget_entry_meta_args', $args );
}

function enlightenment_custom_query_widget_excerpt_length( $length ) {
	global $enlightenment_custom_query_name;

	if ( isset( $enlightenment_custom_query_name ) && 'custom_query_widget_list' == $enlightenment_custom_query_name ) {
		return apply_filters( 'enlightenment_custom_query_widget_excerpt_length', 24 );
	}

	return $length;
}
add_filter( 'excerpt_length', 'enlightenment_custom_query_widget_excerpt_length' );

add_filter( 'enlightenment_custom_query_widget_tagline', 'wpautop' );
add_filter( 'enlightenment_custom_query_widget_tagline', 'do_shortcode' );

function enlightenment_custom_query_widget_row_gutter( $args ) {
	if ( ! doing_action( 'enlightenment_custom_before_entries_list' ) ) {
		return $args;
	}

	global $enlightenment_custom_widget_instance;

	if ( ! isset( $enlightenment_custom_widget_instance ) ) {
		return $args;
	}

	if ( 'gallery' != $enlightenment_custom_widget_instance['type'] ) {
		return $args;
	}

	$args['container_class'] .= ' g-2';

	return $args;
}
add_filter( 'enlightenment_row_args', 'enlightenment_custom_query_widget_row_gutter' );
