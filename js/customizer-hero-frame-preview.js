/**
 * Reload-free Customizer preview for each Hero slide's independent abstract
 * frame, image position, and image zoom. The path-building math mirrors
 * dq_hero_frame_build_path() (inc/hero-frame.php) exactly, so the live
 * preview always matches what gets rendered server-side after Publish.
 * Runs inside the preview iframe (enqueued via customize_preview_init with
 * the 'customize-preview' dependency, which provides wp.customize here).
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.customize || ! window.dqHeroFrameShapes ) {
		return;
	}

	var SHAPES = window.dqHeroFrameShapes;
	var WIDTH_MULT = window.dqHeroFrameWidthMultipliers || {};
	var INTENSITY_MULT = window.dqHeroFrameIntensityMultipliers || {};
	var POSITION_OFFSET = window.dqHeroFramePositionOffsets || {};

	function average( points ) {
		var sum = 0;
		points.forEach( function ( p ) {
			sum += p[ 0 ];
		} );
		return points.length ? sum / points.length : 0;
	}

	function clamp( value, min, max ) {
		return Math.max( min, Math.min( max, value ) );
	}

	function round4( value ) {
		return parseFloat( value.toFixed( 4 ) );
	}

	function buildPath( shapeKey, intensityKey, widthKey, positionKey ) {
		var shape = SHAPES[ shapeKey ] || SHAPES[ 'soft-wave' ];
		var widthMult = WIDTH_MULT[ widthKey ] || 1;
		var intensityMult = INTENSITY_MULT[ intensityKey ] || 1;
		var positionOffset = POSITION_OFFSET[ positionKey ] || 0;

		var points = shape.points;
		var avgX = average( points );

		var adjusted = points.map( function ( point ) {
			var x = point[ 0 ];
			var y = point[ 1 ];

			x = avgX + ( x - avgX ) * intensityMult;
			x = x * widthMult;
			y = clamp( y + positionOffset, 0.02, 0.98 );

			return [ round4( x ), round4( y ) ];
		} );

		var d = 'M 1,0 L 1,1 L 0,1 ';
		var prev = [ 0, 1 ];

		if ( 'line' === shape.curve ) {
			adjusted.forEach( function ( point ) {
				d += 'L ' + point[ 0 ] + ',' + point[ 1 ] + ' ';
				prev = point;
			} );
		} else {
			adjusted.forEach( function ( point ) {
				var ctrlX = round4( ( prev[ 0 ] + point[ 0 ] ) / 2 );
				var ctrlY = round4( ( prev[ 1 ] + point[ 1 ] ) / 2 );
				d += 'Q ' + ctrlX + ',' + ctrlY + ' ' + point[ 0 ] + ',' + point[ 1 ] + ' ';
				prev = point;
			} );
		}

		d += 'L 0,0 Z';

		return d;
	}

	function currentValue( settingId, fallback ) {
		var setting = wp.customize( settingId );
		return setting ? setting.get() : fallback;
	}

	function slideSettingId( index, field ) {
		return 'dq_theme_settings[hero_slides][' + index + '][' + field + ']';
	}

	function applyFrame( index ) {
		var slideEl = document.getElementById( 'hero-slide-' + index );
		var frameEl = slideEl && slideEl.querySelector( '.academic-hero__image-frame' );

		if ( ! frameEl ) {
			return;
		}

		var enabled = !! currentValue( slideSettingId( index, 'frame_enabled' ), true );
		var shape = currentValue( slideSettingId( index, 'frame_shape' ), 'soft-wave' );
		var intensity = currentValue( slideSettingId( index, 'frame_intensity' ), 'sedang' );
		var width = currentValue( slideSettingId( index, 'frame_width' ), 'sedang' );
		var position = currentValue( slideSettingId( index, 'frame_position' ), 'tengah' );

		frameEl.classList.toggle( 'academic-hero__image-frame--frame-enabled', enabled );
		frameEl.style.clipPath = enabled ? 'url(#dq-hero-frame-clip-' + index + ')' : '';

		var pathEl = document.getElementById( 'dq-hero-frame-clip-path-' + index );
		if ( pathEl ) {
			pathEl.setAttribute( 'd', buildPath( shape, intensity, width, position ) );
		}
	}

	function applyImageStyle( index ) {
		var slideEl = document.getElementById( 'hero-slide-' + index );
		var imgEl = slideEl && slideEl.querySelector( '.academic-hero__image' );

		if ( ! imgEl ) {
			return;
		}

		var position = currentValue( slideSettingId( index, 'image_position' ), 'center' );
		var zoom = parseFloat( currentValue( slideSettingId( index, 'image_zoom' ), '100' ) ) || 100;

		imgEl.style.objectPosition = position;
		imgEl.style.transform = 'scale(' + ( zoom / 100 ) + ')';
	}

	wp.customize.bind( 'preview-ready', function () {
		for ( var i = 0; i < 3; i++ ) {
			( function ( index ) {
				[ 'frame_enabled', 'frame_shape', 'frame_intensity', 'frame_width', 'frame_position' ].forEach( function ( field ) {
					wp.customize( slideSettingId( index, field ), function ( value ) {
						value.bind( function () {
							applyFrame( index );
						} );
					} );
				} );

				[ 'image_position', 'image_zoom' ].forEach( function ( field ) {
					wp.customize( slideSettingId( index, field ), function ( value ) {
						value.bind( function () {
							applyImageStyle( index );
						} );
					} );
				} );

				applyFrame( index );
				applyImageStyle( index );
			} )( i );
		}
	} );
} )( window.wp );
