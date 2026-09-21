<?php
/**
 * Small reusable helpers used throughout the theme.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a safe wa.me URL from a saved settings phone number + message.
 *
 * Never duplicate the WhatsApp number string in templates — always call
 * this helper (Master Prompt §37).
 *
 * @param string $message Optional message to pre-fill. Falls back to the
 *                         theme setting's default message.
 * @return string Escaped-ready wa.me URL (esc_url() should still be applied
 *                at the point of output, per WordPress convention).
 */
function dq_whatsapp_url( $message = '' ) {
	$number = dq_get_setting( 'whatsapp_number' );
	$digits = preg_replace( '/[^0-9]/', '', (string) $number );

	if ( empty( $digits ) ) {
		return '';
	}

	// Normalize a leading 0 to Indonesia's country code, only when no
	// country code appears to be present already.
	if ( '0' === substr( $digits, 0, 1 ) ) {
		$digits = '62' . substr( $digits, 1 );
	}

	if ( '' === $message ) {
		$message = dq_get_setting( 'whatsapp_default_message' );
	}

	$url = 'https://wa.me/' . $digits;

	if ( ! empty( $message ) ) {
		$url .= '?text=' . rawurlencode( $message );
	}

	return $url;
}

/**
 * Read one field from the consolidated theme settings option.
 *
 * @param string $key     Field key.
 * @param mixed  $default Fallback when unset.
 * @return mixed
 */
function dq_get_setting( $key, $default = '' ) {
	static $settings = null;

	if ( null === $settings ) {
		$settings = get_option( 'dq_theme_settings', array() );
		if ( ! is_array( $settings ) ) {
			$settings = array();
		}
	}

	if ( isset( $settings[ $key ] ) && '' !== $settings[ $key ] ) {
		return $settings[ $key ];
	}

	return $default;
}

/**
 * Read one hero slide's settings array (0-indexed, 3 slides supported).
 *
 * @param int $index Slide index (0-2).
 * @return array
 */
function dq_get_hero_slide( $index ) {
	$slides = dq_get_setting( 'hero_slides', array() );

	if ( isset( $slides[ $index ] ) && is_array( $slides[ $index ] ) ) {
		return wp_parse_args( $slides[ $index ], dq_hero_slide_defaults() );
	}

	return dq_hero_slide_defaults();
}

/**
 * Default (empty) shape of one hero slide, so templates never have to
 * guess at array keys.
 *
 * @return array
 */
function dq_hero_slide_defaults() {
	return array(
		'tab_label'          => '',
		'badge_emoji'        => '',
		'badge_text'         => '',
		'heading_line1'      => '',
		'heading_line2'      => '',
		'tagline_quote'      => '',
		'tags'               => '',
		'description'        => '',
		'primary_cta_label'  => '',
		'primary_cta_message'=> '',
		'secondary_cta_label'=> '',
		'secondary_cta_url'  => '',
		'image_id'           => 0,
		'image_alt'          => '',
		'caption_badge'      => '',
		'caption_text'       => '',
		'quote_text'         => '',
		'quote_author'       => '',
		'check_item_1'       => '',
		'check_item_2'       => '',
		'metric_1_value'     => '',
		'metric_1_label'     => '',
		'metric_2_value'     => '',
		'metric_2_label'     => '',
		'metric_3_value'     => '',
		'metric_3_label'     => '',
	);
}

/**
 * Render a Font Awesome icon safely from an admin-entered class string.
 *
 * Only allows the expected Font Awesome class vocabulary so a stray meta
 * value can never inject arbitrary HTML/attributes.
 *
 * @param string $icon_class e.g. "fa-solid fa-graduation-cap".
 * @return string Escaped <i> markup, or empty string when invalid.
 */
function dq_icon( $icon_class ) {
	$icon_class = trim( (string) $icon_class );

	if ( '' === $icon_class || ! preg_match( '/^[a-zA-Z0-9\-\s]+$/', $icon_class ) ) {
		return '';
	}

	return '<i class="' . esc_attr( $icon_class ) . '" aria-hidden="true"></i>';
}

/**
 * Split a newline-separated textarea meta value into a clean array.
 *
 * @param string $raw Raw meta value.
 * @return string[]
 */
function dq_lines_to_array( $raw ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $raw );
	$lines = array_map( 'trim', $lines );
	return array_values( array_filter( $lines, static function ( $line ) {
		return '' !== $line;
	} ) );
}

/**
 * Split a comma-separated string into a clean array (used for hero tag chips).
 *
 * @param string $raw Raw comma separated value.
 * @return string[]
 */
function dq_csv_to_array( $raw ) {
	$parts = explode( ',', (string) $raw );
	$parts = array_map( 'trim', $parts );
	return array_values( array_filter( $parts, static function ( $part ) {
		return '' !== $part;
	} ) );
}

/**
 * Strip a redundant leading/trailing quote mark from a value that a
 * template already wraps in typographic quotes (e.g. the Hero tagline
 * and quote fields use &ldquo;…&rdquo;). Without this, an admin who
 * naturally types the quote marks themselves ends up with doubled
 * quotes like ""text.""
 *
 * @param string $text Raw field value.
 * @return string
 */
function dq_strip_wrapping_quotes( $text ) {
	$text = trim( (string) $text );
	$text = preg_replace( '/^["\'\x{201C}\x{2018}]+/u', '', $text );
	$text = preg_replace( '/["\'\x{201D}\x{2019}]+$/u', '', $text );
	return trim( $text );
}

/**
 * Strip a redundant leading dash from a quote-attribution value that a
 * template already prefixes with an em dash (e.g. Hero quote author),
 * so the result never doubles up as "— — Name".
 *
 * @param string $text Raw field value.
 * @return string
 */
function dq_strip_leading_dash( $text ) {
	$text = trim( (string) $text );
	return trim( (string) preg_replace( '/^[-–—\x{2013}\x{2014}]+\s*/u', '', $text ) );
}
