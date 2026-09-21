<?php
/**
 * 5 value-pillars strip. Editorial brand copy — kept as filterable, versioned
 * content (not a CPT) since it is core brand identity, not a business record
 * an admin needs to add/remove entries from day to day.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filterable pillar list — a developer can override via functions.php of a
 * child theme without touching this template part.
 */
$pillars = apply_filters( 'dq_value_pillars', array(
	array(
		'icon'  => 'fa-solid fa-user-graduate',
		'title' => __( 'Profesional', 'dzurriyyatul-academic' ),
		'desc'  => __( 'Didukung mentor magister & doktor berpengalaman.', 'dzurriyyatul-academic' ),
	),
	array(
		'icon'  => 'fa-solid fa-book-open',
		'title' => __( 'Edukatif', 'dzurriyyatul-academic' ),
		'desc'  => __( 'Membantu memahami proses metodologi, bukan sekadar hasil.', 'dzurriyyatul-academic' ),
	),
	array(
		'icon'  => 'fa-solid fa-handshake-simple',
		'title' => __( 'Transparan', 'dzurriyyatul-academic' ),
		'desc'  => __( 'Biaya dan ruang lingkup bimbingan terinci jelas sejak awal.', 'dzurriyyatul-academic' ),
	),
	array(
		'icon'  => 'fa-solid fa-laptop-file',
		'title' => __( 'Fleksibel', 'dzurriyyatul-academic' ),
		'desc'  => __( 'Konsultasi interaktif melalui Zoom, Google Meet & tatap muka.', 'dzurriyyatul-academic' ),
	),
	array(
		'icon'  => 'fa-solid fa-scale-balanced',
		'title' => __( 'Berintegritas', 'dzurriyyatul-academic' ),
		'desc'  => __( 'Menolak keras plagiarisme, joki skripsi, dan fabrikasi data.', 'dzurriyyatul-academic' ),
	),
) );

if ( empty( $pillars ) ) {
	return;
}
?>
<section class="academic-pillars">
	<div class="academic-container academic-pillars__grid">
		<?php foreach ( $pillars as $pillar ) : ?>
			<div class="academic-pillars__item">
				<div class="academic-pillars__icon"><?php echo dq_icon( $pillar['icon'] ); ?></div>
				<div>
					<h4 class="academic-pillars__title"><?php echo esc_html( $pillar['title'] ); ?></h4>
					<p class="academic-pillars__desc"><?php echo esc_html( $pillar['desc'] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
