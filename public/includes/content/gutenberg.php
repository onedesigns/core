<?php

function enlightenment_register_gutenberg_styles() {
    wp_register_style(
        'enlightenment-editor-panels',
        enlightenment_styles_directory_uri() . '/editor-panels.css',
    );
}
add_action( 'enqueue_block_editor_assets', 'enlightenment_register_gutenberg_styles', 0 );

function enlightenment_block_editor_custom_css( $editor_settings ) {
	$custom_css = apply_filters( 'enlightenment_block_editor_custom_css', '' );

	if ( ! empty( $custom_css ) ) {
		$editor_settings['styles'][] = array(
			'css'            => $custom_css,
			'__unstableType' => 'theme',
		);
	}

	return $editor_settings;
}
add_filter( 'block_editor_settings_all', 'enlightenment_block_editor_custom_css' );

/**
 * Intercepts any request with legacy-widget-preview in the query param and, if
 * set, renders a page containing a preview of the requested Legacy Widget
 * block.
 */
function enlightenment_handle_legacy_widget_preview_iframe() {
	if ( empty( $_GET['legacy-widget-preview'] ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	global $wp_widget_factory, $wp_registered_widgets;

	$attributes = $_GET['legacy-widget-preview'];

	if ( isset( $attributes['idBase'] ) ) {
		$id_base = $attributes['idBase'];

		if ( method_exists( $wp_widget_factory, 'get_widget_object' ) ) {
			$widget_object = $wp_widget_factory->get_widget_object( $id_base );
		} else {
			/*
			 * This file is copied from the published @wordpress/widgets package when WordPress
			 * Core is built. Because the package is a dependency of both WordPress Core and the
			 * Gutenberg plugin where the block editor is developed, this fallback condition is
			 * required until the minimum required version of WordPress for the plugin is raised
			 * to 5.8.
			 */
			$widget_object = gutenberg_get_widget_object( $id_base );
		}

		$widget_object_id = $widget_object->id;
	}

	define( 'IFRAME_REQUEST', true );

	?>
	<!doctype html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="profile" href="https://gmpg.org/xfn/11" />
		<?php wp_head(); ?>
		<style>
			/* Reset theme styles */
			html, body, #page, #content {
				padding: 0 !important;
				margin: 0 !important;
			}
		</style>
	</head>
	<body <?php body_class(); ?>>
		<div id="page" class="site">
			<div id="content" class="site-content">
				<?php
				$registry = WP_Block_Type_Registry::get_instance();
				$block    = $registry->get_registered( 'core/legacy-widget' );
				$output   = $block->render( $attributes );

				if ( isset( $attributes['idBase'] ) ) {
					if ( $widget_object ) {
						$widget = $wp_registered_widgets[ $widget_object_id ];
					} else {
						$widget = array();
					}

					if ( isset( $attributes['instance']['encoded'], $attributes['instance']['hash'] ) ) {
						$serialized_instance = base64_decode( $attributes['instance']['encoded'] );
						if ( wp_hash( $serialized_instance ) !== $attributes['instance']['hash'] ) {
							return '';
						}
						$instance = unserialize( $serialized_instance );
					} else {
						$instance = array();
					}

					$output = apply_filters( sprintf( 'enlightenment_widget_%s', $id_base ), $output, $widget, $instance );
				}

				echo $output;
				?>
			</div><!-- #content -->
		</div><!-- #page -->
		<?php wp_footer(); ?>
	</body>
	</html>
	<?php

	exit;
}

// Use admin_init instead of init to ensure get_current_screen function is already available.
// This isn't strictly required, but enables better compatibility with existing plugins.
// See: https://github.com/WordPress/gutenberg/issues/32624.
add_action( 'admin_init', 'enlightenment_handle_legacy_widget_preview_iframe', 18 );
