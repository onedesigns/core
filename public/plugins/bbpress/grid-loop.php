<?php

function enlightenment_bbp_grid_loop_templates( $templates ) {
	if ( isset( $templates['forum'] ) ) {
		unset( $templates['forum'] );
	}

	if ( isset( $templates['topic'] ) ) {
		unset( $templates['topic'] );
	}

	if ( isset( $templates['topic-tag'] ) ) {
		unset( $templates['topic-tag'] );
	}

	return $templates;
}
add_filter( 'enlightenment_grid_loop_templates', 'enlightenment_bbp_grid_loop_templates' );
