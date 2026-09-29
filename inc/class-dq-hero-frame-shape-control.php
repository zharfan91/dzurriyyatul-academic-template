<?php
/**
 * Customizer control: visual thumbnail picker for a Hero slide's frame
 * shape (8 options). Each thumbnail is drawn from the real clip path
 * (dq_hero_frame_build_path() at default intensity/width), so what the
 * admin picks is what renders.
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
		 * Radio inputs keep Customizer's own change detection working; the
		 * :checked state drives the selected styling in
		 * assets/css/hero-frame-controls.css.
		 */
		public function render_content() {
			$current = $this->value();
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( $this->description ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<div class="dq-hero-frame-shape-grid" role="radiogroup" aria-label="<?php echo esc_attr( $this->label ); ?>">
				<?php foreach ( dq_hero_frame_shapes() as $key => $shape ) : ?>
					<label class="dq-hero-frame-shape-option">
						<input
							type="radio"
							value="<?php echo esc_attr( $key ); ?>"
							name="<?php echo esc_attr( '_customize-radio-' . $this->id ); ?>"
							<?php $this->link(); ?>
							<?php checked( $key, $current ); ?>
						/>
						<span class="dq-hero-frame-shape-thumb">
							<svg viewBox="0 0 1 1" preserveAspectRatio="none" aria-hidden="true" focusable="false">
								<path d="<?php echo esc_attr( dq_hero_frame_build_path( $key, 'sedang', 'sedang' ) ); ?>"></path>
							</svg>
							<span class="dq-hero-frame-shape-check" aria-hidden="true">
								<svg viewBox="0 0 16 16" focusable="false"><path d="M3.5 8.5l3 3 6-6.5"></path></svg>
							</span>
						</span>
						<span class="dq-hero-frame-shape-label"><?php echo esc_html( $shape['label'] ); ?></span>
					</label>
				<?php endforeach; ?>
			</div>
			<?php
		}
	}
}
