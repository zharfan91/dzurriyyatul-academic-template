<?php
/**
 * Top announcement bar: legal affiliation line, hours, WhatsApp hotline.
 * All business data comes from Theme Settings — never hard-coded (§24).
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$entity_name = dq_get_setting( 'legal_entity_name' );
$ahu_number  = dq_get_setting( 'legal_ahu_number' );
$hours       = dq_get_setting( 'operating_hours' );
$phone       = dq_get_setting( 'contact_phone' );
$wa_url      = dq_whatsapp_url();

if ( '' === $entity_name && '' === $hours && '' === $wa_url ) {
	return; // Nothing configured yet — do not render an empty bar.
}
?>
<div class="academic-announcement">
	<div class="academic-container academic-announcement__row">
		<div class="academic-announcement__legal">
			<?php if ( '' !== $entity_name ) : ?>
				<span class="academic-announcement__badge">
					<?php echo dq_icon( 'fa-solid fa-certificate' ); ?>
					<?php
					echo esc_html( $entity_name );
					if ( '' !== $ahu_number ) {
						echo esc_html( ' • ' . $ahu_number );
					}
					?>
				</span>
			<?php endif; ?>
			<span class="academic-announcement__tagline"><?php esc_html_e( 'Bimbingan Objektif, Edukatif & Berintegritas', 'dzurriyyatul-academic' ); ?></span>
		</div>
		<div class="academic-announcement__contact">
			<?php if ( '' !== $hours ) : ?>
				<span><?php echo dq_icon( 'fa-regular fa-clock' ); ?> <?php echo esc_html( $hours ); ?></span>
			<?php endif; ?>
			<?php if ( '' !== $wa_url ) : ?>
				<a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" class="academic-announcement__whatsapp">
					<?php echo dq_icon( 'fa-brands fa-whatsapp' ); ?>
					<?php
					printf(
						/* translators: %s: contact phone number */
						esc_html__( 'Hotline Bimbingan: %s', 'dzurriyyatul-academic' ),
						esc_html( '' !== $phone ? $phone : '' )
					);
					?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>
