<?php
/**
 * Small template-layer helpers: body classes, pagination, breadcrumbs.
 *
 * @package DzurriyyatulAcademic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add a helper class so front-page.php sections can be targeted precisely.
 *
 * @param string[] $classes Existing body classes.
 * @return string[]
 */
function dq_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'is-academic-front';
	}
	return $classes;
}
add_filter( 'body_class', 'dq_body_classes' );

/**
 * Simple, accessible prev/next pagination for article archives.
 */
function dq_pagination() {
	$pagination = paginate_links( array(
		'prev_text' => __( '&larr; Sebelumnya', 'dzurriyyatul-academic' ),
		'next_text' => __( 'Berikutnya &rarr;', 'dzurriyyatul-academic' ),
		'type'      => 'array',
	) );

	if ( empty( $pagination ) ) {
		return;
	}

	echo '<nav class="academic-pagination" aria-label="' . esc_attr__( 'Navigasi halaman', 'dzurriyyatul-academic' ) . '"><ul class="academic-pagination__list">';
	foreach ( $pagination as $link ) {
		echo '<li class="academic-pagination__item">' . wp_kses_post( $link ) . '</li>';
	}
	echo '</ul></nav>';
}

/**
 * A minimal breadcrumb trail for non-front pages (helps both users and SEO).
 */
function dq_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="academic-breadcrumbs" aria-label="' . esc_attr__( 'Remah roti', 'dzurriyyatul-academic' ) . '">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Beranda', 'dzurriyyatul-academic' ) . '</a>';

	if ( is_singular( 'service' ) ) {
		echo ' <span aria-hidden="true">/</span> <a href="' . esc_url( get_post_type_archive_link( 'service' ) ) . '">' . esc_html__( 'Layanan', 'dzurriyyatul-academic' ) . '</a>';
	} elseif ( is_singular( 'package' ) ) {
		echo ' <span aria-hidden="true">/</span> <a href="' . esc_url( get_post_type_archive_link( 'package' ) ) . '">' . esc_html__( 'Paket', 'dzurriyyatul-academic' ) . '</a>';
	} elseif ( is_singular() ) {
		echo ' <span aria-hidden="true">/</span> <a href="' . esc_url( home_url( '/blog/' ) ) . '">' . esc_html__( 'Artikel', 'dzurriyyatul-academic' ) . '</a>';
	}

	if ( is_singular() ) {
		echo ' <span aria-hidden="true">/</span> <span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_search() ) {
		echo ' <span aria-hidden="true">/</span> <span aria-current="page">' . esc_html__( 'Hasil Pencarian', 'dzurriyyatul-academic' ) . '</span>';
	}

	echo '</nav>';
}

/**
 * Star rating renderer (1-5), used by the testimonial grid.
 *
 * @param int $rating 1-5.
 */
function dq_star_rating( $rating ) {
	$rating = max( 1, min( 5, (int) $rating ) );
	$out    = '<div class="academic-stars" role="img" aria-label="' . esc_attr(
		sprintf(
			/* translators: %d: star rating out of 5 */
			__( 'Rating %d dari 5', 'dzurriyyatul-academic' ),
			$rating
		)
	) . '">';

	for ( $i = 1; $i <= 5; $i++ ) {
		$out .= $i <= $rating ? '<i class="fa-solid fa-star" aria-hidden="true"></i>' : '<i class="fa-regular fa-star" aria-hidden="true"></i>';
	}

	$out .= '</div>';

	echo $out; // phpcs:ignore -- built entirely from escaped/int-cast parts above.
}

/**
 * Empty-state helper so a section never renders a blank/broken area when
 * its CPT has no published items yet (Master Prompt §54).
 *
 * @param string $message Text to show the admin/visitor.
 */
function dq_empty_state( $message ) {
	echo '<p class="academic-empty-state">' . esc_html( $message ) . '</p>';
}
