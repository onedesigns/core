<?php

function enlightenment_load_framework_text_domain() {
	load_theme_textdomain( 'enlightenment', enlightenment_languages_directory() );
}
add_action( 'after_setup_theme', 'enlightenment_load_framework_text_domain', 5 );

function enlightenment_setup_theme_minimals() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array(
		'comment-list',
		'comment-form',
		'search-form',
		'gallery',
		'caption'
	) );
	add_theme_support( 'yoast-seo-breadcrumbs' );

	register_nav_menu( 'primary', __( 'Primary', 'enlightenment' ) );
}

add_action( 'after_setup_theme', 'enlightenment_setup_theme_minimals', 5 );

function enlightenment_register_core_styles() {
	$suffix = wp_scripts_get_suffix();

	wp_register_style(
		'bootstrap',
		enlightenment_styles_directory_uri() . "/bootstrap{$suffix}.css",
		false,
		null
	);

	wp_register_style(
		'font-awesome',
		enlightenment_styles_directory_uri() . "/fontawesome{$suffix}.css",
		false,
		null
	);

	wp_register_style(
		'colorbox',
		enlightenment_styles_directory_uri() . '/colorbox.css',
		false,
		null
	);

	wp_register_style(
		'fluidbox',
		enlightenment_styles_directory_uri() . '/fluidbox.css',
		false,
		null
	);

	wp_register_style(
		'imagelightbox',
		enlightenment_styles_directory_uri() . '/imagelightbox.css',
		false,
		null
	);

	wp_register_style(
		'flexslider',
		enlightenment_styles_directory_uri() . '/flexslider.css',
		false,
		null
	);

	wp_register_style(
		'select2',
		enlightenment_styles_directory_uri() . "/select2{$suffix}.css",
		false,
		null
	);

	wp_register_style(
		'gemini-scrollbar',
		enlightenment_styles_directory_uri() . '/gemini-scrollbar.css',
		false,
		null
	);

	wp_register_style(
		'enlightenment-dropdown',
		enlightenment_styles_directory_uri() . '/dropdown.css',
		false,
		null
	);

	$deps = apply_filters( 'enlightenment_theme_stylesheet_deps', array() );

	if ( get_stylesheet_directory() != get_template_directory() ) {
		$parent_deps = apply_filters( 'enlightenment_parent_stylesheet_deps', $deps );

		wp_register_style( 'enlightenment-parent-stylesheet', get_template_directory_uri() . '/style.css', $parent_deps, null );

		$deps = apply_filters( 'enlightenment_child_stylesheet_deps', $deps );
	}

	wp_register_style( 'enlightenment-theme-stylesheet', get_stylesheet_uri(), $deps, null );
}
add_action( 'init', 'enlightenment_register_core_styles' );

function enlightenment_child_stylesheet_parent_dependent( $deps ) {
	$deps[] = 'enlightenment-parent-stylesheet';

	return $deps;
}
add_filter( 'enlightenment_child_stylesheet_deps', 'enlightenment_child_stylesheet_parent_dependent' );

function enlightenment_enqueue_core_styles() {
	wp_enqueue_style( 'enlightenment-theme-stylesheet' );

	$custom_css = apply_filters( 'enlightenment_theme_custom_css', '' );

	if ( ! empty( $custom_css ) ) {
		wp_add_inline_style( 'enlightenment-theme-stylesheet', $custom_css );
	}
}
add_action( 'wp_enqueue_scripts', 'enlightenment_enqueue_core_styles', 8 );

function enlightenment_register_core_scripts() {
	$suffix = wp_scripts_get_suffix();

	wp_register_script(
		'bootstrap',
		enlightenment_scripts_directory_uri() . "/bootstrap.bundle{$suffix}.js",
		array( 'jquery' ),
		null,
		true
	);

	wp_register_script(
		'bootstrap-color-mode',
		enlightenment_scripts_directory_uri() . "/bootstrap-color-mode{$suffix}.js",
		array( 'jquery' ),
		null,
		true
	);

	wp_register_script(
		'colorbox',
		enlightenment_scripts_directory_uri() . "/jquery.colorbox{$suffix}.js",
		array( 'jquery' ),
		null,
		true
	);

	wp_register_script(
		'throttle-debounce',
		enlightenment_scripts_directory_uri() . "/jquery.ba-throttle-debounce{$suffix}.js",
		array( 'jquery' ),
		null,
		true
	);

	wp_register_script(
		'fluidbox',
		enlightenment_scripts_directory_uri() . "/jquery.fluidbox{$suffix}.js",
		array( 'jquery', 'throttle-debounce' ),
		null,
		true
	);

	wp_register_script(
		'imagelightbox',
		enlightenment_scripts_directory_uri() . "/imagelightbox{$suffix}.js",
		array( 'jquery' ),
		null,
		true
	);

	wp_register_script(
		'fitvids',
		enlightenment_scripts_directory_uri() . '/jquery.fitvids.js',
		array( 'jquery' ),
		null,
		true
	);

	wp_register_script(
		'flexslider',
		enlightenment_scripts_directory_uri() . "/jquery.flexslider{$suffix}.js",
		array( 'jquery' ),
		null,
		true
	);

	wp_register_script(
		'ajax-navigation',
		enlightenment_scripts_directory_uri() . '/ajax-navigation.js',
		array( 'jquery' ),
		null,
		true
	);

	wp_register_script(
		'infinitescroll',
		enlightenment_scripts_directory_uri() . '/jquery.infinitescroll.js',
		array( 'jquery' ),
		null,
		true
	);

	wp_register_script(
		'select2',
		enlightenment_scripts_directory_uri() . "/select2.full{$suffix}.js",
		false,
		null,
		true
	);

	wp_register_script(
		'gemini-scrollbar',
		enlightenment_scripts_directory_uri() . '/gemini-scrollbar.js',
		false,
		null,
		true
	);

	wp_register_script(
		'enlightenment-dropdown',
		enlightenment_scripts_directory_uri() . '/dropdown.js',
		array( 'jquery' ),
		null,
		true
	);

	$deps = apply_filters( 'enlightenment_call_js', array() );

	wp_register_script( 'enlightenment-call-js', enlightenment_scripts_directory_uri() . '/call.js', $deps, null, true );

	wp_localize_script( 'enlightenment-call-js', 'enlightenment_call_js', apply_filters( 'enlightenment_call_js_args', array(
		'ajaxurl'      => admin_url( 'admin-ajax.php' ),
		'site_url'     => site_url(),
		'admin_url'    => admin_url(),
		'includes_url' => includes_url(),
		'plugins_url'  => plugins_url(),
	) ) );
}
add_action( 'init', 'enlightenment_register_core_scripts' );

function enlightenment_register_core_admin_styles() {
	wp_register_style(
		'enlightenment-alpha-color-picker', enlightenment_styles_directory_uri() . '/alpha-color-picker.css',
		array( 'wp-color-picker' ), null
	);

	wp_register_style(
		'enlightenment-admin-form-controls', enlightenment_styles_directory_uri() . '/form-controls.css',
		array(), null
	);
}
add_action( 'admin_enqueue_scripts', 'enlightenment_register_core_admin_styles', 8 );

function enlightenment_register_core_admin_scripts() {
	wp_register_script(
		'enlightenment-alpha-color-picker', enlightenment_scripts_directory_uri() . '/alpha-color-picker.js',
		array( 'jquery', 'wp-color-picker' ), null, true
	);

	wp_register_script(
		'enlightenment-admin-form-controls', enlightenment_scripts_directory_uri() . '/form-controls.js',
		array( 'jquery' ), null, true
	);
}
add_action( 'admin_enqueue_scripts', 'enlightenment_register_core_admin_scripts', 8 );

function enlightenment_call_js_add_mediaelement( $deps ) {
	wp_enqueue_style( 'wp-mediaelement' );

	$deps[] = 'wp-mediaelement';

	return $deps;
}

function enlightenment_enqueue_core_scripts() {
	if ( is_singular() && get_option( 'thread_comments' ) && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$deps = apply_filters( 'enlightenment_call_js', array() );

	if ( ! empty( $deps ) ) {
		wp_enqueue_script( 'enlightenment-call-js' );
	}
}
add_action( 'wp_enqueue_scripts', 'enlightenment_enqueue_core_scripts' );

function enlightenment_widgets_init_minimals() {
	register_sidebar( wp_parse_args( enlightenment_register_sidebars_args(), array(
		'name'        => __( 'Primary', 'enlightenment' ),
		'id'          => 'sidebar-1',
		'description' => __( 'The Primary Sidebar', 'enlightenment' ),
	) ) );
}
add_action( 'widgets_init', 'enlightenment_widgets_init_minimals', 5 );
