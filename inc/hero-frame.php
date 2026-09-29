<?php
/**
 * Abstract left-edge frame for the Hero image — an enhancement layered onto
 * the existing hero-carousel.php markup (see .academic-hero__image-frame),
 * not a replacement for it. Top/right/bottom edges of the image always stay
 * straight; only the left edge gets an organic/abstract path, built as an
 * SVG clipPath (objectBoundingBox units, so one path definition scales
 * correctly to any image size — the reason this uses an SVG <clipPath>
 * rather than CSS clip-path: path(), which has no percentage-unit support).
 *
 * Each of the 3 hero slides has its OWN independent frame configuration
 * (enable/shape/intensity/width/position), stored alongside that slide's
 * other fields in dq_theme_settings[hero_slides][$i][...] — registered as
 * part of the main per-slide field loop in inc/customizer.php, not as a
 * separate global theme_mod section. This file keeps the shape math (proven
 * correct against a PHP/JS cross-validation harness) plus the per-slide
 * clip-path rendering and the sanitize callbacks the Customizer controls use.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The 8 selectable frame shapes. Each is a smooth or angular path along the
 * LEFT edge only, described as a list of [x, y] control points in
 * objectBoundingBox space (0-1) — y runs top(0) to bottom(1), x is how far
 * the curve bulges right into the image (0 = flush with the left edge).
 * Points are listed bottom-to-top (path travels from bottom-left corner up
 * to top-left corner).
 *
 * 'curve' => 'quadratic' joins points with smooth Q-curves through each
 * point as its own control point (organic look); 'line' joins them with
 * straight L segments (faceted/angular look, used by Sharp Abstract).
 *
 * @return array<string,array{label:string,curve:string,points:array<array{0:float,1:float}>}>
 */
function dq_hero_frame_shapes() {
	return array(
		'soft-wave'      => array(
			'label'  => __( 'Soft Wave', 'dzurriyyatul-academic' ),
			'curve'  => 'quadratic',
			'points' => array( array( 0.09, 0.72 ), array( 0.05, 0.28 ) ),
		),
		'deep-wave'      => array(
			'label'  => __( 'Deep Wave', 'dzurriyyatul-academic' ),
			'curve'  => 'quadratic',
			'points' => array( array( 0.20, 0.68 ), array( 0.18, 0.32 ) ),
		),
		'organic-curve'  => array(
			'label'  => __( 'Organic Curve', 'dzurriyyatul-academic' ),
			'curve'  => 'quadratic',
			'points' => array( array( 0.11, 0.80 ), array( 0.15, 0.42 ), array( 0.04, 0.12 ) ),
		),
		's-curve'        => array(
			'label'  => __( 'S-Curve', 'dzurriyyatul-academic' ),
			'curve'  => 'quadratic',
			'points' => array( array( -0.06, 0.75 ), array( 0.16, 0.50 ), array( -0.02, 0.25 ) ),
		),
		'liquid'         => array(
			'label'  => __( 'Liquid', 'dzurriyyatul-academic' ),
			'curve'  => 'quadratic',
			'points' => array( array( 0.07, 0.85 ), array( 0.14, 0.63 ), array( 0.03, 0.42 ), array( 0.11, 0.18 ) ),
		),
		'double-wave'    => array(
			'label'  => __( 'Double Wave', 'dzurriyyatul-academic' ),
			'curve'  => 'quadratic',
			'points' => array( array( 0.12, 0.82 ), array( 0.02, 0.62 ), array( 0.12, 0.38 ), array( 0.02, 0.16 ) ),
		),
		'sharp-abstract' => array(
			'label'  => __( 'Sharp Abstract', 'dzurriyyatul-academic' ),
			'curve'  => 'line',
			'points' => array( array( 0.16, 0.70 ), array( 0.03, 0.50 ), array( 0.16, 0.30 ) ),
		),
		'minimal-curve'  => array(
			'label'  => __( 'Minimal Curve', 'dzurriyyatul-academic' ),
			'curve'  => 'quadratic',
			// 2 close points (not 1) so the intensity multiplier — which
			// scales each point's distance from the shape's average X —
			// has something to act on; a single-point shape would always
			// average to itself and make "intensity" a no-op.
			'points' => array( array( 0.03, 0.65 ), array( 0.06, 0.35 ) ),
		),
	);
}

/**
 * Width presets: multiply every control point's X (how far the curve
 * reaches into the image) by this factor.
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
 * Intensity presets: pull each point's X toward (low) or push it away from
 * (high) the average of all points, exaggerating or flattening the
 * difference between the curve's peaks and troughs without changing its
 * overall reach.
 *
 * @return array<string,float>
 */
function dq_hero_frame_intensity_multipliers() {
	return array(
		'ringan' => 0.55,
		'sedang' => 1.0,
		'kuat'   => 1.6,
	);
}

/**
 * Position presets: shift the whole curve up or down within the frame by
 * this fraction of the frame's height, so the same shape can sit higher,
 * centered, or lower.
 *
 * @return array<string,float>
 */
function dq_hero_frame_position_offsets() {
	return array(
		'atas'   => -0.12,
		'tengah' => 0,
		'bawah'  => 0.12,
	);
}

/**
 * Build the final SVG path `d` attribute (objectBoundingBox space) for the
 * given shape + intensity + width + position combination. Shared by the
 * PHP-rendered markup and mirrored in JS (assets handled in
 * dq_hero_frame_enqueue_preview_script()) for reload-free Customizer preview.
 *
 * @param string $shape_key
 * @param string $intensity_key
 * @param string $width_key
 * @param string $position_key
 * @return string
 */
function dq_hero_frame_build_path( $shape_key, $intensity_key, $width_key, $position_key ) {
	$shapes = dq_hero_frame_shapes();
	if ( ! isset( $shapes[ $shape_key ] ) ) {
		$shape_key = 'soft-wave';
	}
	$shape = $shapes[ $shape_key ];

	$width_mult    = isset( dq_hero_frame_width_multipliers()[ $width_key ] ) ? dq_hero_frame_width_multipliers()[ $width_key ] : 1.0;
	$intensity_mult = isset( dq_hero_frame_intensity_multipliers()[ $intensity_key ] ) ? dq_hero_frame_intensity_multipliers()[ $intensity_key ] : 1.0;
	$position_offset = isset( dq_hero_frame_position_offsets()[ $position_key ] ) ? dq_hero_frame_position_offsets()[ $position_key ] : 0;

	$points = $shape['points'];
	$avg_x  = array_sum( array_column( $points, 0 ) ) / max( 1, count( $points ) );

	$adjusted = array();
	foreach ( $points as $point ) {
		list( $x, $y ) = $point;

		// Intensity: pull toward / push away from the average bulge depth.
		$x = $avg_x + ( $x - $avg_x ) * $intensity_mult;
		// Width: scale overall reach.
		$x = $x * $width_mult;
		// Position: shift vertically, clamped to stay inside the frame.
		$y = max( 0.02, min( 0.98, $y + $position_offset ) );

		$adjusted[] = array( round( $x, 4 ), round( $y, 4 ) );
	}

	// Path: start top-right -> straight down right edge -> straight along
	// bottom edge -> up the left edge via the (possibly organic) points,
	// in the order supplied (bottom-most point first) -> straight across
	// the top edge back to the start, closing the shape.
	$d  = 'M 1,0 L 1,1 L 0,1 ';
	$prev = array( 0, 1 );

	if ( 'line' === $shape['curve'] ) {
		foreach ( $adjusted as $point ) {
			$d   .= 'L ' . $point[0] . ',' . $point[1] . ' ';
			$prev = $point;
		}
	} else {
		foreach ( $adjusted as $point ) {
			// Quadratic curve using the midpoint between the previous
			// anchor and this control point as the curve's own control
			// handle, and this point as the new anchor — gives a smooth,
			// continuous organic line through every listed point.
			$ctrl_x = round( ( $prev[0] + $point[0] ) / 2, 4 );
			$ctrl_y = round( ( $prev[1] + $point[1] ) / 2, 4 );
			$d     .= 'Q ' . $ctrl_x . ',' . $ctrl_y . ' ' . $point[0] . ',' . $point[1] . ' ';
			$prev   = $point;
		}
	}

	$d .= 'L 0,0 Z';

	return $d;
}

/**
 * Extract one slide's frame settings from its already-fetched
 * dq_get_hero_slide() array (frame fields live alongside the slide's other
 * content, not in a separate global store).
 *
 * @param array $slide A slide array as returned by dq_get_hero_slide().
 * @return array{enabled:bool,shape:string,intensity:string,width:string,position:string}
 */
function dq_hero_frame_settings_for_slide( $slide ) {
	return array(
		'enabled'   => (bool) $slide['frame_enabled'],
		'shape'     => $slide['frame_shape'],
		'intensity' => $slide['frame_intensity'],
		'width'     => $slide['frame_width'],
		'position'  => $slide['frame_position'],
	);
}

/**
 * Render one shared <svg><defs> holding one <clipPath> PER hero slide index
 * (0-2), each with its own path built from that slide's independent frame
 * settings. Call from hero-carousel.php right before the slides markup.
 *
 * Every index gets a clipPath regardless of that slide's enabled state (even
 * disabled ones), so postMessage live preview in the Customizer can flip a
 * slide's frame on without needing to create SVG nodes on the fly — see
 * js/customizer-hero-frame-preview.js.
 *
 * @param array<int,array> $slides Slide arrays keyed by index, as built in
 *                                 hero-carousel.php.
 */
function dq_hero_frame_render_clip_paths( $slides ) {
	?>
	<svg width="0" height="0" aria-hidden="true" focusable="false" style="position:absolute;">
		<defs>
			<?php for ( $i = 0; $i < 3; $i++ ) : ?>
				<?php
				$slide    = isset( $slides[ $i ] ) ? $slides[ $i ] : dq_hero_slide_defaults();
				$settings = dq_hero_frame_settings_for_slide( $slide );
				$d        = dq_hero_frame_build_path( $settings['shape'], $settings['intensity'], $settings['width'], $settings['position'] );
				?>
				<clipPath id="dq-hero-frame-clip-<?php echo esc_attr( $i ); ?>" clipPathUnits="objectBoundingBox">
					<path d="<?php echo esc_attr( $d ); ?>" id="dq-hero-frame-clip-path-<?php echo esc_attr( $i ); ?>"></path>
				</clipPath>
			<?php endfor; ?>
		</defs>
	</svg>
	<?php
}

/**
 * Sanitize a value against a known key set, falling back to $default when
 * the submitted value isn't one of the allowed keys.
 *
 * @param string $value
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
 * @param mixed $value
 * @return string
 */
function dq_hero_frame_sanitize_position( $value ) {
	return dq_hero_frame_sanitize_key( $value, array_keys( dq_hero_frame_position_offsets() ), 'tengah' );
}

/**
 * The 5 frame controls (enable/shape/intensity/width/position) are
 * registered per-slide as part of the main Hero Slides field loop in
 * inc/customizer.php (each slide gets its own independent frame section),
 * not here — this file only supplies the shape math + sanitize callbacks
 * that loop uses, and the shared rendering/preview plumbing below.
 */

/**
 * Enqueue the visual shape-picker's grid/thumbnail CSS on the Customizer
 * controls screen only.
 */
function dq_hero_frame_enqueue_controls_assets() {
	wp_enqueue_style( 'dq-hero-frame-controls', DQ_THEME_URI . '/assets/css/hero-frame-controls.css', array(), DQ_THEME_VERSION );
}
add_action( 'customize_controls_enqueue_scripts', 'dq_hero_frame_enqueue_controls_assets' );

/**
 * Reload-free live preview: mirrors dq_hero_frame_build_path() in JS and
 * rewrites each slide's clip-path <path> element's "d" attribute directly
 * whenever any of that slide's frame settings changes in the Customizer
 * (and likewise for image position/zoom), instead of refreshing the whole
 * preview iframe. See js/customizer-hero-frame-preview.js.
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
		'window.dqHeroFrameShapes = ' . wp_json_encode( dq_hero_frame_shapes() ) . ';' .
		'window.dqHeroFrameWidthMultipliers = ' . wp_json_encode( dq_hero_frame_width_multipliers() ) . ';' .
		'window.dqHeroFrameIntensityMultipliers = ' . wp_json_encode( dq_hero_frame_intensity_multipliers() ) . ';' .
		'window.dqHeroFramePositionOffsets = ' . wp_json_encode( dq_hero_frame_position_offsets() ) . ';',
		'before'
	);
}
add_action( 'customize_preview_init', 'dq_hero_frame_enqueue_preview_script' );
