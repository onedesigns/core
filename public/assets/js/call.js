jQuery( window ).on( 'load', function() {
	if ( typeof enlightenment_custom_query_flexslider_args != 'undefined' ) {
		jQuery.each( enlightenment_custom_query_flexslider_args, function( index, args ) {
			var selector   = args.selector,
				bootstrap  = args.bootstrap,
				columns    = args.columns,
				lastCols;

			delete args['selector'];
			delete args['bootstrap'];
			delete args['columns'];

			var $flexslider = jQuery( selector );

			if ( ! $flexslider.length ) {
				$flexslider = jQuery('.flexslider');
			}

			if ( bootstrap ) {
				var getGridCols = function() {
					var cols = columns.smartphone_portrait;

					if ( window.innerWidth >= 576 && typeof columns.smartphone_landscape != 'undefined' ) {
						cols = columns.smartphone_landscape;
					}

					if ( window.innerWidth >= 768 && typeof columns.tablet_portrait != 'undefined' ) {
						cols = columns.tablet_portrait;
					}

					if ( window.innerWidth >= 992 && typeof columns.tablet_landscape != 'undefined' ) {
						cols = columns.tablet_landscape;
					}

					if ( window.innerWidth >= 1200 && typeof columns.desktop_laptop != 'undefined' ) {
						cols = columns.desktop_laptop;
					}

					return cols;
				}

				var gridCols = getGridCols();

				args.minItems = gridCols;
				args.maxItems = gridCols;

				lastCols = gridCols;

				jQuery( window ).on( 'resize', function() {
					var flexslider = $flexslider.data( 'flexslider' );

					if ( typeof flexslider == 'undefined' ) {
						return true;
					}

					var gridCols = getGridCols();

					if ( gridCols != lastCols ) {
						flexslider.vars.minItems = gridCols;
			            flexslider.vars.maxItems = gridCols;

						setTimeout( function() {
							flexslider.resize();
						}, 250 );

						lastCols = gridCols;
					}
				} );
			} else {
				args.minItems = columns;
				args.maxItems = columns;
			}

			$flexslider.flexslider( args );
		} );
	}
} );

( function() {
	const dropdownToggle = document.querySelector( '[data-js-selector="color-mode-switcher-toggle"]' );

	if ( ! dropdownToggle ) {
		return;
	}

	const currentIcon   = dropdownToggle.querySelector( '.current-color-mode-icon' );
	const currentName   = dropdownToggle.querySelector( '.current-color-mode-name' );
	const dropdownItems = dropdownToggle.parentNode.querySelectorAll( '.dropdown-item' );

	dropdownItems.forEach( function( dropdownItem ) {
		dropdownItem.addEventListener( 'click', function( event ) {
			event.preventDefault();

			const currentItem = this;

			if ( currentItem.classList.contains( 'active' ) ) {
				return true;
			}

			const theme = ( function() {
				if ( 'auto' != currentItem.dataset.colorModeValue ) {
					return currentItem.dataset.colorModeValue;
				}

				return window.matchMedia( '(prefers-color-scheme: dark)' ).matches ? 'dark' : 'light';
			} )();

			if ( ! theme ) {
				return true;
			}

			document.documentElement.dataset.bsTheme = theme;

			const activeItem = Array.from( dropdownItems ).find( function( dropdownItem ) {
				return dropdownItem.classList.contains( 'active' );
			} );

			const activeIconClass  = 'fa-%s'.replace( '%s', enlightenment_call_js.color_modes[ activeItem.dataset.colorModeValue ].icon );
			const currentIconClass = 'fa-%s'.replace ('%s', enlightenment_call_js.color_modes[ currentItem.dataset.colorModeValue ].icon );
			const currentIconName  = enlightenment_call_js.color_modes[ currentItem.dataset.colorModeValue ].name;

			currentIcon.classList.remove( activeIconClass );
			currentIcon.classList.add( currentIconClass );
			currentName.innerText = currentIconName;

			activeItem.classList.remove( 'active' );
			activeItem.removeAttribute( 'aria-selected' );

			currentItem.classList.add( 'active' );
			currentItem.setAttribute( 'aria-selected', 'true' );

			const switchEvent = new CustomEvent( 'enlightenment_switch_color_mode', {
				detail: {
					newColorMode: currentItem.dataset.colorModeValue,
					oldColorMode: activeItem.dataset.colorModeValue,
				},
			} );

			document.documentElement.dispatchEvent( switchEvent );

			if ( document.body.classList.contains( 'logged-in' ) ) {
				const formData = new FormData();

				formData.append( 'action',     'enlightenment_color_mode' );
				formData.append( 'color_mode', currentItem.dataset.colorModeValue );
				formData.append( '_wpnonce',   ( new URLSearchParams( currentItem.href ) ).get( '_wpnonce' ) );

				fetch( enlightenment_call_js.ajaxurl, {
					method: 'POST',
					body: formData,
				} );
			} else {
				localStorage.setItem( 'enlightenmentBsTheme', currentItem.dataset.colorModeValue );
			}
		} );
	} );
} )();

jQuery(document).ready(function($) {
	if( typeof enlightenment_masonry_args != 'undefined' ) {
		var $container = $(enlightenment_masonry_args.container);

		$container.imagesLoaded( function() {
			$container.masonry(enlightenment_masonry_args);
		});
	}

	if( typeof enlightenment_fluidbox_args != 'undefined' ) {
		let $images = $(enlightenment_fluidbox_args.selector);

		$images.each(function() {
			var $this = $(this);

			if ( typeof enlightenment_custom_query_flexslider_args != 'undefined' ) {
				if ( $this.closest('.custom-query-has-type-carousel').length ) {
					return true;
				}
			}

			if ( typeof jetpackCarouselStrings != 'undefined' ) {
				if ( $this.parent().hasClass('gallery-icon') ) {
					return true;
				}

				if ( $this.parent().parent().hasClass('wp-block-gallery') ) {
					return true;
				}

				if ( $this.hasClass('single-image-gallery') ) {
					return true;
				}
			}

			if ( $this.parent('.woocommerce-product-gallery__image').length ) {
				return true;
			}

			if ( typeof elementorFrontendConfig != 'undefined' && 'yes' == $this.data('elementor-open-lightbox') ) {
				return true;
			}

			$this.fluidbox(enlightenment_fluidbox_args);

			let closeFluidboxOnScroll = function() {
				requestAnimationFrame(function() {
					$this.fluidbox('close');
				});
			};

			$this.on('openstart.fluidbox', function() {
				window.addEventListener('scroll', closeFluidboxOnScroll);
			});

			$this.on('closestart.fluidbox', function() {
				window.removeEventListener('scroll', closeFluidboxOnScroll);
			});
		});
	} else if( typeof enlightenment_colorbox_args != 'undefined' ) {
		let $images = $(enlightenment_colorbox_args.selector);

		$images.each(function() {
			var $this = $(this);

			if ( typeof jetpackCarouselStrings != 'undefined' ) {
				if ( $this.parent().hasClass('gallery-icon') ) {
					return true;
				}

				if ( $this.parent().parent().hasClass('blocks-gallery-item') ) {
					return true;
				}

				if ( $this.hasClass('single-image-gallery') ) {
					return true;
				}
			}

			if ( $this.parent('.woocommerce-product-gallery__image').length ) {
				return true;
			}

			if ( typeof elementorFrontendConfig != 'undefined' && 'yes' == $this.data('elementor-open-lightbox') ) {
				return true;
			}

			$this.colorbox(enlightenment_colorbox_args);
		});
	} else if( typeof enlightenment_imagelightbox_args != 'undefined' ) {
		let $images = $(enlightenment_imagelightbox_args.selector);

		$images.each(function() {
			var $this = $(this);

			if ( typeof jetpackCarouselStrings != 'undefined' ) {
				if ( $this.parent().hasClass('gallery-icon') ) {
					return true;
				}

				if ( $this.parent().parent().hasClass('blocks-gallery-item') ) {
					return true;
				}

				if ( $this.hasClass('single-image-gallery') ) {
					return true;
				}
			}

			if ( $this.parent('.woocommerce-product-gallery__image').length ) {
				return true;
			}

			if ( typeof elementorFrontendConfig != 'undefined' && 'yes' == $this.data('elementor-open-lightbox') ) {
				return true;
			}

			$this.imageLightbox();
		});
	}

	if( $.fn.fitVids ) {
		$('.entry-attachment, .entry-content, .activity-inner').fitVids({
			customSelector: "embed[src*='wordpress.com'], embed[src*='wordpress.tv'], iframe[src*='wordpress.com'], iframe[src*='wordpress.tv'], iframe[src*='www.dailymotion.com'], iframe[src*='blip.tv'], iframe[src*='www.viddler.com']",
			ignore: '.wp-block-file__embed',
		});
	}

	if( typeof enlightenment_ajax_navigation_args != 'undefined' ) {
		var selector = enlightenment_ajax_navigation_args.selector;
		delete enlightenment_ajax_navigation_args['selector'];

		enlightenment_ajax_navigation_args['complete'] = function( items, url, response ) {
			if ( window.location.href != url ) {
				history.pushState( null, null, url );
			}

			if( typeof enlightenment_masonry_args != 'undefined' ) {
				var $container = $(enlightenment_masonry_args.container);

				$container.imagesLoaded( function() {
					$container.masonry('appended', items);
				});
			}

			if( typeof enlightenment_fluidbox_args != 'undefined' ) {
				let $images = $(enlightenment_fluidbox_args.selector);

				$images.each(function() {
					var $this = $(this);

					if ( typeof jetpackCarouselStrings != 'undefined' ) {
						if ( $this.parent().hasClass('gallery-icon') ) {
							return true;
						}

						if ( $this.parent().parent().hasClass('blocks-gallery-item') ) {
							return true;
						}

						if ( $this.hasClass('single-image-gallery') ) {
							return true;
						}
					}

					if ( $this.parent('.woocommerce-product-gallery__image').length ) {
						return true;
					}

					if ( typeof elementorFrontendConfig != 'undefined' && 'yes' == $this.data('elementor-open-lightbox') ) {
						return true;
					}

					$this.fluidbox(enlightenment_fluidbox_args);

					let closeFluidboxOnScroll = function() {
						requestAnimationFrame(function() {
							$this.fluidbox('close');
						});
					};

					$this.on('openstart.fluidbox', function() {
						window.addEventListener('scroll', closeFluidboxOnScroll);
					});

					$this.on('closestart.fluidbox', function() {
						window.removeEventListener('scroll', closeFluidboxOnScroll);
					});
				});
			} else if( typeof enlightenment_colorbox_args != 'undefined' ) {
				let $images = $(enlightenment_colorbox_args.selector);

				$images.each(function() {
					var $this = $(this);

					if ( typeof jetpackCarouselStrings != 'undefined' ) {
						if ( $this.parent().hasClass('gallery-icon') ) {
							return true;
						}

						if ( $this.parent().parent().hasClass('blocks-gallery-item') ) {
							return true;
						}

						if ( $this.hasClass('single-image-gallery') ) {
							return true;
						}
					}

					if ( $this.parent('.woocommerce-product-gallery__image').length ) {
						return true;
					}

					if ( typeof elementorFrontendConfig != 'undefined' && 'yes' == $this.data('elementor-open-lightbox') ) {
						return true;
					}

					$this.colorbox(enlightenment_colorbox_args);
				});
			} else if( typeof enlightenment_imagelightbox_args != 'undefined' ) {
				let $images = $(enlightenment_imagelightbox_args.selector);

				$images.each(function() {
					var $this = $(this);

					if ( typeof jetpackCarouselStrings != 'undefined' ) {
						if ( $this.parent().hasClass('gallery-icon') ) {
							return true;
						}

						if ( $this.parent().parent().hasClass('blocks-gallery-item') ) {
							return true;
						}

						if ( $this.hasClass('single-image-gallery') ) {
							return true;
						}
					}

					if ( $this.parent('.woocommerce-product-gallery__image').length ) {
						return true;
					}

					if ( typeof elementorFrontendConfig != 'undefined' && 'yes' == $this.data('elementor-open-lightbox') ) {
						return true;
					}

					$this.imageLightbox();
				});
			}

			if( typeof $.fn.fitVids != 'undefined' ) {
				$('.entry-attachment, .entry-content').fitVids({
					customSelector: "embed[src*='wordpress.com'], embed[src*='wordpress.tv'], iframe[src*='wordpress.com'], iframe[src*='wordpress.tv'], iframe[src*='www.dailymotion.com'], iframe[src*='blip.tv'], iframe[src*='www.viddler.com']",
					ignore: '.wp-block-file__embed',
				});
			}

			var $response = $( $.parseHTML( response, document, true ) ),
				$done     = $response.filter( '#ajax-navigation-done-scripts' );

			if ( $done.length ) {
				let done_styles  = enlightenment_ajax_navigation_args.styles.slice(),
					done_scripts = enlightenment_ajax_navigation_args.scripts.slice(),
					$head        = $( 'head' ),
					$body        = $( 'body' );

				$( '#ajax-navigation-done-scripts' ).remove();

				$done.prop( 'async', false );
				$done.appendTo( 'body' );

				$.each( enlightenment_ajax_navigation_args.styles, function( index, value ) {
					if ( $.inArray( value, done_styles ) !== -1 ) {
						return true;
					}

					var $style = $response.filter( '#' + value + '-css' );

					if ( $style.length ) {
						$head.append( $style );

						let $inline = $response.filter( '#' + value + '-inline-css' );

						if ( $inline.length ) {
							$head.append( $inline );
						}
					}
				} );

				$.each( enlightenment_ajax_navigation_args.scripts, function( index, value ) {
					if ( value == 'wp-mediaelement' && typeof mejs != 'undefined' ) {
						let initMejs = function() {
							if ( typeof mejs == 'undefined' ) {
								setTimeout( initMejs, 250 );
								return;
							}

							var settings = {};

							if ( typeof _wpmejsSettings !== 'undefined' ) {
								settings = $.extend( true, {}, _wpmejsSettings );
							}

							settings.classPrefix = 'mejs-';

							settings.success = settings.success || function ( mejs ) {
								var autoplay, loop;

								if ( mejs.rendererName && -1 !== mejs.rendererName.indexOf( 'flash' ) ) {
									autoplay = mejs.attributes.autoplay && 'false' !== mejs.attributes.autoplay;
									loop     = mejs.attributes.loop     && 'false' !== mejs.attributes.loop;

									if ( autoplay ) {
										mejs.addEventListener( 'canplay', function() {
											mejs.play();
										}, false );
									}

									if ( loop ) {
										mejs.addEventListener( 'ended', function() {
											mejs.play();
										}, false );
									}
								}
							};

							settings.customError = function ( media, node ) {
								// Make sure we only fall back to a download link for flash files.
								if ( -1 !== media.rendererName.indexOf( 'flash' ) || -1 !== media.rendererName.indexOf( 'flv' ) ) {
									return '<a href="' + node.src + '">' + mejsL10n.strings['mejs.download-file'] + '</a>';
								}
							};

							settings.stretching = 'responsive';

							$( items ).each( function() {
								$( '.wp-audio-shortcode, .wp-video-shortcode', this ).mediaelementplayer( settings );
							} );
						};

						setTimeout( initMejs, 250 );

						return true;
					}

					if ( $.inArray( value, done_scripts ) !== -1 ) {
						return true;
					}

					var $script = $response.filter( '#' + value + '-js' );

					if ( $script.length ) {
						let $extra = $response.filter( '#' + value + '-js-extra' );

						if ( $extra.length ) {
							$extra.prop( 'async', false );
							$body.append( $extra );
						}

						let $before = $response.filter( '#' + value + '-js-before' );

						if ( $before.length ) {
							$before.prop( 'async', false );
							$body.append( $before );
						}

						$script.prop( 'async', false );
						$body.append( $script );

						let $after = $response.filter( '#' + value + '-js-after' );

						if ( $after.length ) {
							$after.prop( 'async', false );
							$body.append( $after );
						}
					}
				} );
			}

			$(items).each(function() {
				$context = $( '.twitter-tweet', this );
				if( $context.length ) {
					if( typeof twttr == 'undefined' ) {
						$.getScript( 'https://platform.twitter.com/widgets.js', function( data, textStatus, jqxhr ) {
							twttr.widgets.load( $context.get(0) );
						});
					} else {
						twttr.widgets.load( $context.get(0) );
					}
				}
			});

			$(this).trigger('enlightenment_ajax_navigation', [ items ]);
		}

		$(selector).ajaxload(enlightenment_ajax_navigation_args);
	}

	if( typeof enlightenment_infinite_scroll_args != 'undefined' ) {
		$(enlightenment_infinite_scroll_args.contentSelector).infinitescroll(enlightenment_infinite_scroll_args, function(items, data, url, response) {
			if ( window.location.href != url ) {
				history.pushState( null, null, url );
			}

			if( typeof enlightenment_masonry_args != 'undefined' ) {
				var $container = $(enlightenment_masonry_args.container);

				$container.imagesLoaded( function() {
					$container.masonry('appended', items);
				});
			}

			if( typeof enlightenment_fluidbox_args != 'undefined' ) {
				let $images = $(enlightenment_fluidbox_args.selector);

				$images.each(function() {
					var $this = $(this);

					if ( typeof jetpackCarouselStrings != 'undefined' ) {
						if ( $this.parent().hasClass('gallery-icon') ) {
							return true;
						}

						if ( $this.parent().parent().hasClass('blocks-gallery-item') ) {
							return true;
						}

						if ( $this.hasClass('single-image-gallery') ) {
							return true;
						}
					}

					if ( $this.parent('.woocommerce-product-gallery__image').length ) {
						return true;
					}

					if ( typeof elementorFrontendConfig != 'undefined' && 'yes' == $this.data('elementor-open-lightbox') ) {
						return true;
					}

					$this.fluidbox(enlightenment_fluidbox_args);

					let closeFluidboxOnScroll = function() {
						requestAnimationFrame(function() {
							$this.fluidbox('close');
						});
					};

					$this.on('openstart.fluidbox', function() {
						window.addEventListener('scroll', closeFluidboxOnScroll);
					});

					$this.on('closestart.fluidbox', function() {
						window.removeEventListener('scroll', closeFluidboxOnScroll);
					});
				});
			} else if( typeof enlightenment_colorbox_args != 'undefined' ) {
				let $images = $(enlightenment_colorbox_args.selector);

				$images.each(function() {
					var $this = $(this);

					if ( typeof jetpackCarouselStrings != 'undefined' ) {
						if ( $this.parent().hasClass('gallery-icon') ) {
							return true;
						}

						if ( $this.parent().parent().hasClass('blocks-gallery-item') ) {
							return true;
						}

						if ( $this.hasClass('single-image-gallery') ) {
							return true;
						}
					}

					if ( $this.parent('.woocommerce-product-gallery__image').length ) {
						return true;
					}

					if ( typeof elementorFrontendConfig != 'undefined' && 'yes' == $this.data('elementor-open-lightbox') ) {
						return true;
					}

					$this.colorbox(enlightenment_colorbox_args);
				});
			} else if( typeof enlightenment_imagelightbox_args != 'undefined' ) {
				let $images = $(enlightenment_imagelightbox_args.selector);

				$images.each(function() {
					var $this = $(this);

					if ( typeof jetpackCarouselStrings != 'undefined' ) {
						if ( $this.parent().hasClass('gallery-icon') ) {
							return true;
						}

						if ( $this.parent().parent().hasClass('blocks-gallery-item') ) {
							return true;
						}

						if ( $this.hasClass('single-image-gallery') ) {
							return true;
						}
					}

					if ( $this.parent('.woocommerce-product-gallery__image').length ) {
						return true;
					}

					if ( typeof elementorFrontendConfig != 'undefined' && 'yes' == $this.data('elementor-open-lightbox') ) {
						return true;
					}

					$this.imageLightbox();
				});
			}

			if( typeof $.fn.fitVids != 'undefined' ) {
				$('.entry-attachment, .entry-content').fitVids({
					customSelector: "embed[src*='wordpress.com'], embed[src*='wordpress.tv'], iframe[src*='wordpress.com'], iframe[src*='wordpress.tv'], iframe[src*='www.dailymotion.com'], iframe[src*='blip.tv'], iframe[src*='www.viddler.com']",
					ignore: '.wp-block-file__embed',
				});
			}

			var $response = $( $.parseHTML( response, document, true ) ),
				$done     = $response.filter( '#infinite-scroll-done-scripts' );

			if ( $done.length ) {
				let done_styles  = enlightenment_infinite_scroll_args.styles.slice(),
					done_scripts = enlightenment_infinite_scroll_args.scripts.slice(),
					$head        = $( 'head' ),
					$body        = $( 'body' );

				$( '#infinite-scroll-done-scripts' ).remove();

				$done.prop( 'async', false );
				$done.appendTo( 'body' );

				$.each( enlightenment_infinite_scroll_args.styles, function( index, value ) {
					if ( $.inArray( value, done_styles ) !== -1 ) {
						return true;
					}

					var $style = $response.filter( '#' + value + '-css' );

					if ( $style.length ) {
						$head.append( $style );

						let $inline = $response.filter( '#' + value + '-inline-css' );

						if ( $inline.length ) {
							$head.append( $inline );
						}
					}
				} );

				$.each( enlightenment_infinite_scroll_args.scripts, function( index, value ) {
					if ( value == 'wp-mediaelement' && typeof mejs != 'undefined' ) {
						let initMejs = function() {
							if ( typeof mejs == 'undefined' ) {
								setTimeout( initMejs, 250 );
								return;
							}

							var settings = {};

							if ( typeof _wpmejsSettings !== 'undefined' ) {
								settings = $.extend( true, {}, _wpmejsSettings );
							}

							settings.classPrefix = 'mejs-';

							settings.success = settings.success || function ( mejs ) {
								var autoplay, loop;

								if ( mejs.rendererName && -1 !== mejs.rendererName.indexOf( 'flash' ) ) {
									autoplay = mejs.attributes.autoplay && 'false' !== mejs.attributes.autoplay;
									loop     = mejs.attributes.loop     && 'false' !== mejs.attributes.loop;

									if ( autoplay ) {
										mejs.addEventListener( 'canplay', function() {
											mejs.play();
										}, false );
									}

									if ( loop ) {
										mejs.addEventListener( 'ended', function() {
											mejs.play();
										}, false );
									}
								}
							};

							settings.customError = function ( media, node ) {
								// Make sure we only fall back to a download link for flash files.
								if ( -1 !== media.rendererName.indexOf( 'flash' ) || -1 !== media.rendererName.indexOf( 'flv' ) ) {
									return '<a href="' + node.src + '">' + mejsL10n.strings['mejs.download-file'] + '</a>';
								}
							};

							settings.stretching = 'responsive';

							$( items ).each( function() {
								$( '.wp-audio-shortcode, .wp-video-shortcode', this ).mediaelementplayer( settings );
							} );
						};

						setTimeout( initMejs, 250 );

						return true;
					}

					if ( $.inArray( value, done_scripts ) !== -1 ) {
						return true;
					}

					var $script = $response.filter( '#' + value + '-js' );

					if ( $script.length ) {
						let $extra = $response.filter( '#' + value + '-js-extra' );

						if ( $extra.length ) {
							$extra.prop( 'async', false );
							$body.append( $extra );
						}

						let $before = $response.filter( '#' + value + '-js-before' );

						if ( $before.length ) {
							$before.prop( 'async', false );
							$body.append( $before );
						}

						$script.prop( 'async', false );
						$body.append( $script );

						let $after = $response.filter( '#' + value + '-js-after' );

						if ( $after.length ) {
							$after.prop( 'async', false );
							$body.append( $after );
						}
					}
				} );
			}

			$(items).each(function() {
				$context = $( '.twitter-tweet', this );
				if( $context.length ) {
					if( typeof twttr == 'undefined' ) {
						$.getScript( 'https://platform.twitter.com/widgets.js', function( data, textStatus, jqxhr ) {
							twttr.widgets.load( $context.get(0) );
						});
					} else {
						twttr.widgets.load( $context.get(0) );
					}
				}
			});

			$(this).trigger('enlightenment_infinite_scroll', [ items ]);
		});

		$(enlightenment_infinite_scroll_args.navSelector).hide();
	}

	$(document.body).on('stripeError', function (event, originalEvent) {
		if ( ! $('.form-control.StripeElement').length ) {
			return true;
		}

		setTimeout(function() {
			$('.wc-stripe-error').addClass('list-unstyled mb-2').children('li').addClass('text-danger');
		}, 0);
	});
});
