<?php
/**
 * Academic Integrity Manifesto — the core brand-differentiating section
 * (Master Prompt §29): explicitly NOT a joki-skripsi / ghostwriting /
 * plagiarism service. Settings-driven copy (Theme Settings → Integritas &
 * Etika); each string falls back to the original brand-critical wording
 * via dq_get_setting()'s default argument when unset.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$donts = array(
	dq_get_setting( 'integrity_dont_1', 'TIDAK menjual naskah jadi / joki instan' ),
	dq_get_setting( 'integrity_dont_2', 'TIDAK memalsukan data lapangan / fabrikasi' ),
);

$dos = array(
	dq_get_setting( 'integrity_do_1', 'Bimbingan logika berpikir dan pemahaman isi naskah' ),
	dq_get_setting( 'integrity_do_2', 'Edukasi metodologi ilmiah sesuai pedoman kampus' ),
);
?>
<section class="academic-integrity" id="integritas">
	<div class="academic-container">
		<div class="academic-integrity__panel">
			<div class="academic-integrity__main">
				<span class="academic-eyebrow academic-eyebrow--green"><?php echo dq_icon( 'fa-solid fa-shield-cat' ); ?> <?php echo esc_html( dq_get_setting( 'integrity_eyebrow_text', 'Komitmen Moral & Islam' ) ); ?></span>
				<h2 class="academic-heading-xl"><?php echo esc_html( dq_get_setting( 'integrity_heading', 'Pendampingan Akademik yang Menjunjung Tinggi Integritas Moral & Nilai-Nilai Islam' ) ); ?></h2>

				<div class="academic-integrity__notice">
					<strong><?php esc_html_e( 'PENTING:', 'dzurriyyatul-academic' ); ?></strong>
					<?php echo esc_html( dq_get_setting( 'integrity_notice_text', 'DZURRIYYATUL QUR\'AN ACADEMIC BUKAN JASA JOKI SKRIPSI. Kami menolak keras praktik pembuatan skripsi/karya ilmiah secara instan yang melanggar hukum, etika keilmuan, dan syariat Islam.' ) ); ?>
				</div>

				<p class="academic-body"><?php echo esc_html( dq_get_setting( 'integrity_body', 'Tujuan kami adalah memberdayakan mahasiswa agar memahami substansi penelitian mereka sendiri. Kami membimbing logika berpikir, mengajarkan teknik analisis, dan memandu penulisan ilmiah sehingga saat sidang munaqasyah/skripsi, Anda mampu mempertahankan argumen di depan dewan penguji dengan penuh percaya diri dan barakah.' ) ); ?></p>

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
				<h3 class="academic-heading-sm"><?php echo esc_html( dq_get_setting( 'integrity_pledge_heading', 'Ijazah Berkah & Terhormat' ) ); ?></h3>
				<p><?php echo esc_html( dq_get_setting( 'integrity_pledge_quote', '"Menuntut ilmu dengan cara yang jujur adalah bagian dari ibadah dan pondasi rezeki yang halal di masa depan."' ) ); ?></p>
				<div class="academic-integrity__pledge-badge">
					<?php echo dq_icon( 'fa-solid fa-certificate' ); ?> <?php echo esc_html( dq_get_setting( 'integrity_pledge_badge', 'Terikat Pakta Integritas Peneliti' ) ); ?>
				</div>
			</div>
		</div>
	</div>
</section>
