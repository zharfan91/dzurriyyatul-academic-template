<?php
/**
 * Diagnostic clinic: "Kesulitan Penelitian Anda Ada di Tahap Mana?"
 * Fixed editorial content (the 4 struggle/solution pairs are core brand
 * messaging), exposed through a filter for developer-level customization.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$struggles = apply_filters( 'dq_diagnostic_struggles', array(
	array(
		'label'    => __( 'Masalah 01', 'dzurriyyatul-academic' ),
		'tone'     => 'rose',
		'icon'     => 'fa-regular fa-face-frown',
		'title'    => __( 'Judul Selalu Ditolak Dosen', 'dzurriyyatul-academic' ),
		'problem'  => __( 'Bingung mencari urgensi penelitian, tidak tahu cara menemukan novelty (kebaruan), dan fenomena lapangan dianggap terlalu umum.', 'dzurriyyatul-academic' ),
		'solution' => __( 'Bimbingan pemetaan 3 judul alternatif dengan analisis fenomena riil & gap riset jurnal 5 tahun terakhir.', 'dzurriyyatul-academic' ),
	),
	array(
		'label'    => __( 'Masalah 02', 'dzurriyyatul-academic' ),
		'tone'     => 'amber',
		'icon'     => 'fa-regular fa-circle-question',
		'title'    => __( 'Revisi Bab 2 & 3 Berulang', 'dzurriyyatul-academic' ),
		'problem'  => __( 'Kritik dosen: teori kurang relevan, referensi kadaluarsa, definisi operasional kabur, atau salah memilih teknik analisis data.', 'dzurriyyatul-academic' ),
		'solution' => __( 'Restrukturisasi kerangka konseptual, kurasi 20+ jurnal bereputasi, dan penyusunan metodologi runut.', 'dzurriyyatul-academic' ),
	),
	array(
		'label'    => __( 'Masalah 03', 'dzurriyyatul-academic' ),
		'tone'     => 'blue',
		'icon'     => 'fa-solid fa-chart-simple',
		'title'    => __( 'Buta Software Olah Data', 'dzurriyyatul-academic' ),
		'problem'  => __( 'Data sudah terkumpul tapi tidak paham mengoperasikan SPSS, SmartPLS, atau NVivo, serta takut hasil uji tidak signifikan.', 'dzurriyyatul-academic' ),
		'solution' => __( 'Private coaching olah data langsung dengan screen sharing hingga paham membaca tabel output Bab 4.', 'dzurriyyatul-academic' ),
	),
	array(
		'label'    => __( 'Masalah 04', 'dzurriyyatul-academic' ),
		'tone'     => 'purple',
		'icon'     => 'fa-solid fa-newspaper',
		'title'    => __( 'Jurnal Kerap Ditolak Editor', 'dzurriyyatul-academic' ),
		'problem'  => __( 'Submit jurnal SINTA/Scopus langsung reject tanpa review karena format tidak sesuai standar atau bahasa Inggris kaku.', 'dzurriyyatul-academic' ),
		'solution' => __( 'Bedah naskah mendalam, proofreading native academic, serta bimbingan memilih jurnal yang tepat scope.', 'dzurriyyatul-academic' ),
	),
) );

$wa_url = dq_whatsapp_url( __( 'Halo Admin, saya ingin jadwalkan Free Diagnosis Skripsi', 'dzurriyyatul-academic' ) );
?>
<section class="academic-diagnosis" id="solusi">
	<div class="academic-container">
		<div class="academic-diagnosis__header">
			<span class="academic-eyebrow academic-eyebrow--gold"><?php esc_html_e( 'Diagnosis Masalah Skripsi & Jurnal', 'dzurriyyatul-academic' ); ?></span>
			<h2 class="academic-heading-xl"><?php esc_html_e( 'Kesulitan Penelitian Anda Ada di Tahap Mana?', 'dzurriyyatul-academic' ); ?></h2>
			<p class="academic-body"><?php esc_html_e( 'Seringkali mahasiswa merasa buntu bukan karena kurang cerdas, melainkan belum menemukan peta jalan dan metode yang tepat. Temukan solusi Anda di sini.', 'dzurriyyatul-academic' ); ?></p>
		</div>

		<?php if ( ! empty( $struggles ) ) : ?>
			<div class="academic-diagnosis__grid">
				<?php foreach ( $struggles as $struggle ) : ?>
					<div class="academic-card academic-diagnosis-card">
						<div class="academic-diagnosis-card__top">
							<span class="academic-tag academic-tag--<?php echo esc_attr( $struggle['tone'] ); ?>"><?php echo esc_html( $struggle['label'] ); ?></span>
							<?php echo dq_icon( $struggle['icon'] ); ?>
						</div>
						<h3 class="academic-heading-sm"><?php echo esc_html( $struggle['title'] ); ?></h3>
						<p class="academic-diagnosis-card__problem"><?php echo esc_html( $struggle['problem'] ); ?></p>
						<div class="academic-diagnosis-card__solution">
							<p class="academic-diagnosis-card__solution-label"><?php echo dq_icon( 'fa-solid fa-lightbulb' ); ?> <?php esc_html_e( 'Solusi Kami:', 'dzurriyyatul-academic' ); ?></p>
							<?php echo esc_html( $struggle['solution'] ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( '' !== $wa_url ) : ?>
			<div class="academic-diagnosis__callout">
				<div class="academic-diagnosis__callout-info">
					<div class="academic-diagnosis__callout-icon"><?php echo dq_icon( 'fa-solid fa-stethoscope' ); ?></div>
					<div>
						<h3 class="academic-heading-sm"><?php esc_html_e( 'Tidak Yakin dengan Kendala Anda Saat Ini?', 'dzurriyyatul-academic' ); ?></h3>
						<p><?php esc_html_e( 'Diskusikan draf skripsi atau bab yang sedang Anda tulis langsung bersama Research Advisor kami.', 'dzurriyyatul-academic' ); ?></p>
					</div>
				</div>
				<a class="academic-btn academic-btn--gold academic-btn--pill" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Konsultasi Diagnosis Gratis', 'dzurriyyatul-academic' ); ?> <?php echo dq_icon( 'fa-solid fa-arrow-right' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>
