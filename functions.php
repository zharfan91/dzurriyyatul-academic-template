<?php
/**
 * Theme bootstrap.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Disallow direct access.
}

define( 'DQ_THEME_VERSION', '1.1.1' );
define( 'DQ_THEME_DIR', get_template_directory() );
define( 'DQ_THEME_URI', get_template_directory_uri() );

$dq_includes = array(
	'/inc/helpers.php',
	'/inc/setup.php',
	'/inc/enqueue.php',
	'/inc/security.php',
	'/inc/seo.php',
	'/inc/template-functions.php',
	'/inc/post-types.php',
	'/inc/settings.php',
	'/inc/consultation-form.php',
);

foreach ( $dq_includes as $dq_file ) {
	$dq_path = DQ_THEME_DIR . $dq_file;
	if ( is_readable( $dq_path ) ) {
		require_once $dq_path;
	}
}
unset( $dq_includes, $dq_file, $dq_path );
