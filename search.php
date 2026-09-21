<?php
/**
 * Search results template.
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
			<h1 class="academic-heading-xl">
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( 'Hasil Pencarian untuk: %s', 'dzurriyyatul-academic' ),
					'<span>' . esc_html( get_search_query() ) . '</span>'
				);
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="academic-article-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'academic-card academic-article-card' ); ?>>
						<h2 class="academic-heading-sm"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="academic-article-card__meta"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></p>
						<p class="academic-article-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
					</article>
				<?php endwhile; ?>
			</div>
			<?php dq_pagination(); ?>
		<?php else : ?>
			<?php dq_empty_state( __( 'Tidak ditemukan hasil yang sesuai dengan pencarian Anda.', 'dzurriyyatul-academic' ) ); ?>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
