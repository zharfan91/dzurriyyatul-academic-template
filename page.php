<?php
/**
 * Generic page template.
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
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="academic-article__thumb">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
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
