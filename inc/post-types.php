<?php
/**
 * Custom Post Types: service, package, mentor, testimonial, faq.
 *
 * Stage 1 scope only (Master Prompt §16) — no LMS/dashboard/membership CPTs.
 * Every CPT uses 'page-attributes' so admins get WordPress' native drag-
 * orderable "Order" box for Display Order, instead of a bespoke meta field.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all Stage 1 custom post types.
 */
function dq_register_post_types() {
	register_post_type( 'service', array(
		'labels'       => array(
			'name'          => __( 'Layanan', 'dzurriyyatul-academic' ),
			'singular_name' => __( 'Layanan', 'dzurriyyatul-academic' ),
			'add_new_item'  => __( 'Tambah Layanan', 'dzurriyyatul-academic' ),
			'edit_item'     => __( 'Edit Layanan', 'dzurriyyatul-academic' ),
			'all_items'     => __( 'Semua Layanan', 'dzurriyyatul-academic' ),
			'search_items'  => __( 'Cari Layanan', 'dzurriyyatul-academic' ),
			'not_found'     => __( 'Belum ada layanan.', 'dzurriyyatul-academic' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'layanan' ),
		'menu_icon'    => 'dashicons-welcome-learn-more',
		'show_in_rest' => true,
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
	) );

	register_post_type( 'package', array(
		'labels'       => array(
			'name'          => __( 'Paket', 'dzurriyyatul-academic' ),
			'singular_name' => __( 'Paket', 'dzurriyyatul-academic' ),
			'add_new_item'  => __( 'Tambah Paket', 'dzurriyyatul-academic' ),
			'edit_item'     => __( 'Edit Paket', 'dzurriyyatul-academic' ),
			'all_items'     => __( 'Semua Paket', 'dzurriyyatul-academic' ),
			'search_items'  => __( 'Cari Paket', 'dzurriyyatul-academic' ),
			'not_found'     => __( 'Belum ada paket.', 'dzurriyyatul-academic' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'paket' ),
		'menu_icon'    => 'dashicons-tag',
		'show_in_rest' => true,
		'supports'     => array( 'title', 'editor', 'page-attributes' ),
	) );

	register_post_type( 'mentor', array(
		'labels'       => array(
			'name'          => __( 'Mentor', 'dzurriyyatul-academic' ),
			'singular_name' => __( 'Mentor', 'dzurriyyatul-academic' ),
			'add_new_item'  => __( 'Tambah Mentor', 'dzurriyyatul-academic' ),
			'edit_item'     => __( 'Edit Mentor', 'dzurriyyatul-academic' ),
			'all_items'     => __( 'Semua Mentor', 'dzurriyyatul-academic' ),
			'search_items'  => __( 'Cari Mentor', 'dzurriyyatul-academic' ),
			'not_found'     => __( 'Belum ada mentor.', 'dzurriyyatul-academic' ),
		),
		'public'       => true,
		'has_archive'  => false,
		'rewrite'      => array( 'slug' => 'mentor' ),
		'menu_icon'    => 'dashicons-groups',
		'show_in_rest' => true,
		'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
	) );

	register_post_type( 'testimonial', array(
		'labels'       => array(
			'name'          => __( 'Testimoni', 'dzurriyyatul-academic' ),
			'singular_name' => __( 'Testimoni', 'dzurriyyatul-academic' ),
			'add_new_item'  => __( 'Tambah Testimoni', 'dzurriyyatul-academic' ),
			'edit_item'     => __( 'Edit Testimoni', 'dzurriyyatul-academic' ),
			'all_items'     => __( 'Semua Testimoni', 'dzurriyyatul-academic' ),
			'search_items'  => __( 'Cari Testimoni', 'dzurriyyatul-academic' ),
			'not_found'     => __( 'Belum ada testimoni.', 'dzurriyyatul-academic' ),
		),
		'public'       => true,
		'has_archive'  => false,
		'rewrite'      => array( 'slug' => 'testimoni' ),
		'menu_icon'    => 'dashicons-format-quote',
		'show_in_rest' => true,
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
	) );

	register_post_type( 'faq', array(
		'labels'       => array(
			'name'          => __( 'FAQ', 'dzurriyyatul-academic' ),
			'singular_name' => __( 'FAQ', 'dzurriyyatul-academic' ),
			'add_new_item'  => __( 'Tambah FAQ', 'dzurriyyatul-academic' ),
			'edit_item'     => __( 'Edit FAQ', 'dzurriyyatul-academic' ),
			'all_items'     => __( 'Semua FAQ', 'dzurriyyatul-academic' ),
			'search_items'  => __( 'Cari FAQ', 'dzurriyyatul-academic' ),
			'not_found'     => __( 'Belum ada FAQ.', 'dzurriyyatul-academic' ),
		),
		'public'       => true,
		'has_archive'  => false,
		'rewrite'      => array( 'slug' => 'faq' ),
		'menu_icon'    => 'dashicons-editor-help',
		'show_in_rest' => true,
		'supports'     => array( 'title', 'editor', 'page-attributes' ),
	) );
}
add_action( 'init', 'dq_register_post_types' );

/**
 * Field schema for each CPT's single meta box, in the shape:
 *   meta_key => array( 'label' => ..., 'type' => text|textarea|checkbox|number, 'help' => ... )
 *
 * @return array
 */
function dq_cpt_meta_schema() {
	return array(
		'service'     => array(
			'_dq_short_description' => array( 'label' => __( 'Deskripsi Singkat', 'dzurriyyatul-academic' ), 'type' => 'textarea', 'help' => __( 'Ringkasan 1-2 kalimat yang tampil di kartu layanan.', 'dzurriyyatul-academic' ) ),
			'_dq_icon'               => array( 'label' => __( 'Ikon Layanan', 'dzurriyyatul-academic' ), 'type' => 'icon_picker', 'help' => __( 'Klik salah satu ikon di bawah, atau ketik manual kelas Font Awesome lain.', 'dzurriyyatul-academic' ) ),
			'_dq_price_label'        => array( 'label' => __( 'Label Harga', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_duration'           => array( 'label' => __( 'Durasi', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_features'           => array( 'label' => __( 'Poin Unggulan (satu per baris)', 'dzurriyyatul-academic' ), 'type' => 'textarea' ),
			'_dq_cta_text'           => array( 'label' => __( 'Teks Tombol CTA', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_cta_url'            => array( 'label' => __( 'URL Tombol CTA (kosongkan untuk pakai WhatsApp default)', 'dzurriyyatul-academic' ), 'type' => 'url' ),
			'_dq_featured'           => array( 'label' => __( 'Tampilkan sebagai Unggulan', 'dzurriyyatul-academic' ), 'type' => 'checkbox' ),
		),
		'package'     => array(
			'_dq_price'        => array( 'label' => __( 'Harga (angka saja)', 'dzurriyyatul-academic' ), 'type' => 'text', 'help' => __( 'Contoh: 300000. Format tampilan Rupiah otomatis.', 'dzurriyyatul-academic' ) ),
			'_dq_price_label'  => array( 'label' => __( 'Satuan Harga', 'dzurriyyatul-academic' ), 'type' => 'text', 'help' => __( 'Contoh: / paket modul', 'dzurriyyatul-academic' ) ),
			'_dq_billing_type' => array( 'label' => __( 'Tipe Penagihan', 'dzurriyyatul-academic' ), 'type' => 'text', 'help' => __( 'Contoh: Per Sesi, Paket Bab, Full Skripsi', 'dzurriyyatul-academic' ) ),
			'_dq_features'     => array( 'label' => __( 'Fitur (satu per baris)', 'dzurriyyatul-academic' ), 'type' => 'textarea' ),
			'_dq_cta_text'     => array( 'label' => __( 'Teks Tombol CTA', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_cta_url'      => array( 'label' => __( 'URL Tombol CTA (kosongkan untuk pakai WhatsApp default)', 'dzurriyyatul-academic' ), 'type' => 'url' ),
			'_dq_badge_text'   => array( 'label' => __( 'Label Badge (mis. Paling Diminati Mahasiswa)', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_featured'     => array( 'label' => __( 'Tampilkan sebagai Paket Unggulan', 'dzurriyyatul-academic' ), 'type' => 'checkbox' ),
		),
		'mentor'      => array(
			'_dq_role'           => array( 'label' => __( 'Jabatan/Peran', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_specialization' => array( 'label' => __( 'Bidang Spesialisasi', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_credentials'    => array( 'label' => __( 'Badge Kredensial (satu per baris)', 'dzurriyyatul-academic' ), 'type' => 'textarea', 'help' => __( 'Contoh: SINTA 2, Alumni UGM', 'dzurriyyatul-academic' ) ),
			'_dq_academic_id'    => array( 'label' => __( 'ID Akademik (Scopus/SINTA ID, opsional)', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_institution'    => array( 'label' => __( 'Institusi/Almamater', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_experience'     => array( 'label' => __( 'Lama Pengalaman', 'dzurriyyatul-academic' ), 'type' => 'text' ),
		),
		'testimonial' => array(
			'_dq_program'          => array( 'label' => __( 'Program Studi', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_graduation_year'  => array( 'label' => __( 'Tahun Lulus/Publikasi', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_initials'         => array( 'label' => __( 'Inisial (jika tanpa foto)', 'dzurriyyatul-academic' ), 'type' => 'text' ),
			'_dq_rating'           => array( 'label' => __( 'Rating (1-5)', 'dzurriyyatul-academic' ), 'type' => 'number' ),
		),
		'faq'         => array(
			'_dq_category' => array( 'label' => __( 'Kategori/Grup', 'dzurriyyatul-academic' ), 'type' => 'text', 'help' => __( 'Opsional, untuk pengelompokan visual di masa depan.', 'dzurriyyatul-academic' ) ),
		),
	);
}

/**
 * Curated Font Awesome icon choices offered by the visual icon picker on
 * the Layanan (service) meta box, so admins never have to know FA class
 * syntax by heart. Includes the exact 9 icons used by the approved Stitch
 * design's service cards, plus a broader set for services added later.
 *
 * @return array<string,string> icon_class => human label.
 */
function dq_icon_picker_options() {
	return array(
		'fa-solid fa-graduation-cap'      => __( 'Topi Wisuda', 'dzurriyyatul-academic' ),
		'fa-solid fa-file-signature'      => __( 'Tanda Tangan Dokumen', 'dzurriyyatul-academic' ),
		'fa-solid fa-chart-pie'           => __( 'Diagram Data', 'dzurriyyatul-academic' ),
		'fa-solid fa-compass-drafting'    => __( 'Metodologi', 'dzurriyyatul-academic' ),
		'fa-solid fa-book-journal-whills' => __( 'Jurnal Ilmiah', 'dzurriyyatul-academic' ),
		'fa-solid fa-spell-check'         => __( 'Cek Ejaan', 'dzurriyyatul-academic' ),
		'fa-solid fa-chalkboard-user'     => __( 'Kelas/Pengajar', 'dzurriyyatul-academic' ),
		'fa-solid fa-users-viewfinder'    => __( 'Mentoring Kelompok', 'dzurriyyatul-academic' ),
		'fa-solid fa-box-archive'         => __( 'Arsip/Template', 'dzurriyyatul-academic' ),
		'fa-solid fa-book'                => __( 'Buku', 'dzurriyyatul-academic' ),
		'fa-solid fa-book-open'           => __( 'Buku Terbuka', 'dzurriyyatul-academic' ),
		'fa-solid fa-laptop-code'         => __( 'Analisis/Coding', 'dzurriyyatul-academic' ),
		'fa-solid fa-file-lines'          => __( 'Dokumen', 'dzurriyyatul-academic' ),
		'fa-solid fa-users'               => __( 'Kelompok', 'dzurriyyatul-academic' ),
		'fa-solid fa-clipboard-check'     => __( 'Checklist', 'dzurriyyatul-academic' ),
		'fa-solid fa-language'            => __( 'Bahasa/Terjemahan', 'dzurriyyatul-academic' ),
		'fa-solid fa-microscope'          => __( 'Riset Lab', 'dzurriyyatul-academic' ),
		'fa-solid fa-flask'               => __( 'Eksperimen', 'dzurriyyatul-academic' ),
		'fa-solid fa-calculator'          => __( 'Statistik', 'dzurriyyatul-academic' ),
		'fa-solid fa-magnifying-glass-chart' => __( 'Analisis Data', 'dzurriyyatul-academic' ),
		'fa-solid fa-comments'            => __( 'Diskusi', 'dzurriyyatul-academic' ),
		'fa-solid fa-headset'             => __( 'Konsultasi', 'dzurriyyatul-academic' ),
		'fa-solid fa-shield-halved'       => __( 'Integritas', 'dzurriyyatul-academic' ),
		'fa-solid fa-award'               => __( 'Penghargaan', 'dzurriyyatul-academic' ),
		'fa-solid fa-lightbulb'           => __( 'Ide', 'dzurriyyatul-academic' ),
		'fa-solid fa-list-check'          => __( 'Daftar Tugas', 'dzurriyyatul-academic' ),
		'fa-solid fa-file-pdf'            => __( 'PDF', 'dzurriyyatul-academic' ),
		'fa-solid fa-database'            => __( 'Database', 'dzurriyyatul-academic' ),
		'fa-solid fa-pen-nib'             => __( 'Menulis', 'dzurriyyatul-academic' ),
		'fa-solid fa-certificate'         => __( 'Sertifikat', 'dzurriyyatul-academic' ),
		'fa-solid fa-user-graduate'       => __( 'Mahasiswa', 'dzurriyyatul-academic' ),
		'fa-solid fa-handshake'           => __( 'Kesepakatan', 'dzurriyyatul-academic' ),
	);
}

/**
 * Expose every schema field to the REST API as real post meta, so the
 * native Gutenberg sidebar panel (js/admin-cpt-panel.js) can read/write it
 * through the block editor's own data store — no classic meta box, no
 * custom $_POST handling. WordPress's REST meta controller does the actual
 * saving; we only supply type/sanitization/capability per field.
 */
function dq_register_cpt_meta() {
	foreach ( dq_cpt_meta_schema() as $post_type => $fields ) {
		foreach ( $fields as $key => $field ) {
			$type = 'checkbox' === $field['type'] ? 'boolean' : ( 'number' === $field['type'] ? 'integer' : 'string' );

			register_post_meta( $post_type, $key, array(
				'type'              => $type,
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => function ( $value ) use ( $field ) {
					switch ( $field['type'] ) {
						case 'textarea':
							return sanitize_textarea_field( $value );
						case 'url':
							return esc_url_raw( $value );
						case 'number':
							return max( 1, min( 5, absint( $value ) ) );
						case 'checkbox':
							return (bool) $value;
						default:
							return sanitize_text_field( $value );
					}
				},
				'auth_callback'     => function () use ( $post_type ) {
					$post_type_object = get_post_type_object( $post_type );
					return $post_type_object && current_user_can( $post_type_object->cap->edit_posts );
				},
			) );
		}
	}
}
add_action( 'init', 'dq_register_cpt_meta' );

/**
 * Load the native sidebar panel (Gutenberg PluginDocumentSettingPanel) on
 * the 5 custom post types' add/edit screens only. Replaces the old classic
 * meta box entirely — the panel reads/writes meta via register_post_meta()
 * above instead of a hand-rolled $_POST save routine.
 */
function dq_enqueue_cpt_panel_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();
	$schema = dq_cpt_meta_schema();
	if ( ! $screen || ! isset( $schema[ $screen->post_type ] ) ) {
		return;
	}

	wp_enqueue_style( 'dq-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1' );
	wp_enqueue_style( 'dq-admin-cpt-panel', DQ_THEME_URI . '/assets/css/admin-cpt-panel.css', array(), DQ_THEME_VERSION );

	wp_enqueue_script(
		'dq-admin-cpt-panel',
		DQ_THEME_URI . '/js/admin-cpt-panel.js',
		array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-i18n' ),
		DQ_THEME_VERSION,
		true
	);

	$labels = array(
		'service'     => __( 'Detail Layanan', 'dzurriyyatul-academic' ),
		'package'     => __( 'Detail Paket', 'dzurriyyatul-academic' ),
		'mentor'      => __( 'Detail Mentor', 'dzurriyyatul-academic' ),
		'testimonial' => __( 'Detail Testimoni', 'dzurriyyatul-academic' ),
		'faq'         => __( 'Detail FAQ', 'dzurriyyatul-academic' ),
	);

	wp_add_inline_script(
		'dq-admin-cpt-panel',
		'window.dqCptPanel = ' . wp_json_encode( array(
			'schema' => $schema,
			'icons'  => dq_icon_picker_options(),
			'labels' => $labels,
		) ) . ';',
		'before'
	);
}
add_action( 'admin_enqueue_scripts', 'dq_enqueue_cpt_panel_assets' );
