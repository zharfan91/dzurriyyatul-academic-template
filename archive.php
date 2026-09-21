<?php
/**
 * Default archive template (native blog articles + fallback for any
 * archive not covered by a more specific template).
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
			<h1 class="academic-heading-xl"><?php the_archive_title(); ?></h1>
			<?php the_archive_description( '<div class="academic-archive__desc academic-prose">', '</div>' ); ?>
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
			<?php dq_empty_state( __( 'Belum ada artikel yang dipublikasikan.', 'dzurriyyatul-academic' ) ); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
