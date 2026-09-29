<?php
/**
 * Setting-less Customizer control that renders a group heading (e.g.
 * "Konten", "Gambar", "Bingkai Abstrak") inside a section.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'DQ_Customize_Heading_Control' ) ) {

	class DQ_Customize_Heading_Control extends WP_Customize_Control {

		/**
		 * @var string
		 */
		public $type = 'dq_heading';

		public function render_content() {
			?>
			<h3 class="dq-customize-heading"><?php echo esc_html( $this->label ); ?></h3>
			<?php if ( $this->description ) : ?>
				<p class="description customize-control-description"><?php echo esc_html( $this->description ); ?></p>
			<?php endif; ?>
			<?php
		}
	}
}
