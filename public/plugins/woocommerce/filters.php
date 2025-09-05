<?php

function enlightenment_woocommerce_filter_account_menu_item_classes( $classes, $endpoint ) {
	if ( in_array( 'is-active', $classes ) ) {
		// Remove 'is-active' class if we're not currently in My Account
		$myaccount_id = wc_get_page_id( 'myaccount' );

		if ( ! is_page( $myaccount_id ) ) {
			$classes = array_diff( $classes, array( 'is-active' ) );
		}
	}

    return $classes;
}
add_filter( 'woocommerce_account_menu_item_classes', 'enlightenment_woocommerce_filter_account_menu_item_classes', 10, 2 );

function enlightenment_woocommerce_archive_title( $output ) {
    if ( ! is_shop() && ! is_product_taxonomy() ) {
        return $output;
    }

    if ( ! apply_filters( 'woocommerce_show_page_title', true ) ) {
		return '';
	}

    return woocommerce_page_title( false );
}
add_filter( 'get_the_archive_title', 'enlightenment_woocommerce_archive_title' );

function enlightenment_woocommerce_filter_archive_description_args( $args ) {
    if ( is_product_taxonomy() ) {
        $args['container_class'] .= ' term-description';
    }

    return $args;
}
add_filter( 'enlightenment_archive_description_args', 'enlightenment_woocommerce_filter_archive_description_args' );

function enlightenment_woocommerce_filter_archive_description( $output ) {
    if ( is_shop() ) {
        $shop_page = get_post( wc_get_page_id( 'shop' ) );

        if ( $shop_page ) {
            return wc_format_content( $shop_page->post_content );
        }
    } elseif ( is_product_taxonomy() ) {
        $term = get_queried_object();

        if ( $term && ! empty( $term->description ) ) {
            return wc_format_content( $term->description );
        }
    }

    return $output;
}
add_filter( 'get_the_archive_description', 'enlightenment_woocommerce_filter_archive_description' );

function enlightenment_woocommerce_breadcrumbs( $output ) {
    if ( ! is_woocommerce() ) {
        return $output;
    }

    if ( function_exists( 'yoast_breadcrumb' ) && has_filter( 'enlightenment_breadcrumbs', 'enlightenment_yoast_breadcrumbs' ) ) {
        return $output;
    }

    ob_start();
    woocommerce_breadcrumb();
    return ob_get_clean();
}
add_filter( 'enlightenment_breadcrumbs', 'enlightenment_woocommerce_breadcrumbs' );

function enlightenment_woocommerce_post_thumbnail_args( $args ) {
	if( 'product' != get_post_type() ) {
		return $args;
	}

	global $post;

	$size  = is_singular() ? apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' ) : 'woocommerce_thumbnail';
	$props = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );

	$args['size']          = $size;
	$args['default']       = wc_placeholder_img( $args['size'] );
	$args['attr']['alt']   = $props['alt'];
	$args['attr']['title'] = $props['title'];

	return $args;
}
add_filter( 'enlightenment_post_thumbnail_args', 'enlightenment_woocommerce_post_thumbnail_args' );

function enlightenment_woocommerce_filter_entry_title_args( $args ) {
    if ( 'product' == get_post_type() ) {
        $args['container_class'] .= ' woocommerce-loop-product__title';
    }

    return $args;
}
add_filter( 'enlightenment_entry_title_args', 'enlightenment_woocommerce_filter_entry_title_args' );

function enlightenment_woocommerce_filter_template_content_product_cat_output( $output ) {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'     => 'class',
			'value'   => 'Elementor',
			'compare' => 'STARTS_WITH',
		),
	) ) ) {
		return $output;
	}

	if ( doing_action( 'wp_ajax_elementor_ajax' ) || doing_action( 'admin_action_elementor' ) || doing_filter( 'the_content' ) ) {
		return $output;
	}

    $output = str_replace( '<li ', '<article ', $output );
    $output = str_replace( '</li>', '</article>', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_content_product_cat_output', 'enlightenment_woocommerce_filter_template_content_product_cat_output' );

function enlightenment_wc_get_template_part( $template, $slug, $name ) {
    if ( 'content' == $slug && 'product' == $name ) {
		if ( enlightenment_has_in_call_stack( array(
			array(
				'key'     => 'class',
				'value'   => 'Elementor',
				'compare' => 'STARTS_WITH',
			),
		) ) ) {
			return $template;
		}

		if ( doing_action( 'wp_ajax_elementor_ajax' ) || doing_action( 'admin_action_elementor' ) || doing_filter( 'the_content' ) ) {
            return $template;
        }

        $slug = apply_filters( 'enlightenment_product_content_template_slug', $slug );
        $name = apply_filters( 'enlightenment_product_content_template_name', $name );

        if( '' != locate_template( array( '$slug-$name.php', '$slug.php' ), false, false ) ) {
            /*
             * Include the Post-Format-specific template for the content.
             * If you want to override this in a child theme, then include a
             * file called content-__.php (where __ is the Post Format name)
             * and that will be used instead.
             */
            get_template_part( $slug, $name );
        } else {
            global $post;

            add_action( 'enlightenment_custom_entry_header', 'woocommerce_show_product_loop_sale_flash' );
    		add_action( 'enlightenment_custom_entry_header', 'enlightenment_post_thumbnail' );
    		add_action( 'enlightenment_custom_entry_header', 'enlightenment_entry_title' );

    		add_action( 'enlightenment_custom_entry_footer', 'woocommerce_template_loop_rating' );
    		add_action( 'enlightenment_custom_entry_footer', 'woocommerce_template_loop_price' );
    		add_action( 'enlightenment_custom_entry_footer', 'woocommerce_template_loop_add_to_cart' );

            if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
                add_action( 'enlightenment_custom_before_entry', 'enlightenment_bootstrap_woocommerce_product_wrapper', 12 );
                add_action( 'enlightenment_custom_after_entry', 'enlightenment_close_div', 8 );
            }

            enlightenment_custom_loop( array(
                'default_post_class' => true,
                'query_name' => 'woocommerce_loop_product_content',
                'query_args' => array(
                    'post_type' => 'product',
                    'p'         => $post->ID,
                ),
            ) );
        }

        return false;
    }

    return $template;
}
add_filter( 'wc_get_template_part', 'enlightenment_wc_get_template_part', 10, 3 );

function enlightenment_woocommerce_remove_comments_template_filter() {
    remove_filter( 'comments_template', array( 'WC_Template_Loader', 'comments_template_loader' ) );
}
add_action( 'init', 'enlightenment_woocommerce_remove_comments_template_filter', 12 );

function enlightenment_woocommerce_review_args( $args, $comment ) {
    if ( 'review' != $comment->comment_type ) {
        return $args;
    }

    if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && wc_review_is_from_verified_owner( $comment->comment_ID ) ) {
        $args['comment_class'] .= ' by-verified-owner';
    }

    return $args;
}
add_filter( 'enlightenment_comment_args', 'enlightenment_woocommerce_review_args', 10, 2 );

function enlightenment_woocommerce_review_awaiting_moderation_args( $args, $comment ) {
    if ( 'review' != $comment->comment_type ) {
        return $args;
    }

    $args['text'] = __( 'Your review is awaiting approval.', 'enlightenment' );

    return $args;
}
add_filter( 'enlightenment_comment_awaiting_moderation_args', 'enlightenment_woocommerce_review_awaiting_moderation_args', 10, 2 );

function enlightenment_woocommerce_review_author_args( $args, $comment ) {
    if ( 'review' != $comment->comment_type ) {
        return $args;
    }

    $args['container_class'] .= ' woocommerce-review__author';
    $args['after']            = '';

    if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && wc_review_is_from_verified_owner( $comment->comment_ID ) ) {
        $args['after'] = sprintf( '<em class="woocommerce-review__verified verified">(%s)</em> ', esc_attr__( 'verified owner', 'enlightenment' ) );
    }

    return $args;
}
add_filter( 'enlightenment_comment_author_args', 'enlightenment_woocommerce_review_author_args', 10, 2 );

function enlightenment_woocommerce_comment_time_args( $args, $comment ) {
    if ( 'review' != $comment->comment_type ) {
        return $args;
    }

    $args['container_class'] .= ' woocommerce-review__published-date';
    $args['text_format']      = '%1$s';

    return $args;
}
add_filter( 'enlightenment_comment_time_args', 'enlightenment_woocommerce_comment_time_args', 10, 2 );

function enlightenment_woocommerce_filter_comments_number( $count, $post_id ) {
    if ( ! is_singular( 'product' ) ) {
        return $count;
    }

	$product = wc_get_product( $post_id );

	if ( ! $product ) {
		return $count;
	}

    return $product->get_review_count();
}
add_filter( 'get_comments_number', 'enlightenment_woocommerce_filter_comments_number', 10, 2 );

function enlightenment_woocommerce_filter_comments_number_args( $args ) {
    if ( ! is_singular( 'product' ) ) {
        return $args;
    }

    $args['zero'] = __( 'No reviews', 'enlightenment' );
    $args['one']  = __( '1 review', 'enlightenment' );
    $args['more'] = __( '% reviews', 'enlightenment' );

    return $args;
}
add_filter( 'enlightenment_comments_number_args', 'enlightenment_woocommerce_filter_comments_number_args' );

function enlightenment_woocommerce_filter_comment_form_fields( $fields, $commenter, $req ) {
    if ( ! is_singular( 'product' ) ) {
        return $fields;
    }

    unset( $fields['url'] );

    return $fields;
}
add_filter( 'enlightenment_comment_form_fields', 'enlightenment_woocommerce_filter_comment_form_fields', 10, 3 );

function enlightenment_woocommerce_filter_comment_form_defaults_args( $args ) {
    if ( ! is_singular( 'product' ) ) {
        return $args;
    }

    $args['label_text'] = _x( 'Review', 'noun', 'enlightenment' );

    return $args;
}
add_filter( 'enlightenment_comment_form_defaults_args', 'enlightenment_woocommerce_filter_comment_form_defaults_args' );

function enlightenment_woocommerce_filter_comment_form_defaults( $defaults ) {
    if ( ! is_singular( 'product' ) ) {
        return $defaults;
    }

    $account_page_url = wc_get_page_permalink( 'myaccount' );

    $defaults['must_log_in'] = str_replace(
        sprintf(
            /* translators: %s: Login URL. */
            __( 'You must be <a href="%s">logged in</a> to post a comment.', 'enlightenment' ),
            /** This filter is documented in wp-includes/link-template.php */
            wp_login_url( apply_filters( 'the_permalink', get_permalink( get_the_ID() ), get_the_ID() ) )
        ),
        sprintf(
            /* translators: %s: Login URL. */
            __( 'You must be <a href="%s">logged in</a> to post a review.', 'enlightenment' ),
            $account_page_url ? $account_page_url : wp_login_url( apply_filters( 'the_permalink', get_permalink( get_the_ID() ), get_the_ID() ) )
        ),
        $defaults['must_log_in']
    );

    /* translators: %s is product title */
    $defaults['title_reply'] = have_comments() ? esc_html__( 'Add a review', 'enlightenment' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'enlightenment' ), get_the_title() );

    // $defaults['comment_notes_before'] = '';

    if ( wc_review_ratings_enabled() ) {
        $star_rating = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . '</label><select name="rating" id="rating" required>
            <option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
            <option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
            <option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
            <option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
            <option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
            <option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
        </select></div>';

        $defaults['comment_field'] = sprintf( '%s %s', $star_rating, $defaults['comment_field'] );
    }

    $defaults['label_submit'] = __( 'Post Review', 'enlightenment' );

    return $defaults;
}
add_filter( 'enlightenment_comment_form_defaults', 'enlightenment_woocommerce_filter_comment_form_defaults' );

function enlightenment_woocommerce_filter_posts_nav_args( $args ) {
	if ( ! is_shop() && ! is_product_taxonomy() ) {
        return $args;
    }

	$args = wp_parse_args( apply_filters( 'woocommerce_pagination_args', array( // WPCS: XSS ok.
		'base'      => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
		'format'    => '',
		'add_args'  => false,
		'current'   => max( 1, wc_get_loop_prop( 'current_page' ) ),
		'total'     => wc_get_loop_prop( 'total_pages' ),
		'prev_text' => __( 'Next page', 'enlightenment' ),
		'next_text' => __( 'Previous page', 'enlightenment' ),
		'type'      => 'list',
		'end_size'  => 3,
		'mid_size'  => 3,
	) ), $args );

	$args['container_class']   .= ' woocommerce-pagination';
	$args['label_class']       .= ' screen-reader-text';
	$args['screen_reader_text'] = __( 'Products navigation', 'enlightenment' );
	$args['aria_label']         = __( 'Products', 'enlightenment' );
	$args['paged']              = true;

	if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
		$args['echo'] = false;
	}

	$args = apply_filters( 'enlightenment_woocommerce_pagination_args', $args );

	return $args;
}
add_filter( 'enlightenment_posts_nav_args', 'enlightenment_woocommerce_filter_posts_nav_args' );

add_action( 'woocommerce_before_mini_cart_contents', 'enlightenment_ob_start', 999 );

function enlightenment_woocommerce_filter_mini_cart_contents() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_woocommerce_filter_mini_cart_contents', $output );

    echo $output;
}
add_action( 'woocommerce_mini_cart_contents', 'enlightenment_woocommerce_filter_mini_cart_contents', 1 );

add_action( 'woocommerce_widget_shopping_cart_before_buttons', 'enlightenment_ob_start' );

function enlightenment_woocommerce_filter_widget_shopping_cart_buttons() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_woocommerce_filter_widget_shopping_cart_buttons', $output );

    echo $output;
}
add_action( 'woocommerce_widget_shopping_cart_after_buttons', 'enlightenment_woocommerce_filter_widget_shopping_cart_buttons' );

function enlightenment_woocommerce_filter_template_cart_coupon_form( $output ) {
	return str_replace( '<label for="coupon_code">', '<label for="coupon_code" class="screen-reader-text">', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_cart_cart_output', 'enlightenment_woocommerce_filter_template_cart_coupon_form', 8 );

add_action( 'woocommerce_checkout_before_customer_details', 'enlightenment_ob_start' );

function enlightenment_woocommerce_filter_checkout_customer_details() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_woocommerce_filter_checkout_customer_details', $output );

    echo $output;
}
add_action( 'woocommerce_checkout_after_customer_details', 'enlightenment_woocommerce_filter_checkout_customer_details' );

add_action( 'woocommerce_credit_card_form_start', 'enlightenment_ob_start' );

function enlightenment_woocommerce_filter_credit_card_form() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_woocommerce_filter_credit_card_form', $output );

    echo $output;
}
add_action( 'woocommerce_credit_card_form_end', 'enlightenment_woocommerce_filter_credit_card_form' );

function enlightenment_woocommerce_filter_paypal_payments_single_product_renderer_hook( $hook ) {
	return 'woocommerce_after_add_to_cart_form';
}
add_filter( 'woocommerce_paypal_payments_single_product_renderer_hook', 'enlightenment_woocommerce_filter_paypal_payments_single_product_renderer_hook' );

add_action( 'woocommerce_edit_account_form_start', 'enlightenment_ob_start' );

function enlightenment_woocommerce_filter_edit_account_form() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_woocommerce_filter_edit_account_form', $output );

    echo $output;
}
add_action( 'woocommerce_edit_account_form_end', 'enlightenment_woocommerce_filter_edit_account_form' );

add_action( 'woocommerce_login_form_start', 'enlightenment_ob_start' );

function enlightenment_woocommerce_filter_login_form() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_woocommerce_filter_login_form', $output );

    echo $output;
}
add_action( 'woocommerce_login_form_end', 'enlightenment_woocommerce_filter_login_form' );

add_action( 'woocommerce_register_form_start', 'enlightenment_ob_start' );

function enlightenment_woocommerce_filter_register_form() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_woocommerce_filter_register_form', $output );

    echo $output;
}
add_action( 'woocommerce_register_form_end', 'enlightenment_woocommerce_filter_register_form' );

function enlightenment_woocommerce_filter_shortcode_tag_products( $output, $atts ) {
	if ( ! is_array( $atts ) ) {
		$atts = array();
	}

	if ( ! isset( $atts['columns'] ) || ! absint( $atts['columns'] ) ) {
		$columns = wc_get_default_products_per_row();

		if ( false === strpos( $output, sprintf( ' columns-%s ', $columns ) ) ) {
			$columns = 4;
		}

		$atts['columns'] = $columns;
	}

	if ( ! isset( $atts['class'] ) ) {
		$atts['class'] = '';
	}

	$columns = absint( $atts['columns'] );
	$classes = array(
		'woocommerce',
		sprintf( 'columns-%s', $columns ),
		$atts['class'],
	);

	$output = str_replace(
		sprintf( '<div class="%s">', esc_attr( implode( ' ', $classes ) ) ),
		sprintf( '<div class="%s">', esc_attr( implode( ' ', array_merge(
			$classes,
			array( 'woocommerce-products-shortcode' )
		) ) ) ),
		$output
	);

	return $output;
}
add_filter( 'enlightenment_filter_shortcode_tag_products',              'enlightenment_woocommerce_filter_shortcode_tag_products', 10, 2 );
add_filter( 'enlightenment_filter_shortcode_tag_featured_products',     'enlightenment_woocommerce_filter_shortcode_tag_products', 10, 2 );
add_filter( 'enlightenment_filter_shortcode_tag_sale_products',         'enlightenment_woocommerce_filter_shortcode_tag_products', 10, 2 );
add_filter( 'enlightenment_filter_shortcode_tag_best_selling_products', 'enlightenment_woocommerce_filter_shortcode_tag_products', 10, 2 );
add_filter( 'enlightenment_filter_shortcode_tag_recent_products',       'enlightenment_woocommerce_filter_shortcode_tag_products', 10, 2 );
add_filter( 'enlightenment_filter_shortcode_tag_product_attribute',     'enlightenment_woocommerce_filter_shortcode_tag_products', 10, 2 );
add_filter( 'enlightenment_filter_shortcode_tag_top_rated_products',    'enlightenment_woocommerce_filter_shortcode_tag_products', 10, 2 );

function enlightenment_woocommerce_filter_shortcode_tag_related_products( $output ) {
	return str_replace( 'class="related products"', 'class="related products woocommerce-related-products-shortcode"', $output );
}
add_filter( 'enlightenment_filter_shortcode_tag_related_products', 'enlightenment_woocommerce_filter_shortcode_tag_related_products' );

function enlightenment_woocommerce_filter_shortcode_tag_product_category( $output, $atts ) {
	if ( ! is_array( $atts ) ) {
		$atts = array();
	}

	if ( ! isset( $atts['columns'] ) || ! absint( $atts['columns'] ) ) {
		$columns = wc_get_default_products_per_row();

		if ( false === strpos( $output, sprintf( ' columns-%s ', $columns ) ) ) {
			$columns = 4;
		}

		$atts['columns'] = $columns;
	}

	if ( ! isset( $atts['class'] ) ) {
		$atts['class'] = '';
	}

	$columns = absint( $atts['columns'] );
	$classes = array(
		'woocommerce',
		sprintf( 'columns-%s', $columns ),
		$atts['class'],
	);

	$output = str_replace(
		sprintf( '<div class="%s">', esc_attr( implode( ' ', $classes ) ) ),
		sprintf( '<div class="%s">', esc_attr( implode( ' ', array_merge(
			$classes,
			array( 'woocommerce-product-category-shortcode' )
		) ) ) ),
		$output
	);

	return $output;
}
add_filter( 'enlightenment_filter_shortcode_tag_product_category', 'enlightenment_woocommerce_filter_shortcode_tag_product_category', 10, 2 );

function enlightenment_woocommerce_filter_shortcode_tag_product_categories( $output, $atts ) {
	if ( ! is_array( $atts ) ) {
		$atts = array();
	}

	if ( ! isset( $atts['columns'] ) ) {
		$atts['columns'] = 4;
	}

	$columns = absint( $atts['columns'] );
	$classes = array(
		'woocommerce',
		sprintf( 'columns-%s', $columns ),
	);

	$output = str_replace(
		sprintf( 'class="woocommerce columns-%d"',    $columns ),
		sprintf( 'class="woocommerce columns-%d %s"', $columns, 'woocommerce-product-categories-shortcode' ),
		$output
	);

	return $output;
}
add_filter( 'enlightenment_filter_shortcode_tag_product_categories', 'enlightenment_woocommerce_filter_shortcode_tag_product_categories', 10, 2 );

function enlightenment_woocommerce_filter_shortcode_tag_product_page( $output ) {
	return str_replace( 'class="woocommerce"', 'class="woocommerce woocommerce-product-page-shortcode"', $output );
}
add_filter( 'enlightenment_filter_shortcode_tag_product_page', 'enlightenment_woocommerce_filter_shortcode_tag_product_page' );

function enlightenment_woocommerce_filter_shortcode_tag_add_to_cart( $output ) {
	return str_replace( 'class="product woocommerce add_to_cart_inline ', 'class="product woocommerce add_to_cart_inline woocommerce-add-to-cart-shortcode ', $output );
}
add_filter( 'enlightenment_filter_shortcode_tag_add_to_cart', 'enlightenment_woocommerce_filter_shortcode_tag_add_to_cart' );

function enlightenment_woocommerce_filter_shortcode_tag_shop_messages( $output ) {
	return str_replace( 'class="woocommerce"', 'class="woocommerce woocommerce-shop-messages-shortcode"', $output );
}
add_filter( 'enlightenment_filter_shortcode_tag_shop_messages', 'enlightenment_woocommerce_filter_shortcode_tag_shop_messages' );
add_filter( 'enlightenment_filter_shortcode_tag_woocommerce_messages', 'enlightenment_woocommerce_filter_shortcode_tag_woocommerce_messages' );

function enlightenment_woocommerce_filter_shortcode_tag_woocommerce_cart( $output ) {
	return str_replace( 'class="woocommerce"', 'class="woocommerce woocommerce-cart-shortcode"', $output );
}
add_filter( 'enlightenment_filter_shortcode_tag_woocommerce_cart', 'enlightenment_woocommerce_filter_shortcode_tag_woocommerce_cart' );

function enlightenment_woocommerce_filter_shortcode_tag_woocommerce_checkout( $output ) {
	return str_replace( 'class="woocommerce"', 'class="woocommerce woocommerce-checkout-shortcode"', $output );
}
add_filter( 'enlightenment_filter_shortcode_tag_woocommerce_checkout', 'enlightenment_woocommerce_filter_shortcode_tag_woocommerce_checkout' );

function enlightenment_woocommerce_filter_shortcode_tag_woocommerce_my_account( $output ) {
	return str_replace( 'class="woocommerce"', 'class="woocommerce woocommerce-my-account-shortcode"', $output );
}
add_filter( 'enlightenment_filter_shortcode_tag_woocommerce_my_account', 'enlightenment_woocommerce_filter_shortcode_tag_woocommerce_my_account' );

function enlightenment_woocommerce_filter_shortcode_tag_woocommerce_order_tracking( $output ) {
	return str_replace( 'class="woocommerce"', 'class="woocommerce woocommerce-order-tracking-shortcode"', $output );
}
add_filter( 'enlightenment_filter_shortcode_tag_woocommerce_order_tracking', 'enlightenment_woocommerce_filter_shortcode_tag_woocommerce_order_tracking' );

function enlightenment_woocommerce_blocks_product_grid_item_html( $output, $data, $product ) {
	if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
        return $output;
    }

	$permalink_tag = sprintf( '<a href="%s" class="wc-block-grid__product-link">', $data->permalink );

	if ( $offset = strpos( $output, $permalink_tag ) ) {
        $output = substr_replace( $output, '', $offset, strlen( $permalink_tag ) );
        $offset = strpos( $output, '</a>', $offset );
        $output = substr_replace( $output, '', $offset, 4 );
    }

	if ( ! empty( $data->image ) ) {
        $image = $data->image;
		$image = str_replace( '<div class="wc-block-grid__product-image">', '<div class="wc-block-grid__product-image entry-media">', $image );
        $image = str_replace( '<img ', sprintf( '<a href="%s" title="%s"><img ', get_permalink( $product->get_id() ), esc_attr( $product->get_name() ) ), $image );
	    $image = str_replace( '</div>', '</a></div>', $image );

		$output = str_replace( $data->image, $image, $output );
	}

	if ( ! empty( $data->title ) ) {
		$title = $data->title;
		$title = str_replace( '<div class="wc-block-grid__product-title">', sprintf( '<div class="wc-block-grid__product-title"><a href="%s" title="%s">', get_permalink( $product->get_id() ), esc_attr( $product->get_name() ) ), $title );
	    $title = str_replace( '</div>', '</a></div>', $title );

		$output = str_replace( $data->title, $title, $output );
	}

	/*if ( ! empty( $data->price ) && ! empty( $data->rating ) ) {
		$output = str_replace( $data->rating, '', $output );
		$output = str_replace( $data->price, $data->rating . "\n" . $data->price, $output );
	}*/

    return $output;
}
add_filter( 'woocommerce_blocks_product_grid_item_html', 'enlightenment_woocommerce_blocks_product_grid_item_html', 10, 3 );

function enlightenment_woocommerce_filter_product_rating_block( $output ) {
	$content = $output;

	$start   = strpos( $content, '<div data-block-name="woocommerce/product-rating" ' );
	if ( false !== $start ) {
		$end     = strpos( $content, '>', $start ) + 1;
		$length  = $end - $start;
		$content = substr_replace( $content, '', $start, $length );

		$start   = strrpos( $content, '</div>' );
		if ( false !== $start ) {
			$content = substr_replace( $content, '', $start, 6 );
		}
	}

	if ( '' === trim( $content ) ) {
		return '';
	}

	return $output;
}
add_filter( 'enlightenment_render_block_woocommerce_product_rating', 'enlightenment_woocommerce_filter_product_rating_block' );

function enlightenment_woocommerce_filter_currency_switcher_block( $output ) {
	return sprintf( '<div class="wp-block-currency-switcher wc-block-currency-switcher">%s', $output );
}
add_filter( 'enlightenment_render_block_woocommerce_payments_multi_currency_switcher', 'enlightenment_woocommerce_filter_currency_switcher_block' );

function enlightenment_custom_query_widget_product_meta_args( $args ) {
	if ( 'product' == get_post_type() ) {
        global $product;

        $price_html = $product->get_price_html();

        $args['format'] = $price_html ? sprintf( '<span class="price">%s</span>', $price_html ) : '';
	}

	return $args;
}
add_filter( 'enlightenment_custom_query_widget_entry_meta_args', 'enlightenment_custom_query_widget_product_meta_args' );

function enlightenment_woocommerce_filter_currency_switcher_widget( $output ) {
	$output = str_replace( 'class="widget ', 'class="widget woocommerce widget_currency_switcher ', $output );
	$output = str_replace( 'class="widget"', 'class="widget woocommerce widget_currency_switcher"', $output );

	return $output;
}
add_filter( 'enlightenment_widget_currency_switcher_widget', 'enlightenment_woocommerce_filter_currency_switcher_widget' );

function enlightenment_woocommerce_filter_template_addons_addon_start_output( $output, $template_name, $template_path, $located, $args ) {
	$type    = str_replace( '_', '-', $args['addon']['type'] );
	$classes = array( sprintf( 'wc-pao-addon-type-%s', esc_attr( $type ) ) );

	if ( 'multiple-choice' == $type && isset( $args['addon']['display'] ) ) {
		$classes[] = sprintf( 'wc-pao-addon-display-%s', esc_attr( $args['addon']['display'] ) );
	}

	return str_replace( 'class="wc-pao-addon-container ', sprintf( 'class="wc-pao-addon-container %s ', join( ' ', $classes ) ), $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_addon_start_output', 'enlightenment_woocommerce_filter_template_addons_addon_start_output', 10, 5 );

add_action( 'woocommerce_product_addons_end', 'enlightenment_ob_start', 8 );

function enlightenment_woocommerce_filter_product_addons_total() {
    $output = ob_get_clean();
    $output = apply_filters( 'enlightenment_woocommerce_filter_product_addons_total', $output );

    echo $output;
}
add_action( 'woocommerce_product_addons_end', 'enlightenment_woocommerce_filter_product_addons_total', 12 );
