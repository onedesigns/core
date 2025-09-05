<?php
/**
 * Customizer functions for Unlimited Sidebars.
 *
 * @package Enlightenment_Framework
 */

function enlightenment_unlimited_sidebars_customizer_templates() {
	$templates = array(
		'error404'  => array(
			'name' => __( '404',      'enlightenment' ),
			'url'  => add_query_arg( 'page_id', -1, home_url( '/' ) ),
		),
		'search'    => array(
			'name' => __( 'Search',   'enlightenment' ),
			'url'  => add_query_arg( 's', '', home_url( '/' ) ),
		),
		'blog'      => array(
			'name' => __( 'Blog',     'enlightenment' ),
			'url'  => 'page' == get_option( 'show_on_front' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' ),
		),
		'post'      => array(
			'name' => __( 'Post',     'enlightenment' ),
			'url'  => '',
		),
		'page'      => array(
			'name' => __( 'Page',     'enlightenment' ),
			'url'  => '',
		),
		'attachment' => array(
			'name' => __( 'Media',    'enlightenment' ),
			'url'  => '',
		),
		'author'    => array(
			'name' => __( 'Author',   'enlightenment' ),
			'url'  => '',
		),
		'date'      => array(
			'name' => __( 'Date',     'enlightenment' ),
			'url'  => '',
		),
		'category'  => array(
			'name' => __( 'Category', 'enlightenment' ),
			'url'  => '',
		),
		'post_tag'  => array(
			'name' => __( 'Post Tag', 'enlightenment' ),
			'url'  => '',
		),
	);

	if ( class_exists( 'WPSEO_Options' ) && true === WPSEO_Options::get( 'disable-attachment' ) ) {
		unset( $templates['attachment'] );
	}

	$posts = get_posts( array(
		'posts_per_page' => 1,
	) );

	if ( count( $posts ) ) {
		$templates['post']['url']   = get_permalink( $posts[0] );
		$templates['author']['url'] = get_author_posts_url( $posts[0]->post_author );
		$templates['date']['url']   = get_year_link( date( 'Y', strtotime( $posts[0]->post_date ) ) );
	}

	$pages = get_posts( array(
		'posts_per_page' => 1,
		'post_type'      => 'page',
	) );

	if ( count( $pages ) ) {
		$templates['page']['url'] = get_permalink( $pages[0] );
	}

	if ( isset( $templates['attachment'] ) ) {
		$media = get_posts( array(
			'posts_per_page' => 1,
			'post_type'      => 'attachment',
			'post_status'    => 'any',
		) );

		if ( count( $media ) ) {
			$templates['attachment']['url'] = get_permalink( $media[0] );
		}
	}

	$cats = get_categories( array(
		'number' => 1,
	) );

	if ( count( $cats ) ) {
		$templates['category']['url'] = get_category_link( $cats[0] );
	}

	$tags = get_tags( array(
		'number' => 1,
	) );

	if ( count( $tags ) ) {
		$templates['post_tag']['url'] = get_tag_link( $tags[0] );
	}

	$post_types = get_post_types( array( 'has_archive' => true ), 'objects' );

	foreach ( $post_types as $name => $post_type ) {
		$templates[ $name . '-archive' ] = array(
			'name' => sprintf( __( '%s Archive', 'enlightenment' ), $post_type->labels->name ),
			'url'  => get_post_type_archive_link( $name ),
		);
	}

	$post_types = get_post_types( array( 'publicly_queryable' => true ), 'objects' );

	unset( $post_types['post'] );
	unset( $post_types['attachment'] );
	unset( $post_types['e-landing-page'] );
	unset( $post_types['e-floating-buttons'] );

	foreach ( $post_types as $name => $post_type ) {
		if ( 'elementor_library' == $name ) {
			$post_type->labels->singular_name = __( 'Elementor Template', 'enlightenment' );
		}

		$url   = '';
		$posts = get_posts( array(
			'posts_per_page' => 1,
			'post_type'      => $name,
		) );

		if ( count( $posts ) ) {
			$url = get_permalink( $posts[0] );
		}

		$templates[ $name ] = array(
			'name' => $post_type->labels->singular_name,
			'url'  => $url,
		);
	}

	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

	unset( $taxonomies['category'] );
	unset( $taxonomies['post_tag'] );
	unset( $taxonomies['post_format'] );
	unset( $taxonomies['product_shipping_class'] );

	foreach( $taxonomies as $name => $taxonomy ) {
		switch ( $name ) {
			case 'product_cat':
				$taxonomy->labels->singular_name = __( 'Product Category', 'enlightenment' );
				break;

			case 'product_tag':
				$taxonomy->labels->singular_name = __( 'Product Tag', 'enlightenment' );
				break;
		}

		$url   = '';
		$terms = get_terms( array(
			'taxonomy' => $name,
			'number'   => 1,
		) );

		if ( count( $terms ) ) {
			$url = get_term_link( array_shift( $terms ) );
		}

		$templates[ $name ] = array(
			'name' => $taxonomy->labels->singular_name,
			'url'  => $url,
		);
	}

	return apply_filters( 'enlightenment_unlimited_sidebars_customizer_templates', $templates );
}

/**
 * Customizer settings, sections and controls.
 */
function enlightenment_unlimited_sidebars_customize_register( $wp_customize ) {
	$type      = 'theme_mod';
	$cap       = 'edit_theme_options';
	$supports  = 'enlightenment-unlimited-sidebars';
	$locations = enlightenment_sidebar_locations();
	$templates = enlightenment_unlimited_sidebars_customizer_templates();
	$sidebars  = enlightenment_registered_sidebars();
	$defaults  = enlightenment_registered_sidebars_default_atts();

	if ( current_theme_supports( 'enlightenment-grid-loop' ) ) {
		$columns = array();

		foreach ( enlightenment_grid_columns() as $grid => $atts ) {
			$columns[ $grid ] = array(
				'svg' => ( 0 === strpos( $atts['image'], '<svg ' ) ? $atts['image'] : '' ),
				'src' => ( 0 === strpos( $atts['image'], '<svg ' ) ? '' : $atts['image'] ),
				'alt' => $atts['name'],
			);
		}
	}

	$wp_customize->add_panel( 'unlimited-sidebars', array(
		'title'          => __( 'Sidebars', 'enlightenment' ),
		'description'    => '',
		'priority'       => 108,
		'theme_supports' => $supports,
	) );

	/**
	 * Existing sidebars
	 */
	foreach ( $sidebars as $sidebar => $atts ) {
		$wp_customize->add_section( sprintf( 'sidebar-%s', $sidebar ), array(
		    'panel'       => 'unlimited-sidebars',
		    'title'       => $atts['name'],
		    'description' => '',
		) );

		$wp_customize->add_setting( sprintf( 'sidebars[%s][name]', $sidebar ), array(
		    'type'              => $type,
		    'capability'        => $cap,
		    'default'           => '',
		    'transport'         => 'postMessage',
		    'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => 'enlightenment_validate_sidebar_title',
		) );

		$wp_customize->add_control( new Enlightenment_Customize_Sidebar_Title_Control( $wp_customize, sprintf( 'sidebars[%s][name]', $sidebar ), array(
		    'section'     => sprintf( 'sidebar-%s', $sidebar ),
		    'label'       => __( 'Sidebar Title', 'enlightenment' ),
		    'description' => '',
		) ) );

		$wp_customize->add_setting( sprintf( 'sidebars[%s][display_title]', $sidebar ), array(
		    'type'              => $type,
		    'capability'        => $cap,
		    'default'           => $defaults['display_title'],
		    'transport'         => 'postMessage',
		    'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( sprintf( 'sidebars[%s][display_title]', $sidebar ), array(
		    'section'     => sprintf( 'sidebar-%s', $sidebar ),
		    'type'        => 'checkbox',
		    'label'       => __( 'Show Title in Sidebar', 'enlightenment' ),
		    'description' => '',
		) );

		$wp_customize->add_setting( sprintf( 'sidebars[%s][description]', $sidebar ), array(
		    'type'              => $type,
		    'capability'        => $cap,
		    'default'           => $defaults['description'],
		    'transport'         => 'postMessage',
		    'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( sprintf( 'sidebars[%s][description]', $sidebar ), array(
		    'section'     => sprintf( 'sidebar-%s', $sidebar ),
		    'type'        => 'textarea',
		    'label'       => __( 'Sidebar Description', 'enlightenment' ),
		    'description' => '',
		) );

		$wp_customize->add_setting( sprintf( 'sidebars[%s][display_description]', $sidebar ), array(
		    'type'              => $type,
		    'capability'        => $cap,
		    'default'           => $defaults['display_description'],
		    'transport'         => 'postMessage',
		    'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( sprintf( 'sidebars[%s][display_description]', $sidebar ), array(
		    'section'     => sprintf( 'sidebar-%s', $sidebar ),
		    'type'        => 'checkbox',
		    'label'       => __( 'Show Description in Sidebar', 'enlightenment' ),
		    'description' => '',
		) );

		if ( current_theme_supports( 'enlightenment-grid-loop' ) ) {
		    if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
		        $breakpoints = enlightenment_bootstrap_breakpoints();
		        $prev = '';

		        foreach ( $breakpoints as $breakpoint => $label ) {
		            $cols = $columns;

		            if ( '' != $prev ) {
		                $cols = array_merge( array(
		                    'inherit' => array(
		                        'src' => '',
		                        'alt' => sprintf( __( 'Inherit from %s', 'enlightenment' ), $prev ),
		                    )
		                ), $cols );
		            }

		            $prev = $label;

		            $wp_customize->add_setting( sprintf( 'sidebars[%s][grid][%s]', $sidebar, $breakpoint ), array(
		                'type'              => $type,
		                'capability'        => $cap,
		                'default'           => $defaults['grid'][ $breakpoint ],
		                'transport'         => 'postMessage',
		                'sanitize_callback' => 'sanitize_text_field',
		            ) );

		            $wp_customize->add_control( new Enlightenment_Customize_Image_Radio_Control( $wp_customize, sprintf( 'sidebars[%s][grid][%s]', $sidebar, $breakpoint ), array(
		                'section'     => sprintf( 'sidebar-%s', $sidebar ),
		                'label'       => $label,
		                'description' => '',
		                'choices'     => $cols,
		            ) ) );
		        }
		    } else {
		        $wp_customize->add_setting( sprintf( 'sidebars[%s][grid]', $sidebar ), array(
		            'type'              => $type,
		            'capability'        => $cap,
		            'default'           => $defaults['grid'],
		            'transport'         => 'postMessage',
		            'sanitize_callback' => 'sanitize_text_field',
		        ) );

		        $wp_customize->add_control( new Enlightenment_Customize_Image_Radio_Control( $wp_customize, sprintf( 'sidebars[%s][grid]', $sidebar ), array(
		            'section'     => sprintf( 'sidebar-%s', $sidebar ),
		            'label'       => __( 'Sidebar Grid', 'enlightenment' ),
		            'description' => '',
		            'choices'     => $columns,
		        ) ) );
		    }
		}

		if( current_theme_supports( 'enlightenment-bootstrap' ) ) {
		    $wp_customize->add_setting( sprintf( 'sidebars[%s][contain_widgets]', $sidebar ), array(
		        'type'              => $type,
		        'capability'        => $cap,
		        'default'           => $defaults['contain_widgets'],
		        'transport'         => 'postMessage',
		        'sanitize_callback' => 'sanitize_text_field',
		    ) );

		    $wp_customize->add_control( sprintf( 'sidebars[%s][contain_widgets]', $sidebar ), array(
		        'section'     => sprintf( 'sidebar-%s', $sidebar ),
		        'type'        => 'checkbox',
		        'label'       => __( 'Center widgets in a fixed-width wrapper', 'enlightenment' ),
		        'description' => '',
		    ) );
		}

		if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'custom_sidebar_background' ) ) {
			$wp_customize->add_setting( sprintf( 'sidebars[%s][background][color]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['background']['color'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new Enlightenment_Customize_Alpha_Color_Control( $wp_customize, sprintf( 'sidebars[%s][background][color]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'label'       => __( 'Sidebar Background Color', 'enlightenment' ),
			    'description' => '',
			) ) );

			$wp_customize->add_setting( sprintf( 'sidebars[%s][background][image]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['background']['image'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, sprintf( 'sidebars[%s][background][image]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'label'       => __( 'Sidebar Background Image', 'enlightenment' ),
			    'description' => '',
			) ) );

			$wp_customize->add_setting( sprintf( 'sidebars[%s][background][position]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['background']['position'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

		    $wp_customize->add_control( new Enlightenment_Customize_Position_Control( $wp_customize, sprintf( 'sidebars[%s][background][position]', $sidebar ), array(
		        'section' => sprintf( 'sidebar-%s', $sidebar ),
		        'label'   => __( 'Sidebar Background Position', 'enlightenment' ),
		    ) ) );

			$wp_customize->add_setting( sprintf( 'sidebars[%s][background][repeat]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['background']['repeat'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( sprintf( 'sidebars[%s][background][repeat]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'type'        => 'select',
			    'label'       => __( 'Sidebar Background Repeat', 'enlightenment' ),
			    'description' => '',
			    'choices'     => array(
			        'no-repeat' => __( 'No Repeat',          'enlightenment' ),
			        'repeat'    => __( 'Tiled',              'enlightenment' ),
			        'repeat-x'  => __( 'Tiled Horizontally', 'enlightenment' ),
			        'repeat-y'  => __( 'Tiled Vertically',   'enlightenment' ),
			    ),
			) );

			$wp_customize->add_setting( sprintf( 'sidebars[%s][background][size]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['background']['size'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( sprintf( 'sidebars[%s][background][size]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'type'        => 'select',
			    'label'       => __( 'Sidebar Background Size', 'enlightenment' ),
			    'description' => '',
			    'choices'     => array(
			        'auto'    => __( 'Original',  'enlightenment' ),
			        'cover'   => __( 'Cover',     'enlightenment' ),
			        'contain' => __( 'Contained', 'enlightenment' ),
			    ),
			) );

			$wp_customize->add_setting( sprintf( 'sidebars[%s][background][scroll]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['background']['scroll'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( sprintf( 'sidebars[%s][background][scroll]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'type'        => 'select',
			    'label'       => __( 'Sidebar Background Attachment', 'enlightenment' ),
			    'description' => '',
			    'choices'     => array(
			        'scroll' => __( 'Scroll', 'enlightenment' ),
			        'fixed'  => __( 'Fixed', 'enlightenment' ),
			    ),
			) );
		}

		if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'sidebar_title_color' ) ) {
			$wp_customize->add_setting( sprintf( 'sidebars[%s][sidebar_title_color]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['sidebar_title_color'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, sprintf( 'sidebars[%s][sidebar_title_color]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'label'       => __( 'Sidebar Title Color', 'enlightenment' ),
			    'description' => '',
			) ) );
		}

		if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'sidebar_text_color' ) ) {
			$wp_customize->add_setting( sprintf( 'sidebars[%s][sidebar_text_color]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['sidebar_text_color'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, sprintf( 'sidebars[%s][sidebar_text_color]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'label'       => __( 'Sidebar Description Color', 'enlightenment' ),
			    'description' => '',
			) ) );
		}

		if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'custom_widgets_background' ) ) {
			$wp_customize->add_setting( sprintf( 'sidebars[%s][widgets_background_color]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['widgets_background_color'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new Enlightenment_Customize_Alpha_Color_Control( $wp_customize, sprintf( 'sidebars[%s][widgets_background_color]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'label'       => __( 'Widgets Background Color', 'enlightenment' ),
			    'description' => '',
			) ) );
		}

		if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'widgets_title_color' ) ) {
			$wp_customize->add_setting( sprintf( 'sidebars[%s][widgets_title_color]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['widgets_title_color'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, sprintf( 'sidebars[%s][widgets_title_color]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'label'       => __( 'Widgets Headings Color', 'enlightenment' ),
			    'description' => '',
			) ) );
		}

		if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'widgets_text_color' ) ) {
			$wp_customize->add_setting( sprintf( 'sidebars[%s][widgets_text_color]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['widgets_text_color'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, sprintf( 'sidebars[%s][widgets_text_color]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'label'       => __( 'Widgets Text Color', 'enlightenment' ),
			    'description' => '',
			) ) );
		}

		if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'widgets_link_color' ) ) {
			$wp_customize->add_setting( sprintf( 'sidebars[%s][widgets_link_color]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['widgets_link_color'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, sprintf( 'sidebars[%s][widgets_link_color]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'label'       => __( 'Widgets Link Color', 'enlightenment' ),
			    'description' => '',
			) ) );
		}

		if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'widgets_link_hover_color' ) ) {
			$wp_customize->add_setting( sprintf( 'sidebars[%s][widgets_link_hover_color]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['widgets_link_hover_color'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, sprintf( 'sidebars[%s][widgets_link_hover_color]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'label'       => __( 'Widgets Link Hover Color', 'enlightenment' ),
			    'description' => '',
			) ) );
		}

		if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'widgets_link_active_color' ) ) {
			$wp_customize->add_setting( sprintf( 'sidebars[%s][widgets_link_active_color]', $sidebar ), array(
			    'type'              => $type,
			    'capability'        => $cap,
			    'default'           => $defaults['widgets_link_active_color'],
			    'transport'         => 'postMessage',
			    'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, sprintf( 'sidebars[%s][widgets_link_active_color]', $sidebar ), array(
			    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			    'label'       => __( 'Widgets Link Active Color', 'enlightenment' ),
			    'description' => '',
			) ) );
		}

		$wp_customize->add_setting( sprintf( 'sidebars[%s][delete]', $sidebar ), array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( new Enlightenment_Customize_Delete_Sidebar_Control( $wp_customize, sprintf( 'sidebars[%s][delete]', $sidebar ), array(
		    'section'     => sprintf( 'sidebar-%s', $sidebar ),
			'priority'    => 1001,
			'label'       => __( 'Delete Sidebar',                                          'enlightenment' ),
			'aria_label'  => __( 'Delete selected sidebar',                                 'enlightenment' ),
			'description' => __( 'Deleting this sidebar will remove it from all locations', 'enlightenment' ),
		) ) );
	}

	/**
	 * Add new sidebar
	 */

	$wp_customize->add_section( 'create-sidebar', array(
		'panel'          => 'unlimited-sidebars',
		'priority'       => 1010,
		'title'          => __( 'Create New Sidebar', 'enlightenment' ),
		'description'    => '',
	) );

	$wp_customize->add_setting( 'new_sidebar_name', array(
		'type'              => 'enlightenment_inert_setting_type',
		'capability'        => $cap,
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'new_sidebar_name', array(
		'section'         => 'create-sidebar',
		'label'           => __( 'Sidebar Title', 'enlightenment' ),
		'description'     => '',
	) );

	$wp_customize->add_setting( 'add_new_sidebar', array(
		'type'              => 'enlightenment_inert_setting_type',
		'capability'        => $cap,
		'default'           => false,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new Enlightenment_Customize_Add_Sidebar_Control( $wp_customize, 'add_new_sidebar', array(
		'section'         => 'create-sidebar',
		'label'           => __( 'Next', 'enlightenment' ),
		'description'     => '',
	) ) );

	/**
	 * Sidebar locations
	 */
	$choices = array(
		'' => '&mdash;',
	);

	foreach ( $sidebars as $sidebar => $atts ) {
		$choices[ $sidebar ] = $atts['name'];
	}

	foreach ( $locations as $template => $settings ) {
		$wp_customize->add_section( sprintf( 'sidebar-locations-template-%s', $template ), array(
			'panel'          => 'unlimited-sidebars',
			'priority'       => 1020,
			'title'          => $templates[ $template ]['name'],
			'description'    => sprintf( '<p>%s</p>', sprintf( __( 'You are customizing sidebar locations for the %s template.', 'enlightenment' ), sprintf( '<strong>%s</strong>', $templates[ $template ]['name'] ) ) ),
		) );

		foreach ( $settings as $location => $atts ) {
			$wp_customize->add_setting( sprintf( 'sidebar_locations[%s][%s]', $template, $location ), array(
				'type'              => $type,
				'capability'        => $cap,
				'default'           => $atts['sidebar'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new Enlightenment_Customize_Sidebar_Location_Control( $wp_customize, sprintf( 'sidebar_locations[%s][%s]', $template, $location ), array(
				'section'         => sprintf( 'sidebar-locations-template-%s', $template ),
				'label'           => $atts['name'],
				'description'     => '',
				'choices'         => $choices,
				'add_label'       => __( '+ Create New Sidebar',  'enlightenment' ),
				'add_aria_label'  => __( 'Create a new sidebar',  'enlightenment' ),
				'edit_label'      => __( 'Edit Sidebar',          'enlightenment' ),
				'edit_aria_label' => __( 'Edit selected sidebar', 'enlightenment' ),
			) ) );
		}
	}
}
add_action( 'customize_register', 'enlightenment_unlimited_sidebars_customize_register' );

function enlightenment_unlimited_sidebars_customize_controls_scripts() {
	wp_enqueue_style( 'enlightenment-customize-controls' );
	wp_enqueue_script( 'enlightenment-customize-controls' );
}
add_action( 'customize_controls_enqueue_scripts', 'enlightenment_unlimited_sidebars_customize_controls_scripts' );

function enlightenment_unlimited_sidebars_customize_controls_args( $args ) {
	global $wp_registered_sidebars;

	$sidebars  = enlightenment_registered_sidebars();
	$ids       = array();
	$next_id   = false;
	$defaults  = enlightenment_registered_sidebars_default_atts();
	$templates = enlightenment_unlimited_sidebars_customizer_templates();

	foreach ( array_keys( $wp_registered_sidebars ) as $id ) {
		$id = str_replace( 'sidebar-', '', $id );
		$id = absint( $id );

		if ( $id && $id == absint( $id ) ) {
			$ids[] = absint( $id );
		} else {
			// A non-standard sidebar id has been encountered and extracting
			// numeric values from ids is no longer reliable.
			$ids = array();
			break;
		}
	}

	if ( ! empty( $ids ) ) {
		$next_id = max( $ids ) + 1;
	}

	if ( ! $next_id ) {
		$next_id = count( $wp_registered_sidebars ) + 1;

		while ( array_key_exists( sprintf( 'sidebar-%d', $next_id ), $wp_registered_sidebars ) ) {
			$next_id++;
		}
	}

	$sidebar = sprintf( 'sidebar-%d', $next_id );

	if ( current_theme_supports( 'enlightenment-grid-loop' ) ) {

	}

	$args['unlimited_sidebars'] = array(
		'default_sidebars'          => $GLOBALS['enlightenment_default_sidebars'],
		'first_sidebar'             => ! empty( $sidebars )  ? array_keys( $sidebars )[0]  : '',
		'next_id'                   => $next_id,
		'templates'                 => $templates,
		'first_template'            => ! empty( $templates ) ? array_keys( $templates )[0] : '',
		'sidebarsHeader'            => array(
			'title'       => __( 'Sidebars', 'enlightenment' ),
			'description' => __( 'Select a sidebar to customize.', 'enlightenment' ),
		),
		'customizeAction'           => __( 'Customizing &#9656; Sidebars', 'enlightenment' ),
		'grid'                      => current_theme_supports( 'enlightenment-grid-loop' ),
		'bootstrap'                 => current_theme_supports( 'enlightenment-bootstrap' ),
		'custom_sidebar_background' => current_theme_supports( 'enlightenment-unlimited-sidebars', 'custom_sidebar_background' ),
		'custom_widgets_background' => current_theme_supports( 'enlightenment-unlimited-sidebars', 'custom_widgets_background' ),
		'default_atts'              => $defaults,
		'labels'                    => array(
			'sidebar_title'             => __( 'Sidebar Title',                                                  'enlightenment' ),
			'display_title'             => __( 'Show Title in Sidebar',                                          'enlightenment' ),
			'description'               => __( 'Sidebar Description',                                            'enlightenment' ),
			'display_description'       => __( 'Show Description in Sidebar',                                    'enlightenment' ),
			'grid'                      => __( 'Sidebar Grid',                                                   'enlightenment' ),
			'grid_inherit_from'         => __( 'Inherit from %s',                                                'enlightenment' ),
			'contain_widgets'           => __( 'Center widgets in a fixed-width wrapper',                        'enlightenment' ),
			'sidebar_bg_color'          => __( 'Sidebar Background Color',                                       'enlightenment' ),
			'sidebar_bg_alpha'          => __( 'Sidebar Background Opacity',                                     'enlightenment' ),
			'sidebar_bg_image'          => __( 'Sidebar Background Image',                                       'enlightenment' ),
			'sidebar_bg_position'       => __( 'Sidebar Background Position',                                    'enlightenment' ),
			'sidebar_bg_repeat'         => __( 'Sidebar Background Repeat',                                      'enlightenment' ),
			'sidebar_bg_size'           => __( 'Sidebar Background Size',                                        'enlightenment' ),
			'sidebar_bg_scroll'         => __( 'Sidebar Background Attachment',                                  'enlightenment' ),
			'sidebar_title_color'       => __( 'Sidebar Title Color',                                            'enlightenment' ),
			'sidebar_text_color'        => __( 'Sidebar Description Color',                                      'enlightenment' ),
			'widgets_bg_color'          => __( 'Widgets Background Color',                                       'enlightenment' ),
			'widgets_bg_alpha'          => __( 'Widgets Background Opacity',                                     'enlightenment' ),
			'widgets_title_color'       => __( 'Widgets Headings Color',                                         'enlightenment' ),
			'widgets_text_color'        => __( 'Widgets Text Color',                                             'enlightenment' ),
			'widgets_link_color'        => __( 'Widgets Link Color',                                             'enlightenment' ),
			'widgets_link_hover_color'  => __( 'Widgets Link Hover Color',                                       'enlightenment' ),
			'widgets_link_active_color' => __( 'Widgets Link Active Color',                                      'enlightenment' ),
			'delete_sidebar'            => __( 'Delete Sidebar',                                                 'enlightenment' ),
			'aria_delete_sidebar'       => __( 'Delete selected sidebar',                                        'enlightenment' ),
			'delete_sidebar_desc'       => __( 'Deleting this sidebar will remove it from all locations',        'enlightenment' ),
			'cant_delete'               => __( 'You can not delete this sidebar because it is a theme default.', 'enlightenment' ),
		),
		'locationsHeader'           => array(
			'title'       => __( 'Sidebar Locations', 'enlightenment' ),
			'description' => __( 'Select a template to customize its sidebar locations.', 'enlightenment' ),
		),
	);

	if ( current_theme_supports( 'enlightenment-grid-loop' ) ) {
		$columns = array();

		foreach ( enlightenment_grid_columns() as $grid => $atts ) {
			$columns[ $grid ] = array(
				'svg' => ( 0 === strpos( $atts['image'], '<svg ' ) ? $atts['image'] : '' ),
				'src' => ( 0 === strpos( $atts['image'], '<svg ' ) ? '' : $atts['image'] ),
				'alt' => $atts['name'],
			);
		}

		$args['unlimited_sidebars']['grid_cols'] = $columns;
	}

	if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
		$args['unlimited_sidebars']['breakpoints'] = enlightenment_bootstrap_breakpoints();
	}

	if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'custom_sidebar_background' ) ) {
		$args['unlimited_sidebars']['canUpload'] = current_user_can( 'upload_files' );

		$args['unlimited_sidebars']['button_labels'] = array(
			'select'       => __( 'Select image',      'enlightenment' ),
			'site_icon'    => __( 'Select site icon',  'enlightenment' ),
			'change'       => __( 'Change image',      'enlightenment' ),
			'default'      => __( 'Default',           'enlightenment' ),
			'remove'       => __( 'Remove',            'enlightenment' ),
			'placeholder'  => __( 'No image selected', 'enlightenment' ),
			'frame_title'  => __( 'Select image',      'enlightenment' ),
			'frame_button' => __( 'Choose image',      'enlightenment' ),
		);

		$args['unlimited_sidebars']['bg_repeat_choices'] = array(
			'no-repeat' => __( 'No Repeat',          'enlightenment' ),
			'repeat'    => __( 'Tiled',              'enlightenment' ),
			'repeat-x'  => __( 'Tiled Horizontally', 'enlightenment' ),
			'repeat-y'  => __( 'Tiled Vertically',   'enlightenment' ),
		);

		$args['unlimited_sidebars']['bg_size_choices'] = array(
			'auto'    => __( 'Original',  'enlightenment' ),
			'cover'   => __( 'Cover',     'enlightenment' ),
			'contain' => __( 'Contained', 'enlightenment' ),
		);

		$args['unlimited_sidebars']['bg_scroll_choices'] = array(
			'scroll' => __( 'Scroll',   'enlightenment' ),
			'fixed'  => __( 'Fixed',    'enlightenment' ),
		);

		global $wp_filter;

		$has_filter = false;

		if ( ! empty( $wp_filter['theme_mod_sidebars'] ) && ! empty( $wp_filter['theme_mod_sidebars'][10] ) ) {
			foreach ( $wp_filter['theme_mod_sidebars'][10] as $callback ) {
				if (
					is_array( $callback['function'] )
					&&
					$callback['function'][0] instanceof WP_Customize_Setting
					&&
					'_multidimensional_preview_filter' == $callback['function'][1]
				) {
					$has_filter = true;
					$filter     = $callback['function'];

					break;
				}
			}
		}

		if ( $has_filter ) {
			remove_filter( 'theme_mod_sidebars', $filter );
		}

		$sidebars = get_theme_mod( 'sidebars' );

		if ( $has_filter ) {
			add_filter( 'theme_mod_sidebars', $filter );
		}

		if ( ! is_array( $sidebars ) ) {
			$sidebars = array();
		}

		$args['unlimited_sidebars']['sidebars'] = array_keys( $sidebars );
	}

	return $args;
}
add_filter( 'enlightenment_customize_controls_args', 'enlightenment_unlimited_sidebars_customize_controls_args' );

function enlightenment_unlimited_sidebars_customize_dynamic_setting_args( $setting_args, $setting_id ) {
	$type     = 'theme_mod';
	$cap      = 'edit_theme_options';
	$defaults = enlightenment_registered_sidebars_default_atts();

	if ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[name\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => 'enlightenment_validate_sidebar_title',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[display_title\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['display_title'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[description\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['description'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[display_description\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['display_description'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[grid\]/', $setting_id ) ) {
		if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
			$breakpoints = enlightenment_bootstrap_breakpoints();

			foreach ( $breakpoints as $breakpoint => $label ) {
				if ( preg_match( sprintf( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[grid\]\[%s\]$/', $breakpoint ), $setting_id ) ) {
					$setting_args = array(
						'type'              => $type,
						'capability'        => $cap,
						'default'           => $defaults['grid'][ $breakpoint ],
						'transport'         => 'postMessage',
						'sanitize_callback' => 'sanitize_text_field',
					);

					break;
				}
			}
		} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[grid\]$/', $setting_id ) ) {
			$setting_args = array(
				'type'              => $type,
				'capability'        => $cap,
				'default'           => $defaults['grid'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			);
		}
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[contain_widgets\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['contain_widgets'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[background\]\[color\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['background']['color'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[background\]\[image\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['background']['image'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[background\]\[position\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['background']['position'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[background\]\[repeat\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['background']['repeat'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[background\]\[size\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['background']['size'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[background\]\[scroll\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['background']['scroll'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[sidebar_title_color\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['sidebar_title_color'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[sidebar_text_color\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['sidebar_text_color'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[widgets_background_color\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['widgets_background_color'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[widgets_title_color\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['sidebar_text_color'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[widgets_text_color\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['sidebar_text_color'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[widgets_link_color\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['sidebar_text_color'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[widgets_link_hover_color\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['sidebar_text_color'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[widgets_link_active_color\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => $defaults['sidebar_text_color'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	} elseif ( preg_match( '/^sidebars\[sidebar-(?P<id>-?\d+)\]\[delete\]$/', $setting_id ) ) {
		$setting_args = array(
			'type'              => $type,
			'capability'        => $cap,
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		);
	}

	return $setting_args;
}
add_filter( 'customize_dynamic_setting_args', 'enlightenment_unlimited_sidebars_customize_dynamic_setting_args', 10, 2 );

function enlightenment_unlimited_sidebars_customize_controls_print_templates() {
	?>

	<script type="text/html" id="tmpl-unlimited-sidebars-sidebars-header">
		<span class="customize-control-title customize-section-title-unlimited_sidebars_sidebars-heading">{{ data.sidebarsHeader.title }}</span>
		<p class="customize-control-description customize-section-title-unlimited_sidebars_sidebars-description">{{ data.sidebarsHeader.description }}</p>
	</script>

	<script type="text/html" id="tmpl-unlimited-sidebars-locations-header">
		<span class="customize-control-title customize-section-title-unlimited_sidebars_locations-heading">{{ data.locationsHeader.title }}</span>
		<p class="customize-control-description customize-section-title-unlimited_sidebars_locations-description">{{ data.locationsHeader.description }}</p>
	</script>

	<?php
}
add_action( 'customize_controls_print_footer_scripts', 'enlightenment_unlimited_sidebars_customize_controls_print_templates' );

function enlightenment_unlimited_sidebars_customize_delete_sidebar( $sidebars ) {
	global $wpdb, $enlightenment_default_sidebars;

	$settings  = get_theme_mod( 'sidebar_locations' );
	$reset_mod = false;
	$post_ids  = $wpdb->get_col( "SELECT `post_id` FROM `$wpdb->postmeta` WHERE `meta_key` = '_enlightenment_sidebar_locations'" );

	foreach ( $sidebars as $id => $atts ) {
		if ( ! isset( $atts['delete'] ) || '1' !== $atts['delete'] ) {
			continue;
		}

		if ( in_array( $id, $enlightenment_default_sidebars ) ) {
			continue;
		}

		// Delete sidebar
		unset( $sidebars[ $id ] );

		// Remove from all template locations
		foreach ( $settings as $template => $locations ) {
			foreach ( $locations as $location => $sidebar ) {
				if ( $sidebar == $id ) {
					$settings[ $template ][ $location ] = '';

					$reset_mod = true;
				}
			}
		}

		// Remove from all post locations
		if ( ! empty( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				$locations  = get_post_meta( $post_id, '_enlightenment_sidebar_locations', true );

				if ( ! is_array( $locations ) ) {
					continue;
				}

				$reset_meta = false;

				foreach ( $locations as $location => $sidebar ) {
					if ( $sidebar == $id ) {
						$locations[ $location ] = '';

						$reset_meta = true;
					}
				}

				if ( $reset_meta ) {
					update_post_meta( $post_id, '_enlightenment_sidebar_locations', $locations );
				}
			}
		}
	}

	// We've removed the sidebar from locations so we need to reset the theme mod
	if ( $reset_mod ) {
		set_theme_mod( 'sidebar_locations', $settings );
	}

	return $sidebars;
}
add_filter( 'pre_set_theme_mod_sidebars', 'enlightenment_unlimited_sidebars_customize_delete_sidebar' );

function enlightenment_validate_sidebar_title( $validity, $value ) {
	if ( empty( $value ) ) {
		$validity->add( 'required', __( 'Please enter a title for your sidebar.', 'enlightenment' ) );
	}

	return $validity;
}
