<?php
/**
 * Testimonials — dynamic WP_Query over the `testimonial` CPT (§33).
 * Treated strictly as admin-verified content; nothing here is invented
 * or published by default (§20, §48).
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testimonials_query = new WP_Query( array(
	'post_type'      => 'testimonial',
	'post_status'    => 'publish',
	'posts_per_page' => 6,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

if ( ! $testimonials_query->have_posts() ) {
	if ( current_user_can( 'edit_posts' ) ) {
		echo '<section class="academic-testimonials"><div class="academic-container">';
		dq_empty_state( __( 'Belum ada testimoni terverifikasi. Tambahkan melalui menu Testimoni di wp-admin.', 'dzurriyyatul-academic' ) );
		echo '</div></section>';
	}
	return;
}
?>
<section class="academic-testimonials">
	<div class="academic-container">
		<div class="academic-testimonials__header">
			<span class="academic-eyebrow academic-eyebrow--gold"><?php esc_html_e( 'Kisah Sukses Mahasiswa', 'dzurriyyatul-academic' ); ?></span>
			<h2 class="academic-heading-xl"><?php esc_html_e( 'Mereka yang Telah Lulus dengan Bangga', 'dzurriyyatul-academic' ); ?></h2>
			<p class="academic-body"><?php esc_html_e( 'Bukan hanya mendapat nilai A, tetapi paham apa yang ditulis saat diuji dewan penguji.', 'dzurriyyatul-academic' ); ?></p>
		</div>

		<div class="academic-testimonials__grid">
			<?php while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post(); ?>
				<?php
				$program   = get_post_meta( get_the_ID(), '_dq_program', true );
				$year      = get_post_meta( get_the_ID(), '_dq_graduation_year', true );
				$initials  = get_post_meta( get_the_ID(), '_dq_initials', true );
				$rating    = get_post_meta( get_the_ID(), '_dq_rating', true );
				?>
				<div class="academic-card academic-testimonial-card">
					<?php if ( '' !== $rating ) : ?>
						<?php dq_star_rating( (int) $rating ); ?>
					<?php endif; ?>
					<p class="academic-testimonial-card__quote">&ldquo;<?php echo esc_html( wp_strip_all_tags( get_the_content() ) ); ?>&rdquo;</p>
					<div class="academic-testimonial-card__author">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="academic-testimonial-card__avatar-photo"><?php the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy' ) ); ?></div>
						<?php else : ?>
							<div class="academic-testimonial-card__avatar" aria-hidden="true"><?php echo esc_html( '' !== $initials ? $initials : mb_substr( get_the_title(), 0, 2 ) ); ?></div>
						<?php endif; ?>
						<div>
							<p class="academic-testimonial-card__name"><?php the_title(); ?></p>
							<?php if ( '' !== $program ) : ?>
								<p class="academic-testimonial-card__program">
									<?php echo esc_html( $program ); ?><?php echo '' !== $year ? esc_html( ' (' . $year . ')' ) : ''; ?>
								</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
