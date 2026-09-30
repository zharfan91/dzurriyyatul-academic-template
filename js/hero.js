/**
 * Hero carousel: dot indicator + counter, previous/next, arrow
 * keys, touch swipe, autoplay (Customizer → Hero Slides → Pengaturan
 * Carousel) paused on hover/focus. Autoplay and the slide effect run for
 * every visitor, including prefers-reduced-motion (site owner's choice).
 *
 * initHero() is re-run whenever the Customizer's selective refresh
 * re-renders the hero, so preview changes never leave a stale timer behind.
 */
( function () {
	'use strict';

	var teardown = null;

	function initHero() {
		if ( teardown ) {
			teardown();
			teardown = null;
		}

		var hero = document.getElementById( 'beranda' );
		var stage = document.getElementById( 'heroCarouselStage' );
		if ( ! hero || ! stage ) {
			return;
		}

		var slides = Array.prototype.slice.call( stage.querySelectorAll( '.academic-hero__slide' ) );
		var total = slides.length;
		if ( total < 2 ) {
			return;
		}

		var dots = Array.prototype.slice.call( hero.querySelectorAll( '.academic-hero__dot' ) );
		var counter = document.getElementById( 'heroSlideCounter' );
		var prev = document.getElementById( 'heroPrevBottom' );
		var next = document.getElementById( 'heroNextBottom' );

		var current = 0;
		var timer = null;
		var intervalTime = parseInt( stage.getAttribute( 'data-autoplay' ), 10 ) || 6000;
		var autoplay = '1' === stage.getAttribute( 'data-autoplay-enabled' );
		var pad = function ( n ) {
			return String( n ).padStart( 2, '0' );
		};

		function show( index ) {
			var target = ( index + total ) % total;

			// Enables the staged text/image entrance (sections.css) from the
			// first real slide change on, never on the initial paint.
			if ( target !== current ) {
				hero.classList.add( 'academic-hero--animated' );
			}
			current = target;

			slides.forEach( function ( slide, idx ) {
				var active = idx === current;
				slide.classList.toggle( 'is-active', active );
				slide.setAttribute( 'aria-hidden', active ? 'false' : 'true' );
				if ( 'inert' in slide ) {
					slide.inert = ! active;
				}
			} );

			dots.forEach( function ( dot, idx ) {
				dot.classList.toggle( 'is-active', idx === current );
			} );

			if ( counter ) {
				counter.textContent = pad( current + 1 ) + ' / ' + pad( total );
			}
		}

		function stop() {
			if ( timer ) {
				window.clearInterval( timer );
				timer = null;
			}
		}

		function start() {
			stop();
			if ( ! autoplay ) {
				return;
			}
			timer = window.setInterval( function () {
				show( current + 1 );
			}, intervalTime );
		}

		function go( index ) {
			show( index );
			start();
		}

		var listeners = [];
		function on( el, type, fn, opts ) {
			if ( ! el ) {
				return;
			}
			el.addEventListener( type, fn, opts );
			listeners.push( [ el, type, fn, opts ] );
		}

		dots.forEach( function ( el ) {
			on( el, 'click', function () {
				go( parseInt( el.getAttribute( 'data-slide-index' ), 10 ) || 0 );
			} );
		} );

		on( prev, 'click', function () {
			go( current - 1 );
		} );
		on( next, 'click', function () {
			go( current + 1 );
		} );

		on( stage, 'keydown', function ( event ) {
			if ( 'ArrowLeft' === event.key ) {
				go( current - 1 );
			} else if ( 'ArrowRight' === event.key ) {
				go( current + 1 );
			}
		} );

		var touchX = null;
		on( stage, 'touchstart', function ( event ) {
			touchX = event.touches[ 0 ].clientX;
		}, { passive: true } );
		on( stage, 'touchend', function ( event ) {
			if ( null === touchX ) {
				return;
			}
			var delta = event.changedTouches[ 0 ].clientX - touchX;
			touchX = null;
			if ( Math.abs( delta ) > 50 ) {
				go( delta < 0 ? current + 1 : current - 1 );
			}
		}, { passive: true } );

		on( hero, 'mouseenter', stop );
		on( hero, 'mouseleave', start );
		on( hero, 'focusin', stop );
		on( hero, 'focusout', start );

		show( 0 );
		start();

		teardown = function () {
			stop();
			listeners.forEach( function ( l ) {
				l[ 0 ].removeEventListener( l[ 1 ], l[ 2 ], l[ 3 ] );
			} );
		};
	}

	initHero();

	// Customizer preview: the hero is re-rendered by the 'dq_hero' partial.
	if ( window.wp && window.wp.customize ) {
		window.wp.customize.bind( 'preview-ready', function () {
			var selectiveRefresh = window.wp.customize.selectiveRefresh;
			if ( ! selectiveRefresh ) {
				return;
			}
			selectiveRefresh.bind( 'partial-content-rendered', function ( placement ) {
				if ( placement && placement.partial && 'dq_hero' === placement.partial.id ) {
					initHero();
				}
			} );
		} );
	}
} )();
