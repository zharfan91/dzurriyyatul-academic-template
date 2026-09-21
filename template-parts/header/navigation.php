<?php
/**
 * Sticky main navigation + mobile drawer.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wa_url = dq_whatsapp_url();
?>
<header class="academic-header" id="masthead">
	<nav class="academic-nav" aria-label="<?php esc_attr_e( 'Navigasi Utama', 'dzurriyyatul-academic' ); ?>">
		<div class="academic-container academic-nav__row">
			<a class="academic-nav__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					echo '<span class="academic-nav__brand-text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
				}
				?>
			</a>

			<div class="academic-nav__links">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'academic-nav__menu',
						'depth'          => 1,
					) );
				} elseif ( is_front_page() ) {
					?>
					<ul class="academic-nav__menu">
						<li><a href="#beranda"><?php esc_html_e( 'Beranda', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#layanan"><?php esc_html_e( 'Layanan', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#paket"><?php esc_html_e( 'Paket & Biaya', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#mentor"><?php esc_html_e( 'Mentor & Pengajar', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#solusi"><?php esc_html_e( 'Diagnosa Masalah', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#integritas" class="academic-nav__menu-highlight"><?php echo dq_icon( 'fa-solid fa-shield-halved' ); ?> <?php esc_html_e( 'Integritas', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#legalitas"><?php esc_html_e( 'Tentang Kami', 'dzurriyyatul-academic' ); ?></a></li>
						<li><a href="#faq"><?php esc_html_e( 'FAQ', 'dzurriyyatul-academic' ); ?></a></li>
					</ul>
					<?php
				}
				?>
			</div>

			<div class="academic-nav__actions">
				<?php if ( '' !== $wa_url ) : ?>
					<a class="academic-btn academic-btn--primary academic-btn--pill academic-nav__cta" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo dq_icon( 'fa-brands fa-whatsapp' ); ?>
						<span><?php esc_html_e( 'Konsultasi', 'dzurriyyatul-academic' ); ?></span>
					</a>
				<?php endif; ?>
				<button type="button" class="academic-nav__toggle" id="academicMobileToggle" aria-expanded="false" aria-controls="academicMobileDrawer">
					<span class="screen-reader-text"><?php esc_html_e( 'Buka menu navigasi', 'dzurriyyatul-academic' ); ?></span>
					<?php echo dq_icon( 'fa-solid fa-bars' ); ?>
				</button>
			</div>
		</div>
	</nav>

	<div class="academic-mobile-drawer" id="academicMobileDrawer" hidden>
		<ul class="academic-mobile-drawer__list">
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'depth'          => 1,
				) );
				?>
			<?php elseif ( is_front_page() ) : ?>
				<li><a href="#beranda"><?php esc_html_e( 'Beranda', 'dzurriyyatul-academic' ); ?></a></li>
				<li><a href="#layanan"><?php esc_html_e( 'Layanan', 'dzurriyyatul-academic' ); ?></a></li>
				<li><a href="#solusi"><?php esc_html_e( 'Diagnosis Masalah', 'dzurriyyatul-academic' ); ?></a></li>
				<li><a href="#paket"><?php esc_html_e( 'Paket Pendampingan', 'dzurriyyatul-academic' ); ?></a></li>
				<li><a href="#integritas"><?php esc_html_e( 'Pakta Integritas', 'dzurriyyatul-academic' ); ?></a></li>
				<li><a href="#mentor"><?php esc_html_e( 'Dewan Mentor', 'dzurriyyatul-academic' ); ?></a></li>
				<li><a href="#faq"><?php esc_html_e( 'FAQ', 'dzurriyyatul-academic' ); ?></a></li>
			<?php endif; ?>
			<?php if ( '' !== $wa_url ) : ?>
				<li class="academic-mobile-drawer__cta">
					<a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" class="academic-btn academic-btn--primary">
						<?php echo dq_icon( 'fa-brands fa-whatsapp' ); ?> <?php esc_html_e( 'Hubungi WhatsApp Hotline', 'dzurriyyatul-academic' ); ?>
					</a>
				</li>
			<?php endif; ?>
		</ul>
	</div>
</header>
