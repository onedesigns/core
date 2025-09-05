"use strict";

( function( exports, $ ) {
	var api  = wp.customize,
		args = enlightenment_customize_controls_args;

	api.SliderControl = api.Control.extend( {
		ready: function() {
			var control = this,
				nodes   = this.container.find('input'),
				range   = nodes.filter('[type="range"]'),
				number  = nodes.filter('[type="number"]');

			nodes.each( function () {
				var node    = $( this ),
					element = new api.Element( node );

				control.elements.push( element );
			} );

			range.on( 'input', function() {
				control.setting.set( this.value );
				number.val( this.value );
			} );

			number.on( 'input', function() {
				control.setting.set( this.value );
				range.val( this.value );
			} );

			control.setting.bind( function ( value ) {
				range.val(  value );
				number.val( value );
			} );
		},
	} );

	api.CheckboxesControl = api.Control.extend({
		ready: function() {
			var control = this,
				nodes   = this.container.find('input[type="checkbox"]');

			nodes.each( function () {
				var node    = $( this ),
					element = new api.Element( node );

				control.elements.push( element );
			} );

			nodes.on('change', function(event) {
				var values = [];

				nodes.each(function() {
					if ( this.checked ) {
						values.push( this.value );
					}
				} );

				control.setting.set( values );
			} );

			control.setting.bind( function ( value ) {
				nodes.each(function() {
					this.checked = ( -1 !== $.inArray( this.value, value ) );
				} );
			} );
		},
	} );

	api.ImageRadioControl = api.Control.extend({
		ready: function() {
			var control = this,
				nodes   = control.container.find( 'input[type="radio"]' ),
				element = new api.Element( nodes );

			control.elements.push( element );
			element.sync( control.setting );
			element.set( control.setting() );
		},
	} );

	api.PositionControl = api.Control.extend({
		ready: function() {
			var control = this,
				nodes   = control.container.find( 'input[type="radio"]' ),
				element = new api.Element( nodes );

			control.elements.push( element );
			element.sync( control.setting );
			element.set( control.setting() );
		},
	} );

	api.AlphaColorControl = api.Control.extend({
		ready: function() {
			var control      = this,
				value        = control.setting().replace( /\s+/g, '' ),
				updating     = false,
				node         = control.container.find( 'input[type="text"]' ),
				element      = new api.Element( node ),
				showOpacity  = true === node.data( 'showOpacity' ) ? true : false,
				defaultColor = node.data( 'defaultColor' ),
				alphaSlider;

			control.elements.push( element );

			/**
			 * Given an RGBa, RGB, or hex color value, return the alpha channel value.
			 */
			function get_alpha_value_from_color( value ) {
				var alphaVal;

				// Remove all spaces from the passed in value to help our RGBa regex.
				value = value.replace( / /g, '' );

				if ( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ ) ) {
					alphaVal = parseFloat( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ )[1] ).toFixed(2) * 100;
					alphaVal = parseInt( alphaVal );
				} else {
					alphaVal = 100;
				}

				return alphaVal;
			}

			/**
			 * Force update the alpha value of the color picker object and maybe the alpha slider.
			 */
			function update_alpha_value_on_color_control( alpha, node, alphaSlider, update_slider ) {
				var iris, colorPicker, color;

				iris = node.data( 'a8cIris' );
				colorPicker = node.data( 'wpWpColorPicker' );

				// Set the alpha value on the Iris object.
				iris._color._alpha = alpha;

				// Store the new color value.
				color = iris._color.toString();

				// Set the value of the input.
				node.val( color );

				// Update the background color of the color picker.
				colorPicker.toggler.css( {
					'background-color': color
				} );

				// Maybe update the alpha slider itself.
				if ( update_slider ) {
					update_alpha_value_on_alpha_slider( alpha, alphaSlider );
				}

				// Update the color value of the color picker object.
				node.wpColorPicker( 'color', color );
			}

			/**
			 * Update the slider handle position and label.
			 */
			function update_alpha_value_on_alpha_slider( alphaVal, alphaSlider ) {
				alphaSlider.slider( 'value', alphaVal );
				alphaSlider.find( '.ui-slider-handle' ).text( alphaVal.toString() );
			}

			node.val( control.setting() ).wpColorPicker({
				change: function( event, ui ) {
					var value = node.wpColorPicker( 'color' ),
						alpha = get_alpha_value_from_color( value );

					updating = true;
					control.setting.set( value );
					updating = false;

					update_alpha_value_on_alpha_slider( alpha, alphaSlider );

					if ( showOpacity ) {
						control.container.find( '.alpha-slider .ui-slider-handle' ).text( alpha );
					}

					control.container.find( '.transparency' ).css( 'background-color', ui.color.toString( 'no-alpha' ) );
				},
				clear: function() {
					updating = true;
					control.setting.set( '' );
					updating = false;

					if ( showOpacity ) {
						control.container.find( '.alpha-slider .ui-slider-handle' ).text( '' );
					}

					control.container.find( '.transparency' ).css( 'background-color', 'transparent' );
				},
				palettes: function() {
					var palette = $control.data( 'palette' );;

					// Process the palette.
					if ( -1 !== palette.indexOf( '|' ) ) {
						return palette.split( '|' );
					} else if ( 'false' == palette ) {
						return false;
					} else {
						return true;
					}
				}
			} );

			control.container.find( '.wp-picker-container' ).addClass( 'wp-picker-container-has-alpha' );

			// Insert our opacity slider.
			$( '<div class="alpha-color-picker-container">' +
					'<div class="min-click-zone click-zone"></div>' +
					'<div class="max-click-zone click-zone"></div>' +
					'<div class="alpha-slider"></div>' +
					'<div class="transparency"></div>' +
				'</div>' ).appendTo( control.container.find( '.wp-picker-holder' ) );

			alphaSlider = control.container.find( '.alpha-slider' );

			// Initialize jQuery UI slider with our options.
			alphaSlider.slider( {
				create: function( event, ui ) {
					var alphaVal = $( this ).slider( 'value' );

					// Set up initial values.
					$( this ).find( '.ui-slider-handle' ).text( alphaVal );
					$( this ).siblings( '.transparency ').css( 'background-color', value );
				},
				value: get_alpha_value_from_color( value ),
				range: 'max',
				step: 1,
				min: 0,
				max: 100,
				animate: false
			} );

			// Maybe show the opacity on the handle.
			if ( showOpacity ) {
				alphaSlider.find( '.ui-slider-handle' ).addClass( 'show-opacity' );
			}

			// Bind event handlers for the click zones.
			control.container.find( '.min-click-zone' ).on( 'click', function() {
				update_alpha_value_on_color_control( 0, node, alphaSlider, true );
			} );
			control.container.find( '.max-click-zone' ).on( 'click', function() {
				update_alpha_value_on_color_control( 100, none, alphaSlider, true );
			} );

			// Bind event handler for clicking on a palette color.
			node.find( '.iris-palette' ).on( 'click', function() {
				var color, alpha;

				color = $( this ).css( 'background-color' );
				alpha = get_alpha_value_from_color( color );

				update_alpha_value_on_alpha_slider( alpha, alphaSlider );

				// Sometimes Iris doesn't set a perfect background-color on the palette,
				// for example rgba(20, 80, 100, 0.3) becomes rgba(20, 80, 100, 0.298039).
				// To compensante for this we round the opacity value on RGBa colors here
				// and save it a second time to the color picker object.
				if ( 100 != alpha ) {
					color = color.replace( /[^,]+(?=\))/, ( alpha / 100 ).toFixed( 2 ) );
				}

				node.wpColorPicker( 'color', color );
			} );

			// Bind event handler for clicking on the 'Default' button.
			control.container.find( '.button.wp-picker-default' ).on( 'click', function() {
				update_alpha_value_on_alpha_slider( get_alpha_value_from_color( defaultColor ), alphaSlider );
			} );

			// Update all the things when the slider is interacted with.
			alphaSlider.slider().on( 'slide', function( event, ui ) {
				var alpha = parseFloat( ui.value ) / 100.0;

				update_alpha_value_on_color_control( alpha, node, alphaSlider, false );

				// Change value shown on slider handle.
				$( this ).find( '.ui-slider-handle' ).text( ui.value );
			} );

			control.setting.bind( function ( value ) {
				// Bail if the update came from the control itself.
				if ( updating ) {
					return;
				}

				node.val( value );
				node.wpColorPicker( 'color', value );
			} );

			// Collapse color picker when hitting Esc instead of collapsing the current section.
			control.container.on( 'keydown', function( event ) {
				var pickerContainer;

				if ( 27 !== event.which ) { // Esc.
					return;
				}

				pickerContainer = control.container.find( '.wp-picker-container' );

				if ( pickerContainer.hasClass( 'wp-picker-active' ) ) {
					node.wpColorPicker( 'close' );
					control.container.find( '.wp-color-result' ).focus();
					event.stopPropagation(); // Prevent section from being collapsed.
				}
			} );
		},
	} );

	api.EditableDropdownControl = api.Control.extend({
		ready: function() {
			var control  = this,
				id       = control.id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' ),
				ddwrap   = control.container.find('#dd-' + id),
				dropdown = ddwrap.children('.dropdown'),
				dditems  = dropdown.find('a'),
				dd       = new DropDown( ddwrap ),
				node     = control.container.find( 'input' ),
				element  = new api.Element( node );

			control.elements.push( element );
			element.sync( control.setting );
			element.set( control.setting() );

			node.on('input',function( event ) {
				dropdown.children('.selected').removeClass('selected');
				dropdown.find('a[data-value="' + this.value + '"]').parent().addClass('selected');
			} );

			node.on('change',function( event ) {
				var value = this.value;

				ddwrap.removeClass( 'invalid' );

				if ( control.params.accepted_units.length && value ) {
					if ( control.params.accepted_units.includes('') && ! isNaN( value ) ) {
						return true;
					}

					value = value.toString();

					var has_unit = false;

					_.each( control.params.accepted_units, function( unit ) {
						if ( ( -1 !== value.indexOf( unit ) ) && ( value.indexOf( unit ) === value.length - unit.length ) ) {
							has_unit = true;

							return false;
						}
					} );

					if ( ! has_unit ) {
						if ( isNaN( value ) ) {
							ddwrap.addClass( 'invalid' );
						} else {
							if ( ! control.params.default_unit ) {
								control.params.default_unit = control.params.accepted_units[0];
							}

							value += control.params.default_unit;

							node.val( value ).trigger('input').trigger('change');
						}
					}
				}
			} );

			dditems.on('click',function( event ) {
				control.setting.set( $(this).data('value') );
			} );

			control.setting.bind( function ( value ) {
				if ( ! node.is(':focus') && control.params.accepted_units.length && value ) {
					value = value.toString();

					var has_unit = false;

					_.each( control.params.accepted_units, function( unit ) {
						if ( ( -1 !== value.indexOf( unit ) ) && ( value.indexOf( unit ) === value.length - unit.length ) ) {
							has_unit = true;

							return false;
						}
					} );

					if ( ! has_unit ) {
						if ( isNaN( value ) ) {
							ddwrap.addClass( 'invalid' );
						} else {
							if ( ! control.params.default_unit ) {
								control.params.default_unit = control.params.accepted_units[0];
							}

							value += control.params.default_unit;

							control.setting.set( value );

							return false;
						}
					}
				}

				dropdown.children('.selected').removeClass('selected');
				dropdown.find('a[data-value="' + value + '"]').parent().addClass('selected');
			} );
		},
	} );

	api.FontFamilyControl = api.Control.extend({
		ready: function() {
			var control = this,
				nodes   = control.container.find( 'select' ),
				element = new api.Element( nodes );

			control.elements.push( element );
			element.sync( control.setting );
			element.set( control.setting() );
		},
	} );

	api.FontSizeControl = api.Control.extend({
		ready: function() {
			var control = this,
				range   = control.container.find( 'input[type="range"]' ),
				number  = control.container.find( 'input[type="number"]' );

			if ( control.params.accepted_units.length ) {
				let select = control.container.find( 'select' );

				range.on('input', function() {
					var unit = this.value ? select.val() : '';

					control.setting.set( this.value + unit );

					number.val( this.value );
				} );

				number.on('input', function() {
					var unit = this.value ? select.val() : '';

					control.setting.set( this.value + unit );

					range.val( this.value );
				} );

				select.on('change', function() {
					switch ( this.value ) {
						case 'px':
							range.prop(  'max', control.params.px_max );
							number.prop( 'max', control.params.px_max );

							range.prop(  'step',  control.params.px_step );
							number.prop( 'step',  control.params.px_step );

							if ( number.val() ) {
								range.val(  control.params.px_default );
								number.val( control.params.px_default );
							}

							break;

						default:
							range.prop(  'step', control.params.em_step );
							number.prop( 'step', control.params.em_step );

							range.prop(  'max', control.params.em_max );
							number.prop( 'max', control.params.em_max );

							if ( number.val() && 'px' == this.dataset.value ) {
								range.val(  control.params.em_default );
								number.val( control.params.em_default );
							}
					}

					this.dataset.value = this.value;

					var unit = number.val() ? this.value : '';

					control.setting.set( number.val() + unit );
				} );

				control.setting.bind( function ( value ) {
					var size = '',
						unit = '';

					$.each( control.params.accepted_units, function( index, accepted_unit ) {
						if ( value.indexOf( accepted_unit ) === value.length - accepted_unit.length ) {
							var numeric_value = value.slice( 0, -1 * accepted_unit.length );

							if ( '' === numeric_value ) {
								return true;
							} else if ( false === isNaN( numeric_value ) ) {
								size = numeric_value;
								unit = accepted_unit;

								return false;
							}
						}
					} );

					if ( '' === unit ) {
						unit = 'px';
					}

					range.val(  size );
					number.val( size );
					select.val( unit );
				} );
			} else {
				range.on('input', function() {
					control.setting.set( this.value );
					number.val( this.value );
				} );

				number.on('input', function() {
					control.setting.set( this.value );
					range.val( this.value );
				} );

				control.setting.bind( function ( value ) {
					range.val(  value );
					number.val( value );
				} );
			}
		},
	} );

	api.LineHeightControl = api.Control.extend({
		ready: function() {
			var control = this,
				range   = control.container.find( 'input[type="range"]' ),
				number  = control.container.find( 'input[type="number"]' );

			if ( control.params.accepted_units.length && '' !== control.params.accepted_units.toString() ) {
				let select = control.container.find( 'select' );

				range.on('input', function() {
					var unit = this.value ? select.val() : '';

					control.setting.set( this.value + unit );

					number.val( this.value );
				} );

				number.on('input', function() {
					var unit = this.value ? select.val() : '';

					control.setting.set( this.value + unit );

					range.val( this.value );
				} );

				select.on('change', function(event) {
					switch ( this.value ) {
						case 'px':
							range.prop(  'max', control.params.px_max );
							number.prop( 'max', control.params.px_max );

							range.prop(  'step',  control.params.px_step );
							number.prop( 'step',  control.params.px_step );

							if ( number.val() ) {
								range.val(  control.params.px_default );
								number.val( control.params.px_default );
							}

							break;

						default:
							range.prop(  'step', control.params.step );
							number.prop( 'step', control.params.step );

							range.prop(  'max', control.params.max );
							number.prop( 'max', control.params.max );

							if ( number.val() && 'px' == this.dataset.value ) {
								range.val(  control.params.default );
								number.val( control.params.default );
							}
					}

					this.dataset.value = this.value;

					var unit = number.val() ? this.value : '';

					control.setting.set( number.val() + unit );
				} );

				control.setting.bind( function ( value ) {
					var size = '',
						unit = '';

					if ( typeof value == 'number' ) {
						value = value.toString();
					}

					$.each( control.params.accepted_units, function( index, accepted_unit ) {
						if ( '' === accepted_unit && false === isNaN( value ) ) {
							size = value;

							return false;
						}

						if ( value.indexOf( accepted_unit ) === value.length - accepted_unit.length ) {
							var numeric_value = value.slice( 0, -1 * accepted_unit.length );

							if ( '' === numeric_value ) {
								return true;
							} else if ( false === isNaN( numeric_value ) ) {
								size = numeric_value;
								unit = accepted_unit;

								return false;
							}
						}
					} );

					range.val(  size );
					number.val( size );
					select.val( unit );
				} );
			} else {
				range.on('input', function() {
					control.setting.set( this.value );
					number.val( this.value );
				} );

				number.on('input', function() {
					control.setting.set( this.value );
					range.val( this.value );
				} );

				control.setting.bind( function ( value ) {
					range.val(  value );
					number.val( value );
				} );
			}
		},
	} );

	api.TextAlignControl = api.Control.extend({
		ready: function() {
			var control = this,
				nodes   = control.container.find( 'select' ),
				element = new api.Element( nodes );

			control.elements.push( element );
			element.sync( control.setting );
			element.set( control.setting() );
		},
	} );

	api.TextDecorationControl = api.Control.extend({
		ready: function() {
			var control = this,
				nodes   = control.container.find( 'select' ),
				element = new api.Element( nodes );

			control.elements.push( element );
			element.sync( control.setting );
			element.set( control.setting() );
		},
	} );

	api.TextTransformControl = api.Control.extend({
		ready: function() {
			var control = this,
				nodes   = control.container.find( 'select' ),
				element = new api.Element( nodes );

			control.elements.push( element );
			element.sync( control.setting );
			element.set( control.setting() );
		},
	} );

	api.SubsetsControl = api.Control.extend({
		ready: function() {
			var control = this,
				nodes   = this.container.find('input[type="checkbox"]');

			nodes.each( function () {
				var node    = $( this ),
					element = new api.Element( node );

				control.elements.push( element );
			} );

			nodes.on('change', function(event) {
				var values = [];

				nodes.each(function() {
					if ( this.checked ) {
						values.push( this.value );
					}
				} );

				if ( values.length == 0 ) {
					nodes.filter('[value="latin"]').prop('checked', true);

					values = ['latin'];
				}

				control.setting.set( values );
			} );

			control.setting.bind( function ( values ) {
				if ( values.length == 0 ) {
					values = ['latin'];

					control.setting.set( values );
				}

				nodes.each(function() {
					this.checked = ( -1 !== $.inArray( this.value, values ) );
				} );
			} );
		},
	} );

	api.WebFontsControl = api.Control.extend({
		ready: function() {
			var control = this,
				nodes   = control.container.find('input[type="checkbox"]'),
				values  = args.web_fonts.local;

			nodes.each( function () {
				var node    = $( this ),
					element = new api.Element( node );

				control.elements.push( element );

				api.control.each( function ( control ) {
					if (
						control.params.type == 'fontFamily'
						&&
						node.val() === control.setting()
					) {
						node.prop( 'disabled', true );
					}
				} );
			} );

			args.web_fonts.fonts = $.extend({}, values);

			nodes.on( 'change', function(event) {
				values = {};

				nodes.each( function() {
					if ( this.checked ) {
						var value = {};

						value[ this.value ] = args.web_fonts.local[ this.value ];

						$.extend( values, value );
					}
				} );

				control.setting.set( values );
			} );

			control.setting.bind( function ( values ) {
				nodes.each( function() {
					this.checked = ( this.value in values );
				} );
			} );

			build_font_list( control );
		},
	} );

	api.SidebarTitleControl = api.Control.extend({
		ready: function() {
			var control          = this,
				node             = control.container.find( 'input[type="text"]' ),
				element          = new api.Element( node ),
				sidebarID        = control.id.replace( 'sidebars[', '' ).replace( '][name]', '' ),
				section          = api.section( control.section() ),
				sectionTitle     = section.contentContainer.find('.customize-section-title h3'),
				customizeAction  = sectionTitle.children('.customize-action').clone(),
				sectionToggle    = section.headContainer.find('.accordion-section-title'),
				screenReaderText = sectionToggle.children('.screen-reader-text').clone();

			control.elements.push( element );
			element.sync( control.setting );
			element.set( control.setting() );

			control.container.find( 'input' ).on( 'input', function() {
				// A blank section title would break the layout
				if ( '' == this.value ) {
					return true;
				}

				sectionTitle.text( this.value );
				sectionTitle.prepend( customizeAction.clone() );
			} );

			control.container.find( 'input' ).on( 'change', function() {
				// A blank section title would break the layout
				if ( '' == this.value ) {
					return true;
				}

				sectionToggle.text( this.value );
				sectionToggle.append( screenReaderText.clone() );
			} );
		},
	} );

	api.AddSidebarControl = api.Control.extend({
		ready: function() {
			var control = this,
				node    = control.container.find('.create-sidebar'),
				element = new api.Element( node );

			control.elements.push( element );

			node.on( 'click', function() {
				var sidebarID           = 'sidebar-' + args.unlimited_sidebars.next_id,
					settingPrefix       = 'sidebars[' + sidebarID + ']',
					sidebarTitleControl = api.control( 'new_sidebar_name' ),
					sidebarTitle        = sidebarTitleControl.setting();

				if ( ! sidebarTitle ) {
					sidebarTitleControl.container.find( '#_customize-input-new_sidebar_name' ).addClass( 'invalid' ).focus();

					return true;
				}

				/**
				 * New sidebar section
				 */
				var section = new api.Section( 'sidebar-' + sidebarID, {
					panel:           'unlimited-sidebars',
					priority:        1000,
					title:           sidebarTitle,
					customizeAction: args.unlimited_sidebars.customizeAction,
				} );
				api.section.add( section );

				/**
				 * New sidebar title control
				 */
				var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.sidebar_title, 'text/html' ),
					label = doc.documentElement.textContent;

				var titleSetting = new api.Setting( settingPrefix + '[name]', '', {
					transport: 'postMessage',
				} );
				api.add( titleSetting );

				var titleControl = new api.SidebarTitleControl( settingPrefix + '[name]', {
					setting: settingPrefix + '[name]',
					type:    'sidebarTitle',
					section: 'sidebar-' + sidebarID,
					label:   label,
				} );
				api.control.add( titleControl );

				var nodes   = titleControl.container.find( 'input[type="text"]' ),
					element = new api.Element( nodes );

				titleControl.elements.push( element );
				element.sync( titleSetting );
				element.set( sidebarTitle );

				/**
				 * New sidebar display title control
				 */
				var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.display_title, 'text/html' ),
					label = doc.documentElement.textContent;

				var showTitleSetting = new api.Setting( settingPrefix + '[display_title]', '', {
					transport: 'postMessage',
				} );
				api.add( showTitleSetting );

				var showTitleControl = new api.Control( settingPrefix + '[display_title]', {
					setting: settingPrefix + '[display_title]',
					type:    'checkbox',
					section: 'sidebar-' + sidebarID,
					label:   label,
				} );
				api.control.add( showTitleControl );

				showTitleControl.setting.set( args.unlimited_sidebars.default_atts.display_title );

				/**
				 * New sidebar description control
				 */
				var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.description, 'text/html' ),
					label = doc.documentElement.textContent;

				var descriptionSetting = new api.Setting( settingPrefix + '[description]', '', {
					transport: 'postMessage',
				} );
				api.add( descriptionSetting );

				var descriptionControl = new api.Control( settingPrefix + '[description]', {
					setting: settingPrefix + '[description]',
					type:    'textarea',
					section: 'sidebar-' + sidebarID,
					label:   label,
				} );
				api.control.add( descriptionControl );

				descriptionControl.setting.set( args.unlimited_sidebars.default_atts.description );

				/**
				 * New sidebar display description control
				 */
				var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.display_description, 'text/html' ),
					label = doc.documentElement.textContent;

				var showDescriptionSetting = new api.Setting( settingPrefix + '[display_description]', '', {
					transport: 'postMessage',
				} );
				api.add( showDescriptionSetting );

				var showDescriptionControl = new api.Control( settingPrefix + '[display_description]', {
					setting: settingPrefix + '[display_description]',
					type:    'checkbox',
					section: 'sidebar-' + sidebarID,
					label:   label,
				} );
				api.control.add( showDescriptionControl );

				showDescriptionControl.setting.set( args.unlimited_sidebars.default_atts.display_description );

				/**
				 * New sidebar grid controls
				 */
				if ( args.unlimited_sidebars.grid ) {
					if ( args.unlimited_sidebars.bootstrap ) {
						let prev = '';

						$.each( args.unlimited_sidebars.breakpoints, function( breakpoint, label ) {
							var setting = settingPrefix + '[grid]' + '[' + breakpoint + ']',
								choices = $.extend( {}, args.unlimited_sidebars.grid_cols ),
								doc     = new DOMParser().parseFromString( label, 'text/html' );

							label = doc.documentElement.textContent;

							if ( '' != prev ) {
								choices = $.extend( {
									inherit: {
										src: '',
										alt: args.unlimited_sidebars.labels.grid_inherit_from.replace( '%s', prev ),
									},
								}, choices );
							}

							prev = label;

							var gridSetting = new api.Setting( setting, '', {
								transport: 'postMessage',
							} );
							api.add( gridSetting );

							var gridControl = new api.ImageRadioControl( setting, {
								setting: setting,
								type:    'imageRadio',
								section: 'sidebar-' + sidebarID,
								label:   label,
								choices: choices,
							} );
							api.control.add( gridControl );

							var nodes   = gridControl.container.find( 'input[type="radio"]' ),
								element = new api.Element( nodes );

							gridControl.elements.push( element );
							element.sync( gridSetting );
							element.set( args.unlimited_sidebars.default_atts.grid[ breakpoint ] );
						} );
					} else {
						var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.grid, 'text/html' ),
							label = doc.documentElement.textContent;

						var gridSetting = new api.Setting( settingPrefix + '[grid]', '', {
							transport: 'postMessage',
						} );
						api.add( gridSetting );

						var gridControl = new api.ImageRadioControl( settingPrefix + '[grid]', {
							setting: settingPrefix + '[grid]',
							type:    'imageRadio',
							section: 'sidebar-' + sidebarID,
							label:   label,
							choices: args.unlimited_sidebars.grid_cols,
						} );
						api.control.add( gridControl );

						var nodes   = gridControl.container.find( 'input[type="radio"]' ),
							element = new api.Element( nodes );

						gridControl.elements.push( element );
						element.sync( gridSetting );
						element.set( args.unlimited_sidebars.default_atts.grid );
					}
				}

				/**
				 * New sidebar contain widgets control
				 */
				if ( args.unlimited_sidebars.bootstrap ) {
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.contain_widgets, 'text/html' ),
						label = doc.documentElement.textContent;

					var containWidgetsSetting = new api.Setting( settingPrefix + '[contain_widgets]', '', {
						transport: 'postMessage',
					} );
					api.add( containWidgetsSetting );

					var containWidgetsControl = new api.Control( settingPrefix + '[contain_widgets]', {
						setting: settingPrefix + '[contain_widgets]',
						type:    'checkbox',
						section: 'sidebar-' + sidebarID,
						label:   label,
					} );
					api.control.add( containWidgetsControl );

					containWidgetsControl.setting.set( args.unlimited_sidebars.default_atts.contain_widgets );
				}

				/**
				 * New sidebar background controls
				 */
				if ( args.unlimited_sidebars.custom_sidebar_background ) {
					/**
					 * New sidebar background color control
					 */
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.sidebar_bg_color, 'text/html' ),
						label = doc.documentElement.textContent;

					var sidebarBgColorSetting = new api.Setting( settingPrefix + '[background][color]', '', {
						transport: 'postMessage',
					} );
					api.add( sidebarBgColorSetting );

					var sidebarBgColorControl = new api.ColorControl( settingPrefix + '[background][color]', {
						setting: settingPrefix + '[background][color]',
						type:    'color',
						section: 'sidebar-' + sidebarID,
						label:   label,
					} );
					api.control.add( sidebarBgColorControl );

					sidebarBgColorControl.setting.set( args.unlimited_sidebars.default_atts.background.color );

					/**
					 * New sidebar background image control
					 */
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.sidebar_bg_image, 'text/html' ),
						label = doc.documentElement.textContent;

					var sidebarBgImageSetting = new api.Setting( settingPrefix + '[background][image]', '', {
						transport: 'postMessage',
					} );
					api.add( sidebarBgImageSetting );

					var sidebarBgImageControl = new api.ImageControl( settingPrefix + '[background][image]', {
						setting: settingPrefix + '[background][image]',
						type:    'image',
						section: 'sidebar-' + sidebarID,
						label:   label,
						canUpload: args.unlimited_sidebars.canUpload,
						button_labels: args.unlimited_sidebars.button_labels,
					} );
					api.control.add( sidebarBgImageControl );

					sidebarBgImageControl.setting.set( args.unlimited_sidebars.default_atts.background.image );

					/**
					 * New sidebar background position control
					 */
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.sidebar_bg_position, 'text/html' ),
						label = doc.documentElement.textContent;

					var sidebarBgPositionSetting = new api.Setting( settingPrefix + '[background][position]', '', {
						transport: 'postMessage',
					} );
					api.add( sidebarBgPositionSetting );

					var sidebarBgPositionControl = new api.PositionControl( settingPrefix + '[background][position]', {
						setting: settingPrefix + '[background][position]',
						type:    'position',
						section: 'sidebar-' + sidebarID,
						label:   label,
					} );
					api.control.add( sidebarBgPositionControl );

					var nodes   = sidebarBgPositionControl.container.find( 'input[type="radio"]' ),
						element = new api.Element( nodes );

					sidebarBgPositionControl.elements.push( element );
					element.sync( sidebarBgPositionSetting );
					element.set( args.unlimited_sidebars.default_atts.background.position );

					/**
					 * New sidebar background repeat control
					 */
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.sidebar_bg_repeat, 'text/html' ),
						label = doc.documentElement.textContent;

					var sidebarBgRepeatSetting = new api.Setting( settingPrefix + '[background][repeat]', '', {
						transport: 'postMessage',
					} );
					api.add( sidebarBgRepeatSetting );

					var sidebarBgRepeatControl = new api.Control( settingPrefix + '[background][repeat]', {
						setting: settingPrefix + '[background][repeat]',
						type:    'select',
						section: 'sidebar-' + sidebarID,
						label:   label,
						choices: args.unlimited_sidebars.bg_repeat_choices,
					} );
					api.control.add( sidebarBgRepeatControl );

					sidebarBgRepeatControl.setting.set( args.unlimited_sidebars.default_atts.background.repeat );

					/**
					 * New sidebar background size control
					 */
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.sidebar_bg_size, 'text/html' ),
						label = doc.documentElement.textContent;

					var sidebarBgSizeSetting = new api.Setting( settingPrefix + '[background][size]', '', {
						transport: 'postMessage',
					} );
					api.add( sidebarBgSizeSetting );

					var sidebarBgSizeControl = new api.Control( settingPrefix + '[background][size]', {
						setting: settingPrefix + '[background][size]',
						type:    'select',
						section: 'sidebar-' + sidebarID,
						label:   label,
						choices: args.unlimited_sidebars.bg_size_choices,
					} );
					api.control.add( sidebarBgSizeControl );

					sidebarBgSizeControl.setting.set( args.unlimited_sidebars.default_atts.background.size );

					/**
					 * New sidebar background scroll control
					 */
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.sidebar_bg_scroll, 'text/html' ),
						label = doc.documentElement.textContent;

					var sidebarBgScrollSetting = new api.Setting( settingPrefix + '[background][scroll]', '', {
						transport: 'postMessage',
					} );
					api.add( sidebarBgScrollSetting );

					var sidebarBgScrollControl = new api.Control( settingPrefix + '[background][scroll]', {
						setting: settingPrefix + '[background][scroll]',
						type:    'select',
						section: 'sidebar-' + sidebarID,
						label:   label,
						choices: args.unlimited_sidebars.bg_scroll_choices,
					} );
					api.control.add( sidebarBgScrollControl );

					sidebarBgScrollControl.setting.set( args.unlimited_sidebars.default_atts.background.scroll );
				}

				/**
				 * New sidebar title color control
				 */
				if ( args.unlimited_sidebars.default_atts.sidebar_title_color ) {
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.sidebar_title_color, 'text/html' ),
						label = doc.documentElement.textContent;

					var sidebarTitleColorSetting = new api.Setting( settingPrefix + '[sidebar_title_color]', '', {
						transport: 'postMessage',
					} );
					api.add( sidebarTitleColorSetting );

					var sidebarTitleColorControl = new api.ColorControl( settingPrefix + '[sidebar_title_color]', {
						setting: settingPrefix + '[sidebar_title_color]',
						type:    'color',
						section: 'sidebar-' + sidebarID,
						label:   label,
					} );
					api.control.add( sidebarTitleColorControl );

					sidebarTitleColorControl.setting.set( args.unlimited_sidebars.default_atts.sidebar_title_color );
				}

				/**
				 * New sidebar text color control
				 */
				if ( args.unlimited_sidebars.default_atts.sidebar_text_color ) {
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.sidebar_text_color, 'text/html' ),
						label = doc.documentElement.textContent;

					var sidebarTextColorSetting = new api.Setting( settingPrefix + '[sidebar_text_color]', '', {
						transport: 'postMessage',
					} );
					api.add( sidebarTextColorSetting );

					var sidebarTextColorControl = new api.ColorControl( settingPrefix + '[sidebar_text_color]', {
						setting: settingPrefix + '[sidebar_text_color]',
						type:    'color',
						section: 'sidebar-' + sidebarID,
						label:   label,
					} );
					api.control.add( sidebarTextColorControl );

					sidebarTextColorControl.setting.set( args.unlimited_sidebars.default_atts.sidebar_text_color );
				}

				/**
				 * New sidebar widgets background controls
				 */
				if ( args.unlimited_sidebars.custom_widgets_background ) {
					/**
					 * New sidebar widgets background color control
					 */
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.widgets_bg_color, 'text/html' ),
						label = doc.documentElement.textContent;

					var widgetsBgColorSetting = new api.Setting( settingPrefix + '[widgets_background_color]', '', {
						transport: 'postMessage',
					} );
					api.add( widgetsBgColorSetting );

					var widgetsBgColorControl = new api.ColorControl( settingPrefix + '[widgets_background_color]', {
						setting: settingPrefix + '[widgets_background_color]',
						type:    'color',
						section: 'sidebar-' + sidebarID,
						label:   label,
					} );
					api.control.add( widgetsBgColorControl );

					widgetsBgColorControl.setting.set( args.unlimited_sidebars.default_atts.widgets_background_color );
				}

				/**
				 * New sidebar widgets title color control
				 */
				if ( args.unlimited_sidebars.default_atts.widgets_title_color ) {
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.widgets_title_color, 'text/html' ),
						label = doc.documentElement.textContent;

					var widgetsTitleColorSetting = new api.Setting( settingPrefix + '[widgets_title_color]', '', {
						transport: 'postMessage',
					} );
					api.add( widgetsTitleColorSetting );

					var widgetsTitleColorControl = new api.ColorControl( settingPrefix + '[widgets_title_color]', {
						setting: settingPrefix + '[widgets_title_color]',
						type:    'color',
						section: 'sidebar-' + sidebarID,
						label:   label,
					} );
					api.control.add( widgetsTitleColorControl );

					widgetsTitleColorControl.setting.set( args.unlimited_sidebars.default_atts.widgets_title_color );
				}

				/**
				 * New sidebar widgets text color control
				 */
				if ( args.unlimited_sidebars.default_atts.widgets_text_color ) {
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.widgets_text_color, 'text/html' ),
						label = doc.documentElement.textContent;

					var widgetsTextColorSetting = new api.Setting( settingPrefix + '[widgets_text_color]', '', {
						transport: 'postMessage',
					} );
					api.add( widgetsTextColorSetting );

					var widgetsTextColorControl = new api.ColorControl( settingPrefix + '[widgets_text_color]', {
						setting: settingPrefix + '[widgets_text_color]',
						type:    'color',
						section: 'sidebar-' + sidebarID,
						label:   label,
					} );
					api.control.add( widgetsTextColorControl );

					widgetsTextColorControl.setting.set( args.unlimited_sidebars.default_atts.widgets_text_color );
				}

				/**
				 * New sidebar widgets link color control
				 */
				if ( args.unlimited_sidebars.default_atts.widgets_link_color ) {
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.widgets_link_color, 'text/html' ),
						label = doc.documentElement.textContent;

					var widgetsLinkColorSetting = new api.Setting( settingPrefix + '[widgets_link_color]', '', {
						transport: 'postMessage',
					} );
					api.add( widgetsLinkColorSetting );

					var widgetsLinkColorControl = new api.ColorControl( settingPrefix + '[widgets_link_color]', {
						setting: settingPrefix + '[widgets_link_color]',
						type:    'color',
						section: 'sidebar-' + sidebarID,
						label:   label,
					} );
					api.control.add( widgetsLinkColorControl );

					widgetsLinkColorControl.setting.set( args.unlimited_sidebars.default_atts.widgets_link_color );
				}

				/**
				 * New sidebar widgets link hover color control
				 */
				if ( args.unlimited_sidebars.default_atts.widgets_link_hover_color ) {
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.widgets_link_hover_color, 'text/html' ),
						label = doc.documentElement.textContent;

					var widgetsLinkHoverColorSetting = new api.Setting( settingPrefix + '[widgets_link_hover_color]', '', {
						transport: 'postMessage',
					} );
					api.add( widgetsLinkHoverColorSetting );

					var widgetsLinkHoverColorControl = new api.ColorControl( settingPrefix + '[widgets_link_hover_color]', {
						setting: settingPrefix + '[widgets_link_hover_color]',
						type:    'color',
						section: 'sidebar-' + sidebarID,
						label:   label,
					} );
					api.control.add( widgetsLinkHoverColorControl );

					widgetsLinkHoverColorControl.setting.set( args.unlimited_sidebars.default_atts.widgets_link_hover_color );
				}

				/**
				 * New sidebar widgets link active color control
				 */
				if ( args.unlimited_sidebars.default_atts.widgets_link_active_color ) {
					var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.widgets_link_active_color, 'text/html' ),
						label = doc.documentElement.textContent;

					var widgetsLinkActiveColorSetting = new api.Setting( settingPrefix + '[widgets_link_active_color]', '', {
						transport: 'postMessage',
					} );
					api.add( widgetsLinkActiveColorSetting );

					var widgetsLinkActiveColorControl = new api.ColorControl( settingPrefix + '[widgets_link_active_color]', {
						setting: settingPrefix + '[widgets_link_active_color]',
						type:    'color',
						section: 'sidebar-' + sidebarID,
						label:   label,
					} );
					api.control.add( widgetsLinkActiveColorControl );

					widgetsLinkActiveColorControl.setting.set( args.unlimited_sidebars.default_atts.widgets_link_active_color );
				}

				/**
				 * New sidebar delete control
				 */
				var doc   = new DOMParser().parseFromString( args.unlimited_sidebars.labels.delete_sidebar, 'text/html' ),
					label = doc.documentElement.textContent;

				var deleteSidebarSetting = new api.Setting( settingPrefix + '[delete]', '', {
					transport: 'postMessage',
				} );
				api.add( deleteSidebarSetting );

				var deleteSidebarControl = new api.DeleteSidebarControl( settingPrefix + '[delete]', {
					setting:          settingPrefix + '[delete]',
					section:          'sidebar-' + sidebarID,
					label:            label,
					aria_label:       args.unlimited_sidebars.labels.aria_delete_sidebar,
					description:      args.unlimited_sidebars.labels.delete_sidebar_desc,
					cant_delete:      args.unlimited_sidebars.labels.cant_delete,
					default_sidebars: args.unlimited_sidebars.default_sidebars,
				} );
				api.control.add( deleteSidebarControl );

				deleteSidebarControl.setting.set( false );

				/**
				 * Open new sidebar section to allow user to edit further
				 */
				api.section( 'sidebar-' + sidebarID ).focus();

				/**
				 * Add new sidebar as an option for each location
				 */
				_.each( api.panel( 'unlimited-sidebars' ).sections(), function( section ) {
					if ( 0 !== section.id.indexOf( 'sidebar-locations-template-' ) ) {
						return true;
					}

					_.each( section.controls(), function( control ) {
						var option = $( document.createElement( 'option' ) );

						option.attr( 'value', sidebarID );
						option.text( sidebarTitle );

						control.container.find( 'select' ).append( option );
					} );
				} );

				api.control( 'new_sidebar_name' ).setting.set( '' );
				args.unlimited_sidebars.next_id++;
			} );
		},
	} );

	api.DeleteSidebarControl = api.Control.extend({
		ready: function() {
			var control   = this,
				node      = control.container.find('.delete-sidebar'),
				element   = new api.Element( node ),
				sidebarID = control.id.replace( 'sidebars[', '' ).replace( '][delete]', '' );

			control.elements.push( element );

			node.on( 'click', function() {
				if ( -1 !== $.inArray( sidebarID, args.unlimited_sidebars.default_sidebars ) ) {
					return true;
				}

				control.setting.set( true );

				api.section( control.section(), function( section ) {
					section.active.set( false );
				} );

				_.each( api.panel( 'unlimited-sidebars' ).sections(), function( section ) {
					if ( 0 !== section.id.indexOf( 'sidebar-locations-template-' ) ) {
						return true;
					}

					_.each( section.controls(), function( control ) {
						if ( control.setting() == sidebarID ) {
							control.setting.set( '' );
						}

						control.container.find('option[value="' + sidebarID + '"]').remove();
					} );
				} );
			} );

			control.setting.bind( function ( value ) {
				if ( -1 !== $.inArray( sidebarID, args.unlimited_sidebars.default_sidebars ) ) {
					control.setting.set( false );
				} else if ( true === value ) {
					api.section( control.section(), function( section ) {
						section.active.set( false );
					});

					_.each( api.panel( 'unlimited-sidebars' ).sections(), function( section ) {
						if ( 0 !== section.id.indexOf( 'sidebar-locations-template-' ) ) {
							return true;
						}

						_.each( section.controls(), function( control ) {
							if ( control.setting() == sidebarID ) {
								control.setting.set( '' );
							}

							control.container.find('option[value="' + sidebarID + '"]').remove();
						} );
					} );
				}
			} );
		},
	} );

	api.SidebarLocationControl = api.Control.extend({
		ready: function() {
			var control  = this,
				node     = control.container.find( 'select' ),
				element  = new api.Element( node ),
				add_btn  = control.container.find( '.create-sidebar' ),
				edit_btn = control.container.find( '.edit-sidebar' );

			control.elements.push( element );
			element.sync( control.setting );
			element.set( control.setting() );

			node.on( 'change', function() {
				// control.setting.set( this.value );

				if ( this.value ) {
					add_btn.addClass( 'hidden' );
					edit_btn.removeClass( 'hidden' );
				} else {
					add_btn.removeClass( 'hidden' );
					edit_btn.addClass( 'hidden' );
				}
			} );

			add_btn.on( 'click', function() {
				api.section( 'create-sidebar' ).focus();
			} );

			edit_btn.on( 'click', function() {
				var sidebarID = control.setting();

				if ( sidebarID ) {
					api.section( 'sidebar-' + sidebarID ).focus();
				}
			} );
		},
	} );

	api.ExportControl = api.Control.extend({
		ready: function() {
			var control  = this,
				link     = this.container.find('a');

			link.on('click', function(event) {
				if ( ! api.state( 'saved' )() ) {
					link.attr('target', '_blank');
				} else {
					link.removeAttr('target');
				}
			} );
		},
	} );

	api.ImportControl = api.UploadControl.extend({

		/**
		 * Callback handler for when an attachment is selected in the media modal.
		 * Gets the selected image information, and sets it within the control.
		 */
		select: function() {
			// Get the attachment from the modal frame.
			var node,
				attachment = this.frame.state().get( 'selection' ).first().toJSON(),
				mejsSettings = window._wpmejsSettings || {},
				media = ['image', 'media', 'upload', 'background', 'cropped_image', 'site_icon', 'header'];

			this.params.attachment = attachment;

			$.get( attachment.url, function( data ) {
				api.control.each( function ( control ) {
					if ( control.id in data ) {
						control.setting.set( data[ control.id ] );

						if ( -1 !== $.inArray( control.params.type, media ) ) {
							control.params.attachment = control.params.defaultAttachment;
						}
					}
				} );
			} );

			// No need for audio/video previews.
			this.cleanupPlayer();
		},
	} );

	api.ResetControl = api.Control.extend({
		ready: function() {
			var control  = this,
				button   = this.container.find('.reset-button'),
				section  = control.section(),
				media = ['image', 'media', 'upload', 'background', 'cropped_image', 'site_icon', 'header'];

			button.on('click', function(event) {
				event.preventDefault();
				event.stopPropagation();

				_.each( api.section( section ).controls(), function( control ) {
					control.setting.set( args.defaults[ control.id ] );

					if( -1 !== $.inArray( control.params.type, media ) ) {
						control.restoreDefault( event );
					}
				} );
			} );
		},
	} );

	$.extend( api.controlConstructor, {
		checkboxes:       api.CheckboxesControl,
		imageRadio:       api.ImageRadioControl,
		position:         api.PositionControl,
		slider:           api.SliderControl,
		alphaColor:       api.AlphaColorControl,
		editableDropdown: api.EditableDropdownControl,
		fontSize:         api.FontSizeControl,
		fontFamily:       api.FontFamilyControl,
		lineHeight:       api.LineHeightControl,
		textAlign:        api.TextAlignControl,
		textDecoration:   api.TextDecorationControl,
		textTransform:    api.TextTransformControl,
		subsets:          api.SubsetsControl,
		webfonts:         api.WebFontsControl,
		sidebarTitle:     api.SidebarTitleControl,
		addSidebar:       api.AddSidebarControl,
		deleteSidebar:    api.DeleteSidebarControl,
		sidebarLocation:  api.SidebarLocationControl,
		import:           api.ImportControl,
		export:           api.ExportControl,
		reset:            api.ResetControl,
	});

	api( 'web_fonts[host_locally]', function( value ) {
		value.bind( function( to ) {
			wp.customize.control( 'web_fonts[provider]' ).active.set( ! to );
		} );
	} );

	api( 'web_fonts[subsets]', function( value ) {
		value.bind( function( to ) {
			if( api.value('web_fonts[filter_by_subsets]')() ) {
				var control = wp.customize.control( 'web_fonts[fonts]' );
				build_font_list( control, true );
			}
		} );
	} );

	api( 'web_fonts[filter_by_subsets]', function( value ) {
		value.bind( function( to ) {
			var control = wp.customize.control( 'web_fonts[fonts]' );
			build_font_list( control, true );
		} );
	} );

	api( 'web_fonts[sort_fonts_by]', function( value ) {
		value.bind( function( to ) {
			var control = wp.customize.control( 'web_fonts[fonts]' );
			build_font_list( control, true );
		} );
	} );

	api( 'web_fonts[fonts]', function( value ) {
		value.bind( function( to ) {
			var options = {},
				option;

			$.each(args.web_fonts.safe_fonts, function( font, family ) {
				option = {};
				option[ font ] = family.replace(/"/g, '');
				$.extend( options, option );
			});

			$.each(to, function( font, atts ) {
				option = {};
				option[ font ] = atts.family + ', ' + atts.category;
				$.extend( options, option );
			});

			api.control.each( function ( control ) {
				if ( control.params.type == 'fontFamily' ) {
					var select = control.container.find('select'),
						option;

					select.html('');
					$.each(options, function( value, label ) {
						option = $('<option></option>').attr('value', value).text(label);
						if ( control.setting() == value ) {
							option.attr('selected', 'selected');
						}
						select.append( option );
					} );
				}
			} );
		} );
	} );

	api.bind( 'ready', function() {
		var body = $( 'body' );

		api.panel.each( function( panel ) {
			if ( panel.id == 'custom-layouts' ) {
				_.each( panel.sections(), function( section ) {
					var template = section.id.replace( 'custom-layouts-template-', '' );

					section.expanded.bind( function( isExpanded ) {
						if ( isExpanded && typeof args.custom_layouts.templates[ template ] != 'undefined' && args.custom_layouts.templates[ template ].url != '' ) {
							api.previewer.previewUrl.set( args.custom_layouts.templates[ template ].url );
						}
					} );
				} );
			} else if ( panel.id == 'grid-loop' ) {
				_.each( panel.sections(), function( section ) {
					var template = section.id.replace( 'grid-loop-template-', '' );

					section.expanded.bind( function( isExpanded ) {
						if ( isExpanded && typeof args.grid_loop.templates[ template ] != 'undefined' && args.grid_loop.templates[ template ].url != '' ) {
							api.previewer.previewUrl.set( args.grid_loop.templates[ template ].url );
						}
					} );
				} );
			} else if ( panel.id == 'unlimited-sidebars' ) {
				_.each( panel.sections(), function( section ) {
					if ( 0 !== section.id.indexOf( 'sidebar-locations-template-' ) ) {
						return true;
					}

					var template = section.id.replace( 'sidebar-locations-template-', '' );

					section.expanded.bind( function( isExpanded ) {
						if ( isExpanded && typeof args.unlimited_sidebars.templates[ template ] != 'undefined' && args.unlimited_sidebars.templates[ template ].url != '' ) {
							api.previewer.previewUrl.set( args.unlimited_sidebars.templates[ template ].url );
						}
					} );
				} );
			// } else if ( panel.id.indexOf( 'edit-template-' ) === 0 ) {
			} else if ( panel.id == 'template-editor' ) {
				// var template = panel.id.replace( 'edit-template-', '' );

				_.each( panel.sections(), function( section ) {
					var template = section.id.replace( 'edit-template-', '' );
					// var hook = section.id.replace( 'template-' + template + '-hook-', '' );

					section.expanded.bind( function( isExpanded ) {
						if ( isExpanded ) {
							if (
								typeof args.template_editor.templates[ template ] != 'undefined'
								&&
								args.template_editor.templates[ template ].url    != ''
							) {
								api.previewer.previewUrl.set( args.template_editor.templates[ template ].url );
							}
						} else {
							if ( body.hasClass('reordering-template-functions') ) {
								body.removeClass('reordering-template-functions');

								_.each( section.controls(), function( control ) {
									if ( ! control.container.hasClass('reordering') ) {
										return true;
									}

									control.container.removeClass('reordering');

									return false;
								} );
							}

							if ( body.hasClass('hooking-template-functions') ) {
								body.removeClass('hooking-template-functions');

								_.each( section.controls(), function( control ) {
									if ( ! control.container.hasClass('editing') ) {
										return true;
									}

									var hook      = control.id.replace( 'template_hooks[' + template + '][', '' ).slice( 0, -1 ),
										sidepanel = $( '#available-functions-template_hooks-' + template + '-' + hook );

									sidepanel.removeClass('visible');
									control.container.removeClass('editing');

									return false;
								} );
							}
						}
					} );

					var addNewFunctionBtns = section.container.find('.add-new-function');

					_.each( section.controls(), function( control ) {
						var hook      = control.id.replace( 'template_hooks[' + template + '][', '' ).slice( 0, -1 ),
							sidepanel = $( '#available-functions-template_hooks-' + template + '-' + hook ),
							available = sidepanel.children('.available-functions-list' ),
							hooked    = control.container.children('.hooked-functions-list');

						hooked.sortable( {
							items       : '> .function-tpl',
							handle      : '.function-top',
							axis        : 'y',
							tolerance   : 'pointer',
							update      : function() {
								control.setting( hooked.sortable( 'toArray', {
									attribute : 'data-function-name',
								} ).join( ',' ) );
							},
						} );

						hooked.on( 'click', '.move-function-down', function( event ) {
							event.preventDefault();

							var item   = $( this ).closest( '.function-tpl' ),
								widget = hooked.data( 'ui-sortable' );

							item.insertAfter( item.next() );

							hooked.trigger('sortupdate');
							widget._trigger( 'update', null, widget._uiHash( widget ) );
						} );

						hooked.on( 'click', '.move-function-up', function( event ) {
							event.preventDefault();

							var item   = $( this ).closest( '.function-tpl' ),
								widget = hooked.data( 'ui-sortable' );

							item.insertBefore( item.prev() );

							hooked.trigger('sortupdate');
							widget._trigger( 'update', null, widget._uiHash( widget ) );
						} );

						hooked.on( 'click', '.item-delete', function( event ) {
							event.preventDefault();

							var widget = hooked.data( 'ui-sortable' );

							$( this ).closest( '.function-tpl' ).appendTo( available );

							hooked.trigger('sortupdate');
							widget._trigger( 'update', null, widget._uiHash( widget ) );
						} );

						control.container.children( '.add-new-function' ).on( 'click', function( event ) {
							event.preventDefault();

							var $this = $(this);

							if ( body.hasClass('reordering-template-functions') ) {
								body.removeClass('reordering-template-functions');

								_.each( section.controls(), function( control ) {
									if ( ! control.container.hasClass('reordering') ) {
										return true;
									}

									control.container.removeClass('reordering');

									return false;
								} );
							}

							if ( body.hasClass('hooking-template-functions') && ! control.container.hasClass('editing') ) {
								body.removeClass('hooking-template-functions');

								_.each( section.controls(), function( control ) {
									if ( ! control.container.hasClass('editing') ) {
										return true;
									}

									var hook      = control.id.replace( 'template_hooks[' + template + '][', '' ).slice( 0, -1 ),
										sidepanel = $( '#available-functions-template_hooks-' + template + '-' + hook );

									sidepanel.removeClass('visible');
									control.container.removeClass('editing');

									return false;
								} );
							}

							if ( control.container.hasClass('editing') ) {
								body.removeClass('hooking-template-functions');
								sidepanel.removeClass('visible');
								control.container.removeClass('editing');
							} else {
								body.addClass('hooking-template-functions');
								sidepanel.addClass('visible');
								control.container.addClass('editing');
							}
						} );

						control.container.children( '.reorder-functions' ).on( 'click', function( event ) {
							event.preventDefault();

							if ( control.container.hasClass('reordering') ) {
								body.removeClass('reordering-template-functions');
								control.container.removeClass( 'reordering' );
							} else {
								if ( body.hasClass('reordering-template-functions') ) {
									body.removeClass('reordering-template-functions');

									_.each( section.controls(), function( control ) {
										if ( ! control.container.hasClass('reordering') ) {
											return true;
										}

										control.container.removeClass('reordering');

										return false;
									} );
								}

								if ( body.hasClass('hooking-template-functions') ) {
									body.removeClass('hooking-template-functions');

									_.each( section.controls(), function( control ) {
										if ( ! control.container.hasClass('editing') ) {
											return true;
										}

										var hook      = control.id.replace( 'template_hooks[' + template + '][', '' ).slice( 0, -1 ),
											sidepanel = $( '#available-functions-template_hooks-' + template + '-' + hook );

										sidepanel.removeClass('visible');
										control.container.removeClass('editing');

										return false;
									} );
								}

								body.addClass('reordering-template-functions');
								control.container.addClass( 'reordering' );
							}
						} );

						available.on( 'click', '.function-tpl', function( event ) {
							event.preventDefault();

							var widget = hooked.data( 'ui-sortable' );

							$(this).appendTo( hooked );

							hooked.trigger( 'sortupdate' );
							widget._trigger( 'update', null, widget._uiHash( widget ) );
						} );
					} );
				} );
			}
		} );

		var menuItemDropdown = function ( control ) {
			if ( control.params.type != 'nav_menu_item' ) {
				return true;
			}

			if ( control.hasDropdown ) {
				return true;
			}

			var id       = control.id.replace('nav_menu_item[', '').replace(']', ''),
				ddwrap   = control.container.find('#dd-' + id),
				current  = ddwrap.children('.current'),
				dropdown = ddwrap.children('.dropdown'),
				di       = ddwrap.find('ul.dropdown > li > a'),
				dd       = new DropDown( ddwrap ),
				node     = control.container.find('input[name="menu-item-icon[' + id + ']"]');

			node.on('change', function() {
				var setting = { ...control.setting() };

				setting.icon = this.value;

				control.setting.set( setting );
			} );

			di.on('click',function( event ) {
				var $this = $(this);

				$this.closest('.wrapper-dropdown').next('.edit-menu-item-icon').val( $this.data('icon') ).trigger('input').trigger('change');
			} );

			control.setting.bind( function ( value ) {
				node.val( value.icon );

				current.text( ' ' + args.menu_icons.icons[ value.icon ] );
				current.prepend( '<span class="' + args.menu_icons.prefix + value.icon + '"></span>' );

				dropdown.children('.selected').removeClass('selected');
				dropdown.find('a[data-icon="' + value.icon + '"]').parent().addClass('selected');
			} );

			control.hasDropdown = true;
		};

		_.each( api.panel('nav_menus').sections(), function ( section ) {
			section.expanded.bind( function( isExpanding ) {
				if ( isExpanding ) {
					_.each( section.controls(), menuItemDropdown );
				}
			} );
		} );

		api.control.bind( 'add', menuItemDropdown );

		api.control.each( function ( control ) {
			if ( control.params.type == 'fontFamily' ) {
				control.setting.bind( function ( value, previousValue ) {
					var isPreviousValueUsed = false;

					api.control.each( function ( ctrl ) {
						if (
							ctrl.params.type == 'fontFamily'
							&&
							ctrl.id !== control.id
							&&
							ctrl.setting() === previousValue
						) {
							isPreviousValueUsed = true;

							return false;
						}
					} );

					api.control.each( function ( ctrl ) {
						if ( ctrl.params.type == 'webfonts' ) {
							var nodes = ctrl.container.find('input[type="checkbox"]:checked');

							nodes.each( function() {
								if ( this.value === value ) {
									this.disabled = true;
								}

								if (
									! isPreviousValueUsed
									&&
									this.value === previousValue
								) {
									this.disabled = false;
								}
							} );
						}
					} );
				} );
			}
		} );

		if ( typeof args.unlimited_sidebars != 'undefined' ) {
			if ( args.unlimited_sidebars.first_sidebar ) {
				api.section( 'sidebar-' + args.unlimited_sidebars.first_sidebar, function( section ) {
					section.headContainer.prepend(
						wp.template( 'unlimited-sidebars-sidebars-header' )( args.unlimited_sidebars )
					);
				} );
			}

			if ( args.unlimited_sidebars.first_template ) {
				api.section( 'sidebar-locations-template-' + args.unlimited_sidebars.first_template, function( section ) {
					section.headContainer.prepend(
						wp.template( 'unlimited-sidebars-locations-header' )( args.unlimited_sidebars )
					);
				} );
			}
		}

		api.previewer.bind( 'enlightenment-template-editor', function( settings ) {
			// var template = settings.template;

			_.each( api.panel( 'template-editor' ).sections(), function( section ) {
				var template = section.id.replace( 'edit-template-', '' );

				_.each( section.controls(), function( control ) {
					var setting   = control.setting(),
						hook      = control.id.replace( 'template_hooks[' + template + '][', '' ).slice( 0, -1 ),
						functions = settings.hooks[ hook ],
						available = $('#available-functions-template_hooks-' + template + '-' + hook + ' .available-functions-list'),
						hooked    = control.container.children('.hooked-functions-list'),
						widget    = hooked.data( 'ui-sortable' );

					if ( setting !== '' ) {
						return true;
					}

					if ( functions.length ) {
						_.each( functions, function( func ) {
							available.children( '.function-tpl' ).each(function() {
								var self = $( this );

								if( self.data( 'function-name' ) == func ) {
									self.appendTo( hooked );
								}
							});
						} );

						hooked.removeClass( 'empty' );

						hooked.trigger( 'sortupdate' );
						widget._trigger( 'update', null, widget._uiHash( widget ) );
					}

					control.container.removeClass( 'loading' );
				} );
			} );
		} );
	} );

	function build_font_list( control, refresh ) {
		if ( typeof control == 'undefined' ) {
			return;
		}

		if ( typeof refresh == 'undefined' ) {
			refresh = false;
		}

		var sort_fonts_by     = api.value('web_fonts[sort_fonts_by]')(),
			filter_by_subsets = api.value('web_fonts[filter_by_subsets]')(),
			subsets           = api.value('web_fonts[subsets]')(),
			fonts             = api.value('web_fonts[fonts]')(),
			available_fonts   = Object.keys( fonts ),
			local             = args.web_fonts.local,
			fontlist          = control.container.find('.google-fonts-list'),
			fonts_in_use      = [],
			font, subset, cont, checkbox, input_id, input, nodes, label;

		if ( refresh ) {
			fontlist.html( '' );

			$.each( local, function( i, font ) {
				if ( filter_by_subsets ) {
					cont = false;

					$.each( subsets, function( j, subset ) {
						if ( -1 === $.inArray( subset, font.subsets ) ) {
							cont = true;
						}
					} );

					if ( cont ) {
						return true;
					}
				}

				let family = {};

				family[ font.family ] = {
					family:   font.family,
					category: font.category,
					variants: font.variants,
					subsets:  font.subsets,
				};

				$.extend( args.web_fonts.fonts, family );

				checkbox = $('<span class="customize-inside-control-row"></span>');
				input_id = '_customize-input-' + control.id + '-checkbox-' + font.family.replaceAll(' ', '-');
				input    = $('<input id="' + input_id + '" type="checkbox" value="' + font.family + '" name="_customize-checkboxes-web_fonts[fonts][]"' + ( -1 !== $.inArray( font.family, available_fonts ) ? ' checked="checked"' : '' ) +' />');
				label    = $('<label for="' + input_id + '">' + font.family + '</label>');

				input.appendTo(checkbox);
				$( document.createTextNode( ' ' ) ).appendTo(checkbox);
				label.appendTo(checkbox);
				checkbox.appendTo(fontlist);

				input.on( 'change', function(event) {
					var values = {};

					fontlist.find( 'input[type="checkbox"]' ).each( function() {
						if ( $(this).is(':checked') ) {
							var value = {};
							value[ this.value ] = args.web_fonts.fonts[ this.value ];
							$.extend(values, value);
						}
					} );

					control.setting.set( values );
				} );
			} );

			nodes = control.container.find('input[type="checkbox"]');

			control.setting.bind( function ( values ) {
				nodes.each( function() {
					this.checked = ( this.value in values );
				} );
			} );

			api.control.each( function ( control ) {
				if ( control.params.type == 'fontFamily' ) {
					fonts_in_use.push( control.setting() );
				}
			} );

			nodes.each( function() {
				if ( -1 !== $.inArray( this.value, fonts_in_use ) ) {
					this.disabled = true;
				}
			} );
		}

		function append_ajax_fonts( fonts ) {
			fontlist.find( 'h3' ).remove();

			switch ( sort_fonts_by ) {
				case 'alpha':
					fonts.sort( function( a, b ) {
						return a.family.localeCompare( b.family );
					} );

					break;

				case 'date':
					fonts.sort( function( a, b ) {
						var date_a = new Date( a.lastModified ),
							date_b = new Date( b.lastModified );

						if ( date_a.getTime() > date_b.getTime() ) {
							return -1;
						}

						if ( date_a.getTime() < date_b.getTime() ) {
							return 1;
						}

						return 0;
					} );

					break;

				case 'style':
					fonts.sort( function( a, b ) {
						if ( a.variants.length > b.variants.length ) {
							return -1;
						}

						if ( a.variants.length < b.variants.length ) {
							return 1;
						}

						return 0;
					} );

					break;

				case 'subset':
					fonts.sort( function( a, b ) {
						if ( a.subsets.length > b.subsets.length ) {
							return -1;
						}

						if ( a.subsets.length < b.subsets.length ) {
							return 1;
						}

						return 0;
					} );

					break;

				case 'popularity':
				default:
					fonts.sort( function( a, b ) {
						if ( a.popularity < b.popularity ) {
							return -1;
						}

						if ( a.popularity > b.popularity ) {
							return 1;
						}

						return 0;
					} );
			}

			$.each( fonts, function( i, font ) {
				if ( font.family in local ) {
					return true;
				}

				if ( filter_by_subsets ) {
					cont = false;

					$.each( subsets, function( j, subset ) {
						if ( -1 === $.inArray( subset, font.subsets ) ) {
							cont = true;
						}
					} );

					if ( cont ) {
						return true;
					}
				}

				var family = {};
				family[ font.family ] = {
					family:   font.family,
					category: font.category,
					variants: font.variants,
					subsets:  font.subsets,
				};

				$.extend( args.web_fonts.fonts, family );

				checkbox = $('<span class="customize-inside-control-row"></span>');
				input_id = '_customize-input-' + control.id + '-checkbox-' + font.family.replaceAll( ' ', '-' );
				input    = $('<input id="' + input_id + '" type="checkbox" value="' + font.family + '" name="_customize-checkboxes-web_fonts[fonts][]"' + ( -1 !== $.inArray( font.family, available_fonts ) ? ' checked="checked"' : '' ) +' />');
				label    = $('<label for="' + input_id + '">' + font.family + '</label>');

				input.appendTo( checkbox );
				$( document.createTextNode( ' ' ) ).appendTo( checkbox );
				label.appendTo( checkbox );
				checkbox.appendTo( fontlist );

				input.on( 'change', function( event ) {
					var values = {};

					fontlist.find('input[type="checkbox"]').each(function() {
						if( $(this).is(':checked') ) {
							var value = {};
							value[ this.value ] = args.web_fonts.fonts[ this.value ];
							$.extend( values, value );
						}
					} );

					control.setting.set( values );
				} );
			} );

			nodes = control.container.find('input[type="checkbox"]');

			control.setting.bind( function ( values ) {
				nodes.each( function() {
					this.checked = ( this.value in values );
				} );
			} );
		}

		if ( typeof args.web_fonts.ajaxResponse == 'undefined' ) {
			$.ajax( args.web_fonts.ajaxUrl, {
				success: function( response ) {
					if ( typeof response == 'object' ) {
						if ( args.web_fonts.supportedVariants.length ) {
							$.each( args.web_fonts.supportedVariants, function( index, variant ) {
								if ( typeof variant === 'number' ) {
									args.web_fonts.supportedVariants[ index ] = variant.toString();
								}
							} );

							$.each( response, function( index, font ) {
								response[ index ].variants = $( response[ index ].variants ).filter( args.web_fonts.supportedVariants );
							} );
						}

						args.web_fonts.ajaxResponse = response;

						append_ajax_fonts( response );
					} else {
						var h3 = fontlist.find('h3');

						if ( h3.length == 0 ) {
							h3 = $('<h3></h3>');
							h3.appendTo( fontlist )
						}

						h3.html( args.web_fonts.ajaxError );
					}
				},
				error: function( data, status, error ) {
					var h3 = fontlist.find('h3');

					if ( h3.length == 0 ) {
						h3 = $('<h3></h3>');
						h3.appendTo( fontlist )
					}

					h3.html( args.web_fonts.ajaxError );
				},
			} );
		} else {
			append_ajax_fonts( args.web_fonts.ajaxResponse );
		}
	}
} )( wp, jQuery );
