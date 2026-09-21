<?php
/**
 * Single article (native `post`).
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
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
					<h1 class="academic-heading-xl"><?php the_title(); ?></h1>
					<p class="academic-article__meta">
						<?php
						printf(
							/* translators: 1: published date, 2: author name */
							esc_html__( 'Dipublikasikan %1$s oleh %2$s', 'dzurriyyatul-academic' ),
							esc_html( get_the_date() ),
							esc_html( get_the_author() )
						);
						?>
					</p>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="academic-article__thumb">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="academic-article__content academic-prose">
					<?php the_content(); ?>
				</div>

				<?php
				wp_link_pages( array(
					'before' => '<nav class="academic-page-links">' . esc_html__( 'Halaman:', 'dzurriyyatul-academic' ),
					'after'  => '</nav>',
				) );
				?>
			</article>

			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
