<?php

function enlightenment_menu_icons_enqueue_scripts( $hook ) {
	if( 'nav-menus.php' != $hook ) {
		return;
	}

	wp_enqueue_style( current_theme_supports('enlightenment-menu-icons', 'iconset' ) );
	wp_enqueue_style( 'enlightenment-menu-icons', enlightenment_styles_directory_uri() . '/menu-icons.css', array( 'enlightenment-dropdown', current_theme_supports( 'enlightenment-menu-icons', 'iconset' ) ), null );

	wp_enqueue_script( 'enlightenment-dropdown' );
}
add_action( 'admin_enqueue_scripts', 'enlightenment_menu_icons_enqueue_scripts' );

function enlightenment_wp_nav_menu_item_icon_field( $item_id, $item ) {
	$icons  = enlightenment_menu_icons();
	$prefix = enlightenment_menu_icon_prefix();
	?>

	<div class="field-icon description description-wide">
		<label for="edit-menu-item-icon-<?php echo $item_id; ?>">
			<?php _e( 'Menu Item Icon', 'enlightenment' ); ?><br />

			<div id="dd-<?php echo $item_id; ?>" class="wrapper-dropdown" tabindex="1">
				<span class="current"><?php echo ( empty( $item->icon ) ? '' : '<span class="' . $prefix . esc_attr( $item->icon ) . '"></span> ' ) . $icons[ $item->icon ]; ?></span>

				<ul class="dropdown">

					<?php foreach( $icons as $icon => $name ) : ?>

						<li<?php echo $item->icon == $icon ? ' class="selected"' : ''; ?>>
							<a href="#" data-icon="<?php echo esc_attr( $icon ); ?>">
								<?php echo ( empty( $icon ) ? '' : '<span class="' . $prefix . esc_attr( $icon ) . '"></span> ' ); ?>
								<?php echo esc_attr( $name ); ?>
							</a>
						</li>

					<?php endforeach; ?>

				</ul>
			</div>

			<input type="hidden" id="edit-menu-item-icon-<?php echo $item_id; ?>" class="widefat edit-menu-item-icon" name="menu-item-icon[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->icon ); ?>" />
		</label>
	</div>

	<script>
		(function($) {
			$(document).ready(function() {
				var dw = $('#dd-<?php echo $item_id; ?>'),
					di = dw.find('ul.dropdown > li > a'),
					dd = new DropDown( dw );

				di.on('click',function( event ){
					var $this = $(this);

					$this.closest('.wrapper-dropdown').next('.edit-menu-item-icon').val( $this.data('icon') ).trigger('input').trigger('change');
				});
			});
		})(jQuery);
	</script>

	<?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'enlightenment_wp_nav_menu_item_icon_field', 10, 2 );

function enlightenment_menu_icons_nav_fields( $menu_id, $menu_item_db_id, $args ) {
	// Check if element is properly sent
	if ( isset( $_REQUEST['menu-item-icon'] ) && is_array( $_REQUEST['menu-item-icon'] ) ) {
		$icon = $_REQUEST['menu-item-icon'][ $menu_item_db_id ];
		update_post_meta( $menu_item_db_id, '_enlightenment_menu_item_icon', $icon );
	}
}
add_action( 'wp_update_nav_menu_item', 'enlightenment_menu_icons_nav_fields', 10, 3 );

function enlightenment_menu_icons_enqueue_block_editor_assets() {
	if ( ! is_admin() ) {
		return;
	}

	wp_enqueue_style( current_theme_supports( 'enlightenment-menu-icons', 'iconset' ) );
}
add_action( 'enqueue_block_assets', 'enlightenment_menu_icons_enqueue_block_editor_assets' );
