jQuery( document ).ready(function( $ ) {
	if ( typeof $.fn.wpColorPicker != 'undefined' ) {
		$('.wp-color-picker').wpColorPicker();
	}

	if ( typeof $.fn.alphaColorPicker != 'undefined' ) {
		$('.alpha-color-picker').alphaColorPicker();
	}

	var file_frame;

	$( '.upload-media-button' ).on( 'click', function( event ) {
		event.preventDefault();

		var upload_button = $( this );

		// If the media frame already exists, reopen it.
		// if ( file_frame ) {
		// 	file_frame.open();
		// 	return;
		// }

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media( {
			title: $( this ).data( 'uploader-title' ),
			button: {
				text: $( this ).data( 'uploader-button-text' ),
			},
			library: {
				type: $( this ).data( 'mime-type' ),
			},
			multiple: $( this ).data( 'multiple' )  // Set to true to allow multiple files to be selected
		} );

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			// attachment = file_frame.state().get('selection').first().toJSON();

			var val = [];

			var attachments = file_frame.state().get( 'selection' ).toJSON();

			$( attachments ).each( function() {
				val.push(this.id);
			} );

			val = val.join();

			// Do something with attachment.id and/or attachment.url here
			$( 'input[type="hidden"]', upload_button.parent() ).val( val );

			if ( $( '.preview-media' ).length ) {
				$( attachments ).each(function() {
					attachment = this;

					var mime_type = attachment.mime.replace( '/' + attachment.subtype, '' );

					var data = {
						action:    'enlightenment_media_preview',
						id:        attachment.id,
						size:      upload_button.data( 'thumbnail' ),
						mime_type: mime_type,
					}

					$.post( ajaxurl, data, function( r ) {
						var preview_media = $( '.preview-media', upload_button.parent() );

						preview_media.html( r );

						if ( 0 == preview_media.css( 'height' ) || '0px' == preview_media.css( 'height' ) ) {
							preview_media.css('height', 'auto');
						}
					});
				});
			}

			$( '.remove-media-button', upload_button.parent() ).show();
		});

		// Finally, open the modal
		file_frame.open();
	} );

	$( '.remove-media-button' ).on('click', function( event ) {
		event.preventDefault();

		var remove_button = $( this );

		$( 'input[type="hidden"]', remove_button.parent() ).val( '' );
		$( '.preview-media', remove_button.parent() ).html( '' );

		remove_button.hide();
	} );

	$('.enlightenment-opacity-slider').each(function() {
		var $this  = $(this),
			$range = $this.children('input[type="range"]'),
			$value = $this.find('.enlightenment-opacity-value');

		$range.on('input', function() {
			$value.text( $(this).val() );
		});
	});

	$( '.background-options__bg-color' ).each( function() {
		var $this   = $(this),
			$label  = $this.prev( 'label' ),
			$picker = $this.find('.wp-color-picker'),
			$button = $this.find( '.wp-color-result' ),
			forAttr = $label.attr( 'for' );

		if ( forAttr != '' ) {
			$picker.removeAttr( 'id' );
			$button.attr( 'id', forAttr );
		}
	} );

	$('input[name="enlightenment_default_custom_layout"]').on('change', function() {
		if ( this.checked ) {
			$('.custom-layouts').slideUp();
		} else {
			$('.custom-layouts').slideDown();
		}
	});

	$('input[name="enlightenment_default_sidebar_locations"]').on('change', function() {
		console.log('enlightenment_default_sidebar_locations')
		if ( this.checked ) {
			$('.sidebar-locations').slideUp();
		} else {
			$('.sidebar-locations').slideDown();
		}
	});
});
