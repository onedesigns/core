<?php
/**
 * Customizer functions for Template Editor.
 *
 * @package Enlightenment_Framework
 */

function enlightenment_current_template_hooks( $args ) {
	global $wp_filter;

	$settings = get_theme_mod( 'template_hooks' );
	$template = enlightenment_current_query();//substr( $this->id, 15, strpos( $this->id, ']' ) - 15 );
	$hooks    = enlightenment_template_hooks();

	$args['template_editor'] = array(
		'template'  => enlightenment_current_query(),
		'hooks'     => array(),
	);

	foreach ( $hooks as $hook => $atts ) {
		if ( isset( $settings[ $template ][ $hook ] ) ) {
			$functions = $settings[ $template ][ $hook ];
		} else {
			$functions = array();

			if ( isset( $wp_filter[ $hook ] ) && isset( $wp_filter[ $hook ][10] ) ) {
				foreach( $wp_filter[ $hook ][10] as $function ) {
					$functions[] = $function['function'];
				}
			}
		}

		$args['template_editor']['hooks'][ $hook ] = $functions;
	}

	return $args;
}

function enlightenment_hook_current_template_hooks() {
	add_filter( 'enlightenment_customize_preview_args', 'enlightenment_current_template_hooks' );
}
add_action( 'template_redirect', 'enlightenment_hook_current_template_hooks' );

/*function enlightenment_is_active_template( $control ) {
	$template = substr( $control->id, 15, strpos( $control->id, ']' ) - 15 );

	return enlightenment_current_query() == $template;
}*/

function enlightenment_customizer_templates() {
	$templates = enlightenment_templates();

	$posts = get_posts( array(
		'posts_per_page' => 1,
	) );

	foreach ( $templates as $template => $atts ) {
		switch ( $template ) {
			case 'error404':
				$templates[ $template ]['url'] = add_query_arg( 'page_id', -1, home_url( '/' ) );

				break;

			case 'search':
				$templates[ $template ]['url'] = add_query_arg( 's', '', home_url( '/' ) );

				break;

			case 'blog':
				$templates[ $template ]['url'] = 'page' == get_option( 'show_on_front' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );

				break;

			case 'post':
			case 'comments':
				$templates[ $template ]['url'] = count( $posts ) ? get_permalink( $posts[0] ) : '';

				break;

			case 'author':
				$templates[ $template ]['url'] = count( $posts ) ? get_author_posts_url( $posts[0]->post_author ) : '';

				break;

			case 'date':
				$templates[ $template ]['url'] = count( $posts ) ? get_year_link( date( 'Y', strtotime( $posts[0]->post_date ) ) ) : '';

				break;

			case 'page':
				$pages = get_posts( array(
					'posts_per_page' => 1,
					'post_type'      => 'page',
				) );

				$templates[ $template ]['url'] = count( $pages ) ? get_permalink( $pages[0] ) : '';

				break;

			case 'attachment':
				$media = get_posts( array(
					'posts_per_page' => 1,
					'post_type'      => 'attachment',
					'post_status'    => 'any',
				) );

				$templates[ $template ]['url'] = count( $media ) ? get_permalink( $media[0] ) : '';

				break;

			case 'category':
				$cats = get_categories( array(
					'number' => 1,
				) );

				$templates[ $template ]['url'] = count( $cats ) ? get_category_link( $cats[0] ) : '';

				break;

			case 'post_tag':
				$tags = get_tags( array(
					'number' => 1,
				) );

				$templates[ $template ]['url'] = count( $tags ) ? get_tag_link( $tags[0] ) : '';

				break;

			default:
				$templates[ $template ]['url'] = '';
		}
	}

	$post_types = get_post_types( array( 'has_archive' => true ) );

	foreach( $post_types as $name => $post_type ) {
		if ( ! isset( $templates[ $name . '-archive' ] ) ) {
			continue;
		}

		$templates[ $name . '-archive' ]['url'] = get_post_type_archive_link( $name );
	}

	$post_types = get_post_types( array( 'publicly_queryable' => true ) );

	unset( $post_types['post'] );
	unset( $post_types['attachment'] );
	unset( $post_types['e-landing-page'] );
	unset( $post_types['e-floating-buttons'] );

	foreach ( $post_types as $name => $post_type ) {
		if ( ! isset( $templates[ $name ] ) ) {
			continue;
		}

		$posts = get_posts( array(
			'posts_per_page' => 1,
			'post_type'      => $name,
		) );

		if ( count( $posts ) ) {
			$templates[ $name ]['url'] = get_permalink( $posts[0] );
		}
	}

	$taxonomies = get_taxonomies( array( 'public' => true ) );

	unset( $taxonomies['category'] );
	unset( $taxonomies['post_tag'] );
	unset( $taxonomies['post_format'] );
	unset( $taxonomies['product_shipping_class'] );

	foreach( $taxonomies as $name => $taxonomy ) {
		if ( ! isset( $templates[ $name ] ) ) {
			continue;
		}

		$terms = array_values( get_terms( array(
			'taxonomy' => $name,
			'number'   => 1,
		) ) );

		if ( count( $terms ) ) {
			$templates[ $name ]['url'] = get_term_link( array_shift( $terms ) );
		}
	}

	return apply_filters( 'enlightenment_customizer_templates', $templates );
}

function enlightenment_template_editor_simulate_query( $template ) {
	global $wp_query, $wp_filter, $enlightenment_wp_filter, $post, $enlightenment_post;

	$enlightenment_wp_filter = $wp_filter;

	switch ( $template ) {
		case 'error404':
			$wp_query->is_404 = true;

			break;

		case 'search':
			$wp_query->is_search  = true;
			$wp_query->is_archive = true;

			break;

		case 'blog':
			$wp_query->is_home    = true;
			$wp_query->is_archive = true;

			break;

		case 'post':
			$posts = get_posts( array(
				'post_type' => $template,
				'posts_per_page' => 1,
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key'     => '_enlightenment_page_builder',
						'value'   => '',
						'compare' => '=',
					),
					array(
						'key'     => '_enlightenment_page_builder',
						'value'   => 'bug #23268',
						'compare' => 'NOT EXISTS',
					),
				),
			) );

			$wp_query = new WP_Query( array(
				'p'         => $posts[0]->ID,
				'post_type' => $template,
			) );

			break;

		case 'page':
			$posts = get_pages( array(
				'post_type'  => $template,
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key'     => '_enlightenment_page_builder',
						'value'   => '',
						'compare' => '=',
					),
					array(
						'key'     => '_enlightenment_page_builder',
						'value'   => 'bug #23268',
						'compare' => 'NOT EXISTS',
					),
				),
			) );

			$wp_query = new WP_Query( array(
				'page_id' => $posts[0]->ID,
			) );

			break;

		case 'author':
			$wp_query->is_author  = true;
			$wp_query->is_archive = true;
			$wp_query->is_home    = false;

			break;

		case 'date':
			$wp_query->is_date    = true;
			$wp_query->is_archive = true;
			$wp_query->is_home    = false;
			$wp_query->is_author  = false;

			break;

		case 'category':
			$terms = get_terms( 'category' );

			if ( count( $terms ) ) {
				$wp_query = new WP_Query( array(
					'category_name' => array_shift( $terms )->slug,
				) );
			}

			break;

		case 'post_tag':
			$terms = get_terms( 'post_tag' );

			if ( count( $terms ) ) {
				$wp_query = new WP_Query( array(
					'tag' => array_shift( $terms )->slug,
				) );
			}

			break;

		case 'comments':
			$wp_query->is_singular = true;
			$wp_query->is_page     = true;
			$wp_query->is_single   = true;

			break;

		default:
			$atts = enlightenment_get_template( $template );

			switch ( $atts['type'] ) {
				case 'post_type_archive':
					$wp_query->is_archive           = true;
					$wp_query->is_post_type_archive = true;
					$wp_query->is_home              = false;
					$wp_query->is_author            = false;

					$wp_query->query_vars['post_type'] = str_replace( '-archive', '', $template );

					break;

				case 'post_type':
					$posts = get_posts( array(
						'post_type'      => $template,
						'posts_per_page' => 1,
						'meta_query' => array(
							'relation' => 'OR',
							array(
								'key'     => '_enlightenment_page_builder',
								'value'   => '',
								'compare' => '=',
							),
							array(
								'key'     => '_enlightenment_page_builder',
								'value'   => 'bug #23268',
								'compare' => 'NOT EXISTS',
							),
						),
					) );

					if ( count( $posts ) ) {
						$the_post = array_shift( $posts );

						$wp_query = new WP_Query( array(
							'p'         => $the_post->ID,
							'post_type' => $template,
						) );

						$enlightenment_post = isset( $post ) ? $post : null;

						$post = $the_post;
					}

					break;

				case 'taxonomy':
					$terms = get_terms( $template );

					if ( count( $terms ) ) {
						$wp_query = new WP_Query( array(
							$template => array_shift( $terms )->slug,
						) );
					}

					break;

				default:
					do_action( 'enlightenment_template_editor_simulate_query', $template, $atts );
			}
	}

	add_filter( 'enlightenment_is_lead_post', '__return_true' );

	do_action_ref_array( 'wp', array( &$wp_query ) );
}

function enlightenment_template_editor_reset_query( $template ) {
	global $wp_filter, $enlightenment_wp_filter, $post, $enlightenment_post;

	if ( ! empty( $enlightenment_wp_filter ) ) {
		$wp_filter = $enlightenment_wp_filter;
	}

	remove_filter( 'enlightenment_is_lead_post', '__return_true' );

	wp_reset_query();

	$post = isset( $enlightenment_post ) ? $enlightenment_post : null;

	do_action( 'enlightenment_template_editor_reset_query', $template );
}

/**
 * Customizer settings, sections and controls.
 */
function enlightenment_template_editor_customize_register( $wp_customize ) {
	global $wp_filter;

	$type      = 'theme_mod';
	$cap       = 'edit_theme_options';
	$supports  = 'enlightenment-template-editor';
	$templates = enlightenment_customizer_templates();
	$hooks     = enlightenment_template_hooks();
	$functions = enlightenment_template_functions();
	$settings  = get_theme_mod( 'template_hooks' );

	if ( ! is_array( $settings ) ) {
		$settings = array();
	}

	$wp_customize->add_panel( 'template-editor', array(
		'title'          => __( 'Templates', 'enlightenment' ),
		'description'    => '',
		'priority'       => 112,
		'theme_supports' => $supports,
	) );

	foreach ( $templates as $template => $atts ) {
		if ( 'comments' == $template ) {
			continue;
		}

		$wp_customize->add_section( sprintf( 'edit-template-%s', $template ), array(
			'panel'       => 'template-editor',
			'title'       => $atts['name'],
			'description' => sprintf( '<p>%s</p>', sprintf( __( 'You are customizing the %s template.', 'enlightenment' ), sprintf( '<strong>%s</strong>', $atts['name'] ) ) ),
			'priority'    => 112,
		) );

		if ( ! isset( $settings[ $template ] ) ) {
			$settings[ $template ] = array();
		}

		$simulate_query = false;

		if ( empty( $settings[ $template ] ) ) {
			$simulate_query = true;
		} else {
			foreach ( $atts['hooks'] as $hook ) {
				if ( ! isset( $settings[ $template ][ $hook ] ) ) {
					$simulate_query = true;

					break;
				}
			}
		}

		if ( $simulate_query ) {
			enlightenment_template_editor_simulate_query( $template );

			foreach ( $atts['hooks'] as $hook ) {
				if ( isset( $settings[ $template ][ $hook ] ) ) {
					continue;
				}

				$settings[ $template ][ $hook ] = array();

				if ( isset( $wp_filter[ $hook ] ) && isset( $wp_filter[ $hook ][10] ) ) {
					foreach( $wp_filter[ $hook ][10] as $callback ) {
						$settings[ $template ][ $hook ][] = $callback['function'];
					}
				}
			}
		}

		foreach ( $atts['hooks'] as $hook ) {
			if ( ! empty( $hooks[ $hook ]['functions'] ) ) {
				$choices = array();

				foreach ( $hooks[ $hook ]['functions'] as $function ) {
					$choices[ $function ] = $functions[ $function ];
				}

				/*$wp_customize->add_section( sprintf( 'template-%s-hook-%s', $template, $hook ), array(
					'panel'          => sprintf( 'edit-template-%s', $template ),
					'title'          => $hooks[ $hook ]['name'],
					'description'    => '',
					'theme_supports' => $supports,
				) );*/

				$wp_customize->add_setting( sprintf( 'template_hooks[%s][%s]', $template, $hook ), array(
					'type'              => $type,
					'capability'        => $cap,
					'theme_supports'    => $supports,
					'default'           => $settings[ $template ][ $hook ],
					'transport'         => 'postMessage',
					'sanitize_callback' => 'enlightenment_sanitize_template_hook',
				) );

				$wp_customize->add_control( new Enlightenment_Customize_Template_Hook_Control( $wp_customize, sprintf( 'template_hooks[%s][%s]', $template, $hook ), array(
					'section'         => sprintf( 'edit-template-%s', $template ),//sprintf( 'template-%s-hook-%s', $template, $hook ),
					'label'           => $hooks[ $hook ]['name'],
					'description'     => '',
					'choices'         => $choices,
					// 'active_callback' => 'enlightenment_is_active_template',
				) ) );
			}
		}

		if ( $simulate_query ) {
			enlightenment_template_editor_reset_query( $template );
		}
	}
}
add_action( 'customize_register', 'enlightenment_template_editor_customize_register' );

function enlightenment_template_editor_customize_controls_scripts() {
	wp_enqueue_script( 'enlightenment-customize-controls' );
}
add_action( 'customize_controls_enqueue_scripts', 'enlightenment_template_editor_customize_controls_scripts' );

function enlightenment_template_editor_customize_controls_args( $args ) {
	$args['template_editor'] = array(
		'templates' => enlightenment_customizer_templates(),
	);

	return $args;
}
add_filter( 'enlightenment_customize_controls_args', 'enlightenment_template_editor_customize_controls_args' );
