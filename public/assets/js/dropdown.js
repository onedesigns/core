function DropDown( el ) {
    this.dd          = el;
    this.placeholder = this.dd.children('.current');
    this.opts        = this.dd.find('ul.dropdown > li > a');
    this.val         = '';
    this.index       = -1;

    this.initEvents();
}

DropDown.prototype = {
    initEvents : function() {
        var obj = this;

        obj.placeholder.on('click focus', function( event ) {
			if ( 'input' == event.target.tagName.toLowerCase() ) {
				obj.dd.addClass('active');
			} else {
	            obj.dd.toggleClass('active');
			}
        });

		obj.opts.on('focus',function( event ) {
			obj.dd.addClass('active');
		});

		obj.opts.on('blur',function( event ) {
			setTimeout(function() {
				if ( ! obj.dd.find(':focus').length ) {
					obj.dd.removeClass('active');
				}
			}, 15);
		});

		obj.opts.on('click',function( event ) {
			event.preventDefault();

			var opt = jQuery(this);

			obj.val   = opt.html();
			obj.index = opt.index();

            opt.closest('.dropdown').children('li').removeClass('selected');
            opt.parent().addClass('selected');
			opt.closest('.wrapper-dropdown').children('.current').html( obj.val );

			opt.blur();
			obj.dd.removeClass('active');
		});
    },
	getValue : function() {
		return this.val;
	},
	getIndex : function() {
		return this.index;
	}
}

jQuery( document ).ready(function($) {
	var $document = $( document );
	$document.on('click', function( event ) {
		if ( $( event.target ).closest('.wrapper-dropdown').length )  {
			return true;
		}

		// all dropdowns
		$('.wrapper-dropdown').removeClass('active');
	});

	$document.on('keydown', function( event ) {
		if ( event.keyCode === 27 )  {
			$('.wrapper-dropdown').each(function() {
				var $this = $(this);

				if ( $this.find(':focus') ) {
					$this.removeClass('active');

					event.stopPropagation();

					return false;
				}
			});
		}
	});
});
