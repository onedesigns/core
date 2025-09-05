<?php

function enlightenment_menu_descriptions_enqueue_block_editor_assets() {
	if ( ! is_admin() ) {
		return;
	}

	wp_enqueue_style( 'enlightenment-menu-descriptions', enlightenment_styles_directory_uri() . '/menu-descriptions.css', array(), null );
}
add_action( 'enqueue_block_assets', 'enlightenment_menu_descriptions_enqueue_block_editor_assets' );
