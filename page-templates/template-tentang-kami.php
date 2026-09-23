<?php
/**
 * Template Name: Tentang Kami
 *
 * Selectable in Pages → Add New → Page Attributes → Template. The page's
 * own content (the_content()) is where the admin writes the company story
 * in the normal block editor; legal details below it are pulled from the
 * same Theme Settings (Appearance → Customize → Pengaturan Situs Akademik →
 * Legalitas) that already back the homepage legality banner, so nothing is
 * duplicated/re-entered.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$entity_name = dq_get_setting( 'legal_entity_name' );
$ahu_number  = dq_get_setting( 'legal_ahu_number' );
$sk_date     = dq_get_setting( 'legal_sk_date' );
$legal_note  = dq_get_setting( 'legal_note' );
$wa_url      = dq_whatsapp_url( __( 'Halo Admin, saya ingin tahu lebih lanjut tentang lembaga ini', 'dzurriyyatul-academic' ) );

$has_legal_details = ( '' !== $entity_name || '' !== $ahu_number );
?>

<main id="main-content" class="academic-main academic-main--page">
	<div class="academic-container academic-content-narrow">
		<?php dq_breadcrumbs(); ?>

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'academic-article' ); ?>>
				<header class="academic-article__header">
					<span class="academic-eyebrow academic-eyebrow--green"><?php echo dq_icon( 'fa-solid fa-building-columns' ); ?> <?php esc_html_e( 'Tentang Kami', 'dzurriyyatul-academic' ); ?></span>
					<h1 class="academic-heading-xl"><?php the_title(); ?></h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="academic-article__thumb">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="academic-article__content academic-prose">
					<?php the_content(); ?>
				</div>
			</article>

			<?php if ( $has_legal_details ) : ?>
				<section class="academic-legality-detail">
					<h2 class="academic-heading-md"><?php esc_html_e( 'Legalitas Lembaga', 'dzurriyyatul-academic' ); ?></h2>
					<dl class="academic-legality-detail__list">
						<?php if ( '' !== $entity_name ) : ?>
							<div class="academic-legality-detail__row">
								<dt><?php esc_html_e( 'Nama Badan Hukum/Yayasan', 'dzurriyyatul-academic' ); ?></dt>
								<dd><?php echo esc_html( $entity_name ); ?></dd>
							</div>
						<?php endif; ?>
						<?php if ( '' !== $ahu_number ) : ?>
							<div class="academic-legality-detail__row">
								<dt><?php esc_html_e( 'Nomor AHU Kemenkumham', 'dzurriyyatul-academic' ); ?></dt>
								<dd><?php echo esc_html( $ahu_number ); ?></dd>
							</div>
						<?php endif; ?>
						<?php if ( '' !== $sk_date ) : ?>
							<div class="academic-legality-detail__row">
								<dt><?php esc_html_e( 'Tanggal SK', 'dzurriyyatul-academic' ); ?></dt>
								<dd><?php echo esc_html( $sk_date ); ?></dd>
							</div>
						<?php endif; ?>
					</dl>
					<?php if ( '' !== $legal_note ) : ?>
						<p class="academic-legality-detail__note"><?php echo esc_html( $legal_note ); ?></p>
					<?php endif; ?>
				</section>
			<?php endif; ?>

			<?php if ( '' !== $wa_url ) : ?>
				<div class="academic-article__cta">
					<a class="academic-btn academic-btn--primary academic-btn--pill academic-btn--lg" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo dq_icon( 'fa-brands fa-whatsapp' ); ?>
						<span><?php esc_html_e( 'Konsultasi Sekarang', 'dzurriyyatul-academic' ); ?></span>
					</a>
				</div>
			<?php endif; ?>

			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
