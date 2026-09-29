/**
 * Reload-free Customizer preview for the Hero image's abstract frame.
 * Mirrors dq_hero_frame_build_path() (inc/hero-frame.php) exactly, so the
 * live preview always matches what gets rendered server-side after Publish.
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

	function applyFrame() {
		var enabled = !! currentValue( 'dq_hero_frame_enabled', true );
		var shape = currentValue( 'dq_hero_frame_shape', 'soft-wave' );
		var intensity = currentValue( 'dq_hero_frame_intensity', 'sedang' );
		var width = currentValue( 'dq_hero_frame_width', 'sedang' );
		var position = currentValue( 'dq_hero_frame_position', 'tengah' );

		document.querySelectorAll( '.academic-hero' ).forEach( function ( hero ) {
			hero.classList.toggle( 'academic-hero--frame-enabled', enabled );
		} );

		if ( ! enabled ) {
			return;
		}

		var pathEl = document.getElementById( 'dq-hero-frame-clip-path' );
		if ( pathEl ) {
			pathEl.setAttribute( 'd', buildPath( shape, intensity, width, position ) );
		}
	}

	wp.customize.bind( 'preview-ready', function () {
		[
			'dq_hero_frame_enabled',
			'dq_hero_frame_shape',
			'dq_hero_frame_intensity',
			'dq_hero_frame_width',
			'dq_hero_frame_position',
		].forEach( function ( settingId ) {
			wp.customize( settingId, function ( value ) {
				value.bind( applyFrame );
			} );
		} );

		applyFrame();
	} );
} )( window.wp );
