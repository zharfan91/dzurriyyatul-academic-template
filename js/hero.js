/**
 * Hero carousel: tab/dot/arrow controls, keyboard navigation, autoplay
 * (enable + duration set via Appearance → Customize → Hero Slides →
 * Pengaturan Carousel), pause on hover/focus, and a prefers-reduced-motion
 * escape hatch (§25-26).
 */
( function () {
	'use strict';

	var stage = document.getElementById( 'heroCarouselStage' );
	if ( ! stage ) {
		return;
	}

	var slides = Array.prototype.slice.call( stage.querySelectorAll( '.academic-hero__slide' ) );
	var tabs = Array.prototype.slice.call( document.querySelectorAll( '.academic-hero__tab' ) );
	var dots = Array.prototype.slice.call( document.querySelectorAll( '.academic-hero__dot' ) );
	var counter = document.getElementById( 'heroSlideCounter' );
	var prevButtons = [ document.getElementById( 'heroPrevTop' ), document.getElementById( 'heroPrevBottom' ) ].filter( Boolean );
	var nextButtons = [ document.getElementById( 'heroNextTop' ), document.getElementById( 'heroNextBottom' ) ].filter( Boolean );

	var total = slides.length;
	if ( total < 2 ) {
		return;
	}

	var current = 0;
	var timer = null;
	var intervalTime = parseInt( stage.getAttribute( 'data-autoplay' ), 10 ) || 6000;
	var autoplayEnabled = '0' !== stage.getAttribute( 'data-autoplay-enabled' );
	var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function show( index ) {
		if ( index < 0 ) {
			index = total - 1;
		}
		if ( index >= total ) {
			index = 0;
		}
		current = index;

		slides.forEach( function ( slide, idx ) {
			var isActive = idx === current;
			slide.classList.toggle( 'is-active', isActive );
			slide.setAttribute( 'aria-hidden', isActive ? 'false' : 'true' );
		} );

		tabs.forEach( function ( tab, idx ) {
			var isActive = idx === current;
			tab.classList.toggle( 'is-active', isActive );
			tab.setAttribute( 'aria-current', isActive ? 'true' : 'false' );
		} );

		dots.forEach( function ( dot, idx ) {
			dot.classList.toggle( 'is-active', idx === current );
		} );

		if ( counter ) {
			counter.textContent = String( current + 1 ).padStart( 2, '0' ) + ' / ' + String( total ).padStart( 2, '0' );
		}
	}

	function start() {
		// Always clear any running timer first (not just when the guard below
		// passes) so a live Customizer-preview toggle from enabled to disabled
		// — which calls start() again to apply the change — actually stops
		// the interval instead of merely skipping the creation of a new one.
		stop();
		if ( reduceMotion || ! autoplayEnabled ) {
			return;
		}
		timer = window.setInterval( function () {
			show( current + 1 );
		}, intervalTime );
	}

	function stop() {
		if ( timer ) {
			window.clearInterval( timer );
			timer = null;
		}
	}

	tabs.forEach( function ( tab, idx ) {
		tab.addEventListener( 'click', function () {
			show( idx );
			start();
		} );
	} );

	dots.forEach( function ( dot, idx ) {
		dot.addEventListener( 'click', function () {
			show( idx );
			start();
		} );
	} );

	prevButtons.forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			show( current - 1 );
			start();
		} );
	} );

	nextButtons.forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			show( current + 1 );
			start();
		} );
	} );

	stage.addEventListener( 'keydown', function ( event ) {
		if ( 'ArrowLeft' === event.key ) {
			show( current - 1 );
			start();
		} else if ( 'ArrowRight' === event.key ) {
			show( current + 1 );
			start();
		}
	} );

	stage.addEventListener( 'mouseenter', stop );
	stage.addEventListener( 'mouseleave', start );
	stage.addEventListener( 'focusin', stop );
	stage.addEventListener( 'focusout', start );

	show( 0 );
	start();

	// Reload-free Customizer preview: when running inside the preview iframe
	// (Appearance → Customize → Hero Slides → Pengaturan Carousel), react to
	// the autoplay enable/duration settings changing and restart the timer
	// with the new value instead of waiting for a full iframe refresh.
	if ( window.wp && window.wp.customize ) {
		wp.customize.bind( 'preview-ready', function () {
			wp.customize( 'dq_theme_settings[hero_autoplay_enabled]', function ( value ) {
				value.bind( function ( newValue ) {
					autoplayEnabled = !! newValue;
					start();
				} );
			} );

			wp.customize( 'dq_theme_settings[hero_autoplay_duration]', function ( value ) {
				value.bind( function ( newValue ) {
					intervalTime = parseInt( newValue, 10 ) || 6000;
					start();
				} );
			} );
		} );
	}
} )();
