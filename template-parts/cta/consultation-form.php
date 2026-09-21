<?php
/**
 * Consultation request form (§51). An alternative, on-site channel to the
 * WhatsApp-only funnel used throughout the rest of the design.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="academic-consultation" id="konsultasi">
	<div class="academic-container academic-content-narrow">
		<div class="academic-consultation__header">
			<span class="academic-eyebrow academic-eyebrow--green"><?php esc_html_e( 'Ajukan Konsultasi', 'dzurriyyatul-academic' ); ?></span>
			<h2 class="academic-heading-xl"><?php esc_html_e( 'Ceritakan Kebutuhan Riset Anda', 'dzurriyyatul-academic' ); ?></h2>
			<p class="academic-body"><?php esc_html_e( 'Isi formulir berikut dan tim kami akan menghubungi Anda melalui WhatsApp atau email untuk menjadwalkan sesi konsultasi awal.', 'dzurriyyatul-academic' ); ?></p>
		</div>

		<?php
		// The security nonce is supplied via wp_localize_script() (see
		// inc/enqueue.php) and sent by js/main.js on submit — nothing
		// nonce-related needs to live in this markup.
		?>
		<form id="dqConsultationForm" class="academic-form" novalidate>
			<div class="academic-form__hp" aria-hidden="true">
				<label for="dq_website"><?php esc_html_e( 'Website', 'dzurriyyatul-academic' ); ?></label>
				<input type="text" id="dq_website" name="dq_website" tabindex="-1" autocomplete="off" />
			</div>

			<div class="academic-form__grid">
				<div class="academic-form__field">
					<label for="dq-name"><?php esc_html_e( 'Nama Lengkap', 'dzurriyyatul-academic' ); ?> <span aria-hidden="true">*</span></label>
					<input type="text" id="dq-name" name="name" required />
				</div>
				<div class="academic-form__field">
					<label for="dq-whatsapp"><?php esc_html_e( 'Nomor WhatsApp', 'dzurriyyatul-academic' ); ?> <span aria-hidden="true">*</span></label>
					<input type="text" id="dq-whatsapp" name="whatsapp" required />
				</div>
				<div class="academic-form__field">
					<label for="dq-email"><?php esc_html_e( 'Email', 'dzurriyyatul-academic' ); ?></label>
					<input type="email" id="dq-email" name="email" />
				</div>
				<div class="academic-form__field">
					<label for="dq-university"><?php esc_html_e( 'Universitas', 'dzurriyyatul-academic' ); ?></label>
					<input type="text" id="dq-university" name="university" />
				</div>
				<div class="academic-form__field">
					<label for="dq-program"><?php esc_html_e( 'Program Studi', 'dzurriyyatul-academic' ); ?></label>
					<input type="text" id="dq-program" name="program" />
				</div>
				<div class="academic-form__field">
					<label for="dq-level"><?php esc_html_e( 'Jenjang', 'dzurriyyatul-academic' ); ?></label>
					<select id="dq-level" name="level">
						<option value=""><?php esc_html_e( 'Pilih Jenjang', 'dzurriyyatul-academic' ); ?></option>
						<option value="D3/D4"><?php esc_html_e( 'Diploma (D3/D4)', 'dzurriyyatul-academic' ); ?></option>
						<option value="S1"><?php esc_html_e( 'Sarjana (S1)', 'dzurriyyatul-academic' ); ?></option>
						<option value="S2"><?php esc_html_e( 'Magister (S2)', 'dzurriyyatul-academic' ); ?></option>
						<option value="S3"><?php esc_html_e( 'Doktor (S3)', 'dzurriyyatul-academic' ); ?></option>
					</select>
				</div>
				<div class="academic-form__field">
					<label for="dq-stage"><?php esc_html_e( 'Tahap Penelitian', 'dzurriyyatul-academic' ); ?></label>
					<input type="text" id="dq-stage" name="research_stage" placeholder="<?php esc_attr_e( 'Contoh: Bab 3, Seminar Proposal', 'dzurriyyatul-academic' ); ?>" />
				</div>
				<div class="academic-form__field">
					<label for="dq-topic"><?php esc_html_e( 'Topik Penelitian', 'dzurriyyatul-academic' ); ?></label>
					<input type="text" id="dq-topic" name="research_topic" />
				</div>
				<div class="academic-form__field academic-form__field--full">
					<label for="dq-service"><?php esc_html_e( 'Layanan yang Dibutuhkan', 'dzurriyyatul-academic' ); ?></label>
					<input type="text" id="dq-service" name="service_needed" placeholder="<?php esc_attr_e( 'Contoh: Analisis Data SPSS', 'dzurriyyatul-academic' ); ?>" />
				</div>
				<div class="academic-form__field academic-form__field--full">
					<label for="dq-problem"><?php esc_html_e( 'Masalah Utama', 'dzurriyyatul-academic' ); ?></label>
					<textarea id="dq-problem" name="main_problem" rows="3"></textarea>
				</div>
				<div class="academic-form__field academic-form__field--full">
					<label for="dq-message"><?php esc_html_e( 'Pesan Tambahan', 'dzurriyyatul-academic' ); ?></label>
					<textarea id="dq-message" name="message" rows="3"></textarea>
				</div>
			</div>

			<div class="academic-form__status" role="status" aria-live="polite" id="dqFormStatus"></div>

			<button type="submit" class="academic-btn academic-btn--primary academic-btn--pill academic-btn--lg">
				<?php esc_html_e( 'Kirim Permintaan Konsultasi', 'dzurriyyatul-academic' ); ?>
			</button>
			<p class="academic-form__privacy"><?php echo dq_icon( 'fa-solid fa-lock' ); ?> <?php esc_html_e( 'Data Anda hanya digunakan untuk keperluan komunikasi konsultasi dan tidak dibagikan ke pihak ketiga.', 'dzurriyyatul-academic' ); ?></p>
		</form>
	</div>
</section>
