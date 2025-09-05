<?php
/**
 * Customizer functions for WooCommerce.
 *
 * @package Enlightenment_Framework
 */

function enlightenment_woocommerce_customize_register( $wp_customize ) {
    $defaults = enlightenment_default_theme_mods();

    $wp_customize->add_setting(
        'grid_loop_shop_per_page',
        array(
            'default'              => $defaults['grid_loop_shop_per_page'],
            'type'                 => 'theme_mod',
            'capability'           => 'manage_woocommerce',
            'theme_supports'       => 'enlightenment-grid-loop',
            'sanitize_callback'    => 'absint',
            'sanitize_js_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'grid_loop_shop_per_page',
        array(
            'label'       => __( 'Products per page', 'enlightenment' ),
            'description' => __( 'How many products should be shown per page?', 'enlightenment' ),
            'section'     => 'woocommerce_product_catalog',
            'settings'    => 'grid_loop_shop_per_page',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => '',
                'step' => 1,
            ),
        )
    );
}
add_action( 'customize_register', 'enlightenment_woocommerce_customize_register' );
