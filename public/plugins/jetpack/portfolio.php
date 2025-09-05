<?php

function enlightenment_jetpack_portfolio_setup() {
	// Add support for portfolio projects
	add_theme_support( 'jetpack-portfolio' );
}
add_action( 'after_setup_theme', 'enlightenment_jetpack_portfolio_setup', 35 );

function enlightenment_project_types( $args = null ) {
	$terms = get_the_terms( get_the_ID(), 'jetpack-portfolio-type' );

	if ( empty( $terms ) ) {
		$terms = array();
	}

	$defaults = array(
		'container'       => 'span',
		'container_class' => 'project-types',
		'before'          => '',
		'after'           => '',
		'format'          => _n( 'Category: %s', 'Categories: %s', count( $terms ), 'enlightenment' ),
		'sep'             => '<span class="sep">,</span> ',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_project_types_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( 'jetpack-portfolio' != get_post_type() ) {
		return;
	}

	$terms = get_the_term_list( get_the_ID(), 'jetpack-portfolio-type', $args['before'], $args['sep'], $args['after'] );

	$output = '';
	if ( ! empty( $terms ) ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'] );
		$output .= $args['before'];
		$output .= sprintf( $args['format'], $terms );
		$output .= $args['after'];
		$output .= enlightenment_close_tag( $args['container'] );
	}
	$output = apply_filters( 'enlightenment_project_types', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_project_tags( $args = null ) {
	$terms = get_the_terms( get_the_ID(), 'jetpack-portfolio-tag' );

	if ( empty( $terms ) ) {
		$terms = array();
	}

	$defaults = array(
		'container'       => 'span',
		'container_class' => 'project-tags',
		'before'          => '',
		'after'           => '',
		'format'          => _n( 'Tag: %s', 'Tags: %s', count( $terms ), 'enlightenment' ),
		'sep'             => '<span class="sep">,</span> ',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_project_tags_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( 'jetpack-portfolio' != get_post_type() ) {
		return;
	}

	$terms = get_the_term_list( get_the_ID(), 'jetpack-portfolio-tag', $args['before'], $args['sep'], $args['after'] );

	$output = '';
	if ( ! empty( $terms ) ) {
		$output .= enlightenment_open_tag( $args['container'], $args['container_class'] );
		$output .= $args['before'];
		$output .= sprintf( $args['format'], $terms );
		$output .= $args['after'];
		$output .= enlightenment_close_tag( $args['container'] );
	}
	$output = apply_filters( 'enlightenment_project_tags', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_portfolio_entry_meta_args( $args ) {
	if ( 'jetpack-portfolio' != get_post_type() ) {
		return $args;
	}

	$project_types = enlightenment_project_types( array( 'echo' => false ) );
	$project_tags  = enlightenment_project_tags( array( 'echo' => false ) );

	if ( is_singular() ) {
		$args['format'] = sprintf( '%1$s%2$s', $project_types, $project_tags );
	} else {
		$args['format'] = sprintf( '%1$s', $project_types, $project_tags );
	}

	return $args;
}
add_filter( 'enlightenment_entry_meta_args', 'enlightenment_portfolio_entry_meta_args' );

function enlightenment_project_types_filter( $args = null ) {
	$defaults = array(
		'container'          => 'nav',
		'container_class'    => 'project-types-filter secondary-navigation',
		'wrapper_tag'        => 'div',
		'wrapper_class'      => 'menu-project-types-filter-container',
		'filter_title'       => __( 'Filter Projects', 'enlightenment' ),
		'filter_title_tag'   => 'h2',
		'filter_title_class' => 'screen-reader-text',
		'filter_tag'         => 'ul',
		'filter_class'       => 'menu',
		'term_tag'           => 'li',
		'term_class'         => 'project-type',
		'link_class'         => '',
		'current_term_class' => 'current-project-type',
		'sep'                => '',
		'echo'               => true,
	);
	$defaults = apply_filters( 'enlightenment_project_types_filter_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$terms = get_terms( 'jetpack-portfolio-type' );

	$output = '';
	if ( ! empty( $terms ) ) {
		$link_class  = empty( $args['link_class'] )? '' : sprintf( ' class="%s"', $args['link_class'] );
		$current_url = enlightenment_get_current_uri();

		$output .= enlightenment_open_tag( $args['container'], $args['container_class'] );
		$output .= enlightenment_open_tag( $args['wrapper_tag'], $args['wrapper_class'] );

		if ( ! empty( $args['filter_title'] ) ) {
			$output .= enlightenment_open_tag( $args['filter_title_tag'], $args['filter_title_class'] );
			$output .= esc_html( $args['filter_title'] );
			$output .= enlightenment_close_tag( $args['filter_title_tag'] );
		}

		$output .= enlightenment_open_tag( $args['filter_tag'], $args['filter_class'] );

		$link      = get_post_type_archive_link( 'jetpack-portfolio' );
		$link_html = apply_filters( 'enlightenment_project_types_filter_link_html', sprintf(
			'<a%1$s href="%2$s" rel="%3$s" data-filter="%4$s">%5$s</a>',
			$link_class,
			$link,
			'jetpack-portfolio-type',
			'jetpack-portfolio',
			__( 'All Projects', 'enlightenment' )
		), false, $link, $current_url );

		$output .= enlightenment_open_tag( $args['term_tag'], $args['term_class'] . ( is_post_type_archive( 'jetpack-portfolio' ) ? ' ' . $args['current_term_class'] : '' ) );
		$output .= $link_html;
		$output .= enlightenment_close_tag( $args['term_tag'] );

		foreach ( $terms as $term ) {
			$class = $args['term_class'];
			$link  = get_term_link( $term, $term->taxonomy );

			if ( $link == $current_url ) {
				$class .= ' ' . $args['current_term_class'];
			}

			$link_html = apply_filters( 'enlightenment_project_types_filter_link_html', sprintf(
				'<a%1$s href="%2$s" rel="%3$s" data-filter="%4$s">%5$s</a>',
				$link_class,
				$link,
				$term->taxonomy,
				$term->taxonomy . '-' . $term->slug,
				$term->name
			), $term, $link, $current_url );

			$output .= enlightenment_open_tag( $args['term_tag'], $class );
			$output .= $link_html;
			$output .= enlightenment_close_tag( $args['term_tag'] );
		}

		$output .= enlightenment_close_tag( $args['filter_tag'] );

		$output .= enlightenment_close_tag( $args['wrapper_tag'] );
		$output .= enlightenment_close_tag( $args['container'] );
	}

	$output = apply_filters( 'enlightenment_project_types_filter', $output );

	if ( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_project_nav_args( $args ) {
	if ( 'jetpack-portfolio' != get_post_type() ) {
        return $args;
    }

	$args['prev_format'] = str_replace( '%4$s', '[project_type]', $args['prev_format'] );
	$args['next_format'] = str_replace( '%4$s', '[project_type]', $args['next_format'] );

	return $args;
}
add_filter( 'enlightenment_post_nav_args', 'enlightenment_project_nav_args' );

function enlightenment_project_nav_link( $output, $format, $link, $post ) {
	if ( 'jetpack-portfolio' != get_post_type() ) {
		return $output;
	}

	$terms = get_the_terms( $post, 'jetpack-portfolio-type' );

	if ( empty( $terms ) ) {
		return str_replace( '[project_type]', '', $output );
	}

    $args = array(
        'project_type_tag'   => '',
        'project_type_class' => '',
    );
    $args = apply_filters( 'enlightenment_project_nav_link_args', $args );

    $project_type  = enlightenment_open_tag( $args['project_type_tag'], $args['project_type_class'] );
	$project_type .= $terms[0]->name;
	$project_type .= enlightenment_close_tag( $args['project_type_tag'] );

    $output = str_replace( '[project_type]', $project_type, $output );

	return $output;
}
add_filter( 'previous_post_link', 'enlightenment_project_nav_link', 10, 4 );
add_filter( 'next_post_link', 'enlightenment_project_nav_link', 10, 4 );

function enlightenment_portfolio_nav_args( $args ) {
	if ( ! is_post_type_archive( 'jetpack-portfolio' ) && ! is_tax( 'jetpack-portfolio-type' ) && ! is_tax( 'jetpack-portfolio-tag' ) ) {
		return $args;
	}

	$args['prev_text']          = __( 'Older projects', 'enlightenment' );
	$args['next_text']          = __( 'Newer projects', 'enlightenment' );
	$args['screen_reader_text'] = __( 'Projects navigation', 'enlightenment' );
	$args['aria_label']         = __( 'Projects', 'enlightenment' );

	return $args;
}
add_filter( 'enlightenment_posts_nav_args', 'enlightenment_portfolio_nav_args' );

function enlightenment_portfolio_archive_grid( $grids ) {
	if( current_theme_supports( 'enlightenment-bootstrap' ) ) {
		$grid = array(
			'smartphone-portrait'  => 'onecol',
			'smartphone-landscape' => 'inherit',
			'tablet-portrait'      => 'twocol',
			'tablet-landscape'     => 'threecol',
			'desktop-laptop'       => 'inherit',
		);
	} else {
		$grid = 'threecol';
	}

	$grids['jetpack-portfolio'] = array(
		'grid'       => $grid,
		'lead_posts' => 0,
	);
	$grids['jetpack-portfolio-type'] = array(
		'grid'       => $grid,
		'lead_posts' => 0,
	);
	$grids['jetpack-portfolio-tag'] = array(
		'grid'       => $grid,
		'lead_posts' => 0,
	);

	return $grids;
}
add_filter( 'enlightenment_archive_grids', 'enlightenment_portfolio_archive_grid' );

function enlightenment_portfolio_content_hooks( $hooks ) {
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_project_types_filter';

	return $hooks;
}
add_filter( 'enlightenment_page_content_hooks', 'enlightenment_portfolio_content_hooks' );

function enlightenment_portfolio_template_functions( $functions ) {
	$functions['enlightenment_project_types_filter'] = __( 'Project Types Filter', 'enlightenment' );

	return $functions;
}
add_filter( 'enlightenment_template_functions', 'enlightenment_portfolio_template_functions' );

function enlightenment_portfolio_actions() {
	if ( ! is_post_type_archive( 'jetpack-portfolio' ) && ! is_tax( 'jetpack-portfolio-type' ) ) {
		return;
	}

	add_action( 'enlightenment_before_page_content', 'enlightenment_project_types_filter' );
}
add_action( 'wp', 'enlightenment_portfolio_actions', 7 );

function enlightenment_project_actions() {
	if ( 'jetpack-portfolio' != get_post_type() ) {
		return;
	}

	if ( is_singular() ) {
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );

		add_action( 'enlightenment_entry_footer', 'enlightenment_entry_meta' );
	} else {
		remove_action( 'enlightenment_entry_header', 'enlightenment_post_thumbnail' );
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );

		remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );
		remove_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );

		add_action( 'enlightenment_entry_header', 'enlightenment_post_thumbnail' );
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );

		add_action( 'enlightenment_entry_content', 'enlightenment_post_excerpt' );
	}
}
add_action( 'enlightenment_before_entry', 'enlightenment_project_actions', 5 );

function enlightenment_project_teaser_actions() {
	if ( ! current_theme_supports( 'enlightenment-grid-loop' ) ) {
		return;
	}

	if ( enlightenment_is_lead_post() ) {
		return;
	}

	if ( ! is_post_type_archive( 'jetpack-portfolio' ) && ! is_tax( 'jetpack-portfolio-type' ) && ! is_tax( 'jetpack-portfolio-tag' ) ) {
		return;
	}

	enightenment_clear_entry_hooks();

	add_action( 'enlightenment_entry_header', 'enlightenment_post_thumbnail' );
	add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
	add_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
}
add_action( 'enlightenment_before_entry', 'enlightenment_project_teaser_actions', 7 );

function enlightenment_custom_query_widget_project_meta_args( $args ) {
	if ( 'jetpack-portfolio' == get_post_type() ) {
		$args['format'] = enlightenment_project_types( array( 'echo' => false ) );
	}

	return $args;
}
add_filter( 'enlightenment_custom_query_widget_entry_meta_args', 'enlightenment_custom_query_widget_project_meta_args' );

function enlightenment_bootstrap_jetpack_portfolio_shortcode_tag( $output, $atts ) {
	if ( ! current_theme_supports( 'enlightenment-bootstrap' ) ) {
		return $output;
	}

	if ( ! is_array( $atts ) ) {
		$atts = array();
	}

	$columns = isset( $atts['columns'] ) ? absint( $atts['columns'] ) : 2;

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
		sprintf( '<div class="jetpack-portfolio-shortcode column-%d">', $columns ),
		sprintf( '<div class="jetpack-portfolio-shortcode column-%d">%s<div class="row">', $columns, "\n" ),
		$output
	);

	$output = str_replace(
		sprintf( '<div class="portfolio-entry portfolio-entry-column-%d ', $columns ),
		sprintf( '<div class="col-md%s">%s<div class="portfolio-entry ', $colspan, "\n" ),
		$output
	);

	$output = str_replace(
		sprintf( '<div class="portfolio-entry portfolio-entry-column-%d"', $columns ),
		sprintf( '<div class="col-md%s">%s<div class="portfolio-entry"', $colspan, "\n" ),
		$output
	);

	$output = str_replace(
		'</div><!-- close .portfolio-entry -->',
		'</div>' . "\n" . '</div><!-- close .portfolio-entry -->',
		$output
	);

	$output = str_replace(
		'</div><!-- close .jetpack-portfolio -->',
		'</div>' . "\n" . '</div><!-- close .jetpack-portfolio -->',
		$output
	);

	$output = str_replace( ' portfolio-entry-mobile-first-item-row', '', $output );
	$output = str_replace( ' portfolio-entry-mobile-last-item-row',  '', $output );
	$output = str_replace( ' portfolio-entry-first-item-row',        '', $output );
	$output = str_replace( ' portfolio-entry-last-item-row',         '', $output );

	return $output;
}
add_filter( 'enlightenment_filter_shortcode_tag_portfolio', 'enlightenment_bootstrap_jetpack_portfolio_shortcode_tag', 10, 2 );
add_filter( 'enlightenment_filter_shortcode_tag_jetpack_portfolio', 'enlightenment_bootstrap_jetpack_portfolio_shortcode_tag', 10, 2 );
