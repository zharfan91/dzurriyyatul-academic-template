<?php
/**
 * Theme Settings admin page (Settings API) — Master Prompt §22.
 *
 * A single sanitized option `dq_theme_settings` backs every dynamic,
 * business-specific value in the theme (brand contact info, social links,
 * legal info, SEO fallback, and the 3 hero slides), so nothing production-
 * specific is ever hard-coded into a template (§24, §30, §31, §37).
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Flat (non-hero) field schema: key => sanitize type.
 * Types: text | textarea | email | url.
 *
 * @return array
 */
function dq_settings_schema() {
	return array(
		'tagline_override'        => 'text',
		'whatsapp_number'         => 'text',
		'whatsapp_default_message'=> 'textarea',
		'contact_email'           => 'email',
		'contact_phone'           => 'text',
		'contact_address'         => 'textarea',
		'operating_hours'         => 'text',
		'social_instagram'        => 'url',
		'social_facebook'         => 'url',
		'social_tiktok'           => 'url',
		'social_youtube'          => 'url',
		'google_maps_url'         => 'url',
		'legal_entity_name'       => 'text',
		'legal_ahu_number'        => 'text',
		'legal_sk_date'           => 'text',
		'legal_note'              => 'textarea',
		'seo_default_description' => 'textarea',
		'integrity_eyebrow_text'   => 'text',
		'integrity_heading'        => 'text',
		'integrity_notice_text'    => 'textarea',
		'integrity_body'           => 'textarea',
		'integrity_dont_1'         => 'text',
		'integrity_dont_2'         => 'text',
		'integrity_do_1'           => 'text',
		'integrity_do_2'           => 'text',
		'integrity_pledge_heading' => 'text',
		'integrity_pledge_quote'   => 'textarea',
		'integrity_pledge_badge'   => 'text',
	);
}

/**
 * Per-slide field schema: key => sanitize type.
 * Types: text | textarea | url | image.
 *
 * @return array
 */
function dq_hero_slide_schema() {
	return array(
		'tab_label'           => 'text',
		'badge_emoji'         => 'text',
		'badge_text'          => 'text',
		'heading_line1'       => 'text',
		'heading_line2'       => 'text',
		'tagline_quote'       => 'text',
		'tags'                => 'text',
		'description'         => 'textarea',
		'primary_cta_label'   => 'text',
		'primary_cta_message' => 'textarea',
		'secondary_cta_label' => 'text',
		'secondary_cta_url'   => 'url',
		'image_id'            => 'image',
		'image_alt'           => 'text',
		'caption_badge'       => 'text',
		'caption_text'        => 'text',
		'quote_text'          => 'textarea',
		'quote_author'        => 'text',
		'check_item_1'        => 'text',
		'check_item_2'        => 'text',
		'metric_1_value'      => 'text',
		'metric_1_label'      => 'text',
		'metric_2_value'      => 'text',
		'metric_2_label'      => 'text',
		'metric_3_value'      => 'text',
		'metric_3_label'      => 'text',
	);
}

/**
 * Register the settings menu page.
 */
function dq_register_settings_page() {
	add_options_page(
		__( 'Pengaturan Situs Akademik', 'dzurriyyatul-academic' ),
		__( 'Pengaturan Akademik', 'dzurriyyatul-academic' ),
		'manage_options',
		'dq-theme-settings',
		'dq_render_settings_page'
	);
}
add_action( 'admin_menu', 'dq_register_settings_page' );

/**
 * Register the option + sanitize callback.
 */
function dq_register_setting() {
	register_setting( 'dq_theme_settings_group', 'dq_theme_settings', array(
		'type'              => 'array',
		'sanitize_callback' => 'dq_sanitize_theme_settings',
		'default'           => array(),
	) );
}
add_action( 'admin_init', 'dq_register_setting' );

/**
 * Sanitize one scalar value by its declared type.
 *
 * @param mixed  $raw  Raw value.
 * @param string $type One of text|textarea|email|url|image.
 * @return mixed
 */
function dq_sanitize_by_type( $raw, $type ) {
	switch ( $type ) {
		case 'textarea':
			return sanitize_textarea_field( $raw );
		case 'email':
			return sanitize_email( $raw );
		case 'url':
			return esc_url_raw( trim( (string) $raw ) );
		case 'image':
			return absint( $raw );
		default:
			return sanitize_text_field( $raw );
	}
}

/**
 * Sanitize the entire settings array (flat fields + 3 hero slides).
 *
 * @param array $input Raw $_POST-derived array from the settings form.
 * @return array
 */
function dq_sanitize_theme_settings( $input ) {
	$clean = array();

	if ( ! is_array( $input ) ) {
		return $clean;
	}

	foreach ( dq_settings_schema() as $key => $type ) {
		$clean[ $key ] = isset( $input[ $key ] ) ? dq_sanitize_by_type( $input[ $key ], $type ) : '';
	}

	$clean['hero_slides'] = array();
	$slide_schema         = dq_hero_slide_schema();

	for ( $i = 0; $i < 3; $i++ ) {
		$slide       = isset( $input['hero_slides'][ $i ] ) && is_array( $input['hero_slides'][ $i ] ) ? $input['hero_slides'][ $i ] : array();
		$clean_slide = array();

		foreach ( $slide_schema as $key => $type ) {
			$clean_slide[ $key ] = isset( $slide[ $key ] ) ? dq_sanitize_by_type( $slide[ $key ], $type ) : '';
		}

		$clean['hero_slides'][] = $clean_slide;
	}

	return $clean;
}

/**
 * Load the media uploader script on our settings page only.
 *
 * @param string $hook Current admin page hook.
 */
function dq_settings_page_assets( $hook ) {
	if ( 'settings_page_dq-theme-settings' !== $hook ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script( 'dq-settings-media', DQ_THEME_URI . '/js/admin-settings.js', array( 'jquery' ), DQ_THEME_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'dq_settings_page_assets' );

/**
 * Text/textarea/url/email input row.
 *
 * @param string $name  Field name attribute.
 * @param string $label Field label.
 * @param string $value Current value.
 * @param string $type  Field type.
 */
function dq_field_row( $name, $label, $value, $type = 'text' ) {
	echo '<tr><th scope="row"><label for="' . esc_attr( $name ) . '">' . esc_html( $label ) . '</label></th><td>';

	if ( 'textarea' === $type ) {
		echo '<textarea id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" rows="3" class="large-text">' . esc_textarea( $value ) . '</textarea>';
	} else {
		$html_type = in_array( $type, array( 'email', 'url', 'text' ), true ) ? $type : 'text';
		echo '<input type="' . esc_attr( $html_type ) . '" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="large-text" />';
	}

	echo '</td></tr>';
}

/**
 * Media picker row (stores an attachment ID).
 *
 * @param string $name  Field name attribute.
 * @param string $label Field label.
 * @param int    $value Attachment ID.
 */
function dq_image_field_row( $name, $label, $value ) {
	$value = absint( $value );
	$src   = $value ? wp_get_attachment_image_url( $value, 'thumbnail' ) : '';

	echo '<tr><th scope="row">' . esc_html( $label ) . '</th><td>';
	echo '<div class="dq-image-field">';
	echo '<img src="' . esc_url( $src ) . '" style="max-width:120px;height:auto;display:' . ( $src ? 'block' : 'none' ) . ';margin-bottom:8px;" class="dq-image-preview" />';
	echo '<input type="hidden" class="dq-image-value" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';
	echo '<button type="button" class="button dq-image-select">' . esc_html__( 'Pilih Gambar', 'dzurriyyatul-academic' ) . '</button> ';
	echo '<button type="button" class="button dq-image-remove"' . ( $src ? '' : ' style="display:none;"' ) . '>' . esc_html__( 'Hapus', 'dzurriyyatul-academic' ) . '</button>';
	echo '</div>';
	echo '</td></tr>';
}

/**
 * Render the full settings page.
 */
function dq_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = get_option( 'dq_theme_settings', array() );
	$flat     = wp_parse_args( $settings, array_fill_keys( array_keys( dq_settings_schema() ), '' ) );

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Pengaturan Situs Akademik', 'dzurriyyatul-academic' ); ?></h1>
		<p><?php esc_html_e( 'Kelola nomor WhatsApp, kontak, media sosial, legalitas, dan konten 3 slide hero. Logo, favicon, dan judul situs dikelola melalui Penyesuai (Customizer) → Identitas Situs.', 'dzurriyyatul-academic' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'dq_theme_settings_group' ); ?>

			<h2 class="title"><?php esc_html_e( 'Kontak & WhatsApp', 'dzurriyyatul-academic' ); ?></h2>
			<table class="form-table" role="presentation"><tbody>
				<?php
				dq_field_row( 'dq_theme_settings[whatsapp_number]', __( 'Nomor WhatsApp (contoh: 6281234567890)', 'dzurriyyatul-academic' ), $flat['whatsapp_number'] );
				dq_field_row( 'dq_theme_settings[whatsapp_default_message]', __( 'Pesan Default WhatsApp', 'dzurriyyatul-academic' ), $flat['whatsapp_default_message'], 'textarea' );
				dq_field_row( 'dq_theme_settings[contact_email]', __( 'Email', 'dzurriyyatul-academic' ), $flat['contact_email'], 'email' );
				dq_field_row( 'dq_theme_settings[contact_phone]', __( 'Telepon', 'dzurriyyatul-academic' ), $flat['contact_phone'] );
				dq_field_row( 'dq_theme_settings[contact_address]', __( 'Alamat', 'dzurriyyatul-academic' ), $flat['contact_address'], 'textarea' );
				dq_field_row( 'dq_theme_settings[operating_hours]', __( 'Jam Operasional', 'dzurriyyatul-academic' ), $flat['operating_hours'] );
				dq_field_row( 'dq_theme_settings[tagline_override]', __( 'Tagline (opsional, override tagline situs)', 'dzurriyyatul-academic' ), $flat['tagline_override'] );
				?>
			</tbody></table>

			<h2 class="title"><?php esc_html_e( 'Media Sosial & Peta', 'dzurriyyatul-academic' ); ?></h2>
			<table class="form-table" role="presentation"><tbody>
				<?php
				dq_field_row( 'dq_theme_settings[social_instagram]', __( 'Instagram URL', 'dzurriyyatul-academic' ), $flat['social_instagram'], 'url' );
				dq_field_row( 'dq_theme_settings[social_facebook]', __( 'Facebook URL', 'dzurriyyatul-academic' ), $flat['social_facebook'], 'url' );
				dq_field_row( 'dq_theme_settings[social_tiktok]', __( 'TikTok URL', 'dzurriyyatul-academic' ), $flat['social_tiktok'], 'url' );
				dq_field_row( 'dq_theme_settings[social_youtube]', __( 'YouTube URL', 'dzurriyyatul-academic' ), $flat['social_youtube'], 'url' );
				dq_field_row( 'dq_theme_settings[google_maps_url]', __( 'Google Maps Embed/Link URL', 'dzurriyyatul-academic' ), $flat['google_maps_url'], 'url' );
				?>
			</tbody></table>

			<h2 class="title"><?php esc_html_e( 'Legalitas', 'dzurriyyatul-academic' ); ?></h2>
			<table class="form-table" role="presentation"><tbody>
				<?php
				dq_field_row( 'dq_theme_settings[legal_entity_name]', __( 'Nama Badan Hukum/Yayasan', 'dzurriyyatul-academic' ), $flat['legal_entity_name'] );
				dq_field_row( 'dq_theme_settings[legal_ahu_number]', __( 'Nomor AHU Kemenkumham', 'dzurriyyatul-academic' ), $flat['legal_ahu_number'] );
				dq_field_row( 'dq_theme_settings[legal_sk_date]', __( 'Tanggal SK', 'dzurriyyatul-academic' ), $flat['legal_sk_date'] );
				dq_field_row( 'dq_theme_settings[legal_note]', __( 'Catatan Legal Tambahan', 'dzurriyyatul-academic' ), $flat['legal_note'], 'textarea' );
				?>
			</tbody></table>

			<h2 class="title"><?php esc_html_e( 'SEO', 'dzurriyyatul-academic' ); ?></h2>
			<table class="form-table" role="presentation"><tbody>
				<?php dq_field_row( 'dq_theme_settings[seo_default_description]', __( 'Meta Deskripsi Default', 'dzurriyyatul-academic' ), $flat['seo_default_description'], 'textarea' ); ?>
			</tbody></table>

			<h2 class="title"><?php esc_html_e( 'Integritas & Etika', 'dzurriyyatul-academic' ); ?></h2>
			<table class="form-table" role="presentation"><tbody>
				<?php
				dq_field_row( 'dq_theme_settings[integrity_eyebrow_text]', __( 'Label Badge (Eyebrow)', 'dzurriyyatul-academic' ), $flat['integrity_eyebrow_text'] );
				dq_field_row( 'dq_theme_settings[integrity_heading]', __( 'Judul Section', 'dzurriyyatul-academic' ), $flat['integrity_heading'] );
				dq_field_row( 'dq_theme_settings[integrity_notice_text]', __( 'Teks Peringatan "PENTING"', 'dzurriyyatul-academic' ), $flat['integrity_notice_text'], 'textarea' );
				dq_field_row( 'dq_theme_settings[integrity_body]', __( 'Paragraf Deskripsi', 'dzurriyyatul-academic' ), $flat['integrity_body'], 'textarea' );
				dq_field_row( 'dq_theme_settings[integrity_dont_1]', __( 'Poin "TIDAK" 1', 'dzurriyyatul-academic' ), $flat['integrity_dont_1'] );
				dq_field_row( 'dq_theme_settings[integrity_dont_2]', __( 'Poin "TIDAK" 2', 'dzurriyyatul-academic' ), $flat['integrity_dont_2'] );
				dq_field_row( 'dq_theme_settings[integrity_do_1]', __( 'Poin "YA" 1', 'dzurriyyatul-academic' ), $flat['integrity_do_1'] );
				dq_field_row( 'dq_theme_settings[integrity_do_2]', __( 'Poin "YA" 2', 'dzurriyyatul-academic' ), $flat['integrity_do_2'] );
				dq_field_row( 'dq_theme_settings[integrity_pledge_heading]', __( 'Judul Kartu Pakta', 'dzurriyyatul-academic' ), $flat['integrity_pledge_heading'] );
				dq_field_row( 'dq_theme_settings[integrity_pledge_quote]', __( 'Kutipan Kartu Pakta', 'dzurriyyatul-academic' ), $flat['integrity_pledge_quote'], 'textarea' );
				dq_field_row( 'dq_theme_settings[integrity_pledge_badge]', __( 'Teks Badge Pakta', 'dzurriyyatul-academic' ), $flat['integrity_pledge_badge'] );
				?>
			</tbody></table>

			<h2 class="title"><?php esc_html_e( 'Hero Slides (3 Slide)', 'dzurriyyatul-academic' ); ?></h2>
			<?php
			$hero_defaults = dq_hero_slide_defaults();
			for ( $i = 0; $i < 3; $i++ ) :
				$slide = isset( $settings['hero_slides'][ $i ] ) && is_array( $settings['hero_slides'][ $i ] ) ? wp_parse_args( $settings['hero_slides'][ $i ], $hero_defaults ) : $hero_defaults;
				$n     = 'dq_theme_settings[hero_slides][' . $i . ']';
				?>
				<h3><?php printf( esc_html__( 'Slide %d', 'dzurriyyatul-academic' ), $i + 1 ); ?></h3>
				<table class="form-table" role="presentation"><tbody>
					<?php
					dq_field_row( $n . '[tab_label]', __( 'Label Tab (mis. Bimbingan Skripsi & Tesis)', 'dzurriyyatul-academic' ), $slide['tab_label'] );
					dq_field_row( $n . '[badge_emoji]', __( 'Emoji Badge', 'dzurriyyatul-academic' ), $slide['badge_emoji'] );
					dq_field_row( $n . '[badge_text]', __( 'Teks Badge', 'dzurriyyatul-academic' ), $slide['badge_text'] );
					dq_field_row( $n . '[heading_line1]', __( 'Judul Baris 1', 'dzurriyyatul-academic' ), $slide['heading_line1'] );
					dq_field_row( $n . '[heading_line2]', __( 'Judul Baris 2 (ditampilkan miring & warna primer)', 'dzurriyyatul-academic' ), $slide['heading_line2'] );
					dq_field_row( $n . '[tagline_quote]', __( 'Tagline Kutipan', 'dzurriyyatul-academic' ), $slide['tagline_quote'] );
					dq_field_row( $n . '[tags]', __( 'Tag Topik (pisahkan dengan koma)', 'dzurriyyatul-academic' ), $slide['tags'] );
					dq_field_row( $n . '[description]', __( 'Deskripsi', 'dzurriyyatul-academic' ), $slide['description'], 'textarea' );
					dq_field_row( $n . '[primary_cta_label]', __( 'Label CTA Utama', 'dzurriyyatul-academic' ), $slide['primary_cta_label'] );
					dq_field_row( $n . '[primary_cta_message]', __( 'Pesan WhatsApp untuk CTA Utama', 'dzurriyyatul-academic' ), $slide['primary_cta_message'], 'textarea' );
					dq_field_row( $n . '[secondary_cta_label]', __( 'Label CTA Sekunder', 'dzurriyyatul-academic' ), $slide['secondary_cta_label'] );
					dq_field_row( $n . '[secondary_cta_url]', __( 'URL CTA Sekunder', 'dzurriyyatul-academic' ), $slide['secondary_cta_url'], 'url' );
					dq_image_field_row( $n . '[image_id]', __( 'Gambar Visual', 'dzurriyyatul-academic' ), $slide['image_id'] );
					dq_field_row( $n . '[image_alt]', __( 'Teks Alternatif Gambar', 'dzurriyyatul-academic' ), $slide['image_alt'] );
					dq_field_row( $n . '[caption_badge]', __( 'Badge Kecil di Atas Gambar', 'dzurriyyatul-academic' ), $slide['caption_badge'] );
					dq_field_row( $n . '[caption_text]', __( 'Teks Keterangan di Atas Gambar', 'dzurriyyatul-academic' ), $slide['caption_text'] );
					dq_field_row( $n . '[quote_text]', __( 'Kutipan Pendukung', 'dzurriyyatul-academic' ), $slide['quote_text'], 'textarea' );
					dq_field_row( $n . '[quote_author]', __( 'Sumber Kutipan', 'dzurriyyatul-academic' ), $slide['quote_author'] );
					dq_field_row( $n . '[check_item_1]', __( 'Poin Checklist 1', 'dzurriyyatul-academic' ), $slide['check_item_1'] );
					dq_field_row( $n . '[check_item_2]', __( 'Poin Checklist 2', 'dzurriyyatul-academic' ), $slide['check_item_2'] );
					dq_field_row( $n . '[metric_1_value]', __( 'Metrik 1 - Nilai', 'dzurriyyatul-academic' ), $slide['metric_1_value'] );
					dq_field_row( $n . '[metric_1_label]', __( 'Metrik 1 - Label', 'dzurriyyatul-academic' ), $slide['metric_1_label'] );
					dq_field_row( $n . '[metric_2_value]', __( 'Metrik 2 - Nilai', 'dzurriyyatul-academic' ), $slide['metric_2_value'] );
					dq_field_row( $n . '[metric_2_label]', __( 'Metrik 2 - Label', 'dzurriyyatul-academic' ), $slide['metric_2_label'] );
					dq_field_row( $n . '[metric_3_value]', __( 'Metrik 3 - Nilai', 'dzurriyyatul-academic' ), $slide['metric_3_value'] );
					dq_field_row( $n . '[metric_3_label]', __( 'Metrik 3 - Label', 'dzurriyyatul-academic' ), $slide['metric_3_label'] );
					?>
				</tbody></table>
				<?php
			endfor;
			?>

			<?php submit_button( __( 'Simpan Pengaturan', 'dzurriyyatul-academic' ) ); ?>
		</form>
	</div>
	<?php
}
