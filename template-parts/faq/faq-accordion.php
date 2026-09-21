<?php
/**
 * FAQ accordion — dynamic WP_Query over the `faq` CPT (§21, §34).
 *
 * The approved Stitch source renders every FAQ item permanently expanded
 * (no toggle script at all). We implement a real, keyboard-operable,
 * ARIA-correct accordion here instead — an accessibility improvement the
 * master prompt explicitly requires (§21 "accessible accordion", §34,
 * §42), while keeping the identical card visuals or the collapsed state.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$faq_query = new WP_Query( array(
	'post_type'      => 'faq',
	'post_status'    => 'publish',
	'posts_per_page' => 20,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

if ( ! $faq_query->have_posts() ) {
	if ( current_user_can( 'edit_posts' ) ) {
		echo '<section class="academic-faq" id="faq"><div class="academic-container">';
		dq_empty_state( __( 'Belum ada FAQ. Tambahkan melalui menu FAQ di wp-admin.', 'dzurriyyatul-academic' ) );
		echo '</div></section>';
	}
	return;
}
?>
<section class="academic-faq" id="faq">
	<div class="academic-container academic-faq__inner">
		<div class="academic-faq__header">
			<span class="academic-eyebrow academic-eyebrow--green"><?php esc_html_e( 'Pertanyaan Umum', 'dzurriyyatul-academic' ); ?></span>
			<h2 class="academic-heading-xl"><?php esc_html_e( 'Hal yang Kerap Ditanyakan Mahasiswa', 'dzurriyyatul-academic' ); ?></h2>
		</div>

		<div class="academic-accordion" data-academic-accordion>
			<?php $i = 0; while ( $faq_query->have_posts() ) : $faq_query->the_post(); $i++; ?>
				<?php $panel_id = 'faq-panel-' . get_the_ID(); $button_id = 'faq-button-' . get_the_ID(); ?>
				<div class="academic-accordion__item">
					<h3 class="academic-accordion__heading">
						<button
							type="button"
							class="academic-accordion__trigger"
							id="<?php echo esc_attr( $button_id ); ?>"
							aria-expanded="<?php echo 1 === $i ? 'true' : 'false'; ?>"
							aria-controls="<?php echo esc_attr( $panel_id ); ?>"
						>
							<span><?php the_title(); ?></span>
							<?php echo dq_icon( 'fa-solid fa-chevron-down' ); ?>
						</button>
					</h3>
					<div
						id="<?php echo esc_attr( $panel_id ); ?>"
						class="academic-accordion__panel"
						role="region"
						aria-labelledby="<?php echo esc_attr( $button_id ); ?>"
						<?php echo 1 === $i ? '' : 'hidden'; ?>
					>
						<div class="academic-accordion__content">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
