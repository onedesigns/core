<?php

function enlightenment_add_template_actions() {
	if ( is_admin() ) {
		return;
	}

	$hooks = get_theme_mod( 'template_hooks' );
	$query = enlightenment_current_query();

	if ( current_theme_supports( 'enlightenment-page-builder' ) && is_singular() ) {
		global $post;

		$page_builder_hooks = get_post_meta( $post->ID, '_enlightenment_page_builder', true );

		if ( '' != $page_builder_hooks ) {
			$hooks[ $query ] = $page_builder_hooks;
		}
	}

	if ( isset( $hooks[ $query ] ) ) {
		foreach ( $hooks[ $query ] as $hook => $functions ) {
			global $wp_filter;

			if ( isset( $wp_filter[ $hook ][10] ) && ! empty( $wp_filter[ $hook ][10] ) ) {
				remove_all_actions( $hook, 10 );
			}

			if ( ! empty( $functions ) ) {
				if ( ! is_array( $functions ) ) {
					$functions = explode( ',', $functions );
				}

				foreach ( $functions as $function ) {
					add_action( $hook, $function, 10, apply_filters( 'enlightenment_template_actions_accepted_args', 2 ) );
				}
			}
		}
	}

	if ( is_singular() && isset( $hooks['comments'] ) ) {
		foreach( $hooks['comments'] as $hook => $functions ) {
			global $wp_filter;

			if ( isset( $wp_filter[ $hook ][10] ) && ! empty( $wp_filter[ $hook ][10] ) ) {
				remove_all_actions( $hook, 10 );
			}

			if ( ! empty( $functions ) ) {
				foreach( $functions as $function ) {
					add_action( $hook, $function );
				}
			}
		}
	}
}
add_action( 'wp', 'enlightenment_add_template_actions', 30 );

function enlightenment_add_lead_post_actions() {
	if ( ! is_singular() && '' == get_post_format()  ) {
		$hooks    = get_theme_mod( 'template_hooks' );
		$template = 'blog';

		if ( isset( $hooks[ $template ] ) ) {
			foreach( $hooks[ $template ] as $hook => $functions ) {
				remove_all_actions( $hook, 10 );

				if ( ! empty( $functions ) ) {
					if ( ! is_array( $functions ) ) {
						$functions = explode( ',', $functions );
					}

					foreach ( $functions as $function ) {
						add_action( $hook, $function, 10, apply_filters( 'enlightenment_template_actions_accepted_args', 2 ) );
					}
				}
			}
		}
	}
}
add_action( 'enlightenment_before_entry', 'enlightenment_add_lead_post_actions', 5 );
