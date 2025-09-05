<?php

function enlightenment_post_class( $classes, $class, $post_id ) {
	global $enlightenment_post_counter;

	$post_class = apply_filters( "enlightenment_post_class-count-{$enlightenment_post_counter}", '' );

	if( ! empty( $post_class ) ) {
		$classes[] = $post_class;
	}

	return $classes;
}
add_filter( 'post_class', 'enlightenment_post_class', 10, 3 );

function enlightenment_excerpt_more($more) {
	return ' &#8230;';
}
add_filter( 'excerpt_more', 'enlightenment_excerpt_more' );

function enlightenment_more_link_text( $args ) {
	$args['more_link_text'] = __( 'Read more', 'enlightenment' );

	return $args;
}
add_filter( 'enlightenment_post_content_args', 'enlightenment_more_link_text' );

function enlightenment_filter_shortcode_tag( $output, $tag, $attr, $m ) {
	return apply_filters( sprintf( 'enlightenment_filter_shortcode_tag_%s', $tag ), $output, $attr, $m );
}
add_filter( 'do_shortcode_tag', 'enlightenment_filter_shortcode_tag', 10, 4 );

function enlightenment_content_link_pages( $content ) {
	return $content . "\n" . enlightenment_link_pages( array( 'echo' => false ) );
}

add_filter( 'the_author_description', 'wpautop' );

function enlightenment_register_block_type_args( $args, $name ) {
	return apply_filters( sprintf( 'enlightenment_register_block_type_args_%s', $name ), $args );
}
add_filter( 'register_block_type_args', 'enlightenment_register_block_type_args', 999, 2 );

function enlightenment_render_block( $output, $block ) {
    if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
        return $output;
    }

	if ( ! is_array( $block ) || ! isset( $block['blockName'] ) ) {
		return $output;
	}

    $output = apply_filters( 'enlightenment_render_block', $output, $block );

	$b_slug = $block['blockName'];
	$b_slug = str_replace( '/', '_', $b_slug );
	$b_slug = str_replace( '-', '_', $b_slug );
	$filter = sprintf( 'enlightenment_render_block_%s', $b_slug );
	$output = apply_filters( $filter, $output, $block );

    return $output;
}
add_filter( 'render_block', 'enlightenment_render_block', 999, 2 );

function enlightenment_filter_columns_block( $output, $block ) {
	$count  = ( isset( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) ? count( $block['innerBlocks'] ) : 0;
	$offset = strpos( $output, 'class="wp-block-columns"' );

    if ( false === $offset ) {
        $offset = strpos( $output, 'class="wp-block-columns ' );
    }

    if ( false === $offset ) {
        $offset = strpos( $output, ' wp-block-columns"' );
    }

    if ( false === $offset ) {
        $offset = strpos( $output, ' wp-block-columns ' );
    }

    if ( false !== $offset ) {
        $offset += 7;
        $offset  = strpos( $output, '"', $offset + 1 );
        $output  = substr_replace( $output, sprintf( ' wp-block-columns-count-%d', $count ), $offset, 0 );
    }

	return $output;
}
add_filter( 'enlightenment_render_block_core_columns', 'enlightenment_filter_columns_block', 10, 2 );

function enlightenment_filter_query_pagination_block( $output, $block ) {
	if ( empty( $block['innerBlocks'] ) ) {
		return $output;
	}

	$offset = strpos( $output, 'class="wp-block-query-pagination"' );

	if ( false === $offset ) {
		$offset = strpos( $output, 'class="wp-block-query-pagination ' );
	}

	if ( false !== $offset ) {
		$start  = strpos( $output, '>' ) + 1;
		$end    = strpos( $output, '</div>', $start );
		$length = $end - $start;
		$inner  = substr( $output, $start, $length );

		if ( empty( trim( $inner ) ) ) {
			$output = substr_replace( $output, ' is-empty', $offset + 32, 0 );
			$output = str_replace( $inner, '', $output );
		}
	}

	$hasNextPrev = false;
	$hasNumbers  = false;

	foreach ( $block['innerBlocks'] as $key => $block ) {
		if ( 'core/query-pagination-previous' == $block['blockName'] ) {
			$hasNextPrev = true;
		}

		if ( 'core/query-pagination-next' == $block['blockName'] ) {
			$hasNextPrev = true;
		}

		if ( 'core/query-pagination-numbers' == $block['blockName'] ) {
			$hasNumbers = true;
		}
	}

	if ( $hasNumbers ) {
		$output = str_replace( 'class="wp-block-query-pagination ', 'class="wp-block-query-pagination has-numbers ', $output );
		$output = str_replace( 'class="wp-block-query-pagination"', 'class="wp-block-query-pagination has-numbers"', $output );
	}

	if ( $hasNextPrev ) {
		$output = str_replace( 'class="wp-block-query-pagination ', 'class="wp-block-query-pagination has-next-prev ', $output );
		$output = str_replace( 'class="wp-block-query-pagination"', 'class="wp-block-query-pagination has-next-prev"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_render_block_core_query_pagination', 'enlightenment_filter_query_pagination_block', 10, 2 );
