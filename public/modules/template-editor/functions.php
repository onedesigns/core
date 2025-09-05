<?php

function enlightenment_site_header_hooks() {
	$hooks = array(
		'enlightenment_before_site'        => array(
			'name'      => __( 'Before Page', 'enlightenment' ),
			'functions' => array(),
		),
		'enlightenment_before_site_header' => array(
			'name'      => __( 'Before Site Header', 'enlightenment' ),
			'functions' => array(),
		),
		'enlightenment_site_header'        => array(
			'name'      => __( 'Site Header', 'enlightenment' ),
			'functions' => array( 'enlightenment_site_branding', 'enlightenment_nav_menu' ),
		),
		'enlightenment_after_site_header'  => array(
			'name'      => __( 'After Site Header', 'enlightenment' ),
			'functions' => array(),
		),
	);

	return apply_filters( 'enlightenment_site_header_hooks', $hooks );
}

function enlightenment_page_content_hooks() {
	$hooks = array_merge(
		array(
			'enlightenment_before_site_content' => array(
				'name'      => __( 'Before Site Content', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_before_page_header'  => array(
				'name'      => __( 'Before Page Header', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_page_header'         => array(
				'name'      => __( 'Page Header', 'enlightenment' ),
				'functions' => array( 'enlightenment_author_avatar', 'enlightenment_archive_title', 'enlightenment_archive_description', 'enlightenment_breadcrumbs', 'enlightenment_author_social_links' ),
			),
			'enlightenment_after_page_header'   => array(
				'name'      => __( 'After Page Header', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_before_page_content' => array(
				'name'      => __( 'Before Page Content', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_page_content'        => array(
				'name'      => __( 'Page Content', 'enlightenment' ),
				'functions' => array( 'enlightenment_the_loop' ),
			),
		),
		enlightenment_the_loop_hooks(),
		array(
			'enlightenment_after_page_content'  => array(
				'name'      => __( 'After Page Content', 'enlightenment' ),
				'functions' => array( 'get_sidebar' ),
			),
			'enlightenment_after_site_content'  => array(
				'name'      => __( 'After Site Content', 'enlightenment' ),
				'functions' => array(),
			),
		)
	);

	return apply_filters( 'enlightenment_page_content_hooks', $hooks );
}

function enlightenment_the_loop_hooks() {
	$hooks = array_merge(
		array(
			'enlightenment_before_the_loop'     => array(
				'name'      => __( 'Before The Loop', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_before_entries_list' => array(
				'name'      => __( 'Before Entries List', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_before_entries' => array(
				'name'      => __( 'Before Entries', 'enlightenment' ),
				'functions' => array(),
			),
		),
		enlightenment_entry_hooks(),
		array(
			'enlightenment_after_entries'  => array(
				'name'      => __( 'After Entries', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_after_entries_list'  => array(
				'name'      => __( 'After Entries List', 'enlightenment' ),
				'functions' => array( 'enlightenment_posts_nav' ),
			),
			'enlightenment_no_entries'=> array(
				'name'      => __( 'No Content Found', 'enlightenment' ),
				'functions' => array( 'enlightenment_no_posts_found' ),
			),
			'enlightenment_after_the_loop'      => array(
				'name'      => __( 'After The Loop', 'enlightenment' ),
				'functions' => array(),
			),
		)
	);

	return apply_filters( 'enlightenment_the_loop_hooks', $hooks );
}

function enlightenment_entry_hooks() {
	$hooks = array_merge(
		array(
			'enlightenment_before_entry'         => array(
				'name'      => __( 'Before Entry', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_before_entry_header'  => array(
				'name'      => __( 'Before Entry Header', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_entry_header'         => array(
				'name'      => __( 'Entry Header', 'enlightenment' ),
				'functions' => array( 'enlightenment_post_thumbnail', 'enlightenment_entry_title', 'enlightenment_entry_meta' ),
			),
			'enlightenment_after_entry_header'   => array(
				'name'      => __( 'After Entry Header', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_before_entry_content' => array(
				'name'      => __( 'Before Entry Content', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_entry_content'        => array(
				'name'      => __( 'Entry Content', 'enlightenment' ),
				'functions' => array( 'enlightenment_post_thumbnail', 'enlightenment_post_excerpt', 'enlightenment_post_content', 'enlightenment_link_pages' ),
			),
			'enlightenment_after_entry_content'  => array(
				'name'      => __( 'After Entry Content', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_before_entry_footer'  => array(
				'name'      => __( 'Before Entry Footer', 'enlightenment' ),
				'functions' => array(),
			),
			'enlightenment_entry_footer'         => array(
				'name'      => __( 'Entry Footer', 'enlightenment' ),
				'functions' => array( 'enlightenment_entry_meta' ),
			),
			'enlightenment_after_entry_footer'   => array(
				'name'      => __( 'After Entry Footer', 'enlightenment' ),
				'functions' => array( 'enlightenment_post_nav', 'enlightenment_comments_template' ),
			),
		),
		enlightenment_comments_hooks(),
		array(
			'enlightenment_after_entry'          => array(
				'name'      => __( 'After Entry', 'enlightenment' ),
				'functions' => array(),
			),
		)
	);

	return apply_filters( 'enlightenment_entry_hooks', $hooks );
}

function enlightenment_comments_hooks() {
	$hooks = array(
		'enlightenment_before_comments'      => array(
			'name'      => __( 'Before Comments', 'enlightenment' ),
			'functions' => array(),
		),
		'enlightenment_comments'             => array(
			'name'      => __( 'Comments', 'enlightenment' ),
			'functions' => array( 'enlightenment_list_comments' ),
		),
		'enlightenment_before_comments_list' => array(
			'name'      => __( 'Before Comments List', 'enlightenment' ),
			'functions' => array( 'enlightenment_comments_number', 'enlightenment_comments_nav' ),
		),
		'enlightenment_after_comments_list'  => array(
			'name'      => __( 'After Comments List', 'enlightenment' ),
			'functions' => array( 'enlightenment_comments_nav' ),
		),
		'enlightenment_no_comments'          => array(
			'name'      => __( 'No Comments', 'enlightenment' ),
			'functions' => array(),
		),
		'enlightenment_after_comments'       => array(
			'name'      => __( 'After Comments', 'enlightenment' ),
			'functions' => array( 'enlightenment_comment_form' ),
		),
	);

	return apply_filters( 'enlightenment_comments_hooks', $hooks );
}

function enlightenment_site_footer_hooks() {
	$hooks = array(
		'enlightenment_before_site_footer' => array(
			'name' => __( 'Before Site Footer', 'enlightenment' ),
			'functions' => array(),
		),
		'enlightenment_site_footer'        => array(
			'name' => __( 'Site Footer', 'enlightenment' ),
			'functions' => array( 'enlightenment_copyright_notice', 'enlightenment_credit_links' ),
		),
		'enlightenment_after_site_footer'  => array(
			'name' => __( 'After Site Footer', 'enlightenment' ),
			'functions' => array(),
		),
		'enlightenment_after_site'         => array(
			'name' => __( 'After Page', 'enlightenment' ),
			'functions' => array(),
		),
	);

	return apply_filters( 'enlightenment_site_footer_hooks', $hooks );
}

function enlightenment_template_hooks() {
	$hooks = array_merge(
		enlightenment_site_header_hooks(),
		enlightenment_page_content_hooks(),
		enlightenment_site_footer_hooks()
	);

	return apply_filters( 'enlightenment_template_hooks', $hooks );
}

function enlightenment_get_template_hook( $hook ) {
	$hooks = enlightenment_template_hooks();

	if ( isset( $hooks[$hook] ) ) {
		return $hooks[$hook];
	}

	return false;
}

function enlightenment_template_functions() {
	$functions = array(
		'enlightenment_site_branding'       => __( 'Site Branding',        'enlightenment' ),
		'enlightenment_nav_menu'            => __( 'Navigation Menu',      'enlightenment' ),
		'enlightenment_search_form'         => __( 'Search Form',          'enlightenment' ),
		'enlightenment_author_avatar'       => __( 'Author Avatar',        'enlightenment' ),
		'enlightenment_archive_title'       => __( 'Archive Title',        'enlightenment' ),
		'enlightenment_archive_description' => __( 'Archive Description',  'enlightenment' ),
		'enlightenment_breadcrumbs'         => __( 'Breadcrumbs',          'enlightenment' ),
		'enlightenment_author_social_links' => __( 'Author Social Links',  'enlightenment' ),
		'enlightenment_the_loop'            => __( 'The Loop',             'enlightenment' ),
		'enlightenment_entry_title'         => __( 'Post Title',           'enlightenment' ),
		'enlightenment_entry_meta'          => __( 'Post Meta',            'enlightenment' ),
		'enlightenment_post_excerpt'        => __( 'Post Excerpt',         'enlightenment' ),
		'enlightenment_post_thumbnail'      => __( 'Post Thumbnail',       'enlightenment' ),
		'enlightenment_post_content'        => __( 'Post Content',         'enlightenment' ),
		'enlightenment_link_pages'          => __( 'Post Pagination',      'enlightenment' ),
		'enlightenment_author_hcard'        => __( 'Author Bio',           'enlightenment' ),
		'enlightenment_post_nav'            => __( 'Next / Previous Post', 'enlightenment' ),
		'enlightenment_comments_template'   => __( 'Comments',             'enlightenment' ),
		'enlightenment_comments_number'     => __( 'Comments Title',       'enlightenment' ),
		'enlightenment_comments_nav'        => __( 'Comments Navigation',  'enlightenment' ),
		'enlightenment_list_comments'       => __( 'Comments List',        'enlightenment' ),
		'enlightenment_comment_form'        => __( 'Comment Form',         'enlightenment' ),
		'enlightenment_posts_nav'           => __( 'Posts Navigation',     'enlightenment' ),
		'enlightenment_no_posts_found'      => __( 'No Posts Found',       'enlightenment' ),
		'get_sidebar'                       => __( 'Primary Sidebar',      'enlightenment' ),
		'enlightenment_copyright_notice'    => __( 'Copyright Notice',     'enlightenment' ),
		'enlightenment_credit_links'        => __( 'Credit Links',         'enlightenment' ),
		// 'enlightenment_custom_function'     => __( 'Custom Function',      'enlightenment' ),
	);

	return apply_filters( 'enlightenment_template_functions', $functions );
}

function enlightenment_template_function_name( $function ) {
	$functions = enlightenment_template_functions();

	if ( isset( $functions[$function] ) ) {
		return $functions[$function];
	}

	return false;
}

function enlightenment_templates() {
	$templates = array(
		'error404' => array(
			'name'        => __( '404', 'enlightenment' ),
			'conditional' => 'is_404',
			'type'        => 'special',
		),
		'search'   => array(
			'name'        => __( 'Search', 'enlightenment' ),
			'conditional' => 'is_search',
			'type'        => 'archive',
		),
		'blog'     => array(
			'name'        => __( 'Blog', 'enlightenment' ),
			'conditional' => 'is_home',
			'type'        => 'post_type_archive',
		),
		'post'     => array(
			'name'        => __( 'Post', 'enlightenment' ),
			'conditional' => 'is_single',
			'type'        => 'post_type',
		),
		'page'     => array(
			'name'        => __( 'Page', 'enlightenment' ),
			'conditional' => 'is_page',
			'type'        => 'post_type',
		),
		'author'   => array(
			'name'        => __( 'Author', 'enlightenment' ),
			'conditional' => 'is_author',
			'type'        => 'archive',
		),
		'date'     => array(
			'name'        => __( 'Date', 'enlightenment' ),
			'conditional' => 'is_date',
			'type'        => 'archive',
		),
		'category' => array(
			'name'        => __( 'Category', 'enlightenment' ),
			'conditional' => 'is_category',
			'type'        => 'archive',
		),
		'post_tag' => array(
			'name'        => __( 'Tag', 'enlightenment' ),
			'conditional' => 'is_tag',
			'type'        => 'archive',
		),
		'comments' => array(
			'name'        => __( 'Comments', 'enlightenment' ),
			'conditional' => 'is_singular',
			'hooks'       => array_keys( enlightenment_comments_hooks() ),
			'type'        => 'special',
		),
	);

	$post_types = get_post_types( array( 'has_archive' => true ), 'objects' );

	foreach ( $post_types as $name => $post_type ) {
		$templates[$name . '-archive'] = array(
			'name'        => sprintf( __( '%s Archive', 'enlightenment' ), $post_type->labels->name ),
			'conditional' => array( 'is_post_type_archive', $name ),
			'type'        => 'post_type_archive',
		);
	}

	$post_types = get_post_types( array( 'publicly_queryable' => true ), 'objects' );

	unset( $post_types['post'] );
	unset( $post_types['attachment'] );
	unset( $post_types['e-landing-page'] );
	unset( $post_types['e-floating-buttons'] );
	unset( $post_types['elementor_library'] );

	foreach ( $post_types as $name => $post_type ) {
		$templates[$name] = array(
			'name'        => $post_type->labels->singular_name,
			'conditional' => array( 'is_singular', $name ),
			'type'        => 'post_type',
		);
	}

	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

	unset( $taxonomies['category'] );
	unset( $taxonomies['post_tag'] );
	unset( $taxonomies['post_format'] );
	unset( $taxonomies['product_shipping_class'] );

	foreach ( $taxonomies as $name => $taxonomy ) {
		$templates[ $name ] = array(
			'name'        => $taxonomy->labels->singular_name,
			'conditional' => array( 'is_tax', $name ),
			'type'        => 'taxonomy',
		);
	}

	$templates = apply_filters( 'enlightenment_templates', $templates );

	$default_hooks = array_keys( enlightenment_template_hooks() );

	foreach ( $templates as $name => $template ) {
		if ( ! isset( $template['hooks'] ) || empty( $template['hooks'] ) ) {
			$templates[ $name ]['hooks'] = $default_hooks;
		}
	}

	return $templates;
}

function enlightenment_get_template( $template ) {
	$templates = enlightenment_templates();

	if ( array_key_exists( $template, $templates) ) {
		return $templates[ $template ];
	}

	return false;
}

function enlightenment_current_query() {
	if ( is_admin() ) {
		return;
	}

	if ( is_404() ) {
		$query = 'error404';
	} elseif ( is_search() ) {
		$query = 'search';
	} elseif ( is_home() ) {
		$query = 'blog';
	} elseif ( is_single() && 'post' == get_post_type() ) {
		$query = 'post';
	} elseif ( is_page() ) {
		$query = 'page';
	} elseif ( is_author() ) {
		$query = 'author';
	} elseif ( is_date() ) {
		$query = 'date';
	} elseif ( is_category() ) {
		$query = 'category';
	} elseif ( is_tag() ) {
		$query = 'post_tag';
	} elseif ( is_post_type_archive() ) {
		$query = get_queried_object()->name . '-archive';
	} elseif ( is_singular() ) {
		$query = get_post_type();
	} elseif ( is_tax() ) {
		$query = get_queried_object()->taxonomy;
	} else {
		$query = '';
	}

	return apply_filters( 'enlightenment_current_query', $query );
}
