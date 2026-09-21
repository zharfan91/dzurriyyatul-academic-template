<?php
/**
 * Mentor corps — dynamic WP_Query over the `mentor` CPT (§32).
 * No mentor identity is ever fabricated; the archive is simply empty
 * until an administrator adds real profiles (§19, §48).
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mentors_query = new WP_Query( array(
	'post_type'      => 'mentor',
	'post_status'    => 'publish',
	'posts_per_page' => 8,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

// No mentor profiles yet: skip the section for visitors entirely rather
// than showing an admin-facing "add content" notice on the public site.
if ( ! $mentors_query->have_posts() ) {
	if ( current_user_can( 'edit_posts' ) ) {
		echo '<section class="academic-mentors academic-mentors--empty" id="mentor"><div class="academic-container">';
		dq_empty_state( __( 'Profil mentor belum ditambahkan. Tambahkan melalui menu Mentor di wp-admin.', 'dzurriyyatul-academic' ) );
		echo '</div></section>';
	}
	return;
}
?>
<section class="academic-mentors" id="mentor">
	<div class="academic-container">
		<div class="academic-mentors__header">
			<div>
				<span class="academic-eyebrow academic-eyebrow--green"><?php esc_html_e( 'Dewan Pakar & Mentor', 'dzurriyyatul-academic' ); ?></span>
				<h2 class="academic-heading-xl"><?php esc_html_e( 'Didampingi Akademisi & Praktisi Riset', 'dzurriyyatul-academic' ); ?></h2>
			</div>
			<p class="academic-mentors__desc"><?php esc_html_e( 'Semua mentor telah melalui seleksi ketat, memegang gelar master & doktor dari perguruan tinggi terkemuka, serta aktif menerbitkan artikel di jurnal bereputasi.', 'dzurriyyatul-academic' ); ?></p>
		</div>

		<div class="academic-mentors__grid">
				<?php while ( $mentors_query->have_posts() ) : $mentors_query->the_post(); ?>
					<?php
					$role      = get_post_meta( get_the_ID(), '_dq_role', true );
					$specialty = get_post_meta( get_the_ID(), '_dq_specialization', true );
					$badges    = dq_lines_to_array( get_post_meta( get_the_ID(), '_dq_credentials', true ) );
					?>
					<div class="academic-card academic-mentor-card">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="academic-mentor-card__photo">
								<?php the_post_thumbnail( 'dq-avatar', array( 'loading' => 'lazy', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
							</div>
						<?php endif; ?>
						<h3 class="academic-heading-sm"><?php the_title(); ?></h3>
						<?php if ( '' !== $role ) : ?>
							<p class="academic-mentor-card__role"><?php echo esc_html( $role ); ?></p>
						<?php endif; ?>
						<?php if ( '' !== $specialty ) : ?>
							<p class="academic-mentor-card__specialty"><?php echo esc_html( sprintf( __( 'Bidang: %s', 'dzurriyyatul-academic' ), $specialty ) ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $badges ) ) : ?>
							<div class="academic-mentor-card__badges">
								<?php foreach ( array_slice( $badges, 0, 2 ) as $badge ) : ?>
									<span class="academic-chip"><?php echo esc_html( $badge ); ?></span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
	</div>
</section>
