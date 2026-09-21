<?php
/**
 * Packages/pricing grid — dynamic WP_Query over the `package` CPT (§31).
 * Pricing is never hard-coded; admins fully control it from wp-admin.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$packages_query = new WP_Query( array(
	'post_type'      => 'package',
	'post_status'    => 'publish',
	'posts_per_page' => 6,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

$wa_url = dq_whatsapp_url();

// No published packages yet: skip the section for visitors entirely rather
// than showing an admin-facing "add content" notice on the public site.
if ( ! $packages_query->have_posts() ) {
	if ( current_user_can( 'edit_posts' ) ) {
		echo '<section class="academic-packages academic-packages--empty" id="paket"><div class="academic-container">';
		dq_empty_state( __( 'Belum ada paket yang dipublikasikan. Tambahkan melalui menu Paket di wp-admin.', 'dzurriyyatul-academic' ) );
		echo '</div></section>';
	}
	return;
}
?>
<section class="academic-packages" id="paket">
	<div class="academic-container">
		<div class="academic-packages__header">
			<span class="academic-eyebrow academic-eyebrow--green"><?php esc_html_e( 'Transparan & Terjangkau', 'dzurriyyatul-academic' ); ?></span>
			<h2 class="academic-heading-xl"><?php esc_html_e( 'Pilihan Paket Pendampingan Akademik', 'dzurriyyatul-academic' ); ?></h2>
			<p class="academic-body"><?php esc_html_e( 'Investasi pendidikan dengan ruang lingkup bimbingan yang terdefinisi transparan tanpa biaya tersembunyi.', 'dzurriyyatul-academic' ); ?></p>
		</div>

			<div class="academic-packages__grid">
				<?php while ( $packages_query->have_posts() ) : $packages_query->the_post(); ?>
					<?php
					$price       = get_post_meta( get_the_ID(), '_dq_price', true );
					$price_label = get_post_meta( get_the_ID(), '_dq_price_label', true );
					$billing     = get_post_meta( get_the_ID(), '_dq_billing_type', true );
					$features    = dq_lines_to_array( get_post_meta( get_the_ID(), '_dq_features', true ) );
					$featured    = '1' === get_post_meta( get_the_ID(), '_dq_featured', true );
					$badge       = get_post_meta( get_the_ID(), '_dq_badge_text', true );
					$cta_text    = get_post_meta( get_the_ID(), '_dq_cta_text', true );
					$cta_url     = get_post_meta( get_the_ID(), '_dq_cta_url', true );
					if ( '' === $cta_url ) {
						$cta_url = dq_whatsapp_url(
							sprintf(
								/* translators: %s: package title */
								__( 'Halo Admin, saya mau ambil %s', 'dzurriyyatul-academic' ),
								get_the_title()
							)
						);
					}
					?>
					<article class="academic-card academic-package-card<?php echo $featured ? ' is-featured' : ''; ?>">
						<?php if ( $featured && '' !== $badge ) : ?>
							<div class="academic-package-card__ribbon"><?php echo esc_html( $badge ); ?></div>
						<?php endif; ?>

						<div class="academic-package-card__head">
							<h3 class="academic-heading-sm"><?php the_title(); ?></h3>
							<?php if ( '' !== $billing ) : ?>
								<span class="academic-chip"><?php echo esc_html( $billing ); ?></span>
							<?php endif; ?>
						</div>

						<?php if ( has_excerpt() || get_the_content() ) : ?>
							<p class="academic-package-card__desc"><?php echo esc_html( wp_trim_words( get_the_excerpt() ? get_the_excerpt() : get_the_content(), 28 ) ); ?></p>
						<?php endif; ?>

						<?php if ( '' !== $price ) : ?>
							<div class="academic-package-card__price">
								<span class="academic-package-card__price-eyebrow"><?php esc_html_e( 'Mulai dari', 'dzurriyyatul-academic' ); ?></span>
								<div class="academic-package-card__price-row">
									<span class="academic-package-card__price-value"><?php echo esc_html( 'Rp ' . number_format_i18n( (float) $price, 0 ) ); ?></span>
									<?php if ( '' !== $price_label ) : ?>
										<span class="academic-package-card__price-unit"><?php echo esc_html( $price_label ); ?></span>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $features ) ) : ?>
							<ul class="academic-package-card__features">
								<?php foreach ( $features as $feature ) : ?>
									<li><?php echo dq_icon( 'fa-solid fa-check' ); ?> <span><?php echo esc_html( $feature ); ?></span></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php if ( '' !== $cta_url ) : ?>
							<a class="academic-btn academic-btn--block<?php echo $featured ? ' academic-btn--primary' : ' academic-btn--outline'; ?>" href="<?php echo esc_url( $cta_url ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo esc_html( '' !== $cta_text ? $cta_text : __( 'Pilih Paket Ini', 'dzurriyyatul-academic' ) ); ?>
							</a>
						<?php endif; ?>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>

		<?php if ( '' !== $wa_url ) : ?>
			<p class="academic-packages__note">
				<?php esc_html_e( 'Butuh bimbingan khusus untuk Tesis S2, Disertasi S3, atau Publikasi Scopus Q1-Q4?', 'dzurriyyatul-academic' ); ?>
				<a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Diskusikan kebutuhan kustom bersama tim kami →', 'dzurriyyatul-academic' ); ?></a>
			</p>
		<?php endif; ?>
	</div>
</section>
