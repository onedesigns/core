<?php

function enlightenment_set_content_width( $width, $force = false ) {
	global $content_width;

	if( isset( $content_width ) && ! $force ) {
		return;
	}

	$content_width = apply_filters( 'enlightenment_content_width', $width );
}

function enlightenment_filter_current_theme_supports( $support, $args ) {
	global $_wp_theme_features, $wp_current_filter;

	if ( false === $support ) {
		return false;
	}

	if ( empty( $args ) ) {
		return $support;
	}

	$filter = array_values( array_slice( $wp_current_filter, -1 ) )[0];

	if( false === strpos( $filter, 'current_theme_supports-' ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'This function must be hooked to the \'current_theme_supports-{$theme_feature}\' filter.', 'enlightenment' ), '1.0.0' );

		return;
	}

	$feature = str_replace( 'current_theme_supports-', '', $filter );

	if ( ! isset( $_wp_theme_features[ $feature ][0][ $args[0] ] ) ) {
		return false;
	}

	return $_wp_theme_features[ $feature ][0][ $args[0] ];
}

function enlightenment_current_template_file() {
	$files = get_included_files();
	return basename( end( $files ) );
}

function enlightenment_post_is_paged() {
	global $page;
	return is_singular() && isset( $page ) && 1 < $page;
}

function enlightenment_class( $class, $echo = false ) {
	if( '' == $class ) {
		$output = '';
	} else {
		$output = apply_filters( 'enlightenment_class', sprintf( ' class="%s"', trim( esc_attr( $class ) ) ) );
	}

	if( ! $echo ) {
		return $output;
	}

	echo $output;
}

function enlightenment_id( $id, $echo = false ) {
	if( '' == $id ) {
		$output = '';
	} else {
		$output = sprintf( ' id="%s"', esc_attr( $id ) );
	}

	if( ! $echo ) {
		return $output;
	}
	echo $output;
}

function enlightenment_attr_is_type( $attr, $type ) {
	if( 'data' != $type && 'aria' != $type ) {
		_doing_it_wrong( __FUNCTION__, __ ( "Second parameter can only be 'data' or 'aria'", 'enlightenment' ), '' );
		return;
	}

	$pos = strpos( $attr, $type . '-' );

	return ( false !== $pos && 0 === $pos );
}

function enlightenment_extra_atts( $atts, $echo = false ) {
	/*
	 * Attributes that load external resources like src are not safe,
	 * attributes that point to external resources like href are fine
	 * except inside tags that load the resources like <link>
	 */
	$safe_atts = apply_filters( 'enlightenment_safe_extra_atts',  array(
		'alt', 'title', 'href', 'rel', 'role', 'style', 'target', 'name',
		'type', 'value', 'checked', 'selected', 'multiple', 'placeholder',
		'width', 'height', 'action', 'method', 'enctype', 'itemscope',
		'itemprop', 'itemtype', 'lang',
	) );
	// data-* attributes
	$data_atts_allowed = apply_filters( 'enlightenment_data_atts_allowed', true );
	// aria-* attributes
	$aria_atts_allowed = apply_filters( 'enlightenment_aria_atts_allowed', true );

	if ( is_string( $atts ) ) {
		$atts = trim( $atts );

		$html = sprintf( '<span %s></span>', $atts );
		$atts = array();

		$dom = new DOMDocument();
		$dom->loadHTML( $html );

		$span = $dom->getElementsByTagName( 'span' )->item( 0 );
		if ( $span->hasAttributes() ) {
			foreach( $span->attributes as $attr ) {
				$atts[$attr->nodeName] = $attr->nodeValue;
			}
		}

	} elseif ( ! is_array( $atts ) ) {
		_doing_it_wrong( __FUNCTION__, __ ( 'First parameter must be string or associative array.', 'enlightenment' ), '' );
		return;
	}

	$output = '';

	foreach ( $atts as $attr => $value ) {
		if (
			in_array( $attr, $safe_atts )
			||
			( enlightenment_attr_is_type( $attr, 'data' ) && $data_atts_allowed )
			||
			( enlightenment_attr_is_type( $attr, 'aria' ) && $aria_atts_allowed )
		) {
			$output .= ' ' . esc_attr( $attr ) . ( '' !== $value ? '="' . esc_attr( $value ) . '"' : '' );
		}
	}

	$output = apply_filters( 'enlightenment_extra_atts', $output, $atts );

	if( ! $echo ) {
		return $output;
	}
	echo $output;
}

function enlightenment_extra_attr( $attr, $echo = false ) {
	return enlightenment_extra_atts( $attr, $echo );
}

function enlightenment_open_tag( $container = 'div', $class = '', $id = '', $extra = '' ) {
	$container = esc_attr( $container );
	$class     = enlightenment_class( $class );
	$id        = enlightenment_id( $id );
	$extra     = enlightenment_extra_atts( $extra );
	$output    = '';

	if( '' != $container ) {
		$format = apply_filters( 'enlightenment_open_tag_format', '<%1$s%2$s%3$s%4$s>' . "\n", $container, $class, $id, $extra );
		$output = sprintf( $format, $container, $class, $id, $extra );
	}

	return apply_filters( 'enlightenment_open_tag', $output, $container, $id, $class, $extra );
}

function enlightenment_close_tag( $container = 'div' ) {
	$output = '';

	if( ! empty( $container ) ) {
		$output = sprintf( "</%s>\n", $container );
	}

	return apply_filters( 'enlightenment_close_tag', $output, $container );
}

function enlightenment_close_div() {
	echo enlightenment_close_tag( 'div' );
}

function enlightenment_get_available_fonts() {
	$fonts = array(
		'System'         => array(
			'category' => 'sans-serif',
			'family'   => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Oxygen-Sans, Oxygen, Ubuntu, Cantarell',
		),
		'Helvetica Neue' => array(
			'category' => 'sans-serif',
			'family'   => '"Helvetica Neue", Helvetica, "Nimbus Sans L"',
		),
		'Geneva'         => array(
			'category' => 'sans-serif',
			'family'   => 'Geneva, Verdana, "DejaVu Sans"',
		),
		'Tahoma'         => array(
			'category' => 'sans-serif',
			'family'   => 'Tahoma, "DejaVu Sans"',
		),
		'Trebuchet MS'   => array(
			'category' => 'sans-serif',
			'family'   => '"Trebuchet MS", "Bitstream Vera Sans"',
		),
		'Lucida Grande'  => array(
			'category' => 'sans-serif',
			'family'   => '"Lucida Grande", "Lucida Sans Unicode", "Bitstream Vera Sans"',
		),
		'Georgia'        => array(
			'category' => 'serif',
			'family'   => 'Georgia, "URW Bookman L"',
		),
		'Times'          => array(
			'category' => 'serif',
			'family'   => 'Times, "Times New Roman", "Century Schoolbook L"',
		),
		'Palatino'       => array(
			'category' => 'serif',
			'family'   => 'Palatino, "Palatino Linotype", "URW Palladio L"',
		),
		'Courier'        => array(
			'category' => 'monospace',
			'family'   => 'Courier, "Courier New", "Nimbus Mono L"',
		),
		'SF Mono'        => array(
			'category' => 'monospace',
			'family'   => 'SFMono-Regular, Menlo, Monaco, Consolas, "Lucida Console", "Bitstream Vera Sans Mono"',
		),
	);

	return apply_filters( 'enlightenment_available_fonts', $fonts );
}

function enlightenment_get_font_variants() {
	return apply_filters( 'enlightenment_font_variants', array(
		'300'       => __( 'Light',            'enlightenment' ),
		'300italic' => __( 'Light Italic',     'enlightenment' ),
		'400'       => __( 'Regular',          'enlightenment' ),
		'italic'    => __( 'Italic',           'enlightenment' ),
		'500'       => __( 'Medium',           'enlightenment' ),
		'500italic' => __( 'Medium Italic',    'enlightenment' ),
		'600'       => __( 'Semi-Bold',        'enlightenment' ),
		'600italic' => __( 'Semi-Bold Italic', 'enlightenment' ),
		'700'       => __( 'Bold',             'enlightenment' ),
		'700italic' => __( 'Bold Italic',      'enlightenment' ),
	) );
}

function enlightenment_current_sidebar_name() {
	$files = array_reverse( get_included_files() );

	foreach ( $files as $file ) {
		if ( false !== strpos( $file, 'sidebar') ) {
			$sidebar = basename( $file );
			break;
		}
	}

	if ( 'sidebar.php' == $sidebar ) {
		$sidebar = 'primary';
	} else {
		$sidebar = str_replace( 'sidebar-', '', $sidebar );
		$sidebar = str_replace( '.php', '', $sidebar );
	}

	return apply_filters( 'enlightenment_current_sidebar_name', $sidebar );
}

function enlightenment_ob_start() {
	ob_start();
}

function enlightenment_can_edit_post_type( $post_type = null ) {
	if ( empty( $post_type ) ) {
		if ( isset( $GLOBALS['post'] ) && $GLOBALS['post'] instanceof WP_Post ) {
			$post = $GLOBALS['post'];
		} elseif ( isset( $GLOBALS['id'] ) ) {
			$post = get_post( $GLOBALS['id'] );

			if ( ! $post instanceof WP_Post ) {
				return false;
			}
		} else {
			return false;
		}

		$post_type = $post->post_type;
	}

	return current_user_can( get_post_type_object( $post_type )->cap->edit_posts );
}

function enlightenment_get_current_uri( $args = null ) {
	$defaults = array(
		'format'        => '%1$s://%2$s%3$s',
		'http_protocol' => is_ssl() ? 'https' : 'http',
		'http_host'     => isset( $_SERVER['HTTP_HOST'] )   ? $_SERVER['HTTP_HOST']   : '',
		'request_uri'   => isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '',
		'params'        => array(),
	);
	$defaults = apply_filters( 'enlightenment_get_current_uri_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	return apply_filters(
		'enlightenment_get_current_uri',
		add_query_arg( $args['params'], sprintf(
			$args['format'],
			$args['http_protocol'],
			$args['http_host'],
			$args['request_uri']
		) ),
		$args
	);
}

function enlightenment_has_in_call_stack( $checks = array(), $operator = 'AND' ) {
	if ( empty( $checks ) ) {
		// Nothing to check
		return false;
	}

	// If a single function name was passed, convert it to an array
	if ( is_string( $checks ) ) {
		$checks = array(
			array(
				'value' => $checks,
			),
		);
	}

	if ( ! is_array( $checks ) ) {
		// Invalid value for $checks
		return false;
	}

	// When an array of type [class, function] is passed, convert it to an acceptable check format
	if (
		$operator == 'AND'
		&&
		2 == count( $checks )
		&&
		! empty( $checks[0] )
		&&
		! empty( $checks[1] )
		&&
		( is_callable( $checks ) || is_string( $checks[0] ) || is_string( $checks[1] ) )
	) {
		$checks = array(
			array(
				'key'   => 'class',
				'value' => is_string( $checks[0] ) ? $checks[0] : get_class( $checks[0] ),
			),
			array(
				'key'   => 'function',
				'value' => $checks[1],
			),
		);
	}

	// Validate checks
	foreach ( $checks as $key => $check ) {
		// Default property 'key' to 'function'
		if ( ! isset( $check['key'] ) || ! is_string( $check['key'] ) ) {
			$check['key'] = 'function';
		}

		// Convert property 'key' to lower case
		$check['key'] = strtolower( $check['key'] );

		// Property 'key' can only be 'function' or 'class'
		if ( ! in_array( $check['key'], array( 'function', 'class' ) ) ) {
			$check['key'] = 'function';
		}

		// Verify that 'value' property exists
		if ( ! isset( $check['value'] ) ) {
			unset( $checks[ $key ] );
			continue;
		}

		// Convert objects passes as value to their corresponding class
		if ( 'class' == $check['key'] && is_object( $check['value'] ) ) {
			$check['value'] = get_class( $check['value'] );
		}

		// Verify that 'value' property is a string
		if ( ! is_string( $check['value'] ) ) {
			unset( $checks[ $key ] );
			continue;
		}

		// Verify that 'value' isn't empty
		if ( empty( $check['value'] ) ) {
			unset( $checks[ $key ] );
			continue;
		}

		// Convert property 'value' to lower case
		$check['value'] = strtolower( $check['value'] );

		// Default property 'compare' to 'EQUALS'
		if ( ! isset( $check['compare'] ) || ! is_string( $check['compare'] ) ) {
			$check['compare'] = 'EQUALS';
		}

		// Convert property 'compare' to upper case
		$check['compare'] = strtoupper( $check['compare'] );

		// Property 'compare' can only be the following
		if ( ! in_array( $check['compare'], array( 'EQUALS', 'STARTS_WITH', 'CONTAINS', 'ENDS_WITH' ) ) ) {
			$check['compare'] = 'EQUALS';
		}

		// Assign the valid check to the original checks array
		$checks[ $key ] = $check;
	}

	if ( empty( $checks ) ) {
		// All checks are invalid
		return false;
	}

	// Default $operator to 'AND'
	if ( empty( $operator ) || ! is_string( $operator ) ) {
		$operator = 'AND';
	}

	// Convert $operator to upper case
	$operator = strtoupper( $operator );

	// $operator can only be 'AND' or 'OR'
	if ( ! in_array( $operator, array( 'AND', 'OR' ) ) ) {
		$operator = 'AND';
	}

	// Get the callback stack trace
	$stack = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );

	// The first frame will always be this function, which we are ignoring
	array_shift($stack);

	switch ( $operator ) {
		// Test the stacj against each check
		case 'AND':
			// Loop through each stack frame
			foreach ( $stack as $key => $frame ) {
				// Assume all checks will fail
				$pass = false;

				// Perform checks
				foreach ( $checks as $check ) {
					if ( ! isset( $frame[ $check['key'] ] ) ) {
						// The key to check against doesn't exist on this frame
						break;
					}

					// Assume the check will fail
					$pass = false;

					// Convert the value to check against to lower case
					$value = strtolower( $frame[ $check['key'] ] );

					switch ( $check['compare'] ) {
						case 'EQUALS':
							// Value is equal to
							if ( $value == $check['value'] ) {
								$pass = $key;
							}

							break;

						case 'STARTS_WITH':
							// Value starts with
							if ( 0 === strpos( $value, $check['value'] ) ) {
								$pass = $key;
							}

							break;

						case 'CONTAINS':
							// Value contains
							if ( false !== strpos( $value, $check['value'] ) ) {
								$pass = $key;
							}

							break;

						case 'ENDS_WITH':
							// Value ends with
							if ( function_exists( 'str_ends_with' ) && str_ends_with( $value, $check['value'] ) ) {
								// PHP 8
								$pass = $key;
							} elseif ( 0 === substr_compare( $value, $check['value'], strlen( $check['value'] ) * -1 ) ) {
								// PHP 5, PHP 7
								$pass = $key;
							}

							break;
					}

					if ( false === $pass ) {
						// Bail as soon as a check fails
						break;
					}
				}

				if ( $pass ) {
					// All checks passed, return the key
					return $pass;
				}
			}

			// Checks failed on all stack frames
			return false;

		// Return the stack key as soon as a check passes
		case 'OR':
			// Loop through each stack frame
			foreach ( $stack as $key => $frame ) {
				// Perform checks
				foreach ( $checks as $check ) {
					if ( ! isset( $frame[ $check['key'] ] ) ) {
						// The key to check against doesn't exist on this frame
						continue;
					}

					// Convert the value to check against to lower case
					$value = strtolower( $frame[ $check['key'] ] );

					switch ( $check['compare'] ) {
						case 'EQUALS':
							// Value is equal to
							if ( $value == $check['value'] ) {
								return $key;
							}

							break;

						case 'STARTS_WITH':
							// Value starts with
							if ( 0 === strpos( $value, $check['value'] ) ) {
								return $key;
							}

							break;

						case 'CONTAINS':
							// Value contains
							if ( false !== strpos( $value, $check['value'] ) ) {
								return $key;
							}

							break;

						case 'ENDS_WITH':
							// Value ends with
							if ( function_exists( 'str_ends_with' ) && str_ends_with( $value, $check['value'] ) ) {
								// PHP 8
								return $key;
							} elseif ( 0 === substr_compare( $value, $check['value'], strlen( $check['value'] ) * -1 ) ) {
								// PHP 5, PHP 7
								return $key;
							}

							break;
					}
				}

				// All checks failed
				return false;
			}
	}
}

function enlightenment_hex2rgb( $value, $args = null ) {
	$args = wp_parse_args( $args, array(
		'format'  => 'auto',
		'opacity' => 1,
		'wrapper' => true,
	) );

	$output = apply_filters( 'enlightenment_pre_hex2rgb', $value, $args );

	if ( $output !== $value ) {
		return $output;
	}

	$hex = $value;

	if ( 0 < strlen( $hex ) && '#' == $hex[0] ) {
		$hex = substr( $hex, 1 );
	}

	if ( 3 == strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	if ( 6 == strlen( $hex ) && ctype_xdigit( $hex ) ) {
		list( $r, $g, $b ) = sscanf( $hex, '%02x%02x%02x' );

		if ( 'rgb' == $args['format'] ) {
			$output = sprintf( '%d, %d, %d', $r, $g, $b );

			if ( $args['wrapper'] ) {
				$output = sprintf( 'rgb(%s)', $output );
			}
		} else {
			$a = abs( $args['opacity'] );

			if ( 100 < $a ) {
				$a = 1;
			} elseif ( 1 < $a ) {
				$a = round( $a / 100, 2 );
			}

			if ( 'rgba' == $args['format'] || $a && 1 > $a ) {
				$output = sprintf( '%d, %d, %d, %g', $r, $g, $b, $a );

				if ( $args['wrapper'] ) {
					$output = sprintf( 'rgba(%s)', $output );
				}
			} else {
				$output = sprintf( '%d, %d, %d', $r, $g, $b );

				if ( $args['wrapper'] ) {
					$output = sprintf( 'rgb(%s)', $output );
				}
			}
		}
	}

	return apply_filters( 'enlightenment_hex2rgb', $output, $value, $args );
}
