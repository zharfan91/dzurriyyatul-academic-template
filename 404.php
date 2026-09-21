<?php
/**
 * 404 error template.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main-content" class="academic-main academic-main--page">
	<div class="academic-container academic-content-narrow academic-error-state">
		<span class="academic-eyebrow academic-eyebrow--gold">404</span>
		<h1 class="academic-heading-xl"><?php esc_html_e( 'Halaman Tidak Ditemukan', 'dzurriyyatul-academic' ); ?></h1>
		<p class="academic-body"><?php esc_html_e( 'Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan. Silakan kembali ke beranda atau gunakan pencarian di bawah ini.', 'dzurriyyatul-academic' ); ?></p>

		<?php get_search_form(); ?>

		<a class="academic-btn academic-btn--primary academic-btn--pill" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php esc_html_e( 'Kembali ke Beranda', 'dzurriyyatul-academic' ); ?>
		</a>
	</div>
</main>

<?php
get_footer();
