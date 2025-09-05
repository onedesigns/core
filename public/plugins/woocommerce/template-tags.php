<?php

function enlightenment_woocommerce_user_account( $args = null ) {
	$defaults = array(
		'container'                => 'nav',
		'container_class'          => 'user-account dropdown',
		'container_id'             => '',
		'toggle_tag'               => 'button',
		'toggle_class'             => 'user-account-toggle',
		'toggle_id'                => 'user-account-toggle',
		'toggle_extra_atts'        => array(
			'aria-haspopup' => 'true',
			'aria-expanded' => 'false',
		),
		'toggle_label'             => __( 'My Account', 'enlightenment' ),
		'dropdown_menu_tag'        => 'div',
		'dropdown_menu_class'      => 'user-account-menu',
		'dropdown_menu_id'         => 'user-account-menu',
		'dropdown_menu_extra_atts' => array(
			'aria-labelledby' => 'user-account-toggle',
		),
		'message'                  => '',
		'redirect'                 => '',
		'hidden'                   => false,
		'echo'                     => true,
	);

	$defaults = apply_filters( 'enlightenment_woocommerce_user_account_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	$output .= enlightenment_open_tag( $args['toggle_tag'], $args['toggle_class'], $args['toggle_id'], $args['toggle_extra_atts'] );
	$output .= esc_html( $args['toggle_label'] );
	$output .= enlightenment_close_tag( $args['toggle_tag'] );

	$output .= enlightenment_open_tag( $args['dropdown_menu_tag'], $args['dropdown_menu_class'], $args['dropdown_menu_id'], $args['dropdown_menu_extra_atts'] );

	if ( is_user_logged_in() ) {
		ob_start();
		woocommerce_account_navigation();
		$nav = ob_get_clean();

		$start = strpos( $nav, '<nav class="woocommerce-MyAccount-navigation"' );
		if ( false !== $start ) {
			$end    = strpos( $nav, '>', $start ) + 1;
			$length = $end - $start;
		    $nav    = substr_replace( $nav, '', $start, $length );
			$nav    = str_replace( '</nav>', '', $nav );
		}

		$nav = apply_filters( 'enlightenment_woocommerce_user_account_nav', $nav );

		$output .= $nav;
	} else {
		ob_start();
		woocommerce_login_form( array(
			'message'  => $args['message'],
			'redirect' => $args['redirect'],
			'hidden'   => $args['hidden'],
		) );
		$form = ob_get_clean();
		$form = str_replace( '"rememberme"', '"remember-me"', $form );

		$form = apply_filters( 'enlightenment_woocommerce_user_account_login_form', $form );

		$output .= $form;
	}

	$output .= enlightenment_close_tag( $args['dropdown_menu_tag'] );

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_woocommerce_user_account', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

/**
 * Display a Shopping Cart Dropdown.
 *
 * @since 2.0.0
 */
function enlightenment_shopping_cart( $args = null ) {
	$defaults = array(
		'container'                => 'nav',
		'container_class'          => 'shopping-cart',
		'container_id'             => '',
		'link_class'               => 'shopping-cart-link',
		'link_id'                  => 'shopping-cart-link',
		'link_extra_atts'          => array(
			'href' =>  wc_get_cart_url(),
		),
		'link_label'               => __( 'Cart', 'enlightenment' ),
		'count_tag'                => 'span',
		'count_class'              => 'cart-contents-count',
		'count_empty_class'        => 'empty',
		'cart_contents_tag'        => 'div',
		'cart_contents_class'      => 'cart-contents',
		'cart_contents_id'         => 'cart-contents',
		'cart_contents_extra_atts' => array(),
		'echo'                     => true,
	);

	$defaults = apply_filters( 'enlightenment_shopping_cart_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$dropdown = ! apply_filters( 'woocommerce_widget_cart_is_hidden', is_cart() || is_checkout() );

	global $woocommerce;

	if ( empty( $woocommerce ) || ! $woocommerce instanceof WooCommerce ) {
		return;
	}

	if ( empty( $woocommerce->cart ) || ! $woocommerce->cart instanceof WC_Cart ) {
		return;
	}

	$count = $woocommerce->cart->cart_contents_count;

	$args['count_class'] .= empty( $count ) ? ' ' . $args['count_empty_class'] : '';

	if ( $dropdown ) {
		$args['link_extra_atts'] = array_merge( $args['link_extra_atts'], array(
			'aria-haspopup' => 'true',
			'aria-expanded' => 'false',
		) );
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	$output .= enlightenment_open_tag( 'a', $args['link_class'], $args['link_id'], $args['link_extra_atts'] );
	$output .= esc_html( $args['link_label'] );

	$output .= enlightenment_open_tag( $args['count_tag'], $args['count_class'] );
	$output .= sprintf( '<span aria-hidden="true">%d</span> ', $count );
	$output .= sprintf( '<span class="screen-reader-text">%s</span>', sprintf( _n(
		'%d item in your cart.',
		'%d items in your cart.',
		$count,
		'enlightenment'
	), $count ) );
	$output .= enlightenment_close_tag( $args['count_tag'] );

	$output .= enlightenment_close_tag( 'a' );

	if ( $dropdown ) {
		if ( ! empty( $args['link_id'] ) ) {
			$args['cart_contents_extra_atts'] = array_merge( $args['cart_contents_extra_atts'], array(
				'aria-labelledby' => $args['link_id'],
			) );
		}

		$output .= enlightenment_open_tag( $args['cart_contents_tag'], $args['cart_contents_class'], $args['cart_contents_id'], $args['cart_contents_extra_atts'] );

		ob_start();
		the_widget( 'WC_Widget_Cart', 'title=' );
		$output .= ob_get_clean();

		$output .= enlightenment_close_tag( $args['cart_contents_tag'] );
	}

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_shopping_cart', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

/**
 * Display a Currency Switcher.
 *
 * @since 2.0.0
 */
function enlightenment_currency_switcher( $args = null ) {
	$defaults = array(
		'container'         => 'div',
		'container_class'   => 'currency-switcher',
		'container_id'      => '',
		'provider'          => 'woopayments',
		'provider_settings' => array(
			'woopayments' => array(),
		),
		'echo'              => true,
	);

	$defaults = apply_filters( 'enlightenment_currency_switcher_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	switch ( $args['provider'] ) {
		case 'woopayments':
			$provider_args = array_merge( array(
				'container'       => $args['container'],
				'container_class' => sprintf( '%s woopayments-currency-switcher', $args['container_class'] ),
				'container_id'    => $args['container_id'],
				'echo'            => false,
			), $args['provider_settings']['woopayments'] );

			$output = enlightenment_woopayments_currency_switcher( $provider_args );

			break;
	}

	$output = apply_filters( 'enlightenment_currency_switcher', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

/**
 * Display the WooPayments Currency Switcher.
 *
 * @since 2.0.0
 */
function enlightenment_woopayments_currency_switcher( $args = null ) {
	if ( ! function_exists( 'WC_Payments_Multi_Currency' ) ) {
		return false;
	}

	$defaults = array(
		'container'                    => 'div',
		'container_class'              => 'currency-switcher woopayments-currency-switcher',
		'container_id'                 => '',
		'toggle_tag'                   => 'button',
		'toggle_class'                 => 'currency-switcher-toggle',
		'toggle_id'                    => 'currency-switcher-toggle',
		'toggle_extra_atts'            => array(
			'aria-label'    => __( 'Switch currency', 'enlightenment' ),
			'aria-expanded' => 'false',
		),
		'toggle_label_format'          => '%2$s %3$s',
		'dropdown_menu_tag'            => 'div',
		'dropdown_menu_class'          => 'currency-switcher-menu',
		'dropdown_menu_id'             => 'currency-switcher-menu',
		'dropdown_menu_extra_atts'     => array(
			'aria-labelledby' => 'currency-switcher-toggle',
		),
		'dropdown_item_tag'            => 'li',
		'dropdown_item_class'          => 'currency-switcher-option',
		'dropdown_item_active_class'   => 'currency-switcher-option-active',
		'dropdown_button_tag'          => 'a',
		'dropdown_button_class'        => '',
		'dropdown_button_active_class' => '',
		'dropdown_button_extra_atts'   => array(
			'href' => '%s',
		),
		'dropdown_button_label_format' => '%2$s %3$s',
		'echo'                         => true,
	);

	$defaults = apply_filters( 'enlightenment_woopayments_currency_switcher_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output = '';

	$enabled_currencies = WC_Payments_Multi_Currency()->get_enabled_currencies();

	if ( 1 !== count( $enabled_currencies ) ) {
		$selected_currency = WC_Payments_Multi_Currency()->get_selected_currency();

		$code   = $selected_currency->get_code();
		$symbol = $selected_currency->get_symbol();
		$flag   = $selected_currency->get_flag();
		$name   = $selected_currency->get_name();

		$output .= enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], );
		$output .= enlightenment_open_tag( $args['toggle_tag'], $args['toggle_class'], $args['toggle_id'], $args['toggle_extra_atts'] );

		if (
			html_entity_decode( $symbol ) === $code
			&&
			false !== strpos( $args['toggle_label_format'], '%2$s' )
			&&
			false !== strpos( $args['toggle_label_format'], '%3$s' )
		) {
			$args['toggle_label_format'] = str_replace( '%2$s', '', $args['toggle_label_format'] );
		}

		$output .= sprintf(
			$args['toggle_label_format'],
			sprintf( '<span class="currency-flag" aria-hidden="true" role="presentation">%s</span>',   $flag   ),
			sprintf( '<span class="currency-symbol" aria-hidden="true" role="presentation">%s</span>', $symbol ),
			sprintf( '<span class="currency-code" aria-hidden="true" role="presentation">%s</span>',   $code   ),
			sprintf( '<span class="currency-name">%s</span>',                                          $name   )
		);

		$output .= enlightenment_close_tag( $args['toggle_tag'] );
		$output .= enlightenment_open_tag(
			$args['dropdown_menu_tag'],
			$args['dropdown_menu_class'],
			$args['dropdown_menu_id'],
			$args['dropdown_menu_extra_atts']
		);

		foreach ( $enabled_currencies as $currency ) {
			$code   = $currency->get_code();
			$symbol = $currency->get_symbol();
			$flag   = $currency->get_flag();
			$name   = $currency->get_name();

			$item_class = $args['dropdown_item_class'];

			if (
				$selected_currency->get_code() === $code
				&&
				! empty( $args['dropdown_item_active_class'] )
			) {
				$item_class .= ' ' . $args['dropdown_item_active_class'];
			}

			$output .= enlightenment_open_tag( $args['dropdown_item_tag'], $item_class );

			$button_class = $args['dropdown_button_class'];

			if (
				$selected_currency->get_code() === $code
				&&
				! empty( $args['dropdown_button_active_class'] )
			) {
				$button_class .= ' ' . $args['dropdown_button_active_class'];
			}

			$currency_url = esc_url( enlightenment_get_current_uri( array(
				'params' => array(
					'currency' => $code,
				),
			) ) );

			$extra_atts = $args['dropdown_button_extra_atts'];

			foreach ( $extra_atts as $key => $extra_attr ) {
				$extra_atts[ $key ] = sprintf( $extra_attr, $currency_url );
			}

			if ( $selected_currency->get_code() === $code ) {
				$extra_atts['aria-selected'] = 'true';
			}

			$output .= enlightenment_open_tag(
				$args['dropdown_button_tag'],
				$button_class,
				'',
				$extra_atts
			);

			$dropdown_button_label_format = $args['dropdown_button_label_format'];

			if (
				html_entity_decode( $symbol ) === $code
				&&
				false !== strpos( $dropdown_button_label_format, '%2$s' )
				&&
				false !== strpos( $dropdown_button_label_format, '%3$s' )
			) {
				$dropdown_button_label_format = str_replace( '%2$s', '', $dropdown_button_label_format );
			}

			$output .= sprintf(
				$dropdown_button_label_format,
				sprintf( '<span class="currency-flag" aria-hidden="true" role="presentation">%s</span>',   $flag   ),
				sprintf( '<span class="currency-symbol" aria-hidden="true" role="presentation">%s</span>', $symbol ),
				sprintf( '<span class="currency-code" aria-hidden="true" role="presentation">%s</span>',   $code   ),
				sprintf( '<span class="currency-name">%s</span>',                                          $name   )
			);

			$output .= enlightenment_close_tag( $args['dropdown_button_tag'] );
			$output .= enlightenment_close_tag( $args['dropdown_item_tag'] );
		}

		$output .= enlightenment_close_tag( $args['dropdown_menu_tag'] );
		$output .= enlightenment_close_tag( $args['container'] );
	}

	$output = apply_filters( 'enlightenment_woopayments_currency_switcher', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_product_categories( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'categories-list',
		'container_id'    => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_product_categories_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$categories = woocommerce_maybe_show_product_subcategories();

	if ( empty( $categories ) ) {
		return;
	}

	$categories = apply_filters( 'enlightenment_product_categories_html', $categories, $args );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= $categories;
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_product_categories', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_woocommerce_product_description_tab( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'product-description',
		'container_id'    => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_woocommerce_product_description_tab_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	ob_start();
	woocommerce_product_description_tab();
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_woocommerce_product_description_tab', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_woocommerce_product_additional_information_tab( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'product-additional-information',
		'container_id'    => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_woocommerce_product_additional_information_tab_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );

	ob_start();
	woocommerce_product_additional_information_tab();
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_woocommerce_product_additional_information_tab', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_woocommerce_verification_required_notice( $args = null ) {
	$defaults = array(
		'container'       => 'p',
		'container_class' => 'woocommerce-verification-required',
		'container_id'    => '',
		'text'            => esc_html( 'Only logged in customers who have purchased this product may leave a review.', 'enlightenment' ),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_woocommerce_verification_required_notice_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= $args['text'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_woocommerce_verification_required_notice', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_woocommerce_upsell_display( $args = null ) {
	$defaults = array(
		'limit'   => -1,
		'columns' => 4,
		'orderby' => 'rand',
		'order'   => 'desc',
		'echo'    => true,
	);
	$defaults = apply_filters( 'enlightenment_woocommerce_upsell_display_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	woocommerce_upsell_display(
		$args['limit'],
		$args['columns'],
		$args['orderby'],
		$args['order']
	);
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_woocommerce_upsell_display', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_woocommerce_pb_template_add_to_cart() {
	if ( ! function_exists( 'wc_pb_template_add_to_cart' ) ) {
		return;
	}

	global $product;

	if ( ! $product instanceof WC_Product_Bundle ) {
		return;
	}

	if (
		doing_action( 'enlightenment_before_entry_content' )
		&&
		'after_summary' === $product->get_add_to_cart_form_location()
	) {
		return;
	}

	wc_pb_template_add_to_cart();
}

function enlightenment_woocommerce_wcss_products_first_payment_date( $args ) {
	if ( ! class_exists( 'WC_Subscriptions_Synchroniser' ) ) {
		return;
	}

	$defaults = array(
		'container'       => 'p',
		'container_class' => 'first-payment-date',
		'wrapper'         => 'small',
		'wrapper_class'   => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_woocommerce_wcss_products_first_payment_date_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$date = WC_Subscriptions_Synchroniser::products_first_payment_date();

	if ( empty( $date ) ) {
		return;
	}

	$date = str_replace( '<p class="first-payment-date"><small>', '', $date );
	$date = str_replace( '</small></p>',                          '', $date );

	$args['container']       = trim( esc_attr( $args['container'] ) );
	$args['container_class'] = trim( esc_attr( $args['container_class'] ) );
	$args['wrapper']         = trim( esc_attr( $args['wrapper'] ) );
	$args['wrapper_class']   = trim( esc_attr( $args['wrapper_class'] ) );

	$output = sprintf(
		'<%1$s%2$s><%3$s%4$s>%5$s</%3$s></%1$s>',
		$args['container'],
		$args['container_class'] ? sprintf( ' class="%s"',$args['container_class'] ) : '',
		$args['wrapper'],
		$args['wrapper_class']   ? sprintf( ' class="%s"',$args['wrapper_class'] )   : '',
		$date
	);

	$output = apply_filters( 'enlightenment_woocommerce_wcss_products_first_payment_date', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
add_action( 'woocommerce_after_add_to_cart_form', 'enlightenment_woocommerce_wcss_products_first_payment_date', 999 );
