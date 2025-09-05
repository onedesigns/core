<?php

// register Enlightenment_Custom_Query widget
function enlightenment_custom_query_register_widget() {
	register_widget( 'Enlightenment_Custom_Query' );
}
add_action( 'widgets_init', 'enlightenment_custom_query_register_widget' );

function enlightenment_enqueue_flexslider_scripts() {
	if ( ! is_active_widget( false, false, 'enlightenment-custom-query' ) ) {
		return;
	}


	global $wp_registered_widgets;

	$sidebars_widgets = wp_get_sidebars_widgets();
	if ( is_array( $sidebars_widgets ) ) {
		foreach ( $sidebars_widgets as $sidebar => $widgets ) {
			if ( 'wp_inactive_widgets' === $sidebar || 'orphaned_widgets' === substr( $sidebar, 0, 16 ) ) {
				continue;
			}

			if ( is_array( $widgets ) ) {
				foreach ( $widgets as $widget ) {
					if (
						isset( $wp_registered_widgets[ $widget ]['callback'] )
						&&
						is_array( $wp_registered_widgets[ $widget ]['callback'] )
						&&
						$wp_registered_widgets[ $widget ]['callback'][0] instanceof Enlightenment_Custom_Query
					) {
						$instances = $wp_registered_widgets[ $widget ]['callback'][0]->get_settings();
						$number    = substr( $widget, 27, strlen( $widget ) - 27 );

						if ( isset( $instances[ $number ] ) ) {
							switch ( $instances[ $number ]['type'] ) {
								case 'slider':
									$args[] = array(
										'selector'       => sprintf( '#%s .custom-query-has-type-slider', $widget ),
										'controlNav'     => false,
										'fadeFirstSlide' => false,
									);

									break;

								case 'carousel':
									$columns = '';

									if ( current_theme_supports( 'enlightenment-grid-loop' ) ) {
										if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
											$columns = array();

											foreach ( $instances[ $number ]['grid'] as $breakpoint => $grid ) {
												if ( 'inherit' == $grid ) {
													continue;
												}

												$breakpoint = str_replace( '-', '_', $breakpoint );
												$atts       = enlightenment_get_grid( $grid );

												$columns[ $breakpoint ] = $atts['content_columns'];
											}
										} else {
											$atts    = enlightenment_get_grid( $instances[ $number ]['grid'] );
											$columns = $atts['content_columns'];
										}
									}

									$args[] = array(
										'selector'      => sprintf( '#%s .custom-query-has-type-carousel', $widget ),
										'columns'       => $columns,
										'bootstrap'     => current_theme_supports( 'enlightenment-bootstrap' ),
										'controlNav'    => false,
										'animation'     => 'slide',
										'animationLoop' => false,
										'slideshow'     => false,
										'itemWidth'     => 150,
										'itemMargin'    => 8,
										'minItems'      => 3,
										'maxItems'      => 5,
										'move'          => 1,
									);

									break;
							}
						}
					}
				}
			}
		}
	}

	if ( ! empty( $args ) ) {
		wp_enqueue_style( 'flexslider' );
		wp_enqueue_script( 'flexslider' );

		wp_localize_script( 'flexslider', 'enlightenment_custom_query_flexslider_args', array_values( $args ) );
	}
}
add_action( 'wp_enqueue_scripts', 'enlightenment_enqueue_flexslider_scripts' );

function enlightenment_custom_queries_scripts( $hook ) {
	if ( 'widgets.php' != $hook ) {
		return;
	}

	wp_enqueue_style( 'enlightenment-custom-queries', enlightenment_styles_directory_uri() . '/custom-queries.css', array( 'enlightenment-admin-form-controls' ) );

	wp_enqueue_script( 'enlightenment-custom-queries', enlightenment_scripts_directory_uri() . '/custom-queries.js', array( 'enlightenment-admin-form-controls' ) );
}
add_action( 'admin_enqueue_scripts', 'enlightenment_custom_queries_scripts' );

function enlightenment_custom_query_add_post_thumbnail_sizes() {
	$size = apply_filters( 'enlightenment_custom_query_small_thumb_size', 75 );

	add_image_size( 'enlightenment-custom-query-small-thumb', $size, $size, 1 );
}
add_action( 'init', 'enlightenment_custom_query_add_post_thumbnail_sizes' );

function enlightenment_ajax_get_post_types() {
	$options = array();
	$posts   = get_posts( array(
		'post_type'      => esc_attr( $_POST['post_type'] ),
		'posts_per_page' => -1,
	) );

	foreach ( $posts as $post ) {
		$options[ $post->ID ] = get_the_title( $post->ID );
	}

	echo enlightenment_open_tag( 'p', 'post-type show' );

	enlightenment_select_box( array(
		'label'   => __( 'Post:', 'enlightenment' ),
		'name'    => $_POST['name'],
		'class'   => 'widefat',
		'id'      => $_POST['id'],
		'options' => $options,
		'value'   => $_POST['value'],
	) );

	echo enlightenment_close_tag( 'p' );

	die();
}
add_action( 'wp_ajax_enlightenment_ajax_get_post_types', 'enlightenment_ajax_get_post_types' );

function enlightenment_ajax_get_terms() {
	$options = array();
	$terms   = get_terms( esc_attr( $_POST['taxonomy'] ) );

	foreach ( $terms as $term ) {
		$options[ $term->slug ] = $term->name;
	}

	echo enlightenment_open_tag( 'p', 'term show' );

	enlightenment_select_box( array(
		'label'   => __( 'Term:', 'enlightenment' ),
		'name'    => $_POST['name'],
		'class'   => 'widefat',
		'id'      => $_POST['id'],
		'options' => $options,
		'value'   => $_POST['value'],
	) );

	echo enlightenment_close_tag( 'p' );

	die();
}
add_action( 'wp_ajax_enlightenment_ajax_get_terms', 'enlightenment_ajax_get_terms' );

function enlightenment_custom_query_widget_hooks( $query_name ) {
	if ( 0 !== strpos( $query_name, 'custom_query_widget_' ) ) {
		return;
	}

	global $enlightenment_custom_widget_instance, $enlightenment_custom_lead_posts;

	$instance   = $enlightenment_custom_widget_instance;
	$lead_posts = $enlightenment_custom_lead_posts;

	if ( 'post_type' == $instance['query'] || 'page' == $instance['query'] ) {
		$lead_posts = 1;
	}

	if (
		current_theme_supports( 'enlightenment-bootstrap' )
		&&
		current_theme_supports( 'enlightenment-grid-loop' )
		&&
		'slider' != $instance['type']
		&&
		'carousel' != $instance['type']
		&&
		'post_type' != $instance['query']
		&&
		'page' != $instance['query']
	) {
		add_action( 'enlightenment_custom_before_entries_list', 'enlightenment_open_row' );
		add_action( 'enlightenment_custom_after_entries_list',  'enlightenment_close_row' );
	}

	if (
		current_theme_supports( 'enlightenment-grid-loop' )
		&&
		current_theme_supports( 'enlightenment-bootstrap' )
		&&
		'slider' != $instance['type']
		&&
		'carousel' != $instance['type']
		&&
		'post_type' != $instance['query']
		&&
		'page' != $instance['query']
	) {
		add_action( 'enlightenment_custom_before_entry', 'enlightenment_custom_query_widget_entry_container' );
	}

	add_filter( 'enlightenment_custom_post_class', 'enlightenment_custom_query_widget_post_class' );

	for ( $i = 1; $i <= $lead_posts; $i++ ) {
		add_filter( 'enlightenment_custom_post_class-count-' . $i, 'enlightenment_custom_query_widget_lead_post_class' );
	}

	for ( $i; $i <= $instance['posts_per_page']; $i++ ) {
		remove_filter( 'enlightenment_custom_post_class-count-' . $i, 'enlightenment_custom_query_widget_lead_post_class' );
	}

	if ( 'gallery' == $instance['query'] ) {
		add_filter( 'enlightenment_custom_query_widget_image_size', 'enlightenment_custom_query_post_thumbnail_size' );
		add_action( 'enlightenment_custom_entry_header', 'enlightenment_custom_query_widget_image_link' );
	} else {
		if ( 'custom_query_widget_list' == $query_name ) {
			if ( 'page' != $instance['query'] && 'pages' != $instance['query'] ) {
				add_filter( 'post_thumbnail_size', 'enlightenment_custom_query_post_thumbnail_size' );

				add_action( 'enlightenment_custom_entry_header', 'enlightenment_hook_custom_entry_title_args_filter', 8 );
				add_action( 'enlightenment_custom_entry_header', 'enlightenment_hook_custom_entry_meta_args_filter', 8 );
				add_action( 'enlightenment_custom_entry_header', 'enlightenment_post_thumbnail' );
				add_action( 'enlightenment_custom_entry_header', 'enlightenment_entry_title' );
				add_action( 'enlightenment_custom_entry_header', 'enlightenment_unhook_custom_entry_title_args_filter', 12 );
				add_action( 'enlightenment_custom_entry_header', 'enlightenment_unhook_custom_entry_meta_args_filter', 12 );
			}

			add_action( 'enlightenment_custom_before_entry', 'enlightenment_custom_query_widget_add_meta' );

			if ( 'post_type' == $instance['query'] || 'page' == $instance['query'] || 'pages' == $instance['query'] ) {
				add_action( 'enlightenment_custom_entry_content', 'enlightenment_post_content' );
			} else {
				add_action( 'enlightenment_custom_entry_content', 'enlightenment_post_excerpt' );
			}

			add_action( 'enlightenment_custom_before_entry', 'enlightenment_custom_query_widget_content_switcher' );
		} elseif ( 'custom_query_widget_slider' == $query_name ) {
			add_action( 'enlightenment_custom_before_entry_header', 'enlightenment_custom_query_widget_open_slide_container' );

			if ( 'page' != $instance['query'] && 'pages' != $instance['query'] ) {
				add_action( 'enlightenment_custom_entry_header', 'enlightenment_entry_title' );
			}

			if ( 'post_type' == $instance['query'] || 'page' == $instance['query'] || 'pages' == $instance['query'] ) {
				add_action( 'enlightenment_custom_entry_content', 'enlightenment_post_content' );
			}

			add_action( 'enlightenment_custom_after_entry_content', 'enlightenment_close_div' );
		} elseif ( 'custom_query_widget_carousel' == $query_name ) {
			add_action( 'enlightenment_custom_entry_header', 'enlightenment_post_thumbnail' );
		} elseif ( 'custom_query_widget_gallery' == $query_name ) {
			add_action( 'enlightenment_custom_entry_header', 'enlightenment_post_thumbnail' );
		}
	}

	if (
		current_theme_supports( 'enlightenment-grid-loop' )
		&&
		current_theme_supports( 'enlightenment-bootstrap' )
		&&
		'slider' != $instance['type']
		&&
		'carousel' != $instance['type']
		&&
		'post_type' != $instance['query']
		&&
		'page' != $instance['query']
	) {
		add_action( 'enlightenment_custom_after_entry', 'enlightenment_close_div' );
	}
}
add_action( 'enlightenment_before_custom_loop', 'enlightenment_custom_query_widget_hooks' );

function enlightenment_hook_custom_entry_title_args_filter() {
	add_filter( 'enlightenment_entry_title_args', 'enlightenment_custom_query_widget_entry_title_args' );
}

function enlightenment_unhook_custom_entry_title_args_filter() {
	remove_filter( 'enlightenment_entry_title_args', 'enlightenment_custom_query_widget_entry_title_args' );
}

function enlightenment_hook_custom_entry_meta_args_filter() {
	add_filter( 'enlightenment_entry_meta_args', 'enlightenment_custom_query_widget_entry_meta_args' );
}

function enlightenment_unhook_custom_entry_meta_args_filter() {
	remove_filter( 'enlightenment_entry_meta_args', 'enlightenment_custom_query_widget_entry_meta_args' );
}

function enlightenment_custom_query_widget_entry_container() {
	if ( ! current_theme_supports( 'enlightenment-grid-loop' ) ) {
		return;
	}

	if ( ! current_theme_supports( 'enlightenment-bootstrap' ) ) {
		return;
	}

	global $enlightenment_custom_widget_instance, $enlightenment_custom_grid, $enlightenment_custom_post_counter;

	$instance = $enlightenment_custom_widget_instance;
	$counter  = $enlightenment_custom_post_counter;

	if ( 'slider' == $instance['type'] ) {
		return;
	}

	if ( 'carousel' == $instance['type'] ) {
		return;
	}

	if ( 'post_type' == $instance['query'] ) {
		return;
	}

	if ( 'page' == $instance['query'] ) {
		return;
	}

	$class = '';

	if ( $counter <= enlightenment_custom_query_widget_lead_posts() ) {
		$atts = enlightenment_get_grid( 'onecol' );

		if ( ! empty( $atts['entry_class'] ) ) {
			$prefix = enlightenment_bootstrap_get_breakpoint_prefix( 'smartphone-portrait' );
			$class .= ' ' . sprintf( $atts['entry_class'], $prefix );
		}
	} else {
		foreach ( $enlightenment_custom_grid as $breakpoint => $grid ) {
			if ( 'inherit' == $grid ) {
				continue;
			}

			$atts = enlightenment_get_grid( $grid );

			if ( ! empty( $atts['entry_class'] ) ) {
				$prefix = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );
				$class .= ' ' . sprintf( $atts['entry_class'], $prefix );
			}
		}
	}

	$class = trim( $class );

	echo enlightenment_open_tag( 'div', $class );
}

function enlightenment_custom_query_remove_thumbnail_size() {
	remove_filter( 'post_thumbnail_size', 'enlightenment_custom_query_post_thumbnail_size' );
	remove_filter( 'enlightenment_custom_query_widget_image_size', 'enlightenment_custom_query_post_thumbnail_size' );
}
add_action( 'enlightenment_after_custom_loop', 'enlightenment_custom_query_remove_thumbnail_size' );

function enlightenment_custom_query_widget_add_meta() {
	global $post;

	if ( 'page' != $post->post_type ) {
		add_action( 'enlightenment_custom_entry_header', 'enlightenment_entry_meta' );
	}
}

function enlightenment_custom_query_widget_content_switcher() {
	global $enlightenment_custom_widget_instance, $enlightenment_custom_query_name, $enlightenment_custom_post_counter;

	if ( 'custom_query_widget_list' != $enlightenment_custom_query_name ) {
		return;
	}

	if ( $enlightenment_custom_post_counter <= enlightenment_custom_query_widget_lead_posts() ) {
		return;
	}

	if ( 'post_type' == $enlightenment_custom_widget_instance['query'] || 'pages' == $enlightenment_custom_widget_instance['query'] ) {
		remove_action( 'enlightenment_custom_entry_content', 'enlightenment_post_content' );

		if ( 'pages' == $enlightenment_custom_widget_instance['query'] ) {
			add_action( 'enlightenment_custom_entry_header', 'enlightenment_entry_title' );
		}

		add_action( 'enlightenment_custom_entry_content', 'enlightenment_post_excerpt' );
	} else {
		remove_action( 'enlightenment_custom_entry_content', 'enlightenment_post_excerpt' );
	}
}
