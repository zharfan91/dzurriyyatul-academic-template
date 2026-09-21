/**
 * Sticky navigation + accessible mobile drawer.
 * Vanilla JS, no dependencies. Every lookup is guarded so a missing
 * element (e.g. on a template without the WhatsApp CTA) never throws.
 */
( function () {
	'use strict';

	var toggle = document.getElementById( 'academicMobileToggle' );
	var drawer = document.getElementById( 'academicMobileDrawer' );

	if ( ! toggle || ! drawer ) {
		return;
	}

	var focusableSelector = 'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])';
	var lastFocused = null;

	function openDrawer() {
		lastFocused = document.activeElement;
		drawer.hidden = false;
		toggle.setAttribute( 'aria-expanded', 'true' );
		document.addEventListener( 'keydown', onKeydown );

		var first = drawer.querySelector( focusableSelector );
		if ( first ) {
			first.focus();
		}
	}

	function closeDrawer() {
		drawer.hidden = true;
		toggle.setAttribute( 'aria-expanded', 'false' );
		document.removeEventListener( 'keydown', onKeydown );

		if ( lastFocused ) {
			lastFocused.focus();
		}
	}

	function onKeydown( event ) {
		if ( 'Escape' === event.key ) {
			closeDrawer();
			return;
		}

		if ( 'Tab' !== event.key ) {
			return;
		}

		var focusables = Array.prototype.slice.call( drawer.querySelectorAll( focusableSelector ) );
		if ( ! focusables.length ) {
			return;
		}

		var first = focusables[ 0 ];
		var last = focusables[ focusables.length - 1 ];

		if ( event.shiftKey && document.activeElement === first ) {
			event.preventDefault();
			last.focus();
		} else if ( ! event.shiftKey && document.activeElement === last ) {
			event.preventDefault();
			first.focus();
		}
	}

	toggle.addEventListener( 'click', function () {
		if ( drawer.hidden ) {
			openDrawer();
		} else {
			closeDrawer();
		}
	} );

	drawer.querySelectorAll( 'a' ).forEach( function ( link ) {
		link.addEventListener( 'click', closeDrawer );
	} );

	window.addEventListener( 'resize', function () {
		if ( window.innerWidth >= 1200 && ! drawer.hidden ) {
			closeDrawer();
		}
	} );
} )();
