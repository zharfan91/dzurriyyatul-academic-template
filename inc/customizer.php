<?php
/**
 * Theme Settings — native Customizer integration.
 *
 * Replaces the old Settings API admin page (Pengaturan Akademik, formerly
 * registered in inc/settings.php) with organized Customizer panels/sections,
 * so the site owner gets a navigable left-hand menu ("tabs") AND WordPress's
 * built-in live preview pane on the right — no custom preview JS needed.
 *
 * Storage is unchanged: every control below still writes into the SAME
 * consolidated 'dq_theme_settings' option, at the SAME array key, via
 * bracket-array setting IDs (e.g. 'dq_theme_settings[whatsapp_number]') combined
 * with 'type' => 'option'. This is what lets dq_get_setting( $key, $default )
 * in inc/helpers.php — used throughout every template-parts/*.php file — keep
 * working completely unmodified.
 *
 * Every setting uses 'transport' => 'refresh' (the Customizer default): the
 * preview iframe reloads after each change. No postMessage/selective-refresh
 * JS is used here, by design (out of scope for ~119 fields).
 *
 * Sanitization reuses dq_sanitize_by_type( $value, $type ), which still lives
 * in inc/settings.php alongside dq_settings_schema(), dq_hero_slide_schema(),
 * dq_register_setting() and dq_sanitize_theme_settings() (all kept there as
 * the safety-net sanitizer for register_setting() / direct option updates).
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared sanitize-callback wrappers for add_setting( 'sanitize_callback' => ... ).
 *
 * WP_Customize_Manager::add_setting() requires a plain callable of signature
 * ( $value ), so these thin wrappers adapt the theme's existing
 * dq_sanitize_by_type( $value, $type ) helper (defined once in
 * inc/settings.php) to that shape. Defined once here; every section below
 * references them by name.
 */

/**
 * @param mixed $value Raw value.
 * @return string
 */
function dq_customize_sanitize_text( $value ) {
	return dq_sanitize_by_type( $value, 'text' );
}

/**
 * @param mixed $value Raw value.
 * @return string
 */
function dq_customize_sanitize_textarea( $value ) {
	return dq_sanitize_by_type( $value, 'textarea' );
}

/**
 * @param mixed $value Raw value.
 * @return string
 */
function dq_customize_sanitize_email( $value ) {
	return dq_sanitize_by_type( $value, 'email' );
}

/**
 * @param mixed $value Raw value.
 * @return string
 */
function dq_customize_sanitize_url( $value ) {
	return dq_sanitize_by_type( $value, 'url' );
}

/**
 * Register every dq_theme_settings field as a native Customizer
 * panel/section/setting/control.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function dq_customize_register( $wp_customize ) {

	/**
	 * =========================================================================
	 * Panels (declared once each — every field-section below nests under one
	 * of these two).
	 * =========================================================================
	 */
	$wp_customize->add_panel( 'dq_panel_site_settings', array(
		'title'       => __( 'Pengaturan Situs Akademik', 'dzurriyyatul-academic' ),
		'description' => __( 'Kelola nomor WhatsApp, kontak, media sosial, legalitas, SEO, dan integritas akademik. Logo, favicon, dan judul situs dikelola melalui Identitas Situs.', 'dzurriyyatul-academic' ),
		'priority'    => 30,
	) );

	$wp_customize->add_panel( 'dq_panel_hero', array(
		'title'       => __( 'Hero Slides', 'dzurriyyatul-academic' ),
		'description' => __( 'Kelola konten 3 slide hero di halaman depan.', 'dzurriyyatul-academic' ),
		'priority'    => 25,
	) );

	/**
	 * =========================================================================
	 * Section: Kontak & WhatsApp (dq_section_contact)
	 * =========================================================================
	 */
	$wp_customize->add_section( 'dq_section_contact', array(
		'title'    => __( 'Kontak & WhatsApp', 'dzurriyyatul-academic' ),
		'panel'    => 'dq_panel_site_settings',
		'priority' => 10,
	) );

	$wp_customize->add_setting( 'dq_theme_settings[whatsapp_number]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[whatsapp_number]', array(
		'label'   => __( 'Nomor WhatsApp (contoh: 6281234567890)', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_contact',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[whatsapp_default_message]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_textarea',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[whatsapp_default_message]', array(
		'label'   => __( 'Pesan Default WhatsApp', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_contact',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[contact_email]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_email',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[contact_email]', array(
		'label'   => __( 'Email', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_contact',
		'type'    => 'email',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[contact_phone]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[contact_phone]', array(
		'label'   => __( 'Telepon', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_contact',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[contact_address]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_textarea',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[contact_address]', array(
		'label'   => __( 'Alamat', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_contact',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[operating_hours]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[operating_hours]', array(
		'label'   => __( 'Jam Operasional', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_contact',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[tagline_override]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[tagline_override]', array(
		'label'   => __( 'Tagline (opsional, override tagline situs)', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_contact',
		'type'    => 'text',
	) );

	/**
	 * =========================================================================
	 * Section: Media Sosial & Peta (dq_section_social)
	 * =========================================================================
	 */
	$wp_customize->add_section( 'dq_section_social', array(
		'title'    => __( 'Media Sosial & Peta', 'dzurriyyatul-academic' ),
		'panel'    => 'dq_panel_site_settings',
		'priority' => 20,
	) );

	$wp_customize->add_setting( 'dq_theme_settings[social_instagram]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_url',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[social_instagram]', array(
		'label'   => __( 'Instagram URL', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_social',
		'type'    => 'url',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[social_facebook]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_url',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[social_facebook]', array(
		'label'   => __( 'Facebook URL', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_social',
		'type'    => 'url',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[social_tiktok]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_url',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[social_tiktok]', array(
		'label'   => __( 'TikTok URL', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_social',
		'type'    => 'url',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[social_youtube]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_url',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[social_youtube]', array(
		'label'   => __( 'YouTube URL', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_social',
		'type'    => 'url',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[google_maps_url]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_url',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[google_maps_url]', array(
		'label'   => __( 'Google Maps Embed/Link URL', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_social',
		'type'    => 'url',
	) );

	/**
	 * =========================================================================
	 * Section: Legalitas (dq_section_legal)
	 * =========================================================================
	 */
	$wp_customize->add_section( 'dq_section_legal', array(
		'title'    => __( 'Legalitas', 'dzurriyyatul-academic' ),
		'panel'    => 'dq_panel_site_settings',
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'dq_theme_settings[legal_entity_name]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[legal_entity_name]', array(
		'label'   => __( 'Nama Badan Hukum/Yayasan', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_legal',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[legal_ahu_number]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[legal_ahu_number]', array(
		'label'   => __( 'Nomor AHU Kemenkumham', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_legal',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[legal_sk_date]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[legal_sk_date]', array(
		'label'   => __( 'Tanggal SK', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_legal',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[legal_note]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_textarea',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[legal_note]', array(
		'label'   => __( 'Catatan Legal Tambahan', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_legal',
		'type'    => 'textarea',
	) );

	/**
	 * =========================================================================
	 * Section: SEO (dq_section_seo)
	 * =========================================================================
	 */
	$wp_customize->add_section( 'dq_section_seo', array(
		'title'    => __( 'SEO', 'dzurriyyatul-academic' ),
		'panel'    => 'dq_panel_site_settings',
		'priority' => 40,
	) );

	$wp_customize->add_setting( 'dq_theme_settings[seo_default_description]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_textarea',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[seo_default_description]', array(
		'label'   => __( 'Meta Deskripsi Default', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_seo',
		'type'    => 'textarea',
	) );

	/**
	 * =========================================================================
	 * Section: Integritas & Etika (dq_section_integrity)
	 * =========================================================================
	 */
	$wp_customize->add_section( 'dq_section_integrity', array(
		'title'    => __( 'Integritas & Etika', 'dzurriyyatul-academic' ),
		'panel'    => 'dq_panel_site_settings',
		'priority' => 50,
	) );

	$wp_customize->add_setting( 'dq_theme_settings[integrity_eyebrow_text]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[integrity_eyebrow_text]', array(
		'label'   => __( 'Label Badge (Eyebrow)', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_integrity',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[integrity_heading]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[integrity_heading]', array(
		'label'   => __( 'Judul Section', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_integrity',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[integrity_notice_text]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_textarea',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[integrity_notice_text]', array(
		'label'   => __( 'Teks Peringatan "PENTING"', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_integrity',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[integrity_body]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_textarea',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[integrity_body]', array(
		'label'   => __( 'Paragraf Deskripsi', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_integrity',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[integrity_dont_1]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[integrity_dont_1]', array(
		'label'   => __( 'Poin "TIDAK" 1', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_integrity',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[integrity_dont_2]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[integrity_dont_2]', array(
		'label'   => __( 'Poin "TIDAK" 2', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_integrity',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[integrity_do_1]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[integrity_do_1]', array(
		'label'   => __( 'Poin "YA" 1', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_integrity',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[integrity_do_2]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[integrity_do_2]', array(
		'label'   => __( 'Poin "YA" 2', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_integrity',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[integrity_pledge_heading]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[integrity_pledge_heading]', array(
		'label'   => __( 'Judul Kartu Pakta', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_integrity',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[integrity_pledge_quote]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_textarea',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[integrity_pledge_quote]', array(
		'label'   => __( 'Kutipan Kartu Pakta', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_integrity',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'dq_theme_settings[integrity_pledge_badge]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_text',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[integrity_pledge_badge]', array(
		'label'   => __( 'Teks Badge Pakta', 'dzurriyyatul-academic' ),
		'section' => 'dq_section_integrity',
		'type'    => 'text',
	) );

	/**
	 * =========================================================================
	 * Panel: Hero Slides — 3 sections (dq_section_hero_0..2), generated from a
	 * single 26-field definition list looped over slide indices 0-2. Mirrors
	 * dq_hero_slide_schema() / dq_hero_slide_defaults() field-for-field.
	 * =========================================================================
	 */

	// Field definitions: key => [ type, label ]. Labels copied verbatim from
	// the old dq_render_settings_page() "Hero Slides (3 Slide)" section;
	// types copied verbatim from dq_hero_slide_schema().
	$dq_hero_fields = array(
		'tab_label'           => array( 'type' => 'text',     'label' => __( 'Label Tab (mis. Bimbingan Skripsi & Tesis)', 'dzurriyyatul-academic' ) ),
		'badge_emoji'         => array( 'type' => 'text',     'label' => __( 'Emoji Badge', 'dzurriyyatul-academic' ) ),
		'badge_text'          => array( 'type' => 'text',     'label' => __( 'Teks Badge', 'dzurriyyatul-academic' ) ),
		'heading_line1'       => array( 'type' => 'text',     'label' => __( 'Judul Baris 1', 'dzurriyyatul-academic' ) ),
		'heading_line2'       => array( 'type' => 'text',     'label' => __( 'Judul Baris 2 (ditampilkan miring & warna primer)', 'dzurriyyatul-academic' ) ),
		'tagline_quote'       => array( 'type' => 'text',     'label' => __( 'Tagline Kutipan', 'dzurriyyatul-academic' ) ),
		'tags'                => array( 'type' => 'text',     'label' => __( 'Tag Topik (pisahkan dengan koma)', 'dzurriyyatul-academic' ) ),
		'description'         => array( 'type' => 'textarea', 'label' => __( 'Deskripsi', 'dzurriyyatul-academic' ) ),
		'primary_cta_label'   => array( 'type' => 'text',     'label' => __( 'Label CTA Utama', 'dzurriyyatul-academic' ) ),
		'primary_cta_message' => array( 'type' => 'textarea', 'label' => __( 'Pesan WhatsApp untuk CTA Utama', 'dzurriyyatul-academic' ) ),
		'secondary_cta_label' => array( 'type' => 'text',     'label' => __( 'Label CTA Sekunder', 'dzurriyyatul-academic' ) ),
		'secondary_cta_url'   => array( 'type' => 'url',      'label' => __( 'URL CTA Sekunder', 'dzurriyyatul-academic' ) ),
		'image_id'            => array( 'type' => 'image',    'label' => __( 'Gambar Visual', 'dzurriyyatul-academic' ) ),
		'image_alt'           => array( 'type' => 'text',     'label' => __( 'Teks Alternatif Gambar', 'dzurriyyatul-academic' ) ),
		'caption_badge'       => array( 'type' => 'text',     'label' => __( 'Badge Kecil di Atas Gambar', 'dzurriyyatul-academic' ) ),
		'caption_text'        => array( 'type' => 'text',     'label' => __( 'Teks Keterangan di Atas Gambar', 'dzurriyyatul-academic' ) ),
		'quote_text'          => array( 'type' => 'textarea', 'label' => __( 'Kutipan Pendukung', 'dzurriyyatul-academic' ) ),
		'quote_author'        => array( 'type' => 'text',     'label' => __( 'Sumber Kutipan', 'dzurriyyatul-academic' ) ),
		'check_item_1'        => array( 'type' => 'text',     'label' => __( 'Poin Checklist 1', 'dzurriyyatul-academic' ) ),
		'check_item_2'        => array( 'type' => 'text',     'label' => __( 'Poin Checklist 2', 'dzurriyyatul-academic' ) ),
		'metric_1_value'      => array( 'type' => 'text',     'label' => __( 'Metrik 1 - Nilai', 'dzurriyyatul-academic' ) ),
		'metric_1_label'      => array( 'type' => 'text',     'label' => __( 'Metrik 1 - Label', 'dzurriyyatul-academic' ) ),
		'metric_2_value'      => array( 'type' => 'text',     'label' => __( 'Metrik 2 - Nilai', 'dzurriyyatul-academic' ) ),
		'metric_2_label'      => array( 'type' => 'text',     'label' => __( 'Metrik 2 - Label', 'dzurriyyatul-academic' ) ),
		'metric_3_value'      => array( 'type' => 'text',     'label' => __( 'Metrik 3 - Nilai', 'dzurriyyatul-academic' ) ),
		'metric_3_label'      => array( 'type' => 'text',     'label' => __( 'Metrik 3 - Label', 'dzurriyyatul-academic' ) ),
	);

	// type => sanitize callback (reusing the dq_sanitize_by_type() wrappers).
	$dq_hero_sanitize_callbacks = array(
		'text'     => 'dq_customize_sanitize_text',
		'textarea' => 'dq_customize_sanitize_textarea',
		'url'      => 'dq_customize_sanitize_url',
		'image'    => 'absint',
	);

	// type => default value, matching dq_hero_slide_defaults() in inc/helpers.php.
	$dq_hero_default_values = array(
		'text'     => '',
		'textarea' => '',
		'url'      => '',
		'image'    => 0,
	);

	for ( $dq_i = 0; $dq_i < 3; $dq_i++ ) {

		$dq_hero_section_id = "dq_section_hero_{$dq_i}";

		$wp_customize->add_section( $dq_hero_section_id, array(
			/* translators: %d: hero slide number (1-3). */
			'title'    => sprintf( __( 'Hero Slide %d', 'dzurriyyatul-academic' ), $dq_i + 1 ),
			'panel'    => 'dq_panel_hero',
			'priority' => $dq_i * 10,
		) );

		foreach ( $dq_hero_fields as $dq_field_key => $dq_field ) {

			$dq_setting_id = "dq_theme_settings[hero_slides][{$dq_i}][{$dq_field_key}]";

			if ( 'image' === $dq_field['type'] ) {
				$wp_customize->add_setting( $dq_setting_id, array(
					'type'              => 'option',
					'default'           => $dq_hero_default_values['image'],
					'sanitize_callback' => $dq_hero_sanitize_callbacks['image'],
					'transport'         => 'refresh',
				) );

				$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "dq_hero_{$dq_i}_image", array(
					'label'    => $dq_field['label'],
					'section'  => $dq_hero_section_id,
					'settings' => $dq_setting_id,
				) ) );

				continue;
			}

			$wp_customize->add_setting( $dq_setting_id, array(
				'type'              => 'option',
				'default'           => $dq_hero_default_values[ $dq_field['type'] ],
				'sanitize_callback' => $dq_hero_sanitize_callbacks[ $dq_field['type'] ],
				'transport'         => 'refresh',
			) );

			$wp_customize->add_control( $dq_setting_id, array(
				'label'   => $dq_field['label'],
				'section' => $dq_hero_section_id,
				'type'    => $dq_field['type'],
			) );
		}
	}
}
add_action( 'customize_register', 'dq_customize_register' );
