(function ($) {
	var defaults = {
		type:         'GET',
		content:      '.entries-list',
		item:         '.hentry',
		navigation:   '.posts-navigation',
		next:         '.nav-previous a, .page-item a.next',
		navClass:     'ajax-navigation',
		navID:        'ajax-navigation',
		linkClass:    'ajax-navigation__next',
		spinnerClass: 'ajax-navigation__spinner',
		spinner:      '',
		labelClass:   'ajax-navigation__label',
		labelText:    'Load more posts',
		loadingClass: 'ajax-navigation__loading',
		loadingText:  'Loading the next set of posts &hellip;',
		data:         {},
		complete:     '',
	};

	$.fn.ajaxload = function( options ) {
		var settings  = $.extend( {}, defaults, options ),
			container = this,
			next      = $( settings.next, container ),
			ajaxurl   = next.attr( 'href' );

		if ( next.length ) {
			container.hide();

			var spinner = '';

			if ( settings.spinner != '' ) {
				spinner = '<span class="' + settings.spinnerClass + '" role="presentation" aria-hidden="true">' + settings.spinner + '</span>';
			}

			var label = '';

			if ( settings.label != '' ) {
				label = '<span class="' + settings.labelClass + '">' + settings.labelText + '</span>';
			}

			var loading = '';

			if ( settings.loading != '' ) {
				loading = '<span class="' + settings.loadingClass + '">' + settings.loadingText + '</span>';
			}

			container.after( '<div class="' + settings.navClass + '" id="' + settings.navID + '"><a class="' + settings.linkClass + '" href="' + next.attr( 'href' ) + '">' + spinner + label + loading + '</a></div>' );

			var ajax_nav  = container.next( '.ajax-navigation' ),
				ajax_link = ajax_nav.children( '.ajax-navigation__next' ),
				spinner   = ajax_link.children( '.ajax-navigation__spinner' ),
				loading   = ajax_link.children( '.ajax-navigation__loading' ),
				label     = ajax_link.children( '.ajax-navigation__label' );

			spinner.hide();
			loading.hide();

			ajax_link.on( 'click', function( event ) {
				event.preventDefault();

				$.ajax({
					type: settings.type,
					url:  ajaxurl,
					data: settings.data,
					beforeSend: function() {
						ajax_link.addClass('loading');
						spinner.show();
						loading.show();
						label.hide();
					},
				}).always(function() {
					ajax_link.removeClass('loading');
					spinner.hide();
					loading.hide();
					label.show();
				}).done(function( response ) {
					var url    = ajaxurl,
						helper = $( '<div></div>' );

					helper.html( response );

					if ( settings.type == 'GET' ) {
						var content = $( settings.content, helper );
					} else if( settings.type == 'POST' ) {
						var content = helper;
					}

					var items = $( settings.item, content );

					items.each(function() {
						var item      = $( this ),
							content   = $( settings.content ),
							last_item = $( settings.item, content ).last();

						last_item.after(item);
					});

					var posts_nav = $( settings.navigation, helper ),
						next      = $( settings.next, posts_nav ).attr( 'href' );

					if ( typeof next !== 'undefined' ) {
						ajaxurl = next;

						$( settings.next, container ).attr( 'href', next );

						ajax_link.attr( 'href', next );
					} else {
						ajax_nav.hide();
					}

					if( typeof settings.complete == 'function' ) {
						settings.complete( items, url, response );
					}
				});
			});
		}
	};
}(jQuery));
