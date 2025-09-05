<?php

function enlightenment_grid_columns() {
	$columns = array(
		'onecol' => array(
			'name'             => __( '1 Column', 'enlightenment' ),
			'content_columns'  => 1,
			'body_class'       => '',
			'content_class'    => '',
			'entry_class'      => '',
			'full_width_class' => '',
			'image'            => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="currentColor" d="M0 0v29.168h100V0H0zm0 35.418v29.166h100V35.418H0zm0 35.416v29.168h100V70.834H0z"/></svg>',
		),
		'twocol' => array(
			'name'             => sprintf( __( '%d Columns', 'enlightenment' ), 2 ),
			'content_columns'  => 2,
			'body_class'       => 'grid-columns-2',
			'content_class'    => 'content-columns-2',
			'entry_class'      => '',
			'full_width_class' => '',
			'image'            => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="currentColor" d="M0 0v29.168h46.875V0H0zm53.125 0v29.168H100V0H53.125zM0 35.418v29.166h46.875V35.418H0zm53.125 0v29.166H100V35.418H53.125zM0 70.834v29.168h46.875V70.834H0zm53.125 0v29.168H100V70.834H53.125z"/></svg>',
		),
		'threecol' => array(
			'name'             => sprintf( __( '%d Columns', 'enlightenment' ), 3 ),
			'content_columns'  => 3,
			'body_class'       => 'grid-columns-3',
			'content_class'    => 'content-columns-3',
			'entry_class'      => '',
			'full_width_class' => '',
			'image'            => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="currentColor" d="M0 0v29.168h29.168V0H0zm35.418 0v29.168h29.166V0H35.418zm35.416 0v29.168h29.168V0H70.834zM0 35.418v29.166h29.168V35.418H0zm35.418 0v29.166h29.166V35.418H35.418zm35.416 0v29.166h29.168V35.418H70.834zM0 70.834v29.168h29.168V70.834H0zm35.418 0v29.168h29.166V70.834H35.418zm35.416 0v29.168h29.168V70.834H70.834z"/></svg>',
		),
		'fourcol' => array(
			'name'             => sprintf( __( '%d Columns', 'enlightenment' ), 4 ),
			'content_columns'  => 4,
			'body_class'       => 'grid-columns-4',
			'content_class'    => 'content-columns-4',
			'entry_class'      => '',
			'full_width_class' => '',
			'image'            => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="currentColor" d="M0 0v29.168h20.313V0H0zm26.563 0v29.168h20.312V0H26.562zm26.562 0v29.168h20.313V0H53.124zm26.563 0v29.168H100V0H79.687zM0 35.418v29.166h20.313V35.418H0zm26.563 0v29.166h20.312V35.418H26.562zm26.562 0v29.166h20.313V35.418H53.124zm26.563 0v29.166H100V35.418H79.687zM0 70.834v29.168h20.313V70.834H0zm26.563 0v29.168h20.312V70.834H26.562zm26.562 0v29.168h20.313V70.834H53.124zm26.563 0v29.168H100V70.834H79.687z"/></svg>',
		),
	);

	return apply_filters( 'enlightenment_grid_columns', $columns );
}

function enlightenment_archive_grids() {
	$grids = array(
		'search' => array(
			'grid'       => enlightenment_default_grid(),
			'lead_posts' => 0,
		),
		'post'   => array(
			'grid'       => enlightenment_default_grid(),
			'lead_posts' => 0,
		),
		'author' => array(
			'grid'       => enlightenment_default_grid(),
			'lead_posts' => 0,
		),
		'date'   => array(
			'grid'       => enlightenment_default_grid(),
			'lead_posts' => 0,
		),
	);

	$post_types = get_post_types( array( 'has_archive' => true ) );

	foreach ( $post_types as $post_type ) {
		$grids[ $post_type ] = array(
			'grid'       => enlightenment_default_grid(),
			'lead_posts' => 0,
		);
	}

	$taxonomies = get_taxonomies( array( 'public' => true ) );

	unset( $taxonomies['post_format'] );
	unset( $taxonomies['product_shipping_class'] );

	foreach ( $taxonomies as $taxonomy ) {
		$grids[ $taxonomy ] = array(
			'grid'       => enlightenment_default_grid(),
			'lead_posts' => 0,
		);
	}

	return apply_filters( 'enlightenment_archive_grids', $grids );
}

function enlightenment_default_grid() {
	return apply_filters( 'enlightenment_default_grid', 'onecol' );
}

function enlightenment_current_grid() {
	$grids = enlightenment_archive_grids();

	if ( is_home() && ! is_page() ) {
		$grid = $grids['post']['grid'];
	} elseif ( is_author() ) {
		$grid = $grids['author']['grid'];
	} elseif ( is_date() ) {
		$grid = $grids['date']['grid'];
	} elseif ( is_post_type_archive() ) {
		$grid = $grids[ get_query_var( 'post_type' ) ]['grid'];
	} elseif ( is_category() ) {
		$grid = $grids['category']['grid'];
	} elseif ( is_tag() ) {
		$grid = $grids['post_tag']['grid'];
	} elseif ( is_tax( 'post_format' ) ) {
		$grid = $grids['post']['grid'];
	} elseif ( is_tax() ) {
		$grid = $grids[ get_queried_object()->taxonomy ]['grid'];
	} elseif ( is_search() ) {
		$grid = $grids['search']['grid'];
	} else {
		$grid = enlightenment_default_grid();
	}

	return apply_filters( 'enlightenment_current_grid', $grid );
}

function enlightenment_current_lead_posts() {
	$lead_posts = apply_filters( 'enlightenment_pre_current_lead_posts', null );

	if ( null !== $lead_posts ) {
		return $lead_posts;
	}

	$grids = enlightenment_archive_grids();
	$grid  = enlightenment_get_grid( enlightenment_current_grid() );

	if ( 1 == $grid['content_columns'] ) {
		$lead_posts = get_option( 'posts_per_page' );
	} elseif ( is_home() && ! is_page() ) {
		$lead_posts = $grids['post']['lead_posts'];
	} elseif ( is_author() ) {
		$lead_posts = $grids['author']['lead_posts'];
	} elseif ( is_date() ) {
		$lead_posts = $grids['date']['lead_posts'];
	} elseif ( is_post_type_archive() ) {
		$lead_posts = $grids[ get_query_var( 'post_type' ) ]['lead_posts'];
	} elseif ( is_category() ) {
		$lead_posts = $grids['category']['lead_posts'];
	} elseif ( is_tag() ) {
		$lead_posts = $grids['post_tag']['lead_posts'];
	} elseif ( is_tax( 'post_format' ) ) {
		$lead_posts = $grids['post']['lead_posts'];
	} elseif ( is_tax() ) {
		$lead_posts = $grids[ get_queried_object()->taxonomy ]['lead_posts'];
	} elseif ( is_search() ) {
		$lead_posts = $grids['post']['lead_posts'];
	}

	return apply_filters( 'enlightenment_current_lead_posts', $lead_posts );
}

function enlightenment_get_grid( $grid ) {
	$grids = enlightenment_grid_columns();

	if ( isset( $grids[ $grid ] ) ) {
		return $grids[ $grid ];
	}

	return false;
}

function enlightenment_is_lead_post() {
	if ( is_admin() ) {
		$is_lead_post = false;
	} elseif ( is_singular() ) {
		$is_lead_post = true;
	} else {
		$is_lead_post = apply_filters( 'enlightenment_pre_is_lead_post', null );

		if ( null !== $is_lead_post ) {
			return $is_lead_post;
		}

		$grid = enlightenment_get_grid( enlightenment_current_grid() );

		if ( 1 == $grid['content_columns'] ) {
			$is_lead_post = true;
		} else {
			global $enlightenment_post_counter;

			if ( ! isset( $enlightenment_post_counter ) ) {
				_doing_it_wrong( __FUNCTION__, 'This function can only be called inside The Loop', '' );
				return;
			}

			$lead_posts   = enlightenment_current_lead_posts();
			$is_lead_post = ! is_paged() && $lead_posts >= $enlightenment_post_counter;
		}
	}

	return apply_filters( 'enlightenment_is_lead_post', $is_lead_post );
}
