/**
 * Hero carousel: tab/dot/arrow controls, keyboard navigation, 7s autoplay,
 * pause on hover/focus, and a prefers-reduced-motion escape hatch (§25-26).
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
	var intervalTime = parseInt( stage.getAttribute( 'data-autoplay' ), 10 ) || 7000;
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
		if ( reduceMotion ) {
			return;
		}
		stop();
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
} )();
