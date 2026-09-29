<?php
/**
 * Hero carousel — 3-slide editorial hero driven by Customizer → Hero Slides.
 *
 * Each slide: 50/50 text + large image that bleeds to the viewport's right
 * edge, with a per-slide abstract left edge (inc/hero-frame.php). Navigation
 * is the top slide tabs + a subtle footer (indicator + previous/next);
 * behavior lives in js/hero.js.
 *
 * Older per-slide fields (quote, checklist, caption, tags, metrics) are kept
 * in the saved settings but no longer rendered here.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$carousel = dq_hero_carousel_settings();

$slides = array();
for ( $i = 0; $i < 3; $i++ ) {
	$slide = dq_get_hero_slide( $i );
	if ( '' !== trim( $slide['heading_line1'] ) && $slide['enabled'] ) {
		$slides[ $i ] = $slide;
	}
}

if ( ! $carousel['enabled'] && $slides ) {
	$slides = array_slice( $slides, 0, 1, true );
}

if ( empty( $slides ) ) {
	if ( current_user_can( 'edit_theme_options' ) ) {
		echo '<section class="academic-hero academic-hero--empty" id="beranda"><div class="academic-container">';
		dq_empty_state( __( 'Hero belum dikonfigurasi. Buka Penyesuai (Customize) → Hero Slides untuk mengisi slide hero.', 'dzurriyyatul-academic' ) );
		echo '</div></section>';
		return;
	}

	// Visitors still get a minimal branded banner built only from Site
	// Identity + the WhatsApp number while the carousel is unconfigured.
	$fallback_wa_url = dq_whatsapp_url();
	?>
	<section class="academic-hero academic-hero--fallback" id="beranda">
		<div class="academic-hero__pattern" aria-hidden="true"></div>
		<div class="academic-container academic-hero__fallback-inner">
			<h1 class="academic-hero__heading"><?php bloginfo( 'name' ); ?></h1>
			<?php if ( '' !== get_bloginfo( 'description' ) ) : ?>
				<p class="academic-hero__tagline">&ldquo;<?php bloginfo( 'description' ); ?>&rdquo;</p>
			<?php endif; ?>
			<?php if ( '' !== $fallback_wa_url ) : ?>
				<div class="academic-hero__actions academic-hero__actions--center">
					<a class="academic-btn academic-btn--primary academic-btn--pill academic-btn--lg" href="<?php echo esc_url( $fallback_wa_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo dq_icon( 'fa-brands fa-whatsapp' ); ?>
						<span><?php esc_html_e( 'Konsultasi Sekarang', 'dzurriyyatul-academic' ); ?></span>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return;
}

$total        = count( $slides );
$is_carousel  = $total > 1;
$show_nav     = $is_carousel && $carousel['show_navigation'];
$show_dots    = $is_carousel && $carousel['show_indicator'];
$hero_classes = 'academic-hero academic-hero--editorial academic-hero--anim-' . $carousel['animation'] . ( $is_carousel ? '' : ' academic-hero--single' );
?>
<section
	class="<?php echo esc_attr( $hero_classes ); ?>"
	id="beranda"
	style="--hero-anim-duration: <?php echo esc_attr( $carousel['animation_duration'] ); ?>ms;"
	<?php if ( $is_carousel ) : ?>aria-roledescription="carousel"<?php endif; ?>
	aria-label="<?php esc_attr_e( 'Sorotan Program', 'dzurriyyatul-academic' ); ?>"
>
	<?php dq_hero_frame_render_clip_paths( $slides ); ?>
	<div class="academic-hero__pattern" aria-hidden="true"></div>

	<?php if ( $is_carousel ) : ?>
		<div class="academic-container academic-hero__tabbar">
			<div class="academic-hero__tabs">
				<?php $n = 0; foreach ( $slides as $index => $slide ) : ?>
					<button
						type="button"
						class="academic-hero__tab<?php echo 0 === $n ? ' is-active' : ''; ?>"
						data-slide-index="<?php echo esc_attr( $n ); ?>"
						aria-controls="hero-slide-<?php echo esc_attr( $index ); ?>"
						aria-current="<?php echo 0 === $n ? 'true' : 'false'; ?>"
					>
						<span class="academic-hero__tab-number"><?php echo esc_html( $n + 1 ); ?></span>
						<span><?php echo esc_html( '' !== $slide['tab_label'] ? $slide['tab_label'] : $slide['heading_line1'] ); ?></span>
					</button>
				<?php $n++; endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<div
		class="academic-hero__stage"
		id="heroCarouselStage"
		data-autoplay="<?php echo esc_attr( $carousel['duration'] ); ?>"
		data-autoplay-enabled="<?php echo esc_attr( $is_carousel && $carousel['autoplay'] ? '1' : '0' ); ?>"
		<?php if ( $is_carousel ) : ?>tabindex="0"<?php endif; ?>
	>
		<?php $n = 0; foreach ( $slides as $index => $slide ) :
			$heading_tag  = 0 === $n ? 'h1' : 'h2';
			$is_wa        = '' === $slide['primary_cta_url'];
			$primary_url  = $is_wa ? dq_whatsapp_url( $slide['primary_cta_message'] ) : $slide['primary_cta_url'];
			$framed       = ! empty( $slide['frame_enabled'] );
			$frame_class  = 'academic-hero__image-frame' . ( $framed ? ' academic-hero__image-frame--framed' : '' );
			$frame_style  = $framed ? 'clip-path:url(#dq-hero-frame-clip-' . absint( $index ) . ');' : '';
			$image_style  = sprintf( 'object-position:%s;transform:scale(%s);', $slide['image_position'], (float) $slide['image_zoom'] / 100 );
			?>
			<div
				class="academic-hero__slide<?php echo 0 === $n ? ' is-active' : ''; ?>"
				data-slide="<?php echo esc_attr( $n ); ?>"
				id="hero-slide-<?php echo esc_attr( $index ); ?>"
				<?php if ( $is_carousel ) : ?>
					role="group"
					aria-roledescription="slide"
					aria-label="<?php echo esc_attr( sprintf( '%1$d / %2$d', $n + 1, $total ) ); ?>"
					<?php echo 0 !== $n ? 'aria-hidden="true"' : ''; ?>
				<?php endif; ?>
			>
				<div class="academic-container academic-hero__grid">
					<div class="academic-hero__content">
						<?php if ( '' !== $slide['badge_text'] ) : ?>
							<p class="academic-hero__badge">
								<span class="academic-hero__badge-dot" aria-hidden="true"></span>
								<span><?php echo esc_html( trim( $slide['badge_emoji'] . ' ' . $slide['badge_text'] ) ); ?></span>
							</p>
						<?php endif; ?>

						<<?php echo $heading_tag; // phpcs:ignore -- fixed 'h1'/'h2' literal. ?> class="academic-hero__heading">
							<?php echo esc_html( $slide['heading_line1'] ); ?>
							<?php if ( '' !== $slide['heading_line2'] ) : ?>
								<span class="academic-hero__heading-accent"><?php echo esc_html( $slide['heading_line2'] ); ?></span>
							<?php endif; ?>
						</<?php echo $heading_tag; // phpcs:ignore -- fixed 'h1'/'h2' literal. ?>>

						<?php if ( '' !== $slide['tagline_quote'] ) : ?>
							<p class="academic-hero__tagline">&ldquo;<?php echo esc_html( dq_strip_wrapping_quotes( $slide['tagline_quote'] ) ); ?>&rdquo;</p>
						<?php endif; ?>

						<?php if ( '' !== $slide['description'] ) : ?>
							<p class="academic-hero__description"><?php echo esc_html( $slide['description'] ); ?></p>
						<?php endif; ?>

						<?php if ( ( '' !== $slide['primary_cta_label'] && '' !== $primary_url ) || ( '' !== $slide['secondary_cta_label'] && '' !== $slide['secondary_cta_url'] ) ) : ?>
							<div class="academic-hero__actions">
								<?php if ( '' !== $slide['primary_cta_label'] && '' !== $primary_url ) : ?>
									<a class="academic-btn academic-btn--primary academic-btn--pill academic-btn--lg" href="<?php echo esc_url( $primary_url ); ?>"<?php echo $is_wa ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
										<?php if ( $is_wa ) : ?>
											<?php echo dq_icon( 'fa-brands fa-whatsapp' ); ?>
										<?php endif; ?>
										<span><?php echo esc_html( $slide['primary_cta_label'] ); ?></span>
									</a>
								<?php endif; ?>
								<?php if ( '' !== $slide['secondary_cta_label'] && '' !== $slide['secondary_cta_url'] ) : ?>
									<a class="academic-btn academic-btn--outline academic-btn--pill academic-btn--lg" href="<?php echo esc_url( $slide['secondary_cta_url'] ); ?>">
										<span><?php echo esc_html( $slide['secondary_cta_label'] ); ?></span>
										<?php echo dq_icon( 'fa-solid fa-arrow-right' ); ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="academic-hero__visual">
					<div class="<?php echo esc_attr( $frame_class ); ?>" style="<?php echo esc_attr( $frame_style ); ?>">
						<?php if ( $slide['image_id'] && wp_attachment_is_image( $slide['image_id'] ) ) : ?>
							<?php
							echo wp_get_attachment_image(
								$slide['image_id'],
								'full',
								false,
								array(
									'class'         => 'academic-hero__image',
									'style'         => $image_style,
									'alt'           => $slide['image_alt'],
									'sizes'         => '(min-width: 1024px) 50vw, 100vw',
									'fetchpriority' => 0 === $n ? 'high' : 'low',
									'loading'       => 0 === $n ? 'eager' : 'lazy',
								)
							);
							?>
						<?php else : ?>
							<div class="academic-hero__image-placeholder" aria-hidden="true"></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php $n++; endforeach; ?>
	</div>

	<?php if ( $show_nav || $show_dots ) : ?>
		<div class="academic-container academic-hero__footer">
			<?php if ( $show_dots ) : ?>
				<div class="academic-hero__dots">
					<?php for ( $n = 0; $n < $total; $n++ ) : ?>
						<button type="button" class="academic-hero__dot<?php echo 0 === $n ? ' is-active' : ''; ?>" data-slide-index="<?php echo esc_attr( $n ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Buka Slide %d', 'dzurriyyatul-academic' ), $n + 1 ) ); ?>"></button>
					<?php endfor; ?>
					<span class="academic-hero__counter" id="heroSlideCounter" aria-live="polite">01 / <?php echo esc_html( sprintf( '%02d', $total ) ); ?></span>
				</div>
			<?php endif; ?>
			<?php if ( $show_nav ) : ?>
				<div class="academic-hero__arrows">
					<button type="button" class="academic-hero__arrow" id="heroPrevBottom" aria-label="<?php esc_attr_e( 'Slide sebelumnya', 'dzurriyyatul-academic' ); ?>"><?php echo dq_icon( 'fa-solid fa-arrow-left' ); ?></button>
					<button type="button" class="academic-hero__arrow" id="heroNextBottom" aria-label="<?php esc_attr_e( 'Slide berikutnya', 'dzurriyyatul-academic' ); ?>"><?php echo dq_icon( 'fa-solid fa-arrow-right' ); ?></button>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
