<?php
/**
 * Front page — assembles every section in the approved Stitch order.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main-content" class="academic-main">
	<?php
	get_template_part( 'template-parts/hero/hero-carousel' );
	get_template_part( 'template-parts/trust/value-pillars' );
	get_template_part( 'template-parts/legalitas/legality-banner' );
	get_template_part( 'template-parts/services/services-grid' );
	get_template_part( 'template-parts/diagnosis/diagnostic-section' );
	get_template_part( 'template-parts/integrity/integrity-manifesto' );
	get_template_part( 'template-parts/packages/packages-grid' );
	get_template_part( 'template-parts/mentors/mentors-grid' );
	get_template_part( 'template-parts/testimonials/testimonials-grid' );
	get_template_part( 'template-parts/faq/faq-accordion' );
	get_template_part( 'template-parts/cta/consultation-form' );
	get_template_part( 'template-parts/cta/final-cta' );
	?>
</main>

<?php
get_footer();
