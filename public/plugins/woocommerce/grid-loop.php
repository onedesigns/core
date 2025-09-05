<?php

function enlightenment_woocommerce_loop_shop_columns( $columns ) {
    if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
        $grids = array_reverse( enlightenment_current_grid() );

    	foreach ( $grids as $breakpoint => $grid ) {
    		if( 'inherit' == $grid ) {
    			continue;
    		}

    		$atts = enlightenment_get_grid( $grid );
    		break;
    	}
    } else {
        $grid = enlightenment_current_grid();
        $atts = enlightenment_get_grid( $grid );
    }

    return absint( $atts['content_columns'] );
}
add_filter( 'loop_shop_columns', 'enlightenment_woocommerce_loop_shop_columns' );

function enlightenment_grid_loop_product_cat_class( $classes ) {
    if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
        return $classes;
    }

    $grid = enlightenment_current_grid();
    $atts = enlightenment_get_grid( $grid );

    $classes[] = $atts['entry_class'];

    return $classes;
}
add_filter( 'product_cat_class', 'enlightenment_grid_loop_product_cat_class' );

function enlightenment_woocommerce_grid_loop_default_theme_mods( $mods ) {
    return array_merge( $mods, array(
        'grid_loop_shop_per_page' => 12,
    ) );
}
add_filter( 'enlightenment_default_theme_mods', 'enlightenment_woocommerce_grid_loop_default_theme_mods' );

function enlightenment_woocommerce_loop_shop_per_page( $posts_per_page ) {
    $setting = get_theme_mod( 'grid_loop_shop_per_page' );

    if ( 0 < $setting ) {
        $posts_per_page = $setting;
    }

    return $posts_per_page;
}
add_filter( 'loop_shop_per_page', 'enlightenment_woocommerce_loop_shop_per_page' );

function enlightenment_woocommerce_grid_loop_memberships_restricted_message_html( $output ) {
    if ( doing_action( 'loop_start' ) ) {
        $grid = enlightenment_current_grid();
        $atts = enlightenment_get_grid( $grid );

        if ( 1 < $atts['content_columns'] ) {
            $output = str_replace( 'class="woocommerce"', 'class="woocommerce onecol"', $output );
        }
    }

    return $output;
}
add_filter( 'wc_memberships_restricted_message_html', 'enlightenment_woocommerce_grid_loop_memberships_restricted_message_html' );
