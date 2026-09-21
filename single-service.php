<?php
/**
 * Single `service` template.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$icon        = get_post_meta( get_the_ID(), '_dq_icon', true );
	$price_label = get_post_meta( get_the_ID(), '_dq_price_label', true );
	$duration    = get_post_meta( get_the_ID(), '_dq_duration', true );
	$features    = dq_lines_to_array( get_post_meta( get_the_ID(), '_dq_features', true ) );
	$cta_text    = get_post_meta( get_the_ID(), '_dq_cta_text', true );
	$cta_url     = get_post_meta( get_the_ID(), '_dq_cta_url', true );
	if ( '' === $cta_url ) {
		$cta_url = dq_whatsapp_url(
			sprintf(
				/* translators: %s: service title */
				__( 'Halo Admin, saya ingin konsultasi %s', 'dzurriyyatul-academic' ),
				get_the_title()
			)
		);
	}
	?>
	<main id="main-content" class="academic-main academic-main--page">
		<div class="academic-container academic-content-narrow">
			<?php dq_breadcrumbs(); ?>

			<article <?php post_class( 'academic-article academic-service-single' ); ?>>
				<header class="academic-article__header">
					<?php if ( '' !== $icon ) : ?>
						<div class="academic-service-single__icon"><?php echo dq_icon( $icon ); ?></div>
					<?php endif; ?>
					<h1 class="academic-heading-xl"><?php the_title(); ?></h1>
					<div class="academic-service-single__meta">
						<?php if ( '' !== $price_label ) : ?><span><?php echo dq_icon( 'fa-solid fa-tag' ); ?> <?php echo esc_html( $price_label ); ?></span><?php endif; ?>
						<?php if ( '' !== $duration ) : ?><span><?php echo dq_icon( 'fa-regular fa-clock' ); ?> <?php echo esc_html( $duration ); ?></span><?php endif; ?>
					</div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="academic-article__thumb">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="academic-article__content academic-prose">
					<?php the_content(); ?>
				</div>

				<?php if ( ! empty( $features ) ) : ?>
					<ul class="academic-service-single__features">
						<?php foreach ( $features as $feature ) : ?>
							<li><?php echo dq_icon( 'fa-solid fa-check' ); ?> <?php echo esc_html( $feature ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( '' !== $cta_url ) : ?>
					<a class="academic-btn academic-btn--primary academic-btn--pill academic-btn--lg" href="<?php echo esc_url( $cta_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo dq_icon( 'fa-brands fa-whatsapp' ); ?>
						<span><?php echo esc_html( '' !== $cta_text ? $cta_text : __( 'Konsultasikan Layanan Ini', 'dzurriyyatul-academic' ) ); ?></span>
					</a>
				<?php endif; ?>
			</article>
		</div>
	</main>
	<?php
endwhile;

get_footer();
