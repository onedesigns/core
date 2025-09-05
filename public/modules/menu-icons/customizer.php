<?php

function enlightenment_menu_icons_register_customizer_styles() {
	wp_enqueue_style( 'font-awesome' );
}
add_action( 'customize_controls_enqueue_scripts', 'enlightenment_menu_icons_register_customizer_styles', 8 );

function enlightenment_menu_icons_enqueue_customizer_scripts() {
	wp_enqueue_style( 'enlightenment-menu-icons', enlightenment_styles_directory_uri() . '/menu-icons.css', array( 'enlightenment-dropdown', current_theme_supports('enlightenment-menu-icons', 'iconset' ) ), null );

	wp_enqueue_script( 'enlightenment-dropdown' );
}
add_action( 'customize_controls_enqueue_scripts', 'enlightenment_menu_icons_enqueue_customizer_scripts' );

function enlightenment_wp_nav_menu_item_icon_field_customize_template() {
	?>
	<#
	var api         = wp.customize,
		args        = enlightenment_customize_controls_args,
		menuItem    = api.settings.settings['nav_menu_item[' + data.menu_item_id + ']'],
		currentIcon = menuItem ? menuItem.value.icon : '';
	#>
	<div class="field-icon description description-thin">
		<label>
			<?php _e( 'Menu Item Icon', 'enlightenment' ); ?><br />

			<div id="dd-{{ data.menu_item_id }}" class="wrapper-dropdown" tabindex="1">
				<span class="current"><# if ( currentIcon ) { #><span class="{{ args.menu_icons.prefix }}{{ currentIcon }}"></span> <# } #>{{ args.menu_icons.icons[ currentIcon ] }}</span>

				<ul class="dropdown">

					<#
					_.each( args.menu_icons.icons, function( name, icon ) {
						#>

						<li<# if ( currentIcon == icon ) { #> class="selected"<# } #>>
							<a href="#" data-icon="{{ icon }}">
								<# if ( icon ) { #><span class="{{ args.menu_icons.prefix }}{{ icon }}"></span> <# } #>
								{{{ name }}}
							</a>
						</li>

						<#
					});
					#>

				</ul>
			</div>

			<input type="hidden" class="widefat edit-menu-item-icon" name="menu-item-icon[{{ data.menu_item_id }}]" value="{{ currentIcon }}" />
		</label>
	</div>
	<?php
}
add_action( 'wp_nav_menu_item_custom_fields_customize_template', 'enlightenment_wp_nav_menu_item_icon_field_customize_template' );

function enlightenment_menu_icons_customize_controls_args( $args ) {
	$args['menu_icons'] = array(
		'icons'  => enlightenment_menu_icons(),
		'prefix' => enlightenment_menu_icon_prefix(),
	);

	return $args;
}
add_filter( 'enlightenment_customize_controls_args', 'enlightenment_menu_icons_customize_controls_args' );

function enlightenment_menu_icons_customize_save_menu_item_icons( $wp_customize ) {
	$values = $wp_customize->unsanitized_post_values(
		array(
			'exclude_post_data' => true,
			'exclude_changeset' => false,
		)
	);

	foreach ( $values as $setting_id => $value ) {
		$setting = $wp_customize->get_setting( $setting_id );

		if ( ! $setting instanceof WP_Customize_Nav_Menu_Item_Setting ) {
			continue;
		}

		if ( ! $setting->check_capabilities() ) {
			continue;
		}

		if ( ! isset( $value['icon'] ) ) {
			continue;
		}

		$icons = enlightenment_menu_icons();

		if ( ! array_key_exists( $value['icon'], $icons ) ) {
			continue;
		}

		update_post_meta( $setting->post_id, '_enlightenment_menu_item_icon', $value['icon'] );
	}
}
add_action( 'customize_save_after', 'enlightenment_menu_icons_customize_save_menu_item_icons' );
