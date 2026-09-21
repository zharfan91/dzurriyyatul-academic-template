<?php
/**
 * Academic Integrity Manifesto — the core brand-differentiating section
 * (Master Prompt §29): explicitly NOT a joki-skripsi / ghostwriting /
 * plagiarism service. Fixed, brand-critical legal-tone copy; exposed via
 * filter for a maintainer to adjust wording without editing markup.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$donts = apply_filters( 'dq_integrity_donts', array(
	__( 'TIDAK menjual naskah jadi / joki instan', 'dzurriyyatul-academic' ),
	__( 'TIDAK memalsukan data lapangan / fabrikasi', 'dzurriyyatul-academic' ),
) );

$dos = apply_filters( 'dq_integrity_dos', array(
	__( 'Bimbingan logika berpikir dan pemahaman isi naskah', 'dzurriyyatul-academic' ),
	__( 'Edukasi metodologi ilmiah sesuai pedoman kampus', 'dzurriyyatul-academic' ),
) );
?>
<section class="academic-integrity" id="integritas">
	<div class="academic-container">
		<div class="academic-integrity__panel">
			<div class="academic-integrity__main">
				<span class="academic-eyebrow academic-eyebrow--green"><?php echo dq_icon( 'fa-solid fa-shield-cat' ); ?> <?php esc_html_e( 'Komitmen Moral & Islam', 'dzurriyyatul-academic' ); ?></span>
				<h2 class="academic-heading-xl"><?php esc_html_e( 'Pendampingan Akademik yang Menjunjung Tinggi Integritas Moral & Nilai-Nilai Islam', 'dzurriyyatul-academic' ); ?></h2>

				<div class="academic-integrity__notice">
					<strong><?php esc_html_e( 'PENTING:', 'dzurriyyatul-academic' ); ?></strong>
					<?php esc_html_e( 'DZURRIYYATUL QUR\'AN ACADEMIC BUKAN JASA JOKI SKRIPSI. Kami menolak keras praktik pembuatan skripsi/karya ilmiah secara instan yang melanggar hukum, etika keilmuan, dan syariat Islam.', 'dzurriyyatul-academic' ); ?>
				</div>

				<p class="academic-body"><?php esc_html_e( 'Tujuan kami adalah memberdayakan mahasiswa agar memahami substansi penelitian mereka sendiri. Kami membimbing logika berpikir, mengajarkan teknik analisis, dan memandu penulisan ilmiah sehingga saat sidang munaqasyah/skripsi, Anda mampu mempertahankan argumen di depan dewan penguji dengan penuh percaya diri dan barakah.', 'dzurriyyatul-academic' ); ?></p>

				<div class="academic-integrity__checklist">
					<?php foreach ( $donts as $item ) : ?>
						<div class="academic-integrity__row academic-integrity__row--no"><?php echo dq_icon( 'fa-solid fa-circle-xmark' ); ?> <span><?php echo esc_html( $item ); ?></span></div>
					<?php endforeach; ?>
					<?php foreach ( $dos as $item ) : ?>
						<div class="academic-integrity__row academic-integrity__row--yes"><?php echo dq_icon( 'fa-solid fa-circle-check' ); ?> <span><?php echo esc_html( $item ); ?></span></div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="academic-integrity__pledge">
				<div class="academic-integrity__pledge-icon"><?php echo dq_icon( 'fa-solid fa-scale-unbalanced-flip' ); ?></div>
				<h3 class="academic-heading-sm"><?php esc_html_e( 'Ijazah Berkah & Terhormat', 'dzurriyyatul-academic' ); ?></h3>
				<p><?php esc_html_e( '"Menuntut ilmu dengan cara yang jujur adalah bagian dari ibadah dan pondasi rezeki yang halal di masa depan."', 'dzurriyyatul-academic' ); ?></p>
				<div class="academic-integrity__pledge-badge">
					<?php echo dq_icon( 'fa-solid fa-certificate' ); ?> <?php esc_html_e( 'Terikat Pakta Integritas Peneliti', 'dzurriyyatul-academic' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>
