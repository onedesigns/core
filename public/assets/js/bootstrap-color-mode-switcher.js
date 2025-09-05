"use strict";

( function() {
	const storedTheme = localStorage.getItem( 'enlightenmentBsTheme' );

	if ( storedTheme ) {
		const activeItem = document.querySelector ( '.active[data-color-mode-value]' );

		if ( activeItem.dataset.colorModeValue != storedTheme ) {
			const colorModes       = {};
			const dropdownToggle   = document.querySelector( '[data-js-selector="color-mode-switcher-toggle"]' );
			const currentIcon      = dropdownToggle.querySelector( '.current-color-mode-icon' );
			const currentName      = dropdownToggle.querySelector( '.current-color-mode-name' );
			const currentItem      = document.querySelector( '[data-color-mode-value="%s"]'.replace( '%s', storedTheme ) );
			const activeIconClass  = 'fa-%s'.replace( '%s', colorModes[ activeItem.dataset.colorModeValue ].icon );
			const currentIconClass = 'fa-%s'.replace( '%s', colorModes[ storedTheme ].icon );
			const currentIconName  = colorModes[ storedTheme ].name;

			currentIcon.classList.remove( activeIconClass );
			currentIcon.classList.add( currentIconClass );
			currentName.innerText = currentIconName;

			activeItem.classList.remove( 'active' );
			activeItem.removeAttribute( 'aria-selected' );

			currentItem.classList.add( 'active' );
			currentItem.setAttribute( 'aria-selected', 'true' );
		}
	}
} )();
