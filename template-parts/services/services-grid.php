<?php
/**
 * Services grid — dynamic WP_Query over the `service` CPT (§27).
 * Admins add/edit/remove/reorder/feature services entirely from wp-admin;
 * nothing here is hard-coded.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services_query = new WP_Query( array(
	'post_type'      => 'service',
	'post_status'    => 'publish',
	'posts_per_page' => 9,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

$wa_url = dq_whatsapp_url();

// No published services yet: skip the section for visitors entirely rather
// than showing an admin-facing "add content" notice on the public site
// (matches the pattern already used by hero/testimonials/FAQ). Editors
// still see a reminder so the gap isn't invisible while building the site.
if ( ! $services_query->have_posts() ) {
	if ( current_user_can( 'edit_posts' ) ) {
		echo '<section class="academic-services academic-services--empty" id="layanan"><div class="academic-container">';
		dq_empty_state( __( 'Belum ada layanan yang dipublikasikan. Tambahkan melalui menu Layanan di wp-admin.', 'dzurriyyatul-academic' ) );
		echo '</div></section>';
	}
	return;
}
?>
<section class="academic-services" id="layanan">
	<div class="academic-container">
		<div class="academic-services__header">
			<div class="academic-services__intro">
				<span class="academic-eyebrow academic-eyebrow--green"><?php echo dq_icon( 'fa-solid fa-layer-group' ); ?> <?php esc_html_e( 'Layanan Kami', 'dzurriyyatul-academic' ); ?></span>
				<h2 class="academic-heading-xl"><?php esc_html_e( 'Solusi Lengkap Kebutuhan Akademik Anda', 'dzurriyyatul-academic' ); ?></h2>
				<p class="academic-body"><?php esc_html_e( 'Kami menyediakan program pendampingan step-by-step untuk membantu Anda menguasai setiap fase riset ilmiah secara mandiri dan percaya diri.', 'dzurriyyatul-academic' ); ?></p>
			</div>
			<?php if ( '' !== $wa_url ) : ?>
				<a class="academic-link-arrow" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
					<span><?php esc_html_e( 'Konsultasikan Kebutuhan Anda', 'dzurriyyatul-academic' ); ?></span>
					<?php echo dq_icon( 'fa-solid fa-arrow-right' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="academic-services__grid">
			<?php while ( $services_query->have_posts() ) : $services_query->the_post(); ?>
				<?php
				global $post;
				$post = dq_maybe_preview_post( $post );
				setup_postdata( $post );

				$icon      = get_post_meta( get_the_ID(), '_dq_icon', true );
				$short     = get_post_meta( get_the_ID(), '_dq_short_description', true );
				$features  = dq_lines_to_array( get_post_meta( get_the_ID(), '_dq_features', true ) );
				$featured  = '1' === get_post_meta( get_the_ID(), '_dq_featured', true );
				?>
				<article class="academic-card academic-service-card<?php echo $featured ? ' is-featured' : ''; ?>">
					<?php if ( '' !== $icon ) : ?>
						<div class="academic-service-card__icon"><?php echo dq_icon( $icon ); ?></div>
					<?php endif; ?>
					<h3 class="academic-heading-md">
						<a href="<?php echo esc_url( get_permalink( $post->post_parent ? $post->post_parent : get_the_ID() ) ); ?>"><?php the_title(); ?></a>
					</h3>
					<p class="academic-service-card__desc">
						<?php echo esc_html( '' !== $short ? $short : wp_trim_words( get_the_excerpt(), 24 ) ); ?>
					</p>
					<?php if ( ! empty( $features ) ) : ?>
						<ul class="academic-service-card__features">
							<?php foreach ( array_slice( $features, 0, 4 ) as $feature ) : ?>
								<li><?php echo dq_icon( 'fa-solid fa-check' ); ?> <?php echo esc_html( $feature ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
