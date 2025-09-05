<?php

add_filter( 'enlightenment_render_block_jetpack_markdown', 'enlightenment_bootstrap_table_block' );

function enlightenment_bootstrap_jetpack_button( $output, $block ) {
    if ( isset( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'is-style-outline' ) ) {
        $output = str_replace( 'class="wp-block-jetpack-button ', 'class="wp-block-jetpack-button btn-outline-secondary ', $output );
        $output = str_replace( 'class="wp-block-jetpack-button"', 'class="wp-block-jetpack-button btn-outline-secondary"', $output );
    } else {
        $output = str_replace( 'class="wp-block-jetpack-button ', 'class="wp-block-jetpack-button btn-secondary ', $output );
        $output = str_replace( 'class="wp-block-jetpack-button"', 'class="wp-block-jetpack-button btn-secondary"', $output );
    }

    $output = str_replace( 'class="wp-block-jetpack-button ', 'class="wp-block-jetpack-button d-flex ', $output );
	$output = str_replace( 'class="is-visually-hidden"', 'class="is-visually-hidden visually-hidden"', $output );
	$output = str_replace( 'class="spinner"', 'class="spinner d-none"', $output );

    if ( isset( $block['attrs']['align'] ) ) {
        switch ( $block['attrs']['align'] ) {
            case 'left':
                $output = str_replace( 'class="wp-block-jetpack-button d-flex ', 'class="wp-block-jetpack-button d-flex justify-content-start me-0 ', $output );
                break;

            case 'center':
                $output = str_replace( 'class="wp-block-jetpack-button d-flex ', 'class="wp-block-jetpack-button d-flex justify-content-center ', $output );
                break;

            case 'right':
                $output = str_replace( 'class="wp-block-jetpack-button d-flex ', 'class="wp-block-jetpack-button d-flex justify-content-end ms-0 ', $output );
                break;
        }
    }

    $start = strpos( $output, 'class="wp-block-jetpack-button ' );
    if ( false !== $start ) {
        $offset = strpos( $output, 'style="', $start );
        $end    = strpos( $output, '>', $start );
        if ( false !== $offset && $offset < $end ) {
            $output = substr_replace( $output, 'background-color:transparent;', $offset + 7, 0 );
        } else {
            $offset = strpos( $output, '>', $start );
            $output = substr_replace( $output, ' style="background-color:transparent;"', $offset, 0 );
        }
    }

    if ( isset( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'is-style-outline' ) ) {
        $output = str_replace( 'class="wp-block-button__link ', 'class="btn btn-outline-secondary ',  $output );
        $output = str_replace( 'class="wp-block-button__link"', 'class="btn btn-outline-secondary"',  $output );
    } else {
        $output = str_replace( 'class="wp-block-button__link ', 'class="btn btn-secondary ',  $output );
        $output = str_replace( 'class="wp-block-button__link"', 'class="btn btn-secondary"',  $output );
    }

    $start  = strpos( $output, 'class="btn ' );
    if ( false !== $start ) {
        $end    = strpos( $output, '>', $start );
        $offset = strpos( $output, 'style="', $start );

        if ( false !== $offset && $offset < $end ) {
            $output = substr_replace( $output, 'border-width:1px;', $offset + 7, 0 );
        } else {
            $offset = strpos( $output, '"', $start );
            $offset = strpos( $output, '"', $offset + 1 );
            $output = substr_replace( $output, ' style="border-width:1px;"', $offset + 1, 0 );
        }

        if (
			! isset( $block['attrs']['className'] )
			||
			false !== strpos( $block['attrs']['className'], 'is-style-fill' )
		) {
            $end    = strpos( $output, '"', $start );
            $end    = strpos( $output, '"', $end + 1 );
            $offset = strpos( $output, ' has-background', $start );

            if ( false !== $offset && $offset < $end ) {
                $end    = strpos( $output, '>', $start );
                $offset = strpos( $output, 'style="', $start );

                if ( false !== $offset && $offset < $end ) {
                    $output = substr_replace( $output, 'border-color:transparent;', $offset + 7, 0 );
                } else {
                    $offset = strpos( $output, '"', $start );
                    $offset = strpos( $output, '"', $offset + 1 );
                    $output = substr_replace( $output, ' style="border-color:transparent;"', $offset + 1, 0 );
                }
            }
        }
    }

    return $output;
}
add_filter( 'enlightenment_render_block_jetpack_button', 'enlightenment_bootstrap_jetpack_button', 10, 2 );

function enlightenment_bootstrap_jetpack_contact_form_block( $output, $block ) {
	$style = 'default';

	if ( isset( $block['attrs'] ) && isset( $block['attrs']['className'] ) ) {
		if ( false !== strpos( $block['attrs']['className'], 'is-style-outline' ) ) {
			$style = 'outlined';
		} elseif ( false !== strpos( $block['attrs']['className'], 'is-style-animated' ) ) {
			$style = 'animated';
		}
	}

	$output = str_replace( 'class="grunion-field-label ', 'class="grunion-field-label form-label fw-bold mb-2 ', $output );
    $output = str_replace( "class='grunion-field-label ", "class='grunion-field-label form-label fw-bold mb-2 ", $output );
	$output = str_replace( 'class="grunion-field-label"', 'class="grunion-field-label form-label fw-bold mb-2"', $output );
	$output = str_replace( "class='grunion-field-label'", "class='grunion-field-label form-label fw-bold mb-2'", $output );
	$output = str_replace( 'class="grunion-field-label form-label fw-bold mb-2 consent consent-implicit"', 'class="grunion-field-label consent consent-implicit mb-0"', $output );
    $output = str_replace( "class='grunion-field-label form-label fw-bold mb-2 consent consent-implicit'", "class='grunion-field-label consent consent-implicit mb-0'", $output );
	$output = str_replace( 'class="notched-label"', 'class="notched-label w-auto"', $output );
	$output = str_replace( 'class="animated-label__label ', 'class="fw-normal ', $output );
	$output = str_replace( 'class="animated-label__label"', 'class="fw-normal"', $output );
	$output = str_replace( 'class="contact-form__input-error"', 'class="contact-form__input-error invalid-feedback mb-0"', $output );
	$output = str_replace( '<div class="clear-form"></div>', '', $output );
    $output = str_replace( "<div class='clear-form'></div>", '', $output );
	$output = str_replace( 'class="wp-block-jetpack-button ', 'class="col ', $output );
    $output = str_replace( 'class="btn ', 'class="btn btn-lg w-auto ', $output );

	$offset = strpos( $output, 'class="wp-block-jetpack-contact-form ' );
	if ( false !== $offset ) {
		$output  = str_replace( 'class="contact-form ', 'class="contact-form d-block ', $output );
		$output  = str_replace( "class='contact-form ", "class='contact-form d-block ", $output );
		$offset  = strpos( $output, 'class="wp-block-jetpack-contact-form ' );
		$offset += 36;
	} else {
		$offset = strpos( $output, 'class="wp-block-jetpack-contact-form"' );

		if ( false !== $offset ) {
			$output  = str_replace( 'class="contact-form ', 'class="contact-form d-block ', $output );
			$output  = str_replace( "class='contact-form ", "class='contact-form d-block ", $output );
			$offset  = strpos( $output, 'class="wp-block-jetpack-contact-form"' );
			$offset += 36;
		} else {
			$offset = strpos( $output, 'class="contact-form ' );
			if ( false === $offset ) {
				$offset = strpos( $output, "class='contact-form " );
			}

			if ( false !== $offset ) {
				$offset += 19;
			}
		}
	}
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' row gx-3 gap-0 p-0', $offset, 0 );
	}

    $start = strpos( $output, '<label' );
    while ( false !== $start ) {
		$end    = strpos( $output, '</label>', $start );
		$offset = strpos( $output, '<span>', $start );

		if ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="small fw-normal ms-1"', $offset + 5, 0 );
		}

		$start = strpos( $output, '<label', $start + 1 );
	}

	$py = 2;

	if ( 'animated' == $style ) {
		$py = 3;
	}

	$wrapper_classes = ' col-sm-%d mb-3 border-0';

	switch ( $style ) {
		case 'outlined':
		case 'animated':
			$class  = 'class="contact-form__inset-label-wrap';
			$offset = strpos( $output, $class );

			if ( false === $offset ) {
				$class  = "class='contact-form__inset-label-wrap";
				$offset = strpos( $output, $class );
			}

			while ( false !== $offset ) {
				$offset_a = strpos( $output, '-wrap grunion-field-wrap', $offset );
				$end_a    = strpos( $output, '</div>', $offset );
				if ( false !== $offset_a && $offset_a < $end_a ) {
					$start  = strrpos( $output, ' grunion-field-width-', $offset_a - strlen( $output ) );
					if ( false !== $start && $start > $offset ) {
						$start += 21;
				        $end    = strpos( $output, '-wrap', $start );
				        $length = $end - $start;
				        $width  = substr( $output, $start, $length );
					} else {
						$width  = 100;
					}
				} else {
					$width  = 100;
				}

				switch ( $width ) {
					case 25:
						$colspan = 3;
						break;

					case 50:
						$colspan = 6;
						break;

					case 75:
						$colspan = 9;
						break;

					default:
						$colspan = 12;
				}

				$output = substr_replace( $output, sprintf( $wrapper_classes, $colspan ), $offset + 37, 0 );
				$offset = strpos( $output, strpos( $class, '"' ) ? '"' : "'", $offset + 7 );
				$output = substr_replace( $output, ' style="flex-basis: auto;"', $offset + 1, 0 );
				$offset = strpos( $output, '-wrap grunion-field-wrap', $offset );
				$output = substr_replace( $output, ' mw-100', $offset + 24, 0 );

				$offset = strpos( $output, $class, $offset );
			}

			break;

		default:
			$quotes = strpos( $output, 'class="wp-block-jetpack-field-' ) ? '"' : "'";
			$offset = strpos( $output, '-wrap grunion-field-wrap' );
			while ( false !== $offset ) {
				$start  = strrpos( $output, ' grunion-field-width-', $offset - strlen( $output ) );
				$limit  = strrpos( $output, $quotes, $offset - strlen( $output ) );
				if ( false !== $start && $start > $limit ) {
					$start += 21;
			        $end    = strpos( $output, '-wrap', $start );
			        $length = $end - $start;
			        $width  = substr( $output, $start, $length );
				} else {
					$width  = 100;
				}

				switch ( $width ) {
					case 25:
						$colspan = 3;
						break;

					case 50:
						$colspan = 6;
						break;

					case 75:
						$colspan = 9;
						break;

					default:
						$colspan = 12;
				}

				$offset = strpos( $output, $quotes, $offset );
		        $output = substr_replace( $output, sprintf( $wrapper_classes, $colspan ), $offset, 0 );

				$offset = strpos( $output, '-wrap grunion-field-wrap', $offset );
			}
	}

	$wrapper_classes = sprintf( ' form-control px-3 py-%d mb-0', $py );

    $offset = strpos( $output, '-wrap grunion-field-wrap' );
    while ( false !== $offset ) {
		$start = strrpos( $output, "class='wp-block-jetpack-field-", $offset - strlen( $output ) );

		if ( false !== $start ) {
			$start += 30;
			$end    = strpos( $output, ' ', $start );
		} else {
			$start  = strrpos( $output, "class='grunion-field-", $offset - strlen( $output ) ) + 21;
			$end    = strpos( $output, '-wrap', $start );
		}

        $length = $end - $start;
        $type   = substr( $output, $start, $length );

		if ( 'animated' == $style && ! in_array( $type, array( 'checkbox', 'checkbox-multiple', 'radio', 'consent' ) ) ) {
			$output = substr_replace( $output, ' form-floating', $offset + 24, 0 );
		}

		$class    = strpos( $output, sprintf( 'class="%s ', $type ), $offset ) ? 'class="%s ' : "class='%s ";
		$quotes   = $class == 'class="%s ' ? '"' : "'";
		$offset_a = strpos( $output, sprintf( $class, $type ), $offset );
		$end_a    = strpos( $output, '</div>', $offset );
		while ( false !== $offset_a && $offset_a < $end_a ) {
			$offset_a = strpos( $output, $quotes, $offset_a + 7 );
			$output   = substr_replace( $output, $wrapper_classes, $offset_a, 0 );

			$end_a    = strpos( $output, '</div>', $offset );
			$offset_a = strpos( $output, sprintf( $class, $type ), $offset_a );
		}

		$class    = strpos( $output, sprintf( 'class="%s"', $type ), $offset ) ? 'class="%s"' : "class='%s'";
		$quotes   = $class == 'class="%s ' ? '"' : "'";
		$offset_a = strpos( $output, sprintf( $class, $type ), $offset );
		$end_a    = strpos( $output, '</div>', $offset );
        while ( false !== $offset_a && $offset_a < $end_a ) {
            $offset_a = strpos( $output, $quotes, $offset_a + 7 );
			$output   = substr_replace( $output, $wrapper_classes, $offset_a, 0 );

			$end_a    = strpos( $output, '</div>', $offset );
			$offset_a = strpos( $output, sprintf( $class, $type ), $offset_a );
        }

		if ( 'animated' == $style && 'textarea' == $type ) {
			$offset = strpos( $output, $wrapper_classes, $offset + 1 );
			$output = substr_replace( $output, ' h-auto', $offset + 28, 0 );
		}

        $offset = strpos( $output, '-wrap grunion-field-wrap', $offset + 1 );
    }

	$offset = strpos( $output, 'contact-form__checkbox-wrap' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, ' form-check', $offset + 27, 0 );
		$offset = strpos( $output, $wrapper_classes, $offset );
		$output = substr_replace( $output, ' form-check-input', $offset, 28 );
		$offset = strpos( $output, 'grunion-field-label form-label fw-bold mb-2 checkbox', $offset );
		$output = substr_replace( $output, ' form-check-label mb-0', $offset + 20, 23 );

		$offset = strpos( $output, 'contact-form__checkbox-wrap', $offset );
	}

    $offset = strpos( $output, 'grunion-field-select-wrap' );
    while ( false !== $offset ) {
		$start = strpos( $output, 'contact-form__select-wrapper', $offset );
		if ( false !== $start ) {
			$start  = strrpos( $output, '<', $start - strlen( $output ) );
			$end    = strpos( $output, '>', $start ) + 1;
			$length = $end - $start;
			$output = substr_replace( $output, '', $start, $length );

			$start  = strpos( $output, '</div>', $start );
			$output = substr_replace( $output, '', $start, 6 );
		}

		$offset_a = strpos( $output, "class='select ", $offset );
		if ( false !== $offset_a ) {
			$offset_a = strpos( $output, ' form-control ', $offset_a );
	        $output   = substr_replace( $output, 'form-select', $offset_a + 1, 12 );
		} else {
			$offset_a = strpos( $output, '<select ', $offset );
			if ( false !== $offset_a ) {
		        $output = substr_replace( $output, 'class="form-select" ', $offset_a + 8, 12 );
			}
		}

		if ( 'outlined' == $style ) {
			$offset_a = strpos( $output, 'class="notched-label w-auto"', $offset );
			$end_a    = strpos( $output, '</div>', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output = substr_replace( $output, ' top-0', $offset_a + 27, 0 );
			}
		}

        $offset = strpos( $output, 'grunion-field-select-wrap', $offset + 1 );
    }

    $offset = strpos( $output, 'wp-block-jetpack-field-checkbox-multiple' );
    while ( false !== $offset ) {
		$offset_a = strpos( $output, 'class="grunion-field-label form-label fw-bold ', $offset );
		$quote_a  = '"';
		if ( false === $offset_a ) {
			$offset_a = strpos( $output, "class='grunion-field-label form-label fw-bold ", $offset );
			$quote_a  = "'";
		}
		if ( false !== $offset_a ) {
			$offset_a = strpos( $output, $quote_a, $offset_a + 7 );
			$output   = substr_replace( $output, ' style="font-size: inherit !important;"', $offset_a + 1, 0 );
		}

		if ( 'outlined' == $style ) {
			$offset_a = strpos( $output, 'class="grunion-label-text"', $offset );
			if ( false !== $offset_a ) {
				$output   = substr_replace( $output, ' style="font-size: inherit !important;"', $offset_a + 26, 0 );
			}

			$offset_a = strpos( $output, 'class="grunion-label-required"', $offset );
			if ( false !== $offset_a ) {
				$output   = substr_replace( $output, ' style="font-size: inherit !important;"', $offset_a + 30, 0 );
			}

			$offset_a = strpos( $output, 'grunion-checkbox-multiple-options', $offset );
			if ( false !== $offset_a ) {
				$output   = substr_replace( $output, ' rounded border', $offset_a + 33, 0 );
				$offset_a = strpos( $output, 'grunion-field-label form-label fw-bold ', $offset_a );
				$output   = substr_replace( $output, '', $offset_a + 30, 8 );
			}
		}

		$offset_a = strpos( $output, 'contact-form-field', $offset );
		$end_a    = strpos( $output, '</fieldset>', $offset );
        while ( false !== $offset_a && $offset_a < $end_a ) {
			$offset_a = strpos( $output, '>', $offset_a );
			$output   = substr_replace( $output, '<span class="form-check">', $offset_a + 1, 0 );
			$offset_a = strpos( $output, "class='checkbox-multiple ", $offset_a );
			$output   = substr_replace( $output, 'form-check-input', $offset_a + 7, 17 );
			$offset_a = strpos( $output, $wrapper_classes, $offset_a );
			$output   = substr_replace( $output, '', $offset_a, 28 );
			$offset_a = strpos( $output, '/>', $offset_a );
			$output   = substr_replace( $output, ' style="min-width: auto;" ', $offset_a, 0 );
			$offset_a = strpos( $output, "class='grunion-checkbox-multiple-label ", $offset_a );
			$output   = substr_replace( $output, ' form-check-label', $offset_a + 38, 0 );
			$offset_a = strpos( $output, '</p>', $offset_a );
			$output   = substr_replace( $output, '</span>', $offset_a, 0 );

			$offset_a = strpos( $output, 'contact-form-field', $offset_a );
		}

		$offset = strpos( $output, 'wp-block-jetpack-field-checkbox-multiple', $offset + 1 );
	}

    $offset = strpos( $output, 'grunion-radio-options' );
    while ( false !== $offset ) {
		$offset_a = strpos( $output, 'class="grunion-field-label form-label fw-bold ', $offset );
		$quote_a  = '"';
		if ( false === $offset_a ) {
			$offset_a = strpos( $output, "class='grunion-field-label form-label fw-bold ", $offset );
			$quote_a  = "'";
		}
		if ( false !== $offset_a ) {
			$offset_a = strpos( $output, $quote_a, $offset_a + 7 );
			$output   = substr_replace( $output, ' style="font-size: inherit !important;"', $offset_a + 1, 0 );
		}

		if ( 'outlined' == $style ) {
			$output   = substr_replace( $output, ' rounded border', $offset + 21, 0 );

			$offset_a = strpos( $output, 'class="grunion-label-text"', $offset );
			if ( false !== $offset_a ) {
				$output   = substr_replace( $output, ' style="font-size: inherit !important;"', $offset_a + 26, 0 );
			}

			$offset_a = strpos( $output, 'class="grunion-label-required"', $offset );
			if ( false !== $offset_a ) {
				$output   = substr_replace( $output, ' style="font-size: inherit !important;"', $offset_a + 30, 0 );
			}
		}

		$offset_a = strpos( $output, 'contact-form-field', $offset );
		$end_a    = strpos( $output, '</fieldset>', $offset );
        while ( false !== $offset_a && $offset_a < $end_a ) {
			$offset_a = strpos( $output, '>', $offset_a );
			$output   = substr_replace( $output, '<span class="form-check">', $offset_a + 1, 0 );
			$offset_a = strpos( $output, "class='radio ", $offset_a );
			$output   = substr_replace( $output, 'form-check-input', $offset_a + 7, 5 );
			$offset_a = strpos( $output, $wrapper_classes, $offset_a );
			$output   = substr_replace( $output, '', $offset_a, 28 );
			$offset_a = strpos( $output, '/>', $offset_a );
			$output   = substr_replace( $output, ' style="min-width: auto;" ', $offset_a, 0 );
			$offset_a = strpos( $output, "class='grunion-radio-label ", $offset_a );
			$output   = substr_replace( $output, ' form-check-label', $offset_a + 26, 0 );
			$offset_a = strpos( $output, '</p>', $offset_a );
			$output   = substr_replace( $output, '</span>', $offset_a, 0 );

			$offset_a = strpos( $output, 'contact-form-field', $offset_a );
		}

		$offset = strpos( $output, 'grunion-radio-options', $offset + 1 );
	}

	$offset = strpos( $output, "<label class='grunion-field-label form-label fw-bold mb-2 consent consent-explicit" );
    if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$label  = substr( $output, $start, $length );
		$output = substr_replace( $output, '', $start, $length );

		$start  = strpos( $output, " name='", $start ) + 7;
		$end    = strpos( $output, "'", $start );
		$length = $end - $start;
		$name   = substr( $output, $start, $length );

		$label  = str_replace( '<label ', sprintf( '<label for="%s" ', $name ), $label );
		$label  = str_replace( ' form-label fw-bold mb-2 ', ' form-check-label mb-0 ', $label );

		$output = substr_replace( $output, '<div class="form-check">', $offset, 0 );
		$offset = strpos( $output, sprintf( " name='%s'", $name ), $offset );
		$output = substr_replace( $output, sprintf( ' id="%s"', $name ), $offset, 0 );
		$offset = strpos( $output, $wrapper_classes, $offset );
		$output = substr_replace( $output, ' form-check-input', $offset, 28 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, $label, $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, '</div>', $offset + 8, 0 );
	}

	$offset = strpos( $output, ' class="contact-form-submission"' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, 'alert alert-info d-flex flex-wrap align-items-center gap-2', $offset + 8, 23 );
		$offset = strpos( $output, ' class="go-back-message"', $offset );
		$output = substr_replace( $output, ' order-1 mb-0', $offset + 23, 0 );
		$offset = strpos( $output, ' class="link"', $offset );
		$output = substr_replace( $output, ' btn btn-outline-info', $offset + 12, 0 );
		$offset = strpos( $output, ' id="contact-form-success-header"', $offset );
		$output = substr_replace( $output, ' class="alert-heading w-100 mb-0"', $offset, 0 );
		$offset = strpos( $output, '</h4>', $offset );
		$output = substr_replace( $output, '<div class="flex-grow-1">', $offset + 5, 0 );

		$offset_a = strpos( $output, '<p>', $offset );
		while ( false !== $offset_a ) {
			$output = substr_replace( $output, 'dl', $offset_a + 1, 1 );
			$offset_a = strpos( $output, '</p>', $offset_a );
			$output = substr_replace( $output, 'dl', $offset_a + 2, 1 );

			$offset_a = strpos( $output, '<p>', $offset_a );
		}

		$offset_a = strpos( $output, '<div class="field-name">', $offset );
		while ( false !== $offset_a ) {
			$output = substr_replace( $output, 'dt', $offset_a + 1, 3 );
			$offset_a = strpos( $output, '</div>', $offset_a );
			$output = substr_replace( $output, 'dt', $offset_a + 2, 3 );

			$offset_a = strpos( $output, '<div class="field-name">', $offset_a );
		}

		$offset_a = strpos( $output, '<div class="field-value">', $offset );
		while ( false !== $offset_a ) {
			$output = substr_replace( $output, 'dd', $offset_a + 1, 3 );
			$offset_a = strpos( $output, '</div>', $offset_a );
			$output = substr_replace( $output, 'dd', $offset_a + 2, 3 );

			$offset_a = strpos( $output, '<div class="field-value">', $offset_a );
		}

		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );
	}

    return $output;

}
add_filter( 'enlightenment_render_block_jetpack_contact_form', 'enlightenment_bootstrap_jetpack_contact_form_block', 10, 2 );

function enlightenment_bootstrap_jetpack_eventbrite_block( $output, $block ) {
    if ( ! ( isset( $block['attrs'] ) && isset( $block['attrs']['style'] ) && 'modal' == $block['attrs']['style'] ) ) {
        return $output;
    }

    $class = 'd-flex';

    if ( isset( $block['attrs'] ) && isset( $block['attrs']['align'] ) ) {
        switch( $block['attrs']['align'] ) {
            case 'center':
            case 'wide':
            case 'full':
                $class .= ' justify-content-center';
                break;
        }
    }

    $output = str_replace( 'class="wp-block-jetpack-eventbrite ', sprintf( 'class="wp-block-jetpack-eventbrite %s ', $class ), $output );

    return $output;
}
add_filter( 'enlightenment_render_block_jetpack_eventbrite', 'enlightenment_bootstrap_jetpack_eventbrite_block', 10, 2 );

function enlightenment_bootstrap_jetpack_calendly_block( $output, $block ) {
    if ( ! ( isset( $block['attrs'] ) && isset( $block['attrs']['style'] ) && 'link' == $block['attrs']['style'] ) ) {
        return $output;
    }

    $class = 'd-flex';

    if ( isset( $block['attrs'] ) && isset( $block['attrs']['align'] ) ) {
        switch( $block['attrs']['align'] ) {
            case 'center':
            case 'wide':
            case 'full':
                $class .= ' justify-content-center';
                break;
        }
    }

    $output = str_replace( 'class="wp-block-jetpack-calendly ', sprintf( 'class="wp-block-jetpack-calendly %s ', $class ), $output );

    if ( isset( $block['innerBlocks'][0]['attrs']['className'] ) && false !== strpos( $block['innerBlocks'][0]['attrs']['className'], 'is-style-outline' ) ) {
        $output = str_replace( 'class="wp-block-jetpack-button ', 'class="wp-block-jetpack-button d-flex btn-outline-secondary ', $output );
        $output = str_replace( 'class="wp-block-jetpack-button"', 'class="wp-block-jetpack-button d-flex btn-outline-secondary"', $output );
    } else {
        $output = str_replace( 'class="wp-block-jetpack-button ', 'class="wp-block-jetpack-button d-flex btn-secondary ', $output );
        $output = str_replace( 'class="wp-block-jetpack-button"', 'class="wp-block-jetpack-button d-flex btn-secondary"', $output );
    }

    $start = strpos( $output, 'class="wp-block-jetpack-button ' );
    if ( false !== $start ) {
        $offset = strpos( $output, '>', $start );
        $output = substr_replace( $output, ' style="background-color:transparent;"', $offset, 0 );
    }

    if ( isset( $block['innerBlocks'][0]['attrs']['className'] ) && false !== strpos( $block['innerBlocks'][0]['attrs']['className'], 'is-style-outline' ) ) {
        $output = str_replace( 'class="wp-block-button__link ', 'class="btn btn-outline-secondary ',  $output );
        $output = str_replace( 'class="wp-block-button__link"', 'class="btn btn-outline-secondary"',  $output );
    } else {
        $output = str_replace( 'class="wp-block-button__link ', 'class="btn btn-secondary ',  $output );
        $output = str_replace( 'class="wp-block-button__link"', 'class="btn btn-secondary"',  $output );
    }

    $start  = strpos( $output, 'class="btn ' );
    if ( false !== $start ) {
        $end    = strpos( $output, '>', $start );
        $offset = strpos( $output, 'style="', $start );

        if ( false !== $offset && $offset < $end ) {
            $output = substr_replace( $output, 'border-width:1px;', $offset + 7, 0 );
        } else {
            $offset = strpos( $output, '"', $start );
            $offset = strpos( $output, '"', $offset + 1 );
            $output = substr_replace( $output, ' style="border-width:1px;"', $offset + 1, 0 );
        }

        if (
			! isset( $block['innerBlocks'][0]['attrs']['className'] )
			||
			false !== strpos( $block['innerBlocks'][0]['attrs']['className'], 'is-style-fill' )
		) {
            $end    = strpos( $output, '"', $start );
            $end    = strpos( $output, '"', $end + 1 );
            $offset = strpos( $output, ' has-background', $start );

            if ( false !== $offset && $offset < $end ) {
                $end    = strpos( $output, '>', $start );
                $offset = strpos( $output, 'style="', $start );

                if ( false !== $offset && $offset < $end ) {
                    $output = substr_replace( $output, 'border-color:transparent;', $offset + 7, 0 );
                } else {
                    $offset = strpos( $output, '"', $start );
                    $offset = strpos( $output, '"', $offset + 1 );
                    $output = substr_replace( $output, ' style="border-color:transparent;"', $offset + 1, 0 );
                }
            }
        }
    }

    return $output;
}
add_filter( 'enlightenment_render_block_jetpack_calendly', 'enlightenment_bootstrap_jetpack_calendly_block', 10, 2 );

function enlightenment_bootstrap_jetpack_comment_subscription_form( $output ) {
    $output = str_replace( 'class="comment-subscription-form"', 'class="comment-subscription-form form-check"',  $output );
    $output = str_replace( 'id="subscribe_comments"', 'id="subscribe_comments" class="form-check-input"',  $output );
    $output = str_replace( 'class="subscribe-label"', 'class="subscribe-label form-check-label"',  $output );
    $output = str_replace( 'id="subscribe_blog"', 'id="subscribe_blog" class="form-check-input"',  $output );

	$offset = strrpos( $output, 'class="comment-subscription-form form-check"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' mb-3', $offset + 43, 0 );
	}

    return $output;
}
add_filter( 'jetpack_comment_subscription_form', 'enlightenment_bootstrap_jetpack_comment_subscription_form' );

function enlightenment_bootstrap_blog_subscription_widget( $output ) {
    $output = str_replace( 'class="screen-reader-text"', 'class="screen-reader-text visually-hidden"', $output );

    $offset = strpos( $output, '<p id="subscribe-email">' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'div', $offset + 1, 1 );
        $output = substr_replace( $output, ' class="input-group flex-grow-1 flex-shrink-1 w-auto mb-0"', $offset + 25, 0 );
        $output = substr_replace( $output, '<div class="input-group flex-nowrap">' . "\n", $offset, 0 );
        $offset = strpos( $output, '<input type="email"', $offset );
        $output = substr_replace( $output, '<div class="input-group flex-grow-1 ms-0 w-auto">' . "\n", $offset, 0 );
        $offset = strpos( $output, '<input type="email"', $offset );

        $offset_a = strpos( $output, 'class="', $offset );
        $end_a    = strpos( $output, '/>', $offset );
        if ( false !== $offset_a && $offset_a < $end_a ) {
            $output = substr_replace( $output, 'form-control w-100 ', $offset_a + 7, 0 );
        } else {
            $output = substr_replace( $output, 'class="form-control w-100" ', $offset + 7, 0 );
        }

        $offset = strpos( $output, '/>', $offset );
        $output = substr_replace( $output, "\n" . '<i class="d-none"></i>' . "\n" . '</div>', $offset + 2, 0 );
        $offset = strpos( $output, '</p>', $offset );
        $output = substr_replace( $output, 'div', $offset + 2, 1 );
        $offset = strpos( $output, '<p id="subscribe-submit"', $offset );
        $output = substr_replace( $output, 'div', $offset + 1, 1 );
        $output = substr_replace( $output, ' class="input-group flex-grow-0 flex-shrink-1 w-auto mb-0"', $offset + 26, 0 );
        $offset = strpos( $output, '<button type="submit"', $offset );

        $offset_a = strpos( $output, 'class="', $offset );
        $end_a    = strpos( $output, '>', $offset );
        if ( false !== $offset_a && $offset_a < $end_a ) {
            $output   = substr_replace( $output, 'btn btn-light ', $offset_a + 7, 0 );

			$offset_a = strpos( $output, ' wp-block-button__link', $offset_a );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output   = substr_replace( $output, '', $offset_a, 22 );
			}
        } else {
            $output = substr_replace( $output, 'class="btn btn-light" ', $offset + 8, 0 );
        }

        $offset = strpos( $output, '</p>', $offset );
        $output = substr_replace( $output, 'div', $offset + 2, 1 );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
    }

    return $output;
}
add_filter( 'enlightenment_widget_blog_subscription', 'enlightenment_bootstrap_blog_subscription_widget' );

function enlightenment_bootstrap_grofile_widget( $output ) {
	return str_replace( 'class="grofile-full-link"', 'class="grofile-full-link btn btn-secondary"', $output );
}
add_filter( 'enlightenment_widget_grofile', 'enlightenment_bootstrap_grofile_widget' );
