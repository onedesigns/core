"use strict";

( function( useLocalStorage = true ) {
	const theme = ( function() {
		if ( document.documentElement.dataset.bsTheme ) {
			return document.documentElement.dataset.bsTheme;
		}

		if ( useLocalStorage ) {
			const storedTheme = localStorage.getItem( 'enlightenmentBsTheme' );

			if ( storedTheme && 'auto' != storedTheme ) {
				return storedTheme;
			}
		}

		return window.matchMedia( '(prefers-color-scheme: dark)' ).matches ? 'dark' : 'light';
	} )();

	if ( theme ) {
		document.documentElement.dataset.bsTheme = theme;
	}
} )();
