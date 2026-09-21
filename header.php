<?php
/**
 * Header: doctype, <head>, skip link, announcement bar, navigation.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="academic-skip-link" href="#main-content"><?php esc_html_e( 'Lewati ke konten utama', 'dzurriyyatul-academic' ); ?></a>

<?php
get_template_part( 'template-parts/header/announcement-bar' );
get_template_part( 'template-parts/header/navigation' );
