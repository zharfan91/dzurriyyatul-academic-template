<?php
/**
 * Institutional legality & trust banner. All facts come from Theme
 * Settings — no legal claim is ever invented (§30).
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$entity_name    = dq_get_setting( 'legal_entity_name' );
$ahu_number     = dq_get_setting( 'legal_ahu_number' );
$sk_date        = dq_get_setting( 'legal_sk_date' );
$about_page_url = dq_get_setting( 'about_page_url' );

if ( '' === $entity_name && '' === $ahu_number ) {
	return;
}
?>
<section class="academic-legality" id="legalitas">
	<div class="academic-container academic-legality__row">
		<div class="academic-legality__info">
			<div class="academic-legality__icon"><?php echo dq_icon( 'fa-solid fa-building-columns' ); ?></div>
			<div>
				<p class="academic-legality__eyebrow"><?php esc_html_e( 'Legalitas & Kepercayaan Lembaga', 'dzurriyyatul-academic' ); ?></p>
				<p class="academic-legality__text">
					<?php if ( '' !== $ahu_number ) : ?>
						<?php esc_html_e( 'Terdaftar Resmi Kemenkumham RI:', 'dzurriyyatul-academic' ); ?>
						<span class="academic-legality__number"><?php echo esc_html( $ahu_number ); ?></span>
						<?php if ( '' !== $sk_date ) : ?>
							(<?php echo esc_html( $sk_date ); ?>)
						<?php endif; ?>
					<?php else : ?>
						<?php echo esc_html( $entity_name ); ?>
					<?php endif; ?>
				</p>
			</div>
		</div>
		<div class="academic-legality__meta">
			<?php if ( '' !== $entity_name ) : ?>
				<span class="academic-legality__under"><?php printf( esc_html__( 'Di bawah naungan %s', 'dzurriyyatul-academic' ), esc_html( $entity_name ) ); ?></span>
			<?php endif; ?>
			<?php if ( '' !== $about_page_url ) : ?>
				<a class="academic-legality__cta" href="<?php echo esc_url( $about_page_url ); ?>">
					<span><?php esc_html_e( 'Lihat Legalitas', 'dzurriyyatul-academic' ); ?></span>
					<?php echo dq_icon( 'fa-solid fa-arrow-up-right-from-square' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
