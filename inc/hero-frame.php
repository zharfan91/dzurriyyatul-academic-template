<?php
/**
 * Abstract left-edge frame for each Hero slide's image.
 *
 * The frame is an SVG <clipPath> in objectBoundingBox units (0-1), so one
 * path definition scales to any image size — CSS clip-path: path() has no
 * percentage units, which is why this isn't done in CSS alone. The top,
 * right and bottom edges are always straight; only the left edge is an
 * organic curve, drawn as a smooth Catmull-Rom spline (converted to cubic
 * Béziers) through each shape's control points. The curve's end points sit
 * inset from the left edge, so it never degenerates into a corner-to-corner
 * diagonal cut.
 *
 * Shapes are defined here (not as static .svg files) because intensity and
 * width reshape the path per slide; js/customizer-hero-frame-preview.js
 * mirrors dq_hero_frame_build_path() exactly for live preview.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The 8 selectable frame shapes. Points are [x, y] in objectBoundingBox
 * space, listed bottom (y = 1) to top (y = 0); x is how far the image's
 * visible left edge sits in from the frame's left side at that height.
 * 'curve' => 'smooth' draws a spline through the points, 'line' joins them
 * with straight segments (Sharp Abstract only).
 *
 * @return array<string,array{label:string,curve:string,points:array<array{0:float,1:float}>}>
 */
function dq_hero_frame_shapes() {
	return array(
		'soft-wave'      => array(
			'label'  => __( 'Soft Wave', 'dzurriyyatul-academic' ),
			'curve'  => 'smooth',
			'points' => array( array( 0.16, 1 ), array( 0.06, 0.7 ), array( 0.12, 0.38 ), array( 0.04, 0.1 ), array( 0.08, 0 ) ),
		),
		'organic-curve'  => array(
			'label'  => __( 'Organic Curve', 'dzurriyyatul-academic' ),
			'curve'  => 'smooth',
			'points' => array( array( 0.22, 1 ), array( 0.03, 0.55 ), array( 0.2, 0 ) ),
		),
		'deep-wave'      => array(
			'label'  => __( 'Deep Wave', 'dzurriyyatul-academic' ),
			'curve'  => 'smooth',
			'points' => array( array( 0.26, 1 ), array( 0.03, 0.68 ), array( 0.24, 0.34 ), array( 0.06, 0 ) ),
		),
		's-curve'        => array(
			'label'  => __( 'S-Curve', 'dzurriyyatul-academic' ),
			'curve'  => 'smooth',
			'points' => array( array( 0.26, 1 ), array( 0.22, 0.78 ), array( 0.12, 0.5 ), array( 0.03, 0.22 ), array( 0.02, 0 ) ),
		),
		'liquid-curve'   => array(
			'label'  => __( 'Liquid Curve', 'dzurriyyatul-academic' ),
			'curve'  => 'smooth',
			'points' => array( array( 0.14, 1 ), array( 0.03, 0.8 ), array( 0.17, 0.58 ), array( 0.06, 0.36 ), array( 0.15, 0.15 ), array( 0.09, 0 ) ),
		),
		'double-wave'    => array(
			'label'  => __( 'Double Wave', 'dzurriyyatul-academic' ),
			'curve'  => 'smooth',
			'points' => array( array( 0.06, 1 ), array( 0.2, 0.75 ), array( 0.05, 0.5 ), array( 0.2, 0.25 ), array( 0.06, 0 ) ),
		),
		'sharp-abstract' => array(
			'label'  => __( 'Sharp Abstract', 'dzurriyyatul-academic' ),
			'curve'  => 'line',
			'points' => array( array( 0.2, 1 ), array( 0.05, 0.64 ), array( 0.16, 0.4 ), array( 0.03, 0 ) ),
		),
		'minimal-curve'  => array(
			'label'  => __( 'Minimal Curve', 'dzurriyyatul-academic' ),
			'curve'  => 'smooth',
			'points' => array( array( 0.09, 1 ), array( 0.02, 0.5 ), array( 0.09, 0 ) ),
		),
	);
}

/**
 * Width presets: scale how far the curve reaches into the image.
 *
 * @return array<string,float>
 */
function dq_hero_frame_width_multipliers() {
	return array(
		'sempit' => 0.65,
		'sedang' => 1.0,
		'lebar'  => 1.4,
	);
}

/**
 * Intensity presets: flatten (low) or exaggerate (high) each point's
 * distance from the shape's average reach — i.e. how pronounced the waves
 * are — without changing the overall reach.
 *
 * @return array<string,float>
 */
function dq_hero_frame_intensity_multipliers() {
	return array(
		'ringan' => 0.5,
		'sedang' => 1.0,
		'kuat'   => 1.5,
	);
}

/**
 * Round to 4 decimals, half away from zero. Deliberately not PHP's round():
 * its handling of binary-float ties (e.g. 0.12025) differs from JS and has
 * changed across PHP versions, and this must match roundFrame() in
 * js/customizer-hero-frame-preview.js digit-for-digit.
 *
 * @param float $value
 * @return float
 */
function dq_hero_frame_round( $value ) {
	$n    = (float) sprintf( '%.6F', $value * 10000 );
	$sign = $n < 0 ? -1 : 1;
	return ( $sign * floor( abs( $n ) + 0.5 ) ) / 10000 + 0; // + 0 turns -0 into 0.
}

/**
 * Build the SVG path `d` attribute (objectBoundingBox space) for a shape +
 * intensity + width combination. Mirrored exactly in
 * js/customizer-hero-frame-preview.js.
 *
 * @param string $shape_key
 * @param string $intensity_key
 * @param string $width_key
 * @return string
 */
function dq_hero_frame_build_path( $shape_key, $intensity_key, $width_key ) {
	$shapes = dq_hero_frame_shapes();
	$shape  = isset( $shapes[ $shape_key ] ) ? $shapes[ $shape_key ] : $shapes['soft-wave'];

	$widths      = dq_hero_frame_width_multipliers();
	$intensities = dq_hero_frame_intensity_multipliers();
	$width_mult  = isset( $widths[ $width_key ] ) ? $widths[ $width_key ] : 1.0;
	$int_mult    = isset( $intensities[ $intensity_key ] ) ? $intensities[ $intensity_key ] : 1.0;

	$points = $shape['points'];
	$avg_x  = array_sum( array_column( $points, 0 ) ) / count( $points );

	$p = array();
	foreach ( $points as $point ) {
		$x   = ( $avg_x + ( $point[0] - $avg_x ) * $int_mult ) * $width_mult;
		$x   = max( 0, min( 0.45, $x ) );
		$p[] = array( dq_hero_frame_round( $x ), $point[1] );
	}

	$last = count( $p ) - 1;

	// Straight top edge from the curve's top end to the top-right corner,
	// straight right and bottom edges, then the organic left edge upward.
	$d = 'M ' . $p[ $last ][0] . ',0 L 1,0 L 1,1 L ' . $p[0][0] . ',1 ';

	for ( $i = 0; $i < $last; $i++ ) {
		if ( 'line' === $shape['curve'] ) {
			$d .= 'L ' . $p[ $i + 1 ][0] . ',' . $p[ $i + 1 ][1] . ' ';
			continue;
		}

		$p0 = $p[ max( 0, $i - 1 ) ];
		$p1 = $p[ $i ];
		$p2 = $p[ $i + 1 ];
		$p3 = $p[ min( $last, $i + 2 ) ];

		$c1x = dq_hero_frame_round( $p1[0] + ( $p2[0] - $p0[0] ) / 6 );
		$c1y = dq_hero_frame_round( $p1[1] + ( $p2[1] - $p0[1] ) / 6 );
		$c2x = dq_hero_frame_round( $p2[0] - ( $p3[0] - $p1[0] ) / 6 );
		$c2y = dq_hero_frame_round( $p2[1] - ( $p3[1] - $p1[1] ) / 6 );

		$d .= 'C ' . $c1x . ',' . $c1y . ' ' . $c2x . ',' . $c2y . ' ' . $p2[0] . ',' . $p2[1] . ' ';
	}

	return $d . 'Z';
}

/**
 * One slide's frame settings, from its dq_get_hero_slide() array.
 *
 * @param array $slide
 * @return array{enabled:bool,shape:string,intensity:string,width:string}
 */
function dq_hero_frame_settings_for_slide( $slide ) {
	return array(
		'enabled'   => (bool) $slide['frame_enabled'],
		'shape'     => $slide['frame_shape'],
		'intensity' => $slide['frame_intensity'],
		'width'     => $slide['frame_width'],
	);
}

/**
 * Render one hidden <svg><defs> holding a <clipPath> per slide index (0-2).
 * Every index gets one even when that slide's frame is off, so the
 * Customizer preview can switch a frame on without creating SVG nodes.
 *
 * @param array<int,array> $slides Slide arrays keyed by original index.
 */
function dq_hero_frame_render_clip_paths( $slides ) {
	?>
	<svg class="academic-hero__clip-defs" width="0" height="0" aria-hidden="true" focusable="false">
		<defs>
			<?php for ( $i = 0; $i < 3; $i++ ) : ?>
				<?php
				$settings = dq_hero_frame_settings_for_slide( isset( $slides[ $i ] ) ? $slides[ $i ] : dq_hero_slide_defaults() );
				$d        = dq_hero_frame_build_path( $settings['shape'], $settings['intensity'], $settings['width'] );
				?>
				<clipPath id="dq-hero-frame-clip-<?php echo esc_attr( $i ); ?>" clipPathUnits="objectBoundingBox">
					<path id="dq-hero-frame-clip-path-<?php echo esc_attr( $i ); ?>" d="<?php echo esc_attr( $d ); ?>"></path>
				</clipPath>
			<?php endfor; ?>
		</defs>
	</svg>
	<?php
}

/**
 * @param mixed  $value
 * @param array  $allowed_keys
 * @param string $default
 * @return string
 */
function dq_hero_frame_sanitize_key( $value, $allowed_keys, $default ) {
	$value = sanitize_key( $value );
	return in_array( $value, $allowed_keys, true ) ? $value : $default;
}

/**
 * @param mixed $value
 * @return string
 */
function dq_hero_frame_sanitize_shape( $value ) {
	return dq_hero_frame_sanitize_key( $value, array_keys( dq_hero_frame_shapes() ), 'soft-wave' );
}

/**
 * @param mixed $value
 * @return string
 */
function dq_hero_frame_sanitize_intensity( $value ) {
	return dq_hero_frame_sanitize_key( $value, array_keys( dq_hero_frame_intensity_multipliers() ), 'sedang' );
}

/**
 * @param mixed $value
 * @return string
 */
function dq_hero_frame_sanitize_width( $value ) {
	return dq_hero_frame_sanitize_key( $value, array_keys( dq_hero_frame_width_multipliers() ), 'sedang' );
}

/**
 * Shape-picker grid/thumbnail CSS, Customizer controls screen only.
 */
function dq_hero_frame_enqueue_controls_assets() {
	wp_enqueue_style( 'dq-hero-frame-controls', DQ_THEME_URI . '/assets/css/hero-frame-controls.css', array(), DQ_THEME_VERSION );
}
add_action( 'customize_controls_enqueue_scripts', 'dq_hero_frame_enqueue_controls_assets' );

/**
 * Live preview for frame + image position/zoom (see
 * js/customizer-hero-frame-preview.js).
 */
function dq_hero_frame_enqueue_preview_script() {
	wp_enqueue_script(
		'dq-hero-frame-preview',
		DQ_THEME_URI . '/js/customizer-hero-frame-preview.js',
		array( 'customize-preview' ),
		DQ_THEME_VERSION,
		true
	);

	wp_add_inline_script(
		'dq-hero-frame-preview',
		'window.dqHeroFrame = ' . wp_json_encode(
			array(
				'shapes'      => dq_hero_frame_shapes(),
				'widths'      => dq_hero_frame_width_multipliers(),
				'intensities' => dq_hero_frame_intensity_multipliers(),
			)
		) . ';',
		'before'
	);
}
add_action( 'customize_preview_init', 'dq_hero_frame_enqueue_preview_script' );
