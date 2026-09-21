<?php
/**
 * Final call-to-action band.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wa_url = dq_whatsapp_url( __( 'Halo Admin Dzurriyyatul Quran Academic, saya ingin jadwalkan bimbingan', 'dzurriyyatul-academic' ) );
?>
<section class="academic-cta">
	<div class="academic-cta__pattern" aria-hidden="true"></div>
	<div class="academic-container academic-cta__inner">
		<div class="academic-cta__icon"><?php echo dq_icon( 'fa-solid fa-graduation-cap' ); ?></div>
		<h2 class="academic-heading-xl academic-cta__heading"><?php esc_html_e( 'Selesaikan Skripsi & Publikasi Riset Anda Bersama Mentor Terbaik', 'dzurriyyatul-academic' ); ?></h2>
		<p class="academic-cta__desc"><?php esc_html_e( 'Jangan biarkan skripsi dan revisi tertunda menghambat masa depan Anda. Dapatkan pendampingan terstruktur, ilmiah, dan berintegritas sekarang.', 'dzurriyyatul-academic' ); ?></p>
		<div class="academic-cta__actions">
			<?php if ( '' !== $wa_url ) : ?>
				<a class="academic-btn academic-btn--gold academic-btn--pill academic-btn--lg" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php echo dq_icon( 'fa-brands fa-whatsapp' ); ?> <span><?php esc_html_e( 'Mulai Bimbingan Sekarang', 'dzurriyyatul-academic' ); ?></span>
				</a>
			<?php endif; ?>
			<a class="academic-btn academic-btn--ghost-inverse academic-btn--pill academic-btn--lg" href="#layanan">
				<span><?php esc_html_e( 'Pelajari Seluruh Layanan', 'dzurriyyatul-academic' ); ?></span>
			</a>
		</div>
		<p class="academic-cta__note"><?php echo dq_icon( 'fa-solid fa-lock' ); ?> <?php esc_html_e( 'Konsultasi awal ramah & rahasia. Data riset dan privasi mahasiswa dilindungi penuh.', 'dzurriyyatul-academic' ); ?></p>
	</div>
</section>
