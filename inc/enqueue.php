<?php
/**
 * Asset registration: fonts, icons, theme CSS/JS.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front-end styles and scripts.
 *
 * Only Playfair Display + Plus Jakarta Sans are loaded (the source design
 * also imported Public Sans and Material Symbols Outlined, neither of
 * which is ever referenced in the markup — dropped per Master Prompt §13/§40).
 * Font Awesome is loaded as a stylesheet only (no JS kit) so glyphs stay
 * available without shipping its autoloader.
 */
function dq_enqueue_assets() {
	wp_enqueue_style(
		'dq-google-fonts',
		'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'dq-font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
		array(),
		'6.5.1'
	);

	wp_enqueue_style( 'dq-tokens', DQ_THEME_URI . '/assets/css/tokens.css', array(), DQ_THEME_VERSION );
	wp_enqueue_style( 'dq-base', DQ_THEME_URI . '/assets/css/base.css', array( 'dq-tokens' ), DQ_THEME_VERSION );
	wp_enqueue_style( 'dq-components', DQ_THEME_URI . '/assets/css/components.css', array( 'dq-base' ), DQ_THEME_VERSION );
	wp_enqueue_style( 'dq-sections', DQ_THEME_URI . '/assets/css/sections.css', array( 'dq-components' ), DQ_THEME_VERSION );
	wp_enqueue_style( 'dq-responsive', DQ_THEME_URI . '/assets/css/responsive.css', array( 'dq-sections' ), DQ_THEME_VERSION );

	wp_enqueue_script( 'dq-navigation', DQ_THEME_URI . '/js/navigation.js', array(), DQ_THEME_VERSION, true );
	wp_enqueue_script( 'dq-hero', DQ_THEME_URI . '/js/hero.js', array(), DQ_THEME_VERSION, true );
	wp_enqueue_script( 'dq-faq', DQ_THEME_URI . '/js/faq.js', array(), DQ_THEME_VERSION, true );
	wp_enqueue_script( 'dq-main', DQ_THEME_URI . '/js/main.js', array(), DQ_THEME_VERSION, true );

	wp_localize_script( 'dq-main', 'dqSettings', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'dq_consultation_form' ),
	) );

	if ( is_singular() && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dq_enqueue_assets' );

/**
 * All theme <script> tags are safe to defer: none of them do document.write
 * or rely on being parsed before DOMContentLoaded.
 *
 * @param string $tag    The <script> tag markup.
 * @param string $handle Registered script handle.
 * @return string
 */
function dq_defer_scripts( $tag, $handle ) {
	$deferred = array( 'dq-navigation', 'dq-hero', 'dq-faq', 'dq-main' );

	if ( in_array( $handle, $deferred, true ) && false === strpos( $tag, 'defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'dq_defer_scripts', 10, 2 );

/**
 * Preconnect to font/icon origins for a faster first paint.
 */
function dq_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = 'https://cdnjs.cloudflare.com';
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'dq_resource_hints', 10, 2 );
