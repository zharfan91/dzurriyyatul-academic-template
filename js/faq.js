/**
 * Accessible FAQ accordion. Each trigger is a real <button>, so it is
 * keyboard-operable by default; this script only toggles aria-expanded
 * and the `hidden` attribute on its panel (§21, §34, §42).
 */
( function () {
	'use strict';

	var accordion = document.querySelector( '[data-academic-accordion]' );
	if ( ! accordion ) {
		return;
	}

	var triggers = Array.prototype.slice.call( accordion.querySelectorAll( '.academic-accordion__trigger' ) );

	triggers.forEach( function ( trigger ) {
		trigger.addEventListener( 'click', function () {
			var panel = document.getElementById( trigger.getAttribute( 'aria-controls' ) );
			var isExpanded = 'true' === trigger.getAttribute( 'aria-expanded' );

			trigger.setAttribute( 'aria-expanded', isExpanded ? 'false' : 'true' );

			if ( panel ) {
				panel.hidden = isExpanded;
			}
		} );
	} );
} )();
