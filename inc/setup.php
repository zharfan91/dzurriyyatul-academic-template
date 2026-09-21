<?php
/**
 * Core theme setup: supports, menus, image sizes, sidebars.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup.
 */
function dq_setup() {
	load_theme_textdomain( 'dzurriyyatul-academic', DQ_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Editorial hero visuals need a wide crop; keep true production sizes only.
	add_image_size( 'dq-hero', 960, 720, true );
	add_image_size( 'dq-card', 640, 480, true );
	add_image_size( 'dq-avatar', 240, 240, true );

	register_nav_menus( array(
		'primary' => __( 'Navigasi Utama', 'dzurriyyatul-academic' ),
		'footer'  => __( 'Navigasi Footer', 'dzurriyyatul-academic' ),
	) );
}
add_action( 'after_setup_theme', 'dq_setup' );

/**
 * Sensible excerpt length/marker for the article system.
 */
function dq_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'dq_excerpt_length' );

function dq_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'dq_excerpt_more' );

/**
 * Register the single footer widget area used for the "Layanan Akademik"
 * column fallback when no menu is assigned yet.
 */
function dq_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer Widget', 'dzurriyyatul-academic' ),
		'id'            => 'footer-widget',
		'description'   => __( 'Widget opsional yang tampil di kolom footer.', 'dzurriyyatul-academic' ),
		'before_widget' => '<div class="academic-footer__widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="academic-footer__heading">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'dq_widgets_init' );

/**
 * Flush rewrite rules once when the theme is activated so the `service`
 * and `package` CPT archive/single permalinks work immediately, without
 * requiring the admin to manually visit Settings → Permalinks first.
 */
function dq_flush_rewrites_on_activation() {
	dq_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'dq_flush_rewrites_on_activation' );
