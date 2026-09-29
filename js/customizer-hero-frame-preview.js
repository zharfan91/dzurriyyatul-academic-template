/**
 * Instant Customizer preview for each Hero slide's abstract frame and image
 * position/zoom. buildPath() mirrors dq_hero_frame_build_path()
 * (inc/hero-frame.php) exactly. Content fields are previewed separately via
 * selective refresh (see dq_customize_register()).
 */
( function ( wp, config ) {
	'use strict';

	if ( ! wp || ! wp.customize || ! config ) {
		return;
	}

	// Same algorithm as dq_hero_frame_round() in PHP, so paths match exactly.
	function round4( value ) {
		var n = parseFloat( ( value * 10000 ).toFixed( 6 ) );
		var sign = n < 0 ? -1 : 1;
		return ( sign * Math.floor( Math.abs( n ) + 0.5 ) ) / 10000 + 0;
	}

	function buildPath( shapeKey, intensityKey, widthKey ) {
		var shape = config.shapes[ shapeKey ] || config.shapes[ 'soft-wave' ];
		var widthMult = config.widths[ widthKey ] || 1;
		var intMult = config.intensities[ intensityKey ] || 1;

		var avgX = shape.points.reduce( function ( sum, point ) {
			return sum + point[ 0 ];
		}, 0 ) / shape.points.length;

		var p = shape.points.map( function ( point ) {
			var x = ( avgX + ( point[ 0 ] - avgX ) * intMult ) * widthMult;
			x = Math.max( 0, Math.min( 0.45, x ) );
			return [ round4( x ), point[ 1 ] ];
		} );

		var last = p.length - 1;
		var d = 'M ' + p[ last ][ 0 ] + ',0 L 1,0 L 1,1 L ' + p[ 0 ][ 0 ] + ',1 ';

		for ( var i = 0; i < last; i++ ) {
			if ( 'line' === shape.curve ) {
				d += 'L ' + p[ i + 1 ][ 0 ] + ',' + p[ i + 1 ][ 1 ] + ' ';
				continue;
			}

			var p0 = p[ Math.max( 0, i - 1 ) ];
			var p1 = p[ i ];
			var p2 = p[ i + 1 ];
			var p3 = p[ Math.min( last, i + 2 ) ];

			var c1x = round4( p1[ 0 ] + ( p2[ 0 ] - p0[ 0 ] ) / 6 );
			var c1y = round4( p1[ 1 ] + ( p2[ 1 ] - p0[ 1 ] ) / 6 );
			var c2x = round4( p2[ 0 ] - ( p3[ 0 ] - p1[ 0 ] ) / 6 );
			var c2y = round4( p2[ 1 ] - ( p3[ 1 ] - p1[ 1 ] ) / 6 );

			d += 'C ' + c1x + ',' + c1y + ' ' + c2x + ',' + c2y + ' ' + p2[ 0 ] + ',' + p2[ 1 ] + ' ';
		}

		return d + 'Z';
	}

	function settingId( index, field ) {
		return 'dq_theme_settings[hero_slides][' + index + '][' + field + ']';
	}

	function value( index, field, fallback ) {
		var setting = wp.customize( settingId( index, field ) );
		return setting ? setting.get() : fallback;
	}

	function applyFrame( index ) {
		var pathEl = document.getElementById( 'dq-hero-frame-clip-path-' + index );
		var frameEl = document.querySelector( '#hero-slide-' + index + ' .academic-hero__image-frame' );
		var enabled = !! value( index, 'frame_enabled', true );

		if ( pathEl ) {
			pathEl.setAttribute( 'd', buildPath(
				value( index, 'frame_shape', 'soft-wave' ),
				value( index, 'frame_intensity', 'sedang' ),
				value( index, 'frame_width', 'sedang' )
			) );
		}

		if ( frameEl ) {
			frameEl.classList.toggle( 'academic-hero__image-frame--framed', enabled );
			frameEl.style.clipPath = enabled ? 'url(#dq-hero-frame-clip-' + index + ')' : '';
		}
	}

	function applyImage( index ) {
		var imgEl = document.querySelector( '#hero-slide-' + index + ' .academic-hero__image' );
		if ( ! imgEl ) {
			return;
		}
		var zoom = parseFloat( value( index, 'image_zoom', '100' ) ) || 100;
		imgEl.style.objectPosition = value( index, 'image_position', 'center' );
		imgEl.style.transform = 'scale(' + ( zoom / 100 ) + ')';
	}

	wp.customize.bind( 'preview-ready', function () {
		[ 0, 1, 2 ].forEach( function ( index ) {
			[ 'frame_enabled', 'frame_shape', 'frame_intensity', 'frame_width' ].forEach( function ( field ) {
				wp.customize( settingId( index, field ), function ( setting ) {
					setting.bind( function () {
						applyFrame( index );
					} );
				} );
			} );

			[ 'image_position', 'image_zoom' ].forEach( function ( field ) {
				wp.customize( settingId( index, field ), function ( setting ) {
					setting.bind( function () {
						applyImage( index );
					} );
				} );
			} );
		} );
	} );
} )( window.wp, window.dqHeroFrame );
