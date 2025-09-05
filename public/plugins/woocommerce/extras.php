<?php

function enlightenment_woocommerce_setup() {
	// Add Support for WooCommerce Plugin

	$args = get_theme_support( 'enlightenment-woocommerce' );

	if ( true === $args ) {
		add_theme_support( 'woocommerce' );
		return;
	}

	$defaults = array(
        'product_gallery_zoom'     => false,
    	'product_gallery_lightbox' => false,
    	'product_gallery_slider'   => false,
	);

	$args = is_array( $args ) ? array_shift( $args ) : array();
	$args = wp_parse_args( $args, $defaults );

	add_theme_support( 'woocommerce', $args );

	// Add Support for WooCommerce Gallery
	if ( $args['product_gallery_zoom'] ) {
		add_theme_support( 'wc-product-gallery-zoom' );
	}

	if ( $args['product_gallery_lightbox'] ) {
		add_theme_support( 'wc-product-gallery-lightbox' );
	}

	if ( $args['product_gallery_slider'] ) {
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'enlightenment_woocommerce_setup', 35 );

function enlightenment_woocommerce_remove_template_loader() {
	remove_filter( 'template_include', array( 'WC_Template_Loader', 'template_loader' ) );
}
add_action( 'init', 'enlightenment_woocommerce_remove_template_loader' );

function enlightenment_woocommerce_single_product_image_thumbnail_html( $output ) {
	$output = str_replace( 'class="zoom ', 'class="', $output );
	$output = str_replace( ' data-rel="prettyPhoto[product-gallery]"', '', $output );

	return $output;
}
// add_filter( 'woocommerce_single_product_image_thumbnail_html', 'enlightenment_woocommerce_single_product_image_thumbnail_html' );

function enlightenment_woocommerce_remove_default_product_tabs_filter() {
	remove_filter( 'woocommerce_product_tabs', 'woocommerce_default_product_tabs' );
}
add_action( 'init', 'enlightenment_woocommerce_remove_default_product_tabs_filter', 12 );

function enlightenment_woocommerce_default_product_tabs( $tabs = array() ) {
	global $product, $post;

	// Description tab - shows product content
	if ( $post->post_content ) {
		$tabs['description'] = array(
			'title'    => __( 'Description', 'enlightenment' ),
			'priority' => 10,
			'callback' => 'enlightenment_woocommerce_product_description_tab'
		);
	}

	// Additional information tab - shows attributes
	if ( $product && ( $product->has_attributes() || apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) ) ) {
		$tabs['additional_information'] = array(
			'title'    => __( 'Additional Information', 'enlightenment' ),
			'priority' => 20,
			'callback' => 'enlightenment_woocommerce_product_additional_information_tab'
		);
	}

	// Reviews tab - shows comments
	if ( comments_open() ) {
		$tabs['reviews'] = array(
			'title'    => sprintf( __( 'Reviews (%d)', 'enlightenment' ), $product->get_review_count() ),
			'priority' => 30,
			'callback' => 'enlightenment_comments_template'
		);
	}

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'enlightenment_woocommerce_default_product_tabs' );

function enlightenment_product_short_description_wrap( $excerpt, $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'entry-summary',
		'container_id'    => 'summary',
		'extra_atts'      => '',
	);
	$defaults = apply_filters( 'enlightenment_product_short_description_wrap_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	if( '' != $excerpt ) {
		$output = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['extra_atts'] );
		$output .= $excerpt . "\n";
		$output .= enlightenment_close_tag( $args['container'] );

		return $output;
	}

	return $excerpt;
}
add_filter( 'woocommerce_short_description', 'enlightenment_product_short_description_wrap' );

function enlightenment_woocommerce_product_review_comment_form_args( $args ) {
	$commenter = wp_get_current_commenter();

	$args = array(
		'title_reply'          => have_comments() ? __( 'Add a review', 'enlightenment' ) : sprintf( __( 'Be the first to review &ldquo;%s&rdquo;', 'enlightenment' ), get_the_title() ),
		'title_reply_to'       => __( 'Leave a Reply to %s', 'enlightenment' ),
		'comment_notes_before' => '',
		'comment_notes_after'  => '',
		'fields'               => array(
			'author' => '<p class="comment-form-author">' . '<label class="screen-reader-text visually-hidden" for="author">' . __( 'Name', 'enlightenment' ) . ' <span class="required">*</span></label> ' .
			            '<input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" placeholder="' . __( 'Name', 'enlightenment' ) . '" /></p>',
			'email'  => '<p class="comment-form-email"><label class="screen-reader-text visually-hidden" for="email">' . __( 'Email', 'enlightenment' ) . ' <span class="required">*</span></label> ' .
			            '<input class="form-control" id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" placeholder="' . __( 'Email', 'enlightenment' ) . '" /></p>',
		),
		'label_submit'  => __( 'Submit', 'enlightenment' ),
		'logged_in_as'  => '',
		'comment_field' => ''
	);

	if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
		$args['must_log_in'] = '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a review.', 'enlightenment' ), esc_url( $account_page_url ) ) . '</p>';
	}

	$args['comment_field']  = '';

	if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
		$args['comment_field'] .= '<p class="comment-form-rating">';
		$args['comment_field'] .= '<label class="screen-reader-text visually-hidden" for="rating">' . __( 'Your Rating', 'enlightenment' ) . '</label>';
		$args['comment_field'] .= '<select name="rating" id="rating">';
		$args['comment_field'] .= '<option value="">' . __( 'Rate&hellip;', 'enlightenment' ) . '</option>';
		$args['comment_field'] .= '<option value="5">' . __( 'Perfect', 'enlightenment' ) . '</option>';
		$args['comment_field'] .= '<option value="4">' . __( 'Good', 'enlightenment' ) . '</option>';
		$args['comment_field'] .= '<option value="3">' . __( 'Average', 'enlightenment' ) . '</option>';
		$args['comment_field'] .= '<option value="2">' . __( 'Not that bad', 'enlightenment' ) . '</option>';
		$args['comment_field'] .= '<option value="1">' . __( 'Very Poor', 'enlightenment' ) . '</option>';
		$args['comment_field'] .= '</select>';
		$args['comment_field'] .= '</p>';
	}

	$args['comment_field'] .= '<p class="comment-form-comment">';
	$args['comment_field'] .= '<label class="screen-reader-text visually-hidden" for="comment">' . __( 'Your Review', 'enlightenment' ) . '</label>';
	$args['comment_field'] .= '<textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . __( 'Click to leave your Review &#8230;', 'enlightenment' ) . '"></textarea>';
	$args['comment_field'] .= '</p>';

	return $args;
}
add_filter( 'woocommerce_product_review_comment_form_args', 'enlightenment_woocommerce_product_review_comment_form_args' );

function enlightenment_shop_infinite_scroll_theme_support_args() {
	if( is_post_type_archive( 'product' ) || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
		global $_wp_theme_features;
		$_wp_theme_features['enlightenment-infinite-scroll'][0]['navSelector'] = '.woocommerce-pagination';
	}
}
add_action( 'wp', 'enlightenment_shop_infinite_scroll_theme_support_args' );
