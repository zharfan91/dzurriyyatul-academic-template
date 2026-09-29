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
 * Register the flat (non-hero) dq_theme_settings fields as native
 * Customizer panels/sections/settings/controls. The Hero Slides panel lives
 * in inc/customizer-hero.php.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function dq_customize_register( $wp_customize ) {

	/**
	 * =========================================================================
	 * Panel
	 * =========================================================================
	 */
	$wp_customize->add_panel( 'dq_panel_site_settings', array(
		'title'       => __( 'Pengaturan Situs Akademik', 'dzurriyyatul-academic' ),
		'description' => __( 'Kelola nomor WhatsApp, kontak, media sosial, legalitas, SEO, dan integritas akademik. Logo, favicon, dan judul situs dikelola melalui Identitas Situs.', 'dzurriyyatul-academic' ),
		'priority'    => 30,
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

	$wp_customize->add_setting( 'dq_theme_settings[about_page_url]', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'dq_customize_sanitize_url',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'dq_theme_settings[about_page_url]', array(
		'label'       => __( 'URL Halaman Tentang Kami', 'dzurriyyatul-academic' ),
		'description' => __( 'Isi setelah membuat halaman "Tentang Kami" di Pages → Add New (gunakan Template "Tentang Kami"). Kosongkan untuk menyembunyikan tombol "Lihat Legalitas" di banner legalitas homepage.', 'dzurriyyatul-academic' ),
		'section'     => 'dq_section_legal',
		'type'        => 'url',
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

}
add_action( 'customize_register', 'dq_customize_register' );
