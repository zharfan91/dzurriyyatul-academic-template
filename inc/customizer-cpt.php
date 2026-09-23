<?php
/**
 * Customizer-native content management for the 5 unbounded CPT collections
 * (Layanan, Paket, Mentor, Testimoni, FAQ) — a "Kelola Konten" panel where each section
 * is a JS-driven repeater (list + add + edit + delete + reorder) talking
 * directly to the REST API, with its own instant save (not tied to
 * Customizer's Publish button) and its own previewer.refresh() call.
 *
 * See inc/class-dq-cpt-repeater-control.php for why this can't be built with
 * stock WP_Customize_Control types, and js/customizer-cpt-manager.js for the
 * actual list/CRUD UI.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The 5 CPTs this panel manages, with their section label and whether they
 * support a featured image (service, testimonial + mentor do; package + faq
 * don't) and whether they support the native "editor" (post_content) field —
 * every type except mentor displays it front-end (service's excerpt
 * fallback, package's description, faq's actual answer text, testimonial's
 * quote itself), so the repeater's item editor needs a content textarea for
 * those four. Matches each register_post_type() 'supports' array in
 * inc/post-types.php.
 *
 * @return array<string,array{label:string,has_thumbnail:bool,has_content:bool,content_label:string}>
 */
function dq_cpt_manager_types() {
	return array(
		'service'     => array(
			'label'         => __( 'Layanan', 'dzurriyyatul-academic' ),
			'has_thumbnail' => false,
			'has_content'   => true,
			'content_label' => __( 'Deskripsi Lengkap (opsional — dipakai jika Deskripsi Singkat di atas kosong)', 'dzurriyyatul-academic' ),
		),
		'package'     => array(
			'label'         => __( 'Paket', 'dzurriyyatul-academic' ),
			'has_thumbnail' => false,
			'has_content'   => true,
			'content_label' => __( 'Deskripsi Paket', 'dzurriyyatul-academic' ),
		),
		'mentor'      => array(
			'label'         => __( 'Mentor', 'dzurriyyatul-academic' ),
			'has_thumbnail' => true,
			'has_content'   => false,
			'content_label' => '',
		),
		'testimonial' => array(
			'label'         => __( 'Testimoni', 'dzurriyyatul-academic' ),
			'has_thumbnail' => true,
			'has_content'   => true,
			'content_label' => __( 'Kutipan Testimoni', 'dzurriyyatul-academic' ),
		),
		'faq'         => array(
			'label'         => __( 'FAQ', 'dzurriyyatul-academic' ),
			'has_thumbnail' => false,
			'has_content'   => true,
			'content_label' => __( 'Jawaban', 'dzurriyyatul-academic' ),
		),
	);
}

/**
 * Register the "Kelola Konten" panel: one section + one repeater control
 * per CPT. Each control needs a WP_Customize_Setting per the Customizer
 * API's own requirement, even though nothing ever reads or writes it — the
 * repeater saves directly via REST, not through Customizer's setting/save
 * pipeline.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function dq_customize_register_cpt_manager( $wp_customize ) {
	require_once DQ_THEME_DIR . '/inc/class-dq-cpt-repeater-control.php';

	if ( ! class_exists( 'DQ_Customize_CPT_Repeater_Control' ) ) {
		return;
	}

	$wp_customize->add_panel( 'dq_panel_cpt_manager', array(
		'title'       => __( 'Kelola Konten', 'dzurriyyatul-academic' ),
		'description' => __( 'Tambah, edit, atau hapus Layanan, Paket, Mentor, dan FAQ langsung di sini, dengan preview langsung di sebelah kanan.', 'dzurriyyatul-academic' ),
		'priority'    => 20,
	) );

	foreach ( dq_cpt_manager_types() as $post_type => $config ) {
		$section_id = "dq_section_cpt_{$post_type}";
		$setting_id = "dq_cpt_repeater_{$post_type}_noop";
		$control_id = "dq_cpt_repeater_{$post_type}";

		$wp_customize->add_section( $section_id, array(
			'title' => $config['label'],
			'panel' => 'dq_panel_cpt_manager',
		) );

		// Placeholder setting — required by the Customizer API, never
		// actually read or written; the repeater control below saves
		// directly via REST instead.
		$wp_customize->add_setting( $setting_id, array(
			'type'              => 'theme_mod',
			'default'           => '',
			'sanitize_callback' => '__return_empty_string',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( new DQ_Customize_CPT_Repeater_Control(
			$wp_customize,
			$control_id,
			array(
				'section'   => $section_id,
				'settings'  => $setting_id,
				'post_type' => $post_type,
				'label'     => '',
			)
		) );
	}
}
add_action( 'customize_register', 'dq_customize_register_cpt_manager' );

/**
 * Load the repeater app only on the Customizer controls screen, and only
 * once (shared by all 4 sections — each mounts itself into its own
 * [data-post-type] div client-side).
 */
function dq_customize_cpt_manager_enqueue_assets() {
	wp_enqueue_media();

	wp_enqueue_style( 'dq-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1' );
	wp_enqueue_style( 'dq-customizer-cpt-manager', DQ_THEME_URI . '/assets/css/customizer-cpt-manager.css', array(), DQ_THEME_VERSION );

	wp_enqueue_script(
		'dq-customizer-cpt-manager',
		DQ_THEME_URI . '/js/customizer-cpt-manager.js',
		array( 'wp-element', 'wp-components', 'wp-i18n', 'wp-api-fetch', 'wp-url', 'customize-controls', 'media-editor' ),
		DQ_THEME_VERSION,
		true
	);

	$types = dq_cpt_manager_types();
	$schema = dq_cpt_meta_schema();

	$config = array();
	foreach ( $types as $post_type => $meta ) {
		$config[ $post_type ] = array(
			'label'        => $meta['label'],
			'hasThumbnail' => $meta['has_thumbnail'],
			'hasContent'   => $meta['has_content'],
			'contentLabel' => $meta['content_label'],
			'fields'       => isset( $schema[ $post_type ] ) ? $schema[ $post_type ] : array(),
		);
	}

	wp_add_inline_script(
		'dq-customizer-cpt-manager',
		'window.dqCptManager = ' . wp_json_encode( array(
			'types' => $config,
			'icons' => dq_icon_picker_options(),
		) ) . ';',
		'before'
	);
}
add_action( 'customize_controls_enqueue_scripts', 'dq_customize_cpt_manager_enqueue_assets' );
