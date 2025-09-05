<?php

function enlightenment_web_fonts_get_default_theme_mods() {
	$mods = array(
		'host_locally'      => current_theme_supports( 'enlightenment-web-fonts', 'host_locally' ),
		'provider'          => current_theme_supports( 'enlightenment-web-fonts', 'provider' ),
		'subsets'           => current_theme_supports( 'enlightenment-web-fonts', 'subsets' ),
		'filter_by_subsets' => current_theme_supports( 'enlightenment-web-fonts', 'filter_by_subsets' ),
		'font_display'      => current_theme_supports( 'enlightenment-web-fonts', 'font_display' ),
		'sort_fonts_by'     => current_theme_supports( 'enlightenment-web-fonts', 'sort_fonts_by' ),
	);

	if ( ! is_string( $mods['provider'] ) || empty( $mods['provider'] ) ) {
		$mods['provider'] = 'google-fonts';
	}

	if ( ! is_array( $mods['subsets'] ) || empty( $mods['subsets'] ) ) {
		$mods['subsets'] = array( 'latin' );
	}

	if ( ! is_string( $mods['font_display'] ) || empty( $mods['font_display'] ) ) {
		$mods['font_display'] = 'auto';
	}

	if ( ! is_string( $mods['sort_fonts_by'] ) || empty( $mods['sort_fonts_by'] ) ) {
		$mods['sort_fonts_by'] = 'popularity';
	}

	$has_filter = ( 10 === has_filter( 'enlightenment_web_fonts', 'enlightenment_set_theme_options_web_fonts' ) );

	if ( $has_filter ) {
		remove_filter( 'enlightenment_web_fonts', 'enlightenment_set_theme_options_web_fonts' );
	}

	$mods['fonts'] = enlightenment_get_web_fonts();

	if ( $has_filter ) {
		add_filter( 'enlightenment_web_fonts', 'enlightenment_set_theme_options_web_fonts' );
	}

	return apply_filters( 'enlightenment_web_fonts_default_theme_mods', $mods );
}

function enlightenment_get_web_fonts() {
	return apply_filters( 'enlightenment_web_fonts', array(
		'Roboto' => array(
			'family'   => 'Roboto',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Open Sans' => array(
			'family'   => 'Open Sans',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Montserrat' => array(
			'family'   => 'Montserrat',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Poppins' => array(
			'family'   => 'Poppins',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'devanagari' ),
		),
		'Lato' => array(
			'family'   => 'Lato',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext' ),
		),
		'Inter' => array(
			'family'   => 'Inter',
			'category' => 'sans-serif',
			'variants' => array( '300', '400', '500', '600', '700' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Oswald' => array(
			'family'   => 'Oswald',
			'category' => 'sans-serif',
			'variants' => array( '300', '400', '500', '600', '700' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Noto Sans' => array(
			'family'   => 'Noto Sans',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese', 'devanagari' ),
		),
		'Raleway' => array(
			'family'   => 'Raleway',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Playfair Display' => array(
			'family'   => 'Playfair Display',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic', 'vietnamese' ),
		),
		'Nunito Sans' => array(
			'family'   => 'Nunito Sans',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Ubuntu' => array(
			'family'   => 'Ubuntu',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext' ),
		),
		'Rubik' => array(
			'family'   => 'Rubik',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic', 'cyrillic-ext', 'arabic', 'hebrew' ),
		),
		'Roboto Slab' => array(
			'family'   => 'Roboto Slab',
			'category' => 'serif',
			'variants' => array( '300', '400', '500', '600', '700' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Merriweather' => array(
			'family'   => 'Merriweather',
			'category' => 'serif',
			'variants' => array( '300', '300italic', '400', 'italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'PT Sans' => array(
			'family'   => 'PT Sans',
			'category' => 'sans-serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic, cyrillic-ext' ),
		),
		'Lora' => array(
			'family'   => 'Lora',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Fira Sans' => array(
			'family'   => 'Fira Sans',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'PT Serif' => array(
			'family'   => 'PT Serif',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic, cyrillic-ext' ),
		),
		'Libre Franklin' => array(
			'family'   => 'Libre Franklin',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'vietnamese' ),
		),
		'Noto Serif' => array(
			'family'   => 'Noto Serif',
			'category' => 'serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Nanum Gothic' => array(
			'family'   => 'Nanum Gothic',
			'category' => 'sans-serif',
			'variants' => array( '400', '700' ),
			'subsets'  => array( 'latin', 'korean' ),
		),
		'Libre Baskerville' => array(
			'family'   => 'Libre Baskerville',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700' ),
			'subsets'  => array( 'latin', 'latin-ext' ),
		),
		'Bitter' => array(
			'family'   => 'Bitter',
			'category' => 'serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'EB Garamond' => array(
			'family'   => 'EB Garamond',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Assistant' => array(
			'family'   => 'Assistant',
			'category' => 'sans-serif',
			'variants' => array( '300', '400', '500', '600', '700' ),
			'subsets'  => array( 'latin', 'latin-ext', 'hebrew' ),
		),
		'Hind' => array(
			'family'   => 'Hind',
			'category' => 'sans-serif',
			'variants' => array( '300', '400', '500', '600', '700' ),
			'subsets'  => array( 'latin', 'latin-ext', 'devanagari' ),
		),
		'DM Serif Display' => array(
			'family'   => 'DM Serif Display',
			'category' => 'serif',
			'variants' => array( '400', 'italic' ),
			'subsets'  => array( 'latin', 'latin-ext' ),
		),
		'Crimson Text' => array(
			'family'   => 'Crimson Text',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'vietnamese' ),
		),
		'Cormorant Garamond' => array(
			'family'   => 'Cormorant Garamond',
			'category' => 'serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Zilla Slab' => array(
			'family'   => 'Zilla Slab',
			'category' => 'serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext' ),
		),
		'Source Sans 3' => array(
			'family'   => 'Source Sans 3',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Merriweather Sans' => array(
			'family'   => 'Merriweather Sans',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'cyrillic-ext', 'vietnamese' ),
		),
		'Nanum Myeongjo' => array(
			'family'   => 'Nanum Myeongjo',
			'category' => 'serif',
			'variants' => array( '400', '700' ),
			'subsets'  => array( 'latin', 'korean' ),
		),
		'Source Serif 4' => array(
			'family'   => 'Source Serif 4',
			'category' => 'serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Frank Ruhl Libre' => array(
			'family'   => 'Frank Ruhl Libre',
			'category' => 'serif',
			'variants' => array( '300', '400', '500', '600','700' ),
			'subsets'  => array( 'latin', 'latin-ext', 'hebrew' ),
		),
		'Neuton' => array(
			'family'   => 'Neuton',
			'category' => 'serif',
			'variants' => array( '300', '400', 'italic', '700' ),
			'subsets'  => array( 'latin', 'latin-ext' ),
		),
		'Bodoni Moda' => array(
			'family'   => 'Bodoni Moda',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext' ),
		),
		'Literata' => array(
			'family'   => 'Literata',
			'category' => 'serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' ),
			'subsets'  => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Libre Caslon Text' => array(
			'family'   => 'Libre Caslon Text',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700' ),
			'subsets'  => array( 'latin', 'latin-ext' ),
		),
	) );
}

function enlightenment_get_available_subsets() {
	return apply_filters( 'enlightenment_available_subsets', array(
		'latin'        => __( 'Latin',             'enlightenment' ),
		'latin-ext'    => __( 'Latin Extended',    'enlightenment' ),
		'greek'        => __( 'Greek',             'enlightenment' ),
		'greek-ext'    => __( 'Greek Extended',    'enlightenment' ),
		'cyrillic'     => __( 'Cyrillic',          'enlightenment' ),
		'cyrillic-ext' => __( 'Cyrillic Extended', 'enlightenment' ),
		'devangari'    => __( 'Devangari',         'enlightenment' ),
		'khmer'        => __( 'Khmer',             'enlightenment' ),
		'telugu'       => __( 'Telugu',            'enlightenment' ),
		'vietnamese'   => __( 'Vietnamese',        'enlightenment' ),
	) );
}

function enlightenment_get_fonts_to_load() {
	global $enlightenment_web_fonts;

	if ( ! did_action( 'enlightenment_enqueue_fonts' ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'The action \'enlightenment_enqueue_fonts\' must be done first. Did you try to load the web fonts too soon?', 'enlightenment' ), '2.0.0' );
	}

	if ( ! isset( $enlightenment_web_fonts ) ) {
		$enlightenment_web_fonts = array();
	}

	return apply_filters( 'enlightenment_fonts_to_load', $enlightenment_web_fonts );
}

function enlightenment_enqueue_font( $font, $styles = null ) {
	if ( null === $styles ) {
		$styles = current_theme_supports( 'enlightenment-web-fonts', 'variants' );
	}

	global $enlightenment_web_fonts;

	if ( ! isset( $enlightenment_web_fonts ) ) {
		$enlightenment_web_fonts = array();
	}

	$fonts = enlightenment_get_web_fonts();

	if ( array_key_exists( $font, $fonts ) ) {
		if ( isset( $enlightenment_web_fonts[ $font ] ) ) {
			$enlightenment_web_fonts[ $font ] = array_unique( array_merge( $enlightenment_web_fonts[ $font ], $styles ) );
		} else {
			$enlightenment_web_fonts[ $font ] = $styles;
		}
	}
}

function enlightenment_dequeue_font( $font ) {
	global $enlightenment_web_fonts;

	if ( ! isset( $enlightenment_web_fonts ) ) {
		return;
	}

	if ( array_key_exists( $font, $enlightenment_web_fonts ) ) {
		unset( $enlightenment_web_fonts[$font] );

		$enlightenment_web_fonts = array_values( $enlightenment_web_fonts );
	}
}

function enlightenment_get_web_font_props( $font ) {
	$slug     = sanitize_title( $font );
	$subsets  = current_theme_supports( 'enlightenment-web-fonts', 'subsets' );
	$response = wp_remote_get(
		add_query_arg(
			'subsets',
			join( ',', $subsets ),
			sprintf( 'https://gwfh.mranftl.com/api/fonts/%s', $slug )
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
		$props = json_decode( wp_remote_retrieve_body( $response ) );
	} else {
		$props = new WP_Error( 'enlightenment_get_web_font_props_failed', sprintf( __( 'Unable to retrieve information for web font &ldquo;%s&rdquo; at this time. Make sure you are online and try again.', 'enlightenment' ), $font ) );
	}

	return apply_filters( 'enlightenment_web_font_props', $props, $font );
}

function enlightenment_get_web_fonts_directory_name() {
	return apply_filters( 'enlightenment_web_fonts_directory_name', 'fonts' );
}

function enlightenment_get_web_fonts_directory_path( $path = '' ) {
	$uploads_dir = wp_get_upload_dir();
	$dir_name    = enlightenment_get_web_fonts_directory_name();
	$fonts_path  = sprintf( '%s/%s', $uploads_dir['basedir'], $dir_name );

	if ( ! empty( $path ) ) {
		$fonts_path .= '/' . $path;
	}

	$fonts_path = apply_filters( 'enlightenment_web_fonts_directory_path', $fonts_path, $path );

	return $fonts_path;
}

function enlightenment_get_web_fonts_directory_url( $path = '' ) {
	$uploads_dir = wp_get_upload_dir();
	$dir_name    = enlightenment_get_web_fonts_directory_name();
	$fonts_url   = sprintf( '%s/%s', $uploads_dir['baseurl'], $dir_name );

	if ( ! empty( $path ) ) {
		$fonts_url .= '/' . $path;
	}

	$fonts_url = apply_filters( 'enlightenment_web_fonts_directory_url', $fonts_url, $path );

	return $fonts_url;
}

function enlightenment_download_web_font( $font, $styles = array(), $overwrite = false ) {
	global $wp_filesystem, $enlightenment_download_web_fonts_errors;

	$font_props = enlightenment_get_web_font_props( $font );

	if ( is_wp_error( $font_props ) ) {
		return $font_props;
	}

	$variants = array_map( function( $variant ) {
		return 'regular' == $variant->id ? '400' : $variant->id;
	}, $font_props->variants );

	$files = array();

	foreach ( $font_props->variants as $variant ) {
		$variant_id = 'regular' == $variant->id ? '400' : $variant->id;

		$files[ $variant_id ] = array_diff_key( (array) $variant, array(
			'id'         => null,
			'fontFamily' => null,
			'fontStyle'  => null,
			'fontWeight' => null,
		) );
	}

	if ( empty( $styles ) ) {
		$styles = current_theme_supports( 'enlightenment-web-fonts', 'variants' );
	} elseif ( 'all' === $styles ) {
		$styles = $variants;
	}

	$styles = array_map( function( $style ) {
		return 'regular' == $style ? '400' : $style;
	}, $styles );

	if ( empty( array_intersect( $styles, $variants ) ) ) {
		return new WP_Error( 'enlightenment_web_font_variants_empty', sprintf(
			__( 'None of the requested variants: "%1$s" exist for the font "%2$s".', 'enlightenment' ),
			join( ', ', $variants ),
			$font
		) );
	}

	if ( ! function_exists( 'download_url' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	if ( null === $wp_filesystem ) {
		if ( ! WP_Filesystem() ) {
			return new WP_Error( 'enlightenment_wp_filesystem_failed', __( 'Unable to initialize the file system. Make sure the uploads directory is writable.', 'enlightenment' ) );
		}
	}

	$rel_path   = sprintf( '%s/%s', $font_props->id, $font_props->version );
	$fonts_path = enlightenment_get_web_fonts_directory_path( $rel_path );
	$fonts_url  = enlightenment_get_web_fonts_directory_url( $rel_path );

	if ( ! is_dir( $fonts_path ) ) {
		if ( ! wp_mkdir_p( $fonts_path ) ) {
			return new WP_Error( 'enlightenment_mkdir_failed', sprintf( __( 'Unable to create the directory %s. Make sure the uploads directory is writable.', 'enlightenment' ), sprintf( '<code>fonts</code>', $fonts_path ) ) );
		}
	}

	if ( ! isset( $enlightenment_download_web_fonts_errors ) ) {
		$enlightenment_download_web_fonts_errors = new WP_Error();
	}

	$src = array();

	foreach ( $styles as $style ) {
		if ( ! in_array( $style, $variants ) ) {
			$enlightenment_download_web_fonts_errors->add( 'enlightenment_web_font_variant_inexistent', sprintf(
				__( 'The font %1$s does not have the variant %2$s.', 'enlightenment' ),
				sprintf( '&ldquo;%s&rdquo;', $font ),
				sprintf( '<code>%s</code>',  $style )
			) );

			continue;
		}

		$src[ $style ] = array();

		foreach ( $files[ $style ] as $format => $url ) {
			$filename = sprintf(
				'%s-%s-%s-%s.%s',
				$font_props->id,
				$font_props->version,
				$font_props->storeID,
				( '400' == $style ? 'regular' : $style ),
				$format
			);
			$fontpath = sprintf( '%s/%s', $fonts_path, $filename );

			if ( ! $overwrite && $wp_filesystem->exists( $fontpath ) ) {
				$src[ $style ][ $format ] = sprintf( '%s/%s', $rel_path, $filename );

				continue;
			}

			$file = download_url( $url );

			if ( is_wp_error( $file ) ) {
				$enlightenment_download_web_fonts_errors->add( 'enlightenment_download_web_font_variant_failed', sprintf(
					__( 'The font "%1$s" with variant "%2$s" and subset "%3$s" could not be downloaded.', 'enlightenment' ),
					$font,
					sprintf( '<code>%s</code>', $style ),
					sprintf( '<code>%s</code>', $font_props->storeID )
				) );

				continue;
			}

			if ( ! $wp_filesystem->move( $file, $fontpath, $overwrite ) ) {
				$enlightenment_download_web_fonts_errors->add( 'enlightenment_save_web_font_failed', sprintf(
					__( 'The font "%1$s" with variant %2$s and subset %3$s could not be saved to disk.', 'enlightenment' ),
					$font,
					sprintf( '<code>%s</code>', $style ),
					sprintf( '<code>%s</code>', $font_props->storeID )
				) );

				continue;
			}

			$src[ $style ][ $format ] = sprintf( '%s/%s', $rel_path, $filename );
		}

		if ( empty( $src[ $style ] ) ) {
			unset( $src[ $style ] );
		}
	}

	if ( empty( $src ) ) {
		if ( $enlightenment_download_web_fonts_errors->has_errors() ) {
			return $enlightenment_download_web_fonts_errors;
		} else {
			return new WP_Error( 'enlightenment_download_web_font_failed', sprintf( __( 'All attempts to download the font "%s" have failed.', 'enlightenment' ), $font ) );
		}
	}

	$web_fonts = enlightenment_get_web_fonts();
	$subsets   = current_theme_supports( 'enlightenment-web-fonts', 'subsets' );
	$subsetMap = array_values( array_intersect( $subsets, array_keys( array_filter( (array) $font_props->subsetMap, function( $value ) {
		return $value;
	} ) ) ) );

	if ( isset( $web_fonts[ $font ] ) ) {
		$atts = $web_fonts[ $font ];
	} else {
		$atts = array(
			'family'   => $font_props->family,
			'category' => $font_props->category,
			'variants' => $variants,
			'subsets'  => $font_props->subsets,
		);
	}

	if ( ! empty( $atts['src'] ) ) {
		// array_merge would produce unexpected results due to numeric keys.
		$src = $src + $atts['src'];
	}

	$atts = array_merge( $atts, array(
		'src'          => $src,
		'subsetMap'    => $subsetMap,
		'lastModified' => $font_props->lastModified,
		'version'      => $font_props->version,
	) );

	$web_fonts[ $font ] = $atts;

	$mod = get_theme_mod( 'web_fonts' );

	if ( ! is_array( $mod ) ) {
		$mod = array();
	}

	$mod['fonts'] = $web_fonts;

	set_theme_mod( 'web_fonts', $mod );

	return $atts;
}

function enlightenment_get_web_fonts_inline_style() {
	$output    = '';
	$fonts     = enlightenment_get_fonts_to_load();
	$web_fonts = enlightenment_get_web_fonts();

	foreach ( $fonts as $font => $styles ) {
		$variants = $styles;
		$atts     = enlightenment_get_font_atts( $font );

		if ( ! empty( $atts['src'] ) ) {
			$variants = array_diff( $styles, array_keys( $atts['src'] ) );

			if ( ! empty( $atts['subsetMap'] ) ) {
				$subsets = array_intersect( $atts['subsets'], current_theme_supports( 'enlightenment-web-fonts', 'subsets' ) );

				if ( count( array_diff( $subsets, $atts['subsetMap'] ) ) ) {
					$variants = array_merge( $styles, array_keys( $atts['src'] ) );
				}
			}
		}

		if ( ! empty( $variants ) ) {
			$props = enlightenment_download_web_font( $font, $variants );

			if ( ! is_wp_error( $props ) ) {
				$atts = $props;
			}
		}

		foreach ( $styles as $style ) {
			if ( ! isset( $atts['src'][ $style ] ) ) {
				continue;
			}

			$output .= "\n@font-face {\n";
			$output .= sprintf( "\tfont-family: '%s';\n", $font );

			if ( false !== strpos( $style, 'italic' ) ) {
				$font_style = 'italic';
			} else {
				$font_style = 'normal';
			}

			$output .= sprintf( "\tfont-style: %s;\n", $font_style  );

			$font_weight = str_replace( 'italic', '', $style );

			if ( empty( $font_weight ) ) {
				$font_weight = '400';
			}

			$output .= sprintf( "\tfont-weight: %s;\n", $font_weight );

			$font_display = current_theme_supports( 'enlightenment-web-fonts', 'font_display' );

			// The value 'auto' is the default and there's no need to add it.
			if ( in_array( $font_display, array( 'block', 'swap', 'fallback', 'optional' ) ) ) {
				$output .= sprintf( "\tfont-display: %s;\n", $font_display );
			}

			// Move 'woff' format higher up the list.
			if ( isset( $atts['src'][ $style ]['woff'] ) ) {
				$atts['src'][ $style ] = array_merge( array(
					'woff' => $atts['src'][ $style ]['woff'],
				), $atts['src'][ $style ] );
			}

			// Move 'woff2' format highest up the list.
			if ( isset( $atts['src'][ $style ]['woff2'] ) ) {
				$atts['src'][ $style ] = array_merge( array(
					'woff2' => $atts['src'][ $style ]['woff2'],
				), $atts['src'][ $style ] );
			}

			if ( isset( $atts['src'][ $style ]['eot'] ) ) {
				// Put eot format at the top to prevent IE from requesting an invalid URL.
				$atts['src'][ $style ] = array_merge( array(
					'eot' => $atts['src'][ $style ]['eot'],
				), $atts['src'][ $style ] );

				$url = wp_http_validate_url( $atts['src'][ $style ]['eot'] );

				if ( ! $url ) {
					$url = enlightenment_get_web_fonts_directory_url( $atts['src'][ $style ]['eot'] );
				}

				// IE9 Compat Mode
				$output .= sprintf( "\tsrc: url(%s);\n", $url );
			}

			if ( isset( $atts['src'][ $style ]['svg'] ) ) {
				// Put svg format at the bottom to prevent loading before other formats.
				$atts['src'][ $style ] = array_merge( array_diff( $atts['src'][ $style ], array(
					'svg' => $atts['src'][ $style ]['svg'],
				) ), array(
					'svg' => $atts['src'][ $style ]['svg'],
				) );
			}

			$src = array();

			foreach ( $atts['src'][ $style ] as $format => $path ) {
				switch ( $format ) {
					case 'eot':
						$format = 'embedded-opentype';
						$path  .= '?#iefix';

						break;

					case 'ttf':
						$format = 'truetype';

						break;

					case 'svg':
						// Append #FontName to URL to target the <font> element inside the svg file.
						$path .= sprintf( '#%s', str_replace( ' ', '', $font ) );

						break;
				}

				$url = wp_http_validate_url( $path );

				if ( ! $url ) {
					$url = enlightenment_get_web_fonts_directory_url( $path );
				}

				$src[] = sprintf( "url(%s) format('%s')", $url, $format );
			}

			$output .= sprintf( "\tsrc: %s;\n", join( ', ', $src ) );

			if ( isset( $atts['ascent'] ) ) {
				$ascent = abs( floatval( $atts['ascent'] ) );

				if ( ! empty( $ascent ) ) {
					$output .= sprintf( "\tascent-override: %d%%;\n", $ascent );
				}
			}

			$output .= "}\n";
		}
	}

	return apply_filters( 'enlightenment_web_fonts_inline_style', $output );
}

function enlightenment_get_web_fonts_stylesheet_uri() {
	$output = '';
	$fonts  = enlightenment_get_fonts_to_load();

	if ( ! empty( $fonts ) ) {
		$families = array();

		foreach ( $fonts as $font => $styles ) {
			$family = str_replace( ' ', '+', $font );

			if (
				! empty( $styles )
				&&
				array( '400' ) != $styles
				&&
				array( 'regular' ) != $styles
			) {
				$family .= sprintf( ':%s', join( ',', $styles ) );
			}

			$families[] = $family;
		}

		$query_args = array(
			'family' => join( urlencode( '|' ), $families ),
		);

		$subset = array_intersect(
			current_theme_supports( 'enlightenment-web-fonts', 'subsets' ),
			array_diff(
				array_keys( enlightenment_get_available_subsets() ),
				// The subset 'latin' is always added when present.
				array( 'latin' )
			)
		);

		if ( ! empty( $subset ) && array( 'latin' ) != $subset ) {
			$query_args['subset'] = join( ',', $subset );
		}

		$font_display = current_theme_supports( 'enlightenment-web-fonts', 'font_display' );

		// The value 'auto' is the default and there's no need to add it.
		if ( in_array( $font_display, array( 'block', 'swap', 'fallback', 'optional' ) ) ) {
			$query_args['display'] = $font_display;
		}

		switch ( current_theme_supports( 'enlightenment-web-fonts', 'provider' ) ) {
			case 'bunny-fonts':
				$url = 'https://fonts.bunny.net/css';
				break;

			case 'google-fonts':
			default:
				$url = 'https://fonts.googleapis.com/css';
		}

		$output = add_query_arg( $query_args, $url );
	}

	return apply_filters( 'enlightenment_web_fonts_stylesheet_uri', $output );
}

function enlightenment_get_font_atts( $font ) {
	$fonts = enlightenment_get_web_fonts();

	if ( isset( $fonts[ $font ] ) ) {
		return $fonts[ $font ];
	}

	return false;
}

function enlightenment_custom_typography_selectors() {
	return apply_filters( 'enlightenment_custom_typography_selectors', array() );
}
