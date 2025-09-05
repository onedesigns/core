<?php
/**
 * Enlightenment Framework Customize Control classes
 *
 * @package Enlightenment_Framework
 * @subpackage Customize
 * @since 1.2.0
 */

/**
 * Customize Plain Text Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Plain_Text_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'plainText';

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the plain text control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>

		<# if ( data.label ) { #>
			<div class="customize-control-title">{{{ data.label }}}</div>
		<# } #>

		<# if ( data.description ) { #>
			<div class="description customize-control-description">{{{ data.description }}}</div>
		<# } #>

		<?php
	}

}

/**
 * Customize Slider Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Slider_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'slider';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'enlightenment-customize-controls' );
		wp_enqueue_script( 'enlightenment-customize-controls' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['input_attrs']  = $this->input_attrs;
		$this->json['defaultValue'] = $this->value();
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the slider control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		id = id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' );

		var input_id = '_customize-input-' + id;
		#>

		<# if ( data.label ) { #>
			<label for="{{ input_id }}" class="customize-control-title">{{{ data.label }}}</label>
		<# } #>

		<# if ( data.description ) { #>
			<div class="description customize-control-description">{{{ data.description }}}</div>
		<# } #>

		<div class="range-number-wrap">
			<input
				id="{{ input_id }}"
				type="range"
				<# _.each( data.input_attrs, function( value, key ) { #>
					{{{ key }}}="{{ value }}"
				<# }); #>
				<# if ( ! data.input_attrs || ! data.input_attrs.value ) { #>
					value="{{ data.defaultValue }}"
				<# } #>
			/>

			<input
				type="number"
				<# _.each( data.input_attrs, function( value, key ) { #>
					{{{ key }}}="{{ value }}"
				<# }); #>
				<# if ( ! data.input_attrs || ! data.input_attrs.value ) { #>
					value="{{ data.defaultValue }}"
				<# } #>
			/>
		</div>
		<?php
	}

}

/**
 * Customize Multiple Checkboxes Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Multiple_Checkboxes_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'checkboxes';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'enlightenment-customize-controls' );
		wp_enqueue_script( 'enlightenment-customize-controls' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['choices']      = $this->choices;
		$this->json['defaultValue'] = $this->value();
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the multiple checkboxes control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>

		<#
		if ( Array.isArray( data.choices ) && 0 === data.choices.length ) {
			return;
		}

		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		id = id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' );

		var desc_id = '_customize-description-' + id,
			name    = '_customize-checkboxes-' + id;
		#>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span id="{{ desc_id }}" class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<#
		_.each( data.choices, function( label, value ) {
			var input_id = '_customize-input-' + id + '-checkbox-' + value;
			#>
			<span class="customize-inside-control-row">
				<input
					id="{{ input_id }}"
					<# if ( data.description ) { #>aria-describedby="{{ desc_id }}"<# } #>
					type="checkbox"
					value="{{ value }}"
					name="{{ name }}[]"
					<# if ( _.contains( data.defaultValue, value ) ) { #>checked="checked"<# } #>
				/>

				<label for="{{ input_id }}">{{{ label }}}</label>
			</span>
			<#
		});
		#>

		<?php
	}

}

/**
 * Customize Image Radio Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Image_Radio_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'imageRadio';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'enlightenment-customize-controls' );
		wp_enqueue_script( 'enlightenment-customize-controls' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['choices']      = $this->choices;
		$this->json['defaultValue'] = $this->value();
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the image radio control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		if ( Array.isArray( data.choices ) && 0 === data.choices.length ) {
			return;
		}

		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		id = id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' );

		var desc_id = '_customize-description-' + id,
			name    = '_customize-radio-' + id;
		#>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span id="{{ desc_id }}" class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<fieldset>
			<#
			_.each( data.choices, function( img, value ) {
				var className = 'customize-inside-control-row',
					has_image = ( typeof img == 'object' && ( ( typeof img.svg != 'undefined' && img.svg ) || ( typeof img.src != 'undefined' && img.src ) ) );

				if ( has_image ) {
					className += ' customize-control-imageRadio-item';
				} else {
					className += ' customize-control-textRadio-item';
				}

				var input_id = '_customize-input-' + id + '-radio-' + value;
				#>
				<span class="{{ className }}">
					<input
						id="{{ input_id }}"
						type="radio"
						<# if ( data.description ) { #>aria-describedby="{{ desc_id }}"<# } #>
						value="{{ value }}"
						name="{{ name }}"
						<# if ( data.defaultValue == value ) { #>checked="checked"<# } #>
					/>

					<label for="{{ input_id }}">
						<#
						if ( has_image ) {
							var svg = ( typeof img.svg != 'undefined' && img.svg ) ? img.svg : '';

							if ( svg ) {
								#>
								{{{ svg }}}
								<span class="screen-reader-text">{{{ img.alt }}}</span>
								<#
							} else {
								#>
								<img src="{{ img.src }}" alt="{{ img.alt }}" />
								<#
							}
						} else {
							#>
							{{{ img.alt }}}
							<#
						}
						#>
					</label>
				</span>
				<#
			} );
			#>
		</fieldset>
		<?php
	}
}

/**
 * Customize Position Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Position_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'position';

	/**
	 * @access public
	 * @var bool
	 */
	public $can_be_blank = true;

	/**
	 * @access public
	 * @var string
	 */
	public $blank_label = '';

	/**
	 * Constructor.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		add_filter( 'enlightenment_customize_controls_args', array( $this, 'filter_customize_controls_args' ) );
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'enlightenment-customize-controls' );
		wp_enqueue_script( 'enlightenment-customize-controls' );
	}

	public function filter_customize_controls_args( $args ) {
		$args['positionControl'] = array(
			'blankLabel' => _x( 'Default', 'Position', 'enlightenment' ),
			'choices'    => array(
	            'left-top'      => _x( 'Left Top',      'Position', 'enlightenment' ),
	            'center-top'    => _x( 'Center Top',    'Position', 'enlightenment' ),
	            'right-top'     => _x( 'Right Top',     'Position', 'enlightenment' ),
	            'left-center'   => _x( 'Left Center',   'Position', 'enlightenment' ),
	            'center-center' => _x( 'Center Center', 'Position', 'enlightenment' ),
	            'right-center'  => _x( 'Right Center',  'Position', 'enlightenment' ),
	            'left-bottom'   => _x( 'Left Bottom',   'Position', 'enlightenment' ),
	            'center-bottom' => _x( 'Center Bottom', 'Position', 'enlightenment' ),
	            'right-bottom'  => _x( 'Right Bottom',  'Position', 'enlightenment' ),
	        ),
		);

		return $args;
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['choices']      = $this->choices;
		$this->json['defaultValue'] = $this->value();
		$this->json['canBeBlank']   = $this->can_be_blank;
		$this->json['blankLabel']   = $this->blank_label;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the image radio control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		var choices    = _.extend( {}, enlightenment_customize_controls_args.positionControl.choices );
		var blankLabel = enlightenment_customize_controls_args.positionControl.blankLabel;

		if ( ! _.isEmpty( data.choices ) ) {
			_.each( choices, function( label, value ) {
				if ( typeof data.choices[ value ] != 'undefined' ) {
					choices[ value ] = data.choices[ value ];
				}
			} );
		}

		if ( data.blankLabel ) {
			blankLabel = data.blankLabel;
		}

		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		id = id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' );

		var desc_id = '_customize-description-' + id,
			name    = '_customize-radio-' + id;
		#>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span id="_customize-description-{{ id }}" class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<# if ( data.canBeBlank ) { #>
			<span class="customize-inside-control-row customize-control-default-item">
				<input
					id="_customize-input-{{ id }}-radio-default"
					type="radio"
					<# if ( data.description ) { #>aria-describedby="_customize-description-{{ id }}"<# } #>
					value=""
					name="_customize-radio-{{ id }}"
					<# if ( data.defaultValue == '' ) { #>checked="checked"<# } #>
				/>

				<label for="_customize-input-{{ id }}-radio-default">
					{{{ blankLabel }}}
				</label>
			</span>
		<# } #>

		<fieldset>
			<#
			_.each( choices, function( label, value ) {
				var input_id = '_customize-input-' + id + '-radio-' + value;
				#>

				<span class="customize-inside-control-row customize-control-position-item">
					<input
						id="{{ input_id }}"
						type="radio"
						<# if ( data.description ) { #>aria-describedby="_customize-description-{{ id }}"<# } #>
						value="{{ value }}"
						name="_customize-radio-{{ id }}"
						<# if ( data.defaultValue == value ) { #>checked="checked"<# } #>
					/>

					<label for="{{ input_id }}">
						<span class="screen-reader-text">{{{ label }}}</span>
					</label>
				</span>

				<#
			} );
			#>
		</fieldset>
		<?php
	}
}

/**
 * Alpha Color Picker Customizer Control
 *
 * This control adds a second slider for opacity to the stock WordPress color picker,
 * and it includes logic to seamlessly convert between RGBa and Hex color values as
 * opacity is added to or removed from a color.
 *
 * This Alpha Color Picker is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this Alpha Color Picker. If not, see <http://www.gnu.org/licenses/>.
 */
class Enlightenment_Customize_Alpha_Color_Control extends WP_Customize_Control {

	/**
	 * Official control name.
	 */
	public $type = 'alphaColor';

	/**
	 * Add support for palettes to be passed in.
	 *
	 * Supported palette values are true, false, or an array of RGBa and Hex colors.
	 */
	public $palette;

	/**
	 * Add support for showing the opacity value on the slider handle.
	 */
	public $show_opacity;

	/**
	 * Enqueue scripts and styles.
	 *
	 * Ideally these would get registered and given proper paths before this control object
	 * gets initialized, then we could simply enqueue them here, but for completeness as a
	 * stand alone class we'll register and enqueue them here.
	 */
	public function enqueue() {
		wp_enqueue_style( 'enlightenment-customize-controls' );
		wp_enqueue_style( 'enlightenment-alpha-color-picker' );

		wp_enqueue_script( 'enlightenment-customize-controls' );
		wp_enqueue_script( 'enlightenment-alpha-color-picker' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		// Process the palette
		if ( is_array( $this->palette ) ) {
			$palette = implode( '|', $this->palette );
		} else {
			// Default to true.
			$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
		}

		// Support passing show_opacity as string or boolean. Default to false.
		$show_opacity = ( true === $this->show_opacity || 'true' === $this->show_opacity ) ? 'true' : 'false';

		$this->json['palette']       = $palette;
		$this->json['show_opacity']  = $show_opacity;
		$this->json['default_color'] = $this->settings['default']->default;
		$this->json['defaultValue']  = $this->value();
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the image radio control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		id = id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' );

		var input_id = '_customize-input-' + id;
		#>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span id="{{ desc_id }}" class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<input
			id="{{ input_id }}"
			class="alpha-color-control"
			type="text"
			value="{{ data.defaultValue }}"
			data-show-opacity="{{ data.show_opacity }}"
			data-palette="{{ data.palette }}"
			data-default-color="{{ data.default_color }}"
		/>
		<?php
	}
}

/**
 * Customize Font Family Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Font_Family_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'fontFamily';

	/**
	 * @access public
	 * @var string
	 */
	public $selector = '';

	/**
	 * @access public
	 * @var bool
	 */
	public $can_be_blank = true;

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'enlightenment-customize-controls' );
		wp_enqueue_script( 'enlightenment-customize-controls' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 */
	public function to_json() {
		parent::to_json();

		$choices = array_map( function( $atts ) {
			return sprintf(
				'%s, %s',
				str_replace( '"', '', $atts['family'] ),
				$atts['category']
			);
		}, enlightenment_get_available_fonts() );

		if ( $this->can_be_blank ) {
			$choices = array( '' => _x( 'Default', 'default font family', 'enlightenment' ) ) + $choices;
		}

		$this->json['selector']     = $this->selector;
		$this->json['defaultValue'] = $this->value();
		$this->json['choices']      = $choices;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the font family control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		if ( ! data.choices ) {
			return;
		}
		#>

		<label>
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{{ data.label }}}</span>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>

			<select>
				<# _.mapObject( data.choices, function( label, value ) { #>
					<option value="{{ value }}"<# if ( data.defaultValue == value ) { #> selected="selected"<# } #>>{{{ label }}}</option>
				<# } ); #>
			</select>
		</label>
		<?php
	}

}

/**
 * Customize Editable Dropdown Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Editable_Dropdown_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'editableDropdown';

	/**
	 * @access public
	 * @var string
	 */
	public $input_type = 'text';

	/**
	 * @access public
	 * @var array
	 */
	public $accepted_units = array();

	/**
	 * @access public
	 * @var string
	 */
	public $default_unit = '';

	/**
	 * @access public
	 * @var float
	 */
	public $min = 0;

	/**
	 * @access public
	 * @var float
	 */
	public $max = 0;

	/**
	 * @access public
	 * @var float
	 */
	public $step = 1;

	/**
	 * @access public
	 * @var array
	 */
	public $suggestions = array();

	/**
	 * Enqueue scripts/styles for the editable dropdown.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'enlightenment-dropdown' );
		wp_enqueue_style( 'enlightenment-customize-controls' );
		wp_enqueue_script( 'enlightenment-dropdown' );
		wp_enqueue_script( 'enlightenment-customize-controls' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$defaultValue = $this->value();

		if ( ! empty( $this->default_unit ) ) {
			$this->accepted_units = array_unique( array_merge( $this->accepted_units, array( $this->default_unit ) ) );
		}

		if ( ! empty( $this->accepted_units ) ) {
			$this->input_type     = 'text';
			$this->accepted_units = array_values( $this->accepted_units );

			if ( empty( $this->suggestions ) ) {
				if ( ! is_numeric( $this->min ) ) {
					$this->min = 0;
				}

				if ( ! is_numeric( $this->max ) ) {
					$this->max = 0;
				}

				if ( ! is_numeric( $this->step ) ) {
					$this->step = 1;
				}

				if ( $this->min !== $this->max ) {
					$this->suggestions = array();

					for ( $i = $this->min; $i <= $this->max; $i = $i + $this->step ) {
						$this->suggestions[] = $i;
					}

					if ( ! in_array( $this->max, $this->suggestions ) ) {
						$this->suggestions[] = $this->max;
					}
				}
			}

			foreach ( $this->suggestions as $key => $suggestion ) {
				$has_unit = false;

				foreach ( $this->accepted_units as $unit ) {
					if ( strpos( $suggestion, $unit ) === strlen( $suggestion ) - strlen( $unit ) ) {
						$has_unit = true;

						break;
					}
				}

				if ( ! $has_unit ) {
					if ( empty( $suggestion ) ) {
						continue;
					}

					if ( ! is_numeric( $suggestion ) ) {
						unset( $this->suggestions[ $key ] );
						continue;
					}

					if ( empty( $this->default_unit ) ) {
						$this->default_unit = $this->accepted_units[0];
					}

					$this->suggestions[ $key ] = sprintf( '%s%s', $suggestion, $this->default_unit );
				}
			}

			if ( ! empty( $defaultValue ) ) {
				$has_unit = false;

				foreach ( $this->accepted_units as $unit ) {
					if ( strpos( $defaultValue, $unit ) === strlen( $defaultValue ) - strlen( $unit ) ) {
						$has_unit = true;

						break;
					}
				}

				if ( ! $has_unit ) {
					if ( ! is_numeric( $defaultValue ) ) {
						$defaultValue = '';
					} else {
						if ( empty( $this->default_unit ) ) {
							$this->default_unit = $this->accepted_units[0];
						}

						$defaultValue = sprintf( '%s%s', $defaultValue, $this->default_unit );
					}
				}
			}
		}

		$this->json['input_type']     = $this->input_type;
		$this->json['accepted_units'] = $this->accepted_units;
		$this->json['default_unit']   = $this->default_unit;
		$this->json['suggestions']    = $this->suggestions;
		$this->json['input_attrs']    = $this->input_attrs;
		$this->json['defaultValue']   = $defaultValue;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the editable dropdown control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		id = id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' );

		var input_id     = '_customize-input-' + id,
			desc_id      = '_customize-description-' + id,
			name         = '_customize-input-' + id,
			defaultValue = data.input_attrs && data.input_attrs.value ? data.input_attrs.value : data.defaultValue;
		#>

		<# if ( data.label ) { #>
			<label for="{{ input_id }}" class="customize-control-title">{{{ data.label }}}</label>
		<# } #>

		<# if ( data.description ) { #>
			<span id="{{ desc_id }}" class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<# if ( data.suggestions.length ) { #>
			<div id="dd-{{ id }}" class="wrapper-dropdown editable-dropdown-wrap">
		<# } #>

				<input
					id="{{ input_id }}"
					<# if ( data.suggestions.length ) { #>
						class="current"
					<# } #>
					type="{{ data.input_type }}"
					<# _.each( data.input_attrs, function( value, key ) { #>
						{{{ key }}}="{{ value }}"
					<# }); #>
					<# if ( ! data.input_attrs || ! data.input_attrs.value ) { #>
						value="{{ data.defaultValue }}"
					<# } #>
				/>

		<# if ( data.suggestions.length !== 0 ) { #>
				<ul class="dropdown">
					<#
					_.each( data.suggestions, function( value ) {
						#>

						<li<# if ( defaultValue == value ) { #> class="selected"<# } #>>
							<a href="#" data-value="{{ value }}">{{{ value }}}</a>
						</li>

						<#
					});
					#>
				</ul>
			</div>
		<# } #>

		<?php
	}

}

/**
 * Customize Font Size Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Font_Size_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'fontSize';

	/**
	 * @access public
	 * @var array
	 */
	public $accepted_units = array( 'px', 'rem' );

	/**
	 * @access public
	 * @var int
	 */
	public $em_max = 6;

	/**
	 * @access public
	 * @var float
	 */
	public $em_step = 0.005;

	/**
	 * @access public
	 * @var int
	 */
	public $em_default = 1;

	/**
	 * @access public
	 * @var int
	 */
	public $px_max = 96;

	/**
	 * @access public
	 * @var int
	 */
	public $px_step = 1;

	/**
	 * @access public
	 * @var int
	 */
	public $px_default = 16;

	/**
	 * Enqueue scripts/styles for the font size control.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'enlightenment-customize-controls' );
		wp_enqueue_script( 'enlightenment-customize-controls' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$value = $this->value();
		$size  = '';
		$unit  = '';

		if ( is_numeric( $value ) ) {
			$size = $value;
		} else {
			foreach ( $this->accepted_units as $accepted_unit ) {
				if ( strpos( $value, $accepted_unit ) === strlen( $value ) - strlen( $accepted_unit ) ) {
					$numeric_value = substr( $value, 0, -1 * strlen( $accepted_unit ) );

					if ( is_numeric( $numeric_value ) ) {
						$size = $numeric_value;
						$unit = $accepted_unit;

						break;
					}
				}
			}
		}

		if ( '' === $unit ) {
			if ( 1 === count( $this->accepted_units ) ) {
				$accepted_units = array_values( $this->accepted_units );

				$unit = array_shift( $accepted_units );
			} else {
				$default_unit = apply_filters( 'enlightenment_default_font_size_unit', 'px' );

				if ( in_array( $default_unit, $this->accepted_units ) ) {
					$unit = $default_unit;
				} else {
					$accepted_units = array_values( $this->accepted_units );

					$unit = array_shift( $accepted_units );
				}
			}
		}

		$this->json['input_attrs']    = $this->input_attrs;
		$this->json['defaultValue']   = $value;
		$this->json['defaultSize']    = $size;
		$this->json['defaultUnit']    = $unit;
		$this->json['accepted_units'] = $this->accepted_units;
		$this->json['em_max']         = $this->em_max;
		$this->json['em_step']        = $this->em_step;
		$this->json['em_default']     = $this->em_default;
		$this->json['px_max']         = $this->px_max;
		$this->json['px_step']        = $this->px_step;
		$this->json['px_default']     = $this->px_default;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the font size control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		id = id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' );

		var range_id  = '_customize-input-' + id + '-range',
			number_id = '_customize-input-' + id + '-number',
			unit_id   = '_customize-input-' + id + '-unit';

		if ( Array.isArray( data.input_attrs ) ) {
			data.input_attrs = Object.fromEntries( data.input_attrs );
		}

		data.input_attrs.min = data.input_attrs.min || 0;

		switch ( data.defaultUnit ) {
			case 'px':
				data.input_attrs.max  = data.px_max;
				data.input_attrs.step = data.px_step;

				break;

			default:
				data.input_attrs.max  = data.em_max;
				data.input_attrs.step = data.em_step;
		}
		#>

		<# if ( data.label ) { #>
			<label for="{{ range_id }}" class="customize-control-title">{{{ data.label }}}</label>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<div class="range-number-wrap">
			<input
				id="{{ range_id }}"
				type="range"
				<# if ( data.description ) { #> aria-describedby="{{ desc_id }}"<# } #>
				<#
				_.each( data.input_attrs, function( value, label ) {
					#>
					{{{ label }}}={{ value }}
					<#
				} );
				#>
				<# if ( ! data.input_attrs || ! data.input_attrs.value ) { #>
					value="{{ data.defaultSize }}"
				<# } #>
			/>

			<# if ( data.accepted_units.length ) { #>
				<div class="size-unit-wrap">
			<# } #>

					<input
						id="{{ number_id }}"
						type="number"
						<#
						_.each( data.input_attrs, function( value, label ) {
							#>
							{{{ label }}}={{ value }}
							<#
						} );
						#>
						<# if ( ! data.input_attrs || ! data.input_attrs.value ) { #>
							value="{{ data.defaultSize }}"
						<# } #>
					/>

			<# if ( data.accepted_units.length ) { #>
					<select id="{{ unit_id }}" data-value="{{ data.defaultUnit }}"<# if ( 1 === data.accepted_units.length ) { #> disabled="disabled"<# } #>>
						<#
						_.each( data.accepted_units, function( unit ) {
							#>
							<option value="{{ unit }}"<# if ( data.defaultUnit === unit ) { #> selected="selected"<# } #>>{{{ unit }}}</option>
							<#
						} );
						#>
					</select>
				</div>
			<# } #>
		</div>

		<?php
	}

}

/**
 * Customize Font Variant Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Font_Variant_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'fontVariant';

	/**
	 * @access public
	 * @var array
	 */
	public $accepted_choices = array();

	/**
	 * @access public
	 * @var bool
	 */
	public $can_be_blank = true;

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * If `$args['settings']` is not defined, use the `$id` as the setting ID.
	 *
	 * @since 3.4.0
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$choices = enlightenment_get_font_variants();

		if ( ! empty( $this->accepted_choices ) ) {
			$choices = array_intersect_key( $choices, array_flip( $this->accepted_choices ) );
		}

		if ( $this->can_be_blank ) {
			$choices = array( '' => _x( 'Default', 'default font variant', 'enlightenment' ) ) + $choices;
		}

		$this->choices = $choices;
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['defaultValue'] = $this->value();
		$this->json['choices']      = $this->choices;
	}

	/**
	 * Render the control's content.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}

		$input_id         = '_customize-input-' . $this->id;
		$description_id   = '_customize-description-' . $this->id;
		$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';

		?>
		<?php if ( ! empty( $this->label ) ) : ?>
			<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
		<?php endif; ?>
		<?php if ( ! empty( $this->description ) ) : ?>
			<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
		<?php endif; ?>

		<select id="<?php echo esc_attr( $input_id ); ?>" <?php echo $describedby_attr; ?> <?php $this->link(); ?>>
			<?php
			foreach ( $this->choices as $value => $label ) {
				echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
			}
			?>
		</select>
		<?php
	}

}

/**
 * Customize Line Height Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Line_Height_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'lineHeight';

	/**
	 * @access public
	 * @var array
	 */
	public $accepted_units = array();

	/**
	 * @access public
	 * @var int
	 */
	public $max = 3;

	/**
	 * @access public
	 * @var float
	 */
	public $step = 0.005;

	/**
	 * @access public
	 * @var int
	 */
	public $default = 1.5;

	/**
	 * @access public
	 * @var int
	 */
	public $px_max = 144;

	/**
	 * @access public
	 * @var int
	 */
	public $px_step = 1;

	/**
	 * @access public
	 * @var int
	 */
	public $px_default = 24;

	/**
	 * Enqueue scripts/styles for the line height control.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'enlightenment-customize-controls' );
		wp_enqueue_script( 'enlightenment-customize-controls' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$value = $this->value();
		$size  = '';
		$unit  = '';

		if ( is_numeric( $value ) ) {
			$size = $value;
		} else {
			foreach ( $this->accepted_units as $accepted_unit ) {
				if ( strpos( $value, $accepted_unit ) === strlen( $value ) - strlen( $accepted_unit ) ) {
					$numeric_value = substr( $value, 0, -1 * strlen( $accepted_unit ) );

					if ( is_numeric( $numeric_value ) ) {
						$size = $numeric_value;
						$unit = $accepted_unit;

						break;
					}
				}
			}
		}

		$this->json['input_attrs']    = $this->input_attrs;
		$this->json['defaultValue']   = $value;
		$this->json['defaultSize']    = $size;
		$this->json['defaultUnit']    = $unit;
		$this->json['accepted_units'] = $this->accepted_units;
		$this->json['max']            = $this->max;
		$this->json['step']           = $this->step;
		$this->json['default']        = $this->default;
		$this->json['px_max']         = $this->px_max;
		$this->json['px_step']        = $this->px_step;
		$this->json['px_default']     = $this->px_default;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the line height control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		id = id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' );

		var range_id  = '_customize-input-' + id + '-range',
			number_id = '_customize-input-' + id + '-number',
			unit_id   = '_customize-input-' + id + '-unit';

		if ( Array.isArray( data.input_attrs ) ) {
			data.input_attrs = Object.fromEntries( data.input_attrs );
		}

		data.input_attrs.min = data.input_attrs.min || 0;

		switch ( data.defaultUnit ) {
			case 'px':
				data.input_attrs.max  = data.px_max;
				data.input_attrs.step = data.px_step;

				break;

			default:
				data.input_attrs.max  = data.max;
				data.input_attrs.step = data.step;
		}
		#>

		<# if ( data.label ) { #>
			<label for="{{ range_id }}" class="customize-control-title">{{{ data.label }}}</label>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<div class="range-number-wrap">
			<input
				id="{{ range_id }}"
				type="range"
				<# if ( data.description ) { #> aria-describedby="{{ desc_id }}"<# } #>
				<#
				_.each( data.input_attrs, function( value, label ) {
					#>
					{{{ label }}}={{ value }}
					<#
				} );
				#>
				<# if ( ! data.input_attrs || ! data.input_attrs.value ) { #>
					value="{{ data.defaultSize }}"
				<# } #>
			/>

			<# if ( data.accepted_units.length && '' !== data.accepted_units.toString() ) { #>
				<div class="size-unit-wrap">
			<# } #>

					<input
						id="{{ number_id }}"
						type="number"
						<#
						_.each( data.input_attrs, function( value, label ) {
							#>
							{{{ label }}}={{ value }}
							<#
						} );
						#>
						<# if ( ! data.input_attrs || ! data.input_attrs.value ) { #>
							value="{{ data.defaultSize }}"
						<# } #>
					/>

			<# if ( data.accepted_units.length && '' !== data.accepted_units.toString() ) { #>
					<select id="{{ unit_id }}" data-value="{{ data.defaultUnit }}"<# if ( 1 === data.accepted_units.length ) { #> disabled="disabled"<# } #>>
						<#
						_.each( data.accepted_units, function( unit ) {
							#>
							<option value="{{ unit }}"<# if ( data.defaultUnit === unit ) { #> selected="selected"<# } #>>{{{ unit }}}</option>
							<#
						} );
						#>
					</select>
				</div>
			<# } #>
		</div>

		<?php
	}

}

/**
 * Customize Text Align Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Text_Align_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'textAlign';

	/**
	 * @access public
	 * @var array
	 */
	public $accepted_choices = array();

	/**
	 * @access public
	 * @var bool
	 */
	public $can_be_blank = true;

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * If `$args['settings']` is not defined, use the `$id` as the setting ID.
	 *
	 * @since 3.4.0
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$choices = array(
			'start'   => _x( 'Left',      'Text alignment', 'enlightenment' ),
			'center'  => _x( 'Center',    'Text alignment', 'enlightenment' ),
			'end'     => _x( 'Right',     'Text alignment', 'enlightenment' ),
			'justify' => _x( 'Justified', 'Text alignment', 'enlightenment' ),
		);

		if ( ! empty( $this->accepted_choices ) ) {
			$choices = array_intersect_key( $choices, array_flip( $this->accepted_choices ) );
		}

		if ( $this->can_be_blank ) {
			$choices = array( '' => _x( 'Default', 'default text alignment', 'enlightenment' ) ) + $choices;
		}

		$this->choices = $choices;
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['defaultValue'] = $this->value();
		$this->json['choices']      = $this->choices;
	}

	/**
	 * Render the control's content.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the text align control.
	 *
	 * This function is not yet used, because passing the choices to JS results
	 * in loss of property order.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		if ( ! data.choices ) {
			return;
		}
		#>

		<label>
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{{ data.label }}}</span>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>

			<select>
				<# _.mapObject( data.choices, function( label, value ) { #>
					<option value="{{ value }}"<# if ( data.defaultValue == value ) { #> selected="selected"<# } #>>{{{ label }}}</option>
				<# } ); #>
			</select>
		</label>
		<?php
	}

}

/**
 * Customize Text Decoration Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Text_Decoration_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'textDecoration';

	/**
	 * @access public
	 * @var array
	 */
	public $accepted_choices = array();

	/**
	 * @access public
	 * @var bool
	 */
	public $can_be_blank = true;

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * If `$args['settings']` is not defined, use the `$id` as the setting ID.
	 *
	 * @since 3.4.0
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$choices = array(
			'none'         => _x( 'None',         'Text decoration', 'enlightenment' ),
			'underline'    => _x( 'Underline',    'Text decoration', 'enlightenment' ),
			'overline'     => _x( 'Overline',     'Text decoration', 'enlightenment' ),
			'line-through' => _x( 'Line Through', 'Text decoration', 'enlightenment' ),
		);

		if ( ! empty( $this->accepted_choices ) ) {
			$choices = array_intersect_key( $choices, array_flip( $this->accepted_choices ) );
		}

		if ( $this->can_be_blank ) {
			$choices = array( '' => _x( 'Default', 'default text decoration', 'enlightenment' ) ) + $choices;
		}

		$this->choices = $choices;
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['defaultValue'] = $this->value();
		$this->json['choices']      = $this->choices;
	}

	/**
	 * Render the control's content.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the text align control.
	 *
	 * This function is not yet used, because passing the choices to JS results
	 * in loss of property order.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		if ( ! data.choices ) {
			return;
		}
		#>

		<label>
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{{ data.label }}}</span>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>

			<select>
				<# _.mapObject( data.choices, function( label, value ) { #>
					<option value="{{ value }}"<# if ( data.defaultValue == value ) { #> selected="selected"<# } #>>{{{ label }}}</option>
				<# } ); #>
			</select>
		</label>
		<?php
	}

}

/**
 * Customize Text Transform Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Text_Transform_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'textTransform';

	/**
	 * @access public
	 * @var array
	 */
	public $accepted_choices = array();

	/**
	 * @access public
	 * @var bool
	 */
	public $can_be_blank = true;

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * If `$args['settings']` is not defined, use the `$id` as the setting ID.
	 *
	 * @since 3.4.0
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$choices = array(
			'none'       => _x( 'None',       'Text transform', 'enlightenment' ),
			'capitalize' => _x( 'Capitalize', 'Text transform', 'enlightenment' ),
			'uppercase'  => _x( 'Uppercase',  'Text transform', 'enlightenment' ),
			'lowercase'  => _x( 'Lowercase',  'Text transform', 'enlightenment' ),
		);

		if ( ! empty( $this->accepted_choices ) ) {
			$choices = array_intersect_key( $choices, array_flip( $this->accepted_choices ) );
		}

		if ( $this->can_be_blank ) {
			$choices = array( '' => _x( 'Default', 'default text transform', 'enlightenment' ) ) + $choices;
		}

		$this->choices = $choices;
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['defaultValue'] = $this->value();
		$this->json['choices']      = $this->choices;
	}

	/**
	 * Render the control's content.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the text align control.
	 *
	 * This function is not yet used, because passing the choices to JS results
	 * in loss of property order.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		if ( ! data.choices ) {
			return;
		}
		#>

		<label>
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{{ data.label }}}</span>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>

			<select>
				<# _.mapObject( data.choices, function( label, value ) { #>
					<option value="{{ value }}"<# if ( data.defaultValue == value ) { #> selected="selected"<# } #>>{{{ label }}}</option>
				<# } ); #>
			</select>
		</label>
		<?php
	}

}

/**
 * Customize Subsets Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Subsets_Control extends Enlightenment_Customize_Multiple_Checkboxes_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'subsets';

	/**
	 * @access public
	 * @var array
	 */
	public $accepted_choices = array();

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * If `$args['settings']` is not defined, use the `$id` as the setting ID.
	 *
	 * @since 3.4.0
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$choices = enlightenment_get_available_subsets();

		if ( ! empty( $this->accepted_choices ) ) {
			$choices = array_intersect_key( $choices, array_flip( $this->accepted_choices ) );
		}

		$this->choices = $choices;
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['choices'] = $this->choices;
	}

}

/**
 * Customize Web Fonts Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Web_Fonts_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'webfonts';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'enlightenment-customize-controls' );
		wp_enqueue_script( 'enlightenment-customize-controls' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['defaultValue']   = $this->value();
		$this->json['fonts']          = enlightenment_get_web_fonts();
		$this->json['please_wait']    = __( 'Please wait while the fonts list is being populated.', 'enlightenment' );
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the web fonts control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		id = id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' );

		var name = '_customize-checkboxes-' + id;
		#>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<div class="google-fonts-list">

			<#
			_.each( data.fonts, function( atts, font ) {
				var input_id = '_customize-input-' + id + '-checkbox-' + font.replaceAll(' ', '-');
				#>

				<span class="customize-inside-control-row">
					<input
						id="{{ input_id }}"
						type="checkbox"
						value="{{ atts.family }}"
						name="{{ name }}[{{ font }}][family]"
						<# if ( font in data.defaultValue ) { #>checked="checked"<# } #>
					/>

					<label for="{{ input_id }}">{{{ font }}}</label>
				</span>

				<#
			});
			#>

			<h3><# {{{ data.please_wait }}} #></h3>

		</div>

		<?php
	}

}

/**
 * Customize Delete Sidebar Control Class.
 *
 * This custom control is only needed for JS.
 *
 * @since 4.3.0
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Sidebar_Title_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @since 4.3.0
	 * @access public
	 * @var string
	 */
	public $type = 'sidebarTitle';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['input_attrs']  = $this->input_attrs;
		$this->json['defaultValue'] = $this->value();
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the sidebar title control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>

		<#
		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		id = id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' );

		var input_id = '_customize-input-' + id,
			desc_id  = '_customize-description-' + id;
		#>

		<# if ( data.label ) { #>
			<label for="{{ input_id }}" class="customize-control-title">{{{ data.label }}}</label>
		<# } #>

		<# if ( data.description ) { #>
			<span id="{{ desc_id }}" class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<input
			id="{{ input_id }}"
			type="text"
			<# if ( data.description ) { #> aria-describedby="{{ desc_id }}"<# } #>
			<#
			_.each( data.input_attrs, function( value, label ) {
				#>
				{{{ label }}}={{ value }}
				<#
			});
			#>
			<# if ( ! data.input_attrs || ! data.input_attrs.value ) { #>
				value="{{ data.defaultValue }}"
			<# } #>
		/>
		<?php
	}

}

/**
 * Customize Add Sidebar Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Add_Sidebar_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'addSidebar';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'enlightenment-customize-controls' );
		wp_enqueue_script( 'enlightenment-customize-controls' );
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the add sidebar control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>
		<#
		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		id = id.replaceAll( '][', '-' ).replaceAll( '[', '-' ).replaceAll( ']', '' );

		var desc_id = 'customize-' + id + '-description',
			btn_id  = 'customize-' + id + '-button';
		#>

		<# if ( data.description ) { #>
			<p id="{{ desc_id }}">{{{ data.description }}}</p>
		<# } #>

		<button type="button" class="button create-sidebar"<# if ( data.description ) { #> aria-describedby="{{ desc_id }}"<# } #>>
			{{{ data.label }}}
		</button>
		<?php
	}

}

/**
 * Customize Delete Sidebar Control Class.
 *
 * This custom control is only needed for JS.
 *
 * @since 4.3.0
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Delete_Sidebar_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @since 4.3.0
	 * @access public
	 * @var string
	 */
	public $type = 'deleteSidebar';

	/**
	 * @access public
	 * @var string
	 */
	public $default_sidebars = array();

	/**
	 * @access public
	 * @var string
	 */
	public $aria_label = '';

	/**
	 * @access public
	 * @var string
	 */
	public $cant_delete = '';

	/**
	 * Constructor.
	 *
	 * @since 3.4.0
	 *
	 * @see WP_Customize_Control::__construct()
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Optional. Arguments to override class property defaults.
	 *                                      See WP_Customize_Control::__construct() for information
	 *                                      on accepted arguments. Default empty array.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		$this->default_sidebars = $GLOBALS['enlightenment_default_sidebars'];
		$this->cant_delete      = __( 'You can not delete this sidebar because it is a theme default.', 'enlightenment' );

		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['default_sidebars'] = $this->default_sidebars;
		$this->json['aria_label']       = $this->aria_label;
		$this->json['cant_delete']      = $this->cant_delete;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the delete sidebar control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>

		<#
		var id = typeof data.settings != 'undefined' ? data.settings.default : ( typeof data.setting != 'undefined' ? data.setting : '' );

		sidebar_id = id.replace( 'sidebars[', '' ).replace( '][delete]', '' );

		if ( data.default_sidebars.includes( sidebar_id ) ) {
			#>
			{{{ data.cant_delete }}}
			<#
		} else {
			#>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>

			<button
				type="button"
				class="button-link button-link-delete delete-sidebar"
				aria-label="{{ data.aria_label }}"
			>{{{ data.label }}}</button>

			<#
		}
		#>

		<?php
	}

}

/**
 * Customize Sidebar Location Control Class.
 *
 * This custom control is only needed for JS.
 *
 * @since 4.3.0
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Sidebar_Location_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @since 4.3.0
	 * @access public
	 * @var string
	 */
	public $type = 'sidebarLocation';

	/**
	 * @access public
	 * @var string
	 */
	public $add_label = '';

	/**
	 * @access public
	 * @var string
	 */
	public $add_aria_label = '';

	/**
	 * @access public
	 * @var string
	 */
	public $edit_label = '';

	/**
	 * @access public
	 * @var string
	 */
	public $edit_aria_label = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['choices']         = $this->choices;
		$this->json['defaultValue']    = $this->value();
		$this->json['add_label']       = $this->add_label;
		$this->json['add_aria_label']  = $this->add_aria_label;
		$this->json['edit_label']      = $this->edit_label;
		$this->json['edit_aria_label'] = $this->edit_aria_label;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the sidebar location control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>

		<#
		if ( Array.isArray( data.choices ) && 0 === data.choices.length ) {
			return;
		}
		#>

		<label>
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{{ data.label }}}</span>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>

			<select>
				<#
				_.each( data.choices, function( label, value ) {
					#>
					<option value="{{ value }}"<# if ( data.defaultValue == value ) { #> selected="selected"<# } #>>{{{ label }}}</option>
					<#
				});
				#>
			</select>
		</label>

		<button type="button" class="button-link create-sidebar<# if ( data.defaultValue ) { #> hidden<# } #>" aria-label="{{ data.add_aria_label }}">{{{ data.add_label }}}</button>

		<button type="button" class="button-link edit-sidebar<# if ( ! data.defaultValue ) { #> hidden<# } #>" aria-label="{{ data.edit_aria_label }}">{{{ data.edit_label }}}</button>

		<?php
	}

}

/**
 * Customize Sidebar Location Control Class.
 *
 * This custom control is only needed for JS.
 *
 * @since 4.3.0
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Template_Hook_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @since 4.3.0
	 * @access public
	 * @var string
	 */
	public $type = 'templateHook';

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * If `$args['settings']` is not defined, use the $id as the setting ID.
	 *
	 * @since 3.4.0
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Optional. Arguments to override class property defaults.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		add_action( 'customize_controls_print_footer_scripts', array( $this, 'output_function_control_templates' ) );
	}

	/**
	 * Render content just like a normal select control.
	 *
	 * @since 4.3.0
	 * @access public
	 */
	public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}

		$id        = str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
		$value     = '' === $this->value() ? array() : (array) $this->value();
		$functions = enlightenment_template_functions();

		$hooked_functions = array();
		foreach ( $value as $function ) {
			if ( ! isset( $functions[ $function ] ) ) {
				continue;
			}

			$hooked_functions[ $function ] = $functions[ $function ];
		}

		if ( ! empty( $this->label ) ) :
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php
		endif;

		if ( ! empty( $this->description ) ) :
			?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php
		endif;
		?>

		<span class="spinner"></span>

		<div class="hooked-functions-list<?php echo empty( $functions ) ? ' empty' : ''; ?>">
			<?php
			foreach ( $hooked_functions as $function => $name ) :
				$this->output_function_control_template( $function, $name );
			endforeach;
			?>
		</div>

		<input type="hidden" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( join( ',', array_keys( $hooked_functions ) ) ); ?>" <?php $this->link(); ?> />

		<button type="button" class="button add-new-widget add-new-function" aria-expanded="false" aria-controls="available-functions-<?php echo esc_attr( $id ); ?>">
			<?php _e( 'Add a Function', 'enlightenment' ); ?>
		</button>

		<button type="button" class="button-link reorder-toggle reorder-functions" aria-label="<?php esc_attr_e( 'Reorder functions', 'enlightenment' ); ?>" aria-describedby="reorder-widgets-desc-<?php echo esc_attr( $id ); ?>">
			<span class="reorder"><?php _ex( 'Reorder', 'Reorder functions in Customizer', 'enlightenment' ); ?></span>
			<span class="reorder-done"><?php _ex( 'Done', 'Cancel reordering functions in Customizer', 'enlightenment' ); ?></span>
		</button>

		<p class="screen-reader-text" id="reorder-widgets-desc-<?php echo esc_attr( $id ); ?>"><?php _e( 'When in reorder mode, additional controls to reorder functions will be available in the functions list above.', 'enlightenment' ); ?></p>
		<?php
	}

	/**
	 * Renders the function form control templates into the DOM.
	 *
	 * @since 3.9.0
	 * @access public
	 */
	public function output_function_control_templates() {
		$id        = str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
		$section   = str_replace( ']', '', str_replace( 'template_hooks[', 'template-', str_replace( '][', '-hook-', $this->id ) ) );
		$value     = '' === $this->value() ? array() : (array) $this->value();
		$functions = enlightenment_template_functions();

		$hooked_functions = array();
		foreach ( $value as $function ) {
			if ( ! isset( $functions[ $function ] ) ) {
				continue;
			}

			$hooked_functions[ $function ] = $functions[ $function ];
		}

		$available_functions = array_diff( $this->choices, $hooked_functions );

		?>
		<div class="available-functions" id="available-functions-<?php echo esc_attr( $id ); ?>">
			<div class="customize-section-title">
				<button class="customize-section-back" tabindex="-1">
					<span class="screen-reader-text"><?php _e( 'Back', 'enlightenment' ); ?></span>
				</button>

				<h3><?php _e( 'Available Functions', 'enlightenment' ); ?></h3>
			</div>

			<div class="available-functions-list">
				<?php foreach ( $available_functions as $function => $name ): ?>
					<?php $this->output_function_control_template( $function, $name ); ?>
				<?php endforeach; ?>
			</div><!-- .available-functions-list -->
		</div><!-- #available-functions-<?php echo esc_attr( $id ); ?> -->
		<?php
	}

	/**
	 * Renders the function form control templates into the DOM.
	 *
	 * @since 3.9.0
	 * @access public
	 */
	public function output_function_control_template( $function, $name ) {
		?>
		<div class="widget-tpl function-tpl function-tpl-<?php echo esc_attr( $function ) ?>" data-function-name="<?php echo esc_attr( $function ) ?>" tabindex="0">
			<div class="widget function">
				<div class="widget-top function-top">
					<div class="widget-title-action function-title-action">
						<button type="button" class="widget-action function-action hide-if-no-js" aria-expanded="false">
							<span class="screen-reader-text edit">
								<?php
								/* translators: %s: Function title. */
								printf( __( 'Edit function: %s', 'enlightenment' ), $name );
								?>
							</span>
							<span class="screen-reader-text add">
								<?php
								/* translators: %s: Function title. */
								printf( __( 'Add widget: %s' ), $name );
								?>
							</span>
							<span class="toggle-indicator" aria-hidden="true"></span>
						</button>
						<a class="widget-control-edit function-control-edit hide-if-js" href="">
							<span class="edit"><?php _ex( 'Edit', 'function', 'enlightenment' ); ?></span>
							<span class="add"><?php _ex( 'Add', 'function', 'enlightenment' ); ?></span>
							<span class="screen-reader-text"><?php echo $name; ?></span>
						</a>
					</div>

					<div class="function-title widget-title">
						<h3><?php echo $name; ?></h3>

						<span class="in-widget-title in-function-title">
							<span class="move-function-down move-widget-down" tabindex="0"><?php printf( __( '%s: Move down', 'enlightenment' ), $name ); ?></span>
							<span class="move-function-up move-widget-up" tabindex="0"><?php printf( __( '%s: Move up', 'enlightenment' ), $name ); ?></span>
							<button type="button" class="button-link item-delete submitdelete deletion"><span class="screen-reader-text"><?php printf( __( 'Remove Function: %s', 'enlightenment' ), $name ); ?></span></button>
						</span>
					</div>
				</div>

				<div class="function-description widget-description"></div>
			</div>
		</div>
		<?php
	}

}

/**
 * Customize Reset Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Export_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @since 4.2.0
	 * @access public
	 * @var string
	 */
	public $type = 'export';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['export_url'] = admin_url( 'admin-ajax.php/?action=enlightenment_export_settings' );
		$this->json['btn_label']  = __( 'Download export file', 'enlightenment' );
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the export control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>

		<# if ( data.label ) { #>
			<div class="customize-control-title">{{{ data.label }}}</div>
		<# } #>

		<# if ( data.description ) { #>
			<div class="description customize-control-description">{{{ data.description }}}</div>
		<# } #>

		<a href="{{ data.export_url }}">{{{ data.btn_label }}}</a>

		<?php
	}

}

/**
 * Customize Reset Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Import_Control extends WP_Customize_Upload_Control {

	/**
	 * Control type.
	 *
	 * @since 4.2.0
	 * @access public
	 * @var string
	 */
	public $type = 'import';

	/**
	 * Media control mime type.
	 *
	 * @since 4.2.0
	 * @access public
	 * @var string
	 */
	public $mime_type = 'application/json';

	/**
	 * Renders the control wrapper and calls $this->render_content() for the internals.
	 *
	 * @since 3.4.0
	 */
	protected function render() {
		$id    = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
		$class = 'customize-control customize-control-upload customize-control-' . $this->type;

		printf( '<li id="%s" class="%s">', esc_attr( $id ), esc_attr( $class ) );
		$this->render_content();
		echo '</li>';
	}

}

/**
 * Customize Reset Control class.
 *
 * @see WP_Customize_Control
 */
class Enlightenment_Customize_Reset_Control extends WP_Customize_Control {

	/**
	 * @access public
	 * @var string
	 */
	public $type = 'reset';

	/**
	 * @access public
	 * @var string
	 */
	public $btn_label = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['btn_label'] = $this->btn_label;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the reset control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
		?>

		<# if ( data.label ) { #>
			<div class="customize-control-title">{{{ data.label }}}</div>
		<# } #>

		<# if ( data.description ) { #>
			<div class="description customize-control-description">{{{ data.description }}}</div>
		<# } #>

		<button type="button" class="button reset-button" value="0">{{{ data.btn_label }}}</button>

		<?php
	}

}

function enlightenment_register_control_types( $wp_customize ) {
	$wp_customize->register_control_type( 'Enlightenment_Customize_Plain_Text_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Slider_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Font_Family_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Multiple_Checkboxes_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Image_Radio_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Position_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Alpha_Color_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Editable_Dropdown_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Font_Size_Control' );
	// $wp_customize->register_control_type( 'Enlightenment_Customize_Font_Variant_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Line_Height_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Text_Align_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Text_Decoration_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Text_Transform_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Subsets_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Web_Fonts_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Sidebar_Title_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Add_Sidebar_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Delete_Sidebar_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Sidebar_Location_Control' );
	// $wp_customize->register_control_type( 'Enlightenment_Customize_Template_Hook_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Export_Control' );
	// $wp_customize->register_control_type( 'Enlightenment_Customize_Import_Control' );
	$wp_customize->register_control_type( 'Enlightenment_Customize_Reset_Control' );
}
add_action( 'customize_register', 'enlightenment_register_control_types' );

function enlightenment_customize_add_typography_section( $wp_customize, $args = null ) {
	if ( ! $wp_customize instanceof WP_Customize_Manager ) {
		return;
	}

	$defaults = array(
		'id'                 => '',
		'panel'              => '',
		'title'              => '',
		'description'        => '',
		'type'               => 'theme_mod',
		'capability'         => 'edit_theme_options',
		'default'            => array(),
		'transport'          => 'refresh',
		'sanitize_callback'  => 'sanitize_text_field',
		'supported_controls' => 'common',
		'settings_args'      => array(),
		'controls_args'      => array(),
	);
	$defaults = apply_filters( 'enlightenment_customize_add_typography_section_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	if ( empty( $args['id'] ) ) {
		return;
	}

    $wp_customize->add_section( $args['id'], array(
		'panel'       => $args['panel'],
        'title'       => $args['title'],
		'description' => $args['description'],
    ) );

	$control_classes = apply_filters( 'enlightenment_customize_typography_control_classes', array(
		'font_color'      => 'WP_Customize_Color_Control',
		'font_family'     => 'Enlightenment_Customize_Font_Family_Control',
		'font_size'       => 'Enlightenment_Customize_Font_Size_Control',
		'font_variant'    => 'Enlightenment_Customize_Font_Variant_Control',
		'line_height'     => 'Enlightenment_Customize_Line_Height_Control',
		'letter_spacing'  => 'Enlightenment_Customize_Font_Size_Control',
		'text_align'      => 'Enlightenment_Customize_Text_Align_Control',
		'text_decoration' => 'Enlightenment_Customize_Text_Decoration_Control',
		'text_transform'  => 'Enlightenment_Customize_Text_Transform_Control',
	) );

	$control_classes = apply_filters( sprintf( 'enlightenment_customize_typography_section_%s_control_classes', $args['id'] ), $control_classes );

	$control_labels = apply_filters( 'enlightenment_customize_typography_control_labels', array(
		'font_color'      => __( 'Color',           'enlightenment' ),
		'font_family'     => __( 'Font Family',     'enlightenment' ),
		'font_size'       => __( 'Font Size',       'enlightenment' ),
		'font_variant'    => __( 'Font Style',      'enlightenment' ),
		'line_height'     => __( 'Line Height',     'enlightenment' ),
		'letter_spacing'  => __( 'Letter Spacing',  'enlightenment' ),
		'text_align'      => __( 'Text Alignment',  'enlightenment' ),
		'text_decoration' => __( 'Text Decoration', 'enlightenment' ),
		'text_transform'  => __( 'Text Transform',  'enlightenment' ),
	) );

	$all_controls = apply_filters( 'enlightenment_customize_typography_all_controls', array_keys( $control_classes ) );

	$common_controls = apply_filters( 'enlightenment_customize_typography_common_controls', array_diff( $all_controls, array(
		'letter_spacing',
		'text_decoration',
		'text_transform',
	) ) );

	if ( 'common' == $args['supported_controls'] ) {
		$args['supported_controls'] = $common_controls;
	} elseif ( 'all' == $args['supported_controls'] ) {
		$args['supported_controls'] = $all_controls;
	}

	$default_settings_args = array(
		'type'              => $args['type'],
		'capability'        => $args['capability'],
		'transport'         => $args['transport'],
		'sanitize_callback' => $args['sanitize_callback'],
	);

	$default_controls_args = array(
		'section' => $args['id'],
	);

	$letter_spacing_default_control_args = array(
		'accepted_units' => array( 'px' ),
		'px_max'         => 2,
		'px_step'        => 0.1,
		'input_attrs'    => array(
			'min' => -2,
		),
	);

	foreach ( $args['supported_controls'] as $setting ) {
		$setting_id   = sprintf( '%s[%s]', $args['id'], $setting );
		$default      = isset( $args['default'][ $setting ] ) ? $args['default'][ $setting ] : '';
		$setting_args = ! empty( $args['settings_args'][ $setting ] ) && is_array( $args['settings_args'][ $setting ] ) ?
			$args['settings_args'][ $setting ] :
			array();
		$control_args = ! empty( $args['controls_args'][ $setting ] ) && is_array( $args['controls_args'][ $setting ] ) ?
			$args['controls_args'][ $setting ] :
			array();

		if ( 'letter_spacing' == $setting ) {
			$control_args = array_merge(
				$letter_spacing_default_control_args,
				$control_args
			);
		}

		$wp_customize->add_setting(
			$setting_id,
			array_merge(
				$default_settings_args,
				array(
			        'default' => $default,
			    ),
				$setting_args
			)
		);

	    $wp_customize->add_control( ( new ReflectionClass( $control_classes[ $setting ] ) )->newInstanceArgs(
			array(
				$wp_customize,
				$setting_id,
				array_merge(
					$default_controls_args,
					array(
				        'label' => $control_labels[ $setting ],
				    ),
					$control_args
				)
			)
		) );
	}
}
