<?php
/**
 * Archive of `package` posts.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main-content" class="academic-main academic-main--page">
	<div class="academic-container">
		<?php dq_breadcrumbs(); ?>

		<header class="academic-archive__header">
			<h1 class="academic-heading-xl"><?php esc_html_e( 'Paket Pendampingan', 'dzurriyyatul-academic' ); ?></h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="academic-packages__grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$price       = get_post_meta( get_the_ID(), '_dq_price', true );
					$price_label = get_post_meta( get_the_ID(), '_dq_price_label', true );
					$billing     = get_post_meta( get_the_ID(), '_dq_billing_type', true );
					$features    = dq_lines_to_array( get_post_meta( get_the_ID(), '_dq_features', true ) );
					?>
					<article class="academic-card academic-package-card">
						<div class="academic-package-card__head">
							<h2 class="academic-heading-sm"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php if ( '' !== $billing ) : ?><span class="academic-chip"><?php echo esc_html( $billing ); ?></span><?php endif; ?>
						</div>
						<?php if ( '' !== $price ) : ?>
							<div class="academic-package-card__price">
								<span class="academic-package-card__price-eyebrow"><?php esc_html_e( 'Mulai dari', 'dzurriyyatul-academic' ); ?></span>
								<div class="academic-package-card__price-row">
									<span class="academic-package-card__price-value"><?php echo esc_html( 'Rp ' . number_format_i18n( (float) $price, 0 ) ); ?></span>
									<?php if ( '' !== $price_label ) : ?><span class="academic-package-card__price-unit"><?php echo esc_html( $price_label ); ?></span><?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $features ) ) : ?>
							<ul class="academic-package-card__features">
								<?php foreach ( array_slice( $features, 0, 4 ) as $feature ) : ?>
									<li><?php echo dq_icon( 'fa-solid fa-check' ); ?> <span><?php echo esc_html( $feature ); ?></span></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<a class="academic-btn academic-btn--outline academic-btn--block" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Lihat Detail', 'dzurriyyatul-academic' ); ?></a>
					</article>
				<?php endwhile; ?>
			</div>
			<?php dq_pagination(); ?>
		<?php else : ?>
			<?php dq_empty_state( __( 'Belum ada paket yang dipublikasikan.', 'dzurriyyatul-academic' ) ); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
