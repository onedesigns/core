<?php

function enlightenment_templates_blog_post_teaser( $templates ) {
	$templates = array_slice( $templates, 0, 3, true ) + array(
		'post-teaser' => array(
			'name'  => __( 'Blog Post Teaser', 'enlightenment' ),
			'hooks' => array_keys( enlightenment_entry_hooks() ),
			'type'  => 'special',
		),
	) + array_slice( $templates, 3, null, true );

	return $templates;
}
add_filter( 'enlightenment_templates', 'enlightenment_templates_blog_post_teaser' );

function enlightenment_customizer_templates_blog_post_teaser( $templates ) {
	if ( ! isset( $templates['post-teaser'] ) ) {
		return $templates;
	}

	$templates['post-teaser']['url'] = 'page' == get_option( 'show_on_front' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );

	return $templates;
}
add_filter( 'enlightenment_customizer_templates', 'enlightenment_customizer_templates_blog_post_teaser' );

function enlightenment_add_post_teaser_actions() {
	if( ! enlightenment_is_lead_post() && '' == get_post_format()  ) {
		$hooks    = get_theme_mod( 'template_hooks' );
		$template = 'post-teaser';

		if( isset( $hooks[ $template ] ) ) {
			foreach( $hooks[ $template ] as $hook => $functions ) {
				remove_all_actions( $hook, 10 );

				if( ! empty( $functions ) ) {
					foreach( $functions as $function ) {
						add_action( $hook, $function, 10, apply_filters( 'enlightenment_template_actions_accepted_args', 2 ) );
					}
				}
			}
		}
	}
}
add_action( 'enlightenment_before_entry', 'enlightenment_add_post_teaser_actions', 6 );
