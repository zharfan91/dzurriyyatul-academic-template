<?php
/**
 * Theme Settings schema + sanitization — Master Prompt §22.
 *
 * A single sanitized option `dq_theme_settings` backs every dynamic,
 * business-specific value in the theme (brand contact info, social links,
 * legal info, SEO fallback, and the 3 hero slides), so nothing production-
 * specific is ever hard-coded into a template (§24, §30, §31, §37).
 *
 * The admin UI for editing this option now lives natively in the Customizer
 * (Appearance → Customize), registered in inc/customizer.php, which gives
 * organized sections and WordPress's built-in live preview for free. This
 * file keeps only the shared schema/sanitize functions that both the
 * Customizer controls and register_setting() rely on.
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
		'about_page_url'          => 'url',
		'seo_default_description' => 'textarea',
		'hero_autoplay_enabled'    => 'checkbox',
		'hero_autoplay_duration'   => 'number',
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
		'enabled'             => 'checkbox',
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
		'image_position'      => 'text',
		'image_zoom'          => 'text',
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
		'frame_enabled'       => 'checkbox',
		'frame_shape'         => 'text',
		'frame_intensity'     => 'text',
		'frame_width'         => 'text',
		'frame_position'      => 'text',
	);
}

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
		case 'checkbox':
			return rest_sanitize_boolean( $raw );
		case 'number':
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

	// Non-blank fallbacks for fields whose correct "not yet saved" default
	// isn't an empty string — e.g. a field introduced in a theme update that
	// an older stored dq_theme_settings option simply doesn't have a key for
	// yet. Without this, saving ANY unrelated field (this function rebuilds
	// the whole option on every save) would write '' for these, which
	// wp_parse_args() in dq_get_hero_slide()/dq_get_setting() then treats as
	// an explicit false/empty rather than falling through to the real
	// default — silently disabling every slide's frame/visibility on the
	// first Customizer save after an update.
	$flat_defaults = array(
		'hero_autoplay_enabled'  => true,
		'hero_autoplay_duration' => 6000,
	);

	foreach ( dq_settings_schema() as $key => $type ) {
		$default       = isset( $flat_defaults[ $key ] ) ? $flat_defaults[ $key ] : '';
		$clean[ $key ] = isset( $input[ $key ] ) ? dq_sanitize_by_type( $input[ $key ], $type ) : $default;
	}

	$clean['hero_slides'] = array();
	$slide_schema         = dq_hero_slide_schema();
	$slide_defaults       = dq_hero_slide_defaults();

	for ( $i = 0; $i < 3; $i++ ) {
		$slide       = isset( $input['hero_slides'][ $i ] ) && is_array( $input['hero_slides'][ $i ] ) ? $input['hero_slides'][ $i ] : array();
		$clean_slide = array();

		foreach ( $slide_schema as $key => $type ) {
			$default             = isset( $slide_defaults[ $key ] ) ? $slide_defaults[ $key ] : '';
			$clean_slide[ $key ] = isset( $slide[ $key ] ) ? dq_sanitize_by_type( $slide[ $key ], $type ) : $default;
		}

		$clean['hero_slides'][] = $clean_slide;
	}

	return $clean;
}

