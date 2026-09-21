<?php
/**
 * Site footer — brand, services links, quick nav, contact, copyright.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$entity_name = dq_get_setting( 'legal_entity_name' );
$ahu_number  = dq_get_setting( 'legal_ahu_number' );
$sk_date     = dq_get_setting( 'legal_sk_date' );
$phone       = dq_get_setting( 'contact_phone' );
$email       = dq_get_setting( 'contact_email' );
$hours       = dq_get_setting( 'operating_hours' );

$socials = array(
	'instagram' => array( dq_get_setting( 'social_instagram' ), 'fa-brands fa-instagram' ),
	'whatsapp'  => array( dq_whatsapp_url(), 'fa-brands fa-whatsapp' ),
	'youtube'   => array( dq_get_setting( 'social_youtube' ), 'fa-brands fa-youtube' ),
	'tiktok'    => array( dq_get_setting( 'social_tiktok' ), 'fa-brands fa-tiktok' ),
	'facebook'  => array( dq_get_setting( 'social_facebook' ), 'fa-brands fa-facebook' ),
);
?>
<footer class="academic-footer">
	<div class="academic-container">
		<div class="academic-footer__grid">
			<div class="academic-footer__brand">
				<div class="academic-footer__brand-row">
					<?php if ( has_custom_logo() ) : ?>
						<div class="academic-footer__logo"><?php the_custom_logo(); ?></div>
					<?php endif; ?>
					<div>
						<h3 class="academic-footer__brand-name"><?php bloginfo( 'name' ); ?></h3>
						<?php
						$tagline = dq_get_setting( 'tagline_override' ) ? dq_get_setting( 'tagline_override' ) : get_bloginfo( 'description' );
						if ( '' !== $tagline ) :
							?>
							<p class="academic-footer__brand-tag"><?php echo esc_html( $tagline ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<p class="academic-footer__desc"><?php esc_html_e( 'Lembaga pusat bimbingan riset ilmiah, pendampingan penulisan skripsi, tesis, dan publikasi jurnal terakreditasi.', 'dzurriyyatul-academic' ); ?></p>

				<?php if ( '' !== $ahu_number ) : ?>
					<div class="academic-footer__legal-box">
						<span><?php esc_html_e( 'Legalitas Kemenkumham RI:', 'dzurriyyatul-academic' ); ?></span><br />
						<?php echo esc_html( $ahu_number ); ?><br />
						<?php if ( '' !== $sk_date ) : ?>
							<?php echo esc_html( sprintf( __( 'Tanggal SK: %s', 'dzurriyyatul-academic' ), $sk_date ) ); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="academic-footer__social">
					<?php foreach ( $socials as $key => $data ) : list( $url, $icon ) = $data; ?>
						<?php if ( '' !== $url ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $key ) ); ?>"><?php echo dq_icon( $icon ); ?></a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="academic-footer__col">
				<h4 class="academic-footer__heading"><?php esc_html_e( 'Layanan Akademik', 'dzurriyyatul-academic' ); ?></h4>
				<?php
				$services = get_posts( array(
					'post_type'      => 'service',
					'post_status'    => 'publish',
					'posts_per_page' => 6,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'no_found_rows'  => true,
				) );
				if ( ! empty( $services ) ) :
					?>
					<ul class="academic-footer__list">
						<?php foreach ( $services as $service ) : ?>
							<li><a href="<?php echo esc_url( get_permalink( $service ) ); ?>"><?php echo esc_html( $service->post_title ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php elseif ( is_front_page() ) : ?>
					<ul class="academic-footer__list"><li><a href="#layanan"><?php esc_html_e( 'Lihat semua layanan', 'dzurriyyatul-academic' ); ?></a></li></ul>
				<?php endif; ?>
			</div>

			<div class="academic-footer__col">
				<h4 class="academic-footer__heading"><?php esc_html_e( 'Navigasi', 'dzurriyyatul-academic' ); ?></h4>
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'academic-footer__list',
						'depth'          => 1,
					) );
					?>
				<?php elseif ( is_front_page() ) : ?>
					<ul class="academic-footer__list">
						<li><a href="#beranda"><?php esc_html_e( 'Beranda', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#solusi"><?php esc_html_e( 'Diagnosis Riset', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#paket"><?php esc_html_e( 'Paket & Biaya', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#integritas"><?php esc_html_e( 'Pakta Integritas', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#mentor"><?php esc_html_e( 'Profil Mentor', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#faq"><?php esc_html_e( 'FAQ Bimbingan', 'dzurriyyatul-academic' ); ?></a></li>
					</ul>
				<?php endif; ?>
			</div>

			<div class="academic-footer__col">
				<h4 class="academic-footer__heading"><?php esc_html_e( 'Hotline & Sekretariat', 'dzurriyyatul-academic' ); ?></h4>
				<?php if ( '' !== $entity_name ) : ?>
					<p class="academic-footer__desc"><?php echo esc_html( $entity_name ); ?></p>
				<?php endif; ?>
				<div class="academic-footer__contact">
					<?php if ( '' !== $phone ) : ?>
						<p><?php echo dq_icon( 'fa-solid fa-phone' ); ?> <span><?php echo esc_html( $phone ); ?></span></p>
					<?php endif; ?>
					<?php if ( '' !== $email ) : ?>
						<p><?php echo dq_icon( 'fa-solid fa-envelope' ); ?> <span><a href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>"><?php echo esc_html( $email ); ?></a></span></p>
					<?php endif; ?>
					<?php if ( '' !== $hours ) : ?>
						<p><?php echo dq_icon( 'fa-solid fa-clock' ); ?> <span><?php echo esc_html( $hours ); ?></span></p>
					<?php endif; ?>
				</div>
				<span class="academic-footer__badge"><?php esc_html_e( 'Konsultasi Tatap Muka & Virtual', 'dzurriyyatul-academic' ); ?></span>
				<?php if ( is_active_sidebar( 'footer-widget' ) ) : ?>
					<?php dynamic_sidebar( 'footer-widget' ); ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="academic-footer__bottom">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Seluruh Hak Cipta Dilindungi Undang-Undang.', 'dzurriyyatul-academic' ); ?></p>
			<div class="academic-footer__policies">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'fallback_cb'    => false,
					'depth'          => 1,
					'menu_class'     => 'academic-footer__policies-list',
				) );
				?>
			</div>
		</div>
	</div>
</footer>
