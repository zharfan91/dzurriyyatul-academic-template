<?php
/**
 * Hero carousel — 3 slides driven entirely by Theme Settings.
 *
 * Preserves the approved Stitch interaction: tab switcher + arrow controls
 * + dot indicators + counter + 7s autoplay (js/hero.js), with full keyboard
 * support and prefers-reduced-motion handling (§25–§26).
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$slides = array();
for ( $i = 0; $i < 3; $i++ ) {
	$slide = dq_get_hero_slide( $i );
	if ( '' !== trim( $slide['heading_line1'] ) ) {
		$slides[ $i ] = $slide;
	}
}

if ( empty( $slides ) ) {
	if ( current_user_can( 'edit_theme_options' ) ) {
		echo '<section class="academic-hero academic-hero--empty" id="beranda"><div class="academic-container">';
		dq_empty_state( __( 'Hero belum dikonfigurasi. Buka Penyesuai (Customize) → Hero Slides untuk mengisi 3 slide hero.', 'dzurriyyatul-academic' ) );
		echo '</div></section>';
		return;
	}

	// Public visitors still get a real (if minimal) hero banner instead of a
	// blank gap before the site owner finishes configuring the 3-slide
	// carousel. Built only from confirmed data already set up in WordPress
	// (Site Identity + WhatsApp number) — nothing here is invented.
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

$total = count( $slides );
?>
<section class="academic-hero" id="beranda" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Sorotan Program', 'dzurriyyatul-academic' ); ?>">
	<div class="academic-hero__pattern" aria-hidden="true"></div>

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
		<?php if ( $total > 1 ) : ?>
			<div class="academic-hero__arrows academic-hero__arrows--top">
				<button type="button" class="academic-hero__arrow" id="heroPrevTop" aria-label="<?php esc_attr_e( 'Slide sebelumnya', 'dzurriyyatul-academic' ); ?>"><?php echo dq_icon( 'fa-solid fa-chevron-left' ); ?></button>
				<button type="button" class="academic-hero__arrow" id="heroNextTop" aria-label="<?php esc_attr_e( 'Slide berikutnya', 'dzurriyyatul-academic' ); ?>"><?php echo dq_icon( 'fa-solid fa-chevron-right' ); ?></button>
			</div>
		<?php endif; ?>
	</div>

	<div class="academic-hero__stage" id="heroCarouselStage" data-autoplay="<?php echo esc_attr( $total > 1 ? '7000' : '0' ); ?>">
		<?php $n = 0; foreach ( $slides as $index => $slide ) :
			$primary_url   = dq_whatsapp_url( '' !== $slide['primary_cta_message'] ? $slide['primary_cta_message'] : '' );
			$tags          = dq_csv_to_array( $slide['tags'] );
			?>
			<div class="academic-hero__slide<?php echo 0 === $n ? ' is-active' : ''; ?>" data-slide="<?php echo esc_attr( $n ); ?>" id="hero-slide-<?php echo esc_attr( $index ); ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( '%1$d / %2$d', $n + 1, $total ) ); ?>"<?php echo 0 !== $n ? ' aria-hidden="true"' : ''; ?>>
				<div class="academic-container academic-hero__grid">
					<div class="academic-hero__content">
						<?php if ( '' !== $slide['badge_text'] ) : ?>
							<div class="academic-hero__badge">
								<span class="academic-hero__badge-dot" aria-hidden="true"></span>
								<span><?php echo esc_html( trim( $slide['badge_emoji'] . ' ' . $slide['badge_text'] ) ); ?></span>
							</div>
						<?php endif; ?>

						<h1 class="academic-hero__heading">
							<?php echo esc_html( $slide['heading_line1'] ); ?>
							<?php if ( '' !== $slide['heading_line2'] ) : ?>
								<br /><span class="academic-hero__heading-accent"><?php echo esc_html( $slide['heading_line2'] ); ?></span>
							<?php endif; ?>
						</h1>

						<?php if ( '' !== $slide['tagline_quote'] ) : ?>
							<p class="academic-hero__tagline">&ldquo;<?php echo esc_html( dq_strip_wrapping_quotes( $slide['tagline_quote'] ) ); ?>&rdquo;</p>
						<?php endif; ?>

						<?php if ( ! empty( $tags ) ) : ?>
							<div class="academic-hero__tags">
								<?php foreach ( $tags as $i => $tag ) : ?>
									<?php if ( $i > 0 ) : ?><span class="academic-hero__tags-sep" aria-hidden="true">&bull;</span><?php endif; ?>
									<span class="academic-hero__tag"><?php echo esc_html( $tag ); ?></span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php if ( '' !== $slide['description'] ) : ?>
							<p class="academic-hero__description"><?php echo esc_html( $slide['description'] ); ?></p>
						<?php endif; ?>

						<div class="academic-hero__actions">
							<?php if ( '' !== $slide['primary_cta_label'] && '' !== $primary_url ) : ?>
								<a class="academic-btn academic-btn--primary academic-btn--pill academic-btn--lg" href="<?php echo esc_url( $primary_url ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo dq_icon( 'fa-brands fa-whatsapp' ); ?>
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

						<?php if ( '' !== $slide['metric_1_value'] || '' !== $slide['metric_2_value'] || '' !== $slide['metric_3_value'] ) : ?>
							<div class="academic-hero__metrics">
								<?php foreach ( array( 1, 2, 3 ) as $m ) : ?>
									<?php if ( '' !== $slide[ 'metric_' . $m . '_value' ] ) : ?>
										<div class="academic-hero__metric">
											<p class="academic-hero__metric-value"><?php echo esc_html( $slide[ 'metric_' . $m . '_value' ] ); ?></p>
											<p class="academic-hero__metric-label"><?php echo esc_html( $slide[ 'metric_' . $m . '_label' ] ); ?></p>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="academic-hero__visual">
						<div class="academic-hero__visual-card">
							<?php if ( $slide['image_id'] ) : ?>
								<div class="academic-hero__image-frame">
									<?php
									echo wp_get_attachment_image(
										$slide['image_id'],
										'dq-hero',
										false,
										array(
											'class'        => 'academic-hero__image',
											'alt'          => esc_attr( $slide['image_alt'] ),
											'fetchpriority'=> 0 === $n ? 'high' : 'low',
											'loading'      => 0 === $n ? 'eager' : 'lazy',
										)
									);
									?>
									<?php if ( '' !== $slide['caption_badge'] || '' !== $slide['caption_text'] ) : ?>
										<div class="academic-hero__image-caption">
											<?php if ( '' !== $slide['caption_badge'] ) : ?>
												<span class="academic-hero__image-badge"><?php echo dq_icon( 'fa-solid fa-award' ); ?> <?php echo esc_html( $slide['caption_badge'] ); ?></span>
											<?php endif; ?>
											<?php if ( '' !== $slide['caption_text'] ) : ?>
												<p><?php echo esc_html( $slide['caption_text'] ); ?></p>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<?php if ( '' !== $slide['quote_text'] ) : ?>
								<div class="academic-hero__quote-box">
									<?php echo dq_icon( 'fa-solid fa-quote-left' ); ?>
									<div>
										<p class="academic-hero__quote-text">&ldquo;<?php echo esc_html( dq_strip_wrapping_quotes( $slide['quote_text'] ) ); ?>&rdquo;</p>
										<?php if ( '' !== $slide['quote_author'] ) : ?>
											<p class="academic-hero__quote-author">&mdash; <?php echo esc_html( dq_strip_leading_dash( $slide['quote_author'] ) ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( '' !== $slide['check_item_1'] || '' !== $slide['check_item_2'] ) : ?>
								<div class="academic-hero__checklist">
									<?php if ( '' !== $slide['check_item_1'] ) : ?>
										<span><?php echo dq_icon( 'fa-solid fa-circle-check' ); ?> <?php echo esc_html( $slide['check_item_1'] ); ?></span>
									<?php endif; ?>
									<?php if ( '' !== $slide['check_item_2'] ) : ?>
										<span><?php echo dq_icon( 'fa-solid fa-circle-check' ); ?> <?php echo esc_html( $slide['check_item_2'] ); ?></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php $n++; endforeach; ?>
	</div>

	<?php if ( $total > 1 ) : ?>
		<div class="academic-container academic-hero__footer">
			<div class="academic-hero__dots">
				<?php $n = 0; foreach ( $slides as $slide ) : ?>
					<button type="button" class="academic-hero__dot<?php echo 0 === $n ? ' is-active' : ''; ?>" data-slide-index="<?php echo esc_attr( $n ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Buka Slide %d', 'dzurriyyatul-academic' ), $n + 1 ) ); ?>"></button>
				<?php $n++; endforeach; ?>
				<span class="academic-hero__counter" id="heroSlideCounter" aria-live="polite">01 / <?php echo esc_html( sprintf( '%02d', $total ) ); ?></span>
			</div>
			<div class="academic-hero__arrows">
				<button type="button" class="academic-btn academic-btn--outline academic-btn--sm" id="heroPrevBottom"><?php echo dq_icon( 'fa-solid fa-arrow-left' ); ?> <span><?php esc_html_e( 'Sebelumnya', 'dzurriyyatul-academic' ); ?></span></button>
				<button type="button" class="academic-btn academic-btn--primary academic-btn--sm" id="heroNextBottom"><span><?php esc_html_e( 'Berikutnya', 'dzurriyyatul-academic' ); ?></span> <?php echo dq_icon( 'fa-solid fa-arrow-right' ); ?></button>
			</div>
		</div>
	<?php endif; ?>
</section>
