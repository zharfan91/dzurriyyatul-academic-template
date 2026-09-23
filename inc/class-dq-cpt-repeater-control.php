<?php
/**
 * Custom Customizer control: an unbounded-collection repeater for a CPT
 * (Layanan, Paket, Mentor, FAQ).
 *
 * WP_Customize_Control has no native concept of "an arbitrary, addable/
 * removable list of items" — every stock control type binds to exactly one
 * WP_Customize_Setting value. This control deliberately bypasses that model:
 * it only renders an empty mount point (see render_content()); everything
 * else — listing, add, edit, delete, reorder — is done in
 * js/customizer-cpt-manager.js talking directly to the REST API
 * (wp.apiFetch, already nonce-authenticated by WordPress core once
 * `wp-api-fetch` is enqueued). Each save calls
 * wp.customize.previewer.refresh() itself, independent of the Customizer's
 * own Save/Publish queue.
 *
 * A real WP_Customize_Setting is still registered per instance (see
 * dq_customize_register_cpt_manager() in inc/customizer-cpt.php) purely
 * because the Customizer API requires every control to have one — it's a
 * theme_mod placeholder that's never read.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'DQ_Customize_CPT_Repeater_Control' ) ) {

	class DQ_Customize_CPT_Repeater_Control extends WP_Customize_Control {

		/**
		 * @var string
		 */
		public $type = 'dq_cpt_repeater';

		/**
		 * Post type this instance manages, e.g. 'service'.
		 *
		 * @var string
		 */
		public $post_type = '';

		/**
		 * Render just the JS mount point — no PHP-rendered form fields.
		 */
		public function render_content() {
			printf(
				'<div class="dq-cpt-repeater" data-post-type="%s"><p class="dq-cpt-repeater__loading">%s</p></div>',
				esc_attr( $this->post_type ),
				esc_html__( 'Memuat…', 'dzurriyyatul-academic' )
			);
		}
	}
}
