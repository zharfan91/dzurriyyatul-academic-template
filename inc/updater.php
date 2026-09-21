<?php
/**
 * GitHub-based automatic theme updates.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once DQ_THEME_DIR . '/inc/vendor/plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5p7\PucFactory;

$dq_update_checker = PucFactory::buildUpdateChecker(
	'https://github.com/zharfan91/dzurriyyatul-academic-template/',
	get_stylesheet_directory() . '/style.css',
	get_stylesheet()
);
$dq_update_checker->setBranch( 'main' );

/**
 * Let WordPress's background updater apply new releases automatically,
 * instead of waiting for someone to click "Update now" in wp-admin.
 */
add_filter(
	'auto_update_theme',
	function ( $update, $item ) {
		if ( isset( $item->theme ) && get_stylesheet() === $item->theme ) {
			return true;
		}
		return $update;
	},
	10,
	2
);
