<?php
/**
 * Footer wrapper: renders the site footer template part and wp_footer().
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part( 'template-parts/footer/site-footer' );
?>

<?php wp_footer(); ?>
</body>
</html>
