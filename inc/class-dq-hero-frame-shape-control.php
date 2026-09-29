<?php
/**
 * Customizer control: visual thumbnail picker for the Hero image frame
 * shape (8 options) — the request explicitly asks for thumbnails instead
 * of a plain dropdown. Each thumbnail is a tiny live SVG preview of that
 * shape's actual clip-path, generated with dq_hero_frame_build_path() at
 * its default intensity/width/position, so what the admin sees matches
 * what they'll get.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'DQ_Hero_Frame_Shape_Control' ) ) {

	class DQ_Hero_Frame_Shape_Control extends WP_Customize_Control {

		/**
		 * @var string
		 */
		public $type = 'dq_hero_frame_shape';

		/**
		 * Render a grid of clickable shape thumbnails; the underlying
		 * <input type="radio"> keeps this working exactly like a native
		 * control for Customizer's own change-detection/save handling.
		 */
		public function render_content() {
			$shapes  = dq_hero_frame_shapes();
			$current = $this->value();
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( $this->description ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<div class="dq-hero-frame-shape-grid">
				<?php foreach ( $shapes as $key => $shape ) : ?>
					<?php $path = dq_hero_frame_build_path( $key, 'sedang', 'sedang', 'tengah' ); ?>
					<label class="dq-hero-frame-shape-option<?php echo $key === $current ? ' is-selected' : ''; ?>">
						<input
							type="radio"
							value="<?php echo esc_attr( $key ); ?>"
							name="<?php echo esc_attr( $this->id ); ?>"
							<?php $this->link(); ?>
							<?php checked( $key, $current ); ?>
						/>
						<span class="dq-hero-frame-shape-thumb">
							<svg viewBox="0 0 1 1" preserveAspectRatio="none" aria-hidden="true">
								<path d="<?php echo esc_attr( $path ); ?>"></path>
							</svg>
						</span>
						<span class="dq-hero-frame-shape-label"><?php echo esc_html( $shape['label'] ); ?></span>
					</label>
				<?php endforeach; ?>
			</div>
			<?php
		}
	}
}
