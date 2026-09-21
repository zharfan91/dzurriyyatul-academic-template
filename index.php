<?php
/**
 * Universal fallback template (required by WordPress for every theme).
 * Reached for the blog posts index (when a static front page has a
 * separate "Posts page" configured) and as the last-resort template for
 * any request type without a more specific template file.
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
				if ( is_home() && ! is_front_page() ) {
					single_post_title();
				} else {
					esc_html_e( 'Artikel', 'dzurriyyatul-academic' );
				}
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
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="academic-article-card__thumb" href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'dq-card', array( 'loading' => 'lazy' ) ); ?>
							</a>
						<?php endif; ?>
						<h2 class="academic-heading-sm"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="academic-article-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
						<p class="academic-article-card__meta"><?php echo esc_html( get_the_date() ); ?></p>
					</article>
				<?php endwhile; ?>
			</div>

			<?php dq_pagination(); ?>
		<?php else : ?>
			<?php dq_empty_state( __( 'Belum ada konten yang dipublikasikan.', 'dzurriyyatul-academic' ) ); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
