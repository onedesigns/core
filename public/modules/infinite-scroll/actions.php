<?php

function enlightenment_infinite_scroll_theme_support_args() {
	global $_wp_theme_features;

	$defaults = array(
		'loading'         => array(
			'selector'    => '.site-main',
			'img'         => enlightenment_images_directory_uri() . '/ajax-loader-transparent.gif',
			'msgText'     => __( 'Loading more %s &hellip;', 'enlightenment' ),
			'finishedMsg' => __( 'There are no more %s to display.', 'enlightenment' ),
		),
		'navSelector'     => '.posts-navigation',
		'nextSelector'    => '.nav-previous a, .page-item a.next',
		'contentSelector' => '.entries-list',
		'itemSelector'    => '.hentry',
		'debug'           => false,
	);

	$args = get_theme_support( 'enlightenment-infinite-scroll' );

	if( is_array( $args ) ) {
		$args = array_shift( $args );
	} else {
		$args = $_wp_theme_features['enlightenment-infinite-scroll'] = array();
	}

	if ( isset( $args['loading'] ) && is_array( $args['loading'] ) ) {
		$args['loading'] = wp_parse_args( $args['loading'], $defaults['loading'] );
	}

	if ( isset( $args['state'] ) && is_array( $args['state'] ) ) {
		$args['state'] = wp_parse_args( $args['state'], $defaults['state'] );
	}

	$args = wp_parse_args( $args, $defaults );

	$_wp_theme_features['enlightenment-infinite-scroll'][0] = $args;
}
add_action( 'after_setup_theme', 'enlightenment_infinite_scroll_theme_support_args', 50 );

function enlightenment_enqueue_infinite_scroll_script() {
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

	$args = get_theme_support( 'enlightenment-infinite-scroll' );

	if( is_array( $args ) ) {
		$args = array_shift( $args );
	} else {
		$args = array();
	}

	$args['styles']  = array();
	$args['scripts'] = array();

	$args = apply_filters( 'enlightenment_infinite_scroll_script_args', $args );

	$label = strtolower( get_post_type_object( $wp_query->posts[0]->post_type )->label );

	$args['loading']['msgText']     = sprintf( $args['loading']['msgText'],     $label );
	$args['loading']['finishedMsg'] = sprintf( $args['loading']['finishedMsg'], $label );

	wp_enqueue_script( 'infinitescroll' );
	wp_localize_script( 'infinitescroll', 'enlightenment_infinite_scroll_args', $args );
}
add_action( 'wp_enqueue_scripts', 'enlightenment_enqueue_infinite_scroll_script' );

function enlightenment_enqueue_infinite_scroll_done_scripts() {
	global $wp_query;

	if ( is_singular() ) {
		return;
	}

	if ( empty( $wp_query->posts ) ) {
		return;
	}

	if ( empty( $wp_query->posts ) ) {
		return;
	}

	if ( 1 < $wp_query->query_vars->paged ) {
		return;
	}

	global $wp_styles, $wp_scripts;

	$styles  = $wp_styles  instanceof WP_Styles  ? $wp_styles->done  : array();
	$scripts = $wp_scripts instanceof WP_Scripts ? $wp_scripts->done : array();

	?>
<script type="text/javascript" id="infinite-scroll-done-scripts">
	(function($) {
		if ( ! window.enlightenment_infinite_scroll_args ) {
			return;
		}

		$.extend( window.enlightenment_infinite_scroll_args.styles,  <?php echo wp_json_encode( $styles );  ?> );
		$.extend( window.enlightenment_infinite_scroll_args.scripts, <?php echo wp_json_encode( $scripts ); ?> );
	})(jQuery);
</script>
<?php
}
add_action( 'wp_footer', 'enlightenment_enqueue_infinite_scroll_done_scripts', 22 );
