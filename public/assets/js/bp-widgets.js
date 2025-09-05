(function($) {
	var $document = $(document);

	function bp_profile_widget(widget) {
		widget.find('.wrapper-dropdown').each(function() {
			var dw = $(this),
				di = dw.find('ul.dropdown > li > a'),
				dd = new DropDown( dw );

			di.on('click',function( event ){
				var $this = $(this);

				$this.closest('.wrapper-dropdown').next('.edit-profile-field-icon').val( $this.data('icon') ).trigger('input').trigger('change');
			});
		});

		widget.find('select').on('change', function() {
			var val = $(this).val();

			$(this).parent().parent().children('.profile-group').each(function() {
				$(this).addClass('hidden');

				if( $(this).attr('id').substr( 14, $(this).attr('id').length ) == val ) {
					$(this).removeClass('hidden');
				}
			});
		});
	}

	$document.ready(function() {
		var widgets = $('#widgets-right').find('.widget[id*="enlightenment_bp_profile_widget"]');

		widgets.each(function() {
			var self = $(this);

			bp_profile_widget(self);
		});
	});

	$document.on('widget-added widget-updated', function(event, widget) {
		bp_profile_widget(widget);
	});
})(jQuery);
