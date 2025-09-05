<?php

function enlightenment_web_fonts_set_theme_support_args() {
	$defaults = array(
		'host_locally'      => false,
		'provider'          => 'google-fonts',
		'variants'          => array( '400', 'italic', '700' ),
		'fuzzy_match'       => true,
		'subsets'           => array( 'latin' ),
		'filter_by_subsets' => true,
		'font_display'      => 'auto',
		'sort_fonts_by'     => 'popularity',
		'extra_fonts'       => array(),
		'theme_defaults'    => array(),
	);

	$args = get_theme_support( 'enlightenment-web-fonts' );
	$args = is_array( $args ) ? array_shift( $args ) : array();
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'enlightenment_web_fonts_theme_support_args', $args );

	global $_wp_theme_features;
	if( ! is_array( $_wp_theme_features['enlightenment-web-fonts'] ) ) {
		$_wp_theme_features['enlightenment-web-fonts'] = array();
	}

	$_wp_theme_features['enlightenment-web-fonts'][0] = $args;
}
add_action( 'after_setup_theme', 'enlightenment_web_fonts_set_theme_support_args', 50 );

function enlightenment_web_fonts_enqueue_theme_default_fonts() {
	$theme_support = get_theme_support( 'enlightenment-web-fonts' );
	$theme_support = array_shift( $theme_support );

	$fonts = $theme_support['theme_defaults'];

	foreach( $fonts as $key => $value ) {
		if( is_int( $key ) ) {
			enlightenment_enqueue_font( $value );
		} else {
			enlightenment_enqueue_font( $key, $value );
		}
	}
}
add_action( 'enlightenment_enqueue_fonts', 'enlightenment_web_fonts_enqueue_theme_default_fonts' );

function enlightenment_enqueue_fonts() {
	global $enlightenment_web_fonts, $enlightenment_web_fonts_errors;

	if ( ! isset( $enlightenment_web_fonts ) ) {
		$enlightenment_web_fonts = array();
	}

	do_action( 'enlightenment_enqueue_fonts' );

	if ( ! empty( $enlightenment_web_fonts ) ) {
		if ( ! isset( $enlightenment_web_fonts_errors ) ) {
			$enlightenment_web_fonts_errors = new WP_Error();
		}

		$variants = current_theme_supports( 'enlightenment-web-fonts', 'variants' );
		$subsets  = current_theme_supports( 'enlightenment-web-fonts', 'subsets'  );

		foreach ( $enlightenment_web_fonts as $font => $styles ) {
			if ( empty( $styles ) ) {
				$styles = $variants;
			}

			$atts = enlightenment_get_font_atts( $font );

			/* Backwards compatibility code, to be removed in a future version */
			foreach ( $atts['variants'] as $key => $variant ) {
				$variant = str_replace( 'light',    '300', $variant );
				$variant = str_replace( 'normal',   '400', $variant );
				$variant = str_replace( 'medium',   '700', $variant );
				$variant = str_replace( 'semibold', '600', $variant );
				$variant = str_replace( 'bold',     '700', $variant );

				$atts['variants'][ $key ] = $variant;
			}

			foreach ( $atts['variants'] as $key => $variant ) {
				if ( 'regular' == $variant ) {
					$atts['variants'][ $key ] = '400';
				}
			}

			foreach ( $styles as $key => $style ) {
				if ( 'regular' == $style ) {
					$style = '400';
				}

				if ( ! in_array( $style, $atts['variants'], true ) ) {
					if ( current_theme_supports( 'enlightenment-web-fonts', 'fuzzy_match' ) ) {
						$normal = str_replace( 'italic', '', $style );

						if ( empty( $normal ) ) {
							$normal = '400';
						}

						if ( false !== strpos( $style, 'italic' ) ) {
							if (
								in_array( $style, array( '100italic', '200italic' ), true )
								&&
								in_array( '300italic', $atts['variants'], true )
							) {
								// Fall back 100italic, 200italic to 300italic
								if ( ! in_array( '300italic', $styles, true ) ) {
									$styles[ $key ] = '300italic';
								} else {
									unset( $styles[ $key ] );
								}

								$enlightenment_web_fonts_errors->add(
									'enlightenment_web_font_variant_unavailable',
									sprintf(
										__( 'The font %1$s does not have the variant %2$s; falling back to %3$s.', 'enlightenment' ),
										sprintf( '&ldquo;%s&rdquo;', $font ),
										sprintf( '<code>%s</code>',  $style ),
										sprintf( '<code>%s</code>',  '300italic' )
									)
								);
							} elseif (
								in_array( $style, array( '600italic', '800italic', '900italic' ), true )
								&&
								in_array( '700italic', $atts['variants'], true )
							) {
								// Fall back 600italic, 800italic, 900italic to 700italic
								if ( ! in_array( '700italic', $styles, true ) ) {
									$styles[ $key ] = '700italic';
								} else {
									unset( $styles[ $key ] );
								}

								$enlightenment_web_fonts_errors->add(
									'enlightenment_web_font_variant_unavailable',
									sprintf(
										__( 'The font %1$s does not have the variant %2$s; falling back to %3$s.', 'enlightenment' ),
										sprintf( '&ldquo;%s&rdquo;', $font ),
										sprintf( '<code>%s</code>',  $style ),
										sprintf( '<code>%s</code>',  '700italic' )
									)
								);
							} elseif ( ! in_array( 'italic', $styles, true ) && in_array( 'italic', $atts['variants'], true ) ) {
								// No close italic variants available, attempt to load 400 italic
								$styles[ $key ] = 'italic';

								$enlightenment_web_fonts_errors->add(
									'enlightenment_web_font_variant_unavailable',
									sprintf(
										__( 'The font %1$s does not have the variant %2$s; falling back to %3$s.', 'enlightenment' ),
										sprintf( '&ldquo;%s&rdquo;', $font ),
										sprintf( '<code>%s</code>',  $style ),
										sprintf( '<code>%s</code>',  'italic' )
									)
								);
							} elseif ( in_array( $normal, $atts['variants'], true ) ) {
								// Italic variant unavailable, attempt to load normal variant
								if ( ! in_array( $normal, $styles, true ) ) {
									$styles[ $key ] = $normal;
								} else {
									unset( $styles[ $key ] );
								}

								$enlightenment_web_fonts_errors->add(
									'enlightenment_web_font_variant_unavailable',
									sprintf(
										__( 'The font %1$s does not have the variant %2$s; falling back to %3$s.', 'enlightenment' ),
										sprintf( '&ldquo;%s&rdquo;', $font ),
										sprintf( '<code>%s</code>',  $style ),
										sprintf( '<code>%s</code>',  $normal )
									)
								);
							} elseif (
								in_array( $style, array( '100italic', '200italic' ), true )
								&&
								in_array( '300', $atts['variants'], true )
							) {
								// normal variant unavailable, fall back 100italic, 200italic to 300
								if ( ! in_array( '300', $styles, true ) ) {
									$styles[ $key ] = '300';
								} else {
									unset( $styles[ $key ] );
								}

								$enlightenment_web_fonts_errors->add(
									'enlightenment_web_font_variant_unavailable',
									sprintf(
										__( 'The font %1$s does not have the variant %2$s; falling back to %3$s.', 'enlightenment' ),
										sprintf( '&ldquo;%s&rdquo;', $font ),
										sprintf( '<code>%s</code>',  $style ),
										sprintf( '<code>%s</code>',  '300' )
									)
								);
							} elseif (
								in_array( $style, array( '600italic', '800italic', '900italic' ), true )
								&&
								in_array( '700', $atts['variants'], true )
							) {
								// normal variant unavailable, fall back 600italic, 800italic, 900italic to 700
								if ( ! in_array( '700', $styles, true ) ) {
									$styles[ $key ] = '700';
								} else {
									unset( $styles[ $key ] );
								}

								$enlightenment_web_fonts_errors->add(
									'enlightenment_web_font_variant_unavailable',
									sprintf(
										__( 'The font %1$s does not have the variant %2$s; falling back to %3$s.', 'enlightenment' ),
										sprintf( '&ldquo;%s&rdquo;', $font ),
										sprintf( '<code>%s</code>',  $style ),
										sprintf( '<code>%s</code>',  '700' )
									)
								);
							} else {
								unset( $styles[ $key ] );

								$enlightenment_web_fonts_errors->add(
									'enlightenment_web_font_variant_unavailable',
									sprintf(
										__( 'The font %1$s does not have the variant %2$s and no suitable fallback has been found, skipping.', 'enlightenment' ),
										sprintf( '&ldquo;%s&rdquo;', $font ),
										sprintf( '<code>%s</code>',  $style ),
										sprintf( '<code>%s</code>',  '700' )
									)
								);
							}
						} elseif (
							in_array( $style, array( '100', '200' ), true )
							&&
							in_array( '300', $atts['variants'], true )
						) {
							// Fall back 100, 200 to 300
							if ( ! in_array( '300', $styles, true ) ) {
								$styles[ $key ] = '300';
							} else {
								unset( $styles[ $key ] );
							}

							$enlightenment_web_fonts_errors->add(
								'enlightenment_web_font_variant_unavailable',
								sprintf(
									__( 'The font %1$s does not have the variant %2$s; falling back to %3$s.', 'enlightenment' ),
									sprintf( '&ldquo;%s&rdquo;', $font ),
									sprintf( '<code>%s</code>',  $style ),
									sprintf( '<code>%s</code>',  '300' )
								)
							);
						} elseif (
							in_array( $style, array( '600', '800', '900' ), true )
							&&
							in_array( '700', $atts['variants'], true )
						) {
							// Fall back 600, 800, 900 to 700
							if ( ! in_array( '700', $styles, true ) ) {
								$styles[ $key ] = '700';
							} else {
								unset( $styles[ $key ] );
							}

							$enlightenment_web_fonts_errors->add(
								'enlightenment_web_font_variant_unavailable',
								sprintf(
									__( 'The font %1$s does not have the variant %2$s; falling back to %3$s.', 'enlightenment' ),
									sprintf( '&ldquo;%s&rdquo;', $font ),
									sprintf( '<code>%s</code>',  $style ),
									sprintf( '<code>%s</code>',  '700' )
								)
							);
						} else {
							// No fallback avaialble, unset style
							unset( $styles[ $key ] );

							$enlightenment_web_fonts_errors->add(
								'enlightenment_web_font_variant_unavailable',
								sprintf(
									__( 'The font %1$s does not have the variant %2$s and no suitable fallback has been found, skipping.', 'enlightenment' ),
									sprintf( '&ldquo;%s&rdquo;', $font ),
									sprintf( '<code>%s</code>',  $style ),
									sprintf( '<code>%s</code>',  '700' )
								)
							);
						}
					} else {
						// Fuzzy match disabled, unset style
						unset( $styles[ $key ] );

						$enlightenment_web_fonts_errors->add(
							'enlightenment_web_font_variant_unavailable',
							sprintf(
								__( 'The font %1$s does not have the variant %2$s and fuzzy match is disabled, skipping.', 'enlightenment' ),
								sprintf( '&ldquo;%s&rdquo;', $font ),
								sprintf( '<code>%s</code>',  $style ),
								sprintf( '<code>%s</code>',  '700' )
							)
						);
					}
				}
			}

			// No styles avaialble, attempt to load at least 400
			if ( empty( $styles ) && current_theme_supports( 'enlightenment-web-fonts', 'fuzzy_match' ) ) {
				if ( in_array( '400', $atts['variants'], true ) ) {
					$styles = array( '400' );

					$enlightenment_web_fonts_errors->add(
						'enlightenment_web_font_variant_unavailable',
						sprintf(
							__( 'No variants for the font %1$s are available or none have been requested; falling back to %2$s.', 'enlightenment' ),
							sprintf( '&ldquo;%s&rdquo;', $font ),
							sprintf( '<code>%s</code>',  '400' )
						)
					);
				} else {
					$enlightenment_web_fonts_errors->add(
						'enlightenment_web_font_variants_unavailable',
						sprintf(
							__( 'No variants for the font %s are available or none have been requested. The font will not be loaded.', 'enlightenment' ),
							sprintf( '&ldquo;%s&rdquo;', $font )
						)
					);
				}
			}

			foreach ( $subsets as $key => $subset ) {
				if ( ! in_array( $subset, $atts['subsets'] ) ) {
					$enlightenment_web_fonts_errors->add(
						'enlightenment_web_font_subset_unsupported',
						sprintf(
							__( 'The font %1$s does not support your selected subset %2$s.', 'enlightenment' ),
							sprintf( '&ldquo;%s&rdquo;', $font ),
							sprintf( '<code>%s</code>',  $subset )
						)
					);
				}
			}

			if ( ! empty( $styles ) ) {
				$enlightenment_web_fonts[ $font ] = array_values( $styles );
			} elseif ( ! current_theme_supports( 'enlightenment-web-fonts', 'fuzzy_match' ) ) {
				$enlightenment_web_fonts_errors->add(
					'enlightenment_web_font_variants_unavailable',
					sprintf(
						__( 'No variants for the font %s are available or none have been requested and fuzzy match is disabled. The font will not be loaded.', 'enlightenment' ),
						sprintf( '&ldquo;%s&rdquo;', $font )
					)
				);
			}
		}
	}
}
add_action( 'init', 'enlightenment_enqueue_fonts', 5 );

function enlightenment_register_web_fonts_style() {
	$fonts = enlightenment_get_fonts_to_load();

	if( ! empty( $fonts ) ) {
		wp_register_style( 'enlightenment-web-fonts', enlightenment_get_web_fonts_stylesheet_uri(), false, null );
	}
}
add_action( 'init', 'enlightenment_register_web_fonts_style' );

function enlightenment_enqueue_theme_options_font( $option, $custom_styles = null ) {
	$defaults = enlightenment_default_theme_mods();

	$default_font   = $defaults[ $option ];
	$custom_font    = get_theme_mod( $option );
	$default_styles = null;

	if ( ! empty( $custom_styles ) && is_string( $custom_styles ) ) {
		if (
			false !== strpos( $custom_styles, ',' )
			||
			in_array( $custom_styles, array_keys( enlightenment_get_font_variants() ) )
		) {
			$custom_styles = array_map( 'trim', explode( ',', $custom_styles ) );
		} else {
			if ( isset( $defaults[ $custom_styles ] ) ) {
				$default_styles = $defaults[ $custom_styles ];
			}

			$custom_styles = (array) get_theme_mod( $custom_styles );
		}
	}

	if ( is_array( $custom_font ) ) {
		if ( ! isset( $custom_font['font_family'] ) ) {
			return;
		}

		if ( ! empty( $custom_font['font_variant'] ) ) {
			$custom_styles = array_merge( (array) $custom_styles, (array) $custom_font['font_variant'] );
		}

		$custom_font  = $custom_font['font_family'];
		$default_font = $default_font['font_family'];
	}

	if ( ! array_key_exists( $custom_font, enlightenment_get_web_fonts() ) ) {
		return;
	}

	if ( ! empty( $custom_styles ) ) {
		$custom_styles = array_map( fn( $style ) => (string) $style, (array) $custom_styles );
	}

	if ( $custom_font == $default_font && ( empty( $custom_styles ) || $custom_styles == $default_styles ) ) {
		return;
	}

	enlightenment_enqueue_font( $custom_font, $custom_styles );
}

function enlightenment_web_fonts_preconnect( $args = null ) {
	if ( current_theme_supports( 'enlightenment-web-fonts', 'host_locally' ) ) {
		return;
	}

	$defaults = array(
		'links' => array(),
		'cors' => true,
		'echo' => true,
	);

	switch ( current_theme_supports( 'enlightenment-web-fonts', 'provider' ) ) {
		case 'bunny-fonts':
			$defaults['links'][] = array(
				'href' => 'https://fonts.bunny.net',
				'cors' => true,
			);

			break;

		case 'google-fonts':
		default:
			$defaults['links'][] = array(
				'href' => 'https://fonts.googleapis.com',
				'cors' => true,
			);

			$defaults['links'][] = array(
				'href' => 'https://fonts.gstatic.com',
				'cors' => true,
			);
	}

	$defaults = apply_filters( 'enlightenment_web_fonts_preconnect_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$output = '';

	foreach( $args['links'] as $link ) {
		$output .= sprintf(
			'<link rel="preconnect" href="%s"%s />%s',
			esc_url( $link['href'] ),
			$link['cors'] ? ' crossorigin' : '',
			"\n"
		);
	}

	$output = apply_filters( 'enlightenment_web_fonts_preconnect', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
add_action( 'enlightenment_head', 'enlightenment_web_fonts_preconnect', 1 );

function enlightenment_web_fonts_preload( $args = null ) {
	if ( current_theme_supports( 'enlightenment-web-fonts', 'host_locally' ) ) {
		return;
	}

	$defaults = array(
		'as'   => 'style',
		'href' => enlightenment_get_web_fonts_stylesheet_uri(),
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_web_fonts_preload_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['href'] ) ) {
		return;
	}

	$output = sprintf(
		'<link rel="preload"%s href="%s" />%s',
		! empty( $args['as'] ) ? sprintf( ' as="%s"', esc_attr( $args['as'] ) ) : '',
		esc_url( $args['href'] ),
		"\n"
	);
	$output = apply_filters( 'enlightenment_web_fonts_preload', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
add_action( 'enlightenment_head', 'enlightenment_web_fonts_preload', 1 );

function enlightenment_web_fonts_enqueue_block_editor_assets() {
	if ( ! is_admin() ) {
		return;
	}

	$fonts = enlightenment_get_fonts_to_load();

	if ( empty( $fonts ) ) {
		return;
	}

	if ( current_theme_supports( 'enlightenment-web-fonts', 'host_locally' ) ) {
		wp_add_inline_style( 'wp-edit-blocks', enlightenment_get_web_fonts_inline_style() );
	} else {
		wp_enqueue_style( 'enlightenment-web-fonts' );
	}
}
add_action( 'enqueue_block_assets', 'enlightenment_web_fonts_enqueue_block_editor_assets' );
