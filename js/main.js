/**
 * Consultation form AJAX submit + in-page anchor smooth-scroll fallback.
 * Uses the nonce/ajaxUrl localized via wp_localize_script (see inc/enqueue.php).
 */
( function () {
	'use strict';

	var form = document.getElementById( 'dqConsultationForm' );

	if ( form && window.dqSettings ) {
		var status = document.getElementById( 'dqFormStatus' );
		var submitButton = form.querySelector( 'button[type="submit"]' );

		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();

			if ( status ) {
				status.textContent = '';
				status.className = 'academic-form__status';
			}

			if ( submitButton ) {
				submitButton.disabled = true;
			}

			var formData = new FormData( form );
			formData.append( 'action', 'dq_consultation_form' );
			formData.append( 'nonce', window.dqSettings.nonce );

			fetch( window.dqSettings.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: formData,
			} )
				.then( function ( response ) {
					return response.json();
				} )
				.then( function ( result ) {
					if ( ! status ) {
						return;
					}

					if ( result && result.success ) {
						status.textContent = result.data && result.data.message ? result.data.message : '';
						status.classList.add( 'is-success' );
						form.reset();
					} else {
						status.textContent = result && result.data && result.data.message ? result.data.message : 'Terjadi kesalahan. Silakan coba lagi.';
						status.classList.add( 'is-error' );
					}
				} )
				.catch( function () {
					if ( status ) {
						status.textContent = 'Tidak dapat terhubung ke server. Silakan hubungi kami melalui WhatsApp.';
						status.classList.add( 'is-error' );
					}
				} )
				.finally( function () {
					if ( submitButton ) {
						submitButton.disabled = false;
					}
				} );
		} );
	}

	// Smooth-scroll in-page anchors that point at an id on the current page
	// (progressive enhancement — CSS scroll-behavior already handles this
	// in modern browsers; this only adds an offset for the sticky header).
	var header = document.getElementById( 'masthead' );

	document.querySelectorAll( 'a[href^="#"]' ).forEach( function ( link ) {
		var hash = link.getAttribute( 'href' );
		if ( ! hash || hash.length < 2 ) {
			return;
		}

		var target = document.getElementById( hash.slice( 1 ) );
		if ( ! target ) {
			return;
		}

		link.addEventListener( 'click', function ( event ) {
			event.preventDefault();
			var headerHeight = header ? header.offsetHeight : 0;
			var top = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 16;
			window.scrollTo( { top: top, behavior: 'smooth' } );
			target.setAttribute( 'tabindex', '-1' );
			target.focus( { preventScroll: true } );
		} );
	} );
} )();
