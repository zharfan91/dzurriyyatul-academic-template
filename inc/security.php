<?php
/**
 * Security hardening (Master Prompt §41).
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove version-leaking / low-value meta output.
 */
function dq_harden_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
}
add_action( 'init', 'dq_harden_head' );

/**
 * Strip the generator meta tag / version query strings from asset URLs.
 */
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Disable XML-RPC — not needed by this site and a common attack surface.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Disable in-dashboard file editing for themes/plugins.
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Send a conservative set of security headers on the front end.
 */
function dq_security_headers() {
	if ( headers_sent() ) {
		return;
	}

	header( 'X-Content-Type-Options: nosniff' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
}
add_action( 'send_headers', 'dq_security_headers' );

/**
 * Generic capability + nonce guard used by every admin-side save handler
 * in this theme (meta boxes, settings page). Dies with a translated
 * message on failure so a forged/absent request never silently succeeds.
 *
 * @param string $nonce_field Name of the $_POST field holding the nonce.
 * @param string $action      Nonce action string.
 * @param string $capability  Capability required to proceed.
 */
function dq_verify_admin_request( $nonce_field, $action, $capability = 'edit_posts' ) {
	if ( ! isset( $_POST[ $nonce_field ] ) ) {
		return false;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST[ $nonce_field ] ) );

	if ( ! wp_verify_nonce( $nonce, $action ) ) {
		return false;
	}

	if ( ! current_user_can( $capability ) ) {
		return false;
	}

	return true;
}
