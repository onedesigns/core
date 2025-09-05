<?php

function enlightenment_jetpack_testimonials_setup() {
	// Add support for testimonials
	add_theme_support( 'jetpack-testimonial' );
}
add_action( 'after_setup_theme', 'enlightenment_jetpack_testimonials_setup', 35 );

function enlightenment_bootstrap_jetpack_testimonials_shortcode_tag( $output, $atts ) {
	if ( ! current_theme_supports( 'enlightenment-bootstrap' ) ) {
		return $output;
	}

	if ( ! is_array( $atts ) ) {
		$atts = array();
	}

	$columns = isset( $atts['columns'] ) ? absint( $atts['columns'] ) : 1;
    $image   = isset( $atts['image'] ) && 'true' != $atts['image'] ? false : true;

	if ( 1 == $columns ) {
        return $output;
    }

    switch ( $columns ) {
		case 2:
			$colspan = '-6';
			break;

		case 3:
			$colspan = '-4';
			break;

		case 4:
			$colspan = '-3';
			break;

		case 6:
			$colspan = '-2';
			break;

		default:
			$colspan = '';
			break;
	}

	$output = str_replace(
		sprintf( '<div class="jetpack-testimonial-shortcode column-%d">', $columns ),
		sprintf( '<div class="jetpack-testimonial-shortcode column-%d">%s<div class="row">', $columns, "\n" ),
		$output
	);

	$output = str_replace(
		sprintf( '<div class="testimonial-entry testimonial-entry-column-%d ', $columns ),
		sprintf( '<div class="col-md%s">%s<div class="testimonial-entry ', $colspan, "\n" ),
		$output
	);

	$output = str_replace(
		sprintf( '<div class="testimonial-entry testimonial-entry-column-%d"', $columns ),
		sprintf( '<div class="col-md%s">%s<div class="testimonial-entry"', $colspan, "\n" ),
		$output
	);

	$output = str_replace(
		'</div><!-- close .testimonial-entry -->',
		'</div>' . "\n" . '</div><!-- close .testimonial-entry -->',
		$output
	);

	$output = str_replace(
		'</div><!-- close .jetpack-testimonial-shortcode -->',
		'</div>' . "\n" . '</div><!-- close .jetpack-testimonial-shortcode -->',
		$output
	);

	$output = str_replace( ' testimonial-entry-mobile-first-item-row', '', $output );
	$output = str_replace( ' testimonial-entry-mobile-last-item-row',  '', $output );
	$output = str_replace( ' testimonial-entry-first-item-row',        '', $output );
	$output = str_replace( ' testimonial-entry-last-item-row',         '', $output );

	return $output;
}
add_filter( 'enlightenment_filter_shortcode_tag_testimonials', 'enlightenment_bootstrap_jetpack_testimonials_shortcode_tag', 10, 2 );
add_filter( 'enlightenment_filter_shortcode_tag_jetpack_testimonials', 'enlightenment_bootstrap_jetpack_testimonials_shortcode_tag', 10, 2 );
