<?php

function enlightenment_ajax_navigation_theme_support_args() {
	global $_wp_theme_features;
	$defaults = array(
		'selector'    => '.posts-navigation',
		'type'        => 'GET',
		'next'        => '.nav-previous a, .page-item a.next',
		'content'     => '.entries-list',
		'item'        => '.hentry',
		'labelText'   => __( 'Load more %s', 'enlightenment' ),
		'loadingText' => __( 'Loading more %s &hellip;', 'enlightenment' ),
		'spinner'     => '',
	);
	$args = get_theme_support( 'enlightenment-ajax-navigation' );

	if( is_array( $args ) ) {
		$args = array_shift( $args );
	} else {
		$args = $_wp_theme_features['enlightenment-ajax-navigation'] = array();
	}

	$args = wp_parse_args( $args, $defaults );
	$_wp_theme_features['enlightenment-ajax-navigation'][0] = $args;
}
add_action( 'after_setup_theme', 'enlightenment_ajax_navigation_theme_support_args', 50 );

function enlightenment_enqueue_ajax_navigation_script() {
	global $wp_query;

	if ( is_singular() ) {
		return;
	}

	if ( is_paged() ) {
		return;
	}

	if ( empty( $wp_query->posts ) ) {
		return;
	}

	if ( 2 > $wp_query->max_num_pages ) {
		return;
	}

	$args = get_theme_support( 'enlightenment-ajax-navigation' );

	if( is_array( $args ) ) {
		$args = array_shift( $args );
	} else {
		$args = array();
	}

	$args['styles']  = array();
	$args['scripts'] = array();

	$args = apply_filters( 'enlightenment_ajax_navigation_script_args', $args );

	$label = strtolower( get_post_type_object( $wp_query->posts[0]->post_type )->label );

	$args['labelText']   = sprintf( $args['labelText'],   $label );
	$args['loadingText'] = sprintf( $args['loadingText'], $label );

	wp_enqueue_script( 'ajax-navigation' );
	wp_localize_script( 'ajax-navigation', 'enlightenment_ajax_navigation_args', $args );
}
add_action( 'wp_enqueue_scripts', 'enlightenment_enqueue_ajax_navigation_script' );

function enlightenment_enqueue_ajax_navigation_done_scripts() {
	global $wp_query;

	if ( is_singular() ) {
		return;
	}

	if ( empty( $wp_query->posts ) ) {
		return;
	}

	if ( 2 > $wp_query->max_num_pages ) {
		return;
	}

	global $wp_styles, $wp_scripts;

	$styles  = $wp_styles  instanceof WP_Styles  ? $wp_styles->done  : array();
	$scripts = $wp_scripts instanceof WP_Scripts ? $wp_scripts->done : array();

	?>
<script type="text/javascript" id="ajax-navigation-done-scripts">
	(function($) {
		$.extend( window.enlightenment_ajax_navigation_args.styles,  <?php echo wp_json_encode( $styles );  ?> );
		$.extend( window.enlightenment_ajax_navigation_args.scripts, <?php echo wp_json_encode( $scripts ); ?> );
	})(jQuery);
</script>
<?php
}
add_action( 'wp_footer', 'enlightenment_enqueue_ajax_navigation_done_scripts', 22 );
