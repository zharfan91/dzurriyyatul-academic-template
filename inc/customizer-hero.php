<?php
/**
 * Customizer: Hero Slides panel.
 *
 *   Hero Slides
 *   ├── Pengaturan Carousel
 *   ├── Slide 1
 *   ├── Slide 2
 *   └── Slide 3
 *
 * Everything is stored in the dq_theme_settings option (same keys as
 * dq_hero_slide_defaults() / dq_hero_carousel_settings()). Every setting uses
 * postMessage: frame and image position/zoom are patched instantly by
 * js/customizer-hero-frame-preview.js; everything else re-renders the hero
 * through the 'dq_hero' selective-refresh partial (no full page reload).
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param mixed $value
 * @return int
 */
function dq_customize_sanitize_autoplay_duration( $value ) {
	return max( 3000, min( 10000, absint( $value ) ) );
}

/**
 * @param mixed $value
 * @return int
 */
function dq_customize_sanitize_animation_duration( $value ) {
	return max( 200, min( 1500, absint( $value ) ) );
}

/**
 * Whitelist sanitizer for a select setting.
 *
 * @param array  $choices
 * @param string $default
 * @return Closure
 */
function dq_customize_select_sanitizer( $choices, $default ) {
	return function ( $value ) use ( $choices, $default ) {
		return array_key_exists( (string) $value, $choices ) ? (string) $value : $default;
	};
}

/**
 * Selective-refresh render callback for the whole hero section.
 */
function dq_render_hero_partial() {
	get_template_part( 'template-parts/hero/hero-carousel' );
}

/**
 * Per-slide field definitions, grouped for the Customizer.
 *
 * 'live' => true marks fields patched instantly by the preview JS instead of
 * re-rendering the hero partial.
 *
 * @return array<string,array<string,array>>
 */
function dq_customize_hero_slide_fields() {
	return array(
		__( 'Konten', 'dzurriyyatul-academic' )          => array(
			'enabled'             => array( 'type' => 'checkbox', 'label' => __( 'Tampilkan slide ini', 'dzurriyyatul-academic' ), 'default' => true ),
			'badge_emoji'         => array( 'type' => 'text', 'label' => __( 'Emoji Eyebrow (opsional)', 'dzurriyyatul-academic' ) ),
			'badge_text'          => array( 'type' => 'text', 'label' => __( 'Eyebrow', 'dzurriyyatul-academic' ) ),
			'heading_line1'       => array( 'type' => 'text', 'label' => __( 'Judul Baris 1', 'dzurriyyatul-academic' ) ),
			'heading_line2'       => array( 'type' => 'text', 'label' => __( 'Judul Baris 2 (aksen miring)', 'dzurriyyatul-academic' ) ),
			'tagline_quote'       => array( 'type' => 'text', 'label' => __( 'Tagline', 'dzurriyyatul-academic' ) ),
			'description'         => array( 'type' => 'textarea', 'label' => __( 'Deskripsi', 'dzurriyyatul-academic' ) ),
			'primary_cta_label'   => array( 'type' => 'text', 'label' => __( 'Teks CTA Utama', 'dzurriyyatul-academic' ) ),
			'primary_cta_url'     => array(
				'type'        => 'url',
				'label'       => __( 'URL CTA Utama', 'dzurriyyatul-academic' ),
				'description' => __( 'Kosongkan untuk membuka WhatsApp dengan pesan di bawah.', 'dzurriyyatul-academic' ),
			),
			'primary_cta_message' => array( 'type' => 'textarea', 'label' => __( 'Pesan WhatsApp CTA Utama', 'dzurriyyatul-academic' ) ),
			'secondary_cta_label' => array( 'type' => 'text', 'label' => __( 'Teks CTA Sekunder', 'dzurriyyatul-academic' ) ),
			'secondary_cta_url'   => array( 'type' => 'url', 'label' => __( 'URL CTA Sekunder', 'dzurriyyatul-academic' ) ),
		),
		__( 'Gambar', 'dzurriyyatul-academic' )          => array(
			'image_id'       => array( 'type' => 'image', 'label' => __( 'Gambar', 'dzurriyyatul-academic' ) ),
			'image_alt'      => array( 'type' => 'text', 'label' => __( 'Teks Alternatif (Alt)', 'dzurriyyatul-academic' ) ),
			'image_position' => array(
				'type'    => 'select',
				'label'   => __( 'Posisi Gambar (Fokus)', 'dzurriyyatul-academic' ),
				'default' => 'center',
				'live'    => true,
				'choices' => array(
					'top left'     => __( 'Kiri Atas', 'dzurriyyatul-academic' ),
					'top'          => __( 'Atas', 'dzurriyyatul-academic' ),
					'top right'    => __( 'Kanan Atas', 'dzurriyyatul-academic' ),
					'left'         => __( 'Kiri', 'dzurriyyatul-academic' ),
					'center'       => __( 'Tengah', 'dzurriyyatul-academic' ),
					'right'        => __( 'Kanan', 'dzurriyyatul-academic' ),
					'bottom left'  => __( 'Kiri Bawah', 'dzurriyyatul-academic' ),
					'bottom'       => __( 'Bawah', 'dzurriyyatul-academic' ),
					'bottom right' => __( 'Kanan Bawah', 'dzurriyyatul-academic' ),
				),
			),
			'image_zoom'     => array(
				'type'    => 'select',
				'label'   => __( 'Zoom Gambar', 'dzurriyyatul-academic' ),
				'default' => '100',
				'live'    => true,
				'choices' => array(
					'100' => '100%',
					'110' => '110%',
					'120' => '120%',
					'130' => '130%',
					'140' => '140%',
					'150' => '150%',
				),
			),
		),
		__( 'Bingkai Abstrak', 'dzurriyyatul-academic' ) => array(
			'frame_enabled'   => array( 'type' => 'checkbox', 'label' => __( 'Aktifkan bingkai abstrak', 'dzurriyyatul-academic' ), 'default' => true, 'live' => true ),
			'frame_shape'     => array(
				'type'        => 'frame_shape',
				'label'       => __( 'Gaya Bingkai', 'dzurriyyatul-academic' ),
				'description' => __( 'Hanya sisi kiri gambar yang abstrak — atas, kanan, dan bawah tetap lurus.', 'dzurriyyatul-academic' ),
				'default'     => 'soft-wave',
				'live'        => true,
			),
			'frame_intensity' => array(
				'type'     => 'select',
				'label'    => __( 'Intensitas Lengkungan', 'dzurriyyatul-academic' ),
				'default'  => 'sedang',
				'live'     => true,
				'sanitize' => 'dq_hero_frame_sanitize_intensity',
				'choices'  => array(
					'ringan' => __( 'Ringan', 'dzurriyyatul-academic' ),
					'sedang' => __( 'Sedang', 'dzurriyyatul-academic' ),
					'kuat'   => __( 'Kuat', 'dzurriyyatul-academic' ),
				),
			),
			'frame_width'     => array(
				'type'     => 'select',
				'label'    => __( 'Lebar Bingkai', 'dzurriyyatul-academic' ),
				'default'  => 'sedang',
				'live'     => true,
				'sanitize' => 'dq_hero_frame_sanitize_width',
				'choices'  => array(
					'sempit' => __( 'Sempit', 'dzurriyyatul-academic' ),
					'sedang' => __( 'Sedang', 'dzurriyyatul-academic' ),
					'lebar'  => __( 'Lebar', 'dzurriyyatul-academic' ),
				),
			),
		),
	);
}

/**
 * @param WP_Customize_Manager $wp_customize
 */
function dq_customize_register_hero( $wp_customize ) {
	require_once DQ_THEME_DIR . '/inc/class-dq-hero-frame-shape-control.php';
	require_once DQ_THEME_DIR . '/inc/class-dq-customize-heading-control.php';

	$partial_settings = array();

	$wp_customize->add_panel( 'dq_panel_hero', array(
		'title'       => __( 'Hero Slides', 'dzurriyyatul-academic' ),
		'description' => __( 'Kelola carousel 3 slide di halaman depan.', 'dzurriyyatul-academic' ),
		'priority'    => 25,
	) );

	/*
	 * Pengaturan Carousel
	 */
	$wp_customize->add_section( 'dq_section_hero_carousel_settings', array(
		'title'    => __( 'Pengaturan Carousel', 'dzurriyyatul-academic' ),
		'panel'    => 'dq_panel_hero',
		'priority' => 1,
	) );

	$animation_choices = array(
		'fade-slide' => __( 'Bertahap: teks naik satu per satu, gambar bergeser & zoom halus', 'dzurriyyatul-academic' ),
		'fade'       => __( 'Fade bertahap (tanpa gerakan)', 'dzurriyyatul-academic' ),
		'none'       => __( 'Tanpa animasi', 'dzurriyyatul-academic' ),
	);

	$carousel_fields = array(
		'hero_carousel_enabled'   => array( 'type' => 'checkbox', 'label' => __( 'Aktifkan Carousel', 'dzurriyyatul-academic' ), 'description' => __( 'Jika dimatikan, hanya slide pertama yang tampil.', 'dzurriyyatul-academic' ), 'default' => true ),
		'hero_autoplay_enabled'   => array( 'type' => 'checkbox', 'label' => __( 'Putar Otomatis (Autoplay)', 'dzurriyyatul-academic' ), 'default' => true ),
		'hero_autoplay_duration'  => array( 'type' => 'range', 'label' => __( 'Durasi Autoplay (ms)', 'dzurriyyatul-academic' ), 'default' => 6000, 'sanitize' => 'dq_customize_sanitize_autoplay_duration', 'attrs' => array( 'min' => 3000, 'max' => 10000, 'step' => 500 ) ),
		'hero_animation_type'     => array( 'type' => 'select', 'label' => __( 'Jenis Animasi', 'dzurriyyatul-academic' ), 'default' => 'fade-slide', 'choices' => $animation_choices ),
		'hero_animation_duration' => array( 'type' => 'range', 'label' => __( 'Durasi Animasi (ms)', 'dzurriyyatul-academic' ), 'default' => 600, 'sanitize' => 'dq_customize_sanitize_animation_duration', 'attrs' => array( 'min' => 200, 'max' => 1500, 'step' => 50 ) ),
		'hero_show_navigation'    => array( 'type' => 'checkbox', 'label' => __( 'Tampilkan Tombol Sebelumnya/Berikutnya', 'dzurriyyatul-academic' ), 'default' => true ),
		'hero_show_indicator'     => array( 'type' => 'checkbox', 'label' => __( 'Tampilkan Indikator Slide (01 / 03)', 'dzurriyyatul-academic' ), 'default' => true ),
	);

	foreach ( $carousel_fields as $key => $field ) {
		$id = "dq_theme_settings[{$key}]";
		dq_customize_add_hero_field( $wp_customize, $id, $field, 'dq_section_hero_carousel_settings', "dq_{$key}" );
		$partial_settings[] = $id;
	}

	/*
	 * Slide 1 / 2 / 3
	 */
	for ( $i = 0; $i < 3; $i++ ) {
		$section = "dq_section_hero_{$i}";

		$wp_customize->add_section( $section, array(
			/* translators: %d: slide number (1-3). */
			'title'    => sprintf( __( 'Slide %d', 'dzurriyyatul-academic' ), $i + 1 ),
			'panel'    => 'dq_panel_hero',
			'priority' => 10 + $i,
		) );

		foreach ( dq_customize_hero_slide_fields() as $group => $fields ) {
			$wp_customize->add_control( new DQ_Customize_Heading_Control( $wp_customize, 'dq_hero_' . $i . '_heading_' . sanitize_key( $group ), array(
				'label'    => $group,
				'section'  => $section,
				'settings' => array(),
			) ) );

			foreach ( $fields as $key => $field ) {
				$id = "dq_theme_settings[hero_slides][{$i}][{$key}]";
				dq_customize_add_hero_field( $wp_customize, $id, $field, $section, "dq_hero_{$i}_{$key}" );

				if ( empty( $field['live'] ) ) {
					$partial_settings[] = $id;
				}
			}
		}
	}

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'dq_hero', array(
			'selector'            => '#beranda',
			'settings'            => $partial_settings,
			'container_inclusive' => true,
			'render_callback'     => 'dq_render_hero_partial',
			'fallback_refresh'    => true,
		) );
	}
}
add_action( 'customize_register', 'dq_customize_register_hero' );

/**
 * Register one setting + control from a field definition.
 *
 * @param WP_Customize_Manager $wp_customize
 * @param string               $id         Setting ID.
 * @param array                $field      Field definition.
 * @param string               $section    Section ID.
 * @param string               $control_id Control ID (distinct from the bracketed setting ID).
 */
function dq_customize_add_hero_field( $wp_customize, $id, $field, $section, $control_id ) {
	$type = $field['type'];

	$defaults = array(
		'checkbox'    => false,
		'image'       => 0,
		'range'       => 0,
		'select'      => '',
		'frame_shape' => 'soft-wave',
	);
	$default = isset( $field['default'] ) ? $field['default'] : ( isset( $defaults[ $type ] ) ? $defaults[ $type ] : '' );

	if ( isset( $field['sanitize'] ) ) {
		$sanitize = $field['sanitize'];
	} elseif ( 'select' === $type ) {
		$sanitize = dq_customize_select_sanitizer( $field['choices'], $default );
	} else {
		$by_type  = array(
			'checkbox'    => 'rest_sanitize_boolean',
			'image'       => 'absint',
			'range'       => 'absint',
			'frame_shape' => 'dq_hero_frame_sanitize_shape',
			'textarea'    => 'dq_customize_sanitize_textarea',
			'url'         => 'dq_customize_sanitize_url',
		);
		$sanitize = isset( $by_type[ $type ] ) ? $by_type[ $type ] : 'dq_customize_sanitize_text';
	}

	$wp_customize->add_setting( $id, array(
		'type'              => 'option',
		'default'           => $default,
		'sanitize_callback' => $sanitize,
		'transport'         => 'postMessage',
	) );

	$args = array(
		'label'    => $field['label'],
		'section'  => $section,
		'settings' => $id,
	);
	if ( isset( $field['description'] ) ) {
		$args['description'] = $field['description'];
	}

	if ( 'image' === $type ) {
		// Media control (not Image control): it saves the attachment ID,
		// which is what absint() and wp_get_attachment_image() expect — the
		// Image control saves the file URL instead.
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $control_id, $args + array( 'mime_type' => 'image' ) ) );
		return;
	}

	if ( 'frame_shape' === $type ) {
		$wp_customize->add_control( new DQ_Hero_Frame_Shape_Control( $wp_customize, $control_id, $args ) );
		return;
	}

	$args['type'] = $type;
	if ( 'select' === $type ) {
		$args['choices'] = $field['choices'];
	}
	if ( isset( $field['attrs'] ) ) {
		$args['input_attrs'] = $field['attrs'];
	}

	$wp_customize->add_control( $control_id, $args );
}
