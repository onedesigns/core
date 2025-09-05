(function($) {
	var $document = $(document);

	function widget_form( $widget ) {
		var $container          = $widget.find('.enlightenment-custom-query-widget'),
			widget_id           = $widget.find('.widget_number').val(),
			$type               = $container.children('.type'),
			$grid               = $container.children('.grid'),
			$query              = $container.children('.query'),
			$post_types         = $container.children('.post-types'),
			$post_type          = $container.children('.post-type'),
			$page               = $container.children('.page'),
			$pages              = $container.children('.pages'),
			$image_gallery      = $container.children('.image-gallery'),
			$author             = $container.children('.author'),
			$taxonomy           = $container.children('.taxonomy'),
			$term               = $container.children('.term'),
			$posts_count        = $container.children('.posts-count'),
			$lead_posts         = $container.children('.lead-posts'),
			$archive_link       = $container.children('.archive-link'),
			$archive_link_label = $container.children('.archive-link-label');

		$type.find('select').on('change', function() {
			var type  = $(this).val(),
				query = $query.find('select').val();

			switch ( type ) {
				case 'slider':
					$grid.hide();
					$lead_posts.hide();

					break;

				case 'carousel':
					$lead_posts.hide();

					switch ( query ) {
						case 'post_type':
						case 'page':
							$grid.hide();

							break;

						default:
							$grid.show();
					}

					break;

				default:
					switch ( query ) {
						case 'post_type':
						case 'page':
							$grid.hide();
							$lead_posts.hide();

							break;

						default:
							$grid.show();
							$lead_posts.show();
					}
			}
		});

		$query.find('select').on('change', function() {
			var query         = $(this).val(),
				$type_select  = $type.find('select'),
				type          = $type_select.val(),
				$carousel_opt = $type_select.children('option[value="carousel"]');

			switch ( query ) {
				case 'sticky_posts':
					$carousel_opt.show();
					$post_types.hide();
					$post_type.hide();
					$page.hide();
					$pages.hide();
					$image_gallery.hide();
					$author.hide();
					$taxonomy.hide();
					$term.hide();
					$posts_count.hide();
					$archive_link.hide();
					$archive_link_label.hide();

					switch ( type ) {
						case 'slider':
							$grid.hide();
							$lead_posts.hide();

							break;

						case 'carousel':
							$grid.show();
							$lead_posts.hide();

							break;

						default:
							$grid.show();
							$lead_posts.show();
					}

					break;

				case 'post_type_archive':
					$carousel_opt.show();
					$post_types.show();
					$post_type.hide();
					$page.hide();
					$pages.hide();
					$image_gallery.hide();
					$author.hide();
					$taxonomy.hide();
					$term.hide();
					$posts_count.show();
					$archive_link.show();

					switch ( type ) {
						case 'slider':
							$grid.hide();
							$lead_posts.hide();

							break;

						case 'carousel':
							$grid.show();
							$lead_posts.hide();

							break;

						default:
							$grid.show();
							$lead_posts.show();
					}

					if ( $archive_link.find('input').is(':checked') ) {
						$archive_link_label.show();
					}

					break;

				case 'post_type':
					$grid.hide();
					$post_types.show();
					$post_type.show();
					$page.hide();
					$pages.hide();
					$image_gallery.hide();
					$author.hide();
					$taxonomy.hide();
					$term.hide();
					$posts_count.hide();
					$lead_posts.hide();
					$archive_link.hide();
					$archive_link_label.hide();

					if ( type == 'carousel' ) {
						$type_select.val('list');
					}

					$carousel_opt.hide();

					break;

				case 'page':
					$grid.hide();
					$post_types.hide();
					$post_type.hide();
					$page.show();
					$pages.hide();
					$image_gallery.hide();
					$author.hide();
					$taxonomy.hide();
					$term.hide();
					$posts_count.hide();
					$lead_posts.hide();
					$archive_link.hide();
					$archive_link_label.hide();

					if ( type == 'carousel' ) {
						$type_select.val('list');
					}

					$carousel_opt.hide();

					break;

				case 'pages':
					$carousel_opt.show();
					$post_types.hide();
					$post_type.hide();
					$page.hide();
					$pages.show();
					$image_gallery.hide();
					$author.hide();
					$taxonomy.hide();
					$term.hide();
					$posts_count.hide();
					$archive_link.hide();
					$archive_link_label.hide();

					switch ( type ) {
						case 'slider':
							$grid.hide();
							$lead_posts.hide();

							break;

						case 'carousel':
							$grid.show();
							$lead_posts.hide();

							break;

						default:
							$grid.show();
							$lead_posts.show();
					}

					break;

				case 'gallery':
					$carousel_opt.show();
					$post_types.hide();
					$post_type.hide();
					$page.hide();
					$pages.hide();
					$image_gallery.show();
					$author.hide();
					$taxonomy.hide();
					$term.hide();
					$posts_count.hide();
					$archive_link.hide();
					$archive_link_label.hide();

					switch ( type ) {
						case 'slider':
							$grid.hide();
							$lead_posts.hide();

							break;

						case 'carousel':
							$grid.show();
							$lead_posts.hide();

							break;

						default:
							$grid.show();
							$lead_posts.show();
					}

					break;

				case 'author':
					$carousel_opt.show();
					$post_types.hide();
					$post_type.hide();
					$page.hide();
					$pages.hide();
					$image_gallery.hide();
					$author.show();
					$taxonomy.hide();
					$term.hide();
					$posts_count.show();
					$archive_link.show();

					switch ( type ) {
						case 'slider':
							$grid.hide();
							$lead_posts.hide();

							break;

						case 'carousel':
							$grid.show();
							$lead_posts.hide();

							break;

						default:
							$grid.show();
							$lead_posts.show();
					}

					if ( $archive_link.find('input').is(':checked') ) {
						$archive_link_label.show();
					}

					break;

				case 'taxonomy':
					$carousel_opt.show();
					$post_types.hide();
					$post_type.hide();
					$page.hide();
					$pages.hide();
					$image_gallery.hide();
					$author.hide();
					$taxonomy.show();
					$term.show();
					$posts_count.show();
					$archive_link.show();

					switch ( type ) {
						case 'slider':
							$grid.hide();
							$lead_posts.hide();

							break;

						case 'carousel':
							$grid.show();
							$lead_posts.hide();

							break;

						default:
							$grid.show();
							$lead_posts.show();
					}

					if ( $archive_link.find('input').is(':checked') ) {
						$archive_link_label.show();
					}

					break;
			}
		});

		$post_types.find('select').on('change', function() {
			var $post_s = $post_type.find('select'),
				data    = {
				action    : 'enlightenment_ajax_get_post_types',
				post_type : $(this).val(),
				name      : 'widget-enlightenment-custom-query[' + widget_id + '][p]',
				id        : 'widget-enlightenment-custom-query-' + widget_id + '-p',
				value     : $post_s.val(),
			}

			$.post(ajaxurl, data, function(r) {
				var html = document.createElement( 'div' );

				$(html).html(r);
				$post_s.html( $( 'select', $(html) ).html() );
			});
		});

		var file_frame;
		$image_gallery.find( '.upload-media-button' ).on( 'click', function( event ) {
			event.preventDefault();

			var upload_button = $( this );

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();

				return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media( {
				title: $( this ).data( 'uploader-title' ),
				button: {
					text: $( this ).data( 'uploader-button-text' ),
				},
				library: {
					type: $( this ).data( 'mime-type' ),
				},
				multiple: $( this ).data( 'multiple' ),  // Set to true to allow multiple files to be selected
			} );

			file_frame.on( 'open', function() {
				var selection = file_frame.state().get( 'selection' ),
					selected  = $image_gallery.find( '.upload-media-input' ).val();

				if ( selected ) {
					let attachments = selected.split( ',' );

					$.each( attachments, function( index, attachment ) {
						selection.add( wp.media.attachment( attachment ) );
					} )
				}
			} );

			file_frame.on( 'select', function() {
				var val         = [],
					attachments = file_frame.state().get( 'selection' ).toJSON();

				$( attachments ).each( function() {
					val.push( this.id );
				} );

				val = val.join();

				$image_gallery.find( '.upload-media-input' ).val( val ).trigger( 'change' );

				var $preview_media = $image_gallery.find( '.preview-media' );

				if ( $preview_media.length ) {
					$preview_media.html( '' );

					$( attachments ).each( function() {
						var data = {
							action:    'enlightenment_media_preview',
							id:        this.id,
							size:      upload_button.data( 'thumbnail' ),
							mime_type: 'image',
						}

						$.post( ajaxurl, data, function( r ) {
							var $image = $( r );

							$image.appendTo( $preview_media );

							if ( 0 == $preview_media.css( 'height' ) || '0px' == $preview_media.css( 'height' ) ) {
								$preview_media.css( 'height', 'auto' );
							}
						} );
					} );
				}
			} );


			file_frame.open();
		});

		$taxonomy.find('select').on('change', function() {
			var $term_s = $term.find('select'),
				data    = {
				action   : 'enlightenment_ajax_get_terms',
				taxonomy : $('.taxonomy select', $container).val(),
				name     : 'widget-enlightenment-custom-query[' + widget_id + '][term]',
				id       : 'widget-enlightenment-custom-query-' + widget_id + '-term',
				value    : $term_s.val(),
			}

			$.post(ajaxurl, data, function(r) {
				var html = document.createElement( 'div' );

				$(html).html(r);
				$term.remove();
				$taxonomy.after( $(html).html() );
				$term = $container.children('.term');
			});
		});

		$archive_link.find('input').each(function() {
			var query = $query.find('select').val();

			if( query == 'post_type_archive' || query == 'author' || query == 'taxonomy' ) {
				if( $(this).is(':checked') ) {
					$archive_link_label.show();
				} else {
					$archive_link_label.hide();
				}
			}
		});

		$archive_link.find('input').on('change', function() {
			if( $(this).is(':checked') ) {
				$archive_link_label.show();
			} else {
				$archive_link_label.hide();
			}
		});
	}

	$document.ready(function() {
		$('#widgets-right .widget[id*="enlightenment-custom-query"]').each(function() {
			widget_form( $(this) );
		});
	});

	$document.on('widget-added widget-updated widget-synced', function(event, $widget) {
		var $widget_id = $widget.find('[name="widget-id"]');

		if ( ! $widget_id.length ) {
			return true;
		}

		if ( $widget_id.val().indexOf('enlightenment-custom-query') != -1 ) {
			widget_form( $widget );
		}
	});
})(jQuery);
