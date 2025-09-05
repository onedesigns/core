<?php

function enlightenment_shop_header_layout_hooks() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
}
add_action( 'send_headers', 'enlightenment_shop_header_layout_hooks', 8 );

function enlightenment_shop_layout_hooks() {
	if ( ! is_shop() && ! is_product_taxonomy() && ! is_product() ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );

	add_action( 'enlightenment_before_page_content', 'enlightenment_woocommerce_before_main_content', 9 );
	add_action( 'enlightenment_before_page_content', 'enlightenment_breadcrumbs' );

	add_action( 'enlightenment_after_page_content', 'enlightenment_woocommerce_after_main_content', 9 );

	if ( is_shop() || is_product_taxonomy() ) {
		remove_action( 'enlightenment_no_entries', 'enlightenment_no_posts_found' );

		remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description' );
		remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description' );

		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices' );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination' );

		remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );

		add_action( 'enlightenment_page_header', 'enlightenment_woocommerce_archive_description', 9 );

		add_action( 'enlightenment_before_entries_list', 'enlightenment_woocommerce_before_shop_loop', 9 );

		add_action( 'enlightenment_before_entries_list', 'enlightenment_product_categories' );
		add_action( 'enlightenment_before_entries_list', 'woocommerce_output_all_notices' );
		add_action( 'enlightenment_before_entries_list', 'woocommerce_result_count' );
		add_action( 'enlightenment_before_entries_list', 'woocommerce_catalog_ordering' );

		add_action( 'enlightenment_after_entries_list', 'enlightenment_woocommerce_after_shop_loop', 9 );

		add_action( 'enlightenment_no_entries', 'enlightenment_woocommerce_no_products_found', 9 );
		add_action( 'enlightenment_no_entries', 'wc_no_products_found' );
	} elseif( is_product() ) {
		remove_action( 'enlightenment_after_entry_footer', 'get_sidebar', 996 );
	}
}
add_action( 'wp', 'enlightenment_shop_layout_hooks' );

function enlightenment_woocommerce_pb_entry_hooks() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	remove_action( 'woocommerce_bundle_add_to_cart', 'wc_pb_template_add_to_cart' );

	add_action( 'woocommerce_bundle_add_to_cart', 'enlightenment_woocommerce_pb_template_add_to_cart' );
}
add_action( 'wp', 'enlightenment_woocommerce_pb_entry_hooks' );

function enlightenment_shop_entry_hooks() {
	if ( 'product' != get_post_type() ) {
		return;
	}

	if ( is_singular() ) {
		remove_action( 'woocommerce_before_single_product', 'wc_print_notices' );

		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating' );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price' );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs' );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_sharing', 50 );

		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );
		remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );

		remove_action( 'enlightenment_entry_content', 'enlightenment_post_content' );

		remove_action( 'enlightenment_after_entry_footer', 'enlightenment_post_nav' );
		remove_action( 'enlightenment_after_entry_footer', 'enlightenment_comments_template' );

		add_action( 'enlightenment_before_entry', 'enlightenment_woocommerce_before_single_product', 9 );
		add_action( 'enlightenment_before_entry', 'wc_print_notices' );

		add_action( 'enlightenment_after_entry_header', 'enlightenment_woocommerce_before_single_product_summary', 9 );
		add_action( 'enlightenment_after_entry_header', 'woocommerce_show_product_sale_flash' );
		add_action( 'enlightenment_after_entry_header', 'woocommerce_show_product_images' );

		add_action( 'enlightenment_before_entry_content', 'enlightenment_woocommerce_single_product_summary', 9 );
		add_action( 'enlightenment_before_entry_content', 'woocommerce_template_single_title' );
		add_action( 'enlightenment_before_entry_content', 'woocommerce_template_single_rating' );
		add_action( 'enlightenment_before_entry_content', 'woocommerce_template_single_price' );
		add_action( 'enlightenment_before_entry_content', 'woocommerce_template_single_excerpt' );
		add_action( 'enlightenment_before_entry_content', 'woocommerce_template_single_add_to_cart' );
		add_action( 'enlightenment_before_entry_content', 'woocommerce_template_single_meta' );
		add_action( 'enlightenment_before_entry_content', 'woocommerce_template_single_sharing' );

		add_action( 'enlightenment_entry_content', 'enlightenment_woocommerce_after_single_product_summary', 9 );
		add_action( 'enlightenment_entry_content', 'woocommerce_output_product_data_tabs' );
		add_action( 'enlightenment_entry_content', 'enlightenment_woocommerce_upsell_display' );
		add_action( 'enlightenment_entry_content', 'woocommerce_output_related_products' );

		add_action( 'enlightenment_after_entry', 'enlightenment_woocommerce_after_single_product', 9 );
	} else {
		enightenment_clear_entry_hooks();

		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open' );

		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );

		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title' );

		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price' );

		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );

		add_action( 'enlightenment_before_entry', 'enlightenment_woocommerce_shop_loop', 9 );

		add_action( 'enlightenment_before_entry_header', 'enlightenment_woocommerce_before_shop_loop_item', 9 );
		add_action( 'enlightenment_before_entry_header', 'enlightenment_woocommerce_before_shop_loop_item_title', 9 );
		add_action( 'enlightenment_before_entry_header', 'woocommerce_show_product_loop_sale_flash' );
		add_action( 'enlightenment_before_entry_header', 'enlightenment_post_thumbnail' );

		add_action( 'enlightenment_entry_header', 'enlightenment_woocommerce_shop_loop_item_title', 9 );
		add_action( 'enlightenment_entry_header', 'enlightenment_entry_title' );

		add_action( 'enlightenment_after_entry_header', 'enlightenment_woocommerce_after_shop_loop_item_title', 9 );
		add_action( 'enlightenment_after_entry_header', 'woocommerce_template_loop_rating' );
		add_action( 'enlightenment_after_entry_header', 'woocommerce_template_loop_price' );

		add_action( 'enlightenment_after_entry_footer', 'enlightenment_woocommerce_after_shop_loop_item', 9 );
		add_action( 'enlightenment_after_entry_footer', 'woocommerce_template_loop_add_to_cart' );
	}
}
add_action( 'enlightenment_before_entry', 'enlightenment_shop_entry_hooks', 7 );

function enlightenment_shop_plugins_entry_hooks() {
	if ( 'product' != get_post_type() ) {
		return;
	}

	if ( is_singular() ) {
		if ( class_exists( 'WC_Subscriptions_Synchroniser' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'WC_Subscriptions_Synchroniser::products_first_payment_date', 31 );
		}

		if ( class_exists( 'SkyVerge\WooCommerce\Memberships\Restrictions\Products' ) ) {
			$instance = wc_memberships()->get_restrictions_instance()->get_products_restrictions_instance();

			remove_action( 'woocommerce_single_product_summary', array( $instance, 'display_product_purchasing_discount_message' ), 30 );
			remove_action( 'woocommerce_single_product_summary', array( $instance, 'display_product_purchasing_restricted_message' ), 31 );

			foreach ( wc_get_product_types() as $handle => $name ) {
				$hook = sprintf( 'woocommerce_%s_add_to_cart', $handle );

				add_action( $hook, array( $instance, 'display_product_purchasing_discount_message' ), 30 );
				add_action( $hook, array( $instance, 'display_product_purchasing_restricted_message' ), 31 );
			}
		}
	}
}
add_action( 'enlightenment_before_entry', 'enlightenment_shop_plugins_entry_hooks', 7 );
