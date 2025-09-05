/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	"use strict";
	var api  = wp.customize,
		args = enlightenment_customize_preview_args;

	args.web_fonts.fonts = {};
	args.web_fonts.fonts = $.extend( args.web_fonts.fonts, args.web_fonts.local );

	// Header Image.
	api( 'header_image', function( value ) {
		value.bind( function( to ) {
			var body  = $(document.body);

			if( to != '' ) {
				// See get_body_class() in /wp-includes/post-template.php
				body.addClass( 'custom-header' );
				body.addClass( 'custom-header-image' );
			} else {
				body.removeClass( 'custom-header-image' );

				if( api.value( 'header_textcolor' )() == parent.top.wp.customize.control('header_textcolor').params.defaultValue ) {
					body.removeClass( 'custom-header' );
				}
			}
		} );
	} );

	// Header Text Color.
	api( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			var body  = $(document.body);

			if( to != '' ) {
				to = to.replace('#', '');

				if( to != parent.top.wp.customize.control('header_textcolor').params.defaultValue ) {
					// See get_body_class() in /wp-includes/post-template.php
					body.addClass( 'custom-header' );
					body.addClass( 'custom-header-textcolor' );

					if( 'blank' == to ) {
						body.addClass( 'custom-header-blank-textcolor' );
					}
				} else {
					body.removeClass( 'custom-header-textcolor' );
					body.removeClass( 'custom-header-blank-textcolor' );

					if( api.value( 'header_image' )() == '' ) {
						body.removeClass( 'custom-header' );
					}
				}
			}
		} );
	} );

	api( 'site_logo_url', function( value ) {
		value.bind( function( to ) {
			var container = $('.site-header .branding a');

			if( '' == to ) {
				container.find('.site-logo').remove();
				container.html( container.text() );
			} else {
				var text = container.find('.site-title-text');
				if( text.length == 0 ) {
					text = $('<span class="site-title-text"></span>').text( container.text() );
					container.html('');
					text.appendTo( container );
				}

				container.find('.site-logo').remove();
				$('<img>').addClass('site-logo').attr('src', to).prependTo( container );
			}
		} );
	} );

	api( 'web_fonts[subsets]', function( value ) {
		value.bind( function( to ) {
			var style = $('#enlightenment-web-fonts-css');

			if( ! style.length ) {
				return;
			}

			if( to.length == 0 ) {
				to = ['latin'];
			}

			var subsets = to.join();
			var href  = style.attr('href');

			if( href.indexOf('&subset=') != -1 ) {
				href = href.substring(0, href.indexOf('&subset='));
			}

			href += '&subset=' + subsets;

			style.attr('href', href);
		} );
	} );

	api( 'web_fonts[fonts]', function( value ) {
		value.bind( function( to ) {
			$.extend( args.web_fonts.fonts, to );
		} );
	} );

	parent.top.wp.customize.control.each( function( control ) {
		if( control.params.type == 'fontFamily' ) {
			api( control.id, function( value ) {
				value.bind( function( to ) {
					var head = $('head'),
						href = '',
						font = '';

					if( to in args.web_fonts.fonts ) {
						font = args.web_fonts.fonts[ to ]['family'].replace(' ', '+');
						href = '//fonts.googleapis.com/css?family=' + font + ':' + args.web_fonts.fonts[ to ]['variants'].join() + '&subset=' + args.web_fonts.subsets.join();

						$.get(href, function(response) {
							$('#enlightenment-' + control.id + '-web-font-css').remove();
							$('head').append('<style type="text/css" id="enlightenment-' + control.id + '-web-font-css">' + response + '</style>');

							// Refresh the stylesheet by removing and recreating it.
							if( typeof control.params.selector == 'string' && control.params.selector != '' ) {
								$('#enlightenment-' + control.id + '-inline-css').remove();

								var family = args.web_fonts.fonts[ to ]['family'];

								if( family.indexOf(' ') != -1 ) {
									family = '"' + family + '"';
								}

								$( '<style type="text/css" id="enlightenment-' + control.id + '-inline-css">' + control.params.selector + ' { font-family: ' + family + ', ' + args.web_fonts.fonts[ to ]['category'] + '; }</style>' ).appendTo( head );
							}
						});
					} else {
						// Refresh the stylesheet by removing and recreating it.
						if( typeof control.params.selector != 'undefined' ) {
							$('#enlightenment-' + control.id + '-fonts-inline-css').remove();

							$( '<style type="text/css" id="enlightenment-' + control.id + '-fonts-inline-css">' + control.params.selector + ' { font-family: ' + args.web_fonts.safe_fonts[ to ] + '; }</style>' ).appendTo( head );
						}
					}
				} );
			} );
		}
	} );

	if( typeof args.template_editor != 'undefined' ) {
		api.bind( 'preview-ready', function() {
			api.preview.bind( 'active', function() {
				api.preview.send( 'enlightenment-template-editor', args.template_editor );
			} );
		} );
	}
} )( jQuery );
