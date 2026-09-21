<?php
/**
 * Single `package` template.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$price       = get_post_meta( get_the_ID(), '_dq_price', true );
	$price_label = get_post_meta( get_the_ID(), '_dq_price_label', true );
	$billing     = get_post_meta( get_the_ID(), '_dq_billing_type', true );
	$features    = dq_lines_to_array( get_post_meta( get_the_ID(), '_dq_features', true ) );
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
	<main id="main-content" class="academic-main academic-main--page">
		<div class="academic-container academic-content-narrow">
			<?php dq_breadcrumbs(); ?>

			<article <?php post_class( 'academic-article academic-package-single' ); ?>>
				<header class="academic-article__header">
					<h1 class="academic-heading-xl"><?php the_title(); ?></h1>
					<?php if ( '' !== $billing ) : ?><span class="academic-chip"><?php echo esc_html( $billing ); ?></span><?php endif; ?>
				</header>

				<?php if ( '' !== $price ) : ?>
					<div class="academic-package-card__price academic-package-single__price">
						<span class="academic-package-card__price-eyebrow"><?php esc_html_e( 'Mulai dari', 'dzurriyyatul-academic' ); ?></span>
						<div class="academic-package-card__price-row">
							<span class="academic-package-card__price-value"><?php echo esc_html( 'Rp ' . number_format_i18n( (float) $price, 0 ) ); ?></span>
							<?php if ( '' !== $price_label ) : ?><span class="academic-package-card__price-unit"><?php echo esc_html( $price_label ); ?></span><?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="academic-article__content academic-prose">
					<?php the_content(); ?>
				</div>

				<?php if ( ! empty( $features ) ) : ?>
					<ul class="academic-package-card__features">
						<?php foreach ( $features as $feature ) : ?>
							<li><?php echo dq_icon( 'fa-solid fa-check' ); ?> <span><?php echo esc_html( $feature ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( '' !== $cta_url ) : ?>
					<a class="academic-btn academic-btn--primary academic-btn--pill academic-btn--lg" href="<?php echo esc_url( $cta_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html( '' !== $cta_text ? $cta_text : __( 'Pilih Paket Ini', 'dzurriyyatul-academic' ) ); ?>
					</a>
				<?php endif; ?>
			</article>
		</div>
	</main>
	<?php
endwhile;

get_footer();
