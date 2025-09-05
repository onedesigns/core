<?php

/**
 * Scripts and styles.
 */
function enlightenment_woocommerce_bootstrap_dequeue_styles() {
	wp_dequeue_style( 'woocommerce-layout' );
	wp_dequeue_style( 'woocommerce-smallscreen' );
	wp_dequeue_style( 'woocommerce-general' );
    wp_dequeue_style( 'wc-braintree' );
    wp_dequeue_style( 'mollie-components' );
	wp_dequeue_style( 'wc-square' );
	wp_dequeue_style( 'kco' );
	wp_dequeue_style( 'wc-bookings-styles' );
}
add_action( 'wp_enqueue_scripts', 'enlightenment_woocommerce_bootstrap_dequeue_styles', 12 );

function enlightenment_woocommerce_bootstrap_unset_styles( $styles ) {
	unset( $styles['woocommerce-layout'] );
	unset( $styles['woocommerce-smallscreen'] );
	unset( $styles['woocommerce-general'] );
	unset( $styles['woocommerce-blocktheme'] );

	return $styles;
}
add_filter( 'woocommerce_enqueue_styles', 'enlightenment_woocommerce_bootstrap_unset_styles' );

function enlightenment_woocommerce_bootstrap_user_account_args( $args ) {
	$args['toggle_class'] .= ' dropdown-toggle btn btn-link p-0 border-0 rounded-0';

	$args['toggle_extra_atts']['data-bs-toggle'] = 'dropdown';

	$args['dropdown_menu_tag'] = 'ul';

	$args['dropdown_menu_class'] .= ' dropdown-menu dropdown-menu-end';

	return $args;
}
add_filter( 'enlightenment_woocommerce_user_account_args', 'enlightenment_woocommerce_bootstrap_user_account_args' );

function enlightenment_woocommerce_bootstrap_user_account_nav( $output ) {
	$output = str_replace( '<ul class="nav">', '', $output );
	$output = str_replace( '</ul>', '', $output );
	$output = str_replace( ' nav-item"', '"', $output );
	$output = str_replace( '"nav-link"', '"dropdown-item"', $output );
	$output = str_replace( '"nav-link active"', '"dropdown-item active"', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_user_account_nav', 'enlightenment_woocommerce_bootstrap_user_account_nav' );

function enlightenment_woocommerce_bootstrap_user_account_login_form( $output ) {
	return str_replace( 'col-md-6', 'col-12', $output );
}
add_filter( 'enlightenment_woocommerce_user_account_login_form', 'enlightenment_woocommerce_bootstrap_user_account_login_form' );

function enlightenment_woocommerce_bootstrap_user_account( $output, $args ) {
	return str_replace( esc_html( $args['toggle_label'] ), sprintf( '<i class="far fa-user-circle" aria-hidden="true" role="presentation"></i> <span class="screen-reader-text visually-hidden">%s</span>', esc_html( $args['toggle_label'] ) ), $output );
}
add_filter( 'enlightenment_woocommerce_user_account', 'enlightenment_woocommerce_bootstrap_user_account', 10, 2 );

function enlightenment_bootstrap_shopping_cart_args( $args ) {
	$dropdown = ! apply_filters( 'woocommerce_widget_cart_is_hidden', is_cart() || is_checkout() );

	$args['count_class'] .= ' badge text-bg-light text-dark';

	if ( $dropdown ) {
		$args['container_class']     .= ' dropdown';
		$args['link_class']          .= ' dropdown-toggle';
		$args['link_extra_atts']      = array_merge( $args['link_extra_atts'], array(
			'data-bs-toggle' => 'dropdown',
		) );
		$args['cart_contents_class'] .= ' dropdown-menu dropdown-menu-end';
	}

	$args['link_class'] .= ' btn btn-link p-0 border-0 rounded-0';

	return $args;
}
add_filter( 'enlightenment_shopping_cart_args', 'enlightenment_bootstrap_shopping_cart_args' );

function enlightenment_bootstrap_shopping_cart( $output, $args ) {
	$output = str_replace( esc_html( $args['link_label'] ), sprintf( '<i class="fas fa-shopping-cart" aria-hidden="true" role="presentation"></i> <span class="screen-reader-text visually-hidden">%s</span>', esc_html( $args['link_label'] ) ), $output );
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );

	return $output;
}
add_filter( 'enlightenment_shopping_cart', 'enlightenment_bootstrap_shopping_cart', 10, 2 );

function enlightenment_bootstrap_currency_switcher_args( $args ) {
	$args['container_class'] .= ' dropdown';

	return $args;
}
add_filter( 'enlightenment_currency_switcher_args', 'enlightenment_bootstrap_currency_switcher_args' );

function enlightenment_bootstrap_woopayments_currency_switcher_args( $args ) {
	$args['container_class']              .= ' dropdown';
	$args['toggle_class']                 .= ' dropdown-toggle btn btn-link p-0 border-0 rounded-0';
	$args['dropdown_menu_class']          .= ' dropdown-menu dropdown-menu-end';
	$args['dropdown_button_class']        .= ' dropdown-item';
	$args['dropdown_button_active_class'] .= ' active';
	$args['dropdown_button_active_class']  = trim( $args['dropdown_button_active_class'] );

	$args['toggle_extra_atts']['data-bs-toggle'] = 'dropdown';

	return $args;
}
add_filter( 'enlightenment_woopayments_currency_switcher_args', 'enlightenment_bootstrap_woopayments_currency_switcher_args' );

function enlightenment_woocommerce_bootstrap_breadcrumb_args( $args ) {
    $args['delimiter']   = '';
    $args['wrap_before'] = '<nav class="woocommerce-breadcrumb breadcrumbs" aria-label="breadcrumb"><ol class="breadcrumb">';
    $args['wrap_after']  = '</ol></nav>';
    $args['before']      = '<li class="breadcrumb-item">';
    $args['after']       = '</li>';

    return $args;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'enlightenment_woocommerce_bootstrap_breadcrumb_args' );

function enlightenment_woocommerce_bootstrap_template_notices_error_output( $output ) {
	$output = str_replace( 'class="wc-block-components-notice-banner is-error"', 'class="alert alert-danger d-flex align-items-baseline gap-2"', $output );
	$output = str_replace( 'class="wc-block-components-notice-banner__content"', 'class="wc-block-components-notice-banner__content flex-grow-1 d-flex flex-column flex-md-row flex-wrap align-items-baseline gap-1"', $output );
    $output = str_replace( '<ul class="woocommerce-error"', '<ul class="woocommerce-error alert alert-danger list-unstyled"', $output );
	$output = str_replace( '<li>', '<li class="d-flex flex-wrap align-items-center">', $output );
	$output = str_replace( 'class="button ', 'class="button btn btn-outline-danger order-1 ms-md-auto ', $output );
    $output = str_replace( 'class="button"', 'class="button btn btn-outline-danger order-1 ms-md-auto"', $output );
	$output = str_replace( 'class="showlogin"', 'class="showlogin btn btn-outline-danger order-1 ms-auto"', $output );

	$start = strpos( $output, '<svg ' );
	if ( false !== $start ) {
		$end = strpos( $output, '</svg>', $start ) + 6;
		$length = $end - $start;
		$output = substr_replace( $output, '<i class="fas fa-times-circle" aria-hidden="true" role="presentation"></i>', $start, $length );
	}

	$offset = strpos( $output, 'class="wc-block-components-notice-banner__summary"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' w-100 mb-0', $offset + 49, 0 );
		$offset = strpos( $output, '<ul>', $offset );
		$output = substr_replace( $output, ' class="w-100"', $offset + 3, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_notices_error_output', 'enlightenment_woocommerce_bootstrap_template_notices_error_output' );

function enlightenment_woocommerce_bootstrap_template_notices_success_output( $output ) {
	$output = str_replace( 'class="wc-block-components-notice-banner is-success"', 'class="alert alert-success d-flex align-items-baseline gap-2"', $output );
	$output = str_replace( 'class="wc-block-components-notice-banner__content"', 'class="wc-block-components-notice-banner__content flex-grow-1 d-flex flex-column flex-md-row flex-wrap align-items-baseline gap-1"', $output );
    $output = str_replace( '<div class="woocommerce-message"', '<div class="woocommerce-message alert alert-success d-flex align-items-center"', $output );
	$output = str_replace( 'class="button ', 'class="button btn btn-outline-success order-1 ms-md-auto ', $output );
    $output = str_replace( 'class="button"', 'class="button btn btn-outline-success order-1 ms-md-auto"', $output );
    $output = str_replace( 'class="wishlist-message-dismiss"', 'class="wishlist-message-dismiss btn btn-outline-success order-1 ms-md-auto"', $output );

	$start = strpos( $output, '<svg ' );
	while ( false !== $start ) {
		$end = strpos( $output, '</svg>', $start ) + 6;
		$length = $end - $start;
		$output = substr_replace( $output, '<i class="fas fa-check-circle" aria-hidden="true" role="presentation"></i>', $start, $length );

		$start = strpos( $output, '<svg ' );
	}

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_notices_success_output', 'enlightenment_woocommerce_bootstrap_template_notices_success_output' );

function enlightenment_woocommerce_bootstrap_template_notices_notice_output( $output ) {
	$output = str_replace( 'class="wc-block-components-notice-banner is-info"', 'class="alert alert-info d-flex align-items-baseline gap-2"', $output );
	$output = str_replace( 'class="wc-block-components-notice-banner__content"', 'class="wc-block-components-notice-banner__content flex-grow-1 d-flex flex-column flex-md-row flex-wrap align-items-baseline gap-1"', $output );
    $output = str_replace( '<div class="woocommerce-info"', '<div class="woocommerce-info alert alert-info d-flex flex-column flex-md-row flex-wrap align-items-baseline gap-1"', $output );
    $output = str_replace( 'class="showlogin"', 'class="showlogin btn btn-outline-info order-1 ms-md-auto"', $output );
	$output = str_replace( 'class="button ', 'class="button btn btn-outline-info order-1 ms-md-auto ', $output );
    $output = str_replace( 'class="button"', 'class="button btn btn-outline-info order-1 ms-md-auto"', $output );
	$output = str_replace( ' button"', ' btn btn-outline-info order-1 ms-md-auto button"', $output );

	$start = strpos( $output, '<svg ' );
	while ( false !== $start ) {
		$end = strpos( $output, '</svg>', $start ) + 6;
		$length = $end - $start;
		$output = substr_replace( $output, '<i class="fas fa-info-circle" aria-hidden="true" role="presentation"></i>', $start, $length );

		$start = strpos( $output, '<svg ' );
	}

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_notices_notice_output', 'enlightenment_woocommerce_bootstrap_template_notices_notice_output' );

function enlightenment_woocommerce_bootstrap_no_available_payment_methods_message( $output ) {
	return sprintf( '<span>%s</span>', $output );
}
add_filter( 'woocommerce_no_available_payment_methods_message', 'enlightenment_woocommerce_bootstrap_no_available_payment_methods_message', 12 );


function enlightenment_woocommerce_bootstrap_removed_from_cart_notice_text( $translated_text, $text, $domain ) {
	if ( $domain != 'woocommerce' ) {
		return $translated_text;
	}

	if ( $text != '%1$s has been removed from your cart because it has since been modified. You can add it back to your cart <a href="%2$s">here</a>.' ) {
		return $translated_text;
	}

	$translated_text = sprintf(
		'%s %s',
		__( '%1$s has been removed from your cart because it has since been modified.', 'enlightenment' ),
		sprintf( '<a href="%s" class="btn btn-outline-info ms-auto">%s</a>', '%2$s', __( 'Add back to cart', 'enlightenment' ) )
	);

	return $translated_text;
}
add_filter( 'gettext', 'enlightenment_woocommerce_bootstrap_removed_from_cart_notice_text', 10, 3 );

function enlightenment_woocommerce_bootstrap_template_global_breadcrumb_output( $output, $template_name, $template_path, $located, $args ) {
    $last   = array_values( array_slice( $args['breadcrumb'], -1 ) )[0][0];
    $output = str_replace( sprintf( '<li class="breadcrumb-item">%s</li>', $last ), sprintf( '<li class="breadcrumb-item active" aria-current="page">%s</li>', $last ), $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_global_breadcrumb_output', 'enlightenment_woocommerce_bootstrap_template_global_breadcrumb_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_global_quantity_input_output( $output, $template_name, $template_path, $located, $args ) {
	$classes = (array) $args['classes'];

    $output = str_replace( '<label class="screen-reader-text" for="', '<label class="screen-reader-text visually-hidden" for="', $output );
	$output = str_replace(
		sprintf( 'class="%s"', esc_attr( join( ' ', $classes ) ) ),
		sprintf( 'class="%s"', esc_attr( join( ' ', array_merge( $classes, array( 'form-control' ) ) ) ) ),
		$output
	);

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_global_quantity_input_output', 'enlightenment_woocommerce_bootstrap_template_global_quantity_input_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_loop_orderby_output( $output ) {
	return str_replace( 'class="orderby"', 'class="orderby form-select w-auto"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_loop_orderby_output', 'enlightenment_woocommerce_bootstrap_template_loop_orderby_output' );

function enlightenment_woocommerce_bootstrap_template_loop_loop_start_output( $output ) {
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

    return str_replace( sprintf( '<ul class="products columns-%s">', esc_attr( wc_get_loop_prop( 'columns' ) ) ), sprintf( '<div class="products columns-%s"><div class="row">', esc_attr( wc_get_loop_prop( 'columns' ) ) ), $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_loop_loop_start_output', 'enlightenment_woocommerce_bootstrap_template_loop_loop_start_output' );

function enlightenment_woocommerce_bootstrap_template_loop_loop_end_output( $output ) {
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

    return str_replace( '</ul>', '</div></div>', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_loop_loop_end_output', 'enlightenment_woocommerce_bootstrap_template_loop_loop_end_output' );

function enlightenment_bootstrap_grid_loop_product_categories_html( $output ) {
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

    return sprintf( '<div class="row">%s</div>', $output );
}
add_filter( 'enlightenment_product_categories_html', 'enlightenment_bootstrap_grid_loop_product_categories_html' );

function enlightenment_woocommerce_bootstrap_template_content_product_cat_output( $output ) {
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

    $grids   = enlightenment_current_grid();
    $classes = array();

    foreach ( $grids as $breakpoint => $grid ) {
        if( 'inherit' == $grid ) {
            continue;
        }

        $atts   = enlightenment_get_grid( $grid );
        $prefix = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );

        $classes[] = sprintf( $atts['entry_class'], $prefix );
    }

    $class = join( ' ', $classes );

    return sprintf( '<div class="%s">%s</div>', $class, $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_content_product_cat_output', 'enlightenment_woocommerce_bootstrap_template_content_product_cat_output' );

function enlightenment_woocommerce_bootstrap_template_single_product_rating_output( $output ) {
    return str_replace( 'href="#reviews"', 'href="#tab-reviews"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_rating_output', 'enlightenment_woocommerce_bootstrap_template_single_product_rating_output' );

function enlightenment_woocommerce_bootstrap_template_single_product_price_output( $output ) {
    return str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_price_output', 'enlightenment_woocommerce_bootstrap_template_single_product_price_output' );

function enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_simple_output( $output ) {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Add_To_Cart',
		),
	) ) ) {
		return $output;
	}

	$output = str_replace( 'class="cart"', 'class="cart d-flex flex-wrap align-items-center gap-4"', $output );
    $output = str_replace( 'class="single_add_to_cart_button ', 'class="single_add_to_cart_button btn btn-primary btn-lg ', $output );

	$output = str_replace( 'id="wc-stripe-payment-request-button-separator"', 'id="wc-stripe-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wcpay-payment-request-button-separator"', 'id="wcpay-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wc-stripe-payment-request-wrapper"', 'id="wc-stripe-payment-request-wrapper" class="w-100 order-2"', $output );
	$output = str_replace( 'id="wcpay-payment-request-wrapper"', 'id="wcpay-payment-request-wrapper" class="w-100 order-2"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_add_to_cart_simple_output', 'enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_simple_output' );

function enlightenment_woocommerce_bootstrap_dropdown_variation_attribute_options_args( $args ) {
	$args['class'] = isset( $args['class'] ) ? $args['class'] : '';
	$args['class'] = trim( sprintf( '%s form-select d-inline-block w-auto', $args['class'] ) );

	return $args;
}
add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'enlightenment_woocommerce_bootstrap_dropdown_variation_attribute_options_args' );

function enlightenment_woocommerce_bootstrap_reset_variations_link( $output ) {
	return str_replace( 'class="reset_variations"', 'class="reset_variations btn btn-link"', $output );
}
add_filter( 'woocommerce_reset_variations_link', 'enlightenment_woocommerce_bootstrap_reset_variations_link' );

function enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_variable_output( $output ) {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Add_To_Cart',
		),
	) ) ) {
		return $output;
	}

	$offset = strpos( $output, '<td class="value">' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="d-flex flex-wrap align-items-center row-gap-2 column-gap-3">', $offset + 1, 0 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );

		$offset = strpos( $output, '<td class="value">', $offset );
	}

    $output = str_replace( 'class="variations_form cart"', 'class="variations_form cart d-flex flex-wrap align-items-center gap-3"', $output );
    $output = str_replace( 'class="stock out-of-stock"', 'class="stock out-of-stock text-danger mb-0"', $output );
    $output = str_replace( 'class="variations"', 'class="variations w-100"', $output );
    $output = str_replace( 'class="single_variation_wrap"', 'class="single_variation_wrap d-flex flex-column w-100"', $output );
    $output = str_replace( 'class="woocommerce-variation-add-to-cart variations_button"', 'class="woocommerce-variation-add-to-cart variations_button d-flex flex-wrap align-items-center gap-4"', $output );
    $output = str_replace( 'class="single_add_to_cart_button ', 'class="single_add_to_cart_button btn btn-primary btn-lg ', $output );

	$output = str_replace( 'id="wc-stripe-payment-request-button-separator"', 'id="wc-stripe-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wcpay-payment-request-button-separator"', 'id="wcpay-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wc-stripe-payment-request-wrapper"', 'id="wc-stripe-payment-request-wrapper" class="w-100 order-2"', $output );
	$output = str_replace( 'id="wcpay-payment-request-wrapper"', 'id="wcpay-payment-request-wrapper" class="w-100 order-2"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_add_to_cart_variable_output', 'enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_variable_output' );

function enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_grouped_output( $output ) {
    $output = str_replace( 'class="cart grouped_form"', 'class="cart grouped_form d-flex flex-wrap align-items-center column-gap-4 row-gap-3"', $output );
    $output = str_replace( 'class="woocommerce-grouped-product-list group_table"', 'class="woocommerce-grouped-product-list group_table w-100"', $output );
    $output = str_replace( 'class="single_add_to_cart_button ', 'class="single_add_to_cart_button btn btn-primary btn-lg ', $output );

	$output = str_replace( 'id="wc-stripe-payment-request-button-separator"', 'id="wc-stripe-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wcpay-payment-request-button-separator"', 'id="wcpay-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wc-stripe-payment-request-wrapper"', 'id="wc-stripe-payment-request-wrapper" class="w-100 order-2"', $output );
	$output = str_replace( 'id="wcpay-payment-request-wrapper"', 'id="wcpay-payment-request-wrapper" class="w-100 order-2"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_add_to_cart_grouped_output', 'enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_grouped_output' );

function enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_external_output( $output ) {
    $output = str_replace( 'class="cart"', 'class="cart d-flex flex-wrap align-items-center"', $output );
    $output = str_replace( 'class="single_add_to_cart_button ', 'class="single_add_to_cart_button btn btn-primary btn-lg ', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_add_to_cart_external_output', 'enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_external_output' );

function enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_subscription_output( $output ) {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Add_To_Cart',
		),
	) ) ) {
		return $output;
	}

	$output = str_replace( 'class="cart"', 'class="cart d-flex flex-wrap align-items-center gap-4"', $output );
    $output = str_replace( 'class="single_add_to_cart_button ', 'class="single_add_to_cart_button btn btn-primary btn-lg ', $output );

	$output = str_replace( 'id="wc-stripe-payment-request-button-separator"', 'id="wc-stripe-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wcpay-payment-request-button-separator"', 'id="wcpay-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wc-stripe-payment-request-wrapper"', 'id="wc-stripe-payment-request-wrapper" class="w-100 order-2"', $output );
	$output = str_replace( 'id="wcpay-payment-request-wrapper"', 'id="wcpay-payment-request-wrapper" class="w-100 order-2"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_add_to_cart_subscription_output', 'enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_subscription_output' );

function enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_variable_subscription_output( $output ) {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Add_To_Cart',
		),
	) ) ) {
		return $output;
	}

	$offset = strpos( $output, '<td class="value">' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="d-flex flex-wrap align-items-center gap-3">', $offset + 1, 0 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );

		$offset = strpos( $output, '<td class="value">', $offset );
	}

    $output = str_replace( 'class="variations"', 'class="variations w-100 mb-3"', $output );
    $output = str_replace( 'class="woocommerce-variation-add-to-cart variations_button"', 'class="woocommerce-variation-add-to-cart variations_button d-flex flex-wrap align-items-center gap-4"', $output );
    $output = str_replace( 'class="single_add_to_cart_button ', 'class="single_add_to_cart_button btn btn-primary btn-lg ', $output );

	$output = str_replace( 'id="wc-stripe-payment-request-button-separator"', 'id="wc-stripe-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wcpay-payment-request-button-separator"', 'id="wcpay-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wc-stripe-payment-request-wrapper"', 'id="wc-stripe-payment-request-wrapper" class="w-100 order-2"', $output );
	$output = str_replace( 'id="wcpay-payment-request-wrapper"', 'id="wcpay-payment-request-wrapper" class="w-100 order-2"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_add_to_cart_variable_subscription_output', 'enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_variable_subscription_output' );

function enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_booking_output( $output ) {
	$output = str_replace( 'class="wc-bookings-booking-form"', 'class="wc-bookings-booking-form card mb-3"', $output );
	$output = str_replace( 'class="wc-bookings-booking-cost ', 'class="wc-bookings-booking-cost card-footer ', $output );
	$output = str_replace( 'class="wc-bookings-booking-cost"', 'class="wc-bookings-booking-cost card-footer"', $output );

	$offset = strpos( $output, 'id="wc-bookings-booking-form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="card-body">', $offset + 1, 0 );
		$offset = strpos( $output, '<div class="wc-bookings-booking-cost ', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

    $output = str_replace( 'class="wc-bookings-booking-form-button single_add_to_cart_button ', 'class="wc-bookings-booking-form-button single_add_to_cart_button btn btn-primary btn-lg ', $output );

	$output = str_replace( 'id="wc-stripe-payment-request-button-separator"', 'id="wc-stripe-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wcpay-payment-request-button-separator"', 'id="wcpay-payment-request-button-separator" class="w-100 order-1"', $output );
	$output = str_replace( 'id="wc-stripe-payment-request-wrapper"', 'id="wc-stripe-payment-request-wrapper" class="w-100 order-2"', $output );
	$output = str_replace( 'id="wcpay-payment-request-wrapper"', 'id="wcpay-payment-request-wrapper" class="w-100 order-2"', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_add_to_cart_booking_output', 'enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_booking_output' );

function enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_bundle_button_output( $output ) {
    return str_replace( 'class="single_add_to_cart_button bundle_add_to_cart_button ', 'class="single_add_to_cart_button bundle_add_to_cart_button btn btn-primary btn-lg ', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_add_to_cart_bundle_button_output', 'enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_bundle_button_output' );

function enlightenment_woocommerce_bootstrap_template_booking_form_number_output( $output, $template_name, $template_path, $located, $args ) {
	$output = str_replace( 'class="form-field form-field-wide ', 'class="form-field form-field-wide mb-3 ', $output );
	$output = str_replace( '<label ', '<label class="form-label" ', $output );
	$output = str_replace( 'type="number"', 'type="number" class="form-control"', $output );

	if ( ! empty( $args['field']['after'] ) ) {
		$after  = esc_html( $args['field']['after'] );
		$output = str_replace( $after, sprintf( '<span class="d-block form-text">%s</span>', $after ), $output );
	}

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_booking_form_number_output', 'enlightenment_woocommerce_bootstrap_template_booking_form_number_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_booking_form_select_output( $output ) {
	$output = str_replace( 'class="form-field form-field-wide ', 'class="form-field form-field-wide mb-3 ', $output );
	$output = str_replace( '<label ', '<label class="form-label" ', $output );
	$output = str_replace( '<select ', '<select class="form-select" ', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_booking_form_select_output', 'enlightenment_woocommerce_bootstrap_template_booking_form_select_output' );

function enlightenment_woocommerce_bootstrap_template_booking_form_date_picker_output( $output, $template_name, $template_path, $located, $args ) {
	$output = str_replace( '<br />', '', $output );

	if ( 'always_visible' !== $args['field']['display'] ) {
		$offset = strpos( $output, '<span class="label">' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
			$offset = strpos( $output, 'class="label"', $offset );
			$output = substr_replace( $output, ' screen-reader-text visually-hidden', $offset + 12, 0 );
			$offset = strpos( $output, '</span>', $offset );
			$output = substr_replace( $output, '', $offset, 7 );
			$offset = strpos( $output, ':', $offset );
			$output = substr_replace( $output, '</span>', $offset + 1, 0 );
			$offset = strpos( $output, '<div class="wc-bookings-date-picker-date-fields">', $offset );
			$offset = strpos( $output, '</div>', $offset );
			$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
		}
	}

	$offset = strpos( $output, '<div class="wc-bookings-date-picker-date-fields">' );
	while ( false !== $offset ) {
		if ( 'customer' === $args['field']['duration_type'] && $args['field']['is_range_picker_enabled'] ) {
			$offset = strpos( $output, '<span>', $offset );
			$output = substr_replace( $output, ' class="d-inline-block form-label"', $offset + 5, 0 );
			$offset = strpos( $output, '</span>', $offset );
		}

		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row gx-2">', $offset + 1, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );

		$offset = strpos( $output, '<div class="wc-bookings-date-picker-date-fields">', $offset );
	}

	$output = str_replace( '/ <label>', '<span class="col-auto"><span class="d-block col-form-label">/</span></span> <label>', $output );
	$output = str_replace( '<label>', '<label class="col mb-0">', $output );
	$output = str_replace( 'class="booking_date_month"', 'class="booking_date_month form-control"', $output );
	$output = str_replace( 'class="booking_date_day"', 'class="booking_date_day form-control"', $output );
	$output = str_replace( 'class="booking_date_year"', 'class="booking_date_year form-control"', $output );
	$output = str_replace( 'class="booking_to_date_month"', 'class="booking_to_date_month form-control"', $output );
	$output = str_replace( 'class="booking_to_date_day"', 'class="booking_to_date_day form-control"', $output );
	$output = str_replace( 'class="booking_to_date_year"', 'class="booking_to_date_year form-control"', $output );
	$output = str_replace( '<span>', '<span class="d-block form-text">', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_booking_form_date_picker_output', 'enlightenment_woocommerce_bootstrap_template_booking_form_date_picker_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_booking_form_datetime_picker_output( $output, $template_name, $template_path, $located, $args ) {
	$output = str_replace( 'class="wc-bookings-date-picker-timezone-block"', 'class="wc-bookings-date-picker-timezone-block alert alert-info"', $output );
	$output = str_replace( ' align="center"', '', $output );

	if ( 'always_visible' !== $args['field']['display'] ) {
		$offset = strpos( $output, '<span class="label">' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
			$offset = strpos( $output, 'class="label"', $offset );
			$output = substr_replace( $output, ' d-inline-block form-label', $offset + 12, 0 );
			$offset = strpos( $output, '</span>', $offset );
			$output = substr_replace( $output, '', $offset, 7 );
			$offset = strpos( $output, ':', $offset );
			$output = substr_replace( $output, '</span>', $offset + 1, 0 );
			$offset = strpos( $output, '<div class="wc-bookings-date-picker-date-fields">', $offset );
			$offset = strpos( $output, '</div>', $offset );
			$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
		}
	}

	$offset = strpos( $output, '<div class="wc-bookings-date-picker-date-fields">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, "\n" . '<div class="row gx-2">', $offset + 49, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$output = str_replace( '/ <label>', '<span class="col-auto"><span class="d-block col-form-label">/</span></span> <label>', $output );
	$output = str_replace( '<label>', '<label class="col mb-0">', $output );
	$output = str_replace( 'class="required_for_calculation ', 'class="required_for_calculation form-control ', $output );
	$output = str_replace( '<span>', '<span class="d-block form-text">', $output );

	$start = strpos( $output, '<div class="block-picker wc-bookings-time-block-picker">' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<p>', $offset );

		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="col-12 mb-0"', $offset + 2, 0 );
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, '<span class="d-block alert alert-info mb-0">', $offset + 1, 0 );
			$offset = strpos( $output, '</p>', $offset );
			$output = substr_replace( $output, '</span>', $offset, 0 );
		}
	}

	$output = str_replace( 'class="block-picker wc-bookings-time-block-picker"', 'class="block-picker wc-bookings-time-block-picker row gx-3"', $output );
	$output = str_replace( 'class="block-picker"', 'class="block-picker list-unstyled row g-0 mb-0"', $output );
	$output = str_replace( '<li>', '<li class="col-12"><div class="alert alert-info mb-0">', $output );
	$output = str_replace( '</li>', '</div></li>', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_booking_form_datetime_picker_output', 'enlightenment_woocommerce_bootstrap_template_booking_form_datetime_picker_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_bookings_get_end_time_html( $output ) {
	$output = str_replace( 'class="wc-bookings-end-time-container"', 'class="wc-bookings-end-time-container col-6 mb-0"', $output );
	$output = str_replace( 'for="wc-bookings-form-end-time"', 'for="wc-bookings-form-end-time" class="form-label"', $output );
	$output = str_replace( '<select ', '<select class="form-select" ', $output );

	return $output;
}
add_filter( 'wc_bookings_get_end_time_html', 'enlightenment_woocommerce_bootstrap_bookings_get_end_time_html' );

function enlightenment_woocommerce_bootstrap_bookings_get_time_slots_html( $output ) {
	$output = str_replace( 'class="wc-bookings-start-time-container"', 'class="wc-bookings-start-time-container col-6 mb-0"', $output );
	$output = str_replace( 'class="wc-bookings-end-time-container"', 'class="wc-bookings-end-time-container col-6 mb-0"', $output );
	$output = str_replace( '&nbsp;', '', $output );
	$output = str_replace( 'for="wc-bookings-form-start-time"', 'for="wc-bookings-form-start-time" class="form-label"', $output );
	$output = str_replace( '<select ', '<select class="form-select" ', $output );
	$output = str_replace( 'class="block"', 'class="block col-4"', $output );
	$output = str_replace( '<a ', '<a class="d-block p-1 text-center"', $output );

	return $output;
}
add_filter( 'wc_bookings_get_time_slots_html', 'enlightenment_woocommerce_bootstrap_bookings_get_time_slots_html' );

function enlightenment_woocommerce_bootstrap_template_booking_form_month_picker_output( $output, $template_name, $template_path, $located, $args ) {
	if ( 'always_visible' !== $args['field']['display'] ) {
		$offset = strpos( $output, '<span class="label">' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, ' d-inline-block mb-2', $offset + 18, 0 );
		}
	}

	$output = str_replace( 'class="block-picker"', 'class="block-picker list-unstyled row g-0 mb-0"', $output );
	$output = str_replace( '<li class="', '<li class="col-4 ', $output );
	$output = str_replace( '<a ', '<a class="d-block p-1 text-center"', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_booking_form_month_picker_output', 'enlightenment_woocommerce_bootstrap_template_booking_form_month_picker_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_bookings_calculated_booking_cost_error_output( $output ) {
	return str_replace( 'class="booking-error"', 'class="booking-error text-danger"', $output );
}
add_filter( 'woocommerce_bookings_calculated_booking_cost_error_output', 'enlightenment_woocommerce_bootstrap_bookings_calculated_booking_cost_error_output' );

function enlightenment_woocommerce_bootstrap_memberships_message_classes( $classes ) {
	$classes[] = 'alert';
	$classes[] = 'alert-info';

	return $classes;
}
add_filter( 'wc_memberships_message_classes', 'enlightenment_woocommerce_bootstrap_memberships_message_classes' );

function enlightenment_woocommerce_bootstrap_template_addons_addon_start_output( $output ) {
	$output = str_replace( 'class="wc-pao-addon-container ', 'class="wc-pao-addon-container w-100 ', $output );
	$output = str_replace( 'class="wc-pao-addon-description"', 'class="wc-pao-addon-description text-muted"', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_addon_start_output', 'enlightenment_woocommerce_bootstrap_template_addons_addon_start_output' );

function enlightenment_woocommerce_bootstrap_template_addons_addon_end_output( $output ) {
	return str_replace( '<div class="clear"></div>', '', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_addon_end_output', 'enlightenment_woocommerce_bootstrap_template_addons_addon_end_output' );

function enlightenment_woocommerce_bootstrap_template_addons_select_output( $output ) {
	return str_replace( 'class="wc-pao-addon-field wc-pao-addon-select"', 'class="wc-pao-addon-field wc-pao-addon-select form-select" style="min-width: auto;"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_select_output', 'enlightenment_woocommerce_bootstrap_template_addons_select_output' );

function enlightenment_woocommerce_bootstrap_template_addons_radiobutton_output( $output, $template_name, $template_path, $located, $args ) {
	$field_name = ! empty( $addon['field_name'] ) ? $addon['field_name'] : '';
	$addon_key  = 'addon-' . sanitize_title( $field_name );

	if ( empty( $args['addon']['required'] ) ) {
		$option_id = $addon_key . '-none';

		$offset = strpos( $output, '<label>' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, '', $offset, 7 );
			$offset = strpos( $output, 'class="wc-pao-addon-field ', $offset );
			$output = substr_replace( $output, sprintf( 'id="%s" ', $option_id ), $offset, 0 );
			$offset = strpos( $output, '/>', $offset );
			$output = substr_replace( $output, sprintf( ' <label for="%s">', $option_id ), $offset, 0 );
		}
	}

	$output = str_replace( '<div class="wc-pao-addon-', '<div class="form-check wc-pao-addon-', $output );
	$output = str_replace( 'class="wc-pao-addon-field wc-pao-addon-radio"', 'class="wc-pao-addon-field wc-pao-addon-radio form-check-input"', $output );
	$output = str_replace( '<label for="', '<label class="form-check-label" for="', $output );

	$output = str_replace( '&nbsp;', '', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_radiobutton_output', 'enlightenment_woocommerce_bootstrap_template_addons_radiobutton_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_addons_checkbox_output( $output ) {
	$output = str_replace( '<div class="wc-pao-addon-', '<div class="form-check wc-pao-addon-', $output );
	$output = str_replace( 'class="wc-pao-addon-field wc-pao-addon-checkbox"', 'class="wc-pao-addon-field wc-pao-addon-checkbox form-check-input align-self-auto me-0"', $output );
	$output = str_replace( '<label for="', '<label class="form-check-label" for="', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_checkbox_output', 'enlightenment_woocommerce_bootstrap_template_addons_checkbox_output' );

function enlightenment_woocommerce_bootstrap_template_addons_file_upload_output( $output ) {
	$output = str_replace( 'class="wc-pao-addon-file-upload input-text wc-pao-addon-field"', 'class="wc-pao-addon-file-upload input-text wc-pao-addon-field form-control"', $output );
	$output = str_replace( '<small>', '<div class="form-text">', $output );
	$output = str_replace( '</small>', '</div>', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_file_upload_output', 'enlightenment_woocommerce_bootstrap_template_addons_file_upload_output' );

function enlightenment_woocommerce_bootstrap_template_addons_custom_text_output( $output ) {
	return str_replace( 'class="input-text wc-pao-addon-field wc-pao-addon-custom-text"', 'class="input-text wc-pao-addon-field wc-pao-addon-custom-text form-control"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_custom_text_output', 'enlightenment_woocommerce_bootstrap_template_addons_custom_text_output' );

function enlightenment_woocommerce_bootstrap_template_addons_custom_textarea_output( $output ) {
	return str_replace( 'class="input-text wc-pao-addon-field wc-pao-addon-custom-textarea"', 'class="input-text wc-pao-addon-field wc-pao-addon-custom-textarea form-control"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_custom_textarea_output', 'enlightenment_woocommerce_bootstrap_template_addons_custom_textarea_output' );

function enlightenment_woocommerce_bootstrap_template_addons_custom_price_output( $output ) {
	return str_replace( 'class="input-text wc-pao-addon-field wc-pao-addon-custom-price"', 'class="input-text wc-pao-addon-field wc-pao-addon-custom-price form-control"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_custom_price_output', 'enlightenment_woocommerce_bootstrap_template_addons_custom_price_output' );

function enlightenment_woocommerce_bootstrap_template_addons_input_multiplier_output( $output ) {
	return str_replace( 'class="input-text wc-pao-addon-field wc-pao-addon-input-multiplier"', 'class="input-text wc-pao-addon-field wc-pao-addon-input-multiplier form-control"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_input_multiplier_output', 'enlightenment_woocommerce_bootstrap_template_addons_input_multiplier_output' );

function enlightenment_woocommerce_bootstrap_template_addons_datepicker_output( $output ) {
	return str_replace( 'class="datepicker input-text wc-pao-addon-field"', 'class="datepicker input-text wc-pao-addon-field form-control"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_addons_datepicker_output', 'enlightenment_woocommerce_bootstrap_template_addons_datepicker_output' );

function enlightenment_woocommerce_bootstrap_product_addons_total( $output ) {
	return str_replace( 'id="product-addons-total"', 'id="product-addons-total" class="w-100"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_product_addons_total', 'enlightenment_woocommerce_bootstrap_product_addons_total' );

function enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_bundle_output( $output, $template_name, $template_path, $located, $args ) {
	if ( 'grid' == $args['product']->get_layout() ) {
		$columns = WC_PB()->display->get_grid_layout_columns( $args['product'] );
		$colspan = 'col-sm-4 col-xl';

        switch ( $columns ) {
            case 1:
                $colspan = 'col-12';
                break;

            case 2:
                $colspan = 'col-sm-6';
                break;

            case 3:
                $colspan = 'col-sm-4';
                break;

            case 4:
                $colspan = 'col-sm-6 col-lg-3';;
                break;

            case 6:
                $colspan = 'col-sm-4 col-lg-2';
                break;
        }

		$output  = str_replace(
			sprintf( 'class="products bundled_products columns-%s"', esc_attr( $columns ) ),
			sprintf( 'class="products bundled_products columns-%s row list-unstyled"', esc_attr( $columns ) ),
			$output
		);

		WC_PB()->display->reset_grid_layout_pos();

		foreach ( $args['bundled_items'] as $bundled_item ) {
			$classes = array_merge(
				$bundled_item->get_classes( false ),
				array( WC_PB()->display->get_grid_layout_class( $bundled_item ) ),
			);

			$output  = str_replace(
				sprintf( 'class="%s"', esc_attr( implode( ' ', $classes ) ) ),
				sprintf( 'class="%s %s"', esc_attr( implode( ' ', $classes ) ), $colspan ),
				$output
			);

			WC_PB()->display->incr_grid_layout_pos( $bundled_item );
		}

		$output = str_replace( 'class="details"', 'class="details d-flex flex-column align-items-center"', $output );
		$output = str_replace( 'class="bundled_item_after_cart_details ', 'class="bundled_item_after_cart_details d-flex justify-content-center ', $output );
	}

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_add_to_cart_bundle_output', 'enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_bundle_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_single_product_bundled_item_optional_output( $output ) {
	$offset = strpos( $output, '<label class="bundled_product_optional_checkbox">' );
	if ( false !== $offset ) {
		$name   = '';
		$start  = strpos( $output, 'name="', $offset );
		$end    = strpos( $output, '</label>', $offset );
		if ( false !== $start && $start < $end ) {
			$start += 6;
			$end    = strpos( $output, '"', $start );
			$length = $end - $start;
			$name   = substr( $output, $start, $length );
		}

		if ( ! empty( $name ) ) {
			$output = substr_replace( $output, '<div class="bundled_product_optional_checkbox form-check">', $offset, 49 );
			$offset = strpos( $output, 'class="bundled_product_checkbox"', $offset );
			$output = substr_replace( $output, ' form-check-input', $offset + 31, 0 );
			$output = substr_replace( $output, sprintf( 'id="%s" ', $name ), $offset, 0 );
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, sprintf( ' <label for="%s" class="form-check-label">', $name ), $offset + 1, 0 );
			$offset = strpos( $output, '</label>', $offset );
			$output = substr_replace( $output, '</div>', $offset + 8, 0 );
		}
	}

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_bundled_item_optional_output', 'enlightenment_woocommerce_bootstrap_template_single_product_bundled_item_optional_output' );

function enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_bundle_add_to_cart_wrap_output( $output ) {
	return str_replace( 'class="bundle_button"', 'class="bundle_button d-flex flex-wrap align-items-center gap-4"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_add_to_cart_bundle_add_to_cart_wrap_output', 'enlightenment_woocommerce_bootstrap_template_single_product_add_to_cart_bundle_add_to_cart_wrap_output' );

function enlightenment_woocommerce_bootstrap_template_add_to_wishlist_link_output( $output ) {
    global $add_to_wishlist_args;

    if ( in_array( 'button', $add_to_wishlist_args['btn_class'] ) ) {
        $class  = join( ' ', $add_to_wishlist_args['btn_class'] );
        $output = str_replace( sprintf( 'class="%s"', $class ), sprintf( 'class="%s btn btn-secondary"', $class ), $output );
    }

    $output = str_replace( '<ul>', '<ul class="list-unstyled ms-0 mb-3">', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_add_to_wishlist_link_output', 'enlightenment_woocommerce_bootstrap_template_add_to_wishlist_link_output' );

function enlightenment_woocommerce_bootstrap_template_add_to_wishlist_modal_output( $output ) {
    $output = str_replace( 'class="wl-list-pop woocommerce"', 'class="wl-list-pop woocommerce pt-0 px-4 pb-3"', $output );
    $output = str_replace( '<dl>', '<dl class="mb-0">', $output );
    $output = str_replace( '<dt>', '<dt class="mt-3 mb-2">', $output );
    $output = str_replace( '<dd>', '<dd class="mt-1 ms-0 mb-0">', $output );
    $output = str_replace( '<strong>', '<div class="mt-3">', $output );
    $output = str_replace( '</strong>', '</div>', $output );
    $output = str_replace( 'class="wl-add-to-single button"', 'class="wl-add-to-single button btn btn-secondary"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_add_to_wishlist_modal_output', 'enlightenment_woocommerce_bootstrap_template_add_to_wishlist_modal_output' );

function enlightenment_woocommerce_bootstrap_availability_class( $class, $product ) {
    if ( ! $product->is_in_stock() ) {
        $class .= ' text-danger';
    } elseif (
		( $product->managing_stock() && $product->is_on_backorder( 1 ) ) ||
		( ! $product->managing_stock() && $product->is_on_backorder( 1 ) )
	) {
        $class .= ' text-info';
	} elseif (
		$product->managing_stock() &&
		! $product->is_on_backorder( 1 ) &&
		'low_amount' == get_option( 'woocommerce_stock_format' ) &&
		$product->get_stock_quantity() <= wc_get_low_stock_amount( $product )
	) {
		$class .= ' text-warning';
    } else {
        $class .= ' text-success';
    }

    return $class;
}
add_filter( 'woocommerce_get_availability_class', 'enlightenment_woocommerce_bootstrap_availability_class', 10, 2 );

function enlightenment_woocommerce_bootstrap_wcss_products_first_payment_date_args( $args ) {
    if ( 'p' == $args['container'] ) {
        $args['container_class'] .= ' mb-0';
        $args['wrapper_class']   .= ' d-block mb-3';
    }

    return $args;
}
add_filter( 'enlightenment_woocommerce_wcss_products_first_payment_date_args', 'enlightenment_woocommerce_bootstrap_wcss_products_first_payment_date_args' );

function enlightenment_woocommerce_bootstrap_template_single_product_tabs_tabs_output( $output ) {
    $tabs = apply_filters( 'woocommerce_product_tabs', array() );

    if ( empty( $tabs ) ) {
        return $output;
    }

    $first_tab_key = array_keys( $tabs )[0];

    $output = str_replace( 'class="tabs wc-tabs"', 'class="tabs wc-tabs nav nav-tabs"', $output );

    foreach ( $tabs as $key => $tab ) {
		$output = str_replace(
			sprintf( 'class="%s_tab"', esc_attr( $key ) ),
			sprintf( 'class="%s_tab nav-item"', esc_attr( $key ) ),
			$output
		);

		$output = str_replace(
			sprintf( 'id="tab-title-%s"', esc_attr( $key ) ),
			'',
			$output
		);

		$output = str_replace(
			sprintf( ' role="tab" aria-controls="tab-%s"', esc_attr( $key ) ),
			'role="presentation"',
			$output
		);

		$output = str_replace(
			sprintf( '<a href="#tab-%s"', esc_attr( $key ) ),
			sprintf(
				'<button class="nav-link%1$s" id="tab-title-%2$s" data-bs-toggle="tab" data-bs-target="#tab-%2$s" type="button" role="tab" aria-controls="tab-%2$s" aria-selected="3$s"',
				$key == $first_tab_key ? ' active' : '',
				$key,
				$key == $first_tab_key ? 'true' : 'false'
			),
			$output
		);

		$offset  = strpos( $output, sprintf( 'id="tab-title-%s"', esc_attr( $key ) ) );
		if ( false !== $offset ) {
			$offset = strpos( $output, '</a>', $offset );
			$output = substr_replace( $output, 'button', $offset + 2, 1 );
		}

	    $output = str_replace(
			sprintf( 'class="woocommerce-Tabs-panel woocommerce-Tabs-panel--%s panel entry-content wc-tab"', esc_attr( $key ) ),
			sprintf( 'class="woocommerce-Tabs-panel woocommerce-Tabs-panel--%s entry-content tab-pane fade"', esc_attr( $key ) ),
			$output
		);
    }

	$offset = strpos( $output, '<ul class="tabs wc-tabs ' );
	if ( false !== $offset ) {
		$offset  = strpos( $output, '</ul>', $offset );
		$output  = substr_replace( $output, "\n" . '<div class="tab-content">', $offset + 5, 0 );
		$output .= "\n" . '</div>';
	}

    $output = str_replace(
		sprintf( 'class="woocommerce-Tabs-panel woocommerce-Tabs-panel--%s entry-content tab-pane fade"', esc_attr( $first_tab_key ) ),
		sprintf( 'class="woocommerce-Tabs-panel woocommerce-Tabs-panel--%s entry-content tab-pane fade show active"', esc_attr( $first_tab_key ) ),
		$output
	);

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_tabs_tabs_output', 'enlightenment_woocommerce_bootstrap_template_single_product_tabs_tabs_output' );

function enlightenment_woocommerce_bootstrap_template_single_product_product_attributes_output( $output ) {
    $output = str_replace( 'class="woocommerce-product-attributes shop_attributes"', 'class="woocommerce-product-attributes shop_attributes table"', $output );
    $output = str_replace( '<table', '<div class="table-responsive"><table', $output );
    $output = str_replace( '</table>', '</table></div>', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_single_product_product_attributes_output', 'enlightenment_woocommerce_bootstrap_template_single_product_product_attributes_output' );

function enlightenment_woocommerce_bootstrap_comment_form_defaults( $defaults ) {
	$defaults['comment_field'] = str_replace( 'class="comment-form-rating"', 'class="comment-form-rating mb-3"', $defaults['comment_field'] );
	$defaults['comment_field'] = str_replace( '<label for="rating">', '<label for="rating" class="form-label">', $defaults['comment_field'] );
	$defaults['comment_field'] = str_replace( 'id="rating"', 'id="rating" class="form-select"', $defaults['comment_field'] );

	return $defaults;
}
add_filter( 'enlightenment_comment_form_defaults', 'enlightenment_woocommerce_bootstrap_comment_form_defaults', 12 );

function enlightenment_woocommerce_bootstrap_review_author_args( $args, $comment ) {
    if ( 'review' != $comment->comment_type ) {
        return $args;
    }

    if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && wc_review_is_from_verified_owner( $comment->comment_ID ) ) {
        $args['after'] = sprintf( '<span class="woocommerce-review__verified verified badge text-bg-success">%s</span> ', esc_attr__( 'verified owner', 'enlightenment' ) );
    }

    return $args;
}
add_filter( 'enlightenment_comment_author_args', 'enlightenment_woocommerce_bootstrap_review_author_args', 10, 2 );

function enlightenment_woocommerce_bootstrap_pagination_args( $args ) {
	$args['label_class'] .= ' visually-hidden';

	return $args;
}
add_filter( 'enlightenment_woocommerce_pagination_args', 'enlightenment_woocommerce_bootstrap_pagination_args' );

function enlightenment_woocommerce_bootstrap_template_loop_no_products_found_output( $output ) {
    return str_replace( 'class="woocommerce-info"', 'class="woocommerce-info alert alert-info"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_loop_no_products_found_output', 'enlightenment_woocommerce_bootstrap_template_loop_no_products_found_output' );

function enlightenment_woocommerce_bootstrap_template_product_searchform_output( $output ) {
    $output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
    $output = str_replace( '<input type="search"', '<div class="input-group"><input type="search"', $output );
    $output = str_replace( 'class="search-field"', 'class="search-field form-control"', $output );
    $output = str_replace( '</button>', '</button></div>', $output );

	$start = strpos( $output, '<button type="submit"' );
	if ( false !== $start ) {
		$end    = strpos( $output, '>', $start );
		$offset = strpos( $output, 'class=""', $start );

		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, 'btn btn-light', $offset + 7, 0 );
		} else {
			$offset = strpos( $output, 'class="', $start );

			if ( false !== $offset && $offset < $end ) {
				$output = substr_replace( $output, 'btn btn-light ', $offset + 7, 0 );
			} else {
				$output = substr_replace( $output, ' class="btn btn-light"', $start + 21, 0 );
			}
		}
	}

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_product_searchform_output', 'enlightenment_woocommerce_bootstrap_template_product_searchform_output' );

function enlightenment_woocommerce_bootstrap_template_cart_mini_cart_output( $output ) {
    return str_replace( '<ul class="woocommerce-mini-cart cart_list product_list_widget ', '<ul class="woocommerce-mini-cart cart_list product_list_widget list-unstyled ', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_cart_mini_cart_output', 'enlightenment_woocommerce_bootstrap_template_cart_mini_cart_output' );

function enlightenment_woocommerce_bootstrap_mini_cart_contents( $output ) {
    ob_start();

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
            $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
            $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
            $product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

            if ( ! empty( $product_permalink ) ) {
                $product_name = sprintf( '<a href="%s" title="%s">%s</a>', $product_permalink, esc_attr( wp_kses( $product_name, 'strip' ) ), $product_name );
                $thumbnail    = sprintf( '<a href="%s">%s</a>', $product_permalink, $thumbnail );
            }
            ?>
            <li class="woocommerce-mini-cart-item card border-0 <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
                <div class="row g-0 align-items-center">
                    <div class="col-3">
                        <?php echo $thumbnail; ?>
                    </div>

                    <div class="col-8">
                        <div class="card-body ps-3 py-0 pe-0">
                            <h4 class="product-title card-title mb-1">
                                <?php echo $product_name; ?>
                                <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                            </h4>

                            <div class="card-text">
                                <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-1 text-end">
                        <?php
                        echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"><i class="fas fa-times" aria-hidden="true" role="presentation"></i></a>',
                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                esc_attr__( 'Remove this item', 'woocommerce' ),
                                esc_attr( $product_id ),
                                esc_attr( $cart_item_key ),
                                esc_attr( $_product->get_sku() )
                            ),
                            $cart_item_key
                        );
                        ?>
                    </div>
                </div>
            </li>
            <?php
        }
    }

	return ob_get_clean();
}
add_filter( 'enlightenment_woocommerce_filter_mini_cart_contents', 'enlightenment_woocommerce_bootstrap_mini_cart_contents' );

function enlightenment_woocommerce_bootstrap_template_content_widget_price_filter_output( $output ) {
	$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="price_slider"', 'class="price_slider mb-3"', $output );
	$output = str_replace( 'class="price_slider_amount"', 'class="price_slider_amount d-flex align-items-center gap-2"', $output );
	$output = str_replace( '<input type="text"', '<input type="text" class="form-control"', $output );
	$output = str_replace( 'class="button ', 'class="button btn btn-secondary order-1 ms-auto ', $output );
	$output = str_replace( 'class="button"', 'class="button btn btn-secondary order-1 ms-auto"', $output );
	$output = str_replace( '<div class="clear"></div>', '', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_content_widget_price_filter_output', 'enlightenment_woocommerce_bootstrap_template_content_widget_price_filter_output' );

function enlightenment_woocommerce_bootstrap_widget_product_list( $output ) {
    return str_replace( '<ul class="product_list_widget">', '<ul class="product_list_widget list-unstyled">', $output );
}
add_filter( 'woocommerce_before_widget_product_list', 'enlightenment_woocommerce_bootstrap_widget_product_list' );
add_filter( 'woocommerce_before_widget_product_review_list', 'enlightenment_woocommerce_bootstrap_widget_product_list' );

function enlightenment_woocommerce_bootstrap_template_content_widget_product_output( $output, $template_name, $template_path, $located, $args ) {
    global $product;

    if ( ! is_a( $product, 'WC_Product' ) ) {
    	return '';
    }

    ob_start();

    ?>

    <li class="card border-0">
        <div class="row g-0 align-items-center">
            <div class="col-3">
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
            		<?php echo $product->get_image(); ?>
            	</a>
            </div>

            <div class="col-9">
                <div class="card-body ps-3 py-0 pe-0">
                	<?php do_action( 'woocommerce_widget_product_item_start', $args ); ?>

            		<h4 class="product-title card-title mb-1">
                        <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                            <?php echo wp_kses_post( $product->get_name() ); ?>
                        </a>
                    </h4>

                	<?php if ( ! empty( $args['show_rating'] ) ) : ?>
                		<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                	<?php endif; ?>

                    <div class="card-text">
                    	<?php echo $product->get_price_html(); ?>
                    </div>

                	<?php do_action( 'woocommerce_widget_product_item_end', $args ); ?>
                </div>
            </div>
        </div>
    </li>

    <?php

    $output = ob_get_clean();

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_content_widget_product_output', 'enlightenment_woocommerce_bootstrap_template_content_widget_product_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_content_widget_reviews_output( $output, $template_name, $template_path, $located, $args ) {
    ob_start();

    ?>

    <li class="card border-0">
        <div class="row g-0 align-items-center">
            <div class="col-3">
                <a href="<?php echo esc_url( get_comment_link( $args['comment']->comment_ID ) ); ?>">
            		<?php echo $args['product']->get_image(); ?>
            	</a>
            </div>

            <div class="col-9">
                <div class="card-body ps-3 py-0 pe-0">
                	<?php do_action( 'woocommerce_widget_product_review_item_start', $args ); ?>

                    <h4 class="product-title card-title mb-1">
                    	<a href="<?php echo esc_url( get_comment_link( $args['comment']->comment_ID ) ); ?>">
                    		<span class="product-title"><?php echo $args['product']->get_name(); ?></span>
                    	</a>
                    </h4>

                	<?php echo wc_get_rating_html( intval( get_comment_meta( $args['comment']->comment_ID, 'rating', true ) ) );?>

                    <div class="card-text">
                    	<span class="reviewer"><?php echo sprintf( esc_html__( 'by %s', 'woocommerce' ), get_comment_author( $args['comment']->comment_ID ) ); ?></span>
                    </div>

                	<?php do_action( 'woocommerce_widget_product_review_item_end', $args ); ?>
                </div>
            </div>
        </div>
    </li>

    <?php

    $output = ob_get_clean();

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_content_widget_reviews_output', 'enlightenment_woocommerce_bootstrap_template_content_widget_reviews_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_currency_switcher_widget( $output ) {
	$start = strpos( $output, '<select' );
	if ( false !== $start ) {
		$end = strpos( $output, '>', $start );
		$offset = strpos( $output, 'class="', $start );

		if ( false === $offset || $offset > $end ) {
			$offset = strpos( $output, "class='", $start );
		}

		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, 'form-select ', $offset + 7, 0 );
		} else {
			$output = substr_replace( $output, ' class="form-select"', $start + 7, 0 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_widget_currency_switcher_widget', 'enlightenment_woocommerce_bootstrap_currency_switcher_widget' );

function enlightenment_woocommerce_bootstrap_template_cart_cart_empty_output( $output ) {
    $output = str_replace( 'class="return-to-shop"', 'class="return-to-shop d-flex justify-content-center mb-0"', $output );
	$output = str_replace( 'class="button wc-backward ', 'class="button wc-backward btn btn-primary btn-lg ', $output );
    $output = str_replace( 'class="button wc-backward"', 'class="button wc-backward btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_cart_cart_empty_output', 'enlightenment_woocommerce_bootstrap_template_cart_cart_empty_output' );

function enlightenment_woocommerce_bootstrap_template_cart_cart_output( $output ) {
    $output = str_replace( 'class="shop_table shop_table_responsive cart woocommerce-cart-form__contents"', 'class="shop_table shop_table_responsive cart woocommerce-cart-form__contents table"', $output );
    $output = str_replace( '<table', '<div class="table-responsive"><table', $output );
    $output = str_replace( '</table>', '</table></div>', $output );

	$offset = strpos( $output, '<label for="coupon_code" ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, ' class="screen-reader-text"', $offset );
		$output = substr_replace( $output, ' visually-hidden', $offset + 26, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="input-group">', $offset + 8, 0 );
		$offset = strpos( $output, 'class="input-text"', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 17, 0 );
		$offset = strpos( $output, 'class="button', $offset );
		$output = substr_replace( $output, ' btn btn-light', $offset + 13, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
	}

	$offset = strpos( $output, '<div class="coupon e-cart-section ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="form-row coupon-col"', $offset );
		$output = substr_replace( $output, ' input-group', $offset + 26, 0 );
		$offset = strpos( $output, '<div class="coupon-col-start">', $offset );
		$output = substr_replace( $output, '', $offset, 30 );
		$offset = strpos( $output, 'class="input-text"', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 17, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '', $offset, 6 );
		$offset = strpos( $output, '<div class="coupon-col-end">', $offset );
		$output = substr_replace( $output, '', $offset, 28 );
		$offset = strpos( $output, 'class="button e-apply-coupon"', $offset );
		$output = substr_replace( $output, ' btn btn-light w-auto', $offset + 28, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '', $offset, 6 );
	}

    $output = str_replace( 'class="button" name="update_cart"', 'class="button btn btn-secondary btn-lg" name="update_cart"', $output );
	$output = str_replace( 'class="button wp-element-button" name="update_cart"', 'class="button wp-element-button btn btn-secondary btn-lg" name="update_cart"', $output );

    // PayPal Plus Banner
    $output = str_replace( '<div id="paypal-credit-banner"></div>', '<div id="paypal-credit-banner" class="mb-3"></div>', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_cart_cart_output', 'enlightenment_woocommerce_bootstrap_template_cart_cart_output' );

function enlightenment_woocommerce_bootstrap_cart_item_name( $output ) {
	$offset = strpos( $output, 'class="edit_bundle_in_cart_text edit_in_cart_text"' );
	if ( false !== $offset ) {
	    $output = substr_replace( $output, ' btn btn-secondary btn-sm', $offset + 49, 0 );
		$offset = strpos( $output, '<small>', $offset );
		$output = substr_replace( $output, '', $offset, 7 );
		$offset = strpos( $output, '</small>', $offset );
		$output = substr_replace( $output, '', $offset, 8 );

		$output = str_replace( 'class="woocommerce-Button button"', 'class="woocommerce-Button button btn btn-outline-info ms-auto"', $output );
	}

	return $output;
}
add_filter( 'woocommerce_cart_item_name', 'enlightenment_woocommerce_bootstrap_cart_item_name', 12 );

function enlightenment_woocommerce_bootstrap_template_cart_cross_sells_output( $output, $template_name, $template_path, $located, $args ) {
	$columns = wc_get_loop_prop( 'columns' );
	$colspan = 0;

	switch ( $columns ) {
		case 1:
			$colspan = 12;
			break;

		case 2:
			$colspan = 6;
			break;

		case 3:
			$colspan = 4;
			break;

		case 4:
			$colspan = 3;
			break;

		case 6:
			$colspan = 2;
			break;
	}

    if ( 5 == $columns ) {
        $start  = strpos( $output, '<ul class="products ' );
        $end    = strpos( $output, '</ul>', $start );
        $offset = $start;
        $did    = 0;

        while ( false !== $offset ) {
            $offset = strpos( $output, '<li ', $offset );

            if ( 5 == $did ) {
                $output = substr_replace( $output, '</div><div class="row">', $offset, 0 );
                $offset = strpos( $output, '<li ', $offset );
                $did    = 0;
            }

            $offset++;
            $did++;

            $offset = strpos( $output, '<li ', $offset );
            $end    = strpos( $output, '</ul>', $start );

            if ( $offset > $end ) {
                break;
            }
        }
    }

	switch ( $colspan ) {
		case 12:
			$class = sprintf( 'col-%s', $colspan );
			break;

		case 6:
			$class = sprintf( 'col-sm-%s', $colspan );
			break;

		case 4:
			$class = sprintf( 'col-md-%s', $colspan );
			break;

		case 3:
			$class = sprintf( 'col-sm-6 col-lg-%s', $colspan );
			break;

		case 2:
			$class = sprintf( 'col-sm-6 col-md-4 col-xl-%s', $colspan );
			break;

		case 0:
		default:
			$class = 'col-sm-6 col-md-4 col-lg flex-sm-grow-1 flex-sm-shrink-0 mw-100';
			break;
	}

	$output = str_replace( sprintf( '<ul class="products columns-%s">', esc_attr( $columns ) ), sprintf( '<div class="products columns-%s"><div class="row">', esc_attr( $columns ) ), $output );
	$output = str_replace( '<li class="', sprintf( '<article class="%s ', $class ), $output );
	$output = str_replace( '</li>', '</article>', $output );
    $output = str_replace( '</ul>', '</div></div>', $output );

	return $output;
}
add_action( 'enlightenment_woocommerce_filter_template_cart_cross_sells_output', 'enlightenment_woocommerce_bootstrap_template_cart_cross_sells_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_cart_cart_shipping_output( $output, $template_name, $template_path, $located, $args ) {
	if ( ! isset( $args['available_methods'] ) || 2 > count( $args['available_methods'] ) ) {
		return $output;
	}

	$start = strpos( $output, '<ul id="shipping_method"' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$offset = strpos( $output, '<input type="radio"', $offset );
			$output = substr_replace( $output, '<div class="form-check">', $offset, 0 );
			$offset = strpos( $output, 'class="shipping_method"', $offset );
			$output = substr_replace( $output, ' form-check-input', $offset + 22, 0 );
			$offset = strpos( $output, 'for="shipping_method_', $offset );
			$output = substr_replace( $output, 'class="form-check-label" ', $offset, 0 );
			$offset = strpos( $output, '</label>', $offset );
			$output = substr_replace( $output, '</div>', $offset + 8, 0 );

			$end    = strpos( $output, '</ul>', $start );
			$offset = strpos( $output, '<li>', $offset );
		}
	}

    return $output;
}
add_action( 'enlightenment_woocommerce_filter_template_cart_cart_shipping_output', 'enlightenment_woocommerce_bootstrap_template_cart_cart_shipping_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_cart_cart_recurring_shipping_output( $output, $template_name, $template_path, $located, $args ) {
	if ( ! isset( $args['available_methods'] ) || 2 > count( $args['available_methods'] ) ) {
		return $output;
	}

	$start = strpos( $output, '<ul id="shipping_method_' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<li>', $start );

		while ( false !== $offset && $offset < $end ) {
			$offset = strpos( $output, '<input type="radio"', $offset );
			$output = substr_replace( $output, '<div class="form-check">', $offset, 0 );
			$offset = strpos( $output, 'class="shipping_method ', $offset );
			$output = substr_replace( $output, ' form-check-input', $offset + 22, 0 );
			$offset = strpos( $output, 'for="shipping_method_', $offset );
			$output = substr_replace( $output, 'class="form-check-label" ', $offset, 0 );
			$offset = strpos( $output, '</label>', $offset );
			$output = substr_replace( $output, '</div>', $offset + 8, 0 );

			$end    = strpos( $output, '</ul>', $start );
			$offset = strpos( $output, '<li>', $offset );
		}
	}

	return $output;
}
add_action( 'enlightenment_woocommerce_filter_template_cart_cart_recurring_shipping_output', 'enlightenment_woocommerce_bootstrap_template_cart_cart_recurring_shipping_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_cart_shipping_calculator_output( $output ) {
    $output = str_replace( 'class="form-row form-row-wide"', 'class="form-row mb-2 form-row-wide"', $output );
	$output = str_replace( 'for="calc_shipping_country"', 'for="calc_shipping_country" class="form-label"', $output );
	$output = str_replace( 'for="calc_shipping_state"', 'for="calc_shipping_state" class="form-label"', $output );
	$output = str_replace( 'for="calc_shipping_city"', 'for="calc_shipping_city" class="form-label"', $output );
	$output = str_replace( 'for="calc_shipping_postcode"', 'for="calc_shipping_postcode" class="form-label"', $output );
	$output = str_replace( 'class="country_to_state country_select"', 'class="country_to_state country_select form-select form-select-sm"', $output );
    $output = str_replace( '<input type="text" class="input-text"', '<input type="text" class="input-text form-control form-control-sm"', $output );
    $output = str_replace( 'id="calc_shipping_state"', 'id="calc_shipping_state" data-input-classes="form-control form-control-sm"', $output );
    $output = str_replace( '<p>', '<p class="mb-0">', $output );
	$output = str_replace( 'class="button ', 'class="button btn btn-secondary btn-sm ', $output );
    $output = str_replace( 'class="button"', 'class="button btn btn-secondary btn-sm"', $output );

    return $output;
}
add_action( 'enlightenment_woocommerce_filter_template_cart_shipping_calculator_output', 'enlightenment_woocommerce_bootstrap_template_cart_shipping_calculator_output' );

function enlightenment_woocommerce_bootstrap_template_cart_proceed_to_checkout_button_output( $output ) {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Cart',
		),
	) ) ) {
		return str_replace( 'class="checkout-button ', 'class="checkout-button btn btn-primary btn-lg ', $output );
	}

	return str_replace( 'class="checkout-button ', 'class="checkout-button btn btn-primary btn-lg d-block w-100 ', $output );
}
add_action( 'enlightenment_woocommerce_filter_template_cart_proceed_to_checkout_button_output', 'enlightenment_woocommerce_bootstrap_template_cart_proceed_to_checkout_button_output' );

function enlightenment_woocommerce_bootstrap_template_checkout_form_checkout_output( $output, $template_name, $template_path, $located, $args ) {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Checkout',
		),
	) ) ) {
		$output = str_replace( 'class="e-coupon-anchor-description"', 'class="e-coupon-anchor-description form-label"', $output );

		$offset = strpos( $output, 'class="e-coupon-anchor"' );
		if ( false !== $offset ) {
			$offset = strpos( $output, '<div class="form-row">', $offset );
			$output = substr_replace( $output, '', $offset, 22 );
			$offset = strpos( $output, 'class="coupon-container-grid"', $offset );
			$output = substr_replace( $output, ' input-group d-flex align-items-stretch', $offset + 28, 0 );
			$offset = strpos( $output, '<div class="col coupon-col-1 ">', $offset );
			$output = substr_replace( $output, '', $offset, 31 );
			$offset = strpos( $output, 'class="input-text"', $offset );
			$output = substr_replace( $output, ' form-control me-0', $offset + 17, 0 );
			$offset = strpos( $output, '</div>', $offset );
			$output = substr_replace( $output, '', $offset, 6 );
			$offset = strpos( $output, '<div class="col coupon-col-2">', $offset );
			$output = substr_replace( $output, '', $offset, 31 );
			$offset = strpos( $output, 'class="woocommerce-button button e-apply-coupon"', $offset );
			$output = substr_replace( $output, ' btn btn-light w-auto', $offset + 47, 0 );
			$offset = strpos( $output, '</div>', $offset );
			$output = substr_replace( $output, '', $offset, 6 );
			$offset = strpos( $output, '</div>', $offset );
			$offset = strpos( $output, '</div>', $offset + 1 );
			$output = substr_replace( $output, '', $offset, 6 );
		}
	} else {
	    $offset = strpos( $output, '<form name="checkout"' );
	    if ( false !== $offset ) {
			$end    = strpos( $output, '>', $offset );
	        $offset = strpos( $output, 'enctype="multipart/form-data"', $offset );

			if ( false !== $offset && $offset < $end ) {
				$offset = strpos( $output, '>', $offset );

				if ( WC()->checkout()->get_checkout_fields() ) {
					$output = substr_replace( $output, "\n" . '<div class="col-lg-8">', $offset + 1, 0 );
				}

		        $output = substr_replace( $output, "\n" . '<div class="row">', $offset + 1, 0 );
		        $offset = strpos( $output, '</form>', $offset );
		        $output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
			}
	    }
	}

	$output = str_replace( 'class="tribe-checkout-backlinks"', 'class="tribe-checkout-backlinks d-flex gap-1 mb-3"', $output );
	$output = str_replace( 'class="tribe-checkout-backlink"', 'class="tribe-checkout-backlink btn btn-secondary btn-sm"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_form_checkout_output', 'enlightenment_woocommerce_bootstrap_template_checkout_form_checkout_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_klarna_checkout( $output ) {
	if ( false === strpos( $output, 'id="kco-wrapper"' ) ) {
		return $output;
	}

	$output = str_replace( 'id="kco-wrapper"', 'id="kco-wrapper" class="row"', $output );
	$output = str_replace( 'id="kco-order-review"', 'id="kco-order-review" class="col-lg-6"', $output );
	$output = str_replace( 'id="kco-extra-checkout-fields"', 'id="kco-extra-checkout-fields" class="row"', $output );
	$output = str_replace( 'class="woocommerce-additional-fields"', 'class="woocommerce-additional-fields col-12"', $output );
	$output = str_replace( 'id="kco-iframe"', 'id="kco-iframe" class="col-lg-6"', $output );
    $output = str_replace( 'class="checkout-button button"', 'class="checkout-button button btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_form_checkout_output', 'enlightenment_woocommerce_bootstrap_klarna_checkout' );

function enlightenment_woocommerce_bootstrap_paypal_plus_banner( $output ) {
	return str_replace( '<div id="paypal-credit-banner"></div>', '<div id="paypal-credit-banner" class="col-12 mb-3"></div>', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_form_checkout_output', 'enlightenment_woocommerce_bootstrap_paypal_plus_banner' );

function enlightenment_woocommerce_bootstrap_elementor_login_section( $output ) {
	if ( false === strpos( $output, 'class="e-woocommerce-login-section"' ) ) {
		return $output;
	}

	$offset = strpos( $output, 'class="woocommerce-form-login-toggle e-checkout-secondary-title"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' alert alert-info d-flex align-items-center', $offset + 63, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<span class="flex-grow-1 flex-shrink-0 pe-3">', $offset + 1, 0 );
		$offset = strpos( $output, '<a ', $offset );
		$output = substr_replace( $output, '</span>', $offset, 0 );
		$offset = strpos( $output, 'class="e-show-login"', $offset );
		$output = substr_replace( $output, ' btn btn-outline-info order-1 ms-auto', $offset + 19, 0 );
	}

	$offset = strpos( $output, 'class="e-login-wrap"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' d-block', $offset + 19, 0 );
		$offset = strpos( $output, 'class="e-login-wrap-start"', $offset );
		$output = substr_replace( $output, ' row', $offset + 25, 0 );
		$offset = strpos( $output, 'class="form-row form-row-first"', $offset );
		$output = substr_replace( $output, ' mb-3 col-md-6', $offset + 30, 0 );
		$offset = strpos( $output, 'for="username"', $offset );
		$output = substr_replace( $output, 'class="form-label" ', $offset, 0 );
		$offset = strpos( $output, 'class="input-text"', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 17, 0 );
		$offset = strpos( $output, 'class="form-row form-row-last"', $offset );
		$output = substr_replace( $output, ' mb-3 col-md-6', $offset + 29, 0 );
		$offset = strpos( $output, 'for="password"', $offset );
		$output = substr_replace( $output, 'class="form-label" ', $offset, 0 );
		$offset = strpos( $output, 'class="input-text"', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 17, 0 );
		$offset = strpos( $output, '<div class="clear"></div>', $offset );
		$output = substr_replace( $output, '', $offset, 25 );
		$offset = strpos( $output, 'class="e-login-wrap-end"', $offset );
		$offset = strpos( $output, 'class="form-row"', $offset );
		$output = substr_replace( $output, ' text-start', $offset + 15, 0 );
		$offset = strpos( $output, 'class="e-login-label"', $offset );
		$output = substr_replace( $output, ' d-none', $offset + 20, 0 );
		$offset = strpos( $output, 'class="woocommerce-button button woocommerce-form-login__submit e-woocommerce-form-login-submit"', $offset );
		$output = substr_replace( $output, ' btn btn-primary btn-lg w-auto', $offset + 95, 0 );
	}

	$offset = strpos( $output, 'class="e-login-actions-wrap"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' d-block mt-0', $offset + 27, 0 );
		$offset = strpos( $output, 'class="e-login-actions-wrap-start"', $offset );
		$output = substr_replace( $output, ' form-check mb-3', $offset + 33, 0 );
		$offset = strpos( $output, 'class="woocommerce-form__label ', $offset );
		$output = substr_replace( $output, ' form-check-label', $offset + 30, 0 );
		$output = substr_replace( $output, 'for="e-login-actions-rememberme" ', $offset, 0 );
		$offset = strpos( $output, 'class="woocommerce-form__label ', $offset );
		$output = substr_replace( $output, ' form-check-label', $offset + 30, 0 );
		$offset = strpos( $output, 'class="woocommerce-form__input ', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 30, 0 );
		$offset = strpos( $output, 'id="rememberme"', $offset );
		$output = substr_replace( $output, 'e-login-actions-', $offset + 4, 0 );

		$start = strpos( $output, '<label for="e-login-actions-rememberme"' );
		if ( false !== $start ) {
			$end    = strpos( $output, '>', $start ) + 1;
			$length = $end - $start;
			$label  = substr( $output, $start, $length );
			$output = substr_replace( $output, '', $start, $length );

			$offset = strpos( $output, '<span class="elementor-woocomemrce-login-rememberme">', $start );
			$output = substr_replace( $output, $label, $offset, 53 );
			$offset = strpos( $output, '</span>', $start );
			$output = substr_replace( $output, '', $offset, 7 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_form_checkout_output', 'enlightenment_woocommerce_bootstrap_elementor_login_section' );

function enlightenment_woocommerce_bootstrap_template_checkout_form_billing_output( $output ) {
    $output = str_replace( 'class="woocommerce-billing-fields__field-wrapper"', 'class="woocommerce-billing-fields__field-wrapper row"', $output );

    $output = str_replace( 'class="form-row form-row-wide create-account"', 'class="form-row form-row-wide create-account form-check"', $output );
    $output = str_replace( '<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">', '', $output );
    $output = str_replace( 'class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"', 'class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox form-check-input"', $output );

    if ( $offset = strpos( $output, 'id="createaccount"' ) ) {
        $offset = strpos( $output, '/> <span>', $offset );
        $output = substr_replace( $output, '<label for="createaccount" class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox form-check-label">', $offset + 3, 0 );
    }

    $output = str_replace( 'class="create-account"', 'class="create-account row"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_form_billing_output', 'enlightenment_woocommerce_bootstrap_template_checkout_form_billing_output' );

function enlightenment_woocommerce_bootstrap_template_checkout_form_shipping_output( $output ) {
    $output = str_replace( 'class="woocommerce-shipping-fields__field-wrapper"', 'class="woocommerce-shipping-fields__field-wrapper row"', $output );

    $output = str_replace( 'id="ship-to-different-address"', 'id="ship-to-different-address" class="form-check"', $output );
    $output = str_replace( '<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">', '', $output );
    $output = str_replace( 'class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"', 'class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox form-check-input"', $output );

    if ( $offset = strpos( $output, '<input id="ship-to-different-address-checkbox"' ) ) {
        $offset = strpos( $output, '/> <span>', $offset );
        $output = substr_replace( $output, '<label for="ship-to-different-address-checkbox" class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox form-check-label">', $offset + 3, 0 );
    }

    $output = str_replace( 'class="woocommerce-additional-fields__field-wrapper"', 'class="woocommerce-additional-fields__field-wrapper row"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_form_shipping_output', 'enlightenment_woocommerce_bootstrap_template_checkout_form_shipping_output' );

function enlightenment_woocommerce_bootstrap_template_checkout_form_delivery_date_output( $output ) {
    $output = str_replace( '<div id="wc-od">', '<div id="wc-od" class="row">', $output );
    $output = str_replace( '<p>', '<p class="col-12">', $output );
    $output = str_replace( '<h3>', '<h3 class="col-12">', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_form_delivery_date_output', 'enlightenment_woocommerce_bootstrap_template_checkout_form_delivery_date_output' );

function enlightenment_woocommerce_bootstrap_credit_card_form_fields( $fields ) {
    $keys = array_keys( $fields );

    foreach ( $fields as $key => $field ) {
        if ( $key == $keys[0] ) {
            $field = sprintf( '<div class="row">%s', $field );
        }

        if ( false !== strpos( $field, '<p class="form-row form-row-wide">' ) ) {
            $field = str_replace( '<p class="form-row form-row-wide">', '<div class="form-row mb-3 col-12 form-row-wide">', $field );
            $field = str_replace( '</p>', '</div>', $field );
        } elseif ( false !== strpos( $field, '<p class="form-row form-row-first">' ) ) {
            $field = str_replace( '<p class="form-row form-row-first">', '<div class="form-row mb-3 col-6 form-row-first">', $field );
            $field = str_replace( '</p>', '</div>', $field );
        } elseif ( false !== strpos( $field, '<p class="form-row form-row-last">' ) ) {
            $field = str_replace( '<p class="form-row form-row-last">', '<div class="form-row mb-3 col-6 form-row-last">', $field );
            $field = str_replace( '</p>', '</div>', $field );
        }

		if (
			false === strpos( $field, ' type="checkbox"' )
			&&
			false === strpos( $field, ' type="radio"' )
			&&
			false === strpos( $field, ' type="hidden"' )
			&&
			false === strpos( $field, ' type="button"' )
			&&
			false === strpos( $field, ' type="submit"' )
		) {
			$start = strpos( $field, '<label ' );
			if ( false !== $start ) {
				$end    = strpos( $field, '>', $start );
				$offset = strpos( $field, ' class=', $start );

				if ( false !== $offset && $offset < $end ) {
					$field = substr_replace( $field, 'form-label ', $offset + 8, 0 );
				} else {
					$field = substr_replace( $field, ' class="form-label"', $start + 6, 0 );
				}
			}
		}

        $field = str_replace( 'class="input-text ', 'class="input-text form-control ', $field );
        $field = str_replace( 'style="width:100px"', '', $field );

        if ( $key == $keys[ count( $fields ) - 1 ] ) {
            $field .= '</div>';
        }

        $fields[ $key ] = $field;
    }

    return $fields;
}
add_filter( 'woocommerce_credit_card_form_fields', 'enlightenment_woocommerce_bootstrap_credit_card_form_fields', 999 );

function enlightenment_woocommerce_bootstrap_template_checkout_form_coupon_output( $output ) {
    $output = str_replace( 'class="showcoupon"', 'class="showcoupon btn btn-outline-info order-1 ms-md-auto"', $output );

    if ( $offset = strpos( $output, '<p class="form-row form-row-first">' ) ) {
		$label = '';
		$start = strpos( $output, '<label for="coupon_code" class="screen-reader-text">', $offset );
		$end   = strpos( $output, '</p>', $offset );
		if ( false !== $start && $start < $end ) {
			$end    = strpos( $output, '</label>', $start ) + 8;
			$length = $end - $start;
			$label  = substr( $output, $start, $length );
			$label  = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $label );
			$output = substr_replace( $output, '', $start, $length );
		}

        $output = substr_replace( $output, $label . "\n" . '<div class="input-group mb-0">', $offset, 35 );
        $offset = strpos( $output, '</p>', $offset );
        $output = substr_replace( $output, '', $offset, 4 );
    }

    if ( $offset = strpos( $output, '<p class="form-row form-row-last">' ) ) {
        $output = substr_replace( $output, '', $offset, 34 );
        $offset = strpos( $output, '</p>', $offset );
        $output = substr_replace( $output, '</div>', $offset, 4 );
    }

    $output = str_replace( '<div class="clear"></div>', '', $output );

    $output = str_replace( 'class="input-text"', 'class="input-text form-control"', $output );
	$output = str_replace( 'class="button ', 'class="button btn btn-light ', $output );
    $output = str_replace( 'class="button"', 'class="button btn btn-light"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_form_coupon_output', 'enlightenment_woocommerce_bootstrap_template_checkout_form_coupon_output' );

function enlightenment_woocommerce_bootstrap_template_checkout_payment_output( $output ) {
    $output = str_replace( 'class="woocommerce-notice woocommerce-notice--info woocommerce-info"', 'class="woocommerce-notice woocommerce-notice--info woocommerce-info alert alert-info"', $output );

	if ( false === strpos( $output, 'class="button alt btn ' ) ) {
	    $output = str_replace( 'class="button alt ', 'class="button alt btn btn-secondary ', $output );
		$output = str_replace( 'class="button alt"', 'class="button alt btn btn-secondary"', $output );
	}

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_payment_output', 'enlightenment_woocommerce_bootstrap_template_checkout_payment_output' );

function enlightenment_woocommerce_bootstrap_template_checkout_payment_method_output( $output, $template_name, $template_path, $located, $args ) {
	$offset = strpos( $output, '<input id="payment_method_' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="form-check">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="input-radio"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 18, 0 );
		$offset = strpos( $output, 'for="payment_method_', $offset );
		$output = substr_replace( $output, 'class="form-check-label" ', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	if ( $args['gateway']->supports( 'credit_card_form_cvc_on_saved_method' ) ) {
		$output = str_replace( '<fieldset><p class="form-row form-row-last">', '<fieldset><div class="form-row form-row-last mb-3">', $output );
		$output = str_replace( sprintf( '<label for="%s-card-cvc">', esc_attr( $args['gateway']->id ) ), sprintf( '<label class="form-label" for="%s-card-cvc">', esc_attr( $args['gateway']->id ) ), $output );
		$output = str_replace( 'lass="input-text wc-credit-card-form-card-cvc"', 'lass="input-text wc-credit-card-form-card-cvc form-control"', $output );
		$output = str_replace( 'style="width:100px"', '', $output );
		$output = str_replace( '</p></fieldset>', '</div></fieldset>', $output );
	}

    switch( $args['gateway']->id ) {
		case 'woocommerce_payments':
			wp_dequeue_style( 'wcpay-checkout' );

			$output = str_replace( 'class="wc-upe-form wc-payment-form"', 'class="wc-upe-form wc-payment-form p-0"', $output );
			$output = str_replace( 'class="wcpay-upe-element"', 'class="wcpay-upe-element p-0"', $output );
			$output = str_replace( 'id="wcpay-card-element"', 'id="wcpay-card-element" class="form-control"', $output );
			$output = str_replace( 'class="form-row woocommerce-SavedPaymentMethods-saveNew"', 'class="form-row woocommerce-SavedPaymentMethods-saveNew form-check"', $output );
			$output = str_replace( 'class="form-row woocommerce-SavedPaymentMethods-saveNew"', 'class="form-row woocommerce-SavedPaymentMethods-saveNew form-check"', $output );
			$output = str_replace( 'id="wc-woocommerce_payments-new-payment-method"', 'id="wc-woocommerce_payments-new-payment-method" class="form-check-input"', $output );
			$output = str_replace( ' style="width:auto;"', '', $output );
			$output = str_replace( 'for="wc-woocommerce_payments-new-payment-method"', 'for="wc-woocommerce_payments-new-payment-method" class="form-check-label"', $output );
			$output = str_replace( ' style="display:inline;"', '', $output );
			$output = str_replace( '<div>', '<div class="alert alert-danger mb-0">', $output );

			break;

		case 'ppcp-credit-card-gateway':
			$output = str_replace( 'class="form-label" for="ppcp-credit-card-gateway-card-number"', 'class="form-label mt-0 mb-2" for="ppcp-credit-card-gateway-card-number"', $output );
			$output = str_replace( 'class="form-label" for="ppcp-credit-card-gateway-card-expiry"', 'class="form-label mt-0 mb-2" for="ppcp-credit-card-gateway-card-expiry"', $output );
			$output = str_replace( 'class="form-label" for="ppcp-credit-card-gateway-card-cvc"', 'class="form-label mt-0 mb-2" for="ppcp-credit-card-gateway-card-cvc"', $output );

			$offset = strpos( $output, '<p class="form-row woocommerce-SavedPaymentMethods-saveNew">' );
			if ( false !== $offset ) {
                $output = substr_replace( $output, '<div class="form-row woocommerce-SavedPaymentMethods-saveNew form-check">', $offset, 60 );
                $offset = strpos( $output, 'id="wc-ppcp-credit-card-gateway-new-payment-method"', $offset );
                $output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );

				$offset_a = strpos( $output, ' style="width:auto;"', $offset );
				if ( false !== $offset ) {
	                $output = substr_replace( $output, '', $offset_a, 20 );
				}

                $offset = strpos( $output, 'for="wc-ppcp-credit-card-gateway-new-payment-method"', $offset );
                $output = substr_replace( $output, 'class="form-check-label" ', $offset, 0 );

				$offset_a = strpos( $output, ' style="display:inline;"', $offset );
				if ( false !== $offset ) {
	                $output = substr_replace( $output, '', $offset_a, 24 );
				}

                $offset = strpos( $output, '</p>', $offset );
                $output = substr_replace( $output, ' </div>', $offset, 4 );
            }

			$offset = strpos( $output, '<label for="vault">' );
			$length = 19;
			if ( false === $offset ) {
				$offset = strpos( $output, '<label for="ppcp-credit-card-vault">' );
				$length = 36;
			}
			if ( false !== $offset ) {
                $output = substr_replace( $output, '<div class="form-check">', $offset, $length );
                $offset = strpos( $output, 'class="ppcp-credit-card-vault"', $offset );
                $output = substr_replace( $output, ' form-check-input', $offset + 29, 0 );
                $offset = strpos( $output, '>', $offset );
                $output = substr_replace( $output, ' <label class="form-check-label" for="ppcp-credit-card-vault">', $offset + 1, 0 );
                $offset = strpos( $output, '</label>', $offset );
                $output = substr_replace( $output, ' </div>', $offset + 8, 0 );
            }

			break;

        case 'stripe_sepa':
			$offset = strpos( $output, '<p class="form-row form-row-wide">' );
			if ( false !== $offset ) {
				$output = substr_replace( $output, '<div class="form-row form-row-wide mb-3">', $offset, 34 );
				$offset = strpos( $output, 'for="stripe-iban-element"', $offset );
				$output = substr_replace( $output, 'class="form-label" ', $offset, 0 );
				$offset = strpos( $output, 'class="wc-stripe-iban-element-field"', $offset );
				$output = substr_replace( $output, 'class="form-control"', $offset, 36 );
				$offset = strpos( $output, '</p>', $offset );
				$output = substr_replace( $output, '</div>', $offset, 4 );
			}

        case 'stripe':
            $output = str_replace( '<p class="form-row woocommerce-SavedPaymentMethods-saveNew">', '<p class="form-check woocommerce-SavedPaymentMethods-saveNew">', $output );
            $output = str_replace( '-new-payment-method" type="checkbox" value="true"', '-new-payment-method" class="form-check-input" type="checkbox" value="true"', $output );
            $output = str_replace( '-new-payment-method" style="display:inline;"', '-new-payment-method" class="form-check-label" style="display:inline;"', $output );

            break;

        case 'eway_payments':
            if ( false !== strpos( $output, '<label for="eway_card_number">' ) ) {
                $settings = get_option( 'woocommerce_eway_payments_settings', null );

                $output = str_replace( '<fieldset>', '<div class="row">', $output );
                $output = str_replace( 'class="eway-credit-card-message"', 'class="eway-credit-card-message col-12"', $output );

                $divider = strpos( $output, '<div class="clear"></div>' );
                $offset  = 0;

                while ( $offset = strpos( $output, '<p class="form-row form-row-first">', $offset ) ) {
                    if ( $offset < $divider ) {
                        $output = substr_replace( $output, '<p class="form-row form-row-first mb-3 col-12">', $offset, 35 );
                    } else {
                        $output = substr_replace( $output, '<p class="form-row form-row-first mb-3 col-6">', $offset, 35 );
                    }

                    $divider = strpos( $output, '<div class="clear"></div>' );
                    $offset++;
                }

                $divider = strpos( $output, '<div class="clear"></div>' );
                $offset  = 0;

                while ( $offset = strpos( $output, '<p class="form-row form-row-last">', $offset ) ) {
                    if ( $offset < $divider ) {
                        $output = substr_replace( $output, '<p class="form-row form-row-last mb-3 col-12">', $offset, 34 );
                    } else {
                        $output = substr_replace( $output, '<p class="form-row form-row-last mb-3 col-6">', $offset, 34 );
                    }

                    $divider = strpos( $output, '<div class="clear"></div>' );
                    $offset++;
                }

				$output = str_replace( 'for="eway_card_number"',   'for="eway_card_number" class="form-label"',     $output );
                $output = str_replace( 'name="eway_card_number"',  'name="eway_card_number" class="form-control"',  $output );
				$output = str_replace( 'for="eway_card_name"',     'for="eway_card_name" class="form-label"',       $output );
                $output = str_replace( 'name="eway_card_name"',    'name="eway_card_name" class="form-control"',    $output );
				$output = str_replace( 'for="eway_expiry_month"',  'for="eway_expiry_month" class="form-label"',    $output );
                $output = str_replace( 'name="eway_expiry_month"', 'name="eway_expiry_month" class="form-control"', $output );
                $output = str_replace( 'name="eway_expiry_year"',  'name="eway_expiry_year" class="form-control"',  $output );
				$output = str_replace( 'for="eway_cvn"',           'for="eway_cvn" class="form-label"',             $output );
                $output = str_replace( 'name="eway_cvn"',          'name="eway_cvn" class="form-control"',          $output );

                if ( false !== strpos( $output, '"eway_expiry_month"' ) && false !== strpos( $output, '"eway_expiry_year"' ) ) {
                    $pos = strpos( $output, '<select name="eway_expiry_month" ' );
                    $output = substr_replace( $output, '<span class="input-group">', $pos, 0 );
                    $pos = strpos( $output, '<select name="eway_expiry_year" ', $pos );
                    $pos = strpos( $output, '</select>', $pos );
                    $output = substr_replace( $output, '</span>', $pos + 9, 0 );
                }

                if ( ! empty( $settings['eway_site_seal_code'] ) ) {
                    $output = str_replace( $settings['eway_site_seal_code'], sprintf( '<div class="eway-site-seal col-12">%s</div>', $settings['eway_site_seal_code'] ), $output );
                }

                $output = str_replace( '</fieldset>', '</div>', $output );

                $output = str_replace( '<div class="clear"></div>', '', $output );
            }

            break;

        case 'braintree_credit_card':
            $output = str_replace( '<fieldset id="wc-braintree-credit-card-credit-card-form">', '<fieldset id="wc-braintree-credit-card-credit-card-form" class="row">', $output );

            $output = str_replace( '<div class="wc-braintree-credit-card-new-payment-method-form js-wc-braintree-credit-card-new-payment-method-form">', '<div class="wc-braintree-credit-card-new-payment-method-form js-wc-braintree-credit-card-new-payment-method-form row">', $output );

            if ( $offset = strpos( $output, '<p class="form-row">' ) ) {
                $output = substr_replace( $output, '<div class="form-row mb-3">', $offset, 20 );
                $pos    = strpos( $output, '</p>', $offset );
                $output = substr_replace( $output, '</div>', $pos, 4 );
            }

            if ( $offset = strpos( $output, '<p class="form-row form-row-wide">' ) ) {
                $output = substr_replace( $output, '<div class="form-row mb-3 form-row-wide">', $offset, 34 );
                $pos    = strpos( $output, '</p>', $offset );
                $output = substr_replace( $output, '</div>', $pos, 4 );
            }

			$output = str_replace( '<label for="wc-braintree-credit-card-test-amount">', '<label class="form-label" for="wc-braintree-credit-card-test-amount">', $output );
			$output = str_replace( '<label for="wc-braintree-credit-card-account-number-hosted">', '<label class="form-label" for="wc-braintree-credit-card-account-number-hosted">', $output );
			$output = str_replace( '<label for="wc-braintree-credit-card-expiry-hosted">', '<label class="form-label" for="wc-braintree-credit-card-expiry-hosted">', $output );
			$output = str_replace( '<label for="wc-braintree-credit-card-csc-hosted">', '<label class="form-label" for="wc-braintree-credit-card-csc-hosted">', $output );

            $output = str_replace( 'id="wc-braintree-credit-card-test-amount"', 'id="wc-braintree-credit-card-test-amount" class="form-control"', $output );
            $output = str_replace( 'id="wc-braintree-paypal-test-amount"', 'id="wc-braintree-paypal-test-amount" class="form-control"', $output );

            $output = str_replace( 'class="button sv-wc-payment-gateway-payment-form-manage-payment-methods"', 'class="button sv-wc-payment-gateway-payment-form-manage-payment-methods btn btn-secondary d-block w-100"', $output );

            $output = str_replace( '<div class="form-row form-row-wide ', '<div class="form-row form-row-wide mb-3 col-12 ', $output );

            $output = str_replace( ' wc-braintree-hosted-field-card-number ', ' wc-braintree-hosted-field-card-number form-control ', $output );

            $output = str_replace( '<div class="form-row form-row-first ', '<div class="form-row form-row-first mb-3 col-6 ', $output );

            $output = str_replace( ' wc-braintree-hosted-field-card-expiry ', ' wc-braintree-hosted-field-card-expiry form-control ', $output );

            $output = str_replace( '<div class="form-row form-row-last ', '<div class="form-row form-row-last mb-3 col-6 ', $output );

            $output = str_replace( ' wc-braintree-hosted-field-card-csc ', ' wc-braintree-hosted-field-card-csc form-control ', $output );

            if ( $offset = strpos( $output, '<p class="form-row"><input name="wc-braintree-credit-card-tokenize-payment-method"' ) ) {
                $output = substr_replace( $output, '<p class="form-row mb-0 col-12"><span class="form-check">', $offset, 20 );
                $pos    = strpos( $output, '</p>', $offset );
                $output = substr_replace( $output, '</span></p>', $pos, 4 );
            }

            $output = str_replace( ' js-wc-braintree-credit-card-tokenize-payment-method', ' js-wc-braintree-credit-card-tokenize-payment-method form-check-input', $output );
            $output = str_replace( 'for="wc-braintree-credit-card-tokenize-payment-method" style="display:inline;"', 'for="wc-braintree-credit-card-tokenize-payment-method" class="form-check-label"', $output );

            break;

        case 'braintree_paypal':
            if ( $offset = strpos( $output, '<p class="form-row">' ) ) {
                $output = substr_replace( $output, '<div class="form-row mb-3">', $offset, 20 );
                $pos    = strpos( $output, '</p>', $offset );
                $output = substr_replace( $output, '</div>', $pos, 4 );
            }

			$output = str_replace( '<label for="wc-braintree-paypal-test-amount">', '<label class="form-label" for="wc-braintree-paypal-test-amount">', $output );
            $output = str_replace( 'id="wc-braintree-paypal-test-amount"', 'id="wc-braintree-paypal-test-amount" class="form-control"', $output );

			$output = str_replace( 'class="button sv-wc-payment-gateway-payment-form-manage-payment-methods"', 'class="button sv-wc-payment-gateway-payment-form-manage-payment-methods btn btn-secondary d-block w-100"', $output );

            break;

        case 'square_credit_card':
            /**
             * Square Gateway
            **/
            if ( $offset = strpos( $output, '<p class="form-row form-row-wide">' ) ) {
                $output = substr_replace( $output, '<div class="form-row form-row-wide">', $offset, 34 );
                $offset = strpos( $output, '</p>', $offset );
                $output = substr_replace( $output, '</div>', $offset, 4 );
            }

            $output = str_replace( 'class="button sv-wc-payment-gateway-payment-form-manage-payment-methods"', 'class="button sv-wc-payment-gateway-payment-form-manage-payment-methods btn btn-secondary d-block w-100"', $output );

            $offset = 0;

            while ( $offset = strpos( $output, '<input type="radio" id="wc-square-credit-card-payment-token-', $offset ) ) {
                $output = substr_replace( $output, '<div class="form-check">', $offset, 0 );
                $offset = strpos( $output, 'class="js-sv-wc-payment-gateway-payment-token ', $offset );
                $output = substr_replace( $output, 'form-check-input ', $offset + 46, 0 );
                $offset = strpos( $output, 'class="sv-wc-payment-gateway-payment-form-saved-payment-method', $offset );
                $output = substr_replace( $output, ' form-check-label mb-0', $offset + 62, 0 );
                $offset = strpos( $output, '</label><br />', $offset );
                $output = substr_replace( $output, '</div>', $offset + 8, 6 );
            }

            if ( $offset = strpos( $output, '<input type="radio" id="wc-square-credit-card-use-new-payment-method"' ) ) {
                $output = substr_replace( $output, '<div class="form-check">', $offset, 0 );
                $offset = strpos( $output, 'class="js-sv-wc-payment-token ', $offset );
                $output = substr_replace( $output, 'form-check-input ', $offset + 30, 0 );
                $offset = strpos( $output, 'style="display:inline;" for="wc-square-credit-card-use-new-payment-method"', $offset );
                $output = substr_replace( $output, 'class="form-check-label"', $offset, 23 );
                $offset = strpos( $output, '</label>', $offset );
                $output = substr_replace( $output, '</div>', $offset + 8, 0 );
            }

            $output = str_replace( '<div class="wc-square-credit-card-new-payment-method-form js-wc-square-credit-card-new-payment-method-form">', '<div class="wc-square-credit-card-new-payment-method-form js-wc-square-credit-card-new-payment-method-form row">', $output );
            $output = str_replace( '<div class="form-row form-row-wide ', '<div class="form-row form-row-wide mb-3 col-12 ', $output );
            $output = str_replace( '<div class="form-row form-row-first ', '<div class="form-row form-row-first mb-3 col-6 ', $output );
            $output = str_replace( '<div class="form-row form-row-last ', '<div class="form-row form-row-last mb-3 col-6 ', $output );

			$output = str_replace( '<label for="wc-square-credit-card-account-number-hosted">', '<label class="form-label" for="wc-square-credit-card-account-number-hosted">', $output );
			$output = str_replace( '<label for="wc-square-credit-card-expiry-hosted">', '<label class="form-label" for="wc-square-credit-card-expiry-hosted">', $output );
			$output = str_replace( '<label for="wc-square-credit-card-csc-hosted">', '<label class="form-label" for="wc-square-credit-card-csc-hosted">', $output );
			$output = str_replace( '<label for="wc-square-credit-card-postal-code-hosted">', '<label class="form-label" for="wc-square-credit-card-postal-code-hosted">', $output );

            if ( $offset = strpos( $output, '<div id="wc-square-credit-card-account-number-hosted"' ) ) {
                $output = substr_replace( $output, '<div class="form-control">', $offset, 0 );
                $offset = strpos( $output, '</div>', $offset );
                $output = substr_replace( $output, '</div>', $offset, 0 );
            }

            if ( $offset = strpos( $output, '<div id="wc-square-credit-card-expiry-hosted"' ) ) {
                $output = substr_replace( $output, '<div class="form-control">', $offset, 0 );
                $offset = strpos( $output, '</div>', $offset );
                $output = substr_replace( $output, '</div>', $offset, 0 );
            }

            if ( $offset = strpos( $output, '<div id="wc-square-credit-card-csc-hosted"' ) ) {
                $output = substr_replace( $output, '<div class="form-control">', $offset, 0 );
                $offset = strpos( $output, '</div>', $offset );
                $output = substr_replace( $output, '</div>', $offset, 0 );
            }

            if ( $offset = strpos( $output, '<div id="wc-square-credit-card-postal-code-hosted"' ) ) {
                $output = substr_replace( $output, '<div class="form-control">', $offset, 0 );
                $offset = strpos( $output, '</div>', $offset );
                $output = substr_replace( $output, '</div>', $offset, 0 );
            }

            if ( $offset = strpos( $output, '<p class="form-row">', $offset ) ) {
                $output = substr_replace( $output, '<div class="form-row col-12">', $offset, 20 );
                $offset = strpos( $output, '</p>', $offset );
                $output = substr_replace( $output, '</div>', $offset, 4 );
            }

            if ( $offset = strpos( $output, '<input name="wc-square-credit-card-tokenize-payment-method"' ) ) {
				$end_a    = strpos( $output, '>', $offset );
				$offset_a = strpos( $output, 'type="checkbox"', $offset );

				if ( $offset_a && $offset_a < $end_a ) {
	                $output = substr_replace( $output, '<div class="form-check">', $offset, 0 );
	                $offset = strpos( $output, 'class="js-sv-wc-tokenize-payment', $offset );
	                $output = substr_replace( $output, ' form-check-input', $offset + 32, 0 );
	                $offset = strpos( $output, '<label ', $offset );
	                $output = substr_replace( $output, 'class="form-check-label" ', $offset + 7, 0 );
	                $offset = strpos( $output, '</label>', $offset );
	                $output = substr_replace( $output, '</div>', $offset + 8, 0 );
				}
            }

            break;
    }

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_payment_method_output', 'enlightenment_woocommerce_bootstrap_template_checkout_payment_method_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_braintree_credit_card_payment_form_payment_method_html( $output ) {
    $output = str_replace( 'js-wc-braintree-credit-card-payment-token', 'js-wc-braintree-credit-card-payment-token form-check-input', $output );
    $output = str_replace( '<label style="display:inline;"', '<label ', $output );
    $output = str_replace( '<label ', '<label class="form-check-label" ', $output );
    $output = str_replace( '<br />', '', $output );

	$start = strpos( $output, 'style="' );
	if ( false !== $start ) {
		$end    = strpos( $output, '"', $start + 7 );
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

    return sprintf( '<span class="form-check">%s</span>', $output );
}
add_filter( 'wc_braintree_credit_card_payment_form_payment_method_html', 'enlightenment_woocommerce_bootstrap_braintree_credit_card_payment_form_payment_method_html' );
add_filter( 'wc_braintree_credit_card_payment_form_new_payment_method_input_html', 'enlightenment_woocommerce_bootstrap_braintree_credit_card_payment_form_payment_method_html' );

function enlightenment_woocommerce_bootstrap_braintree_paypal_payment_form_payment_method_html( $output ) {
    $output = str_replace( 'js-wc-braintree-paypal-payment-token', 'js-wc-braintree-paypal-payment-token form-check-input', $output );
    $output = str_replace( 'class="sv-wc-payment-gateway-payment-form-saved-payment-method"', 'class="sv-wc-payment-gateway-payment-form-saved-payment-method form-check-label mb-0" ', $output );
	$output = str_replace( '<label style="display:inline;"', '<label class="form-check-label" ', $output );
    $output = str_replace( '<br />', '', $output );

	$start = strpos( $output, 'style="' );
	if ( false !== $start ) {
		$end    = strpos( $output, '"', $start + 7 );
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

    return sprintf( '<span class="form-check">%s</span>', $output );
}
add_filter( 'wc_braintree_paypal_payment_form_payment_method_html', 'enlightenment_woocommerce_bootstrap_braintree_paypal_payment_form_payment_method_html' );
add_filter( 'wc_braintree_paypal_payment_form_new_payment_method_input_html', 'enlightenment_woocommerce_bootstrap_braintree_paypal_payment_form_payment_method_html' );

function enlightenment_woocommerce_bootstrap_braintree_paypal_payment_form_save_payment_method_checkbox_html( $output ) {
    $output = str_replace( '<p class="form-row">', '<div class="form-row form-check">', $output );
    $output = str_replace( 'class="js-sv-wc-tokenize-payment', 'class="js-sv-wc-tokenize-payment form-check-input', $output );
    $output = str_replace( '<label ', '<label class="form-check-label"' , $output );
    $output = str_replace( '</p>', '</div>' , $output );

    return $output;
}
add_filter( 'wc_braintree_paypal_payment_form_save_payment_method_checkbox_html', 'enlightenment_woocommerce_bootstrap_braintree_paypal_payment_form_save_payment_method_checkbox_html' );

function enlightenment_woocommerce_bootstrap_template_checkout_terms_output( $output ) {
    $output = str_replace( 'class="form-row validate-required"', 'class="form-row validate-required form-check"', $output );
    $output = str_replace( '<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">', '', $output );
    $output = str_replace( 'class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"', 'class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox form-check-input"', $output );
    $output = str_replace( '<span class="woocommerce-terms-and-conditions-checkbox-text">', '<label for="terms" class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox form-check-label"><span class="woocommerce-terms-and-conditions-checkbox-text">', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_terms_output', 'enlightenment_woocommerce_bootstrap_template_checkout_terms_output' );

add_action( apply_filters( 'woocommerce_paypal_payments_checkout_dcc_renderer_hook', 'woocommerce_review_order_after_submit' ), 'enlightenment_ob_start', 9 );
add_action( apply_filters( 'woocommerce_paypal_payments_checkout_dcc_renderer_hook', 'woocommerce_pay_order_after_submit' ), 'enlightenment_ob_start', 9 );

function enlightenment_woocommerce_bootstrap_dcc_renderer() {
	$output = ob_get_clean();
	$output = str_replace( 'class="button alt ', 'class="button alt btn btn-primary btn-lg ', $output );
	$output = str_replace( 'class="button alt"', 'class="button alt btn btn-primary btn-lg"', $output );

	echo $output;
}
add_action( apply_filters( 'woocommerce_paypal_payments_checkout_dcc_renderer_hook', 'woocommerce_review_order_after_submit' ), 'enlightenment_woocommerce_bootstrap_dcc_renderer', 13 );
add_action( apply_filters( 'woocommerce_paypal_payments_checkout_dcc_renderer_hook', 'woocommerce_pay_order_after_submit' ), 'enlightenment_woocommerce_bootstrap_dcc_renderer', 13 );

function enlightenment_woocommerce_bootstrap_template_checkout_order_receipt_output( $output ) {
    $output = str_replace( 'class="order_details"', 'class="order_details list-unstyled row"', $output );

    $start  = strpos( $output, '<ul class="woocommerce-order-overview ' );
    $end    = strpos( $output, '</ul>', $start );
    $offset = $start;
    while ( false !== $offset ) {
        $offset = strpos( $output, '<li class="', $offset );
        $output = substr_replace( $output, 'col-md text-truncate ', $offset + 11, 0 );
        $offset = strpos( $output, '<strong>', $offset );
        $output = substr_replace( $output, '<strong class="d-block text-truncate">', $offset, 8 );

        $offset = strpos( $output, '<li class="', $offset );
        $end    = strpos( $output, '</ul>', $start );

        if ( $offset > $end ) {
            break;
        }
    }

    $output = str_replace( 'id="payfast_payment_form"', 'id="payfast_payment_form" class="d-flex align-items-center"', $output );
    $output = str_replace( 'class="button-alt" id="submit_payfast_payment_form"', 'class="button-alt btn btn-primary btn-lg" id="submit_payfast_payment_form"', $output );
    $output = str_replace( 'class="button cancel"', 'class="button cancel ms-3"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_order_receipt_output', 'enlightenment_woocommerce_bootstrap_template_checkout_order_receipt_output' );

function enlightenment_woocommerce_bootstrap_template_checkout_thankyou_output( $output ) {
	$output = str_replace( 'class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"', 'class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed alert alert-danger"', $output );

	$start = strpos( $output, '<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions"' );
	if ( false !== $start ) {
		$offset = strpos( $output, 'class="button pay"', $start );
		$output = substr_replace( $output, ' btn btn-primary', $offset + 17, 0 );

		$end    = strpos( $output, '</p>', $start );
		$offset = strpos( $output, 'class="button pay"', $offset );
		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' btn btn-secondary', $offset + 17, 0 );
		}
	}

	$output = str_replace( 'class="button pay"', 'class="button pay btn btn-primary"', $output );
    $output = str_replace( 'woocommerce-notice woocommerce-notice--success', 'woocommerce-notice woocommerce-notice--success alert alert-success', $output );
    // $output = str_replace( 'class="wc-bacs-bank-details order_details bacs_details"', 'class="wc-bacs-bank-details order_details bacs_details list-unstyled mb-0"', $output );
    $output = str_replace( 'class="woocommerce-order-overview woocommerce-thankyou-order-details order_details"', 'class="woocommerce-order-overview woocommerce-thankyou-order-details order_details list-unstyled row"', $output );

    $start  = strpos( $output, '<ul class="woocommerce-order-overview ' );
    $end    = strpos( $output, '</ul>', $start );
    $offset = $start;
    while ( false !== $offset ) {
        $offset = strpos( $output, '<li class="woocommerce-order-overview__', $offset );
        $output = substr_replace( $output, 'col-md text-truncate ', $offset + 11, 0 );
        $offset = strpos( $output, '<strong>', $offset );
        $output = substr_replace( $output, '<strong class="d-block text-truncate">', $offset, 8 );

        $end    = strpos( $output, '</ul>', $start );
        $offset = strpos( $output, '<li class="woocommerce-order-overview__', $offset );

        if ( $offset > $end ) {
            break;
        }
    }

    $payment_gateways = WC()->payment_gateways() ? WC()->payment_gateways->payment_gateways() : array();

    if ( isset( $payment_gateways['bacs'] ) && 'yes' == $payment_gateways['bacs']->enabled ) {
        $bacs_accounts = apply_filters( 'woocommerce_bacs_accounts', get_option(
            'woocommerce_bacs_accounts',
            array(
                array(
                    'account_name'   => $payment_gateways['bacs']->get_option( 'account_name' ),
                    'account_number' => $payment_gateways['bacs']->get_option( 'account_number' ),
                    'sort_code'      => $payment_gateways['bacs']->get_option( 'sort_code' ),
                    'bank_name'      => $payment_gateways['bacs']->get_option( 'bank_name' ),
                    'iban'           => $payment_gateways['bacs']->get_option( 'iban' ),
                    'bic'            => $payment_gateways['bacs']->get_option( 'bic' ),
                ),
            )
        ) );

        if (
            ! empty( $bacs_accounts ) &&
            (
                false !== strpos( $output, '<h3 class="wc-bacs-bank-details-account-name">' ) ||
                false !== strpos( $output, '<ul class="wc-bacs-bank-details order_details bacs_details">', $start )
            )
        ) {
            $count  = count( $bacs_accounts );
            $suffix = 0 === $count % 3 ? '-md-6 col-lg-4' : ( 0 === $count % 2 ? '-md-6' : '' );
            $start  = 0;
            $i      = 0;

            foreach ( $bacs_accounts as $bacs_account ) {
                if ( $bacs_account['account_name'] ) {
                    $start = strpos( $output, '<h3 class="wc-bacs-bank-details-account-name">', $start );
                } else {
                    $start = strpos( $output, '<ul class="wc-bacs-bank-details order_details bacs_details">', $start );
                }

                if ( 0 == $i ) {
                    $output = substr_replace( $output, sprintf( '<div class="row"><div class="col%s">', $suffix ), $start, 0 );
                } else {
                    $output = substr_replace( $output, sprintf( '<div class="col%s">', $suffix ), $start, 0 );
                }

                $start  = strpos( $output, '<ul class="wc-bacs-bank-details order_details bacs_details">', $start );
                $output = substr_replace( $output, '<table class="wc-bacs-bank-details order_details bacs_details text-nowrap mb-3"><tbody>', $start, 60 );
                $end    = strpos( $output, '</ul>', $start );
                $offset = $start;

                if ( $count == $i + 1 ) {
                    $output = substr_replace( $output, '</tbody></table></div></div>', $end, 5 );
                } else {
                    $output = substr_replace( $output, '</tbody></table></div>', $end, 5 );
                }

                while ( false !== $offset ) {
                    $offset = strpos( $output, '<li ', $offset );
                    $output = substr_replace( $output, '<tr ', $offset, 4 );
                    $offset = strpos( $output, '>', $offset );
                    $output = substr_replace( $output, '><td class="py-1 ps-0 pe-3">', $offset, 1 );
                    $offset = strpos( $output, '<strong>', $offset );
                    $output = substr_replace( $output, '</td><td class="py-1 px-0"><strong>', $offset, 8 );
                    $offset = strpos( $output, '</strong>', $offset );
                    $output = substr_replace( $output, '</strong></td>', $offset, 9 );
                    $offset = strpos( $output, '</li>', $offset );
                    $output = substr_replace( $output, '</tr>', $offset, 5 );

                    $end    = strpos( $output, '</table>', $start );
                    $offset = strpos( $output, '<li ', $offset );

                    if ( $offset > $end ) {
                        break;
                    }
                }

                $start++;
                $i++;
            }
        }
    }

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_thankyou_output', 'enlightenment_woocommerce_bootstrap_template_checkout_thankyou_output' );

function enlightenment_woocommerce_bootstrap_template_checkout_form_pay_output( $output ) {
    $output = str_replace( '<table class="shop_table">', '<div class="row"><div class="col-lg-8"><div class="order-review"><div class="table-responsive"><table class="shop_table table">', $output );
    $output = str_replace( '</table>', '</table></div></div></div><div class="col-lg-4"><div class="order-payment">', $output );
    $output = str_replace( '</form>', '</div></div></div></form>', $output );
    $output = str_replace( 'class="woocommerce-notice woocommerce-notice--info woocommerce-info"', 'class="woocommerce-notice woocommerce-notice--info woocommerce-info alert alert-info"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_form_pay_output', 'enlightenment_woocommerce_bootstrap_template_checkout_form_pay_output' );

function enlightenment_woocommerce_bootstrap_pay_order_button_html( $output ) {
	$output = str_replace( 'class="button alt ', 'class="button alt btn btn-primary btn-lg ', $output );
    $output = str_replace( 'class="button alt"', 'class="button alt btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'woocommerce_pay_order_button_html', 'enlightenment_woocommerce_bootstrap_pay_order_button_html' );

function enlightenment_woocommerce_bootstrap_template_myaccount_view_order_output( $output ) {
    // WooCommerce Shipment Tracking
    if ( $offset = strpos( $output, '<table class="shop_table shop_table_responsive my_account_tracking">' ) ) {
        $output = substr_replace( $output, '<div class="table-responsive">', $offset, 0 );
        $offset = strpos( $output, '<td class="order-actions"', $offset );
        $offset = strpos( $output, 'class="button', $offset );
        $output = substr_replace( $output, ' btn btn-secondary', $offset + 13, 0 );
        $offset = strpos( $output, '</table>', $offset );
        $output = substr_replace( $output, '</div></section>', $offset + 8, 0 );

        $output = str_replace( 'class="shop_table shop_table_responsive my_account_tracking"', 'class="shop_table shop_table_responsive my_account_tracking table"', $output );

        $pos   = 0;
        $limit = 0;

        while ( $pos < $offset ) {
            $limit = $pos;
            $pos   = strpos( $output, '<h2>', $limit + 1 );

            if ( $pos <= $limit ) {
                break;
            }
        }

        if ( $limit ) {
            $output = substr_replace( $output, '<section class="woocommerce-order-tracking">', $limit, 0 );
        }
    }

    // AfterShip
    $options = get_option( 'aftership_option_name' );

    if (
        ! empty( $options ) &&
        isset( $options['track_message_1'] ) &&
        $offset = strpos( $output, $options['track_message_1'] )
    ) {
        $output = substr_replace( $output, "<ul class=\"aftership-tracking-info alert alert-info list-unstyled\">\n<li>", $offset, 0 );
        $offset = strpos( $output, '<br/>', $offset );
        $output = substr_replace( $output, "</li>\n<li>", $offset, 5 );
        $offset = strpos( $output, '<div id="as-root">', $offset );
        $output = substr_replace( $output, "</li>\n<li>", $offset, 0 );
        $offset = strpos( $output, '<br><br>', $offset );
        $output = substr_replace( $output, "</li>\n</ul>", $offset, 8 );
    }

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_view_order_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_view_order_output' );

function enlightenment_woocommerce_bootstrap_template_order_order_details_output( $output ) {
	if ( $offset = strpos( $output, '<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">' ) ) {
        $output = substr_replace( $output, ' table', $offset + 89, 0 );
        $output = substr_replace( $output, '<div class="table-responsive">', $offset, 0 );
        $offset = strpos( $output, '</table>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );
	}

    // YITH WooCommerce Order Tracking
    // $output = str_replace( 'ywot_order_details ', 'ywot_order_details alert alert-info ', $output );
	if ( $offset = strpos( $output, '<div class="ywot_order_details ' ) ) {
		$output = substr_replace( $output, ' p-0 bg-transparent', $offset + 30, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<div class="alert alert-info">', $offset + 1, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );
	}

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_order_order_details_output', 'enlightenment_woocommerce_bootstrap_template_order_order_details_output' );

function enlightenment_woocommerce_bootstrap_template_order_order_again_output( $output ) {
    return str_replace( 'class="button"', 'class="button btn btn-primary btn-lg"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_order_order_again_output', 'enlightenment_woocommerce_bootstrap_template_order_order_again_output' );

function enlightenment_woocommerce_bootstrap_template_order_order_details_customer_output( $output ) {
    $output = str_replace( '<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">', '<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses row">', $output );
    $output = str_replace( '<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">', '<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-md-6">', $output );
    $output = str_replace( '<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">', '<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-md-6">', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_order_order_details_customer_output', 'enlightenment_woocommerce_bootstrap_template_order_order_details_customer_output' );

function enlightenment_woocommerce_bootstrap_template_order_form_tracking_output( $output ) {
    $output = str_replace( '<p>', '<p class="alert alert-info">', $output );
    $output = str_replace( '<p class="form-row form-row-first">', '<div class="row"><p class="form-row form-row-first mb-3 col-12 col-md">', $output );
    $output = str_replace( '<p class="form-row form-row-last">', '<p class="form-row form-row-last mb-3 col-12 col-md">', $output );
	$output = str_replace( '<label for="orderid"', '<label for="orderid" class="form-label"', $output );
	$output = str_replace( '<label for="order_email"', '<label for="order_email" class="form-label"', $output );
	$output = str_replace( 'class="input-text"', 'class="input-text form-control"', $output );
	$output = str_replace( '<div class="clear"></div>', '</div>', $output );
	$output = str_replace( 'class="button ', 'class="button btn btn-primary btn-lg ', $output );
	$output = str_replace( 'class="button"', 'class="button btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_order_form_tracking_output', 'enlightenment_woocommerce_bootstrap_template_order_form_tracking_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_navigation_output( $output ) {
    $output = str_replace( '<ul>', '<ul class="nav">', $output );
    $output = str_replace( '<a href="', '<a class="nav-link" href="', $output );

    if ( $offset = strpos( $output, 'is-active' ) ) {
        $pos    = 15 + strpos( $output, 'class="nav-link', $offset );
        $output = substr_replace( $output, ' active', $pos, -( strlen( $output ) - $pos + 1 ) );
    }

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_navigation_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_navigation_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_orders_output( $output ) {
    $output = str_replace( '<div class="woocommerce-message woocommerce-message--info ', '<div class="woocommerce-message woocommerce-message--info alert alert-info d-flex align-items-center ', $output );
    $output = str_replace( 'class="woocommerce-Button button"', 'class="woocommerce-Button button btn btn-outline-info order-1 ms-auto"', $output );

    $output = str_replace( 'class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table"', 'class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table table"', $output );
    $output = str_replace( '<table', '<div class="table-responsive"><table', $output );
    $output = str_replace( '</table>', '</table></div>', $output );

    $offset = 0;
    while ( $offset = strpos( $output, 'woocommerce-orders-table__cell-order-actions', $offset ) ) {
        $pos    = strpos( $output, '<a ', $offset );
        $output = substr_replace( $output, '<div class="btn-group" role="group">', $pos, 0 );
        $pos    = strpos( $output, '</td>', $offset );
        $output = substr_replace( $output, '</div>', $pos, 0 );
        $offset++;
    }

    $output = str_replace( ' woocommerce-Pagination">', ' woocommerce-Pagination mt-5"><div class="btn-group">', $output );
    $offset = strpos( $output, '<div class="btn-group">' );
    $pos    = strpos( $output, '</div>', $offset );
    $output = substr_replace( $output, '</div>', $pos, -( strlen( $output ) - $pos + 1 ) );

    $output = str_replace( 'woocommerce-button ', 'woocommerce-button btn btn-secondary ', $output );

    // YITH WooCommerce Order Tracking
    $output = str_replace( 'track-button button ', 'track-button button btn btn-secondary py-0 d-inline-flex align-items-center', $output );
    $output = str_replace( 'style="display:inline-block;height:25px; padding-top:0; padding-bottom:0; top: 10px; position: relative; margin-left:10px"', '', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_orders_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_orders_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_downloads_output( $output ) {
    $output = str_replace( '<div class="woocommerce-Message woocommerce-Message--info woocommerce-info"', '<div class="woocommerce-Message woocommerce-Message--info woocommerce-info alert alert-info d-flex align-items-center" "', $output );
    $output = str_replace( 'class="woocommerce-Button button"', 'class="woocommerce-Button button btn btn-outline-info order-1 ms-auto"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_downloads_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_downloads_output' );

function enlightenment_woocommerce_bootstrap_template_order_order_downloads_output( $output ) {
    $output = str_replace( 'class="woocommerce-table woocommerce-table--order-downloads shop_table shop_table_responsive order_details"', 'class="woocommerce-table woocommerce-table--order-downloads shop_table shop_table_responsive order_details table"', $output );
    $output = str_replace( '<table', '<div class="table-responsive"><table', $output );
    $output = str_replace( '</table>', '</table></div>', $output );
    $output = str_replace( 'class="woocommerce-MyAccount-downloads-file button alt"', 'class="woocommerce-MyAccount-downloads-file button alt btn btn-secondary"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_order_order_downloads_output', 'enlightenment_woocommerce_bootstrap_template_order_order_downloads_output' );

add_action( 'woocommerce_account_ppcp-paypal-payment-tokens_endpoint', 'enlightenment_ob_start', 8 );

function enlightenment_woocommerce_bootstrap_ppcp_paypal_payment_tokens_endpoint() {
	$output = ob_get_clean();
	$output = str_replace( '<div class="woocommerce-Message woocommerce-Message--info woocommerce-info"', '<div class="woocommerce-Message woocommerce-Message--info woocommerce-info alert alert-info" "', $output );

	echo $output;
}
add_action( 'woocommerce_account_ppcp-paypal-payment-tokens_endpoint', 'enlightenment_woocommerce_bootstrap_ppcp_paypal_payment_tokens_endpoint', 12 );

function enlightenment_woocommerce_bootstrap_template_myaccount_my_subscriptions_output( $output ) {
	$offset = strpos( $output, '<table class="my_account_subscriptions ' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="table-responsive">' . "\n", $offset, 0 );
		$offset = strpos( $output, '<table class="my_account_subscriptions ', $offset );
	    $offset = strpos( $output, '"', $offset + 14 );
	    $output = substr_replace( $output, ' table', $offset, 0 );
		$offset = strpos( $output, '</table>', $offset );
	    $output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	$offset = strpos( $output, '<td class="subscription-actions ' );
	if ( false !== $offset ) {
		while ( false !== $offset ) {
		    $offset = strpos( $output, '>', $offset );
		    $output = substr_replace( $output, "\n" . '<div class="btn-group">', $offset + 1, 0 );
		    $offset = strpos( $output, '</td>', $offset );
		    $output = substr_replace( $output, '</div>' . "\n", $offset, 0 );

			$offset = strpos( $output, '<td class="subscription-actions ', $offset );
		}

		$output = str_replace( 'class="woocommerce-button button ', 'class="woocommerce-button button btn btn-secondary ', $output );
	}

	$offset = strpos( $output, 'class="no_subscriptions ' );
	if ( false !== $offset ) {
	    $offset = strpos( $output, '"', $offset + 7 );
	    $output = substr_replace( $output, ' alert alert-info d-flex align-items-center', $offset, 0 );

		$output = str_replace( 'class="woocommerce-Button button"', 'class="woocommerce-Button button btn btn-outline-info ms-auto"', $output );
	}

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_my_subscriptions_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_my_subscriptions_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_related_subscriptions_output( $output ) {
    return str_replace( 'class="woocommerce-button button view"', 'class="woocommerce-button button view btn btn-secondary"', $output );
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_related_subscriptions_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_related_subscriptions_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_subscription_details_output( $output ) {
    $offset = strpos( $output, 'class="woocommerce-button ' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, '<td>', $offset - strlen( $output ) );
		$output = substr_replace( $output, '<div class="btn-group">', $offset + 4, 0 );
		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );
	}

	$offset = strpos( $output, 'class="woocommerce-button ' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '"', $offset + 7 );
        $output = substr_replace( $output, ' btn btn-secondary mb-0', $offset, 0 );

		$offset = strpos( $output, 'class="woocommerce-button ', $offset );
	}

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_subscription_details_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_subscription_details_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_subscription_totals_table_output( $output ) {
    $output = str_replace( 'class="shop_table order_details"', 'class="shop_table order_details table"', $output );
    $output = str_replace( '<table ', '<div class="table-responsive"><table ', $output );
    $output = str_replace( '</table>', '</table></div>', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_subscription_totals_table_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_subscription_totals_table_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_my_memberships_output( $output, $template_name, $template_path, $located, $args ) {
	$output = str_replace( 'class="button ', 'class="button btn btn-secondary ', $output );

	if ( empty( $args['customer_memberships'] ) ) {
		$output = str_replace( '<p>', '<div class="alert alert-info">', $output );
		$output = str_replace( '</p>', '</div>', $output );
	}

	if ( $offset = strpos( $output, '<table class="shop_table shop_table_responsive my_account_orders my_account_memberships">' ) ) {
        $output = substr_replace( $output, ' table', $offset + 87, 0 );
        $output = substr_replace( $output, '<div class="table-responsive">', $offset, 0 );
        $offset = strpos( $output, '</table>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );
	}

	if ( $offset = strpos( $output, '<td class="membership-actions order-actions"' ) ) {
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, '<div class="btn-group">', $offset + 1, 0 );
        $offset = strpos( $output, '</td>', $offset );
        $output = substr_replace( $output, '</div>', $offset, 0 );
	}

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_my_memberships_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_my_memberships_output', 10, 5 );

function enlightenment_bootstrap_memberships_members_area_pagination_links( $output ) {
	$output = str_replace( 'class="wc-memberships-members-area-pagination"', 'class="wc-memberships-members-area-pagination pagination justify-content-center float-none text-start" style="line-height: var(--bs-body-line-height);"', $output );
	$output = str_replace( '<a title="', '<span class="page-item"><a aria-label="', $output );
	$output = str_replace( '</a>', '</a></span>', $output );
	$output = str_replace( '&nbsp;', '', $output );
	$output = str_replace( 'class="wc-memberships-members-area-page-link wc-memberships-members-area-page-link ', 'class="wc-memberships-members-area-page-link page-link ', $output );
	$output = str_replace( 'class="wc-memberships-members-area-page-linkwc-memberships-members-area-page-link ', 'class="wc-memberships-members-area-page-link page-link ', $output );
	$output = str_replace( 'class="wc-memberships-members-area-page-link ', 'class="wc-memberships-members-area-page-link page-link ', $output );
	$output = str_replace( '<span class="first">&#x25C4;</span>', '<i class="first fst-normal" aria-hidden="true">&laquo;</i>', $output );
	$output = str_replace( '<span class="prev">&#x25C2;</span>', '<i class="prev fst-normal" aria-hidden="true">&lsaquo;</i>', $output );
	$output = str_replace( '<span class="next">&#x25B8;</span>', '<i class="next fst-normal" aria-hidden="true">&rsaquo;</i>', $output );
	$output = str_replace( '<span class="last">&#x25BA;</span>', '<i class="last fst-normal" aria-hidden="true">&raquo;</i>', $output );

	if ( $offset = strpos( $output, '<span class="current">' ) ) {
        $output = substr_replace( $output, ' page-link', $offset + 20, 0 );
		$output = substr_replace( $output, '<span class="page-item active" aria-current="page">', $offset, 0 );
        $offset = strpos( $output, '</span>', $offset );
        $output = substr_replace( $output, '</span>', $offset, 0 );
	}

	return $output;
}
add_filter( 'wc_memberships_members_area_pagination_links', 'enlightenment_bootstrap_memberships_members_area_pagination_links' );

function enlightenment_bootstrap_memberships_members_area_my_membership_content_column_names( $columns ) {
	$columns['membership-content-actions'] = '';

	return $columns;
}
add_filter( 'wc_memberships_members_area_my_membership_content_column_names', 'enlightenment_bootstrap_memberships_members_area_my_membership_content_column_names' );

function enlightenment_woocommerce_bootstrap_template_myaccount_my_membership_content_output( $output, $template_name, $template_path, $located, $args ) {
	$output = str_replace( 'class="button ', 'class="button btn btn-secondary ', $output );
	$output = str_replace( 'class="membership-content-title"', 'class="membership-content-title text-truncate"', $output );
	$output = str_replace( 'class="membership-content-excerpt"', 'class="membership-content-excerpt text-truncate"', $output );
	$output = str_replace( 'class="sort-status ', 'class="sort-status text-nowrap ', $output );

	if ( empty( $args['restricted_content']->posts ) ) {
		$output = str_replace( '<p>', '<div class="alert alert-info">', $output );
		$output = str_replace( '</p>', '</div>', $output );
	}

	$offset = strpos( $output, '<table class="shop_table ' );
	if ( false !== $offset ) {
        $output = substr_replace( $output, ' table', $offset + 24, 0 );
        $output = substr_replace( $output, '<div class="table-responsive">', $offset, 0 );
        $offset = strpos( $output, '</table>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );
	}

	$offset = strpos( $output, '<td class="membership-content-actions order-actions"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, '<div class="btn-group text-nowrap">', $offset + 1, 0 );
		$output = substr_replace( $output, ' style="min-width: auto;"', $offset, 0 );
        $offset = strpos( $output, '</td>', $offset );
        $output = substr_replace( $output, '</div>', $offset, 0 );
	}

	$start = strpos( $output, '<tfoot>' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</tfoot>', $start ) + 8;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	$output .= wc_memberships_get_members_area_page_links( $args['customer_membership']->get_plan(), 'my-membership-content', $args['restricted_content'] );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_my_membership_content_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_my_membership_content_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_myaccount_my_membership_products_output( $output, $template_name, $template_path, $located, $args ) {
	$output = str_replace( 'class="button ', 'class="button btn btn-secondary ', $output );
	$output = str_replace( 'class="membership-product-title"', 'class="membership-product-title text-truncate"', $output );
	$output = str_replace( 'class="membership-product-excerpt"', 'class="membership-product-excerpt text-truncate"', $output );
	$output = str_replace( 'class="sort-status ', 'class="sort-status text-nowrap ', $output );

	if ( empty( $args['restricted_products']->posts ) ) {
		$output = str_replace( '<p>', '<div class="alert alert-info">', $output );
		$output = str_replace( '</p>', '</div>', $output );
	}

	$offset = strpos( $output, '<table class="shop_table ' );
	if ( false !== $offset ) {
        $output = substr_replace( $output, ' table', $offset + 24, 0 );
        $output = substr_replace( $output, '<div class="table-responsive">', $offset, 0 );
        $offset = strpos( $output, '</table>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );
	}

	$offset = strpos( $output, '<td class="membership-product-actions order-actions"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, '<div class="btn-group text-nowrap">', $offset + 1, 0 );
		$output = substr_replace( $output, ' style="min-width: auto;"', $offset, 0 );
        $offset = strpos( $output, '</td>', $offset );
        $output = substr_replace( $output, '</div>', $offset, 0 );
	}

	$start = strpos( $output, '<tfoot>' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</tfoot>', $start ) + 8;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	$output .= wc_memberships_get_members_area_page_links( $args['customer_membership']->get_plan(), 'my-membership-products', $args['restricted_products'] );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_my_membership_products_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_my_membership_products_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_myaccount_my_membership_discounts_output( $output, $template_name, $template_path, $located, $args ) {
	$output = str_replace( 'class="button ', 'class="button btn btn-secondary ', $output );
	$output = str_replace( 'class="membership-discount-title"', 'class="membership-discount-title text-truncate"', $output );
	$output = str_replace( 'class="membership-discount-excerpt"', 'class="membership-discount-excerpt text-truncate"', $output );
	$output = str_replace( 'class="membership-product-excerpt"', 'class="membership-product-excerpt text-truncate"', $output );
	$output = str_replace( 'class="sort-status ', 'class="sort-status text-nowrap ', $output );

	if ( empty( $args['discounted_products']->posts ) ) {
		$output = str_replace( '<p>', '<div class="alert alert-info">', $output );
		$output = str_replace( '</p>', '</div>', $output );
	}

	$offset = strpos( $output, '<table class="shop_table ' );
	if ( false !== $offset ) {
        $output = substr_replace( $output, ' table', $offset + 24, 0 );
        $output = substr_replace( $output, '<div class="table-responsive">', $offset, 0 );
        $offset = strpos( $output, '</table>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );
	}

	$offset = strpos( $output, '<td class="membership-discount-actions order-actions"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, '<div class="btn-group text-nowrap">', $offset + 1, 0 );
		$output = substr_replace( $output, ' style="min-width: auto;"', $offset, 0 );
        $offset = strpos( $output, '</td>', $offset );
        $output = substr_replace( $output, '</div>', $offset, 0 );
	}

	$start = strpos( $output, '<tfoot>' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</tfoot>', $start ) + 8;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	$output .= wc_memberships_get_members_area_page_links( $args['customer_membership']->get_plan(), 'my-membership-discounts', $args['discounted_products'] );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_my_membership_discounts_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_my_membership_discounts_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_memberships_members_area_my_membership_notes_column_names( $columns ) {
	$columns['membership-note-actions'] = '';

	return $columns;
}
add_filter( 'wc_memberships_members_area_my_membership_notes_column_names', 'enlightenment_woocommerce_bootstrap_memberships_members_area_my_membership_notes_column_names' );

function enlightenment_woocommerce_bootstrap_memberships_note_modal( $note ) {
	ob_start();
	?>
	<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#note-<?php echo $note->comment_ID; ?>">
		<?php _e( 'View', 'enlightenment' ) ?>
	</button>

	<div class="membership-note-content-modal modal fade" id="note-<?php echo $note->comment_ID; ?>" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php _e( 'Close', 'enlightenment' ) ?>"></button>
				</div>

				<div class="modal-body">
					<?php echo wpautop( wp_kses_post( $note->comment_content ) ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php

	$output = ob_get_clean();

	echo apply_filters( 'enlightenment_woocommerce_bootstrap_memberships_note_modal', $output );
}
add_action( 'wc_memberships_members_area_my_membership_notes_column_membership-note-actions', 'enlightenment_woocommerce_bootstrap_memberships_note_modal' );

function enlightenment_woocommerce_bootstrap_template_myaccount_my_membership_notes_output( $output, $template_name, $template_path, $located, $args ) {
	$output = str_replace( 'class="button ', 'class="button btn btn-secondary ', $output );
	$output = str_replace( 'class="membership-note-content"', 'class="membership-note-content text-truncate"', $output );

	if ( empty( $args['customer_notes'] ) ) {
		$output = str_replace( '<p>', '<div class="alert alert-info">', $output );
		$output = str_replace( '</p>', '</div>', $output );
	}

	$offset = strpos( $output, '<table class="shop_table ' );
	if ( false !== $offset ) {
        $output = substr_replace( $output, ' table', $offset + 24, 0 );
        $output = substr_replace( $output, '<div class="table-responsive">', $offset, 0 );
        $offset = strpos( $output, '</table>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_my_membership_notes_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_my_membership_notes_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_myaccount_my_membership_details_output( $output, $template_name, $template_path, $located, $args ) {
	$output = str_replace( 'class="button ', 'class="button btn btn-secondary ', $output );

	$offset = strpos( $output, '<table class="shop_table ' );
	if ( false !== $offset ) {
        $output = substr_replace( $output, ' table', $offset + 24, 0 );
        $output = substr_replace( $output, '<div class="table-responsive">', $offset, 0 );
        $offset = strpos( $output, '</table>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );
	}

	$offset = strpos( $output, '<tr class="my-membership-detail-user-membership-actions">' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<td>', $offset );
		$offset = strpos( $output, '<td>', $offset + 1 );
        $output = substr_replace( $output, '<div class="btn-group text-nowrap">', $offset + 4, 0 );
        $offset = strpos( $output, '</td>', $offset );
        $output = substr_replace( $output, '</div>', $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_my_membership_details_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_my_membership_details_output', 10, 5 );

function enlightenment_woocommerce_bootstrap_template_myaccount_related_orders_output( $output ) {
    $output = str_replace( 'class="shop_table shop_table_responsive my_account_orders woocommerce-orders-table woocommerce-MyAccount-orders woocommerce-orders-table--orders"', 'class="shop_table shop_table_responsive my_account_orders woocommerce-orders-table woocommerce-MyAccount-orders woocommerce-orders-table--orders table"', $output );
    $output = str_replace( '<table ', '<div class="table-responsive"><table ', $output );
    $output = str_replace( '</table>', '</table></div>', $output );
    $output = str_replace( 'class="woocommerce-button button ', 'class="woocommerce-button button btn btn-secondary ', $output );

    $offset = strpos( $output, '<td class="order-actions woocommerce-orders-table__cell woocommerce-orders-table__cell-order-actions">' );
    $output = substr_replace( $output, '<div class="btn-group">', $offset + 102, 0 );
    $offset = strpos( $output, '</td>', $offset );
    $output = substr_replace( $output, '</div></td>', $offset, 5 );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_related_orders_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_related_orders_output' );

function enlightenment_woocommerce_bootstrap_template_checkout_form_change_payment_method_output( $output ) {
    $output = str_replace( '<form id="order_review" method="post">', '<form id="order_review" method="post"><div class="row">', $output );
    $output = str_replace( '<table ', '<div class="col-lg-8"><div class="order-review"><table ', $output );
    $output = str_replace( '</table>', '</table></div></div>', $output );
    $output = str_replace( '<div id="payment">', '<div class="col-lg-4"><div id="payment">', $output );
    $output = str_replace( '</form>', '</div></div></form>', $output );

    $output = str_replace( '<table ', '<div class="table-responsive"><table ', $output );
    $output = str_replace( 'class="shop_table"', 'class="shop_table table"', $output );
    $output = str_replace( '</table>', '</table></div>', $output );
    $output = str_replace( 'class="payment_methods methods"', 'class="payment_methods methods list-unstyled"', $output );
	$output = str_replace( 'class="button alt ', 'class="button alt btn btn-primary btn-lg ', $output );
    $output = str_replace( 'class="button alt"', 'class="button alt btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_checkout_form_change_payment_method_output', 'enlightenment_woocommerce_bootstrap_template_checkout_form_change_payment_method_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_html_modal_output( $output ) {
    $output = str_replace( 'class="modal-header"', 'class="modal-header p-0"', $output );
    $output = str_replace( 'class="button alt ', 'class="button alt btn btn-primary btn-lg ', $output );
	$output = str_replace( 'class="button alt"', 'class="button alt btn btn-primary btn-lg"', $output );
    $output = str_replace( 'class="modal-footer"', 'class="modal-footer px-0 pb-0"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_html_modal_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_html_modal_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_my_address_output( $output ) {
	$output = str_replace( '<p>', '<p class="alert alert-info">', $output );
    $output = str_replace( '<div class="u-columns woocommerce-Addresses col2-set addresses">', '<div class="u-columns woocommerce-Addresses col2-set addresses row">', $output );
    $output = str_replace( '<div class="u-column1 col-1 woocommerce-Address">', '<div class="u-column1 col-md-6 woocommerce-Address">', $output );
    $output = str_replace( '<div class="u-column2 col-2 woocommerce-Address">', '<div class="u-column1 col-md-6 woocommerce-Address">', $output );
    $output = str_replace( '<header class="woocommerce-Address-title title">', '<header class="woocommerce-Address-title title mb-3">', $output );
    $output = str_replace( 'class="edit"', 'class="edit btn btn-secondary"', $output );
    $output = str_replace( '<address>', '<address class="mb-0">', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_my_address_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_my_address_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_form_edit_address_output( $output ) {
    $output = str_replace( 'class="woocommerce-address-fields"', 'class="woocommerce-address-fields row"', $output );
    $output = str_replace( 'class="woocommerce-address-fields__field-wrapper"', 'class="woocommerce-address-fields__field-wrapper col-12 row px-0 mx-0"', $output );
    $output = str_replace( '<p>', '<p class="col-12">', $output );
	// $output = str_replace( '<p>', '<p class="alert alert-info">', $output );
    $output = str_replace( 'class="button ', 'class="button btn btn-primary btn-lg ', $output );
	$output = str_replace( 'class="button"', 'class="button btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_form_edit_address_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_form_edit_address_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_payment_methods_output( $output ) {
    $output = str_replace( 'class="woocommerce-MyAccount-paymentMethods shop_table shop_table_responsive account-payment-methods-table"', 'class="woocommerce-MyAccount-paymentMethods shop_table shop_table_responsive account-payment-methods-table table"', $output );
    $output = str_replace( 'class="shop_table shop_table_responsive sv-wc-payment-gateway-my-payment-methods-table wc-braintree-my-payment-methods"', 'class="shop_table shop_table_responsive sv-wc-payment-gateway-my-payment-methods-table wc-braintree-my-payment-methods table"', $output );
    $output = str_replace( 'class="shop_table shop_table_responsive sv-wc-payment-gateway-my-payment-methods-table wc-square-my-payment-methods"', 'class="shop_table shop_table_responsive sv-wc-payment-gateway-my-payment-methods-table wc-square-my-payment-methods table"', $output );

    $output = str_replace( '<table', '<div class="table-responsive"><table', $output );
    $output = str_replace( '</table>', '</table></div>', $output );
    $output = str_replace( 'class="woocommerce-Message woocommerce-Message--info woocommerce-info"', 'class="woocommerce-Message woocommerce-Message--info woocommerce-info alert alert-info"', $output );
    $output = str_replace( 'class="button"', 'class="button btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_payment_methods_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_payment_methods_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_form_add_payment_method_output( $output ) {
    $output = str_replace( 'class="woocommerce-PaymentMethods payment_methods methods"', 'class="woocommerce-PaymentMethods payment_methods methods list-unstyled"', $output );
	$output = str_replace( 'class="woocommerce-Button woocommerce-Button--alt button alt ', 'class="woocommerce-Button woocommerce-Button--alt button alt btn btn-primary btn-lg ', $output );
    $output = str_replace( 'class="woocommerce-Button woocommerce-Button--alt button alt"', 'class="woocommerce-Button woocommerce-Button--alt button alt btn btn-primary btn-lg"', $output );
    $output = str_replace( 'class="woocommerce-notice woocommerce-notice--info woocommerce-info"', 'class="woocommerce-notice woocommerce-notice--info woocommerce-info alert alert-info"', $output );

	$offset = strpos( $output, '<input id="payment_method_' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="form-check">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="input-radio"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 18, 0 );
		$offset = strpos( $output, 'for="payment_method_', $offset );
		$output = substr_replace( $output, 'class="form-check-label" ', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );

		$offset = strpos( $output, '<input id="payment_method_', $offset );
	}

	wp_dequeue_style( 'wcpay-checkout' );
	$output = str_replace( 'id="wcpay-card-element"', 'id="wcpay-card-element" class="form-control"', $output );

    $output = str_replace( 'class="form-row form-row-wide"', 'class="form-row form-row-wide mb-3"', $output );
    $output = str_replace( 'class="wc-stripe-iban-element-field"', 'class="form-control"', $output );

    $output = str_replace( '<div class="wc-braintree-credit-card-new-payment-method-form js-wc-braintree-credit-card-new-payment-method-form">', '<div class="wc-braintree-credit-card-new-payment-method-form js-wc-braintree-credit-card-new-payment-method-form row">', $output );
    $output = str_replace( '<div class="form-row form-row-wide wc-braintree-hosted-field-card-number-parent wc-braintree-hosted-field-parent">', '<div class="form-row form-row-wide mb-3 col-12 py-0 wc-braintree-hosted-field-card-number-parent wc-braintree-hosted-field-parent">', $output );
    $output = str_replace( '<div class="form-row form-row-first wc-braintree-hosted-field-card-expiry-parent wc-braintree-hosted-field-parent">', '<div class="form-row form-row-first mb-3 col-6 py-0 wc-braintree-hosted-field-card-expiry-parent wc-braintree-hosted-field-parent">', $output );
    $output = str_replace( '<div class="form-row form-row-last wc-braintree-hosted-field-card-csc-parent wc-braintree-hosted-field-parent">', '<div class="form-row form-row-last mb-3 col-6 py-0 wc-braintree-hosted-field-card-csc-parent wc-braintree-hosted-field-parent">', $output );

    $output = str_replace( ' wc-braintree-hosted-field"', ' wc-braintree-hosted-field form-control"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_form_add_payment_method_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_form_add_payment_method_output' );

function enlightenment_woocommerce_bootstrap_template_find_a_list_output( $output ) {
    $offset = strpos( $output, '<form class="wl-search-form" method="get">' );
    $offset = strpos( $output, '<input type="text"', $offset );
    $output = substr_replace( $output, '<div class="input-group">', $offset, 0 );
    $offset = strpos( $output, '</form>', $offset );
    $output = substr_replace( $output, '</div>', $offset, 0 );

    $output = str_replace( 'class="find-input"', 'class="find-input form-control me-0"', $output );
    $output = str_replace( 'class="button"', 'class="button btn btn-secondary"', $output );
    $output = str_replace( '<table class="shop_table cart wl-table wl-manage wl-find-table"', '<div class="table-responsive"><table class="shop_table cart wl-table wl-manage wl-find-table table"', $output );
    $output = str_replace( '</table>', '</table></div>', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_find_a_list_output', 'enlightenment_woocommerce_bootstrap_template_find_a_list_output' );

function enlightenment_woocommerce_bootstrap_template_my_account_lists_output( $output ) {
    $output = str_replace( '<table class="shop_table cart wl-table wl-manage"', '<div class="table-responsive"><table class="shop_table cart wl-table wl-manage table"', $output );
    $output = str_replace( '</table>', '</table></div>', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_my_account_lists_output', 'enlightenment_woocommerce_bootstrap_template_my_account_lists_output' );

function enlightenment_woocommerce_bootstrap_template_my_lists_output( $output ) {
    $output = str_replace( 'class="button alt ', 'class="button alt btn btn-secondary btn-lg ', $output );

    $row_offset = 0;
    while ( $row_offset = strpos( $output, '<div class="row-actions">', $row_offset ) ) {
        $start  = strpos( $output, '<div class="row-actions">', $row_offset );
        $end    = strpos( $output, '</div>', $start );
        $offset = $start;
        while ( $offset = strpos( $output, '<small>', $offset ) ) {
            if ( $offset > $end ) {
                break;
            }

            $output = substr_replace( $output, '', $offset, 7 );
            $end    = strpos( $output, '</div>', $start );
            $offset++;
        }

        $offset = strpos( $output, '<div class="row-actions">', $row_offset );
        $output = substr_replace( $output, '<div class="row-actions"><div class="btn-group">', $offset, 25 );
        $output = substr_replace( $output, '</div>', $end, 0 );

        $start  = strpos( $output, '<div class="row-actions">', $row_offset );
        $end    = strpos( $output, '</div>', $start );
        $offset = $start;
        while ( $offset = strpos( $output, '</small>', $offset ) ) {
            if ( $offset > $end ) {
                break;
            }

            $output = substr_replace( $output, '', $offset, 8 );
            $end    = strpos( $output, '</div>', $start );
            $offset++;
        }

        $start  = strpos( $output, '<div class="row-actions">', $row_offset );
        $end    = strpos( $output, '</div>', $start );
        $offset = $start;
        while ( $offset = strpos( $output, '<span class="', $offset ) ) {
            if ( $offset > $end ) {
                break;
            }

            $output = substr_replace( $output, '<a class="btn btn-secondary btn-sm ', $offset, 13 );
            $offset = strpos( $output, '>', $offset );
            $output = substr_replace( $output, '', $offset, 1 );
            $offset = strpos( $output, '<a ', $offset );
            $output = substr_replace( $output, '', $offset, 3 );
            $offset = strpos( $output, '</span>', $offset );
            $output = substr_replace( $output, '', $offset, 7 );
            $end    = strpos( $output, '</div>', $start );
            $offset++;
        }

        $start  = strpos( $output, '<div class="row-actions">', $row_offset );
        $end    = strpos( $output, '</div>', $start );
        $offset = $start;
        while ( $offset = strpos( $output, '|', $offset ) ) {
            if ( $offset > $end ) {
                break;
            }

            $output = substr_replace( $output, '', $offset, 1 );
            $end    = strpos( $output, '</div>', $start );
            $offset++;
        }

        $row_offset++;
    }

    $output = str_replace( 'class="wl-priv-sel"', 'class="wl-priv-sel form-control"', $output );
    $output = str_replace( 'class="button wl-but"', 'class="button wl-but btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_my_lists_output', 'enlightenment_woocommerce_bootstrap_template_my_lists_output' );

function enlightenment_woocommerce_bootstrap_template_create_a_list_output( $output ) {
    $output = str_replace( 'class="wl-form"', 'class="wl-form mb-0"', $output );
    $output = str_replace( '<div class="form-row">', '<div class="form-row mb-3">', $output );

    $offset = 0;
    while ( $offset = strpos( $output, '<input  type="radio"', $offset ) ) {
        $output = substr_replace( $output, '<div class="form-check"><input class="form-check-input" type="radio"', $offset, 20 );
        $offset = strpos( $output, '</td>', $offset );
        $output = substr_replace( $output, '', $offset, 5 );
        $offset = strpos( $output, '<td>', $offset );
        $output = substr_replace( $output, '', $offset, 4 );
        $offset = strpos( $output, '<label ', $offset );
        $output = substr_replace( $output, '<label class="form-check-label" ', $offset, 7 );
        $offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, '</label></div>', $offset, 8 );
    }

    $offset = 0;
    while ( $offset = strpos( $output, '<input  checked=\'checked\' type="radio"', $offset ) ) {
        $output = substr_replace( $output, '<div class="form-check"><input checked="checked" class="form-check-input" type="radio"', $offset, 38 );
        $offset = strpos( $output, '</td>', $offset );
        $output = substr_replace( $output, '', $offset, 5 );
        $offset = strpos( $output, '<td>', $offset );
        $output = substr_replace( $output, '', $offset, 4 );
        $offset = strpos( $output, '<label ', $offset );
        $output = substr_replace( $output, '<label class="form-check-label" ', $offset, 7 );
        $offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, '</label></div>', $offset, 8 );
    }

    $offset = 0;
    while ( $offset = strpos( $output, '<input type="radio"', $offset ) ) {
        $output = substr_replace( $output, '<div class="form-check"><input class="form-check-input" type="radio"', $offset, 19 );
        $offset = strpos( $output, '</td>', $offset );
        $output = substr_replace( $output, '', $offset, 5 );
        $offset = strpos( $output, '<td>', $offset );
        $output = substr_replace( $output, '', $offset, 4 );
        $offset = strpos( $output, '<label ', $offset );
        $output = substr_replace( $output, '<label class="form-check-label" ', $offset, 7 );
        $offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, '</label></div>', $offset, 8 );
    }

	$output = str_replace( 'for="wishlist_title"', 'for="wishlist_title" class="form-label"', $output );
	$output = str_replace( 'for="wishlist_description"', 'for="wishlist_description" class="form-label"', $output );
	$output = str_replace( 'for="wishlist_first_name"', 'for="wishlist_first_name" class="form-label"', $output );
	$output = str_replace( 'for="wishlist_last_name"', 'for="wishlist_last_name" class="form-label"', $output );
	$output = str_replace( 'for="wishlist_owner_email"', 'for="wishlist_owner_email" class="form-label"', $output );
    $output = str_replace( 'class="input-text"', 'class="input-text form-control"', $output );
    $output = str_replace( '<textarea name="wishlist_description"', '<textarea class="form-control" name="wishlist_description"', $output );
    $output = str_replace( 'class="button alt ', 'class="button alt btn btn-primary btn-lg ', $output );
	$output = str_replace( 'class="button alt"', 'class="button alt btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_create_a_list_output', 'enlightenment_woocommerce_bootstrap_template_create_a_list_output' );

function enlightenment_woocommerce_bootstrap_wishlist_wishlist_created_message( $output ) {
    return sprintf( '<div>%s</div>', $output );
}
add_filter( 'woocommerce_wishlist_wishlist_created_message', 'enlightenment_woocommerce_bootstrap_wishlist_wishlist_created_message' );

function enlightenment_woocommerce_bootstrap_template_edit_my_list_output( $output ) {
    $output = str_replace( 'class="wl-intro"', 'class="wl-intro d-flex flex-wrap align-items-center mb-4"', $output );
    $output = str_replace( 'class="wl-intro-desc"', 'class="wl-intro-desc w-100 order-1"', $output );
    $output = str_replace( 'class="wl-share-url"', 'class="wl-share-url w-100 order-1"', $output );
    $output = str_replace( 'class="wl-meta-share"', 'class="wl-meta-share w-100 order-1"', $output );

    $offset = strpos( $output, 'class="wl-intro-desc ' );
    $offset = strpos( $output, '</div>', $offset );
    $offset = strpos( $output, '<p>', $offset );
    $output = substr_replace( $output, '<div class="btn-group ms-auto">', $offset, 3 );
    $offset = strpos( $output, 'class="wlconfirm"', $offset );
    $output = substr_replace( $output, 'class="wlconfirm btn btn-secondary"', $offset, 17 );

    if ( strpos( $output, '|', $offset ) ) {
        $offset = strpos( $output, '|', $offset );
        $output = substr_replace( $output, '', $offset, 1 );
        $offset = strpos( $output, '<a rel="nofollow"', $offset );
        $output = substr_replace( $output, '<a class="btn btn-secondary" rel="nofollow"', $offset, 17 );
    }

    $offset = strpos( $output, '</p>', $offset );
    $output = substr_replace( $output, '</div>', $offset, 4 );

    $output = str_replace( 'class="wl-tabs tabs"', 'class="tabs nav nav-tabs"', $output );
    $output = str_replace( 'class="wl-items-tab"', 'class="wl-items-tab nav-item"', $output );
    $output = str_replace( 'class="wl-settings-tab"', 'class="wl-settings-tab nav-item"', $output );
    $output = str_replace( '<a href="#tab-wl-items"', '<a href="#tab-wl-items" class="nav-link active" data-bs-toggle="tab"', $output );
    $output = str_replace( '<a href="#tab-wl-settings"', '<a href="#tab-wl-settings" class="nav-link" data-bs-toggle="tab"', $output );

    $output = str_replace( '<div class="wl-panel panel" id="tab-wl-items">', '<div class="tab-content"><div class="panel tab-pane fade show active" id="tab-wl-items">', $output );
    $output = str_replace( '<div class="wl-panel panel" id="tab-wl-settings">', '<div class="panel tab-pane fade" id="tab-wl-settings">', $output );
    $output = str_replace( '</div><!-- /tab-wl-settings panel -->', '</div><!-- /tab-wl-settings panel --></div>', $output );

    $output = str_replace( 'class="wl-form"', 'class="wl-form mb-0"', $output );

    $offset = strpos( $output, 'class="wl-row"' );
    $output = substr_replace( $output, 'class="wl-row d-flex mb-3"', $offset, 14 );
    $offset = strpos( $output, 'class="wl-row"', $offset + 1 );
    $output = substr_replace( $output, 'class="wl-row d-flex mt-3 mb-0"', $offset, 14 );

    $output = str_replace( 'class="wl-actions-table"', 'class="wl-actions-table m-0"', $output );
    $output = str_replace( 'class="wl-sel move-list-sel"', 'class="wl-sel move-list-sel form-control me-3"', $output );
    $output = str_replace( 'class="button small wl-but wl-add-to btn-apply"', 'class="button small wl-but wl-add-to btn-apply btn btn-secondary"', $output );

    $offset = strpos( $output, '<table class="cart wl-table manage' );
    $output = substr_replace( $output, '<div class="table-responsive"><table class="cart wl-table manage table mb-0', $offset, 34 );
    $offset = strpos( $output, '</table>', $offset );
    $output = substr_replace( $output, '</table></div>', $offset, 8 );

	$offset = strpos( $output, '<th class="check-column"' );
    $i      = 0;
    if ( false !== $offset ) {
		$end_a    = strpos( $output, '</th>', $offset );
        $offset_a = strpos( $output, '<input type="checkbox"', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
	        $output   = substr_replace( $output, sprintf( '<div class="form-check"><input class="form-check-input" id="checkbox-%d" type="checkbox"', $i ), $offset_a, 22 );
	        $offset_a = strpos( $output, '/>', $offset_a );
	        $output   = substr_replace( $output, sprintf( '<label class="form-check-label" for="checkbox-%d"></label></div>', $i ), $offset_a + 2, 0 );
		}
    }

    $offset = strpos( $output, '<td class="check-column"' );
    $i      = 1;
    while ( false !== $offset ) {
		$end_a    = strpos( $output, '</td>', $offset );
        $offset_a = strpos( $output, '<input type="checkbox"', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
	        $output   = substr_replace( $output, sprintf( '<div class="form-check"><input class="form-check-input" id="checkbox-%d" type="checkbox"', $i ), $offset_a, 22 );
	        $offset_a = strpos( $output, '/>', $offset_a );
	        $output   = substr_replace( $output, sprintf( '<label class="form-check-label" for="checkbox-%d"></label></div>', $i ), $offset_a + 2, 0 );

	        $i++;
		}

		$offset = strpos( $output, '</td>', $offset );
		$offset = strpos( $output, '<td class="check-column"', $offset );
    }

    $output = str_replace( 'class="input-text qty text"', 'class="input-text qty text form-control"', $output );
    $output = str_replace( 'class="wishlist-add-to-cart-button button alt"', 'class="wishlist-add-to-cart-button button alt btn btn-secondary"', $output );
	$output = str_replace( 'class="button alt wl-add-all"', 'class="button alt wl-add-all btn btn-primary"', $output );
    $output = str_replace( '<div class="form-row">', '<div class="form-row mb-3">', $output );

    $offset = strpos( $output, 'class="wl-rad-table"' );
    while ( $offset = strpos( $output, '<input type="radio"', $offset ) ) {
        $output = substr_replace( $output, '<div class="form-check"><input class="form-check-input" type="radio"', $offset, 19 );
        $offset = strpos( $output, '</td>', $offset );
        $output = substr_replace( $output, '', $offset, 5 );
        $offset = strpos( $output, '<td>', $offset );
        $output = substr_replace( $output, '', $offset, 4 );
        $offset = strpos( $output, '<label ', $offset );
        $output = substr_replace( $output, '<label class="form-check-label" ', $offset, 7 );
        $offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, '</label></div>', $offset, 8 );
    }

	$output = str_replace( 'class="stock ', 'class="stock ms-2 mb-0 ', $output );
	$output = str_replace( 'for="wishlist_title"', 'for="wishlist_title" class="form-label"', $output );
	$output = str_replace( 'for="wishlist_description"', 'for="wishlist_description" class="form-label"', $output );
	$output = str_replace( 'for="wishlist_first_name"', 'for="wishlist_first_name" class="form-label"', $output );
	$output = str_replace( 'for="wishlist_last_name"', 'for="wishlist_last_name" class="form-label"', $output );
	$output = str_replace( 'for="wishlist_owner_email"', 'for="wishlist_owner_email" class="form-label"', $output );
    $output = str_replace( 'class="input-text"', 'class="input-text form-control"', $output );
    $output = str_replace( '<textarea name="wishlist_description"', '<textarea class="form-control" name="wishlist_description"', $output );
    $output = str_replace( 'class="button alt ', 'class="button alt btn btn-primary btn-lg ', $output );
	$output = str_replace( 'class="button alt ', 'class="button alt btn btn-primary btn-lg ', $output );
	$output = str_replace( 'class="button alt"', 'class="button alt btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_edit_my_list_output', 'enlightenment_woocommerce_bootstrap_template_edit_my_list_output' );

function enlightenment_woocommerce_bootstrap_template_view_a_list_output( $output ) {
	$offset = strpos( $output, 'class="woocommerce-info woocommerce_info"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' alert alert-info d-flex align-items-center', $offset + 40, 0 );
		$offset = strpos( $output, 'class="button"' );
		$output = substr_replace( $output, ' btn btn-outline-info order-1 ms-auto', $offset + 13, 0 );
	}

	$offset = strpos( $output, '<td class="product-quantity">&nbsp;</td>' );
	if ( false !== $offset ) {
		$offset_a = strpos( $output, '<td class="product-quantity">&nbsp;</td>', $offset + 40 );

		if ( false === $offset_a ) {
			$output = substr_replace( $output, "\n" . '<td class="product-quantity">&nbsp;</td>', $offset + 40, 0 );
		}
	}

    $output = str_replace( 'class="wl-intro"', 'class="wl-intro d-flex flex-wrap align-items-center mb-4"', $output );
    $output = str_replace( 'class="entry-title"', 'class="entry-title w-100"', $output );
    $output = str_replace( 'class="wl-intro-desc"', 'class="wl-intro-desc w-100"', $output );
    $output = str_replace( 'class="wl-meta-share"', 'class="wl-meta-share w-100 order-1"', $output );

    $output = str_replace( 'class="wl-row wl-clear"', 'class="wl-row d-flex justify-content-end mb-3"', $output );
    $output = str_replace( 'class="wl-actions-table wl-right"', 'class="wl-actions-table m-0"', $output );
    $output = str_replace( 'class="wl-sel"', 'class="wl-sel form-control w-auto"', $output );
    $output = str_replace( "class='wl-sel'", 'class="wl-sel form-control w-auto"', $output );
    $output = str_replace( 'class="button small wl-but"', 'class="button small wl-but btn btn-secondary"', $output );

    $offset = strpos( $output, '<table class="shop_table shop_table_responsive cart wl-table view"' );
	if ( false !== $offset ) {
	    $output = substr_replace( $output, '<div class="table-responsive"><table class="shop_table shop_table_responsive cart wl-table view table"', $offset, 66 );
	    $offset = strpos( $output, '</table>', $offset );
	    $output = substr_replace( $output, '</table></div>', $offset, 8 );
	}

	$output = str_replace( 'class="input-text qty text"', 'class="input-text qty text form-control"', $output );
    $output = str_replace( 'class="wishlist-add-to-cart-button-view button"', 'class="wishlist-add-to-cart-button-view button btn btn-secondary"', $output );
	$output = str_replace( 'class="button wishlist-add-to-cart-button"', 'class="button wishlist-add-to-cart-button btn btn-secondary"', $output );
    $output = str_replace( 'class="button"', 'class="button btn btn-secondary"', $output );
    $output = str_replace( 'class="button alt wl-add-all"', 'class="button alt wl-add-all btn btn-primary"', $output );


    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_view_a_list_output', 'enlightenment_woocommerce_bootstrap_template_view_a_list_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_form_login_output( $output ) {
    $output = str_replace( '<div class="u-columns col2-set"', '<div class="row"', $output );
    $output = str_replace( '<div class="u-column1 col-1">', '<div class="col-lg-6">', $output );
    $output = str_replace( '<div class="u-column2 col-2">', '<div class="col-lg-6">', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_form_login_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_form_login_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_form_lost_password_output( $output ) {
	$output = str_replace( '<label for="user_login"', '<label for="user_login" class="form-label"', $output );
    $output = str_replace( 'class="woocommerce-Input woocommerce-Input--text input-text"', 'class="woocommerce-Input woocommerce-Input--text input-text form-control"', $output );
    $output = str_replace( 'class="woocommerce-Button button ', 'class="woocommerce-Button button btn btn-primary btn-lg ', $output );
	$output = str_replace( 'class="woocommerce-Button button"', 'class="woocommerce-Button button btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_form_lost_password_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_form_lost_password_output' );

function enlightenment_woocommerce_bootstrap_template_myaccount_form_reset_password_output( $output ) {
	$output = str_replace( '<label ', '<label class="form-label" ', $output );
    $output = str_replace( 'class="required"', 'class="required text-danger"', $output );
    $output = str_replace( 'class="woocommerce-Input woocommerce-Input--text input-text"', 'class="woocommerce-Input woocommerce-Input--text input-text form-control"', $output );
    $output = str_replace( 'class="woocommerce-Button button ', 'class="woocommerce-Button button btn btn-primary btn-lg ', $output );
	$output = str_replace( 'class="woocommerce-Button button"', 'class="woocommerce-Button button btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_template_myaccount_form_reset_password_output', 'enlightenment_woocommerce_bootstrap_template_myaccount_form_reset_password_output' );

function enlightenment_woocommerce_single_product_details_container() {
	$breakpoint = apply_filters( 'enlightenment_woocommerce_single_product_details_container_breakpoint', '-md' );
	$class      = sprintf( 'col%s-6', $breakpoint );

    echo enlightenment_open_tag( 'div', $class );
}

function enlightenment_woocommerce_bootstrap_single_product_layout() {
    if( ! is_singular( 'product' ) ) {
		return;
    }

    if( ! has_action( 'enlightenment_after_entry_header' ) ) {
		return;
    }

    if( ! has_action( 'enlightenment_before_entry_content' ) ) {
		return;
    }

    add_action( 'enlightenment_after_entry_header', 'enlightenment_open_row', 6 );

    add_action( 'enlightenment_after_entry_header', 'enlightenment_woocommerce_single_product_details_container', 8 );
    add_action( 'enlightenment_after_entry_header', 'enlightenment_close_div', 12 );

    add_action( 'enlightenment_before_entry_content', 'enlightenment_woocommerce_single_product_details_container', 8 );
    add_action( 'enlightenment_before_entry_content', 'enlightenment_close_div', 12 );

    add_action( 'enlightenment_before_entry_content', 'enlightenment_close_row', 14 );
}
add_action( 'enlightenment_before_entry', 'enlightenment_woocommerce_bootstrap_single_product_layout', 999 );

function enlightenment_woocommerce_bootstrap_cart_item_quantity( $output ) {
	$output = str_replace( 'input-text qty text', 'form-control input-text qty text', $output );

	return $output;
}
add_filter( 'woocommerce_cart_item_quantity', 'enlightenment_woocommerce_bootstrap_cart_item_quantity' );

function enlightenment_woocommerce_loop_add_to_cart_link( $output, $product, $args = array() ) {
	if ( false !== strpos( $output, '<div class="wp-block-button ' ) ) {
		return $output;
	}

    $class = esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' );

	$output = str_replace( sprintf( ' %s ', $class ), sprintf( ' %s btn btn-secondary ', $class ), $output );
	$output = str_replace( sprintf( ' %s"', $class ), sprintf( ' %s btn btn-secondary"', $class ), $output );
	$output = str_replace( sprintf( '"%s ', $class ), sprintf( '"%s btn btn-secondary ', $class ), $output );
	$output = str_replace( sprintf( '"%s"', $class ), sprintf( '"%s btn btn-secondary"', $class ), $output );

	// WooCommerce Bundles
	$output = str_replace( 'class="button product_type_bundle ', 'class="button product_type_bundle btn btn-secondary ', $output );

	return $output;
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'enlightenment_woocommerce_loop_add_to_cart_link', 10, 3 );

add_filter( 'enlightenment_woocommerce_pagination', 'enlightenment_bootstrap_paginate_links',    12, 2 );
add_filter( 'enlightenment_woocommerce_pagination', 'enlightenment_bootstrap_navigation_markup', 12, 2 );

function enlightenment_woocommerce_bootstrap_widget_shopping_cart_button( $output ) {
	return str_replace( '" class="button ', '" class="button btn btn-secondary ', $output );
}
add_filter( 'enlightenment_woocommerce_filter_widget_shopping_cart_buttons', 'enlightenment_woocommerce_bootstrap_widget_shopping_cart_button' );

remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message' );

function enlightenment_woocommerce_bootstrap_empty_cart_message() {
    echo '<div class="cart-empty woocommerce-info alert alert-info">' . wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your cart is currently empty.', 'enlightenment' ) ) ) . '</div>';
}
add_action( 'woocommerce_cart_is_empty', 'enlightenment_woocommerce_bootstrap_empty_cart_message' );

function enlightenment_woocommerce_bootstrap_removed_notice( $message ) {
    return str_replace( 'class="restore-item"', 'class="restore-item btn btn-outline-success ms-auto"', $message );
}
add_filter( 'woocommerce_add_message', 'enlightenment_woocommerce_bootstrap_removed_notice' );

function enlightenment_bootstrap_woocommerce_product_wrapper() {
    if ( current_theme_supports( 'enlightenment-custom-layouts' ) ) {
        $layouts = array_reverse( enlightenment_current_layout() );

        foreach ( $layouts as $layout ) {
            if ( 'inherit' == $layout ) {
                continue;
            }

            switch ( $layout ) {
                case 'sidebar-content-sidebar':
                    $colspan = 6;
                    break;

                case 'full-width' :
                    $colspan = is_cart() ? 4 : 3;
                    break;

                case 'content-sidebar':
                case 'sidebar-content':
                default:
                    $colspan = 4;
            }

            break;
        }
    } else {
        $colspan = 3;
    }

	switch ( $colspan ) {
		case 3:
			$class = sprintf( 'col-12 col-sm-6 col-lg-%s', $colspan );
			break;

		case 4:
			$class = sprintf( 'col-12 col-sm-%s', $colspan );
			break;

		case 6:
			$class = sprintf( 'col-12 col-sm-%s', $colspan );
			break;
	}

    echo enlightenment_open_tag( 'div', $class );
}

/**
 * Short circuit default layout for custom loops like related posts
**/
function enlightenment_woocommerce_bootstrap_related_custom_loop_post_metadata( $value, $post_id, $meta_key ) {
	if ( '_enlightenment_custom_layout' != $meta_key ) {
		return $value;
	}

	if ( ! is_singular( 'product' ) ) {
		return $value;
	}

	if ( $post_id == get_queried_object()->ID ) {
		return $value;
	}

	return array( get_post_meta( get_queried_object()->ID, '_enlightenment_custom_layout', true ) );
}
add_filter( 'get_post_metadata', 'enlightenment_woocommerce_bootstrap_related_custom_loop_post_metadata', 10, 3 );

function enlightenment_woocommerce_maybe_open_cart_row() {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Cart',
		),
	) ) ) {
		return;
	}

    enlightenment_open_row();
}
add_action( 'woocommerce_before_cart', 'enlightenment_woocommerce_maybe_open_cart_row', 997 );

function enlightenment_woocommerce_maybe_open_cart_form_container() {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Cart',
		),
	) ) ) {
		return;
	}

	echo enlightenment_open_tag( 'div', 'col-lg-8' );
}
add_action( 'woocommerce_before_cart', 'enlightenment_woocommerce_maybe_open_cart_form_container', 999 );

function enlightenment_woocommerce_maybe_close_cart_form_container() {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Cart',
		),
	) ) ) {
		return;
	}

	enlightenment_close_div();
}
add_action( 'woocommerce_before_cart_collaterals', 'enlightenment_woocommerce_maybe_close_cart_form_container', 1 );

function enlightenment_woocommerce_maybe_open_cart_collaterals_container() {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Cart',
		),
	) ) ) {
		return;
	}

	echo enlightenment_open_tag( 'div', 'col-lg-4' );
}
add_action( 'woocommerce_before_cart_collaterals', 'enlightenment_woocommerce_maybe_open_cart_collaterals_container', 999 );

function enlightenment_woocommerce_maybe_close_cart_collaterals_container() {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Cart',
		),
	) ) ) {
		return;
	}

	enlightenment_close_div();
}
add_action( 'woocommerce_after_cart', 'enlightenment_woocommerce_maybe_close_cart_collaterals_container', 1 );

function enlightenment_woocommerce_maybe_close_cart_row() {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Cart',
		),
	) ) ) {
		return;
	}

	enlightenment_close_row();
}
add_action( 'woocommerce_after_cart', 'enlightenment_woocommerce_maybe_close_cart_row', 3 );

function enlightenment_woocommerce_bootstrap_checkout_customer_details( $output ) {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Checkout',
		),
	) ) ) {
		return $output;
	}

	$output = str_replace( ' class="col2-set"', '', $output );
    $output = str_replace( ' class="col-1"', '', $output );
    $output = str_replace( ' class="col-2"', '', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_checkout_customer_details', 'enlightenment_woocommerce_bootstrap_checkout_customer_details' );

function enlightenment_woocommerce_bootstrap_maybe_close_checkout_fields_container() {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Checkout',
		),
	) ) ) {
		return;
	}

	if ( ! WC()->checkout()->get_checkout_fields() ) {
		return;
	}

	echo enlightenment_close_tag( 'div' );
}
add_action( 'woocommerce_checkout_before_order_review_heading', 'enlightenment_woocommerce_bootstrap_maybe_close_checkout_fields_container' );

function enlightenment_woocommerce_bootstrap_maybe_open_order_review_container() {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Checkout',
		),
	) ) ) {
		return;
	}

	echo enlightenment_open_tag( 'div', 'col-lg-4' );
}
add_action( 'woocommerce_checkout_before_order_review_heading', 'enlightenment_woocommerce_bootstrap_maybe_open_order_review_container' );

function enlightenment_woocommerce_bootstrap_maybe_close_order_review_container() {
	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'   => 'class',
			'value' => 'ElementorPro\Modules\Woocommerce\Widgets\Checkout',
		),
	) ) ) {
		return;
	}

	enlightenment_close_div();
}
add_action( 'woocommerce_checkout_after_order_review', 'enlightenment_woocommerce_bootstrap_maybe_close_order_review_container' );

function enlightenment_woocommerce_bootstrap_form_field_args( $args, $key ) {
    switch ( $args['type'] ) {
        case 'select':
			$args['input_class'][] = 'form-select';

			break;

		case 'country':
			$countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

			if ( 1 < count( $countries ) ) {
				$args['input_class'][] = 'form-select';
			}

		case 'state':
			$for_country = isset( $args['country'] ) ? $args['country'] : WC()->checkout->get_value( 'billing_state' === $key ? 'billing_country' : 'shipping_country' );
			$states      = WC()->countries->get_states( $for_country );

			if ( ! is_null( $for_country ) && is_array( $states ) && ! empty( $states ) ) {
				$args['input_class'][] = 'form-select';
			} else {
				$args['input_class'][] = 'form-control';
			}

			break;

		case 'checkbox':
        case 'radio':
            $args['label_class'][] = 'form-check-label';
            $args['input_class'][] = 'form-check-input';

            break;

		default:
            $args['input_class'][] = 'form-control';
    }

    return $args;
}
add_filter( 'woocommerce_form_field_args', 'enlightenment_woocommerce_bootstrap_form_field_args', 10, 2 );

function enlightenment_woocommerce_bootstrap_form_field_checkbox( $output, $key, $args ) {
    $output = str_replace( '<span class="woocommerce-input-wrapper">', '<span class="woocommerce-input-wrapper form-check">', $output );
    $start  = strpos( $output, '<label ' );
    $end    = strpos( $output, '>', $start );
    $length = $end - $start + 1;
    $label  = substr( $output, $start, $length );
    $output = substr_replace( $output, '', $start, $length );
    $offset = strpos( $output, '<input ', $start );
    $offset = strpos( $output, '/> ', $offset );

    if ( false === strpos( $label, ' for="' ) ) {
        $label  = str_replace( '<label ', sprintf( '<label for="%s" ', esc_attr( $args['id'] ) ), $label );
    }

	if ( false === strpos( $label, ' form-check-label' ) ) {
	    if ( false === strpos( $label, ' class="' ) ) {
	        $label  = str_replace( '<label ', '<label class="form-check-label" ', $label );
	    } else {
			$label  = str_replace( ' class="', ' class="form-check-label ', $label );
		}
	}

    $output = substr_replace( $output, $label, $offset + 3, 0 );

    return $output;
}
add_filter( 'woocommerce_form_field_checkbox', 'enlightenment_woocommerce_bootstrap_form_field_checkbox', 10, 3 );

function enlightenment_woocommerce_bootstrap_form_field_radio( $output ) {

    return $output;
}
add_filter( 'woocommerce_form_field_radio', 'enlightenment_woocommerce_bootstrap_form_field_radio' );

function enlightenment_woocommerce_bootstrap_form_field( $output, $key, $args ) {
    if ( false !== strpos( $output, 'class="form-row form-row-first' ) ) {
        $output = str_replace( 'class="form-row form-row-first', 'class="form-row form-row-first mb-3 col-md-6', $output );
    } elseif ( false !== strpos( $output, 'class="form-row form-row-last' ) ) {
        $output = str_replace( 'class="form-row form-row-last', 'class="form-row form-row-last mb-3 col-md-6', $output );
    } elseif ( false !== strpos( $output, 'class="form-row form-row-wide' ) ) {
        $output = str_replace( 'class="form-row form-row-wide', 'class="form-row form-row-wide mb-3 col-12', $output );
    } elseif ( false !== strpos( $output, 'class="form-row notes' ) ) {
        $output = str_replace( 'class="form-row notes', 'class="form-row notes mb-0 col-12', $output );
    } elseif ( false !== strpos( $output, 'class="form-row validate-required' ) ) {
        $output = str_replace( 'class="form-row validate-required', 'class="form-row validate-required mb-3 col-12', $output );
    }

    $output = str_replace( '<span class="woocommerce-input-wrapper">', '<span class="woocommerce-input-wrapper d-block">', $output );
    $output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );

	if ( 'checkbox' != $args['type'] && 'radio' != $args['type'] ) {
	    $output = str_replace( '<label ', '<label class="form-label" ', $output );
	}

    return $output;
}
add_filter( 'woocommerce_form_field', 'enlightenment_woocommerce_bootstrap_form_field', 10, 3 );

function enlightenment_woocommerce_bootstrap_payment_gateway_form_saved_payment_methods_html( $output ) {
    $output = str_replace( 'class="woocommerce-SavedPaymentMethods wc-saved-payment-methods"', 'class="woocommerce-SavedPaymentMethods wc-saved-payment-methods list-unstyled"', $output );
    $output = str_replace( '<input ', '<div class="form-check"><input ', $output );
    $output = str_replace( 'class="woocommerce-SavedPaymentMethods-tokenInput"', 'class="woocommerce-SavedPaymentMethods-tokenInput form-check-input"', $output );
    $output = str_replace( '<label ', '<label class="form-check-label" ', $output );
    $output = str_replace( '</label>', '</label></div>', $output );

    return $output;
}
add_filter( 'wc_payment_gateway_form_saved_payment_methods_html', 'enlightenment_woocommerce_bootstrap_payment_gateway_form_saved_payment_methods_html' );

function enlightenment_woocommerce_bootstrap_stripe_credit_card_form( $output ) {
    if ( false !== strpos( $output, 'class="stripe-card-group"' ) ) {
        $output = str_replace( '<div class="form-row form-row-wide">', '<div class="row"><div class="form-row form-row-wide mb-3 col-12">', $output );
        $output = str_replace( '<div class="form-row form-row-first">', '<div class="form-row form-row-first mb-3 col-6">', $output );
        $output = str_replace( '<div class="form-row form-row-last">', '<div class="form-row form-row-last mb-3 col-6">', $output );
		$output = str_replace( 'for="stripe-card-element"', 'class="form-label" for="stripe-card-element"', $output );
        $output = str_replace( 'class="stripe-card-group"', 'class="stripe-card-group mb-0"', $output );
		$output = str_replace( 'for="stripe-exp-element"', 'class="form-label" for="stripe-exp-element"', $output );
		$output = str_replace( 'for="stripe-cvc-element"', 'class="form-label" for="stripe-cvc-element"', $output );
        $output = str_replace( 'class="wc-stripe-elements-field"', 'class="form-control"', $output );
        $output = str_replace( '<div class="clear"></div>', '</div>', $output );
    } elseif ( false !== strpos( $output, 'class="wc-stripe-elements-field"' ) ) {
		$output = str_replace( 'for="card-element"', 'class="form-label" for="card-element"', $output );
        $output = str_replace( 'class="wc-stripe-elements-field"', 'class="form-control"', $output );
        $output = substr_replace( $output, '<div class="mb-3">', 0, 0 );
        $pos    = strpos( $output, '</div>' );
        $output = substr_replace( $output, '</div>', $pos, 0 );
	}

    return $output;
}
add_filter( 'enlightenment_woocommerce_filter_credit_card_form', 'enlightenment_woocommerce_bootstrap_stripe_credit_card_form' );

function enlightenment_woocommerce_bootstrap_stripe_elements_classes( $classes ) {
    return array(
        'focus'   => 'focused',
        'empty'   => 'empty',
        'invalid' => 'invalid is-invalid',
    );
}
add_filter( 'wc_stripe_elements_classes', 'enlightenment_woocommerce_bootstrap_stripe_elements_classes' );

function enlightenment_woocommerce_bootstrap_order_button( $output ) {
	return str_replace( 'class="button ', 'class="button btn btn-primary btn-lg ', $output );
}
add_filter( 'woocommerce_order_button_html', 'enlightenment_woocommerce_bootstrap_order_button' );

function enlightenment_woocommerce_bootstrap_account_menu_item_classes( $classes ) {
    $classes[] = 'nav-item';

    return $classes;
}
add_filter( 'woocommerce_account_menu_item_classes', 'enlightenment_woocommerce_bootstrap_account_menu_item_classes' );

function enlightenment_woocommerce_bootstrap_account_payment_methods_column_actions( $method ) {
    if ( empty( $method['actions'] ) ) {
        return;
    }

    echo '<div class="btn-group" role="group">';

    foreach ( $method['actions'] as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
        printf( '<a href="%s" class="button btn btn-secondary %s">%s</a>', esc_url( $action['url'] ), sanitize_html_class( $key ), esc_html( $action['name'] ) );
    }

    echo '</div>';
}
add_action( 'woocommerce_account_payment_methods_column_actions', 'enlightenment_woocommerce_bootstrap_account_payment_methods_column_actions' );

function enlightenment_woocommerce_bootstrap_my_payment_methods_table_method_title_html( $output ) {
	return str_replace( 'class="nickname"', 'class="nickname form-control"', $output );
}
add_filter( 'wc_braintree_my_payment_methods_table_method_title_html', 'enlightenment_woocommerce_bootstrap_my_payment_methods_table_method_title_html' );

function enlightenment_woocommerce_bootstrap_payment_method_expires_html() {
	$output = ob_get_clean();

	$output = str_replace( 'class="expires"', 'class="expires form-control"', $output );

	echo $output;
}

function enlightenment_woocommerce_account_payment_methods_column_expires_actions() {
	if ( has_action( 'woocommerce_account_payment_methods_column_expires' ) ) {
		add_action( 'woocommerce_account_payment_methods_column_expires', 'enlightenment_ob_start', 8 );
		add_action( 'woocommerce_account_payment_methods_column_expires', 'enlightenment_woocommerce_bootstrap_payment_method_expires_html', 12 );
	}
}
add_action( 'wp', 'enlightenment_woocommerce_account_payment_methods_column_expires_actions', 12 );

function enlightenment_woocommerce_bootstrap_edit_account_form( $output ) {
    $output = str_replace( 'class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first"', 'class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first mb-3 col-md-6"', $output );
    $output = str_replace( 'class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last"', 'class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last mb-3 col-md-6"', $output );
    $output = str_replace( 'class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide"', 'class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-3 col-12"', $output );
    $output = str_replace( '<p>', '<p class="mb-3 col-12">', $output );

    $output = str_replace( '<fieldset>', '<fieldset class="col-12"><div class="row">', $output );
    $output = str_replace( '</fieldset>', '</div></fieldset>', $output );
    $output = str_replace( '<legend>', '<legend class="col-12">', $output );

	$output = str_replace( '<label ', '<label class="form-label" ', $output );
    $output = str_replace( ' input-text"', ' input-text form-control"', $output );
	$output = str_replace( 'id="wcpay_selected_currency"', 'id="wcpay_selected_currency" class="form-select"', $output );
    $output = str_replace( 'class="woocommerce-Button button ', 'class="woocommerce-Button button btn btn-primary btn-lg ', $output );
	$output = str_replace( 'class="woocommerce-Button button"', 'class="woocommerce-Button button btn btn-primary btn-lg"', $output );

	$offset = strpos( $output, '</em></span>' );
	while ( false !== $offset ) {
		$offset = strrpos( $output, '<span', $offset - strlen( $output ) );
	    $output = substr_replace( $output, ' class="d-block form-text"', $offset + 5, 0 );
		$offset = strpos( $output, '<em>', $offset );
		$output = substr_replace( $output, '', $offset, 4 );
		$offset = strpos( $output, '</em>', $offset );
		$output = substr_replace( $output, '', $offset, 5 );

		$offset = strpos( $output, '</em></span>', $offset );
	}

	$offset = strpos( $output, 'for="wcpay_selected_currency"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first mb-3 col-md-6"', $offset - strlen( $output ) );
	    $output = substr_replace( $output, '12', $offset + 92, 1 );
		$output = substr_replace( $output, 'wide', $offset + 50, 5 );
	}

    $output = sprintf( '<div class="row">%s</div>', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_edit_account_form', 'enlightenment_woocommerce_bootstrap_edit_account_form' );

function enlightenment_woocommerce_bootstrap_login_form( $output ) {
	$output = str_replace( '<p>', '<p class="col-12">', $output );
    $output = str_replace( 'class="form-row form-row-first"', 'class="form-row form-row-first mb-3 col-md-6"', $output );
    $output = str_replace( 'class="form-row form-row-last"', 'class="form-row form-row-last mb-3 col-md-6"', $output );
    $output = str_replace( '<p class="form-row">', '<p class="form-row col-12"><span class="form-check">', $output );
    $output = str_replace( 'class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide"', 'class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-3 col-12"', $output );
    $output = str_replace( 'class="lost_password"', 'class="lost_password col-12"', $output );
    $output = str_replace( 'class="woocommerce-LostPassword lost_password"', 'class="woocommerce-LostPassword lost_password col-12 mb-0"', $output );
    $output = str_replace( '<div class="clear"></div>', '', $output );
	$output = str_replace( '<label for="username"', '<label for="username" class="form-label"', $output );
	$output = str_replace( '<label for="password"', '<label for="password" class="form-label"', $output );
    $output = str_replace( 'class="woocommerce-Input woocommerce-Input--text input-text"', 'class="woocommerce-Input woocommerce-Input--text input-text form-control"', $output );
    $output = str_replace( 'class="input-text ', 'class="input-text form-control ', $output );
	$output = str_replace( 'class="input-text"', 'class="input-text form-control"', $output );
    $output = str_replace( '<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">', '', $output );
    $output = str_replace( 'class="woocommerce-form__input woocommerce-form__input-checkbox"', 'class="woocommerce-form__input woocommerce-form__input-checkbox form-check-input"', $output );

    if ( $offset = strpos( $output, 'id="rememberme"' ) ) {
        $offset = strpos( $output, '/> <span>', $offset );
        $output = substr_replace( $output, '<label for="rememberme" class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme form-check-label">', $offset + 3, 0 );
    }

    $output = str_replace( '<button type="submit"', '</p> <p class="form-row mb-3 col-12"><button type="submit"', $output );

    if ( $offset = strpos( $output, 'id="rememberme"' ) ) {
        $offset = strpos( $output, '</p>', $offset );
        $output = substr_replace( $output, '</span>', $offset, 0 );
    }

    $output = str_replace( 'class="woocommerce-button button ', 'class="woocommerce-button button btn btn-primary btn-lg ', $output );

    $output = sprintf( '<div class="row">%s</div>', $output );

	return $output;
}
add_filter( 'enlightenment_woocommerce_filter_login_form', 'enlightenment_woocommerce_bootstrap_login_form' );

function enlightenment_woocommerce_bootstrap_register_form( $output ) {
    $output = str_replace( 'class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide"', 'class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide col-12"', $output );
    $output = str_replace( '<p>', '<p class="col-12">', $output );
    $output = str_replace( 'class="woocommerce-FormRow form-row"', 'class="woocommerce-FormRow form-row col-12 mb-0"', $output );
	$output = str_replace( 'class="woocommerce-form-row form-row"', 'class="woocommerce-form-row form-row col-12 mb-0"', $output );
    $output = str_replace( '<div class="woocommerce-privacy-policy-text"><p class="col-12">', '<div class="woocommerce-privacy-policy-text col-12"><p>', $output );

	$output = str_replace( '<label for="reg_username"', '<label class="form-label" for="reg_username"', $output );
	$output = str_replace( '<label for="reg_email"', '<label class="form-label" for="reg_email"', $output );
	$output = str_replace( '<label for="reg_password"', '<label class="form-label" for="reg_password"', $output );
    $output = str_replace( 'class="woocommerce-Input woocommerce-Input--text input-text"', 'class="woocommerce-Input woocommerce-Input--text input-text form-control"', $output );
    $output = str_replace( 'class="input-text"', 'class="input-text form-control"', $output );
    $output = str_replace( 'class="woocommerce-Button woocommerce-button ', 'class="woocommerce-Button woocommerce-button btn btn-primary btn-lg ', $output );

    return sprintf( '<div class="row">%s</div>', $output );
}
add_filter( 'enlightenment_woocommerce_filter_register_form', 'enlightenment_woocommerce_bootstrap_register_form' );

function enlightenment_woocommerce_bootstrap_notice_message( $message ) {
    return str_replace( sprintf( '<a href="%s">', wc_logout_url() ), sprintf( '<a class="btn btn-outline-success ms-auto" href="%s">', wc_logout_url() ), $message );
}
add_filter( 'woocommerce_add_success', 'enlightenment_woocommerce_bootstrap_notice_message' );

function enlightenment_woocommerce_bootstrap_demo_store( $output ) {
    $output = str_replace( 'class="woocommerce-store-notice demo_store"', 'class="woocommerce-store-notice demo_store alert alert-info fixed-bottom mx-4 mb-4"', $output );

    $output = str_replace( 'style="display:none;">', 'style="display:none;"><span class="d-flex align-items-center">', $output );

    $output = str_replace( '</p>', '</span></p>', $output );

    $output = str_replace( 'class="woocommerce-store-notice__dismiss-link"', 'class="woocommerce-store-notice__dismiss-link btn btn-outline-info ms-auto"', $output );

    return $output;
}
add_filter( 'woocommerce_demo_store', 'enlightenment_woocommerce_bootstrap_demo_store' );

function enlightenment_woocommerce_bootstrap_blocks_product_grid_item_html( $output, $data, $product ) {
	if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
        return $output;
    }

	if ( ! empty( $data->title ) ) {
		$output = str_replace( 'class="wc-block-grid__product-title"', 'class="wc-block-grid__product-title h3"', $output );
	}

	if ( ! empty( $data->badge ) ) {
		$output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );
	}

	if ( ! empty( $data->button ) ) {
		$button = $data->button;
		$button = str_replace( 'class="wp-block-button wc-block-grid__product-add-to-cart"', 'class="wp-block-button btn-group"', $button );
	    $button = str_replace( 'class="wp-block-button__link add_to_cart_button', 'class="add_to_cart_button btn btn-secondary', $button );
		$button = str_replace( 'class="wp-block-button__link  add_to_cart_button', 'class="add_to_cart_button btn btn-secondary', $button );
		$button = str_replace( 'class="wp-block-button__link wp-element-button add_to_cart_button', 'class="wp-element-button add_to_cart_button btn btn-secondary', $button );
		$button = str_replace( 'class="wp-block-button__link  wp-element-button add_to_cart_button', 'class="wp-element-button add_to_cart_button btn btn-secondary', $button );

		$output = str_replace( $data->button, $button, $output );
	}

    return $output;
}
add_filter( 'woocommerce_blocks_product_grid_item_html', 'enlightenment_woocommerce_bootstrap_blocks_product_grid_item_html', 10, 3 );

function enlightenment_woocommerce_bootstrap_product_grid_block( $output, $block ) {
	$columns = ( isset( $block['attrs'] ) && isset( $block['attrs']['columns'] ) ) ? $block['attrs']['columns'] : 3;
	$colspan = 0;

    switch ( $columns ) {
        case 1:
            $colspan = 12;
            break;

        case 2:
            $colspan = 6;
            break;

        case 3:
            $colspan = 4;
            break;

        case 4:
            $colspan = 3;
            break;

        case 6:
            $colspan = 2;
            break;
    }

    if ( 5 == $columns ) {
        $start  = strpos( $output, '<ul class="wc-block-grid__products">' );
        $end    = strpos( $output, '</ul>', $start );
        $offset = $start;
        $did    = 0;

        while ( false !== $offset ) {
            $offset = strpos( $output, '<li class="wc-block-grid__product">', $offset );

            if ( 5 == $did ) {
                $output = substr_replace( $output, '</div><div class="row">', $offset, 0 );
                $offset = strpos( $output, '<li class="wc-block-grid__product">', $offset );
                $did    = 0;
            }

            $offset++;
            $did++;

            $offset = strpos( $output, '<li class="wc-block-grid__product">', $offset );
            $end    = strpos( $output, '</ul>', $start );

            if ( $offset > $end ) {
                break;
            }
        }
    }

	switch ( $colspan ) {
		case 12:
			$class = sprintf( 'col-%s', $colspan );
			break;

		case 6:
			$class = sprintf( 'col-sm-%s', $colspan );
			break;

		case 4:
			$class = sprintf( 'col-md-%s', $colspan );
			break;

		case 3:
			$class = sprintf( 'col-sm-6 col-lg-%s', $colspan );
			break;

		case 2:
			$class = sprintf( 'col-sm-6 col-md-4 col-xl-%s', $colspan );
			break;

		case 0:
		default:
			$class = 'col-sm-6 col-md-4 col-lg flex-sm-grow-1 flex-sm-shrink-0 mw-100';
			break;
	}

    $output = str_replace( '<ul class="wc-block-grid__products">', '<div class="wc-block-grid__products d-block mx-0"><div class="row">', $output );
    $output = str_replace( '</ul>', '</div></div>', $output );

    $output = str_replace( '<li class="wc-block-grid__product">', sprintf( '<div class="%s"><div class="product">', $class ), $output );
    $output = str_replace( '</li>', '</div></div>', $output );

    return $output;
}
add_filter( 'enlightenment_render_block_woocommerce_product_best_sellers', 'enlightenment_woocommerce_bootstrap_product_grid_block', 10, 2 );
add_filter( 'enlightenment_render_block_woocommerce_product_category', 'enlightenment_woocommerce_bootstrap_product_grid_block', 10, 2 );
add_filter( 'enlightenment_render_block_woocommerce_product_new', 'enlightenment_woocommerce_bootstrap_product_grid_block', 10, 2 );
add_filter( 'enlightenment_render_block_woocommerce_product_on_sale', 'enlightenment_woocommerce_bootstrap_product_grid_block', 10, 2 );
add_filter( 'enlightenment_render_block_woocommerce_product_top_rated', 'enlightenment_woocommerce_bootstrap_product_grid_block', 10, 2 );
add_filter( 'enlightenment_render_block_woocommerce_handpicked_products', 'enlightenment_woocommerce_bootstrap_product_grid_block', 10, 2 );
add_filter( 'enlightenment_render_block_woocommerce_products_by_attribute', 'enlightenment_woocommerce_bootstrap_product_grid_block', 10, 2 );
add_filter( 'enlightenment_render_block_woocommerce_product_tag', 'enlightenment_woocommerce_bootstrap_product_grid_block', 10, 2 );

function enlightenment_woocommerce_bootstrap_product_collection_block( $output, $block ) {
	if ( empty( $block['attrs'] ) ) {
		return $output;
	}

	if ( isset( $block['attrs']['align'] ) && 'full' == $block['attrs']['align'] ) {
		$offset = strpos( $output, ' class="wp-block-woocommerce-product-collection ' );

		if ( false === $offset ) {
			$offset = strpos( $output, ' wp-block-woocommerce-product-collection ' );
		}

		if ( false !== $offset ) {
			$offset  = strpos( $output, '>', $offset );
			$output  = substr_replace( $output, '<div class="container">', $offset + 1, 0 );
			$output .= '</div>';
		}
	}

	if (
		( isset( $block['attrs']['displayLayout'] ) && isset( $block['attrs']['displayLayout']['type'] ) )
		&&
		'flex' == $block['attrs']['displayLayout']['type']
	) {
		$columns = isset( $block['attrs']['displayLayout']['columns'] ) ? $block['attrs']['displayLayout']['columns'] : 3;
		$did     = 0;

		$offset = strpos( $output, '<ul ' );
		if ( false !== $offset ) {
			$class  = 5 == $columns ? 'row flex-xl-nowrap list-unstyled' : 'row list-unstyled';

			$output = substr_replace( $output, 'div', $offset + 1, 2 );
			$offset = strpos( $output, ' class="', $offset );
			$offset = strpos( $output, '"', $offset + 8 );
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, "\n" . sprintf( '<ul class="%s">', $class ), $offset + 1, 0 );

			$offset_r = 0;
			for ( $i = 0; $i <= $did; $i++ ) {
				$offset_r = strrpos( $output, '</ul>', $offset_r ? ( $offset_r - strlen( $output ) - 1 ) : 0 );

				if ( false === $offset_r ) {
					break;
				}
			}
			if ( false !== $offset_r ) {
				$output = substr_replace( $output, sprintf( '<!-- .%s -->', str_replace( ' ', '.', $class ) ) . "\n" . '</div>', $offset_r + 5, 0 );
			}

			$did++;

			$offset = strpos( $output, '<ul ', $offset + 1 );
		}

		$output = str_replace( '"wc-block-product-template__responsive ',  '"', $output );
		$output = str_replace( ' wc-block-product-template__responsive ',  ' ', $output );
		$output = str_replace( ' wc-block-product-template__responsive"',  '"', $output );

        if ( 5 == $columns ) {
			$start  = strpos( $output, '<ul class="row flex-xl-nowrap list-unstyled">' );
			$end    = strpos( $output, '</ul><!-- .row.flex-xl-nowrap.list-unstyled -->', $start );

			while ( false !== $start ) {
	            $offset = strpos( $output, ' class="wc-block-product ', $start );
	            $did    = 0;

	            while ( false !== $offset && $offset < $end ) {
	                if ( 5 == $did ) {
						$offset = strrpos( $output, '<li ', $offset - strlen( $output ) );
	                    $output = substr_replace( $output, '</ul><ul class="row flex-xl-nowrap list-unstyled">', $offset, 0 );
	                    $offset = strpos( $output, ' class="wc-block-product ', $offset );
	                    $did    = 0;
	                }

	                $did++;

					$end    = strpos( $output, '</ul><!-- .row.flex-xl-nowrap.list-unstyled -->', $start );
					$offset = strpos( $output, '>', $offset );
	                $offset = strpos( $output, ' class="wc-block-product ', $offset );
	            }

				$start  = strpos( $output, '<ul class="row flex-xl-nowrap list-unstyled">', $end );
				$end    = strpos( $output, '</ul><!-- .row.flex-xl-nowrap.list-unstyled -->', $start );
			}
        }

		switch ( $columns ) {
			case 1:
				$output = str_replace( ' class="wc-block-product ',  ' class="wc-block-product col-12 ', $output );
				break;

			case 2:
				$output = str_replace( ' class="wc-block-product ',  ' class="wc-block-product col-sm-6 ', $output );
				break;

			case 3:
				$output = str_replace( ' class="wc-block-product ',  ' class="wc-block-product col-sm-6 col-lg-4 ', $output );
				break;

			case 4:
				$output = str_replace( ' class="wc-block-product ',  ' class="wc-block-product col-sm-6 col-lg-3 ', $output );
				break;

			case 5:
				$output = str_replace( ' class="wc-block-product ',  ' class="wc-block-product col-sm-6 col-md-4 col-lg-3 col-xl flex-sm-grow-1 flex-sm-shrink-0 ', $output );
				break;

			case 6:
				$output = str_replace( ' class="wc-block-product ',  ' class="wc-block-product col-sm-6 col-lg-4 col-xl-2 ', $output );
				break;
		}
	}

	return $output;
}
add_filter( 'enlightenment_render_block_woocommerce_product_collection', 'enlightenment_woocommerce_bootstrap_product_collection_block', 10, 2 );

function enlightenment_woocommerce_bootstrap_featured_category_block( $output, $block ) {
	if ( isset( $block['attrs'] ) && isset( $block['attrs']['align'] ) && 'full' == $block['attrs']['align'] ) {
		$offset = strpos( $output, 'class="wc-block-featured-category ' );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, '<div class="container" style="z-index:1;">', $offset + 1, 0 );
		$output .= '</div>';
	}

	if (
		isset( $block['innerBlocks'] ) &&
		isset( $block['innerBlocks'][0] ) &&
		isset( $block['innerBlocks'][0]['blockName'] ) &&
		(
			'core/button' == $block['innerBlocks'][0]['blockName'] &&
			isset( $block['innerBlocks'][0]['attrs'] ) &&
			isset( $block['innerBlocks'][0]['attrs']['className'] ) &&
			false !== strpos( $block['innerBlocks'][0]['attrs']['className'], 'is-style-outline' )
		) || (
			'core/buttons' == $block['innerBlocks'][0]['blockName'] &&
			isset( $block['innerBlocks'][0]['innerBlocks'] ) &&
			isset( $block['innerBlocks'][0]['innerBlocks'][0] ) &&
			isset( $block['innerBlocks'][0]['innerBlocks'][0]['blockName'] ) &&
			'core/button' == $block['innerBlocks'][0]['innerBlocks'][0]['blockName'] &&
			isset( $block['innerBlocks'][0]['innerBlocks'][0]['attrs'] ) &&
			isset( $block['innerBlocks'][0]['innerBlocks'][0]['attrs']['className'] ) &&
			false !== strpos( $block['innerBlocks'][0]['innerBlocks'][0]['attrs']['className'], 'is-style-outline' )
		)
	) {
		$offset   = strpos( $output, ' is-style-outline' );
		$offset_a = strpos( $output, ' btn-outline-secondary"', $offset );
		$end      = strpos( $output, '"',                  $offset );

		if ( false === $offset_a || $offset_a > $end ) {
			$offset_a = strpos( $output, ' btn-outline-secondary ', $offset );
		}

		if ( false !== $offset_a && $offset_a < $end ) {
			$output = substr_replace( $output, 'btn-outline-light', $offset_a + 1, 21 );
		}

		if ( false === $offset_a || $offset_a > $end ) {
			$output = str_replace( ' btn-outline-secondary ', ' btn-outline-light ', $output );
			$output = str_replace( ' btn-outline-secondary"', ' btn-outline-light"', $output );
		} else {
			$offset = strpos( $output, 'class="btn ', $offset );

			if ( false !== $offset ) {
				$offset_b = strpos( $output, ' btn-outline-secondary"', $offset );
				$end      = strpos( $output, '"',                  $offset );
				$end      = strpos( $output, '"',                  $end + 1 );

				if ( false === $offset_b || $offset_a > $end ) {
					$offset_b = strpos( $output, ' btn-outline-secondary ', $offset );
				}

				if ( false !== $offset_b && $offset_b < $end ) {
					$output = substr_replace( $output, 'btn-outline-light', $offset_b + 1, 21 );
				}
			}
		}
	} else {
		$offset = strpos( $output, 'class="wp-block-button ' );

		if ( false === $offset ) {
			$output = str_replace( ' btn-secondary ', ' btn-light ', $output );
			$output = str_replace( ' btn-secondary"', ' btn-light"', $output );
		} else {
			$offset_a = strpos( $output, ' btn-secondary"', $offset );
			$end      = strpos( $output, '"',          $offset );
			$end      = strpos( $output, '"',          $end + 1 );

			if ( false === $offset_a || $offset_a > $end ) {
				$offset_a = strpos( $output, ' btn-secondary ', $offset );
			}

			if ( false !== $offset_a && $offset_a < $end ) {
				$output = substr_replace( $output, 'btn-light', $offset_a + 1, 13 );
			}

			$offset = strpos( $output, 'class="btn ', $offset );

			if ( false !== $offset ) {
				$offset_b = strpos( $output, ' btn-secondary"', $offset );
				$end      = strpos( $output, '"',          $offset );
				$end      = strpos( $output, '"',          $end + 1 );

				if ( false === $offset_b || $offset_a > $end ) {
					$offset_b = strpos( $output, ' btn-secondary ', $offset );
				}

				if ( false !== $offset_b && $offset_b < $end ) {
					$output = substr_replace( $output, 'btn-light', $offset_b + 1, 13 );
				}
			}
		}
	}

	return $output;
}
add_filter( 'enlightenment_render_block_woocommerce_featured_category', 'enlightenment_woocommerce_bootstrap_featured_category_block', 10, 2 );

function enlightenment_woocommerce_bootstrap_product_categories_block( $output, $block ) {
	if ( isset( $block['attrs'] ) && isset( $block['attrs']['isDropdown'] ) && true === $block['attrs']['isDropdown'] ) {
		$output = str_replace( 'class="wc-block-product-categories__button"', 'class="wc-block-product-categories__button btn btn-secondary"', $output );
	} else {
		$output = str_replace( 'class="wc-block-product-categories-list ', 'class="wc-block-product-categories-list list-unstyled ', $output );

		$start  = strpos( $output, 'wc-block-product-categories-list--depth-1' );
	    $offset = $start;

	    while ( false !== $offset ) {
	        $offset = strpos( $output, '"', $offset );

	        if ( false !== $offset ) {
	            $output = substr_replace( $output, ' ps-4', $offset, 0 );
	        }

	        $offset = strpos( $output, 'wc-block-product-categories-list--depth-', $offset );
	    }
	}

    $output  = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_woocommerce_product_categories', 'enlightenment_woocommerce_bootstrap_product_categories_block', 10, 2 );

function enlightenment_woocommerce_bootstrap_featured_product_block( $output, $block ) {
	if ( isset( $block['attrs'] ) && isset( $block['attrs']['align'] ) && 'full' == $block['attrs']['align'] ) {
		$offset = strpos( $output, 'class="wc-block-featured-product ' );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, '<div class="container" style="z-index:1;">', $offset + 1, 0 );
		$output .= '</div>';
	}

	if (
		isset( $block['innerBlocks'] ) &&
		isset( $block['innerBlocks'][0] ) &&
		isset( $block['innerBlocks'][0]['blockName'] ) &&
		(
			'core/button' == $block['innerBlocks'][0]['blockName'] &&
			isset( $block['innerBlocks'][0]['attrs'] ) &&
			isset( $block['innerBlocks'][0]['attrs']['className'] ) &&
			false !== strpos( $block['innerBlocks'][0]['attrs']['className'], 'is-style-outline' )
		) || (
			'core/buttons' == $block['innerBlocks'][0]['blockName'] &&
			isset( $block['innerBlocks'][0]['innerBlocks'] ) &&
			isset( $block['innerBlocks'][0]['innerBlocks'][0] ) &&
			isset( $block['innerBlocks'][0]['innerBlocks'][0]['blockName'] ) &&
			'core/button' == $block['innerBlocks'][0]['innerBlocks'][0]['blockName'] &&
			isset( $block['innerBlocks'][0]['innerBlocks'][0]['attrs'] ) &&
			isset( $block['innerBlocks'][0]['innerBlocks'][0]['attrs']['className'] ) &&
			false !== strpos( $block['innerBlocks'][0]['innerBlocks'][0]['attrs']['className'], 'is-style-outline' )
		)
	) {
		$offset   = strpos( $output, ' is-style-outline' );
		$offset_a = strpos( $output, ' btn-outline-secondary"', $offset );
		$end      = strpos( $output, '"',                  $offset );

		if ( false === $offset_a || $offset_a > $end ) {
			$offset_a = strpos( $output, ' btn-outline-secondary ', $offset );
		}

		if ( false !== $offset_a && $offset_a < $end ) {
			$output = substr_replace( $output, 'btn-outline-light', $offset_a + 1, 21 );
		}

		if ( false === $offset_a || $offset_a > $end ) {
			$output = str_replace( ' btn-outline-secondary ', ' btn-outline-light ', $output );
			$output = str_replace( ' btn-outline-secondary"', ' btn-outline-light"', $output );
		} else {
			$offset = strpos( $output, 'class="btn ', $offset );

			if ( false !== $offset ) {
				$offset_b = strpos( $output, ' btn-outline-secondary"', $offset );
				$end      = strpos( $output, '"',                  $offset );
				$end      = strpos( $output, '"',                  $end + 1 );

				if ( false === $offset_b || $offset_a > $end ) {
					$offset_b = strpos( $output, ' btn-outline-secondary ', $offset );
				}

				if ( false !== $offset_b && $offset_b < $end ) {
					$output = substr_replace( $output, 'btn-outline-light', $offset_b + 1, 21 );
				}
			}
		}
	} else {
		$offset   = strpos( $output, 'class="wp-block-button ' );

		if ( false === $offset ) {
			$output = str_replace( ' btn-secondary ', ' btn-light ', $output );
			$output = str_replace( ' btn-secondary"', ' btn-light"', $output );
		} else {
			$offset_a = strpos( $output, ' btn-secondary"', $offset );
			$end      = strpos( $output, '"',          $offset );
			$end      = strpos( $output, '"',          $end + 1 );

			if ( false === $offset_a || $offset_a > $end ) {
				$offset_a = strpos( $output, ' btn-secondary ', $offset );
			}

			if ( false !== $offset_a && $offset_a < $end ) {
				$output = substr_replace( $output, 'btn-secondary', $offset_a + 1, 13 );
			}

			$offset = strpos( $output, 'class="btn ', $offset );

			if ( false !== $offset ) {
				$offset_b = strpos( $output, ' btn-secondary"', $offset );
				$end      = strpos( $output, '"',          $offset );
				$end      = strpos( $output, '"',          $end + 1 );

				if ( false === $offset_b || $offset_a > $end ) {
					$offset_b = strpos( $output, ' btn-secondary ', $offset );
				}

				if ( false !== $offset_b && $offset_b < $end ) {
					$output = substr_replace( $output, 'btn-light', $offset_b + 1, 13 );
				}
			}
		}
	}

	return $output;
}
add_filter( 'enlightenment_render_block_woocommerce_featured_product', 'enlightenment_woocommerce_bootstrap_featured_product_block', 10, 2 );

function enlightenment_woocommerce_bootstrap_product_search_block( $output ) {
	if ( false !== strpos( $output, '<button ' ) ) {
		$offset = strpos( $output, 'class="wc-block-product-search__field"' );
		$offset = strrpos( $output, '<input ', $offset - strlen( $output ) );
		$output = substr_replace( $output, '<div class="input-group">', $offset, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, '</div>', $offset + 9, 0 );
    }

    $output = str_replace( 'class="wc-block-product-search__field"',  'class="form-control"', $output );
    $output = str_replace( 'class="wc-block-product-search__button"', 'class="btn btn-light"', $output );

    return $output;
}
add_filter( 'enlightenment_render_block_woocommerce_product_search', 'enlightenment_woocommerce_bootstrap_product_search_block' );

function enlightenment_woocommerce_bootstrap_product_query_block( $output, $block ) {
	if ( empty( $block['attrs'] ) ) {
		return $output;
	}

	if ( ! isset( $block['attrs']['query'] ) ) {
		return $output;
	}

	if ( ! isset( $block['attrs']['query']['postType'] ) ) {
		return $output;
	}

	if ( 'product' != $block['attrs']['query']['postType'] ) {
		return $output;
	}

	if ( ! isset( $block['attrs']['displayLayout'] ) ) {
		return $output;
	}

	if ( ! isset( $block['attrs']['displayLayout']['type'] ) ) {
		return $output;
	}

	if ( 'flex' != $block['attrs']['displayLayout']['type'] ) {
		return $output;
	}

	if ( ! isset( $block['attrs']['displayLayout']['columns'] ) ) {
		return $output;
	}

	if ( 4 != $block['attrs']['displayLayout']['columns'] ) {
		return $output;
	}

	return str_replace( 'class="wp-block-post col-sm-6 col-lg-3 ',  'class="wp-block-post col-sm-6 col-md-4 col-lg-3 ', $output );
}
add_filter( 'enlightenment_render_block_core_query', 'enlightenment_woocommerce_bootstrap_product_query_block', 12, 2 );

function enlightenment_woocommerce_bootstrap_product_rating_block( $output ) {
	return str_replace( '/#reviews"', '/#tab-reviews"', $output );
}
add_filter( 'enlightenment_render_block_woocommerce_product_rating', 'enlightenment_woocommerce_bootstrap_product_rating_block' );

function enlightenment_woocommerce_bootstrap_product_button_block( $output ) {
	$output = str_replace( 'class="wp-block-button__link ',  'class="btn btn-secondary ', $output );
	$output = str_replace( 'class="added_to_cart ',  'class="added_to_cart btn btn-secondary ', $output );
	$output = str_replace( ' wc-block-components-product-button__button ',  ' ', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_woocommerce_product_button', 'enlightenment_woocommerce_bootstrap_product_button_block' );

function enlightenment_woocommerce_bootstrap_currency_switcher_block_type_args( $args ) {
	$args['attributes']['fontSize']['default']         = 16;
	$args['attributes']['fontLineHeight']['default']   = 'var(--bs-body-line-height)';
	$args['attributes']['fontColor']['default']        = 'var(--bs-body-color)';
	$args['attributes']['borderRadius']['default']     = 6;
	$args['attributes']['borderColor']['default']      = 'var(--bs-border-color)';
	$args['attributes']['backgroundColor']['default']  = 'var(--bs-body-bg)';

	return $args;
}
add_filter( 'enlightenment_register_block_type_args_woocommerce-payments/multi-currency-switcher', 'enlightenment_woocommerce_bootstrap_currency_switcher_block_type_args', 10, 2 );

function enlightenment_woocommerce_bootstrap_currency_switcher_block( $output ) {
	$start = strpos( $output, '<select' );
	if ( false !== $start ) {
		$end = strpos( $output, '>', $start );
		$offset = strpos( $output, 'class="', $start );
		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, 'form-select ', $offset + 7, 0 );
		} else {
			$output = substr_replace( $output, ' class="form-select"', $start + 7, 0 );
		}

		$end = strpos( $output, '>', $start );
		$offset = strpos( $output, 'style="', $start );
		if ( false !== $offset && $offset < $end ) {
			$end    = strpos( $output, '"', $offset + 7 );
			$styles = substr( $output, $offset + 7, $end - $offset - 7 );

			$start_a = strpos( $output, 'class="currency-switcher-holder"' );
			if ( false !== $start_a ) {
				$start_a  = strrpos( $output, '<div ', $offset - strlen( $output ) );
				$end_a    = strpos( $output, '>', $start_a );
				$offset_a = strpos( $output, 'style="', $start_a );

				if ( false !== $offset_a && $offset_a < $end_a ) {
					$end_a    = strpos( $output, '"', $offset_a + 7 );
					$styles_a = substr( $output, $offset_a + 7, $end_a - $offset_a - 7 );

					if ( ! empty( $styles_a ) ) {
						$styles .= $styles_a;
					}
				}
			}

			$styles = str_replace( 'padding: 2px; ', '', $styles );
			$styles = str_replace( 'border: 1px solid; ', 'border: var(--bs-border-width) solid; ', $styles );
			$styles = str_replace( array( ': ', '; ' ), array( ':', ';' ), $styles );

			$output = substr_replace( $output, '', $offset, $end + 1 - $offset );
			$output = substr_replace( $output, sprintf( '<style>.wp-block-currency-switcher select{%s}</style>', $styles ), $start, 0 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_render_block_woocommerce_payments_multi_currency_switcher', 'enlightenment_woocommerce_bootstrap_currency_switcher_block' );

function enlightenment_woocommerce_bootstrap_remove_filter_grid_loop_memberships_restricted_message_html () {
	remove_filter( 'wc_memberships_restricted_message_html', 'enlightenment_woocommerce_grid_loop_memberships_restricted_message_html' );
}
add_action( 'init', 'enlightenment_woocommerce_bootstrap_remove_filter_grid_loop_memberships_restricted_message_html' );

function enlightenment_woocommerce_bootstrap_grid_loop_memberships_restricted_message_html( $output ) {
    if ( doing_action( 'loop_start' ) ) {
		$grids = enlightenment_current_grid();

		foreach ( $grids as $grid ) {
			if ( 'inherit' == $grid ) {
				continue;
			}

			$atts = enlightenment_get_grid( $grid );

			if ( 1 < $atts['content_columns'] ) {
	            $output = str_replace( 'class="woocommerce"', 'class="woocommerce col-12"', $output );
				break;
	        }
		}
    }

    return $output;
}
add_filter( 'wc_memberships_restricted_message_html', 'enlightenment_woocommerce_bootstrap_grid_loop_memberships_restricted_message_html' );
