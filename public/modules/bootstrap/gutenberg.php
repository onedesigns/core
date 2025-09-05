<?php

function enlightenment_bootstrap_enqueue_block_editor_assets() {
	if ( ! is_admin() ) {
		return;
	}

	wp_enqueue_style( 'enlightenment-bootstrap-block-editor', enlightenment_styles_directory_uri() . '/editor-style.css' );
}
add_action( 'enqueue_block_assets', 'enlightenment_bootstrap_enqueue_block_editor_assets' );

function enlightenment_bootstrap_cover_block( $output, $block ) {
	if ( isset( $block['attrs']['align'] ) && 'full' == $block['attrs']['align'] ) {
		$output = str_replace( 'class="wp-block-cover ', 'class="wp-block-cover align-items-stretch justify-content-start ', $output );
		$class = 'align-items-center justify-content-center';

		if ( isset( $block['attrs']['contentPosition'] ) ) {
			switch( $block['attrs']['contentPosition'] ) {
				case 'top left':
					$class = 'align-items-start justify-content-start';
					break;

				case 'top center':
					$class = 'align-items-start justify-content-center';
					break;

				case 'top right':
					$class = 'align-items-start justify-content-end';
					break;

				case 'center left':
					$class = 'align-items-center justify-content-start';
					break;

				case 'center center':
					$class = 'align-items-center justify-content-center';
					break;

				case 'center right':
					$class = 'align-items-center justify-content-end';
					break;

				case 'bottom left':
					$class = 'align-items-end justify-content-start';
					break;

				case 'bottom center':
					$class = 'align-items-end justify-content-center';
					break;

				case 'bottom right':
					$class = 'align-items-end justify-content-end';
					break;
			}
		}

		$offset = strpos( $output, 'class="wp-block-cover__inner-container ' );
		if ( false === $offset ) {
			$offset = strpos( $output, 'class="wp-block-cover__inner-container"' );
		}
		if ( false !== $offset ) {
			$output  = substr_replace( $output, ' position-relative d-flex mw-100 w-100', $offset + 38, 0 );
			$offset  = strpos( $output, '"', $offset + 7 );
			$output  = substr_replace( $output, ' height="100%"', $offset + 1, 0 );
			$offset  = strpos( $output, '>', $offset );
			$output  = substr_replace( $output, sprintf( '<div class="container d-flex %s"><div class="wp-block-cover__inner-wrapper">', $class ), $offset + 1, 0 );
			$output .= '</div></div>';
		}
	}

	$output = str_replace( 'class="wp-block-button btn-secondary ', 'class="wp-block-button btn-light ', $output );
	$output = str_replace( 'class="wp-block-button btn-secondary"', 'class="wp-block-button btn-light"', $output );
	$output = str_replace( 'class="btn btn-secondary ', 'class="btn btn-light ', $output );
	$output = str_replace( 'class="btn btn-secondary"', 'class="btn btn-light"', $output );
	$output = str_replace( 'class="wp-block-button btn-outline-secondary ', 'class="wp-block-button btn-outline-light ', $output );
	$output = str_replace( 'class="wp-block-button btn-outline-secondary"', 'class="wp-block-button btn-outline-light"', $output );
	$output = str_replace( 'class="btn btn-outline-secondary ', 'class="btn btn-outline-light ', $output );
	$output = str_replace( 'class="btn btn-outline-secondary"', 'class="btn btn-outline-light"', $output );

    return $output;
}
add_filter( 'enlightenment_render_block_core_cover', 'enlightenment_bootstrap_cover_block', 10, 2 );

function enlightenment_bootstrap_table_block( $output ) {
    $stripes = '';

    if ( false !== strpos( $output, ' is-style-stripes' ) ) {
        $stripes = ' table-striped';
        $output  = str_replace( ' is-style-stripes', '', $output );
    }

	/**
	 * Prevent default styles being applied to the table.
	 */
	$output = str_replace( 'class="wp-block-table ', 'class="wp-block-bs-table ', $output );
	$output = str_replace( 'class="wp-block-table"', 'class="wp-block-bs-table"', $output );

    $output = str_replace( '<table class="', sprintf( '<div class="table-responsive"><table class="table%s ', $stripes ), $output );
    $output = str_replace( '<table>', sprintf( '<div class="table-responsive"><table class="table%s">', $stripes ), $output );
    $output = str_replace( '</table>', '</table></div>', $output );

    return $output;
}
add_filter( 'enlightenment_render_block_core_table', 'enlightenment_bootstrap_table_block' );

function enlightenment_bootstrap_button_block( $output, $block ) {
    if ( isset( $block['attrs'] ) && isset( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'is-style-outline' ) ) {
		$offset = strpos( $output, 'class="wp-block-button ' );
		$end    = strpos( $output, '"', $offset + 7 );
		$offset = strpos( $output, ' wp-block-button__link', $offset );

		if ( false !== $offset && $offset < $end ) {
			$output = str_replace( 'class="wp-block-button ', 'class="btn btn-outline-secondary ', $output );
			$output = str_replace( ' wp-block-button__link', '', $output );

			if ( isset( $block['attrs'] ) && isset( $block['attrs']['textColor'] ) ) {
	            $offset_a = strpos( $output, 'style="', $offset );
		        $end      = strpos( $output, '>', $offset );

	            if ( false !== $offset_a && $offset_a < $end ) {
	                $output = substr_replace( $output, 'border-color:initial;', $offset_a + 7, 0 );
	            } else {
					$offset = strpos( $output, '"', $offset );
	                $output = substr_replace( $output, ' style="border-color:initial;"', $offset + 1, 0 );
	            }
	        }
		} else {
			$output = str_replace( ' is-style-outline ', ' ', $output );
			$output = str_replace( ' is-style-outline"', '"', $output );

	        $offset   = strpos( $output, 'class="wp-block-button' );
	        $end      = strpos( $output, '>', $offset );
	        $offset_a = strpos( $output, '"', $offset + 7 );

	        if ( false !== $offset_a && $offset_a < $end ) {
	            $output = substr_replace( $output, ' btn-outline-secondary" style="border-width:0;background-color:transparent;"', $offset_a, 1 );
	            $end    = strpos( $output, '</div>', $offset );
	        }

	        $offset_b = strpos( $output, 'class="wp-block-button__link ', $offset );

	        if ( false !== $offset_b && $offset_b < $end ) {
	            $output = substr_replace( $output, 'btn btn-outline-secondary', $offset_b + 7, 21 );
	            $end    = strpos( $output, '</div>', $offset );
	        }

	        $offset_c = strpos( $output, 'class="wp-block-button__link"', $offset );

	        if ( false !== $offset_c && $offset_c < $end ) {
	            $output = substr_replace( $output, 'btn btn-outline-secondary', $offset_c + 7, 21 );
	            $end    = strpos( $output, '</div>', $offset );
	        }
		}
    } else {
		$offset = strpos( $output, 'class="wp-block-button ' );

		if ( false === $offset ) {
			$offset = strpos( $output, 'class="wp-block-button"' );
		}

		if ( false !== $offset ) {
			$end    = strpos( $output, '"', $offset );
			$end    = strpos( $output, '"', $end + 1 );
			$offset = strpos( $output, ' wp-block-button__link', $offset );

			if ( false !== $offset && $offset < $end ) {
				$output = str_replace( 'class="wp-block-button ', 'class="btn btn-secondary ', $output );
				$output = str_replace( ' wp-block-button__link', '', $output );

				if ( isset( $block['attrs'] ) && isset( $block['attrs']['backgroundColor'] ) ) {
		            $offset_a = strpos( $output, 'style="', $offset );
			        $end      = strpos( $output, '>', $offset );

		            if ( false !== $offset_a && $offset_a < $end ) {
		                $output = substr_replace( $output, 'border-color:transparent;', $offset_a + 7, 0 );
		            } else {
						$offset = strpos( $output, '"', $offset );
		                $output = substr_replace( $output, ' style="border-color:transparent;"', $offset + 1, 0 );
		            }
		        }
			} else {
		        $offset   = strpos( $output, 'class="wp-block-button' );
		        $end      = strpos( $output, '>', $offset );
		        $offset_a = strpos( $output, '"', $offset );
		        $offset_a = strpos( $output, '"', $offset_a + 1 );

		        if ( false !== $offset_a && $offset_a < $end ) {
		            $output = substr_replace( $output, ' btn-secondary" style="border-width:0;background-color:transparent;"', $offset_a, 1 );
		        }

		        $offset_b = strpos( $output, 'class="wp-block-button__link ', $offset );
		        $end      = strpos( $output, '</div>', $offset );

		        if ( false !== $offset_b && $offset_b < $end ) {
		            $output = substr_replace( $output, 'btn btn-secondary', $offset_b + 7, 21 );
		        }

		        $offset_c = strpos( $output, 'class="wp-block-button__link"', $offset );
		        $end      = strpos( $output, '</div>', $offset );

		        if ( false !== $offset_c && $offset_c < $end ) {
		            $output = substr_replace( $output, 'btn btn-secondary', $offset_c + 7, 21 );
		        }

				if ( isset( $block['attrs'] ) && isset( $block['attrs']['backgroundColor'] ) ) {
					$offset_d = strpos( $output, ' has-background', $offset );
		            $offset_d = strpos( $output, '"', $offset_d );
		            $offset_e = strpos( $output, 'style="', $offset_d );
			        $end      = strpos( $output, '</div>', $offset );

		            if ( false !== $offset_e && $offset_e < $end ) {
		                $output = substr_replace( $output, 'border-color:transparent;', $offset_e + 7, 0 );
		            } else {
		                $output = substr_replace( $output, ' style="border-color:transparent;"', $offset_d + 1, 0 );
		            }
		        }
			}
	    }
	}

    return $output;
}
add_filter( 'enlightenment_render_block_core_button', 'enlightenment_bootstrap_button_block', 10, 2 );

function enlightenment_bootstrap_buttons_block( $output, $block ) {
    $offset = strpos( $output, 'class="wp-block-buttons"' );

    if ( false === $offset ) {
        $offset = strpos( $output, 'class="wp-block-buttons ' );
    }

    if ( false === $offset ) {
        $offset = strpos( $output, ' wp-block-buttons ' );
    }

    if ( false === $offset ) {
        $offset = strpos( $output, ' wp-block-buttons"' );
    }

    if ( false !== $offset ) {
        $offset  = strpos( $output, '"', $offset + 7 );

        if ( isset( $block['attrs'] ) && isset( $block['attrs']['align'] ) && 'center' == $block['attrs']['align'] ) {
            $output = substr_replace( $output, ' justify-content-center', $offset, 0 );
        }

        $output  = substr_replace( $output, ' d-flex', $offset, 0 );
        $offset  = strpos( $output, '>', $offset );
        $output  = substr_replace( $output, '<div class="d-flex flex-wrap align-items-center">', $offset + 1, 0 );
        $output .= '</div>';
    }

    return $output;
}
add_filter( 'enlightenment_render_block_core_buttons', 'enlightenment_bootstrap_buttons_block', 10, 2 );

function enlightenment_bootstrap_file_block( $output ) {
    $output = str_replace( 'class="wp-block-file__button ', 'class="btn btn-primary btn-lg ms-2 ', $output );
	$output = str_replace( 'class="wp-block-file__button"', 'class="btn btn-primary btn-lg ms-2"', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_core_file', 'enlightenment_bootstrap_file_block' );

function enlightenment_bootstrap_columns_block( $output, $block ) {
	$class = 'row text-break';

	if ( isset( $block['attrs'] ) && isset( $block['attrs']['isStackedOnMobile'] ) && false === $block['attrs']['isStackedOnMobile'] ) {
		$class .= ' flex-nowrap';
	}

	if ( isset( $block['attrs'] ) && isset( $block['attrs']['verticalAlignment'] ) ) {
		switch ( $block['attrs']['verticalAlignment'] ) {
			case 'top':
				$class .= ' align-items-start';
				break;

			case 'center':
				$class .= ' align-items-center';
				break;

			case 'bottom':
				$class .= ' align-items-end';
				break;
		}
	}

    $offset = strpos( $output, 'class="wp-block-columns"' );

    if ( false === $offset ) {
        $offset = strpos( $output, 'class="wp-block-columns ' );
    }

    if ( false === $offset ) {
        $offset = strpos( $output, ' wp-block-columns ' );
    }

    if ( false === $offset ) {
        $offset = strpos( $output, ' wp-block-columns"' );
    }

    if ( false !== $offset ) {
        $offset  = strpos( $output, '>', $offset );
        $output  = substr_replace( $output, "\n" . '<div class="w-100">', $offset + 1, 0 );
        $offset  = strpos( $output, '<div class="w-100">', $offset );
		$output  = substr_replace( $output, "\n" . sprintf( '<div class="%s">', $class ), $offset + 19, 0 );
        $output .= "\n" . '</div>' . "\n" . '</div>';
    }

	if ( isset( $block['attrs'] ) && isset( $block['attrs']['isStackedOnMobile'] ) && false === $block['attrs']['isStackedOnMobile'] ) {
		$output = str_replace( 'class="col-md-6 col-lg ', 'class="col ', $output );
		$output = str_replace( 'class="col-md-6 col-lg"', 'class="col"', $output );
		$output = str_replace( ' col-md-6 col-lg ', ' col ', $output );
		$output = str_replace( ' col-md-6 col-lg"', ' col"', $output );
	} else {
		$count = ( isset( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) ? count( $block['innerBlocks'] ) : 0;

		switch ( $count ) {
			case 1:
				$output = str_replace( 'class="col-md-6 col-lg ', 'class="col-lg-12 ', $output );
				$output = str_replace( 'class="col-md-6 col-lg"', 'class="col-lg-12"', $output );
				$output = str_replace( ' col-md-6 col-lg ', ' col-lg-12 ', $output );
				$output = str_replace( ' col-md-6 col-lg"', ' col-lg-12"', $output );
				break;

			case 2:
			case 4:
			case 8:
			case 10:
				$output = str_replace( 'class="col-md-6 col-lg ', 'class="col-sm-6 col-lg ', $output );
				$output = str_replace( 'class="col-md-6 col-lg"', 'class="col-sm-6 col-lg"', $output );
				$output = str_replace( ' col-md-6 col-lg ', ' col-sm-6 col-lg ', $output );
				$output = str_replace( ' col-md-6 col-lg"', ' col-sm-6 col-lg"', $output );
				break;

			case 3:
			case 9:
				$output = str_replace( 'class="col-md-6 col-lg ', 'class="col-md-4 col-lg ', $output );
				$output = str_replace( 'class="col-md-6 col-lg"', 'class="col-md-4 col-lg"', $output );
				$output = str_replace( ' col-md-6 col-lg ', ' col-md-4 col-lg ', $output );
				$output = str_replace( ' col-md-6 col-lg"', ' col-md-4 col-lg"', $output );
				break;

			case 5:
			case 7:
			case 11:
				$output = str_replace( 'class="col-md-6 col-lg ', ' col-sm-6 col-md-4 col-lg flex-sm-grow-1 flex-sm-shrink-0 mw-100 ', $output );
				$output = str_replace( 'class="col-md-6 col-lg"', ' col-sm-6 col-md-4 col-lg flex-sm-grow-1 flex-sm-shrink-0 mw-100"', $output );
				$output = str_replace( ' col-md-6 col-lg ', ' col-sm-6 col-md-4 col-lg flex-sm-grow-1 flex-sm-shrink-0 mw-100 ', $output );
				$output = str_replace( ' col-md-6 col-lg"', ' col-sm-6 col-md-4 col-lg flex-sm-grow-1 flex-sm-shrink-0 mw-100"', $output );
				break;

			case 6:
			case 12:
				$output = str_replace( 'class="col-md-6 col-lg ', 'class="col-sm-6 col-md-4 col-lg ', $output );
				$output = str_replace( 'class="col-md-6 col-lg"', 'class="col-sm-6 col-md-4 col-lg"', $output );
				$output = str_replace( ' col-md-6 col-lg ', ' col-sm-6 col-md-4 col-lg ', $output );
				$output = str_replace( ' col-md-6 col-lg"', ' col-sm-6 col-md-4 col-lg"', $output );
				break;
		}
	}

    return $output;
}
add_filter( 'enlightenment_render_block_core_columns', 'enlightenment_bootstrap_columns_block', 10, 2 );

function enlightenment_bootstrap_column_block( $output, $block ) {
	if ( isset( $block['attrs'] ) && isset( $block['attrs']['verticalAlignment'] ) ) {
		switch ( $block['attrs']['verticalAlignment'] ) {
			case 'top':
				$output = str_replace( 'class="wp-block-column is-vertically-aligned-top ', 'class="col-md-6 col-lg align-self-start ', $output );
				$output = str_replace( 'class="wp-block-column is-vertically-aligned-top"', 'class="col-md-6 col-lg align-self-start"', $output );

				$output = str_replace( ' wp-block-column is-vertically-aligned-top ', ' col-md-6 col-lg align-self-start ', $output );
				$output = str_replace( ' wp-block-column is-vertically-aligned-top"', ' col-md-6 col-lg align-self-start"', $output );

				break;

			case 'center':
				$output = str_replace( 'class="wp-block-column is-vertically-aligned-center ', 'class="col-md-6 col-lg align-self-center ', $output );
				$output = str_replace( 'class="wp-block-column is-vertically-aligned-center"', 'class="col-md-6 col-lg align-self-center"', $output );

				$output = str_replace( ' wp-block-column is-vertically-aligned-center ', ' col-md-6 col-lg align-self-center ', $output );
				$output = str_replace( ' wp-block-column is-vertically-aligned-center"', ' col-md-6 col-lg align-self-center"', $output );

				break;

			case 'bottom':
				$output = str_replace( 'class="wp-block-column is-vertically-aligned-bottom ', 'class="col-md-6 col-lg align-self-end ', $output );
				$output = str_replace( 'class="wp-block-column is-vertically-aligned-bottom"', 'class="col-md-6 col-lg align-self-end"', $output );

				$output = str_replace( ' wp-block-column is-vertically-aligned-bottom ', ' col-md-6 col-lg align-self-end ', $output );
				$output = str_replace( ' wp-block-column is-vertically-aligned-bottom"', ' col-md-6 col-lg align-self-end"', $output );

				break;
		}
	} else {
		$output = str_replace( 'class="wp-block-column ', 'class="col-md-6 col-lg ', $output );
		$output = str_replace( 'class="wp-block-column"', 'class="col-md-6 col-lg"', $output );
		$output = str_replace( ' wp-block-column ', ' col-md-6 col-lg ', $output );
		$output = str_replace( ' wp-block-column"', ' col-md-6 col-lg"', $output );
	}

	$start  = strpos( $output, 'col-md-6 col-lg' );
	while ( false !== $start ) {
		$start  = strpos( $output, '"', $start );
		$offset = strpos( $output, ' style="', $start );
		$end    = strpos( $output, '>', $start );
		if ( false !== $offset && $offset < $end ) {
			$end_a    = strpos( $output, '"', $offset + 8 );
			$offset_a = strpos( $output, 'flex-basis:', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$offset_b = $offset_a + 11;
				$end_b    = strpos( $output, ';', $offset_a );
				if ( false === $end_b || $end_b > $end_a ) {
					$end_b = $end_a;
				}
				$length_b   = $end_b - $offset_b;
				$flex_basis = substr( $output, $offset_b, $length_b );
				$unit       = trim( preg_replace( '!\d+(?:\.\d+)?!', '', $flex_basis ) );

				$output = substr_replace( $output, ' flex-lg-grow-0 flex-lg-shrink-1', $start, 0 );

				if ( '%' != $unit && 'vw' != $unit ) {
					$offset = strpos( $output, 'flex-basis:', $start );
					$output = substr_replace( $output, sprintf( 'calc(%s + var(--bs-gutter-x))', trim( $flex_basis ) ), $offset + 11, strlen( $flex_basis ) );
					$output = substr_replace( $output, sprintf( 'max-width: calc(%s + var(--bs-gutter-x));', trim( $flex_basis ) ), $offset, 0 );
				}
			}
		}

		$start  = strpos( $output, 'col-md-6 col-lg', $start );
	}

    return $output;
}
add_filter( 'enlightenment_render_block_core_column', 'enlightenment_bootstrap_column_block', 10, 2 );

function enlightenment_bootstrap_navigation_block( $output, $block ) {
	if (
		isset( $block['attrs']['layout'] )
		&&
		isset( $block['attrs']['layout']['orientation'] )
		&&
		'vertical' == $block['attrs']['layout']['orientation']
	) {
		$output = str_replace( 'class="wp-block-navigation__container ', 'class="wp-block-navigation__container nav d-flex flex-column gap-0 w-100 ', $output );
		$output = str_replace( 'class="wp-block-navigation__container"', 'class="wp-block-navigation__container nav d-flex flex-column gap-0 w-100"', $output );
	} else {
		$class = 'nav gap-0 w-100 position-static';

		if ( isset( $block['attrs']['layout']['flexWrap'] ) && 'nowrap' == $block['attrs']['layout']['flexWrap'] ) {
			$class .= ' flex-nowrap text-nowrap';

			$output = str_replace( 'class="wp-block-navigation__responsive-container-content"', 'class="wp-block-navigation__responsive-container-content overflow-auto"', $output );
			$output = str_replace( ' wp-block-navigation-submenu nav-item dropdown"', ' wp-block-navigation-submenu nav-item dropdown position-static"', $output );
		}

		$output = str_replace( 'class="wp-block-navigation__container ', sprintf( 'class="wp-block-navigation__container %s ', esc_attr( $class ) ), $output );
		$output = str_replace( 'class="wp-block-navigation__container"', sprintf( 'class="wp-block-navigation__container %s"', esc_attr( $class ) ), $output );
	}

    return $output;
}
add_filter( 'enlightenment_render_block_core_navigation', 'enlightenment_bootstrap_navigation_block', 10, 2 );

function enlightenment_bootstrap_navigation_submenu_block( $output ) {
	$offset = strpos( $output, ' wp-block-navigation-submenu"' );
	$end    = strpos( $output, '>' );
	if ( false === $offset || $offset > $end ) {
		$offset = strpos( $output, ' wp-block-navigation-submenu ' );
	}
	if ( false !== $offset && $offset < $end ) {
		$output = substr_replace( $output, ' nav-item dropdown', $offset + 28, 0 );

		$offset_a = strpos( $output, 'class="wp-block-navigation-item__content"', $offset );
		$end_a    = strpos( $output, '<ul ', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output   = substr_replace( $output, ' nav-link', $offset_a + 40, 0 );

			$offset_a = strpos( $output, ' wp-block-navigation-submenu__toggle"', $offset_a );
			$end_a    = strpos( $output, '<ul ', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output = substr_replace( $output, ' data-bs-toggle="dropdown"', $offset_a + 37, 0 );
				$output = substr_replace( $output, ' nav-link dropdown-toggle dropdown-toggle-split', $offset_a + 36, 0 );
			}
		} else {
			$offset_a = strpos( $output, 'class="wp-block-navigation-item__content ', $offset );
			$end_a    = strpos( $output, '<ul ', $offset_a );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output   = substr_replace( $output, ' nav-link', $offset_a + 40, 0 );
				$offset_a = strpos( $output, ' wp-block-navigation-submenu__toggle"', $offset_a );
				$output   = substr_replace( $output, ' data-bs-toggle="dropdown"', $offset_a + 37, 0 );
				$output   = substr_replace( $output, ' dropdown-toggle', $offset_a + 36, 0 );
			}
		}
	}

	$start = strpos( $output, '<svg ' );
	while ( false !== $start ) {
		$end    = strpos( $output, '</svg>', $start ) + 6;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );

		$start  = strpos( $output, '<svg ' );
	}

	$output = str_replace( '<span class="wp-block-navigation__submenu-icon"></span>', '', $output );

	$haystack = 'class="wp-block-navigation__submenu-container ';
	$offset   = strpos( $output, $haystack );
	if ( false === $offset ) {
		$haystack = 'class="wp-block-navigation__submenu-container"';
		$offset = strpos( $output, $haystack );
	}
	if ( false !== $offset ) {
		$offset_a = $offset;
		while ( false !== $offset_a ) {
			$offset_a += 7;

			if ( false === strpos( $output, ' open-on-click ' ) ) {
				$output = substr_replace( $output, ' dropdown-menu dropdown-menu-end py-2 border-0', $offset_a + 38, 0 );
				// $output = substr_replace( $output, 'dropdown-menu dropdown-menu-end py-2 border-0', $offset_a, 38 );
			} else {
				$output = substr_replace( $output, 'dropdown-menu dropdown-menu-end py-2 border-0', $offset_a, 38 );
			}

			$offset_a = strpos( $output, $haystack, $offset_a );
		}

		$offset_a = strpos( $output, ' wp-block-navigation-link nav-item"', $offset );
		while ( false !== $offset_a ) {
			$output   = substr_replace( $output, '', $offset_a + 25, 9 );
			$offset_a = strpos( $output, ' wp-block-navigation-link nav-item"', $offset_a );
		}

		$offset_a = strpos( $output, ' wp-block-navigation-submenu nav-item"', $offset );
		while ( false !== $offset_a ) {
			$output   = substr_replace( $output, '', $offset_a + 28, 9 );
			$offset_a = strpos( $output, ' wp-block-navigation-submenu nav-item"', $offset_a );
		}

		$offset_a = strpos( $output, ' wp-block-navigation-submenu nav-item ', $offset );
		while ( false !== $offset_a ) {
			$output   = substr_replace( $output, '', $offset_a + 28, 9 );
			$offset_a = strpos( $output, ' wp-block-navigation-submenu nav-item ', $offset_a );
		}

		$offset_a = strpos( $output, 'class="wp-block-navigation-item__content nav-link"', $offset );
		while ( false !== $offset_a ) {
			$output   = substr_replace( $output, ' dropdown-item px-3 py-1', $offset_a + 40, 9 );
			$offset_a = strpos( $output, 'class="wp-block-navigation-item__content nav-link"', $offset_a );
		}

		$offset_a = strpos( $output, 'class="wp-block-navigation-item__content nav-link ', $offset );
		while ( false !== $offset_a ) {
			$output   = substr_replace( $output, ' dropdown-item px-3 py-1', $offset_a + 40, 9 );
			$offset_a = strpos( $output, 'class="wp-block-navigation-item__content nav-link ', $offset_a );
		}

		$offset_a = strpos( $output, ' wp-block-navigation-submenu__toggle nav-link ', $offset );
		while ( false !== $offset_a ) {
			$output   = substr_replace( $output, ' dropdown-item w-100 h-100 px-2 py-1 ms-0', $offset_a + 36, 9 );
			$offset_a = strpos( $output, ' wp-block-navigation-submenu__toggle nav-link ', $offset_a );
		}
	}

	return $output;
}
add_filter( 'enlightenment_render_block_core_navigation_submenu', 'enlightenment_bootstrap_navigation_submenu_block' );

function enlightenment_bootstrap_navigation_top_level_link_block( $output ) {
	$offset = strpos( $output, ' wp-block-navigation-link"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' nav-item', $offset + 25, 0 );
	}

	$offset = strpos( $output, 'class="wp-block-navigation-item__content"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' nav-link', $offset + 40, 0 );
	}

	$offset = strpos( $output, ' current-menu-item ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, ' nav-link"', $offset );
		$output = substr_replace( $output, ' active', $offset + 9, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_render_block_core_navigation_link', 'enlightenment_bootstrap_navigation_top_level_link_block', 10, 2 );

function enlightenment_bootstrap_archives_block( $output ) {
    $output  = str_replace( '<ul class="wp-block-archives ',  '<ul class="wp-block-archives list-unstyled ', $output );
	$output  = str_replace( '<ul class="wp-block-archives-list wp-block-archives',  '<ul class="wp-block-archives-list wp-block-archives list-unstyled', $output );
	$output  = str_replace( '<ul class=" wp-block-archives-list wp-block-archives',  '<ul class="wp-block-archives-list wp-block-archives list-unstyled', $output );
    $output  = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );

    return $output;
}
add_filter( 'enlightenment_render_block_core_archives', 'enlightenment_bootstrap_archives_block' );

function enlightenment_bootstrap_categories_block( $output ) {
    $output  = str_replace( 'class="wp-block-categories wp-block-categories-list', 'class="wp-block-categories wp-block-categories-list list-unstyled', $output );
	$output  = str_replace( 'class="wp-block-categories-list wp-block-categories', 'class="wp-block-categories-list wp-block-categories list-unstyled', $output );
    $output  = str_replace( "class='children'", 'class="children list-unstyled ps-4"', $output );
    $output  = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );

    return $output;
}
add_filter( 'enlightenment_render_block_core_categories', 'enlightenment_bootstrap_categories_block' );

function enlightenment_bootstrap_latest_posts_block( $output, $block ) {
    if ( isset( $block['attrs'] ) && isset( $block['attrs']['postLayout'] ) && 'grid' == $block['attrs']['postLayout'] ) {
        $columns = isset( $block['attrs']['columns'] ) ? $block['attrs']['columns'] : 3;
        $colspan = 0;

        switch ( $columns ) {
            case 2:
                $colspan = 6;
                break;

            case 3:
                $colspan = 4;
                break;

            case 4:
                $colspan = 3;
                break;

            case 6:
                $colspan = 2;
                break;
        }

        if ( 5 == $columns ) {
            $start  = strpos( $output, '<ul class="wp-block-latest-posts ' );
            $end    = strpos( $output, '</ul>', $start );
            $offset = $start;
            $did    = 0;

            while ( false !== $offset ) {
                $offset = strpos( $output, '<li>', $offset );

                if ( 5 == $did ) {
                    $output = substr_replace( $output, '</div><div class="row">', $offset, 0 );
                    $offset = strpos( $output, '<li>', $offset );
                    $did    = 0;
                }

                $offset++;
                $did++;

                $offset = strpos( $output, '<li>', $offset );
                $end    = strpos( $output, '</ul>', $start );

                if ( $offset > $end ) {
                    break;
                }
            }
        }

        $suffix = $colspan ? sprintf( '-%s', $colspan ) : '';

        $offset = strpos( $output, '<ul class="wp-block-latest-posts ' );
        $offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, '<div class="row">', $offset + 1, 0 );
        $offset = strpos( $output, '</ul>', $offset );
        $output = substr_replace( $output, '</div>', $offset, 0 );

        $output  = str_replace( '<ul class="wp-block-latest-posts ', '<div class="wp-block-latest-posts ', $output );
        $output  = str_replace( '<li>', sprintf( '<div class="col-sm%s">', $suffix ), $output );
        $output  = str_replace( '</li>', '</div>', $output );
        $output  = str_replace( '</ul>', '</div>', $output );
    } else {
        $output = str_replace( 'class="wp-block-latest-posts ', 'class="wp-block-latest-posts list-unstyled ', $output );
		$output = str_replace( 'class="wp-block-latest-posts__list ', 'class="wp-block-latest-posts__list list-unstyled ', $output );
    }

    return $output;
}
add_filter( 'enlightenment_render_block_core_latest_posts', 'enlightenment_bootstrap_latest_posts_block', 10, 2 );

function enlightenment_bootstrap_latest_comments_block( $output ) {
    $output = str_replace( 'class="wp-block-latest-comments ', 'class="wp-block-latest-comments list-unstyled ', $output );
    $output = str_replace( 'class="wp-block-latest-comments"', 'class="wp-block-latest-comments list-unstyled"', $output );

    return $output;
}
add_filter( 'enlightenment_render_block_core_latest_comments', 'enlightenment_bootstrap_latest_comments_block' );

function enlightenment_bootstrap_rss_block( $output, $block ) {
    if ( isset( $block['attrs'] ) && isset( $block['attrs']['blockLayout'] ) && 'grid' == $block['attrs']['blockLayout'] ) {
        $columns = isset( $block['attrs']['columns'] ) ? $block['attrs']['columns'] : 2;
        $colspan = 0;

        switch ( $columns ) {
            case 2:
                $colspan = 6;
                break;

            case 3:
                $colspan = 4;
                break;

            case 4:
                $colspan = 3;
                break;

            case 6:
                $colspan = 2;
                break;
        }

        if ( 5 == $columns ) {
            $start  = strpos( $output, '<ul class="wp-block-rss ' );
            $end    = strpos( $output, '</ul>', $start );
            $offset = $start;
            $did    = 0;

            while ( false !== $offset ) {
                $offset = strpos( $output, "<li class='wp-block-rss__item'>", $offset );

                if ( 5 == $did ) {
                    $output = substr_replace( $output, "<li class='wp-block-rss__item'><div class='row'>", $offset, 0 );
                    $offset = strpos( $output, '<li>', $offset );
                    $did    = 0;
                }

                $offset++;
                $did++;

                $offset = strpos( $output, "<li class='wp-block-rss__item'>", $offset );
                $end    = strpos( $output, '</ul>', $start );

                if ( $offset > $end ) {
                    break;
                }
            }
        }

        $suffix = $colspan ? sprintf( '-%s', $colspan ) : '';

        $offset = strpos( $output, "<ul class='wp-block-rss " );
        $offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, '<div class="row">', $offset + 1, 0 );
        $offset = strpos( $output, '</ul>', $offset );
        $output = substr_replace( $output, '</div>', $offset, 0 );

        $output  = str_replace( "<ul class='wp-block-rss ", "<div class='wp-block-rss ", $output );
        $output  = str_replace( "<li class='wp-block-rss__item'>", sprintf( "<div class='wp-block-rss__item col-sm%s'>", $suffix ), $output );
        $output  = str_replace( '</li>', '</div>', $output );
        $output  = str_replace( '</ul>', '</div>', $output );
    } else {
        $output = str_replace( "class='wp-block-rss ", "class='wp-block-rss list-unstyled ", $output );
        $output = str_replace( "class='wp-block-rss'", "class='wp-block-rss list-unstyled'", $output );
    }

    return $output;
}
add_filter( 'enlightenment_render_block_core_rss', 'enlightenment_bootstrap_rss_block', 10, 2 );

function enlightenment_bootstrap_search_block( $output, $block ) {
	$output = str_replace( 'class="wp-block-search__label screen-reader-text"', 'class="wp-block-search__label screen-reader-text visually-hidden"', $output );

	$btnpos = 'outside';

	if ( isset( $block['attrs'] ) && isset( $block['attrs']['buttonPosition'] ) ) {
		switch ( $block['attrs']['buttonPosition'] ) {
			case 'button-inside':
				$btnpos = 'inside';
				break;

			case 'button-inside':
				$btnpos = 'none';
				break;
		}
	}

	switch ( $btnpos ) {
		case 'inside':
			$class = 'form-control d-flex p-2';
			break;

		case 'outside':
		default:
			$class = 'input-group';
	}

	if ( 'inside' == $btnpos ) {
		$output = str_replace( 'class="wp-block-search__inside-wrapper ',  'class="wp-block-search__inside-wrapper p-0 border-0 ', $output );
		$output = str_replace( 'class="wp-block-search__inside-wrapper"',  'class="wp-block-search__inside-wrapper p-0 border-0"', $output );
		$output = str_replace( 'class="wp-block-search__input ',  'class="wp-block-search__input p-0 border-0 ', $output );
		$output = str_replace( 'class="wp-block-search__input"',  'class="wp-block-search__input p-0 border-0"', $output );
	}

	$offset = strpos( $output, '<button ' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, 'type="search"', $offset - strlen( $output ) );
		$offset = strrpos( $output, '<input ', $offset - strlen( $output ) );
		$output = substr_replace( $output, sprintf( '<div class="%s"><input type="search" ', $class ), $offset, 7 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, '</div>', $offset + 9, 0 );
    }

	if ( 'outside' == $btnpos || 'none' == $btnpos ) {
	    $output = str_replace( 'class="wp-block-search__input ',  'class="wp-block-search__input form-control ', $output );
		$output = str_replace( 'class="wp-block-search__input"',  'class="wp-block-search__input form-control"', $output );
	}

	$output = str_replace( 'class="wp-block-search__button ', 'class="wp-block-search__button btn btn-light ', $output );
    $output = str_replace( 'class="wp-block-search__button"', 'class="wp-block-search__button btn btn-light"', $output );

    return $output;
}
add_filter( 'enlightenment_render_block_core_search', 'enlightenment_bootstrap_search_block', 10, 2 );

function enlightenment_bootstrap_group_block( $output, $block ) {
    if ( isset( $block['attrs'] ) && isset( $block['attrs']['align'] ) && 'full' == $block['attrs']['align'] ) {
		$offset = strpos( $output, 'class="wp-block-group__inner-container' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, ' container', $offset + 38, 0 );
		} else {
			$container_classes = array( 'container' );

			$start = strpos( $output, '<div class="wp-block-group ' );
			if ( false !== $start ) {
				$start  += 26;
				$end     = strpos( $output, '"', $start );
				$length  = $end - $start;
				$class   = substr( $output, $start, $length );
				$classes = explode( ' ', $class );

				foreach ( $classes as $class ) {
					if ( 0 === strpos( $class, 'is-' ) ) {
						$container_classes[] = $class;
					}

					if ( 0 === strpos( $class, 'wp-container-' ) ) {
						$container_classes[] = $class;
					}

					if ( 0 === strpos( $class, 'wp-block-group-is-' ) ) {
						$container_classes[] = $class;
					}
				}

				$block_classes = array_diff( $classes, $container_classes );
				$block_class   = join( ' ', $block_classes );

				$output = substr_replace( $output, $block_class, $start, $length );
			}

			$class = join( ' ', $container_classes );

			$offset = strpos( $output, '<div class="wp-block-group ' );
			if ( false !== $offset ) {
				$offset = strpos( $output, '>', $offset );
				$output = substr_replace( $output, sprintf( '<div class="%s">', $class ), $offset + 1, 0 );
				$offset = strrpos( $output, '</div>' );
				$output = substr_replace( $output, '</div>', $offset, 0 );
			}
		}
    }

	if (
		isset( $block['attrs'] )
		&&
		isset( $block['attrs']['className'] )
		&&
		isset( $block['attrs']['style'] )
		&&
		isset( $block['attrs']['style']['elements'] )
		&&
		isset( $block['attrs']['style']['elements']['button'] )
		&&
		isset( $block['attrs']['style']['elements']['button']['color'] )
	) {
		$style = sprintf(
			'%1$s<style>%1$s.%2$s .wp-element-button,%1$s.%2$s .wp-block-button__link {%1$s%3$sborder-color: transparent;%1$s}%1$s</style>%1$s',
			"\n",
			$block['attrs']['className'],
			"\t"
		);

		$offset = strpos( $output, '<div class="wp-block-group ' );
		if ( false !== $offset ) {
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, $style, $offset + 1, 0 );
		}
	}

    return $output;
}
add_filter( 'render_block_core/group', 'enlightenment_bootstrap_group_block', 12, 2 );

function enlightenment_bootstrap_query_block( $output, $block ) {
	if ( empty( $block['attrs'] ) ) {
		return $output;
	}

	if ( isset( $block['attrs']['align'] ) && 'full' == $block['attrs']['align'] ) {
		$offset = strpos( $output, 'class="wp-block-query ' );

		if ( false === $offset ) {
			$offset = strpos( $output, ' wp-block-query ' );
		}

		if ( false !== $offset ) {
			$offset  = strpos( $output, '>', $offset );
			$output  = substr_replace( $output, '<div class="container">', $offset + 1, 0 );
			$output .= '</div>';
		}
	}

	if (
		( isset( $block['attrs']['displayLayout'] ) && isset( $block['attrs']['displayLayout']['type'] ) )
		&&
		'flex' == $block['attrs']['displayLayout']['type']
	) {
		$columns = isset( $block['attrs']['displayLayout']['columns'] ) ? $block['attrs']['displayLayout']['columns'] : 3;
		$did     = 0;

		$offset = strpos( $output, '<ul class="is-flex-container ' );
		while ( false !== $offset ) {
			$class  = 5 == $columns ? 'row flex-xl-nowrap list-unstyled' : 'row list-unstyled';

			$output = substr_replace( $output, 'div', $offset + 1, 2 );
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, "\n" . sprintf( '<ul class="%s">', $class ), $offset + 1, 0 );

			$offset_r = 0;
			for ( $i = 0; $i <= $did; $i++ ) {
				$offset_r = strrpos( $output, '</ul>', $offset_r ? ( $offset_r - strlen( $output ) - 1 ) : 0 );

				if ( false === $offset_r ) {
					break;
				}
			}
			if ( false !== $offset_r ) {
				$output = substr_replace( $output, sprintf( '<!-- .%s -->', str_replace( ' ', '.', $class ) ) . "\n" . '</div>', $offset_r + 5, 0 );
			}

			$did++;

			$offset = strpos( $output, '<ul class="is-flex-container ' );
		}

		$output = str_replace( 'class="is-flex-container ',  'class="', $output );

        if ( 5 == $columns ) {
			$start  = strpos( $output, '<ul class="row flex-xl-nowrap list-unstyled">' );
			$end    = strpos( $output, '</ul><!-- .row.flex-xl-nowrap.list-unstyled -->', $start );

			while ( false !== $start ) {
	            $offset = strpos( $output, '<li class="wp-block-post ', $start );
	            $did    = 0;

	            while ( false !== $offset && $offset < $end ) {
	                if ( 5 == $did ) {
	                    $output = substr_replace( $output, '</ul><ul class="row flex-xl-nowrap list-unstyled">', $offset, 0 );
	                    $offset = strpos( $output, '<li class="wp-block-post ', $offset );
	                    $did    = 0;
	                }

	                $did++;

					$end    = strpos( $output, '</ul><!-- .row.flex-xl-nowrap.list-unstyled -->', $start );
					$offset = strpos( $output, '>', $offset );
	                $offset = strpos( $output, '<li class="wp-block-post ', $offset );
	            }

				$start  = strpos( $output, '<ul class="row flex-xl-nowrap list-unstyled">', $end );
				$end    = strpos( $output, '</ul><!-- .row.flex-xl-nowrap.list-unstyled -->', $start );
			}
        }

		switch ( $columns ) {
			case 1:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-12 ', $output );
				break;

			case 2:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-sm-6 ', $output );
				break;

			case 3:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-sm-6 col-lg-4 ', $output );
				break;

			case 4:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-sm-6 col-lg-3 ', $output );
				break;

			case 5:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-sm-6 col-md-4 col-lg-3 col-xl flex-sm-grow-1 flex-sm-shrink-0 ', $output );
				break;

			case 6:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-sm-6 col-lg-4 col-xl-2 ', $output );
				break;
		}
	}

	return $output;
}
add_filter( 'enlightenment_render_block_core_query', 'enlightenment_bootstrap_query_block', 10, 2 );

function enlightenment_bootstrap_post_template_block( $output, $block ) {
	if (
		( isset( $block['attrs']['layout'] ) && isset( $block['attrs']['layout']['type'] ) )
		&&
		'grid' == $block['attrs']['layout']['type']
	) {
		$columns = isset( $block['attrs']['layout']['columnCount'] ) ? $block['attrs']['layout']['columnCount'] : 3;

		if ( ! isset( $block['attrs']['layout']['columnCount'] ) && isset( $block['attrs']['layout']['minimumColumnWidth'] ) ) {
			$columns = 'auto';
		}

		$did = 0;

		$offset = strpos( $output, '<ul ' );
		if ( false !== $offset ) {
			$class  = 5 == $columns ? 'row flex-xl-nowrap list-unstyled' : 'row list-unstyled';

			$output = substr_replace( $output, 'div', $offset + 1, 2 );
			$offset = strpos( $output, ' class="', $offset );
			$offset = strpos( $output, '"', $offset + 8 );
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, "\n" . sprintf( '<ul class="%s">', $class ), $offset + 1, 0 );

			$offset_r = 0;
			for ( $i = 0; $i <= $did; $i++ ) {
				$offset_r = strrpos( $output, '</ul>', $offset_r ? ( $offset_r - strlen( $output ) - 1 ) : 0 );

				if ( false === $offset_r ) {
					break;
				}
			}
			if ( false !== $offset_r ) {
				$output = substr_replace( $output, sprintf( '<!-- .%s -->', str_replace( ' ', '.', $class ) ) . "\n" . '</div>', $offset_r + 5, 0 );
			}

			$did++;

			$offset = strpos( $output, '<ul ', $offset + 1 );
		}

		$output = str_replace( '"is-layout-grid ',  '"', $output );
		$output = str_replace( ' is-layout-grid ',  ' ', $output );
		$output = str_replace( ' is-layout-grid"',  '"', $output );

        if ( 5 == $columns ) {
			$start  = strpos( $output, '<ul class="row flex-xl-nowrap list-unstyled">' );
			$end    = strpos( $output, '</ul><!-- .row.flex-xl-nowrap.list-unstyled -->', $start );

			while ( false !== $start ) {
	            $offset = strpos( $output, '<li class="wp-block-post ', $start );
	            $did    = 0;

	            while ( false !== $offset && $offset < $end ) {
	                if ( 5 == $did ) {
	                    $output = substr_replace( $output, '</ul><ul class="row flex-xl-nowrap list-unstyled">', $offset, 0 );
	                    $offset = strpos( $output, '<li class="wp-block-post ', $offset );
	                    $did    = 0;
	                }

	                $did++;

					$end    = strpos( $output, '</ul><!-- .row.flex-xl-nowrap.list-unstyled -->', $start );
					$offset = strpos( $output, '>', $offset );
	                $offset = strpos( $output, '<li class="wp-block-post ', $offset );
	            }

				$start  = strpos( $output, '<ul class="row flex-xl-nowrap list-unstyled">', $end );
				$end    = strpos( $output, '</ul><!-- .row.flex-xl-nowrap.list-unstyled -->', $start );
			}
        }

		switch ( $columns ) {
			case 1:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-12 ', $output );
				break;

			case 2:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-sm-6 ', $output );
				break;

			case 3:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-sm-6 col-lg-4 ', $output );
				break;

			case 4:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-sm-6 col-lg-3 ', $output );
				break;

			case 5:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-sm-6 col-md-4 col-lg-3 col-xl flex-sm-grow-1 flex-sm-shrink-0 ', $output );
				break;

			case 6:
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-sm-6 col-lg-4 col-xl-2 ', $output );
				break;

			case 'auto':
				$output = str_replace( 'class="wp-block-post ',  'class="wp-block-post col-sm-6 col-lg ', $output );

				$offset = strpos( $output, 'class="wp-block-post ' );
				while ( false !== $offset ) {
					$start  = strrpos( $output, '<', $offset - strlen( $output ) );
					$end    = strpos( $output, '>', $offset ) + 1;
					$length = $end - $start;
					$tag    = substr( $output, $start, $length );
					$style  = sprintf(
						'flex-basis: min(%1$s + var(--bs-gutter-x), %2$s); max-width: max(%1$s + var(--bs-gutter-x), %2$s); ',
						esc_attr( $block['attrs']['layout']['minimumColumnWidth'] ),
						'100%'
					);

					if ( $offset_a = strpos( $tag, 'style="' ) ) {
						$tag = substr_replace( $tag, $style, $offset_a + 7, 0 );
					} elseif ( $offset_a = strpos( $tag, "style='" ) ) {
						$tag = substr_replace( $tag, $style, $offset_a + 7, 0 );
					} else {
						$tag = substr_replace( $tag, sprintf( ' style="%s"', trim( $style ) ), strlen( $tag ) - 1, 0 );
					}

					$output = substr_replace( $output, $tag, $start, $length );

					$offset = strpos( $output, 'class="wp-block-post ', $offset + 1 );
				}

				break;
		}
	}

	return $output;
}
add_filter( 'enlightenment_render_block_core_post_template', 'enlightenment_bootstrap_post_template_block', 10, 2 );

function enlightenment_bootstrap_query_pagination_previous_block( $output ) {
	$output = str_replace( 'wp-block-query-pagination-previous ', 'wp-block-query-pagination-previous mb-0 ', $output );
	$output = str_replace( 'wp-block-query-pagination-previous"', 'wp-block-query-pagination-previous mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_core_query_pagination_previous', 'enlightenment_bootstrap_query_pagination_previous_block' );

function enlightenment_bootstrap_query_pagination_next_block( $output ) {
	$output = str_replace( 'wp-block-query-pagination-next ', 'wp-block-query-pagination-next mb-0 ', $output );
	$output = str_replace( 'wp-block-query-pagination-next"', 'wp-block-query-pagination-next mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_core_query_pagination_next', 'enlightenment_bootstrap_query_pagination_next_block' );

function enlightenment_bootstrap_query_pagination_numbers_block( $output ) {
	$output = str_replace( 'class="wp-block-query-pagination-numbers ', 'class="wp-block-query-pagination-numbers pagination d-flex me-0 mb-0 ', $output );
	$output = str_replace( 'class="wp-block-query-pagination-numbers"', 'class="wp-block-query-pagination-numbers pagination d-flex me-0 mb-0"', $output );
	$output = str_replace( '<a ', '<dummy-div class="page-item"><a ', $output );
	$output = str_replace( '</a>', '</a></dummy-div>', $output );

	$offset = strpos( $output, 'class="page-numbers current"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, '<span ', $offset - strlen( $output ) );
		$output = substr_replace( $output, '<dummy-div class="page-item active">', $offset, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, '</dummy-div>', $offset + 7, 0 );
	}

	$offset = strpos( $output, 'class="page-numbers dots"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, '<span ', $offset - strlen( $output ) );
		$output = substr_replace( $output, '<dummy-div class="page-item">', $offset, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, '</dummy-div>', $offset + 7, 0 );
	}

	$output = str_replace( 'class="page-numbers ', 'class="page-numbers page-link ', $output );
	$output = str_replace( 'class="page-numbers"', 'class="page-numbers page-link"', $output );

	$output = str_replace( '<dummy-div ', '<div ', $output );
	$output = str_replace( '</dummy-div>', '</div>', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_core_query_pagination_numbers', 'enlightenment_bootstrap_query_pagination_numbers_block' );

function enlightenment_bootstrap_comments_query_loop_block( $output, $block ) {
	if ( empty( $block['attrs'] ) ) {
		return $output;
	}

	if ( isset( $block['attrs']['align'] ) && 'full' == $block['attrs']['align'] ) {
		$offset = strpos( $output, 'class="wp-block-comments-query-loop ' );

		if ( false === $offset ) {
			$offset = strpos( $output, ' wp-block-comments-query-loop ' );
		}

		if ( false !== $offset ) {
			$offset  = strpos( $output, '>', $offset );
			$output  = substr_replace( $output, '<div class="container">', $offset + 1, 0 );
			$output .= '</div>';
		}
	}

	return $output;
}
add_filter( 'enlightenment_render_block_core_comments_query_loop', 'enlightenment_bootstrap_comments_query_loop_block', 10, 2 );

function enlightenment_bootstrap_comments_pagination_previous_block( $output ) {
	$output = str_replace( 'wp-block-comments-pagination-previous ', 'wp-block-comments-pagination-previous mb-0 ', $output );
	$output = str_replace( 'wp-block-comments-pagination-previous"', 'wp-block-comments-pagination-previous mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_core_comments_pagination_previous', 'enlightenment_bootstrap_comments_pagination_previous_block' );

function enlightenment_bootstrap_comments_pagination_next_block( $output ) {
	$output = str_replace( 'wp-block-comments-pagination-next ', 'wp-block-comments-pagination-next mb-0 ', $output );
	$output = str_replace( 'wp-block-comments-pagination-next"', 'wp-block-comments-pagination-next mb-0"', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_core_comments_pagination_next', 'enlightenment_bootstrap_comments_pagination_next_block' );

function enlightenment_bootstrap_comments_pagination_numbers_block( $output ) {
	$output = str_replace( 'class="wp-block-comments-pagination-numbers ', 'class="wp-block-comments-pagination-numbers pagination d-flex me-0 mb-0 ', $output );
	$output = str_replace( 'class="wp-block-comments-pagination-numbers"', 'class="wp-block-comments-pagination-numbers pagination d-flex me-0 mb-0"', $output );
	$output = str_replace( '<a ', '<dummy-div class="page-item"><a ', $output );
	$output = str_replace( '</a>', '</a></dummy-div>', $output );

	$offset = strpos( $output, 'class="page-numbers current"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, '<span ', $offset - strlen( $output ) );
		$output = substr_replace( $output, '<dummy-div class="page-item active">', $offset, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, '</dummy-div>', $offset + 7, 0 );
	}

	$offset = strpos( $output, 'class="page-numbers dots"' );
	if ( false !== $offset ) {
		$offset = strrpos( $output, '<span ', $offset - strlen( $output ) );
		$output = substr_replace( $output, '<dummy-div class="page-item">', $offset, 0 );
		$offset = strpos( $output, '</span>', $offset );
		$output = substr_replace( $output, '</dummy-div>', $offset + 7, 0 );
	}

	$output = str_replace( 'class="page-numbers ', 'class="page-numbers page-link ', $output );
	$output = str_replace( 'class="page-numbers"', 'class="page-numbers page-link"', $output );

	$output = str_replace( '<dummy-div ', '<div ', $output );
	$output = str_replace( '</dummy-div>', '</div>', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_core_comments_pagination_numbers', 'enlightenment_bootstrap_comments_pagination_numbers_block' );

function enlightenment_bootstrap_post_comments_form_block( $output ) {
	$output = str_replace( 'class="comment-respond wp-block-post-comments-form"', 'class="comment-respond wp-block-comment-respond"', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_core_post_comments_form', 'enlightenment_bootstrap_post_comments_form_block' );

function enlightenment_bootstrap_post_comments_block( $output, $block ) {
	if ( isset( $block['attrs'] ) && isset( $block['attrs']['textAlign'] ) && 'right' == $block['attrs']['textAlign'] ) {
		$output = str_replace( 'class="comment-author-avatar flex-shrink-0 me-3"', 'class="comment-author-avatar flex-shrink-0 ms-3 order-1"', $output );
	}

    return $output;
}
add_filter( 'enlightenment_render_block_core_post_comments', 'enlightenment_bootstrap_post_comments_block', 10, 2 );
