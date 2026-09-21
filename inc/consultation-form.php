<?php
/**
 * Consultation form: nonce-protected, sanitized, validated AJAX handler.
 *
 * Fields per Master Prompt §51: Nama, WhatsApp, Email, Universitas, Program
 * Studi, Jenjang, Tahap Penelitian, Topik Penelitian, Masalah Utama,
 * Layanan yang Dibutuhkan, Pesan. §53 privacy: no logging of submission
 * contents beyond the single email notification, nothing written to a
 * public page, no personal data placed into JS or URLs.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field schema for the consultation form: key => array(label, required, type).
 *
 * @return array
 */
function dq_consultation_field_schema() {
	return array(
		'name'            => array( 'label' => __( 'Nama Lengkap', 'dzurriyyatul-academic' ), 'required' => true, 'type' => 'text' ),
		'whatsapp'        => array( 'label' => __( 'Nomor WhatsApp', 'dzurriyyatul-academic' ), 'required' => true, 'type' => 'text' ),
		'email'           => array( 'label' => __( 'Email', 'dzurriyyatul-academic' ), 'required' => false, 'type' => 'email' ),
		'university'      => array( 'label' => __( 'Universitas', 'dzurriyyatul-academic' ), 'required' => false, 'type' => 'text' ),
		'program'         => array( 'label' => __( 'Program Studi', 'dzurriyyatul-academic' ), 'required' => false, 'type' => 'text' ),
		'level'           => array( 'label' => __( 'Jenjang', 'dzurriyyatul-academic' ), 'required' => false, 'type' => 'text' ),
		'research_stage'  => array( 'label' => __( 'Tahap Penelitian', 'dzurriyyatul-academic' ), 'required' => false, 'type' => 'text' ),
		'research_topic'  => array( 'label' => __( 'Topik Penelitian', 'dzurriyyatul-academic' ), 'required' => false, 'type' => 'text' ),
		'main_problem'    => array( 'label' => __( 'Masalah Utama', 'dzurriyyatul-academic' ), 'required' => false, 'type' => 'textarea' ),
		'service_needed'  => array( 'label' => __( 'Layanan yang Dibutuhkan', 'dzurriyyatul-academic' ), 'required' => false, 'type' => 'text' ),
		'message'         => array( 'label' => __( 'Pesan', 'dzurriyyatul-academic' ), 'required' => false, 'type' => 'textarea' ),
	);
}

/**
 * Handle the AJAX submission (both logged-in and guest visitors).
 */
function dq_handle_consultation_form() {
	check_ajax_referer( 'dq_consultation_form', 'nonce' );

	// Honeypot: a hidden field real visitors never fill in.
	if ( ! empty( $_POST['dq_website'] ) ) {
		wp_send_json_success( array( 'message' => __( 'Terima kasih, permintaan Anda telah kami terima.', 'dzurriyyatul-academic' ) ) );
	}

	$schema = dq_consultation_field_schema();
	$clean  = array();
	$errors = array();

	foreach ( $schema as $key => $field ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';

		switch ( $field['type'] ) {
			case 'email':
				$value = sanitize_email( $raw );
				if ( '' !== $value && ! is_email( $value ) ) {
					$errors[] = sprintf(
						/* translators: %s: field label */
						__( '%s tidak valid.', 'dzurriyyatul-academic' ),
						$field['label']
					);
					$value = '';
				}
				break;
			case 'textarea':
				$value = sanitize_textarea_field( $raw );
				break;
			default:
				$value = sanitize_text_field( $raw );
		}

		if ( $field['required'] && '' === trim( $value ) ) {
			$errors[] = sprintf(
				/* translators: %s: field label */
				__( '%s wajib diisi.', 'dzurriyyatul-academic' ),
				$field['label']
			);
		}

		$clean[ $key ] = $value;
	}

	if ( '' === $clean['email'] && '' === $clean['whatsapp'] ) {
		$errors[] = __( 'Sertakan setidaknya nomor WhatsApp atau email agar kami dapat menghubungi Anda.', 'dzurriyyatul-academic' );
	}

	if ( ! empty( $errors ) ) {
		wp_send_json_error( array( 'message' => implode( ' ', $errors ) ), 400 );
	}

	$recipient = dq_get_setting( 'contact_email' );
	if ( '' === $recipient ) {
		$recipient = get_option( 'admin_email' );
	}

	$subject = sprintf(
		/* translators: %s: sender name */
		__( '[Konsultasi Baru] %s', 'dzurriyyatul-academic' ),
		$clean['name']
	);

	$body_lines = array();
	foreach ( $schema as $key => $field ) {
		if ( '' !== $clean[ $key ] ) {
			$body_lines[] = $field['label'] . ': ' . $clean[ $key ];
		}
	}
	$body = implode( "\n", $body_lines );

	$sent = wp_mail( $recipient, $subject, $body );

	if ( ! $sent ) {
		wp_send_json_error( array( 'message' => __( 'Maaf, terjadi kendala teknis saat mengirim permintaan. Silakan hubungi kami langsung melalui WhatsApp.', 'dzurriyyatul-academic' ) ), 500 );
	}

	/**
	 * Fires after a consultation request email has been sent successfully.
	 * A site owner can hook a CRM/notification integration here without
	 * touching this file.
	 *
	 * @param array $clean Sanitized submission fields.
	 */
	do_action( 'dq_consultation_form_submitted', $clean );

	wp_send_json_success( array( 'message' => __( 'Terima kasih! Tim kami akan segera menghubungi Anda.', 'dzurriyyatul-academic' ) ) );
}
add_action( 'wp_ajax_dq_consultation_form', 'dq_handle_consultation_form' );
add_action( 'wp_ajax_nopriv_dq_consultation_form', 'dq_handle_consultation_form' );
