<?php
/**
 * Archive of `service` posts (standalone "Layanan" listing page).
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
			<h1 class="academic-heading-xl"><?php esc_html_e( 'Layanan Akademik', 'dzurriyyatul-academic' ); ?></h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="academic-services__grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$icon     = get_post_meta( get_the_ID(), '_dq_icon', true );
					$short    = get_post_meta( get_the_ID(), '_dq_short_description', true );
					$features = dq_lines_to_array( get_post_meta( get_the_ID(), '_dq_features', true ) );
					?>
					<article class="academic-card academic-service-card">
						<?php if ( '' !== $icon ) : ?>
							<div class="academic-service-card__icon"><?php echo dq_icon( $icon ); ?></div>
						<?php endif; ?>
						<h2 class="academic-heading-md"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="academic-service-card__desc"><?php echo esc_html( '' !== $short ? $short : wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
						<?php if ( ! empty( $features ) ) : ?>
							<ul class="academic-service-card__features">
								<?php foreach ( array_slice( $features, 0, 4 ) as $feature ) : ?>
									<li><?php echo dq_icon( 'fa-solid fa-check' ); ?> <?php echo esc_html( $feature ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</article>
				<?php endwhile; ?>
			</div>
			<?php dq_pagination(); ?>
		<?php else : ?>
			<?php dq_empty_state( __( 'Belum ada layanan yang dipublikasikan.', 'dzurriyyatul-academic' ) ); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
